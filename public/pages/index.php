<?php 
    session_start(); 
    require('../../controller/db_model.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Potchito's Buns and Cookies</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/index.css">

    <link rel="stylesheet" href="../css/flickity.css" media="screen">
    <script src="../js/flickity.pkgd.min.js"></script>

</head>
<body style="height: 88vh">

    <?php include 'layout/header.php'; ?>
    
    <!-- HEADING -->
    <div class="hero-section h-100 w-100 d-flex align-items-center justify-content-start">
        <div class="ms-5 text-white">
            <h1 class="display-3 fw-bold">Cookies & Pastries <br> You'll Really Love</h1>
            <h2>Potchito's Buns x Cookies</h2>
            <?php if(!isset($_SESSION['userID'])){ ?>
            <a href="login.php" class="btn btn-outline-light btn-lg">Order Now</a>
            <?php } ?>
        </div>
    </div>

    <!-- HIGHLIGHT PRODUCTS -->
    <div class="featured-section bg-white py-5">
        <h2 class="ps-5 pt-4 mb-4 text-center">Featured Goodies</h2> 

        <div class="carousel" data-flickity='{ "groupCells": true, "wrapAround": true, "cellAlign": "left", "prevNextButtons": true, "pageDots": false }'>
            <?php foreach ($products as $product) { ?>
                <div class="card mx-2" style="width: 20rem;">
                    <a href="product-view.php?id=<?= $product['pID']?>&type=<?=$product['pType']?>" class="product-link">
                    <img src="../../product-gallery/<?= array('Cookie', 'Pastry', 'Cake')[$product['pType']-1]."_".$product['pID'].".jpg"?>" class="card-img-top">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?= $product['pName'] ?></h5>
                        <p class="card-text">₱<?= $product['pPrice'] ?></p>
                    </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
    
    <!-- CATEGORIES -->
    <div class="h-50 w-100 d-flex flex-row">
        <a href="product-by-type.php?type=3" class="cakes-section h-100 w-100 d-inline-block d-flex align-items-center justify-content-center text-decoration-none">
            <h2 class="text-white">Cakes</h2>
        </a>
        <a href="product-by-type.php?type=2" class="pastries-section h-100 w-100 d-inline-block d-flex align-items-center justify-content-center text-decoration-none">
            <h2 class="text-white">Pastries</h2>
        </a>
        <a href="product-by-type.php?type=1" class="cookies-section h-100 w-100 d-inline-block d-flex align-items-center justify-content-center text-decoration-none">
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

