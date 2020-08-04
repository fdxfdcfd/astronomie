<?php  $scrpt_vrsn_dt  = 'moon_c_block.php|00|2019-12-12|';  # release 1912
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
#
# ----------------------- general meteors script
$scrpt          = 'meteors_shared.php'; 
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') include_once =>'.$scrpt.PHP_EOL;
include_once $scrpt; 
if ($meteor_link_blck <> '') {$meteor_default  = $meteor_link_blck;}
#
#-----------------------------------------------
#                                script settings
#-----------------------------------------------
$clr_light      = '#ff8841';
$clr_dark       = 'dimgrey';
$clr_sun_up     = '#e8c400'; #'#f6ff00';
$now            = time();
$date           = date ('Y-m-d');
#
# ------------------------- translation of texts
$ltxt_tmrrw     = lang('Tomorrow');
$ltxt_td        = lang('Today');
$ltxt_mnrs      = lang('Moonrise');
$ltxt_mnst      = lang('Moonset');
$ltxt_nxtfull   = lang('Next Full Moon');
$ltxt_nxtnew    = lang('Next New Moon');
$ltxt_luninance = lang('Luminance'); 
#-----------------------------------------------
#                                    date format
#-----------------------------------------------
if ($clockformat == '24') 
     {  $date_time_frmt = 'D j M';}
