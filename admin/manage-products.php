<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="css/manage-product.css">
    <script src="js/navbar-loader.js" defer></script>
    <?php
        session_start();
        require('../controller/db_model.php');
        $stmt = $conn->prepare("SELECT * FROM products");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(isset($_GET['toDisable'])){
            $stmt = $conn->prepare("UPDATE products SET pVisibility = 2 WHERE pID = ?");
            $stmt->execute([$_GET['toDisable']]);
            header('Location: manage-products.php');
        }

        if(isset($_GET['toEnable'])){
            $stmt = $conn->prepare("UPDATE products SET pVisibility = 1 WHERE pID = ?");
            $stmt->execute([$_GET['toEnable']]);
            header('Location: manage-products.php');
        }
    ?>
</head>
<body>
    <?php include 'layout/navbar.php'; ?>

    <section id="main-container">
      
        <main>
            <h1>Manage Products</h1>
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Product Image</th>
                        <th>Product Name</th>
                        <th>Product Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example product row -->
                    <?php foreach($products as $product){ ?>
                        <tr>
                            <td><img src="../product-gallery/<?= array('Cookie', 'Pastry', 'Cake')[$product['pType']-1]."_".$product['pID'].".jpg"?>" class="product-image"></td>
                            <td><?= $product['pName'] ?></td>
                            <td>₱<?= $product['pPrice'] ?></td>
                            <td>
                                <?php if($product['pVisibility'] == 1){ ?>
                                <a href="manage-products.php?toDisable=<?=$product['pID']?>" class="btn disable" style="text-decoration: none;" data-id="<?= $product['pID'] ?>">Disable</a>
                                <?php }else{ ?>
                                <a href="manage-products.php?toEnable=<?=$product['pID']?>" class="btn enable" style="text-decoration: none;" data-id="<?= $product['pID'] ?>">Enable</a>
                                <?php } ?>
                                <button class="btn delete" data-id="<?= $product['pID'] ?>">Delete</button>
                                <button class="btn" data-id="<?= $product['pID'] ?>">Edit</button>
                                <button class="btn success" data-id="<?= $product['pID'] ?>">Feature</button>
                            </td>
                        </tr>
                    <?php } ?>
                    <!-- Repeat above row for additional products -->
                </tbody>
            </table>
        </main>
    </section>
</body>
</html>