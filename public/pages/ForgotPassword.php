<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .background-box {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: 
                linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
                url('../assets/potchitosrepeat.png');
            background-size: cover;
            background-position: center;
        }
        .form-box {
            width: 35%;
            padding: 40px;
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .form-box h1 {
            margin-bottom: 20px;
        }
        .form-box button {
            width: 100%;
            padding: 10px;
        }
        .form-box p {
            margin-top: 15px;
            text-align: center;
        }
        .success-message, .error-message {
            margin-bottom: 10px;
        }
        .success-message {
            color: green;
        }
        .error-message {
            color: red;
        }
    </style>

    <?php
        session_start();
        require_once("../../controller/db_model.php");

        $message = "";
        if (isset($_POST['email'])) {
            $emailAddress = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $stmt = $conn->prepare("SELECT * FROM users WHERE uEmail = ?");
            $stmt->execute([$emailAddress]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $message = "<div class='success-message'>Instructions to reset your password have been sent to your email.</div>";
            } else {
                $message = "<div class='error-message'>Email not found. Please try again.</div>";
            }
        }
    ?>
</head>
<body>

    <?php include 'layout/header.php'; ?>

    <div class="background-box">
        <div class="form-box">
            <h1 class="text-center">Forgot Password</h1>
            <?php if ($message): ?>
                <?= $message; ?>
            <?php endif; ?>
            <form action="ForgotPassword.php" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <button type="submit" class="btn btn-primary">Send Reset Instructions</button>
            </form>
            <p>Remembered your password? <a href="login.php">Log In Instead</a></p>
        </div>
    </div>

</body>
</html>
