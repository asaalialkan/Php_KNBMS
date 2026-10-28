<?php
session_start();

require_once 'includes/dbconnection.php';

// Secure session validation
if (!isset($_SESSION['bpmsaid']) || empty($_SESSION['bpmsaid'])) {
    header("Location: logout.php");
    exit();
}

$adminId = $_SESSION['bpmsaid'];
$message = '';
$messageType = '';

// Update Profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $adminName = trim($_POST['adminname'] ?? '');
    $contactNo = trim($_POST['contactnumber'] ?? '');

    // Validation
    if (empty($adminName) || empty($contactNo)) {

        $message = "All fields are required.";
        $messageType = "danger";

    } elseif (!preg_match('/^[0-9]{10,15}$/', $contactNo)) {

        $message = "Invalid contact number.";
        $messageType = "danger";

    } else {

        // Prepared statement
        $stmt = mysqli_prepare(
            $con,
            "UPDATE tbladmin 
             SET AdminName = ?, MobileNumber = ?
             WHERE ID = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssi",
            $adminName,
            $contactNo,
            $adminId
        );

        if (mysqli_stmt_execute($stmt)) {

            $message = "Profile updated successfully.";
            $messageType = "success";

        } else {

            $message = "Something went wrong.";
            $messageType = "danger";
        }

        mysqli_stmt_close($stmt);
    }
}

// Fetch admin data
$stmt = mysqli_prepare(
    $con,
    "SELECT * FROM tbladmin WHERE ID = ?"
);

mysqli_stmt_bind_param($stmt, "i", $adminId);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$admin = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Admin Profile</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="css/font-awesome.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- JS -->
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/modernizr.custom.js"></script>
    <script src="js/metisMenu.min.js"></script>
    <script src="js/custom.js"></script>

    <style>

        :root{
            --primary:#c8a96b;
            --dark:#0f0f0f;
            --card:#1a1a1a;
            --border:#2c2c2c;
            --text:#ffffff;
            --muted:#9f9f9f;
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

        .profile-container{
            max-width:750px;
            margin:auto;
        }

        .profile-card{
            background:var(--card);
            border:1px solid var(--border);
            border-radius:24px;
            overflow:hidden;
            box-shadow:0 20px 60px rgba(0,0,0,0.35);
        }

        .profile-header{
            padding:45px 35px;
            text-align:center;
            border-bottom:1px solid var(--border);
            background:linear-gradient(
                135deg,
                #1b1b1b,
                #111111
            );
        }

        .profile-avatar{
            width:95px;
            height:95px;
            border-radius:50%;
            background:rgba(200,169,107,0.12);
            display:flex;
            align-items:center;
            justify-content:center;
            margin:auto auto 20px;
            color:var(--primary);
            font-size:38px;
        }

        .profile-header h2{
            margin:0;
            color:black;
            font-size:36px;
            font-family:'Playfair Display',serif;
        }

        .profile-header p{
            margin-top:10px;
            color:var(--muted);
            font-size:14px;
        }

        .profile-body{
            padding:35px;
        }

        .form-group{
            margin-bottom:24px;
        }

        .form-group label{
            display:block;
            margin-bottom:10px;
            color:#ddd;
            font-size:14px;
            font-weight:500;
        }

        .form-control{
            height:55px;
            border-radius:14px !important;
            background:#111 !important;
            border:1px solid var(--border) !important;
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

        .readonly{
            opacity:0.8;
            cursor:not-allowed;
        }

        .btn-update{
            width:100%;
            height:56px;
            border:none;
            border-radius:14px;
            background:var(--primary);
            color:#111;
            font-size:15px;
            font-weight:600;
            transition:0.2s ease;
        }

        .btn-update:hover{
            opacity:0.92;
        }

        .alert{
            border:none;
            border-radius:14px;
            font-size:14px;
        }

        @media(max-width:768px){

            #page-wrapper{
                padding:20px 15px;
            }

            .profile-header{
                padding:35px 20px;
            }

            .profile-body{
                padding:25px 20px;
            }

            .profile-header h2{
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

        <div class="profile-container">

            <div class="profile-card">

                <!-- Header -->
                <div class="profile-header">

                    <div class="profile-avatar">
                        <i class="fa fa-user"></i>
                    </div>

                    <h2>
                        <?= htmlspecialchars($admin['AdminName']) ?>
                    </h2>

                    <p>
                        Administrator Profile Settings
                    </p>

                </div>

                <!-- Body -->
                <div class="profile-body">

                    <?php if (!empty($message)): ?>

                        <div class="alert alert-<?= $messageType ?>">
                            <?= htmlspecialchars($message) ?>
                        </div>

                    <?php endif; ?>

                    <form method="POST">

                        <div class="form-group">

                            <label>Admin Name</label>

                            <input
                                type="text"
                                name="adminname"
                                class="form-control"
                                placeholder="Enter admin name"
                                required
                                value="<?= htmlspecialchars($admin['AdminName']) ?>"
                            >

                        </div>

                        <div class="form-group">

                            <label>Username</label>

                            <input
                                type="text"
                                class="form-control readonly"
                                readonly
                                value="<?= htmlspecialchars($admin['UserName']) ?>"
                            >

                        </div>

                        <div class="form-group">

                            <label>Contact Number</label>

                            <input
                                type="text"
                                name="contactnumber"
                                class="form-control"
                                placeholder="Enter contact number"
                                pattern="[0-9]{10,15}"
                                required
                                value="<?= htmlspecialchars($admin['MobileNumber']) ?>"
                            >

                        </div>

                        <div class="form-group">

                            <label>Email Address</label>

                            <input
                                type="email"
                                class="form-control readonly"
                                readonly
                                value="<?= htmlspecialchars($admin['Email']) ?>"
                            >

                        </div>

                        <button type="submit" class="btn-update">
                            <i class="fa fa-save me-2"></i>
                            Update Profile
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

<!-- Scrolling -->
<script src="js/jquery.nicescroll.js"></script>
<script src="js/scripts.js"></script>

<!-- Bootstrap -->
<script src="js/bootstrap.js"></script>

</body>
</html>