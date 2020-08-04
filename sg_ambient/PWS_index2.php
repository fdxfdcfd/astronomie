<?php  $scrpt_vrsn_dt  = 'PWS_index2.php|00|2020-05-03|';  # empty last block | release 2004  | img width | manifest 
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
   header("Accept-Ranges: bytes");
   header("Content-Length: $download_size");
   header('Connection: close');
   readfile($filenameReal);
   exit;}
elseif (!isset ($_REQUEST['test'])) 
     {  ini_set('display_errors', 0); error_reporting(0);}
else {  ini_set('display_errors', 1); error_reporting(1);}  
# -------------------save list of loaded scrips;
$stck_lst       = basename(__FILE__).'('.__LINE__.')  loaded  =>'.$scrpt_vrsn_dt.PHP_EOL;     
#
$read_net_data  = true;
#
$scrpt          = 'PWS_livedata.php';
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') include  =>'.$scrpt.PHP_EOL;
include $scrpt;
#-----------------------------------------------
#                                script settings
#-----------------------------------------------
$clr_offline    = '#ff8841';
$clr_online     = 'green';

if ($current_theme == 'light') {
$clr_htmlbg     = '#dcdcdc';    /* bckground  html      */
$clr_mnbg       = '#efeded';    /* bckground  menu/header   */
$clr_hdrdrk      = '#cecece';   /* bckground  text headers    */
$clr_hdrbg      = '#e0e0e0';    /* bckground  block title    */
$clr_blckbg     = '#efeded';    /* bckground  block     */
$clr_brdr       = '#F0F0F0';    /* color  border        */
$clr_txt        = '#000';       /* color  text          */ 
} elseif ($current_theme == 'user') {
$clr_htmlbg     =  'rgba(233,241,226)';    /* bckground  html      */
$clr_mnbg       = '#b0cba0';    /* bckground  menu/header   */
$clr_hdrdrk     = '#669966';    /* bckground  text headers    */
$clr_hdrbg      = '#b0cba0';    /* bckground  block title    */
$clr_blckbg     = 'rgba(233,241,226, 0.4)';    /* bckground  block     */
$clr_brdr       = '#108400';    /* color  border        */
$clr_txt        = '#000';       /* color  text          */
$clr_offline    = '#f37867'; 
} else  {
$clr_htmlbg     = '#151819';    /* bckground  html      */
$clr_mnbg       = '#393D40';    /* bckground  menu/header   */
$clr_hdrdrk     = '#5b6165';    /* bckground  text headers   */
$clr_hdrbg      = '#787f841a';    /* bckground  headers   */
$clr_blckbg     = '#24262B';    /* bckground  block     */
$clr_brdr       = 'transparent';       /* color  border        */
$clr_txt        = '#aaa';       /* color  text          */ 
}
$strng_style = '
html          { color: '.$clr_txt.'; 
                background-color: '.$clr_htmlbg.'; }
a             { color: '.$clr_txt.'; }
h1            { background-color: '.$clr_mnbg.'; }
.PWS_weather_item , .PWS_weather_item_s
              { border-color: '.$clr_brdr.';
                background-color: '.$clr_blckbg.'; }
.PWS_module_title 
              { background-color: '.$clr_hdrbg.'; }
.sidebarMenuInner .separator    { border-top: 1px  '.$clr_hdrdrk.' solid; 
                border-bottom: 1px  '.$clr_hdrdrk.' solid;  } 
.PWS_bar      { color: '.$clr_hdrbg.';}
.PWS_border   { border-color: '.$clr_brdr.';}
.PWS_offline  { color: '.$clr_offline.';}
.PWS_online   { color: '.$clr_online.';}
#sidebarMenu 
              { background-color: '.$clr_mnbg.'; }
