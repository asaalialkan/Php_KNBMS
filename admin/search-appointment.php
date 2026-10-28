<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (!isset($_SESSION['bpmsaid']) || strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
    exit();
}
?>

<!DOCTYPE HTML>
<html>
<head>
<title>BPMS || Search Appointment</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">

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

.page-title{
    font-weight:600;
    margin-bottom:20px;
}

.search-box input{
    height:45px;
    border-radius:8px;
}

.btn-search{
    padding:10px 20px;
    border-radius:8px;
}

.table th{
    background:#343a40;
    color:#fff;
}

.badge-empty{
    background:#6c757d;
    color:#fff;
    padding:4px 10px;
    border-radius:12px;
}
</style>

<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/wow.min.js"></script>
<script> new WOW().init(); </script>

</head>

<body class="cbp-spmenu-push">

<div class="main-content">

<?php include_once('includes/sidebar.php');?>
<?php include_once('includes/header.php');?>

<div id="page-wrapper">
<div class="container-fluid">

<div class="row">
<div class="col-md-12">

<div class="card-box">

<h3 class="page-title">
<i class="fa fa-search"></i> Search Appointment
</h3>

<form method="post">

<div class="form-group search-box">
<label>Search by Appointment No / Phone / Name</label>
<input type="text" name="searchdata" class="form-control" required>
</div>

<button type="submit" name="search" class="btn btn-primary btn-search">
<i class="fa fa-search"></i> Search
</button>

</form>

<?php
if (isset($_POST['search'])) {

$sdata = mysqli_real_escape_string($con, $_POST['searchdata']);
?>

<hr>

<h4 align="center">
Results for "<?php echo htmlentities($sdata); ?>"
</h4>

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead>
<tr>
<th>#</th>
<th>Appointment No</th>
<th>Name</th>
<th>Mobile</th>
<th>Date</th>
<th>Time</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php
$ret = mysqli_query($con,"
SELECT tbluser.FirstName, tbluser.LastName, tbluser.MobileNumber,
tblbook.ID as bid, tblbook.AptNumber, tblbook.AptDate, tblbook.AptTime
FROM tblbook
JOIN tbluser ON tbluser.ID = tblbook.UserID
WHERE tblbook.AptNumber LIKE '%$sdata%'
OR tbluser.MobileNumber LIKE '%$sdata%'
OR tbluser.FirstName LIKE '%$sdata%'
");

$cnt = 1;
$num = mysqli_num_rows($ret);

if ($num > 0) {
while ($row = mysqli_fetch_array($ret)) {
?>

<tr>
<td><?php echo $cnt; ?></td>
<td><?php echo htmlentities($row['AptNumber']); ?></td>
<td><?php echo htmlentities($row['FirstName'].' '.$row['LastName']); ?></td>
<td><?php echo htmlentities($row['MobileNumber']); ?></td>
<td><?php echo htmlentities($row['AptDate']); ?></td>
<td><?php echo date("h:i A", strtotime($row['AptTime'])); ?></td>
<td>
<a href="view-appointment.php?viewid=<?php echo $row['bid']; ?>"
class="btn btn-primary btn-sm">
<i class="fa fa-eye"></i> View
</a>
</td>
</tr>

<?php $cnt++; } } else { ?>

<tr>
<td colspan="7" style="text-align:center;">
<span class="badge-empty">No record found</span>
</td>
</tr>

<?php } } ?>

</tbody>
</table>

</div>

</div>

</div>
</div>

</div>
</div>

<?php include_once('includes/footer.php');?>

</div>

<script src="js/bootstrap.js"></script>

</body>
</html>