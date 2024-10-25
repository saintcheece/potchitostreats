<?php
    session_start();

    require('controller/db_model.php');
    $stmt = $conn->prepare("SELECT uType FROM users WHERE uID = ?");
    $stmt->execute([$_SESSION['userID']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $uType = $user['uType'];

    if($uType == 3){
        audit('102');
    }else if($uType == 1){
        audit('202');
    }
    
    session_destroy();
    header("Location: index.php");
?>