';
#
if (isset ($_REQUEST['noborders']) ) {$txt_border = 'border: none;';}
elseif (! isset ($txt_border) )      {$txt_border = '';}
elseif ($txt_border == true)         {$txt_border = '';}
else                                 {$txt_border = 'border: none;';}
#
header('Content-type: text/html; charset=UTF-8');
echo '<!DOCTYPE html>
<html  lang="'.substr($used_lang,0,2).'"  class="'.$current_theme.'" >
'; 
$frame_ok       = false; // no invalid frames allowed
$title_text     = ' Home Weather Station ('.$livedataFormat.' version)';
#
if (isset ($_REQUEST['frame']) && strlen(trim($_REQUEST['frame']) < 20 ) )
     {  $scrpt          = 'PWS_frames.php';
        $stck_lst      .= basename(__FILE__).' ('.__LINE__.') include  =>'.$scrpt.PHP_EOL;
        include $scrpt;
        #
        if (array_key_exists ($_REQUEST['frame'], $frm_ttls) )
             {  $key    = $_REQUEST['frame'];
                if ( isset ($frm_ttls[$key]) )  { $title_text = ' '.$frm_ttls[$key];}
                $frame_ok       = true;}
} // eo check for menupage / frame
?>
<head>
<title><?php echo $stationlocation.$title_text; ?></title>
<meta content="Personal Home weather station with the weather conditions for <?php echo $stationlocation;?>" name="description">
<!-- Facebook Meta Tags -->
<meta property="og:url" content="">
<meta property="og:type" content="website">
<meta property="og:title" content="PWS_Dashboard at <?php echo $stationlocation;?>">
<meta property="og:description" content="Personal Weather Station with the weather conditions for <?php echo $stationlocation;?>">
<!-- Twitter Meta Tags -->
<meta property="twitter:card" content="summary">
<meta property="twitter:url" content="">
<meta property="twitter:title" content="">
<meta property="twitter:description" content="Weather conditions for <?php echo $stationlocation;?>">
<meta content="INDEX,FOLLOW" name="robots">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name=apple-mobile-web-app-title content="Personal Weather Station">
<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#ffffff">
<link rel="manifest" href="css/manifest.json">
<link rel="icon" href="img/icon.png" type="image/x-icon" />
<link href="css/featherlight.css" type="text/css" rel="stylesheet" />
<script src="js/jqueryv341.js"></script>
<script src="js/featherlight.js"></script>
<style>
.featherlight .featherlight-inner {background:url(./img/loading.gif) top center no-repeat;  }        
.featherlight-content    { background: transparent; max-width: 850px; width: 100%;} 
.featherlight-content .featherlight-close-icon { top: 0px; left: 0px; width: 60px; height: 20px; background: transparent;}
.featherlight-content iframe {width: 100%; height: 100%;} 
@keyframes fadeOut {  0% {opacity: 2;} 50% {opacity: 1;}100% {opacity: 0;}} 

*, html       { box-sizing: border-box;       
                text-align: center; 
                font-family: arial,sans-serif;}
body          { margin: 0 auto; 
                padding: 0;    
                font-size: 14px;  
                line-height: 1.2;}                               
small         { line-height: 12px;}
sup           { vertical-align: 20%;
                font-size: smaller;}
a             { text-decoration: none;}
div           { display: block;}
h1            { font-size: 15px;}
img           { vertical-align: middle;}
.PWS_weather_container 
              { display: flex; 
                justify-content: center; flex-wrap: wrap; flex-direction: row; align-items: flex-start;
                overflow: hidden; 
                margin: 0 auto;}
.PWS_weather_item 
              { position: relative; 
                width: 316px; min-width: 316px; float: left;
                height: 192px; 
                margin: 2px;
                border: 1px solid #000;  
                font-size: 12px;  }
.PWS_weather_item_s 
              { position: relative; 
                min-width: 200px; float: left;
                height: 80px; 
                margin: 2px;
                border: 1px solid #000;  
                font-size: 12px; }
.PWS_module_title 
              { width:100%;  
                height: 18px; 
                border: none;}
.PWS_module_content 
              { font-size: 10px; 
                vertical-align: middle;}             
.PWS_ol_time  { margin-top: -14px; 
                margin-right: 2px; 
                font-size: 10px;
                line-height: 10px; 
                float: right;} 
   
