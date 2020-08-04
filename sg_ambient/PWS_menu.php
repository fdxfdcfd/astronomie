<?php   $scrpt_vrsn_dt  = 'PWS_menu.php|00|2019-11-30|'; # release1912
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
# -------------------save list of loaded scrips;
if (!isset ($stck_lst) ) {$stck_lst = '';}
$stck_lst      .= basename(__FILE__).' ('.__LINE__.') version =>'.$scrpt_vrsn_dt.PHP_EOL;       
#-----------------------------------------------
#                                script settings
#-----------------------------------------------

# ------------------------- translation of texts
$ltxt_menu      = lang('Menu');
$ltxt_home      = lang('Home');
$ltxt_sett      = lang('Settings');
$ltxt_fsts      = lang('FIRST SETUP (protected)');
$ltxt_usrp      = lang('USER PREFERENCES');
$ltxt_dark      = lang('Dark Theme');
$ltxt_lght      = lang('Light Theme');
$ltxt_usrt      = lang('Station Theme');
$ltxt_impe      = lang('Imperial');
$ltxt_metr      = lang('Metric');
$ltxt_ukmp      = lang('UK (MPH)');
$ltxt_msms      = lang('Scandinavia');
$ltxt_lang      = lang('Select language');
$ltxt_extr      = lang('EXTRAS');
$ltxt_webc      = lang('Web Cam');
$ltxt_cntc      = lang('Contact Info');
$ltxt_sppt      = lang('SUPPORT');
$ltxt_mntn      = lang('Maintained by');
# ------------------------------------------------------
# THE HOME AND MENU ICONS IN THE TOP LEFT OF THE WINDOW
# ------------------------------------------------------
$homeicon='<svg width=14 height=14 fill=currentcolor stroke=currentcolor  viewBox="0 0 93 97.06" >
<g><path d="M92.56,45.42l-45-45a1.54,1.54,0,0,0-2.12,0l-45,45a1.5,1.5,0,0,0,0,2.12l8.12,8.12a1.54,1.54,0,0,0,2.12,0l2.16-2.16V95.56a1.5,1.5,0,0,0,1.5,1.5H78.66a1.5,1.5,0,0,0,1.5-1.5V53.5l2.16,2.16a1.5,1.5,0,0,0,2.12,0l8.12-8.12A1.5,1.5,0,0,0,92.56,45.42ZM37.66,94.06V70.65H55.34V94.06ZM77.16,50.63V94.06H58.34V69.15a1.5,1.5,0,0,0-1.5-1.5H36.16a1.5,1.5,0,0,0-1.5,1.5V94.06H15.84V50.63s0-.08,0-.11L46.5,19.84,77.17,50.51S77.16,50.59,77.16,50.63Zm6.23,1.86L47.56,16.66a1.54,1.54,0,0,0-2.12,0L9.62,52.48l-6-6L46.5,3.6,89.38,46.48Z"/></g>
</svg>';
$menuicon='<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="14px" height="14px" xml:space="preserve">
<g fill="#01a4b4"><path d="M0,3  l0,2 21,0 0,-2z" /><path d="M0,7 l0,2 21,0 0,-2z" /><path d="M0,11 l0,2 21,0 0,-2z" /></g>
</svg>';

# 
#if (isset ($frame_ok) && $frame_ok == false) 
#     {  $stl_hm = 'color: #01a4b4;';} 
#else {  $stl_hm = 'color: #ff7c39; font-weight: 700;';}
#
echo '<header style="position: absolute; top: 0; z-index: 4;">
<input type="checkbox" class=" opensidebarMenu" id="opensidebarMenu">
<label for="opensidebarMenu" class=" sidebarIconToggle"  style="top: 15px; width: 200px; text-align: left; color: #01a4b4">
'.$menuicon.'&nbsp;'.$ltxt_menu.'&nbsp;&nbsp;&nbsp;&nbsp;
<a href="./index.php" style="color: #01a4b4;">'.$homeicon.'&nbsp;'.$ltxt_home.'</a>
</label>'.PHP_EOL;
# ------------------------------------------------------
#                             THE MENU block starts here
# ------------------------------------------------------
echo '<div id="sidebarMenu">
  <span style="display: flex; margin-top: 15px; margin-left: 200px;
        color: white; border-radius: 3px; box-sizing: content-box;
        width: 18px; height: 18px; padding: 1px; 
        background: #ff7c39; font-weight: 600; font-size: 16px;
        align-items: center; justify-content: center;">X
