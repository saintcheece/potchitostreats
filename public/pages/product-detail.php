<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page</title> 

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="test.css">
    <style>
        .container-product {
            background-color: rgb(54, 110, 184);
            height: 90vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card-container {
            background-color: white;
            width: 80%;
            height: 90%;
            border-radius: 10px;
            display: flex;
            flex-direction: row;
        }
        .card-container img {
            width: 50%; /* Adjust to your desired size */
            height: 100%;
            object-fit: cover;
            border-radius: 10px 0 0 10px;
        }
        .product-details {
            padding: 20px; 
            display: flex; 
            flex-direction: column; /* Stack items vertically */
            justify-content: center; /* Center items vertically */
            width: 50%; /* Ensure it takes the remaining space beside the image */
        }
        .custom-input {
    width: 200px; /* Set your desired width */
}

    </style>
</head>
<body>

    <?php include 'layout/header.php'; ?>
    <section class="container-product">
        <div class="card-container">
            <img src="assets/cake-picture.jpg" alt="Product Image">
            <div class="product-details">
                <h1>Product Name</h1>
                <p>Product Description</p>
                <p>Price: PESOS SIGN: 100.00 </p>
                
                <!-- Quantity input -->
                <div class="input-group mb-3">
                    <span class="input-group-text">Quantity</span>
                    <input type="number" class="form-control custom-input" min="1" value="1">
                    </div>

                <button class="btn btn-primary mt-2" style="width: 200px;">Add to Cart</button>
            </div>
        </div>
    </section>

    <?php include 'layout/footer.php'; ?>
    
</body>
</html>
