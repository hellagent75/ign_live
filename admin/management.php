<?php

if(!ispageadmin($userID) OR mb_substr(basename($_SERVER['REQUEST_URI']),0,15) != "admincenter.php") die($_language->module['access_denied']);

if(isset($_GET['action'])) $action = $_GET['action'];
else $action = '';

if($action=="add") {
	
	echo'<h1>&curren; <a href="admincenter.php?site=management" class="white">Management</a> &raquo; Add User</h1>';
	
	$CAPCLASS = new Captcha;
	$CAPCLASS->create_transaction();
	$hash = $CAPCLASS->get_hash();
	
	echo'<form method="post" id="post" name="post" action="admincenter.php?site=management" enctype="multipart/form-data" onsubmit="return chkFormular();">
  <table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr>
      <td width="15%"><b>Name</b></td>
      <td width="85%"><input type="text" name="name" size="60" maxlength="255" /></td>
    </tr>
	<tr>
	  <td width="15%"><b>Category</b></td>
	  <td width="85%"><select name="category"><option value="1">Management</option><option value="2">Staff</option></select></td>
	</tr>
	<tr>
	  <td width="15%"><b>Email</b></td>
	  <td width="85%"><input type="text" name="email" size="60" maxlength="255" /></td>
	</tr>
	<tr>
      <td width="15%"><b>Position</b></td>
      <td width="85%"><input type="text" name="position" size="60" maxlength="255" /></td>
    </tr>
    <tr>
      <td><b>Display</b></td>
      <td><input type="checkbox" name="displayed" value="1" checked="checked" /></td>
    </tr>
    <tr>
      <td><input type="hidden" name="captcha_hash" value="'.$hash.'" /></td>
      <td><input type="submit" name="save" value="Add User" /></td>
    </tr>
  </table>
  </form>';
	
}

elseif($action=="edit") {
	
	$CAPCLASS = new Captcha;
	$CAPCLASS->create_transaction();
	$hash = $CAPCLASS->get_hash();
	
	$userID = $_GET['userID'];
	$ergebnis=safe_query("SELECT * FROM ".PREFIX."management WHERE userID='$userID'");
	$ds=mysql_fetch_array($ergebnis);
	
	if($ds['displayed']=='1') $displayed='<input type="checkbox" name="displayed" value="1" checked="checked" />';
	else $displayed='<input type="checkbox" name="displayed" value="1" />';
	
	if($ds['category']=='1') $category='<option value="1">Management</option><option value="2">Staff</option>';
	elseif($ds['category']=='2') $category='<option value="2">Staff</option><option value="1">Management</option>';
	
	echo'<h1>&curren; <a href="admincenter.php?site=management" class="white">Management</a> &raquo; Edit  &raquo; '.getinput($ds['name']).'</h1>';
	
	echo'<form method="post" action="admincenter.php?site=management" enctype="multipart/form-data">
  <table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr>
      <td width="15%"><b>Name</b></td>
      <td width="85%"><input type="text" name="name" size="60" maxlength="255" value="'.getinput($ds['name']).'" /></td>
    </tr>
	<tr>
      <td width="15%"><b>Category</b></td>
      <td width="85%"><select name="category">'.$category.'</select></td>
    </tr>
	<tr>
      <td width="15%"><b>Email</b></td>
      <td width="85%"><input type="text" name="email" size="60" maxlength="255" value="'.getinput($ds['email']).'" /></td>
    </tr>
	<tr>
      <td width="15%"><b>Position</b></td>
      <td width="85%"><input type="text" name="position" size="60" maxlength="255" value="'.getinput($ds['position']).'" /></td>
    </tr>
    <tr>
      <td><b>Display</b></td>
      <td>'.$displayed.'</td>
    </tr>
    <tr>
      <td><input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="userID" value="'.$userID.'" /></td>
      <td><input type="submit" name="saveedit" value="Edit User" /></td>
    </tr>
  </table>
  </form>';
	
}

elseif(isset($_POST["save"])) {
	
	$name=$_POST["name"];
	$email=$_POST["email"];
	$position=$_POST["position"];
	$category=$_POST["category"];
	if(isset($_POST["displayed"])) $displayed = $_POST['displayed'];
	else $displayed="";
	
	$CAPCLASS = new Captcha;
	if($CAPCLASS->check_captcha(0, $_POST['captcha_hash'])) {
		
		safe_query("INSERT INTO ".PREFIX."management (userID, name, email, position, category, sort, displayed) values('', '".$name."', '".$email."', '".$position."', '".$category."', '1', '".$displayed."')");
		
		redirect("admincenter.php?site=management","",0);
		
	} else echo 'Error';
	
}

