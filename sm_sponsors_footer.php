<?php
/*
	Addon: Sponsors Category
	Webspell Version: 4
	Author: Andre Sardo
	Websites: www.andresardo.com | www.unstudios.org
*/

if(isset($sponsorcat) AND $sponsorcat) $only = "AND sponsorcat='".$sponsorcat."'";
else $only = "";

$_language->read_module('sponsors');
$mainsponsors=safe_query("SELECT * FROM ".PREFIX."sponsors WHERE (displayed = '1' AND mainsponsor = '1' ".$only.") ORDER BY sort");
if(mysql_num_rows($mainsponsors)) {
	
	if(mysql_num_rows($mainsponsors) == 1) $main_title = $_language->module['mainsponsor'];
	else $main_title = $_language->module['mainsponsors'];
	echo '';
	
	while($da=mysql_fetch_array($mainsponsors)) {
		if(!empty($da['banner_small'])) $sponsor='<img src="images/sponsors/'.$da['banner_small'].'" border="0" alt="" title="" />';
		else $sponsor=$da['name'];
		$sponsorID = $da['sponsorID'];
		
		eval ("\$sc_sponsors_main = \"".gettemplate("sc_sponsors_main")."\";");
		echo $sc_sponsors_main;
	}
}

$sponsors=safe_query("SELECT * FROM ".PREFIX."sponsors WHERE (displayed = '1' AND mainsponsor = '0' ".$only.") ORDER BY sort");
if(mysql_num_rows($sponsors)) {
	
	if(mysql_num_rows($sponsors) == 1) $title = $_language->module['sponsor'];
	else $title = $_language->module['sponsors'];
	echo '';
	
	while($db=mysql_fetch_array($sponsors)) {
		if(!empty($db['banner_small'])) $sponsor='<img src="images/sponsors/'.$db['banner_small'].'" border="0" alt="" title="" />';
		else $sponsor=$db['name'];
		$sponsorID = $db['sponsorID'];
		
		eval ("\$sc_sponsors = \"".gettemplate("sc_sponsors")."\";");
		echo $sc_sponsors;
	}
}
unset($sponsorcat);

?>