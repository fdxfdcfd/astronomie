<?php  $scrpt_vrsn_dt  = 'soil_tmp_mst_small.php|00|2020-04-15|';  # typos removed | release2004
# Settings:
# -----------------------    
$tmp_key                = 'soil_tmp1';
$hum_key                = 'soil_mst1';
$first_description      = 'Garden';
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
#-----------------------------------------------
#                                script settings
#-----------------------------------------------
$box_style      = 'width: 55px; height: 55px; margin: 2px; padding-top:16px; color: black; border-width: 1px;';
# ------------------------------test values
#$weather[$tmp_key]  = 14.5; 
#$weather[$hum_key]  = 130;
# ------------------------------test values
if ($tmp_key <> false && !array_key_exists($tmp_key, $weather) ) {$tmp_key = false;} 
if ($hum_key <> false && !array_key_exists($hum_key, $weather) ) {$hum_key = false;} 

#
if ($tmp_key == false && $hum_key == false)
     {  echo '<smal style="color: red;">Soil-sensor not available, script ends</small>';
        return;}
#
$how_much_text  = 0;
$left_block     = '';
$right_block    = '';
#
if (!isset ($_REQUEST['id_blck'])) // at first loading set the block heading
     {  echo '<script> id_blck = id_blck + "mt_s";  document.getElementById(id_blck).innerHTML = "'.lang ($first_description).'" </script>'.PHP_EOL;
     }
if (!array_key_exists ('soilmoist_type',$weather) )
     {  $moist_unit = '%'; } else {$moist_unit = $weather['soilmoist_type'];}

if ($hum_key <> false) 
     {  if ($moist_unit == '%') 
             {  getSoilMoistIndex ($weather[$hum_key] ); }
        else {  getSoilMoistCB ($weather[$hum_key] ); }
        $how_much_text++;  
        $left_block =   '<div class="PWS_div_left PWS_round" style="' .$box_style.' float: left;  background-color: '.$color_b.';">'
                .'<span style="font-size: 16px; ">'.round($weather[$hum_key]).'</span><b><small><br />'.$moist_unit.'</small></b>'.PHP_EOL.'</div>'.PHP_EOL;}
#                
if ($tmp_key <> false) 
     {  getSoilTempIndex  ($weather[$tmp_key] );
        $how_much_text++;  
        $right_block  = '<div class="PWS_div_left PWS_round" style="'.$box_style.' float: right; background-color: '.$color_b_t.';">'
                .'<span style="font-size: 16px;">'.$weather[$tmp_key].'&deg;</span>'.PHP_EOL.'</div>'.PHP_EOL;}
#
if ($how_much_text == 1)
    {   $padding= 18; } else { $padding= 4; } 
#
$middle_block =  '<span class=" " style="display: block; padding: '.$padding.'px;">';

if ($hum_key <> false) 
    {   $middle_block .= lang('Moisture').': '.lang($text); }

if ($how_much_text > 1)
    {   $middle_block .= '<br />'; }
    
if ($tmp_key <> false)  
   {   $middle_block .= lang('Temperature').': '.lang($text_t);}

$middle_block .=   '</span>';
#
echo $left_block.$right_block.$middle_block;
#
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') $color_b= '.$color_b.' $color_t= '.$color_t.' $text= '.$text.' $range='.$range.PHP_EOL;

