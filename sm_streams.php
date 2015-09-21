<?php
$streams=safe_query("SELECT * FROM ".PREFIX."streams WHERE displayed='1' AND status='1' ORDER BY featured DESC, viewers DESC LIMIT 8");
if(mysql_num_rows($streams)) {
	
	while($ds=mysql_fetch_array($streams)) {
		
		$streamID = $ds['streamID'];
		$title = $ds['title'];
		if(mb_strlen($title)>19) {
			$title=mb_substr($title, 0, 19);
			$title.='..';
		}
		$status = $ds['status'];
		$viewers = $ds['viewers'];
		$type = $ds['type'];
		$featured = $ds['featured'];
		$thumb = $ds['thumb'];
		
		$game = mysql_fetch_array(safe_query("SELECT tag FROM ".PREFIX."games WHERE gameID = '".$ds['gameID']."' LIMIT 0,1"));
		$game = $game['tag'];
				
		echo '<div class="sm_gallery" style="background:url('.$thumb.') #000; background-size: 249px 142px;">
    <div class="gallery_overlay"><img src="images/games/'.$game.'.gif" alt="" />
    <small>'.$viewers.' VIEWERS</small><br/>
	<a href="stream/'.$streamID.'/">'.$title.'</a>
    </div>
</div>
';		
	}
} else echo '<div style="margin:7px 0px 0px 0px; float:left;">No Streams Available.</div>';
?>