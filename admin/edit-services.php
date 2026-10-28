<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

/* SESSION CHECK */
if (!isset($_SESSION['bpmsaid']) || strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

/* GET ID SAFELY */
$eid = isset($_GET['editid']) ? intval($_GET['editid']) : 0;

/* UPDATE SERVICE */
if (isset($_POST['submit'])) {

    $sername = mysqli_real_escape_string($con, $_POST['sername']);
    $serdesc = mysqli_real_escape_string($con, $_POST['serdesc']);
    $cost    = mysqli_real_escape_string($con, $_POST['cost']);

    $query = mysqli_query($con, "
        UPDATE tblservices 
        SET ServiceName='$sername',
            ServiceDescription='$serdesc',
            Cost='$cost'
        WHERE ID='$eid'
    ");

    if ($query) {

        echo "<script>
                alert('Service has been Updated.');
                window.location.href='manage-services.php';
              </script>";
        exit();

    } else {

        echo "<script>
                alert('Something went wrong. Please try again.');
              </script>";
    }
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>

<title>BPMS | Update Services</title>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap -->
<link href="css/bootstrap.css" rel="stylesheet">

<!-- Font Awesome -->
<link href="css/font-awesome.css" rel="stylesheet">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<script src="js/jquery-1.11.1.min.js"></script>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    background:#ffffff;
    font-family:'Poppins', sans-serif;
}

/* MAIN CONTENT */
#page-wrapper{
    margin-left:350px;
    padding:35px;
    min-height:100vh;
    background:#fff;
}

/* PAGE TITLE */
.page-title{
    font-size:30px;
    font-weight:600;
    color:#111;
    margin-bottom:25px;
}

/* CARD */
.card-box{
    width:100%;
    max-width:950px;
    background:#fff;
    border-radius:18px;
    padding:35px;
    border:1px solid #e6e6e6;
    box-shadow:0 4px 15px rgba(0,0,0,0.05);
}

/* CARD TITLE */
.card-box h4{
    font-size:22px;
    font-weight:600;
    margin-bottom:30px;
    color:#111;
}

/* FORM */
.form-group{
    margin-bottom:25px;
}

.form-group label{
    display:block;
    margin-bottom:10px;
    font-size:14px;
    font-weight:500;
    color:#444;
}

.form-control{
    width:100%;
    border:1px solid #dcdcdc;
    border-radius:12px;
    padding:12px 15px;
    font-size:15px;
    background:#fff;
    color:#111;
    transition:0.3s;
}

.form-control:focus{
    border-color:#000;
    box-shadow:none;
    outline:none;
}

/* TEXTAREA */
textarea.form-control{
    min-height:120px;
    resize:none;
}

/* IMAGE */
.service-image{
    width:130px;
    height:130px;
    object-fit:cover;
    border-radius:12px;
    border:1px solid #ddd;
    padding:4px;
    background:#fff;
    margin-bottom:10px;
}

/* UPDATE IMAGE LINK */
.update-link{
    display:inline-block;
    margin-top:10px;
    color:#000;
    font-weight:500;
    text-decoration:none;
}

.update-link:hover{
    text-decoration:underline;
}

/* RESPONSIVE */
@media(max-width:768px){

    #page-wrapper{
        margin-left:0;
        padding:20px;
    }

    .card-box{
        padding:25px;
    }

    .page-title{
        font-size:24px;
    }
}

</style>

</head>

<body class="cbp-spmenu-push">

<div class="main-content">

    <!-- SIDEBAR -->
    <?php include_once('includes/sidebar.php');?>

    <!-- MAIN -->
    <div id="page-wrapper">

        <!-- HEADER -->
        <?php include_once('includes/header.php');?>

        <h2 class="page-title">Update Services</h2>

        <div class="card-box">

            <h4>Update Barbershop Services</h4>

            <form method="post">

                <?php
                $ret = mysqli_query($con, "SELECT * FROM tblservices WHERE ID='$eid'");
                $row = mysqli_fetch_array($ret);
                ?>

                <!-- SERVICE NAME -->
                <div class="form-group">

                    <label>Service Name</label>

                    <input 
                        type="text"
                        class="form-control"
                        name="sername"
                        value="<?php echo htmlentities($row['ServiceName']); ?>"
                        required
                    >

                </div>

                <!-- DESCRIPTION -->
                <div class="form-group">

                    <label>Service Description</label>

                    <textarea 
                        class="form-control"
                        name="serdesc"
                        required><?php echo htmlentities($row['ServiceDescription']); ?></textarea>

                </div>

                <!-- COST -->
                <div class="form-group">

                    <label>Cost</label>

                    <input 
                        type="text"
                        class="form-control"
                        name="cost"
                        value="<?php echo htmlentities($row['Cost']); ?>"
                        required
                    >

                </div>

                <!-- IMAGE -->
                <div class="form-group">

                    <label>Image</label><br>

                    <img 
                        src="images/<?php echo $row['Image']; ?>"
                        class="service-image"
                        alt="Service Image"
                    >

                    <br>

                    <a 
                        href="update-image.php?lid=<?php echo $row['ID']; ?>"
                        class="update-link"
                    >
                        Update Image
                    </a>

                </div>

                <!-- ORIGINAL BUTTON -->
                <button type="submit" name="submit" class="btn btn-primary">
                    Update
                </button>

            </form>

        </div>

        <!-- FOOTER -->
        <?php include_once('includes/footer.php');?>

    </div>

</div>

<script src="js/bootstrap.js"></script>

</body>
</html>