<?php
session_start();
include("config_pms.php");
$dbUser = strtoupper($_POST['username']);
$dbPass = $_POST['password'];
$device = $_POST['device'];
if($dbPass == '' || $dbUser =='')
{
	$_SESSION['grp'] = 'Please Insert both User Name and Password.';
		
		header("location:index.php");
}
	
$_SESSION['device'] = $device;
//Database Configuration
include(INC."db_conn.php");
if (!$c1) {
	include(BASEPATH."index.php");
   exit;	
} 
else 
{
	
	//-------Check Group---------------	
	$groupSql = "SELECT grp FROM PMS.S_USER_AUTH_MASTER WHERE USER_ID= '$dbUser'";
	$grpropEx = oci_parse($c1, $groupSql);
	oci_execute($grpropEx, OCI_DEFAULT);			
	while ($grprow = oci_fetch_row($grpropEx)) {
	  foreach($grprow as $grpropItem) {
		 $group= $grpropItem;
		 //echo $group;
		}
	}
	$group = strtoupper($group);
	if($group != 'UIC'){
		$_SESSION['grp'] = 'Sorry, This interface is not for you.<br /> Only UIC Agent can use this interface.';
		
		header("location:index.php");
	}
	else
	{
		$_SESSION['username'] = $dbUser;
		$_SESSION['password'] = $dbPass;
		$_SESSION['pbs_user_is_loged_in'] = 'loged';
		$_SESSION['grp'] = '';
		$_SESSION['timeout'] = time();
		
		header("location:".$device."/".$device."_main.php");
		
	}

}

?>