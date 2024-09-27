<?php 
    require('../../controller/db_model_products.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cookies</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="test.css">

    <link rel="stylesheet" href="flickity.css" media="screen">
    <script src="flickity.pkgd.min.js"></script>
    <style>
        .hero-section {
            height: 20vh; 
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(54, 110, 184);
            color: white;
        }
        .hero-section h2 {
            font-size: 3rem;
        }
        
        .card-img-top {
            height: 250px; 
            object-fit: cover; 
        }
    </style>
</head>
<body style="height: 88vh">

    <?php include 'layout/header.php'; ?>
    <header>
        <div class="hero-section">
            <h2>Cookies</h2>
        </div>
    </header>
    <section class="container py-5">
        <?php displayProductCards($products, 2);?>
    </section>
    <?php include 'layout/footer.php'; ?>
    
</body>
</html>
