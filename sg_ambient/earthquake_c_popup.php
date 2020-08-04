<?php  $scrpt_vrsn_dt  = 'earthquake_c_popup.php|00|2020-03-24|';  # release 2004
#-----------------------------------------------
#         PWS-Dashboard - Updates and support by 
#     Wim van der Kuil https://pwsdashboard.com/
#-----------------------------------------------
#       display source of script if requested so
#-----------------------------------------------
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
   $filenameReal = __FILE__;  
   $download_size = filesize($filenameReal);
   header('Pragma: public');
   header('Cache-Control: private');
   header('Cache-Control: no-cache, must-revalidate');
   header('Content-type: text/plain; charset=UTF-8');
   header('Accept-Ranges: bytes');
   header("Content-Length: $download_size");
   header('Connection: close');
   readfile($filenameReal);
   exit;}
elseif (!isset ($_REQUEST['test'])) 
     {  ini_set('display_errors', 0); error_reporting(0);}
else {  ini_set('display_errors', 1); error_reporting(1);}  
#
header('Content-type: text/html; charset=UTF-8');
# -------------------save list of loaded scrips;
$stck_lst        = basename(__FILE__).' ('.__LINE__.') version =>'.$scrpt_vrsn_dt.PHP_EOL;       
#
# -------------load weatherdata and all settings 
$scrpt          = 'PWS_livedata.php'; 
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') include_once =>'.$scrpt.PHP_EOL;
include_once $scrpt; 
#-----------------------------------------------
#                                script settings
#-----------------------------------------------
$show_close_x   = $close_popup;  // set to false or true to overrde settings // 
$ltxt_clsppp    = lang('Close');
$color_head     = "#FF7C39";  // attention color
# --------------------------      standard texts 
$txt_company    = 'earthquake-report.com';
$txt_href       = 'https://earthquake-report.com';
#
# ------------------------- translation of texts
$ltxt_url       = lang('Earthquake information courtesy of');
$ltxt_magnitude = lang('Magnitude');
$ltxt_depth     = lang('Depth');
$ltxt_distance  = lang('Distance');
$ltxt_time      = lang('Time');
$ltxt_title     = lang('Description');
$ltxt_link      = lang('Link');
$ltxt_latlon    = lang('Coordinates');
$ltxt_miles     = lang('Miles');
$ltxt_km        = lang('KMs');
$ltxt_updated   = lang('Updated');
$ltxt_is_sorted = lang('Table is sorted by');
$ltxt_sort_on   = lang('Click to sort the table on');
$ltxt_sort_on2   = lang('or on');
$ltxt_here      = lang('here');

$rows           = 99;

$sort           = 'dist'; 
if (isset ($_REQUEST['sort']))
     {  if (trim($_REQUEST['sort']) == 'dist')
             {  $sort  = 'dist';}
        elseif (trim($_REQUEST['sort']) == 'magn')
             {  $sort  = 'magn';}
        else {  $sort  = 'time';} 
        }
if ($sort == 'dist')
     {  $srt_nw = strtolower($ltxt_distance).' ('.$distanceunit.')';
        $srt_aa = strtolower($ltxt_time);
        $srt_bb = strtolower($ltxt_magnitude);
        $srt_a  = 'time';
        $srt_b  = 'magn';}
elseif ($sort == 'time')
     {  $srt_nw = strtolower($ltxt_time);
        $srt_aa = strtolower($ltxt_distance).' ('.$distanceunit.')';
        $srt_bb = strtolower($ltxt_magnitude);
        $srt_a  = 'dist';
        $srt_b  = 'magn';}
else {  $srt_nw = strtolower($ltxt_magnitude);
        $srt_aa = strtolower($ltxt_time);
        $srt_bb = strtolower($ltxt_distance).' ('.$distanceunit.')';
        $srt_a  = 'time';
        $srt_b  = 'dist';}
#
$b_clrs[0]      = '#FFFFFE';  # 0- < 2 
$b_clrs[1]      = '#FFFFFE';  # 0- < 2 
$b_clrs[2]      = '#EDEFF3';  # 2
$b_clrs[3]      = '#BEC2D6'; # 3
$b_clrs[4]      = '#01a4b4'; # 4
$b_clrs[5]      = '#9aba2f'; # 5
$b_clrs[6]      = '#ecb454'; # 6
$b_clrs[7]      = '#ff8841'; # 7  EEC44C
$b_clrs[8]      = '#f37867'; # 8 DA8B43
$b_clrs[9]      = '#CE5B38'; # 9
$b_clrs[10]     = '#C63A33'; # 10

# https://earthquake.usgs.gov/learn/topics/shakingsimulations/colors.php
# https://volcanoes.usgs.gov/observatories/hvo/felt_earthquakes.html
#
$json_string    = file_get_contents($fl_folder.$qks_fl);  # 'jsondata/eqnotification.txt'
$filetime       = filemtime ( $fl_folder.$qks_fl);
if (time() - $filetime > $quakesRefresh)
     {  $txt_updated    = '<b class="PWS_offline"> '.$online.lang('Offline').' </b>';}
else {  $txt_updated    = '<b class="PWS_online"> ' .$online.set_my_time_lng($filetime,true).' </b>' ;}
#
$parsed_json    = json_decode($json_string,true);
$arr_to_srt     = array ();
$n=0;
foreach ($parsed_json as $arr)
     {  $time_key       = strtotime($arr['date_time']);
        $lati           = $arr['latitude'];
	$longi          = $arr['longitude'];
	$distance       = 
	$arr['distance']= round(distance($lat, $lon, $lati, $longi)) ;
	$magnitude      = $arr['magnitude'];
	if ($sort == 'time')
	     {  $key    = $time_key;}
	elseif ($sort == 'dist')
	     {  $key    = 100000 - $distance;}
	else {  $n++;
	        $key    = round($magnitude,1);
	        $key    = (string) $key .'-'.$n;}
        $arr_to_srt[$key]= $arr;}
