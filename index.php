<?php 
    require('controller/db_model.php');

    $stmt = $conn->prepare("INSERT INTO visit (vDate, vCount) VALUES (CURDATE(), 1) ON DUPLICATE KEY UPDATE vCount = vCount + 1");
    $stmt->execute();

    header('Location: public/pages/index.php')?>