<?php
 include "db_connect.php";
?>	


<?php
if ($_SESSION['role'] != 1) {
    header("Location:login.php?logout=1");
} //$_SESSION['role'] != 1
?>


<?php
if (isset($_POST['delete'])) {
    $deleted = $_POST['checkbox'];
    $n       = 0;
    foreach ($deleted as $index => $value) {
        $del_sql = "DELETE FROM users WHERE id = '$value'";
        $result = mysql_query($del_sql) or die(mysql_error());
        $n++;
    } //$deleted as $index => $value
    echo "<p>Deleted " . $n . "user(s)</p>";
} //isset($_POST['delete'])
elseif (isset($_GET['change_status'])) {
    $change     = $_GET['change_status'];
    $user_id    = $_GET['id'];
    $update_sql = "UPDATE users SET activated = '$change' WHERE id = '$user_id'";
    $result = mysql_query($update_sql) or die(mysql_error());
} //isset($_GET['change_status'])
$where = "1 = 1";
$sql   = "SELECT (select count(*) from cameras where user_id = u.id ) as num_cameras, (select count(*) from sites where user_id = u.id ) as num_sites, u.id,CONCAT(u.firstname,' ',u.lastname) as fullname, u.email,u.role,u.active FROM users u WHERE " . $where . " ORDER BY role DESC";
$result = mysql_query($sql) or die(mysql_error());
$num = mysql_num_rows($result);
if ($num > 0) {
    echo "<h2>" . $num . " row(s) returned.</h2>";
    echo "<a href='register.php'>Register New Client </a> ";
?>
<form action="" name="listingForm" id="listingForm" method="post">
<table cellspacing="0" cellpadding="0" class="grid listItems" style="font-size:13px;" width="100%">
	<thead>
	<tr class="headerRow">
		<th><input type="checkbox" onclick="toggleChecked(this.checked)"></th>
		<th>ID</th>
		<th>Full Name</th>
		<th>Email </th>
		<th>Cameras</th>
		<th>Sites</th>
		<th>Action</th>
		<th>&nbsp;</th>
	</tr>
</thead>
<tbody>
<?php
    $role = 1;
    while ($row = mysql_fetch_object($result)) {
        if ($row->num_cameras > 0) {
            $cameras_link = "<a href='view_cameras.php?cid=$row->id'>($row->num_cameras) Camera(s) </a>";
        } //$row->num_cameras > 0
        else {
            $cameras_link = "<a href='add_camera.php?cid=$row->id'>Add Camera </a>";
        }
        if ($row->activated == 0) {
            $status_link = "<a href='?change_status=1&amp;id=$row->id'>Activate</a>";
        } //$row->activated == 0
        else {
            $status_link = "<a href='?change_status=0&amp;id=$row->id'>De-activate</a>";
        }
        if ($row->num_sites > 0) {
            $sites_link = "<a href='view_sites.php?cid=$row->id'>($row->num_sites) Site(s) </a>";
        } //$row->num_sites > 0
        else {
            $sites_link = "<a href='add_sites.php?cid=$row->id'>Add Site </a>";
        }
?>
	<tr class="gridRow">
		<td bgcolor="#FFFFFF"><input name="checkbox[]" type="checkbox" class="checkbox" value="<?php echo $row->id; ?>"></td>
		<td><?php echo $row->id; ?></td>
		<td><?php echo ($row->role == 1) ? "<b class='red'>" . $row->fullname . " (admin) </b>" : $row->fullname; ?></td>
		<td><?php echo $row->username; ?></td>
		<td><?php echo $cameras_link; ?></td>
		<td><?php echo $sites_link; ?></td>
		<td><a href="register.php?cid=<?php echo $row->id; ?>">Update Record</a> </td>
		<td><?php echo $status_link; ?></td>
	</tr>
	<?php
    } //$row = mysql_fetch_object($result)
?>
<!-- 	<tr>
<td colspan="20" bgcolor="#FFFFFF"><input name="delete" type="submit" id="delete" value="Delete" style="background:red;"></td>
</tr> -->
</tbody>
</table>
</form>
	<?php
} //$num > 0
else {
    echo "No records found.";
}
?>

