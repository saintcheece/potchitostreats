<?php 
    require('../../controller/db_model_products.php');

    //ADD TO CART
    if (isset($_POST['addToCart'])) {
        $productID = filter_input(INPUT_POST, 'addToCart', FILTER_SANITIZE_STRING);

        // GET TRANSACTION ID
        $stmt = $conn->prepare("SELECT tID FROM transactions WHERE uID = ? AND tStatus= 1");
        $stmt->execute([$_SESSION['userID']]);
        $transactionID = $stmt->fetchColumn();

        // if transaction exists
        if ($transactionID != null) {
            // if order of product exists, increment the quantity
            $stmt = $conn->prepare("SELECT oID FROM orders WHERE tID = ? AND pID = ?");
            $stmt->execute([$transactionID, $productID]);
            $orderID = $stmt->fetchColumn();
            if ($orderID != null) {
                $stmt = $conn->prepare("UPDATE orders SET oQty = oQty + 1 WHERE oID = ?");
                $stmt->execute([$orderID]);
            } else {
                // add this product to the db
                $stmt = $conn->prepare("INSERT INTO orders (tID, pID, oQty) VALUES (?, ?, 1)");
                $stmt->execute([$transactionID, $productID]);
            }
        } else {
            // if no transaction exists, create one
            $stmt = $conn->prepare("INSERT INTO transactions (uID, tStatus) VALUES (?, 1)");
            $stmt->execute([$_SESSION['userID']]);
            $transactionID = $conn->lastInsertId();
            $stmt = $conn->prepare("INSERT INTO orders (tID, pID, oQty) VALUES (?, ?, 1)");
            $stmt->execute([$transactionID, $productID]);
        }

        header('Location: cart.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cookies</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/product-by-type.css">

    <link rel="stylesheet" href="flickity.css" media="screen">
    <script src="flickity.pkgd.min.js"></script>
</head>
<body style="height: 88vh">

    <?php include 'layout/header.php'; ?>
    <header>
        <div class="hero-section">
            <h2>
            <?php
                switch ($_GET['type']) {
                    case 1:
                        echo 'Cookies';
                        break;
                    case 2:
                        echo 'Pastries';
                        break;
                    case 3:
                        echo 'Cakes';
                        break;
                }
            ?>
            </h2>
        </div>
    </header>
    <section class="container py-5">
        <?php 
            switch ($_GET['type']) {
                case 1:
                    displayProductCards($products, 1);
                    break;
                case 2:
                    displayProductCards($products, 2);
                    break;
                case 3:
                    displayProductCards($products, 3);
                    break;
            }
        ?>
    </section>
    <?php include 'layout/footer.php'; ?>
    
</body>
</html>
