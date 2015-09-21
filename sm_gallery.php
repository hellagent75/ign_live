<?php
/*
##########################################################################
#                                                                        #
#           Version 4       /                        /   /               #
#          -----------__---/__---__------__----__---/---/-               #
#           | /| /  /___) /   ) (_ `   /   ) /___) /   /                 #
#          _|/_|/__(___ _(___/_(__)___/___/_(___ _/___/___               #
#                       Free Content / Management System                 #
#                                   /                                    #
#                                                                        #
#                                                                        #
#   Copyright 2005-2010 by webspell.org                                  #
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
#                                                                        #
##########################################################################
*/
	$galclass = new Gallery;
	$_language->read_module('gallery');

	$ergebnis = safe_query("SELECT * FROM ".PREFIX."gallery ORDER BY galleryID DESC LIMIT 0,4");

	while($ds = mysql_fetch_array($ergebnis)) {
		$title = $ds['name'];
		
		if(mb_strlen($title)>65) {
		$title=mb_substr($title, 0, 65);
		$title.='...';
		}
		
		
		$gallerys = mysql_num_rows(safe_query("SELECT galleryID FROM ".PREFIX."gallery WHERE galleryID='".$ds['galleryID']."'"));
		$pics = mysql_num_rows(safe_query("SELECT picID FROM ".PREFIX."gallery as gal, ".PREFIX."gallery_pictures as pic WHERE gal.galleryID='".$ds['galleryID']."' AND gal.galleryID=pic.galleryID"));

    $gallery = mysql_fetch_array(safe_query("SELECT * FROM ".PREFIX."gallery WHERE galleryID='".$ds['galleryID']."' ORDER BY galleryID DESC LIMIT 0,5"));

		
		//Datum ausgabe	
		$monate = array(1=>"Januar", 2=>"Februar", 3=>"März",  4=>"April", 5=>"Mai", 6=>"Juni", 7=>"Juli", 8=>"August", 9=>"September", 10=>"Oktober", 11=>"November", 12=>"Dezember");
		$monat = date("n", $gallery['date']);
		$day = date("d.", $gallery['date']);
		$ger_monat = $monate[$monat];
		$year = date("Y", $gallery['date']);
		//Datum ausgabe	ende
		
		$gallery['picID'] = $galclass->randompic($gallery['galleryID']);
		
		//Bilder ausgabe thumb
		$dir='images/gallery/';
		$gallery['pic'] = $dir.'thumb/'.$gallery['picID'].'.jpg';
		//Bilder ausgabe thumb ende
	
		//views ausgabe	geht leider nur von einem pic,
		$views = safe_query("SELECT * FROM ".PREFIX."gallery_pictures WHERE galleryID='".$gallery['galleryID']."'");		 
		if(mysql_num_rows($views)) {
		$view = array();
			while($ds = mysql_fetch_array($views)) {
				$view[] = $ds['views'];				
			}			
		 $views = array_sum($view);		 
		}	
		//views ausgabe	ende

		eval ("\$sc_gallery = \"".gettemplate("sm_gallery")."\";");
		echo $sc_gallery;


	$_language->read_module('gallery');


	}



?>