<?php
    session_start();
    require_once("../controller/db_model.php");
?>

<!-- WEEKLY REPORT -->
<?php
    $stmt = $conn->prepare("
        SELECT 
            CASE DAYOFWEEK(d.date)
                WHEN 1 THEN 'Sunday'
                WHEN 2 THEN 'Monday'
                WHEN 3 THEN 'Tuesday'
                WHEN 4 THEN 'Wednesday'
                WHEN 5 THEN 'Thursday'
                WHEN 6 THEN 'Friday'
                WHEN 7 THEN 'Saturday'
            END AS day_of_week,
            IFNULL(t.transactions, 0) AS transactions
        FROM
            (
                SELECT CURDATE() - INTERVAL n DAY AS date
                FROM (
                    SELECT 0 AS n
                    UNION ALL SELECT 1
                    UNION ALL SELECT 2
                    UNION ALL SELECT 3
                    UNION ALL SELECT 4
                    UNION ALL SELECT 5
                    UNION ALL SELECT 6
                ) numbers
            ) d
            LEFT JOIN
            (
                SELECT
                    DATE(tDateOrder) AS date,
                    COUNT(*) AS transactions
                FROM
                    transactions
                WHERE
                    tDateOrder >= CURDATE() - INTERVAL 6 DAY
                    AND tStatus = 6
                GROUP BY
                    DATE(tDateOrder)
            ) t
            ON d.date = t.date
        ORDER BY
            d.date;

    ");
    $stmt->execute();
    $weeklyReport = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    // MONTHLY REPORT
    $stmt = $conn->prepare("
        SELECT 
            CONCAT(
                DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL (n.n - 1) WEEK) + INTERVAL 1 - WEEKDAY(CURDATE()) DAY, '%b %d'),
                ' to ',
                DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL (n.n - 1) WEEK) + INTERVAL 7 - WEEKDAY(CURDATE()) DAY, '%b %d')
            ) AS week_span,
            COALESCE(COUNT(t.tDateOrder), 0) AS total_sold
        FROM 
            (
                SELECT 1 AS n
                UNION ALL SELECT 2
                UNION ALL SELECT 3
                UNION ALL SELECT 4
            ) n
            LEFT JOIN transactions t
                ON WEEK(t.tDateOrder, 1) = WEEK(DATE_SUB(CURDATE(), INTERVAL (n.n - 1) WEEK) + INTERVAL 1 - WEEKDAY(CURDATE()) DAY, 1)
        WHERE 
            t.tDateOrder IS NULL OR t.tDateOrder >= DATE_SUB(CURDATE(), INTERVAL 4 WEEK)
        GROUP BY 
            n.n
        ORDER BY 
            n.n DESC
    ");
    $stmt->execute();
    $monthlyReport = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    // YEARLY REPORT
    $stmt = $conn->prepare("
        SELECT 
            MONTHNAME(d.month) AS month_name,
            IFNULL(t.transactions, 0) AS transactions
        FROM
            (
                SELECT DATE_FORMAT(CURDATE() - INTERVAL (12 - n) MONTH, '%Y-%m-01') AS month
                FROM (
                    SELECT 1 AS n
                    UNION ALL SELECT 2
                    UNION ALL SELECT 3
                    UNION ALL SELECT 4
                    UNION ALL SELECT 5
                    UNION ALL SELECT 6
                    UNION ALL SELECT 7
                    UNION ALL SELECT 8
                    UNION ALL SELECT 9
                    UNION ALL SELECT 10
                    UNION ALL SELECT 11
                    UNION ALL SELECT 12
                ) numbers
            ) d
            LEFT JOIN
            (
                SELECT
                    DATE_FORMAT(tDateOrder, '%Y-%m-01') AS month,
                    COUNT(*) AS transactions
                FROM
                    transactions
                WHERE
                    YEAR(tDateOrder) = YEAR(CURDATE())
                    AND tStatus = 6
                GROUP BY
                    DATE_FORMAT(tDateOrder, '%Y-%m-01')
            ) t
            ON d.month = t.month
        ORDER BY
            d.month;

    ");
    $stmt->execute();
    $yearlyReport = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Generation</title>
    <link rel="stylesheet" href="css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9; 
            color: #333; 
            margin: 0;
            padding: 20px; 
        }

        .container {
            max-width: 100%; 
            padding: 20px;
            background-color: #fff; 
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 20px;
            color: #4A90E2; 
        }

        .tabs {
            display: flex;
            margin-bottom: 10px;
        }

        .tab {
            flex: 1; 
            cursor: pointer;
            padding: 10px;
            background-color: #e7f1ff; 
            border-radius: 5px;
            margin-right: 10px; 
            transition: background-color 0.3s;
            text-align: center;
        }

        .tab:hover {
            background-color: #d0e3ff; 
        }

        .tab.active {
            background-color: #4A90E2; 
            color: white; 
        }

        .report-section {
            display: none; 
            margin-bottom: 20px;
        }

        .report-section.active {
            display: block; 
        }

        .chart-placeholder {
            height: 300px; 
            margin-top: 15px; 
            position: relative; 
        }

        .button-group {
            margin: 15px 0; 
            display: flex; 
            justify-content: flex-end; 
        }
        .max-width-group{
            display: flex;
            justify-content: space-between;
        }
        .generate-button {
            background-color: #4A90E2; 
            color: white; 
            border: none; 
            border-radius: 4px; 
            padding: 10px 15px; 
            cursor: pointer; 
            transition: background-color 0.3s;
        }

        .generate-button:hover {
            background-color: #357ABD; 
        }

        /* New button styles for sales period selection */
        .sales-period-buttons {
            margin-bottom: 10px;
        }

        .sales-period-button {
            background-color: #e7f1ff; 
            border: none; 
            border-radius: 4px; 
            padding: 8px 12px; 
            margin-right: 5px; 
            cursor: pointer; 
            transition: background-color 0.3s;
        }

        .sales-period-button:hover {
            background-color: #d0e3ff; 
        }

        table {
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }

        th, td {
            padding: 8px; 
            text-align: left; 
            border-bottom: 1px solid #eee; 
        }

        th {
            background-color: #f2f2f2; 
        }

        /* Pagination Styles */
        .pagination {
            display: flex;
            justify-content: flex-end; 
            margin-top: 10px;
        }

        .pagination button {
            background-color: #e7f1ff; 
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 5px 10px;
            margin-left: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .pagination button:hover {
            background-color: #d0e3ff; 
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body id="main" class="p-0">
<?php include 'layout/navbar.php'; ?>
    <div class="main-container p-4">
        <h2>Report Generation</h2>
        <div class="tabs">
            <div class="tab active" onclick="showReport('sales')">Sales Report</div>
            <div class="tab" onclick="showReport('products')">Products Report</div>
            <div class="tab" onclick="showReport('orders')">Orders Report</div>
        </div>

        <!-- Sales Report Section -->
        <div class="report-section active" id="sales">
            <div class="sales-period-buttons">
                <button class="sales-period-button" onclick="updateSalesChart('week')">Weekly Sales</button>
                <button class="sales-period-button" onclick="updateSalesChart('month')">Monthly Sales</button>
                <button class="sales-period-button" onclick="updateSalesChart('year')">Yearly Sales</button>
            </div>
            <div class="chart-placeholder">
                <canvas id="salesChart"></canvas> 
            </div>
            <div class="button-group">
            <a href="genrep_annual.php" class="generate-button btn btn-primary">Print Report</a>
            </div>
        </div>

        <?php
            // CAKES
            $stmt = $conn->prepare("
                SELECT 
                    p.pName, 
                    p.pType,
                    SUM(o.oQty) AS total_quantity_sold,
                    SUM(
                        CASE 
                            WHEN p.pType = 3 
                                THEN (p.pPrice + (COALESCE(cf.cfPrice, 0) * COALESCE(c.cLayers, 0) * COALESCE(cs.csSize, 0))) * o.oQty
                            ELSE p.pPrice * o.oQty
                        END
                    ) AS total_revenue
                FROM 
                    products p
                INNER JOIN 
                    orders o ON p.pID = o.pID
                LEFT JOIN 
                    cakes c ON o.oID = c.oID
                LEFT JOIN 
                    cakes_flavor cf ON c.cfID = cf.cfID
                LEFT JOIN 
                    cakes_size cs ON c.csID = cs.csID
                LEFT JOIN 
                    cakes_color cc ON c.ccID = cc.ccID
                INNER JOIN 
                    transactions t ON o.tID = t.tID
                WHERE 
                    t.tStatus = 6
                GROUP BY 
                    p.pID
                ORDER BY 
                    total_quantity_sold DESC;
            ");
            $stmt->execute();
            $soldProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        ?>
        <!-- Products Report Section -->
        <div class="report-section" id="products">
            <div class="button-group max-width-group">
                <!-- <div class="filter-button-container">
                    <button class="sales-period-button" onclick="filterProducts('all')">All Products</button>
                    <button class="sales-period-button" onclick="filterProducts('cakes')">Cakes</button>
                    <button class="sales-period-button" onclick="filterProducts('pastries')">Pastries</button>
                </div> -->
                <a href="genrep_products.php" class="generate-button btn btn-primary">Print Report</a>
            </div>
            <table id="productsTable">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity Sold</th>
                        <th>Total Sold</th>
                    </tr>
                </thead>
                <tbody><?php
                    foreach ($soldProducts as $product) {
                    ?><tr>
                        <td><?= $product['pName']?></td>
                        <td><?= $product['total_quantity_sold']?></td>
                        <td><?= $product['total_revenue']?></td>
                        <td></td>
                    </tr><?php
                    }
                ?></tbody>
            </table>
            <div class="pagination">
                <button>Previous</button>
                <button>Next</button>
            </div>
        </div>

        <!-- ORDERS ============================================================================================================================================================================ -->

        <!-- Orders Report Section -->
        <div class="report-section" id="orders">
            <div class="button-group">
                <a href="genrep_orders.php" class="generate-button btn btn-primary">Print Report</a>
            </div>
            <table id="ordersTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Products Bought</th>
                        <th>Date and Time Ordered</th>
                        <th>Payment Method</th>
                        <th>Deposit or Fully Paid</th>
                        <th>Total Paid</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $orderslimit = 30; // Number of records per page
                    $orderspage = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
                    
                    $ordersoffset = ($orderspage - 1) * $orderslimit;
                    
                    $stmt = $conn->prepare('SELECT t.tID, CONCAT(u.uFName, " ", u.uLName) AS uName, t.tType, t.tDateOrder, t.tDateClaim, t.tPayStatus, t.tStatus, t.tPayRemain, t.tDateOrder
                                            FROM transactions t
                                            INNER JOIN users u ON t.uID = u.uID
                                            ORDER BY tID DESC
                                            LIMIT :limit OFFSET :offset');
                    
                    // Bind the parameters as integers
                    $stmt->bindValue(':limit', $orderslimit, PDO::PARAM_INT);
                    $stmt->bindValue(':offset', $ordersoffset, PDO::PARAM_INT);
                    
                    $stmt->execute();
                    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    $currentMonth = '';
                    
                    foreach($transactions as $transaction){
                        $orderDate = date('F Y', strtotime($transaction['tDateOrder']));
                        if ($currentMonth != $orderDate) {
                            $currentMonth = $orderDate;
                            echo '<tr><td colspan="6"><strong>' . $currentMonth . '</strong></td></tr>';
                        }
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
                        $stmt->execute([$transaction['tID']]);
                        $transactionItems = $stmt->fetchAll(PDO::FETCH_ASSOC);?>
                    <tr>
                        <td><?=$transaction['tID']?></td>
                        <td><ul>
                        <?php
                            $totalPay = 0;
                            foreach($transactionItems as $transactionItem){ ?>
                                <li><b><?= $transactionItem['pName']?> </b><small><i class="text-muted">x<?=$transactionItem['oQty']?></i></small><?php
                                if($transactionItem['pType'] == 3){?>
                                    <small>
                                        <ul style="list-style-type: none;">
                                            <li>Flavor: <?= $transactionItem['cfName'] ?></li>
                                            <li>Size: <?= $transactionItem['csSize'] ?></li>
                                            <li>Color: <?= $transactionItem['ccName'] ?></li>
                                            <li>Layers: <?= $transactionItem['cLayers'] ?></li>
                                            <li>Message: <?= $transactionItem['cMessage'] ?></li>
                                            <li>Instructions: <?= $transactionItem['cInstructions'] ?></li>
                                            <?php if(file_exists('../reference-gallery/cRef_'.$transactionItem['cID'].'.jpg')){ ?>
                                                <a href="../reference-gallery/cRef_<?=$transactionItem['cID']?>.jpg" target="_blank" rel="noopener noreferrer"><small>view reference</small></a><br>
                                            <?php } ?>
                                        </ul>
                                    </small><?php
                                }?>
                                </li><?php
                                $totalPay += $transactionItem['total'] * $transactionItem['oQty'];
                            }?>
                        </ul></td>
                        <td><?= date('F j, Y, g:i A', strtotime($transaction['tDateOrder'])) ?></td>
                        <td>GCash</td>
                        <td><?= $transaction['tType'] == 1 ? 'Fully Paid' : ($transaction['tType'] == 2 ? 'Deposit' : '') ?></td>
                        <td>Php <?= number_format($totalPay, 2) ?></td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                <li class="page-item <?php echo ($page <= 1 ? 'disabled' : ''); ?>">
                    <a class="page-link" href="?page=<?php echo ($page - 1); ?>" tabindex="-1">Previous</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo ($page + 1); ?>">Next</a>
                </li>
                </ul>
            </nav>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        let salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['<?= implode("', '", array_keys($weeklyReport)) ?>'], 
                datasets: [{
                    label: 'Sales (Php)',
                    data: [<?= implode(', ', array_values($weeklyReport)) ?>], 
                    backgroundColor: '#4A90E2', 
                    borderColor: '#4A90E2', 
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, 
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        function updateSalesChart(period) {
            let newData = [];
            let newLabels = [];

            switch (period) {
                case 'week':
                    newData = [<?= implode(', ', array_values($weeklyReport)) ?>];
                    newLabels = ['<?= implode("', '", array_keys($weeklyReport)) ?>'];
                    break;
                case 'month':
                    newData = [<?= implode(', ', array_values($monthlyReport)) ?>];
                    newLabels = ['<?= implode("', '", array_keys($monthlyReport)) ?>'];
                    break;
                case 'year':
                    newData = [<?= implode(', ', array_values($yearlyReport)) ?>];
                    newLabels = ['<?= implode("', '", array_keys($yearlyReport)) ?>']; 
                    break;
            }

            salesChart.data.labels = newLabels;
            salesChart.data.datasets[0].data = newData;
            salesChart.update();
        }

        function showReport(reportType) {
            const reports = document.querySelectorAll('.report-section');
            const tabs = document.querySelectorAll('.tab');

            reports.forEach(report => {
                report.classList.remove('active');
            });

            tabs.forEach(tab => {
                tab.classList.remove('active');
            });

            document.getElementById(reportType).classList.add('active');
            document.querySelector(`.tab[onclick*="${reportType}"]`).classList.add('active');
        }
    </script>
</body>
</html>
