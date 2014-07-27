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
$current_camera_id = (!empty($_GET['camera_id']))? $_GET['camera_id']:0;

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


			<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget" id="wid-id-0" data-widget-sortable="false" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-deletebutton="false">
						<!-- widget options:
							usage: class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
							
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
							<span class="widget-icon"> <i class="fa fa-search"></i> </span>
							<h2>Search form </h2>				
							
						</header>

						<!-- widget div-->
						<div>
							
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
								
							</div>
							<!-- end widget edit box -->
							
							<!-- widget content -->
							<div class="widget-body no-padding">
								
								<form method="post" action="" id="search-form" class="smart-form" novalidate="novalidate">
									<header>
										Use this form to search the video archive.
									</header>									

									<fieldset>
										<!-- <div class="row">
											<section class="col col-6">
												<label class="select">
													<select name="interested">
														<option value="0" selected="" disabled="">Interested in</option>
														<option value="3">video</option>
													</select> <i></i> </label>
											</section>
											<section class="col col-6">
												<label class="select">
													<select name="budget">
														<option value="0" selected="" disabled="">Budget</option>
														<option value="1">less than 5000$</option>
														<option value="4">more than 20000$</option>
													</select> <i></i> </label>
											</section>
										</div> -->

										<div class="row">
											<section class="col col-6">
												<label class="input" style="display: inline-block;"> <i class="icon-append fa fa-calendar"></i>
													<input type="text" name="startdate" id="startdate" placeholder="Starting date" value="<?php echo @$_POST['startdate'] ?>">
												</label>
												<label class="select" style="display: inline-block;width:55px;">
													<select name="starthour">
														<option value="">HH</option>
														<?php for($i = 0;$i <= 24;$i++){ 
																$selected = ( $_POST['starthour'] == $i )? 'selected="selected"':'';
															?>
															<option <?php echo $selected ?> value="<?php echo sprintf("%02d", $i) ?>"><?php echo sprintf("%02d", $i) ?></option>
														<?php } ?>														
													</select> <i></i> 
												</label>
												<label class="select" style="display: inline-block;width:60px;">
													<select name="startminute">
														<option value="">MM</option>
														<?php for($i = 0;$i <= 60;$i+=5){ 
															$selected = ( $_POST['startminute'] == $i )? 'selected="selected"':'';
															?>
															<option <?php echo $selected ?> value="<?php echo sprintf("%02d", $i) ?>"><?php echo sprintf("%02d", $i) ?></option>
														<?php } ?>	
													</select> <i></i> 
												</label>
											</section>
											<section class="col col-6">
												<label class="input" style="display: inline-block;"> <i class="icon-append fa fa-calendar"></i>
													<input type="text" name="finishdate" id="finishdate" placeholder="Ending date" value="<?php echo @$_POST['finishdate'] ?>">
												</label>
												<label class="select" style="display: inline-block;width:55px;">
													<select name="finishhour">
														<option value="">HH</option>
														<?php for($i = 0;$i <= 24;$i++){ 
															$selected = ( $_POST['finishhour'] == $i )? 'selected="selected"':'';
															?>
															<option <?php echo $selected ?> value="<?php echo sprintf("%02d", $i) ?>"><?php echo sprintf("%02d", $i) ?></option>
														<?php } ?>
													</select> <i></i> 
												</label>
												<label class="select" style="display: inline-block;width:60px;">
													<select name="finishminute">
														<option value="">MM</option>
														<?php for($i = 0;$i <= 60;$i+=5){ 
															$selected = ( $_POST['finishminute'] == $i )? 'selected="selected"':'';
															?>
															<option <?php echo $selected ?> value="<?php echo sprintf("%02d", $i) ?>"><?php echo sprintf("%02d", $i) ?></option>
														<?php } ?>
													</select> <i></i> 
												</label>
											</section>
										</div>

										<div class="row">
											<section class="col col-6">
												<label class="select" style="display: inline-block;width:200px;">
													<select name="site_id" id="site_id">
														<option value=""> - select site - </option>
														<?php foreach($sites as $site){ 
																$selected = ( $_REQUEST['site_id'] == $site['id'] )? 'selected="selected"':'';
															?>
															<option <?php echo $selected ?> value="<?php echo $site['id'] ?>"><?php echo $site['description'] ?></option>
														<?php } ?>														
													</select> <i></i> 
												</label>
											</section>
											<section class="col col-6">
												<label class="select" style="display: inline-block;width:200px;">
													<select name="camera_id" id="camera_id">
														<option value=""> - select site first- </option>																											
													</select> <i></i> 
												</label>
											</section>
											</div>
											<div class="row">
											<section class="col col-6">
												<label class="select" style="display: inline-block;width:150px;">
													<select name="playback_rate" id="playback_rate">
														<option value="1/1"> Playback Speed </option>
														<option value="1/8"> .125x </option>
														<option value="1/4"> .25x </option>
														<option value="1/2"> .5x </option>		
														<option value="1/1"> 1x </option>	
														<option value="2/1"> 2x </option>	
														<option value="4/1"> 4x </option>	
														<option value="8/1"> 8x </option>														
													</select> <i></i> 
												</label>
												</section>
												<section class="col col-6">
												<label class="select" style="display: inline-block;width:150px;">
													<select name="event_id" id="event_id">
														<option value="Continuous"> Event Type </option>
														<option value="Continuous"> Continuous </option>
														<option value="Record"> Record </option>
														<option value="Motion"> Motion </option>		
														<option value="PTZ"> PTZ </option>	
														<option value="Tampering"> Tampering </option>	
														<option value="Manual"> Manual </option>														
													</select> <i></i> 
												</label>
											</section>
										</div>
										<input type="hidden" id="cam_id" name="cam_id" value="<?php echo $_REQUEST['camera_id'] ?>">
									</fieldset>
									<footer>
										<button type="submit" name="btnSearch" class="btn btn-primary">
											Search 
										</button>
									</footer>
								</form>

							</div>
							<!-- end widget content -->
							
						</div>
						<!-- end widget div -->
						
					</div>
					<!-- end widget -->	

					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget" id="wid-id-1" data-widget-sortable="false" data-widget-colorbutton="false" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-deletebutton="false">						
						<header>
							<span class="widget-icon"> <i class="fa fa-equal"></i> </span>
							<h2>Video Playback </h2>	
							
						</header>

						<!-- widget div-->
						<div>
							
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
								
							</div>
							<!-- end widget edit box -->
							
							<!-- widget content -->
							<div class="widget-body no-padding">
								<?php 
									//form has been submitted
									if ( isset($_POST['btnSearch']) ) {
										$camera_id = $_POST['camera_id'];
										$startdate = $_POST['startdate'];
										$starthour = $_POST['starthour'];
										$startminute = $_POST['startminute'];
										$playback_rate = $_POST['playback_rate'];
										$event_id = $_POST['event_id'];
										
	// Matt // Added -04:00 to starttime so now playback is no longer 4 hrs off
										$starttime = ( !empty($startdate) )?"&starttime=".$startdate."T"."$starthour:$startminute:00-04:00":"";

										$stopdate = $_POST['finishdate'];
										$stophour = $_POST['finishhour'];
										$stopminute = $_POST['finishminute'];
										
	// Matt // Added -04:00 to stoptime so now playback is no longer 4 hrs off
										$stoptime = ( !empty($stopdate) )?"&stoptime=".$stopdate."T"."$stophour:$stopminute:00-04:00":"";

										$site = strtolower($site_name);
										$camera_data = $site_obj->get_cam_details($camera_id);
										//$camera_ip  = $camera_data['cam_user'].":".$camera_data['cam_pwd']."@".$camera_data['wan_ip'].":".$camera_data['port'];
										$camera_ip  = $camera_data['wan_ip'].":9090";
										//$camera_ip  = $camera_data['wan_ip'].":".$camera_data['port'];

	// Matt // We need to use port from mysql for wan_ip and camera User
										//$camera_ip 	= "50.133.34.33:9090"; //root:inviso@
										
	// Matt // Added url_params so the recordings array is returned in the correct order in the table (start time from user input should be at the top of the list although this seems backwards it is correct)
										$url_params = "&recordingstatus=completed&sortorder=ascending&eventid=$event_id";
										$urlRequest = "http://$camera_ip/axis-cgi/record/list.cgi?listentity=recording$starttime$stoptime$url_params";
										$select_options = array();
										$resp = simplexml_load_file( $urlRequest );									
										$recordings_data = $resp->recordings;

										//total records
										$total_num_records = $recordings_data['totalnumberofrecordings'];

										//matched records
										$num_records = $recordings_data['numberofrecordings'];

										$recordings = array();
										$video_to_play = null;
										
										if( $num_records > 0 ) { 
											foreach ($recordings_data->recording as $item)
												$recordings[] = $item;
										}

										//echo"<pre>";print_r($recordings);

										//loop through the recording list
										if( !empty($recordings) ) {
											//get the first element to load on the player
											$video_to_play = array_shift($recordings);
									
											$recordingid = $video_to_play['recordingid'];
											
	// Matt // Added starttime and stoptime to the play_url so in case continuous file is selected it wont playback forever
											$recordingstarttime = ( !empty($video_to_play['starttimelocal']) )? "&starttime=".$video_to_play['starttimelocal']:"";
											$recordingstoptime = ( !empty($video_to_play['stoptimelocal']) )? "&stoptime=".$video_to_play['stoptimelocal']:"";
											$play_url = "http://$camera_ip/axis-cgi/record/play.cgi?recordingid=$recordingid$recordingstarttime$recordingstoptime&rate=$playback_rate";

											//load it on the player
											?>		
											
											
											
											<SCRIPT LANGUAGE="JavaScript"> 
												var BaseURL; 
												BaseURL = "<?php echo $play_url ?>";
												var DisplayWidth = "100%"; 
												var DisplayHeight = "80%"; 
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
												<footer>
													<div class="well">
													<hr>
														<p><?php echo "<strong>Start Date/Time: </strong>".$startdate." ".$starthour.":".$startminute ?></p>
														<p><?php echo "<strong>End Date/Time: </strong>".$stopdate." ".$stophour.":".$stopminute ?></p>
														<p><?php echo "<strong>Playback Speed: </strong>".$playback_rate ?></p>
														<p><?php echo "<strong>Event: </strong>".$event_id ?></p>
													</div>
												</footer>
												
								</div>
						</div>
					</div>
					
				</article>
				
	<!-- mcc -- I Put the Recordings into their own widget to break the messed up video playing under table -->
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
					
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget" id="wid-id-4" data-widget-sortable="false" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-deletebutton="false">						
						<header>
							<span class="widget-icon"> <i class="fa fa-equal"></i> </span>
							<h2>Record/Event Listing </h2>				
						</header>

						<!-- widget div-->
						<div>
							
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
								
							</div>
							<!-- end widget edit box -->
							
							<!-- widget content -->
							<div class="widget-body no-padding">
												<header>
										
												</header>
											<?php
											//eof player
											//echo "<pre>";print_r($recordings);

											if( !empty($recordings) ) {
												?>
												<table id="tbl_feed" class="table table-striped table-condensed table-bordered table-hover table-responsive">
													<thead>
														<tr>
															<th>Sn</th>
															<th>Start Time</th>
															<th>Stop Time</th>
															<th>Recording Status</th>
															<th>Event</th>
															<th>Storage</th>
															<th>Play</th>
														</tr>
													</thead>
													<tbody>
												<?php
												$sn = 1;
												 
												foreach ($recordings as $record) {
													$start = substr($record['starttimelocal'], 0, -13);
													$end = substr($record['stoptimelocal'], 0, -13);
													
														
													//$record['locked'] = "No"
													?>
													<tr>
														<td><?php echo $sn ?></td>
														<td><?php echo $start ?></td>
														<td><?php echo $end ?></td>
														<td><?php echo $record['recordingstatus'] ?></td>
														<td><?php echo $record['eventid'] ?></td>
														<td><?php echo $record['diskid'] ?></td>
														<td>
	<!-- mcc: --	I was thinking here we will need to post this recordingid from the table and request it from $play_url  if it is set... Right now the href is trying to run the play.cgi on invisio server, not axis camera IP I started to mess with it on next commented out line.
															<a class="play_video" alt='Play this video' title='Play this Video' href="http://<?php echo $camera_ip ?>/axis-cgi/record/play.cgi?recordingid=<?php echo $record['recordingid'] ?>">		-->
															
														<!-- <a class="play_video" alt='Play this video' title='Play this Video' href="http://<?php echo $camera_ip ?>/operator/playWindow.shtml?imagepath=/axis-cgi/record/play.cgi?recordingid=<?php echo $record['recordingid'] ?>&videocodec=jpeg&startTimeLocal=<?php echo $record['starttimelocal'] ?>&stopTimeLocal=<?php echo $record['stoptimelocal'] ?>&img_width=640&img_height=400&audio=no&id0=<?php echo $record['recordingid'] ?>&rate=<?php echo $playback_rate ?>" target="_blank"> -->
														<a class="play_video" alt='Play this video' title='Play this Video' href="video_player.php?recordingid=<?php echo $record['recordingid'] ?>&camera_ip=<?php echo $camera_ip ?>&startTimeLocal=<?php echo $record['starttimelocal'] ?>&stopTimeLocal=<?php echo $record['stoptimelocal'] ?>&rate=<?php echo $playback_rate ?>" target="_blank">
																Play
																<i class="fa fa-play-circle-o"></i>
															</a>														
														</td>														
													</tr>
											<?php 
											$sn++;
											} 
											?>	
												</tbody>
											</table>
											<?php
											}
											
										} //oef !empty recordings
							
									}														
								?>
							</div>
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

