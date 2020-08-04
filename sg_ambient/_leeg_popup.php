<?php  $scrpt_vrsn_dt  = '_leeg_popup.php|00|2020-03-21|'; # empty example popup
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
# ------------------------- translation of texts
$ltxt_url       = lang('test Information');

$ltxt_hd1       = lang('head1');
$ltxt_hd2       = lang('head2');
$ltxt_hd3       = lang('head3 Information');
$ltxt_hd4       = lang('head4 Information');
#
echo '<!DOCTYPE html>
<html lang="'.substr($user_lang,0,2).'">
<head>
    <meta charset="UTF-8">
    <title>'.$ltxt_url.'</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
'.my_style().'
</head>
<body class="dark">
    <div class="PWS_module_title" style="width: 100%; height: 24px; font-size: 20px;" >
    <span style="float: left;">&nbsp;X&nbsp;&nbsp;<small>'.$ltxt_clsppp.'</small></span>'
.$ltxt_url.'
    </div>
    <br />
    <div class="PWS_weather_container"><!-- toprow -->
        <div class="PWS_weather_item" style="position: relative;"><!-- weatheritem 1 -->
            <div class="PWS_module_title"><div class="title">'.$ltxt_hd1.'</div></div>';
#
echo   '<br />insert code for first row left block';
#
echo '          
        </div><!-- eo weatheritem 1 -->
        <div class="PWS_weather_item"><!-- weatheritem 2 -->
        <div class="PWS_module_title"><div class="title">'.$ltxt_hd2.'</div></div>';
#
echo   '<br />insert code for first row right block';
#
echo '          
        </div><!-- eo weatheritem 2 -->
    </div><!-- eo toprow -->
    <div class="PWS_weather_container"><!-- second row -->
        <div class="PWS_weather_item " style="position: relative;"><!-- weatheritem 3 info -->
        <div class="PWS_module_title"><div class="title">'.$ltxt_hd3.'</div></div>';
#
echo   '<br />insert code for bottom row left block';
#
echo '          
        </div><!-- eo weatheritem 3 -->
        <div class="PWS_weather_item " style="position: relative;"><!-- weatheritem  4 info -->
        <div class="PWS_module_title"><div class="title">'.$ltxt_hd4.'</div></div>';
#
echo   '<br />insert code for bottom row right block';
#
echo '          
        </div><!-- eo weatheritem details sun --> 
    </div><!-- eo second row -->'.PHP_EOL;

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
