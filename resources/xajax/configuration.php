<?php

$xajax->registerFunction("showSettings");
$xajax->registerFunction("editSettings");
$xajax->registerFunction("regBranch");
$xajax->registerFunction("saveSettings");
/*$xajax->registerFunction("showProvisions");
$xajax->registerFunction("editProvisions");
$xajax->registerFunction("updateProvisions2");
$xajax->registerFunction("updateProvisions");*/
$xajax->registerFunction("addBranchForm");

$xajax->registerFunction("insertBranch");
$xajax->registerFunction("addBranch");
$xajax->registerFunction("memberAccounts");
$xajax->registerFunction("saveAccounts");
$xajax->registerFunction("closeMonthForm");
$xajax->registerFunction("registerCloseMonth");
$xajax->registerFunction("overdraftForm");
$xajax->registerFunction("registeroverdraftsettings");
$xajax->registerFunction("regBudgetForm");
$xajax->registerFunction("pmtSettings");
$xajax->registerFunction("pmtRegSettings");

$xajax->registerFunction("passwordmgtForm");
$xajax->registerFunction("registerpassmgt");
$xajax->registerFunction("locktoday");
$xajax->registerFunction("schedule_today");

$xajax->registerFunction("add_saveproduct");
$xajax->registerFunction("list_saveproduct");
$xajax->registerFunction("insert_saveproduct");
$xajax->registerFunction("update_saveproduct");
$xajax->registerFunction("update2_saveproduct");
$xajax->registerFunction("delete_saveproduct");
$xajax->registerFunction("delete2_saveproduct");
$xajax->registerFunction("edit_saveproduct");

$xajax->registerFunction("add_loanproduct");
$xajax->registerFunction("list_loanproducts");
$xajax->registerFunction("insert_loanproduct");
$xajax->registerFunction("edit_loanproduct");
$xajax->registerFunction("update_loanproduct");
$xajax->registerFunction("update2_loanproduct");
$xajax->registerFunction("delete_loanproduct");
$xajax->registerFunction("delete2_loanproduct");

$xajax->registerFunction("showProvisions");
$xajax->registerFunction("editProvisions");
$xajax->registerFunction("updateProvisions2");
$xajax->registerFunction("updateProvisions");
$xajax->registerFunction("add_loanloss");
$xajax->registerFunction("insert_loanloss");
$xajax->registerFunction("list_loanloss");
$xajax->registerFunction("loadBranches");
$xajax->registerFunction("uploadCustomersForm");
$xajax->registerFunction("add_bulk");
$xajax->registerFunction("insert_bulk");
$xajax->registerFunction("list_bulk");
$xajax->registerFunction("delete_bulk");
$xajax->registerFunction("delete2_bulk");
$xajax->registerFunction("list_ind_post");
$xajax->registerFunction("openingBalance");
$xajax->registerFunction("insertOpeningBalance");
$xajax->registerFunction("listOpeningBalances");
$xajax->registerFunction("saccoSettings");
$xajax->registerFunction("openingBalMirror");
$xajax->registerFunction("updateOpeningBalance");
$xajax->registerFunction("deleteOpeningBalance1");
$xajax->registerFunction("deleteOpeningBalance2");
$xajax->registerFunction("shares_settings");
$xajax->registerFunction("insert_shares_settings");
$xajax->registerFunction("loan_charges");
$xajax->registerFunction("insert_loan_charges");
$xajax->registerFunction("charge_type");
$xajax->registerFunction("chargeAs");
$xajax->registerFunction("list_loan_charges");
$xajax->registerFunction("basedOn");
$xajax->registerFunction("basedOnAmount");
$xajax->registerFunction("list_shares_settings");
$xajax->registerFunction("list_ind_savings_posted");
$xajax->registerFunction("list_savings_uploads");

function passwordmgtForm(){
$resp = new xajaxResponse();
//$resp->assign("status","innerHTML","");
$selectcharge="";$selectpercent = "";
$close_m = mysql_query("select * from passwordmgt");
if(mysql_num_rows($close_m)>0){
$m = mysql_fetch_array($close_m);


$selectcharge=$m['expiredays'];

$starttime=$m['waketime'];

$endtime=$m['sleeptime'];
}

//get groups
$gps = mysql_query("select * from userGroups");
$todaygps = mysql_query("select g.usergroupId, g.usergroupName,u.Groupstatus,u.userGroupId from userGroups g join users u on (g.usergroupId = u.userGroupId)");

$content ='
                    
                        <div class="col-md-12"><!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                              <h3 class="panel-title">Password Management Control</h3>
                                        </div>  
                            <div class="panel-body"> ';
$content .="<div class='form-group'>
                                               
                                               <h3>Today's Schedule</h3>
                                       <hr>";
                                       $content .="<div class='col-sm-3'><div class='form-group'><div class='col-sm-12'>
      
                                           <label class='control-label'>Select UserGroup:</label>  <select name='sleeptime' id='todaygrp' class='form-control'><option value=''>-- Select --</option>";
while($grp = mysql_fetch_array($todaygps)){
 $content .= '<option value='.$grp['usergroupId'].'>'.$grp['usergroupName'].'- '.$grp['Groupstatus'].'</option>';


}
      
     $content.="</select></div></div></div><div class='col-sm-3'>
                                       <label class='control-label'>Enter Wake up time:</label>  <select name='wakeup' id='todaywakeup' class='form-control'><option value='$starttime'>$starttime</option>";
                    for($hours=0; $hours<24; $hours++) // the interval for hours is '1'
    for($mins=0; $mins<60; $mins+=30) // the interval for mins is '30'
     $content .='<option value='.str_pad($hours,2,'0',STR_PAD_LEFT).':'
                       .str_pad($mins,2,'0',STR_PAD_LEFT).':00'.'>'.str_pad($hours,2,'0',STR_PAD_LEFT).':'
                       .str_pad($mins,2,'0',STR_PAD_LEFT).':00</option>                        
                                      ';

                                  $content .= "</select> </div><div class='col-sm-3'>
                                       <label class='control-label'>Enter Sleep time:</label>  <select name='sleeptime' id='todaysleeptime' class='form-control'><option value='$endtime'>$endtime</option>";
                    for($hours=0; $hours<24; $hours++) // the interval for hours is '1'
    for($mins=0; $mins<60; $mins+=30) // the interval for mins is '30'
       $content .= '<option value='.str_pad($hours,2,'0',STR_PAD_LEFT).':'
                       .str_pad($mins,2,'0',STR_PAD_LEFT).':00'.'>'.str_pad($hours,2,'0',STR_PAD_LEFT).':'
                       .str_pad($mins,2,'0',STR_PAD_LEFT).':00</option>';
                                         
                     $content .= '  </select>  </div> <label class="control-label">.</label>
                               <input type="button" class="btn btn-primary" value="Update Schedule"  onclick=\'xajax_schedule_today(getElementById("todaygrp").value,getElementById("todaywakeup").value,getElementById("todaysleeptime").value); return false;\'>
                               <input type="button" class="btn btn-danger" value="Lock Today"  onclick=\'xajax_locktoday(getElementById("todaygrp").value); return false;\'>
                     </div>
                     <div class="dr"><span></span></div> 
                     <div class="col-md-4"> ';
                            
        $content .="<div class='form-group'><div class='col-sm-12'>
        <h3>UserGroup Selection</h3>
                                       <hr> 
                                           <label class='control-label'>Select UserGroup:</label>  <select name='sleeptime' id='sleeptime' class='form-control'><option value=''>-- Select --</option>";
while($grp = mysql_fetch_array($gps)){
 $content .= '<option value='.$grp['usergroupId'].'>'.$grp['usergroupName'].'</option>';


}
      
                     $content .= "</select></div><div class='col-sm-12'><label class='control-label'><br>Set password Expiry (in days):</label>
                    <input type='text' class='form-control' id='expiredays' value='$selectcharge'/>                        
                                       </div>   </div> </div>   
                     <div class='col-md-4'>";
                            
        $content .="<div class='form-group'>
                                               <div class='col-sm-12'>
                                               <h3>Weekday Schedule</h3>
                                       <hr>
                                       <label class='control-label'>Enter Wake up time:</label>  <select name='wakeup' id='wakeup' class='form-control'><option value='$starttime'>$starttime</option>";
                    for($hours=0; $hours<24; $hours++) // the interval for hours is '1'
    for($mins=0; $mins<60; $mins+=30) // the interval for mins is '30'
     $content .='<option value='.str_pad($hours,2,'0',STR_PAD_LEFT).':'
                       .str_pad($mins,2,'0',STR_PAD_LEFT).':00'.'>'.str_pad($hours,2,'0',STR_PAD_LEFT).':'
                       .str_pad($mins,2,'0',STR_PAD_LEFT).':00</option>                        
                                      ';

                                  $content .= "</select> </div><div class='col-sm-12'>
                                       <label class='control-label'><br>Enter Sleep time:</label>  <select name='sleeptime' id='sleeptime' class='form-control'><option value='$endtime'>$endtime</option>";
                    for($hours=0; $hours<24; $hours++) // the interval for hours is '1'
    for($mins=0; $mins<60; $mins+=30) // the interval for mins is '30'
       $content .= '<option value='.str_pad($hours,2,'0',STR_PAD_LEFT).':'
                       .str_pad($mins,2,'0',STR_PAD_LEFT).':00'.'>'.str_pad($hours,2,'0',STR_PAD_LEFT).':'
                       .str_pad($mins,2,'0',STR_PAD_LEFT).':00</option>';
                                         
                     $content .= '  </select>  </div> </div> 
                     </div>  
                      <div class="col-md-4">  ';
                            
        $content .="<div class='form-group'>
                                       <div class='col-sm-12'>
                                       <h3>Weekends Schedule</h3>
                                       <hr>
                                       <label class='control-label'>Enter Wake up time:</label>  <select name='wakeup' id='wakeup' class='form-control'><option value='$starttime'>$starttime</option>";
                    for($hours=0; $hours<24; $hours++) // the interval for hours is '1'
    for($mins=0; $mins<60; $mins+=30) // the interval for mins is '30'
     $content .='<option value='.str_pad($hours,2,'0',STR_PAD_LEFT).':'
                       .str_pad($mins,2,'0',STR_PAD_LEFT).':00'.'>'.str_pad($hours,2,'0',STR_PAD_LEFT).':'
                       .str_pad($mins,2,'0',STR_PAD_LEFT).':00</option>                        
                                      ';

                                  $content .= "</select> </div><div class='col-sm-12'>
                                       <label class='control-label'><br>Enter Sleep time:</label>  <select name='sleeptime' id='sleeptime' class='form-control'><option value='$endtime'>$endtime</option>";
                    for($hours=0; $hours<24; $hours++) // the interval for hours is '1'
    for($mins=0; $mins<60; $mins+=30) // the interval for mins is '30'
       $content .= '<option value='.str_pad($hours,2,'0',STR_PAD_LEFT).':'
                       .str_pad($mins,2,'0',STR_PAD_LEFT).':00'.'>'.str_pad($hours,2,'0',STR_PAD_LEFT).':'
                       .str_pad($mins,2,'0',STR_PAD_LEFT).':00</option>';
                                         
                     $content .= '  </select> <div  align="right"> <label class="control-label">.</label>
                               <input type="button" class="btn btn-primary" value="Set Schedule"  onclick=\'xajax_registerpassmgt(getElementById("expiredays").value,getElementById("wakeup").value,getElementById("sleeptime").value); return false;\'>
                            </div> </div> </div> 
                     </div>                       
                              </div>
                        </form><div id="status"></div>
                        <!--/ Form default layout -->
                        </div>
                   ';   

$resp->assign("display_div","innerHTML",$content);
return $resp;
}

function locktoday($grpid){
$resp=new xajaxResponse();
$response="";
$resp->confirmCommands(1,"Are you sure you want to Lock this group today!?");

//insert charge
    if(mysql_num_rows(mysql_query("select userId from users where userGroupId = '".$grpid."'")) > 0){
if(mysql_query("update users set Groupstatus='Inactive' where userGroupId = '".$grpid."' "))

        $response="<font class='alert alert-success'>Successfully Updated a Password management Control.</font>";
    }else{
      $response="<font class='alert alert-error'>Error: The selected user group has no user attached to it.</font>";
    }
    

$resp->assign("status","innerHTML",$response);
return $resp;
}

function schedule_today($grpid,$wakeup,$sleep){
$resp=new xajaxResponse();
$response="";
$resp->confirmCommands(1,"Are you sure you want to set password controls");

//insert charge
    if(mysql_num_rows(mysql_query("select id from schedule_today where group_id = '".$grpid."' && day = '".date("Y-m-d",time())."'")) > 0){
if(mysql_query("update schedule_today set start_time='".$wakeup."',end_time='".$sleep."' where group_id = '".$grpid."' && day='".date("Y-m-d",time())."'")){
  mysql_query("update users set Groupstatus='Active' where userGroupId = '".$grpid."'");

        $response="<font class='alert alert-success'>Successfully Updated Today's System access Control.</font>";
      }
    }
    else{
    
    if(mysql_query("insert into schedule_today (group_id,start_time,end_time,day) values ('".$grpid."','".$wakeup."','".$sleep."','".date("Y-m-d h:i:s",time())."')")){
      mysql_query("update users set Groupstatus='Active' where userGroupId = '".$grpid."'");

        $response="<font class='alert alert-success'>Successfully set Today's System access Control.</font>";
      }
}

$resp->assign("status","innerHTML",$response);
return $resp;
}

function registerpassmgt($expiredays,$wakeup,$sleep){
$resp=new xajaxResponse();
$response="";
$resp->confirmCommands(1,"Are you sure you want to set password controls");

//insert charge
    if(mysql_num_rows(mysql_query("select id from passwordmgt")) > 0){
if(mysql_query("update passwordmgt set expiredays='".$expiredays."',waketime='".$wakeup."',sleeptime='".$sleep."'"))

        $response="<font class='alert alert-success'>Successfully Updated a Password management Control.</font>";
    }
    else{
    
    if(mysql_query("insert into passwordmgt (expiredays,waketime,sleeptime) values ('".$expiredays."','".$wakeup."','".$sleep."')"))

        $response="<font class='alert alert-success'>Successfully set a Password management Control.</font>";
}

$resp->assign("status","innerHTML",$response);
return $resp;
}




function regBudgetForm(){
$resp = new xajaxResponse();
//$resp->assign("status","innerHTML","");
$close_m = mysql_query("select distinct last_month from branch");
$select="";
if(mysql_num_rows($close_m)>0){
$m = mysql_fetch_array($close_m);
$select=$m['last_month'];
}

$content ='
                    
                        <div class="col-md-12"><!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                              <h3 class="panel-title">Register Budget Items</h3>
                                        
                            <div class="panel-body">';
                            
        $content .="<div class='form-group'>
<div class='col-sm-3'>
                                            <label class='control-label'>Item Name:</label>
                                            <input type='text' id='item_name' class='form-control'/>
                                       
                                </div>

        <div class='col-sm-3'>
                                            <label class='control-label'>Register As Income:</label>
                                            
                                       
                                </div>
                                <div class='col-sm-3'>
                                            <label class='control-label'>OR Register As Expense:</label>
                                            <select name='expense' id='expense' class='form-control'>";
      for($i=1;$i<13;$i++){
      $selected = $select==""?($i==12?"selected":""):($i==intval($select)?"selected":"");
      $content .="<option value='".$i."' ".$selected.">".digit_month($i)."</option>";
      }
      $content .="</select>
                                       
                                </div>";
      
                     $content .= '<div class="form-group">                              
                                
                               <input type="button" class="btn btn-primary" value="Save"  onclick=\'xajax_registerCloseMonth(getElementById("month").value); return false;\'>
                            </div><div id="status"></div></div>
                        </form>
                        <!--/ Form default layout -->
                        </div>
                   ';   

$resp->assign("display_div","innerHTML",$content);
return $resp;
}

function overdraftForm(){
$resp = new xajaxResponse();
//$resp->assign("status","innerHTML","");
$selectcharge="";$selectpercent = "";
$close_m = mysql_query("select * from overdraft");
if(mysql_num_rows($close_m)>0){
$m = mysql_fetch_array($close_m);

if($type=$m['type'] == 0){
$selectcharge=$m['charge'];
}else if($type=$m['type'] == 1){
 $selectpercent=$m['charge'];   
}
}

$content ='
                    
                        <div class="col-md-12"><!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                              <h3 class="panel-title">Overdraft Charge Settings</h3>
                                           
                            <div class="panel-body">';
                            
        $content .="<div class='form-group'><div class='col-sm-3'>
                                            <label class='control-label'>Enter charge amount:</label>
                    <input type='text' class='form-control' id='charge' value='$selectcharge' />                        
                                       </div>

                                       <div class='col-sm-3'>
                                       <label class='control-label'>OR Enter charge Percentage:</label>
                    <input type='text' class='form-control' id='chargepercent' value='$selectpercent'/>                        
                                     </div>  
                                ";
            
                     $content .= '                             
                               <label class="control-label">.</label>
                               <input type="button" class="btn btn-primary" value="Set"  onclick=\'xajax_registeroverdraftsettings(getElementById("charge").value,getElementById("chargepercent").value); return false;\'>
                            </div></div>
                        </form><div id="status"></div>
                        <!--/ Form default layout -->
                        </div>
                   ';   

$resp->assign("display_div","innerHTML",$content);
return $resp;
}
function registeroverdraftsettings($charge,$chargepercent){
$resp=new xajaxResponse();
$response="";
$resp->confirmCommands(1,"Are you sure you want to set the Overdraft Charge");
if($charge == '' && $chargepercent==''){
//all empty
    $response="<font class='alert alert-danger'>All charges can not be Zero!!</font>";


}elseif($charge != '' && $chargepercent!=''){
//all filled
    $response="<font class='alert alert-danger'>Choose only one Charge Type!!</font>";

}
elseif($charge != '' && $chargepercent==''){
//insert charge
    $type = 0;
    if(mysql_num_rows(mysql_query("select id from overdraft")) > 0){
if(mysql_query("update overdraft set charge='".$charge."',type='".$type."'"))

        $response="<font class='alert alert-success'>Successfully Updated an overdraft charge.</font>";
    }
    else{
    
    if(mysql_query("insert into overdraft (charge,type) values ('".$charge."','".$type."')"))

        $response="<font class='alert alert-success'>Successfully set an overdraft charge.</font>";
}
}
elseif ($charge == '' && $chargepercent !='') {
//insert chargepercent
    $type = 1;
    if(mysql_num_rows(mysql_query("select id from overdraft")) > 0){
if(mysql_query("update overdraft set charge='".$chargepercent."',type='".$type."'"))

        $response="<font class='alert alert-success'>Successfully Updated an overdraft charge percentage.</font>";
    }
    else{
    
        if(mysql_query("insert into overdraft (charge,type) values ('".$chargepercent."','".$type."')"))
        $response="<font class='alert alert-success'>Successfully set an overdraft charge percentage.</font>";
}
}
$resp->assign("status","innerHTML",$response);
return $resp;
}

