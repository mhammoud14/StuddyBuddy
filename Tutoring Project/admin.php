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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width-device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Admin Panel</title>
</head>

<body>
    <?php include 'admin_header.php'; ?>
    <section class="dashboard">
        <h1 class="title">Dashboard</h1>
        <div class="box-container">
            <div class="box">
                <?php
                $select_requests = mysqli_query($connection, "SELECT * FROM `requests`") or die('requests Failed3');
                $num_of_requests = mysqli_num_rows($select_requests);
                ?>
                <h3>
                    <?php echo $num_of_requests; ?>
                </h3>
                <p>Tutor Application Requests</p>
            </div>

            <div class="box">
                <?php
                $select_tutors = mysqli_query($connection, "SELECT * FROM `tutors`") or die('Failed to Add4');
                $num_of_tutors = mysqli_num_rows($select_tutors);
                ?>
                <h3>
                    <?php echo $num_of_tutors; ?>
                </h3>
                <p>Certified Tutors</p>
            </div>

            <div class="box">
                <?php
                $select_users = mysqli_query($connection, "SELECT * FROM `users` WHERE user_type = 'user'") or die('Query Failed5');
                $num_of_users = mysqli_num_rows($select_users);
                ?>
                <h3>
                    <?php echo $num_of_users; ?>
                </h3>
                <p>Registered Users</p>
            </div>

            <div class="box">
                <?php
                $select_admins = mysqli_query($connection, "SELECT * FROM `users` WHERE user_type = 'admin'") or die('Query Failed6');
                $num_of_admins = mysqli_num_rows($select_admins);
                ?>
                <h3>
                    <?php echo $num_of_admins; ?>
                </h3>
                <p>Total Admins</p>
            </div>

            <div class="box">
                <?php
                $select_totalusers = mysqli_query($connection, "SELECT * FROM `users` ") or die('Query Failed7');
                $num_of_totalusers = mysqli_num_rows($select_totalusers);
                ?>
                <h3>
                    <?php echo $num_of_totalusers; ?>
                </h3>
                <p>Total Users</p>
            </div>

            <div class="box">
                <?php
                $select_message = mysqli_query($connection, "SELECT * FROM `reviews`") or die('Query Failed8');
                $num_of_message = mysqli_num_rows($select_message); ?>
                <h3>
                    <?php echo $num_of_message; ?>
                </h3>
                <p>Total Reviews</p>
            </div>
            <div class="box">
                <?php
                $select_message = mysqli_query($connection, "SELECT * FROM `messages`") or die('Query Failed8');
                $num_of_message = mysqli_num_rows($select_message); ?>
                <h3>
                    <?php echo $num_of_message; ?>
                </h3>
                <p>New Messages</p>
            </div>
        </div>
    </section>
    <script type="text/javascript" src="script.js"></script>
</body>

</html>