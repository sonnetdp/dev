<?php
session_start();
include('../../config_pms.php');


if($_SESSION['username']==''||$_SESSION['password']==''){//--------------Start if 001
	header("location:".BASEPATH."index.php");
}//End of 001


else if($_SESSION['username']!='' && $_SESSION['password']!='' && $_SESSION['pbs_user_is_loged_in'] == 'loged')
{
	$dbUser 	= $_SESSION['username'];
	$dbPass 	= $_SESSION['password'];
	$agentId	= $_SESSION['agentId'];
	$userName	= $_SESSION['userName'];
	
	include(INC."db_conn.php");
	include(INC."functions.php");
	$pbs = new PbsCal;
	$officeCode = $pbs->officeCode($dbUser);
	$sysdate = $pbs->oraSysDate('DD-MON-RRRR');
	//echo $officeCode; exit;
include(DESK."pc_header.php");
include(INC."report/agent_ledger_report.php");

$summery = agentLedgerReport($agentId,$officeCode);

foreach($summery as $row)
				{
					$name = $row['E_NAME'];
					$zela = $row['E_ZELA'];
					$thana = $row['E_THANA'];
					$union = $row['E_UNION'];
					$geoCode = $row['GEO_CODE'];
					$balanceLimit = $row['BALANCE_LIMIT'];
					$father = $row['E_F_NAME'];
					$mother = $row['E_M_NAME'];
					$appn = $row['APPN'];
					break;
				}

//$summery = dayReport($agentId,$geoCode,$sysdate);
//var_dump($dayReport); exit;
?>

  <div id="main">
   <?php include(DESK."pc_nav.php"); ?>
    <div id="site_content">
      <div class="content">
      <input type="button" onclick="window.print();" value="Print Report" id="print_btn"/>
      <div class="print">
          <table width="948" border="0">
          <tr>
            <td width="137" scope="col"><div align="right">Agent Name :</div></td>
            <td width="336" scope="col"><?php echo $name;?></td>
            <td width="121" scope="col"><div align="right">Agent Id :</div></td>
            <td width="325" scope="col"><?php echo $agentId;?></td>
          </tr>
          <tr>
            <td><div align="right">Father's Name :</div></td>
            <td><?php echo $father;?></td>
            <td><div align="right">Application No :</div></td>
            <td><?php echo $appn;?></td>
          </tr>
          <tr>
            <td><div align="right">Mother's Name :</div></td>
            <td><?php echo $mother;?></td>
            <td><div align="right">Office Code :</div></td>
            <td><?php echo $officeCode;?></td>
          </tr>
          <tr>
            <td><div align="right">Village :</div></td>
            <td><?php //echo $agentId;?></td>
            <td><div align="right">GEO Code :</div></td>
            <td><?php echo $geoCode;?></td>
          </tr>
          <tr>
            <td><div align="right">Union :</div></td>
            <td><?php echo $union;?></td>
            <td><div align="right">Balance Limit :</div></td>
            <td><?php echo $balanceLimit;?></td>
          </tr>
          <tr>
            <td><div align="right">Thana :</div></td>
            <td><?php echo $thana;?></td>
            <td><div align="right">Phone Office :</div></td>
            <td><?php //echo $agentId;?></td>
          </tr>
          <tr>
            <td><div align="right">District :</div></td>
            <td><?php echo $zela;?></td>
            <td><div align="right">Phone Res :</div></td>
            <td><?php //echo $agentId;?></td>
          </tr>
          <tr>
            <td><div align="right">Print Date :</div></td>
            <td><?php echo $sysdate;?></td>
          </tr>
          <tr>
            <td><div align="right">Present Address :</div></td>
            <td colspan="3"><?php //echo $agentId;?></td>
          </tr>
          
        </table>
        <table width="948" border="1">
            <tr>
              <th width="187" scope="col">Pay Date</th>
              <th width="286" scope="col">Credit Amount</th>
              <th width="280" scope="col">Debit Amount</th>
              <th width="167" scope="col">Balance</th>
            </tr>
			<?php 
				$bal = 0;
				$totalCr = 0;
				$totalDr = 0;
				//echo 'Test';
				$summery = agentLedgerReport($agentId,$officeCode);
				//$pbs->dumpVar($summery);
				foreach($summery as $row)
				{
					echo '<tr>';
					echo '<td>'.$row['PAYDATE'].'</td>';
					echo '<td>'.$row['CRAMT'].'</td>';
					echo '<td>'.$row['DRAMT'].'</td>';
					$bal = $row['CRAMT'] - $row['DRAMT'] + $bal;
					echo '<td>'.$bal.'</td>';
					echo '</tr>';
					$totalCr = $totalCr + $row['CRAMT'];
					$totalDr = $totalDr + $row['DRAMT'];
				}
			?>   
          	<tr bgcolor="#CCCC99">
              <td colspan="1" bgcolor="#CCCC99">Total </td>
              <td colspan="1" bgcolor="#CCCC99"><?php echo $totalCr;?></td>
              <td colspan="1" bgcolor="#CCCC99"><?php echo $totalDr;?></td>
              <td colspan="1" bgcolor="#CCCC99"><?php echo $totalCr - $totalDr;?></td>
            </tr>
          </table>
          </div>
      </div>
    </div>
    <footer>
      <p>Copyright &copy; <a href="http://www.cactsgroup.com">CACTS</a></p>
    </footer>
  </div>
  <p>&nbsp;</p>
  <!-- javascript at the bottom for fast page loading -->
  <script type="text/javascript" src="<?php echo URL; ?>js/jquery.js"></script>
  <script type="text/javascript" src="<?php echo URL; ?>js/jquery.easing-sooper.js"></script>
  <script type="text/javascript" src="<?php echo URL; ?>js/jquery.sooperfish.js"></script>
  <script type="text/javascript" src="<?php echo URL; ?>js/image_fade.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('ul.sf-menu').sooperfish();
    });
  </script>
</body>
</html>
<?php
}
else{
	$_SESSION['msg']="Login User/Password Incorrect";
		header("location:".URL."index.php");
}
?>
