<?php
session_start();

require_once 'includes/dbconnection.php';

// Secure session check
if (!isset($_SESSION['bpmsaid']) || empty($_SESSION['bpmsaid'])) {
    header("Location: logout.php");
    exit();
}

$adminId = $_SESSION['bpmsaid'];
$message = '';
$messageType = '';

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $currentPassword = trim($_POST['currentpassword'] ?? '');
    $newPassword     = trim($_POST['newpassword'] ?? '');
    $confirmPassword = trim($_POST['confirmpassword'] ?? '');

    // Validation
    if (
        empty($currentPassword) ||
        empty($newPassword) ||
        empty($confirmPassword)
    ) {

        $message = "All fields are required.";
        $messageType = "danger";

    } elseif ($newPassword !== $confirmPassword) {

        $message = "Passwords do not match.";
        $messageType = "danger";

    } elseif (strlen($newPassword) < 8) {

        $message = "Password must be at least 8 characters.";
        $messageType = "danger";

    } else {

        // Get current password
        $stmt = mysqli_prepare(
            $con,
            "SELECT Password FROM tbladmin WHERE ID = ?"
        );

        mysqli_stmt_bind_param($stmt, "i", $adminId);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $admin = mysqli_fetch_assoc($result);

        mysqli_stmt_close($stmt);

        $validPassword = false;

        if ($admin) {

            // Support old MD5
            if ($admin['Password'] === md5($currentPassword)) {
                $validPassword = true;
            }

            // Modern password_hash
            if (password_verify($currentPassword, $admin['Password'])) {
                $validPassword = true;
            }
        }

        if ($validPassword) {

            // Secure password hash
            $hashedPassword = password_hash(
                $newPassword,
                PASSWORD_DEFAULT
            );

            $updateStmt = mysqli_prepare(
                $con,
                "UPDATE tbladmin SET Password = ? WHERE ID = ?"
            );

            mysqli_stmt_bind_param(
                $updateStmt,
                "si",
                $hashedPassword,
                $adminId
            );

            if (mysqli_stmt_execute($updateStmt)) {

                $message = "Password updated successfully.";
                $messageType = "success";

            } else {

                $message = "Something went wrong.";
                $messageType = "danger";
            }

            mysqli_stmt_close($updateStmt);

        } else {

            $message = "Current password is incorrect.";
            $messageType = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Change Password</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="css/font-awesome.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Scripts -->
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/modernizr.custom.js"></script>
    <script src="js/metisMenu.min.js"></script>
    <script src="js/custom.js"></script>

    <style>

        :root{
            --primary:#c8a96b;
            --dark:#0f0f0f;
            --card:#1b1b1b;
            --border:#2d2d2d;
            --text:#ffffff;
            --muted:#aaaaaa;
        }

        body{
            background:whitesmoke !important;
            font-family:'Inter',sans-serif;
        }

        #page-wrapper{
            background:whitesmoke !important;
            min-height:100vh;
            padding:40px 25px;
        }

        .password-container{
            max-width:650px;
            margin:auto;
        }

        .password-card{
            background:var(--card);
            border:1px solid var(--border);
            border-radius:24px;
            overflow:hidden;
            box-shadow:0 15px 40px rgba(0,0,0,0.35);
        }

        .card-header-custom{
            padding:40px 35px;
            text-align:center;
            border-bottom:1px solid var(--border);
            background:linear-gradient(
                135deg,
                #1d1d1d,
                #101010
            );
        }

        .icon-circle{
            width:80px;
            height:80px;
            border-radius:50%;
            background:rgba(200,169,107,0.15);
            color:var(--primary);
            display:flex;
            align-items:center;
            justify-content:center;
            margin:auto auto 20px;
            font-size:32px;
        }

        .card-header-custom h2{
            color:white;
            margin:0;
            font-size:34px;
            font-family:'Playfair Display',serif;
        }

        .card-header-custom p{
            margin-top:10px;
            color:var(--muted);
            font-size:14px;
        }

        .card-body-custom{
            padding:35px;
        }

        .form-group{
            margin-bottom:24px;
        }

        .form-group label{
            display:block;
            color:#ddd;
            margin-bottom:10px;
            font-size:14px;
            font-weight:500;
        }

        .form-control{
            height:55px;
            background:#111 !important;
            border:1px solid var(--border) !important;
            border-radius:14px !important;
            color:white !important;
            box-shadow:none !important;
            padding:0 16px !important;
            font-size:15px;
        }

        .form-control:focus{
            border-color:var(--primary) !important;
        }

        .form-control::placeholder{
            color:#777;
        }

        .password-note{
            margin-top:8px;
            font-size:12px;
            color:#888;
        }

        .btn-update{
            width:100%;
            height:55px;
            border:none;
            border-radius:14px;
            background:var(--primary);
            color:#111;
            font-weight:600;
            font-size:15px;
            transition:0.2s;
        }

        .btn-update:hover{
            opacity:0.9;
        }

        .alert{
            border-radius:14px;
            border:none;
            font-size:14px;
        }

        @media(max-width:768px){

            #page-wrapper{
                padding:20px 15px;
            }

            .card-header-custom{
                padding:30px 20px;
            }

            .card-body-custom{
                padding:25px 20px;
            }

            .card-header-custom h2{
                font-size:28px;
            }
        }

    </style>

