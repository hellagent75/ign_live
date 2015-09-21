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
#   Copyright 2005-2010 by webspell.org                                  #
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

$_language->read_module('forumico');

if(!ispageadmin($userID) OR mb_substr(basename($_SERVER['REQUEST_URI']),0,15) != "admincenter.php") die($_language->module['access_denied']);

$filepath = "../images/forumico/";

if(isset($_GET['action'])) $action = $_GET['action'];
else $action = '';

if($action=="add") {
	
	$CAPCLASS = new Captcha;
	$CAPCLASS->create_transaction();
	$hash = $CAPCLASS->get_hash();
  echo'<h1>&curren; <a href="admincenter.php" class="white">'.$_language->module['overview'].'</a> &raquo; <a href="admincenter.php?site=forumico" class="white">'.$_language->module['forumicons'].'</a> &raquo; '.$_language->module['add_ico'].'</h1>';
	
	echo'<form method="post" action="admincenter.php?site=forumico" enctype="multipart/form-data">
  <table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr>
      <td width="15%"><b>'.$_language->module['search_icon'].'</b></td>
      <td width="85%"><input name="icon" type="file" size="40" /></td>
    </tr>
    <tr>
      <td><b>'.$_language->module['ico_name'].'</b></td>
      <td><input type="text" name="name" size="60" maxlength="255" /></td>
    </tr>
    <tr>
      <td><b>'.$_language->module['ico_tag'].'</b></td>
      <td><input type="text" name="tag" size="5" maxlength="5" /></td>
    </tr>
    <tr>
      <td><input type="hidden" name="captcha_hash" value="'.$hash.'" /></td>
      <td><input type="submit" name="save" value="'.$_language->module['add_ico'].'" /></td>
    </tr>
  </table>
  </form>';
}

elseif($action=="edit") {
	$ds=mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."forum_ico WHERE icoID='".$_GET["icoID"]."'"));
	$pic='<img src="../images/forumico/'.$ds['tag'].'.jpg" border="0" alt="'.$ds['name'].'" />';
  
  $CAPCLASS = new Captcha;
	$CAPCLASS->create_transaction();
	$hash = $CAPCLASS->get_hash();
	
  echo'<h1>&curren; <a href="admincenter.php" class="white">'.$_language->module['overview'].'</a> &raquo; <a href="admincenter.php?site=forumico" class="white">'.$_language->module['forumicons'].'</a> &raquo; '.$_language->module['edit_ico'].'</h1>';

	echo'<form method="post" action="admincenter.php?site=forumico" enctype="multipart/form-data">
  <input type="hidden" name="icoID" value="'.$ds['icoID'].'" />
  <table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr>
      <td width="15%"><b>'.$_language->module['present_icon'].'</b></td>
      <td width="45%">'.$pic.'</td>
    </tr>
    <tr>
      <td><b>'.$_language->module['search_icon'].'</b></td>
      <td><input name="icon" type="file" size="40" /></td>
    </tr>
    <tr>
      <td><b>'.$_language->module['ico_name'].'</b></td>
      <td><input type="text" name="name" size="60" maxlength="255" value="'.getinput($ds['name']).'" /></td>
    </tr>
    <tr>
      <td><b>'.$_language->module['ico_tag'].'</b></td>
      <td><input type="text" name="tag" size="5" maxlength="5" value="'.getinput($ds['tag']).'" readonly="readonly" /></td>
    </tr>
    <tr>
      <td><input type="hidden" name="captcha_hash" value="'.$hash.'" /></td>
      <td><input type="submit" name="saveedit" value="'.$_language->module['edit_ico'].'" /></td>
    </tr>
  </table>
  </form>';
}

elseif(isset($_POST['save'])) {
	$icon=$_FILES["icon"];
	$name=$_POST["name"];
	$tag=$_POST["tag"];
	$CAPCLASS = new Captcha;
	if($CAPCLASS->check_captcha(0, $_POST['captcha_hash'])) {	
		if($name AND $tag) {
			$file_ext=strtolower(mb_substr($icon['name'], strrpos($icon['name'], ".")));
			if($file_ext==".jpg") {
				safe_query("INSERT INTO ".PREFIX."forum_ico (icoID, name, tag) values('', '".$name."', '".$tag."')");
				if($icon['name'] != "") {
					move_uploaded_file($icon['tmp_name'], $filepath.$icon['name']);
					@chmod($filepath.$icon['name'], 0755);
					$file=$tag.$file_ext;
					rename($filepath.$icon['name'], $filepath.$file);
	        redirect("admincenter.php?site=forumico","",0);
				}
			} else echo'<b>'.$_language->module['format_incorrect'].'</b><br /><br /><a href="javascript:history.back()">&laquo; '.$_language->module['back'].'</a>';
		} else echo'<b>'.$_language->module['fill_correctly'].'</b><br /><br /><a href="javascript:history.back()">&laquo; '.$_language->module['back'].'</a>';
	} else echo $_language->module['transaction_invalid'];	
}

