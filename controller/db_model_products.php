<?php 
    session_start();
    require('../../controller/db_model.php');
    $stmt = $conn->prepare("SELECT * FROM products");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //ADD TO CART
    if (isset($_POST['productID'])) {
        $productID = filter_input(INPUT_POST, 'productID', FILTER_SANITIZE_STRING);

        // GET TRANSACTION ID
        $stmt = $conn->prepare("SELECT tID FROM transactions WHERE uID = ? AND tStatus= 1");
        $stmt->execute([$_SESSION['userID']]);
        $transactionID = $stmt->fetchColumn();

        // if transaction exists
        if ($transactionID != null) {
            // if order of product exists, increment the quantity
            $stmt = $conn->prepare("SELECT oID FROM orders WHERE tID = ? AND pID = ?");
            $stmt->execute([$transactionID, $productID]);
            $orderID = $stmt->fetchColumn();
            if ($orderID != null) {
                $stmt = $conn->prepare("UPDATE orders SET oQty = oQty + 1 WHERE oID = ?");
                $stmt->execute([$orderID]);
            } else {
                // add this product to the db
                $stmt = $conn->prepare("INSERT INTO orders (tID, pID, oQty) VALUES (?, ?, 1)");
                $stmt->execute([$transactionID, $productID]);
            }
        } else {
            // if no transaction exists, create one
            $stmt = $conn->prepare("INSERT INTO transactions (uID, tType, tStatus) VALUES (?, 2, 1)");
            $stmt->execute([$_SESSION['userID']]);
            $transactionID = $conn->lastInsertId();
            $stmt = $conn->prepare("INSERT INTO orders (tID, pID, oQty) VALUES (?, ?, 1)");
            $stmt->execute([$transactionID, $productID]);
        }
    }

    //DISPLAY CARDS
    function displayProductCards($products, $type) {
        $counter = 0;
        foreach ($products as $product) {
            if($product['pType'] == $type){
                if($counter % 3 == 0){
                    echo '<div class="row">';
                }
                
                echo '<div class="col-md-4">
                        <div class="card mb-4">
                            <img src="..\assets\image.png" class="card-img-top" alt="Fruit 3">
                            <div class="card-body">
                                <h5 class="card-title">'.$product['pName'].'</h5>
                                <p class="card-text">'.$product['pPrice'].'</p>';
                if(isset($_SESSION['userID']) && $_SESSION['userID'] != null) {
                    echo '<form action="products.php" method="post">
                            <button type="submit" name="productID" value="'.$product['pID'].'">Add to Cart</button>
                            </form>';
                }
                
                echo '</div>
                    </div>
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