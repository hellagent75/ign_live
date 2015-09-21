<?php
/*
 ########################################################################
#                                                                        #
#           Version 4       /                        /   /               #
#          -----------__---/__---__------__----__---/---/-               #
#           | /| /  /___) /   ) (_ `   /   ) /___) /   /                 #
#          _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/___               #
#                       Free Content / Management System                 #
#                                   /                                    #
#                                                                        #
#                                                                        #
#   Copyright 2005-2006 by webspell.org                                  #
#                                                                        #
#   visit webSPELL.org, webspell.info to get webSPELL for free           #
#   - Script runs under the GNU GENERAL PUBLIC LICENSE                   #
#   - It's NOT allowed to remove this copyright-tag                      #
#   -- http://www.fsf.org/licensing/licenses/gpl.html                    #
#                                                                        #
#   Code based on WebSPELL Clanpackage (Michael Gruber - webspell.at),   #
#   Far Development by Development Team - webspell.org                   #
#                                                                        #
#   visit webspell.org                                                   #
#   addon by esport-project.net                                          #
#                                                                        #
 ########################################################################
*/

$ergebnis = safe_query("SELECT * FROM ".PREFIX."awards ORDER BY date DESC LIMIT 0,4");

while($ds=mysql_fetch_array($ergebnis)) {
			$date=date("d.m.Y", $ds[date]);
			$squad=''.getsquadname($ds[squadID]).'';
			$award=cleartext($ds[award]);		
			$rangz=$ds['rang'];
			if($rangz=='1') $rang='<img class="awards" src="images/awards/gold.png" alt="1st" title="1st" />';
			elseif($rangz=='2') $rang='<img class="awards" src="images/awards/silver.png" alt="2nd" title="2nd" />';
			elseif($rangz=='3') $rang='<img class="awards" src="images/awards/bronze.png" alt="3rd" title="3rd" />';
			elseif($rangz=='4') $rang='<img class="awards" src="images/awards/7th.png" alt="4th" title="4th" />';
			elseif($rangz=='5') $rang='<img class="awards" src="images/awards/7th.png" alt="5th" title="5th" />';
			elseif($rangz=='6') $rang='<img class="awards" src="images/awards/7th.png" alt="6th" title="6th" />';
			elseif($rangz=='7') $rang='<img class="awards" src="images/awards/7th.png" alt="7th" title="7th" />';
			elseif($rangz=='8') $rang='<img class="awards" src="images/awards/7th.png" alt="8th" title="8th" />';
			elseif($rangz=='9') $rang='<img class="awards" src="images/awards/7th.png" alt="9th" title="9th" />';
			elseif($rangz=='10') $rang='<img class="awards" src="images/awards/7th.png" alt="10th" title="10th" />';	
			else $rang=$rangz;
			
			
    eval ("\$sc_awards = \"".gettemplate("sm_awards")."\";");
    echo $sc_awards;
}

?>