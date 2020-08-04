<?php   $scrpt_vrsn_dt  = 'PWS_easyweathersetup.php|00|2020-05-05|';  # DWLv2api | ! remove "-error | updated defaults | release 2004
# 
#-----------------------------------------------
# CREDIT - DO NOT REMOVE WITHOUT PERMISSION
# Author:       : Wim van der Kuil
# Documentation 
#   and support : https://pwsdashboard.com/
#-----------------------------------------------
#  display source of script if requested so
#-----------------------------------------------
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) 
     {  $filenameReal = __FILE__;			
        $download_size = filesize($filenameReal);
        header('Pragma: public');
        header('Cache-Control: private');
        header('Cache-Control: no-cache, must-revalidate');
        header("Content-type: text/plain");
        header("Accept-Ranges: bytes");
        header("Content-Length: $download_size");
        header('Connection: close');
        readfile($filenameReal);
        exit;}
elseif (!isset ($_REQUEST['test'])) {  ini_set('display_errors', 0); error_reporting(0);}
#-----------------------------------------------        
$pageName	= 'PWS_easyweathersetup.php';
$pageVersion	= '0.00 2020-04-16'; 
$pageUpdated	= '07 version '; 
#--------------------------------------- History
# 0.00 2020-04-16 release version
# ---------------------------------- Houskeeping
$pageLoaded 	= basename(__FILE__); 
$string         = $pageVersion.' | '.$pageUpdated;
if ( $pageName <>  $pageLoaded)  {  $string .= ' => check script name: '.$pageName; } 
#
$scrpt_vrsn_dt  = $pageLoaded.'|'.$string; 
# 
# ----------------------------- SCRIPT  SETTINGS
$my             =  basename(__FILE__);
$sttngs_fl      = './_my_settings/settings.php';
$stck_lst       = '';
#-----------------------------------------------        
# load current settings file
#-----------------------------------------------
if (is_file($sttngs_fl) )
     {  include $sttngs_fl;}  
else {  $pass = $password = '12345';}      

# ----------------------------------------------
# Enclosing html
#
echo '<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8"/>
<title>Easyweather setup</title>
<link rel="stylesheet" type="text/css" href="css/configure_css.css" />
</head>
<body style="width: 1020px; margin: 0 auto;">'.PHP_EOL; 
# ----------------------------------------------
if (isset($_POST['submit_pwd']))
     {  if (isset($_POST['passwd']) ) {$pass = $_POST['passwd']; } else {$pass = '';} 
        if ($pass != $password) 
             {  showForm('<b style="color: red;">EASY SETUP needs a valid password</b>');   
                exit;}

        } 
elseif (!isset($_POST['submit']) )
     {  showForm("PWS_Dashboard template setup version PWSD_2004"); 
        exit; } 
# ----------------------------------------------
#
# allowed default languages
$arr    = file ('_my_settings/languages.txt');  // this site language settings
$easystr= '';
$extra  = '';
foreach ($arr as $string) 
     {  if (substr($string,0,1) == '#') {continue;} // skip comments
        list ($lng_key, $lng_flag, $lng_locale, $lng_file, $lng_txt, $country_flag) = explode ('|',$string.'|||||||||');
        if (trim($lng_key) <> '')    {$lng_key    = trim($lng_key);}    else {continue;}
        if (trim($lng_flag) <> '')   {$lng_flag   = trim($lng_flag);}   else {continue;}
        if (trim($lng_locale) <> '') {$lng_locale = trim($lng_locale);} else {continue;}
        if (trim($lng_file) <> '')   {$lng_file   = trim($lng_file);}   else {continue;}
        if (trim($lng_txt) <> '')    {$lng_txt     = trim($lng_txt);}   else {continue;}
        if (trim($country_flag) <> '')    
             {  $country_flag   = trim($country_flag);}
        else {  $country_flag   = $lng_flag;}
        $lngsArr[$lng_key]=array ('flag' => $lng_flag, 'locale' => $lng_locale, 'file' => $lng_file, 'txt' => $lng_txt, 'ctrflg' => $country_flag);
        $easystr .= $extra .$lng_key.'#'.$lng_txt;
        $extra  = '!';
        #break;
}    #echo '<pre>'.print_r ($lngsArr, true); exit;
#
# allowed livedataFormats    # !';\n$arr_vals[]      = '
$easystr2       = '';
$arr_vals       = array();
$arr_vals[]     = 'ecoLcl#Ecowitt local upload!';
$arr_vals[]     = 'AWapi#API - AmbientWeather.net!';
$arr_vals[]     = 'WDapi#API - WeatherDisplay!';
$arr_vals[]     = 'wf#API - WeatherFlow!';
$arr_vals[]     = 'DWL_v2api#API - WeatherLink Cloud API v2!';
$arr_vals[]     = 'DWL#API - WeatherLink Cloud!';
$arr_vals[]     = 'wu#API - WeatherUnderground !';
$arr_vals[]     = 'wd#Clientraw - WeatherDisplay!';
$arr_vals[]     = 'meteohub#Clientraw - Meteohub!';
$arr_vals[]     = 'wswin#Clientraw - WSWIN!';
$arr_vals[]     = 'cumulus#realtime.txt - Cumulus!';
$arr_vals[]     = 'MB_rt#realtime.txt - Meteobridge!';
$arr_vals[]     = 'weathercat#realtime.txt - WeatherCat!';
$arr_vals[]     = 'weatherlink#realtime.txt - WeatherLink program!';
$arr_vals[]     = 'weewx#realtime.txt - Weewx!';
$arr_vals[]     = 'wifilogger#realtime.txt - WifiLogger';
foreach ($arr_vals as $str) {$easystr2.=$str;}
#
# allowed small block;
$easystr3       = '';
$arr_vals       = array();
$arr_vals[]     = 'wind_c_small.php#Wind-gust max today!';
$arr_vals[]     = 'temp_c_small.php#Maximum/Mininimum temperatures!';
$arr_vals[]     = 'rain_c_small.php#Rainfall year - month!';
$arr_vals[]     = 'earthquake_c_small.php#earthquakes!';
$arr_vals[]     = 'lightning_station_small.php#Your station lightning detector!';
$arr_vals[]     = 'lightning_boltek_small.php#Nexstorm lightning!';
$arr_vals[]     = 'lightning_wf_small.php#Weatherflow lightning!';
$arr_vals[]     = 'extra_tmp_c_small.php#extra temp-hum!';
$arr_vals[]     = 'soil_tmp_mst_small.php#Soil information';
foreach ($arr_vals as $str) {$easystr3.=$str;}
#
# supported forecasts
$easystr4       = '';
$arr_vals       = array();
$arr_vals[]     = 'fct_yrno_block.php#Forecast by yr.no!';
$arr_vals[]     = 'fct_aeris_block.php#Forecast by Aeris!';
$arr_vals[]     = 'fct_wu_block.php#WeatherUnderground (WC) forecast!';
$arr_vals[]     = 'fct_darksky_block.php#DarkSky forecast!';
$arr_vals[]     = 'fct_wxsim_block.php#WXSIM forecast!';
$arr_vals[]     = 'fct_ec_block.php#Environment Canada forecast';
foreach ($arr_vals as $str) {$easystr4.=$str;}
#
# supported current conditions
$easystr5       = '';
$arr_vals       = array();
$arr_vals[]      = 'ccn_aeris_block.php#Use Aeris for current weather!';
$arr_vals[]      = 'ccn_metar_block.php#Use METAR airport weather for current conditions (needs API key).!';
$arr_vals[]      = 'ccn_darksky_block.php#Use Darksky for current conditions (needs API key).!';
$arr_vals[]      = 'ccn_cltraw_block.php#When you selected clientraw for live data, you can use it here also.!';
$arr_vals[]      = 'ccn_ec_block.php#Weatherstations in Canada can use Environment Canada also.';
foreach ($arr_vals as $str) {$easystr5.=$str;}
#
# supported extra blocks
$easystr6       = '';
$arr_vals       = array();
$arr_vals[]      = 'extra_temp_block.php#Your extra temphum sensors!';
$arr_vals[]      = 'indoor_c_block.php#Indoor Temp/Hum.!';
$arr_vals[]      = 'webcam_c_block.php#Webcam: add url of your webcam to webcam_c_block.php!';
$arr_vals[]      = 'earthquake_c_block.php#Earthquake information!';
$arr_vals[]      = 'uvsolarlux_c_block.php#Solar, uv + lux from your station, Weatherflow or Darksky!';
$arr_vals[]      = 'moon_c_block.php#Moonphase information.!';
$arr_vals[]      = 'AQ_luftdaten_c_block.php#Your Luftdaten AQ sensor information.!';
$arr_vals[]      = 'AQ_purpleair_c_block.php#Your Purpleair AQ sensor information.!';
$arr_vals[]      = 'AQ_gov_c_block.php#Nearby official AQ sensor station!';
$arr_vals[]      = 'AQ_station_block.php#Your station AQ sensor';
foreach ($arr_vals as $str) {$easystr6.=$str;}

