<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> About Us</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="test.css">
    <style>
       *,::before,::after{
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}
body{
    font-family: 'Poppins', sans-serif;
    background-color: #ffffff;

}
#about-us{
    display: flex;
    align-items: center;
    height: 100vh;
    flex-direction: row;
}
#text-container{
    display: flex;
    flex-direction: column;
    align-items: left;
    width: 50%;
    height: 100%;
    background-color: #ffffff;
    padding: 9rem 1.7rem 0rem 3rem;
}
#text-container h1{
    font-size: 2.3rem;
    color: #000000;
    margin-bottom: 1rem;
}
#image-container{   
    background-color: #ffffff;
    width: 50%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;

}
#image-container img{
    width: 60%;
    height: 80%;
    object-fit: cover;
    margin-bottom: 3rem;
}
    </style>
</head>
<body>

    <?php include 'layout/header.php'; ?>

    <section id="about-us">
    <div id="text-container">
        <h1>History of Potchito's Buns x Cookies</h1>
        <p>Welcome to Potchitos Buns and Cookies, where every bite tells a story of passion, creativity, and inspiration. Founded in 2020, we began as a humble side gig, ignited by the enchanting legacy of Madam Kelvin's Son - Potchi. Our bakery is more than just a place to indulge your sweet tooth; it’s a labor of love, born from the desire to bring joy and warmth into every home.</p>

    </div>
    <div id="image-container">
        <img src="assets/potchitos_owner.jpg" alt="">
    </div>

</section>
    <?php include 'layout/footer.php'; ?>

</body>
</html>
