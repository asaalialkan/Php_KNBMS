<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['bpmsaid']) == 0) {
    header('location:logout.php');
    exit();
}

if(isset($_POST['submit'])) {

    $image = $_FILES["image"]["name"];
    $extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));

    // Allowed extensions
    $allowed_extensions = array("jpg","jpeg","png","gif");

    if(!in_array($extension, $allowed_extensions)) {

        echo "<script>
                alert('Invalid format. Only JPG, JPEG, PNG & GIF files are allowed.');
              </script>";

    } else {

        // Rename image
        $newimage = md5($image . time()) . "." . $extension;

        // Upload image
        move_uploaded_file($_FILES["image"]["tmp_name"], "images/" . $newimage);

        $eid = intval($_GET['lid']);

        $query = mysqli_query(
            $con,
            "UPDATE tblservices SET Image='$newimage' WHERE ID='$eid'"
        );

        if($query){

            echo "<script>
                    alert('Service image updated successfully.');
                    window.location.href='manage-services.php';
                  </script>";

        } else {

            echo "<script>
                    alert('Something went wrong. Please try again.');
                  </script>";
        }
    }
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>BPMS | Update Service</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="css/font-awesome.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

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

        /* PAGE WRAPPER */
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
            height:50px;
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

        input[type="file"]{
            padding:10px;
        }

        /* IMAGE */
        .service-image{
            width:160px;
            height:160px;
            object-fit:cover;
            border-radius:14px;
            border:1px solid #dddddd;
            padding:4px;
            background:#fff;
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

    <!-- Sidebar -->
    <?php include_once('includes/sidebar.php');?>

    <!-- Main Content -->
    <div id="page-wrapper">

        <!-- Header -->
        <?php include_once('includes/header.php');?>

        <h2 class="page-title">Update Service</h2>

        <div class="card-box">

            <h4>Update Barbershop Service Image</h4>

            <form method="post" enctype="multipart/form-data">

                <?php
                $cid = intval($_GET['lid']);

                $ret = mysqli_query($con, "SELECT * FROM tblservices WHERE ID='$cid'");

                while ($row = mysqli_fetch_array($ret)) {
                ?>

                <!-- Service Name -->
                <div class="form-group">
                    <label>Service Name</label>

                    <input 
                        type="text"
                        class="form-control"
                        value="<?php echo $row['ServiceName'];?>"
                        readonly
                    >
                </div>

                <!-- Old Image -->
                <div class="form-group">

                    <label>Current Image</label><br>

                    <img 
                        src="images/<?php echo $row['Image'];?>"
                        class="service-image"
                        alt="Service Image"
                    >
                </div>

                <!-- New Image -->
                <div class="form-group">

                    <label>Upload New Image</label>

                    <input 
                        type="file"
                        class="form-control"
                        name="image"
                        required
                    >
                </div>

                <?php } ?>

                <!-- ORIGINAL BUTTON -->
                <button type="submit" name="submit" class="btn btn-primary">
                    Update
                </button>
            </form>

        </div>

        <!-- Footer -->
        <?php include_once('includes/footer.php');?>

    </div>
</div>

<!-- JS -->
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/bootstrap.js"></script>

</body>
</html>