<?php
/*
*	@authore: Sonnet
*	Company: CACTS LTD.
*	URL: http://cactsgroup.com
*	Date: 2012-Jul-17
*	Last update: 2012-Nov-25
*	**********************************
*	Source: Procidure->BILL_VARIFY_OV_BILL*
*/

session_start();
include("../config_pms.php");

//======================| Create Devic URL |=============================
$device = $_SESSION['device'];

$url = 'location:'.URL.$device.'/'.$device.'_bill_pay_view.php';
$confirmationUrl = 'location:'.URL.$device.'/'.$device.'_bill_confirmation.php';
//-----------------------------------------------------------------------

if($_SESSION['username']==''||$_SESSION['password']==''){//--------------Start if 001
	header("location:".URL."index.php");
}//End of 001

else if($_POST['billno'] ==''||$_POST['billAmt']==''){
	//echo $_POST['billno']." -- " .$_POST['billAmt'];exit;
		$_SESSION['msg']="Insert Bill No & Amount";
		header("$url");
}

else if($_SESSION['username']!='' && $_SESSION['password']!='' && $_SESSION['pbs_user_is_loged_in'] == 'loged')
{
		//Start 002
		$dbUser = $_SESSION['username'];
		$dbPass = $_SESSION['password'];
		$billNumber = $_POST['billno'];
		$billAmt = $_POST['billAmt'];
		
		//echo $_POST['billno']." -- " .$_POST['billAmt'];exit;
		
		//==================== Global Variable ==========================
		$_SESSION['billNumber'] = $billNumber;
		$_SESSION['billAmt'] = $billAmt;
		
		
		include(INC."db_conn.php");
		include(INC."functions.php");
		$pbs = new PbsCal;		
		
				
		//---------------------Checking Bill Number is Exixt or Not------------------------------------
		$billCat = substr($billNumber,0,3);
				
				if($billCat == '101')
				{
					$dom101Sql = oci_parse($c1, "select BILLNO from PMS.DOM_READ_Z1 where billno = '$billNumber'");
					oci_execute($dom101Sql,OCI_DEFAULT);
					while ($billCollrow = oci_fetch_array($dom101Sql, OCI_ASSOC+OCI_RETURN_NULLS)) {
						 $billNo = $billCollrow['BILLNO'];
					}	
						
				}
				else if($billCat == '201'){
					$dom101Sql = oci_parse($c1, "select BILLNO from PMS.FLAT_READ_Z1 where billno = '$billNumber'");
					oci_execute($dom101Sql,OCI_DEFAULT);
					while ($billCollrow = oci_fetch_array($dom101Sql, OCI_ASSOC+OCI_RETURN_NULLS)) {
						 $billNo = $billCollrow['BILLNO'];
					}	
					
				}
				else if($billCat == '205'){
					$dom101Sql = oci_parse($c1, "select BILLNO from PMS.PEAK_READ_Z1 where billno = '$billNumber'");
					oci_execute($dom101Sql,OCI_DEFAULT);
					while ($billCollrow = oci_fetch_array($dom101Sql, OCI_ASSOC+OCI_RETURN_NULLS)) {
						 $billNo = $billCollrow['BILLNO'];
					}	
				}
				else{
					$_SESSION['msg'] = "The bill category $billCat not found";
					header("$url");
					exit;
				}
				
		if($billNo =='')
		{
			$_SESSION['msg'] = '<span id="bangla">বিল নং সঠিক নয়।</span><br />Bill number is incorrect.';
			header("$url");
			exit;
		}
				
		$geoCodeSql = oci_parse($c1, "SELECT * FROM PMS.S_USER_AUTH_MASTER WHERE USER_ID = '$dbUser'");
		oci_execute($geoCodeSql,OCI_DEFAULT);
		while ($thisrow = oci_fetch_array($geoCodeSql, OCI_ASSOC+OCI_RETURN_NULLS)) {
			 $geoCode = $thisrow['GEO_CODE'];
			 $officeCode = $thisrow['OFFICE_CODE'];
			 
		}
		
		$unionSql = oci_parse($c1, "SELECT E_DISTICT,E_THANA,E_UNION FROM PMS.VW_UNION_INFO WHERE UNION_CODE = '$geoCode'");
		oci_execute($unionSql,OCI_DEFAULT);
		while ($unionRow = oci_fetch_array($unionSql, OCI_ASSOC+OCI_RETURN_NULLS)) {
			 $e_Distict = $unionRow['E_DISTICT'];
			 $e_thana = $unionRow['E_THANA'];
			 $e_union = $unionRow['E_UNION'];
		}
		//-----------------------Global Variable---------------------
		$_SESSION['geoCode'] = $geoCode;
		$_SESSION['officeCode'] = $officeCode;
		$_SESSION['e_Distict'] = $e_Distict;
		$_SESSION['e_thana'] = $e_thana;
		$_SESSION['e_union'] = $e_union;
		
		//echo "GEO_CODE: $geoCode, <br /> OFFICE_CODE: $officeCode <br />"; 
		
		$agentIdSql = oci_parse($c1, "SELECT DISTINCT AGENT_ID FROM PMS.S_USER_AUTH_MASTER WHERE USER_ID = '$dbUser'");
		oci_execute($agentIdSql,OCI_DEFAULT);
		while ($thisrow1 = oci_fetch_array($agentIdSql, OCI_ASSOC+OCI_RETURN_NULLS)) {
			 $agentId = $thisrow1['AGENT_ID'];
			 //echo $agentId;
			}
		//-----------------------Global Variable---------------------
		$_SESSION['agentId'] = $agentId;
		
		
		$sysdate = $pbs->oraSysDate('DD/MM/RRRR');
		//-----------------------Global Variable---------------------
		$_SESSION['sysdate'] = $sysdate;
		
		
		//echo $geoCode.' - '.$officeCode.' - '.$agentId.' - '.$sysdate;
		//================== Checking Daily Process is complete today or Not ===================================
		if($geoCode !='' && $agentId !=''){ //-----------if 
			$processDateSql = "select to_char(SYSDAT,'DD/MM/RRRR') VDAT from PMS.POST_PROCESS_UIC
				  WHERE UIC_CODE=$geoCode AND TO_DATE(SUBSTR(SYSDAT,1,10),'DD/MM/RRRR')=TO_DATE('$sysdate','DD/MM/RRRR')
				  AND OFFICE_CODE=$officeCode AND AGENT_ID=$agentId";
			$processDateSqlEx = oci_parse($c1, $processDateSql);
			oci_execute($processDateSqlEx,OCI_DEFAULT);
			while ($row2 = oci_fetch_row($processDateSqlEx)) {
					 foreach($row2 as $item2) {
				 $processDate= $item2;
				}
				if($processDate){
					$_SESSION['msg'] = "To-Day Online UISC Posting Process is already complete. You can not collect To-Day.";
					header("$url"); 
				}
			}
		
			$sysDate2= $pbs->oraSysDate('DD/MM/RRRR');
			//-----------------------Global Variable---------------------
			$_SESSION['sysDate2'] = $sysDate2;
			//echo $sysDate2;
			
			//Checking Bill Collected or not.
			$billCollSql = oci_parse($c1, "select PAYDATE,COLL_POINT,POSTED_YN from PMS.bill_collection where billno = '$billNumber'");
			oci_execute($billCollSql,OCI_DEFAULT);
			while ($billCollrow = oci_fetch_array($billCollSql, OCI_ASSOC+OCI_RETURN_NULLS)) {
				 $payDate = $billCollrow['PAYDATE'];
				 $collPoint = $billCollrow['COLL_POINT'];
				 $postedYn = $billCollrow['POSTED_YN'];
				 //echo $payDate;
			}
					
			//Checking Bill Collected or not for DMCM.
			$billCollDmcmSql = oci_parse($c1, "select PAYDATE,COLL_POINT,POSTED_YN from PMS.bill_collection where DMCM_BILLNO = '$billNumber'");
			oci_execute($billCollDmcmSql,OCI_DEFAULT);
			
			while ($billCollrow = oci_fetch_array($billCollDmcmSql, OCI_ASSOC+OCI_RETURN_NULLS)) {
				 $payDateDmcm = $billCollrow['PAYDATE'];
				 $collPointDmcm = $billCollrow['COLL_POINT'];
				 $postedYnDmcm = $billCollrow['POSTED_YN'];
			}
			if($payDate !='' || $payDateDmcm !=''){
				$billPayed = '1';
			}
			//echo $postedYnDmcm;
			//Checking Group of loggedin user
			$groupSql = "SELECT grp FROM PMS.S_USER_AUTH_MASTER WHERE USER_ID= '$dbUser'";
			$grpropEx = oci_parse($c1, $groupSql);
			oci_execute($grpropEx, OCI_DEFAULT);			
			while ($grprow = oci_fetch_row($grpropEx)) {
			  foreach($grprow as $grpropItem) {
				 $group= $grpropItem;
				}
			}
			//-----------------------Global Variable---------------------
			$_SESSION['group'] = $group;
			
			//Verifi user Group
			if(isset($payDate)){
				$group = strtoupper($group);
				$g_overPay = 'N';
				$g_postedYn = 'U';
				
				//-----------------------Global Variable---------------------
				$_SESSION['g_overPay'] = $g_overPay;
				$_SESSION['g_postedYn'] = $g_postedYn;
				
				//--------------TODO-------------OVER Payment.
				//echo $group;
				//$msg = "Collected  on  $payDate  at $collPoint.Do you Over collect it?";
				if($group == 'FINANCE' || $group == 'SU' || $group == 'DBA' || $group == 'CASH' ||$group == 'ASST_CASH'){
					$g_overPay = 'Y';
					echo "Collected  on  $payDate  at $collPoint. Over collect it?". '<a href="over_bill_coll.php"> [--Yes--] </a> <a href="bill_pay.php"> [--No--] </a>';
					//--TODO-----
					
				}
				else
				{
					//------ Added for test-------
					$test_all = $_SESSION['test_all'];
					if ($test_all != 'Y')
					{	
						$_SESSION['msg'] = "This bill is already paid on $payDate at $collPoint";
						header("$url");
					}
				}			
			}
			
			else if(isset($payDateDmcm)){
				$_SESSION['msg'] = 'This bill is collected partially.<br /> 
									You must pay this bill at PBS office.';
				header($url);
				exit;
			}
			else{
				$billCat = substr($billNumber,0,3);
				//-----------------------Global Variable---------------------
				$_SESSION['billCat'] = $billCat;
				//echo test;exit;
				if($billCat == '101'){
					//echo test101;exit;
					include(INC."bill_cat_101.php");
					//echo 'bill_pay_process-line-232';exit;
					//echo $confirmationUrl;exit;
					if($agentHasEnoughBalance == '1')
					{
						header("$confirmationUrl");
					}
				}
				else if($billCat == '201'){
					$_SESSION['msg'] =  "Industrial Bill collection is not alowed here currently!!";
					header("$url");
					exit;
					//Source: FROM->BILL_COLLECTION.fmb->Bill_number Textbox.
					//include(INC."bill_cat_201.php");
				}
				else if($billCat == '205'){
					$_SESSION['msg'] =  'Irrigation Bill Collection<br /> is not allowed here. <br />Pay this bill in Bank or PBS office.';
					header("$url");
					exit;
				}
				else{
					$_SESSION['msg'] = "The bill category $billCat not found";
					header("$url");
					exit;
				}
			}
		}
		else{
			$_SESSION['msg'] = "You are not authorized to collect bill.";
					header("$url");
					exit;
		}
		
		
		//------ Added for test-------
		$test_all = $_SESSION['test_all'];
		if ($test_all == 'Y')
		{	
			include(INC."bill_cat_101.php");
			header("$confirmationUrl");
		}
		//-------End of test Code------
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
Test retrived Data
*/
//--------------Test Variable Values----------------
		$bill_pay_process = $_SESSION['bill_pay_process'];
		if ($bill_pay_process == 'Y')
		{		
		echo '<br /><br />Veriables in bil_pay_process.php Script';
		echo '<br />--------------------------------------------------------------------------';
				echo '<br />Pay Date : '.$payDate;
				echo '<br />Collection Point: '.$collPoint;
				echo '<br />Posted Y/N : '.$postedYn;
				echo '<br />Pay Date DMCM: '.$payDateDmcm;
				echo '<br />Collection Point DMCM: '.$collPointDmcm;
				echo '<br />Posted DMCM Y/N : '.$postedYnDmcm;
				echo '<br />User Name: '.$dbUser; 
				echo '<br />Bill Number: '.$billNumber;
				echo '<br />Bill Amount Entered: '.$billAmt;
				echo '<br />Geo Code: '.$geoCode;
				echo '<br />Office Code: '.$officeCode;
				echo '<br />Agent ID: '.$agentId;
				echo '<br />SysDate (DD/MM/YYYY) : '.$sysdate;
				echo '<br />SysDate (DD/MM/YYYY) : '.$sysDate2;
		echo '<br />--------------------------------------------------------------------------';
		}
	}//End of 002
else{
	$_SESSION['msg']="Login User/Password Incorrect";
		header("location:".BASEPATH."index.php");
}
?>
