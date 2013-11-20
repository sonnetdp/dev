<?php
/*
*	@authore: Sonnet
*	Company: CACTS LTD.
*	URL: http://cactsgroup.com
*	Date: 2012-Jul-23
*	**********************************
*/
//================ Session Time check ===================
$sessionTime 	= $_SESSION['time_limit'];
$device 		= $_SESSION['device'];
if ($_SESSION['timeout'] + $sessionTime * 60 < time()) 
{
	$urlx = 'location:'.URL.$device.'/'.$device.'_index.php';
     $_SESSION['grp'] = 'Sorry your session has expeired.';
	 header($urlx);
} 
else 
{
     $_SESSION['timeout'] = time();
}
//--------------- End of Session Time ------------------

class PbsCal{
	
	function dumpVar($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        exit();
    }
	
	function nvl($value,$constant){
		if($value){
			$val = $value;
		}
		else{
			$val = $constant;
		}
		return $val;
	}

	//================ Result Array ==================
	function result_array($sql)
    {
		$c1 = $GLOBALS['c1'];
		$result = array();
		$query = oci_parse($c1, $sql);
		oci_execute($query,OCI_DEFAULT);
        while($data = oci_fetch_array($query))
        {
            $result[] =  $data;
        }
        $rows = count($result);
        if($rows)
        {
            $total_global_rows = count($result);
            $total_inner_rows =  count($result[0]);
            $count_total_inner_rows = $total_inner_rows/2;

            for($i = 0;$i<$total_global_rows;$i++)
            {
                for($j=0;$j<$count_total_inner_rows;$j++)
                {
                    unset($result[$i][$j]);        
                }    
            }
        }    
        return $result;    
    }
	//--------------------------------------------------------------------
	
	function oraSysDate($dateFormat){
		
		$c1 = $GLOBALS['c1'];
		//echo $dateFormat.'<br />';
		if($dateFormat == 'NF')
		{
			$sysDateSql = "Select sysdate FROM dual";
		}
		else
		{
			$sysDateSql = "Select to_char(sysdate, '$dateFormat') FROM dual";
		}
		//echo $sysDateSql;
		$sysDateEx = oci_parse($c1, $sysDateSql);
		oci_execute($sysDateEx, OCI_DEFAULT);
		while ($sysDateRow = oci_fetch_row($sysDateEx)) {
		  foreach($sysDateRow as $sysDateItem) {
			 $sysDat= $sysDateItem;
			 //echo $sysDat;
			}
		}
		return $sysDat;
		//echo $sysDat;
	}
	
	function balanceOfAgent($aId,$gCode){
		$c1 = $GLOBALS['c1'];
		$agentBalCheckSql ="SELECT (SUM(NVL(CRAMT,0))- SUM(NVL(DRAMT,0))) AGENT_BALANCE
							FROM PMS.VW_AGENT_DEPOSITE_LEDGER
							WHERE AGENT_ID='$aId' AND GEO_CODE='$gCode'";
		$agentBalCheckEx = oci_parse($c1, $agentBalCheckSql);
		oci_execute($agentBalCheckEx,OCI_DEFAULT);
		while ($agentBalCheckRow = oci_fetch_array($agentBalCheckEx, OCI_ASSOC+OCI_RETURN_NULLS)) {
			 $agentBalance = $agentBalCheckRow['AGENT_BALANCE'];
		}
		//echo $agentBalance;
		return $agentBalance;
	}
	function balanceLimitOfAgent($aId,$gCode){
		$c1 = $GLOBALS['c1'];
		$agentBalCheckSql ="SELECT BALANCE_LIMIT
							FROM PMS.AGENT_Z1
							WHERE AGENT_ID='$aId' AND GEO_CODE='$gCode'";
		$agentBalCheckEx = oci_parse($c1, $agentBalCheckSql);
		oci_execute($agentBalCheckEx,OCI_DEFAULT);
		while ($agentBalCheckRow = oci_fetch_array($agentBalCheckEx, OCI_ASSOC+OCI_RETURN_NULLS)) {
			 $agentBalanceLimit = $agentBalCheckRow['BALANCE_LIMIT'];
		}
		//echo $agentBalance;
		return $agentBalanceLimit;
	}
	function userLogedIn(){
		if(isset($_SESSION['user_is_here']))
		{
			return $_SESSION['user_is_here'];
		}
		else
		{
			return '';
		}
	}
	

	function current_user_name($loginID)
	{
		$c1 = $GLOBALS['c1'];
		$geoCodeSql = oci_parse($c1, "SELECT GEO_CODE,USRNM FROM PMS.S_USER_AUTH_MASTER WHERE USER_ID = '$loginID'");
		oci_execute($geoCodeSql,OCI_DEFAULT);
		while ($thisrow = oci_fetch_array($geoCodeSql, OCI_ASSOC+OCI_RETURN_NULLS)) {
			 $userName = $thisrow['USRNM'];
		   }
	return $userName;	
	}
	
