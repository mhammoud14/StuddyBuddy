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

/* adding tutors to database */
if (isset($_POST['add_tutor'])) {
    $tutor_name = mysqli_real_escape_string($connection, $_POST['name']);
    $tutor_course = mysqli_real_escape_string($connection, $_POST['course']);
    $tutor_price = mysqli_real_escape_string($connection, $_POST['price']);
    $tutor_linked_in = mysqli_real_escape_string($connection, $_POST['linked_in']);
    $tutor_detail = mysqli_real_escape_string($connection, $_POST['detail']);
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'image/'.$image;
    $select_tutor_name = mysqli_query($connection, "SELECT name FROM `tutors` WHERE name = '$tutor_name'") or die(
        'Query Failed');
    if (mysqli_num_rows($select_tutor_name) > 0) {
        $message[] = 'Tutor already exists';
    } else {
        $insert_tutor = mysqli_query($connection, "INSERT INTO `tutors` (`name`,`course`, `price`,`linked_in` , `tutor_detail`, `image`)
    VALUES ('$tutor_name','$tutor_course','$tutor_price','$tutor_linked_in' ,'$tutor_detail', '$image')") or die('Query Failed');
        if ($insert_tutor) {
            if ($image_size > 2000000) {
                $message[] = 'Image Size is Too Large';
            } else {
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = 'Tutor added successfully';
            }
        }

    }
}

/* delete tutors from database */
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $select_delete_image = mysqli_query($connection, "SELECT image FROM `tutors` WHERE id = $delete_id") or die('
    Query Failed');
    $fetch_delete_image = mysqli_fetch_assoc($select_delete_image);
    unlink('image/'.$fetch_delete_image['image']);
    mysqli_query($connection, "DELETE FROM `tutors` WHERE id = '$delete_id'") or die('Query Failed');
    mysqli_query($connection, "DELETE FROM `favorites` WHERE pid = '$delete_id'") or die('Query Failed');
    header('location:admin_tutor.php');
}
/* update tutors*/

if (isset($_POST['update_tutor'])) {
    $update_p_id = $_POST['update_p_id'];
    $update_p_name = $_POST['update_p_name'];
    $update_p_course = $_POST['update_p_course'];
    $update_p_price = $_POST['update_p_price'];
    $update_p_linked_in = $_POST['update_p_linked_in'];
    $update_p_detail = $_POST['update_p_detail'];
    $current_image = $_POST['current_image'];
    
    if ($_FILES['update_p_image']['name'] != '') {
        // a new image is selected, update it
        $update_p_img = $_FILES['update_p_image']['name'];
        $update_p_image_tmp_name = $_FILES['update_p_image']['tmp_name'];
        $update_p_image_folder = 'image/'.$update_p_img;
        move_uploaded_file($update_p_image_tmp_name, $update_p_image_folder);
        // delete the old image
    } else {
        // no new image selected, keep the current one
        $update_p_img = $current_image;
    }
    
    $update_query = mysqli_query($connection, "UPDATE `tutors` SET id='$update_p_id', name='$update_p_name', course =' $update_p_course',
    price='$update_p_price', linked_in ='$update_p_linked_in' ,tutor_detail='$update_p_detail', image='$update_p_img' WHERE id='$update_p_id'") or die('Queryy failed1');
    if(isset($update_query)) {
        echo "<script>window.location.href='admin_tutor.php';</script>";
        }
        }
    ?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Document</title>
</head>

<body>
    <?php include 'admin_header.php'; ?>
    <?php
    if (isset($message)) {
        foreach($message as $message) {
            echo '
        <div class="message">
        <span>'.$message.'</span>
        <i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
        </div>';
        }
    }
    ?> |
    <section class="add-tutors">
        <form method="post" action="" enctype="multipart/form-data">
            <h1 class="title">Hire New Tutor</h1>
            <div class="input-field">
                <label>Tutor Name</label>
                <input type="text" name="name" required>
            </div>
            <div class="input-field">
                <label>Course</label>
                <input type="text" name="course" required>
            </div>
            <div class="input-field">
                <label>Session Price</label>
                <input type="text" name="price" required>
            </div>
            <div class="input-field">
                <label>Linked in</label>
                <input type="text" name="linked_in" required>
            </div>
            <div class="input-field">
                <label>Tutor Description</label>
                <textarea name="detail" required></textarea>
            </div>
            <div class="input-field">
                <label>Tutor Image</label>
                <input type="file" name="image" accept="image/jpg, image/png, image/jpeg, image/webp" required>
            </div>
            <input type="submit" name="add_tutor" value="add tutor" class="btn">
        </form>
    </section>
    <!-- show tutors section -->
    <section class="show-tutors">
    <div class="box-container">
        <?php
        $select_tutors = mysqli_query($connection, "SELECT * FROM `tutors` ") or die('query failed');
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
            <img src="image/<?php echo $fetch_tutors['image']; ?>">
            <p>price: $
                <?php echo $fetch_tutors['price']; ?>/hr
            </p>
            <h4>
                <?php echo $fetch_tutors['name']; ?>
            </h4>
            <p>Courses :
                <?php echo $fetch_tutors['course']; ?>
            </p>
            <br>
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
            </div>
            <p>Rated by : 
                <?php echo $fetch_tutors['raters']; ?> users
            </p>
            <p class="detail">
                <?php echo $fetch_tutors['tutor_detail']; ?>
            </p>
           
            <a href="admin_tutor.php?edit=<?php echo $fetch_tutors['id'] ?>" class="edit">edit</a>
            <a href="admin_tutor.php?delete=<?php echo $fetch_tutors['id'] ?>" class="delete"
                onclick="return confirm('delete this tutor');">delete</a>
        </div>
        <?php
            }
        }
        ?>
    </div>
</section>
    <section class="update-container">
        <?php


if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $edit_query = mysqli_query($connection, "SELECT * FROM `tutors` WHERE id = $edit_id") or die('Query Failed');
    if (mysqli_num_rows($edit_query) > 0) {
        $fetch_edit = mysqli_fetch_assoc($edit_query);
?>
        <form method="post" action="" enctype="multipart/form-data">
            <img src="image/<?php echo $fetch_edit['image']; ?>">
            <input type="hidden" name="update_p_id" value="<?php echo $fetch_edit['id']; ?>">
            <input type="text" name="update_p_name" value="<?php echo $fetch_edit['name']; ?>">
            <input type="text" name="update_p_course" value="<?php echo $fetch_edit['course']; ?>">
            <input type="number" min="0" name="update_p_price" value="<?php echo $fetch_edit['price']; ?>">
            <input type="text" name="update_p_linked_in" value="<?php echo $fetch_edit['linked_in']; ?>">
            <textarea name="update_p_detail"><?php echo $fetch_edit['tutor_detail']; ?></textarea>
            <input type="hidden" name="current_image" value="<?php echo $fetch_edit['image']; ?>">
            <input type="file" name="update_p_image" accept="image/png, image/jpg, image/jpeg, image/webp">
            <input type="submit" name="update_tutor" value="update" class="edit">
            <input type="reset" value="cancel" class="option-btn btn" id="close-edit">
        </form>
<?php
    }
    echo "<script>document.querySelector('.update-container').style.display='block';</script>";
}
?>
    </section>
    <script type="text/javascript" src="script.js"></script>
</body>

</html>