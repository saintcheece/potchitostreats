<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="test.css">
    <style>
  #cart-section {
 padding: 3em;
 background-color: white;
 height: 100vh;
 }

 .cart-title {
 color: #2c5aaa;
 font-size: 2.5rem;
 margin-bottom: 1.5em;
 text-align: center;
 }  

 .cart-content {
 display: flex;
 justify-content: space-around;
 }

 .cart-items {
 width: 60%;
 padding: 1em;
 border-radius: 10px;
 border: 0.5px solid lightgray;

 }
 .cart-items th {
 padding: 0.75em;
 text-align: center;
 border-bottom: 1px solid #d1d1d1;
 color: #585858;
 font-weight: normal;
 font-size: 0.875rem; /
 }
 .shipping-options {
 margin-bottom: 1rem;
 }

 .shipping-options p {
 font-weight: bold;
 margin-bottom: 0.5rem;
 }
 hr {
 border: 1px solid #3a549c;
 margin: 1em 0;
 }
 .shipping-option {
    margin-bottom: 0.5rem;
    margin-left: 2em;

}

.shipping-option input[type="radio"] {
    margin-right: 0.5rem;
}

.shipping-option label {
    cursor: pointer;
}

.cart-items table {
    width: 100%;
    border-collapse: collapse;
}

.cart-items th, .cart-items td {
    padding: 0.75em;
    text-align: center;
    border-bottom: 1px solid #d1d1d1;
}


.item-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 5px;
}

.quantity-input {
    width: 60px;
    padding: 0.25em;
    border: 1px solid #d1d1d1;
    border-radius: 5px;
    text-align: center;
}

.remove-item {
    border: none;
    background-color: white;
    cursor: pointer;

}


.empty-cart {
    display: block;
    margin-top: 1em;
    background-color: #a32e2e;
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.update-cart:hover {
    background-color: #1e3f82;
}
/* meow */
.price-breakdown {
    width: 35%;
    background-color: rgba(119, 172, 247, 0.218);
    padding: 1em;
    border-radius: 10px;
}

.price-summary, .shipping-options, .total-amount {
    margin-bottom: 1em;
}

.summary-label, .total-amount p {
    font-size: 1.25rem;
    font-weight: bold;
}

.summary-amount, .total-price {
    color: #2c5aaa;
    font-size: 1.5rem;
}

.checkout-button {
    background-color: #2c5aaa;
    color: white;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
}

.checkout-button:hover {
    background-color: #1e3f82;
}

    </style>
</head>
<body>

    <?php include 'layout/header.php'; ?>

    <section id="cart-section">
    <h1 class="h3 mt-1 mb-2 ml-5">Your Cart</h1>
    <div class="cart-content">
        <div class="cart-items">
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>Thumbnail</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                        <button class="remove-item" aria-label="Remove Chocolate Cake" title="Remove Chocolate Cake from cart">
    <i class="fas fa-times"></i>
</button>


                        </td>
                        <td>
                            <img src="assets/cake-thumbnail.jpg" alt="Chocolate Cake Image" class="item-image">
                        </td>
                        <td>Chocolate Cake</td>
                        <td>₱280</td>
                        <td>
                            <input type="number" value="1" min="1" class="quantity-input" aria-label="Quantity of Chocolate Cake">
                        </td>
                        <td>₱280</td>
                    </tr>
                </tbody>
            </table>
            <button class="empty-cart" title="Empty your cart">Empty Cart</button>
        </div>
        <div class="col-lg-3">
            <!-- checkout -->
 <div class="card position-sticky top-0 mb-1">
    <div class="p-3 bg-light bg-opacity-10">
      <h6 class="card-title mb-3">Order Summary</h6>
      <div class="d-flex justify-content-between mb-1 small">
        <span>Product 1 </span> <span>₱280 </span>
      </div>
  
      <hr>
      <div class="d-flex justify-content-between mb-4 small">
        <span>TOTAL</span> <strong class="text-dark">₱280</strong>
      </div>
      <div class="form-check form-check-inline mb-1 small">
        <input class="form-check-input" type="radio" name="deliveryOption" value="pickup" id="pickup">
        <label class="form-check-label" for="pickup">
            Pick up
        </label>
      </div>
      <div class="form-check form-check-inline mb-1 small">
        <input class="form-check-input" type="radio" name="deliveryOption" value="delivery" id="delivery">
        <label class="form-check-label" for="delivery">
            Delivery
        </label>
      </div>
      <div class="mb-1 small">
              <label class="form-check-label text-muted" for="tnc">
          You have a custom cake in your cart. You will be contacted by our team for consultation after order is placed.
        </label>
      </div>
     
      <button class="btn btn-primary w-100 mt-2" onclick="window.location.href='payment.php';">
        Proceed to Checkout
      </button>
    </div>
</div>

  <!-- address -->
<div class="card position-sticky top-0">
    <div class="p-3 bg-light bg-opacity-10">
      <h6 class="card-title mb-3">Address</h6>
        <div class="form-check mb-1 small">
        <input class="form-check-input" type="radio" name="address" value="address1" id="address1">
        <label class="form-check-label" for="address1">
          Address 1 (Brgy. Something, San Ildefonso)
        </label>
      </div>
      <div class="form-check mb-1 small">
        <input class="form-check-input" type="radio" name="address" value="address2" id="address2">
        <label class="form-check-label" for="address2">
        Address 2 (Brgy. Something, Bustos)
        </label>
      </div>
    </div>
</div>
</div>
</section>

    <?php include 'layout/footer.php'; ?>

</body>
</html>
