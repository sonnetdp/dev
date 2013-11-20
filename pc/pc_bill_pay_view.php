<?php
/*
*	@authore: Kazi Sanghati
*	Company: OSS Japan Bangladesh Ltd.
*	URL: http://ossjb.com
*	Date: 2012-Nov-05
*	**********************************
*
*/
session_start();
include('../config_pms.php');

if($_SESSION['username']==''||$_SESSION['password']==''){//--------------Start if 001
	header("location:".URL."index.php");
}//End of 001


else if($_SESSION['username']!='' && $_SESSION['password']!='' && $_SESSION['pbs_user_is_loged_in'] == 'loged')
{
	$dbUser = $_SESSION['username'];
	$dbPass = $_SESSION['password'];
	include(INC."db_conn.php");
	include(INC."functions.php");
	$pbs = new PbsCal;
	
	$agentId 	= $_SESSION['agentId'] ;
	$userName 	= $_SESSION['userName'];
	$geoCode	= $_SESSION['geoCode'];
	//$balance = $pbs->balanceOfAgent($agentId,$geoCode);
	include("pc_header.php");
?>

  <div id="main">
    <?php include("pc_nav.php"); ?>
    <div id="site_content">
      <div class="content">
      <div id="agent_info">
          <div id="label">Retailer Name: <span style="color:red;"><?php echo $userName;?></span></div>
          <!--div id="label">Current Balance:<span style="color:red;"> <?php echo $balance." Taka"; ?></span></div-->
          <?php 
              $msg = $_SESSION['msg'];
              if(isset($msg)){
                          echo '<div id="label_msg"><font color="#FF0000">'.$msg.'</font></div>';
                          unset($_SESSION['msg']);
                          unset($msg);
                      }
          ?>
      </div>
      <div id="agent_info">  
        <form name="form1" method="post" action="<?php echo URLINC;?>bill_pay_process.php">
        <table width="302" border="0">
  		  <tr>
            <td>Bill Number:</td>
            <td><input type="text" id="billno" name="billno"></td>
          </tr>
          <tr>
            <td>Bill Amount:</td>
            <td><input type="text" id="billAmt" name="billAmt"></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input type="submit" id="billpay" name="billpay" value="Pay"></td>
          </tr>
        </table>
        </form>
		</div>
      </div>
    </div>
<?php
include(DESK."footer.php");
}
else{
	$_SESSION['msg']="Login User/Password Incorrect";
		header("location:".URL."index.php");
}
?>
