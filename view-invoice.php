<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsuid']==0)) {
  header('location:logout.php');
  } else{



  ?>
<!doctype html>
<html lang="en">
  <head>
 

    <title>Barbershop Management System | Booking History</title>

    <!-- Template CSS -->
    <link rel="stylesheet" href="assets/css/style-starter.css">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Slab:400,700,700i&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
  </head>
  <body id="home">
<?php include_once('includes/header.php');?>

 <style>
    /* ── Header → black ── */
    .w3l-header-4 .absolute-top,
    .w3l-header-4 header.active,
    .w3l-header-4 header.nav-fixed {
      background: #000 !important;
      border-bottom: none;
    }
    .w3l-header-4 .dropdown-menu {
      background: #111;
      border-color: #333;
    }

    /* ── Action button → blue ── */
    a.action-button, button.action-button {
      color: #1a73e8 !important;
      border-bottom: 1.5px solid #1a73e8 !important;
      border-radius: 0;
    }
    a.action-button:hover, button.action-button:hover {
      color: #0d47a1 !important;
      border-bottom: 1.5px solid #0d47a1 !important;
    }

    /* ── Logo/appointment button → blue ── */
    a.logo-button, button.logo-button {
      background: #1a73e8 !important;
      border: 2px solid #1a73e8 !important;
      color: #fff !important;
    }
    a.logo-button:hover, button.logo-button:hover {
      background: #0d47a1 !important;
      border-color: #0d47a1 !important;
    }

    /* ── Breadcrumb banner ── */
    .w3l-inner-banner-main .about-inner {
      background: url('assets/images/a1.jpg') no-repeat center;
      background-size: cover;
    }

    /* ── History section wrapper ── */
    .history-section {
      padding: 60px 0;
    }

    /* ── Section heading ── */
    .history-heading {
      text-align: center;
      margin-bottom: 2rem;
    }
    .history-heading h4 {
      font-size: 28px;
      color: #2b4052;
      font-weight: 700;
    }
    .history-heading p {
      color: #777;
      font-size: 15px;
      margin-top: 6px;
    }

    /* ── Table card ── */
    .table-card {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 16px rgba(0,0,0,0.08);
      overflow: hidden;
    }

    /* ── Table styles ── */
    .history-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 15px;
    }
    .history-table thead {
      background: #1a73e8;
      color: #fff;
    }
    .history-table thead th {
      padding: 14px 16px;
      font-weight: 600;
      letter-spacing: 0.03em;
      text-align: left;
      border: none;
    }
    .history-table tbody tr {
      border-bottom: 1px solid #f0f0f0;
      transition: background 0.15s;
    }
    .history-table tbody tr:last-child {
      border-bottom: none;
    }
    .history-table tbody tr:hover {
      background: #f5f9ff;
    }
    .history-table tbody td {
      padding: 13px 16px;
      color: #444;
      vertical-align: middle;
    }

    /* ── Status badge ── */
    .status-badge {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 600;
      letter-spacing: 0.03em;
    }
    .status-waiting {
      background: #fff8e1;
      color: #f57f17;
      border: 1px solid #ffe082;
    }
    .status-confirmed {
      background: #e8f5e9;
      color: #2e7d32;
      border: 1px solid #a5d6a7;
    }
    .status-cancelled {
      background: #fce4ec;
      color: #c62828;
      border: 1px solid #f48fb1;
    }
    .status-completed {
      background: #e3f2fd;
      color: #1565c0;
      border: 1px solid #90caf9;
    }

    /* ── View button ── */
    .btn-view {
      display: inline-block;
      background: #1a73e8;
      color: #fff;
      padding: 6px 16px;
      border-radius: 4px;
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      transition: background 0.2s;
    }
    .btn-view:hover {
      background: #0d47a1;
      color: #fff;
      text-decoration: none;
    }

    /* ── No records row ── */
    .no-record {
      text-align: center;
      padding: 40px 20px;
      color: #999;
    }
    .no-record .fa {
      font-size: 40px;
      color: #ddd;
      display: block;
      margin-bottom: 12px;
    }

    /* ── Book now CTA ── */
    .book-cta {
      text-align: center;
      margin-top: 2rem;
    }

    /* Responsive scroll */
    .table-responsive {
      overflow-x: auto;
    }
  </style>
</head>
<body id="home">

<?php include_once('includes/header.php'); ?>

<script src="assets/js/jquery-3.3.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script>
  $(function () {
    $('.navbar-toggler').click(function () {
      $('body').toggleClass('noscroll');
    });
  });
