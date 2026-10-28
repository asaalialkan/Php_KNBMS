<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
error_reporting(0);

if(isset($_POST['submit']))
  {
    $contactno=$_SESSION['contactno'];
    $email=$_SESSION['email'];
    $password=md5($_POST['newpassword']);
    $query=mysqli_query($con,"update tbladmin set Password='$password' where Email='$email' && MobileNumber='$contactno'");
    if($query) {
      echo "<script>alert('Password successfully changed');</script>";
      session_destroy();
    }
  }
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Kuya Nash Barbershop | Admin Reset Password</title>

<script type="application/x-javascript">
addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
function hideURLbar(){ window.scrollTo(0,1); }
</script>

<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
<link href="css/style.css" rel='stylesheet' type='text/css' />
<link href="css/font-awesome.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;1,400&family=Raleway:wght@300;400;500;600&display=swap" rel="stylesheet">

<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/modernizr.custom.js"></script>
<link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
<script src="js/wow.min.js"></script>
<script> new WOW().init(); </script>
<script src="js/metisMenu.min.js"></script>
<script src="js/custom.js"></script>
<link href="css/custom.css" rel="stylesheet">

<script type="text/javascript">
function checkpass() {
  if(document.changepassword.newpassword.value != document.changepassword.confirmpassword.value) {
    alert('New Password and Confirm Password field does not match');
    document.changepassword.confirmpassword.focus();
    return false;
  }
  return true;
}
</script>

<style>
  *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

  :root {
    --black:      #0a0a0a;
    --gray-light: #888;
    --cream:      #e8e4dc;
    --off-white:  #f0ede7;
  }

  body {
    font-family: 'Raleway', sans-serif;
    background: url('../a1.jpg') center center / cover no-repeat fixed;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
  }

  body::before {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.78);
    filter: grayscale(100%);
  }

  .main-content {
    position: relative;
    z-index: 1;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
  }

  /* ── CARD ── */
  .reset-card {
    width: 100%;
    max-width: 400px;
    background: rgba(10,10,10,0.88);
    border: 0.5px solid rgba(255,255,255,0.1);
    backdrop-filter: blur(8px);
    padding: 2.8rem 2.5rem;
  }

  /* ── BRAND ── */
  .card-brand {
    text-align: center;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 0.5px solid rgba(255,255,255,0.07);
  }

  .card-brand h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.7rem;
    font-weight: 400;
    color: var(--cream);
    letter-spacing: 0.02em;
    margin-bottom: 0.3rem;
  }

  .card-brand h3 em {
    font-style: italic;
    color: var(--gray-light);
  }

  .card-brand .brand-sub {
    font-size: 0.6rem;
    letter-spacing: 0.22em;
    text-transform: uppercase;
    color: #444;
  }

  /* ── CARD TITLE ── */
  .card-title {
    font-size: 0.6rem;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: #555;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.8rem;
  }

  .card-title::after {
    content: '';
    flex: 1;
    height: 0.5px;
    background: #1f1f1f;
  }

  /* ── HINT TEXT ── */
  .card-hint {
    font-size: 0.72rem;
    color: #555;
    font-weight: 300;
    line-height: 1.7;
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 0.5px solid rgba(255,255,255,0.05);
  }

  /* ── FIELD GROUP ── */
  .field-group {
    margin-bottom: 1rem;
  }

  .field-label {
    display: block;
    font-size: 0.58rem;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: #444;
    margin-bottom: 0.4rem;
    font-weight: 500;
  }

  .field-group input[type="password"] {
    width: 100%;
    padding: 0.85rem 1rem;
    background: rgba(255,255,255,0.05) !important;
    border: 0.5px solid rgba(255,255,255,0.12) !important;
    border-radius: 0 !important;
    color: var(--cream) !important;
    font-family: 'Raleway', sans-serif;
    font-size: 0.78rem;
    letter-spacing: 0.04em;
    outline: none;
    transition: border-color 0.2s, background 0.2s;
  }

  .field-group input::placeholder { color: #444; font-size: 0.75rem; }

  .field-group input:focus {
    border-color: rgba(255,255,255,0.3) !important;
    background: rgba(255,255,255,0.08) !important;
    box-shadow: none !important;
  }

  /* ── SUBMIT ── */
  .btn-reset {
    width: 100%;
    padding: 0.9rem;
    margin-top: 0.5rem;
    background: var(--cream);
    color: var(--black);
    border: none;
    border-radius: 0;
    font-family: 'Raleway', sans-serif;
    font-size: 0.68rem;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
  }

  .btn-reset:hover { background: var(--off-white); }

  /* ── BOTTOM LINK ── */
  .card-footer-link {
    margin-top: 1.5rem;
    padding-top: 1.2rem;
    border-top: 0.5px solid rgba(255,255,255,0.06);
    text-align: center;
  }

  .card-footer-link a {
    font-size: 0.65rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: #555;
    text-decoration: none;
    transition: color 0.2s;
  }

  .card-footer-link a:hover { color: var(--cream); }
</style>

</head>
<body class="cbp-spmenu-push">

<div class="main-content">
  <div class="reset-card">

    <!-- Brand -->
    <div class="card-brand">
      <h3>Kuya <em>Nash</em></h3>
      <span class="brand-sub">Admin Portal</span>
    </div>

    <!-- Title -->
    <div class="card-title">Reset Password</div>

    <!-- Hint -->
    <p class="card-hint">Enter a new password for your admin account. Both fields must match before submitting.</p>

    <!-- Form -->
    <form method="post" action="" name="changepassword" onsubmit="return checkpass();">

      <div class="field-group">
        <label class="field-label">New Password</label>
        <input type="password" name="newpassword" placeholder="Enter new password" required>
      </div>

      <div class="field-group">
        <label class="field-label">Confirm Password</label>
        <input type="password" name="confirmpassword" placeholder="Confirm new password" required>
      </div>

      <button type="submit" name="submit" class="btn-reset">Reset Password</button>

    </form>

    <!-- Footer link -->
    <div class="card-footer-link">
      <a href="index.php">← Back to Sign In</a>
    </div>

  </div>
</div>

<script src="js/classie.js"></script>
<script>
  var menuLeft = document.getElementById('cbp-spmenu-s1'),
      showLeftPush = document.getElementById('showLeftPush'),
      body = document.body;

  if(showLeftPush) {
    showLeftPush.onclick = function() {
      classie.toggle(this, 'active');
      classie.toggle(body, 'cbp-spmenu-push-toright');
      classie.toggle(menuLeft, 'cbp-spmenu-open');
    };
  }
</script>

<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>
<script src="js/bootstrap.js"></script>

</body>
</html>