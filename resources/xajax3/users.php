<?php
/*@filename: users.php
 *@date: 2014-01-11 18:22
 *@author: Noah Nambale
 */
 
 $xajax->registerFunction("listUsers");
 $xajax->registerFunction("registerUserStatus");
 function listUsers(){
 	$res = new xajaxResponse();
 	//$res->alert("hello users");
 	$um = new users_model();
 	$table = new tables();
 	$content = $table->basic("Users",$um->getUsers()->result(),array("Username","Role","GroupName"));
 	$res->assign("display_div","innerHTML",$content);
 	return $res;
 }
 
 function registerUserStatus($status){
 	$res = new xajaxResponse();
 	$res->alert($status);
 	return $res;
 }
?>
