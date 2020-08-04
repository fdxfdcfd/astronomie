<?php  $scrpt_vrsn_dt  = 'baro_c_block.php|00|2020-03-23|';  # release 2004
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
$baro_max_m     = 1050; // metric hPa max/min 
$baro_min_m     = 950;
$baro_max_e     = 31;   // imperial/english inHg
$baro_min_e     = 28;
#
# ------------------------- translation of texts
$rise_l = lang('Rising') .' &uarr;';
$fall_l = lang('Falling').' &darr;'; 
$stdy_l = lang('Steady') .' &harr;';
$min_l  = lang('Min');
$max_l  = lang('Max');
$curt_l = lang('Current');
#
# -------------------------------- marker colors
$cbr_hgh_clr    =  '#d65b4a';  // marker high
$cbr_lw_clr     =  '#01a4b4';   // marker low
$cbr_nw_clr     =  '#9aba2f';   // marker current
#
# ----------- test values
#$weather["temp_units"]          = 'C';   $weather["temp_units"]          = 'F'; 
#$weather["barometer"]           = 1000;  $weather["barometer"]           = 29.5;
#$weather["barometer_min"]       = 950;   $weather["barometer_min"]       = 28;
#$weather["barometer_max"]       = 1050;  $weather["barometer_max"]       = 31;
#$weather['barometer_trend'] = 0;
# --------EO test values
#
$baro_high      = number_format($weather["barometer_max"],$dec_baro,'.','');
$baro_low       = number_format($weather["barometer_min"],$dec_baro,'.','');
$baro_act       = number_format($weather["barometer"],$dec_baro,'.','');
$baro_unit      = lang($weather['barometer_units']);
if ($weather["temp_units"]=='C')
     {  $baro_other     = number_format($baro_act *0.029529983071445,$dec_baro+1,'.','');
        $baro_other_u   = lang('inHg');}
else {  $baro_other     = number_format($baro_act *33.863886666667,$dec_baro-1,'.','');
        $baro_other_u   = lang('hPa');}
#
$trnd_clr       = $cbr_nw_clr;
$trnd_txt       = $trnd_num     ='';
$trnd_num       = '<!-- '.$weather['barometer_trend'].' -->';
if ( (string) $weather['barometer_trend'] <> 'n/a' )  // 'n/a'  and 0 are sometimes equal
     {  $trnd   = number_format((float)$weather['barometer_trend'],$dec_baro+1); 
        if     ($trnd > 20)     { $trnd = 0;}
        if ($trnd > 0 )         { $trnd_clr     =   $cbr_hgh_clr; $trnd_txt =  $rise_l;}
        elseif ($trnd < 0)      { $trnd_clr     =   $cbr_lw_clr;  $trnd_txt =  $fall_l;}
        else                    { $trnd_clr     =   $cbr_nw_clr;  $trnd_txt =  $stdy_l;}
        $trnd_num       = $trnd.' '.$baro_unit;}
if (isset ($weather['barometer_trend_text']) && (string) $weather['barometer_trend_text'] <> 'n/a')
     {  $trnd_txt       = lang($weather['barometer_trend_text']);
	$string         = ' '.strtolower($weather['barometer_trend_text']);
        if     (strpos ($string,'rising') > 0)  {$trnd_clr     =   $cbr_hgh_clr;}
        elseif (strpos ($string,'falling') > 0) {$trnd_clr     =   $cbr_lw_clr;}}
$trnd_txt       .=  '<br />'.$trnd_num;
#
#-----------------------------------------------
#                                  generate html
#-----------------------------------------------
#
# ------------            date time of last data
echo '<div class="PWS_ol_time">'.$online_txt_ld.'</div>'.PHP_EOL;
#
# -------------                 the block itself
echo '<div class="PWS_module_content"><br />'.PHP_EOL;
#
# ----------------                   left column
echo '<!-- left values -->
<div class="PWS_left">
<!-- lowest value -->
<div class="PWS_div_left" style="border-right-color: '.$cbr_lw_clr.';">'
        .$min_l.'<br /><b >'
        .$baro_low.'&nbsp;'.$baro_unit.'</b>'
        .'</div>'.PHP_EOL;
echo '<!-- other unit block -->
<div class="PWS_div_left" style="border-right-color: '.$cbr_nw_clr.';">'
        .$curt_l.'<br /><b >'
        .$baro_other.'&nbsp;'.$baro_other_u.'</b>'
        .'</div>'.PHP_EOL;
echo '</div>
<!-- END of left values -->'.PHP_EOL;       
#
# ----------------                   middle area
echo '<!-- middle part  -->
<div class="PWS_middle">'.PHP_EOL;
echo '     <div style="width: 130px; margin: 0 auto;"> 
<!-- middle texts -->
        <div class=" narrow" style="position: absolute; margin: 50px 15px;">
          <span class="large" >'.$baro_act.'</span>
        </div> 
    <canvas id="my_baro_Canvas" width="130" height="130" style="position: inherit; background: transparent; margin-top: 0px;">
    </canvas>
   </div>'.PHP_EOL;
