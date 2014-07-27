<?php
session_start();
?>
<?php
include "db_connect.php";
?>	
            <header class="content-header">
                <!-- header actions -->
                <div class="header-actions pull-right">
                    <!-- (recomended: dont change the id value) -->
                    <div class="btn-group">
                        <a id="users-setting" class="btn btn-icon data-toggle" data-toggle="dropdown" role="button">
                            <i class="icon ion-gear-b"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-extend pull-right" role="menu">
                            <li class="dropdown-profile">
                                <div class="dp-cover">
                                    <img class="img-bg" src="assets/app/img/cover-blur.jpg" alt="">
                                    <a class="img-avatar" href="page_profile.html">
                                        <img class="img-circle" src="assets/app/img/brand-md.png" alt="">
                                    </a>
                                    <div class="dp-details"><?php
if (isset($_SESSION['ID'])) {
    echo $_SESSION['username'];
} //isset($_SESSION['ID'])
?></div>
                                </div>
                            </li>
                            <li class="dropdown-footer">
                                <div class="clearfix">
								<?php
if (isset($_SESSION['ID'])) {
?>
                        <a href="login.php?logout=1" class="btn btn-default pull-right">Sign out</a>
                      <?php
} //isset($_SESSION['ID'])
?>
                                    
                                    <a href="user_listing.php" class="btn btn-default pull-left">Manage Users, Cameras, Sites</a>
                                </div>
                            </li><!-- /dropdown-footer -->
                        </ul><!-- /dropdown-extend -->
                    </div><!-- /btn-group setting -->

                    <!-- (recomended: dont change the id value) -->
                    <a id="toggle-aside" class="btn btn-icon" role="button"><i class="icon ion-navicon-round"></i></a>
                </div><!-- /header actions -->


                <!-- your Awesome App title -->
                <h1 class="content-title"></h1>
            </header><!-- /side left -->
            

            <!-- app-body -->
            <div class="app-body">
<?php
if (isset($_POST['delete'])) {
    $deleted = $_POST['checkbox'];
    $user_id = $_POST['id'];
    $n       = 0;
    foreach ($deleted as $index => $value) {
        $del_sql = "DELETE FROM cameras WHERE id = '$value'";
        $result = mysql_query($del_sql) or die(mysql_error());
        $n++;
    } //$deleted as $index => $value
    echo "<p>Deleted " . $n . "item(s)</p>";
} //isset($_POST['delete'])
$user_id = $_GET['id'];
$where   = "c.user_id = '$user_id' ";
$sql     = "SELECT c.wan_ip as url, c.site_id, c.description as cameraDesc, s.description as description, c.camera_num as camera_num FROM cameras c JOIN sites s ON c.site_id = s.id WHERE " . $where . "  ORDER BY s.id, c.id";
$result = mysql_query($sql) or die(mysql_error());
$num = mysql_num_rows($result);
if ($num > 0) {
    echo "<a href='add_camera.php?id=$user_id'>Add Camera </a> | ";
    echo "<a href='user_listing.php'>Back to User Listing </a>";
?>
<div id="panel7" class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="panel-icon"><i class="icon ion-ios7-photos-outline"></i></div>
                                        <div class="panel-actions">
                                            <a role="button" data-refresh="#panel7" title="refresh" class="btn btn-sm btn-icon">
                                                <i class="icon ion-refresh"></i>
                                            </a>
                                            <a role="button" data-expand="#panel7" title="expand" class="btn btn-sm btn-icon">
                                                <i class="icon ion-arrow-resize"></i>
                                            </a>
                                            <a role="button" data-collapse="#panel7" title="collapse" class="btn btn-sm btn-icon">
                                                <i class="icon ion-chevron-down"></i>
                                            </a>
                                            <a role="button" data-close="#panel7" title="close" class="btn btn-sm btn-icon">
                                                <i class="icon ion-close-round"></i>
                                            </a>
                                        </div><!-- /panel-actions -->
                                        <h3 class="panel-title">Camera Listing</h3>
                                    </div><!-- /panel-heading -->
									<form action="" name="listingForm" id="listingForm" method="post">
										<input type="hidden" name="id" value="<?php
    echo $_GET['id'];
?>">
                                    <div class="table-responsive">
                                        <table class="table table-hover datatables">
										<thead>
										<tr class="headerRow">
											<th><input type="checkbox" onclick="toggleChecked(this.checked)"></th>
											<th>Site</th>
											<th>Camera #</th>
											<th>Description</th>
											<th>Camera URL</th>
										</tr>
									</thead>
									<tbody>
<?php
    while ($row = mysql_fetch_object($result)) {
?>
		<tr class="gridRow">
		<td><input name="checkbox[]" type="checkbox" class="checkbox" value="<?php
        echo $row->url;
?>"></td>
		<td><?php
        echo $row->description;
?></td>
		<td><?php
        echo $row->camera_num;
?></td>
		<td><?php
        echo $row->cameraDesc;
?></td>
		<td><?php
        echo $row->url;
?></td>
	</tr>

	<?php
    } //$row = mysql_fetch_object($result)
?>
	                                            </tbody>
                                        </table>
                                    </div>
                                </div><!-- /panel7 -->
</form>
		<tr>
<td colspan="20"><input name="delete" type="submit" id="delete" value="Delete" style="background:red;"></td>
</tr>

	<?php
} //$num > 0
else {
    echo "No records found.";
}
?>
   </div><!-- /app body -->