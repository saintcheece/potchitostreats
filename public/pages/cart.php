<?php

    $total = 0;
    $products;
    $prepTime = 0;

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
            $stmt = $conn->prepare("SELECT p.pID, p.pType, p.pName, p.pPrice,  o.oQty, ((p.pPrice + COALESCE(cf.cfPrice, 0) + COALESCE(cs.csPrice, 0))) AS total, p.pPrepTime
                                    FROM orders o
                                    INNER JOIN products p ON o.pID = p.pID
                                    LEFT JOIN cakes c ON o.pID = c.pID
                                    LEFT JOIN cakes_flavor cf ON c.cfID = cf.cfID
                                    LEFT JOIN cakes_size cs ON c.csID = cs.csID
                                    WHERE o.tID = ?");
            $stmt->execute([$transaction]);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // ...GET TOTAL
        foreach($products as $product){
            $total += ($product['pType'] === 3 ? $product['total'] / 2 : $product['total']);
            $prepTime += ($product['pPrepTime'] * $product['oQty']);
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
                        <tr class="clickable-row" data-product-id="<?=$product['pID']?>" data-href="product-view.php?id=<?=$product['pID']?>&type=<?=$product['pType']?>">

                            <!-- REMOVE BUTTON -->
                            <td><a href="cart.php?remove=<?=$product['pID']?>" class="remove-item">×</a></td>

                            <!-- PRODUCT IMAGE -->
                            <td><img src="../../product-gallery/<?= array('Cookie', 'Pastry', 'Cake')[$product['pType']-1]."_".$product['pID'].".jpg"?>" alt="Cake Image" class="item-image"></td>
                            
                            <!-- PRODUCT NAME (AND DETAILS IF CAKE) -->
                            <td style="text-align:left;"><b><?=$product['pName']?></b>
                                <?php if($product['pType'] == 3){?>
                                    <?php 
                                        $stmt = $conn->prepare("SELECT * 
                                                                FROM cakes c
                                                                INNER JOIN cakes_flavor cf ON c.cfID = cf.cfID
                                                                INNER JOIN cakes_size cs ON c.csID = cs.csID
                                                                WHERE c.tID = ? AND c.pID = ?");
                                        $stmt->execute([$transaction, $product['pID']]);
                                        $cake = $stmt->fetch(PDO::FETCH_ASSOC);
                                    ?>
                                    <div>
                                        <small>Flavor: <?= $cake['cfName']?></small><br>
                                        <small>Size: <?= $cake['csSize']?></small><br>
                                        <small>Message: <?= $cake['cMessage']?></small><br>
                                        <small>Instruction: <?= $cake['cInstructions']?></small><br>
                                    </div>
                                <?php }?>
                            </td>

                            <!-- ORDER PRICE -->
                            <td>₱<?=$product['total'] ?></td>

                            <!-- ORDER QUANTITY -->
                            <td><input type="number" value="<?=$product['oQty']?>" min="1" class="quantity-input product-qty"></td>

                            <!-- TOTAL -->
                            <td class="subtotal">
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
                    <form action="../../controller/checkout.php" method="POST" class="p-3 bg-light bg-opacity-10">
                        <h6 class="card-title mb-3">Order Summary</h6>

                        <?php 
                        if(isset($products)){
                            foreach($products as $product){ ?>

                            <div class="d-flex justify-content-between mb-1 small">
                                <!-- RECEIPT CONTENTS -->
                                <span><?=$product['pName'] . " x" . $product['oQty']?>
                                    <span class="summary-price">
                                    </span> 
                                </span> 
                            </div>

                        <?php } ?>

                        <hr>
                        <div class="d-flex justify-content-between mb-4 small">
                            <span>TOTAL</span> <strong id="order-total" class="text-dark">₱</strong>
                        </div>
                        <div class="mb-1 small">
                            <?php if(in_array(3, array_column($products, 'pType'))){ ?>
                            <label class="form-check-label text-muted" for="tnc">
                                You have a custom cake in your cart. You will be contacted by our team for consultation after order is placed.
                            </label>
                            <?php } ?>
                        </div>
                        <label for="date" class="form-label">Choose a date</label>
                        <?php
                            // current day + (prep time(int as hours) + grace time(int as hours) rounded up to nearest day since input is date, not time)
                            $timestamp = strtotime('+' . ($prepTime + 24) . ' hours');
                            $timestamp = strtotime(date('Y-m-d', $timestamp) . ' + ' . ceil(($prepTime + 24) / 24) . ' hours');
                        ?>
                        <input class="form-control" type="date" id="date" name="orderDate" value="<?= date('Y-m-d', $timestamp)?>" min="<?= date('Y-m-d', $timestamp)?>" required>
                        <div class="invalid-feedback">
                            Please choose a date.
                        </div>
                        <hr/>
                            <p><b>Payment Type:</b></p>
                            <div class="form-check my-2 mx-3">
                                <input class="form-check-input" type="radio" name="paymentOption" id="fullPayment" value="fullPayment" 
                                <?php if(!in_array(3, array_column($products, 'pType'))){ echo 'checked'; }else{ echo ''; }?>>
                                <label class="form-check-label" for="fullPayment" value="1">
                                    Full Payment
                                </label>
                            </div>
                            <div class="form-check my-2 mx-3">
                                <input class="form-check-input" type="radio" name="paymentOption" id="deposit" value="deposit" 
                                <?php if(in_array(3, array_column($products, 'pType'))){ echo 'checked'; }else{ echo 'disabled'; }?>>
                                <label class="form-check-label" for="deposit" value="2">
                                    Deposit<small> (50% deposit on cakes)</small>
                                </label>
                            </div>
                        <hr/>
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

    var products = <?php 
        echo json_encode(array_map(function($product) {
            return array('price' => $product['total'], 
                         'qty' => $product['oQty'],
                         'pType' => $product['pType'],
                         'pPrepTime' => $product['pPrepTime'] ?? null);
        }, $products));
    ?>;

    $(document).ready(function(){
        products.forEach(function(product, index){
            $(".subtotal").eq(index).html("&#8369;"+(product.price*product.qty).toFixed(2));
            $(".summary-price").eq(index).html("&#8369;"+(product.price*product.qty).toFixed(2));
        });
    });
    transactionType = $("input[name='paymentOption']:checked").val() == '1' ? 1 : 2;

    function updateSubtotal() {
        var total = 0;
        $(".subtotal").each(function(index){
            var qty = $(".quantity-input").eq(index).val();
            if(products[index].pType != 3){
                total += products[index].price*qty;
                $(this).html("&#8369;"+(products[index].price*qty).toFixed(2));
                $(".summary-price").eq(index).html("&#8369;"+(products[index].price*qty).toFixed(2));
            } else {
                total += products[index].price*qty/(transactionType == 1 ? 1 : 2);
                $(this).html("&#8369;"+(products[index].price*qty/(transactionType == 1 ? 1 : 2)).toFixed(2));
                $(".summary-price").eq(index).html("&#8369;"+(products[index].price*qty/(transactionType == 1 ? 1 : 2)).toFixed(2));
            }
        });

        var date = new Date($("#date").val());
        date.setHours(date.getHours() + products.reduce((acc, curr) => acc + (curr.pType == 3 ? curr.pPrepTime : 0), 0));
        $("#date").val(date.toISOString().split('T')[0]);
        $("#order-total").html("&#8369;"+total.toFixed(2));
    }

    $('input[name="paymentOption"]').on('change', function() {
        transactionType = $("input[name='paymentOption']:checked").val() == 'fullPayment' ? 1 : 2;
        updateSubtotal();
    });


    jQuery(document).ready(function($) {
        $(".clickable-row").click(function() {
            window.location = $(this).data("href");
        });
        updateSubtotal();
    });
    
    // Add the click event to the product-qty elements
    $(".product-qty").click(function(e) {
        e.stopPropagation();
    });


    

    $(".quantity-input").change(function(){
        var index = $(this).closest('.product-qty').index('.product-qty');
        var qty = $(this).val();
        var orderId = <?= $transaction ?>;
        var productId = $(this).closest('tr').data('product-id');

        $.ajax({
            url: '../../controller/cart_model.php',
            type: 'POST',
            data: {
                orderId: orderId,
                productId: productId,
                quantity: qty
            },
            success: function(data) {
                console.log(productId);
            }
        });
        updateSubtotal();
    });


</script>
</html>