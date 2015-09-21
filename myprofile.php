<?php
$_language->read_module('myprofile');

if(!$userID) echo $_language->module['not_logged_in'];

else {

	eval ("\$title_myprofile = \"".gettemplate("title_myprofile")."\";");
	echo $title_myprofile;
	$showerror = '';

	if(isset($_POST['submit'])) {
		$nickname = htmlspecialchars(mb_substr(trim($_POST['nickname']), 0, 30));
		if(isset($_POST['mail'])) $mail = $_POST['mail'];
    	else $mail="";
		if(isset($_POST['mail_hide'])) $mail_hide = true;
		else $mail_hide = false;
		$usernamenew = mb_substr(trim($_POST['usernamenew']), 0, 30);
		$usertext = $_POST['usertext'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		
		/*********HARDWARE*********/
		
	
		/**************************/
		
		$b_day = $_POST['b_day'];
		$b_month = $_POST['b_month'];
		$b_year = $_POST['b_year'];
		$sex = $_POST['sex'];
		$flag = preg_replace("/[^a-zA-Z0-9\s]/", "", $_POST['flag']);
		$town = $_POST['town'];
		$icq = $_POST['icq'];
		$icq = str_replace('-','',$icq); // Replace - 
		$about = $_POST['messageabout'];
		$clantag = $_POST['clantag'];
		$clanname = $_POST['clanname'];
		$clanhp = $_POST['clanhp'];
		$clanirc = $_POST['clanirc'];
		$clanhistory = $_POST['clanhistory'];
		$cpu = $_POST['cpu'];
		$mainboard = $_POST['mainboard'];
		$monitor = $_POST['monitor'];
		$ram = $_POST['ram'];
		$graphiccard = $_POST['graphiccard'];
		$soundcard = $_POST['soundcard'];
		$connection = $_POST['connection'];
		$keyboard = $_POST['keyboard'];
		$mouse = $_POST['mouse'];
		$mousepad = $_POST['mousepad'];
		$newsletter = $_POST['newsletter'];
		$homepage = str_replace('http://', '', $_POST['homepage']);
		$pm_mail = $_POST['pm_mail'];
		$avatar = $_FILES['avatar'];
		$userpic = $_FILES['userpic'];
		$userbanner = $_FILES['userbanner'];
		$language = $_POST['language'];
		$id = $userID;
		
		$error_array = array();
		
		if(isset($_POST['userID']) or isset($_GET['userID']) or $userID=="") die($_language->module['not_logged_in']);

		if(isset($_POST['delavatar'])) {
			$filepath = "./images/avatars/";
			if(file_exists($filepath.$id.'.gif')) @unlink($filepath.$id.'.gif');
			if(file_exists($filepath.$id.'.jpg')) @unlink($filepath.$id.'.jpg');
			if(file_exists($filepath.$id.'.png')) @unlink($filepath.$id.'.png');
			safe_query("UPDATE ".PREFIX."user SET avatar='' WHERE userID='".$id."'");
		}
		if(isset($_POST['deluserpic'])) {
			$filepath = "./images/userpics/";
			if(file_exists($filepath.$id.'.gif')) @unlink($filepath.$id.'.gif');
			if(file_exists($filepath.$id.'.jpg')) @unlink($filepath.$id.'.jpg');
			if(file_exists($filepath.$id.'.png')) @unlink($filepath.$id.'.png');
			safe_query("UPDATE ".PREFIX."user SET userpic='' WHERE userID='".$id."'");
		}
		if(isset($_POST['deluserbanner'])) {
			$filepath = "./images/userbanner/";
			if(file_exists($filepath.$id.'.gif')) @unlink($filepath.$id.'.gif');
			if(file_exists($filepath.$id.'.jpg')) @unlink($filepath.$id.'.jpg');
			if(file_exists($filepath.$id.'.png')) @unlink($filepath.$id.'.png');
			safe_query("UPDATE ".PREFIX."user SET userbanner='' WHERE userID='".$id."'");
		}

		//avatar
		$filepath = "./images/avatars/";
		if($avatar['name'] != "" or ($_POST['avatar_url'] != "" and $_POST['avatar_url'] != "http://")) {
			if($avatar['name'] != "") {
				move_uploaded_file($avatar['tmp_name'], $filepath.$avatar['name'].".tmp");
			}
			else {
				$avatar['name'] = strrchr($_POST['avatar_url'],"/");
				if(!copy($_POST['avatar_url'],$filepath.$avatar['name'].".tmp")) {
					$error_array['can_not_copy'] = $_language->module['can_not_copy'];
				}
			}
			if(!array_key_exists('can_not_copy', $error_array))
			{
				@chmod($filepath.$avatar['name'].".tmp", $new_chmod);
				$info = getimagesize($filepath.$avatar['name'].".tmp");
				if($info[0] < 101 && $info[1] < 101) {
					$pic = '';
					if($info[2] == 1) $pic=$id.'.gif';
					elseif($info[2] == 2) $pic=$id.'.jpg';
					elseif($info[2] == 3) $pic=$id.'.png';
					if($pic != "") {
						if(file_exists($filepath.$id.'.gif')) @unlink($filepath.$id.'.gif');
						if(file_exists($filepath.$id.'.jpg')) @unlink($filepath.$id.'.jpg');
						if(file_exists($filepath.$id.'.png')) @unlink($filepath.$id.'.png');
						rename($filepath.$avatar['name'].'.tmp', $filepath.$pic);
						safe_query("UPDATE ".PREFIX."user SET avatar='".$pic."' WHERE userID='".$id."'");
					}
					else {
						if(unlink($filepath.$avatar['name'].".tmp")) {
							$error_array[] = $_language->module['invalid_picture-format'];
						}
						else {
							$error_array[] = $_language->module['upload_failed'];
						}
					}
				}
				else {
					@unlink($filepath.$avatar['name'].".tmp");
					$error_array[] = 'Avatar picture is to big, max:100x100px';
				}
			}
		}

		//userpic
		$filepath = "./images/userpics/";
		if($userpic['name'] != "" or ($_POST['userpic_url'] != "" and $_POST['userpic_url'] != "http://")) {
			if($userpic['name'] != "") {
				move_uploaded_file($userpic['tmp_name'], $filepath.$userpic['name'].".tmp");
			} else {
				$userpic['name'] = strrchr($_POST['userpic_url'],"/");
				if(!copy($_POST['userpic_url'],$filepath.$userpic['name'].".tmp")) {
					$error_array['can_not_copy'] = $_language->module['can_not_copy'];
				}
			}
			if(!array_key_exists('can_not_copy', $error_array))
			{
				@chmod($filepath.$userpic['name'].".tmp", $new_chmod);
				$info = getimagesize($filepath.$userpic['name'].".tmp");
				if($info[0] < 281 && $info[1] < 281) {
					$pic = '';
					if($info[2] == 1) $pic=$id.'.gif';
					elseif($info[2] == 2) $pic=$id.'.jpg';
					elseif($info[2] == 3) $pic=$id.'.png';
					if($pic != "") {
						if(file_exists($filepath.$id.'.gif')) @unlink($filepath.$id.'.gif');
						if(file_exists($filepath.$id.'.jpg')) @unlink($filepath.$id.'.jpg');
						if(file_exists($filepath.$id.'.png')) @unlink($filepath.$id.'.png');
						rename($filepath.$userpic['name'].".tmp", $filepath.$pic);
						safe_query("UPDATE ".PREFIX."user SET userpic='".$pic."' WHERE userID='".$id."'");
					}
					else {
						if(unlink($filepath.$userpic['name'].".tmp")) {
							$error_array[] = $_language->module['invalid_picture-format'];
						}
						else {
							$error_array[] = $_language->module['upload_failed'];
						}
					}
				}
				else {
					@unlink($filepath.$userpic['name'].".tmp");
					$error_array[] = 'Profile picture is to big, max:280x280px';
				}
			}
		}
		//userbanner
		$filepath = "./images/userbanner/";
		if($userbanner['name'] != "" or ($_POST['userbanner_url'] != "" and $_POST['userbanner_url'] != "http://")) {
			if($userbanner['name'] != "") {
				move_uploaded_file($userbanner['tmp_name'], $filepath.$userbanner['name'].".tmp");
			} else {
				$userbanner['name'] = strrchr($_POST['userbanner_url'],"/");
				if(!copy($_POST['userbanner_url'],$filepath.$userbanner['name'].".tmp")) {
					$error_array['can_not_copy'] = $_language->module['can_not_copy'];
				}
			}
			if(!array_key_exists('can_not_copy', $error_array))
			{
				@chmod($filepath.$userbanner['name'].".tmp", $new_chmod);
				$info = getimagesize($filepath.$userbanner['name'].".tmp");
				if($info[0] < 1001 && $info[1] < 301) {
					$pic = '';
					if($info[2] == 1) $pic=$id.'.gif';
					elseif($info[2] == 2) $pic=$id.'.jpg';
					elseif($info[2] == 3) $pic=$id.'.png';
					if($pic != "") {
						if(file_exists($filepath.$id.'.gif')) @unlink($filepath.$id.'.gif');
						if(file_exists($filepath.$id.'.jpg')) @unlink($filepath.$id.'.jpg');
						if(file_exists($filepath.$id.'.png')) @unlink($filepath.$id.'.png');
						rename($filepath.$userbanner['name'].".tmp", $filepath.$pic);
						safe_query("UPDATE ".PREFIX."user SET userbanner='".$pic."' WHERE userID='".$id."'");
					}
					else {
						if(unlink($filepath.$userbanner['name'].".tmp")) {
							$error_array[] = $_language->module['invalid_picture-format'];
						}
						else {
							$error_array[] = $_language->module['upload_failed'];
						}
					}
				}
				else {
					@unlink($filepath.$userbanner['name'].".tmp");
					$error_array[] = 'Profile picture is to big, max:1000x300px';
				}
			}
		}

		$birthday = $b_year.'-'.$b_month.'-'.$b_day;

		$qry = "SELECT userID FROM ".PREFIX."user WHERE username = '".$usernamenew."' AND userID != ".$userID." LIMIT 0,1";
		if(mysql_num_rows(safe_query($qry))) {
			$error_array[] = $_language->module['username_aleady_in_use'];
		}
		
		$qry = "SELECT userID FROM ".PREFIX."user WHERE nickname = '".$nickname."' AND userID!=".$userID." LIMIT 0,1";
		if(mysql_num_rows(safe_query($qry))) {
				$error_array[] = $_language->module['nickname_already_in_use'];
		}

		if(count($error_array)) 
		{
			$fehler=implode('', $error_array);
			$showerror = '<span class="settings-error-error">Some errors occured:<br/>'.$fehler.'</span>';
		}
		else
		{
			safe_query("UPDATE `".PREFIX."user`
						SET 
							nickname='".$nickname."',
							username='".$usernamenew."',
							email_hide='".$mail_hide."',
							firstname='".$firstname."',
							lastname='".$lastname."',
							sex='".$sex."',
							country='".$flag."',
							town='".$town."',
							birthday='".$birthday."',
							icq='".$icq."',
							usertext='".$usertext."',
							clantag='".$clantag."',
							clanname='".$clanname."',
							clanhp='".$clanhp."',
							clanirc='".$clanirc."',
							clanhistory='".$clanhistory."',
							cpu='".$cpu."',
							mainboard='".$mainboard."',
							ram='".$ram."',
							monitor='".$monitor."',
							graphiccard='".$graphiccard."',
							soundcard='".$soundcard."',
							verbindung='".$connection."',
							keyboard='".$keyboard."',
							mouse='".$mouse."',
							mousepad='".$mousepad."',
							mailonpm='".$pm_mail."',
							newsletter='".$newsletter."',
							homepage='".$homepage."',
							about='".$about."',
							language='".$language."'
						WHERE 
							userID='".$id."'");
	
			redirect("", '<span class="settings-error-success">Profile updated successfuly!</span>',2);
		}
  }

	if(isset($_GET['action']) AND $_GET['action']=="editpwd") {
	
		$bg1 = BG_1;
		$bg2 = BG_2;
	  	$bg3 = BG_3;
		$bg4 = BG_4;
		$border = BORDER;
	
		eval("\$myprofile_editpwd = \"".gettemplate("myprofile_editpwd")."\";");
		echo $myprofile_editpwd;

	}	
	
	elseif(isset($_POST['savepwd'])) {

		$oldpwd = $_POST['oldpwd'];
		$pwd1 = $_POST['pwd1'];
		$pwd2 = $_POST['pwd2'];
		$id = $userID;

		$ergebnis = safe_query("SELECT password FROM ".PREFIX."user WHERE userID='".$id."'");
		$ds = mysql_fetch_array($ergebnis);

		if(!(mb_strlen(trim($oldpwd)))) {
			$error = $_language->module['forgot_old_pw'];
			die('<h2>ERROR</h2>'.$error.'<br/><input type="button" onclick="javascript:history.back()" value="'.$_language->module['back'].'" />');
		}
		$oldmd5pwd = md5($oldpwd);
		if($oldmd5pwd != $ds['password']) {
			$error = $_language->module['old_pw_not_valid'];
			die('<h2>ERROR</h2> '.$error.'<br/><input type="button" onclick="javascript:history.back()" value="'.$_language->module['back'].'" />');
		}
		if($pwd1 == $pwd2) {
			if(!(mb_strlen(trim($pwd1)))) {
				$error = $_language->module['forgot_new_pw'];
				die('<h2>ERROR</h2> '.$error.'<br/><input type="button" onclick="javascript:history.back()" value="'.$_language->module['back'].'" />');
			}
		}
		else {
			$error = $_language->module['repeated_pw_not_valid'];
			die('<h2>ERROR</h2>'.$error.'<br /><input type="button" onclick="javascript:history.back()" value="'.$_language->module['back'].'" />');
		}
		$newmd5pwd = md5(stripslashes($pwd1));
		safe_query("UPDATE ".PREFIX."user SET password='".$newmd5pwd."' WHERE userID='".$userID."'");

		//logout
		unset($_SESSION['ws_auth']);
		unset($_SESSION['ws_lastlogin']);
		session_destroy();

    redirect('', '<div class="settings-error-success">You have changed your password Successfuly.</div>',2);

	}	
	
	elseif(isset($_GET['action']) AND $_GET['action']=="editmail") {

		$bg1 = BG_1;
		$bg2 = BG_2;
    $bg3 = BG_3;
		$bg4 = BG_4;
		$border = BORDER;

		eval("\$myprofile_editmail = \"".gettemplate("myprofile_editmail")."\";");
		echo $myprofile_editmail;

	}	
	
	elseif(isset($_POST['savemail'])){

		$activationkey = createkey(20);
		$activationlink = 'http://'.$hp_url.'/index.php?site=register&mailkey='.$activationkey;
		$pwd = $_POST['oldpwd'];
		$mail1 = $_POST['mail1'];
		$mail2 = $_POST['mail2'];

		$ergebnis = safe_query("SELECT password, username FROM ".PREFIX."user WHERE userID='".$userID."'");
		$ds = mysql_fetch_array($ergebnis);
		$username = $ds['username'];
		if(!(mb_strlen(trim($pwd)))) {
			$error = $_language->module['forgot_old_pw'];
			die('<h2>ERROR</h2> '.$error.'<br/><input type="button" onclick="javascript:history.back()" value="'.$_language->module['back'].'" />');
		}
		$md5pwd = md5(stripslashes($pwd));
		if($md5pwd != $ds['password']) {
			die('<h2>ERROR</h2> '.$error.'<br /><input type="button" onclick="javascript:history.back()" value="'.$_language->module['back'].'" />');
		}
		if($mail1 == $mail2) {
			if(!(mb_strlen(trim($mail1)))) {
				$error = $_language->module['mail_not_valid'];
				die('<h2>ERROR</h2> '.$error.'<br /><input type="button" onclick="javascript:history.back()" value="'.$_language->module['back'].'" />');
			}
		}
		else {
			$error = $_language->module['repeated_pw_not_valid'];
			die('<h2>ERROR</h2> '.$error.'<br /><input type="button" onclick="javascript:history.back()" value="'.$_language->module['back'].'" />');
		}

		// check e-mail
		
		if(!validate_email($mail1)){ 
			$error=$_language->module['invalid_mail'];
			die('<h2>ERROR</h2> '.$error.'<br /><input type="button" onclick="javascript:history.back()" value="'.$_language->module['back'].'" />');
		}
		
		safe_query("UPDATE ".PREFIX."user SET email_change = '".$mail1."', email_activate = '".$activationkey."' WHERE userID='".$userID."'");

		$ToEmail = $mail1;
		$ToName = $username;
		$header =  str_replace(Array('%homepage_url%'), Array($hp_url), $_language->module['mail_subject']);
		$Message = str_replace(Array('%username%', '%activationlink%', '%pagetitle%', '%homepage_url%'), Array($username, $activationlink, $hp_title, $hp_url), $_language->module['mail_text']);

		if(mail($ToEmail,$header, $Message, "From:".$admin_email."\nContent-type: text/plain; charset=utf-8\n")) echo $_language->module['mail_changed'];
		else echo $_language->module['mail_failed'];

	}	
	
	else {
		$ergebnis = safe_query("SELECT * FROM ".PREFIX."user WHERE userID='".$userID."'");
		$anz = mysql_num_rows($ergebnis);
		if($anz) {
			$ds = mysql_fetch_array($ergebnis);
			$flag = '[flag]'.$ds['country'].'[/flag]';
			$country = flags($flag);
			$country = str_replace("<img","<img id='county'",$country);
			$sex = '<option value="m">'.$_language->module['male'].'</option><option value="f">'.$_language->module['female'].'</option><option value="u">'.$_language->module['unknown'].'</option>';
			$sex = str_replace('value="'.$ds['sex'].'"','value="'.$ds['sex'].'" selected="selected"',$sex);
			if($ds['newsletter'] == "1") $newsletter = '<option value="1" selected="selected">'.$_language->module['yes'].'</option><option value="0">'.$_language->module['no'].'</option>';
			else $newsletter = '<option value="1">'.$_language->module['yes'].'</option><option value="0" selected="selected">'.$_language->module['no'].'</option>';
			if($ds['mailonpm'] == "1") $pm_mail = '<option value="1" selected="selected">'.$_language->module['yes'].'</option><option value="0">'.$_language->module['no'].'</option>';
			else $pm_mail = '<option value="1">'.$_language->module['yes'].'</option><option value="0" selected="selected">'.$_language->module['no'].'</option>';
			if($ds['email_hide']) $email_hide = ' checked="checked"';
			else $email_hide = '';
			$b_day = mb_substr($ds['birthday'],8,2);
			$b_month = mb_substr($ds['birthday'],5,2);
			$b_year = mb_substr($ds['birthday'],0,4);
			$countries = str_replace(" selected=\"selected\"", "", $countries);
			$countries = str_replace('value="'.$ds['country'].'"', 'value="'.$ds['country'].'" selected="selected"', $countries);
			if($ds['avatar']) $viewavatar ='<img src="images/avatars/'.$ds['avatar'].'" width="80" height="80" alt="" style="box-shadow: 0 0 7px #080808; margin: 0px 15px 0px 0px; border-radius: 2px; float: left;">';
			else $viewavatar = '<img src="images/userpics/nouserpic.gif" width="80" style="box-shadow: 0 0 7px #080808; margin: 0px 15px 0px 0px; border-radius: 2px; float: left;" />';
			if($ds['userpic']) $viewpic = '<img src="images/userpics/'.$ds['userpic'].'" width="80" height="80" alt="" style="box-shadow: 0 0 7px #080808; margin: 0px 15px 0px 0px; border-radius: 2px; float: left;"></a>';
			else $viewpic = '<img src="images/userpics/nouserpic.gif" width="80" height="80" alt="" style="box-shadow: 0 0 7px #080808; margin: 0px 15px 0px 0px; border-radius: 2px; float: left;" />';
			
			if($ds['userbanner']) $viewuserbanner ='<img src="images/userbanner/'.$ds['userbanner'].'" width="80" height="65" alt="" style="box-shadow: 0 0 7px #080808; margin:0px 15px 0px 0px; border-radius: 2px; float: left;">';
			else $viewuserbanner = '<img src="images/userbanner/nouserbanner.jpg" width="80" height="65" style="box-shadow: 0 0 7px #080808; margin:0px 15px 0px 0px; border-radius: 2px; float: left;" />';

			$usertext = getinput($ds['usertext']);
			$clanhistory = clearfromtags($ds['clanhistory']);
			$clanname = clearfromtags($ds['clanname']);
			$clantag = clearfromtags($ds['clantag']);
			$clanirc = clearfromtags($ds['clanirc']);
			$firstname = clearfromtags($ds['firstname']);
			
			/***************************************************/
			
						
			/***************************************************/
			
			$lastname = clearfromtags($ds['lastname']);
			$town = clearfromtags($ds['town']);
			$cpu = clearfromtags($ds['cpu']);
			$mainboard = clearfromtags($ds['mainboard']);
			$ram = clearfromtags($ds['ram']);
			$monitor = clearfromtags($ds['monitor']);
			$graphiccard = clearfromtags($ds['graphiccard']);
			$soundcard = clearfromtags($ds['soundcard']);
			$connection = clearfromtags($ds['verbindung']);
			$keyboard = clearfromtags($ds['keyboard']);
			$mouse = clearfromtags($ds['mouse']);
			$mousepad = clearfromtags($ds['mousepad']);
			$clanhp = getinput($ds['clanhp']);
			$about = getinput($ds['about']);
			$nickname = $ds['nickname'];
			$username = getinput($ds['username']);
			$email = getinput($ds['email']);
			$icq = getinput($ds['icq']);
			$homepage = getinput($ds['homepage']);
			$langdirs = '';
			$filepath = "languages/";
			
			// Select all possible languages
			$mysql_langs = array();
			$query = safe_query("SELECT lang, language FROM ".PREFIX."news_languages");
			while($dx = mysql_fetch_assoc($query)){
				$mysql_langs[$dx['lang']] = $dx['language'];
			}
			if($dh = opendir($filepath)) {
				while($file = mb_substr(readdir($dh), 0, 2)) {
					if($file != "." and $file!=".." and is_dir($filepath.$file)) {
						if(isset($mysql_langs[$file])){
							$name = $mysql_langs[$file];
							$name = ucfirst($name);
							$langdirs .= '<option value="'.$file.'">'.$name.'</option>';
						}
						else {
							$langdirs .= '<option value="'.$file.'">'.$file.'</option>';
						}
					}
				}
				closedir($dh);
			}
			
			if($ds['language']) $langdirs = str_replace('"'.$ds['language'].'"', '"'.$ds['language'].'" selected="selected"', $langdirs);
			else $langdirs = str_replace('"'.$_language->language.'"', '"'.$_language->language.'" selected="selected"', $langdirs);
			
			$bg1 = BG_1;
			$bg2 = BG_2;
			$bg3 = BG_3;
			$bg4 = BG_4;

			eval("\$myprofile = \"".gettemplate("myprofile")."\";");
			echo $myprofile;

		}
		else echo $_language->module['not_logged_in'];
	}
}
?>