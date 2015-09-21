<?php
$ergebnis=safe_query("SELECT * FROM ".PREFIX."videos ORDER BY vidID DESC LIMIT 0,8");

while($ds=mysql_fetch_array($ergebnis)) {

$name=$ds[vidheadline];
	if(strlen($name)>25) {
	    $name=substr($name, 0, 25);
		$name.='..';
		}	
		
echo('<div class="sm_gallery" style="background:url(http://img.youtube.com/vi/'.$ds[vidclip].'/mqdefault.jpg) #12181C; background-size: 249px 142px;">
    <div class="gallery_overlay"><small>VIDEO</small><br/>
	<a href="video/'.$ds[vidID].'/">'.clearfromtags($name).'</a>
    </div></div>');}?>