function closeMonthForm(){
$resp = new xajaxResponse();
//$resp->assign("status","innerHTML","");
$close_m = mysql_query("select distinct last_month from branch");
$select="";
if(mysql_num_rows($close_m)>0){
$m = mysql_fetch_array($close_m);
$select=$m['last_month'];
}

$content ='
                    
                        <div class="col-md-12"><!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                              <h3 class="panel-title">CLOSING MONTH OF A FINANCIAL YEAR</h3>
                            </div>               
                            <div class="panel-body">';
                            
        $content .="<div class='col-sm-6'><div class='form-group'>
                                            <label class='control-label'>Select Closing Month:</label>
                                            <select name='month' id='month' class='form-control'>";
			for($i=1;$i<13;$i++){
			$selected = $select==""?($i==12?"selected":""):($i==intval($select)?"selected":"");
			$content .="<option value='".$i."' ".$selected.">".digit_month($i)."</option>";
			}
			$content .="</select>
                                       
                                </div>";
			
                     $content .= '<div class="form-group">                              
                                
                               <input type="button" class="btn btn-primary" value="Save"  onclick=\'xajax_registerCloseMonth(getElementById("month").value); return false;\'>
                            </div><div id="status"></div></div>
                        </form>
                        <!--/ Form default layout -->
                        </div>
                   ';   

$resp->assign("display_div","innerHTML",$content);
return $resp;
}
function isLastMonthExist(){
$month=mysql_query("select last_month from branch");
if(mysql_num_rows($month)>0){
$m=mysql_fetch_array($month);
if($m['last_month']!=NULL)
return 1;
}
return 0;
}
function registerCloseMonth($month){
$resp=new xajaxResponse();
$response="";
if(isLastMonthExist())
$resp->confirmCommands(1,"Are you sure you want to edit the closing month");
if(mysql_query("update branch set last_month=".$month))
$response="<font class='alert alert-success'>Successfully set the closing Month</font>";
else
$response="<font class='alert alert-danger'>Failed to set closing month, Contact Mobis Admin</font>";
$resp->assign("status","innerHTML",$response);
return $resp;
}
function memberAccounts(){
	$resp = new xajaxResponse();
	$cur_settings = mysql_query("select*from customer_settings");
	$create = NULL; $always=NULL;
	if(mysql_num_rows($cur_settings)>0){
		$set = mysql_fetch_array($cur_settings);
		$create=$set['auto_create']=="on"?"CHECKED":NULL;
		$always=$set['always_create']=="on"?"CHECKED":NULL;
	}
	
	$content = '
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">MEMBER ACCOUNTS</h3>
                            </div>               
                            <div class="panel-body">';
        $content = '
                    <div class="col-md-12">
                        <!-- START panel -->
                        <div class="panel panel-default">
                            <!-- panel heading/header -->
                            <div class="panel-heading">
                                <h3 class="panel-title">MEMBER ACCOUNTS</h3>
                            </div>
                            <!--/ panel heading/header -->
                            <!-- panel body -->
                            <div class="panel-body">
                                <form class="form-horizontal form-bordered" action="">';
                            
	$content .='<div class="form-group">
                                       
                                        <div class="col-sm-9">
                                            <span class="checkbox custom-checkbox custom-checkbox-inverse">
                                                <input type="checkbox" name="customcheckbox1" id="customcheckbox1" value="create" "'.$create.'">
                                                <label for="customcheckbox1">&nbsp;&nbsp;Create user Accounts for all customers</label>
                                            </span>
                                            <span class="checkbox custom-checkbox">
                                                <input type="checkbox" name="customcheckbox2" id="customcheckbox2" value="always create" "'.$always.'">
                                                <label for="customcheckbox2">&nbsp;&nbsp;Always Create user Accounts for all customers during registration</label>
                                            </span>
                                        </div>
                                    </div>';
                                    
         $content .= '<div class="">                              
                                 
                               <input type="button" class="btn  btn-primary" value="Save"  onclick=\'xajax_saveAccounts(getElementById("customcheckbox1").checked,getElementById("customcheckbox2").checked); return false;\'>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div></div></div></div>';	
	
	$resp->assign("display_div","innerHTML",$content);
	return $resp;
}
function saveAccounts($create,$always){
	$resp = new xajaxResponse();
	$exist = 0; $created=0;
	$create=$create=='true'?'on':'off';
	$always=$always=='true'?'on':'off';
	$settings = mysql_query("select*from customer_settings");
	$content = "<table align=center><tr><td></td></tr>";
	$name="";$status='active';$username="";$password="";$position="member";$date = date('y-m-d');
	$close_time="23:59:59";$open_time="00:00:00";
	if(mysql_num_rows($settings)>0){
		if(mysql_query("update customer_settings set auto_create='".$create."',always_create='".$always."'")){
			$cont = "<div>Settings Changed and Saved</div>";
			$resp->assign("status","innerHTML",$cont);
		}else
			$content .=mysql_error();
	}
		else{
			if(mysql_query("insert into customer_settings(auto_create,always_create)values('".$create."','".$always."')")){
			$cont ="<div>Settings Saved</div>";
			$resp->append("status","innerHTML",$cont);
			}
			else
				$content .=mysql_error();
		}
	if($create='on'){
		$mem = mysql_query("select mem_no,first_name,last_name from member");
		$cont ="<div>Member Accounts Created.</div>";
		       $resp->append("status","innerHTML",$cont);
		while($row=mysql_fetch_array($mem)){
			if(isAccountExists($mem))
				$exist++;
			else{
				$name = $row['first_name']." ".$row['last_name'];
				$username=strtolower(preg_replace("[ ]","",$row['first_name'].".".$row['last_name']));
				$password=crypt($row['mem_no'],"xy");
				if(mysql_query("insert into users(name,username,password,position,date,open_time,close_time,status,mem_no)values('".$name."','".$username."','".$password."','".$position."','".$date."','".$open_time."','".$close_time."','".$status."','".$row['mem_no']."')"))
					$created++;
			}
		}
	}
	//$content .="</table>";
	//$resp->append("display_div","innerHTML",$content);
	return $resp;
}
function isAccountExists($mem_no){
	$mem = mysql_query("select*from users where mem_no='".$mem_no."'");
	return mysql_num_rows($mem)>0?1:0;
}
# BRANCH DETAILS FORM
function addBranchForm(){
$resp = new xajaxResponse();

$content ="<div class='col-md-12' >";
	$content .="<form method='post' action='configuration/regBranch' enctype='multipart/form-data' class='panel panel-default form-horizontal form-bordered'>";
$content .= '
  			  		
<div class="panel-heading">
                              <h3 class="panel-title">REGISTER A NEW BRANCH</h3>
                            </div>  
                                      <div class="panel-body">   ';
                                        
	 
	                                    $content .= '<div class="col-sm-6"><div class="form-group">
                                            <label class="col-sm-3 control-label">Institution\'s Name:</label>
                                            <div class="col-sm-6">
                                            <select id="sacco_name" name="sacco_name" class="form-control">
                                            '.libinc::getItem("company","companyId","companyName","").'
                                            </select>
                                            </div>                                            
                                            </div>';                                           
                                            $content .='<div class="form-group required">
                                            <label class="col-sm-3 control-label">Branch No:</label>
                                            <div class="col-sm-6">
                                            <input type="text" id="branch_no" name="branch_no" class="form-control">	
                                            </div></div>';
                                            $content .= '<div class="form-group required">
                                            <label class="col-sm-3 control-label">Branch Name:</label>
                                            <div class="col-sm-6">
                                           <input type="text" id="branch_name" name="branch_name" class="form-control">
                                            </div></div>';                                            
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Email:</label>
                                            <div class="col-sm-6">
                                           <input type="text" id="email" name="email" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Address:</label>
                                            <div class="col-sm-6">
                                           <input type="text" id="address" name="address" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group required">
                                            <label class="col-sm-3 control-label">Share Value:</label>
                                            <div class="col-sm-6">
                                           <input type="text" id="share_value" name="share_value" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group required">
                                            <label class="col-sm-3 control-label">Report Period:</label>
                                            <div class="col-sm-6">
                                           <input type="text" id="report_period" name="report_period" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group required">
                                            <label class="col-sm-3 control-label">Minimum Shares:</label>
                                            <div class="col-sm-6">
                                           <input type="text" id="min_shares" name="min_shares" class="form-control">
                                            </div></div></div>';
                                           $content .="<div class='col-md-6'>";
                                       
                                            
                                             $content .= '<div class="form-group required">
                                            <label class="col-sm-3 control-label">Maxmum Share Percentage:</label>
                                            <div class="col-sm-6">
                                           <input type="text" id="max_share_per" name="max_share_per" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group required">
                                            <label class="col-sm-3 control-label">Loan Share Percentage:</label>
                                            <div class="col-sm-6">
                                           <input type="text" id="loan_share_per" name="loan_share_per" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group required">
                                            <label class="col-sm-3 control-label">Loan Save Percentage:</label>
                                            <div class="col-sm-6">
                                           <input type="text" id="loan_save_per" name="loan_save_per" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group required">
                                            <label class="col-sm-3 control-label">Guarantor Share Percentage:</label>
                                            <div class="col-sm-6">
                                           <input type="text" id="guarantor_share_per" name="guarantor_share_per" class="form-control">
                                            </div></div>';                                            
                                             $content .= '<div class="form-group required">
                                            <label class="col-sm-3 control-label">Guarantor Save Percentage:</label>
                                            <div class="col-sm-6">
                                           <input type="text" id="guarantor_save_per" name="guarantor_save_per" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Branch Prefix:</label>
                                            <div class="col-sm-6">
                                           <input type="text" name="prefix" id="prefix" class="form-control">
                                            </div></div>';
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Logo:</label>
                                            <div class="col-sm-6">                                                                         
                                            <input type="file" name="logo" id="logo">                                                  
                                            </div></div>
                                        ';         
                                           /* $content .= "<div class='center'><button type='reset' class='btn btn-default' onclick=\"xajax_addBranchForm(); return false;\">Reset</button>
                                            <button type='button' class='btn btn-primary'   onclick=\"xajax_insertBranch(getElementById('sacco_name').value, getElementById('branch_no').value, getElementById('branch_name').value, getElementById('email').value, getElementById('address').value, getElementById('share_value').value, getElementById('report_period').value, getElementById('min_shares').value, getElementById('max_share_per').value, getElementById('loan_share_per').value, getElementById('loan_save_per').value, getElementById('guarantor_share_per').value, getElementById('guarantor_save_per').value,getElementById('prefix').value,getElementById('logo').value); return false;\">Save</button><br><br>";  */
                                           // $content .= '<div id="status"></div></div></div></div></div>';
                                            // $content .= '';
                                            $content .= '<div class="panel-footer"><button type="reset" class="btn btn-default" onclick=\"xajax_addCustForm(); return false;\">Reset</button>
                                            <input type="submit" value="Save" name="cust_enter" id="cust_enter" class="btn btn-primary">';
                                            $content .= '</div>';

                                            $content .= '</div></div></form></div></div>';
                                           
                                          
$resp->assign('display_div','innerHTML',$content);
return $resp;
}

# CHECK BRANCH DETAILS PROVIDED
function insertBranch($sacco_name,$branch_no,$branch_name,$email,$address,$share_value,$report_period,$min_share,$max_share,$loan_share,$loan_save,$guarantor_share,$guarantor_save,$prefix){
$response = new xajaxResponse();
$content = NULL;
if($branch_no==NULL||$branch_name==NULL||$share_value==NULL||$report_period==NULL||$min_share==NULL||$max_share==NULL||$loan_share==NULL||$loan_save==NULL||$guarantor_share==NULL||$guarantor_save==NULL){
	//$response->alert('You may not leave any filled Blank!');
$content ="<font class='alert alert-danger'>Fill all the requred fields, then save again.</label>";}
elseif(mysql_num_rows(mysql_query("select branch_no from branch where branch_no=".$branch_no))>0)
	$response->alert('The Branch No. exists. Cannot duplicate it, Choose another number and try again.');
elseif(mysql_num_rows(mysql_query("select branch_name from branch where branch_name='".$branch_name."'"))>0)
	$response->alert("A branch with a similar name exists. Cannot use same name for several branches!");
elseif(mysql_num_rows(mysql_query("select prefix from branch where prefix='".$prefix."'"))>0)
	$response->alert("A branch with a similar prefix exists. Are you sure you want to use the same prefix?");
	elseif(mysql_query("insert into branch set branch_no='".$branch_no."', overall_name='".$sacco_name."', branch_name='".$branch_name."', email='".$email."', address='".$address."', share_value=".$share_value.", report_period='".$report_period."', min_shares='".$min_share."', max_share_percent='".$max_share."', loan_share_percent='".$loan_share."', loan_save_percent='".$loan_save."', prefix='".$prefix."', guarantor_share_percent='".$guarantor_share."', guarantor_save_percent='".$guarantor_save."', companyId='".$sacco_name."'"))
	$content = "<font class='alert alert-success'>Branch Sucessfully Registered.</font>";
	
else{
	$content ="<font class='alert alert-danger'>Failed to register Branch. Please contact Mobis</font>";
//$response->alert("Failed to register Branch. . ".mysql_error());
}
$response->assign('status','innerHTML',$content);
return $response;
}
/*
function showProvisions()
{
	$resp = new xajaxResponse();
	$prov_res = @mysql_query("select * from provissions order by id asc");
	if (@mysql_num_rows($prov_res) > 0)
	{
	
			    
			    $content = '<div class="col-md-12"><div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">LOAN LOSS PROVISIONS SETTINGS</h3>
                               
                               </div>';
 		$content .= '<table class="table table-bordered" id="table-tools">
			    
				<th></th><th>Range</th><th>Percentage Loss</th><th>Action</th>
			    <tbody>';
			    
		$x = 1;
		while ($prov_row = @mysql_fetch_array($prov_res))
		{
			preg_match("/range(\d+)_(\d+)/", $prov_row['range'], $arg);
			//$start = $arg[1]
			//$resp->alert($arg);
			//return $resp;
			$range = !isset($arg[2]) ? "More than 180" : $arg[1]. " - " .$arg[2];
			/* switch (strtolower($prov_row['range']))
			{
				case 'range1_30':
					$range = "1 - 30";
					break;
				case 'range31_60':
					$range = "31 - 60";
					break;
				case 'range61_90':
					$range = "61 - 90";
					break;
				case 'range91_120':
					$range = "91 - 120";
					break;
				case 'range121_180':
					$range = "121 - 180";
					break;
				case 'range180_over':
					$range = "More than 180 ";
					break;
			}
			*/
			/*if ($x % 2 == 0)
				$color = "lightgrey";
			else
				$color = "white";*/
		/*	$content .= "
				    <tr>
					<td>$x.</td><td align=center>".$range." days</td><td align=center>".$prov_row['percent']."</td><td><a href='javascript:;' onclick=\"xajax_editProvisions('".$prov_row['id']."', '".$range."', '".$prov_row['percent']."'); return false;\">Edit</a></td>
				    </tr>
				    ";
			$x++;
		}
		$content .= "</tbody></table></div>";
	}
	else{
		$cont = "<font color='red'>No Loan Loss Provisions yet registered.</font>";
		$resp->assign("status", "innerHTML", $cont);
		return $resp;
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function editProvisions($pid, $range, $percent)
{
	$resp = new xajaxResponse();
	/*if($_SESSION['position'] <>'Manager'){
		$resp->alert("Access Denied! \nOnly the Manager can edit an item");
		return $resp;
	}*/

/*	$content ='<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                              </p><h3 class="panel-title">EDIT LOAN LOSS PROVISIONS</h3></p>
                            </div>               
                            <div class="panel-body">';
                            
        $content .='<div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Range:</label>
                                            <span>'.$range.'</span> 
                                        </div>
                                    </div>
                                </div>';
			
			 $content.='<div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                        <label class="control-label">Amount:</label>
                                           <input type=int name="percentage" id="percentage" value="'.$percent.'" class="form-control" >
                                    </div>
                                 </div></div>';
                     $content .= '<div class="panel-footer">                              
                                
                                <input type="button" class="btn  btn-default" value="Back"  onclick=\'xajax_showProvisions(); return false;\'>&nbsp;&nbsp;<input type="button" class="btn btn-primary" value="Update"  onclick=\'xajax_updateProvisions2("'.$pid.'", getElementById("percentage").value); return false;\'>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>';   
		    		    
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function updateProvisions2($pid, $percent)
{
	$resp = new xajaxResponse();
	if ($pid == '' || $percent == '')
	{
		$resp->alert("Please fill in all the fields.");
		return $resp;
	}
	$resp->confirmCommands(1, "Do you really want to update this provission to $percent%?");
	$resp->call('xajax_updateProvisions', $pid, $percent);
	return $resp;
}

function updateProvisions($pid, $percent)
{
	$resp = new xajaxResponse();
	$upd_res = mysql_query("UPDATE provissions set percent = '".$percent."' where id = $pid");
	if (isset($upd_res) && @mysql_affected_rows() != -1)
		$content .= "<font color='green'>Loan Loss Provision successfully updated.</font><br>";
	else{
		$content .= "<font color='red'>Loan Loss Provision not updated.</font><br>";
	}
	$resp->assign("status", "innerHTML", $content);
	$resp->call("xajax_showProvisions");
	return $resp;
}

*/
function showSettings()
{
  $resp = new xajaxResponse();
  $res = @mysql_query("select * from branch");
  if (@mysql_num_rows($res) > 0)
  {
  
  $content = '<div class="col-md-12"><div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">BRANCH SETTINGS</h3>
                               
                               </div>';
    $content .= '<table class="table borderless table-hover" id="table-tools" width=100%>
  
      
         <th>Branch Name</th>
         <th>Email</th>
         <th>Physical Address</th>
         <th>Share Value</th>
         <th>Min Shares</th>
         <th>Max Share Percentage</th>
         <th>Borrower\'s Compulsory Savings % of Loan</th>
         <th>Guarantor\'s Compulsory Savings % of Loan</th>
         <th>Borrower\'s Compulsory Shares % of Loan</th>
          <th>Guarantor\'s Compulsory Shares % of Loan</th>
         <th>Member No Prefix</th>
          <th>Logo</th>
        <th></th>
    <tbody>';
      $n = 0;
      while ($row = @mysql_fetch_array($res)){
        //$color =($n%2==0)?"lightgrey":"white";
        /*<td>".@((100 / $row['loan_save_percent']) * 100)."</td>
         <td>".@((100 / $row['guarantor_save_percent']) * 100)."</td>
       <td>".@((100 / $row['loan_share_percent']) * 100)."</td>
         <td>".@((100 / $row['guarantor_share_percent']) * 100)."</td> */
        $content .="
      <tr>
         <td>".$row['branch_name']."</td>
         <td>".$row['email']."</td>
         <td>".ucwords($row['address'])."</td>
         <td>".number_format($row['share_value'],2)."</td>
         <td>".number_format($row['min_shares'],2)."</td>
         <td>".number_format($row['max_share_percent'],2)."</td>
        
          <td>".number_format($row['loan_save_percent'],2)."</td>
         <td>".number_format($row['guarantor_save_percent'],2)."</td>
       <td>".number_format($row['loan_share_percent'],2)."</td>
         <td>".number_format($row['guarantor_share_percent'],2)."</td>
        <td>".$row['prefix']."</td>
        <td><img src='logos/$row[logo]' width=60 height=80></td>
         <td><a href='javascript:;' onclick=\"xajax_editSettings('".$row['branch_no']."'); return false;\">Edit</a></td>
      </tr>";
      $n++;
      }
      $content .="
         </tbody></table></div>
        ";
  $resp->assign("display_div", "innerHTML", $content);
  }
  else
  {
    $cont = "<div><font color=red>No branch is defined yet.</font><div>";
    $resp->assign("display_div", "innerHTML", $cont);
    return $resp;
    
        }
/*    $content .= "
          <form method=post><table height=100 border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='70%' id='AutoNumber2' align=center>
          <tr>
        <td><font color=red></font></td><td><input type=hidden id='branch_name' name='branch_name'></td>
          </tr>
          <tr>
        <td>Email: </td><td><input type=text id='email' name='email'></td>
          </tr>
          <tr>
        <td>Physical Address: </td><td><input type=text id='address' name='address'></td>
          </tr>
          <tr>
        <td><font color=red>Share Value: </font></td><td><input type=text id='share_value' name='share_value'></td>
          </tr>
          <tr>
        <td><font color=red>Minimum Shares: </font></td><td><input type=text id='min_shares' name='min_shares'></td>
          </tr>
          <tr>
        <td><font color=red>Max Share Percentage: </font></td><td><input type=text id='max_share_percent' name='max_share_percent'></td>
          </tr>
         
          <tr>
        <td><font color=red>Borrower's Compulsory Savings % of Loan: </font></td><td><input type=numeric id='loan_save_percent' name='loan_save_percent' value='".floor((100 / $row['loan_save_percent']) * 100)."'></td>
          </tr>
         <tr>
        <td><font color=red>Borrower's Compulsory Shares % of Loan: </font></td><td><input type=numeric id='loan_share_percent' name='loan_share_percent' value='".((100 / $row['loan_share_percent']) * 100)."'></td>
          </tr>
          <tr>
        <td><font color=red>Guarantor's Compulsory Savings % of Loan: </font></td><td><input type=numeric id='guarantor_save_percent' name='guarantor_save_percent' value='".((100 / $row['guarantor_save_percent']) * 100)."'></td>
          </tr>
        <tr>
        <td><font color=red>Guarantor's Compulsory Shares % of Loan: </font></td><td><input type=numeric id='guarantor_share_percent' name='guarantor_share_percent' value='".((100 / $row['guarantor_share_percent']) * 100)."'></td>
          </tr>
        <tr>
        <td><font color=red>Prefix: </font></td><td><input type=numeric id='prefix' name='prefix' value='".$row['prefix']."'></td>
          </tr>
          <tr>
        <td></td><td><a href=# onclick=\"xajax_regBranch( getElementById('branch_name').value, getElementById('email').value, getElementById('address').value, getElementById('share_value').value, getElementById('min_shares').value, getElementById('max_share_percent').value, getElementById('loan_share_percent').value, getElementById('loan_save_percent').value, getElementById('guarantor_share_percent').value, getElementById('guarantor_save_percent').value, getElementById('prefix').value); return false;\"><img src='images/btn_save.jpg'></a></td>
          </tr>
          </table></form>
          ";*/
    
    
  //}
  $resp->assign("display_div", "innerHTML", $content);
  return $resp;
}


