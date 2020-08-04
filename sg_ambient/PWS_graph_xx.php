<?php $scrpt_vrsn_dt  = 'PWS_graph_xx.php|00|2020-04-30|'; # change °  to &deg; |  release 2004
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
$stck_lst       = basename(__FILE__).'('.__LINE__.') loaded  =>'.$scrpt_vrsn_dt.PHP_EOL;       // save list of loaded scrips;
#
# load settings when run stand-alone
$scrpt          = './PWS_livedata.php';
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') include_once  =>'.$scrpt.PHP_EOL; 
include_once $scrpt;
#-----------------------------------------------
#                                script settings
#-----------------------------------------------
$show_close_x   = $close_popup;  // set to false or true to overrde settings // 
$color          = "#FF7C39"; // head line
$ltxt_clsppp    = lang('Close');
#
# 	built on CanvasJs 
#       canvasJs.js is protected by CREATIVE COMMONS LICENCE BY-NC 3.0 
# 	free for non commercial use and credit must be left in tact .
#
$grph_prd='month';
if (isset ($_REQUEST['period']) )
    {   $request        = trim($_REQUEST['period']);
        $allowed        = array ('day','month','year');
        if (in_array ($request,$allowed) ) {  $grph_prd= $request; } 
        $stck_lst      .= basename(__FILE__).' ('.__LINE__.')  period set to '. $grph_prd .' from request '.$request.PHP_EOL;}
#
$grph_val='baro';
if (isset ($_REQUEST['type']) )
    {   $request        = trim($_REQUEST['type']);
        $allowed        = array ('temp','baro','rain','wind');
        if (in_array ($request,$allowed) ) {  $grph_val= $request; } 
        $stck_lst      .= basename(__FILE__).' ('.__LINE__.') type set to '. $grph_val .' from request '.$request.PHP_EOL;}
#        
if ($charts_from == 'WU') 
     {  if (!isset ($chartdata) ) {$chartdata   = 'chartswudata';}
        if (!isset ($wuID) )      {$wuID        = $id;}
        if (trim($wuID) == '') 
             {  $stck_lst .= basename(__FILE__).' ('.__LINE__.') No valid WU station name found '.PHP_EOL; 
                echo 'No valid WU station name found'; return;}
        $nm_prt1        = './'.$chartdata.'/'.$wuID;  //   chartswudata/IVLAAMSG47
        if      ($grph_prd == 'month') {$txt = 'YM.txt';}
        elseif  ($grph_prd == 'year')  {$txt = 'Y.txt';}
        else                           {$txt = 'YMD.txt';} // this period data for WU	
        $weatherfile    = $nm_prt1.$txt;
        $arr_l          = file($weatherfile);}
elseif ($grph_prd == 'day')
     {  if (!isset ($chartdata) ) {$chartdata   = 'chartsmydata';}
        $weatherfile    = './'.$chartdata.'/'.'today.txt';
        $arr_l          = file($weatherfile);}
else {  if (!isset ($chartdata) ) {$chartdata   = 'chartsmydata';}
        $year   = date('Y');
        $last   = $chartdata.'/'.$year.'.txt';
        $prev   = $chartdata.'/'.($year - 1).'.txt';
        if      ($grph_prd == 'month') {$needed = 31;} else {$needed = 361;}
        $arr_l  = array();
        $arr_p  = array();
        if (file_exists ($last) ) 
             {  $arr_l  = file ($last);}
        $cnt_l  = count ($arr_l);
        if ($needed > $cnt_l &&  file_exists ($prev) )
             {  $arr_p  = file ($prev);
                array_shift ( $arr_l);
                $arr_l = array_merge($arr_p,$arr_l);}
        $cnt_max        = count($arr_l);
        $strt           = 0;            # echo'<pre>'.' $last='.$last.' $prev='.$prev.print_r($arr_p,true).PHP_EOL;exit;
        $strt           = $cnt_max - $needed;
        if ($strt > 0)
             {  $arr_l  = array_slice ($arr_l,$strt,$needed);}
        $stck_lst      .= basename(__FILE__).' ('.__LINE__.')  $needed='.$needed. ' $cnt_max='.$cnt_max.' $strt='.$strt.' count='.count($arr_l).PHP_EOL;  #print_r($arr_l);exit;               
} // eo databse 
 $stck_lst    .= basename(__FILE__).' ('.__LINE__.') loading  =>'.$weatherfile .PHP_EOL;       
