<?php   $scrpt_vrsn_dt  = 'temp_c_block.php|00|2020-01-15|';  # check min-temp | release 1912
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
# -------------load weatherdata and all settings 
$scrpt          = 'PWS_livedata.php'; 
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') include_once =>'.$scrpt.PHP_EOL;
include_once $scrpt; 
#-----------------------------------------------
#                                script settings
#-----------------------------------------------
#
# ------------------------- translation of texts
$heat_l = lang('Heatindex');
$fls_l  = lang('Feels like');
$chll_l = lang('Windchill');
$wb_l   = lang('Wet Bulb');
$hum_l  = lang('Humidity');
$dwp_l  = lang('Dewpoint');
$c_l    = lang('Celsius');
$f_l    = lang('Fahrenheit');
$ind_l  = lang('Indoor');
#
$temp_colors = array(
        '#F6AAB1', '#F6A7B6', '#F6A5BB', '#F6A2C1', '#F6A0C7', '#F79ECD', '#F79BD4', '#F799DB', '#F796E2', '#F794EA', 
        '#F792F3', '#F38FF7', '#EA8DF7', '#E08AF8', '#D688F8', '#CC86F8', '#C183F8', '#B681F8', '#AA7EF8', '#9E7CF8', 
        '#9179F8', '#8477F9', '#7775F9', '#727BF9', '#7085F9', '#6D8FF9', '#6B99F9', '#68A4F9', '#66AFF9', '#64BBFA', 
        '#61C7FA', '#5FD3FA', '#5CE0FA', '#5AEEFA', '#57FAF9', '#55FAEB', '#52FADC', '#50FBCD', '#4DFBBE', '#4BFBAE', 
        '#48FB9E', '#46FB8D', '#43FB7C', '#41FB6A', '#3EFB58', '#3CFC46', '#40FC39', '#4FFC37', '#5DFC35', '#6DFC32', 
        '#7DFC30', '#8DFC2D', '#9DFC2A', '#AEFD28', '#C0FD25', '#D2FD23', '#E4FD20', '#F7FD1E', '#FDF01B', '#FDDC19', 
        '#FDC816', '#FDC816', '#FEB414', '#FEB414', '#FE9F11', '#FE9F11', '#FE890F', '#FE890F', '#FE730C', '#FE730C', 
        '#FE5D0A', '#FE5D0A', '#FE4607', '#FE4607', '#FE2F05', '#FE2F05', '#FE1802', '#FE1802', '#FF0000', '#FF0000',);
$maxTemp        = count($temp_colors) - 1;
#-----------------------------------------------
#                                      functions
#-----------------------------------------------
#                 temp_in_c
if (!function_exists ('temp_in_c') ){
function temp_in_c ($value)
     {  global $tempunit;
        $return = (float) ($value);
        if ($tempunit <> 'C') 
             {  $return  = round (5*($return -32)/9);}
        else {  $return  = round ($return);}
        return $return; }
}
#                 temp_value
if (!function_exists ('temp_value') ) {
function temp_value ($in_C, $value, $text)
     {  global $maxTemp, $temp_colors;
        if ($value === 'n/a' || $value === false) 
            {   return '<!-- no value '.$value.' -->'.PHP_EOL; return;}
        $n      = 32 + $in_C;
        if ($n < 0) {$n=0;}
        if ($n > $maxTemp)      
             {  $color  = $temp_colors[$maxTemp];}
        else {  $color  = $temp_colors[$n];}
        return '<div class="PWS_div_right" style="border-left-color: '.$color.';">'
        .$text.'<b><br />'.$value.'&deg;</b>'
        .'</div>'.PHP_EOL;}
}
#                 temp_nr
if (!function_exists ('tempnr') ){
function tempnr ($value)
     {  global $dec_tmp;
        return number_format ($value,$dec_tmp);}
}
#
# -------------test values
#$weather['heat_index'] = 32.4;
#$weather['windchill']  = 3.5;
#$weather['temp']  = 20;
#$weather['temp_high']  = 28;
#$weather['temp_low']  = 16;
# -------------test values
#
if (array_key_exists ('temp_high', $weather)) 
     {  $max_temp       = ceil  ($weather['temp_high'] + 5);
        $min_temp       = floor ($weather['temp_low']  - 5);
        $max_min_txt    = tempnr($weather['temp_high']).'&deg; | ' .tempnr($weather['temp_low']).'&deg;';}
