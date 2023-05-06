<?php
include 'connection.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location: login.php');
}

// Check if the form is submitted
$is_submitted = false;
if (isset($_POST['submit-btn'])) {
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $courses = mysqli_real_escape_string($connection, $_POST['courses']);
    $experience = mysqli_real_escape_string($connection, $_POST['experience']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    $linked_in = mysqli_real_escape_string($connection, $_POST['linked_in']);

    $video_link = mysqli_real_escape_string($connection, $_POST['video_link']);

    mysqli_query($connection, "INSERT INTO `requests`(`user_id`, `name`,`email`,`courses`, `experience`, `description`, `linked_in`, `video_link`) 
    VALUES ('$user_id', '$name', '$email', '$courses', '$experience', '$description', '$linked_in', '$video_link')") or die('query failed2');
    
    $is_submitted = true;
}

// Get the status of the requests
$select_request = mysqli_query($connection, "SELECT * FROM `requests` WHERE `user_id`='$user_id' LIMIT 1");
$requests = mysqli_fetch_assoc($select_request);
$status = isset($requests) ? $requests['status'] : 'Pending';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width-device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <title><?php echo $status; ?></title>
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="banner">
        <h1><?php echo $status; ?></h1>
        <?php if (!$is_submitted && !isset($requests)): ?>
        <h1>Please fill out the form below to submit your requests.</h1>
        <?php endif; ?>
    </div>
    <div class="form-container">
        <?php if (!$is_submitted && !isset($requests)): ?>
        <div class="form-section">
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="courses">Courses:</label>
                    <input type="text" id="courses" name="courses" required>
                </div>
                <div class="form-group">
                    <label for="experience">Experience:</label>
                    <input type="text" id="experience" name="experience" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="4" cols="50" required></textarea>
                </div>
                <div class="form-group">
                    <label for="linked_in">Linked in:</label>
                    <input type="text" id="linked_in" name="linked_in" required>
                </div>
                <div class="form-group">
                    <label for="video_link">Video Link:</label>
                    <input type="text" id="video_link" name="video_link" required>
                </div>
                <div class="form-group">
                    <button type="submit" name="submit-btn" class="btn">Submit</button>
                </div>
            </form>
        </div>
        <?php elseif ($is_submitted): ?>
        <div class="form-section">
            <h1>Your requests has been submitted successfully. We will get back to you as soon as possible.</h1>
        </div>
        <?php else: ?>
        <div class="form-section">
            <h1>Your requests is currently <?php echo $status; ?>. We will notify you once there is an update.</h1>
        </div>
        <?php endif; ?>
    </div>
    <?php include 'footer.php'; ?>

</body>

</html>