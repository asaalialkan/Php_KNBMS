<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// ── CSRF token generation ──────────────────────────────────────────────────
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// ── Form processing ────────────────────────────────────────────────────────
if (isset($_POST['submit'])) {

    // 1. CSRF check
    if (
        empty($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        die("<script>alert('Invalid request. Please try again.'); window.location.href='forgot-password.php';</script>");
    }

    // 2. Sanitize inputs
    $email     = trim($_POST['email']       ?? '');
    $contactno = trim($_POST['contactno']   ?? '');
    $newpw     = $_POST['newpassword']      ?? '';
    $confirmpw = $_POST['confirmpassword']  ?? '';

    // 3. Server-side validation
    $errors = [];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (!preg_match('/^09[0-9]{9}$/', $contactno)) {
        $errors[] = 'Mobile number must be 11 digits and start with 09.';
    }

    if (strlen($newpw) < 8) {
        $errors[] = 'New password must be at least 8 characters.';
    }

    if ($newpw !== $confirmpw) {
        $errors[] = 'New password and confirm password do not match.';
    }

    if (!empty($errors)) {
        $msg = implode('\\n', $errors);
        echo "<script>alert('$msg');</script>";

    } else {
        // 4. Check if user exists — prepared statement
        $stmt = $con->prepare(
            "SELECT ID FROM tbluser
             WHERE Email = ? AND MobileNumber = ?
             LIMIT 1"
        );
        $stmt->bind_param("ss", $email, $contactno);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            echo "<script>alert('Invalid details. Please try again.');</script>";
        } else {
            $stmt->close();

            // 5. Update password — prepared statement
            //    password_hash() replaces md5()
            $hash = password_hash($newpw, PASSWORD_DEFAULT);

            $upd = $con->prepare(
                "UPDATE tbluser SET Password = ?
                 WHERE Email = ? AND MobileNumber = ?"
            );
            $upd->bind_param("sss", $hash, $email, $contactno);

            if ($upd->execute()) {
                // Regenerate CSRF token after successful reset
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                echo "<script>alert('Password successfully changed. Please login with your new password.'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Something went wrong. Please try again.');</script>";
            }
            $upd->close();
        }

        if (isset($stmt) && !$stmt->errno) {
            $stmt->close();
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <title>Barbershop Management System | Forgot Password</title>
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
      font-size: 22px;
      color: #1a73e8 !important;
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
    .cont-right p a:hover { color: #1a73e8; }

    /* ── Contact/Form section layout ── */
    .contact-view {
      display: grid;
      grid-template-columns: 1fr 1.4fr;
      gap: 50px;
      align-items: start;
    }
    @media (max-width: 767px) {
      .contact-view { grid-template-columns: 1fr; }
    }

    /* ── Form card ── */
    .reset-card {
      background: #fff;
      border-radius: 8px;
      padding: 2rem 2.2rem;
      box-shadow: 0 2px 16px rgba(0,0,0,0.08);
    }

    .reset-card h3 {
      font-size: 24px;
      color: #2b4052;
      margin-bottom: 0.4rem;
      font-weight: 600;
    }

    .reset-card p.subtitle {
      font-size: 14px;
      color: #888;
      margin-bottom: 1.5rem;
    }

    /* ── Form groups ── */
    .field-group {
      margin-bottom: 1.1rem;
    }
    .field-group label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: #444;
      margin-bottom: 5px;
      letter-spacing: 0.03em;
      text-transform: uppercase;
    }
    .field-group input {
      width: 100%;
      padding: 11px 14px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 15px;
      color: #333;
      background: #f9f9f9;
      outline: none;
      transition: border-color 0.2s, background 0.2s;
    }
    .field-group input:focus {
      border-color: #1a73e8;
      background: #fff;
    }

    /* ── Password hint ── */
    .hint {
      font-size: 12px;
      color: #999;
      margin-top: 4px;
    }

    /* ── Submit button ── */
    .btn-reset {
      width: 100%;
      padding: 12px;
      margin-top: 1.2rem;
      background: #1a73e8;
      color: #fff;
      border: none;
      border-radius: 4px;
      font-size: 15px;
      font-weight: 600;
      letter-spacing: 0.05em;
      cursor: pointer;
      transition: background 0.2s;
    }
    .btn-reset:hover {
      background: #0d47a1;
    }

    /* ── Sign in redirect ── */
    .signin-link {
      text-align: center;
      margin-top: 1rem;
      font-size: 14px;
      color: #777;
    }
    .signin-link a {
      color: #1a73e8;
      font-weight: 600;
    }
    .signin-link a:hover {
      color: #0d47a1;
      text-decoration: underline;
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
        <h4 class="title">Forgot Password</h4>
      </div>
    </div>
  </div>

  <div class="breadcrumbs-sub">
    <div class="container">
      <ul class="breadcrumbs-custom-path">
        <li><a href="index.php">Home <span class="fa fa-angle-right" aria-hidden="true"></span></a></li>
        <li class="active">Forgot Password</li>
      </ul>
    </div>
  </div>
</section>
<!-- //breadcrumb -->

<!-- ── FORGOT PASSWORD SECTION ── -->
<section class="w3l-contact-info-main" id="forgot-password">
  <div class="contact-sec">
    <div class="container">
      <div class="contact-view">

        <!-- LEFT: Contact Info -->
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
              <p class="para">
                <a href="tel:<?php echo htmlspecialchars($row['MobileNumber']); ?>">
                  +<?php echo htmlspecialchars($row['MobileNumber']); ?>
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
          <p class="para">Contact information unavailable.</p>
          <?php endif; ?>
        </div><!-- /cont-details -->

        <!-- RIGHT: Reset Password Form -->
        <div class="reset-card">
          <h3>Reset Your Password</h3>
          <p class="subtitle">Enter your registered email and mobile number to set a new password.</p>

          <form method="post" autocomplete="off">
            <input type="hidden" name="csrf_token"
                   value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

            <div class="field-group">
              <label for="email">Email Address</label>
              <input type="email" id="email" name="email"
                     placeholder="e.g. juan@email.com" required>
            </div>

            <div class="field-group">
              <label for="contactno">Mobile Number</label>
              <input type="text" id="contactno" name="contactno"
                     placeholder="e.g. 09123456789"
                     pattern="[0-9]+" maxlength="11" required>
            </div>

            <div class="field-group">
              <label for="newpassword">New Password</label>
              <input type="password" id="newpassword" name="newpassword"
                     placeholder="Enter a strong password" required>
              <p class="hint">At least 8 characters recommended.</p>
            </div>

            <div class="field-group">
              <label for="confirmpassword">Confirm Password</label>
              <input type="password" id="confirmpassword" name="confirmpassword"
                     placeholder="Repeat your new password" required>
            </div>

            <button type="submit" name="submit" class="btn-reset">Reset Password</button>
          </form>

          <p class="signin-link">
            Remembered your password? <a href="login.php">Sign In</a>
          </p>
        </div><!-- /reset-card -->

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