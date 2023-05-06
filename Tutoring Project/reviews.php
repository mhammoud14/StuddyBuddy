<?php
include 'connection.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location: login.php');
}



?>
<style type="text/css">
<?php include 'main.css';
?>
</style>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width-device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Study Buddy</title>
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="banner">
        <h1>tutor detail</h1>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. </p>
    </div>
    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '
                <div class="message">
                    <span>' . $m . '</span>
                    <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
                </div> 
            ';
        }
    }
    
    ?>
    <div class="view-img">
        <div class="box-container">
            <?php
        $tutor_id = $_GET['pid'];
        $select_tutor = mysqli_query($connection, "SELECT * FROM `tutors` WHERE `id` = '$tutor_id'") or die('Query failed');
        $tutor = mysqli_fetch_assoc($select_tutor);
        ?>
            <form action="" method="post" class="box">
                <img src="image/<?php echo $tutor['image']; ?>">
                <div class="name"><?php echo $tutor['name']; ?></div>
                <input type="hidden" name="tutor_id" value="<?php echo $tutor['id']; ?>">
                <input type="hidden" name="tutor_name" value="<?php echo $tutor['name']; ?>">
                <input type="hidden" name="tutor_price" value="<?php echo $tutor['price']; ?>">
                <input type="hidden" name="tutor_quantity" value="1" min="0">
                <input type="hidden" name="tutor_image" value="<?php echo $tutor['image']; ?>">
            </form>
        </div>
    </div>


    <div class="reviews-form-container">
  <h3>Leave a review</h3>
  <form class="reviews-form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?pid=' . $_GET['pid']); ?>">
    <label for="reviews-rating">Rating:</label>
    <div class="rating-stars">
      <input type="radio" name="reviews-rating" value="5" id="rating-5">
      <label for="rating-5">&#9733;</label>
      <input type="radio" name="reviews-rating" value="4" id="rating-4">
      <label for="rating-4">&#9733;</label>
      <input type="radio" name="reviews-rating" value="3" id="rating-3">
      <label for="rating-3">&#9733;</label>
      <input type="radio" name="reviews-rating" value="2" id="rating-2">
      <label for="rating-2">&#9733;</label>
      <input type="radio" name="reviews-rating" value="1" id="rating-1">
      <label for="rating-1">&#9733;</label>
    </div>
    <label for="reviews-text">Review:</label>
    <textarea name="reviews-text" id="reviews-text"></textarea>
    <button name="submit_btn" type="submit">Submit</button>
  </form>
</div>




    <?php
if (isset($_POST['reviews-rating']) && isset($_POST['reviews-text'])) {
    $rating = $_POST['reviews-rating'];
    $text = $_POST['reviews-text'];
    $tutorId = $_GET['pid'];
    $userId = $user_id; // Replace with the actual user ID

    // Check if user ID and tutor ID are the same
    if ($userId == $tutorId) {
        $message[] = "You cannot reviews yourself.";
    } else {
        // Check if user has already reviewed this tutor
        $existing_review_query = mysqli_query($connection, "SELECT * FROM `reviews` WHERE tutor_id = '$tutorId' AND user_id = '$userId'");
        if (mysqli_num_rows($existing_review_query) > 0) {
            // User has already reviewed this tutor
            $message[] = "You have already reviewed this tutor.";
        } else {
            // Insert new reviews into database
            $insert_query = mysqli_query($connection, "INSERT INTO `reviews` (tutor_id, user_id, rating, text) VALUES ('$tutorId', '$userId', '$rating', '$text')") or die("query failed");

            // Update tutor's ratings
            $select_query = mysqli_query($connection, "SELECT total_ratings, raters FROM tutors WHERE id = '$tutorId'");
            $row = mysqli_fetch_assoc($select_query);
            $currentTotalRatings = $row['total_ratings'];
            $currentRaters = $row['raters'];

            // Calculate the new values for total_ratings and raters
            $newTotalRatings = $currentTotalRatings + $rating;
            $newRaters = $currentRaters + 1;

            // Update the tutor's row with the new values
            $update_query = mysqli_query($connection, "UPDATE tutors SET total_ratings = '$newTotalRatings', raters = '$newRaters' WHERE id = '$tutorId'") or die("query failed");

            // Display success message
            $message[] = "reviews submitted successfully.";
        }
    }
}

?>

    <div class="reviews">
        <h2>Reviews</h2>
        <?php
        // Fetch all reviews for this tutor from the database
        $pid = $_GET['pid'];
        $reviews_query = mysqli_query($connection, "SELECT * FROM `reviews` WHERE tutor_id = '$pid'");
        $reviews_count = mysqli_num_rows($reviews_query);

        if ($reviews_count > 0) {
            // If there are reviews, display them
            while ($reviews = mysqli_fetch_assoc($reviews_query)) {
                $username_query = mysqli_query($connection, "SELECT * FROM `users` WHERE id = '{$reviews['user_id']}'");
                $username = mysqli_fetch_assoc($username_query)['name'];
                ?>
        <div class="reviews">
            <h3><?php echo $username; ?>
                <div class="rating">
                    <?php
                        $rating = $reviews['rating'];
                        for ($i = 0; $i < 5; $i++) { // loop through 5 stars
                            if ($rating >= $i + 1) {
                            // full star
                            echo '<span class="fa fa-star checked"></span>';
                            } else if ($rating > $i) {
                            // half star
                            echo '<span class="fa fa-star-half-o checked"></span>';
                            } else {
                            // empty star
                            echo '<span class="fa fa-star-o"></span>';
                            }
                        } ?>
                </div>
                <p><?php echo $reviews['text']; ?></p>
        </div>
        <?php
            }
        } else {
            // If there are no reviews, display a message
            echo '<p>No reviews yet.</p>';
        }
        ?>
    </div>
    </div>
    </div>
    <?php include 'footer.php'; ?>
    <script type="text/javascript" src="script.js"></script>
    <script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    </script>

</body>

</html>