	function current_user_geocode($loginID)
	{
		$c1 = $GLOBALS['c1'];
		$geoCodeSql = oci_parse($c1, "SELECT GEO_CODE,USRNM FROM PMS.S_USER_AUTH_MASTER WHERE USER_ID = '$loginID'");
		oci_execute($geoCodeSql,OCI_DEFAULT);
		while ($thisrow = oci_fetch_array($geoCodeSql, OCI_ASSOC+OCI_RETURN_NULLS)) {
			 $geoCode = $thisrow['GEO_CODE'];
		   }
	return $geoCode;	
	}
	function current_user_agentid($loginID)
	{
		$c1 = $GLOBALS['c1'];
		$agentIdSql = oci_parse($c1, "SELECT DISTINCT AGENT_ID FROM PMS.S_USER_AUTH_MASTER WHERE USER_ID = '$loginID'");
		oci_execute($agentIdSql,OCI_DEFAULT);
		while ($thisrow1 = oci_fetch_array($agentIdSql, OCI_ASSOC+OCI_RETURN_NULLS)) {
			$agentId = $thisrow1['AGENT_ID'];
	   	}
		//echo $agentId;
	return $agentId;	
	}
	
	//----Current User Current Balance--------
	function current_user_balance($loginID)
	{
		$c1 = $GLOBALS['c1'];
		$geoCodeSql = oci_parse($c1, "SELECT GEO_CODE,USRNM FROM PMS.S_USER_AUTH_MASTER WHERE USER_ID = '$loginID'");
		oci_execute($geoCodeSql,OCI_DEFAULT);
		while ($thisrow = oci_fetch_array($geoCodeSql, OCI_ASSOC+OCI_RETURN_NULLS)) {
			 $geoCode = $thisrow['GEO_CODE'];
			 
		}
		
		$agentIdSql = oci_parse($c1, "SELECT DISTINCT AGENT_ID FROM PMS.S_USER_AUTH_MASTER WHERE USER_ID = '$loginID'");
		oci_execute($agentIdSql,OCI_DEFAULT);
		while ($thisrow1 = oci_fetch_array($agentIdSql, OCI_ASSOC+OCI_RETURN_NULLS)) {
		 	$agentId = $thisrow1['AGENT_ID'];
		 //$userName = $thisrow['USRNM'];
	   }
		 
		$balanceSql = oci_parse($c1, "SELECT (SUM(NVL(CRAMT,0))- SUM(NVL(DRAMT,0))) BAL FROM PMS.VW_AGENT_DEPOSITE_LEDGER WHERE AGENT_ID='$agentId' AND GEO_CODE='$geoCode'");
		oci_execute($balanceSql,OCI_DEFAULT);
		while ($thisrow2 = oci_fetch_array($balanceSql, OCI_ASSOC+OCI_RETURN_NULLS)) {
			$balance = $thisrow2['BAL'];
			
	   	}
	return $balance;	
	}
	
	//----Scroll No of Bill Collected Bill-----
	function scroll_no($loginID)
	{
		$c1 = $GLOBALS['c1'];
		$pbs = new PbsCal;
		$geoCode = $pbs->current_user_geocode($loginID);
		$v_date = $pbs->oraSysDate('DD-MON-RRRR');
		$sql = "select nvl(max(scroll_no),0) V_SCROLL_NO  
									from PMS.BILL_COLLECTION
				  					where GEO_CODE = '$geoCode' and TO_DATE(TO_CHAR(SYSDAT,'DD-MM-RRRR'),'DD-MM-RRRR')='$v_date'";
		$scrollSql = oci_parse($c1, $sql);
		//echo $sql;
		oci_execute($scrollSql,OCI_DEFAULT);
		while ($thisrow = oci_fetch_array($scrollSql, OCI_ASSOC+OCI_RETURN_NULLS)) {
		 	$scrollNo = $thisrow['V_SCROLL_NO'];
		}
		$scrollNo = $scrollNo + 1;
	   return $scrollNo;
	}
	
