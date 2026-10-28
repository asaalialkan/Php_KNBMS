<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if(isset($_POST['submit']))
  {
    $contactno=$_POST['contactno'];
    $email=$_POST['email'];
    $query=mysqli_query($con,"select ID from tbladmin where Email='$email' and MobileNumber='$contactno'");
    $ret=mysqli_fetch_array($query);
    if($ret>0){
      $_SESSION['contactno']=$contactno;
      $_SESSION['email']=$email;
      header('location:reset-password.php');
    } else {
      $msg="Invalid Details. Please try again.";
    }
  }
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Kuya Nash Barbershop | Admin Forgot Password</title>

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

<style>
  *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

  :root {
    --black:      #0a0a0a;
    --gray-dark:  #1a1a1a;
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
  .forgot-card {
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

  /* ── ERROR MESSAGE ── */
  .error-msg {
    font-size: 0.72rem;
    text-align: center;
    color: #c0392b;
    background: rgba(192,57,43,0.08);
    border: 0.5px solid rgba(192,57,43,0.2);
    padding: 0.6rem 1rem;
    margin-bottom: 1.2rem;
    letter-spacing: 0.04em;
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

  .field-group input[type="text"],
  .field-group input[type="email"] {
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
  <div class="forgot-card">

    <!-- Brand -->
    <div class="card-brand">
      <h3>Kuya <em>Nash</em></h3>
      <span class="brand-sub">Admin Portal</span>
    </div>

    <!-- Title -->
    <div class="card-title">Forgot Password</div>

    <!-- Error -->
    <?php if(!empty($msg)): ?>
      <div class="error-msg"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>

    <!-- Form -->
    <form method="post" action="">

      <div class="field-group">
        <label class="field-label">Email Address</label>
        <input type="text" name="email" placeholder="Enter your admin email" required>
      </div>

      <div class="field-group">
        <label class="field-label">Mobile Number</label>
        <input type="text" name="contactno" placeholder="Enter your mobile number" required maxlength="10" pattern="[0-9]+">
      </div>

      <button type="submit" name="submit" class="btn-reset">Send Reset Link</button>

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