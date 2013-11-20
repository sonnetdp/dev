<?php
/*
*	@authore: Kazi Sanghati
*	Company: OSS Japan Bangladesh Ltd.
*	URL: http://ossjb.com
*	Date: 2012-Jul-25
*	**********************************
*
*/

session_start();

if($_SESSION['username']==''){
	header("location:".BASEPATH."index.php");
	} else {
include(BASEPATH.'config_pms.php');	
?>
        

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content = "width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" name = "viewport" />
<title>Mobile Interface | Bill Pay</title>
<link href="<?php echo URL; ?>style/main.css" rel="stylesheet" type="text/css"/>
<style type="text/css">

</style>
</head>

<body>

<div id="main">
	<div id="logo"><a href="/"><img src="<?php echo URL; ?>images/cacts-logo.gif" width="80" height="39" alt="logo" border="0" style="padding-bottom:5px;"/></a></div>

	<div style="padding:15px; background-color:#f2f2f2;-moz-border-radius:6px; -webkit-border-radius: 6px;">
		<?php 
			$msg = $_SESSION['msg'];
			if($msg){
						echo '<font color="#FF0000">'.$msg.'</font>';
						unset($_SESSION['msg']);
					}
		?>
        <form action="include/bill_pay_process.php" method="post" enctype="multipart/form-data">
        	<div id="label">Bill No:</div>
             <input type="text" name="billno"  class="input"/>
            <div id="label">Amount:</div>
            <input name="billAmt" type="text" class="input"/>
               <input type="submit" value="Pay" name="Submit" class="login"/></form>
               <div class="clear"></div>
              <div> <ul id="nav">
				<li><a href="main.php" title="Back">Back</a></li>
                <!--li><a href="#" title="Process">Process</a></li-->
				<li><a href="logout.php" title="Log out">Log out</a></li>
				

				</ul>
             </div>  
          
        </div>
 		<?php include(BASEPATH."footer.php");?>
    </div>
</div>

</body>
</html>
<?php
	}
	?>
