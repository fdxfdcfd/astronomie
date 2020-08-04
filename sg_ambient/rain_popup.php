<?php $scrpt_vrsn_dt  = 'rain_popup.php|00|2019-12-01|';  # release 1912
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
$show_close_x   = $close_popup;  // set to false or true to overrde settings
$ltxt_url       = 'rain radar';
#          choose if you want to fill whole area
$stretch        = false;  // use original image. 
$stretch        = true;   // image is stretched

#     set the link to your rainradar image here
$rainradar_img  = 'https://www.meteox.com/images.aspx?jaar=-3&voor=&soort=exp&c=&amp;n=&tijdid='.time();
#$rainradar_img  = 'https://icons.wxug.com/data/weather-maps/radar/united-states/united-states-current-radar.gif?'.time();
#$rainradar_img  = 'http://icons.wunderground.com/data/640x480/ne_rd_anim.gif?'.time();

#-----------------------------------------------
#
# code for stretched to fit
$body1 =  '<body style="background: transparent url(\''.$rainradar_img.'\') no-repeat fixed center; margin: 0;  background-size: 100% 100%; ">'.PHP_EOL;
# code for  original size
$body2 =  '<body style="background: transparent url(\''.$rainradar_img.'\') no-repeat fixed center;  margin: 0;  background-size: contain;  margin: 0 auto; ">'.PHP_EOL;
# code for close X
$close_x        = '<b style="position: absolute; top: 0; left: 0; font-size: 18px; font-family: sans-serif; color: white;">&nbsp;X&nbsp;</b>';

echo '<!DOCTYPE html>
<html lang="'.substr($user_lang,0,2).'"  style="width: 100%; height: 100%;">
<head>
    <meta charset="UTF-8">
    <title>'.$ltxt_url.'</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">'
.my_style().'
</head>'.PHP_EOL;
if ($stretch == true) {echo $body1; } else {echo $body2; }
if ($show_close_x == true) {echo $close_x;}

echo '</body>
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

        $return         .= '    </style>'.PHP_EOL;
        return $return;
 }