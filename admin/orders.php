<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="css/orders.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <?php
        session_start();
        require('../controller/db_model.php');
            
        function getTransactionsWithStatus($status) {
            global $conn;
            $stmt = $conn->prepare('SELECT t.tID, CONCAT(u.uFName, " ", u.uLName) AS uName, t.tType, t.tDateOrder, t.tDateClaim, t.tPayStatus, t.tStatus, t.tPayRemain, t.tDateOrder
                                    FROM transactions t
                                    INNER JOIN users u ON t.uID = u.uID
                                    WHERE tStatus = ?
                                    ORDER BY tID DESC');
            $stmt->execute([$status]);
            $pendings = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach($pendings as $pending){
                $stmt = $conn->prepare('SELECT c.cID, o.oID, p.pID, p.pType, p.pName, p.pPrice,  o.oQty, cfName, csSize, cInstructions, cMessage, cc.ccName, cLayers,
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
                                        WHERE o.tID = ?;');
                $stmt->execute([$pending['tID']]);
                $pendingItems = $stmt->fetchAll(PDO::FETCH_ASSOC);?>

            <tr class="<?= $pending['tType'] == 1 ? 'table-success' : 'table-warning' ?>">
                <td><?= $pending['tID']; ?></td>
                <td><?= $pending['uName']; ?></td>
                <td><ul>
                <?php
                    $totalPay = 0;
                    foreach($pendingItems as $pendingItem){ ?>
                        <li><b><?= $pendingItem['pName']?> </b><small><i class="text-muted">x<?=$pendingItem['oQty']?></i></small><?php
                        if($pendingItem['pType'] == 3){?>
                            <small>
                                <ul style="list-style-type: none;">
                                    <li>Flavor: <?= $pendingItem['cfName'] ?></li>
                                    <li>Size: <?= $pendingItem['csSize'] ?></li>
                                    <li>Color: <?= $pendingItem['ccName'] ?></li>
                                    <li>Layers: <?= $pendingItem['cLayers'] ?></li>
                                    <li>Message: <?= $pendingItem['cMessage'] ?></li>
                                    <li>Instructions: <?= $pendingItem['cInstructions'] ?></li>
                                    <?php if(file_exists('../reference-gallery/cRef_'.$pendingItem['cID'].'.jpg')){ ?>
                                        <a href="../reference-gallery/cRef_<?=$pendingItem['cID']?>.jpg" target="_blank" rel="noopener noreferrer"><small>view reference</small></a><br>
                                    <?php } ?>
                                </ul>
                            </small><?php
                        }?>
                        </li><?php
                        $totalPay += $pendingItem['total'] * $pendingItem['oQty'];
                    }?>
                </ul></td>
                <td>₱<?= $totalPay - $pending['tPayRemain']; ?></td>
                <td>₱<?= $pending['tPayRemain']; ?></td>
                <td>
                    <?php if($status == 2){ ?>
                        <input type="date" value="<?= date('Y-m-d', strtotime($pending['tDateClaim'])); ?>" class="form-control" min="<?= date('Y-m-d', strtotime($pending['tDateOrder'])); ?>">
                    <?php } else { ?>
                        <?= date('F j, Y', strtotime($pending['tDateClaim'])); ?>
                    <?php } ?>
                </td>
                <?php if($status != 0 && $status != 6){ ?>
                <td>
                    <!-- BUTTONS PER STATUS -->
                    <?php
                    switch($status){
                        case -1: ?>
                            <a class="btn btn-update btn-warning" data-transaction="<?= $pending['tID']?>">Accept Cancellation</a><?php 
                        break;
                        case 2: ?>
                            <a class="btn btn-update btn-primary" data-transaction="<?= $pending['tID']?>">Accept</a>
                            <a class="btn btn-cancel btn-danger" data-transaction="<?= $pending['tID']?>">Reject</a><?php 
                        break;
                        case 3: ?>
                            <a class="btn btn-update btn-primary" data-transaction="<?= $pending['tID']?>">Done</a><?php 
                        break;
                        case 4 || 5: ?>
                            <a class="btn btn-update btn-primary" data-transaction="<?= $pending['tID']?>">Paid and Claimed</a><?php 
                        break;
                    }
                    ?>
                </td>
                <?php } ?>
                </tr><?php 
            }
        }
    ?>

</head>
<body id="main" class="p-0">
    <?php include 'layout/navbar.php'; ?>

    <section id="main-container">
       
        <main>
            <form action="orders.php" method="POST">
            <h1 class="page-title">Manage Orders</h1>
            <ul class="tabs">
                <li class="tab-link active" data-tab="pending">Pending Orders</li>
                <li class="tab-link" data-tab="processing">Processing Orders</li>
                <li class="tab-link" data-tab="claim">For Claim</li>
                <li class="tab-link" data-tab="done">Complete Orders</li>
                <li class="tab-link" data-tab="failed">Failed Orders</li>
            </ul>
            <div class="tab-content" id="pending">
                <div class="color-enumerator" style="text-align: center;">
                    <div style="display: inline-block; width: 1rem; height: 0.7rem; background-color: #ffc107; margin-right: 0.5rem;"></div> <small>= Deposit</small>
                    <div style="display: inline-block; width: 1rem; height: 0.7rem; background-color: #28a745; margin-left: 1rem;"></div> <small>= Fully Paid</small>
                </div><br/>
                <table class="orders-table table">
                    <thead>
                        <tr class="table-dark">
                            <th>ID</th>
                            <th>Customer Name</th>
                            <th>Orders + Amount</th>
                            <th>Paid</th>
                            <th>Pending</th>
                            <th>Pickup Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // GET EACH PENDING ORDER ITEMS 
                        $pendings = getTransactionsWithStatus(2);?>
                    </tbody>
                </table>
            </div>
            <div class="tab-content" id="processing" style="display: none;">
                <table class="orders-table table">
                <thead>
                        <tr class="table-dark">
                            <th>ID</th>
                            <th>Customer Name</th>
                            <th>Orders + Amount</th>
                            <th>Paid</th>
                            <th>Pending</th>
                            <th>Pickup Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php getTransactionsWithStatus(3);?>
                    </tbody>
                </table>
            </div>
            <div class="tab-content" id="claim" style="display: none;">
                <table class="orders-table table">
                    <thead>
                        <tr class="table-dark">
                            <th>ID</th>
                            <th>Customer Name</th>
                            <th>Orders + Amount</th>
                            <th>Paid</th>
                            <th>Pending</th>
                            <th>Pickup Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php getTransactionsWithStatus(5);?>
                        <?php getTransactionsWithStatus(4);?>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>
            <div class="tab-content" id="done" style="display: none;">
                <table class="orders-table table">
                    <thead>
                        <tr class="table-dark">
                            <th>ID</th>
                            <th>Customer Name</th>
                            <th>Orders + Amount</th>
                            <th>Paid</th>
                            <th>Pending</th>
                            <th>Pickup Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php getTransactionsWithStatus(6);?>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>
            <div class="tab-content" id="failed" style="display: none;">
                <div class="sort-options">
                    <select class="sort-dropdown">
                        <option value="all">All</option>
                        <option value="month">By Month</option>
                        <option value="week">By Week</option>
                        <option value="day">By Day</option>
                    </select>
                </div>
                <table class="orders-table table">
                    <thead>
                        <tr class="table-dark">
                            <th>ID</th>
                            <th>Customer Name</th>
                            <th>Orders + Amount</th>
                            <th>Paid</th>
                            <th>Pending</th>
                            <th>Pickup Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php getTransactionsWithStatus(-1);?>
                        <?php getTransactionsWithStatus(0);?>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>
    </form>
        </main>
    </section>

    <script>
        let currentTab = 0;

        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            let statusPage = parseInt(urlParams.get('statusPage') || 2);
            const tabLinks = document.querySelectorAll('.tab-link');
            const tabContents = document.querySelectorAll('.tab-content');

            tabLinks[2].click();

            // switch (statusPage) {
            //     case 2:
            //         tabLinks[0].click();
            //         currentTab = 0;
            //         break;
            //     case 3:
            //         tabLinks[1].click();
            //         currentTab = 1;
            //         break;
            //     case 4:
            //     case 5:
            //         tabLinks[2].click();
            //         currentTab = 2;
            //         break;
            //     case 6:
            //         tabLinks[3].click();
            //         currentTab = 3;
            //         break;
            // }

            tabLinks.forEach(link => {
                link.addEventListener('click', () => {
                    tabLinks.forEach(l => l.classList.remove('active'));
                    tabContents.forEach(content => content.style.display = 'none');

                    link.classList.add('active');
                    document.getElementById(link.dataset.tab).style.display = 'block';
                    currentTab = Array.from(tabLinks).indexOf(link);
                });
            });
        });

        $(".btn-update").click(function(){
            var transactionId = $(this).attr('data-transaction');
            console.log(transactionId);
            $.ajax({
                url: '../controller/admin-order-update.php',
                type: 'POST',
                data: {
                    updateOrder: transactionId
                },
                success: function(data) {
                    window.location.href = "http://localhost/potchitos/admin/orders.php?statusPage="+currentTab;
                }
            });
        });

        $(".btn-cancel").click(function(){
            var transactionId = $(this).attr('data-transaction');
            console.log(transactionId);
            $.ajax({
                url: '../controller/admin_order_update.php',
                type: 'POST',
                data: {
                    cancelOrder: transactionId
                },
                success: function(data) {
                    window.location.href = "http://localhost/potchitos/admin/orders.php?statusPage="+currentTab;
                }
            });
        });
    </script>
</body>
</html>