# copy file to javascript arrau
$data_str= '<script>
var allLinesArray = [];'.PHP_EOL;  // contains all datapoint values
#$data   = file ($weatherfile);
$n      = -1;
$fld_nms= '' ;# print_r($data); exit; 
foreach ($arr_l as $string) 
     {  $string = str_replace('<br>','',$string);
        $string = trim($string);
        if ($string <> '')
             {  if ($fld_nms == '' ) 
                     {$fld_nms = $string;}
                else {  $n++;
                        $data_str .=' allLinesArray['.$n.'] = "'.$string.'";'.PHP_EOL;} }
        } // eo for each
$data_str .='</script>'.PHP_EOL;
#      
# calculate conversion factors
if ($charts_from == 'WU') 
      { $names  = explode (',',$fld_nms);
        $unitsWU= ''; #echo '<pre>'.print_r($names,true); exit;
        if      (in_array ('TemperatureHighC',$names) )  {$unitsWU = 'metric';}
        elseif  (in_array ('TemperatureHighF',$names) )  {$unitsWU = 'imperial';}
        elseif  (in_array ('TemperatureC',$names) )      {$unitsWU = 'metric';}
        elseif  (in_array ('TemperatureF',$names) )      {$unitsWU = 'imperial';}
        if ($unitsWU == '')
             {  $stck_lst .= basename(__FILE__).' ('.__LINE__.')Line-names contains '.$fld_nms.PHP_EOL; 
                echo '<h3> No valid data file found, script ends</h3><br />DEBUG<br /><pre>'.$stck_lst; return;}
        if ($unitsWU == 'metric')
             {  $temp_fl = 'C';
                $wind_fl = 'km/h';
                if ($grph_prd == 'day') {$rain_fl = 'mm';} else {$rain_fl = 'cm';}
                $baro_fl = 'hPa';}
        else {  $temp_fl = 'F';
                $wind_fl = 'mph';
                $rain_fl = 'in';
                $baro_fl = 'inHg';} 
        } // get units from file for WU
else {  $temp_fl = $temp_his;
        $wind_fl = $wind_his;
        $rain_fl = $rain_his;
        $baro_fl = $baro_his;}

if (strtolower($tempunit)  ==  strtolower($temp_fl) ) 
     {  $temperatureconv = 1;} 
elseif (strtolower($temp_fl) == 'c') 
     {  $temperatureconv = -1; }
else {  $temperatureconv = 0; }
#
$repl		= array ('/',' ','hg','mb');
$with		= array ('' ,'' ,'','hpa');
$convertArr	= array
    (   "hpa"	=> array('hpa' => 1    ,   'mm' => 0.75006 	, 'in' => 0.02953),
        "mm"	=> array('hpa' => 1.3332 , 'mm' => 1 	        , 'in' => 0.03937 ),
        "in"	=> array('hpa' => 33.864 , 'mm' => 25.4 	, 'in' => 1));
$fromUnit 	= trim(str_replace ($repl,$with,strtolower($baro_fl)));
$toUnit   	= trim(str_replace ($repl,$with,strtolower($pressureunit)));
if (!isset ($convertArr[$fromUnit][$toUnit]) ) 
     {  $pressureconv = 1;} 
else {  $pressureconv = $convertArr[$fromUnit][$toUnit];}
#
$repl = array ('/',' ','p');
$with = array ('','','');
$convertArr= array
   (    "kmh"   => array('kmh' => 1	, 'kts' => 0.5399568034557235	, 'ms' => 0.2777777777777778 	, 'mh' => 0.621371192237334 ),
        "kts"   => array('kmh' => 1.852	, 'kts' => 1 			, 'ms' => 0.5144444444444445 	, 'mh' => 1.1507794480235425),
        "ms"    => array('kmh' => 3.6	, 'kts' => 1.9438444924406046	, 'ms' => 1 			, 'mh' => 2.236936292054402 ),
        "mh"    => array('kmh' => 1.609344	, 'kts' => 0.8689762419006479	, 'ms' => 0.44704 		, 'mh' => 1 ));
