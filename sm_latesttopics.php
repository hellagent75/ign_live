<?php
$l_content = 35;
if (isset($site)) $_language->read_module('latesttopics');
$usergroups = array();
if($loggedin){
	$usergroups[] = 'user';
	$get = safe_query("SELECT * FROM ".PREFIX."user_forum_groups WHERE userID='".$userID."'");
	$data = mysql_fetch_row($get);
	for($i=2; $i<count($data);$i++){
		if($data[$i] == 1){
			$info = mysql_fetch_field($get,$i);
			$usergroups[] = $info->name;
		}
	}
}
$userallowedreadgrps = array();
$userallowedreadgrps['boardIDs'] = array();
$userallowedreadgrps['catIDs'] = array();
$get = safe_query("SELECT boardID FROM ".PREFIX."forum_boards WHERE readgrps = ''");
while($ds = mysql_fetch_assoc($get)){
	$userallowedreadgrps['boardIDs'][] = $ds['boardID'];
}

$get = safe_query("SELECT * FROM ".PREFIX."forum_categories WHERE readgrps = ''");
while($ds = mysql_fetch_assoc($get)){
	$userallowedreadgrps['catIDs'][] = $ds['catID'];
}
if($loggedin){
	$get = safe_query("SELECT boardID, readgrps FROM ".PREFIX."forum_boards WHERE readgrps != ''");
	while($ds = mysql_fetch_assoc($get)){
		$groups = explode(";",$ds['readgrps']);
		$allowed = array_intersect($groups,$usergroups);
		if(!count($allowed)) continue;
		$userallowedreadgrps['boardIDs'][] = $ds['boardID'];
	}
	$get = safe_query("SELECT * FROM ".PREFIX."forum_categories WHERE readgrps != ''");
	while($ds = mysql_fetch_assoc($get)){
		$groups = explode(";",$ds['readgrps']);
		$allowed = array_intersect($groups,$usergroups);
		if(!count($allowed)) continue;
		$userallowedreadgrps['catIDs'][] = $ds['catID'];
	}
}
if(empty($userallowedreadgrps['catIDs'])){
	$userallowedreadgrps['catIDs'][] = 0;
}
if(empty($userallowedreadgrps['boardIDs'])){
	$userallowedreadgrps['boardIDs'][] = 0;
}
$ergebnis=safe_query("SELECT t.*, u.nickname, b.*
						FROM ".PREFIX."forum_topics t
				   LEFT JOIN ".PREFIX."user u ON u.userID = t.lastposter
				   LEFT JOIN ".PREFIX."forum_boards b ON b.boardID = t.boardID
					   WHERE b.category IN (".implode(",",$userallowedreadgrps['catIDs']).") AND
					   		 t.boardID IN (".implode(",",$userallowedreadgrps['boardIDs']).") AND
					  		 t.moveID = '0'
					ORDER BY t.lastdate DESC
					   LIMIT 0,".$maxlatesttopics);
$anz=mysql_num_rows($ergebnis);
if($anz) {
	eval ("\$latesttopics_head = \"".gettemplate("latesttopics_head")."\";");

	$n=1;
	while($ds=mysql_fetch_array($ergebnis)) {
		if($ds['readgrps'] != "") {
			$usergrps = explode(";", $ds['readgrps']);
			$usergrp = 0;
			foreach($usergrps as $value) {
				if(isinusergrp($value, $userID)) {
					$usergrp = 1;
					break;
				}
			}
			if(!$usergrp and !ismoderator($userID, $ds['boardID'])) continue;
		}
		if($n%2) {
			$bg1=BG_1;
			$bg2=BG_2;
		}
		else {
			$bg1=BG_3;
			$bg2=BG_4;
		}

		$topictitle_full = clearfromtags($ds['topic']);
		$topictitle	= unhtmlspecialchars($topictitle_full);
		if(mb_strlen($topictitle)>40) {
			$topictitle=mb_substr($topictitle, 0, 40);
			$topictitle.='..';
		}
		$topictitle = htmlspecialchars($topictitle);
		$last_poster = $ds['nickname'];
		$board = $ds['name'];
		$date = date('d.m.Y', $ds['lastdate']);
		$small_date	= date('d.m H:i', $ds['lastdate']);
		$latesticon	=	'<img src="images/icons/'.$ds['icon'].'" alt="" />';
		$boardlink	=	'<a href="index.php?site=forum&amp;board='.$ds['boardID'].'">'.$board.'</a>';
		if(mb_strlen($board)>$l_content) {
			$board=mb_substr($board, 0, $l_content);
			$board.='..';
		}
		$topiclink =	'<a href="index.php?site=forum_topic&amp;topic='.$ds['topicID'].'&amp;type=ASC&amp;page='.ceil(($ds['replys']+1)/$maxposts).'">'.$topictitle.'</a>';
		$replys	=	$ds['replys'];
		$replys_text = ($replys == 1) ? $_language->module['reply'] : $_language->module['replies'];
		$boardID = $ds['boardID'];
		$visibility = $ds['visibility'];

        if ($ds['boardico_grp']=="1")       $boardico='<img src="images/games/'.$ds['boardico'].'.gif" alt="ICO" />';
        elseif ($ds['boardico_grp']=="2")   $boardico='<img src="images/flags/'.$ds['boardico'].'.gif" alt="ICO" />';
        elseif ($ds['boardico_grp']=="3")   $boardico='<img src="images/smileys/'.$ds['boardico'].'" alt="ICO" />';
        elseif ($ds['boardico_grp']=="4")   $boardico='<img src="images/forumico/'.$ds['boardico'].'.jpg" alt="ICO" />';
        else                                $boardico='<img src="images/forumico/no_icon.jpg" alt="ICO" />';

        $got = safe_query("SELECT * FROM ".PREFIX."forum_boards WHERE boardID='".$boardID."'");
        while($ds = mysql_fetch_assoc($got)){
            $catID = $ds['category'];
        }
        $gut = safe_query("SELECT * FROM ".PREFIX."forum_categories WHERE catID='".$catID."'");
        while($de = mysql_fetch_assoc($gut)){
            if ($de['catico_grp']=="1")     	$catico='<img src="images/games/'.$de['catico'].'.gif" alt="ICO" />';
            elseif ($de['catico_grp']=="2") 	$catico='<img src="images/flags/'.$de['catico'].'.gif" alt="ICO" />';
            elseif ($de['catico_grp']=="3") 	$catico='<img src="images/smileys/'.$de['catico'].'" alt="ICO" />';
            elseif ($de['catico_grp']=="4")		$catico='<img src="images/forumico/'.$de['catico'].'.jpg" alt="ICO" />';
            else                            	$catico='';
        }

		if ($boardico!="" && $visibility=="1") $sc_forumico=$boardico;
		else $sc_forumico=$catico;

		eval ("\$latesttopics_content = \"".gettemplate("sm_latesttopics")."\";");
		echo $latesttopics_content;
		$n++;
	}

}
unset($board);
?>