<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

$msg = "";
?>
<!DOCTYPE HTML>
<html>
<head>
<title>BPMS|Sales Reports</title>

<link href="css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="css/style.css" rel="stylesheet" type="text/css">
<link href="css/font-awesome.css" rel="stylesheet">
<link href="css/animate.css" rel="stylesheet">
<script src="js/wow.min.js"></script>
<script> new WOW().init(); </script>

<style>
body { 
    background: #f4f6f9; 
}

/* CENTER CONTAINER */
#page-wrapper .main-page {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 30vh;
    padding: 50px 35px;
}

/* FORM CARD */
.card-box {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);

    width: 100%;
    max-width: 600px;  /* controls length/width */
}

/* TITLE */
.form-title h4 { 
    margin: 0; 
    font-weight: 600; 
    text-align: center;
}

/* FORM LABEL */
.form-group label { 
    font-weight: 600; 
}

/* BUTTON */
.btn-submit {
    background: #28a745;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    width: 100%;
}

.btn-submit:hover { 
    background: #218838; 
}
</style>

</head>

<body class="cbp-spmenu-push">
<div class="main-content">

<?php include_once('includes/sidebar.php'); ?>
<?php include_once('includes/header.php'); ?>

<div id="page-wrapper">
<div class="main-page">

<div class="card-box">

    <div class="form-title">
        <h4><i class="fa fa-chart-line"></i> Generate Sales Report</h4>
    </div>

    <br>

    <form method="post" action="sales-reports-detail.php">

        <p style="font-size:16px; color:red; text-align:center;">
            <?php if ($msg) { echo $msg; } ?>
        </p>

        <div class="form-group">
            <label>From Date</label>
            <input type="date" class="form-control" name="fromdate" required>
        </div>

        <div class="form-group">
            <label>To Date</label>
            <input type="date" class="form-control" name="todate" required>
        </div>

        <div class="form-group">
            <label>Request Type</label><br>
            <label><input type="radio" name="requesttype" value="mtwise" checked> Month wise</label>
            &nbsp;&nbsp;
            <label><input type="radio" name="requesttype" value="yrwise"> Year wise</label>
        </div>

        <div class="form-group">
            <label>Source</label><br>
            <label><input type="radio" name="source" value="all" checked> All (Online + Walk-In)</label>
            &nbsp;&nbsp;
            <label><input type="radio" name="source" value="online"> Online Only</label>
            &nbsp;&nbsp;
            <label><input type="radio" name="source" value="walkin"> Walk-In Only</label>
        </div>

        <button type="submit" name="submit" class="btn-submit">
            Submit Report
        </button>

    </form>

</div>

</div>
</div>

<?php include_once('includes/footer.php'); ?>

</div>

<script src="js/classie.js"></script>
<script>
var menuLeft      = document.getElementById('cbp-spmenu-s1'),
    showLeftPush  = document.getElementById('showLeftPush'),
    body          = document.body;

showLeftPush.onclick = function () {
    classie.toggle(this, 'active');
    classie.toggle(body, 'cbp-spmenu-push-toright');
    classie.toggle(menuLeft, 'cbp-spmenu-open');
    disableOther('showLeftPush');
};

function disableOther(button) {
    if (button !== 'showLeftPush') {
        classie.toggle(showLeftPush, 'disabled');
    }
}
</script>

<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>
<script src="js/bootstrap.js"></script>

</body>
</html>