$fromUnit 	= trim(str_replace ($repl,$with,strtolower($wind_fl)));
$toUnit   	= trim(str_replace ($repl,$with,strtolower($windunit)));
if (!isset ($convertArr[$fromUnit][$toUnit]) ) 
     {  $windconv = 1;} 
else {  $windconv = $convertArr[$fromUnit][$toUnit];}
#
$repl 		= array ('l/m','/',' ','ch');
$with 		= array ('mm' ,'' ,'' ,'');
$convertArr	= array
     (  "mm"    => array('mm' => 1	,'in' => 0.03937007874015748 	, 'cm' => 0.1 ),
        "in"    => array('mm' => 25.4	,'in' => 1			, 'cm' => 2.54),
        "cm"    => array('mm' => 10	,'in' => 0.3937007874015748 	, 'cm' => 1 )   );
$fromUnit 	= trim(str_replace ($repl,$with,strtolower($rain_fl)));
$toUnit   	= trim(str_replace ($repl,$with,strtolower($rainunit)));
if (!isset ($convertArr[$fromUnit][$toUnit]) ) 
     {  $rainfallconv = 1;} 
else {  $rainfallconv = $convertArr[$fromUnit][$toUnit];} 
#
if ($toUnit == 'in') {$dcmls = 2;} else {$dcmls = 1;}

if ($charts_from == 'WU')
     {  $grphs        = array();  ## 2019-01-27
        $grphs['rain|day']    = '|'.       '|0|'.lang('Rainfall')   .'|'.lang('Rate').     '|1|'.lang('Rainfall')   .'|'.$rainfallconv.   '|'.$rainunit.     '|12|9|LT|12|||';
        $grphs['rain|month']  = '|YYYY-MM-DD|0|'.lang('Rainfall')   .'|'.                  '|0|'.lang('Rainfall')   .'|'.$rainfallconv.   '|'.$rainunit.     '|15|-1|MMM Do|2|c||';
        $grphs['rain|year']   = '|YYYY-MM-DD|0|'.lang('Rainfall')   .'|'.                  '|0|'.lang('Rainfall')   .'|'.$rainfallconv.   '|'.$rainunit.     '|15|-1|MMM Do|30|c||';
        $grphs['temp|day']    = '|'.       '|0|'.lang('Temperature').'|'.lang('Dewpoint'). '|1|'.lang('Temperature').'|'.$temperatureconv.'|'.$tempunit.     '|1|2|LT|20|||';
        $grphs['temp|month']  = '|YYYY-MM-DD|0|'.lang('High').       '|'.lang('Low').      '|1|'.lang('Temperature').'|'.$temperatureconv.'|'.$tempunit.     '|1|3|MMM Do|2|||';
        $grphs['temp|year']   = '|YYYY-MM-DD|0|'.lang('High').       '|'.lang('Low').      '|1|'.lang('Temperature').'|'.$temperatureconv.'|'.$tempunit.     '|1|3|MMM Do|30|||';        
        $grphs['baro|day']    = '|'.       '|0|'.lang('Barometer')  .'|'.                  '|0|'.lang('Pressure').   '|'.$pressureconv.   '|'.$pressureunit. '|3|-1|LT|12|||';
        $grphs['baro|month']  = '|YYYY-MM-DD|0|'.lang('High')       .'|'.lang('Low').      '|1|'.lang('Pressure').   '|'.$pressureconv.   '|'.$pressureunit.'|10|11|MMM Do|2|||';
        $grphs['baro|year']   = '|YYYY-MM-DD|0|'.lang('High')       .'|'.lang('Low').      '|1|'.lang('Pressure').   '|'.$pressureconv   .'|'.$pressureunit.'|10|11|MMM Do|30|||';     
        $grphs['wind|day']    = '|'.       '|0|'.lang('Wind')       .'|'.lang('Gust').     '|1|'.lang('Wind - Gust').'|'.$windconv.       '|'.$windunit.     '|6|7|LT|12|||';
        $grphs['wind|month']  = '|YYYY-MM-DD|0|'.lang('Wind')       .'|'.lang('Gust').     '|1|'.lang('Wind - Gust').'|'.$windconv.       '|'.$windunit.    '|12|14|ddd Do|2|||';
        $grphs['wind|year']   = '|YYYY-MM-DD|0|'.lang('Wind')       .'|'.lang('Gust').     '|1|'.lang('Wind - Gust').'|'.$windconv.       '|'.$windunit.    '|12|14|MMM Do|30|||';     
        $key = trim($grph_val).'|'.trim($grph_prd);
        list ($null,$grph_x_dt, $grph_x, $lng_high, $lng_low, $shw_lgnd2, $txt_val, $value_conv ,$dataunit, $graph_fld_1, $graph_fld_2, $grph_x_frmt, $grph_x_int,$grph_type,$grph_wdth) = explode('|',$grphs[$key]);
        if      ($grph_type == '')  {$grph_type = 'spline';}
        elseif  ($grph_type == 'c') {$grph_type = 'column';}
        if      ($grph_wdth == '')  {$grph_wdth = 0;}
        $stored_at = lang ('stored at WU').' ';} // eo wu
