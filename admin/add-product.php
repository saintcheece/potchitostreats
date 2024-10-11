<?php
session_start();
require_once("../controller/db_model.php");
if (isset($_POST['productName'])) {
    $productName = filter_input(INPUT_POST, 'productName', FILTER_SANITIZE_STRING);
    $productPrice = filter_input(INPUT_POST, 'productPrice', FILTER_SANITIZE_STRING);
    $productDescription = filter_input(INPUT_POST, 'productDescription', FILTER_SANITIZE_STRING);
    $productType = filter_input(INPUT_POST, 'productType', FILTER_SANITIZE_STRING);
    $cakeFlavorNames = filter_input(INPUT_POST, 'cakeFlavorName', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $cakeFlavorPrices = filter_input(INPUT_POST, 'cakeFlavorPrice', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $cakeSizeNames = filter_input(INPUT_POST, 'cakeSizeName', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $cakeSizePrices = filter_input(INPUT_POST, 'cakeSizePrice', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

    $cakeFlavorNames = array_map(function($element){
        return filter_var($element, FILTER_SANITIZE_STRING);
    }, $cakeFlavorNames);

    $cakeFlavorPrices = array_map(function($element){
        return filter_var($element, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }, $cakeFlavorPrices);

    $cakeSizeNames = array_map(function($element){
        return filter_var($element, FILTER_SANITIZE_STRING);
    }, $cakeSizeNames);

    $cakeSizePrices = array_map(function($element){
        return filter_var($element, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }, $cakeSizePrices);


    $typeString;

    switch($_POST['productType']){
        case 1: $typeString = "Cookie";
        break;
        case 2: $typeString = "Pastry";
        break;
        case 3: $typeString = "Cake";
        break;
    }

    // add this product to the db
    $newProduct = "INSERT INTO products (pName, pPrice, pDesc, pType) 
                   VALUES ('$productName', '$productPrice', '$productDescription', '$productType')";
    $stmt = $conn->prepare($newProduct);
    $stmt->execute();
    $pid = $conn->lastInsertId();

    if (isset($_FILES['fileField'])) {
        $newname = "$typeString"."_$pid.jpg";
        if(move_uploaded_file($_FILES['fileField']['tmp_name'], "../product-gallery/$newname")) {
            // echo "File uploaded successfully!";
          } else {
            // echo "Error uploading file!";
          }
    } 

    // insert into cake_flavor each cakeFlavorNames and cakeFlavorPrices
    if (count($cakeFlavorNames) > 0) {
        $stmt = $conn->prepare("INSERT INTO cakes_flavor (pID, cfName, cfPrice) VALUES (:pID, :cfName, :cfPrice)");
        $stmt->bindParam(':pID', $pid);
        $stmt->bindParam(':cfName', $cfName);
        $stmt->bindParam(':cfPrice', $cfPrice);
        foreach ($cakeFlavorNames as $i => $cfName) {
            $cfPrice = $cakeFlavorPrices[$i];
            $stmt->execute();
        }
    }

    // insert into cake_size each cakeSizeNames and cakeSizePrices
    if (count($cakeSizeNames) > 0) {
        $stmt = $conn->prepare("INSERT INTO cakes_size (pID, csSize, csPrice) VALUES (:pID, :csName, :csPrice)");
        $stmt->bindParam(':pID', $pid);
        $stmt->bindParam(':csName', $csName);
        $stmt->bindParam(':csPrice', $csPrice);
        foreach ($cakeSizeNames as $i => $csName) {
            $csPrice = $cakeSizePrices[$i];
            $stmt->execute();
        }
    }
    header("Location: manage-products.php");
}
?>

<!DOCTYPE html>
<html lang="en">    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <link rel="stylesheet" href="css/add-product.css">
    <script src="js/navbar-loader.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
</head>
<body>
    <?php include 'layout/navbar.php'; ?>

    <section id="main-container">
        
        <main>
            <h1 class="page-title">Add New Product</h1>
            <form action="add-product.php" method="POST" class="add-product-form" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="fileField">Product Image:</label>
                    <input type="file" id="fileField" name="fileField">
                </div>
                <div class="form-group">
                    <label for="productName">Product Name:</label>
                    <input type="text" id="product-name" name="productName" placeholder="Enter product name" required>
                </div>
                <div class="form-group">
                    <label for="productDescription">Product Description:</label>
                    <textarea id="product-description" name="productDescription" rows="4" placeholder="Enter product description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="productPrice">Product Price ($):</label>
                    <input type="number" id="product-price" name="productPrice" step="0.01" placeholder="Enter product price" required>
                </div>
                <div class="form-group">
                    <label for="productType">Product Type:</label>
                    <select class="form-select" id="product-type" name="productType" required>
                        <option value="">Select a category</option>
                        <option value="1">Cookies</option>
                        <option value="2">Buns</option>
                        <option value="3">Cake</option>
                    </select>
                </div>
                <!-- FLAVOR INPUT -->
                <div class="form-group" id="flavorGroup" class="flex">
                    <label id="flavorLabel" style="display: none;">Flavor Options:</label>
                    <div id="flavorInput">
                    </div>
                    <div class="d-grid gap-2">
                        <button type="button" id="addFlavor" class="btn btn-outline-secondary my-2" style="display: none;">Add Flavor</button>
                    </div>
                </div>

                <!-- SIZE INPUT -->
                <div class="form-group" id="SizeGroup" class="flex">
                    <label id="sizeLabel" style="display: none;">Size Options:</label>
                    <div id="sizeInput">
                    </div>
                    <div class="d-grid gap-2">
                        <button type="button" id="addSize" class="btn btn-outline-secondary my-2" style="display: none;">Add Size</button>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn submit">Add Product</button>
                </div>
            </form>
        </main>
    </section>
</body>
    <script>
        const select = document.getElementById('product-type');
        const flavorGroup = document.getElementById('flavorGroup');
        const sizeGroup = document.getElementById('SizeGroup');

        select.addEventListener('change', (e) => {
            const flavorLabel = document.getElementById('flavorLabel');
            const addFlavor = document.getElementById('addFlavor');
            const flavorInput = document.getElementById('flavorInput');
            const sizeLabel = document.getElementById('sizeLabel');
            const addSize = document.getElementById('addSize');
            const sizeInput = document.getElementById('sizeInput');

            if(e.target.value === "3"){
                flavorLabel.style.display = 'block';
                addFlavor.style.display = 'block';
                sizeLabel.style.display = 'block';
                addSize.style.display = 'block';

                if(flavorInput.childElementCount === 0){
                    const newInput = document.createElement('div');
                    newInput.classList.add('d-flex', 'flex-row');
                    newInput.innerHTML = `
                        <div class="d-flex flex-row">
                            <input type="text" name="cakeFlavorName[]" placeholder="Flavor Name" required>
                            <input type="number" name="cakeFlavorPrice[]" 
                            placeholder="Flavor Price" step="0.01" required>
                        </div>
                    `;
                    flavorInput.appendChild(newInput);
                }

                if(sizeInput.childElementCount === 0){
                    const newInput = document.createElement('div');
                    newInput.classList.add('d-flex', 'flex-row');
                    newInput.innerHTML = `
                        <div class="d-flex flex-row">
                            <input type="text" name="cakeSizeName[]" placeholder="Size (inches)" required>
                            <input type="number" name="cakeSizePrice[]" 
                            placeholder="Size Price" step="0.01" required>
                        </div>
                    `;
                    sizeInput.appendChild(newInput);
                }
            } else {
                flavorLabel.style.display = 'none';
                addFlavor.style.display = 'none';
                sizeLabel.style.display = 'none';
                addSize.style.display = 'none';
                flavorInput.innerHTML = '';
                sizeInput.innerHTML = '';
            }
        });

        const addFlavor = document.getElementById('addFlavor');
        const addSize = document.getElementById('addSize');

        addFlavor.addEventListener('click', (e) => {
            const flavorInput = document.getElementById('flavorInput');
            const newInput = document.createElement('div');
            newInput.classList.add('d-flex', 'flex-row');
            newInput.innerHTML = `
                <div class="d-flex flex-row">
                    <button type="button" class="btn-close align-self-center mx-2" aria-label="Close" onclick="this.parentElement.remove()"></button>
                    <input type="text" name="cakeFlavorName[]" placeholder="Flavor Name" required>
                    <input type="number" name="cakeFlavorPrice[]" 
                    placeholder="Flavor Price" step="0.01" required>
                </div>
            `;
            flavorInput.appendChild(newInput);
        });

        addSize.addEventListener('click', (e) => {
            const sizeInput = document.getElementById('sizeInput');
            const newInput = document.createElement('div');
            newInput.classList.add('d-flex', 'flex-row');
            newInput.innerHTML = `
                <div class="d-flex flex-row">
                    <button type="button" class="btn-close align-self-center mx-2" aria-label="Close" onclick="this.parentElement.remove()"></button>
                    <input type="text" name="cakeSizeName[]" placeholder="Size Name" required>
                    <input type="number" name="cakeSizePrice[]" 
                    placeholder="Size Price" step="0.01" required>
                </div>
            `;
            sizeInput.appendChild(newInput);
        });
    </script>
</html>
