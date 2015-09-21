<?php
$_language->read_module('login');

if($loggedin) {
	$username='<a href="user/'.$userID.'/">'.strip_tags(getnickname($userID)).'</a>';
	$myprofile='<a href="user/'.$userID.'/">View Profile</a>';
	if(isanyadmin($userID)) $admin='<a href="admin/admincenter.php" target="blank">Admin Center</a>';
	else $admin='';
	if(isclanmember($userID) or iscashadmin($userID)) $cashbox='&#8226; <a href="index.php?site=cash_box">'.$_language->module['cash-box'].'</a><br />';
	else $cashbox='';
	$anz=getnewmessages($userID);
	if($anz) {
		$newmessages=' (<b>'.$anz.'</b>)';
	}
	else $newmessages='';
	if($getavatar = getavatar($userID)) $l_avatar='<img src="images/avatars/'.$getavatar.'" alt="Avatar" />';
	else $l_avatar=$_language->module['n_a'];


	echo '<div id="user-logged">';
	eval ("\$logged = \"".gettemplate("sm_logged")."\";");
	echo $logged; echo $admin;
	echo '</div></div>';

}
else {
	//set sessiontest variable (checks if session works correctly)
	$_SESSION['ws_sessiontest'] = true;
	echo '<div id="user-panel">';
	eval ("\$loginform = \"".gettemplate("sm_login")."\";");
	echo $loginform;
	echo '</div>';
}

?>