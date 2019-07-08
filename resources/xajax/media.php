<?php
$xajax->registerFunction("dummyFunction");
function dummyFunction(){
	$res = new xajaxResponse();
	//$res ->alert("they called me ajax");
	$content='<h3> this is dummy data posted the display_div view</h3>';
	$res->assign("display_div","innerHTML",$content);
	return $res;}
?>
