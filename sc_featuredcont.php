<?php 
$max_fcname = 50;
$max_fctext = 85;
$max_entrys = 5;

$fullimgs = "";
$fcnavs = "";

$query = safe_query("SELECT * FROM ".PREFIX."featuredcont WHERE activated=1 ORDER BY sortid ASC LIMIT 0,".$max_entrys);	
$blankID = 1;
while($result = mysql_fetch_array($query)) {
	$fullimgurl = $result['fullimg'];
	$fcid = $result['id'];
	$curclass = ($blankID == 1) ? $curclass = " cur" : $curclass="";
	$fcurl = $result['url'];
	$fctext = $result['text'];
			if(mb_strlen($fctext)>$max_fctext) {
			$fctext=mb_substr($fctext, 0, $max_fctext);
			$fctext.='...';
		}
	$fcname = $result['name'];
	$fctease = $result['tease'];
			if(mb_strlen($fctease)>$max_fcname) {
			$fctease=mb_substr($fctease, 0, $max_fcname);
			$fctease.='...';
		}
	eval ("\$fullimgs .= \"".gettemplate("sc_fcfullimg")."\";");
	$blankID++;
}

eval ("\$sc_featuredcont = \"".gettemplate("sc_fcwrap")."\";");
echo $sc_featuredcont;
?>