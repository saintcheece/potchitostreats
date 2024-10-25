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
    
</head>
<body>

    <?php include 'layout/header.php'; ?>

    <div class="page-container">
        <div class="form-box">
            <h1 class="text-center">Change Password</h1>
            <form action="login.php" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="inptEmail" required>
                    <small><p>Before proceeding, please enter your email address. You will receive a link to create a new password via email.</p></small>
                </div>
                <button type="submit" class="btn btn-primary">Send Reset Link</button>
            </form>
        </div>

        <div class="image-box"></div>
    </div>

</body>
</html>
