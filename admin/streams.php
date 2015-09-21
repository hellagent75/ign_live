<?php

if(!ispageadmin($userID) OR mb_substr(basename($_SERVER['REQUEST_URI']),0,15) != "admincenter.php") die($_language->module['access_denied']);

$filepath = "../images/";

if(isset($_GET['action'])) $action = $_GET['action'];
else $action = '';

if($action=="add") {
	
	echo'<h1>&curren; <a href="admincenter.php?site=streams" class="white">Streams</a> &raquo; Add Stream</h1>';
	
	$CAPCLASS = new Captcha;
	$CAPCLASS->create_transaction();
	$hash = $CAPCLASS->get_hash();
	
	echo'<form method="post" id="post" name="post" action="admincenter.php?site=streams" enctype="multipart/form-data" onsubmit="return chkFormular();">
  <table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr>
      <td width="15%"><b>Type</b></td>
      <td width="85%"><select name="type"><option value="1">Twitch</option><option value="2">Owned</option></select></td>
    </tr>
	<tr>
	  <td width="15%"><b>Main Page</b></td>
	  <td width="85%"><select name="main"><option value="0">No</option><option value="1">Yes</option></select></td>
	</tr>
	<tr>
	  <td width="15%"><b>Event</b></td>
	  <td width="85%"><select name="featured"><option value="0">No</option><option value="1">Yes</option></select></td>
	</tr>
	<tr>
      <td width="15%"><b>Stream Name</b></td>
      <td width="85%"><input type="text" name="title" size="60" maxlength="255" /></td>
    </tr>
    <tr>
      <td><b>Channel</b></td>
      <td><input type="text" name="channel" size="60" maxlength="255" /></td>
    </tr>
	<tr>
      <td><b>Chat</b></td>
      <td><input type="text" name="chat" size="60" maxlength="255" /></td>
    </tr>
	<tr>
      <td><b>Information</b></td>
      <td><input type="text" name="info" size="60" maxlength="255" /></td>
    </tr>
	<tr>
      <td><b>Game</b></td>
      <td><select name="gameID">';
		$games = safe_query("SELECT * FROM ".PREFIX."games ORDER BY name ASC");
		while($game = mysql_fetch_array($games)) {
			$checked = $game['gameID'] == $ds['gameID'] ? 'selected="selected"' : '';
			echo '<option '.$checked.' value="'.$game['gameID'].'">'.$game['name'].'</option>';
		}
	echo '</select>';
	  echo '</td>
    </tr>
	<tr>
      <td><b>Country</b></td>
      <td><select name="countryID">';
		$countries = safe_query("SELECT * FROM ".PREFIX."countries ORDER BY country ASC");
		while($country = mysql_fetch_array($countries)) {
			$checked = $country['countryID'] == $ds['countryID'] ? 'selected="selected"' : '';
			echo '<option '.$checked.' value="'.$country['countryID'].'">'.$country['country'].'</option>';
		}
	echo '</select>';
	  echo '</td>
    </tr>
    <tr>
      <td><b>Display</b></td>
      <td><input type="checkbox" name="displayed" value="1" checked="checked" /></td>
    </tr>
    <tr>
      <td><input type="hidden" name="captcha_hash" value="'.$hash.'" /></td>
      <td><input type="submit" name="save" value="Add Stream" /></td>
    </tr>
  </table>
  </form>';
	
}

