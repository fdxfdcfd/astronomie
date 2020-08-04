<?php  $scrpt_vrsn_dt  = 'PWS_mydata_convert.php|00|2019-12-15|';    # release 1912
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
#if (!isset($_REQUEST['pw']) || trim($_REQUEST['pw']) <> $password) { die ('Sorry - not possible');}
#
#-----------  text fileds used - no translations
$ltxt_url       = 'Convert PWS07 units to default units';
$ltxt_clsppp    = 'Close';
#
function color_unit ($unit1,$unit2){
        if ($unit1 <> $unit2) {$style=' style="color: red;"';} else {$style='';}
        return '&nbsp;<b'.$style.'>'.$unit1.'</b>'; }
#
$o_units         = array();
$old_unts['AWapi']      = array ('temp'  => $amb_u_temp, 'baro' => $amb_u_baro, 'wind' => $amb_u_wind,  'rain' => $amb_u_rain);
$old_unts['wf']         = array ('temp'  => $wfl_u_temp, 'baro' => $wfl_u_baro, 'wind' => $wfl_u_wind,  'rain' => $wfl_u_rain);
$old_unts['wu']         = array ('temp'  => $wu_temp,    'baro' => $wu_baro,    'wind' => $wu_wind,     'rain' => $wu_rain);
$old_unts['DWL']        = array ('temp'  => 'f',         'baro' => 'inhg',      'wind' => 'mph',        'rain' => 'in');
#$old_unts['rt']         = array ('temp'  => $data_temp,  'baro' => $data_baro,  'wind' => $data_wind,   'rain' => $data_rain);
#$old_unts['cltrw']      = array ('temp'  => $data_temp,  'baro' => $data_baro,  'wind' => $data_wind,   'rain' => $data_rain);
#$old_unts['cltrw']      = array ('temp'  => $data_temp,  'baro' => $data_baro,  'wind' => $data_wind,   'rain' => $data_rain);
$old_unts['other']      = array ('temp'  => $data_temp,  'baro' => $data_baro,  'wind' => $data_wind,   'rain' => $data_rain);
#
$stck_lst       .= basename(__FILE__).' ('.__LINE__.') livedataFormat = '.$livedataFormat.PHP_EOL;       
#
if ($livedataFormat == 'AWapi' || $livedataFormat == 'wf' || $livedataFormat == 'wu' || $livedataFormat == 'DWL' )
     {  $unit_old       = $old_unts[$livedataFormat]; }
else {  $unit_old       = $old_unts['other'];}
$from   = array();
$to     = array();
$from['temp']   = trim(strtolower($unit_old['temp']));
$from['baro']   = trim(strtolower($unit_old['baro']));
$from['wind']   = trim(strtolower($unit_old['wind']));
if ($livedataFormat == 'wd' || $livedataFormat == 'meteohub' || $livedataFormat == 'wswin') {$from['wind']   = 'm/s';}
$from['rain']   = trim(strtolower($unit_old['rain']));
$to['temp']     = trim(strtolower($weather['temp_units']));
$to['baro']     = trim(strtolower($weather['barometer_units']));
$to['wind']     = trim(strtolower($weather['wind_units']));
$to['rain']     = trim(strtolower($weather['rain_units']));
# date,MAX_outsideTemp,MIN_outsideTemp,MAX_dewpoint,MIN_dewpoint,MAX_raintoday,MAX_windgustmph,MAX_windSpeed,MAX_radiation,MAX_barometer,MIN_barometer,SUM_lightning,MAX_UV,
$convert_flds   = $convert_strt = array (false, 'temp', 'temp', 'temp', 'temp', 'rain', 'wind', 'wind', false, 'baro', 'baro', false, false);
#print_r($from); print_r($to); exit;
if ($to['temp'] == $from['temp']) {$convert_flds[1] = $convert_flds[2] = $convert_flds[3]= $convert_flds[4] =false;}
if ($to['baro'] == $from['baro']) {$convert_flds[9] = $convert_flds[10] = false;}
if ($to['wind'] == $from['wind']) {$convert_flds[6] = $convert_flds[7] = false;}
if ($to['rain'] == $from['rain']) {$convert_flds[5] = false;}
#
$convert = false;
foreach ($convert_flds as $value) { if ($value <> false) { $convert = true;} }
#
if ($convert == false)     {   die ('File needs no conversion.'); } #echo '$convert_flds='.print_r($convert_flds,true); 
#
$old_file       = '../old/2019.txt';
$old_file       = '../old/2020.txt';
$lines          = file($old_file); # echo 'old file='.print_r($lines); exit;
$new_file       = $lines[0];
$fields         = explode(',',$new_file);
echo 'Following fields will be converted:'.PHP_EOL;
echo '</pre><table><tr><th>nr</th><th>header</th><th>from</th><th>to</th></tr>'.PHP_EOL;
for ($n = 0; $n < count($convert_flds); $n++)
     {  $old    = 'n/a';
        $new    = 'same';
        $key    = $convert_strt[$n];
        if ($key <> false) 
             {  $old    = $from[$key];} #  echo __LINE__.' '.$key.' '.$old; exit;
        $key    = $convert_flds[$n];
        if ($key <> false) 
             {  $new    = $to[$key];}
        echo '<tr><td>'.($n+1).'</td><td>'.$fields[$n].'</td><td>'.$old.'</td><td>'.$new.'</td></tr>'.PHP_EOL;}
echo '</table>'.PHP_EOL.'<pre>';
$count          = count($lines);
for ($n = 1; $n < $count; $n++)
     {  $arr            = explode (',',trim($lines[$n]));
        $cnt_flds       = count ($arr);
        for ($i = 0; $i < $cnt_flds; $i++)
             {  $field  = $arr[$i];
                $convert= $convert_flds[$i];
                if ($convert <> false)
                     {  switch ($convert) {
                                case 'temp': $field  = convert_temp   ($field, $from['temp'],  $to['temp']);break;
                                case 'baro': $field  = convert_baro   ($field, $from['baro'],  $to['baro']);break;
                                case 'wind': $field  = convert_speed  ($field, $from['wind'],  $to['wind']);break;
                                case 'rain': $field  = convert_precip ($field, $from['rain'],  $to['rain']);break;
                        }  // eo switch
                } // not false
                $new_file       .= $field.',';
        }  // eo each filed
        $new_file       .= PHP_EOL;
} // eo each line;
echo $new_file;

echo $stck_lst;

