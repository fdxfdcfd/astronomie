<?php  $scrpt_vrsn_dt  = 'PWS_blocks.php|00|2020-04-23|'; # extra block | release 2004 
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
elseif (!isset ($_REQUEST['test'])) {  ini_set('display_errors', 0); error_reporting(0);}
#
$stck_lst       .= basename(__FILE__).'('.__LINE__.')  loaded  =>'.$scrpt_vrsn_dt.PHP_EOL;       // save list of loaded scrips;
#
$year_txt       = ' 360 '.lang('days') ;
$month_txt      = ' 30 '.lang('days');
$today_txt      = lang('Today');
$fct_txt        = lang('Forecast');
$AQ_txt         = lang('Air Quality');
$hist_txt       = lang('History');
$metar_txt      = lang('Airport');
$quake_txt      = lang('Earthquakes');
$map_txt        = lang('Map');
$radar_txt      = lang('Radar');
$moon_txt       = lang('Moon info');
$aurora_txt     = lang('Aurora');
$meteors_txt    = lang('Meteors');
$cam_txt        = lang('Enlarge');
$movie_txt      = lang('Movie');
$uv_guide_txt   = lang('UV Guide');
$extra_snsrs    = lang('Extra sensors');
$fct_daily      = lang('Daily');
$fct_hourly     = lang('Hourly');
$fct_page       = lang('Full page');
$fct_details    = lang('Details');
$fct_texts      = lang('Texts');
#
$head_baro      = lang('Barometer').' - '.lang($weather["barometer_units"]);
$head_temp      = lang('Temperature')        .' &deg;'.$weather['temp_units'];
$head_temp_smll = lang('Max-Min Temperature').' &deg;'.$weather['temp_units'];
$head_temp_in   = lang('Indoor Temperature') .' &deg;'.$weather["temp_units"];
$head_rain      = lang('Rainfalltoday').' - '.lang($weather["rain_units"]); 
$head_rain_smll = lang('Annual Rainfall');
$head_wind      = lang('Wind') .' | '. lang('Gust').' - '.lang($weather["wind_units"]); 
$head_wind_smll = lang('Max').' '.$head_wind ;
$head_lightning = lang('Lightning');
$head_quake     = lang('Earthquake');
$head_quake_smll= lang('Last Earthquake');
$head_ccn       = lang('Currentsky');
$head_sun       = lang('SunPosition');
$head_moon      = lang('Moonphase information');
$head_webcam    = lang('LiveWebCam');
$head_extr_smll = lang('Extra block');
$head_extr_blck = lang('Extra block large');
$head_temp_soil = lang('Soil info');
$head_uv_solar  = lang('Solar - UV-Index - Lux');
$head_AQ_prpl   = lang('Our PurpleAir sensor');
$head_AQ_lftdtn = lang('Our Luftdaten AQ sensor');
$head_AQ_fficl  = lang('Official AQ sensor station');
$head_AQ_cwtt   = lang('Our station AQ sensor');
$head_fct       = lang('Forecast'); // used for DarkSky
$head_fct_wu    = lang('WeatherUnderground forecast');
$head_fct_wxsim = lang('Our WXSIM forecast');
$head_fct_ec    = lang('Environment Canada forecast');
#
# refresh times for the blocks  !  not for the data loads
$rfrsh_temp     = 110;
$rfrsh_rain     = 90;
$rfrsh_wind     = 40;
$rfrsh_baro     = 190;
$rfrsh_lightning= 120;
$rfrsh_quakes   = 250;
$rfrsh_sun_moon = 280;
$rfrsh_uv_solar = 110;
$rfrsh_webcam   = 120;
$rfrsh_AQ       = 300;
$rfrsh_CCN      = 180;  
$rfrsh_fct      = 1800;
$rfrsh_fct_DS   = 500;
$rfrsh_others   = 110;
$rfrsh_small    = 100;  #extra wait time for high-low small blocks
#
$blck_ttls      = array();      // title of a block
$blck_rfrs      = array();      // refresh specific for this block IN SECONDS
$blck_ppp       = array();      // available popups
#
$script                 = 'clock_c_small.php';    
$blck_ttls[$script]     = '';
$blck_rfrs[$script]     = false;                // needs no refresh
#
$script                 = 'temp_c_small.php';      // max min temp
$blck_ttls[$script]     = $head_temp_smll; #$head_temp_small;
$blck_rfrs[$script]     = $rfrsh_small + $rfrsh_temp;  
#
$script                 = 'rain_c_small.php';
$blck_ttls[$script]     = $head_rain_smll;
$blck_rfrs[$script]     = $rfrsh_small + $rfrsh_rain;  
#
$script                 = 'wind_c_small.php';
$blck_ttls[$script]     = $head_wind_smll;  
$blck_rfrs[$script]     = $rfrsh_small + $rfrsh_wind;  
#
$script                 = 'lightning_boltek_small.php';
$blck_ttls[$script]     = $head_lightning;
$blck_rfrs[$script]     = $rfrsh_lightning;  
#
$script                 = 'lightning_station_small.php';
$blck_ttls[$script]     = $head_lightning;
$blck_rfrs[$script]     = $rfrsh_lightning;  
#
$script                 = 'lightning_wf_small.php';
$blck_ttls[$script]     = $head_lightning;
$blck_rfrs[$script]     = $rfrsh_lightning;  
#
$script                 = 'earthquake_c_small.php';
$blck_ttls[$script]     = $head_quake_smll;
$blck_rfrs[$script]     = $rfrsh_quakes;  
#
$script                 = 'advisory_c_small.php';
$blck_ttls[$script]     = '';
$blck_rfrs[$script]     = $rfrsh_others;  
#
$script                 = 'extra_tmp_c_small.php';
$blck_ttls[$script]     = $head_extr_smll;
$blck_rfrs[$script]     = $rfrsh_small + $rfrsh_temp;; 
#
$script                 = 'extra_temp_block.php';
$blck_ttls[$script]     = $head_extr_blck;
$blck_rfrs[$script]     = $rfrsh_small + $rfrsh_temp;; 
#
$script                 = 'soil_tmp_mst_small.php';      // max min temp
$blck_ttls[$script]     = $head_temp_soil; #$head_temp_small;
$blck_rfrs[$script]     = 500; #$rfrsh_small + $rfrsh_temp;  

