<?php
session_start();
include("../config_pms.php");
if($_SESSION['username']==''||$_SESSION['password']==''){
	header("location:".BASEPATH."index.php");
	}
else if($_SESSION['username']!='' && $_SESSION['password']!='' && $_SESSION['pbs_user_is_loged_in'] == 'loged')
{
	//echo 'pc_bill_confirmation-line-09';exit;
	$dbUser = $_SESSION['username'];
	$dbPass = $_SESSION['password'];
	include(INC."db_conn.php");
	include(INC."functions.php");
	$pbs = new PbsCal;
	if(!$c1){
		echo "Could not connect";
		exit;
	}
	$userName = $pbs->current_user_name($dbUser);	
	$balance = $pbs->current_user_balance($dbUser);
		
	$billNumber 		= $_SESSION['billNumber'];
	$billAmt 			= $_SESSION['billAmt'];
	$dom101bookNo		= $_SESSION['dom101bookNo'];
	$dom101acNo 		= $_SESSION['dom101acNo'];
include("pc_header.php");
?>

  <div id="main">
   <?php include("pc_nav.php"); ?>
	<div id="site_content">
      <div class="content">
		<div id="content-main">
			<div class="left">
                <div id="label"><strong>Retailer Name:</strong> <span style="color:red;"><?php echo $userName;?></span></div>
                <div id="label"><strong>Current Balance:</strong><span style="color:red;"> <?php echo $balance." "; ?></span>Taka</div>
                <div id="label"><strong>Bill Number: </strong><span style="color:#060;"><?php echo $billNumber;?></span></div>
                <div id="label"><strong>Bill Amount: </strong><span style="color:#060;"> <?php echo $billAmt." "; ?></span>Taka</div>
                <div id="label"><strong>Book Number: </strong></strong><span style="color:#060;"><?php echo $dom101bookNo;?></span></div>
                <div id="label"><strong>Account Number:</strong> <span style="color:#060;"> <?php echo $dom101acNo; ?></span></div>
                
             </div>
             
        	<div class="right"> 
            	<div id="label">Do You Want To Pay The Bill? </div><br /><br />
           		<a href="<?php echo URL;?>bill_pay_yes.php" title="Pay" class="button">Yes</a>
                <a href="<?php echo URLPC;?>pc_bill_pay_view.php" title="Not Pay" class="button">No</a>
			</div> 
            <div style="clear:both"></div> 
		</div>
        <div style="clear:both"></div>
<?php
include(DESK."footer.php");
}
else{
	$_SESSION['msg']="Login User/Password Incorrect";
		header("location:".BASEPATH."index.php");
}
?>