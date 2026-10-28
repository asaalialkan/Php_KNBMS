<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

/* DELETE REGISTERED CUSTOMER */
if (isset($_GET['delid'])) {

    $customerId = intval($_GET['delid']);

    mysqli_query($con, "DELETE FROM tbluser WHERE ID='$customerId'");

    echo "<script>alert('Registered customer deleted successfully.');</script>";
    echo "<script>window.location.href='customer-list.php';</script>";
    exit();
}

/* DELETE WALK-IN CUSTOMER */
if (isset($_GET['del_walkin'])) {

    $walkinId = intval($_GET['del_walkin']);

    mysqli_query($con, "DELETE FROM walkin_customers WHERE id='$walkinId'");

    echo "<script>alert('Walk-in customer deleted successfully.');</script>";
    echo "<script>window.location.href='customer-list.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<title>Customer List</title>

<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">

<style>

body{
    background:#f4f6f9;
}

/* WRAPPER */
.container-box{
    max-width:1350px;
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
    font-size:22px;
    font-weight:700;
    color:#222;
}

/* BUTTONS */
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

/* CARD */
.card-box{
    background:#fff;
    padding:20px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
}

/* BADGES */
.badge-custom{
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}

.badge-registered{
    background:#0d6efd20;
    color:#0d6efd;
}

.badge-walkin{
    background:#19875420;
    color:#198754;
}

/* TABLE */
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

/* BUTTONS */
.btn-action{
    padding:5px 10px;
    border-radius:8px;
    font-size:12px;
    text-decoration:none;
    margin-right:5px;
    display:inline-block;
}

.btn-assign{
    background:#c8a96b;
    color:#111;
}

.btn-delete{
    background:#dc354520;
    color:#dc3545;
}

/* PRINT */
@media print{
    .page-header,
    .btn-action,
    .btn-back,
    .btn-print{
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

<!-- HEADER -->
<div class="page-header">

    <div class="page-title">👥 Customer List</div>

    <div>
        <a href="dashboard.php" class="btn-back">⬅ Back</a>
    </div>

</div>

<!-- CARD -->
<div class="card-box">

<div class="table-responsive">

<table class="table table-bordered">

<thead>
<tr>
    <th>#</th>
    <th>Name</th>
    <th>Mobile</th>
    <th>Email</th>
    <th>Date</th>
    <th>Type</th>
    <th>Action</th>
</tr>
</thead>

<tbody>

<?php
$count = 1;

/* REGISTERED */
$registered = mysqli_query($con,"SELECT * FROM tbluser ORDER BY RegDate DESC");

while ($row = mysqli_fetch_assoc($registered)) {
?>

<tr>
<td><?= $count++ ?></td>
<td><?= $row['FirstName'].' '.$row['LastName'] ?></td>
<td><?= $row['MobileNumber'] ?></td>
<td><?= $row['Email'] ?></td>
<td><?= $row['RegDate'] ?></td>
<td><span class="badge-custom badge-registered">Registered</span></td>

<td>

<a href="add-customer-services.php?addid=<?= $row['ID'] ?>"
class="btn-action btn-assign">Assign</a>

<a href="?delid=<?= $row['ID'] ?>"
class="btn-action btn-delete"
onclick="return confirm('Delete customer?')">Delete</a>

</td>
</tr>

<?php } ?>

<?php
/* WALK-IN */
$walkins = mysqli_query($con,"SELECT * FROM walkin_customers ORDER BY id DESC");

while ($row = mysqli_fetch_assoc($walkins)) {
?>

<tr>
<td><?= $count++ ?></td>
<td><?= $row['customer_name'] ?></td>
<td><?= $row['phone'] ?></td>
<td>—</td>
<td><?= $row['appointment_date'].' '.$row['appointment_time'] ?></td>
<td><span class="badge-custom badge-walkin">Walk-In</span></td>

<td>
<span class="btn-action btn-assign" style="pointer-events:none; opacity:0.5;">
        Assign
    </span>

    <a href="?del_walkin=<?= $row['id'] ?>"
       class="btn-action btn-delete"
       onclick="return confirm('Delete walk-in customer?')">
       Delete
    </a>
</td>
</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

</body>
</html>