elseif($action=="edit") {
	
	$CAPCLASS = new Captcha;
	$CAPCLASS->create_transaction();
	$hash = $CAPCLASS->get_hash();
	
	$streamID = $_GET['streamID'];
	$ergebnis=safe_query("SELECT * FROM ".PREFIX."streams WHERE streamID='$streamID'");
	$ds=mysql_fetch_array($ergebnis);
	
	if($ds['displayed']=='1') $displayed='<input type="checkbox" name="displayed" value="1" checked="checked" />';
	else $displayed='<input type="checkbox" name="displayed" value="1" />';
	
	if($ds['type']=='1') $type='<option value="1">Twitch</option><option value="2">Owned</option>';
	elseif($ds['type']=='2') $type='<option value="2">Owned</option><option value="1">Twitch</option>';
	
	if($ds['main']=='1') $main='<option value="1">Yes</option><option value="0">No</option>';
	elseif($ds['main']=='0') $main='<option value="0">No</option><option value="1">Yes</option>';
	
	if($ds['featured']=='1') $featured='<option value="1">Yes</option><option value="0">No</option>';
	elseif($ds['featured']=='0') $featured='<option value="0">No</option><option value="1">Yes</option>';
	
	echo'<h1>&curren; <a href="admincenter.php?site=streams" class="white">Streams</a> &raquo; Edit  &raquo; '.getinput($ds['title']).'</h1>';
	
	echo'<form method="post" action="admincenter.php?site=streams" enctype="multipart/form-data">
  <table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr>
      <td width="15%"><b>Type</b></td>
      <td width="85%"><select name="type">'.$type.'</select></td>
    </tr>
	<tr>
      <td width="15%"><b>Main Page</b></td>
      <td width="85%"><select name="main">'.$main.'</select></td>
    </tr>
	<tr>
      <td width="15%"><b>Event</b></td>
      <td width="85%"><select name="featured">'.$featured.'</select></td>
    </tr>
	<tr>
      <td width="15%"><b>Stream Name</b></td>
      <td width="85%"><input type="text" name="title" size="60" maxlength="255" value="'.getinput($ds['title']).'" /></td>
    </tr>
    <tr>
      <td><b>Channel</b></td>
      <td><input type="text" name="channel" size="60" maxlength="255" value="'.getinput($ds['channel']).'" /></td>
    </tr>
	<tr>
      <td><b>Chat</b></td>
      <td><input type="text" name="chat" size="60" maxlength="255" value="'.getinput($ds['chat']).'" /></td>
    </tr>
	<tr>
      <td><b>Information</b></td>
      <td><input type="text" name="info" size="60" maxlength="255" value="'.getinput($ds['info']).'" /></td>
    </tr>
	<tr>
      <td><b>Game</b></td>
      <td><select name="gameID">';
		$games = safe_query("SELECT * FROM ".PREFIX."games ORDER BY name ASC");
		while($game = mysql_fetch_array($games)) {
			$checked = $game['gameID'] == $ds['gameID'] ? 'selected="selected"' : '';
			echo '<option '.$checked.' value="'.$game['gameID'].'">'.$game['name'].'</option>';
		}
	echo '</select>';
	  echo '</td>
    </tr>
	<tr>
      <td><b>Country</b></td>
      <td><select name="countryID">';
		$countries = safe_query("SELECT * FROM ".PREFIX."countries ORDER BY country ASC");
		while($country = mysql_fetch_array($countries)) {
			$checked = $country['countryID'] == $ds['countryID'] ? 'selected="selected"' : '';
			echo '<option '.$checked.' value="'.$country['countryID'].'">'.$country['country'].'</option>';
		}
	echo '</select>';
	  echo '</td>
    </tr>
    <tr>
      <td><b>Display</b></td>
      <td>'.$displayed.'</td>
    </tr>
    <tr>
      <td><input type="hidden" name="captcha_hash" value="'.$hash.'" /><input type="hidden" name="streamID" value="'.$streamID.'" /></td>
      <td><input type="submit" name="saveedit" value="Edit Stream" /></td>
    </tr>
  </table>
  </form>';
	
}

elseif(isset($_POST["save"])) {
	
	$title=$_POST["title"];
	$type=$_POST["type"];
	$main=$_POST["main"];
	$featured=$_POST["featured"];
	$channel=$_POST["channel"];
	$chat=$_POST["chat"];
	$gameID=$_POST["gameID"];
	$countryID=$_POST["countryID"];
	$info=$_POST["info"];
	if(isset($_POST["displayed"])) $displayed = $_POST['displayed'];
	else $displayed="";
	
	$CAPCLASS = new Captcha;
	if($CAPCLASS->check_captcha(0, $_POST['captcha_hash'])) {
		
		safe_query("INSERT INTO ".PREFIX."streams (streamID, title, info, type, main, featured, channel, chat, gameID, countryID, displayed) values('', '".$title."', '".$info."', '".$type."', '".$main."', '".$featured."', '".$channel."', '".$chat."', '".$gameID."', '".$countryID."', '".$displayed."')");
		
		redirect("admincenter.php?site=streams","",0);
		
	} else echo 'Error';
	
}

