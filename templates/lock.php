<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>$pagetitle - Locked</title>
<link href='http://fonts.googleapis.com/css?family=Titillium+Web%7COpen+Sans:400,600' rel='stylesheet' type='text/css'>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="css/sm_lock.css"/>
<link rel="shortcut icon" type="image/x-icon" href="sm/favicon.ico"/>
</head>
<body>

<div id="wrapper">

	<div class="logo"><img src="./sm/logo.png" alt=""/></div>

    <div id="login_area">
      <form method="post" name="login" id="login_form" action="checklogin.php">
      <input name="ws_user" type="text" value="username" onfocus="if(this.value=='username') this.value='';" onblur="if(this.value=='') { this.value='username'; }"  />
      <input name="pwd" type="password" value="password" onfocus="if(this.value=='password') this.value='';" onblur="if(this.value=='') { this.value='password'; }" />
      <input type="submit" name="Submit" value="Admincenter Login" class="log" />
      </form>
    </div>
    

        <div id="reason">
          $reason
        </div>
        
</div>

</body>
</html>