function editSettings($branch_no)
{
  $resp = new xajaxResponse();
  $row = @mysql_fetch_array(@mysql_query("select * from branch where branch_no = $branch_no"));
  $content ="";
  $content .="<form method='post' action='configuration/editBranch' enctype='multipart/form-data' class='panel-default form-horizontal form-bordered'>";
$content .= '
              <div class="panel-heading">
                                    
                                                  <h4 class="semibold text-primary mt0 mb5">EDIT BRANCH SETTINGS</h4>
                                                   
                                          
                                        </div><div class="panel-body"><div class="col-sm-6">';
                                        
   
                                     /* $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Institution\'s Name:</label>
                                            <div class="col-sm-6"><input type="text" id="sacco_name" name="sacco_name" class="form-control"></div>                                            
                                            </div>';  */                                        
                                             $content .='<div class="form-group">
                                            
                                            <label class="col-sm-3 control-label">Branch No.:</label>
                                            <div class="col-sm-6">
                                            <input type="text" id="branch_no" name="branch_no" value="'.$row['branch_no'].'" class="form-control">  
                                            </div></div>';
                                            $content .= '<div class="form-group">
                                           
                                            <label class="col-sm-3 control-label">Branch Name:</label>
                                            <div class="col-sm-6">
                                           <input type="text" id="branch_name" name="branch_name" value="'.$row['branch_name'].'" class="form-control">
                                            </div></div>';                                                         
                                             $content .= '<div class="form-group">
                                              
                                            <label class="col-sm-3 control-label">Email:</label>
                                             <div class="col-sm-6">
                                           <input type="text" id="email" name="email" value="'.$row['email'].'" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Address:</label>
                                             <div class="col-sm-6">
                                           <input type="text" id="address" name="address" value="'.$row['address'].'" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Share Value:</label>
                                             <div class="col-sm-6">
                                           <input type="text" id="share_value" name="share_value" value="'.$row['share_value'].'"  class="form-control">
                                            </div></div>';
                                             /*$content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Report Period:</label>
                                            <div class="col-sm-6">
                                           <input type="text" id="report_period" name="report_period" class="form-control">
                                            </div></div>';*/
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Minimum Shares:</label>
                                             <div class="col-sm-6">
                                           <input type="text" id="min_shares" name="min_shares" value="'.number_format($row['min_shares'],0).'" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Maximum Share Percentage:</label>
                                            <div class="col-sm-6">
                                           <input type="text" id="max_share_per" name="max_share_per" value="'.number_format($row['max_share_percent'],0).'" class="form-control">
                                            </div></div></div>';
                                             $content .= '<div class="col-sm-6"><div class="form-group">
                                            <label class="control-label">Loan Share Percentage:</label>
                                             <div class="col-sm-6">
                                           <input type="text" id="loan_share_per" name="loan_share_per" value="'.number_format($row['loan_share_percent'],0).'" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Loan Save Percentage:</label>
                                             <div class="col-sm-6">
                                           <input type="text" id="loan_save_per" name="loan_save_per" value="'.number_format($row['loan_save_percent'],0).'" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Guarantor Share Percentage:</label>
                                             <div class="col-sm-6">
                                           <input type="text" id="guarantor_share_per" name="guarantor_share_per" value="'.number_format($row['guarantor_share_percent'],0).'" class="form-control">
                                            </div></div>';                                            
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Guarantor Save Percentage:</label>
                                            <div class="col-sm-6">
                                           <input type="text" id="guarantor_save_per" name="guarantor_save_per" value="'.number_format($row['guarantor_save_percent'],0).'" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Branch Prefix:</label>
                                             <div class="col-sm-6">
                                           <input type="text" name="prefix" id="prefix"  value="'.$row['prefix'].'" class="form-control">
                                            </div></div>';
                                              $content .= "<div class='panel-footer'><button type='reset' class='btn btn-default' onclick=\"xajax_showSettings()\">Reset</button>
                                            <button type='button' class='btn btn-primary'   onclick=\"xajax_saveSettings('".$row['branch_no']."',  getElementById('branch_name').value, getElementById('email').value, getElementById('address').value, getElementById('share_value').value, getElementById('min_shares').value, getElementById('max_share_per').value, getElementById('loan_share_per').value, getElementById('loan_save_per').value,
      getElementById('guarantor_share_per').value, getElementById('guarantor_save_per').value, getElementById('prefix').value); return false;\">Update</button>";
      //$content .='<input type="submit" value="Save" name="save" id="save" class="btn btn-primary">';
                                           
                                            $content .= '</div></form></div>';
  $resp->assign("display_div", "innerHTML", $content);
  return $resp;
}
function saveSettings($branch_no,  $branch_name, $email, $address, $share_value, $min_shares, $max_share_percent, $loan_share_percent, $loan_save_percent, $guarantor_share_percent, $guarantor_save_percent, $prefix)
{
	$resp = new xajaxResponse();
	if ($branch_no == ''  || $branch_name == '' || $share_value == '' || $min_shares == '' || $max_share_percent == '' || $loan_share_percent == '' || $loan_save_percent == '')
	{
		$content = "<font color='red'>Please Fill in all the fields marked in red.</font>";
		$resp->assign("status", "innerHTML", $content);
		return $resp;
	}
	else
	{
		if($loan_save_percent != 0)
			$loan_save_percent = ($loan_save_percent);
		if($loan_share_percent != 0)
			$loan_share_percent = ($loan_share_percent);
		if($guarantor_save_percent != 0) 
			$guarantor_save_percent = ($guarantor_save_percent);
		if($guarantor_share_percent != 0)
			$guarantor_share_percent = ($guarantor_share_percent);
		$res = @mysql_query("update branch set  branch_name = '".ucfirst($branch_name)."', email = '".$email."', address ='".$address."', share_value = '".$share_value."', min_shares ='".$min_shares."', max_share_percent = '".$max_share_percent."', loan_share_percent = '".$loan_share_percent."', loan_save_percent = '".$loan_save_percent."', guarantor_share_percent='".$guarantor_share_percent."', guarantor_save_percent = '".$guarantor_save_percent."',  prefix='".$prefix."' where branch_no ='". $branch_no."'");
		
		if($res==1){
			$content = "<font color=red>Settings successfully updated</font>";
			
			$action = "update branch set  branch_name = '".ucfirst($branch_name)."', email = '".$email."', address ='".$address."', share_value = '".$share_value."', min_shares ='".$min_shares."', max_share_percent = '".$max_share_percent."', loan_share_percent = '".$loan_share_percent."', loan_save_percent = '".$loan_save_percent."', guarantor_share_percent='".$guarantor_share_percent."', guarantor_save_percent = '".$guarantor_save_percent."',  prefix='".$prefix."' where branch_no ='". $branch_no."'";
			mysql_query("insert into logs set user_id='".$_SESSION['user_id']."', time=NOW(),  action='".$_SESSION['name'].":  ".mysql_escape_string($action)."'");
		}else
			$content = "<font color=red><b>Settings not updated.".mysql_error()."</b></font>";
		$resp->assign("status", "innerHTML", $content);
		$resp->call("xajax_showSettings");
		return $resp;
	}
}

function regBranch( $branch_name, $email, $address, $share_value, $min_shares, $max_share_percent, $loan_share_percent, $loan_save_percent, $guarantor_share_percent, $guarantor_save_percent, $prefix)
{
	$resp = new xajaxResponse();
	if ( $branch_name == '' || $share_value == '' || $min_shares == '' || $max_share_percent == '' || $loan_share_percent == '' || $loan_save_percent == '')
	{
		$content = "<font color='red'>Please Fill in all the fields marked in red.</font>";
		$resp->assign("status", "innerHTML", $content);
		return $resp;	
	}
	else
	{
		$loan_save_percent = floor((100 /$loan_save_percent) * 100);
		$loan_share_percent = floor((100 /$loan_share_percent) * 100);
		$res = @mysql_query("insert into branch ( branch_name, email, address, share_value, min_shares, max_share_percent, loan_share_percent, loan_save_percent) VALUES ('".$branch_name."', '".$email."', '".$address."', $share_value, $min_shares, $max_share_percent, $loan_share_percent, $loan_save_percent)");
		if (@mysql_num_rows($res) > 0)
			$content = "<font color=red>Settings successfully registered.</font>";
		else
			$content = "<font color=red>Settings not registered. ".mysql_error()."</font>";
		$resp->assign("status", "innerHTML", $content);
		$resp->call("xajax_showSettings");
		return $resp;
	}
}

/*PMT settings
 *module implemented 2011-06-12 (Mon)
 *TODO 
 *Add PMT default values
 */
 
 function pmtSettings($req=""){
 		$defaultFields = array("Company Name"	=> "CraneApps",
 					"Report Code"	=> "",
 					"Poverty Line"	=> "15000",
 					"Hash Value"	=> "",
 					"Organization Type" => "SACCO");
 		$org_type = array("SACCO", "MFI", "MDI", "Bank", "NGO", "Other");
 					
 		$cur_set = mysql_query("select * from pmt_settings") or die(mysql_error());
 		$no_rows = mysql_num_rows($cur_set);
 		$content = "<table width=70% height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center><tr class = 'headings'><td colspan=3>PMT Settings</td></tr>";
 		$resp = new xajaxResponse();
 		if($no_rows > 0){
 			while($row = mysql_fetch_assoc($cur_set)){
 				if($req == "modify")
 					if($row['field']=="Organization Type"){
 						$content .= "<tr><td>".$row['field']."</td><td><select name ='".$row['id']."' id='".$row['id']."'><option value='".$row['value']."'>".$row['value']."</option>";
 						foreach($org_type as $orgT)
 							$content .= $orgT != $row['value'] ? "<option value='".$orgT."'>".$orgT."</option>": NULL;
 						$content .="</select></td><td><input type='button' value='Modify' onclick=\"xajax_pmtRegSettings('".$row['id']."','".$row['field']."',getElementById('".$row['id']."').value,'modify');\"> | <input type='button' value='Delete' onclick=\"xajax_pmtRegSettings('".$row['id']."','".$row['field']."',getElementById('".$row['id']."').value,'delete');\"></td></tr>";
 						}
 					else
 						$content .= "<tr><td>".$row['field']."</td><td><input type='text' name ='".$row['id']."' id='".$row['id']."' value='".$row['value']."'></td><td><input type='button' value='Modify' onclick=\"xajax_pmtRegSettings('".$row['id']."','".$row['field']."',getElementById('".$row['id']."').value,'modify');\"> | <input type='button' value='Delete' onclick=\"xajax_pmtRegSettings('".$row['id']."','".$row['field']."',getElementById('".$row['id']."').value,'delete');\"></td></tr>";
 				else
 				 	$content .= "<tr><td>".$row['field']."</td><td>".$row['value']."</td></tr>";
 				 }
 			if($req == "")
 				$content .="<tr><td colspan=2 align=center><input type='button' value='Modify' onclick=\"xajax_pmtSettings('modify');\"></td></tr>";
 			else
 				$content .= "<tr><td><input type='text' name='field' id='field'></td><td><input type='text' name ='val' id='val' value=''></td><td><input type='button' value='Add' onclick=\"xajax_pmtRegSettings('',getElementById('field').value,getElementById('val').value,'new');\">";
 		}
 		else {
 			foreach($defaultFields as $field => $value)
 				mysql_query("insert into pmt_settings(field, value) values('".$field."','".$value."')");
 			$resp->call("xajax_pmtSettings");
 			return $resp;
 		}
 		$content .="</table>";
 		$resp->assign("display_div","innerHTML",$content);
 		return $resp;
 				
 }
 
 #reg pmt settings
 function pmtRegSettings($id="",$field="",$value="",$req=""){
 	$resp = new xajaxResponse();
 	//$resp->alert("i have been called by ".$req." field is ".$field." and value is ".$value);
 	switch($req){
 		case "new":
 			if(!empty($field))
 				mysql_query("insert into pmt_settings(field, value) values('".$field."','".$value."')") or die(mysql_error());
 		break;
 		
 		case "modify":
 			if(!empty($id) && !empty($field))
 				mysql_query("update pmt_settings set field='".$field."', value='".$value."' where id=".$id) or die(mysql_error);
 		break;
 		
 		case "delete":
 			//$resp->confirmCommands(1,"Are you sure you want to delete this property?");
 			mysql_query("delete from pmt_settings where id=".$id) or die(mysql_error());
 		break;
 		default:
 		break;
 	}
 	$resp->call("xajax_pmtSettings", "modify");
 	return $resp;
 }
 
 
 //savings products
