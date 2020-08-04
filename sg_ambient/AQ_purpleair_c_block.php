<?php $scrpt_vrsn_dt  = 'AQ_purpleair_c_block.php|00|2020-03-23|';  # release 2004
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
# ------------check if script is already running
$string = str_replace('.php','',basename(__FILE__));
if (isset ($$string) ) {echo 'This info is already displayed'; return;}
$$string = $string;
#
# -------------load settings / shared data and common scripts
$scrpt          = 'PWS_settings.php';  
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') include_once =>'.$scrpt.PHP_EOL;
include_once $scrpt; 
$scrpt          = 'PWS_shared.php';  
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') include_once  =>'.$scrpt.PHP_EOL; 
include_once $scrpt; 
#-----------------------------------------------
#      load the data 
#-----------------------------------------------
#
$fl_t_ld                = $fl_folder.$prpl_fl;
$json_string            = file_get_contents($fl_t_ld);
$parsed_json            = json_decode($json_string,true);  #echo '<pre>'.print_r ($parsed_json,true); exit;
#-----------------------------------------------
#     check if data is usable
#-----------------------------------------------
$dataFALSE              = '';
if ( $parsed_json == FALSE) 
     {  $dataFALSE = __LINE__.': Invalid / no JSON data'; }
elseif (!array_key_exists ('results', $parsed_json) )
     {  $dataFALSE = __LINE__.': No data found'; } 
elseif (count($parsed_json['results']) === 0)
     {  $dataFALSE = __LINE__.': No sensordata found'; } 
elseif (!array_key_exists ('PM2_5Value', $parsed_json['results'][0]) )
     {  $dataFALSE = __LINE__.': No measurement found'; } 
if ($dataFALSE<> '')
     {  echo 'Problem '.$dataFALSE.'<br />Check settings and data'; return;}
#
#-----------------------------------------------
#      general functions / tables for AQ scripts
#-----------------------------------------------
$scrpt          = 'AQ_shared.php';  
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') include  =>'.$scrpt.PHP_EOL; 
include_once $scrpt; 
#
#-----------------------------------------------
#                        get the data to display 
#-----------------------------------------------
# 
#  measurments
$pm25   = 0;
$count  = count($parsed_json['results']); 
$value  = (float) $parsed_json['results'][0]['PM2_5Value'] ;
if ($count > 1)         // we only use 2 measurements
     {  $value = (float) $parsed_json['results'][1]['PM2_5Value'] ;}
if ($value > $pm25) {$pm25   = $value;} #echo __LINE__.' '.$pm25;
#
# echo __LINE__.$parsed_json['results'][0]['Stats']; exit; \"v5\":1.8393146177050788, // 24 hour average
$values = json_decode ( $parsed_json['results'][0]['Stats'],true ); # echo '<pre>'.print_r($value,true); exit;
$pm25_24= $values['v5'];
if ($count > 1)         // we only use 2 measurements
     {  $values = json_decode ( $parsed_json['results'][1]['Stats'] ,true);
        $value  = $values['v5'];}
if ($value > $pm25_24) {$pm25_24   = $value;} #echo __LINE__.' '.$pm25;

#
$forecastime    = (int) $parsed_json['results'][0]['LastSeen'];
$lat_lon        = '('.round($parsed_json['results'][0]['Lat'],2).','
                     .round($parsed_json['results'][0]['Lon'],2).')';
$time           = set_my_time($forecastime,true);
$sensor         = $parsed_json['results'][0]['ID'];
$city           = $parsed_json['results'][0]['Label'];
$aqi            = number_format(pm25_to_aqi($pm25),1 );    #echo __LINE__.' '.$aqi;
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') $pm25='.$pm25.' $aqi='.$aqi.PHP_EOL;    
$aqi24          = number_format(pm25_to_aqi($pm25_24),1 ); #echo __LINE__.' '.$aqi24;
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') $pm25_24='.$pm25_24.' $aqi24='.$aqi24.PHP_EOL;    

#
$now            = time();
$diff           = time() - $forecastime; 
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') $forecastime='.$forecastime.' $now='.$now.' $diff='.$diff.' '.PHP_EOL; 
#
if (filesize($fl_t_ld) < 1 || $diff > 3600)
	{ $online_txt   = '<b class="PWS_offline"> '.$online.lang('Offline').' </b>'; }
else    { $online_txt   = '<b class="PWS_online"> ' .$online.set_my_time_lng($forecastime,true).' </b>';}
#
# ------------------- define colors for this AQI
foreach ($AQ_levels as $n => $value) 
     {  if ($aqi > $value) { continue;}
        $icon   = $aq_icon[$n];
        $class  = 'dottedcircle'.$aq_class[$n];
        $color  = $b_color = $aq_color[$n];
        $text   = $aq_text[$n];
        break;}
# -------------------------------- assemble html
$left_txt = 
'<div class="PWS_left" style="height: 110px; "><!-- some facts -->
    <div class="PWS_div_left" style="height: 110px; margin: 0px 5px; font-size: 10px; ">
        <span  class="normal" ><b>'
        .$tr_station.':</b><br /><br />#'
        .$sensor.'<br /><br />'
        .$city.'<br />'.$lat_lon;
$left_txt .= '    </span>
</div>
</div>'.PHP_EOL;
#
$middle_text = 
'<div class="PWS_middle" style="height: 100px;"><!-- large icon -->
    <span class=""  style="font-size: 20px; line-height: 1.0;">'.$aqi.' AQI<br /></span>
    <div class="PWS_round" 
        style = "margin: 0 auto; margin-top: 5px;
                 width: 72px; 
                 height: 72px; 
                 background-color: '.$b_color.'; 
                 border: 0px solid silver;">
        <img src="./img/'.$icon.'" width="72" height="72" alt="Air quality: '.$text .' " title="Air quality: ' .$text.' " />
    </div>
</div>'.PHP_EOL;
#
$right_text     = 
'<div class="PWS_right" style="height: 110px;">
    <div class="PWS_div_right" style="height: 110px;  margin: 0px 5px; font-size: 10px; ">
        <span  class="normal" ><b>PM2.5</b>:
        <br />
        <b>'.lang('Current').'<br />'.$pm25.'</b><small> ug/m3</small><br /><br /></span>
        <span  class="normal" ><b> 1 '.lang('Day').' </b>:
        <br /><b>'.$aqi24.'</b> AQI <br />
        <b>PM2.5<br />'.$pm25_24.'</b><small> ug/m3</small><br /><br /></span>
    </div>
</div>'.PHP_EOL;
#
$bottom_text = '<div style="clear: both; width: 100%; font-size: 16px; padding-top: 5px;">'.lang($text).'</div>
<!-- eo weatheritem 2 -->'.PHP_EOL;
if (isset ($PWS_popup))   {  return; }  // are we in a popup 
#
echo '<div class="PWS_ol_time">'.$online_txt.'</div>'.PHP_EOL;
echo '<br />'.$left_txt.$middle_text.$right_text.$bottom_text;
#
#  print needed debug messages
if (isset ($_REQUEST['test']) ) { echo '<!-- '.PHP_EOL.$stck_lst.'-->'.PHP_EOL; $stck_lst='';}