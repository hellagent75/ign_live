<?php

/********************** BUTTON FÜRS HINZUFÜGEN NEUER VIDEOS **********************/
$_language->read_module('videos');

	
/********************** CONFIG **********************/
$max='20';			 /* Anzahl der Videos pro Seite */
$sortierung = vidID;  /* Art der Sortierung ( Angegeben können werden: hits und vidID */
/****************************************************/

/********************** KATEGORIEN AUSGABE **********************/

$videosrubrics=safe_query("SELECT rubricID, rubric FROM ".PREFIX."videos_rubrics ORDER BY rubric");
while($dr=mysql_fetch_array($videosrubrics)) {
$rubrics.='<option value="'.$dr[rubricID].'">'.$dr[rubric].'</option>';
}

/********************** ACTION VARIABLEN **********************/	

if(!isset($_GET['action'])) {
	$page = $_GET['page'];
	$sort = $_GET['sort'];
	$type = $_GET['type'];
	$rubric = $_POST['rubric'];
	$alle=safe_query("SELECT vidID FROM ".PREFIX."videos");
	$gesamt = mysql_num_rows($alle);
	$pages=1;
	if(!isset($page)) $page = 1;
	if(!isset($sort)) $sort = "$sortierung";
	if(!isset($type)) $type = "DESC";		
    for ($n=$max; $n<=$gesamt; $n+=$max) {
	    if($gesamt>$n) $pages++;
	}

/********************** SEITENANZAHL GENERIEREN BEI MEHR ALS EINER SEITE + SORTIERUNG **********************/

	if($pages>1) $page_link = makepagelink("index.php?site=videos&sort=$sort&type=$type", $page, $pages);
	if ($page == "1") {
        $ergebnis = safe_query("SELECT * FROM ".PREFIX."videos ORDER BY $sort $type LIMIT 0,$max");
	  if(isset($rubric)) $ergebnis = safe_query("SELECT * FROM ".PREFIX."videos WHERE rubric=".$rubric." ORDER BY $sort $type LIMIT 0,$max");
	    if($type=="DESC") $n=$gesamt;
		else $n=1;
	}
	else {
	    $start=$page*$max-$max;
	    $ergebnis = safe_query("SELECT * FROM ".PREFIX."videos ORDER BY $sort $type LIMIT $start,$max");
	    if(isset($rubric)) $ergebnis = safe_query("SELECT * FROM ".PREFIX."videos WHERE rubric=".$rubric." ORDER BY $sort $type LIMIT $start,$max");
	    if($type=="DESC") $n = ($gesamt)-$page*$max+$max;
		else $n = ($gesamt+1)-$page*$max+$max;
	}
	
	if($gesamt) {
	

/********************** TITLE VOM VIDEOS-BEREICH **********************/							
	$cat = $_language->module['category'];						
	eval ("\$head = \"".gettemplate("videos_head")."\";");
	echo $head;
/********************** HAFTUNGSAUSSCHLUSS **********************/		
	
/********************** BEI MEHR ALS EINER SEITE AUSGABE DER SEITENZAHLEN **********************/		

if($pages>1) echo $page_link;
	$i=1;
	while($ar=mysql_fetch_array($ergebnis)) {
				if($i%2) {
				$bg1=BG_1;
				$bg2=BG_2;
			}
			else {
				$bg1=BG_3;
				$bg2=BG_4;
			}
			$com=getanzcomments($ar[vidID],"mo");
			
/********************** INHALT VOM VIDEOS-BEREICH **********************/		

$cat = $_language->module['category'];	
$len = $_language->module['length'];	
$comm = $_language->module['comments'];	
$vis = $_language->module['visit'];	
$cli=$_language->module['clicks'];
$mins=$_language->module['mins'];
$prevt=$_language->module['preview'];
	eval ("\$content = \"".gettemplate("videos_liste")."\";");
	echo '<div class="video_inner">';
	echo $content;
	echo '</div>';
	
	
$i++;
}
	}
	else{
	echo''.$_language->module['no_entrys'].'';
	}
if(isnewsadmin($userID)) echo'<input type="button" style="width:670px; margin:10px 0 0 0px;" onClick="MM_goToURL(\'parent\',\'index.php?site=videos&action=add\');return document.MM_returnValue" value="Add Video">';	
/********************** BEI MEHR ALS EINER SEITE AUSGABE DER SEITENZAHLEN **********************/	

	echo $page_link;
	
/********************** ACTION DETAIL **********************/	

} elseif($_GET['action'] == "detail") {
	$id=$_GET['id'];
	if(isset($id)){
	$res=safe_query("SELECT * FROM ".PREFIX."videos WHERE vidID=$id");
	if(mysql_num_rows($res)){
	$ar=mysql_fetch_array($res);
	safe_query("UPDATE ".PREFIX."videos SET hits=hits+1 WHERE vidID=$id"); 
	$bg1=BG_1;
	$bg2=BG_2;

/********************** WENN DER BENUTZER FILE-ADMIN RECHTE HAT, AUSGABE DER BUTTONS FÜR DIE ADMINISTRIERUNG **********************/	

if(isfileadmin($userID)) 
	$adm='<input type="button" onClick="MM_goToURL(\'parent\',\'index.php?site=videos&action=edit&id='.$ar[vidID].'\');return document.MM_returnValue" value="Edit">
	<input type="button" onClick="MM_confirm(\''.$_language->module['conf_delete'].'\', \'index.php?site=videos&action=delete&id='.$ar[vidID].'\')" value="Delete">';

/********************** VIDEOBERSCHREIBUNG AUSGABE **********************/	

if($ar[viddescription]) $des=$ar[viddescription];
else $des=''.$_language->module['no_desc'].'';
	$cat = $_language->module['category'];	
	$len = $_language->module['length'];	
	$comm = $_language->module['comments'];	
	$bac = $_language->module['back'];	
	$cli=$_language->module['clicks'];
	$mins=$_language->module['mins'];
	$dess=$_language->module['description'];
	eval ("\$details = \"".gettemplate("videos_watch")."\";");
	echo $details;
		$parentID = $id;
		$type = "mo";
		$referer = "index.php?site=videos&amp;action=detail&amp;id=$id";
		$comments_allowed = 2;
		include("comments.php");
		
}

/********************** FEHLERMELDUNG BEI FALSCHER ID EINGABE **********************/	

else echo'<div ID="error">'.$_language->module['no_id'].' '.$id.' '.$_language->module['avail'].'</div>';
}
else echo''.$_language->module['err_entry'].'!';

}