if (isset ($_REQUEST['test']) ) { echo '<!-- '.PHP_EOL.$stck_lst.'-->'.PHP_EOL; $stck_lst='';}
#-----------------------------------------------
#                                      functions
#-----------------------------------------------
function getSoilMoistIndex ( $moist ) {      # $soilArr = array (0,11,26,61,101,240); < 11 =saturated 240  Dangerous Dry $weather['soilmoist_type']= 'cb';
        global $color_b, $color_t, $text,$range;
	$soilArr = array (0,11,26,61,101,240);  
	$soilArr = array (100,75,50,25,0);
	$moist   = (int) $moist;
	$smArr = array();
	$smArr['75'] = '#003399|white|Saturated|'.$soilArr[0].' - '.$soilArr[1];
	$smArr['50'] = '#33FF00|black|Adequate|'.$soilArr[1].' - '.$soilArr[2];
	$smArr['25'] = '#FF0000|white|Irrigation needed|'.$soilArr[2].' - '.$soilArr[3];
	$smArr['0' ] = '#9933CC|white|Dangerous Dry|'.$soilArr[3].' - '.$soilArr[4];
        $result = '';
        foreach ($smArr as $level => $texts)
             {  if ($moist > (int) $level)
                     {  $result = $texts;
                        list ($color_b, $color_t, $text,$range) = explode ('|',$result.'|' );
                        break;}
                continue;}
        if ($result == '') {list ($color_b, $color_t, $text,$range) = explode ('|','grey|white|ERROR no reading|??|' );}
}  // eof get_SoilMoistIndex
function getSoilMoistCB ( $moist ) {      # $soilArr = array (0,11,26,61,101,240); < 11 =saturated 240  Dangerous Dry $weather['soilmoist_type']= 'cb';
        global $color_b, $color_t, $text,$range;
	$soilArr = array (0,11,26,61,101,240);  
	$moist   = (int) $moist;
	$smArr = array();
	$smArr['11'] = '#003399|white|Saturated|'.$soilArr[0].' - '.$soilArr[1];
	$smArr['26'] = '#33FF00|black|Adequate|'.$soilArr[1].' - '.$soilArr[2];
	$smArr['61'] = '#33FF00|black|Irrigation desired|'.$soilArr[1].' - '.$soilArr[2];
	$smArr['101']= '#FF0000|white|Irrigation needed|'.$soilArr[2].' - '.$soilArr[3];
	$smArr['240']= '#9933CC|white|Dangerous Dry|'.$soilArr[3].' - '.$soilArr[4];
        $result = '';
        foreach ($smArr as $level => $texts)
             {  if ($moist < (int) $level)
                     {  $result = $texts;
                        list ($color_b, $color_t, $text,$range) = explode ('|',$result.'|' );
                        break;}
                continue;}
        if ($result == '') {list ($color_b, $color_t, $text,$range) = explode ('|','grey|white|ERROR no reading|??|' );}
}  // eof get_SoilMoistIndex


function getSoilTempIndex ( $soiltemp ) {
	global $weather;
	global $color_b_t, $color_t_t, $text_t,$range_t;
	if ($weather['temp_units'] == 'F')
	      { $key = convert_temp ($soiltemp,'F','C');
	        $tempArr = array (0,20,33,50,60,70,100); } 
	else   {$key = $soiltemp;
	        $tempArr = array (-18,-6,0,10,15,21,37);} 
#	$tempArr = array (-18,-6,0,10,15,21,37); } else {$tempArr = array (0,20,33,50,60,70,100);}
        $tmp_unit       =  ' &deg;'.$weather['temp_units'];
	$result == '';
	$stArr = array();
	$stArr['-18']   = 'grey|red|ERROR no reading|'.              ' &lt; '.$tempArr[0];
	$stArr['-6']    = '#003399|white|Deep freeze|'.'                &lt '.$tempArr[1];
	$stArr['0']     = '#33CCFF|black|Frost line|'.      $tempArr[1].' - '.$tempArr[2];
	$stArr['10']    = '#FF0000|white|Too cold to plant|'.$tempArr[2].' - '.$tempArr[3];
	$stArr['15']    = '#FF9933|black|Minimum growth|'.   $tempArr[3].' - '.$tempArr[4];
	$stArr['21']    = '#669900|white|Optimal growth|'.   $tempArr[4].' - '.$tempArr[5];
	$stArr['37']    = '#33FF00|black|Ideal growth|'.     $tempArr[5].' - '.$tempArr[6];
	
        foreach ($stArr as $level => $texts)
             {  if ($key < (int) $level)
                     {  $result = $texts;
                        list ($color_b_t, $color_t_t, $text_t,$range_t) = explode ('|',$result.'|' );
                        break;}
                continue;}
        if ($result == '') 
             { list ($color_b_t, $color_t_t, $text_t,$range_t) = explode ('|','|grey|red|ERROR no reading|'. ' &gt; '.$tempArr[6].'|' );}
	$range_t .= $tmp_unit;
}				// end get_SoilTemperature

