<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (isset($_POST['login'])) {
    $emailcon = trim($_POST['emailcont']);
    $password = $_POST['password'];

    // Prepared statement to prevent SQL Injection
    $stmt = mysqli_prepare($con, "SELECT ID, Password FROM tbluser WHERE Email = ? OR MobileNumber = ? LIMIT 1");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $emailcon, $emailcon);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $ret = mysqli_fetch_assoc($result);

        if ($ret) {
            $valid = password_verify($password, $ret['Password']) || (md5($password) === $ret['Password']);

            if ($valid) {
                session_regenerate_id(true);
                $_SESSION['bpmsuid'] = $ret['ID'];
                header('Location: index.php');
                exit();
            } else {
                echo "<script>alert('Invalid password. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('No account found with that email or mobile number.');</script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Database query error. Please try again later.');</script>";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <title>Barbershop Management System | Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="assets/css/style-starter.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Josefin+Slab:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="icon" href="images/favicon.ico">

  <style>
    /* ── Navbar ── */
    .w3l-header-4 .absolute-top,
    .w3l-header-4 header.active,
    .w3l-header-4 header.nav-fixed { background: #000000 !important; border-bottom: none; }
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
    @media (max-width: 767px) { .bk-grid { grid-template-columns: 1fr; } }

    /* ── Cards ── */
    .bk-info-card, .bk-form-card {
      background: #fff;
      border-radius: 8px;
      border: 1px solid #e0e4ea;
      overflow: hidden;
    }
    .bk-info-card { margin-bottom: 20px; }

    .bk-card-header {
      background: #1a73e8;
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

    /* ── Quick links card ── */
    .bk-tip-item {
      display: flex; gap: 10px; align-items: flex-start;
      padding: 8px 0; border-bottom: 1px solid #f0f2f5; font-size: 12px;
    }
    .bk-tip-item:last-child { border-bottom: none; }
    .bk-tip-item .fa { color: #1a73e8; font-size: 13px; margin-top: 2px; flex-shrink: 0; }
    .bk-tip-item span { color: #666; line-height: 1.5; }

    /* ── Form ── */
    .bk-form-body { padding: 1.8rem 2rem; }

    .field-group { margin-bottom: 1.1rem; }
    .field-group label {
      display: block; font-size: 11px; font-weight: 600;
      color: #555; margin-bottom: 5px;
      letter-spacing: 0.06em; text-transform: uppercase;
    }

    /* ── Input with icon wrapper ── */
    .input-icon-wrap {
      position: relative;
    }
    .input-icon-wrap .fa-user,
    .input-icon-wrap .fa-lock {
      position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
      color: #b0bcd0; font-size: 14px; pointer-events: none; z-index: 1;
    }
    .input-icon-wrap input {
      padding-left: 38px !important;
      padding-right: 38px !important; /* Preserves space for toggle-pw icon */
    }

    /* ── Toggle password ── */
    .input-icon-wrap .toggle-pw {
      position: absolute; right: 13px; top: 50%; transform: translateY(-50%);
      color: #b0bcd0; font-size: 14px; cursor: pointer;
      pointer-events: all; z-index: 2;
    }
    .input-icon-wrap .toggle-pw:hover { color: #1a73e8; }

    .field-group input {
      width: 100%; box-sizing: border-box;
      padding: 10px 13px; border: 1px solid #dde1ea;
      border-radius: 5px; font-size: 14px; color: #2b4052;
      background: #f8f9fc; outline: none;
      font-family: 'Poppins', sans-serif;
      transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
    }
    .field-group input:focus {
      border-color: #1a73e8; background: #fff;
      box-shadow: 0 0 0 3px rgba(26,115,232,0.1);
    }

    /* ── Forgot link ── */
    .forgot-link {
      text-align: right; margin-bottom: 1.2rem; margin-top: -0.4rem;
    }
    .forgot-link a { font-size: 12px; color: #1a73e8; text-decoration: none; }
    .forgot-link a:hover { color: #0d47a1; text-decoration: underline; }

    /* ── Section separator ── */
    .bk-sep {
      display: flex; align-items: center; gap: 10px;
      margin: 1.2rem 0;
    }
    .bk-sep::before, .bk-sep::after { content:''; flex:1; height:1px; background:#e0e4ea; }
    .bk-sep span {
      font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase;
      color: #aaa; font-weight: 600; white-space: nowrap;
    }

    /* ── Submit ── */
    .btn-login-submit {
      width: 100%; padding: 13px; background: #1a73e8; color: #fff;
      border: none; border-radius: 5px; font-size: 14px; font-weight: 600;
      letter-spacing: 0.06em; cursor: pointer; font-family: 'Poppins', sans-serif;
      transition: background 0.2s;
      display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-login-submit:hover { background: #0d47a1; }

    /* ── Signup link ── */
    .signup-link {
      text-align: center; margin-top: 1rem;
      font-size: 13px; color: #888;
    }
    .signup-link a { color: #1a73e8; font-weight: 600; text-decoration: none; }
    .signup-link a:hover { color: #0d47a1; text-decoration: underline; }
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
        <h4 class="title">Login</h4>
      </div>
    </div>
  </div>
  <div class="breadcrumbs-sub">
    <div class="container">
      <ul class="breadcrumbs-custom-path">
        <li><a href="index.php">Home <span class="fa fa-angle-right" aria-hidden="true"></span></a></li>
        <li class="active">Login</li>
      </ul>
    </div>
  </div>
</section>

<!-- LOGIN SECTION -->
<section class="bk-section">
  <div class="container">
    <div class="bk-grid">

      <!-- LEFT: Contact Info + Quick Links -->
      <div>
        <div class="bk-info-card">
          <div class="bk-card-header">
            <span class="fa fa-info-circle"></span>
            <h3>Contact Information</h3>
          </div>
          <div class="bk-card-body">
            <?php
              $ret = mysqli_query($con, "SELECT * FROM tblpage WHERE PageType='contactus'");
              if ($ret && mysqli_num_rows($ret) > 0):
                $row = mysqli_fetch_assoc($ret);
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

        <div class="bk-info-card">
          <div class="bk-card-header blue">
            <span class="fa fa-sign-in"></span>
            <h3>Quick Access</h3>
          </div>
          <div class="bk-card-body">
            <div class="bk-tip-item">
              <span class="fa fa-calendar-plus-o"></span>
              <span>Login to book your barber appointment online.</span>
            </div>
            <div class="bk-tip-item">
              <span class="fa fa-history"></span>
              <span>View your past and upcoming appointments.</span>
            </div>
            <div class="bk-tip-item">
              <span class="fa fa-credit-card"></span>
              <span>Track your payment verification status.</span>
            </div>
            <div class="bk-tip-item">
              <span class="fa fa-user-plus"></span>
              <span>No account yet? <a href="signup.php" style="color:#1a73e8;font-weight:600;">Sign up here</a>.</span>
            </div>
          </div>
        </div>
      </div>

      <!-- RIGHT: Login Form -->
      <div class="bk-form-card">
        <div class="bk-card-header">
          <span class="fa fa-lock"></span>
          <h3>Welcome Back</h3>
        </div>
        <div class="bk-form-body">

          <form method="post" autocomplete="off">

            <!-- Email / Mobile -->
            <div class="field-group">
              <label for="emailcont">Email or Mobile Number</label>
              <div class="input-icon-wrap">
                <span class="fa fa-user"></span>
                <input type="text" id="emailcont" name="emailcont"
                       placeholder="e.g. juan@email.com or 09123456789" required>
              </div>
            </div>

            <!-- Password -->
            <div class="field-group">
              <label for="password">Password</label>
              <div class="input-icon-wrap">
                <span class="fa fa-lock"></span>
                <input type="password" id="password" name="password"
                       placeholder="Enter your password" required>
                <span class="fa fa-eye toggle-pw" id="toggle-pw" onclick="togglePassword()" title="Toggle password visibility"></span>
              </div>
            </div>

            <!-- Forgot password -->
            <div class="forgot-link">
              <a href="forgot-password.php">Forgot Password?</a>
            </div>

            <!-- Submit -->
            <button type="submit" name="login" class="btn-login-submit">
              <span class="fa fa-sign-in"></span>
              Sign In
            </button>

            <div class="bk-sep"><span>New here?</span></div>

            <a href="signup.php" style="
              display:flex; align-items:center; justify-content:center; gap:8px;
              width:100%; box-sizing:border-box; padding:12px;
              border:1px solid #dde1ea; border-radius:5px;
              font-size:14px; font-weight:600; color:#2b4052;
              text-decoration:none; background:#f8f9fc;
              transition:border-color 0.2s, background 0.2s;
            " onmouseover="this.style.borderColor='#1a73e8';this.style.background='#f0f5ff';"
               onmouseout="this.style.borderColor='#dde1ea';this.style.background='#f8f9fc';">
              <span class="fa fa-user-plus"></span>
              Create an Account
            </a>

          </form>

        </div>
      </div>

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

  function togglePassword() {
    var input = document.getElementById('password');
    var icon  = document.getElementById('toggle-pw');
    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
    } else {
      input.type = 'password';
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
    }
  }
</script>

</body>
</html>