/********************** ACTION ADD **********************/	

elseif($_GET['action'] == "add") {
if(!isnewsadmin($userID)) die('<div ID="error">'.$_language->module['no_perm'].'</div>');
$bg1=BG_1;
$bg2=BG_2;
$head = $_language->module['add_vid'];
$leng = $_language->module['length'];
$cat = $_language->module['category'];
$desc = $_language->module['description'];

	eval ("\$add = \"".gettemplate("videos_add")."\";");
	echo $add;
} 

/********************** ACTION EDIT **********************/	

elseif($_GET['action'] == "edit") {
if(!isnewsadmin($userID)) die('<div ID="error">'.$_language->module['no_perm'].'</div>');
$bg1=BG_1;
$bg2=BG_2;
$id=$_GET['id'];
$get=safe_query("SELECT * FROM ".PREFIX."videos WHERE vidID=$id");
if(mysql_num_rows($get)){
$ds=mysql_fetch_array($get);
/// TEMPLATE EDIT ///

echo'<div style="width:650px; float:left;">
<h2>Edit video</h2>
<form action="index.php?site=videos&action=saveedit" method="post" name="saveedit" enctype="multipart/form-data">
<table width="650" cellspacing="1" cellpadding="2" align="left">
	<tr><td>Title:</td><td><input type="text" name="name" value="'.$ds[vidheadline].'"></td></tr>
    <tr><td>Category:</td><td><select name="rubric">';
	$rubrics=safe_query("SELECT * FROM ".PREFIX."videos_rubrics ORDER BY rubric");
	while($dr=mysql_fetch_array($rubrics))
	{
		if($ds[rubric]==$dr[rubricID]){
		echo'<option value="'.$dr[rubricID].'" selected>'.$dr[rubric].'</option>';
		}else{
		echo'<option value="'.$dr[rubricID].'">'.$dr[rubric].'</option>';
		}
	}
	echo'</select></td></tr>
    <tr><td>Description:</td><td ><textarea name="description" cols="60" rows="7">'.$ds[viddescription].'</textarea></td></tr>
    <tr><td>Youtube-ID:</td><td><input type="text" name="clip" value="'.$ds[vidclip].'" size="62"></td></tr>
    <tr><td valign="top">Preview:</td><td><img src="http://img.youtube.com/vi/'.$ds[vidclip].'/default.jpg" style="margin: 5px;"></td></tr>
    <tr><td><input type="submit" value="save" name="saveedit"></td><td><input type="hidden" value="'.$id.'" name="id"</td></tr>
</table>
</form></div>';


//// ENDE TEMPLATE EDIT ////
}

/********************** FEHLERMELDUNG BEI FALSCHER ID **********************/	

else echo'<div ID="error">'.$_language->module['no_id'].' '.$id.' '.$_language->module['avail'].'</div>';
}

