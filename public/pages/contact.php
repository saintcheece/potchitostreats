<?php
    session_start();

    require '../../controller/db_model.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require '..\..\vendor\phpmailer\phpmailer\src\Exception.php';
    require '..\..\vendor\phpmailer\phpmailer\src\PHPMailer.php';
    require '..\..\vendor\phpmailer\phpmailer\src\SMTP.php';            

    if(isset($_POST["submit"])){
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'saintjake33@gmail.com';
            $mail->Password = 'quvm ikur hnrx zywv';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom($_POST["inputEmail"], $_POST["inputName"]);
            $mail->addAddress('saintjake33@gmail.com');

            $mail->isHTML(true);
            $mail->Subject = 'Potchito\'s Customer Query';
            $mail->Body    = $_POST["inputMessage"];

            $mail->send();

            notify(2);

            echo 'Email has been sent';
        } catch (Exception $e) {
            echo 'womp womp';
        }
    }

    include 'layout/header.php'; 

    $user = [];
    
    if(isset($_SESSION['userID'])){
        $stmt = $conn->prepare("SELECT uEmail, CONCAT(uFName, ' ', uLName) AS uName FROM users WHERE uID = :userID");
        $stmt->execute(array(':userID' => $_SESSION['userID']));
        $user = $stmt->fetch();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="test.css">
    <style>
       *,::before,::after{
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}
body{
    font-family: 'Poppins', sans-serif;
    background-color: #ffffff;
    

}
#contact-us{
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;   
     background-color: #ffffff;
}
#contact-us h1{
    margin-top: 2em;
    color:#346cbc;
    font-size: 2.3em;
}
#contact-us p{
    color: #323232;
    font-size:1rem;
}
#contact-us form{
    margin-top: 2em;
    display: flex;
    flex-direction: column;
    align-items: left;
    border: 3px solid #346cbc;
    width: 40%;
    border-radius: 5px;
    padding: 2em;
    height: 60%;
}
#contact-us form label{
    color: #292929;
    font-size: 1.2em;
    margin-top: 0.4em;
}
#contact-us form input{
    margin-top: 0.4em;
    padding: 0.5em;
    border: 1px solid #4b4b4b;
    border-radius: 5px;
}
#contact-us form textarea{
    margin-top: 0.4em;
    padding: 0.5em;
    border: 1px solid #555555;
    border-radius: 5px;
    height: 50%;
    resize: none;
}
#contact-us form input[type="submit"]{
    margin-top: 1em;
    padding: 0.5em;
    border: 1px solid #4b4b4b;
    border-radius: 5px;
    background-color:#346cbc;
    color: white;
    font-size: 1em;
    font-weight: 500;
    cursor: pointer;
    width: auto;
    
}
    </style>
</head>
<body>
    <section id="contact-us">
  <h1 class="mt-3">Contact Us</h1>
  <p>If you have any inquiries, concern, or in need of clarifications.</p>
  <form action="contact.php" method="POST" class="m-0">
    <label for="name">Name:</label>
    <input type="text" id="name" name="inputName" value="<?php echo isset($user) && isset($user['uName']) ? htmlspecialchars($user['uName']) : ''; ?>" <?php echo isset($_SESSION['userID']) ? 'readonly' : 'required'; ?>>
    <label for="email">Email:</label>
    <input type="email" id="email" name="inputEmail" value="<?php echo isset($user) && isset($user['uEmail']) ? htmlspecialchars($user['uEmail']) : ''; ?>" <?php echo isset($_SESSION['userID']) ? 'readonly' : 'required'; ?>>
    <label for="message">Message:</label>
    <textarea id="message" name="inputMessage" required placeholder="What would you like to talk about?"></textarea>
    <input type="submit" name="submit" value="Submit">
  </form>
</section>
    <?php include 'layout/footer.php'; ?>

</body>
</html>