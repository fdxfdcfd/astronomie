<?php $scrpt_vrsn_dt  = 'image_popup.php|00|2020-04-14|';  # release 2004
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
# -------------------------------- load settings 
$scrpt          = 'PWS_settings.php'; 
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') include_once =>'.$scrpt.PHP_EOL;
include_once $scrpt; 
#
# -----------------------  general functions aso  
$scrpt          = 'PWS_shared.php'; 
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') include_once =>'.$scrpt.PHP_EOL;
include_once $scrpt;   
#-----------------------------------------------
#                                script settings
#-----------------------------------------------
$show_close_x   = $close_popup;  // set to false or true to overrde settings
$ltxt_clsppp    = lang('Close');
$ltxt_url       = 'Color map 24 hours';
#
#-------- set the link to your  image here
$first_img      = 'https://www.fourmilab.ch/cgi-bin/Earth?img=learth.evif&imgsize=320&dynimg=y&opt=-p&lat=&lon=&alt=&tle=&date=0&utc=&jd=';
$show_img       = $first_img;
#
if (strpos ('?',$show_img) == false)
     {  $extra  = '?_'.time();}
else {  $extra  = '&_'.time();}
#                                               IMPORTANT                                        
#  $extra  =   '';  // remove the comment mark if the link already uses a timestamp
#                                               IMPORTANT                                        
$show_img     = $show_img.$extra;
#
#  optional close X in the top-left.
if ($show_close_x == true )
     {  $closehtml = '<span style="position: absolute; top:0; left: 0; font-size: 14px; color: grey;">&nbsp;X&nbsp;&nbsp;<small>'.$ltxt_clsppp.'</small></span>';}
else {  $closehtml = ''; }

# stretched to fit
echo '<!DOCTYPE html>
<html lang="'.substr($user_lang,0,2).'"  style="width: 100%; height: 100%;">
<head>
    <meta charset="UTF-8">
    <title>'.$ltxt_url.'</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">'
.my_style().'
</head>
<body style="background: transparent url(\''.$show_img.'\') no-repeat fixed center;  background-size: 100% 100%; margin:  0;">'.PHP_EOL
.$closehtml.'
<span style="color: grey;">This image courtesy of <a style="color: grey;" href="https://www.fourmilab.ch/" target="_blank">Fourmilab Switzerland</a></span>
</body>
</html>';
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