<?php $scrpt_vrsn_dt  = 'fct_yrno_shared_hrs.php|00|2020-04-15|';  # BETA
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
# -------------------save list of loaded scrips;
$stck_lst       = basename(__FILE__).' ('.__LINE__.') version =>'.$scrpt_vrsn_dt.PHP_EOL;     
#
$fl_folder     = './jsondata/';
$yrno_fct_fl    = 'yrno_fct_hourly.xml';  
$icn_prefix     = './pws_icons/';
$icn_post       = '.svg';
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
if (!function_exists ('temp_color') ) {
function temp_color ( $value)
     {  global $tempunit, $maxTemp, $temp_colors;
        if ($value === 'n/a' || $value === false) 
            {   return '<!-- no value '.$value.' -->'.PHP_EOL; return;}
        $tmp    = (float) $value; 
        if ($tempunit <> 'C')
             {  $tmp    = round (    5*( ($tmp -32)/9) );}
        $n      = 32 + (int) $tmp;
        if ($n > $maxTemp)      
             {  $color  = $temp_colors[$maxTemp];}
        else {  $color  = $temp_colors[$n];}
        return $color;}
} // eo exist temp_color

$raw_data       = file_get_contents($fl_folder.$yrno_fct_fl);
$data_fct       = pws_check_xml($raw_data, array( '</weatherdata>') ); # echo '<pre>yrno data'.print_r($data_fct, true) ; exit;    
#
$parts          = array();
if ($data_fct == null) { echo '<small style="color: red;"><b>invalid data</b></small>'; return;}

$yrno_fct_time = filemtime($fl_folder.$yrno_fct_fl);

$lastupdate     = $data_fct['meta']['lastupdate'];  
$nextupdate     = $data_fct['meta']['nextupdate']; # echo '<pre>'.$lastupdate.' '.$nextupdate.PHP_EOL; exit;
$periods        = $data_fct['forecast']['tabular']['time'];

#$dayp_txts      = array ( lang('night'), lang('morning') ,lang('afternoon'),lang( 'evening'));

$valid          = time() - 2*3600; # echo date ('c',$valid).PHP_EOL;
$arr    = array(); 
$arr['credit_link'] = $data_fct['credit']['link']['@attributes']['url'];
$arr['credit_text'] = $data_fct['credit']['link']['@attributes']['text'];
foreach ($periods as $period)
     {  # echo '<pre>'.print_r($period, true) ; exit;   
        $arr['unix']    = strtotime($period['@attributes']['from']);
        $arr['wday']    = lang(date('l',$arr['unix'])).'<br>'.$dayp_txts[$arr['part']];

        $arr['from']    = str_replace (':00','',set_my_time($period['@attributes']['from']));
        $arr['to']      = str_replace (':00','',set_my_time($period['@attributes']['to']));
# temp
        $unit           = substr($period['temperature']['@attributes']['unit'],0,1);
        $temp           = convert_temp  ($period['temperature']['@attributes']['value'],$unit,$tempunit,0);
        $arr['temp']    = $temp;
        $arr['tclr']    = temp_color($temp);
# rain
        $arr['rain']    = convert_precip($period['precipitation']['@attributes']['value'],'mm',$rainunit,1);
#pressure
        $unit           = $period['pressure']['@attributes']['unit'];
        $arr['baro']    = convert_baro  ($period['pressure']['@attributes']['value'],$unit,$pressureunit,1);
# wind dir
        $arr['wdir']    = $period['windDirection']['@attributes']['code'];        # [code] => SE
        $arr['wdeg']    = round($period['windDirection']['@attributes']['deg']);  # [deg] => 132.3
# wind speed
        $unit   = 'ms';
        $arr['wspd']    = convert_speed ($period['windSpeed']['@attributes']['mps'],$unit,$windunit,0);
        $arr['bftt']    = lang($period['windSpeed']['@attributes']['name']);
# icon        
        $arr['icnx']    = $period['symbol']['@attributes']['var'];
        $arr['icon']    = icon_trns($arr['icnx']);
        $arr['icnl']    = $icn_prefix.$arr['icon'].$icn_post;
        $original       = $period['symbol']['@attributes']['name'];
        $translated     = lang($original);
        if ($original == $translated)
             {  $translated = '';
                $blocks = explode ('with',$original); 
                foreach ($blocks as $key => $block)
                     {  $or_BL  = trim ($block);
                        $tr_BL  = lang ($or_BL);
                        if ($or_BL == $tr_BL)
                             {  $words  = explode (' ',$or_BL); 
                                foreach ($words as $key => $word) { $translated .= lang ($word).' ';}
                                $translated = trim($translated);}
                        else {  $translated .= $tr_BL;}
                        $translated .= '#';}
                $translated = substr($translated,0,-1);
             #   $translated = str_replace ('#',' '.lang('with').' ',$translated);
                $translated = str_replace ('#',' , ',$translated);
                }                 
        $arr['desc']    = $translated;        
        $parts[]        = $arr; 
        $arr            = array();           
}
$count  = count($parts);
for ($n = 0; $n < $count ; $n++)
    {   if ($n < $count - 1) {$tmp_to   = $parts[$n+1]['temp'];} else {$tmp_to = '';}
        $parts[$n]['temp_ft'] = $parts[$n]['temp'].'&rarr;'.$parts[$n+1]['temp'];}
