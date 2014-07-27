<?php
############################
## FaSpeed Test - Easily measure your uand download speeds
## Home Page:   http://www.brandonchecketts.com/speedtest/
## Author:      Brandon Checketts
## File:        index.php
## Version:     1.1
## Date:        2006-02-06
## Purpose:     Display a welcome page, or redirect straight to the 
##              download test if auto_start is enabled
###############################################################################

include("common.php");
ReadConfig("speedtest.cfg");

## Redirect immediately to download.php if auto_start = 1
if ($config->{'general'}->{'auto_start'}) {
    Header("Location: " . $config->{'general'}->{'base_url'} . "download.php");
    exit;
}
?>


<?php
include("header.html");
?>
<div id="speedtest_content">


    <h2><a href="download.php"> Begin Bandwidth Test </a></h2>
