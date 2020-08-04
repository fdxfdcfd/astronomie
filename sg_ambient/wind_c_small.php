<?php  $scrpt_vrsn_dt  = 'wind_c_small.php|00|2019-12-12|';  # release 1912
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
$name_l = lang ('Today');
#
$bft_clr = array(
	"transparent", 
	"transparent", "transparent", "transparent", "transparent", "transparent", 
	 "#FFFF53",    "#F46E07",     "#F00008",     "#F36A6A",     "#6D6F04", 
	 "#640071",    "#650003");	
#
if (!function_exists ("wind_color") ){
function wind_color ( $value)  
     {  global $windunit,$bft_clr,$toKnots;       
        if ($value === 'n/a' || $value === false) 
            {   return '<!-- no value '.$value.' -->'.PHP_EOL; return;}
        $bft_clr = array(
                "lightgrey", 
                "lightgrey", "lightgrey", "lightgrey", "lightgrey", "lightgrey", 
                 "#FFFF53",    "#F46E07",     "#F00008",     "#F36A6A",     "#6D6F04", 
                 "#640071",    "#650003");	
        $spd_knts = round ((float) $value*$toKnots , 1);  #echo $spd_knts; exit;
        $lvl_bft= array ( 1 ,  4,  7, 11, 17, 22, 28, 34, 41, 48, 56, 64, 999999999999 );   // https://simple.wikipedia.org/wiki/Beaufort_scale
        foreach ($lvl_bft as $key => $n)
             {  if ($spd_knts > $n) {continue;}  # $key=12; # for test 
                $txt = 'beaufort'.$key;
                break;}
        $clr    = $bft_clr[$key];
        return $clr;}
}
# ------------------     max wind
$wnd    = (float) $weather['wind_speed_max']; 
if ($wnd > 99) {$dec = 0;} else {$dec = $dec_wnd;}
$color  = wind_color ($wnd);
$box_style      = 'width: 55px; height: 55px; margin: 2px; padding-top: 8px; color: black; border-width: 1px;';
#
echo '<div class="PWS_div_left PWS_round" style="'.$box_style.' float: left; background-color: '.$color.';">'
        .'<span style="font-size: 16px; ">'.number_format ($wnd,$dec).'</span>'
        .'<br />'.$weather["wind_speed_max_time"].'
</div>'.PHP_EOL;
# ------------------     max gust
if ((string)$weather['wind_gust_speed_max'] <> 'n/a')
     {  $wnd  = (float) $weather['wind_gust_speed_max'];
        if ($wnd > 99) {$dec = 0;} else {$dec = $dec_wnd;}
        $color  = wind_color ($wnd);
        echo '<div class="PWS_div_left PWS_round" style="'.$box_style.' float: right; background-color: '.$color.';">'
                .'<span style="font-size: 16px; ">'.number_format ($wnd,$dec_wnd).'</span>'
                .'<br />'.$weather["wind_gust_speed_max_time"].'
        </div>'.PHP_EOL;}
#
echo '<span class="large orange" style="display: block; padding-top: 15px;">'.$name_l.'</span>';

if (isset ($_REQUEST['test']) ) { echo '<!-- '.PHP_EOL.$stck_lst.'-->'.PHP_EOL; $stck_lst='';}