</span>
<ul class="sidebarMenuInner" style="margin-top: 10px;">'.PHP_EOL;

# ------------------------------------------------------
#                    OPTONAL  settings link  in the menu
# ------------------------------------------------------
if ( !isset ($show_settings) ||  $show_settings == true) {
#
#                        icon used before the menu entry
        $settingsicon = '<svg id="i-settings" viewBox="0 0 32 32" width="14" height="14" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="8%">
<path d="M13 2 L13 6 11 7 8 4 4 8 7 11 6 13 2 13 2 19 6 19 7 21 4 24 8 28 11 25 13 26 13 30 19 30 19 26 21 25 24 28 28 24 25 21 26 19 30 19 30 13 26 13 25 11 28 8 24 4 21 7 19 6 19 2 Z"></path>
<circle cx="16" cy="16" r="4"></circle></svg>'.PHP_EOL;
        echo '<li class="separator">'.$ltxt_fsts.'</li>
<li><a href="PWS_easyweathersetup.php"  title="WEATHERSTATION SETTINGS PAGE">'.$ltxt_sett.' '.$settingsicon.'</a>
</li>'.PHP_EOL;  
} // eo optional settings link
#
# ------------------------------------------------------
#                               allowed  visitor changes
# ------------------------------------------------------
#                      icon used for these  menu entries
$to_outside = '<svg x="0px" y="0px" width="12" height="12" fill="currentcolor" stroke="currentcolor" stroke-width="8%" viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000">
<g><path d="M500,10C229.4,10,10,229.4,10,500c0,270.6,219.4,490,490,490c270.6,0,490-219.4,490-490C990,229.4,770.6,10,500,10z M500,967.9C241.6,967.9,32.1,758.4,32.1,500C32.1,241.6,241.6,32.1,500,32.1c258.4,0,467.9,209.5,467.9,467.9C967.9,758.4,758.4,967.9,500,967.9z M634.6,501.4l-247,248.3L371,733l230.3-231.6L371,269.8l16.6-16.7L634.6,501.4L634.6,501.4z"></path></g>
</svg>'.PHP_EOL;
#
#                              "user preferences" header
echo '<li class="separator" >'.$ltxt_usrp.'</li>'.PHP_EOL;
#
if ($themes === true) { #       switch between available themes 
$themes = array ();
$themes['dark']  = $ltxt_dark;
$themes['light'] = $ltxt_lght;
$themes['user']  = $ltxt_usrt;
unset ($themes[$current_theme]);
foreach ($themes as $key=> $text)
    {echo '<li><a href="./index.php?theme='.$key.' ">'.$text.'</a></li>'.PHP_EOL; }
} 
#
#                      icon used for these  menu entries
$unit_mark = '<b class="PWS_round" style=" color: white;  box-sizing: content-box;
        width: 12px; height: 12px; padding: 1px;
        background: #ff7c39; font-weight: 600; font-size: 10px;
        ">&deg;';
#                show ONLY the OTHER available unit sets
if($units_used!='us')       {  echo '<li><a  href="./index.php?units=us">'         .$ltxt_impe.' '.$unit_mark.'F</b></a></li>'.PHP_EOL;}
if($units_used!='metric')   {  echo '<li><a  href="./index.php?units=metric">'     .$ltxt_metr.' '.$unit_mark.'C</b></a></li>'.PHP_EOL;}
if($units_used!='uk')       {  echo '<li><a  href="./index.php?units=uk">'         .$ltxt_ukmp.' '.$unit_mark.'C</b></a></li>'.PHP_EOL;}
if($units_used!='scandinavia'){echo '<li><a  href="./index.php?units=scandinavia">'.$ltxt_msms.' '.$unit_mark.'C</b></a></li>'.PHP_EOL;}
# ------------------------------------------------------
#                                              LANGUAGES 
#   A flag for every supported language can be displayed
#                         when allowed by your  settings
# ------------------------------------------------------
if($lang_select == true) 
     {  $lng_mn = array();  // generate the html for each language and store in array
        foreach ($lngsArr as $key => $arr) {
                $menu_flag      = $arr['flag'];
                $title          = $arr['txt'];
                $lng_mn[$key]   = '<a href="index.php?lang='.$key.'" style="float: none;">'
                                 .'<img src="img/flags/'.$menu_flag.'"  title="'.$title.'"  width="25" height="25"> </a>';         
        } // eo foreach
        echo '<li class="separator" >'.$ltxt_lang.'</li>'.PHP_EOL;
#
#  all flags are grouped by 8 in one <li> line in the menu
#
        echo '<li style="text-align: center;">'.PHP_EOL;# html for the first li with up to 8 flags
        echo $lng_mn[$defaultlanguage].PHP_EOL;         # display the default language as the first flag
        unset ($lng_mn[$defaultlanguage]);              #   and remove it from the array
#
# display remaining langauges in groups of  8 / row in their own li
        $n      = 0;    // count
        $br     = 8;    // max flags / row
        foreach ($lng_mn as $key => $string)
             {  $n++;
                if ($n >= $br )
                     {  echo '</li>'.PHP_EOL;   // close last group  of 8 flags
                        echo '<li style="text-align: center;">'; // and start new group
                        $n      = 0;}           // reset counter
                echo $string.PHP_EOL;
                } // eo each flag
        echo '</li>'.PHP_EOL;  // close last group of 8 or less flags
        } // eo all languages
