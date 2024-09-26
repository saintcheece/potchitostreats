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

    <!-- HIGHLIGHT PRODUCTS -->
    <div class="orange w-100 h-100">

        <div class="d-flex h-75 justify-content-between">
            <div class="green h-100 w-100">
                <!-- <img src="../assets/image.png" style="height: 100%; object-fit: contain;"> -->
            </div>
            <div class="red h-100 w-100 d-flex align-items-center">
                <div class="m-3">
                    <h1>Product Name</h1>
                    <h4>Description</h4>
                    <h4>Quantity</h4>
                    <button type="button" class="btn btn-primary">Add to cart</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'layout/footer.php'; ?>
    
</body>
</html>