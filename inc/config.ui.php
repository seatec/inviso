<?php
//CONFIGURATION for SmartAdmin UI

//ribbon breadcrumbs config
//array("Display Name" => "URL");
$breadcrumbs = array(
	"Home" => APP_URL
);

/*navigation array config

ex:
"dashboard" => array(
	"title" => "Display Title",
	"url" => "http://yoururl.com",
	"icon" => "fa-home"
	"label_htm" => "<span>Add your custom label/badge html here</span>",
	"sub" => array() //contains array of sub items with the same format as the parent
)
*/

if( isset($site_obj) && !empty($site_obj) ) {
	$user_id = @$_SESSION['ID'];
	$sites = $site_obj->get_user_sites($user_id);
	$page_nav = array();


	if ( !empty($sites) ) {
		foreach( $sites as $site ) {
			$page_nav[$site['description']] = array(
				 "title"	=>	$site['description']
				,"url"		=>	APP_URL."/live.php?site_id=".$site['id']
				,"icon" 	=> "fa-inbox"
			);
		}
	}
}

// $page_nav = array(
// 	"Site 1" => array(
// 					"title" => "Site 1",
// 					"icon" 	=> "fa-inbox",
// 					"sub" 	=> array(
// 						"Pelco PTZ 1" => array(
// 							"title" => "Pelco PTZ 1",
// 							"url" => APP_URL."/live.php",
// 							"icon" => "fa-inbox"
// 						)
// 					)
// 	),
// );

//configuration variables
$page_title = "";
$page_css = array();
$no_main_header = false; //set true for lock.php and login.php
$page_body_prop = array(); //optional properties for <body>
?>