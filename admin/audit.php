<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Records</title>
    <link rel="stylesheet" href="css/manage-product.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="js/navbar-loader.js" defer></script>
    <?php
    session_start();
    require('../controller/db_model.php');

    // Set the limit of records per page
    $limit = 30;

    // Get the current page number from the URL, default to 1 if not set
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    // Calculate the starting record for the current page
    $offset = ($page - 1) * $limit;

    // Prepare the SQL statement with LIMIT and OFFSET for pagination
    $stmt = $conn->prepare("
        SELECT a.aID, a.uID, ae.aOpDesc, a.aTime 
        FROM audit a 
        INNER JOIN audit_enum ae ON a.aOpID = ae.aOpID 
        ORDER BY a.aID DESC 
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get the total number of records for pagination calculation
    $total_stmt = $conn->prepare("SELECT COUNT(*) as total FROM audit");
    $total_stmt->execute();
    $total_records = $total_stmt->fetch(PDO::FETCH_ASSOC)['total'];
    $total_pages = ceil($total_records / $limit);
    ?>
</head>

<body id="main" class="p-0">
    <?php include 'layout/navbar.php'; ?>
    <section id="main-container">
        <main class="container">
            <h1>Audit Records</h1>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User ID</th>
                        <th>Operation</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $record) { ?>
                        <tr>
                            <td><?= $record['aID'] ?></td>
                            <td><?= $record['uID'] ?></td>
                            <td><?= $record['aOpDesc'] ?></td>
                            <td><?= date('F j, Y, g:i a', strtotime($record['aTime'])) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <!-- Pagination Controls -->
            <nav>
                <ul class="pagination">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </main>
    </section>
</body>
</html>
