<?php
session_start();
include('includes/dbconnection.php');

if (!isset($_SESSION['staffid'])) {
    header('location:staff-login.php');
    exit();
}

$staffid = $_SESSION['staffid'];
$role = $_SESSION['staffrole'];
?>
<!DOCTYPE html>
<html>
<head>
<title>Staff Dashboard</title>
<link rel="stylesheet" href="css/bootstrap.css">
</head>

<body>

<div class="container" style="margin-top:30px;">

<h2>Welcome, <?php echo $_SESSION['staffname']; ?></h2>

<hr>

<?php if ($role == "Barber") { ?>

    <h4>Your Appointments</h4>

    <table class="table table-bordered">

    <tr>
        <th>Customer</th>
        <th>Service</th>
        <th>Date</th>
        <th>Time</th>
        <th>Status</th>
    </tr>

    <?php
    $ret = mysqli_query($con,"
        SELECT tblbook.*, tbluser.FirstName, tbluser.LastName, tblservices.ServiceName
        FROM tblbook
        JOIN tbluser ON tbluser.ID = tblbook.UserID
        JOIN tblservices ON tblservices.ID = tblbook.ServiceID
        WHERE tblbook.BarberID='$staffid'
        ORDER BY tblbook.ID DESC
    ");

    while ($row = mysqli_fetch_array($ret)) {
    ?>

    <tr>
        <td><?php echo $row['FirstName']." ".$row['LastName']; ?></td>
        <td><?php echo $row['ServiceName']; ?></td>
        <td><?php echo $row['AptDate']; ?></td>
        <td><?php echo $row['AptTime']; ?></td>
        <td><?php echo $row['Status']; ?></td>
    </tr>

    <?php } ?>

    </table>

<?php } else { ?>

    <h4>You are not assigned as Barber.</h4>

<?php } ?>

</div>

</body>
</html>