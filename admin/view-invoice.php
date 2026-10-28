<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

$invid = isset($_GET['invoiceid']) ? intval($_GET['invoiceid']) : 0;
$type  = isset($_GET['type']) ? $_GET['type'] : "Online";

$gtotal = 0;
?>

<!DOCTYPE HTML>
<html>
<head>
<title>BPMS | Invoice</title>

<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">

<style>
body{background:#f4f6f9;font-family:Arial;}
.receipt-box{width:280px;margin:20px auto;background:#fff;padding:10px;border:1px solid #ddd;font-size:11px;}
.header{text-align:center;border-bottom:1px dashed #000;padding-bottom:8px;margin-bottom:10px;}
table{width:100%;border-collapse:collapse;font-size:11px;}
th{background:#000;color:#fff;padding:4px;}
td{padding:4px;border-bottom:1px solid #eee;}
.total{font-weight:bold;}
.print-btn{width:100%;background:#007bff;color:#fff;border:none;padding:10px;font-size:14px;margin-top:10px;cursor:pointer;border-radius:5px;}
.print-btn:hover{background:#0056b3;}
@media print{.print-btn{display:none;}}
</style>

</head>

<body>

<div class="receipt-box" id="exampl">

<div class="header">
    <h3>Kuya Nash Barbershop</h3>
    <p>Invoice #: <?php echo $invid; ?></p>
    <p><?php echo $type; ?></p>
</div>

<?php
/* ================= WALK-IN ================= */
if ($type == "Walk-in") {

    $ret = mysqli_query($con,"SELECT * FROM walkin_customers WHERE id='$invid'");

    if ($ret && mysqli_num_rows($ret) > 0) {

        $row = mysqli_fetch_assoc($ret);
?>

<table>
<tr><td><b>Name:</b></td><td><?php echo $row['customer_name'] ?? ''; ?></td></tr>
<tr><td><b>Phone:</b></td><td><?php echo $row['phone'] ?? ''; ?></td></tr>
<tr><td><b>Date:</b></td><td><?php echo $row['appointment_date'] ?? ''; ?></td></tr>
</table>

<br>

<table>
<tr>
<th>Service</th>
<th>Price</th>
</tr>

<?php
$service = isset($row['service']) ? explode(",", $row['service']) : [];
$price   = isset($row['amount']) ? explode(",", $row['amount']) : [];

for ($i = 0; $i < count($service); $i++) {

    $p = isset($price[$i]) ? $price[$i] : 0;
    $gtotal += $p;
?>
<tr>
<td><?php echo $service[$i]; ?></td>
<td>₱<?php echo $p; ?></td>
</tr>
<?php } ?>

<tr>
<td class="total">TOTAL</td>
<td class="total">₱<?php echo $gtotal; ?></td>
</tr>
</table>

<?php
    } else {
        echo "<p style='text-align:center;color:red;'>Walk-in invoice not found</p>";
    }

/* ================= ONLINE ================= */
} else {

$ret = mysqli_query($con,"
SELECT DISTINCT
DATE(tblinvoice.PostingDate) as invoicedate,
tbluser.FirstName,
tbluser.LastName,
tbluser.MobileNumber
FROM tblinvoice
JOIN tbluser ON tbluser.ID = tblinvoice.Userid
WHERE tblinvoice.BillingId = '$invid'
");

if ($ret && mysqli_num_rows($ret) > 0) {

    $row = mysqli_fetch_assoc($ret);
?>

<table>
<tr><td><b>Name:</b></td><td><?php echo $row['FirstName'].' '.$row['LastName']; ?></td></tr>
<tr><td><b>Phone:</b></td><td><?php echo $row['MobileNumber']; ?></td></tr>
<tr><td><b>Date:</b></td><td><?php echo $row['invoicedate']; ?></td></tr>
</table>

<br>

<table>
<tr>
<th>#</th>
<th>Service</th>
<th>Price</th>
</tr>

<?php
$ret2 = mysqli_query($con,"
SELECT tblservices.ServiceName, tblservices.Cost
FROM tblinvoice
JOIN tblservices ON tblservices.ID = tblinvoice.ServiceId
WHERE tblinvoice.BillingId = '$invid'
");

$cnt = 1;
$gtotal = 0;

while ($row2 = mysqli_fetch_assoc($ret2)) {

    $gtotal += $row2['Cost'];
?>

<tr>
<td><?php echo $cnt; ?></td>
<td><?php echo $row2['ServiceName']; ?></td>
<td>₱<?php echo $row2['Cost']; ?></td>
</tr>

<?php $cnt++; } ?>

<tr>
<td class="total">TOTAL</td>
<td class="total">₱<?php echo $gtotal; ?></td>
</tr>

</table>

<?php
} else {
    echo "<p style='text-align:center;color:red;'>Invoice not found</p>";
}
}
?>

<div style="text-align:center;">
    <p>Thank you for your visit!</p>
    <button class="print-btn" onclick="window.print()">🖨️ Print Invoice</button>
</div>

</div>

</body>
</html>