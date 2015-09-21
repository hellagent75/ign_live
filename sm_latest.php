<?php
$_language->read_module('sc_latest');

/* ################## FUNCTIONS ################## */

function get_headline($id,$type) {	// GET HEADLINE OF COMMENTTYPE
	if($type == "ne") {
		$res=mysql_fetch_array(safe_query("SELECT headline FROM `".PREFIX."news_contents` WHERE newsID='".$id."'"));
		return '<a href="index.php?site=news_comments&amp;newsID='.$id.'">'.$res['headline'].'</a>';
	}	
	if($type == "ga") {
		$res = mysql_fetch_array(safe_query("SELECT name FROM ".PREFIX."gallery_pictures WHERE picID='".$id."'"));
		return '<a href="index.php?site=gallery&amp;picID='.$id.'">'.$res['name'].'</a>';
	}
	if($type == "de") {
		$res = mysql_fetch_array(safe_query("SELECT clantag1,clantag2 FROM ".PREFIX."demos WHERE demoID='".$id."'"));
		return '<a href="index.php?site=demos&amp;action=showdemo&amp;demoID='.$id.'">'.$res['clantag1'].' vs. '.$res['clantag2'].'</a>';
	}
	if($type == "ar") {
		$res = mysql_fetch_array(safe_query("SELECT title FROM ".PREFIX."articles WHERE articlesID='".$id."'"));
		return '<a href="index.php?site=articles&amp;action=show&amp;articlesID='.$id.'">'.$res['title'].'</a>';
	}
	if($type == "cw") {
		$res = mysql_fetch_array(safe_query("SELECT squad,opponent FROM ".PREFIX."clanwars WHERE cwID='".$id."'"));
		return '<a href="index.php?site=clanwars_details&amp;cwID='.$id.'">'.getsquadname($res['squad']).' vs. '.$res['opponent'].'</a>';
	}
	if($type == "po") {
		$res = mysql_fetch_array(safe_query("SELECT title FROM ".PREFIX."polls WHERE pollID='".$id."'"));
		return '<a href="index.php?site=polls&amp;pollID='.$id.'">'.$res['title'].'</a>';
	}
	return '';
}

/* ################## SETTINGS ################## */

$_l = array();
$_l['news'] = 1;
$_l['comments'] = 1;
$_l['topics'] = 1;
$_l['replys'] = 1;
$_l['files'] = 1;
$_l['users'] = 1;
$_l['guestbook'] = 1;
$_l['awards'] = 1;

$_l_bg1 = "#D3D3D3";
$_l_bg2 = "#B7B7B7";

/* ################## /SETTINGS ################## */

$query = "SELECT n.newsID as `lastID`,n.`date`,n.poster as `addedby`,'news' as `type`,'' as `parent`, nc.`headline` as `title` FROM ".PREFIX."news as n, ".PREFIX."news_contents as nc WHERE n.newsID=nc.newsID and n.published=1";

if($_l['comments'])		$query .= " UNION (SELECT `commentID` as `lastID`,`date`,`userID` as `addedby`,'com' as `type`, `parentID` as `parent`, `type` as `title`  FROM ".PREFIX."comments)";
if($_l['topics'])		$query .= " UNION (SELECT `topicID` as `lastID`, `date`, `userID` as `addedby`,'f_topic' as `type`, `boardID` as `parent`, `topic` as `title` FROM ".PREFIX."forum_topics)";
if($_l['replys'])		$query .= " UNION (SELECT p.`postID` as `lastID`,p.`date`, p.`poster` as `addedby`, 'f_reply' as `type`, p.`topicID` as `parent`, t.`topic` as `title` FROM ".PREFIX."forum_posts as p, ".PREFIX."forum_topics as t WHERE t.date<>p.date)";
if($_l['awards'])		$query .= " UNION (SELECT `awardID` as `lastID`,`date`,0 as `addedby`,'award' as `type`,`squadID` as `parent`, `award` as `title` FROM ".PREFIX."awards)";
$query .= " ORDER BY `date` DESC LIMIT 0,10";

$query = mysql_query($query);
$i = 1;
while($res = mysql_fetch_array($query)) {
	$uID = $res['type'] == 'newuser' ? $res['lastID'] : $res['addedby'];
	$nick = '<a href="index.php?site=profile&id='.$res['addedby'].'">'.getnickname($uID).'</a>';
	$ret = $nick.' ';
	$dateformat = $res['type'] == 'award' ? "d.m.Y" : "d.m.Y H:i";
	$date = date($dateformat,$res['date']);
	$type = $_language->module['type_'.$res['type']];

	if($res['type'] == "news")			$ret .= $_language->module['published_news'].' <i><a href="index.php?site=news_comments&amp;newsID='.$res['lastID'].'">'.$res['title'].'</a></i>';
	elseif($res['type'] == "com")		$ret .= $_language->module['comment_to'].' '.$_language->module['comtype_'.$res['title']].' <i>'.get_headline($res['parent'],$res['title']).'</i>';
	elseif($res['type'] == "file")		$ret .= $_language->module['added_file'].'  <i><a href="index.php?site=file&amp;file='.$res['lastID'].'">'.$res['title'].'</a></i>';
	elseif($res['type'] == "f_topic")	$ret .= $_language->module['created_topic'].'  <i><a href="index.php?site=forum_topic&amp;topic='.$res['lastID'].'&amp;type=ASC&amp;page=1">'.$res['title'].'</a></i>';
	elseif($res['type'] == "f_reply")	$ret .= $_language->module['replyed_to'].'  <a href="index.php?site=forum_topic&amp;topic='.$res['parent'].'&amp;type=ASC&amp;page=1"><i>'.$res['title'].'</i></a>';
	elseif($res['type'] == "newuser")	$ret .= $_language->module['new_user'];
	elseif($res['type'] == "guestb")	$ret = $_language->module['new_gb'];
	elseif($res['type'] == "award")		$ret = str_replace("%squad%",getsquadname($res['parent']),str_replace("%award%",'<a href="index.php?site=awards">'.$res['title'].'</a>',$_language->module['new_award']));
	
	$bg = $i%2 == 0 ? $_l_bg1 : $_l_bg2;
	eval ("\$sc_latest = \"".gettemplate("sm_latest")."\";");
	echo $sc_latest;
	
$i++;
}
?>