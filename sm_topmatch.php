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
 ########################################################################
*/

$_language->read_module('topmatch');

$now=time();
$limit = "LIMIT 0,1";

$ergebnis=safe_query("SELECT * FROM ".PREFIX."topmatch WHERE date>= $now AND displayed='1' ORDER BY date ".$limit);
while($ds=mysql_fetch_array($ergebnis)) {


	$ja=date("Y", $ds['date']);
	$mo=date("m", $ds['date']);
 	$ta=date("d", $ds['date']);
	$st=date("H", $ds['date']);
	$mi=date("i", $ds['date']);
	
	$endTime = mktime($st, $mi-1, 60, $mo, $ta, $ja); //Stunde, Minute, Sekunde, Monat, Tag, Jahr;

	//Aktuellezeit des microtimestamps nach PHP5, für PHP4 muss eine andere Form genutzt werden.
	$timeNow = microtime(true);
	
	//Berechnet differenz von der Endzeit vom jetzigen Zeitpunkt aus.
	$diffTime = $endTime - $timeNow;
	
	//Zerlegt $diffTime am Dezimalpunkt, rundet vorher auf 2 Stellen nach dem Dezimalpunkt und gibt diese zurück.
	$milli = explode(".", round($diffTime, 2));
	$millisec = round($milli[1]);
	
	//Berechnung für Tage, Stunden, Minuten
	$day = floor($diffTime / (24*3600));
	$diffTime = $diffTime % (24*3600);
	$houre = floor($diffTime / (60*60));
	$diffTime = $diffTime % (60*60);
	$min = floor($diffTime / 60);
	$sec = $diffTime % 60;
	
	$matchlink=str_replace("&", "&amp;", str_replace("&amp;", "&", $ds['matchlink']));
	$league=$ds['league'];
	$maps=$ds['maps'];
	$server=$ds['server'];
	if(file_exists('images/games/'.$ds['game'].'.gif')) $game_ico = 'images/games/'.$ds['game'].'.gif';
	$game='<img src="'.$game_ico.'" width="13" height="13" border="0" alt="" />';
	
	$logo1=$ds['logo1'];
	$country1=$ds['country1'];
	$team1=$ds['team1'];
	$url1=$ds['homepage1'];

	$logo2=$ds['logo2'];
	$country2=$ds['country2'];
	$team2=$ds['team2'];
	$url2=$ds['homepage2'];
	$report=$ds['report'];
	
	eval ("\$sc_topmatch = \"".gettemplate("sm_topmatch")."\";");
	echo $sc_topmatch;
	
}

?>
