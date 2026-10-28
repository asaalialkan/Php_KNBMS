<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
?>
<!doctype html>
<html lang="en">
<head>
  <title>KNSMS | Home Page</title>
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
      background: #111111;
      border-color: #333;
    }

    /* ── "Read more" action button → blue ── */
    a.action-button,
    button.action-button {
      color: #1a73e8 !important;
      border-bottom: 1.5px solid #1a73e8 !important;
      border-radius: 0;
    }

    a.action-button:hover,
    a.action-button:active,
    button.action-button:hover,
    button.action-button:active {
      color: #0d47a1 !important;
      border-bottom: 1.5px solid #0d47a1 !important;
    }

    /* ── "Get An Appointment" logo-button → blue ── */
    a.logo-button,
    button.logo-button {
      background: #1a73e8 !important;
      border: 2px solid #1a73e8 !important;
      color: #ffffff !important;
    }

    a.logo-button:hover,
    a.logo-button:active,
    button.logo-button:hover,
    button.logo-button:active {
      background: #0d47a1 !important;
      border-color: #0d47a1 !important;
      color: #ffffff !important;
    }
  </style>
</head>

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

<!-- ── HERO SLIDER ── -->
<style>
  /* Fix image paths and ensure slider renders correctly */
  .w3l-hero-headers-9 .slide-one {
    background-image: url('assets/images/a1.jpg') !important;
  }
  .w3l-hero-headers-9 .slide-two {
    background-image: url('assets/images/P299.png') !important;
  }

  /* Ensure the slider and slides have height */
  .w3l-hero-headers-9 .css-slider,
  .w3l-hero-headers-9 .slide {
    height: 700px;
  }

  /* First slide must start visible (left: 0) when slide-1 is checked */
  #slide-1:checked ~ .css-slider .slide-one,
  #slide-2:checked ~ .css-slider .slide-two {
    left: 0;
    z-index: 100;
    transition: left .65s ease-in-out;
  }

  /* All other slides stay off-screen */
  #slide-1:checked ~ .css-slider .slide-two,
  #slide-2:checked ~ .css-slider .slide-one {
    left: 100%;
  }

  /* Dot nav labels need display */
  .w3l-hero-headers-9 header label {
    background-color: rgba(255,255,255,0.6);
    border-radius: 5px;
    width: 42px;
    height: 5px;
    margin: 25px 5px;
    cursor: pointer;
    display: inline-block;
  }
  #slide-1:checked ~ header label[for="slide-1"],
  #slide-2:checked ~ header label[for="slide-2"] {
    background: #5acae3;
  }

  /* Arrow labels need display block */
  .w3l-hero-headers-9 nav .prev label,
  .w3l-hero-headers-9 nav .next label {
    display: block;
    cursor: pointer;
    color: #fff;
    font-size: 2rem;
    padding: 10px;
  }
</style>

<div class="w3l-hero-headers-9">
  <div class="header-outs">

    <!-- Radio inputs MUST be siblings of .css-slider and header for CSS selectors to work -->
    <input type="radio" name="slider" id="slide-1" checked style="display:none;">
    <input type="radio" name="slider" id="slide-2" style="display:none;">

    <div class="css-slider">

      <div class="slide slide-one">
        <div class="container">
          <div class="banner-text">
            <h4>Welcome to</h4>
            <h3>Kuya Nash Barbershop</h3>
            <p>Your one-stop grooming destination for cuts, styling, facials, and full relaxation services.</p>
            <a href="book-appointment.php" class="btn logo-button">Get An Appointment</a>
          </div>
        </div>
      </div>

      <div class="slide slide-two">
        <div class="container">
          <div class="banner-text">
            <h4>Experience the Best</h4>
            <h3>Grooming &amp; Wellness</h3>
            <p>From classic haircuts to body spa treatments — we've got you covered.</p>
            <a href="book-appointment.php" class="btn logo-button">Book Now</a>
          </div>
        </div>
      </div>

    </div><!-- /css-slider -->

    <!-- Dot navigation — must be sibling of inputs -->
    <header>
      <label for="slide-1"></label>
      <label for="slide-2"></label>
    </header>

    <!-- Arrow navigation -->
    <nav>
      <div class="prev">
        <label for="slide-2">&#10094;</label>
      </div>
      <div class="next">
        <label for="slide-1">&#10095;</label>
      </div>
    </nav>

  </div><!-- /header-outs -->
</div><!-- /w3l-hero-headers-9 -->

<!-- ── CALL TO ACTION ── -->
<section class="w3l-call-to-action_9">
  <div class="call-w3">
    <div class="container">
      <div class="grids">
        <div class="grids-content row">

          <div class="column col-lg-4 col-md-6 color-2">
            <div>
              <h4>Our Barbershop is Most Popular</h4>
              <p class="para">Barbershop Offers - Services</p>
              <a href="about.php" class="action-button btn mt-md-4 mt-3">Read more</a>
            </div>
          </div>

          <div class="column col-lg-4 col-md-6 col-sm-6 back-image">
            <img src="assets/images/PC1.png" alt="product" class="img-responsive">
          </div>

          <div class="column col-lg-4 col-md-6 col-sm-6 back-image2">
            <img src="assets/images/PC2.png" alt="product" class="img-responsive">
          </div>

        </div>
      </div>
    </div>
  </div>
</section>

<!-- ── TEAMS / ABOUT SNIPPET ── -->
<section class="w3l-teams-15">
  <div class="team-single-main">
    <div class="container">
  
        <h3 class="team-head">Come experience the secrets of relaxation with every cut and shave.</h3>
        <p class="para text">Kuya Nash Barbershop is a full-service grooming establishment that offers a wide range of personal
          care services in one place, including haircuts, hair styling, colouring, texturing, and hair spa
          treatments, along with grooming and wellness options such as facials, body spa, waxing, threading.
          It functions not just as a traditional barbershop but as a modern community grooming hub where
          customers can get both basic haircuts and more complete salon-style services. Known for its
          convenient walk-in setup and affordable local pricing, Kuya Nash provides a practical one-stop
          solution for grooming needs in the area, catering to clients who want either a quick trim or a
          full self-care and relaxation experience.
        </p>
        <a href="book-appointment.php" class="btn logo-button top-margin mt-4">Get An Appointment</a>
      </div>
    </div>
  </div>
</section>

<!-- ── SERVICES LIST ── -->
<section class="w3l-specification-6">
  <div class="specification-layout">
    <div class="container">
      <div class="row">

        <div class="col-lg-6 back-image">
          <img src="assets/images/KNB.png" alt="product" class="img-responsive">
        </div>

        <div class="col-lg-6 about-right-faq align-self">
          <h3 class="title-big"><a href="about.php">Clean &amp; Recommended Barber Services</a></h3>
          <p class="mt-3 para">Their array of Bar services include haircuts, hair spas, colouring, texturing, styling, waxing, natural facials and more.</p>

          </div><!-- /hair-cut -->

        </div><!-- /col-lg-6 -->
      </div><!-- /row -->
    </div><!-- /container -->
  </div><!-- /specification-layout -->
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