<?php  $scrpt_vrsn_dt  = 'PWS_cron_stationcron.php|00|2020-04-23|';   #  n/a values | release 2004 
#
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

$stck_lst       = basename(__FILE__).' ('.__LINE__.') version =>'.$scrpt_vrsn_dt.PHP_EOL;       // save list of loaded scrips;
#
# --------------------   load all external files
$weatherfilefolder      = __DIR__.'/chartsmydata/'; 
$weatherfile            = $weatherfilefolder.'today.txt';                
$cron                   = true; #      with shorter refresh time
$no_data                = 2355;  // HH mm after wich no day data should be processed in history until 10 minutes after midnight
# --------------------   load all external files
$scrpt          = 'PWS_load_files.php'; 
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') include_once =>'.$scrpt.PHP_EOL;
include_once $scrpt;
echo 'success files loaded ';
#
# -------------------   load latest station data
$scrpt          = 'PWS_livedata.php'; 
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') include_once =>'.$scrpt.PHP_EOL;
include_once $scrpt;     #   echo '<pre>'.print_r($weather,true); exit;
#
if ($charts_from == "DB") { //  generate file for today  x times / hour when using our webserver for historical data
# all current values in original units to a string
#        $string       = $sql_updated.','.$sql_temp.','.$sql_barometer.','.$sql_raintoday.','.$sql_uv.','.$sql_windgust.','
#                        .$sql_windspeed.','.$sql_solar.','.$sql_dewpoint.','.$sql_rainrate.','.$sql_direction.','.$sql_date.','.$sql_lightning.','.PHP_EOL;
#                       
        $string       = $sql_updated.','.$weather['temp'].','.$weather['barometer'].','.$weather['rain_today'].','.$weather['uv'].','.$weather['wind_gust_speed'].','
                        .$weather['wind_speed'].','.$weather['solar'].','.$weather['dewpoint'].','.$weather['rain_rate'].','.$weather['wind_direction'].','.$sql_date.','.$sql_lightning.','.PHP_EOL;                       
# check for empty data
        if ( 0 == (float) $sql_temp + (float) $sql_barometer + (float) $sql_temp + (float) $sql_raintoday )
             {  echo ' ! problem empty data skipped!';
                return;        }
# no file (for instance after testing), also include  header line with field descriptions
        if (!file_exists ($weatherfile) )       # first time
             {  $daily_flds     = 'time, outsideTemp, barometer, raintoday, UV, windgust, windSpeed, radiation, dewpoint, rainrate, direction, date, lightning,'.PHP_EOL;
                $string         = $daily_flds. $string;
                echo ' + day file should be created';}
#  add 1 data line
        $rslt   = file_put_contents ($weatherfile,  $string, FILE_APPEND); 
        if ($rslt == false) 
             {  $stck_lst  .= basename(__FILE__).' ('.__LINE__.') Data could not be saved to '.$weatherfile.PHP_EOL;
                echo ' ! problems with saving data !';        }
        else {  $stck_lst  .= basename(__FILE__).' ('.__LINE__.') Data saved to '.$weatherfile.PHP_EOL;
                echo ' + data appended to day file ';}
#
} // eo save to own data to file  (= option  DB)
#
# -----  check if time is in "not allowed range" 
# ------------  normally between 23:55 and 00:05 
# ------ as there are often delayed data records
#
if (date ('Gi') >= $no_data || date ('Gi') <= 5)  {  echo ' no history  ';  return;}
#
# ------------------------  update history table 
$weatherfilefolder      =  __DIR__.'/_my_settings/'; 
#
$change = false;   $changed_items = '';       
$loop   = array ('all','year','month','today');
update_hist_LH ('temp', $weather['temp']); 
update_hist_LH ('temp', $weather['temp_high']); 
update_hist_LH ('temp', $weather['temp_low']);  
update_hist_LH ('dewp', $weather['dewpoint']); 
update_hist_LH ('dewp', $weather['dewpoint_low']);   
#update_hist_LH ('rain', $weather['rain_today']);  
update_hist_LH ('humd', $weather['humidity']);
update_hist_LH ('baro', $weather['barometer']);
update_hist_LH ('baro', $weather['barometer_max']);   
update_hist_LH ('baro', $weather['barometer_min']); 
update_hist_LH ('wind', $weather['wind_speed']);  
update_hist_LH ('wind', $weather['wind_speed_max']);
update_hist_LH ('gust', $weather['wind_gust_speed']);    
update_hist_LH ('gust', $weather['wind_gust_speed_max']);  
update_hist_LH ('uvuv', $weather['uv']);  
update_hist_LH ('uvuv', $weather['uv_max']);
update_hist_LH ('solr', $weather['solar']);  
update_hist_LH ('solr', $weather['solar_max']);  
#
$loop   = array ('today');
update_hist_LH ('rain', $weather['rain_today']); 
#
if ($change == true)  {  // check if there were any updates to the history 
        echo ' + history recalculated ';
        $result = file_put_contents($weatherfilefolder.'history.rnm', serialize($hist));
        if ($result > 0) 
             {  rename ($weatherfilefolder.'history.rnm', $weatherfilefolder.'history.txt');
                echo ' + history saved '; }
        else {  echo 'error writing temp file chartsmydata/history.rnm'; }
      #  if (isset ($_REQUEST['test'])) {
                echo PHP_EOL.$changed_items. PHP_EOL.'$livedata='.$livedata.PHP_EOL.'$hist_file='.$hist_file;
       # }
}
else {  echo ' + history was already valid '.PHP_EOL.'$livedata='.$livedata.PHP_EOL.'$hist_file='.$hist_file; #echo '<pre>'.print_r($hist,true).'</pre>';
        }
