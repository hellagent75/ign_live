<?php
chdir('../');
include("_mysql.php");
include("_settings.php");
include("_functions.php");
chdir('admin');

$_language->read_module('admincenter');

if(isset($_GET['site'])) $site = $_GET['site'];
else
if(isset($site)) unset($site);

$admin=isanyadmin($userID);
if(!$loggedin) die($_language->module['not_logged_in']);
if(!$admin) die($_language->module['access_denied']);

if(!isset($_SERVER['REQUEST_URI'])) {
	$arr = explode("/", $_SERVER['PHP_SELF']);
	$_SERVER['REQUEST_URI'] = "/" . $arr[count($arr)-1];
	if ($_SERVER['argv'][0]!="")
	$_SERVER['REQUEST_URI'] .= "?" . $_SERVER['argv'][0];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $myclanname ?> Admincenter</title>
<script language="JavaScript" type="text/JavaScript">
  var calledfrom='admin';
</script>
<script src="../js/bbcode.js" language="JavaScript" type="text/javascript"></script>
<link href="styles/layout.css" rel="stylesheet" type="text/css" />
</head>
<body>
  <div id="wrapper">
  <div id="logo"></div>
    <div id="wrapper-inner">
      <div id="inner-left">
        
        <div id="menu">
          <h1>Statistics</h1>
          <ul>
            <li><a href="admincenter.php">Overview</a></li>
            <li><a href="admincenter.php?site=page_statistic">Page Statistics</a></li>
            <li><a href="admincenter.php?site=visitor_statistic">Visitor Statistics</a></li>
            <li><a href="admincenter.php?site=contact">Contacts</a></li>
            <li><a href="../logout.php">Logout</a></li>
          </ul>
          <?php if(ispageadmin($userID)) { ?>
          <h1>Settings</h1>
          <ul>
            <li><a href="admincenter.php?site=settings">Settings</a></li>
            <li><a href="admincenter.php?site=countries">Countries</a></li>
            <li><a href="admincenter.php?site=games">Games</a></li>
            <li><a href="admincenter.php?site=database">Database</a></li>
          </ul>
          <?php } if(isuseradmin($userID)) { ?>
          <h1>Users</h1>
          <ul>
            <li><a href="admincenter.php?site=users">Registered Users</a></li>
            <li><a href="admincenter.php?site=newsletter">Newsletter</a></li>
          </ul>
          <?php } if(isuseradmin($userID)) { ?>
          <h1>Teams / Players</h1>
          <ul>
            <li><a href="admincenter.php?site=squads">Teams</a></li>
            <li><a href="admincenter.php?site=members">Members</a></li>
            <li><a href="admincenter.php?site=about">About</a></li>
          </ul>
          <?php } if(isnewsadmin($userID)) { ?>
          <h1>Articles, News</h1>
          <ul>
            <li><a href="admincenter.php?site=articles_rubrics">Articles - Rubrics</a></li>
            <li><a href="admincenter.php?site=rubrics">News - Rubrics</a></li>
            <li><a href="admincenter.php?site=newslanguages">News - Languages</a></li>
          </ul>
          <?php } if(ispageadmin($userID)) { ?>
          <h1>Content</h1>
          <ul>
		    <li><a href="admincenter.php?site=featuredcont">Content Slider</a></li>
            <li><a href="admincenter.php?site=streams">Streams</a></li>
            <li><a href="admincenter.php?site=vidrubrics">Videos</a></li>
            <li><a href="admincenter.php?site=topmatch">Next Match</a></li>
            <li><a href="admincenter.php?site=static">Static Pages</a></li>
            <li><a href="admincenter.php?site=sponsors">Sponsors</a></li>
            <li><a href="admincenter.php?site=partners">Partners *Footer</a></li>
            <li><a href="admincenter.php?site=bannerrotation">Advertising</a></li>
          </ul>
          <?php } if(isforumadmin($userID)) { ?>
          <h1>Forum</h1>
          <ul>
            <li><a href="admincenter.php?site=boards">Boards</a></li>
            <li><a href="admincenter.php?site=forumico">Forum Rubrics</a></li>
          </ul>
          <?php } if(isfileadmin($userID) || isgalleryadmin($userID)) { ?>
          <h1>Media</h1>
          <ul>
            <?php } if(isfileadmin($userID)) { ?>
            <li><a href="admincenter.php?site=filecategorys">Files - Categorys</a></li>
            <?php } if(isgalleryadmin($userID)) { ?>
            <li><a href="admincenter.php?site=gallery&amp;part=gallerys">Gallery - Manage</a></li>
            <li><a href="admincenter.php?site=gallery&amp;part=groups">Gallery - Groups, Categorys</a></li>
          </ul>
          <?php } ?>
        </div>
      </div>
      <div id="inner-right">
		<?php
        if(isset($site) && $site!="news"){
			$invalide = array('\\','/','//',':','.');
			$site = str_replace($invalide,' ',$site);
		if(file_exists($site.'.php')) include($site.'.php');
		}
		else include('overview.php');
		?>
      </div>
    </div>
  </div>
</body>
</html>
