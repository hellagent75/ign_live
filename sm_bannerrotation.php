<?php
/*
	Addon: Bannerrotation Type
	Webspell Version: 4
	Author: Andre Sardo
	Websites: www.andresardo.com | www.unstudios.org
*/

$_language->read_module('sc_bannerrotation');

if(isset($bannertype) AND $bannertype) $only = "AND bannertype='".$bannertype."'";
else $only = "";
//get banner
$allbanner = safe_query("SELECT * FROM ".PREFIX."bannerrotation WHERE displayed='1' ".$only." ORDER BY RAND() LIMIT 0,1");
$total = mysql_num_rows($allbanner);
if($total) {
	$banner = mysql_fetch_array($allbanner);
        $file_ext=strtolower(mb_substr($banner['banner'], strrpos($banner['banner'], ".")));
        list($width,$height) = explode('x',$banner['size']);
        if($file_ext == '.swf' || $file_ext == '.flv'){
            echo '<a href="ads/out/'.$banner['bannerID'].'/" target="_blank"><embed src="./images/bannerrotation/'.$banner['banner'].'" border="0" alt="'.htmlspecialchars($banner['bannername']).'" width="'.$width.'" height="'.$height.'" /></a>';
            $adv = '<a href="ads/out/'.$banner['bannerID'].'/" target="_blank"><embed src="./images/bannerrotation/'.$banner['banner'].'" border="0" alt="'.htmlspecialchars($banner['bannername']).'" width="'.$width.'" height="'.$height.'" /></a>';
        }
        else{
            echo '<a href="ads/out/'.$banner['bannerID'].'/" target="_blank"><img src="./images/bannerrotation/'.$banner['banner'].'" border="0" alt="'.htmlspecialchars($banner['bannername']).'" width="'.$width.'" height="'.$height.'" /></a>';
            $adv = '<a href="ads/out/'.$banner['bannerID'].'/" target="_blank"><img src="./images/bannerrotation/'.$banner['banner'].'" border="0" alt="'.htmlspecialchars($banner['bannername']).'" width="'.$width.'" height="'.$height.'" /></a>';
        }
}
unset($banner);
unset($bannertype);
?>
