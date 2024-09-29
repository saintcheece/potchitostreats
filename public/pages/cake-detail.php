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
        #background {
            height: 160vh;
            display: flex;
            flex-direction: row;
        }

        .custom-cake-image-container {
            width: 50%;
            height: 100%;
            background-color: rgb(47, 111, 201);
            display: flex;
            justify-content: center;
            padding: 4em 0;
        }

        .custom-cake-image-container img {
            width: 600px;
            height: 500px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 5em;
        }

        .form-container {
            width: 50%;
            background-color: #fff;
            display: flex;
            flex-direction: column;
            padding: 3em;
        }

        .form-container h1 {
            color: #2c5aaa;
            font-size: 2rem;
            margin-top: 1rem;
        }

        .form-container p {
            color: #585858;
            margin: 1rem 0;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2em;
            margin-top: 1.5em;
        }

        .grid-item p {
            margin-bottom: 0.5em;
        }

        input[type="date"], select, textarea {
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #d1d1d1;
            border-radius: 5px;
            width: 100%;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .divider {
            grid-column: span 2;
            border-top: 1px solid #d1d1d1;
            margin: 1.5em 0;
        }

        .total-and-cart {
            display: flex;
            justify-content: space-between;
            align-items: center;
            grid-column: span 2;
        }

        .total-price {
            margin: 0;
        }

        #add-to-cart {
            background-color: #2c5aaa;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #add-to-cart:hover {
            background-color: #1e3f82;
        }

        #message {
            resize: none;
            height: 100px;
        }

        .file-upload {
            grid-column: span 2;
            margin-bottom: 1rem;
        }

        .file-upload input {
            border: 1px solid #d1d1d1;
            padding: 0.5rem;
            border-radius: 5px;
            width: 100%;
        }

        #instructions {
            color: gray;
            font-size: 0.85rem;
            height: 100px;
            resize: none;
        }
        #input-message{
          margin-top: -1rem; /* Adjusted margin */

        }
        .file-upload p {
          margin-top: -1rem
        }
        #additional-instruction-p{
          margin-top: -1rem;
        }
    </style>
</head>
<body>

    <?php include 'layout/header.php'; ?>
    <section id="background">
        <div class="custom-cake-image-container">
            <img src="assets/cake-with-transparent-background-high-quality-ultra-hd-free-photo.jpg" alt="">
        </div>
        <div class="form-container">
            <h1>Cool Custom Cake Title</h1>
            <p id="description-cake">
                Please fill in the details for your custom cake order. You can upload a reference image, specify pickup details, and leave additional instructions.
            </p>
            <p id="allergens"> May Contains Soy, Nut, Hotdog</p>
            <p>If you are ordering a custom cake, the baker will call you to ensure clarity </p>

            <hr />
            <div class="grid-container">
                <div class="grid-item">
                    <p>Select Pickup Date</p>
                    <input type="date" name="pickup-date" id="pickup-date" required>

                </div>
                <div class="grid-item">
                    <p>Select Pickup Time</p>
                    <select name="time" id="time">
                        <option value="09:00">09:00 AM</option>
                        <option value="10:00">10:00 AM</option>
                        <option value="11:00">11:00 AM</option>
                        <option value="12:00">12:00 PM</option>
                        <option value="13:00">01:00 PM</option>
                        <option value="14:00">02:00 PM</option>
                        <option value="15:00">03:00 PM</option>
                        <option value="16:00">04:00 PM</option>
                        <option value="17:00">05:00 PM</option>
                    </select>
                </div>
                <div class="grid-item">
                    <p>Choose Cake Flavor</p>
                    <select name="flavor" id="flavor">
                        <option value="chocolate">Chocolate</option>
                        <option value="vanilla">Vanilla</option>
                        <option value="strawberry">Strawberry</option>
                        <option value="red-velvet">Red Velvet</option>
                    </select>
                </div>
                <div class="grid-item">
                    <p>Choose Cake Size</p>
                    <select name="size" id="size">
                        <option value="6-inch">6-inch</option>
                        <option value="8-inch">8-inch</option>
                        <option value="10-inch">10-inch</option>
                        <option value="12-inch">12-inch</option>
                    </select>
                </div>
                <div class="grid-item full-width">
                    <p id="input-message">Input Message</p>
                    <textarea name="message" id="message" placeholder="Enter your message..." maxlength="200"></textarea>
                </div>
                <!-- Image upload field -->
                <div class="file-upload">
                    <p>Upload Reference Image (optional) Max 5MB</p>
                    <input type="file" accept="image/*">
                </div>

                <!-- Original message for the cake -->
            

                <!-- Additional instructions with max char limit -->
                <div class="grid-item full-width">
                    <p id="additional-instruction-p">Additional Instructions (optional)</p>
                    <textarea name="instructions" id="instructions" maxlength="300" placeholder="Enter any special requests or instructions (max 300 characters)"></textarea>
                </div>

                <div class="divider"></div>

                <div class="total-and-cart">
                    <p class="total-price"><b>Total Price: $45.00</b></p>
                    <button id="add-to-cart">Add to Cart</button>
                </div>
            </div>
        </div>
    </section>

    <?php include 'layout/footer.php'; ?>

</body>
</html>
<script>
    // Get today's date in YYYY-MM-DD format
    const today = new Date();
    const dd = String(today.getDate()).padStart(2, '0');
    const mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
    const yyyy = today.getFullYear();
    const minDate = yyyy + '-' + mm + '-' + dd; // Format: YYYY-MM-DD
    
    // Set the min attribute
    document.getElementById('pickup-date').setAttribute('min', minDate);
</script>