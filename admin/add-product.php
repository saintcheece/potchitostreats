<?php
session_start();
require_once("../controller/db_model.php");
if (isset($_POST['productName'])) {
    $productName = filter_input(INPUT_POST, 'productName', FILTER_SANITIZE_STRING);
    $productPrice = filter_input(INPUT_POST, 'productPrice', FILTER_SANITIZE_STRING);
    $productDescription = filter_input(INPUT_POST, 'productDescription', FILTER_SANITIZE_STRING);
    $productPrepTime = (int) filter_input(INPUT_POST, 'productPrepTime', FILTER_SANITIZE_NUMBER_INT);

    $productType = filter_input(INPUT_POST, 'productType', FILTER_SANITIZE_STRING);

    if ($productType == 3) {
    $cakeFlavorNames = filter_input(INPUT_POST, 'cakeFlavorName', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $cakeFlavorPrices = filter_input(INPUT_POST, 'cakeFlavorPrice', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $cakeSizeNames = filter_input(INPUT_POST, 'cakeSizeName', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

    $cakeColorNames = filter_input(INPUT_POST, 'cakeColorName', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $cakeColor = filter_input(INPUT_POST, 'cakeColor', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

    $layerDefault = filter_input(INPUT_POST, 'layerDefault', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $layerMinimum = filter_input(INPUT_POST, 'layerMinimum', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $layerMaximum = filter_input(INPUT_POST, 'layerMaximum', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    $cakeFlavorNames = array_map(function($element){
        return filter_var($element, FILTER_SANITIZE_STRING);
    }, $cakeFlavorNames);
    $cakeFlavorPrices = array_map(function($element){
        return filter_var($element, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }, $cakeFlavorPrices);

    $cakeSizeNames = array_map(function($element){
        return filter_var($element, FILTER_SANITIZE_STRING);
    }, $cakeSizeNames);

    $cakeColorNames = array_map(function($element){
        return filter_var($element, FILTER_SANITIZE_STRING);
    }, $cakeColorNames);
    $cakeColor = array_map(function($element){
        return filter_var($element, FILTER_SANITIZE_STRING);
    }, $cakeColor);


    $layerDefault = filter_var($layerDefault, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $layerMinimum = filter_var($layerMinimum, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $layerMaximum = filter_var($layerMaximum, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);


    }
    
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
    $newProduct = "INSERT INTO products (pName, pPrice, pDesc, pType, pPrepTime) 
                   VALUES ('$productName', '$productPrice', '$productDescription', '$productType', '$productPrepTime')";
    $stmt = $conn->prepare($newProduct);
    $stmt->execute();
    $pid = $conn->lastInsertId();
    audit('103');

    if (isset($_FILES['fileField'])) {
        $newname = "$typeString"."_$pid.jpg";
        if(move_uploaded_file($_FILES['fileField']['tmp_name'], "../product-gallery/$newname")) {
            // echo "File uploaded successfully!";
          } else {
            // echo "Error uploading file!";
          }
    } 

    // insert into cake_flavor each cakeFlavorNames and cakeFlavorPrices
    if ($productType == 3) {
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
            $stmt = $conn->prepare("INSERT INTO cakes_size (pID, csSize) VALUES (:pID, :csName)");
            $stmt->bindParam(':pID', $pid);
            foreach ($cakeSizeNames as $csName) {
                $stmt->bindParam(':csName', $csName);
                $stmt->execute();
            }
        }

        // insert into cake_color each cakeColorNames and cakeColor
        if (count($cakeColorNames) > 0) {
            $stmt = $conn->prepare("INSERT INTO cakes_color (pID, ccName, ccHex) VALUES (:pID, :ccName, :ccHex)");
            $stmt->bindParam(':pID', $pid);
            $stmt->bindParam(':ccName', $ccName);
            $stmt->bindParam(':ccHex', $ccColor);
            foreach ($cakeColorNames as $i => $ccName) {
                $ccColor = $cakeColor[$i];
                $stmt->execute();
            }
        }

        // insert into cake_layer each layerDefault, layerMinimum, layerMaximum
        if ($layerDefault > 0) {
            $stmt = $conn->prepare("INSERT INTO cakes_layer (pID, clDefault, clMinCount, clMaxCount) VALUES (:pID, :clDefault, :clMinimum, :clMaximum)");
            $stmt->bindParam(':pID', $pid);
            $stmt->bindParam(':clDefault', $layerDefault);
            $stmt->bindParam(':clMinimum', $layerMinimum);
            $stmt->bindParam(':clMaximum', $layerMaximum);
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
<body id="main" class="p-0">
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
                    <label for="productPrice">Base Product Price (₱):</label>
                    <input type="number" id="product-price" name="productPrice" step="0.01" placeholder="Enter product price" required>
                </div>
                <div class="form-group">
                    <label for="productPrepTime">Time To Process (hrs):</label>
                    <input type="number" id="product-preptime" name="productPrepTime" step="0.01" placeholder="Enter how long it takes to prepare the product" required>
                </div>
                <div class="form-group">
                    <label for="productType">Product Type:</label>
                    <select class="form-select" id="product-type" name="productType" required>
                        <option value="">Select a category</option>
                        <option value="1">Cookies</option>
                        <option value="2">Pastries</option>
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

                <!-- COLOR INPUT -->
                <div class="form-group" id="ColorGroup" class="flex">
                    <label id="colorLabel" style="display: none;">Color Options:</label>
                    <div id="colorInput">
                    </div>
                    <div class="d-grid gap-2">
                        <button type="button" id="addColor" class="btn btn-outline-secondary my-2" style="display: none;">Add Color</button>
                    </div>
                </div>

                <div class="form-group" id="LayerGroup">
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
        const colorGroup = document.getElementById('ColorGroup');
        const layerGroup = document.getElementById('LayerGroup');

        select.addEventListener('change', (e) => {
            const flavorLabel = document.getElementById('flavorLabel');
            const addFlavor = document.getElementById('addFlavor');
            const flavorInput = document.getElementById('flavorInput');

            const sizeLabel = document.getElementById('sizeLabel');
            const addSize = document.getElementById('addSize');
            const sizeInput = document.getElementById('sizeInput');
            
            const colorLabel = document.getElementById('colorLabel');
            const addColor = document.getElementById('addColor');
            const colorInput = document.getElementById('colorInput');

            if(e.target.value === "3"){
                flavorLabel.style.display = 'block';
                addFlavor.style.display = 'block';

                sizeLabel.style.display = 'block';
                addSize.style.display = 'block';

                colorLabel.style.display = 'block';
                addColor.style.display = 'block';

                if(flavorInput.childElementCount === 0){
                    const newInput = document.createElement('div');
                    newInput.classList.add('d-flex', 'flex-row');
                    newInput.innerHTML = `
                        <div class="d-flex flex-row">
                            <input type="text" name="cakeFlavorName[]" placeholder="Flavor Name" required>
                            <input type="number" name="cakeFlavorPrice[]" 
                            placeholder="Flavor Price per inch" step="0.01" required>
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
                        </div>
                    `;
                    sizeInput.appendChild(newInput);
                }

                if(colorInput.childElementCount === 0){
                    const newInput = document.createElement('div');
                    newInput.classList.add('d-flex', 'flex-row');
                    newInput.innerHTML = `
                        <div class="d-flex flex-row">
                            <input type="text" name="cakeColorName[]" placeholder="Color Name" required>
                            <input type="color" class="form-control form-control-color" name="cakeColor[]" required>
                        </div>
                    `;
                    colorInput.appendChild(newInput);
                }

                if(layerGroup.childElementCount === 0){
                    const newInput = document.createElement('div');
                    newInput.innerHTML = `
                        <label for="layerDefault">Default Layers:</label>
                        <input type="number" id="layerDefault" name="layerDefault" step="0.01" placeholder="Enter Default Layers" required>
                        <label for="layerMinimum">Minimum Layers:</label>
                        <input type="number" id="layerMinimum" name="layerMinimum" step="0.01" placeholder="Enter Minimum Layers" required>
                        <label for="layerMaximum">Maximum Layers:</label>
                        <input type="number" id="layerMaximum" name="layerMaximum" step="0.01" placeholder="Enter Maximum Layers" required>
                    `;
                    layerGroup.appendChild(newInput);
                }

            } else {
                flavorLabel.style.display = 'none';
                addFlavor.style.display = 'none';

                sizeLabel.style.display = 'none';
                addSize.style.display = 'none';

                colorLabel.style.display = 'none';
                addColor.style.display = 'none';

                flavorInput.innerHTML = '';
                sizeInput.innerHTML = '';
                colorInput.innerHTML = '';
                layerGroup.innerHTML = '';

            }
        });

        const addFlavor = document.getElementById('addFlavor');
        const addSize = document.getElementById('addSize');
        const addColor = document.getElementById('addColor');

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
                </div>
            `;
            sizeInput.appendChild(newInput);
        });

        addColor.addEventListener('click', (e) => {
            const colorInput = document.getElementById('colorInput');
            const newInput = document.createElement('div');
            newInput.classList.add('d-flex', 'flex-row');
            newInput.innerHTML = `
                <div class="d-flex flex-row">
                    <button type="button" class="btn-close align-self-center mx-2" aria-label="Close" onclick="this.parentElement.remove()"></button>
                    <input type="text" name="cakeColorName[]" placeholder="Color Name" required>
                    <input type="color" class="form-control form-control-color" name="cakeColor[]" required>
                </div>
            `;
            colorInput.appendChild(newInput);
        });
        
    </script>
</html>
