<?php
$xajax->registerFunction("ussdSettingsForm");
$xajax->registerFunction("saveussdSettings");

function ussdSettingsForm(){
 	$res = new xajaxResponse();
 	$p = "USSD Settings";
 	$smpp = new ussd();
	$gw = $smpp->getUSSDSettings();
	$gw_gw = "";
	$gw_uid = "";
	$gw_pass = "";
	$gw_gs = "";
	if(count($gw->result()) > 0){
		$gw2 = $gw->row();
		$gw_gw = $gw2->smpp_gateway;
		$gw_uid = $gw2->smpp_userId;
		$gw_pass = $gw2->smpp_password;
		$gw_gs = $gw2->smpp_globalSender;
	}
	$content .= 
			"<form method='post' action='settings/savesmppSettings' enctype='multipart/form-data' class='panel form-horizontal form-bordered'>";
$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h4 class="semibold text-primary mt0 mb5">USSD Settings</h4>
                                               		 <p class="text-default nm"></p>
                                           	 </div>
                                        </div><div id="responsediv" class="alert alert-dismissable alert-danger"></div>
                                        ';
	$content .='<div class="form-group">
                    <label class="col-sm-3 control-label">Gateway:</label>
                    <div class="col-sm-6"><span><input type="text" class="form-control" name="gw" id="gw" value="'.$gw_gw.'" required></span></div></div>';
	$content .='<div class="form-group">
                    <label class="col-sm-3 control-label">User Id::</label> <div class="col-sm-6"><span><input type="text" class="form-control" name="uid" id="uid" value="'.$gw_uid.'" required></div></div>';	
	$content .='<div class="form-group">
                    <label class="col-sm-3 control-label">Password:</label> <div class="col-sm-6"><span><input class="form-control" type="password" name="pwd" id="pwd" value="'.$gw_pass.'" required></div></div>';	
		
	$content .='<div class="panel-footer"><input type="button" class="btn btn-primary" name="save" id="save" value="Save" onclick=\'xajax_saveussdSettings(getElementById("gw").value,getElementById("uid").value,getElementById("pwd").value);\'></div>';
	
	
	//$content = pageheaderdesc("Settings","Message settings page disabled.");
	$res->assign("display_div","innerHTML",$content);
 	return $res;
 }
 
 function saveussdSettings($gw, $uid="", $pwd=""){
 	$res = new xajaxResponse();
 	$val = new ussd($gw, $uid, $pwd);
 	$errmsg = $val->setSMPPSettings();
 	if($errmsg==="success")
 		$res->assign("status","innerHTML","Saved Changes");
 	else
 		$res->assign("status","innerHTML",$errmsg);
 	return $res;	
 }
?>
