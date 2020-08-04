<?php $scrpt_vrsn_dt  = 'AQ_station_block.php|00|2020-04-13|';  # beta for release 2004
#
# Settings:
$first_pm_sensor        = 1;  // 1 to 4 sensors can be avialable
$first_description      = 'Outside';
#
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
#
# -------------load weatherdata and all settings 
$scrpt          = 'PWS_livedata.php'; 
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') include_once =>'.$scrpt.PHP_EOL;
include_once $scrpt; 
#
#-----------------------------------------------
#     check if data is usable
#-----------------------------------------------
$dataFALSE              = '';
if (! array_key_exists ('pm25_crnt'.$first_pm_sensor, $weather) ) 
     {  $dataFALSE = __LINE__.': No PM sensor number '.$first_pm_sensor.'  found'; }
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
#                         gather data to be used 
#-----------------------------------------------
# 
$aq_unix        = $weather['datetime'];
$aq_time        = set_my_time($aq_unix,true);
$aq_frst        = '#'.$first_pm_sensor.'<br />'.lang($first_description); 
$aq_frst_pm     = $weather['pm25_crnt'.$first_pm_sensor]; 
$aq_frst_pmAvg  = false;  
if (array_key_exists ('pm25_24avg'.$first_pm_sensor, $weather) ) 
     {  $aq_frst_pmAvg  = $weather['pm25_24avg'.$first_pm_sensor];
        $aq_avg         = true;}
else {  $aq_avg         = false;}
#
$aq_frst_aqi    = number_format(pm25_to_aqi($aq_frst_pm),1 );
if ($aq_avg == true)
     {  $aq_frst_aqiAvg = number_format(pm25_to_aqi($aq_frst_pmAvg),1 );}
#
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') $aq_frst_pm='.$aq_frst_pm.' $aq_frst_aqi='.$aq_frst_aqi.PHP_EOL;    
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') $aq_frst_pmAvg='.$aq_frst_pmAvg.' $aq_frst_aqiAvg='.$aq_frst_aqiAvg.PHP_EOL;    
#
$now            = time();
$diff           = $now - $aq_unix; 
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') $weather["datetime"]='.$weather['datetime'].' $now='.$now.' $diff='.$diff.' '.PHP_EOL; 
#
# ------------------- define colors a.s.o. for an AQI
$arr    = aq_set_clrs ($aq_frst_aqi); 
# -------------------------------- assemble html
$left_txt = 
'<div class="PWS_left" style="height: 110px; ">
    <div class="PWS_div_left" style="height: 110px; margin: 0px 5px; font-size: 10px; ">
        <span  class="normal" ><b>'
        .$tr_station.':</b><br /><br />'
        .$aq_frst.'<br /><br />
        </span>
</div>
</div>'.PHP_EOL;
#
$middle_text = 
'<div class="PWS_middle" style="height: 100px;"><!-- large icon -->
    <span class=""  style="font-size: 20px; line-height: 1.0;">'.$aq_frst_aqi.' AQI<br /></span>
    <div class="PWS_round" 
        style = "margin: 0 auto; margin-top: 5px;
                 width: 72px; 
                 height: 72px; 
                 background-color: '.$b_color.'; 
                 border: 0px solid silver;">
        <img src="./img/'.$arr['icon'].'" width="72" height="72" alt="Air quality: '.$arr['text'] .' " title="Air quality: ' .$arr['text'].' " />
    </div>
</div>'.PHP_EOL;
#
$right_text     = 
'<div class="PWS_right" style="height: 110px;">
    <div class="PWS_div_right" style="height: 110px;  margin: 0px 5px; font-size: 10px; ">
        <span  class="normal" ><b>PM2.5</b>:<br />'.PHP_EOL;
#        
$right_text    .= '        <b>'.lang('Current') .'<br />'                                     .$aq_frst_pm   .'</b><small> ug/m3</small><br /><br />'.PHP_EOL;  
if ($aq_avg == true)
     {  $right_text    .= '        <b>'.lang('24h Avg') .'<br />' .$aq_frst_aqiAvg.'</b> AQI<br /><b>'.$aq_frst_pmAvg.'</b><small> ug/m3</small>'.PHP_EOL;}
$right_text    .= '        </span>
    </div>
</div>'.PHP_EOL;

$bottom_text = '<div style="clear: both; width: 100%; font-size: 16px; padding-top: 5px;">'.lang($arr['text']).'</div>
<!-- eo weatheritem 2 -->'.PHP_EOL;

if (isset ($PWS_popup))   {  return; }  // are we in a popup 
#
# ------------            date time of last data
echo '<div class="PWS_ol_time">'.$online_txt_ld.'</div>'.PHP_EOL;
echo '<br />'.$left_txt.$middle_text.$right_text.$bottom_text;
#
#  print needed debug messages
if (isset ($_REQUEST['test']) ) { echo '<!-- '.PHP_EOL.$stck_lst.'-->'.PHP_EOL; $stck_lst='';}