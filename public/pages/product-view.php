<?php
    require('../../controller/db_model_products.php');

    $productID = filter_input(INPUT_POST, 'addToCart', FILTER_SANITIZE_STRING);
    
    // GET PRODUCT DETAILS
    $stmt = $conn->prepare("SELECT * FROM products WHERE pID = ?");
    $stmt->execute([$_GET['id']]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // GET TRANSACTION ID
    $stmt = $conn->prepare("SELECT tID FROM transactions WHERE uID = ? AND tStatus= 1");
    $stmt->execute([$_SESSION['userID']]);
    $transactionID = $stmt->fetchColumn();

    if(isset($_POST['cakeFlavor'], $_POST['cakeSize'], $_POST['cakeMessage'], $_POST['cakeInstructions'])){

        echo "VALID";

        if ($transactionID != null) {
            // if order of product exists, increment the quantity
            $stmt = $conn->prepare("SELECT oID FROM orders WHERE tID = ? AND pID = ?");
            $stmt->execute([$transactionID, $_GET['id']]);
            $orderID = $stmt->fetchColumn();
            if ($orderID != null) {
                $stmt = $conn->prepare("UPDATE orders SET oQty = oQty + 1 WHERE oID = ?");
                $stmt->execute([$orderID]);
            } else {
                addCakeToTransaction($transactionID, $_GET['id'], $conn, $_POST);

                // add this product to the db
                $stmt = $conn->prepare("INSERT INTO orders (tID, pID, oQty) VALUES (?, ?, 1)");
                $stmt->execute([$transactionID, $_GET['id']]);

                header("Location: product-view.php?id=".$_GET['id']."&type=3");
            }
        } else {
            // if no transaction exists, create one
            $stmt = $conn->prepare("INSERT INTO transactions (uID, tType, tStatus) VALUES (?, 2, 1)");
            $stmt->execute([$_SESSION['userID']]);
            $transactionID = $conn->lastInsertId();
            // then add the product
            $stmt = $conn->prepare("INSERT INTO orders (tID, pID, oQty) VALUES (?, ?, 1)");
            $stmt->execute([$transactionID, $_GET['id']]);

            addCakeToTransaction($transactionID, $_GET['id'], $conn, $_POST);
        }
    }

    function addCakeToTransaction($tID, $pID, $conn, $cakeDetails){
        $stmt = $conn->prepare("INSERT INTO cakes (tID, pID, cFlavor, cSize, cMessage, cInstructions) VALUES (:tID, :pID, :cakeFlavor, :cakeSize, :cakeMessage, :cakeInstructions)");
        $stmt->execute([
            'tID' => $tID,
            'pID' => $pID,
            'cakeFlavor' => $cakeDetails['cakeFlavor'],
            'cakeSize' => $cakeDetails['cakeSize'],
            'cakeMessage' => $cakeDetails['cakeMessage'],
            'cakeInstructions' => $cakeDetails['cakeInstructions']
        ]);
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/product-view.css">

<?php if($_GET['type'] != 3){?>
    <title>Product Page</title> 

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </head>
    <body>

        <?php include 'layout/header.php'; ?>
        <section class="container-product">
            <div class="card-container">
                <img src="../../product-gallery/<?= array('Cookie', 'Pastry', 'Cake')[$product['pType']-1]."_".$product['pID'].".jpg"?>" alt="Product Image">
                <div class="product-details">
                    <h1><?= $product['pName']?></h1>
                    <p><?= $product['pDesc']?></p>
                    <p>₱ <?= $product['pPrice']?> </p>
                    
                    <?php if(isset($_SESSION['userID'])){?>
                    <!-- Quantity input -->
                    <div class="input-group mb-3">
                        <span class="input-group-text">Quantity</span>
                        <input type="number" class="form-control custom-input" min="1" value="1">
                        </div>

                    <button class="btn btn-primary mt-2" style="width: 200px;">Add to Cart</button>
                    <?php }?>
                </div>
            </div>
        </section>

        <?php include 'layout/footer.php'; ?>
        
    </body>
<?php }else{?>
    <title>Product Page</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>

    <?php include 'layout/header.php'; ?>
    <section id="background">
        <div class="custom-cake-image-container">
            <img src="../../product-gallery/<?= array('Cookie', 'Pastry', 'Cake')[$product['pType']-1]."_".$product['pID'].".jpg"?>" alt="">
        </div>
        <form action="product-view.php?id=<?= $_GET['id']?>&type=3" method="POST" class="form-container p-3">
            <h1><?= $product['pName']?></h1>
            <p id="description-cake">
                <?= $product['pDesc']?>
            </p>
            <p id="allergens"> May Contains Soy, Nut, Hotdog</p>
            <p>If you are ordering a custom cake, the baker will call you to ensure clarity </p>

            <hr />
            <div class="grid-container mt-1">
                <div class="grid-item">
                    <p>Choose Cake Flavor</p>
                    <select name="cakeFlavor" id="flavor">
                        <option value="1">Chocolate</option>
                        <option value="2">Vanilla</option>
                        <option value="3">Strawberry</option>
                        <option value="4">Red Velvet</option>
                    </select>
                </div>
                <div class="grid-item">
                    <p>Choose Cake Size</p>
                    <select name="cakeSize" id="size">
                        <option value="6">6-inch</option>
                        <option value="8">8-inch</option>
                        <option value="10">10-inch</option>
                        <option value="12">12-inch</option>
                    </select>
                </div>
                <div class="grid-item full-width">
                    <label id="input-message">Input Message</label>
                    <small><i>(only applies to dedicatable cakes)</i></small>
                    <textarea name="cakeMessage" id="message" placeholder="Enter your message..." maxlength="200"></textarea>
                </div>
                <!-- Image upload field -->
                <div class="grid-item">
                    <label for="" class="form-label">Upload Reference:</label><br>
                    <input class="form-control" name="cakeReference" type="file" accept="image/*">
                    <small> Maximum File Size of 5MB</small>
                </div>

                <div class="grid-item">
                    <label for="" class="form-label">Quantity:</label><br>
                    <input class="form-control" name="cakeQuantity" type="number" min="1" step="1" value="1">
                    <small>Applies the Same Customizations</small>
                </div>

                <!-- Original message for the cake -->
            

                <!-- Additional instructions with max char limit -->
                <div class="grid-item full-width">
                    <p id="additional-instruction-p">Additional Instructions (optional)</p>
                    <textarea name="cakeInstructions" id="instructions" maxlength="300" placeholder="Enter any special requests or instructions (max 300 characters)"></textarea>
                </div>
                
                <div class="divider m-2"></div>

                <div class="total-and-cart">
                    <p class="total-price"><b>Total Price: $45.00</b></p>
                    <button type="submit" name="addCakeToCart" id="add-to-cart">Add to Cart</button>
                </div>
            </div>
        </form>
    </section>

    <?php include 'layout/footer.php'; ?>

</body>
<?php }?>
</html>