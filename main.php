<?php
/*
*	@authore: Kazi Sanghati
*	Company: OSS Japan Bangladesh Ltd.
*	URL: http://ossjb.com
*	Date: 2012-May-20
*	**********************************
*/
session_start();
include("test_script.php");
if($_SESSION['username']==''||$_SESSION['password']==''){//--------------Start if 001
	header("location:../index.php");
}//End of 001


else if($_SESSION['username']!='' && $_SESSION['password']!='' && $_SESSION['pbs_user_is_loged_in'] == 'loged')
{
	$dbUser = $_SESSION['username'];
	$dbPass = $_SESSION['password'];
	include("include/db_conn.php");
	include("include/functions.php");
	$pbs = new PbsCal;
	if(!$c1){
		echo "Could not connect";
		exit;
	}
	$success_scroll_no = $_SESSION['success_scroll_no'];
	if($success_scroll_no !='')
	{
		$success_msg = '<div id="label">Bill Collection Successful <br /> Please Write Down The Scroll Number.<br /> Scroll no: <span style="color:red;">'.$success_scroll_no.'</span></div>';
	}
	else
	{
		$success_msg = '';
	}
	
	//============================== Geo Code of Current User =================================
	$geoCodeSql = oci_parse($c1, "SELECT GEO_CODE,USRNM FROM PMS.S_USER_AUTH_MASTER WHERE USER_ID = '$dbUser'");
	oci_execute($geoCodeSql,OCI_DEFAULT);
	while ($thisrow = oci_fetch_array($geoCodeSql, OCI_ASSOC+OCI_RETURN_NULLS)) {
		 $geoCode = $thisrow['GEO_CODE'];
		 $userName = $thisrow['USRNM'];
	   }
	$_SESSION['geoCode'] = $geoCode;
	//echo $geoCode; exit;
	//-------------------------------- End of Geo Code ---------------------------------------
	
	//================================ Agent ID of loged in Agent ============================
	$agentIdSql = oci_parse($c1, "SELECT DISTINCT AGENT_ID FROM PMS.S_USER_AUTH_MASTER WHERE USER_ID = '$dbUser'");
	oci_execute($agentIdSql,OCI_DEFAULT);
	while ($thisrow1 = oci_fetch_array($agentIdSql, OCI_ASSOC+OCI_RETURN_NULLS)) {
		 $agentId = $thisrow1['AGENT_ID'];
		 //$userName = $thisrow['USRNM'];
	   }
	$_SESSION['agentId'] = $agentId;
	
	//-------------------------------- End of Agent ID ----------------------------------------
	
	//================================  Calculate the Current Agent Balance ========================
	$balanceSql = "SELECT (SUM(NVL(CRAMT,0))- SUM(NVL(DRAMT,0))) BAL FROM PMS.VW_AGENT_DEPOSITE_LEDGER WHERE AGENT_ID='$agentId' AND GEO_CODE='$geoCode'";
	//echo $balanceSql;exit;
	$balanceSqlParse = oci_parse($c1, $balanceSql);
	oci_execute($balanceSqlParse,OCI_DEFAULT);
	
	while ($thisrow2 = oci_fetch_array($balanceSqlParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
			$balance = $thisrow2['BAL'];
	   }
	//-------------------------------: End of Current User Balance --------------------------------------------
}
else{
	$_SESSION['msg']="Login User/Password Incorrect";
		header("location:../index.php");
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
        	<?php echo $success_msg;?>
			<div id="label">Agent Name: <span style="color:red;"><?php echo $userName;?></span></div>
            <div id="label">Agent ID: <span style="color:red;"><?php echo $agentId;?></span></div>
			<div id="label" style="margin-top:5px;">Current Balance:<span style="color:red;"> <?php echo $balance." Taka"; ?></span></div>
		
        	<div class="clear"></div>
        	<div> <ul id="nav">
           		<li><a href="bill_pay.php" title="Bill Pay">Bill Pay</a></li>
				<!--li><a href="bill_day_end_process.php" title="Process">Process</a></li-->
				<li><a href="logout.php" title="Log out">Log out</a></li>
				</ul>
             </div>  
			            
		</div>
	<?php 
	$_SESSION['success_scroll_no'] = '';
	$success_msg = '';
	include("footer.php");
	?>

</div>

</body>
</html>
