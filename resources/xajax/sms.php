<?php
$xajax->registerFunction('compose');
$xajax->registerFunction('getMessages');
$xajax->registerFunction('addSMSGroupForm');
$xajax->registerFunction('getSMSgroups');
$xajax->registerFunction("send");
$xajax->registerFunction("initSend");
$xajax->registerFunction("addNums");
$xajax->registerFunction("removeNums");
$xajax->registerFunction("addSchedule");
$xajax->registerFunction("smsReport");
$xajax->registerFunction("viewMsg");


function compose(){
	$res = new xajaxResponse();
	$data =  '
			<form method="post" action="sms/sendSMS" enctype="multipart/form-data" class="panel form-horizontal form-bordered"><div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h4 class="semibold text-primary mt0 mb5"> Compose SMS</h4>
                                               		 <p class="text-default nm"></p>
                                           	 </div>
                                        </div>';
	$data .='<div class="form-group">
                    <label class="col-sm-3 control-label">Phone No(s).:</label><div class="col-sm-6"><span><input name="to_" id="to_" class="form-control"  value="256" maxlength="12" type="text" />(Optional)  </span></div></div>';
	
	$data .='<div class="form-group">
                    <label class="col-sm-3 control-label">Upload No List:</label> <div class="col-sm-6"><span><input type="hidden" name="MAX_FILE_SIZE" value="20000">
					    <input class="form-control"  name="userfile" type="file" id="userfile"> (Optional)<br/><p align="auto"class="quick_info">Extension Supported - CSV, TXT, XLS (Max Size: 2MB)</span></div></div>';
	$data .='<div class="form-group">
                    <label class="col-sm-3 control-label">Send to group:</label><div class="col-sm-6"><span><select class="form-control"  id="groups" name="groups" onChange="xajax_listSender(this.value)">
						'.libinc::getGroups().'
						</select> (Optional)</span></div></div>';
	$data .='<div class="form-group">
                    <label class="col-sm-3 control-label">Sender:</label> <div class="col-sm-6"><span><div id="senderDiv"><input name="sender" id="sender" class="form-control"  value="" maxlength="12" type="text" /></div>(Optional)  </span></div></div>';
	$data .='<div class="form-group">
                    <label class="col-sm-3 control-label">Send Schedule Alerts: </label><div class="col-sm-6"><span> <input name="schedule" id="schedule" type="checkbox" value="yes" tabindex="35" onclick="xajax_addSchedule(this.checked);" /></span></div></div>';	
	$data .='<div class="form-group">
                    <label class="col-sm-3 control-label"></label><div class="col-sm-6"><span><div id="scheduleDiv"> </div></span></div></div>';
	$data .='<div class="form-group">
                    <label class="col-sm-3 control-label">Message:</label><div class="col-sm-6"><span><textarea name="msg" id="msg" class="form-control"  cols="21" rows="5" maxlength="160" onKeyUp="toCount(\'msg\',\'sBann\',\'{CHAR} characters left\',160);"></textarea>
						<div align=""><span class="minitext style1" id="sBann">160 characters left.</span></div></span></div></div>';
	$data .='<div class="panel-footer"><input id="button1" class="btn btn-primary btn-large"  name="button1" type="submit" value="Send" /> | <input class="btn btn-large" id="button2" type="reset" /></div>
				</form>';

	$res->assign("display_div","innerHTML",$data);
                return $res;
}


