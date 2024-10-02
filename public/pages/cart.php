<?php

    $total = 0;
    $products;

    require('../../controller/db_model.php');

    session_start();

    // GET TRANSACTION
    $stmt = $conn->prepare("SELECT tID FROM transactions WHERE uID = ? AND tStatus = 1");
    $stmt->execute([$_SESSION['userID']]);
    $transaction = $stmt->fetchColumn();

    // X (remove an item) CLICKED
    if(isset($_GET['remove'])){
        $stmt = $conn->prepare("DELETE FROM orders WHERE pID = ?");
        $stmt->execute([$_GET['remove']]);
        header('cart.php');
    }

    // CHECKOUT
    if (isset($_POST['checkout'])) {
        if($transaction){
            $stmt = $conn->prepare("UPDATE transactions SET tStatus = 2, tDateOrder = NOW() WHERE tID = ?");
            $stmt->execute([$transaction]);
            header('cart.php');
            $transaction = null;
        }
    }

    if(isset($_POST['emptyCart'])){
        $stmt = $conn->prepare("DELETE FROM orders WHERE tID = ?");
        $stmt->execute([$transaction]);
        header('cart.php');
    }

    // IF A TRANSACTION ALREADY EXISTS...
    if($transaction){
        // ...GET ALL PRODUCTS IN CART
        $stmt = $conn->prepare("SELECT COUNT(*) FROM orders WHERE tID = ?");
        $stmt->execute([$transaction]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if($orders > 0){
            // ...GET PRODUCT DETAILS FOR EACH ORDER
            $stmt = $conn->prepare("SELECT p.pID,
                                    p.pType,
                                    p.pName,
                                    p.pPrice, 
                                    o.oQty, 
                                    (p.pPrice * o.oQty) AS total
                                    FROM orders o
                                    INNER JOIN products p ON o.pID = p.pID
                                    WHERE o.tID = ?");
            $stmt->execute([$transaction]);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // ...GET TOTAL
        foreach($products as $product){
            $total += $product['total'];
        }

    }else{
        // IF THERE IS NO TRANSACTION THAT EXISTS, ASSIGN NULL
        $transaction = null;
    }

    ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/cart.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
      .clickable-row:hover{
        background-color: #f5f5f5;
        cursor: pointer;
      }
    </style>
</head>
<body>

    <?php include 'layout/header.php'; ?>

    <section id="cart-section">
        <h1 class="h3 mt-1 mb-2 ml-5">Your Cart</h1>
        <div class="cart-content">
            <div class="cart-items">
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Thumbnail</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($products as $product){ ?>
                      <tr class="clickable-row" data-href="product-view.php?id=<?=$product['pID']?>&type=<?=$product['pType']?>">
                        <td><a href="cart.php?remove=<?=$product['pID']?>" class="remove-item">×</a></td>
                        <td><img src="../../product-gallery/<?= array('Cookie', 'Pastry', 'Cake')[$product['pType']-1]."_".$product['pID'].".jpg"?>" alt="Cake Image" class="item-image"></td>
                        <td><?=$product['pName']?></td>
                        <td><?=$product['pPrice']?></td>
                        <td><input type="number" value="<?=$product['oQty']?>" min="1" class="quantity-input"></td>
                        <td>₱<?=$product['oQty'] * $product['pPrice']?></td>
                      </tr>
                    <?php }?>
                    </tbody>
                </table>
                <?php if(count($products) > 0){ ?>
                    <form action="cart.php" method="post">
                        <button class="empty-cart" name="emptyCart" value="<?= $transaction?>">Empty Cart</button>
                    </form>
                <?php } ?>
            </div>
            <div class="col-lg-3">
                <!-- checkout -->
                <div class="card position-sticky top-0 mb-1">
                    <div class="p-3 bg-light bg-opacity-10">
                        <h6 class="card-title mb-3">Order Summary</h6>
                        <?php foreach($products as $product){ ?>
                        <div class="d-flex justify-content-between mb-1 small">
                            <span><?= $product['pName']?> x<?= $product['oQty']?></span> <span>₱<?= $product['total']?> </span>
                        </div>
                        <?php } ?>

                        <hr>
                        <div class="d-flex justify-content-between mb-4 small">
                            <span>TOTAL</span> <strong class="text-dark"><?= $total?></strong>
                        </div>
                        <div class="mb-1 small">
                            <label class="form-check-label text-muted" for="tnc">
                                You have a custom cake in your cart. You will be contacted by our team for consultation after order is placed.
                            </label>
                        </div>

                        <button class="btn btn-primary w-100 mt-2" onclick="window.location.href='../../controller/checkout.php';" <?php if(count($products) == 0) echo 'disabled';?>>
                            Proceed to Checkout
                        </button>
                    </div>
                </div>

                <!-- address -->
            </div>
        </div>
    </section>

    <?php include 'layout/footer.php'; ?>

</body>
<script>
    jQuery(document).ready(function($) {
        $(".clickable-row").click(function() {
            window.location = $(this).data("href");
        });
    });
</script>
</html>