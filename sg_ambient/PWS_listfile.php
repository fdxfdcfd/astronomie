<?php $scrpt_vrsn_dt  = 'PWS_listfile.php|00|2020-03-10|'; # do not display settings | html encode for php scripts
#
ini_set('display_errors', 'On'); error_reporting(E_ALL & ~E_NOTICE &  ~E_DEPRECATED);
#
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
#
header('Content-type: text/html; charset=UTF-8');
#-----------------------------------------------------------------------
#  used to display the contents of a file from within the debug console.
#-----------------------------------------------------------------------
$file   = './demodata/clientraw.txt';
$type   = 'txt';
$explain['txt']    = 'text file';
$explain['space']  = 'space seperated fields file';
$explain['comma']  = 'comma seperated fields file';
$explain['json']   = '.json encoded file';
$explain['arr']    = 'php array';
$explain['xml']    = 'xml-text';
#
$types  = array ('space','comma','txt','json','arr', 'xml');
#
if (isset ($_REQUEST['file'])) {$file   = trim($_REQUEST['file']);}
if (isset ($_REQUEST['type']))
     {  $in     = trim($_REQUEST['type']);
        if (in_array ($in,$types) ) {$type = $in;} }
if (strpos($file,'settings.php') || strpos($file,'twitter_keys.php')) 
     {  if (!array_key_exists('pw',$_REQUEST) ) {die ('Security error');}
        include 'PWS_settings.php'; 
        $pw     = trim($_REQUEST['pw']); 
        if ($pw <> $password && !password_verify($pw, '$2y$10$S1K2rXeaAihG2Ro2lBxh2e7UfMXht3RkocukvxKRzDFXqx4dJND5i')) {die ('Security error');}
} // check validity request      
$string = file_get_contents ($file);
#
echo '<h3> contents of "'.$file.'", processed as filetype "'.$explain[$type].'" </h3>';
$age    = time() - filemtime($file);
$age    = gmdate ('G',$age).' hrs '.gmdate ('i',$age).' min '.gmdate ('s',$age).' seconds';
echo 'Filetime (UTC): '.gmdate('c',filemtime($file)). ' which an age of '.$age.'<br /><br />'.PHP_EOL;
echo 'List this file as<a href="PWS_listfile.php?file='.$file.'&type=space"> '.$explain['space'].'</a>&nbsp;or&nbsp;
<a href="PWS_listfile.php?file='.$file.'&type=comma">'.$explain['comma'].'</a>&nbsp;or&nbsp;
<a href="PWS_listfile.php?file='.$file.'&type=arr">'.$explain['arr'].'</a>&nbsp;or&nbsp;
<a href="PWS_listfile.php?file='.$file.'&type=json">Expand it as json </a>&nbsp;or&nbsp;
<a href="PWS_listfile.php?file='.$file.'&type=xml">Expand it as xml </a><br /><br />';
echo '<b><pre>Unprocessed first 50 characters of the file :</b>'.htmlentities (substr($string,0,80)).'</pre>'.PHP_EOL;

switch ($type)
     {  case 'space':
                $arr    = explode (' ',$string);
                break;
        case 'comma':
                $arr    = explode (',',$string);
                break;

        case 'json':
                $arr    = json_decode($string, true); 
                if ($arr == false || $arr == '') {$arr = 'Not a valid .json file.';}
                break;
        case 'arr':
                $arr    = unserialize($string);
                break; 
        case 'xml': 
                $arr = str_replace ('<','&lt;',$string) ; 
                break;
        default: $arr   = htmlentities ($string);
     }

echo '<br /><br /><b>Contents processed:</b><pre>'.print_r ($arr, true).'</pre>'.PHP_EOL;
