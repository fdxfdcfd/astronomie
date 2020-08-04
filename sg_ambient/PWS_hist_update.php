<?php  $scrpt_vrsn_dt  = 'PWS_hist_update.php|00|2019-12-15|';    # release 1912
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
$stck_lst       = basename(__FILE__).' ('.__LINE__.') version =>'.$scrpt_vrsn_dt.PHP_EOL;       
#-----------------------------------------------
#                                script settings
#-----------------------------------------------
$show_close_x   = false;        // 
$color          = "#FF7C39";    // important color
$cron           = true;         // force default units, all $_GET[] are ignored
#
# -------------load weatherdata and all settings 
$scrpt          = 'PWS_livedata.php'; 
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') include_once =>'.$scrpt.PHP_EOL;
include_once $scrpt; 
# ------------------ check if user is legitimate
if (!isset($_REQUEST['pw']) || trim($_REQUEST['pw']) <> $password) { die ('Sorry - not possible');}
#
#-----------  text fileds used - no translations
$ltxt_url       = 'Update the station weather history';
$ltxt_clsppp    = 'Close';
#
# the text for the rows in the table
$ltxt_type              = array ();
$ltxt_type['temp']      = 'Temperature'   .color_unit($temp_his,$weather['temp_units']);
$ltxt_type['dewp']      = 'Dewpoint'      .color_unit($temp_his,$weather['temp_units']);
$ltxt_type['rain']      = 'Rain'          .color_unit($rain_his,$weather['rain_units']);
$ltxt_type['humd']      = 'Humidity'      .'<b> % </b>';
$ltxt_type['baro']      = 'Pressure'      .color_unit($baro_his,$weather['barometer_units']);
$ltxt_type['wind']      = 'Wind'          .color_unit($wind_his,$weather['wind_units']);
$ltxt_type['gust']      = 'Gust'          .color_unit($wind_his,$weather['wind_units']);
$ltxt_type['uvuv']      = 'UV'            .'&nbsp;<b>Index</b>';
$ltxt_type['solr']      = 'Solar'         .'&nbsp;<b>w/m<sup>2</sup></b>'; 
function color_unit ($unit1,$unit2){
        if ($unit1 <> $unit2) {$style=' style="color: red;"';} else {$style='';}
        return '&nbsp;<b'.$style.'>'.$unit1.'</b>'; }
