<?php 
$test   = '';
if (isset ($_REQUEST['test'])) 
     {  ini_set('display_errors', 'On');   error_reporting(E_ALL & ~E_NOTICE );
        $test='&test';}  // all errors except notices abvout new releases a.s.o.
else {  ini_set('display_errors', 0);      error_reporting(0);}                   // no reporting at all => for production sites
#
# remove comment markon two lines below if asked so by support only
# $_REQUEST['test'] = true;
# ini_set('display_errors', 'On');   error_reporting(E_ALL & ~E_NOTICE );  // used for full testing of new releases
#
if (!is_file('./_my_settings/settings.php') )
     {  include 'PWS_easyweathersetup.php';}
else {  include 'PWS_index2.php';}