$script = 'temp_c_block.php';
$blck_ttls[$script]     = $head_temp;
$blck_rfrs[$script]     = $rfrsh_temp; 
$blck_ppp [$script][]   = array ('show' => true,     'popup' => 'PWS_graph_xx.php?period=year&type=temp',   'chartinfo' => 'popup',  'text' => $year_txt );
$blck_ppp [$script][]   = array ('show' => true,     'popup' => 'PWS_graph_xx.php?period=month&type=temp',  'chartinfo' => 'popup',  'text' => $month_txt );
$blck_ppp [$script][]   = array ('show' => true,     'popup' => 'PWS_graph_xx.php?period=day&type=temp',    'chartinfo' => 'popup',  'text' => $today_txt );
$blck_ppp [$script][]   = array ('show' => true,     'popup' => 'fct_windy_popup.php?lang='.$used_lang, 'chartinfo' => 'popup',  'text' => $fct_txt );
$blck_ppp [$script][]   = array ('show' => $DWL,     'popup' => 'WLCOM_popup.php?lang='.$used_lang, 'chartinfo' => 'popup',  'text' => $extra_snsrs );

$script = 'ccn_aeris_block.php';
$blck_ttls[$script]     = $head_ccn;
$blck_rfrs[$script]     = $rfrsh_CCN; 
$blck_ppp [$script][]   = array ('show' => true,         'popup' => 'history_popup.php?lang='.$used_lang,      'chartinfo' => 'popup',  'text' => $hist_txt );
$blck_ppp [$script][]   = array ('show' => true,         'popup' => 'metar_aeris_popup.php?lang='.$used_lang,  'chartinfo' => 'popup',  'text' => $metar_txt );
$blck_ppp [$script][]   = array ('show' => true,         'popup' => 'earthquake_c_popup.php?lang='.$used_lang, 'chartinfo' => 'popup',  'text' => $quake_txt  );