#-----------------------------------------------
#          The following arrays drive the script
#-----------------------------------------------
# used for "Current" data: the name in the weather array  
$ltxt_value['temp']     = 'temp';
$ltxt_value['rain']     = 'rain_lasthour';
$ltxt_value['humd']     = 'humidity';
$ltxt_value['dewp']     = 'dewpoint';
$ltxt_value['baro']     = 'barometer';
$ltxt_value['wind']     = 'wind_speed';
$ltxt_value['gust']     = 'wind_gust_speed';
$ltxt_value['uvuv']     = 'uv';
$ltxt_value['solr']     = 'solar';
#
#
# also show the low value for those items
$ltxt_lows      = array ('temp',  'dewp', 'baro', 'humd');
#
# the coloms which can be in the table
$ltxt_col       = array();
$ltxt_col['type']    = '';
$ltxt_col['month']   = 'This month'.'<br />value  DD ';
$ltxt_col['year']    = 'This year'.'<br />value  MM-DD ';
$ltxt_col['all']     = 'All time'.'<br />value  YYYY-MM-DD';
#
# the rows which can be in the table
$show           = array('temp', 'rain', 'humd', 'dewp', 'baro', 'wind', 'gust' );
if ($uvsolarsensors == 'both' || $uvsolarsensors == 'wf' || $uvsolarsensors == 'darksky') {
        $show[]         = 'solr';
        if ($uvsolarsensors <> 'darksky') {$show[]  = 'uvuv';}
}
#  
$hist = unserialize (file_get_contents('_my_settings/history.txt'));   #echo '<pre>'.print_r ($hist,true);
if (! is_array ($hist) ) {die (basename(__FILE__).' ('.__LINE__.') no correct data file found');}
#
if (isset($_POST['submit']) && $_POST['submit'] == 'Save updates') {
        $year   = $_POST['year'];
        $month  = $_POST['month'];
        $Yr_m   = $year.'-'.$month.'-';
        $year   = $year.'-';
        $updated= false;
        $report = '';
        $arr    = $_POST['return'];  # echo '<pre>'.print_r($arr,true); exit;
        $hi_lo  = array ('HghV',  'LowV');
        $times  = array ('HghV_D','LowV_D');
        $string = '';
        foreach ($show as $type)  {  
                foreach ($ltxt_col as $col => $text) {
                        if ($col == 'type') {continue;}
                        $high_value     = $low_value    = $high_date    =  $low_date    = 'n/a';
                    foreach ($hi_lo as $n => $key)  {
                        if ($key == 'LowV' && !in_array($type,$ltxt_lows) ){continue;}
                        if (isset ($arr[$type][$key][$col])  && trim($arr[$type][$key][$col]) <> 'n/a') {
                                $new_value      = (float) $arr[ $type][$key][$col];
                                $old_value      = (float) $hist[$type][$key][$col];
                                if ($old_value <> $new_value) {
                                        $string .= $type.$key.$col.' old='.$hist[$type][$key][$col].' update='.$new_value.'<br />'.PHP_EOL;
                                        $hist[$type][$key][$col]      = $new_value;
                                        $updated= true;}
                        }//  value exist
                        $key    = $times[$n];
                        $date   = $hist[$type][$key][$col];             
                        $value  = trim($arr[$type][$key][$col]);        #echo $type.'-'.$key.'-'.$col.'='.$hist[$type][$key][$col].' post='.$arr[$type][$key][$col]; exit;
                        if     ($col == 'all') {
                                $compare= date ('Y-m-d',$date);
                                $new    = $value;}
                        elseif ($col == 'year'){
                                $compare= date (  'm-d',$date);
                                $new    = $year.$value;}
                        else {  $compare= date (    'd',$date);
                                $new    = $Yr_m.$value;}
                        if ($value <> $compare && $type <> 'rain') 
                             {  list ($yr,$mnth,$day) = explode ('-',$new.'---');
                                $new_value = mktime( 0, 0, 0, (int) $mnth, (int) $day, (int) $yr);
                                $string .= '$value='.$value.' $compare='.$compare.'<br />'.PHP_EOL;
                                $string .= $type.$key.$col.' old='.$hist[$type][$key][$col].' update='.$new_value.'<br />'.PHP_EOL;
                                $hist[$type][$key][$col]= $new_value;
                                $updated= true;}
                    } // eo hi_lo
                } // eo foreach ($ltxt_col
        } // eo foreach $show 
        if ($updated==true) { 
                $result = file_put_contents('_my_settings/history.txt', serialize($hist));
                if ($result == false) {$string .= '<br /><b style="color: red;">ERROR:</b> The updated history could not be saved. Please contact support.<br />'.PHP_EOL; }     
        } 
} // eo submit
/*['temp'] => Array(
            ['HghV'] => Array(
                    ['month'] => 11.40
                    ['year'] => 43.60
                    ['all'] => 43.60)
            ['LowV'] => Array (
                    ['month'] => -2.30
                    ['year'] => -5.90
                    ['all'] => -13.90 )
            ['HghV_D'] => Array (
                    ['month'] => 08
                    ['year'] => 07-25
                    ['all'] => 2019-07-25 )
            ['LowV_D'] => Array (
                    ['month'] => 05
                    ['year'] => 01-21
                    ['all'] => 2012-02-04)*/
#-----------------------------------------------
#                         first part of the html
#-----------------------------------------------
echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>'.$ltxt_url.'</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
'.my_style().'
</head>
<body class="dark" style="background-color: black; overflow: hidden; max-width: 850px; margin: 0 auto; text-align: center;">
    <div class="PWS_module_title  font_head" style="width: 100%;" >'.PHP_EOL;
if ($show_close_x <> false ) // optional close text and large X
     { echo '<div style="padding-top: 2px;"><span style="float: left;">&nbsp;X&nbsp;&nbsp;<small>'.$ltxt_clsppp.'</small></span>';}
echo '    <span style="color: '.$color.'; ">'.$ltxt_url.'</span></div>
    </div>'.PHP_EOL;
        if ($updated==true) { 
                echo 'there were updates<br />'.$string.'<hr>';             
        } 
echo '<div class= "div_height" style="width: 100%;text-align: center;  overflow: auto;">
<form method="post" name="hist_update" action="PWS_hist_update.php" style="padding: 0px; margin: 0px;">
<table class= " font_head"  style=" width: 80%; margin: 0px auto; text-align: center;">'.PHP_EOL;
#
# -------------------------------------head-line
echo '<tr style="text-align: center;"><th></th><th colspan="2">This month</th><th colspan="2">This year</th><th colspan="2">All time</th></tr>';  
echo '<tr><th></th>
<th style="text-align: right;">value&nbsp;&nbsp;</th><th style="text-align: left;">DD</th>
<th style="text-align: right;">value&nbsp;&nbsp;</th><th style="text-align: left;">MM-DD</th>
<th style="text-align: right;">value&nbsp;&nbsp;</th><th style="text-align: left;">YYYY-MM-DD</th></tr>';  

