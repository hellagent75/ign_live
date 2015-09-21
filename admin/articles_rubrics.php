<?php

$_language->read_module('rubrics');

if(!isnewsadmin($userID) OR mb_substr(basename($_SERVER['REQUEST_URI']),0,15) != "admincenter.php") die($_language->module['access_denied']);

if(isset($_POST['save'])) {
 	$CAPCLASS = new Captcha;
	if($CAPCLASS->check_captcha(0, $_POST['captcha_hash'])) {
		$pic = $_FILES['pic'];
		if(checkforempty(Array('name'))) {
			safe_query("INSERT INTO ".PREFIX."articles_rubrics ( rubric ) values( '".$_POST['name']."' ) ");
			$id=mysql_insert_id();
		
			$filepath = "../images/articles-rubrics/";
			
			if($pic['name'] != "") {
				move_uploaded_file($pic['tmp_name'], $filepath.$pic['name'].".tmp");
				@chmod($filepath.$pic['name'].".tmp", 0755);
				$getimg = getimagesize($filepath.$pic['name'].".tmp");
				$rubricpic = '';
				if($getimg[2] == 1) $rubricpic=$id.'.gif';
				elseif($getimg[2] == 2) $rubricpic=$id.'.jpg';
				elseif($getimg[2] == 3) $rubricpic=$id.'.png';
				if($rubricpic != "") {
					if(file_exists($filepath.$id.'.gif')) unlink($filepath.$id.'.gif');
					if(file_exists($filepath.$id.'.jpg')) unlink($filepath.$id.'.jpg');
					if(file_exists($filepath.$id.'.png')) unlink($filepath.$id.'.png');
					rename($filepath.$pic['name'].".tmp", $filepath.$rubricpic);
					safe_query("UPDATE ".PREFIX."articles_rubrics SET pic='".$rubricpic."' WHERE rubricID='".$id."'");
				}  else {
					@unlink($filepath.$pic['name'].".tmp");
					$error = $_language->module['format_incorrect'];
					die('<b>'.$error.'</b><br /><br /><a href="admincenter.php?site=articles_rubrics&amp;action=edit&amp;rubricID='.$id.'">&laquo; '.$_language->module['back'].'</a>');
				}
			}
		} else echo $_language->module['information_incomplete'];
	} else echo $_language->module['transaction_invalid'];
}

elseif(isset($_POST['saveedit'])) {
 	$CAPCLASS = new Captcha;
	if($CAPCLASS->check_captcha(0, $_POST['captcha_hash'])) {
		$pic = $_FILES['pic'];
		if(checkforempty(Array('name'))) {
			safe_query("UPDATE ".PREFIX."articles_rubrics SET rubric='".$_POST['name']."' WHERE rubricID='".$_POST['rubricID']."'");
		
			$id=$_POST['rubricID'];
			$filepath = "../images/articles-rubrics/";
			
			if($pic['name'] != "") {
				move_uploaded_file($pic['tmp_name'], $filepath.$pic['name'].".tmp");
				@chmod($filepath.$pic['name'].".tmp", 0755);
				$getimg = getimagesize($filepath.$pic['name'].".tmp");
				$rubricpic = '';
				if($getimg[2] == 1) $rubricpic=$id.'.gif';
				elseif($getimg[2] == 2) $rubricpic=$id.'.jpg';
				elseif($getimg[2] == 3) $rubricpic=$id.'.png';
				if($rubricpic != "") {
					if(file_exists($filepath.$id.'.gif')) unlink($filepath.$id.'.gif');
					elseif(file_exists($filepath.$id.'.jpg')) unlink($filepath.$id.'.jpg');
					elseif(file_exists($filepath.$id.'.png')) unlink($filepath.$id.'.png');
					rename($filepath.$pic['name'].".tmp", $filepath.$rubricpic);
					safe_query("UPDATE ".PREFIX."articles_rubrics SET pic='".$rubricpic."' WHERE rubricID='".$id."'");
				}  else {
					@unlink($filepath.$pic['name'].".tmp");
					$error = $_language->module['format_incorrect'];
					die('<b>'.$error.'</b><br /><br /><a href="admincenter.php?site=articles_rubrics&amp;action=edit&amp;rubricID='.$id.'">&laquo; '.$_language->module['back'].'</a>');
				}
			}
		} else echo $_language->module['information_incomplete'];
	} else echo $_language->module['transaction_invalid'];
}

elseif(isset($_GET['delete'])) {
 	$CAPCLASS = new Captcha;
	if($CAPCLASS->check_captcha(0, $_GET['captcha_hash'])) {
		$rubricID = $_GET['rubricID'];
		$filepath = "../images/articles-rubrics/";
		safe_query("DELETE FROM ".PREFIX."articles_rubrics WHERE rubricID='$rubricID'");
		if(file_exists($filepath.$rubricID.'.gif')) @unlink($filepath.$rubricID.'.gif');
		if(file_exists($filepath.$rubricID.'.jpg')) @unlink($filepath.$rubricID.'.jpg');
		if(file_exists($filepath.$rubricID.'.png')) @unlink($filepath.$rubricID.'.png');
	} else echo $_language->module['transaction_invalid'];
}

