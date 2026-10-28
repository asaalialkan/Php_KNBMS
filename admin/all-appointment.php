<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

/* ── HANDLE REMARKS ONLY (status unchanged) ── */
if (isset($_POST['toggle_done'])) {
    $id          = intval($_POST['record_id']);
    $type        = $_POST['record_type'];
    $autoRemarks = mysqli_real_escape_string($con, $_POST['new_remarks']);

    if ($type === 'Walk-in') {
        mysqli_query($con,
            "UPDATE walkin_customers
             SET remarks='$autoRemarks'
             WHERE id=$id"
        );
    } else {
        mysqli_query($con,
            "UPDATE tblbook
             SET remarks='$autoRemarks'
             WHERE ID=$id"
        );
    }

    header('location:' . $_SERVER['PHP_SELF']);
    exit();
}
?>
<!DOCTYPE HTML>
<html>
<head>
<title>All Appointments</title>

<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
.walkin-badge {
    background: #17a2b8;
    color: #fff;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
}
.online-badge {
    background: #28a745;
    color: #fff;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
}
.done-badge {
    background: #6f42c1;
    color: #fff;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    display: inline-block;
}
.remarks-done {
    font-size: 12px;
    color: #6f42c1;
    font-weight: 600;
}
.remarks-pending {
    font-size: 12px;
    color: #e67e22;
    font-weight: 600;
}
.remarks-none {
    font-size: 12px;
    color: #bbb;
    font-style: italic;
}