#
krsort($arr_to_srt);  #echo '<pre>'.count($arr_to_srt).print_r($arr_to_srt,true); exit;
$i      = 0;
/*      [title] => Minor earthquake - Sumbawa Region, Indonesia - April 25, 2019
        [magnitude] => 3.7
        [location] => SUMBAWA REGION, INDONESIA
        [depth] => 10
        [latitude] => -8.07
        [longitude] => 117.71
        [date_time] => 2019-04-25T01:24:59+00:00
        [link] => https://earthquake-report.com/2019/04/25/minor-earthquake-sumbawa-region-indonesia-april-25-2019/
        [distance] => 1233  */
if (trim($windunit) == 'mph') {$dist   = $ltxt_miles;} else {$dist   = $ltxt_km;}
echo '<!DOCTYPE html>
<html lang="'.substr($user_lang,0,2).'" style="background-color: white; ">
<head>
    <meta charset="UTF-8">
    <title>'.$ltxt_url.' '.$txt_company.'</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">'
.my_style().'
</head>
<body class="dark" style="background-color: white; overflow: hidden;">
    <div class="PWS_module_title font_head" style="width: 100%; height: 20px; " >
        <div style="padding-top: 2px;">'.PHP_EOL;   
# large X and optional close text
if ($show_close_x <> false ) // 
     { echo '            <span style="float: left;">&nbsp;X&nbsp;&nbsp;<small>'.$ltxt_clsppp.'</small></span>'.PHP_EOL;}
# middle text
echo '            <span style="color: '.$color_head.'">'
        .$ltxt_url
        .' <a href="'.$txt_href.'" target="_blank" style="color: white;">'
        .$txt_company.'</a></span>'.PHP_EOL; 
# time of downloaded file       
echo '            <span style="float:right;"><small>'.$txt_updated.'&nbsp;&nbsp;</small></span>
        </div>
    </div>'.PHP_EOL;
echo '<div class= "div_height" style="width: 100%; padding: 0px; text-align: left; font-size: 14px; overflow-x: hidden; overflow-y: scroll; ">
<table class="font_head" style=" width: 100%; margin: 0px auto; text-align: center; background-color: white; color: black;">'.PHP_EOL;

echo '<tr class="font_head" style="text-align: left; ">
        <td colspan="6">'
                .$ltxt_is_sorted.': <b>'.$srt_nw.'</b>.&nbsp;&nbsp;&nbsp;'
                .$ltxt_sort_on. ' <a href="./earthquake_c_popup.php?sort='.$srt_a.'"><b>'.$srt_aa.' </b> '.$ltxt_here.'.</a> '
                .$ltxt_sort_on2.' <a href="./earthquake_c_popup.php?sort='.$srt_b.'"><b>'.$srt_bb.' </b> '.$ltxt_here.'.</a></td></tr>'.PHP_EOL;

echo'<tr><th>'.$ltxt_magnitude.'</th><th>'.$ltxt_depth.'</th><th>'.$ltxt_distance.'</th><th>'
        .$ltxt_time.'</th><th>'.$ltxt_link.'</th><th style="text-align: left;">'.$ltxt_title.'</th></tr>'.PHP_EOL;  
foreach ($arr_to_srt as $key => $parsed_json) 
     {  #if ($i > $rows) {break;}
        $magnitude      = round ($parsed_json['magnitude'],2);
        $eqtitle        = $parsed_json['title'];
        $depth          = $parsed_json['depth'];
        $eqtime         = set_my_time( $parsed_json['date_time']);
        $lati           = $parsed_json['latitude'];
        $longi          = $parsed_json['longitude'];
        $eqdist         = $parsed_json['distance'];
        $link           = $parsed_json['link'];
        if (trim($windunit) == 'mph') 
             {$distance = round($eqdist * 0.621371);} 
        else {$distance = round($eqdist);}
        $key            = (int) ceil ($magnitude);
        if ($key > 10) {$key = 10;}
        $color          = $b_clrs[$key];
        echo '<tr>';
        echo  '<td style=" background-color: '.$color.'"><b>'.$magnitude.'</b></td>';
        echo  '<td>'.$depth.'</td>';
        echo  '<td>'.$distance.'<!-- '.$lati.','.$longi.' --></td>';
        echo  '<td>'.$eqtime.'</td>';
        echo  '<td><a href="'.$link.'" target="_blank">'.$ltxt_link.'</a></td>';
        echo  '<td style="text-align: left;"> '.$eqtitle.'</td>';
        echo '</tr>'.PHP_EOL;;
        }
echo '</table>
</div>'.PHP_EOL;
if (isset ($_REQUEST['test'] ) ) {echo '<!-- '.$stck_lst.' -->'; } 
echo ' </body>
</html>'.PHP_EOL;
#
# style is printed in the header 
function my_style()
     {  global $popup_css ;
        $return         = PHP_EOL.'    <style>'.PHP_EOL;
# load the genral css for pop-ups
        if (isset ($popup_css) && $popup_css <> false)  
             {  $return .= file_get_contents ($popup_css);}
             
# add pop-up specific css
        $return .= '
        td {border-bottom: 1px solid silver;}  '.PHP_EOL;       
        $return         .= '    </style>'.PHP_EOL;
        return $return;
 }