if(isset($_GET['action'])) $action = $_GET['action'];
else $action = '';

if($action=="add") {
	$CAPCLASS = new Captcha;
	$CAPCLASS->create_transaction();
	$hash = $CAPCLASS->get_hash();
  echo'<h1>&curren; <a href="admincenter.php?site=articles_rubrics" class="white">Articles Rubrics</a> &raquo; '.$_language->module['add_rubric'].'</h1>';

	echo'<form method="post" action="admincenter.php?site=articles_rubrics" enctype="multipart/form-data">
  <table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr>
      <td width="15%"><b>'.$_language->module['rubric_name'].'</b></td>
      <td width="85%"><input type="text" name="name" size="60" /></td>
    </tr>
    <tr>
      <td><b>'.$_language->module['picture_upload'].'</b></td>
      <td><input name="pic" type="file" size="40" /></td>
    </tr>
    <tr>
      <td><input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="rubricID" value="'.$ds['rubricID'].'" /></td>
      <td><input type="submit" name="save" value="'.$_language->module['add_rubric'].'" /></td>
    </tr>
  </table>
  </form>';
}

elseif($action=="edit") {
	$CAPCLASS = new Captcha;
	$CAPCLASS->create_transaction();
	$hash = $CAPCLASS->get_hash();
  echo'<h1>&curren; <a href="admincenter.php?site=articles_rubrics" class="white">Articles Rubrics</a> &raquo; '.$_language->module['edit_rubric'].'</h1>';

	$rubricID = $_GET['rubricID'];
	$ergebnis=safe_query("SELECT * FROM ".PREFIX."articles_rubrics WHERE rubricID='$rubricID'");
	$ds=mysql_fetch_array($ergebnis);

	echo'<form method="post" action="admincenter.php?site=articles_rubrics" enctype="multipart/form-data">
  <table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr>
      <td width="15%"><b>'.$_language->module['rubric_name'].'</b></td>
      <td width="85%"><input type="text" name="name" size="60" value="'.getinput($ds['rubric']).'" /></td>
    </tr>
    <tr>
      <td><b>'.$_language->module['picture'].'</b></td>
      <td><img style="width:650px;" src="../images/articles-rubrics/'.$ds['pic'].'" alt="" /></td>
    </tr>
    <tr>
		   <td><b>'.$_language->module['picture_upload'].'</b></td>
       <td><input name="pic" type="file" size="40" /></td>
     </tr>
     <tr>
      <td><input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="rubricID" value="'.$ds['rubricID'].'" /></td>
      <td><input type="submit" name="saveedit" value="'.$_language->module['edit_rubric'].'" /></td>
    </tr>
  </table>
  </form>';
}

else {

  echo'<h1>&curren; Articles Rubrics</h1>';

	echo'<input type="button" onclick="MM_goToURL(\'parent\',\'admincenter.php?site=articles_rubrics&amp;action=add\');return document.MM_returnValue" value="'.$_language->module['new_rubric'].'" /><br /><br />';

	$ergebnis=safe_query("SELECT * FROM ".PREFIX."articles_rubrics ORDER BY rubric");
	
  echo'<table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#DDDDDD">
    <tr>
      <td width="25%" class="title"><b>'.$_language->module['rubric_name'].'</b></td>
      <td width="55%" class="title"><b>'.$_language->module['picture'].'</b></td>
      <td width="20%" class="title"><b>'.$_language->module['actions'].'</b></td>
   		</tr>';
	$CAPCLASS = new Captcha;
	$CAPCLASS->create_transaction();
	$hash = $CAPCLASS->get_hash();
	$i=1;
  while($ds=mysql_fetch_array($ergebnis)) {
    if($i%2) { $td='td1'; }
    else { $td='td2'; }
    
		echo'<tr>
      <td class="'.$td.'">'.getinput($ds['rubric']).'</td>
      <td class="'.$td.'" align="center"><img src="../images/articles-rubrics/'.$ds['pic'].'" width="350" height="150" alt="" /></td>
      <td class="'.$td.'" align="center"><input type="button" onclick="MM_goToURL(\'parent\',\'admincenter.php?site=articles_rubrics&amp;action=edit&amp;rubricID='.$ds['rubricID'].'\');return document.MM_returnValue" value="'.$_language->module['edit'].'" />
      <input type="button" onclick="MM_confirm(\''.$_language->module['really_delete'].'\', \'admincenter.php?site=articles_rubrics&amp;delete=true&amp;rubricID='.$ds['rubricID'].'&amp;captcha_hash='.$hash.'\')" value="'.$_language->module['delete'].'" /></td>
    </tr>';
      
      $i++;
	}
	echo'</table>';
}
?>