<?php

$ergebnis=safe_query("SELECT * FROM ".PREFIX."clanwars ORDER BY date DESC LIMIT 0,6");

if(mysql_num_rows($ergebnis)){
	$n=1;
	while($ds=mysql_fetch_array($ergebnis)) {

		$date=date("d.m.Y", $ds['date']);
		$homescr=array_sum(unserialize($ds['homescore']));
		$oppscr=array_sum(unserialize($ds['oppscore']));

		if($n%2) {
			$bg1=BG_1;
			$bg2=BG_2;
		}
		else {
			$bg1=BG_3;
			$bg2=BG_4;
		}
		
		 $clanwarslogos=safe_query("SELECT * FROM ".PREFIX."clanwars_logo WHERE logID='1'");
     while($dm=mysql_fetch_array($clanwarslogos)) {
          $opponent_logo = '<img src="images/clanwars/'.$dm['nologo'].'"  alt="" />';
          $ownlogo = $dm['logo'];
          }
     if($ds['banner']) {$opponent_logo='<img src="images/clanwars/'.$ds['banner'].'" alt="" />'; }
		

		if($homescr>$oppscr) $home_score='<div class="win_text">'.$homescr.'</div>';
		elseif($homescr<$oppscr) $home_score='<div class="loss_text">'.$homescr.'</div>';
		else $home_score='<div class="draw_text">'.$homescr.'</div>';
		
		if($oppscr>$homescr) $away_score='<div class="win_text">'.$oppscr.'</div>';
		elseif($oppscr<$homescr) $away_score='<div class="loss_text">'.$oppscr.'</div>';
		else $away_score='<div class="draw_text">'.$oppscr.'</div>';

		$resultID=$ds['cwID'];
		$gameicon="images/games/";
		if(file_exists($gameicon.$ds['game'].".gif")) $gameicon = $gameicon.$ds['game'].".gif";

		eval ("\$results = \"".gettemplate("sm_results_home")."\";");
		echo $results;
		$n++;
	}
	
	unset($game);

}
?>
