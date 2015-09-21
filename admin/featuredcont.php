<?php 

if(file_exists("featuredcont_set.php")) include("featuredcont_set"); else $fc_set_req_small = TRUE;

$_language->read_module("featuredcont");

if(!ispageadmin($userID) OR mb_substr(basename($_SERVER['REQUEST_URI']),0,15) != "admincenter.php") die($_language->module['access_denied']);

echo '<h1>'.$_language->module['fc_title'].'</h1>';

function move_image_home($imgsrc,$pre) {
	if(is_uploaded_file($imgsrc['tmp_name'])) {
		$imgname = $pre.time()."_".$imgsrc["name"];
		if($move = move_uploaded_file($imgsrc['tmp_name'], '../images/featuredcont/'.$imgname)) {
			return $imgname;
		}
		else return false;
	}
	else {
		return false;
	}
}

$action = isset($_GET["action"]) ? $action = $_GET["action"] : $action = "";

if($action == "new") {			
	$fcname = isset($_POST["name"]) ? $_POST["name"] : "";
	$fctease = isset($_POST["tease"]) ? $_POST["tease"] : "";
	$fcurl = isset($_POST["url"]) ? $_POST["url"] : "";
	$fctext = isset($_POST["text"]) ? $_POST["text"] : "";
	$fcfullimg = isset($_FILES["fullimg"]) ? $_FILES["fullimg"] : "";
	$error = array();
	if(isset($_POST['safe'])) {
		if(empty($fcname)) $error[] = $_language->module['fc_add_e_name'];
		if(empty($fcurl)) $error[] = $_language->module['fc_add_e_url'];
		if(!is_uploaded_file($fcfullimg['tmp_name'])) $error[] = $_language->module['fc_add_e_fullimg'];
				
		if(!count($error)) {
			$fullimg_name = move_image_home($fcfullimg,"full_");
			if($fullimg_name != false){
				$sortid = mysql_num_rows(safe_query("SELECT id from ".PREFIX."featuredcont"))+1;
				$query = safe_query("INSERT INTO ".PREFIX."featuredcont VALUES(NULL,'".$fcname."',CURRENT_DATE(),'".$fctease."','".$fcurl."','".$fctext."','".$fullimg_name."','".$fullimg_name."','".$sortid."',1);");
				if($query) {	
					redirect('admincenter.php?site=featuredcont',$_language->module['fc_added'],2);
				}
				else {
				echo $_language->module['fc_added_error'];
				}
			}
		}
	}
	if(count($error)) {
		echo '<div style="text-align:center; font-weight:bold;">'.implode("<br />",$error).'</div>';
	}
	
	echo '
	<form enctype="multipart/form-data" action="admincenter.php?site=featuredcont&action=new" method="post">		
	<table width="100%" border="0" cellspacing="2" cellpadding="2">
    
    <tr>
    <td>*Name:</td>
    <td><input type="text" name="name" value="'.$fcname.'" /></td>
    </tr>
    
	<tr>
    <td>*Link:</td>
    <td><input type="text" name="url" value="'.$fcurl.'" /></td>
    </tr>
	
	<tr>
    <td>*Small Title:</td>
    <td><input type="text" name="tease" value="'.$fctease.'" /></td>
    </tr>
       
	<tr>
    <td valign="top">*Main Title:</td>
	<td><textarea cols="40" rows="1" name="text">'.$fctext.'</textarea></td>
    </tr>
	
	<tr>
	<td>*Slider Image:</br><small>1920x886px</small></td>
	<td><input name="fullimg" type="file" /></td>
	</tr>
	
	<tr><td colspan="2">* = Required Data <br/></td></tr>
	
	</table>
	<input type="hidden" name="safe" value="1" />
	
	<input type="submit" value="'.$_language->module['fc_add_doadd'].'" />&nbsp;<input type="button" OnClick="window.location.href=\'admincenter.php?site=featuredcont\'" value="Back" />
	</form>
	';
}
elseif($action == "del") {
		$delid = (int) $_GET["id"];
		if($delquery = mysql_fetch_array(safe_query("SELECT name, fullimg FROM ".PREFIX."featuredcont WHERE id = ".$delid))) {
			$delfull = (@unlink("../images/featuredcont/".$delquery["fullimg"])) ? '<img src="images/tick.gif" alt="Done" /> Deleted image '.$delquery["fullimg"].' successfully!' : '<img src="images/del.gif"> Error while trying to delete '.$delquery["fullimg"];
			$deldb = safe_query("DELETE FROM ".PREFIX."featuredcont WHERE id =".$delid) ? '<img src="images/tick.gif" alt="Done" /> Deleted database entry successfully!' : '<img src="images/del.gif"> Error while trying to delete database entry!';
			echo '<br />'.$delfull.'<br />'.$deldb.'<br /><a href="admincenter.php?site=featuredcont">Back</a>';
		}
		else {
			echo $_language->module['fc_general_error'];
		}			

}
elseif($action == "sort") {
	$sortlen = count($_POST["sort"]);
	$sortlen2 = count(array_unique($_POST["sort"]));
	if(isset($_POST["sort"])) {
		if($sortlen == $sortlen2){
			foreach($_POST["sort"] as $id => $newsort) {
				safe_query("UPDATE ".PREFIX."featuredcont SET sortid=".$newsort." WHERE id=".$id);
				redirect("admincenter.php?site=featuredcont","",0);
			}
		}
		else {
			echo $_language->module['fc_sort_error'];
		}
	}
	else {
		echo $_language->module['fc_general_error'];
	}
}
elseif($action == "act") {
	if(isset($_GET["id"]) && isset($_GET["do"])){
		$id = (int) $_GET["id"];
			if($_GET["do"] == "a"){
				safe_query("UPDATE ".PREFIX."featuredcont SET activated=1 WHERE id=".$id);
				redirect("admincenter.php?site=featuredcont","",0);
			}
			elseif($_GET["do"] == "d") {
				safe_query("UPDATE ".PREFIX."featuredcont SET activated=0 WHERE id=".$id);
				redirect("admincenter.php?site=featuredcont","",0);
			}
			else {
				redirect("admincenter.php?site=featuredcont","",0);
			}
	}
	else {
		redirect("admincenter.php?site=featuredcont","",0);
	}
}
elseif($action == "edit") {
	$id = $_GET["id"];
	if(isset($_POST["edit"])){		
		$name = $_POST["name"];		
		$tease = $_POST["tease"];		
		$url = $_POST["url"];				
		$text = $_POST["text"];
		$fcfullimg = $_FILES["fullimg"];
		$query = "UPDATE ".PREFIX."featuredcont SET name = '".$name."', tease = '".$tease."', url = '".$url."', text = '".$text."'";
		$delquery = mysql_fetch_array(safe_query("SELECT fullimg FROM ".PREFIX."featuredcont WHERE id = ".$id));
		
		if(!empty($name) && !empty($url)) {
						
			if(($fullimg_name = move_image_home($fcfullimg, "full_")) != false) {
				$query .= ", fullimg = '".$fullimg_name."'";
				$delfull = unlink("../images/featuredcont/".$delquery["fullimg"]);
			}
			
			$query = safe_query($query." WHERE id = ".$id);	
			
			if($query) {
				redirect("admincenter.php?site=featuredcont",$_language->module['fc_edited'],2);
			}
			else {
				echo $_language->module['fc_general_error'];
			}
		}
		else echo $_language->module['fc_general_error'];
	}
	
		$query = safe_query("SELECT * FROM ".PREFIX."featuredcont WHERE id = ".$id);
		if(mysql_num_rows($query) > 0) {
			$result = mysql_fetch_array($query);
			echo '
			<form enctype="multipart/form-data" action="admincenter.php?site=featuredcont&action=edit&id='.$id.'" method="post">		
			<table cellpadding="2" cellspacing="0" border="0">
			<tr><td>'.$_language->module['fc_add_name'].'*:</td><td><input type="text" name="name" value="'.$result['name'].'" /></td></tr>
			<tr><td>'.$_language->module['fc_add_tease'].':</td><td><input type="text" value="'.$result['tease'].'" name="tease" /></td></tr>
			<tr><td>'.$_language->module['fc_add_url'].'*:</td><td><input type="text" value="'.$result['url'].'" name="url" /></td></tr>
			<tr><td valign="top">'.$_language->module['fc_add_text'].':</td><td><textarea cols="45" rows="10" name="text">'.$result['text'].'</textarea></td></tr>
			<tr><td>'.$_language->module['fc_add_fullimg'].'*:</td><td><input name="fullimg" type="file"></td></tr>
			<tr><td valign="top"><small>1920x886px</small></td><td><img src="../images/featuredcont/'.$result['fullimg'].'" alt="Fullsize" style="padding:1px; border:1px solid grey; width:560px;" /></td></tr>
			<tr><td colspan="2">* '.$_language->module['fc_add_required'].'!</td></tr>
			<tr><td>
			<input type="hidden" name="edit" value="1" />
		<input type="submit" value="'.$_language->module['fc_edit_doedit'].'" />&nbsp;<input type="button" OnClick="window.location.href=\'admincenter.php?site=featuredcont\'" value="'.$_language->module['fc_edit_back'].'" />
		</td></tr>
			</table>
			
		
			</form>
			';
		}
		else {
			echo $_language->module['fc_general_error'];
		}
	
}
else {	
	echo '<form name="fc_form" action="admincenter.php?site=featuredcont&action=sort" method="POST">';		
	echo '<table border="0" width="97%" margin:5px;">';
	echo '<tr><td class="title">#</td>
	<td class="title">'.$_language->module['fc_name'].'</td>
	<td class="title">'.$_language->module['fc_url'].'</td>
	<td class="title">'.$_language->module['fc_addedon'].'</td>
	<td class="title">'.$_language->module['fc_sort'].'</td>
	<td class="title">'.$_language->module['fc_activate'].'</td>
	<td class="title">'.$_language->module['fc_edit'].'</td>
	<td class="title" width="30" style="text-align: center;">'.$_language->module['fc_delete'].'</td></tr>';
	
	$num_result = mysql_num_rows($query = safe_query("SELECT id,name,DATE_FORMAT(addedon,'%e.%c.%Y') as date,url,activated FROM ".PREFIX."featuredcont ORDER BY sortid ASC"));
	if ($num_result > 0){	
		$blankID = 1;
		while($result = mysql_fetch_array($query)) {
			echo '<td class="td_head">#'.$blankID.'</td>';
			echo '<td class="td_head">'.$result['name'].'</td>';
			echo '<td class="td_head">'.$result['url'].'</td>';
			echo '<td class="td_head">'.$result['date'].'</td>';
			echo '<td class="td_head"><select name="sort['.$result['id'].']">';
			for($i=1;$i<=$num_result;$i++) {
				if($i==$blankID) {
					echo '<option selected="selected">'.$i.'</option>';
				}
				else {
					echo '<option>'.$i.'</option>';
				}
			}
			echo '</select></td>';
			if($result['activated'] == 1) {
				echo '<td class="td_head"><input type="checkbox" checked="checked" OnClick="window.location.href=\'admincenter.php?site=featuredcont&action=act&id='.$result["id"].'&do=d\'" /></td>';
			}
			else {
				echo '<td class="td_head"><input type="checkbox" OnClick="window.location.href=\'admincenter.php?site=featuredcont&action=act&id='.$result["id"].'&do=a\'" /></td>';	
			}
			echo '<td class="td_head"><a href="admincenter.php?site=featuredcont&action=edit&id='.$result['id'].'">'.$_language->module['fc_edit'].'</a></td>';
			echo '<td class="td_head" style="text-align: center;"><a href="#" OnClick="if(confirm(\'Delete?\')) window.location.href=\'admincenter.php?site=featuredcont&action=del&id='.$result['id'].'\';"><img src="images/del.gif" alt="Delete" /></a></td>';
			echo '</tr>';
			$blankID++;
		}
		echo '</tr>';
	}
	else {
		echo '<tr><td colspan="8">'.$_language->module['fc_empty'].'</td></tr>';
	}
	echo '</table>';
	echo '<input type="submit" value="'.$_language->module['fc_dosort'].'" />&nbsp;<input type="button" OnClick="window.location.href=\'admincenter.php?site=featuredcont&action=new\'" value="'.$_language->module['fc_new'].'" /></form>';
}
?>