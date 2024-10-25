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
            'signPhoneNum' => $_POST['signPhoneNum']
        );

        if($_POST['inputCode'] == $_SESSION['verificationCode']){

            require('../../controller/db_model.php');

            $hash = password_hash($_POST['signPass'], PASSWORD_BCRYPT);

            $stmt = $conn->prepare("INSERT INTO users (uFName, uLName, uEmail, uPass, uPhone) VALUES (:signNameFirst, :signNameLast, :signEmail, :signPass, :signPhoneNum)");
            $stmt->execute(array(
                ':signNameFirst' => $_POST['signNameFirst'],
                ':signNameLast' => $_POST['signNameLast'],
                ':signEmail' => $_POST['signEmail'],
                ':signPass' => $hash,
                ':signPhoneNum' => (string)$_POST['signPhoneNum']
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <style>
        .verification-container {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: 
            linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), /* Darken with black gradient */
            url('../assets/potchitosrepeat.png');
            background-size: cover;
            background-position: center;
        }

        .verification-card {
            width: 400px;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .verification-card h1 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            text-align: center;
        }

        .verification-card input {
            letter-spacing: 5px;
            text-align: center; 
            font-size: 1.25rem;
        }

        .verification-card button {
            width: 100%;
            margin-top: 20px;
        }
    </style>
</head>
    <body>
    <div class="verification-container">
    <div class="verification-card">
        <h1>Verification Code</h1>
        <p class="text-center">Enter the code sent to your email</p>
        <form action="verify.php" method="post">
            <div class="mb-3">
                <input type="text" class="form-control" name="inputCode" id="code" maxlength="6" required>
                <!-- FOR ACCOUNT INFO STORAGE -->
                <input type="hidden" name="signNameFirst" id="signNameFirst" value="<?php echo $_POST["signNameFirst"] ?>">
                <input type="hidden" name="signNameLast" id="signNameLast" value="<?php echo $_POST["signNameLast"] ?>">
                <input type="hidden" name="signEmail" id="signEmail" value="<?php echo $_POST["signEmail"] ?>">
                <input type="hidden" name="signPass" id="signPass" value="<?php echo $_POST["signPass"] ?>">
                <input type="hidden" name="signPhoneNum" id="signPhoneNum" value="<?php echo $_POST["signPhoneNum"] ?>">
            </div>
            <button type="submit" class="btn btn-primary" name="isSubmitted" value="Verify">Verify</button>
        </form>
        <p class="text-center mt-3">Didn't receive the code? <a href="#">Resend</a></p>
    </div>
</div>
</body>
</html>