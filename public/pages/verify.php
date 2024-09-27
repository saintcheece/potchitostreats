<?php
    session_start();

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require '..\..\vendor\phpmailer\phpmailer\src\Exception.php';
    require '..\..\vendor\phpmailer\phpmailer\src\PHPMailer.php';
    require '..\..\vendor\phpmailer\phpmailer\src\SMTP.php';

    $postFields = array();

    // ONCE SUBMITTED
    if(isset($_POST['isSubmitted'])){
        $postFields = array(
            'signNameFirst' => $_POST['signNameFirst'],
            'signNameLast' => $_POST['signNameLast'],
            'signEmail' => $_POST['signEmail'],
            'signPass' => $_POST['signPass'],
            'signRegion' => $_POST['signRegion'],
            'signProv' => $_POST['signProv'],
            'signCity' => $_POST['signCity'],
            'signTown' => $_POST['signTown'],
            'signStreet' => $_POST['signStreet'],
            'signAddHouseNum' => $_POST['signAddHouseNum']
        );

        if($_POST['inputCode'] == $_SESSION['verificationCode']){

            require('../../controller/db_model.php');

            $hash = password_hash($_POST['signPass'], PASSWORD_BCRYPT);

            $stmt = $conn->prepare("INSERT INTO users (uFName, uLName, uEmail, uPass, uAddrRegion, uAddrProvince, uAddrCity, uAddrTown, uAddrStreet, uAddrHouseNum) VALUES (:signNameFirst, :signNameLast, :signEmail, :signPass, :signRegion, :signProv, :signCity, :signTown, :signStreet, :signAddHouseNum)");
            $stmt->execute(array(
                ':signNameFirst' => $_POST['signNameFirst'],
                ':signNameLast' => $_POST['signNameLast'],
                ':signEmail' => $_POST['signEmail'],
                ':signPass' => $hash,
                ':signRegion' => (string)$_POST['signRegion'],
                ':signProv' => (string)$_POST['signProv'],
                ':signCity' => (string)$_POST['signCity'],
                ':signTown' => (string)$_POST['signTown'],
                ':signStreet' => (string)$_POST['signStreet'],
                ':signAddHouseNum' => (string)$_POST['signAddHouseNum']
            ));
            
            session_destroy();
            header("Location: login.php");
        } else {
            echo '<form id="redirectForm" action="verify.php" method="post">';
            foreach($postFields as $key=>$value){
                echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
            }
            echo '</form>';
            echo '<script>document.getElementById("redirectForm").submit()</script>';
            
        }
    }

    // FROM SIGN UP
    if(isset($_POST['signNameFirst'])){

        $_SESSION['verificationCode'] = (string)rand(100000, 999999);

        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'saintjake33@gmail.com';
            $mail->Password = 'quvm ikur hnrx zywv';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('saintjake33@gmail.com', 'Potchitos Buns & Cookies');
            $mail->addAddress($_POST["signEmail"]);

            $mail->isHTML(true);
            $mail->Subject = 'Email Verification';
            $mail->Body    = 'Your verification code is ' . $_SESSION['verificationCode'];

            $mail->send();
        } catch (Exception $e) {
            // echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
    <body>
    <form action="verify.php" method="post">
        <label for="code">Enter your verification code</label>
        <input type="text" name="inputCode" id="code">

        <!-- FOR ACCOUNT INFO STORAGE -->
        
        <input type="hidden" name="signNameFirst" id="signNameFirst" value="<?php echo $_POST["signNameFirst"] ?>">
        <input type="hidden" name="signNameLast" id="signNameLast" value="<?php echo $_POST["signNameLast"] ?>">
        <input type="hidden" name="signEmail" id="signEmail" value="<?php echo $_POST["signEmail"] ?>">
        <input type="hidden" name="signPass" id="signPass" value="<?php echo $_POST["signPass"] ?>">
        <input type="hidden" name="signAddHouseNum" id="signAddHouseNum" value="<?php echo $_POST["signAddHouseNum"] ?>">
        <input type="hidden" name="signStreet" id="signStreet" value="<?php echo $_POST["signStreet"] ?>">
        <input type="hidden" name="signTown" id="signTown" value="<?php echo $_POST["signTown"] ?>">
        <input type="hidden" name="signProv" id="signProv" value="<?php echo $_POST["signProv"] ?>">
        <input type="hidden" name="signCity" id="signCity" value="<?php echo $_POST["signCity"] ?>">
        <input type="hidden" name="signRegion" id="signRegion" value="<?php echo $_POST["signRegion"] ?>">

        <input type="submit" name="isSubmitted" value="Submit">
    </form>
</body>
</html>