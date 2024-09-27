<link rel="stylesheet" href="css/navbar.css">
<body>
    <nav class="top-navigation-bar">
        <div class="logo">Admin Dashboard</div>
        <div class="menu">
            <ul>
                <li><a href="admin.php">Home</a></li>
                <li class="dropdown">
                    <a href="#">Products</a>
                    <div class="dropdown-content">
                        <a href="manage-products.php">Manage Products</a>
                        <a href="add-product.php">Add New Product</a>
                    </div>
                </li>
                <li><a href="orders.php">Orders</a></li>
            </ul>
        </div>
        <div class="user-actions">
            <p class="email"><?=$_SESSION['userEmail']?></p>
            <a href="../logout.php"><div class="logout">Logout</div></a>
        </div>
    </nav>
</body>
</html>
