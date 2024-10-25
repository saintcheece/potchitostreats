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
    /* General Styles */
    body {
    margin: 0;
    font-family: Arial, sans-serif;
    color: #333;
    overflow-x: hidden;
}

.top-navigation-bar {
    background-color: #343a40;
    color: #f8f9fa;
    padding: 5px 15px;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.top-navigation-bar .logo {
    font-size: 20px;
    color: #f8f9fa;
}

.top-navigation-bar .menu {
    display: flex;
    flex-grow: 1;
    justify-content: center;
}

.top-navigation-bar .menu ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
}

.top-navigation-bar .menu ul li {
    position: relative;
    margin: 0 8px;
}

.top-navigation-bar .menu ul li a {
    color: #f8f9fa;
    text-decoration: none;
    font-size: 16px;
    padding: 8px;
    border-radius: 5px;
}

.top-navigation-bar .menu ul li a:hover {
    background-color: #495057;
}

.top-navigation-bar .dropdown:hover .dropdown-content {
    display: block;
}

.top-navigation-bar .dropdown-content {
    display: none;
    position: absolute;
    background-color: #343a40;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}

.top-navigation-bar .dropdown-content a {
    color: #f8f9fa;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.top-navigation-bar .dropdown-content a:hover {
    background-color: #495057;
}

.top-navigation-bar .user-actions {
    display: flex;
    align-items: center;
}

.top-navigation-bar .user-actions .email {
    margin-right: 15px;
}

.top-navigation-bar .user-actions .logout {
    padding: 8px 15px;
    background-color: #d15454;
    color: #fff;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    text-align: center;
    display: inline-block;
}






.grid-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-template-rows: auto auto;
    gap: 20px;
}

.card {
    background-color: #ffffff;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.total-sales,
.total-users,
.date-today {
    grid-column: span 1;
}

.bar-chart-container {
    grid-column: span 2;
}

.pie-chart-container {
    grid-column: span 1;
}

#barChart,
#pieChart {
    width: 100% !important;
}


    </style>
</head>
<body>
    <!-- Add all page content inside this div if you want the side nav to push page content to the right (not used if you only want the sidenav to sit on top of the page -->
    <?php include 'layout/navbar.php'; ?>
    <section id="main-container">
        <main>
            <section class="analytics-container">
                <div class="grid-container">
                    <!-- Top Row Cards -->
                    <div class="card total-sales">
                        <?php
                            $totalSales = 0;
                            $salesCount = [];
                            foreach($transactions as $transaction){

                                $stmt = $conn->prepare("
                                    SELECT p.pName, SUM(o.oQty) as totalSold
                                    FROM orders o
                                    INNER JOIN products p ON o.pID = p.pID
                                    GROUP BY p.pName
                                ");
                                $stmt->execute();
                                $productSales = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($productSales as $product) {
                                    if (isset($salesCount[$product['pName']])) {
                                        $salesCount[$product['pName']] += $product['totalSold'];
                                    } else {
                                        $salesCount[$product['pName']] = $product['totalSold'];
                                    }
                                }

                                $stmt = $conn->prepare("
                                    SELECT p.pPrice,  o.oQty,
                                    CASE WHEN p.pType = 3 
                                        THEN (p.pPrice + COALESCE(cf.cfPrice, 0) + COALESCE(cs.csPrice, 0))
                                        ELSE p.pPrice
                                        END AS total, p.pPrepTime
                                    FROM orders o
                                    INNER JOIN products p ON o.pID = p.pID
                                    LEFT JOIN cakes c ON o.oID = c.oID
                                    LEFT JOIN cakes_flavor cf ON c.cfID = cf.cfID
                                    LEFT JOIN cakes_size cs ON c.csID = cs.csID
                                    WHERE o.tID = ?
                                ");
                                $stmt->execute([$transaction['tID']]);
                                $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                foreach($orders as $order){
                                    $totalSales += ($order['total'] * $order['oQty']);
                                }
                            }
                        ?>
                        <p class="h1 fw-bold">$<?=$totalSales?></p>
                        <p class="h4">Total Sales of the Month</p>
                    </div>
                    <div class="card total-users">
                        <?php
                            $stmt = $conn->prepare("SELECT COUNT(*) as totalUsers FROM users WHERE uType = 1");
                            $stmt->execute();
                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <p class="h1 fw-bold"><?=$result['totalUsers']?></p>
                        <p class="h4">Total Users</p>
                    </div>
                    <div class="card date-today">
                        <?php
                            $stmt = $conn->prepare("SELECT SUM(vCount) AS totalVisits FROM visit");
                            $stmt->execute();
                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <p class="h1 fw-bold"><?=$result['totalVisits']?></p>
                        <p class="h4">Total Number of Visits</p>
                    </div>

                    <!-- Bar Chart and Pie Chart Section -->
                    <div class="card bar-chart-container">
                        <p class="h4">Sales Weekly Report</p>
                        <canvas id="barChart"></canvas>
                    </div>
                    <div class="card pie-chart-container">
                        <p class="h4">Most Bought Products</p>
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </section>
        </main>
    </section>
  
    <!-- Initialize Chart.js -->
    <script>

        <?php
        $stmt = $conn->prepare("
            SELECT 
    CASE d.day
        WHEN 1 THEN 'Sunday'
        WHEN 2 THEN 'Monday'
        WHEN 3 THEN 'Tuesday'
        WHEN 4 THEN 'Wednesday'
        WHEN 5 THEN 'Thursday'
        WHEN 6 THEN 'Friday'
        WHEN 7 THEN 'Saturday'
    END AS day_of_week,
    IFNULL(t.non_null_dates, 0) AS non_null_dates
FROM 
    (
        SELECT DAYOFWEEK(CURDATE()) AS day
        UNION
        SELECT 1 AS day UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7
    ) d
    LEFT JOIN 
    (
        SELECT 
            DAYOFWEEK(tDateOrder) AS day,
            COUNT(tDateOrder) AS non_null_dates
        FROM 
            transactions
        WHERE 
            tDateOrder IS NOT NULL
        GROUP BY 
            DAYOFWEEK(tDateOrder)
    ) t
    ON d.day = t.day
ORDER BY 
    MOD(d.day - DAYOFWEEK(CURDATE()) + 7, 7)
        ");
        $stmt->execute();
        $weeklyReport = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        ?>
        // Bar Chart
        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['<?= implode("', '", array_keys($weeklyReport)) ?>'],
                datasets: [{
                    label: 'Sales',
                    data: [<?= implode(', ', array_values($weeklyReport)) ?>],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Pie Chart
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['<?= implode("', '", array_keys($salesCount)) ?>'],
                datasets: [{
                    label: 'as',
                    data: [<?= implode(', ', array_values($salesCount)) ?>],
                    borderWidth: 1
                }]
            },
            options: {
                font: {
                    family: 'Helvetica',
                    size: 15,
                    style: 'normal',
                    weight: 'bold'
                }
            }
        });
    </script>
</body>
</html>