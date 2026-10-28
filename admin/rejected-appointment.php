<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

/* SESSION CHECK FIX */
if (!isset($_SESSION['bpmsaid']) || strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

/* SAFE DELETE */
if (isset($_GET['delid']) && is_numeric($_GET['delid'])) {

    $sid = intval($_GET['delid']);
    mysqli_query($con, "DELETE FROM tblbook WHERE ID='$sid'");

    echo "<script>alert('Appointment deleted successfully');</script>";
    echo "<script>window.location.href='rejected-appointment.php'</script>";
    exit();
}
?>

<!DOCTYPE HTML>
<html>
<head>
<title>BPMS | Rejected Appointments</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body{
    background:#f4f6f9;
}

.card-box{
    background:#fff;
    padding:20px;
    border-radius:12px;
    box-shadow:0 5px 20px rgba(0,0,0,0.08);
}

.table th{
    background:#dc3545;
    color:#fff;
}

.badge-rejected{
    background:#dc3545;
    color:#fff;
    padding:4px 10px;
    border-radius:12px;
    font-size:12px;
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
<div class="col-md-12">

<div class="card-box">

<h3><i class="fa-solid fa-circle-xmark"></i> Rejected Appointments</h3>

<br>

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
<th>Status</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php
$ret = mysqli_query($con,"
SELECT 
tbluser.FirstName,
tbluser.LastName,
tbluser.MobileNumber,
tblbook.ID as bid,
tblbook.AptNumber,
tblbook.AptDate,
tblbook.AptTime,
tblbook.Status
FROM tblbook
JOIN tbluser ON tbluser.ID = tblbook.UserID
WHERE tblbook.Status='Rejected'
ORDER BY tblbook.ID DESC
");

$cnt = 1;

while ($row = mysqli_fetch_array($ret)) {
?>

<tr>
<td><?php echo $cnt; ?></td>

<td><?php echo htmlentities($row['AptNumber']); ?></td>

<td>
<?php echo htmlentities($row['FirstName'].' '.$row['LastName']); ?>
</td>

<td><?php echo htmlentities($row['MobileNumber']); ?></td>

<td><?php echo htmlentities($row['AptDate']); ?></td>

<td><?php echo date("h:i A", strtotime($row['AptTime'])); ?></td>

<td>
<span class="badge-rejected">
<?php echo htmlentities($row['Status']); ?>
</span>
</td>

<td>

<a href="view-appointment.php?viewid=<?php echo $row['bid']; ?>"
class="btn btn-primary btn-sm">
<i class="fa-solid fa-eye"></i>
</a>

<a href="rejected-appointment.php?delid=<?php echo $row['bid']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this rejected appointment?');">

<i class="fa-solid fa-trash"></i>
</a>

</td>

</tr>

<?php $cnt++; } ?>

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

</body>
</html>