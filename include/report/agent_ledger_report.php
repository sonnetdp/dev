<?php
/*
*	@authore: Sonnet
*	Company: CACTS LTD.
*	URL: http://cactsgroup.com
*	Date: 2012-Nov-18
*	Last update: 2012-Nov-21
*	**********************************
*	Agent Ledger Report
*/

session_start();

if($_SESSION['username']==''||$_SESSION['password']==''){
	header("location:".BASEPATH."index.php");
}

else if($_SESSION['username']!='' && $_SESSION['password']!='' && $_SESSION['pbs_user_is_loged_in'] == 'loged')
{
	function agentLedgerReport($agentId,$officeCode)
		{
			$c1 = $GLOBALS['c1'];
			$pbs = new PbsCal;
			$sql = "SELECT Z1.AGENT_ID AGENT_ID, Z1.E_NAME E_NAME, Z1.E_F_NAME E_F_NAME, Z1.E_M_NAME E_M_NAME, Z1.E_PRE_ADDR, VWAG.OFFICE_CODE OFFICE_CODE, VWAG.GEO_CODE, VWAG.AGENT_ID, VWAG.DRAMT DRAMT, 
						VWAG.PAYDATE PAYDATE, VWAG.CRAMT CRAMT, Z1.E_ZELA E_ZELA, Z1.E_THANA E_THANA, Z1.E_UNION E_UNION, Z1.E_VILLAGE, Z1.PHONE_RES, Z1.PHONE_OFFICE PHONE_OFFICE, Z1.APPN, Z1.BALANCE_LIMIT BALANCE_LIMIT
						FROM PMS.AGENT_Z1 Z1, PMS.VW_AGENT_DEPOSITE_LEDGER VWAG
						WHERE Z1.AGENT_ID = VWAG.AGENT_ID
						AND Z1.AGENT_ID = $agentId
						AND Z1.OFFICE_CODE = $officeCode
						ORDER BY Z1.AGENT_ID, VWAG.PAYDATE, VWAG.CRAMT desc";
			$data = $pbs->result_array($sql);
			return $data;
		}
			
		
}
else{
	$_SESSION['msg']="Login User/Password Incorrect";
		header("location:".BASEPATH."index.php");
}
?>