#
#-----------------------------------------------
#             update_hist_LH  function to update 
#             history table with low high values
#-----------------------------------------------
function update_hist_LH ($type, $value) {  //
        global $change, $loop, $hist, $recordDate,  $sql_updated, $changed_items;
        foreach ($loop as $period) {
                if (!isset ($hist[$type]['HghV'][$period] ) )
                     {  $check  = -2000;}
                elseif ( (string)   $hist[$type]['HghV'][$period] === 'n/a' )
                     {  $check  = -2000; } 
                else {  $check  =  (float) $hist[$type]['HghV'][$period];}
#  if ($type == 'rain') {echo '$check='.$check.' $value='.$value; exit;}
                if ( ($check < $value)  && (string) $value <> '' && (string) $value <> 'n/a') 
                     {  $change = true; $changed_items .= $type.'|HghV|'.$period.'|'.$value.'|'.$check.'|'.PHP_EOL;
                        $hist[$type]['HghV']    [$period] = $value; 
                        $hist[$type]['HghV_D']  [$period] = $recordDate; 
                        $hist[$type]['HghV_D_T'][$period] = $sql_updated; }
# if ($type == 'rain') {echo '$check='.$check.' $value='.$value.' $changed_items='.$changed_items; exit;}
                if (!isset ($hist[$type]['LowV'][$period] ))
                     {  $check  =  2000;}
                elseif ( (string)   $hist[$type]['LowV'][$period] === 'n/a' )
                     {  $check  =  2000; } 
                else {  $check  =  (float) $hist[$type]['LowV'][$period];}
                if ($value <  $check && (string) $value <> '' && (string) $value <> 'n/a') 
                     {  $change = true; $changed_items .= $type.'|LowV|'.$period.'|'.$value.'|'.$check.'|'.PHP_EOL;
                        $hist[$type]['LowV']    [$period] = $value; 
                        $hist[$type]['LowV_D']  [$period] = $recordDate; 
                        $hist[$type]['LowV_D_T'][$period] = $sql_updated; }
        } // eo foreach
} // eof update_hist_LH
