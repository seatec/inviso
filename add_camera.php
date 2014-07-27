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

$page_title = "New Camera";

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
							<h2>New Camera</h2>
		
						</header>
		
						<!-- widget div-->
						<div>
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
							
							<?php							
							// If Button Save was click this is the code will executed
							if (isset($_POST['description'])) {
							    // check fields for wheither not empty or empty
							    if (!empty($_POST['description']) && 
							    	!empty($_POST['wan_ip']) && 
							    	!empty($_POST['port']) && 
							    	!empty($_POST['cam_user']) && 
							    	!empty($_POST['site_id']) && 
							    	!empty($_POST['cam_pwd'])) 
							    {
							        // initialize the variable to be the value of the textbox
							        $description    = $_POST['description'];
							        $wan_ip    = $_POST['wan_ip'];
							        $lan_ip    = $_POST['lan_ip'];
							        $port = $_POST['port'];
							        $cam_user = $_POST['cam_user'];
							        $cam_pwd     = $_POST['cam_pwd'];
							        $cam_num = $_POST['cam_num'];
							        $ptz     = $_POST['ptz'];
							        $site_id     = $_POST['site_id'];
							        $user_id = $site_obj->get_site_owner($site_id);

							        //Sql syntax for INSERT Statement
							        $query    = "INSERT INTO cameras SET 
							        				 description = '$description'
							        				,wan_ip 	 = '$wan_ip'
							        				,lan_ip		 = '$lan_ip'
							        				,port		 = '$port'
							        				,cam_user	 = '$cam_user'
							        				,cam_pwd	 = '$cam_pwd'
							        				,cam_num	 = '$cam_num'
							        				,PTZ		 = '$ptz'
							        				,site_id	 = '$site_id'
							        				,user_id	 = '$user_id' ";
							        $result   = mysql_query($query);
							        if ($result) {
							            $msg = " Successfully Added ($description).";

							            header("Location: view_cameras.php?site_id=$site_id");
							        } //$result
							    } //!empty($_POST['all fields'])
							    else {
							        $msg = " All fields are required!";
							    }
							} //isset($_POST['save'])
							$site_id = @$_REQUEST['site_id'];
							$site_user_id = @$_REQUEST['user_id'];
							?>	

							<!-- widget content -->
							<div class="widget-body">
								<?php
								if (isset($msg)) {
								    echo "<p>$msg</p>";
								} //isset($msg)
								?>
								<form class="form-horizontal" action="" method="post">
									
									<fieldset>
										<!-- <legend>Default Form Elements</legend> -->

										<?php if( isset($site_id) && !empty($site_id) ) { ?>
											<input type="hidden" name="site_id" value="<?php echo $site_id?>">
										<?php }elseif( isset($site_user_id) && !empty($site_user_id) ) { ?>
											<div class="form-group">
											<label class="col-md-2 control-label" for="site_id">Site</label>
											<div class="col-md-10">
												<select class="form-control" id="site_id" name="site_id" required>
													<option value="">-select site-</option>
													<?php 
													$user_sites = $site_obj->get_user_sites($site_user_id);
													foreach($user_sites as $site) { ?>
														<option value="<?php echo $site['id'] ?>"><?php echo $site['description'] ?></option>
													<?php } ?>
												</select> 		
											</div>
										</div>
										<?php } ?>
										<div class="form-group">
											<label for="description" class="col-md-2 control-label">Camera name</label>
											<div class="col-md-10">
												<input id="description" name="description" class="form-control" placeholder="Camera description" type="text" required>
											</div>
										</div>

										<div class="form-group">
											<label for="wan_ip" class="col-md-2 control-label">WAN IP</label>
											<div class="col-md-10">
												<input id="wan_ip" name="wan_ip" class="form-control" placeholder="Wan IP" type="text" required>
											</div>
										</div>

										<div class="form-group">
											<label for="lan_ip" class="col-md-2 control-label">LAN IP</label>
											<div class="col-md-10">
												<input id="lan_ip" name="lan_ip" class="form-control" placeholder="Lan IP" type="text" required>
											</div>
										</div>

										<div class="form-group">
											<label for="port" class="col-md-2 control-label">Port</label>
											<div class="col-md-10">
												<input id="port" name="port" class="form-control" placeholder="Port" type="text" required>
											</div>
										</div>										

										<div class="form-group">											
											<label class="control-label col-md-2" for="prepend">Camera Username</label>
											<div class="col-md-10">
												<div class="icon-addon addon-sm">
								                    <input type="text" id="cam_user" name="cam_user" class="form-control" required>
								                    <label for="cam_user" class="glyphicon glyphicon-user" rel="tooltip" title="Camera Access Username"></label>
								                </div>
											</div>											
										</div>

										<div class="form-group">											
											<label class="control-label col-md-2" for="prepend">Camera Password</label>
											<div class="col-md-10">
												<div class="icon-addon addon-sm">
								                    <input type="text" id="cam_pwd" name="cam_pwd" class="form-control" required>
								                    <label for="cam_pwd" class="glyphicon glyphicon-lock" rel="tooltip" title="Camera Access Password"></label>
								                </div>
											</div>											
										</div>
										
										<div class="form-group">
											<label class="col-md-2 control-label" for="cam_num">Camera Number</label>
											<div class="col-md-10">
												<select class="form-control" id="cam_num" name="cam_num" required>
													<option value="">-select-</option>
													<?php for($n = 1; $n <= 15; $n++) { ?>
														<option value="<?php echo $n ?>"><?php echo $n ?></option>
													<?php } ?>
												</select> 		
											</div>
										</div>

										<div class="form-group">
											<label class="col-md-2 control-label" for="ptz">Is PTZ ?</label>
											<div class="col-md-10">
												<label class="radio radio-inline">
													<input type="radio" class="radiobox" name="ptz" value="1">
													<span>YES</span> 
												</label>
												<label class="radio radio-inline">
													<input type="radio" class="radiobox" name="ptz" value="0">
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
													Save Camera
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