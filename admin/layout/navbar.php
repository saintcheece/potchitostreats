<link rel="stylesheet" href="css/navbar.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<?php
    function notificationLink($type){
        switch($type){
            case 1:
                return "orders.php"; break;
            case 2:
                return "http://mail.google.com/"; break;
            case 3:
                return "orders.php"; break;
            default:
                return "#";
        }
    }
?>
<style>
    /* The side navigation menu */
    .sidenav {
    height: 100%; /* 100% Full-height */
    width: 0; /* 0 width - change this with JavaScript */
    position: fixed; /* Stay in place */
    z-index: 1; /* Stay on top */
    top: 0;
    left: 0;
    overflow-x: hidden; /* Disable horizontal scroll */
    padding-top: 3vh; /* Place content 60px from the top */
    transition: 0.5s; /* 0.5 second transition effect to slide in the sidenav */
    }

    /* The navigation menu links */
    .sidenav a {
    padding: 8px 8px 8px 32px;
    text-decoration: none;
    font-size: 25px;
    color: #818181;
    display: block;
    transition: 0.3s;
    }

    /* When you mouse over the navigation links, change their color */
    .sidenav a:hover {
    color: #f1f1f1;
    }

    /* Position and style the close button (top right corner) */
    .sidenav .closebtn {
    font-size: 36px;
    }

    /* Style page content - use this if you want to push the page content to the right when you open the side navigation */
    #main {
    transition: margin-left .5s;
    padding: 20px;
    }

    /* On smaller screens, where height is less than 450px, change the style of the sidenav (less padding and a smaller font size) */
    @media screen and (max-height: 450px) {
    .sidenav {padding-top: 15px;}
    .sidenav a {font-size: 18px;}
    }
</style>

    <nav class="top-navigation-bar">
        <div class="logo">Potchito's Admin Access</div>
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
                <li><a href="audit.php">Audit</a></li>
                <li><a href="reports.php">Reports</a></li>
                <li>
                <?php
                    function countNotifications(){
                        global $conn;
                        $stmt = $conn->prepare("SELECT COUNT(*) FROM notifications WHERE nViewed = 0");
                        $stmt->execute();
                        $result = $stmt->fetch();
                        return $result[0];
                    }
                ?>
                    <a id="notifications" onclick="openNav()" class="position-relative" style="cursor: pointer">
                        Notifications
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= countNotifications();?>
                        <span class="visually-hidden">unread messages</span>
                        </span>
                    </a>            
                </li>
            </ul>
        </div>
        <div class="user-actions">
            <!-- <p class="email"><?=$_SESSION['userEmail']?></p> -->
            <a href="../logout.php"><div class="logout">Logout</div></a>
        </div>
    </nav>

    <script>

        /* Set the width of the side navigation to 250px and the left margin of the page content to 250px */
        function openNav() {
        document.getElementById("mySidenav").style.width = "350px";
        document.getElementById("main").style.marginLeft = "350px";
        }

        /* Set the width of the side navigation to 0 and the left margin of the page content to 0 */
        function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
        document.getElementById("main").style.marginLeft = "0";
        }

        $('#notifications').click(function() {
            markNotificationsViewed();
        });

        function markNotificationsViewed() {
            $.ajax({
                url: '../controller/mark-notifications-viewed.php',
                type: 'POST',
                success: function(response) {
                    if(response == 'success') {
                        console.log('All notifications marked as viewed.');
                    } else {
                        console.error('Failed to mark notifications as viewed.');
                    }
                },
                error: function() {
                    console.error('Error in AJAX request.');
                }
            });
        }
    </script>

<div id="mySidenav" class="sidenav p-0">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div class="m-3 h3 fw-bold">Notifications</div>
            <div><a href="javascript:void(0)" class="closebtn me-3" onclick="closeNav()">&times;</a></div>
        </div>
        <table style="width: 100%; border-top: 1px solid #ddd; border-bottom: 1px solid #ddd;">
        <?php 
            $stmt = $conn->prepare("SELECT n.nID, CONCAT(u.uFName, ' ', u.uLName) AS uName, ne.neOpID, ne.neOpDesc, n.nTime, nViewed 
                                    FROM notifications n
                                    INNER JOIN users u ON n.uID = u.uID
                                    INNER JOIN notifications_enum ne ON n.neOpID = ne.neOpID
                                    ORDER BY n.nTime DESC");
            $stmt->execute();
            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($notifications as $notification) {
                ?>
                <tr style="cursor: pointer; background-color: #f1f1f1;" onmouseover="this.style.backgroundColor='#ddd'" onmouseout="this.style.backgroundColor='#f1f1f1'" onclick="window.location.href='<?= notificationLink($notification['neOpID'])?>'">
                <td style="border-top: 1px solid #ddd; border-bottom: 1px solid #ddd;" onclick="<?= notificationLink($notification['neOpID']) ?>">
                    <div class="my-2">
                        <p class="ms-3 mb-0"><b><?=$notification['uName'] ?></b> <?= $notification['neOpDesc'] . ($notification['nViewed'] == 0 ? ' <span class="badge rounded-pill bg-success">New</span>' : '') ?></p>
                        <div style="text-align: right;"><small class="ms-3 me-3 mb-0"><?php echo date('M j, g:i A', strtotime($notification['nTime'])); ?></small></div>
                    </div>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>