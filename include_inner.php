<div id="wrapper_container">
<?php if(isset($_GET['site']) AND ($_GET['site'] == "forum" OR $_GET['site'] == "forum_topic" or $site=="squads"  or $site=="squads_full" or $site=="sponsors" or $site=="profile" or $site=="register" or $site=="clanwars" or $site=="clanwars_details")) { ?>
<?php if(!isset($site)) $site="news";$invalide = array('\\','/','/\/',':','.');$site = str_replace($invalide,' ',$site);if(!file_exists($site.".php")) $site = "news";include($site.".php");?>
<?php } else { ?>

    <div id="inner_container">  
           
            <?php if(!isset($site)) $site="main";
            $invalide = array('\\','/','/\/',':','.');
            $site = str_replace($invalide,' ',$site);
            if(!file_exists($site.".php")) $site = "main";
            include($site.".php"); ?>
            
    </div>
    
    <div id="right_container">   
    <?php include("include_right.php"); ?>
    </div>
    <?php } ?> 
    
    <?php if(!isset($_GET['site']) || $site == 'clanwars') include("sm_squads.php"); ?> <div style="clear:left;"></div> 

	
</div>
<div style="clear:left;"></div>
<?php include("sm_footer.php"); ?> 
       