function send($to,$msg,$filePath,$groups,$sender){
	$res = new xajaxResponse();
	//$res->alert("Atleast i was called");
	//return $res;
	require_once("resources/includes/lib.inc.php");
	$sms = new sendSMS();
	
	//preg_match("/(\d{9}$)/", $to, $arr);
	//if ($arr[1] <> '')
		//$sms->addPhone("256".$arr[1]);
		//$res->alert($to);
		if(strlen($to) >= 9 && preg_match( '/[0-9]/', $to )){
			$sms->addPhone($to);
			//$res->alert(count($sms->phoneArray));
		}
		$sms->setSenderId($sender);
		$sms->setMessage($msg);
		if($groups <> ''){
			$sms->addGroup($groups);
		}
	$nums = count($sms->phoneArray);
	if($filePath <> ''){
		$sms->setFilePath($filePath);
		$nums = count($sms->getFileContent());
	}
	/*if($nums > getMessageLeft($_SESSION['sess_id'])){
		$res->alert("You have insufficient funds to send ".$nums." messages, Please recharge your account to proceed.");
		return $res;
	}*/
	$res->confirmCommands(1,"You are about to send ".$nums." messages.\nDo you want to proceed and send?");
	$res->call("xajax_initSend",$sms->senderId,$sms->message,$sms->phoneArray,$groups);
	//$sms->relayMultiple();
	//$res->alert(count($sms->phoneArray));
	//$res->alert($sms->phoneArray[0]);
	//$res->alert("to: ".$to." msg: ".$msg." file: ".$filePath);
	//$res->alert("Sent: ".$nums." messages");
	return $res;
}

function initSend($sender,$message,$phoneArray,$gId){
	require_once("resources/includes/lib.inc.php");
	$sms = new sendSMS();
	$sms->setSenderId($sender);
	$sms->setGroupId($gId);
	$sms->setMessage($message);
	$sms->phoneArray = $phoneArray;
	$sms->setUID($_SESSION['sess_id']);
	$res = new xajaxResponse();
	//$res->alert($sms->relayMultiple());
	$res->alert($sms->relayXML());
	/*$nums = "";
	foreach($phoneArray as $p){
		!is_array($p) && $p <> "" && preg_match('/[0-9]/',$p) ? $nums .= $p.", " : NULL;
	}
	$res->alert("Sent: ".$nums." messages");*/
	return $res;
}

/**
*SMS Logs
 *
 */
function smsReport($rep="outbox",$page=1,$num=10){
	$strRep = checkLogin('1,2') != TRUE ? "select * from outbox where user_id='".$_SESSION['sess_id']."' order by timestamp desc" : "select * from outbox order by timestamp desc";
	if($rep=="inbox")
		$strRep = "select * from inbox order by timestamp desc";
	$res = new xajaxResponse();
	//$res->alert("They called me!");
	
	$db = new SQLDB($strRep);
	$db->connect();
	//$smss = $db->SQLDataAdapter();
	$result = mysql_query($strRep);
	$no_sms = mysql_num_rows($result);
	//$smss = 
	//$no_sms = count($smss);
	//$res->alert($no_sms);
	
	$i=($page-1)*$num;
	/*$s = new paging($smss,$num);
	$s->setPage($page);
	$pages = $s->getPages();
	$sms = $s->getData();*/
	$new_query = $strRep." limit ".$i.",".$num;
	$sms = mysql_query($new_query);
	$pages = ceil($no_sms/$num);
	//$res->alert($sms);
	//return $res;
	$data = pageheaderdesc("SMS ".ucfirst($rep),"").'
                	<table width="70%" cellpadding="0" cellspacing="0" border="0">
				<thead>
				<tr>
					<td colspan="6" align="right"><a href="#" onclick="xajax_compose();">[ Compose New ]</a></td>
				</tr>
				<tr>
				<td>#No</td>
				<td>Sender</td>
				<td>Phone</td>
				<td>Date</td>
				<td>Status</td>
				<td>Action</td>
				</tr>
				</thead>
				<tbody>
							';
//0704600665 Mark Kyeyune	mysql_escape_string(utf8_encode();
	if($no_sms > 0){
	
		while($row = mysql_fetch_array($sms)){
			$data .= '<tr>
                            	<td class="a-center" >'.($i+1).'</td>
                            	<td>'.$row['sender'].'</a></td>
                                <td>'.$row['phone'].'</td>';
			
				$data .= '<td>'.$row['timestamp'].'</td>
                                	 <td>'.$row['status'].'</td>
					 <td><a href="#" onclick=\'xajax_viewMsg("'.$row['message'].'","'.$row['timestamp'].'");\'><img src="assets/images/icons/Info-icon.png" title="Show Message" width="16" height="16" /></a>  <a href="#" onclick=\'xajax_viewMsg(\''.$row['message'].'\',\''.$row['timestamp'].'\');\'><img src="assets/images/icons/delete-icon.png" title="Delete Message" width="16" height="16" /></a></td>
					 </tr>';
			$i++;
		}
	}
	$data .= '<tr><td colspan=10><div id="msgDiv"></div></td></tbody>
					</table>
					
                    <div id="pager">
                    	Page'; 
                    	if($page >1)
                    	$data .='<a href="#"><img src="assets/images/icons/arrow_left.gif" width="16" height="16" onclick = \'xajax_smsReport("'.$rep.'",'.($page-1).','.$num.');\'/></a>';
                    	$data .=' 
                    	<input size="1" value="'.$page.'" type="text" name="page" id="page" onblur=\'xajax_smsReport("'.$rep.'",this.value,'.$num.');\' onkeypress="runScript(event);" /> ';
                    	if($page < $pages)
                    		$data .='<a href="#"><img src="assets/images/icons/arrow_right.gif" width="16" height="16" onclick = \'xajax_smsReport("'.$rep.'",'.($page+1).','.$num.');\' /></a>';
                    	
                    $data .='	of '.$pages.'
                    pages | View <select name="view" id="view" onchange=\'xajax_smsReport("'.$rep.'",1,getElementById("view").value);\'>
                    			<option>'.$num.'</option>
                    				<option>10</option>
                                    <option>20</option>
                                    <option>50</option>
                                    <option>100</option>
                    			</select> 
                    per page | Total <strong>'.$no_sms.'</strong> records found
                    </div>
                    </div>';
                    
       $res->assign('content','innerHTML',$data);
       return $res;	
}

