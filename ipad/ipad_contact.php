<?php
session_start();
$_SESSION['device'] = 'pc';
include('../config_pms.php');
if($_SESSION['username']==''||$_SESSION['password']==''){//--------------Start if 001
	header("location:".DESK."pcindex.php");
}//End of 001


else if($_SESSION['username']!='' && $_SESSION['password']!='' && $_SESSION['pbs_user_is_loged_in'] == 'loged')
{
	$dbUser = $_SESSION['username'];
	$dbPass = $_SESSION['password'];
	include(INC."db_conn.php");
	include(INC."functions.php");
	$pbs = new PbsCal;
	if(!$c1){
		echo "Could not connect";
		exit;
	}
	
}
else{
	$_SESSION['msg']="Login User/Password Incorrect";
		header("location:".BASEPATH."pc/pcindex.php");
}

include("pc_header.php");
?>

  <div id="main">
   <?php include("pc_nav.php"); ?>
    <div id="site_content">
      <div class="content">
        
        <div id="label">Company: <span style="color:#112233;">CACTS LTD.</span></div>
        <div id="label">Address:<span style="color:#112233;">Shamoly, Adabar.<br />840-841 Baitul Aman Tower.<br />Dhaka </span></div>
		<div id="label">Phone:<span style="color:#112233;">9000000<br /><br /> </span></div>
        <div class="clear"></div>
      </div>
    </div>
<?php
include(DESK."footer.php");
	$_SESSION['success_scroll_no'] = '';
	$success_msg = '';
	//include("footer.php");
?>