$script = 'ccn_metar_block.php';
$blck_ttls[$script]     = $head_ccn;
$blck_rfrs[$script]     = $rfrsh_CCN; 
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'history_popup.php?lang='.$used_lang,      'chartinfo' => 'popup',  'text' => $hist_txt );
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'metar_popup.php?lang='.$used_lang,        'chartinfo' => 'popup',  'text' => $metar_txt );
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'earthquake_c_popup.php?lang='.$used_lang, 'chartinfo' => 'popup',  'text' => $quake_txt  );

$script = 'ccn_darksky_block.php';
$blck_ttls[$script]     = $head_ccn;
$blck_rfrs[$script]     = $rfrsh_CCN; 
$blck_ppp [$script][]   = array ('show' => true,          'popup' => 'history_popup.php?lang='.$used_lang,      'chartinfo' => 'popup',  'text' => $hist_txt );
$blck_ppp [$script][]   = array ('show' => $aeris_popup,  'popup' => 'metar_aeris_popup.php?lang='.$used_lang,  'chartinfo' => 'popup',  'text' => $metar_txt );
$blck_ppp [$script][]   = array ('show' => $metar_popup,  'popup' => 'metar_popup.php?lang='.$used_lang,        'chartinfo' => 'popup',  'text' => $metar_txt );
$blck_ppp [$script][]   = array ('show' => true,          'popup' => 'earthquake_c_popup.php?lang='.$used_lang, 'chartinfo' => 'popup',  'text' => $quake_txt  );

$script = 'ccn_cltraw_block.php';
$blck_ttls[$script]     = $head_ccn;
$blck_rfrs[$script]     = $rfrsh_CCN; 
$blck_ppp [$script][]   = array ('show' => true,          'popup' => 'history_popup.php?lang='.$used_lang,      'chartinfo' => 'popup',  'text' => $hist_txt );
$blck_ppp [$script][]   = array ('show' => $aeris_popup,  'popup' => 'metar_aeris_popup.php?lang='.$used_lang,  'chartinfo' => 'popup',  'text' => $metar_txt );
$blck_ppp [$script][]   = array ('show' => $metar_popup,  'popup' => 'metar_popup.php?lang='.$used_lang,        'chartinfo' => 'popup',  'text' => $metar_txt );
$blck_ppp [$script][]   = array ('show' => true,          'popup' => 'earthquake_c_popup.php?lang='.$used_lang, 'chartinfo' => 'popup',  'text' => $quake_txt  );

$script = 'ccn_ec_block.php';
$blck_ttls[$script]     = $head_ccn;
$blck_rfrs[$script]     = $rfrsh_CCN; 
$blck_ppp [$script][]   = array ('show' => true,          'popup' => 'history_popup.php?lang='.$used_lang,      'chartinfo' => 'popup',  'text' => $hist_txt );
$blck_ppp [$script][]   = array ('show' => $aeris_popup,  'popup' => 'metar_aeris_popup.php?lang='.$used_lang,  'chartinfo' => 'popup',  'text' => $metar_txt );
$blck_ppp [$script][]   = array ('show' => $metar_popup,  'popup' => 'metar_popup.php?lang='.$used_lang,        'chartinfo' => 'popup',  'text' => $metar_txt );
$blck_ppp [$script][]   = array ('show' => true,          'popup' => 'earthquake_c_popup.php?lang='.$used_lang, 'chartinfo' => 'popup',  'text' => $quake_txt  );

$script = 'wind_c_block.php';
$blck_ttls[$script]     = $head_wind;  
$blck_rfrs[$script]     = $rfrsh_wind; 
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'PWS_graph_xx.php?period=year&type=wind',   'chartinfo' => 'popup',  'text' => $year_txt );
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'PWS_graph_xx.php?period=month&type=wind',  'chartinfo' => 'popup',  'text' => $month_txt );
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'PWS_graph_xx.php?period=day&type=wind',    'chartinfo' => 'popup',  'text' => $today_txt );
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'fct_windy_popup.php?lang='.$used_lang, 'chartinfo' => 'popup',  'text' => $fct_txt );

