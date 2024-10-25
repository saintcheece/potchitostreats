<?php

    session_start();

    require('db_model.php');

    $stmt = $conn->prepare("SELECT o.oID, p.pID, p.pType, p.pName, p.pPrice,  o.oQty,
                                    CASE WHEN p.pType = 3 
                                    	THEN (p.pPrice + (COALESCE(cf.cfPrice, 0) * COALESCE(c.cLayers, 0) * COALESCE(cs.csSize, 0)))
                                    	ELSE p.pPrice
                                    	END AS total, p.pPrepTime
                                    FROM orders o
                                    INNER JOIN products p ON o.pID = p.pID
                                    LEFT JOIN cakes c ON o.oID = c.oID
                                    LEFT JOIN cakes_flavor cf ON c.cfID = cf.cfID
                                    LEFT JOIN cakes_size cs ON c.csID = cs.csID
                                    WHERE o.tID = ?");
    $stmt->execute([$_GET['transactionID']]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);


    $total = 0;

    foreach($orders as $order){
        if($order['pType'] === 3){
            $total += $order['total'] / 2;
        }
    }

    $total *= 100;

    require_once('../vendor/autoload.php');

    $client = new \GuzzleHttp\Client();

    $line_items = array();
    foreach ($orders as $order) {
        if($order['pType'] === 3){
            $line_items[] = array(
                "currency" => "PHP",
                "images" => array(
                    "http://localhost/potchitos/product-gallery/" . "Cake_".$order['pID'].".jpg"),
                "amount" => (int) ($order['total'] / 2) * 100,
                "name" => $order['pName'] . " (cancellation balance)",
                "description" => "Enter Customizations Made Here",
                "quantity" => $order['oQty']
            );    
        }
    }

    $response = $client->request('POST', 'https://api.paymongo.com/v1/checkout_sessions', [
    'body' => '{
        "data": {
            "attributes": {
                "send_email_receipt": false,
                "show_description": true,
                "show_line_items": true,
                "cancel_url": "http://localhost/potchitos/public/pages/cart.php",
                "line_items": ' . json_encode($line_items) . ',
                "payment_method_types": [
                    "gcash"
                ],
                "success_url": "http://localhost/potchitos/controller/check_balance_cancel.php?transactionID=' . $_GET['transactionID'] . '",
                "description": "Potchito\'s Buns x Cookies"
            }
        }
    }',
    'headers' => [
        'Content-Type' => 'application/json',
        'accept' => 'application/json',
        'authorization' => 'Basic c2tfdGVzdF81b01Qb2pWd2l5NjJndkI2Q2hEYlhBZjk6',
    ],
    ]);

    $res =  $response->getBody();

    $payment_json = json_decode($res, true);

    $stmt = $conn->prepare("UPDATE transactions SET tPayIDRemain = ? WHERE tID = ?");
    $stmt->execute([$payment_json['data']['id'], $_GET['transactionID']]);

    header("Location: " . $payment_json['data']['attributes']['checkout_url']);

?>