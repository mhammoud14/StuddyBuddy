<?php
 include 'connection.php';
 session_start();

 $user_id = $_SESSION['user_id'];
 if (!isset($user_id)) {
     header('location: login.php');
 }

 /* adding tutors to favorites */
 if (isset($_POST['add_to_favorites'])) {
    $tutor_id = $_POST['tutor_id'];
    $tutor_name = $_POST['tutor_name'];
    $tutor_price = $_POST['tutor_price'];
    $tutor_image = $_POST['tutor_image'];
    $tutor_linked_in = $_POST['tutor_linked_in'];

    $favorites_number = mysqli_query($connection, "SELECT * FROM `favorites` WHERE name = '$tutor_name' AND 
   user_id='$user_id'") or die('query failed1');

    if (mysqli_num_rows($favorites_number) > 0) {
        $message[] = 'tutor already exist in favorites';
    }  else {
        mysqli_query($connection, "INSERT INTO `favorites` (`user_id`, `pid`, `name`, `price`, `image`,`linked_in` ) VALUES ('$user_id', 
   '$tutor_id', '$tutor_name', '$tutor_price', '$tutor_image' , '$tutor_linked_in')");
        $message[] = 'tutor successfuly added in favorites';
    }
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
    <div class="tutors">
        <h1 class="title">tutors best tutors</h1>
        <?php
         if (isset($message)) {
             foreach ($message as $message) {
                 echo '
                    <div class="message">
                        <span>' . $message . '</span>
                        <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
                    </div> 
                ';
             }
         }
         ?>
        <section class="show-tutors">
            <div class="search-container">
                <form method="GET">

                    <input type="text" name="search_query" placeholder="Search by name or course...">
                    <button type="submit"><i class="bi bi-search"></i></button>
                </form>
            </div>
            <div class="box-container">
                <?php
                $category = isset($_GET['category']) ? $_GET['category'] : '';

                // Construct the search query with the category parameter
                $search_query = "SELECT * FROM products";
                if (!empty($category)) {
                    $search_query = $_GET['category'];
                    $select_tutors = mysqli_query($connection, "SELECT * FROM `tutors` WHERE `course` LIKE '%$search_query%'") or die('query failed');
                }
                else{
        if (isset($_GET['search_query'])) {
            $search_query = $_GET['search_query'];
            $select_tutors = mysqli_query($connection, "SELECT * FROM `tutors` WHERE `name` LIKE '%$search_query%' OR `id` = '$search_query' OR `course` LIKE '%$search_query%'") or die('query failed');
        } else {
            $select_tutors = mysqli_query($connection, "SELECT * FROM `tutors`") or die('query failed');
        }
    }
        
        if (mysqli_num_rows($select_tutors) > 0) {
            while ($fetch_tutors = mysqli_fetch_assoc($select_tutors)) {
                $total_ratings = $fetch_tutors['total_ratings'];
                $raters = $fetch_tutors['raters'];
                if ($raters > 0) {
                    $rating = $total_ratings / $raters;
                } else {
                    $rating = 0; 
                }
                ?>
                <div class="box">
                    <form method="POST">
                        <input type="hidden" name="tutor_id" value="<?php echo $fetch_tutors['id']; ?>">
                        <input type="hidden" name="tutor_name" value="<?php echo $fetch_tutors['name']; ?>">
                        <input type="hidden" name="tutor_price" value="<?php echo $fetch_tutors['price']; ?>">
                        <input type="hidden" name="tutor_image" value="<?php echo $fetch_tutors['image']; ?>">
                        <input type="hidden" name="tutor_linked_in" value="<?php echo $fetch_tutors['linked_in']; ?>">
                        <?php
                        // check if tutor is in favorites
                        $tutor_in_favorites = false;
                        $favorites_query = mysqli_query($connection, "SELECT * FROM `favorites` WHERE `pid` = '{$fetch_tutors['id']}' AND `user_id` = '$user_id'") or die('query failed');
                        if (mysqli_num_rows($favorites_query) > 0) {
                            $tutor_in_favorites = true;
                        }

                        // set heart icon
                        $heart_icon = ($tutor_in_favorites) ? "bi-heart-fill" : "bi-heart";
                    ?> <div class="heart">
                            <button type="submit" name="add_to_favorites"
                                class="bi <?php echo $heart_icon; ?>"></button>
                </div>
                </form>
                <img src="image/<?php echo $fetch_tutors['image']; ?>">
                <p>price: $<?php echo $fetch_tutors['price']; ?>/hr</p>
                <h4><?php echo $fetch_tutors['name']; ?></h4>
                <p>Courses : <?php echo $fetch_tutors['course']; ?></p>
                <div class="rating">
                    <?php for ($i = 0; $i < 5; $i++) { // loop through 5 stars
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
                    <p>Rated by :
                        <?php echo $fetch_tutors['raters']; ?> users
                    </p>
                </div>
                <br><br>
                <a href="reviews.php?pid=<?php echo $fetch_tutors['id']; ?>" class="button"> Check Reviews</a>
                <a href="<?php echo $fetch_tutors['linked_in']; ?>" target="_blank" class="button">Tutor Information</a>
            </div>

            <?php
            }
        }
        ?>
    </div>
    </section>
    </div>
    <?php include 'footer.php'; ?>
    <script type="text/javascript" src="script.js"></script>
</body>

</html>