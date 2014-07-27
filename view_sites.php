<?php //initilize the page
require_once ("inc/init.php");

// load the SITE Class
require_once("lib/site.php");
$site_obj = new Site($conn);

//require UI configuration (nav, ribbon, etc.)
require_once ("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

 YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
 E.G. $page_title = "Custom Title" */

$page_title = "Sites";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include ("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$current_site_id = (!empty($_GET['site_id']))? $_GET['site_id']:0;

if( $current_site_id  == 0 ) {
	$user_id = $_SESSION['ID'];
	$sites = $site_obj->get_user_sites($user_id);
	$current_site = array_shift($sites);
	$current_site_id = $current_site['id'];
}


if( $current_site_id > 0 ){ 
	$site_name = $site_obj->get_site_name($current_site_id);
	$page_nav[$site_name]["active"] = true;
}
include ("inc/nav.php");

require_once 'lib/user.php';
$user = new User();

// Confirm if user is logged in
$user->confirm_logged_in();
?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">

	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Tables"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
		
		<!-- widget grid -->
		<section id="widget-grid" class="">
		
			<!-- row -->
			<div class="row">
		
				<!-- NEW WIDGET START -->
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<?php
						$site_user_id = $_GET['id'];
					?>
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-darken" id="wid-id-users">
						<!-- widget options:
						usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
		
						data-widget-colorbutton="false"
						data-widget-editbutton="false"
						data-widget-togglebutton="false"
						data-widget-deletebutton="false"
						data-widget-fullscreenbutton="false"
						data-widget-custombutton="false"
						data-widget-collapsed="true"
						data-widget-sortable="false"
		
						-->
						<header>
							<span class="widget-icon"> <i class="fa fa-users"></i> </span>
							<h2>Sites</h2>

							<div class="widget-toolbar" id="" role="menu">
							<div class="btn-group">
								<a class="btn btn-primary" href="add_site.php?user_id=<?php echo $site_user_id ?>">
									Add New Site
								</a>								
							</div></div>
		
						</header>
		
						<!-- widget div-->
						<div>
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
							
							
							<?php
							if(isset($_POST['delete']) ) {
									$deleted = $_POST['checkbox'];
									$user_id  = $_POST['user_id'];
									$n = 0;
								foreach ($deleted as $index => $value) {
									$del_sql = "DELETE FROM sites WHERE user_id = '$value'";
									$result = mysql_query($del_sql) or die(mysql_error());

									$n++;
									}
							echo "<p>Deleted ". $n ."item(s)</p>";
							header("Location:view_sites.php?id=$site_user_id");
							}
							elseif (isset($_GET['change_status'])) {
							    $change     = $_GET['change_status'];
							    $id    = $_GET['id'];
							    $update_sql = "UPDATE sites SET active = '$change' WHERE id = '$id'";
							    $result = mysql_query($update_sql) or die(mysql_error());
							} //isset($_GET['change_status'])
							
							$where = (!empty($site_user_id))?"user_id = '$site_user_id'":"";
							$sql = "SELECT s.id, s.description, s.active, (select count(*) from cameras where site_id = s.id ) as num_cameras FROM sites s WHERE ".$where." ORDER BY s.id";
							$result = mysql_query($sql) or die(mysql_error());
							$num = mysql_num_rows($result);
							?>


							<!-- widget content -->
							<div class="widget-body no-padding">
								<form action="" name="listingForm" id="listingForm" method="post">
						        <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
									<thead>			                
										<tr>
											<th><input type="checkbox" onclick="toggleChecked(this.checked)"></th>
											<th data-hide="phone">ID</th>
											<th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Name</th>
											<th data-hide="phone,tablet"><i class="fa fa-fw fa-camera txt-color-blue hidden-md hidden-sm hidden-xs"></i>Cameras</th>
											<th>Action</th>
											<th>&nbsp;</th>
										</tr>
									</thead>
									<tbody>
									<?php if( $num > 0) { ?>
										<?php while( $row = mysql_fetch_object($result)) { 
												if ($row->num_cameras > 0) {
										            $cameras_link = "<a href='view_cameras.php?site_id=$row->id'>($row->num_cameras) Camera(s) </a>";
										        } //$row->num_cameras > 0
										        else {
										            $cameras_link = "<a href='add_camera.php?site_id=$row->id'>Add Camera </a>";
										        }

										        if ($row->active == 0) {
										            $status_link = "<a href='?change_status=1&amp;id=$row->id'>Activate</a>";
										        } //$row->activated == 0
										        else {
										            $status_link = "<a href='?change_status=0&amp;id=$row->id'>De-activate</a>";
										        }
										        
										?>
											<tr>
												<td bgcolor="#FFFFFF"><input name="checkbox[]" type="checkbox" value="<?php echo $row->id; ?>"></td>
												<td><?php echo $row->id; ?></td>
												<td><?php echo $row->description; ?></td>
												<td><?php echo $cameras_link; ?></td>												
												<td><a href="update_site.php?site_id=<?php echo $row->id; ?>&user_id=<?php echo $site_user_id; ?>">Update Record</a> </td>
												<td><?php echo $status_link; ?></td>												
											</tr>
										<?php } ?>
									<?php } ?>	
									</tbody>
								</table>
								<button name="delete" class="btn btn-danger" type="submit">
									Delete
								</button>									
								</form>
		
							</div>
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->
		
					</div>
					<!-- end widget -->
		
				</article>
				<!-- WIDGET END -->
		
			</div>
		
			<!-- end row -->
		
			<!-- end row -->
		
		</section>
		<!-- end widget grid -->


	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<!-- PAGE FOOTER -->
<?php // include page footer
include ("inc/footer.php");
?>
<!-- END PAGE FOOTER -->

<?php //include required scripts
include ("inc/scripts.php");
?>

<!-- PAGE RELATED PLUGIN(S) -->
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/dataTables.colVis.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/dataTables.tableTools.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>

<script type="text/javascript">

// DO NOT REMOVE : GLOBAL FUNCTIONS!

$(document).ready(function() {
	
	/* // DOM Position key index //
		
	l - Length changing (dropdown)
	f - Filtering input (search)
	t - The Table! (datatable)
	i - Information (records)
	p - Pagination (paging)
	r - pRocessing 
	< and > - div elements
	<"#id" and > - div with an id
	<"class" and > - div with a class
	<"#id.class" and > - div with an id and class
	
	Also see: http://legacy.datatables.net/usage/features
	*/	

	/* BASIC ;*/
		var responsiveHelper_dt_basic = undefined;
		var responsiveHelper_datatable_fixed_column = undefined;
		var responsiveHelper_datatable_col_reorder = undefined;
		var responsiveHelper_datatable_tabletools = undefined;
		
		var breakpointDefinition = {
			tablet : 1024,
			phone : 480
		};

		$('#dt_basic').dataTable({
			"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
				"t"+
				"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
			"autoWidth" : true,
			"preDrawCallback" : function() {
				// Initialize the responsive datatables helper once.
				if (!responsiveHelper_dt_basic) {
					responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic'), breakpointDefinition);
				}
			},
			"rowCallback" : function(nRow) {
				responsiveHelper_dt_basic.createExpandIcon(nRow);
			},
			"drawCallback" : function(oSettings) {
				responsiveHelper_dt_basic.respond();
			}
		});

	/* END BASIC */

})

</script>

<?php
//include footer
include("inc/footer.php"); 
?>