		<!-- Your GOOGLE ANALYTICS CODE Below -->
		<script type="text/javascript">
			var _gaq = _gaq || [];
				_gaq.push(['_setAccount', 'UA-XXXXXXXX-X']);
				_gaq.push(['_trackPageview']);
			
			(function() {
				var ga = document.createElement('script');
				ga.type = 'text/javascript';
				ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0];
				s.parentNode.insertBefore(ga, s);
			})();
		</script>
		<script type="text/javascript">

		$(function(){    			
			
		});

		function set_active_camera(cam_id){ 
			$("#active_camera").val(cam_id);
		}   

		function save_preset() {
			var preset_name = $("#preset_name").val();
			var cam_id      = $("#active_camera").val();
			$.ajax({
		        type: "POST",
		        url: "control_ptz.php",
		        dataType: 'json',
		        data: { command: "save_preset", name: preset_name, camera_id: cam_id },
		        cache: true,			        
		        success: function(data) {
		        	$("#preset_name").val("");			          	
					$.smallBox({
						title : "Position Saved Successfully.",
						// content : "<i class='fa fa-info'></i> <i>"+data.msg+"</i>",
						color : "#739E73",
						sound:false,
						iconSmall : "fa fa-check bounce animated",
						timeout : 2000
					});
		        },			 		        
			    async: false
		    });
		}	  			

		function delete_preset() {
			var preset_name = $("#preset_select").val();
			var cam_id      = $("#active_camera").val();
			
			$.ajax({
		        type: "POST",
		        url: "control_ptz.php",
		        dataType: 'json',
		        data: { command: "delete_preset", preset_no: preset_name, camera_id: cam_id },
		        cache: true,			        
		        success: function(data) {			          	
					$.smallBox({
						title : "Position Deleted Successfully.",
						// content : "<i class='fa fa-info'></i> <i>"+data.msg+"</i>",
						color : "#739E73",
						sound:false,
						iconSmall : "fa fa-check bounce animated",
						timeout : 2000
					});
		        },			 		        
			    async: false
		    });     
		}

		function load_presets(cam_id) {
			var $preset_sel = $('select#ptz_presets').empty();
			var $preset_config_sel = $('select#preset_select').empty();

			$.ajax({
		        type: "POST",
		        url: "control_ptz.php",
		        dataType: 'json',
		        data: { command: "get_positions", camera_id : cam_id },
		        cache: true,			        
		        success: function(data) {
		        	$preset_config_sel.append($("<option/>", {
				        value: "",
				        text: "- select -"
				    }));			          	
					$.each(data, function(i, record) {
					   $preset_sel.append($("<option/>", {
					        value: record.key,
					        text: record.val
					    }));

					   	$preset_config_sel.append($("<option/>", {
					        value: record.key,
					        text: record.val
					    }));
					});				
		        },			 		        
			    async: false
		    });	


		    $preset_sel.change( function() {
		    	var selected_pos = $(this).val();
		    	var cam_id = $(this).data("cam");
				$.ajax({
			        type: "POST",
			        url: "control_ptz.php",
			        dataType: 'json',
			        data: { command: "load_position", preset_no: selected_pos, camera_id : cam_id },
			        cache: true,			        
			        success: function(data) {			          	
						console.log(data);
			        },			 		        
				    async: false
			    });
			});
		}

			function control_ptz(obj) {
				var cmd = $(obj).attr("alt");	
				var cam_id = $(obj).closest("div.well").attr("id");				

				$.ajax({
			        type: "POST",
			        url: "control_ptz.php",
			        dataType: 'json',
			        data: { command: cmd, camera_id: cam_id },
			        cache: true,
			        beforeSend: function() {
			            // cog placed
			       		$.smallBox({
							title : "Sending command to device.",
							content : "<i class='fa fa-cog fa-spin'></i> <i>Please wait...</i>",
							color : "#296191",
							sound:false,
							iconSmall : "fa fa-clock-o animated",
							timeout : 1500
						});     
			        },
			        success: function(data) {
			          	$.smallBox({
							title : "Command Successfully Sent.",
							content : "<i class='fa fa-info'></i> <i>"+data.msg+"</i>",
							color : "#739E73",
							sound:false,
							iconSmall : "fa fa-check bounce animated",
							timeout : 4000
						});
			        },			 
			        error: function(xhr, ajaxOptions, thrownError) {
			 			$.smallBox({
							title : "Failed to send command.",
							content : "<i class='fa fa-warning'></i> <i>"+thrownError+"</i>",
							color : "#C46A69",
							sound:false,
							iconSmall : "fa fa-times animated",
							timeout : 4000
						});
			        },
			        async: true
			    });				
			}
		</script>


	<!-- Modal -->
		<div class="modal fade" id="preset_config" tabindex="-1" role="dialog" aria-labelledby="Label" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							&times;
						</button>
						<h4 class="modal-title" id="Label">Manage Presets</h4>
					</div>
					<div class="modal-body">
						
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="preset_name"> Preset Name</label>
									<input type="text" class="form-control" id="preset_name" placeholder="Enter name of new preset." required />									
								</div>								
							</div>
							<div class="col-md-6">
								<div class="form-group" style="margin-top: 23px;">
									<button onclick="save_preset()" type="submit" class="btn btn-success btn-sm" id="save_preset" data-dismiss="modal">
										<span class="glyphicon glyphicon-floppy-disk"></span> Save Preset
									</button>
								</div>
							</div>							
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="preset_select"> Select Preset</label>
									<select class="form-control" id="preset_select" required>
										<option>- select -</option>										
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group" style="margin-top: 23px;">
									<button onclick="delete_preset()" type="submit" class="btn btn-danger btn-sm" id="delete_preset" data-dismiss="modal">
										<span class="glyphicon glyphicon-trash"></span> Delete Preset
									</button>
								</div>
							</div>
						</div>						
		
					</div>
					<input type="hidden" id="active_camera" name="active_camera" value="">
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">
							Cancel
						</button>		
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

	</body>
</html>