	//----Collection Point-----
	function collPoint($loginID)
	{
	//echo $loginID;exit;
		$c1 = $GLOBALS['c1'];
		$pbs = new PbsCal;
		$geoCode = $pbs->current_user_geocode($loginID);
		//$agentId = $pbs->current_user_agentid($loginID);
		
		$sql = oci_parse($c1, "SELECT E_DISTICT,E_THANA,E_UNION FROM PMS.VW_UNION_INFO
	  							WHERE UNION_CODE = '$geoCode'");
		oci_execute($sql,OCI_DEFAULT);
		while ($thisrow = oci_fetch_array($sql, OCI_ASSOC+OCI_RETURN_NULLS)) {
		 	$e_district = $thisrow['E_DISTICT'];
			$e_thana = $thisrow['E_THANA'];
			$e_union = $thisrow['E_UNION'];
		}
		$collPoint = "$e_district-$e_thana-$e_union";
	   return $collPoint;
	}
	
	//----PBS CODE-----
	function pbsCode($book,$acno)
	{
		$c1 = $GLOBALS['c1'];
		$pbs = new PbsCal;
		$geoCode = $pbs->current_user_geocode($loginID);
		//$agentId = $pbs->current_user_agentid($loginID);
		
		$sql = oci_parse($c1, "SELECT PBS_CODE FROM PMS.ACNO_Z1
	  							WHERE BOOK_NO = '$book' AND ACNO = '$acno'");
		oci_execute($sql,OCI_DEFAULT);
		while ($thisrow = oci_fetch_array($sql, OCI_ASSOC+OCI_RETURN_NULLS)) {
			$pbs_code = $thisrow['PBS_CODE'];
		}
		
	   return $pbs_code;
	}
	
	//-------Bank Code---------------
	function bankCode($loginID)
	{
		$c1 = $GLOBALS['c1'];
		$pbs = new PbsCal;
		$geoCode = $pbs->current_user_geocode($loginID);
		//$agentId = $pbs->current_user_agentid($loginID);
		
		$sql = oci_parse($c1, "SELECT BANK_CODE FROM PMS.S_USER_AUTH_MASTER");
		oci_execute($sql,OCI_DEFAULT);
		while ($thisrow = oci_fetch_array($sql, OCI_ASSOC+OCI_RETURN_NULLS)) {
			$bank_code = $thisrow['BANK_CODE'];
		}
		return $bank_code;
	}
	//============== Office Code ===================
	function officeCode($loginID)
	{
		$c1 = $GLOBALS['c1'];
		$pbs = new PbsCal;
		$geoCode = $pbs->current_user_geocode($loginID);
		//$agentId = $pbs->current_user_agentid($loginID);
		
		$sql = oci_parse($c1, "SELECT OFFICE_CODE FROM PMS.S_USER_AUTH_MASTER");
		oci_execute($sql,OCI_DEFAULT);
		while ($thisrow = oci_fetch_array($sql, OCI_ASSOC+OCI_RETURN_NULLS)) {
			$office_code = $thisrow['OFFICE_CODE'];
		}
		return $office_code;
	}
	
	//-------Agent Commission---------------
	function agentComm($loginID)
	{
		$c1 = $GLOBALS['c1'];
		$pbs = new PbsCal;
		$geoCode = $pbs->current_user_geocode($loginID);
		$agentId = $pbs->current_user_agentid($loginID);
		
		$sql =  "SELECT AGENT_COMM FROM PMS.AGENT_Z1 WHERE GEO_CODE = '$geoCode' AND AGENT_ID = '$agentId'";
		//echo $sql;
		$sql_parse = oci_parse($c1, $sql);
		oci_execute($sql_parse,OCI_DEFAULT);
		while ($thisrow = oci_fetch_array($sql_parse, OCI_ASSOC+OCI_RETURN_NULLS)) {
			$agentCommission = $thisrow['AGENT_COMM'];
		}
		return $agentCommission;
	}
	
	//-------Soft Commission---------------
	function softComm($loginID)
	{
		$c1 = $GLOBALS['c1'];
		$pbs = new PbsCal;
		$geoCode = $pbs->current_user_geocode($loginID);
		$agentId = $pbs->current_user_agentid($loginID);
		
		$sql = "SELECT SOFT_SUPP_COMM FROM PMS.AGENT_Z1 WHERE GEO_CODE = '$geoCode' AND AGENT_ID = '$agentId'";
		//echo $sql;
		$sql_parse = oci_parse($c1,$sql );
		oci_execute($sql_parse,OCI_DEFAULT);
		while ($thisrow = oci_fetch_array($sql_parse, OCI_ASSOC+OCI_RETURN_NULLS)) {
			$softCommission = $thisrow['SOFT_SUPP_COMM'];
		}
		return $softCommission;
	}
	
	//-------Soft Commission---------------
	function dueDateDb($bookNo,$acNo,$billNumber,$billYear,$billMonth)
	{
		$Sql ="SELECT DUE_DATE
						FROM PMS.DOM_READ_Z1
						WHERE  BOOK_NOX   = $bookNo  
						AND ACNOX  = $acNo
						AND BILLNO = $billNumber
						AND BILL_YEAR = $billYear
						AND BILL_MONTH = $billMonth";
		$domHolidayCheckEx = oci_parse($c1, $domHolidayCheckSql);
		oci_execute($domHolidayCheckEx,OCI_DEFAULT);
		while ($domHolidayCheckRow = oci_fetch_array($domHolidayCheckEx, OCI_ASSOC+OCI_RETURN_NULLS)) {
			 $dueDate = $domHolidayCheckRow['DUE_DATE'];
		}
		return $dueDate;
	}
  
	
}


class DebugTest{
	function dumpVar($data)
		{
			echo '<pre>';
			print_r($data);
			echo '</pre>';
			exit();
		}
}
?>
