<?php
include 'connection.php';
session_start();

$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location: login.php');
}
/* deleting tutors from favorites */
if (isset($_POST['delete_tutor'])) {
    $delete_id = $_POST['delete_tutor'];
    mysqli_query($connection, "DELETE FROM `favorites` WHERE id = '$delete_id'") or die('Query Failed');
    header('location:favorites.php');
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
        <h1>my favorites</h1>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. </p>
    </div>
    <div class="tutors">
        <h1 class="title">tutors added in favorites </h1>
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
        $select_wishlist = mysqli_query($connection, "SELECT * FROM `favorites` WHERE user_id='$user_id'") or die('Query failed');
        if (mysqli_num_rows($select_wishlist) > 0) {
            while ($fetch_wishlist = mysqli_fetch_assoc($select_wishlist)) {
                ?>
                <form action="" method="post" class="box">
                    <img src="image/<?php echo $fetch_wishlist['image']; ?>">
                    <div class="price">$<?php echo $fetch_wishlist['price']; ?>/Hr</div>
                    <div class="name"><?php echo $fetch_wishlist['name']; ?></div>
                    <input type="hidden" name="tutor_id" value="<?php echo $fetch_wishlist['id']; ?>">
                    <input type="hidden" name="tutor_name" value="<?php echo $fetch_wishlist['name']; ?>">
                    <input type="hidden" name="tutor_price" value="<?php echo $fetch_wishlist['price']; ?>">
                    <input type="hidden" name="tutor_image" value="<?php echo $fetch_wishlist['image']; ?>">
                    <button type="submit" name="delete_tutor" value="<?php echo $fetch_wishlist['id']; ?>" class="button">
                     Remove
                    </button>
                    <a href="<?php echo $fetch_wishlist['linked_in']; ?>" target="_blank" class="button">Tutor Information</a>
                    </a>
                </form>
        <?php
            }
        } else {
            echo '<img src="image/empty.webp">';
        }
        ?>


        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script type="text/javascript" src="script.js"></script>
</body>

</html>