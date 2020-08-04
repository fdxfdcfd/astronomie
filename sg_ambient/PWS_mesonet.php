<?php  $scrpt_vrsn_dt  = 'PWS_mesonet.php|00|2020-03-02|';   # first version
header('Content-type: text/plain; charset=UTF-8');
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
   header("Accept-Ranges: bytes");
   header("Content-Length: $download_size");
   header('Connection: close');
   readfile($filenameReal);
   exit;}
elseif (!isset ($_REQUEST['test'])) 
     {  ini_set('display_errors', 0); error_reporting(0);}
else {  ini_set('display_errors', 1); error_reporting(1);}  
#-----------------------------------------------
$stck_lst       = basename(__FILE__).' ('.__LINE__.') version =>'.$scrpt_vrsn_dt.PHP_EOL;    
#
# -------------------   load latest station data
$scrpt          = 'PWS_livedata.php'; 
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') include_once =>'.$scrpt.PHP_EOL;
include_once $scrpt;     #   echo '<pre>'.print_r($weather,true); exit;
#
$windlabel      = array ('N','NNE', 'NE', 'ENE', 'E', 'ESE', 'SE', 'SSE', 'S',
		         'SSW','SW', 'WSW', 'W', 'WNW', 'NW', 'NNW');
$compass        = $windlabel[ fmod((((int)$weather['wind_direction'] + 11) / 22.5),16) ]; 

$ms_string      = '';
$ms_string      .= date('H:i:s',$weather['datetime']) . ',';  #[hh]:[mm]:[ss]
$ms_string      .= date('d/m/Y',$weather['datetime']) . ',';  #[DD]/[MM]/[YYYY]
$ms_string      .= $weather['temp'] . ',';      #[th0temp-act]
$ms_string      .= $weather['heat_index'] . ','; # outsideHeatIndex
$ms_string      .= $weather['windchill'] . ','; #[wind0chill-act]
$ms_string      .= $weather['humidity'] . ',';  #[th0hum-act.0]
$ms_string      .= $weather['dewpoint'] . ',';  #[th0dew-act]
$ms_string      .= $weather['barometer'] . ','; #[thb0seapress-act]
$ms_string      .= $weather['barometer_trend'] . ','; #[thb0seapress-delta60.1:--]
$ms_string      .= $weather['wind_speed'] . ','; #[wind0wind-act],
$ms_string      .= $windlabel[ fmod((((int)$weather['wind_direction'] + 11) / 22.5),16) ] . ','; #[wind0dir-act=endir], # make text
$ms_string      .= $weather['rain_today'] . ','; #[rain0total-daysum],
$ms_string      .= ','; #current conditions,
$ms_string      .= date('H:i',$sunrs2) . ',';      #[mbsystem-sunrise:],
$ms_string      .= date('H:i',$suns2) . ',';      #[mbsystem-sunset:],
$ms_string      .= $weather['wind_speed'] . ',';      #[wind0wind-avg10:],
$ms_string      .= $weather['wind_gust_speed'] . ',';      #[wind0wind-max10:],
$ms_string      .= $weather["temp_units"].'|'
                  .$weather["wind_units"].'|'
                  .$weather["barometer_units"].'|'
                  .$weather["rain_units"];      # C|m/s|hPa|mm
 
echo $ms_string;
#echo '<pre>'.PHP_EOL.print_r($weather,true);