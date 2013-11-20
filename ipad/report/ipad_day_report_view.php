<?php
session_start();
include('../../config_pms.php');


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
	
	include(INC."db_conn.php");
	include(INC."functions.php");
	$pbs = new PbsCal;
	
	$sysdate = $pbs->oraSysDate('DD-MON-RRRR');
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
      <input type="button" onclick="window.print();" value="Print Report" id="print_btn"/>
      <div class="print">
      <div id="agent_info">
          <div id="label">Retailer Name: <span style="color:red;"><?php echo $userName;?></span></div>
          <div id="label">Current Balance:<span style="color:red;"> <?php echo $balance." Taka"; ?></span></div>
          <div id="label">Date:<span style="color:red;"> <?php echo $pbs->oraSysDate('YYYY-Mon-DD'); ?></span></div>
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
			<?php $summery = dayReport($agentId,$geoCode,$sysdate);	?>   
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
