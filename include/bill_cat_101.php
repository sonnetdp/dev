<?php
/*
*	@authore: Sonnet
*	Company: CACTS LTD.
*	URL: http://cactsgroup.com
*	Date: 2012-Jul-18
*	Last Update: 2012-Nov-25
*	**********************************
*/
if($_SESSION['username']==''||$_SESSION['password']==''){
	header("location:".URL."index.php");
}

else if($_SESSION['username']!='' && $_SESSION['password']!='' && $_SESSION['pbs_user_is_loged_in'] == 'loged') //--User Authenticate------
{
	//echo bill_cat_101-line:16;exit;
	//--Variable Unset---
		 unset($dom101acNo);
		 unset($dom101bookNo);
		 unset($dom101billMonth);
		 unset($dom101billYear);
		 unset($dom101latePayment);
		 unset($dom101cCodex);
		 unset($dom101dueDate);
		 unset($dom101pendingProcess);
		 unset($dom101adjTyp);
		 unset($dom101dmcmBillno);
		 unset($dom101arrMonth);
		 unset($dom101arrYear);
		 unset($dom101issueDate);
		 unset($dom101officeCode);
		 unset($dom101bill_YYYYMM);
	
	
	$domBillCheckSql ="SELECT ACNOX,BOOK_NOX,BILL_MONTH,BILL_YEAR,LATE_PAYMENT,C_CODEX,DUE_DATE,PENDING_PROCESS,ADJ_TYP,DMCM_BILLNO,ARR_MONTH,ARR_YEAR,ISSUE_DATE,OFFICE_CODE,YYYYMM FROM PMS.DOM_READ_Z1 WHERE BILLNO= '$billNumber' AND C_CODEX in('B','CI','C')";
	//echo $domBillCheckSql;exit;
	$domBillCheckEx = oci_parse($c1, $domBillCheckSql);
	
	oci_execute($domBillCheckEx,OCI_DEFAULT);
	
	while ($domBillCheckRow = oci_fetch_array($domBillCheckEx, OCI_ASSOC+OCI_RETURN_NULLS)) {
		 $dom101acNo = $domBillCheckRow['ACNOX'];
		 $dom101bookNo = $domBillCheckRow['BOOK_NOX'];
		 $dom101billMonth = $domBillCheckRow['BILL_MONTH'];
		 $dom101billYear = $domBillCheckRow['BILL_YEAR'];
		 $dom101latePayment = $domBillCheckRow['LATE_PAYMENT'];
		 $dom101cCodex = $domBillCheckRow['C_CODEX'];
		 $dom101dueDate = $domBillCheckRow['DUE_DATE'];
		 $dom101pendingProcess = $domBillCheckRow['PENDING_PROCESS'];
		 $dom101adjTyp = $domBillCheckRow['ADJ_TYP'];
		 $dom101dmcmBillno = $domBillCheckRow['DMCM_BILLNO'];
		 $dom101arrMonth = $domBillCheckRow['ARR_MONTH'];
		 $dom101arrYear = $domBillCheckRow['ARR_YEAR'];
		 $dom101issueDate = $domBillCheckRow['ISSUE_DATE'];
		 $dom101officeCode = $domBillCheckRow['OFFICE_CODE'];
		 $dom101bill_YYYYMM = $domBillCheckRow['YYYYMM'];
		 
		 //Test Data 
		// 
//		 echo 'ACNOX: '.$dom101acNo.', BOOK_NOX: '.$dom101bookNo.', BILL_MONTH: '.$dom101billMonth
//		 .', BILL_YEAR: '.$dom101billYear.', LATE_PAYMENT: '.$dom101latePayment.', C_CODEX: '.$dom101cCodex
//		 .', DUE_DATE: '.$dom101dueDate.', PENDING_PROCESS: '.$dom101pendingProcess.', ADJ_TYP: '
//		 .$dom101adjTyp.', DMCM_BILLNO: '.$dom101dmcmBillno.', ARR_MONTH: '.$dom101arrMonth.', ARR_YEAR: '
//		 .$dom101arrYear.', ISSUE_DATE: '.$dom101issueDate.', OFFICE_CODE: '.$dom101officeCode.', YYYYMM: '.$dom101bill_YYYYMM.'<br />';
//		 
	}
	//-----------------------Global Variable---------------------
	$_SESSION['dom101acNo'] = $dom101acNo;
	$_SESSION['dom101bookNo'] = $dom101bookNo;
	$_SESSION['dom101billMonth'] = $dom101billMonth;
	$_SESSION['dom101billYear'] = $dom101billYear;
	$_SESSION['dom101latePayment'] = $dom101latePayment;
	$_SESSION['dom101cCodex'] = $dom101cCodex;
	$_SESSION['dom101dueDate'] = $dom101dueDate;
	$_SESSION['dom101pendingProcess'] = $dom101pendingProcess;
	$_SESSION['dom101adjTyp'] = $dom101adjTyp;
	$_SESSION['dom101dmcmBillno'] = $dom101dmcmBillno;
	$_SESSION['dom101arrMonth'] = $dom101arrMonth;
	$_SESSION['dom101arrYear'] = $dom101arrYear;
	$_SESSION['dom101issueDate'] = $dom101issueDate;
	$_SESSION['dom101officeCode'] = $dom101officeCode;
	$_SESSION['dom101bill_YYYYMM'] = $dom101bill_YYYYMM;
	
	//Test Code to check data
	
	//echo 'ACNOX: '.$dom101acNo.'<br /> BOOK_NOX: '.$dom101bookNo.'<br /> BILL_MONTH: '.$dom101billMonth
//		 .'<br /> BILL_YEAR: '.$dom101billYear.'<br /> LATE_PAYMENT: '.$dom101latePayment.'<br /> C_CODEX: '.$dom101cCodex
//		 .'<br /> DUE_DATE: '.$dom101dueDate.'<br /> PENDING_PROCESS: '.$dom101pendingProcess.'<br /> ADJ_TYP: '
//		 .$dom101adjTyp.'<br /> DMCM_BILLNO: '.$dom101dmcmBillno.'<br /> ARR_MONTH: '.$dom101arrMonth.'<br /> ARR_YEAR: '
//		 .$dom101arrYear.'<br /> ISSUE_DATE: '.$dom101issueDate.'<br /> OFFICE_CODE: '.$dom101officeCode.'<br />';
		
	//echo $dom101acNo; exit;
	if(isset($dom101acNo))
	{
		$secureAging = 'myWonSecurity';
		//echo 'bill_cat_101-line:94';exit;
		//---Search Z_CODE
		$z_codeSql ="SELECT Z_CODE FROM PMS.ACNO_Z1 WHERE BOOK_NO = '$dom101bookNo' AND ACNO = '$dom101acNo'";
		$z_codeEx = oci_parse($c1, $z_codeSql);
		oci_execute($z_codeEx,OCI_DEFAULT);
		while ($z_codeRow = oci_fetch_array($z_codeEx, OCI_ASSOC+OCI_RETURN_NULLS)) 
		{
			 $g_z_code = $z_codeRow['Z_CODE'];
			 $g_pbs_code = $z_codeRow['PBS_CODE'];
		}
	//====================== Set Global Variable =============================
	$_SESSION['g_z_code'] = $g_z_code;
	$_SESSION['g_pbs_code'] = $g_pbs_code;
		
	//echo $g_z_code; exit;
	//echo 'bill_cat_101-line-107';exit;
	include(INC.'aging_tab_z1.php');
	}
	else{
		$_SESSION['msg'] = 'Bill Number Not Found.<br /> 
							You can pay only for Bari(B) and <br />
							Charitable Institutions(CI) tariff bill.<br /> 
							Pay this bill in Bank or PBS office.';
		header("$url");
	}
	
//Returned with all varification up to LPC/NLPC	
	if(isset($g_BillAmt)){
		//echo "$g_BillAmt T-bill_cat101-0001010"; exit;
		if($g_BillAmt != $billAmt){
			$_SESSION['msg'] = "Bill Amount is Incorrect.";
			header("$url");
			exit;
		}
		else{
			$payableBillAmt = $g_BillAmt;
			include(INC."agent_acc_check.php");
			
			//========== Validation for Agent Balance =======
			if($agentHasEnoughBalance != '1')
			{
				$_SESSION['msg'] = 'This bill Amount is larger<br /> 
									then your current credit limit.<br /> 
									Please Deposit to PBS.';
				header("$url");
				exit;
			}
			//Test Code
			//echo '<br /><br />Bill Amount given : '.$payableBillAmt;
			//echo '<br />LPC Bill Amt from DB: '.$lpcBillAmt;
		}
	}
	else{
		$_SESSION['msg'] = 'You are not authorized to collect Commercial Bill (C)<br /> 
							You can collect only for Bari(B) and <br />
							Charitable Institutions(CI) tariff bill.<br /> 
							Pay this bill in Bank or PBS office.';
		header("$url");
	}
	
//echo '<br /><br />Variable Used in bill_cat_101.php';
//echo '<br />--------------------------------------------------------------------------';
//		echo '<br />Dom Acno : '.$dom101acNo;
//		echo '<br />Dom Book No.: '.$dom101bookNo;
//		echo '<br />Dom Bill Month : '.$dom101billMonth;
//		echo '<br />Dom Bill Year : '.$dom101billYear;
//		echo '<br />Dom Late Payment: '.$dom101latePayment;
//		echo '<br />Dom Codex : '.$dom101cCodex;
//		echo '<br />Dom Due Date : '.$dom101dueDate;
//		echo '<br />Dom Pending Process : '.$dom101pendingProcess; 
//		echo '<br />ADJ_TYPM : '.$dom101adjTyp;
//		echo '<br />Dom DMCM Bill No. : '.$dom101dmcmBillno;
//		echo '<br />Dom Arr Month : '.$dom101arrMonth;
//		echo '<br />Dom Arr Year : '.$dom101arrYear;
//		echo '<br />Dom Issue Date : '.$dom101issueDate;
//		echo '<br />Dom Office Code : '.$dom101officeCode;
			
}
else{
	$_SESSION['msg']="Login User/Password Incorrect";
		header("location:".URL."index.php");
}
?>
