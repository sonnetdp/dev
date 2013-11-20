<?php
/*
*	@authore: Kazi Sanghati
*	Company: OSS Japan Bangladesh Ltd.
*	URL: http://ossjb.com
*	Date: 2012-Jul-29
*	**********************************
*
*/
session_start();
include('../config_pms.php');
if($_SESSION['username']==''||$_SESSION['password']==''){
	header("location:".URL."index.php");
	}
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
	$userName = $pbs->current_user_name($dbUser);	
	$balance = $pbs->current_user_balance($dbUser);
		
	$billNumber 		= $_SESSION['billNumber'];
	$billAmt 			= $_SESSION['billAmt'];
	$dom101bookNo		= $_SESSION['dom101bookNo'];
	$dom101acNo 		= $_SESSION['dom101acNo'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content = "width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" name = "viewport" />
<title>Mobile Interface | Login</title>
<link href="<?php echo URL;?>style/main.css" rel="stylesheet" type="text/css"/>
<style type="text/css">

</style>
</head>

<body>

<div id="main">
	<div id="logo"><a href="/"><img src="<?php echo URL;?>images/cacts-logo.gif" width="80" height="39" alt="logo" border="0" style="padding-bottom:5px;"/></a>
    </div>				
		<div style="padding:15px; background-color:#f2f2f2;-moz-border-radius:6px; -webkit-border-radius: 6px;">
			<div id="label">Retailer Name: <span style="color:red;"><?php echo $userName;?></span></div>
			<div id="label" style="margin-top:5px;">Current Balance:<span style="color:red;"> <?php echo $balance." "; ?></span>Taka</div>
			<div id="label">Bill Number: <span style="color:#060;"><?php echo $billNumber;?></span></div>
			<div id="label" style="margin-top:5px;">Bill Amount: <span style="color:#060;"> <?php echo $billAmt." "; ?></span>Taka</div>
        	<div id="label">Book Number: <span style="color:#060;"><?php echo $dom101bookNo;?></span></div>
			<div id="label" style="margin-top:5px;">Account Number: <span style="color:#060;"> <?php echo $dom101acNo; ?></span></div>
        	<div id="label" style="margin-top:5px;">Do You Want To Pay The Bill? </div>
            <div class="clear"></div>
        	<div> <ul id="nav">
           		<li><a href="<?php echo URL;?>bill_pay_yes.php" title="Pay">Yes</a></li>
                <li><a href="<?php echo URLMOB;?>mobile_bill_pay_view.php" title="Not Pay">No</a></li>
				<!--li><a href="#" title="Process">Process</a></li-->
				<li><a href="<?php echo URL;?>logout.php" title="Log out">Log out</a></li>
				</ul>
             </div>  
			            
			</div>
			<?php include("mobile_footer.php");?>

</div>

</body>
</html>
<?php
}
else{
	$_SESSION['msg']="Login User/Password Incorrect";
		header("location:".URL."index.php");
}
?>