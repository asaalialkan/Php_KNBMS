<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
?>
<!doctype html>
<html lang="en">
<head>
  <title>Barbershop Management System | About Us</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="assets/css/style-starter.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://fonts.googleapis.com/css?family=Josefin+Slab:400,700,700i&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
  <link rel="icon" href="images/favicon.ico">

  <style>
    /* ── Header background → black ── */
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

    /* ── About page specific ── */
    .about-me {
      width: 100%;
      height: auto;
      object-fit: cover;
      border-radius: 4px;
    }

    .hair-two-columns {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
      margin-top: 20px;
    }

    .hair-left h5 a {
      font-size: 18px;
      color: var(--para-light-color);
      display: inline-block;
    }

    .hair-left h5 a:hover {
      color: #1a73e8;
    }

    /* Full-width package offer item */
    .hair-left.full-width {
      grid-column: 1 / -1;
    }

    /* Breadcrumb banner */
    .w3l-inner-banner-main .about-inner {
      background: url('assets/images/a1.jpg') no-repeat center;
      background-size: cover;
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
        <h4 class="title">About Us</h4>
      </div>
    </div>
  </div>

  <div class="breadcrumbs-sub">
    <div class="container">
      <ul class="breadcrumbs-custom-path">
        <li><a href="index.php">Home <span class="fa fa-angle-right" aria-hidden="true"></span></a></li>
        <li class="active">About</li>
      </ul>
    </div>
  </div>
</section>
<!-- //breadcrumb -->

<!-- ── ABOUT INTRO ── -->
<section class="w3l-content-with-photo-4" id="about">
  <div class="content-with-photo4-block">
    <div class="container">
      <div class="cwp4-two row align-items-center">

        <div class="cwp4-image col-xl-6 col-lg-6 mb-4 mb-lg-0">
          <img src="assets/images/C.jpg" alt="About Kuya Nash Barbershop" class="img-responsive about-me">
        </div>

        <div class="cwp4-text col-xl-6 col-lg-6">
          <div class="position-grid">
            <h3>Style and success starts here</h3>
            <p class="para mt-3">
              We offer a premium grooming experience tailored to every client.
              Whether you need a quick trim or a full grooming session, we've got you covered.
            </p>

            <div class="hair-two-columns mt-4">
              <div class="hair-left">
                <h5><a href="services.php">Haircut</a></h5>
              </div>
              <div class="hair-left">
                <h5><a href="services.php">Shave</a></h5>
              </div>
              <div class="hair-left">
                <h5><a href="services.php">Facial Steamer</a></h5>
              </div>
              <div class="hair-left">
                <h5><a href="services.php">Head Massage</a></h5>
              </div>
              <!-- Full-width package -->
              <div class="hair-left full-width">
                <h5><a href="services.php">Package Offer: Haircut, Shave, Facial Steamer, Hot Towel &amp; Head Massage</a></h5>
              </div>
            </div>

            <a href="book-appointment.php" class="btn logo-button mt-4">Get An Appointment</a>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<!-- ── ABOUT CONTENT FROM DB ── -->
<section class="w3l-recent-work">
  <div class="jst-two-col">
    <div class="container">
      <div class="row align-items-center">

        <div class="my-bio col-lg-6 mb-4 mb-lg-0">
          <div class="hair-make">
            <?php
              $ret = mysqli_query($con, "SELECT * FROM tblpage WHERE PageType='aboutus'");
              if ($ret && mysqli_num_rows($ret) > 0) {
                while ($row = mysqli_fetch_array($ret)) {
            ?>
              <h5><a href="#"><?php echo htmlspecialchars($row['PageTitle']); ?></a></h5>
              <p class="para mt-2"><?php echo htmlspecialchars($row['PageDescription']); ?></p>
            <?php
                }
              } else {
            ?>
              <h5>About Kuya Nash Barbershop</h5>
              <p class="para mt-2">
                Kuya Nash Barbershop is a full-service grooming establishment offering haircuts, styling,
                colouring, facials, body spa, waxing, and threading — your one-stop community grooming hub.
              </p>
            <?php } ?>
          </div>
        </div>

        <div class="col-lg-6">
          <img src="assets/images/barbershop_image_3.jpg" alt="Barbershop interior" class="img-responsive about-me">
        </div>

      </div>
    </div>
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