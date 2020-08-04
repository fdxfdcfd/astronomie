<?php   $scrpt_vrsn_dt  = 'webcam_c_block.php|00|2019-12-12|';  # release 1912
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
# ------------------------------  load  settings 
include_once 'PWS_settings.php';
#
#-------- set the link to your webcam image here
#
$webcam_img     = $mywebcamimg; // as set with easyweather
#
if (strpos ('?',$mywebcamimg) == false)
     {  $extra  = '?_'.time();}
else {  $extra  = '&_'.time();}
#                                               IMPORTANT                                        
#  $extra  =   '';  // remove the comment mark if the link already uses a timestamp
#                                               IMPORTANT                                        
$webcam_img     = $webcam_img.$extra;
#------------------------------------------------
$webcam_height  = ' height:100%; ';     // always 
$webcam_width   = ' width: 100%; ';     // this will stretch to fit
#$webcam_width   = ' ';                  // this will NOT stretch the picture, remove comment mark if you want this

echo '<a href="webcam_popup.php" data-featherlight="iframe" title="WEATHERSTATION WEBCAM">
<img src="'.$webcam_img.'" alt="weathercam" style="'.$webcam_width.$webcam_height.';" />
</a>'.PHP_EOL;
if (isset ($_REQUEST['test']) ) { echo '<!-- '.PHP_EOL.$stck_lst.'-->'.PHP_EOL; $stck_lst='';}
