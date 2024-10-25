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
    $stmt = $conn->prepare("SELECT a.aID, a.uID, ae.aOpDesc, a.aTime FROM audit a INNER JOIN audit_enum ae ON a.aOpID = ae.aOpID ORDER BY a.aID DESC");
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
</head>

<body id="main" class="p-0">
    <?php include 'layout/navbar.php'; ?>
    <section id="main-container">
        <main>
            <h1>Audit Records</h1>
            <table class="products-table">
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
        </main>
    </section>
</body>

</html>

