<?php
/*
*	@authore: Sonnet
*	Company: CACTS LTD.
*	URL: http://cactsgroup.com
*	Start Date: 2012-Aug-06
*	Last Update: 2012-Aug-12
*	**********************************
*	Source: FROM->BILL_COLLECTION.fmb->Bill_number Textbox.
*/

if($_SESSION['username']==''||$_SESSION['password']==''){
	header("location:".URL."index.php");
	}
else if($_SESSION['username']!='' && $_SESSION['password']!='' && $_SESSION['pbs_user_is_loged_in'] == 'loged')
{//Start ------------01
		
		$success_url = 'location:'.URL.$device.'/'.$device.'_main.php';
		$un_success_url = 'location:'.URL.$device.'/'.$device.'_bill_pay_view.php';
		//echo $success_url;exit;
		//$dbUser = $_SESSION['username'];
		//$dbPass = $_SESSION['password'];
		//include(INC."db_conn.php");
		//include(INC."functions.php");
		//$pbs = new PbsCal;
		//exit;
		
		//============== Unset Previous Value =============== 2012121656
		unset($payDateF);
		unset($collPointF);
		unset($postedYnF);
		//-------------------------------------
		//============= Checking Bill Collected or not.
		$billNumber 		= $_SESSION['billNumber'];
		$billCollSql = "select PAYDATE,COLL_POINT,POSTED_YN from PMS.bill_collection where billno = '$billNumber'";
		//echo $billCollSql;exit;
		$billCollPrs = oci_parse($c1, $billCollSql);
		oci_execute($billCollPrs,OCI_DEFAULT);
		while ($billCollrow = oci_fetch_array($billCollPrs, OCI_ASSOC+OCI_RETURN_NULLS)) {
			 $payDateF = $billCollrow['PAYDATE'];
			 $collPointF = $billCollrow['COLL_POINT'];
			 $postedYnF = $billCollrow['POSTED_YN'];
			 //echo $payDate;
		}
		if(isset($payDateF))
		{
			$_SESSION['msg']="You Have Just Collected the Bill,<br />
								Collection Point: $collPointF, <br />
								Collection Date: $payDateF";
			header($un_success_url);
			exit;
		}
		//------------ Unset Previous Value ------------ 2012121656
		//echo 'Test';exit;
		$c_u_collPoint = $pbs->collPoint($dbUser);
		//echo $c_u_collPoint;exit;	
		$paydate = $pbs->oraSysDate('DD-Mon-YYYY');
		$scrollNo = $pbs->scroll_no($dbUser);
		$agentComm = $pbs->agentComm($dbUser);
		$softComm = $pbs->softComm($dbUser);
		
		//------------Global Variables---------------------
		$billNumber 		= $_SESSION['billNumber'];
		$billAmt 			= $_SESSION['billAmt'];
		//-------------------------------------------------
		$g_Netamt 			= $_SESSION['g_Netamt'];
		$g_AnetAmt 			= $_SESSION['g_AnetAmt'];
		$g_aVatAmt 			= $_SESSION['g_aVatAmt'];
		$g_ALpcAmt 			= $_SESSION['g_ALpcAmt'];
		$g_ApaneltyAmt 		= $_SESSION['g_ApaneltyAmt'];
		$g_AmistAmt 		= $_SESSION['g_AmistAmt'];
		$g_AmeterRent 		= $_SESSION['g_AmeterRent'];
		$g_AdjTyp			= $_SESSION['g_AdjTyp'];
		//--------------------------------------------------
		$dom101bookNo		= $_SESSION['dom101bookNo'];
		$dom101acNo 		= $_SESSION['dom101acNo'];
		$dom101billMonth	= $_SESSION['dom101billMonth'];
		$dom101billYear		= $_SESSION['dom101billYear'];
		$latePayment		= $_SESSION['latePayment'];
		$g_BillAmt			= $_SESSION['g_BillAmt'];
		$officeCode 		= $_SESSION['officeCode'];
		$geoCode 			= $_SESSION['geoCode'];
		
		$z_code = substr($officeCode,5,5);
		//echo $z_code;exit;
		$pbs_code = $pbs->pbsCode($dom101bookNo,$dom101acNo);
		$dom101cCodex = $_SESSION['dom101cCodex'];
		$bankCode = $pbs->bankCode($dbUser);
		
		$dom101bill_YYYYMM 	= $_SESSION['dom101bill_YYYYMM'];
		$dueDateMM_DD_YYYY	= $_SESSION['dueDateMM_DD_YYYY'];
		$g_PayAmount 		= $_SESSION['g_PayAmount'];
		$g_Vatamt 			= $_SESSION['g_Vatamt'];
		//echo $g_Vatamt;exit;
		$g_Lpcamt 			= $_SESSION['g_Lpcamt'];
		$g_Total 			= $_SESSION['g_Total'];
		$dom101dmcmBillno 	= $_SESSION['dom101dmcmBillno'];
		$dom101arrMonth 	= $_SESSION['dom101arrMonth'];
		$dom101arrYear 		= $_SESSION['dom101arrYear'];
		$dcRcFee 			= $_SESSION['dcRcFee'];
		$dup_bill_fee 		= $_SESSION['dup_bill_fee'];
		$g_aVatAmt 			= $_SESSION['g_aVatAmt'];
		$g_ALpcAmt 			= $_SESSION['g_ALpcAmt'];
		$g_ApaneltyAmt 		= $_SESSION['g_ApaneltyAmt'];
		$g_AmistAmt 		= $_SESSION['g_AmistAmt'];
		$g_AmeterRent 		= $_SESSION['g_AmeterRent'];
		$g_overPay 			= $_SESSION['g_overPay'];
		$g_MeterRent 		= $_SESSION['g_MeterRent'];
		$g_OtherAmt 		= $_SESSION['g_OtherAmt'];
		$g_MistAmt 			= $_SESSION['g_MistAmt'];
		$agentId 			= $_SESSION['agentId'];
		$dueDateDb 			= $_SESSION['dueDateDb'];

		//---------Constant Data------------------------
		$pay_mode = 'Cash';
		
		
		//-------------Collumn Not Used-----------------
		$null = '';
		$zero = 0;
		$upd_by = $null;
		$upd_dat = $null;
		$bundle_no = $null;
		$cheque_no = $null;
		$cheque_date = $null;
		$c_acno = $null;
		$tr_rent = $zero;
		$arr_tr_rent = $null;
		$arr_tloss_chg = $null;
		$arr_irri_lpc = $null;
		$arr_pf_diff = $zero;
		$arr_pf_chg = $zero;
		$orig_billno = $null;
		$amt_reduce_yn = 'N';
		$coll_for_dis_chq = 'N';
		$jm_for_chq = $null;
		$high_auth = $null;
		$posted_yn = 'U';
		$process_date = $null;
		$cash_in_hand_yn = $null;
		$oc = $null;
		$entry_date = $null;
		$pay_slip_yn = $null;
		
		if($g_overPay == 'Y')
		{
			$orig_billno = $billNumber ;
			$billNumber = $billNumber.'-1';
		}

	//--------------Test Variable Values----------------
	$bill_pay_insert = $_SESSION['bill_pay_insert'];
	if ($bill_pay_insert == 'Y')
	{		
	echo '<br /><br />Veriables in bil_pay_insert.php Script';
	echo '<br />--------------------------------------------------------------------------';
			echo '<br />billNumber: '.$billNumber;
			echo '<br />Pay Date : '.$paydate;
			echo '<br />dom101acNo: '.$dom101acNo;
			echo '<br />dom101bookNo: '.$dom101bookNo;
			echo '<br />c_acno: '.$c_acno;
			echo '<br />dom101billMonth: '.$dom101billMonth;
			echo '<br />dom101billYear: '.$dom101billYear;
			echo '<br />latePayment: '.$latePayment;
			echo '<br />g_PayAmount: '.$g_PayAmount;
			echo '<br />Collection Point: '.$c_u_collPoint;
			echo '<br />pay_mode: '.$pay_mode;
			echo '<br />dbUser : '.$dbUser;
			echo '<br />paydate: '.$paydate;
			echo '<br />upd_by : '.$upd_by;
			echo '<br />upd_dat: '.$upd_dat;
			echo '<br />z_code: '.$z_code;
			echo '<br />pbs_code: '.$pbs_code;
			echo '<br />dom101cCodex: '.$dom101cCodex;
			echo '<br />bankCode: '.$bankCode;
			echo '<br />dom101bill_YYYYMM: '.$dom101bill_YYYYMM;
			echo '<br />bundle_no: '.$bundle_no; 
			echo '<br />cheque_no: '.$cheque_no;
			echo '<br />cheque_date: '.$cheque_date;
			echo '<br />dueDateDb: '.$dueDateDb;
			echo '<br />g_Total: '.$g_Total;
			echo '<br />g_Vatamt: '.$g_Vatamt;
			echo '<br />g_Lpcamt: '.$g_Lpcamt;
			echo '<br />g_OtherAmt : '.$g_OtherAmt;
			echo '<br />g_AnetAmt : '.$g_AnetAmt;
			echo '<br />g_AdjTyp: '.$g_AdjTyp;
			echo '<br />dom101dmcmBillno: '.$dom101dmcmBillno;
			echo '<br />dom101arrMonth: '.$dom101arrMonth; 
			echo '<br />dom101arrYear: '.$dom101arrYear;
			echo '<br />dcRcFee: '.$dcRcFee;
			echo '<br />dup_bill_fee: '.$dup_bill_fee;
			echo '<br />g_aVatAmt: '.$g_aVatAmt;
			echo '<br />g_ALpcAmt: '.$g_ALpcAmt;
			echo '<br />arr_irri_lpc: '.$zero;
			echo '<br />arr_tr_rent: '.$zero;
			echo '<br />g_ApaneltyAmt: '.$g_ApaneltyAmt;
			echo '<br />g_AmistAmt: '.$g_AmistAmt;
			echo '<br />g_AmeterRent: '.$g_AmeterRent;
			echo '<br />arr_tloss_chg: '.$null;
			echo '<br />arr_pf_diff: '.$null;
			echo '<br />arr_pf_chg: '.$zero;
			echo '<br />g_overPay: '.$g_overPay;
			echo '<br />orig_billno: '.$null;
			echo '<br />amt_reduce_yn: '.$amt_reduce_yn;
			echo '<br />g_MeterRent: '.$g_MeterRent;
			echo '<br />g_Panalty: '.$g_Panalty;
			echo '<br />g_MistAmt: '.$g_MistAmt;
			echo '<br />tr_rent: '.$tr_rent;
			echo '<br />coll_for_dis_chq: '.$coll_for_dis_chq;
			echo '<br />jm_for_chq: '.$jm_for_chq;
			echo '<br />high_auth: '.$high_auth;
			echo '<br />posted_yn: '.$posted_yn;
			echo '<br />geoCode: '.$geoCode;
			echo '<br />officeCode: '.$officeCode;
			echo '<br />agentId: '.$agentId;
			echo '<br />process_date: '.$process_date;
			echo '<br />dom101bill_YYYYMM: '.$cash_in_hand_yn;
			echo '<br />scrollNo: '.$scrollNo;
			echo '<br />softComm: '.$softComm;
			echo '<br />agentComm: '.$agentComm;
			echo '<br />oc: '.$oc;
			echo '<br />entry_date: '.$entry_date;
			echo '<br />pay_slip_yn: '.$pay_slip_yn;
			
			echo '<br />--------------------------------------------------------------------------';exit;
			echo '<br /><br />Bill Pay Insert';
			
		}
		$insertSql = "INSERT INTO PMS.BILL_COLLECTION
							(BILLNO,PAYDATE,ACNO,BOOK_NO,C_ACNO,BILL_MONTH,BILL_YEAR,LATE_PAYMENT,PAYAMT,
							COLL_POINT,PAY_MODE,USR,SYSDAT,UPD_BY,UPD_DAT,Z_CODE,PBS_CODE,C_CODE,BANK_CODE,YYYYMM,
							BUNDLE_NO,CHEQUE_NO,CHEQUE_DATE,DUE_DATE,NETAMT,VATAMT,LPCAMT,OTHAMT,ARR_NET_AMT,ADJ_TYP,
							DMCM_BILLNO,ARR_MONTH,ARR_YEAR,DC_RC_FEE,DUP_BILL_FEE,ARR_VAT,ARR_LPC,ARR_IRRI_LPC,
							ARR_TR_RENT,ARR_PENALTY,ARR_MISC,ARR_MRENT,ARR_TLOSS_CHG,ARR_PF_DIFF,ARR_PF_CHG,OVER_PAY,
							ORIG_BILLNO,AMT_REDUCE_YN,METER_RENT,PENALTY,MISCAMT,TR_RENT,COLL_FOR_DIS_CHQ,JM_FOR_CHQ,HIGH_AUTH,
							POSTED_YN,GEO_CODE,OFFICE_CODE,AGENT_ID,PROCESS_DATE,CASH_IN_HAND_YN,SCROLL_NO,SOFT_SUPP_COMM,AGENT_COMM,
							OC,ENTRY_DATE,PAY_SLIP_YN)
						VALUES
							('$billNumber','$paydate','$dom101acNo','$dom101bookNo','$c_acno','$dom101billMonth','$dom101billYear','$latePayment','$g_BillAmt',
							'$c_u_collPoint','$pay_mode','$dbUser','$paydate','$upd_by','$upd_dat','$z_code','$pbs_code','$dom101cCodex','$bankCode','$dom101bill_YYYYMM',
							'$bundle_no','$cheque_no','$cheque_date','$dueDateDb','$g_Total','$g_Vatamt','$g_Lpcamt','$g_OtherAmt','$g_AnetAmt','$g_AdjTyp',
							'$dom101dmcmBillno','$dom101arrMonth','$dom101arrYear','$dcRcFee','$dup_bill_fee','$g_aVatAmt','$g_ALpcAmt','$arr_irri_lpc',
							'$arr_tr_rent','$g_ApaneltyAmt','$g_AmistAmt','$g_AmeterRent','$arr_tloss_chg','$arr_pf_diff','$arr_pf_chg','$g_overPay',
							'$orig_billno','$amt_reduce_yn','$g_MeterRent','$g_Panalty','$g_MistAmt','$tr_rent','$coll_for_dis_chq','$jm_for_chq','$high_auth',
							'$posted_yn','$geoCode','$officeCode','$agentId','$process_date','$cash_in_hand_yn','$scrollNo','$softComm','$agentComm','$oc',
							'$entry_date','$pay_slip_yn')" ;  
		//echo $insertSql;exit;
		$insertPrs = oci_parse($c1, $insertSql);
		$insertEx = oci_execute($insertPrs,OCI_DEFAULT);
		if($insertEx)
		{
			//oci_commit($c1); //*** Commit Transaction ***//
			$_SESSION['success_msg_ins_101'] = '1';
		}
		else
		{
			//oci_rollback($c1); //*** RollBack Transaction ***//
			$_SESSION['success_msg_ins_101'] = '0';
		}
		
		//======= Source: PRE-INSERT =================
		
		$dom101UpdSql = "update PMS.dom_read_z1 set BILL_COLLECT = 'Y' 
             				WHERE book_nox = '$dom101bookNo' 
							  and acnox = '$dom101acNo' 
							  and billno = '$billNumber'
							  and bill_month = '$dom101billMonth' 
							  and bill_year = '$dom101billYear'" ; 
							  //echo $dom101UpdSql; 
		$dom101UpdPrs = oci_parse($c1, $dom101UpdSql);
		$dom101UpdEx = oci_execute($dom101UpdPrs,OCI_DEFAULT);
		if($dom101UpdEx)
		{
			$oraSuccess = '1';
			//oci_commit($c1); //*** Commit Transaction ***//
		}
		else
		{
			$oraSuccess = '0';
			//oci_rollback($c1); //*** RollBack Transaction ***//
		}
		
		if($insertEx && $dom101UpdEx){
			$success_scroll_no = "$geoCode-$paydate-$scrollNo";					
			$_SESSION['success_scroll_no'] = $success_scroll_no;
			//header($success_url);
		}
		else
		{
			$_SESSION['success_scroll_no'] = 'Your Server Responding too Slow';
			//header($success_url);
		}
		//oci_close($c1);
		
//	echo '<br /><br />Variable Used in pending_proces101.php';
//	echo '<br />--------------------------------------------------------------------------';
//			echo '<br />pen101_V_C_CODEX : '.$pen101_V_C_CODEX;
//			echo '<br />pen101_V_ENERGY_CHG : '.$pen101_V_ENERGY_CHG;
//			echo '<br />pen101_V_MINIAMT : '.$pen101_V_MINIAMT;
//			echo '<br />pen101_V_SCHG : '.$pen101_V_SCHG;
//			echo '<br />pen101_V_DCHG : '.$pen101_V_DCHG;
//			echo '<br />pen101_V_TOT_KWREAD : '.$pen101_V_TOT_KWREAD;
//			echo '<br />pen101_V_VATAMT : '.$pen101_V_VATAMT;
//			echo '<br />pen101_V_LPCAMT : '.$pen101_V_LPCAMT;
//			echo '<br />pen101_V_IRR_LPC : '.$pen101_V_IRR_LPC;
//			echo '<br />pen101_V_DUE_OR_COLL_DATE : '.$pen101_V_DUE_OR_COLL_DATE;
//			echo '<br />pen101_V_DISC_DATE : '.$pen101_V_DISC_DATE;
//			echo '<br />pen101_V_BTYPE: '.$pen101_V_BTYPE;
//			echo '<br />pen101_V_PENALTY_AMT : '.$pen101_V_PENALTY_AMT;
//			echo '<br />pen101_V_MISC_AMT : '.$pen101_V_MISC_AMT;
//			echo '<br />pen101_V_METER_RENT : '.$pen101_V_METER_RENT;
//			echo '<br />pen101_V_INST_PAY_NETTOT : '.$pen101_V_INST_PAY_NETTOT;
//			echo '<br />pen101_V_INST_PAY_VATTOT : '.$pen101_V_INST_PAY_VATTOT;
//			echo '<br />pen101_V_INST_PAY_LPCTOT : '.$pen101_V_INST_PAY_LPCTOT;
//			echo '<br />pen101_V_INST_PAY_PENTOT : '.$pen101_V_INST_PAY_PENTOT;
//			echo '<br />pen101_V_INST_PAY_MISTOT : '.$pen101_V_INST_PAY_MISTOT;
//			echo '<br />pen101_V_INST_PAY_METTOT : '.$pen101_V_INST_PAY_METTOT;
//			echo '<br />pen101_V_REBATE : '.$pen101_V_REBATE;		
			
}
else{
	$_SESSION['msg']="Login User/Password Incorrect";
		header("location:".URL."index.php");
}

?>
