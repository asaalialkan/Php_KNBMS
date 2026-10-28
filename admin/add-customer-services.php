<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (!isset($_SESSION['bpmsaid']) || strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

/* =========================
   FORM SUBMIT HANDLER
========================= */
if (isset($_POST['submit'])) {

    $uid = intval($_GET['addid']);
    $invoiceid = mt_rand(100000000, 999999999);

    $sid = isset($_POST['sids']) ? $_POST['sids'] : [];

    if (count($sid) > 0) {

        foreach ($sid as $svid) {
            mysqli_query($con,
                "INSERT INTO tblinvoice(Userid, ServiceId, BillingId)
                 VALUES('$uid','$svid','$invoiceid')"
            );
        }

        echo "<script>
            alert('Invoice created successfully. Invoice No: $invoiceid');
            window.location.href='invoices.php';
        </script>";
        exit();

    } else {
        echo "<script>alert('Please select at least one service');</script>";
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
<title>BPMS || Assign Services</title>

<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">

<script src="js/jquery-1.11.1.min.js"></script>

<style>
body { background:#f4f6f9; }
.card-box{
    background:#fff;
    padding:25px;
    border-radius:10px;
    box-shadow:0 5px 20px rgba(0,0,0,0.08);
}
.table th{
    background:#343a40;
    color:#fff;
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

<h3>Assign Services</h3>

<form method="post">

<table class="table table-bordered">

<thead>
<tr>
<th>#</th>
<th>Service Name</th>
<th>Price</th>
<th>Select</th>
</tr>
</thead>

<tbody>

<?php
$ret = mysqli_query($con, "SELECT * FROM tblservices");
$cnt = 1;

while ($row = mysqli_fetch_array($ret)) {
?>

<tr>
<td><?php echo $cnt++; ?></td>
<td><?php echo $row['ServiceName']; ?></td>
<td>₱ <?php echo number_format($row['Cost'],2); ?></td>
<td>
<input type="checkbox" name="sids[]" value="<?php echo $row['ID']; ?>">
</td>
</tr>

<?php } ?>

<tr>
<td colspan="4" style="text-align:center;">
<button type="submit" name="submit" class="btn btn-primary">
Create Invoice
</button>
</td>
</tr>

</tbody>
</table>

</form>

</div>

</div>
</div>
</div>

<?php include_once('includes/footer.php');?>

</div>

<script src="js/bootstrap.js"></script>
</body>
</html>