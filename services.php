<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
?>
<!doctype html>
<html lang="en">
<head>
  <title>Barbershop Management System | Services</title>
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

    /* ── Service cards ── */
    .service-card {
      background: #fff;
      border-radius: 6px;
      overflow: hidden;
      box-shadow: 0 2px 12px rgba(0,0,0,0.08);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      height: 100%;
      display: flex;
      flex-direction: column;
    }

    .service-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 24px rgba(0,0,0,0.14);
    }

    .service-card img {
      width: 100%;
      height: 220px;
      object-fit: cover;
      display: block;
    }

    .service-card-body {
      padding: 1.2rem 1.4rem 1.5rem;
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .service-card-body hr {
      border-color: #f0f0f0;
      margin: 0 0 0.8rem;
    }

    .service-card-body h5 {
      font-size: 18px;
      font-weight: 600;
      color: #2b4052;
      margin-bottom: 0.5rem;
    }

    .service-card-body p.desc {
      font-size: 15px;
      line-height: 1.6;
      color: #777;
      flex: 1;
      margin-bottom: 0.8rem;
    }

    .service-card-body .cost {
      font-size: 16px;
      font-weight: 700;
      color: #1a73e8;
      margin-bottom: 1rem;
    }

    .service-card-body .btn-book {
      display: inline-block;
      background: #1a73e8;
      color: #fff;
      font-size: 13px;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      padding: 9px 20px;
      border-radius: 4px;
      border: none;
      text-decoration: none;
      transition: background 0.2s;
      align-self: flex-start;
    }

    .service-card-body .btn-book:hover {
      background: #0d47a1;
      color: #fff;
      text-decoration: none;
    }

    /* Card grid spacing */
    .services-grid .col-lg-4,
    .services-grid .col-md-6 {
      margin-bottom: 30px;
      display: flex;
    }

    /* Empty state */
    .no-services {
      text-align: center;
      padding: 60px 20px;
      color: #777;
    }
    .no-services .fa {
      font-size: 48px;
      color: #ddd;
      margin-bottom: 16px;
      display: block;
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
        <h4 class="title">Our Services</h4>
      </div>
    </div>
  </div>

  <div class="breadcrumbs-sub">
    <div class="container">
      <ul class="breadcrumbs-custom-path">
        <li><a href="index.php">Home <span class="fa fa-angle-right" aria-hidden="true"></span></a></li>
        <li class="active">Services</li>
      </ul>
    </div>
  </div>
</section>
<!-- //breadcrumb -->

<!-- ── SERVICES GRID ── -->
<section class="w3l-recent-work-hobbies">
  <div class="recent-work">
    <div class="container">

      <div class="text-center mb-5">
        <h3 style="color:#2b4052; font-size:32px;">What We Offer</h3>
        <p class="para" style="max-width:560px; margin:10px auto 0;">
          From classic cuts to full grooming packages — find the service that's right for you.
        </p>
      </div>

      <div class="row services-grid">
        <?php
          $ret = mysqli_query($con, "SELECT * FROM tblservices");
          if ($ret && mysqli_num_rows($ret) > 0):
            while ($row = mysqli_fetch_array($ret)):
        ?>
        <div class="col-lg-4 col-md-6 col-sm-6">
          <div class="service-card">
            <img src="admin/images/<?php echo htmlspecialchars($row['Image']); ?>"
                 alt="<?php echo htmlspecialchars($row['ServiceName']); ?>"
                 onerror="this.src='assets/images/b1.jpg'">
            <div class="service-card-body">
              <hr>
              <h5><?php echo htmlspecialchars($row['ServiceName']); ?></h5>
              <p class="desc"><?php echo htmlspecialchars($row['ServiceDescription']); ?></p>
              <p class="cost">&#8369; <?php echo number_format($row['Cost'], 2); ?></p>
              <a href="book-appointment.php" class="btn-book">Book Now</a>
            </div>
          </div>
        </div>
        <?php
            endwhile;
          else:
        ?>
        <div class="col-12 no-services">
          <span class="fa fa-scissors"></span>
          <h5>No services available at the moment.</h5>
          <p>Please check back soon or contact us directly.</p>
        </div>
        <?php endif; ?>
      </div><!-- /row -->

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