<?php
    require('conn.php');

    function save ($insertQuery, $entity) {
        global $conn;
        $stmt = $conn->prepare($insertQuery);
        $stmt->execute();
        $pid = $conn->lastInsertId();
        if (isset($_FILES['fileField'])) {
            $newname = "$entity"."_$pid.jpg";
            move_uploaded_file($_FILES['fileField']['tmp_name'], "../uploads/$newname");
        }
    }

    $stmt = $conn->prepare("SELECT * FROM products WHERE pVisibility = 1");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);