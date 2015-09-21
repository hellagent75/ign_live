<div id="navi">
	<ul>
        <li <?php if($site=="include_home") echo 'class="active"'; ?>><a href="./">Home.</a></li>
        <li <?php if($site=="news" or $site=="news_comments") echo 'class="active"'; ?>><a href="news/">News.</a></li>
        <li <?php if($site=="forum" or $site=="forum_topic") echo 'class="active"'; ?>><a href="forum/">Forums.</a></li>
        <li <?php if($site=="squads_full") echo 'class="active"'; ?>><a href="teams/">Teams.</a></li>
        <li <?php if($site=="clanwars" or $site=="clanwars_details") echo 'class="active"'; ?>><a href="results/">Results.</a></li>
        <li <?php if($site=="videos") echo 'class="active"'; ?>><a href="videos/">Videos.</a></li>
        <li <?php if($site=="gallery") echo 'class="active"'; ?>><a href="albums/">Albums.</a></li>
        <li <?php if($site=="about") echo 'class="active"'; ?>><a href="about/">About.</a></li>
        <li <?php if($site=="sponsors") echo 'class="active"'; ?>><a href="sponsors/">Sponsors.</a></li>
	</ul>
    
    <div id="login_area">
		<?php include("login.php"); ?>
	</div>
    
</div>