# ------------------------------------------------------
#         links to other (optional) pages in the menu
# ------------------------------------------------------
#                      icon used for these  menu entries
$PWSinfo = '<svg viewBox="0 0 32 32" width="12" height="12" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="10%">
<path d="M16 14 L16 23 M16 8 L16 10"></path>
<circle cx="16" cy="16" r="14"></circle>
</svg>'.PHP_EOL;
#
#                            header for EXTRA menu items
$menu_extra     = '<li class="separator" >'.$ltxt_extr.'</li>'.PHP_EOL;
$menu_show      = false;
#
#                                  optionalwebcam pop-up
if ($mywebcam == true) 
     {  $menu_show      = true;
        $menu_extra     .= '<li><a href="webcam_popup.php" data-featherlight="iframe" title="WEATHERSTATION WEBCAM">'.$ltxt_webc.' '.$PWSpopup.'</a></li>'.PHP_EOL; } 
#
#                        your info such as twitter a.s.o.
if ( isset ($contact_show) &&  $contact_show <> false) 
     {  $menu_show      = true;
        $menu_extra     .=  '<li><a href="bio_popup.php" data-featherlight="iframe" title="Contact WEATHERSTATION Info">'.$ltxt_cntc.' '.$PWSpopup.' </a></li>'.PHP_EOL;}
#
if ($menu_show == true) 
     {  echo $menu_extra;
        $menu_extra     = '';}
#             other menu items come from the frames file
#         in your  settings you can switch them on / off
#
$scrpt          = 'PWS_frames.php';
if ($extralinks == true && file_exists($scrpt) ) 
     {  echo $menu_extra;
        $stck_lst      .= basename(__FILE__).' ('.__LINE__.') include_once =>'.$scrpt.PHP_EOL;
        include_once $scrpt; # print_r($frm_ttls); exit;
}
#
if ($extralinks == true ) // are there any entries ?
     {  foreach ($frm_ttls as $key => $text)
                if (!isset ($frm_frme[$key]) || $frm_frme[$key] == true)  
                     {  echo '<li><a href="index.php?frame='.$key.'"> '.$text.'</a></li>'.PHP_EOL;}
                else {  echo '<li><a href="'.$frm_src[$key].'" target="_blank">'.$text.' '.$to_outside.'</a></li>'.PHP_EOL;}
        } // eo for each entry in the frames file
# ------------------------------------------------------
#     The last entries are links to support 
# ------------------------------------------------------
#                                            header line
echo '<li class="separator" >'.$ltxt_sppt.'</li>'.PHP_EOL;
#                                                support
echo '<li><a href="http://pwsdashboard.com/" title="Wim van der Kuil" target="_blank">'.$ltxt_mntn.' Wim van der Kuil '.$to_outside.'</a></li>'.PHP_EOL;
#
echo '
 </ul>
<br />&nbsp;
</div>
</header>';
if (isset ($frame_ok) && $frame_ok == true) 
     {  echo '<a href="./index.php">
<span style="position: absolute; top: 0; z-index: 4; display: flex; margin-top: 15px; margin-left: 200px;
        color: white; border-radius: 3px; box-sizing: content-box;
        width: 18px; height: 18px; padding: 1px; 
        background: #ff7c39; font-weight: 600; font-size: 16px;
        align-items: center; justify-content: center;">X
</span>  </a>   
     
     ';}
