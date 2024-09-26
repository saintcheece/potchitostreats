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

</head>
<body style="height: 88vh">

    <?php include 'layout/header.php'; ?>
    
    <!-- HEADING -->
    <div class="red h-100 w-100 d-flex align-items-center justify-content-start">
        <div class="ms-5">
            <h1 class="display-2">Potchito's <br> Buns & Cookies</h1>
            <h2>Homemade Specialty Pastries</h2>
            <button type="button" class="btn btn-outline-light btn-lg">Order Now</button>
        </div>
    </div>

    <!-- HIGHLIGHT PRODUCTS -->
    <div class="yellow h-100 w-100">
        <h2 class="ps-5 pt-4">Featured Goodies</h2>
        <div class="carousel h-75" data-flickity='{ "groupCells": true }'>
            <?php for ($i=0; $i < 20; $i++) { 
            echo '<div class="card w-25 me-3 h-100">
                <img src="../assets/image.png" class="card-img-top h-75" alt="...">
                <div class="card-body h-25">
                    <h5 class="card-title">Product Name</h5>
                </div>
            </div>';
            } ?>
        </div>
    </div>

    <!-- CATEGORIES -->
    <div class="red h-50 w-100 d-flex flex-row">
        <div class="blue h-100 w-100 d-inline-block d-flex align-items-center justify-content-center">
            <h1>Cakes</h1>
        </div>
        <div class="violet h-100 w-100 d-inline-block d-flex align-items-center justify-content-center">
            <h1>Buns</h1>
        </div>
        <div class="green h-100 w-100 d-inline-block d-flex align-items-center justify-content-center">
            <h1>Cookies</h1>
        </div>
    </div>

    <!-- SOCIAL -->
    <div class="yellow h-75 w-100 d-flex align-items-center justify-content-end">
        <div class="me-5">
            <h2 style="text-align:right">Check our official Facebook page</h2>
        </div>
    </div>

    <?php include 'layout/footer.php'; ?>
    
</body>
</html>