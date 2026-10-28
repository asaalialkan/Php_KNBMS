<?php
// SAFE SESSION START (PREVENT DUPLICATE ERROR)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('includes/dbconnection.php');

/* SAFETY CHECK */
if (!isset($_SESSION['bpmsaid'])) {
    header('location:logout.php');
    exit();
}

$adid = $_SESSION['bpmsaid'];

/* ADMIN INFO (SAFE) */
$stmt = $con->prepare("SELECT AdminName, Email FROM tbladmin WHERE ID=?");
$stmt->bind_param("i", $adid);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$name  = $row['AdminName'] ?? 'Admin';
$email = $row['Email'] ?? '';

/* NOTIFICATIONS */
$ret1 = mysqli_query($con,"
  SELECT tbluser.FirstName, tbluser.LastName, tblbook.ID as bid
  FROM tblbook 
  JOIN tbluser ON tbluser.ID=tblbook.UserID 
  WHERE tblbook.Status IS NULL
  ORDER BY tblbook.ID DESC
  LIMIT 5
");

$num = mysqli_num_rows($ret1);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Header</title>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body{
  margin:0;
  font-family:Arial, sans-serif;
}

/* HEADER */
.header{
  position:fixed;
  top:15px;
  right:60px;
  display:flex;
  gap:12px;
  z-index:9999;
}

/* ICON BUTTON */
.icon, .profile-trigger{
  position:relative;
  display:flex;
  align-items:center;
  gap:8px;
  cursor:pointer;
  background:rgba(247, 247, 247, 0.95);
  padding:10px 16px;
  border-radius:25px;
  box-shadow:0 5px 20px rgba(0,0,0,0.15);
  font-size:14px;
}

/* BADGE */
.badge{
  position:absolute;
  top:-5px;
  right:-5px;
  background:#ff3b3b;
  color:#fff;
  font-size:10px;
  padding:3px 6px;
  border-radius:50%;
}

/* DROPDOWN */
.dropdown-menu{
  position:absolute;
  top:55px;
  right:0;
  width:260px;
  background:#fff;
  border-radius:12px;
  box-shadow:0 10px 30px rgba(0,0,0,0.2);
  overflow:hidden;
  display:none;
}

.dropdown-menu.active{
  display:block;
}

/* HEADER BLOCK */
.profile-header{
  padding:10px;
  background:#f7f7f7;
  border-bottom:1px solid #0932e9;
}

.profile-header h3{
  margin:0;
  font-size:15px;
}

.profile-header p{
  margin:3px 0 0;
  font-size:12px;
  color:#666;
  word-break:break-word;
}

/* MENU ITEM */
.menu-item{
  display:flex;
  align-items:center;
  gap:10px;
  padding:12px 14px;
  text-decoration:none;
  color:#333;
  font-size:14px;
}

.menu-item:hover{
  background:#f2f2f2;
}

.menu-icon{
  width:18px;
}

/* DIVIDER */
.divider{
  height:1px;
  background:#eee;
}

.logout{
  color:#d32f45;
  font-weight:500;
}

@media (max-width:500px){
  .dropdown-menu{
    width:90vw;
  }
}
</style>
</head>

<body>

<div class="header">

  <!-- NOTIFICATIONS -->
  <div class="icon" onclick="toggleMenu('notifMenu')">
    <i class="fa fa-bell"></i>

    <?php if($num > 0): ?>
      <span class="badge"><?= $num ?></span>
    <?php endif; ?>

    <div class="dropdown-menu" id="notifMenu">

      <div class="profile-header">
        <h3>Notifications</h3>
        <p><?= $num ?> new updates</p>
      </div>

      <?php if($num > 0){ 
        while($n = mysqli_fetch_assoc($ret1)){ ?>
          <a href="view-appointment.php?viewid=<?= $n['bid'] ?>" class="menu-item">
            <i class="fa fa-bell menu-icon"></i>
            <?= htmlspecialchars($n['FirstName'].' '.$n['LastName']) ?>
          </a>
      <?php }} else { ?>
        <div class="menu-item">No new notifications</div>
      <?php } ?>

      <div class="divider"></div>

      <a href="all-appointment.php" class="menu-item">
        <i class="fa fa-list menu-icon"></i> View All
      </a>

    </div>
  </div>

  <!-- PROFILE -->
  <div class="profile-trigger" onclick="toggleMenu('profileMenu')">
    <i class="fa fa-user"></i>
    <?= htmlspecialchars($name) ?>

    <div class="dropdown-menu" id="profileMenu">

      <div class="profile-header">
        <h3><?= htmlspecialchars($name) ?></h3>
        <p><?= htmlspecialchars($email) ?></p>
      </div>

      <a href="admin-profile.php" class="menu-item">
        <i class="fa fa-user menu-icon"></i>My Profile
      </a>

      <a href="change-password.php" class="menu-item">
        <i class="fa fa-lock menu-icon"></i>Change
      </a>

      <div class="divider"></div>

      <a href="logout.php" class="menu-item logout">
        <i class="fa fa-sign-out-alt menu-icon"></i>Logout
      </a>

    </div>
  </div>

</div>

<script>
function toggleMenu(id){
  document.querySelectorAll('.dropdown-menu').forEach(m=>{
    if(m.id !== id) m.classList.remove('active');
  });
  document.getElementById(id).classList.toggle('active');
}

document.addEventListener("click", function(e){
  if(!e.target.closest('.icon') && !e.target.closest('.profile-trigger')){
    document.querySelectorAll('.dropdown-menu').forEach(m=>{
      m.classList.remove('active');
    });
  }
});
</script>

</body>
</html>