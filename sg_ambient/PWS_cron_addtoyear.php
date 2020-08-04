<?php  $scrpt_vrsn_dt  = 'PWS_cron_addtoyear.php|01|2019-12-06|';    # release 1912
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
#
$stck_lst       = basename(__FILE__).' ('.__LINE__.') version =>'.$scrpt_vrsn_dt.PHP_EOL;       // save list of loaded scrips;
#    
$scrpt          = 'PWS_settings.php';  
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') include_once =>'.$scrpt.PHP_EOL;
include_once $scrpt; 
#
# --------------------------------------------  settings 
$weatherfilefolder      = __DIR__.'/_my_settings/'; 
#
# ------------------ move today to yday  in history file
if (file_exists ($weatherfilefolder.'history.txt') )
     {  $hist = unserialize (file_get_contents($weatherfilefolder.'history.txt'));   #echo '<pre>'.print_r ($hist,true);
#        $wthr_types     = array ('temp','dewp','rain','humd','baro','wind','gust','uvuv','solr');
        $wthr_types     = array ('temp','dewp','humd','baro','wind','gust','uvuv','solr');
        $types_values   = array ('HghV','HghV_D','HghV_D_T','LowV','LowV_D','LowV_D_T');
        $values_period  = array ('today','yday','month','year','all');
        foreach ($wthr_types as $type) {
                foreach ($types_values as $value) {
                        $hist[$type][$value]['yday']    = $hist[$type][$value]['today'];       
                        $hist[$type][$value]['today']   = 'n/a'; 
                } // eo foreach value
        } // eo foreach type
        $value                          = (float) $hist['rain']['HghV']['today']; 
        $hist['rain']['HghV']['yday']   = $value; 
        $hist['rain']['HghV']['month']  = $value + (float) $hist['rain']['HghV']['month'];
        $hist['rain']['HghV']['year']   = $value + (float) $hist['rain']['HghV']['year'];
        $hist['rain']['HghV']['all']    = $value + (float) $hist['rain']['HghV']['all'];
        $hist['rain']['HghV']['today']   = 0;
        $result = file_put_contents($weatherfilefolder.'history.rrr', serialize($hist));
        if ($result > 0) {  rename ($weatherfilefolder.'history.rrr', $weatherfilefolder.'history.txt');}
        $txt_hist       = ' history updated ';}
else {  $txt_hist       = ' no history file in use ';}
#

#  -------------- check if we use our owndata fro graphs
if ($charts_from <> "DB") 
     {  echo 'succes :   '.$txt_hist.' , no internal DB in use';
        return;}
#
# ----------------------   daily monthly and yearly .csv
#
# --------------------------------------------  settings 
$weatherfilefolder      = __DIR__.'/chartsmydata/'; 
#
$weatherfileyear        = $weatherfilefolder.date('Y').'.txt';	
$weatherfilemonth       = $weatherfilefolder.date('Y_m').'.txt';	
$weatherfile            = $weatherfilefolder.'today.txt';

if (!file_exists ($weatherfile))  
     {  die ('PROBLEM: Daily  data file '.$weatherfile.'  does not exist yet. Script ends.');}
$arr    = file($weatherfile);   // load daily file
$count  = COUNT($arr);
if ($count < 2)  
     {  die ('PROBLEM: Daily  data file '.$weatherfile.'  is empty. Script ends.');}
#
# ----- init min-max fields
$MAX_outsideTemp=$MAX_dewpoint=$MAX_raintoday=$MAX_windgust=$MAX_windSpeed=$MAX_radiation=$MAX_uv = $MAX_barometer = -9000;
$MIN_outsideTemp=$MIN_dewpoint                                                                    = $MIN_barometer = +9000;
$SUM_lightning  = 0;
#
# process each data line and calculate min-max values
#
$daystart= false;
#
for ($n = 1; $n < $count; $n++)        // skip first line with header-texts.
      {  list ($time, $outsideTemp, $barometer, $raintoday, $UV, $windgust, $windSpeed, $radiation, $dewpoint, $rainrate, $direction, $date, $lightning)
                = explode (',',$arr[$n].',,,,,,,,,,, ');
        if (trim($time) == '') {continue;}
        if ($daystart == false) {
                list ($hr,$min) = explode (':',trim($time));
                if ( (int) $hr < 23 ) {$daystart= true; } else {continue;}
        }
        if ($outsideTemp> $MAX_outsideTemp)     {$MAX_outsideTemp = $outsideTemp; }
        if ($outsideTemp< $MIN_outsideTemp)     {$MIN_outsideTemp = $outsideTemp; }

        if ($dewpoint   > $MAX_dewpoint)        {$MAX_dewpoint    = $dewpoint; }
        if ($dewpoint   < $MIN_dewpoint)        {$MIN_dewpoint    = $dewpoint; }

        if ($raintoday  > $MAX_raintoday)       {$MAX_raintoday   = $raintoday;  }

        if ($windgust   > $MAX_windgust)        {$MAX_windgust    = $windgust;  }

        if ($windSpeed  > $MAX_windSpeed)       {$MAX_windSpeed   = $windSpeed;  }

        if ($radiation  > $MAX_radiation)       {$MAX_radiation   = $radiation;  }

        if ($UV         > $MAX_uv)              {$MAX_uv          = $UV;         }

        if ($barometer  > $MAX_barometer)       {$MAX_barometer   = $barometer;  }
        if ($barometer  < $MIN_barometer)       {$MIN_barometer   = $barometer;  }

        $SUM_lightning  = $SUM_lightning + (int) $lightning;
        }

# ------   construct headers for files
$daily_flds     = 'time, outsideTemp, barometer, raintoday, UV, windgust, windSpeed, radiation, dewpoint, rainrate, direction, date, lightning,'.PHP_EOL;
$m_y_flds       = 'date,MAX_outsideTemp,MIN_outsideTemp,MAX_dewpoint,MIN_dewpoint,MAX_raintoday,MAX_windgustmph,MAX_windSpeed,MAX_radiation,MAX_barometer,MIN_barometer,SUM_lightning,MAX_UV,'.PHP_EOL;
#    
$year_string    = $month_string = '';
if (!file_exists ($weatherfileyear))  { $year_string    = $m_y_flds;}
if (!file_exists ($weatherfilemonth)) { $month_string   = $m_y_flds;}
#
# ------   construct data line of todays min-max values
#
$data_string    = date ('Y-m-d H:i:s').','.$MAX_outsideTemp.','.$MIN_outsideTemp.','.$MAX_dewpoint.','.$MIN_dewpoint.','.$MAX_raintoday.','.$MAX_windgust.','.$MAX_windSpeed.','.$MAX_radiation.','.$MAX_barometer.','.$MIN_barometer.','.$SUM_lightning.','.$MAX_uv.','.PHP_EOL;#

# ----------------------  add max-min data to month & year files
# -------------------------  if files not exist they are created
$rslt   = file_put_contents ($weatherfilemonth,  $month_string.$data_string, FILE_APPEND); 
if ($rslt == false) { die ('ERROR: Couldn not write data to '.$weatherfilemonth);}
#
$rslt   = file_put_contents ($weatherfileyear,  $year_string.$data_string, FILE_APPEND); 
if ($rslt == false) { die ('ERROR: Couldn not write data to '.$weatherfileyear);}
#
# ------------------------------------  reset daily file
$rslt   = file_put_contents ($weatherfile,  $daily_flds); 
if ($rslt == false) { die ('PROBLEM: Couldn not clear and initialize daily file '.$weatherfile);}
#
echo 'succes :  totals of '.($count - 1).' daily records added to month/year files, daily file reset';
