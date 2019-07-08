<?php
$xajax->registerFunction("dummyCall");
$xajax->registerFunction("getHierachyData");


function getHierachyData($parentId=0,$div="div",$name="select"){
	$res = new xajaxResponse();
	$data = libinc::getCountryData($parentId);
	$cbDiv = "";
	$cbName = "";
	switch($name){
		case "district":
			$cbDiv = "sectorDiv";
			$cbName = "sector";
			break;
		case "sector":
			$cbDiv = "cellDiv";
			$cbName = "cell";
			break;
		case "cell":
			$cbDiv = "villageDiv";
			$cbName = "village";
		default:
			break;
			
	}
	$content = '<select class="form-control" name="'.$name.'" id="'.$name.'"  onchange="xajax_getHierachyData(this.value,\''.$cbDiv.'\',\''.$cbName.'\')">';
	//if($callBackDiv == "" || $callBackName =="")
		//$content = '<select class="form-control" name="'.$name.'" id="'.$name.'" >';
	$content .= '<option value="">Select...</option>';
	foreach($data as $key => $value)
		$content .= '<option value="'.$key.'">'.$value.'</option>';
	$content .= '</select>';
	$res->assign($div,"innerHTML",$content);
	return $res;
}

function dummyCall(){
	$res = new xajaxResponse();
	$res->alert("I am so dumb yet they called me");
	
	return $res;
}
?>
