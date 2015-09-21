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

$_language->read_module('profile');
$profile_username = $_GET['username'];

if(isset($_GET['id'])) $id = (int)$_GET['id'];
else $id=$userID;

if(isset($_GET['action'])) $action = $_GET['action'];
else $action = '';

if(isset($_GET['username'])) $id = getuserid($_GET['username']);

if(isset($id) and getnickname($id) != '') {
	
	if(isbanned($id)) $banned = '<div id="profile_banned">This user is Banned.</div>';
	else $banned = '';
	if(isbanned($id)) $bannedpic = '';
	else $bannedpic = '';

	if($action == "galleries") {

		//galleries
		eval("\$title_profile = \"".gettemplate("title_profile")."\";");
		echo $title_profile;

		$galclass = new Gallery();

		$border = BORDER;
		$bgcat = BGCAT;
		$pagebg = PAGEBG;

		$galleries = safe_query("SELECT * FROM ".PREFIX."gallery WHERE userID='".$id."'");

		echo '<br /><table width="100%" cellpadding="2" cellspacing="0" bgcolor="'.$border.'">
      <tr>
        <td class="title" colspan="4">&nbsp;&#8226; '.$_language->module['galleries'].' '.$_language->module['by'].' '.getnickname($id).'</td>
      </tr>
      <tr><td bgcolor="'.$pagebg.'" colspan="4"></td></tr>
      <tr bgcolor="'.$bgcat.'">
        <td width="100">&nbsp;</td>
        <td width="100"><b>'.$_language->module['date'].'</b></td>
        <td><b>'.$_language->module['name'].'</b></td>
        <td width="80"><b>'.$_language->module['pictures'].'</b></td>
      </tr>';

		if($usergalleries) {
			if(mysql_num_rows($galleries)) {
				$n = 1;
				while($ds = mysql_fetch_array($galleries)) {
					$n % 2 ? $bg = BG_1 : $bg = BG_2;

					$piccount = mysql_num_rows(safe_query("SELECT * FROM ".PREFIX."gallery_pictures WHERE galleryID='".$ds['galleryID']."'"));
					$commentcount = mysql_num_rows(safe_query("SELECT * FROM ".PREFIX."comments WHERE parentID='".$ds['galleryID']."' AND type='ga'"));


					$gallery['date'] = date("d.m.Y",$ds['date']);
					$gallery['title'] = cleartext($ds['name']);
					$gallery['picture'] = $galclass->randompic($ds['galleryID']);
					$gallery['galleryID'] = $ds['galleryID'];
					$gallery['count'] = mysql_num_rows(safe_query("SELECT picID FROM `".PREFIX."gallery_pictures` WHERE galleryID='".$ds['galleryID']."'"));

					eval("\$profile = \"".gettemplate("profile_galleries")."\";");
					echo $profile;

					$n++;
				}
			}
			else echo '<tr>
          <td colspan="4" bgcolor="'.BG_1.'">'.$_language->module['no_galleries'].'</td>
        </tr>';
		}
		else echo '<tr>
        <td colspan="4" bgcolor="'.BG_1.'">'.$_language->module['usergalleries_disabled'].'</td>
      </tr>';

		echo '</table>';

	}
	elseif($action == "lastposts") {

		//profil: last posts

		eval("\$title_profile = \"".gettemplate("title_profile")."\";");
		echo $title_profile;

		$topiclist="";
		$topics=safe_query("SELECT * FROM ".PREFIX."forum_topics WHERE userID='".$id."' AND moveID=0 ORDER BY date DESC");
		if(mysql_num_rows($topics)) {
			$n = 1;
			while($db = mysql_fetch_array($topics)) {
				if($db['readgrps'] != "") {
					$usergrps = explode(";", $db['readgrps']);
					$usergrp = 0;
					foreach($usergrps as $value) {
						if(isinusergrp($value, $userID)) {
							$usergrp = 1;
							break;
						}
					}
					if(!$usergrp and !ismoderator($userID, $db['boardID'])) continue;
				}
				$n % 2 ? $bgcolor = BG_1 : $bgcolor = BG_2;
				$posttime = date("d.m.y H:i", $db['date']);

				$topiclist .= '<tr bgcolor="'.$bgcolor.'">
            <td width="50%">
            <table width="100%" cellpadding="2" cellspacing="0">
              <tr>
                <td colspan="3"><div style="overflow:hidden;"><a href="index.php?site=forum_topic&amp;topic='.$db['topicID'].'">'.$posttime.'<br /><b>'.clearfromtags($db['topic']).'</b></a><br /><i>'.$db['views'].' '.$_language->module['views'].' - '.$db['replys'].' '.$_language->module['replys'].'</i></div></td>
              </tr>
            </table>
            </td>
          </tr>';

				if($profilelast == $n) break;
				$n++;
			}
		}
		else $topiclist = '<tr>
        <td colspan="2" bgcolor="'.BG_1.'">'.$_language->module['no_topics'].'</td>
      </tr>';

		$postlist="";
		$posts=safe_query("SELECT ".PREFIX."forum_topics.boardID, ".PREFIX."forum_topics.readgrps, ".PREFIX."forum_topics.topicID, ".PREFIX."forum_topics.topic, ".PREFIX."forum_posts.date, ".PREFIX."forum_posts.message FROM ".PREFIX."forum_posts, ".PREFIX."forum_topics WHERE ".PREFIX."forum_posts.poster='".$id."' AND ".PREFIX."forum_posts.topicID=".PREFIX."forum_topics.topicID ORDER BY date DESC");
		if(mysql_num_rows($posts)) {
			$n = 1;
			while($db = mysql_fetch_array($posts)) {
				if($db['readgrps'] != "") {
					$usergrps = explode(";", $db['readgrps']);
					$usergrp = 0;
					foreach($usergrps as $value) {
						if(isinusergrp($value, $userID)) {
							$usergrp = 1;
							break;
						}
					}
					if(!$usergrp and !ismoderator($userID, $db['boardID'])) continue;
				}

				$n % 2 ? $bgcolor1 = BG_1 : $bgcolor1 = BG_2;
				$n % 2 ? $bgcolor2 = BG_3 : $bgcolor2 = BG_4;
				$posttime = date("d.m.y h:i", $db['date']);
				if(mb_strlen($db['message']) > 100) $message = mb_substr($db['message'], 0, 90 + mb_strpos(mb_substr($db['message'], 90, mb_strlen($db['message'])), " "))."...";
				else $message = $db['message'];
				$postlist.='<tr bgcolor="'.$bgcolor1.'">
            <td>
            <table width="100%" cellpadding="2" cellspacing="0">
              <tr>
                <td colspan="3"><a href="index.php?site=forum_topic&amp;topic='.$db['topicID'].'">'.$posttime.' <br /><b>'.$db['topic'].'</b></a></td>
              </tr>
              <tr><td></td></tr>
              <tr>
                <td width="1%">&nbsp;</td>
                <td bgcolor="'.$bgcolor2.'"><div style="width: 250px;overflow:hidden;">'.clearfromtags($message).'</div></td>
                <td width="1%">&nbsp;</td>
              </tr>
            </table>
            </td>
          </tr>';

				if($profilelast == $n) break;
				$n++;
			}
		}
		else $postlist='<tr>
        <td colspan="2" bgcolor="'.BG_1.'">'.$_language->module['no_posts'].'</td>
      </tr>';



		eval("\$profile = \"".gettemplate("profile_lastposts")."\";");
		echo $profile;
	}
	else {
		
		
		//Friends
		
		$buddylist="";
    $buddys = safe_query("SELECT buddy FROM ".PREFIX."buddys WHERE userID='".$id."'");
		if(mysql_num_rows($buddys)) {
			$n = 1;
			while($db = mysql_fetch_array($buddys)) {
				$n % 2 ? $bgcolor = BG_1 : $bgcolor = BG_2;
				$flag = '[flag]'.getcountry($db['buddy']).'[/flag]';
				$country = flags($flag);
				$nicknamebuddy = getnickname($db['buddy']);
				$email = "<a href='mailto:".mail_protect(getemail($db['buddy']))."'><img src='images/icons/email.gif' border='0' alt='' /></a>";
        
        if(isignored($userID, $db['buddy'])) $buddy = '<a href="buddys.php?action=readd&amp;id='.$db['buddy'].'&amp;userID='.$userID.'"><img src="images/icons/buddy_readd.gif" border="0" alt="'.$_language->module['back_buddylist'].'" /></a>';
				elseif(isbuddy($userID, $db['buddy'])) $buddy = '<a href="buddys.php?action=ignore&amp;id='.$db['buddy'].'&amp;userID='.$userID.'"><img src="images/icons/buddy_ignore.gif" border="0" alt="'.$_language->module['ignore_user'].'" /></a>';
				elseif($userID == $db['buddy']) $buddy = '';
				else $buddy = '<a href="buddys.php?action=add&amp;id='.$db['buddy'].'&amp;userID='.$userID.'"><img src="images/icons/buddy_add.gif" border="0" alt="'.$_language->module['add_buddylist'].'" /></a>';

        if(isonline($db['buddy'])==0) $statuspic = 'offline';
				else $statuspic = '<font style="color: #0C3; text-decoration: blink;">online</font>';
        
		$budyuserpic = getbudyuserpic($nicknamebuddy);
		
        $buddylist .= '<a href="user/'.$nicknamebuddy.'/" class="tip_trigger"><img src="images/userpics/'.$budyuserpic.'" width="60" height="60" alt="" /><span class="tooltip"><font style="color: #f26722; font-weight: bold;">'.$nicknamebuddy.'</font><br />'.$statuspic.'</span></a><tr bgcolor="'.$bgcolor.'">';
            
				$n++;
			}
		}
		else $buddylist = '<span style="margin:6px 0px 0px 6px; float: left;">No friends yet.</span>';
		
		//user guestbook

		if(isset($_POST['save'])) {

			$date = time();
			$ip = $GLOBALS['ip'];
			$run = 0;

			if($userID) {
				$name = getnickname($userID);
				if(getemailhide($userID)) $email='';
        else $email = getemail($userID);
				$url = gethomepage($userID);
				$icq = geticq($userID);
				$run = 1;
			}
			else {
				$name = $_POST['gbname'];
				$email = $_POST['gbemail'];
				$url = $_POST['gburl'];
				$icq = $_POST['icq'];
				$CAPCLASS = new Captcha;
				if($CAPCLASS->check_captcha($_POST['captcha'], $_POST['captcha_hash'])) $run = 1;
			}

			if($run) {

				safe_query("INSERT INTO ".PREFIX."user_gbook (userID, date, name, email, hp, icq, ip, comment)
								values('".$id."', '".$date."', '".$_POST['gbname']."', '".$_POST['gbemail']."', '".$_POST['gburl']."', '".$_POST['icq']."', '".$ip."', '".$_POST['message']."')");

				if($id != $userID) sendmessage($id, $_language->module['new_guestbook_entry'], str_replace('%guestbook_id%', $id, $_language->module['new_guestbook_entry_msg']));
			}
			redirect('user/'.getnickname($id).'/','',0);
		}
		elseif(isset($_GET['delete'])) {
			if(!isanyadmin($userID) and $id != $userID) die($_language->module['no_access']);

			foreach($_POST['gbID'] as $gbook_id) {
				safe_query("DELETE FROM ".PREFIX."user_gbook WHERE gbID='$gbook_id'");
			}
			redirect('user/'.getnickname($id).'/','',0);
		}
		else {

			$bg1 = BG_1;
			$bg2 = BG_2;

			$gesamt = mysql_num_rows(safe_query("SELECT gbID FROM ".PREFIX."user_gbook WHERE userID='".$id."'"));

			if(isset($_GET['page'])) $page = (int)$_GET['page'];
			$type="DESC";
			if(isset($_GET['type'])){
			  if(($_GET['type']=='ASC') || ($_GET['type']=='DESC')) $type=$_GET['type'];
			}
			$typex="";
			if(isset($_GET['type'])){
			  if(($_GET['type']=='') || ($_GET['type']=='')) $type=$_GET[''];
			}

			$pages = 1;
			if(!isset($page)) $page = 1;
			if(!isset($type)) $type = "DESC";

			$max = $maxguestbook;
			$pages = ceil($gesamt/$max);

			if($pages > 1) $page_link = makepagelink("user/".$profile_username."/".$typex, $page, $pages);
			else $page_link='';

			if($page == "1") {
				$ergebnis = safe_query("SELECT * FROM ".PREFIX."user_gbook WHERE userID='".$id."' ORDER BY date ".$type." LIMIT 0, ".$max);
				if($type == "DESC") $n = $gesamt;
				else $n = 1;
			}
			else {
				$start = $page * $max - $max;
				$ergebnis = safe_query("SELECT * FROM ".PREFIX."user_gbook WHERE userID='".$id."' ORDER BY date ".$type." LIMIT ".$start.", ".$max);
				if($type == "DESC") $n = $gesamt - ($page - 1) * $max;
				else $n = ($page - 1) * $max + 1;
			}

			if($type=="ASC")
			$sorter='<a href="index.php?site=profile&amp;id='.$id.'&amp;action=guestbook&amp;page='.$page.'&amp;type=DESC">'.$_language->module['sort'].':</a> <img src="images/icons/asc.gif" width="9" height="7" border="0" alt="" />&nbsp;&nbsp;&nbsp;';
			else
			$sorter='<a href="index.php?site=profile&amp;id='.$id.'&amp;action=guestbook&amp;page='.$page.'&amp;type=ASC">'.$_language->module['sort'].':</a> <img src="images/icons/desc.gif" width="9" height="7" border="0" alt="" />&nbsp;&nbsp;&nbsp;';

			$gbook_form_header = '<form method="post" name="form" action="index.php?site=profile&amp;id='.$id.'&amp;action=guestbook&amp;delete=true">';
			while ($ds = mysql_fetch_array($ergebnis)) {
				$n % 2 ? $bg1 = BG_1 : $bg1 = BG_2;
				$date = date("d.m.Y", $ds['date']);
				
				/*MOD OF TIME***********************************/
				$time = time();
				$logintime = $ds['date'];
				
				$sec = $time - $logintime;
				$days = $sec / 86400;
				$days = mb_substr($days, 0, mb_strpos($days, "."));
				
				$sec = $sec - $days * 86400;
				$hours = $sec / 3600;
				$hours = mb_substr($hours, 0, mb_strpos($hours, "."));
				
				$sec = $sec - $hours * 3600;
				$minutes = $sec / 60;
				$minutes = mb_substr($minutes, 0, mb_strpos($minutes, "."));
				if($time - $logintime < 60) {
					$now = "Seconds ago";
					$days = "";
					$hours = "";
					$minutes = "";
				}
				else {
					$now = '';
					if($days!=0){
						$days = $days.' Days ago';
						$hours = "";
						$minutes = "";
					}elseif($hours!=0){
						$days = "";
						$hours = $hours.' Hours ago';
						$minutes = "";
					}elseif($minutes!=0){
						$days = "";
						$hours = "";
						$minutes = $minutes.' Minutes ago';
					}
				}
				
			$dcounttime = $now.$days.$hours.$minutes;
			/************************************/

				if(validate_email($ds['email'])) $email = '<a href="mailto:'.mail_protect($ds['email']).'"><img src="images/icons/email.gif" border="0" alt="'.$_language->module['email'].'" /></a>';
				else $email = '';

				if(validate_url($ds['hp'])) $hp = '<a href="'.$ds['hp'].'" target="_blank"><img src="images/icons/hp.gif" border="0" alt="'.$_language->module['homepage'].'" /></a>';
				else $hp = '';

				$sem = '/[0-9]{6,11}/si';
				$icq_number = str_replace('-', '', $ds['icq']);
				if(preg_match($sem, $icq_number)) $icq = '<a href="http://www.icq.com/people/about_me.php?uin='.$icq_number.'" target="_blank"><img src="http://online.mirabilis.com/scripts/online.dll?icq='.$icq_number.'&amp;img=5" border="0" alt="icq" /></a>';
				else $icq = "";

				$name = strip_tags($ds['name']);
				$message = cleartext($ds['comment']);
				$quotemessage = strip_tags($ds['comment']);
				$quotemessage = str_replace("'", "`", $quotemessage);
				
				$gbookpic = getguestbookuserpic($name);

				$actions = '';
				$ip = $_language->module['logged'];
				$quote = '<a href="javascript:AddCode(\'[quote='.$name.']'.$quotemessage.'[/quote]\')"><img src="images/icons/quote.gif" border="0" alt="'.$_language->module['quote'].'" /></a>';
				if(isfeedbackadmin($userID) OR $id == $userID) {
					$actions = '<input class="input" type="checkbox" name="gbID[]" value="'.$ds['gbID'].'" />';
					if(isfeedbackadmin($userID)) $ip = $ds['ip'];
				}

				eval("\$profile_guestbook = \"".gettemplate("profile_guestbook")."\";");
				$gbook_comments .= $profile_guestbook;

				if($type == "DESC") $n--;
				else $n++;
			}
			
			if( !isset($gbook_comments) ) $gbook_comments = '<div style=" padding: 5px; font-weight: bold; width: 282px; float: left;">No Comments</div>';

			if(isfeedbackadmin($userID) || $userID == $id) $submit='<span style="margin: 2px 0px 20px 0px;float: right;"><input type="submit" value="'.$_language->module['delete_selected'].'" /></span>';
											  else $submit='';
			$gbook_form_footer = ''.$page_link.''.$submit.'</form>';

			if($loggedin) {
				$name = getnickname($userID);
				if(getemailhide($userID)) $email='';
        else $email = getemail($userID);
				$url = gethomepage($userID);
				$icq = geticq($userID);
				$_language->read_module('bbcode', true);

				eval ("\$addbbcode = \"".gettemplate("addbbcode")."\";");
				eval("\$profile_guestbook_loggedin = \"".gettemplate("profile_guestbook_loggedin")."\";");
				$profile_guestbook_loggedin;
			}
			else {
				$CAPCLASS = new Captcha;
				$captcha = $CAPCLASS->create_captcha();
				$hash = $CAPCLASS->get_hash();
				$CAPCLASS->clear_oldcaptcha();
				$_language->read_module('bbcode', true);

				eval ("\$addbbcode = \"".gettemplate("addbbcode")."\";");
				eval("\$profile_guestbook_notloggedin = \"".gettemplate("profile_guestbook_notloggedin")."\";");
				$profile_guestbook_notloggedin;
			}
		}

		//profil: home

		$date = time();
		$ergebnis = safe_query("SELECT * FROM ".PREFIX."user WHERE userID='".$id."'");
		$anz = mysql_num_rows($ergebnis);
		$ds = mysql_fetch_array($ergebnis);

		if($userID != $id && $userID != 0) {
			safe_query("UPDATE ".PREFIX."user SET visits=visits+1 WHERE userID='".$id."'");
			if(mysql_num_rows(safe_query("SELECT visitID FROM ".PREFIX."user_visitors WHERE userID='".$id."' AND visitor='".$userID."'")))
			safe_query("UPDATE ".PREFIX."user_visitors SET date='".$date."' WHERE userID='".$id."' AND visitor='".$userID."'");
			else safe_query("INSERT INTO ".PREFIX."user_visitors (userID, visitor, date) values ('".$id."', '".$userID."', '".$date."')");
		}
		$anzvisits = $ds['visits'];
		if($ds['userpic']) $userpic = '<img src="images/userpics/'.$ds['userpic'].'" alt="" />';
		else $userpic = '<img src="images/userpics/nouserpic.jpg" alt=""/>';
		$nickname = $ds['nickname'];
		$registered = date("F dS, Y", $ds['registerdate']);
		
		/************************************/
		$time = time();
		$logintime = $ds['lastlogin'];

		$sec = $time - $logintime;
		$days = $sec / 86400;								// sekunden / (60*60*24)
		$days = mb_substr($days, 0, mb_strpos($days, "."));		// kommastelle

		$sec = $sec - $days * 86400;
		$hours = $sec / 3600;
		$hours = mb_substr($hours, 0, mb_strpos($hours, "."));

		$sec = $sec - $hours * 3600;
		$minutes = $sec / 60;
		$minutes = mb_substr($minutes, 0, mb_strpos($minutes, "."));
		if($time - $logintime < 60) {
			$now = "Online";
			$days = "";
			$hours = "";
			$minutes = "";
		}
		else {
			$now = '';
			if($days!=0){
			$days = $days.' Days ago';
			$hours = "";
			$minutes = "";
		}elseif($hours!=0){
			$days = "";
			$hours = $hours.' Hours ago';
			$minutes = "";
		}elseif($minutes!=0){
			$days = "";
			$hours = "";
			$minutes = $minutes.' Minutes ago';
		}
		}
		
		$lastlogin = $now.$days.$hours.$minutes;
		/************************************/
		
		if($ds['avatar']) $avatar = '<img src="images/avatars/'.$ds['avatar'].'" alt="" />';
		else $avatar = '<img src="images/avatars/noavatar.gif" border="0" alt="" />';
		
		if($ds['userbanner']) $userbanner = 'images/userbanner/'.$ds['userbanner'].'';
		else $userbanner = 'images/userbanner/nouserbanner.jpg';
		
		$status = isonline($ds['userID']);
		if(isonline($ds['userID'])==0) $status = '<img src="sm/core/profiles/offline.png" alt="" class="status" />';
		else $status = '<img src="sm/core/profiles/online.png" alt="" class="status" />';
		if($ds['email_hide']) $email = $_language->module['n_a'];
		else $email = '<a href="mailto:'.mail_protect(cleartext($ds['email'])).'"><img src="images/icons/email.gif" border="0" alt="'.$_language->module['email'].'" /></a>';
		$sem = '/[0-9]{4,11}/si';
		if(preg_match($sem, $ds['icq'])) $icq = '<a href="http://www.icq.com/people/about_me.php?uin='.sprintf('%d', $ds['icq']).'" target="_blank"><img src="http://online.mirabilis.com/scripts/online.dll?icq='.sprintf('%d', $ds['icq']).'&amp;img=5" border="0" alt="icq" /></a>';
		else $icq='Not added';
		if($loggedin && $ds['userID'] != $userID) {
			$pm = '<a href="mail/write/to/'.$ds['userID'].'-element/" class="message">Send Message</a>';
			if(isignored($userID, $ds['userID'])) $buddy = '';
			elseif(isbuddy($userID, $ds['userID'])) $buddy = '<a href="friend/remove/'.$ds['userID'].'/'.$userID.'/" class="remove">Remove friend</a>';
			elseif($userID == $ds['userID']) $buddy = '';
			else $buddy = '<a href="friend/add/'.$ds['userID'].'/'.$userID.'/" class="add">Add to friends</a>';
		}
		else $pm = '' & $buddy = '' & $userlogmenu = '';
		
		if($loggedin && $ds['userID'] == $userID) {
			$userlogmenu ='<a href="settings/" class="settings">Change your settings</a>';
		}
		else $userlogmenu ='';

		if($ds['homepage']!='') {
			if(stristr($ds['homepage'],"http://")) $homepage = '<a href="'.htmlspecialchars($ds['homepage']).'" target="_blank" rel="nofollow">'.htmlspecialchars($ds['homepage']).'</a>';
			else $homepage = '<a href="http://'.htmlspecialchars($ds['homepage']).'" target="_blank" rel="nofollow">'.htmlspecialchars($ds['homepage']).'</a>';
		}
		else $homepage = '';

		$clanhistory = clearfromtags($ds['clanhistory']);
		if($clanhistory == '') $clanhistory = $_language->module['n_a'];
		$clanname = clearfromtags($ds['clanname']);
		if($clanname == '') $clanname = $_language->module['n_a'];
				
		$clantag = clearfromtags($ds['clantag']);
		if($clantag == '') $clantag = '';
		else $clantag = '('.$clantag.') ';

		$firstname = clearfromtags($ds['firstname']);
		$lastname = clearfromtags($ds['lastname']);
		
		// Mod by Tiago Ferreira @ www.tiagof.com
		$allbanner = safe_query("SELECT * FROM ".PREFIX."bannerrotation WHERE displayed='1' AND bannertype='2' ORDER BY RAND() LIMIT 0,1");
		$total = mysql_num_rows($allbanner);
		if($total) {
			$banner = mysql_fetch_array($allbanner);
			$pub = '<a href="ads/out/'.$banner['bannerID'].'/" target="_blank"><img src="./images/bannerrotation/'.$banner['banner'].'" border="0" alt="'.htmlspecialchars($banner['bannername']).'" /></a>';
}
// Mod by Tiago Ferreira @ www.tiagof.com

		$birthday = mb_substr($ds['birthday'], 0, 10);
		$birthday = date("F jS, Y",strtotime($birthday));
		
		$res = safe_query("SELECT birthday, DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(birthday)), '%Y') 'age' FROM ".PREFIX."user WHERE userID = '".$id."'");
		$cur = mysql_fetch_array($res);
		$birthday = ''.(int)$cur['age'].' ('.$birthday.')';

		if($ds['sex'] == "f") $sex = $_language->module['female'];
		elseif($ds['sex'] == "m") $sex = $_language->module['male'];
		else $sex = $_language->module['unknown'];
		$flag = '[flag]'.$ds['country'].'[/flag]';
		$profilecountry = flags($flag);
		$getcountry = mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."countries WHERE short='".$ds['country']."'"));
        $countryname = $getcountry['country'];
		$town = clearfromtags($ds['town']);
		if($town == '') $town = $_language->module['n_a'];
		$cpu = clearfromtags($ds['cpu']);
		if($cpu == '') $cpu = $_language->module['n_a'];
			
		if($ds['mainboard'] == '') $mainboard = '<img style="opacity:0.4; filter:alpha(opacity=40);" src="./sm/social/twitter.png" border="0" alt="Steam" />';
		else {
			$mainboard = '<a href="http://www.twitter.com/'.clearfromtags($ds['mainboard']).'" target="_blank" rel="nofollow"><img src="./sm/social/twitter.png" border="0" alt="Steam" /></a>';
		}
		
		if($ds['clanirc'] == '') $clanirc = '<img style="opacity:0.4; filter:alpha(opacity=40);" src="./sm/social/facebook.png" border="0" alt="Facebook" />';
		else {
			$clanirc = '<a href="http://sfacebook.com/'.clearfromtags($ds['clanirc']).'" target="_blank" rel="nofollow"><img src="./sm/social/facebook.png" border="0" alt="Facebook" /></a>';}
			
		if($ds['clanhp'] == '') $clanhp = '<img style="opacity:0.4; filter:alpha(opacity=40);" src="./sm/social/steam.png" border="0" alt="Steam" />';
		else {
			$clanhp = '<a href="http://steamcommunity.com/id/'.clearfromtags($ds['clanhp']).'" target="_blank" rel="nofollow"><img src="./sm/social/steam.png" border="0" alt="Steam" /></a>';
		}
		
		$ram = clearfromtags($ds['ram']);
		if($ram == '') $ram = $_language->module['n_a'];
		$monitor = clearfromtags($ds['monitor']);
		if($monitor == '') $monitor = $_language->module['n_a'];
		$graphiccard = clearfromtags($ds['graphiccard']);
		if($graphiccard == '') $graphiccard = $_language->module['n_a'];
		$soundcard = clearfromtags($ds['soundcard']);
		if($soundcard == '') $soundcard = $_language->module['n_a'];
		$keyboard = clearfromtags($ds['keyboard']);
		if($keyboard == '') $keyboard = $_language->module['n_a'];
		$mouse = clearfromtags($ds['mouse']);
		if($mouse == '') $mouse = $_language->module['n_a'];
		$mousepad = clearfromtags($ds['mousepad']);
		if($mousepad == '') $mousepad = $_language->module['n_a'];
		
		/******HardwareMod******/
		
		/************************************************/

		$anznewsposts = getusernewsposts($ds['userID']);
		$anzforumtopics = getuserforumtopics($ds['userID']);
		$anzforumposts = getuserforumposts($ds['userID']);
		
		$comments = array();
		$comments[] = getusercomments($ds['userID'], 'ne');
		$comments[] = getusercomments($ds['userID'], 'cw');
		$comments[] = getusercomments($ds['userID'], 'ar');
		$comments[] = getusercomments($ds['userID'], 'de');

		$pmgot = 0;
		$pmgot = $ds['pmgot'];

		$pmsent = 0;
		$pmsent = $ds['pmsent'];

		if($ds['about']) $about =''.cleartext($ds['about']).'';
		else $about = 'No user data.';

		if(isforumadmin($ds['userID'])) {
			$usertype = $_language->module['administrator'];
			$rang = '<img src="images/icons/ranks/admin.gif" alt="" />';
		}
		elseif(isanymoderator($ds['userID'])) {
			$usertype = $_language->module['moderator'];
			$rang = '<img src="images/icons/ranks/moderator.gif" alt="" />';
		}
		else {
			$posts = getuserforumposts($ds['userID']);
			$ergebnis = safe_query("SELECT * FROM ".PREFIX."forum_ranks WHERE ".$posts." >= postmin AND ".$posts." <= postmax AND postmax >0");
			$ds = mysql_fetch_array($ergebnis);
			$usertype = $ds['rank'];
			$rang = '<img src="images/icons/ranks/'.$ds['pic'].'" alt="" />';
		}

		$lastvisits="";
		$visitors = safe_query("SELECT v.*, u.nickname, u.country FROM ".PREFIX."user_visitors v JOIN ".PREFIX."user u ON u.userID = v.visitor WHERE v.userID='".$id."' ORDER BY v.date DESC LIMIT 0,8");
		if(mysql_num_rows($visitors)) {
			$n = 1;
			while($dv = mysql_fetch_array($visitors)) {
				$n % 2 ? $bgcolor = BG_1 : $bgcolor = BG_2;
				$flag = '[flag]'.$dv['country'].'[/flag]';
				$country = flags($flag);
				$nicknamevisitor = $dv['nickname'];
				if(isonline($dv['visitor']) == "offline") $statuspic = '<img src="images/icons/offline.gif" alt="'.$_language->module['offline'].'" />';
				else $statuspic = '<img src="images/icons/online.gif" alt="'.$_language->module['online'].'" />';
				$time = time();
				$visittime = $dv['date'];

				$sec = $time - $visittime;
				$days = $sec / 86400;								// sekunden / (60*60*24)
				$days = mb_substr($days, 0, mb_strpos($days, "."));		// kommastelle

				$sec = $sec - $days * 86400;
				$hours = $sec / 3600;
				$hours = mb_substr($hours, 0, mb_strpos($hours, "."));

				$sec = $sec - $hours * 3600;
				$minutes = $sec / 60;
				$minutes = mb_substr($minutes, 0, mb_strpos($minutes, "."));

				if($time - $visittime < 60) {
					$now = '<font style="color: #0C3; text-decoration: blink;">now</font>';
					$days = "";
					$hours = "";
					$minutes = "";
				}
				else {
					$now = '';
                    if($days!=0){
                    $days = $days.' Days ago';
                    $hours = "";
                    $minutes = "";
                }elseif($hours!=0){
                    $days = "";
                    $hours = $hours.' Hours ago';
                    $minutes = "";
                }elseif($minutes!=0){
                    $days = "";
                    $hours = "";
                    $minutes = $minutes.' Minutes ago';
                }
				}

				$lvisituserimg = getlvisituserimg($nicknamevisitor);
				
				$lastvisits .= '<a href="user/'.$dv['visitor'].'/"><img src="images/userpics/'.$lvisituserimg.'" width="60" height="60" alt="" /></a>';
        
				$n++;
			}
		}
		else $lastvisits = '<span style="margin:6px 0px 0px 6px; float: left;">No Visitors yet.</span>';
	 
		$bg1 = BG_1;
		$bg2 = BG_2;
		$bg3 = BG_3;
		$bg4 = BG_4;

		eval("\$profile = \"".gettemplate("profile")."\";");
		echo $profile;
	}

}
else redirect('index.php', $_language->module['user_doesnt_exist'],3);
?>