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

$_language->read_module('lostpassword');

if(isset($_POST['submit'])) {
	$email = trim($_POST['email']);
	if($email!=''){
		$ergebnis = safe_query("SELECT * FROM ".PREFIX."user WHERE email = '".$email."'");
		$anz = mysql_num_rows($ergebnis);
	
		if($anz) {
	
			$newpwd=RandPass(6);
			$newmd5pwd=md5($newpwd);
	
			$ds = mysql_fetch_array($ergebnis);
			safe_query("UPDATE ".PREFIX."user SET password='".$newmd5pwd."' WHERE userID='".$ds['userID']."'");
	
			$ToEmail = $ds['email'];
			$ToName = $ds['username'];
			$vars = Array('%pagetitle%', '%username%', '%new_password%', '%homepage_url%');
			$repl = Array($hp_title, $ds['username'], $newpwd, $hp_url);
			$header = str_replace($vars, $repl, $_language->module['email_subject']);
			$Message = str_replace($vars, $repl, $_language->module['email_text']);
	
			if(mail($ToEmail,$header, $Message, "From:".$admin_email."\nContent-type: text/plain; charset=utf-8\n"))
			echo str_replace($vars, $repl, '<div id="box-login">

          <h3>Lost Password</h3>
          <ul>
            <li><a href="login/">Login</a></li>
			<li><a href="register/">Create Account</a></li>
          </ul>
        </div>
        <div id="box-login-content">
          <form method="post" action="lostpassword/">
		    <b>Your account '.$ds['email'].' has been found.</b><br /><br />
			You will get an e-mail with your account data in a few seconds.
		 </form>
        </div>
      </div>');
			else echo '<div id="box-login">
        <div id="box-login-header">
          <h3>Lost Password</h3>

        </div>
        <div id="box-login-content">
          <form method="post" action="lostpassword/">
		    <b>There was a problem while sending mail. Please contact the webmaster.</b>
		 </form>
        </div>
      </div>';
	
	
		}
		else {
			echo'<div id="box-login">

          <h3>Lost Password</h3>

        <div id="box-login-content">
          <form method="post" action="lostpassword/">
		    <b>This email was not found in our database.</b><br /><br />
			<a href="lostpassword/">Go back and try again!</a>
		 </form>
        </div>
      </div>';
		}
	}
	else{
		echo'<div id="box-login">
        <div id="box-login-header">
          <h1>Lost Password</h1>
          <ul>
            <li><a href="login/">Login</a></li>
			<li><a href="register/">Create Account</a></li>
          </ul>
        </div>
        <div id="box-login-content">
          <form method="post" action="lostpassword/">
		    <b>You have not entered any E-mail adress.</b><br /><br />
			<a href="lostpassword/">Go back and try again!</a>
		 </form>
        </div>
      </div>';
	}
}
else {
	echo'<div id="box-login">

          <h3>Lost Password</h3>
        <div id="box-login-content">
          <form method="post" action="lostpassword/">
		    <b>Please enter your e-mail address and then click on the Recover Password button.</b><br /><br />
		    Your new password will be sent to the e-mail adress you indicated.<br />
			Once you have received your new password you can login and change it, if you so desire.<br /><br />
			<input type="text" name="email" size="25" style="margin: 0px 10px 0px 0px;" /> <input type="submit" name="submit" value="Recover Password" />
		 </form>
        </div>
      </div>';
}

?>