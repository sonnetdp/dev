<?php
/*
*	Main Cofiguration File
*	Date: 2012-04-15
*	To Check Data Value for specific page Just Put 'Y' in  that variable value.
*	Example If you want to check pay_with_lpc.php Then $pay_with_lpc = 'Y';
*	Last Update: 2013-Jan-25 : 0138
*	
*/
//HHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHH Database Configuration HHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHH


$db_name		= 'PMS';			//--------| DB Database Scheema User Name
$db_host		= '192.168.101.241';//--------| Insert IP or domain of Database Server
$db_service_name 	= 'pms';			//--------| Oracle SID 
	

//============================= Directory Name ==================================================================

$dirName 		= 'dev';			//--------| Directory Name. If in root then leave it blank.
$sessionTime 		= 15;				//--------| Session Time in Minute


//============================= Only for Debug use this Test Configuration =======================================
$test_YesNo		= 'N';
$bill_pay_process 	= 'N';
$bill_cat_101	 	= 'N';
$pay_with_lpc 		= 'N';
$pay_without_lpc	= 'N';
$bill_pay_insert	= 'N';
//------------------------------------------ End of Test Configuration -------------------------------------------


//================================== DO NOT CHANGE ANYTHIN BELLOW THIS LINE ======================================
//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX


if (DIRECTORY_SEPARATOR=='/') 
  $base_path = dirname(__FILE__).'/'; 
else 
  $base_path = str_replace('\\', '/', dirname(__FILE__)).'/'; 

define('BASEPATH',$base_path);
define('INC',$base_path.'include/');
define('MOBI',$base_path.'mobile/');
define('IPAD',$base_path.'ipad/');
define('DESK',$base_path.'pc/');

if($dirName != '')
	$dirName = $dirName.'/';

$baseUrl 	= "http://" . $_SERVER['HTTP_HOST'] . '/'.$dirName;
$baseUrlInc	= $baseUrl . 'include/';
$baseUrlPc 	= $baseUrl . 'pc/';
$baseUrlMob 	= $baseUrl . 'mobile/';
$baseUrlPad 	= $baseUrl . 'ipad/';

define('URL',$baseUrl);
define('URLINC',$baseUrlInc);
define('URLPC',$baseUrlPc);
define('URLMOB',$baseUrlMob);
define('URLPAD',$baseUrlPad);

//============================ DB Name Define =====================================================
define('DB_NAME',$dbName);

$_SESSION['test_all'] 		= $test_YesNo;
$_SESSION['bill_pay_process'] 	= $bill_pay_process;
$_SESSION['bill_cat_101'] 	= $bill_cat_101;
$_SESSION['pay_with_lpc'] 	= $pay_with_lpc;
$_SESSION['pay_without_lpc'] 	= $pay_without_lpc;
$_SESSION['bill_pay_insert'] 	= $bill_pay_insert;
/*xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx*/
$_SESSION['time_limit'] 	= $sessionTime;

?>
