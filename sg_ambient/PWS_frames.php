<?php $scrpt_vrsn_dt  = 'frames.php|00|2019-12-18|';  # release 1912
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
#
$frm_ttls       = array();
$frm_src        = array();
$frm_hgth       = array();
$frm_type       = array();
$frm_frme       = array();
# ==============================================#
# FIRST ones are  important, do NOT remove them #
# ==============================================#
#
# this one is optionally used by the weather warning scripts 
# DO NOT delete or change without contacting support
#
if (isset ($weatheralarm) && $weatheralarm <> '' && $weatheralarm <> false) {
        $frame                  = 'weatheralarms';
        $frm_ttls[$frame]       = lang('Official weather alarms');  // name in menu
        $frm_src[$frame]        = './wrnPrintWarnings.php';
        $frm_hgth[$frame]       = 800;         //height
        $frm_frme[$frame]       = true;
        $frm_type[$frame]       = 'div'; }
#
# this one is optionally  used if you have a weatherflow device
#
if ( isset ($weatherflowoption) && $weatherflowoption == true) {
        if (!isset ($weatherflowmapzoom) ) {$weatherflowmapzoom = 9;}
        $frame                  = 'WeatherFlow_map';
        $frm_ttls[$frame]       = 'WeatherFlow map';  // name in menu
        $frm_src[$frame]        = 'https://staging.smartweather.weatherflow.com/map/'.$lat.'/'.$lon.'/'.$weatherflowmapzoom;
        $frm_frme[$frame]       = true;
        $frm_hgth[$frame]       =  800;  }
#
# this one is optionally  used if use WXSIM as forecast
#
if ( $position12 == 'fct_wxsim_block.php') {
        $frame                  = 'wxsimPP';
        $frm_ttls[$frame]       = 'WXSIM '.lang('Forecast');  // name in menu
        $frm_src[$frame]        = './wxsimPP/plaintext-parser.php?lang='.substr($used_lang,0,2);
        #$frm_wdth[$frame]       =  650; 
        $frm_frme[$frame]       = true;
        $frm_hgth[$frame]       = 1500; }        

# ===
#
# this one is optionally  used if use WXSIM as forecast
#
if ( $livedataFormat == "DWL") {
        $frame                  = 'dwl_data';
        $frm_ttls[$frame]       = lang('Stations data at WL.com');  // name in menu
        $frm_src[$frame]        = './WLCOM_summary.php';
        #$frm_wdth[$frame]       =  650; 
        $frm_frme[$frame]       = true;
        $frm_type[$frame]       = 'div'; }        

# ===
#
# this one is optionally  used if use DARKSKY as forecast
#
if ( $position12 == 'fct_darksky_block.php') {
        $frame                  = 'darkskyPP';
        $frm_ttls[$frame]       = 'DarkSky '.lang('Forecast');  // name in menu
        $frm_src[$frame]        = 'https://darksky.net/forecast/'.$lat.','.$lon.'/'.$ds_page_unit.'/'.substr($locale_wu,0,2);
        $frm_frme[$frame]       = false;
        $frm_hgth[$frame]       = 2000;}
# ===
#
# this one is optionally  used if use yrno as forecast
#
if ( $position12 == 'fct_yrno_block.php') {
        $frame                  = 'yrnoPP';
        $frm_ttls[$frame]       = 'YrNo '.lang('Forecast');  // name in menu
        $frm_src[$frame]        = 'https://www.yr.no/place/'.$yrno_area.'/forecast.pdf';
        $frm_frme[$frame]       = true;
        $frm_hgth[$frame]       = 1500;}
