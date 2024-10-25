<?php
session_start();
require('db_model.php');

if (isset($_POST['oldPassword'])) {
    $oldPassword = $_POST['oldPassword'];
    $stmt = $conn->prepare("SELECT uPass FROM users WHERE uID = ?");
    $stmt->execute([$_SESSION['userID']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (password_verify($oldPassword, $user['uPass'])) {
        echo 'match';
    } else {
        echo 'no match';
    }
}
?>