configure_now_strings();
#
$arr	        = explode  ("\n", $settings_txt);       #  echo '<pre>'.print_r($arr,true).'</pre>'.PHP_EOL; exit;
$form   = array();
foreach ($arr as $line) 
     {  if (substr($line,0,1)  <> '|') {continue;}
        $items	= explode ('|', $line);	# echo '<pre>';  print_r($items); exit;	
        if (!is_numeric ($items[1]) ) 
             {  echo '<!-- '.$my.' ('.__LINE__.'): settings text line '.$n.' skipped - nr not numeric -->'.PHP_EOL;	
                continue;}
        $nr		= $items[1];
        $text		= trim($items[2]);
        $set_wp		= $text;
        $set_region	= trim($items[3]);
        if (!isset ($items[4]) ) {      # echo $start_echo.'Error in line '.$line.$end_echo; continue;
                echo   '<!-- '.$my.' ('.__LINE__.'): Error in line '.$line.' -->'.PHP_EOL;
        }
        $set_key	= trim($items[4]);
        $set_type	= trim($items[5]);
        $set_old	= trim($items[6]);        
        $set_new        = '';
        if (isset ($$set_key) ) 
             {  $value  = $$set_key;
                if      ($value === true)  { $value = 'true';}
                elseif  ($value === false) { $value = 'false';}
                else                       { $value = $value;}
                $set_new = $set_old = $value;}
        if (!isset ($items[7]) ) 
             {  $set_values 	= ''; }
        else {	$set_values	= trim($items[7]);}
        $form [] = array ('wp' => $set_wp,'region' => $set_region,'setting' => $set_key,'type' => $set_type, 'new' => $set_new,'old' => $set_old ,'values' => $set_values);		
} // eo for each line
#echo '<pre>'.print_r($form,true); exit;
# ----------------------------------------------
# if entry-form was filled in, save all entered values to a file
#
if (isset ($_POST['submit']) )  
    {   $string = '<?php   $scrpt_vrsn_dt  = "./_my_settings/settings.php|00|'.gmdate ('Y-m-d').'| ";
$stck_lst      .= basename(__FILE__)." (".__LINE__.") version =>".$scrpt_vrsn_dt.PHP_EOL;       // save list of loaded scrips;
# '.PHP_EOL;
        $lookup = array();
        $values = array();
        $sttng_lngth    = 22;
        $spaces = str_repeat(' ',$sttng_lngth);
        foreach ($form as $arr)
            {   if ($arr['type'] <> '#') 
                     {  $key            = $arr['setting'];
                        $lookup[$key]   = $arr['type'];}}
        $arr    = $_POST['settings']; # print_r($arr) ;
        foreach ($arr as $key => $value)
             {  $name   = $key;
                if     ($value == 'true')  {$value   = 'true';}
                elseif ($value == 'false') {$value   = 'false';}
                else                       {$value   = '"'.$value.'"';}
                $extra  = '';
                $type   = $lookup[$key];
                if ($type ==  'numberDecimal') {$extra = '(float) ';}
                elseif ($type ==  'noDecimal') {$extra = '(int) ';}
                $key = substr($spaces.'$'.$key,-$sttng_lngth);
                $string.= $key.' = '.$extra.$value.';'.PHP_EOL;
                $values[$name]  = $value;
                } // eo foreach

        $metarapikey    = str_replace('"','',$values['metarapikey']);  # 2020-04-16
        if ($metarapikey <> 'ADD YOUR API KEY' && trim($metarapikey) <> '')
             {  $string.= '          $metar_popup = true; // used for popup'.PHP_EOL;}
        else {  $string.= '          $metar_popup = false; // used for popup'.PHP_EOL;} 

        $aeris_access_id= str_replace('"','',$values['aeris_access_id']);  # 2020-04-16
        if ($aeris_access_id <> 'ADD YOUR API KEY' && trim($aeris_access_id) <> '')
             {  $string.= '          $aeris_popup = true;  // used for popup'.PHP_EOL;
                $string.= '          $metar_popup = false; // used for popup'.PHP_EOL;}
        else {  $string.= '          $aeris_popup = false; // used for popup'.PHP_EOL;} 
        
        $defaultlanguage= str_replace('"','',$values['defaultlanguage']);
        $string        .= '         $country_flag = "'.$lngsArr[$defaultlanguage]["ctrflg"].'";'.PHP_EOL;

        $wu_csv_unit    = str_replace('"','',$values['unit']);
        $string        .= '          $wu_csv_unit = "'.$wu_csv_unit.'";'.PHP_EOL;

        if (array_key_exists('HTTP_REFERER',$_SERVER))
             {  $from   = array ('PWS_easyweathersetup.php', 'easyweathersetup.php');
                $this_server    =  str_replace ($from,'',$_SERVER['HTTP_REFERER']);
                $string.= '          $this_server = "'.$this_server.'";   # used for WU MITM script'.PHP_EOL;}
# save new settings to file
        $fp = fopen($sttngs_fl, "w") or die("Unable to open ./_my_settings/settings.php file check file permissions !");
        fwrite($fp, $string);
        fclose($fp);
        showForm("PWS_Dashboard template setup version PWSD_2004"); 
        exit();         
        } // eo entry form received
