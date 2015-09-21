<?php
/*
	Addon: News Features
	Webspell Version: 4
	Author: Andre Sardo
	Websites: www.andresardo.com | www.unstudios.org
*/

if(!isnewsadmin($userID) OR mb_substr(basename($_SERVER['REQUEST_URI']),0,15) != "admincenter.php") die($_language->module['access_denied']);

if(isset($_POST['save'])) {
 	$CAPCLASS = new Captcha;
	if($CAPCLASS->check_captcha(0, $_POST['captcha_hash'])) {
			safe_query("INSERT INTO ".PREFIX."news_category ( category ) values( '".$_POST['name']."' ) ");
	} else echo $_language->module['transaction_invalid'];
}

elseif(isset($_POST['saveedit'])) {
 	$CAPCLASS = new Captcha;
	if($CAPCLASS->check_captcha(0, $_POST['captcha_hash'])) {
		safe_query("UPDATE ".PREFIX."news_category SET category='".$_POST['name']."' WHERE categoryID='".$_POST['categoryID']."'");
	} else echo $_language->module['transaction_invalid'];
}

elseif(isset($_GET['delete'])) {
 	$CAPCLASS = new Captcha;
	if($CAPCLASS->check_captcha(0, $_GET['captcha_hash'])) {
		$categoryID = $_GET['categoryID'];
		safe_query("DELETE FROM ".PREFIX."news_category WHERE categoryID='$categoryID'");
	} else echo $_language->module['transaction_invalid'];
}

if(isset($_GET['action'])) $action = $_GET['action'];
else $action = '';

if($action=="add") {
	$CAPCLASS = new Captcha;
	$CAPCLASS->create_transaction();
	$hash = $CAPCLASS->get_hash();
  echo'<h1>&curren; <a href="admincenter.php?site=news_category" class="white">News Category</a> &raquo; Add Category</h1>';

	echo'<form method="post" action="admincenter.php?site=news_category" enctype="multipart/form-data">
  <table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr>
      <td width="15%"><b>Category Name</b></td>
      <td width="85%"><input type="text" name="name" size="30" /></td>
    </tr>
    <tr>
      <td><input type="hidden" name="captcha_hash" value="'.$hash.'" /></td>
      <td><input type="submit" name="save" value="Add Category" /></td>
    </tr>
  </table>
  </form>';
}

elseif($action=="edit") {
	$CAPCLASS = new Captcha;
	$CAPCLASS->create_transaction();
	$hash = $CAPCLASS->get_hash();
  echo'<h1>&curren; <a href="admincenter.php?site=news_category" class="white">News Category</a> &raquo; Edit Category</h1>';

	$categoryID = $_GET['categoryID'];
	$ergebnis=safe_query("SELECT * FROM ".PREFIX."news_category WHERE categoryID='$categoryID'");
	$ds=mysql_fetch_array($ergebnis);

	echo'<form method="post" action="admincenter.php?site=news_category" enctype="multipart/form-data">
  <table width="100%" border="0" cellspacing="1" cellpadding="3">
  	<tr>
      <td width="15%"><b>CategoryID</b></td>
      <td width="85%"><input type="text" name="name" size="10" value="'.$ds['categoryID'].'" DISABLED /></td>
    </tr>
    <tr>
      <td width="15%"><b>Category Name</b></td>
      <td width="85%"><input type="text" name="name" size="30" value="'.getinput($ds['category']).'" /></td>
    </tr>
     <tr>
      <td><input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="categoryID" value="'.$ds['categoryID'].'" /></td>
      <td><input type="submit" name="saveedit" value="Edit Category" /></td>
    </tr>
  </table>
  </form>';
}

else {

  echo'<h1>&curren; News Category</h1>';

	echo'<input type="button" onclick="MM_goToURL(\'parent\',\'admincenter.php?site=news_category&amp;action=add\');return document.MM_returnValue" value="New Category" /><br /><br />';

	$ergebnis=safe_query("SELECT * FROM ".PREFIX."news_category ORDER BY category");
	
  echo'<table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#DDDDDD">
    <tr>
	  <td width="10%" class="title"><b>CategoryID</b></td>
      <td width="40%" class="title"><b>Category Name</b></td>
      <td width="20%" class="title"><b>Options</b></td>
   		</tr>';
	$CAPCLASS = new Captcha;
	$CAPCLASS->create_transaction();
	$hash = $CAPCLASS->get_hash();
	$i=1;
  while($ds=mysql_fetch_array($ergebnis)) {
    if($i%2) { $td='td1'; }
    else { $td='td2'; }
    
		echo'<tr>
	  <td class="'.$td.'" align="center">'.$ds['categoryID'].'</td>
      <td class="'.$td.'">'.getinput($ds['category']).'</td>
      <td class="'.$td.'" align="center"><input type="button" onclick="MM_goToURL(\'parent\',\'admincenter.php?site=news_category&amp;action=edit&amp;categoryID='.$ds['categoryID'].'\');return document.MM_returnValue" value="Edit" />
      <input type="button" onclick="MM_confirm(\'Are you sure you wanna delete?\', \'admincenter.php?site=news_category&amp;delete=true&amp;categoryID='.$ds['categoryID'].'&amp;captcha_hash='.$hash.'\')" value="Delete" /></td>
    </tr>';
      
      $i++;
	}
	echo'</table>';
}
?>