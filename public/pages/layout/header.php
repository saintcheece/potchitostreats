<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar {
            border-bottom: 2px solid #ccc; 
        }

        /* CSS used here will be applied after bootstrap.css */

        .notifications {
            width: 30vw;
            left: 68vw;
        }
        
        .notification-heading, .notification-footer  {
            padding:2px 10px;
            display: flex;
            justify-content: space-between;
        }

        .item-title {
            color:#000;
            margin-bottom: 0;
        }

        .notifications a.content {
            text-decoration:none;
        }
            
        .notification-item {
            padding:10px;
            margin:5px;
        }

        .notification-item:hover {
            background:#ccc;
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
                    <a class="dropdown-item" href="product-by-type.php?type=3">Cakes</a>
                    <a class="dropdown-item" href="product-by-type.php?type=1">Cookies</a>
                    <a class="dropdown-item" href="product-by-type.php?type=2">Pastries</a>
                </div>
            </div>
            <a class="nav-link text-secondary" href="contact.php">Contact Us</a>
            <a class="nav-link text-secondary" href="about.php">About Us</a>
        </div>

        <?php 
          if(isset($_SESSION['userID']) && $_SESSION['userID'] != null) { ?>
            <div class="d-flex align-items-center">
                <a class="nav-link text-secondary mr-3" href="user-profile.php"><i class="fas fa-user"></i>  Profile</a>
                <a class="nav-link text-secondary mr-3" href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a>
                <a class="nav-link text-secondary mr-3" href="orders.php"><i class="fas fa-box"></i> Orders</a>
                <!-- <a class="nav-link text-secondary mr-3" id="dLabel" role="button" data-toggle="dropdown" data-target="#" ><i class="fas fa-bell"></i></a> -->
                <!-- NOTIFCATIONS -->
                <ul class="dropdown-menu notifications" role="menu" aria-labelledby="dLabel" style="max-height: 70vh; overflow-y: auto;">
                    <div class="notification-heading mr-4 d-flex align-items-center">
                        <h4 class="menu-title m-0">Notifications</h4>
                        <!-- <p class="menu-title pull-right m-0">View all <i class="fa fa-arrow-right"></i></p> -->
                    </div>
                    <table class="divider w-100"></li>
                        <?php for ($i = 0; $i < 30; $i++) { ?>
                        <tr class="notifications-wrapper">
                            <td>
                                <a class="content" href="#">
                                <div class="notification-item">
                                    <p class="item-title">Evaluation Deadline 1 · day ago</p>
                                    <small class="m-0 text-faded">Marketing 101, Video Assignment</small>
                                </div>
                            </td>
                        </tr> 
                        <?php } ?>
                    </table></li>
                </ul>
                <!--  -->
                <a class="btn btn-primary" href="../../logout.php">Log Out</a>
            </div>
        <?php }else{ ?>
            <div class="d-flex align-items-center">
                <a class="btn btn-primary" href="login.php">Log In</a>
            </div>
        <?php } ?>
    </div>
</nav>

<!-- IF ANY ERROR IN ERROR OCCURED, UNCOMMENT THIS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>