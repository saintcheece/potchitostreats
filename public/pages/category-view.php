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
    <div class="red h-50 w-100 d-flex align-items-center justify-content-center">
        <div class="">
            <h1 class="display-3">Category</h1>
        </div>
    </div>

    <!-- HIGHLIGHT PRODUCTS -->
    <div class="yellow w-100 min-vh-100">
        <div class="container" >
            <div class="row py-3" style="height: 75vh">
                <div class="col h-100">
                    <div class="card w-100 me-3 h-100">
                        <img src="../assets/image.png" class="card-img-top h-75" alt="...">
                        <div class="card-body h-25">
                            <h5 class="card-title">Product Name</h5>
                        </div>
                    </div>
                </div>
                <div class="col h-100">
                    <div class="card w-100 me-3 h-100">
                        <img src="../assets/image.png" class="card-img-top h-75" alt="...">
                        <div class="card-body h-25">
                            <h5 class="card-title">Product Name</h5>
                        </div>
                    </div>
                </div>
                <div class="col h-100">
                    <div class="card w-100 me-3 h-100">
                        <img src="../assets/image.png" class="card-img-top h-75" alt="...">
                        <div class="card-body h-25">
                            <h5 class="card-title">Product Name</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pb-3" style="height: 75vh">
                <div class="col h-100">
                    <div class="card w-100 me-3 h-100">
                        <img src="../assets/image.png" class="card-img-top h-75" alt="...">
                        <div class="card-body h-25">
                            <h5 class="card-title">Product Name</h5>
                        </div>
                    </div>
                </div>
                <div class="col h-100">
                    <div class="card w-100 me-3 h-100">
                        <img src="../assets/image.png" class="card-img-top h-75" alt="...">
                        <div class="card-body h-25">
                            <h5 class="card-title">Product Name</h5>
                        </div>
                    </div>
                </div>
                <div class="col h-100">
                    <div class="card w-100 me-3 h-100">
                        <img src="../assets/image.png" class="card-img-top h-75" alt="...">
                        <div class="card-body h-25">
                            <h5 class="card-title">Product Name</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'layout/footer.php'; ?>
    
</body>
</html>