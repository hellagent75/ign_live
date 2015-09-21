<?php
$_language->read_module('news');

if(isset($newsID)) unset($newsID);
if(isset($_GET['newsID'])) $newsID = $_GET['newsID'];
if(isset($lang)) unset($lang);
if(isset($_GET['lang'])) $lang = $_GET['lang'];
$post = "";
if(isnewswriter($userID)) $post='<input type="button" onclick="MM_openBrWindow(\'news.php?action=new\',\'News\',\'toolbar=no,status=no,scrollbars=yes,width=800,height=600\')" value="'.$_language->module['post_news'].'" />';

if($newsID) {
	$result=safe_query("SELECT * FROM ".PREFIX."news WHERE newsID='".$newsID."'");
	$ds=mysql_fetch_array($result);

	if($ds['intern'] <= isclanmember($userID) && ($ds['published'] || (isnewsadmin($userID) || (isnewswriter($userID) and $ds['poster'] == $userID)))) {

		$date = date("jS F Y", $ds['date']);
		$time = date("H:i", $ds['date']);
		
		$category='';
		$newscategory=safe_query("SELECT categoryID, category FROM ".PREFIX."news_category WHERE categoryID='".$ds['category']."'");
		while($dr=mysql_fetch_array($newscategory)) {
			$categoryname=$dr['category'];
			$categoryname_link = getinput($categoryname);
		}
		
		if(file_exists('images/games/'.$ds['game'].'.gif')) $pic = '<img src="images/games/'.$ds['game'].'.gif" alt="'.$ds['game'].'" />';
		$game=$ds['game'];
		
		$rubrikname=getrubricname($ds['rubric']);
		$rubrikname_link = getinput($rubrikname);
		$rubricpic_name = getrubricpic($ds['rubric']);
		$rubricpic='images/news-rubrics/'.$rubricpic_name;
		if(!file_exists($rubricpic) OR $rubricpic_name=='') $rubricpic = ''; 
		else $rubricpic = ''.$rubricpic.'';

		$message_array = array();
		$query=safe_query("SELECT n.*, c.short AS `countryCode`, c.country FROM ".PREFIX."news_contents n LEFT JOIN ".PREFIX."countries c ON c.short = n.language WHERE n.newsID='".$newsID."'");
		while($qs = mysql_fetch_array($query)) {
			$message_array[] = array('lang' => $qs['language'], 'headline' => $qs['headline'], 'message' => $qs['content'], 'country'=> $qs['country'], 'countryShort' => $qs['countryCode']);
		}
		if(isset($_GET['lang'])) $showlang = getlanguageid($_GET['lang'], $message_array);
		else $showlang = select_language($message_array);

		$langs='';
		$i=0;
		foreach($message_array as $val) {
			if($showlang!=$i)	$langs.='<a href="news/'.$ds['newsID'].'/'.$val['lang'].'/" class="'.$val['countryShort'].'"></a>';
			$i++;
		}
		
		$headline=$message_array[$showlang]['headline'];
		$content=$message_array[$showlang]['message'];
		
		if($ds['intern'] == 1) $isintern = '('.$_language->module['intern'].')';
		else $isintern = '';
		
		$content = htmloutput($content);
		$content = toggle($content, $ds['newsID']);
		$headline = clearfromtags($headline);
		$comments = '';
		
		// COMENTS MOD BY TIAGOF.COM
		
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
					case 0: $comments = str_replace($replace, $vars, $_language->module['no_comment']); break;
					case 1: $comments = str_replace($replace, $vars, $_language->module['comment']); break;
					default: $comments = str_replace($replace, $vars, $_language->module['comments']); break;
				}
			}
		}
		else $comments='';
		
		// END OF COMENTS MOD

		$poster='<a href="user/'.$ds[poster].'/">'.getnickname($ds['poster']).'</a>';
		$avatar = '<img src="images/avatars/'.getavatar($ds['poster']).'" alt="'.getnickname($ds['poster']).'" />'; 
		$related='';
		if($ds['link1'] && $ds['url1']!="http://" && $ds['window1']) $related.='&#8226; <a href="'.$ds['url1'].'" target="_blank">'.$ds['link1'].'</a> ';
		if($ds['link1'] && $ds['url1']!="http://" && !$ds['window1']) $related.='&#8226; <a href="'.$ds['url1'].'">'.$ds['link1'].'</a> ';

		if($ds['link2'] && $ds['url2']!="http://" && $ds['window2']) $related.='&#8226; <a href="'.$ds['url2'].'" target="_blank">'.$ds['link2'].'</a> ';
		if($ds['link2'] && $ds['url2']!="http://" && !$ds['window2']) $related.='&#8226; <a href="'.$ds['url2'].'">'.$ds['link2'].'</a> ';

		if($ds['link3'] && $ds['url3']!="http://" && $ds['window3']) $related.='&#8226; <a href="'.$ds['url3'].'" target="_blank">'.$ds['link3'].'</a> ';
		if($ds['link3'] && $ds['url3']!="http://" && !$ds['window3']) $related.='&#8226; <a href="'.$ds['url3'].'">'.$ds['link3'].'</a> ';

		if($ds['link4'] && $ds['url4']!="http://" && $ds['window4']) $related.='&#8226; <a href="'.$ds['url4'].'" target="_blank">'.$ds['link4'].'</a> ';
		if($ds['link4'] && $ds['url4']!="http://" && !$ds['window4']) $related.='&#8226; <a href="'.$ds['url4'].'">'.$ds['link4'].'</a> ';

		if(empty($related)) $related="n/a";
    
    if(isnewsadmin($userID) or (isnewswriter($userID) and $ds['poster'] == $userID)) {
			$adminaction='<input type="button" onclick="MM_openBrWindow(\'news.php?action=edit&amp;newsID='.$ds['newsID'].'\',\'News\',\'toolbar=no,status=no,scrollbars=yes,width=800,height=600\')" value="'.$_language->module['edit'].'" />
	    <input type="button" onclick="MM_confirm(\''.$_language->module['really_delete'].'\', \'news.php?action=delete&amp;id='.$ds['newsID'].'\')" value="'.$_language->module['delete'].'" />';
		$unpublishs = '<form method="post" action="news.php?quickactiontype=unpublish"><input type="hidden" name="newsID[]" value="'.$ds['newsID'].'" /><input type="submit" name="submit" value="'.$_language->module['unpublish'].'" /></form>';
		}
		else $adminaction='';

		$bg1=BG_1;

		eval ("\$news = \"".gettemplate("sm_news_comments")."\";");
		echo $news;

		if(isnewsadmin($userID)) {
			if(!$ds['published']) echo '<form method="post" action="news.php?quickactiontype=publish"><input type="hidden" name="newsID[]" value="'.$ds['newsID'].'" /><input type="submit" name="submit" value="'.$_language->module['publish_now'].'" /></form>';
		}

		$comments_allowed = $ds['comments'];
		$parentID = $newsID;
		$type = "ne";
		$referer = "news/$newsID/";

		include("comments.php");
	}
	else echo $_language->module['no_access'];
}

?>