else {  $grphs['rain|day']    = '|hh:mm|0|'.lang('Rainfall')   .'|'.lang('Rate').     '|1|'.lang('Rainfall')   .'|'.$rainfallconv.   '|'.$rainunit.     '|3|9|LT|12|||';
        $grphs['rain|month']  = '|'.  '|0|'.lang('Rainfall')   .'|'.                  '|0|'.lang('Rainfall')   .'|'.$rainfallconv.   '|'.$rainunit.     '|5|-1|MMM Do|2|c||';
        $grphs['rain|year']   = '|'.  '|0|'.lang('Rainfall')   .'|'.                  '|0|'.lang('Rainfall')   .'|'.$rainfallconv.   '|'.$rainunit.     '|5|-1|MMM Do|30|c||';
        $grphs['temp|day']    = '|hh:mm|0|'.lang('Temperature').'|'.lang('Dewpoint'). '|1|'.lang('Temperature').'|'.$temperatureconv.'|'.$tempunit.     '|1|8|LT|20|||';
        $grphs['temp|month']  = '|'.  '|0|'.lang('High').       '|'.lang('Low').      '|1|'.lang('Temperature').'|'.$temperatureconv.'|'.$tempunit.     '|1|2|MMM Do|2|||';
        $grphs['temp|year']   = '|'.  '|0|'.lang('High').       '|'.lang('Low').      '|1|'.lang('Temperature').'|'.$temperatureconv.'|'.$tempunit.     '|1|2|MMM Do|30|||';        
        $grphs['baro|day']    = '|hh:mm|0|'.lang('Barometer')  .'|'.                  '|0|'.lang('Pressure').   '|'.$pressureconv.   '|'.$pressureunit. '|2|-1|LT|12|||';
        $grphs['baro|month']  = '|'.  '|0|'.lang('High')       .'|'.lang('Low').      '|1|'.lang('Pressure').   '|'.$pressureconv.   '|'.$pressureunit.'|9|10|MMM Do|2|||';
        $grphs['baro|year']   = '|'.  '|0|'.lang('High')       .'|'.lang('Low').      '|1|'.lang('Pressure').   '|'.$pressureconv   .'|'.$pressureunit.'|9|10|MMM Do|30|||';     
        $grphs['wind|day']    = '|hh:mm|0|'.lang('Wind')       .'|'.lang('Gust').     '|1|'.lang('Wind - Gust').'|'.$windconv.       '|'.$windunit.     '|6|5|LT|12|||';
        $grphs['wind|month']  = '|'.  '|0|'.lang('Wind')       .'|'.lang('Gust').     '|1|'.lang('Wind - Gust').'|'.$windconv.       '|'.$windunit.    '|7|6|MMM Do|2|||';
        $grphs['wind|year']   = '|'.  '|0|'.lang('Wind')       .'|'.lang('Gust').     '|1|'.lang('Wind - Gust').'|'.$windconv.       '|'.$windunit.    '|7|6|MMM Do|30|||';     
        $key = trim($grph_val).'|'.trim($grph_prd);
        list ($null,$grph_x_dt, $grph_x, $lng_high, $lng_low, $shw_lgnd2, $txt_val, $value_conv ,$dataunit, $graph_fld_1, $graph_fld_2, $grph_x_frmt, $grph_x_int,$grph_type,$grph_wdth) = explode('|',$grphs[$key]);
        if      ($grph_type == '')  {$grph_type = 'spline';}
        elseif  ($grph_type == 'c') {$grph_type = 'column';}
        if      ($grph_wdth == '')  {$grph_wdth = 0;}
        $stored_at = '';} // eo our own server
