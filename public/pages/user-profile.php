<?php
    session_start();
    require('../../controller/db_model.php');

    if (isset($_POST['newFName'])) {
        $newUFName = filter_input(INPUT_POST, 'newUFName', FILTER_SANITIZE_STRING);

        $stmt = $conn->prepare("UPDATE users SET uFName = :newUFName, uLName = :newULName, uEmail = :newUEmail, uPhone = :newUPhone WHERE uID = :uID");
        $stmt->execute([
            'newUFName' => $_POST['newFName'],
            'newULName' => $_POST['newLName'],
            'newUEmail' => $_POST['newEmail'],
            'newUPhone' => $_POST['newPhone'],
            'uID' => $_SESSION['userID']
        ]);
    }

    if(isset($_POST['newPassword'])){

        $stmt = $conn->prepare("UPDATE users SET uPass = :newUPass WHERE uID = :uID");
        $stmt->execute([
            'newUPass' => $_POST['newPassword'],
            'uID' => $_SESSION['userID']
        ]);
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE uID = :uID LIMIT 1");
    $stmt->execute(['uID' => $_SESSION['userID']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <style>
        body {
            margin-top: 20px;
            color: #9b9ca1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .bg-secondary-soft {
            background-color: rgba(208, 212, 217, 0.1) !important;
        }

        .rounded {
            border-radius: 5px !important;
        }

        .py-5 {
            padding-top: 3rem !important;
            padding-bottom: 3rem !important;
        }

        .px-4 {
            padding-right: 1.5rem !important;
            padding-left: 1.5rem !important;
        }

        .form-control {
            display: block;
            width: 100%;
            padding: 0.5rem 1rem;
            font-size: 0.9375rem;
            border: 1px solid #e5dfe4;
            border-radius: 5px;
        }

        .tab-content {
            min-height: 400px;
        }

        footer {
            margin-top: auto;
            background-color: #f8f9fa;
            padding: 1rem;
            text-align: center;
        }

        /* Hides Save and Cancel buttons by default */
        .action-buttons {
            display: none;
        }

        .editable .form-control {
            pointer-events: auto;
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

    <?php include 'layout/header.php'; ?>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Bootstrap Tabs -->
                <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="general-info-tab" data-bs-toggle="tab" data-bs-target="#general-info" type="button" role="tab" aria-controls="general-info" aria-selected="true">General Information</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="change-password-tab" data-bs-toggle="tab" data-bs-target="#change-password" type="button" role="tab" aria-controls="change-password" aria-selected="false">Change Password</button>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <!-- General Information Section -->
                    <div class="tab-pane fade show active" id="general-info" role="tabpanel" aria-labelledby="general-info-tab">
                        <div class="bg-secondary-soft px-4 py-5 rounded">
                            <form action="user-profile.php" method="post" class="row g-3">
                                <h4 class="mb-4 mt-0">Contact Detail</h4>
                                <div class="col-md-6">
                                    <label class="form-label">First Name *</label>
                                    <input name="newFName" type="text" class="form-control" value="<?= $user['uFName']?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Last Name *</label>
                                    <input name="newLName" type="text" class="form-control" value="<?= $user['uLName']?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Mobile number *</label>
                                    <input name="newPhone" type="text" class="form-control" value="<?= $user['uPhone']?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="inputEmail4" class="form-label">Email *</label>
                                    <input name="newEmail" type="email" class="form-control" id="inputEmail4" value="<?= $user['uEmail']?>" readonly>
                                </div><div class="action-buttons mt-3">
                                    <button class="btn btn-secondary cancel-btn">Cancel</button>
                                    <input type="submit" class="btn btn-primary save-btn" value="Save Changes">
                                </div>
                            </form>
                            <?php
                                $stmt = $conn->prepare("SELECT * FROM transactions WHERE uID = ? AND tPayStatus > 0 AND tPayStatus < 6");
                                $stmt->execute([$_SESSION['userID']]);
                                $existsOngoing = (bool) $stmt->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <?php if ($existsOngoing){ ?>
                                <small class="text-danger">You have ongoing orders, please cancel them or wait for them to be completed before changing your account details.</>
                            <?php }else{ ?>
                                <button class="btn btn-primary edit-btn mt-3">Edit</button>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Change Password Section -->
                    <div class="tab-pane fade" id="change-password" role="tabpanel" aria-labelledby="change-password-tab">
                        <div class="bg-secondary-soft px-4 py-5 rounded">
                            <div class="row g-3">
                                <h4 class="my-4">Change Password</h4>
                                <div class="col-md-12">
                                    <label for="oldPassword" class="form-label">Old password *</label>
                                    <input type="password" class="form-control" id="oldPassword" readonly>
                                </div>
                                <div class="col-md-12">
                                    <label for="newPassword" class="form-label">New password *</label>
                                    <input type="password" class="form-control" id="newPassword" readonly required pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d\W_&#46;]{8,}$" onkeyup="checkPassword()">
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
                                <div class="col-md-12">
                                    <label for="confirmPassword" class="form-label">Confirm Password *</label>
                                    <input type="password" class="form-control" id="confirmPassword" readonly>
                                </div>
                            </div>
                            <div class="action-buttons mt-3">
                                <button class="btn btn-secondary cancel-btn">Cancel</button>
                                <button class="btn btn-primary save-btn">Save</button>
                            </div>
                            <button class="btn btn-primary edit-password-btn mt-3">Edit</button>
                        </div>
                    </div>
                    </div>
                </div>

             

            </div>
        </div>
    </div>
	<?php include 'layout/footer.php'; ?>

    <script>
        $(document).ready(function() {
            $('#oldPassword').on('blur', function() {
                var oldPassword = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: '../../controller/check-password.php',
                    data: {oldPassword: oldPassword},
                    success: function(response) {
                        if (response === 'match') {
                            $('#newPassword, #confirmPassword').removeAttr('readonly');
                        } else {
                            alert('Old password is incorrect.');
                            $('#newPassword, #confirmPassword').attr('readonly', true);
                        }
                    }
                });
            });
        });

        document.querySelectorAll('.edit-password-btn').forEach(function(editBtn) {
            editBtn.addEventListener('click', function() {
                var parent = this.closest('.bg-secondary-soft');
                parent.classList.add('editable');
                parent.querySelector('#oldPassword').removeAttribute('readonly');
                parent.querySelector('.action-buttons').style.display = 'block';
                this.style.display = 'none';
                parent.querySelector('#oldPassword').focus();
            });
        });

        document.querySelectorAll('.edit-btn').forEach(function(editBtn) {
            editBtn.addEventListener('click', function() {
                var parent = this.closest('.bg-secondary-soft');
                parent.classList.add('editable');
                parent.querySelectorAll('.form-control').forEach(function(input) {
                    input.removeAttribute('readonly');
                });
                parent.querySelector('.action-buttons').style.display = 'block';
                this.style.display = 'none';
            });
        });

        document.querySelectorAll('.cancel-btn').forEach(function(cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                var parent = this.closest('.bg-secondary-soft');
                parent.classList.remove('editable');
                parent.querySelectorAll('.form-control').forEach(function(input) {
                    input.setAttribute('readonly', true);
                });
                parent.querySelector('.action-buttons').style.display = 'none';
                parent.querySelector('.edit-btn').style.display = 'block';
            });
        });

        // Add save functionality based on your back-end logic
        document.querySelectorAll('.save-btn').forEach(function(saveBtn) {
            saveBtn.addEventListener('click', function() {
                var parent = this.closest('.bg-secondary-soft');
                // Perform your save logic here, e.g., submitting the form via AJAX or PHP

                // Once saved, make fields read-only again
                parent.classList.remove('editable');
                parent.querySelectorAll('.form-control').forEach(function(input) {
                    input.setAttribute('readonly', true);
                });
                parent.querySelector('.action-buttons').style.display = 'none';
                parent.querySelector('.edit-btn').style.display = 'block';
            });
        });

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

    // PASSWORD STUFF

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
            document.getElementById('newPassword').type = 'text';
            document.getElementById('confirmPassword').type = 'text';
        } else {
            document.getElementById('newPassword').type = 'password';
            document.getElementById('confirmPassword').type = 'password';
        }
    }

    var myInput = document.getElementById("newPassword");
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
</body>
</html>