echo '<div id="config__manager">
<form action= "'.$_SERVER['PHP_SELF'].'" method="post">
<table class="">
<tr ><td colspan="2" style="text-align: center;"><b>Welcome by EasyweatherSetup</b>
<br /><br />You can check and modify your settings here. But do not forget to save them at the bottom of this page.<br />&nbsp; </td></tr>';
#
#echo '<pre>'.print_r($form,true).'</pre>'.PHP_EOL; exit;

foreach ($form as $key => $arr) # generate the input form
     {  tr_setting($key, $arr);}
echo '<tr>
<td>&nbsp;</td><td class="value" style="background-color: green;"><div class="input">
  <input type="submit" style="width: 200px; margin-right: 100px;" name="submit" class="button" value="'.langtransstr('Save configuration').'">
</div></td></tr>
</table>
</form>

</div><br />
</div>
</body>
</html>';  
return;
# ----------------------------------------------
function langtransstr($string)  # to translate texts
     {  global $LANGLOOKUP;
        if (isset ($LANGLOOKUP[$string]))
             {  return $LANGLOOKUP[$string]; }
        else {  return $string;}} // eof langtransstr
# 
function showForm ($msg="LOGIN")# to display password form
     {  global $password;
        #if (isset ($_POST)) {echo '<!-- '.print_r($_POST,true) .$password.' -->';}
        echo  '<div style="text-align: center;"><br />'. $msg.'<br /><br />
<a href="./index.php">Back to the main page</a><br /><br />
<div style = " width:600px; margin:0 auto; color:rgba(24, 25, 27, 1.000); border:solid 1px grey; padding:10px; border-radius:4px;" >
<form action = "'.$_SERVER['PHP_SELF'].'" method="post" name="pwd" > 
'.langtransstr('Enter your password for the "Setup Screen" below').'<br /><br />
<center>
    <input name = "passwd" type= "password"  class = "input" />  
    <input type = "submit" name= "submit_pwd" value="Login" class="btn" /> 
</center>         
</form>
<br />
<b>Info:</b> Your current PHP version is  : ' . phpversion(). ' <br>
'.langtransstr('PHP 7+ is advised for this template but it will run also with PHP 5.6.3 or higher').'
</div>
</div>
</body>
</html>';
}

