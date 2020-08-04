<?php  ini_set('display_errors', 'On');   error_reporting(E_ALL);  # error_reporting(E_ALL & ~E_NOTICE &  ~E_DEPRECATED);
$script = 'index.php';
if (isset ($_GET['test'])  )    { $script = trim($_GET['test']);}
#echo '<style>';include 'css/main.light.css';echo '</style>';
$_REQUEST['test']='test';
$missing = array();
include ($script);  
if (isset ($missing) && is_array ($missing) && count ($missing) > 0)
     {  echo '<pre>'.PHP_EOL;
        foreach ($missing as $txt) {echo $txt;}
        echo '</pre>'.PHP_EOL;}