else {  $max_min_txt    = '';
        $max_temp       = ceil   (10 + $weather['temp']);
        $min_temp       = floor  ($weather['temp']) - 10;}
# --------------------------------------
# ---------------          generate html
#
# ---------------- the date time
echo '<div class="PWS_ol_time">'.$online_txt_ld.'</div>'.PHP_EOL;
#
# ------------- the block itself
echo '<div class="PWS_module_content"><br />'.PHP_EOL;
#
# ----------------   left column
echo '<!-- left values -->
<div class="PWS_left">'.PHP_EOL;
# 
# ----------  temp in other unit
#
$temp   = $weather['temp'];
$in_C   = temp_in_c ($temp);
if ($tempunit == 'C')
     {  $o_unit = 'F';
        $u_txt  = $f_l;}
else {  $o_unit = 'C';
        $u_txt  = $c_l;}
$o_temp = convert_temp ($temp,$tempunit,$o_unit,1);
$n      = 32 + $in_C;
if ($n < 0) {$n=0;}
if ($n > $maxTemp)      
     {  $color  = $temp_colors[$maxTemp];}
else {  $color  = $temp_colors[$n];}
echo '<div class="PWS_div_left" style="border-right-color: '.$color.';">'
        .$u_txt.'<b><br />'.tempnr($o_temp) .'&deg;</b>'
        .'</div>'.PHP_EOL;
#
# ------------------ inside temp
#
if (isset ($show_indoor) && $show_indoor <> false && (string) $weather['temp_indoor'] <> 'n/a')
     {  $temp   = (float)$weather['temp_indoor'];
        $in_C   = temp_in_c ($temp);
        $n      = 32 + $in_C;
        if ($n < 0) {$n=0;}
        if ($n > $maxTemp)      
             {  $color  = $temp_colors[$maxTemp];}
        else {  $color  = $temp_colors[$n];}
echo '<div class="PWS_div_left" style="border-right-color: '.$color.';">'
        .$ind_l.'<b><br />'.tempnr($temp) .'&deg;</b>'
        .'</div>'.PHP_EOL;}
#
# --------------------  Humidity
$hum    = (int) $weather["humidity"];
if     ($hum  > 80) { $clr = 'orange';}
elseif ($hum  > 60) { $clr = 'green';}
else                { $clr = 'yellow';}
if(array_key_exists("humidity_trend",$weather))
     {  if     ( $weather["humidity_trend"] > 0) { $arrow       = '&uarr;';}
        elseif ( $weather["humidity_trend"] < 0) { $arrow       = '&darr;';}
        else                                     { $arrow       = '';}
}
echo '<div class="PWS_div_left" style="border-right-color: '.$color.';">'
        .$hum_l.'<b><br />'.$hum.'% '.$arrow.'</b>'
        .'</div>'.PHP_EOL;
#      
echo '</div>
<!-- END of left values -->'.PHP_EOL;       
#
# ----------------  middle area
echo '<!-- middle part  -->
<div class="PWS_middle">'.PHP_EOL;
$in_C   = temp_in_c ($max_temp);
$n      = 32 + $in_C;
if ($n < 0) {$n=0;}
if ($n > $maxTemp)      
     {  $color1 = $temp_colors[$maxTemp];}
else {  $color1 = $temp_colors[$n];}
$in_C   = temp_in_c ($min_temp);
$n      = 32 + $in_C;
if ($n < 0) {$n=0;}
if ($n > $maxTemp)      
     {  $color2 = $temp_colors[$maxTemp];}
