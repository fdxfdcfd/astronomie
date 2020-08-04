<?php $scrpt_vrsn_dt  = 'AQ_popup.php|00|2020-03-25|';  # release 2004
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
header('Content-type: text/html; charset=UTF-8');
# -------------------save list of loaded scrips;
$stck_lst        = basename(__FILE__).' ('.__LINE__.') version =>'.$scrpt_vrsn_dt.PHP_EOL;       
#
# -------------load weatherdata and all settings 
$scrpt          = 'PWS_livedata.php'; 
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') include_once =>'.$scrpt.PHP_EOL;
include_once $scrpt; 
#-----------------------------------------------
#                                script settings
#-----------------------------------------------
$show_close_x   = $close_popup;  // set to false or true to overrde settings // 
#
# ------------------------- translation of texts
$ltxt_clsppp    = lang('Close');
$tr_feet        = lang('feet');
$tr_mt          = lang('mt');
$tr_airq        = lang('Air Quality');
$tr_updt        = lang('Updated');
$tr_station     = lang('Station ID');
#
$tr_aqi           = array();
$tr_aqi['green']  = lang('GoodAQ');
$tr_aqi['yellow'] = lang('ModerateAQ');
$tr_aqi['orange'] = lang('UnhealthyFSAQ');
$tr_aqi['red']    = lang('UnhealthyAQ');
$tr_aqi['purple'] = lang('VeryUnhealthyAQ');
$tr_aqi['maroon'] = lang('HazordousAQ');
$tr_aqi_pre     = lang('Precautions Required');
$tr_aqi_cri     = lang('Critical Conditions');
$tr_aqi_lif     = lang('Life Threatening');
$tr_aqi_credit  = lang('Air Quality data from our own sensor');
#
$arr_aq_scripts['AQ_luftdaten_c_block'] = lang('Our Luftdaten AQ sensor');
$arr_aq_scripts['AQ_purpleair_c_block'] = lang('Our PurpleAir sensor');
$arr_aq_scripts['AQ_gov_c_block']       = lang('Official AQ sensor station');
$arr_aq_scripts['AQ_station_block']   = lang('Our station AQ sensor');

#
$ltxt_hd1       = lang('Current cloudbase');
$ltxt_hd2       = lang('');
$ltxt_hd3       = lang('Information on Cloudbase');
$ltxt_hd4       = lang('Information on Air Quality');
#
$ltxt_inf1      = lang( 'Based on a common formula using the temperature & dewpoint, as recorded realtime from this weather station,'.
        ' an estimated height of the cloudbase is calculated.');       
$ltxt_inf2      = lang('It is not a accurate tool it is a relative indicator.');
#
# ---------       check  the sensor are we using
if (!isset($_REQUEST['script']))        
     {  $script = 'AQ_gov_c_block.php';} # when testing default
else {  $script = trim($_REQUEST['script']);}
list ($aqsensor, $none) = explode ('.php',$script); 
#
if (! array_key_exists ( $aqsensor, $arr_aq_scripts)) {die ('unsuccesfull load'); }
$text   = lang($arr_aq_scripts[$aqsensor]);
$script = $aqsensor.'.php';
#
# ---------  add sensor-name to translated texts
$ltxt_hd2       = $text.' '.$ltxt_hd2;
$ltxt_url       = $text.' &amp; '.$ltxt_hd1;
$str_fill       = str_repeat('&nbsp;',10);
#
$weather["cloudbase3"] = round((anyToC($weather["temp"]) - anyToC($weather["dewpoint"])) * 1000 /2.4444) ;
#
#-----------------------------------------------
#  now we load the correct AQ block script to
#  get the AQ values
#-----------------------------------------------
#
#  tell the blockscript to only gather the data
#  $PWS_popup set the script to "not display"
$PWS_popup      = true;
#  we know which script to load from $_REQUEST['script'] 
$scrpt          = $script; 
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') include_once =>'.$scrpt.PHP_EOL;
include_once $scrpt;           #  echo '<pre>'.print_r($aqiweather,true); exit;
#
# ------- set the colors to use from AQ_shared.php
$t_color                = '#000000';   
$b_clrs['maroon']       = $aq_class[5];
$b_clrs['purple']       = $aq_class[4];
$b_clrs['red']          = $aq_class[3];
$b_clrs['orange']       = $aq_class[2];
$b_clrs['yellow']       = $aq_class[1];
$b_clrs['green']        = $aq_color[0];
#$b_color        = $b_clrs[$color];
# ------------------    adjust size of some text
if (strlen($aqiweather['city']) > 6) 
     {  $city   = '<small>'.$aqiweather['city'].'</small>';}
else {  $city   = $aqiweather['city'].'<br />';}        #echo '<pre>'.print_r($aqiweather,true).$stck_lst; exit;
#
if (isset ($no_close_x) && $no_close_x == true) {$show_close_x = false;}
#
# now we generate the html of the popup
echo '<!DOCTYPE html>
<html lang="'.substr($user_lang,0,2).'">
<head>
    <meta charset="UTF-8">
    <title>'.$ltxt_url.'</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
'. my_style().'
</head>
<body class="dark">
    <div class="PWS_module_title" style="width: 100%; height: 20px; font-size: 14px; padding-top: 4px;" >'.PHP_EOL;
if ($show_close_x <> false ) // optional close text and large X
     { echo '    <span style="float: left;">&nbsp;X&nbsp;&nbsp;<small>'.$ltxt_clsppp.'</small></span>';}
