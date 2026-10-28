<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('includes/dbconnection.php');

$current = basename($_SERVER['PHP_SELF']);

$notifCount = 0;
$appointmentCount = 0;

$notifQuery = mysqli_query($con, "SELECT COUNT(ID) AS total FROM tblcontact WHERE IsRead IS NULL");
if ($notifQuery) {
    $notifCount = mysqli_fetch_assoc($notifQuery)['total'];
}

$appQuery = mysqli_query($con, "SELECT COUNT(ID) AS total FROM tblbook WHERE Status='' OR Status IS NULL");
if ($appQuery) {
    $appointmentCount = mysqli_fetch_assoc($appQuery)['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Admin Sidebar</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>

/* =========================
ONLY 3 COLORS THEME
BLACK • WHITE • BLUE
========================= */

:root{
    --black:#000;
    --white:#fff;
    --blue:#1e3a8a;
    --blue-light:#2563eb;
    --gray:#cbd5e1;
}

/* BODY */
body{
    margin:0;
    font-family:Segoe UI, sans-serif;
    background:var(--white);
}

/* SIDEBAR */
.sidebar{
    position:fixed;
    top:0;
    left:0;
    width:270px;
    height:100vh;
    background:var(--black);
    border-right:2px solid var(--blue);
    display:flex;
    flex-direction:column;
    transition:.25s ease;
    overflow:hidden;
    z-index:1000;
}

/* COLLAPSED */
.sidebar.collapsed{
    width:82px;
}

/* BRAND */
.brand{
    display:flex;
    align-items:center;
    gap:12px;
    padding:20px;
    border-bottom:1px solid var(--blue);
}

.brand img{
    width:42px;
    height:42px;
}

.brand-text{
    color:var(--white);
}

.sidebar.collapsed .brand-text{
    display:none;
}

/* TOGGLE */
.toggle-btn{
    position:absolute;
    right:-12px;
    top:22px;
    width:28px;
    height:28px;
    background:var(--blue);
    color:var(--white);
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
}

/* MENU */
#side-menu{
    list-style:none;
    padding:12px;
    flex:1;
    overflow-y:auto;
}

#side-menu li{
    margin-bottom:6px;
}

#side-menu li a{
    display:flex;
    align-items:center;
    gap:14px;
    padding:14px 16px;
    border-radius:12px;
    color:var(--gray);
    text-decoration:none;
    transition:.2s ease;
}

/* HOVER */
#side-menu li a:hover{
    background:var(--blue);
    color:var(--white);
}

/* ACTIVE */
#side-menu li.active > a{
    background:var(--blue-light);
    color:var(--white);
}

/* ICON */
.nav_icon{
    width:22px;
    color:var(--white);
}

/* SUBMENU */
.submenu{
    display:none;
    margin-left:14px;
    padding-left:8px;
    border-left:1px solid var(--blue);
}

.submenu.open{
    display:block;
}

.submenu a{
    display:block;
    padding:10px;
    color:var(--gray);
    text-decoration:none;
    border-radius:8px;
}

.submenu a:hover{
    background:var(--blue);
    color:var(--white);
}

/* BADGE */
.notif-badge{
    margin-left:auto;
    min-width:22px;
    height:22px;
    border-radius:50px;
    background:#ff0000;   /* RED */
    color:#ffffff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:11px;
    font-weight:700;
    padding:0 7px;
    box-shadow:0 0 10px rgba(255,0,0,0.6);
}
/* COLLAPSED TEXT */
.sidebar.collapsed .text{
    display:none;
}

</style>
</head>

<body>