</script>

<!-- disable body scroll which navbar is in active -->

<!-- breadcrumbs -->
<section class="w3l-inner-banner-main">
    <div class="about-inner">
        <div class="container">   
            <div class="main-titles-head text-center">
            <h3 class="header-name ">
                
 Invoice History
            </h3>
        </div>
</div>
</div>
<div class="breadcrumbs-sub">
<div class="container">   
<ul class="breadcrumbs-custom-path">
    <li class="right-side propClone"><a href="index.php" class="">Home <span class="fa fa-angle-right" aria-hidden="true"></span></a> <p></li>
    <li class="active ">
        Invoice History</li>
</ul>
</div>
</div>
    </div>
</section>
<!-- breadcrumbs //-->
<section class="w3l-contact-info-main" id="contact">
    <div class="contact-sec	">
        <div class="container">

            <div>
                <div class="cont-details">
                   <div class="table-content table-responsive cart-table-content m-t-30">
                    <h3 class="title1">Invoice Details</h3>
                    
    <?php
    $invid=intval($_GET['invoiceid']);
$ret=mysqli_query($con,"select DISTINCT  date(tblinvoice.PostingDate) as invoicedate,tbluser.FirstName,tbluser.LastName,tbluser.Email,tbluser.MobileNumber,tbluser.RegDate
    from  tblinvoice 
    join tbluser on tbluser.ID=tblinvoice.Userid 
    where tblinvoice.BillingId='$invid'");
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>              
                
                    <div class="table-responsive bs-example widget-shadow">
                        <h4>Invoice #<?php echo $invid;?></h4>
                        <table class="table table-bordered" width="100%" border="1"> 
<tr>
<th colspan="6">Customer Details</th>   
</tr>
                             <tr> 
                                <th>Name</th> 
                                <td><?php echo $row['FirstName']?> <?php echo $row['LastName']?></td> 
                                <th>Contact no.</th> 
                                <td><?php echo $row['MobileNumber']?></td>
                                <th>Email </th> 
                                <td><?php echo $row['Email']?></td>
                            </tr> 
                            <tr> 
                                <th>Registration Date</th> 
                             <td>
    <?php echo date("Y-m-d h:i A", strtotime($row['RegDate'])); ?>
</td>
    <th>Invoice Date</th> 
    <td colspan="3">
        <?php echo date("Y-m-d h:i A", strtotime($row['invoicedate'])); ?>
    </td> 
</tr>
<?php }?>
</table> 
<table class="table table-bordered" width="100%" border="1"> 
<tr>
<th colspan="3">Services Details</th>   
</tr>
<tr>
<th>#</th>  
<th>Service</th>
<th>Cost</th>
</tr>

<?php
$ret=mysqli_query($con,"select tblservices.ServiceName,tblservices.Cost  
    from  tblinvoice 
    join tblservices on tblservices.ID=tblinvoice.ServiceId 
    where tblinvoice.BillingId='$invid'");
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {
    ?>

<tr>
<th><?php echo $cnt;?></th>
<td><?php echo $row['ServiceName']?></td>   
<td><?php echo $subtotal=$row['Cost']?></td>
</tr>
<?php 
$cnt=$cnt+1;
$gtotal+=$subtotal;
} ?>

<tr>
<th colspan="2" style="text-align:center">Grand Total</th>
<th><?php echo $gtotal?></th>   

</tr>
</table>
  <p style="margin-top:1%"  align="center">
  <i class="fa fa-print fa-2x" style="cursor: pointer;"  OnClick="CallPrint(this.value)" ></i>
</p>
                    </div> </div>
                
    </div>
   
    </div></div>
</section>
<?php include_once('includes/footer.php');?>
<!-- move top -->
<button onclick="topFunction()" id="movetop" title="Go to top">
	<span class="fa fa-long-arrow-up"></span>
</button>
<script>
	// When the user scrolls down 20px from the top of the document, show the button
	window.onscroll = function () {
		scrollFunction()
	};

	function scrollFunction() {
		if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
			document.getElementById("movetop").style.display = "block";
		} else {
			document.getElementById("movetop").style.display = "none";
		}
	}

	// When the user clicks on the button, scroll to the top of the document
	function topFunction() {
		document.body.scrollTop = 0;
		document.documentElement.scrollTop = 0;
	}
</script>
<!-- /move top -->
</body>

</html><?php } ?>