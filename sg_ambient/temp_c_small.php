<?php  $scrpt_vrsn_dt  = 'temp_c_small.php|00|2020-01-15|';  # check min-temp | release 1912
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
# ------------check if script is already running
$string = str_replace('.php','',basename(__FILE__));
if (isset ($$string) ) {echo 'This info is already displayed'; return;}
$$string = $string;
#
# -------------load weatherdata and all settings 
$scrpt          = 'PWS_livedata.php'; 
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') include_once =>'.$scrpt.PHP_EOL;
include_once $scrpt; 
# ------------------------- translation of texts
$name_l = lang ('Today');
#
$temp_colors = array(
        '#F6AAB1', '#F6A7B6', '#F6A5BB', '#F6A2C1', '#F6A0C7', '#F79ECD', '#F79BD4', '#F799DB', '#F796E2', '#F794EA', 
        '#F792F3', '#F38FF7', '#EA8DF7', '#E08AF8', '#D688F8', '#CC86F8', '#C183F8', '#B681F8', '#AA7EF8', '#9E7CF8', 
        '#9179F8', '#8477F9', '#7775F9', '#727BF9', '#7085F9', '#6D8FF9', '#6B99F9', '#68A4F9', '#66AFF9', '#64BBFA', 
        '#61C7FA', '#5FD3FA', '#5CE0FA', '#5AEEFA', '#57FAF9', '#55FAEB', '#52FADC', '#50FBCD', '#4DFBBE', '#4BFBAE', 
        '#48FB9E', '#46FB8D', '#43FB7C', '#41FB6A', '#3EFB58', '#3CFC46', '#40FC39', '#4FFC37', '#5DFC35', '#6DFC32', 
        '#7DFC30', '#8DFC2D', '#9DFC2A', '#AEFD28', '#C0FD25', '#D2FD23', '#E4FD20', '#F7FD1E', '#FDF01B', '#FDDC19', 
        '#FDC816', '#FDC816', '#FEB414', '#FEB414', '#FE9F11', '#FE9F11', '#FE890F', '#FE890F', '#FE730C', '#FE730C', 
        '#FE5D0A', '#FE5D0A', '#FE4607', '#FE4607', '#FE2F05', '#FE2F05', '#FE1802', '#FE1802', '#FF0000', '#FF0000',);
$maxTemp        = count($temp_colors) - 1;
#-----------------------------------------------
#                                      functions
#-----------------------------------------------
#                 temp_color
if (!function_exists ('temp_color') ) {
function temp_color ( $value)
     {  global $tempunit, $maxTemp, $temp_colors;
        if ($value === 'n/a' || $value === false) 
            {   return '<!-- no value '.$value.' -->'.PHP_EOL; return;}
        $tmp    = (float) $value; 
        if ($tempunit <> 'C')
             {  $tmp    = round (    5*( ($tmp -32)/9) );}
        $n      = 32 + $tmp;
        if ($n < 0) {$n=0;}
        if ($n > $maxTemp)      
             {  $color  = $temp_colors[$maxTemp];}
        else {  $color  = $temp_colors[$n];}
        return $color;}
}
#                 temp_nr
if (!function_exists ('tempnr') ) {
function tempnr ($value)
     {  global $dec_tmp;
        return number_format ($value,$dec_tmp);}
}
#
# ------------------     max temperature
$tmp    = $weather['temp_high'];
$color  = temp_color ($tmp);
$arrow  = '&uarr;';
if ($weather["temp_high_time"] === 'n/a') {$wtime = '';} else {$wtime = $weather["temp_high_time"];} 
$box_style      = 'width: 55px; height: 55px; margin: 2px; padding-top: 12px; background-color: transparent; border-width: 1px; color: black;';
#
echo '<div class="PWS_div_left PWS_round" style="'.$box_style.' float: left;  background-color: '.$color.';">'
        .'<span style="font-size: 14px; font-weight: 700;">'.$arrow.tempnr ($tmp).'&deg;</span>'
        .'<br /><small>'.$wtime.'</small>
</div>'.PHP_EOL;
# ------------------     min temperature
$tmp    = $weather['temp_low'];
$color  = temp_color ($tmp);
$arrow  = '&darr;';
if ($weather["temp_low_time"] === 'n/a') {$wtime = '';} else {$wtime = $weather["temp_low_time"];} 
echo '<div class="PWS_div_right PWS_round" style="'.$box_style.' float: right; background-color: '.$color.';">'
        .'<span style="font-size: 14px; font-weight: 700;">'.$arrow.tempnr ($tmp).'&deg;</span>'
        .'<br /><small>'.$wtime.'</small>
</div>'.PHP_EOL;
#
echo '<span class="large orange" style="display: block; padding-top: 15px;">'.$name_l.'</span>';
if (isset ($_REQUEST['test']) ) { echo '<!-- '.PHP_EOL.$stck_lst.'-->'.PHP_EOL; $stck_lst='';}

