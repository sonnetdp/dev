<?php
include(BASEPATH."config_pms.php");
$dbHost = $db_host;
//$dbHost = '192.168.101.212';
//$serviceName = 'orcl';
$serviceName = $db_service_name;
$db ="(DESCRIPTION =(ADDRESS =(PROTOCOL = TCP)(HOST = $dbHost)(PORT = 1521))(CONNECT_DATA =(SERVER = DEDICATED)(SERVICE_NAME =$serviceName)))";
$c1 = oci_connect($dbUser,$dbPass,$db);

?>