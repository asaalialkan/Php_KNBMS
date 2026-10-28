<?php
session_start();
include('includes/dbconnection.php');

/* ================= LOGIN CHECK ================= */
if (!isset($_SESSION['userid'])) {
    header("location:login.php");
    exit();
}

/* ================= ADD STAFF (AJAX) ================= */
if (isset($_POST['action']) && $_POST['action'] == 'add') {

    $name   = mysqli_real_escape_string($con, $_POST['name']);
    $email  = mysqli_real_escape_string($con, $_POST['email']);
    $mobile = mysqli_real_escape_string($con, $_POST['mobile']);
    $role   = mysqli_real_escape_string($con, $_POST['role']);
    $pass   = md5($_POST['password']);

    mysqli_query($con,
        "INSERT INTO tbladmin
        (AdminName, UserName, Email, MobileNumber, Password, Role, Status)
        VALUES
        ('$name','$email','$email','$mobile','$pass','$role','Active')"
    );

    echo "added";
    exit();
}

/* ================= DELETE STAFF (AJAX) ================= */
if (isset($_POST['action']) && $_POST['action'] == 'delete') {

    $id = intval($_POST['id']);

    mysqli_query($con, "DELETE FROM tbladmin WHERE ID='$id'");

    echo "deleted";
    exit();
}

/* ================= EDIT STAFF (AJAX) ================= */
if (isset($_POST['action']) && $_POST['action'] == 'edit_save') {

    $id     = intval($_POST['id']);
    $name   = mysqli_real_escape_string($con, $_POST['name']);
    $email  = mysqli_real_escape_string($con, $_POST['email']);
    $mobile = mysqli_real_escape_string($con, $_POST['mobile']);
    $role   = mysqli_real_escape_string($con, $_POST['role']);

    mysqli_query($con,
        "UPDATE tbladmin SET
        AdminName='$name',
        Email='$email',
        UserName='$email',
        MobileNumber='$mobile',
        Role='$role'
        WHERE ID='$id'"
    );

    echo "updated";
    exit();
}

/* ================= FETCH ONE FOR EDIT ================= */
if (isset($_POST['action']) && $_POST['action'] == 'get_one') {

    $id = intval($_POST['id']);

    $q = mysqli_query($con, "SELECT * FROM tbladmin WHERE ID='$id'");
    $row = mysqli_fetch_assoc($q);

    echo json_encode($row);
    exit();
}

/* ================= FETCH STAFF ================= */
$staff = mysqli_query($con, "SELECT * FROM tbladmin ORDER BY ID DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>AJAX Staff System</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
body{background:#f4f6f9;padding:20px;}
.card{padding:20px;background:#fff;border-radius:10px;}
</style>
</head>

<body>

<div class="container">

<h3>Staff Management (AJAX)</h3>

<!-- ================= ADD STAFF ================= -->
<div class="card mb-3">

<h5>Add Staff</h5>

<input id="name" class="form-control mb-2" placeholder="Name">
<input id="email" class="form-control mb-2" placeholder="Email">
<input id="mobile" class="form-control mb-2" placeholder="Mobile">
<input id="password" type="password" class="form-control mb-2" placeholder="Password">

<select id="role" class="form-control mb-2">
    <option>Admin</option>
    <option>Barber</option>
    <option>Receptionist</option>
</select>

<button class="btn btn-primary" onclick="addStaff()">Add</button>

</div>

<!-- ================= STAFF TABLE ================= -->
<div class="card">

<table class="table table-bordered">
<thead>
<tr>
<th>Name</th>
<th>Email</th>
<th>Mobile</th>
<th>Role</th>
<th>Action</th>
</tr>
</thead>

<tbody id="staffTable">

<?php while ($row = mysqli_fetch_assoc($staff)) { ?>

<tr id="row<?= $row['ID'] ?>">
    <td><?= $row['AdminName'] ?></td>
    <td><?= $row['Email'] ?></td>
    <td><?= $row['MobileNumber'] ?></td>
    <td><?= $row['Role'] ?></td>
    <td>
        <button class="btn btn-sm btn-info" onclick="editStaff(<?= $row['ID'] ?>)">Edit</button>
        <button class="btn btn-sm btn-danger" onclick="deleteStaff(<?= $row['ID'] ?>)">Delete</button>
    </td>
</tr>

<?php } ?>

</tbody>
</table>

</div>
</div>

<!-- ================= EDIT MODAL ================= -->
<div class="modal" id="editModal" style="display:none;position:fixed;top:10%;left:30%;background:#fff;padding:20px;width:400px;">
    <h5>Edit Staff</h5>

    <input id="eid" type="hidden">

    <input id="ename" class="form-control mb-2">
    <input id="eemail" class="form-control mb-2">
    <input id="emobile" class="form-control mb-2">

    <select id="erole" class="form-control mb-2">
        <option>Admin</option>
        <option>Barber</option>
        <option>Receptionist</option>
    </select>

    <button class="btn btn-success" onclick="saveEdit()">Save</button>
    <button class="btn btn-secondary" onclick="$('#editModal').hide()">Close</button>
</div>

<script>

/* ================= ADD ================= */
function addStaff(){
    $.post("staff-system.php",{
        action:"add",
        name:$("#name").val(),
        email:$("#email").val(),
        mobile:$("#mobile").val(),
        password:$("#password").val(),
        role:$("#role").val()
    },function(){
        location.reload();
    });
}

/* ================= DELETE ================= */
function deleteStaff(id){
    if(confirm("Delete this staff?")){
        $.post("staff-system.php",{action:"delete",id:id},function(){
            $("#row"+id).remove();
        });
    }
}

/* ================= GET ONE ================= */
function editStaff(id){
    $.post("staff-system.php",{action:"get_one",id:id},function(data){

        let d = JSON.parse(data);

        $("#eid").val(d.ID);
        $("#ename").val(d.AdminName);
        $("#eemail").val(d.Email);
        $("#emobile").val(d.MobileNumber);
        $("#erole").val(d.Role);

        $("#editModal").show();
    });
}

/* ================= SAVE EDIT ================= */
function saveEdit(){
    $.post("staff-system.php",{
        action:"edit_save",
        id:$("#eid").val(),
        name:$("#ename").val(),
        email:$("#eemail").val(),
        mobile:$("#emobile").val(),
        role:$("#erole").val()
    },function(){
        location.reload();
    });
}

</script>

</body>
</html>