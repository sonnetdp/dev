<?php
/*
*	@authore: Sonnet
*	Company: CACTS LTD.
*	URL: http://cactsgroup.com
*	Date: 2012-Jul-18
*	Last Update: 2013-Jan-19 16:17
*	**********************************
*/
if($_SESSION['username']==''||$_SESSION['password']==''){
	header("location:".URL."index.php");
	}
else if($_SESSION['username']!='' && $_SESSION['password']!='' && $_SESSION['pbs_user_is_loged_in'] == 'loged')
{
	//================= Current Balance of Agent ======================
		$agentBalance = $pbs->balanceOfAgent($agentId,$geoCode);
		$agentBalanceLimit = $pbs->balanceLimitOfAgent($agentId,$geoCode);
		if($agentBalance == ''){
			$_SESSION['msg'] = 'Your Balance is nill.<br /> 
								Please Deposit to PBS.';
			header("location:".URL."bill_pay.php");
		}
		else
		{
			//==================== Deduct The Balance Limit Amount for Safty ===================
			$agentBalance = $agentBalance - $agentBalanceLimit;
			
			if($agentBalance < $payableBillAmt){
				$_SESSION['msg'] = 'This bill Amount is larger<br /> 
									then your current credit limit.<br /> 
									Please Deposit to PBS.';
				header("location:".URL."bill_pay.php");
			}
			else{
				$agentHasEnoughBalance = '1';
			}
		}
}
else{
	$_SESSION['msg']="Login User/Password Incorrect";
		header("location:".URL."index.php");
}
?>
