<?php 

    $total = 0;
    $orders = [];

    require('../../controller/db_model.php'); 

    session_start();

    $stmt = $conn->prepare("SELECT tID FROM transactions WHERE uID = ? AND tStatus = 1");
    $stmt->execute([$_SESSION['userID']]);
    $transaction = $stmt->fetchColumn();

    // CHECKOUT
    if (isset($_POST['checkout'])) {
        if($transaction){
            $stmt = $conn->prepare("UPDATE transactions SET tStatus = 2, tDateOrder = NOW() WHERE tID = ?");
            $stmt->execute([$transaction]);
            header('cart.php');
            $transaction = null;
        }
    }
    
    if($transaction){
        // GET ALL PRODUCTS IN CART
        $stmt = $conn->prepare("SELECT pid, oQty FROM orders WHERE tID = ?");
        $stmt->execute([$transaction]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // GET PRODUCT DETAILS FOR EACH ORDER
        $stmt = $conn->prepare("SELECT pName, pID, pPrice FROM products WHERE pID IN (".implode(',', array_map(function($val) {
            return (int)$val['pid'];
        }, $orders)).")");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // GET TOTAL
        for($i = 0; $i < count($orders); $i++){
            $total += $products[$i]['pPrice'] * $orders[$i]['oQty'];
        }
        
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
                            for($i = 0; $i < count($orders); $i++){
                                echo '<tr>
                                        <td><button class="remove-item">×</button></td>
                                        <td><img src="assets/cake-thumbnail.jpg" alt="Cake Image" class="item-image"></td>
                                        <td>'.$products[$i]['pName'].'</td>
                                        <td>₱'.$products[$i]['pPrice'].'</td>
                                        <td><input type="number" value="'.$orders[$i]['oQty'].'" min="1" class="quantity-input"></td>
                                        <td>₱'.$orders[$i]['oQty'] * $products[$i]['pPrice'].'</td>
                                        </tr>';
                            }
                        ?>
                </tbody>
            </table>
            <button class="empty-cart" title="Empty your cart">Empty Cart</button>
        </div>
        <div class="col-lg-3">
            <!-- checkout -->
 <div class="card position-sticky top-0 mb-1">
    <div class="p-3 bg-light bg-opacity-10">
      <h6 class="card-title mb-3">Order Summary</h6>
      <div class="d-flex justify-content-between mb-1 small">
        <span>Product 1 </span> <span>₱280 </span>
      </div>
  
      <hr>
      <div class="d-flex justify-content-between mb-4 small">
        <span>TOTAL</span> <strong class="text-dark">₱280</strong>
      </div>
      <div class="form-check form-check-inline mb-1 small">
        <input class="form-check-input" type="radio" name="deliveryOption" value="pickup" id="pickup">
        <label class="form-check-label" for="pickup">
            Pick up
        </label>
      </div>
      <div class="form-check form-check-inline mb-1 small">
        <input class="form-check-input" type="radio" name="deliveryOption" value="delivery" id="delivery">
        <label class="form-check-label" for="delivery">
            Delivery
        </label>
      </div>
      <div class="mb-1 small">
              <label class="form-check-label text-muted" for="tnc">
          You have a custom cake in your cart. You will be contacted by our team for consultation after order is placed.
        </label>
      </div>
     
      <button class="btn btn-primary w-100 mt-2" onclick="window.location.href='payment.php';">
        Proceed to Checkout
      </button>
    </div>
</div>

  <!-- address -->
<div class="card position-sticky top-0">
    <div class="p-3 bg-light bg-opacity-10">
      <h6 class="card-title mb-3">Address</h6>
        <div class="form-check mb-1 small">
        <input class="form-check-input" type="radio" name="address" value="address1" id="address1">
        <label class="form-check-label" for="address1">
          Address 1 (Brgy. Something, San Ildefonso)
        </label>
      </div>
      <div class="form-check mb-1 small">
        <input class="form-check-input" type="radio" name="address" value="address2" id="address2">
        <label class="form-check-label" for="address2">
        Address 2 (Brgy. Something, Bustos)
        </label>
      </div>
    </div>
</div>
</div>
</section>

    <?php include 'layout/footer.php'; ?>

</body>
</html>
