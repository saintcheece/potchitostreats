<?php
    require('db_model.php');
    if(isset($_POST['updateOrder'])){
        $stmt = $conn->prepare("UPDATE transactions SET tStatus = tStatus + 1 WHERE tID = ?");
        $stmt->execute([$_POST['updateOrder']]);
        audit(107);
    }

    if(isset($_POST['cancelOrder'])){
        $stmt = $conn->prepare("UPDATE transactions SET tStatus = 0 WHERE tID = ?");
        $stmt->execute([$_POST['cancelOrder']]);
        audit(108);
    }
?>