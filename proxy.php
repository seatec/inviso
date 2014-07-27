<?php
header('Content-type: application/xml');
$url=$_GET['url'];
$json=file_get_contents($url);
echo $json;
?>