<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

$fdate  = mysqli_real_escape_string($con, $_POST['fromdate']);
$tdate  = mysqli_real_escape_string($con, $_POST['todate']);
$source = $_POST['source'] ?? 'all';

$source_labels = [
    'all'    => 'All (Online + Walk-In)',
    'online' => 'Online Only',
    'walkin' => 'Walk-In Only'
];
$source_label = $source_labels[$source];

/* =====================================================
   FETCH ONLINE INVOICES
   ===================================================== */
$online_rows = [];

if ($source === 'all' || $source === 'online') {
    $q = mysqli_query($con,"
        SELECT DISTINCT
            tblinvoice.BillingId        AS invoice_id,
            CONCAT(tbluser.FirstName,' ',tbluser.LastName) AS customer,
            tblinvoice.PostingDate      AS invoice_date,
            'Online'                    AS type
        FROM tblinvoice
        JOIN tbluser ON tbluser.ID = tblinvoice.Userid
        WHERE DATE(tblinvoice.PostingDate) BETWEEN '$fdate' AND '$tdate'
        ORDER BY tblinvoice.PostingDate DESC
    ");
    while ($r = mysqli_fetch_assoc($q)) {
        $online_rows[] = $r;
    }
}

/* =====================================================
   FETCH WALK-IN INVOICES
   ===================================================== */
$walkin_rows = [];

if ($source === 'all' || $source === 'walkin') {
    $q = mysqli_query($con,"
        SELECT
            id                  AS raw_id,
            customer_name       AS customer,
            appointment_date    AS invoice_date,
            'Walk-In'           AS type
        FROM walkin_customers
        WHERE appointment_date BETWEEN '$fdate' AND '$tdate'
        ORDER BY appointment_date DESC
    ");
    while ($r = mysqli_fetch_assoc($q)) {
        $r['invoice_id'] = 'WLK-' . str_pad($r['raw_id'], 4, '0', STR_PAD_LEFT);
        $walkin_rows[] = $r;
    }
}

/* =====================================================
   MERGE + SORT BY DATE DESC
   ===================================================== */
$all_rows = array_merge($online_rows, $walkin_rows);

usort($all_rows, function($a, $b) {
    return strtotime($b['invoice_date']) - strtotime($a['invoice_date']);
});

$total_online = count($online_rows);
$total_walkin = count($walkin_rows);
$total_all    = count($all_rows);
?>
<!DOCTYPE HTML>
<html>
<head>
<title>BPMS | Between Dates Reports</title>

<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body { background: #f4f6f9; }

.card-box {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    margin-bottom: 20px;
}

.table thead th { background: #343a40; color: #fff; }

.report-title { font-weight: 600; margin-bottom: 10px; }

.badge-date {
    background: #17a2b8;
    color: #fff;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 13px;
}

.badge-online {
    background: #378ADD;
    color: #fff;
    padding: 3px 9px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 600;
}

.badge-walkin {
    background: #1D9E75;
    color: #fff;
    padding: 3px 9px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 600;
}

.btn-back {
    background: #343a40;
    color: #fff;
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
    display: inline-block;
    margin-bottom: 16px;
}

.btn-back:hover { background: #23272b; color: #fff; text-decoration: none; }

.summary-cards {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.summary-card {
    flex: 1;
    min-width: 140px;
    background: #fff;
    border-radius: 10px;
    padding: 14px 18px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.07);
    border-left: 4px solid #ccc;
}

.summary-card.online   { border-left-color: #378ADD; }
.summary-card.walkin   { border-left-color: #1D9E75; }
.summary-card.combined { border-left-color: #343a40; }

.summary-card .s-label  { font-size: 12px; color: #888; text-transform: uppercase; letter-spacing: 0.5px; }
.summary-card .s-count  { font-size: 26px; font-weight: 700; color: #333; margin-top: 2px; }
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

    <a href="bwdates-reports-ds.php" class="btn-back">
        <i class="fa fa-arrow-left"></i> Back to Reports
    </a>

    <div class="card-box">

        <h3><i class="fa-solid fa-calendar-days"></i> Between Dates Reports</h3>
        <hr>

        <h4 class="report-title">
            Report:
            <span class="badge-date"><?php echo $fdate; ?></span>
            &nbsp;to&nbsp;
            <span class="badge-date"><?php echo $tdate; ?></span>
            &nbsp;&nbsp;
            <small style="font-size:13px; color:#666;"><?php echo $source_label; ?></small>
        </h4>

        <!-- SUMMARY CARDS -->
        <div class="summary-cards">
            <?php if ($source === 'all' || $source === 'online'): ?>
            <div class="summary-card online">
                <div class="s-label">Online Invoices</div>
                <div class="s-count"><?php echo $total_online; ?></div>
            </div>
            <?php endif; ?>

            <?php if ($source === 'all' || $source === 'walkin'): ?>
            <div class="summary-card walkin">
                <div class="s-label">Walk-In Invoices</div>
                <div class="s-count"><?php echo $total_walkin; ?></div>
            </div>
            <?php endif; ?>

            <?php if ($source === 'all'): ?>
            <div class="summary-card combined">
                <div class="s-label">Total Invoices</div>
                <div class="s-count"><?php echo $total_all; ?></div>
            </div>
            <?php endif; ?>
        </div>

        <!-- TABLE -->
        <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Invoice ID</th>
                    <th>Customer Name</th>
                    <th>Invoice Date</th>
                    <?php if ($source === 'all'): ?>
                    <th>Type</th>
                    <?php endif; ?>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            <?php if (empty($all_rows)): ?>
            <tr>
                <td colspan="<?php echo $source === 'all' ? 6 : 5; ?>" style="text-align:center; color:#888; padding:20px;">
                    No records found for the selected date range.
                </td>
            </tr>
            <?php endif; ?>

            <?php $cnt = 1; foreach ($all_rows as $row): ?>
            <tr>
                <td><?php echo $cnt++; ?></td>
                <td><?php echo htmlspecialchars($row['invoice_id']); ?></td>
                <td><?php echo htmlspecialchars($row['customer']); ?></td>
                <td><?php echo date('Y-m-d', strtotime($row['invoice_date'])); ?></td>

                <?php if ($source === 'all'): ?>
                <td>
                    <?php if ($row['type'] === 'Online'): ?>
                        <span class="badge-online">Online</span>
                    <?php else: ?>
                        <span class="badge-walkin">Walk-In</span>
                    <?php endif; ?>
                </td>
                <?php endif; ?>

                <td>
                    <?php if ($row['type'] === 'Online'): ?>
                    <a href="view-invoice.php?invoiceid=<?php echo $row['invoice_id']; ?>&type=Online"
                       class="btn btn-primary btn-sm">
                        <i class="fa fa-eye"></i> View
                    </a>
                    <?php else: ?>
                    <a href="view-invoice.php?invoiceid=<?php echo $row['raw_id']; ?>&type=Walk-in"
                       class="btn btn-success btn-sm">
                        <i class="fa fa-eye"></i> View
                    </a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>

            </tbody>
        </table>
        </div>

    </div>

</div>
</div>
</div>
</div>

<?php include_once('includes/footer.php'); ?>

</div>
</body>
</html>