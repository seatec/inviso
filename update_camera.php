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

$page_title = "Update Camera";

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
						$site_user_id = @$_REQUEST['user_id'];
						$site_id = @$_REQUEST['site_id'];

						if( isset($site_id) && !empty($site_id) ){
							$id_clause = "?site_id=$site_id";
						}
						elseif( isset($site_user_id) && !empty($site_user_id) ){
							$id_clause = "?user_id=$site_user_id";
						}
					?>
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-collapsed="false">
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
							<span class="widget-icon"> <i class="fa fa-camera"></i> </span>
							<h2>Update Camera</h2>
		
						</header>
		
						<!-- widget div-->
						<div>
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
							
							<?php							
							if ( !empty($_POST) ) {
							    //update the record if the form was submitted

							    $cam_data = array(
							    	 "description"	=>	mysql_real_escape_string($_POST['description'])
							    	,"wan_ip"	=>	mysql_real_escape_string($_POST['wan_ip'])
							    	,"lan_ip"	=>	mysql_real_escape_string($_POST['lan_ip'])
							    	,"port"	=>	mysql_real_escape_string($_POST['port'])
							    	,"cam_user"	=>	mysql_real_escape_string($_POST['cam_user'])
							    	,"cam_pwd"	=>	mysql_real_escape_string($_POST['cam_pwd'])
							    	,"cam_num"	=>	mysql_real_escape_string($_POST['cam_num'])
							    	,"site_id"	=>	mysql_real_escape_string($_POST['site_id'])
							    	,"PTZ"	=>	mysql_real_escape_string($_POST['ptz'])
							    );
							    $cam_id =  @mysql_real_escape_string($_POST['cam_id']);

							    $updated = $site_obj->update_camera($cam_id, $cam_data);

							    if( $updated ) header("Location:view_cameras.php$id_clause");

							} //$_POST
							$cam_id  = $_REQUEST['id']; //the cam id
							//this query will select the  data which is to be used to fill up the form
							$sql = "select c.site_id as cam_site_id, c.user_id as cam_user_id, c.* from cameras c where id='$cam_id'";
							$rs = mysql_query($sql) or die("SQL: " . $sql . " >> " . mysql_error());
							$num = mysql_num_rows($rs);
							//just a little validation, if a record was found, the form will be shown
							//it means that there's an information to be edited
							if ($num > 0) {
							    $row = mysql_fetch_assoc($rs);
							    extract($row);
							}
							?>

							<!-- widget content -->
							<div class="widget-body">
								<?php
								if (isset($msg)) {
								    echo "<p>$msg</p>";
								} //isset($msg)
								?>
								<form class="form-horizontal" action="" method="post">
									<input type="hidden" name="cam_id" value="<?php echo $id ?>">	
									<fieldset>
										<!-- <legend>Default Form Elements</legend> -->										
										<div class="form-group">
											<label class="col-md-2 control-label" for="site_id">Site</label>
											<div class="col-md-10">
												<select class="form-control" id="site_id" name="site_id" required>
													<option value="">-select site-</option>
													<?php 
													$user_sites = $site_obj->get_user_sites($cam_user_id);
													foreach($user_sites as $site) { 
														$selected = ($site['id'] == $cam_site_id)? 'selected="selected"':'';
														?>
														<option value="<?php echo $site['id'] ?>" <?php echo $selected ?> ><?php echo $site['description'] ?></option>
													<?php } ?>
												</select> 		
											</div>
										</div>

										<div class="form-group">
											<label for="description" class="col-md-2 control-label">Camera name</label>
											<div class="col-md-10">
												<input id="description" name="description" value="<?php echo $description ?>" class="form-control" placeholder="Camera Description" type="text" required>
											</div>
										</div>

										<div class="form-group">
											<label for="wan_ip" class="col-md-2 control-label">WAN IP</label>
											<div class="col-md-10">
												<input id="wan_ip" name="wan_ip" value="<?php echo $wan_ip ?>" class="form-control" placeholder="Wan IP" type="text" required>
											</div>
										</div>

										<div class="form-group">
											<label for="lan_ip" class="col-md-2 control-label">LAN IP</label>
											<div class="col-md-10">
												<input id="lan_ip" name="lan_ip" value="<?php echo $lan_ip ?>" class="form-control" placeholder="Lan IP" type="text" required>
											</div>
										</div>

										<div class="form-group">
											<label for="port" class="col-md-2 control-label">Port</label>
											<div class="col-md-10">
												<input id="port" name="port" class="form-control" value="<?php echo $port ?>" placeholder="Port" type="text" required>
											</div>
										</div>										

										<div class="form-group">											
											<label class="control-label col-md-2" for="prepend">Camera Username</label>
											<div class="col-md-10">
												<div class="icon-addon addon-sm">
								                    <input type="text" id="cam_user" value="<?php echo $cam_user ?>" name="cam_user" class="form-control" required>
								                    <label for="cam_user" class="glyphicon glyphicon-user" rel="tooltip" title="Camera Access Username"></label>
								                </div>
											</div>											
										</div>

										<div class="form-group">											
											<label class="control-label col-md-2" for="prepend">Camera Password</label>
											<div class="col-md-10">
												<div class="icon-addon addon-sm">
								                    <input type="text" id="cam_pwd" name="cam_pwd" value="<?php echo $cam_pwd ?>" class="form-control" required>
								                    <label for="cam_pwd" class="glyphicon glyphicon-lock" rel="tooltip" title="Camera Access Password"></label>
								                </div>
											</div>											
										</div>
										
										<div class="form-group">
											<label class="col-md-2 control-label" for="cam_num">Camera Number</label>
											<div class="col-md-10">
												<select class="form-control" id="cam_num" name="cam_num" required>
													<option value="">-select-</option>
													<?php for($n = 1; $n <= 15; $n++) { 
														$selected = ($n == $cam_num)? 'selected="selected"':'';
														?>
														<option value="<?php echo $n ?>" <?php echo $selected ?>><?php echo $n ?></option>
													<?php } ?>
												</select> 		
											</div>
										</div>

										<div class="form-group">
											<label class="col-md-2 control-label" for="ptz">Is PTZ ?</label>
											<div class="col-md-10">
												<label class="radio radio-inline">
													<input type="radio" class="radiobox" name="ptz" value="1" <?php echo ($PTZ == 1)? 'checked="checked"':'' ?>>
													<span>YES</span> 
												</label>
												<label class="radio radio-inline">
													<input type="radio" class="radiobox" name="ptz" value="0" <?php echo ($PTZ == 0)? 'checked="checked"':'' ?>>
													<span>NO</span>  
												</label>											
											</div>
										</div>


									</fieldset>	
									
									<div class="form-actions">
										<div class="row">
											<div class="col-md-12">
												<button class="btn btn-default" type="submit">
													Cancel
												</button>
												<button name="save" class="btn btn-primary" type="submit">
													<i class="fa fa-save"></i>
													Save Changes
												</button>
											</div>
										</div>
									</div>
		
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