#echo '<pre>data '.print_r($parts, true) ; exit;
#-----------------------------------------------
# Icon translation
#-----------------------------------------------
function icon_trns ($icon)
     {  global $stck_lst;
        $icn_t = array(		//  YrNo icon to default icon translation array
'01d'=>'clear_day',	'01n'=>'clear_night',	// 1 Sun
'02d'=>'few_day',	'02n'=>'few_night',	// 2 LightCloud
'03d'=>'pc_day',	'03n'=>'pc_night',      // 3 PartlyCloud
'04' =>'ovc_dark',	'04d'=>'ovc_dark',	'04n'=>'ovc_dark',	// 4 Cloud
'05d'=>'mc_rain',	'05n'=>'mc_rain',	// 5 LightRainSun
'06d'=>'ovc_thun_rain_dark',   '06n'=>'ovc_thun_rain_dark',	// 6 LightRainThunderSun
'07d'=>'ovc_sleet',	'07n'=>'v',  // 7 SleetSun
'08d'=>'ovc_sleet',	'08n'=>'ovc_sleet',	// 8 SnowSun
'09' =>'mc_rain',       '09d'=>'mc_rain',	'09n'=>'mc_rain',	// 9 LightRain
'10' =>'mc_rain',	'10d'=>'mc_rain',	'10n'=>'mc_rain',	// 10 Rain
'11' =>'ovc_thun_rain_dark',   '11d'=>'ovc_thun_rain_dark',	'11n'=>'ovc_thun_rain_dark',	// 11 RainThunder
'12' =>'ovc_sleet',	'12d'=>'ovc_sleet',	'12n'=>'ovc_sleet',	// 12 Sleet
'13' =>'ovc_flurries',   '13d'=>'ovc_flurries',	'13n'=>'ovc_flurries',	// 13 Snow
'14' =>'ovc_thun_rain_dark',	'14d'=>'ovc_thun_rain_dark',	'14n'=>'ovc_thun_rain_dark',	// 14 SnowThunder
'15' =>'mc_fog',   '15d'=>'mc_fog',	'15n'=>'mc_fog_dark',  // 15 Fog
'20d'=>'ovc_thun_rain_dark',   '20n'=>'ovc_thun_rain_dark',  // 20 SleetSunThunder
'21d'=>'ovc_thun_rain_dark',   '21n'=>'ovc_thun_rain_dark',  // 21 SnowSunThunder
'22d'=>'ovc_thun_rain_dark',   '22n'=>'ovc_thun_rain_dark',  // 22 LightRainThunder
'23d'=>'ovc_thun_rain_dark',   '23n'=>'ovc_thun_rain_dark',  // 23 SleetThunder
'24d'=>'ovc_thun_rain_dark',   '24n'=>'ovc_thun_rain_dark',  // 24 DrizzleThunderSun
'25d'=>'ovc_thun_rain_dark',   '25n'=>'ovc_thun_rain_dark',  // 25 RainThunderSun
'26d'=>'ovc_thun_rain_dark',   '26n'=>'ovc_thun_rain_dark',  // 26 LightSleetThunderSun
'27d'=>'ovc_thun_rain_dark',   '27n'=>'ovc_thun_rain_dark',  // 27 HeavySleetThunderSun
'28d'=>'ovc_thun_rain_dark',   '28n'=>'ovc_thun_rain_dark',  // 28 LightSnowThunderSun
'29d'=>'ovc_thun_rain_dark',   '29n'=>'ovc_thun_rain_dark',  // 29 HeavySnowThunderSun
'30d'=>'ovc_thun_rain_dark',   '30n'=>'ovc_thun_rain_dark',  // 30 DrizzleThunder
'31d'=>'ovc_thun_rain_dark',   '31n'=>'ovc_thun_rain_dark',  // 31 LightSleetThunder
'32d'=>'ovc_thun_rain_dark',   '32n'=>'ovc_thun_rain_dark',  // 32 HeavySleetThunder
'33d'=>'ovc_thun_rain_dark',   '33n'=>'ovc_thun_rain_dark',  // 33 LightSnowThunder
'34d'=>'ovc_thun_rain_dark',   '34n'=>'ovc_thun_rain_dark',  // 34 HeavySnowThunder

'40d'=>'mc_flurries',   '40n'=>'mc_flurries_night',  // 40 DrizzleSun
'41d'=>'mc_flurries',   '41n'=>'mc_flurries_night',  // 41 RainSun
'42d'=>'mc_flurries',   '42n'=>'mc_flurries_night',  // 42 LightSleetSun
'43d'=>'mc_flurries',   '43n'=>'mc_flurries_night',  // 43 HeavySleetSun
'44d'=>'mc_flurries',   '44n'=>'mc_flurries_night',  // 44 LightSnowSun
'45d'=>'mc_flurries',   '45n'=>'mc_flurries_night',  // 45 HeavysnowSun
'46d'=>'mc_flurries',   '46n'=>'mc_flurries_night',  // 46 Drizzle
'47d'=>'mc_flurries',   '47n'=>'mc_flurries_night',  // 47 LightSleet
'48d'=>'mc_flurries',   '48n'=>'mc_flurries_night',  // 48 HeavySleet
'49d'=>'mc_flurries',   '49n'=>'mc_flurries_night',  // 49 LightSnow
'50d'=>'mc_flurries',   '50n'=>'mc_flurries_night',  // 50 HeavySnow
);
        if (!isset ($icn_t[$icon]))
             {  $stck_lst      .= basename(__FILE__).' ('.__LINE__.') unknown icon '.$icon.PHP_EOL;
                $return = 'offline';}
        else {  $return = $icn_t[$icon];}
        return $return;}