/********************** ACTION SAVE **********************/	

elseif($_GET['action'] == "save") {
if(!isnewsadmin($userID)) die('<div ID="error">'.$_language->module['no_perm'].'</div>');
$name=$_POST['name'];
$rubric = $_POST['rubric'];
$length=$_POST['length'];
$source=$_POST['source'];
$desc=nl2br($_POST['description']);
$clip=$_POST['clip'];
$safe=safe_query("INSERT INTO ".PREFIX."videos (rubric, vidheadline, vidlength, vidsource, viddescription, vidclip) VALUES ('".$rubric."', '".$name."', '".$length."', '".$source."', '".$desc."', '".$clip."')");
if($safe)echo'<div ID="successful">'.$_language->module['suc_entry'].'</div><meta http-equiv="refresh" content="2; URL=index.php?site=videos">';
else echo'<div ID="error">'.$_language->module['err_entry'].'</div>';
} 

/********************** ACTION SAVE EDIT **********************/

elseif($_GET['action'] == "saveedit"){
if(!isnewsadmin($userID)) die('<div ID="error">'.$_language->module['no_perm'].'</div>');
$name=$_POST['name'];
$rubric = $_POST['rubric'];
$length=$_POST['length'];
$source=$_POST['source'];
$desc=nl2br($_POST['description']);
$clip=$_POST['clip'];
$id=$_POST['id'];
$safe=safe_query("UPDATE ".PREFIX."videos SET rubric='$rubric', vidheadline='$name', vidlength='$length', vidsource='$source', vidclip='$clip', viddescription='$desc' WHERE vidID=$id");
$preview=$_FILES['preview'];
$prename = $preview['name'];
if(!empty($prename)) {	
$safe2=safe_query("UPDATE ".PREFIX."videos SET vidpreview='$prename' WHERE vidID=$id");

/********************** WENN ALLES STIMMT, DANN ERFOLGREICH. WENN NICHT, DANN FEHLERMELDUNG **********************/

if($upload && $safe2)echo'<div ID="successful">'.$_language->module['suc_prev'].'</div>';
else echo'<div ID="error">'.$_language->module['err_prev'].'</div>';
}

/********************** AUFFORDERUNG ES ERNEUT ZU VERSUCHEN + AUTOMATISCHES ZURÜCKGEHEN AUF DIE VIDEOS SEITE **********************/

if($safe)echo'<div ID="successful">'.$_language->module['suc_edit'].'</div><meta http-equiv="refresh" content="2; URL=index.php?site=videos">';
else echo'<div ID="error">'.$_language->module['err_entry'].'</div>';
}

/********************** SHOW **********************/

if($_GET['show']) {
   $result=safe_query("SELECT rubricID FROM ".PREFIX."videos_rubrics WHERE rubric='".$_GET['show']."' LIMIT 0,1");
   $dv=mysql_fetch_array($result);
   $showonly = "AND rubric='".$dv[rubricID]."'";
  }
  
/********************** ACTION DELETE **********************/

elseif($_GET['action'] == "delete") {
if(!isnewsadmin($userID)) die('<div ID="error">'.$_language->module['no_perm'].'</div>');
$id=$_GET['id'];
safe_query("DELETE FROM ".PREFIX."videos WHERE vidID=$id");
echo'<div ID="successful">'.$_language->module['suc_delete'].'</div><meta http-equiv="refresh" content="2; URL=index.php?site=videos">';
}

echo (''); /* DIESES COPYRIGHT DARF NUR DANN ENTFERNT WERDEN, WENN AUF EURER SEITE EIN QUELLENNACHWEIS ANGEGEBEN WIRD! */ 

?>