#
#$lng_period     = ' this '.$grph_prd;
$txt_unit       = '';
if (strtolower($dataunit) == 'f' || strtolower($dataunit) == 'c' ) {$txt_unit = '&deg;';} 
$lng_title      = $txt_val.' ('.$txt_unit.lang($dataunit).') ';
$forthe = lang('for the last');
$days   = lang('days');
$day    = lang('for this day');
$trans_period   = array('day' => $day, 'month' => $forthe.' 30 '. $days,  'year' => $forthe.' 360 '. $days);
$ltxt_url       = $lng_title.' '.$trans_period[$grph_prd];
#
header('Content-type: text/html; charset=UTF-8');
echo '<!DOCTYPE html>
<html lang="'.substr($used_lang,0,2).'">
<head>
    <meta charset="UTF-8">
    <title>'.$ltxt_url.'</title>
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name=apple-mobile-web-app-title content="Personal Weather Station">
<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, viewport-fit=cover">
    <script src="js/canvasjs.min.js"></script>
    <script src="js/moment-with-locales.min.js"></script>
    '
.my_style().'
</head>
<body class="dark" style="overflow: hidden; ">
    <div class="PWS_module_title font_head" style="width: 100%;" >'.PHP_EOL;
if ($show_close_x <> false ) // optional close text and large X
     { echo '<div style="padding-top: 2px;"><span style="float: left;">&nbsp;X&nbsp;&nbsp;<small>'.$ltxt_clsppp.'</small></span>';}
echo '    <span style="color: '.$color.'; ">'.$ltxt_url.'</span></div>
    </div>
<div class="chartContainer">
<div id="chartContainer" class="chartContainer" style="position: absolute; left: 0; margin: 0px; padding: 0px; background-color: black; text-align: left;">
</div>
</div>
<span style="float: right; margin-right: 1px; color: grey;">'.lang('Data from our weatherstation').' '.$stored_at.' </span>
'.PHP_EOL;
# generate vars for javascript
echo $data_str;
echo '<script>
var lng_title   = "'.$lng_title.'"
var bck_color   = "#000000";
var grd_color   = "RGBA(64, 65, 66, 0.8)";
var dcmls       = '.$dcmls.';
var lng_high    = "'.$lng_high.'"
var lng_low     = "'.$lng_low.'"
var val_convert = "'.$value_conv.'";
var graph_fld_1 = '.$graph_fld_1.';
var graph_fld_2 = '.$graph_fld_2.';
var grph_x      = '.$grph_x.';
var grph_x_dt  = "'.$grph_x_dt.'";
var grph_x_frmt = "'.$grph_x_frmt.'";
var grph_x_int  = '.$grph_x_int.';
var data_unit   = "'.$dataunit.'";
var shw_lgnd2   = '.$shw_lgnd2.';
var grph_type   =  "'.$grph_type.'";
var grph_wdth   = '.$grph_wdth.';
moment.locale("'.$used_lang.'");
</script>'.PHP_EOL;
#
?>
<script>
    //barometermonth
