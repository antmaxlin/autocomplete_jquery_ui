<?php
	$db = mysql_connect('YOUR_DB_ADDRESS','YOUR_DB_USER','YOUR_DB_PASS') or die("Database error");
	mysql_select_db('YOUR_DB', $db); 
	$categories = TRUE;
	$images = TRUE;
	$default_img = "http://static.weflect.com/system/default_16.gif";
	

?>