elseif(isset($_POST["saveedit"])) {
	$icon=$_FILES["icon"];
	$name=$_POST["name"];
	$tag=$_POST["tag"];
	$CAPCLASS = new Captcha;
	if($CAPCLASS->check_captcha(0, $_POST['captcha_hash'])) {
		if($name AND $tag) {
			if($icon['name']=="") {
				if(safe_query("UPDATE ".PREFIX."forum_ico SET name='".$name."', tag='".$tag."' WHERE icoID='".$_POST["icoID"]."'"))
	      redirect("admincenter.php?site=forumico","",0);
	
			} else {
				$file_ext=strtolower(mb_substr($icon['name'], strrpos($icon['name'], ".")));
				if($file_ext==".jpg") {
					move_uploaded_file($icon['tmp_name'], $filepath.$icon['name']);
					@chmod($filepath.$icon['name'], 0755);
					$file=$tag.$file_ext;
					rename($filepath.$icon['name'], $filepath.$file);
	
					if(safe_query("UPDATE ".PREFIX."forum_ico SET name='".$name."', tag='".$tag."' WHERE icoID='".$_POST["icoID"]."'")) {
						
	          redirect("admincenter.php?site=forumico","",0);
					}
				} else echo'<b>'.$_language->module['format_incorrect'].'</b><br /><br /><a href="javascript:history.back()">&laquo; '.$_language->module['back'].'</a>';
			}
		} else echo'<b>'.$_language->module['fill_correctly'].'</b><br /><br /><a href="javascript:history.back()">&laquo; '.$_language->module['back'].'</a>';
	} else echo $_language->module['transaction_invalid'];
}

elseif(isset($_GET["delete"])) {
 	$CAPCLASS = new Captcha;
	if($CAPCLASS->check_captcha(0, $_GET['captcha_hash'])) {
		safe_query("DELETE FROM ".PREFIX."forum_ico WHERE icoID='".$_GET["icoID"]."'");
		redirect("admincenter.php?site=forumico","",0);
	} else echo $_language->module['transaction_invalid'];
}

else {
	
  echo'<h1>&curren; <a href="admincenter.php" class="white">'.$_language->module['overview'].'</a> &raquo; '.$_language->module['forumicons'].'</h1>';
  
  echo'<input type="button" onclick="MM_goToURL(\'parent\',\'admincenter.php?site=forumico&amp;action=add\');return document.MM_returnValue" value="'.$_language->module['new_ico'].'" /><br /><br />';
  
  echo'<form method="post" action="admincenter.php?site=forumico">
  <table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#DDDDDD">
    <tr>
      <td width="15" class="title" align="center"><b>'.$_language->module['icons'].'</b></td>
      <td width="45%" class="title"><b>'.$_language->module['ico_name'].'</b></td>
      <td width="15%" class="title" align="center"><b>'.$_language->module['ico_tag'].'</b></td>
      <td width="25%" class="title" align="center"><b>'.$_language->module['actions'].'</b></td>
    </tr>';
  
	$ergebnis=safe_query("SELECT * FROM ".PREFIX."forum_ico ORDER BY name");
	$anz=mysql_num_rows($ergebnis);
	if($anz) {
		
    $i=1;
    $CAPCLASS = new Captcha;
    $CAPCLASS->create_transaction();
    $hash = $CAPCLASS->get_hash();
    
    while($ds = mysql_fetch_array($ergebnis)) {
      if($i%2) { $td='td1'; }
      else { $td='td2'; }
      $pic='<img style=" height:35px;" src="../images/forumico/'.$ds['tag'].'.jpg" border="0" alt="" />';
      			
      echo'<tr>
        <td class="'.$td.'" align="center">'.$pic.'</td>
        <td class="'.$td.'">'.getinput($ds['name']).'</td>
        <td class="'.$td.'" align="center">'.getinput($ds['tag']).'</td>
        <td class="'.$td.'" align="center"><input type="button" onclick="MM_goToURL(\'parent\',\'admincenter.php?site=forumico&amp;action=edit&amp;icoID='.$ds['icoID'].'\');return document.MM_returnValue" value="'.$_language->module['edit'].'" />
        <input type="button" onclick="MM_confirm(\''.$_language->module['really_delete'].'\', \'admincenter.php?site=forumico&amp;delete=true&amp;icoID='.$ds['icoID'].'&amp;captcha_hash='.$hash.'\')" value="'.$_language->module['delete'].'" /></td>
      </tr>';
      
      $i++;
		}
	}
  else echo'<tr><td class="td1" colspan="5">'.$_language->module['no_entries'].'</td></tr>';
	
  echo '</table>
  </form>';
}
?>