function viewMsg($msg,$time){
	$res = new xajaxResponse();
	//$res->alert($msg);
	$content ='<table border="0" cellspacing="0" cellpadding="1">
		<thead>
		<tr>
			<th>View Message  - date: '.$time.'</th>
		</tr>
		</thead>
		<tr>
			<td>'.$msg.'</td>
		</tr>
	</table>';


	$res->assign("msgDiv","innerHTML",$content);
	return $res;
}
	
/*function addSchedule($isSchedule){
	$res = new xajaxResponse();
	if($isSchedule=="true"){
	//$res->alert("u checked me");
		require_once("view/schedule-form.php");
		$res->assign("scheduleDiv","innerHTML",scheduleForm());
	}
	else
		$res->assign("scheduleDiv","innerHTML","");
		//$res->call("xajax_sendForm",$isSchedule);
	return $res;
}*/

function addSchedule($isSchedule){
	$res = new xajaxResponse();
	if($isSchedule=="true"){
		$data = "<input type='checkbox' id='now' name='now' CHECKED> Send Now and on<table>
	<tr>
		<td>From: </td>
		<td><input type='text' name='fromdate' id='fromdate' value='' ></td>		
		<td>to: </td>
		<td><input type='text' name='todate' id='todate' value='' ></td>
		<td> At:".hour("schedTo_","")."</td>
	</tr>";	
	$res->script("createCal('fromdate');");
	$res->script("createCal('todate');");

$data .= "<tr>
			<td colspan='2'><input type='checkbox' id='mon' name='mon' CHEKED> Monday</td>
			<td colspan='3'><input type='checkbox' id='fri' name='fri' CHEKED> Friday</td>
		</tr>";
$data .= "<tr>
			<td colspan='2'><input type='checkbox' id='tue' name='tue' CHEKED> Tuesday</td>
			<td colspan='3'><input type='checkbox' id='sat' name='sat' CHEKED> Saturday</td>
		</tr>";
$data .= "<tr>
			<td colspan='2'><input type='checkbox' id='wed' name='wed' CHEKED> Wednesday</td>
			<td colspan='3'><input type='checkbox' id='sun' name='sun' CHEKED> Sunday</td>
		</tr>";
$data .= "<tr>
			<td colspan='2'><input type='checkbox' id='thur' name='thur' CHEKED> Thursday</td>
			<td colspan='3'></td>
		</tr>
	<table>";
	
		$res->assign("scheduleDiv","innerHTML",$data);
	}
	else
		$res->assign("scheduleDiv","innerHTML","");
		//$res->call("xajax_sendForm",$isSchedule);
	return $res;
}




?>
