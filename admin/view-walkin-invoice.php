<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid'])==0) {
    header('location:logout.php');
} else {
?>
<!DOCTYPE HTML>
<html>
<head>
<title>BPMS || View Walk-In Invoice</title>
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
<link href="css/style.css" rel='stylesheet' type='text/css' />
<link href="css/font-awesome.css" rel="stylesheet">
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/modernizr.custom.js"></script>
<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,300italic,400italic,700,700italic' rel='stylesheet' type='text/css'>
<link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
<script src="js/wow.min.js"></script>
<script> new WOW().init(); </script>
<script src="js/metisMenu.min.js"></script>
<script src="js/custom.js"></script>
<link href="css/custom.css" rel="stylesheet">
</head>
<body class="cbp-spmenu-push">
<div class="main-content">

    <?php include_once('includes/sidebar.php'); ?>
    <?php include_once('includes/header.php'); ?>

    <div id="page-wrapper">
        <div class="main-page">
            <div class="tables" id="exampl">

                <?php
                $id  = intval($_GET['id']);
                $ret = mysqli_query($con, "SELECT * FROM walkin_customers WHERE id='$id'");
                $row = mysqli_fetch_array($ret);

                if ($row):
                    $invoice_no = 'WLK-' . str_pad($row['id'], 4, '0', STR_PAD_LEFT);
                ?>

                <div class="table-responsive bs-example widget-shadow">
                    <h4>Invoice #<?= $invoice_no ?></h4>

                    <!-- Customer Details -->
                    <table class="table table-bordered" width="100%" border="1">
                        <tr>
                            <th colspan="6">Customer Details</th>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td><?= htmlspecialchars($row['customer_name']) ?></td>
                            <th>Contact no.</th>
                            <td><?= htmlspecialchars($row['phone']) ?></td>
                            <th>Invoice Date</th>
                            <td><?= date('Y-m-d') ?></td>
                        </tr>
                        <tr>
                            <th>Appointment Date</th>
                            <td><?= htmlspecialchars($row['appointment_date']) ?></td>
                            <th>Appointment Time</th>
                            <td colspan="3"><?= htmlspecialchars($row['appointment_time']) ?></td>
                        </tr>
                        <?php if (!empty($row['notes'])): ?>
                        <tr>
                            <th>Notes</th>
                            <td colspan="5"><?= htmlspecialchars($row['notes']) ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>

                    <!-- Service Details -->
                    <table class="table table-bordered" width="100%" border="1">
                        <tr>
                            <th colspan="3">Services Details</th>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>Service</th>
                            <th>Cost</th>
                        </tr>
                        <tr>
                            <th>1</th>
                            <td><?= htmlspecialchars($row['service']) ?></td>
                            <td>—</td>
                        </tr>
                        <tr>
                            <th colspan="2" style="text-align:center">Grand Total</th>
                            <th>—</th>
                        </tr>
                    </table>

                    <!-- Print Button -->
                    <p style="margin-top:1%" align="center">
                        <i class="fa fa-print fa-2x" style="cursor:pointer;" onclick="CallPrint()"></i>
                    </p>
                </div>

                <?php else: ?>
                    <div class="alert alert-danger">Walk-in record not found.</div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <?php include_once('includes/footer.php'); ?>
</div>

<script src="js/classie.js"></script>
<script>
    var menuLeft     = document.getElementById('cbp-spmenu-s1'),
        showLeftPush = document.getElementById('showLeftPush'),
        body         = document.body;

    showLeftPush.onclick = function() {
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

    function CallPrint() {
        var prtContent = document.getElementById("exampl");
        var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
        WinPrint.document.write(prtContent.innerHTML);
        WinPrint.document.close();
        WinPrint.focus();
        WinPrint.print();
    }
</script>
<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>
<script src="js/bootstrap.js"></script>
</body>
</html>
<?php } ?>