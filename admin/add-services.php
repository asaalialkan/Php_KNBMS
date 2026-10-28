<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (!isset($_SESSION['bpmsaid']) || strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

$msg = "";

if (isset($_POST['submit'])) {

    $sername = mysqli_real_escape_string($con, $_POST['sername']);
    $serdesc = mysqli_real_escape_string($con, $_POST['serdesc']);
    $cost = mysqli_real_escape_string($con, $_POST['cost']);

    $image = $_FILES["image"]["name"];
    $tmp = $_FILES["image"]["tmp_name"];

    $extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));
    $allowed = array("jpg","jpeg","png","gif");

    if (!in_array($extension, $allowed)) {
        $msg = "Invalid image format. Only JPG, JPEG, PNG, GIF allowed.";
    } else {

        $newimage = md5($image . time()) . "." . $extension;

        move_uploaded_file($tmp, "images/" . $newimage);

        $query = mysqli_query($con, "
            INSERT INTO tblservices(ServiceName, ServiceDescription, Cost, Image)
            VALUES('$sername','$serdesc','$cost','$newimage')
        ");

        if ($query) {
            $msg = "Service added successfully.";
        } else {
            $msg = "Something went wrong. Try again.";
        }
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
<title>BPMS | Add Services</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">

<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body{
    background:#f4f6f9;
}

.card-box{
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 5px 20px rgba(0,0,0,0.08);
}

.title{
    font-weight:bold;
    margin-bottom:20px;
}

.form-group label{
    font-weight:600;
}

.preview{
    width:100px;
    margin-top:10px;
}
</style>

</head>

<body class="cbp-spmenu-push">

<div class="main-content">

<?php include_once('includes/sidebar.php');?>
<?php include_once('includes/header.php');?>

<div id="page-wrapper">
<div class="container-fluid">

<div class="row">
<div class="col-md-10 col-md-offset-1">

<div class="card-box">

<h3 class="title"><i class="fa-solid fa-scissors"></i> Add Service</h3>

<?php if($msg){ ?>
<p style="color:green;font-weight:bold;"><?php echo $msg; ?></p>
<?php } ?>

<form method="post" enctype="multipart/form-data">

<div class="form-group">
<label>Service Name</label>
<input type="text" class="form-control" name="sername" required>
</div>

<div class="form-group">
<label>Service Description</label>
<textarea class="form-control" name="serdesc" rows="5" required></textarea>
</div>

<div class="form-group">
<label>Cost</label>
<input type="number" class="form-control" name="cost" required>
</div>

<div class="form-group">
<label>Image</label>
<input type="file" class="form-control" name="image" required>
</div>

<button type="submit" name="submit" class="btn btn-primary">
<i class="fa-solid fa-plus"></i> Add Service
</button>

</form>

</div>

</div>
</div>

</div>
</div>

<?php include_once('includes/footer.php');?>

</div>

</body>
</html>