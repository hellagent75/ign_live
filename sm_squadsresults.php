<?php
  
$_language->read_module('squads');	
	
$ergebnis=safe_query("SELECT * FROM ".PREFIX."squads WHERE gamesquad = '1' ORDER BY sort DESC LIMIT 4"); 
if(mysql_num_rows($ergebnis)) {
	$n=1;
	while($db=mysql_fetch_array($ergebnis)) {
		if($n%2) {
			$bg1=BG_1;
			$bg2=BG_2;
		}
		else {
			$bg1=BG_3;
			$bg2=BG_4;
		}
		$n++;
		$anzmembers=mysql_num_rows(safe_query("SELECT sqmID FROM ".PREFIX."squads_members WHERE squadID='".$db['squadID']."'"));
		$anzmembers = $_language->module['members'].': '.$anzmembers;
		
		$anzmatches=mysql_num_rows(safe_query("SELECT squad FROM ".PREFIX."clanwars WHERE squad='".$db['squadID']."'"));
		$anzmatches = ''.$_language->module['results'].': ';

		if(!empty($db['icon_small'])) $squadicon='images/squadicons/'.$db['icon_small'].'';
		else $squadicon='';		
		$squadname=getinput($db['name']);
		
		$totalHomeScoreSQ="";
		$totalOppScoreSQ="";
		$drawall="";
		$wonall="";
		$loseall="";
		  
		$squadcws=safe_query("SELECT * FROM ".PREFIX."clanwars WHERE squad='".$db['squadID']."'");
		while($squadcwdata=mysql_fetch_array($squadcws)) {

				// SQUAD CLANWAR STATISTICS

				// total squad homescore
				$sqHomeScoreQry=mysql_fetch_array(safe_query("SELECT homescore FROM ".PREFIX."clanwars WHERE cwID='".$squadcwdata['cwID']."' AND squad='".$db['squadID']."'"));
				$sqHomeScore=array_sum(unserialize($sqHomeScoreQry['homescore']));
				$totalHomeScoreSQ+=array_sum(unserialize($sqHomeScoreQry['homescore']));
				// total squad oppscore
				$sqOppScoreQry=mysql_fetch_array(safe_query("SELECT oppscore FROM ".PREFIX."clanwars WHERE cwID='".$squadcwdata['cwID']."' AND squad='".$db['squadID']."'"));
				$sqOppScore=array_sum(unserialize($sqOppScoreQry['oppscore']));
				$totalOppScoreSQ+=array_sum(unserialize($sqOppScoreQry['oppscore']));

				//
				if($sqHomeScore > $sqOppScore) $wonall++;
				if($sqHomeScore < $sqOppScore) $loseall++;
				if($sqHomeScore == $sqOppScore) $drawall++;
				//
				unset($sqHomeScore);
				unset($sqOppScore);
				
			}
		if(empty($wonall)) $wonall=0;
		if(empty($loseall)) $loseall=0;
		if(empty($drawall)) $drawall=0;
		eval ("\$sc_squads = \"".gettemplate("sm_squadresults")."\";");
		echo $sc_squads;
		unset($wonall);
		unset($loseall);
		unset($drawall);
	}
}
?>