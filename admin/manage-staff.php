this one: <?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

/* ================= LOGIN CHECK ================= */
if (empty($_SESSION['bpmsaid'])) {
    header('location:logout.php');
    exit();
}

/* ================= DELETE STAFF ================= */
if (isset($_GET['delid'])) {

    $rid = intval($_GET['delid']);

    $del = mysqli_query($con, "DELETE FROM tbladmin WHERE ID='$rid'");

    if ($del) {
        echo "<script>alert('Staff deleted successfully');</script>";
        echo "<script>window.location.href='manage-staff.php';</script>";
    } else {
        echo "<script>alert('Delete failed');</script>";
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
<title>Manage Staff</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">

<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/bootstrap.js"></script>

<style>
#page-wrapper{
    margin-left:300px;
    transition:0.3s;
}

body{
    background:#f4f6f9;
}

.page-container{
    padding:20px;
}

.card-box{
    background:#fff;
    padding:20px;
    border-radius:8px;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
}

.page-title{
    font-size:24px;
    font-weight:600;
    margin-bottom:20px;
}

.table th{
    background:#f8f9fa;
}

.badge-role{
    background:#007bff;
    color:#fff;
    padding:5px 10px;
    border-radius:4px;
    font-size:12px;
}
</style>

</head>

<body>

<div class="page-container">

<?php include_once('includes/sidebar.php');?>
<?php include_once('includes/header.php');?>

<div id="page-wrapper">
<div class="main-page">

<div class="card-box">

<div class="row">
    <div class="col-md-6">
        <h3 class="page-title">Manage Staff Users</h3>
    </div>

    <div class="col-md-6 text-right">
        <a href="add-staff.php" class="btn btn-primary">
            <i class="fa fa-plus"></i> Add Staff
        </a>
    </div>
</div>

<hr>

<table class="table table-bordered table-striped">

<thead>
<tr>
    <th>#</th>
    <th>Name</th>
    <th>Email</th>
    <th>Mobile</th>
    <th>Role</th>
    <th>Status</th>
    <th>Created</th>
    <th width="120">Action</th>
</tr>
</thead>

<tbody>

<?php
$ret = mysqli_query($con, "SELECT * FROM tbladmin ORDER BY ID DESC");

if (!$ret) {
    die("SQL Error: " . mysqli_error($con));
}

$cnt = 1;

while ($row = mysqli_fetch_assoc($ret)) {

    $role   = !empty($row['Role']) ? $row['Role'] : 'Receptionist';
    $status = !empty($row['Status']) ? $row['Status'] : 'Active';
    $date   = !empty($row['AdminRegdate']) ? $row['AdminRegdate'] : 'N/A';
?>

<tr>

<td><?php echo $cnt; ?></td>

<td><?php echo htmlspecialchars($row['AdminName']); ?></td>
<td><?php echo htmlspecialchars($row['Email']); ?></td>
<td><?php echo htmlspecialchars($row['MobileNumber']); ?></td>

<td>
    <span class="badge-role">
        <?php echo htmlspecialchars($role); ?>
    </span>
</td>

<td>
    <?php echo htmlspecialchars($status); ?>
</td>

<td>
    <?php echo htmlspecialchars($date); ?>
</td>

<td>

<!-- EDIT -->
<a href="edit-staff.php?editid=<?php echo $row['ID']; ?>" 
   class="btn btn-success btn-sm">
   <i class="fa fa-pencil"></i>
</a>

<!-- DELETE -->
<a href="manage-staff.php?delid=<?php echo $row['ID']; ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('Delete this staff?');">
   <i class="fa fa-trash"></i>
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

<?php include_once('includes/footer.php');?>

</body>
</html>