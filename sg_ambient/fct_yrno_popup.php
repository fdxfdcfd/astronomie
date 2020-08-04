<?php $scrpt_vrsn_dt  = 'fct_yrno_popup.php|00|2020-04-05|';  # beta
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
$stck_lst        = basename(__FILE__).' ('.__LINE__.') version =>'.$scrpt_vrsn_dt.PHP_EOL;       
#
# -------------load weatherdata and all settings 
$scrpt          = 'PWS_livedata.php'; 
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') include_once =>'.$scrpt.PHP_EOL;
include_once $scrpt; 
#-----------------------------------------------
#                                script settings
#-----------------------------------------------
$show_close_x   = $close_popup;  // set to false or true to overrde settings // 
$ltxt_clsppp    = lang('Close');
#
$round_crnr             = 5;
if (isset ($_REQUEST['round']) || (isset ($use_round) && $use_round == true ) )  
     {  $round_crnr     = 50;}   
#
$b_clrs['maroon']       = 'rgb(208, 80, 65)';
$b_clrs['purple']       = '#916392';
$b_clrs['red']          = '#f37867';
$b_clrs['orange']       = '#ff8841';
$b_clrs['green']        = '#9aba2f';
$b_clrs['yellow']       = '#ecb454'; 
$b_clrs['blue']         = '#01a4b4';
#
$fll_uv  = array();
$fll_uv[0]  = $b_clrs['green'];
$fll_uv[1]  = $b_clrs['green'];
$fll_uv[2]  = $b_clrs['green'];
$fll_uv[3]  = $b_clrs['yellow'];
$fll_uv[4]  = $b_clrs['yellow'];
$fll_uv[5]  = $b_clrs['yellow'];
$fll_uv[6]  = $b_clrs['orange'];
$fll_uv[7]  = $b_clrs['orange'];
$fll_uv[8]  = $b_clrs['red'];
$fll_uv[9]  = $b_clrs['red'];
$fll_uv[10] = $b_clrs['red'];
$fll_uv[11] = $b_clrs['maroon'];
# -----------------   load general Aeris fct code
$scrpt          = 'fct_yrno_shared.php';
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') include_once  =>'.$scrpt.PHP_EOL; 
$return         = include_once $scrpt; 
#
if ($return == false) { return false;}  
#
if ($clockformat == '24') 
     {  $date_time_frmt = 'l  j  F';}
else {  $date_time_frmt = 'D M j';}
# ------------------------- translation of texts
$ltxt_url       = lang('Forecast');
#
$rows           = count ($parts);
$windlabel      = array ("North","NNE", "NE", "ENE", "East", "ESE", "SE", "SSE", "South",
         "SSW","SW", "WSW", "West", "WNW", "NW", "NNW");
$cl_headers     = array ('part' => '', 
                         'icon' => '<span style="float: left; padding-left: 10px;">'.lang('Conditions').'</span>', 
                         'temp' => lang('Temperature'), 
                         'rain' => lang('Precipitation'), 
                         'wspd' => lang('Windspeed'),
                         'wdir' => lang('Direction'), 
                         'uvuv' => lang('UV index'), 
                         'baro' => lang('Pressure')  );
$cl_cntnt       = array ('part','icon','temp', 'dewp', 'feel', 'humi', 'uvuv', 'r_ch', 'rain',
                         'baro','clds','wdir', 'wspd', 'wspd', 'desc');

/*  [part] => 2
    [isDay] => 1
    [unix] => 1586084400
    [wday] => Sunday    afternoon
    [from] => 13:00
    [to] => 18:00
    [temp] => 17
    [rain] => 0
    [baro] => 1019.1
    [wdir] => SSE
    [wdeg] => 156
    [wspd] => 20
    [bftt] => Moderate breeze
    [icnx] => 01d
    [icon] => clear_day
    [icnl] => ./pws_icons/clear_day.svg
    [desc] => Clear sky
    [temp_ft] => 17→19

 */
# -------   check if all data is present
$cols           = count($cl_cntnt);
$arr            = $parts[0]; # echo  '<pre>'.print_r($parts,true).'</pre>';  exit;
foreach ($cl_headers as $item => $text)
     {  if (!array_key_exists($item,$arr) ) 
             {  unset ($cl_headers[$item]);}
        }
