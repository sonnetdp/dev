<?php
/*
*	@authore: Kazi Sanghati
*	Company: OSS Japan Bangladesh Ltd.
*	URL: http://ossjb.com
*	Date: 2012-Sep-22
*	**********************************
*
*/
session_start();
include("../config_pms.php");

if($_SESSION['username']==''||$_SESSION['password']==''){//--------------Start if 001
	header("location:".URL."index.php");
}//End of 001

else if($_SESSION['username']!='' && $_SESSION['password']!='' && $_SESSION['pbs_user_is_loged_in'] == 'loged')
{
	include(INC."functions.php");
	$success_scroll_no = $_SESSION['success_scroll_no'];
	if($success_scroll_no !='')
	{
		$success_msg = $success_scroll_no;
	}
	else
	{
		$success_msg = '';
	}
?>
        

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content = "width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" name = "viewport" />
<title>Mobile Interface | Bill Pay</title>
<link href="<?php echo URL; ?>/style/mobile.css" rel="stylesheet" type="text/css"/>
<style type="text/css">

</style>
</head>

<body>

<div id="main">
	<div id="logo"><a href="/"><img src="<?php echo URL; ?>/images/cacts-logo.gif" width="80" height="39" alt="logo" border="0" style="padding-bottom:5px;"/></a></div>

	<div style="padding:15px; background-color:#f2f2f2;-moz-border-radius:6px; -webkit-border-radius: 6px;">
		<?php 
			$msg = $_SESSION['msg'];
			if($msg){
					echo '<font color="#FF0000">'.$msg.'</font>';
					unset($_SESSION['msg']);
					unset($msg);
			}
			
			$success_msg = $_SESSION['success_scroll_no'];
			if(isset($success_msg)){
				echo '<div id="label_msg"><font color="#FF0000">'.$success_msg.'</font></div>';
				unset($_SESSION['success_scroll_no']);
				unset($success_msg);
			}
		?>
        
        
        <form action="<?php echo URLINC; ?>bill_pay_process.php" method="post" enctype="multipart/form-data">
        	<div id="label">Bill No:</div>
             <input type="text" name="billno"  class="input"/>
            <div id="label">Amount:</div>
            <input name="billAmt" type="text" class="input"/>
               <input type="submit" value="Pay" name="Submit" class="login"/></form>
               <div class="clear"></div>
              <div> <ul id="nav">
				<li><a href="<?php echo URLMOB; ?>mobile_main.php" title="Back">Back</a></li>
                <!--li><a href="#" title="Process">Process</a></li-->
				<li><a href="<?php echo URL; ?>logout.php" title="Log out">Log out</a></li>
				

				</ul>
             </div>  
          
        </div>
 

        <?php include("mobile_footer.php");?>
    </div>
</div>

</body>
</html>
<?php
	}
	?>
