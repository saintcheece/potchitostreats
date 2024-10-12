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
    <style>

/* The message box is shown when the user clicks on the password field */
#message {
  display:none;
}

/* Add a green text color and a checkmark when the requirements are right */
.valid {
  color: green;
}

/* Add a red text color and an "x" when the requirements are wrong */
.invalid {
  color: red;
}
</style>
</head>
<body>

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
                    <input type="password" class="form-control" name="signPass" id="signPass" required pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" onkeyup="checkPassword()">
                    <div class="d-flex justify-content-end">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="showPass" onclick="showPassword()">
                        <small class="form-check-label" for="showPass">View Password</small>
                    </div>
                    </div>
                    <small id="message" class="text-muted opacity-50">
                        <p id="letter" class="invalid m-0" style="text-align:left;">a lowercase letter</p>
                        <p id="capital" class="invalid m-0" style="text-align:left;">a capital (uppercase) letter</p>
                        <p id="number" class="invalid m-0" style="text-align:left;">a number</p>
                        <p id="length" class="invalid m-0" style="text-align:left;">8 characters</p>
                    </small>
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
                <div class="mb-3">
                    <label for="signPhoneNum" class="form-label">Phone Number:</label><br>
                    <div class="input-group">
                        <div class="input-group-text">+63</div>
                        <input type="text" class="form-control" name="signPhoneNum" id="signPhoneNum" maxlength="10" minlength="10" pattern="[0-9]{10}" required>
                    </div>
                </div>
                <div class="mb-3 d-flex justify-content-center">Already Have An Account? &nbsp;<a href="login.php">Log In Instead</a></div>
                <div class="d-flex justify-content-end">
                <input type="submit" class="btn btn-primary btn-lg btn-block" id="submitBtn" value="Submit" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>

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

        function showPassword() {
            if (document.getElementById('showPass').checked) {
                document.getElementById('signPass').type = 'text';
                document.getElementById('signPassConfirm').type = 'text';
            } else {
                document.getElementById('signPass').type = 'password';
                document.getElementById('signPassConfirm').type = 'password';
            }
        }
    </script>
    <script>
        var myInput = document.getElementById("signPass");
        var letter = document.getElementById("letter");
        var capital = document.getElementById("capital");
        var number = document.getElementById("number");
        var length = document.getElementById("length");

        // When the user clicks on the password field, show the message box
        myInput.onfocus = function() {
        document.getElementById("message").style.display = "block";
        }

        // When the user clicks outside of the password field, hide the message box
        myInput.onblur = function() {
        document.getElementById("message").style.display = "none";
        }

        // When the user starts to type something inside the password field
        myInput.onkeyup = function() {
        // Validate lowercase letters
        var lowerCaseLetters = /[a-z]/g;
        if(myInput.value.match(lowerCaseLetters)) {  
            letter.classList.remove("invalid");
            letter.classList.add("valid");
        } else {
            letter.classList.remove("valid");
            letter.classList.add("invalid");
        }
        
        // Validate capital letters
        var upperCaseLetters = /[A-Z]/g;
        if(myInput.value.match(upperCaseLetters)) {  
            capital.classList.remove("invalid");
            capital.classList.add("valid");
        } else {
            capital.classList.remove("valid");
            capital.classList.add("invalid");
        }

        // Validate numbers
        var numbers = /[0-9]/g;
        if(myInput.value.match(numbers)) {  
            number.classList.remove("invalid");
            number.classList.add("valid");
        } else {
            number.classList.remove("valid");
            number.classList.add("invalid");
        }
        
        // Validate length
        if(myInput.value.length >= 8) {
            length.classList.remove("invalid");
            length.classList.add("valid");
        } else {
            length.classList.remove("valid");
            length.classList.add("invalid");
        }
        }
</script>
</html>