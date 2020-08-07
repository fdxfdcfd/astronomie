<?php
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
$videoURL = $data[0]['VideoList'][4];

$contentImg = file_get_contents($imageURL);
$contentVideo = file_get_contents($videoURL);
file_put_contents('./img/bloomsky.jpg', $contentImg);
file_put_contents('./video/bloomsky.mp4', $contentVideo);
