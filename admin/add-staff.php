<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('includes/dbconnection.php');

if (empty($_SESSION['bpmsaid'])) {
    header('location:logout.php');
    exit();
}

/* ================= ADD STAFF ================= */
if (isset($_POST['submit'])) {

    $name   = mysqli_real_escape_string($con, $_POST['name']);
    $email  = mysqli_real_escape_string($con, $_POST['email']);
    $mobile = mysqli_real_escape_string($con, $_POST['mobile']);
    $role   = mysqli_real_escape_string($con, $_POST['role']);

    $username = $email;
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $status   = "Active";

    /* CHECK DUPLICATE EMAIL */
    $check = mysqli_query($con, "SELECT ID FROM tbladmin WHERE Email='$email'");

    if (mysqli_num_rows($check) > 0) {

        echo "<script>alert('Email already exists');</script>";

    } else {

        $query = mysqli_query($con,
            "INSERT INTO tbladmin
            (
                AdminName,
                UserName,
                MobileNumber,
                Email,
                Password,
                Role,
                Status
            )
            VALUES
            (
                '$name',
                '$username',
                '$mobile',
                '$email',
                '$password',
                '$role',
                '$status'
            )"
        );

        if ($query) {
            echo "<script>alert('Staff added successfully');</script>";
            echo "<script>window.location.href='add-staff.php';</script>";
        } else {
            echo "<script>alert('Insert failed');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Staff</title>

<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">

<style>
body{
    background:#f4f6f9;
}

.page-container{
    margin-left:240px;
    padding:25px;
}

.top-title{
    background:#fff;
    padding:15px 20px;
    border-radius:10px;
    margin-bottom:20px;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
}

.center-box{
    display:flex;
    justify-content:center;
}

.form-card{
    width:100%;
    max-width:450px;
    background:#fff;
    padding:30px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
    text-align:center;
}

.form-card h3{
    margin-bottom:5px;
    font-weight:bold;
}

.form-card p{
    font-size:13px;
    color:#777;
    margin-bottom:20px;
}

.input-group{
    margin-bottom:12px;
}

.input-group-addon{
    background:#f1f1f1;
    border-right:0;
    border-radius:8px 0 0 8px;
}

.form-control{
    height:45px;
    border-radius:0 8px 8px 0;
}

.btn-submit{
    width:100%;
    height:45px;
    background:#1a73e8;
    color:#fff;
    border:none;
    border-radius:8px;
    font-weight:bold;
}

.btn-submit:hover{
    background:#0d47a1;
}

@media(max-width:768px){
    .page-container{
        margin-left:0;
    }
}
</style>
</head>

<body>

<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>

<div class="page-container">

    <div class="top-title">
        <h4 style="margin:0;">Add Staff Account</h4>
    </div>

    <div class="center-box">

        <div class="form-card">

            <h3>Create Staff</h3>
            <p>Barber / Admin / Receptionist</p>

            <form method="post">

                <!-- NAME -->
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-user"></i>
                    </span>
                    <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                </div>

                <!-- EMAIL -->
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-envelope"></i>
                    </span>
                    <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                </div>

                <!-- MOBILE -->
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-phone"></i>
                    </span>
                    <input type="text" name="mobile" class="form-control" placeholder="Mobile Number" required>
                </div>

                <!-- PASSWORD -->
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-lock"></i>
                    </span>
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>

                <!-- ROLE -->
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-briefcase"></i>
                    </span>
                    <select name="role" class="form-control" required>
                        <option value="">Select Role</option>
                        <option value="Admin">Admin</option>
                        <option value="Barber">Barber</option>
                        <option value="Receptionist">Receptionist</option>
                    </select>
                </div>

                <button type="submit" name="submit" class="btn-submit">
                    <i class="fa fa-user-plus"></i> Add Staff
                </button>
                <!-- BACK BUTTON -->
<div style="text-align:center; margin-top:15px;">
    <a href="dashboard.php" class="btn btn-default" 
       style="padding:10px 25px; border-radius:6px;">
        <i class="fa fa-arrow-left"></i> Back
    </a>
</div>

            </form>

        </div>

    </div>

</div>

</body>
</html>