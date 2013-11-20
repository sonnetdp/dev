<?php
/*
*	@authore: Sonnet
*	Company: CACTS LTD.
*	URL: http://cactsgroup.com
*	Date: 2012-Jul-18
*	Last Update: 2012-Nov-28
*	**********************************
*/

if($_SESSION['username']==''||$_SESSION['password']==''){
	header("location:".BASEPATH."index.php");
	}
else if($_SESSION['username']!='' && $_SESSION['password']!='' && $_SESSION['pbs_user_is_loged_in'] == 'loged')
{
	$nLpcBillDataSql= "select  (NETAMT + MINIAMT + SCHG + DCHG + NVL(PF_CHG,0) - REBATE + PENALTY + MISC_AMT + ADJAMT + VATAMT + nvl(METER_RENT,0)) PAYAMT,
	NETAMT, MINIAMT, SCHG, DCHG, NVL(PF_CHG,0) PF_CHG, REBATE, VATAMT, LPCAMT, A_NET_AMT, A_VAT_AMT, A_LPC_AMT, INSTALL_AMT, INSTALL_VAT, INSTALL_LPC, (NETAMT + MINIAMT + SCHG + DCHG + NVL(PF_CHG,0) - REBATE) TOTAL,0 OTHAMT, nvl(adjamt,0) ADJAMT_N,
					  nvl(METER_RENT,0) METER_RNT,
					  nvl(A_PENALTY_AMT,0) A_PENALTY_AMT,
					  nvl(A_MISC_AMT,0) A_MISC_AMT, nvl(A_METER_RENT,0) A_METER_RENT,
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
					
	$nLpcBillDataEx = oci_parse($c1, $nLpcBillDataSql);
	oci_execute($nLpcBillDataEx,OCI_DEFAULT);
	while ($nLpcBillDataRow = oci_fetch_array($nLpcBillDataEx, OCI_ASSOC+OCI_RETURN_NULLS)) {
		 $g_PayAmount 		= $nLpcBillDataRow['PAYAMT'];
		 $g_Netamt 			= $nLpcBillDataRow['NETAMT'];
		 $g_Miniamt 		= $nLpcBillDataRow['MINIAMT'];
		 $g_Schg 			= $nLpcBillDataRow['SCHG'];
		 $g_Dchg 			= $nLpcBillDataRow['DCHG'];
		 $g_PF_chg 			= $nLpcBillDataRow['PF_CHG'];
		 $g_Rebate 			= $nLpcBillDataRow['REBATE'];
		 $g_Vatamt 			= $nLpcBillDataRow['VATAMT'];
		 $g_Lpcamt 			= $nLpcBillDataRow['LPCAMT'];
		 $g_AnetAmt 		= $nLpcBillDataRow['A_NET_AMT'];
		 $g_aVatAmt 		= $nLpcBillDataRow['A_VAT_AMT'];
		 $g_ALpcAmt 		= $nLpcBillDataRow['A_LPC_AMT'];
		 $g_InstallAmt 		= $nLpcBillDataRow['INSTALL_AMT'];
		 $g_InstallVat 		= $nLpcBillDataRow['INSTALL_VAT'];
		 $g_InstallLpc		= $nLpcBillDataRow['INSTALL_LPC'];
		 $g_Total			= $nLpcBillDataRow['TOTAL'];
		 $g_OtherAmt 		= $nLpcBillDataRow['OTHAMT'];
		 $g_AdjAmt 			= $nLpcBillDataRow['ADJAMT'];
		 $g_MeterRent		= $nLpcBillDataRow['METER_RNT'];
		 $g_ApaneltyAmt		= $nLpcBillDataRow['A_PENALTY_AMT'];
		 $g_AmistAmt	 	= $nLpcBillDataRow['A_MISC_AMT'];
		 $g_AmeterRent		= $nLpcBillDataRow['A_METER_RENT'];
		 $g_Panalty 		= $nLpcBillDataRow['PENALTY'];
		 $g_MistAmt 		= $nLpcBillDataRow['MISC_AMT'];
		 $g_DmcmnBllNo 		= $nLpcBillDataRow['DMCM_BILLNO'];
		 $g_AdjTyp 			= $nLpcBillDataRow['ADJ_TYP'];
		 
		 
		 
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
	}
	
		
	//Test Data
	$nvl = new PbsCal;
	$latePayment = "N";
	$dcRcFee = 0;
	$dup_bill_fee = 0;
	
	$g_Netamt 			= 0 - $nvl->nvl($g_Netamt,0);
	$g_Miniamt 			= 0 - $nvl->nvl($g_Miniamt,0);
	$g_Schg 			= 0 - $nvl->nvl($g_Schg,0);
	$g_Dchg 			= 0 - $nvl->nvl($g_Dchg,0);
	$g_PF_chg			= 0 - $pbs->nvl($g_PF_chg,0);
	$g_Rebate 			= 0 - $nvl->nvl($g_Rebate,0);
	$g_MeterRent 		= $nvl->nvl($g_MeterRent,0);
	
	$lpc				= 0 -($nvl->nvl($g_ALpcAmt,0) + $nvl->nvl($g_InstallAmt,0));
	
	$g_Vatamt			= 0 -($nvl->nvl($g_Vatamt,0) + $nvl->nvl($g_aVatAmt,0) + $nvl->nvl($g_InstallVat,0));
	
	$g_Total 			= 0 - $nvl->nvl($g_Total,0);
	$g_OtherAmt 		= 0 - $nvl->nvl($g_OtherAmt,0);
	
	$grandTotal			= ($nvl->nvl($g_Total,0) + $nvl->nvl($g_OtherAmt,0) + $nvl->nvl($g_ApaneltyAmt,0)
							+ $nvl->nvl($g_AmistAmt,0) + $nvl->nvl($g_AmeterRent,0) + $nvl->nvl($g_MeterRent,0) 
							+ $nvl->nvl($g_Vatamt,0) + $lpc + $dcRcFee + $dup_bill_fee + $nvl->nvl($g_Panalty,0) 
							+ $nvl->nvl($g_MistAmt,0));
							
	$g_BillAmt			= ($nvl->nvl($g_PayAmount,0) + $nvl->nvl($g_AnetAmt,0) + $nvl->nvl($g_aVatAmt,0) 
							+ $nvl->nvl($g_ALpcAmt,0) + $nvl->nvl($g_InstallAmt,0) + $nvl->nvl($g_InstallVat,0)
							+ $nvl->nvl($g_InstallLpc,0) + $nvl->nvl($g_ApaneltyAmt,0) + $nvl->nvl($g_AmistAmt,0)
							+ $nvl->nvl($g_AmeterRent,0) + $dcRcFee + $dup_bill_fee);
	
	$g_Total 			= 0 - $nvl->nvl($g_Total,0);//-- BILL_COLLECTION->NETAMT
	$g_Vatamt			= $nvl->nvl($g_Vatamt,0);
	
	if($g_BillAmt < 0){
		$g_BillAmt = 0;
	}
	else{
		$g_BillAmt = $g_BillAmt;
	}
	
	//-----------------------Global Variable---------------------
	$_SESSION['g_PayAmount'] 	= $g_PayAmount;
	$_SESSION['g_Netamt'] 		= $g_Netamt;
	$_SESSION['g_Miniamt'] 		= $g_Miniamt;
	$_SESSION['g_Schg'] 		= $g_Schg;
	$_SESSION['g_Dchg'] 		= $g_Dchg;
	$_SESSION['g_PF_chg'] 		= $g_PF_chg;
	$_SESSION['g_Rebate'] 		= $g_Rebate;
	$_SESSION['g_Vatamt'] 		= $g_Vatamt;
	$_SESSION['g_Lpcamt'] 		= $lpc;
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
	
//Back to bill_cat_101
	$lpcYn = '2';
	$pay_without_lpc = $_SESSION['pay_without_lpc'];
	if ($pay_without_lpc == 'Y')
	{
		echo '<br /><br />Variable Used in pay_without_lpc.php';
		echo '<br />--------------------------------------------------------------------------';
			echo '<br />PAYAMT: '.$g_PayAmount;
			echo '<br />NETAMT: '.$g_Netamt;
			echo '<br />MINIAMT: '.$g_Miniamt;
			echo '<br />SCHG: '.$g_Schg;
			echo '<br />DCHG: '.$g_Dchg;
			echo '<br />REBATE: '.$g_Rebate;
			echo '<br />VATAMT: '.$g_Vatamt;
			echo '<br />LPCAMT: '.$lpc;
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
			echo '<br />Without LPC Bill Amount Calculated: '.$g_BillAmt;
		
	}
}


?>
