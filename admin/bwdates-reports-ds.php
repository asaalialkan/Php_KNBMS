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
<title>BPMS | Between Dates Reports</title>

<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body{
    background:#f4f6f9;
}

/* CENTER PAGE */
#page-wrapper .container-fluid{
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:80vh;
}

/* CARD */
.card-box{
    background:#fff;
    width:100%;
    max-width:650px;
    padding:30px;
    border-radius:16px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
}

/* TITLE */
.card-box h3{
    font-size:22px;
    font-weight:600;
    display:flex;
    align-items:center;
    gap:10px;
    margin-bottom:10px;
}

.title-icon{
    font-size:20px;
}

/* FORM */
.form-group label{
    font-weight:600;
    margin-bottom:6px;
    display:block;
}

.form-control1{
    width:100%;
    padding:8px;
    border:1px solid #ddd;
    border-radius:6px;
}

/* RADIO */
.form-group input[type="radio"]{
    margin-right:5px;
}

/* BUTTON */
.btn-submit{
    width:100%;
    background:#343a40;
    color:#fff;
    padding:12px;
    border:none;
    border-radius:8px;
    font-size:15px;
    display:flex;
    justify-content:center;
    align-items:center;
    gap:8px;
}

.btn-submit:hover{
    background:#23272b;
}
</style>

</head>

<body class="cbp-spmenu-push">
<div class="main-content">

<?php include_once('includes/sidebar.php'); ?>
<?php include_once('includes/header.php'); ?>

<div id="page-wrapper">
<div class="container-fluid">

<div class="card-box">

    <h3>
        <i class="fa-solid fa-calendar-days title-icon"></i>
        Between Dates Reports
    </h3>

    <hr>

    <form method="post" action="bwdates-reports-details.php">

        <p style="color:red; text-align:center;">
            <?php if ($msg) { echo $msg; } ?>
        </p>

        <div class="form-group">
            <label>From Date</label>
            <input type="date" name="fromdate" class="form-control1" required>
        </div>

        <div class="form-group">
            <label>To Date</label>
            <input type="date" name="todate" class="form-control1" required>
        </div>

        <div class="form-group">
            <label>Source</label><br>

            <label><input type="radio" name="source" value="all" checked> All (Online + Walk-In)</label><br>
            <label><input type="radio" name="source" value="online"> Online Only</label><br>
            <label><input type="radio" name="source" value="walkin"> Walk-In Only</label>

        </div>

        <br>

        <button type="submit" class="btn-submit">
            <i class="fa fa-search"></i> Generate Report
        </button>

    </form>

</div>

</div>
</div>

<?php include_once('includes/footer.php'); ?>

</div>
</body>
</html>