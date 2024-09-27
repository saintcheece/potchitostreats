<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>

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
#contact-us{
    height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;   
     background-color: #ffffff;
}
#contact-us h1{
    margin-top: 2em;
    color: #254ac5;
    font-size: 2.3em;
}
#contact-us p{
    color: #323232;
    font-size:1rem;
}
#contact-us form{
    margin-top: 2em;
    display: flex;
    flex-direction: column;
    align-items: left;
    border: 3px solid #254ac5;
    width: 40%;
    border-radius: 5px;
    padding: 2em;
    height: 60%;
}
#contact-us form label{
    color: #292929;
    font-size: 1.2em;
    margin-top: 0.4em;
}
#contact-us form input{
    margin-top: 0.4em;
    padding: 0.5em;
    border: 1px solid #4b4b4b;
    border-radius: 5px;
}
#contact-us form textarea{
    margin-top: 0.4em;
    padding: 0.5em;
    border: 1px solid #555555;
    border-radius: 5px;
    height: 50%;
    resize: none;
}
#contact-us form input[type="submit"]{
    margin-top: 1em;
    padding: 0.5em;
    border: 1px solid #4b4b4b;
    border-radius: 5px;
    background-color: #254ac5;
    color: white;
    font-size: 1em;
    font-weight: 500;
    cursor: pointer;
    width: auto;
    
}
    </style>
</head>
<body>

    <?php include 'layout/header.php'; ?>

    <section id="contact-us">
  <h1>Contact Us</h1>
  <p>If you have any inquiries, concern, or in need of clarifications.</p>
  <form action="">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <label for="message">Message:</label>
    <textarea id="message" name="message" required></textarea>
    <input type="submit" value="Submit">
  </form>
</section>
    <?php include 'layout/footer.php'; ?>

</body>
</html>
