<?php
/*
*	@authore: Kazi Sanghati
*	Company: OSS Japan Bangladesh Ltd.
*	URL: http://ossjb.com
*	Date: 2012-Jul-25
*	**********************************
*
*/
session_start();

if($_SESSION['username']==''||$_SESSION['password']==''){
	header("location: index.php");
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
	//======= Session Time =======
	$_SESSION['timeout'] = time();

	//$success_url = 'location:'.URL.$device.'/'.$device.'_main.php';
	$un_success_url = 'location:'.URL.$device.'/'.$device.'_bill_pay_view.php';
	
	$userName = $pbs->current_user_name($dbUser);	
	$balance = $pbs->current_user_balance($dbUser);
		
	$billNumber 		= $_SESSION['billNumber'];
	$billAmt 			= $_SESSION['billAmt'];
	$dom101bookNo		= $_SESSION['dom101bookNo'];
	$dom101acNo 		= $_SESSION['dom101acNo'];
	if(!$billNumber)
	{
		$_SESSION['msg']="Please Insert a Bill Number";
		header($un_success_url);
		exit;
	}
	else
	{
		//============== Unset Previous Value ===============
		unset($payDateF);
		unset($collPointF);
		unset($postedYnF);
		//-------------------------------------
		//Checking Bill Collected or not.
		$billCollSql = oci_parse($c1, "select PAYDATE,COLL_POINT,POSTED_YN from PMS.bill_collection where billno = '$billNumber'");
		oci_execute($billCollSql,OCI_DEFAULT);
		while ($billCollrow = oci_fetch_array($billCollSql, OCI_ASSOC+OCI_RETURN_NULLS)) {
			 $payDateF = $billCollrow['PAYDATE'];
			 $collPointF = $billCollrow['COLL_POINT'];
			 $postedYnF = $billCollrow['POSTED_YN'];
			 //echo $payDate;
		}
		if(isset($payDateF) && isset($collPointF) && isset($postedYnF))
		{
			$_SESSION['msg']="You Have Just Collected the Bill,<br />
									Collection Point: $collPointF, <br />
									Collection Date: $payDateF";
			header($un_success_url);
			exit;
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content = "width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" name = "viewport" />
<title>Mobile Interface | Login</title>
<link href="style/main.css" rel="stylesheet" type="text/css"/>
<style type="text/css">

</style>
</head>

<body>

<div id="main">
	<div id="logo"><a href="/"><img src="images/cacts-logo.gif" width="80" height="39" alt="logo" border="0" style="padding-bottom:5px;"/></a>
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
           		<li><a href="bill_pay_yes.php" title="Pay">Yes</a></li>
                <li><a href="pc_bill_pay_view.php" title="Not Pay">No</a></li>
				<!--li><a href="#" title="Process">Process</a></li-->
				<li><a href="<?php echo URL; ?>logout.php" title="Log out">Log out</a></li>
				</ul>
             </div>  
			            
			</div>
			<?php include("footer.php");?>

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