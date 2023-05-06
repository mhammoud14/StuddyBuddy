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

     $favorites_number = mysqli_query($connection, "SELECT * FROM `favorites` WHERE name = '$tutor_name' AND 
    user_id='$user_id'") or die('query failed1');
     if (mysqli_num_rows($favorites_number) > 0) {
         $message[] = 'tutor already exist in favorites';
     } else {
         mysqli_query($connection, "INSERT INTO `favorites` (`user_id`, `pid`, `name`, `price`, `image` ) VALUES ('$user_id', 
    '$tutor_id', '$tutor_name', '$tutor_price', '$tutor_image')");
         $message[] = 'tutor successfuly added in favorites';
     }
 }
 ?>
 <style type="text/css">
<?php 
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
     <div class="slider-section">
         <div class="slide-show-container">
             <div class="wrapper-one">
                 <div class="wrapper-text1">Enhance Your skills</div>
             </div>
             <div class="wrapper-two">
                 <div class="wrapper-text2">Expand Your Knowledge</div>
             </div>
             <div class="wrapper-three">
                 <div class="wrapper-text3">Upgrade Your Arsenal</div>
             </div>
         </div>
     </div>
     <div class="categories">
         <h1 class="title">TOP COURSES</h1>
         <div class="box-container">
             <a href="tutors.php?category=Mathematics" class="box">
                 <img src="image/math.jpg">
                 <span>Mathematics</span>
             </a>
             <a href="tutors.php?category=Computer%20Science" class="box">
                 <img src="image/cs.jpg">
                 <span>Computer Science</span>
             </a>
             <a href="tutors.php?category=Biology" class="box">
                 <img src="image/biology.jpg">
                 <span>Biology</span>
             </a>
             <a href="tutors.php?category=Chemistry" class="box">
                 <img src="image/chemistry.jpg">
                 <span>Chemistry</span>
             </a>
             <a href="tutors.php?category=Physics" class="box">
                 <img src="image/physics.jpg">
                 <span>Physics</span>
             </a>
         </div>
     </div>

     <div class="banner3">
         <div style="  color: white ; text-shadow: 1px 1px 1px black;" class="detail">
             <span style="font-size: 20">Want to be a Tutor ?</span>
             <h1>Look No Further!</h1>
             <p style="font-size: 20">Submit a request right now to have the chance to become a tutor!</p>
             <a href="requests.php">Apply now <i class="bi bi-arrow-right"></i></a>
         </div>
     </div>
     <div class="tutors">
         <h1 class="title">Top 3 Tutors</h1>
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
         <div class="box-container">
             <?php
             $select_tutors = mysqli_query($connection, "SELECT * FROM `tutors` WHERE `raters` > 0 ORDER BY `total_ratings`/`raters` DESC, `raters` DESC LIMIT 3") or die('Query failed');
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

         <?php include 'footer.php'; ?>
         <script type="text/javascript" src="script.js"></script>
 </body>

 </html>