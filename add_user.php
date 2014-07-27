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

$page_title = "New User Registration";

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
							<span class="widget-icon"> <i class="fa fa-user"></i> </span>
							<h2>User Registration Form</h2>
		
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
							if (isset($_POST['firstname'])) {
							    // check fields for wheither not empty or empty
							    if (!empty($_POST['firstname']) && 
							    	!empty($_POST['lastname']) && 
							    	!empty($_POST['email']) && 
							    	!empty($_POST['password']) && 
							    	!empty($_POST['role'])) 
							    {
							        // initialize the variable to be the value of the textbox
							        $fname    = $_POST['firstname'];
							        $lname    = $_POST['lastname'];
							        $email    = $_POST['email'];
							        $password = $_POST['password'];
							        $repeat_password = $_POST['repeat_password'];
							        $role     = $_POST['role'];
							        //Sql syntax for INSERT Statement
							        $query    = "INSERT INTO users (firstname,lastname,email,password,role) values ('$fname','$lname','$email','$password','$role')";
							        $result   = mysql_query($query);
							        if ($result) {
							            $msg = " Successfully Added ($fname $lname).";

							            header('Location: users.php');
							        } //$result
							    } //!empty($_POST['all fields'])
							    else {
							        $msg = " All fields are required!";
							    }
							} //isset($_POST['save'])
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
										<div class="form-group">
											<label for="firstname" class="col-md-2 control-label">Firstname</label>
											<div class="col-md-10">
												<input id="firstname" name="firstname" class="form-control" placeholder="User's Firstname" type="text" required>
											</div>
										</div>

										<div class="form-group">
											<label for="lastname" class="col-md-2 control-label">Lastname</label>
											<div class="col-md-10">
												<input id="lastname" name="lastname" class="form-control" placeholder="User's Lastname" type="text" required>
											</div>
										</div>

										<div class="form-group">											
											<label class="control-label col-md-2" for="prepend">Email Address</label>
											<div class="col-md-10">
												<div class="icon-addon addon-sm">
								                    <input type="text" placeholder="Email address" id="email" name="email" class="form-control" required>
								                    <label for="email" class="glyphicon glyphicon-envelope" rel="tooltip" title="email"></label>
								                </div>
											</div>											
										</div>

										<div class="form-group">											
											<label class="control-label col-md-2" for="prepend">Password</label>
											<div class="col-md-10">
												<div class="icon-addon addon-sm">
								                    <input type="password" id="password" name="password" class="form-control" required>
								                    <label for="password" class="glyphicon glyphicon-lock" rel="tooltip" title="password"></label>
								                </div>
											</div>											
										</div>
			
										<div class="form-group">											
											<label class="control-label col-md-2" for="prepend">Repeat Password</label>
											<div class="col-md-10">
												<div class="icon-addon addon-sm">
								                    <input type="password" id="_repeat_password" name="_repeat_password" class="form-control" required>
								                    <label for="_repeat_password" class="glyphicon glyphicon-lock" rel="tooltip" title="Confirm Password"></label>
								                </div>
											</div>											
										</div>
										
										<div class="form-group">
											<label class="col-md-2 control-label" for="role">User Role</label>
											<div class="col-md-10">
												<select class="form-control" id="role" name="role" required>
													<option value="">-select-</option>
													<option value="1">Administrator</option>
													<option value="2">Operator</option>
													<option value="3">Viewer</option>
												</select> 
			
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
													Save
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