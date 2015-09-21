<h1>&curren; Content Slider</h1>
<?php if($_GET['delete']){
		$img_id = $_GET['delete'];
		$filepath = "../sm/slider/upload/";
		safe_query("DELETE FROM ".PREFIX."nivosliderimgs WHERE img_id='$img_id'");
		
		if(file_exists($filepath.$img_id.'_thumb.jpg')) @unlink($filepath.$img_id.'_thumb.jpg');
		if(file_exists($filepath.$img_id.'.jpg')) @unlink($filepath.$img_id.'.jpg');
		
		header("LOCATION:?site=slider");
	 }elseif($_GET['edit']){ 
	 	$id = $_GET['edit'];
	 	$qimgs=safe_query("SELECT * FROM ".PREFIX."nivosliderimgs WHERE img_id='$id'");
        $rimgs=mysql_fetch_array($qimgs);
		
	 	if($_POST['edit']){	
			$img = $_FILES['img'];
			$img_thumb = $_FILES['img_thumb'];
		
			$caption = $_POST['caption'];
			safe_query("UPDATE ".PREFIX."nivosliderimgs SET caption='$caption' WHERE img_id='$id'");
			
			$link = $_POST['link'];
			safe_query("UPDATE ".PREFIX."nivosliderimgs SET link='$link' WHERE img_id='$id'");
			
			$header = $_POST['header'];
			safe_query("UPDATE ".PREFIX."nivosliderimgs SET header='$header' WHERE img_id='$id'");
			
			$error = 0;
			$filepath = "../sm/slider/upload/";
			
				
		  if($img['name'] != "") {
			  move_uploaded_file($img['tmp_name'], $filepath.$img['name'].".tmp");
			  @chmod($filepath.$img['name'].".tmp", 0755);
			  $getimg = getimagesize($filepath.$img['name'].".tmp");
			  if($getimg[2] == 1) $ext='.gif';
			  elseif($getimg[2] == 2) $ext='.jpg';
			  elseif($getimg[2] == 3) $ext='.png';
			  $image = $id.$ext;
			  if($image != "") {
				  if(file_exists($filepath.$id.'.gif')) unlink($filepath.$id.'.gif');
				  if(file_exists($filepath.$id.'.jpg')) unlink($filepath.$id.'.jpg');
				  if(file_exists($filepath.$id.'.png')) unlink($filepath.$id.'.png');
				  rename($filepath.$img['name'].".tmp", $filepath.$id.$ext);
				  safe_query("UPDATE ".PREFIX."nivosliderimgs SET ext='$ext' WHERE img_id='$id'");
			  }  else {
				  $error = 1;
				  @unlink($filepath.$img['name'].".tmp");
				  echo '<b>IMAGE: Please only upload images gif, jpg and png.</b><br /><br />';
			  }
		  }
			
			if($error!=1) header("LOCATION:?site=slider");
		}
?>
	<strong>** I highly recomend you use JPGMini to Compress your slider images, Its free & will reduce the loadtime! **</strong><br /><br />
     <form method="post" action="admincenter.php?site=slider&edit=<?php echo $id; ?>" enctype="multipart/form-data">
        <table width="100%" border="0" cellspacing="1" cellpadding="3">
            <tr>
              <td><b>Slider Image</b></td>
              <td><img src="../sm/slider/upload/<?php echo $id.$rimgs['ext']; ?>" alt="<?php echo $id; ?>" width="500" height="275" /><br /><input name="img" type="file" /></td>
            </tr>
            <tr>
              <td><b>Link</b></td>
              <td><input name="link" value="<?php echo $rimgs['link']; ?>" type="text" /></td>
            </tr>
            <tr>
              <td><b>Small Title</b></td>
              <td><input name="header" value="<?php echo $rimgs['header']; ?>"  type="text" /></td>
            </tr>
            <tr>
              <td width="15%"><b>Main Headline</b></td>
              <td width="85%"><textarea cols="50" rows="3" name="caption"><?php echo $rimgs['caption']; ?></textarea><br /><small>(HTML is allowed)</small></td>
            </tr>
            <tr>
              <td></td>
              <td><input type="submit" name="edit" value="Edit" /></td>
            </tr>
        </table>
    </form>

    <br /><br />
    <a href="?site=slider"> &laquo; Back</a>
<?php }elseif($_GET['upload']){ 
		if($_POST['upload']){
			$img = $_FILES['img'];
			$img_thumb = $_FILES['img_thumb'];
			
			safe_query("INSERT INTO ".PREFIX."nivosliderimgs ( caption, link, header ) values( '".$_POST['caption']."', '".$_POST['link']."', '".$_POST['header']."' ) ");
			$id=mysql_insert_id();
			
			$filepath = "../sm/slider/upload/";
			
			if($img['name'] != "") {
				move_uploaded_file($img['tmp_name'], $filepath.$img['name'].".tmp");
				@chmod($filepath.$img['name'].".tmp", 0755);
				$getimg = getimagesize($filepath.$img['name'].".tmp");
				if($getimg[2] == 1) $ext='.gif';
				elseif($getimg[2] == 2) $ext='.jpg';
				elseif($getimg[2] == 3) $ext='.png';
				$image = $id.$ext;
				if($image != "") {
					if(file_exists($filepath.$id.'.gif')) unlink($filepath.$id.'.gif');
					if(file_exists($filepath.$id.'.jpg')) unlink($filepath.$id.'.jpg');
					if(file_exists($filepath.$id.'.png')) unlink($filepath.$id.'.png');
					rename($filepath.$img['name'].".tmp", $filepath.$id.$ext);
					safe_query("UPDATE ".PREFIX."nivosliderimgs SET ext='$ext' WHERE img_id='$id'");
				}  else {
					@unlink($filepath.$img['name'].".tmp");
					echo '<b>IMAGE: Please only upload images gif, jpg and png.</b><br /><br />';
				}
			}else echo '<b>Please upload a image...</b><br /><br />';
			
			if(file_exists($filepath.$id.'_thumb.gif') || file_exists($filepath.$id.'_thumb.gif')) header("LOCATION:?site=slider");
			if(file_exists($filepath.$id.'_thumb.jpg') || file_exists($filepath.$id.'_thumb.jpg')) header("LOCATION:?site=slider");
			if(file_exists($filepath.$id.'_thumb.png') || file_exists($filepath.$id.'_thumb.png')) header("LOCATION:?site=slider");
			
			header("LOCATION:?site=slider");
		}
?>
	<strong>** I highly recomend you use JPGMini to Compress your slider images, Its free & will reduce the loadtime! **</strong><br /><br />
	<form method="post" action="admincenter.php?site=slider&upload=1" enctype="multipart/form-data">
        <table width="100%" border="0" cellspacing="1" cellpadding="3">
            <tr>
              <td><b>Slider Image</b></td>
              <td><input name="img" type="file" /></td>
            </tr>
            <tr>
              <td><b>Link</b></td>
              <td><input name="link" value="" type="text" /></td>
            </tr>
            <tr>
              <td><b>Small Title</b></td>
              <td><input name="header" value="" type="text" /></td>
            </tr>
            <tr>
              <td width="15%"><b>Main Headline</b></td>
              <td width="85%"><textarea cols="50" rows="2" name="caption"></textarea><br /><small>(HTML is allowed)</small></td>
            </tr>
            <tr>
              <td></td>
              <td><input type="submit" name="upload" value="Upload Slider"/></td>
            </tr>
        </table>
    </form>
    <br />
    <a href="?site=slider"> &laquo; Back</a>
<?php }else{ ?>
    <input type="button" onclick="MM_goToURL('parent','admincenter.php?site=slider&amp;upload=1');return" value="Add new Image" />
    <br /><br />
    
    <table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#DDDDDD">
        <tr>
          <td width="25%" class="title"><b>Image</b></td>
          <td width="55%" class="title"><b>Title</b></td>
          <td width="55%" class="title"><b>Options</b></td>
        </tr>
        <?php
            $qimgs=safe_query("SELECT * FROM ".PREFIX."nivosliderimgs");
            while($rimgs=mysql_fetch_array($qimgs)){
                if($i%2) { $td='td1'; }
                else { $td='td2'; }
                
                echo '<tr>
                        <td class="'.$td.'" align="center">
                            <img src="../sm/slider/upload/'.$rimgs['img_id'].'.jpg" width="300" height="175" alt="" />
                        </td>
                        <td class="'.$td.'" align="center"><a href="http://'.$rimgs['link'].'">'.$rimgs['header'].'</a><br />'.$rimgs['caption'].'</td>
                        <td class="'.$td.'" align="center">
                            <input type="button" onclick="MM_goToURL(\'parent\',\'admincenter.php?site=slider&amp;edit='.$rimgs['img_id'].'\');return document.MM_returnValue" value="Edit" />
                            <input type="button" onclick="MM_confirm(\'You really want to delete?\', \'admincenter.php?site=slider&amp;delete='.$rimgs['img_id'].'\')" value="Delete"  />
                        </td>
                     </tr>';
            }
        ?>
    </table>
<?php } ?>