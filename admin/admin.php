<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <?php 
        require('../controller/db_model.php');
        $stmt = $conn->prepare("
            SELECT t.tID, t.tDateOrder, t.tStatus
            FROM transactions t
            INNER JOIN users u ON t.uID = u.uID
            WHERE tStatus = 6
            ORDER BY tID DESC
        ");

        $stmt->execute();
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            color: #333;
            overflow-x: hidden;
        }

        main {
            flex: 1;
            padding: 20px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(2, auto);
            gap: 15px;
            margin: 3vw;
        }

        .card {
            background-color: #ffffff;
            padding: 15px;
            text-align: left;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .large-card {
            grid-column: span 2;
            grid-row: span 2;
            height: 400px;
        }

        .other-metrics-card {
            grid-row: span 2;
            height: 400px;
            overflow: hidden;
        }

        .card h2 {
            margin: 0;
            font-size: 1.25rem;
            color: #4A90E2;
        }

        .card .numbers {
            margin-top: 8px;
            font-size: 1rem;
            font-weight: 600;
            color: #333;
        }

        .card p {
            margin-top: 4px;
            font-size: 0.9rem;
            color: #777;
        }

        .button-group {
            margin: 15px 0; 
        }

        .button {
            background-color: #4A90E2; 
            color: white;
            border: none; 
            border-radius: 4px; 
            padding: 8px 12px;
            margin-right: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #357ABD; 
        }

        .chart-placeholder {
            height: 60%; 
            position: relative; 
            margin-top: 15px; 
        }

        @media (max-width: 768px) {
            .grid-container {
                grid-template-columns: 1fr; 
            }
        }

        .product-list {
            max-height: 300px; /* Set max height for scrolling */
            overflow-y: auto; /* Enable scrolling */
            margin-top: 10px; /* Space between header and product list */
            border-top: 1px solid #eee; /* Add border for separation */
        }

        table {
            width: 100%; /* Full width for the table */
            border-collapse: collapse; /* Collapse borders */
        }

        th, td {
            padding: 10px; /* Padding for table cells */
            text-align: left; /* Align text to the left */
            border-bottom: 1px solid #eee; /* Light border between rows */
        }

        th {
            background-color: #f2f2f2; /* Light background for header */
        }
    </style>
</head>
<body id="main" class="p-0">
    <?php include 'layout/navbar.php'; ?>
    <section id="main-container">
        <main>
            <section class="analytics-container">
        <div class="grid-container">
            <?php
                $stmt = $conn->prepare('SELECT COUNT(*) AS pendingCount FROM transactions WHERE tStatus = 2');
                $stmt->execute();
                $pendingCount = $stmt->fetchColumn();
            ?>
            <div class="card">
                <h1 class="h1"><?= $pendingCount ?></h1>
                <h2>Pending Orders</h2>
            </div>
            <?php
                $stmt = $conn->prepare('SELECT COUNT(*) AS pendingCount FROM transactions WHERE tStatus = 3');
                $stmt->execute();
                $processingCount = $stmt->fetchColumn();
            ?>
            <div class="card">
                <h1 class="h1"><?= $processingCount ?></h1>
                <h2>Processing Orders</h2>
            </div>
            <?php
                $stmt = $conn->prepare('SELECT COUNT(*) AS pendingCount 
                                        FROM transactions 
                                        WHERE (tStatus = 4 OR tStatus = 5) 
                                        AND tDateClaim = CURDATE()');
                $stmt->execute();
                $claimCount = $stmt->fetchColumn();
            ?>
            <div class="card">
                <h1 class="h1"><?= $claimCount ?></h1>
                <h2>For Claiming Today</h2>
            </div>
            <div class="card large-card">
                <h2>Sales Summary</h2>
                <div class="button-group">
                    <button class="button" onclick="updateChart('week')">This Week</button>
                    <button class="button" onclick="updateChart('month')">This Month</button>
                    <button class="button" onclick="updateChart('year')">This Year</button>
                </div>
                <div class="chart-placeholder">
                    <canvas id="salesChart"></canvas> 
                </div>
            </div>
            <div class="card other-metrics-card">
                <h2>Top Products</h2>
                <?php
                    $stmt = $conn->prepare("
                        SELECT 
                            p.pName, 
                            SUM(o.oQty) AS total_sold
                        FROM 
                            products p
                            JOIN orders o ON p.pID = o.pID
                            JOIN transactions t ON o.tID = t.tID
                        WHERE 
                            t.tStatus = 6
                        GROUP BY 
                            p.pID
                        ORDER BY total_sold DESC
                    ");
                    $stmt->execute();
                    $topProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <div class="product-list">
                    <table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty Sold</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($topProducts as $product) { ?>
                            <tr>
                                <td><?= $product['pName']?></td>
                                <td><?= $product['total_sold'] ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
            </section>
    </main>
    </section>

    <script>
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
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        const today = new Date().toLocaleDateString(undefined, options);
        document.querySelectorAll('#date-today').forEach(el => {
            el.textContent = today;
        });

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

        function updateChart(period) {
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
    </script>
</body>
</html>
