<?php

//initilize the page
require_once("inc/init.php");

// load the SITE Class
require_once("lib/site.php");
$site_obj = new Site($conn);


//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */
	$page_title = "Pelco PTZ 1";
	
/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

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
include("inc/nav.php");


require_once 'lib/user.php';
$user = new User();

// Confirm if user is logged in
$user->confirm_logged_in();

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<!-- MAIN CONTENT -->
	<div id="content">
		<!-- widget grid -->
		<section id="widget-grid" class="">
			<div class="row">
				<article class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
		

<?php 
	// Camera SQL
	// $user_id = $_GET['id'];
	//	Waiting for user class to be added
	
	// $user_id = 1;
	// $where   = "c.user_id = '$user_id'";
	// $cameras_sql = "SELECT (select count(*) from cameras WHERE user_id = " . $user_id . "  ) as num_cameras, (select count(*) from sites WHERE user_id = " . $user_id . " ) as num_sites, 
	// 						c.id, CONCAT('http://',c.cam_user,':',c.cam_pwd,'@',c.wan_ip,':',c.port,'/axis-cgi/mjpg/video.cgi?camera=',c.cam_num) as cam_url, 
	// 						c.description 
	// 				FROM cameras c 
	// 				JOIN sites s ON c.site_id = s.id
 	//			 	ORDER BY s.id, c.id";

    
    $site_cameras = $site_obj->get_site_cameras($current_site_id);

    //echo "<pre>";print_r($site_cameras);

	// $cameras_result = mysql_query($cameras_sql) or die(mysql_error());
	// $cameras_num = mysql_num_rows($cameras_result);
	foreach( $site_cameras as $site_cam ) {
		$site_cam_url = "http://".$site_cam['cam_user'].":".$site_cam['cam_pwd']."@".$site_cam['wan_ip'].":".$site_cam['port']."/axis-cgi/mjpg/video.cgi?camera=".$site_cam['cam_num'];
	//while ($cameras_row = mysql_fetch_object($cameras_result)) {
?>

					<!-- new widget -->
					<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-<?php echo $site_cam['id'] ?>" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
						<header>
							<span class="widget-icon"> <i class="glyphicon glyphicon-eye-open"></i> </span>
							<h2> <?php echo $site_cam['description'] ?> </h2>
							<div class="widget-toolbar non-hidden">
								<!-- add: non-hidden - to disable auto hide -->
								<span class="hidden-mobile">
								<div class="btn-group">
									<button class="btn dropdown-toggle btn-xs btn-default">
										<a href="camera_archive.php?site_id=<?php echo $current_site_id ?>&camera_id=<?php echo $site_cam['id'] ?>"> View video archive </a>
									</button>
									<button class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown">
										Settings <i class="fa fa-caret-down"></i>
									</button>
									<ul class="dropdown-menu pull-right js-status-update">
										<li>
											<a href="javascript:void(0);"> <?php echo $site_cam['description'] ?></a>
										</li>
										<li class="divider"></li>
										<li>
											<a href="javascript:void(0);"> Reboot Camera</a>
										</li>
										<li>
											<a href="javascript:void(0);"> Configuration</a>
										</li>
										<li>
											<a href="javascript:void(0);"> Refresh Stream</a>
										</li>
										<li>
											<a href="camera_qnap.php?camera_id=<?php echo $site_cam['id'] ?>"> View video archive </a>
										</li>
									</ul>
								</div>
								</span>
							</div>
						</header>
						<!-- widget div-->
						<div>
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<div>
									<label>Title:</label>
									<input type="text" />
								</div>
							</div>
							<!-- end widget edit box -->
							<div class="widget-body widget-hide-overflow no-padding">
								<!-- content goes here -->
								<div class="camera-feed">
<?php
		$date = strftime('%d%Y%H%M%S');
?>		<SCRIPT LANGUAGE="JavaScript"> 
				var BaseURL; 
				BaseURL = "<?php echo $site_cam_url ?>";
				var DisplayWidth = "100%"; 
				var DisplayHeight = "100%"; 
				var File = "";
				// No changes required below this point 
				var output = ""; 
				if ((navigator.appName == "Microsoft Internet Explorer") && 
				(navigator.platform != "MacPPC") && (navigator.platform != "Mac68k")) 
				{ 
				// If Internet Explorer under Windows then use ActiveX 
				output = '<OBJECT ID="Player" width=' 
				output += DisplayWidth; 
				output += ' height='; 
				output += DisplayHeight; 
				output += ' CLASSID="CLSID:DE625294-70E6-45ED-B895-CFFA13AEB044" '; 
				output += 'CODEBASE="'; 
				output += BaseURL; 
				output += 'activex/AMC.cab#version=3,20,18,0">'; 
				output += '<PARAM NAME="MediaURL" VALUE="'; 
				output += BaseURL; 
				output += File + '">'; 
				output += '<param name="MediaType" value="mjpeg-unicast">'; 
				output += '<param name="ShowStatusBar" value="1">'; 
				output += '<param name="ShowToolbar" value="1">'; 
				output += '<param name="AutoStart" value="1">'; 
				output += '<param name="StretchToFit" value="1">'; 
				output += '<BR><B>Axis Media Control</B><BR>'; 
				output += 'The AXIS Media Control, which enables you '; 
				output += 'to view live image streams in Microsoft Internet'; 
				output += ' Explorer, could not be registered on your computer.'; 
				output += '<BR></OBJECT>'; 
				} else { 
				// If not IE for Windows use the browser itself to display 
				theDate = new Date(); 
				output = '<IMG SRC="'; 
				output += BaseURL; 
				output += File; 
				output += '&dummy=' + theDate.getTime().toString(10); 
				output += '" HEIGHT="'; 
				output += DisplayHeight; 
				output += '" WIDTH="'; 
				output += DisplayWidth; 
				output += '" ALT="Camera Image">'; 
				} 
				document.write(output); 
				// document.Player.ToolbarConfiguration = "play,+snapshot,+fullscreen" 
				// document.Player.UIMode = "MDConfig"; 
				// document.Player.MotionConfigURL = "/axis-cgi/operator/param.cgi?ImageSource=0" 
				// document.Player.MotionDataURL = "/axis-cgi/motion/motiondata.cgi"; 
				</SCRIPT>
					
							</div>
								<!-- CHAT CONTAINER -->
								<span class="hidden-mobile">
								<div id="chat-container" style="width:175;height:400;">
									<span class="chat-list-open-close"><i onclick="load_presets(<?php echo $site_cam['id'] ?>);" class="fa fa-crosshairs"></i></span>
									<div class="chat-list-body custom-scroll">
									<!-- h5 style="text-align:center;">PTZ Controls</h5> -->								
											<div id="<?php echo $site_cam['id'] ?>" class="well well-sm">
											<div id='ptz-control' onload='load_presets()' name='ptz-control'>
											<div class='btn-group-vertical btn-group-justified'> 
												<a href="javascript:void(0);" onclick='control_ptz(this);' alt='upleft' title='upleft' target='' class="btn btn-default btn-xs"><small><span class="glyphicon glyphicon-fullscreen"></span></small></a>
												<a href="javascript:void(0);" onclick='control_ptz(this);' target=''  title='Move Up' alt='up' title='Move Up' class="btn btn-default btn-xs"><i class="fa fa-chevron-up"></i></a>
												<a href="javascript:void(0);" onclick='control_ptz(this);' alt='upright' title='upright' target='' class="btn btn-default btn-xs"><small><span class="glyphicon glyphicon-fullscreen"></span></small></a>
											</div>
											<div class='btn-group-vertical btn-group-justified'> 
												<a href="javascript:void(0);" onclick='control_ptz(this);' alt='left' title='Left' target='' class="btn btn-default btn-xs"><i class="fa fa-chevron-left"></i> </a>
												<a href="javascript:void(0);" onclick='control_ptz(this);' alt='home' title='Home' target='' class="btn btn-default btn-xs"><i class="fa fa-home"></i></a>
												<a href="javascript:void(0);" onclick='control_ptz(this);' alt='right' title='Right' target='' class="btn btn-default btn-xs"><i class="fa fa-chevron-right"></i></a>
											</div>
											<div class='btn-group-vertical btn-group-justified'> 
												<a href="javascript:void(0);" onclick='control_ptz(this);' alt='downleft' title='zoom_out' target='' class="btn btn-default btn-xs"><small><span class="glyphicon glyphicon-fullscreen"></span></small></a>
												<a href="javascript:void(0);" onclick='control_ptz(this);' alt='down' title='zoom_out' target='' class="btn btn-default btn-xs"><i class="fa fa-chevron-down"></i></a>
												<a href="javascript:void(0);" onclick='control_ptz(this);' alt='downright' title='zoom_out' target='' class="btn btn-default btn-xs"><small><span class="glyphicon glyphicon-fullscreen"></span></small></a>
											</div>
											</div>
											<br>
											<div class='btn-group btn-group-justified'> 
												<a href="javascript:void(0);" onclick='control_ptz(this);' alt='zoom_out' title='zoom_out' target='' class="btn btn-default btn-sm"><i class="fa fa-search-minus"></i></a>
												<a href="javascript:void(0);" onclick='control_ptz(this);' alt='zoom_in' title='zoom_in' target='' class="btn btn-default btn-sm"><i class="fa fa-search-plus"></i></a>
												<a href="javascript:void(0);" onclick='control_ptz(this);' alt='reset_zoom' title='reset_zoom' target='' class="btn btn-default btn-sm">Reset</a>
											</div>
											<br>
											<small>PTZ Speed
											<input class='slider slider-primary slider-xs' id='tilt_speed' name='tilt_speed' value='' 
													data-slider-max='100' 
													data-slider-value='50' 
													data-slider-selection = 'before' 
													data-slider-handle='round'>
											</input>

											<div class='smart-form'>
											<select class='form-control input-sm' id='ptz_presets' data-cam="<?php echo $site_cam['id'] ?>">
											<option>Home</option>
											<option>Preset 1</option>
											<option>Preset 2</option>										
											<option>Preset 3</option>
										</select>
											</small>
										<div class='btn-group btn-group-justified'> 
												<a href="#" onclick="set_active_camera(<?php echo $site_cam['id'] ?>);" id="configure_presets" alt='configure_presets' data-toggle='modal' data-target='#preset_config' title='Configure Presets' class="btn btn-default btn-xs"><i class="fa fa-cog"></i> Presets</a>
												<a href="javascript:void(0);" class="btn btn-default btn-sm"><i class="fa fa-cog"></i> OSD</a>
											</div>
										</div>
										
										<div class='smart-form'>
											<label class='toggle'>
												<input type='checkbox' name='autofocus' checked=''>
												<i data-swchon-text='ON' data-swchoff-text='OFF'></i><small>AutoFocus</small>
											</label>										
											<label class='toggle'>
												<input type='checkbox' name='autoiris' checked=''>
												<i data-swchon-text='ON' data-swchoff-text='OFF'></i><small>AutoIris</small>
											</label>
										</div>
									</div>
									</div>
									<!-- <div class="chat-list-footer">
										<div class="control-group">
											<form class="smart-form">
												<section>
													<label class="input">
														<input type="text" id="filter-chat-list" placeholder="PTZ Presets">	
													</label>
												</section>
											</form>
										</div>
									</div> -->	
								</div>
								</span>
								<!-- end content -->
							</div>
						</div>
						<!-- end widget div -->
					</div>
					<!-- end widget -->


				
							<?php }//eof foreach loop ?>
						
				</article>
				
	<!-- Placeholder	-->
			<article class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<div class="jarviswidget jarviswidget-placeholder" data-widget-hidden="true">
				<div class="widget-body widget-hide-overflow no-padding">
				
			<!--	<img src="http://berg.themattc.com:9000/?action=stream" height="100%" width="100%">	
				
				<script type="text/javascript">

