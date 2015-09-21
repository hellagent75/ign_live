<?php

$ergebnis=safe_query("SELECT * FROM ".PREFIX."streams WHERE main='1' AND displayed='1' AND status='1' ORDER BY viewers DESC LIMIT 0,1");
if(mysql_num_rows($ergebnis)){
	
	while($ds=mysql_fetch_array($ergebnis)) {
		
		$type = $ds['type'];
		$title = $ds['title'];
		$channel = $ds['channel'];
	
		// Build
		if($type == 1) echo '<div id="main-stream"><div id="main-stream-header"><h1><a href="streams/">Streams</a> » '.$title.'</h1></div><div id="main-stream-content"><object type="application/x-shockwave-flash" height="503" width="949" id="live_embed_player_flash" data="http://pt-br.twitch.tv/widgets/live_embed_player.swf?channel='.$channel.'" bgcolor="#000000"><param name="allowFullScreen" value="true" /><param name="allowScriptAccess" value="always" /><param name="allowNetworking" value="all" /><param name="movie" value="http://pt-br.twitch.tv/widgets/live_embed_player.swf" /><param name="flashvars" value="hostname=pt-br.twitch.tv&channel='.$channel.'&auto_play=false&start_volume=25" /></object></div></div>';
	
		elseif($type == 2) echo '<div id="main-stream"><div id="main-stream-header"><h1><a href="streams/">Streams</a> » '.$title.'</h1></div><div id="main-stream-content"><iframe frameborder="0" scrolling="no" src="http://www.own3d.tv/liveembed/'.$channel.'?autoPlay=false" height="303" width="634"></iframe></div></div>';
		
	}	
	
}

else include("sc_slider.php");

?>