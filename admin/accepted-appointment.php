<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (!isset($_SESSION['bpmsaid']) || strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

/* SAFE DELETE — booked appointment */
if (isset($_GET['delid']) && is_numeric($_GET['delid'])) {
    $sid = intval($_GET['delid']);
    mysqli_query($con, "DELETE FROM tblbook WHERE ID='$sid'");
    echo "<script>alert('Appointment deleted successfully.');</script>";
    echo "<script>window.location.href='accepted-appointment.php'</script>";
    exit();
}

/* DELETE WALK-IN */
if (isset($_GET['delwalk']) && is_numeric($_GET['delwalk'])) {
    $sid = intval($_GET['delwalk']);
    mysqli_query($con, "DELETE FROM tblwalkin WHERE ID='$sid'");
    echo "<script>alert('Walk-in record deleted successfully.');</script>";
    echo "<script>window.location.href='accepted-appointment.php'</script>";
    exit();
}

/* UPDATE STATUS TO NO SHOW */
if (isset($_GET['noshow']) && is_numeric($_GET['noshow'])) {
    $sid = intval($_GET['noshow']);
    mysqli_query($con, "UPDATE tblbook SET Status='No Show' WHERE ID='$sid'");
    echo "<script>alert('Appointment marked as No Show.');</script>";
    echo "<script>window.location.href='accepted-appointment.php'</script>";
    exit();
}

/* REVERT STATUS BACK TO SELECTED */
if (isset($_GET['revert']) && is_numeric($_GET['revert'])) {
    $sid = intval($_GET['revert']);
    mysqli_query($con, "UPDATE tblbook SET Status='Selected' WHERE ID='$sid'");
    echo "<script>alert('Appointment status reverted to Selected.');</script>";
    echo "<script>window.location.href='accepted-appointment.php'</script>";
    exit();
}

/* ADD WALK-IN CUSTOMER */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_walkin') {

    $name   = trim(mysqli_real_escape_string($con, $_POST['walkin_name']));
    $mobile = trim(mysqli_real_escape_string($con, $_POST['walkin_mobile']));
    $date   = trim(mysqli_real_escape_string($con, $_POST['walkin_date']));
    $time   = trim(mysqli_real_escape_string($con, $_POST['walkin_time']));

    if ($name !== '' && $date !== '' && $time !== '') {
        $aptNum = 'WI' . rand(100000000, 999999999);
        mysqli_query($con,
            "INSERT INTO tblwalkin (AptNumber, FullName, MobileNumber, AptDate, AptTime, Status)
             VALUES ('$aptNum', '$name', '$mobile', '$date', '$time', 'Walk-In')"
        );
        echo "<script>alert('Walk-in customer added successfully.');</script>";
    } else {
        echo "<script>alert('Please fill in all required fields.');</script>";
    }

    echo "<script>window.location.href='accepted-appointment.php'</script>";
    exit();
}
?>

<!DOCTYPE HTML>
<html>
<head>
<title>BPMS | Accepted Appointments</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body {
    background: #f4f6f9;
}

