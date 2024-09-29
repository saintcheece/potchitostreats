<?php 
    session_start();
    require(__DIR__ . '/../controller/db_model.php');
    $stmt = $conn->prepare("SELECT * FROM products");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //DISPLAY CARDS
    function displayProductCards($products, $type) {
        $counter = 0;
        foreach ($products as $product) {
            if($product['pType'] == $type){
                if($counter % 3 == 0){
                    echo '<div class="row">';
                }
                
                echo '
                    <div class="col-md-4">
                        <a href="product-view.php?id='.$product['pID'].'&type='.$type.'" class="product-link">
                        <div class="card mb-4">
                            <img src="..\assets\image.png" class="card-img-top" alt="Fruit 3">
                            <div class="card-body">
                                <h5 class="card-title">'.$product['pName'].'</h5>
                                <p class="card-text">'.$product['pPrice'].'</p>';
                if(isset($_SESSION['userID']) && $_SESSION['userID'] != null) {
                    echo '<form action="product-by-type.php" method="post">
                            <input type="hidden" name="addToCart" value="'.$product['pID'].'">
                            <button type="submit">Add to Cart</button>
                            </form>';
                }
                
                echo '</div>
                    </div>
                    </a>
                </div>';
                $counter++;
                
                if($counter % 3 == 0){
                    echo '</div>';
                }
            }
        }
        if($counter % 3 != 0){
            echo '</div>';
        }
    }
?>