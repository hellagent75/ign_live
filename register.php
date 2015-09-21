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

$_language->read_module('register');

$show = true;
if(isset($_POST['save'])) {

	if(!$loggedin){
		$username = mb_substr(trim($_POST['username']), 0, 30);
		$nickname = $username;
		$pwd1 = $_POST['pwd1'];
		$pwd2 = $_POST['pwd2'];
		$mail = $_POST['mail'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$sex = $_POST['sex'];
		$b_day = $_POST['b_day'];
		$b_month = $_POST['b_month'];
		$b_year = $_POST['b_year'];
		$CAPCLASS = new Captcha;
		
		$birthday = $b_year.'-'.$b_month.'-'.$b_day;
		
		$error = array();
	  	
	  // check nickname
		if(!(mb_strlen(trim($nickname)))) $error[]=$_language->module['enter_nickname'];
	  
	  // check nickname inuse
		$ergebnis = safe_query("SELECT * FROM ".PREFIX."user WHERE nickname = '$nickname' ");
		$num = mysql_num_rows($ergebnis);
		if($num) $error[]=$_language->module['nickname_inuse'];
	  
	  // check username
	  	if(!(mb_strlen(trim($username)))) $error[]=$_language->module['enter_username'];
		elseif(mb_strlen(trim($username)) > 30 ) $error[]=$_language->module['username_toolong'];
	    
	  // check First Name
		if(!(mb_strlen(trim($firstname)))) $error[]='Please enter a First Name.';
		
	  // check Last Name
		if(!(mb_strlen(trim($lastname)))) $error[]='Please enter a Last Name.';
	  
	  // check passwort
		if($pwd1 == $pwd2) {
			if(!(mb_strlen(trim($pwd1)))) $error[]=$_language->module['enter_password'];
		}
		else $error[]=$_language->module['repeat_invalid'];
	  
	  // check e-mail
		if(!validate_email($mail)) $error[]=$_language->module['invalid_mail'];
	  
	  // check e-mail inuse
		$ergebnis = safe_query("SELECT userID FROM ".PREFIX."user WHERE email = '$mail' ");
		$num = mysql_num_rows($ergebnis);
		if($num) $error[]=$_language->module['mail_inuse'];
	  
	  // check captcha
	  	if(!$CAPCLASS->check_captcha($_POST['captcha'], $_POST['captcha_hash'])) $error[]=$_language->module['wrong_securitycode'];
	  
	  	if(count($error)) {
	    	$list = implode('<br />&#8226; ', $error);
	    	$showerror = '<div class="settings-error-error">
	      	<b>Something is wrong:</b><br /><br />
	      	&#8226; '.$list.'
	    	</div>';
		}
		else {
			// insert in db
			$md5pwd = md5(stripslashes($pwd1));
			$registerdate=time();
			$activationkey = createkey(20);
			$activationlink='http://'.$hp_url.'/register/key/'.$activationkey;
	
			safe_query("INSERT INTO `".PREFIX."user` (`registerdate`, `lastlogin`, `username`, `password`, `nickname`, `firstname`, `lastname`, `sex`, `country`, `birthday`, `email`, `newsletter`, `activated`) VALUES ('$registerdate', '$registerdate', '$username', '$md5pwd', '$nickname', '$firstname', '$lastname', '$sex', '$country', '$birthday', '$mail', '1', '".$activationkey."')");
	
			$insertid = mysql_insert_id();
	
			// insert in user_groups
			safe_query("INSERT INTO ".PREFIX."user_groups ( userID ) values('$insertid' )");
			
			// mail to user
			$ToEmail = $mail;
			$ToName = $username;
			$header =  str_replace(Array('%username%', '%password%', '%activationlink%', '%pagetitle%', '%homepage_url%'), Array(stripslashes($username), stripslashes($pwd1), stripslashes($activationlink), $hp_title, $hp_url), $_language->module['mail_subject']);
			$Message = str_replace(Array('%username%', '%password%', '%activationlink%', '%pagetitle%', '%homepage_url%'), Array(stripslashes($username), stripslashes($pwd1), stripslashes($activationlink), $hp_title, $hp_url), $_language->module['mail_text']);
	
			if(mail($ToEmail,$header, $Message, "From:".$admin_email."\nContent-type: text/plain; charset=utf-8\n")){
				echo'<h3>Account Created</h3>You have successfully registered your account, Please wait for your activation email.<div style="height:400px;"></div>';
				$show = false;
			}
			else{
				echo'<h3>Account Activation</h3> E-mail activation can not be sent!<br/>Please report this error to the webmaster.<div style="height:400px;"></div>';
				$show = false;
			}
		}
	}
	else{
		echo'<h3>Create Account</h3>You are already registered and logged in.<div style="height:400px;"></div>';
	}
}
if(isset($_GET['key'])) {
	safe_query("UPDATE `".PREFIX."user` SET activated='1' WHERE activated='".$_GET['key']."'");
	if(mysql_affected_rows()) echo'<h3>Key</h3>You have successfully activated your account!<div style="height:400px;"></div>';
	else echo'<h3>Activation Key</h3>You have entered an invalid activation key!<div style="height:400px;"></div>';

}
elseif(isset($_GET['mailkey'])) {
  if(mb_strlen(trim($_GET['mailkey']))==32){
		safe_query("UPDATE `".PREFIX."user` SET email_activate='1', email=email_change, email_change='' WHERE email_activate='".$_GET['mailkey']."'");
		if(mysql_affected_rows()) echo'<h3>Account is Active!</h3>You have successfully activated your account!<div style="height:400px;"></div>';
		else echo'<h3>invalid Activation Key</h3>You have entered an invalid activation key.<div style="height:400px;"></div>';
  }
}
else {
	if($show == true){
		if(!$loggedin){
			$bg1=BG_1;
			$bg2=BG_2;
			$bg3=BG_3;
			$bg4=BG_4;
		
			$CAPCLASS = new Captcha;
			$captcha = $CAPCLASS->create_captcha();
			$hash = $CAPCLASS->get_hash();
			$CAPCLASS->clear_oldcaptcha();
			
			$sex = '<option value="m">Male</option><option value="f">Female</option>';
			$countries = str_replace(" selected=\"selected\"", "", $countries);
		
			if(!isset($showerror)) $showerror='';
			if(isset($_POST['nickname'])) $nickname=getforminput($_POST['nickname']);
			else $nickname='';
			if(isset($_POST['firstname'])) $firstname=getforminput($_POST['firstname']);
			else $firstname='';
			if(isset($_POST['lastname'])) $lastname=getforminput($_POST['lastname']);
			else $lastname='';
			if(isset($_POST['b_day'])) $b_day=getforminput($_POST['b_day']);
			else $b_day='';
			if(isset($_POST['b_month'])) $b_month=getforminput($_POST['b_month']);
			else $b_month='';
			if(isset($_POST['b_year'])) $b_year=getforminput($_POST['b_year']);
			else $b_year='';
			if(isset($_POST['pwd1'])) $pwd1=getforminput($_POST['pwd1']);
			else $pwd1='';
			if(isset($_POST['pwd2'])) $pwd2=getforminput($_POST['pwd2']);
			else $pwd2='';
			if(isset($_POST['mail'])) $mail=getforminput($_POST['mail']);
			else $mail='';
			
			if(isset($_POST['sex'])) $sex = str_replace('value="'.getforminput($_POST['sex']).'"', 'value="'.getforminput($_POST['sex']).'" selected="selected"', $sex);
			if(isset($_POST['country'])) $countries = str_replace('value="'.getforminput($_POST['country']).'"', 'value="'.getforminput($_POST['country']).'" selected="selected"', $countries);
		
			eval("\$register = \"".gettemplate("register")."\";");
			echo $register;
		}
		else echo'<h3>Create Account</h3>You are already registered.<div style="height:400px;"></div>';		
	}
}

?>