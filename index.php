<?php

header("Location: login.php");
exit;

//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Pelco PTZ 1";
$camera_desc = "Pelco PTZ Cam 1";
$camera_url = "http://nvradmin:inviso@50.133.34.33:9191/axis-cgi/mjpg/video.cgi?resolution=4CIF&compression=30&fps=5&camera=1";
$camera_url2 = "http://nvradmin:inviso@50.133.34.33:9192/axis-cgi/mjpg/video.cgi?resolution=4CIF&compression=30&fps=5&camera=1";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
 $page_nav["Site 1"]["sub"]["Pelco PTZ 1"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<!-- MAIN CONTENT -->
	<div id="content">
		<div class="row">
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-6">
				<h1 class="page-title txt-color-blueDark"> </h1>
			</div>
			<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
			</div>
		</div>
		<!-- widget grid -->
		<section id="widget-grid" class="">
			<!-- row -->
			<div class="row">
				<article class="col-xs-12 col-sm-7 col-md-7 col-lg-6">
					<!-- new widget -->
					<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
						<header>
							<span class="widget-icon"> <i class="glyphicon glyphicon-eye-open"></i> </span>
							<h2> <?php echo $camera_desc; ?></h2>
							<div class="widget-toolbar non-hidden">
								<!-- add: non-hidden - to disable auto hide -->
								<div class="btn-group">
									<button class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown">
										Settings <i class="fa fa-caret-down"></i>
									</button>
									<ul class="dropdown-menu pull-right js-status-update">
										<li>
											<a href="javascript:void(0);"> <?php echo $camera_desc; ?></a>
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
									</ul>
								</div>
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
							<img src=" <?php echo $camera_url; ?> " width="100%" height="100%" alt="Could not load the stream, please refresh or restart the camera.">
								<!-- CHAT CONTAINER -->
								<div id="chat-container">
									<span class="chat-list-open-close"><i class="fa fa-crosshairs"></i></span>
									<div class="chat-list-body custom-scroll">
										<ul id="chat-users">
											<li>
											<h5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PTZ Controls</h5>
											</li>
											<li>
												<a href="javascript:void(0);" class="btn btn-default" rel="tooltip" data-placement="left" data-original-title="Zoom In"><i class="fa fa-search-plus"></i> Zoom In</a>
											</li>
											<li>
												<a href="javascript:void(0);" class="btn btn-default" rel="tooltip" data-placement="left" data-original-title="Zoom Out"><i class="fa fa-search-minus"></i> Zoom Out</a>
											</li>
										</ul>
									</div>
								<div class="chat-list-footer">
										<div class="control-group">
											<form class="smart-form">
												<section>
													<label class="input">
														<input type="text" id="filter-chat-list" placeholder="PTZ Presets">	
													</label>
												</section>
											</form>
										</div>
									</div>	
								</div>
								<!-- end content -->
							</div>
						</div>
						<!-- end widget div -->
					</div>
					<!-- end widget -->
				</article>

				<article class="col-xs-12 col-sm-7 col-md-7 col-lg-6">
					<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
						<header>
							<span class="widget-icon"> <i class="glyphicon glyphicon-eye-open"></i> </span>
							<h2> <?php echo $camera_desc; ?></h2>
							<div class="widget-toolbar non-hidden">
								<!-- add: non-hidden - to disable auto hide -->
								<div class="btn-group">
									<button class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown">
										Settings <i class="fa fa-caret-down"></i>
									</button>
									<ul class="dropdown-menu pull-right js-status-update">
										<li>
											<a href="javascript:void(0);"> <?php echo $camera_desc; ?></a>
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
									</ul>
								</div>
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
							<!--	<img src="http://nvradmin:inviso@50.133.34.33:9191/axis-cgi/mjpg/video.cgi?resolution=4CIF&compression=30&fps=5&camera=1" width="100%" height="100%" alt="Could not load the stream, please refresh or restart the camera.">	-->
							<img src=" <?php echo $camera_url2; ?> " width="100%" height="100%" alt="Could not load the stream, please refresh or restart the camera.">
								<!-- CHAT CONTAINER -->
								<div id="chat-container">
									<span class="chat-list-open-close"><i class="fa fa-crosshairs"></i></span>
									<div class="chat-list-body custom-scroll">
										<ul id="chat-users">
											<li>
											<h5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PTZ Controls</h5>
											</li>
											<li>
												<a href="javascript:void(0);" class="btn btn-default" rel="tooltip" data-placement="left" data-original-title="Zoom In"><i class="fa fa-search-plus"></i> Zoom In</a>
											</li>
											<li>
												<a href="javascript:void(0);" class="btn btn-default" rel="tooltip" data-placement="left" data-original-title="Zoom Out"><i class="fa fa-search-minus"></i> Zoom Out</a>
											</li>
											    <li>
											</li>
										    	<li>
											</li>
										</ul>
									</div>
								<div class="chat-list-footer">
										<div class="control-group">
											<form class="smart-form">
												<section>
													<label class="input">
														<input type="text" id="filter-chat-list" placeholder="PTZ Presets">	
													</label>
												</section>
											</form>
										</div>
									</div>	
								</div>
								<!-- end content -->
							</div>
						</div>
						<!-- end widget div -->
					</div>
					<!-- end widget -->
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
		 * PTZ
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