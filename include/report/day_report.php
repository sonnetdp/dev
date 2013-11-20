<?php
/*
*	@authore: Sonnet
*	Company: CACTS LTD.
*	URL: http://cactsgroup.com
*	Date: 2012-Nov-18
*	Last update: 2012-Nov-21
*	**********************************
*/


session_start();

if($_SESSION['username']==''||$_SESSION['password']==''){
	header("location:".BASEPATH."index.php");
}

else if($_SESSION['username']!='' && $_SESSION['password']!='' && $_SESSION['pbs_user_is_loged_in'] == 'loged')
{
	function dayReport($agentId,$geoCode,$sysdate)
		{
			$c1 = $GLOBALS['c1'];
			$dayReportSql = "SELECT ALL t1.COLL_POINT,t1.BILLNO, t1.BOOK_NO, t1.ACNO,t1.BILL_MONTH, t1.BILL_YEAR, 
				nvl(LPCAMT,0)+nvl(ARR_LPC,0)+nvl(ARR_IRRI_LPC,0)  LPCAMT,  LATE_PAYMENT,
				(nvl(NETAMT,0)+nvl(ARR_NET_AMT,0)) NETAMOUNT,   nvl(VATAMT,0)+nvl(ARR_VAT,0) VATAMT, 
				nvl(OTHAMT,0)+nvl(DC_RC_FEE,0)+nvl(DUP_BILL_FEE,0)+ 
				nvl(TR_RENT,0) + nvl(ARR_TR_RENT,0)+nvl(PENALTY,0)+nvl(ARR_PENALTY,0)+
				nvl(ARR_MISC,0)+nvl(MISCAMT,0)+nvl(ARR_TLOSS_CHG,0)+nvl(ARR_PF_DIFF,0)+nvl(ARR_PF_CHG,0)    othamt,nvl(meter_rent,0)+nvl(ARR_MRENT,0)  mrent,SCROLL_NO
				
				FROM PMS.BILL_COLLECTION t1
				WHERE  T1.AGENT_ID=$agentId AND T1.GEO_CODE=$geoCode AND t1.PAYDATE='$sysdate' AND T1.POSTED_YN='U'
				
				UNION ALL
				select previ_bill_coll.coll_point, previ_bill_coll.billno, previ_bill_coll.book_nox,previ_bill_coll.acnox, previ_bill_coll.bill_month,
				previ_bill_coll.bill_year, 
				(nvl(previ_bill_coll.lpcamt,0)
					+nvl(previ_bill_coll.irri_lpc,0)
					+nvl(previ_bill_coll.a_lpc_amt,0)
					+nvl(previ_bill_coll.install_lpc,0)) lpcamt, 
				previ_bill_coll.late_payment, 
				(nvl(previ_bill_coll.netamt,0)
					+nvl(previ_bill_coll.miniamt,0)
					+nvl(previ_bill_coll.schg,0)
					+nvl(previ_bill_coll.dchg,0)
					+nvl(previ_bill_coll.pf_chg,0)
					+nvl(previ_bill_coll.tloss_chg,0)
					-nvl(previ_bill_coll.rebate,0)
					+nvl(previ_bill_coll.a_net_amt,0)
				+nvl(previ_bill_coll.install_amt,0)) netamount, 
				(nvl(previ_bill_coll.vatamt,0)
				+nvl(previ_bill_coll.a_vat_amt,0)
				+nvl(previ_bill_coll.install_vat,0)) vatamt,   
				( nvl(penalty,0)+nvl(misc_amt,0)
				+nvl(adjamt,0)
				+nvl(tr_rent,0) ) othamt ,
				nvl(meter_rent,0) , 0 SCROLL_NO
				from PMS.previ_bill_coll
				where  AGENT_ID = $agentId AND GEO_CODE = $geoCode AND PAYDATE='$sysdate' AND POSTED_YN='U' order by SCROLL_NO";
			
			//echo $dayReportSql; exit;
			$dayReportParse = oci_parse($c1,$dayReportSql);
			//$count = oci_num_rows($dayReportParse);
			oci_execute($dayReportParse,OCI_DEFAULT);
			//$nrows = oci_fetch_all($dayReportParse, $dayReport);
			$dayTotal = 0;
			$stampedBill = 0;
			$totalCount = 0;
			while ($dayReportRow = oci_fetch_array($dayReportParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
		 			$total = $dayReportRow['NETAMOUNT'] + $dayReportRow['VATAMT'] + $dayReportRow['LPCAMT'] + $dayReportRow['MRENT'] + $dayReportRow['OTHAMT'];
					echo '<tr>';
						echo '<td>'.$dayReportRow['SCROLL_NO'].'</td>';
						echo '<td>'.$dayReportRow['ACNO'].'</td>';
						echo '<td>'.$dayReportRow['BOOK_NO'].'</td>';
						echo '<td>'.$dayReportRow['BILLNO'].'</td>';
						echo '<td>'.$dayReportRow['BILL_MONTH'].' - '.$dayReportRow['BILL_YEAR'].'</td>';
						echo '<td>'.$dayReportRow['NETAMOUNT'].'</td>';
						echo '<td>'.$dayReportRow['VATAMT'].'</td>';
						echo '<td>'.$dayReportRow['LPCAMT'].'</td>';
						echo '<td>'.$dayReportRow['MRENT'].'</td>';
						echo '<td>'.$dayReportRow['OTHAMT'].'</td>';
						echo '<td>'.$total.'</td>';
					echo '</tr>';
					$dayTotal = $dayTotal + $total;
					if($total > 400)
					{
						$stampedBill++;
					}
					$totalCount++;
			}
			$summery['dayTotal'] = $dayTotal;
			$summery['totalCount'] = $totalCount;
			$summery['stampedBill'] = $stampedBill;
			
			return $summery;
		}
//============ Report of Date Range ==========================		
		function dayRangeReport($agentId,$geoCode,$startDate,$endDate)
		{
			$c1 = $GLOBALS['c1'];
			$dayReportSql = "SELECT ALL t1.COLL_POINT,t1.BILLNO, t1.BOOK_NO, t1.ACNO,t1.BILL_MONTH, t1.BILL_YEAR, 
				nvl(LPCAMT,0)+nvl(ARR_LPC,0)+nvl(ARR_IRRI_LPC,0)  LPCAMT,  LATE_PAYMENT,
				(nvl(NETAMT,0)+nvl(ARR_NET_AMT,0)) NETAMOUNT,   nvl(VATAMT,0)+nvl(ARR_VAT,0) VATAMT, 
				nvl(OTHAMT,0)+nvl(DC_RC_FEE,0)+nvl(DUP_BILL_FEE,0)+ 
				nvl(TR_RENT,0) + nvl(ARR_TR_RENT,0)+nvl(PENALTY,0)+nvl(ARR_PENALTY,0)+
				nvl(ARR_MISC,0)+nvl(MISCAMT,0)+nvl(ARR_TLOSS_CHG,0)+nvl(ARR_PF_DIFF,0)+nvl(ARR_PF_CHG,0)    othamt,nvl(meter_rent,0)+nvl(ARR_MRENT,0)  mrent,SCROLL_NO
				
				FROM PMS.BILL_COLLECTION t1
				WHERE  T1.AGENT_ID=$agentId AND T1.GEO_CODE=$geoCode AND T1.POSTED_YN='U' AND t1.PAYDATE BETWEEN '$startDate' AND '$endDate'
				
				UNION ALL
				select previ_bill_coll.coll_point, previ_bill_coll.billno, previ_bill_coll.book_nox,previ_bill_coll.acnox, previ_bill_coll.bill_month,
				previ_bill_coll.bill_year, 
				(nvl(previ_bill_coll.lpcamt,0)
					+nvl(previ_bill_coll.irri_lpc,0)
					+nvl(previ_bill_coll.a_lpc_amt,0)
					+nvl(previ_bill_coll.install_lpc,0)) lpcamt, 
				previ_bill_coll.late_payment, 
				(nvl(previ_bill_coll.netamt,0)
					+nvl(previ_bill_coll.miniamt,0)
					+nvl(previ_bill_coll.schg,0)
					+nvl(previ_bill_coll.dchg,0)
					+nvl(previ_bill_coll.pf_chg,0)
					+nvl(previ_bill_coll.tloss_chg,0)
					-nvl(previ_bill_coll.rebate,0)
					+nvl(previ_bill_coll.a_net_amt,0)
				+nvl(previ_bill_coll.install_amt,0)) netamount, 
				(nvl(previ_bill_coll.vatamt,0)
				+nvl(previ_bill_coll.a_vat_amt,0)
				+nvl(previ_bill_coll.install_vat,0)) vatamt,   
				( nvl(penalty,0)+nvl(misc_amt,0)
				+nvl(adjamt,0)
				+nvl(tr_rent,0) ) othamt ,
				nvl(meter_rent,0) , 0 SCROLL_NO
				from PMS.previ_bill_coll
				where  AGENT_ID = $agentId AND GEO_CODE = $geoCode AND POSTED_YN='U' AND PAYDATE BETWEEN '$startDate' AND '$endDate' order by SCROLL_NO";
			
			//echo $dayReportSql; exit;
			$dayReportParse = oci_parse($c1,$dayReportSql);
			//$count = oci_num_rows($dayReportParse);
			oci_execute($dayReportParse,OCI_DEFAULT);
			//$nrows = oci_fetch_all($dayReportParse, $dayReport);
			$dayTotal = 0;
			$stampedBill = 0;
			$totalCount = 0;
			while ($dayReportRow = oci_fetch_array($dayReportParse, OCI_ASSOC+OCI_RETURN_NULLS)) {
		 			$total = $dayReportRow['NETAMOUNT'] + $dayReportRow['VATAMT'] + $dayReportRow['LPCAMT'] + $dayReportRow['MRENT'] + $dayReportRow['OTHAMT'];
					echo '<tr>';
						echo '<td>'.$dayReportRow['SCROLL_NO'].'</td>';
						echo '<td>'.$dayReportRow['ACNO'].'</td>';
						echo '<td>'.$dayReportRow['BOOK_NO'].'</td>';
						echo '<td>'.$dayReportRow['BILLNO'].'</td>';
						echo '<td>'.$dayReportRow['BILL_MONTH'].' - '.$dayReportRow['BILL_YEAR'].'</td>';
						echo '<td>'.$dayReportRow['NETAMOUNT'].'</td>';
						echo '<td>'.$dayReportRow['VATAMT'].'</td>';
						echo '<td>'.$dayReportRow['LPCAMT'].'</td>';
						echo '<td>'.$dayReportRow['MRENT'].'</td>';
						echo '<td>'.$dayReportRow['OTHAMT'].'</td>';
						echo '<td>'.$total.'</td>';
					echo '</tr>';
					$dayTotal = $dayTotal + $total;
					if($total > 400)
					{
						$stampedBill++;
					}
					$totalCount++;
			}
			$summery['dayTotal'] = $dayTotal;
			$summery['totalCount'] = $totalCount;
			$summery['stampedBill'] = $stampedBill;
			
			return $summery;
		}

		
}
else{
	$_SESSION['msg']="Login User/Password Incorrect";
		header("location:".BASEPATH."index.php");
}
?>
