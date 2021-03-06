<?php  $scrpt_vrsn_dt  = 'PWS_load_files.php|00|2020-05-05|';  # DWLv2api | minor changes | release 2004
#-----------------------------------------------
#         PWS-Dashboard - Updates and support by 
#     Wim van der Kuil https://pwsdashboard.com/
#-----------------------------------------------
#       display source of script if requested so
#-----------------------------------------------
if (isset($_REQUEST['sce']) && strtolower($_REQUEST['sce']) == 'view' ) {
   $filenameReal = __FILE__;    #               display source of script if requested so
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
elseif (!isset ($_REQUEST['test'])) 
     {  ini_set('display_errors', 0); error_reporting(0);}
else {  ini_set('display_errors', 1); error_reporting(1);}  
#
# -------------------save list of loaded scrips;
if (!isset ($stck_lst) ) {$stck_lst = '';}
$stck_lst      .= basename(__FILE__).'('.__LINE__.') loaded  =>'.$scrpt_vrsn_dt.PHP_EOL;       // save list of loaded scrips;
#
# load settings when run stand-alone
$scrpt          = 'PWS_settings.php';
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') include  =>'.$scrpt.PHP_EOL; 
include_once $scrpt; 
#
if (isset ($cron) && $cron == true) {$times  = 0.8;} else {$times  = 1;}
$now    = time();
$noload = $fl_to_load = '______________';
# 
unset   ($ch);
#
$fake_keys      = array();
$fl_to_load     = '';
#
# load current weather from WU with your private API 
if ($livedataFormat == 'wu') 
     {  $filename       = $livedata; 
        $cron_min_time  = 180;
        $allowed_age    = $cron_min_time*$times; 
        $url            = 'https://api.weather.com/v2/pws/observations/current?stationId='
                        .$wuID.'&format=json&units='
                        .$wu_unit.'&numericPrecision=decimal&apiKey='
                        .$wu_apikey;
        $fake_keys[0]   = $wu_apikey;
        fnctn_load_file ( 'WU-ccn');
        if (isset ($read_net_data) ) {return;}        
} // eo WU
#
# load current weather from WeatherLink Cloud with your v1 API 
elseif ($livedataFormat == 'DWL' && $dwl_api <> 'ADD YOUR API KEY')
     {  $filename       = $livedata; 
        $cron_min_time  = 300;
        $allowed_age    = $cron_min_time*$times;
        $url            = 'https://api.weatherlink.com/v1/NoaaExt.json?user='
                        .$dwl_did.'&pass='
                        .$dwl_pass.'&apiToken='
                        .$dwl_api;
        $fake_keys[0]   = $dwl_pass;
        $fake_keys[1]   = $dwl_api;
        fnctn_load_file ('WL-data');
        if (isset ($read_net_data) ) {return;}
} // eo Davis WL.com
elseif ($livedataFormat == 'DWL_v2api' && $dwl_api2 <> 'ADD YOUR API KEY'  && (int) $dwl_station <> 0)
     {  $filename       = $livedata; 
        $cron_min_time  = 300;
        $allowed_age    = $cron_min_time*$times;
        $now            = time();
        $data = 'api-key'.$dwl_api2.'station-id'.$dwl_station.'t'.$now; #die ($data .' secret= '.$dwl_secret);
        $apiSignature   = hash_hmac("sha256", $data, $dwl_secret);
        $url            = 'https://api.weatherlink.com/v2/current/'
                        .$dwl_station
                        .'?api-key='.$dwl_api2
                        .'&api-signature='.$apiSignature
                        .'&t='.$now;
        $fake_keys[0]   = $dwl_api2;
        $fake_keys[1]   = $dwl_api;
        fnctn_load_file ('WL-cloud-v2');
        if (isset ($read_net_data) ) {return;}
} // eo Davis WL.com
#
# load current weather from WeatherLink Cloud with your v2 API 

#
# load your current weather from weatherflow
elseif ( $livedataFormat == 'wf')
     {  $filename       = $livedata;
        $cron_min_time  = 300;
        $allowed_age    = $cron_min_time*$times;
        $url            = 'https://swd.weatherflow.com/swd/rest/observations/station/'
                        .$weatherflowID.'?api_key='
                        .$somethinggoeshere; 
        $fake_keys[0]   = $somethinggoeshere;
        fnctn_load_file ('WeatherFlow'); 
        if (isset ($read_net_data) ) {return;}
}  // eo   weatherflow
#
# load your current weather from AmbientWeather.net
elseif ( $livedataFormat == 'AWapi' && $aw_key <> 'ADD YOUR API KEY')
     {  $filename       = $livedata;
        $cron_min_time  = 120;
        $allowed_age    = $cron_min_time*$times;
        $AW_app_key     = '45234c52a04040e8a8d0e240a1236200cfbbbc0aaf7b4136a5b313e6088e7889';
        $AW_app_key     = '2d94ac7e48e74359a823319e79db8675b596bceb50634fdcb171cff819b4e284';
        $url            = 'https://api.ambientweather.net/v1/devices?applicationKey='.$AW_app_key.'&apiKey='.$aw_key;
        $fake_keys[0]   =  $AW_app_key;
        $fake_keys[1]   =  $aw_key;    
        $fl_to_load     = 'AmbientWeather'; 
        fnctn_load_file (); 
        if (isset ($read_net_data) ) {return;}
} 
#    
# load metar from checkwx.com with your private API 
if (isset ($metarapikey)  && $metarapikey <> '' && $metarapikey <> 'ADD YOUR API KEY')
     {  $filename       = $fl_folder.$mtr_fl;  
        $allowed_age    = $metarRefresh*$times;
        $url            = 'https://api.checkwx.com/metar/'
                        .$icao1.'/decoded';
        $header         = array( "X-API-KEY:".trim($metarapikey)."",);
        $fake_keys[0]    = $metarapikey;
        fnctn_load_file ('METAR-'.$icao1);
        $header         = ''; }  
else {  $name   = substr('METAR-'.$icao1.$noload,0,14);
        $stck_lst .= basename(__FILE__).' ('.__LINE__.') '.$name.': not loaded API='.$metarapikey.PHP_EOL; }
#
# load WeatherUnderground forecast with your private API
if ( isset ($wu_apikey)   && $wu_apikey <> ''  && $wu_apikey <> 'ADD YOUR API KEY')
     {  $filename       = $fl_folder.'wufct_'.$locale_wu.'_' .$wu_fct_unit.'.txt';  
        $allowed_age    = $fcts_refresh*$times;
        $latlon= round($lat,2).','.round($lon,2);
        $url            = 'https://api.weather.com/v3/wx/forecast/daily/5day?geocode='
                        .$latlon.'&format=json&units='
                        .$wu_fct_unit.'&language='
                        .$locale_wu.'&apiKey='
                        .$wu_apikey;
        $fake_keys[0]    = $wu_apikey;
        fnctn_load_file ('WU_forecast'); } 
else {  $name           = substr('WU_forecast'.$noload,0,14);
        $stck_lst .= basename(__FILE__).' ('.__LINE__.') '.$name.': not loaded API='.$wu_apikey.PHP_EOL; }
#
# load darksky  forecast and current conditions with your private API
if ( isset ($dark_apikey)  && $dark_apikey <> ''   && $dark_apikey <> 'ADD YOUR API KEY')
     {  $filename       = $fl_folder.$drksk_fl;
        $allowed_age    = $fcts_refresh*$times;
        $url            = 'https://api.forecast.io/forecast/'
                        .$dark_apikey.'/'
                        .$lat.','.$lon.'?lang='
                        .substr($locale_wu,0,2).'&units='
                        .$darkskyunit ;
        $fake_keys[0]   = $dark_apikey;
        fnctn_load_file ('Darksky'); } 
else {  $name           = substr('Darksky'.$noload,0,14);
        $stck_lst .= basename(__FILE__).' ('.__LINE__.') '.$name.': not loaded API='.$dark_apikey.PHP_EOL; }
#
# load yr.no  forecasts
if ( isset ($yrno_area)   && $yrno_area <> ''  && $yrno_area <> 'CHECK yr.no website')
     {  $filename       = $fl_folder.'yrno_fct_4p.xml';
        $allowed_age    = $fcts_refresh*$times;
        $url            = 'https://www.yr.no/place/'.$yrno_area.'varsel.xml';
        fnctn_load_file ('yrno_4dp_fct');
#
        $filename       = $fl_folder.'yrno_fct_hourly.xml';
        $url            = 'https://www.yr.no/place/'.$yrno_area.'forecast_hour_by_hour.xml';
        fnctn_load_file ('yrno_hourly_fct');}
else  { $name           = substr('yr.no'.$noload,0,14);
        $stck_lst .= basename(__FILE__).' ('.__LINE__.') '.$name.': Not used / not yet correctly set'.PHP_EOL; }

# load AERIS weatherdata 
if ( isset ($aeris_access_id)   && $aeris_access_id <> ''  && $aeris_access_id <> 'ADD YOUR API KEY'
  && isset ($aeris_secret_key)  && $aeris_secret_key <> '' && $aeris_secret_key <> 'ADD YOUR API KEY')
     {  $url_a  = 'https://api.aerisapi.com/';
        $url_b  = '/'.$lat.','.$lon.'?&format=json&client_id='.$aeris_access_id.'&client_secret='.$aeris_secret_key;
#
        $fake_keys[0]   = $aeris_secret_key;
        $fake_keys[1]   = $aeris_access_id;
        $filename       = $fl_folder.'aeris_fct_hrs.json';
        $allowed_age    = $metarRefresh*$times;
        $url            = $url_a.'forecasts'   .$url_b.'&filter=1hr&limit=26';
        fnctn_load_file ('Aeris_hourly'); 
#        
        $filename       = $fl_folder.'aeris_fct_dp.json';
        $allowed_age    = $fcts_refresh*$times;
        $url            = $url_a.'forecasts'   .$url_b.'&filter=daynight&limit=14';
        fnctn_load_file ('Aeris_dayparts');   
#         
        $filename       = $fl_folder.'aeris_ccn.json';
        $allowed_age    = $metarRefresh*$times;
        $url            = $url_a.'observations'.$url_b.'&filter=metar&limit=1';
        fnctn_load_file ('Aeris_ccn'); }
else {  $name           = substr('Aeris'.$noload,0,14);
        $stck_lst .= basename(__FILE__).' ('.__LINE__.') '.$name.': not loaded keys='.$aeris_access_id.' '.$aeris_secret_key.PHP_EOL; }



# $weatheralarm == 'canada' ||  currentconditions  from ccn_ec_block.php  
$load_ec = false; 
if ($weatheralarm == 'canada')
     {  $load_ec        = true;
        $allowed_age    = 600;}
elseif ($position13  == 'ccn_ec_block.php') 
     {  $load_ec        = true;
        $allowed_age    = 3000;}
if ($load_ec == true)
     {  $EClang = 'e';
        if (substr($used_lang,0,2)  == 'fr') { $EClang = 'f';}
        $filename       = $fl_folder.'ec_'.$province.'_'.$alarm_area.'_'.$EClang.'.xml';
        $url            = 'https://dd.meteo.gc.ca/citypage_weather/xml/'
                        .$province.'/'
                        .$alarm_area.'_'
                        .$EClang.'.xml';
        fnctn_load_file ('EC-weather');}
else {  $name           = substr('EC-weather'.$noload,0,14);
        $stck_lst .= basename(__FILE__).' ('.__LINE__.') '.$name.': Environement Canada: not used '.PHP_EOL; }
#
# load earthquakes file, can always be loaded, just check age 
$filename       = $fl_folder.$qks_fl;
$allowed_age    = $quakesRefresh*$times;
$url            = 'https://earthquake-report.com/feeds/recent-eq?json'; 
fnctn_load_file ('Earthquakes'); 
#
#  load k-index, can always be loaded, just check age
$filename       = $fl_folder.$kndx_fl;
$allowed_age    = $kindexRefresh*$times;
$url            = 'https://services.swpc.noaa.gov/products/geospace/planetary-k-index-dst.json'; 
fnctn_load_file ('Aurora-kindex'); 
#  
# load luftdaten air quality      
if(isset ($luftdatenhardware)  &&  (   $luftdatenhardware === true) )
     {  $filename       = $fl_folder.$lfdtn_fl;
        $allowed_age    = $luftRefresh*$times;
        $url            = 'https://data.sensor.community/airrohr/v1/sensor/' # replaced old url 'http://api.luftdaten.info/v1/sensor/'
                        .$luftdatenID.'/'; 
        fnctn_load_file ('AQ-Luftdaten'); }
else {  $name           = substr('AQ-Luftdaten'.$noload,0,14);
        $stck_lst .= basename(__FILE__).' ('.__LINE__.') '.$name.': luftdaten sensor not used '.PHP_EOL; }
# 
# load purple air quality                
if( isset ($purpleairhardware) && (   $purpleairhardware === true) )
     {  $filename       = $fl_folder.$prpl_fl;
        $allowed_age    = $purpleRefresh*$times;
        $url            = 'https://www.purpleair.com/json?show='
                        .$purpleairID;
        fnctn_load_file ('AQ-Purpleair'); }
else {  $name           = substr('AQ-Purpleair'.$noload,0,14);
        $stck_lst .= basename(__FILE__).' ('.__LINE__.') '.$name.': purplair sensor not used '.PHP_EOL; }
#
# load WAQI air quality
if(isset ($gov_aqi)  &&  (   $gov_aqi === true)  &&  $waqitoken <> 'ADD YOUR API KEY')
     {  $filename       = $fl_folder.$gvaqi_fl;
        $allowed_age    = 3600*$times;
        $url            = 'https://api.waqi.info/feed/geo:'
                        .$lat.';'.$lon.'/?token='
                        .$waqitoken;
        $fake_keys[0]   = $waqitoken;
        fnctn_load_file ('AQ-official'); }
else {  $name           = substr('AQ-official'.$noload,0,14);
        $stck_lst .= basename(__FILE__).' ('.__LINE__.') '.$name.': goverment aqi not used '.PHP_EOL; }
#
if ( $weatherflowoption == true && $livedataFormat <> 'wf') # load weatherflow 
     {  $filename       = $fl_folder.'weatherflow.txt';
        $allowed_age    = 660*$times;
        $url            = 'https://swd.weatherflow.com/swd/rest/observations/station/'
                        .$weatherflowID.'?api_key='
                        .$somethinggoeshere; 
        $fake_keys[0]   = $somethinggoeshere;
        fnctn_load_file ('Weatherflow'); } 
else {  $name           = substr('Weatherflow'.$noload,0,14);
        if ($livedataFormat == 'wf') {$text = ': WeatherFlow data already loaded';} else {$text = ': WeatherFlow device not used';}
        $stck_lst .= basename(__FILE__).' ('.__LINE__.') '.$name.$text.PHP_EOL; }
#
# check if we have to load WU files to generate charts
if ($charts_from <> 'WU') 
     {  $name           = substr('WU_graphs'.$noload,0,14);
        $stck_lst .= basename(__FILE__).' ('.__LINE__.') '.$name.': WeatherUnderground graphs-data not used '.PHP_EOL;
        if (isset ($_REQUEST['test'])) 
             {  echo '<pre>'.$stck_lst.'<pre>';}
        return;}
#        
if (trim($wuID) == '' || trim($wuID) == 'no key') 
     {  $name           = substr('WU_graphs'.$noload,0,14);
        $stck_lst .= basename(__FILE__).' ('.__LINE__.') '.$name.': No valid WU station name found '.trim($wuID).PHP_EOL; 
        if (isset ($_REQUEST['test'])) 
             {  echo '<pre>'.$stck_lst.'</pre>';}
        return;}      
#
# LAST part of loads , loading WU .CSV can take considarable time
$chartdata      = 'chartswudata';
$wu_server      = $this_server;
$from_year      = $now - 12*30*3600*24;
$year_month     = date ('m',$from_year);
$year_year      = date ('Y',$from_year);
$timeout_lf     = 20;
#
#
$nm_prt1        = realpath(dirname(__FILE__)).'/'.$chartdata.'/'.$wuID;    // chartswudata/IVLAAMSG47
$filename       = $nm_prt1.'YMD.txt';           // todays data as known by WU
$allowed_age    = 1800*$times;                  // 1800 seconds = 0.5 hour
$url            = $wu_server.'PWS_DailyHistory.php?ID='.$wuID.'&graphspan=day&day&month&year&format=1';   #localhost/pwsWD/   https://www.wunderground.com/weatherstation/
fnctn_load_file ('WU-today-CSV');
#
$now            = time();
$month          = date ('m',$now);
$year           = date ('Y',$now);
$day            = date ('d',$now);
$start          = $now - 31*3600*24;
$from_month     = date ('m',$start);
$from_year      = date ('Y',$start);
#
$filename       = $nm_prt1.'YM.txt';            // this "month" (30 days) data as known by WU
$allowed_age    = 4*3600*$times;
$url            = $wu_server.'PWS_DailyHistory.php?ID='.$wuID.'&graphspan=custom'
                        .  '&year='.$from_year.    '&month='.$from_month.     '&day='.$day.
                        '&yearend='.$year.       '&monthend='.$month.       '&dayend='.$day.'&format=1';   #echo $url.PHP_EOL;
fnctn_load_file ('WU-month-CSV');
#
$start          = $now - 365*3600*24;
$from_month     = date ('m',$start);
$from_year      = date ('Y',$start);
$filename       = $nm_prt1.'Y.txt';             // this "year" (360 days) data as known by WU
$allowed_age    = 12*3600*$times;
$url            = $wu_server.'PWS_DailyHistory.php?ID='.$wuID.'&graphspan=custom'
                        .  '&year='.$from_year.    '&month='.$from_month.     '&day='.$day.
                        '&yearend='.$year.       '&monthend='.$month.       '&dayend='.$day.'&format=1';  #echo $url; exit;                       
fnctn_load_file ('WU-year-CSV');
#
if (isset ($_REQUEST['test'])) {echo '<pre>'.$stck_lst.'</pre>';}
#
function fnctn_load_file ($string='')
     {  global $now, $filename, $allowed_age, $url, $stck_lst, $header, $fake_keys, $fl_to_load, $timeout_lf;
        if ($string <> '') {$fl_to_load = substr($string.'______________',0,14);}
        if (!isset ($timeout_lf)) {$timeout_lf = 10;}
        $start_time =  microtime(true);
        $fake_url = str_replace ($fake_keys, '_API_SETTING_',$url);
        if ( file_exists($filename))
             {  $age = $now - filemtime($filename);
                if ($age < $allowed_age)
                     {  $stck_lst .= basename(__FILE__).' ('.__LINE__.') '.$fl_to_load.': File not old enough ('.floor ($age).'/'.$allowed_age.' seconds) '.$fake_url.PHP_EOL; 
                        return false; } 
                        }
        $fp     = fopen($filename.'rnm', 'wb');
        if ($fp === false) 
             {  $stck_lst .= basename(__FILE__).' ('.__LINE__.') '.$fl_to_load.': NO DATA will be loaded - unable to open file to save data into: '.$filename.'rnm, check permissions of file and folder.'.PHP_EOL;
                return false;}
        $ch     = curl_init(); 
        if (isset ($header) && is_array($header) )
             {  curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
                unset ($header); }
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,$timeout_lf); // connection timeout
        curl_setopt($ch, CURLOPT_TIMEOUT,$timeout_lf);        // data timeout 
        curl_setopt($ch, CURLOPT_FILE, $fp );
        $result = curl_exec ($ch);
        $info	= curl_getinfo($ch);
        $error  = curl_error($ch);
        curl_close ($ch);
        $now    = microtime(true);
        $passed = $now - $start_time;
        if ($passed < 0.0001) {$string1 = '< 0.0001';} else {$string1 = round($passed,4);}
        $CHECK_HTTP_CODES = array ('404', '429','502', '500');
        if (in_array ($info['http_code'],$CHECK_HTTP_CODES) ) 
             {  $stck_lst .= basename(__FILE__).' ('.__LINE__.') '.$fl_to_load.': time spent: '.$string1.' - PROBLEM => http_code: '.$info['http_code'].', no valid data '.$fake_url.PHP_EOL;
                return false;} 
        if ($error <> '')
             {  $stck_lst .= basename(__FILE__).' ('.__LINE__.') '.$fl_to_load.': time spent: '.$string1.' -  invalid CURL '.$error.' '.$fake_url.PHP_EOL; 
                return;}
        else {  $stck_lst .= basename(__FILE__).' ('.__LINE__.') '.$fl_to_load.': time spent: '.$string1.' -  CURL OK for '.$fake_url.PHP_EOL; }
        fclose ($fp);
        if (file_exists($filename.'rnm') && filesize ($filename.'rnm') > 10) 
             {  rename ($filename,$filename.'old');
                $rename = rename ($filename.'rnm',$filename);
                if ($rename === false) 
                     {  $stck_lst .= basename(__FILE__).' ('.__LINE__.') '.$fl_to_load.': File '.$filename .'could not be created, check permissions of file and folder'.PHP_EOL;
                        rename ($filename.'old', $filename);
                        return false;}
                }
        else {  $stck_lst .= basename(__FILE__).' ('.__LINE__.') '.$fl_to_load.': time spent: '.$string1.' -  empty data or wrong file permissions for '.$fake_url.' Old data will be used '.$filename.'rnm'.PHP_EOL;}
        unset  ($ch, $fp);
} // eof fnctn_load_file
