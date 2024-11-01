<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="css/manage-product.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="js/navbar-loader.js" defer></script>
    <?php
    session_start();
    require('../controller/db_model.php');

    $products = [];

    try {
        $stmt = $conn->prepare("SELECT * FROM products ORDER BY pID DESC");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Feature/unfeature logic
    if (isset($_GET['toFeature'])) {
        $stmt = $conn->prepare("UPDATE products SET pFeatured = 2 WHERE pID = ?");
        $stmt->execute([$_GET['toFeature']]);
        header('Location: manage-products.php');
    }

    // Update to remove from feature
    if (isset($_GET['toUnfeature'])) {
        $stmt = $conn->prepare("UPDATE products SET pFeatured = 0 WHERE pID = ?");
        $stmt->execute([$_GET['toUnfeature']]);
        header('Location: manage-products.php');
    }

    // Enable/disable logic 
    if (isset($_GET['toDisable'])) {
        $stmt = $conn->prepare("UPDATE products SET pVisibility = 0 WHERE pID = ?");
        $stmt->execute([$_GET['toDisable']]);
        header('Location: manage-products.php');
        exit();
    }

    if (isset($_GET['toEnable'])) {
        $stmt = $conn->prepare("UPDATE products SET pVisibility = 1 WHERE pID = ?");
        $stmt->execute([$_GET['toEnable']]);
        header('Location: manage-products.php');
        exit();
    }


    // Editing logic
    if (isset($_POST['editProduct'])) {
        $pID = $_POST['pID'];
        $pName = $_POST['pName'];
        $pPrice = $_POST['pPrice'];
        $stmt = $conn->prepare("UPDATE products SET pName = ?, pPrice = ? WHERE pID = ?");
        $stmt->execute([$pName, $pPrice, $pID]);
        header('Location: manage-products.php');
    }

?>




</head>
<body id="main" class="p-0">
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
                    <?php foreach($products as $product) { ?>
                        <tr>
                            <td><img src="../product-gallery/<?= array('Cookie', 'Pastry', 'Cake')[$product['pType']-1]."_".$product['pID'].".jpg" ?>" class="product-image"></td>
                            <td><?= $product['pName'] ?></td>
                            <td>₱<?= $product['pPrice'] ?></td>
                            <td>
                                <?php if($product['pVisibility'] == 1){ ?>
                                    <a href="manage-products.php?toDisable=<?= $product['pID'] ?>" class="btn btn-danger" style="text-decoration: none;" data-id="<?= $product['pID'] ?>">Disable</a>
                                <?php } else { ?>
                                    <a href="manage-products.php?toEnable=<?= $product['pID'] ?>" class="btn btn-success" style="text-decoration: none;" data-id="<?= $product['pID'] ?>">Enable</a>
                                <?php } ?>

                                <?php if($product['pFeatured'] == 2){ ?>
                                    <a href="manage-products.php?toUnfeature=<?= $product['pID'] ?>" class="btn btn-warning">Remove from Featured</a>
                                <?php } else { ?>
                                    <a href="manage-products.php?toFeature=<?= $product['pID'] ?>" class="btn btn-primary">Feature</a>
                                <?php } ?>

                                <!-- Edit Button -->
                                <button class="btn btn-info edit-btn" data-id="<?= $product['pID'] ?>" data-name="<?= $product['pName'] ?>" data-price="<?= $product['pPrice'] ?>">Edit</button>
                            </td> 
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </main>
    </section>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="manage-products.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="pID" id="editProductId">
                        <div class="mb-3">
                            <label for="editProductName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" name="pName" id="editProductName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editProductPrice" class="form-label">Product Price</label>
                            <input type="number" class="form-control" name="pPrice" id="editProductPrice" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="editProduct" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', () => {
                const pID = button.getAttribute('data-id');
                const pName = button.getAttribute('data-name');
                const pPrice = button.getAttribute('data-price');

                document.getElementById('editProductId').value = pID;
                document.getElementById('editProductName').value = pName;
                document.getElementById('editProductPrice').value = pPrice;

                new bootstrap.Modal(document.getElementById('editModal')).show();
            });
        });
    </script>
</body>
</html>
