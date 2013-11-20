<?php
/*
*	@authore: Kazi Sanghati
*	Company: OSS Japan Bangladesh Ltd.
*	URL: http://ossjb.com
*	Date: 2012-Jul-25
*	**********************************
*
*/


?>

		<?php include("header.php");?>
    	<div id="content">
        <form action="" method="post" enctype="multipart/form-data">
        	<table cellpadding="0" cellspacing="0" border="0">
            	<tr>
                	<td>Bill Amount:</td>
                  <td><input type="text" name="billno" value="" size="15" /></td>
                </tr>
                <tr>
                	<td>Acc No:</td>
                  <td><input name="amnt" type="text" value="" size="15" /></td>
                </tr>
                <tr>
                	<td>Consumer's Name:</td>
                  <td><input name="name" type="text" value="" size="15" /></td>
                </tr>
                <tr bgcolor="#edd4ff">
                	<td align="right">Pay:</td>
                    <td><a href="#">Yes</a> :: <a href="main.php">No</a></td>
                </tr>
        	</table></form>
        </div>
        <?php include("footer.php");?>
    </div>
  </div>
</div>
</body>
</html>
