<?php
/*
*	@authore: Sonnet
*	Company: CACTS LTD.
*	URL: http://cactsgroup.com
*	Date: 2012-Jul-18
*	Last Update: 2012-Aug-12
*	**********************************
*/

session_start();
if($_SESSION['username']==''||$_SESSION['password']==''){
	header("location:".URL."index.php");
	}
else if($_SESSION['username']!='' && $_SESSION['password']!='' && $_SESSION['pbs_user_is_loged_in'] == 'loged')
{//else 001
	$lpcBillDataSql= "SELECT  (LPCAMT + NETAMT + MINIAMT + SCHG + DCHG + NVL(PF_CHG,0) - REBATE + PENALTY + MISC_AMT + ADJAMT + VATAMT + NVL(METER_RENT,0)) PAYAMT,
					NETAMT,MINIAMT,SCHG,DCHG,NVL(PF_CHG,0) PF_CHG,REBATE, VATAMT, LPCAMT , A_NET_AMT, A_VAT_AMT, A_LPC_AMT, INSTALL_AMT, INSTALL_VAT, 
					INSTALL_LPC,(NETAMT + MINIAMT + SCHG + DCHG + NVL(PF_CHG,0) - REBATE) TOTAL,0 OTHAMT,nvl(ADJAMT,0) ADJAMT,
					  nvl(METER_RENT,0) METER_RNT,
                      nvl(A_PENALTY_AMT,0) A_PENALTY_AMT,
					  nvl(A_MISC_AMT,0) A_MISC_AMT,
					  nvl(A_METER_RENT,0) A_METER_RENT,
                      nvl(PENALTY,0) PENALTY,
					  nvl(MISC_AMT,0) MISC_AMT,
                      DMCM_BILLNO,
					  ADJ_TYP
                
               		FROM PMS.DOM_READ_Z1 
              		WHERE  book_nox = '$dom101bookNo'   
			  		AND ACNOx  = '$dom101acNo'
					AND billno = '$billNumber'
					AND bill_year = '$dom101billYear'
					AND  bill_month = '$dom101billMonth'";
	$lpcBillDataEx = oci_parse($c1, $lpcBillDataSql);
	oci_execute($lpcBillDataEx,OCI_DEFAULT);
	while ($lpcBillDataRow = oci_fetch_array($lpcBillDataEx, OCI_ASSOC+OCI_RETURN_NULLS)) {
		 $g_PayAmount 		= $lpcBillDataRow['PAYAMT'];
		 $g_Netamt 			= $lpcBillDataRow['NETAMT'];
		 $g_Miniamt 		= $lpcBillDataRow['MINIAMT'];
		 $g_Schg 			= $lpcBillDataRow['SCHG'];
		 $g_Dchg 			= $lpcBillDataRow['DCHG'];
		 $g_PF_chg 			= $lpcBillDataRow['PF_CHG'];
		 $g_Rebate  		= $lpcBillDataRow['REBATE'];
		 $g_Vatamt 			= $lpcBillDataRow['VATAMT'];
		 $g_Lpcamt 			= $lpcBillDataRow['LPCAMT'];
		 $g_AnetAmt 		= $lpcBillDataRow['A_NET_AMT'];
		 $g_aVatAmt 		= $lpcBillDataRow['A_VAT_AMT'];
		 $g_ALpcAmt 		= $lpcBillDataRow['A_LPC_AMT'];
		 $g_InstallAmt 		= $lpcBillDataRow['INSTALL_AMT'];
		 $g_InstallVat 		= $lpcBillDataRow['INSTALL_VAT'];
		 $g_InstallLpc		= $lpcBillDataRow['INSTALL_LPC'];
		 $g_Total	 		= $lpcBillDataRow['TOTAL'];
		 $g_OtherAmt 		= $lpcBillDataRow['OTHAMT'];
		 $g_AdjAmt 			= $lpcBillDataRow['ADJAMT'];
		 $g_MeterRent	 	= $lpcBillDataRow['METER_RNT'];
		 $g_ApaneltyAmt		= $lpcBillDataRow['A_PENALTY_AMT'];
		 $g_AmistAmt	 	= $lpcBillDataRow['A_MISC_AMT'];
		 $g_AmeterRent		= $lpcBillDataRow['A_METER_RENT'];
		 $g_Panalty 		= $lpcBillDataRow['PENALTY'];
		 $g_MistAmt 		= $lpcBillDataRow['MISC_AMT'];
		 $g_DmcmBillNo 		= $lpcBillDataRow['DMCM_BILLNO'];
		 $g_AdjTyp 			= $lpcBillDataRow['ADJ_TYP'];
		 
		//Test Data 
		/*
		 echo 'PAYAMT: '.$g_PayAmount.', NETAMT: '.$g_Netamt.', MINIAMT: '.$g_Miniamt
		 .', SCHG: '.$g_Schg.', DCHG: '.$g_Dchg.', REBATE: '.$g_Rebate
		 .', VATAMT: '.$g_Vatamt.', LPCAMT: '.$g_Lpcamt.', A_NET_AMT: '.$g_AnetAmt
		 .', A_VAT_AMT: '.$g_aVatAmt.', A_LPC_AMT: '.$g_ALpcAmt.', INSTALL_AMT: '.$g_InstallAmt
		 .', INSTALL_VAT: '.$g_InstallVat.', INSTALL_LPC: '.$g_InstallLpc.', AMT_REBET: '.$g_BillAmtRebet
		 .', OTHAMT: '.$g_OtherAmt.', ADJAMT: '.$g_AdjAmt.', METER_RNT: '.$g_MeterRent
		 .', A_PANELTY_AMT: '.$g_ApaneltyAmt.', A_MISC_AMT: '.$g_AmistAmt.', A_METER_RENT: '.$g_AmeterRent
		 .', PENALTY: '.$g_Panalty.', MISC_AMT: '.$g_MistAmt.', DMCM_BILLNO: '.$g_DmcmBillNo
		 .', ADJ_TYP: '.$g_AdjTyp
		 .'<br />';
		*/
	}//End of while
	
	//Test Code to check Data
	/*
	echo 'PAYAMT: '.$g_PayAmount.', NETAMT: '.$g_Netamt.', MINIAMT: '.$g_Miniamt
		 .', SCHG: '.$g_Schg.', DCHG: '.$g_Dchg.', REBATE: '.$g_Rebate
		 .', VATAMT: '.$g_Vatamt.', LPCAMT: '.$g_Lpcamt.', A_NET_AMT: '.$g_AnetAmt
		 .', A_VAT_AMT: '.$g_aVatAmt.', A_LPC_AMT: '.$g_ALpcAmt.', INSTALL_AMT: '.$g_InstallAmt
		 .', INSTALL_VAT: '.$g_InstallVat.', INSTALL_LPC: '.$g_InstallLpc.', AMT_REBET: '.$g_BillAmtRebet
		 .', OTHAMT: '.$g_OtherAmt.', ADJAMT: '.$g_AdjAmt.', METER_RNT: '.$g_MeterRent
		 .', A_PANELTY_AMT: '.$g_ApaneltyAmt.', A_MISC_AMT: '.$g_AmistAmt.', A_METER_RENT: '.$g_AmeterRent
		 .', PENALTY: '.$g_Panalty.', MISC_AMT: '.$g_MistAmt.', DMCM_BILLNO: '.$g_DmcmBillNo
		 .', ADJ_TYP: '.$g_AdjTyp
		 .'<br />';
	*/
	
	$latePayment = "Y";
	$dcRcFee = 0;
	$dup_bill_fee =0;
	
	$g_Netamt 		= 0 - $pbs->nvl($g_Netamt,0);
	$g_Miniamt 		= 0 - $pbs->nvl($g_Miniamt,0);
	$g_Schg 		= 0 - $pbs->nvl($g_Schg,0);
	$g_Dchg 		= 0 - $pbs->nvl($g_Dchg,0);
	$g_PF_chg		= 0 - $pbs->nvl($g_PF_chg,0);
	$g_Rebate 		= 0 - $pbs->nvl($g_Rebate,0);
	$g_Total 		= 0 - $pbs->nvl($g_Total,0);//-NETAMT
	$g_OtherAmt 	= 0 - $pbs->nvl($g_OtherAmt,0);
	$lpcx			= $g_Lpcamt;
	
	$g_Vatxy		= $pbs->nvl($g_Vatamt,0);
	
	$lpc			= 0 - ($pbs->nvl($g_Lpcamt,0) + $pbs->nvl($g_ALpcAmt,0) + $pbs->nvl($g_InstallAmt,0));
	// Change Done - 20120903-2136
	$vatx			= 0 - ($pbs->nvl($g_Vatamt,0) + $pbs->nvl($g_aVatAmt,0) + $pbs->nvl($g_InstallVat,0));
	//echo $vatx;exit;
	$grandTotal		= 	($pbs->nvl($g_Total,0) + $pbs->nvl($g_OtherAmt,0) + $pbs->nvl($g_ApaneltyAmt,0)
						+ $pbs->nvl($g_AmistAmt,0) + $pbs->nvl($g_AmeterRent,0) + $pbs->nvl($g_MeterRent,0) 
						+ $pbs->nvl($vatx,0) + $lpc + $dcRcFee + $dup_bill_fee + $pbs->nvl($g_Panalty,0) 
						+ $pbs->nvl($g_MistAmt,0));
	
	$g_BillAmt		=  ($pbs->nvl($g_PayAmount,0) + $pbs->nvl($g_AnetAmt,0) + $pbs->nvl($g_aVatAmt,0)+ $pbs->nvl($g_ALpcAmt,0) 
												  + $pbs->nvl($g_InstallAmt,0) + $pbs->nvl($g_InstallVat,0) + $pbs->nvl($g_InstallLpc,0) 
												  + $pbs->nvl($g_ApaneltyAmt,0) + $pbs->nvl($g_AmistAmt,0) + $pbs->nvl($g_AmeterRent,0) 
												  + $dcRcFee + $dup_bill_fee);
	
	$g_Total 		= 0 - $pbs->nvl($g_Total,0);//-- BILL_COLLECTION->NETAMT
	$g_Vatamt		= $g_Vatxy;
	//echo $g_Vatamt;exit;
	if($g_BillAmt < 0){
		$g_BillAmt = 0;
	}
	else{
		$g_BillAmt = $g_BillAmt;
	}

	//-----------------------Global Variable---------------------201211281505
	$_SESSION['g_PayAmount'] 	= $g_PayAmount;
	$_SESSION['g_Netamt'] 		= $g_Netamt;
	$_SESSION['g_Miniamt'] 		= $g_Miniamt;
	$_SESSION['g_Schg'] 		= $g_Schg;
	$_SESSION['g_Dchg'] 		= $g_Dchg;
	$_SESSION['g_PF_chg'] 		= $g_PF_chg;
	$_SESSION['g_Rebate'] 		= $g_Rebate;
	$_SESSION['g_Vatamt'] 		= $g_Vatamt;
	$_SESSION['g_vatx'] 		= $vatx;
	$_SESSION['g_Lpcamt'] 		= $g_Lpcamt;
	$_SESSION['g_AnetAmt'] 		= $g_AnetAmt;
	$_SESSION['g_aVatAmt'] 		= $g_aVatAmt;
	$_SESSION['g_ALpcAmt'] 		= $g_ALpcAmt;
	$_SESSION['g_InstallAmt'] 	= $g_InstallAmt;
	$_SESSION['g_InstallVat'] 	= $g_InstallVat;
	$_SESSION['g_InstallLpc'] 	= $g_InstallLpc;
	$_SESSION['g_Total'] 		= $g_Total;
	$_SESSION['g_OtherAmt'] 	= $g_OtherAmt;
	$_SESSION['g_AdjAmt'] 		= $g_AdjAmt;
	$_SESSION['g_MeterRent'] 	= $g_MeterRent;
	$_SESSION['g_ApaneltyAmt'] 	= $g_ApaneltyAmt;
	$_SESSION['g_AmistAmt'] 	= $g_AmistAmt;
	$_SESSION['g_AmeterRent'] 	= $g_AmeterRent;
	$_SESSION['g_Panalty'] 		= $g_Panalty;
	$_SESSION['g_MistAmt'] 		= $g_MistAmt;
	$_SESSION['g_DmcmBillNo'] 	= $g_DmcmBillNo;
	$_SESSION['g_AdjTyp'] 		= $g_AdjTyp;
	$_SESSION['g_BillAmt'] 		= $g_BillAmt;
	$_SESSION['latePayment'] 	= $latePayment;
	$_SESSION['grandTotal'] 	= $grandTotal;
	$_SESSION['dcRcFee'] 		= $dcRcFee;
	$_SESSION['dup_bill_fee'] 	= $dup_bill_fee;
	
	//////////////////////////////////////////////////////////


	
//Back to bill_cat_101
//====================Variables Upto this Script=================================//
$lpcYn = '1';
$pay_with_lpc = $_SESSION['pay_with_lpc'];
if ($pay_with_lpc == 'Y')
{
	echo '<br /><br />Variable Used in pay_with_lpc.php';
	echo '<br />--------------------------------------------------------------------------';
			echo '<br />PAYAMT: '.$g_PayAmount;
			echo '<br />NETAMT: '.$g_Netamt;
			echo '<br />MINIAMT: '.$g_Miniamt;
			echo '<br />SCHG: '.$g_Schg;
			echo '<br />DCHG: '.$g_Dchg;
			echo '<br />PF_CHG: '.$g_PF_chg;
			echo '<br />REBATE: '.$g_Rebate;
			echo '<br />VATAMT: '.$g_Vatamt;
			echo '<br />LPCAMT: '.$g_Lpcamt;
			echo '<br />A_NET_AMT: '.$g_AnetAmt;
			echo '<br />A_VAT_AMT: '.$g_aVatAmt;
			echo '<br />A_LPC_AMT: '.$g_ALpcAmt;
			echo '<br />INSTALL_AMT: '.$g_InstallAmt;
			echo '<br />INSTALL_VAT: '.$g_InstallVat;
			echo '<br />INSTALL_LPC: '.$g_InstallLpc;
			echo '<br />AMT_REBET: '.$g_AmtRebet;
			echo '<br />OTHAMT: '.$g_OtherAmt;
			echo '<br />ADJAMT: '.$g_AdjAmt;
			echo '<br />METER_RNT: '.$g_MeterRent;
			echo '<br />A_PANELTY_AMT: '.$g_ApaneltyAmt;
			echo '<br />A_MISC_AMT: '.$g_AmistAmt;
			echo '<br />A_METER_RENT: '.$g_AmeterRent;
			echo '<br />PENALTY: '.$g_Panalty;
			echo '<br />MISC_AMT: '.$g_MistAmt;
			echo '<br />DMCM_BILLNO: '.$g_DmcmBillNo;
			echo '<br />ADJ_TYP: '.$g_AdjTyp;
			echo '<br />LPC Bill Amount Calculated: '.$g_BillAmt;
	echo '<br />--------------------------------------------------------------------------';
	}
}//End of else 1


?>
