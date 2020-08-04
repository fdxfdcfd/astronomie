<?php  $scrpt_vrsn_dt  = 'PWS_cron_empty.php|00|2019-12-06|';   # release 1912
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
$hour                   = (int) date ('G');  // hour 0-24, needs to run early in the day only
$today_day              = (int) date ('j');  // daynr 1-31
$today_month_day        = (int) date ('nj'); // month 1-12 + daynr 1-31
#
#
if (!array_key_exists ( 'force', $_REQUEST) && $hour > 0 )
     {  die ('Probably this script is started by accident. Check the documentation!');}  
#
#  --------- clear history based on day of month
#
# ------------------------------------  settings 
#
$weatherfilefolder      = __DIR__.'/_my_settings/';
#
$hist   = unserialize (file_get_contents($weatherfilefolder.'history.txt'));   #echo '<pre>'.print_r ($hist,true);
#
$wthr_types     = array ('temp','dewp','rain','humd','baro','wind','gust','uvuv','solr');
$types_values   = array ('HghV','HghV_D','HghV_D_T','LowV','LowV_D','LowV_D_T');
$values_period  = array ('today','yday','month','year','all');
#
if (     $today_month_day == 11) {  $clear  = array ('today', 'month', 'year');} // January, 1
elseif ( $today_day == 1)        {  $clear  = array ('today', 'month');}         // first day of other  months
else                             {  $clear  = array ('today');}                  // all other days
#
foreach ($wthr_types as $type) {
        foreach ($types_values as $value) {
                foreach ($clear as $period) {
                        $hist[$type][$value][$period]   = 'n/a'; 
                } // eo foreach period
        } // eo foreach value
} // eo foreach type
echo ' succes :  hist year file cleared ';
$result = file_put_contents($weatherfilefolder.'history.rrr', serialize($hist));
if ($result > 0) 
     {  rename ($weatherfilefolder.'history.rrr', $weatherfilefolder.'history.txt');
        echo ' and saved';}
#  ----------------------- end of  clear history
#
#if  not using own data.txt for graphs  => ready
#
if ($charts_from <> "DB") { return; }  
#
# ------------------------------------  settings 
$weatherfilefolder      = __DIR__.'/chartsmydata/'; // data stored here if not using WU 
#
# ----------------------------  reset daily file
$daily_flds             = 'time, outsideTemp, barometer, raintoday, UV, windgust, windSpeed, radiation, dewpoint, rainrate, direction, date, lightning,'.PHP_EOL;      
$weatherfile            = $weatherfilefolder.'today.txt';
$rslt   = file_put_contents ($weatherfile,  $daily_flds); 
if ($rslt == false) 
     {  $echo = 'PROBLEM: Couldn not clear and initialize daily file '.$weatherfile.PHP_EOL;}
else {  $echo = 'succes : daily file reset ';}        
#
#  -----  first day of month own data .txt files
if ($today_day == 1) 
# ----------------------------  reset month file
      { $weatherfile    = $weatherfilefolder.date('Y_m').'.txt';
        $m_y_flds       = 'date,MAX_outsideTemp,MIN_outsideTemp,MAX_dewpoint,MIN_dewpoint,MAX_raintoday,MAX_windgustmph,MAX_windSpeed,MAX_radiation,MAX_barometer,MIN_barometer,SUM_lightning,MAX_UV,'.PHP_EOL;
        $rslt           = file_put_contents ($weatherfile, $m_y_flds ); 
        if ($rslt == false) { echo ' ERROR: Couldn not write data to '.$weatherfile;
} // eo clear own data month file
#
#  ---------  first of january clean year values
if ($today_month_day == 11) 
# -----------------------------  reset year file
        $weatherfile    = $weatherfilefolder.date('Y').'.txt';
        $rslt           = file_put_contents ($weatherfile, $m_y_flds ); 
        if ($rslt == false) { echo ' ERROR: Couldn not write data to '.$weatherfile;}
} // eo  own data year file
