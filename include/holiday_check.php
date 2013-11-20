<?php
/*
*	@authore: Sonnet
*	Company: CACTS LTD.
*	URL: http://cactsgroup.com
*	Date: 2012-Jul-18
*	**********************************
*	Source: DOM_BILL_CHECK*
*/

if($_SESSION['username']==''||$_SESSION['password']==''){
	header("location:".BASEPATH."index.php");
	}
else if($_SESSION['username']!='' && $_SESSION['password']!='' && $_SESSION['pbs_user_is_loged_in'] == 'loged')
{
	$sysDateLpcSql = "Select to_char(sysdate, 'dd-Mon-YYYY') FROM dual";
	$sysDateLpcEx = oci_parse($c1, $sysDateLpcSql);
	oci_execute($sysDateLpcEx, OCI_DEFAULT);
	//echo 'holiday_check-line:17';exit;
	while ($sysDateLpcRow = oci_fetch_row($sysDateLpcEx)) {
	  foreach($sysDateLpcRow as $sysDateLpcItem) {
		 $sysDateLpc= $sysDateLpcItem;
		}
	}
	
	$domHolidayCheckSql ="SELECT ISSUE_DATE,DUE_DATE,PENDING_PROCESS,DISC_DATE
					FROM PMS.DOM_READ_Z1
      				WHERE  BOOK_NOX   = $dom101bookNo  
					AND ACNOX  = $dom101acNo
					AND BILLNO = $billNumber
					AND BILL_YEAR = $dom101billYear
					AND BILL_MONTH = $dom101billMonth";
	$domHolidayCheckEx = oci_parse($c1, $domHolidayCheckSql);
	oci_execute($domHolidayCheckEx,OCI_DEFAULT);
	while ($domHolidayCheckRow = oci_fetch_array($domHolidayCheckEx, OCI_ASSOC+OCI_RETURN_NULLS)) {
		 $issueDateDb 	= $domHolidayCheckRow['ISSUE_DATE'];
		 $dueDateDb 	= $domHolidayCheckRow['DUE_DATE'];
		 $pendingProcss = $domHolidayCheckRow['PENDING_PROCESS'];
		 $discDate 		= $domHolidayCheckRow['DISC_DATE'];
	}
	//Type Converssion of Date
	if($issueDateDb == '' || $dueDateDb == '')
	{
		$_SESSION['msg'] = '<span id="bangla">এই বিলটি এখনও গ্রাহককে পাঠানো হয়নাই।</span><br />The Bill is not issued yet.]';
		header("$url");
		exit;
	}
	//$dueDate='23-Jul-12';
	$dueDate = strtotime($dueDateDb);
	$dueDate = date('d-M-Y',$dueDate);
	//echo $dueDateDb;
	$_SESSION['dueDateDb'] = $dueDateDb;
	//echo 'Due Date from Database: '.$dueDate;
	//echo '<br />';
	
	//exit;
	
	$holiday = '';

	do{
		if($holiDay !=''){
			$dueDate = strtotime($dueDate);
			$dueDate = strtotime('+1 day', $dueDate);
			$dueDate = date('d-M-Y',$dueDate);
			$holidayCheckSql ="select to_char(H_DATE,'DD-Mon-YYYY') as HO_DATE from PMS.holiday_z1 where H_DATE = '$dueDate'";
			
		}
		else {
			$holidayCheckSql ="select to_char(H_DATE,'DD-Mon-YYYY') as HO_DATE from PMS.holiday_z1 where H_DATE = '$dueDate'";
		}
		//Test SQL syntex 
		//echo $holidayCheckSql.'<br />';
		
		$holidayCheckEx = oci_parse($c1, $holidayCheckSql);
		oci_execute($holidayCheckEx,OCI_DEFAULT);
		while ($holidayCheckRow = oci_fetch_array($holidayCheckEx, OCI_ASSOC+OCI_RETURN_NULLS)) {
			 $holiDay = $holidayCheckRow['HO_DATE'];
			 //Test Data
			 //echo '<br />Holiday: '.$holiDay.' <br />Due Date'.$dueDate.'<br />';
		}
	}while($dueDate == $holiDay);
	
	//Test Due Date after Holiday Check
	
	//echo 'Due Date with Holiday: '.$dueDate.'<br />';
	
	//Convert Date to dd-Mon-YYYY
	$sysDateLpcToTime = strtotime($sysDateLpc);
		
	
	//Test The final Due Date after Holiday
	/*
	$sysDateLpc = date('d-M-Y',$sysDateLpcToTime);
	echo 'Sysdate: '.$sysDateLpc.'<br />';
	echo 'Final Due Date: '.$dueDate.'<br />';
	*/
	
	//Convert Due Date to YYYY-Mon-dd
	$dueDateToTime = strtotime($dueDate);
	$dueDateY_M_d = date('Y-M-d',$dueDateToTime);
	$dueDateMM_DD_YYYY = date('M/d/Y',$dueDateToTime);
	
	//Convert System Date to YYYY-Mon-dd
	$sysDateLpcToTimeY_M_d = date('Y-M-d',$sysDateLpcToTime);
	
	//Check System Date and Final Due Date Converted
	
	//echo 'Sysdate: '.$sysDateLpcToTimeY_M_d.'<br />';
	//echo 'Final Due Date: '.$dueDateY_M_d.'<br />';
	
	//-----------------------Global Variable---------------------
	$_SESSION['dueDateY_M_d'] = $dueDateY_M_d;
	$_SESSION['dueDateMM_DD_YYYY'] = $dueDateMM_DD_YYYY;
	
		
	//=============== Check Due date is passed or not ========================
	if(strtotime($sysDateLpcToTimeY_M_d) > strtotime($dueDateY_M_d)){
		//echo "pay with lpc";
		include(INC."pay_with_lpc.php");
	}
	elseif(strtotime($sysDateLpcToTimeY_M_d) <= strtotime($dueDateY_M_d)){
		//echo "pay without lpc";
		include(INC."pay_without_lpc.php");
	}
}
?>
