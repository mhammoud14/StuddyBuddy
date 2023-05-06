<?php
include 'connection.php';
session_start();
$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location: login.php');
}
if (isset($_POST['logout'])) {
    session_destroy();
    header('location: login.php');
}
/* delete requests detail from database */
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($connection, "DELETE FROM `users` WHERE id = '$delete_id'") or die('Query Failed');
    header('location:admin_user.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Admin Panel</title>
</head>

<body>
    <?php include 'admin_header.php'; ?>
    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '
                    <div class="message">
                    <span>' . $message . '</span>
                    <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
                    </div> ';
        }
    }
    ?>
    <section class="user-container">
        <h1 class="title">Total registered users</h1>
        <div class="search-container">
            <form method="get" action="">
                <input type="text" name="search" placeholder="Search by user name or email">
                <button type="submit"><i class="bi bi-search"></i></button>
            </form>
        </div>
        <div class="box-container">
            <?php
            if (isset($_GET['search'])) {
                $search = $_GET['search'];
                $select_users = mysqli_query($connection, "SELECT * FROM `users` WHERE LOWER(name) LIKE '%$search%' OR id LIKE '%$search%'") or die('Query Failed');
                if (mysqli_num_rows($select_users) > 0) {
                    while ($fetch_users = mysqli_fetch_assoc($select_users)) {
                        ?>
                        <div class="box">
                            <p>User ID: <span><?php echo $fetch_users['id']; ?></span></p>
                            <p>User Name: <span><?php echo $fetch_users['name']; ?></span></p>
                            <p>Email: <span><?php echo $fetch_users['email']; ?></span></p>
                            <p>User Type: <span style="color:<?php if ($fetch_users['user_type'] == 'admin') {
                                echo 'red';
                            } else {
                                echo 'green';
                            } ?>">
                                    <?php echo $fetch_users['user_type']; ?>
                                </span></p>
                            <a href="admin_user.php?delete=<?php echo $fetch_users['id']; ?>" class="delete"
                                onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </div>
                        <?php
                    }
                } else {
                    echo '<p>No results found.</p>';
                }
            } else {
                $select_users = mysqli_query($connection, "SELECT * FROM `users` ") or die('query failed');
                if (mysqli_num_rows($select_users) > 0) {
                    while ($fetch_users = mysqli_fetch_assoc($select_users)) {
                        ?>
                        <div class="box">
                            <p>User ID: <span><?php echo $fetch_users['id']; ?></span></p>
                            <p>User Name: <span><?php echo $fetch_users['name']; ?></span></p>
                            <p>Email: <span><?php echo $fetch_users['email']; ?></span></p>
                            <p>User Type: <span style="color:<?php if ($fetch_users['user_type'] == 'admin') {
                                echo 'red';
                            } else {
                                echo 'green';
                            } ?>">
                                    <?php echo $fetch_users['user_type']; ?>
                                </span></p>
                            <a href="admin_user.php?delete=<?php echo $fetch_users['id']; ?>" class="delete"
                                onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </div>
                        <?php
                    }
                } else {
                    echo '<p>No registered users found.</p>';
                }
            }
            ?>
        </div>
    </section>
    <script type="text/javascript" src="script.js"></script>
</body>

</html>