<?php

require("common.php");
ReadConfig("speedtest.cfg");


## Save the results of this speedtest to the database, if enabled
if($config->{'database'}->{'enable'}) {
    $ip_matches = $config->{'database'}->{'ip_matches'};
    if( (! $ip_matches) || ($ip_matches && \preg_match("/$ip_matches/",$_SERVER['REMOTE_ADDR'])) ) {
        Debug("Saving to database");
        $dbh = mysql_connect(
            $config->{'database'}->{'host'},
            $config->{'database'}->{'user'},
            $config->{'database'}->{'password'}
        );
        $dbs = mysql_select_db( $config->{'database'}->{'database'}, $dbh);
        $table = $config->{'database'}->{'table'};
        /* @var $_SERVER type */
        $ip = $_SERVER['REMOTE_ADDR'];
    //    $upspeed = addslashes($_GET['upspeed']);
        $downspeed = addslashes($_GET['downspeed']);
        $sql = "
            INSERT INTO `$table`
            SET
                `ip_string` =  '$ip',
                `ip` = INET_ATON('$ip'),
                `timestamp` = NOW(),
                `downspeed` = '$downspeed'
        ";
        mysql_query($sql,$dbh);
    }
}



?>


<?php 
if(file_exists("header.html")) {
    ## Include "header.html" for a custom header, if the file exists
    include("header.html");
} else { 
    ## Else just print a plain header

}
?>
<div id="speedtest_contents">

<?php

    $bar_width = 400;

    $clean_down = CleanSpeed($_GET['downspeed']);
    $download_biggest = $_GET['downspeed'];
    print "<h2>Bandwidth Speed: $clean_down</h2>\n";
	?>
	<br>
	<hr>
	<?php $ip_address = $_SERVER['REMOTE_ADDR'];
	print "IP Address: $ip_address\n";
	?>
	<br>
	<hr>
	<?php
	

    ## Find the biggest value
    foreach($config->{'comparisons-download'} as $key=>$value) {
        if($value > $download_biggest) {
            $download_biggest = $value;
        }
    }
    ## Print a pretty table with a graph of the results
	print "<center>\n";
    print "<table>\n";
    foreach($config->{'comparisons-download'} as $key=>$value) {
        $this_bar_width = $bar_width / $download_biggest * $value;
        print "<tr> <td> $key </td> <td>".CleanSpeed($value)." </td> <td>\n";
        print "<img src=\"". $config->{'general'}->{'image_path'};
        print "bar.gif\" height=\"10\" width=\"$this_bar_width\" alt=\"$value kbps\" /> </td> </tr>\n";
    }
    $this_bar_width = $bar_width / $download_biggest * $_GET['downspeed'];
	
	
    print "<tr> <td> <h3>Your Speed: <br> </h3> </td> &nbsp; \n";
    print " &nbsp; <td> $clean_down </td> <td> &nbsp; <img src=\"";
    print "\n";
    print $config->{'general'}->{'image_path'} . "bar.gif\" height=\"10\" alt=\"$value kbps\" width=\"$this_bar_width\"> </td> </tr>\n";
    print "</table>\n";
    print "</center>\n";



    ## Don't display the upload stuff if we didn't get a speed to compare with
/* @var $_GET type */
if(isset($_GET['upspeed'])) {
        $clean_up = CleanSpeed($_GET['upspeed']);
        $upload_biggest = $_GET['upspeed'];
        print "<h2>Upload Speed: $clean_up</h2>\n";
        foreach($config->{'comparisons-upload'} as $key=>$value) {
            if($value > $upload_biggest) {
                $upload_biggest = $value;
            }
        }
        print "<table>\n";
        foreach($config->{'comparisons-upload'} as $key=>$value) {
            $this_bar_width = $bar_width / $upload_biggest * $value;
            print "<tr> <td> $key </td> <td>".CleanSpeed($value)."</td>\n";
            print "<td width='400'><img src=\"";
            print  $config->{'general'}->{'image_path'} ."bar.gif\" height=\"10\" width=\"$this_bar_width\" alt=\"$value kbps\" /></td></tr>\n";
        }
        $this_bar_width = $bar_width / $upload_biggest * $_GET['upspeed'];
        print "<tr> <td> <b>Your Speed</b> </td> <td>$clean_up</td> <td width='400'>";
        print "<img src=\"". $config->{'general'}->{'image_path'} ."bar.gif\" height=\"10\" width=\"$this_bar_width\"></td></tr>\n";
        print "</table>\n";
        }
        
?>

<br /><br />
<!--	<h2><a class="start_test" href="<?php echo $config->{'general'}->{'base_url'}; ?>download.php">Test Again</a></h2>	-->
<h2><A HREF="javascript:history.back(-1)">Go back</A></h2>



<br>
<h3>
<?php 
/*
echo ' Your IP Address: '; 
if ( isset($_SERVER["REMOTE_ADDR"]) )    { 
    echo '' . $_SERVER["REMOTE_ADDR"] . ' '; 
} else if ( isset($_SERVER["HTTP_X_FORWARDED_FOR"]) )    { 
    echo '' . $_SERVER["HTTP_X_FORWARDED_FOR"] . ' '; 
} else if ( isset($_SERVER["HTTP_CLIENT_IP"]) )    { 
    echo '' . $_SERVER["HTTP_CLIENT_IP"] . ' '; 
} 
*/
?>
</h3>

<?php


//$user_id = $_GET['id'];
$user_id = "1";

$where = "al.user_id = '$user_id' ";


$sql = "SELECT al.id as id, al.ip_string as ip_string, al.timestamp as timestamp, al.downspeed as downspeed, u.id, u.username as username FROM access_log al JOIN users u ON al.user_id = u.id ORDER BY al.timestamp DESC LIMIT 5";

$result = mysql_query($sql) or die(mysql_error());

$num = mysql_num_rows($result);
if($num > 0) {
//echo "<a href='add_camera.php?id=$user_id'>Add Camera </a> | ";
//echo "<a href='user_listing.php'>Back to User Listing </a>";
?>
<div class="container">
<div class="well">
                                        <h3> Access Log </h3>
                                  
									<form action="" name="listingForm" id="listingForm" method="post">
									<table class="table table-responsive table-hover table-condensed">
	<thead>
	<tr>

											<th>User Name</th>
											<th> Date/Time </th>
											<th> IP Address </th>
											<th> Speed</th>
	</tr>
</thead>
<tbody>

<?php
    
	while ($row = mysql_fetch_object($result)) {	
	$downspeed_MBs = round($row->downspeed  / 1024,2) . " Mbps";
	$dateTime= date($row->timestamp);
	?>
		<tr>
		<td><?php echo $row->username ?></td>
		<td><?php echo $dateTime ?></td>
		<td><?php echo $row->ip_string ?></td>
		<td><?php echo $downspeed_MBs ?></td>
	</tr>

	<?php	
	}
	?>
	                                            </tbody>
                                        </table>

</form>

	<?php
}
else {
	echo "No records found.";
}
?>



</div>
</div>
</div>

<?php if(file_exists("footer.html")) { include("footer.html"); } ?>

</body>
</html>

<?php
## Convert the raw speed value to a nicer value
function CleanSpeed($kbps) {
    if($kbps > 1024)   {
        $cleanspeed = round($kbps / 1024,2) . " Mbps";
    } else {
        $cleanspeed = round($kbps,2). " kbps";
    }
    return $cleanspeed;
}