.PWS_left    { float: left;  width: 86px;  margin-left:  2px;  border: none;}
.PWS_right   { float: right; width: 86px;  margin-right: 2px;  border: none; }
.PWS_middle  { float: left;  width: 136px; margin: 0 auto;      border: none; }
.PWS_2_heigh { height: 80px; vertical-align: middle;}
.PWS_3_heigh { height: 53px; vertical-align: middle;}
.PWS_4_heigh { height: 40px; vertical-align: middle;}
.PWS_div_left{ height: 28px; margin: 0 auto; margin-top: 10px; font-size: 10px; 
                border-radius: 3px; border: 1px solid silver; 
                border-right: 3px solid silver;  padding: 1px; <?php echo $txt_border; ?>}    
.PWS_div_right{ height: 28px; margin: 0 auto; margin-top: 10px; font-size: 10px; 
                border-radius: 3px; border: 1px solid silver; 
                border-left: 3px solid silver;  padding: 1px; <?php echo $txt_border; ?>}   
       
.orange      { color: #ff8841;}
.green       { color: #9aba2f;}
.blue        { color: #01a4b4;}
.yellow      { color: #ecb454;}
.red         { color: #f37867;}
.purple      { color: #916392;}
.maroon      { color: rgb(208, 80, 65);}
.grey        { color: #aaaaaa;}
.large       { font-size: 20px;}
.narrow      { width: 100px;}      

.PWS_bucket {   
        height:105px; width:108px;
        border:         4px solid  silver;
        border-top:     1px dotted rgb(233, 235, 241);
        background:     url("img/rain/marker.png");
        background-size:cover;
        margin: 0px auto;}
.PWS_bucket .water {
        background:     url("img/rain/water.png");
        border: 0px;}
.PWS_bucket .clouds {
        background:     rgba(159, 163, 166, 0.4);
        border:         0px;
        border-top:     1px dotted rgb(255, 124, 57);}
.PWS_bucket .empty {
        background-color: transparent;
        border: 0px;}
.PWS_border             {  border: 1px solid silver; }
.PWS_notify 
    {   width:  250px;
        right:  10px;
        top:    120px;
        z-index: 9999;
        position: fixed;
        font-family: Arial, Helvetica, sans-serif;
        animation-fill-mode: both;
        animation-name: fadeOut;       }
.PWS_notify_box 
    {   position: relative;
        min-height: 80px;
        margin-bottom: 8px;
        font-size: 15px;
        background: rgb(97, 106, 114)}
.PWS_notify_box .PWS_notify_header
    {   position: relative;
        height: 26px;
        color: #aaa;
        background-color: rgb(61, 64, 66);} 
.PWS_notify_box .content
    {   padding: 8px;
        background: rgba(97, 106, 114, 1);
        color: #fff;
        text-align: center;}
.PWS_notify_box .PWS_notify_left
    {   float: left;
        text-align: left;
        padding: 3px;}
.PWS_notify_box .PWS_notify_right
    {   float: right;
        text-align: right;
        padding: 3px;}
@media screen and (max-width: 639px) {
        .PWS_weather_item, .PWS_weather_item_s {margin: 2px auto 0; float: none; width: 320px;}
        .invisible {display: none;}
        .cposition4 {display: none;}
        .cposition3 {display: none;}
        .cposition2 {display: none;}
        .featherlight-content {height: 250px;}
}
@media screen and (min-width: 640px){
        .PWS_weather_container {width: 640px;}
        .cposition4 {display: none;}
        .cposition3 {display: none;}
/*        .cposition2 {display: none;} */
        .PWS_weather_item_s {width: 209px;}
        .featherlight-content {height: 350px;}
}
@media screen and (min-width: 850px){
        .featherlight-content {height: 550px;}
}
@media screen and (min-width: 960px)  {
        .PWS_weather_container {width: 960px;}
        .cposition4 {display: block;}
        .cposition3 {display: none;}
        .PWS_weather_item_s {width: 236px;}
        .featherlight-content {height: 550px;}
}
<?php  
$wide   = true;  
if     (isset ($_REQUEST['tall']) ) 
     {  $wide = false;
        if ($extra3used  == 'wide') {$extra3used  = 'row';}
        }
elseif (isset ($_REQUEST['wide']) ) 
     {  if ($extra3used  == 'row') {$extra3used  = 'wide';}
        }
if ($extra3used  == 'none')     {$wide = false;}
elseif ($extra3used  == 'row')  {$wide = false;}

if ($wide == true) {  ?>
@media screen and (min-width: 1280px) {
        .PWS_weather_container {width: 1280px;}
        .cposition4 {display: block;}
        .cposition3 {display: block;}
        .PWS_weather_item_s {width: 252px;}}
<?php  }  ?>
#sidebarMenu {   
    position: absolute;
    left: 0;
    width: 240px;
    top: 0;
    transform: translateX(-250px);
    transition: transform 250ms ease-in-out;
    float: left;
    z-index: 30}
.sidebarIconToggle, input[type=checkbox] {
    transition: all .3s;
    box-sizing: border-box}
.sidebarMenuInner {
    margin: 0;
    padding: 0;
    width: 240px;
    float: left;}
.sidebarMenuInner li {
    list-style: none;
    padding: 5px 5px 5px 10px;
    cursor: pointer;
    border-bottom: 0;
    float: left;
    width: 240px;
    font-size: 12px;
    font-weight: 400}
.sidebarMenuInner .separator {
    cursor: default;
    margin: 5px 0px;
    font-weight: bold;
}
.sidebarMenuInner li a {
    cursor: pointer;
    text-decoration: none;
    float: left;
    font-size: 12px;}
.sidebarMenuInner li a:hover {
    color: #f5650a;}
    
    
input[type=checkbox]:checked ~ #sidebarMenu {
    transform: translateX(0)}
input[type=checkbox] {
    display: none}
.sidebarIconToggle {
    cursor: pointer;
    position: absolute;
    z-index: 99;
    top: 22px;
    left: 15px;
    height: 22px;
    width: 22px}

<?php 
if (isset ($_REQUEST['round']) || (isset ($use_round) && $use_round == true ) )  { ?>
.PWS_round { border-radius: 50%;}
<?php } else {  ?>
.PWS_round { border-radius: 3px;}
<?php }

echo $strng_style; ?>



</style>
</head>
<?php if (isset ($_REQUEST['stripall'])) {$stripall='display: none; '; } else {$stripall='';} ?>
<body style="height: 100%; background: transparent; <?php if ($stripall <> '') { echo " overflow: hidden; ";} ?>">
<!-- begin top layout -->
<?php 
echo '<h1  style="'.$stripall.'padding: 10px; padding-top: 15px;  margin: 0; height: 44px;" >'.PHP_EOL;
if(trim($units_used) != 'us') { $o_units = 'us'; $text='F'; } else  { $o_units = 'metric'; $text='C'; } 
echo '<span class="" style="float: right; margin-right: 10px;">
<a class="" href="./index.php?units='.$o_units.'">
<span style="display: flex; color: white; border-radius: 3px; box-sizing: content-box;
        width: 18px; height: 18px; padding: 1px; 
        background: #ff7c39; font-weight: 600; font-size: 16px;
        align-items: center; justify-content: center;">&deg;'.$text.'</span>
</a>
</span>'.PHP_EOL;
?>
<b class=" invisible" ><?php echo $stationName.'&nbsp; &#8226;&nbsp; '.$stationlocation; ?></b>
<span id="positionClock" style="float: left; width: 30px; display: block; background: transparent" class="invisible">
&nbsp;
</span>
</h1>
<!-- end top layout -->
<?php
#
if ($frame_ok == true)
     {  $frame = $key;
        if ( isset ($frm_type[$key]) )  { $type  = $frm_type[$key]; } else { $type  = 'frame'; }
        if (!isset ($frm_wdth[$frame])) 
                     {  $width = 'width: 100%;';
                        $pagew = '';} 
                else {  $width = 'width: '.$frm_wdth[$frame].'px;';
                        $pagew = ' style="'.$width.'"'; }
        echo '<!-- begin frame or extra page  -->
<div class="PWS_weather_container " '.$pagew.'>'.PHP_EOL;              
        if ($type == 'frame') { echo '<iframe id="'.$frame.'" title="'.$frame.'" 
        style=" '.$width.' height: '.$frm_hgth[$frame].'px; background: white url(./img/loading.gif) top center no-repeat; margin: 2px 2px auto; border: none;"
        src="'.$frm_src[$frame].'">
        </iframe>'.PHP_EOL;}
        #
        if ($type == 'img') 
             {  if (!isset ($frm_wdth[$frame])) 
                     {  $width = 'max-width: 100%; height: auto;';} 
                else {  $width = 'max-width: '.$frm_wdth[$frame].'px; width: 100%; height: auto;';}
                echo '
<img src="'.$frm_src[$frame].'"  alt="'.$frame.'" 
style="'.$width.' margin: 2px 2px auto;  padding: 2px; ">
        '.PHP_EOL;} #  width: 100%; max-height: '.$frm_hgth[$frame].'px;
        # 
        if ($type == 'div') { include $frm_src[$frame];}
        #
        echo '<!-- end of container for external scripts -->
</div>'.PHP_EOL;        
        } // eo check and optional display extra page
else { // there was no valid frame / optional page
#
        $scrpt          = 'PWS_blocks.php';
        $stck_lst      .= basename(__FILE__).' ('.__LINE__.') include  =>'.$scrpt.PHP_EOL;
        include $scrpt;
        if (isset ($_REQUEST['test']) ) { echo '<!-- '.PHP_EOL.$stck_lst.'-->'.PHP_EOL; $stck_lst='';}
        if ($stripall == '') {
                $scrpt          = 'clock_c_small.php';
                $stck_lst      .= basename(__FILE__).' ('.__LINE__.') include  =>'.$scrpt.PHP_EOL;
                include $scrpt;
        #
                function  PWS_weather_item ($id,$script)
                     {  global $info, $blck_ttls, $wdth;
                        echo '<div class="PWS_weather_item_s c'.$id.'" style="height: 80px; '.$wdth.' ">
                    <div class="PWS_module_title" id="'.$id.'mt"><span style="position: relative;  top: 2px;" id="'.$id.'mt_s">'.$blck_ttls[$script].'</span></div>
                    <script> var id_blck= "'.$id.'"; </script>
                    <div id="'.$id.'">'.PHP_EOL;}
                $end_block= '
                    </div>
                </div>'.PHP_EOL;
                #
                echo '<div class="PWS_weather_container" style="clear: both; " >'.PHP_EOL;
                PWS_weather_item ('positionlast',$positionlast);echo '<br /><img src="./img/loading.gif" alt="loading">'; echo $end_block; 
                PWS_weather_item ('position1',$position1); include $position1; echo $end_block;
                PWS_weather_item ('position2',$position2); include $position2; echo $end_block;
                if ($extra3used == 'wide') { 
                        PWS_weather_item ('position3',$position3); include $position3; echo $end_block; }
                        PWS_weather_item ('position4',$position4); include $position4; echo $end_block; 
                        echo '</div> <!--end top layout -->'.PHP_EOL;
                } // eo stripall
# build middle part of page with x * x blocks
        $PWSpopup = '<svg viewBox="0 0 32 32" width="12" height="10" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="10%">
        <path d="M14 9 L3 9 3 29 23 29 23 18 M18 4 L28 4 28 14 M28 4 L14 18"></path>
        </svg>'.PHP_EOL;

        $class_extra= '';#'invisible_wide';
function  weatheritem ($id, $script,$class_extra='') 
    {   global $blck_ttls, $blck_ppp, $PWSpopup;
        echo '  <div class="PWS_weather_item '.$class_extra.'"><!-- '.$id.' '.$script.' -->
    <div class="PWS_module_title"><span style="position: relative;  top: 2px;">'.$blck_ttls[$script].'</span></div>
    <div id="'.$id.'" style="height: 154px;">'.PHP_EOL;
    }
function  weatherbottom ($script)
     {  global $blck_ttls, $blck_ppp, $PWSpopup, $stripall;
        echo '    </div>
    <div class="PWS_module_title" style="text-align: left; font-size: 10px; padding-top: 4px;">'.PHP_EOL;
        if (key_exists($script,$blck_ppp))
             {  $img    = '&nbsp;'.$PWSpopup.'&nbsp;';
                foreach ($blck_ppp [$script] as $arr)
                     {  if ($arr['show'] <> true) {continue;} 
                        if ($arr['chartinfo'] == 'popup' )
                             {  $string = $arr['popup'];
                                if (strpos(' '.$string,'?') <> false ) 
                                     {  $string .= '&amp;script='.$script;} 
                                else {  $string .= '?script='.$script;}
                                echo '<span><a href="'
                                        .$string
                                        .'" data-featherlight="iframe" >'
                                        .$img
                                        .$arr['text']
                                        .'</a>&nbsp;</span>'.PHP_EOL; 
                                        }
                        elseif ($arr['chartinfo'] == 'page' && $stripall == '')
                         {  echo '<a href="index.php?frame='.$arr['popup'].'">&nbsp;'.$img.'&nbsp;'.$arr['text'].'</a>';}
                        elseif ($arr['chartinfo'] == 'external' )
                         {  echo '<a href="'.$arr['popup'].'" target="_parent">&nbsp;'.$img.'&nbsp;'.$arr['text'].'</a>';}
                        $img = ' - ';
                        } // eo for each
                } // eo if key-exists
        echo '    </div>
<br></div>'.PHP_EOL; } 
#
        echo '<!-- begin outside/station data section -->
        <div class="PWS_weather_container " >
<!-- first row of three or four -->        '.PHP_EOL;
        $position11 = 'temp_c_block.php';
        weatheritem ('position11', $position11); include $position11; weatherbottom ($position11);
        weatheritem ('position12', $position12); include $position12; weatherbottom ($position12);
        weatheritem ('position13', $position13); include $position13; weatherbottom ($position13);
        #
        if ($extra3used == 'wide') 
             {  weatheritem ('position1e', $position1e,$class_extra);include $position1e;  weatherbottom ($position1e); }
        #       
        echo '<!-- second  row of three or four -->'.PHP_EOL;

        $position21 = 'wind_c_block.php';
        weatheritem ('position21', $position21); include $position21; weatherbottom ($position21);
        $position22 = 'baro_c_block.php';
        weatheritem ('position22', $position22); include $position22; weatherbottom ($position22);
        $position23 = 'sun_c_block.php';
        weatheritem ('position23', $position23); include $position23;  weatherbottom ($position23);
        #
        if ($extra3used == 'wide') 
             {  weatheritem ('position2e', $position2e,$class_extra);include $position2e;  weatherbottom ($position2e); }
        #
        echo '<!-- third  row of three or four -->'.PHP_EOL;
        $position31 = 'rain_c_block.php';
        weatheritem ('position31', $position31); include $position31; weatherbottom ($position31);
        weatheritem ('position32', $position32); include $position32; weatherbottom ($position32);
        weatheritem ('position33', $position33); include $position33; weatherbottom ($position33);
        #
        if ($extra3used == 'wide' && $position3e <> 'none') 
             {  weatheritem ('position3e', $position3e,$class_extra);include $position3e;  weatherbottom ($position3e); }
        
        
        if ($extra3used == 'row') 
             {  echo '<!-- fourth  row of three or four -->'.PHP_EOL;
                weatheritem ('position1e', $position1e,$class_extra);include $position1e; weatherbottom ($position1e);
                weatheritem ('position2e', $position2e,$class_extra);include $position2e; weatherbottom ($position2e);
                if ($position3e <> 'none') 
                     {  weatheritem ('position3e', $position3e,$class_extra);include $position3e; weatherbottom ($position3e); }
                }
        #
        echo '</div><!-- end all blocks -->'.PHP_EOL;
} // eo normal page display
#
echo '<div class="PWS_weather_container invisible" style="'.$stripall.'">
<table class="PWS_weather_item" style="width: 100%; height: 40px; margin: 2px; padding: 4px; font-size: 12px; ">
<tr>
<td style="text-align: left; min-width: 120px; vertical-align: top;"><a href="'.$weatherprogram['href'].'" target="_blank" title="'.$livedataFormat.'">'.$weatherprogram['img'].'</a></td>
<td style="text-align: center; width: 100%; vertical-align: top;">
<span style=" margin: 0 auto;">';
echo $weather['swversion'].'&nbsp;&nbsp;-&nbsp;&nbsp;'
 #    .$PWS_version.'&nbsp;&nbsp;-&nbsp;&nbsp;'
     .$hardware.'&nbsp;&nbsp;-&nbsp;&nbsp;'
     .$stationlocation.'&nbsp; <img src="img/flags/'.$country_flag.'"  title="'.$PWS_version.'" width="15" alt="flag">'.PHP_EOL;
if (trim($personalmessage) <> '') { echo '<br />'.lang($personalmessage);}
echo '</span>
</td>
<td style="float: right; text-align: right; min-width: 120px; font-size: 8px; vertical-align: top;">';
if ($manufacturer == 'davis')
     {  echo '<a href="https://www.davisinstruments.com/weather-monitoring/" title="https://www.davisinstruments.com/weather-monitoring/" target="_blank">
        <img src="img/davis.svg" width="95" height="20" alt="Davis Instruments&reg;" ></a>';}
elseif ($manufacturer == 'fineoffset')
     {  echo '<a href="http://www.foshk.com/" title="Fine Offset" target="_blank">
       <img src="img/foshk_logo.png" width="100" alt="www.foshk.com" ></a>';}
elseif ($manufacturer == 'weatherflow' ||  $weatherflowoption == true)
     {  echo '<a href="https://weatherflow.com/" title="https://weatherflow.com/" target="_blank">
        <img src="img/wflogo.svg" width="100" alt="http://weatherflow.com/" ></a>';}
else echo '';
echo '</td>
</tr>
</table>
</div>
<div id="notifications"></div>
<br />'.PHP_EOL;
#
# generate reload of all blocks.
#
if ($frame_ok == false) {
        echo '<script>'.PHP_EOL;
        echo '// load all data  - first functions using time-out  = immidate execution, sleep later
$(document).ready(function(){stationcron()});
function stationcron()
     {  $.ajax ({cache:false, 
                 success: function(a) {$("#blank").html(a); setTimeout(stationcron,1000*'.$non_cron.')},
                 type:"GET",url:"PWS_load_files.php?lang='.$used_lang.$test.'"})};
//
$(document).ready(function(){positionlast()});
// advisory script  needs to load external scripts and external data
function positionlast()
    {   $.ajax({cache:false,
                success:function(a){$("#positionlast").html(a);
                setTimeout(positionlast,' . '1000*'.$blck_rfrs[$positionlast].')},
                type:"GET",url:"'.$positionlast.'?lang='.$used_lang.'"})};
// now all functions with setInterval = sleep first run after'.PHP_EOL;
#
        $blocks = array (1,2,4,11,12,13,21,22,23,31,32,33);
        if ($extra3used <> 'none')
             {  $blocks[] = '1e';
                $blocks[] = '2e';
                $blocks[] = '3e';}
        if ($extra3used == 'wide') 
             {  $blocks[] = '3';}
        $cnt    = count ($blocks);
        for ($n = 0; $n < $cnt; $n++)
             {  $var    = 'position'.$blocks[$n];
                $url    = $$var;
                echo ' function '.$var.'() {  
                        $.ajax ( { cache:false, 
                                   success:function(a){$("#'.$var.'").html(a); },
                                   type:"GET",url:"'.$url.'?lang='.$used_lang.'&id_blck='.$var.'"} )} '.PHP_EOL;
                if ($blck_rfrs[$url] > 0) 
                     {  echo ' setInterval('.$var.',' . '1000*'.$blck_rfrs[$url].');'.PHP_EOL.'//'.PHP_EOL;}
                }

        echo 'function notifications(){  
        $.ajax ({cache:false, 
        success: function(a) {$("#notifications").html(a);},
        type:"GET",url:"PWS_notifications.php?lang='.$used_lang.$test.'"})};
$(document).ready(function()
     {  notifications();
        setInterval(notifications,1000*300);
        });
//
</script>'.PHP_EOL;
}
# now the menu is generated
#
if (!isset ($_REQUEST['stripall'])) {
        $scrpt          = 'PWS_menu.php';  
        $stck_lst      .= basename(__FILE__).' ('.__LINE__.') include_once =>'.$scrpt.PHP_EOL;
        include_once $scrpt; 
}
if (isset ($_REQUEST['test']) ) echo '<!-- '.PHP_EOL.$stck_lst.'-->'.PHP_EOL;
?>
</body>
</html>
<?php
if (isset ($_REQUEST['test']) && isset ($missing))
     {  echo '<!-- ';
        foreach ($missing as $txt) {echo $txt;}
        echo ' -->'.PHP_EOL; }
