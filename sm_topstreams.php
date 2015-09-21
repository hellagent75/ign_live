<?php

$streams=safe_query("SELECT * FROM ".PREFIX."streams WHERE displayed='1' AND status='1' ORDER BY featured DESC, viewers DESC LIMIT 5");
if(mysql_num_rows($streams)) {

	while($ds=mysql_fetch_array($streams)) {
		
		$streamID = $ds['streamID'];
		$title = $ds['title'];
		$status = $ds['status'];
		$viewers = $ds['viewers'];
		$type = $ds['type'];
		$featured = $ds['featured'];
		
		$game = mysql_fetch_array(safe_query("SELECT tag FROM ".PREFIX."games WHERE gameID = '".$ds['gameID']."' LIMIT 0,1"));
		$game = $game['tag'];
		
		if($type == 1) $type_img = '<img src="gfx/streams/twitch.png" class="type" />';
		elseif($type == 2) $type_img = '<img src="gfx/streams/own3d.png" class="type" />';
		
		if($featured == 1) $featured_stream = '<img src="gfx/streams/featured.png" style="width: 25px; height: 25px; position: absolute; margin: 0px; float: left;" />';
		elseif($featured == 0) $featured_stream = '';
		
		echo '<div class="sm_bg"><img src="images/games/'.$game.'.gif" alt=""><a href="stream/'.$streamID.'/">'.$title.'</a> <span>'.$viewers.'</span> </div>';
			
	}
	
} else echo '<div class="sm_bg">No Streams Available. <span>NA</span> </div>';

?>