$cols           = count($cl_headers); # echo '<pre>'.print_r($cl_headers);
$norain         = '-';
$nouv           = '-';
$color          = 
$clrwrm         = "#FF7C39";
$clrcld         = "#01A4B4";
#
echo '<!DOCTYPE html>
<html lang="'.substr($user_lang,0,2).'">
<head>
    <meta charset="UTF-8">
    <title>'.$ltxt_url.'</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">'
.my_style().'
</head>
<body class="dark" style="overflow: hidden;">
    <div class="PWS_module_title font_head" style="width: 100%; " >'.PHP_EOL;
if ($show_close_x <> false ) // optional close text and large X
     { echo '<div style="padding-top: 2px;"><span style="float: left;">&nbsp;X&nbsp;&nbsp;<small>'.$ltxt_clsppp.'</small></span></div>';}
echo '
    </div>'.PHP_EOL;
echo '<div class= "div_height" style="width: 100%;text-align: left; overflow: auto;">
<table class= "div_height font_head"  style=" width: 100%; margin: 0px auto; text-align: center; border-collapse: collapse;">'.PHP_EOL;

$head_str = '<tr style="border-bottom: 1px grey solid; color: black; background-color: darkgrey; ">';
foreach ($cl_headers as $key => $header)
     {  $head_str .='<td>'.$header.'</td>';}// print   the table headers
echo PHP_EOL.'</tr>'.PHP_EOL;

