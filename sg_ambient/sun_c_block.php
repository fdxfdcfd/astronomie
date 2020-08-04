<?php  $scrpt_vrsn_dt  = 'sun_c_block.php|00|2019-12-12|';  # release 1912
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
$clr_light      = '#e8c400'; # '#ff8841';
$clr_dark       = 'dimgrey';
$clr_sun_up     = '#e8c400'; # '#ecb454';
# ------------------------- translation of texts
$hrs_l  = ' '.lang('hrs ');
$min_l  = ' '.lang('min ');
$dayl_l = lang('Daylight');
$sunr_l = lang('Sunrise');
$suns_l = lang('Sunset');
$azim_l = lang('Azimuth');
$drkl_l = lang('Darkness');
$elv_l  = lang('Elevation');
#
$result         = date_sun_info(time(), $lat, $lon);  #echo '<pre>'.time().print_r($result,true);
$nextday        = time() + 24*60*60;
$result2        = date_sun_info($nextday,$lat, $lon); # echo '<pre>'.print_r($result2,true);
#
$light          = $result['sunset'] - $result['sunrise'];
$daylight       = gmdate('G',$light).$hrs_l.gmdate('i',$light).$min_l;
$dark           = 24*60*60 - $light;
$daydark        = gmdate('G',$dark). $hrs_l.gmdate('i',$dark). $min_l;
$nextrise       = $result['sunrise'];
$now            = time();
if ($now > $nextrise)
     {  $nextrise       = set_my_time($result2['sunrise'], true);  
        $nextrisetxt    = lang('Tomorrow');}
else {  $nextrisetxt    = lang('Today');
        $nextrise       = set_my_time($nextrise, true);} 
$nextset        = $result['sunset'];
if ($now > $nextset)
     {  $nextset        = set_my_time($result2['sunset'], true);
        $nextsettxt     = lang('Tomorrow');}
else {  $nextsettxt     = lang('Today');
        $nextset        = set_my_time($nextset, true);} 
$firstrise      =   $result['sunrise'];
$secondrise     =   $result2['sunrise'];    
$firstset       =   $result ['sunset']; 

if ($now < $firstrise) 
    {   $time   = $firstrise - $now;
        $hrs    = gmdate ('G',$time);
        $min    = gmdate ('i',$time);
        $txt    = lang('Till Sunrise');}
elseif ($now < $firstset)
    {   $time   = $firstset - $now;
        $hrs    = gmdate ('G',$time);
        $min    = gmdate ('i',$time);
        $txt    = lang('Of Daylight');}
else {  $time   = $secondrise - $now;
        $hrs    = gmdate ('G',$time);
        $min    = gmdate ('i',$time);
        $txt    = lang('Till Sunrise');}

function get_azimuth ()
    {   global $lat, $lon, $TZ,$azimuth,$elevation ;
        include './others/azimuth.php';
        $azimuth        = round($sunazi[25],2);
        $elevation      = round($sunpos[25],2); }
        
$azimuth=$elevation=0;
get_azimuth ();
#
# ---------------- the date time
echo '<div class="PWS_ol_time"><b class="PWS_online"> ' .$online.set_my_time_lng(time(),true).' </b></div>'.PHP_EOL;
#
# ------------- the block itself
echo '<div class="PWS_module_content"><br />'.PHP_EOL;
#
# ----------------   left column <div class="PWS_div_left" style="border-right-color: '.$clr.';">  height: 40px;
echo '<!-- left values -->
<div class="PWS_left">
<div class="PWS_div_left" style="border-right-color: '.$clr_light.';">'
        .$dayl_l.'<br /><b >'
        .$daylight.'</b>'
        .'</div>
<div class="PWS_div_left" style="border-right-color: '.$clr_sun_up.'; height: 40px;">'
        .$sunr_l.'<br /><b >'
        .$nextrise.'</b><br />'
        .$nextrisetxt
        .'</div>
<div class="PWS_div_left" style="border-right-width: 1px;">'
        .$azim_l.'<br /><b>'
        .$azimuth.'&deg;</b>'
        .'</div>'.PHP_EOL;     
