<?php
/*
*	@authore: Kazi Sanghati
*	Company: OSS Japan Bangladesh Ltd.
*	URL: http://ossjb.com
*	Date: 2012-Jul-26
*	**********************************
*
*/

session_start();
include('config_pms.php');
if($_SESSION['username']=='' || $_SESSION['password']==''){//--------------Start if 001
	header("location:".BASEPATH."index.php");
}//End of 001

else if($_SESSION['username'] !='' && $_SESSION['password'] !='' && $_SESSION['pbs_user_is_loged_in'] == 'loged')
{
	$device = $_SESSION['device'];
	//======== Comon Include ========
	$dbUser = $_SESSION['username'];
	$dbPass = $_SESSION['password'];
	include(INC."db_conn.php");
	include(INC."functions.php");
	$pbs = new PbsCal;
	//exit;
	
	$success_url = 'location:'.URL.$device.'/'.$device.'_main.php';
	$un_success_url = 'location:'.URL.$device.'/'.$device.'_bill_pay_view.php';
	
	//--------------------------------
	if($_SESSION['billNumber'])
	{
		$success_url = 'location:'.URL.$device.'/'.$device.'_main.php';
		
		//======== Enter if there is an pending Bill ========
		$pendingFlag = '0';
		
		if($_SESSION['dom101dmcmBillno'] !='')
		{
			$pendingFlag = '1';
			include(INC."pending_process_101.php");
		}
		if($pendingFlag == '0')
		{
			include(INC."bill_pay_insert.php");
		}
		elseif($pendingFlag == '1' && $pendingInsertResult == '1')
		{
			include(INC."bill_pay_insert.php");
		}
		if($oraSuccess == '1')
		{
			/*
			IF :global.adj_amt>0 then
DECLARE
  v_acct number(8,2);
BEGIN

 IF :c_code ='I' then
    v_acct:=235;
 ELSE
   v_acct:=225.1;
 END IF;

   SELECT appn into :appn FROM PMS.acno_z1 where book_no=:book_no and acno=:acno;
   INSERT INTO PMS.deposite 
          (depono,depodate,depoamt,acct,book_no,acno,appno,c_code,z_code,pbscode,remarks)
   VALUES
          (:billno,:paydate,:global.adj_amt,v_acct,:book_no,:acno,:appn,:c_code,:z_code,:pbs_code,'Advance Collection');

END;
END IF;
			
			
			*/
			
			oci_commit($c1);
			//========= Cleare Session =========
				unset($_SESSION['billNumber']);
				unset($_SESSION['billAmt']);
				unset($_SESSION['g_Netamt']);
				unset($_SESSION['g_AnetAmt']);
				unset($_SESSION['g_aVatAmt']);
				unset($_SESSION['g_ALpcAmt']);
				unset($_SESSION['g_ApaneltyAmt']);
				unset($_SESSION['g_AmistAmt']);
				unset($_SESSION['g_AmeterRent']);
				unset($_SESSION['g_AdjTyp']);
				unset($_SESSION['dom101bookNo']);
				unset($_SESSION['dom101acNo']);
				unset($_SESSION['dom101billMonth']);
				unset($_SESSION['dom101billYear']);
				unset($_SESSION['latePayment']);
				unset($_SESSION['g_BillAmt']);
				unset($_SESSION['dom101bill_YYYYMM']);
				unset($_SESSION['g_PayAmount']);
				unset($_SESSION['g_Vatamt']);
				unset($_SESSION['g_Lpcamt']);
				unset($_SESSION['g_Total']);
				unset($_SESSION['dueDateMM_DD_YYYY']);
				unset($_SESSION['dom101arrMonth']);
				unset($_SESSION['dom101arrYear']);
				unset($_SESSION['dcRcFee']);	
				unset($_SESSION['dup_bill_fee']);
				unset($_SESSION['g_aVatAmt']);
				unset($_SESSION['g_ALpcAmt']);
				unset($_SESSION['g_ApaneltyAmt']);
				unset($_SESSION['g_AmistAmt']);
				unset($_SESSION['g_AmeterRent']);
				unset($_SESSION['g_overPay']);
				unset($_SESSION['g_MeterRent']);
				unset($_SESSION['g_OtherAmt']);
				unset($_SESSION['g_MistAmt']);
				unset($_SESSION['dueDateDb']);
				
			header($success_url);
		}
		else
		{
			$_SESSION['msg']='Sorry Your Internet Connection is very slow.<br /> Error: DBBPY97';
			header($un_success_url);
			exit;
			
		}
	}
	else
	{
		$_SESSION['msg']="Please Insert Another Bill Number";
		header($un_success_url);
		exit;
	}
}//End of 002
else{
	$_SESSION['msg']="Login User/Password Incorrect";
		header("location:".URL."index.php");
}
?>