#
foreach ($show as $type) {
        if (in_array ($type,  $ltxt_lows) ){ 
                $h_arrow = '&uarr;&nbsp;'; 
                $l_arrow = '&darr;&nbsp;';} 
        else {  $h_arrow = $l_arrow = '&varr;&nbsp;';;}
        echo '<tr>';
# first the name 
        echo '<td>'.$ltxt_type[$type].'</td>';
#
        foreach ($ltxt_col as $col => $text) {
                if ($col == 'type' || $col == 'crnt') {continue;}
                $high_value     = $low_value    = $high_date    =  $low_date    = 'n/a';
                if (isset ($hist[$type]['HghV'][$col]) )   {$high_value  = number_format ($hist[$type]['HghV'][$col],2,'.',''); }
                if (isset ($hist[$type]['LowV'][$col]) )   {$low_value   = number_format ($hist[$type]['LowV'][$col],2,'.',''); }
                if (isset ($hist[$type]['HghV_D'][$col]) ) {$high_date   = $hist[$type]['HghV_D'][$col]; }
                if (isset ($hist[$type]['LowV_D'][$col]) ) {$low_date    = $hist[$type]['LowV_D'][$col];}
                $dtHigh = $dtLow  =  $dtextra = '';
                $lngth  = 0;
                switch ($col) {
                   case 'all' :   $dtHigh  = date ('Y-', $high_date); 
                                  $dtLow   = date ('Y-', $low_date); 
                                  $lngth   = 2;
                   case 'year':   $dtHigh .= date ('m-', $high_date);
                                  $dtLow  .= date ('m-', $low_date );  
                                  $lngth++;
                   case 'month':  $dtHigh .= date ('d', $high_date); 
                                  $dtLow  .= date ('d', $low_date);  
                                  $lngth++;            
                } // eo switch
                $time_ft        = str_replace (':s','',$timeFormat); 
                switch ($col) {
                        case 'today':
                        case 'yday':
                                break;
                        default:# ---------- high value
$name   = "return[".$type."][HghV][".$col."]";
$date   = "return[".$type."][HghV_D][".$col."]";
$size   = $lngth*20;
echo  '<td style="text-align: right;">'.$h_arrow
        .'<input name="'.$name.'" type="text" style="width: 50px; text-align: right;" class="edit" value="'.$high_value.'">&nbsp;&nbsp;';
if (in_array ($type,  $ltxt_lows) ) 
     {  $name   = "return[".$type."][LowV][".$col."]";
        echo '<br />'.$l_arrow
                .'<input name="'.$name.'" type="text" style="width: 50px; text-align: right;" class="edit" value="'.$low_value.'">&nbsp;&nbsp;';}           
echo '</td>';       
echo  '<td style="text-align: left;">';
if ($type <> 'rain')        
      { echo '<input name="'.$date.'" type="text" style="width: '.$size.'px; text-align: center;" class="edit" value="'.$dtHigh.'">';}
if (in_array ($type,  $ltxt_lows) ) 
     {  $date   = "return[".$type."][LowV_D][".$col."]";
        echo '<br />'
                .'<input name="'.$date.'" type="text" style="width: '.$size.'px; text-align: center;" class="edit" value="'.$dtLow.'">';}
echo '</td>';
                                break;
                } // eo switch 
        } // eo each col
        echo '</tr>'.PHP_EOL;
} // eo each type 
echo '<tr style="text-align: center;">
<td colspan="7" class="value" style="background-color: green;"><div class="input">
  <input type="submit" style="width: 200px;" name="submit" class="button" value="Save updates">
  <input type="hidden" name="pw"    value="'.$password.'">
  <input type="hidden" name="year"  value="'.date ('Y').'">
  <input type="hidden" name="month" value="'.date ('m').'">
  
</div></td></tr>'.PHP_EOL;
echo '</table>
</div>'.PHP_EOL;
if (isset ($_REQUEST['test'] ) ) {echo '<!-- '.$stck_lst.' -->'; } 
echo ' </body>
</html>'.PHP_EOL;
#
# style is printed in the header 
function my_style()
     {  global $popup_css ;
        $return         = PHP_EOL.'    <style>'.PHP_EOL;
# load the genral css for pop-ups
        if (isset ($popup_css) && $popup_css <> false)  
             {  $return .= file_get_contents ($popup_css);}
             
# add pop-up specific css

        $return         .= '    </style>'.PHP_EOL;
        return $return;

 }
