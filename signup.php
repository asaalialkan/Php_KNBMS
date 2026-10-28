<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (isset($_POST['submit'])) {
  $fname    = mysqli_real_escape_string($con, $_POST['firstname']);
  $lname    = mysqli_real_escape_string($con, $_POST['lastname']);
  $contno   = mysqli_real_escape_string($con, $_POST['mobilenumber']);
  $email    = mysqli_real_escape_string($con, $_POST['email']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $check = mysqli_query($con,
    "SELECT Email FROM tbluser WHERE Email='$email' OR MobileNumber='$contno'"
  );

  if (mysqli_num_rows($check) > 0) {
    echo "<script>alert('This email or mobile number is already linked to an account.');</script>";
  } else {
    $query = mysqli_query($con,
      "INSERT INTO tbluser (FirstName, LastName, MobileNumber, Email, Password)
       VALUES ('$fname','$lname','$contno','$email','$password')"
    );
    if ($query) {
      echo "<script>alert('You have successfully registered!'); window.location.href='login.php';</script>";
    } else {
      echo "<script>alert('Something went wrong. Please try again.');</script>";
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <title>Barbershop Management System | Sign Up</title>
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

    /* ── Tips card ── */
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
    .field-group input.error { border-color: #e53935; }
    .field-group input.success { border-color: #43a047; }

    .hint { font-size: 11px; color: #aaa; margin-top: 4px; }
    .hint.error-msg { color: #e53935; display: none; }

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

    /* ── Password strength bar ── */
    .strength-bar {
      height: 4px; border-radius: 2px; background: #eee;
      margin-top: 6px; overflow: hidden;
    }
    .strength-fill {
      height: 100%; width: 0; border-radius: 2px;
      transition: width 0.3s, background 0.3s;
    }
    .strength-label { font-size: 11px; color: #aaa; margin-top: 3px; }

    /* ── Submit ── */
    .btn-signup {
      width: 100%; padding: 13px; background: #1a73e8; color: #fff;
      border: none; border-radius: 5px; font-size: 14px; font-weight: 600;
      letter-spacing: 0.06em; cursor: pointer; font-family: 'Poppins', sans-serif;
      transition: background 0.2s;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      margin-top: 0.4rem;
    }
    .btn-signup:hover { background: #0d47a1; }

    /* ── Login link ── */
    .login-link {
      text-align: center; margin-top: 1rem;
      font-size: 13px; color: #888;
    }
    .login-link a { color: #1a73e8; font-weight: 600; text-decoration: none; }
    .login-link a:hover { color: #0d47a1; text-decoration: underline; }
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
        <h4 class="title">Sign Up</h4>
      </div>
    </div>
  </div>
  <div class="breadcrumbs-sub">
    <div class="container">
      <ul class="breadcrumbs-custom-path">
        <li><a href="index.php">Home <span class="fa fa-angle-right" aria-hidden="true"></span></a></li>
        <li class="active">Sign Up</li>
      </ul>
    </div>
  </div>
</section>

<!-- SIGNUP SECTION -->
<section class="bk-section">
  <div class="container">
    <div class="bk-grid">

      <!-- LEFT: Contact Info + Tips -->
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

        <div class="bk-info-card">
          <div class="bk-card-header blue">
            <span class="fa fa-lightbulb-o"></span>
            <h3>Why Register?</h3>
          </div>
          <div class="bk-card-body">
            <div class="bk-tip-item">
              <span class="fa fa-calendar-check-o"></span>
              <span>Book appointments online anytime, anywhere.</span>
            </div>
            <div class="bk-tip-item">
              <span class="fa fa-history"></span>
              <span>Track your appointment history and status.</span>
            </div>
            <div class="bk-tip-item">
              <span class="fa fa-bell-o"></span>
              <span>Get updates on your booking and payment.</span>
            </div>
            <div class="bk-tip-item">
              <span class="fa fa-lock"></span>
              <span>Your data is safe and secure with us.</span>
            </div>
          </div>
        </div>
      </div>

      <!-- RIGHT: Signup Form -->
      <div class="bk-form-card">
        <div class="bk-card-header">
          <span class="fa fa-user-plus"></span>
          <h3>Create an Account</h3>
        </div>
        <div class="bk-form-body">

          <form method="post" name="signup" onsubmit="return checkpass();" autocomplete="off">

            <!-- Name row -->
            <div class="field-row">
              <div class="field-group">
                <label for="firstname">First Name</label>
                <input type="text" id="firstname" name="firstname"
                       placeholder="e.g. Juan" required>
              </div>
              <div class="field-group">
                <label for="lastname">Last Name</label>
                <input type="text" id="lastname" name="lastname"
                       placeholder="e.g. Dela Cruz" required>
              </div>
            </div>

            <!-- Mobile -->
            <div class="field-group">
              <label for="mobilenumber">Mobile Number</label>
              <input type="text" id="mobilenumber" name="mobilenumber"
                     placeholder="e.g. 09123456789"
                     pattern="[0-9]{11}" maxlength="11" minlength="11"
                     title="Mobile number must be exactly 11 digits" required
                     oninput="validateMobile(this)">
              <p class="hint">Must be exactly 11 digits.</p>
              <p class="hint error-msg" id="mobile-error">Please enter a valid 11-digit mobile number.</p>
            </div>

            <!-- Email -->
            <div class="field-group">
              <label for="email">Email Address</label>
              <input type="email" id="email" name="email"
                     placeholder="e.g. juan@email.com" required>
            </div>

            <div class="bk-sep"><span>Security</span></div>

            <!-- Password -->
            <div class="field-group">
              <label for="password">Password</label>
              <input type="password" id="password" name="password"
                     placeholder="Enter a strong password" required
                     oninput="checkStrength(this.value)">
              <div class="strength-bar"><div class="strength-fill" id="strength-fill"></div></div>
              <p class="strength-label" id="strength-label">Password strength</p>
            </div>

            <!-- Confirm Password -->
            <div class="field-group">
              <label for="repeatpassword">Confirm Password</label>
              <input type="password" id="repeatpassword" name="repeatpassword"
                     placeholder="Repeat your password" required
                     oninput="validateConfirm(this)">
              <p class="hint error-msg" id="confirm-error">Passwords do not match.</p>
            </div>

            <button type="submit" name="submit" class="btn-signup">
              <span class="fa fa-user-plus"></span>
              Create Account
            </button>

          </form>

          <p class="login-link">
            Already have an account? <a href="login.php">Sign In</a>
          </p>

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

  function checkpass() {
    const pw  = document.signup.password.value;
    const rpw = document.signup.repeatpassword.value;
    if (pw !== rpw) {
      alert('Password and Confirm Password do not match.');
      document.signup.repeatpassword.focus();
      return false;
    }
    return true;
  }

  function validateMobile(input) {
    var err = document.getElementById('mobile-error');
    if (input.value.length === 11 && /^[0-9]{11}$/.test(input.value)) {
      input.classList.remove('error');
      input.classList.add('success');
      err.style.display = 'none';
    } else {
      input.classList.remove('success');
      input.classList.add('error');
      err.style.display = 'block';
    }
  }

  function validateConfirm(input) {
    var pw  = document.getElementById('password').value;
    var err = document.getElementById('confirm-error');
    if (input.value && input.value !== pw) {
      input.classList.add('error');
      input.classList.remove('success');
      err.style.display = 'block';
    } else if (input.value && input.value === pw) {
      input.classList.remove('error');
      input.classList.add('success');
      err.style.display = 'none';
    }
  }

  function checkStrength(pw) {
    var fill  = document.getElementById('strength-fill');
    var label = document.getElementById('strength-label');
    var score = 0;
    if (pw.length >= 8)              score++;
    if (/[A-Z]/.test(pw))           score++;
    if (/[0-9]/.test(pw))           score++;
    if (/[^A-Za-z0-9]/.test(pw))   score++;

    var widths = ['0%','25%','50%','75%','100%'];
    var colors = ['#eee','#e53935','#fb8c00','#fdd835','#43a047'];
    var labels = ['','Weak','Fair','Good','Strong'];

    fill.style.width      = widths[score];
    fill.style.background = colors[score];
    label.textContent     = labels[score];
    label.style.color     = colors[score];
  }
</script>

</body>
</html>