</head>

<body class="cbp-spmenu-push">

<div class="main-content">

    <!-- Sidebar -->
    <?php include_once('includes/sidebar.php'); ?>

    <!-- Header -->
    <?php include_once('includes/header.php'); ?>

    <!-- Main Content -->
    <div id="page-wrapper">

        <div class="password-container">

            <div class="password-card">

                <div class="card-header-custom">

                    <div class="icon-circle">
                        <i class="fa fa-lock"></i>
                    </div>

                    <h2>Change Password</h2>

                    <p>
                        Keep your administrator account secure
                    </p>

                </div>

                <div class="card-body-custom">

                    <?php if (!empty($message)): ?>

                        <div class="alert alert-<?php echo $messageType; ?>">
                            <?php echo htmlspecialchars($message); ?>
                        </div>

                    <?php endif; ?>

                    <form method="POST">

                        <div class="form-group">

                            <label>Current Password</label>

                            <input
                                type="password"
                                name="currentpassword"
                                class="form-control"
                                placeholder="Enter current password"
                                required
                            >

                        </div>

                        <div class="form-group">

                            <label>New Password</label>

                            <input
                                type="password"
                                name="newpassword"
                                class="form-control"
                                placeholder="Enter new password"
                                minlength="8"
                                required
                            >

                            <div class="password-note">
                                Minimum 8 characters required
                            </div>

                        </div>

                        <div class="form-group">

                            <label>Confirm Password</label>

                            <input
                                type="password"
                                name="confirmpassword"
                                class="form-control"
                                placeholder="Confirm new password"
                                minlength="8"
                                required
                            >

                        </div>

                        <button type="submit" class="btn-update">
                            <i class="fa fa-save me-2"></i>
                            Update Password
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <!-- Footer -->
    <?php include_once('includes/footer.php'); ?>

</div>

<!-- Sidebar Toggle -->
<script src="js/classie.js"></script>

<script>

    var menuLeft = document.getElementById('cbp-spmenu-s1'),
        showLeftPush = document.getElementById('showLeftPush'),
        body = document.body;

    if(showLeftPush){

        showLeftPush.onclick = function () {

            classie.toggle(this, 'active');
            classie.toggle(body, 'cbp-spmenu-push-toright');
            classie.toggle(menuLeft, 'cbp-spmenu-open');
        };
    }

</script>

<!-- Scroll Scripts -->
<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>

<!-- Bootstrap -->
<script src="js/bootstrap.js"></script>

</body>
</html>