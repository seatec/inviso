<?php
session_start();
?>
<?php
include "db_connect.php";
?>	

<?php
if (isset($_POST['submit'])) {
    $url = $_POST['url'];
    $camera_num  = $_POST['camera_num'];
    $description = $_POST['description'];
    $site = $_POST['site'];
    $user_id = $_POST['id'];
    $check_sql = "SELECT * FROM cameras WHERE wan_ip = '$url' AND site_id = '$site' ";
    $result = mysql_query($check_sql) or die(mysql_error());
    $num = mysql_num_rows($result);
    if ($num < 0) {
        echo "Camera already registered!";
    } //$num < 0
    else {
        $ct_sql = "INSERT INTO cameras(user_id,wan_ip, description, site_id, camera_num) VALUES('$user_id','$url','$description','$site','$camera_num') ";
        $result = mysql_query($ct_sql) or die(mysql_error());
        echo "<p> Camera Added! </p>";
        echo "<a href='user_listing.php'>Back to User Listing </a>";
    }
} //isset($_POST['submit'])

$add_camera_form_html = "
<form action='' name='addCamera' id='addCamera' method='post'>
<input type='hidden' name='id' value=' "<?php
echo $_GET['id'];
?>"'>
<table cellspacing='0' cellpadding='0' width='50%'>
	<tr>
		<td></td>
		<td></td>
	</tr>	
	<tr>
		<td>Camera URL</td>
		<td><input type='text' name='url' id='url'></td>
	</tr>
		<tr>
		<td>Description</td>
		<td><input type='text' name='description' id='description'></td>
	</tr>
			<tr>
		<td>Camera Number</td>
		<td>
		<select name='camera_num' id='camera_num'>
		<option value='1'>1</option>
		<option value='2'>2</option>
		<option value='3'>3</option>
		<option value='4'>4</option>
		<option value='5'>5</option>
		<option value='6'>6</option>
		<option value='7'>7</option>
		<option value='8'>8</option>
		<option value='9'>9</option>
		<option value='10'>10</option>
		<option value='11'>11</option>
		<option value='12'>12</option>
		<option value='13'>13</option>
		<option value='14'>14</option>
		<option value='15'>15</option>
		<option value='16'>16</option>
		</select>
		</td>
	</tr>
	<tr>
		<td>Site</td>
		<td>
			<select name='site' id='site'>
				'"<?php
$site_sql = "SELECT * FROM sites WHERE user_id = '" . $_GET['id'] . "' ";
$result = mysql_query($site_sql) or die(mysql_error());
while ($row = mysql_fetch_object($result)) {
?>"'
				<option value='"<?php echo $row->id; ?>"'>'
				"<?php echo $row->description; ?>"'</option>'
				"<?php
} //$row = mysql_fetch_object($result)
?>"'
			</select>
		</td>
	</tr>
	<tr>
		<td colspan='2'><input type='submit' name='submit' value='Add Camera'></td>
	</tr>
</table>
</form>
";
?>