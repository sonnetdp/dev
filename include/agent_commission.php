<?php
/*
*	@authore: Sonnet
*	Company: CACTS LTD.
*	URL: http://cactsgroup.com
*	Date: 2012-Jul-28
*	**********************************
*/

if($_SESSION['username']==''||$_SESSION['password']==''){
	header("location: index.php");
	}
else{//Start ------------0001
	if($dmcmBillno){//Start------0002
		$pendingCheckSql ="select due_date,pending_process,disc_date   
							from PMS.dom_read_z1
							where  book_nox = '$dom101bookNo' 
							AND ACNOx  		= '$dom101acNo'
							and  billno     = '$billNumber'
							and  bill_year  = '$dom101billYear'
							and  bill_month = '$dom101billMonth'";
		$pendingCheckEx = oci_parse($c1, $pendingCheckSql);
		oci_execute($pendingCheckEx,OCI_DEFAULT);
		while ($pendingCheckRow = oci_fetch_array($pendingCheckEx, OCI_ASSOC+OCI_RETURN_NULLS)) {
			 $penDueDate = $pendingCheckRow['DUE_DATE'];
			 $pendingProcess = $pendingCheckRow['PENDING_PROCESS'];
			 $discDate = $pendingCheckRow['DISC_DATE'];
			 
		}
		echo '<br /><br />Due Date : '.$penDueDate;
		echo '<br />Pending Process : '.$pendingProcess;
		echo '<br />Disc Date : '.$discDate;
	}//-----End----0002
}//End------0001
?>
