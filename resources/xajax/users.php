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
	$tables = $um->getUsers()->result();
	foreach($tables as $row)
	{
		$data[] = array('Username'=>$row->Username,
		                 'GroupName'=>$row->GroupName,
						 'Role'=>$row->Role,
						 'edit'=>'<a href="'.base_url().'user/addUser/'.$row->userId.'">Edit</a>');
	}
 	$content = $table->basic("Users",$data,array("Username","Role","GroupName","edit"));
 	$res->assign("display_div","innerHTML",$content);
 	return $res;
 }
 
 function registerUserStatus($status){
 	$res = new xajaxResponse();
 	$res->alert($status);
 	return $res;
 }
?>
