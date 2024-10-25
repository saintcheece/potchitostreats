<?php
        // mark_notifications_viewed.php
        require('../controller/db_model.php');

        $stmt = $conn->prepare("UPDATE notifications SET nViewed = 1");
        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'error';
        }
        ?>