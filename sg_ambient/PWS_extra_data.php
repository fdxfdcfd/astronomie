<?php   $scrpt_vrsn_dt  = 'PWS_extra_data.php|00|2020-04-22';  # release 2004
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
if (!isset ($stck_lst) )  {$stck_lst = '';}
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') version =>'.$scrpt_vrsn_dt.PHP_EOL;       // save list of loaded scrips;
#
$scrpt          = 'PWS_settings.php'; 
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') include_once =>'.$scrpt.PHP_EOL;
include_once $scrpt;
#
$scrpt          = 'PWS_shared.php';  
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') include_once =>'.$scrpt.PHP_EOL;
include_once $scrpt;
#
$string = file_get_contents($extra_data);
$weather['loaded_from'].= PHP_EOL.$extra_data;
$arr    = explode (PHP_EOL,$string);  # echo '<pre>'.print_r($arr,true); exit;
unset ($string);
$fromrain       = $torain = $weather['rain_units'];
$fromtemp       = $totemp = $weather['temp_units'];
#
foreach ($arr as $string)
     {  $skip   = substr($string,0,1);
        if ($skip == '#' || $skip <> '|') {continue;}
        list ($none, $key, $type, $value) = explode ('|',$string.'||||');
        $key    = trim ($key);
        $type   = trim ($type);
        $value  = trim ($value);
        if (strlen ($key) < 3 ) {continue;} // skip unknown data
        switch ($type) {  
            case 'uom':     
                $$key   = $value; 
                break;
            case 'rain':
                $weather[$key]  = convert_precip ($value,$fromrain,$torain); 	
                break;
            case 'temp':
                $weather[$key]  = convert_temp   ($value,$fromtemp,$totemp);
                break;
            case 'light':
                $weather[$key]  = (float) $value;
                if ($key == 'lightningkm')   
                     {  $weather['lightningmi']         = round ( (float) $value / 0.621371 );}
                if ($key== 'lightningtime') 
                     {  $weather['lightningtimeago']    = time() - (int) $value;}
                break;
#           case 'hum':
#           case 'text':
            default:
                $weather[$key]  = $value;  
                 
            }     
}
#echo '<pre>'.print_r($weather,true); exit;
#
