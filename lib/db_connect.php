<?php
$host = "localhost";
$db_name = "inviso";
$username = "inviso";
$password = "nvruser";

$conn = mysql_connect($host, $username, $password) or die ('Error connecting to mysql');
mysql_select_db($db_name) or die ('Unable to select database!');
?>