#
$ymd_old        = 0;
#
for ($n = 0; $n < $rows; $n++) // print 1 row / daypart with all data in coloms
     {  $arr    = $parts[$n]; #  echo  '<pre>'.print_r($arr,true); exit;   
        # ffirst check if new day arraived:
        $ymd    = date('Ymd',$arr['unix']);
        if ($ymd <> $ymd_old)
             {  $sunrise = date_sunrise($arr['unix'], SUNFUNCS_RET_TIMESTAMP, $lat, $lon);
                $rise   = date($timeFormatShort,$sunrise);
                $sunset = date_sunset($arr['unix'], SUNFUNCS_RET_TIMESTAMP, $lat, $lon);
                $set    = date($timeFormatShort,$sunset);
                $length = $sunset -  $sunrise;
                $length = date('H:i',$length); 
                $length = str_replace (':',' '.lang ('hrs '),$length);
                $length .= ' '.lang ('mins ');

                $day_nm = trans_long_date(date($date_time_frmt,$arr['unix']));
                echo '<tr style="border-bottom: 1px grey solid; color: black; background-color: darkgrey;  height: 22px;" ><td colspan="'
                .$cols.'"><span style="padding: 4px; font-size: 14px;">&nbsp;<b>'
                .$day_nm.'</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<img src="img/sunrise.png" alt="sunrise">&nbsp;&nbsp;'.$rise.'&nbsp;&nbsp;
<img src="img/sunset.png" alt="sunset">&nbsp;&nbsp;'.$set.'&nbsp;&nbsp;&nbsp;'.lang ('Daylight') .': '.$length.'&nbsp;</span></td></tr>'.PHP_EOL.$head_str;	        
                $head_str = '';
                $ymd_old  = $ymd;}
        echo '<tr style="border-bottom: 1px grey solid; ">';
             
        if ($arr['isDay']) {$colorx = $clrwrm; } else {$colorx = $clrcld;}
        foreach ($cl_headers as $key => $header)  // for every data value = column we want to print
            {   if (isset ($arr[$key]) ) 
                     {  $content  = $arr[$key]; } 
                else {  $content = 'n/a';}
                switch ($key){
                    case 'part': 
                        echo PHP_EOL.'<td><span style="color: '.$colorx.';">'.$arr['pday'].'</span><span><br />'.$arr['from'].' -  '.$arr['to'].'</span>'; 
                        break;
                    case 'icon':
                        $icon   = $icn_prefix.$content.$icn_post;
                        echo PHP_EOL.'<td><span style="float: left;"><img src="'.$icon.'" width="60" height="32" alt="'.$content.'" style="vertical-align: middle;">';
                        echo $arr['desc'].'</span>';
                        break;
                    case 'temp': 
#                        echo PHP_EOL.'<td><span style="font-size: 20px; color: '.$color.';">'.$arr['temp_ft'].'&deg;</span>';
                        $tclr  =  $arr['tclr'];
                        echo PHP_EOL.'<td><span style="font-size: 20px; color: '.$tclr.';">'.$arr['temp'];
                        $tclr  =  $arr['tclr'];
                        if ($n < $rows - 1)
                             {  $tclr   =  $parts[$n+1]['tclr'];
                                $temp   =  $parts[$n+1]['temp']; 
                                echo '</span>&rarr;<span style="font-size: 20px; color: '.$tclr.';">'.$temp;}
                        echo '&deg;</span>';
                        break;
                    case 'rain': 
                        echo PHP_EOL.'<td>';
                        $amount = (float) $arr['rain']; 
                        if ($amount == 0) 
                             {  echo $norain; 
                                break; }
                        echo  $amount.'<small> '.lang($rainunit).'</small>';
                        break;              
                    case 'wspd':  
                        echo PHP_EOL.'<td>'.$content.'<small> '.lang($windunit).'</small>&nbsp;&nbsp;&nbsp;'.$arr['bftt'];
                        break;
                    case 'wdir': 
                        echo PHP_EOL.'<td>';
                        echo '<img src="img/windicons/'.$content.'.svg" width="20" height="20" alt="'.$content.'"  style="vertical-align: bottom;"> ';
                        echo lang($content);
                        break;
                    case 'uvuv': 
                        echo PHP_EOL.'<td>';
                        $value  = trim($content);
                        if ($value == '' || $value == '0')   
                             {  echo $nouv;  break;}
                        $value  = (int) $content;
                        if ($value > 11) {$value = 11;}  
                        echo '<div class="my_uv" style="background-color:'.$fll_uv[$value].'; ">'.(int) $content.'</div>';
                        break;
                    case 'baro':
                        echo PHP_EOL.'<td>'.$content.'<small> '.lang($pressureunit).'</small>';
                        break;         
                   # default: echo $n.'-'.$i.'-'.$content; exit;
                }              
                echo '</td>';
                } // eo coloms
        echo PHP_EOL.'</tr>'.PHP_EOL;
} // eo rows
echo '<tr><td  colspan="'.$cols.'">
<span style="float: right; font-size: 10px;">
<a href="'.$parts[0]['credit_link'].'" target="_blank" style="color: grey">'
.$parts[0]['credit_text'].'</tr></table>
</div>'.PHP_EOL;
if (isset ($_REQUEST['test'] ) ) {echo '<!-- '.$stck_lst.' -->'; } 
echo ' </body>
</html>'.PHP_EOL;
#
# style is printed in the header 
function my_style()
     {  global $popup_css ,$round_crnr;
        $return         = PHP_EOL.'    <style>'.PHP_EOL;
# load the genral css for pop-ups
        if (isset ($popup_css) && $popup_css <> false)  
             {  $return .= file_get_contents ($popup_css);}
# add pop-up specific css
        $return .= '
        .my_uv  { background-color: lightgrey;  margin: 0 auto; border-radius: '.$round_crnr.'%;
                    height: 20px; width: 20px;  color: #fff;
                    line-height: 20px;font-size: 16px;
                    font-family: Helvetica,sans-seriff;
                    border: 1px solid #FFFFFF;}
         .rTxt { padding: 3px; text-align: right; float: right;  }         '.PHP_EOL;  
        $return         .= '    </style>'.PHP_EOL;
        return $return;
 }
 
function trans_long_date ($date)
     {  $from   = array ( 
                'Apr ','Aug ','Dec ','Feb ','Jan ','Jul ','Jun ','Mar ','May ','Nov ','Oct ','Sep ',
                'April','August','December','February','January','July','June','March','May','November','October','September',
                'Mon ','Tue ','Wed ','Thu ','Fri ','Sat ','Sun ',
                'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
        foreach ($from  as $txt) {$to_dates[] = lang($txt).' ';} # echo '-'.$txt.'-'.lang($txt).PHP_EOL;
        return str_replace ($from, $to_dates, $date);
        }       
