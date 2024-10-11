<?php 
    session_start();
    require(__DIR__ . '/../controller/db_model.php');
    $stmt = $conn->prepare("SELECT * FROM products WHERE pVisibility = 1");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //DISPLAY CARDS
    function displayProductCards($products, $type) {
        echo '<div class="row">';
        foreach ($products as $product) {
            if($product['pType'] == $type){?> 
                    <div class="col-md-4">
                        <a href="product-view.php?id=<?= $product['pID'] ?>&type=<?= $type ?>" class="product-link">
                        <div class="card mb-4">
                            <img src="../../product-gallery/<?= array('Cookie', 'Pastry', 'Cake')[$product['pType']-1]."_".$product['pID'].".jpg"?>" class="card-img-top">
                            <div class="card-body">
                                <h5 class="card-title"><?= $product['pName'] ?></h5>
                                <p class="card-text">₱<?= $product['pPrice'] ?></p>
                                <?php if(isset($_SESSION['userID']) && $_SESSION['userID'] != null) { ?>
                                    <form action="<?= $product['pType'] == 3 ? 'product-view.php?id=' . $product['pID'] . '&type=3' : 'product-by-type.php' ?>" method="post">
                                        <input type="hidden" name="addToCart" value="<?= $product['pID'] ?>">
                                        <button class="btn btn-primary" type="submit">Add to Cart</button>
                                    </form>
                                <?php }?> 
                            </div>
                        </a>
                    </div>
                </div>
            <?php }
        }
        echo '</div>';
    }
?>
