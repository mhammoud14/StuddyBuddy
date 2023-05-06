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
/* delete requests detail from database */ 
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($connection, "DELETE FROM `requests` WHERE id = '$delete_id'") or die('Query Failed');
    header('location:admin_requests.php');
}

/* update requests detail */
if (isset($_POST['update_request'])) {
    $request_id = $_POST['request_id'];
    $update_status = $_POST['status'];
    $message = '';
    if ($update_status == 'accepted') {
        mysqli_query($connection, "UPDATE `requests` SET status='accepted' WHERE id='$request_id'") or die('query failed');
        $message = 'Application accepted and payment ';
    } elseif ($update_status == 'rejected') {
        mysqli_query($connection, "UPDATE `requests` SET status='rejected' WHERE id='$request_id'") or die('query failed');
        $message = 'Application rejected and payment ';
    } elseif ($update_status == 'pending') {
        mysqli_query($connection, "UPDATE `requests` SET status='pending' WHERE id='$request_id'") or die('query failed');
        $message = 'Application status set to pending a';
    }
    header('location:admin_requests.php?message=' . urlencode($message));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width-device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
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
    <section class="requests-container">
        <h1 class="title">Tutor Applications</h1>
        <div class="box-container">
            <?php
      $select_requests = mysqli_query($connection, "SELECT * FROM `requests` WHERE status='pending' OR status='rejected'") or die('Query Failed1');

       if (mysqli_num_rows($select_requests) > 0) {
           ?>
            <div class="form-container">
                <?php while ($fetch_requests = mysqli_fetch_assoc($select_requests)) { ?>
                <div class="box">
                    <p>User Name: <span><?php echo $fetch_requests['name']; ?></span></p>
                    <p>Email: <span><?php echo $fetch_requests['email']; ?></span></p>
                    <p>Courses: <span><?php echo $fetch_requests['courses']; ?></span></p>
                    <p>Experience: <span><?php echo $fetch_requests['experience']; ?></span></p>
                    <p>Description: <span><?php echo $fetch_requests['description']; ?></span></p>
                    <p>Linked in: <span><?php echo $fetch_requests['linked_in']; ?></span></p>
                    <p>Video Link: <span><?php echo $fetch_requests['video_link']; ?></span></p>
                    <form method="post">
                        <input type="hidden" name="request_id" value="<?php echo $fetch_requests['id']; ?>">
                        <select name="status">
                            <option disabled selected><?php echo $fetch_requests['status']; ?></option>
                            <option value="accepted">Accept</option>
                            <option value="rejected">Reject</option>
                        </select>
                        <input type="submit" name="update_request" value="Update requests" class="btn">
                        <a href="admin_requests.php?delete=<?php echo $fetch_requests['id']; ?>" class="delete"
                            onclick="return confirm('Delete this?')">Delete</a>

                    </form>
                </div>
                <?php } ?>
            </div>
            <?php } ?>

        </div>
    </section>

    <script type="text/javascript" src="script.js"></script>
</body>

</html>