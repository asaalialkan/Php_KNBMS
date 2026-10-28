<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (empty($_SESSION['bpmsuid'])) {
  header('Location: login.php');
  exit();
}

if (isset($_POST['submit'])) {
    $uid       = intval($_SESSION['bpmsuid']);
    $service   = intval($_POST['service']);
    $barber    = intval($_POST['barber']);
    $adate     = mysqli_real_escape_string($con, $_POST['adate']);
    $atime     = mysqli_real_escape_string($con, $_POST['atime']);
    $msg       = mysqli_real_escape_string($con, $_POST['message']);
    $payment   = mysqli_real_escape_string($con, $_POST['payment_method']);
    $aptnumber = mt_rand(100000000, 999999999);
    $proof     = "";

    if (!empty($_FILES['payment_proof']['name'])) {
        $extension = pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION);
        $proof = time() . '_' . mt_rand(1000, 9999) . '.' . $extension;
        move_uploaded_file($_FILES['payment_proof']['tmp_name'], 'uploads/payments/' . $proof);
    }

    $query = mysqli_query($con,
        "INSERT INTO tblbook
        (UserID, AptNumber, ServiceID, BarberID, AptDate, AptTime, Message, PaymentMethod, PaymentProof, PaymentStatus)
        VALUES
        ('$uid','$aptnumber','$service','$barber','$adate','$atime','$msg','$payment','$proof','Pending')"
    );

    if ($query) {
        $_SESSION['aptno'] = $aptnumber;
        echo "<script>alert('Appointment booked successfully. Payment is pending verification.'); window.location='thank-you.php';</script>";
        exit();
    } else {
        echo "<script>alert('Something went wrong. Please try again.');</script>";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <title>Barbershop Management System | Book Appointment</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="assets/css/style-starter.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Josefin+Slab:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="icon" href="images/favicon.ico">

  <style>
    /* ── Navbar ── */
    .w3l-header-4 .absolute-top,
    .w3l-header-4 header.active,
    .w3l-header-4 header.nav-fixed { background: #000 !important; border-bottom: none; }
    .w3l-header-4 .dropdown-menu { background: #111; border-color: #333; }
    a.action-button, button.action-button {
      color: #1a73e8 !important; border-bottom: 1.5px solid #1a73e8 !important; border-radius: 0;
    }
    a.action-button:hover, button.action-button:hover {
      color: #0d47a1 !important; border-bottom: 1.5px solid #0d47a1 !important;
    }
    a.logo-button, button.logo-button {
      background: #1a73e8 !important; border: 2px solid #1a73e8 !important; color: #fff !important;
    }
    a.logo-button:hover, button.logo-button:hover {
      background: #0d47a1 !important; border-color: #0d47a1 !important;
    }

    /* ── Breadcrumb banner ── */
    .w3l-inner-banner-main .about-inner {
      background: url('assets/images/a1.jpg') no-repeat center;
      background-size: cover;
    }

    /* ── Page ── */
    body { background: #f4f6f9; font-family: 'Poppins', sans-serif; }
    .bk-section { padding: 3rem 0 4rem; }

    /* ── Two-column grid ── */
    .bk-grid {
      display: grid;
      grid-template-columns: 300px 1fr;
      gap: 28px;
      align-items: start;
    }
    @media (max-width: 992px) { .bk-grid { grid-template-columns: 1fr; } }

    /* ── Left column stack ── */
    .bk-left { display: flex; flex-direction: column; gap: 20px; }

    /* ── Cards ── */
    .bk-card {
      background: #fff;
      border-radius: 8px;
      border: 1px solid #e0e4ea;
      overflow: hidden;
    }

    .bk-card-header {
      background: #000;
      padding: 1rem 1.4rem;
      display: flex; align-items: center; gap: 10px;
    }
    .bk-card-header.blue { background: #1a73e8; }
    .bk-card-header h3 {
      font-family: 'Josefin Slab', serif;
      font-size: 1rem; color: #fff; margin: 0; letter-spacing: 0.04em;
    }
    .bk-card-header .fa { color: #fff; font-size: 16px; }
    .bk-card-body { padding: 1.2rem 1.4rem; }

    /* ── Contact items ── */
    .bk-contact-item {
      display: flex; gap: 12px; align-items: flex-start;
      padding: 10px 0; border-bottom: 1px solid #f0f2f5;
    }
    .bk-contact-item:last-child { border-bottom: none; }
    .bk-contact-icon {
      width: 36px; height: 36px; background: #e8f0fd;
      border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .bk-contact-icon .fa { color: #1a73e8; font-size: 15px; }
    .bk-contact-text strong { display: block; font-size: 12px; font-weight: 600; color: #2b4052; margin-bottom: 2px; }
    .bk-contact-text span { font-size: 12px; color: #888; line-height: 1.5; }
    .bk-contact-text a { color: #555; text-decoration: none; }
    .bk-contact-text a:hover { color: #1a73e8; }

    /* ── Payment number boxes ── */
    .pay-box {
      display: flex; align-items: center; gap: 12px;
      border-radius: 5px; padding: 12px 14px;
      margin-top: 10px;
    }
    .pay-box:first-of-type { margin-top: 0; }
    .pay-box.gcash {
      background: #e8f0fd; border: 1px solid #b8d0f7; border-left: 3px solid #1a73e8;
    }
    .pay-box.maya {
      background: #e8f5e9; border: 1px solid #b7dfc2; border-left: 3px solid #00b14f;
      margin-top: 10px;
    }
    .pay-box .fa { font-size: 22px; flex-shrink: 0; }
    .pay-box.gcash .fa { color: #1a73e8; }
    .pay-box.maya  .fa { color: #00b14f; }
    .pay-box .pay-label {
      font-size: 10px; color: #666;
      text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 2px;
    }
    .pay-box.gcash .pay-num { font-size: 17px; font-weight: 700; color: #1a73e8; letter-spacing: 0.04em; }
    .pay-box.maya  .pay-num { font-size: 17px; font-weight: 700; color: #00b14f; letter-spacing: 0.04em; }

    /* ── Form ── */
    .bk-form-body { padding: 1.8rem 2rem; }

    .field-group { margin-bottom: 1.1rem; }
    .field-group label {
      display: block; font-size: 11px; font-weight: 600;
      color: #555; margin-bottom: 5px;
      letter-spacing: 0.06em; text-transform: uppercase;
    }
    .field-group input,
    .field-group select,
    .field-group textarea {
      width: 100%; box-sizing: border-box;
      padding: 10px 13px; border: 1px solid #dde1ea;
      border-radius: 5px; font-size: 14px; color: #2b4052;
      background: #f8f9fc; outline: none;
      font-family: 'Poppins', sans-serif;
      transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
    }
    .field-group input:focus,
    .field-group select:focus,
    .field-group textarea:focus {
      border-color: #1a73e8; background: #fff;
      box-shadow: 0 0 0 3px rgba(26,115,232,0.1);
    }
    .field-group textarea { resize: vertical; min-height: 90px; }
    .field-group select {
      appearance: none; -webkit-appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath fill='%231a73e8' d='M5 6L0 0h10z'/%3E%3C/svg%3E");
      background-repeat: no-repeat; background-position: right 13px center;
      padding-right: 34px; cursor: pointer;
    }

    .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    @media (max-width: 480px) { .field-row { grid-template-columns: 1fr; } }

    /* ── Section separator ── */
    .bk-sep {
      display: flex; align-items: center; gap: 10px;
      margin: 1.4rem 0 1.1rem;
    }
    .bk-sep::before, .bk-sep::after { content:''; flex:1; height:1px; background:#e0e4ea; }
    .bk-sep span {
      font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase;
      color: #aaa; font-weight: 600; white-space: nowrap;
    }

    /* ── Upload area ── */
    .bk-upload-area {
      border: 2px dashed #dde1ea; border-radius: 6px;
      padding: 20px; text-align: center; cursor: pointer;
      transition: border-color 0.2s, background 0.2s;
      position: relative; background: #f8f9fc; margin-bottom: 1.1rem;
    }
    .bk-upload-area:hover { border-color: #1a73e8; background: #f0f5ff; }
    .bk-upload-area .fa { font-size: 26px; color: #c5cfe0; display: block; margin-bottom: 8px; }
    .bk-upload-area .upl-title { font-size: 13px; font-weight: 600; color: #555; margin-bottom: 3px; }
    .bk-upload-area .upl-sub { font-size: 11px; color: #aaa; }
    .bk-upload-area input[type="file"] {
      position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
    }

    /* ── Submit ── */
    .btn-apt {
      width: 100%; padding: 13px; background: #1a73e8; color: #fff;
      border: none; border-radius: 5px; font-size: 14px; font-weight: 600;
      letter-spacing: 0.06em; cursor: pointer; font-family: 'Poppins', sans-serif;
      transition: background 0.2s;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      margin-top: 0.4rem;
    }
    .btn-apt:hover { background: #0d47a1; }
  </style>
</head>
<body id="home">

<?php include_once('includes/header.php'); ?>

<script src="assets/js/jquery-3.3.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script>
  $(function () {
    $('.navbar-toggler').click(function () { $('body').toggleClass('noscroll'); });
  });
</script>

<!-- BREADCRUMB BANNER -->
<section class="w3l-inner-banner-main">
  <div class="about about-inner">
    <div class="container">
      <div class="seen-w3">
        <h4 class="title">Book Appointment</h4>
      </div>
    </div>
  </div>
  <div class="breadcrumbs-sub">
    <div class="container">
      <ul class="breadcrumbs-custom-path">
        <li><a href="index.php">Home <span class="fa fa-angle-right" aria-hidden="true"></span></a></li>
        <li class="active">Book Appointment</li>
      </ul>
    </div>
  </div>
</section>

<!-- APPOINTMENT SECTION -->
<section class="bk-section">
  <div class="container">
    <div class="bk-grid">

      <!-- ── LEFT COLUMN ── -->
      <div class="bk-left">

        <!-- Contact Info card -->
        <div class="bk-card">
          <div class="bk-card-header">
            <span class="fa fa-info-circle"></span>
            <h3>Contact Information</h3>
          </div>
          <div class="bk-card-body">
            <?php
              $ret = mysqli_query($con, "SELECT * FROM tblpage WHERE PageType='contactus'");
              if ($ret && mysqli_num_rows($ret) > 0):
                $row = mysqli_fetch_array($ret);
            ?>
            <div class="bk-contact-item">
              <div class="bk-contact-icon"><span class="fa fa-phone"></span></div>
              <div class="bk-contact-text">
                <strong>Call Us</strong>
                <span><a href="tel:<?php echo htmlspecialchars($row['MobileNumber']); ?>">+<?php echo htmlspecialchars($row['MobileNumber']); ?></a></span>
              </div>
            </div>
            <div class="bk-contact-item">
              <div class="bk-contact-icon"><span class="fa fa-envelope-o"></span></div>
              <div class="bk-contact-text">
                <strong>Email Us</strong>
                <span><a href="mailto:<?php echo htmlspecialchars($row['Email']); ?>"><?php echo htmlspecialchars($row['Email']); ?></a></span>
              </div>
            </div>
            <div class="bk-contact-item">
              <div class="bk-contact-icon"><span class="fa fa-map-marker"></span></div>
              <div class="bk-contact-text">
                <strong>Address</strong>
                <span><?php echo htmlspecialchars($row['PageDescription']); ?></span>
              </div>
            </div>
            <div class="bk-contact-item">
              <div class="bk-contact-icon"><span class="fa fa-clock-o"></span></div>
              <div class="bk-contact-text">
                <strong>Opening Hours</strong>
                <span><?php echo htmlspecialchars($row['Timing']); ?></span>
              </div>
            </div>
            <?php else: ?>
            <p style="color:#888;font-size:13px;margin:0;">Contact information unavailable.</p>
            <?php endif; ?>
          </div>
        </div>
        <!-- /Contact Info card -->

        <!-- E-Wallet Payment card -->
        <div class="bk-card">
          <div class="bk-card-header blue">
            <span class="fa fa-mobile"></span>
            <h3>E-Wallet Payment</h3>
          </div>
          <div class="bk-card-body">
            <p style="font-size:12px;color:#888;margin:0 0 12px;">
              Send your payment to any of the numbers below, then upload your screenshot in the form.
            </p>

            <!-- GCash -->
            <div class="pay-box gcash">
              <span class="fa fa-mobile"></span>
              <div>
                <div class="pay-label">GCash Number</div>
                <div class="pay-num">09123456789</div>
              </div>
            </div>

            <!-- Maya -->
            <div class="pay-box maya">
              <span class="fa fa-mobile"></span>
              <div>
                <div class="pay-label">Maya Number</div>
                <div class="pay-num">09123456789</div>
              </div>
            </div>

          </div>
        </div>
        <!-- /E-Wallet Payment card -->

      </div>
      <!-- ── END LEFT COLUMN ── -->

      <!-- ── RIGHT COLUMN: Appointment Form ── -->
      <div class="bk-card">
        <div class="bk-card-header">
          <span class="fa fa-calendar-plus-o"></span>
          <h3>Schedule Your Visit</h3>
        </div>
        <div class="bk-form-body">

          <form method="post" enctype="multipart/form-data" autocomplete="off">

            <!-- Service -->
            <div class="field-group">
              <label for="service">Select Service</label>
              <select id="service" name="service" required>
                <option value="">-- Choose a Service --</option>
                <?php
                  $sret = mysqli_query($con, "SELECT ID, ServiceName, Cost FROM tblservices ORDER BY ServiceName ASC");
                  while ($srow = mysqli_fetch_array($sret)):
                ?>
                <option value="<?php echo intval($srow['ID']); ?>">
                  <?php echo htmlspecialchars($srow['ServiceName']); ?> — ₱<?php echo number_format($srow['Cost'], 2); ?>
                </option>
                <?php endwhile; ?>
              </select>
            </div>

            <!-- Barber -->
            <div class="field-group">
              <label for="barber">Select Barber</label>
              <select id="barber" name="barber" required>
                <option value="">-- Choose a Barber --</option>
                <?php
                  $bret = mysqli_query($con, "SELECT ID, Name FROM tblbarbers ORDER BY Name ASC");
                  while ($brow = mysqli_fetch_array($bret)):
                ?>
                <option value="<?php echo intval($brow['ID']); ?>">
                  <?php echo htmlspecialchars($brow['Name']); ?>
                </option>
                <?php endwhile; ?>
              </select>
            </div>

            <!-- Date & Time -->
            <div class="field-row">
              <div class="field-group">
                <label for="adate">Appointment Date</label>
                <input type="date" id="adate" name="adate" required>
              </div>
              <div class="field-group">
                <label for="atime">Appointment Time</label>
                <input type="time" id="atime" name="atime" required>
              </div>
            </div>

            <!-- Notes -->
            <div class="field-group">
              <label for="message">Additional Notes</label>
              <textarea id="message" name="message" placeholder="Any special requests or notes..."></textarea>
            </div>

            <!-- Payment separator -->
            <div class="bk-sep"><span>Payment Details</span></div>

            <!-- Payment Method -->
            <div class="field-group">
              <label for="payment_method">Payment Method</label>
              <select id="payment_method" name="payment_method" required>
                <option value="">-- Select Payment Method --</option>
                <option value="GCash">GCash</option>
                <option value="Maya">Maya</option>
              </select>
            </div>

            <!-- Upload -->
            <div class="field-group">
              <label>Upload Payment Screenshot</label>
            </div>
            <div class="bk-upload-area">
              <span class="fa fa-cloud-upload"></span>
              <div class="upl-title">Click to upload receipt</div>
              <div class="upl-sub">JPG, PNG or PDF accepted</div>
              <input type="file" name="payment_proof"
                     accept=".jpg,.jpeg,.png,.pdf" required
                     onchange="updateFileName(this)">
            </div>

            <!-- Submit -->
            <button type="submit" name="submit" class="btn-apt">
              <span class="fa fa-calendar-check-o"></span>
              Confirm Appointment
            </button>

          </form>
        </div>
      </div>
      <!-- ── END RIGHT COLUMN ── -->

    </div>
  </div>
</section>

<?php include_once('includes/footer.php'); ?>

<button onclick="topFunction()" id="movetop" title="Go to top">
  <span class="fa fa-long-arrow-up"></span>
</button>

<script>
  window.onscroll = function () { scrollFunction(); };
  function scrollFunction() {
    document.getElementById("movetop").style.display =
      (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) ? "block" : "none";
  }
  function topFunction() { document.body.scrollTop = 0; document.documentElement.scrollTop = 0; }

  $(function () {
    var today = new Date();
    var month = String(today.getMonth() + 1).padStart(2, '0');
    var day   = String(today.getDate()).padStart(2, '0');
    $('#adate').attr('min', today.getFullYear() + '-' + month + '-' + day);
  });

  function updateFileName(input) {
    var area = input.closest('.bk-upload-area');
    if (input.files && input.files[0]) {
      area.querySelector('.upl-title').textContent = input.files[0].name;
      area.querySelector('.upl-sub').textContent = 'File selected — click to change';
      area.style.borderColor = '#1a73e8';
      area.style.background = '#f0f5ff';
    }
  }
</script>

</body>
</html>