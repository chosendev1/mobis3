<?php
$xajax->registerFunction("approveClient");
$xajax->registerFunction("disapproveClient");
$xajax->registerFunction("deleteClientConfirm");
$xajax->registerFunction("deleteClient");

function approveClient($companyId){
	$res = new xajaxResponse();
	$h = new client_model();
	$s = $h->approveclient($companyId);
	//$res->alert("approve has been called ".$companyId."--");
	$content = $s == "success" ? "Succeffuly approved client!" : $s;
	$res->alert($content);
	$res->assign("status","innerHTML",$content);
	return $res;
}

function disapproveClient($companyId){
	$res = new xajaxResponse();
	$h = new client_model();
	$s = $h->disapproveclient($companyId);
	//$res->alert("Now disapprove has been called ".$companyId);
	$content = $s == "success" ? "Succeffuly disapproved client!" : $s;
	$res->alert($content);
	$res->assign("status","innerHTML",$content);
	return $res;
}

function deleteClientConfirm($companyId){
	$res = new xajaxResponse();
	$res->confirmCommands(1,"Are you sure you want to delete the client?");
	$res->call("xajax_deleteClient", $companyId);
	return $res;
}

function deleteClient($companyId){
	$res = new xajaxResponse();
	$s = $h->deleteClient($companyId);
	$content = $s == "success" ? "deleted client!" : $s;
	$res->alert($content);
	$res->script("location.reload()");
	return $res;
}

?>