/* Copyright (C) 2007 Richard Atterer, richardÂ©atterer.net
   This program is free software; you can redistribute it and/or modify it
   under the terms of the GNU General Public License, version 2. See the file
   COPYING for details. */

var imageNr = 0; // Serial number of current image
var finished = new Array(); // References to img objects which have finished downloading
var paused = false;

function createImageLayer() {
  var img = new Image();
  img.style.position = "absolute";
  img.style.zIndex = -1;
  img.onload = imageOnload;
  img.onclick = imageOnclick;
  img.src = "berg.themattc.com:9000/?action=snapshot&n=" + (++imageNr);
  var webcam = document.getElementById("webcam");
  webcam.insertBefore(img, webcam.firstChild);
}

// Two layers are always present (except at the very beginning), to avoid flicker
function imageOnload() {
  this.style.zIndex = imageNr; // Image finished, bring to front!
  while (1 < finished.length) {
    var del = finished.shift(); // Delete old image(s) from document
    del.parentNode.removeChild(del);
  }
  finished.push(this);
  if (!paused) createImageLayer();
}

function imageOnclick() { // Clicking on the image will pause the stream
  paused = !paused;
  if (!paused) createImageLayer();
}

</script>
<body onload="createImageLayer();">

<div id="webcam"><noscript><img src="/?action=snapshot" /></noscript></div>	-->
				
				</div>
				</div>
				</article>	
	
			</div>
			<!-- end row -->
		
				</section>
		<!-- end widget grid -->
	


	</div>
	<!-- END MAIN CONTENT -->
