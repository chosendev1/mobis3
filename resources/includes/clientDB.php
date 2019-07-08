<?php
/*@filename: clientDB.php
 *@date: 2015-02-28
 *@author: noah nambale [namnoah@gmail.com]
 */
 require_once("app/libraries/CAP_session.php");
 require_once("resources/conf.php");
 //$default_settings_['dbName'] = "mobis_cleanDB";
 $default_settings_['dbName'] = DB_NAME;
 $default_settings_['dbUser'] = DB_USER;
 $default_settings_['dbPass'] = DB_PASSWORD;
 CAP_session::init();
 $uid = CAP_session::get('userId');
 if(isset($uid)){
 	 
 	
 	require_once("resources/includes/libinc.php");
 	$default_settings_['dbName'] = fetchDB('localhost',$default_settings_['dbUser'],$default_settings_['dbPass'],$default_settings_['dbName']);
 	 
 	if($default_settings_['dbName'] !== "" && $default_settings_['dbName'] !== 0 && $default_settings_['dbName'] !== DB_NAME)
 		$default_settings_['dbName'] = DB_PREFIX.$default_settings_['dbName'];
 	//die(DB_PREFIX.$default_settings_['dbName'] );
 }
?>