#-----------------------------------------------
# Check validity of xml and convert to PHP_array
#-----------------------------------------------
function pws_check_xml($raw_data, $check_contents = false){
        global $ws_msg_string;
        $ws_script_environment = basename(__FILE__).' => '.__FUNCTION__;
	$data_ok= true;
	$data   = trim($raw_data);		
	libxml_use_internal_errors(true);
	libxml_clear_errors();
	$doc    = new DOMDocument('1.0', 'utf-8');
	$doc->loadXML($data);
	$errors = libxml_get_errors();
	unset ($doc);
	if(!empty($errors)) {
	        foreach(libxml_get_errors() as $error) 
		     {  echo $ws_script_environment.' ('.__LINE__.'): error '.trim($error->message);}
		return false;}
	if ($check_contents <> false) {
	        if (is_array ($check_contents) ) 
	             {  $checks    = $check_contents; } 
	        else {  $checks[0] = $check_contents; } 
	        foreach ($checks as $find) {
                        $found  = strrpos($data,$find);
                        if ($found === false) {
                                echo $ws_script_environment.' ('.__LINE__.'): bad formatted xml: '.$find.' not found.';
                                return false;}
		} // eoforeach
	} // eo if check contents
        libxml_clear_errors();
        $xml = simplexml_load_string( $raw_data , null , LIBXML_NOCDATA ); 
        $json = json_encode($xml); 
        $data = json_decode($json,TRUE);
	return $data;	
} // eo pws_check_xml