$script = 'baro_c_block.php';
$blck_ttls[$script]     = $head_baro;
$blck_rfrs[$script]     = $rfrsh_baro; 
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'PWS_graph_xx.php?period=year&type=baro',   'chartinfo' => 'popup',  'text' => $year_txt );
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'PWS_graph_xx.php?period=month&type=baro',  'chartinfo' => 'popup',  'text' => $month_txt );
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'PWS_graph_xx.php?period=day&type=baro',    'chartinfo' => 'popup',  'text' => $today_txt );
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'fct_windy_popup.php?lang='.$used_lang, 'chartinfo' => 'popup',  'text' => $fct_txt );
$blck_ppp [$script][]   = array ('show' => $extralinks,'popup' => 'baromap' ,                                'chartinfo' => 'page',   'text' => $map_txt  );

$script = 'sun_c_block.php';
$blck_ttls[$script]     = $head_sun;
$blck_rfrs[$script]     = $rfrsh_sun_moon; 
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'moon_popup.php?lang='.$used_lang,     'chartinfo' => 'popup',  'text' => $moon_txt  );
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'aurora_popup.php?lang='.$used_lang,   'chartinfo' => 'popup',  'text' => $aurora_txt  );
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'meteors_popup.php?lang='.$used_lang,  'chartinfo' => 'popup',  'text' => $meteors_txt  );
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'image_popup.php?lang='.$used_lang,    'chartinfo' => 'popup',  'text' => $map_txt  );

$script = 'rain_c_block.php';
$blck_ttls[$script]     = $head_rain;
$blck_rfrs[$script]     = $rfrsh_rain; 
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'PWS_graph_xx.php?period=year&type=rain',   'chartinfo' => 'popup',  'text' => $year_txt );
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'PWS_graph_xx.php?period=month&type=rain',  'chartinfo' => 'popup',  'text' => $month_txt );
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'PWS_graph_xx.php?period=day&type=rain',    'chartinfo' => 'popup',  'text' => $today_txt );
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'fct_windy_popup.php?lang='.$used_lang, 'chartinfo' => 'popup',   'text' => $fct_txt );
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'rain_popup.php?lang='.$used_lang,          'chartinfo' => 'popup',   'text' => $radar_txt  );

$script = 'webcam_c_block.php';
$blck_ttls[$script]     = $head_webcam;
$blck_rfrs[$script]     = $rfrsh_webcam; 
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'webcam_popup.php?lang='.$used_lang,       'chartinfo' => 'popup',    'text' => $cam_txt  );
#$blck_ppp [$script][]   = array ('show' => true,      'popup' => '_my_settings/cam_movie_popup.php',        'chartinfo' => 'popup',    'text' => $movie_txt  );

$script = 'uvsolarlux_c_block.php';
$blck_ttls[$script]     = $head_uv_solar;
$blck_rfrs[$script]     = $rfrsh_uv_solar; 
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'uvsolarlux_popup.php?lang='.$used_lang,  'chartinfo' => 'popup',  'text' => $uv_guide_txt  );

$script = 'indoor_c_block.php';
$blck_ttls[$script]     = $head_temp_in;
$blck_rfrs[$script]     = $rfrsh_temp; 

$script = 'earthquake_c_block.php';
$blck_ttls[$script]     = $head_quake;
$blck_rfrs[$script]     = $rfrsh_quakes; 
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'earthquake_c_popup.php?lang='.$used_lang,  'chartinfo' => 'popup',  'text' => $quake_txt  );

$script = 'AQ_station_block.php';
$blck_rfrs[$script]     = $rfrsh_AQ; 
$blck_ttls[$script]     = $head_AQ_cwtt;
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'AQ_popup.php?lang='.$used_lang,       'chartinfo' => 'popup',  'text' => $AQ_txt );

$script = 'AQ_purpleair_c_block.php';
$blck_rfrs[$script]     = $rfrsh_AQ; 
$blck_ttls[$script]     = $head_AQ_prpl;
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'AQ_popup.php?lang='.$used_lang,       'chartinfo' => 'popup',  'text' => $AQ_txt );
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'airqualityPU' ,   'chartinfo' => 'page',   'text' => $map_txt  );

