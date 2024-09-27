<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar {
            border-bottom: 2px solid #ccc; 
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white px-3 position-sticky" style="height: 12vh; z-index: 1030;">
    <a href="index.php" class="navbar-brand d-flex align-items-center h-100">
        <img src="../assets/blue-potchitos-logo1.png" style="height: 65%;" alt="Logo">
    </a>

    <div class="d-flex justify-content-between w-100">
        <div class="navbar-nav">
            <div class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-secondary" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Products
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="cakes.php">Cakes</a>
                    <a class="dropdown-item" href="cookies.php">Cookies</a>
                    <a class="dropdown-item" href="pastries.php">Pastries</a>
                </div>
            </div>
            <a class="nav-link text-secondary" href="contact.php">Contact Us</a>
            <a class="nav-link text-secondary" href="about.php">About Us</a>
        </div>

        <div class="d-flex align-items-center">
            <a class="nav-link text-secondary mr-3" href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a>
            <a class="btn btn-primary" href="login.php">Log In</a>
        </div>
    </div>
</nav>

<!-- IF ANY ERROR IN ERROR OCCURED, UNCOMMENT THIS -->
<!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
