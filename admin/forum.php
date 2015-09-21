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
#   Copyright 2005-2009 by webspell.org                                  #
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

$_language->read_module('forum');

if(!ispageadmin($userID) OR mb_substr(basename($_SERVER['REQUEST_URI']),0,15) != "admincenter.php") die($_language->module['access_denied']);

echo'<h1>&curren; '.$_language->module['forum_h1'].'</h1>';

if(isset($_POST['submit'])) {
 	$CAPCLASS = new Captcha;
	if($CAPCLASS->check_captcha(0, $_POST['captcha_hash'])) {
	 $count = mysql_num_rows(safe_query("SELECT * FROM ".PREFIX."forum"));
        if($count > 0) {
		safe_query("UPDATE ".PREFIX."forum SET forum='".$_POST['forum_soft']."', db='".$_POST['forum_db']."', host='".$_POST['forum_host']."', user='".$_POST['forum_user']."', password='".$_POST['forum_password']."', enabled='".$_POST['forum_enabled']."', prefix ='".$_POST['forum_prefix']."'");
		redirect("admincenter.php?site=forum","",0);
		} else {
		safe_query("INSERT INTO ".PREFIX."forum ( forum, db, host, user, password, enabled, prefix ) VALUES ( '".$_POST['forum_soft']."', '".$_POST['forum_db']."', '".$_POST['forum_host']."', '".$_POST['forum_user']."', '".$_POST['forum_password']."', '".$_POST['forum_enabled']."', '".$_POST['forum_prefix']."')");
		redirect("admincenter.php?site=forum","",0);
		}
	} else echo $_language->module['transaction_invalid'];
}

else {
	$ergebnis=safe_query("SELECT * FROM ".PREFIX."forum");
	$ds=mysql_fetch_array($ergebnis);

	$CAPCLASS = new Captcha;
	$CAPCLASS->create_transaction();
	$hash = $CAPCLASS->get_hash();

	$forum = "<option value='0'>".$_language->module['forum_soft1']."</option><option value='1'>".$_language->module['forum_soft2']."</option><option value='2'>".$_language->module['forum_soft3']."</option><option value='3'>".$_language->module['forum_soft4']."</option><option value='4'>".$_language->module['forum_soft5']."</option><option value='5'>".$_language->module['forum_soft6']."</option>";
	$forum = str_replace("value='".$ds['forum']."'","value='".$ds['forum']."' selected='selected'",$forum);
	$enabled = "<option value='0'>".$_language->module['no']."</option><option value='1'>".$_language->module['yes']."</option>";
	$enabled = str_replace("value='".$ds['enabled']."'","value='".$ds['enabled']."' selected='selected'",$enabled);

	echo'<form method="post" action="admincenter.php?site=forum">
  <table width="50%" border="0" cellspacing="1" cellpadding="2">
    <tr><td colspan="2"></td></tr>
   <tr>
      <td align="right"><b>'.$_language->module['forum_soft'].'</b></td>
      <td><select name="forum_soft">'.$forum.'</select></td>
    </tr>
   <tr>
      <td align="right"><b>'.$_language->module['forum_db'].'</b></td>
      <td><input type="text" name="forum_db" value="'.$ds['db'].'" maxlength="30" /></td>
    </tr>
    <tr>
      <td align="right"><b>'.$_language->module['forum_host'].'</b></td>
      <td><input type="text" name="forum_host" value="'.$ds['host'].'" maxlength="30" /></td>
    </tr>
    <tr>
      <td align="right"><b>'.$_language->module['forum_user'].'</b></td>
      <td><input type="text" name="forum_user" value="'.$ds['user'].'" maxlength="30" /></td>
    </tr>
    <tr>
      <td align="right"><b>'.$_language->module['forum_password'].'</b></td>
      <td><input type="password" name="forum_password" value="'.$ds['password'].'" maxlength="30" /></td>
    </tr>
    <tr>
      <td align="right"><b>'.$_language->module['forum_prefix'].'</b></td>
      <td><input type="text" name="forum_prefix" value="'.$ds['prefix'].'" maxlength="30" /></td>
    </tr>
    <tr>
      <td align="right"><b>'.$_language->module['forum_enabled'].'</b></td>
      <td><select name="forum_enabled">'.$enabled.'</select></td>
    </tr>
  </table>
  <br /><br />
  <input type="hidden" name="captcha_hash" value="'.$hash.'" />
  <input type="submit" name="submit" value="'.$_language->module['update'].'" />
  </form>';
}
?>