function add_saveproduct(){
	$resp = new xajaxResponse();
	
	$content .="   <div class='col-md-12'>
  <form method='post' class='panel panel-default'>";
$content .= '<div class="panel-heading">
  			  		
                                 
                                                	<h4>CREATE A SAVINGS PRODUCT</h4>
                                               		
                                           	 </div>
                                        <div class="panel-body">';
                                        
	 
	$content .= '                                         
                                            <div class="col-sm-5"><label class="control-label">Branch:</label>'.branch().'</div>                                            
                                          ';                                           
                                            $content .='<div class="form-group">
                                            
                                            <div class="col-sm-5"><label class="control-label">Product Name(From Chart of Accounts):</label>
                                            <select name="account_id" id="account_id" class="form-control">';
                                            $level1_res = mysql_query("select * from accounts where account_no like '211%' and account_no not like '211' and account_no <= 2119 and branch_no like '".$_SESSION['branch_no']."' and id not in (select s.account_id from savings_product s join accounts a on s.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."')");
	
	while($level1 = mysql_fetch_array($level1_res)){
		$level2_res = mysql_query("select * from accounts where account_no like '".$level1['account_no']."%' and account_no not like '".$level1['id']."' and account_no <= 2119 and branch_no like '".$_SESSION['branch_no']."' and id not in (select s.account_id from savings_product s join accounts a on s.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."')");
		
		if( mysql_numrows($level2_res) == 0){
			$resp->alert($level1['account_no']);
			$content .= "<option value='".$level1['id']."'>".$level1['account_no']. "-". $level1['name']; 
		}else{
			while($level2 = mysql_fetch_array($level2_res)){
				$level3_res = mysql_query("select * from accounts where account_no like '".$level2['account_no']."%' and account_no not like '".$level2['account_no']."' and account_no <= 211999 and branch_no like '".$_SESSION['branch_no']."' and id not in (select s.account_id from savings_product s join accounts a on s.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."')");
				if(@ mysql_numrows($level3_res) == 0)
					$content .= "<option value='".$level2['id']."'>".$level2['account_no']. "-". $level2['name']; 
				else{
					while($level3 = mysql_fetch_array($level3_res)){
						$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no not like '".$level3['id']."' and account_no <= 21199999 and branch_no like '".$_SESSION['branch_no']."' and id not in (select s.account_id from savings_product s join accounts a on s.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."')");
						if(@ mysql_numrows($level4_res) == 0)
							$content .= "<option value='".$level3['id']."'>".$level3['account_no']. "-". $level3['name']; 
						else{
							while($level4 = mysql_fetch_array($level4_res)){
								$content .= "<option value='".$level4['id']."'>".$level4['account_no']. "-". $level4['name']; 
							}
						}
					}
				}
			}
		}
	}
	$content .= '</select>
                                            </div></div>';
                                            $content .= '<div class="form-group">
                                            
                                            <div class="col-sm-5"><label class="control-label">Opening Balance:</label>
                                           <input type="int" name="opening_bal" id="opening_bal" class="form-control">
                                            </div></div>';                                            
                                             $content .= '<div class="form-group">
                                            
                                            <div class="col-sm-5"><label class="control-label">Minimum Balance:</label>
                                           <input type="int" name="min_bal" id="min_bal" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            
                                            <div class="col-sm-5"><label class="control-label">Maturity Period (Months before Loan):</label>
                                           <input type="int" name="grace_period" id="grace_period" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                           
                                            <div class="col-sm-5"> <label class="control-label">Interest Frequency:</label>
                                           <select type="int" id="int_frequency" class="form-control"><option value=0><option value=1>1 Month<option value=2>2 Months<option value=3>3 Months<option value=4>4 Months<option value=5>5 Months<option value=6>6 Months<option value=7>7 Months<option value=8>8 Months<option value=9>9 Months<option value=10>10 Months<option value=11>11 Months<option value=12>12 Months<option value=24>24 Months</select>
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                           
                                            <div class="col-sm-5"> <label class="control-label">Interest Rate % Per Annum:</label>
                                           <input type="numeric" name="int_rate" id="int_rate" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            
                                            <div class="col-sm-5"><label class="control-label">Tax % on Interest:</label>
                                           <input type="numeric" name="tax_rate" id="tax_rate" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            
                                            <div class="col-sm-5"><label class="control-label">Charge % on Withdrawal:</label>
                                           <input type="numeric" name="withdrawal_perc" id="withdrawal_perc" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                           
                                            <div class="col-sm-5"> <label class="control-label">Flat Charge on Withdrawal:</label>
                                           <input type="numeric" name="withdrawal_flat" id="withdrawal_flat" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                           
                                            <div class="col-sm-5"> <label class="control-label">Charge % on Deposit:</label>
                                           <input type="numeric" name="deposit_perc" id="deposit_perc" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                           
                                            <div class="col-sm-5"> <label class="control-label">Flat Charge on Deposit:</label>
                                           <input type="numeric" name="deposit_flat" id="deposit_flat" class="form-control">
                                            </div></div>';                                            
                                             $content .= '<div class="form-group">
                                           
                                            <div class="col-sm-5"> <label class="control-label">Monthly Charge:</label>
                                           <input type="int" name="monthly_charge" id="monthly_charge" class="form-control">
                                            </div></div></div>';
                                            $content .= "<div class='panel panel-footer'><button type='reset' class='btn btn-default' onclick=\"xajax_add_saveproduct()\">Reset</button>
                                            <button type='button' class='btn btn-primary'  onclick=\"xajax_insert_saveproduct(getElementById('account_id').value,  getElementById('opening_bal').value, getElementById('min_bal').value, getElementById('grace_period').value, getElementById('int_frequency').value, getElementById('int_rate').value,  getElementById('withdrawal_perc').value, getElementById('withdrawal_flat').value, getElementById('deposit_perc').value, getElementById('deposit_flat').value, getElementById('monthly_charge').value, getElementById('branch_id').value, getElementById('tax_rate').value); return false; \">Save</button>";
                                            $content .= '</div></form></div>';
          
          
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function edit_saveproduct($saveproduct_id){
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$resp->assign("status", "innerHTML", "");
	$content ="";
	$content .="<div class='col-md-12'><form method='post' class='panel panel-default'>";
$content .= '
  			  		<div class="panel-heading">
                                 		
                                                	<h4 panel-title>EDIT A SAVINGS PRODUCT</h4>
                                               		 
                                           	 </div>
                                        <div class="panel-body">';
                                         
	                                           
                                            $content .='<div class="form-group">
                                            <label class="control-label">Product Name(From Chart of Accounts):</label>
                                            <div class="col-sm-6">
                                            <input type=hidden name="saveproduct_id" value="'.$saveproduct_id.'"><select name="account_id" id="account_id" class="form-control">';
	$former_res = mysql_query("select a.name as name, a.id as account_id, a.account_no as account_no, s.opening_bal as opening_bal, s.min_bal as min_bal, (s.grace_period/30) as grace_period, s.tax_rate as tax_rate, s.int_frequency as int_frequency, s.int_rate as int_rate, s.pledged_account_id as pledged_account_id, s.withdrawal_perc as withdrawal_perc, s.withdrawal_flat as withdrawal_flat, s.deposit_perc as deposit_perc, s.deposit_flat as deposit_flat, s.monthly_charge as monthly_charge  from savings_product s join accounts a on s.account_id=a.id where s.id='".$saveproduct_id."'");
	$former = mysql_fetch_array($former_res);
	$content .= "<option value='".$former['account_id']."'>".$former['account_no'] ."-".$former['name'];
	$level1_res = mysql_query("select * from accounts where account_no like '211%' and account_no not like '211' and account_no <= 2119 and branch_no like '".$_SESSION['branch_no']."' and id not in (select s.account_id from savings_product s join accounts a on s.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."') and id<>".$saveproduct_id."");
	
	while($level1 = mysql_fetch_array($level1_res)){
		$level2_res = mysql_query("select * from accounts where account_no like '".$level1['account_no']."%' and account_no not like '".$level1['account_no']."' and account_no <= 2119 and branch_no like '".$_SESSION['branch_no']."' and id not in (select s.account_id from savings_product s join accounts a on s.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."')");
		
		if( mysql_numrows($level2_res) == 0){
			//$resp->alert($level1['account_no']);
			$content .= "<option value='".$level1['id']."'>".$level1['account_no']. "-". $level1['name']; 
		}else{
			while($level2 = mysql_fetch_array($level2_res)){
				$level3_res = mysql_query("select * from accounts where account_no like '".$level2['account_no']."%' and account_no not like '".$level2['account_no']."' and account_no <= 211999 and branch_no like '".$_SESSION['branch_no']."' and id not in (select s.account_id from savings_product s join accounts a on s.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."')");
				if(@ mysql_numrows($level3_res) == 0)
					$content .= "<option value='".$level2['id']."'>".$level2['account_no']. "-". $level2['name']; 
				else{
					while($level3 = mysql_fetch_array($level3_res)){
						$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no not like '".$level3['account_id']."' and account_no <= 21199999 and branch_no like '".$_SESSION['branch_no']."' and id not in (select s.account_id from savings_product s join accounts a on s.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."')");
						if(@ mysql_numrows($level4_res) == 0)
							$content .= "<option value='".$level3['id']."'>".$level3['account_no']. "-". $level3['name']; 
						else{
							while($level4 = mysql_fetch_array($level4_res)){
								$content .= "<option value='".$level4['id']."'>".$level4['account_no']. "-". $level4['name']; 
							}
						}
					}
				}
			}
		}
	}
	$content .= '</select>
                                            </div></div>';
                                            $content .= '<div class="form-group">
                                            <label class="control-label">Opening Balance:</label>
                                            <div class="col-sm-6">
                                           <input type="int" name="opening_bal" id="opening_bal" value="'.$former['opening_bal'].'" class="form-control">
                                            </div></div>';                                            
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Minimum Balance:</label>
                                            <div class="col-sm-6">
                                           <input type="int" name="min_bal" id="min_bal" value="'.$former['min_bal'].'" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Maturity Period (Months before Loan):</label>
                                            <div class="col-sm-6">
                                           <input type="int" name="grace_period" id="grace_period"  value="'.$former['grace_period'].'" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Interest Frequency:</label>
                                            <div class="col-sm-6">
                                           <select type="int" id="int_frequency" class="form-control"><option value="'.$former['int_frequency'].'">'.$former['int_frequency'].' Months<option value=1>1 Month<option value=2>2 Months<option value=3>3 Months<option value=4>4 Months<option value=5>5 Months<option value=6>6 Months<option value=7>7 Months<option value=8>8 Months<option value=9>9 Months<option value=10>10 Months<option value=11>11 Months<option value=12>12 Months<option value=24>24 Months</select>
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Interest Rate % Per Annum:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" name="int_rate" id="int_rate" value="'.$former['int_rate'].'" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Tax % on Interest:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" name="tax_rate" id="tax_rate" value="'.$former['tax_rate'].'" class="form-control">
                                           <div id="pledged_text"><div id="pledged_put"><input type=hidden value="0" id="pledged_account_id"><input type=hidden value="0" id="former_pledged_id"></div></div>
                                            </div></div>';
                                  
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Charge % on Withdrawal:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" name="withdrawal_perc" id="withdrawal_perc"  value="'.$former['withdrawal_perc'].'" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Flat Charge on Withdrawal:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" name="withdrawal_flat" id="withdrawal_flat" value="'.$former['withdrawal_flat'].'" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class=" control-label">Charge % on Deposit:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" name="deposit_perc" id="deposit_perc" value="'.$former['deposit_perc'].'" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Flat Charge on Deposit:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" name="deposit_flat" id="deposit_flat" value="'.$former['deposit_flat'].'" class="form-control">
                                            </div></div>';                                            
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Monthly Charge:</label>
                                            <div class="col-sm-6">
                                           <input type="int" name="monthly_charge" id="monthly_charge" value="'.$former['monthly_charge'].'" class="form-control">
                                            </div></div>';
                                            $content .= "<div class=''><div class='col-sm-6'><button type='reset' class='btn btn-default' onclick=\"xajax_edit_saveproduct('".$saveproduct_id."')\">Reset</button>
                                            <button type='reset' class='btn btn-default' onclick=\"xajax_delete_saveproduct('".$saveproduct_id."')\">Delete</button>
                                            <button type='button' class='btn btn-primary' onclick=\"xajax_update_saveproduct('".$saveproduct_id."', getElementById('account_id').value,  getElementById('opening_bal').value, getElementById('min_bal').value, getElementById('grace_period').value, getElementById('int_frequency').value, getElementById('int_rate').value,  getElementById('withdrawal_perc').value, getElementById('withdrawal_flat').value, getElementById('deposit_perc').value, getElementById('deposit_flat').value, getElementById('monthly_charge').value, getElementById('tax_rate').value)\">Save</button>";
        $content .= '</div></div>';
	
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//INSERT INTO DATABASE
function insert_saveproduct($account_id, $opening_bal, $min_bal, $grace_period, $int_frequency, $int_rate, $withdrawal_perc, $withdrawal_flat, $deposit_perc, $deposit_flat, $monthly_charge,$branch_id, $tax_rate){
	$resp = new xajaxResponse();
	if($account_id=='' || $opening_bal=='' || $min_bal=='' || $grace_period=='' || $int_frequency =='' || $int_rate=='' || $withdrawal_perc=='' || $withdrawal_flat=='' || $deposit_perc=='' || $deposit_flat=='' || $monthly_charge=='' || $tax_rate=='')
		$resp->alert("You may not leave any field blank");
	else{
		$sth1 = mysql_query("select * from savings_product where account_id='$account_id'");
		if(@ mysql_numrows($sth1) > 0)
			$resp->alert("The savings product already exists! \n Select another");
		else{
			$grace_period= $grace_period * 30;
			mysql_query("insert into savings_product set account_id='".$account_id."', type='free', opening_bal='".$opening_bal."', min_bal='".$min_bal."', grace_period='".$grace_period."', int_frequency='".$int_frequency."', int_rate='".$int_rate."', withdrawal_perc='".$withdrawal_perc."', withdrawal_flat='".$withdrawal_flat."', deposit_perc='".$deposit_perc."', deposit_flat='".$deposit_flat."', monthly_charge='".$monthly_charge."', tax_rate='".$tax_rate."', branch_id=".$branch_id);

			///////////////
			$accno = mysql_fetch_assoc(mysql_query("select account_no,name from accounts where id=".$account_id));
			$action = "insert into savings_product set account_id='$account_id', type='free', opening_bal='$opening_bal', min_bal='$min_bal', grace_period='$grace_period', int_frequency='$int_frequency', int_rate='$int_rate', withdrawal_perc='$withdrawal_perc', withdrawal_flat='$withdrawal_flat', deposit_perc='$deposit_perc', deposit_flat='$deposit_flat', monthly_charge='$monthly_charge'";
			$msg = "Registered an Opening Balance of:".number_format($opening_bal,2)." into ac/no:".$accno['account_no']." - ".$accno['name'];
			log_action($_SESSION['user_id'],$action,$msg);
			//////////////////
		}
		$resp->assign("status", "innerHTML", "<font color=red>Product created</font>");
	}
	return $resp;
}

//UPDATE SAVINGS PRODUCT
function update_saveproduct($saveproduct_id, $account_id, $opening_bal, $min_bal, $grace_period, $int_frequency, $int_rate, $withdrawal_perc, $withdrawal_flat, $deposit_perc, $deposit_flat, $monthly_charge, $tax_rate){
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	if($account_id=='' || $opening_bal=='' || $min_bal=='' || $grace_period=='' || $int_frequency =='' || $int_rate=='' || $withdrawal_perc=='' || $withdrawal_flat=='' || $deposit_perc=='' || $deposit_flat=='' || $monthly_charge=='' || $tax_rate=='')
		$resp->alert("You may not leave any field blank");
	else{
		$sth1 = mysql_query("select * from savings_product where account_id='$account_id' and id<>'".$saveproduct_id."'");
		if(@ mysql_numrows($sth1) > 0)
			$resp->alert("The savings product already exists! \n Select another");
		else{
			$resp->confirmCommands(1, "Do you really want to update?");
			$resp->call('xajax_update2_saveproduct', $saveproduct_id, $account_id, $opening_bal, $min_bal, $grace_period, $int_frequency, $int_rate, $withdrawal_perc, $withdrawal_flat, $deposit_perc, $deposit_flat, $monthly_charge, $tax_rate);	
		}
	}
	return $resp;
}

//CONFIRM UPDATE OF SAVINGS PRODUCT
function update2_saveproduct($saveproduct_id, $account_id, $opening_bal, $min_bal, $grace_period, $int_frequency, $int_rate, $withdrawal_perc, $withdrawal_flat, $deposit_perc, $deposit_flat, $monthly_charge, $tax_rate){
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$grace_period= $grace_period * 30;
	if(! mysql_query("update savings_product set account_id='$account_id', type='free', opening_bal='$opening_bal', min_bal='$min_bal', grace_period='$grace_period', int_frequency='$int_frequency', int_rate='$int_rate', withdrawal_perc='$withdrawal_perc', withdrawal_flat='$withdrawal_flat', deposit_perc='$deposit_perc', deposit_flat='$deposit_flat', monthly_charge='$monthly_charge', tax_rate='$tax_rate' where id='".$saveproduct_id."'"))
		$resp->alert("ERROR: The savings product \ncould not be updated");
	else{
			
			///////////////
			$accno = mysql_fetch_assoc(mysql_query("select account_no,name from accounts where id=".$account_id));
			$action = "update savings_product set account_id='$account_id', type='free', opening_bal='$opening_bal', min_bal='$min_bal', grace_period='$grace_period', int_frequency='$int_frequency', int_rate='$int_rate', withdrawal_perc='$withdrawal_perc', withdrawal_flat='$withdrawal_flat', deposit_perc='$deposit_perc', deposit_flat='$deposit_flat', monthly_charge='$monthly_charge', tax_rate='$tax_rate' where id='".$saveproduct_id;
			$msg = "Updated Savings Product set Opening Balance:".number_format($opening_bal,2).",  min_bal:".number_format($min_bal,2)." on ac/no:".$accno['account_no']." - ".$accno['name'];
			log_action($_SESSION['user_id'],$action,$msg);
			//////////////////
		$resp->assign("status", "innerHTML", "<font color=red>Savings Product updated</font>");
	}
	return $resp;
}

//DELETE SAVINGS PRODUCT
function delete_saveproduct($saveproduct_id){
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	$sth = mysql_query("select * from mem_accounts where saveproduct_id='".$saveproduct_id."'");
	if(mysql_numrows($sth) > 0){
		$resp->alert("Cant delete this product \n The product has accounts created under it already");
	}else{
		$resp->confirmCommands(1, "Do you really want to delete?");
		$resp->call("xajax_delete2_saveproduct", $saveproduct_id);
	}
	return $resp;
}

//CONFIRM DELETION OF A SAVINGS PRODUCT
function delete2_saveproduct($saveproduct_id){
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	$accno = mysql_fetch_assoc(mysql_query("select a.account_no,a.name s.opening_bal from accounts a join savings_product s on a.id=s.account_id where s.id=".$saveproduct_id));
	if(! mysql_query("delete from savings_product where id='".$saveproduct_id."'"))
		$resp->alert("ERROR: Could not delete the savings product");
	else{
		////////////////////
		$action = "delete from savings_product where id='".$saveproduct_id."'";
			
			$msg = "Deleted from Savings Product where the Opening Balance was:".$accno['opening_bal']." on ac/no:".$accno['account_no']." - ".$accno['name'];
			log_action($_SESSION['user_id'],$action,$msg);
			//////////////////
		$resp->assign("status", "innerHTML", "<font color=red>Savings product deleted</font>");
	}
	return $resp;
}

//LIST SAVINGS PRODUCT
function list_saveproduct(){
	$resp = new xajaxResponse();
	//$resp->assign("status", "innerHTML", "");
		
	$content = '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                               <p><h4 class="semibold text-primary mt0 mb5">LIST OF SAVINGS PRODUCTS</h4></p>
                               
                               </div>';
 		$content .= '<table class="borderless">';
 				
	$sth = mysql_query("select s.id as saveproduct_id, s.type as type, a.account_no as account_no, a.name as name,s.id as id, s.account_id as account_id, s.grace_period as grace_period, s.opening_bal as opening_bal, s.min_bal as min_bal, s.int_rate as int_rate, s.withdrawal_perc as withdrawal_perc, s.withdrawal_flat as withdrawal_flat, s.tax_rate as tax_rate, s.deposit_perc as deposit_perc, s.deposit_flat as deposit_flat, s.monthly_charge as monthly_charge, s.int_frequency as int_frequency from savings_product s join accounts a on s.account_id=a.id order by a.name");
	
	if(@ mysql_numrows($sth) == 0){
		$cont = "<div><font color=red>No savings products created yet!</font></div>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
		$content .= "<thead><th><b>Savings Product</b></th><th><b>Opening Bal.</b></th><th><b>Min Bal.</b></th><th><b>Int. Rate (%)</b></th><th><b>Tax on Interest (%)</b></th><th><b>Withdrawal Charge (% of Amt)</b></th><th><b>Withdrawal Flat Charge</b></th><th><b>Deposit Charge (% of Amt)</b></th><th><b>Deposit Flat Charge</b></th><th><b>Monthly Charge</b></th><th><b>Int. Frequency(Months)</b></th><th><b>Type</b></th></thead><tbody>";
		$i = 0;
		while($row = mysql_fetch_array($sth)){
			
			//if($_SESSION['companyId']==135)  //135 exodus sacco	       
			//$name = $row['account_no']." - ".$row['name'];
			//else
			$name = ($row['type']=='free') ? "<a href='javascript:;' onclick=\"xajax_edit_saveproduct(".$row['saveproduct_id']."); return false;\">".$row['account_no']." - ".$row['name']."</a>"  :  $row['account_no']." - ".$row['name'];
			$content .= "<tr><td>".$name."</td><td>".number_format($row['opening_bal'])."</td><td>".number_format($row['min_bal'])."</td><td>".$row['int_rate']."</td><td>".$row['tax_rate']."</td><td>".$row['withdrawal_perc']."</td><td>".number_format($row['withdrawal_flat'])."</td><td>".$row['deposit_perc']."</td><td>".number_format($row['deposit_flat'])."</td><td>".number_format($row['monthly_charge'])."</td><td>".$row['int_frequency']."</td><td>".$row['type']."</td></tr>";
			$i++;
				
		}
	}
	$content .= "</tbody></table></div>";
	$resp->call("createTableJs");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//loanproducts

function add_loanloss(){
	$resp = new xajaxResponse();
			    
         $content ="<div class='col-md-12'>";
	$content .="<form method='post' class='panel panel-default'>";
$content .= '
                                               	
                                         <div class="panel-heading">
                              <h3 class="panel-title">REGISTER MANUAL LOAN LOSS</h3>
                            </div>               
                            <div class="panel-body">';
                                        
	 
	$content .= '<div class="col-sm-6">
	<div class="form-group">
                                            <label control-label">Branch:</label>
                                            <div><span>'.branch().'</span></div>                                            
                                            </div>';                                           
                                      	
	$content .= '<div class="form-group">
                                            <label control-label">Amount Paid:</label>
                                            <div>
                                           <input type="numeric" name="amount" id="amount" class="form-control">
                                            </div></div>';                                            
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Date:</label>
                                            <div>
                                          <input type="text" class="form-control" id="date" name="date" placeholder="date" />
                                            </div></div></div></div>';
                                                                                        
                                            $content .= "<div class='form-group'><div class='panel-footer'><button type='reset' class='btn btn-default' onclick='xajax_add_loanloss(); return false;'>Reset</button>
                                            <button  value='Save' class='btn btn-primary' onclick=\"xajax_insert_loanloss(document.getElementById('branch_id').value, document.getElementById('amount').value, document.getElementById('date').value); return false;\">Save</button>";
                                            $content .= '</div></div></div></form>';
                                            $resp->call("createDate","date");
          
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function insert_loanloss($branch_id, $amount,$date){
	$resp = new xajaxResponse();
	list($year,$month,$mday) = explode('-', $date);
	if($amount ==''){
		$cont="<font color=red>Please fill in the amount</font>";
		$resp->assign("status", "innerHTML", $cont);	
	}
	
	elseif($date ==''){
		$cont="<font color=red>Please Select Date</font>";
		$resp->assign("status", "innerHTML", $cont);
	
	}
	elseif($branch_id ==''){
		$cont="<font color=red>Please Select The Branch</font>";
		$resp->assign("status", "innerHTML", $cont);
	
	}
	else{
		$date = sprintf("%04d-%02d-%02d 00:00:00", $year, $month, $mday);
		mysql_query("insert into otherloan_loss set branch_id='".$branch_id."', amount='".$amount."', date='".$date."'");
		$resp->assign("status", "innerHTML", "<font color=red>Loan Loss Provision Registered!</font>");
	}
	return $resp;
}

function list_loanloss(){
	$resp = new xajaxResponse();
	$sth = mysql_query("select l.*, b.branch_name as branch_name from otherloan_loss l join branch b on l.branch_id=b.branch_no order by l.date desc");
	if(@ mysql_numrows($sth) == 0){
		$content = "<div class='col-md-12'><div class='alert alert-info'><font color='red'><center>No Loan Loss Provisions Registered Yet.</center></font></div></div>";
		$resp->assign("display_div", "innerHTML", $content);
		return $resp;
	}
	else{
				
		$content = '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <p><h4 class="semibold text-primary mt0 mb5">MANUAL LOAN LOSS PROVISSIONS</h3></p>                          
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
 		$content .= '<thead><th>DATE</th><th>AMOUNT</th><th>BRANCH</th></thead>';
		
			
		$i=0;
		while($row = mysql_fetch_array($sth)){
			//$color = ($i%2==0) ? "lightgrey" : "white";
			$content .= "<tbody><tr><td>".$row['date']."</td><td>".number_format($row['amount'],2)."</td><td>".$row['branch_name']."</td></tr>";
			$i++;
		}
		$content .="</tbody></table></div>";
		$resp->call("createTableJs");
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function showProvisions()
{
	$resp = new xajaxResponse();
	$prov_res = @mysql_query("select * from provissions order by id asc");
	if (@mysql_num_rows($prov_res) > 0)
	{
						
			$content = '<div class="col-md-12"><div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                               <p><h4 class="semibold text-primary mt0 mb5">LOAN LOSS PROVISSIONS SETTINGS</h4></p>
                               
                               </div>';
 		$content .= '<table class="table table-bordered" id="table-tool">';
 		$content .= '<th>#</th><th>RANGE</th><th>PERCENTAGE LOSS</th><th>&nbsp;</th><tbody>';
		
			
		$x = 1;
		while ($prov_row = @mysql_fetch_array($prov_res))
		{
			switch (strtolower($prov_row['range']))
			{
				case 'range1_30':
					$range = "1 - 30";
					break;
				case 'range31_60':
					$range = "31 - 60";
					break;
				case 'range61_90':
					$range = "61 - 90";
					break;
				case 'range91_120':
					$range = "91 - 120";
					break;
				case 'range121_180':
					$range = "121 - 180";
					break;
				case 'range180_over':
					$range = "More than 180 ";
					break;
			}
			/*if ($x % 2 == 0)
				$color = "lightgrey";
			else
				$color = "white";*/
			$content .= "
				    <tr>
					<td>".$x."</td><td align=center>".$range."&nbsp;days</td><td align=center>".$prov_row['percent']."</td>";
					
					 $content .= '<td class="text-center">
                                                <!-- button toolbar -->
                                                <div class="toolbazr">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-sm btn-default">Action</button>
                                                        <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
                                                            <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-right">
                                                            <li><a onclick=\'xajax_editProvisions("'.$prov_row['id'].'", "'.$range.'", "'.$prov_row['percent'].'"); return false;\'><i class="icon ico-pencil"></i>Update</a></li>                                                            
                                                        </ul>
                                                    </div>
                                                </div>
                                                <!--/ button toolbar -->
                                            </td>
				    </tr>
				    ';
			$x++;
		}
		$content .= "</tbody></table></div></center>";
		$resp->call("createTableJs");
	}
	else
		$content = "<font color='red'>No Loan Loss Provisions yet registered.</font>";
	
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function editProvisions($pid, $range, $percent)
{
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
        $content ="";
		    
		 $content = '
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">EDIT LOAN LOSS PROVISSIONS</h3>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="col-sm-6"> <div class="form-group">
                                    
                                       
                                            <label class="control-label">Range:</label>
                                         <span>'.$range.'Days</span>                                            
                                        </div>
                       <div class="form-group">
                                    
                                       
                                            <label class="control-label">Percentage:</label>
                                            <input type="numeric" id="percentage" class="form-control" name="percentage" value="'.$percent.'">
                                        </div>
                                    </div>
                                </div>';
	
	$content .= '<div class="panel-footer">
	
	              <button type="reset" class="btn btn-inverse" onclick=\'xajax_showProvisions(); return false;\'>Back</button>
                                <button type="submit" class="btn btn-primary" onclick=\'xajax_updateProvisions2("'.$pid.'", getElementById("percentage").value); return false;\'>Update</button>                              
                            </div>
                        </form>
                        <!--/ Form default layout -->
                   ';	
                    
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function updateProvisions2($pid, $percent)
{
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	if ($pid == '' || $percent == '')
	{
		$resp->alert("Please fill in all the fields.");
		return $resp;
	}
	$resp->confirmCommands(1, "Do you really want to update this provission to $percent%?");
	$resp->call('xajax_updateProvisions', $pid, $percent);
	return $resp;
}

function updateProvisions($pid, $percent)
{       $content ="";
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$upd_res = mysql_query("UPDATE provissions set percent = '".$percent."' where id = '".$pid."'");
	if (isset($upd_res) && @mysql_affected_rows() != -1)
		$content .= "<font color='red'>Loan Loss Provision successfully updated.</font><br>";
	else{
		$content .= "<font color='red'>Loan Loss Provision not updated.</font><br>";
	}
	$resp->assign("status", "innerHTML", $content);
	$resp->call("xajax_showProvisions");
	return $resp;
}
//create loan product
function add_loanproduct(){
$resp = new xajaxResponse();

$content ="<div class='col-md-12'>";
	$content .="<form method='post' class='panel panel-default form-horizontal form-bordered'>";
$content .= '
                                <div class="panel-heading">
                              <h3 class="panel-title">CREATE A LOAN PRODUCT</h3>
                            </div>     <div class="panel-body">';	 
	$content .= '<div class="col-sm-6"><div class="form-group required">
                                            <label class="col-sm-3 control-label">Product Name:</label>
                                            <div class="col-sm-6">
                                            <select name="account_id" id="account_id" class="form-control"><option value="">--select--</option>";';
                                            $level1_res = mysql_query("select * from accounts where account_no like '113%' and account_no not like '111' and account_no <= 1119 and branch_no like '".$_SESSION['branch_no']."' and id not in (select p.account_id from loan_product p join accounts a on p.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."')");
	
	while($level1 = mysql_fetch_array($level1_res)){
		$level2_res = mysql_query("select * from accounts where account_no like '".$level1['account_no']."%' and account_no not like '".$level1['account_no']."' and branch_no like '".$_SESSION['branch_no']."' and id not in (select p.id from loan_product p join accounts a on p.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."')");
		
		if( @ mysql_numrows($level2_res) == 0){
		
			$resp->call($level1['account_no']);
			$content .= "<option value='".$level1['id']."'>".$level1['account_no']. "-". $level1['name']; 
		}else{
			while($level2 = mysql_fetch_array($level2_res)){
				$level3_res = mysql_query("select * from accounts where account_no like '".$level2['account_no']."%' and account_no not like '".$level2['account_no']."'  and branch_no like '".$_SESSION['branch_no']."' and id not in (select p.account_id from loan_product p join accounts a on p.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."')");
				if(@ mysql_numrows($level3_res) == 0)
					$content .= "<option value='".$level2['id']."'>".$level2['account_no']. "-". $level2['name']; 
				else{
					while($level3 = mysql_fetch_array($level3_res)){
						$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no not like '".$level3['account_no']."' and branch_no like '".$_SESSION['branch_no']."' and id not in (select p.account_id from loan_product p join accounts a on p.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."')");
						if(@ mysql_numrows($level4_res) == 0)
							$content .= "<option value='".$level3['id']."'>".$level3['account_no']. "-". $level3['name']; 
						else{
							while($level4 = mysql_fetch_array($level4_res)){
								$content .= "<option value='".$level4['id']."'>".$level4['account_no']. "-". $level4['name']; 
							}
						}
					}
				}
			}
		}
	}
	$content .= '</select>
                                            </div></div>';
                                            $content .= '<div class="form-group required">
                                            <label class="col-sm-3 control-label">Interest Method:</label>
                                            <div class="col-sm-6">
                                           <select id="int_method" class="form-control"><option value="">--select--</option><option value="Declining Balance">Declining Balance<option value="Flat">Flat<option value="Armotised">Amortised</select>
                                            </div></div>';
                                            
                                            $content .= '<div class="form-group required">
                                            <label class="col-sm-3 control-label">Payment Frequency:</label>
                                            <div class="col-sm-6">
                                          <select type="int" id="pay_frequency" class="form-control"><option value="">--select--</option><option value="weekly">Weekly</option><option value="biweekly">Bi-Weekly</option><option value="montly">Montly</option><option value="quarterly">Quarterly</option></select>
                                            </div></div>';
                                            
                                            $content .= '<div class="form-group required">
                                            <label class="col-sm-3 control-label">Interest Rate(%):</label>
                                            <div class="col-sm-6">
                                           <input type="int" name="int_rate" id="int_rate" class="form-control">
                                            </div></div>';
                                            $content .= '<div class="form-group required">
                                            <label class="col-sm-3 control-label">Interest Type:</label>
                                            <div class="col-sm-6">
                                          <select type="int" id="int_type" class="form-control"><option value="">--select--</option><option value="Per Installment">Per Installment</option><option value="Fixed">Fixed</option></select>
                                            </div></div>';
                                            
                                                                                                                                                                            
                                             $content .= '<div class="form-group required">
                                            <label class="col-sm-3 control-label">Penalty Rate (% Based on Frequency):</label>
                                            <div class="col-sm-6">
                                           <input type="numeric(6,3)" name="penalty_rate" id="penalty_rate" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group required">
                                            <label class="col-sm-3 control-label">Grace Period (Days):</label>
                                            <div class="col-sm-6">
                                           <input type="int" name="grace_period" id="grace_period" value=0 class="form-control">
                                            </div></div></div><div class="col-sm-6">';
                                             $content .= '<div class="form-group required">
                                            <label class="col-sm-3 control-label">Arrears Maturity (Days before a payment becomes arrears):</label>
                                            <div class="col-sm-6">
                                           <input type="int" name="arrears_period" id="arrears_period" value=0 class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group required">
                                            <label class="col-sm-3 control-label">Maximum Loan Period:</label>
                                            <div class="col-sm-6">
                                           <input type="int" name="loan_period" id="loan_period" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group required">
                                            <label class="col-sm-3 control-label">Maximum Loan Amount:</label>
                                            <div class="col-sm-6">
                                           <input type="big" name="max_loan_amt" id="max_loan_amt" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group required">
                                            <label class="col-sm-3 control-label">Write-off Period (% Based on Frequency):</label>
                                            <div class="col-sm-6">
                                           <input type="int" name="writeoff_period" id="writeoff_period" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group required">
                                            <label class="col-sm-3 control-label">Based On:</label>
                                            <div class="col-sm-6">
                                           <select id="based_on" class="form-control"><option value=""><option value="savings">Savings<option value="shares">Shares</select>
                                            </div></div>';
                                             
                                            $content .= '<div class="center"><button type="reset" class="btn btn-default" onclick=\"xajax_add_loanproduct()\">Reset</button>
                                            <button type="button" class="btn btn-primary" onclick=\'xajax_insert_loanproduct(getElementById("account_id").value, getElementById("int_rate").value, getElementById("penalty_rate").value, getElementById("int_method").value, getElementById("grace_period").value, getElementById("arrears_period").value, getElementById("loan_period").value, getElementById("max_loan_amt").value, getElementById("writeoff_period").value, getElementById("based_on").value,getElementById("pay_frequency").value,getElementById("int_type").value); return false; \'>Save</button><br><br>';
                                            $content .= '<div id="status"></div></div></div>';
                                     
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//edit loan product
function edit_loanproduct($loanproduct_id){
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$resp->assign("status", "innerHTML", "");
	$former_res = mysql_query("select p.id as loanproduct_id,p.repay_freq as repay_freq, p.int_rate as int_rate, p.int_method as int_method, p.grace_period as grace_period, p.arrears_period as arrears_period, p.loan_period as loan_period, p.writeoff_period as writeoff_period, p.max_loan_amt as max_loan_amt,p.penalty_rate as penalty_rate, p.int_type as int_type, a.id as account_id, a.name as name, a.account_no as account_no from loan_product p join accounts a on p.account_id=a.id where p.id='".$loanproduct_id."'");
	$former = mysql_fetch_array($former_res);
	
	$content ="<div class='col-md-12'><form method='post' class='panel panel-default'>";
$content .= '<div class="panel-heading">
                              <h3 class="panel-title">Edit Loan Product</h3>
                            </div>               
                            <div class="panel-body">';
                                             
                                              $content .= "<div class='col-sm-6'>
                                              <div class='form-group'>
	                                   	                                   
                                            <label class='control-label'>Product Name:</label>
                                          <div><select name='account_id' id='account_id' class='form-control'><option value='".$former['account_id']."'>".$former['account_no']." - ".$former['name'];
	$level1_res = mysql_query("select * from accounts where account_no like '111%' and account_no not like '111' and account_no <= 1119 and branch_no like '".$_SESSION['branch_no']."' and id not in (select p.account_id from loan_product p join accounts a on p.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."')");
	
	while($level1 = mysql_fetch_array($level1_res)){
		$level2_res = mysql_query("select * from accounts where account_no like '".$level1['account_no']."%' and account_no not like '".$level1['account_id']."' and account_no <= 1119 and branch_no like '".$_SESSION['branch_no']."' and id not in (select p.account_id from loan_product p join accounts a on p.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."')");
		
		if( @ mysql_numrows($level2_res) == 0){
			$resp->alert($level1['account_no']);
			$content .= "<option value='".$level1['id']."'>".$level1['account_no']. "-". $level1['name']; 
		}else{
			while($level2 = mysql_fetch_array($level2_res)){
				$level3_res = mysql_query("select * from accounts where account_no like '".$level2['account_no']."%' and account_no not like '".$level2['account_no']."' and account_no <= 111999 and branch_no like '".$_SESSION['branch_no']."' and id not in (select p.account_id from loan_product p join accounts a on p.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."')");
				if(@ mysql_numrows($level3_res) == 0)
					$content .= "<option value='".$level2['id']."'>".$level2['account_no']. "-". $level2['name']; 
				else{
					while($level3 = mysql_fetch_array($level3_res)){
						$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no not like '".$level3['account_id']."' and account_no <= 11199999 and branch_no like '".$_SESSION['branch_no']."' and id not in (select p.account_id from loan_product p join accounts a on p.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."')");
						if(@ mysql_numrows($level4_res) == 0)
							$content .= "<option value='".$level3['id']."'>".$level3['account_no']. "-". $level3['name']; 
						else{
							while($level4 = mysql_fetch_array($level4_res)){
								$content .= "<option value='".$level4['id']."'>".$level4['account_no']. "-". $level4['name']; 
							}
						}
					}
				}
			}
		}
	}
	$grace_period = $former['grace_period'];
	$loan_period = $former['loan_period'];
	$arrears_period = $former['arrears_period'];
	$writeoff_period = $former['writeoff_period'];
	$content .= "</select></div></div>";                                                                                         			                                          
                                            $content .= "<div class='form-group'>
	                                   	                                   
                                            <label class='control-label'>Interest Method:</label>
                                         <div><select id='int_method' class='form-control'><option value='".$former['int_method']."'>".$former['int_method']."<option value='Declining Balance'>Declining Balance<option value='Flat'>Flat<option value='Armotised'>Armotised</select>
                                            </div></div>";
                                            
                                            $content .= "<div class='form-group'>
	                                   	                                   
                                            <label class='control-label'>Repayment Frequency:</label>
                                         <div><select id='repay_freq' class='form-control'><option value='".$former['repay_freq']."'>".$former['repay_freq']."<option value='weekly'>Weekly<option value='biweekly'>Bi Weekly<option value='monthly'>Monthly<option value='quarterly'>Quarterly</select>
                                            </div></div>"; 
                                            
                                            $content .= "<div class='form-group'>
	                                   	                                   
                                            <label class='control-label'>Interest Rate(%):</label>
                                          <div><input type='int' id='int_rate' value='".$former['int_rate']."' class='form-control'>
                                            </div></div>";
                                            
                                            $content .= "<div class='form-group'>
	                                   	                                   
                                            <label class='control-label'>Interest Type:</label>
                                         <div><select type='int' id='int_type' class='form-control'><option value='".$former['int_type']."'>".$former['int_type']."<option value='Per Installment'>Per Installment</option><option value='Fixed'>Fixed</option></select>
                                            </div></div>"; 
                                             $content .= "<div class='form-group'>
	                                   	                                   
                                            <label class='control-label'>Penalty Rate (% Based on Frequency):</label>
                                          <div><input type=numeric(6,3) id='penalty_rate' value='".$former['penalty_rate']."' class='form-control'>
                                            </div></div></div>";
                                                                            
                                             $content .= "<div class='col-sm-6'><div class='form-group'>
	                                   	                                  
                                            <label class='control-label'>Arrears Maturity (Months before a payment becomes arrears):</label>
                                          <div><input type=int id='arrears_period' value='".$arrears_period."' class='form-control'>
                                            </div></div>";
                                            
                                             $content .= "<div class='form-group'>
	                                   	                                  
                                            <label class='control-label'>Maximum Loan Period:</label>
                                        <div><input type=int id='loan_period' value='".$loan_period."' class='form-control'>
                                            </div></div>";
                                            
                                            	    $content .= "<div class='form-group'>
	                                   	                                   
                                            <label class='control-label'>Maximum Loan Amount:</label>
                                       <div><input type=bigint id='max_loan_amt' value='".$former['max_loan_amt']."' class='form-control'>
                                            </div></div>";
                                                   
                                                    
                                             	    $content .= "<div class='form-group'>
	                                   	                                   
                                            <label control-label'>Grace Period (% Based on Frequency):</label>
                                          <div><input type=int id='grace_period' value='".$grace_period."' class='form-control'>
                                            </div></div>"; 
                                            
                                            	    $content .= "<div class='form-group'>	                                   
	                                  
                                            <label class='control-label'>Write-off Period(% Based on Frequency):</label>
                                      <div><input type=int id='writeoff_period' value='".$writeoff_period."' class='form-control'>
                                            </div></div></div></div>";
                                                              
                                           	     $content .= "<div class='panel-footer'><button class='btn btn-default' onclick=\"xajax_delete_loanproduct('".$loanproduct_id."');\">Delete</button>&nbsp;<button class='btn btn-primary' onclick=\"xajax_update_loanproduct('".$loanproduct_id."', getElementById('repay_freq').value,getElementById('account_id').value, getElementById('int_rate').value, getElementById('penalty_rate').value, getElementById('int_method').value, getElementById('grace_period').value, getElementById('arrears_period').value, getElementById('loan_period').value, getElementById('max_loan_amt').value, getElementById('writeoff_period').value, getElementById('int_type').value);return false;\">Update</button>";
 
     $content .= '</div></div></form></div>';  
	
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//update loanproduct
function update_loanproduct($loanproduct_id,$repay_freq, $account_id, $int_rate, $penalty_rate, $int_method, $grace_period, $arrears_period, $loan_period, $max_loan_amt, $writeoff_period,$int_type){
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	//$resp->assign("status", "innerHTML", "");
	if($loanproduct_id=='' || $account_id=='' || $int_rate=='' || $int_method=='' || $grace_period=='' || $arrears_period=='' || $loan_period=='' || $max_loan_amt=='' || $writeoff_period=='' || $penalty_rate=='' || $repay_freq=='' || $int_type==''){
		$resp->alert("You may not leave any field blank!");
		//$resp->call('xajax_edit_loanproduct', $loanproduct_id);
		return $resp;
	}else{
		$resp->confirmCommands(1, "Do you really want to update?");
		//$resp->call('xajax_update2_loanproduct', $loanproduct_id, $account_id, $int_rate, $penalty_rate, $int_method, $grace_period, $arrears_period, $loan_period,  $max_loan_amt, $writeoff_period);
		$grace_period=$grace_period;
	$arrears_period = $arrears_period;
	$loan_period = $loan_period;
	$writeoff_period = $writeoff_period;
	mysql_query("update loan_product set account_id='".$account_id."', int_rate='".$int_rate."', int_method='".$int_method."', grace_period='".$grace_period."', arrears_period='".$arrears_period."', loan_period='".$loan_period."', max_loan_amt='".$max_loan_amt."', writeoff_period='".$writeoff_period."', penalty_rate='".$penalty_rate."',repay_freq='".$repay_freq."',int_type='".$int_type."' where id='".$loanproduct_id."'");
	////////////
	$accno = mysql_fetch_assoc(mysql_query("select name,account_no from accounts where id=".$account_id));
	$action = "update loan_product set account_id='".$account_id."', int_rate='".$int_rate."', int_method='".$int_method."', grace_period='".$grace_period."', arrears_period='".$arrears_period."', loan_period='".$loan_period."', max_loan_amt='".$max_loan_amt."', writeoff_period='".$writeoff_period."', penalty_rate='".$penalty_rate."',repay_freq='".$repay_freq."',int_type='".$int_type."' where id='".$loanproduct_id."'";
	$msg = "Edited loan Product set ac/no:".$accno['account_no']." - ".$accno['name'].", Max_loan amount:".number_format($max_loan_amt).", Write-off period:".$writeoff_period;
	log_action($_SESSION['user_id'],$action,$msg);
	////////////
	$resp->call('xajax_list_loanproducts','<font color=red>Loan Product updated!</font>');
	//$resp->assign("status", "innerHTML", "<font color=red>Loan Product updated!</font>");
	return $resp;
	}
	//return $resp;
}

//confirm update of loanproduct in DB
function update2_loanproduct($loanproduct_id,$repay_freq,$account_id, $int_rate, $penalty_rate, $int_method, $grace_period, $arrears_period, $loan_period,  $max_loan_amt, $writeoff_period,$int_type){
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$grace_period=30 * $grace_period;
	$arrears_period = 30 * $arrears_period;
	$loan_period = 30 * $loan_period;
	$writeoff_period = 30 * $writeoff_period;
	mysql_query("update loan_product set account_id='".$account_id."', int_rate='".$int_rate."', int_method='".$int_method."', grace_period='".$grace_period."', arrears_period='".$arrears_period."', loan_period='".$loan_period."', max_loan_amt='".$max_loan_amt."', writeoff_period='".$writeoff_period."', penalty_rate='".$penalty_rate."',repay_freq='".$repay_freq."',int_type='".$int_type."' where id='".$loanproduct_id."'");
	////////////
	$accno = mysql_fetch_assoc(mysql_query("select name,account_no from accounts where id=".$account_id));
	$action = "update loan_product set account_id='".$account_id."', int_rate='".$int_rate."', int_method='".$int_method."', grace_period='".$grace_period."', arrears_period='".$arrears_period."', loan_period='".$loan_period."', max_loan_amt='".$max_loan_amt."', writeoff_period='".$writeoff_period."', penalty_rate='".$penalty_rate."',repay_freq='".$repay_freq."',int_type='".$int_type."' where id='".$loanproduct_id."'";
	$msg = "Edited loan Product set ac/no:".$accno['account_no']." - ".$accno['name'].", Max_loan amount:".number_format($max_loan_amt).", Write-off period:".$writeoff_period;
	log_action($_SESSION['user_id'],$action,$msg);
	////////////
	$resp->assign("status", "innerHTML", "<font color=red>Loan Product updated!</font>");
	return $resp;
}

//DELETE LOAN PRODUCT
function delete_loanproduct($loanproduct_id){
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	$resp->assign("status", "innerHTML", "");
	$app_res = mysql_query("select * from loan_applic where product_id='".$loanproduct_id."'");
	if(@ mysql_numrows($app_res))
		$resp->alert("You cant delete this product, \nfirst delete its loan applications");
	else{
		$resp->confirmCommands(1, "Do you really want to delete?");
		$resp->call('xajax_delete2_loanproduct', $loanproduct_id);
	}
	return $resp;
}

//CONFIRM DELETION OF LOAN PRODUCT IN DB
function delete2_loanproduct($loanproduct_id){
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	mysql_query("delete from loan_product where id='".$loanproduct_id."'");
	//////////////
	$action = "delete from loan_product where id='".$loanproduct_id."'";
	$msg = "Deleted a loan product based on ".$accno['based_on']." from ac/no:".$accno['account_no']." - ".$accno['name'];
	log_action($_SESSION['user_id'],$action,$msg);
	////////////
	$resp->assign("status", "innerHTML", "<font color=red>Product deleted!</font>");
	return $resp;
}

//insert loan product into DB
function insert_loanproduct($account_id, $int_rate, $penalty_rate, $int_method, $grace_period, $arrears_period, $loan_period, $max_loan_amt, $writeoff_period, $based_on,$freq,$int_type){
	$resp = new xajaxResponse();
	$sth = mysql_query("select * from loan_product where account_id='".$account_id."'");
	if($account_id=='' || $int_rate=='' || $int_method=='' || $grace_period=='' || $arrears_period=='' || $loan_period=='' || $max_loan_amt=='' || $writeoff_period=='' || $based_on=='' || $penalty_rate=='' || $freq=='' || $int_type=='')
		//$resp->alert("You may not leave any field blank");
	$resp->assign("status", "innerHTML", "<FONT class='alert alert-danger'>You may not leave any field blank</font>");
	elseif(@ mysql_numrows($sth) >0){
		//$resp->alert("Product not registered, it already exists!");
		$resp->assign("status", "innerHTML", "<FONT class='alert alert-danger'>Product not registered, it already exists!</font>");
	}else{
		$grace_period=30 * $grace_period;
		$arrears_period = 30 * $arrears_period;
		$loan_period = 30 * $loan_period;
		$writeoff_period = 30 * $writeoff_period;

		mysql_query("insert into loan_product set account_id='".$account_id."', int_rate='".$int_rate."', penalty_rate='".$penalty_rate."', int_method='".$int_method."', grace_period='".$grace_period."', arrears_period='".$arrears_period."', loan_period='".$loan_period."', max_loan_amt='".$max_loan_amt."', writeoff_period='".$writeoff_period."', based_on='".$based_on."',repay_freq='".$freq."',int_type='".$int_type."'");

		////////////////////////
		$accno = mysql_fetch_assoc(mysql_query("select account_no,name from accounts where id=".$account_id));
	$action = "insert into loan_product set account_id='".$account_id."', int_rate='".$int_rate."', penalty_rate='".$penalty_rate."', int_method='".$int_method."', grace_period='".$grace_period."', arrears_period='".$arrears_period."', loan_period='".$loan_period."', max_loan_amt='".$max_loan_amt."', writeoff_period='".$writeoff_period."', based_on='".$based_on."',repay_freq='".$freq."',int_type='".$int_type."'";
	$msg = "Created a loan product based on: ".$based_on." with Max loan Amount set to:".$max_loan_amt." on ac/no:".$accno['account_no']." - ".$accno['name'];
	log_action($_SESSION['user_id'],$action,$msg);
	///////////////////////////
		
		$resp->assign("status", "innerHTML", "<FONT COLOR=RED>Loan Product Created</font>");
	}
	return $resp;
}

//LIST LOAN PRODUCTS
function list_loanproducts($msg){
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML",$msg);
	$content = '<div class="col-sm-12">
	 <div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <p><h4>LIST OF LOAN PRODUCTS</h4></p>
                            </div>';
 		$content .= '<table class="borderless" id="table-tools">';
	
	$sth = mysql_query("select p.id as loanproduct_id, p.repay_freq as repay_freq,p.int_rate as int_rate, p.penalty_rate as penalty_rate, p.int_method as int_method, p.based_on as based_on, p.grace_period as grace_period, p.arrears_period as arrears_period, p.loan_period as loan_period, p.writeoff_period as writeoff_period, p.int_type as int_type,p.max_loan_amt as max_loan_amt, a.id as account_id, a.name as name, a.account_no as account_no from loan_product p join accounts a on p.account_id=a.id order by a.account_no");
	if(@ mysql_numrows($sth) == 0){
		$cont = "<font color=red>No Loan Products Created Yet!</font>";
		
	}
	else{
		$content .= "<th>#</th><th>PRODUCT</th><th>FREQUENCY</th><th>INTEREST RATE (%)</th><th>INTEREST TYPE</th><th>PENALTY RATE(%/Freq)</th><th>METHOD</th><th>GRACE PERIOD(Freq)</th><th>ARREARS MATURITY(Days)</th><th>MAX. LOAN PERIOD</th><th>MAX. AMOUNT</th><th>WRITE-OFF PERIOD(Freq)</th><th>BASED ON</th>";
		$content .="<tbody>";
		$i=0;
		while($row = mysql_fetch_array($sth)){
			$content .= "<tr><td>".($i+1)."</td>";
			$grace_period = $row['grace_period'];
			$loan_period = $row['loan_period'];
			$arrears_period = $row['arrears_period'];
			$writeoff_period = $row['writeoff_period'];
			
			$content .= "<td><a href='javascript:;' onclick=\"xajax_edit_loanproduct('".$row['loanproduct_id']."'); \">".$row['account_no'] ." - ".$row['name']."</td><td>".$row['repay_freq']."</td><td>".$row['int_rate']."</td><td>".$row['int_type']."</td><td>".$row['penalty_rate']."</td><td>".$row['int_method']."</td><td>".$grace_period."</td><td>".$arrears_period."</td><td>".$loan_period."</td><td>".number_format($row['max_loan_amt'])."</td><td>".$writeoff_period."</td><td>".ucfirst($row['based_on'])."</td></tr>";
			$i++;
		}
		$content .= "</tbody></table></div>";
	$resp->call("createTableJs");
	}
	
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;

}


function uploadCustomersForm(){
  $res = new xajaxResponse();
  if(CAP_Session::get('edit')<>1){
  $resp->alert('You Dont Have Permissions to Edit');
  return $resp;
  }
  $content .= 
      "<div class='col-md-12'><form method='post' action='customers/importCustomers' enctype='multipart/form-data' class='panel panel-default'>";
  $content .= '
              <div class="panel-heading">
                                    
                                                  <h4 class="semibold panel-title"> Upload  Existing Customers</h4>
                                                   <p class="text-default nm"></p>
                                           
                                        </div><div class="panel-body"><div id="responsediv" class="alert alert-dismissable alert-warning">
                                        
          <br/>Click mirror to download the format
          <a href="./resources/templates/customerfile.xls" target="_blank" >Click here for Mirror</a>
                                        </div>
                                        ';
         $content .='<div class="col-sm-6"><div class="form-group">
                    <label class="control-label">Organization1:</label>
                    <span><select class="form-control" name="company" id="company" onchange="xajax_loadBranches(this.value);" required>
                      <option value="">Select Company</option>
                      '.libinc::getItem("company","companyId","companyName","").'
                    </select>
                    </span></div>';
                     $content .='<div class="form-group">
                    <label class="control-label">Branch1:</label>
                   
                    <div id="branchDiv">
                    
                    </div>
                    </span></div>';                               
  $content .='<div class="input-group">
    <input type="text" name="fname_" id="fname_" class="form-control"  readonly>
                     
                 <span class="input-group-btn">
                                                    <div class="btn btn-primary btn-file">
                                                        <span class="icon iconmoon-file-3"></span> Browse <input type="file" name="fname" id="fname" required>
                                                    </div>
                                                </span>
                  </div><br>';
    
  $content .='<div class="panel-footer"><input type="submit" class="btn btn-primary btn-large" name="Upload" id="Upload" value="Upload" ><button type="reset" class="btn btn-large">Cancel</button></div>
        </form>';

  $res->assign("display_div","innerHTML",$content);
  return $res;
}


function loadBranches($companyId){
	$res = new xajaxResponse();
	$cm = new Configuration_Model();
	$branches = $cm->getBraches($companyId);
	$content = '
		<select class="form-control" name="branch" id="branch" required>
                    
                    	';
        if($branches <> 0){
        	foreach($branches->result() as $row)
        		$content .= '<option value="'.$row->branch_no.'">'.$row->branch_name.'</option>';
        }
        $content .='</select>';
        $res->assign("branchDiv", "innerHTML", $content);
	return $res;
}
//post bulk payments
function add_bulk()
{       $modes="";
	$resp = new xajaxResponse();
	
	//if (strtolower($_SESSION['position']) == 'manager')
		$acc = @mysql_query("select ba.id, ac.name, ac.account_no, ba.bank from accounts ac join bank_account ba on ac.id = ba.account_id");
	/*else
		$acc = @mysql_query("select ba.id, ac.name, ac.account_no, ba.bank from accounts ac join bank_account ba on ac.id = ba.account_id where ba.id in (select bank_account_id from user_account where user_id =".$_SESSION['user_id'].")");*/
	$accts .= "<option value=''>&nbsp;</option>";
	while ($accrow = @mysql_fetch_array($acc))
	{
		$accts .= "<option value='".$accrow['id']."'>".$accrow['account_no']."-".$accrow['bank']." ".$accrow['name']."</option>";
	}
	
	$modes .= $action == 'cash'? "<option value='cash' selected>Cash/Cheque</option>" : "<option value='cash' selected>Cash/Cheque</option>";
	$modes .= $action == 'offset'? "<option value='offset' selected>Offset from Member's Savings</option>" : "<option value='offset'>Offset from Member's Savings</option>";

	$content ="";
	$content .="<form method='post' class='panel panel-default' enctype='multipart/form-data' action='transactionAccounts/importBulkPost'>";
$content .= '
  			  		<div class="panel-heading">
                                 		
                                                	<h4 class="panel-title">POST A SCHEDULE</h4>
                                               		
                                           	
                                        </div><div class="panel-body"> <div class="alert alert-warning">The excel file should have the columns: MemberNo, ReceiptNo, Shares, then the Account Codes  of the transactions <br>The file should have the etension .xls</div>';
                                                                                                           
         				$content .='<div class="form-group">
					    <label class="control-label">Organization:</label>
					    <div class="col-sm-6"><span><select class="form-control" name="company" id="company" onchange="xajax_loadBranches(this.value);" required>
					    	<option value="">Select Company</option>
					    	'.libinc::getItem("company","companyId","companyName","").'
					    </select>
					    </span></div></div>';
					     $content .='<div class="form-group">
					    <label class="control-label">Branch:</label>
					    <div class="col-sm-6"><span>
					    <div id="branchDiv">
					    
					    </div>
					    </span></div></div>';                                          
                                             $content .= '<div class="form-group">
                                             
                                            <label class="control-label">Select Dest Bank Account:</label>
                                            <div class="col-sm-6">
                                           <select name="bank_acct" class="form-control" id="bank_acct" required>'.$accts.'</select>
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Contact Person:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" class="form-control" name="contact" id="contact" required>
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">File Ref:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" name="rcpt_no" id="rcpt_no"  class="form-control" required>
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Cheque No (Optional):</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" name="cheque_no" id="cheque_no" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Date:</label>
                                            <div class="col-sm-6">
                                           <span><input type="text" class="form-control" name="date" id="date" required></span>
                                            </div></div>';
                                            $resp->call("createDate","date");
                                            
                                              $content .= '<div class="form-group">
                                            <label class="control-label">File:</label>
                                            <div class="col-sm-6">
                                            <div class="input-group">
                                                 <input type="text" name="fname_" id="fname_" class="form-control"  readonly>
                                                <span class="input-group-btn">
                                                    <div class="btn btn-primary btn-file">
                                                        <span class="icon iconmoon-file-3"></span> Browse <input type="file" name="fname" id="fname" required>
                                                    </div>
                                                </span>
                                            </div> </div>
                                        </div></div>'; 
                                            $content .= "<div class='panel-footer'><button type='reset' class='btn btn-default' onclick=\"xajax_add_bulk()\">Reset</button>
                                            <input type='submit' class='btn btn-primary' onclick=\"xajax_insert_bulk(getElementById('bank_acct').value, getElementById('contact').value, getElementById('rcpt_no').value, getElementById('cheque_no').value,  document.getElementById('date').value,getElementById('branch_id').value); return false;\" value='Upload' name='submit' id='submit'>";
                                            $content .= '</div></form></div>';
		    
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}



//INSERT BULK POST
function insert_bulk($bank_acct_id, $contact, $file_ref, $cheque_no,  $date, $branch_id){
	$resp = new xajaxResponse();
	$data = new Spreadsheet_Excel_Reader();
	// Set output Encoding.
	$data->setOutputEncoding('CP1251');
	$data->read('C:\savings_plus_backup\bulk.xls');

	if($bank_acct_id=='' || $contact=='' || $file_ref==''){
		$resp->alert("Bulk Posting rejected! Fill in all the fields!");
		return $resp;
	}
	if(! unique_ref($file_ref, '')){
		$resp->alert("Bulk Posting not done \n File Ref $file_ref  already exists");
		return $resp;
	}
	
	$numbers = array();
	$names = array();
	$calc = new Date_Calc();
	$branch = @mysql_fetch_array(@mysql_query("select share_value, prefix from branch order by branch_no limit 1"));
	$prefix = $branch['prefix'];
	$share_value = $branch['share_value'];
	//$date= sprintf("%d-%02d-%02d ", $date);
	$date = $date. date('H:i:s');

	start_trans();
	
	for ($i = 2;  $i <= $data->sheets[0]['numRows']; $i++) {
		$mem_no = $data->sheets[0]['cells'][$i][1];
		$receipt_no = $data->sheets[0]['cells'][$i][2];
		if(! unique_rcpt($receipt_no, '')){
			$resp->alert("Bulk Posting not done \n ReceiptNo in line $i already exists");
			rollback();
			return $resp;
		}
		$lastnum_shares =0;
		$lasttot_value =0;
		if($mem_no == ''){
			break;
		}
		if(!preg_match("/\d+$/", $mem_no)){
			$resp->alert("Import rolled back: Invalid Member No ".$mem_no." found! \n Only digits are accepted");
			rollback();
			$resp->assign("status", "innerHTML", "<font color=red>Invalid Member No ".$mem_no." found in row:".$i."</font>");
			return $resp;
		}
	
		$mem_no = str_pad($mem_no, 3, "0", STR_PAD_LEFT);
		$mem_no = $prefix . $mem_no;
		//if($data->sheets[0]['cells'][$i][2] ==0)
		//	continue;
		$num_shares = $data->sheets[0]['cells'][$i][3] / $share_value;
	//$disbursed_amt = preg_replace("/,/", "", $data->sheets[0]['cells'][$i][3]);
		if(!preg_match("/^\d+[.]?\d*$/", $num_shares)){
			$resp->alert("Import rolled back: Invalid number of shares found! \n Only numbers are accepted");
			rollback();
			$resp->assign("status", "innerHTML", "<font color=red>Invalid number of shares ".$data->sheets[0]['cells'][$i][3]." found in row:".$i."</font>");
			return $resp;
		}

		$mem_res =mysql_query("select *, datediff(CURDATE(), dob)/365 as age from member where mem_no='$mem_no'");
	//echo("<font color=red>MemberNo ".$mem_no." in row:".$i." NOT found in database<br>".mysql_error()."</font>");
		if(mysql_numrows($mem_res) == 0){
			$resp->alert("Import rolled back: Member No not found!");
			rollback();
			$resp->assign("status", "innerHTML", "<font color=red>MemberNo ".$mem_no." in row:".$i." NOT found in database<br>".mysql_error()."</font>");
			return $resp;
		}
		$mem = mysql_fetch_array($mem_res);
	
		$tot_value = $data->sheets[0]['cells'][$i][3];
	
		$num_shares = $data->sheets[0]['cells'][$i][3] / $share_value;
		
		if($tot_value !=0){
			if (! mysql_query("INSERT INTO shares set shares = ".$num_shares.", value = ".$tot_value.", date = '".$date."', mem_id = ".$mem['id'].", 	receipt_no = '".$receipt_no."', file_ref='".$file_ref."', bank_account = '".$bank_acct_id."', branch_id='".$branch_id."'"))
			{
				$resp->alert("ERROR: Import rolled back! \n Could not insert shares balance.");	
				rollback();
				$resp->assign("status", "innerHTML", "<font color=red>ERROR: Could not insert shares balance for row: ".$i." \n".mysql_error()."</font>");
				return $resp;
			}
			
		//UPDATE THE BANK ACCOUNT
			if(! mysql_query("update bank_account set account_balance=account_balance+".$tot_value." where id=".$bank_acct_id."")){
				$resp->alert("ERROR: Import rolled back! \n Could not update bank account balance".mysql_error());	
				rollback();
				$resp->assign("status", "innerHTML", "<font color=red>ERROR: Could not update bank/cash account balance for row: ".$i." \n".mysql_error()."</font>");
				return $resp;
			}
		}
		$tot_collections = 0;
		for($x=4; $x <=$data->sheets[0]['numRows']; $x++){     //REGISTER DEPOSITS, REPAYMENTS OF LOANS, AND OTHER INCOME CONTRIBUTIONS
			$acct_no = $data->sheets[0]['cells'][1][$x];
			$acc = mysql_fetch_array(mysql_query("select * from accounts where account_no='".$acct_no."'"));
			$account_id = $acc['id'];
			$amt_paid = $data->sheets[0]['cells'][$i][$x];
			if($amt_paid ==0)
				continue;
			
			if(preg_match("/^111/", $data->sheets[0]['cells'][1][$x])){  //loan repayment
				$loan_res = mysql_query("select d.id as loan_id from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id join member m on applic.mem_id=m.id where m.mem_no='".$mem_no."' and a.account_no='".$acct_no."' and d.balance>0");
				if(mysql_numrows($loan_res)== 0){
					$resp->alert("ERROR: Import rolled back! \n Member ".$mem_no ." does not have a loan on ".$acct_no." product");	
					rollback();
					$resp->assign("status", "innerHTML", "<font color=red>ERROR: Import rolled back! \n Member ".$mem_no ." does not have a loan on ".$acct_no." product</font>");
					return $resp;
				}
				while($loan = mysql_fetch_array($loan_res)){
					$sched = mysql_fetch_array(mysql_query("select sum(s.princ_amt) as princ_amt, sum(s.int_amt) as int_amt from schedule s join disbursed d on s.loan_id=d.id where d.id='".$loan['loan_id']."' and s.date <='".$date."'"));
					$paid = mysql_fetch_array(mysql_query("select sum(p.princ_amt) as princ_amt, sum(p.int_amt) as int_amt from payment p join disbursed d on p.loan_id=d.id where d.id='".$loan['loan_id']."'"));
					$int_due = $sched['int_amt'] - $paid['int_due'];
					$int_due = ($int_due < 0) ? 0 : $int_due;

					$sched = mysql_fetch_array(mysql_query("select sum(s.princ_amt) as princ_amt, sum(s.int_amt) as int_amt from schedule s join disbursed d on s.loan_id=d.id where d.id='".$loan['loan_id']."'"));
					$balance = $sched['princ_amt'] - $paid['princ_amt'];

					if($amt_paid > ($balance + $int_due)){
						//$resp->alert("ERROR: Import rolled back! \n Member ".$mem_no ." has an outstanding balance of ".($balance + $int_due)." yet they are paying ".$amt_paid." for product ".$acct_no." in line ".$i);	
						//rollback();
						//$resp->assign("status", "innerHTML", "<font color=red>ERROR: Import rolled back! <br> Member ".$mem_no ." has an outstanding balance of ".($balance + $int_due)." yet they are paying ".$amt_paid." for product ".$acct_no." in line ".$i."</font>");
						//return $resp;
						$int_pay = $amt_paid - $balance;
						$princ_pay = $balance;
						$end_bal = 0;
					}elseif($int_due > $amt_paid){
						$int_pay = $amt_paid;
						$princ_pay =0;
						$end_bal = $balance;
					}else{
						$int_pay = $int_due;
						$princ_pay = $amt_paid - $int_due;
						$end_bal = $balance - $princ_pay;
					}
					if(! mysql_query("insert into payment set loan_id='".$loan['loan_id']."', receipt_no='".$receipt_no."', princ_amt='".$princ_pay."', int_amt='".$int_pay."', mode='cash', begin_bal='".$balance."', end_bal='".$end_bal."', date='".$date."', file_ref='".$file_ref."', bank_account='$bank_acct_id', branch_id= (select branch_id from disbursed where id='".$loan['loan_id']."')")){	
						$resp->alert("ERROR: Could not insert into the payment table in line $i");
						rollback();
						return $resp;
					}
					mysql_query("update disbursed set balance='".$end_bal."' where id='".$loan['loan_id']."'");
					$amt_paid = $amt_paid - $princ_pay - $int_pay;
					if($amt_paid ==0)
						continue;
				}

			}elseif(preg_match("/^211/", $data->sheets[0]['cells'][1][$x])) {  //end if loan repayment
				$mem = mysql_fetch_array(mysql_query("select mem.id as memaccount_id from mem_accounts mem join member m on mem.mem_id=m.id join savings_product p on mem.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.account_no='".$acct_no."' and m.mem_no='".$mem_no."'"));
				if($mem['memaccount_id'] =='' || $mem['memaccount_id'] ==NULL){
					$resp->alert("ERROR: Import rolled back! \n Member ".$mem_no ." does not have an account on ".$acct_no." product in line $i");	
					rollback();
					$resp->assign("status", "innerHTML", "<font color=red>ERROR: Import rolled back! \n Member ".$mem_no ." does not have an account on ".$acct_no." product in line $i</font>");
					return $resp;
				}
				if(! mysql_query("insert into deposit set amount='".$amt_paid."', receipt_no='".$receipt_no."', date='".$date."', memaccount_id='".$mem['memaccount_id']."', cheque_no='".$cheque."', trans_date=CURDATE(), flat_value=0, percent_value=0, branch_id='".$branch_id."', file_ref='".$file_ref."', bank_account='".$bank_acct_id."'")){
					$resp->alert("ERROR: Could not register deposit in line $i".mysql_error());
					rollback();
					return $resp;
				}

			}elseif(preg_match("/^4/", $data->sheets[0]['cells'][1][$x])){  //end if savings
				$inc_res = mysql_query("select * from other_income o join accounts a on o.account_id=a.id where a.account_no='".$acct_no."'");
				if(mysql_numrows($inc_res)== 0){
					$resp->alert("ERROR: Import rolled back! \n You need to first deposit at least once on this account ". $acct_no." individually, before Bulk Posting can be done. Line $i");	
					rollback();
					$resp->assign("status", "innerHTML", "<font color=red>ERROR: Import rolled back! \n You need to first deposit at least once on this account ". $acct_no." individually, before Bulk Posting can be done. Line $i</font>");
					return $resp;
				}
				if(! mysql_query("insert into other_income set amount='".$amt_paid."', date='".$date."', receipt_no='".$receipt_no."', account_id='".$account_id."', file_ref='".$file_ref."', bank_account='".$bank_acct_id."', branch_id='".$branch_id."', contact='".$mem_no."', cheque_no='".$cheque_no."', mode='cash'")){
					$resp->alert("ERROR: Import rolled back! \n Could not register income contribution to account ".$acct_no.". Line $i");	
					rollback();
					$resp->assign("status", "innerHTML", "<font color=red>ERROR: Import rolled back! \n You need to first deposit at least once on this account ". $acct_no." individually, before Bulk Posting can be done. Line $i</font>");
					return $resp;
				}
			}else{
				$resp->alert("ERROR: Import rolled back! \n Could not register transaction to account ".$acct_no." in line $i, you cant do bulk posting to this account.");	
				rollback();
				$resp->assign("status", "innerHTML", "<font color=red>ERROR: Import rolled back! \n Could not register transaction to account ".$acct_no." in line $i, you cant do bulk posting to this account.</font>");
				return $resp;
			}
			//UPDATE THE BANK ACCOUNT
			if(! mysql_query("update bank_account set account_balance=account_balance+".$amt_paid." where id=".$bank_acct_id."")){
				$resp->alert("ERROR: Import rolled back! \n Could not update bank account balance".mysql_error());	
				rollback();
				$resp->assign("status", "innerHTML", "<font color=red>ERROR: Could not update bank/cash account balance for row: ".$i." \n".mysql_error()."</font>");
				return $resp;
			}
		}
	}
	//REGISTER BLOCK
	if(! mysql_query("insert into bulk_post set date='".$date."', contact='".$contact."', file_ref='".$file_ref."', cheque_no='".$cheque_no."', branch_id='".$branch_id."'")){
		$resp->alert("ERROR: Import rolled back! \n Could not register the block post");	
		rollback();
		$resp->assign("status", "innerHTML", "<font color=red>ERROR: Import rolled back! \n Could not register the block post</font>");
		return $resp;
	}
	commit();
	$resp->assign("status", "innerHTML", "<font color=red>The bulk posting registered successfully!</font>");
	return $resp;
}

//LIST INDIVIDUAL POSTS
function list_ind_post($file_ref){
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	$sth = mysql_query("select 'Shares' as type, s.receipt_no as receipt_no, m.mem_no as mem_no, s.value as amount from shares s left join member m on s.mem_id=m.id where s.file_ref='".$file_ref."' UNION select 'Savings' as type, d.receipt_no as receipt_no, m.mem_no as mem_no, d.amount as amount from deposit d left join mem_accounts mem on d.memaccount_id=mem.id left join member m on mem.mem_id=m.id where d.file_ref='".$file_ref."' UNION select 'Loan Repayment' as type, p.receipt_no as receipt_no, m.mem_no as mem_no, (princ_amt + int_amt) as amount from payment p left join disbursed d on p.loan_id=d.id left join loan_applic applic on d.applic_id=applic.id left join member m on applic.mem_id=m.id where p.file_ref='".$file_ref."' UNION select 'Income Contribution' as type, i.receipt_no as receipt_no, i.contact as mem_no, i.amount as amount from other_income i where i.file_ref='".$file_ref."'");
	
	$grand =0;
	$content = '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                 <h5 class="semibold text-primary mt0 mb5">SHARING DONE</h5></div>';
                      $content.="<input type='button' class='pull-right' href=\"#\" onClick =\"$('#uploads').tableExport({type:'excel',escape:'false'});\" value='Excel'>   
                               ";
                           
 		$content .= '<table class="table table-striped table-bordered" id="uploads">';           
	//$resp->alert(mysql_error());
	if(mysql_numrows($sth) > 0){
		$content .= "<thead><th><b>#</b></th><th><b>Member No</b></th><th><b>Transaction</b></th><th><b>Receipt No</b></th><th><b>Amount</b></th></thead><tbody>";
		$i=1;
		while($row = mysql_fetch_array($sth)){
			//$color = ($i%2 == 0) ? "white" : "lightgrey";
			$content .= "<tr><td>".$i."</td><td>".$row['mem_no']."</td><td>".$row['type']."</td><td>".$row['receipt_no']."</td><td>".number_format($row['amount'], 2)."</td></tr>";
			$i++;
			$grand += $row['amount'];
		}
		$content .= "<tr><td colspan=4>TOTAL</td><td>".number_format($grand, 2)."</td></tr>";
	}
	$content .= "</tbody></table></div>";
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function list_bulk($file_ref, $from_date,$to_date){
	$resp = new xajaxResponse();
	
	//$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	//$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
	//$resp->alert($from_date);
	
	$content = '
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <p><h5 class="semibold text-primary">SEARCH FOR BULK POSTING</h5></p>
                            </div>               
                            <div class="panel-body">';
                            
                       $content .="<div class='form-group'>
                                   
                                        <div class='col-sm-6'>
                                            <label class='control-label'>File Ref:</label>
                                          <input type='text' id='file_ref' class='form-control'>
                                    </div>
                                        </div>
                                    </div>";   
                            
                      $content .='<div class="form-group">
                                    
                                        <div class="col-sm-6">
                                            <label class="control-label">Date range:</label>
                                          
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            
                                        </div>
                                    </div>';                                  
                                                                      
                
	$content .= "<div class='panel-footer'>                              
                                
                                <input type='button' class='btn  btn-primary' value='Show Report'  onclick=\"xajax_list_bulk(getElementById('file_ref').value, getElementById('from_date').value, getElementById('to_date').value); return false\">
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","from_date");
		    $resp->call("createDate","to_date");
                   //$resp->assign("display_div", "innerHTML", $content);
        if($from_date =='' || $to_date ==''){
		$cont ="<font color=red>Select the period for your report!</font>";
		$resp->assign("status", "innerHTML", $cont);
	       // return $resp;
	}  
	else{
	$sth = mysql_query("select * from bulk_post where date >= '".$from_date."' and date <= '".$to_date."' and file_ref like '%".$file_ref."%' order by date asc");
	       
	if(mysql_numrows($sth) >0){
	
	$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                               <h5 class="semibold text-primary mt0 mb5">LIST BULK POSTING</h5>
                               
                               </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
		$content .= "<thead><th><b>Date</b></th><th><b>File Reference</b></th><th><b>Total Amt</b></th><th><b>Contact</b></th><th><b>Action</b></th></tr>";
		$grand = 0;
		while($row = mysql_fetch_array($sth)){
			$shares = mysql_fetch_array(mysql_query("select sum(value) as amount from shares where file_ref='".$row['file_ref']."'"));
			$save = mysql_fetch_array(mysql_query("select sum(amount) as amount from deposit where file_ref='".$row['file_ref']."'"));
			$inc = mysql_fetch_array(mysql_query("select sum(amount) as amount from other_income where file_ref='".$row['file_ref']."'"));
			$pay = mysql_fetch_array(mysql_query("select sum(princ_amt) as princ_amt, sum(int_amt) as int_amt from payment where file_ref='".$row['file_ref']."'"));
			$total_amt = $shares['amount'] + $save['amount'] + $pay['princ_amt'] + $pay['int_amt'] + $inc['amount'];
			
			$content .= "<tr><td>".$row['date']."</td><td>".$row['file_ref']."</td><td>".number_format($total_amt, 2)."</td><td>".$row['contact']."</td><td> <a href='javascript:;'  onclick=\"xajax_list_ind_post('".$row['file_ref']."')\">View Individual Posts</a></td></tr>";
			$grand += $total_amt;
		}
		$content .= "<tr><td></td><td></td><td>".number_format($grand, 2)."</td><td></td><td></td></tr>";
	}
	else{  
	        $cont ="<font color=red>No bulk posting done in the selected period!</font>";
		$resp->assign("status", "innerHTML", $cont);
	return $resp;
	}
	$content .= "</tbody></table></div>";
	}
	$resp->call("createTableJs");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function delete_bulk($id, $file_ref, $from_date,$to_date){
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	$resp->confirmCommands(1, "Do you really want to delete?");
	$resp->call('xajax_delete2_bulk', $id, $file_ref, $from_date,$to_date);
	return $resp;
}

function delete2_bulk($id, $file_ref, $from_date,$to_date){
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	$row = mysql_fetch_array(mysql_query("select * from bulk_post where id='".$id."'"));
	$nowfile_ref = $row['file_ref'];

	//SHARES
	mysql_query("delete from shares where file_ref='".$nowfile_ref."'");
	//savings
	mysql_query("delete from deposit where file_ref='".$nowfile_ref."'");
	//other income
	mysql_query("delete from other_income where file_ref='".$nowfile_ref."'");
	return $resp;
}

function openingBalance($srcAcctId,$dstAcctId,$amt,$date,$desc,$balId,$edit){
	$resp = new xajaxResponse();
if(!empty($srcAcctId) && !empty($dstAcctId)){
$srcAcc=libinc::getItemById("accounts",$srcAcctId,"id","name");
$dstAcc=libinc::getItemById("accounts",$dstAcctId,"id","name");
}
else{
$srcAcc='Choose Account';
$dstAcc='Choose Account';
}
$level1 = @mysql_query("select id, account_no, name from accounts");
	//$level1 = @mysql_query("select id, account_no, name from accounts where account_no like '".$prefix."%' or and id NOT in (select account_id from bank_account)");
	while ($level1row = @mysql_fetch_array($level1))
	{
	        
		$level2 = @mysql_query("select id, account_no, name from accounts where account_no >= '".$level1row['account_no']."01' and account_no <= '".$level1row['account_no']."99' and id NOT in (select account_id from bank_account)");	
		if (@mysql_num_rows($level2) > 0) // there's a level2 account
		{
			while ($level2row = @mysql_fetch_array($level2))
			{
			
			
				$level3 = @mysql_query("select id, account_no, name from accounts where account_no between ".$level2row['account_no']."01 and ".$level2row['account_no']."99 and id NOT in (select account_id from bank_account)");	
				if (@mysql_num_rows($level3) > 0) // there's a level3 account
				{
					while ($level3row = @mysql_fetch_array($level3))
					{
					
					$fixed_acc .= "<option value='".$level3row['id']."'>".$level3row['account_no']." -".$level3row['name']."</option>";
					}
				}
				else /* Plain level2 accounts */
				{
					$fixed_acc .= "<option value='".$level2row['id']."'>".$level2row['account_no']."-".$level2row['name']."</option>";
				}
			} // end while level2
		}
		else /* Plain level1 accounts */
		{
			$fixed_acc .= "<option value='".$level1row['id']."'>".$level1row['account_no']."-".$level1row['name']."</option>";
		}
	}	
                    
	//if(mysql_numrows($sth) >0){ 
	
	//$bal=@mysql_fetch_array($sth);       
        $content ='<div class="row-fluid">
            
            <div class="span4">                

                <div class="widget">
                    <div class="head">
                        <div class="icon"><i class="icos-pencil2"></i></div>
                        <h2>Register Opening Balances</h2>
                    </div> 
                    <div id="divbg2">                       
                    <div class="block-fluid">

                        <div class="row-form">
                            <div class="top title">Debit-Dr.</div>
                            <div><select id="srcAcctId" name="srcAcctId" class="form-control"><option value="'.$srcAcctId.'">'.$srcAcc.' '.$fixed_acc.'</select></div>
                        </div>
                        <div class="row-form">
                            <div class="top title">Credit-Cr.</div>
                            <div><select id="dstAcctId" name="dstAcctId" class="form-control"><option value="'.$dstAcctId.'">'.$dstAcc.' '.$fixed_acc.'</select></div>
                        </div>
                        <div class="row-form">
                            <div class="top title">Amount</div>
                            <div><input type="text" id="amt" value="'.$amt.'"/></div>
                            
                        </div>
                        
                         <div class="row-form">
                           
                            <div class="top title">Date</div>
                            <div><input type="text" class="form-control" id="date" name="date" value="'.$date.'" /></div>
                        </div>
                               
                        <div class="row-form">
                            <div class="top title">Description</div>
                            <div><textarea id="desc" value="'.$desc.'">'.$desc.'</textarea></div>                                                       
                        </div>

                        <div class="toolbar bottom TAL">';
                        if($edit)
                            $content.='<button class="btn btn-primary" onclick=\'xajax_updateOpeningBalance(getElementById("srcAcctId").value, getElementById("dstAcctId").value, getElementById("amt").value, getElementById("date").value,getElementById("desc").value,"'.$balId.'"); return false;\'>Reverse</button>'; 
                        else
                           $content.='<button class="btn btn-primary" onclick=\'xajax_insertOpeningBalance(getElementById("srcAcctId").value, getElementById("dstAcctId").value, getElementById("amt").value, getElementById("date").value,getElementById("desc").value); return false;\'>Submit</button>';
                        $content.='</div>

                    </div>
                </div>
                </div>
                 </div>
                <div class="span8">                

                <div class="widget">
                    <div class="head">
                        <div class="icon"><i class=""></i></div>
                        <h2>Opening Balances</h2>
                    </div> 
                                         
                   <div id="divbg2">                      
                    <div class="block-fluid">
			 <table class="table table-bordered table-striped" id="tableSortable">
	                        <thead>
	                            <tr>
	                            	<th>#</th>
	                            	<th>Account</th>
	                            	<th>Dr.</th>
	                            	<th>Cr.</th>
	                            	<th>Date</th>
	                            	<th>Description</th>
	                            	<th></th>	                            	
	                            </tr>
	                        </thead>
	                        <tbody>';
	                       $sth = mysql_query("select * from openingBalances");
	                       $i=1;	
	                        while($bal=@mysql_fetch_array($sth)){
	                       
	                           $content.='<tr>
	                           <td>'.$i.'</td>	                                                                           
	                        <td><p>'.libinc::getItemById("accounts",$bal['sourceAccId'],"id","name").'</p> 
                                  '.libinc::getItemById("accounts",$bal['destAccId'],"id","name").'
                                </td>
	                          <td>'.$bal['amount'].'</td>
	                          <td><p>&nbsp;</p>
	                         '.$bal['amount'].'</td>
	                         <td>'.$bal['date'].'</td>
	                         <td>'.$bal['description'].'</td>
	                         <td> <div class="btn-group">
                                        <button class="btn" onclick=\'xajax_openingBalance("'.$bal['sourceAccId'].'","'.$bal['destAccId'].'","'.$bal['amount'].'","'.$bal['date'].'","'.$bal['description'].'","'.$bal['balId'].'",1); return false;\'><span class="icon-pencil"></span></button>
                                        <button class="btn" onclick=\'xajax_deleteOpeningBalance1("'.$bal['balId'].'"); return false;\'><span class="icon-remove"></span></button>
                                    </div>
	                         
	                       </td>	                              
	                            </tr>';
	                          $i++;}
	                        $content.='</tbody>
	                    </table>
                    </div>
                </div>
                 </div>
              
            </div>';
	 $resp->call("createDate","date");                                           
                                                                                    		
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}



function insertOpeningBalance($source_id, $dest_id, $amount, $date,$desc){
	list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	//$resp->assign("status", "innerHTML", "");
	if($source_id=='' || $dest_id=='' || $amount==''){
		$resp->alert("You may not leave any field blank ".$dest_id);
		return $resp;
	}
	if(!$calc->isValidDate($mday, $month, $year)){
		$resp->alert("Transaction rejected! Please enter valid date");
		return $resp;
	}
	
	//$sth = mysql_query("select account_balance - ".$amount." as account_balance, min_balance from bank_account where id=$source_id");
	//$row = mysql_fetch_array($sth);
	//$date = sprintf("%d-%02d-%02d", $date);
	if($dest_id == $source_id){
		$resp->alert("Transaction Failed: \nYou must enter different accounts");
		return $resp;
	}
	/*if($row['account_balance'] < 0){
		$resp->alert("Transfer Failed: \nInsufficient funds on the source account");
		return $resp;
	}*/
	/*if($row['account_balance'] < $row['min_balance']){
		$resp->alert("Transfer Failed: \nAccount Balance of the source account would go below the required minimum");
		return $resp;
	}*
	start_trans();
	if(!mysql_query("update bank_account set account_balance = account_balance - ".$amount." where id=$source_id")){
		$resp->alert("Transfer Failed: \n Could not update source account balance");
		rollback();
		return $resp;
	}
	if(!mysql_query("update bank_account set account_balance = account_balance + ".$amount." where id=$dest_id")){
		$resp->alert("Transfer Failed: \n Could not update Destination account balance");
		rollback();
		return $resp;
	}*/
	if(! mysql_query("insert into openingBalances set sourceAccId=".$source_id.", destAccId=".$dest_id.", amount=".$amount.", date='".$date."',description='".$desc."'")){
		$resp->alert("Transaction Failed: \n Could not register the Transaction");
		rollback();
		return $resp;
	}
	commit();
	$resp->assign("status", "innerHTML", "<font color=green>Transaction successfull!</font>");
	$resp->call("xajax_openingBalance");
	return $resp;
}


function updateOpeningBalance($source_id, $dest_id, $amount, $date,$desc,$id){
	list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	//$resp->assign("status", "innerHTML", "");
	if($source_id=='' || $dest_id=='' || $amount==''){
		$resp->alert("You may not leave any field blank ".$dest_id);
		return $resp;
	}
	if(!$calc->isValidDate($mday, $month, $year)){
		$resp->alert("Transaction rejected! Please enter valid date");
		return $resp;
	}

	if($dest_id == $source_id){
		$resp->alert("Transaction Failed: \nYou must enter different accounts");
		return $resp;
	}
	
	if(! mysql_query("update openingBalances set sourceAccId=".$source_id.", destAccId=".$dest_id.", amount=".$amount.", date='".$date."',description='".$desc."' where balId=$id")){
		$resp->alert("Transaction Failed: \n Could not update the Transaction");
		rollback();
		return $resp;
	}
	commit();
	$resp->assign("status", "innerHTML", "<font color=green>Transaction Updated!</font>");
	$resp->call("xajax_openingBalance");
	return $resp;
}

function listOpeningBalances($from_date,$to_date){
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	//$from_date = sprintf("%d-%02d-%02d", $from_year, $from_month, $from_mday);
	//$to_date = sprintf("%d-%02d-%02d", $to_year, $to_month, $to_mday);
	
	$content = '
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <p><h5 class="semibold text-primary mt0 mb5">LIST OF OPENING BALANCES</h5></p>
                            </div>               
                            <div class="panel-body">';
                            
                      $content .='<div class="form-group">
                                   
                                        <div class="col-sm-6">
                                            <label class="control-label">Date range:</label>
                                           <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>
                                    </div>
                                        </div>
                                    </div>';                                  
                                                                      
                
	$content .= "<div class='panel-footer'>                              
                                
                                <input type='button' class='btn  btn-primary' value='Show Report'  onclick=\"xajax_list_transfer(getElementById('from_date').value,getElementById('to_date').value);return false;\">
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","from_date");
		    $resp->call("createDate","to_date");
                  //$resp->assign("display_div", "innerHTML", $content);
                   
         if($from_date =="" || $to_date ==""){
	        $cont ="<font color=red>Select the period for your report!</font>";
		$resp->assign("status", "innerHTML", $cont);
		
	//return $resp;
	}
	else{
	
	$sth = mysql_query("select * from cash_transfer where date >= '".$from_date."' and date <= '".$to_date."' order by date asc");
                    
	if(mysql_numrows($sth) >0){
	
	
	$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <p><h5  class="semibold text-primary mt0 mb5">LIST OF CASH TRANSFERS</h5></p>
                               
                               </div>';
 		$content .= '<table class="table" id="">';
		
		$content .= "<thead><th><b>Date</b></th><th><b>Amount</b></th><th><b>Source Account</b></th><th><b>Destination Account</b></th><th></th></thead><tbody>";
		while($row = mysql_fetch_array($sth)){
			$source = mysql_fetch_array(mysql_query("select b.bank, a.name, a.account_no from bank_account b join accounts a on b.account_id=a.id where b.id=".$row['source_id'].""));
			$dest = mysql_fetch_array(mysql_query("select b.bank, a.name, a.account_no from bank_account b join accounts a on b.account_id=a.id where b.id=".$row['dest_id'].""));
			$content .= "<tr><td>".$row['date']."</td><td>".number_format($row['amount'], 2)."</td><td>".$source['account_no']." - ".$source['bank']." ".$source['name']."</td><td>".$dest['account_no']." - ".$dest['bank']." ".$dest['name']."</td><td><a href='javascript:;' onclick=\"xajax_delete_transfer(".$row['id'].", ".$from_date.", ".$to_date.")\">Delete</td></tr>";
		}
	}
	else{
		$cont ="<font color=red>No Opening Balances Added Yet!</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
	}
	$content .= "</tbody></table></div>";
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


function deleteOpeningBalance1($id){
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	$resp->confirmCommands(1, "Do You Really Want to Delete?");
	$resp->call('xajax_deleteOpeningBalance2', $id);
	return $resp;
}

function deleteOpeningBalance2($id){
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	
	mysql_query("delete from openingBalances where balId='".$id."'");
	$resp->call('xajax_openingBalance', $id);
	return $resp;
}

function shares_settings(){
$resp = new xajaxResponse();

        $content ="<div class='col-md-12'>";
	$content .="<form method='post' class='panel panel-default form-horizontal form-bordered'>";
        $content .= '
                     <div class="panel-heading">
                     <h3 class="panel-title">SHARES SETTINGS</h3>
                     </div><div class="panel-body">';	 
                                            $content .= '<div class="form-group required">
                                            <label class="col-sm-3 control-label">Shares Value:</label>
                                            <div class="col-sm-3">
                                            <input type="int" onkeyup="format_as_number(this.id)" id="value" class="form-control">
                                            </div></div>';
                                            
                                            $content .= '<div class="form-group required">
                                            <label class="col-sm-3 control-label">Maximum Share Percentage(%) Owned by a Member:</label>
                                            <div class="col-sm-3">
                                            <input type="int" id="max_share_percentage" class="form-control">
                                            </div></div>';
                                            					    
					    $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Premium Percentage(%):</label>
                                            <div class="col-sm-3">
                                            <input type="int" id="premium_percentage" class="form-control" placeholder="Optional">
                                            </div></div>';
                                            
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Premium Account:</label>
                                            <div class="col-sm-3">                                          
                                            <select type=int id="premium_account" class="form-control">
                                            <option value="">Optional</option>';  
					  $accts=mysql_query("select * from accounts where (account_no >=3211 and account_no <=3219) or (account_no >=321101 and account_no <= 321199) or (account_no >=321201 and account_no <= 321299) or (account_no >=321301 and account_no <= 321399) or (account_no >=321401 and account_no <= 321499) or (account_no >=321501 and account_no <= 321599) or (account_no >=321601 and account_no <= 321699) or (account_no >=3311 and account_no <=3319) or (account_no >=331101 and account_no <= 331199) or (account_no >=331201 and account_no <= 331299) or (account_no >=331301 and account_no <= 331399) or (account_no >=331401 and account_no <= 331499) or (account_no >=331501 and account_no <= 331599) or (account_no >=331601 and account_no <= 331699) order by account_no ");
					    if(mysql_num_rows($accts) > 0){
					    while($row=mysql_fetch_array($accts)){
					    $content.= "<option value='".$row['id']."'>".$row['account_no']."-".$row['name']."</option>";    
					    }
					    } 
					    $content.='</select></div></div>';
                                                                                        
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Share Purchase Charge(Optional):</label>
                                            <div class="col-sm-3">
                                            <input type="int" id="share_charge" onkeyup="format_as_number(this.id)" class="form-control">
                                            </div></div>';
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Share Purchase Charge Account:</label>
                                            <div class="col-sm-3">                                          
                                            <select type=int id="share_charge_account" class="form-control">
                                            <option value="">Optional</option>';  
					   $accts=mysql_query("select * from accounts where (account_no >=4121 and account_no <=4129) or (account_no >=412101 and account_no <= 412199) or (account_no >=412201 and account_no <= 412299) or (account_no >=412301 and account_no <= 412399) or (account_no >=412401 and account_no <= 412499) or (account_no >=412501 and account_no <= 412599) or (account_no >=412601 and account_no <= 412699) or (account_no >=412701 and account_no <= 412799) or (account_no >=412801 and account_no <= 412899) or (account_no >=412901 and account_no <= 412999) or (account_no >=4131 and account_no <=4139) or (account_no >=413101 and account_no <= 413199) or (account_no >=413201 and account_no <= 413299) or (account_no >=413301 and account_no <= 413399) or (account_no >=413401 and account_no <= 413499) or (account_no >=413501 and account_no <= 413599) or (account_no >=413601 and account_no <= 413699) or (account_no >=413701 and account_no <= 413799) or (account_no >=413801 and account_no <= 413899) or (account_no >=413901 and account_no <= 413999) or (account_no >=4141 and account_no <=4149) or (account_no >=414101 and account_no <= 414199) or (account_no >=414201 and account_no <= 414299) or (account_no >=414301 and account_no <= 414399) or (account_no >=414401 and account_no <= 414499) or (account_no >=414501 and account_no <= 414599) or (account_no >=414601 and account_no <= 414699) or (account_no >=414701 and account_no <= 414799) or (account_no >=414801 and account_no <= 414899) or (account_no >=414901 and account_no <= 414999)order by account_no ");
					    if(mysql_num_rows($accts) > 0){
					    while($row=mysql_fetch_array($accts)){
					    $content.= "<option value='".$row['id']."'>".$row['account_no']."-".$row['name']."</option>";    
					    }
					    } 
					    $content.='</select></div></div>';
					    
					    $content .= '<div class="form-group required">
                                            <label class="col-sm-3 control-label">Shares Transfer Fund Account:</label>
                                            <div class="col-sm-3">                                           
					    <select type=int id="transfer_account" class="form-control"><option value="">--Choose Account--</option>'; 
					    $accts=mysql_query("select * from accounts where (account_no >=3211 and account_no <=3219) or (account_no >=321101 and account_no <= 321199) or (account_no >=321201 and account_no <= 321299) or (account_no >=321301 and account_no <= 321399) or (account_no >=321401 and account_no <= 321499) or (account_no >=321501 and account_no <= 321599) or (account_no >=321601 and account_no <= 321699) or (account_no >=3311 and account_no <=3319) or (account_no >=331101 and account_no <= 331199) or (account_no >=331201 and account_no <= 331299) or (account_no >=331301 and account_no <= 331399) or (account_no >=331401 and account_no <= 331499) or (account_no >=331501 and account_no <= 331599) or (account_no >=331601 and account_no <= 331699) order by account_no ");
					    if(mysql_num_rows($accts) > 0){
					    while($row=mysql_fetch_array($accts)){
					    $content.= "<option value='".$row['id']."'>".$row['account_no']."-".$row['name']."</option>";    
					    }
					    } 
					    $content.='</select></div></div>';
					                                                
                                            $content .= '<hr><div>
                                            <button type="button" class="btn btn-primary" onclick=\'xajax_insert_shares_settings(getElementById("value").value,getElementById("max_share_percentage").value,getElementById("transfer_account").value,getElementById("premium_percentage").value,getElementById("premium_account").value,getElementById("share_charge").value,getElementById("share_charge_account").value); return false; \'>Save</button><br><br>';
                                            $content .= '<div id="status"></div></div></div>';
                                     
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function insert_shares_settings($value,$max_share_percentage,$transfer_account,$premium_percentage,$premium_account,$share_charge,$share_charge_account){
$resp = new xajaxResponse();

$rows=mysql_query("select * from shares_settings");
if(mysql_num_rows($rows) > 0){
$resp->alert("Shares Settings Already Configured");
return $resp;
}
   		
$value=str_ireplace(",","",$value);
$charge=str_ireplace(",","",$share_charge);
		
if(empty($value))
{
$resp->alert("Share Value is Required");
return $resp;
} 
if(!preg_match("/\d[.]?\d*/", $value)){
$resp->alert("Enter the Right Share Value");
return $resp;
}
if(empty($max_share_percentage))
{
$resp->alert("Maximum Share Percentage is Required");
return $resp;
}
if(!preg_match("/\d[.]?\d*/", $max_share_percentage)){
$resp->alert("Enter the Right Share Percentage");
return $resp;
}
if($max_share_percentage >=100)
{
$resp->alert("Maximum Share Percentage Can not be 100");
return $resp;
}  

if(empty($premium_account) && !empty($premium_percentage))
{
$resp->alert("Choose Shares Premium Account if Premium Percentage is set ");
return $resp;
}

if(empty($premium_percentage) && !empty($premium_account))
{
$resp->alert("Set Premium Percentage if Premium Percentage is set");
return $resp;
}

if(empty($share_charge_account) && !empty($share_charge))
{
$resp->alert("Choose Shares Charge Account if Share Charge is set ");
return $resp;
} 

if(empty($share_charge) && !empty($share_charge_account))
{
$resp->alert("Enter Shares Charge if Share Charge Account is set");
return $resp;
}
if(!empty($share_charge))
{
if(!preg_match("/\d[.]?\d*/", $share_charge)){
$resp->alert("Enter the Right Share Charge");
return $resp;
}
}
if(empty($transfer_account))
{
$resp->alert("Share Transfer Fund Account is Required");
return $resp;
} 
   		  		
		if(@mysql_query("insert into shares_settings set share_value='".$value."',max_share_percentage='".$max_share_percentage."',shares_redeeming_account_id='".$transfer_account."',shares_premium_account_id='".$premium_account."',premium_percentage='".$premium_percentage."',shares_charge_account_id='".$share_charge_account."',shares_purchase_charge='".$charge."'")){
		
		$action = "insert into shares_settings set share_value='".$value."',max_share_percentage='".$max_share_percentage."',shares_redeeming_account_id='".$transfer_account."',shares_premium_account_id='".$premium_account."',premium_percentage='".$premium_percentage."',shares_charge_account_id='".$shares_charge_account."',shares_purchase_charge='".$charge."'";
		$msg = "Registered Shares Settings " ;
		log_action($_SESSION['user_id'],$action,$msg);
	        ///////////////////////
	        $resp->alert("Shares Setting Registered Successfully");
	        $resp->call('xajax_list_shares_settings');
	        }
	        else $resp->alert("Transaction Failed!".mysql_error()); 
	       
	return $resp;
}

function list_shares_settings(){
$resp = new xajaxResponse();

	       $qry=mysql_query("select * from shares_settings");
	        
	       if(mysql_num_rows($qry) > 0)
	       {
	        $content = '<div class="col-md-12">
			   <div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                               <p><h4 class="semibold text-primary mt0 mb5">SHARES SETTINGS</h4></p>                             
                               </div>';
 		$content .= '<table class="borderless" id="table-tools">';		
		$content .= "<thead><th>#</th><th><b>Share Value</b></th><th><b>Maximum Shares Percentage Held</b></th><th><b>Premium Percentage</b></th><th><b>Premium Account</b></th><th><b>Share Purchase Charge</b></th><th><b>Share Purchase Charge Account</b></th><th><b>Shares Transfer Fund Account</b></th></thead><tbody>";
		$i = 1;
		while($row = mysql_fetch_array($qry)){					
			$content .= "<tr><td>".$i."</td><td>".$row['share_value']."</td><td>".$row['max_share_percentage']."</td><td>";
			empty($row['premium_percentage']) ? $content.=0 : $content.=$row['premium_percentage'];
			$content.="</td><td>";
			empty($row['shares_premium_account_id']) ? $content.="--" : $content.=libinc::getItemById("accounts",$row['shares_premium_account_id'],"id","name");       
			$content.="</td><td>";			
			empty($row['shares_purchase_charge']) ? $content.=0 : $content.=$row['shares_purchase_charge'];
			$content.="</td><td>";
			empty($row['shares_charge_account_id']) ? $content.="--" : $content.=libinc::getItemById("accounts",$row['shares_charge_account_id'],"id","name");       
			$content.="</td>";
			$content.="<td>".libinc::getItemById("accounts",$row['shares_redeeming_account_id'],"id","name")."</td></tr>";
			$i++;				
		}
		$content .= "</tbody></table></div></div></div>";
	       }  
	       else $content.='No Settings Found'; 
	       
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function loan_charges(){
$resp = new xajaxResponse();

        $content ="<div class='col-md-12'>";
	$content .="<form method='post' class='panel panel-default form-horizontal form-bordered'>";
        $content .='
                     <div class="panel-heading">
                     <h3 class="panel-title">LOAN CHARGES</h3>
                     </div><div class="panel-body">';	 
                                            $content .= '<div class="form-group required">
                                            <label class="col-sm-3 control-label">Loan Product:</label>
                                            <div class="col-sm-3">';
                                            $prodt = mysql_query("select a.name as name, a.account_no as account_no, p.id as id from loan_product p join accounts a on p.account_id=a.id order by a.account_no, a.name"); 
                                            $content.='<select type=int id="product" class="form-control"><option value="">--Choose Account--</option>'; 
					    if(mysql_num_rows($prodt) > 0){
					    while($prod = @ mysql_fetch_array($prodt)){
		                            $content .= "<option value='".$prod['id']."'>".$prod['account_no']. " - ".$prod['name']."</option>";
	                                    }
					    } 
					    $content.='</select>
                                            </div></div>';
                                            
                                            $content .= '<div class="form-group required">
                                            <label class="col-sm-3 control-label">Charge Type:</label>
                                            <div class="col-sm-3">
                                            <select type=int id="charge_type" class="form-control" onchange=\'xajax_charge_type(this.value);\'>
                                            <option value="">--Choose--</option>
                                            <option value="income">Income</option> 
                                            <option value="payable">Payable</option>
					    </select>
					    </div></div>';
					    
					    $content.= '<div id="accts_div"></div>';
					    
					    $content.= '<div class="form-group required">
					    <label class="col-sm-3 control-label">Based on Laon Amount:</label>
					    <div class="col-sm-2">
					    <select type=int id="basedOn" class="form-control" required onchange=\'xajax_basedOn(this.value);\'>
					    <option value="0">No</option>
					    <option value=1>Yes</option>
					    </select></div></div>';
					    
					    $content.= '<div id="based_div">
					    <input type="hidden" id="less" value="0">
                                            <input type="hidden" id="above" value="0">
                                            <input type="hidden" id="loanAmt" value="0">
                                            </div>';
					    $content.= '<div id="basedAmount_div"></div>';
					    
					    $content.='<div class="form-group required">
                                            <label class="col-sm-3 control-label">Charge As:</label>
				            <div class="col-sm-3">
				            <input type="radio" id="flat" name="no" value="flat" onclick=\'xajax_chargeAs(this.value);\'/>&nbsp;Flat Amount&nbsp;&nbsp;&nbsp;&nbsp;
				            <input type="radio" id="percent" value="percentage" name="no" onclick=\'xajax_chargeAs(this.value);\'/>&nbsp;Percentage
				            </div>
				            </div>';
				       
				            $content.= '<div id="chargeAs_div"></div>';                                                                                                                                 
                                            $content.='<hr><div>
                                            <button type="button" class="btn btn-primary" onclick=\'xajax_insert_loan_charges(getElementById("product").value,getElementById("acctId").value, getElementById("amount").value,getElementById("percent_charge").value,getElementById("charge_interest").value,getElementById("charge_type").value,getElementById("basedOn").value,getElementById("loanAmt").value,getElementById("less").value,getElementById("above").value); return false; \'>Save</button><br><br>';
                                            $content .= '<div id="status"></div></div></div>';
                                     
                                            $resp->assign("display_div", "innerHTML", $content);
	                                    return $resp;
}

function charge_type($type){
$resp = new xajaxResponse();
if(empty($type)){
$resp->alert("Choose Charge Type Please");
return $resp;
}
$content.='<div class="form-group required">';

if($type=='income'){
$content.='<label class="col-sm-3 control-label">Income Account:</label>
<div class="col-sm-3">
<select type=int id="acctId" class="form-control" required><option value="">--Choose Account--</option>'; 
$accts=mysql_query("select * from accounts where (account_no >=4121 and account_no <=4129) or (account_no >=412101 and account_no <= 412199) or (account_no >=4131 and account_no <=4139) or (account_no >=413101 and account_no <= 413199)  order by account_no ");
        if(mysql_num_rows($accts) > 0){
        while($row=mysql_fetch_array($accts)){
       $content.= "<option value='".$row['id']."'>".$row['account_no']."-".$row['name']."</option>";    
        }
        } 
$content.='</div></div>';
$resp->assign("accts_div", "innerHTML", $content);
}

if($type=='payable'){
$content.='<label class="col-sm-3 control-label">Payable Account:</label>
<div class="col-sm-3">
<select type=int id="acctId" class="form-control" required><option value="">--Choose Account--</option>'; 
$accts=mysql_query("select * from accounts where (account_no >=2131 and account_no <=2139) or (account_no >=213101 and account_no <= 213199) order by account_no ");
        if(mysql_num_rows($accts) > 0){
        while($row=mysql_fetch_array($accts)){
        $content.= "<option value='".$row['id']."'>".$row['account_no']."-".$row['name']."</option>";    
        }
        } 
$content.='</div></div>';
$resp->assign("accts_div", "innerHTML", $content);
}

return $resp;
}

function insert_loan_charges($product,$acctId,$amount,$percentage,$charge_interest,$type,$basedOn,$loanAmt,$less,$above){
$resp = new xajaxResponse();
$amt=str_ireplace(",","",$amount);
$loanAmt=str_ireplace(",","",$loanAmt);

if(empty($amt) && empty($percentage))
{
$resp->alert("Enter Flat Amount or Percentage");
return $resp;
} 

if(empty($product))
{
$resp->alert("Choose a loan product please");
return $resp;
}  

if(empty($acctId))
{
$resp->alert("Choose an account please");
return $resp;
} 

if($percentage > 20 )
{
$resp->alert("Percentage charge is seemingly high, please contact mobis team for advice");
return $resp;
} 

if(empty($type))
{
$resp->alert("Choose an account type please");
return $resp;
}

if(!empty($basedOn) && empty($loanAmt))
{
$resp->alert("Loan Amount Is Required");
return $resp;
}

if($less){
$less=$loanAmt;
$above=0;
}
if($above){
$above=$loanAmt;
$less=0;
}

	      /* $qry=mysql_query("select * from loan_charges where loan_product_id=$product and charge_account_id=$acctId");
	        
	       if(mysql_num_rows($qry) > 0)
	       {
	       $resp->alert("This charge is already registered for this loan product!"); 
	       return $resp;
	       }  
   	      */
	       if(@mysql_query("insert into loan_charges set loan_product_id='".$product."',charge_account_id='".$acctId."',based_on_loan='".$basedOn."', less_or_equal='".$less."', above='".$above."', amount='".$amt."',percentage='".$percentage."',charge_interest='".$charge_interest."',charge_type='".$type."'")){
		
		$action = "insert into loan_charges set loan_product_id='".$product."',charge_account_id='".$acctId."',amount='".$amt."',percentage='".$percentage."',charge_interest='".$charge_interest."',charge_type='".$type."'";
		$charge_interest=($charge_interest==1) ? "Yes" : "No";
		$msg = "Registered Loan Charges: Loan product-'".libinc::getItemById("accounts",libinc::getItemById("loan_product",$product,"id","account_id"),"id","name")."',Account-'".libinc::getItemById("accounts",$acctId,"id","name")."',Amount-'".$amt."',Percentage-'".$percentage."',Charge interest-'".$charge_interest."',Charge type-'".$type."'" ;
		log_action($_SESSION['user_id'],$action,$msg);
	        ///////////////////////
	        $resp->alert("Loan Charge Registered Successfully");
	        $resp->call('xajax_list_loan_charges');
	        }
	        else $resp->alert("Transaction Failed!".mysql_error()); 
	       
	return $resp;
}

function chargeAs($type){
$resp = new xajaxResponse();

if($type=='flat'){
$content.='<div class="form-group required">
<label class="col-sm-3 control-label">Charge Amount:</label>
<div class="col-sm-2">
<input type="int" id="amount" onkeyup="format_as_number(this.id)" class="form-control">
<input type="hidden" id="percent_charge" value=0>
<input type="hidden" id="charge_interest" value=0>
</div>';
$resp->assign("chargeAs_div", "innerHTML", $content);
}
if($type=='percentage'){
$content.='<div class="form-group required">
<label class="col-sm-3 control-label">Percentage Charge:</label>
<div class="col-sm-2">
<input type="int" id="percent_charge" class="form-control">
<input type="hidden" id="amount" value=0>
</div></div>
<div class="form-group required">
<label class="col-sm-3 control-label">Charge Interest Inclusive:</label>
<div class="col-sm-2">
<select type=int id="charge_interest" class="form-control" required>
<option value="">No</option>
<option value=1>Yes</option>
</select></div>'; 

$resp->assign("chargeAs_div", "innerHTML", $content);
}
return $resp;
}

function list_loan_charges(){
$resp = new xajaxResponse();

	       $qry=mysql_query("select * from loan_charges");
	        
	       if(mysql_num_rows($qry) > 0)
	       {
	        $content = '<div class="col-md-12">
			   <div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                               <p><h4 class="semibold text-primary mt0 mb5">LIST OF LOAN CHARGES</h4></p>                             
                               </div>';
 		$content .= '<table class="borderless" id="table-tools">';		
		$content .= "<thead><th>#</th><th><b>Loan Product</b></th><th><b>Charge Account</b></th><th><b>Type</b></th><th><b>Based On Loan Amount</b></th><th><b>Less OR Equal To</b></th><th><b>Above</b></th><th><b>Flat Charge</b></th><th><b>Percentage Charge</b></th><th><b>Charge Interest Inclusive</b></th></thead><tbody>";
		$i = 1;
		while($row = mysql_fetch_array($qry)){					
			$content .= "<tr><td>".$i."</td><td>".libinc::getItemById("accounts",libinc::getItemById("loan_product",$row['loan_product_id'],"id","account_id"),"id","name")."</td><td>".libinc::getItemById("accounts",$row['charge_account_id'],"id","name")."</td><td>".$row['charge_type']."</td><td>";
			$row['based_on_loan']==0 ? $content.="No" : $content .="Yes";
			$content.="</td><td>";
			$row['based_on_loan']==1 ? ($row['less_or_equal'] <> 0 ? $content .=number_format($row['less_or_equal'],2) : $content.="--") : $content.="--";         
			$content.="</td><td>";
			$row['based_on_loan']==1 ? ($row['above'] <> 0 ? $content .=number_format($row['above'],2) : $content.="--") : $content.="--";;
			$content .="</td><td>";
			$row['amount']==0 ? $content.="--" : $content .=number_format($row['amount'],2);
			$content .="</td><td>";
			$row['percentage']==0 ? $content.="--" : $content .=$row['percentage'];
			$content .="</td><td>";
			$row['charge_interest']==0 ? $content.="No" : $content .="Yes";
			$content .="</td></tr>";
			$i++;				
		}
		$content .= "</tbody></table></div></div></div>";
	       }  
	       else $content.='No Charges Found'; 
	       
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function basedOn($amount){
$resp = new xajaxResponse();
if($amount){
$content.='<div class="form-group required">
<label class="col-sm-3 control-label">Range:</label>
<div class="col-sm-2">
<select type=int id="range" class="form-control" required onchange=\'xajax_basedOnAmount(this.value);\'>
<option value="0">Choose Range</option>
<option value="less">Less or Equal To</option>
<option value="above">Above</option>
</select></div></div>'; 

$resp->assign("based_div", "innerHTML", $content);
}
else{
$content.='<input type="hidden" id="less" value="0">
<input type="hidden" id="above" value="0">
<input type="hidden" id="loanAmt" value="0">';
$resp->assign("based_div", "innerHTML","");
$resp->assign("basedAmount_div", "innerHTML",$content);
}
return $resp;
}

function basedOnAmount($size){
$resp = new xajaxResponse();
if($size=='less'){
$content.='<div class="form-group required">
<label class="col-sm-3 control-label">Less or Equal To (Loan Amount):</label>
<div class="col-sm-2">
<input type="int" id="loanAmt" onkeyup="format_as_number(this.id)" class="form-control">
<input type="hidden" id="less" value="1">
<input type="hidden" id="above" value="0">
</div></div>'; 
}
if($size=='above'){
$content.='<div class="form-group required">
<label class="col-sm-3 control-label">Above(Loan Amount):</label>
<div class="col-sm-2">
<input type="int" id="loanAmt" onkeyup="format_as_number(this.id)" class="form-control">
<input type="hidden" id="above" value="1">
<input type="hidden" id="less" value="0">
</div></div>'; 
}
if($size==0){
$content.='
<input type="hidden" id="loanAmt"  value="0">
<input type="hidden" id="above" value="0">
<input type="hidden" id="less" value="0">
</div></div>'; 
}
$resp->assign("basedAmount_div", "innerHTML", $content);
return $resp;
}

function list_savings_uploads($from_date,$to_date){
	$resp = new xajaxResponse();
	
	$content = '
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <p><h5 class="semibold text-primary">SEARCH FOR UPLOADED SAVINGS SCHEDULES</h5></p>
                            </div>               
                            <div class="panel-body">';
                            
                      $content .='<div class="form-group">
                                    
                                        <div class="col-sm-6">
                                            <label class="control-label">Date range:</label>
                                          
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            
                                       </div></div>
                                    </div>';                                  
                                                                                     
	$content .= "<div class='panel-footer'>                                                             
                                <input type='button' class='btn  btn-primary' value='Show Report'  onclick=\"xajax_list_savings_uploads(getElementById('from_date').value, getElementById('to_date').value); return false\">
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","from_date");
		    $resp->call("createDate","to_date");
                   //$resp->assign("display_div", "innerHTML", $content);
        if($from_date =='' || $to_date ==''){
		$cont ="<font color=red>Select the period for your report!</font>";
		$resp->assign("status", "innerHTML", $cont);
	       // return $resp;
	}  
	else{
	$sth = mysql_query("select trans_date as date,file_ref from deposit where trans_date >= '".$from_date."' and trans_date <= '".$to_date."' and file_ref <> '' group by file_ref order by trans_date asc");
	       
	if(mysql_numrows($sth) >0){
	
	$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                               <h5 class="semibold text-primary mt0 mb5">LIST BULK POSTING</h5>
                               
                               </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
		$content .= "<thead><th><b>Date</b></th><th><b>File Reference</b></th><th><b>Total Amt</b></th><th><b>Contact</b></th><th><b>Action</b></th></tr>";
		$grand = 0;
		while($row = mysql_fetch_array($sth)){
		
			$save = mysql_fetch_array(mysql_query("select sum(amount) as amount from deposit where file_ref='".$row['file_ref']."'")); 
			$depositor = mysql_fetch_array(mysql_query("select contact from bulk_post where file_ref='".$row['file_ref']."'"));
			
			$total_amt = $save['amount'];
			
			$content .= "<tr><td>".$row['date']."</td><td>".$row['file_ref']."</td><td>".number_format($total_amt, 2)."</td><td>".$depositor['contact']."</td><td> <a href='javascript:;' onclick=\"xajax_list_ind_savings_posted('".$row['file_ref']."')\">View Individual Posts</a></td></tr>";
			$grand += $total_amt;
		}
		$content .= "<tr><td></td><td></td><td>".number_format($grand, 2)."</td><td></td><td></td></tr>";
	}
	else{  
	        $cont ="<font color=red>No bulk posting done in the selected period!</font>";
		$resp->assign("status", "innerHTML", $cont);
	return $resp;
	}
	$content .= "</tbody></table></div>";
	}
	$resp->call("createTableJs");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function list_ind_savings_posted($file_ref){
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	$sth = mysql_query("select 'Savings' as type, d.receipt_no as receipt_no, m.first_name as first_name,m.last_name as last_name,m.mem_no as mem_no,m.ipps as ipps,d.amount as amount,d.date from deposit d join mem_accounts mem on d.memaccount_id=mem.id join member m on mem.mem_id=m.id where d.file_ref='".$file_ref."'");
	
	$grand =0;
	$content = '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                 <p><h5 class="semibold text-primary mt0 mb5">SCHEDULES POSTED</h5></p>';
                             $content.="<input type='button' class='pull-right' href=\"#\" onClick =\"$('#savings').tableExport({type:'excel',escape:'false'});\" value='Excel'>   
                               </div>";
 		$content.= '<table class="table table-striped table-bordered" id="savings">';           
	//$resp->alert(mysql_error());
	if(mysql_numrows($sth) > 0){
		$content.= "<thead><th><b>#</b></th><th><b>First Name</b></th><th><b>Last Name</b></th><th><b>Member No</b></th><th><b>Ipps</b></th><th><b>Transaction</b></th><th><b>Receipt No</b></th><th><b>Amount</b></th><th><b>Date</b></th></thead><tbody>";
		$i=1;
		while($row = mysql_fetch_array($sth)){
			//$color = ($i%2 == 0) ? "white" : "lightgrey";
			$content .= "<tr><td>".$i."</td><td>".$row['first_name']."</td><td>".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".$row['ipps']."</td><td>".$row['type']."</td><td>".$row['receipt_no']."</td><td>".number_format($row['amount'], 2)."</td><td>".$row['date']."</td></tr>";
			$i++;
			$grand += $row['amount'];
		}
		$content .= "<tr><td colspan=7>TOTAL</td><td>".number_format($grand, 2)."</td></tr>";
	}
	$content .= "</tbody></table></div>";
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

?>
