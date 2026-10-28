<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

$msg = "";

if (isset($_POST['login'])) {
    $adminuser = $_POST['username'];
    $password  = $_POST['password'];

    $loggedIn = false;

    /* ── 1. CHECK ADMIN TABLE ── */
    $stmt = $con->prepare("SELECT ID, Password FROM tbladmin WHERE UserName=?");
    $stmt->bind_param("s", $adminuser);
    $stmt->execute();
    $result = $stmt->get_result();
    $user   = $result->fetch_assoc();
    $stmt->close();

    if ($user) {
        if (password_verify($password, $user['Password']) || md5($password) == $user['Password']) {
            session_regenerate_id(true);
            $_SESSION['bpmsaid']    = $user['ID'];
            $_SESSION['bpms_role']  = 'Admin';
            $_SESSION['bpms_name']  = $adminuser;
            $loggedIn = true;
            header("Location: dashboard.php");
            exit();
        } else {
            $msg = "Invalid password.";
        }
    }

    /* ── 2. CHECK STAFF TABLE (only if not logged in yet) ── */
    if (!$loggedIn) {
        $stmt2 = $con->prepare("
            SELECT ID, Password, FullName 
            FROM tblstaff 
            WHERE UserName=? AND Status=1
        ");
        $stmt2->bind_param("s", $adminuser);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $staff   = $result2->fetch_assoc();
        $stmt2->close();

        if ($staff) {
            if (password_verify($password, $staff['Password']) || md5($password) == $staff['Password']) {
                session_regenerate_id(true);
                $_SESSION['bpmsaid']    = $staff['ID'];
                $_SESSION['bpms_role']  = 'Staff';
                $_SESSION['bpms_name']  = $staff['FullName'];
                header("Location: dashboard.php");
                exit();
            } else {
                $msg = "Invalid password.";
            }
        } else {
            if (empty($msg)) {
                $msg = "User not found.";
            }
        }
    }
}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
  <title>KNBMS|Admin Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <script type="application/x-javascript">
    addEventListener("load", function () { setTimeout(hideURLbar, 0); }, false);
    function hideURLbar() { window.scrollTo(0, 1); }
  </script>

  <link rel="icon" type="image/png" href="images/Icon.png">
  <link href="css/bootstrap.css" rel="stylesheet" type="text/css">
  <link href="css/style.css" rel="stylesheet" type="text/css">
  <link href="css/font-awesome.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Raleway:wght@300;400;600;700&display=swap" rel="stylesheet">

  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --black:     #0f0f10;
      --card-bg:   rgba(13,13,14,0.97);
      --cream:     #f8f8f0;
      --off-white: #f0ede7;
      --gray:      #888888;
      --border:    rgba(255,255,255,0.10);
      --accent:    #f0bb0f;
    }

    /* ── FULL PAGE BACKGROUND (original) ── */
    body {
      font-family: 'Raleway', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: url('./images/1.jpg') center center / cover no-repeat fixed;
      position: relative;
      padding: 1.5rem;
    }

    body::before {
      content: '';
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.75);
      filter: grayscale(100%);
    }

    /* ── WRAPPER ── */
    .login-wrapper {
      position: relative;
      z-index: 1;
      display: grid;
      grid-template-columns: 1fr 1fr;
      width: 100%;
      max-width: 860px;
      min-height: 530px;
      border: 0.5px solid var(--border);
      overflow: hidden;
    }

    /* ── LEFT: ADVERT PANEL ── */
    .advert-panel {
      background: transparent rgba(175, 176, 199, 0.92);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 2.5rem 2rem;
      position: relative;
      overflow: hidden;
      backdrop-filter: blur(4px);
    }

    .advert-panel::before {
      content: '';
      position: absolute;
      top: -60px; right: -60px;
      width: 220px; height: 220px;
      border-radius: 50%;
      background: rgba(255,255,255,0.08);
    }

    .advert-panel::after {
      content: '';
      position: absolute;
      bottom: -80px; left: -40px;
      width: 260px; height: 260px;
      border-radius: 50%;
      background: rgba(0,0,0,0.08);
    }

    .advert-logo {
      position: relative;
      z-index: 1;
    }

    .advert-logo h2 {
      font-family: 'Playfair Display', serif;
      font-size: 2rem;
      font-weight: 400;
      color: #ffffff;
      line-height: 1.2;
      margin-bottom: 0.3rem;
    }

    .advert-logo h2 em {
      font-style: italic;
      color: #dfb11c;
    }

    .advert-logo p {
      font-size: 0.58rem;
      letter-spacing: 0.25em;
      text-transform: uppercase;
      color: rgba(255, 255, 255, 0.65);
    }

    .advert-body {
      position: relative;
      z-index: 1;
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 2rem 0 1.5rem;
    }

    .advert-body h3 {
      font-family: 'Playfair Display', serif;
      font-size: 1.45rem;
      font-weight: 600;
      color: #f8f8f8;
      line-height: 1.4;
      margin-bottom: 1rem;
    }

    .advert-body p {
      font-size: 0.82rem;
      color: rgba(255, 253, 251, 0.72);
      line-height: 1.7;
      margin-bottom: 1.4rem;
    }

    .advert-features {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 0.6rem;
    }

    .advert-features li {
      display: flex;
      align-items: center;
      gap: 9px;
      font-size: 0.78rem;
      color: #ffffff;
      font-weight: 600;
      letter-spacing: 0.02em;
    }

    .advert-features li .fa {
      font-size: 13px;
      color: #f1f1ee;
      width: 16px;
      text-align: center;
    }

    .advert-footer {
      position: relative;
      z-index: 1;
      font-size: 0.6rem;
      letter-spacing: 0.15em;
      text-transform: uppercase;
      color: rgba(26,16,8,0.45);
      border-top: 0.5px solid rgba(26,16,8,0.15);
      padding-top: 1rem;
    }

    /* ── RIGHT: LOGIN PANEL ── */
    .login-panel {
      background: var(--card-bg);
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 2.8rem 2.4rem;
      border-left: 0.5px solid var(--border);
    }

    .login-brand {
      margin-bottom: 2rem;
      padding-bottom: 1.4rem;
      border-bottom: 0.5px solid var(--border);
    }

    .login-brand h3 {
      font-family: 'Playfair Display', serif;
      font-size: 1.4rem;
      font-weight: 400;
      color: var(--cream);
      letter-spacing: 0.02em;
      margin-bottom: 0.25rem;
    }

    .login-brand p {
      font-size: 0.6rem;
      letter-spacing: 0.22em;
      text-transform: uppercase;
      color: var(--gray);
    }

    /* ── ERROR ── */
    .error-msg {
      font-size: 0.72rem;
      letter-spacing: 0.05em;
      text-align: center;
      color: #e74c3c;
      background: rgba(231,76,60,0.08);
      border: 0.5px solid rgba(231,76,60,0.2);
      padding: 0.6rem 1rem;
      margin-bottom: 1.2rem;
    }

    /* ── FORM ── */
    .form-group { margin-bottom: 1rem; }

    .input-icon-wrap {
      position: relative;
      display: flex;
      align-items: center;
    }

    .input-icon-wrap .field-icon {
      position: absolute;
      left: 13px;
      color: var(--gray);
      font-size: 14px;
      pointer-events: none;
      z-index: 2;
    }

    .input-icon-wrap .toggle-pw {
      position: absolute;
      right: 13px;
      color: var(--gray);
      font-size: 14px;
      cursor: pointer;
      z-index: 2;
      transition: color 0.2s;
    }

    .input-icon-wrap .toggle-pw:hover { color: var(--cream); }

    .form-control {
      width: 100%;
      padding: 0.82rem 2.5rem 0.82rem 2.4rem !important;
      background: rgba(255,255,255,0.05) !important;
      border: 0.5px solid rgba(255,255,255,0.12) !important;
      border-radius: 0 !important;
      color: var(--cream) !important;
      font-family: 'Raleway', sans-serif;
      font-size: 0.9rem;
      letter-spacing: 0.04em;
      outline: none;
      transition: border-color 0.2s, background 0.2s;
    }

    .form-control::placeholder { color: #3a3a3a; font-size: 0.75rem; }

    .form-control:focus {
      border-color: rgba(201,169,110,0.5) !important;
      background: rgba(255,255,255,0.08) !important;
      box-shadow: none !important;
      color: var(--cream) !important;
    }

    /* ── BUTTON ── */
    .btn-login {
      width: 100%;
      padding: 0.88rem;
      margin-top: 1.3rem;
      background: var(--accent) !important;
      color: #1a1008 !important;
      border: none !important;
      border-radius: 0 !important;
      font-family: 'Raleway', sans-serif;
      font-size: 0.68rem;
      letter-spacing: 0.2em;
      text-transform: uppercase;
      font-weight: 700;
      cursor: pointer;
      transition: background 0.2s;
    }

    .btn-login:hover { background: var(--off-white) !important; }

    /* ── LINKS ── */
    .login-links {
      margin-top: 1.4rem;
      padding-top: 1.1rem;
      border-top: 0.5px solid var(--border);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .login-links a {
      font-size: 0.72rem;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: var(--gray);
      text-decoration: none;
      transition: color 0.2s;
    }

    .login-links a:hover { color: var(--cream); }

    /* ── RESPONSIVE: tablet ≤768px → stack ── */
    @media (max-width: 768px) {
      .login-wrapper {
        grid-template-columns: 1fr;
        max-width: 440px;
      }

      .advert-panel { padding: 2rem 1.8rem 1.6rem; }
      .advert-body h3 { font-size: 1.2rem; }

      .login-panel {
        border-left: none;
        border-top: 0.5px solid var(--border);
        padding: 2.2rem 1.8rem;
      }
    }

    /* ── RESPONSIVE: mobile ≤480px → full screen ── */
    @media (max-width: 480px) {
      body { padding: 0; align-items: stretch; }

      .login-wrapper {
        max-width: 100%;
        min-height: 100vh;
        border: none;
      }

      .advert-panel { padding: 1.6rem 1.4rem 1.2rem; }
      .advert-body  { padding: 1rem 0; }
      .advert-body h3 { font-size: 1.1rem; }
      .advert-body p  { display: none; }

      .login-panel { padding: 1.8rem 1.4rem; }
      .login-brand h3 { font-size: 1.2rem; }
    }
  </style>
</head>
<body>

<div class="login-wrapper">

  <!-- ── LEFT: ADVERT PANEL ── -->
  <div class="advert-panel">

    <div class="advert-logo">
      <h2>Kuya <em>Nash</em></h2>
      <p>Barbershop &amp; Wellness</p>
    </div>

    <div class="advert-body">
      <h3>Your Community's Grooming Hub</h3>
      <p>Professional cuts, styling, facials, and full spa services — all in one place. Walk-ins welcome every day.</p>
      <ul class="advert-features">
        <li><span class="fa fa-cut"></span> Haircuts &amp; Hair Styling</li>
        <li><span class="fa fa-tint"></span> Colouring &amp; Texturing</li>
        <li><span class="fa fa-leaf"></span> Natural Facials &amp; Waxing</li>
        <li><span class="fa fa-clock-o"></span> Walk-ins Welcome Daily</li>
      </ul>
    </div>

    <div class="advert-footer">
      Kuya Nash Barbershop &mdash; Est. 2024
    </div>

  </div>

  <!-- ── RIGHT: LOGIN PANEL ── -->
  <div class="login-panel">

    <div class="login-brand">
      <h3>Admin Portal</h3>
      <p>Barbershop Service Management System</p>
    </div>

    <?php if ($msg): ?>
      <div class="error-msg">
        <span class="fa fa-exclamation-circle"></span>
        <?php echo htmlspecialchars($msg); ?>
      </div>
    <?php endif; ?>

    <form method="post" autocomplete="off">

      <div class="form-group">
        <div class="input-icon-wrap">
          <span class="fa fa-user field-icon"></span>
          <input type="text" name="username" class="form-control"
                 placeholder="Username" required>
        </div>
      </div>

      <div class="form-group">
        <div class="input-icon-wrap">
          <span class="fa fa-lock field-icon"></span>
          <input type="password" name="password" id="adminPassword"
                 class="form-control" placeholder="Password" required>
          <span class="fa fa-eye-slash toggle-pw" id="toggleAdminPw"
                title="Show / hide password"></span>
        </div>
      </div>

      <button type="submit" name="login" class="btn btn-login">Sign In</button>

      <div class="login-links">
        <a href="../index.php"><span class="fa fa-arrow-left"></span> Back to Site</a>
        <a href="forgot-password.php">Forgot Password?</a>
      </div>

    </form>

  </div>

</div>

<script>
  document.getElementById('toggleAdminPw').addEventListener('click', function () {
    var pw = document.getElementById('adminPassword');
    var hidden = pw.type === 'password';
    pw.type = hidden ? 'text' : 'password';
    this.className = hidden ? 'fa fa-eye toggle-pw' : 'fa fa-eye-slash toggle-pw';
  });
</script>

</body>
</html>