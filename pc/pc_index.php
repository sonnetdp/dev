<?php
include('../config_pms.php');

session_start();
if(isset($_SESSION['grp'])){
	$grpMsg = $_SESSION['grp'];
}
else
{
	$grpMsg ='';
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content = "width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" name = "viewport" />
<title>Mobile Interface | Login</title>
<link href="<?php echo URL; ?>style/main.css" rel="stylesheet" type="text/css"/>
<style type="text/css">

</style>
</head>

<body>

<div id="main">
	<div id="logo"><a href="/"><img src="<?php echo URL; ?>images/cacts-logo.gif" width="80" height="39" alt="logo" border="0" style="padding-bottom:5px;"/></a>
    </div>
	<div style="padding:15px; background-color:#f2f2f2;-moz-border-radius:6px; -webkit-border-radius: 6px;">
<form method="post" action="<?php echo URL; ?>login_query.php" enctype="multipart/form-data">
	<div id="label"><span style="color:red;"><?php echo $grpMsg; ?></span></div>
    <input type="hidden" name="device" value="pc" class="input"/>
    <div id="label">Username</div>
<input type="text" name="username" class="input"/>
    <div id="label">Password</div>
<input type="password" name="password" class="input"/>
<input type="submit" value="  Login  " class="login"/>
</form>
	</div>
     <?php 
	 session_destroy();
	 include(BASEPATH."footer.php");?>
</div>

</body>
</html>