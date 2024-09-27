<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <style>
        .page-container {
            /* height: 120vh; */
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            background: 
    linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), /* Darken with black gradient */
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
            height: auto;
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
        /* Style for error message */
        .error-message {
            color: red;
            font-size: 0.9rem;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <?php include 'layout/header.php'; ?>

    <div class="page-container p-4">
        <div class="form-box">
            <h1 class="text-center">Sign Up</h1>
            <form id="signupForm" action="verify.php" method="post">
                <div class="mb-3">
                    <label for="signNameFirst" class="form-label">First Name:</label><br>
                    <input type="text" class="form-control" name="signNameFirst" id="signNameFirst" required>
                </div>
                <div class="mb-3">
                    <label for="signNameLast" class="form-label">Last Name:</label><br>
                    <input type="text" class="form-control" name="signNameLast" id="signNameLast" required>
                </div>
                <div class="mb-3">
                    <label for="signEmail" class="form-label">Email:</label><br>
                    <input type="email" class="form-control" name="signEmail" id="signEmail" required>
                </div>
                <div class="mb-3">
                    <label for="signPass" class="form-label">Password:</label><br>
                    <input type="password" class="form-control" name="signPass" id="signPass" required pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" title="Password must be at least 8 characters long, contain at least one lowercase letter, one uppercase letter, one number, and one special character" onkeyup="checkPassword()">
                </div>
                <div class="mb-3">
                    <label for="signPassConfirm" class="form-label">Confirm Password:</label><br>
                    <input type="password" class="form-control" name="signPassConfirm" id="signPassConfirm" required onkeyup="checkPassword()">
                    <span id="passwordError" class="error-message"></span>
                </div>
                <div class="mb-3">
                    <label for="signRegion" class="form-label">Region:</label><br>
                    <select id="region" name="signRegion" class="form-select" required></select>
                    <input type="hidden" id="signRegion">
                </div>
                <div class="mb-3">
                    <label for="signProv" class="form-label">Province:</label><br>
                    <select id="province" name="signProv" class="form-select" required></select>
                    <input type="hidden" id="signProv">
                </div>
                <div class="mb-3">
                    <label for="signCity" class="form-label">City:</label><br>
                    <select id="city" name="signCity" class="form-select" required></select>
                    <input type="hidden" id="signCity">
                </div>
                <div class="mb-3">
                    <label for="signTown" class="form-label">Town:</label><br>
                    <select id="barangay" name="signTown" class="form-select" required></select>
                    <input type="hidden" id="signTown">
                </div>
                <div class="mb-3">
                    <label for="signStreet" class="form-label">Street:</label><br>
                    <input type="text" class="form-control" name="signStreet" id="signStreet">
                </div>
                <div class="mb-3">
                    <label for="signAddHouseNum" class="form-label">House Number:</label><br>
                    <input type="text" class="form-control" name="signAddHouseNum" id="signAddHouseNum">
                </div>
                <input type="submit" class="btn btn-primary" id="submitBtn" value="Submit" class="btn btn-primary">
            </form>
        </div>
    </div>
    <?php include 'layout/footer.php'; ?>
</body>
<script src="../js/ph-address-selector.js"></script>
<script>
        function checkPassword() {
            const password = document.getElementById('signPass').value;
            const confirmPassword = document.getElementById('signPassConfirm').value;
            const passwordError = document.getElementById('passwordError');
            const submitBtn = document.getElementById('submitBtn');

            if (password !== confirmPassword) {
                passwordError.textContent = 'Passwords do not match';
                submitBtn.disabled = true;
            } else {
                passwordError.textContent = '';
                submitBtn.disabled = false;
            }
        }
    </script>
</html>