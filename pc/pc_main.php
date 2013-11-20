<?php
/*
*	@authore: Kazi Sanghati
*	Company: OSS Japan Bangladesh Ltd.
*	URL: http://ossjb.com
*	Date: 2012-Sep-12
*	**********************************
*
*/
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
	$success_scroll_no = $_SESSION['success_scroll_no'];
	if($success_scroll_no !='')
	{
		$success_msg = '<div id="label">Bill Collection Successful <br /> 
						Please Write Down The Scroll Number.<br /> 
						Scroll no: <span style="color:red;">'.$success_scroll_no.'</span></div>';
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
	$_SESSION['userName'] = $userName;
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
	$balance = $pbs->balanceOfAgent($agentId,$geoCode);

	//-------------------------------: End of Current User Balance --------------------------------------------
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
        <?php 
			echo $success_msg; 
			unset($_SESSION['success_scroll_no']);
			unset($success_msg);
			
			$msg = $_SESSION['msg'];
            if(isset($msg))
			{
            	echo '<div id="label_msg"><font color="#FF0000">'.$msg.'</font></div>';
                unset($_SESSION['msg']);
                unset($msg);
            }
		?>
        <div id="label">Retailer Name: <span style="color:red;"><?php echo $userName;?></span></div>
        <div id="label">Current Balance:<span style="color:red;"> <?php echo $balance." Taka"; ?></span></div>
        <div class="clear"></div>
      </div>
    </div>
<?php
include(DESK."footer.php");
	$_SESSION['success_scroll_no'] = '';
	$success_msg = '';
	//include("footer.php");
?>