var dataPoints1 = [];	
var dataPoints2 = [];
var n = allLinesArray.length-1;
for (var i = 0; i <= n; i++) 
     {  var rowData = allLinesArray[i].split(',');
        if ( rowData.length >1)
             {  xvalue  = moment(rowData[grph_x],grph_x_dt).format(grph_x_frmt);
                if (val_convert > 0) 
                     {  var yvalue1 = parseFloat(rowData[graph_fld_1]* val_convert);
                        if (graph_fld_2 != -1)  
                             {  var yvalue2 = parseFloat(rowData[graph_fld_2]* val_convert);}} 
                else if (val_convert == -1)  // C to F parseFloat((rowData[xxxxx] *1.8) +32
                     {  var yvalue1 = parseFloat((rowData[graph_fld_1]*1.8)  +32);
                        if (graph_fld_2 != -1)  
                             {  var yvalue2 = parseFloat((rowData[graph_fld_2]* 1.8) +32);}} 
                else // F to C parseFloatparseFloat((rowData[xxxx]- 32) / 1.8
                     {  var yvalue1 = parseFloat((rowData[graph_fld_1]- 32)  / 1.8);
                        if (graph_fld_2 != -1)  
                             {  var yvalue2 = parseFloat((rowData[graph_fld_2] -32 )/ 1.8);}} 
                dataPoints1.push(
                     {  label: xvalue ,
                        y:yvalue1  });
                if (graph_fld_2 != -1) 
                      { dataPoints2.push(
                              { label: xvalue,
                                y:yvalue2 });
                        }
                }
        }
var chart = new CanvasJS.Chart("chartContainer", 
    {   backgroundColor: bck_color,
        animationEnabled: true,
        dataPointWidth: grph_wdth,
        title: {text: "",
                fontSize: 12,
                fontColor:' #ccc',
                fontFamily: "arial",},
        toolTip:{ fontStyle: "normal",
                   cornerRadius: 4,
                   backgroundColor: bck_color, fontColor:'grey',
                   toolTipContent: " x: {x} y: {y} <br /> name: {name}, label:{label}",
                   shared: true, },
        axisX: {gridColor: grd_color,
                labelFontSize: 10,
                labelFontColor:' #ccc',
                lineThickness: 0.5,
                gridThickness: 0.5,	
                titleFontFamily: "arial",	
                labelFontFamily: "arial",	
                minimum: 0,
                interval: grph_x_int , 
                intervalType : "hour"
                },	
        axisY:{ title: lng_title,
                titleFontColor: "#ccc",
                titleFontSize: 10,
                titleWrap: false,
                margin: 10,
                lineThickness: 0.5,		
                gridThickness: 1,		
                includeZero: false,
                gridColor: grd_color,
                labelFontSize: 11,
                titleFontFamily: "arial",
                labelFontFamily: "arial",
                labelFormatter: function ( e ) {
                        return e.value .toFixed(dcmls) ;   },		 
                labelFontColor:' #ccc', },  
        legend:{ fontFamily: "arial",
                fontColor:"#ccc", },
        data: [
            {   type: grph_type,
                color:"#ff9350",
                markerSize:2,
                showInLegend:true,
                legendMarkerType: "circle",
                lineThickness: 0,
                markerType: "circle",
                name: lng_high,
                dataPoints: dataPoints1,
                yValueFormatString: "#0.## " + data_unit,},
            {   type: grph_type,
                color:"#00A4B4",
                markerSize:2,
                showInLegend: shw_lgnd2,
                legendMarkerType: "circle",
                lineThickness: 2,
                markerType: "circle",
                name: lng_low,
                dataPoints: dataPoints2,
                yValueFormatString: "#0.## " + data_unit ,}
                ]
        }
        );
chart.render();
</script>
</body>
</html>
<?php if (isset ($_REQUEST['test']) ) { echo '<!-- '.PHP_EOL.$stck_lst.'-->'.PHP_EOL; $stck_lst='';}
#
# style is printed in the header 
function my_style()
     {  global $popup_css ;
        $return         = PHP_EOL.'    <style>'.PHP_EOL;
# load the genral css for pop-ups
        if (isset ($popup_css) && $popup_css <> false)  
             {  $return .= file_get_contents ($popup_css);}
# add pop-up specific css
        $return         .= ' iframe {width: 100%;}'.PHP_EOL;        
        $return         .= '    </style>'.PHP_EOL;
        return $return;
 }
