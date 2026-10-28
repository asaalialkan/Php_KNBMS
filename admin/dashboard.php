<?php
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

$currency = "₱";

/* ================= STATS ================= */
$totalcust        = mysqli_num_rows(mysqli_query($con,"SELECT * FROM tbluser"));
$totalappointment = mysqli_num_rows(mysqli_query($con,"SELECT * FROM tblbook"));
$totalaccapt      = mysqli_num_rows(mysqli_query($con,"SELECT * FROM tblbook WHERE Status='Selected'"));
$totalrejapt      = mysqli_num_rows(mysqli_query($con,"SELECT * FROM tblbook WHERE Status='Rejected'"));
$totalser         = mysqli_num_rows(mysqli_query($con,"SELECT * FROM tblservices"));

/* ================= TODAY SALES (Online + Walk-In) ================= */
$todysale = 0;
$q = mysqli_query($con,"SELECT tblservices.Cost FROM tblinvoice JOIN tblservices ON tblservices.ID=tblinvoice.ServiceId WHERE DATE(PostingDate)=CURDATE()");
while ($r = mysqli_fetch_array($q)) { $todysale += $r['Cost']; }
$q2 = mysqli_query($con,"SELECT amount FROM walkin_customers WHERE appointment_date=CURDATE() AND invoice_status='Accepted'");
while ($r2 = mysqli_fetch_array($q2)) { $todysale += $r2['amount']; }

/* ================= TOTAL SALES (Online + Walk-In) ================= */
$totalsale = 0;
$q3 = mysqli_query($con,"SELECT tblservices.Cost FROM tblinvoice JOIN tblservices ON tblservices.ID=tblinvoice.ServiceId");
while ($r3 = mysqli_fetch_array($q3)) { $totalsale += $r3['Cost']; }
$q4 = mysqli_query($con,"SELECT amount FROM walkin_customers WHERE invoice_status='Accepted'");
while ($r4 = mysqli_fetch_array($q4)) { $totalsale += $r4['amount']; }

/* ================= WALK-IN COUNT ================= */
$total_walkins = mysqli_num_rows(mysqli_query($con,"SELECT id FROM walkin_customers"));
$today_walkins = mysqli_num_rows(mysqli_query($con,"SELECT id FROM walkin_customers WHERE appointment_date=CURDATE()"));

