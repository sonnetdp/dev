<?php
/*
*	@authore: Kazi Sanghati
*	Company: OSS Japan Bangladesh Ltd.
*	URL: http://ossjb.com
*	Date: 2012-Apr-20
*	**********************************
*/

include('config_pms.php');
require_once(INC.'mobile_device_detect.php');
//mobile_device_detect(true,'ipad/ipadindex.php',true,true,true,true,true,'mobile/mobileindex.php',false);
mobile_device_detect(true,'ipad/ipad_index.php',true,true,true,true,true,'mobile/mobile_index.php','pc/pc_index.php');
?>