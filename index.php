<?php
include("_mysql.php");
include("_settings.php");
include("_functions.php");
$_language->read_module('index');
$index_language = $_language->module;
?>
<!DOCTYPE html>
<html>
<head>
<base href="http://<?php echo $hp_url; ?>/"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="webSPELL Template by www.shamedia.net" />
<meta name="author" content="design by shamedia.net, webspell.org adaption by shamedia" />
<meta name="keywords" content="webspell, template, clan, design, shamedia" />
<title><?php echo PAGETITLE; ?></title>

<link href='http://fonts.googleapis.com/css?family=Titillium+Web%7COpen+Sans:400,600' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="_stylesheet.css"/>
<link rel="stylesheet" type="text/css" href="css/sm_impetus.css"/>
<link rel="shortcut icon" type="image/x-icon" href="sm/favicon.ico"/>
<link href="tmp/rss.xml" rel="alternate" type="application/rss+xml" title="<?php echo getinput($myclanname); ?> - RSS Feed" />

<script type="text/javascript" src="js/bbcode.js" ></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="js/lightbox/themes/default/jquery.lightbox.css" />
<script type="text/javascript" src="js/ws/jquery.idTabs.min.js"></script>
<script type="text/javascript" src="js/ws/unoSlider.js"></script>
<script type="text/javascript"> $(document).ready(function() { window.unoSlider = $('#slider1').unoSlider(); });</script>
<script type="text/javascript" src="js/ws/jquery.tinycarousel.min.js"></script>
<script type="text/javascript">$(document).ready(function(){$('.sponsor_top').tinycarousel({display:1, controls:true, interval:false, intervaltime:3000, animation:true,});});</script> 
</head>
<body>

<div id="top_container">
 
    <div class="full_width">
        <?php include("navibar.php"); ?>
    </div>
    
</div>

<div id="header">
    <div id="logo"><a href="#"><img src="./sm/logo.png" alt="logo"/></a></div>
        <div class="sponsor_top">
            <a class="buttons prev" href="#">Left</a>
            <div class="viewport">
            <ul class="overview"><?php include("sc_sponsors.php"); ?></ul>
            </div>
            <a class="buttons next" href="#">Right</a>
        </div>
</div>
<?php if(!isset($_GET['site']) || $site == 'main') include("include_home.php"); else include("include_inner.php"); ?>
</body>
</html>