<?php
/*
*	@authore: Sonnet
*	Email: sonnetdp@gmail.com
*	Company: CACTS LTD.
*	URL: http://cactsgroup.com
*	Last Update: 2013-Jan-21: 1953
*	**********************************
*/

if($_SESSION['username']==''||$_SESSION['password']=='')
{
	header("location:".URL."index.php");
}
else if($_SESSION['username']!='' && $_SESSION['password']!='' && $_SESSION['pbs_user_is_loged_in'] == 'loged')
{	//Start ------------01
		
	//echo 'Test- pending_process_101-Line-17';exit;
	
	
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
		
	//------------Global Variables---------------------
	$billNumber 		= $_SESSION['billNumber'];
	$billAmt 			= $_SESSION['billAmt'];
	//--------
	$g_Netamt			= $_SESSION['g_Netamt'];
	$g_AnetAmt 			= $_SESSION['g_AnetAmt'];
	$g_aVatAmt 			= $_SESSION['g_aVatAmt'];
	$g_InstallVat		= $_SESSION['g_InstallVat'];
	$g_ALpcAmt 			= $_SESSION['g_ALpcAmt'];
	$g_InstallLpc 		= $_SESSION['g_InstallLpc'];
	$g_InstallAmt 		= $_SESSION['g_InstallAmt'];
	$g_ApaneltyAmt 		= $_SESSION['g_ApaneltyAmt'];
	$g_AmistAmt 		= $_SESSION['g_AmistAmt'];
	$g_AmeterRent 		= $_SESSION['g_AmeterRent'];
	$g_AdjTyp			= $_SESSION['g_AdjTyp'];
	$g_OtherAmt			= $_SESSION['g_OtherAmt'];
	$g_Schg				= $_SESSION['g_Schg'];
	$g_Dchg				= $_SESSION['g_Dchg'];
	$g_PF_chg			= $_SESSION['g_PF_chg'];
	$latePayment		= $_SESSION['latePayment'];
	//---------
	$dom101bookNo		= $_SESSION['dom101bookNo'];
	$dom101acNo 		= $_SESSION['dom101acNo'];
	$dom101arrYear		= $_SESSION['dom101arrYear'];
	$dom101arrMonth		= $_SESSION['dom101arrMonth'];
	$dom101cCodex		= $_SESSION['dom101cCodex'];
	$oths_amt 			= $_SESSION['oths_amt'];
	$officeCode			= $_SESSION['officeCode'];
	$geoCode 			= $_SESSION['geoCode'];
	$g_overPay 			= $_SESSION['g_overPay'];
	$agentId			= $_SESSION['agentId'];
	
	$z_code 			= substr($officeCode,5,5);
	$pbs_code 			= $pbs->pbsCode($dom101bookNo,$dom101acNo);
	$payDateYYYYMM		= $pbs->oraSysDate('RRRRMM');
	$payDate			= $pbs->oraSysDate('DD-Mon-RRRR');
	$sysDate			= $pbs->oraSysDate('NF');
	
	
	
	unset($pendingDmcmBillno);
	$pendingBillDataSql= "SELECT DMCM_BILLNO  
							FROM PMS.DOM_READ_Z1 
							WHERE billno = '$billNumber'";
	$pendingBillDataEx = oci_parse($c1, $pendingBillDataSql);
	oci_execute($pendingBillDataEx,OCI_DEFAULT);
	while ($pendingBillDataRow = oci_fetch_array($pendingBillDataEx, OCI_ASSOC+OCI_RETURN_NULLS)) 
	{
		 $pendingDmcmBillno = $pendingBillDataRow['DMCM_BILLNO'];
		 //echo $pendingDmcmBillno;exit;
	}
	$penDmcmBillCat	= substr($pendingDmcmBillno,0,3);
	//echo $pendingDmcmBillno;
	
	//============================= IF THERE IS A DMCM BILL NUMBER ========================================
	if(isset($pendingDmcmBillno))
	{	//Start------0101
		//echo '<br /><br />Pending Process---';exit;
		$pendingCheck101Sql ="SELECT c_codex V_C_CODEX,
								   netamt V_ENERGY_CHG,
								   miniamt V_MINIAMT,
								   schg V_SCHG,
								   dchg V_DCHG,
								   nvl(PF_CHG,0) V_PF_CHG,
								   tot_kwread V_TOT_KWREAD,
								   vatamt V_VATAMT,
								   lpcamt V_LPCAMT,
								   0 V_IRR_LPC,
								   due_date V_DUE_OR_COLL_DATE,
								   disc_date V_DISC_DATE,
								   11 V_BTYPE,
								   nvl(PENALTY,0) V_PENALTY_AMT,
								   nvl(MISC_AMT,0) V_MISC_AMT,
								   nvl(METER_RENT,0) V_METER_RENT,
								   nvl(INST_PAY_NETTOT,0) V_INST_PAY_NETTOT,
								   nvl(INST_PAY_VATTOT,0) V_INST_PAY_VATTOT,
								   nvl(INST_PAY_LPCTOT,0) V_INST_PAY_LPCTOT,
								   nvl(INST_PAY_PENTOT,0) V_INST_PAY_PENTOT,
								   nvl(INST_PAY_MISTOT,0) V_INST_PAY_MISTOT,
								   nvl(INST_PAY_METTOT,0) V_INST_PAY_METTOT,
								   nvl(rebate,0) V_REBATE
						  
						  FROM PMS.dom_read_z1
						  WHERE billno = '$pendingDmcmBillno'";
		//echo 'pendingCheck101Sql-(Line-94)=> '.$pendingCheck101Sql;exit;				  
		$pendingCheck101Ex = oci_parse($c1, $pendingCheck101Sql);
		oci_execute($pendingCheck101Ex,OCI_DEFAULT);
		
		while ($pendingCheck101Row = oci_fetch_array($pendingCheck101Ex, OCI_ASSOC+OCI_RETURN_NULLS)) 
		{
		
			 $pen101_V_C_CODEX 				= $pendingCheck101Row['V_C_CODEX'];
			 $pen101_V_ENERGY_CHG 			= $pendingCheck101Row['V_ENERGY_CHG'];
			 $pen101_V_MINIAMT 				= $pendingCheck101Row['V_MINIAMT'];
			 $pen101_V_SCHG 				= $pendingCheck101Row['V_SCHG'];
			 $pen101_V_DCHG 				= $pendingCheck101Row['V_DCHG'];
			 $pen101_V_PF_CHG 				= $pendingCheck101Row['V_PF_CHG'];
			 $pen101_V_TOT_KWREAD			= $pendingCheck101Row['V_TOT_KWREAD'];
			 $pen101_V_VATAMT 				= $pendingCheck101Row['V_VATAMT'];
			 $pen101_V_LPCAMT	 			= $pendingCheck101Row['V_LPCAMT'];
			 $pen101_V_IRR_LPC 				= $pendingCheck101Row['V_IRR_LPC'];
			 $pen101_V_DUE_OR_COLL_DATE 	= $pendingCheck101Row['V_DUE_OR_COLL_DATE'];
			 $pen101_V_DISC_DATE 			= $pendingCheck101Row['V_DISC_DATE'];
			 $pen101_V_BTYPE 				= $pendingCheck101Row['V_BTYPE'];
			 $pen101_V_PENALTY_AMT 			= $pendingCheck101Row['V_PENALTY_AMT'];
			 $pen101_V_MISC_AMT 			= $pendingCheck101Row['V_MISC_AMT'];
			 $pen101_V_METER_RENT			= $pendingCheck101Row['V_METER_RENT'];
			 $pen101_V_INST_PAY_NETTOT		= $pendingCheck101Row['V_INST_PAY_NETTOT'];
			 $pen101_V_INST_PAY_VATTOT		= $pendingCheck101Row['V_INST_PAY_VATTOT'];
			 $pen101_V_INST_PAY_LPCTOT		= $pendingCheck101Row['V_INST_PAY_LPCTOT'];
			 $pen101_V_INST_PAY_PENTOT		= $pendingCheck101Row['V_INST_PAY_PENTOT'];
			 $pen101_V_INST_PAY_MISTOT 		= $pendingCheck101Row['V_INST_PAY_MISTOT'];
			 $pen101_V_INST_PAY_METTOT 		= $pendingCheck101Row['V_INST_PAY_METTOT'];
			 $pen101_V_REBATE 				= $pendingCheck101Row['V_REBATE'];
			 
		}
		//Row Count
		oci_fetch_all($pendingCheck101Ex, $array);
		unset($array);
		$dataExixt = oci_num_rows($pendingCheck101Ex);
		//echo $dataExixt; exit;
		if($dataExixt >= '1')
		{
			$v_swsx = '1';
		}
		else
		{
			$v_swsx = '0';
		}
		//echo 'pending_process_101: (Line:141) $v_swsx = '.$v_swsx;exit;
		//========== Pending process in PRE-INSERT ==============================================================
		
		$g_arr_tr_rent		=	0;
		$amt_reduce_yn 		=	'N';
		$vatx				=	$_SESSION['g_vatx'];
		$g_OtherAmt			= 	0 - ($g_ApaneltyAmt + $g_AmistAmt + $g_AmeterRent + $g_arr_tr_rent);
		$arr_vat			=	0 - ($pbs->nvl($g_aVatAmt,0) + $pbs->nvl($g_InstallVat,0));
		$arr_lpc			=	0 - ($pbs->nvl($g_ALpcAmt,0) + $pbs->nvl($g_InstallLpc,0));
		$arr_net			=	0 - ($pbs->nvl($g_AnetAmt,0) + $pbs->nvl($g_InstallAmt,0)); 
		
		//===================== Over Collection =================================================================
		
		if($g_AnetAmt != 0 || $g_aVatAmt != 0 || $g_ALpcAmt != 0 || $g_OtherAmt != 0)
		{	//=========== Start 0102
			$pendingInsertResult = '0';
			if($g_overPay == 'Y')
			{//================ Start 010201--------- if overpay ---------------------- 
				
				$rmk ='OVER COLLECTION FOR BILL-'.$billNumber; 
				$overPaySql = "	insert into PMS.pending_z1 
						(Book_Nox,ACNOx,BILL_YEAR,BILL_MONTH,Billno,yyyy_mm,c_codex,
						TOT_KWREAD,ENERGY_CHG,MINIAMT,SCHG,DCHG,PF_CHG,TLOSS_CHG,LATE_PAYMENT,
						VATAMT,LPCAMT,IRR_LPC,TOTAMT,balamt,Typx,mini_bill,not_mini_bill,
						DUE_OR_coll_DATE,btype, usr,z_code,pbs_code,sysdat,REMARKSX,over_pay,
						oths_amt,GEO_CODE )
					 values 
						('$dom101bookNo','$dom101acNo','$dom101arrYear','$dom101arrMonth','$pendingDmcmBillno','$payDateYYYYMM','$dom101cCodex',
						0,0,0,'$g_Schg' ,'$g_Dchg' ,$g_PF_chg,0,'$latePayment',
						'$arr_vat', '$arr_lpc',0,'$arr_net',0,'COLL',0,0,
						'$payDate',0, '$dbUser','$z_code','$pbs_code','$sysDate',$rmk,'$g_overPay',
						'$g_OtherAmt','$geoCode')";
				
				//echo 'overPaySql-(Line-180)=> '.$overPaySql;exit;	
				$overPayPrs = oci_parse($c1, $overPaySql);
				$overPayEx = oci_execute($overPayPrs,OCI_DEFAULT);
				if($overPayEx)
				{
					$pendingInsertResult = '1';
				}
				else
				{
					$pendingInsertResult = '0';
				}
			}//-------------End 010201 -----------
			else
			{	//============= Start 010202------------- else overpay 
				
				$billCat = substr($pendingDmcmBillno,0,3);
				if($g_AnetAmt < 0 || $g_aVatAmt < 0 || $g_ALpcAmt < 0)
				{	//================= Start 01020201
					if($billCat == '101' || $billCat == '201' || $billCat == '205')
					{
						$rmk	=	'OP adjustment by billno-'.$billNumber;
					}
					else
					{
						$rmk	=	$g_AdjTyp.' adjustment by billno-'.$billNumber;
					}
				}//---------------------End 01020201
				else
				{	//================= Start 01020202
					$rmk	=	$g_AdjTyp.' adjustment by billno-'.$billNumber;
				}	//----------------- End 01020202
								
				$overPaySql = "insert into PMS.pending_z1 
						(Book_Nox,ACNOx,BILL_YEAR,BILL_MONTH,Billno,yyyy_mm,c_codex,
						TOT_KWREAD,ENERGY_CHG,MINIAMT,SCHG,DCHG,PF_CHG,TLOSS_CHG,LATE_PAYMENT,
						VATAMT,LPCAMT,IRR_LPC,TOTAMT,balamt,Typx,mini_bill,not_mini_bill,
						DUE_OR_coll_DATE,btype, usr,z_code,pbs_code,sysdat,REMARKSX,over_pay,
						oths_amt,GEO_CODE )
					values 
						('$dom101bookNo','$dom101acNo','$dom101arrYear','$dom101arrMonth','$pendingDmcmBillno','$payDateYYYYMM','$dom101cCodex',
						0,0,0,0,0,$g_PF_chg,0,'$latePayment',
						'$arr_vat', '$arr_lpc',0,'$arr_net',0,'COLL',0,0,
						'$payDate',0, '$dbUser','$z_code','$pbs_code','$sysDate','$rmk','$g_overPay',
						'$g_OtherAmt','$geoCode')";
				
				//echo 'overPaySql-(Line-228)=> '.$overPaySql;exit;
				$overPayPrs = oci_parse($c1, $overPaySql);
				$overPayEx = oci_execute($overPayPrs,OCI_DEFAULT);
				if($overPayEx)
				{
					$pendingInsertResult = '1';
				}
				else
				{
					$pendingInsertResult = '0';
				}
			}//------------End 010202 ------ overpay ----------------
			
			$xtype = '';
			if($g_AdjTyp == 'AR')
			{
				$xtyp ='ARREAR';
			}
			elseif($g_AdjTyp == 'DM')
			{
				$xtyp = 'DM';
			}
			elseif ($g_AdjTyp =='CM')
			{
				$xtyp = 'CM';
			}
			elseif ($g_AdjTyp =='JM')
			{
				$xtyp = 'JM';  
			}
			$updatePendingSql = "update PMS.pending_z1 set INST_COLL_YN='Y' 
								where billno = '$pendingDmcmBillno'  
								and	book_nox = '$dom101bookNo' 
								and acnox = '$dom101acNo' 
								and bill_year = '$dom101arrYear' 
								and bill_month = '$dom101arrMonth'";
			
			//echo 'updatePendingSql-(Line-269)=> '.$updatePendingSql;exit;
			$updatePendingPrs = oci_parse($c1, $updatePendingSql);
			$updatePendingEx = oci_execute($updatePendingPrs,OCI_DEFAULT);
			if($updatePendingEx)
			{
				$pendingInsertResult = '1';
			}
			else
			{
				$pendingInsertResult = '0';
			}
		}//--------End 0102
		
		if($g_InstallAmt != 0 || $g_InstallVat != 0 || $g_InstallLpc != 0)
		{	//=========== Start 0103
			$rmk	=	$g_AdjTyp.' adjustment by billno-'.$billNumber;
			$pendingInsertResult = '0';
			$insPenSql = "insert into PMS.pending_z1 
							  (Book_Nox,ACNOx,BILL_YEAR,BILL_MONTH,Billno,yyyy_mm,c_codex,
							  TOT_KWREAD,ENERGY_CHG,MINIAMT,SCHG,DCHG,PF_CHG,TLOSS_CHG,LATE_PAYMENT,
							  VATAMT,LPCAMT,IRR_LPC,TOTAMT,balamt,Typx,mini_bill,not_mini_bill,
							  DUE_OR_coll_DATE,btype, usr,z_code,pbs_code,sysdat,REMARKSX,over_pay,
							  oths_amt,GEO_CODE )
							values 
							   ('$dom101bookNo','$dom101acNo','$dom101arrYear','$dom101arrMonth','$pendingDmcmBillno','$payDateYYYYMM','$dom101cCodex',
								 0,0,0,0,0,0,0,0,0,0,'$g_PF_chg',0,'$latePayment',
								'$arr_vat', '$arr_lpc',0,'$arr_net',0,'COLL',0,0,
								'$payDate',0, '$dbUser','$z_code','$pbs_code','$sysDate',$rmk,'$g_overPay',
								'$g_OtherAmt','$geoCode')";
			
			//echo 'insPenSql-(Line-304)=> '.$insPenSql;exit;
			$insPenPrs = oci_parse($c1, $insPenSql);
			$insPenEx = oci_execute($insPenPrs,OCI_DEFAULT);
			if($updatePendingEx)
			{
				$pendingInsertResult = '1';
			}
			else
			{
				$pendingInsertResult = '0';
			}
		}//------- End 0103
		
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////			
/////////////////////////////////////////// END OF PRE-INSERT  //////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($penDmcmBillCat == '101' || $penDmcmBillCat == '102' || $penDmcmBillCat == '205')
		{	//============ Start 0104
			$pendingInsertResult = '0';
			if ($pen101_V_ENERGY_CHG + $pen101_V_MINIAMT + $pen101_V_SCHG + $pen101_V_DCHG - ($pbs->nvl($pen101_V_REBATE,0)) <= abs($g_AnetAmt)  
					&& $pen101_V_VATAMT <= abs($g_aVatAmt) 
					&& $pen101_V_LPCAMT <= abs($g_ALpcAmt) 
					&& $pen101_V_PENALTY_AMT <= abs($g_ApaneltyAmt) 
					&& $pen101_V_MISC_AMT <= abs($g_AmistAmt) 
					&& $pen101_V_METER_RENT  <= abs($g_AmeterRent))
			{	//==================== Start 010401
				if ($g_AdjTyp =='OP') 
				{	//======================= Start 01040101 
					
					$pendingOPSql = "UPDATE PMS.dom_read_z1 
								set bill_collect='Y',
								pending_process='N',
								balance_yn='N',
								INST_COLL_YN='Y',
								INST_PAY_NETTOT = nvl(INST_PAY_NETTOT,0)+ abs(nvl($g_AnetAmt,0)),
								INST_PAY_VATTOT = nvl(INST_PAY_vatTOT,0)+ abs(nvl($g_aVatAmt,0)),
								INST_PAY_LPCTOT = nvl(INST_PAY_lpcTOT,0)+ abs(nvl($g_ALpcAmt,0)),
								INST_PAY_PENTOT = nvl(INST_PAY_PENTOT,0)+ abs(nvl($g_ApaneltyAmt,0)),
								INST_PAY_MISTOT = nvl(INST_PAY_MISTOT,0)+ abs(nvl($g_AmistAmt,0)),
								INST_PAY_METTOT = nvl(INST_PAY_METTOT,0)+ abs(nvl($g_AmeterRent,0))   
								WHERE book_nox = '$dom101bookNo' 
								and acnox = '$dom101acNo' 
								and  billno = '$pendingDmcmBillno'" ;  
					//echo 'PendingOPSql-if-if(Line-340)=> '.$pendingOPSql;exit;
					$pendingOpPrs = oci_parse($c1, $pendingOPSql);
					$pendingOpEx = oci_execute($pendingOpPrs,OCI_DEFAULT);
					if($pendingOpEx)
					{
						$pendingInsertResult = '1';
					}
					else
					{
						$pendingInsertResult = '0';
					}
				}//-------------- End 01040101
				else
				{	//============ Start 01040102
					
					$pendingOPSql = "UPDATE PMS.dom_read_z1 
								set bill_collect='Y',
								pending_process='N',
								balance_yn='Y',
								INST_COLL_YN='Y',												  
								INST_PAY_NETTOT = nvl(INST_PAY_NETTOT,0)+ abs(nvl($g_AnetAmt,0)),
								INST_PAY_VATTOT = nvl(INST_PAY_vatTOT,0)+ abs(nvl($g_aVatAmt,0)),
								INST_PAY_LPCTOT = nvl(INST_PAY_lpcTOT,0)+ abs(nvl($g_ALpcAmt,0)),
								INST_PAY_PENTOT = nvl(INST_PAY_PENTOT,0)+ abs(nvl($g_ApaneltyAmt,0)),
								INST_PAY_MISTOT = nvl(INST_PAY_MISTOT,0)+ abs(nvl($g_AmistAmt,0)),
								INST_PAY_METTOT = nvl(INST_PAY_METTOT,0)+ abs(nvl($g_AmeterRent,0))   
							  WHERE book_nox = '$dom101bookNo' 
								and acnox = '$dom101acNo' 
								and  billno = '$pendingDmcmBillno'" ;  
					//echo 'PendingOPSql-if-else(Line:374)=> '.$pendingOPSql;exit;
					$pendingOpPrs = oci_parse($c1, $pendingOPSql);
					$pendingOpEx = oci_execute($pendingOpPrs,OCI_DEFAULT);
					if($pendingOpEx)
					{
						$pendingInsertResult = '1';
					}
					else
					{
						$pendingInsertResult = '0';
					}
				}//------------- End 01040102
			}//-------- End 010401
		
			else 
			{	//============== Start 010402
				if( $pbs->nvl( $pen101_V_INST_PAY_NETTOT,0) + abs($pbs->nvl($g_AnetAmt,0)) >= ($pen101_V_ENERGY_CHG + $pen101_V_MINIAMT + $pen101_V_SCHG + $pen101_V_DCHG - $pbs->nvl($pen101_V_REBATE,0))
					&& $pbs->nvl($pen101_V_INST_PAY_VATTOT,0)+abs($pbs->nvl($g_Vatamt,0)) >=  $pen101_V_VATAMT  
					&& $pbs->nvl($pen101_V_INST_PAY_LPCTOT,0)+abs($pbs->nvl($g_Lpcamt,0)) >=  $pen101_V_LPCAMT 
					&& $pbs->nvl($pen101_V_INST_PAY_PENTOT,0)+abs($pbs->nvl($g_ApaneltyAmt,0)) >= $pen101_V_PENALTY_AMT 
					&& $pbs->nvl($pen101_V_INST_PAY_MISTOT,0)+abs($pbs->nvl($g_AmistAmt,0)) >= $pen101_V_MISC_AMT
					&& $pbs->nvl($pen101_V_INST_PAY_METTOT,0)+abs($pbs->nvl($g_AmeterRent,0)) >= $pen101_V_METER_RENT)
				{//===================== Start 01040201
				  	if ($g_AdjTyp =='OP')
					{	//==================== Start 0104020101
						$pendingInsertResult = '0';
						$pendingOPSql = "UPDATE PMS.dom_read_z1 
												set bill_collect='Y',
												pending_process='N',
												balance_yn='N',
												INST_COLL_YN='Y',
												INST_PAY_NETTOT = nvl(INST_PAY_NETTOT,0)+ abs(nvl($g_AnetAmt,0)),
												INST_PAY_VATTOT = nvl(INST_PAY_vatTOT,0)+ abs(nvl($g_aVatAmt,0)),
												INST_PAY_LPCTOT = nvl(INST_PAY_lpcTOT,0)+ abs(nvl($g_ALpcAmt,0)),
												INST_PAY_PENTOT = nvl(INST_PAY_PENTOT,0)+ abs(nvl($g_ApaneltyAmt,0)),
												INST_PAY_MISTOT = nvl(INST_PAY_MISTOT,0)+ abs(nvl($g_AmistAmt,0)),
												INST_PAY_METTOT = nvl(INST_PAY_METTOT,0)+ abs(nvl($g_AmeterRent,0))   
											WHERE book_nox = '$dom101bookNo' 
											  and acnox = '$dom101acNo' 
											  and  billno = '$pendingDmcmBillno'" ;  
						//echo 'PendingOPSql-else-if(Line:418)=> '.$pendingOPSql;exit;
						$pendingOpPrs = oci_parse($c1, $pendingOPSql);
						$pendingOpEx = oci_execute($pendingOpPrs,OCI_DEFAULT);
						if($pendingOpEx)
						{
							$pendingInsertResult = '1';
						}
						else
						{
							$pendingInsertResult = '1';
						}
					}//------------- End 0104020101 -----------
					else
					{	//======================= Start 0104020102
						$pendingInsertResult = '0';
						$pendingOPSql = "UPDATE PMS.dom_read_z1 set 
									bill_collect='Y',
									pending_process='N',
									balance_yn='Y',
									INST_COLL_YN='Y',
									INST_PAY_NETTOT = nvl(INST_PAY_NETTOT,0)+ abs(nvl($g_AnetAmt,0)),
									INST_PAY_VATTOT = nvl(INST_PAY_vatTOT,0)+ abs(nvl($g_aVatAmt,0)),
									INST_PAY_LPCTOT = nvl(INST_PAY_lpcTOT,0)+ abs(nvl($g_ALpcAmt,0)),
									INST_PAY_PENTOT = nvl(INST_PAY_PENTOT,0)+ abs(nvl($g_ApaneltyAmt,0)),
									INST_PAY_MISTOT = nvl(INST_PAY_MISTOT,0)+ abs(nvl($g_AmistAmt,0)),
									INST_PAY_METTOT = nvl(INST_PAY_METTOT,0)+ abs(nvl($g_AmeterRent,0))   
									WHERE book_nox = '$dom101bookNo' 
								  and acnox = '$dom101acNo' 
								  and  billno = '$pendingDmcmBillno'" ;
						//echo 'PendingOPSql-else-else(Line:452)=> '.$pendingOPSql;exit;
						$pendingOpPrs = oci_parse($c1, $pendingOPSql);
						$pendingOpEx = oci_execute($pendingOpPrs,OCI_DEFAULT);
						if($pendingOpEx)
						{
							$pendingInsertResult = '1';
						}
						else
						{
							$pendingInsertResult = '0';
						}
					}//----------- End 0104020102
				}//----------- End 01040201
				else
				{	//=============== Start 01040202
					if ($g_AdjTyp =='OP')
					{	//=============== Start 0104020201
						$pendingInsertResult = '0';
						$pendingOPSql = "UPDATE PMS.dom_read_z1 set 
									pending_process='N',
									balance_yn='N',
									INST_COLL_YN='Y',
									INST_PAY_NETTOT = nvl(INST_PAY_NETTOT,0)+ abs(nvl($g_AnetAmt,0)),
									INST_PAY_VATTOT = nvl(INST_PAY_vatTOT,0)+ abs(nvl($g_aVatAmt,0)),
									INST_PAY_LPCTOT = nvl(INST_PAY_lpcTOT,0)+ abs(nvl($g_ALpcAmt,0)),
									INST_PAY_PENTOT = nvl(INST_PAY_PENTOT,0)+ abs(nvl($g_ApaneltyAmt,0)),
									INST_PAY_MISTOT = nvl(INST_PAY_MISTOT,0)+ abs(nvl($g_AmistAmt,0)),
									INST_PAY_METTOT = nvl(INST_PAY_METTOT,0)+ abs(nvl($g_AmeterRent,0))   
									WHERE book_nox = '$dom101bookNo' 
								  and acnox = '$dom101acNo' 
								  and  billno = '$pendingDmcmBillno'" ;
						//echo 'PendingOPSql-else-if(Line:488)=> '.$pendingOPSql;exit;
						$pendingOpPrs = oci_parse($c1, $pendingOPSql);
						$pendingOpEx = oci_execute($pendingOpPrs,OCI_DEFAULT);
						if($pendingOpEx)
						{
							$pendingInsertResult = '1';
						}//----------End--------- 0101020101
						else
						{
							$pendingInsertResult = '0';
						}
					}//--------------- End 0104020201
					else
					{	//=============== Start 0104020202
						$pendingInsertResult = '0';
						$pendingOPSql = "UPDATE PMS.dom_read_z1 set 
										pending_process='N',
										balance_yn='Y',
										INST_COLL_YN='Y',
										INST_PAY_NETTOT = nvl(INST_PAY_NETTOT,0)+ abs(nvl($g_AnetAmt,0)),
										INST_PAY_VATTOT = nvl(INST_PAY_vatTOT,0)+ abs(nvl($g_aVatAmt,0)),
										INST_PAY_LPCTOT = nvl(INST_PAY_lpcTOT,0)+ abs(nvl($g_ALpcAmt,0)),
										INST_PAY_PENTOT = nvl(INST_PAY_PENTOT,0)+ abs(nvl($g_ApaneltyAmt,0)),
										INST_PAY_MISTOT = nvl(INST_PAY_MISTOT,0)+ abs(nvl($g_AmistAmt,0)),
										INST_PAY_METTOT = nvl(INST_PAY_METTOT,0)+ abs(nvl($g_AmeterRent,0))   
										WHERE book_nox = '$dom101bookNo' 
									  and acnox = '$dom101acNo' 
									  and  billno = '$pendingDmcmBillno'" ;
						//echo 'PendingOPSql-else-else(Line:521)=> '.$pendingOPSql;exit;
						$pendingOpPrs = oci_parse($c1, $pendingOPSql);
						$pendingOpEx = oci_execute($pendingOpPrs,OCI_DEFAULT);
						if($pendingOpEx)
						{
							$pendingInsertResult = '1';
						}//----------End--------- 0101020101
						else
						{
							$pendingInsertResult = '0';
						}
					}//------------ End 0104020202
				}//------------End 01040202
			}//-----------End 010402
			//echo 'pending_process_101: (Line:350) $v_swsx = '.$v_swsx;exit;
			if($v_swsx == '1')//------------Sonnet 201208131757
			{
				$domPendingSql ="select 1 YES from PMS.pending_z1 
									where  book_nox = '$dom101bookNo'  
									and acnox = '$dom101acNo' 
									and billno = '$pendingDmcmBillno'
									and  typx = 'BILL'";
				//echo 'domPendingSql-if(Line:548)=> '.$domPendingSql;exit;
				$domPendingEx = oci_parse($c1, $domPendingSql);
				oci_execute($domPendingEx,OCI_DEFAULT);
				$dataExixtPending = oci_num_rows($domPendingEx);
							//echo $dataExixtPending; exit;
							if($dataExixtPending >= '1')
							{
								$pendingYesNo = '1';
							}
							else
							{
								$pendingYesNo = '0';
							}
				if($pendingYesNo == '0')
				{
					//echo 'pending_process_101:Line-378';exit;
					if ($g_AdjTyp !='OP')
					{
						$pendingInsertResult = '0';
						//echo 'pending_process_101:Line-381';exit;
						$v_tLoss_chg = 0;
						$total = ($pbs->nvl($pen101_V_ENERGY_CHG,0)
								+$pbs->nvl($pen101_V_MINIAMT,0)
								+$pbs->nvl($pen101_V_SCHG,0)
								+$pbs->nvl($pen101_V_DCHG,0)
								+$pbs->nvl($pen101_V_PF_CHG,0)
								+$pbs->nvl($v_tLoss_chg,0)
								-$pbs->nvl($pen101_V_REBATE,0));
						$oths_amt = $pen101_V_PENALTY_AMT+ $pen101_V_MISC_AMT + $pen101_V_METER_RENT;
						$remarks = $g_AdjTyp.' adjustment by billno-'.$billNumber;
						$sysdate = $pbs->oraSysDate('DD-Mon-RRRR');
						
						$pendingZ1Sql = "INSERT INTO PMS.pending_z1 
										(BOOK_NOX,ACNOX,BILL_YEAR,BILL_MONTH,BILLNO,
										YYYY_MM,C_CODEX,TOT_KWREAD,ENERGY_CHG,
										MINIAMT,SCHG,DCHG,PF_CHG,TLOSS_CHG,LATE_PAYMENT,
										VATAMT,LPCAMT,IRR_LPC,
										TOTAMT,REBATE,
										BALAMT,TYPX,MINI_BILL,NOT_MINI_BILL,
										DUE_OR_COLL_DATE,BILL_MON_DT,BTYPE,USR,Z_CODE,PBS_CODE,SYSDAT,
										REMARKSX,OVER_PAY,
										OTHS_AMT,GEO_CODE,OFFICE_CODE)
										values 
										('$dom101bookNo','$dom101acNo','$dom101arrYear','$dom101arrMonth','$pendingDmcmBillno',
										 '$dom101arrYear$dom101arrMonth','$pen101_V_C_CODEX','$pen101_V_TOT_KWREAD','$pen101_V_ENERGY_CHG',
										 '$pen101_V_MINIAMT','$pen101_V_SCHG','$pen101_V_DCHG','$pen101_V_PF_CHG','0','Y',
										 '$pen101_V_VATAMT','$pen101_V_LPCAMT','$pen101_V_IRR_LPC',
										 '$total','$pen101_V_REBATE',0,'BILL',0,0,'$pen101_V_DUE_OR_COLL_DATE',
										 '$pen101_V_DISC_DATE','$pen101_V_BTYPE', '$dbUser','$z_code','$pbs_code','$sysdate',
										 '$remarks' ,'$g_overPay',
										 '$oths_amt','$geoCode','$officeCode' )" ; 
						//echo 'pendingZ1Sql-if-if(Line:602)=> '.$pendingZ1Sql;exit;
						$pendingZ1Prs = oci_parse($c1, $pendingZ1Sql);
						$pendingOpEx = oci_execute($pendingZ1Prs,OCI_DEFAULT);
						
						if($pendingOpEx)
						{
							$pendingInsertResult = '1';
						}
						else
						{
							$pendingInsertResult = '0';
						}
					}
				}
			}
		}//------------ End 0101 -----------
	}//---------- End 0101 -----------			
}//--------End 01-----------
else
{
	$_SESSION['msg']="Login User/Password Incorrect";
	header("location:".URL."index.php");
}
//================ End of This Script ==============
?>