else {  $color2 = $temp_colors[$n];}
$arrow  = '';
if (array_key_exists ('temp_trend', $weather) && $weather["temp_trend"] <> 'n/a') 
     {  if     ( $weather["temp_trend"] > 0) { $arrow       = '&uarr;';}
        elseif ( $weather["temp_trend"] < 0) { $arrow       = '&darr;';}
        else                                 { $arrow       = '&harr;';}
        }
#
echo '    <div class="PWS_round" style= "margin: 0 auto; margin-top: 10px; margin-bottom: 10px; height: 104px; width: 104px; 
        overflow: hidden; text-align: center; background: linear-gradient(90deg, '.$color1.', '.$color2.');  color: black;">
        <br /><br /><b style="font-size: 14px; ">'.$max_min_txt.'</b>
        <br /><b style="font-size: 28px;">'. tempnr($weather['temp']).'&deg;</b>';
if ($weather["temp_trend"] == '' || $weather["temp_trend"] == 'n/a')
     {  echo '';}
else {  echo '
        <br /><b style="font-size: 14px; ">'.tempnr($weather["temp_trend"]).' '.$arrow.'</b>';}
echo '
    </div>'.PHP_EOL;
echo '</div>
<!-- END of middle part  -->'.PHP_EOL;
#
# ---------------- right column
echo '<!-- right values -->
<div class="PWS_right">'.PHP_EOL;
#
# ------  feels/ chill type temps
$heat   = temp_in_c ($weather['heat_index']);
$tmp    = temp_in_c ($weather['temp']);       
$feel   = temp_in_c ($weather['temp_feel']);
$chill  = temp_in_c ($weather['windchill']);

if ( $heat > $notifyHeatIndex  
|| ( $showFeelsLike == true  && $tmp > 27) )
     {  if ($tmp < $feel )
             {  echo temp_value ($heat, $weather['heat_index'], $heat_l);}
        else {  echo temp_value ($tmp,  $weather['temp']      , $heat_l);}
        }
#
elseif ($weather['windchill'] === 'n/a' || $weather['windchill'] === '--' )
     {  echo temp_value ($feel,  tempnr($weather['temp_feel'])      , $fls_l);}
#
elseif ($chill < 5)
     {  echo temp_value ($chill,  tempnr($weather['windchill'])      , $chll_l);}
#
elseif ($showFeelsLike)
     {  echo temp_value ($feel,  tempnr($weather['temp_feel'])      , $fls_l);}
#
# -------- wetbulb
$Tc     = $tmp;
$P      = convert_baro ($weather['barometer'],$pressureunit,'hPa');
$RH     = $weather['humidity'];
#
$Tdc = (($Tc - (14.55 + 0.114 * $Tc) * (1 - (0.01 * $RH)) - pow((2.5 + 0.007 * $Tc) * (1 - (0.01 * $RH)) , 3) - (15.9 + 0.117 * $Tc) * pow(1 - (0.01 * $RH),  14)));
$E = (6.11 * pow(10 , (7.5 * $Tdc / (237.7 + $Tdc))));
$wetbulbcalc = (((0.00066 * $P) * $Tc) + ((4098 * $E) / pow(($Tdc + 237.7) , 2) * $Tdc)) / ((0.00066 * $P) + (4098 * $E) / pow(($Tdc + 237.7) , 2));
$wetbulbx       = number_format($wetbulbcalc,1);
$wet            = (float) $wetbulbx;
if ($tempunit <> 'C') 
     {  $wetbulbx = convert_temp ($wetbulbx,'C',$tempunit,1);}
echo temp_value ($wet,  tempnr($wetbulbx)      , $wb_l);
#
# --------  Dewpoint
$dewp   = temp_in_c ($weather['dewpoint']);
echo temp_value ($dewp,  tempnr($weather['dewpoint']) , $dwp_l);
echo '<!-- END of right values -->'.PHP_EOL;
#
# ----------------   end of PWS_module_content
echo '</div>'.PHP_EOL;
# ----------------   end of html
echo '</div>'.PHP_EOL;
#
if (isset ($_REQUEST['test']) ) { echo '<!-- '.PHP_EOL.$stck_lst.'-->'.PHP_EOL; $stck_lst='';}


