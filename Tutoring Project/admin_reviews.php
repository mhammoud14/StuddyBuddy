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
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $select_review = mysqli_query($connection, "SELECT * FROM `reviews` WHERE id = '$delete_id'");
    if (mysqli_num_rows($select_review) > 0) {
        $fetch_review = mysqli_fetch_assoc($select_review);
        $tutor_id = $fetch_review['tutor_id'];
        $rating = $fetch_review['rating'];
        $select_tutor = mysqli_query($connection, "SELECT * FROM `tutors` WHERE id = '$tutor_id'");
        if (mysqli_num_rows($select_tutor) > 0) {
            $fetch_tutor = mysqli_fetch_assoc($select_tutor);
            $total_ratings = $fetch_tutor['total_ratings'];
            $raters = $fetch_tutor['raters'];
            if ($raters > 0) {
                $new_total_ratings = $total_ratings - $rating;
                $new_raters = $raters - 1;
                mysqli_query($connection, "UPDATE `tutors` SET total_ratings = '$new_total_ratings', raters = '$new_raters' WHERE id = '$tutor_id'");
            }
        }
    }
    mysqli_query($connection, "DELETE FROM `reviews` WHERE id = '$delete_id'") or die('Query Failed');
    header('location:admin_reviews.php');
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width-device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
    <h1 class="title">Reviews</h1>
    <div class="search-container">
        <form action="" method="POST">
            <input type="text" placeholder="Search by name or ID" name="search">
            <button type="submit" name="submit"><i class="fa fa-search"></i></button>
        </form>
    </div>
    <div class="box-container">
        <?php
        if (isset($_POST['submit'])) {
            $search = mysqli_real_escape_string($connection, $_POST['search']);
            $select_reviews = mysqli_query($connection, "SELECT * FROM `reviews`") or die('query failed');
            if (mysqli_num_rows($select_reviews) > 0) {
                while ($fetch_reviews = mysqli_fetch_assoc($select_reviews)) {
                    $tutor_id = $fetch_reviews['tutor_id'];
                    $select_tutors = mysqli_query($connection, "SELECT * FROM `tutors` WHERE id = '$tutor_id'") or die('query failed');
                    $fetch_tutors = mysqli_fetch_assoc($select_tutors);
                    $search = strtolower($search);
                    $tutor_name = strtolower($fetch_tutors['name']);
                    $tutor_id = strtolower($fetch_reviews['tutor_id']);

                    if (strpos($tutor_name, $search) !== false || strpos($tutor_id, $search) !== false) {
                        echo '<div class="box">
                        <p>Tutor Name: <span>' . $fetch_tutors['name'] . '</span></p>
                        <p>Tutor ID: <span>' . $fetch_reviews['tutor_id'] . '</span></p>
                        <div class="rating">';
                        $rating = $fetch_reviews['rating'];
                        for ($i = 0; $i < 5; $i++) {
                            if ($rating >= $i + 1) {
                                echo '<span class="fa fa-star checked"></span>';
                            } else if ($rating > $i) {
                                echo '<span class="fa fa-star-half-o checked"></span>';
                            } else {
                                echo '<span class="fa fa-star-o"></span>';
                            }
                        }
                        echo '</div>
                      <p>Rated by: ' . $fetch_tutors['raters'] . ' users</p>
                      <p>reviews: <span>' . $fetch_reviews['text'] . '</span></p>
                      <a href="admin_reviews.php?delete=' . $fetch_reviews['id'] . '" class="delete" onclick="return confirm(\'delete this\')">Delete</a>
                    </div>';
                    }
                }
            }
            else {
                echo "No results found";
            }
        } else {
            $select_reviews = mysqli_query($connection, "SELECT * FROM `reviews`") or die('query failed');
            if (mysqli_num_rows($select_reviews) > 0) {
                while ($fetch_reviews = mysqli_fetch_assoc($select_reviews)) {
                    $tutor_id = $fetch_reviews['tutor_id'];
                    $select_tutors = mysqli_query($connection, "SELECT * FROM `tutors` WHERE id = '$tutor_id'") or die('query failed');
                    $fetch_tutors = mysqli_fetch_assoc($select_tutors);
                    echo '<div class="box">
                    <p>Tutor Name: <span>' . $fetch_tutors['name'] . '</span></p>
                    <p>Tutor ID: <span>' . $fetch_reviews['tutor_id'] . '</span></p>
                    <div class="rating">';
                    $rating = $fetch_reviews['rating'];
                    for ($i = 0; $i < 5; $i++) {
                        if ($rating >= $i + 1) {
                            echo '<span class="fa fa-star checked"></span>';
                        } else if ($rating > $i) {
                            echo '<span class="fa fa-star-half-o checked"></span>';
                        } else {
                            echo '<span class="fa fa-star-o"></span>';
                        }
                    }
                    echo '</div>
                    <p>reviews: <span>' . $fetch_reviews['text'] . '</span></p>
                    <a href="admin_reviews.php?delete=' . $fetch_reviews['id'] . '" class="delete" onclick="return confirm(\'delete this\')">Delete</a>
                    </div>';
                }
            } else {
                echo "No reviews found";
            }
        }
        ?>
    </div>
    </section>
    <script type="text/javascript" src="script.js"></script>
</body>

</html>