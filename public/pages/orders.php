<?php
    session_start();

    require('../../controller/db_model.php');

    $stmt = $conn->prepare('
        SELECT tID, tType, tDateOrder, tDateClaim, tPayStatus, tStatus, tPayRemain
        FROM transactions
        WHERE uID = ?
        ORDER BY tID DESC
    ');

    $stmt->execute([$_SESSION['userID']]);
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $pendingTransactions = getTransactionsByStatus($transactions, 2);
    $processingTransactions = getTransactionsByStatus($transactions, 3);
    $pickupTransactions = array_merge(
        getTransactionsByStatus($transactions, 4),
        getTransactionsByStatus($transactions, 5)
    );
    $successTransactions = getTransactionsByStatus($transactions, 6);
    $cancelledTransactions = array_merge(
        getTransactionsByStatus($transactions, -1),
        getTransactionsByStatus($transactions, 0)
    );

    function getTransactionsByStatus($transactions, $status)
    {
        return array_filter($transactions, function ($transaction) use ($status) {
            return $transaction['tStatus'] === $status;
        });
    }

    // PRINTER
    function printTransactions($conn, $transactions){
        foreach($transactions as $transaction){
            $stmt = $conn->prepare("SELECT c.cID, o.oID, p.pID, p.pType, p.pName, p.pPrice,  o.oQty, cf.cfName, cs.csSize, cc.ccName, c.cLayers, c.cInstructions, c.cMessage,
                                    CASE WHEN p.pType = 3 
                                    	THEN (p.pPrice + (COALESCE(cf.cfPrice, 0) * COALESCE(c.cLayers, 0) * COALESCE(cs.csSize, 0)))
                                    	ELSE p.pPrice
                                    	END AS total, p.pPrepTime
                                    FROM orders o
                                    INNER JOIN products p ON o.pID = p.pID
                                    LEFT JOIN cakes c ON o.oID = c.oID
                                    LEFT JOIN cakes_flavor cf ON c.cfID = cf.cfID
                                    LEFT JOIN cakes_size cs ON c.csID = cs.csID
                                    LEFT JOIN cakes_color cc ON c.ccID = cc.ccID
                                    WHERE o.tID = ?;");
            $stmt->execute([$transaction['tID']]);
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
        <div class="bg-white card mb-4 order-list shadow-sm">
            <div class="gold-members p-4">
                <div class="media">
                    <div class="media-body">
                        <!-- TOP MESSAGE -->
                        <?php switch($transaction['tStatus']){
                            case -1:
                                echo '<span class="float-right text-fade"><i class="icofont-close-circled text-danger">Please wait for the cancellation refund.</i></span>';
                                break;
                            case 0:
                                echo '<span class="float-right text-danger"><i class="icofont-close-circled text-danger">CANCELLED</i></span>';
                                break;
                            case 2:
                                echo '<span class="float-right"><i class="icofont-close-circled text-fade">WAITING CONFIRMATION...</i></span>';
                                break;
                            case 3:
                                echo '<span class="float-right text-info"><i class="icofont-check-circled text-success">Pick Up on <b>'.date('F j, Y', strtotime($transaction['tDateClaim'])).'</b></i></span>';
                                break;
                            case 4:
                                echo '<span class="float-right text-info"><i class="icofont-check-circled text-success">Ready for Pickup!</i></span>';
                                break;
                            case 6:
                                echo '<span class="float-right text-success"><i class="icofont-close-circled text-success">COMPLETED</i></span>';
                                break;
                        }?>

                        <!-- ORDER NUMBER -->
                        <h6 class="mb-2">Order #<?= $transaction['tID']?></h6>

                        <?php 
                            $pendingPayment = 0;
                            $totalPayment = 0;
                            foreach($orders as $order){ ?>
                                <!-- ORDER NUMBER -->
                                <p class="text-dark no-margin"><?= $order['pName'] ?> - <?= $order['oQty'] ?></p>

                                <!-- IF CAKE, LIST MODIFICATIONS -->
                                <?php 
                                    if($order['pType'] == 3){
                                    ?>
                                            <ul style="list-style-type: none;">

                                                <small>Flavor: <?= $order['cfName']?></small><br>
                                                <small>Size: <?= $order['csSize']?></small><br>
                                                <small>Color: <?= $order['ccName']?></small><br>
                                                <small>Layers: <?= $order['cLayers']?></small><br>
                                                <small>Message: <?= $order['cMessage']?></small><br>
                                                <small>Instruction: <?= $order['cInstructions']?></small><br>
                                                <?php
                                                $href = "../../reference-gallery/cRef_".$order['cID'].".jpg";
                                                if(file_exists($href)){
                                                ?>
                                                    <a href="<?= $href ?>" target="_blank" rel="noopener noreferrer"><small>view reference</small></a><br>
                                                <?php
                                                }
                                                ?>
                                            </ul>
                                    <?php
                                    $pendingPayment += (($transaction['tType'] == 1 ) ? $order['total']* $order['oQty'] : $order['total'] * $order['oQty']/2);
                                    }
                                    $totalPayment += $order['total']* $order['oQty'];
                                } ?>
                        <hr>
                        <div class="float-right">
                            <?php 
                                $diff = strtotime($transaction['tDateClaim']) - time();
                                $hours = floor($diff / (60 * 60));
                                // IF ORDER IS < 48 HOURS
                                // IF ORDER IS ACCEPTED
                                if($transaction['tStatus'] == 4 || $transaction['tStatus'] == 5) { ?>
                                    <a class="btn btn-sm btn-outline-secondary" href="https://www.google.com/maps/place/Potchito's+Buns+x+Cookies/@15.0776334,120.9362988,17.95z/data=!4m6!3m5!1s0x339703be6c93bf6b:0xceff0ab57a3a425c!8m2!3d15.0779421!4d120.9374447!16s%2Fg%2F11s0drmx4v?entry=ttu&g_ep=EgoyMDI0MTAwOS4wIKXMDSoASAFQAw%3D%3D" target="_blank"><i class="icofont-headphone-alt"></i> Get Directions</a><?php 
                                }
                                if($hours < 48 && $transaction['tStatus'] < 5) {
                                    if($transaction['tPayStatus'] == 1){ ?>
                                        <button class="cancel-order-late btn btn-sm btn-outline-danger" data-order-id="<?= $transaction['tID'] ?>">Cancel Order</button><?php
                                    }
                                // IF NOT LATE
                                } else if ($transaction['tStatus'] > 1 && $transaction['tStatus'] <= 3 && (strtotime($transaction['tDateClaim']) - time() > 48*60*60) ){ ?>
                                    <button class="cancel-order-valid btn btn-sm btn-outline-danger" href="#" data-bs-toggle="modal" data-order-id="<?= $transaction['tID'] ?>" data-bs-target="#cancelOrderModal"><i class="icofont-headphone-alt"></i> Cancel Order</button>
                                    <!-- <button class="btn btn-sm btn-outline-primary" href="#" data-bs-toggle="modal" data-order-id="<?= $transaction['tID'] ?>" data-bs-target="">
                                        <i class="icofont-headphone-alt"></i> Edit Orders
                                    </button> -->
                                    <?php 
                                }
                                // DONE DEPOSITS
                                if($transaction['tType'] == 2 && $transaction['tStatus'] == 4 || $transaction['tStatus'] == 5){ 
                                    // READY FOR PICK UP1    DEPOSIT NOT PAID
                                    if($transaction['tStatus'] == 4){ ?>
                                        <a class="pay-remaining btn btn-sm btn-outline-primary" data-order-id="<?= $transaction['tID']?>"><i class="icofont-headphone-alt"></i> Pay with GCASH</a><?php 
                                    } ?><?php 
                                }?>
                        </div>
                        <?php if($transaction['tPayStatus'] == 1 && $transaction['tStatus'] < 6 && $transaction['tStatus'] > 0 && $transaction['tType'] == 2){ ?>
                            <p class="mb-0 text-black text-primary pt-2"><span class="text-black font-weight-bold"> Pending Balance:</span> <?= $transaction['tPayRemain'] ?></p>
                            <small>Only payable once pick up is ready</small>
                        <?php }else{ ?>
                            <p class="mb-0 text-primary pt-2"><span class="font-weight-bold text-primary"><h2>₱<?= $totalPayment ?></h2></span></p>
                            <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } 
    }?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <style>
        .no-margin {
            margin: 0;
        }
        .custom-disabled {
            background-color: #d3d3d3; 
            color: #a9a9a9;
            border: 1px solid #a9a9a9; 
            pointer-events: none; 
            cursor: not-allowed;
        }

        .custom-disabled:hover {
            background-color: #d3d3d3; 
            color: #a9a9a9; 
            border: 1px solid #a9a9a9; 
        }
        .nav-pills .nav-link {
            border-radius: 0.5rem; 
        }

        .nav-pills .nav-link.active {
            background-color: #254bc5; 
            color: white;
        }

        .nav-pills .nav-link:hover {
            background-color: #e1e1e1; 
            color: #254bc5; 
        }

    </style>
</head>
<body>
    
    <?php include 'layout/header.php'; ?>

    <section class="order-container">
        <div class="container">
            <div class="row">
               <!-- Navigation Bar -->
                <div class="col-md-12 mt-2">
                    <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-all-tab" data-bs-toggle="pill" href="#all" role="tab" aria-controls="pills-all" aria-selected="true">ALL</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-pending-tab" data-bs-toggle="pill" href="#pending" role="tab" aria-controls="pills-pending" aria-selected="false">Pending</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-processing-tab" data-bs-toggle="pill" href="#processing" role="tab" aria-controls="pills-processing" aria-selected="false">Processing</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-pickup-tab" data-bs-toggle="pill" href="#pickup" role="tab" aria-controls="pills-pickup" aria-selected="false">For Pick Up</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-success-tab" data-bs-toggle="pill" href="#success" role="tab" aria-controls="pills-success" aria-selected="false">Success</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-cancelled-tab" data-bs-toggle="pill" href="#cancelled" role="tab" aria-controls="pills-cancelled" aria-selected="false">Cancelled</a>
                        </li>
                    </ul>
                </div>

                <!-- Tab Content -->
                <div class="col-md-12">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="pills-all-tab">
                            <h4 class="font-weight-bold mt-0 mb-4">All Orders</h4>

                            <?php 
                                printTransactions($conn, $pickupTransactions);
                                printTransactions($conn, $processingTransactions);
                                printTransactions($conn, $pendingTransactions);
                            ?>

                        </div>

                        <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pills-pending-tab">
                            <?php 
                                printTransactions($conn, $pendingTransactions);
                            ?>
                        </div>
                        <div class="tab-pane fade" id="processing" role="tabpanel" aria-labelledby="pills-processing-tab">
                            <?php 
                                printTransactions($conn, $processingTransactions);
                            ?>
                        </div>
                        <div class="tab-pane fade" id="pickup" role="tabpanel" aria-labelledby="pills-pickup-tab">
                            <?php 
                                printTransactions($conn, $pickupTransactions);
                            ?>
                        </div>
                        <div class="tab-pane fade" id="success" role="tabpanel" aria-labelledby="pills-success-tab">
                            <?php 
                                printTransactions($conn, $successTransactions);
                            ?>
                        </div>
                        <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="pills-cancelled-tab">
                            <?php 
                                printTransactions($conn, $cancelledTransactions);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- ORDER CANCELLATION MODAL -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"> <!-- Add modal-dialog-centered class -->
        <form class="modal-content" action="../../controller/client_cancel.php" method="post">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelOrderModalLabel">Cancel Order?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="hidden" id="transactionID" name="transactionID">
                    <label for="cancellationReason" class="form-label">Reason for cancellation:</label>
                    <select class="form-select" id="cancellationReason" name="cancellationReason" required>
                        <option value="" disabled selected>Choose a reason</option>
                        <option value="duplicate">Duplicate Order</option>
                        <option value="fraudulent">Fraudulent Order</option>
                        <option value="others">Other Reasons</option>
                    </select>
                </div>
                <div class="alert alert-danger" role="alert">
                    You will be provided a refund, but this can't be undone.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger" id="confirmCancel">Confirm</button>
            </div>
        </form>
    </div>
</div>

<!-- ORDER LATE CANCELLATION MODAL -->
<div class="modal fade" id="cancelOrderLateModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"> <!-- Add modal-dialog-centered class -->
        <form class="modal-content" action="../../controller/client_cancel_checkout.php" method="get">
            <div class="modal-header">
                <h5 class="modal-title">Late Cancellation!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="hidden" id="cancelTransactionID" name="transactionID">
                </div>
                <div class="alert alert-danger" role="alert">
                    <b>Your order is over our 48-hour cancellation deadline!</b><br><br>
                    By proceeding, you will...<br/>
                    <ul>
                        <li>be redirected to the payment page to pay for the remaining balance to pay for the order.</li>
                        <li>not be able to pick up the product as production will be cancelled.</li>
                    </ul>
                    See our <a href="terms-and-conditions.php">Terms and Services</a> for more information.
                    <br/><br/>Thank you for understanding.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger" id="confirmCancelLate">Pay the Remaining Balance</button>
            </div>
        </form>
    </div>
</div>

<!-- ORDER EDITING MODAL -->

    <script>
        $('.cancel-order-valid').on('click', function() {
            var orderId = $(this).data('order-id');
            $('#transactionID').val(orderId);
            $('#cancelOrderModal').modal('show');   
        });

        $('.cancel-order-late').on('click', function() {
            var orderId = $(this).data('order-id');
            $('#cancelTransactionID').val(orderId);
            $('#cancelOrderLateModal').modal('show');
        });

        $('.pay-remaining').on('click', function() {
            var orderId = $(this).data('order-id');
            window.location.href = '../../controller/pay_remaining.php?transactionID=' + orderId;
        });
    </script>
</body>
</html>