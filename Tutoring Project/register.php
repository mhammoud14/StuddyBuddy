<?php
include 'connection.php';
if (isset($_POST['submit-btn'])) {
    $filter_name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $name = mysqli_real_escape_string($connection, $filter_name);

    $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $email = mysqli_real_escape_string($connection, $filter_email);

    $filter_password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $password = mysqli_real_escape_string($connection, $filter_password);

    $filter_confirm_password = filter_var($_POST['confirm_password'], FILTER_SANITIZE_STRING);
    $confirm_password = mysqli_real_escape_string($connection, $filter_confirm_password);

    $select_user = mysqli_query($connection, "SELECT * FROM `users` WHERE email = '$email'") or die('Query Failed');
    if (mysqli_num_rows($select_user) > 0) {
        $message[] = 'User Already Exists';
    } else {
        if ($password != $confirm_password) {
            $message[] = 'Passwords does not match';
        } else {
            mysqli_query($connection, "INSERT INTO `users` (`name`, `email`,`password`) VALUES ('$name', '$email', '$password')") 
            or die('Query Failed');
            $message[] = 'Registered Successfully!';
            header('location: login.php');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width-device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>User Registration Page</title>
</head>

<body>
    <?php
        if (isset($message)) {
            foreach ($message as $message) {
                echo '
                    <div class="message">
                        <span>' .$message. '</span>
                        <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
                    </div>
                    ';
                }
            }
        ?>
    <section class="form-container">
        <form action="" method="post">
            <h3>Register </h3>
            <input type="text" name="name" placeholder="Enter Your Name" required>
            <input type="email" name="email" placeholder="Enter Your Email" required>
            <input type="password" name="password" placeholder="Enter Your Password" required>
            <input type="password" name="confirm_password" placeholder="Reenter Your Password" required>
            <input type="submit" name="submit-btn" class="btn" value="Register">
            <p>Already Have an Account?<a href="login.php"> Login Now</a></p>
        </form>
    </section>
</body>

</html>