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

    $pagetitle = mysqli_real_escape_string($con, $_POST['pagetitle']);
    $pagedes = mysqli_real_escape_string($con, $_POST['pagedes']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $mobnumber = mysqli_real_escape_string($con, $_POST['mobnumber']);
    $timing = mysqli_real_escape_string($con, $_POST['timing']);

    $query = mysqli_query($con, "
        UPDATE tblpage 
        SET 
            PageTitle='$pagetitle',
            Email='$email',
            MobileNumber='$mobnumber',
            Timing='$timing',
            PageDescription='$pagedes'
        WHERE PageType='contactus'
    ");

    if ($query) {
        $msg = "Contact Us page updated successfully.";
    } else {
        $msg = "Something went wrong. Please try again.";
    }
}

$ret = mysqli_query($con, "SELECT * FROM tblpage WHERE PageType='contactus'");
$row = mysqli_fetch_array($ret);
?>

<!DOCTYPE HTML>
<html>
<head>
<title>BPMS | Contact Us</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">

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

textarea{
    resize:none;
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

<h3 class="title"><i class="fa-solid fa-address-book"></i> Update Contact Us</h3>

<?php if($msg){ ?>
<p style="color:green;font-weight:bold;"><?php echo $msg; ?></p>
<?php } ?>

<form method="post">

<div class="form-group">
<label>Page Title</label>
<input type="text" class="form-control" name="pagetitle"
value="<?php echo htmlentities($row['PageTitle']); ?>" required>
</div>

<div class="form-group">
<label>Email</label>
<input type="email" class="form-control" name="email"
value="<?php echo htmlentities($row['Email']); ?>" required>
</div>

<div class="form-group">
<label>Mobile Number</label>
<input type="text" class="form-control" name="mobnumber"
value="<?php echo htmlentities($row['MobileNumber']); ?>" required>
</div>

<div class="form-group">
<label>Timing</label>
<input type="text" class="form-control" name="timing"
value="<?php echo htmlentities($row['Timing']); ?>" required>
</div>

<div class="form-group">
<label>Page Description</label>
<textarea name="pagedes" rows="7" class="form-control" required><?php echo htmlentities($row['PageDescription']); ?></textarea>
</div>

<button type="submit" name="submit" class="btn btn-primary">
<i class="fa-solid fa-floppy-disk"></i> Update
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