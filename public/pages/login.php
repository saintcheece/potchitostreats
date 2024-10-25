<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="test.css">
    <style>
        .page-container {
            height: 90vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .form-box {
            width: 35%;
            padding: 40px;
            background-color: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            height: 100%;
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
        .image-box {
            width: 65%;
            height: 100%;
            background: 
    linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), /* Darken with black gradient */
    url('../assets/potchitosrepeat.png');


            background-size: cover;
            background-position: center;
        }

        /* Make it responsive */
        @media (max-width: 768px) {
            .form-box {
                width: 90%;
            }
            .image-box {
                display: none; /* Hide image box on smaller screens */
            }
            .page-container {
                flex-direction: column;
            }
        }
    </style>

    <?php
        session_start();
        require_once("../../controller/db_model.php");
        if (isset($_POST['inptEmail']) && isset($_POST['inptPass'])) {
            $emailAddress = filter_input(INPUT_POST, 'inptEmail', FILTER_SANITIZE_EMAIL);
            $password = filter_input(INPUT_POST, 'inptPass', FILTER_SANITIZE_STRING);

            $stmt = $conn->prepare("SELECT * FROM users WHERE uEmail = ?");
            $stmt->execute([$emailAddress]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $hashed_password = $user['uPass'];
                if (password_verify($password, $hashed_password)) {
                    if ($user['uType'] == 1) {
                        $_SESSION['userID'] = $user['uID'];
                        audit(101);
                        header("Location: index.php");
                        exit;
                    } else if ($user['uType'] == 3) {
                        $_SESSION['userID'] = $user['uID'];
                        $_SESSION['userEmail'] = $user['uEmail'];
                        audit(201);
                        header("Location: ../../admin/admin.php");
                        exit;
                    }
                } else {
                    echo "Password is incorrect.";
                }
            } else {
                echo "User not found.";
            }
        }
    ?>
    
</head>
<body>

    <?php include 'layout/header.php'; ?>

    <div class="page-container">
        <div class="form-box">
            <h1 class="text-center">Log In</h1>
            <form action="login.php" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="inptEmail" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="inptPass" required>
                    <!-- <a href="#">Forgot Password?</a> -->
                </div>
                <button type="submit" class="btn btn-primary">Log In</button>
            </form>
            <p>Don't have an account yet? <span><a href="signup.php">Sign Up Instead</a></span></p>
        </div>

        <div class="image-box"></div>
    </div>

</body>
</html>
