<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
    exit();
} else {

$vid = intval($_GET['viewid']);

/* Mark as read safely */
mysqli_query($con, "UPDATE tblcontact SET IsRead = 1 WHERE ID = '$vid'");
?>

<!DOCTYPE HTML>
<html>
<head>
<title>BPMS || View Enquiry</title>

<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">

<style>
.card-box {
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 5px 20px rgba(0,0,0,0.08);
}

.title-bar {
    text-align:center;
    font-size:22px;
    font-weight:bold;
    color:#007bff;
    margin-bottom:20px;
}

.badge-time {
    display:inline-block;
    background:#28a745;
    color:#fff;
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
}
</style>

</head>

<body class="cbp-spmenu-push">

<div class="main-content">

<?php include_once('includes/sidebar.php');?>
<?php include_once('includes/header.php');?>

<div id="page-wrapper">
<div class="main-page">

<div class="card-box">

<div class="title-bar">
View Enquiry Details
</div>

<?php
$ret = mysqli_query($con, "SELECT * FROM tblcontact WHERE ID='$vid'");
while ($row = mysqli_fetch_array($ret)) {
?>

<table class="table table-bordered" style="font-size:16px;">

<tr>
<th>Name</th>
<td><?php echo $row['FirstName'].' '.$row['LastName']; ?></td>

<th>Email</th>
<td><?php echo $row['Email']; ?></td>
</tr>

<tr>
<th>Contact No</th>
<td><?php echo $row['Phone']; ?></td>

<th>Inquiry Date & Time</th>
<td>
    <span class="badge-time">
        <i class="fa fa-clock"></i>
        <?php echo date("d M Y h:i A", strtotime($row['EnquiryDate'])); ?>
    </span>
</td>
</tr>

<tr>
<th>Message</th>
<td colspan="3"><?php echo $row['Message']; ?></td>
</tr>

</table>

<?php } ?>

</div>

</div>
</div>

<?php include_once('includes/footer.php');?>

</div>

<script src="js/bootstrap.js"></script>

</body>
</html>

<?php } ?>