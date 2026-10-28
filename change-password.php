<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsuid']==0)) {
  header('location:logout.php');
  } else{
if(isset($_POST['change']))
{
$userid=$_SESSION['bpmsuid'];
$cpassword=md5($_POST['currentpassword']);
$newpassword=md5($_POST['newpassword']);
$query1=mysqli_query($con,"select ID from tbluser where ID='$userid' and   Password='$cpassword'");
$row=mysqli_fetch_array($query1);
if($row>0){
$ret=mysqli_query($con,"update tbluser set Password='$newpassword' where ID='$userid'");

echo '<script>alert("Your password successully changed.")</script>';
} else {
echo '<script>alert("Your current password is wrong.")</script>';

}



}


  ?>
<!doctype html>
<html lang="en">
  <head>
 

    <title>Barbershop Management System | Signup Page</title>

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
    <div class="about-inner ">
        <div class="container">   
            <div class="main-titles-head text-center">
            <h3 class="header-name ">
                
 Change Password
            </h3>
           
        </div>
</div>
</div>
<div class="breadcrumbs-sub">
<div class="container">   
<ul class="breadcrumbs-custom-path">
    <li class="right-side propClone"><a href="index.php" class="">Home <span class="fa fa-angle-right" aria-hidden="true"></span></a> <p></li>
    <li class="active ">
        Change Password</li>
</ul>
</div>
</div>
    </div>
</section>
<!-- breadcrumbs //-->
<section class="w3l-contact-info-main" id="contact">
    <div class="contact-sec	">
        <div class="container">

            <div class="d-grid contact-view">
                <div class="cont-details">
                    <?php

$ret=mysqli_query($con,"select * from tblpage where PageType='contactus' ");
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>
                    <div class="cont-top">
                        <div class="cont-left text-center">
                            <span class="fa fa-phone text-primary"></span>
                        </div>
                        <div class="cont-right">
                            <h6>Call Us</h6>
                            <p class="para"><a href="tel:+44 99 555 42">+<?php  echo $row['MobileNumber'];?></a></p>
                        </div>
                    </div>
                    <div class="cont-top margin-up">
                        <div class="cont-left text-center">
                            <span class="fa fa-envelope-o text-primary"></span>
                        </div>
                        <div class="cont-right">
                            <h6>Email Us</h6>
                            <p class="para"><a href="mailto:example@mail.com" class="mail"><?php  echo $row['Email'];?></a></p>
                        </div>
                    </div>
                    <div class="cont-top margin-up">
                        <div class="cont-left text-center">
                            <span class="fa fa-map-marker text-primary"></span>
                        </div>
                        <div class="cont-right">
                            <h6>Address</h6>
                            <p class="para"> <?php  echo $row['PageDescription'];?></p>
                        </div>
                    </div>
                    <div class="cont-top margin-up">
                        <div class="cont-left text-center">
                            <span class="fa fa-map-marker text-primary"></span>
                        </div>
                        <div class="cont-right">
                            <h6>Time</h6>
                            <p class="para"> <?php  echo $row['Timing'];?></p>
                        </div>
                    </div>
               <?php } ?> </div>
                <div class="map-content-9 mt-lg-0 mt-4">
                    <h3>Password change!!</h3>
                    <form method="post" name="changepassword" onsubmit="return checkpass();">

                        <div style="padding-top: 30px;">
                            <label>Current Password</label>
                            
                            <input type="password" class="form-control" placeholder="Current Password" id="currentpassword" name="currentpassword" value="" required="true"></div>
                           <div style="padding-top: 30px;">
                            <label>New Password</label>
                            
                            <input type="password" class="form-control" placeholder="New Password" id="newpassword" name="newpassword" value="" required="true">
                        </div>
                        <div style="padding-top: 30px;">
                            <label>Confirm Password</label>
                           <input type="password" class="form-control" placeholder="Confirm Password" id="confirmpassword" name="confirmpassword" value=""  required="true">
                        <button type="submit" class="btn btn-contact" name="change">Save Change</button>
                    </form>
                </div>
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