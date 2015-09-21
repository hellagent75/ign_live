<?php
/*
##########################################################################
#                                                                        #
#           Version 4       /                        /   /               #
#          -----------__---/__---__------__----__---/---/-               #
#           | /| /  /___) /   ) (_ `   /   ) /___) /   /                 #
#          _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/___               #
#                       Free Content / Management System                 #
#                                   /                                    #
#                                                                        #
#                                                                        #
#   Copyright 2005-2011 by webspell.org                                  #
#                                                                        #
#   visit webSPELL.org, webspell.info to get webSPELL for free           #
#   - Script runs under the GNU GENERAL PUBLIC LICENSE                   #
#   - It's NOT allowed to remove this copyright-tag                      #
#   -- http://www.fsf.org/licensing/licenses/gpl.html                    #
#                                                                        #
#   Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at),   #
#   Far Development by Development Team - webspell.org                   #
#                                                                        #
#   visit webspell.org                                                   #
#                                                                        #
##########################################################################
*/

$_language->read_module('sponsors');

echo '<h3>Our Sponsors</h3>';

$ergebnis = safe_query("SELECT * FROM ".PREFIX."sponsors WHERE displayed = '1' ORDER BY sort");
if(mysql_num_rows($ergebnis)) {
	$i = 1;
	while($ds=mysql_fetch_array($ergebnis)) {
		if($i % 2) $bg1 = BG_1;
		else $bg1 = BG_2;
		
		$url=str_replace('http://', '', $ds['url']);
		$sponsor = '<a href="sponsors/out/'.$ds['sponsorID'].'/" target="_blank">'.$ds['name'].'</a>';
		$link = 'href="sponsors/out/'.$ds['sponsorID'].'/" target="_blank"';
		$info = cleartext($ds['info']);
		$banner = 'images/sponsors/'.$ds['banner'].'';
		$banner_small = '<img src="images/sponsors/'.$ds['banner_small'].'"/>';
		
		eval ("\$sponsors = \"".gettemplate("sponsors_full")."\";");
		echo $sponsors;
		$i++;
	}
}
else echo $_language->module['no_sponsors'];

?>