#
# Full page Windy graphic and forecast,
#
$windfct_temp   = str_replace ('#',$tempunit,'%C2%B0#');
$windfct_wind   = str_replace ('/','%2F',$windunit);
$frame                  = 'windy';   ############  important  do not delete as it is used in the wind-block pop-up line 
$frm_ttls[$frame]       = 'Wind forecast';  // name in menu
$frm_src[$frame]        = 'https://embed.windy.com/embed2.html?lat='.round($lat,2).'&lon='.round($lon,2).'&zoom=5&level=surface&overlay=wind&menu=&message=&marker=&calendar=&pressure=&type=map&location=coordinates&detail=true'
        .'&detailLat='.round($lat,2).'&detailLon='.round($lon,2).'&metricWind='.$windfct_wind.'&metricTemp='.$windfct_temp.'&radarRange=-1"';
$frm_frme[$frame]       = true;
$frm_hgth[$frame]       = 900;         //height
#
# ===============================================#
# Your station data as shown on other sites
# You can have one page in your menu for each site
#
# You have to set which sites you uplod to 
#          in  _my_settings/upload_to.txt
# ===============================================#
$known_upl      = array ('Awekas','CWOP','ewn','mesonet','pwsweather','WeatherCloud','WOW','WU');
#
$txt_file       = '_my_settings/upload_to.txt'; 
if ( is_file($txt_file) ) 
     {  $arr_txt        = file ($txt_file);
        foreach ($arr_txt as $string) {
                if ( substr($string,0,1) == '#') {continue;}
                list ($key, $value)        = explode ('|',$string.'||');
                $key       = trim($key);
                if ($key == '') {continue;}
                if (!in_array ($key, $known_upl) ) {continue;}
#                
                $value          = trim($value);
                if ($value == '' || $value == 'not') {continue;}
#
                $frm_ttls[$key]         = $key;
                $frm_hgth[$key]         = 1500;
                $frm_frme[$key]         = true;
                switch ($key) {
                    case 'Awekas':
                        $frm_src[$key]  = 'https://www.awekas.at/premium/insert.php?id='.$value.'&amp;lg='.substr($defaultlanguage,0,1).'&amp;eh='.strtolower($tempunit).'&amp;tz=0&amp;header=0';
                        $frm_wdth[$key] = 590;
                        $frm_hgth[$key] = 654;
                        break;
                    case 'CWOP':
                        $frm_src[$key]  = 'https://weather.gladstonefamily.net/site/'.$value;
                        break;
                    case 'ewn':
                        $frm_ttls[$key] = 'European Weahter Network';
                        $frm_src[$key]  = 'https://euweather.eu/';
                        break;
                    case 'mesonet':
                        $frm_ttls[$key] = 'Member of "'.$value.'" Mesonet';
                        $frm_src[$key]  = 'https://www.northamericanweather.net/';
                        break;
                    case 'pwsweather':
                        $frm_src[$key]  = 'https://www.pwsweather.com/obs/'.$value.'.html';
                        break;
                    case 'WU':
                        $frm_ttls[$key] = 'WeatherUnderground';
                        $frm_src[$key]  = 'https://www.wunderground.com/dashboard/pws/'.$value;
                        $frm_frme[$key] = false;
                        break;
                    case 'WeatherCloud':
                        $frm_src[$key]  = 'https://app.weathercloud.net/'.$value.'#current';
                        break;
                    case 'WOW':
                        $frm_src[$key]  = 'https://wow.metoffice.gov.uk/weather/view?siteID='.$value;
                        break;
                     default: break; 
                }    
     } // eo foreach text  upload
} // upload file exists  echo '<pre>'.print_r($frm_src,true); exit;
#
if (!isset ($extralinks)) {$extralinks  = false;} // just to make sure in case of manual changes in settings
#
#           load the script with the extra menu settings and save them in an array
#
$scrpt          = '_my_settings/frames.php';
if ($extralinks == true && file_exists($scrpt) ) 
     {  echo $menu_extra;
        $stck_lst      .= basename(__FILE__).' ('.__LINE__.') include_once =>'.$scrpt.PHP_EOL;
        include_once $scrpt; # print_r($frm_ttls); exit;
}