<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (isset($_POST['submit'])) {
  $fname   = mysqli_real_escape_string($con, $_POST['fname']);
  $lname   = mysqli_real_escape_string($con, $_POST['lname']);
  $phone   = mysqli_real_escape_string($con, $_POST['phone']);
  $email   = mysqli_real_escape_string($con, $_POST['email']);
  $message = mysqli_real_escape_string($con, $_POST['message']);

  $query = mysqli_query($con,
    "INSERT INTO tblcontact (FirstName, LastName, Phone, Email, Message)
     VALUES ('$fname','$lname','$phone','$email','$message')"
  );

  if ($query) {
    echo "<script>alert('Your message was sent successfully!'); window.location.href='contact.php';</script>";
  } else {
    echo "<script>alert('Something went wrong. Please try again.');</script>";
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <title>Barbershop Management System | Contact Us</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="assets/css/style-starter.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://fonts.googleapis.com/css?family=Josefin+Slab:400,700,700i&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
  <link rel="icon" href="images/favicon.ico">

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
    a.action-button,
    button.action-button {
      color: #1a73e8 !important;
      border-bottom: 1.5px solid #1a73e8 !important;
      border-radius: 0;
    }
    a.action-button:hover,
    button.action-button:hover {
      color: #0d47a1 !important;
      border-bottom: 1.5px solid #0d47a1 !important;
    }

    /* ── Logo/appointment button → blue ── */
    a.logo-button,
    button.logo-button {
      background: #1a73e8 !important;
      border: 2px solid #1a73e8 !important;
      color: #ffffff !important;
    }
    a.logo-button:hover,
    button.logo-button:hover {
      background: #0d47a1 !important;
      border-color: #0d47a1 !important;
      color: #ffffff !important;
    }

    /* ── Breadcrumb banner ── */
    .w3l-inner-banner-main .about-inner {
      background: url('assets/images/a1.jpg') no-repeat center;
      background-size: cover;
    }

    /* ── Contact info icons → blue ── */
    .cont-left .fa {
      font-size: 24px;
      color: #1a73e8 !important;
    }

    /* ── Send button → blue ── */
    .btn-contact {
      background: #1a73e8 !important;
      border: none !important;
      color: #fff !important;
      padding: 12px 32px;
      font-size: 15px;
      border-radius: 4px;
      cursor: pointer;
      transition: background 0.2s;
      float: right;
    }
    .btn-contact:hover {
      background: #0d47a1 !important;
      color: #fff !important;
    }

    /* ── Form inputs ── */
    .map-content-9 form input,
    .map-content-9 form textarea {
      border: 1px solid #dedede;
      background: #f8f9fa;
      color: #555;
      font-size: 15px;
      padding: 12px 15px;
      width: 100%;
      border-radius: 4px;
      outline: none;
      transition: border-color 0.2s;
    }
    .map-content-9 form input:focus,
    .map-content-9 form textarea:focus {
      border-color: #1a73e8;
      background: #fff;
    }
    .map-content-9 form textarea {
      resize: vertical;
      min-height: 140px;
    }

    /* ── Contact info layout ── */
    .cont-details {
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
    }

    .cont-top {
      display: grid;
      grid-template-columns: 40px 1fr;
      gap: 15px;
      align-items: flex-start;
    }

    .cont-right h6 {
      font-size: 17px;
      font-weight: 600;
      color: #2b4052;
      margin-bottom: 4px;
    }

    .cont-right p a {
      color: #555;
      font-size: 15px;
    }
    .cont-right p a:hover {
      color: #1a73e8;
    }

    /* ── Two-column form fields ── */
    .twice-two {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
      margin-bottom: 15px;
    }

    .twice {
      margin-bottom: 15px;
    }

    /* ── Contact section layout ── */
    .contact-view {
      display: grid;
      grid-template-columns: 1fr 1.4fr;
      gap: 50px;
      align-items: start;
    }

    @media (max-width: 767px) {
      .contact-view {
        grid-template-columns: 1fr;
      }
      .twice-two {
        grid-template-columns: 1fr;
      }
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

<!-- ── BREADCRUMB BANNER ── -->
<section class="w3l-inner-banner-main">
  <div class="about about-inner">
    <div class="container">
      <div class="seen-w3">
        <h4 class="title">Contact Us</h4>
      </div>
    </div>
  </div>

  <div class="breadcrumbs-sub">
    <div class="container">
      <ul class="breadcrumbs-custom-path">
        <li><a href="index.php">Home <span class="fa fa-angle-right" aria-hidden="true"></span></a></li>
        <li class="active">Contact</li>
      </ul>
    </div>
  </div>
</section>
<!-- //breadcrumb -->

<!-- ── CONTACT SECTION ── -->
<section class="w3l-contact-info-main" id="contact">
  <div class="contact-sec">
    <div class="container">

      <div class="contact-view">

        <!-- LEFT: Contact Info from DB -->
        <div class="cont-details">
          <?php
            $ret = mysqli_query($con, "SELECT * FROM tblpage WHERE PageType='contactus'");
            if ($ret && mysqli_num_rows($ret) > 0):
              $row = mysqli_fetch_array($ret);
          ?>

<div class="cont-top">
  <div class="cont-left text-center">
    <span class="fa fa-phone"></span>
  </div>

  <div class="cont-right">

    <h6>Call Us</h6>

    <?php
      $mobile = preg_replace('/[^0-9]/', '', $row['MobileNumber']);

      // Force Philippine 11-digit format
      if(substr($mobile,0,2) != '09'){
          $mobile = '0' . substr($mobile,-11);
      }
    ?>

    <p class="para">
      <a href="tel:<?php echo htmlspecialchars($mobile); ?>">
        <?php echo htmlspecialchars($mobile); ?>
      </a>
    </p>

  </div>
</div>
          <div class="cont-top">
            <div class="cont-left text-center">
              <span class="fa fa-envelope-o"></span>
            </div>
            <div class="cont-right">
              <h6>Email Us</h6>
              <p class="para">
                <a href="mailto:<?php echo htmlspecialchars($row['Email']); ?>">
                  <?php echo htmlspecialchars($row['Email']); ?>
                </a>
              </p>
            </div>
          </div>

          <div class="cont-top">
            <div class="cont-left text-center">
              <span class="fa fa-map-marker"></span>
            </div>
            <div class="cont-right">
              <h6>Address</h6>
              <p class="para"><?php echo htmlspecialchars($row['PageDescription']); ?></p>
            </div>
          </div>

          <div class="cont-top">
            <div class="cont-left text-center">
              <span class="fa fa-clock-o"></span>
            </div>
            <div class="cont-right">
              <h6>Opening Hours</h6>
              <p class="para"><?php echo htmlspecialchars($row['Timing']); ?></p>
            </div>
          </div>

          <?php else: ?>
          <p class="para">Contact information is not available at the moment.</p>
          <?php endif; ?>
        </div><!-- /cont-details -->

        <!-- RIGHT: Contact Form -->
        <div class="map-content-9">
          <h5 style="font-size:22px; color:#2b4052; margin-bottom:1.2rem;">Send Us a Message</h5>
          <form method="post" autocomplete="off">

            <div class="twice-two">
              <input type="text"  name="fname"  placeholder="First Name" required>
              <input type="text"  name="lname"  placeholder="Last Name"  required>
            </div>

            <div class="twice-two">
              <input type="text"  name="phone"  placeholder="Phone Number" required
                     pattern="[0-9]+" maxlength="11">
              <input type="email" name="email"  placeholder="Email Address" required>
            </div>

            <div class="twice">
              <textarea name="message" placeholder="Your Message" required></textarea>
            </div>

            <button type="submit" name="submit" class="btn btn-contact">Send Message</button>

          </form>
        </div><!-- /map-content-9 -->

      </div><!-- /contact-view -->

    </div><!-- /container -->
  </div>
</section>

<?php include_once('includes/footer.php'); ?>

<!-- ── BACK TO TOP ── -->
<button onclick="topFunction()" id="movetop" title="Go to top">
  <span class="fa fa-long-arrow-up"></span>
</button>

<script>
  window.onscroll = function () { scrollFunction(); };

  function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
      document.getElementById("movetop").style.display = "block";
    } else {
      document.getElementById("movetop").style.display = "none";
    }
  }

  function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
  }
</script>

</body>
</html>