echo '</div>
<!-- END of middle part  -->'.PHP_EOL;
#
# ----------------                  right column
echo '<!-- right values -->
<div class="PWS_right">'.PHP_EOL;
echo '<!-- lowest value -->
<div class="PWS_div_right" style="border-left-color: '.$cbr_hgh_clr.';"><!-- max value -->'
        .$max_l.'<br /><b >'
        .$baro_high.'&nbsp;'.$baro_unit.'</b>'
        .'</div>'.PHP_EOL;
if ( $weather['barometer_trend'] === 'n/a' && (string) $weather['barometer_trend_text'] === 'n/a')
     {  echo '<div class="PWS_div_right" style="border-color: transparent;"></div>'.PHP_EOL;}
else {  echo '<div class="PWS_div_right" style="border-left-color: '.$trnd_clr.';"><!-- trend -->'
        .'<b>'
        .$trnd_txt.'</b>'
        .'</div>'.PHP_EOL;}
echo '</div><!-- END of right values -->'.PHP_EOL; 
#
# ----------------   end of PWS_module_content
echo '</div>'.PHP_EOL;
# ----------------   end of html
#
if (isset ($_REQUEST['test']) ) { echo '<!-- '.PHP_EOL.$stck_lst.'-->'.PHP_EOL; $stck_lst='';}
# ---------- generate the sun cricle
# constants
$c_wdth         = 130;
$c_cntr         = $c_wdth/2; 
$c_dmtr         = 54;           // diameter
$c_clr          = 'silver';  

$cbr_ttl        = 8;            // nr of segments
$cbr_lgth       = 2 / $cbr_ttl;
$cbr_strt       = 3 * $cbr_lgth;// first segment start
$cbr_end        = 9 * $cbr_lgth;
# ------------------------------  min/max scale
if ($weather["temp_units"] == 'C')
     {  $baro_max = $baro_max_m;
        $baro_min = $baro_min_m;
        $br_dg_a  = ($baro_act  - $baro_min) *2.7 - 225;   #  * 0.02953 * 50.6; 
        $br_dg_l  = ($baro_low  - $baro_min) *2.7 - 225;  #  * 0.02953 * 50.6;
        $br_dg_h  = ($baro_high - $baro_min) *2.7 - 225; } #  * 0.02953 * 50.6; }
else {  $baro_max = $baro_max_e;
        $baro_min = $baro_min_e;
        $br_dg_a  = ($baro_act  - $baro_min)  *33 *2.7 - 225;  #    * 50.6;  
        $br_dg_l  = ($baro_low  - $baro_min)  *33 *2.7 - 225;  #    * 50.6;
        $br_dg_h  = ($baro_high - $baro_min)  *33 *2.7 - 225; } #   * 50.6; }

#echo '$br_dg_a='.$br_dg_a.'$br_dg_l='.$br_dg_l.'$br_dg_h='.$br_dg_h;
#
echo '<script>
var c = document.getElementById("my_baro_Canvas");
var ctx = c.getContext("2d");
ctx.textAlign = "left";       // low
ctx.fillStyle = "'.$cbr_lw_clr.'";
ctx.fillText("'.$baro_min.'", 25,112);
ctx.textAlign = "right";       // high
ctx.fillStyle = "'.$cbr_hgh_clr.'";
ctx.fillText("'.$baro_max.'", 105,112);
ctx.beginPath();                // arc
ctx.arc('.$c_cntr.', '.$c_cntr.', '.$c_dmtr.', '.$cbr_strt.' * Math.PI, '.$cbr_end.' * Math.PI);
ctx.lineWidth = 3;
ctx.strokeStyle = "'.$c_clr .'";
ctx.stroke();
ctx.save();                     // arrow hgh
ctx.translate('.$c_cntr.','.$c_cntr.');
ctx.rotate('.$br_dg_h.' * Math.PI / 180)
ctx.translate(-('.$c_cntr.'),-('.$c_cntr.'));
ctx.beginPath();
ctx.fillStyle = "'.$cbr_hgh_clr.'";
ctx.moveTo(119, 60);
ctx.lineTo(119, 66);
ctx.lineTo(126, 63);
ctx.fill(); 
ctx.textAlign = "right";
ctx.fillText(" - -", 112,66);
ctx.restore();
ctx.save();                     // arrow low
ctx.translate('.$c_cntr.','.$c_cntr.');
ctx.rotate('.$br_dg_l.' * Math.PI / 180)
ctx.translate(-('.$c_cntr.'),-('.$c_cntr.'));
ctx.beginPath();
ctx.fillStyle = "'.$cbr_lw_clr.'";
ctx.moveTo(119, 60);
ctx.lineTo(119, 66);
ctx.lineTo(126, 63);
ctx.fill(); 
ctx.textAlign = "right";
ctx.fillText("- - ", 110,66);
ctx.restore();
ctx.save();                     // arrow 2
ctx.translate('.$c_cntr.','.$c_cntr.');
ctx.rotate('.$br_dg_a.' * Math.PI / 180)
ctx.translate(-('.$c_cntr.'),-('.$c_cntr.'));
ctx.beginPath();
ctx.fillStyle = "'.$cbr_nw_clr.'";
ctx.fill(); 
ctx.textAlign = "right";
ctx.fillText("- - ", 110,66);
ctx.moveTo(108,63);
ctx.lineTo(118,68);
ctx.lineTo(118,58);
ctx.fill();
ctx.restore();
</script> 
';
