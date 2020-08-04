<?php $scrpt_vrsn_dt  = 'fct_windy_popup.php|00|2019-11-30|';  # release 1912
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
# -------------------------------- load settings 
$scrpt          = 'PWS_settings.php'; 
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') include_once =>'.$scrpt.PHP_EOL;
include_once $scrpt; 
#
# -----------------------  general functions aso  
$scrpt          = 'PWS_shared.php'; 
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') include_once =>'.$scrpt.PHP_EOL;
include_once $scrpt;   
#-----------------------------------------------
#                                script settings
#-----------------------------------------------
$show_close_x   = $close_popup;  # false; // set to false or true to overrde settings
$ltxt_clsppp    = lang('Close');
$ltxt_url       = 'Wimndy forecast';
#
#  optional close X in the top-left.
if ($show_close_x == true )
     {  $closehtml = '<span style="position: absolute; top:0; left: 0; font-size: 14px;">&nbsp;X&nbsp;&nbsp;<small>'.$ltxt_clsppp.'</small></span>';}
else {  $closehtml = ''; }
$windfct_temp   = str_replace ('#',$tempunit,'%C2%B0#');
$windfct_wind   = str_replace ('/','%2F',$windunit);
#
$type   = 'temp'; 
$scripts        = array ();
$scripts['temp_c_block.php']    = 'temp';
$scripts['wind_c_block.php']    = 'wind';
$scripts['baro_c_block.php']    = 'pressure';
$scripts['rain_c_block.php']    = 'rain';
#
if (!isset ($_REQUEST['script']) )      
     {  $script   = 'temp_c_block.php'; }
else {  $script   = trim($_REQUEST['script']); }
#
if (!isset ($scripts[$script] )   )  
     {  $type   = 'temp'; }
else {  $type   = $scripts[$script]; }

$url='https://embed.windy.com/embed2.html?lat='.round($lat,2).'&lon='.round($lon,2).'&zoom=5&level=surface&overlay='.$type.'&menu=&message=&marker=&calendar=&pressure=&type=map&location=coordinates&detail=true'
        .'&detailLat='.round($lat,2).'&detailLon='.round($lon,2).'&metricWind='.$windfct_wind.'&metricTemp='.$windfct_temp.'&radarRange=-1';

echo '<!DOCTYPE html>
<html lang="'.substr($user_lang,0,2).'"  style="width: 100%; height: 100%;">
<head>
    <meta charset="UTF-8">
    <title>'.$ltxt_url.'</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">'
.my_style().'
</head>
<body style="margin: 0; background-color: black; color: white; font-family: arial, sans-serif; font-size: 16px; width: 100%;  height: 100%; overflow: hidden;">
<iframe src="'.$url.'"   allowfullscreen style="width: 100%;  height: 100%; overflow: hidden; background:url(./img/loading.gif) top center no-repeat; ">
</iframe>'.$closehtml;
if (isset ($_REQUEST['test']) ) { echo '<!-- '.PHP_EOL.$stck_lst.'-->'.PHP_EOL; $stck_lst='';}
echo '</body>
</html>';
#
# style is printed in the header 
function my_style()
     {  global $popup_css ;
        $return         = PHP_EOL.'    <style>'.PHP_EOL;
# load the genral css for pop-ups
        if (isset ($popup_css) && $popup_css <> false)  
             {  $return .= file_get_contents ($popup_css);}
# add pop-up specific css

        $return         .= '    </style>'.PHP_EOL;
        return $return;
 }