elseif(isset($_POST["saveedit"])) {
	
	$streamID=$_POST["streamID"];
	$title=$_POST["title"];
	$type=$_POST["type"];
	$main=$_POST["main"];
	$featured=$_POST["featured"];
	$channel=$_POST["channel"];
	$chat=$_POST["chat"];
	$gameID=$_POST["gameID"];
	$countryID=$_POST["countryID"];
	$info=$_POST["info"];
	if(isset($_POST["displayed"])) $displayed = $_POST['displayed'];
	else $displayed="";
	
	$CAPCLASS = new Captcha;
	if($CAPCLASS->check_captcha(0, $_POST['captcha_hash'])) {
		
		safe_query("UPDATE ".PREFIX."streams SET title='$title', info='$info', type='$type', main='$main', featured='$featured', channel='$channel', chat='$chat', gameID='$gameID', countryID='$countryID', displayed='".$displayed."' WHERE streamID='$streamID'");
		
		redirect("admincenter.php?site=streams","",0);
		
	} else echo 'Error';
	
}

elseif(isset($_GET["delete"])) {
	
	$CAPCLASS = new Captcha;
	if($CAPCLASS->check_captcha(0, $_GET['captcha_hash'])) {
		safe_query("DELETE FROM ".PREFIX."streams WHERE streamID='".$_GET["streamID"]."'");
		redirect("admincenter.php?site=streams","",0);
	}
	else echo 'Error';
	
}

else {
	
	echo'<h1>&curren; Streams</h1>';
	echo'<input type="button" onclick="MM_goToURL(\'parent\',\'admincenter.php?site=streams&amp;action=add\');return document.MM_returnValue" value="New Stream" /><br /><br />';
	
	echo'<form method="post" action="admincenter.php?site=streams">
  <table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#DDDDDD">
    <tr>
      <td width="10%" class="title"><b>Type</b></td>
      <td width="35%" class="title"><b>Stream Name</b></td>
      <td width="5%" class="title"><b>Game</b></td>
	  <td width="10%" class="title"><b>Country</b></td>
	  <td width="10%" class="title"><b>Displayed</b></td>
	  <td width="10%" class="title"><b>Main</b></td>
      <td width="20%" class="title"><b>Actions</b></td>
    </tr>';
	
	$CAPCLASS = new Captcha;
	$CAPCLASS->create_transaction();
	$hash = $CAPCLASS->get_hash();
	
	$qry=safe_query("SELECT * FROM ".PREFIX."streams ORDER BY streamID");
	$anz=mysql_num_rows($qry);
	if($anz) {
		$i=1;
    while($ds = mysql_fetch_array($qry)) {
		
		if($i%2) { $td='td1'; }
			else { $td='td2'; }
		
		$game = mysql_fetch_array(safe_query("SELECT tag FROM ".PREFIX."games WHERE gameID = '".$ds['gameID']."' LIMIT 0,1"));
		$game = '<img src="../images/games/'.$game['tag'].'.gif" />';
		
		$country = mysql_fetch_array(safe_query("SELECT short FROM ".PREFIX."countries WHERE countryID = '".$ds['countryID']."' LIMIT 0,1"));
		$country = '<img src="../images/flags/'.$country['short'].'.gif" />';
			
		$ds['type']==1 ? $type='<img src="'.$filepath.'twitch.png" alt="" />' : $type='<img src="'.$filepath.'own3d.png" alt="" />';
		$ds['displayed']==1 ? $displayed='<font color="green"><b>Yes</b></font>' : $displayed='<font color="red"><b>No</b></font>';
		$ds['main']==1 ? $main='<font color="green"><b>Yes</b></font>' : $main='<font color="red"><b>No</b></font>';
		
		echo'<tr>
        <td class="'.$td.'" align="center">'.$type.'</td>
        <td class="'.$td.'"><b>'.$ds['title'].'</b></td>
        <td class="'.$td.'" align="center">'.$game.'</td>
		<td class="'.$td.'" align="center">'.$country.'</td>
		<td class="'.$td.'" align="center">'.$displayed.'</td>
		<td class="'.$td.'" align="center">'.$main.'</td>
        <td class="'.$td.'" align="center"><input type="button" onclick="MM_goToURL(\'parent\',\'admincenter.php?site=streams&amp;action=edit&amp;streamID='.$ds['streamID'].'\');return document.MM_returnValue" value="edit" />
        <input type="button" onclick="MM_confirm(\'You sure  you want to delete this stream?\', \'admincenter.php?site=streams&amp;delete=true&amp;streamID='.$ds['streamID'].'&amp;captcha_hash='.$hash.'\')" value="delete" /></td>';
		
		$i++;
		}
	}
  else echo'<tr><td class="td1" colspan="6">'.$_language->module['no_entries'].'</td></tr>';
	
  echo'</table>
  </form>';
	
}

?>