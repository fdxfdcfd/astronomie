<?php
	
	// get the latest BloomSky Image file
	// fill in your API key and point your image src to this file
	
	// Jachym, meteotemplate.com
	
	
	header('Content-type: image/png');
	
	$APIkey = " wt6std6jvt3PqdCat4K_58jMxabIuNQ="; // your API key
	
	$url = "https://api.bloomsky.com/api/skydata";

	$opts = array(
	'http'=>array(
	'method'=>"GET",
	'header'=>"Authorization: ".$APIkey."\r\n"
	)
	);
	$context = stream_context_create($opts);
	$file = file_get_contents($url, false, $context);
	
	$data = json_decode($file, true);
	
	$imageURL = $data[0]['Data']['ImageURL'];
	
	echo file_get_contents(  $imageURL );
	
?>