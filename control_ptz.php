<?php
	require_once "lib/db_connect.php";

	$action		=	$_POST['command'];
	$camera_id = 	$_POST['camera_id'];


	//get this camera details
	$sql = "SELECT * FROM cameras WHERE id = '$camera_id'";
	$result = mysql_query($sql) or die(mysql_error());

	if( mysql_num_rows($result) == 1) {
		$camera_data = mysql_fetch_assoc($result);
	}
	else {
		return;
	}

	$camera_ip  = $camera_data['cam_user'].":".$camera_data['cam_pwd']."@".$camera_data['wan_ip'].":".$camera_data['port'];
	$camera_num = $camera_data['cam_num'];

	$camera		= 	array("ip"	=>	$camera_ip, "name"	=>	$camera_data['description'] );
	
//print_r($camera);

	$ptz_commands = array(
			 "up"		=>	array(
			 					"name"	=>	"Move Up"
			 				   ,"cmd"	=>	"move"
			 				   ,"val"	=>	"up"
			 				)
			,"down"		=>	array(
								"name"	=>	"Move Down"
							   ,"cmd"	=>	"move"
							   ,"val"	=>	"down"
							)
			,"left"		=>	array(
								"name"	=>	"Move Left"
							   ,"cmd"	=>	"move"
							   ,"val"	=>	"left"
							)
			,"right"	=>	array(
								"name"	=>	"Move Right"
							   ,"cmd"	=>	"move"
							   ,"val"	=>	"right"
							)
			,"upleft"	=>	array(
								"name"	=>	"Move Up Left"
							   ,"cmd"	=>	"move"
							   ,"val"	=>	"upleft"
							)
			,"upright"	=>	array(
								"name"	=>	"Move Up Right"
							   ,"cmd"	=>	"move"
							   ,"val"	=>	"upright"
							)
			,"downleft"	=>	array(
								"name"	=>	"Move Bottom Left"
							   ,"cmd"	=>	"move"
							   ,"val"	=>	"downleft"
							)
			,"downright"=>	array(
								"name"	=>	"Move Bottom Right"
							   ,"cmd"	=>	"move"
							   ,"val"	=>	"downright"
							)
			,"home"		=>	array(
								"name"	=>	"Move Home"
							   ,"cmd"	=>	"move"
							   ,"val"	=>	"home"
							)
			,"zoom_in"	=>	array(
								"name"	=>	"Zoom In"
							   ,"cmd"	=>	"rzoom"
							   ,"val"	=>	"1000"
							)
			,"zoom_out"	=>	array(
								"name"	=>	"Zoom Out"
							   ,"cmd"	=>	"rzoom"
							   ,"val"	=>	"-1000"
							)
			,"focus_far"	=>	array(
								"name"	=>	"Focus Further"
							   ,"cmd"	=>	"focus_far"
							   ,"val"	=>	"1"
							)
			,"focus_near"	=>	array(
								"name"	=>	"Focus Nearer"
							   ,"cmd"	=>	"focus_near"
							   ,"val"	=>	"-1"
							)
	);

	if ( !empty($action) ) {

		$header = array();		
		$header[] = 'Authorization: username="nvradmin"';

		if($action == "get_positions") {
			$urlRequest = "http://{$camera['ip']}/axis-cgi/com/ptz.cgi?camera=$camera_num&query=presetposcam";
			//echo $urlRequest;
			$select_options = array();
			// Get cURL resource
			$curl = curl_init();
			// Set some options - we are passing in a useragent too here
			curl_setopt_array($curl, array(
			    CURLOPT_RETURNTRANSFER => 1,
			    CURLOPT_URL => $urlRequest
			));
			// Send the request & save response to $resp
			$resp = curl_exec($curl);

			// Close request to clear up some resources
			curl_close($curl);

			$data = explode("\r\n", $resp);

			// Remove first and last items
			array_shift($data);
			array_pop($data);

			if(!empty($data) && $data[0] != "PTZ disabled") {
				//print_r($data);
				foreach ($data as $position) {
					$split = explode("=", $position);
					$key = str_replace("presetposno", "", $split[0]);
					$select_options[] = array("key" => $key, "val" => $split[1]);
				}
			}

			$response = array_reverse($select_options);
		}
		elseif($action == "save_preset") {
			$preset_name = $_POST['name'];
			$urlRequest = "http://{$camera['ip']}/axis-cgi/com/ptzconfig.cgi?camera=$camera_num&setserverpresetname=$preset_name";

			// Get cURL resource
			$curl = curl_init();
			// Set some options - we are passing in a useragent too here
			curl_setopt_array($curl, array(
			    CURLOPT_RETURNTRANSFER => 1,
			    CURLOPT_URL => $urlRequest
			));
			// Send the request & save response to $resp
			$resp = curl_exec($curl);

			// Close request to clear up some resources
			curl_close($curl);
			
			$response["msg"] = $resp;
		}
		elseif($action == "delete_preset") {
			$preset_no = $_POST['preset_no'];
			$urlRequest = "http://{$camera['ip']}/axis-cgi/com/ptzconfig.cgi?camera=$camera_num&removeserverpresetno=$preset_no";

			// Get cURL resource
			$curl = curl_init();
			// Set some options - we are passing in a useragent too here
			curl_setopt_array($curl, array(
			    CURLOPT_RETURNTRANSFER => 1,
			    CURLOPT_URL => $urlRequest
			));
			// Send the request & save response to $resp
			$resp = curl_exec($curl);

			// Close request to clear up some resources
			curl_close($curl);
			
			$response["msg"] = $resp;
		}
		elseif($action == "load_position") {
			$preset_no = $_POST['preset_no'];
			$urlRequest = "http://{$camera['ip']}/axis-cgi/com/ptz.cgi?camera=$camera_num&gotoserverpresetno=$preset_no";

			// Get cURL resource
			$curl = curl_init();
			// Set some options - we are passing in a useragent too here
			curl_setopt_array($curl, array(
			    CURLOPT_RETURNTRANSFER => 1,
			    CURLOPT_URL => $urlRequest
			));
			// Send the request & save response to $resp
			$resp = curl_exec($curl);

			// Close request to clear up some resources
			curl_close($curl);			

			$response["msg"] = $resp;
		}
		else {
			$urlRequest = "http://{$camera['ip']}/axis-cgi/com/ptz.cgi?camera=$camera_num&{$ptz_commands[$action]['cmd']}={$ptz_commands[$action]['val']}";

		    // Get cURL resource
			$curl = curl_init();
			// Set some options - we are passing in a useragent too here
			curl_setopt_array($curl, array(
			    CURLOPT_RETURNTRANSFER => 1,
			    CURLOPT_URL => $urlRequest
			));
			// Send the request & save response to $resp
			$resp = curl_exec($curl);		

			if(curl_errno($curl)) 
			{
			   echo curl_error($curl);
			}

			// Close request to clear up some resources
			curl_close($curl);

		    //file_get_contents($urlRequest);	    
		    $response["msg"]   = "$resp::Request sent to {$camera['name']} at IP {$camera['ip']} to {$ptz_commands[$action]['name']}.";
		}	    
	}
	echo json_encode($response);
?>