function configure_now_strings() # load forl all settings the description, allowed value a.s.o.
     {  global $settings_txt, $LANGLOOKUP, $LINKLOOKUP,$easystr ,$easystr2  ,$easystr3  ,$easystr4  ,$easystr5 ,$easystr6 ;
        $settings_txt = "
#---------------------------------------------------------------------------------------|	
#nr|xx	|xxxxx	|key =>  SITE	|type 		|old/default	|values allowed		|
#---------------------------------------------------------------------------------------|
|00|--	|--	|--		|#		|conf-password_hd|                      |
|00|--	|--	|password	|htmltext	| 		|                       |
|00|--	|--	|show_settings	|select	        |true           |true#Yes, we do.!false#All settings are OK, do not show that menu entry|

|02|--	|--	|--		|#		|conf-language_hd|                      |
|02|--	|--	|defaultlanguage|select	        |en	        |".$easystr."|
|02|--	|--	|lang_select	|select	        |true	        |true#conf-lang_select_yes!false#Do not allow this|
|03|--	|--	|--		|#		|conf-livedata_hd|                      |
|03|--	|--	|livedataFormat |select		|wd             |".$easystr2."|
|03|--	|--	|livedata	|htmltext	|./demodata/clientraw.txt|                |
|03|--	|--	|liveYMD	|select	        |c b a          |a b c#Year  Month  Day!c b a#Day Month Year!c a b#Month Day Year|
|03|--	|--	|--		|#		|conf-extra_sensors_hd|                      |
|03|--	|--	|have_extra	|select	        |false          |true#Yes, we have extra sensors and upload the data.!false#Do not use this|
|03|--	|--	|extra_data	|htmltext	|use demodata|                |

|03|--	|--	|--		|#		|conf-history_hd|                      |
|03|--	|--	|charts_from	|select	        |'WU'      	|WU#We will use our WeatherUnderground data for charts!DB#We will store the data on our webserver and use those for the charts|

|04|--	|--	|--		|#		|conf-descriptions_hd|                      |
|04|--	|--	|stationName    |htmltext	|xyz-weatherstation|                    |
|04|--	|--	|stationlocation|htmltext	|A city in Belgium|                       |
|04|--	|--	|--		|#		|conf-contact_hd|                      |
|04|--	|--	|contact_show   |select	        |false          |true#Yes, we do.!false#Do not use this|
|04|--	|--	|email          |htmltext	|someone@dot.com|                       |
|04|--	|--	|twitterUser    |select	        |false          |true#Yes, we do.!false#Do not use this|
|04|--	|--	|twitter        |htmltext	|pwsweather     |       |

|05|--	|--	|--		|#		|conf-location_hd|                      |
|05|--	|--	|TZ             |htmltext	|Europe/Brussels|                       |
|05|--	|--	|noDST          |select	        |DST            |noDST#Officially they use Daylight Saving, but we personally do not want that.!DST#I do not need this, use always official time zone standards|
|05|--	|--	|lat            |numberDecimal	|50.8500        |                       |
|05|--	|--	|lon            |numberDecimal	|4.3400         |                       |
|05|--	|--	|icao1          |allcap	        |EBBR           |4                      |
|05|--	|--	|airport1       |htmltext	|AirportName    |                       |
|05|--	|--	|airport1dist   |noDecimal      |14             |                       |
|05|--	|--	|weatheralarm   |select	        |europe         |none#Do not use this!europe#Europe uses meteoalarm.eu!curly#USA use NWS!canada#Use Environment Canada!au#Use bom.gov.au|
|05|--	|--	|alarm_area     |htmltext	|BE004          |                       |
|05|--	|--	|province       |htmltext	|ON             |    |
#05|--	|--	|region         |select         |other          |UK#UK!USA#USA!nordic#Scandinavia!other#other|                      |

|07|--	|--	|--		|#		|conf-hardware_hd|                      |
|07|--	|--	|hardware	|htmltext	|Davis Vantage Pro|                     |
|07|--	|--	|manufacturer   |select	        |other          |davis#Davis!weatherflow#Weatherflow!fineoffset#FineOffset!other#Other|

|11|--	|--	|--		|#		|conf-units_used_hd|       |
|11|--	|--	|unit           |select         |metric         |metric#metric: C_km/h_hPa_mm_km!us#imperial: F_mph_inHg_in_mi!uk#uk: C_mph_hPa_mm_mi!scandinavia#nordic: C_m/s_hPa_mm_m/s |
|11|--	|--	|dec_tmp        |select         |1              |0#No decimals for temperature!1#1 decimal for temperature values |
|11|--	|--	|dec_wnd        |select         |1              |0#No decimals for wind values!1#1 decimal for wind values|
|11|--	|--	|dec_rain       |select         |1              |0#No decimal for rain!1#1 decimal for rain in mm, 2 for rain in inches|
|11|--	|--	|dec_baro       |select         |1              |0#No decimal for barometerin hPa, 1 decimal for barometer in  inHg!1#1 decimal for barometer in hPa, 2 for barometer in inHg|
|11|--	|--	|rainrate       |select         |/h             |/h#per hour!/m#per minute |
#11|--	|--	|pressureunit   |select         |hPa            |hPa#hPa!mb#millibar!inHg#inHg  |
#11|--	|--	|distanceunit   |select         |km             |km#kilometer!mi#mile   |
|11|--	|--	|cloudbase      |select         |metres         |metres#metres!feet#feet  |

|12|--	|--	|--		|#		|conf-date_time_hd|       |
|12|--	|--	|dateFormat     |select         |d-m-Y           |d-m-Y#d-m-Y for DAY MONTH YEAR format (12-03-2017)!"
                                                                ."m-d-Y#m-d-Y for MONTH DAY YEAR format (03-12-2017)!"
                                                                ."Y-m-d#Y-m-d  for YEAR MONTH DAY format (2017-12-03)|
|12|--	|--	|clockformat    |select         |24              |24#24 Main Clock output example 17:32:12!"
                                                                ."12#12 Main Clock output example 5:32:12 pm|
|12|--	|--	|timeFormat     |select         |g:i a           |H:i:s#H:i:s  = 17:34:22 format!"
                                                                ."g:i:s a#g:i:s a = 05:34:22 am format!"
                                                                ."g:i a#g:i a = 05:34 am format!"
                                                                ."g:i:s#g:i:s = 05:34:22 format|
|12|--	|--	|timeFormatShort|select         |H:i             |H:i#H:i = 17:34 format!"
                                                                ."g:i a#g:i a = 5:34pm format|
|13|--	|--	|--		|#		|conf-menu_hd|                          |
|13|--	|--	|extralinks     |select         |false          |true#Yes, we do.!false#Do not use this|
|13|--	|--	|themes         |select         |true           |true#Allow visitor to switch colour themes.!false#Not allowed|
|13|--	|--	|theme1         |select         |user           |light#light!dark#dark!user#your own color set|


|19|--	|--	|--		|#		|conf-extra_devices_hd|                      |
|19|--	|--	|purpleairhardware|select       |false          |true#Yes, we do.!false#Do not use this|
|19|--	|--	|purpleairID    |noDecimal      |00000          |                       |
|19|--	|--	|luftdatenhardware|select       |false          |true#Yes, we do.!false#Do not use this|
|19|--	|--	|luftdatenID    |noDecimal      |0000           |                       |
|19|--	|--	|gov_aqi        |select         |false          |true#Yes, we want to use that AQI.!false#Do not use this|
|19|--	|--	|waqitoken      |htmltext       |               |                       |
|19|--	|--	|boltek         |select         |false          |true#Yes, we do.!false#Do not use this|
|19|--	|--	|boltekfile     |htmltext       |demodata/NSRealtime.txt|               | # boltekfile = nexstorm
|19|--	|--	|mywebcam       |select         |false          |true#Yes, we do.!false#Do not use this|
|19|--	|--	|mywebcamimg    |htmltext       |replace wiht link               |                       |
|19|--	|--	|weatherflowoption|select       |false          |true#Yes, we do.!false#Do not use this|
|19|--	|--	|weatherflowID  |noDecimal      |00000          |                       |
|19|--	|--	|uvsolarsensors |select         |false          |false#Do not have these!"
                                                                ."both#Our station has both UV and solar!"
                                                                ."darksky#Our station has solar. Use Darksky UV forcast as the UV sensor!"
                                                                ."wf#We use a weatherflow device for UV and solar|

|19|--	|--	|--		|#		|conf-top_small_hd|                      | 
|19|--	|--	|position1	|select 	|wind_c_small.php      |".$easystr3."|
|19|--	|--	|position2	|select 	|temp_c_small.php      |".$easystr3."|
|19|--	|--	|position3	|select 	|rain_c_small.php      |".$easystr3."|
|19|--	|--	|position4	|select 	|earthquake_c_small.php|".$easystr3."|
|19|--	|--	|positionlast	|select 	|advisory_c_small.php|advisory_c_small.php#Always the advices are displayed here|

|20|--	|--	|--		|#		|conf-top_row_hd|  
|20|--	|--	|position12	|select         |fct_darksky_block.php|".$easystr4."|
|20|--	|--	|position13     |select         |ccn_darksky_block.php|".$easystr5."|

|21|--	|--	|--		|#		|conf-bottom_row_hd|                      |
|21|--	|--	|position32	|select         |extra_temp_block.php|".$easystr6."|
|21|--	|--	|position33 	|select 	|extra_temp_block.php|".$easystr6."|

|22|--	|--	|--		|#		|conf-extra3used_hd|                      |
|22|--	|--	|extra3used     |select         |none              |wide#Yes, we need 1 extra block on the right of each row.!"
                                                                  ."row#Yes, we need an extra row of three blocks ath the bottom.!"
                                                                  ."none#Do not use this|
|22|--	|--	|position1e     |select 	|uvsolarlux_c_block.php|".$easystr6."|
|22|--	|--	|position2e     |select         |extra_temp_block.php  |".$easystr6."|
|22|--	|--	|position3e     |select         |webcam_c_block.php    |none#Do not use this!".$easystr6."|

|30|--	|--	|--		|#		|conf-suppliers_YRNO|                      |
|30|--	|--	|yrno_area 	|htmltext       |CHECK yr.no website|                      |


|30|--	|--	|--		|#		|conf-suppliers_DS|                      |
|30|--	|--	|dark_apikey 	|htmltext       |ADD YOUR API KEY| |
|30|--	|--	|language 	|select	        |en |ar#Arabic!az#Azerbaijani!be#Belarusian!bg#Bulgarian!bs#Bosnian!ca#Catalan!cs#Czech!da#Danish!de#German!el#Greek!"
                                                        ."en#English (which is the default)!es#Spanish!et#Estonian!fi#Finnish!fr#French!he#Hebrew!hr#Croatian!hu#Hungarian!"
                                                        ."id#Indonesian!is#Icelandic!it#Italian!ja#Japanese!ka#Georgian!ko#Korean!kw#Cornish!lv#Latvian!nb#Norwegian Bokmål!"
                                                        ."nl#Dutch!no#Norwegian Bokmål (alias for nb)!pl#Polish!pt#Portuguese!ro#Romanian!ru#Russian!sk#Slovak!sl#Slovenian!sr#Serbian!sv#Swedish!"
                                                        ."tet#Tetum!tr#Turkish!uk#Ukrainian!x-pig-latin#Igpay Atinlay!zh#simplified Chinese!zh-tw#traditional Chinese|
|30|--	|--	|darkskyunit 	|select         |si|si#si:Standard ISO!ca#ca: same as si, windSpeed km/h!uk2#uk: same as si,windSpeed mph!us#us: Imperial units (NON METRIC) |

|30|--	|--	|--		|#		|conf-suppliers_AERIS|                      |
|30|--	|--	|aeris_access_id|htmltext       |ADD YOUR API KEY| |
|30|--	|--	|aeris_secret_key|htmltext      |ADD YOUR API KEY| |

|30|--	|--	|--		|#		|conf-suppliers_WU|                      |
|30|--	|--	|wu_apikey 	|htmltext       |ADD YOUR API KEY| |
|30|--	|--	|wuID 	        |htmltext       |no key| |
|30|--	|--	|wu_start 	|htmltext       |YYYY-MM-DD| |

|30|--	|--	|--		|#		|conf-suppliers_METAR| 
|30|--	|--	|metarapikey    |htmltext       |ADD YOUR API KEY| |

|30|--	|--	|--		|#		|conf-suppliers_DWL|                      |
|30|--	|--	|dwl_api        |htmltext       |ADD YOUR API KEY| |
|30|--	|--	|dwl_did        |htmltext       |your DID| |
|30|--	|--	|dwl_pass       |htmltext       |wl.com password| |

|30|--	|--	|--		|#		|conf-suppliers_DWL2|                      |
|30|--	|--	|dwl_api2       |htmltext       |ADD YOUR API KEY| |
|30|--	|--	|dwl_secret     |htmltext       |ADD YOUR API KEY| |
|30|--	|--	|dwl_station    |noDecimal      |000000| |

|30|--	|--	|--		|#		|conf-suppliers_AW|                      |
|30|--	|--	|aw_key         |htmltext       |ADD YOUR API KEY| |
|30|--	|--	|aw_did         |htmltext       |your DID| |

|40|--	|--	|--		|#		|conf-other_hd|                      |
|40|--	|--	|show_indoor    |select         |true          |true#Yes, we do.!false#Do not use this|
|40|--	|--	|use_round      |select         |false         |true#Yes make items round.!false#We like the square items|
|40|--	|--	|txt_border     |select         |true          |true#Leave the small borders around text blocks, we like that.!false#We like \"Less is more\", remove those borders|
|40|--	|--	|close_popup    |select         |true          |true#Yes, show the close button/text in every pop-up!false#Do not use this|
|40|--	|--	|personalmessage|htmltext       |Never base important decisions that could result in harm to people or property on this weather information.||
";
#EOT;
        if (!isset ($LANGLOOKUP['conf-password_hd'])) 
             {  $LANGLOOKUP['conf-password_hd']         = 'Protect  your settings with a password';
                $LANGLOOKUP['conf-password']            = 'Use a string you remember easily, but not to short and not 12345';
                $LANGLOOKUP['conf-show_settings']       = 'Show a menu entry for this setings script?';
                  
                $LANGLOOKUP['conf-language_hd']	        = 'Choose the default language to display and use..';
                $LANGLOOKUP['conf-defaultlanguage']	= 'Template language to be used as default';
                $LANGLOOKUP['conf-otherlanguage_code']	= 'If you selected "Your_added_language" in the dropdown above and<br />if you created/downloaded another language pack
set the "locale".<br />This code is used only for "locale" time-strings.';
                $LANGLOOKUP['conf-otherlanguage_name']	= 'AND only for "Your_added_language" set the <b>name</b> to be used in the menu.';
                
                $LANGLOOKUP['conf-lang_select']	        = 'Are visitors allowed to change the template language?';
                $LANGLOOKUP['conf-lang_select_yes']	= 'A visitor may change the language used';
                $LANGLOOKUP['conf-country_flag']	        = 'Your country flag';
                $LANGLOOKUP['conf-livedata_hd']	        = 'Live data file we will use';
                $LANGLOOKUP['conf-livedataFormat']	= 'How dow we get our live data?';
                $LANGLOOKUP['conf-livedata']	        = 'Path to your <b>realtime</b> data file.</b> Not used with an API.
<br />Correct path is essential for live realtime data display.
<br />Example "../clientraw.txt" when your file is in the root.';
                $LANGLOOKUP['conf-extra_sensors_hd']	        = 'Extra sensors on our weatherstation';
                $LANGLOOKUP['conf-have_extra']	        = 'If we have extra sensors we need to  upload the data file for those sensors';
                $LANGLOOKUP['conf-extra_data']	        = 'Path and filename of the uploaded file (f.i. demodata/extra_sensors.txt  )';  
                
                $LANGLOOKUP['conf-liveYMD']	        = 'Format of the dates  in the uploaded realtime file';
                $LANGLOOKUP['conf-history_hd']          = 'Historical data: Use WU or your webserver';
                $LANGLOOKUP['conf-charts_from']         = 'Should the graph-script use WU-data?
<br /> Or will a cron-job save your historical data on your own website?';            


                $LANGLOOKUP['conf-hardware_hd']	        = 'Which brand and type of weatherstation do you own?';
                $LANGLOOKUP['conf-hardware']	        = 'Describe your weather-station';
                $LANGLOOKUP['conf-davis']	        = 'Do you own a Davis weather-station?';          
                $LANGLOOKUP['conf-descriptions_hd']	= 'Station/owner details, keep the descriptions short ';
                $LANGLOOKUP['conf-stationName']	        = 'The name to be used for your station';
                $LANGLOOKUP['conf-stationlocation']	= 'A relative short name for the area/region your weather-station is in.';
 
                $LANGLOOKUP['conf-contact_hd']          = 'The contact pop-up';
                $LANGLOOKUP['conf-contact_show']        = 'Do we want to use it';
                $LANGLOOKUP['conf-email']	        = 'The email address for the contact pop-up';
                $LANGLOOKUP['conf-twitterUser']	        = 'Do you have a twitter account?';
                $LANGLOOKUP['conf-twitter']	        = 'Your @twitter account';
                $LANGLOOKUP['conf-location_hd']	        = 'Your station location details';
                $LANGLOOKUP['conf-noDST']               = 'Change only if you really never reset your clocks';               
                $LANGLOOKUP['conf-TZ']	                = 'Set your timezone according to the PHP standards. '
                                                                .'<a target="_blank"; href="http://php.net/manual/en/timezones.php">Check here</a>';
                $LANGLOOKUP['conf-lat']	                = 'Latitude (and next field longitude) are also specified in your weather program.'
                                                                .'<br />Example: 50.8500 is for Leuven in Belgium. <a href="https://www.google.com/maps/" target="blank">Check here.</a> '
                                                                .'<br />North of the equator has  no sign.  South of the equator has a - sign.';
                $LANGLOOKUP['conf-lon']	                = 'For longitudes <b>left</b> of Greenwich a <b>-</b> sign is needed.'
                                                                .'<br />This is the <b style="color: red;">opposite</b> as used in WeatherDisplay!'; 
                $LANGLOOKUP['conf-icao1']	        = 'Enter your nearest airport code (XXXX) or find one <a target="_blank"; href="https://www.travelmath.com/nearest-airport/">here</a>';
                $LANGLOOKUP['conf-metar']	        = 'Display Nearby Metar pop window';
                $LANGLOOKUP['conf-metar_yes']	        = 'We will use this to for the current conditions display';
                $LANGLOOKUP['conf-airport1']	        = 'Short descriptive name of the airport';
                $LANGLOOKUP['conf-airport1dist']	= 'Distance between your station and airport';
                $LANGLOOKUP['conf-weatheralarm']	= 'Do you want to use a weatherlarm service';
                $LANGLOOKUP['conf-alarm_area']	        = '<b>Europe:</b> code for your area, example BE004'
                                                         .'<br /><b>Canada:</b> <a href="http://dd.weather.gc.ca/citypage_weather/docs/site_list_en.csv" target="blank">Download this list.</a> '
                                                         .'Use an editor find your area.<br />Example: s0000047 is the code for Calgary';     
                $LANGLOOKUP['conf-province']	        = 'Canada only: The two letter code for your province';                             
                $LANGLOOKUP['conf-region']	        = 'Region used for unit conversion of WU data';
                $LANGLOOKUP['conf-units_used_hd']	= 'Type of units (example: C or F) to be used';
                $LANGLOOKUP['conf-unit']	        = 'Choose the general units setting';          
                $LANGLOOKUP['conf-dec_tmp']	        = 'Select number of decimals you want to use for these weather-values';
                $LANGLOOKUP['conf-dec_wnd']	        = ' ';
                $LANGLOOKUP['conf-dec_rain']	        = ' ';
                $LANGLOOKUP['conf-dec_baro']	        = ' ';
                $LANGLOOKUP['conf-rainrate']	        = 'Intensity of rain per hour or minute';
                $LANGLOOKUP['conf-pressureunit']	= 'Unit for air pressuere / barometer';
                $LANGLOOKUP['conf-distanceunit']	= 'Distance';
                $LANGLOOKUP['conf-cloudbase']	        = 'Cloudbase height';                       
                $LANGLOOKUP['conf-date_time_hd']	= 'Select date and time formats to be used';
                $LANGLOOKUP['conf-dateFormat']	        = 'Date format';
                $LANGLOOKUP['conf-clockformat']	        = 'Use 24 or 12 hour clock';
                $LANGLOOKUP['conf-timeFormat']	        = 'Time format';
                $LANGLOOKUP['conf-timeFormatShort']	= 'Short time format used for Sun- & Moon- rise/set';
                $LANGLOOKUP['conf-menu_hd']	        = 'Menu options';
                $LANGLOOKUP['conf-themes']	        = 'Display theme-selection in Menu ';
                $LANGLOOKUP['conf-extralinks']	        = 'Display Extra links in Menu (default they are not shown)';
                $LANGLOOKUP['conf-theme1']	        = 'Default Theme Colour ';
                $LANGLOOKUP['conf-extra_devices_hd']	= 'Optional devices to be used';
                $LANGLOOKUP['conf-purpleairhardware']	= 'Do you own a Purpleair sensor?';
                $LANGLOOKUP['conf-purpleairID']	        = 'If we have one, what is the sensor-ID?';
                $LANGLOOKUP['conf-luftdatenhardware']	= 'Do you own a Luftdaten sensor?';
                $LANGLOOKUP['conf-luftdatenID']	        = 'If we have one, what is the sensor-ID?';
                $LANGLOOKUP['conf-gov_aqi']	        = 'Do you want to show an official AQ station nearby?';
                $LANGLOOKUP['conf-waqitoken']	        = 'You need an api token for that, get one at <a href="https://aqicn.org/data-platform/token/#/" target="blank">this site!</a>';
                $LANGLOOKUP['conf-boltek']	        = 'Do you own a Nexstorm device (Astrogenic Systems)';
                $LANGLOOKUP['conf-boltekfile']	        = 'Set the path to your NSRealtime.txt';
                $LANGLOOKUP['conf-weatherflowoption']	= 'Do you own a Weatherflow station (AIR and SKY)';
                $LANGLOOKUP['conf-weatherflowID']	= 'Weather-Flow STATION ID';
                $LANGLOOKUP['conf-mywebcam']	        = 'Do you have webcam you want to show';
                $LANGLOOKUP['conf-mywebcamimg']	        = 'Specify the link to your webcam image';
                $LANGLOOKUP['conf-uvsolarsensors']      = 'Is an UV and Solar sensor available?';             
                $LANGLOOKUP['conf-top_small_hd']	= 'Options for top row with small modules';
                $LANGLOOKUP['conf-position1']	        = 'Position 1';
                $LANGLOOKUP['conf-position2']	        = 'Position 2';
                $LANGLOOKUP['conf-position3']	        = 'Position 3';
                $LANGLOOKUP['conf-position4']	        = 'Position 4';
                $LANGLOOKUP['conf-positionlast']	= 'Position last';
                $LANGLOOKUP['conf-positionlasttitle']	= 'Position last Title';
                $LANGLOOKUP['conf-top_row_hd']          = 'Options for top row module 2 &amp; 3';
                $LANGLOOKUP['conf-position12']	        = 'Default Darksky, WeatherUnderground,  EC for Canadian users or use WXSIM when you installed that product';
                $LANGLOOKUP['conf-position13']	        = 'Where do we get our current conditions from?';
                $LANGLOOKUP['conf-bottom_row_hd']       = 'Options for bottom row module 2 &amp; 3';
                $LANGLOOKUP['conf-position32']	        = 'Select script to be used (mostly UV)';
                $LANGLOOKUP['conf-position33']	        = 'Select script to be used in last position';
                $LANGLOOKUP['conf-extra3used_hd']       = 'Optional extra blocks';
                $LANGLOOKUP['conf-extra3used']          = 'Do you want to show three extra blocks on your website';
                $LANGLOOKUP['conf-position1e']	        = 'Select script to be used';
                $LANGLOOKUP['conf-position2e']	        = 'Select script to be used';
                $LANGLOOKUP['conf-position3e']	        = 'Select script to be used';

                $LANGLOOKUP['conf-suppliers_DS']	= 'Important settings for Darksky';
                $LANGLOOKUP['conf-dark_apikey']	        = 'DarkSky API Key for forecast and current conditions. <a href="https://darksky.net/dev/register" target="blank">Register here</a>';
                $LANGLOOKUP['conf-darkskyunit']	        = 'DarkSky API UNITS';
                $LANGLOOKUP['conf-language']	        = 'DarkSky forecast Language';
                                
                $LANGLOOKUP['conf-suppliers_YRNO']	= 'When using yr.no forecast';
                $LANGLOOKUP['conf-yrno_area']	        = 'Go to yr.no website and search for a city nearby your location.'
                                                                .'The browser line will show AFTER <b>https://www.yr.no/place/</b> the area description. For my area it is Belgium/Flanders/Leuven/';

                $LANGLOOKUP['conf-suppliers_WU']	= 'Important settings for WeatherUnderground';
                $LANGLOOKUP['conf-wuID']	        = 'WeatherUnderground station ID for historical charts';
                $LANGLOOKUP['conf-wu_start']	        = 'First day of uploading <b> correct </b>data to WU<br />Format is YYYY-MM-DD  example 2018-11-24   for November 24, 2018';
                $LANGLOOKUP['conf-wu_apikey']           = 'Your 2019 WU API-key for PWS owners, as generated on your WU dashboard';

                $LANGLOOKUP['conf-suppliers_METAR']	= 'API key to retrieve METAR (local airport)';                
                $LANGLOOKUP['conf-metarapikey']	        = 'CheckWX Metar API KEY you need to sign up <a href="https://www.checkwx.com/signup" target="blank">here</a>';

                $LANGLOOKUP['conf-suppliers_AW']	= 'Important settings for AmbientWeather';
                $LANGLOOKUP['conf-aw_did']              = 'Device ID as used for downloading from AmbientWeather.net';
                $LANGLOOKUP['conf-aw_key']              = 'API-key to allow reading your station data from AmbientWeather.net';

                $LANGLOOKUP['conf-suppliers_DWL']	= 'WeatherLink Cloud version 1 API - f.i. IP-logger';
                $LANGLOOKUP['conf-dwl_api']             = 'API Token v1: as generated on your dashboard';
                $LANGLOOKUP['conf-dwl_did']             = 'Device ID as on sticker of IP-logger';
                $LANGLOOKUP['conf-dwl_pass']            = 'Password used to access weatherlink.com';
                
                $LANGLOOKUP['conf-suppliers_DWL2']	= 'WeatherLink Cloud version 2 API - WeatherLink Live';
                $LANGLOOKUP['conf-dwl_api2']            = 'API Key v2: as generated on your dashboard';
                $LANGLOOKUP['conf-dwl_secret']          = 'API Secret: as generated on your dashboard';
                $LANGLOOKUP['conf-dwl_station']         = 'Station ID check test-program';

                $LANGLOOKUP['conf-suppliers_AERIS']	= 'Important settings for Aeris Weather forecast data';
                $LANGLOOKUP['conf-aeris_access_id']	= 'Aeris API Access ID';
                $LANGLOOKUP['conf-aeris_secret_key']	= 'Aeris API Secret Key';

                $LANGLOOKUP['conf-other_hd']	        = 'Other settings';
                $LANGLOOKUP['conf-show_indoor']         = 'Show indoor temperatures?';
                $LANGLOOKUP['conf-personalmessage']     = 'Optional text to be placed in the footer?';
                $LANGLOOKUP['conf-use_round']           = 'Change some items, such as the temperature, from square to round';
                $LANGLOOKUP['conf-txt_border']          = 'Remove the thin or coloured borders around the small text parts';
                $LANGLOOKUP['conf-close_popup']         = 'Default a close button and text is displayed in te top left corner of the pop-ups';
                $LANGLOOKUP['Do not use this']	        = 'Do not use this';
                $LANGLOOKUP['Do not allow this']	= 'Do not allow this';
                $LANGLOOKUP['Yes, we do.']	        = 'Yes, we do.';
        }  // eo language texts

} // eo load texts