$script = 'AQ_luftdaten_c_block.php';
$blck_ttls[$script]     = $head_AQ_lftdtn;
$blck_rfrs[$script]     = $rfrsh_AQ; 
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'AQ_popup.php?lang='.$used_lang,       'chartinfo' => 'popup',  'text' => $AQ_txt );
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'airqualityLD' ,   'chartinfo' => 'page',   'text' => $map_txt  );

$script = 'AQ_gov_c_block.php';
$blck_ttls[$script]     = $head_AQ_fficl;
$blck_rfrs[$script]     = $rfrsh_AQ; 
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'AQ_popup.php?lang='.$used_lang,       'chartinfo' => 'popup',  'text' => $AQ_txt );
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'airqualityPP' ,   'chartinfo' => 'page',   'text' => $fct_page  );

$script = 'moon_c_block.php';
$blck_ttls[$script]     = $head_moon;
$blck_rfrs[$script]     = $rfrsh_sun_moon;
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'moon_popup.php?lang='.$used_lang,     'chartinfo' => 'popup',  'text' => $moon_txt  );
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'meteors_popup.php?lang='.$used_lang,  'chartinfo' => 'popup',  'text' => $meteors_txt  );

$script = 'fct_darksky_block.php';
$blck_ttls[$script]     = $head_fct;
$blck_rfrs[$script]     = $rfrsh_fct_DS; 
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'fct_darksky_popup_daily.php?lang='.$used_lang,  'chartinfo' => 'popup',  'text' => $fct_daily );
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'fct_darksky_popup_hourly.php?lang='.$used_lang, 'chartinfo' => 'popup',  'text' => $fct_hourly  );
#$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'DarkSky_fct_popup_embed.php' ,                  'chartinfo' => 'popup',   'text' => $fct_page  );

$script = 'fct_aeris_block.php';
$blck_ttls[$script]     = $head_fct;
$blck_rfrs[$script]     = $rfrsh_fct; 
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'fct_aeris_popup.php?lang='.$used_lang,  'chartinfo' => 'popup',  'text' => $fct_daily );
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'fct_aeris_popup_hrs.php?lang='.$used_lang,  'chartinfo' => 'popup',  'text' => $fct_hourly );

$script = 'fct_wxsim_block.php';
$blck_ttls[$script]     = $head_fct_wxsim;
$blck_rfrs[$script]     = $rfrsh_fct;
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'fct_wxsim_popup_daily.php?lang='.$used_lang, 'chartinfo' => 'popup',  'text' => $fct_details  );
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'fct_wxsim_popup_text.php?lang='.$used_lang,  'chartinfo' => 'popup',  'text' => $fct_texts  );
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'wxsimPP' ,                                      'chartinfo' => 'page',   'text' => $fct_page  );

$script = 'fct_ec_block.php';
$blck_ttls[$script]     = $head_fct_ec;
$blck_rfrs[$script]     = $rfrsh_fct;
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'fct_ec_popup_daily.php?lang='.$used_lang,    'chartinfo' => 'popup',  'text' => $fct_daily  );
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'fct_ec_popup_text.php?lang='.$used_lang,     'chartinfo' => 'popup',  'text' => $fct_texts  );
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'fct_ec_popup_hourly.php?lang='.$used_lang,  'chartinfo' => 'popup',  'text' => $fct_hourly  );

$script = 'fct_yrno_block.php';
$blck_ttls[$script]     = $head_fct;
$blck_rfrs[$script]     = $rfrsh_fct;
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'fct_yrno_popup.php?lang='.$used_lang,  'chartinfo' => 'popup',  'text' => $fct_details  );
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'fct_yrno_popup_hrs.php?lang='.$used_lang,   'chartinfo' => 'popup',  'text' => $fct_hourly  );
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'yrnoPP' ,   'chartinfo' => 'page',   'text' => $fct_page  );#yrnoPP

$script = 'fct_wu_block.php';
$blck_ttls[$script]     = $head_fct_wu;
$blck_rfrs[$script]     = $rfrsh_fct;
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'fct_wu_popup_daily.php?lang='.$used_lang,  'chartinfo' => 'popup',  'text' => $fct_details  );
$blck_ppp [$script][]   = array ('show' => true,      'popup' => 'fct_wu_popup_text.php?lang='.$used_lang,   'chartinfo' => 'popup',  'text' => $fct_texts  );
