<?php

    session_start();

    require('db_model.php');

    // GET TRANSACTION
    $stmt = $conn->prepare("SELECT tID FROM transactions WHERE uID = ? AND tStatus = 1");
    $stmt->execute([$_SESSION['userID']]);
    $transaction = $stmt->fetchColumn();

    $stmt = $conn->prepare("SELECT p.pID, p.pType, p.pName, p.pPrice,  o.oQty, ((p.pPrice + COALESCE(cf.cfPrice, 0) + COALESCE(cs.csPrice, 0))) AS total, p.pPrepTime
                            FROM orders o
                            INNER JOIN products p ON o.pID = p.pID
                            LEFT JOIN cakes c ON o.pID = c.pID
                            LEFT JOIN cakes_flavor cf ON c.cfID = cf.cfID
                            LEFT JOIN cakes_size cs ON c.csID = cs.csID
                            WHERE o.tID = ?");
    $stmt->execute([$transaction]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);


    $total = 0;

    foreach($orders as $order){
        if (isset($_POST['paymentOption']) && $_POST['paymentOption'] === '2') {
            $total += $order['pType'] === 3 ? $order['total'] / 2 : $order['total'];
        } else {
            $total += $order['total'];
        }
    }

    $totalRemain = 0;
    if (isset($_POST['paymentOption']) && $_POST['paymentOption'] === '2') {
        foreach($orders as $order){
            if($order['pType'] === 3){
                $totalRemain += $order['total'] * $order['oQty']/2;
            }
        }
    }

    $total *= 100;

    require_once('../vendor/autoload.php');

    $client = new \GuzzleHttp\Client();

    $line_items = array();
    foreach ($orders as $order) {
        $line_items[] = array(
            "currency" => "PHP",
            "images" => array(
                "http://localhost/potchitos/product-gallery/" . array('Cookie', 'Pastry', 'Cake')[$order['pType']-1]."_".$order['pID'].".jpg"),
            "amount" => (int) ((isset($_POST['paymentOption']) && $_POST['paymentOption'] === '2' && $order['pType'] === 3) ? $order['total'] / 2 : $order['total']) * 100,
            "name" => (isset($_POST['paymentOption']) && $_POST['paymentOption'] === '2' && $order['pType'] === 3) ? $order['pName'] . " (deposit)" : $order['pName'],
            "description" => "Enter Customizations Made Here",
            "quantity" => $order['oQty']
        );
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
                "success_url": "http://localhost/potchitos/controller/check_payment.php",
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
    

    // echo $res;

    // AFTER TRANSACTION SHOULD REDIRECT TO SUCCESS PAGE (ORDERS PAGE) WHERE IT CHECKS IF isset(response[data][attributes][paid_at])
    //      it's a JSON, so di pa ako sure if nasend na once redirected
    //      if exists, then it should record as paid

    $payment_json = json_decode($res, true);

    $stmt = $conn->prepare("UPDATE transactions SET tPayID = ?, tDateClaim = ?, tPayStatus = 1, tDateOrder = NOW(), tPayRemain = ? WHERE tID = ?");
    $stmt->execute([$payment_json['data']['id'], date("Y-m-d", strtotime($_POST['orderDate'])), $totalRemain, $transaction]);

    header("Location: " . $payment_json['data']['attributes']['checkout_url']);

?>