<div class="sidebar">

    <div class="toggle-btn" onclick="toggleSidebar()">
        <i class="fa fa-angle-left"></i>
    </div>

    <!-- BRAND -->
    <div class="brand">
        <img src="images/Logo.png">
        <div class="brand-text">
            <div>Barbershop</div>
            <small>Admin Panel</small>
        </div>
    </div>

    <ul id="side-menu">

        <!-- DASHBOARD -->
        <li class="<?= $current=='dashboard.php'?'active':'' ?>">
            <a href="dashboard.php">
                <i class="fa fa-home nav_icon"></i>
                <span class="text">Dashboard</span>
            </a>
        </li>

        <!-- SERVICES -->
        <li class="has-sub <?= in_array($current,['add-services.php','manage-services.php'])?'active':'' ?>">
            <a href="javascript:void(0)" onclick="toggleSub('s1')">
                <i class="fa fa-scissors nav_icon"></i>
                <span class="text">Services</span>
            </a>
            <div class="submenu <?= in_array($current,['add-services.php','manage-services.php'])?'open':'' ?>" id="s1">
                <a href="add-services.php">Add Service</a>
                <a href="manage-services.php">Manage Services</a>
            </div>
        </li>

        <!-- APPOINTMENTS -->
        <li class="has-sub <?= in_array($current,['walkin-customer.php','new-appointment.php','accepted-appointment.php','rejected-appointment.php','all-appointment.php'])?'active':'' ?>">
            <a href="javascript:void(0)" onclick="toggleSub('s2')">
                <i class="fa fa-calendar nav_icon"></i>
                <span class="text">Appointments</span>

                <?php if($appointmentCount>0): ?>
                    <span class="notif-badge"><?= $appointmentCount ?></span>
                <?php endif; ?>
            </a>

            <div class="submenu <?= in_array($current,['walkin-customer.php','new-appointment.php','accepted-appointment.php','rejected-appointment.php','all-appointment.php'])?'open':'' ?>" id="s2">
                <a href="walkin-customer.php">Walk-In</a>
                <a href="new-appointment.php">New</a>
                <a href="accepted-appointment.php">Approved</a>
                <a href="rejected-appointment.php">Rejected</a>
                <a href="all-appointment.php">All Appointments</a>
            </div>
        </li>

        <!-- CUSTOMERS -->
        <li class="<?= $current=='customer-list.php'?'active':'' ?>">
            <a href="customer-list.php">
                <i class="fa fa-users nav_icon"></i>
                <span class="text">Customers</span>
            </a>
        </li>

        <!-- REPORTS -->
        <li class="has-sub <?= in_array($current,['sales-reports.php','bwdates-reports-ds.php'])?'active':'' ?>">
            <a href="javascript:void(0)" onclick="toggleSub('s3')">
                <i class="fa fa-bar-chart nav_icon"></i>
                <span class="text">Reports</span>
            </a>

            <div class="submenu <?= in_array($current,['sales-reports.php','bwdates-reports-ds.php'])?'open':'' ?>" id="s3">
                <a href="sales-reports.php">Revenue Reports</a>
                <a href="bwdates-reports-ds.php">Date-wise Reports</a>
            </div>
        </li>

        <!-- INVOICES -->
        <li class="<?= $current=='invoices.php'?'active':'' ?>">
            <a href="invoices.php">
                <i class="fa fa-file-text nav_icon"></i>
                <span class="text">Invoices</span>
            </a>
        </li>

        <!-- SEARCH -->
        <li class="has-sub <?= in_array($current,['search-appointment.php','search-invoices.php'])?'active':'' ?>">
            <a href="javascript:void(0)" onclick="toggleSub('s4')">
                <i class="fa fa-search nav_icon"></i>
                <span class="text">Search</span>
            </a>

            <div class="submenu <?= in_array($current,['search-appointment.php','search-invoices.php'])?'open':'' ?>" id="s4">
                <a href="search-appointment.php">Appointments</a>
                <a href="search-invoices.php">Invoices</a>
            </div>
        </li>

        <!-- ENQUIRIES -->
        <li class="has-sub <?= in_array($current,['unreadenq.php','readenq.php'])?'active':'' ?>">
            <a href="javascript:void(0)" onclick="toggleSub('s5')">
                <i class="fa fa-envelope nav_icon"></i>
                <span class="text">Enquiries</span>

                <?php if($notifCount>0): ?>
                    <span class="notif-badge"><?= $notifCount ?></span>
                <?php endif; ?>
            </a>

            <div class="submenu <?= in_array($current,['unreadenq.php','readenq.php'])?'open':'' ?>" id="s5">
                <a href="unreadenq.php">Unread</a>
                <a href="readenq.php">Read</a>
            </div>
        </li>

        <!-- STAFF -->
        <li class="has-sub <?= in_array($current,['manage-staff.php','add-staff.php','edit-staff.php'])?'active':'' ?>">
            <a href="javascript:void(0)" onclick="toggleSub('s7')">
                <i class="fa fa-user-secret nav_icon"></i>
                <span class="text">Staff Users</span>
            </a>

            <div class="submenu <?= in_array($current,['manage-staff.php','add-staff.php','edit-staff.php'])?'open':'' ?>" id="s7">
                <a href="manage-staff.php">Manage Staff</a>
                <a href="add-staff.php">Add Staff</a>
            </div>
        </li>

        <!-- PAGES -->
        <li class="has-sub <?= in_array($current,['about-us.php','contact-us.php'])?'active':'' ?>">
            <a href="javascript:void(0)" onclick="toggleSub('s6')">
                <i class="fa fa-book nav_icon"></i>
                <span class="text">Pages</span>
            </a>

            <div class="submenu <?= in_array($current,['about-us.php','contact-us.php'])?'open':'' ?>" id="s6">
                <a href="about-us.php">About Us</a>
                <a href="contact-us.php">Contact</a>
            </div>
        </li>

    </ul>
</div>

<script>

function toggleSidebar(){
    document.querySelector('.sidebar').classList.toggle('collapsed');
}

function toggleSub(id){
    document.querySelectorAll('.submenu').forEach(m=>{
        if(m.id !== id) m.classList.remove('open');
    });

    document.getElementById(id).classList.toggle('open');
}

</script>

</body>
</html>