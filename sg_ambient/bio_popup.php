<?php $scrpt_vrsn_dt  = 'bio_popup2.php|00|2020-03-23|';  # release 2004
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
$url_support    = 'https://pwsdashboard.com/';
$show_links     = false;        // false no URLs will be shown, only the icon and name
#
# ------------------------- translation of texts
$lng_info       = lang('Contact information');
$lng_mail       = lang('Mail');
$lng_twitter    = lang('Twitter');
$lng_website    = lang('Website');
$lng_support    = lang('Download and support');
#
# thanks to  w e a t h e r 34 for the nice icons
$mail_svg='        <svg id="i-mail" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="rgba(255, 93, 46, 1.000)" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
        <path d="M2 26 L30 26 30 6 2 6 Z M2 6 L16 16 30 6"></path>
        </svg>'.PHP_EOL;
$twitter_svg='        <svg id="i-twitter" viewBox="0 0 64 64" width="32" height="32">
        <path stroke-width="0" fill="rgba(0, 154, 171, 1.000)" d="M60 16 L54 17 L58 12 L51 14 C42 4 28 15 32 24 C16 24 8 12 8 12 C8 12 2 21 12 28 L6 26 C6 32 10 36 17 38 L10 38 C14 46 21 46 21 46 C21 46 15 51 4 51 C37 67 57 37 54 21 Z" />
        </svg>'.PHP_EOL;
$website_svg='        <svg id="i-website" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="rgba(144, 177, 42, 1.000)" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
        <path d="M10 29 C10 29 10 24 16 24 22 24 22 29 22 29 L10 29 Z M2 6 L2 23 30 23 30 6 2 6 Z" />
        </svg>'.PHP_EOL;
$support_svg='        <svg id="i-download" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="rgba(242, 58, 48, 1.000)" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
        <path d="M28 22 L28 30 4 30 4 22 M16 4 L16 24 M8 16 L16 24 24 16" />
        </svg>'.PHP_EOL;
#
# --- find the name of the server this script is running on
$url  = '';
if (isset($_SERVER['HTTPS'])) 
     { $url = 'https://'.$_SERVER['SERVER_NAME']; } else {$url = 'http://'.$_SERVER['SERVER_NAME'];}
if (isset($_SERVER['SCRIPT_NAME'])) 
     { $url  .= str_replace (basename(__FILE__), '', $_SERVER['SCRIPT_NAME']);}
#
echo '<!DOCTYPE html>
<html lang="'.substr($used_lang,0,2).'" >
<head>
  <meta charset="UTF-8">
  <title>contact '.$stationName.'</title>  
'. my_style().'
</head>
<body class="dark" style="text-align: left; overflow: hidden; ">'.PHP_EOL;
if ($show_close_x <> false ) // optional close text and large X
     {  echo '<div class="PWS_module_title" style="width: 100%; height: 20px; padding-top: 4px; font-size: 16px;" >
<span style="float: left;">&nbsp;X&nbsp;&nbsp;<small>'.$ltxt_clsppp.'</small></span>
</div>'.PHP_EOL; } else {echo '<br /><br />'; }
echo '<br />
<div style="width: 340px; background-color: rgba(45,56,68,.04); margin: 0 auto; color: #606060; overflow: hidden;">
<!-- darker header area -->
        <div style="width: 100%;  height: 40px; padding: 1rem;  background-color: #2d3844; color: #a7b6bd;">
            <div style="width: 78%; float: left;">
                <h3 style="font-size: 20px; margin: 0; margin-top: 5px;">'.$stationName .'</h3>
                <p  style="font-size: 14px; margin: 0;">'.$lng_info.'</p>
            </div>
        </div><!-- end of herader area -->'.PHP_EOL;
echo '<!-- item mail area -->
        <div style="width: 100%;   height: 40px; padding: 1rem;  background-color: #FFFFFF; border-bottom: 1px solid grey;">
        <a href="mailto:'.$email.'" style="color: inherit;">
            <div style="width: 20%; float: left;">'.$mail_svg.'</div>
            <div style="width: 78%; float: right;">
                <h3 style="font-size: 20px; margin: 0; margin-top: 5px;">'.$lng_mail .'</h3>
                <p  style="font-size: 14px; margin: 0;">';
        if ($show_links == true) {echo '<u>'.$email.'</u>';} else {echo '&nbsp;';}
        echo '</p>
            </div> 
        </a>
        </div>
        <!-- end of item mail area -->'.PHP_EOL;
if (isset ($twitterUser) && $twitterUser <> false) {    // this one is optional
        echo '<!-- item twitter area -->
        <div style="width: 100%;   height: 40px; padding: 1rem;  background-color: #FFFFFF; border-bottom: 1px solid grey;">
        <a href="https://twitter.com/'.$twitter.'" target="_blank" style="color: inherit;">
            <div style="width: 20%; float: left;">'.$twitter_svg.'</div>
            <div style="width: 78%; float: right;">
                <h3 style="font-size: 20px; margin: 0; margin-top: 5px;">'.$lng_twitter .'</h3>
                <p  style="font-size: 14px; margin: 0;">';
        if ($show_links == true) {echo '<u>'.$twitter.'</u>';} else {echo '&nbsp;';}
        echo '</p>
            </div> 
        </a>
        </div>
        <!-- end of item twitter area -->'.PHP_EOL;
        } 
else {echo '<!-- no twitter area printed, not a twitter user  -->'.PHP_EOL;}
#
echo '<!-- item website area -->
        <div style="width: 100%;   height: 40px; padding: 1rem;  background-color: #FFFFFF; border-bottom: 1px solid grey;">
        <a href="'.$url.'" target="_blank" style="color: inherit;">
            <div style="width: 20%; float: left;">'.$website_svg.'</div>
            <div style="width: 78%; float: right;">
                <h3 style="font-size: 20px; margin: 0; margin-top: 5px;">'.$lng_website .'</h3>
                <p  style="font-size: 14px; margin: 0;">';
        if ($show_links == true) {echo '<u>'.$url.'</u>';} else {echo '&nbsp;';}
        echo '</p>
            </div> 
        </a>
        </div>
        <!-- end of item website area -->'.PHP_EOL;
#
echo '<!-- item support area -->
        <div style="width: 100%;   height: 40px; padding: 1rem;  background-color: #FFFFFF; border-bottom: 1px solid grey;">
        <a href="'.$url_support.'" target="_blank"  style="color: inherit;">
            <div style="width: 20%; float: left;">'.$support_svg.'</div>
            <div style="width: 78%; float: right;">
                <h3 style="font-size: 20px; margin: 0; margin-top: 5px;">'.$lng_support .'</h3>
                <p  style="font-size: 14px; margin: 0;">';
if ($show_links == true) {echo '<u>'.$url_support.'</u>';} else {echo '&nbsp;';}
echo '</p>
            </div> 
        </a>
        </div>
        <!-- end of item support area -->'.PHP_EOL;
echo '</div><!-- eo enclosing div -->'.PHP_EOL;
if (isset ($_REQUEST['test']) ) { echo '<!-- '.PHP_EOL.$stck_lst.'-->';}
echo '</body>
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
