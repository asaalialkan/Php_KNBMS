<?php
session_start();
include('includes/dbconnection.php');

if (!isset($_SESSION['bpmsaid']) || empty($_SESSION['bpmsaid'])) {
    header('location:logout.php');
    exit();
}

$cid = intval($_GET['viewid']);

if (isset($_POST['submit'])) {

    $remark = mysqli_real_escape_string($con, $_POST['remark']);
    $status = mysqli_real_escape_string($con, $_POST['status']);
    $paymentstatus = mysqli_real_escape_string($con, $_POST['paymentstatus']);

    $query = mysqli_query($con, "
        UPDATE tblbook
        SET Remark='$remark',
            Status='$status',
            PaymentStatus='$paymentstatus',
            RemarkDate=NOW()
        WHERE ID='$cid'
    ");

    if ($query) {
        echo "<script>alert('Appointment updated successfully');</script>";
        echo "<script>window.location.href='view-appointment.php?viewid=$cid';</script>";
        exit();
    } else {
        echo "<script>alert('Something went wrong');</script>";
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
<title>BPMS || View Appointment</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">

<style>
body { background:#f4f6f9; margin:0; }

#page-wrapper {
	margin-left:250px;
	padding:100px;
}

.bpms-card {
	background:#fff;
	padding:1px;
	border-radius:3px;
}

.bpms-title {
	font-size:22px;
	font-weight:bold;
	margin-bottom:15px;
}

.table th {
	background:#f8f9fa;
}

.status {
	padding:5px 10px;
	border-radius:6px;
	color:#fff;
	font-size:12px;
}

.pending { background:#f0ad4e; }
.approved { background:#28a745; }
.rejected { background:#dc3545; }
</style>
</head>

<body>

<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>

<div id="page-wrapper">
<div class="bpms-card">

<h3 class="bpms-title">Appointment Details</h3>

<?php
$ret = mysqli_query($con, "
SELECT 
	tbluser.FirstName,
	tbluser.LastName,
	tbluser.Email,
	tbluser.MobileNumber,
	tblbook.*,
	tblservices.ServiceName,
	tblbarbers.Name AS BarberName
FROM tblbook
JOIN tbluser ON tbluser.ID = tblbook.UserID
LEFT JOIN tblservices ON tblservices.ID = tblbook.ServiceId
LEFT JOIN tblbarbers ON tblbarbers.ID = tblbook.BarberID
WHERE tblbook.ID='$cid'
");

$row = mysqli_fetch_array($ret);
?>

<?php if ($row) { ?>

<table class="table table-bordered">

	<tr>
		<th>Appointment No</th>
		<td><?php echo $row['AptNumber']; ?></td>
	</tr>

	<tr>
		<th>Name</th>
		<td><?php echo $row['FirstName'] . " " . $row['LastName']; ?></td>
	</tr>

	<tr>
		<th>Email</th>
		<td><?php echo $row['Email']; ?></td>
	</tr>

	<tr>
		<th>Mobile</th>
		<td><?php echo $row['MobileNumber']; ?></td>
	</tr>

	<tr>
		<th>Service</th>
		<td><?php echo $row['ServiceName']; ?></td>
	</tr>

	<tr>
		<th>Barber</th>
		<td>
			<?php
			if (!empty($row['BarberName'])) {
				echo htmlspecialchars($row['BarberName']);
			} else {
				echo "<span style='color:red;'>Not Assigned</span>";
			}
			?>
		</td>
	</tr>
<tr>
    <th>Payment Method</th>
    <td>
        <?php
        echo !empty($row['PaymentMethod'])
            ? htmlspecialchars($row['PaymentMethod'])
            : '<span style="color:red;">Not Provided</span>';
        ?>
    </td>
</tr>

<tr>
    <th>Payment Status</th>
    <td>
        <?php
        if ($row['PaymentStatus'] == "Verified") {
            echo "<span class='status approved'>Verified</span>";
        } elseif ($row['PaymentStatus'] == "Rejected") {
            echo "<span class='status rejected'>Rejected</span>";
        } else {
            echo "<span class='status pending'>Pending Verification</span>";
        }
        ?>
    </td>
</tr>
<tr>
    <th>Payment Proof</th>
    <td>
        <?php if (!empty($row['PaymentProof'])) {
            $proofFile = htmlspecialchars($row['PaymentProof']);
            $ext = strtolower(pathinfo($row['PaymentProof'], PATHINFO_EXTENSION));
            $filePath = '../uploads/payments/' . $proofFile;
            $serverPath = dirname(__DIR__) . '/uploads/payments/' . basename($row['PaymentProof']);
        ?>

            <a href="<?php echo $filePath; ?>" target="_blank" class="btn btn-info btn-sm">
                View Receipt
            </a>

            <br><br>

            <?php if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) { ?>

                <?php if (file_exists($serverPath)) { ?>
                    <img src="<?php echo $filePath; ?>"
                         style="max-width:300px; border:1px solid #ddd; padding:5px; display:block;">
                <?php } else { ?>
                    <span style="color:red;">
                        Image not found on server.<br>
                        <small>Expected at: <?php echo $serverPath; ?></small>
                    </span>
                <?php } ?>

            <?php } elseif ($ext === 'pdf') { ?>
                <a href="<?php echo $filePath; ?>" target="_blank" class="btn btn-default btn-sm">
                    <i class="fa fa-file-pdf-o"></i> Open PDF Receipt
                </a>
            <?php } ?>

        <?php } else { ?>
            <span style="color:red;">No attachment uploaded</span>
        <?php } ?>
    </td>
</tr>
	<tr>
		<th>Date</th>
		<td><?php echo $row['AptDate']; ?></td>
	</tr>

	<tr>
		<th>Time</th>
		<td><?php echo date("h:i A", strtotime($row['AptTime'])); ?></td>
	</tr>

	<tr>
		<th>Status</th>
		<td>
			<?php
			if ($row['Status'] == "") {
				echo "<span class='status pending'>Not Updated</span>";
			} elseif ($row['Status'] == "Selected") {
				echo "<span class='status approved'>Approved</span>";
			} else {
				echo "<span class='status rejected'>Rejected</span>";
			}
			?>
		</td>
	</tr>

</table>

<?php if ($row['Status'] == "") { ?>



<form method="post">

<div class="form-group">
    <label>Remark</label>
    <textarea name="remark" class="form-control" required></textarea>
</div>

<div class="form-group">
    <label>Appointment Status</label>
    <select name="status" class="form-control" required>
        <option value="">Select</option>
        <option value="Selected">Approve Appointment</option>
        <option value="Rejected">Reject Appointment</option>
    </select>
</div>

<div class="form-group">
    <label>Payment Status</label>
    <select name="paymentstatus" class="form-control" required>
        <option value="">Select</option>
        <option value="Verified">Payment Verified</option>
        <option value="Rejected">Payment Rejected</option>
    </select>
</div>

<button type="submit" name="submit" class="btn btn-primary">
    Confirm
</button>

</form>

<?php } else { ?>

<hr>

<h4>Admin Response</h4>

<table class="table table-bordered">

	<tr>
		<th>Remark</th>
		<td><?php echo $row['Remark']; ?></td>
	</tr>

	<tr>
		<th>Status</th>
		<td><?php echo $row['Status']; ?></td>
	</tr>

	<tr>
		<th>Remark Date</th>
		<td><?php echo date("M d, Y h:i A", strtotime($row['RemarkDate'])); ?></td>
	</tr>

</table>

<?php } ?>

<?php } ?>

</div>
</div>

<?php include('includes/footer.php'); ?>

<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/bootstrap.js"></script>

</body>
</html>