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
 		$content .= '<table class="borderless" id="table-tools">
	
			
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
				$content .="
			<tr>
			   <td>".$row['branch_name']."</td>
			   <td>".$row['email']."</td>
			   <td>".$row['address']."</td>
			   <td>".$row['share_value']."</td>
			   <td>".$row['min_shares']."</td>
			   <td>".$row['max_share_percent']."</td>
			   <td>".@((100 / $row['loan_save_percent']) * 100)."</td>
			   <td>".@((100 / $row['guarantor_save_percent']) * 100)."</td>
			 <td>".@((100 / $row['loan_share_percent']) * 100)."</td>
			   <td>".@((100 / $row['guarantor_share_percent']) * 100)."</td>
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
/*		$content .= "
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
	$content .="<form method='post' action='configuration/editBranch' enctype='multipart/form-data' class='panel form-horizontal form-bordered'>";
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
                                              
                                            <label class="control-label">Email:</label>
                                            <div>
                                           <input type="text" id="email" name="email" value="'.$row['email'].'" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Address:</label>
                                            <div class="">
                                           <input type="text" id="address" name="address" value="'.$row['address'].'" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Share Value:</label>
                                            <div class="">
                                           <input type="text" id="share_value" name="share_value" value="'.$row['share_value'].'"  class="form-control">
                                            </div></div>';
                                             /*$content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Report Period:</label>
                                            <div class="col-sm-6">
                                           <input type="text" id="report_period" name="report_period" class="form-control">
                                            </div></div>';*/
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Minimum Shares:</label>
                                            <div class="">
                                           <input type="text" id="min_shares" name="min_shares" value="'.$row['min_shares'].'" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Maximum Share Percentage:</label>
                                            <div class="">
                                           <input type="text" id="max_share_per" name="max_share_per" value="'.$row['max_share_percent'].'" class="form-control">
                                            </div></div></div>';
                                             $content .= '<div class="col-sm-6"><div class="form-group">
                                            <label class="control-label">Loan Share Percentage:</label>
                                            <div>
                                           <input type="text" id="loan_share_per" name="loan_share_per" value="'.@((100 / $row['loan_share_percent']) * 100).'" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Loan Save Percentage:</label>
                                            <div class="">
                                           <input type="text" id="loan_save_per" name="loan_save_per" value="'.@((100 / $row['loan_save_percent']) * 100).'" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Guarantor Share Percentage:</label>
                                            <div class="">
                                           <input type="text" id="guarantor_share_per" name="guarantor_share_per" value="'.@((100 / $row['guarantor_share_percent']) * 100).'" class="form-control">
                                            </div></div>';                                            
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Guarantor Save Percentage:</label>
                                            <div class="">
                                           <input type="text" id="guarantor_save_per" name="guarantor_save_per" value="'.@((100 / $row['guarantor_save_percent']) * 100).'" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Branch Prefix:</label>
                                            <div class="">
                                           <input type="text" name="prefix" id="prefix"  value="'.$row['prefix'].'" class="form-control">
                                            </div></div>';
                                            /*  $content .= "<div class='panel-footer'><button type='reset' class='btn btn-default' onclick=\"xajax_showSettings()\">Reset</button>
                                            <button type='button' class='btn btn-primary'   onclick=\"xajax_saveSettings('".$row['branch_no']."',  getElementById('branch_name').value, getElementById('email').value, getElementById('address').value, getElementById('share_value').value, getElementById('min_shares').value, getElementById('max_share_per').value, getElementById('loan_share_per').value, getElementById('loan_save_per').value,
			getElementById('guarantor_share_per').value, getElementById('guarantor_save_per').value, getElementById('prefix').value); return false;\">Update</button>";*/
			$content .='<input type="submit" value="Save" name="save" id="save" class="btn btn-primary">';
                                           
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
			$loan_save_percent = (100 /$loan_save_percent) * 100;
		if($loan_share_percent != 0)
			$loan_share_percent = (100 /$loan_share_percent) * 100;
		if($guarantor_save_percent != 0) 
			$guarantor_save_percent = (100 /$guarantor_save_percent) * 100;
		if($guarantor_share_percent != 0)
			$guarantor_share_percent = (100 /$guarantor_share_percent) * 100;
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
 
?>
