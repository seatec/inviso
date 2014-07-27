<?php
	require_once "lib/db_connect.php";
	// load the SITE Class
	require_once "lib/site.php";
	$site_obj = new Site($conn);

	$site_id		=	$_POST['site_id'];

	if ( !empty($site_id) ) {
		$select_options = array();
		$site_cameras = $site_obj->get_site_cameras($site_id);
			
		if( !empty($site_cameras) ) {
			foreach ($site_cameras as $site_cam)
				$select_options[] = array("key" => $site_cam['id'], "val" => $site_cam['description'] );			
		}

		$response = array_reverse($select_options);
	}
	echo json_encode($response);
?>