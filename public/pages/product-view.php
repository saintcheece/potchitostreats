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

        $currentQuantity = 0;

        if($transactionID){
            $stmt = $conn->prepare("SELECT oQty FROM orders WHERE tID = ? AND pID = ?");
            $stmt->execute([$transactionID, $_GET['id']]);
            $currentQuantity = $stmt->fetchColumn();
            if($currentQuantity === false){
                $currentQuantity = 0;
            }
        }

        // GET CAKE CUSTOMIZATIONS
        $stmt = $conn->prepare("SELECT COUNT(*) FROM cakes WHERE pID = ? AND tID = ?");
        $stmt->execute([$_GET['id'], $transactionID]);
        $count = $stmt->fetchColumn();
        if($count > 0){
            $stmt = $conn->prepare("SELECT c.*, o.oQty, cf.cfPrice, cs.csSize, c.cLayers, c.ccID, p.pPrice 
                                    FROM cakes c
                                    INNER JOIN transactions t ON c.tID = t.tID
                                    INNER JOIN orders o ON t.tID = o.tID
                                    INNER JOIN products p ON o.pID = p.pID
                                    INNER JOIN cakes_flavor cf ON p.pID = cf.pID    
                                    INNER JOIN cakes_size cs ON p.pID = cs.pID
                                    INNER JOIN cakes_color cc ON p.pID = cc.pID
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

                $stmt = $conn->prepare("SELECT cID FROM cakes WHERE tID = ? AND pID = ? LIMIT 1");
                $stmt->execute([$transactionID, $_GET['id']]);
                $cid = $stmt->fetchColumn();

                if (isset($_FILES['cakeReference'])) {
                    $newname = "cRef"."_$cid.jpg";
                    if(move_uploaded_file($_FILES['cakeReference']['tmp_name'], "../../reference-gallery/$newname")) {
                        echo "File uploaded successfully!";
                      } else {
                        echo "Error uploading file!";
                      }
                } 

                header("Location: cart.php");
            } else {

                // add this product to the db
                $stmt = $conn->prepare("INSERT INTO orders (tID, pID, oQty) VALUES (?, ?, ?)");
                $stmt->execute([$transactionID, $_GET['id'], $_POST['cakeQuantity']]);
                $orderID = $conn->lastInsertId();

                if($_GET['type'] == 3){ addCakeToTransaction($transactionID, $_GET['id'], $orderID, $conn, $_POST); }

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
            $orderID = $conn->lastInsertId();

            if($_GET['type'] == 3){ addCakeToTransaction($transactionID, $_GET['id'], $orderID, $conn, $_POST); }

            header("Location: cart.php");
        }
    }

    if(isset($_POST['noncake-quantity'])){
        if ($transactionID != null) {
            // if order of product exists, update the order details
            $stmt = $conn->prepare("SELECT oID FROM orders WHERE tID = ? AND pID = ?");
            $stmt->execute([$transactionID, $_GET['id']]);
            $orderID = $stmt->fetchColumn();
            
            // if order of product exists, increment the quantity
            if ($orderID != null) {
                $stmt = $conn->prepare("UPDATE orders SET oQty = ? WHERE oID = ?");
                $stmt->execute([$_POST['noncake-quantity'], $orderID]);
            }else{
                $stmt = $conn->prepare("INSERT INTO orders (tID, pID, oQty) VALUES (?, ?, ?)");
                $stmt->execute([$transactionID, $_GET['id'], $_POST['noncake-quantity']]);
                $orderID = $conn->lastInsertId();
            }
        }else{
        // if no transaction exists, create one
        $stmt = $conn->prepare("INSERT INTO transactions (uID, tStatus) VALUES (?, 1)");
        $stmt->execute([$_SESSION['userID']]);
        $transactionID = $conn->lastInsertId();
        // then add the product
        $stmt = $conn->prepare("INSERT INTO orders (tID, pID, oQty) VALUES (?, ?, ?)");
        $stmt->execute([$transactionID, $_GET['id'], $_POST['noncake-quantity']]);
        $orderID = $conn->lastInsertId();
        }

        header('Location: cart.php');
    }

    function addCakeToTransaction($tID, $pID, $oID, $conn, $cakeDetails){

        $stmt = $conn->prepare("INSERT INTO cakes (tID, pID, oID, cfID, csID, cLayers, ccID, cMessage, cInstructions) VALUES (:tID, :pID, :oID, :cakeFlavor, :cakeSize, :cakeLayers, :cakeColor, :cakeMessage, :cakeInstructions)");
        $stmt->execute([
            'tID' => $tID,
            'pID' => $pID,
            'oID' => $oID,
            'cakeFlavor' => $cakeDetails['cakeFlavor'],
            'cakeSize' => $cakeDetails['cakeSize'],
            'cakeLayers' => $cakeDetails['cakeLayers'],
            'cakeColor' => $cakeDetails['cakeColor'],
            'cakeMessage' => $cakeDetails['cakeMessage'],
            'cakeInstructions' => $cakeDetails['cakeInstructions']
        ]);

        $cid = $conn->lastInsertId();

        if (isset($_FILES['cakeReference'])) {
            echo "exitst";
            $newname = "cRef"."_$cid.jpg";
            if(move_uploaded_file($_FILES['cakeReference']['tmp_name'], "../../reference-gallery/$newname")) {
                echo "File uploaded successfully!";
              } else {
                echo "Error uploading file!";
              }
        } 
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
                        <form action="product-view.php?id=<?= $_GET['id']?>&type=1" method="POST">
                            <div class="input-group mb-3">
                                <span class="input-group-text">Quantity</span>
                            <input type="number" name="noncake-quantity" class="form-control custom-input" min="1" value="<?php if($currentQuantity == 0){ echo 1; } else { echo $currentQuantity; }?>">
                            </div>  
                            <?php if($currentQuantity = 0){ ?>
                                <button class="btn btn-primary mt-2" style="width: 200px;">Add to Cart</button>
                            <?php } else { ?>
                                <button class="btn btn-outline-primary mt-2" style="width: 200px;">Update Cart</button>
                            <?php } ?>
                        </form>
                    <!-- Quantity input -->
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
        <div class="custom-cake-image-container">
            <img src="../../product-gallery/<?= array('Cookie', 'Pastry', 'Cake')[$product['pType']-1]."_".$product['pID'].".jpg"?>" alt="">
        </div>
        <form action="product-view.php?id=<?= $_GET['id']?>&type=3" method="POST" class="form-container p-3" enctype="multipart/form-data">
            <h1><b><?= $product['pName']?></b></h1>
            <p id="description-cake">
                <?= $product['pDesc']?>
            </p><br/>
            <small id="allergens"> May Contains Soy, Nut, and Eggs</small>

            <hr />
            <div class="grid-container mt-1">
                <div class="grid-item">
                    <!-- CAKE FLAVOR -->
                    <p class="m-0">Choose Cake Flavor</p>
                    <select name="cakeFlavor" id="flavor">
                        <?php
                            $stmt = $conn->prepare("SELECT * FROM cakes_flavor WHERE pID = ?");
                            $stmt->execute([$product['pID']]);
                            $flavors = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach($flavors as $flavor){
                                $selected = isset($cakeDetails['cfID']) && $cakeDetails['cfID'] == $flavor['cfID'] ? 'selected' : '';
                                echo "<option value='{$flavor['cfID']}' data-flavorPrice='{$flavor['cfPrice']}' $selected>{$flavor['cfName']}<i> (₱{$flavor['cfPrice']} per inch)</i></option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="grid-item">
                    <!-- CAKE SIZE -->
                    <p class="m-0">Choose Cake Size</p> 
                    <select name="cakeSize" id="size">
                        <?php
                            $stmt = $conn->prepare("SELECT * FROM cakes_size WHERE pID = ?");
                            $stmt->execute([$product['pID']]);
                            $sizes = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach($sizes as $size){
                                $selected = isset($cakeDetails['csID']) && $cakeDetails['csID'] == $size['csID'] ? 'selected' : '';
                                echo "<option value='{$size['csID']}' data-size='{$size['csSize']}' $selected>{$size['csSize']}\"</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="grid-item">
                    <!-- CAKE SIZE -->
                    <?php
                            $stmt = $conn->prepare("SELECT * FROM cakes_layer WHERE pID = ?");
                            $stmt->execute([$product['pID']]);
                            $layer = $stmt->fetch(PDO::FETCH_ASSOC);
                        ?>
                    <p  class="m-0">Number of Internal Layers</p>
                    <input name="cakeLayers" class="form-control" type="number" max="<?=$layer['clMaxCount']?>" min="<?=$layer['clMinCount']?>" value='<?php if(isset($cakeDetails["cLayers"])){echo $cakeDetails["cLayers"];}else{echo $layer['clDefault'];}?>'>
                </div>
                <div class="grid-item">
                    <!-- CAKE COLORS -->
                    <p class="m-0">Choose Cake Color</p>
                    <select name="cakeColor" id="color">
                        <?php
                            $stmt = $conn->prepare("SELECT * FROM cakes_color WHERE pID = ?");
                            $stmt->execute([$product['pID']]);
                            $colors = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach($colors as $color){
                                $selected = isset($cakeDetails['ccID']) && $cakeDetails['ccID'] == $color['ccID'] ? 'selected' : '';
                                echo "<option value='{$color['ccID']}' data-color='{$color['ccHex']}' style='background-color:{$color['ccHex']} !important;' $selected>{$color['ccName']}</option>";
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
                    <label for="" class="form-label">Upload Reference:</label><br>
                    <input class="form-control" name="cakeReference" id="cakeReference" type="file" accept="image/*">
                    <small><i> Maximum File Size of 5MB</i></small>
                </div>

                <div class="grid-item">
                    <label for="" class="form-label">Quantity:</label><br>
                    <input class="form-control" name="cakeQuantity" type="number" min="1" step="1" value='<?php if(isset($cakeDetails["oQty"])){echo $cakeDetails["oQty"];}else{echo "1";}?>'>
                    <small><i> Applies the Same Customizations</i></small>
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
                            echo ($cakeDetails['pPrice'] + ($cakeDetails['cfPrice'] * $cakeDetails['csSize']) * $cakeDetails['oQty']);
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
            <small>Please see our <a href="#">Terms and Services</a> to see the rules for custom cake orders.</small>
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
                Number(selectedFlavor.getAttribute('data-flavorPrice'))
                * (Number(selectedSize.getAttribute('data-size')))
                * Number(document.getElementsByName('cakeLayers')[0].value))
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
