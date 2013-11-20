<?php
session_start();
include('../../config_pms.php');

//echo $sysdate;
if($_SESSION['username']==''||$_SESSION['password']==''){//--------------Start if 001
	header("location:".URL."index.php");
}//End of 001


else if($_SESSION['username']!='' && $_SESSION['password']!='' && $_SESSION['pbs_user_is_loged_in'] == 'loged')
{
	$dbUser 	= $_SESSION['username'];
	$dbPass 	= $_SESSION['password'];
	$geoCode	= $_SESSION['geoCode'];
	$agentId	= $_SESSION['agentId'];
	$userName	= $_SESSION['userName'];
	$startDate 	= $_POST['startDate'];
	$endDate 	= $_POST['endDate'];
	include(INC."db_conn.php");
	include(INC."functions.php");
	$pbs = new PbsCal;
	
	$startDateToTime = date('d-M-Y',strtotime($startDate));
	$endDateToTime = date('d-M-Y',strtotime($endDate));
	//echo $dateToTime;exit;
	//$sysdate = $pbs->oraSysDate('DD-MON-RRRR');
	//$balance = $pbs->balanceOfAgent($agentId,$geoCode);
include(DESK."pc_header.php");
include(INC."report/day_report.php");

//$summery = dayReport($agentId,$geoCode,$sysdate);
//var_dump($dayReport); exit;
?>
 
  <div id="main">
   <?php include(DESK."pc_nav.php"); ?>
    <div id="site_content">
      <div class="content">
	  
      <div id="select_date">
      	<form id="frm_select_date" action="" name="frm_select_date" method="post">
        	Selet Start Date: <input type="text" id="startDate" name="startDate"/>&nbsp;&nbsp;
            Selet End Date: <input type="text" id="endDate" name="endDate"/>
            <input type="submit" value="View Report" id="comon_btn"/>
        </form>
      </div>
      <input type="button" onclick="window.print();" value="Print Report" id="print_btn"/>
      
	  
	  <div class="print">
	  <div id="agent_info">
		  <div id="label">Retailer Name: <span style="color:red;"><?php echo $userName;?></span></div>
		  <div id="label">Current Balance:<span style="color:red;"> <?php echo $balance." Taka"; ?></span></div>
        </div>
			<div id="agent_info">
			<table width="948" border="1">
            <tr>
              <th scope="col">Scroll<br /> No.</th>
              <th scope="col">Account<br /> No.</th>
              <th scope="col">Book<br /> No.</th>
              <th scope="col">Bill<br /> No.</th>
              <th scope="col">Bill<br />Month-Year</th>
              <th scope="col">Net Bill</th>
              <th scope="col">VAT</th>
              <th scope="col">LPC</th>
              <th scope="col">Meter<br />Rent</th>
              <th scope="col">Misc.<br />Amount</th>
              <th scope="col">Total</th>
            </tr>
			<?php
			if(isset($endDateToTime) && isset($startDateToTime))
			{ 
				//echo $sysdate;exit;
				$summery = dayRangeReport($agentId,$geoCode,$startDateToTime,$endDateToTime);
			}
			else
			{
				echo '<tr>';
				echo '<td colspan="11">Please select a date to view the report<br />রিপোর্ট দেখার জন্য তারিখ নির্বাচন করুন।</td>';
				echo '</tr>';
			}
			?>   
          	<tr>
              <td colspan="4">Number of Bill Collected Today: <?php echo $summery['totalCount'];?> </td>
              <td colspan="4">Number of Stamped Bill Collected: <?php echo $summery['stampedBill'];?></td>
              <td colspan="2"><div align="right">Day Total Amount: </div></td>
              <td colspan="1"><div align="right"><?php echo $summery['dayTotal'];?></div></td>
            </tr>
          </table>
		  </div>
          </div>
      </div>
    </div>
    <div style="clear:both">  
<?php
include(DESK."footer.php");
}
else{
	$_SESSION['msg']="Login User/Password Incorrect";
		header("location:".URL."index.php");
}
?>
