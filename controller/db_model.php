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

    // AUDIT
    function audit($act){
        global $conn;

        $stmt = $conn->prepare('INSERT INTO audit (uID, aOpID, aTime) VALUES (?, ?, NOW())');
        $stmt->execute([$_SESSION['userID'], $act]);
    }

    function notify($op){
        global $conn;

        $stmt = $conn->prepare('INSERT INTO notifications (uID, neOpID, nTime) VALUES (?, ?, NOW())');
        $stmt->execute([$_SESSION['userID'], $op]);

    }