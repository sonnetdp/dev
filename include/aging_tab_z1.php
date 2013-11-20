<?php
/*
*	@authore: Sonnet
*	Company: CACTS LTD.
*	URL: http://cactsgroup.com
*	Date: 2012-Jul-18
*	**********************************
*	Source: DOM_BILL_CHECK*
*/
if($secureAging == 'myWonSecurity')
{
	$agingCheckSql ="SELECT SYSDAT from PMS.AGING_TAB_Z1 WHERE AGING_YYYYMM=to_number(to_char(SYSDATE,'yyyymm')) and BOOK_NO =  '$dom101bookNo' AND C_CODE = '$dom101cCodex'";
	$agingCheckEx = oci_parse($c1, $agingCheckSql);
	oci_execute($agingCheckEx,OCI_DEFAULT);
	while ($agingCheckRow = oci_fetch_array($agingCheckEx, OCI_ASSOC+OCI_RETURN_NULLS)) {
		 $agingCheckSysDate = $agingCheckRow['SYSDAT'];
		 //echo $agingCheckSysDate; exit;
	}
	//echo 'aging_tab_z1-line:18';exit;
	if($agingCheckSysDate){
		//echo 'aging_tab_z1-line:20';exit;
		$_SESSION['msg'] = '<span id="bangla">এই মাসের এজিং সম্পন্ন হয়েছে,<br /> আপনি এই বিলটি আগামী মাসে গ্রহন করতে পারবেন।</span><br />Month Aging is Complete.<br />
			 				You can Collect the bill next month.';
		header($url);
		exit;
	}
	else{
		include(INC."holiday_check.php");
	}
}
else
{
	header("location:".URL."index.php");
}
?>
