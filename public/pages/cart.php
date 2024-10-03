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

        $stmt = $conn->prepare("SELECT pType FROM products WHERE pID = ?");
        $stmt->execute([$_GET['remove']]);
        $productType = $stmt->fetchColumn();

        $stmt = $conn->prepare("DELETE FROM orders WHERE pID = ?");
        $stmt->execute([$_GET['remove']]);

        if($productType = 3){
            $stmt = $conn->prepare("DELETE FROM cakes WHERE pID = ?");
            $stmt->execute([$_GET['remove']]);
        }

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
            $total += ($product['pType'] === 3 ? $product['total'] / 2 : $product['total']);
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
                    <?php 
                    if(isset($products)){
                        foreach($products as $product){ ?>
                        <tr class="clickable-row" data-href="product-view.php?id=<?=$product['pID']?>&type=<?=$product['pType']?>">

                            <!-- REMOVE BUTTON -->
                            <td><a href="cart.php?remove=<?=$product['pID']?>" class="remove-item">×</a></td>

                            <!-- PRODUCT IMAGE -->
                            <td><img src="../../product-gallery/<?= array('Cookie', 'Pastry', 'Cake')[$product['pType']-1]."_".$product['pID'].".jpg"?>" alt="Cake Image" class="item-image"></td>
                            
                            <!-- PRODUCT NAME (AND DETAILS IF CAKE) -->
                            <td style="text-align:left;"><b><?=$product['pName']?></b>
                                <?php if($product['pType'] == 3){?>
                                    <?php 
                                        $stmt = $conn->prepare("SELECT * FROM cakes WHERE tID = ? AND pID = ?");
                                        $stmt->execute([$transaction, $product['pID']]);
                                        $cake = $stmt->fetch(PDO::FETCH_ASSOC);
                                    ?>
                                    <div>
                                        <small>Flavor: <?= $cake['cFlavor']?></small><br>
                                        <small>Size: <?= $cake['cSize']?></small><br>
                                        <small>Message: <?= $cake['cMessage']?></small><br>
                                        <small>Instruction: <?= $cake['cInstructions']?></small><br>
                                    </div>
                                <?php }?>
                            </td>

                            <!-- ORDER PRICE -->
                            <td>₱<?=$product['pPrice'] ?></td>

                            <!-- ORDER QUANTITY -->
                            <td><input type="number" value="<?=$product['oQty']?>" min="1" class="quantity-input"></td>

                            <!-- TOTAL -->
                            <td>
                                <?php if($product['pType'] == 3){
                                        echo "₱".number_format($product['pPrice'] * $product['oQty'] / 2, 2). " <small><i>(deposit)</i></small>";
                                    }else{
                                        echo "₱".number_format($product['pPrice'] * $product['oQty'], 2);
                                }?>
                            </td>
                        </tr>
                        <?php }}?>
                    </tbody>   
                </table>
                <?php if(isset($products)){
                    if(count($products) > 0){ ?>
                    <form action="cart.php" method="post">
                        <button class="empty-cart" name="emptyCart" value="<?= $transaction?>">Empty Cart</button>
                    </form>
                <?php }} ?>
            </div>
            <div class="col-lg-3">
                <!-- checkout -->
                <div class="card position-sticky top-0 mb-1">
                    <form action="../../controller/checkout.php" class="p-3 bg-light bg-opacity-10">
                        <h6 class="card-title mb-3">Order Summary</h6>

                        <?php if(isset($products)){?>
                        <?php foreach($products as $product){ ?>
                            <div class="d-flex justify-content-between mb-1 small">
                                <!-- RECEIPT CONTENTS -->
                                <span><?=$product['pName'] . " x" . $product['oQty']?>
                                    <?php if($product['pType'] == 3){ ?>
                                        <small><i>(deposit)</i></small>
                                        </span>
                                        <span>
                                            ₱<?= number_format($product['total'] / 2, 2)?>
                                    <?php }else{?>
                                        </span>
                                        <span>
                                            ₱ <?= number_format($product['pPrice'] * $product['oQty'], 2)?>
                                    <?php }?>
                                </span> 
                            </div>
                        <?php } ?>

                        <hr>
                        <div class="d-flex justify-content-between mb-4 small">
                            <span>TOTAL</span> <strong class="text-dark">₱<?= number_format($total, 2)?></strong>
                        </div>
                        <div class="mb-1 small">
                            <label class="form-check-label text-muted" for="tnc">
                                You have a custom cake in your cart. You will be contacted by our team for consultation after order is placed.
                            </label>
                        </div>
                        <label for="date" class="form-label">Choose a date</label>
                        <input class="form-control" type="date" id="date" name="date" value="<?= date('Y-m-d', strtotime('+5 days'))?>" min="<?= date('Y-m-d', strtotime('+5 days'))?>" required>
                        <div class="invalid-feedback">
                            Please choose a date.
                        </div>
                        <div class="d-flex justify-content-center">
                            <div class="form-check my-3" >
                            <input class="form-check-input" type="checkbox" value="" id="tnc" required>
                            <label class="form-check-label" for="tnc">
                                <small>I agree to the <a href="terms-and-conditions.php">terms and conditions</a></small>
                            </label>
                            </div>
                        </div>

                        <button class="btn btn-primary w-100 mt-2" <?php if(count($products) == 0) echo 'disabled';?>>
                            Proceed to Checkout
                        </button>
                        <?php }?>
                    </form>
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