<?php
    session_start();
    require('../../controller/db_model.php');

    $stmt = $conn->prepare("SELECT * FROM products WHERE pID = ?");
    $stmt->execute([$_GET['id']]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
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
        <div class="form-container p-3">
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
                    <select name="flavor" id="flavor">
                        <option value="chocolate">Chocolate</option>
                        <option value="vanilla">Vanilla</option>
                        <option value="strawberry">Strawberry</option>
                        <option value="red-velvet">Red Velvet</option>
                    </select>
                </div>
                <div class="grid-item">
                    <p>Choose Cake Size</p>
                    <select name="size" id="size">
                        <option value="6-inch">6-inch</option>
                        <option value="8-inch">8-inch</option>
                        <option value="10-inch">10-inch</option>
                        <option value="12-inch">12-inch</option>
                    </select>
                </div>
                <div class="grid-item full-width">
                    <label id="input-message">Input Message</label>
                    <textarea name="message" id="message" placeholder="Enter your message..." maxlength="200"></textarea>
                </div>
                <!-- Image upload field -->
                <div class="grid-item">
                    <label for="" class="form-label">Upload Reference:</label><br>
                    <input class="form-control" type="file" accept="image/*">
                    <small>Max of 5mb</small>
                </div>

                <div class="grid-item">
                    <p>Quantity</p>
                    <input class="form-control" type="number" min="1" step="1" value="1">
                </div>

                <!-- Original message for the cake -->
            

                <!-- Additional instructions with max char limit -->
                <div class="grid-item full-width">
                    <p id="additional-instruction-p">Additional Instructions (optional)</p>
                    <textarea name="instructions" id="instructions" maxlength="300" placeholder="Enter any special requests or instructions (max 300 characters)"></textarea>
                </div>
                
                <div class="divider m-2"></div>

                <div class="total-and-cart">
                    <p class="total-price"><b>Total Price: $45.00</b></p>
                    <button id="add-to-cart">Add to Cart</button>
                </div>
            </div>
        </div>
    </section>

    <?php include 'layout/footer.php'; ?>

</body>
<script>
    // Get today's date in YYYY-MM-DD format
    const today = new Date();
    const dd = String(today.getDate()).padStart(2, '0');
    const mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
    const yyyy = today.getFullYear();
    const minDate = yyyy + '-' + mm + '-' + dd; // Format: YYYY-MM-DD
    
    // Set the min attribute
    document.getElementById('pickup-date').setAttribute('min', minDate);
</script>
<?php }?>
</html>