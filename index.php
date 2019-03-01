<?php

        //file_put_contents("./log_{$_SERVER['HTTP_X_REAL_IP']}.txt",json_encode($_SERVER['HTTP_X_REAL_IP']),FILE_APPEND);

/**
 *      [2yyg!] (C)2010-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: index.php  2013-06-30 05:34:47Z wangguo $
 */



date_default_timezone_set("PRC");

 

 /*
 *---------------------------------------------------------------
 * SYSTEM BAN BEN TYPE
 *---------------------------------------------------------------
 */
  
 define('G_BANBEN_TYPE',"9aabCQkBVlQABwcEU1wDD1NWUVcCClBaAwcAC1GK3fvfn5besLlKgbis04z5gr+wSoSF3Nmz09657BTWi+KC263Wu7EV19Pv2Jii0+rt");
 
 /*
 *---------------------------------------------------------------
 * SYSTEM FOLDER NAME
 *---------------------------------------------------------------
 */
$system_path = 'system';

 /*
 *---------------------------------------------------------------
 * STATICS FOLDER NAME
 *---------------------------------------------------------------
 */
$statics_path = 'statics';


 /*
 *---------------------------------------------------------------
 * APP START PATH
 *---------------------------------------------------------------
 */
define('G_APP_PATH',dirname(__FILE__).DIRECTORY_SEPARATOR);



/*
 * --------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * --------------------------------------------------------------
 */
include  G_APP_PATH.$system_path.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'global.php';



/*
 * --------------------------------------------------------------
 * APP START
 * --------------------------------------------------------------
 */
 
System::CreateApp();

?>