elseif(isset($_POST["saveedit"])) {
	
	$userID=$_POST["userID"];
	$name=$_POST["name"];
	$email=$_POST["email"];
	$position=$_POST["position"];
	$category=$_POST["category"];
	$displayed=$_POST["displayed"];
	
	$CAPCLASS = new Captcha;
	if($CAPCLASS->check_captcha(0, $_POST['captcha_hash'])) {
		
		safe_query("UPDATE ".PREFIX."management SET name='$name', email='$email', position='$position', category='$category', displayed='$displayed' WHERE userID='$userID'");
		
		redirect("admincenter.php?site=management","",0);
		
	} else echo 'Error';
	
}

elseif(isset($_GET["delete"])) {
	
	$CAPCLASS = new Captcha;
	if($CAPCLASS->check_captcha(0, $_GET['captcha_hash'])) {
		safe_query("DELETE FROM ".PREFIX."management WHERE userID='".$_GET["userID"]."'");
		redirect("admincenter.php?site=management","",0);
	}
	else echo 'Error';
	
}

if(isset($_POST['sortieren'])) {
 	$CAPCLASS = new Captcha;
	if($CAPCLASS->check_captcha(0, $_POST['captcha_hash'])) {
		$sort = $_POST['sort'];
		if(is_array($sort)) {
			foreach($sort as $sortstring) {
				$sorter=explode("-", $sortstring);
				safe_query("UPDATE ".PREFIX."management SET sort='$sorter[1]' WHERE userID='$sorter[0]' ");
				redirect("admincenter.php?site=management","",0);
			}
		}
	} else echo $_language->module['transaction_invalid'];
}

else {
	
	echo'<h1>&curren; Management</h1>';
	echo'<input type="button" onclick="MM_goToURL(\'parent\',\'admincenter.php?site=management&amp;action=add\');return document.MM_returnValue" value="Add User" /><br /><br />';
	
	echo'<form method="post" action="admincenter.php?site=management">
  <table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#DDDDDD">
    <tr>
      <td width="30%" class="title"><b>Name</b></td>
      <td width="45%" class="title"><b>Position</b></td>
      <td width="20%" class="title"><b>Actions</b></td>
	  <td width="5%" class="title"><b>Sort</b></td>
    </tr>';
	
	$CAPCLASS = new Captcha;
	$CAPCLASS->create_transaction();
	$hash = $CAPCLASS->get_hash();
	
	$qry=safe_query("SELECT * FROM ".PREFIX."management ORDER BY sort");
	$anz=mysql_num_rows($qry);
	if($anz) {
		$i=1;
    while($ds = mysql_fetch_array($qry)) {
		
		if($i%2) { $td='td1'; }
			else { $td='td2'; }
		
		echo'<tr>
        <td class="'.$td.'"><b>'.$ds['name'].'</b></td>
        <td class="'.$td.'">'.$ds['position'].'</td>
        <td class="'.$td.'" align="center"><input type="button" onclick="MM_goToURL(\'parent\',\'admincenter.php?site=management&amp;action=edit&amp;userID='.$ds['userID'].'\');return document.MM_returnValue" value="edit" />
        <input type="button" onclick="MM_confirm(\'You sure  you want to delete this User?\', \'admincenter.php?site=management&amp;delete=true&amp;userID='.$ds['userID'].'&amp;captcha_hash='.$hash.'\')" value="delete" /></td><td class="'.$td.'" align="center"><select name="sort[]">';
        
			for($j=1; $j<=$anz; $j++) {
				if($ds['sort'] == $j) echo'<option value="'.$ds['userID'].'-'.$j.'" selected="selected">'.$j.'</option>';
				
        else echo'<option value="'.$ds['userID'].'-'.$j.'">'.$j.'</option>';
			}
			echo'</select>
        </td>
      </tr>';
		
		$i++;
		}
	}
  else echo'<tr><td class="td1" colspan="6">No entries</td></tr>';
	
  echo'<tr>
      <td class="td_head" colspan="5" align="right"><input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="submit" name="sortieren" value="Sort" /></td>
    </tr>
	</table>
  </form>';
	
}

?>