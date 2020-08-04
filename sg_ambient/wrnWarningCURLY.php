<?php $scrpt_vrsn_dt  = 'wrnWarningCURLY.php|00|2019-12-12|';  # release 1912
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
chdir ('./nws-alerts/');
include './nws-alerts.php';
chdir ('../');
$wrnHref        = '<a href="./index.php?frame=weatheralarms">';
$alertBox       = '';
$alertfile      = './nws-alerts/cache/nws-alertsBoxData.php';
if (file_exists ($alertfile)
  #      && (time() - filemtime ($alertfile) ) < 630 
        )
      {  include $alertfile; }
if (trim($alertBox == '')) {return false;}
/*<!-- nws-alerts box -->
<div style="width:99%; border:solid thin #006699; margin:0px auto 0px auto;">
 <div style=" background-color:#E6E6E3; color: #000; padding:4px 8px 4px 8px; text-align: center"><a href="nws-summary.php" title=" &nbsp;View summary" style="text-decoration:none; color: #000;">No Warnings, Watches, or Advisories</a></div>
</div>
<!-- nws-alerts box -->
<div style="width:99%; border:solid thin #000; margin:0px auto 0px auto;">
 <div style=" background-color:#CC0000; text-align:center; color: white; padding:4px 8px 4px 8px">
  <span style="white-space: nowrap"> <img src="./alert-images/FLW.gif" width="12" height="12" alt="River Flood Warning" title=" River Flood Warning" />&nbsp;<a href="nws-summary.php" style="color: white; text-decoration: none" title=" &nbsp;View summary"><b>RIVER&nbsp;FLOOD&nbsp;WARNING</b></a></span>&nbsp;-&nbsp;<a href="nws-details.php?a=CTZ006#WA1" style="color: white; text-decoration: none" title=" &nbsp;Details for New&nbsp;Haven - River Flood Warning">New&nbsp;Haven</a>  <br />
 </div>
</div>*/
$search = 'No Warnings, Watches';
$pos    = strpos ($alertBox, $search);
if ($pos <> false) { echo '<!-- '.$search.' -->'; return false;}
$search = 'background-color:';
$pos    = strpos ($alertBox, $search);
$pos   += strlen($search);
$color  = substr($alertBox,$pos,7);
$search = 'title="';
$pos    = strpos ($alertBox,$search ,$pos);
$pos   += strlen($search);
$pos2   = strpos ($alertBox, '"',$pos);
$lngth  = $pos2 - $pos;
$text   = substr ($alertBox,$pos,$lngth);
$alertfile      = './nws-alerts/cache/nws-alertsIconData.php';
if (file_exists ($alertfile) )
      {  include $alertfile; }
$cnt    = 1;
if (is_array($bigIcons) ) 
     {  $cnt = count ($bigIcons);}
$wrnStrings     = '<div style="text-align: center; position: absolute;top: 18px; left: 0px; width: 100%;height: 60px;  font-size: 12px; color: black; background-color: '.$color.';">
<div style="margin-top: 4px;"><b>NOAA-NWS</b> warns for <br />';
if ($cnt > 1) { $wrnStrings    .=  'multiple warnings' ;}
 else          { $wrnStrings    .=  $text ;}
$wrnStrings    .=  '
<br />'.
$wrnHref.'
<svg id="i-info" viewBox="0 0 32 32" width="20" height="20" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="6.25%"><path d="M16 14 L16 23 M16 8 L16 10"></path><circle cx="16" cy="16" r="14"></circle></svg>
</a>
</div>';  #       echo $wrnStrings; 
return true ;