else {  $date_time_frmt = 'D M j';}
#-----------------------------------------------
#                                      functions
#-----------------------------------------------
#               PWS_moon_info
#-----------------------------------------------
if  (function_exists ('PWS_moon_info') ) {echo 'Moon info is already displayed'; return;}
if (!function_exists ('PWS_moon_info') ) {
function PWS_moon_info ()
     {  global $lat,$lon;
        include './others/MoonPhase.php';
        include './others/moon.php';
        $now    = time();
        list ($month, $day,$year) = explode ('|', date ('n|j|Y', $now));
        $object = (array) Moon::calculateMoonTimes($month, $day, $year, $lat, $lon);
        $result['moonrise']     = $object['moonrise'];
        $result['moonset']      = $object['moonset'];
        if (date ('Gis',$result['moonrise']) == '000000') {$result['moonrise'] = 0;}
        if (date ('Gis',$result['moonset']) == '000000')  {$result['moonset'] = 0;}
        
#
        $tomorrow       = $now + 24*3600;
        list ($month, $day,$year) = explode ('|', date ('n|j|Y',$tomorrow));  
        $object = (array) Moon::calculateMoonTimes($month, $day, $year, $lat, $lon);
        $result['moonrise2']    = $object['moonrise'];
        $result['moonset2']     = $object['moonset'];
        if (date ('Gis',$result['moonrise2']) == '000000') {$result['moonrise2'] = 0;}
        if (date ('Gis',$result['moonset2']) == '000000')  {$result['moonset2'] = 0;}
        
        $result['now']          = time();      
        return $result;}
}
#-----------------------------------------------
#               trans_long_date
#-----------------------------------------------
if (!function_exists ('trans_long_date') ) {
function trans_long_date ($date)
     {  $from   = array ( 
                'Apr ','Aug ','Dec ','Feb ','Jan ','Jul ','Jun ','Mar ','May ','Nov ','Oct ','Sep ',
                'April','August','December','February','January','July','June','March','May','November','October','September',
                'Mon ','Tue ','Wed ','Thu ','Fri ','Sat ','Sun ',
                'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
        foreach ($from  as $txt) {$to_dates[] = lang($txt).' ';} # echo '-'.$txt.'-'.lang($txt).PHP_EOL;

        return str_replace ($from, $to_dates, $date);} 
}      
#
$arr    = PWS_moon_info ();
#
#echo '<!-- '.PHP_EOL.print_r ($arr,true).' -->'.PHP_EOL; 
#
$moonrise       = $arr['moonrise'];
$moonrise_nxt   = ''; #'<br />'.$ltxt_td;
if ($moonrise < $now) 
     {  $moonrise       = $arr['moonrise2']; 
        $moonrise_nxt   = '<br />'.$ltxt_tmrrw;}
$moonrise_time  = set_my_time($moonrise , true);
#
$moonset        = $arr['moonset'];
$moonset_nxt    = '<br />'.$ltxt_td;
if ($moonset < $now) 
     {  $moonset        = $arr['moonset2']; 
        $moonset_nxt    = '<br />'.$ltxt_tmrrw;}
$moonset_time   = set_my_time($moonset , true );
#
$fullmoon       = $arr['full_moon'] ;
$fullmoon_date  = date ('Y-m-d',$fullmoon);
if ($date > $fullmoon_date) 
     {  $fullmoon       = $arr['next_full_moon'];}
$fullmoon_time  = trans_long_date(date($date_time_frmt,$fullmoon));  #date($dateFormat,$fullmoon );
if ($date == $fullmoon_date) 
     { $fullmoon_time   = $ltxt_td;}

$newmoon       = $arr['new_moon'] ;
$newmoon_date   = date ('Y-m-d',$newmoon);
if ($now > $arr['new_moon']) 
     {  $newmoon       = $arr['next_new_moon'];}
$newmoon_time  = trans_long_date(date($date_time_frmt,$newmoon)); # date($dateFormat,$newmoon );
if ($date == $newmoon_date) 
     { $newmoon_date    = $ltxt_td;}
#
$ltxt_phase     = lang($arr['phase_name']); 
#
$luminance      = round(100*$arr['illumination']);
#
# ---------------  generate html
#
# ---------------- the date time
echo '<div class="PWS_ol_time"><b class="PWS_online"> ' .$online.set_my_time_lng(time(),true).' </b></div>'.PHP_EOL;
#
# ------------- the block itself
echo '<div class="PWS_module_content"><br />'.PHP_EOL;
#
# ----------------   left column  border-right-color:yellow;  height: 40px;
echo '<!-- left values -->
<div class="PWS_left">
<!-- moonrise -->
<div class="PWS_div_left" style="border-right-color: '.$clr_light.'; height: 40px;">'
        .$ltxt_mnrs.$moonrise_nxt
        .'<br /><b>'.$moonrise_time.'</b>'
        .'</div> 
<div class="PWS_div_left" style="border-right-color: '.$clr_sun_up.';">'
        .$ltxt_nxtfull
        .'<br /><b>'.$fullmoon_time.'</b>'
        .'</div>
</div>'.PHP_EOL;       
#
# ---------------- middle column
# $luminance = 100; for testing
echo '<!-- middle texts -->
<div class="PWS_middle">
    <div style="width: 130px; margin: 0px auto;">
        <!-- first the texts in the middle --> 
        <div style="position: absolute; margin: 6px 23px; width: 84px;">
          <br /><span class="large">'.$luminance.'%</span>        
          <br /><span class="orange" ><b>'.$ltxt_luninance.'</b></span>
          <hr style="margin: 1px; margin-bottom: 4px;">
          <span class="orange"><b>'.$ltxt_phase.'</b></span>
        </div>
        <canvas id="my_moon_Canvas" width="130" height="112" style="position: inherit; background: transparent; margin: 0 auto; margin-top: 4px;">
        </canvas>'.PHP_EOL;
#
if ($meteor_default <> '' ) # only displayed if there is information
     {  echo '        <br />
        <svg xmlns="http://www.w3.org/2000/svg" width="12px" height="12px" viewBox="0 0 16 16">
            <path fill="currentcolor" d="M0 0l14.527 13.615s.274.382-.081.764c-.355.382-.82.055-.82.055L0 0zm4.315 1.364l11.277 10.368s.274.382-.081.764c-.355.382-.82.055-.82.055L4.315 1.364zm-3.032 2.92l11.278 10.368s.273.382-.082.764c-.355.382-.819.054-.819.054L1.283 4.284zm6.679-1.747l7.88 7.244s.19.267-.058.534-.572.038-.572.038l-7.25-7.816zm-5.68 5.13l7.88 7.244s.19.266-.058.533-.572.038-.572.038l-7.25-7.815zm9.406-3.438l3.597 3.285s.094.125-.029.25c-.122.125-.283.018-.283.018L11.688 4.23zm-7.592 7.04l3.597 3.285s.095.125-.028.25-.283.018-.283.018l-3.286-3.553z"/>
        </svg> '
        .$meteor_default. PHP_EOL;}
else {  echo '<!-- no meteor showers today -->'.PHP_EOL;}
echo '   </div>  
</div>
<!-- eo middle -->'.PHP_EOL;
#
# ---------------- right column
echo '<!-- right area  new and full moon -->
<div class="PWS_right">
<div class="PWS_div_right" style="border-left-color: '.$clr_light.'; height: 40px;">'
        .$ltxt_mnst.$moonset_nxt.'<br /><b >'
        .$moonset_time.'</b>'
        .'</div>
<div class="PWS_div_right" style="">'
        .$ltxt_nxtnew.'<br /><b >'
        .$newmoon_time.'</b>'
        .'</div>
</div><!-- eo right area -->'.PHP_EOL;
#
# ----------------   end of html
echo '</div>'.PHP_EOL;
#
#-------- values for testing only, do not change
#$arr['phase']        = .4;
#$arr['illumination'] = 0.6;
#-----------------------------  END  for testing 
#
# -----     middle column  GRAPH
if ($arr['phase'] < 0.5) {$grow = 1;} else {$grow = -1;}
if ( ($arr['phase'] <  0.5 && $lat >= 0) 
  || ($arr['phase'] >= 0.5 && $lat < 0) )
             {  $start  = 0 - $arr['illumination'];
                $end    = 0 + $arr['illumination']; }
        else {  $start  = 1 - $arr['illumination'];
                $end    = 1 + $arr['illumination']; }               
#
echo '<script>
var c = document.getElementById("my_moon_Canvas");
var ctx = c.getContext("2d");
ctx.beginPath();
ctx.arc(63, 55, 50, 0, 2 * Math.PI);
ctx.lineWidth = 3;
ctx.strokeStyle = "silver";
ctx.stroke();';
if ($arr['illumination'] <> 0 ) {
        echo '
ctx.beginPath();
ctx.arc(63, 55, 51, '.$start.' * Math.PI, '.$end.' * Math.PI);
ctx.lineWidth = 6;
ctx.lineCap = "round";
ctx.strokeStyle = "'.$clr_sun_up.'";
ctx.stroke();'; }
echo '
</script> 
';
if (isset ($_REQUEST['test']) ) { echo '<!-- '.PHP_EOL.$stck_lst.'-->'.PHP_EOL; $stck_lst='';}       