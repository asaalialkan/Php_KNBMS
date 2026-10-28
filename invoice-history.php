<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Guard: must be logged in
if (empty($_SESSION['bpmsuid'])) {
  header('Location: login.php');
  exit();
}

$userid = intval($_SESSION['bpmsuid']);
?>
<!doctype html>
<html lang="en">
<head>
  <title>Barbershop Management System | Invoice History</title>
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

    /* ── History section ── */
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
    .invoice-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 15px;
    }
    .invoice-table thead {
      background: #1a73e8;
      color: #fff;
    }
    .invoice-table thead th {
      padding: 14px 16px;
      font-weight: 600;
      letter-spacing: 0.03em;
      text-align: left;
      border: none;
    }
    .invoice-table tbody tr {
      border-bottom: 1px solid #f0f0f0;
      transition: background 0.15s;
    }
    .invoice-table tbody tr:last-child {
      border-bottom: none;
    }
    .invoice-table tbody tr:hover {
      background: #f5f9ff;
    }
    .invoice-table tbody td {
      padding: 13px 16px;
      color: #444;
      vertical-align: middle;
    }

    /* ── Invoice ID badge ── */
    .invoice-id {
      display: inline-block;
      background: #e8f0fe;
      color: #1a73e8;
      padding: 3px 10px;
      border-radius: 4px;
      font-size: 13px;
      font-weight: 700;
      letter-spacing: 0.04em;
      font-family: monospace;
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

    /* ── No records ── */
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

    /* ── Book CTA ── */
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

<!-- ── BREADCRUMB BANNER ── -->
<section class="w3l-inner-banner-main">
  <div class="about about-inner">
    <div class="container">
      <div class="seen-w3">
        <h4 class="title">Invoice History</h4>
      </div>
    </div>
  </div>

  <div class="breadcrumbs-sub">
    <div class="container">
      <ul class="breadcrumbs-custom-path">
        <li><a href="index.php">Home <span class="fa fa-angle-right" aria-hidden="true"></span></a></li>
        <li class="active">Invoice History</li>
      </ul>
    </div>
  </div>
</section>
<!-- //breadcrumb -->

<!-- ── INVOICE HISTORY SECTION ── -->
<section class="w3l-contact-info-main">
  <div class="history-section">
    <div class="container">

      <div class="history-heading">
        <h4>Your Invoice History</h4>
        <p>View and download all your past invoices below.</p>
      </div>

      <div class="table-card">
        <div class="table-responsive">
          <table class="invoice-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Invoice ID</th>
                <th>Customer Name</th>
                <th>Mobile Number</th>
                <th>Invoice Date</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $query = mysqli_query($con,
                  "SELECT DISTINCT
                     tbluser.ID        AS uid,
                     tbluser.FirstName,
                     tbluser.LastName,
                     tbluser.MobileNumber,
                     tblinvoice.BillingId,
                     DATE(tblinvoice.PostingDate) AS PostingDate
                   FROM tbluser
                   JOIN tblinvoice ON tbluser.ID = tblinvoice.Userid
                   WHERE tbluser.ID = '$userid'
                   ORDER BY tblinvoice.ID DESC"
                );

                $count = mysqli_num_rows($query);

                if ($count > 0):
                  $cnt = 1;
                  while ($row = mysqli_fetch_array($query)):
                    $fullName    = htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']);
                    $mobile      = htmlspecialchars($row['MobileNumber']);
                    $billingId   = htmlspecialchars($row['BillingId']);
                    $postingDate = !empty($row['PostingDate'])
                                   ? date('M d, Y', strtotime($row['PostingDate']))
                                   : '—';
              ?>
              <tr>
                <td><?php echo $cnt; ?></td>
                <td><span class="invoice-id"><?php echo $billingId; ?></span></td>
                <td>
                  <span class="fa fa-user" style="color:#1a73e8; margin-right:6px;"></span>
                  <?php echo $fullName; ?>
                </td>
                <td>
                  <span class="fa fa-phone" style="color:#888; margin-right:5px;"></span>
                  <?php echo $mobile; ?>
                </td>
                <td>
                  <span class="fa fa-calendar" style="color:#888; margin-right:5px;"></span>
                  <?php echo $postingDate; ?>
                </td>
                <td>
                  <a href="view-invoice.php?invoiceid=<?php echo $billingId; ?>"
                     class="btn-view">
                    <span class="fa fa-file-text-o" style="margin-right:4px;"></span> View
                  </a>
                </td>
              </tr>
              <?php
                  $cnt++;
                  endwhile;
                else:
              ?>
              <tr>
                <td colspan="6" class="no-record">
                  <span class="fa fa-inbox"></span>
                  No invoices found yet.
                </td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div><!-- /table-responsive -->
      </div><!-- /table-card -->

      <div class="book-cta">
        <a href="book-appointment.php" class="btn logo-button mt-4">
          <span class="fa fa-calendar-plus-o" style="margin-right:6px;"></span>
          Book a New Appointment
        </a>
      </div>

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
    document.getElementById("movetop").style.display =
      (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20)
        ? "block" : "none";
  }
  function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
  }
</script>

</body>
</html>