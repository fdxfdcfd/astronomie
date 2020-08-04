<?php $scrpt_vrsn_dt  = 'PWS_listfile.php|00|2019-122-12|';  # release 1912
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
#
$string         = 'date|time|temp|humidity|dewpoint|windspeed|windgust|wind direction|rain rate|rain today|pressure|wind dir|bft wind|--|pressure 60 minutes|month rain|year rain|rain yesterday|indoor temp|indoor hum|windchill|temp 1 hour ago|max temp|max time temp|min temp|min time temp|max avg wind|max wind time|max wind|max gust time time|max press|pressure max time|min press|pressure min time|version|build|wind max last ten minutes|--|--|UV|--|SOLAR|avg ten minute wind|rain total last 60 minutes|--|0|--|10 avg wind direction last ten minutes|--|--|day length|--|--|max uv|max humidity|max humidity time|min humidity|min humidity time|max dewpoint|max dewpoint time|min dewpoint|min dewpoint time|temp 15 minutes ago|humidity 15 minutes ago|dewpoint 15 minutes ago|indoor temp 15 minutes ago|indoor humidity 15 minutes ago|extra temp1|extra temp2|extra temp3|extra hum1|extra hum2|extra hum3|';
$fields         = explode ('|',$string);
$strlen         = 0;
foreach ($fields  as $name) if (strlen($name) > $strlen ) { $strlen = strlen($name); }
$flnm           = './jsondata/WDapi.txt';
$string         = file_get_contents($flnm);
$data           = explode (' ',$string);
$cnt_flds       = count ($fields);
$cnt_dt         = count ($data);
$max            = max ($cnt_flds, $cnt_dt);
echo '<pre>'.PHP_EOL;
$fill   = str_repeat (' ',$strlen);
for ($n = 0; $n < $max; $n++)
  {     $name =  'n/a';
        if ($n < $cnt_flds && trim ($fields[$n]) <> '' )
                     {  $name =  $fields[$n];}
        echo substr ($fill.$name, -$strlen);
        $n_str  = substr('   '.$n,-2);
        echo ' ['.$n_str.'] => ';
        $value  = 'n/a';
        if ($n < $cnt_dt )
              {  if (strlen ($data[$n])  > 0 )
                     {  $value  =  $data[$n];} 
                else {  $value  =  '- empty -';}
                }
        echo $value;
        echo PHP_EOL;}