echo '</div>
<!-- END of left values -->'.PHP_EOL;       
#
# ----------------  middle area
echo '<!-- middle part  -->
<div class="PWS_middle">
    <div style="width: 130px; margin: 0 auto;">
        <!-- middle texts -->
        <div class="narrow" style="position: absolute; margin: 30px 15px;">
            <b class="orange" >'.lang('Estimated').'</b>
            <br />
            <span class="large" > '.$hrs.'</span> '.$hrs_l.' <span class="large" >'.$min.'</span> '.$min_l.'
            <br /><b class="orange">'.$txt.'</b>
        </div>
        <canvas id="my_sun_Canvas" width="130" height="130" style="position: inherit; background: transparent; ">
        </canvas>
    </div>
</div>
<!-- END of middle part  -->'.PHP_EOL;
#
# ---------------- right column
echo '<!-- right values -->
<div class="PWS_right">'.PHP_EOL;
echo '<div class="PWS_div_right" >'
        .$drkl_l.'<br /><b >'
        .$daydark.'</b>'
        .'</div>
<div class="PWS_div_right" style="height: 40px; border-left-color: '.$clr_sun_up.';">'
        .$suns_l.'<br /><b >'
        .$nextset.'</b><br />'
        .$nextsettxt
        .'</div>
<div class="PWS_div_right" style="border-left-width: 1px;">'
        .$elv_l.'<br /><b >'
        .$elevation.'&deg;</b>'
        .'</div>
</div><!-- END of right values -->'.PHP_EOL;
#
# ----------------   end of PWS_module_content
echo '</div>'.PHP_EOL;
# ----------------   end of html

# ---------- generate the sun cricle
$d_crcl = 24*60/2;  #canvas circle = 2PI for 24 hours. 1PI => 12 hrs * 60 min
function clc_crcl ($integer)
     {  global $d_crcl ;
        $h      = (int) date ('H',$integer);
        $m      = (int) date ('i',$integer);
        $calc   = $m + $h*60;   ####  ADD check for 24 hours dark / light
        $calc   = (float) 0.5 + ($calc / $d_crcl );
        if ($calc > 2.0) { $calc = $calc - 2;}
        return round ($calc,5);}

$start  = clc_crcl ($result['sunrise']);
$end    = clc_crcl ($result['sunset']);
$pos    = clc_crcl ($now);
#
$sn_clr = $clr_light;
#if ($now > $result['sunset'] || $now < $result['sunrise'] ) {  $sn_clr = $clr_dark;}
echo '
<script>
var c = document.getElementById("my_sun_Canvas");
var ctx = c.getContext("2d");
ctx.fillStyle = "#FF8940";
ctx.textAlign = "center";
ctx.font = "8px Arial";
ctx.fillText("24", 63, 128);
ctx.textAlign = "right";
ctx.fillText("6",  6, 65);
ctx.textAlign = "left";
ctx.fillText("18",  120, 65);
ctx.textAlign = "center";
ctx.fillText("12", 63, 8);

ctx.beginPath();
ctx.arc(63, 65, 50, '.$start.' * Math.PI, '.$end.' * Math.PI);
ctx.lineWidth = 5;
ctx.lineCap = "round";
ctx.strokeStyle = "'.$sn_clr.'";
ctx.stroke();
ctx.beginPath();
ctx.arc(63, 65, 50, 0, 2 * Math.PI);
ctx.lineWidth = 2;
ctx.strokeStyle = "silver";
ctx.stroke();
ctx.beginPath();
ctx.arc(63, 65, 49, '.$pos.'* Math.PI, '.($pos+0.001).' * Math.PI);
ctx.lineWidth = 14;
ctx.lineCap = "round";
ctx.strokeStyle = "'.$clr_sun_up.'";
ctx.stroke();
</script> 
';
if (isset ($_REQUEST['test']) ) { echo '<!-- '.PHP_EOL.$stck_lst.'-->'.PHP_EOL; $stck_lst='';}

