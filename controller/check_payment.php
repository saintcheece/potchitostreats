<?php
    session_start();

    require('db_model.php');

    require_once('../vendor/autoload.php');

    $stmt = $conn->prepare("SELECT * FROM transactions WHERE uID = ? AND tStatus = 1");
    $stmt->execute([$_SESSION['userID']]);
    $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

    echo $transaction['tPayID'];

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

    $payStatus = 1;
    if($transaction['tPayRemain'] == 0){
        $payStatus = 2;
    }

    if(isset($payment_json['data']['attributes']['paid_at'])){
        $stmt = $conn->prepare("UPDATE transactions SET tStatus = 2, tPayStatus = ?, tDateOrder = NOW() WHERE tID = ?");
        $stmt->execute([$payStatus, $transaction['tID']]);
    }

    audit(109);
    notify(1);

    header('Location: ../public/pages/orders.php');
?>