/* Sales summary bar */
.summary-bar {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 18px;
}
.summary-card {
    flex: 1;
    min-width: 130px;
    padding: 14px 18px;
    border-radius: 12px;
    color: #fff;
    font-size: 13px;
}
.summary-card h4 {
    margin: 4px 0 0;
    font-size: 22px;
    font-weight: 700;
}
.sc-total   { background: #343a40; }
.sc-done    { background: #6f42c1; }
.sc-pending { background: #e67e22; }
.sc-walkin  { background: #17a2b8; }
.sc-online  { background: #28a745; }
</style>
</head>
<body class="cbp-spmenu-push">

<div class="main-content">
<?php include_once('includes/sidebar.php'); ?>
<?php include_once('includes/header.php'); ?>

<div id="page-wrapper">
<div class="main-page">

<div class="tables">
<div class="table-responsive bs-example widget-shadow">

<h4>
    <i class="fa fa-calendar"></i> All Appointments (Online + Walk-in)
</h4>

<?php
/* ── SALES MONITORING SUMMARY ── */
$r_total = mysqli_fetch_assoc(mysqli_query($con,
    "SELECT
        (SELECT COUNT(*) FROM tblbook) +
        (SELECT COUNT(*) FROM walkin_customers) AS total"
));

$r_done = mysqli_fetch_assoc(mysqli_query($con,
    "SELECT
        (SELECT COUNT(*) FROM tblbook          WHERE remarks='Done') +
        (SELECT COUNT(*) FROM walkin_customers WHERE remarks='Done') AS done_count"
));

$r_pending = mysqli_fetch_assoc(mysqli_query($con,
    "SELECT
        (SELECT COUNT(*) FROM tblbook          WHERE remarks!='Done' OR remarks IS NULL) +
        (SELECT COUNT(*) FROM walkin_customers WHERE remarks!='Done' OR remarks IS NULL) AS pending_count"
));

$r_walkin = mysqli_fetch_assoc(mysqli_query($con,
    "SELECT COUNT(*) AS walkin_count FROM walkin_customers"
));

$r_online = mysqli_fetch_assoc(mysqli_query($con,
    "SELECT COUNT(*) AS online_count FROM tblbook"
));
?>

<div class="summary-bar">
    <div class="summary-card sc-total">
        <span><i class="fa fa-list"></i> Total Appointments</span>
        <h4><?php echo $r_total['total']; ?></h4>
    </div>
    <div class="summary-card sc-done">
        <span><i class="fa fa-check-circle"></i> Done</span>
        <h4><?php echo $r_done['done_count']; ?></h4>
    </div>
    <div class="summary-card sc-pending">
        <span><i class="fa fa-clock-o"></i> Not Yet Done</span>
        <h4><?php echo $r_pending['pending_count']; ?></h4>
    </div>
    <div class="summary-card sc-walkin">
        <span><i class="fa fa-user"></i> Walk-in</span>
        <h4><?php echo $r_walkin['walkin_count']; ?></h4>
    </div>
    <div class="summary-card sc-online">
        <span><i class="fa fa-globe"></i> Online</span>
        <h4><?php echo $r_online['online_count']; ?></h4>
    </div>
</div>

<table class="table table-bordered">
<thead style="background:#343a40;color:#fff;">
<tr>
    <th>#</th>
    <th>Appointment No</th>
    <th>Name</th>
    <th>Mobile</th>
    <th>Service</th>
    <th>Date</th>
    <th>Time</th>
    <th>Type</th>
    <th>Status</th>
    <th>Remarks</th>
    <th>Action</th>
</tr>
</thead>
<tbody>

<?php
$query = mysqli_query($con,"
(
    SELECT
        tblbook.ID,
        tblbook.AptNumber   AS aptno,
        CONCAT(tbluser.FirstName,' ',tbluser.LastName) AS cname,
        tbluser.MobileNumber AS mobile,
        tblservices.ServiceName AS service,
        tblbook.AptDate     AS adate,
        tblbook.AptTime     AS atime,
        tblbook.Status      AS status,
        COALESCE(tblbook.remarks,'') AS remarks,
        'Online'            AS type
    FROM tblbook
    LEFT JOIN tbluser     ON tbluser.ID     = tblbook.UserID
    LEFT JOIN tblservices ON tblservices.ID = tblbook.ServiceID
)
UNION ALL
(
    SELECT
        walkin_customers.id,
        walkin_customers.invoice_no        AS aptno,
        walkin_customers.customer_name     AS cname,
        walkin_customers.phone             AS mobile,
        walkin_customers.service,
        walkin_customers.appointment_date  AS adate,
        walkin_customers.appointment_time  AS atime,
        walkin_customers.invoice_status    AS status,
        COALESCE(walkin_customers.remarks,'') AS remarks,
        'Walk-in'                          AS type
    FROM walkin_customers
)
ORDER BY adate DESC, atime DESC
");

$cnt = 1;
while ($row = mysqli_fetch_array($query)):

    // Done/Not Done based on remarks only — status is never touched
    $currentRemarks = $row['remarks'];
    $isDone         = ($currentRemarks === 'Done');
    $newRemarks     = $isDone ? 'Pending' : 'Done';
    $btnLabel       = $isDone
        ? '<i class="fa fa-rotate-left"></i> Not Done'
        : '<i class="fa fa-check"></i> Done';
    $btnClass       = $isDone ? 'btn-warning' : 'btn-success';
?>
<tr>

    <!-- # -->
    <td><?php echo $cnt; ?></td>

    <!-- Apt No -->
    <td>
        <?php
        if ($row['type'] === 'Walk-in') {
            echo '<span style="color:#999;">N/A</span>';
        } else {
            echo htmlspecialchars($row['aptno']);
        }
        ?>
    </td>

    <!-- Name -->
    <td><?php echo htmlspecialchars($row['cname']); ?></td>

    <!-- Mobile -->
    <td><?php echo htmlspecialchars($row['mobile']); ?></td>

    <!-- Service -->
    <td><?php echo htmlspecialchars($row['service']); ?></td>

    <!-- Date -->
    <td><?php echo htmlspecialchars($row['adate']); ?></td>

    <!-- Time -->
    <td><?php echo date('h:i A', strtotime($row['atime'])); ?></td>

    <!-- Type -->
    <td>
        <?php if ($row['type'] === 'Walk-in'): ?>
            <span class="walkin-badge">Walk-in</span>
        <?php else: ?>
            <span class="online-badge">Online</span>
        <?php endif; ?>
    </td>

    <!-- Status (never changed by this page) -->
    <td>
        <?php
        $status = $row['status'];
        if ($status === 'Pending') {
            echo '<span class="btn btn-warning btn-xs">Pending</span>';
        } elseif (in_array($status, ['Accepted', 'Selected'])) {
            echo '<span class="btn btn-success btn-xs">Accepted</span>';
        } elseif ($status === 'Paid') {
            echo '<span class="btn btn-primary btn-xs">Paid</span>';
        } else {
            echo '<span class="btn btn-default btn-xs">'
                 . htmlspecialchars($status)
                 . '</span>';
        }
        ?>
    </td>

    <!-- Remarks (auto: Done or Pending) -->
    <td>
        <?php
        if ($isDone) {
            echo '<span class="remarks-done">'
                 . '<i class="fa fa-check-circle"></i> Done'
                 . '</span>';
        } elseif ($currentRemarks === 'Pending') {
            echo '<span class="remarks-pending">'
                 . '<i class="fa fa-clock-o"></i> Pending'
                 . '</span>';
        } else {
            echo '<span class="remarks-none">—</span>';
        }
        ?>
    </td>

    <!-- Action -->
    <td style="white-space:nowrap;">

        <form method="POST" style="display:inline-block;">
            <input type="hidden" name="record_id"   value="<?php echo $row['ID']; ?>">
            <input type="hidden" name="record_type" value="<?php echo $row['type']; ?>">
            <input type="hidden" name="new_remarks" value="<?php echo $newRemarks; ?>">
            <button type="submit" name="toggle_done"
                    class="btn <?php echo $btnClass; ?> btn-sm"
                    onclick="return confirm('<?php echo $isDone
                        ? 'Mark as Not Done?'
                        : 'Mark as Done?'; ?>')">
                <?php echo $btnLabel; ?>
            </button>
        </form>

        <!-- View button (Online only) -->
        <?php if ($row['type'] === 'Online'): ?>
            <a href="view-appointment.php?viewid=<?php echo $row['ID']; ?>"
               class="btn btn-primary btn-sm">
                <i class="fa fa-eye"></i>
            </a>
        <?php endif; ?>

    </td>

</tr>

<?php
    $cnt++;
endwhile;
?>

</tbody>
</table>

</div>
</div>
</div>
</div>

<?php include_once('includes/footer.php'); ?>
</div>

<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/bootstrap.js"></script>
</body>
</html>