<?php
$_language->read_module('vidrubrics');

if(!ispageadmin($userID) OR substr(basename($_SERVER[REQUEST_URI]),0,15) != "admincenter.php") die('<div style="margin: 5px; padding: 5px; background-color: #ac453d; color: #FFFFFF; font-family: Arial; font-size: 12px;">'.$_language->module['no_perm'].'</div>');

if($_POST['save']) {
	safe_query("INSERT INTO ".PREFIX."videos_rubrics ( rubric ) values( '".$_POST['name']."' ) ");
    $id=mysql_insert_id();
	
}
elseif($_POST['saveedit']) {
	safe_query("UPDATE ".PREFIX."videos_rubrics SET rubric='".$_POST['name']."' WHERE rubricID='".$_POST['rubricID']."'");

}
elseif($_GET['delete']) {
  $rubricID = $_GET['rubricID'];
	safe_query("DELETE FROM ".PREFIX."videos_rubrics WHERE rubricID='$rubricID'");
}

echo'<h2>'.$_language->module['head'].'</h2>';
if($_GET['action']=="add") {
    echo'<form method="post" action="admincenter.php?site=vidrubrics" enctype="multipart/form-data">
	     <table cellpadding="4" cellspacing="0">
			 <tr><td>Name:</td><td><input type="text" name="name" class="form_off" onFocus="this.className=\'form_on\'" onBlur="this.className=\'form_off\'"></td></tr>
			 <tr><td>&nbsp;</td><td><input type="submit" name="save" value="'.$_language->module['add'].'"></td></tr>
		 </table>
		 </form>';
}
elseif($_GET['action']=="edit") {
  $rubricID = $_GET['rubricID'];
  $ergebnis=safe_query("SELECT * FROM ".PREFIX."videos_rubrics WHERE rubricID='$rubricID'");
	$ds=mysql_fetch_array($ergebnis);
	
echo'<form method="post" action="admincenter.php?site=vidrubrics" enctype="multipart/form-data">
		<table cellpadding="4" cellspacing="0">
			<tr><td>Name:</td><td><input type="text" name="name" value="'.$ds[rubric].'" class="form_off" onFocus="this.className=\'form_on\'" onBlur="this.className=\'form_off\'"></td></tr>
			<tr><td><input type="hidden" name="rubricID" value="'.$ds[rubricID].'"></td><td><input type="submit" name="saveedit" value="'.$_language->module['edit'].'"></td></tr>
		</table>
		</form>';
}
else {
	echo'<input type="button" class="button" onClick="MM_goToURL(\'parent\',\'admincenter.php?site=vidrubrics&action=add\');return document.MM_returnValue" value="'.$_language->module['new_cat'].'"><br /><br />';

	$ergebnis=safe_query("SELECT * FROM ".PREFIX."videos_rubrics ORDER BY rubric");
	echo'<table width="100%" cellpadding="4" cellspacing="1">
   		<tr ><td class="title" align="center">Name:</td><td class="title" align="center" colspan="2">Admin:</td></tr>
		<tr ><td colspan="4"></td></tr>';
		
	while($ds=mysql_fetch_array($ergebnis)) {
    	echo'<tr><td align="center"><b>'.$ds[rubric].'</b></td><td align="center"><input type="button" class="button" onClick="MM_goToURL(\'parent\',\'admincenter.php?site=vidrubrics&action=edit&rubricID='.$ds[rubricID].'\');return document.MM_returnValue" value="'.$_language->module['edit'].'"></td>
		   		<td align="center"><input type="button" class="button" onClick="MM_confirm(\''.$_language->module['del_check'].'\', \'admincenter.php?site=vidrubrics&delete=true&rubricID='.$ds[rubricID].'\')" value="'.$_language->module['del'].'"></td>
		 	</tr>';
	}
	echo'</table>';
}	
?>	
