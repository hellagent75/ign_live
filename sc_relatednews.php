<?php
/*
	Addon: News Features
	Webspell Version: 4
	Author: Andre Sardo
	Websites: www.andresardo.com | www.unstudios.org
*/

$news_id = $_GET['newsID'];
$qnews = safe_query("SELECT * FROM ".PREFIX."news WHERE newsID=$news_id");
$news = mysql_fetch_array($qnews);
$sc_game = $news['game'];

$only = '';
if(isset($sc_categoryID) and $sc_categoryID) $only = "AND category='".$sc_categoryID."'";
if(isset($sc_rubricID) and $sc_rubricID) $only = "AND rubric='".$sc_rubricID."'";
if(isset($sc_game) and $sc_game) $only = "AND game='".$sc_game."'";


$ergebnis=safe_query("SELECT * FROM ".PREFIX."news WHERE published='1' ".$only." AND intern<=".isclanmember($userID)." ORDER BY date DESC LIMIT 15");
if(mysql_num_rows($ergebnis)){
	echo '<ul>';
	$n=1;
	while($ds=mysql_fetch_array($ergebnis)) {
		$date=date("d.m.Y", $ds['date']);
		$time=date("H:i", $ds['date']);
		$news_id=$ds['newsID'];
		
		if($n%2) {
			$bg1=BG_1;
			$bg2=BG_2;
		}
		else {
			$bg1=BG_3;
			$bg2=BG_4;
		}
				
		if(file_exists('images/games/'.$ds['game'].'.gif')) $pic = '<img src="images/games/'.$ds['game'].'.gif" alt="'.$ds['game'].'" />';
		$game=$ds['game'];
		
		$message_array = array();
		$query=safe_query("SELECT n.*, c.short AS `countryCode`, c.country FROM ".PREFIX."news_contents n LEFT JOIN ".PREFIX."countries c ON c.short = n.language WHERE n.newsID='".$ds['newsID']."'");
		while($qs = mysql_fetch_array($query)) {
			$message_array[] = array('lang' => $qs['language'], 'headline' => $qs['headline'], 'message' => $qs['content'], 'country'=> $qs['country'], 'countryShort' => $qs['countryCode']);
		}
		$showlang = select_language($message_array);
	  
		$languages='';
		$i=0;
		foreach($message_array as $val) {
			if($showlang!=$i)	$languages.='<span style="padding-left:2px"><a href="index.php?site=news_comments&amp;newsID='.$ds['newsID'].'&amp;lang='.$val['lang'].'"><img src="images/flags/'.$val['countryShort'].'.gif" width="18" height="12" border="0" alt="'.$val['country'].'" /></a></span>';
			$i++;
		}
	  
		$lang=$message_array[$showlang]['lang'];
	
		$headlines=$message_array[$showlang]['headline'];
	
		if(mb_strlen($headlines)>$maxheadlinechars) {
			$headlines=mb_substr($headlines, 0, $maxheadlinechars);
			$headlines.='...';
		}
		
		/* Comments - Mod */
	$comments = $_POST['comments'];
	if($ds['comments']) {
			if($ds['cwID']) {  // CLANWAR-NEWS
				$anzcomments = getanzcomments($ds['cwID'], 'cw');
				$replace = Array('$anzcomments', '$url', '$lastposter', '$lastdate');
				$vars = Array($anzcomments, 'index.php?site=clanwars_details&amp;cwID='.$ds['cwID'], clearfromtags(getlastcommentposter($ds['cwID'], 'cw')), date('d.m.Y - H:i', getlastcommentdate($ds['cwID'], 'cw')));

				switch($anzcomments) {
					case 0: $comments = str_replace($replace, $vars, $_language->module['no_comment']); break;
					case 1: $comments = str_replace($replace, $vars, $_language->module['comment']); break;
					default: $comments = str_replace($replace, $vars, $_language->module['comments']); break;
				}
			}
			else {
				$anzcomments = getanzcomments($ds['newsID'], 'ne');
				$replace = Array('$anzcomments', '$url', '$lastposter', '$lastdate');
				$vars = Array($anzcomments, 'index.php?site=news_comments&amp;newsID='.$ds['newsID'], clearfromtags(html_entity_decode(getlastcommentposter($ds['newsID'], 'ne'))), date('d.m.Y - H:i', getlastcommentdate($ds['newsID'], 'ne')));

				switch($anzcomments) {
					case 0: $comments = str_replace($replace, $vars, '0'); break;
					case 1: $comments = str_replace($replace, $vars, '1'); break;
					default: $comments = str_replace($replace, $vars, '$anzcomments'); break;
				}
			}
		}
		else $comments='Closed';
		
		/* End - Comments Mod*/
	
		$headlines=clearfromtags($headlines);
	
		eval ("\$sc_headlines = \"".gettemplate("sc_headlines")."\";");
		echo $sc_headlines;
		
		$n++;
	}
	echo '</ul>';
	unset($sc_rubricID);
	unset($sc_categoryID);
	unset($sc_game);
}
?>
