<?php
    if (isset($_POST['logout'])) {
        header('location: login.php');
        exit;
    }
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Document</title>
</head>

<body>
    <header class="header">
        <div class="flex">
            <a href="index.php" class="logo">Study<span> Buddy</span></a>
            <nav class="navbar">
                <a href="index.php">Home</a>
                <a href="tutors.php">Tutors</a>
                <a href="about.php">About Us</a>
                <a href="contact.php">Contact</a>
            </nav>
            <div class="icons">
                <?php
                // check if user has sent a requests
                $select_requests = mysqli_query($connection, "SELECT * FROM `requests` WHERE user_id = '$user_id'") or die('query failed');
                $requests_num_rows = mysqli_num_rows($select_requests);

                if ($requests_num_rows > 0) {
                    // if requests exists, check if it is accepted
                    $requests = mysqli_fetch_assoc($select_requests);
                    $requests_status = $requests['status'];
                    if ($requests_status == 'accepted') {
                        echo '<i class="bi bi-person-check" id="requests-btn"></i>';
                    } else {
                        echo '<a href="requests.php"><i class="bi bi-person-plus" id="requests-btn"></i></a>';
                    }
                } else {
                    // if no requests exists, show requests button
                    echo '<a href="requests.php"><i class="bi bi-person-plus" id="requests-btn"></i></a>';
                }

                // show favorites icon
                $select_favorites = mysqli_query($connection, "SELECT * FROM `favorites` WHERE user_id = '$user_id'") or die('query failed');
                $favorites_num_rows = mysqli_num_rows($select_favorites);
                echo '<a href="favorites.php"><i class="bi bi-heart"></i><span>(' . $favorites_num_rows . ')</span></a>';

                echo '<i class="bi bi-list" id="menu-btn"></i>';
                echo '<i class="bi bi-person" id="user-btn"></i>';
                ?>
            </div>

            <div class="user-box">
                <p>username: <span><?php echo $_SESSION['user_name']; ?></span></p>
                <p>email : <span><?php echo $_SESSION['user_email']; ?></span></p>
                <br>
                <form method="post" class="logout">
                    <button name="logout" class="logout-btn">LOG OUT</button>
                </form>
        </div>
    </header>
   
</body>

</html>