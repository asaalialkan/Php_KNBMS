<?php
session_start();
include('includes/dbconnection.php');

if (isset($_POST['login'])) {

    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = md5($_POST['password']); // keep consistent with your system

    $query = mysqli_query($con, "
        SELECT * FROM tbladmin 
        WHERE UserName='$username' 
        AND Password='$password'
        AND Status='Active'
    ");

    $row = mysqli_fetch_array($query);

    if ($row) {

        $_SESSION['staffid'] = $row['ID'];
        $_SESSION['staffrole'] = $row['Role'];
        $_SESSION['staffname'] = $row['AdminName'];

        header("location:staff-dashboard.php");
    } else {
        echo "<script>alert('Invalid login');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Staff Login</title>
<link rel="stylesheet" href="css/bootstrap.css">
</head>

<body>

<div class="container" style="max-width:400px;margin-top:80px;">

<h3>Staff Login</h3>

<form method="post">

<input type="text" name="username" class="form-control" placeholder="Username" required><br>

<input type="password" name="password" class="form-control" placeholder="Password" required><br>

<button type="submit" name="login" class="btn btn-primary btn-block">
Login
</button>

</form>

</div>

</body>
</html>