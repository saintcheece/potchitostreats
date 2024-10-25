<?php

try {
    require('../controller/db_model.php');

    $stmt = $conn->prepare('DELETE FROM orders;
                            DELETE FROM transactions;
                            DELETE FROM cakes;
                            DELETE FROM products;
                            DELETE FROM cakes_color;
                            DELETE FROM cakes_flavor;
                            DELETE FROM cakes_layer;
                            DELETE FROM cakes_size;');

    $stmt->execute();

    echo 'Database has been reset';
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
