<?php $scrpt_vrsn_dt  = 'AQ_shared.php|01|2019-03-23|';  # release 2004
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
# -------------------save list of loaded scrips;
if (!isset ($stck_lst) ) {$stck_lst = '';}
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') version =>'.$scrpt_vrsn_dt.PHP_EOL;       
# 
AQ_check_missing_functions (); 
#
$tr_updt        = lang('Updated');
$tr_station     = lang('Station');
$AQ_levels      = array (50,100,150,200,300,99999999);
$n = 0;  // lowest
$aq_class[$n]   = 'green';
$aq_color[$n]   = '#9aba2f';  
$aq_icon[$n]    = 'aq_green.svg';
$aq_text[$n]    = 'GoodAQ';
$n = 1;
$aq_class[$n]   = 'yellow';
$aq_color[$n]   = '#e8c400'; #'#ecb454';  
$aq_icon[$n]    = 'aq_yellow.svg';
$aq_text[$n]    = 'ModerateAQ';     
$n = 2;
$aq_class[$n]   = 'orange';
$aq_color[$n]   = '#ed7816';  
$aq_icon[$n]    = 'aq_orange.svg';
$aq_text[$n]    = 'UnhealthyFSAQ';     
$n = 3;
$aq_class[$n]   = 'red';
$aq_color[$n]   = '#d65a4a';  
$aq_icon[$n]    = 'aq_red.svg';
$aq_text[$n]    = 'UnhealthyAQ';     
$n = 4;
$aq_class[$n]   = 'purple';
$aq_color[$n]   = '#9f7cac';  
$aq_icon[$n]    = 'aq_purple.svg';
$aq_text[$n]    = 'VeryUnhealthyAQ';     
$n = 5;
$aq_class[$n]   = 'maroon';
$aq_color[$n]   = '#a35757';  
$aq_icon[$n]    = 'aq_maroon.svg';
$aq_text[$n]    = 'HazordousAQ';     

$t_color        =  '#000000';  // if we want text-color adapted on background color
#
function AQ_check_missing_functions () {
global $AQ_levels, $aq_icon, $aq_class, $b_color, $aq_color, $aq_text;
        if (!function_exists ('pm25_to_aqi') ) {
                function pm25_to_aqi($pm25){
                        if ($pm25 > 500.5) {
                          $aqi = 500;
                        } else if ($pm25 > 350.5 && $pm25 <= 500.5 ) {
                          $aqi = aq_map($pm25, 350.5, 500.5, 400, 500);
                        } else if ($pm25 > 250.5 && $pm25 <= 350.5 ) {
                          $aqi = aq_map($pm25, 250.5, 350.5, 300, 400);
                        } else if ($pm25 > 150.5 && $pm25 <= 250.5 ) {
                          $aqi = aq_map($pm25, 150.5, 250.5, 200, 300);
                        } else if ($pm25 > 55.5 && $pm25 <= 150.5 ) {
                          $aqi = aq_map($pm25, 55.5, 150.5, 150, 200);
                        } else if ($pm25 > 35.5 && $pm25 <= 55.5 ) {
                          $aqi = aq_map($pm25, 35.5, 55.5, 100, 150);
                        } else if ($pm25 > 12 && $pm25 <= 35.5 ) {
                          $aqi = aq_map($pm25, 12, 35.5, 50, 100);
                        } else if ($pm25 > 0 && $pm25 <= 12 ) {
                          $aqi = aq_map($pm25, 0, 12, 0, 50);
                        }
                        return $aqi;
                }
        } // eo exist pm25_to_aqi
        #
        if (!function_exists ('aq_map') ) {
                function aq_map($value, $fromLow, $fromHigh, $toLow, $toHigh){
                    $fromRange = $fromHigh - $fromLow;
                    $toRange = $toHigh - $toLow;
                    $scaleFactor = $toRange / $fromRange;
                    // Re-zero the value within the from range
                    $tmpValue = $value - $fromLow;
                    // Rescale the value to the to range
                    $tmpValue *= $scaleFactor;
                    // Re-zero back to the to range
                    return $tmpValue + $toLow;}
        } // eo exist aq_map
        #
         if (!function_exists ('aq_set_clrs') ) {
                function aq_set_clrs ($aqi) {
                    global $AQ_levels, $aq_icon, $aq_class, $b_color, $aq_color, $aq_text;
                    foreach ($AQ_levels as $n => $value) {
                        if ($aqi > $value) { continue;}
                        $arr    = array();
                        $arr['icon']    = $aq_icon[$n];
                        $arr['class']   = 'dottedcircle'.$aq_class[$n];
                        $arr['color']   = $b_color = $aq_color[$n];
                        $arr['text']    = $aq_text[$n];
                        break;}
                    return $arr;
                }
        } // eo exist aq_set_clrs
           
} // eof AQ_check_missing_functions