function tr_setting($key, $arr) # generate one line
     {  global  $wp, $region; 
	$wp = '--'; $region = '--';
	if ($arr['wp'] 	   <> '--' && $LANGLOOKUP['wp']     <> $wp) 	// skip lines for other weatherprogram	
		{return;} 
	if ($arr['region'] <> '--' && $arr['region'] <> $region)// skip lines for other region	
		{return;}
	if ($arr['type'] == 'none') 				// skip no use lines
		{return;}					
	if ($arr['type'] == '##') {
		echo '
    <tr class="headerline2"><td class="headerline2" colspan="2">'.langtransstr($arr['old']).'</td></tr>';	
		return;
	}
	if ($arr['type'] == '#') {
		echo '
    <tr class="headerline1"><td> </td><td class="headerline1"  >'.langtransstr($arr['old']).'</td></tr>';	
		return;
	}
	$border         = $error = '';
	$setting	= $arr['setting'];
	$text		= 'conf-'.trim($setting);
	$link		= 'link-'.trim($setting);
	$text_trans     = langtransstr($text);
	if ($text == $text_trans) {$text_trans = 'explain text for '.$setting.' will be added shortly';}

	$field_type	= $field_typeXX	= $arr['type'];
	$value		= $arr['new'];
	$value_old	= $arr['old'];
	$field_type	= str_replace ('region','',$field_typeXX);
	if ($field_type <> $field_typeXX) {
		$field_type	= lcfirst($field_type);
		$arrOld	= explode ('!',$value_old.'!');
		foreach ($arrOld as $keyOld => $val_regOld) {
			list ($value_old, $regionOld) 	= explode ('#',$val_regOld.'#');	
			if (($regionOld == $region) || ($regionOld == '')  || ($regionOld == 'all') || ($regionOld == '--')  ) {break;}
		} // eo foreach 
	} 
	if  	( ($value_old == 'true') && ($value === true) )	{$value = $value_old;} 
	elseif  ( ($value_old == 'false')&& ($value === false)) {$value = $value_old;}
	$class = '';
	if 	($value == '')		{$value	= $value_old; $class = 'default';} 
	$values		= $arr['values'];
	echo '
    <tr class="">
      <td class="label"><span class="outkey">'.$setting.'</span>'.$text_trans.'</td>
      <td class="value" '.$border.'>';
# ----------------------------      
	if ($field_type == 'select') {
		echo '
        <div class="input '.$class.'" ><!-- '.$field_type.' with value = '.$value. ' -->
          <select class="edit  '.$class.'" id="config__'.$key.'" name="settings['.$setting.']">';
		$arr_values 	= explode ('!',$values);
		foreach ($arr_values as $none => $string) {
			list ($short,$long,$optional) = explode ('#',$string.'#');
			$optional	= trim($optional);
			if ($optional <> '') {
				$ok = array($region, 'all', '--');
				if (!in_array ($optional, $ok ) ) {continue;}
			}
			if ($value == $short) {$selected = 'selected="selected"';} else {$selected = '';}
			$long	= langtransstr($long);
			echo '
            <option value="'.$short.'" '.$selected.'>'.$long.'</option>';
		}
            	echo '
          </select>  
        </div>';
	}
# ----------------------------
	elseif (  $field_type  == 'htmltext' || $field_type == 'numberDecimal'  ||
		  $field_type  == 'allcap'  || $field_type == 'noDecimal') {
		echo '
        <div class="input '.$class.'"><!-- '.$field_type.' with value = '.$value. ' -->
          <input id="config__'.$key.'" name="settings['.$setting.']" type="text" class="edit" value="'.$value.'">
        </div>';
        }
# ----------------------------
	else {	echo ' 
 	<div class="input '.$class.'"><!-- '.$field_type.' with value = '.$value. ' -->
          <input id="config__'.$key.'" name="settings['.$setting.']" type="text" class="edit" value="INVALID FIELDTYPE '.$field_type.' - '.$value.'">
        </div>';
        } // eo if list
	if ($error <> '') {echo $error;}    
	echo '
      </td>
    </tr>';
}

