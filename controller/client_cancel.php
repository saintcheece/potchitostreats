<?php
    session_start();

    require('db_model.php');

    $stmt = $conn->prepare("UPDATE transactions SET tStatus = 0 WHERE tID = ?");
    $stmt->execute([$_POST['transactionID']]);

    echo $_POST['transactionID'];