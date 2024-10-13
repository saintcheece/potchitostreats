<?php
    session_start();

    require('db_model.php');

    $stmt = $conn->prepare("UPDATE transactions SET tStatus = -1, tCancelReason = ?, tCancelTime = NOW() WHERE tID = ?");
    $stmt->execute([$_POST['cancellationReason'], $_POST['transactionID']]);

    header('Location: ../public/pages/orders.php');