<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/dataTables.colVis.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/dataTables.tableTools.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/dataTables.bootstrap.min.js"></script>

<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<script src="<?php echo ASSETS_URL; ?>/js/plugin/flot/jquery.flot.cust.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/flot/jquery.flot.resize.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/flot/jquery.flot.tooltip.min.js"></script>

<!-- Vector Maps Plugin: Vectormap engine, Vectormap language -->
<script src="<?php echo ASSETS_URL; ?>/js/plugin/vectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/vectormap/jquery-jvectormap-world-mill-en.js"></script>

<!-- Full Calendar -->
<script src="<?php echo ASSETS_URL; ?>/js/plugin/fullcalendar/jquery.fullcalendar.min.js"></script>

<!-- Get Sid js -->
<script src="<?php echo ASSETS_URL; ?>/js/get_sid.js"></script>

<script>

	$(document).ready(function() {

		/*
		 * PAGE RELATED SCRIPTS
		 */

		$('#tbl_feed').dataTable();

		// START AND FINISH DATE
		$('#startdate').datepicker({
			dateFormat : 'yy-mm-dd',
			prevText : '<i class="fa fa-chevron-left"></i>',
			nextText : '<i class="fa fa-chevron-right"></i>',
			onSelect : function(selectedDate) {
				$('#finishdate').datepicker('option', 'minDate', selectedDate);
			}
		});
		
		$('#finishdate').datepicker({
			dateFormat : 'yy-mm-dd',
			prevText : '<i class="fa fa-chevron-left"></i>',
			nextText : '<i class="fa fa-chevron-right"></i>',
			onSelect : function(selectedDate) {
				$('#startdate').datepicker('option', 'maxDate', selectedDate);
			}
		});

		var cam_dd  = $("#camera_id");
		var site_dd  = $("#site_id");

		var selected_site  = site_dd.val();

		if ( selected_site > 0 ) {
			var selected_cam = $("#cam_id").val();
			$.ajax({
		        type: "POST",
		        url: "get_site_cameras.php",
		        dataType: 'json',
		        data: { site_id: selected_site },
		        cache: true,
		        beforeSend: function() {
		           cam_dd.empty(); 	       		
		        },
		        success: function(data) {		        	
		          	cam_dd.append($("<option/>", {
				        value: "",
				        text: "- select camera -"
				    }));			          	
					$.each(data, function(i, record) {
					   	cam_dd.append($("<option/>", {
					        value: record.key,
					        text: record.val
					    }));
					});

					if( selected_cam > 0) cam_dd.val(selected_cam);
		        },			 
		        error: function(xhr, ajaxOptions, thrownError) {
		        	console.log(xhr);		 			
		        },
		        async: true
		    });
		};

		site_dd.change( function(e) {
			var site_id = $(this).val();			
			$.ajax({
		        type: "POST",
		        url: "get_site_cameras.php",
		        dataType: 'json',
		        data: { site_id: site_id },
		        cache: true,
		        beforeSend: function() {
		           cam_dd.empty(); 	       		
		        },
		        success: function(data) {		        	
		          	cam_dd.append($("<option/>", {
				        value: "",
				        text: "- select camera -"
				    }));			          	
					$.each(data, function(i, record) {
					   	cam_dd.append($("<option/>", {
					        value: record.key,
					        text: record.val
					    }));
					});
		        },			 
		        error: function(xhr, ajaxOptions, thrownError) {
		        	console.log(xhr);		 			
		        },
		        async: true
		    });

			e.preventDefault();
		});	

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