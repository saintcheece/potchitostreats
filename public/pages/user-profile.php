<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

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
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="address-tab" data-bs-toggle="tab" data-bs-target="#address" type="button" role="tab" aria-controls="address" aria-selected="false">Address</button>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <!-- General Information Section -->
                    <div class="tab-pane fade show active" id="general-info" role="tabpanel" aria-labelledby="general-info-tab">
                        <div class="bg-secondary-soft px-4 py-5 rounded">
                            <div class="row g-3">
                                <h4 class="mb-4 mt-0">Contact Detail</h4>
                                <div class="col-md-6">
                                    <label class="form-label">First Name *</label>
                                    <input type="text" class="form-control" value="Scaralet" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Last Name *</label>
                                    <input type="text" class="form-control" value="Doe" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Mobile number *</label>
                                    <input type="text" class="form-control" value="+09618024503" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="inputEmail4" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="inputEmail4" value="example@homerealty.com" readonly>
                                </div>
                            </div>
                            <div class="action-buttons mt-3">
                                <button class="btn btn-secondary cancel-btn">Cancel</button>
                                <button class="btn btn-primary save-btn">Save</button>
                            </div>
                            <button class="btn btn-primary edit-btn mt-3">Edit</button>
                        </div>
                    </div>

                    <!-- Change Password Section -->
                    <div class="tab-pane fade" id="change-password" role="tabpanel" aria-labelledby="change-password-tab">
                        <div class="bg-secondary-soft px-4 py-5 rounded">
                            <div class="row g-3">
                                <h4 class="my-4">Change Password</h4>
                                <div class="col-md-6">
                                    <label for="oldPassword" class="form-label">Old password *</label>
                                    <input type="password" class="form-control" id="oldPassword" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="newPassword" class="form-label">New password *</label>
                                    <input type="password" class="form-control" id="newPassword" readonly>
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
                            <button class="btn btn-primary edit-btn mt-3">Edit</button>
                        </div>
                    </div>

                    <!-- Address Section -->
                    <div class="tab-pane fade" id="address" role="tabpanel" aria-labelledby="address-tab">
                        <div class="bg-secondary-soft px-4 py-5 rounded">
                            <div class="row g-3">
                                <h4 class="mb-4 mt-0">Primary Address</h4>
                                <div class="col-md-6">
                                    <label class="form-label">Country / Region *</label>
                                    <input type="text" class="form-control" value="Philippines" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Town *</label>
                                    <input type="text" class="form-control" placeholder="Enter your town" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Barangay *</label>
                                    <input type="text" class="form-control" placeholder="Enter your Barangay" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Street Address *</label>
                                    <input type="text" class="form-control" placeholder="Enter your street address" readonly>
                                </div>
                            </div>
                            <div class="action-buttons mt-3">
                                <button class="btn btn-secondary cancel-btn">Cancel</button>
                                <button class="btn btn-primary save-btn">Save</button>
                            </div>
                            <button class="btn btn-primary edit-btn mt-3">Edit</button>
                        </div>

                        <div class="bg-secondary-soft px-4 py-5 rounded mt-3">
                            <div class="row g-3">
                                <h4 class="mb-4 mt-0">Secondary Address</h4>
                                <div class="col-md-6">
                                    <label class="form-label">Country / Region *</label>
                                    <input type="text" class="form-control" value="Philippines" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Town *</label>
                                    <input type="text" class="form-control" placeholder="Enter your town" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Barangay *</label>
                                    <input type="text" class="form-control" placeholder="Enter your Barangay" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Street Address *</label>
                                    <input type="text" class="form-control" placeholder="Enter your street address" readonly>
                                </div>
                            </div>
                            <div class="action-buttons mt-3">
                                <button class="btn btn-secondary cancel-btn">Cancel</button>
                                <button class="btn btn-primary save-btn">Save</button>
                            </div>
                            <button class="btn btn-primary edit-btn mt-3">Edit</button>
                        </div>
                    </div>
                </div>

             

            </div>
        </div>
    </div>
	<?php include 'layout/footer.php'; ?>

    <script>
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
    </script>
</body>
</html>
