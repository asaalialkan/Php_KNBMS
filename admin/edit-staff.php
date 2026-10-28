<?php 
session_start();
include('includes/dbconnection.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* ================= LOGIN CHECK ================= */
if (!isset($_SESSION['bpmsaid']) || empty($_SESSION['bpmsaid'])) {
    header('location:logout.php');
    exit();
}

/* ================= GET ID ================= */
if (!isset($_GET['editid']) || empty($_GET['editid'])) {
    die("<h3 style='padding:20px;color:red;'>Invalid staff ID</h3>");
}

$id = intval($_GET['editid']);

/* ================= UPDATE ================= */
if (isset($_POST['submit'])) {

    $name   = mysqli_real_escape_string($con, $_POST['name']);
    $email  = mysqli_real_escape_string($con, $_POST['email']);
    $mobile = mysqli_real_escape_string($con, $_POST['mobile']);
    $role   = mysqli_real_escape_string($con, $_POST['role']);
    $status = mysqli_real_escape_string($con, $_POST['status']);

    $sql = "UPDATE tbladmin SET 
                AdminName='$name',
                Email='$email',
                MobileNumber='$mobile',
                Role='$role',
                Status='$status'
            WHERE ID=$id";

    $query = mysqli_query($con, $sql);

    if (!$query) {
        die("SQL ERROR: " . mysqli_error($con));
    }

    echo "<script>alert('Updated successfully');</script>";
    echo "<script>window.location.href='manage-staff.php';</script>";
    exit();
}

/* ================= FETCH ================= */
$ret = mysqli_query($con, "SELECT * FROM tbladmin WHERE ID=$id");
$row = mysqli_fetch_assoc($ret);

if (!$row) {
    die("<h3 style='padding:20px;color:red;'>Staff not found</h3>");
}

/* SAFE VALUES (ONLY FOR NON-EMPTY PREFILL FIELDS) */
$mobile = $row['MobileNumber'] ?? '';
$role   = $row['Role'] ?? '';
$status = $row['Status'] ?? '';
?>

<!DOCTYPE HTML>
<html>
<head>
<title>Edit Staff</title>

<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/font-awesome.css" rel="stylesheet">

<style>
body{
    background: linear-gradient(135deg, #eef2f7, #f7f9fc);
    font-family: Arial, sans-serif;
}

.page-wrapper{
    margin-left:700px;
    padding:25px;
}

.card{
    background:#fff;
    padding:30px;
    border-radius:14px;
    box-shadow:0 6px 20px rgba(0,0,0,0.08);
    max-width:650px;
}

.title{
    font-size:20px;
    font-weight:600;
    margin-bottom:25px;
    color:#333;
}

.input-group{
    margin-bottom:18px;
}

.input-group-addon{
    background:#2563eb;
    color:#fff;
    border:none;
    border-radius:6px 0 0 6px;
}

.form-control{
    height:44px;
    border-radius:6px;
    box-shadow:none;
}

.form-control:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 0.2rem rgba(37,99,235,0.15);
}

.btn-primary{
    width:100%;
    padding:12px;
    font-size:15px;
    border-radius:6px;
    background:#2563eb;
    border:none;
}

.btn-primary:hover{
    background:#1d4ed8;
}
</style>

</head>

<body>

<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>

<div class="page-wrapper">

<div class="card">

    <div class="title">
        <i class="fa fa-user"></i> Edit Staff Member
    </div>

    <form method="post">

        <!-- NAME (EMPTY ON LOAD) -->
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-user"></i></span>
            <input type="text" name="name" class="form-control"
                placeholder="Enter staff name" value="">
        </div>

        <!-- EMAIL (EMPTY ON LOAD) -->
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
            <input type="email" name="email" class="form-control"
                placeholder="Enter email address" value="">
        </div>

        <!-- MOBILE -->
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
            <input type="text" name="mobile" class="form-control"
                value="<?php echo htmlspecialchars($mobile); ?>" required>
        </div>

        <!-- ROLE -->
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-briefcase"></i></span>

            <select name="role" class="form-control" required>
                <option value="">-- Select Role --</option>
                <option value="Admin" <?php if($role == 'Admin') echo 'selected'; ?>>Admin</option>
                <option value="Barber" <?php if($role == 'Barber') echo 'selected'; ?>>Barber</option>
                <option value="Receptionist" <?php if($role == 'Receptionist') echo 'selected'; ?>>Receptionist</option>
            </select>
        </div>

        <!-- STATUS -->
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-toggle-on"></i></span>

            <select name="status" class="form-control" required>
                <option value="Active" <?php if($status == 'Active') echo 'selected'; ?>>Active</option>
                <option value="Inactive" <?php if($status == 'Inactive') echo 'selected'; ?>>Inactive</option>
            </select>
        </div>

        <button type="submit" name="submit" class="btn btn-primary">
            <i class="fa fa-save"></i> Update Staff
        </button>
<!-- BACK BUTTON -->
<div style="text-align:center; margin-top:15px;">
    <a href="manage-staff.php" class="btn btn-default" 
       style="padding:10px 25px; border-radius:6px;">
        <i class="fa fa-arrow-left"></i> Back
    </a>
</div>
    </form>

</div>

</div>

</body>
</html>