echo '    <span style="color: #FF7C39;">'.$ltxt_url.'</span>'.PHP_EOL;
?>
    </div>
    <div class="PWS_weather_container"><!-- toprow -->
        <div class="PWS_weather_item" style="position: relative;"><!-- weatheritem 1 cloudbase -->
            <div class="PWS_module_title"><div class="title"><?php echo $ltxt_hd1; ?></div></div>
            <div class="PWS_left" style="font-size: 26px; width: 40%; padding-left: 10px;">
                <br /><?php echo $weather["cloudbase3"].' '.$tr_feet.
'                <br />'.round($weather["cloudbase3"]*0.3048,0).' '.$tr_mt; ?>
            </div>
            <div class="PWS_right" style="float: right; width: 48%; margin-right: 10px;">
                
                    <div class="bucket" style=""> 
                        <div class="clouds" style=" <?php 
$hght   = round( (10000 - $weather["cloudbase3"])/100);
if ($hght < 0) {$hght = 0;} 
$bottom = 100 - $hght; 
if ($bottom < 10) {$bottom = 10; $hght = 90;}
echo 'height: '.$hght.'px; bottom: '.$bottom.'px;';
?>"></div>
                    
                </div> 
            </div>
            <div style="width: 100%; clear: both; font-size: 12px;">
<?php echo '('.lang('Temperature').' &deg;C - '.lang('Dewpoint').' &deg;C )  * 1000 /2.4444 ';   ?></div>
        </div><!-- eo weatheritem cloudbase -->    

        <div class="PWS_weather_item"><!-- weatheritem 2  AQ -->
            <div class="PWS_module_title"><div class="title"><?php echo $ltxt_hd2; ?></div></div>
<?php echo  '<br /><br />'.$left_txt.$middle_text.$right_text.$bottom_text;  ?>
        </div><!-- eo weatheritem 2 -->

    </div><!-- eo toprow -->
    <div class="PWS_weather_container"><!-- second row -->
        <div class="PWS_weather_item " style="position: relative;"><!-- weatheritem 3 info -->
        <div class="PWS_module_title"><div class="title"><?php echo $ltxt_hd3; ?></div></div>
                <br />
                <p style="margin: 10px; text-align: left; font-size: 14px;"><?php 
                echo $ltxt_inf1.'<br /><br />'.$ltxt_inf2; ?>
                </p>
        </div><!-- eo weatheritem 3 -->
        <div class="PWS_weather_item " style="position: relative;"><!-- weatheritem  4 info -->
        <div class="PWS_module_title"><div class="title"><?php echo $ltxt_hd4; ?></div></div>
                <ul style="text-align: left; font-size: 14px;">
                <li class="green"><span  style="display: inline-block; width: 40px;">0-50</span><span><?php   echo $tr_aqi['green']; ?></span></li>
                <li class="yellow"><span style="display: inline-block; width: 40px;">50+</span><span><?php    echo $tr_aqi['yellow']; ?></span></li>
                <li class="orange"><span style="display: inline-block; width: 40px;">100+</span><span><?php   echo $tr_aqi['orange']; ?></span></li>
                <li class="red"><span    style="display: inline-block; width: 40px;">150+</span><span><?php   echo $tr_aqi['red']    .',</span><span style="color: white;"><br /> '.$str_fill.$tr_aqi_pre; ?></span></li>
                <li class="purple"><span style="display: inline-block; width: 40px;">200+</span><span><?php   echo $tr_aqi['purple'] .',</span><span style="color: white;"><br /> '.$str_fill.$tr_aqi_cri; ?></span></li>
                <li class="maroon"><span style="display: inline-block; width: 40px;">300+</span><span><?php   echo $tr_aqi['maroon'] .',</span><span style="color: white;"><br /> '.$str_fill.$tr_aqi_lif; ?></span></li>         
                </ul>
        </div><!-- eo weatheritem details sun --> 
    </div><!-- eo second row -->
<?php if (isset ($_REQUEST['test']) ) { echo '<!-- '.PHP_EOL.$stck_lst.'-->';}  ?> 
</body>
</html>
<?php
function my_style()
     {  global  $b_clrs;
        global  $popup_css ;
        $return         = PHP_EOL.'    <style>'.PHP_EOL;
# load the genral css for pop-ups
        if (isset ($popup_css) && $popup_css <> false)  
             {  $return .= file_get_contents ($popup_css);}
             # add pop-up specific css
        $return .= '
        .orange      { color: '.$b_clrs['orange'].';}
        .green       { color: '.$b_clrs['green'].';}
        .blue        { color: #01a4b4;}
        .yellow      { color: '.$b_clrs['yellow'].';}
        .red         { color: '.$b_clrs['red'].';}
        .purple      { color: '.$b_clrs['purple'].';}
        .maroon      { color: '.$b_clrs['maroon'].';}
        .PWS_weather_item {font-size: 12px;}
        .large       {  font-size: 20px; }
        .bucket      {  background:url(img/rain/cloudmarker.png); 
                        width:100px; height:100px;
                        border:        5px solid #393D40;
                        border-top:    none; 
                        border-bottom: none;
                        position:absolute; bottom: 40px;}
        .clouds       { background:rgba(159, 163, 166, 0.4);  
                        width: 100%;  
                        border:none;
                        border-bottom:1px dotted rgb(255, 124, 57);}
        ';
        $return         .= '    </style>'.PHP_EOL;
        return $return;

 }
