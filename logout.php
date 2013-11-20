<?php
/*
*	@authore: Kazi Sanghati
*	Company: OSS Japan Bangladesh Ltd.
*	URL: http://ossjb.com
*	Date: 2012-Jun-23
*	**********************************
*/
session_start();
// Force script errors and warnings to show on page in case php.ini file is set to not display them
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Destroy the session variables
session_destroy();
header("location: index.php");
 
?> 