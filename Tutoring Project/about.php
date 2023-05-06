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
    <title>Study Buddy</title>
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="banner">
        <h1>about us</h1>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. </p>
    </div>
    <div class="about">
        <div class="row">
            <div class="detail">
                <h1>visit our beautiful showroom</h1>
                <p>Our showroom is an expression of what we love doing; being creative with floral and plant
                    arrangements. Whether you are looking for a florist for your perfect wedding, or just want to
                    uplift
                    any room with some one of a kind living decor, Blossom With Love can help.</p>
                <a href="tutors.php" class="btn2">tutors now</a>
            </div>
            <div class="image-box">
                <img src="image/1.png">
            </div>
        </div>
    </div>
    <div class="banner-2">
        <h1>Let us make your wedding flawless</h1>
        <a href="tutors.php" class="btn2">tutors now</a>
    </div>
    <div class="services">
        <h1 class="title">our services</h1>
        <div class="box-container">
            <div class="box">
                <i class="bi bi-percent"></i>
                <h3>30% OFF + FREE SHIPPING</h3>
                <p>Starting at $36/mo. Plus, get $120 creditlyear on regular requests</p>
            </div>
            <div class="box">
                <i class="bi bi-alarm"></i>
                <h3>SUPER FLEXIBLE</h3>
                <p>Customize recipient, date, or flowers. Skip or cancel anytime.</p>
            </div>
        </div>
    </div>
    <div class="stylist">
        <h1 class="title">StudyBuddy</h1>
        <p>Meet the Team That Makes Miracles Happen</p>
        <div class="box-container">
            <div class="box">
                <div class="image-box">
                    <img src="image/team0.jpg">
                    <div class="social-links">
                        <i class="bi bi-instagram"></i>
                        <i class="bi bi-youtube"></i>
                        <i class="bi bi-twitter"></i>
                        <i class="bi bi-whatsapp"></i>
                        <i class="bi bi-behance"></i>
                    </div>
                </div>
                <h4>Moe Hammoud</h4>
                <p>developer</p>
            </div>
            <div class="box">
                <div class="image-box">
                    <img src="image/team1.jpg">
                    <div class="social-links">
                        <i class="bi bi-instagram"></i>
                        <i class="bi bi-youtube"></i>
                        <i class="bi bi-twitter"></i>
                        <i class="bi bi-whatsapp"></i>
                        <i class="bi bi-behance"></i>
                    </div>
                </div>
                <h4>Mazen Awwad</h4>
                <p>developer</p>
            </div>
        </div>
    </div>
    <div class="testimonial-container">
        <h1 class="title">what people say</h1>
        <div class="container">
            <div class="testimonial-item active">
                <img src="image/test.jpg" width="100">
                <h3>sara smith</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                    consequat.</p>
            </div>
            <div class="testimonial-item">
                <img src="image/test.jpg">
                <h3>joe mama</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                    consequat.</p>
            </div>
            <div class="testimonial-item">
                <img src="image/test.jpg">
                <h3>lauren</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                    consequat.</p>
            </div>
            <div class="left-arrow" onclick="nextSlide();"><i class="bi bi-arrow-left"></i></div>
            <div class="right-arrow" onclick="prevSlide();"><i class="bi bi-arrow-right"></i></div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script type="text/javascript" src="script.js"></script>
</body>

</html>