/* ================= LAST 7 DAYS SALES ================= */
$last7_online = [];
$q5 = mysqli_query($con,"
    SELECT DATE(PostingDate) as d, SUM(tblservices.Cost) as total
    FROM tblinvoice
    JOIN tblservices ON tblservices.ID=tblinvoice.ServiceId
    WHERE PostingDate >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(PostingDate)
    ORDER BY d ASC
");
while ($r5 = mysqli_fetch_array($q5)) { $last7_online[$r5['d']] = (float)$r5['total']; }

$last7_walkin = [];
$q6 = mysqli_query($con,"
    SELECT appointment_date as d, SUM(amount) as total
    FROM walkin_customers
    WHERE invoice_status='Accepted'
    AND appointment_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY appointment_date
    ORDER BY d ASC
");
while ($r6 = mysqli_fetch_array($q6)) { $last7_walkin[$r6['d']] = (float)$r6['total']; }

$labels7 = []; $online7 = []; $walkin7 = [];
for ($i = 6; $i >= 0; $i--) {
    $d = date('Y-m-d', strtotime("-$i days"));
    $labels7[] = date('M d', strtotime($d));
    $online7[]  = $last7_online[$d] ?? 0;
    $walkin7[]  = $last7_walkin[$d] ?? 0;
}

/* ================= TOP SERVICES ================= */
$svc_map = [];
$qs1 = mysqli_query($con,"SELECT tblservices.ServiceName, COUNT(*) as cnt FROM tblinvoice JOIN tblservices ON tblservices.ID=tblinvoice.ServiceId GROUP BY tblservices.ServiceName");
while ($rs1 = mysqli_fetch_array($qs1)) { $svc_map[$rs1['ServiceName']] = ($svc_map[$rs1['ServiceName']] ?? 0) + (int)$rs1['cnt']; }
$qs2 = mysqli_query($con,"SELECT service, COUNT(*) as cnt FROM walkin_customers GROUP BY service");
while ($rs2 = mysqli_fetch_array($qs2)) { $svc_map[$rs2['service']] = ($svc_map[$rs2['service']] ?? 0) + (int)$rs2['cnt']; }
arsort($svc_map);
$top = array_slice($svc_map, 0, 6, true);
$top_services_labels = []; $top_services_data = [];
foreach ($top as $name => $cnt) { $top_services_labels[] = $name; $top_services_data[] = $cnt; }

/* ================= MONTHLY REVENUE ================= */
$monthly_online = array_fill(0, 12, 0);
$monthly_walkin = array_fill(0, 12, 0);
$qm1 = mysqli_query($con,"SELECT MONTH(PostingDate)-1 as m, SUM(tblservices.Cost) as total FROM tblinvoice JOIN tblservices ON tblservices.ID=tblinvoice.ServiceId WHERE YEAR(PostingDate)=YEAR(CURDATE()) GROUP BY m");
while ($rm1 = mysqli_fetch_array($qm1)) { $monthly_online[(int)$rm1['m']] = (float)$rm1['total']; }
$qm2 = mysqli_query($con,"SELECT MONTH(appointment_date)-1 as m, SUM(amount) as total FROM walkin_customers WHERE invoice_status='Accepted' AND YEAR(appointment_date)=YEAR(CURDATE()) GROUP BY m");
while ($rm2 = mysqli_fetch_array($qm2)) { $monthly_walkin[(int)$rm2['m']] = (float)$rm2['total']; }

/* ================= ONLINE vs WALK-IN RATIO ================= */
$online_count = mysqli_num_rows(mysqli_query($con,"SELECT BillingId FROM tblinvoice GROUP BY BillingId"));
$walkin_count = $total_walkins;

/* JSON for JS */
$js_labels7        = json_encode($labels7);
$js_online7        = json_encode($online7);
$js_walkin7        = json_encode($walkin7);
$js_svc_labels     = json_encode($top_services_labels);
$js_svc_data       = json_encode($top_services_data);
$js_monthly_online = json_encode($monthly_online);
$js_monthly_walkin = json_encode($monthly_walkin);
$js_online_count   = (int)$online_count;
$js_walkin_count   = (int)$walkin_count;
?>
<!DOCTYPE HTML>
<html>
<head>
<title>KNBMS | Admin Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body { background: #f4f6f9; }

.small-box {
    border-radius: 12px;
    position: relative;
    display: block;
    box-shadow: 10 6px 18px rgba(0,0,0,0.15);
    padding: 35px;
    color: #080808;
    transition: 0.3s;
    margin-bottom: 20px;
}
.small-box:hover { transform: translateY(-5px); }
.small-box .icon { position: absolute; top: 10px; right: 15px; font-size: 50px; opacity: 0.1; }
.small-box h3 { font-size: 28px; margin: 0; font-weight: bold; }
.small-box p { margin: 0; font-size: 14px; }
.small-box .sub-label { font-size: 11px; opacity: 0.75; margin-top: 4px; }

.bg-info      { background: #17a2b8; }
.bg-success   { background: #28a745; }
.bg-primary   { background: #007bff; }
.bg-warning   { background: #ffc107; color: #000; }
.bg-danger    { background: #dc3545; }
.bg-dark      { background: #f9fafc; }
.bg-secondary { background: #f3f3f3; }
.bg-teal      { background: #20c997; }
.bg-indigo    { background: #6610f2; }

.section-title {
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #888;
    margin: 10px 0 16px;
    padding-bottom: 8px;
    border-bottom: 1px solid #e0e0e0;
}

.chart-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.07);
    padding: 20px;
    margin-bottom: 20px;
}
.chart-card h5 {
    font-size: 13px;
    font-weight: 700;
    color: #555;
    margin: 0 0 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.chart-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    margin-bottom: 14px;
    font-size: 12px;
    color: #666;
}
.chart-legend span { display: flex; align-items: center; gap: 5px; }
.legend-dot { width: 10px; height: 10px; border-radius: 2px; display: inline-block; }
</style>
</head>

<body class="cbp-spmenu-push">
<div class="main-content">

<?php include_once('includes/sidebar.php'); ?>
<?php include_once('includes/header.php'); ?>

<div id="page-wrapper" class="row calender widget-shadow">
<div class="main-page">
<div class="container-fluid">

<!-- ================= HEADER SUMMARY ================= -->
<div class="dashboard-header">
    <div>
        <h2 style="margin:0;font-weight:800;">Dashboard Overview</h2>
        <p style="margin:5px 0;color:#64748b;">Business performance summary & analytics</p>
    </div>
    <div style="font-weight:600;color:#334155;">
        <?php echo date('F d, Y'); ?>
    </div>
</div>

<!-- ================= ROW 1 ================= -->
<div class="row">

    <div class="col-md-3">
        <div class="small-box bg-info">
            <h3><?php echo $totalcust; ?></h3>
            <p>Customers</p>
            <div class="icon"><i class="fa fa-users"></i></div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="small-box bg-success">
            <h3><?php echo $totalappointment; ?></h3>
            <p>Appointments</p>
            <div class="icon"><i class="fa fa-calendar-check"></i></div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="small-box bg-primary">
            <h3><?php echo $totalaccapt; ?></h3>
            <p>Accepted</p>
            <div class="icon"><i class="fa fa-circle-check"></i></div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="small-box bg-danger">
            <h3><?php echo $totalrejapt; ?></h3>
            <p>Rejected</p>
            <div class="icon"><i class="fa fa-circle-xmark"></i></div>
        </div>
    </div>

</div>

<!-- ================= ROW 2 ================= -->
<div class="row">

    <div class="col-md-4">
        <div class="small-box bg-warning">
            <h3><?php echo $totalser; ?></h3>
            <p>Services</p>
            <div class="icon"><i class="fa fa-wand-magic-sparkles"></i></div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="small-box bg-teal">
            <h3><?php echo $today_walkins; ?></h3>
            <p>Walk-Ins Today</p>
            <div class="icon"><i class="fa fa-person-walking"></i></div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="small-box bg-indigo">
            <h3><?php echo $total_walkins; ?></h3>
            <p>Total Walk-Ins</p>
            <div class="icon"><i class="fa fa-door-open"></i></div>
        </div>
    </div>

</div>

<!-- ================= ROW 3 ================= -->
<div class="row">

    <div class="col-md-6">
        <div class="small-box bg-dark">
            <h3><?php echo $currency . number_format($todysale, 2); ?></h3>
            <p>Today Sales</p>
            <div class="sub-label">Online + Walk-In (Accepted)</div>
            <div class="icon"><i class="fa fa-coins"></i></div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="small-box bg-secondary">
            <h3><?php echo $currency . number_format($totalsale, 2); ?></h3>
            <p>Total Sales</p>
            <div class="sub-label">Online + Walk-In (Accepted)</div>
            <div class="icon"><i class="fa fa-chart-line"></i></div>
        </div>
    </div>

</div>

<!-- ===== ANALYTICS ===== -->
<p class="section-title"><i class="fa fa-chart-bar" style="margin-right:6px;"></i>Analytics</p>

<!-- ROW A: 7-Day Line + Doughnut -->
<div class="row">
    <div class="col-md-8">
        <div class="chart-card">
            <h5>Sales — Last 7 Days</h5>
            <div class="chart-legend">
                <span><span class="legend-dot" style="background:#378ADD;"></span>Online</span>
                <span><span class="legend-dot" style="background:#1D9E75;border:1px dashed #1D9E75;"></span>Walk-In</span>
            </div>
            <div style="position:relative;width:100%;height:250px;">
                <canvas id="chart7days" role="img" aria-label="Line chart of online and walk-in sales over the last 7 days">Sales last 7 days.</canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="chart-card">
            <h5>Online vs Walk-In</h5>
            <div class="chart-legend">
                <span><span class="legend-dot" style="background:#378ADD;"></span>Online (<?php echo $js_online_count; ?>)</span>
                <span><span class="legend-dot" style="background:#1D9E75;"></span>Walk-In (<?php echo $js_walkin_count; ?>)</span>
            </div>
            <div style="position:relative;width:100%;height:250px;">
                <canvas id="chartRatio" role="img" aria-label="Doughnut showing online vs walk-in ratio">Online: <?php echo $js_online_count; ?>, Walk-In: <?php echo $js_walkin_count; ?>.</canvas>
            </div>
        </div>
    </div>
</div>

<!-- ROW B: Monthly Revenue + Top Services -->
<div class="row">
    <div class="col-md-7">
        <div class="chart-card">
            <h5>Monthly Revenue — <?php echo date('Y'); ?></h5>
            <div class="chart-legend">
                <span><span class="legend-dot" style="background:#378ADD;"></span>Online</span>
                <span><span class="legend-dot" style="background:#1D9E75;"></span>Walk-In</span>
            </div>
            <div style="position:relative;width:100%;height:270px;">
                <canvas id="chartMonthly" role="img" aria-label="Monthly revenue bar chart for <?php echo date('Y'); ?>">Monthly revenue <?php echo date('Y'); ?>.</canvas>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="chart-card">
            <h5>Top Services</h5>
            <div style="position:relative;width:100%;height:<?php echo max(220, count($top_services_labels) * 44 + 60); ?>px;">
                <canvas id="chartServices" role="img" aria-label="Horizontal bar chart of most booked services">Top services by booking count.</canvas>
            </div>
        </div>
    </div>
</div>

</div>
</div>
</div>

<?php include_once('includes/footer.php'); ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
const labels7        = <?php echo $js_labels7; ?>;
const online7        = <?php echo $js_online7; ?>;
const walkin7        = <?php echo $js_walkin7; ?>;
const svcLabels      = <?php echo $js_svc_labels; ?>;
const svcData        = <?php echo $js_svc_data; ?>;
const monthlyOnline  = <?php echo $js_monthly_online; ?>;
const monthlyWalkin  = <?php echo $js_monthly_walkin; ?>;
const onlineCount    = <?php echo $js_online_count; ?>;
const walkinCount    = <?php echo $js_walkin_count; ?>;

const MONTHS = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const BLUE   = '#378ADD';
const TEAL   = '#1D9E75';
const BLUE_L = 'rgba(55,138,221,0.12)';
const TEAL_L = 'rgba(29,158,117,0.12)';

const pesoFmt = v => '₱' + parseFloat(v).toLocaleString('en-PH', {minimumFractionDigits:2, maximumFractionDigits:2});

/* Last 7 Days Line */
new Chart(document.getElementById('chart7days'), {
    type: 'line',
    data: {
        labels: labels7,
        datasets: [
            { label:'Online', data:online7, borderColor:BLUE, backgroundColor:BLUE_L, borderWidth:2, pointRadius:4, pointBackgroundColor:BLUE, fill:true, tension:0.4 },
            { label:'Walk-In', data:walkin7, borderColor:TEAL, backgroundColor:TEAL_L, borderWidth:2, pointRadius:4, pointBackgroundColor:TEAL, pointStyle:'rectRot', fill:true, tension:0.4, borderDash:[5,3] }
        ]
    },
    options: {
        responsive:true, maintainAspectRatio:false,
        plugins: { legend:{display:false}, tooltip:{ callbacks:{ label: ctx => ' ' + pesoFmt(ctx.parsed.y) } } },
        scales: {
            x: { grid:{color:'rgba(0,0,0,0.04)'}, ticks:{font:{size:11}} },
            y: { grid:{color:'rgba(0,0,0,0.04)'}, ticks:{font:{size:11}, callback: v => '₱'+v.toLocaleString() } }
        }
    }
});

/* Doughnut */
new Chart(document.getElementById('chartRatio'), {
    type: 'doughnut',
    data: {
        labels: ['Online','Walk-In'],
        datasets: [{ data:[onlineCount, walkinCount], backgroundColor:[BLUE, TEAL], borderWidth:0, hoverOffset:8 }]
    },
    options: {
        responsive:true, maintainAspectRatio:false, cutout:'65%',
        plugins: { legend:{display:false}, tooltip:{ callbacks:{ label: ctx => ' '+ctx.label+': '+ctx.parsed+' invoices' } } }
    }
});

/* Monthly Bar */
new Chart(document.getElementById('chartMonthly'), {
    type: 'bar',
    data: {
        labels: MONTHS,
        datasets: [
            { label:'Online', data:monthlyOnline, backgroundColor:BLUE, borderRadius:4, borderSkipped:false },
            { label:'Walk-In', data:monthlyWalkin, backgroundColor:TEAL, borderRadius:4, borderSkipped:false }
        ]
    },
    options: {
        responsive:true, maintainAspectRatio:false,
        plugins: { legend:{display:false}, tooltip:{ callbacks:{ label: ctx => ' '+pesoFmt(ctx.parsed.y) } } },
        scales: {
            x: { grid:{display:false}, ticks:{font:{size:11}, autoSkip:false, maxRotation:0} },
            y: { grid:{color:'rgba(0,0,0,0.04)'}, ticks:{font:{size:11}, callback: v => '₱'+v.toLocaleString()} }
        }
    }
});

/* Top Services Horizontal Bar */
new Chart(document.getElementById('chartServices'), {
    type: 'bar',
    data: {
        labels: svcLabels,
        datasets: [{ label:'Bookings', data:svcData, backgroundColor:['#378ADD','#1D9E75','#BA7517','#D4537E','#7F77DD','#D85A30'], borderRadius:4, borderSkipped:false }]
    },
    options: {
        indexAxis:'y', responsive:true, maintainAspectRatio:false,
        plugins: { legend:{display:false}, tooltip:{ callbacks:{ label: ctx => ' '+ctx.parsed.x+' bookings' } } },
        scales: {
            x: { grid:{color:'rgba(0,0,0,0.04)'}, ticks:{font:{size:11}, stepSize:1} },
            y: { grid:{display:false}, ticks:{font:{size:11}} }
        }
    }
});
</script>
</body>
</html>