<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (!isset($_SESSION['bpmsaid']) || strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

/* --------------------------
   HANDLE SEARCH (POST → REDIRECT)
---------------------------*/
if (isset($_POST['search'])) {
    $_SESSION['searchdata'] = $_POST['searchdata'];
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

/* GET SEARCH VALUE */
$sdata = "";
if (isset($_SESSION['searchdata'])) {
    $sdata = mysqli_real_escape_string($con, $_SESSION['searchdata']);
    unset($_SESSION['searchdata']);
}
?>

<!DOCTYPE HTML>
<html>
<head>
<title>BPMS || Search Invoice</title>

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

.search-input{
    height:45px;
    border-radius:8px;
}

.btn-search{
    border-radius:8px;
    padding:10px 18px;
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
<i class="fa fa-file-invoice"></i> Search Invoice
</h3>

<!-- SEARCH FORM -->
<form method="post">

<div class="form-group">
<label>Search by Invoice No / Billing No / Name / Mobile</label>
<input type="text" name="searchdata" class="form-control search-input" required>
</div>

<button type="submit" name="search" class="btn btn-primary btn-search">
<i class="fa fa-search"></i> Search
</button>

</form>

<!-- RESULTS -->
<?php if (!empty($sdata)) { ?>

<hr>

<h4 align="center">
Results for "<?php echo htmlentities($sdata); ?>"
</h4>

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead>
<tr>
<th>#</th>
<th>Invoice ID</th>
<th>Customer Name</th>
<th>Invoice Date</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php
$ret = mysqli_query($con,"
SELECT DISTINCT tbluser.FirstName,
tblinvoice.BillingId,
DATE(tblinvoice.PostingDate) as invoicedate
FROM tbluser
JOIN tblinvoice ON tbluser.ID = tblinvoice.Userid
WHERE tblinvoice.BillingId LIKE '%$sdata%'
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
<td><?php echo htmlentities($row['BillingId']); ?></td>
<td><?php echo htmlentities($row['FirstName']); ?></td>
<td><?php echo htmlentities($row['invoicedate']); ?></td>
<td>
<a href="view-invoice.php?invoiceid=<?php echo $row['BillingId']; ?>"
class="btn btn-primary btn-sm">
<i class="fa fa-eye"></i> View
</a>
</td>
</tr>

<?php $cnt++; } } else { ?>

<tr>
<td colspan="5" style="text-align:center;">
<span class="badge-empty">No record found</span>
</td>
</tr>

<?php } ?>

</tbody>
</table>

</div>

<?php } ?>

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