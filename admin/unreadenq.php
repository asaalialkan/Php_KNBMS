<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

/* DELETE */
if (isset($_GET['delid'])) {

    $sid = mysqli_real_escape_string($con, $_GET['delid']);

    mysqli_query($con, "
        DELETE FROM tblcontact
        WHERE ID='$sid'
    ");

    echo "<script>alert('Enquiry Deleted');</script>";
    echo "<script>window.location.href='unreadenq.php'</script>";
    exit();
}
?>

<!DOCTYPE HTML>
<html>
<head>

<title>BPMS || Unread Enquiry</title>

<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">

<style>

body{
    background:#f4f6f9;
}

/* SAME SIZE WRAPPER AS READ ENQUIRY */
.container-box{
    max-width:1200px;
    margin:30px auto;
}

/* HEADER */
.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:15px;
}

.page-title{
    font-size:20px;
    font-weight:700;
    color:#222;
}

/* BUTTONS (SAME SIZE AS READ PAGE) */
.btn-back{
    background:#212529;
    color:#fff;
    padding:8px 14px;
    border-radius:8px;
    text-decoration:none;
    font-size:13px;
}

.btn-print{
    background:#198754;
    color:#fff;
    border:none;
    padding:8px 14px;
    border-radius:8px;
    font-size:13px;
}

/* CARD (MATCH READ PAGE) */
.card-box{
    background:#fff;
    padding:20px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
}

/* TABLE SAME STYLE */
.table thead th{
    background:#212529;
    color:#fff;
    font-size:13px;
    padding:12px;
}

.table tbody td{
    font-size:13px;
    padding:12px;
    vertical-align:middle;
}

.table tbody tr:hover{
    background:#f8f9ff;
}

/* DATE BADGE */
.date-badge{
    display:inline-block;
    background:#0d6efd;
    color:#fff;
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
}

/* ACTION BUTTONS */
.btn{
    font-size:12px;
    padding:5px 10px;
    border-radius:8px;
}

/* PRINT */
@media print{
    .page-header,
    .btn{
        display:none !important;
    }

    body{
        background:#fff;
    }

    .card-box{
        box-shadow:none;
    }
}

</style>

</head>

<body>

<?php include_once('includes/sidebar.php'); ?>
<?php include_once('includes/header.php'); ?>

<div id="page-wrapper">

<div class="container-box">

<!-- HEADER (MATCH READ ENQUIRY) -->
<div class="page-header">

    <div class="page-title">📩 Manage Unread Enquiry</div>

    <div>
        <a href="dashboard.php" class="btn-back">⬅ Back</a>
    </div>

</div>

<!-- CARD -->
<div class="card-box">

<h4 style="margin-bottom:15px;">Unread Enquiries</h4>

<div class="table-responsive">

<table class="table table-bordered">

<thead>
<tr>
<th>S.No</th>
<th>Name</th>
<th>Email</th>
<th>Date & Time</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php
$ret = mysqli_query($con,"
    SELECT *
    FROM tblcontact
    WHERE IsRead IS NULL
");

$cnt = 1;

while($row = mysqli_fetch_array($ret)):
?>

<tr>

<td><?= $cnt ?></td>

<td><?= $row['FirstName'].' '.$row['LastName'] ?></td>

<td><?= $row['Email'] ?></td>

<td>
    <span class="date-badge">
        <i class="fa fa-clock-o"></i>
        <?= date("d M Y h:i A", strtotime($row['EnquiryDate'])) ?>
    </span>
</td>

<td>

    <a href="view-enquiry.php?viewid=<?= $row['ID'] ?>"
       class="btn btn-primary">View</a>

    <a href="unreadenq.php?delid=<?= $row['ID'] ?>"
       class="btn btn-danger"
       onclick="return confirm('Delete this enquiry?')">Delete</a>

</td>

</tr>

<?php $cnt++; endwhile; ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<?php include_once('includes/footer.php'); ?>

<script src="js/bootstrap.js"></script>

</body>
</html>