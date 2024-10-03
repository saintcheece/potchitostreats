<?php
    session_start();

    require('db_model.php');

    require_once('../vendor/autoload.php');

    $stmt = $conn->prepare("SELECT * FROM transactions WHERE uID = ? AND tStatus = 1 LIMIT 1");
    $stmt->execute([$_SESSION['userID']]);
    $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

    // echo $transaction['tPayID'];

    $client = new \GuzzleHttp\Client();

    $pay_url = 'https://api.paymongo.com/v1/checkout_sessions/'. $transaction['tPayID'];

    $response = $client->request('GET', $pay_url, [  
    'headers' => [
        'accept' => 'application/json',
        'authorization' => 'Basic c2tfdGVzdF81b01Qb2pWd2l5NjJndkI2Q2hEYlhBZjk6',
    ],
    ]);

    $res =  $response->getBody();

    $payment_json = json_decode($res, true);

    if(isset($payment_json['data']['attributes']['paid_at'])){
        $stmt = $conn->prepare("UPDATE transactions SET tStatus = 2 WHERE tID = ?");
        $stmt->execute([$transaction['tID']]);
    }

    header('Location: ../public/pages/orders.php');
?>