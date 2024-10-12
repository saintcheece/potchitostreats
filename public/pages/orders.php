<?php
    session_start();
    require('../../controller/db_model.php');

    // GET ALL TRANSACTIONS
    $stmt = $conn->prepare("SELECT tID, tType, tDateOrder, tDateClaim, tPayStatus, tStatus, tPayRemain FROM transactions WHERE uID = ? ORDER BY tID DESC");
    $stmt->execute([$_SESSION['userID']]);
    $allTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // GET PENDING TRANSACTION IDS
    $pendingTransactions = getTransactionByStatus($allTransactions, 2);

    // GET PROCESSING TRANSACTION IDS
    $processingTransactions = getTransactionByStatus($allTransactions, 3);

    // GET READY FOR PICKUP TRANSACTION IDS
    $pickupTransactions = getTransactionByStatus($allTransactions, 4);

    // GET SUCCESS TRANSACTION IDS
    $successTransactions = getTransactionByStatus($allTransactions, 5);

    // GET CANCELLED TRANSACTION IDS
    $cancelledTransactions = getTransactionByStatus($allTransactions, 0);

    // GET PICKUP TRANSACTION IDS
    function getTransactionByStatus($transactions, $status){
        $transactionList = [];
        foreach($transactions as $transaction){
            if($transaction['tStatus'] == $status){
                array_push($transactionList, $transaction);
            }
        }
        return $transactionList;
    }

    // PRINTER
    function printTransactions($conn, $transactions){
        foreach($transactions as $transaction){
            $stmt = $conn->prepare("SELECT p.pType, p.pName, p.pPrice, o.oQty, cf.cfPrice, cf.cfName, cs.csSize, cs.csSize, c.cMessage, c.cInstructions
                                    FROM transactions t
                                    INNER JOIN orders o ON t.tID = o.tID
                                    INNER JOIN products p ON o.pID = p.pID
                                    LEFT JOIN cakes c ON t.tID = c.tID AND p.pID = c.pID
                                    INNER JOIN cakes_flavor cf ON c.cfID = cf.cfID
                                    INNER JOIN cakes_size cs ON c.csID = cs.csID
                                    WHERE t.tID = ?;");
            $stmt->execute([$transaction['tID']]);
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
        <div class="bg-white card mb-4 order-list shadow-sm">
            <div class="gold-members p-4">
                <div class="media">
                    <div class="media-body">
                        <?php if($transaction['tStatus'] != 0 && $transaction['tStatus'] > 5){ ?>
                            <span class="float-right text-info"><i class="icofont-check-circled text-success">Pick Up on <b><?= date('F j, Y', strtotime($transaction['tDateClaim']))?></b></i></span>
                        <?php }else if($transaction['tStatus'] == 5){ ?>
                            <span class="float-right text-success"><i class="icofont-close-circled text-success">COMPLETED</i></span>
                        <?php } else{?>
                            <span class="float-right text-danger"><i class="icofont-close-circled text-danger">CANCELLED</i></span>
                        <?php }?>
                        <h6 class="mb-2">Order #<?= $transaction['tID']?></h6>
                        <?php 
                            $pendingPayment = 0;
                            $totalPayment = 0;
                            foreach($orders as $order){ ?>
                                <p class="text-dark no-margin"><?= $order['pName'] ?> - <?= $order['oQty'] ?></p>
                                <?php 
                                    if($order['pType'] == 3){
                                    ?>
                                        <small>
                                            <ul style="list-style-type: none;">
                                                <li>Flavor: <?= $order['cfName'] ?></li>
                                                <li>Size: <?= $order['csSize'] ?></li>
                                                <li>Flavor: <?= $order['cMessage'] ?></li>
                                                <li>Flavor: <?= $order['cInstructions'] ?></li>
                                            </ul>
                                    </small>
                                    <?php
                                    $pendingPayment += (($transaction['tType'] == 1 ) ? $order['pPrice']* $order['oQty'] : $order['pPrice'] * $order['oQty']/2);
                                    }
                                    $totalPayment += $order['pPrice']* $order['oQty'];
                                } ?>
                        <hr> 
                        <div class="float-right">
                            <?php 
                                $diff = strtotime($transaction['tDateClaim']) - time();
                                $hours = floor($diff / (60 * 60));
                                if($hours < 48){ ?>
                                    <button class="btn btn-sm btn-outline-secondary" style="cursor: not-allowed;" disabled>
                                        <i class="icofont-headphone-alt"></i> Cancel Order
                                    </button>
                                <?php } else if ($transaction['tStatus'] != 0 && $transaction['tStatus'] < 3){ ?>
                                    <button class="cancel-order btn btn-sm btn-outline-danger" href="#" data-bs-toggle="modal" data-order-id="<?= $transaction['tID'] ?>" data-bs-target="#cancelOrderModal">
                                        <i class="icofont-headphone-alt"></i> Cancel Order
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" href="#" data-bs-toggle="modal" data-order-id="<?= $transaction['tID'] ?>" data-bs-target="">
                                        <i class="icofont-headphone-alt"></i> Edit Orders
                                    </button>
                                <?php }
                            ?>
                            <?php if($transaction['tType'] == 2 && $transaction['tStatus'] == 4){?>
                                <a class="btn btn-sm btn-outline-primary" href="#"><i class="icofont-headphone-alt"></i> Pay with GCASH</a>
                            <?php }?>
                        </div>
                        <?php if($transaction['tPayStatus'] == 1 && $transaction['tStatus'] < 5 && $transaction['tStatus'] > 0){ ?>
                            <p class="mb-0 text-black text-primary pt-2"><span class="text-black font-weight-bold"> Pending Balance:</span> <?= $transaction['tPayRemain'] ?></p>
                            <small>Only payable once pick up is ready</small>
                        <?php }else{ ?>
                            <p class="mb-0 text-primary pt-2"><span class="font-weight-bold text-primary"><h2>₱<?= $totalPayment?></h2></span></p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } 
    }?>
<!DOCTYPE html>
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
                                printTransactions($conn, $allTransactions);
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

    <!-- Modal -->
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
                    <select class="form-select" id="cancellationReason" name="cancellationReason">
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

    <script>
        $('.cancel-order').on('click', function() {
            var orderId = $(this).data('order-id');
            $('#transactionID').val(orderId);
            $('#cancelOrderModal').modal('show');
        });
    </script>
</body>
</html>
