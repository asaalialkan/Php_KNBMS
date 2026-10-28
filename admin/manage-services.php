<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (!isset($_SESSION['bpmsaid']) || strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

/* DELETE SERVICE (SAFE CHECK) */
if (isset($_GET['delid']) && is_numeric($_GET['delid'])) {

    $sid = intval($_GET['delid']);

    mysqli_query($con, "DELETE FROM tblservices WHERE ID='$sid'");

    echo "<script>alert('Service deleted successfully');</script>";
    echo "<script>window.location.href='manage-services.php'</script>";
    exit();
}
?>

<!DOCTYPE HTML>
<html>
<head>
<title>BPMS | Manage Services</title>

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
    padding:20px;
    border-radius:12px;
    box-shadow:0 5px 20px rgba(0,0,0,0.08);
}

.table th{
    background:#343a40;
    color:#fff;
}

.btn-sm{
    margin-right:5px;
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

<h3><i class="fa-solid fa-list"></i> Manage Services</h3>

<br>

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead>
<tr>
<th>#</th>
<th>Service Name</th>
<th>Price</th>
<th>Created</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php
$ret = mysqli_query($con,"SELECT * FROM tblservices");
$cnt = 1;

while ($row = mysqli_fetch_array($ret)) {
?>

<tr>
<td><?php echo $cnt; ?></td>
<td><?php echo htmlentities($row['ServiceName']); ?></td>
<td>₱<?php echo htmlentities($row['Cost']); ?></td>
<td><?php echo htmlentities($row['CreationDate']); ?></td>

<td>

<a href="edit-services.php?editid=<?php echo $row['ID']; ?>" class="btn btn-primary btn-sm">
<i class="fa-solid fa-pen"></i> Edit
</a>

<a href="manage-services.php?delid=<?php echo $row['ID']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Are you sure you want to delete this service?')">

<i class="fa-solid fa-trash"></i> Delete
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