<?php
    require('../../controller/db_model_products.php');

    $productID = filter_input(INPUT_POST, 'addToCart', FILTER_SANITIZE_STRING);
    
    // GET PRODUCT DETAILS
    $stmt = $conn->prepare("SELECT * FROM products WHERE pID = ?");
    $stmt->execute([$_GET['id']]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // GET TRANSACTION ID
    if(isset($_SESSION['userID'])){
        $stmt = $conn->prepare("SELECT tID FROM transactions WHERE uID = ? AND tStatus= 1");
        $stmt->execute([$_SESSION['userID']]);
        $transactionID = $stmt->fetchColumn();

        // GET CAKE CUSTOMIZATIONS
        $stmt = $conn->prepare("SELECT COUNT(*) FROM cakes WHERE pID = ? AND tID = ?");
        $stmt->execute([$_GET['id'], $transactionID]);
        $count = $stmt->fetchColumn();
        if($count > 0){
            $stmt = $conn->prepare("SELECT c.*, o.oQty, cf.cfPrice, cs.csPrice, p.pPrice FROM cakes c
                                    INNER JOIN transactions t ON c.tID = t.tID
                                    INNER JOIN orders o ON t.tID = o.tID
                                    INNER JOIN products p ON o.pID = p.pID
                                    INNER JOIN cakes_flavor cf ON p.pID = cf.pID    
                                    INNER JOIN cakes_size cs ON p.pID = cs.pID
                                    WHERE o.pID = ? AND o.tID = ? LIMIT 1");
            $stmt->execute([$_GET['id'], $transactionID]);
            $cakeDetails = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

    // ADD TO CART CLICKED
    if(isset($_POST['cakeFlavor'], $_POST['cakeSize'], $_POST['cakeMessage'], $_POST['cakeInstructions'])){

        if ($transactionID != null) {
            // if order of product exists, update the order details
            $stmt = $conn->prepare("SELECT oID FROM orders WHERE tID = ? AND pID = ?");
            $stmt->execute([$transactionID, $_GET['id']]);
            $orderID = $stmt->fetchColumn();
            if ($orderID != null) {
                echo "UPDATED";
                $stmt = $conn->prepare("UPDATE orders SET oQty = ? WHERE oID = ?");
                $stmt->execute([$_POST['cakeQuantity'], $orderID]);

                if($_GET['type'] == 3){
                    $stmt = $conn->prepare("UPDATE cakes SET cfID = ?, csID = ?, cMessage = ?, cInstructions = ? WHERE tID = ? AND pID = ?");
                    $stmt->execute([
                        $_POST['cakeFlavor'],
                        $_POST['cakeSize'],
                        $_POST['cakeMessage'],
                        $_POST['cakeInstructions'],
                        $transactionID,
                        $_GET['id']
                    ]);
                }
                header("Location: cart.php");
            } else {
                if($_GET['type'] == 3){ addCakeToTransaction($transactionID, $_GET['id'], $conn, $_POST); }

                // add this product to the db
                $stmt = $conn->prepare("INSERT INTO orders (tID, pID, oQty) VALUES (?, ?, ?)");
                $stmt->execute([$transactionID, $_GET['id'], $_POST['cakeQuantity']]);

                header("Location: cart.php");
            }
        } else {
            // if no transaction exists, create one
            $stmt = $conn->prepare("INSERT INTO transactions (uID, tStatus) VALUES (?, 1)");
            $stmt->execute([$_SESSION['userID']]);
            $transactionID = $conn->lastInsertId();
            // then add the product
            $stmt = $conn->prepare("INSERT INTO orders (tID, pID, oQty) VALUES (?, ?, ?)");
            $stmt->execute([$transactionID, $_GET['id'], $_POST['cakeQuantity']]);

            if($_GET['type'] == 3){ addCakeToTransaction($transactionID, $_GET['id'], $conn, $_POST); }
        }
    }

    function addCakeToTransaction($tID, $pID, $conn, $cakeDetails){
        $stmt = $conn->prepare("INSERT INTO cakes (tID, pID, cfID, csID, cMessage, cInstructions) VALUES (:tID, :pID, :cakeFlavor, :cakeSize, :cakeMessage, :cakeInstructions)");
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
    <title><?= $product['pName']?></title> 

<?php if($_GET['type'] != 3){?>
    
    <!-- ===================================================================================================================================================== -->

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
<!-- =========================================================================================================================================================== -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>

    <?php include 'layout/header.php'; ?>
    <section id="background">
        <!-- CAKE IMAGE -->
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
                    <!-- CAKE FLAVOR -->
                    <p>Choose Cake Flavor</p>
                    <select name="cakeFlavor" id="flavor">
                        <?php
                            $stmt = $conn->prepare("SELECT * FROM cakes_flavor WHERE pID = ?");
                            $stmt->execute([$product['pID']]);
                            $flavors = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach($flavors as $flavor){
                                $selected = isset($cakeDetails['cfID']) && $cakeDetails['cfID'] == $flavor['cfID'] ? 'selected' : '';
                                echo "<option value='{$flavor['cfID']}' data-flavorPrice='{$flavor['cfPrice']}' $selected>{$flavor['cfName']}</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="grid-item">
                    <!-- CAKE SIZE -->
                    <p>Choose Cake Size</p>
                    <select name="cakeSize" id="size">
                        <?php
                            $stmt = $conn->prepare("SELECT * FROM cakes_size WHERE pID = ?");
                            $stmt->execute([$product['pID']]);
                            $sizes = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach($sizes as $size){
                                $selected = isset($cakeDetails['csID']) && $cakeDetails['csID'] == $size['csID'] ? 'selected' : '';
                                echo "<option value='{$size['csID']}' data-sizePrice='{$size['csPrice']}' $selected>{$size['csSize']}\"</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="grid-item full-width">
                    <label id="input-message">Input Message</label>
                    <small><i>(only applies to dedicatable cakes)</i></small>
                    <textarea name="cakeMessage" id="message" placeholder="Enter your message..." maxlength="200"><?php if(isset($cakeDetails['cMessage'])){ echo $cakeDetails['cMessage']; }?></textarea>
                </div>
                <!-- Image upload field -->
                <div class="grid-item">
                    <label for="" class="form-label">Upload Reference:</label><small><i>Maximum File Size of 5MB</i></small><br>
                    <input class="form-control" name="cakeReference" type="file" accept="image/*">
                </div>

                <div class="grid-item">
                    <label for="" class="form-label">Quantity:</label><small><i>Applies the Same Customizations</i></small><br>
                    <input class="form-control" name="cakeQuantity" type="number" min="1" step="1" value='<?php if(isset($cakeDetails["oQty"])){echo $cakeDetails["oQty"];}else{echo "1";}?>'>
                </div>

                <!-- Original message for the cake -->
            

                <!-- Additional instructions with max char limit -->
                <div class="grid-item full-width">
                    <p id="additional-instruction-p">Additional Instructions (optional)</p>
                    <textarea name="cakeInstructions" id="instructions" maxlength="300" placeholder="Enter any special requests or instructions (max 300 characters)"><?php if(isset($cakeDetails['cInstructions'])){ echo $cakeDetails['cInstructions']; }?></textarea>
                </div>
                
                <div class="divider m-2"></div>

                <div class="total-and-cart">
                    <h3 class="total-price"><b>Total Price: <?php 
                        if(isset($cakeDetails['cID'])){
                            echo ($cakeDetails['pPrice'] + $cakeDetails['cfPrice'] + $cakeDetails['csPrice']) * $cakeDetails['oQty'];
                        }else{
                            echo $product['pPrice'];
                        }
                    ?></b></h3>
                    <?php if(isset($cakeDetails['cID'])){ ?>
                        <button type="submit" name="addCakeToCart" class="btn btn-outline-primary" >Save Changes</button>
                    <?php }else{ ?>
                        <?php if(!isset($_SESSION['userID'])){ ?>
                            <a href="login.php" class="btn btn-outline-primary" >Log in to Add to Cart</a>
                        <?php }else{ ?>
                            <button type="submit" name="addCakeToCart" id="add-to-cart">Add to Cart</button>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </form>
    </section>

    <?php include 'layout/footer.php'; ?>

</body>
    <script>
        const inputs = document.querySelectorAll('input, select, textarea');
        const price = document.getElementsByClassName('total-price')[0];

        const updatePrice = () => {
            const selectedFlavor = document.getElementById('flavor').options[document.getElementById('flavor').selectedIndex];
            const selectedSize = document.getElementById('size').options[document.getElementById('size').selectedIndex];
            price.firstChild.textContent = "Total Price: " + 
                (<?php echo $product['pPrice'] ?> + 
                Number(selectedFlavor.getAttribute('data-flavorPrice')) + 
                Number(selectedSize.getAttribute('data-sizePrice')))
                * Number(document.getElementsByName('cakeQuantity')[0].value);
            };

        updatePrice();

        inputs.forEach(input => {
            console.log("OKAY");
            input.addEventListener('input', updatePrice);
            input.addEventListener('change', updatePrice);
        });
    </script>
<?php }?>

</html>