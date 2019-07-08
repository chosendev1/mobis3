<?php

$xajax->registerFunction("add_loanproduct");
$xajax->registerFunction("list_loanproducts");
$xajax->registerFunction("insert_loanproduct");
$xajax->registerFunction("edit_loanproduct");
$xajax->registerFunction("update_loanproduct");
$xajax->registerFunction("update2_loanproduct");
$xajax->registerFunction("delete_loanproduct");
$xajax->registerFunction("delete2_loanproduct");
$xajax->registerFunction("add_applic");
$xajax->registerFunction("list_applics");
$xajax->registerFunction("insert_applic");
$xajax->registerFunction("edit_applic");
$xajax->registerFunction("other_applic");
$xajax->registerFunction("loan_type");
$xajax->registerFunction("guarantor");
$xajax->registerFunction("verify_guarantor");
$xajax->registerFunction("update_applic");
$xajax->registerFunction("update2_applic");
$xajax->registerFunction("delete_applic");
$xajax->registerFunction("delete2_applic");
$xajax->registerFunction("approve_applic");
$xajax->registerFunction("insert_collateral");
$xajax->registerFunction("update_collateral");
$xajax->registerFunction("add_disbursed");
$xajax->registerFunction("insert_disbursed");
$xajax->registerFunction("list_disbursed");
$xajax->registerFunction("edit_disbursed");
$xajax->registerFunction("update_disbursed");
$xajax->registerFunction("delete_disbursed");
$xajax->registerFunction("update2_disbursed");
$xajax->registerFunction("delete2_disbursed");
$xajax->registerFunction("edit_cheque");
$xajax->registerFunction("update_cheque");
$xajax->registerFunction("update2_cheque");
$xajax->registerFunction("insert_refund");
$xajax->registerFunction("insert_waiver");

$xajax->registerFunction("list_outstanding");
$xajax->registerFunction("list_due");
$xajax->registerFunction("list_arrears");

$xajax->registerFunction("add_pay");
$xajax->registerFunction("schedule");
$xajax->registerFunction("insert_pay");
$xajax->registerFunction("delete_pay");
$xajax->registerFunction("delete2_pay");

$xajax->registerFunction("list_arrears");
$xajax->registerFunction("list_cleared");
$xajax->registerFunction("add_penalty");
$xajax->registerFunction("insert_penalty");
$xajax->registerFunction("delete_penalty");
$xajax->registerFunction("delete2_penalty");
$xajax->registerFunction("write_off");
$xajax->registerFunction("write_off2");
$xajax->registerFunction("unwrite_off");
$xajax->registerFunction("unwrite_off2");
$xajax->registerFunction("list_written_off");
$xajax->registerFunction("add_recovered");
$xajax->registerFunction("insert_recovered");
$xajax->registerFunction("delete_recovered");
$xajax->registerFunction("delete2_recovered");

$xajax->registerFunction("delete_collateral");
$xajax->registerFunction("delete2_collateral");

$xajax->registerFunction("showProvisions");
$xajax->registerFunction("editProvisions");
$xajax->registerFunction("updateProvisions2");
$xajax->registerFunction("updateProvisions");
$xajax->registerFunction("add_loanloss");
$xajax->registerFunction("insert_loanloss");
$xajax->registerFunction("list_loanloss");

function add_loanloss(){
	$resp = new xajaxResponse();
			    
         $content ="";
	$content .="<form method='post' class='panel form-horizontal form-bordered'>";
$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h4 class="semibold text-primary mt0 mb5">REGISTER MANUAL LOAN LOSS</h4>
                                               		 <p class="text-default nm"></p>
                                           	 </div>
                                        </div><div id="responsediv" class="alert alert-dismissable alert-danger"></div>';
                                        
	 
	$content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Branch:</label>
                                            <div class="col-sm-6"><span>'.branch().'</span></div>                                            
                                            </div>';                                           
                                      	
	$content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Amount Paid:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" name="amount" id="amount" class="form-control">
                                            </div></div>';                                            
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Date:</label>
                                            <div class="col-sm-6">
                                          <input type="text" class="form-control" id="date" name="date" placeholder="date" />
                                            </div></div>';
                                                                                        
                                            $content .= "<div class='panel-footer'><button type='reset' class='btn btn-default' onclick='xajax_add_loanloss(); return false;'>Reset</button>
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
	if(mysql_numrows($sth) == 0){
		$content = "<font color='red'><center>No Loan Loss Provisions Registered Yet.</center></font>";
		$resp->assign("status", "innerHTML", $content);
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
						
			$content = '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                               <p><h4 class="semibold text-primary mt0 mb5">LOAN LOSS PROVISSIONS SETTINGS</h4></p>
                               
                               </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
 		$content .= '<thead><th>#</th><th>RANGE</th><th>PERCENTAGE LOSS</th><th>&nbsp;</th></thead><tbody>';
		
			
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
                                                <div class="toolbar">
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
	/*if($_SESSION['position'] <>'Manager'){
		$resp->alert("Access Denied! \nOnly the Manager can edit an item");
		return $resp;
	}*/
        $content ="";
		    
		 $content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">EDIT LOAN LOSS PROVISSIONS</h3>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Range:</label>
                                         <span>'.$range.'Days</span>                                            
                                        </div></div></div>
                       <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
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
{       $content ="";
	$resp = new xajaxResponse();
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

$content ="";
	$content .="<form method='post' class='panel form-horizontal form-bordered'>";
$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h4 class="semibold text-primary mt0 mb5">CREATE A LOAN PRODUCT</h4>
                                               		 <div><font color="#FF0000">*</font> - Required.</div>
													 <p class="text-default nm"></p>
                                           	 </div>
                                        </div><div id="responsediv" class="alert alert-dismissable alert-danger"></div>';
                                        
	 
	$content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Branch <font color="#FF0000">*</font>:</label>
                                            <div class="col-sm-6"><span>'.branch().'</span></div>                                            
                                            </div>';                                           
                                            $content .='<div class="form-group">
                                            <label class="col-sm-3 control-label">Product Name <font color="#FF0000">*</font>:</label>
                                            <div class="col-sm-6">
                                            <select name="account_id" id="account_id" class="form-control">';
                                            $level1_res = mysql_query("select * from accounts where account_no like '111%' and account_no not like '111' and account_no <= 1119 and branch_no like '".$_SESSION['branch_no']."' and id not in (select p.account_id from loan_product p join accounts a on p.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."')");
	
	while($level1 = mysql_fetch_array($level1_res)){
		$level2_res = mysql_query("select * from accounts where account_no like '".$level1['account_no']."%' and account_no not like '".$level1['id']."' and account_no <= 1119 and branch_no like '".$_SESSION['branch_no']."' and id not in (select p.id from loan_product p join accounts a on p.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."')");
		
		if( @ mysql_numrows($level2_res) == 0){
			$resp->call($level1['account_no']);
			$content .= "<option value='".$level1['id']."'>".$level1['account_no']. "-". $level1['name']; 
		}else{
			while($level2 = mysql_fetch_array($level2_res)){
				$level3_res = mysql_query("select * from accounts where account_no like '".$level2['account_no']."%' and account_no not like '".$level2['account_no']."' and account_no <= 111999 and branch_no like '".$_SESSION['branch_no']."' and id not in (select p.account_id from loan_product p join accounts a on p.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."')");
				if(@ mysql_numrows($level3_res) == 0)
					$content .= "<option value='".$level2['id']."'>".$level2['account_no']. "-". $level2['name']; 
				else{
					while($level3 = mysql_fetch_array($level3_res)){
						$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no not like '".$level3['account_no']."' and account_no <= 11199999 and branch_no like '".$_SESSION['branch_no']."' and id not in (select p.account_id from loan_product p join accounts a on p.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."')");
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
                                            <label class="col-sm-3 control-label">Interest Rate (% per Annum) <font color="#FF0000">*</font>:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric(6,3)" name="int_rate" placeholder="Rates per year" id="int_rate" class="form-control">
                                            </div></div>';                                            
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Penalty Rate (% per Annum) <font color="#FF0000">*</font>:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric(6,3)" name="penalty_rate" placeholder="Rates per year" id="penalty_rate" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Interest Method <font color="#FF0000">*</font>:</label>
                                            <div class="col-sm-6">
                                           <select id="int_method" class="form-control"><option value=""><option value="Declining Balance">Declining Balance<option value="Flat">Flat<option value="Armotised">Amortised</select>
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Grace Period (Months) <font color="#FF0000">*</font>:</label>
                                            <div class="col-sm-6">
                                           <input type="int" name="grace_period" placeholder="Per year" id="grace_period" value=0 class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Arrears Maturity (Months before a payment becomes arrears) <font color="#FF0000">*</font>:</label>
                                            <div class="col-sm-6">
                                           <input type="int" name="arrears_period" id="arrears_period" value=0 class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Loan Period <font color="#FF0000">*</font>:</label>
                                            <div class="col-sm-6">
                                           <input type="int" name="loan_period" id="loan_period" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Maximum Loan Amount <font color="#FF0000">*</font>:</label>
                                            <div class="col-sm-6">
                                           <input type="big" name="max_loan_amt" id="max_loan_amt" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Write-off Period (Months) <font color="#FF0000">*</font>:</label>
                                            <div class="col-sm-6">
                                           <input type="int" name="writeoff_period" id="writeoff_period" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Based On <font color="#FF0000">*</font>:</label>
                                            <div class="col-sm-6">
                                           <select id="based_on" class="form-control"><option value=""><option value="savings">Savings<option value="shares">Shares</select>
                                            </div></div>';
                                             
                                            $content .= '<div class="panel-footer"><button type="reset" class="btn btn-default" onclick=\"xajax_add_loanproduct()\">Reset</button>
                                            <button type="button" class="btn btn-primary" onclick=\'xajax_insert_loanproduct(getElementById("account_id").value, getElementById("int_rate").value, getElementById("penalty_rate").value, getElementById("int_method").value, getElementById("grace_period").value, getElementById("arrears_period").value, getElementById("loan_period").value, getElementById("max_loan_amt").value, getElementById("writeoff_period").value, getElementById("based_on").value, getElementById("branch_id").value); return false; \'>Save</button>';
                                            $content .= '</div>';
           
                             
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//edit loan product
function edit_loanproduct($loanproduct_id){
	$resp = new xajaxResponse();
	/*if($_SESSION['position'] <>'Manager'){
		$resp->alert("Access Denied! \nOnly the Manager can edit an item");
		return $resp;
	}*/
	$resp->assign("status", "innerHTML", "");
	$former_res = mysql_query("select p.id as loanproduct_id, p.int_rate as int_rate, p.int_method as int_method, p.grace_period as grace_period, p.arrears_period as arrears_period, p.loan_period as loan_period, p.writeoff_period as writeoff_period, p.max_loan_amt as max_loan_amt, a.id as account_id, a.name as name, a.account_no as account_no from loan_product p join accounts a on p.account_id=a.id where p.id='".$loanproduct_id."'");
	$former = mysql_fetch_array($former_res);
	
	$content ="<form method='post' class='panel form-horizontal form-bordered'>";
$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h4 class="semibold text-primary mt0 mb5">EDIT LOAN PRODUCT</h4>
                                               		 <p class="text-default nm"></p>
                                           	 </div></div>';
                                             
                                              $content .= "<div class='form-group'>
	                                   	                                   
                                            <label class='col-sm-3 control-label'>Product Name:</label>
                                          <div class='col-sm-6'><select name='account_id' id='account_id' class='form-control'><option value='".$former['account_id']."'>".$former['account_no']." - ".$former['name'];
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
	$grace_period = $former['grace_period'] /30;
	$loan_period = $former['loan_period']/30;
	$arrears_period = $former['arrears_period']/30;
	$writeoff_period = $former['writeoff_period']/30;
	$content .= "</select></div></div>";
                                     
        					$content .= "<div class='form-group'>
	                                   	                                   
                                            <label class='col-sm-3 control-label'>Interest Rate (% per Annum):</label>
                                          <div class='col-sm-6'><input type=numeric(6,3) id='int_rate' value='".$former['int_rate']."' class='form-control'>
                                            </div></div>"; 
                                            
                              			 $content .= "<div class='form-group'>
	                                   	                                   
                                            <label class='col-sm-3 control-label'>Penalty Rate (% per Annum):</label>
                                          <div class='col-sm-6'><input type=numeric(6,3) id='penalty_rate' value='".$former['penalty_rate']."' class='form-control'>
                                            </div></div>";
                                             	 
                                             	  $content .= "<div class='form-group'>
	                                   	                                   
                                            <label class='col-sm-3 control-label'>Interest Method:</label>
                                         <div class='col-sm-6'><select id='int_method' class='form-control'><option value='".$former['int_method']."'>".$former['int_method']."<option value='Declining Balance'>Declining Balance<option value='Flat'>Flat<option value='Armotised'>Armotised</select>
                                            </div></div>";  
                                            
                                             	    $content .= "<div class='form-group'>
	                                   	                                   
                                            <label class='col-sm-3 control-label'>Grace Period (Months):</label>
                                          <div class='col-sm-6'><input type=int id='grace_period' value='".$grace_period."' class='form-control'>
                                            </div></div>";  
                                            
                                            	    $content .= "<div class='form-group'>
	                                   	                                  
                                            <label class='col-sm-3 control-label'>Arrears Maturity (Months before a payment becomes arrears):</label>
                                          <div class='col-sm-6'><input type=int id='arrears_period' value='".$arrears_period."' class='form-control'>
                                            </div></div>";
                                            
                                            	    $content .= "<div class='form-group'>
	                                   
	                                  
                                            <label class='col-sm-3 control-label'>Loan Period:</label>
                                        <div class='col-sm-6'><input type=int id='loan_period' value='".$loan_period."' class='form-control'>
                                            </div></div>";
                                            
                                            	    $content .= "<div class='form-group'>
	                                   
	                                   
                                            <label class='col-sm-3 control-label'>Maximum Loan Amount:</label>
                                       <div class='col-sm-6'><input type=bigint id='max_loan_amt' value='".$former['max_loan_amt']."' class='form-control'>
                                            </div></div>"; 
                                            
                                            	    $content .= "<div class='form-group'>
	                                   
	                                  
                                            <label class='col-sm-3 control-label'>Write-off Period (Months):</label>
                                      <div class='col-sm-6'><input type=int id='writeoff_period' value='".$writeoff_period."' class='form-control'>
                                            </div></div>";                                             	                                               	                                  
                                           	     $content .= "<div class='panel-footer'><button class='btn btn-default' onclick=\"xajax_delete_loanproduct('".$loanproduct_id."');\">Delete</button>&nbsp;<button class='btn btn-primary' onclick=\"xajax_update_loanproduct('".$loanproduct_id."', getElementById('account_id').value, getElementById('int_rate').value, getElementById('penalty_rate').value, getElementById('int_method').value, getElementById('grace_period').value, getElementById('arrears_period').value, getElementById('loan_period').value, getElementById('max_loan_amt').value, getElementById('writeoff_period').value);return false;\">Update</button>";
 
     $content .= '</div></div></form>';  
	
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//update loanproduct
function update_loanproduct($loanproduct_id, $account_id, $int_rate, $penalty_rate, $int_method, $grace_period, $arrears_period, $loan_period, $max_loan_amt, $writeoff_period){
	$resp = new xajaxResponse();
	//$resp->assign("status", "innerHTML", "");
	if($loanproduct_id=='' || $account_id=='' || $int_rate=='' || $int_method=='' || $grace_period=='' || $arrears_period=='' || $loan_period=='' || $max_loan_amt=='' || $writeoff_period=='' || $penalty_rate==''){
		$resp->alert("You may not leave any field blank!");
		//$resp->call('xajax_edit_loanproduct', $loanproduct_id);
		return $resp;
	}else{
		$resp->confirmCommands(1, "Do you really want to update?");
		//$resp->call('xajax_update2_loanproduct', $loanproduct_id, $account_id, $int_rate, $penalty_rate, $int_method, $grace_period, $arrears_period, $loan_period,  $max_loan_amt, $writeoff_period);
		$grace_period=30 * $grace_period;
	$arrears_period = 30 * $arrears_period;
	$loan_period = 30 * $loan_period;
	$writeoff_period = 30 * $writeoff_period;
	mysql_query("update loan_product set account_id='".$account_id."', int_rate='".$int_rate."', int_method='".$int_method."', grace_period='".$grace_period."', arrears_period='".$arrears_period."', loan_period='".$loan_period."', max_loan_amt='".$max_loan_amt."', writeoff_period='".$writeoff_period."', penalty_rate='".$penalty_rate."' where id='".$loanproduct_id."'");
	////////////
	$accno = mysql_fetch_assoc(mysql_query("select name,account_no from accounts where id=".$account_id));
	$action = "update loan_product set account_id='".$account_id."', int_rate='".$int_rate."', int_method='".$int_method."', grace_period='".$grace_period."', arrears_period='".$arrears_period."', loan_period='".$loan_period."', max_loan_amt='".$max_loan_amt."', writeoff_period='".$writeoff_period."', penalty_rate='".$penalty_rate."' where id='".$loanproduct_id."'";
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
function update2_loanproduct($loanproduct_id, $account_id, $int_rate, $penalty_rate, $int_method, $grace_period, $arrears_period, $loan_period,  $max_loan_amt, $writeoff_period){
	$resp = new xajaxResponse();
	//$resp->alert("hI");
	//return $resp;
	$grace_period=30 * $grace_period;
	$arrears_period = 30 * $arrears_period;
	$loan_period = 30 * $loan_period;
	$writeoff_period = 30 * $writeoff_period;
	mysql_query("update loan_product set account_id='".$account_id."', int_rate='".$int_rate."', int_method='".$int_method."', grace_period='".$grace_period."', arrears_period='".$arrears_period."', loan_period='".$loan_period."', max_loan_amt='".$max_loan_amt."', writeoff_period='".$writeoff_period."', penalty_rate='".$penalty_rate."' where id='".$loanproduct_id."'");
	////////////
	$accno = mysql_fetch_assoc(mysql_query("select name,account_no from accounts where id=".$account_id));
	$action = "update loan_product set account_id='".$account_id."', int_rate='".$int_rate."', int_method='".$int_method."', grace_period='".$grace_period."', arrears_period='".$arrears_period."', loan_period='".$loan_period."', max_loan_amt='".$max_loan_amt."', writeoff_period='".$writeoff_period."', penalty_rate='".$penalty_rate."' where id='".$loanproduct_id."'";
	$msg = "Edited loan Product set ac/no:".$accno['account_no']." - ".$accno['name'].", Max_loan amount:".number_format($max_loan_amt).", Write-off period:".$writeoff_period;
	log_action($_SESSION['user_id'],$action,$msg);
	////////////
	$resp->assign("status", "innerHTML", "<font color=red>Loan Product updated!</font>");
	return $resp;
}


//DELETE LOAN PRODUCT
function delete_loanproduct($loanproduct_id){
	$resp = new xajaxResponse();
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
function insert_loanproduct($account_id, $int_rate, $penalty_rate, $int_method, $grace_period, $arrears_period, $loan_period, $max_loan_amt, $writeoff_period, $based_on, $branch_id){
	$resp = new xajaxResponse();
	$sth = mysql_query("select * from loan_product where account_id='".$account_id."'");
	if($account_id=='' || $int_rate=='' || $int_method=='' || $grace_period=='' || $arrears_period=='' || $loan_period=='' || $max_loan_amt=='' || $writeoff_period=='' || $based_on=='' || $penalty_rate=='')
		$resp->alert("You may not leave any field blank");
	elseif(@ mysql_numrows($sth) >0){
		$resp->alert("Product not registered, it already exists!");
	}else{
		$grace_period=30 * $grace_period;
		$arrears_period = 30 * $arrears_period;
		$loan_period = 30 * $loan_period;
		$writeoff_period = 30 * $writeoff_period;

		mysql_query("insert into loan_product set account_id='".$account_id."', int_rate='".$int_rate."', penalty_rate='".$penalty_rate."', int_method='".$int_method."', grace_period='".$grace_period."', arrears_period='".$arrears_period."', loan_period='".$loan_period."', max_loan_amt='".$max_loan_amt."', writeoff_period='".$writeoff_period."', based_on='".$based_on."', branch_id=".$branch_id);

		////////////////////////
		$accno = mysql_fetch_assoc(mysql_query("select account_no,name from accounts where id=".$account_id));
	$action = "insert into loan_product set account_id='".$account_id."', int_rate='".$int_rate."', penalty_rate='".$penalty_rate."', int_method='".$int_method."', grace_period='".$grace_period."', arrears_period='".$arrears_period."', loan_period='".$loan_period."', max_loan_amt='".$max_loan_amt."', writeoff_period='".$writeoff_period."', based_on='".$based_on."'";
	$msg = "Created a loan product based on: ".$based_on." with Max loan Amount set to:".$max_loan_amt." on ac/no:".$accno['account_no']." - ".$accno['name'];
	log_action($_SESSION['user_id'],$action,$msg);
	///////////////////////////
		
		$resp->assign("status", "innerHTML", "<FONT COLOR=RED>Loan product created!</font>");
	}
	return $resp;
}

//LIST LOAN PRODUCTS
function list_loanproducts($msg){
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML",$msg);
	$content = ' <!--<div class="col-md-6 col-lg-12"> --> <div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <p><h4 class="semibold text-primary mt0 mb5">LIST OF LOAN PRODUCTS</h4></p>
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
	//$content = "<center><font color=#00008b size=3pt><b></b></font></center><table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>";
	$sth = mysql_query("select p.id as loanproduct_id, p.int_rate as int_rate, p.penalty_rate as penalty_rate, p.int_method as int_method, p.based_on as based_on,  p.grace_period as grace_period, p.arrears_period as arrears_period, p.loan_period as loan_period, p.writeoff_period as writeoff_period, p.max_loan_amt as max_loan_amt, a.id as account_id, a.name as name, a.account_no as account_no from loan_product p join accounts a on p.account_id=a.id order by a.account_no");
	if(@ mysql_numrows($sth) == 0){
		$cont = "<font color=red>No Loan Products Created Yet!</font>";
		//$resp->assign("status", "innerHTML", $cont);
	}
	else{
		$content .= "<thead><th>#</th><th>PRODUCT</th><th>INT. RATE(% Per Annum)</th><th>PENALTY RATE(% Per Annum)</th><th>METHOD</th><th>GRACE PERIOD(Months)</th><th>ARREARS MATURITY(Months)</th><th>LOAN PERIOD</th><th>MAX. AMOUNT</th><th>WRITE-OFF PERIOD(Months)</th><th>BASED ON</th></thead>";
		$content .="<tbody>";
		$i=0;
		while($row = mysql_fetch_array($sth)){
			$content .= "<tr><td>".($i+1)."</td>";
			$grace_period = $row['grace_period'] /30;
			$loan_period = $row['loan_period']/30;
			$arrears_period = $row['arrears_period']/30;
			$writeoff_period = $row['writeoff_period']/30;
			//$color= ($i%2 == 0) ? "lightgrey" : "white"; 
			$content .= "<td><a href='javascript:;' onclick=\"xajax_edit_loanproduct('".$row['loanproduct_id']."'); \">".$row['account_no'] ." - ".$row['name']."</td><td>".$row['int_rate']."</td><td>".$row['penalty_rate']."</td><td>".$row['int_method']."</td><td>".$grace_period."</td><td>".$arrears_period."</td><td>".$loan_period."</td><td>".number_format($row['max_loan_amt'], 2)."</td><td>".$writeoff_period."</td><td>".ucfirst($row['based_on'])."</td></tr>";
			$i++;
		}
		$content .= "</tbody></table></div>";
	$resp->call("createTableJs");
	}
	
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;

}

//APPLY FOR  LOAN
function add_applic(){
	$resp = new xajaxResponse();
	//$resp->assign("status", "innerHTML", "");
			
		$content = '<form class="panel form-horizontal form-bordered">
		<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h4 class="panel-title">APPLY FOR A LOAN</h4>
                                               		<div><font color="#FF0000">*</font> - Required.</div>
                                           	 </div>
                                        </div><div id="responsediv" class="alert alert-dismissable alert-danger"></div>
 		<div class="form-group">
                                   
                                        <div class="col-sm-6">
                                            <label class="control-label">Type of Loan <font color="#FF0000">*</font>:</label>
                                            <select id="loan_type" class="form-control" onchange=\'xajax_loan_type(this.value);\'><option value="">Select</option><option value="Individual">Individual<option value="Group">Group</select>          
                                        </div>                                        
                                    </div>
                                
                                
             <div class="form-group">
                                     <div class="col-sm-6">
                                        <div id="enter_div"> </div>
                                        
                                            <div id="select_div" ></div>
                                        </div>
                                    </div>
                               
                                       
        <div id="other_div"> </div>
                                </div></form></div></div>';
        
        
		$resp->assign("display_div", "innerHTML", $content);
		return $resp;
}

//DROPDOWN FOR EITHER MEM_NOs OR GROUPS
function loan_type($loan_type){
	$resp = new xajaxResponse();
	$resp->assign("other_div", "innerHTML", "");
	//$resp->alert("i was called");
	if($loan_type=='Individual'){
		$resp->assign("enter_div", "innerHTML", "<div class='form-group'><div>
                                    <div class='col-sm-12'>
                                            <label class='control-label'>Enter Member No:</label>
                                            <div class='input-group'>
                                               
                                                <input type=int id='mem_no' class='form-control'>
                                                <span class='input-group-btn'>
                                                    <div class='btn btn-primary btn-file'>
                                                        <span class='icon iconmoon-file-3' onclick=\"xajax_other_applic(getElementById('mem_no').value, '".$loan_type."', 'enter');\">Next</span>
                                                    </div>
                                                </span>
                                           </div>");
		$content = "
                                            <label class='control-label'>OR  Select Member No:</label><div class='input-group'><select id='mem_id1' class='form-control'><option value=''>Select Member</option>";
		$sth=mysql_query("select * from member order by first_name, last_name, mem_no");
		
		while($row = @mysql_fetch_array($sth)){
			$content .= "<option value='".$row['id']."'>".$row['first_name'] ." ".$row['last_name'] ." - ".$row['mem_no'];
		}
		$content .= "</select> <span class='input-group-btn'>
                                                    <div class='btn btn-primary btn-file'>
                                                        <span class='icon iconmoon-file-3' onclick=\"xajax_other_applic(getElementById('mem_id1').value, '".$loan_type."', 'select');\">Next</span>
                                                    </div>
                                                </span>
                                         </div></div></div>";
		$resp->assign("select_div", "innerHTML", $content);
	}elseif($loan_type=='Group'){
		$resp->assign("enter_div", "innerHTML", "<div class='form-group'><div>
                                  <div class='col-sm-12'>
                                            <label class='control-label'>Enter Group Name:</label><div class='input-group'><input type=int id='group_name' class='form-control'><span class='input-group-btn'>
                                                    <div class='btn btn-primary btn-file'>
                                                        <span class='icon iconmoon-file-3' onclick=\"xajax_other_applic(getElementById('group_name').value, '".$loan_type."', 'enter');\">Next</span>
                                                    </div>
                                                </span>
                                           </div>");
		$content = "
                                            <label class='control-label'>OR  Select Group Name:</label><div class='input-group'><select id='group_id1' class='form-control'><option value=''>Select Group</option>";
		$sth=mysql_query("select * from loan_group order by name");
		while($row = @mysql_fetch_array($sth)){
			$content .= "<option value='".$row['id']."'>".$row['name'];
		}
		$content .= "</select><span class='input-group-btn'>
                                                    <div class='btn btn-primary btn-file'>
                                                        <span class='icon iconmoon-file-3' onclick=\"xajax_other_applic(getElementById('group_id1').value, '".$loan_type."', 'select');\">Next</span>
                                                    </div>
                                                </span>
                                            </div></div></div>";
		$resp->assign("select_div", "innerHTML", $content);
	}else{
		$resp->assign("enter_div", "innerHTML", "");
		$resp->assign("select_div", "innerHTML", "");
		$resp->assign("other_div", "innerHTML", "");
	}
	return $resp;
}


//FORM TO CAPTURE THE REST ABOUT THE APPLIC
function other_applic($stuff, $loan_type, $entry_mode){
	$resp = new xajaxResponse();
		
	if($stuff == ''){
		if($loan_type == 'Group'){
			$resp->alert("Please enter or select a group");
			return $resp;
		}elseif($loan_type=='Individual'){
			$resp->alert("Please enter or select a Member's No");
			return $resp;
		}
	}
	/*$content = "<table height=100 border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>";*/
	$content ="";
	//$content .="<form method='post' class='panel form-horizontal form-bordered'>";
                   	    
	if($loan_type=='Group'){
		if($entry_mode == 'select'){
			$sth = mysql_query("select m.id as mem_id, m.first_name as first_name, m.last_name as last_name, m.mem_no from member m join group_member gm on m.id=gm.mem_id join loan_group g on gm.group_id=g.id where g.id='".$stuff."'");	
			$group_res = mysql_query("select * from loan_group where id='".$stuff."'");
			$group = mysql_fetch_array($group_res);
			$group_id=$stuff;
			$group_name = $group['name'];
			$branch_id = $group['branch_id'];
		}elseif($entry_mode=='enter'){
			$group_res = mysql_query("select * from loan_group where name='".$stuff."'");
			if(@ mysql_numrows($group_res)==0){
				$resp->alert("No group exists by that name!");
				return $resp;
			}
			$group = mysql_fetch_array($group_res);
			$group_id = $group['id'];
			$group_name=$stuff;
			$branch_id = $group['branch_id'];
			$sth = mysql_query("select m.id as mem_id, m.first_name as first_name, m.last_name as last_name, m.mem_no from member m join group_member gm on m.id=gm.mem_id join loan_group g on gm.group_id=g.id where g.name='".$stuff."'");
		}
		
	$content .= '<div class="form-group header bgcolor-default">
                                       <div class="col-md-12">
                                       <table>                                        
                                            <tr>
                                                <td><span class="bold">Group Name:&nbsp;&nbsp;</span><span class="text-primary">'.$group_name.'</span></td>
		                           </tr></table>
		
                                            </div>
                                        </div>';
                                        
                $content .= '<div class="form-group"><div class="col-sm-6">
                                            <label class="control-label">Member:</label>';
                                            
                                          $content .= "<input type=hidden id='group_id' value='".$group_id."'><select id='mem_id' class='form-control' onchange=\"xajax_guarantor('".$group_id."', getElementById(\'mem_id\').value);\"><option value=''>";
      while($row = @mysql_fetch_array($sth)){
			$content .= "<option value='".$row['mem_id']."'>".$row['mem_no'] ." - ".$row['first_name']. " ".$row['last_name'];
		}
		$content .= '</select></div></div>';
                              

	}elseif($loan_type=='Individual'){
		if($entry_mode=='select'){
			$sth = mysql_query("select * from member where id = '".$stuff."'");
			$row = mysql_fetch_array($sth);
			$mem_id = $stuff;
			$mem_no = $row['mem_no'];
			$mem_name = $row['first_name'] . " " .$row['last_name'];
			$branch_id = $row['branch_id'];
		}elseif($entry_mode== 'enter'){
			$sth = mysql_query("select * from member where mem_no = '".$stuff."'");
			if(@ mysql_numrows($sth) == 0){
				$resp->alert("No member exists by that MemberNo");
				return $resp;
			}
			$row = mysql_fetch_array($sth);
			$mem_id = $row['id'];
			$mem_name = $row['first_name'] . " " .$row['last_name'];
			$mem_no = $stuff;
		}
		$branch = mysql_query("select branch_name from branch where branch_no='".$row['branch_id']."'") or trigger_error(mysql_error());
		$branch_id = $row['branch_id'];
		if(mysql_num_rows($branch)>0){
			$branch_name = mysql_fetch_assoc($branch);
			$mem_branch = $branch_name['branch_name'];
		}
		else
			$mem_branch="No associated branch";
			
			
		$content .= '<div class="form-group header bgcolor-default">
                                       <div class="col-md-12">
                                       
                                       <table><tr><td><b>Branch:</b></td><td><font color="#00BFFF">'.$mem_branch.'</font></td></tr>
			<tr><td><b>Member No:</b></td><td><font color="#00BFFF">'.$mem_name.'</font></td></tr>
				<t><td><b>Member Name:&nbsp;</b></td><input type=hidden name="mem_id" value="'.$row['id'].'" id="mem_id"><td><font color="#00BFFF">'.$mem_no.'</font></td></tr></table>
                                                                                                                                                                                                                                                                                  
                                            </div>                                           
                                           </div>'; 
                                           
                                           $content.='<div class="form-group">
                                    
                                        <div class="col-sm-12">
                                           <table><tr><td align=right><img src="photos/'.$row['photo_name'].'?dummy='.time().'" width=90 height=90><br>Photo</td><td width=90%>&nbsp;</td><td align=right><img src="signs/'.$row['sign_name'].'?dummy='.time().'" width=90 height=90><br>Signature</td></tr></table> <input type=hidden value="0" id="group_id"><input type="hidden" value="'.$mem_id.'" id="mem_id">
                                  
                                 </div></div>';	
			                                                                              
}                                                                                                                                            						
	             $content .= '<div class="form-group">
	                                   
	                                   <div class="col-sm-6">
                                            <label class="control-label">Marital Status <font color="#FF0000">*</font>:</label>
                                           <select id="marital_status" class="form-control"><option value=""><option value="Single">Single<option value="Married">Married</select>
                                            </div>
                                            
                                           <div class="col-sm-6">
                                            <label class="control-label">Phone No <font color="#FF0000">*</font>:</label>
                                            <input type="text" id="applic_telno" value="'.$row['telno'].'" class="form-control">
                                            </div>
                                            </div>';
                                            
                     $content .= '<div class="form-group">
                                           
	                                   <div class="col-sm-6">
                                            <label class="control-label">Postal Address <font color="#FF0000">*</font>:</label>
                                           <input type="text" id="applic_address" value="'.$row['address'].'" class="form-control">
                                            </div>
                                            
                                            <div class="col-sm-6">                      
                                            <label class="control-label">Residence <font color="#FF0000">*</font>:</label>
                                           <input type="text" id="applic_residence" class="form-control">
                                            </div></div>';
                                            
                     $content .= '<div class="form-group">
                                            
	                                   <div class="col-sm-6">
                                            <label class="control-label">Income Source 1 <font color="#FF0000">*</font>:</label>
                                           <input type="text" id="applic_income1" class="form-control">
                                            </div>
                                            
                                           <div class="col-sm-6"> 
                                            <label class="control-label">Income Source 2 <font color="#FF0000">*</font>:</label>
                                           <input type="text" id="applic_income2" class="form-control">
                                           </div></div>';
                                            
                    $prod_res = mysql_query("select a.name as name, a.account_no as account_no, p.id as id from loan_product p join accounts a on p.account_id=a.id order by a.account_no, a.name");
	
                     $content .= '<div class="form-group">
                                           
	                                   <div class="col-sm-6">
                                            <label class="control-label">Loan Product <font color="#FF0000">*</font>:</label>
                                           
                                           <select id="applic_product_id" class="form-control"><option value="">';
	while($prod = @ mysql_fetch_array($prod_res)){
		$content .= "<option value='".$prod['id']."'>".$prod['account_no']. " - ".$prod['name'];
	}
	$content .='</select></div>';
                                            	  
	
	$content .= '<div class="col-sm-6">
                                            <label class="control-label">Based On <font color="#FF0000">*</font>:</label>
                                            
                                           <select id="based_on" name="based_on" class="form-control"><option value="">&nbsp;<option value="savings">Savings<option value="shares">Shares</option></select>
                                            </div></div>';
                                            
        $content .= '<div class="form-group">
	                                   <div class="col-sm-6">
                                            <label class="control-label">Amount <font color="#FF0000">*</font>:</label>
                                            
                                           <input type="int" name="applic_amount" id="applic_amount" class="form-control">
                                           </div>
                                            
                                           <div class="col-sm-6">
                                            <label class="control-label">Reason <font color="#FF0000">*</font>:</label>
                                           
                                           <input type="text" name="reason" id="reason" class="form-control">
                                            </div></div>'; 
                                                                                                                                                          	
	if($loan_type=='Individual'){
		
		
		 $content .= '<div class="form-group">
	                                   <div class="col-sm-6">
                                            <label class="control-label">Guarantor 1 <font color="#FF0000">*</font>:</label>
                                           
                                           <select id="guarantor1" class="form-control"><option value="">';
                                 if($entry_mode == 'select'){
			$sth1 = mysql_query("select * from member where id<>'".$stuff."' order by first_name, last_name, mem_no");
			$sth2 = mysql_query("select * from member where id<>'".$stuff."' order by first_name, last_name, mem_no");
		}elseif($entry_mode =='enter'){
			$sth1 = mysql_query("select * from member where mem_no<>'".$stuff."' order by first_name, last_name, mem_no");
			$sth2 = mysql_query("select * from member where mem_no<>'".$stuff."' order by first_name, last_name, mem_no");
		}
		while($row = @mysql_fetch_array($sth1)){
			$content .= "<option value='".$row['id']."'>".$row['first_name']. " " .$row['last_name'] ."- ".$row['mem_no'];
		}
		$content .= '</select>
		
		
		</div>
		
		
		
		
                                   
                                   
                                           <div class="col-sm-6">
                                            <label class="control-label">Guarantor 2 <font color="#FF0000">*</font>:</label>
                                            
                                           <select id="guarantor2" class="form-control" onchange=\'xajax_verify_guarantor(getElementById("guarantor1").value, getElementById("guarantor2").value, getElementById("applic_amount").value, getElementById("applic_product_id").value, "0", getElementById("applic_amount").value,getElementById("based_on").value);\'><option value="">';
										   
										   
										   
										   
                                           
                          while($row = @mysql_fetch_array($sth2)){
			$content .= "<option value='".$row['id']."'>".$row['first_name']. " " .$row['last_name'] ."- ".$row['mem_no'];
		}
		
		 $content .='</select>
		 
		 
		 </div></div>'; 
		 
		 
		   $content .= '<div class="form-group">
                                            
	                                   <div class="col-sm-6">
                                            <label class="control-label">Guarantor3 <font color="#FF0000">*</font>:</label>
                                           <input type="text" id="applic_income1" class="form-control">
                                            </div>
                                            
                                           <div class="col-sm-6"> 
                                            <label class="control-label">Guarantor4 <font color="#FF0000">*</font>:</label>
                                           <input type="text" id="applic_income2" class="form-control">
                                           </div></div>';
		 
		 
		
	}elseif($loan_type=='Group'){
	
	              $content .= "<div id='guarantor_div'><input type='hidden' value='0' id='guarantor1'><input type='hidden' value='0' id='guarantor2'></div>";
	              }
                                                    
                                   
         $content .= '<div class="form-group">
	                                   <div class="col-sm-6">
                                            <label class=" control-label">Loan Officer <font color="#FF0000">*</font>:</label>
                                            
                                           <select id="applic_officer_id" class="form-control"><option value="">';
                                           
         $sth = libinc::getCreditOfficers();
        if($sth->num_rows() > 0){
		foreach($sth->result() as $row)
			$content .= "<option value='".$row->employeeId."'>".$row->firstName." ".$row->lastName."</option>";
	}
	$content .= '</select></div>                                   
                                                                       
	                                   <div class="col-sm-6">
                                            <label class="control-label">Date <font color="#FF0000">*</font>:</label>
                                            
                                           <input type="text" class="form-control" id="date" name="date" value="'.date('Y-m-d').'" />
                                            </div></div>';
	$resp->call("createDate","date");
	
	$content .= '<div class="panel-footer"><button type="reset" class="btn btn-default" onclick=jaxax_other_applic("'.$stuff.'", "'.$loan_type.'", "'.$entry_mode.'");>Reset</button>
                                            <button type="button" class="btn btn-primary" onclick=\'xajax_verify_guarantor(getElementById("guarantor1").value, getElementById("guarantor2").value, getElementById("applic_amount").value, getElementById("applic_product_id").value, "0", getElementById("applic_amount").value, getElementById("based_on").value); xajax_insert_applic(getElementById("group_id").value, getElementById("mem_id").value,  getElementById("marital_status").value, getElementById("applic_residence").value, getElementById("applic_telno").value, getElementById("applic_address").value, getElementById("applic_income1").value, getElementById("applic_income2").value, getElementById("applic_amount").value, getElementById("based_on").value, getElementById("guarantor1").value, getElementById("guarantor2").value, getElementById("reason").value, getElementById("applic_product_id").value, getElementById("applic_officer_id").value, getElementById("date").value,"'.$branch_id.'");\'>Save</button>';
     $content .= '</div></div></form></div>';
    	
	$resp->assign("other_div", "innerHTML", $content);
	return $resp;
}

//LIST GROUP GUARANTORS
function guarantor($group_id, $mem_id){
	$resp = new xajaxResponse();	
	$sth = mysql_query("select m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, m.id as mem_id from member m join group_member gm on m.id=gm.mem_id where gm.group_id='".$group_id."' and gm.mem_id <>'".$mem_id."' order by m.first_name, m.last_name, m.mem_no");
	$guarantor1 ='';
	$guarantor2 ='';
	$i=0;
	//$resp->assign("status", "innerHTML", "select m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, m.id as mem_id from member m join group_member gm on m.id=gm.mem_id where gm.group_id='".$group_id."' and gm.mem_id <>'".$mem_id."' order by m.first_name, m.last_name, m.mem_no");
	//return $resp;
	while($row = mysql_fetch_array($sth)){
		if($i == 0)
			$guarantor1 = $row['mem_id'];
		elseif($i == 1)
			$guarantor2 = $row['mem_id'];
		elseif($i % 2 ==0)
			$guarantor1 .= ", ".$row['mem_id'];
		else
			$guarantor2 .= ", ".$row['mem_id'];
		$i++;
	}
	$content = "<input type='hidden' value='".$guarantor1."' id='guarantor1'><input type='hidden' value='".$guarantor2."' id='guarantor2'>";
	$resp->assign("guarantor_div", "innerHTML", $content);
	return $resp;
}

//INSERT APPLIC INTO DB
function insert_applic($group_id, $mem_id, $marital_status, $residence, $telno, $address, $income1, $income2, $amount, $based_on, $guarantor1, $guarantor2, $reason, $product_id, $officer_id, $date,$branch_id){
	$resp = new xajaxResponse();
	if($_SESSION['guaranteed'] == 0){
		$resp->alert("Please enter guarantor");
		return $resp;
	}
	if($officer_id==''){
		$resp->alert("Please enter Loan officer");
		return $resp;
	}

	if($mem_id=='' || $marital_status=='' || $residence=='' || $telno=='' || $address=='' || $income1=='' || $income2=='' || $amount=='' || $based_on == '' || $reason=='')
		$resp->alert("You may not leave any field blank!");
	else{

		# GET BRANCH SETTINGS
		$guarantor1 = ($guarantor1 =='') ? 0 : $guarantor1;
		$guarantor2 = ($guarantor2 =='') ? 0 : $guarantor2;
		$limit_res = mysql_query("select * from branch where branch_no='".$branch_id."'");
		if(mysql_num_rows($limit_res)>0)
		$limit = mysql_fetch_array($limit_res);
		else
		$limit = mysql_fetch_array(mysql_query("select * from branch limit 1"));
		$prod_res = mysql_query("select * from loan_product where id='".$product_id."'");
		$prod = mysql_fetch_array($prod_res);
		//$based_on = $prod['based_on'];
		if($based_on == 'savings'){
			//BORROWER'S CREDIBILITY
			$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.type='free' and m.mem_id='".$mem_id."'");
			$dep = mysql_fetch_array($dep_res);
			$dep_amt = ($dep['amount'] == NULL) ? 0 : $dep['amount'];

			$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.type='free' and m.mem_id='".$mem_id."'");
			$with = mysql_fetch_array($with_res);
			$with_amt = ($with['amount'] == NULL) ? 0 : $with['amount'];

			$pledged_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where a.name='Compulsory Savings' and mem.mem_id='".$mem_id."'");
			$pledged = @mysql_fetch_array($pledged_res);
			$pledged_amt = ($pledged['amount'] == NULL) ? 0 : $pledged['amount'];
			
			//INTEREST AWARDED
			$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id where m.mem_id=".$mem_id."");
			$int =  mysql_fetch_array($int_res);
			$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;

			//MONTHLY CHARGE
			$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts m on c.memaccount_id=m.id where m.mem_id='".$mem_id."'");
			$charge = mysql_fetch_array($charge_res);
			$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;

			//LOAN REPAYMENTS
			$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id where m.mem_id='".$row['mem_id']."'");
			$pay = mysql_fetch_array($pay_res);
			$pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;

			//INCOME DEDUCTIONS
			$inc_res = mysql_query("select sum(i.amount) as amount from other_income i join mem_accounts m on i.mode=m.id where m.mem_id='".$row['mem_id']."'");
			$inc = mysql_fetch_array($inc_res);
			$inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;

			//SHARES DEDUCTIONS
			$sha_res = mysql_query("select sum(s.value) as amount from shares s join mem_accounts m on s.mode = m.id where m.mem_id='".$row['mem_id']."'");
			$sha = mysql_fetch_array($sha_res);
			$sha_amount = ($sha['amount'] != NULL) ? $sha['amount'] : 0;

			$balance = $dep_amt + $int_amt - $with_amt - $pledged_amt - $charge_amt - $pay_amt - $inc_amt - $sha_amount;
			//$balance = $dep_amt + $int_amt - $with_amt - $pledged_amt - $charge_amt;
			$possible = ($balance * $limit['loan_save_percent']) / 100;
			if($possible < $amount && $limit['loan_save_percent'] >0){
				$resp->alert("Application rejected! \nThe member can only take ".$possible." \n based on his Savings.");
				return $resp;
			}
			//GUARANTOR 1'S CREDIBILITY
			$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.type='free' and m.mem_id='".$guarantor1."'");
			$dep = mysql_fetch_array($dep_res);
			$dep_amt = ($dep['amount'] == NULL) ? 0 : $dep['amount'];

			$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.type='free' and m.mem_id='".$guarantor1."'");
			$with = mysql_fetch_array($with_res);
			$with_amt = ($with['amount'] == NULL) ? 0 : $with['amount'];

			$pledged_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where a.name='Compulsory Savings' and mem.mem_id='".$guarantor1."'");
			$pledged = @mysql_fetch_array($pledged_res);
			$pledged_amt = ($pledged['amount'] == NULL) ? 0 : $pledged['amount'];
			
			//INTEREST AWARDED
			$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id where m.mem_id='".$guarantor1."'");
			$int =  mysql_fetch_array($int_res);
			$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;

			//MONTHLY CHARGE
			$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts m on c.memaccount_id=m.id where m.mem_id='".$guarantor1."'");
			$charge = mysql_fetch_array($charge_res);
			$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
			
			//LOAN REPAYMENTS
			$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id where m.mem_id='".$guarantor1."'");
			$pay = mysql_fetch_array($pay_res);
			$pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;

			//INCOME DEDUCTIONS
			$inc_res = mysql_query("select sum(i.amount) as amount from other_income i join mem_accounts m on i.mode=m.id where m.mem_id='".$guarantor1."'");
			$inc = mysql_fetch_array($inc_res);
			$inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;

			//SHARES DEDUCTIONS
			$sha_res = mysql_query("select sum(s.value) as amount from shares s join mem_accounts m on s.mode = m.id where m.mem_id='".$guarantor1."'");
			$sha = mysql_fetch_array($sha_res);
			$sha_amount = ($sha['amount'] != NULL) ? $sha['amount'] : 0;

			$balance = $dep_amt + $int_amt - $with_amt - $pledged_amt - $charge_amt - $pay_amt - $inc_amt - $sha_amount;

			//$balance = $dep_amt + $int_amt - $with_amt - $pledged_amt - $charge_amt;
			$possible = ($balance * $limit['guarantor_save_percent']) / 100;
			if($possible < $amount && $limit['guarantor_save_percent'] >0){
				$resp->alert("Application rejected! \nGuarantor 1 can only guarantee ".$possible." \n based on his Savings.");
				return $resp;
			}
			//GUARANTOR 2'S CREDIBILITY
			$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.type='free' and m.mem_id='".$guarantor2."'");
			$dep = mysql_fetch_array($dep_res);
			$dep_amt = ($dep['amount'] == NULL) ? 0 : $dep['amount'];

			$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.type='free' and m.mem_id='".$guarantor2."'");
			$with = mysql_fetch_array($with_res);
			$with_amt = ($with['amount'] == NULL) ? 0 : $with['amount'];

			$pledged_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where a.name='Compulsory Savings' and mem.mem_id='".$guarantor2."'");
			$pledged = @mysql_fetch_array($pledged_res);
			$pledged_amt = ($pledged['amount'] == NULL) ? 0 : $pledged['amount'];
			
			//INTEREST AWARDED
			$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id where m.mem_id='".$guarantor2."'");
			
			$int =  mysql_fetch_array($int_res);
			$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;

			//MONTHLY CHARGE
			$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts m on c.memaccount_id=m.id where m.mem_id='".$guarantor2."'");
			$charge = mysql_fetch_array($charge_res);
			$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;

			//LOAN REPAYMENTS
			$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id where m.mem_id='".$guarantor2."'");
			$pay = mysql_fetch_array($pay_res);
			$pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;

			//INCOME DEDUCTIONS
			$inc_res = mysql_query("select sum(i.amount) as amount from other_income i join mem_accounts m on i.mode=m.id where m.mem_id='".$guarantor2."'");
			$inc = mysql_fetch_array($inc_res);
			$inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;

			//SHARES DEDUCTIONS
			$sha_res = mysql_query("select sum(s.value) as amount from shares s join mem_accounts m on s.mode = m.id where m.mem_id='".$guarantor2."'");
			$sha = mysql_fetch_array($sha_res);
			$sha_amount = ($sha['amount'] != NULL) ? $sha['amount'] : 0;

			$balance = $dep_amt + $int_amt - $with_amt - $pledged_amt - $charge_amt - $pay_amt - $inc_amt - $sha_amount;

			//$balance = $dep_amt + $int_amt - $with_amt - $pledged_amt - $charge_amt;
			$possible = ($balance * $limit['guarantor_save_percent']) / 100;
			if($possible < $amount && $limit['guarantor_save_percent'] >0){
				$resp->alert("Application rejected! \nGuarantor 2 can only guarantee ".$possible." \nbased on his Savings.");
				return $resp;
			}
		}elseif($based_on == 'shares'){
			//BORROWER'S CREDIBILITY
			$shares_res = mysql_query("select sum(value) as value from shares where mem_id='".$mem_id."'");
			
			$shares = mysql_fetch_array($shares_res);
			
			$shares_amt = ($shares['value'] == NULL) ? 0 : $shares['value']; 
			//$resp->alert($shares_amt);
			$donated_res = mysql_query("select sum(value) as value from share_transfer where to_id='".$mem_id."'");
			$donated = @mysql_fetch_array($donated_res);
			$donated_amt = ($donated['value'] == NULL) ? 0 : $donated['value'];

			$trans_res = mysql_query("select sum(value) as value from share_transfer where from_id='".$mem_id."'");
			$trans = @mysql_fetch_array($trans_res);
			$trans_amt = ($trans['value'] == NULL) ? 0 : $trans['value'];
			$pledged_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where a.name='Compulsory Shares' and mem.mem_id='".$mem_id."'");
			$pledged = @mysql_fetch_array($pledged_res);
			$pledged_amt = ($pledged['amount'] == NULL) ? 0 : $pledged['amount'];

			$balance = $shares_amt + $donated_amt - $trans_amt - $pledged_amt;

			$possible = ($balance * $limit['loan_share_percent']) / 100;
			if($amount > $possible && $limit['loan_share_percent'] >0){
				$resp->alert("Application rejected! \n Customer has insufficient shares for this Loan \n Only ".$possible." is possible");
				return $resp;
			}
			//GUARANTOR 1'S CREDIBILITY
			$shares_res = mysql_query("select sum(value) as value from shares where mem_id='".$guarantor1."'");
			$shares = @mysql_fetch_array($shares_res);
			$shares_amt = ($shares['value'] == NULL) ? 0 : $shares['amount']; 
			
			$donated_res = mysql_query("select sum(value) as value from share_transfer where to_id='".$guarantor1."'");
			$donated = @mysql_fetch_array($donated_res);
			$donated_amt = ($donated['value'] == NULL) ? 0 : $donated['value'];

			$trans_res = mysql_query("select sum(value) as value from share_transfer where from_id='".$guarantor1."'");
			$trans = @mysql_fetch_array($trans_res);
			$trans_amt = ($trans['value'] == NULL) ? 0 : $trans['value'];
			$pledged_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where a.name='Compulsory Shares' and mem.mem_id='".$guarantor1."'");
			$pledged = @mysql_fetch_array($pledged_res);
			$pledged_amt = ($pledged['amount'] == NULL) ? 0 : $pledged['amount'];

			$balance = $shares_amt + $donated_amt - $trans_amt - $pledged_amt;

			$possible = ($balance * $limit['guarantor_share_percent']) / 100;
			if($amount > $possible && $limit['guarantor_share_percent'] >0){
				$resp->alert("Application rejected! \n Guarantor 1 has insufficient shares for this Loan \n Only ".$possible." is possible");
				return $resp;
			}
			//GUARANTOR 2'S CREDIBILITY
			$shares_res = mysql_query("select sum(value) as value from shares where mem_id='".$guarantor2."'");
			$shares = @mysql_fetch_array($shares_res);
			$shares_amt = ($shares['value'] == NULL) ? 0 : $shares['amount']; 
			
			$donated_res = mysql_query("select sum(value) as value from share_transfer where to_id='".$guarantor2."'");
			$donated = @mysql_fetch_array($donated_res);
			$donated_amt = ($donated['value'] == NULL) ? 0 : $donated['value'];

			$trans_res = mysql_query("select sum(value) as value from share_transfer where from_id='".$guarantor2."'");
			$trans = @mysql_fetch_array($trans_res);
			$trans_amt = ($trans['value'] == NULL) ? 0 : $trans['value'];
			$pledged_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where a.name='Compulsory Shares' and mem.mem_id='".$guarantor2."'");
			$pledged = @mysql_fetch_array($pledged_res);
			$pledged_amt = ($pledged['amount'] == NULL) ? 0 : $pledged['amount'];

			$balance = $shares_amt + $donated_amt - $trans_amt - $pledged_amt;

			$possible = ($balance * $limit['guarantor_share_percent']) / 100;
			if($amount > $possible && $limit['guarantor_share_percent'] >0){
				$resp->alert("Application rejected! \n Guarantor 2 has insufficient shares for this Loan \n Only ".$possible." is possible");
				return $resp;
			}
		}
		//$date = sprintf("%d-%02d-%02d", $year, $month, $mday);
	
		mysql_query("insert into loan_applic set group_id='".$group_id."', mem_id='".$mem_id."',  marital_status='".$marital_status."', residence='".$residence."', telno='".$telno."', postal_address='".$address."', amount='".$amount."', date='".$date."', reason='".$reason."', income1='".$income1."', income2='".$income2."', guarantor1='".$guarantor1."', guarantor2='".$guarantor2."', product_id='".$product_id."', approved='0', officer_id='".$officer_id."', branch_id='".$branch_id."'");
		//////////////////////
		//$resp->alert(mysql_error());
		$accno = mysql_fetch_assoc(mysql_query("select mem_no,first_name,last_name from member where id=".$mem_id));
		$action = "insert into loan_applic set group_id='".$group_id."', mem_id='".$mem_id."',  marital_status='".$marital_status."', residence='".$residence."', telno='".$telno."', postal_address='".$address."', amount='".$amount."', date='".$date."', reason='".$reason."', income1='".$income1."', income2='".$income2."', guarantor1='".$guarantor1."', guarantor2='".$guarantor2."', product_id='".$product_id."', approved='0', officer_id='".$officer_id."'";
		$msg = "Registered a Loan Application from member: ".$accno['first_name']." ".$accno['last_name']." - ".$accno['mem_no'];
		log_action($_SESSION['user_id'],$action,$msg);
	///////////////////////
		$resp->assign("status", "innerHTML", "<font color=red>Application registered</font>");
		$resp->call('xajax_add_applic');
	}
	return $resp;
}


//VERIFY GUARANTORS
function verify_guarantor($guarantor1, $guarantor2, $amount, $product_id, $former_amt, $new_amt){
	$resp = new xajaxResponse();
	$prod_res = mysql_query("select * from loan_product where id='".$product_id."'");
	$prod = mysql_fetch_array($prod_res);
	$_SESSION['guaranteed'] =1;
	if(preg_match("/,/", $guarantor) || preg_match("/,/", $guarantor2))
		return $resp;
	if($guarantor1=='' || $guarantor2=='')
		return $resp;
	if($guarantor1 == $guarantor2){
		$resp->alert("Please specify different guarantors");
		return $resp;
	}
	$_SESSION['guaranteed'] =1;
	$limit_res = mysql_query("select  * from branch");
	$limit = mysql_fetch_array($limit_res);
	if($prod['based_on'] == 'savings'){
		$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.type='free' and m.mem_id='".$guarantor1."'");
		$dep = mysql_fetch_array($dep_res);
		$dep_amt = ($dep['amount'] == NULL) ? 0 : $dep['amount'];

		$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.type='free' and m.mem_id='".$guarantor1."'");
		$with = mysql_fetch_array($with_res);
		$with_amt = ($with['amount'] == NULL) ? 0 : $with['amount'];

		$pledged_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where a.name='Compulsory Savings' and mem.mem_id='".$guarantor1."'");
		$pledged = @mysql_fetch_array($pledged_res);
		$pledged_amt = ($pledged['amount'] == NULL) ? 0 : $pledged['amount'];
			
		//INTEREST AWARDED
		$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id where m.mem_id='".$guarantor1."'");
		$int =  mysql_fetch_array($int_res);
		$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;

		//MONTHLY CHARGE
		$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts m on c.memaccount_id=m.id where m.mem_id='".$guarantor1."'");
		$charge = mysql_fetch_array($charge_res);
		$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;

		$balance = $dep_amt + $int_amt - $with_amt - $pledged_amt - $charge_amt;
		$possible = ($balance * $limit['guarantor_save_percent']) / 100;
		if($possible < $amount && $limit['guarantor_save_percent'] >0){
			$resp->alert("Application rejected! \nInsufficient compulsory savings for guarantor 1.");
			return $resp;
		}

		$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.type='free' and m.mem_id='".$guarantor2."'");
		$dep = mysql_fetch_array($dep_res);
		$dep_amt = ($dep['amount'] == NULL) ? 0 : $dep['amount'];

		$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.type='free' and m.mem_id='".$guarantor2."'");
		$with = mysql_fetch_array($with_res);
		$with_amt = ($with['amount'] == NULL) ? 0 : $with['amount'];

		$pledged_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where a.name='Compulsory Savings' and mem.mem_id='".$guarantor2."'");
		$pledged = @mysql_fetch_array($pledged_res);
		$pledged_amt = ($pledged['amount'] == NULL) ? 0 : $pledged['amount'];
			
		//INTEREST AWARDED
		$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id where m.mem_id=".$guarantor2."");
		$int =  mysql_fetch_array($int_res);
		$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;

		//MONTHLY CHARGE
		$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts m on c.memaccount_id=m.id where m.mem_id='".$guarantor2."'");
		$charge = mysql_fetch_array($charge_res);
		$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;

		$balance = $dep_amt + $int_amt - $with_amt - $pledged_amt - $charge_amt;
		$possible = ($balance * $limit['guarantor_save_percent']) / 100;
		if($possible < $amount && $limit['guarantor_save_percent'] >0){
			$resp->alert("Application rejected! \nInsufficient compulsory savings for guarantor 2.");
			return $resp;
		}
	}elseif($bassed_on == 'shares'){
		$shares_res = mysql_query("select sum(value) as value from shares where mem_id='".$guarantor1."'");
		$shares = @mysql_fetch_array($shares_res);
		$shares_amt = ($shares['value'] == NULL) ? 0 : $shares['amount']; 
			
		$donated_res = mysql_query("select sum(value) as value from share_transfer where to_id='".$guarantor1."'");
		$donated = @mysql_fetch_array($donated_res);
		$donated_amt = ($donated['value'] == NULL) ? 0 : $donated['value'];

		$trans_res = mysql_query("select sum(value) as value from share_transfer where from_id='".$guarantor1."'");
		$trans = @mysql_fetch_array($trans_res);
		$trans_amt = ($trans['value'] == NULL) ? 0 : $trans['value'];

		$pledged_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where a.name='Compulsory Shares' and mem.mem_id='".$guarantor1."'");
		$pledged = @mysql_fetch_array($pledged_res);
		$pledged_amt = ($pledged['amount'] == NULL) ? 0 : $pledged['amount'];

		$balance = $shares_amt + $donated_amt - $trans_amt - $pledged_amt;

		$possible = ($balance * $limit['guarantor_share_percent']) / 100;
		if($amount > $possible && $limit['guarantor_share_percent'] >0){
			$resp->alert("Application rejected! \n Guarantor 1 has insufficient shares for this Loan");
			return $resp;
		}

		$shares_res = mysql_query("select sum(value) as value from shares where mem_id='".$guarantor2."'");
		$shares = @mysql_fetch_array($shares_res);
		$shares_amt = ($shares['value'] == NULL) ? 0 : $shares['amount']; 
			
		$donated_res = mysql_query("select sum(value) as value from share_transfer where to_id='".$guarantor2."'");
		$donated = @mysql_fetch_array($donated_res);
		$donated_amt = ($donated['value'] == NULL) ? 0 : $donated['value'];

		$trans_res = mysql_query("select sum(value) as value from share_transfer where from_id='".$guarantor2."'");
		$trans = @mysql_fetch_array($trans_res);
		$trans_amt = ($trans['value'] == NULL) ? 0 : $trans['value'];

		$pledged_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where a.name='Compulsory Shares' and mem.mem_id='".$guarantor2."'");
		$pledged = @mysql_fetch_array($pledged_res);
		$pledged_amt = ($pledged['amount'] == NULL) ? 0 : $pledged['amount'];

		$balance = $shares_amt + $donated_amt - $trans_amt - $pledged_amt;

		$possible = ($balance * $limit['guarantor_share_percent']) / 100;
		if($amount > $possible && $limit['guarantor_share_percent'] >0){
			$resp->alert("Application rejected! \n Guarantor 2 has insufficient shares for this Loan");
			return $resp;
		}
	}
	return $resp;
}

//LIST LOAN APPLICATIONS
function list_applics($cust_name, $cust_no, $account_name, $from_date,$to_date, $category,$branch_id,$search=''){

	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	//$resp->assign("status", "innerHTML", "");
	$start_time = microtime(true);
	$head="LOAN APPLICATIONS";
	$branch=($branch_id=='all'||$branch_id=='')?NULL:"and dis.branch_id=".$branch_id;
	if($category=='all'){
		$head= "LOAN APPLICATIONS";
		$choose = "";
	}elseif($category == 'approval'){
		$head = "LOAN APPLICATIONS AWAITING APPROVAL";
		$choose = " applic.approved='0' and ";
	}elseif($category == 'disbursement'){
		$head = "LOANS AWAITING DISBURSEMENT";
		$choose = " applic.approved ='1' and dis.id is null and ";
	}
		 	
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR '.$head.'</h3>
                            </div>               
                            <div class="panel-body">
 		<div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Customer Name:</label>
                                            <input type="text" id="cust_name" value="All" class="form-control">                                            
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Member No:</label>
                                            <input type=text id="cust_no" value="All" class="form-control">
                                        </div>
                                    </div>
                                </div>
        
        <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Loan Product:</label>
                                            <select id="account_name" class="form-control"><option value="">All';
	$prod_res = mysql_query("select *from view_loan_product_accounts");
	while($prod = @ mysql_fetch_array($prod_res)){
		$content .= "<option value='".$prod['account_name']."'>".$prod['account_no']." - ".$prod['account_name'];
	}
	$content .='</select>                                           
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Branch:</label>
                                            <span>'.branch_rep($branch_id).'</span>
                                        </div>
                                    </div>
                                </div>                               
                                                                                                               
                          <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Date Range:</label>
                                            <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>      
                           </div>                                                                       
                                    </div>
                                </div>                          
                            '; 
                                                                                             		
		 $content .= "<div class='panel-footer'>                              
                                
                                <input type='button' class='btn  btn-primary' id ='search' value='Search' onclick=\"xajax_list_applics(getElementById('cust_name').value, getElementById('cust_no').value, getElementById('account_name').value,getElementById('from_date').value, getElementById('to_date').value, '".$category."', getElementById('branch_id').value, getElementById('search').value);\">
                            </div></div>
                        </form>
                        <!--/ Form default layout -->
                    </div></div>";
                    $resp->call("createDate","from_date");
		    $resp->call("createDate","to_date");             	
	
                    //$resp->assign("display_div", "innerHTML", $content);
        if($search){
	if($from_date=='' || $to_date==''){
		$cont = '<font color="red">Please Select The Range for Applications You Would Like To List!</font>';              
                    
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
	
	$name = ($cust_name == 'All') ? "" : $cust_name;
	$mem_no = ($cust_no == 'All') ? "" : $cust_no;
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
	$sth = mysql_query("select count(applic.id) as num from loan_applic applic join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id join employees o on applic.officer_id=o.employeeId left join disbursed dis on applic.id=dis.applic_id where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') ".$branch." and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$choose." applic.date >='".$from_date."' and applic.date <= '".$to_date."' order by o.firstName, o.lastName, m.first_name, m.last_name, m.mem_no");
	
	//$resp->assign("status", "innerHTML", "select dis.id as disbursed_id, applic.group_id as group_id, applic.approved as approved, applic.reason as reason, (DATEDIFF(applic.date, m.dob)/365) as age, o.firstName as of_firstName, o.lastName as of_lastName, m.id as mem_id, m.sign_name as sign_name, m.photo_name as photo_name, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, applic.amount as amount, applic.date as date, applic.guarantor1 as guarantor1, applic.guarantor2 as guarantor2, applic.id as applic_id, a.name as account_name, a.account_no as account_no, m.id as mem_id, p.based_on as based_on from loan_applic applic join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id join employees o on applic.officer_id=o.employeeId left join disbursed dis on applic.id=dis.applic_id where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') ".$branch." and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$choose." applic.date >='".$from_date."' and applic.date <= '".$to_date."' order by o.firstName, o.lastName, m.first_name, m.last_name, m.mem_no");
	if(mysql_numrows($sth) == 0){
	
	$cont = '<font color=red>No applications found in your search options!</font>';
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	
	else{
	$num=mysql_fetch_array($sth);
	//$max_row = ceil($num['num']/$num_rows);
	$sth2 = mysql_query("select dis.id as disbursed_id, applic.group_id as group_id, applic.approved as approved, applic.reason as reason, (DATEDIFF(applic.date, m.dob)/365) as age, o.firstName as of_firstName, o.lastName as of_lastName, m.id as mem_id, m.sign_name as sign_name, m.photo_name as photo_name, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, applic.amount as amount, applic.date as date, applic.guarantor1 as guarantor1, applic.guarantor2 as guarantor2, applic.id as applic_id, a.name as account_name, a.account_no as account_no, p.based_on as based_on from loan_applic applic join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id join employees o on applic.officer_id=o.employeeId left join disbursed dis on applic.id=dis.applic_id where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') ".$branch." and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$choose." applic.date >='".$from_date."' and applic.date <= '".$to_date."' order by o.firstName, o.lastName, m.first_name, m.last_name, m.mem_no");
	if(mysql_numrows($sth2) > 0){
		
	$former_officer ="";
	$i=$stat+1; 
	$amt_sub_total = 0;
	$total__amount = mysql_fetch_assoc(mysql_query("select sum(applic.amount) as amountd from loan_applic applic join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id join employees o on applic.officer_id=o.employeeId left join disbursed dis on applic.id=dis.applic_id where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') ".$branch." and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$choose." applic.date >='".$from_date."' and applic.date <= '".$to_date."' order by o.firstName, o.lastName, m.first_name, m.last_name, m.mem_no"));
	//if(mysql_num_rows($sth2)>0)
//start time	
	//$start_time2=microtime();
	while($row = mysql_fetch_array($sth2)){
		$officer = $row['of_firstName']." ".$row['of_lastName'];
		if(strcmp($former_officer, $officer) != 0){
			
			$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                             <p><h3 class="semibold text-primary mt0 mb5">LIST OF '.$head.'</h3></p>
                                
                               <p><h5>LOAN OFFICER:&nbsp;<font color="#00BFFF">'.strtoupper($officer).'</font></h5></p>
                                <p><h5>TOTAL DISBURSED:&nbsp;<font color="#00BFFF">'.number_format($total__amount['amountd'],2).'</font></h5></p>
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
 		
 		$content .= '<thead><th>#</th><th>NAME</th><th>MEMBER No.</th><th>AGE</th><th>PHOTO</th><th>SIGNATURE</th><th>PRODUCT</th><th>AMOUNT</th><th>REASON</th><th>COLLATERAL</th><th>GUARANTORS</th><th>DATE</th><th>LOAN BALANCE</th><th>POSSIBLE LOAN ON SHARES</th><th>POSSIBLE LOAN ON SAVINGS</th><th>EDIT COLLATERAL</th><th>APPROVE</th><th>DISBURSE</th></thead><tbody>';
 		$former_officer = $officer;
 		
		}
		$col_res = mysql_query("select * from collateral where applic_id='".$row['applic_id']."'");
		$col = @mysql_fetch_array($col_res);
		if($col['collateral1'] == NULL){
			$collateral1 = "--"; 
			$value1 = "--";
		}else{
			$collateral1 = $col['collateral1'];
			$value1 = "(".$col['value1'].")";
		}

		if($col['collateral2'] == NULL){
			$collateral2 = "--";
			$value2 = "--";
		}else{
			$collateral2 = $col['collateral2'];
			$value2 = "(".$col['value2'].")";
		}
		$sub = "<table><tr><td>".$collateral1."</td><td>".$value1."</td></tr> <tr><td>".$collateral2." </td><td>".$value2."</td></tr></table>";
		$x=1;
		if($row['group_id'] > 0){
			$list = $row['guarantor1'] .", ".$row['guarantor2'];
			$guarantors = preg_split('/, /', $list);
			$guar='';
			//$resp->assign("status", "innerHTML", $list);
			while($id= current($guarantors)){
				$cat_res = mysql_query("select * from member where id='".$id."'");
				$cat = mysql_fetch_array($cat_res);
				$guar .= $x. ". ".$cat['first_name']." " .$cat['last_name']."<br>";
				$x++;
				next($guarantors);
			}
		}else{
			$cat_res = mysql_query("select * from member where id='".$row['guarantor1']."' or id='".$row['guarantor2']."'");
			$x=1;
			$guar='';
			while($cat = mysql_fetch_array($cat_res)){
				$guar .= $x. ". ".$cat['first_name']." " .$cat['last_name']."<br>";
				$x++;
			}
		}

		//LOAN BALANCE
		$loan_res = mysql_query("select sum(l.balance) as balance from disbursed l join loan_applic applic on l.applic_id=applic.id where applic.mem_id='".$row['mem_id']."' and l.balance >0");
		$loan = mysql_fetch_array($loan_res);
		$loan_balance = ($loan['balance'] == NULL) ? 0 : $loan['balance'];
		//MAX PERCENT OF SAVINGS OR SHARES THAT CAN BE LOAN
		$limit_res = mysql_query("select * from branch where branch_no='".$branch_id."'");
		if(mysql_num_rows($limit_res)>0)
		$limit = mysql_fetch_array($limit_res);
		else
			$limit = mysql_fetch_assoc(mysql_query("select * from branch limit 1"));
		//POSSIBLE LOAN ON SAVINGS FOR THIS MEMBER
		$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.type='free' and m.mem_id='".$row['mem_id']."'");
		$dep = mysql_fetch_array($dep_res);
		$dep_amt = ($dep['amount'] == NULL) ? 0 : $dep['amount'];

		$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.type='free' and m.mem_id='".$row['mem_id']."'");
		$with = mysql_fetch_array($with_res);
		$with_amt = ($with['amount'] == NULL) ? 0 : $with['amount'];

		$pledged_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.type='pledged' and m.mem_id='".$row['mem_id']."' and p.account_id in (select pledged_account_id from savings_product)");
		$pledged = mysql_fetch_array($pledged_res);
		$pledged_amt = ($pledged['amount'] == NULL) ? 0 : $pledged['amount'];

		//INTEREST AWARDED
		$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id where m.mem_id=".$row['mem_id']."");
		$int =  mysql_fetch_array($int_res);
		$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;

		//MONTHLY CHARGE
		$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts m on c.memaccount_id=m.id where m.mem_id='".$row['mem_id']."'");
		$charge = mysql_fetch_array($charge_res);
		$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;

		//LOAN REPAYMENTS
		$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id where m.mem_id='".$row['mem_id']."'");
		$pay = mysql_fetch_array($pay_res);
		$pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;

		//INCOME DEDUCTIONS
		$inc_res = mysql_query("select sum(i.amount) as amount from other_income i join mem_accounts m on i.mode=m.id where m.mem_id='".$row['mem_id']."'");
		$inc = mysql_fetch_array($inc_res);
		$inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;

		$balance = $dep_amt + $int_amt - $with_amt - $pledged_amt - $charge_amt - $pay_amt - $inc_amt;
		if($limit['loan_save_percent'] == 0){
			$save_percent =  100000000000;
			$balance = 100;
		}else
			$save_percent = $limit['loan_save_percent'];
		//$branch=mysql_fetch_array("select * from branch");

		$possible_savings_loan = ($balance * $save_percent) / 100;
		
		//POSSIBLE LOAN ON SHARES	
		$shares_res = mysql_query("select sum(value) as value from shares where mem_id='".$row['mem_id']."'");
		$shares = mysql_fetch_array($shares_res);
		$shares_amt = ($shares['value'] == NULL) ? 0 : $shares['value']; 
		
		$donated_res = mysql_query("select sum(value) as value from share_transfer where to_id='".$row['mem_id']."'");
		$donated = mysql_fetch_array($donated_res);
		$donated_amt = ($donated['value'] == NULL) ? 0 : $donated['value'];

		$trans_res = mysql_query("select sum(value) as value from share_transfer where from_id='".$row['mem_id']."'");
		$trans = mysql_fetch_array($trans_res);
		$trans_amt = ($trans['value'] == NULL) ? 0 : $trans['value'];

		$pledged_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where a.name='Compulsory Shares' and mem.mem_id='".$row['mem_id']."'");
		$pledged = mysql_fetch_array($pledged_res);
		$pledged_amt = ($pledged['amount'] == NULL) ? 0 : $pledged['amount'];

		$balance = $shares_amt + $donated_amt - $trans_amt - $pledged_amt;
		if($limit['loan_share_percent'] == 0){
			$share_percent =  100000000000;
			$balance = 100;
		}else
			$share_percent = $limit['loan_share_percent'];
		//$resp->alert($share);
		$possible_shares_loan = ($balance * $share_percent) / 100;
		
		//PASS LIMITS TO DISBURSEMENT MODULE
		if($row['based_on'] == 'savings')
			$possible_amt = $possible_savings_loan;
		else
			$possible_amt = $possible_shares_loan;
		if($row['disbursed_id'] != NULL){
			$approving = "Disbursed";
			$edit = "Disbursed";
		}elseif($row['approved'] == '0')
			$approving = "<a href='javascript:;' onclick=\"xajax_approve_applic('".$row['applic_id']."', '".$row['approved']."',    '".$cust_name."', '".$cust_no."', '".$account_name."', '".$from_date."','".$to_date."', '".$category."','".$branch_id."');\">Approve</a>";
		else
			$approving = "<a href='javascript:;' onclick=\"xajax_approve_applic('".$row['applic_id']."', '".$row['approved']."', '".$cust_name."', '".$cust_no."', '".$account_name."', '".$from_date."', '".$to_date."', '".$category."','".$branch_id."');\">Disapprove</a>";
		if($row['disbursed_id'] == NULL){
			$edit = $edit = "<a href='javascript:;' onclick=\"xajax_edit_applic('".$row['applic_id']."', '".$cust_name."', '".$cust_no."', '".$account_name."', '".$from_date."','".$to_date."', '".$category."','".$branch_id."');\">Edit/ Collateral</a>";		
		}
		if($row['disbursed_id'] == NULL && $row['approved'] ==1)
			$disburse="<a href='javascript:;' onclick=\"xajax_add_disbursed('".$row['applic_id']."', '".$row['amount']."', '".$possible_amt."', '".$row['based_on']."','".$branch_id."');return false;\">Disburse</a>";
		else
			$disburse = "--";
		//$color = ($i%2 == 0) ? "lightgrey" : "white";
		
		$content .="<tr><td>".$i."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".floor($row['age'])."</td><td align=right><img src='photos/".$row['photo_name']."?dummy=".time()."' width=90 height=90><br>Photo</td><td align=right><img src='signs/".$row['sign_name']."?dummy=".time()."' width=90 height=90><br>Signature</td><td>".$row['account_no']." - ".$row['account_name']."</td><td>".number_format($row['amount'], 2)."</td><td>".$row['reason']."</td><td>".$sub."</td><td>".$guar."</td><td>".$row['date']."</td><td>".number_format($loan_balance, 2)."</td><td>".number_format($possible_shares_loan, 2)."</td><td>".number_format($possible_savings_loan, 2)."</td><td>".$edit."</td><td>".$approving."</td><td>".$disburse."</td></tr>";
		$i++;
		$amt_sub_total += $row['amount'];
	}
	
	$content .= "<tr><td><b>TOTAL</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td><b>".number_format($amt_sub_total, 2)."</b></td><td></td><td></td><td></td><td></td><td></td><td></td></td><td></td><td></td><td></td><td></tr>";
	$content .= "</tbody></table></div>";
   
	$resp->call("createTableJs");
	}
	else{
	
	$cont = '<font color=red>No applications found in your search options!</font>';
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	}	
	}
	}
	$finish_time=microtime(true);
	$time_deff=$finish_time-$start_time;
	//$resp->alert($time_deff);
	
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


//EDIT LOAN APPLICATION
function edit_applic($applic_id, $cust_name, $cust_no, $account_name, $from_date,$to_date, $category, $branch_id){
	$resp = new xajaxResponse();
	$former_res = mysql_query("select g.name as group_name, m.id as mem_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, ap.marital_status as status, ap.residence as residence, ap.telno as telno, ap.postal_address as postal_address, ap.amount as amount, date_format(ap.date, '%Y') as year, date_format(ap.date, '%m') as month, date_format(ap.date, '%d') as mday, ap.income1 as income1, o.employeeId as officer_id, o.firstName as of_firstName, o.lastName as of_lastName, ap.income2 as income2, ap.reason as reason, ap.guarantor1 as guarantor1, ap.guarantor2 as guarantor2, ap.product_id as product_id, a.account_no as account_no, a.name as account_name from loan_applic ap join member m on ap.mem_id=m.id join loan_product p on ap.product_id=p.id join accounts a on p.account_id=a.id join employees o on ap.officer_id=o.employeeId left join loan_group g on ap.group_id=g.id where ap.id='".$applic_id."'");

	$former = mysql_fetch_array($former_res);
	$mem_name = $former['first_name']." ".$former['last_name'];
	
	$content = '<form class="panel form-horizontal form-bordered">
		<div class="panel-body pt0 pb0">';
$content .= '<div class="form-group header bgcolor-default">
                                       <div class="col-md-12">
                                       
                                             <p><h3 class="semibold text-primary mt0 mb5">EDIT LOAN APPLICATION</h3></p>';
            if($former['group_name'] != NULL)
           $content .= '<p><h5>GROUP:&nbsp;<font color="#00BFFF">'.$former['group_name'].'</font></h5></p>';                   
 $content .= '<p><h5>MEMBER NAME:&nbsp;<font color="#00BFFF">'.$former['first_name']." ".$former['last_name'].'</font></h5></p>
 <p><h5>MEMBER No:&nbsp;<font color="#00BFFF">'.$former['mem_no'].'</font></h5></p>                                                                                                                                                                                                                                     
                                            </div> </div>';                                           
                                                                                     					
	             $content .= '<div class="form-group">
	                                   
	                                   <div class="col-sm-6">
                                            <label class="control-label">Marital Status:</label>
                                           <select id="marital_status" class="form-control"><option value="'.$former['status'].'">'.$former['status'].'<option value="Single">Single<option value="Married">Married</select>
                                            </div>
                                            
                                           <div class="col-sm-6">
                                            <label class="control-label">Phone No:</label>
                                            <input type="text" id="applic_telno" value="'.$former['telno'].'" class="form-control">
                                            </div>
                                            </div>';
                                            
                     $content .= '<div class="form-group">
                                           
	                                   <div class="col-sm-6">
                                            <label class="control-label">Postal Address:</label>
                                           <input type="text" id="applic_address" value="'.$former['postal_address'].'" class="form-control">
                                            </div>
                                            
                                            <div class="col-sm-6">                      
                                            <label class="control-label">Residence:</label>
                                           <input type="text" id="applic_residence" value="'.$former['residence'].'" class="form-control">
                                            </div></div>';
                                            
                     $content .= '<div class="form-group">
                                            
	                                   <div class="col-sm-6">
                                            <label class="control-label">Income Source 1:</label>
                                           <input type="text" id="applic_income1" value="'.$former['income1'].'"  class="form-control">
                                            </div>
                                            
                                           <div class="col-sm-6"> 
                                            <label class="control-label">Income Source 2:</label>
                                           <input type="text" id="applic_income2" value="'.$former['income1'].'" class="form-control">
                                           </div></div>';
                                            
                    $prod_res = mysql_query("select a.name as name, a.account_no as account_no, p.id as id from loan_product p join accounts a on p.account_id=a.id where p.id<>'".$former['product_id']."' order by a.account_no, a.name");
	
                     $content .= '<div class="form-group">
                                           
	                                   <div class="col-sm-6">
                                            <label class="control-label">Loan Product:</label>
                                           
                                           <select id="applic_product_id" class="form-control"><option value="'.$former['product_id'].'">'.$former['account_no'].' - '.$former['account_name'];
	while($prod = @ mysql_fetch_array($prod_res)){
		$content .= "<option value='".$prod['id']."'>".$prod['account_no']. " - ".$prod['name'];
	}
			$content .='</select></div>
	 				<div class="col-sm-6">
                                            <label class="control-label">Amount:</label>
                                            
                                           <input type="int" name="applic_amount" id="applic_amount" value="'.$former['amount'].'"  class="form-control">
                                           </div></div>';
                                            	                                              
        $content .= '<div class="form-group">
	                                  
                                            
                                           <div class="col-sm-6">
                                            <label class="control-label">Reason:</label>
                                           
                                           <input type="text" name="reason" id="reason" value="'.$former['reason'].'" class="form-control">
                                            </div>';
                                  if($former['group_name'] == NULL){
		$sth = mysql_query("select * from member where id='".$former['guarantor1']."'");
		$row = mysql_fetch_array($sth);          
                                            
                                           $content .= '<div class="col-sm-6">
                                            <label class="control-label">Guarantor 1:</label>
                                           
                                           <select id="guarantor1" class="form-control"><option value="'.$former['guarantor1'].'">'.$row['first_name'].' '.$row['last_name'];
		$sth1 = mysql_query("select * from member where id<>'".$former['guarantor1']."' and  id<>'".$former['guarantor2']."' and id<>'".$former['mem_id']."' order by first_name, last_name, mem_no");
		$sth2 = mysql_query("select * from member where id<>'".$former['guarantor1']."' and  id<>'".$former['guarantor2']."' and id<>'".$former['mem_id']."' order by first_name, last_name, mem_no");
		while($row = @mysql_fetch_array($sth1)){
			$content .= "<option value='".$row['id']."'>".$row['mem_no']." - ".$row['first_name']. " " .$row['last_name'];
		}
		$sth = mysql_query("select * from member where id='".$former['guarantor2']."'");
		$row = mysql_fetch_array($sth);
		$content .= '</select></div>
                                            </div>'; 
                                                                                                                                                          	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
		
		
		 $content .= '<div class="form-group">
	                                  
                                      <div class="col-sm-6">
                                            <label class="control-label">Guarantor 2:</label>
                                            
                                           <select id="guarantor2" class="form-control" onchange=\'xajax_verify_guarantor(getElementById("guarantor1").value, getElementById("guarantor2").value, getElementById("applic_amount").value, getElementById("applic_product_id").value, "'.$former['amount'].'", getElementById("applic_amount").value);\'><option value="'.$former['guarantor2'].'">'.$row['first_name'].' '.$row['last_name'];
		while($row = @mysql_fetch_array($sth2)){
			$content .= "<option value='".$row['id']."'>".$row['mem_no']." - ".$row['first_name']. " " .$row['last_name'];
		}
				
	$content .= '</select></div>';
	
	}elseif($former['group_name'] != NULL){
	
	              $content .= "<div id='guarantor_div'><input type='hidden' value='".$former['guarantor1']."' id='guarantor1'><input type='hidden' id='guarantor2' value='".$former['guarantor2']."'></div>";
	              }
                                                                                    
        // $content .= '<div class="form-group">
	                                $content .= '<div class="col-sm-6">
                                            <label class=" control-label">Loan Officer:</label>
                                            
                                           <select id="applic_officer_id" class="form-control"><option value="'.$former['officer_id'].'">'.$former['of_firstName'].' '.$former['of_lastName'];
	$sth = mysql_query("select e.employeeId as id, e.firstName as first_name,e.lastName as last_name, e.roleId, r.roleId from employees e join roles r on e.roleId = r.roleId where r.roleName ='Credit Officer' and e.employeeId<>'".$former['officer_id']."'");
	while($row = mysql_fetch_array($sth)){
		$content .= "<option value='".$row['id']."'>".$row['first_name']." ".$row['last_name'];
	}
	$content .= '</select></div></div>';   
	
			 $content .= '<div class="form-group">                               
                                                                       
	                                   <div class="col-sm-6">
                                            <label class="control-label">Date:</label>
                                            
                                           <input type="text" class="form-control" id="date" name="date" placeholder="date" />
                                            </div></div>';
					$resp->call("createDate","date");
	$content .= "<div class='panel-footer'><button type='reset' class='btn btn-default' onclick=\"xajax_list_applics('".$cust_name."', '".$cust_no."', '".$account_name."', '".$from_date."','".$to_date."', '".$category."', '".$branch_id."', '');\">Back</button>&nbsp;
 <button type='button' class='btn btn-default' onclick=\"xajax_delete_applic('".$applic_id."');\">Delete</button>&nbsp;<button type='button' class='btn btn-primary' onclick=\"xajax_verify_guarantor(getElementById('guarantor1').value, getElementById('guarantor2').value, getElementById('applic_amount').value, getElementById('applic_product_id').value, '".$former['amount']."', getElementById('applic_amount').value); xajax_update_applic('".$applic_id."', getElementById('marital_status').value, getElementById('applic_residence').value, getElementById('applic_telno').value, getElementById('applic_address').value, getElementById('applic_income1').value, getElementById('applic_income2').value, getElementById('applic_amount').value, getElementById('guarantor1').value, getElementById('guarantor2').value, getElementById('reason').value, getElementById('applic_product_id').value, getElementById('applic_officer_id').value, getElementById('date').value);\">Save</button>";
     $content .= '</div></div></form>';

	//COLLATERAL
	$sth = mysql_query("select * from collateral where applic_id='".$applic_id."'");
	if(@ mysql_numrows($sth)==0){
		$content .= '<form class="panel form-horizontal form-bordered">
		<div class="panel-body pt0 pb0">';
$content .= '<div class="form-group header bgcolor-default">
                                       <div class="col-md-12">
                                       
                                             <h3 class="panel-title">REGISTER COLLATERAL FOR THIS APPLICATION</h3>
                                                                                                                                             
                                            </div>                                           
           </div>';                                                                                      						
	             $content .= '<div class="form-group">
	                                   
	                                   <div class="col-sm-6">
                                            <label class="control-label">Collateral 1:</label>
                                           <input type=text id="collateral1" class="form-control">
                                            </div>
                                            
                                           <div class="col-sm-6">
                                            <label class="control-label">Value for Collateral 1:</label>
                                            <input type=text id="value1" class="form-control">
                                            </div>
                                            </div>';
                                            
                     $content .= '<div class="form-group">
                                           
	                                   <div class="col-sm-6">
                                            <label class="control-label">Collateral 2:</label>
                                           <input type=text id="collateral2" class="form-control">
                                            </div>
                                            
                                            <div class="col-sm-6">                      
                                            <label class="control-label">Value for Collateral 2:</label>
                                           <input type=text id="value2" class="form-control">
                                            </div></div>';                 
		
	$content .= "<div class='panel-footer'><button class='btn btn-primary' onclick=\"xajax_insert_collateral('".$applic_id."', getElementById('collateral1').value, getElementById('value1').value, getElementById('collateral2').value, getElementById('value2').value, '".$applic_id."', '".$cust_name."', '".$cust_no."', '".$account_name."', '".$from_date."','".$to_date."', '".$category."','".$branch_id."')\">Save</button>";
 
     $content .= '</div></div></form></div>';
	}else{
		$list = mysql_fetch_array(mysql_query("select * from collateral where applic_id='".$applic_id."'"));
		$content .= '<form class="panel form-horizontal form-bordered">
		<div class="panel-body pt0 pb0">';
$content .= '<div class="form-group header bgcolor-default">
                                       <div class="col-md-12">
                                       
                                             <p><h3 class="panel-title">COLLATERAL FOR THIS APPLICATION</h3></p>';
                $content .= "<table><tr><td><b>Name</b></td><td><b>Value</b></td></tr>
		<tr><td>".$list['collateral1']."</td><td>".$list['value1']."</td></tr>
		<tr><td>".$list['collateral2']."</td><td>".$list['value2']."</td></tr>
		</table
                                                                                                                                                                              
                                            ></div>                                           
            </div></div></form>";  

		$row = mysql_fetch_array($sth);
		$content .= '<form class="panel form-horizontal form-bordered">
		<div class="panel-body pt0 pb0">';
$content .= '<div class="form-group header bgcolor-default">
                                       <div class="col-md-12">
                                       
                                             <p><h3 class="panel-title">UPDATE COLLATERAL FOR THIS APPLICATION</h3></p>
                                                                                                                                                                              
                                            </div>                                           
            </div>';                                                                                      						
	             $content .= '<div class="form-group">
	                                   
	                                   <div class="col-sm-6">
                                            <label class="control-label">Collateral 1:</label>
                                           <input type=text id="collateral1" value="'.$row['collateral1'].'" class="form-control">
                                            </div>
                                            
                                           <div class="col-sm-6">
                                            <label class="control-label">Value for Collateral 1:</label>
                                            <input type=text id="value1"  value="'.$row['value1'].'" class="form-control">
                                            </div>
                                            </div>';
                                            
                     $content .= '<div class="form-group">
                                           
	                                   <div class="col-sm-6">
                                            <label class="control-label">Collateral 2:</label>
                                           <input type=text id="collateral2"  value="'.$row['collateral2'].'" class="form-control">
                                            </div>
                                            
                                            <div class="col-sm-6">                      
                                            <label class="control-label">Value for Collateral 2:</label>
                                           <input type=text id="value2"  value="'.$row['value2'].'" class="form-control">
                                            </div></div>';
                                            
                    
		
	$content .= "<div class='panel-footer'><button class='btn btn-default' onclick=\"xajax_delete_collateral('".$list['id']."', '".$applic_id."', '".$cust_name."', '".$cust_no."', '".$account_name."', '".$from_date."','".$to_date."', '".$category."','".$branch_id."')\">Delete</button><button class='btn btn-primary' onclick=\"xajax_update_collateral('".$applic_id."', getElementById('collateral1').value, getElementById('value1').value, getElementById('collateral2').value, getElementById('value2').value, '".$applic_id."', '".$cust_name."', '".$cust_no."', '".$account_name."', '".$from_date."','".$to_date."', '".$category."','".$branch_id."')\">Update</button>";
 
     $content .= '</div></div></form></div>';
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function delete_collateral($id, $applic_id, $cust_name, $cust_no, $account_name, $from_date,$to_date, $category,$branch_id){
	$resp = new xajaxResponse();
	$resp->confirmCommands(1, "Do you really want to delete the collateral");
	$resp->call("xajax_delete2_collateral", $id, $applic_id, $cust_name, $cust_no, $account_name, $from_date,$to_date, $category,$branch_id);
	return $resp;
}

function delete2_collateral($id, $applic_id, $cust_name, $cust_no, $account_name, $from_date,$to_date, $category,$branch_id){
	$resp = new xajaxResponse();
	mysql_query("delete from collateral where id=$id");
	////////////
	$action = "delete from collateral where id=$id";
	$msg = "Deleted a collateral for member: ".$accno['first_name']." ".$accno['last_name']." - ".$accno['mem_no'];
	log_action($_SESSION['user_id'],$action,$msg);
	////////////
	$resp->call('xajax_edit_applic', $applic_id, $cust_name, $cust_no, $account_name, $from_date, $to_date, $category,$branch_id);
	$resp->assign("status", "innerHTML", "<FONT COLOR=RED>Collateral deleted!</FONT>");
	return $resp;
}

//UPDATE COLLATERAL
function update_collateral($applic_id, $collateral1, $value1, $collateral2, $value2, $applic_id, $cust_name, $cust_no, $account_name, $from_date,$to_date, $category,$branch_id){
	$resp = new xajaxResponse();
	/*if($_SESSION['position'] <>'Manager'){
		$resp->alert("Access Denied! \nOnly the Manager can manage account");
		return $resp;
	}*/
	if($collateral1 == "" || $value1=='')
		$resp->alert("Collateral rejected! \nPlease fill in Collateral 1 and its value");
	elseif(($collateral2 <>"" && $value2 == "") || ($collateral2 =="" && $value2 <> ""))
		$resp->alert("Collateral rejected! \n Please fill in all the details of Collateral 2");
	else{
		$sth = mysql_query("select * from disbursed where applic_id='".$applic_id."'");
		if(@ mysql_numrows($sth) >0){
			$resp->alert("Update rejected! The loan has been disbursed already");
		}else{
			mysql_query("update collateral set collateral1='".$collateral1."', value1='".$value1."', collateral2='".$collateral2."', value2='".$value2."' where applic_id='".$applic_id."'");
			/////////////////
			$action = "update collateral set collateral1='".$collateral1."', value1='".$value1."', collateral2='".$collateral2."', value2='".$value2."' where applic_id='".$applic_id."'";
			$msg = "Edited a collateral set collateral1:".$collateral1." at a value:".$value1."collateral2:".$collateral2." at a value:".$value2." for member: ".$accno['first_name']." ".$accno['last_name']." - ".$accno['mem_no'];
			log_action($_SESSION['user_id'],$action,$msg);
	////////////////////
			$resp->assign("status", "innerHTML", "<font color=red>Collateral updated!</font>");
		}
	}
	$resp->call('xajax_edit_applic', $applic_id, $cust_name, $cust_no, $account_name, $from_date, $to_date, $category,$branch_id);
	return $resp;
}

//INSERT COLLATERAL 
function insert_collateral($applic_id, $collateral1, $value1, $collateral2, $value2, $applic_id, $cust_name, $cust_no, $account_name, $from_date,$to_date, $category,$branch_id){
	$resp = new xajaxResponse();
	if($collateral1 == "" || $value1=='')
		$resp->alert("Collateral rejected! \nPlease fill in Collateral 1 and its value");
	elseif(($collateral2 <>"" && $value2 == "") || ($collateral2 =="" && $value2 <> ""))
		$resp->alert("Collateral rejected! \n Please fill in all the details of Collateral 2");
	else{
		$branch = mysql_fetch_array(mysql_query("select branch_id from loan_applic where id='".$applic_id."'"));
		mysql_query("insert into collateral set applic_id='".$applic_id."', collateral1='".$collateral1."', value1='".$value1."', collateral2='".$collateral2."', value2='".$value2."', branch_id='".$branch['branch_id']."'");
		/////////////////////
		//$resp->alert(mysql_error());
		$action = "insert into collateral set applic_id='".$applic_id."', collateral1='".$collateral1."', value1='".$value1."', collateral2='".$collateral2."', value2='".$value2."'";
		$msg = "Registered a collaterals:".$collatera11." and ".$collateral2." at values: ".$value1." and ".$value2." respectively for member: ".$cust_name." - ".$cust_no;
		log_action($_SESSION['user_id'],$action,$msg);
	//////////////////////
		$resp->call('xajax_edit_applic', $applic_id, $cust_name, $cust_no, $account_name, $from_date,$to_date, $category,$branch_id);
		$resp->assign("status", "innerHTML", "<font color=red>Collateral registered</font>");
	}
	return $resp;
}
//APPROVE / DISAPPROVE APPLICATION
function approve_applic($applic_id, $status, $cust_name, $cust_no, $account_name, $from_date, $to_date, $category,$branch_id){
	$resp = new xajaxResponse();
	if($status == '0'){
		mysql_query("update loan_applic set approved='1' where id='".$applic_id."'");
		$approving = "Application approved!";
			////////////
		$action = "update loan_applic set approved='1' where id='".$applic_id."'";
		$msg = "Approved loan application for: ".$cust_name." - ".$cust_no." on account:".$account;
		log_action($_SESSION['user_id'],$action,$msg);
		////////////
	}elseif($status == '1'){
		mysql_query("update loan_applic set approved='0' where id='".$applic_id."'");
		$approving = "Application disapproved!";
		////////////
	$action = "update loan_applic set approved='0' where id='".$applic_id."'";
	$msg = "Disapproved loan application for: ".$cust_name." - ".$cust_no." on account:".$account;
	log_action($_SESSION['user_id'],$action,$msg);
	////////////
	
	}
	$resp->call('xajax_list_applics', $cust_name, $cust_no, $account_name, $from_date,$to_date, $category,$branch_id,'');
	$resp->assign("status", "innerHTML", "<font color=red>".$approving."</font>");
	return $resp;
}
//UPDATE APPIC
function update_applic($applic_id, $status, $residence, $telno, $address, $income1, $income2, $amount, $guarantor1, $guarantor2, $reason, $product_id, $officer_id, $date){
	$resp = new xajaxResponse();
	/*if($_SESSION['position'] <>'Manager'){
		$resp->alert("Access Denied! \nOnly the Manager can manage account");
		return $resp;
	}*/
	list($year,$month,$mday) = explode('-', $date);
	$calc = new Date_Calc();
	if($residence=='' || $telno=='' || $address=='' || $income1=='' || $income2=='' || $amount=='')
		$resp->alert("You may not leave any field blank");
	elseif(!$calc->isValidDate($mday, $month, $year))
		$resp->alert("Update rejected! Please enter valid date");
	elseif($calc->isFutureDate($mday, $month, $year))
		$resp->alert("Update rejected! You have entered a future date");
	else{
		$sth = mysql_query("select * from disbursed where applic_id='".$applic_id."'");
		if(@ mysql_numrows($sth) >0){
			$resp->alert("Update rejected! The loan has been disbursed already");
		}else{
			$resp->confirmCommands(1, "Do you really want to update?");
			$resp->call('xajax_update2_applic', $applic_id, $status, $residence, $telno, $address, $income1, $income2, $amount, $guarantor1, $guarantor2, $reason, $product_id, $officer_id, $date);
		}
	}
	return $resp;
}

//CONFIRM  UPDATE OF APPLIC
function update2_applic($applic_id, $status, $residence, $telno, $address, $income1, $income2, $amount, $guarantor1, $guarantor2, $reason, $product_id, $officer_id, $date){
	$resp = new xajaxResponse();
	//$date = sprintf("%d-%02d-%02d", $date);
	mysql_query("update loan_applic set marital_status='".$status."', residence='".$residence."', telno='".$telno."', postal_address='".$address."', income1='".$income1."', income2='".$income2."', amount='".$amount."', guarantor1='".$guarantor1."', guarantor2='".$guarantor2."', reason='".$reason."', product_id='".$product_id."', officer_id='".$officer_id."', date='".$date."' where id='".$applic_id."'");

	//////////////////////////
	$accno = mysql_fetch_assoc(mysql_query("select m.mem_no,m.first_name,m.last_name from member m inner join loan_applic la on la.mem_id=m.id where la.id=".$applic_id));
	$action = "update loan_applic set marital_status='".$status."', residence='".$residence."', telno='".$telno."', postal_address='".$address."', income1='".$income1."', income2='".$income2."', amount='".$amount."', guarantor1='".$guarantor1."', guarantor2='".$guarantor2."', reason='".$reason."', product_id='".$product_id."', officer_id='".$officer_id."', date='".$date."' where id='".$applic_id."'";
	$msg = "Edited a loan application set amount to: ".number_format($amount,2)."for customer: ".$accno['first_name']." ".$accno['last_name']." - ".$accno['mem_no'];
	log_action($_SESSION['user_id'],$action,$msg);
	////////////////////
	$resp->assign("status", "innerHTML", "<font color=red>Update done!</font>");
	return $resp;
}

//DELETE FROM DB
function delete_applic($applic_id){
	$resp = new xajaxResponse();
	/*if($_SESSION['position'] <>'Manager'){
		$resp->alert("Access Denied! \nOnly the Manager can manage account");
		return $resp;
	}*/
	$sth = mysql_query("select * from disbursed where applic_id='".$applic_id."'");
	if(@ mysql_numrows($sth) >0){
		$resp->alert("Cant delete this application! The loan has already been disbursed");
	}else{
		$resp->confirmCommands(1, "Do you really want to delete?");
		$resp->call('xajax_delete2_applic', $applic_id);
	}
	return $resp;
}

//CONFIRM DELETION
function delete2_applic($applic_id){
	$resp = new xajaxResponse();
	mysql_query("delete from loan_applic where id='".$applic_id."'");
	///////////////////
	$action = "delete from loan_applic where id='".$applic_id."'";
	$msg = "Deleted loan application for customer: ".$accno['first_name']." ".$accno['last_name']." - ".$accno['mem_no'];
	log_action($_SESSION['user_id'],$action,$msg);
	//////////////////
	
	$resp->assign("status", "innerHTML", "<font color=red>Application deleted!</font>");
	return $resp;
}

//DISBURSE LOAN
function add_disbursed($applic_id, $amount, $possible_amt, $based_on, $branch_id){
	$resp = new xajaxResponse();
	if($amount > $possible_amt){
		if($based_on == 'savings')
			$resp->alert("The member has insuficient savings for this loan");
		else
			$resp->alert("Disbursement rejected! \nThe member has insuficient shares for this loan");
		return $resp;
	}
	
	$applic_res = mysql_query("select p.int_rate as int_rate, p.writeoff_period as writeoff_period, p.int_method as int_method, p.grace_period as grace_period, p.arrears_period as arrears_period, p.loan_period as loan_period, p.id as product_id,  m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no,  ap.amount as amount,  a.account_no as account_no, a.name as account_name from loan_applic ap join member m on ap.mem_id=m.id join loan_product p on ap.product_id=p.id join accounts a on p.account_id=a.id where ap.id='".$applic_id."'");
	
	$applic = mysql_fetch_array($applic_res);

	$mem_name = $applic['first_name']." ".$applic['last_name'];
	
	
	$content = '<form class="panel form-horizontal form-bordered">
		<div class="panel-body pt0 pb0">';
$content .= '<div class="form-group header bgcolor-default">
                                       <div class="col-md-12">
                                       
                                             <p><h3 class="semibold text-primary mt0 mb5">DISBURSE LOAN</h3></p>';
            
 $content .= '<p><h5>MEMBER NAME:&nbsp;<font color="#00BFFF">'.$mem_name.'</font></h5></p>
 <p><h5>MEMBER No:&nbsp;<font color="#00BFFF">'.$applic['mem_no'].'</font></h5></p>    
 <p><h5>LOAN PRODUCT:&nbsp;<font color="#00BFFF">'.$applic['account_no']." - ".$applic['account_name'].'</font></h5><input type=hidden value="'.$applic['product_id'].'" id="product_id"></p> 
 <p><h5>AMOUNT:&nbsp;<font color="#00BFFF">'.$applic['amount'].'</font></h5></p>                                                                                                                                                                                                                                  
                                            </div> </div>';                                           
                                                                                     					
	             $content .= '<div class="form-group">
	                                   
	                                   <div class="col-sm-6">
                                            <label class="control-label">Cheque/Loan No:</label>
                                          <input type=text  id="cheque_no" class="form-control">
                                            </div>
                                            
                                           <div class="col-sm-6">
                                            <label class="control-label">Phone No:</label>
                                            <input type="text" id="applic_telno" value="'.$former['telno'].'" class="form-control">
                                            </div>
                                            </div>';
                                            
                     $content .= '<div class="form-group">
                                           
	                                   <div class="col-sm-6">
                                            <label class="control-label">Annual Interest Rate (%):</label>
                                          <input type=numeric(6,3) value="'.$applic['int_rate'].'" id="int_rate" class="form-control">
                                            </div>
                                            
                                            <div class="col-sm-6">                      
                                            <label class="control-label">Annual Penalty Rate (%):</label>
                                           <input type=numeric(6,3) value="'.$applic['penalty_rate'].'" id="penalty_rate" class="form-control">
                                            </div></div>';
                                            
                     $content .= '<div class="form-group">
                                            
	                                   <div class="col-sm-6">
                                            <label class="control-label">Method:</label>
                                           <select  id="int_method" class="form-control"><option value="'.$applic['int_method'].'">'.$applic['int_method'].'<option value="Declining Balance">Declining Balance<option value="Flat">Flat<option value="Armotised">Armotised</select>
                                            </div>
                                            
                                           <div class="col-sm-6"> 
                                            <label class="control-label">Grace Period(Months):</label>
                                           <input type=int value="'.($applic['grace_period']/30).'" id="grace_period" class="form-control">
                                           </div></div>';
                                            
                  
                     $content .= '<div class="form-group">
                                           
	                                   <div class="col-sm-6">
                                            <label class="control-label">Arrears Period (Months):</label>
                                           
                                           <input type=int value="'.($applic['arrears_period']/30).'" id="arrears_period" class="form-control"></div>
	 				<div class="col-sm-6">
                                            <label class="control-label">Write-off Period(Months):</label>
                                            
                                           <input type=int value="'.($applic['writeoff_period']/30).'" id="writeoff_period"  class="form-control">
                                           </div></div>';
                                           
                     $content .= '<div class="form-group">
	                                   
	                                   <div class="col-sm-6">
                                            <label class="control-label">Loan Period(Months):</label>
                                          <input type=int value="'.($applic['loan_period']/30).'" id="loan_period" class="form-control">
                                            </div>
                                            
                                           <div class="col-sm-6">
                                            <label class="control-label">Re-Payment Frequency:</label>
                                            <select id="pay_freq" class="form-control"><option value="30">Monthly<option value="1">Daily<option value="7">Weekly<option value="15">2 Weeks<option value="60">2 Months<option value="90">Quarterly<option value="120">4 Months<option value="150">5 Months<option value="180">6 Months<option value="210">7 Months<option value="240"> 8 Months<option value="270">9 Months<option value="300">10 Months<option value="330">11 Months<option value="360">Annually</select>
                                            </div>
                                            </div>';
                                            
                     $content .= '<div class="form-group">
                                           
	                                   <div class="col-sm-6">
                                            <label class="control-label">Repayment Date of Month:</label>
                                         <select id="repay_date"  class="form-control">';
	for($i=28; $i >=1; $i--){
		$content .= "<option value='".$i."'>".$i;
	}
	$content .= '<option value=0>Any</select>
                                            </div>
                                            
                                            <div class="col-sm-6">                      
                                            <label class="control-label">Disbursement Date:</label>
                                           <input type="text" class="form-control" id="date" name="date" placeholder="date" />
                                            </div></div>';
                                            $resp->call("createDate","date");
                                            
        if ($based_on <> '')
	{
		if (strtolower($based_on) == 'savings')
		{
			$base .= "<option value='savings' selected>Savings</option>";
			$base .= "<option value='shares'>Shares</option>";
		}
		elseif (strtolower($based_on) == 'shares')
		{
			$base .= "<option value='savings'>Savings</option>";
			$base .= "<option value='shares' selected>Shares</option>";
		}
		$base .= "<option value=''>&nbsp;</option>";
	}
	else
	{
		$base .= "<option value=''>&nbsp;</option>";
		$base .= "<option value='savings'>Savings</option>";
		$base .= "<option value='shares'>Shares</option>";
	}
                                            
                     $content .= '<div class="form-group">
                                            
	                                   <div class="col-sm-6">
                                            <label class="control-label">Based on:</label>
                                           <select id="based_on" name="based_on" class="form-control">'.$base.'</select>
                                            </div>';
              // if (strtolower($_SESSION['position']) == 'manager')
		$bank_res = mysql_query("select a.account_no as account_no, a.name as account_name, b.id as id from bank_account b join accounts a on b.account_id=a.id order by a.name, a.account_no");
	/*else
		$bank_res = mysql_query("select a.account_no as account_no, a.name as account_name, b.id as id from bank_account b join accounts a on b.account_id=a.id where b.id in (select bank_account_id from user_account where user_id =".$_SESSION['user_id'].") order by a.name, a.account_no");*/                            
                                          $content .= '<div class="col-sm-6"> 
                                            <label class="control-label">Disbursement Bank Account:</label>
                                           <select id="bank_account_id" class="form-control"><option value="">';
	while($bank = mysql_fetch_array($bank_res)){
		$content .= "<option value='".$bank['id']."'>".$bank['account_no']. " - ".$bank['account_name'];
	}
	$content .= '</select>
                                           </div></div>';
                 $mem_res =mysql_query("select a.name as account_name, a.account_no as account_no, mem.id as memaccount_id from mem_accounts mem join savings_product p on mem.saveproduct_id=p.id join accounts a on p.account_id=a.id join member m on mem.mem_id=m.id where m.mem_no='".$applic['mem_no']."' and p.type='free' order by a.account_no");                           
                  
                     $content .= '<div class="form-group">
                                           
	                                   <div class="col-sm-6">
                                            <label class="control-label">Disbursement Method:</label>
                                           
                                          <select type=int id="disburse_method" class="form-control"><option value="0">Issue Cheque or Cash';
	while($mem = mysql_fetch_array($mem_res)){
		$content .= "<option value='".$mem['memaccount_id']."'>Credit Member's ".$mem['account_no']." - ".$mem['account_name'];
	}
	$content .='</select></div>
	 				<div class="col-sm-6">
                                            <label class="control-label">Automatic Payment from Savings:</label>
                                            
                                           <select type=int id="automatic" class="form-control"><option value="0">No<option value="1">Yes</select>
                                           </div></div>';
                                                                                      
                       $content .= "<div class='panel-footer'><button class='btn btn-default' onclick=\"xajax_add_disbursed('".$applic_id."', '".$amount."', '".$possible_amt."', '".$based_on."','".$branch_id."')\">Reset</button>&nbsp;<button class='btn btn-primary' onclick=\"xajax_insert_disbursed('".$applic_id."', getElementById('product_id').value, '".$applic['amount']."', getElementById('cheque_no').value, getElementById('int_rate').value, getElementById('penalty_rate').value, getElementById('int_method').value,  getElementById('grace_period').value,  getElementById('writeoff_period').value, getElementById('arrears_period').value, getElementById('loan_period').value,  getElementById('date').value,getElementById('based_on').value,  getElementById('bank_account_id').value, getElementById('automatic').value, getElementById('disburse_method').value,'".$branch_id."', getElementById('pay_freq').value, getElementById('repay_date').value);return false;\">Save</button>";
 
     $content .= '</div></div></form>';
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//RETURNS THIS DATE IF IT IS A WORKING DAY, OR, IT WILL RETURN THE NEXT WORKING DAY 
function not_holiday($date){   
	$calc = new Date_Calc();
	$mday = date('d', strtotime($date));
	$month = date('m', strtotime($date));
	$year = date('Y', strtotime($date));

	if($calc->getWeekdayAbbrname($date, 3)=='Sun' || $calc->getWeekdayAbbrname($mday, $month, $year, 3)=='Sat'){
		$work_date = $calc->nextWeekday($date, '%Y-%m-%d');
		$sth = mysql_query("select * from holidays where date like '".$work_date."'");
		if(@ mysql_numrows($sth) > 0)
			$work_date = not_holiday($work_date);
	}else
		$work_date = $date;
	return $work_date;
}


//CALCULATE PAYMENT FOR THE ARMOTISED INTEREST METHOD
function calc_payment($pv, $payno, $int, $accuracy) 
{ 
// now do the calculation using this formula: 

//****************************************** 
//            INT * ((1 + INT) ** PAYNO) 
// PMT = PV * -------------------------- 
//             ((1 + INT) ** PAYNO) - 1 
//****************************************** 

$int    = $int / 100;    // convert to a percentage 
$value1 = $int * pow((1 + $int), $payno); 
$value2 = pow((1 + $int), $payno) - 1; 
$value2 = ($value2 == 0) ? 1 : $value2;
$pmt    = $pv * ($value1 / $value2); 
// $accuracy specifies the number of decimal places required in the result 
$pmt    = number_format($pmt, $accuracy, ".", ""); 

return $pmt; 

} 

function print_schedule($balance, $rate, $payment) 
{ 
$count = 0; 
do { 
   $count++; 

   // calculate interest on outstanding balance 
   $interest = $balance * $rate/100; 

   // what portion of payment applies to principal? 
   $principal = $payment - $interest; 

   // watch out for balance < payment 
   if ($balance < $payment) { 
      $principal = $balance; 
      $payment   = $interest + $principal; 
   } // if 

   // reduce balance by principal paid 
   $balance = $balance - $principal; 

   // watch for rounding error that leaves a tiny balance 
   if ($balance < 0) { 
      $principal = $principal + $balance; 
      $interest  = $interest - $balance; 
      $balance   = 0; 
   } // if 
/*
   echo "<tr>"; 
   echo "<td>$count</td>"; 
   echo "<td>" .number_format($payment,   2, ".", ",") ."</td>"; 
   echo "<td>" .number_format($interest,  2, ".", ",") ."</td>"; 
   echo "<td>" .number_format($principal, 2, ".", ",") ."</td>"; 
   echo "<td>" .number_format($balance,   2, ".", ",") ."</td>"; 
   echo "</tr>"; 
*/
   @$totPayment   = $totPayment + $payment; 
   @$totInterest  = $totInterest + $interest; 
   @$totPrincipal = $totPrincipal + $principal; 

   if ($payment < $interest) { 
      echo "</table>"; 
      echo "<p>Payment< Interest amount - rate is too high, or payment is too low</p>"; 
      exit; 
   } // if 

} while ($balance > 0);

}


//INSERT DISBURSEMENT 
function insert_disbursed($applic_id, $product_id, $amount, $cheque_no, $int_rate, $penalty_rate, $int_method,  $grace_period, $writeoff_period, $arrears_period, $loan_period, $date, $based_on, $bank_account_id, $automatic, $disburse_method,$branch_id, $pay_freq, $repay_date){

list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	if($int_rate =='' || $int_method=='' || $grace_period=='' || $arrears_period=='' || $writeoff_period=='' || $loan_period=='' || $bank_account_id=='' || $penalty_rate==''){
		$resp->alert("You may not leave any field blank");
		return $resp;
		}
	elseif(!$calc->isValidDate($mday, $month, $year)){
		$resp->alert("Disbursement rejected! Please enter valid date");
		return $resp;
		}
	elseif($calc->isFutureDate($mday, $month, $year)){
		$resp->alert("Disbursement rejected! You have entered a future date");
		return $resp;
		}
	elseif($pay_freq > ($loan_period*30)){
		$resp->alert("Disbursement rejected!\n Repayment frequency cannot be more than the loan period");
		return $resp;
		}
	//elseif(($loan_period*30) % $pay_freq != 0)
	//	$resp->call("Disbursement rejected!\n Repayment frequency must be divisible by the loan period");
	else{
		$sth = mysql_query("select * from loan_applic where id='".$applic_id."'");
		$row = mysql_fetch_array($sth);
		$branch_id_insert = $row['branch_id'];
		$date = sprintf("%d-%02d-%02d", $year, $month, $mday);
		$date = $date." ".date('H:i:s');
		if( $row['date'] > $date){
			$resp->alert("Disbursement rejected! \nDate entered is earlier than the date of the application");
			return $resp;
		}
		$sth = mysql_query("select * from disbursed where applic_id='".$applic_id."'");
		if(@ mysql_numrows($sth) > 0){
			$resp->alert("This loan has already been disbursed");
			return $resp;
		}
		$sth = mysql_query("select * from disbursed where cheque_no='".$cheque_no."'");
		if(@ mysql_numrows($sth) > 0){
			$resp->alert("Disbursement rejected! \n Cheque No already exists");
			return $resp;
		}
		$limit_res = mysql_query("select * from branch where branch_no='".$branch_id_insert."'");
		if(mysql_num_rows($limit_res)>0)
		$limit = mysql_fetch_array($limit_res);
		else
			$limit = mysql_fetch_assoc(mysql_query("select * from branch limit 1"));
		$prod_res = mysql_query("select * from loan_product where id='".$product_id."'");
		$prod = mysql_fetch_array($prod_res);
	//	$based_on = $prod['based_on'];

		$mem_id = $row['mem_id'];
		$applic_res = mysql_query("select * from loan_applic where id=$applic_id");
		$applic = mysql_fetch_array($applic_res);
		//Number of loans disbursed already
		$already_res = mysql_query("select count(d.id) as no from disbursed d join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$mem_id."'");
		$already = @mysql_fetch_array($already_res);
		$already_no = ($already['no'] == NULL) ? 1 : $already['no']+1;
		$guarantor1 = $applic['guarantor1'];
		$guarantor2 = $applic['guarantor2'];
		if($based_on == 'savings'){
			$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.type='free' and m.mem_id='".$mem_id."'");
			
			$dep = mysql_fetch_array($dep_res);
			$dep_amt = ($dep['amount'] == NULL) ? 0 : $dep['amount'];

			$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.type='free' and m.mem_id='".$mem_id."'");
			$with = mysql_fetch_array($with_res);
			$with_amt = ($with['amount'] == NULL) ? 0 : $with['amount'];

			$pledged_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where a.name ='Compulsory Savings' and mem.mem_id='".$mem_id."'");
			$pledged = @mysql_fetch_array($pledged_res);
			$pledged_amt = ($pledged['amount'] == NULL) ? 0 : $pledged['amount'];
			
			//INTEREST AWARDED
			$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id where m.mem_id=".$mem_id."");
			$int =  mysql_fetch_array($int_res);
			$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;

			//MONTHLY CHARGE
			$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts m on c.memaccount_id=m.id where m.mem_id='".$mem_id."'");
			$charge = mysql_fetch_array($charge_res);
			$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;

			//LOAN REPAYMENTS
			$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id where m.mem_id='".$row['mem_id']."'");
			$pay = mysql_fetch_array($pay_res);
			$pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;

			//INCOME DEDUCTIONS
			$inc_res = mysql_query("select sum(i.amount) as amount from other_income i join mem_accounts m on i.mode=m.id where m.mem_id='".$row['mem_id']."'");
			$inc = mysql_fetch_array($inc_res);
			$inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;

			$balance = $dep_amt + $int_amt - $with_amt - $pledged_amt - $charge_amt - $pay_amt - $inc_amt;
			//$balance = $dep_amt + $int_amt - $with_amt - $pledged_amt - $charge_amt;
			$possible = ($balance * $limit['loan_save_percent']) / 100;
			if($possible < $amount && $limit['loan_save_percent'] >0){
				$resp->alert("Disbursement rejected! \nMember has insufficient compulsory savings. \n Only ".$possible." is possible");
				return $resp;
			}
			

			//GUARANTOR 1'S CREDIBILITY
			$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.type='free' and m.mem_id='".$guarantor1."'");
			$dep = mysql_fetch_array($dep_res);
			$dep_amt = ($dep['amount'] == NULL) ? 0 : $dep['amount'];

			$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.type='free' and m.mem_id='".$guarantor1."'");
			$with = mysql_fetch_array($with_res);
			$with_amt = ($with['amount'] == NULL) ? 0 : $with['amount'];

			$pledged_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where a.name='Compulsory Savings' and mem.mem_id='".$guarantor1."'");
			$pledged = @mysql_fetch_array($pledged_res);
			$pledged_amt = ($pledged['amount'] == NULL) ? 0 : $pledged['amount'];
			
			//INTEREST AWARDED
			$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id where m.mem_id=".$guarantor1."");
			$int =  mysql_fetch_array($int_res);
			$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;

			//MONTHLY CHARGE
			$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts m on c.memaccount_id=m.id where m.mem_id='".$guarantor1."'");
			$charge = mysql_fetch_array($charge_res);
			$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;

			//LOAN REPAYMENTS
			$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id where m.mem_id='".$row['mem_id']."'");
			$pay = mysql_fetch_array($pay_res);
			$pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;

			//INCOME DEDUCTIONS
			$inc_res = mysql_query("select sum(i.amount) as amount from other_income i join mem_accounts m on i.mode=m.id where m.mem_id='".$row['mem_id']."'");
			$inc = mysql_fetch_array($inc_res);
			$inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;

			$balance = $dep_amt + $int_amt - $with_amt - $pledged_amt - $charge_amt - $pay_amt - $inc_amt;

			//$balance = $dep_amt + $int_amt - $with_amt - $pledged_amt - $charge_amt;
			$possible = ($balance * $limit['guarantor_save_percent']) / 100;
			if($possible < $amount && $limit['guarantor_save_percent'] >0){
				$resp->alert("Disbursement rejected! \nGuarantor 1 can only guarantee ".$possible." \n based on his Savings.");
				return $resp;
			}
			//GUARANTOR 2'S CREDIBILITY
			$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.type='free' and m.mem_id='".$guarantor2."'");
			$dep = mysql_fetch_array($dep_res);
			$dep_amt = ($dep['amount'] == NULL) ? 0 : $dep['amount'];

			$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.type='free' and m.mem_id='".$guarantor2."'");
			$with = mysql_fetch_array($with_res);
			$with_amt = ($with['amount'] == NULL) ? 0 : $with['amount'];

			$pledged_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where a.name='Compulsory Savings' and mem.mem_id='".$guarantor2."'");
			$pledged = @mysql_fetch_array($pledged_res);
			$pledged_amt = ($pledged['amount'] == NULL) ? 0 : $pledged['amount'];
			
			//INTEREST AWARDED
			$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id where m.mem_id=".$guarantor2."");
			$int =  mysql_fetch_array($int_res);
			$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;

			//MONTHLY CHARGE
			$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts m on c.memaccount_id=m.id where m.mem_id='".$guarantor2."'");
			$charge = mysql_fetch_array($charge_res);
			$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;

			//LOAN REPAYMENTS
			$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id where m.mem_id='".$row['mem_id']."'");
			$pay = mysql_fetch_array($pay_res);
			$pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;

			//INCOME DEDUCTIONS
			$inc_res = mysql_query("select sum(i.amount) as amount from other_income i join mem_accounts m on i.mode=m.id where m.mem_id='".$row['mem_id']."'");
			$inc = mysql_fetch_array($inc_res);
			$inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;

			$balance = $dep_amt + $int_amt - $with_amt - $pledged_amt - $charge_amt - $pay_amt - $inc_amt;

			//$balance = $dep_amt + $int_amt - $with_amt - $pledged_amt - $charge_amt;
			$possible = ($balance * $limit['guarantor_save_percent']) / 100;
			if($possible < $amount && $limit['guarantor_save_percent'] >0){
				$resp->alert("Disbursement rejected! \nGuarantor 2 can only guarantee ".$possible." \nbased on his Savings.");
				return $resp;
			}
		}elseif($bassed_on == 'shares'){
			$shares_res = mysql_query("select sum(value) as value from shares where mem_id='".$mem_id."'");
			$shares = @mysql_fetch_array($shares_res);
			$shares_amt = ($shares['value'] == NULL) ? 0 : $shares['amount']; 
			
			$donated_res = mysql_query("select sum(value) as value from share_transfer where to_id='".$mem_id."'");
			$donated = @mysql_fetch_array($donated_res);
			$donated_amt = ($donated['value'] == NULL) ? 0 : $donated['value'];

			$trans_res = mysql_query("select sum(value) as value from share_transfer where from_id='".$mem_id."'");
			$trans = @mysql_fetch_array($trans_res);
			$trans_amt = ($trans['value'] == NULL) ? 0 : $trans['value'];

			$pledged_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on m.saveproduct_id=s.id join accounts a on s.account_id=a.id where a.name='Compulsory Shares' and mem.mem_id='".$mem_id."'");
			$pledged = @mysql_fetch_array($pledged_res);
			$pledged_amt = ($pledged['amount'] == NULL) ? 0 : $pledged['amount'];

			$balance = $shares_amt + $donated_amt - $trans_amt - $pledged_amt;

			$possible = ($balance * $limit['loan_share_percent']) / 100;
			if($amount > $possible && $limit['loan_share_percent'] >0){
				$resp->alert("Disbursement rejected! \n Customer has insufficient shares for this Loan. \n Only ".$possible." is possible");
				return $resp;
			}
			//GUARANTOR 1'S CREDIBILITY
			$shares_res = mysql_query("select sum(value) as value from shares where mem_id='".$guarantor1."'");
			$shares = @mysql_fetch_array($shares_res);
			$shares_amt = ($shares['value'] == NULL) ? 0 : $shares['amount']; 
			
			$donated_res = mysql_query("select sum(value) as value from share_transfer where to_id='".$guarantor1."'");
			$donated = @mysql_fetch_array($donated_res);
			$donated_amt = ($donated['value'] == NULL) ? 0 : $donated['value'];

			$trans_res = mysql_query("select sum(value) as value from share_transfer where from_id='".$guarantor1."'");
			$trans = @mysql_fetch_array($trans_res);
			$trans_amt = ($trans['value'] == NULL) ? 0 : $trans['value'];

			$pledged_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on m.saveproduct_id=s.id join accounts a on s.account_id=a.id where a.name='Compulsory Shares' and mem.mem_id='".$guarantor1."'");
			$pledged = @mysql_fetch_array($pledged_res);
			$pledged_amt = ($pledged['amount'] == NULL) ? 0 : $pledged['amount'];

			$balance = $shares_amt + $donated_amt - $trans_amt - $pledged_amt;

			$possible = ($balance * $limit['guarantor_share_percent']) / 100;
			if($amount > $possible && $limit['guarantor_share_percent'] >0){
				$resp->alert("Disbursement rejected! \n Guarantor 1 has insufficient shares for this Loan. \n Only ".$possible." is possible");
				return $resp;
			}

			//GUARANTOR 2'S CREDIBILITY
			$shares_res = mysql_query("select sum(value) as value from shares where mem_id='".$guarantor2."'");
			$shares = @mysql_fetch_array($shares_res);
			$shares_amt = ($shares['value'] == NULL) ? 0 : $shares['amount']; 
			
			$donated_res = mysql_query("select sum(value) as value from share_transfer where to_id='".$guarantor2."'");
			$donated = @mysql_fetch_array($donated_res);
			$donated_amt = ($donated['value'] == NULL) ? 0 : $donated['value'];

			$trans_res = mysql_query("select sum(value) as value from share_transfer where from_id='".$guarantor2."'");
			$trans = @mysql_fetch_array($trans_res);
			$trans_amt = ($trans['value'] == NULL) ? 0 : $trans['value'];

			$pledged_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on m.saveproduct_id=s.id join accounts a on s.account_id=a.id where a.name='Compulsory Shares' and mem.mem_id='".$guarantor2."'");
			$pledged = @mysql_fetch_array($pledged_res);
			$pledged_amt = ($pledged['amount'] == NULL) ? 0 : $pledged['amount'];

			$balance = $shares_amt + $donated_amt - $trans_amt - $pledged_amt;

			$possible = ($balance * $limit['guarantor_share_percent']) / 100;
			if($amount > $possible && $limit['guarantor_share_percent'] >0){
				$resp->alert("Disbursement rejected! \n Guarantor 2 has insufficient shares for this Loan. \n Only ".$possible." is possible");
				return $resp;
			}
		}

		//CHECK THAT THE BANK ACCOUNT WOULD NOT GO BELOW MINIMUM
		$sth = mysql_query("select * from bank_account where id='".$bank_account_id."'");
		$row = mysql_fetch_array($sth);
		/* 
		if($row['account_balance'] - $amount < $row['min_balance']){
			$resp->call("Disbusement rejected! \n The disbursement would go below the minimum balance");
			return $resp;
		}
		*/
		$date= $calc->dateFormat($mday, $month, $year, '%Y-%m-%d');
		$date = $date." ".date('H:i:s');
		start_trans();
		//UPDATE THE DISBURSEMENT BANK ACCOUNT
		if($disburse_method ==0){
			if(! mysql_query("update bank_account set account_balance=account_balance-".$amount." where id=".$bank_account_id."")){
				$resp->alert("ERROR: Disbursement rejected! \n Could not update bank account balance");
				rollback();
				return $resp;
			}
		}
		
		if( ! mysql_query("insert into disbursed set applic_id='".$applic_id."', amount='".$amount."', balance='".$amount."', grace_period='".($grace_period*30)."', int_method='".$int_method."', cheque_no='".$cheque_no."', int_rate='".$int_rate."', penalty_rate='".$penalty_rate."', arrears_period='".($arrears_period* 30)."', writeoff_period='".($writeoff_period*30)."', bank_account='".$bank_account_id."', period='".($loan_period*30)."', date='".$date."', last_pay_date='".$date."', loan_save_percent='".$limit['loan_save_percent']."', loan_share_percent='".$limit['loan_share_percent']."', guarantor_save_percent='".$limit['guarantor_save_percent']."', guarantor_share_percent='".$limit['guarantor_share_percent']."', cycle='".$already_no."', automatic='".$automatic."', branch_id='".$branch_id_insert."', repay_date='".$repay_date."'")){
			$resp->alert("ERROR: Disbursement rejected! \n Could not insert the disbursement".mysql_error());
			rollback();
			return $resp;
		}	
		$loan_res = mysql_query("select last_insert_id() as last_id");
		$loan = mysql_fetch_array($loan_res);
		//DISBURSE METHOD
		if($disburse_method > 0){
			if(! mysql_query("insert into deposit set amount='$amount', receipt_no='LOAN', cheque_no='LOAN', date='$date', bank_account='$bank_account_id', memaccount_id='$disburse_method', trans_date='$date',branch_id='".$branch_id_insert."'")){
				$resp->alert("ERROR: Disbursement rejected! \n Could not credit the savings");
				rollback();
				return $resp;
			}
			$dep_res = mysql_query("select last_insert_id() as last_id");
			$dep = mysql_fetch_array($dep_res);
			$deposit_id = $dep['last_id'];
			if(! mysql_query("update disbursed set deposit_id='$deposit_id' where id= '".$loan['last_id']."'")){
				$resp->alert("ERROR: Disbursement rejected! \n Could not credit the savings");
				rollback();
				return $resp;
			}
		}else
			$deposit_id=0;

		$instalments = ($loan_period * 30) / $pay_freq;
		$portion = ceil($amount / $instalments);
		$monthly_int_rate = ($int_rate * $pay_freq) /365;
		if($int_method == 'Armotised')
			$pmt = calc_payment($amount, $instalments, $monthly_int_rate, 2);
		
		if($pay_freq < 30)
			$int_amt = ceil((($amount * $int_rate * $pay_freq) /100) / 365);   //INTEREST FOR THE FIXED METHOD OF INTEREST CALCULATION
		else
			$int_amt = ceil(($amount * ($int_rate/12)/100)/ $instalments) * $loan_period;
		//$int_amt = sprintf("%.02f", $int_amt);
		$begin_bal=$amount;
		$end_bal=$amount;
		$next_days = $calc->dateToDays($mday, $month, $year) + ($grace_period * $pay_freq) + $pay_freq;
		$total_int =0;
		for($i=0; $i <= $instalments && $begin_bal >0; $i++){
			if($int_method == 'Declining Balance')      //INTEREST FOR THE REDUCING BALANCE METHOD OF INTEREST CALCULATION
				$int_amt = ($pay_freq >=15) ? ceil((($begin_bal * $int_rate * ($pay_freq/30)) /100) / 12) : ceil((($begin_bal * $int_rate * $pay_freq) /100) / 365);
			elseif($int_method == 'Armotised'){
				// calculate interest on outstanding balance 
				$int_amt = $begin_bal * $monthly_int_rate/100; 

				// what portion of payment applies to principal? 
				$portion = $pmt - $int_amt; 
			
				// watch out for balance < pmt 
				if ($begin_bal < $pmt) { 
					$portion = $begin_bal; 
					$pmt   = $int_amt + $portion; 
				} // if 

				// reduce balance by principal paid 
				//$begin_bal = $begin_bal - $portion; 

				// watch for rounding error that leaves a tiny balance 
				/* if ($begin_bal < 0) { 
					$portion = $portion + $begin_bal; 
					$int_amt  = $int_amt - $begin_bal; 
					$begin_bal   = 0; 
				} */ // if 
			}
			$portion = ($portion == 0) ? $amount / ($instalments) : $portion;
			$end_bal -= $portion;
			$total_int += $int_amt;
			
			
			if($pay_freq >= 30 && $repay_date>0){	
				//$pay_date = $calc->endOfNextMonth($calc->daysToDate(($next_days - $pay_freq), '%d'), $calc->daysToDate(($next_days-$pay_freq), '%m'), $calc->daysToDate(($next_days - $pay_freq), '%Y'), '%Y-%m');
				preg_match("/(\d+)-(\d+)-(\d+)/", $pay_date, $arg);
				$pay_date = ($pay_date=='') ? $calc->endOfNextMonth($calc->daysToDate(($next_days - $pay_freq), '%d'), $calc->daysToDate(($next_days-$pay_freq), '%m'), $calc->daysToDate(($next_days - $pay_freq), '%Y'), '%Y-%m') :  $calc->endOfNextMonth( $arg[3], $arg[2], $arg[1], '%Y-%m');

				$pay_date = $pay_date."-".$repay_date;
			}else{
				$pay_date = $calc->daysToDate($next_days, '%Y-%m-%d');
				$pay_date = not_holiday($pay_date);   //check whether this is a holiday	

			}
			if($i == 0){
				$scheduled_pay = $int_amt + $portion;
				$last_pay_date = $calc->daysToDate(($next_days - $pay_freq), '%Y-%m-%d');
				if(! mysql_query("update disbursed set next_princ_amt='".$portion."', next_int_amt='".$int_amt."', next_pay_date='".$pay_date."', last_pay_date='".$last_pay_date."', deposit_id='$deposit_id' where id='".$last['last_id']."'")){
					$resp->alert("ERROR: Disbursement rejected! \n Could not schedule the next payment");
					rollback();
					return $resp;
				}
			}
			
			if(! mysql_query("insert into schedule set loan_id='".$loan['last_id']."', princ_amt='".$portion."', int_amt='".$int_amt."', date='".$pay_date."', begin_bal='".$begin_bal."', end_bal='".$end_bal."',branch_id=".$branch_id_insert)){
				$resp->alert("ERROR: Disbursement rejected! \n Could not schedule the repayment");
				rollback();
				return $resp;
			}
			//if($int_method != 'Armotised')
				$begin_bal -= $portion;
			$next_days += $pay_freq;
		}
		//CREDIT COMPULSORY SAVINGS
		$base_res = mysql_query("select * from loan_product where id='".$product_id."'");
		$base = mysql_fetch_array($base_res);
		if($base['based_on'] == 'savings'){   //LOAN GUARANTEED BY SAVINGS
			$post_res = mysql_query("select p.* from savings_product p join accounts a on p.account_id=a.id where a.name='Compulsory Savings'");
			$pledge_res = mysql_query("select m.* from mem_accounts m join savings_product p on m.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.name='Compulsory Savings' and m.mem_id=$mem_id");
			$pledge1_res = mysql_query("select m.* from mem_accounts m join savings_product p on m.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.name='Compulsory Savings' and m.mem_id=$guarantor1");
			$pledge2_res = mysql_query("select m.* from mem_accounts m join savings_product p on m.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.name='Compulsory Savings' and m.mem_id=$guarantor2");
			$guaranteed_amt = ($limit['guarantor_save_percent'] == 0) ? 0 : ($amount * 100)/$limit['guarantor_save_percent'];
			$comp_amt =  ($limit['loan_save_percent'] == 0) ? 0 : ($amount * 100)/$limit['loan_save_percent'];
			$class = "Compulsory Savings";
		}else{  //LOAN GUARANTEED BY SHARES
			$post_res = mysql_query("select p.* from savings_product p join accounts a on p.account_id=a.id where a.name='Compulsory Shares'");
			$pledge_res = mysql_query("select m.* from mem_accounts m join savings_product p on m.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.name='Compulsory Shares' and m.mem_id=$mem_id");
			$pledge1_res = mysql_query("select m.* from mem_accounts m join savings_product p on m.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.name='Compulsory Shares' and m.mem_id=$guarantor1");
			$pledge2_res = mysql_query("select m.* from mem_accounts m join savings_product p on m.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.name='Compulsory Shares' and m.mem_id=$guarantor2");
			$guaranteed_amt = ($limit['guarantor_share_percent'] == 0) ? 0 : ($amount * 100)/$limit['guarantor_share_percent'];
			$comp_amt = ($limit['loan_share_percent'] == 0) ? 0 : ($amount * 100)/$limit['loan_share_percent'];
			$class = "Compulsory Shares";
		}
		//FOR BORROWER
		$post = mysql_fetch_array($post_res);
		if(@ mysql_numrows($pledge_res) > 0){
			$pledge = mysql_fetch_array($pledge_res);
			if(! mysql_query("update deposit set amount=amount +".$comp_amt. " where memaccount_id=".$pledge['id'])){
				$resp->alert("ERROR: Disbursement rejected! \n Could not update ".$class);
				rollback();
				return $resp;
			}
		}else{
			if(! mysql_query("insert into mem_accounts set mem_id='".$mem_id."', saveproduct_id='".$post['id']."', open_date=CURDATE(),branch_id=".$branch_id_insert)){
				$resp->alert("ERROR: Disbursement rejected! \n Could not open a ".$class." account for this member".mysql_error());
				rollback();
				return $resp;
			};
			$last_res = mysql_query("select last_insert_id() as last_id");
			$last = mysql_fetch_array($last_res);
			if(! mysql_query("insert into deposit set amount ='".$comp_amt."', memaccount_id='".$last['last_id']."', date='".$date."', branch_id=".$branch_id_insert)){
				$resp->alert("ERROR: Disbursement rejected! \n Could not update compulsory savings");
				rollback();
				return $resp;
			}
		}
		//FOR GUARANTOR 1
		if(@ mysql_numrows($pledge1_res) > 0){
			$pledge1 = mysql_fetch_array($pledge1_res);
			if(! mysql_query("update deposit set amount=amount +".$guaranteed_amt. " where memaccount_id=".$pledge1['id'])){
				$resp->alert("ERROR: Disbursement rejected! \n Could not update ".$class);
				rollback();
				return $resp;
			}
		}else{
			if(! mysql_query("insert into mem_accounts set mem_id='".$guarantor1."', saveproduct_id='".$post['id']."', open_date=CURDATE(),branch_id='".$branch_id_insert."'")){
				$resp->alert("ERROR: Disbursement rejected! \n Could not open a ".$class." account for guarantor 1");
				rollback();
				return $resp;
			};
			$last_res = mysql_query("select last_insert_id() as last_id");
			$last = mysql_fetch_array($last_res);
			if(! mysql_query("insert into deposit set amount ='".$guaranteed_amt."', memaccount_id='".$last['last_id']."', date='".$date."',branch_id='".$branch_id_insert."'")){
				$resp->alert("ERROR: Disbursement rejected! \n Could not update compulsory savings for Guarantor 1");
				rollback();
				return $resp;
			}
		}

		//FOR GUARANTOR 2
		if(@ mysql_numrows($pledge2_res) > 0){
			$pledge2 = mysql_fetch_array($pledge2_res);
			if(! mysql_query("update deposit set amount=amount +".$guaranteed_amt. " where memaccount_id=".$pledge2['id'])){
				$resp->alert("ERROR: Disbursement rejected! \n Could not update ".$class);
				rollback();
				return $resp;
			}
		}else{
			if(! mysql_query("insert into mem_accounts set mem_id='".$guarantor2."', saveproduct_id='".$post['id']."', open_date=CURDATE(),branch_id='".$branch_id_insert."'")){
				$resp->alert("ERROR: Disbursement rejected! \n Could not open a ".$class." account for Guarantor 2");
				rollback();
				return $resp;
			};
			$last_res = mysql_query("select last_insert_id() as last_id");
			$last = mysql_fetch_array($last_res);
			
			if(! mysql_query("insert into deposit set amount ='".$guaranteed_amt."', memaccount_id='".$last['last_id']."', date='".$date."',branch_id='".$branch_id_insert."'")){
				$resp->alert("ERROR: Disbursement rejected! \n Could not update ".$class."");
				rollback();
				return $resp;
			}
		}
		if($_SESSION['commit'] == 1){
			commit();
			//////////////////
			$accno = mysql_fetch_assoc(mysql_query("select m.mem_no,m.first_name,m.last_name from member m inner join loan_applic la on la.mem_id=m.id where la.id=".$applic_id));
			$action = "insert into disbursed set applic_id='".$applic_id."', amount='".$amount."', balance='".$amount."', grace_period='".($grace_period*30)."', int_method='".$int_method."', cheque_no='".$cheque_no."', int_rate='".$int_rate."', arrears_period='".($arrears_period* 30)."', writeoff_period='".($writeoff_period*30)."', bank_account='".$bank_account_id."', period='".($loan_period*30)."', date='".$date."', last_pay_date='".$date."', loan_save_percent='".$limit['loan_save_percent']."', loan_share_percent='".$limit['loan_share_percent']."', guarantor_save_percent='".$limit['guarantor_save_percent']."', guarantor_share_percent='".$limit['guarantor_share_percent']."', cycle='".$already_no."', automatic='".$automatic."'";
			$msg = "Registered a disbursement amount:".$amount." from custmer: ".$accno['first_name']." ".$accno['last_name']." - ".$accno['mem_no']; 
			log_action($_SESSION['user_id'],$action,$msg);
			/////////////////
		}       
			$sth = mysql_query("select * from loan_applic where id='".$applic_id."'");
		        $row = mysql_fetch_array($sth);
			//$phone= getItemById("member","'".$row[mem_id]."'","id","telno");
			$content = "<font color=red><font color=red>Loan disbursed!</font>";
			//$content .= libinc::sendSMS($phone, "The Loan application of ".$amount." at  ".$branch['branch_name']." Sacco has been disbursed!");
			$resp->assign("status", "innerHTML",$content);
		;
		
		//PRINT THE REPAYMENT SCHEDULE
		$sth = mysql_query("select date, begin_bal, end_bal, princ_amt, int_amt, (princ_amt + int_amt) as total_amt  from schedule where loan_id='".$loan['last_id']."' order by date asc");
		if(@ mysql_numrows($sth) > 0){
			$resp->assign("status", "innerHTML", "<font color=red>Disbursement done!</font>   <a href='export/schedule?loan_id=".$loan['last_id']."&applic_id=".$applic_id."&cust_name=".$cust_name."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&status=".$status."&format=excel'><b>Print Repayment Schedule</b></a>");
		}else
			$resp->alert("Loan not disbursed! The repayment schedule could not be printed!");
	}
	return $resp;
}

//EDIT DISBURSEMENT
function edit_cheque($id, $applic_id, $cust_name, $cust_no, $account_name, $loan_officer, $from_date, $to_date, $cheque_no,$branch_id){
	$resp = new xajaxResponse();
	/*if($_SESSION['position'] <>'Manager'){
		$resp->alert("Access Denied! \nOnly the Manager can edit an item");
		return $resp;
	}*/
		
	$former_res = mysql_query("select d.int_rate as int_rate, d.penalty_rate as penalty_rate, d.balance as balance, date_format(d.date, '%Y') as year, date_format(d.date, '%m') as month, date_format(d.date, '%d') as mday, d.writeoff_period as writeoff_period, d.int_method as int_method, d.grace_period as grace_period, d.cheque_no as cheque_no, d.arrears_period as arrears_period, d.period as loan_period, p.id as product_id,  d.bank_account as bank_account, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no,  ap.amount as amount,  a.account_no as account_no, a.name as account_name from disbursed d join loan_applic ap on d.applic_id=ap.id join member m on ap.mem_id=m.id join loan_product p on ap.product_id=p.id join accounts a on p.account_id=a.id where d.id='".$id."'");
	
	$former = mysql_fetch_array($former_res);
	$mem_name = $former['first_name']." ".$former['last_name'];
	$content = '<form class="panel form-horizontal form-bordered">
		<div class="panel-body pt0 pb0">';
$content .= '<div class="form-group header bgcolor-default">
                                       <div class="col-md-12">
                                       
                                             <p><h3 class="semibold text-primary mt0 mb5">EDIT CHEQUE No. OF DISBURSED LOAN</h3></p>';
            
 $content .= '<p><h5>MEMBER NAME:&nbsp;<font color="#00BFFF">'.$mem_name.'</font></h5></p>
 <p><h5>MEMBER No:&nbsp;<font color="#00BFFF">'.$former['mem_no'].'</font></h5></p>    
 <p><h5>LOAN PRODUCT:&nbsp;<font color="#00BFFF">'.$former['account_no']." - ".$former['account_name'].'</font></h5><input type=hidden value="'.$former['product_id'].'" id="product_id"></p> 
 <p><h5>AMOUNT:&nbsp;<font color="#00BFFF">'.$former['amount'].'</font></h5></p>                                                                                                                                                                                                                                  
                                            </div> </div>';                                           
                                                                                     					
	             $content .= '<div class="form-group">
	                                   
	                                   <div class="col-sm-6">
                                            <label class="control-label">Cheque/Loan No:</label>
                                          <input type=text  id="cheque_no"  value="'.$former['cheque_no'].'" class="form-control">
                                            </div></div>';
                                                                                                                        
                     $content .= '<div class="form-group">
                                                                                       
                                            <div class="col-sm-6">                      
                                            <label class="control-label">Annual Penalty Rate (%):</label>
                                           <input type=numeric(6,3) value="'.$former['penalty_rate'].'" id="penalty_rate" class="form-control">
                                            </div></div>';
                                                                                                                                              
                      $content .= "<div class='panel-footer'><button class='btn btn-default'onclick=\"xajax_list_disbursed('".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."','".$to_date."', '".$cheque_no."','".$branch_id."')\">Back</button>&nbsp;<button class='btn btn-primary' onclick=\"xajax_update_cheque('".$id."', getElementById('cheque_no').value, getElementById('penalty_rate').value, '".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."','".$to_date."');\">Update</button>";
 
     $content .= '</div></div></form>';

	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//
function update_cheque($loan_id, $cheque_no, $penalty_rate, $cust_name, $cust_no, $account_name, $loan_officer, $from_date,$to_date){
	$resp = new xajaxResponse();
	$resp->confirmCommands(1, "Do you really want to update chequeNo?");
	$resp->call('xajax_update2_cheque', $loan_id, $cheque_no, $penalty_rate);
	return $resp;
}

function update2_cheque($loan_id, $cheque_no, $penalty_rate){
	$resp = new xajaxResponse();
	if(!mysql_query("update disbursed set cheque_no='".$cheque_no."', penalty_rate='".$penalty_rate."' where id='".$loan_id."'"))
		$resp->assign("status", "innerHTML", "<font color=red>Error occured. ".mysql_error()."</font>");
	else{
		////////////
		$action = "update disbursed set cheque_no='".$cheque_no."', penalty_rate='".$penalty_rate."' where id='".$loan_id."'";
		$msg = "Edited a disbursement set cheque_no:". $cheque_no." for customer: ".$cust_name." - ".$cust_no;
		log_action($_SESSION['user_id'],$action,$msg);
	////////////
		$resp->assign("status", "innerHTML", "<font color=red>ChequeNo /Penalty rate updated!</font>");
	}
	return $resp;
}

//EDIT DISBURSEMENT
function edit_disbursed($id, $applic_id, $cust_name, $cust_no, $account_name, $loan_officer, $from_date,$to_date, $cheque_no,$branch_id){
	$resp = new xajaxResponse();
	/*if($_SESSION['position'] <>'Manager'){
		$resp->alert("Access Denied! \nOnly the Manager can edit an item");
		return $resp;
	}*/
	
	$former_res = mysql_query("select d.int_rate as int_rate, d.penalty_rate as penalty_rate, d.automatic as automatic, d.balance as balance, date_format(d.date, '%Y') as year, date_format(d.date, '%m') as month, date_format(d.date, '%d') as mday, d.writeoff_period as writeoff_period, d.int_method as int_method, d.grace_period as grace_period, d.cheque_no as cheque_no, d.arrears_period as arrears_period, d.period as loan_period, p.id as product_id,  d.bank_account as bank_account, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no,  ap.amount as amount,  a.account_no as account_no, a.name as account_name, d.branch_id from disbursed d join loan_applic ap on d.applic_id=ap.id join member m on ap.mem_id=m.id join loan_product p on ap.product_id=p.id join accounts a on p.account_id=a.id where d.id='".$id."'");
	
	$former = mysql_fetch_array($former_res);
	$auto = ($former['automatic'] == '1') ? "Yes" : "No";
	$mem_name = $former['first_name']." ".$former['last_name'];
	$content = '<form class="panel form-horizontal form-bordered">
		<div class="panel-body pt0 pb0">';
$content .= '<div class="form-group header bgcolor-default">
                                       <div class="col-md-12">
                                       
                                             <p><h3 class="semibold text-primary mt0 mb5">EDIT DISBURSEMENT</h3></p>';
            
 $content .= '<p><h5>MEMBER NAME:&nbsp;<font color="#00BFFF">'.$mem_name.'</font></h5></p>
 <p><h5>MEMBER No:&nbsp;<font color="#00BFFF">'.$former['mem_no'].'</font></h5></p>    
 <p><h5>LOAN PRODUCT:&nbsp;<font color="#00BFFF">'.$former['account_no']." - ".$former['account_name'].'</font></h5><input type=hidden value="'.$former['product_id'].'" id="product_id"></p> 
 <p><h5>AMOUNT:&nbsp;<font color="#00BFFF">'.$former['amount'].'</font></h5></p>                                                                                                                                                                                                                                  
                                            </div> </div>';                                           
                                                                                     					
	             $content .= '<div class="form-group">
	                                   
	                                   <div class="col-sm-6">
                                            <label class="control-label">Cheque/Loan No:</label>
                                          <input type=text  id="cheque_no"  value="'.$former['cheque_no'].'" class="form-control">
                                            </div>
                                            
                                           <div class="col-sm-6">
                                            <label class="control-label">Phone No:</label>
                                            <input type="text" id="applic_telno" value="'.$former['telno'].'" class="form-control">
                                            </div>
                                            </div>';
                                            
                     $content .= '<div class="form-group">
                                           
	                                   <div class="col-sm-6">
                                            <label class="control-label">Annual Interest Rate (%):</label>
                                          <input type=numeric(6,3) value="'.$former['int_rate'].'" id="int_rate" class="form-control">
                                            </div>
                                            
                                            <div class="col-sm-6">                      
                                            <label class="control-label">Annual Penalty Rate (%):</label>
                                           <input type=numeric(6,3) value="'.$former['penalty_rate'].'" id="penalty_rate" class="form-control">
                                            </div></div>';
                                            
                     $content .= '<div class="form-group">
                                            
	                                   <div class="col-sm-6">
                                            <label class="control-label">Method:</label>
                                           <select  id="int_method" class="form-control"><option value="'.$former['int_method'].'">'.$former['int_method'].'<option value="Declining Balance">Declining Balance<option value="Flat">Flat<option value="Armotised">Armotised</select>
                                            </div>
                                            
                                           <div class="col-sm-6"> 
                                            <label class="control-label">Grace Period(Months):</label>
                                           <input type=int value="'.($former['grace_period']/30).'" id="grace_period" class="form-control">
                                           </div></div>';
                                            
                  
                     $content .= '<div class="form-group">
                                           
	                                   <div class="col-sm-6">
                                            <label class="control-label">Arrears Period (Months):</label>
                                           
                                           <input type=int value="'.($former['arrears_period']/30).'" id="arrears_period" class="form-control"></div>
	 				<div class="col-sm-6">
                                            <label class="control-label">Write-off Period(Months):</label>
                                            
                                           <input type=int value="'.($former['writeoff_period']/30).'" id="writeoff_period"  class="form-control">
                                           </div></div>';
                                           
                     $content .= '<div class="form-group">
	                                   
	                                   <div class="col-sm-6">
                                            <label class="control-label">Loan Period(Months):</label>
                                          <input type=int value="'.($former['loan_period']/30).'" id="loan_period" class="form-control">
                                            </div>
                                            
                                           <div class="col-sm-6">
                                            <label class="control-label">Re-Payment Frequency:</label>
                                            <select id="pay_freq" class="form-control"><option value="30">Monthly<option value="1">Daily<option value="7">Weekly<option value="15">2 Weeks<option value="60">2 Months<option value="90">Quarterly<option value="120">4 Months<option value="150">5 Months<option value="180">6 Months<option value="210">7 Months<option value="240"> 8 Months<option value="270">9 Months<option value="300">10 Months<option value="330">11 Months<option value="360">Annually</select>
                                            </div>
                                            </div>';
                                            
                     $content .= '<div class="form-group">
                                           
	                                   <div class="col-sm-6">
                                            <label class="control-label">Repayment Date of Month:</label>
                                         <select id="repay_date"  class="form-control">';
	for($i=28; $i >=1; $i--){
		$content .= "<option value='".$i."'>".$i;
	}
	$content .= '<option value=0>Any</select>
                                            </div>
                                            
                                            <div class="col-sm-6">                      
                                            <label class="control-label">Disbursement Date:</label>
                                           <input type="text" class="form-control" id="date" name="date" value="'.$former['mday'].'-'.$former['month'].'-'.$former['year'].'"/>
                                            </div></div>';
                                            $resp->call("createDate","date");
    /*                                        
        if ($based_on <> '')
	{
		if (strtolower($based_on) == 'savings')
		{
			$base .= "<option value='savings' selected>Savings</option>";
			$base .= "<option value='shares'>Shares</option>";
		}
		elseif (strtolower($based_on) == 'shares')
		{
			$base .= "<option value='savings'>Savings</option>";
			$base .= "<option value='shares' selected>Shares</option>";
		}
		$base .= "<option value=''>&nbsp;</option>";
	}
	else
	{
		$base .= "<option value=''>&nbsp;</option>";
		$base .= "<option value='savings'>Savings</option>";
		$base .= "<option value='shares'>Shares</option>";
	}
                                            
                     $content .= '<div class="form-group">
                                            
	                                   <div class="col-sm-6">
                                            <label class="control-label">Based on:</label>
                                           <select id="based_on" name="based_on">'.$base.'</select>
                                            </div>';*/
              // if (strtolower($_SESSION['position']) == 'manager')
		$bank_res = mysql_query("select a.account_no as account_no, a.name as account_name, b.id as id from bank_account b join accounts a on b.account_id=a.id order by a.name, a.account_no");
	/*else
		$bank_res = mysql_query("select a.account_no as account_no, a.name as account_name, b.id as id from bank_account b join accounts a on b.account_id=a.id where b.id in (select bank_account_id from user_account where user_id =".$_SESSION['user_id'].") order by a.name, a.account_no");*/                   					$content .= '<div class="form-group">          
                                          <div class="col-sm-6"> 
                                            <label class="control-label">Disbursement Bank Account:</label>
                                           <select id="bank_account_id" class="form-control"><option value="">';
	while($bank = mysql_fetch_array($bank_res)){
		$content .= "<option value='".$bank['id']."'>".$bank['account_no']. " - ".$bank['account_name'];
	}
	$content .= '</select></div>';
                                          
                 $mem_res =mysql_query("select a.name as account_name, a.account_no as account_no, mem.id as memaccount_id from mem_accounts mem join savings_product p on mem.saveproduct_id=p.id join accounts a on p.account_id=a.id join member m on mem.mem_id=m.id where m.mem_no='".$former['mem_no']."' and p.type='free' order by a.account_no");                           
                                                             
	                            $content .= '<div class="col-sm-6">
                                            <label class="control-label">Disbursement Method:</label>
                                           
                                          <select type=int id="disburse_method" class="form-control"><option value="0">Issue Cheque or Cash';
	while($mem = mysql_fetch_array($mem_res)){
		$content .= "<option value='".$mem['memaccount_id']."'>Credit Member's ".$mem['account_no']." - ".$mem['account_name'];
	}
	$content .='</select></div></div>';
				 $content .= '<div class="form-group">
	 			<div class="col-sm-6">
                                            <label class="control-label">Automatic Payment from Savings:</label>
                                            
                                           <select type=int id="automatic" class="form-control"><option value="'.$former['automatic'].'">'.$auto.'<option value="0">No<option value="1">Yes</select>
                                           </div></div>';
                                                                                     
                      $content .= "<div class='panel-footer'><button class='btn btn-default' onclick=\"xajax_list_disbursed('".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."','".$to_date."', '".$cheque_no."','".$branch_id."')\">Back</button>&nbsp;<button class='btn btn-default' onclick=\"xajax_delete_disbursed('".$id."', '".$former['amount']."', '".$former['bank_account']."','".$former['branch_id']."');\">Delete</button>&nbsp;<button class='btn btn-primary' onclick=\"xajax_update_disbursed('".$id."', '".$applic_id."', '".$former['amount']."', getElementById('cheque_no').value, getElementById('int_rate').value, getElementById('penalty_rate').value, getElementById('int_method').value,  getElementById('grace_period').value,  getElementById('writeoff_period').value, getElementById('arrears_period').value, getElementById('loan_period').value, getElementById('automatic').value, getElementById('date').value,getElementById('pay_freq').value, getElementById('repay_date').value);\">Update</button>";
 
     $content .= '</div></div></form>';
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//UPDATE DISBURSEMENT 
function update_disbursed($id, $applic_id, $amount, $cheque_no, $int_rate, $penalty_rate, $int_method,  $grace_period,  $writeoff_period, $arrears_period, $loan_period, $automatic,  $date, $pay_freq, $repay_date){
	list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	if($int_rate=='' || $int_method=='' || $grace_period=='' || $writeoff_period=='' || $arrears_period=='' || $loan_period=='' || $penalty_rate=='')
		$resp->alert("You may not leave any field blank");
	elseif(!$calc->isValidDate($mday, $month, $year))
		$resp->alert("Update rejected! Please enter valid date");
	elseif($calc->isFutureDate($mday, $month, $year))
		$resp->alert("Update rejected! You have entered a future date");
		elseif($pay_freq > ($loan_period*30))
		$resp->alert("Update rejected!\n Repayment frequency cannot be more than the loan period");
	//elseif(($loan_period*30) % $pay_freq != 0)
	//	$resp->alert("Update rejected!\n Repayment frequency must be divisible by the loan period");
	else{
		$sth = mysql_query("select * from loan_applic where id='".$applic_id."'");
		$row = mysql_fetch_array($sth);
		//$date = sprintf("%d-%02d-%02d", $year, $month, $mday);
		$date = $date." ".date('H:i:s');
		if( $row['date'] > $date){
			$resp->alert("Update rejected! \nDate entered is earlier than the date of the application");
			return $resp;
		}
		$sth = mysql_query("select * from disbursed where cheque_no='".$cheque_no."' and id <> '".$id."'");
		if(@ mysql_numrows($sth) > 0){
			$resp->alert("Disbursement rejected! \n New Cheque No already exists");
			return $resp;
		}
		//CHECK IF THE LOAN HAS SOME PAYMENT MADE TO IT ALREADY
		$sth= mysql_query("select * from payment where loan_id='".$id."'");
		if(@ mysql_numrows($sth) > 0){
			$resp->alert("Update rejected! \n Some payment has been made to this loan already");
			return $resp;
		}
		//CHECK IF THE LOAN HAS BEEN WRITTEN OFF ALREADY
		$sth = mysql_query("select * from written_off where loan_id='".$id."'");
		if(@ mysql_numrows($sth) > 0){
			$resp->alert("Update rejected! \nThe loan has already been written off");
			return $resp;
		}
		$resp->confirmCommands(1, "Do you really want to update?");
		$resp->call('xajax_update2_disbursed', $id, $applic_id, $amount, $cheque_no, $int_rate, $penalty_rate, $int_method,  $grace_period,  $writeoff_period, $arrears_period, $loan_period, $automatic,  $date, $pay_freq, $repay_date);
	}
	return $resp;
}

//CONFIRM DISBURSEMENT UPDATE
function update2_disbursed($id, $applic_id, $amount, $cheque_no, $int_rate, $penalty_rate, $int_method,  $grace_period,  $writeoff_period, $arrears_period, $loan_period, $automatic,  $date, $pay_freq, $repay_date){
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	//$date = sprintf("%d-%02d-%02d", $year, $month, $mday);
	$date = $date." ".date('H:i:s');
	mysql_query("update disbursed set cheque_no='".$cheque_no."', int_rate='".$int_rate."', penalty_rate='".$penalty_rate."', int_method='".$int_method."', grace_period='".($grace_period*30)."', writeoff_period='".($writeoff_period*30)."', arrears_period='".($arrears_period*30)."', period='".($loan_period*30)."', date='".$date."', last_pay_date='".$date."', automatic='".$automatic."', repay_date='".$repay_date."' where id='".$id."'");

	$branch = mysql_fetch_array(mysql_query("select * from disbursed where id='".$id."'"));
	$branch_insert_id = $branch['branch_id'];
	////////////
	$accno = mysql_fetch_assoc(mysql_query("select m.branch_id,m.mem_no,m.first_name,m.last_name from member m inner join loan_applic la on la.mem_id=m.id where la.id=".$applic_id));
	$action = "update disbursed set cheque_no='".$cheque_no."', int_rate='".$int_rate."', penalty_rate='".$penalty_rate."', int_method='".$int_method."', grace_period='".($grace_period*30)."', writeoff_period='".($writeoff_period*30)."', arrears_period='".($arrears_period*30)."', period='".($loan_period*30)."', date='".$date."', last_pay_date='".$date."', automatic='".$automatic."' where id='".$id."'";
	$msg = "Edited a disbursement set cheque_no:".$cheque_no."writeoff period:".($write_perion*30)." for member:".$accno['first_name']." ".$accno['last_name']." - ".$accno['mem_no'];
	log_action($_SESSION['user_id'],$action,$msg);
	////////////
	//UPDATE THE REPAYMENT SCHEDULE TOO
	mysql_query("delete from schedule where loan_id='".$id."'");
	
	$instalments = ($loan_period * 30) / $pay_freq;
	$portion = ceil($amount / $instalments);
	$monthly_int_rate = ($pay_freq>=30) ? ($int_rate * ($pay_freq/30)) /12 : ($int_rate * $pay_freq) /365;
	if($int_method == 'Armotised')
		$pmt = calc_payment($amount, $instalments, $monthly_int_rate, 2);
		
	if($pay_freq < 30)
		$int_amt = ceil((($amount * $int_rate * $pay_freq) /100) / 365);   //INTEREST FOR THE FIXED METHOD OF INTEREST CALCULATION
	else
		$int_amt = ceil(($amount * ($int_rate/12)/100)/ $instalments) * $loan_period;
	$begin_bal=$amount;
	$end_bal=$amount;
	$next_days = $calc->dateToDays($date) + ($grace_period * $pay_freq) + $pay_freq;
	$total_int =0;
	for($i=0; $i <= $instalments && $begin_bal >0; $i++){
		if($int_method == 'Declining Balance')      //INTEREST FOR THE REDUCING BALANCE METHOD OF INTEREST CALCULATION
			$int_amt = ($pay_freq >=15) ? ceil((($begin_bal * $int_rate * ($pay_freq/30)) /100) / 12) : ceil((($begin_bal * $int_rate * $pay_freq) /100) / 365);
		elseif($int_method == 'Armotised'){
			// calculate interest on outstanding balance 
			$int_amt = $begin_bal * $monthly_int_rate/100; 

			// what portion of payment applies to principal? 
			$portion = $pmt - $int_amt; 
			
			// watch out for balance < pmt 
			if ($begin_bal < $pmt) { 
				$portion = $begin_bal; 
				$pmt   = $int_amt + $portion; 
			} 
		}
		$portion = ($portion == 0) ? $amount / ($instalments) : $portion;
		$end_bal -= $portion;
		$total_int += $int_amt;
			
		if($pay_freq >= 30 && $repay_date>0){	
		//	$pay_date = $calc->endOfNextMonth($calc->daysToDate(($next_days - $pay_freq), '%d'), $calc->daysToDate(($next_days-$pay_freq), '%m'), $calc->daysToDate(($next_days - $pay_freq), '%Y'), '%Y-%m');
			
			preg_match("/(\d+)-(\d+)-(\d+)/", $pay_date, $arg);
				$pay_date = ($pay_date=='') ? $calc->endOfNextMonth($calc->daysToDate(($next_days - $pay_freq), '%d'), $calc->daysToDate(($next_days-$pay_freq), '%m'), $calc->daysToDate(($next_days - $pay_freq), '%Y'), '%Y-%m') :  $calc->endOfNextMonth( $arg[3], $arg[2], $arg[1], '%Y-%m');

			$pay_date = $pay_date."-".$repay_date;
		}else{
			$pay_date = $calc->daysToDate($next_days, '%Y-%m-%d');
			$pay_date = not_holiday($pay_date);   //check whether this is a holiday	
		}
		if($i == 0){
			$scheduled_pay = $int_amt + $portion;
			$last_pay_date = $calc->daysToDate(($next_days - $pay_freq), '%Y-%m-%d');
			if(! mysql_query("update disbursed set next_princ_amt='".$portion."', next_int_amt='".$int_amt."', next_pay_date='".$pay_date."', last_pay_date='".$last_pay_date."', deposit_id='$deposit_id' where id='".$id."'")){
				$resp->alert("ERROR: Disbursement rejected! \n Could not schedule the next payment");
				rollback();
				return $resp;
			}
		}
			
		if(! mysql_query("insert into schedule set loan_id='".$id."', princ_amt='".$portion."', int_amt='".$int_amt."', date='".$pay_date."', begin_bal='".$begin_bal."', end_bal='".$end_bal."',branch_id='".$branch_id_insert."'")){
			$resp->alert("ERROR: Disbursement rejected! \n Could not schedule the repayment \n insert into schedule set loan_id='".$id."', princ_amt='".$portion."', int_amt='".$int_amt."', date='".$pay_date."', begin_bal='".$begin_bal."', end_bal='".$end_bal."',branch_id='".$branch_id_insert."'");
			rollback();
			return $resp;
		}
		//if($int_method != 'Armotised')
			$begin_bal -= $portion;
		$next_days += $pay_freq;
	}

		
	//PRINT THE REPAYMENT SCHEDULE
	$sth = mysql_query("select date, begin_bal, end_bal, princ_amt, int_amt, (princ_amt + int_amt) as total_amt  from schedule where loan_id='".$id."' order by date asc");
	if(@ mysql_numrows($sth) > 0){
		$resp->assign("status", "innerHTML", "<font color=red>Update done!</font>  <a href='export/schedule?loan_id=".$id."&applic_id=".$applic_id."&cust_name=".$cust_name."&cust_no=".$cust_no."&account_name=".$account_name."loan_officer=".$loan_officer."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&status=".$status."&format=excel'><b>Print Repayment Schedule</b></a>");
	}else
		$resp->alert("Loan not disbursed! The repayment schedule could not be printed!");
	return $resp;
}

//DELETE DISBURSEMENT
function delete_disbursed($id, $amount, $bank_account_id,$branch_id){
	$resp = new xajaxResponse();
	//CHECK IF THE LOAN HAS PAYMENT MADE TO IT
	$sth= mysql_query("select * from payment where loan_id='".$id."'");
	if(@ mysql_numrows($sth) > 0){
		$resp->alert("Deletion rejected! \n Some payment has been made to this loan already");
		return $resp;
	}
	//CHECK IF THE LOAN HAS BEEN WRITTEN OFF ALREADY
	$sth = mysql_query("select * from written_off where loan_id='".$id."'");
	if(@ mysql_numrows($sth) > 0){
		$resp->alert("Deletion rejected! \nThe loan has already been written off");
		return $resp;
	}
	$sth = mysql_query("select * from disbursed where id='".$id."'");
	$row = mysql_fetch_array($sth);
	$resp->confirmCommands(1, "Do you really want to delete?");
	$resp->call('xajax_delete2_disbursed', $id, $row['amount'], $row['bank_account'], $branch_id);
	return $resp;
}

//CONFIRM DELETION OF DISBURSEMENT IN DB
function delete2_disbursed($id, $amount, $bank_account_id, $branch_id){
	$resp = new xajaxResponse();
	//UPDATE COMPULSORY SAVINGS
	$limit_res = mysql_query("select * from branch where branch_no='".$branch_id."'");
		if(mysql_num_rows($limit_res)>0)
		$limit = mysql_fetch_array($limit_res);
		else
		$limit = mysql_fetch_array(mysql_query("select * from branch limit 1"));

	$base_res = mysql_query("select d.based_on as based_on, d.amount as amount, applic.mem_id as mem_id, applic.guarantor1 as guarantor1, applic.guarantor2 as guarantor2, d.deposit_id from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id where  d.id='".$id."'");
	$base = mysql_fetch_array($base_res);
	if($base['based_on'] == 'savings'){
		$comp_amt = ($limit['loan_save_percent'] == 0) ? 0 : ($base['amount'] * 100) / $limit['loan_save_percent'];
		$guaranteed_amt = ($limit['guarantor_save_percent'] == 0) ? 0 : ($base['amount'] * 100) / $limit['guarantor_save_percent'];
		$comp_res = mysql_query("select s.id as id from savings_product s join accounts a on s.account_id=a.id where a.name='Compulsory Savings'");
		$class = "Compulsory Savings";
	}else{
		$comp_amt = ($limit['loan_save_percent'] == 0) ? 0 : ($base['amount'] * 100) / $limit['loan_share_percent'];
		$guaranteed_amt = ($limit['guarantor_share_percent'] == 0) ? 0 : ($base['amount'] * 100) / $limit['guarantor_share_percent'];
		$comp_res = mysql_query("select s.id as id from savings_product s join accounts a on s.account_id=a.id where a.name='Compulsory Shares'");
		$class = "Compulsory Shares";
	}
	$comp = mysql_fetch_array($comp_res);
	$post_res = mysql_query("select id as memaccount_id from mem_accounts where mem_id='".$base['mem_id']."' and saveproduct_id='".$comp['id']."'");
	$post = mysql_fetch_array($post_res);
	start_trans();
	//DELETE THE SAVINGS DEPOSIT IF THE LOAN HAD BEEN DEPOSITED
	if($base['deposit_id'] > 0){
		if(! mysql_query("delete from deposit where id='".$base['deposit_id']."'")){
			$resp->alert("ERROR: Disbursement not deleted! \n Could not delete savings deposit");
			rollback();
			return $resp;
		}
	}
	if(! mysql_query("update deposit set amount=amount - ".$comp_amt ." where memaccount_id='".$post['memaccount_id']."'")){
		$resp->alert("ERROR: Disbursement not deleted! \n Could not update ".$class);
		rollback();
		return $resp;
	}
	//GUARANTOR1'S COMPULSORY SHARES/SAVINGS
	$post_res = mysql_query("select id as memaccount_id from mem_accounts where mem_id='".$base['guarantor1']."' and saveproduct_id='".$comp['id']."'");
	$post = mysql_fetch_array($post_res);
	if(! mysql_query("update deposit set amount=amount - ".$guaranteed_amt ." where memaccount_id='".$post['memaccount_id']."'")){
		$resp->alert("ERROR: Disbursement not deleted! \n Could not update ".$class . " for Guarantor 1");
		rollback();
		return $resp;
	}
	//GUARANTOR2'S COMPULSORY SHARES/SAVINGS
	$post_res = mysql_query("select id as memaccount_id from mem_accounts where mem_id='".$base['guarantor2']."' and saveproduct_id='".$comp['id']."'");
	$post = mysql_fetch_array($post_res);
	if(! mysql_query("update deposit set amount=amount - ".$guaranteed_amt ." where memaccount_id='".$post['memaccount_id']."'")){
		$resp->alert("ERROR: Disbursement not deleted! \n Could not update ".$class . " for Guarantor 2");
		rollback();
		return $resp;
	}
	if(! mysql_query("delete from schedule where loan_id='".$id."'")){
		$resp->alert("ERROR: Disbursement not deleted! \n Could not delete the repayment schedule");
		rollback();
		return $resp;
	}
	$former_res = mysql_query("select cycle from disbursed where id=".$id."");
	$former = mysql_fetch_array($former_res);
	$sth = mysql_query("select d.id as id from disbursed d join loan_applic applic on d.applic_id = applic.id where applic.mem_id='".$mem_id."' and d.cycle > '".$former['cycle']."'");
	
	while($row = mysql_fetch_array($sth)){
		mysql_query("update disbursed set cycle=cycle-1 where id=".$row['id']."");
	}
	
	if(! mysql_query("delete from disbursed where id='".$id."'")){
		$resp->alert("ERROR: Disbursement not deleted! \n Could not delete it from database");
		rollback();
		return $resp;
	}
	if(! mysql_query("delete from penalty where loan_id='".$id."'")){
		$resp->alert("ERROR: Disbursement not deleted! \n Could not delete the penalties");
		rollback();
		return $resp;
	}
	if($base['deposit_id'] == 0){
		if(! mysql_query("update bank_account set account_balance=account_balance+".$amount." where id=".$bank_account_id."")){
			$resp->alert("ERROR: Disbursement not deleted! \n Could not update the bank account balance");
			rollback();
			return $resp;
		}
	}
	
	////////////////////////
	$accno = mysql_fetch_assoc(mysql_query("select m.mem_no,m.first_name,m.last_name from member m inner join loan_applic la on la.mem_id=m.id inner join disbursed d on d.applic_id=la.id where d.id=".$id));
	$action = "delete from disbursed where id='".$id."'";
	$msg = "Deleted a disbursement of amount:".$amount." for member:".$accno['first_name']." ".$accno['last_name']." - ".$accno['mem_no'];
	log_action($_SESSION['user_id'],$action,$msg);
	/////////////////////////
	commit();
	$resp->assign("status", "innerHTML", "<font color=red>Disbursement deleted</font>");
	return $resp;
}

//LIST DISBURSEMENTS
function list_disbursed($cust_name, $cust_no, $account_name, $loan_officer, $from_date,$to_date, $cheque_no,$branch_id){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	//$resp->alert("this page");
	$branch = ($branch_id=='all'||$branch_id=='') ? NULL: "and d.branch_id=".$branch_id;
	
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR LOANS DISBURSED</h3>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Customer Name:</label>
                                            <input type="text" id="cust_name" value="All" class="form-control">                                            
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Member No:</label>
                                            <input type=text id="cust_no" value="All" class="form-control">
                                        </div>
                                    </div>
                                </div>
        
        <div class="form-group">
        
                 <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Loan Officer:</label>
                                            <select id="officer_id" class="form-control"><option value="0">All';
	$officer_res = mysql_query("select * from employees order by firstName, lastName");
	while($officer = mysql_fetch_array($officer_res)){
		$content .= "<option value='".$officer['employeeId']."'>".$officer['firstName']." ".$officer['lastName'];
	}
	$content .='</select>                                           
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Loan Product:</label>
                                            <select id="account_name" class="form-control"><option value="">All';
	$prod_res = mysql_query("select a.name as account_name, a.account_no as account_no from loan_product p join accounts a on p.account_id=a.id order by a.name, a.account_no");
	while($prod = @ mysql_fetch_array($prod_res)){
		$content .= "<option value='".$prod['account_name']."'>".$prod['account_no']." - ".$prod['account_name'];
	}
	$content .='</select>                                           
                                        </div>
                                    </div>
                                </div>';
                                
                $content .='<div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Cheque No:</label>
                                            <input type="text" id="cheque_no" class="form-control">                                            
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Date Range:</label>
                                            <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>      
                                        </div>
                                    </div>
                                </div>
               
               <div class="form-group">
                                    <div class="row">
                           <div class="col-sm-6">                                          
                                           
                                           <label class="control-label">Branch:</label>
                                            <span>'.branch_rep($branch_id).'</span>                                   
                                    </div>
                                   
                                </div>                          
                            '; 
                            
	
	$content .= "<div class='panel-footer'>                              
                                
                                <button type='button' class='btn  btn-primary'  onclick=\"xajax_list_disbursed(getElementById('cust_name').value, getElementById('cust_no').value, getElementById('account_name').value, getElementById('officer_id').value, getElementById('from_date').value, getElementById('to_date').value, getElementById('cheque_no').value, getElementById('branch_id').value);\">Search</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","from_date");
                    $resp->call("createDate","to_date"); 
                   //$resp->assign("display_div", "innerHTML", $content);
	if($from_date=='' || $to_date==''){
		
            $cont = '<font color="red">Please select the disbursed loans you would like to list!</font>';
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
	$name = ($cust_name == 'All') ? "" : $cust_name;
	$mem_no = ($cust_no == 'All') ? "" : $cust_no;
	$from_date = sprintf("%04d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%04d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
	if($loan_officer >0)
		$officer = "o.employeeId='".$loan_officer."'";
	else
		$officer = "o.employeeId > 0";
	
	$sth = mysql_query("select d.id as id, d.automatic as automatic, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, d.cheque_no as cheque_no, d.int_rate as int_rate, d.int_method as int_method, d.grace_period as grace_period, d.writeoff_period as writeoff_period, d.arrears_period as arrears_period, d.written_off as written_off, d.bank_account as bank_account from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and d.cheque_no like '%".$cheque_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' ".$branch." order by o.firstName, o.lastName, d.cheque_no, m.first_name, m.last_name");
	
	$sth2 = mysql_query("select d.id as id, d.automatic as automatic, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.penalty_rate as penalty_rate, d.balance as balance, d.period as loan_period, d.cheque_no as cheque_no, d.int_rate as int_rate, d.int_method as int_method, d.grace_period as grace_period, d.writeoff_period as writeoff_period, d.arrears_period as arrears_period, d.written_off as written_off, d.bank_account as bank_account from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and d.cheque_no like '%".$cheque_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' ".$branch." order by o.firstName, o.lastName, d.cheque_no, m.first_name, m.last_name");

	if(@ mysql_numrows($sth2) == 0){
		$cont = "<font color=red>No disbursed loans in your search options</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	//$max_page = ceil(mysql_num_rows($sth)/$num_rows);
	$total__amount =mysql_fetch_assoc(mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and d.cheque_no like '%".$cheque_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' ".$branch." order by o.firstName, o.lastName, d.cheque_no, m.first_name, m.last_name ")); 
	$former_officer ="";
	$i=$stat+1;
	$amt_sub_total = 0;
	while($row = mysql_fetch_array($sth2)){
		$officer = $row['of_firstName']." ".$row['of_lastName'];
		if(strcmp($former_officer, $officer) != 0){
						
			$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                             <p><h3 class="semibold text-primary mt0 mb5">LIST OF LOANS DISBURSED</h3></p>
                           
                                <p><h5>LOAN OFFICER:&nbsp;<font color="#00BFFF">'.$officer.'</font></h5></p>
                                <p><h5>TOTAL DISBURSED:&nbsp;<font color="#00BFFF">'.number_format($total__amount['amountd'],2).'</font></h5></p>
                               
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
 		$content .= '<thead><th>#</th><th>NAME</th><th>MEMBER NO.</th><th>DATE</th><th>PRODUCT</th><th>CHEQUE NO.</th><th>AMOUNT</th><th>ANNUAL INT. RATE(%)</th><th>ANNUAL PENALTY RATE(%)</th><th>METHOD</th><th>LOAN PERIOD (MONTHS)</th><th>GRACE PERIOD (MONTHS)</th><th>ARREARS PERIOD</th><th>WRITE-OFF PERIOD (MONTHS)</th><th>DISBURSEMENT ACCOUNT</th><th>AUTOMATIC PAYMENT</th><th>EDIT</th></thead><tbody>';
		
			$former_officer = $officer;
		}
		if($row['balance'] != $row['amount'] && $row['written_off']==0)
			$edit = "Payment Started <a href='javascript:;' onclick=\"xajax_edit_cheque('".$row['id']."',  '".$row['applic_id']."', '".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."','".$to_date."', '".$cheque_no."','".$branch_id."');\">Edit Cheque / Penalty Rate</a>";
		elseif($row['written_off'] == '1')
			$edit = "Written Off <a href='javascript:;' onclick=\"xajax_edit_cheque('".$row['id']."', '".$row['applic_id']."', '".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."','".$to_date."', '".$cheque_no."','".$branch_id."');\">Edit Cheque / Penalty Rate</a>";
		else
			$edit = "<a href='javascript:;' onclick=\"xajax_edit_disbursed('".$row['id']."', '".$row['applic_id']."', '".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."','".$to_date."', '".$cheque_no."','".$branch_id."');\">Edit</a>";
		$bank_res = mysql_query("select a.name as account_name, a.account_no as account_no from bank_account b join accounts a on b.account_id=a.id where b.id='".$row['bank_account']."'");
		$bank = mysql_fetch_array($bank_res);
		//$color = ($i%2 == 0) ? "white" : "lightgrey";
		$auto = ($row['automatic'] == '1') ? "Yes" : "No";
		$content .= "<tr><td>".$i."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".$row['date']."</td><td>".$row['account_no']." - ".$row['account_name']."</td><td>".$row['cheque_no']."</td><td>".number_format($row['amount'], 2)."</td><td>".$row['int_rate']."</td><td>".$row['penalty_rate']."</td><td>".$row['int_method']."</td><td>".($row['loan_period']/30)."</td><td>".($row['grace_period']/30)."</td><td>".($row['arrears_period']/30)."</td><td>".($row['writeoff_period']/30)."</td><td>".$bank['account_no']. " - ".$bank['account_name']."</td><td>".$auto."</td><td>".$edit."</td></tr></tbody>";
		$i++;
		$amt_sub_total += $row['amount'];
	}
	$content .= "<tr><td><b>TOTAL</b></td><td></td><td></td><td></td><td></td><td></td><td><b>".number_format($amt_sub_total, 2)."</b></td><td></td><td></td><td></td><td></td><td></td><td ></td><td></td><td></td><td></td><td></td></tr></tbody></table></div>";
	
	$resp->call("createTableJs");
	 }
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//LIST OUTSTANDING
function list_outstanding($cust_name, $cust_no, $account_name, $loan_officer,$from_date,$to_date, $cheque_no,$branch_id){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$resp->assign("status", "innerHTML", "");
	$branch = ($branch_id=='all'||$branch_id=='') ? NULL : "and d.branch_id=".$branch_id;
	
			//$resp->alert($from_date);
			//return $resp;
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR OUTSTANDING LOANS</h3>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Customer Name:</label>
                                            <input type="text" id="cust_name" value="All" class="form-control">                                            
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Member No:</label>
                                            <input type=text id="cust_no" value="All" class="form-control">
                                        </div>
                                    </div>
                                </div>
        
        <div class="form-group">
        
                 <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Loan Officer:</label>
                                            <select id="officer_id" class="form-control"><option value="0">All';
	$officer_res = mysql_query("select * from employees order by firstName, lastName");
	while($officer = mysql_fetch_array($officer_res)){
		$content .= "<option value='".$officer['employeeId']."'>".$officer['firstName']." ".$officer['lastName'];
	}
	$content .='</select>                                           
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Loan Product:</label>
                                            <select id="account_name" class="form-control"><option value="">All';
	$prod_res = mysql_query("select a.name as account_name, a.account_no as account_no from loan_product p join accounts a on p.account_id=a.id order by a.name, a.account_no");
	while($prod = @ mysql_fetch_array($prod_res)){
		$content .= "<option value='".$prod['account_name']."'>".$prod['account_no']." - ".$prod['account_name'];
	}
	$content .='</select>                                           
                                        </div>
                                    </div>
                                </div>';
                                
                $content .='<div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">ChequeNo:</label>
                                            <input type="text" id="cheque_no" class="form-control">                                            
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Date Range:</label>
                                            <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" placeholder="to" /></div>
                                            </div>      
                                        </div>
                                    </div>
                                </div>';
                                
               $content .='<div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Branch:</label>
                                            <span>'.branch_rep($branch_id).'</span>
                                        </div>             
                                    </div>
                                </div>                       
                            ';                                                        
	
	$content .= "<div class='panel-footer'>                 
                                
                                <button class='btn  btn-primary' onclick=\"xajax_list_outstanding(getElementById('cust_name').value, getElementById('cust_no').value, getElementById('account_name').value, getElementById('officer_id').value, getElementById('from_date').value, getElementById('to_date').value,getElementById('cheque_no').value,getElementById('branch_id').value);return false;\">Search</button>
                            </div></div>
                        </form>
                        <!--/ Form default layout -->
                    </div></div>";
                    $resp->call("createDate","from_date");
		    $resp->call("createDate","to_date");  
                  
	if($from_date=='' || $to_date==''){
		$cont = "<font color=red>Please select the period for the disbursed loans you would like to list!</font>";
		$resp->assign("status", "innerHTML", $cont);
		
	}
	else{
	$name = ($cust_name == 'All') ? "" : $cust_name;
	$mem_no = ($cust_no == 'All') ? "" : $cust_no;
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
	if($loan_officer >0)
		$officer = "o.employeeId='".$loan_officer."'";
	else
		$officer = "o.employeeId > 0";
	
	$sth = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.cheque_no as cheque_no, d.amount as disbursed_amt, d.date as date, d.balance as balance, d.period as loan_period, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.balance >0 and d.cheque_no like '%".$cheque_no."%' and d.written_off=0 ".$branch." order by o.firstName, o.lastName, d.cheque_no, m.first_name, m.last_name");
	
	//$resp->alert(mysql_numrows($sth));
	//return $resp;
	if(@ mysql_numrows($sth) > 0){	

	$sth2 = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.cheque_no as cheque_no, d.amount as disbursed_amt, d.date as date, d.balance as balance, d.period as loan_period, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, d.last_pay_date as last_pay_date, d.pay_freq as pay_freq, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.balance >0 and d.cheque_no like '%".$cheque_no."%' and d.written_off=0 ".$branch." order by o.firstName, o.lastName, d.cheque_no, m.first_name, m.last_name");
	
	//$max_page = ceil(mysql_num_rows($sth)/$num_rows);
	$date = sprintf("%d-%02d-%02d", date('Y'), date('m'), date('d'));
	$date = $date." ".date('H:i:s');
	$former_officer ="";
	$i=$stat+1;
	// INIT SUB TOTALS
	$amt_sub_total = 0; $bal_sub_total = 0; $princ_arrears_sub_total = 0; $int_arrears_sub_total = 0; $tot_arrears_sub_total; $penalty_sub_total = 0; $princ_due_sub_total = 0; $int_due_sub_total = 0; $tot_amt_sub_total = 0;

	$total_amount = mysql_fetch_array(mysql_query("select sum(d.amount) as disbursed, sum(d.balance) as balance from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.balance >0 and d.cheque_no like '%".$cheque_no."%' and d.written_off=0 ".$branch." order by o.firstName, o.lastName, d.cheque_no, m.first_name, m.last_name"));


	while($row = mysql_fetch_array($sth2)){
		$officer = $row['of_firstName']." ".$row['of_lastName'];
		if(strcmp($former_officer, $officer) != 0){
						
			$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                           
                               <p><h3 class="semibold text-primary mt0 mb5">LIST OF OUTSTANDING LOANS</h3></p>
                               <p><h5>LOAN OFFICER:&nbsp;<font color="#00BFFF">'.$officer.'</font></h5></p>
                                <p><h5>TOTAL DISBURSED:&nbsp;<font color="#00BFFF">'.number_format($total__amount['amountd'],2).'</font></h5></p>
                                 <p><h5>TOTAL BALANCE:&nbsp;<font color="#00BFFF">'.number_format($total__amount['balance'],2).'</font></h5></p>
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
 		$content .= '<thead><th>#</th><th>NAME</th><th>MEMBER NO.</th><th>DATE</th><th>PRODUCT</th><th>CHEQUE NO.</th><th>AMOUNT</th><th>LOAN BALANCE</th><th>PRINC ARREARS</th><th>INT ARREARS</th><th>TOTAL ARREARS</th><th>PENALTY</th><th>PRINC DUE</th><th>INT DUE</th><th>TOTAL AMOUNT DUE</th><th>SCHEDULE</th><th>PAY</th></thead><tbody>';
		
			$former_officer = $officer;			
		}

		//RESCHEDULE INTEREST FOR DECLINING BALANCE METHOD
		if($row['int_method'] == 'Declining Balance'){
			//FIND OUT THE UPPER BOUND OF THE CURRENT PAYMENT PERIOD
			$upper = mysql_fetch_array(mysql_query("select date from schedule where date >= '".$date."' and loan_id='".$row['id']."' order by date asc limit 1"));
			$upper_date = ($upper['date'] == NULL) ? $date : $upper['date'];
			
			//$sched_res = mysql_query("select id from schedule where loan_id='".$row['id']."' and princ_amt>0 and date> '".$row['last_pay_date']."' and date <= '".$date."'");
			$sched_res = mysql_query("select id from schedule where loan_id='".$row['id']."' and princ_amt>0 and date> '".$row['last_pay_date']."' and date <= '".$upper_date."'");
		//	$resp->assign("status", "innerHTML", "select id from schedule where loan_id='".$row['id']."' and princ_amt>0 and date> '".$row['last_pay_date']."' and date <= '".$date."'<BR>");
			while($sched = mysql_fetch_array($sched_res)){
				$new_int = $row['balance'] * (($row['int_rate']/100)/12) * ($row['pay_freq']/30);
				mysql_query("update schedule set int_amt ='".$new_int."' where id='".$sched['id']."'");
			}
		}

		//ARREARS
		$arrears_days = $calc->dateToDays(date('d'), date('m'), date('Y')) - $row['arrears_period'];
		$arrears_date = $calc->daysToDate($arrears_days, '%Y-%m-%d');
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id='".$row['id']."' and date < '".$arrears_date."'");
		
		$sched = @mysql_fetch_array($sched_res);
				
		$paid_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from payment where loan_id='".$row['id']."' and date <= '".$date."'");
		
		$paid = mysql_fetch_array($paid_res);
		$sched_amount = ($sched['amount'] == NULL) ? "0" : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? "0" : $sched['int_amt'];
		$paid_amt = ($paid['amount'] == NULL) ? "0" : $paid['amount'];
		$paid_int = ($paid['int_amt'] == NULL) ? "0" : $paid['int_amt'];
		$arrears_amt = $sched_amount - $paid_amt;
		$int_arrears = $sched_int - $paid_int;
		
		
		if($arrears_amt <= 0){
			$princ_arrears =0;
			$int_arrears = 0;
			$total_arrears =0;
			$total_amt_arrears = 0;
			$int_amt_arrears =0;
			$princ_amt_arrears =0;
		}else{
			$princ_arrears = $arrears_amt;
			$princ_amt_arrears = $arrears_amt;
			
			$arrears_days =$arrears_days - $calc->dateToDays($row['last_mday'], $row['last_month'], $row['last_year']) -1;
			
			$total_arrears = $princ_arrears + $int_arrears;
			$total_amt_arrears = $total_arrears;
			$int_amt_arrears = $int_arrears;
			$int_arrears = ($int_arrears == 0) ? "--" : $int_arrears;
		}
				
	//CALCULATE DUE PRINCIPAL
		//FIND OUT THE UPPER BOUND OF THE CURRENT PAYMENT PERIOD
		$upper = mysql_fetch_array(mysql_query("select date from schedule where date >= '".$date."' and loan_id='".$row['id']."' order by date asc limit 1"));
		$upper_date = ($upper['date'] == NULL) ? $date : $upper['date'];
		
		//$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id=".$row['id']." and date <= '".$date."'");
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id=".$row['id']." and date <= '".$upper_date."'");
	
		$sched = @mysql_fetch_array($sched_res);
		$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? 0 : $sched['int_amt'];
			
		$due_princ_amt = $sched_amt - $paid_amt; //- $arrears_amt;
		//$due_princ_amt = $paid_amt;
		$due_int_amt = $sched_int - $paid_int; // - $int_amt_arrears;	
		
		$first_instal =0;
		if($due_princ_amt <= 0 && $paid_amt >= 0){
			$due_princ = 0; 
			if($due_princ_amt ==0 && $paid_amt ==0){    //FIRST INSTALMENT, YOU PAY THE FIRST INTEREST
				$check = mysql_fetch_array(mysql_query("select * from schedule where loan_id='".$row['id']."' and date=CURDATE() order by date asc limit 1"));
				$due_int_amt = $check['int_amt'];
				$due_int = $due_int_amt;
				$first_instal = 1;
			}else{
				$due_int = 0;
				$due_int_amt=0;
			}
		}else{
			$due_princ = $due_princ_amt;
			if($due_int_amt >0){	
				$due_int = $due_int_amt;
			}else
				$due_int=0; 
			
		}
		$total_amt_due =  $due_princ_amt + $due_int_amt;
		$total_due = ($total_amt_due <= 0) ? 0 : $total_amt_due;
				
		$pay = "<a href='javascript:;' onclick=\"xajax_add_pay('".$row['id']."', '".$row['applic_id']."', '".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$princ_amt_arrears."', '".$int_amt_arrears."', '".$due_princ_amt."', '".$due_int_amt."',  '".$from_year."', '".$from_month."', '".$from_mday."', '".$to_year."', '".$to_month."', '".$to_mday."', 'outstanding', '".$cheque_no."','".$branch_id."');\">Payments</a>";
		
		$schedule = "<a href='export/schedule?loan_id=".$row['id']."&applic_id=".$row['applic_id']."&cust_name=".$cust_name."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&status=outstanding&format=excel'>Schedule</a>";

		//PENALTY
		$pen_res = mysql_query("select sum(amount) as amount from penalty where status='pending' and loan_id='".$row['id']."'");
		$pen = mysql_fetch_array($pen_res);
		$penalty = ($pen['amount'] == NULL) ? "--" : $pen['amount'];
		
		//$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr><td>".$i."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".$row['date']."</td><td>".$row['account_no']." - ".$row['account_name']."</td><td>".$row['cheque_no']."</td><td>".number_format($row['disbursed_amt'], 2)."</td><td>".number_format($row['balance'], 2)."</td><td>".number_format($princ_arrears, 2)."</td><td>".number_format($int_arrears, 2)."</td><td>".number_format($total_arrears, 2)."</td><td>".number_format($penalty, 2)."</td><td>".number_format($due_princ, 2)."</td><td>".number_format($due_int, 2)."</td><td>".number_format($total_due,2)."</td><td>".$schedule."</td><td>".$pay."</td></tr>";
		$i++;

		// GET SUB TOTALS
		$amt_sub_total += $row['disbursed_amt']; 
		$bal_sub_total += $row['balance']; 
		$princ_arrears_sub_total += $princ_arrears; 
		$int_arrears_sub_total += $int_arrears;
		$tot_arrears_sub_total += $total_arrears;
		$penalty_sub_total += $penalty; 
		$princ_due_sub_total += $due_princ; 
		$int_due_sub_total += $due_int; 
		$tot_amt_sub_total += $total_due;
	}
	// PRINT SUB TOTALS
	$content .= "<tr><td><b>TOTAL</b></td><td></td><td></td><td></td><td></td><td></td><td><b>".number_format($amt_sub_total, 2)."</b></td><td><b>".number_format($bal_sub_total, 2)."</b></td><td><b>".number_format($princ_arrears_sub_total, 2)."</b></td><td><b>".number_format($int_arrears_sub_total, 2)."</b></td><td><b>".number_format($tot_arrears_sub_total, 2)."</b></td><td><b>".number_format($penalty_sub_total, 2)."</b></td><td><b>".number_format($princ_due_sub_total, 2)."</b></td><td><b>".number_format($int_due_sub_total, 2)."</b></td><td><b>".number_format($tot_amt_sub_total, 2)."</b></td><td></td><td></td></tr></tbody></table></div>";
	$resp->call("createTableJs");
	
	 }
	 
	 else{
	 $cont = "<font color=red>No outstanding loans in your search options</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
	
}


//LIST OUTSTANDING
function list_due($cust_name, $cust_no, $account_name,$loan_officer,$date,$cheque_no,$branch_id){

        list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$resp->assign("status", "innerHTML", "");
	$branch = ($branch_id=='all'||$branch_id=='') ? NULL : "and d.branch_id=".$branch_id;
	
	
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR DUE PAYMENTS</h3>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Customer Name:</label>
                                            <input type="text" id="cust_name" value="All" class="form-control">                                            
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Member No:</label>
                                            <input type=text id="cust_no" value="All" class="form-control">
                                        </div>
                                    </div>
                                </div>
        
        <div class="form-group">
        
                 <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Loan Officer:</label>
                                            <select id="officer_id" class="form-control"><option value="0">All';
	$officer_res = mysql_query("select * from employees order by firstName, lastName");
	while($officer = mysql_fetch_array($officer_res)){
		$content .= "<option value='".$officer['employeeId']."'>".$officer['firstName']." ".$officer['lastName'];
	}
	$content .='</select>                                           
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Loan Product:</label>
                                            <select id="account_name" class="form-control"><option value="">All';
	$prod_res = mysql_query("select a.name as account_name, a.account_no as account_no from loan_product p join accounts a on p.account_id=a.id order by a.name, a.account_no");
	while($prod = @ mysql_fetch_array($prod_res)){
		$content .= "<option value='".$prod['account_name']."'>".$prod['account_no']." - ".$prod['account_name'];
	}
	$content .='</select>                                           
                                        </div>
                                    </div>
                                </div>';                                
                                
                                                                                                               
                           $content .='<div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                             <label class="control-label">Branch:</label>
                                            <span>'.branch_rep($branch_id).'</span>
                                        </div>
                                         <div class="col-sm-6">
                                            <label class="control-label">Cheque No:</label>
                                            <input type="text" id="cheque_no" class="form-control">                                            
                                        </div>      
                                        </div></div>'; 
                                
                $content .='<div class="form-group">
                                    <div class="row">                                       
                                        <div class="col-sm-6">
                                            <label class="control-label">Date of Reporting:</label>
                                            
                                                <input type="text" class="form-control" class="form-control" id="date" name="date"  />
                                                
                                                 
                                        </div>
                                                                              
                                    </div>
                                </div>';                          
                            
                                              
	$resp->call("createDate","date");
	
	$content .= "<div class='panel-footer'>                              
                                
                                <input type='button' class='btn  btn-primary' value='Search'  onclick=\"xajax_list_due(getElementById('cust_name').value, getElementById('cust_no').value, getElementById('account_name').value, getElementById('officer_id').value,getElementById('date').value, getElementById('cheque_no').value,getElementById('branch_id').value);\">
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
	
	if($date==''){
		$cont = "<font color=red>Please select the date for the disbursed loans you would like to list!</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
	$name = ($cust_name == 'All') ? "" : $cust_name;
	$mem_no = ($cust_no == 'All') ? "" : $cust_no;
	$from_date = sprintf("%04d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%04d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
	if($loan_officer >0)
		$officer = "o.employeeId='".$loan_officer."'";
	else
		$officer = "o.employeeId > 0";
	
	$sth = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.cheque_no as cheque_no, d.amount as disbursed_amt, d.date as date, d.balance as balance, d.period as loan_period, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date <='".$date."' and d.balance >0 and d.cheque_no like '%".$cheque_no."%' and d.written_off=0 ".$branch." order by o.firstName, o.lastName, d.cheque_no, m.first_name, m.last_name");
	if(@ mysql_numrows($sth) == 0){
		$cont = "<font color=red>No payments due in your search options</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}

	$sth2 = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.cheque_no as cheque_no, d.amount as disbursed_amt, d.date as date, d.balance as balance, d.period as loan_period, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, d.last_pay_date as last_pay_date, d.pay_freq as pay_freq, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer."  and d.date <='".$date."' and d.balance >0 and d.cheque_no like '%".$cheque_no."%' and d.written_off=0 ".$branch." order by o.firstName, o.lastName, d.cheque_no, m.first_name, m.last_name");
	
	//$max_page = ceil(mysql_num_rows($sth)/$num_rows);
	$date = sprintf("%d-%02d-%02d", date('Y'), date('m'), date('d'));
	$date = $date." ".date('H:i:s');
	$former_officer ="";
	$i=$stat+1;
	// INIT SUB TOTALS
	$amt_sub_total = 0; $bal_sub_total = 0; $princ_arrears_sub_total = 0; $int_arrears_sub_total = 0; $tot_arrears_sub_total; $penalty_sub_total = 0; $princ_due_sub_total = 0; $int_due_sub_total = 0; $tot_amt_sub_total = 0;



	while($row = mysql_fetch_array($sth2)){
		$officer = $row['of_firstName']." ".$row['of_lastName'];
		if(strcmp($former_officer, $officer) != 0){
			
			$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                               
                                 <p><h3 class="semibold text-primary mt0 mb5">LIST OF OF DUE PAYMENTS</h3></p>
                               <p><h5>LOAN OFFICER:&nbsp;<font color="#00BFFF">'.$officer.'</font></h5></p>
                                
                                <p></p>
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
 		$content .= '<thead><th>#</th><th>NAME</th><th>MEMBER N0.</th><th>DATE</th><th>PRODUCT</th><th>CHEQUE NO.</th><th>AMOUNT</th><th>LOAN BALANCE</th><th>PRINC ARREARS</th><th>INT ARREARS</th><th>TOTAL ARREARS</th><th>PENALTY</th><th>PRINC DUE</th><th>INT DUE</th><th>TOTAL AMOUNT DUE</th><th>SCHEDULE</th><th>PAY</th></thead><tbody>';
 		$former_officer = $officer;
		}
		//RESCHEDULE INTEREST FOR DECLINING BALANCE METHOD
		if($row['int_method'] == 'Declining Balance'){
			//FIND OUT THE UPPER BOUND OF THE CURRENT PAYMENT PERIOD
			$upper = mysql_fetch_array(mysql_query("select date from schedule where date >= '".$date."' and loan_id='".$row['id']."' order by date asc limit 1"));
			$upper_date = ($upper['date'] == NULL) ? $date : $upper['date'];

			//$sched_res = mysql_query("select id from schedule where loan_id='".$row['id']."' and princ_amt>0 and date> '".$row['last_pay_date']."' and date <= '".date('Y-m-d 23:59:59')."'");
			$sched_res = mysql_query("select id from schedule where loan_id='".$row['id']."' and princ_amt>0 and date> '".$row['last_pay_date']."' and date <= '".$upper_date."'");
			while($sched = mysql_fetch_array($sched_res)){
				$new_int = $row['balance'] * (($row['int_rate']/100)/12) * ($row['pay_freq']/30);
				mysql_query("update schedule set int_amt ='".$new_int."' where id='".$sched['id']."'");
			}
		}

		//ARREARS
		list($to_year,$to_month,$to_mday) = explode('-', $to_date);
		$arrears_days = $calc->dateToDays($to_mday, $to_month, $to_year) - $row['arrears_period'];
		$arrears_date = $calc->daysToDate($arrears_days, '%Y-%m-%d');
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id='".$row['id']."' and date < '".$arrears_date."'");
		
		$sched = @mysql_fetch_array($sched_res);
				
		$paid_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from payment where loan_id='".$row['id']."' and date <= '".$date."'");
		
		$paid = mysql_fetch_array($paid_res);
		$sched_amount = ($sched['amount'] == NULL) ? "0" : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? "0" : $sched['int_amt'];
		$paid_amt = ($paid['amount'] == NULL) ? "0" : $paid['amount'];
		$paid_int = ($paid['int_amt'] == NULL) ? "0" : $paid['int_amt'];
		$arrears_amt = $sched_amount - $paid_amt;
		$int_arrears = $sched_int - $paid_int;
		
		
		if($arrears_amt <= 0){
			$princ_arrears ="--";
			$int_arrears = "--";
			$total_arrears ="--";
			$total_amt_arrears = 0;
			$int_amt_arrears =0;
			$princ_amt_arrears =0;
		}else{
			$princ_arrears = $arrears_amt;
			$princ_amt_arrears = $arrears_amt;
			
			$arrears_days =$arrears_days - $calc->dateToDays($row['last_mday'], $row['last_month'], $row['last_year']) -1;
			
			$total_arrears = $princ_arrears + $int_arrears;
			$total_amt_arrears = $total_arrears;
			$int_amt_arrears = $int_arrears;
			$int_arrears = ($int_arrears == 0) ? "--" : $int_arrears;
		}
				
	//CALCULATE DUE PRINCIPAL
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id=".$row['id']." and date <= '".$date."'");
	
		$sched = @mysql_fetch_array($sched_res);
		$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? 0 : $sched['int_amt'];
			
		$due_princ_amt = $sched_amt - $paid_amt; //- $arrears_amt;
		//$due_princ_amt = $paid_amt;
		$due_int_amt = $sched_int - $paid_int; // - $int_amt_arrears;	
		
		$first_instal =0;
		if($due_princ_amt <= 0 && $paid_amt >= 0){
			continue;
		}else{
			$due_princ = $due_princ_amt;
			if($due_int_amt >0){	
				$due_int = $due_int_amt;
			}else
				$due_int=0; 
			
		}
		$total_amt_due =  $due_princ_amt + $due_int_amt;
		$total_due = ($total_amt_due <= 0) ? 0 : $total_amt_due;
		
		
		$pay = "<a href='javascript:;' onclick=\"xajax_add_pay('".$row['id']."', '".$row['applic_id']."', '".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$princ_amt_arrears."', '".$int_amt_arrears."', '".$due_princ_amt."', '".$due_int_amt."',  '".$from_year."', '".$from_month."', '".$from_mday."', '".$to_year."', '".$to_month."', '".$to_mday."', 'due', '".$cheque_no."','".$branch_id."');\">Payments</a>";
		
		$schedule = "<a href='export/schedule?loan_id=".$row['id']."&applic_id=".$row['applic_id']."&cust_name=".$cust_name."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&status=outstanding&format=excel'>Schedule</a>";

		//PENALTY
		$pen_res = mysql_query("select sum(amount) as amount from penalty where status='pending' and loan_id='".$row['id']."'");
		$pen = mysql_fetch_array($pen_res);
		$penalty = ($pen['amount'] == NULL) ? 0 : $pen['amount'];
		
		//$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr><td>".$i."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".$row['date']."</td><td>".$row['account_no']." - ".$row['account_name']."</td><td>".$row['cheque_no']."</td><td>".number_format($row['disbursed_amt'], 2)."</td><td>".number_format($row['balance'], 2)."</td><td>".number_format($princ_arrears, 2)."</td><td>".number_format($int_arrears, 2)."</td><td>".number_format($total_arrears, 2)."</td><td>".number_format($penalty, 2)."</td><td>".number_format($due_princ, 2)."</td><td>".number_format($due_int, 2)."</td><td>".number_format($total_due,2)."</td><td>".$schedule."</td><td>".$pay."</td></tr>";
		$i++;

		// GET SUB TOTALS
		$amt_sub_total += $row['disbursed_amt']; 
		$bal_sub_total += $row['balance']; 
		$princ_arrears_sub_total += $princ_arrears; 
		$int_arrears_sub_total += $int_arrears;
		$tot_arrears_sub_total += $total_arrears;
		$penalty_sub_total += $penalty; 
		$princ_due_sub_total += $due_princ; 
		$int_due_sub_total += $due_int; 
		$tot_amt_sub_total += $total_due;
	}
	// PRINT SUB TOTALS
	$content .= "<tr><td><b>TOTAL</b></td><td></td><td></td><td></td><td></td><td></td><td><b>".number_format($amt_sub_total, 2)."</b></td><td><b>".number_format($bal_sub_total, 2)."</b></td><td><b>".number_format($princ_arrears_sub_total, 2)."</b></td><td><b>".number_format($int_arrears_sub_total, 2)."</b></td><td><b>".number_format($tot_arrears_sub_total, 2)."</b></td><td><b>".number_format($penalty_sub_total, 2)."</b></td><td><b>".number_format($princ_due_sub_total, 2)."</b></td><td><b>".number_format($int_due_sub_total, 2)."</b></td><td><b>".number_format($tot_amt_sub_total, 2)."</b></td><td></td><td></td></tr></tbody></table></div>";
	}
	$resp->call("createTableJs");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


//ARREARS REPORT
function list_arrears($cust_name, $cust_no, $account_name, $loan_officer, $from_date,$to_date, $status,$branch_id){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$resp->assign("status", "innerHTML", "");
	$branch = ($branch_id=='all'||$branch_id=='') ? NULL : "and d.branch_id=".$branch_id;
			
		$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">ARREARS REPORT</h3>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Customer Name:</label>
                                            <input type="text" id="cust_name" value="All" class="form-control">                                            
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Member No:</label>
                                            <input type=text id="cust_no" value="All" class="form-control">
                                        </div>
                                    </div>
                                </div>
        
        <div class="form-group">
        
                 <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Loan Officer:</label>
                                            <select id="officer_id" class="form-control"><option value="0">All';
	$officer_res = mysql_query("select * from employees order by firstName, lastName");
	while($officer = mysql_fetch_array($officer_res)){
		$content .= "<option value='".$officer['employeeId']."'>".$officer['firstName']." ".$officer['lastName'];
	}
	$content .='</select>                                           
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Loan Product:</label>
                                            <select id="account_name" class="form-control"><option value="">All';
	$prod_res = mysql_query("select a.name as account_name, a.account_no as account_no from loan_product p join accounts a on p.account_id=a.id order by a.name, a.account_no");
	while($prod = @ mysql_fetch_array($prod_res)){
		$content .= "<option value='".$prod['account_name']."'>".$prod['account_no']." - ".$prod['account_name'];
	}
	$content .='</select>                                           
                                        </div>
                                    </div>
                                </div>';
                                                              
                $content .='<div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Loan Status:</label>
                                            <select id="state"  class="form-control"><option value="">All<option value="On going">On going<option value="Over Due">Over Due</select>
                                                     
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Branch:</label>
                                            <span>'.branch_rep($branch_id).'</span>
                                              
                                        </div>
                                    </div>
                                </div>
               
               <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Date Range:</label>
                                            <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" placeholder="to" /></div>
                                            </div>                                         </div>                                                                            
                                    </div>
                                </div>                          
                            ';
                                              
	$resp->call("createDate","from_date");
        $resp->call("createDate","to_date");
	
	$content .= "<div class='panel-footer'>                              
                                
                                <input type='button' class='btn  btn-primary' value='Search'  onclick=\"xajax_list_arrears(getElementById('cust_name').value, getElementById('cust_no').value, getElementById('account_name').value, getElementById('officer_id').value, getElementById('from_date').value,  getElementById('to_date').value, getElementById('state').value,getElementById('branch_id').value);\">
                            </div></div>
                        </form>
                        <!--/ Form default layout -->
                    </div></div>";
	
		 //$resp->assign("display_div", "innerHTML", $content);
	if($from_date=='' || $to_date==''){
		$cont = "<font color=red>Please Select The Options For The Arrears Report!</font>";                  
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
	$name = ($cust_name == 'All') ? "" : $cust_name;
	$mem_no = ($cust_no == 'All') ? "" : $cust_no;
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
	if($loan_officer >0)
		$officer = "o.employeeId='".$loan_officer."'";
	else
		$officer = "o.employeeId > 0";
	if($status == 'Over Due')
		$state = " and DATEDIFF(CURDATE(), d.date) >= (d.arrears_period + d.grace_period + d.period)";
	elseif($status == 'On going')
		$state = " and DATEDIFF(CURDATE(), d.date) < (d.arrears_period + d.grace_period + d.period)";
	else
		$state = $status;
	$sth = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, (DATEDIFF(CURDATE(), d.last_pay_date) - d.arrears_period) as arrears_days, datediff(CURDATE(), d.date) as ellapsed_time, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' ".$branch." and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.balance > 0 and d.written_off='0'".$state." ".$branch." order by o.firstName, o.lastName, m.first_name, m.last_name");
	
	if(@ mysql_numrows($sth) == 0){
		$cont = "<font color=red>No arrears in your search options</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
	//$max_page = ceil(mysql_num_rows($sth)/$num_rows);
	$former_officer ="";
	$i=$stat+1;
	// INIT SUB TOTALS
	$amt_sub_total = 0; $prepaid_sub_total = 0; $paid_sub_total = 0; $princ_arrears_sub_total = 0; $int_arrears_sub_total = 0; $penalty_sub_total = 0; $princ_due_sub_total = 0; $int_due_sub_total = 0; $out_standing_sub_total = 0;
	$sth2 =  mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, (DATEDIFF(CURDATE(), d.last_pay_date) - d.arrears_period) as arrears_days, datediff(CURDATE(), d.date) as ellapsed_time, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, d.last_pay_date as last_pay_date, d.pay_freq as pay_freq, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' ".$branch." and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.balance > 0 and d.written_off='0'".$state." ".$branch." order by o.firstName, o.lastName, m.first_name, m.last_name");

	$total_amount = mysql_fetch_assoc(mysql_query("select sum(d.amount) as amount,  sum(d.balance) as balance from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' ".$branch." and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.balance > 0 and d.written_off='0'".$state." ".$branch." order by o.firstName, o.lastName, m.first_name, m.last_name"));
	while($row = mysql_fetch_array($sth2)){
		$officer = $row['of_firstName']." ".$row['of_lastName'];
		if(strcmp($former_officer, $officer) != 0){
						
			$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">                                
                                 <p><h3 class="semibold text-primary mt0 mb5">ARREARS REPORT</h3></p>
                            <p><h5>LOAN OFFICER:&nbsp;<font color="#00BFFF">'.$officer.'</font></h5></p>
                                <p><h5>TOTAL DISBURSED:&nbsp;<font color="#00BFFF">'.number_format($total__amount['amountd'],2).'</font></h5></p>
                                 <p><h5>TOTAL BALANCE:&nbsp;<font color="#00BFFF">'.number_format($total__amount['balance'],2).'</font></h5></p>
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
 		$content .= '<thead><th>#</th><th>NAME</th><th>MEMBER NO.</th><th>DATE</th><th>PRODUCT</th><th>AMOUNT</th><th>AMOUNT PAID</th><th>AMOUNT PREPAID</th><th>PRINC ARREAS</th><th>INT ARREARS</th><th>PENALTY</th><th>PRINC DUE</th><th>INT DUE</th><th>OUTSTANDING BALANCE</th><th>REPAYMENT RATE(%)</th><th>ARREARS RATE(%)</th><th>CUMMULATED REPAYMENT RATE(%)</th><th>CUMMULATED ARREARS RATE(%)</th><th>PENELISE/WRITE-OFF</th></thead><tbody>';
		
			$former_officer = $officer;						
		}

		//RESCHEDULE INTEREST FOR DECLINING BALANCE METHOD
		if($row['int_method'] == 'Declining Balance'){
			//FIND OUT THE UPPER BOUND OF THE CURRENT PAYMENT PERIOD
			$upper = mysql_fetch_array(mysql_query("select date from schedule where date >= '".$date."' and loan_id='".$row['id']."' order by date asc limit 1"));
			$upper_date = ($upper['date'] == NULL) ? $date : $upper['date'];

			//$sched_res = mysql_query("select id from schedule where loan_id='".$row['id']."' and princ_amt>0 and date> '".$row['last_pay_date']."' and date <= '".$date."'");
			$sched_res = mysql_query("select id from schedule where loan_id='".$row['id']."' and princ_amt>0 and date> '".$row['last_pay_date']."' and date <= '".$upper_date."'");
			while($sched = mysql_fetch_array($sched_res)){
				$new_int = $row['balance'] * (($row['int_rate']/100)/12) * ($row['pay_freq']/30);
				mysql_query("update schedule set int_amt ='".$new_int."' where id='".$sched['id']."'");
			}
		}


		//ARREARS
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id='".$row['id']."' and date < DATE_SUB(CURDATE(), INTERVAL ".$row['arrears_period']." DAY)");
		$sched = @mysql_fetch_array($sched_res);
	
		$paid_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from payment where loan_id='".$row['id']."' and date <= CURDATE()");
		
		$paid = mysql_fetch_array($paid_res);
		$sched_amount = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? 0 : $sched['int_amt'];
		$paid_amt = ($paid['amount'] == NULL) ? 0 : $paid['amount'];
		$arrears_amt = $sched_amount - $paid_amt;  //PRINC IN ARREARS

		$paid_int = ($paid['int_amt'] == NULL) ? 0 : $paid['int_amt'];   //INTEREST
		$int_arrears = $sched_int - $paid_int;
		
		if($arrears_amt <= 0){   //PRINC IN ARREARS
			$princ_arrears ="--";
			$int_arrears = "--";
			$total_arrears ="--";
			$total_amt_arrears = 0;
			$int_amt_arrears =0;
			$princ_amt_arrears =0;
		}else{
			$princ_arrears = $arrears_amt;
			$princ_amt_arrears = $arrears_amt;
		}
		if($int_arrears <= 0){  //INT IN ARREARS
			$int_arrears = 0;
			$int_amt_arrears =0;
		}else
			$int_amt_arrears = $int_arrears;

		$total_arrears = $princ_amt_arrears + $int_amt_arrears;
		$total_amt_arrears = $total_arrears;

		//CALCULATE DUE PRINCIPAL CUMMULATIVE
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id='".$row['id']."' and date <= CURDATE()");
		
		$sched = @mysql_fetch_array($sched_res);
		$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? 0 : $sched['int_amt'];
		$prepaid = $paid_amt - $sched_amt;
		$prepaid_amt = $prepaid;
		$prepaid = ($prepaid <= 0) ? 0: $prepaid;
		$due_princ_amt = $sched_amt - $paid_amt - $arrears_amt + $prepaid_amt;
		$due_princ_amt = $sched_amt - $paid_amt; // - $arrears_amt + $prepaid_amt;
		$due_int_amt = $sched_int  -$paid_int;
		if($due_princ_amt <= 0){    //PRINC DUE
			$due_princ = 0;
		}else{
			$due_princ = $due_princ_amt;
		}
		if($due_int_amt <= 0){	  //INTEREST DUE
			$due_int = 0;
			$due_int_amt=0;
		}else{
			$due_int = $due_int_amt;
		}
		//CUMMULATIVE RATES
		//CHECK WHETHER ALL LOAN IS OVER DUE
		if($row['ellapsed_time'] >= $row['loan_period'] + $row['arrears_period']){
			$cumm_repay_rate = 0.00;
			$cumm_arrears_rate = 0.00;
			$repay_rate = 0.00;
			$arrears_rate = 0.00;
			$writeoff_link = "<br><a href='javascript:;' onclick=\"xajax_write_off('".$row['id']."', '".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."', '".$to_date."', '".$status."', '".$branch_id."');\">Write-off</a>";
		}else{
			$writeoff_link="";
			$sched_amt_den = ($sched_amt == 0) ? 1 : $sched_amt; 
			$cumm_repay_rate = ($paid_amt * 100) / $sched_amt_den;
			$cumm_repay_rate = sprintf("%.02f", $cumm_repay_rate);
			if($cumm_repay_rate >= 100.00)
				$cumm_arrears_rate = 0.00;
			else
				$cumm_arrears_rate = 100.00 - $cumm_repay_rate;
			$nowsched_res = mysql_query("select * from schedule where loan_id='".$row['id']."' and date < CURDATE() order by date desc limit 1");
			$nowsched = mysql_fetch_array($nowsched_res);
			$nowpaid_res = mysql_query("select sum(princ_amt) as princ_amt from payment where loan_id='".$row['id']."' and date >= '".$nowsched['date']."' and date < DATE_ADD(date, INTERVAL 30 DAY)");
			
			$nowpaid = mysql_fetch_array($nowpaid_res);
			$nowpaid_amt = ($nowpaid['princ_amt'] == NULL) ? 0 : $nowpaid['princ_amt'];
			$nowsched_amt_den = ($nowsched['princ_amt'] == NULL || $nowsched['princ_amt']==0) ? 1 : $nowsched['princ_amt'];
			$repay_rate = $nowpaid_amt / $nowsched_amt_den;
			$repay_rate = sprintf("%.02f", $repay_rate);
			$arrears_rate = 100.00 - $repay_rate;
		}
		//PENALTY
		$pen_res = mysql_query("select sum(amount) as amount from penalty where loan_id='".$row['id']."' and status='pending'");
		$pen = mysql_fetch_array($pen_res);
		$penalty = ($pen['amount'] == NULL) ? "--" : $pen['amount'];

		$penalise_link = "<a href='javascript:;' onclick=\"xajax_add_penalty('".$row['id']."', '".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."', '".$to_date."', '".$status."','".$branch_id."');\">Penalise</a>";
		//$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr><td>".$i."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".$row['date']."</td><td>".$row['account_no']." - ".$row['account_name']."</td><td>".number_format($row['amount'], 2)."</td><td>".number_format($paid_amt, 2)."</td><td>".number_format($prepaid, 2)."</td><td>".number_format($princ_arrears, 2)."</td><td>".number_format($int_arrears, 2)."</td><td>".number_format($penalty, 2)."</td><td>".number_format($due_princ, 2)."</td><td>".number_format($due_int, 2)."</td><td>".number_format($row['balance'], 2)."</td><td>".$repay_rate."</td><td>".$arrears_rate."</td><td>".$cumm_repay_rate. "</td><td>".$cumm_arrears_rate."</td><td>".$penalise_link. $writeoff_link."</td></tr>";
		$i++;
		// GET SUB TOTALS
		$amt_sub_total += $row['amount']; 
		$prepaid_sub_total += $prepaid; 
		$paid_sub_total += $paid_amt; 
		$princ_arrears_sub_total += $princ_arrears; 
		$int_arrears_sub_total += $int_arrears; 
		$penalty_sub_total += $penalty; 
		$princ_due_sub_total += $due_princ; 
		$int_due_sub_total += $due_int; 
		$out_standing_sub_total += $row['balance'];
	}
	// PRINT SUB TOTALS
	$content .= "<tr><td><b>TOTAL</b></td><td></td><td></td><td></td><td></td><td><b>".number_format($amt_sub_total, 2)."</b></td><td><b>".number_format($paid_sub_total, 2)."</b></td><td><b>".number_format($prepaid_sub_total, 2)."</b></td><td><b>".number_format($princ_arrears_sub_total, 2)."</b></td><td><b>".number_format($int_arrears_sub_total, 2)."</b></td><td><b>".number_format($penalty_sub_total, 2)."</b></td><td><b>".number_format($princ_due_sub_total, 2)."</b></td><td><b>".number_format($int_due_sub_total, 2)."</b></td><td><b>".number_format($out_standing_sub_total, 2)."</b></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table></div>";
	
	$resp->call("createTableJs");
	 }
	 }
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//WRITE-OFF A LOAN
function write_off($loan_id, $cust_name, $cust_no, $account_name, $loan_officer, $from_date,$to_date, $status, $branch_id){
	$resp = new xajaxResponse();
	/*if($_SESSION['position'] <>'Manager'){
		$resp->alert("Access Denied! \nOnly the Manager can manage account");
		return $resp;
	}*/
	$resp->confirmCommands(1, "Do you really want to write-off this loan?");
	$resp->call('xajax_write_off2', $loan_id, $cust_name, $cust_no, $account_name, $loan_officer, $from_date, $to_date, $status,$branch_id);
	return $resp;
}

//CONFIRM WRITE-OFF OF LOAN
function write_off2($loan_id, $cust_name, $cust_no, $account_name, $loan_officer, $from_date,$to_date, $status,$branch_id){
	$resp = new xajaxResponse();
	$loan_res = mysql_query("select * from disbursed  where id='".$loan_id."'");
	$loan = mysql_fetch_array($loan_res);
	start_trans();
	$branch_id_insert = $loan['branch_id'];
	
	if(! mysql_query("insert into written_off set loan_id='".$loan_id."', amount='".$loan['balance']."', balance='".$loan['balance']."', date=CURDATE(), branch_id='".$branch_id_insert."'")){
		$resp->alert("ERROR: Could not write-off the Loan, kindly contact FLT Support Team");
		rollback();
		return $resp;
	}

	if(! mysql_query("update disbursed set written_off='1' where id='".$loan_id."'")){
		$resp->alert("ERROR: Could not update loan, kindly contact FLT Support Team");
		rollback();
		return $resp;
	}
	if($_SESSION['commit']==1){
		////////////
		$action = "insert into written_off set loan_id='".$loan_id."', amount='".$loan['balance']."', balance='".$loan['balance']."', date=CURDATE()";
		$msg = "Registered a written_off for amount:".$loan['balance']." for customer: ".$cust_name." - ".$cust_no;
		log_action($_SESSION['user_id'],$action,$msg);
	////////////
		commit();
		$resp->call('xajax_list_arrears', $cust_name, $cust_no, $account_name, $loan_officer, $from_date,$to_date, $status, $branch_id);
		$resp->assign("status", "innerHTML", "<font color=red>Loan written off!</font>");
	}
	return $resp;
}


//LIST OF WRITTEN OFF LOANS
function list_written_off($cust_name, $cust_no, $account_name, $loan_officer, $from_date, $to_date,$branch_id){
        list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	$branch=($branch_id=='all'||$branch_id=='') ? NULL : "and d.branch_id=".$branch_id;
			
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR WRITTEN-OFF LOANS</h3>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Customer Name:</label>
                                            <input type="text" id="cust_name" value="All" class="form-control">                                            
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Member No:</label>
                                            <input type=text id="cust_no" value="All" class="form-control">
                                        </div>
                                    </div>
                                </div>
        
        <div class="form-group">
        
                 <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Loan Officer:</label>
                                            <select id="officer_id" class="form-control"><option value="0">All';
	$officer_res = mysql_query("select * from employees order by firstName, lastName");
	while($officer = mysql_fetch_array($officer_res)){
		$content .= "<option value='".$officer['employeeId']."'>".$officer['firstName']." ".$officer['lastName'];
	}
	$content .='</select>                                           
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Loan Product:</label>
                                            <select id="account_name" class="form-control"><option value="">All';
	$prod_res = mysql_query("select a.name as account_name, a.account_no as account_no from loan_product p join accounts a on p.account_id=a.id order by a.name, a.account_no");
	while($prod = @ mysql_fetch_array($prod_res)){
		$content .= "<option value='".$prod['account_name']."'>".$prod['account_no']." - ".$prod['account_name'];
	}
	$content .='</select>                                           
                                        </div>
                                    </div>
                                </div>';
                                
                $content .='<div class="form-group">
                                    <div class="row">                                        
                                        <div class="col-sm-6">
                                            <label class="control-label">Date Range:</label>
                                            <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" placeholder="to" /></div>
                                            </div>      
                                        </div>
              		 <div class="col-sm-6">
                                            <label class="control-label">Branch:</label>
                                            <span>'.branch_rep($branch_id).'</span>
                                        </div>                            
                                                                              
                                    </div>
                                </div>                          
                            '; 
                $resp->call("createDate","from_date");
                $resp->call("createDate","to_date");                              
	
	
	$content .= "<div class='panel-footer'>                              
                                
                         <input type='button' class='btn  btn-primary' value='Search'  onclick=\"xajax_list_written_off(getElementById('cust_name').value, getElementById('cust_no').value, getElementById('account_name').value, getElementById('officer_id').value, getElementById('from_date').value, getElementById('to_date').value,getElementById('branch_id').value);\">
                            </div></div>
                        </form>
                        <!--/ Form default layout -->
                    </div></div>";	
         //$resp->assign("display_div", "innerHTML", $content); 
         
	if($from_date=='' || $to_date==''){
		$cont = "<font color=red>Please select the Written loans you would like to list!</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
	$name = ($cust_name == 'All') ? "" : $cust_name;
	$mem_no = ($cust_no == 'All') ? "" : $cust_no;
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
	if($loan_officer >0)
		$officer = "o.employeeId='".$loan_officer."'";
	else
		$officer = "o.employeeId > 0";
	
	$sth = mysql_query("select w.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.amount as disburse_amount, d.date as disburse_date, w.balance as write_balance,  d.bank_account as bank_account, w.amount as write_amount, w.date as write_date from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId join written_off w on w.loan_id=d.id where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' ".$branch." and d.written_off='1'");
	
	if(@ mysql_numrows($sth) == 0){
		$cont = "<font color=red>No loans written off in your search options</font>";
		$resp->assign("status", "innerHTML", $cont);
		return $resp;
	}
	////Error_point
	$sth2 = mysql_query("select w.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.amount as disburse_amount, d.date as disburse_date, w.balance as write_balance,  d.bank_account as bank_account, w.amount as write_amount, w.date as write_date from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId join written_off w on w.loan_id=d.id where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' ".$branch." and d.written_off='1'") or trigger_error(mysql_error());
	
	//$max_page = ceil(mysql_num_rows($sth)/$num_rows);
	$former_officer ="";
	$i=$stat+1;
	//////
	while($row = mysql_fetch_array($sth2)){
		$officer = $row['of_firstName']." ".$row['of_lastName'];
		if(strcmp($former_officer, $officer) != 0){
						
			$content = '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                            <p><h3 class="semibold text-primary mt0 mb5">LIST OF WRITTEN-OFF LOANS</h3></p>
                            <p><h5>LOAN OFFICER:&nbsp;<font color="#00BFFF">'.$officer.'</font></h5></p>
                               
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
 		$content .= '<thead><th>#</th><th>DATE DISBURSED</th><th>MEMBER NO.</th><th>NAME</th><th>PRODUCT</th><th>DISBURSED AMOUNT</th><th>AMOUNT WRITTEN-OFF</th><th>WRITTEN-OFF DATE</th><th>WRITTEN-OFF BALANCE</th><th>RECOVER</th><th>UNWRITE-OFF</th><th>PENALTY</th><th>PRINC DUE</th><th>INT DUE</th><th>TOTAL AMOUNT DUE</th><th>SCHEDULE</th><th>PAY</th></thead><tbody>';
		
			$former_officer = $officer;
		}
		if($row['write_balance'] != $row['write_amount'])
			$unwrite = "Partly Recovered";
		elseif($row['write_balance'] == 0)
			$unwrite = "Recovered";
		else
			$unwrite = "<a href='javascript:;' onclick=\"xajax_unwrite_off('".$row['id']."', '".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."','".$to_date."','".$branch_id."');\">Unwrite-off</a>";
		$recover = "<a href='javascript:;' onclick=\"xajax_add_recovered('".$row['id']."', '".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."', '".$to_date."','".$branch_id."')\">Recover</a>";
		//$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr><td>".$i."</td><td>".$row['disburse_date']."</td><td>".$row['mem_no']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['account_no']." - ".$row['account_name']."</td><td>".number_format($row['disburse_amount'], 2)."</td><td>".number_format($row['write_amount'], 2)."</td><td>".$row['write_date']."</td><td>".number_format($row['write_balance'], 2)."</td><td>".$recover."</td><td>".$unwrite."</td></tr>";
		$i++;
	}
	$content="</tbody></table></div>";
	$resp->call("createTableJs");
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//UNWRITE-OFF A LOAN
function unwrite_off($written_id, $cust_name, $cust_no, $account_name, $loan_officer, $from_date, $to_date, $branch_id){
	$resp = new xajaxResponse();
	/*if($_SESSION['position'] <>'Manager'){
		$resp->alert("Access Denied! \nOnly the Manager can manage account");
		return $resp;
	}*/
	$rec_res = mysql_query("select * from recovered where written_off_id='".$written_id."'");
	if(@ mysql_numrows($rec_res) > 0){
		$resp->alert("You cannot unwrite off this loan, it has been partially recovered");
		return $resp;
	}
	$resp->confirmCommands(1, "Do you really want to unwrite-off this loan?");
	$resp->call('xajax_unwrite_off2', $written_id, $cust_name, $cust_no, $account_name, $loan_officer, $from_date,$to_date, $branch_id);
	return $resp;
}

//CONFIRM UNWRITE-OFF 
function unwrite_off2($written_id, $cust_name, $cust_no, $account_name, $loan_officer, $from_date, $to_date, $branch_id){
	
	$resp = new xajaxResponse();
	$loan_res = mysql_query("select loan_id from written_off where id='".$written_id."'");
	$loan = mysql_fetch_array($loan_res);
	start_trans();
	if(! mysql_query("update disbursed set written_off='0' where id='".$loan['loan_id']."'")){
		$resp->alert("ERROR: Could not update the loan");
		rollback();
		return $resp;
	}
	if(! mysql_query("delete from written_off where id='".$written_id."'")){
		$resp->alert("ERROR: Could not delete the write-off");
		rollback();
		return $resp;
	}
	if($_SESSION['commit'] == 1){
		/////////////////////////
		$action = "delete from written_off where id='".$written_id."'";
		$msg = "Deleted a written off of amount:".$loan['amount']." for customer: ".$cust_name." - ".$cust_no;
		log_action($_SESSION['user_id'],$action,$msg);
		///////////////////////
		commit();
		$resp->call('xajax_list_written_off', $cust_name, $cust_no, $account_name, $loan_officer, $from_date,$to_date, $branch_id);
		$resp->assign("status", "innerHTML", "<font color=red>Write-off deleted!</font>");
	}
	return $resp;
}

//ENTER A RECOVERED LOAN
function add_recovered($written_id, $cust_name, $cust_no, $account_name, $loan_officer, $from_date, $to_date,$branch_id){
	$resp = new xajaxResponse();	
	$applic_res = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as disburse_amount, d.date as disburse_date, w.balance as write_balance,  d.bank_account as bank_account, w.amount as write_amount, w.date as write_date from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId join written_off w on w.loan_id=d.id where w.id='".$written_id."'");
	
	$applic = mysql_fetch_array($applic_res);
	$rec_res = mysql_query("select sum(amount) as amount from recovered where written_off_id='".$written_id."'");
	$rec = mysql_fetch_array($rec_res);
	$rec_amt = ($rec['amount'] == NULL) ? "--" : $rec['amount'];
	$content .= "<center><font color=#00008b size=3pt><b>RECOVERED FROM WRITTEN-OFF LOAN OF  ".strtoupper($applic['first_name'])." ".strtoupper($applic['last_name'])."</b></font></center>
	<table height=100 border='1' cellpadding='0' height=70 cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='50%' id='AutoNumber2' align=center>
		<tr bgcolor=lightgrey><td>Member No:</td><td>".$applic['mem_no']."</td></tr>
		<tr bgcolor=white><td>Loan Product:</td><td>".$applic['account_no']." - ".$applic['account_name']."</td></tr>
		<tr bgcolor=lightgrey><td>Amount Written-off:</td><td>".$applic['write_amount']."</td></tr>
		<tr bgcolor=white><td>Write-off Date:</td><td>".$applic['write_date']."</td></tr>
		<tr bgcolor=lightgrey><td>Total Recovered:</td><td>".$rec_amt."</td></tr>
		<tr bgcolor=white><td>Balance:</td><td>".$applic['write_balance']."</td></tr>
	</table><br>
	<table height=100 border='1' cellpadding='0' height=70 cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='80%' id='AutoNumber2' align=center>";
	$sth = mysql_query("select * from recovered where written_off_id='".$written_id."' order by date asc");
	if(@ mysql_numrows($sth) == 0){
		$content .= "<tr BGCOLOR=lightgrey><td><font color=red>No recovered income from this loan yet</font></td></tr>";
	}else{
		$content .= "<tr class='headings'><td><b>No</b></td><td><b>Date</b></td><td><b>Begin Balance</b></td><td><b>Amount</b></td><td><b>ReceiptNo</b></td><td><b>End Balance</b></td><td><b>Edit</b></td></tr>";
		$i=1;
		$num = mysql_numrows($sth);
		$balance = $applic['write_amount'];
		while($row = mysql_fetch_array($sth)){
			$edit = "<a href='javascript:;' onclick=\"xajax_delete_recovered('".$row['id']."', '".$written_id."', '".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."','".$to_date."','".$branch_id."');\">Delete</a>";
			$new_balance = $balance - $row['amount'];
			//$color = ($i%2 == 0) ? "lightgrey" : "white";
			$content .= "<tr><td>".$i."</td><td>".$row['date']."</td><td>".$balance."</td><td>".$row['amount']."</td><td>".$row['receipt_no']."</td><td>".$new_balance."</td><td>".$edit."</td></tr>";
			$i++;
			$balance = $balance - $row['amount'];
		}
	}

	$content .= "</table><br><center><font color=#00008b size=3pt><b>REGISTER RECOVERED INCOME </b></font></center>
	<form method=post><table height=100 border='0' cellpadding='0' height=70 cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='50%' id='AutoNumber2' align=center>";	
	$content .="<tr bgcolor=lightgrey><td>Amount</td><td><input type=int id='amount'></td></tr>
		<tr bgcolor=white><td>ReceiptNo</td><td><input type=int id='receipt_no'></td></tr>
		<tr bgcolor=lightgrey><td>Date</td><td>".month_array('', '', '') . mday('', '')."</td></tr>";

	if (strtolower($_SESSION['position']) == 'manager')
		$bank_res = mysql_query("select a.name as account_name, a.account_no as account_no, b.bank as bank, b.id as bank_account_id from bank_account b join accounts a on b.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."'");
	else
		$bank_res = mysql_query("select a.name as account_name, a.account_no as account_no, b.bank as bank, b.id as bank_account_id from bank_account b join accounts a on b.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."' and b.id in (select bank_account_id from user_account where user_id =".$_SESSION['user_id'].")");
	$content .= "<tr bgcolor=white><td>Bank/Cash Account</td><td><select id='bank_account_id'>";
	//$bank_res = mysql_query("select a.name as account_name, a.account_no as account_no, bank.id as id from bank_account bank join accounts a on bank.account_id=a.id where bank.id<>'".$former['bank_account_id']."'");
	while($bank = @ mysql_fetch_array($bank_res)){
		$content .= "<option value='".$bank['bank_account_id']."'>".$bank['account_no'] ." - ".$bank['account_name'];
	}
	$content .= "</select></td></tr>";
	$back="<a href='javascript:;' onclick=\"xajax_list_written_off('".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."','".$to_date."','".$branch_id."');\"><img src='images/btn_back.jpg'></a>";
	
	$content .="<tr><td></td><td>".$back."<a href='javascript:;' onclick=\"xajax_insert_recovered('".$written_id."', getElementById('amount').value,  getElementById('receipt_no').value, getElementById('year').value, getElementById('month').value, getElementById('mday').value, '".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."','".$to_date."', getElementById('bank_account_id').value, '".$branch_id."')\"><img src='images/btn_enter.jpg'></a></td></tr>
	</table></form>";
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//INSERT A RECOVERED 
function insert_recovered($written_id, $amount,  $receipt_no, $date, $cust_name, $cust_no, $account_name, $loan_officer, $from_date, $to_date, $bank_account_id, $branch_id){

	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	if($amount == NULL || $receipt_no == NULL || $bank_account_id==NULL)
		$resp->alert("You may not leave the any field blank");
	elseif(preg_match("/\D/", $amount))
		$resp->alert("Please for Amount, enter digits only");
	elseif(!$calc->isValidDate($mday, $month, $year))
		$resp->alert("Recovered income rejected! Please enter valid date");
	elseif($calc->isFutureDate($mday, $month, $year))
		$resp->alert("Recovered income rejected! You have entered a future date");
	else{
		//$date = sprintf("%d-%02d-%02d", $year, $month, $mday);	
		$date = $date." ".date('H:i:s');
		$test_res = mysql_query("select * from written_off where id='".$written_id."' and date<'".$date."'");
		if(@ mysql_numrows($test_res)){
			$resp->alert("Recovered income rejected!\n Date entered is earlier than the write-off date");
			return $resp;
		}
		$rec_res = mysql_query("select receipt_no from collected where receipt_no ='".$receipt_no."' union select receipt_no from payment where receipt_no ='".$receipt_no."' union select receipt_no from other_income where receipt_no ='".$receipt_no."' union select receipt_no from deposit where receipt_no ='".$receipt_no."' union select receipt_no from shares where receipt_no='".$receipt_no."' union select receipt_no from sold_asset where receipt_no='".$receipt_no."'");
		if(@ mysql_numrows($rec_res) >0){
			$resp->alert("ERROR: The receiptNo already exists");
			return $resp;
		}
		$bank_res = mysql_query("select b.id as bank_account_id,  w.balance - ".$amount." as balance from written_off w join disbursed d on w.loan_id=d.id join bank_account b on d.bank_account=b.id where w.id='".$written_id."'");
		$bank = mysql_fetch_array($bank_res);
		start_trans();
		if(! mysql_query("update bank_account set account_balance=account_balance + ".$amount. " where id='".$bank_account_id."'")){
			$resp->alert("ERROR: Could not update bank account balance");
			rollback();
			return $resp;
		}
		
		$written = mysql_fetch_array(mysql_query("select * from written_off where id='".$written_id."'"));
		$branch_id_insert = $written['branch_id'];
		if(! mysql_query("insert into recovered set amount='".$amount."', date='".$date."', receipt_no='".$receipt_no."', written_off_id='".$written_id."', balance=".$bank['balance'].", bank_account='$bank_account_id', branch_id=".$branch_id_insert)){
			$resp->alert("ERROR: Could not insert the recovered income");
			rollback();
			return $resp;
		}
		if(! mysql_query("update written_off set balance=balance- ".$amount." where id='".$written_id."'")){
			$resp->alert("ERROR: Could not update the written off loan");
			rollback();
			return $resp;
		}
		if($_SESSION['commit'] == 1){
			commit();
			/////////////////////
			$action = "insert into recovered set amount='".$amount."', date='".$date."', receipt_no='".$receipt_no."', written_off_id='".$written_id."', balance=".$bank['balance'].", bank_account='$bank_account_id'";
			$msg = "Recovered amount:".$amount." from customer:".$cust_name." - ".$cust_no;
			log_action($_SESSION['user_id'],$action,$msg);
			//////////////////
			$resp->call('xajax_add_recovered', $written_id, $cust_name, $cust_no, $account_name, $loan_officer, $from_date,$to_date, $branch_id);
			$resp->assign("status", "innerHTML", "<font color=red>Recovered income regestered!</font>");
		}
	}
	return $resp;
}

//DELETE A RECOVERED INCOME
function delete_recovered($recovered_id, $written_id, $cust_name, $cust_no, $account_name, $loan_officer, $from_date,$to_date,$branch_id){
	$resp = new xajaxResponse();
	/*if($_SESSION['position'] <>'Manager'){
		$resp->alert("Access Denied! \nOnly the Manager can manage account");
		return $resp;
	}*/
	$rec_res = mysql_query("select * from recovered where id='".$recovered_id."'");
	$rec = mysql_fetch_array($rec_res);
	$bank_res = mysql_query("select b.account_balance as account_balance, b.min_balance as min_balance from written_off w join disbursed d on w.loan_id=d.id join bank_account b on d.bank_account=b.id where w.id='".$written_id."'");
	$bank = mysql_fetch_array($bank_res);
	/*
	if($bank['account_balance'] - $rec['amount'] < $bank['min_balance']){
		$resp->alert("Recovered income not deleted! \n Account balance would go below the set minimum balance");
		return $resp;
	}
	*/
	$resp->confirmCommands(1, "Do you really want to delete?");
	$resp->call('xajax_delete2_recovered', $recovered_id, $written_id, $cust_name, $cust_no, $account_name, $loan_officer, $from_date,$to_date, $branch_id);
	return $resp;
}

function delete2_recovered($recovered_id, $written_id, $cust_name, $cust_no, $account_name, $loan_officer, $from_date,$to_date, $branch_id){
	$resp = new xajaxResponse();
	$rec_res = mysql_query("select * from recovered where id='".$recovered_id."'");
	$rec = mysql_fetch_array($rec_res);
	start_trans();
	if(! mysql_query("update written_off set balance=balance + ".$rec['amount']." where id='".$written_id."'")){
		$resp->alert("ERROR: Could not update  the written off loan");
		rollback();
		return $resp;
	}
	$bank_res = mysql_query("select bank_account from recovered where id='$recovered_id'");
	$bank = mysql_fetch_array($bank_res);
	if(! mysql_query("update bank_account set account_balance=account_balance - ".$rec['amount']." where id='".$bank['bank_account']."'")){
		$resp->alert("ERROR: Could not update bank account balance");
		rollback();
		return $resp;
	}
	if(! mysql_query("delete from recovered where id='".$recovered_id."'")){
		$resp->alert("ERROR: Could not delete the recovered income");
		rollback();
		return $resp;
	}
	if($_SESSION['commit'] == 1){
		/////////////////
		$action = "delete from recovered where id='".$recovered_id."'";
		$msg = "Deleted from recovered amount:".$rec['amount']." from member".$cust_name." - ".$cust_no;
		log_action($_SESSION['user_id'],$action,$msg);
		////////////
		commit();
		$resp->call('xajax_add_recovered', $written_id, $cust_name, $cust_no, $account_name, $loan_officer, $from_date,$to_date, $branch_id);
		$resp->assign("status", "innerHTML", "<font color=red>Recovered income deleted!</font>");
	}
	return $resp;
}

//LIST CLEARED LOANS
function list_cleared($cust_name, $cust_no, $account_name, $loan_officer, $from_date,$to_date, $branch_id){ 

        list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$resp->assign("status", "innerHTML", "");
	$branch = ($branch_id=='all'||$branch_id=='')?NULL:"and d.branch_id=".$branch_id;
		
         $content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR CLEARED LOANS</h3>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Customer Name:</label>
                                            <input type="text" id="cust_name" value="All" class="form-control">                                            
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Member No:</label>
                                            <input type=text id="cust_no" value="All" class="form-control">
                                        </div>
                                    </div>
                                </div>
        
        <div class="form-group">
        
                 <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Loan Officer:</label>
                                            <select id="officer_id" class="form-control"><option value="0">All';
	$officer_res = mysql_query("select * from employees order by firstName, lastName");
	while($officer = mysql_fetch_array($officer_res)){
		$content .= "<option value='".$officer['employeeId']."'>".$officer['firstName']." ".$officer['lastName'];
	}
	$content .='</select>                                           
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Loan Product:</label>
                                            <select id="account_name" class="form-control"><option value="">All';
	$prod_res = mysql_query("select a.name as account_name, a.account_no as account_no from loan_product p join accounts a on p.account_id=a.id order by a.name, a.account_no");
	while($prod = @ mysql_fetch_array($prod_res)){
		$content .= "<option value='".$prod['account_name']."'>".$prod['account_no']." - ".$prod['account_name'];
	}
	$content .='</select>                                           
                                        </div>
                                    </div>
                                </div>';
                                
                $content .='<div class="form-group">
                                    <div class="row">                                        
                                        <div class="col-sm-6">
                                            <label class="control-label">Date Range:</label>
                                            <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" placeholder="to" /></div>
                                            </div>      
                                        </div>
                                           <div class="col-sm-6">
                                            <label class="control-label">Branch:</label>
                                            <span>'.branch_rep($branch_id).'</span>
                                        </div>                                   
                                    </div>
                                </div>                          
                            '; 
                                              
	
	        $resp->call("createDate","from_date");
                $resp->call("createDate","to_date");
	
	$content .= "<div class='panel-footer'>                              
                                
                         <button type='button' class='btn  btn-primary' onclick=\"xajax_list_cleared(getElementById('cust_name').value, getElementById('cust_no').value, getElementById('account_name').value, getElementById('officer_id').value, getElementById('from_date').value, getElementById('to_date').value,getElementById('branch_id').value);\">Search</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
            // $resp->assign("display_div", "innerHTML", $content);        
	if($from_date=='' || $from_date==''){
		$cont = "<font color=red>Please select the Cleared loans you would like to list!</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
	$name = ($cust_name == 'All') ? "" : $cust_name;
	$mem_no = ($cust_no == 'All') ? "" : $cust_no;
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
	if($loan_officer >0)
		$officer = "o.employeeId='".$loan_officer."'";
	else
		$officer = "o.employeeId > 0";
	
	$sth = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, (d.period/30) as loan_period,  DATEDIFF(d.last_pay_date, d.date) as actual_days from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.balance <=0 " .$branch." order by o.firstName, o.lastName");
	
	if(@ mysql_numrows($sth) == 0){
		$cont = "<font color=red>No cleared loans in your search options</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
	$former_officer ="";
	$i=$stat+1;
	// INIT Sub totals
	$amt_sub_total = 0; $tot_int_sub_total = 0; $tot_pen_sub_total = 0;
	$sth2 = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, (d.period/30) as loan_period,  DATEDIFF(d.last_pay_date, d.date) as actual_days from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.balance <=0 " .$branch." order by o.firstName, o.lastName");
	//$max_page = ceil(mysql_num_rows($sth)/$num_rows);
$total_amount = mysql_fetch_assoc(mysql_query("select  sum(d.amount) as amount,sum(d.balance) as balance from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.balance =0 " .$branch." order by o.firstName, o.lastName"));
	while($row = mysql_fetch_array($sth2)){
		$officer = $row['of_firstName']." ".$row['of_lastName'];
		if(strcmp($former_officer, $officer) != 0){
								
	        $content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                               <p><h4 class="semibold text-primary mt0 mb5">LIST OF CLEARED LOANS</h4></p>
                                <p><h5>LOAN OFFICER:&nbsp;<font color="#00BFFF">'.$officer.'</font></h5></p>
                                <p><h5>TOTAL DISBURSED:&nbsp;<font color="#00BFFF">'.number_format($total__amount['amountd'],2).'</font></h5></p>
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
 		$content .= '<thead><th>#</th><th>DATE</th><th>MEMBER NO.</th><th>NAME</th><th>PRODUCT</th><th>AMOUNT</th><th>TOTAL INTEREST PAID</th><th>TOTAL PENALTIES PAID</th><th>LOAN PERIOD AWARDED</th><th>ACTUAL PERIOD TAKEN</th><th>SCHEDULE</th><th>PAYMENTS</th></thead><tbody>';
		
			$former_officer = $officer;
		}
		//INTEREST
		$int_res = mysql_query("select sum(int_amt) as int_amt from payment where loan_id='".$row['id']."'");
		$int = mysql_fetch_array($int_res);
		//PENALTIES
		$pen_res = mysql_query("select * from penalty where loan_id='".$row['id']."'");
		$pen = mysql_fetch_array($pen_res);
		$pen_amt = ($pen['amount'] == NULL) ? 0: $pen['amount']; 
		//ACTUAL PERIOD TAKEN
		$actual_months = floor($row['actual_days']/30);
		$actual_days = $row['actual_days'] % 30;
		$actual_period = $actual_months. " Months, ". $actual_days. " Days";
		
		$pay = "<a href='javascript:;' onclick=\"xajax_add_pay('".$row['id']."', '".$row['applic_id']."', '".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$princ_amt_arrears."', '".$int_amt_arrears."', '".$due_princ_amt."', '".$due_int_amt."',  '".$from_year."', '".$from_month."', '".$from_mday."', '".$to_year."', '".$to_month."', '".$to_mday."', 'cleared', '','".$branch_id."');\"><b>Payments</b></a>";
		//$pay = "<a href='list_payments.php?loan_id=".$row['id']."&applic_id=".$row['applic_id']."&cust_name=".$cust_name."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&princ_arrears=0&int_arrears=0&princ_due=0&int_due=0&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&status=cleared' target=blank()><B>Payments</B></a>";
		
		$schedule = "<a href='export/schedule?loan_id=".$row['id']."&branch_id=".$branch_id."&applic_id=".$applic_id."&cust_name=".$cust_name."&cust_no=".$cust_no."&account_name=".$account_name."loan_officer=".$loan_officer."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&status=".$status."&format=excel'><b>Schedule</b></a>";
		//PENALTY
		$pen_res = mysql_query("select sum(amount) as amount from penalty where status='pending' and loan_id='".$row['id']."'");
		$pen = mysql_fetch_array($pen_res);
		$penalty = ($pen['amount'] == NULL) ? "--" : $pen['amount'];
		//$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr><td>".$i."</td><td>".$row['date']."</td><td>".$row['mem_no']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['account_no']." - ".$row['account_name']."</td><td>".number_format($row['amount'], 2)."</td><td>".number_format($int['int_amt'], 2)."</td><td>".number_format($pen_amt, 2)."</td><td>".ceil($row['loan_period'])." Months</td><td>".$actual_period."</td><td>".$schedule."</td><td>".$pay."</td></tr>";
		$i++;
		$amt_sub_total += $row['amount'];
		$tot_int_sub_total += $int['int_amt'];
		$tot_pen_sub_total += $pen_amt;
	}
	// PRINT SUB TOTALS
	$content .= "<tr><td><b>TOTAL</b></td><td></td><td></td><td></td><td></td><td><b>".number_format($amt_sub_total, 2)."</b></td><td><b>".number_format($tot_int_sub_total, 2)."</b></td><td><b>".number_format($tot_pen_sub_total, 2)."</b></td><td></td><td></td><td></td><td></td></tr>";
	$content .= "</tbody></table></div>";
	$resp->call("createTableJs");
	 }
	 }
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


//PRINT REPAYMENT SCHEDULE
function schedule($loan_id, $applic_id,   $cust_name, $cust_no, $account_name, $loan_officer,$from_date, $to_date, $status){
	$resp = new xajaxResponse();
	//PRINT THE REPAYMENT SCHEDULE
	$sth = mysql_query("select date, begin_bal, end_bal, princ_amt, int_amt, (princ_amt + int_amt) as total_amt  from schedule where loan_id='".$loan_id."' order by date asc");
	//if(@ mysql_numrows($sth) > 0){
	$pay_res = mysql_query("select m.branch_id,m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no from loan_applic applic join member m on applic.mem_id=m.id where applic.id='".$applic_id."'");
	$pay = mysql_fetch_array($pay_res);
	$disb_res = mysql_query("select * from disbursed where id='".$loan_id."'");
	$disb = mysql_fetch_array($disb_res);
	$sched_res = mysql_query("select sum(int_amt) as total_int from schedule where loan_id='".$loan_id."'");
	$sched = mysql_fetch_array($sched_res);
	$content = "<br><center><font color=#00008b size=3pt><b>REPAYMENT SCHEDULE</b></font></center>
	<table height=100 border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='80%' id='AutoNumber2' align=center>
		<table height=100 border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='80%' id='AutoNumber2' align=center>
			<tr><td>
			<center><b>Conditions</b></center>
			<table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>
			<tr><td>Member Name</td><td>".$pay['first_name']." ".$pay['last_name']."</td></tr>
			<tr><td>Member No</td><td>".$pay['mem_no']."</td></tr>
			<tr><td>Loan Amount</td><td>".number_format($disb['amount'], 2)."</td></tr>
			<tr><td>Annual Interest Rate (%)</td><td>".$disb['int_rate']."</td></tr>
			<tr><td>Loan Period (Months)</td><td>".($disb['period']/30)."</td></tr>
			<tr><td>Grace Period (Months)</td><td>".($disb['grace_period']/30)."</td></tr>
		</table>
	</td><td>
		<center><b>Loan Summary</b></center>
		<table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>
			<tr><td>Scheduled Payment</td><td>".($disb['next_princ_amt']+$disb['next_int_amt'])."</td></tr>
			<tr><td>Scheduled Number of Payments</td><td>".($disb['period']/30)."</td></tr>
			<tr><td>Total Interest</td><td>".$sched['total_int']."</td></tr>
		</table>
	</td></tr>
	</table>
		<table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='80%' id='AutoNumber2' align=center>
			<tr bgcolor=#cdcdcd><td><b>No</b></td><td><b>Date</b></td><td><b>Beginning Balance</b></td><td><b>Total Payment</b></td><td><b>Principal</b></td><td><b>Interest</b></td><td><b>Ending Balance</b></td></tr>";
	$i=1;
	while($row = mysql_fetch_array($sth)){
		$end_bal = ($row['end_bal'] <= 0) ? "--" : $row['end_bal'];
		$content .= "<tr><td>".$i."</td><td>".$row['date']."</td><td>".number_format($row['begin_bal'], 2)."</td><td>".number_format($row['total_amt'], 2)."</td><td>".number_format($row['princ_amt'], 2)."</td><td>".number_format($row['int_amt'], 2)."</td><td>".number_format($end_bal, 2)."j</td></tr>";
		$i++;
	}
	if($status == 'outstanding')
		$back = "<input type=button value='Back' onclick=\"xajax_list_outstanding('".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."','".$to_date."', '".$branch_id."');\">";
	elseif($status=='cleared')
		$back = "<input type=button value='Back' onclick=\"xajax_list_cleared('".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."','".$to_date."','".$branch_id."');\">";
	$content .= "</table>
	<table height=100 border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='80%' id='AutoNumber2' align=center>
		<tr><td>".$back."</td></tr>
	</table>";
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function add_penalty($loan_id, $cust_name, $cust_no, $account_name, $loan_officer, $from_date, $to_date, $status, $branch_id){
	$resp = new xajaxResponse();
	$applic_res = mysql_query("select m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, d.date as disburse_date, d.amount as amount, d.balance as balance, d.int_method as int_method, d.int_rate as int_rate,  d.arrears_period as arrears_period, d.period as loan_period, d.grace_period as grace_period, datediff(CURDATE(), d.last_pay_date) as since_last, a.account_no as account_no, a.name as account_name from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where d.id='".$loan_id."'");
	
	$applic = mysql_fetch_array($applic_res);
	
	//ARREARS
	$sched_res = mysql_query("select sum(s.princ_amt) as princ_amt from schedule s join disbursed d on s.loan_id=d.id where s.loan_id='".$loan_id."' and s.date <= DATE_SUB(CURDATE(), INTERVAL ".$applic['arrears_period']." DAY)");
	$sched = mysql_fetch_array($sched_res);
	$paid_res = mysql_query("select sum(princ_amt) as amount from payment where loan_id='".$loan_id."' and date <= CURDATE()");
	$paid = mysql_fetch_array($paid_res);
	$paid_amt = ($paid['amount'] == NULL) ? 0 : $paid['amount'];
	$princ_arrears = $sched['princ_amt'] - $paid_amt;

	if($applic['since_last'] <= $applic['arrears_period'])
		$int_arrears = 0;
	else{
		if($applic['int_method'] == 'Declining Balance')
			$int_arrears = ((($applic['balance'] * $applic['int_rate']) /100) /12) * (($applic['since_last'] - $applic['arrears_period'])/30);
		elseif($applic['int_method'] == 'Flat')
			$int_arrears = ((($applic['amount'] * $applic['int_rate']) /100) /12) * (($applic['since_last'] - $applic['arrears_period'])/30);
	}
	$int_arrears = ceil($int_arrears);
	$total_arrears = $int_arrears + $princ_arrears;
	$total_int = $int_arrears + $int_due;
	$pen_res = mysql_query("select status, sum(amount) as amount from penalty where loan_id='".$loan_id."' group by status");
	if(@ mysql_numrows($pen_res) > 0){
		while($pen = @ mysql_fetch_array($pen_res)){
			if($pen['status'] == 'paid'){
				$paid_penalty = ($pen['amount'] == NULL) ? "--" : $pen['amount'];
			}else
				$pending_penalty = ($pen['amount'] == NULL) ? "--" : $pen['amount'];
		}
	}
	$pending_penalty = (! isset($pending_penalty)) ? "--" : $pending_penalty;
	$paid_penalty = (! isset($paid_penalty)) ? "--" : $paid_penalty;
	
	
	
	$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                 <p><h4 class="semibold text-primary mt0 mb5">PENALTIES APPLIED TO THE LOAN OF  '.strtoupper($applic['first_name']).' '.strtoupper($applic['last_name']).'</h4></p>
                <p>Member No: <font color="#00BFFF">'.$applic['mem_no'].'</font></p>
                <p>Loan Product: <font color="#00BFFF">'.$applic['account_no']." - ".$applic['account_name'].'</font></p>
		<p>Amount Disbursed: <font color="#00BFFF">'.$applic['amount'].'</font></p>
		<p>Disbursement Date: <font color="#00BFFF">'.$applic['disburse_date'].'</font></p>
		<p>Principal in Arrears: <font color="#00BFFF">'.$princ_arrears.'</font></p>
		<p>Interest in Arrears: <font color="#00BFFF">'.$int_arrears.'</font></p>
		<p>Total In Arrears: <font color="#00BFFF">'.$total_arrears.'</font></p>
		<p>Total Paid Up Penalty: <font color="#00BFFF">'.$paid_penalty.'</font></p>
		<p>Total Pending Penalty: <font color="#00BFFF">'.$pending_penalty.'</font></p>
                </div>';
	$content .= '<table class="table table-striped table-bordered" id="table-tools">';
 			
	$$sth = mysql_query("select * from penalty where loan_id='".$loan_id."' order by date asc");
	if(@ mysql_numrows($sth) == 0){
		$content .= "<tr><td><font color=red>No Penalties Applied To This Loan Yet</font></td></tr></table></div>";
	}else{	
		                          
		$content .="<thead><th>#</th><th><b>Date</b></th><th><b>Amount</b></th><th><b>Status</b></th><th><b>Edit</b></th></thead><tbody>";
		$i=1;
		$num = mysql_numrows($sth);
		while($row = mysql_fetch_array($sth)){
			//$edit = ($row['status'] == 'pending') ? "<a href=# onclick=\"xajax_delete_penalty('".$row['id']."', '".$loan_id."', '".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_year."', '".$from_month."', '".$from_mday."', '".$to_year."', '".$to_month."', '".$to_mday."', '".$status."','".$branch_id."','".$num_rows."','".$stat."','".$cur_page."');\">Delete</a>" : "--";
			$edit =  "<a href='javascript:;' onclick=\"xajax_delete_penalty('".$row['id']."', '".$loan_id."', '".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."','".$to_date."', '".$status."','".$branch_id."');\">Delete</a>";
			//$color = ($i%2 == 0) ? "lightgrey" : "white";
			$content .= "<tr><td>".$i."</td><td>".$row['date']."</td><td>".$row['amount']."</td><td>".$row['status']."</td><td>".$edit."</td></tr>";
			$i++;
		}
		$content .= "</tbody></table></div>";
		$resp->call("createTableJs");
	}
				
	$content .="<form method='post' class='panel form-horizontal form-bordered'>";
$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h4 class="panel-title">APPLY PENALTY</h4>
                                               		 <p class="text-default nm"></p>
                                           	 </div>
                                        </div>';
                                        
                                            $content .='<div class="form-group">
                                            <label class="col-sm-3 control-label">Amount:</label>
                                            <div class="col-sm-6">
                                         <input type=int id="amount" class="form-control">
                                            </div></div>';                                            
                                            
		   $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Date:</label>
                                            <div class="col-sm-6">
                                           <input type="text" class="form-control" id="date" name="date" placeholder="date" /> 
                                            </div></div>';
                              $resp->call("createDate","date");  
                              
                  $back="<button class='btn btn-primary' onclick=\"xajax_list_arrears('".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."','".$to_date."', '".$status."','".$branch_id."');\">Back</button>";                             
                                             
                  $content .= "<div class='panel-footer'>".$back."
                              <button type='button' class='btn btn-primary' onclick=\"xajax_insert_penalty('".$loan_id."', getElementById('amount').value,  getElementById('date').value,'".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."','".$to_date."', '".$status."','".$branch_id."');\">Save</button>";
                  $content .= '</div></div></form>';
	
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//INSERT PENALTY
function insert_penalty($loan_id, $amount, $date, $cust_name, $cust_no, $account_name, $loan_officer, $from_date, $to_date, $status, $branch_id){
 list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	if($amount == NULL)
		$resp->alert("You may not leave the amount blank");
	elseif(preg_match("/\D/", $amount))
		$resp->alert("Please for Amount, enter digits only");
	elseif(!$calc->isValidDate($mday, $month, $year))
		$resp->alert("Penalty rejected! Please enter valid date");
	elseif($calc->isFutureDate($mday, $month, $year))
		$resp->alert("Penalty rejected! You have entered a future date");
	else{
		$date = sprintf("%d-%02d-%02d", $year, $month, $mday);
		$date = $date." ".date('H:i:s');
		$sth = mysql_query("select * from penalty where loan_id='".$loan_id."' and date='".$date."'");
		if(@ mysql_numrows($sth) > 0){
			$resp->alert("Penalty rejected! \nThe penalty for this date has already been added");
			return $resp;
		}
		$branch = mysql_fetch_array(mysql_query("select branch_id from disbursed where id='".$loan_id."'"));
		$branch_id_insert = $branch['branch_id'];
		mysql_query("insert into penalty set loan_id='".$loan_id."', amount='".$amount."', date='$date', branch_id='".$branch_id_insert."'");
		////////////
	$action = "insert into penalty set loan_id='".$loan_id."', amount='".$amount."', date='$date', branch_id=".$branch_id_insert;
	$msg = "Registered a penalty amount:".$amount." for customer:".$cust_name." - ".$cust_no;
	log_action($_SESSION['user_id'],$action,$msg);
	////////////
		$resp->assign("status", "innerHTML", "<font color=red>Penalty registered</font>");
		$resp->call('xajax_add_penalty', $loan_id, $cust_name, $cust_no, $account_name, $loan_officer, $from_date, $to_date, $status, $branch_id);
	}
	return $resp;
}

//DELETE A PENALTY 
function delete_penalty($pen_id, $loan_id, $cust_name, $cust_no, $account_name, $loan_officer, $from_date,$to_date, $status,$branch_id){
	$resp = new xajaxResponse();
	/*if($_SESSION['position'] <>'Manager'){
		$resp->alert("Access Denied! \nOnly the Manager can manage account");
		return $resp;
	}*/
	$resp->confirmCommands(1, "Do you really want to delete?");
	$resp->call('xajax_delete2_penalty', $pen_id, $loan_id, $cust_name, $cust_no, $account_name, $loan_officer, $from_date,$to_date, $status,$branch_id);
	return $resp;
}

//CONFIRM DELETE FROM DB
function delete2_penalty($pen_id, $loan_id, $cust_name, $cust_no, $account_name, $loan_officer, $from_date,$to_date, $status,$branch_id){
	$resp = new xajaxResponse();
	if(! mysql_query("delete from penalty where id='".$pen_id."'")){
		$resp->alert("Could not delete the penalty, kindly contact Mobis Support Team");
	}else{
		//////////////////////
		$action = "delete from penalty where id='".$pen_id."'";
		$msg = "Deleted a penalty amount:".$amount['amount']." for customer:".$cust_name." - ".$cust_no;
		log_action($_SESSION['user_id'],$action,$msg);
		/////////////////////
		$resp->call('xajax_add_penalty', $loan_id, $cust_name, $cust_no, $account_name, $loan_officer, $from_date,$to_date, $status,$branch_id);
		$resp->assign("status", "innerHTML", "<font color=red>Penalty deleted</font>");
	}
	return $resp;
}

//PRINT PAYMENTS
function add_pay($loan_id, $applic_id, $cust_name, $cust_no, $account_name, $loan_officer, $princ_arrears, $int_arrears, $princ_due, $int_due, $from_date,$to_date, $status, $cheque_no,$branch_id){
	$resp = new xajaxResponse();
	//$resp->alert($loan_id);
	//return $resp;
	$content='';
	$applic_res = mysql_query("select m.id as mem_id, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, m.branch_id as branch_id, applic.amount as amount, d.date as disburse_date, d.amount as disbursed_amt, d.balance as balance, d.penalty_rate as penalty_rate, d.int_method as int_method, d.int_rate as int_rate,  d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id where applic.id='".$applic_id."'");
	//$resp->alert(mysql_error());
	$applic = mysql_fetch_array($applic_res);

	//PENALISE IF THERE ARE AREARS AND HAS NOT BEEN PENALISED IN 30 DAYS
//FIND DAYS SINCE LAST PENALTY 
	$pen = mysql_fetch_array(mysql_query("select datediff(curdate(), date) as penalty_days from schedule where loan_id='".$loan_id."' and princ_amt=0 and date<= curdate() order by date desc limit 1"));
	//IF NO PENALTY EVER GIVEN, FIND DAYS SINCE LAST PAYMENT
	if($pen['penalty_days'] ==NULL)
		$pen = mysql_fetch_array(mysql_query("select datediff(curdate(), date) as penalty_days from  payment where loan_id='".$loan_id."'  and date<= curdate() order by date desc limit 1"));
	//IF NO PAYMENT EVER MADE, FIND DAYS SINCE THE DAY FOR THE 1ST INSTALMENT
	if($pen['penalty_days'] ==NULL)
		$pen = mysql_fetch_array(mysql_query("select datediff(curdate(), date) as penalty_days from  schedule where loan_id='".$loan_id."'  and date<= curdate() order by date asc limit 1"));
		
	if($pen['penalty_days'] > 30 && $applic['penalty_rate'] >0){
		
		$total_arrears = $princ_arrears + $int_arrears;
		
		$penalty_amt = $total_arrears * ($applic['penalty_rate']/100) * (($pen['penalty_days']/30)/12);
		$date = sprintf("%d-%02d-%02d", date('Y'), date('m'), date('d'));
		mysql_query("insert into schedule set int_amt ='".$penalty_amt."', date='".$date."', loan_id='".$loan_id."', branch_id='".$applic['branch_id']."'");
		$int_arrears += $penalty_amt;
		$int_due = $int_arrears;
		$resp->call('xajax_add_pay', $loan_id, $applic_id, $cust_name, $cust_no, $account_name, $loan_officer, $princ_arrears, ($int_arrears + $penalty_amt), $princ_due, $int_due, $from_date, $to_date, $status, $cheque_no, $branch_id);
		$resp->assign("status", "innerHTML", "<font color=red>Penalty levied on this loan!</font>");
		return $resp;
	}

	//BUILD UP THE MEMBER'S SAVINGS ACCOUNTS
	$mem_res = mysql_query("select mem.id as memaccount_id, a.account_no as account_no, a.name as name from mem_accounts mem left join savings_product p on mem.saveproduct_id=p.id left join accounts a on p.account_id=a.id where mem.mem_id='".$applic['mem_id']."' and mem.close_date >CURDATE() and a.name not in ('Compulsory Savings', 'Compulsory Shares')");
	$save_accts='';
	while($mem = mysql_fetch_array($mem_res)){
		$save_accts .= "<option value='".$mem['memaccount_id']."'>Offset From ".$mem['account_no']." - ".$mem['name']."";
	}
	//$total_int = $int_arrears; // + $int_due;
	$total_int = $int_due;
	 
	$pen_res = mysql_query("select sum(amount) as amount from penalty where loan_id='".$loan_id."' and status='pending'");
	$pen = @ mysql_fetch_array($pen_res);
	if($pen['amount'] == NULL){
		$penalty = 0;
		$former_penalty = 0;
	}else{
		$penalty = $pen['amount'];
		$former_penalty = $pen['amount'];
	}
		
	$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                 <p><h4 class="semibold text-primary mt0 mb5">PAYMENTS MADE BY '.strtoupper($applic['first_name']).' '.strtoupper($applic['last_name']).'</h4></p>
                <p>Member No: <font color="#00BFFF">'.$applic['mem_no'].'</font></p>
                <p>Amount Disbursed: <font color="#00BFFF">'.number_format($applic['amount'], 2).'</font></p>
		<p>Disbursement Date: <font color="#00BFFF">'.$applic['disburse_date'].'</font></p>
		<p>Principal in Arrears: <font color="#00BFFF">'.number_format($princ_arrears, 2).'</font></p>
		<p>Penalty: <font color="#00BFFF">'.number_format($penalty, 2).'</font></p>
		<p>Principal Due: <font color="#00BFFF">'.number_format($princ_due, 2).'</font></p>
		<p>Total Interest: <font color="#00BFFF">'.number_format($total_int, 2).'</font></p>
                               </div>';
	$content .= '<table class="table table-striped table-bordered" id="table-tools">';
 			
	$sth = mysql_query("select * from payment where loan_id='".$loan_id."' order by id asc");
	if(@ mysql_numrows($sth) == 0){
		$content .= "<tr><td><font color=red>No payments yet made for this loan</font></td></tr></table></div>";
	}else{	
		                          
		$content .="<thead><th>#</th><th><b>Date</b></th><th><b>Receipt No.</b></th><th><b>Beginning Balance</b></th><th><b>Total Paid</b></th><th><b>Princ Paid</b></th><th><b>Interest Paid</b></th><th><b>Penalty</b></th><th><b>Ending Balance</b></th><th><b>Action</b></th></thead><tbody>";
		$i=1;
		$num = mysql_numrows($sth);
		while($row = mysql_fetch_array($sth)){
			$edit = ($i == $num) ? "<a href='javascript:;' onclick=\"xajax_delete_pay('".$row['id']."', '".$loan_id."', '".$applic_id."', '".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$princ_arrears."', '".$int_arrears."', '".$princ_due."', '".$int_due."', '".$from_date."','".$to_date."', '".$status."', '".$cheque_no."', '".$branch_id."');\">Delete</a>" : "--";
			$pen_res = mysql_query("select sum(amount) as amount from penalty where date='".$row['date']."' and loan_id='".$loan_id."' and status='paid'");
			$pen = mysql_fetch_array($pen_res);
			$pen_amt = ($pen['amount'] == NULL) ? 0 : $pen['amount'];
			$penalty = ($pen['amount'] == NULL) ? "--" : $pen['amount'];
			//$color = ($i%2 == 0) ? "lightgrey" : "white";
			$content .= "<tr><td>".$i."</td><td>".$row['date']."</td><td>".$row['receipt_no']."</td><td>".number_format($row['begin_bal'], 2)."</td><td>".number_format(($row['princ_amt']+$row['int_amt']+$pen_amt), 2)."</td><td>".$row['princ_amt']."</td><td>".number_format($row['int_amt'], 2)."</td><td>".$penalty."</td><td>".number_format($row['end_bal'], 2)."</td><td>".$edit."</td></tr>";
			$i++;
		}
		$content .= "</tbody></table></div>";
		$resp->call("createTableJs");
	}
				
	$content .="<form method='post' class='panel form-horizontal form-bordered'>";
$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h4 class="panel-title">REGISTER PAYMENT</h4>
                                               		 <p class="text-default nm"></p>
                                           	 </div>
                                        </div>';
                                        
                 			    if($former_penalty > 0)                  	                                           
                                            $content .='<div class="form-group">
                                            <label class="col-sm-3 control-label">Penalty:</label>
                                            <div class="col-sm-6">
                                            <input type=int id="penalty" value="'.$former_penalty.'" class="form-control">
                                            </div></div>';
                                            else
                                            $content .= "<input type=hidden value='0' id='penalty'>";
	$former_res = mysql_query("select a.name as account_name, a.account_no as account_no, bank.id as bank_account_id from disbursed d join bank_account bank on d.bank_account=bank.id join accounts a on bank.account_id=a.id where d.id='".$loan_id."'");
	$former = mysql_fetch_array($former_res);
                                            $content .='<div class="form-group">
                                            <label class="col-sm-3 control-label">Principal & Interest:</label>
                                            <div class="col-sm-6">
                                          <input type=int id="amount_paid" value="0" class="form-control">
                                            </div></div>';                                            
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Payment Mode:</label>
                                            <div class="col-sm-6">
                                            <select id="mode" class="form-control"><option=""><option value="Cash">Cash<option value="Cheque">Cheque'.$save_accts.'</select>
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Receipt No/Cheque No:</label>
                                            <div class="col-sm-6">
                                          <input type=int id="receipt_no" value="0" class="form-control">
                                           </div></div>';
                                                                                        
                                            //if (strtolower($_SESSION['position']) == 'manager')
		$bank_res = mysql_query("select a.name as account_name, a.account_no as account_no, b.bank as bank, b.id as bank_account_id from bank_account b join accounts a on b.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."'");
	/*else
		$bank_res = mysql_query("select a.name as account_name, a.account_no as account_no, b.bank as bank, b.id as bank_account_id from bank_account b join accounts a on b.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."' and b.id in (select bank_account_id from user_account where user_id =".$_SESSION['user_id'].")");*/
	 $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Bank Account:</label>
                                            <div class="col-sm-6"><select id="bank_account_id" class="form-control">';
	//$bank_res = mysql_query("select a.name as account_name, a.account_no as account_no, bank.id as id from bank_account bank join accounts a on bank.account_id=a.id where bank.id<>'".$former['bank_account_id']."'");
	while($bank = @ mysql_fetch_array($bank_res)){
		$content .= "<option value='".$bank['bank_account_id']."'>".$bank['account_no'] ." - ".$bank['account_name'];
	}
        
        if($status == 'outstanding')
		$back="<button class='btn btn-primary' onclick=\"xajax_list_outstanding('".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."','".$to_date."', '".$cheque_no."','".$branch_id."');\">Back</button>";
	elseif($status == 'cleared')
		$back = "<button class='btn btn-primary' onclick=\"xajax_list_cleared('".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."',".$to_date."','".$branch_id."');\">Back</button>";
elseif($status == 'due')
	$back = "<button class='btn btn-primary' onclick=\"xajax_list_due('".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."','".$to_date."', '".$cheque_no."','".$branch_id."');\">Back</button>";
	$content .="</select></div></div>";  
	
		   $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Date:</label>
                                            <div class="col-sm-6">
                                           <input type="text" class="form-control" id="date" name="date" placeholder="date" /> 
                                            </div></div>';
                              $resp->call("createDate","date");                               
                                             
                  $content .= "<div class='panel-footer'>".$back."
                              <button type='button' class='btn btn-primary' onclick=\"xajax_insert_pay('".$loan_id."', '".$applic['disbursed_amt']."','".$applic['balance']."', '".$applic['int_mode']."', '".$applic['int_rate']."', '".$former_penalty."', getElementById('penalty').value, '".$total_int."', getElementById('amount_paid').value, getElementById('mode').value, getElementById('bank_account_id').value, getElementById('receipt_no').value, getElementById('date').value,'".$princ_arrears."', '".$int_arrears."', '".$princ_due."', '".$int_due."','".$cust_name."','".$cust_no."','".$status."','".$branch_id."', '".$from_date."','".$to_date."'); return false; \">Save</button>";
                  $content .= '</div></form></div>';

	//ADD REFUND
	$content .="<form method='post' class='panel form-horizontal form-bordered'>";
$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h4 class="panel-title">REGISTER REFUND</h4>
                                               		 <p class="text-default nm"></p>
                                           	 </div>
                                       </div>';
                                       $former_res = mysql_query("select a.name as account_name, a.account_no as account_no, bank.id as bank_account_id from disbursed d join bank_account bank on d.bank_account=bank.id join accounts a on bank.account_id=a.id where d.id='".$loan_id."'");
	$former = mysql_fetch_array($former_res);
                 			   
                                            $content .='<div class="form-group">
                                            <label class="col-sm-3 control-label">Principal To Refund:</label>
                                            <div class="col-sm-6">
                                         <input type=int id="princ_refund" value="0" class="form-control">
                                            </div></div>';                                            
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Interest To Refund:</label>
                                            <div class="col-sm-6">
                                           <input type=int id="int_refund" value="0" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Refund Mode:</label>
                                            <div class="col-sm-6">
                                          <select id="mode_refund" class="form-control"><option=""><option value="Cash">Cash<option value="Cheque">Cheque'.$save_accts.'</select>
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Voucher No/Cheque No:</label>
                                            <div class="col-sm-6">
                                           <input type=int id="receipt_no" value="0" class="form-control">
                                            </div></div>';
                                            $content .= "<div class='form-group'>
                                            <label class='col-sm-3 control-label'>Bank Account:</label>
                                            <div class='col-sm-6'>
                                          <select id='bank_account_id' class='form-control'><option value='".$former['bank_account_id']."'>".$former['account_no']." - ".$former['account_name'];
	$bank_res = mysql_query("select a.name as account_name, a.account_no as account_no, bank.id as id from bank_account bank join accounts a on bank.account_id=a.id where id<>'".$former['bank_account_id']."'");
	while($bank = @ mysql_fetch_array($bank_res)){
		$content .= "<option value='".$bank['bank_account_id']."'>".$bank['account_no'] ." - ".$bank['account_name'];
	}
	if($status == 'outstanding')
		$back="<button class='btn btn-primary' onclick=\"xajax_list_outstanding('".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."', '".$to_date."', '".$cheque_no."','".$branch_id."');\">Back</button>";
	elseif($status == 'cleared')
		$back = "<button class='btn btn-primary' onclick=\"xajax_list_cleared('".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."', '".$to_date."','".$branch_id."');\">Back</button>";
elseif($status == 'due')
	$back = "<button class='btn btn-primary' onclick=\"xajax_list_due('".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."','".$to_date."', '".$cheque_no."','".$branch_id."');\">Back</button>";
	$content .='</select>
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Date:</label>
                                            <div class="col-sm-6">
                                           <input type="text" class="form-control" id="date2" name="date2" placeholder="date" /> 
                                            </div></div>';
                                            $resp->call("createDate","date2");
                                                                                                                 
                  $content .= "<div class='panel-footer'>".$back."
                              <button type='button' class='btn btn-primary' onclick=\"xajax_insert_refund('".$loan_id."', '".$applic['disbursed_amt']."','".$applic['balance']."', '".$applic['int_mode']."', '".$applic['int_rate']."', '".$former_penalty."', getElementById('penalty').value, '".$total_int."', getElementById('princ_refund').value, getElementById('int_refund').value, getElementById('mode_refund').value, getElementById('bank_account_id').value, getElementById('receipt_no').value, getElementById('date2').value,'".$princ_arrears."','".$int_arrears."', '".$princ_due."', '".$int_due."','".$branch_id."','".$cust_name."','".$cust_no."','".$status."' ,'".$from_date."','".$to_date."');\">Save</button></div></div></form>";
                 
	//WAIVER PENALTY
	
	$content .="<form method='post' class='panel form-horizontal form-bordered'>";
$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h4 class="panel-title">WAIVER INTEREST / PENALTY</h4>
                                               		 <p class="text-default nm"></p>
                                           	 </div>
                                        </div>';
		
	$former_res = mysql_query("select a.name as account_name, a.account_no as account_no, bank.id as bank_account_id from disbursed d join bank_account bank on d.bank_account=bank.id join accounts a on bank.account_id=a.id where d.id='".$loan_id."'");
	$former = mysql_fetch_array($former_res);
	
	$content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Penalty Amount To waiver:</label>
                                            <div class="col-sm-6">
                                           <input type=int id="waiver_amt" value="0" class="form-control" >
                                            </div></div>';
                                           
	 $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Date:</label>
                                            <div class="col-sm-6">
                                           <input type="text" class="form-control" id="date3" name="date3" placeholder="date"/> 
                                            </div></div>';
                                            $resp->call("createDate","date3");
	
	if($status == 'outstanding')
		$back="<button class='btn btn-primary' onclick=\"xajax_list_outstanding('".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."','".$to_date."', '".$cheque_no."','".$branch_id."');\">Back</button>";
	elseif($status == 'cleared')
		$back = "<button class='btn btn-primary' onclick=\"xajax_list_cleared('".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."','".$to_date."','".$branch_id."');\">Back</button>";
elseif($status == 'due')
	$back = "<button class='btn btn-primary' onclick=\"xajax_list_due('".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."','".$to_date."', '".$cheque_no."','".$branch_id."');\">Back</button>";
	$content .="</select></div></div>";
	
		 $content .= "<div class='panel-footer'>".$back." <button class='btn btn-primary' onclick=\"xajax_insert_waiver('".$loan_id."','".$applic['disbursed_amt']."','".$applic['balance']."', '".$applic['int_mode']."', '".$applic['int_rate']."', '".$former_penalty."', getElementById('penalty').value, '".$total_int."', getElementById('princ_refund').value, getElementById('int_refund').value, getElementById('mode').value, getElementById('bank_account_id').value, getElementById('receipt_no').value, getElementById('date3').value,'".$princ_arrears."', '".$int_arrears."', '".$princ_due."', '".$int_due."','".$branch_id."', '".$cust_name."','".$cust_no."','".$status."','".$from_date."','".$to_date."',getElementById('waiver_amt').value);\">Save</button></div>
	</div></form>";

	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//DELETE PAYMENT
function delete_pay($pay_id, $loan_id, $applic_id,   $cust_name, $cust_no, $account_name, $loan_officer, $princ_arrears, $int_arrears, $princ_due, $int_due, $from_date,$to_date, $status, $cheque_no,$branch_id){
	$resp = new xajaxResponse();
	/*if($_SESSION['position'] <>'Manager'){
		$resp->alert("Access Denied! \nOnly the Manager can manage account");
		return $resp;
	}*/
	$resp->confirmCommands(1, "Do you really want to delete?");
	$resp->call('xajax_delete2_pay', $pay_id, $loan_id, $applic_id,   $cust_name, $cust_no, $account_name, $loan_officer, $princ_arrears, $int_arrears, $princ_due, $int_due, $from_date,$to_date, $status, $cheque_no,$branch_id);
	return $resp;
}

//CONFIRM DELETION OF PAYMENT
function delete2_pay($pay_id, $loan_id, $applic_id,   $cust_name, $cust_no, $account_name, $loan_officer, $princ_arrears, $int_arrears, $princ_due, $int_due, $from_date, $from_date,$to_date, $status, $cheque_no,$branch_id){
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	//$resp->alert($status);
	//return $resp;
	start_trans();
	$pay_res = mysql_query("select p.id as id, p.begin_bal as begin_bal, p.loan_id as loan_id, p.date as date, p.princ_amt as princ_amt, p.int_amt as int_amt, (p.princ_amt + p.int_amt) as amount_paid, d.amount as disbursed_amt, p.bank_account as bank_account, b.min_balance as min_balance, b.account_balance as account_balance, date_format(d.date, '%Y') as disburse_year, date_format(d.date, '%m') as disburse_month, date_format(d.date, '%d') as disburse_mday  from payment p join disbursed d on p.loan_id=d.id left join bank_account b on p.bank_account=b.id where p.id='".$pay_id."'");
	
	$pay = mysql_fetch_array($pay_res);
	$amt_paid = $pay['amount_paid']; 
	
	$pen_res = mysql_query("select id, amount from penalty where date='".$pay['date']."' and loan_id='".$pay['loan_id']."' and status='paid'");
	$pen = mysql_fetch_array($pen_res);
	if($pen['amount'] != ''){
		$amt_paid += $pen['amount'];
		if(! mysql_query("update penalty set status='pending' where id='".$pen['id']."'")){
			$resp->alert("ERROR: Payment could not be deleted!\n Could not update penalty");
			rollback();
			return $resp;
		}
	}
	/*
	if($pay['account_balance'] - $amt_paid < $pay['min_balance']){
		$resp->alert("ERROR: Payment could not be deleted!\n Bank Account would go below the minimum balance . AccBal=".$pay['min_balance']);
		rollback();
		return $resp;
	}
	*/

	//$last_res = mysql_query("select date, date_format(date, '%Y') as year, date_format(date, '%m') as month, date_format(date, '%d') as mday from payment where date <='".$pay['date']."' and id<>'".$pay['id']."' and loan_id=".$loan_id." order by date desc limit 1");
	$last_res = mysql_query("select date from schedule where date <'".$pay['date']."'  and loan_id=".$loan_id." order by date desc limit 1");

	if(mysql_numrows($last_res)==0){
		$last_res = mysql_query("select date, date_format(date, '%Y') as year, date_format(date, '%m') as month, date_format(date, '%d') as mday from disbursed where id='".$loan_id."'");
		//$last = mysql_fetch_array($last_res);
	}
	$last = mysql_fetch_array($last_res);
	
	$last_pay_int_date = $last['date'];   
	
	$paid = mysql_fetch_array(mysql_query("select sum(princ_amt) as princ_amt from payment where loan_id='".$loan_id."'"));
//	$resp->assign("status", "innerHTML", "select p.id as id, p.begin_bal as begin_bal, p.loan_id as loan_id, p.date as date, p.princ_amt as princ_amt, p.int_amt as int_amt, (p.princ_amt + p.int_amt) as amount_paid, d.amount as disbursed_amt, p.bank_account as bank_account, b.min_balance as min_balance, b.account_balance as account_balance, date_format(d.date, '%Y') as disburse_year, date_format(d.date, '%m') as disburse_month, date_format(d.date, '%d') as disburse_mday  from payment p join disbursed d on p.loan_id=d.id join bank_account b on p.bank_account=b.id where p.id='".$pay_id."'");
	$balance = ($paid['princ_amt'] == NULL) ? $pay['disbursed_amt'] : $pay['disbursed_amt'] - $paid['princ_amt'] + $pay['princ_amt'];
	
	//$resp->alert($pay['begin_bal']);
	//rollback();
	//return $resp;
	if(! mysql_query("update disbursed set balance='".$balance."', last_pay_date='".$last_pay_int_date."' where id='".$loan_id."'")){
		$resp->alert("ERROR: Payment could not be deleted!\n Could not update the disbursement");
		rollback();
		return $resp;
	}
	//mysql_query("update disbursed set balance=amount where id not in (select loan_id from payment)");
	
	//COMPULSORYH SAVINGS/SHARES
	$base_res = mysql_query("select d.based_on as based_on, applic.mem_id as mem_id, applic.guarantor1 as guarantor1, applic.guarantor2 as guarantor2 from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id where d.id=$loan_id");
	$base = mysql_fetch_array($base_res);
	$limit_res = mysql_query("select * from branch where branch_no='".$branch_id."'");
		if(mysql_num_rows($limit_res)>0)
		$limit = mysql_fetch_array($limit_res);
		else
		$limit = mysql_fetch_array(mysql_query("select * from branch limit 1"));
	if($base['based_on'] == 'savings'){
		if($limit['loan_save_percent'] ==0)
			$comp_amt =0;
		else
			$comp_amt = @ ($pay['princ_amt'] * 100) / $limit['loan_save_percent'];
		if($limit['guarantor_save_percent'] == 0)
			$guaranteed_amt =0;
		else
			$guaranteed_amt =  ($pay['princ_amt'] * 100) / $limit['guarantor_save_percent'];
		$comp_res = mysql_query("select s.id as id from savings_product s join accounts a on s.account_id=a.id where a.name='Compulsory Savings'");
		$class = "Compulsory Savings";
	}else{
		if($limit['loan_share_percent'] == 0)
			$comp_amt =0;
		else
			$comp_amt =  ($pay['princ_amt'] * 100) / $limit['loan_share_percent'];
		if($limit['guarantor_share_percent'] == 0)
			$guaranteed_amt = 0;
		else
			$guaranteed_amt = @ ($pay['princ_amt'] * 100) / $limit['guarantor_share_percent'];
		$comp_res = mysql_query("select s.id as id from savings_product s join accounts a on s.account_id=a.id where a.name='Compulsory Shares'");
		$class = "Compulsory Shares";
	}
	$comp = mysql_fetch_array($comp_res);
	//FOR BORROWER
	$post_res = mysql_query("select id as memaccount_id from mem_accounts where mem_id='".$base['mem_id']."' and saveproduct_id='".$comp['id']."'");
	$post = mysql_fetch_array($post_res);
	if(! mysql_query("update deposit set amount=amount + ".$comp_amt ." where memaccount_id='".$post['memaccount_id']."'")){
		$resp->alert("ERROR: Payment not deleted! \n Could not update ".$class);
		rollback();
		return $resp;
	}
	//FOR GUARANTOR 1
	$post_res = mysql_query("select id as memaccount_id from mem_accounts where mem_id='".$base['guarantor1']."' and saveproduct_id='".$comp['id']."'");
	$post = mysql_fetch_array($post_res);
	if(! mysql_query("update deposit set amount=amount + ".$guaranteed_amt ." where memaccount_id='".$post['memaccount_id']."'")){
		$resp->alert("ERROR: Payment not registered! \n Could not update ".$class." for guarantor 1");
		rollback();
		return $resp;
	}
	//FOR GUARANTOR 2
	$post_res = mysql_query("select id as memaccount_id from mem_accounts where mem_id='".$base['guarantor2']."' and saveproduct_id='".$comp['id']."'");
	$post = mysql_fetch_array($post_res);
	if(! mysql_query("update deposit set amount=amount + ".$guaranteed_amt ." where memaccount_id='".$post['memaccount_id']."'")){
		$resp->alert("ERROR: Payment not updated! \n Could not update ".$class." for guarantor 2");
		rollback();
		return $resp;
	}
	if(! mysql_query("update bank_account set account_balance=account_balance -".$amt_paid." where id='".$pay['bank_account']."'") && $pay['account_account']>0){
		$resp->alert("ERROR: Payment could not be deleted!\n Could not update bank account".mysql_error());
		rollback();
		return $resp;
	}
	
	if(! mysql_query("delete from payment where id='".$pay_id."'")){
		rollback();
		$resp->alert("ERROR: Payment could not be deleted!\n Could not delete from table");
		return $resp;
	}
	if($_SESSION['commit'] == 1){
		////////////////
		$action = "delete from payment where id='".$pay_id."'";
		$msg = "Deleted a payment amount:".$pay['princ_amt']." from customer's account: ".$cust_no." - ".$cust_name;
		log_action($_SESSION['user_id'],$action,$msg);
	////////////
		commit();
	}

	$sth = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as disbursed_amt, d.date as date, d.balance as balance, d.period as loan_period, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, d.last_pay_date as last_pay_date, d.pay_freq as pay_freq, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where d.id='".$loan_id."'");
	$row = mysql_fetch_array($sth);	
		
	$date = sprintf("%d-%02d-%02d 23:59:59", date('Y'), date('m'), date('d'));
//RESCHEDULE INTEREST FOR DECLINING BALANCE METHOD
		if($row['int_method'] == 'Declining Balance'){
			//FIND OUT THE UPPER BOUND OF THE CURRENT PAYMENT PERIOD
			$upper = mysql_fetch_array(mysql_query("select date from schedule where date >= '".$date."' and loan_id='".$loan_id."' order by date asc limit 1"));
			$upper_date = ($upper['date'] == NULL) ? $date : $upper['date'];

			//$sched_res = mysql_query("select id from schedule where loan_id='".$loan_id."' and princ_amt>0 and date> '".$row['last_pay_date']."' and date <= '".date('Y-m-d 23:59:59')."'");
			$sched_res = mysql_query("select id from schedule where loan_id='".$loan_id."' and princ_amt>0 and date> '".$row['last_pay_date']."' and date <= '".$upper_date."'");
			while($sched = mysql_fetch_array($sched_res)){
				$new_int = ($balance) * (($row['int_rate']/100)/12) * ($row['pay_freq']/30);

				mysql_query("update schedule set int_amt ='".$new_int."' where id='".$sched['id']."'");
			}
		}

		//ARREARS
		$arrears_days = $calc->dateToDays($date) - $row['arrears_period'];
		$arrears_date = $calc->daysToDate($arrears_days, '%Y-%m-%d');
		$rrears_date = $arrears_date ." 23:59:59";
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id='".$loan_id."' and date < '".$arrears_date."'");
		
		$sched = @mysql_fetch_array($sched_res);
				
		$paid_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from payment where loan_id='".$loan_id."' and date <= '".$date."'");
		
		$paid = mysql_fetch_array($paid_res);
		$sched_amount = ($sched['amount'] == NULL) ? "0" : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? "0" : $sched['int_amt'];
		$paid_amt = ($paid['amount'] == NULL) ? "0" : $paid['amount'];
		$paid_int = ($paid['int_amt'] == NULL) ? "0" : $paid['int_amt'];
		$arrears_amt = $sched_amount - $paid_amt;
		$int_arrears = $sched_int - $paid_int;
		
		
		if($arrears_amt <= 0){
			$princ_arrears ="--";
			$int_arrears = "--";
			$total_arrears ="--";
			$total_amt_arrears = 0;
			$int_amt_arrears =0;
			$princ_amt_arrears =0;
		}else{
			$princ_arrears = $arrears_amt;
			$princ_amt_arrears = $arrears_amt;
			
			$arrears_days =$arrears_days - $calc->dateToDays($row['last_mday'], $row['last_month'], $row['last_year']) -1;
			
			$total_arrears = $princ_arrears + $int_arrears;
			$total_amt_arrears = $total_arrears;
			$int_amt_arrears = $int_arrears;
			$int_arrears = ($int_arrears == 0) ? "--" : $int_arrears;
		}
		
		//CALCULATE DUE PRINCIPAL
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id=".$loan_id." and date <= '".$date."'");
	
		$sched = @mysql_fetch_array($sched_res);
		$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? 0 : $sched['int_amt'];
			
		$due_princ_amt = $sched_amt - $paid_amt;  // - $arrears_amt;
		$due_int_amt = $sched_int - $paid_int; // - $int_amt_arrears;	
		
		$first_instal =0;
		if($due_princ_amt <= 0 && $paid_amt >= 0){
			$due_princ = "--"; 
			if($due_princ_amt ==0 && $paid_int ==0){    //FIRST INSTALMENT, YOU PAY THE FIRST INTEREST
				$check = mysql_fetch_array(mysql_query("select * from schedule where loan_id='$loan_id' order by date asc limit 1"));
				$due_int_amt = $check['int_amt'];
				$due_int = $due_int_amt;
				$first_instal = 1;
			}else{
				$due_int = "--";
				$due_int_amt=0;
			}
		}else{
			$due_princ = $due_princ_amt;
			if($due_int_amt >0){	
				$due_int = $due_int_amt;
			}else
				$due_int="--"; 
			
		}
		$int_due = $due_int;
		$princ_due = $due_princ;
	//$resp->alert($int_arrears);
	$resp->call('xajax_add_pay', $loan_id, $row['applic_id'],   $cust_name, $row['cust_no'], $row['account_name'], $loan_officer, $princ_arrears, $int_arrears, $princ_due, $int_due, $from_date, $to_date, $status, $cheque_no,$branch_id);

	//$resp->call('xajax_add_pay', $loan_id, $row['applic_id'],   $cust_name, $row['cust_no'], $row['account_name'], $loan_officer, $princ_arrears, $int_arrears, $princ_due, $int_due, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday, $status, $cheque_no,$branch_id);

	$resp->assign("status", "innerHTML", "<font color=red>Payment deleted!</font>");
	return $resp;	
}

//INSERT PAYMENT REFUND
function insert_refund($loan_id, $disbursed_amt, $balance, $int_mode, $int_rate, $former_penalty, $penalty, $total_int,  $princ_refund, $int_refund, $mode, $bank_account_id, $receipt_no, $date, $princ_arrears, $int_arrears, $princ_due, $int_due, $branch_id, $cust_name, $cust_no, $status, $from_date, $to_date){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
$accno = mysql_fetch_assoc(mysql_query("select m.first_name,m.last_name, m.branch_id from member m join loan_applic l on m.id=l.mem_id join disbursed d on d.applic_id=l.id where d.id=".$loan_id)) or trigger_error(mysql_error());
	if(!$calc->isValidDate($mday, $month, $year))
		$resp->alert("Payment rejected! Please enter valid date");
	elseif($calc->isFutureDate($mday, $month, $year))
		$resp->alert("Payment rejected! You have entered a future date");
	else{
		$former_res = mysql_query("select * from disbursed where id='$loan_id'");
		$former = mysql_fetch_array($former_res);
		//$date = sprintf("%d-%02d-%02d", $year, $month, $mday);
		$date = $date." ".date('H:i:s');
		start_trans();
		if(! mysql_query("update disbursed set balance=balance+".$princ_refund. " where id='$loan_id'")){
			$resp->alert("Could not update the disbursement, \n".mysql_error());
			rollback();
			return $resp;
		}
		//RESCHEDULE INTEREST FOR DECLINING BALANCE METHOD
		if($former['int_method'] == 'Declining Balance'){
			//FIND OUT THE UPPER BOUND OF THE CURRENT PAYMENT PERIOD
			$upper = mysql_fetch_array(mysql_query("select date from schedule where date >= '".$date."' and loan_id='".$loan_id."' order by date asc limit 1"));
			$upper_date = ($upper['date'] == NULL) ? $date : $upper['date'];

			//$sched_res = mysql_query("select id from schedule where loan_id='".$loan_id."' and princ_amt>0 and date> '".$former['last_pay_date']."' and date <= '".date('Y-m-d 23:59:59')."'");
			$sched_res = mysql_query("select id from schedule where loan_id='".$loan_id."' and princ_amt>0 and date> '".$former['last_pay_date']."' and date <= '".$upper_date."'");
			while($sched = mysql_fetch_array($sched_res)){
				$new_int = ($former['balance'] + $princ_refund) * (($former['int_rate']/100)/12) * ($former['pay_freq']/30);

				mysql_query("update schedule set int_amt ='".$new_int."' where id='".$sched['id']."'");
			}
		}

		$princ_amt = 0 - $princ_refund;
		$int_amt = 0 - $int_refund;
		$end_bal = $former['balance'] + $princ_refund;
		if(strtolower($mode) == 'cash' || strtolower($mode)=='cheque'){
			if(!mysql_query("insert into payment set princ_amt='$princ_amt', int_amt='$int_amt', receipt_no='$receipt_no', loan_id='$loan_id', date='$date', mode='$mode', begin_bal='".$former['balance']."', end_bal='$end_bal', bank_account='$bank_account_id', branch_id=".$accno['branch_id'])){
				$resp->alert("Could not insert the refund, \n".mysql_error());
				rollback();
				return $resp;
			}
			$amount_refund = $princ_refund + $int_refund;
			if(! mysql_query("update bank_account set account_balance=account_balance-".$amount_refund." where id='".$bank_account_id."'")){
				$resp->alert("ERROR: Could not update bank account balance");	
				rollback();
				return $resp;
			}
		}
		if($mode > 0){
			if(!mysql_query("insert into payment set princ_amt='$princ_amt', int_amt='$int_amt', receipt_no='$receipt_no', loan_id='$loan_id', date='$date', mode='$mode', begin_bal='".$former['balance']."', end_bal='$end_bal', bank_account=0, branch_id=".$accno['branch_id'])){
				$resp->alert("Could not insert the refund, \n".mysql_error());
				rollback();
				return $resp;
			}
		}
		
		////////////
		
		$action = "insert into payment set princ_amt='$princ_amt', int_amt='$int_amt', receipt_no='$receipt_no', loan_id='$loan_id', date='$date', mode='$mode', begin_bal='".$former['balance']."', end_bal='$end_bal', bank_account=0";
		$msg = "Registered a payment Principle Amount:".$princ_amt." from member: ".$accno['first_name']." - ".$accno['last_name']." - ".$accno['mem_no'];
		log_action($_SESSION['user_id'],$action,$msg);
	////////////
		commit();
		//PREPARE FOR A RELOAD
		
		$sth = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as disbursed_amt, d.date as date, d.balance as balance, d.period as loan_period, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where d.id='".$loan_id."'");
		$row = mysql_fetch_array($sth);	
		
//ARREARS
		$arrears_days = $calc->dateToDays($date) - $row['arrears_period'];
		$arrears_date = $calc->daysToDate($arrears_days, '%Y-%m-%d');
		$arrears_date = $arrears_date ." 23:59:59";
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id='".$loan_id."' and date < '".$arrears_date."'");
		
		$sched = @mysql_fetch_array($sched_res);
				
		$paid_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from payment where loan_id='".$loan_id."' and date <= '".$date."'");
		
		$paid = mysql_fetch_array($paid_res);
		$sched_amount = ($sched['amount'] == NULL) ? "0" : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? "0" : $sched['int_amt'];
		$paid_amt = ($paid['amount'] == NULL) ? "0" : $paid['amount'];
		$paid_int = ($paid['int_amt'] == NULL) ? "0" : $paid['int_amt'];
		$arrears_amt = $sched_amount - $paid_amt;
		$int_arrears = $sched_int - $paid_int;
		
		
		if($arrears_amt <= 0){
			$princ_arrears ="--";
			$int_arrears = "--";
			$total_arrears ="--";
			$total_amt_arrears = 0;
			$int_amt_arrears =0;
			$princ_amt_arrears =0;
		}else{
			$princ_arrears = $arrears_amt;
			$princ_amt_arrears = $arrears_amt;
			
			$arrears_days =$arrears_days - $calc->dateToDays($row['last_mday'], $row['last_month'], $row['last_year']) -1;
			
			$total_arrears = $princ_arrears + $int_arrears;
			$total_amt_arrears = $total_arrears;
			$int_amt_arrears = $int_arrears;
			$int_arrears = ($int_arrears == 0) ? 0 : $int_arrears;
		}

		//CALCULATE DUE PRINCIPAL
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id=".$loan_id." and date <= '".$date."'");
	
		$sched = @mysql_fetch_array($sched_res);
		$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? 0 : $sched['int_amt'];
			
		$due_princ_amt = $sched_amt - $paid_amt;   //- $arrears_amt;
		$due_int_amt = $sched_int - $paid_int;   // - $int_amt_arrears;	
		
		$first_instal =0;
		if($due_princ_amt <= 0 && $paid_amt >= 0){
			$princ_due = "--"; 
			if($due_princ_amt ==0 && $paid_int ==0){    //FIRST INSTALMENT, YOU PAY THE FIRST INTEREST
				$check = mysql_fetch_array(mysql_query("select * from schedule where loan_id='$loan_id' order by date asc limit 1"));
				$due_int_amt = $check['int_amt'];
				$int_due = $due_int_amt;
				$first_instal = 1;
			}else{
				$int_due = "--";
				$due_int_amt=0;
			}
		}else{
			$princ_due = $due_princ_amt;
			if($due_int_amt >0){	
				$int_due = $due_int_amt;
			}else
				$int_due="--"; 	
		}
		//$resp->alert($int_arrears);
		//return $resp;
		$resp->call('xajax_add_pay', $loan_id, $row['applic_id'],   $cust_name, $cust_no, $row['account_name'],  $loan_officer, $princ_arrears, $int_arrears, $princ_due, $int_due, $from_date,$to_date, $status, $cheque_no,$branch_id);
		$resp->assign("status", "innerHTML", "<font color=red>Payment registered</font>");
	}
	
	return $resp;
}


//INSERT PAYMENT REFUND
function insert_waiver($loan_id, $disbursed_amt, $balance, $int_mode, $int_rate, $former_penalty, $penalty, $total_int,  $princ_refund, $int_refund, $mode, $bank_account_id, $receipt_no, $date, $princ_arrears, $int_arrears, $princ_due, $int_due, $branch_id, $cust_name, $cust_no, $status, $from_date,$to_date, $waiver_amt){

	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$accno = mysql_fetch_assoc(mysql_query("select m.first_name,m.last_name, m.branch_id as branch_id from member m join loan_applic l on m.id=l.mem_id join disbursed d on d.applic_id=l.id where d.id=".$loan_id)) or trigger_error(mysql_error());
	if(!$calc->isValidDate($mday, $month, $year))
		$resp->alert("Payment rejected! Please enter valid date");
	elseif($calc->isFutureDate($mday, $month, $year))
		$resp->alert("Payment rejected! You have entered a future date");
	elseif($waiver_amt =='' || $waiver_amt==0){
		$resp->alert("Waiver rejected! Enter the correct amount waivered");
	}else{
		//$date = sprintf("%d-%02d-%02d", $year, $month, $mday);
		$sched = mysql_fetch_array(mysql_query("select sum(int_amt) as int_amt from schedule where loan_id='".$loan_id."' and date <='".$date." 23:59:59' and princ_amt=0"));
		$paid = mysql_fetch_array(mysql_query("select sum(int_amt) as int_amt from payment where loan_id='".$loan_id."' and date <='".$date." 23:59:59'"));
		$sched_amt = ($sched['int_amt'] == NULL) ? 0 : $sched['int_amt'];
		$paid_amt = ($paid['int_amt'] == NULL) ? 0 : $paid['int_amt'];
		$rem_amt = $sched_amt - $paid_amt;
		if($waiver_amt > $rem_amt){
			$resp->alert("Waiver rejected! It is bigger than the penalty leveid");
			return $resp;
		}else{
			start_trans();
			$waiver_amt = 0 - $waiver_amt;
			if(mysql_query("insert into schedule set date='".$date."', int_amt='".$waiver_amt."', loan_id='".$loan_id."', branch_id='".$accno['branch_id']."'")){
				commit();
				$resp->assign("status", "innerHTML", "<font color=red>Waiver registered!</font>");
			}else{
				$resp->assign("status", "innerHTML", "<font color=red>ERROR:Waiver not registered! ".mysql_error()."</font>");
				return $resp;
			}
		}
		////////////
		
		$action = "insert into schedule set date='".$date."', int_amt='".$waiver_amt."', loan_id='".$loan_id."'";
		$msg = "Registered a waiver amount:".$waiver_amt." for : ".$accno['first_name']." - ".$accno['last_name']." - ".$accno['mem_no'];
		log_action($_SESSION['user_id'],$action,$msg);
	////////////
		commit();
		//PREPARE FOR A RELOAD
		
		$sth = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as disbursed_amt, d.date as date, d.balance as balance, d.period as loan_period, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where d.id='".$loan_id."'");
		$row = mysql_fetch_array($sth);	
		
		//ARREARS
		$arrears_days = $calc->dateToDays($date) - $row['arrears_period'];
		$arrears_date = $calc->daysToDate($arrears_days, '%Y-%m-%d');
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id='".$loan_id."' and date < '".$arrears_date."'");
		
		$sched = @mysql_fetch_array($sched_res);
				
		$paid_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from payment where loan_id='".$loan_id."' and date <= '".$date."'");
		
		$paid = mysql_fetch_array($paid_res);
		$sched_amount = ($sched['amount'] == NULL) ? "0" : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? "0" : $sched['int_amt'];
		$paid_amt = ($paid['amount'] == NULL) ? "0" : $paid['amount'];
		$paid_int = ($paid['int_amt'] == NULL) ? "0" : $paid['int_amt'];
		$arrears_amt = $sched_amount - $paid_amt;
		$int_arrears = $sched_int - $paid_int;
		
		
		if($arrears_amt <= 0){
			$princ_arrears ="--";
			$int_arrears = "--";
			$total_arrears ="--";
			$total_amt_arrears = 0;
			$int_amt_arrears =0;
			$princ_amt_arrears =0;
		}else{
			$princ_arrears = $arrears_amt;
			$princ_amt_arrears = $arrears_amt;
			
			$arrears_days =$arrears_days - $calc->dateToDays($row['last_mday'], $row['last_month'], $row['last_year']) -1;
			
			$total_arrears = $princ_arrears + $int_arrears;
			$total_amt_arrears = $total_arrears;
			$int_amt_arrears = $int_arrears;
			$int_arrears = ($int_arrears == 0) ? 0 : $int_arrears;
		}

		/*
		//CALCULATE DUE PRINCIPAL
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id='".$loan_id."' and date <= NOW()");
		$sched = @mysql_fetch_array($sched_res);
		$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? 0 : $sched['int_amt'];
		$due_princ_amt = $sched_amt - $paid_amt;  // - $arrears_amt;	
		if($due_princ_amt <= 0){
			$princ_due = 0;
			$int_due=0;
		}else{
			$princ_due = $due_princ_amt;

			//CALCULATE DUE INTEREST
			$int_days = $calc->dateToDays(date('d'), date('m'), date('Y')) - $calc->dateToDays($row['last_mday'], $row['last_month'], $row['last_year']) - $row['arrears_period'] +1;
		
			if($int_days >0){
				$no_months = ceil($int_days /30);
				if($row['int_method'] =='Declining Balance'){
					$int_due = ceil((($row['balance'] * $row['int_rate']/100) / 12) * $no_months);
				}elseif($row['int_method'] == 'Flat'){
					$int_due = ceil((($row['disbursed_amt'] * $row['int_rate']/100) / 12) * $no_months);
				}elseif($row['int_method'] == 'Amortised'){
					$int_due = $sched_int - $paid_int;
				}
			}else
				$int_due=0; 
		}
		*/
		//CALCULATE DUE PRINCIPAL
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id=".$loan_id." and date <= '".$date."'");
	
		$sched = @mysql_fetch_array($sched_res);
		$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? 0 : $sched['int_amt'];
			
		$due_princ_amt = $sched_amt - $paid_amt;   //- $arrears_amt;
		$due_int_amt = $sched_int - $paid_int;   // - $int_amt_arrears;	
		
		$first_instal =0;
		if($due_princ_amt <= 0 && $paid_amt >= 0){
			$princ_due = "--"; 
			if($due_princ_amt ==0 && $paid_int ==0){    //FIRST INSTALMENT, YOU PAY THE FIRST INTEREST
				$check = mysql_fetch_array(mysql_query("select * from schedule where loan_id='$loan_id' order by date asc limit 1"));
				$due_int_amt = $check['int_amt'];
				$int_due = $due_int_amt;
				$first_instal = 1;
			}else{
				$int_due = "--";
				$due_int_amt=0;
			}
		}else{
			$princ_due = $due_princ_amt;
			if($due_int_amt >0){	
				$int_due = $due_int_amt;
			}else
				$int_due="--"; 	
		}
		
		//$resp->alert($int_arrears);
		//return $resp;
		$resp->call('xajax_add_pay', $loan_id, $row['applic_id'],   $cust_name, $cust_no, $row['account_name'],  $loan_officer, $princ_arrears, $int_arrears, $princ_due, $int_due, $from_date, $to_date, $status, $cheque_no,$branch_id);
		$resp->assign("status", "innerHTML", "<font color=red>Waiver registered!</font>");
	}
	
	return $resp;
}

//INSERT PAYMENT INTO DB
function insert_pay($loan_id, $disbursed_amt, $balance, $int_mode, $int_rate, $former_penalty, $penalty, $total_int,  $amount_paid, $mode, $bank_account_id, $receipt_no, $date, $princ_arrears, $int_arrears, $princ_due, $int_due,$cust_name,$cust_no, $status, $branch_id, $from_date,$to_date){
	
	list($year,$month,$mday) = explode('-', $date);
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	if($penalty=='' || $amount_paid==''|| $mode=='')
		$resp->alert("You may not leave any field blank");
	elseif($receipt_no == '' && ($mode =='Cheque' || $mode=='Cash'))
		$resp->alert("Please enter the Receipt/ Cheque No!");
	elseif($former_penalty != $penalty)
		$resp->alert("You must first pay the penalty, \n please enter the correct penalty amount");
	elseif(!$calc->isValidDate($mday, $month, $year))
		$resp->alert("Payment rejected! Please enter valid date");
	elseif($calc->isFutureDate($mday, $month, $year))
		$resp->alert("Payment rejected! You have entered a future date");
	elseif($amount_paid <= 0)
		$resp->alert("Payment rejected! Enter the correct amount being paid");
	else{
		
		$sth = mysql_query("select d.applic_id as applic_id, d.balance as balance, d.int_rate as int_rate, d.amount as disbursed_amt,  d.int_method as int_method, m.first_name as first_name, m.last_name as last_name, applic.officer_id as officer_id, a.name as account_name, a.account_no as account_no, d.pay_freq as pay_freq, date_format(d.date, '%Y') as year, date_format(d.date, '%m') as month, date_format(d.date, '%d') as mday, date_format(d.last_pay_date, '%Y') as last_year, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%d') as last_mday, d.arrears_period, d.based_on as based_on, applic.group_id as group_id from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join employees o on applic.officer_id=o.employeeId join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where d.id='".$loan_id."'");
		
		$row = mysql_fetch_array($sth);
		if($mode !='Cash' && $mode != 'Cheque')
			$bank_account_id = 0;
		
		//$date = sprintf("%d-%02d-%02d", $year, $month, $mday);
		$date = $date." ".date('H:i:s');
		//FIND OUT THE UPPER BOUND OF THE CURRENT PAYMENT PERIOD
		$upper = mysql_fetch_array(mysql_query("select date from schedule where date >= '".$date."' and loan_id='".$loan_id."' order by date asc limit 1"));
		$upper_date = ($upper['date'] == NULL) ? $date : $upper['date'];

		//ARREARS
		$arrears_days = $calc->dateToDays($date) - $row['arrears_period'];
		$arrears_date = $calc->daysToDate($arrears_days, '%Y-%m-%d');
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id='".$loan_id."' and date < '".$arrears_date."'");
		//$resp->assign("status", "innerHTML", "select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id='".$loan_id."' and date < '".$arrears_date."'");
		//return $resp;
		
		$sched = mysql_fetch_array($sched_res);
				
		$paid_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from payment where loan_id='".$loan_id."' and date <= '".$date."'");
		
		$paid = mysql_fetch_array($paid_res);
		$sched_amount = ($sched['amount'] == NULL) ? "0" : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? "0" : $sched['int_amt'];
		$paid_amt = ($paid['amount'] == NULL) ? "0" : $paid['amount'];
		$paid_int = ($paid['int_amt'] == NULL) ? "0" : $paid['int_amt'];
		$arrears_amt = $sched_amount - $paid_amt;
		$int_arrears = $sched_int - $paid_int;
		
		
		if($arrears_amt <= 0){
			$princ_arrears =0;
			$int_arrears = 0;
			$total_arrears =0;
			$total_amt_arrears = 0;
			$int_amt_arrears =0;
			$princ_amt_arrears =0;
		}else{
			$princ_arrears = $arrears_amt;
			$princ_amt_arrears = $arrears_amt;
			
			$arrears_days =$arrears_days - $calc->dateToDays($row['last_mday'], $row['last_month'], $row['last_year']) -1;
			
			$total_arrears = $princ_arrears + $int_arrears;
			$total_amt_arrears = $total_arrears;
			$int_amt_arrears = $int_arrears;
			$int_arrears = ($int_arrears == 0) ? 0 : $int_arrears;
		}
		
		//CALCULATE DUE PRINCIPAL
		//$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id=".$loan_id." and date <= '".$date."'");
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id=".$loan_id." and date <= '".$upper_date."'");

	
		$sched = @mysql_fetch_array($sched_res);
		$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? 0 : $sched['int_amt'];
			
		$due_princ_amt = $sched_amt - $paid_amt;   //- $arrears_amt;
		$due_int_amt = $sched_int - $paid_int;   // - $int_amt_arrears;	
		
		$first_instal =0;
		if($due_princ_amt <= 0 && $paid_amt >= 0){
			$due_princ = 0; 
			if($due_princ_amt ==0 && $paid_int ==0){    //FIRST INSTALMENT, YOU PAY THE FIRST INTEREST
				$check = mysql_fetch_array(mysql_query("select * from schedule where loan_id='$loan_id' order by date asc limit 1"));
				$due_int_amt = $check['int_amt'];
				$due_int = $due_int_amt;
				$first_instal = 1;
			}else{
				$due_int = 0;
				$due_int_amt=0;
			}
		}else{
			$due_princ = $due_princ_amt;
			if($due_int_amt >0){	
				$due_int = $due_int_amt;
			}else
				$due_int=0; 
			
		}
		
		if($first_instal ==1)   //FIRST INSTALMENT, INCLUDE FIRST INTEREST
			$total_int = $check['int_amt'];
		else
			$total_int = $sched_int - $paid_int;
		
		$total_int = ($total_int < 0) ? 0 : $total_int;
		if($total_int >= $amount_paid){
			$total_int = $amount_paid;
			$princ_amt = 0;
		}else{
			$princ_amt = $amount_paid - $total_int;
		}
		
		$amount_topay = $total_int + $total_due_princ;

		
		if($princ_amt > $balance){
			$next = mysql_fetch_array(mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id='".$loan_id."'"));
			$nextp = mysql_fetch_array(mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from payment where loan_id='".$loan_id."'"));
			$enhanced_int = $next['int_amt'] - $nextp['int_amt'];
			if(($princ_amt - $balance) > ($enhanced_int - $total_int)){
				$resp->alert("Payment rejected! The payment must not exceed the total ".($enhanced_int+$balance) ."\nOutstanding Princ:".$balance."\nInterest Due:".$total_int."\nInterest for Next Periods:".($enhanced_int - $total_int)."\nTo clear the loan, register ".($balance+$total_int));
				$resp->assign("status", "innerHTML", "<FONT COLOR=RED>Payment rejected! The payment must not exceed the total ".($enhanced_int+$balance) ."\nOutstanding Princ:".$balance."\nInterest Due:".$total_int."\nInterest for Next Periods:".($enhanced_int - $total_int)."\nTo clear the loan, register ".($balance+$total_int)."</FONT>");
				return $resp;
			}
			$total_int = $total_int + $princ_amt - $balance;
			$princ_amt = $balance;
		}

		$rec_res = mysql_query("select receipt_no from collected where receipt_no ='".$receipt_no."' union select receipt_no from payment where receipt_no ='".$receipt_no."' union select receipt_no from other_income where receipt_no ='".$receipt_no."' union select receipt_no from deposit where receipt_no ='".$receipt_no."' union select receipt_no from shares where receipt_no='".$receipt_no."' union select receipt_no from sold_asset where receipt_no='".$receipt_no."'");
		if(@ mysql_numrows($rec_res) >0){
			$resp->alert("ERROR: The receiptNo already exists");
			return $resp;
		}
		
                   if($calc->dateToDays($mday, $month, $year) <= $calc->dateToDays($row['mday'], $row['month'], $row['year'])){
			$resp->alert("Payment rejected! The date entered is earlier than when the loan was disbursed");
			return $resp;
		}
		start_trans();
		
		$end_bal = $balance - $princ_amt;
		$bank_account_id = (intval($mode) > 0) ? 0 : $bank_account_id;

		//LOAN REPAYMENTS
		//$offset_res = mysql_query("select sum(princ_amt + int_amt) as amount from payment where mode='".$mode."'");
		$offset_res = mysql_query("select sum(princ_amt + int_amt) as amount from payment where mode='".$mode."'");
		$offset = mysql_fetch_array($offset_res);
		
		if(! mysql_query("insert into payment set loan_id='".$loan_id."', receipt_no='".$receipt_no."', princ_amt='".$princ_amt."', int_amt='".$total_int."', mode='".$mode."', begin_bal='".$balance."', end_bal='".$end_bal."', date='".$date."', bank_account='$bank_account_id', branch_id= (select branch_id from disbursed where id='".$loan_id."')")){	
			$resp->alert("ERROR: Could not insert into the payment table");
			rollback();
			return $resp;
		}
		
		
		if($total_int >0 && $total_int <= $amount_paid){
			if($first_instal ==1)
				$check = mysql_fetch_array(mysql_query("select * from schedule where  loan_id='$loan_id' order by date asc limit 1"));
			else
				$check = mysql_fetch_array(mysql_query("select * from schedule where date <= '$date' and loan_id='$loan_id' order by date desc limit 1"));
			//$last_pay_int_date = $calc->daysToDate($calc->dateToDays($mday, $month, $year) - $rem, '%Y-%m-%d');
			
			if(! mysql_query("update disbursed set balance=balance -".$princ_amt. ", last_pay_date='".$check['date']."' where id='".$loan_id."'")){
				$resp->alert("ERROR: Could not update the disbursement table");	
				rollback();
				return $resp;
			}
			/* $resp->assign("status", "innerHTML", "update disbursed set balance=balance -".$princ_amt. ", last_pay_date='".$check['date']."' where id='".$loan_id."'");
			rollback();
			return $resp;
			*/
		}else{
			if(! mysql_query("update disbursed set balance=balance -".$princ_amt. " where id='".$loan_id."'")){
				$resp->alert("ERROR: Could not update the disbursement table");	
				rollback();
				return $resp;
			}
		}
		
		//RESCHEDULE INTEREST FOR DECLINING BALANCE METHOD
		if($row['int_method'] == 'Declining Balance'){
			//$sched_res = mysql_query("select id from schedule where loan_id='".$loan_id."' and princ_amt>0 and date > '".$check['date']."' and date <= '".date('Y-m-d 23:59:59')."'");
			//FIND OUT THE UPPER BOUND OF THE CURRENT PAYMENT PERIOD
			$upper = mysql_fetch_array(mysql_query("select date from schedule where date >= '".$date."' and loan_id='".$loan_id."' order by date asc limit 1"));
			$upper_date = ($upper['date'] == NULL) ? $date : $upper['date'];

			//$sched_res = mysql_query("select id from schedule where loan_id='".$loan_id."' and princ_amt>0 and date > '".$check['date']."' and date <= '".date('Y-m-d 23:59:59')."'");
			$sched_res = mysql_query("select id from schedule where loan_id='".$loan_id."' and princ_amt>0 and date > '".$check['date']."' and date <= '".$upper_date."'");
			
			while($sched = mysql_fetch_array($sched_res)){
				$new_int = ($row['balance'] - $princ_amt) * (($row['int_rate']/100)/12) * ($row['pay_freq']/30);

				mysql_query("update schedule set int_amt ='".$new_int."' where id='".$sched['id']."'");
			}
		}
		if($penalty > 0){
			if(!mysql_query("update penalty set date='".$date."', status='paid', bank_account='$bank_account_id' where loan_id='".$loan_id."' and status='pending'")){
				$resp->alert("ERROR: Could not update the Penalty table");
				rollback();
				return $resp;
			}
		}

		//COMPULSORY SAVINGS/SHARES
		$base_res = mysql_query("select d.based_on as based_on, applic.mem_id as mem_id, applic.guarantor1 as guarantor1, applic.guarantor2 as guarantor2 from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id where d.id=$loan_id");
		$base = mysql_fetch_array($base_res);
		
		$limit_res = mysql_query("select * from disbursed where id='".$loan_id."'");
		$limit = mysql_fetch_array($limit_res);
		if($base['based_on'] == 'savings'){
			$comp_amt =  ($limit['loan_save_percent'] == 0) ? 0 : ($princ_amt * 100) / $limit['loan_save_percent'];
			$guaranteed_amt = ($limit['guarantor_save_percent'] == 0) ? 0 : ($princ_amt * 100) / $limit['guarantor_save_percent'];
			$comp_res = mysql_query("select s.id as id from savings_product s join accounts a on s.account_id=a.id where a.name='Compulsory Savings'");
			$class = "Compulsory Savings";
		}else{
			$comp_amt = ($limit['loan_share_percent'] == 0) ? 0 : ($princ_amt * 100) / $limit['loan_share_percent'];
			$guaranteed_amt = ($limit['guarantor_share_percent'] == 0) ? 0 :  ($princ_amt * 100) / $limit['guarantor_share_percent'];
			$comp_res = mysql_query("select s.id as id from savings_product s join accounts a on s.account_id=a.id where a.name='Compulsory Shares'");
			$class = "Compulsory Shares";
		}
		$comp = mysql_fetch_array($comp_res);
		//FOR BORROWER
		$post_res = mysql_query("select id as memaccount_id from mem_accounts where mem_id='".$base['mem_id']."' and saveproduct_id='".$comp['id']."'");
		$post = mysql_fetch_array($post_res);
		if(! mysql_query("update deposit set amount=amount - ".$comp_amt ." where memaccount_id='".$post['memaccount_id']."'")){
			$resp->alert("ERROR: Payment not registered! \n Could not update ".$class);
			rollback();
			return $resp;
		}
		//FOR GUARANTOR 1
		$post_res = mysql_query("select id as memaccount_id from mem_accounts where mem_id='".$base['guarantor1']."' and saveproduct_id='".$comp['id']."'");
		$post = mysql_fetch_array($post_res);
		if(! mysql_query("update deposit set amount=amount - ".$guaranteed_amt ." where memaccount_id='".$post['memaccount_id']."'")){
			$resp->alert("ERROR: Payment not registered! \n Could not update ".$class." for guarantor 1");
			rollback();
			return $resp;
		}
		//FOR GUARANTOR 2
		$post_res = mysql_query("select id as memaccount_id from mem_accounts where mem_id='".$base['guarantor2']."' and saveproduct_id='".$comp['id']."'");
		$post = mysql_fetch_array($post_res);
		if(! mysql_query("update deposit set amount=amount - ".$guaranteed_amt ." where memaccount_id='".$post['memaccount_id']."'")){
			$resp->alert("ERROR: Payment not registered! \n Could not update ".$class." for guarantor 2");
			rollback();
			return $resp;
		}
		$amount_paid = $amount_paid + $penalty;
		if($mode =='Cash' || $mode=='Cheque'){   //CREDIT THE BANK/CASH ACCOUNT ONLY IF YOU RECEIVE MONEY
			if(! mysql_query("update bank_account set account_balance=account_balance+".$amount_paid." where id='".$bank_account_id."'")){
				$resp->alert("ERROR: Could not update bank account balance");	
				rollback();
				return $resp;
			}
		}else{
			//ACCOUNT BALANCE
				$dep_res = mysql_query("select sum(amount - flat_value - percent_value) as amount from deposit where memaccount_id='".$mode."'");
				$dep = mysql_fetch_array($dep_res);
				$dep_amt = ($dep['amount'] != NULL) ? $dep['amount'] : 0;
				//WITHDRAWALS
				$with_res = mysql_query("select sum(amount + flat_value + percent_value) as amount from withdrawal where memaccount_id='".$mode."'");
				$with = mysql_fetch_array($with_res);
				$with_amt = ($with['amount'] != NULL) ? $with['amount'] : 0;
				//MONTHLY CHARGES 
				$charge_res = mysql_query("select sum(amount) as amount from monthly_charge where memaccount_id='".$mode."'");
				$charge = mysql_fetch_array($charge_res);
				$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
				//INTEREST AWARDED
				$int = mysql_fetch_array(mysql_query("select sum(amount) as amount from save_interest where memaccount_id='".$mode."'"));
				$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;
				
				//$resp->assign("status", "innerHTML", "already paid : ".$offset['amount'].", paying:$amount_paid");
				//rollback();
				//return $resp;

				$pay_amt = ($offset['amount'] == NULL) ?  0 : $offset['amount'];

				$balance = $dep_amt + $int_amt  - $with_amt - $charge_amt - $pay_amt;
				//IF INSUFFICIENT SAVINGS
				if($balance < $amount_paid){
					$resp->alert("Payment not registered! \n Insufficient savings (".$balance.") to offset the loan balance");
					rollback();
					return $resp;
				}
		}
		//if($_SESSION['commit'] == 1)
		commit();

		$sth = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as disbursed_amt, d.date as date, d.balance as balance, d.period as loan_period, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where d.id='".$loan_id."'");
		$row = mysql_fetch_array($sth);	
		
		//ARREARS
		$arrears_days = $calc->dateToDays(date('d'), date('m'), date('Y')) - $row['arrears_period'];
		$arrears_date = $calc->daysToDate($arrears_days, '%Y-%m-%d');
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id='".$loan_id."' and date < '".$arrears_date."'");
		
		$sched = @mysql_fetch_array($sched_res);
				
		$paid_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from payment where loan_id='".$loan_id."' and date <= NOW()");
		
		$paid = mysql_fetch_array($paid_res);
		$sched_amount = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? "0" : $sched['int_amt'];
		$paid_amt = ($paid['amount'] == NULL) ? "0" : $paid['amount'];
		$paid_int = ($paid['int_amt'] == NULL) ? "0" : $paid['int_amt'];
		$arrears_amt = $sched_amount - $paid_amt;
		$int_arrears = $sched_int - $paid_int;
		
		if($arrears_amt <= 0){
			$princ_arrears =0;
			$int_arrears = 0;
			$int_amt_arrears =0;
		}else{
			$princ_arrears = $arrears_amt;
			$int_amt_arrears = $int_arrears;

			$arrears_days =$arrears_days - $calc->dateToDays($row['last_mday'], $row['last_month'], $row['last_year']);
			$no_months = floor($arrears_days / 30);
			/* if($no_months <= 0 ){
				$int_arrears = 0;		
			}elseif($row['int_method'] == 'Declining Balance')
				$int_arrears = ((($row['balance'] * $row['int_rate']/100) /12) * $no_months);
			elseif($row['int_method'] == 'Flat')
				$int_arrears = ((($row['disbursed_amt'] * $row['int_rate']/100) /12) * $no_months);
			elseif($row['int_method'] == 'Armotised')
				$int_arrears = $sched_int - $paid_int;
			*/
		}
		
		//CALCULATE DUE PRINCIPAL
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id='".$loan_id."' and date <= CURDATE()");
		$sched = @mysql_fetch_array($sched_res);
		$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? 0 : $sched['int_amt'];
		$due_princ_amt = $sched_amt - $paid_amt; //- $arrears_amt;
		$due_int_amt = $sched_int - $paid_int;  // - $int_amt_arrears;

		if($due_princ_amt <= 0){
			$princ_due = 0;
			$int_due=0;
		}else{
			$princ_due = $due_princ_amt;
			$int_due = $due_int_amt;
		}
		
		//$resp->alert("Princ Due=".$princ_due. ", Int Due=".$int_due);
		$resp->call('xajax_add_pay', $loan_id, $row['applic_id'],   $cust_name, $cust_no, $row['account_name'],  $loan_officer, $princ_arrears, $int_arrears, $princ_due, $int_due, $from_date, $to_date, $status, $cheque_no,$branch_id);
		$resp->assign("status", "innerHTML", "<font color=red>Payment registered</font>");
	}
	return $resp;
}
