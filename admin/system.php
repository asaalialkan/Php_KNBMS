<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

/* ================= LOGOUT ================= */
if (isset($_GET['logout'])) {
    session_destroy();
    header("location:system.php");
    exit();
}

/* ================= LOGIN ================= */
if (isset($_POST['login'])) {

    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = md5($_POST['password']);

    $query = mysqli_query($con,
        "SELECT * FROM tbladmin 
         WHERE UserName='$username' 
         AND Password='$password' 
         AND Status='Active'"
    );

    $user = mysqli_fetch_assoc($query);

    if ($user) {

        $_SESSION['userid'] = $user['ID'];
        $_SESSION['name']   = $user['AdminName'];
        $_SESSION['role']   = $user['Role'];
        $_SESSION['email']  = $user['Email'];

        header("location:system.php");
        exit();

    } else {
        echo "<script>alert('Invalid login');</script>";
    }
}

/* ================= IF NOT LOGGED IN ================= */
if (!isset($_SESSION['userid'])) {
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
</head>
<body>

<h2>Login System</h2>

<form method="post">
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit" name="login">Login</button>
</form>

</body>
</html>

<?php
exit();
}

/* ================= USER SESSION ================= */
$name = $_SESSION['name'];
$role = $_SESSION['role'];

/* ================= STAFF DATA ================= */
$staff = mysqli_query($con, "SELECT * FROM tbladmin ORDER BY ID DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Multi User System</title>

<style>
body{
    font-family:Arial;
    background:#f4f6f9;
    margin:0;
}

/* HEADER */
.header{
    background:#fff;
    padding:15px;
    display:flex;
    justify-content:space-between;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
}

/* CONTAINER */
.container{
    padding:20px;
}

/* CARD */
.card{
    background:#fff;
    padding:20px;
    border-radius:10px;
    margin-bottom:20px;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
}

th, td{
    padding:10px;
    border:1px solid #ddd;
}

th{
    background:#eee;
}
</style>

</head>

<body>

<!-- ================= HEADER (FIXED MULTI USER) ================= -->
<div class="header">
    <div>
        👤 <b><?= htmlspecialchars($name) ?></b>
        <small>(<?= htmlspecialchars($role) ?>)</small>
    </div>

    <div>
        <a href="?logout=1">Logout</a>
    </div>
</div>

<div class="container">

<!-- ================= ROLE DASHBOARD ================= -->
<div class="card">

<h2>Dashboard</h2>

<?php if ($role == 'Admin') { ?>
    <p>✔ Admin Access</p>

<?php } elseif ($role == 'Barber') { ?>
    <p>✂ Barber Access</p>

<?php } else { ?>
    <p>🧾 Reception Access</p>
<?php } ?>

</div>

<!-- ================= STAFF LIST ================= -->
<div class="card">

<h3>Staff List (Database)</h3>

<table>
<tr>
    <th>#</th>
    <th>Name</th>
    <th>Email</th>
    <th>Mobile</th>
    <th>Role</th>
    <th>Status</th>
</tr>

<?php
$cnt = 1;
while ($row = mysqli_fetch_assoc($staff)) {
?>

<tr>
    <td><?= $cnt++; ?></td>
    <td><?= htmlspecialchars($row['AdminName']); ?></td>
    <td><?= htmlspecialchars($row['Email']); ?></td>
    <td><?= htmlspecialchars($row['MobileNumber']); ?></td>
    <td><?= htmlspecialchars($row['Role']); ?></td>
    <td><?= htmlspecialchars($row['Status']); ?></td>
</tr>

<?php } ?>

</table>

</div>

</div>

</body>
</html>