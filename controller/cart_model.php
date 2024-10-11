<?php

if(isset($_POST['orderId'], $_POST['productId'], $_POST['quantity'])){
    require('conn.php');

    $stmt = $conn->prepare("UPDATE orders SET oQty = ? WHERE tID = ? AND pID = ?");
    $stmt->execute([$_POST['quantity'], $_POST['orderId'], $_POST['productId']]);

    echo "HHH";

}