</div>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<script src="<?php echo ASSETS_URL; ?>/js/plugin/flot/jquery.flot.cust.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/flot/jquery.flot.resize.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/flot/jquery.flot.tooltip.min.js"></script>

<!-- Vector Maps Plugin: Vectormap engine, Vectormap language -->
<script src="<?php echo ASSETS_URL; ?>/js/plugin/vectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/vectormap/jquery-jvectormap-world-mill-en.js"></script>

<!-- Full Calendar -->
<script src="<?php echo ASSETS_URL; ?>/js/plugin/fullcalendar/jquery.fullcalendar.min.js"></script>

<script>

	$(document).ready(function() {

		/*
		 * PAGE RELATED SCRIPTS
		 */
		 
	

		$(".js-status-update a").click(function() {
			var selText = $(this).text();
			var $this = $(this);
			$this.parents('.btn-group').find('.dropdown-toggle').html(selText + ' <span class="caret"></span>');
			$this.parents('.dropdown-menu').find('li').removeClass('active');
			$this.parent().addClass('active');
		});

		/*
		* TODO: add a way to add more todo's to list
		*/

		// initialize sortable
		$(function() {
			$("#sortable1, #sortable2").sortable({
				handle : '.handle',
				connectWith : ".todo",
				update : countTasks
			}).disableSelection();
		});

		// check and uncheck
		$('.todo .checkbox > input[type="checkbox"]').click(function() {
			var $this = $(this).parent().parent().parent();

			if ($(this).prop('checked')) {
				$this.addClass("complete");

				// remove this if you want to undo a check list once checked
				//$(this).attr("disabled", true);
				$(this).parent().hide();

				// once clicked - add class, copy to memory then remove and add to sortable3
				$this.slideUp(500, function() {
					$this.clone().prependTo("#sortable3").effect("highlight", {}, 800);
					$this.remove();
					countTasks();
				});
			} else {
				// insert undo code here...
			}

		})
		// count tasks
		function countTasks() {

			$('.todo-group-title').each(function() {
				var $this = $(this);
				$this.find(".num-of-tasks").text($this.next().find("li").size());
			});

		}



		/*
		 * CHAT
		 */

		$.filter_input = $('#filter-chat-list');
		$.chat_users_container = $('#chat-container > .chat-list-body')
		$.chat_users = $('#chat-users')
		$.chat_list_btn = $('#chat-container > .chat-list-open-close');
		$.chat_body = $('#chat-body');

		/*
		* LIST FILTER (CHAT)
		*/

		// custom css expression for a case-insensitive contains()
		jQuery.expr[':'].Contains = function(a, i, m) {
			return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
		};

		function listFilter(list) {// header is any element, list is an unordered list
			// create and add the filter form to the header

			$.filter_input.change(function() {
				var filter = $(this).val();
				if (filter) {
					// this finds all links in a list that contain the input,
					// and hide the ones not containing the input while showing the ones that do
					$.chat_users.find("a:not(:Contains(" + filter + "))").parent().slideUp();
					$.chat_users.find("a:Contains(" + filter + ")").parent().slideDown();
				} else {
					$.chat_users.find("li").slideDown();
				}
				return false;
			}).keyup(function() {
				// fire the above change event after every letter
				$(this).change();

			});

		}

		// on dom ready
		listFilter($.chat_users);

		// open chat list
		$.chat_list_btn.click(function() {
			$(this).parent('#chat-container').toggleClass('open');
		})

		$.chat_body.animate({
			scrollTop : $.chat_body[0].scrollHeight
		}, 500);

	});

</script>

<?php 
	//include footer
	include("inc/footer.php"); 
?>