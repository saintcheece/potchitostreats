<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/test.css">

    <link rel="stylesheet" href="../css/flickity.css" media="screen">
    <script src="../js/flickity.pkgd.min.js"></script>
    <style>
        .hero-section {
            background-image: url('../assets/muffins-wallpaper.jpg'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh; 
            color: white; 
            position: relative;
        }
        .featured-section {
            margin-top: 2rem; 
            margin-bottom: 2rem; 
        }
        .cakes-section {
            background: 
            linear-gradient(rgb(54, 110, 184, 0.5), rgb(54, 110, 184, 0.5)), /* Gradient overlay */
            url('../assets/cake-picture.jpg'); /* Background image */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            border: 1px solid white;
            border-top: none;
        }

        .pastries-section {
            background: 
                linear-gradient(rgb(54, 110, 184, 0.5), rgb(54, 110, 184, 0.5)), /* Gradient overlay */
                url('../assets/buns-picture.jpg'); /* Background image */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            border: 1px solid white;
            border-top: none;
        }

        .cookies-section {
            background: 
                linear-gradient(rgb(54, 110, 184, 0.5), rgb(54, 110, 184, 0.5)), /* Gradient overlay */
                url('../assets/cookies-picture.jpg'); /* Background image */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            border: 1px solid white;
            border-top: none;
        }

        .social-section {
            background: 
                linear-gradient(rgb(54, 110, 184, 0.5), rgb(54, 110, 184, 0.5)), /* Gradient overlay */
                url('../assets/facebook-page-picture.jpg'); /* Background image */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            border: 1px solid white;
        }
    </style>

</head>
<body style="height: 88vh">

    <?php include 'layout/header.php'; ?>
    
    <!-- HEADING -->
    <div class="hero-section h-100 w-100 d-flex align-items-center justify-content-start">
        <div class="ms-5 text-white">
            <h1 class="display-3 fw-bold">Potchito's <br> Buns & Cookies</h1>
            <h2>Homemade Specialty Pastries</h2>
            <button type="button" class="btn btn-outline-light btn-lg">Order Now</button>
        </div>
    </div>

    <!-- HIGHLIGHT PRODUCTS -->
    <div class="featured-section bg-white py-5">
        <h2 class="ps-5 pt-4 mb-4 text-center">Featured Goodies</h2> 

        <div class="carousel" data-flickity='{ "groupCells": true, "wrapAround": true, "cellAlign": "left", "prevNextButtons": true, "pageDots": false }'>
            <?php for ($i=0; $i < 5; $i++) { ?>
                <div class="card mx-2" style="width: 20rem;">
                    <img src="../assets/image.png" class="card-img-top" style="height: 300px; object-fit: cover;" alt="Product Image">
                    <div class="card-body text-center">
                        <h5 class="card-title">Product Name</h5>
                        <p class="card-text">Product Description</p>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <!-- CATEGORIES -->
    <div class="h-50 w-100 d-flex flex-row">
        <a href="cakes.php" class="cakes-section h-100 w-100 d-inline-block d-flex align-items-center justify-content-center text-decoration-none">
            <h2 class="text-white">Cakes</h2>
        </a>
        <a href="pastries.php" class="pastries-section h-100 w-100 d-inline-block d-flex align-items-center justify-content-center text-decoration-none">
            <h2 class="text-white">Pastries</h2>
        </a>
        <a href="cookies.php" class="cookies-section h-100 w-100 d-inline-block d-flex align-items-center justify-content-center text-decoration-none">
            <h2 class="text-white">Cookies</h2>
        </a> 
    </div>


    <!-- SOCIAL -->
    <a href="https://www.facebook.com/PotchitosBunsxCookies" target="_blank" style="text-decoration: none; color: inherit;">
        <div class="social-section h-75 w-100 d-flex align-items-center justify-content-end">
            <div class="me-5">
                <h2 style="color: white; text-align: right;">Check our official Facebook page</h2>
            </div>
        </div>
    </a>

    <?php include 'layout/footer.php'; ?>
    
</body>
</html>