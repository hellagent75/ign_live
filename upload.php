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
#   Copyright 2005-2011 by webspell.org                                  #
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

include("_mysql.php");
include("_settings.php");
include("_functions.php");
$_language->read_module('upload');
if(!isanyadmin($userID)) die($_language->module['no_access']);

if(isset($_GET['cwID'])) {
	$filepath = "images/clanwar-screens/";
	$table = "clanwars";
	$tableid = "cwID";
	$id = $_GET['cwID'];
}
elseif(isset($_GET['newsID'])) {
	$filepath = "images/news-pics/";
	$table = "news";
	$tableid = "newsID";
	$id = $_GET['newsID'];
}
elseif(isset($_GET['articlesID'])) {
	$filepath = "images/articles-pics/";
	$table = "articles";
	$tableid = "articlesID";
	$id = $_GET['articlesID'];
}
else die($_language->module['invalid_access']);

if(isset($_GET['action'])) $action = $_GET['action'];
else $action = null;

if(isset($_POST['submit'])) {
	$screen = $_FILES['screen'];
	if(!empty($screen['name'])) {
		move_uploaded_file($screen['tmp_name'], $filepath.$screen['name']);
		@chmod($filepath.$screen['name'], $new_chmod);
		$file_ext=strtolower(mb_substr($screen['name'], strrpos($screen['name'], ".")));
		$file=$id.'_'.time().$file_ext;
		rename($filepath.$screen['name'], $filepath.$file);
		$ergebnis=safe_query("SELECT screens FROM ".PREFIX."$table WHERE $tableid='$id'");
		$ds=mysql_fetch_array($ergebnis);
		$screens=explode("|", $ds['screens']);
		$screens[]=$file;
		$screens_string=implode("|", $screens);

		safe_query("UPDATE ".PREFIX.$table." SET screens='".$screens_string."' WHERE ".$tableid."='".$id."'");
	}
	header("Location: upload.php?$tableid=$id");
}
elseif($action=="delete") {
	$file = $_GET['file'];
	if(file_exists($filepath.$file)) @unlink($filepath.$file);

	$ergebnis=safe_query("SELECT screens FROM ".PREFIX."$table WHERE $tableid='$id'");
	$ds=mysql_fetch_array($ergebnis);
	$screens=explode("|", $ds['screens']);
	foreach($screens as $pic) {
		if($pic != $file) $newscreens[]=$pic;
	}
	if(is_array($newscreens)) $newscreens_string=implode("|", $newscreens);
	safe_query("UPDATE ".PREFIX."$table SET screens='$newscreens_string' WHERE $tableid='$id'");

	header("Location: upload.php?$tableid=$id");

}
else {

echo'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="description" content="Clanpage using webSPELL 4 CMS" />
	<meta name="author" content="webspell.org" />
	<meta name="keywords" content="webspell, webspell4, clan, cms" />
	<meta name="copyright" content="Copyright &copy; 2005 - 2011 by webspell.org" />
	<meta name="generator" content="webSPELL" />
	<title>'.$_language->module['file_upload'].'</title>
  <script src="js/bbcode.js" language="jscript" type="text/javascript"></script>
	<link href="_stylesheet.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
* { margin: 0px; padding: 0px; outline: none; }
html, body { background: #0c0d0f; font-family: Tahoma, Geneva, sans-serif; font-size: 12px; color: #fff; }
html { padding: 15px; }

/* Links */
a, a:link, a:visited { color: #fff; text-decoration: none; font-family: Tahoma, Geneva, sans-serif; -webkit-transition: all 0.3s ease-in ; -moz-transition: all 0.3s ease-in ; -ms-transition: all 0.3s ease-in ; -o-transition: all 0.3s ease-in ; transition: all 0.3s ease-in ; }
a:hover { color: #f26722; }

/* Resets */
::selection { background: #999; color: #fff; text-shadow: none; }
ul { list-style: none; }
li { list-style: none; }

::-webkit-scrollbar { width: 12px; }
::-webkit-scrollbar-track-piece { background: #222; }
::-webkit-scrollbar-thumb { background: #666; border-radius: 3px; border: 1px #222 solid; -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5); }
::-webkit-scrollbar-corner { display: none; }
::-webkit-resizer { display: none; }

/* Forms */
form { border: none; margin: 0px; padding: 0px; }
input { border: none; font-size: 12px; font-family: Tahoma, Geneva, sans-serif; background: #222222; border-radius: 2px; -moz-border-radius: 2px; -webkit-border-radius: 2px; color: #999999; padding: 5px 7px 5px 7px; color: #999;  border: 1px #302f2f solid; margin: 3px 0px 3px 0px; }
input[type=submit], input[type=button] { border: none; font-size: 12px; font-family: Tahoma, Geneva, sans-serif; background: #343434; border-radius: 2px; -moz-border-radius: 2px; -webkit-border-radius: 2px; color: #999999; padding: 5px 7px 5px 7px; color: #fff; box-shadow: 0 0 7px #000; margin: 3px 0px 3px 0px; cursor: pointer; }
input[type=submit], input[type=button]:hover { background: #2B5C8D; }
select { border: none; font-size: 12px; font-family: Tahoma, Geneva, sans-serif; background: #222222; border-radius: 2px; -moz-border-radius: 2px; -webkit-border-radius: 2px; color: #999999; padding: 5px 7px 5px 7px; color: #999; border: 1px #302f2f solid; margin: 3px 0px 3px 0px; }
option { border: 0px; }
textarea { background: #222222; border-radius: 2px; padding: 10px; color: #999; border: 1px #302f2f solid; }

/* Wrappers */
#wrapper { background: #1d1d1d; width: 100%; box-shadow: 0 0 7px #080808; border-radius: 3px; float: left; }

#header { background:url(sm/core/blue_bg.png); width: 100%; height: 39px; float: left; }
#header h1 { font-size: 14px; margin: 10px 0px 0px 10px; float: left; }

#content { width: 100%; float: left; }
#content-inner { width: 96%; margin: 2%; float: left; }
#content-inner h1 { width: 100%; padding: 0px 0px 2px 0px; margin: 10px 0px 10px 0px; border-bottom: 1px dotted #505050; color: #999; font-size: 14px; float: left; }

#content-inner ul { width: 100%; float: left; }
#content-inner ul li { width: 33%; float: left; }
</style>
</head>
<body>

<div id="wrapper">
  <div id="header">
    <h1>'.$_language->module['file_upload'].':</h1>
  </div>
    <div id="content">
    <div id="content-inner">
<form method="post" action="upload.php?'.$tableid.'='.$id.'" enctype="multipart/form-data">
<table width="100%" cellpadding="4" cellspacing="1">
  <tr>
    <td align="center"><input type="file" name="screen" />
    <input type="submit" name="submit" value="'.$_language->module['upload'].'" />
   
    <h1>'.$_language->module['existing_files'].':</h1>
    <table width="100%" border="0" cellspacing="0" cellpadding="2">';

	$ergebnis=safe_query("SELECT screens FROM ".PREFIX."$table WHERE $tableid='$id'");

	$ds=mysql_fetch_array($ergebnis);
	$screens = array();
	if(!empty($ds['screens'])) $screens=explode("|", $ds['screens']);
	if(is_array($screens)) {
		foreach($screens as $screen) {
			if($screen!="") {
				
        echo'<tr>
            <td><a href="'.$filepath.$screen.'" target="_blank">'.$screen.'</a></td>
            <td><input type="text" name="pic" size="70" value="&lt;img src=&quot;'.$filepath.$screen.'&quot; border=&quot;0&quot; align=&quot;left&quot; style=&quot;padding:4px;&quot; alt=&quot;&quot; /&gt;" /></td>
            <td><input type="button" onclick="AddCodeFromWindow(\'[img]'.$filepath.$screen.'[/img] \')" value="'.$_language->module['add_to_message'].'" /></td>
            <td><input type="button" onclick="MM_confirm(\''.$_language->module['delete'].'\',\'upload.php?action=delete&amp;'.$tableid.'='.$id.'&amp;file='.$screen.'\')" value="'.$_language->module['delete'].'" /></td>
          </tr>';
			}
		}
	}

	echo'</table>
      </td>
    </tr>
  </table>
  </form>
  <br /><br /><input type="button" onclick="javascript:self.close()" value="'.$_language->module['close_window'].'" />
  </div></div>
  </div>
  </body>
  </html>';
}
?>
