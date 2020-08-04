<?php   $scrpt_vrsn_dt  = 'PWS_WDapi.php|00|2019-12-13|';   # release 1912
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
# WeatherDisplay API
# Load file when script is called by WeastherDisplay with fresh data 
#
$filename       = './jsondata/WDapi.txt';
#
if( isset($_GET['d']) ) 
     {  $string         = $_GET['d'];
        $search         = array('+');
        $file_contents  = trim(str_replace($search,' ',$string));  ## ???????????
        if ($file_contents == '' || strlen ($file_contents) < 50) 
             {  exit ('file-empty');}         
        file_put_contents($filename,$file_contents);
        echo "success"; }
