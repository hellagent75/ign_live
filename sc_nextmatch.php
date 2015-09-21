<?php
/*
 ########################################################################
#                                                                        #
#           Version 4       /                        /   /               #
#          -----------__---/__---__------__----__---/---/-               #
#           | /| /  /___) /   ) (_ `   /   ) /___) /   /                 #
#          _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/___               #
#                       Free Content / Management System                 #
#                                   /                                    #
#                                                                        #
#                                                                        #
#   Copyright 2005-2006 by webspell.org                                  #
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
 ########################################################################
*/
if (isset($site)) $_language->read_module('sc_topmatch');
$now=time();
$ergebnis=safe_query("SELECT * FROM ".PREFIX."topmatch WHERE date>= $now AND displayed='1' ORDER BY date LIMIT 0,1");
while($ds=mysql_fetch_array($ergebnis)) {
 $date=date("M jS, Y", $ds[date]);
	$time=date("g a", $ds['date']);

	$matchlink=$ds['matchlink'];
	$league=$ds['league'];
	$maps=$ds['maps'];
	$server=$ds['server'];
	
	$logo1=$ds['logo1'];
	$country1=$ds['country1'];
	$team1=$ds['team1'];
	$url1=$ds['homepage1'];

	$logo2=$ds['logo2'];
	$country2=$ds['country2'];
	$team2=$ds['team2'];
	$url2=$ds['homepage2'];
	
	eval ("\$sc_topmatch = \"".gettemplate("sc_nextmatch")."\";");
	echo $sc_topmatch;
	}
	if(!mysql_num_rows($ergebnis)) { 
	  eval ("\$sc_topmatch_none = \"".gettemplate("sc_nextmatch_none")."\";");
	  echo $sc_topmatch_none;
	 }
?>