.card-box {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.table th {
    background: #343a40;
    color: #fff;
}

.badge-selected {
    background: #198754;
    color: #fff;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    white-space: nowrap;
}

.badge-noshow {
    background: #dc3545;
    color: #fff;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    white-space: nowrap;
}

.badge-walkin {
    background: #0d6efd;
    color: #fff;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    white-space: nowrap;
}

.modal-header {
    background: #343a40;
    color: #fff;
}

.modal-header .btn-close {
    filter: invert(1);
}

.section-title {
    font-size: 16px;
    font-weight: 600;
    margin: 28px 0 10px;
    color: #343a40;
    border-left: 4px solid #343a40;
    padding-left: 10px;
}
</style>
</head>

<body class="cbp-spmenu-push">
<div class="main-content">

<?php include_once('includes/sidebar.php'); ?>
<?php include_once('includes/header.php'); ?>

<div id="page-wrapper">
<div class="container-fluid">

<div class="row">
<div class="col-md-12">

<div class="card-box">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0"><i class="fa-solid fa-circle-check"></i> Approved Appointments</h3>
    </button>
</div>

<!-- BOOKED APPOINTMENTS -->
<div class="section-title"><i class="fa-solid fa-calendar-check fa-sm"></i> Booked Appointments</div>

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
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (!isset($_SESSION['bpmsaid']) || strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

/* DELETE BOOKED */
if (isset($_GET['delid']) && is_numeric($_GET['delid'])) {
    $sid = intval($_GET['delid']);
    mysqli_query($con, "DELETE FROM tblbook WHERE ID='$sid'");
    echo "<script>alert('Appointment deleted successfully.');</script>";
    echo "<script>window.location.href='accepted-appointment.php'</script>";
    exit();
}

/* DELETE WALK-IN (FIXED TABLE NAME) */
if (isset($_GET['delwalk']) && is_numeric($_GET['delwalk'])) {
    $sid = intval($_GET['delwalk']);
    mysqli_query($con, "DELETE FROM walkin_customers WHERE id='$sid'");
    echo "<script>alert('Walk-in deleted successfully.');</script>";
    echo "<script>window.location.href='accepted-appointment.php'</script>";
    exit();
}

/* NO SHOW */
if (isset($_GET['noshow']) && is_numeric($_GET['noshow'])) {
    $sid = intval($_GET['noshow']);
    mysqli_query($con, "UPDATE tblbook SET Status='No Show' WHERE ID='$sid'");
    echo "<script>alert('Marked as No Show');</script>";
    echo "<script>window.location.href='accepted-appointment.php'</script>";
    exit();
}

/* REVERT */
if (isset($_GET['revert']) && is_numeric($_GET['revert'])) {
    $sid = intval($_GET['revert']);
    mysqli_query($con, "UPDATE tblbook SET Status='Selected' WHERE ID='$sid'");
    echo "<script>alert('Reverted to Selected');</script>";
    echo "<script>window.location.href='accepted-appointment.php'</script>";
    exit();
}

/* ADD WALK-IN (FIXED STRUCTURE) */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_walkin') {

    $name   = mysqli_real_escape_string($con, $_POST['walkin_name']);
    $mobile = mysqli_real_escape_string($con, $_POST['walkin_mobile']);
    $service= mysqli_real_escape_string($con, $_POST['walkin_service']);
    $date   = mysqli_real_escape_string($con, $_POST['walkin_date']);
    $time   = mysqli_real_escape_string($con, $_POST['walkin_time']);

    /* get price from services */
    $price = 0;
    $q = mysqli_query($con, "SELECT Cost FROM tblservices WHERE ServiceName='$service' LIMIT 1");
    if ($q && mysqli_num_rows($q) > 0) {
        $row = mysqli_fetch_assoc($q);
        $price = $row['Cost'];
    }

    mysqli_query($con, "
        INSERT INTO walkin_customers
        (customer_name, phone, service, amount, appointment_date, appointment_time)
        VALUES
        ('$name','$mobile','$service','$price','$date','$time')
    ");

    echo "<script>alert('Walk-in added successfully');</script>";
    echo "<script>window.location.href='accepted-appointment.php'</script>";
    exit();
}
?>
<?php
$ret = mysqli_query($con, "
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
    WHERE tblbook.Status = 'Selected' OR tblbook.Status = 'No Show'
    ORDER BY tblbook.ID DESC
");

$cnt = 1;

while ($row = mysqli_fetch_array($ret)) {
    $bid    = intval($row['bid']);
    $status = $row['Status'];
?>

<tr>
    <td><?php echo $cnt; ?></td>
    <td><?php echo htmlentities($row['AptNumber']); ?></td>
    <td><?php echo htmlentities($row['FirstName'] . ' ' . $row['LastName']); ?></td>
    <td><?php echo htmlentities($row['MobileNumber']); ?></td>
    <td><?php echo htmlentities($row['AptDate']); ?></td>
    <td><?php echo date("h:i A", strtotime($row['AptTime'])); ?></td>
    <td>
        <?php if ($status === 'No Show'): ?>
            <span class="badge-noshow"><i class="fa-solid fa-user-slash"></i> No Show</span>
        <?php else: ?>
            <span class="badge-selected"><i class="fa-solid fa-circle-check"></i> Selected</span>
        <?php endif; ?>
    </td>
    <td>
        <a href="view-appointment.php?viewid=<?php echo $bid; ?>"
           class="btn btn-primary btn-sm" title="View">
            <i class="fa-solid fa-eye"></i>
        </a>

        <?php if ($status === 'No Show'): ?>
            <a href="accepted-appointment.php?revert=<?php echo $bid; ?>"
               class="btn btn-success btn-sm" title="Revert to Selected"
               onclick="return confirm('Revert this appointment back to Selected?');">
                <i class="fa-solid fa-rotate-left"></i>
            </a>
        <?php else: ?>
            <a href="accepted-appointment.php?noshow=<?php echo $bid; ?>"
               class="btn btn-warning btn-sm" title="Mark as No Show"
               onclick="return confirm('Mark this customer as No Show?');">
                <i class="fa-solid fa-user-slash"></i>
            </a>
        <?php endif; ?>

        <a href="accepted-appointment.php?delid=<?php echo $bid; ?>"
           class="btn btn-danger btn-sm" title="Delete"
           onclick="return confirm('Are you sure you want to delete this appointment?');">
            <i class="fa-solid fa-trash"></i>
        </a>
    </td>
</tr>

<?php $cnt++; } ?>

</tbody>
</table>
</div>

 </div>

        <!-- FOOTER -->
        <?php include_once('includes/footer.php');?>

    </div>
