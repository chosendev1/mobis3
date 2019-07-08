<?php
$xajax->registerFunction("add_saveproduct");
$xajax->registerFunction("list_saveproduct");
$xajax->registerFunction("insert_saveproduct");
$xajax->registerFunction("update_saveproduct");
$xajax->registerFunction("update2_saveproduct");
$xajax->registerFunction("delete_saveproduct");
$xajax->registerFunction("delete2_saveproduct");
$xajax->registerFunction("chartofaccounts");
$xajax->registerFunction("chart_view");
$xajax->registerFunction("edit_account");
$xajax->registerFunction("inser_account");
$xajax->registerFunction("edit_account");
$xajax->registerFunction("pledged");
$xajax->registerFunction("edit_saveproduct");
$xajax->registerFunction("open_account");
$xajax->registerFunction("insert_account");
$xajax->registerFunction("list_accounts");
$xajax->registerFunction("edit_account");
$xajax->registerFunction("update_account");
$xajax->registerFunction("update2_account");
$xajax->registerFunction("delete_account");
$xajax->registerFunction("delete2_account");
$xajax->registerFunction("add_deposit");
$xajax->registerFunction("insert_deposit");
$xajax->registerFunction("list_deposits");
$xajax->registerFunction("edit_deposit");
$xajax->registerFunction("update_deposit");
$xajax->registerFunction("update2_deposit");
$xajax->registerFunction("delete_deposit");
$xajax->registerFunction("delete2_deposit");
$xajax->registerFunction("add_withdrawal");
$xajax->registerFunction("insert_withdrawal");
$xajax->registerFunction("list_receiver_accts");
$xajax->registerFunction("list_withdrawal");
$xajax->registerFunction("edit_withdrawal");
$xajax->registerFunction("update_withdrawal");
$xajax->registerFunction("update2_withdrawal");
$xajax->registerFunction("delete_withdrawal");
$xajax->registerFunction("delete2_withdrawal");
$xajax->registerFunction("manage_account");
$xajax->registerFunction("manage2_account");
$xajax->registerFunction("savings_ledger");
$xajax->registerFunction("savings_ledger_form");
$xajax->registerFunction("search_receipt");
#MONEY TRANSFER
$xajax->registerFunction("register_transfer_form");
$xajax->registerFunction("register_transfer");
$xajax->registerFunction("list_transfer");
$xajax->registerFunction("edit_transfer");
$xajax->registerFunction("update_transfer");
$xajax->registerFunction("delete_transfer");
$xajax->registerFunction("list_pendwithdrawal");
$xajax->registerFunction("confirm_approve_overdraft");
$xajax->registerFunction("confirm_deny_overdraft");
$xajax->registerFunction("setDeductionForm");
$xajax->registerFunction("saveDeductionAmount");
$xajax->registerFunction("insertDeposit");
$xajax->registerFunction("confirmDeposit");
$xajax->registerFunction("confirmWithdrawal");

function add_saveproduct(){
	$resp = new xajaxResponse();
	
	$content ="";
	$content .="   <div class='col-md-12'>
  <form method='post' class='panel panel-default'>";
$content .= '<div class="panel-heading">
  			  		
                                 
                                        <h4 class="panel-title">CREATE A SAVINGS PRODUCT</h4>
                                               		
                                           	 </div>
                                        <div class="panel-body">';
                                        
	 
	$content .= '<div class="form-group">
                                            <label class="control-label">Branch:</label>
                                            <div class="col-sm-6"><span>'.branch().'</span></div>                                            
                                            </div>';                                           
                                            $content .='<div class="form-group">
                                            <label class="control-label">Product Name(From Chart of Accounts):</label>
                                            <div class="col-sm-6">
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
                                            <label class="control-label">Opening Balance:</label>
                                            <div class="col-sm-6">
                                           <input type="int" name="opening_bal" id="opening_bal" class="form-control">
                                            </div></div>';                                            
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Minimum Balance:</label>
                                            <div class="col-sm-6">
                                           <input type="int" name="min_bal" id="min_bal" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Maturity Period (Months before Loan):</label>
                                            <div class="col-sm-6">
                                           <input type="int" name="grace_period" id="grace_period" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Interest Frequency:</label>
                                            <div class="col-sm-6">
                                           <select type="int" id="int_frequency" class="form-control"><option value=0><option value=1>1 Month<option value=2>2 Months<option value=3>3 Months<option value=4>4 Months<option value=5>5 Months<option value=6>6 Months<option value=7>7 Months<option value=8>8 Months<option value=9>9 Months<option value=10>10 Months<option value=11>11 Months<option value=12>12 Months<option value=24>24 Months</select>
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Interest Rate % Per Annum:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" name="int_rate" id="int_rate" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Tax % on Interest:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" name="tax_rate" id="tax_rate" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Charge % on Withdrawal:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" name="withdrawal_perc" id="withdrawal_perc" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Flat Charge on Withdrawal:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" name="withdrawal_flat" id="withdrawal_flat" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Charge % on Deposit:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" name="deposit_perc" id="deposit_perc" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Flat Charge on Deposit:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" name="deposit_flat" id="deposit_flat" class="form-control">
                                            </div></div>';                                            
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Monthly Charge:</label>
                                            <div class="col-sm-6">
                                           <input type="int" name="monthly_charge" id="monthly_charge" class="form-control">
                                            </div></div>';
                                            $content .= "<div class='panel'><button type='reset' class='btn btn-default' onclick=\"xajax_add_saveproduct()\">Reset</button>
                                            <button type='button' class='btn btn-primary'  onclick=\"xajax_insert_saveproduct(getElementById('account_id').value,  getElementById('opening_bal').value, getElementById('min_bal').value, getElementById('grace_period').value, getElementById('int_frequency').value, getElementById('int_rate').value,  getElementById('withdrawal_perc').value, getElementById('withdrawal_flat').value, getElementById('deposit_perc').value, getElementById('deposit_flat').value, getElementById('monthly_charge').value, getElementById('branch_id').value, getElementById('tax_rate').value); return false; \">Save</button>";
                                            $content .= '</div></div></form></div>';
          
          
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function edit_saveproduct($saveproduct_id){
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
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

//FILL IN THE PLEDGED ACCOUNTS
function pledged($account_id, $saveproduct_id){
	$resp = new xajaxResponse();
	$sth = mysql_query("select * from accounts where id='".$account_id."'");
	$row = mysql_fetch_array($sth);
	$former_res = mysql_query("select a.name as name, a.account_no as account_no, s.account_id as account_id from savings_product s join accounts a on s.pledged_account_id=a.id where s.id=".$saveproduct_id."");
	
	$account_no = $row['account_no'];
	if(@mysql_numrows($former_res) > 0){
		$former = mysql_fetch_array($former_res);
		$content = "<tr><td></td><td><input type=hidden name='former_pledged_id' value='".$former['account_id']."'><select name='pledged_account_id' id='pledged_account_id'><option value=".$former['account_id'].">".$former['account_no']." - ".$former['name'];
	}else{
		$content = "<tr><td></td><td><select name='pledged_account_id' id='pledged_account_id'><option value='0'>Not Applicable";
	}
	$level1_res = mysql_query("select * from accounts where account_no like '211%' and account_no not like '211' and account_no <= 2119 and branch_no like '".$_SESSION['branch_no']."' and account_no<>'".$account_no."' and id not in (select s.account_id from savings_product s join accounts a on s.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."' and s.type='free')");
	
	while($level1 = mysql_fetch_array($level1_res)){
		$level2_res = mysql_query("select * from accounts where account_no like '".$level1['account_no']."%' and account_no not like '".$level1['account_id']."' and account_no <= 2119 and branch_no like '".$_SESSION['branch_no']."' and account_no<>'".$account_no."' and id not in (select s.account_id from savings_product s join accounts a on s.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."' and s.type='free')");
		
		if( mysql_numrows($level2_res) == 0){
			$resp->alert($level1['account_no']);
			$content .= "<option value='".$level1['id']."'>".$level1['account_no']. "-". $level1['name']; 
		}else{
			while($level2 = mysql_fetch_array($level2_res)){
				$level3_res = mysql_query("select * from accounts where account_no like '".$level2['account_no']."%' and account_no not like '".$level2['account_no']."' and account_no <= 211999 and branch_no like '".$_SESSION['branch_no']."' and account_no<>'".$account_no."' and id not in (select s.account_id from savings_product s join accounts a on s.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."' and s.type='free')");
				if(@ mysql_numrows($level3_res) == 0)
					$content .= "<option value='".$level2['id']."'>".$level2['account_no']. "-". $level2['name']; 
				else{
					while($level3 = mysql_fetch_array($level3_res)){
						$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no not like '".$level3['account_id']."' and account_no <= 21199999 and branch_no like '".$_SESSION['branch_no']."' and account_no<>'".$account_no."' and id not in (select s.account_id from savings_product s join accounts a on s.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."' and s.type='free')");
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
	$content .= "</select>";
	$resp->assign("pledged_put", "innerHTML", $content);
	$resp->assign("pledged_text", "innerHTML", "Corresponding Pledged Account");
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
			//$color = ($i%2 == 0) ? "lightgrey" : "white";
			$name = ($row['type']=='free') ? "<a href='javascript:;' onclick=\"xajax_edit_saveproduct(".$row['saveproduct_id']."); return false;\">".$row['account_no']." - ".$row['name']."</a>"  :  $row['account_no']." - ".$row['name'];
			$content .= "<tr><td>".$name."</td><td>".$row['opening_bal']."</td><td>".$row['min_bal']."</td><td>".$row['int_rate']."</td><td>".$row['tax_rate']."</td><td>".$row['withdrawal_perc']."</td><td>".$row['withdrawal_flat']."</td><td>".$row['deposit_perc']."</td><td>".$row['deposit_flat']."</td><td>".$row['monthly_charge']."</td><td>".$row['int_frequency']."</td><td>".$row['type']."</td></tr>";
			$i++;
				
		}
	}
	$content .= "</tbody></table></div>";
	$resp->call("createTableJs");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


//OPEN AN A PRODUCT ACCOUNT FOR A MEMBER
function open_account($mem_no){
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
                                                                             	
	        $content = '
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">OPEN A SAVINGS ACCOUNT FOR A MEMBER</h3>
                                          
                            <div class="panel-body">';
	
	        $content .= '<div class="form-group">
                                    
                                        <div class="col-sm-3">
                                            <label class="control-label">Enter Member No:</label>
                                            <div class="input-group">
                                            <input type="int" id="mem_no1" name="mem_no1" class="form-control">
                                            <span class="input-group-btn">
                                                <button class="btn btn-info" type="button" onclick=\'xajax_open_account(getElementById("mem_no1").value); return false;\'>Next</button>
                                            </span>
                                        </div>                                             
                                        </div>
                                       

                            
                                       <div class="col-sm-3">
                                            <label class="control-label">OR Select:</label>
                                             <div class="input-group">
                                            <select class="form-control" name="mem_no2" id="mem_no2"><option value=0>Select Member';
		$sth = mysql_query("select * from member  order by  first_name, last_name");
		while($row = mysql_fetch_array($sth)){
			$content .= "<option value='".$row['mem_no']."'> ".$row['first_name']." ".$row['last_name'] ." - ".$row['mem_no'];
		}
                                      $content .= '</select><span class="input-group-btn">
                                                <button class="btn btn-info" type="button" onclick=\'xajax_open_account(getElementById("mem_no2").value); return false;\'>Next</button>
                                            </span>
                                        </div></div>
                                    </div>
                                </div>';
		$content.="</div></form></div></div>";
		//$content.="<font color=red>Search a member whose account you want to open</font></div></form></div></div>";
		
        if($mem_no == ''){
		$cont ="<font color=red>Enter or Select member whose account you want to open</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
		}
		
	elseif($mem_no != ''){
		$sth = mysql_query("select * from member where mem_no= '".$mem_no."'");
		if(@ mysql_numrows($sth)==0){
			$resp->alert("No member identified by that Member No");
			return $resp;
		}else{
			$row = mysql_fetch_array($sth);
			$branch = mysql_fetch_assoc(mysql_query("select branch_name from branch where branch_no='".$row['branch_id']."'"));
			
		       $content .='
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="alert alert-info">
                              </p><table class="borderless" width="100%"><tr><td>BRANCH: <font color="#00BFFF"><span class="lead">'.$branch['branch_name'].'</span></font></td>
			<td>MEMBER No: <font color="#00BFFF"><span class="lead">'.$row['mem_no'].'</span></font></td>
				<td>MEMBER NAME:&nbsp;<input type=hidden name="mem_id" value="'.$row['id'].'" id="mem_id"><font color="#00BFFF"><span class="lead">'.strtoupper($row['first_name'].' '.$row['last_name']).'</span></font></td></tr></table></p>
                            </div>               
                            <div class="panel-body">';
                        
                         $content.='<div class="form-group">
                                   
                                        <div class="col-sm-2">
                                          <img src="photos/'.$row['photo_name'].'?dummy='.time().'" width=90 height=90 alt="photo">

                                          <img src="signs/'.$row['sign_name'].'?dummy='.time().'" width=90 height=90 alt="Signature">
                                    </div>
                                 </div>';
                            
                        $content .= '<div class="col-sm-5"><div class="form-group">
                                    
                                        
                                            <label class="control-label">Savings Product:</label>
                                            <select class="form-control" name="saveproduct_id" id="saveproduct_id" class="form-control"><option value="">';
				$prod_res = mysql_query("select a.name as name, s.account_id as account_id, a.account_no as account_no, s.id as id from savings_product s join accounts a on s.account_id=a.id where s.type='free' and s.id not in (select saveproduct_id from mem_accounts where mem_id=".$row['id'].")");
				if(mysql_numrows($prod_res) == 0){
					$resp->alert("The member has accounts with all the Savings Products available\n Please create new product");
					return $resp;
				}
				while($prod = mysql_fetch_array($prod_res))
					$content .= "<option value='".$prod['id']."'>".$prod['account_no'] ." - ".$prod['name'];       
                                      $content.='</select></div>                                        
                                   
                                ';
                                
			$content .='<div class="form-group">
      <label class="control-label">Saving Amount Per end of period (Optional):</label>
                                           <input type="text" class="form-control" id="amt" name="amt" /> 
                                           </div>  
                                           <div class="form-group">

                 <label class="control-label">Date:</label>
                                           <input type="text" class="form-control" id="date" name="date" placeholder="'.date('Y-m-d').'" /> 
                                      
                                    </div>
                             ';
			                               
			 $content .= '<div class="panel-footer">                              
                                
                                <input type="button" class="btn  btn-primary" value="Save"  onclick=\'xajax_insert_account(getElementById("mem_id").value, getElementById("saveproduct_id").value,  getElementById("date").value,"'.$row['branch_id'].'", getElementById("amt").value); return false;\'>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>';
                    $resp->call("createDate","date");
							
		}
	}
	//$content .= "</table></form>";
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//EDIT A PRODUCT ACCOUNT FOR A MEMBER
function edit_account($mem_account_id, $group_name, $mem_no, $name, $product_type,$branch_id,$num_rows,$stat,$cur_page){
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$resp->assign("status", "innerHTML", "");
	
	$sth = mysql_query("select m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, m.sign_name, m.photo_name, a.name as name, a.account_no as account_no, s.id as id, d.open_date as open_date, date_format(open_date, '%Y') as year,  date_format(open_date, '%m') as month, date_format(open_date, '%d') as mday, d.saveproduct_id as saveproduct_id,amount from mem_accounts d join member m on d.mem_id=m.id join savings_product as s on d.saveproduct_id=s.id join accounts a on s.account_id=a.id where d.id='".$mem_account_id."'");
		if(@ mysql_numrows($sth)==0){
			$cont = "<div><font color=red>No such member exists!</div>";
			$resp->assign("status", "innerHTML", "$cont");
			return $resp;
		}
		else{
			$row = mysql_fetch_array($sth);
			
	
	$content ='
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">

                            <div class="panel-heading">
                            <h4 class="semibold text-primary">EDIT A PRODUCT ACCOUNT FOR A MEMBER</h4>
                            </div>
                            <div class="alert alert-info">
                              </p><table class="borderless" width="100%">
			<tr><td><b>Member No:</b></td><td>'.$row['mem_no'].'</td>
				<td><b>Member Name:</b></td><input type=hidden name="mem_account_id" value="'.$mem_account_id.'" id="mem_account_id"><td>'.$row['first_name'].' '.$row['last_name'].'</td></tr></table></p>
                            </div>               
                            <div class="panel-body">';
                        
                        $content.='<div class="col-sm-2">

                                   <img src="photos/'.$row['photo_name'].'?dummy='.time().'" width=90 height=90 alt="Photo">

                                   <img src="signs/'.$row['sign_name'].'?dummy='.time().'" width=90 height=90 alt="Signature">
                                 </div>';
                            
                        $content .= '  <div class="col-sm-5">
                        <div class="form-group">
                                            <label class="control-label">Savings Product:</label>
                                            <select class="form-control" name="saveproduct_id" id="saveproduct_id"><option value="'.$row['saveproduct_id'].'">'.$row['account_no'].' - '.$row['name'];
				$prod_res = mysql_query("select a.name as name, s.account_id as account_id, a.account_no as account_no, s.id as id from savings_product s join accounts a on s.account_id=a.id where s.type='free' and s.id not in (select saveproduct_id from mem_accounts where mem_id=".$row['id'].")");
				if(mysql_numrows($prod_res) == 0){
					$resp->alert("The member has accounts with all the Savings Products available\n Please create new product");
					return $resp;
				}
				while($prod = mysql_fetch_array($prod_res))
					$content .= "<option value='".$prod['id']."'>".$prod['account_no'] ." - ".$prod['name'];
				$open_date = $prod['open_date'];
				$content .= '</select></div>                                        
                                    
                               ';
                                
			$content .='<div class="form-group">
                                    
                                       
                                            <label class="control-label">Savings Amount per end of period (optional):</label>
                                            <input type="text" class="form-control" id="amt" name="amt" value="'.$row['amount'].'" />
                                        </div>

                                        <div class="form-group">
                                    
                                       
                                            <label class="control-label">Date:</label>
                                            <input type="text" class="form-control" id="date" name="date" value="'.$row['year']."-".$row['month']."-".$row['mday'].'" />
                                        </div>
                                    </div>
                                </div>';                            
                             		                                
			 $content .= '<div class="panel-footer">                              
                                
                                <input type="button" class="btn  btn-inverse mb5" value="Back"  onclick=\'xajax_list_accounts("'.$group_name.'", "'.$mem_no.'", "'.$name.'", "'.$product_type.'","'.$branch_id.'","'.$num_rows.'","'.$stat.'","'.$cur_page.'"); return false;\'>&nbsp;</a>&nbsp;<input type="button" class="btn  btn-danger mb5" value="Delete"  onclick=\'xajax_delete_account("'.$mem_account_id.'")\'>&nbsp;<input type="button" class="btn  btn-primary" value="Update"  onclick=\'xajax_update_account("'.$mem_account_id.'", getElementById("saveproduct_id").value,  getElementById("date").value, getElementById("amt").value);return false;\'>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>';
                    $resp->call("createDate","date");
                    
            }
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//UPDATE MEMBER ACCOUNTS
function update_account($memaccount_id, $saveproduct_id, $date,$amt){

        list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$calc = new Date_Calc();
	$sth = mysql_query("select id from deposit where memaccount_id='".$memaccount_id."' union select id from withdrawal where memaccount_id='".$memaccount_id."'");
	if(mysql_numrows($sth) > 0 ){
        $resp->alert("Cannot update account creation date! \n Transactions already exist ");
        }
//	if(mysql_numrows($sth) > 0 ){
//test if savepdt and date has changed
/*$sav = mysql_fetch_array(mysql_query("select * from mem_accounts where id='".$memaccount_id."'"));
if($sav['saveproduct_id'] != $saveproduct_id){
$resp->alert("Cannot update account type! \n Transactions already exist ");
}elseif($sav['open_date'] != $date){
		$resp->alert("Cannot update account creation date! \n Transactions already exist ");
  } */
//}
	elseif(!$calc->isValidDate($mday, $month, $year))
		$resp->alert("Update rejected! Please enter valid date");
	elseif($calc->isFutureDate($mday, $month, $year))
		$resp->alert("Update rejected! You have entered a future date");
	else{
		$resp->confirmCommands(1, "Do you really want to update?");
		$resp->call('xajax_update2_account', $memaccount_id, $saveproduct_id, $date,$amt);
	}
	return $resp;
}

//CONFIRM MEMBER ACCOUNTS
function update2_account($memaccount_id, $saveproduct_id, $date,$amt){
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$open_date = $date;
	if(! mysql_query("update mem_accounts set saveproduct_id='".$saveproduct_id."', open_date='".$open_date."', amount='".$amt."' where id='".$memaccount_id."'"))
		$resp->alert("ERROR: Could not update account");
	else{
		//////////////////////
		$accno = mysql_fetch_assoc(mysql_query("select a.account_no,a.name from accounts a join savings_product s on s.account_id=a.id where s.id=".$saveproduct_id));
		$name =mysql_fetch_assoc(mysql_query("select m.first_name,m.last_name,m.mem_no from member m join mem_accounts ma on m.id=ma.mem_id where ma.id=".$memaccount_id));
		$action = "update mem_accounts set saveproduct_id='".$saveproduct_id."', open_date='".$open_date."' where id='".$memaccount_id."'";
		
			$msg = "Edited an account set Open date:".$open_date.", ac/no:".$accno['account_no']." - ".$accno['name'].". Member: ".$name['first_name']." ".$name['last_name']." - ".$name['mem_no'];
			log_action($_SESSION['user_id'],$action,$msg);
			//////////////////
		
		$resp->assign("status", "innerHTML", "<font color=red>Member's Account updated</font>");
	}
	return $resp;
}

//DELETE MEMBER ACCOUNT
function delete_account($memaccount_id){
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	$sth = mysql_query("select id from deposit where memaccount_id='".$memaccount_id."' union select id from withdrawal where memaccount_id='".$memaccount_id."'");
	if(mysql_numrows($sth) > 0 )
		$resp->alert("Cannot delete account! \n Transactions already exist ");
	else{
		$resp->confirmCommands(1, "Do you really want to delete?");
		$resp->call('xajax_delete2_account', $memaccount_id);
	}
	return $resp;
}

//CONFIRM DELETE A MEMBER ACCOUNT
function delete2_account($memaccount_id){
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	$sth = mysql_query("select * from mem_accounts where id='".$memaccount_id."'");
	$row = mysql_fetch_array($sth);
	if(@ mysql_numrows(mysql_query("select mem.* from mem_accounts join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where a.name <>'Compulsory Savings' and a.name <>'Compulsory Shares' and mem.id<>'".$memaccount_id."' and mem.mem_id='".$row['mem_id']."'")) == 0){
		$pledged_res = mysql_query("select mem.* from  mem_accounts mem join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where (a.name ='Compulsory Savings' or a.name='Compulsory Shares') and mem.mem_id='".$row['mem_id']."'");
		//FOR COMPULSORY SAVINGS
		$pledged = @mysql_fetch_array($pledged_res);
		mysql_query("delete from deposit where memaccount_id='".$pledged['id']."'");
		mysql_query("delete from mem_accounts where id='".$pledged['id']."'");
		//FOR COMPULSORY SHARES
		$pledged = @mysql_fetch_array($pledged_res);
		mysql_query("delete from deposit where memaccount_id='".$pledged['id']."'");
		mysql_query("delete from mem_accounts where id='".$pledged['id']."'");

	}
	$accno = mysql_fetch_assoc(mysql_query("select a.account_no,a.name,s.opening_bal from accounts a join savings_product s on s.account_id=a.id join mem_accounts m on m.saveproduct_id=s.id where m.id =".$memaccount_id));
	$name =mysql_fetch_assoc(mysql_query("select m.first_name,m.last_name,m.mem_no from member m join mem_accounts ma on m.id=ma.mem_id where ma.id=".$memaccount_id));
	if(! mysql_query("delete from mem_accounts where id='".$memaccount_id."'"))
		$resp->alert("ERROR: Could not delete the account");
	else{
		
			////////////////////
			$action = "delete from mem_accounts where id='".$memaccount_id."'";
			$msg = "Deleted a member account from ac/no: ".$accno['account_no']." - ".$accno['name']." that contained an openning Balance:".number_format($accno['opening_bal']).". Member: ".$name['first_name']." ".$name['last_name']." - ".$name['mem_no'];
			log_action($_SESSION['user_id'],$action,$msg);
			//////////////////
		
		$resp->assign("status", "innerHTML", "<font color='red'>Member's Account deleted</font>");
	}
	return $resp;
}
//INSERT SAVINGS PRODUCT ACCOUNT INTO DB
function insert_account($mem_id, $saveproduct_id, $date,$branch_id,$amt){
        list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	//$resp->alert(1);
	if($saveproduct_id=='')
		$resp->alert("You may not leave the product field blank");
	else{
		//$open_date = sprintf("%d-%02d-%02d", $date);
    //check for multiple account opening
$c = mysql_query("select * from mem_accounts where mem_id = '".$mem_id."' and saveproduct_id = '".$saveproduct_id."'");
if(mysql_num_rows($c) > 0){
 $resp->alert("Member already has an account with the selected product."); 
}else{
    //end
		$open_date = $date;
		$last_charge_date = sprintf("%d-%02d-28", $year, $month);
		mysql_query("insert into mem_accounts set mem_id='".$mem_id."', saveproduct_id='".$saveproduct_id."', open_date='".$open_date."', close_date='2032-12-31', last_award_date='".$open_date."', now_award_date='".$open_date."', last_charge_date='".$last_charge_date."',amount='".$amt."', branch_id=".$branch_id);

		///////////////
			$accno = mysql_fetch_assoc(mysql_query("select first_name,last_name,mem_no,telno from member where id=".$mem_id));
			$name =mysql_fetch_assoc(mysql_query("select m.first_name,m.last_name,m.mem_no from member m join mem_accounts ma on m.id=ma.mem_id where ma.id=".$mem_id));
			$action = "insert into mem_accounts set mem_id='".$mem_id."', saveproduct_id='".$saveproduct_id."', open_date='".$open_date."', close_date='2032-12-31', last_award_date='".$open_date."', now_award_date='".$open_date."', last_charge_date='".$last_charge_date."'";
			$msg = "Opened an account with open date set to:".$open_date." for member: ".$accno['first_name']." ".$accno['last_name']." - ".$accno['mem_no'];
			log_action($_SESSION['user_id'],$action,$msg);
			//////////////////
			
		$resp->alert("Account opened successfully");
    $resp->call("xajax_open_account()");
	}
}
	return $resp;
}

//LIST SAVINGS PRODUCT ACCOUNTS FOR MEMBERS
function list_accounts($group_name, $mem_no, $name, $product_id,$branch_id,$num_rows,$stat,$cur_page){
        $max_page =1;
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	$branch = ($branch_id=='all'||$branch_id=='')?NULL:"and m.branch_id=".$branch_id;
	
	
	$content = '
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <p><h5 class="semibold text-primary">SEARCH FOR SAVINGS PRODUCT ACCOUNTS FOR MEMBERS</h5></p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    
                                        <div class="col-sm-3">
                                            <label class="control-label">Group Name:</label>
                                            <input type="varchar(250)" id="group_name" value="All" class="form-control">                                            
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label">Member No:</label>
                                            <input type=int id="smem_no" value="All" class="form-control">
                                        </div>
                                   
                               
                                
                   
                                    
                                        <div class="col-sm-3">
                                            <label class="control-label">Member Name:</label>
                                            <input type="text" id="sname" value="All" class="form-control">                                            
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label">Savings Product:</label>
                                            <select name"product_id", id="product_id" class="form-control"><option value="">All';
	$sth = mysql_query("select a.name as name, a.account_no as account_no, p.id as id from savings_product p join accounts a on p.account_id=a.id where a.name not like 'Compulsory Shares' and a.name not like 'Compulsory Savings' order by a.account_no, a.name");
	while($row = mysql_fetch_array($sth)){
		$content .="<option value='".$row['id']."'>".$row['account_no'] ." - ".$row['name'];
	}
                                $content .='</select></div></div>
                                    
                              
     
        <div class="form-group">
        
                 
                                        <div class="col-sm-3">   <br>
                                            <label class="control-label">Branch:</label>
                                            <span>'.branch_rep($branch_id).'</span>
	                                          
                                        </div>
                                        <div class="col-sm-3">
                                              <br>
                                            <span>'.pages($num_rows).'</span>                         
                                        </div>
                                    </div>
                                </div>';
                                                              
                
	$content .= '<div class="">                              
                                
                                <input type="button" class="btn  btn-primary" value="Search" onclick=\'xajax_list_accounts(getElementById("group_name").value, getElementById("smem_no").value, getElementById("sname").value, getElementById("product_id").value,getElementById("branch_id").value,getElementById("num").value,"0","1"); return false;\'>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>';

	if($mem_no=='' && $name=='' && $product_id==''){
		$cont = "<font color=red>Please enter your search criteria";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
		$group_n = ($group_name == 'All') ? "(g.name is null or m.id in (select mem_id from group_member))" : "g.name like '%".$group_name."%'";
		$mem_no1 = ($mem_no == 'All') ? "" : $mem_no;
		$name1 = ($name == 'All') ? "" : $name;
		if($product_id == '')
			$product = " and s.id like '%%'";
		else
			$product =" and s.id='".$product_id."'";
	  	
		$sth2 = mysql_query("select g.name as group_name, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, a.name as name, a.account_no as account_no, d.id as id, d.open_date as open_date, d.close_date as close_date from mem_accounts d left join member m on d.mem_id=m.id  left join group_member gm on m.id=gm.mem_id left join loan_group g on gm.group_id=g.id left join savings_product s on d.saveproduct_id=s.id join accounts a on s.account_id=a.id where (m.first_name like '%".$name1."%' or m.last_name like '%".$name1."%') and m.mem_no like '%".$mem_no1."%'".$product. " and a.name not in ('Compulsory Shares', 'Compulsory Savings')  and ".$group_n." ".$branch." order by g.name, m.first_name, m.last_name");

		$sth3 = mysql_query("select g.name as group_name, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, a.name as name, a.account_no as account_no, d.id as id, d.open_date as open_date, d.close_date as close_date from mem_accounts d left join member m on d.mem_id=m.id  left join group_member gm on m.id=gm.mem_id left join loan_group g on gm.group_id=g.id left join savings_product s on d.saveproduct_id=s.id join accounts a on s.account_id=a.id where (m.first_name like '%".$name1."%' or m.last_name like '%".$name1."%') and m.mem_no like '%".$mem_no1."%'".$product. " and a.name not in ('Compulsory Shares', 'Compulsory Savings')  and ".$group_n." ".$branch." order by g.name, m.first_name, m.last_name limit ".$stat.",".$num_rows);
		$max_page = ceil(mysql_num_rows($sth2)/$num_rows);
		if(@ mysql_num_rows($sth2)==0){
			$cont = "<div><font color=red>No product accounts for the members selected</font></div>";
			$resp->assign("status", "innerHTML", $cont);
		return $resp;
		}
		else{
			
			$content .= '<div class="col-md-12"><div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <p><h5 class="semibold text-primary mt0 mb5">SAVINGS PRODUCT ACCOUNTS FOR MEMBERS</h5></p>
                              
                               </div>';
 		$content .= "<table class='borderless table-hover' width='100%' id='able-tools'>";

	if($mem_no=='' && $name=='' && $product_type=='')
		$content .= "<tr bgcolor=lightgrey><td><font color=red>Please enter your search criteria</font></td></tr>";
	else{
		$group_n = ($group_name == 'All') ? "(g.name is null or m.id in (select mem_id from group_member))" : "g.name like '%".$group_name."%'";
		$mem_no1 = ($mem_no == 'All') ? "" : $mem_no;
		$name1 = ($name == 'All') ? "" : $name;
		if($product_id == '')
			$product = " and s.id like '%%'";
		else
			$product =" and s.id='".$product_id."'";
	  	
		$sth2 = mysql_query("select g.name as group_name, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, a.name as name, a.account_no as account_no, d.id as id, d.open_date as open_date, d.close_date as close_date from mem_accounts d left join member m on d.mem_id=m.id  left join group_member gm on m.id=gm.mem_id left join loan_group g on gm.group_id=g.id left join savings_product s on d.saveproduct_id=s.id join accounts a on s.account_id=a.id where (m.first_name like '%".$name1."%' or m.last_name like '%".$name1."%') and m.mem_no like '%".$mem_no1."%'".$product. " and a.name not in ('Compulsory Shares', 'Compulsory Savings')  and ".$group_n." ".$branch." order by g.name, m.first_name, m.last_name");

		$sth3 = mysql_query("select g.name as group_name, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, a.name as name, a.account_no as account_no, d.id as id, d.open_date as open_date, d.close_date as close_date from mem_accounts d left join member m on d.mem_id=m.id  left join group_member gm on m.id=gm.mem_id left join loan_group g on gm.group_id=g.id left join savings_product s on d.saveproduct_id=s.id join accounts a on s.account_id=a.id where (m.first_name like '%".$name1."%' or m.last_name like '%".$name1."%') and m.mem_no like '%".$mem_no1."%'".$product. " and a.name not in ('Compulsory Shares', 'Compulsory Savings')  and ".$group_n." ".$branch." order by g.name, m.first_name, m.last_name limit ".$stat.",".$num_rows);
		$max_page = ceil(mysql_num_rows($sth2)/$num_rows);
		if(@ mysql_num_rows($sth2)==0)
			$content .= "<tr bgcolor=lightgrey><td align=center><font color=red>No product accounts for the members selected</font></td></tr>";
		else{
			
			$content .= "<tr class=''><th><b>No</b></th><th><b>Member Name</b></th><th><b>Member No</b></th><th><b>Product</b></th><th><b>Date Opened</b></th><th><b>Balance</b></th><th><b>Status</b></th><th><b>Edit</b></th></tr>";
			$i=$stat+1;
			$group = 'NONE';
			
			$num = mysql_numrows($sth3);
			$sub_total =0;
			$overall_total = 0;
			while($row = mysql_fetch_array($sth3)){
				if($row['group_name'] <> $group){
					
					if($row['group_name'] == NULL)
						$group_show = 'NONE';
					else
						$group_show = $row['group_name'];
					$group = $row['group_name'];
					if($i >1){
						$content .= "<tr class='headings' ><td colspan=5><b>GROUP: ".strtoupper($former)." SUB TOTAL</b></td><td colspan=3>".number_format($sub_total, 2)."</td></tr>";
						$overall_total += $sub_total;
						$sub_total =0;
					}

					$content .="<tr class='' style='background-color:grey'><td colspan=8><b>GROUP: ".strtoupper($group_show)."</b></td></tr>";
					$former = $group_show;
				}
				$status = (date('Y-m-d', strtotime($row['close_date'])) <= date('Y-m-d')) ? "<a href='javascript:;' onclick=\"xajax_manage_account('activate', ".$row['id'].", '".$group_name."', '".$mem_no."', '".$name."', '".$product_id."','".$branch_id."','".$num_rows."','".$stat."','".$cur_page."');\">Closed</a>" : "<a href='javascript:;' onclick=\"xajax_manage_account('close', ".$row['id'].", '".$group_name."', '".$mem_no."', '".$name."', '".$product_id."','".$branch_id."','".$num_rows."','".$stat."','".$cur_page."');\">Active</a>";
				//ACCOUNT BALANCE
				$dep_res = mysql_query("select sum(amount - flat_value - percent_value) as amount from deposit where memaccount_id='".$row['id']."'");
				$dep = mysql_fetch_array($dep_res);
				$dep_amt = ($dep['amount'] != NULL) ? $dep['amount'] : 0;
				//WITHDRAWALS
				$with_res = mysql_query("select sum(amount + flat_value + percent_value) as amount from withdrawal where memaccount_id='".$row['id']."'");
				$with = mysql_fetch_array($with_res);
				$with_amt = ($with['amount'] != NULL) ? $with['amount'] : 0;
				//MONTHLY CHARGES 
				$charge_res = mysql_query("select sum(amount) as amount from monthly_charge where memaccount_id='".$row['id']."'");
				$charge = mysql_fetch_array($charge_res);
				$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
				//INTEREST AWARDED
				$int_res = mysql_query("select sum(amount) as amount from save_interest where memaccount_id='".$row['id']."'");
				$int = mysql_fetch_array($int_res);
				$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;
				
				//LOAN REPAYMENTS
				$pay_res = mysql_query("select sum(princ_amt + int_amt + penalty + other_charges) as amount from payment where mode='".$row['id']."'");
				$pay = mysql_fetch_array($pay_res);
				$pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;
				//INCOME DEDUCTIONS
				$inc_res = mysql_query("select sum(amount) as amount from other_income where mode='".$row['id']."'");
				$inc = mysql_fetch_array($inc_res);
				$inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;
				//TRANSFER TO SHARES
				$shares_res = mysql_query("select sum(value) as amount from shares where mode='".$row['id']."'");
				$shares = mysql_fetch_array($shares_res);
				$shares_amt = ($shares['amount'] != NULL) ? $shares['amount'] : 0;

				$balance = $dep_amt + $int_amt  - $with_amt - $charge_amt - $pay_amt - $inc_amt - $shares_amt;
				$sub_total += $balance;
				$color = ($i%2 == 0) ? "white" : "white";
				$content .= "<tr bgcolor=$color><td>".$i."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".$row['account_no']." - ".$row['name']."</td><td>".$row['open_date']."</td><td>".number_format($balance, 2)."</td><td>".$status."</td><td><a href='javascript:;'  onclick=\"xajax_edit_account(".$row['id'].", '".$group_name."', '".$mem_no."', '".$name."', '".$product_type."','".$branch_id."','".$num_rows."','".$stat."','".$cur_page."'); return false;\">Edit</a></td></tr>";
				if($i == $num){
					$overall_total += $sub_total;
					$content .= "<tr class='headings'><th colspan=5><b>".strtoupper($former)." SUB TOTAL</b></th><th colspan=3><B>".number_format($sub_total, 2)."</B></th></tr>
					<tr class='headings'><th colspan=5><b> OVERALL TOTAL </b></th><th colspan=3><B>".number_format($overall_total, 2)."</B></th></tr>";
				}

				$i++;
			}
		}
	}
	$content .= "</table> <div align=center>";
	#Other pages
	if ($cur_page>1)
			$content .="<a href='javascript:;' onClick=\"xajax_list_accounts('".$group_name."', '".$mem_no."', '".$name."', '".$product_id."','".$branch_id."','".$num_rows."','0','1'); return false;\"><font color=#666666>First </font></a>";
if($cur_page >1 && $max_page>2){
		$n = (($cur_page-1)*$num_rows==$stat)?$cur_page-2:$cur_page-1;
			
	$content .= " |<a href='javascript:;' onclick=\"xajax_list_accounts('".$group_name."', '".$mem_no."', '".$name."', '".$product_id."', '".$branch_id."', '".$num_rows."', '".($n*$num_rows)."', '".($cur_page-1)."'); return false;\"><font color='#666666'><< Previous</a> </font>";
}	
if($max_page>2){
	$j=1;
	$st = 0;
	while($j<=$max_page){
		$st = ($j==1)?0:($j-1)*$num_rows;
		if($j==$cur_page)
			$content .=" <font color=#FF0000>".$j."</font>";
		else
		$content .= "  <a href='javascript:;' onclick=\"xajax_list_accounts('".$group_name."', '".$mem_no."', '".$name."', '".$product_id."', '".$branch_id."', '".$num_rows."', '".$st."', '".$j."'); return false;\"><font color=#666666>".$j."</font></a>";
		$j++;
	}
}
	if($cur_page<$max_page&&$max_page>2){
	$content .=" <a href='javascript:;' onClick=\"xajax_list_accounts('".$group_name."', '".$mem_no."', '".$name."', '".$product_id."','".$branch_id."', '".$num_rows."', '".($num_rows*$cur_page)."', '".($cur_page+1)."'); return false;\"><font color=#666666>Next >></font></a>  ";
	}
	if($cur_page<$max_page){
	$content .= " |<a href='javascript:;' onclick=\"xajax_list_accounts('".$group_name."', '".$mem_no."', '".$name."', '".$product_id."','".$branch_id."', '".$num_rows."', '".(($max_page-1)*$num_rows)."', '".$max_page."'); return false;\"><font color='#666666'>Last</font></a> </div>";
	}
	/***************
	*FINISH PAGING
	****************/
	}
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


//ACTIVATE AND CLOSE ACCOUNT
function manage_account($action, $memaccount_id, $group_name, $mem_no, $name, $product_id,$branch_id,$num_rows,$stat,$cur_page){
	$resp = new xajaxResponse();
	/*if($_SESSION['position'] <>'Manager'){
		$resp->alert("Access Denied! \nOnly the Manager can manage account");
		return $resp;
	}*/
	if($action == 'activate')
		$resp->confirmCommands(1, "Do you really want to re-activate this account?");
	else
		$resp->confirmCommands(1, "Do you really want to close this account?");
	$resp->call('xajax_manage2_account', $action, $memaccount_id, $group_name, $mem_no, $name, $product_id,$branch_id,$num_rows,$stat,$cur_page);
	return $resp;
}

//CONFIRM ACTIVATION OR CLOSURE OF ACCOUNT
function manage2_account($action, $memaccount_id, $group_name, $mem_no, $name, $product_id,$branch_id,$num_rows,$stat,$cur_page){
	$resp = new xajaxResponse();
	$accno =mysql_fetch_assoc(mysql_query("select m.first_name,m.last_name,m.mem_no from member m join mem_accounts ma on m.id=ma.mem_id where ma.id=".$memaccount_id));
	if($action == 'activate'){
		if(! mysql_query("update mem_accounts set close_date='2032-12-31' where id='".$memaccount_id."'"))
			$resp->alert("Account not activated! \n Could not update account details");
		else{
			////////////////////
			$action = "update mem_accounts set close_date='2032-12-31' where id='".$memaccount_id."'";
			$msg = "Re-activated member account:".$accno['mem_no']." - ".$accno['first_name']." ".$accno['last_name'];
			log_action($_SESSION['user_id'],$action,$msg);
			//////////////////
			$resp->assign("status", "innerHTML", "<font color=red>Account re-activated!</font>");
		}
	}elseif($action == 'close'){
		if(! mysql_query("update mem_accounts set close_date=NOW() where id='".$memaccount_id."'"))
			$resp->alert("Account not closed! \n Could not update account details");
		else
			{
			////////////////////
			$action = "update mem_accounts set close_date=NOW() where id='".$memaccount_id."'";
			$msg = "Closed member account:".$accno['mem_no']." - ".$accno['first_name']." ".$accno['last_name'];
			log_action($_SESSION['user_id'],$action,$msg);
			//////////////////
			$resp->assign("status", "innerHTML", "<font color=red>Account closed!</font>");
		}
	}
	
	$resp->call('xajax_list_accounts', $group_name, $mem_no, $name, $product_id,$branch_id,$num_rows,$stat,$cur_page);
	return $resp;
}

//REGISTER DEPOSIT
function add_deposit(){

	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
		
		$content= '
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">REGISTER SAVINGS</h3>
                            </div>               
                            <div class="panel-body">';
                                                        
 		$content .= '<div class="form-group">
                                   
                                        <div class="col-sm-3">
                                        <label class="control-label">Enter Member No:</label>
                                        <div class="input-group">
                                            
                                            <input type=int name="mem_no1" id="mem_no1" class="form-control">
                                              <span class="input-group-btn">
                                                <button class="btn btn-info  form-control" type="button" onclick=\'xajax_insertDeposit(getElementById("mem_no1").value); return false;\'>Next</button>
                                            </span>
                                        </div></div>
                                        <div class="col-sm-3">
                                            <label class="control-label">OR Select:</label>
                                            <div class="input-group">
                                           <select name="mem_no2" id="mem_no2" class="form-control"><option value="0">Select Member';
		$sth = mysql_query("select * from member order by first_name, last_name");
		while($row = mysql_fetch_array($sth)){
			$content .= "<option value='".$row['mem_no']."'>".$row['first_name']." ".$row['last_name'] ." - ".$row['mem_no'];
		}      
                                        $content.='</select>
                                            <span class="input-group-btn">
                                                 <button class="btn btn-info  form-control" type="button" onclick=\'xajax_insertDeposit(getElementById("mem_no2").value); return false;\'>Next</button>
                                            </span>
                                        </div>
                                        </div>
                                    </div>
                                </div></div></form></div></div>';
  $resp->assign("display_div", "innerHTML", $content);
	return $resp;
}                                
		
function insertDeposit($mem_no){
$resp = new xajaxResponse();
		 if($mem_no=='' || $mem_no=='0'){
			    $content .='<font color=red>Select or Enter a member who wants to make a deposit</font>';
					$resp->assign("status", "innerHTML", 'member number is empty');
			 }
	         else{
			$sth =@mysql_query("select * from member where mem_no= '".$mem_no."'");
			if(@mysql_numrows($sth)==0){
			$resp->assign("status", "innerHTML", "<div class='alert alert-success'>member Not Found</div>");
			return $resp;
			}
			else{		
			$row = mysql_fetch_array($sth); 
				$sel = mysql_fetch_array(mysql_query("select id,mem_id from mem_accounts where mem_id = '".$row['id']."'"));
			$savingid =$sel['id'];
			
			$mem_savings=@mysql_query("select id,date,amount,depositor,receipt_no from deposit where memaccount_id=$savingid order by trans_date desc limit 5");
				
			$content.='
 <div class="col-sm-6">
               <form class="panel panel-default form-bordered">
        <div class="panel-body">    
    <ul class="nav nav-tabs">
  <li><a data-toggle="tab" href="#home">Bio Information</a></li>
  <li  class="active"><a data-toggle="tab" href="#menu1">Last 5 Deposits</a></li>
  <li><a data-toggle="tab" href="#menu4">Available Savings</a></li>
</ul>

<div class="tab-content">
  <div id="home" class="tab-pane fade">
  <div class="col-sm-3">';
if($row['photo_name'] != ""){
  $content .='<img src="photos/'.$row['photo_name'].'" width=80 height=60>
  ';
}else{
   $content .='<img src="photos/default.png" width=80 height=60>';
}
  $content .='</div>
   <div class="col-sm-9">
   <b>Name: </b><span class="name sort_1">'.ucwords($row['first_name']).' '.ucwords($row['last_name']).'</span><br>
    <span class="name sort_1"><b>Telephone:</b>'.$row['telno'].'</span><br>
    <span class="name sort_1"><b>A/C No:</b>'.$row['mem_no'].'</span><br>
    <span class="name sort_1"><b>Address:</b>'.$row['address'].'</span>
  </div>
   </div>
  <div id="menu1" class="tab-pane fade in active">
   <table class="borderless table-hover " width="100%" id="table-tools">
    <thead>
      <tr>
        <th>Date</th>      
        <th>Receipt Number</th>
        <th>Amount</th>
          <th>Depositor</th>
      </tr>
    </thead>
    <tbody>';
    $totalsavings = 0;
while($save = mysql_fetch_array($mem_savings)){
     $content.= '<tr>
        <td>'.$save['date'].'</td>   
        <td>'.$save['receipt_no'].'</td> 
        <td>'.number_format($save['amount']).'</td>
         <td>'.$save['depositor'].'</td>
      </tr>'; 
     // $totalsavings += $save['amount'];
  }
    
    $content.=' <tfooter>
        
    </tfooter></tbody>
  </table>
  </div>
 <div id="menu4" class="tab-pane fade">
    <table class="borderless table-hover "  width="100%">
    <thead>
      <tr>    
        <th>Balance</th>
        <th>'.number_format(libinc::get_savings_bal($savingid), 0).'</th>
        
      </tr>
    </thead>
    
  </table>
  </div>


</div>
                       
                    </a>
             </form>
                              
                
            </div></div>

                    <div class="col-md-6">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">

                    <div class="panel-body"><input type=hidden name="mem_id" value="'.$row['id'].'" id="mem_id">';
                       
                        
                        $content.='<div class="form-group">
                                   
                                        <div class="col-sm-6">
                                        
                                   <label class="control-label">Savings Product:</label>
                                            <select name="memaccount_id" id="memaccount_id" class="form-control" ><option value="">';
				$prod_res = mysql_query("select a.name as name, s.account_id as account_id, a.account_no as account_no, d.id as id from mem_accounts d join savings_product s on d.saveproduct_id=s.id join member m on d.mem_id=m.id join accounts a on s.account_id=a.id where s.type='free' and m.mem_no='".$mem_no."' and d.close_date >NOW() and a.name not in ('Compulsory Shares', 'Compulsory Savings')");
				if(mysql_numrows($prod_res) == 0){
					$resp->alert("The member does not have an active Savings Account\n Please ensure it has not been closed, or open them a new one");
					return $resp;
				}
				while($prod = mysql_fetch_array($prod_res))
					$content .= "<option value='".$prod['id']."'>".$prod['account_no'] ." - ".$prod['name'];
				$content .= '</select>
                                </div></div>';
                                
			$content .= '<div class="form-group"><div class="col-sm-6">                                    
                                       
                                            <label class="control-label">Date:</label>
                               <input type=varchar(10) class="form-control" id="date" name="date" /> 
                                                                      
                                </div></div>';
			
			 $content .= '<div class="form-group"><div class="col-sm-6">                                  
                                      
                                        <label class="control-label">Amount:</label>
                                           <input onkeyup="format_as_number(this.id)" type=int name="amount" id="amount" class="form-control" >
                                  
                        </div></div>';
                                 
                         $content .= '<div class="form-group"><div class="col-sm-6">
                                  <label class="control-label">Receipt No.:</label>
                                           <input type=varchar(10) name="receipt_no" id="receipt_no" class="form-control" >
                                                                    </div></div>';
                           $content .= '<div class="form-group"><div class="col-sm-6">                                   
                                      
                                        <label class="control-label">Cheque No. (Optional):</label>
                                           <input type=varchar(10) name="cheque_no" id="cheque_no" class="form-control" >
                                    
                              </div></div>';
                                 
                           $content .= '<div class="form-group"><div class="col-sm-6">                                  
                                       
                                        <label class="control-label">Depositor\'s Name:</label>
                                           <input type=varchar(256) name="depositor" id="depositor" class="form-control" >
                                  
                                 </div></div>';
                                 
                            $content .= '<div class="form-group"><div class="col-sm-6">
                                        <label class="control-label">Destination Account:</label>
                                           <select name="bank_account" id="bank_account" class="form-control" disabled><option value="">';
				//if (strtolower($_SESSION['position']) == 'admin')
					$account_res = mysql_query("select a.name as name, a.account_no as account_no, b.bank as bank, b.id as id from bank_account b join accounts a on b.account_id=a.id where b.id='".CAP_Session::get('account_assigned')."' && a.branch_no like '".$_SESSION['branch_no']."'");
				/*else
					$account_res = mysql_query("select a.name as name, a.account_no as account_no, b.bank as bank, b.id as id from bank_account b join accounts a on b.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."' and b.id in (select bank_account_id from user_account where user_id =".$_SESSION['user_id'].")");*/
				while($account = mysql_fetch_array($account_res)){
					$content .= "<option selected value='".$account['id']."'>".$account['account_no'] ." - ".$account['bank']." ".$account['name'];
				}
				//$resp->alert($row['branch_id']);
				$content .= '</select>
                                    </div></div></div>';
                                                                               
			 $content .= '<div class="panel-footer">                            
                                
                                <input type="button" class="btn  btn-default" value="Back"  onclick=\'xajax_add_deposit(""); return false;\'>&nbsp;<input type="reset" class="btn  btn-default" value="Reset" onclick=\'xajax_add_deposit("'.$mem_no.'")\'>&nbsp;<input type="button" class="btn  btn-primary" value="Save"  onclick=\'xajax_insert_deposit(getElementById("memaccount_id").value, getElementById("amount").value, getElementById("receipt_no").value, getElementById("cheque_no").value, getElementById("depositor").value, getElementById("bank_account").value,  getElementById("date").value, "'.$row['branch_id'].'"); return false;\'>
                            </div></div>
                        </form>
                        <!--/ Form default layout -->
                    </div></div>';
                    $resp->call("createDate","date");			
		}
				
		
	}
	
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//EDIT DEPOSIT
function edit_deposit($deposit_id, $mem_no, $name, $product, $from_date,$to_date,$branch_id,$num_rows,$stat,$cur_page){
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$resp->assign("status", "innerHTML", "");
	
	$former_res = mysql_query("select mem.id as memaccount_id, d.cheque_no, d.receipt_no as receipt_no, d.depositor as depositor, d.bank_account as bank_account,d.trans_date as date,  m.id as mem_id, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, d.amount as amount, d.transfer as transfer, date_format(d.date, '%Y') as year, date_format(d.date, '%m') as month, date_format(d.date, '%d') as mday, a.account_no as account_no, a.name as name from deposit d join mem_accounts mem on d.memaccount_id=mem.id join  member m on mem.mem_id=m.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where d.id='".$deposit_id."'");
		
	$former = mysql_fetch_array($former_res);
	if ($former['transfer'] > 0)
	{
		$resp->alert("Sorry, you cannot edit a transfer.");
		return $resp;
	}
	
	
	
	$content ='
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="alert alert-info">
                            <p>EDIT DEPOSIT</p>
                              </p><table>
			<tr><td>Member No:</td><td><span class="lead">'.$former['mem_no'].'</span></td><td>Member Name:&nbsp;</td><input type=hidden name="memaccount_id" value="'.$former['memaccount_id'].'" id="memaccount_id"><td><span class="lead">'.$former['first_name']." ".$former['last_name'].'</span></td></tr></table></p>
                            </div>               
                            <div class="panel-body">';
                        
                       
                        $content .= ' <div class="col-sm-5">
                        <div class="form-group">
                                    <label class="control-label">Savings Product:</label>
                                            <select name="saveproduct_id" id="saveproduct_id" class="form-control"><option value="'.$former['memaccount_id'].'">'.$former['account_no'] .' - '.$former['name'];
		
	$prod_res = mysql_query("select a.name as name, s.account_id as account_id, a.account_no as account_no, d.id as id from mem_accounts d join savings_product s on d.saveproduct_id=s.id join member m on d.mem_id=m.id join accounts a on s.account_id=a.id where s.type='free' and m.mem_no='".$former['mem_no']."'");
				
	while($prod = mysql_fetch_array($prod_res))
    $d = $former['date'];
  $dat = new DateTime($d);
  $date = $dat->format('Y-m-d');
		$content .= "<option value='".$prod['id']."'>".$prod['account_no'] ." - ".$prod['name'];
	$content .= '</select>                                       
                                   
                                </div>';
                                
			$content .='<div class="form-group">
                                   <label class="control-label">Date:</label>
 <input type="text" class="form-control" id="date" name="date" value="'.$date.'" />
                                        
                                   
                                </div>';
			
			 $content.='<div class="form-group">
                                   
                                        <label class="control-label">Amount:</label>
                                           <input onkeyup="format_as_number(this.id)" type=int name="amount" id="amount" value="'.$former['amount'].'" class="form-control" >
                                    </div>';
                                 
                          $content.='<div class="form-group">
                                   
                                        <label class="control-label">Receipt No.:</label>
                                           <input type=varchar(10) name="receipt_no" id="receipt_no" value="'.$former['receipt_no'].'" class="form-control" >
                                   </div></div>';
                          $content.='<div class="col-sm-5"><div class="form-group">
                                   
                                        <label class="control-label">Cheque No. (Optional):</label>
                                           <input type=varchar(10) name="cheque_no" id="cheque_no" value="'.$former['cheque_no'].'" class="form-control" >
                                   </div>';
                                 
                            $content.='<div class="form-group">
                                    
                                        <label class="control-label">Depositor\'s Name:</label>
            <input type=text name="depositor" id="depositor" value="'.$former['depositor'].'" class="form-control" >
                                   </div>';
                                 
$sth = mysql_query("select a.name as name, a.account_no as account_no, b.bank as bank, b.id as id from bank_account b join accounts a on b.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."' and b.id='".$former['bank_account']."'");
	$row = mysql_fetch_array($sth);                             
                                 
                                                               
                            $content.='<div class="form-group">
                                    
                                        <label class="control-label">Destination Bank Account:</label>
                                           <select name="bank_account" id="bank_account" class="form-control"><option value="'.$former['bank_account'].'">'.$row['account_no'].' - '.$row['name'];
	//if (strtolower($_SESSION['position']) == 'manager')
		$account_res = mysql_query("select a.name as name, a.account_no as account_no, b.bank as bank, b.id as id from bank_account b join accounts a on b.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."' and b.id <>'".$former['bank_account']."'");
	/*else
		$account_res = mysql_query("select a.name as name, a.account_no as account_no, b.bank as bank, b.id as id from bank_account b join accounts a on b.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."' and b.id <>'".$former['bank_account']."' and b.id in (select bank_account_id from user_account where user_id =".$_SESSION['user_id'].")");*/
	//$account_res = mysql_query("select a.name as name, a.account_no as account_no, b.bank as bank, b.id as id from bank_account b join accounts a on b.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."' and b.id <>'".$former['bank_account']."'");
	while($account = mysql_fetch_array($account_res)){
		$content .= "<option value='".$account['id']."'>".$account['account_no'] ." - ".$account['name'];
	}
	$content .= '</select>
                                   </div></div></div>';                    
                                
			 $content .= "<div class='panel-footer'>                              
                                
                                <input type='button' class='btn  btn-inverse mb5' value='Back'  onclick=\"xajax_list_deposits('".$mem_no."', '".$name."', '".$product."', '".$from_date."','".$to_date."', '".$branch_id."','".$num_rows."','".$stat."','".$cur_page."')\">&nbsp;</a>&nbsp;<input type='button' class='btn' value='Delete' onclick=\"xajax_delete_deposit('".$deposit_id."', '".$former['mem_id']."'); return false;\">&nbsp;<input type='button' class='btn  btn-primary' value='Update'  onclick=\"xajax_update_deposit('".$deposit_id."', getElementById('memaccount_id').value, getElementById('amount').value, getElementById('receipt_no').value, getElementById('cheque_no').value,  getElementById('depositor').value, getElementById('bank_account').value,  getElementById('date').value); return false;\">
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","date");
	
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function unique_rcpt($rcptno, $table='')
{
/*
	Check if a given rcpt_no has bn registered before.
	Tables currently containing receipt_no: payment, collected, deposit, shares, other_income, recovered
	returns false if rcpt_no has already bn registered or is an empty string
*/
	if ($rcptno == '')
		return false;
	elseif ($table == '')
	{
		$res = @mysql_query("SELECT receipt_no FROM payment where receipt_no='".$rcptno."' UNION SELECT receipt_no FROM deposit where receipt_no='".$rcptno."' UNION SELECT receipt_no from collected where receipt_no='".$rcptno."' UNION SELECT receipt_no from shares where receipt_no='".$rcptno."' UNION SELECT receipt_no from other_income where receipt_no='".$rcptno."' UNION SELECT receipt_no from recovered where receipt_no='".$rcptno."' union select receipt_no from sold_asset where receipt_no='".$rcptno."' union select receipt_no from sold_invest where receipt_no='".$rcptno."'");
	if (@mysql_num_rows($res) > 0)
		return false;
	else
		return true;
	}
	elseif ($table == 'other_income') 
	{
		$res = @mysql_query("SELECT receipt_no FROM payment where receipt_no='".$rcptno."' UNION SELECT receipt_no FROM deposit where receipt_no='".$rcptno."' UNION SELECT receipt_no from collected where receipt_no='".$rcptno."' UNION SELECT receipt_no from shares where receipt_no='".$rcptno."' UNION SELECT receipt_no from other_income where receipt_no !='".$rcptno."' UNION SELECT receipt_no from recovered where receipt_no='".$rcptno."'");
		if (@mysql_num_rows($res) > 0)
			return false;
		else
			return true;
	}	
			
}

//
function search_receipt($rcptno, $table='')
{
/*
	Check an entry of a given receipt or simillar receipts.
	Tables currently containing receipt_no: payment, collected, deposit, shares, other_income, recovered
	returns false if rcpt_no has already bn registered or is an empty string
*/
	$resp = new xajaxResponse();
	                          
         
        $content = '
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">FIND A RECEIPT NO.</h3>
                                       
                            <div class="panel-body">'; 
	     
	      $content .= '<div class="form-group">
                                   
                                        <div class="col-sm-4">
                                        <label class="control-label">Enter Receipt No:</label>
                                        <div class="input-group">
                                            
                                            <input type=int name="rcpt_no" id="rcpt_no" class="form-control">
                                              <span class="input-group-btn">
                                                <button class="btn btn-info" type="button" onclick=\'xajax_search_receipt(getElementById("rcpt_no").value); return false;\'>Next</button>
                                            </span>
                                        </div></div></div>';
                          $content .='</div></form></div>';   
                          
                         $resp->assign("display_div", "innerHTML", $content);                     
 		                                 
	if ($rcptno == "")
	{              
	      
	     $cont ='<div><font color=red>Enter a Receipt Number</font></div>';		 
	     $resp->assign("status", "innerHTML", $cont);
	     return $resp;      
	     
	}
	else
	{
		$res = @mysql_query("SELECT id, date, transaction, receipt_no,(princ_amt + int_amt + penalty + other_charges) as amount FROM payment where receipt_no='".$rcptno."' UNION SELECT id, date, transaction, receipt_no, amount as amount FROM deposit where receipt_no='".$rcptno."' UNION SELECT id, date, transaction, receipt_no, amount as amount from collected where receipt_no='".$rcptno."' UNION SELECT id, date, transaction, receipt_no, value as amount from shares where receipt_no='".$rcptno."' UNION SELECT id, date, transaction, receipt_no, amount as amount from other_income where receipt_no='".$rcptno."' UNION SELECT id, date, transaction, receipt_no, amount as amount from recovered where receipt_no='".$rcptno."' UNION SELECT id, date, transaction, receipt_no, amount as amount from sold_asset where receipt_no='".$rcptno."' UNION SELECT id, date, transaction, receipt_no, amount as amount from sold_invest where receipt_no='".$rcptno."'");
		if (@mysql_num_rows($res) > 0)
		{       
		
		        $content .= '<div class="panel panel-default" id="table-demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">RECEIPT NUMBER</h3>
                               
                               </div>';
 		$content .= '<table class="borderless table-hover" width="100%" id="table-tools">';
 		
			$content .= "<thead><th><b>Member Name</b></th><th><b>MemberNo</b></th><th><b>Receipt No.</b></th><th><b>Transaction</b></th><th><b>Date</b></th><th><b>Amount</b></th><thead><tbody>";
			while ($row = @mysql_fetch_array($res))
			{
				$i = 0;
				switch ($row['transaction'])
				{
					case "deposit" :
						$row1 = @mysql_fetch_array(@mysql_query("select mem_no, first_name, last_name from member where id = (select mem_id from mem_accounts where id = (select memaccount_id from deposit where id = $row[id]))"));
						//$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td>".$row1['first_name']. " ".$row1['last_name']."</td><td>".$row1['mem_no']."</td><td>".$row['receipt_no']."</td><td>".$row['transaction']."</td><td>".$row['date']."</td><td>".number_format($row['amount'])."</td></tr>";
						
						break;
					
					case "payment" :
						$row1 = @mysql_fetch_array(@mysql_query("select mem_no, first_name, last_name from member where id = (select mem_id from loan_applic where id = (select applic_id from disbursed  where id = (select loan_id from payment where id = $row[id])))"));
						//$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr><td>".$row1['first_name']. " ".$row1['last_name']."</td><td>".$row1['mem_no']."</td><td>".$row['receipt_no']."</td><td>Loan Payment</td><td>".$row['date']."</td><td>".number_format($row['amount'])."</td></tr>";

						break;

					case "collected" :
						//$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr><td>Not Applicable</td><td>Not Applicable</td><td>".$row['receipt_no']."</td><td>Receivable Collected</td><td>".$row['date']."</td><td>".number_format($row['amount'])."</td></tr>";

						break;

					case "shares" :
						$row1 = @mysql_fetch_array(@mysql_query("select mem_no, first_name, last_name from member where id = (select mem_id from shares where id = $row[id])"));
						//$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr><td>".$row1['first_name']. " ".$row1['last_name']."</td><td>".$row1['mem_no']."</td><td>".$row['receipt_no']."</td><td>".$row['transaction']."</td><td>".$row['date']."</td><td>".number_format($row['amount'])."</td></tr>";
						
						break;

					case "other_income" :
						//$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr><td>Not Applicable</td><td>Not Applicable</td><td>".$row['receipt_no']."</td><td>Other Income</td><td>".$row['date']."</td><td>".number_format($row['amount'])."</td></tr>";

						break;

					case "recovered" :
						$row1 = @mysql_fetch_array(@mysql_query("select mem_no, first_name, last_name from member where id = (select mem_id from loan_applic where id = (select applic_id from disbursed  where id (select loan_id from written_off where id = (select written_off_id from recovered where id = $row[id]))))"));
						//$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr><td>".$row1['first_name']. " ".$row1['last_name']."</td><td>".$row1['mem_no']."</td><td>".$row['receipt_no']."</td><td>Loan Payment</td><td>".$row['date']."</td><td>".number_format($row['amount'])."</td></tr>";

						break;
				}
				$i++;
			}
		}
		else{
			$cont = "<div><font color=red>No Such Receipt Found.</font></div>";
			$resp->assign("status", "innerHTML", $cont);
	               return $resp;
	               }
	    $content .= "</tbody></table></div>";           
	}
	
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//INSERT DEPOSIT INTO DB
function insert_deposit($memaccount_id, $amount, $receipt_no, $cheque_no, $depositor, $bank_account, $date, $branch_id){
        list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$amount=str_ireplace(",","",$amount);
	
	if(isFYClosed(parseFY($year,$month,$mday)))
		$resp->alert("Financial year has been Closed!\nCannot run a transaction in the selected period.");
	elseif($memaccount_id=='' || $amount =='' || $bank_account=='')
		$resp->alert("You may not leave any field blank".$bank_account);
	elseif($receipt_no == "" && $cheque_no == "")
		$resp->alert("You cannot leave both Cheque No. and receipt No. blank.");
	elseif(!$calc->isValidDate($mday, $month, $year))
		$resp->alert("Deposit not entered! please enter valid date");
	elseif($calc->isFutureDate($mday, $month, $year))
		$resp->alert("Deposit not entered! You have entered a future date");
	else{
		$sth = mysql_query("select m.open_date as opendate, s.deposit_flat as deposit_flat, s.deposit_perc as deposit_perc from mem_accounts m join savings_product s on m.saveproduct_id=s.id where m.id='".$memaccount_id."'");
		$row = mysql_fetch_array($sth);
		$percent_value = ($row['deposit_perc']/100) * $amount;
		$deposit_flat = $row['deposit_flat'];
		$charge = $row['deposit_flat'] - $percent_value;
		$actual = $amount - $charge;

//test for depositing before account opening
    if($date < $row['opendate']){
      $resp->alert("Deposit not registered \n Deposit can not be before account opening on ".$row['opendate']); 
      return $resp;
    }

		//$date = sprintf("%d-%02d-%02d", $date);
		$date = $date." ".date('H:i:s');
		if($actual <= 0){
			$resp->alert("Deposit not registered. The deposit must be greater than ".$charge." which is the charge");
		}else{
			if($receipt_no != "")
			{
				if(! unique_rcpt($receipt_no, '')){
					$resp->alert("Deposit not registered \n ReceiptNo already exists");
					return $resp;
				}
			}
					
		$resp->confirmCommands(1, "Do you really want to Deposit?");
			$resp->call("xajax_confirmDeposit",$memaccount_id, $amount, $receipt_no, $cheque_no, $depositor,$bank_account, $date, $branch_id,$deposit_flat,$percent_value);				
		}
	}
	return $resp;
}

function confirmDeposit($memaccount_id, $amount, $receipt_no, $cheque_no, $depositor,$bank_account, $date, $branch_id,$deposit_flat,$percent_value){
                                $resp = new xajaxResponse();
                         if(!unique_rcpt($receipt_no, '')){
					$resp->alert("Deposit not registered \n ReceiptNo already exists");
					return $resp;
				}
                                start_trans();
				mysql_query("insert into deposit set memaccount_id='".$memaccount_id."', amount='".$amount."', trans_date = (select now()), receipt_no='".$receipt_no."', cheque_no='".$cheque_no."', depositor='".$depositor."', flat_value='".$deposit_flat."', percent_value='".$percent_value."', date='".$date."', bank_account='".$bank_account."', branch_id='".$branch_id."'");
				$refHandle = mysql_query("select last_insert_id() as last_id");
		                $refNo= mysql_fetch_array($refHandle);
								
				///////////////
				$accno =mysql_fetch_assoc(mysql_query("select m.first_name as fname,m.last_name as lname,m.mem_no as memno,m.telno as telno from member m join mem_accounts ma on m.id=ma.mem_id where ma.id=".$memaccount_id));
				
			$action = "insert into deposit set memaccount_id='".$memaccount_id."', amount='".$amount."', trans_date = (select now()), receipt_no='".$receipt_no."',cheque_no='".$cheque_no."',flat_value='".$deposit_flat."', percent_value='".$percent_value."', date='".$date."', bank_account='".$bank_account."'";
			$msg = "Registered a deposit of:".$amount." for member: ".$accno['fname']." ".$accno['lname']." - ".$accno['memno']." on account: ".libinc::getItemById("accounts",libinc::getItemById("savings_product",libinc::getItemById("mem_accounts",$memaccount_id,"id","saveproduct_id"),"id","account_id"),"id","name");
			log_action($_SESSION['user_id'],$action,$msg);
			//////////////////
				commit();
				//$resp->call('xajax_add_deposit');
				//$content = "<font color=red><font color=red>Deposit registered!</font>";
			//if(libinc::isSmsSubscriber($accno['mem_no'])==true)
			//libinc::sendSMS($accno['telno'], "You have deposited ".$amount." to your ".$branch['branch_name']." Sacco account, your balance is ".checkBalance($accno['mem_no']));
						
			$first_name=$accno['fname'];
			$last_name=$accno['lname'];
			$mem_no=$accno['memno'];
			$balance=number_format(libinc::get_savings_bal($memaccount_id));
			$company=libinc::getItemById("company",$_SESSION['companyId'],"companyId","companyName");
			$message="{$amount} has been deposited to your account under {$company}, {$first_name} {$last_name} {$mem_no}. Ref.no. {$refNo[0]}. Account balance is {$balance}";
			 
		        $res =  libinc::sendMessage($accno['telno'],$message);
				if($res!=null){
			
                     // $adminMessage="Account Creation Message Sent to {$first_name} {$last_name}";
                      $messageSent=true;
			
				 foreach($res as $result) {
					// status is either "Success" or "error message"
					/*$response .=" Number: " .$result->number;
					$response .=" Status: " .$result->status;
					$response.=" MessageId: " .$result->messageId;
					$response .=" Cost: "   .$result->cost."<br/>";
					*/
				  }
                        }
                        else
                        {
                        $adminMessage="";
                       $messageSent=false;
                      } 
                      $content.='<div class="alert alert-success"><ul><li>';
			$content.="{$amount} has been deposited to account under {$company}, {$first_name} {$last_name} {$mem_no}. Ref.no. {$refNo[0]}. Account balance is {$balance}";
			$content.='</li></div>';
			$resp->assign("status", "innerHTML",$content);
return $resp;
}			
//UPDATE DEPOSIT IN DB
function update_deposit($deposit_id, $memaccount_id, $amount, $receipt_no, $cheque_no, $depositor, $bank_account, $date){
        list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$amount=str_ireplace(",","",$amount);
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$calc = new Date_Calc();
	$resp->assign("status", "innerHTML", "");
	if(isFYClosed(parseFY($year,$month,$mday)))
		$resp->alert("Financial year has been Closed!\nCannot run a transaction in the selected period.");
	elseif($memaccount_id=='' || $amount =='' || $bank_account=='')
		$resp->alert("You may not leave any field blank".$memaccount_id);
	elseif($receipt_no == "" && $cheque_no == "")
		$resp->alert("You cannot leave both Cheque No. and Receipt No. blank.");
	elseif(!$calc->isValidDate($mday, $month, $year))
		$resp->alert("Update not done, please enter valid date");
	elseif($calc->isFutureDate($mday, $month, $year))
		$resp->alert("Update not done! You have entered a future date");
	else{
		$sth = mysql_query("select s.deposit_flat as deposit_flat, s.deposit_perc as deposit_perc from mem_accounts m join savings_product s on m.saveproduct_id=s.id where m.id='".$memaccount_id."'");
		$row = mysql_fetch_array($sth);
		$percent_value = ($row['deposit_perc']/100) * $amount;
		$deposit_flat = $row['deposit_flat'];
		$charge = $row['deposit_flat'] - $percent_value;
		$actual = $amount - $charge;
		$date = sprintf("%d-%02d-%02d", $year, $month, $mday);
		$date = $date." ".date('H:i:s');
		if($actual <= 0){
			$resp->alert("Deposit not updated. The deposit must be greater than ".$charge." which is the charge");
		}else{
			//CHECK THAT THE BANK ACCOUNT WOULD NOT GO BELOW MINIMUM
			$former_res = mysql_query("select * from deposit where id='".$deposit_id."'");
			$former = mysql_fetch_array($former_res);
			$sth = mysql_query("select * from bank_account where id='".$bank_account."'");
			$row = mysql_fetch_array($sth);
			/* if(($row['account_balance'] + $amount - $former['amount']) < $row['min_balance']){
				$resp->alert("Update rejected! \n The bank account would go below the minimum balance");
				return $resp;
			}
			*/
			$rec_res = mysql_query("select receipt_no from deposit where receipt_no='".$receipt_no."' and id<>'".$deposit_id."' union select receipt_no from other_income where receipt_no ='".$receipt_no."' union select receipt_no from payment where receipt_no ='".$receipt_no."' union select receipt_no from shares where receipt_no ='".$receipt_no."' union select receipt_no from sold_asset where receipt_no='".$receipt_no."'");
			if(@ mysql_numrows($rec_res) > 0){
				$resp->alert("Deposit not registered \n ReceiptNo already exists");
			}else{
				$resp->confirmCommands(1, "Do you really want to update?");
				$resp->call('xajax_update2_deposit', $deposit_id, $memaccount_id, $amount, $receipt_no, $cheque_no, $flat_value, $percent_value, $date, $bank_account, $depositor);
			}
		}
	}
	return $resp;
}

//CONFIRM UPDATE OF DEPOSIT
function update2_deposit($deposit_id, $memaccount_id, $amount, $receipt_no, $cheque_no, $flat_value, $percent_value, $date, $bank_account, $depositor){
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$former_res = mysql_query("select * from deposit where id='".$deposit_id."'");
	$former = mysql_fetch_array($former_res);
	start_trans();
	if(!mysql_query("update deposit set memaccount_id='".$memaccount_id."', trans_date = (select now()),amount='".$amount."', receipt_no='".$receipt_no."', cheque_no='".$cheque_no."', flat_value='".$deposit_flat."', percent_value='".$percent_value."', date='".$date."', trans_date='".$date."',bank_account='".$bank_account."', depositor='".$former['depositor']."' where id='".$deposit_id."'")){
		$resp->alert("ERROR: Could not update the deposit! \n Contact FLT");
		rollback();
		return $resp;
	}
	if(! mysql_query("update bank_account set account_balance=account_balance -".$former['amount']." + ".$amount." where id=".$bank_account."")){
		$resp->alert("ERROR: Could not update the bank balance! \n Contact FLT");
		rollback();
		return $resp;
	}
	///////////////////////////
	$accno =mysql_fetch_assoc(mysql_query("select m.first_name,m.last_name,m.mem_no from member m join mem_accounts ma on m.id=ma.mem_id where ma.id=".$memaccount_id));
	$action = "update deposit set memaccount_id='".$memaccount_id."', amount='".$amount."', receipt_no='".$receipt_no."', flat_value='".$deposit_flat."', percent_value='".$percent_value."', date='".$date."', bank_account='".$bank_account."' where id='".$deposit_id."'";
	$msg = "Edited a deposit from:".number_format($former['amount'],2)." to:".number_format($amount,2)." for member: ".$accno['first_name']." ".$accno['last_name']." - ".$accno['mem_no'];
	log_action($_SESSION['user_id'],$action,$msg);
	//////////////////////
	commit();
	$resp->assign("status", "innerHTML", "<font color=red>Deposit registered!</center>");
	return $resp;
}

function delete_deposit($deposit_id, $mem_id){
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	$resp->assign("status", "innerHTML", "");
	
	$pledged_res = mysql_query("select sum(amount) as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id where mem.mem_id='".$mem_id."' and s.type='pledged'");
	$pledged = mysql_fetch_array($pledged_res);

	$dfree_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id where mem.mem_id='".$mem_id."' and s.type='free' and d.id<>'".$deposit_id."'");
	$dfree = @mysql_fetch_array($dfree_res);
	$dfree_amt = $dfree['amount'];

	$wfree_res = mysql_query("select sum(w.amount) as amount from withdrawal w join mem_accounts mem on w.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id where mem.mem_id='".$mem_id."' and s.type='free'");
	$wfree = mysql_fetch_array($wfree_res);
	$wfree_amt = $wfree['amount'];

	$pledged_amt = $pledged['amount'];
	$free_amt = $dfree_amt - $wfree_amt;
	if($free_amt < $pledged_amt)
		$resp->alert("Cannot delete this deposit! It is part of compusory savings. \n First delete some of this member's loans");
	else{
		$sth = mysql_query("select * from deposit where id='".$deposit_id."'");
		$row = mysql_fetch_array($sth);
		$resp->confirmCommands(1, "Do you really want delete?");
		$resp->call('xajax_delete2_deposit', $deposit_id, $row['amount'],  $row['bank_account']);
	}
	return $resp;
}

//CONFIRM DELETION OF DEPOSIT
function delete2_deposit($deposit_id, $amount, $bank_account_id){
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	$former_res = mysql_query("select * from deposit where id='".$deposit_id."'");
	$former = mysql_fetch_array($former_res);
	start_trans();
	$accno =mysql_fetch_assoc(mysql_query("select m.first_name,m.last_name,m.mem_no from member m join mem_accounts ma on m.id=ma.mem_id join deposit d on d.memaccount_id=ma.id where d.id=".$deposit_id));
	if(!mysql_query("delete from deposit where id='".$deposit_id."'")){
		$resp->alert("ERROR: Could not delete deposit! \n Contact Ensibuuko");
		rollback();
		return $resp;
	}
	if(! mysql_query("update bank_account set account_balance=account_balance - ".$former['amount']." where id='".$bank_account_id."'")){
		$resp->alert("ERROR: Could not update bank account! \n Contact Ensibuuko");
		rollback();
		return $resp;
	}
	
			///////////////////
			$action = "delete from deposit where id='".$deposit_id."'";
			$msg = "Deleted a deposit of:".$former['amount']." from member's account:".$accno['mem_no']." - ".$accno['first_name']." ".$accno['last_name'];
			log_action($_SESSION['user_id'],$action,$msg);
			//////////////////
	commit();
	$resp->assign("status", "innerHTML", "<font color=red>Deposit deleted!</center>");
	return $resp;
}

//LIST DEPOSITS
function list_deposits($mem_no, $name, $product,$group_id,$from_date,$to_date,$branch_id){
        list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
        $content ="";
	$resp = new xajaxResponse();
	//$resp->alert($group_id);
	//return $resp;
	$resp->assign("status", "innerHTML", "");
	$branch = ($branch_id=='all'||$branch_id=='')? NULL :" and mem.branch_id=".$branch_id;
	$group = ($group_id=='all'||$group_id=='')? NULL :" and m.group_id=".$group_id;
	$group_name = ($group_id=='all'||$group_id=='')? "All Groups" : libinc::getItemById("loan_group",$group_id,"id","name");
	$group_sum=0;
	
	$content .= '
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3  class="panel-title">SEARCH FOR DEPOSITS</h3>
                                
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    
                                        <div class="col-sm-3">
                                            <label class="control-label">Search By Member No:</label>
                                            <input type="int" id="dmem_no" name="dmem_no" value="All" class="form-control">                                            
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label">Member Name:</label>
                                            <input type="text" id="dname" value="All" class="form-control">
                                        </div>
                               
                                   
                                        <div class="col-sm-3">
                                            <label class="control-label">Savings Product:</label>
                                            <select name="saveproduct_id", id="saveproduct_id" class="form-control"><option value="">All';
	$sth = mysql_query("select a.name as name, a.account_no as account_no, s.id as id from savings_product s left join accounts a on s.account_id=a.id");
	while($row = @mysql_fetch_array($sth)){
		$content .= "<option value='".$row['id']."'>".$row['account_no']." - ". $row['name'];
	}
	$content .= '</select>                    
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label">Group:</label>
                                            <select name="group_id", id="group_id" class="form-control"><option value="">Select';
	$sth=mysql_query("select * from loan_group order by name");
		while($row = @mysql_fetch_array($sth)){
			$content .= "<option value='".$row['id']."'>".$row['name'];
		}
	$content .= '</select>                    
                                        </div>
                                    
                                </div>
        
        
        
                
                                        <div class="col-sm-3">
                                            <label class="control-label">Branch:</label>
                                            <span>'.branch_rep($branch_id).'</span>
	                                          
                                        </div><div class="form-group">
                                        <div class="col-sm-3">
                                            <label class="control-label">Date range:</label>
                                          
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>
                                    </div>
                                </div>';
                                                                             
	$content .= '<div class="panel-footer">                              
                                
                                <input type="button" class="btn  btn-primary" value="Search"  onclick=\'xajax_list_deposits(getElementById("dmem_no").value, getElementById("dname").value, getElementById("saveproduct_id").value,getElementById("group_id").value, getElementById("from_date").value,getElementById("to_date").value,getElementById("branch_id").value); return false;\'>
                            </div></div>
                        </form>
                        <!--/ Form default layout -->
                    </div></div>';
                    $resp->call("createDate","from_date");
		    $resp->call("createDate","to_date");
	//$resp->assign("display_div", "innerHTML", $content);
	
	if($mem_no=='' && $name=='' && $product==''){
		$cont = "<font color=red>Please enter your search criteria</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
		$dname = ($name == 'All') ? "" : $name;
		$dmem_no = ($mem_no == 'All') ? "" : $mem_no;
		$from_date = sprintf("%04d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
		$to_date = sprintf("%04d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);

		$sth = mysql_query("select d.receipt_no as receipt_no, d.cheque_no as cheque_no, d.bank_account as bank_account, d.id as id, d.percent_value as percent_value, d.flat_value as flat_value, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, d.amount as amount, d.date as date, a.account_no as account_no, a.name as name from deposit d join mem_accounts mem on d.memaccount_id=mem.id join  member m on mem.mem_id=m.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where mem.saveproduct_id like '%".$product."%' and (m.mem_no like '%".$dmem_no."%' and (m.first_name like '%".$dname."%' or m.last_name like '%".$dname."%')) and d.date >= '".$from_date."' and d.date <= '".$to_date."' and s.type='free' ".$group." ".$branch." order by m.first_name, m.last_name, d.trans_date");
		
	        $sth2 = mysql_query("select d.receipt_no as receipt_no, d.cheque_no as cheque_no, d.bank_account as bank_account, d.id as id, d.percent_value as percent_value, d.flat_value as flat_value, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, d.amount as amount, d.date as date, a.account_no as account_no, a.name as name from deposit d join mem_accounts mem on d.memaccount_id=mem.id join  member m on mem.mem_id=m.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where mem.saveproduct_id like '%".$product."%' and (m.mem_no like '%".$dmem_no."%' and (m.first_name like '%".$dname."%' or m.last_name like '%".$dname."%')) and d.date >= '".$from_date."' and d.date <= '".$to_date."' and s.type='free' ".$group." ".$branch." order by m.first_name, m.last_name, d.trans_date");
	   
	       
		if(@mysql_numrows($sth)==0){
				$cont = "<font color=red>No deposits in the selected search criteria</font>";
				$resp->assign("status", "innerHTML", $cont);
		                // return $resp;
		}
		else{
			//$max_page = ceil(mysql_num_rows($sth)/$num_rows);
			
			
	        if($group_id<>''){
	        while($row = @mysql_fetch_array($sth)){
	        $group_sum+=$row['amount'];	        
	        }
	        
	        }
			
	
 		$content .= '<table class="borderless table-hover" id="table-tools" width="100%">';
 		
			$content .= "<thead><th>#</th><th><b>Member Name</b></th><th><b>MemberNo</b></th><th><b>Amount</b></th><th><b>ReceiptNo</b></th><th><b>Cheque No</b></th><th><b>Percent Charge</b></th><th><b>Flat Charge</b></th><th><b>Date</b></th><th><b>Product</b></th><th><b>Bank Account</b></th></thead><tbody>";
			$i=$stat+1;
			while($row = @mysql_fetch_array($sth2)){
				$bank_res = mysql_query("select a.account_no as account_no, a.name as name from bank_account b join accounts a on b.account_id=a.id where b.id='".$row['bank_account']."'");
				$bank = mysql_fetch_array($bank_res);
				//$color = ($i%2 == 0) ? "lightgrey" : "white";
				//$edit = ($row['receipt_no'] == 'LOAN') ? number_format($row['amount'], 0) : "<a href='javascript:;' onclick=\"xajax_edit_deposit('".$row['id']."', '".$mem_no."', '".$name."', '".$product."', '".$from_date."','".$to_date."','".$branch_id."','".$num_rows."','".$stat."','".$cur_page."')\">".number_format($row['amount'], 0)."</a>";
				//commented out to stop editing of deposits, line below omits the editing ability
				$edit = number_format($row['amount'], 0);
                                
				$cheque_no = ($row['cheque_no'] == NULL) ? "--" : $row['cheque_no'];
				$content .= "<tr><td>".$i."</td><td>".$row['first_name']. " ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".$edit."</td><td>".$row['receipt_no']."</td><td>".$cheque_no."</td><td>".number_format($row['percent_value'], 2)."</td><td>".number_format($row['flat_value'], 0)."</td><td>".$row['date']."</td><td>".$row['account_no']." - ".$row['name']."</td><td>".$bank['account_no']." - ".$bank['name']."</td></tr>";
				$i++;
			}
		}
	}
	$content .= "</tbody></table></div>";
	$resp->call("createTableJs");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//REGISTER WITHDRAWAL
function add_withdrawal($mem_no, $type='cash'){
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
				
		$content = '
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">REGISTER A WITHDRAWAL</h3>
                            </div>               
                            <div class="panel-body">';
                                                        
 		$content .= '<div class="form-group">
                                   
                                        <div class="col-sm-3">
                                        <label class="control-label">Enter Member No:</label>
                                        <div class="input-group">
                                            
                                            <input type=int name="mem_no1" id="mem_no1" class="form-control">
                                              <span class="input-group-btn">
                                                <button class="btn btn-info form-control" type="button" onclick=\'xajax_add_withdrawal(getElementById("mem_no1").value); return false;\'>Next</button>
                                            </span>
                                        </div></div>
                                        <div class="col-sm-3">
                                            <label class="control-label">OR Select:</label>
                                            <div class="input-group">
                                           <select name="mem_no2" id="mem_no2" class="form-control"><option value="0">Select Member';
		$sth = mysql_query("select * from member order by first_name, last_name");
		while($row = mysql_fetch_array($sth)){
			$content .= "<option value='".$row['mem_no']."'>".$row['first_name']." ".$row['last_name'] ." - ".$row['mem_no'];
		}      
                                        $content.='</select>
                                            <span class="input-group-btn">
                                                <button class="btn btn-info form-control" type="button" onclick=\'xajax_add_withdrawal(getElementById("mem_no2").value); return false;\'>Next</button>
                                            </span>
                                        </div>
                                        </div>
                                    </div>
                                </div></div></form></div></div>';
                                
		
		 if($mem_no=='' || $mem_no=='0'){
		$cont ='<font color=red>Search for a member who wants to make a withdrawal</font>';
		$resp->assign("status", "innerHTML", "");	
	
		}else{

			$sth = mysql_query("select * from member where mem_no= '".$mem_no."'");
			if(@ mysql_numrows($sth)>0){
		
			$row = mysql_fetch_array($sth);

      //get loans and savings and withdraws
      $sel = mysql_fetch_array(mysql_query("select id,mem_id from mem_accounts where mem_id = '".$row['id']."'"));
 $savingid =$sel['id'];
      $mem_savings= mysql_query("select * from deposit where memaccount_id='".$savingid."' order by trans_date desc limit 5");

      $mem_withdraws= mysql_query("select * from withdrawal where memaccount_id='".$savingid."' order by trans_date desc limit 5");

      $total_withdraws= mysql_fetch_array(mysql_query("select sum(amount) as total_with from withdrawal where memaccount_id='".$savingid."'"));
  $mem_loans= mysql_query("select mem_id,loan_applic.id, payment.receipt_no,payment.princ_amt, payment.int_amt,payment.loan_id,payment.date,payment.begin_bal,payment.end_bal,payment.mode from loan_applic join payment on (loan_applic.id = payment.id) where mem_id='".$row['id']."'");
  //end get loans and savings
			$branch = mysql_fetch_assoc(mysql_query("select branch_name from branch where branch_no='".$row['branch_id']."'"));
			$opts = (strtolower($type) == 'cash')? "<option value='cash' selected>Cash</option><option value='transfer'>Transfer</option>": "<option value='cash'>Cash</option><option value='transfer' selected>Transfer</option>";	
			
			$content .='

 <div class="col-sm-6">
               <form class="panel panel-default form-bordered">
        <div class="panel-body">    
    <ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#home">Bio Information</a></li>
  <li><a data-toggle="tab" href="#menu4">Available Savings</a></li>
</ul>

<div class="tab-content">
  <div id="home" class="tab-pane fade in active">
  <div class="col-sm-4">';
if($row['photo_name'] != ""){
  $content .='<img src="photos/'.$row['photo_name'].'" width=80 height=60>
  ';
}else{
   $content .='<img src="photos/default.png" width=80 height=60>';
}
  $content .='</div>
   <div class="col-sm-4">
   <b>Name: </b><span class="name sort_1">'.ucwords($row['first_name']).' '.ucwords($row['last_name']).'</span><br>
    <span class="name sort_1"><b>Telephone:</b>'.$row['telno'].'</span><br>
    <span class="name sort_1"><b>Email:</b>'.$row['email'].'</span><br>
    <span class="name sort_1"><b>Address:</b>'.$row['address'].'</span>
  </div>
    <div class="col-sm-4">';
if($row['sign_name'] != ""){
  $content .='<img src="signs/'.$row['sign_name'].'" width=80 height=60>
  ';
}else{
   $content .='<img src="signs/default.png" width=80 height=60>';
}
  $content .='</div>
   </div>
 
 <div id="menu4" class="tab-pane fade">
    <table class="borderless table-hover "  width="100%">
    <thead>
     <tr>    
        <th>Balance</th>
        <th>'.number_format(libinc::get_savings_bal($savingid)).'</th>   
      </tr>
    </thead>'; 
    $content.='</tbody>
  </table>
  </div>
 </div>
                       
             </a>
             </form>
                                
            </div></div>

                    <div class="col-md-6">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                                         
                            <div class="panel-body"><input type=hidden name="mem_id" value="'.$row['id'].'" id="mem_id">';
                       
                        
                        $content.="<div class='form-group'>
                                   
                                        <div class='col-sm-6'>

                                     <label class='control-label'>Withdrawal Type:</label>   
                                      <select name='with_type' id='with_type' class='form-control' onchange=\"xajax_add_withdrawal('".$mem_no."', document.getElementById('with_type').value); return false;\">".$opts."</select>     
                                   
                                 </div>";
                                 
                        $content .= '
                                   
                                        <div class="col-sm-6">
                                            <label class="control-label">Savings Product:</label>
                                            <select name="memaccount_id" id="memaccount_id" class="form-control" ><option value="">';
				$prod_res = mysql_query("select a.name as name, s.account_id as account_id, a.account_no as account_no, d.id as id from mem_accounts d join savings_product s on d.saveproduct_id=s.id join member m on d.mem_id=m.id join accounts a on s.account_id=a.id where s.type='free' and m.mem_no='".$mem_no."' and d.close_date >NOW() and a.name not in ('Compulsory Shares', 'Compulsory Savings')");
				if(mysql_numrows($prod_res) == 0){
					$resp->alert("This member has no active Savings Account.\n Ensure it is not closed or create them a new one");
					return $resp;
				}
				while($prod = mysql_fetch_array($prod_res))
					$content .= "<option value='".$prod['id']."'>".$prod['account_no'] ." - ".$prod['name'];
				$content .= '</select></div>                                        
                                   
                                </div>';
                                
                                if (strtolower($type) == 'transfer')
				{
					$content .='<div class="form-group">
                                    
                                        <div class="col-sm-6">
                                            <label class="control-label">Enter Receiving Account No.:</label><input type=text class="form-control" name="acct_to" id="acct_to" onblur=\'xajax_list_receiver_accts(document.getElementById("acct_to").value); return false;\'></td></tr>';
					$content .= "<tr bgcolor=white><td align=center><div id='receiver1_div' name='receiver1_div'></div></td><td><div id='receiver2_div' name='receiver2_div'></div></td></tr>";         	}
				else
				$content .= "<input type=hidden name='trans_to' id='trans_to' value='0'>";	                            
			$content .='<div class="form-group">
                                    
                                        <div class="col-sm-6">
                                            <label class="control-label">Date:</label>
                                            <input type="text" class="form-control" id="date" name="date" placeholder="'.date('Y-m-d').'" />
                                       
                                    </div>
                                </div>';
			
			 $content.='<div class="form-group">
                                   
                                        <div class="col-sm-6">
                                        <label class="control-label">Amount:</label>
                                           <input onkeyup="format_as_number(this.id)" type=int name="amount" id="amount" class="form-control" >
                                 
                                 </div>';
                                 
                         $content.='<div class="form-group">
                                    
                                        <div class="col-sm-6">
                                        <label class="control-label">Voucher No.:</label>
                                           <input type=varchar(10) name="voucher_no" id="voucher_no" class="form-control" >
                                    
                                 </div></div>';
                                                        
                          $content.='<div class="form-group">
                                   
                                        <div class="col-sm-6">
                                        <label class="control-label">Withdrawal Charge:</label>
                                           <input type=int name="wcharge" id="wcharge" placeholder="If not set" class="form-control" >
                                    
                                 </div></div>';                     
                                 
                            $content.='<div class="form-group">
                                   
                                        <div class="col-sm-6">
                                        <label class="control-label">Source Bank Account:</label>
                                           <select disabled name="bank_account" id="bank_account" class="form-control"><option value="">';
				//if (strtolower($_SESSION['position']) == 'manager')
					$account_res = mysql_query("select a.name as name, a.account_no as account_no, b.bank as bank, b.id as id from bank_account b join accounts a on b.account_id=a.id where b.id='".CAP_Session::get('account_assigned')."' && a.branch_no like '".$_SESSION['branch_no']."'");
				/*else
					$account_res = mysql_query("select a.name as name, a.account_no as account_no, b.bank as bank, b.id as id from bank_account b join accounts a on b.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."' and b.id in (select bank_account_id from user_account where user_id =".$_SESSION['user_id'].")");*/
				//$account_res = mysql_query("select a.name as name, a.account_no as account_no, b.bank as bank, b.id as id from bank_account b join accounts a on b.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."'");
				while($account = mysql_fetch_array($account_res)){
					$content .= "<option selected value='".$account['id']."'>".$account['account_no'] ." - ".$account['bank']." ".$account['name'];
				}
				$content .= '</select>
                                    </div>
                                 </div></div> </div>';                    
                                
			 $content .= "<div class='panel-footer'>                              
                                
                                <input type='button' class='btn  btn-default' value='Back'  onclick=\"xajax_add_withdrawal(''); return false;\">&nbsp;<input type='reset' class='btn btn-default' value='Reset'  onclick=\"xajax_add_withdrawal('".$mem_no."', '".$type."')\">&nbsp;<input type='button' class='btn  btn-primary' value='Save'  onclick=\"xajax_insert_withdrawal(getElementById('memaccount_id').value, getElementById('mem_id').value, getElementById('amount').value, getElementById('voucher_no').value, getElementById('wcharge').value, getElementById('bank_account').value,  getElementById('date').value, '".$row['branch_id']."', getElementById('trans_to').value); return false;\">
                            </div></div>
                        </form>
                        <!--/ Form default layout -->
                    </div></div>";
                    $resp->call("createDate","date");
		}
		else{
		$cont = "<font color=red>No such member exists!</font>";
		$resp->assign("status", "innerHTML", $cont);
		}			
		
	}
	
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function list_receiver_accts($mem_no)
{
	$resp = new xajaxResponse();
	//$resp->alert("Here");
	//return $resp;
	if ($mem_no == '')
		$resp->alert("Please enter the receiver's Member No.");
	elseif (mysql_num_rows(mysql_query("select * from member where mem_no = '".$mem_no."'")) < 1)
		$resp->alert("The Member No. you entered is incorrect.");
	else
	{
		$mem_res = mysql_query("select m.id as mem_id, m.mem_no, m.first_name, m.last_name, ma.id as memaccount_id, a.name as prod_name from member m join mem_accounts ma on m.id = ma.mem_id join savings_product sp on ma.saveproduct_id = sp.id join accounts a on sp.account_id = a.id where m.mem_no = '".$mem_no."' and a.name not in ('Compulsory Savings', 'Compulsory Shares') order by m.first_name, m.last_name asc");
		$members = "<option value=''>&nbsp;</option>";
		while ($mem_row = mysql_fetch_array($mem_res))
		{
			$members .= "<option value='".$mem_row['memaccount_id']."'>".$mem_row['first_name']." ".$mem_row['last_name']." - (".$mem_row['prod_name'].")</option>";
		}
		$content1  = "Select Receiving Account Product.:";
		$content2  = '<div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12"><select name="trans_to" id="trans_to" class="form-control">'.$members.'</select></div></div></div>';
		$resp->assign("receiver1_div", "innerHTML", $content1);
		$resp->assign("receiver2_div", "innerHTML", $content2);
	}
	return $resp;
}

function insert_withdrawal($memaccount_id, $mem_id, $amount, $voucher_no,$flat_value, $bank_account, $date,$branch_id, $trans_to){

        list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$resp->assign("status", "innerHTML", "");
	$amount=str_ireplace(",","",$amount);
	$sth = @mysql_query("select open_date, date_format(open_date, '%Y') as open_year, date_format(open_date, '%m') as open_month, date_format(open_date, '%d') as open_mday  from mem_accounts where id='".$memaccount_id."'");
	$row = mysql_fetch_array($sth);
	$date = sprintf("%04d-%02d-%02d", $year, $month, $mday);
	$date = $date." ".date('H:i:s');
	$charge_res = mysql_query("select s.min_bal as min_bal, s.withdrawal_perc as withdrawal_perc, s.withdrawal_flat as withdrawal_flat, s.grace_period as grace_period from mem_accounts mem join savings_product s on mem.saveproduct_id=s.id where mem.id='".$memaccount_id."'");
	
	$charge_min = mysql_fetch_array($charge_res);
	$availableSavings=libinc::get_savings_bal($memaccount_id) - $charge_min['min_bal'];
	$vch= mysql_query("select * from withdrawal where voucher_no='".$voucher_no."'");
	if(isFYClosed(parseFY($year,$month,$mday)))
		$resp->alert("Financial year has been Closed!\nCannot run a transaction in the selected period.");
	elseif($memaccount_id=='' || $mem_id=='' || $amount=='' || $bank_account=='')
		$resp->alert("You may not leave any field blank");
	elseif($voucher_no == "")
		$resp->alert("You cannot leave Voucher No. blank.");
	elseif(mysql_num_rows($vch) > 0)
	        $resp->alert("voucher No exists!");
	elseif($trans_to == '')
		$resp->alert("Please select which account to transfer money to.");
	elseif(!$calc->isValidDate($mday, $month, $year))
		$resp->alert("Withdrawal not entered, please enter valid date");
	elseif($calc->isFutureDate($mday, $month, $year))
		$resp->alert("Withdrawal not entered! You have entered a future date");
	elseif($calc->dateDiff($mday, $month, $year, $row['open_mday'], $row['open_month'], $row['open_year']) < $charge['open_period'])
		$resp->alert("Withdrawal not entered! The date is in a period when withdrawal from this account is not acceptable");		
	elseif($date < $row['open_date'])  //No withdrawal before the account was opened
		$resp->alert("Withdrawal not entered! Date entered, is earlier than the when the account was opened");
	elseif($amount > $availableSavings)
		$resp->alert("You Cannot Withdraw this Amount! \n Customer has Insufficient Savings \n Only ".number_format($availableSavings,2)." is Available");
	else{
		//CHECK THAT THE BANK ACCOUNT WOULD NOT GO BELOW MINIMUM
		$sth = mysql_query("select * from bank_account where id='".$bank_account."'");
		$row = mysql_fetch_array($sth);
		
		if(libinc::cashAccountBalance($bank_account) - $amount < $row['min_balance']){
			$resp->alert("Withdrawal rejected! \n Your cash account has Insufficient Balance! Only ".number_format((libinc::cashAccountBalance($bank_account) - $row['min_balance']))." is available.  \n Please Contact Supervisor");
			return $resp;
		}
		/*
		$pledged_res = mysql_query("select sum(amount) as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id where mem.mem_id='".$mem_id."' and s.type='pledged'");
		$pledged = mysql_fetch_array($pledged_res);

		$dfree_res = mysql_query("select sum(d.amount - d.percent_value - d.flat_value)-".$amount." as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id where mem.mem_id='".$mem_id."' and s.type='free'");
		$dfree = mysql_fetch_array($dfree_res);
		$dfree_amt = $dfree['amount'];

		$wfree_res = mysql_query("select sum(w.amount + w.percent_value + w.flat_value) as amount from withdrawal w join mem_accounts mem on w.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id where mem.mem_id='".$mem_id."' and s.type='free'");
		$wfree = mysql_fetch_array($wfree_res);
		$wfree_amt = $wfree['amount'];
		
		//MONTHLY CHARGES 
		$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts mem on c.memaccount_id=mem.id where mem.mem_id='".$mem_id."'");
		$charge = mysql_fetch_array($charge_res);
		$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
		//INTEREST AWARDED
		$int = mysql_fetch_array(mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts mem on i.memaccount_id=mem.id where mem.mem_id='".$mem_id."'"));
		$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;
		//LOAN REPAYMENTS
		$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt + p.penalty + p.other_charges) as amount from payment p join mem_accounts mem on p.mode=mem.id where mem.mem_id='".$mem_id."'");
		$pay = mysql_fetch_array($pay_res);
		$pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;
		//INCOME DEDUCTIONS
		$inc_res = mysql_query("select sum(i.amount) as amount from other_income i join mem_accounts mem on i.mode=mem.id where mem.mem_id='".$mem_id."' and transaction  not in ('Other Charges','Loan Processing Fees','Interest')");
		$inc = mysql_fetch_array($inc_res);
		$inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;
		//$balance = $dep_amt + $int_amt  - $with_amt - $charge_amt - $pay_amt - $inc_amt;
		$pledged_amt = $pledged['amount'];
		$free_amt = $dfree_amt - $wfree_amt - $charge_amt + $int_amt - $pay_amt - $inc_amt;
		
		$vouc_res = mysql_query("select voucher_no from withdrawal where voucher_no='".$voucher_no."' union (select voucher_no from expense where voucher_no ='".$voucher_no."')");
		if(@ mysql_numrows($vouc_res) >0)
			$resp->alert("Withdrawal not registered, voucher already exists");
		elseif($free_amt < $pledged_amt)
			$resp->alert("Cannot withdraw this amount! It is part of the compulsory savings. \n First delete some of this member's loans, deposit=".$dfree_amt.", w=".$wfree_amt);
		elseif($free_amt < $charge_min['min_bal']){
			//$resp->alert("Cannot withdraw this amount! \n Member has no sufficient savings");
			$resp->confirmCommands(1,"Withdrawal Pending! \n The Savings account balance is below the minimum,Do you want to perform an Overdraft?");
 if(empty($flat_value)){
    //pending withdraw insert
  $prod_res = mysql_query("select s.withdrawal_perc as withdrawal_perc, s.withdrawal_flat as withdrawal_flat from mem_accounts mem join savings_product s on mem.saveproduct_id=s.id where mem.id='".$memaccount_id."'");
      //$resp->assign("status", "innerHTML", "select s.withdrawal_perc as withdrawal_perc, s.withdrawal_flat as withdrawal_flat from mem_accounts mem join savings_product on mem.saveproduct_id=s.id where mem.id='".$memaccount_id."'");
      //return $resp;
      $prod = mysql_fetch_array($prod_res);
      $percent_value = ($amount *$prod['withdrawal_perc']) /100;
      $flat_value= $prod['withdrawal_flat'];
      $tag = ($trans_to == 0)? "": "/Trans";
      start_trans();
      if($trans_to >0){
        $percent_value = 0;
        $flat_value= 0;
      }else{
        $percent_value = ($amount *$prod['withdrawal_perc']) /100;
        $flat_value= $prod['withdrawal_flat'];
      }
 }
$penwithins = mysql_query("insert into pendwithdrawal set memaccount_id='".$memaccount_id."', voucher_no='".$voucher_no.$tag."', trans_date = (select now()), cheque_no='".$cheque_no."', amount='".$amount."', percent_value='".$percent_value."', transfer=".$trans_to.", flat_value='".$flat_value."', bank_account='".$bank_account."', date='".$date."', branch_id=".$branch_id);
     
   if($penwithins){
      $resp->alert("An Overdraft registered! Please wait for authorization");
      commit();
      $resp->call('xajax_add_withdrawal');
    }else{
      $resp->alert("An Overdraft registeration failed! Please Contact Mobis with this error.<br>".mysql_error());
    }

}
		else{*/
			if(empty($flat_value)){
			$prod_res = mysql_query("select s.withdrawal_perc as withdrawal_perc, s.withdrawal_flat as withdrawal_flat from mem_accounts mem join savings_product s on mem.saveproduct_id=s.id where mem.id='".$memaccount_id."'");
			//$resp->assign("status", "innerHTML", "select s.withdrawal_perc as withdrawal_perc, s.withdrawal_flat as withdrawal_flat from mem_accounts mem join savings_product on mem.saveproduct_id=s.id where mem.id='".$memaccount_id."'");
			//return $resp;
			$prod = mysql_fetch_array($prod_res);
			$percent_value = ($amount *$prod['withdrawal_perc']) /100;
			$flat_value= $prod['withdrawal_flat'];
			$tag = ($trans_to == 0)? "": "/Trans";
			start_trans();
			if($trans_to >0){
				$percent_value = 0;
				$flat_value= 0;
			}else{
				$percent_value = ($amount *$prod['withdrawal_perc']) /100;
				$flat_value= $prod['withdrawal_flat'];
			}
			}
			$voucher_no=$voucher_no.$tag;
			$resp->confirmCommands(1, "Do you really want to withdraw ?");
			$resp->call("xajax_confirmWithdrawal",$memaccount_id, $mem_id,$amount,$voucher_no, $flat_value, $bank_account, $date,$branch_id,$trans_to,$percent_value);
		//}//
	}
	return $resp;
}

function confirmWithdrawal($memaccount_id, $mem_id, $amount,$voucher_no, $flat_value, $bank_account, $date,$branch_id,$trans_to,$percent_value){
        $resp = new xajaxResponse();
        $vch= mysql_query("select * from withdrawal where voucher_no='".$voucher_no."'");
                     if(mysql_num_rows($vch) > 0){
	              $resp->alert("Transaction already saved!");
	              return $resp;
	              }
mysql_query("insert into withdrawal set memaccount_id='".$memaccount_id."', voucher_no='".$voucher_no.$tag."', trans_date = (select now()), cheque_no='', amount='".$amount."', percent_value='".$percent_value."', transfer=".$trans_to.", flat_value='".$flat_value."', bank_account='".$bank_account."', date='".$date."', branch_id=".$branch_id);
		
			$refHandle = mysql_query("select last_insert_id() as last_id");
		        $refNo= mysql_fetch_array($refHandle);
			
			//UPDATE THE DISBURSEMENT BANK ACCOUNT
			if(! mysql_query("update bank_account set account_balance=account_balance-".$amount." where id=".$bank_account."")){
				rollback();
				$resp->alert("Withdrawal not registered \n Could not update bank account");
				return $resp;
			}
			///////////////
			$accno =mysql_fetch_assoc(mysql_query("select m.first_name as fname,m.last_name as lname,m.mem_no as memno,m.telno as telno from member m join mem_accounts ma on m.id=ma.mem_id where ma.id=".$memaccount_id));
			$action = "insert into withdrawal set memaccount_id='".$memaccount_id."', voucher_no='".$voucher_no.$tag."', trans_date = (select now()), cheque_no='', amount='".$amount."', percent_value='".$percent_value."', transfer=".$trans_to.", flat_value='".$flat_value."', bank_account='".$bank_account."', date='".$date."'";
			
			$msg = "Registered a withdrawal worth:".$amount." to member: ".$accno['fname']." ".$accno['lname']." - ".$accno['memno'];
			$first_name=$accno['fname'];
			$last_name=$accno['lname'];
			$mem_no=$accno['memno'];
			log_action($_SESSION['user_id'],$action,$msg);
			if ($trans_to <> 0)
			{
				mysql_query("insert into deposit set memaccount_id='".$trans_to."', amount='".$amount."', trans_date = (select now()), receipt_no='Transfer', cheque_no='', transfer=".$memaccount_id.", date='".$date."', bank_account='".$bank_account."', branch_id=".$branch_id);
				$action = "insert into deposit set memaccount_id='".$trans_to."', amount='".$amount."', trans_date = (select now()), receipt_no='Transfer', cheque_no='', transfer=".$memaccount_id.", date='".$date."', bank_account='".$bank_account."', branch_id=".$branch_id;
				$accno =mysql_fetch_assoc(mysql_query("select m.first_name as fname,m.last_name as lname,m.mem_no as memno,m.telno as telno from member m join mem_accounts ma on m.id=ma.mem_id where ma.id=".$trans_to));
				$msg = "Registered a deposit (transfer) worth:".$amount." to member: ".$accno['first_name']." ".$accno['last_name']." - ".$accno['mem_no'];
				log_action($_SESSION['user_id'],$action,$msg);
				
				
			}
			//////////////////
			commit();
			//$content = "<font color=red>Withdrawal registered</font>";
			////$content .= libinc::sendSMS($accno['telno'], "You have withdrawn ".$amount." from your ".$branch['branch_name']." Sacco account, your balance is ".checkBalance($accno['mem_no']));
			//$resp->assign("status", "innerHTML",$content);
					
			$company=libinc::getItemById("company",$_SESSION['companyId'],"companyId","companyName");
			$message="{$amount} has been withdrawn from your account under {$company}, {$first_name} {$last_name} {$mem_no}. Ref.no. {$refNo[0]} and remaining balance is ".number_format(libinc::get_savings_bal($memaccount_id));
			 
		        $res =  libinc::sendMessage($accno['telno'],$message);
				if($res!=null){
			
                     // $adminMessage="Account Creation Message Sent to {$first_name} {$last_name}";
                      $messageSent=true;
			
				 foreach($res as $result) {
					// status is either "Success" or "error message"
					/*$response .=" Number: " .$result->number;
					$response .=" Status: " .$result->status;
					$response.=" MessageId: " .$result->messageId;
					$response .=" Cost: "   .$result->cost."<br/>";
					*/
				  }
                        }
                        else
                        {
                        $adminMessage="";
                       $messageSent=false;
                      } 
                      //$content.='<div class="alert alert-success"><ul><li>';
			$content.=number_format($amount)." has been withdrawn from  {$mem_no} - {$first_name} {$last_name} 's Account. Ref.no. {$refNo[0]} \n BALANCE: ".number_format(libinc::get_savings_bal($memaccount_id));
			//$content.='</li></div>';
			$resp->alert($content);
			//$resp->assign("status", "innerHTML",$content);
			$resp->call('xajax_add_withdrawal');
                        return $resp;
}
//LIST WITHDRAWALS
function list_withdrawal($mem_no, $name, $product, $from_date,$to_date,$branch_id){
        list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
  $resp = new xajaxResponse();
  $resp->assign("status", "innerHTML", "");
  $branch=($branch_id=='all'||$branch_id=='')?NULL:'and mem.branch_id='.$branch_id;
  
  
  $content = '
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <p><h5 class="page-title">LIST OF WITHDRAWALS</h5></p>
                                       
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    
                                        <div class="col-sm-4">
                                            <label class="control-label">Search By Member No:</label>
                                            <input type="int" id="dmem_no" name="dmem_no" value="All" class="form-control">                                            
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="control-label">Member Name:</label>
                                            <input type="text" id="dname" value="All" class="form-control">
                                       
                                    </div>
                                                               
                                        <div class="col-sm-4">
                                            <label class="control-label">Savings Product:</label>
                                            <select name="saveproduct_id", id="saveproduct_id" class="form-control"><option value="">All';
  $sth = mysql_query("select a.name as name, a.account_no as account_no, s.id as id from savings_product s left join accounts a on s.account_id=a.id where a.branch_id like '".$_SESSION['branch_no']."'");
  while($row = @mysql_fetch_array($sth)){
    $content .= "<option value='".$row['id']."'>".$row['account_no']." - ". $row['name'];
  }
  $content .= '</select>                    
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="control-label">Date range:</label>
                                           
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>
                                                 
                                        <div class="col-sm-4">
                                            <label class="control-label">Branch:</label>
                                            <span>'.branch_rep($branch_id).'</span>
                                                                                                         
                                    </div></div>
                                </div>';
                                                              
                
  $content .= "<div class='panel-footer'>                              
                                
                                <input type='button' class='btn  btn-primary' value='Search'  onclick=\"xajax_list_withdrawal(getElementById('dmem_no').value, getElementById('dname').value, getElementById('saveproduct_id').value, getElementById('from_date').value,getElementById('to_date').value,getElementById('branch_id').value); return false;\">
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","from_date");
        $resp->call("createDate","to_date");
  //$resp->assign("display_div", "innerHTML", $content);
  
  
  
  if($mem_no=='' && $name=='' && $product ==''){
    $cont = "<font color=red>Please enter your search criteria</font>";
    $resp->assign("status", "innerHTML", $cont);
    //return $resp;
  }else{
    $dname = ($name == 'All') ? "" : $name;
    $dmem_no = ($mem_no == 'All') ? "" : $mem_no;
    $from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
    $to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);

    $sth = mysql_query("select w.voucher_no as voucher_no, w.cheque_no as cheque_no, w.bank_account as bank_account, w.id as id, w.percent_value as percent_value, w.flat_value as flat_value, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, w.amount as amount, w.date as date, a.account_no as account_no, a.name as name from  withdrawal w join mem_accounts mem on w.memaccount_id=mem.id join  member m on mem.mem_id=m.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where mem.saveproduct_id like '%".$product."%' and (m.mem_no like '%".$dmem_no."%' and (m.first_name like '%".$dname."%' or m.last_name like '%".$dname."%')) and w.date >= '".$from_date."' and w.date <= '".$to_date."' and s.type='free' order by m.first_name, m.last_name, w.trans_date");
    
    $sth2 = mysql_query("select w.voucher_no as voucher_no, w.cheque_no as cheque_no, w.bank_account as bank_account, w.id as id, w.percent_value as percent_value, w.flat_value as flat_value, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, w.amount as amount, w.date as date, a.account_no as account_no, a.name as name from  withdrawal w join mem_accounts mem on w.memaccount_id=mem.id join  member m on mem.mem_id=m.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where mem.saveproduct_id like '%".$product."%' and (m.mem_no like '%".$dmem_no."%' and (m.first_name like '%".$dname."%' or m.last_name like '%".$dname."%')) and w.date >= '".$from_date."' and w.date <= '".$to_date."' and s.type='free' order by m.first_name, m.last_name, w.trans_date");
    if(@mysql_numrows($sth)==0){
        $cont = "<font color=red>No withdrawals in the selected search criteria</font>";
        $resp->assign("status", "innerHTML", $cont);
    //return $resp;
    }
    else{
      //$max_page = ceil(mysql_num_rows($sth)/$num_rows);
      
      $content = '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <p><h4 class="semibold text-primary mt0 mb5">LIST OF WITHDRAWALS</h4></p>
                               <P></P>
                               </div>';
    $content .= '<table class="borderless table-striped table-hover" id="table-tools" width="100%">';
  
      $content .= "<thead><th>#</th><th><b>Member Name</b></th><th><b>MemberNo</b></th><th><b>Amount</b></th><th><b>VoucherNo</b></th><th><b>Cheque No</b></th><th><b>Percent Charge</b></th><th><b>Flat Charge</b></th><th><b>Date</b></th><th><b>Product</b></th><th><b>Bank Account</b></th></thead><tbody>";
      $i=$stat+1;
      while($row = @mysql_fetch_array($sth2)){
        //$color= ($i%2 == 0) ? "lightgrey" : "white";
        $bank_res = mysql_query("select a.account_no as account_no, a.name as name from bank_account b join accounts a on b.account_id=a.id where b.id='".$row['bank_account']."'");
        $bank = mysql_fetch_array($bank_res);
        //$content .= "<tr><td>".$i."</td><td>".$row['first_name']. " ".$row['last_name']."</td><td>".$row['mem_no']."</td><td><a href='javascript:;' onclick=\"xajax_edit_withdrawal('".$row['id']."', '".$row['amount']."', '".$mem_no."', '".$name."', '".$product."', '".$from_date."', '".$to_date."','".$branch_id."')\">".number_format($row['amount'], 0)."</a></td><td>".$row['voucher_no']."</td><td>".$row['cheque_no']."</td><td>".number_format($row['percent_value'], 2)."</td><td>".number_format($row['flat_value'], 0)."</td><td>".$row['date']."</td><td>".$row['account_no']." - ".$row['name']."</td><td>".$bank['account_no']." - ".$bank['name']."</td></tr>";
        
        //commented out to stop editing withdrawals, withdrawal function removed in the line below
        $content .= "<tr><td>".$i."</td><td>".$row['first_name']. " ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".number_format($row['amount'], 0)."</td><td>".$row['voucher_no']."</td><td>".$row['cheque_no']."</td><td>".number_format($row['percent_value'], 2)."</td><td>".number_format($row['flat_value'], 0)."</td><td>".$row['date']."</td><td>".$row['account_no']." - ".$row['name']."</td><td>".$bank['account_no']." - ".$bank['name']."</td></tr>";
        $i++;
      }
    }
  }
  $content .= "</tbody></table></div><div align=center>";
  $resp->call("createTableJs");
  $resp->assign("display_div", "innerHTML", $content);
  return $resp;
}

//LIST pending WITHDRAWALS
function list_pendwithdrawal($mem_no, $name, $from_date,$to_date,$branch_id){
        list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	$branch=($branch_id=='all'||$branch_id=='')?NULL:'and mem.branch_id='.$branch_id;
	
	
	$content = '
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <p><h5 class="page-title">LIST OF OVERDRAFTS</h5></p>
                                      
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    
                                        <div class="col-sm-3">
                                            <label class="control-label">Search By Member No:</label>
                                            <input type="int" id="dmem_no" name="dmem_no" value="All" class="form-control">                                            
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label">Member Name:</label>
                                            <input type="text" id="dname" value="All" class="form-control">
                                        
                                    </div>
                                
                                       <div class="col-sm-3">
                                            <label class="control-label">Branch:</label>
                                            <span>'.branch_rep($branch_id).'</span>
                                            
                                       
                                        
                                    </div>
                                        <div class="col-sm-3">
                                            <label class="control-label">Date range:</label>
                                           
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>
                                   
                                </div></div>
        
       ';
                                                              
                
	$content .= "<div class='panel-footer'>                              
                                
                                <input type='button' class='btn  btn-primary' value='Search'  onclick=\"xajax_list_pendwithdrawal(getElementById('dmem_no').value, getElementById('dname').value,  getElementById('from_date').value,getElementById('to_date').value,getElementById('branch_id').value); return false;\">
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","from_date");
		    $resp->call("createDate","to_date");
	//$resp->assign("display_div", "innerHTML", $content);
	
	
	
	if($mem_no=='' && $name=='' && $product ==''){
		$cont = "<font color=red>Please enter your search criteria</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}else{
		$dname = ($name == 'All') ? "" : $name;
		$dmem_no = ($mem_no == 'All') ? "" : $mem_no;
		$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
		$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);

		$sth = mysql_query("select w.voucher_no as voucher_no, w.cheque_no as cheque_no, w.bank_account as bank_account, w.id as id, w.percent_value as percent_value, w.flat_value as flat_value, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, w.amount as amount, w.date as date, a.account_no as account_no, a.name as name from  withdrawal w join mem_accounts mem on w.memaccount_id=mem.id join  member m on mem.mem_id=m.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where mem.saveproduct_id like '%".$product."%' and (m.mem_no like '%".$dmem_no."%' and (m.first_name like '%".$dname."%' or m.last_name like '%".$dname."%')) and w.date >= '".$from_date."' and w.date <= '".$to_date."' and s.type='free' order by m.first_name, m.last_name, w.trans_date");
		
		$sth2 = mysql_query("select w.voucher_no as voucher_no, w.cheque_no as cheque_no, w.bank_account as bank_account, w.id as id, w.percent_value as percent_value, w.flat_value as flat_value, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, w.amount as amount, w.date as date, a.account_no as account_no, a.name as name from  pendwithdrawal w join mem_accounts mem on w.memaccount_id=mem.id join  member m on mem.mem_id=m.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where mem.saveproduct_id like '%".$product."%' and (m.mem_no like '%".$dmem_no."%' and (m.first_name like '%".$dname."%' or m.last_name like '%".$dname."%')) and w.date >= '".$from_date."' and w.date <= '".$to_date."' and s.type='free' order by m.first_name, m.last_name, w.trans_date");
		if(@mysql_numrows($sth)==0){
				$cont = "<font color=red>No withdrawals in the selected search criteria</font>";
				$resp->assign("status", "innerHTML", $cont);
		//return $resp;
		}
		else{
			//$max_page = ceil(mysql_num_rows($sth)/$num_rows);
			
			$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <p><h4 class="semibold text-primary mt0 mb5">LIST OF OVERDRAFTS</h4></p>
                               <P></P>
                               </div>';
 		$content .= '<table class="table-hover borderless" id="table-tools">';
 	
			$content .= "<thead><th>#</th><th><b>Member Name</b></th><th><b>MemberNo</b></th><th><b>Amount</b></th><th><b>VoucherNo</b></th><th><b>Cheque No</b></th><th><b>Percent Charge</b></th><th><b>Flat Charge</b></th><th><b>Date</b></th><th><b>Product</b></th><th><b>Bank Account</b></th><th><b>Action</b></th></thead><tbody>";
			$i=$stat+1;
			while($row = @mysql_fetch_array($sth2)){
				//$color= ($i%2 == 0) ? "lightgrey" : "white";
				$bank_res = mysql_query("select a.account_no as account_no, a.name as name from bank_account b join accounts a on b.account_id=a.id where b.id='".$row['bank_account']."'");
				$bank = mysql_fetch_array($bank_res);
				$content .= "<tr><td>".$i."</td><td>".ucwords($row['first_name']. " ".$row['last_name'])."</td><td>".$row['mem_no']."</td><td>".number_format($row['amount'], 2)."</td><td>".$row['voucher_no']."</td><td>".$row['cheque_no']."</td><td>".number_format($row['percent_value'], 2)."</td><td>".number_format($row['flat_value'], 2)."</td><td>".$row['date']."</td><td>".$row['account_no']." - ".$row['name']."</td><td>".$bank['account_no']." - ".$bank['name']."</td><td><a href='javascript:;' onclick=\"xajax_confirm_approve_overdraft(".$row['id'].");\"'>Approve</a> | <a href='javascript:;' onclick=\"xajax_confirm_deny_overdraft(".$row['id'].");\"'>Deny</a></td></tr>";
				$i++;
			}
		}
	}
	$content .= "</tbody></table></div><div align=center>";
	$resp->call("createTableJs");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function confirm_approve_overdraft($id){
$resp = new xajaxResponse();
  $resp->assign("status", "innerHTML", "");
  $resp->confirmCommands(1, "Do you really want to Approve this Overdraft?");
return $resp;
}

function confirm_deny_overdraft($id){
$resp = new xajaxResponse();
  $resp->assign("status", "innerHTML", "");
  $resp->confirmCommands(1, "Do you really want to Deny this Overdraft?");
return $resp;
  
}

function edit_withdrawal($withdrawal_id, $former_amt, $mem_no, $name, $product, $from_date,$to_date,$branch_id){
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$resp->assign("status", "innerHTML", "");
	
	$sth = mysql_query("select m.mem_no as mem_no, m.id as mem_id, m.first_name as first_name, m.last_name as last_name, w.amount as amount, w.transfer as transfer, w.bank_account as bank_account, w.cheque_no as cheque_no, w.voucher_no as voucher_no, date_format(w.date, '%Y') as year, date_format(w.date, '%m') as month, date_format(w.date, '%d') as mday, w.memaccount_id as memaccount_id,w.trans_date as date, mem.saveproduct_id as saveproduct_id, a.account_no as account_no, a.name as name from withdrawal w join mem_accounts mem on w.memaccount_id=mem.id join member m on mem.mem_id=m.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where w.id='".$withdrawal_id."'");

	$former = mysql_fetch_array($sth);
	if ($former['transfer'] > 0)
	{
		$resp->alert("Sorry, you cannot edit a transfer.");
		return $resp;
	}
	
	$content ='
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="alert alert-info">
                            <p><h3 class="panel-title">EDIT WITHDRAWAL</h3></p>
                              <p><table>
			<tr><td><b>Member No:</b></td><td><span class="lead">'.$former['mem_no'].'</span></td><td><b>Member Name:&nbsp;</b></td><input type=hidden name="memaccount_id" value="'.$former['memaccount_id'].'" id="memaccount_id"><td><span class="lead">'.$former['first_name']." ".$former['last_name'].'</span></td></tr></table></p>
                            </div>               
                            <div class="panel-body">';
                        
                       
                        $content .= '<div class="col-sm-6"><div class="form-group">
                                   
                                        
                                            <label class="control-label">Savings Product:</label>
                                            <select name="saveproduct_id" id="saveproduct_id" class="form-control"><option value="'.$former['memaccount_id'].'">'.$former['account_no'] .' - '.$former['name'];
		
	$prod_res = mysql_query("select a.name as name, s.account_id as account_id, a.account_no as account_no, d.id as id from mem_accounts d join savings_product s on d.saveproduct_id=s.id join member m on d.mem_id=m.id join accounts a on s.account_id=a.id where s.type='free' and m.id='".$former['mem_id']."'");
				
	while($prod = mysql_fetch_array($prod_res))
    $d = $former['date'];
  $dat = new DateTime($d);
  $date = $dat->format('Y-m-d');
		$content .= "<option value='".$prod['id']."'>".$prod['account_no'] ." - ".$prod['name'];
	$content .= '</select>   
                                </div>';
                                
			$content .='<div class="form-group">
                                  
                                      
                                            <label class="control-label">Date:</label>
              <input type="text" class="form-control" id="date" name="date" value="'.$date.'" />
                                      
                                   
                                </div>';
			
			 $content.='<div class="form-group">
                                  
                                     
                                        <label class="control-label">Amount:</label>
                                           <input onkeyup="format_as_number(this.id)" type=int name="amount" id="amount" value="'.$former['amount'].'"  class="form-control" >
                                  
                               </div>';
                                 
                          $content.='<div class="form-group">
                                   
                                     
                                        <label class="control-label">Voucher No.:</label>
                                           <input type=varchar(10) name="voucher_no" id="voucher_no" value="'.$former['voucher_no'].'" class="form-control" >
                                    
                                </div>';
                          $content.='<div class="form-group">
                                   
                                        
                                        <label class="control-label">Cheque No. (Optional):</label>
                                           <input type=varchar(10) name="cheque_no" id="cheque_no" value="'.$former['cheque_no'].'" class="form-control" >
                                   
                               </div>';
                                 
                           $bank_res = mysql_query("select a.account_no as account_no, a.name as name, b.id as id from bank_account b join accounts a on b.account_id=a.id where b.id='".$former['bank_account']."'");
	$bank = mysql_fetch_array($bank_res);                           
                                 
                                                               
                            $content.="<div class='form-group'>
                                   
                                     
                                        <label class='control-label'>Source Bank Account:</label>
                                           <select name='bank_account' id='bank_account' class='form-control'><option value='".$former['bank_account']."'>".$bank['account_no']." - ".$bank['name'];
	//if (strtolower($_SESSION['position']) == 'manager')
		$account_res = mysql_query("select a.name as name, a.account_no as account_no, b.bank as bank, b.id as id from bank_account b join accounts a on b.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."' and b.id <>'".$former['bank_account']."'");
	/*else
		$account_res = mysql_query("select a.name as name, a.account_no as account_no, b.bank as bank, b.id as id from bank_account b join accounts a on b.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."' and b.id <> ".$row['bank_account']." and b.id in (select bank_account_id from user_account where user_id =".$_SESSION['user_id'].")");*/
	//$account_res = mysql_query("select a.name as name, a.account_no as account_no, b.bank as bank, b.id as id from bank_account b join accounts a on b.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."' and b.id<>'".$row['bank_account']."'");
	while($account = mysql_fetch_array($account_res)){
		$content .= "<option value='".$account['id']."'>".$account['account_no'] ." - ".$account['name'];
	}
	$content .= "</select>
                                  
                                 </div></div></div>";                    
                                
			 $content .= "<div class='panel-footer'>                              
                                
                                <input type='button' class='btn  btn-inverse mb5' value='Back'  onclick=\"xajax_list_withdrawal('".$mem_no."', '".$name."', '".$product."', '".$from_date."','".$to_date."','".$branch_id."','".$num_rows."','".$stat."','".$cur_page."')\">&nbsp;<input type='button' class='btn' value='Delete' onclick=\"xajax_delete_withdrawal('".$withdrawal_id."', '".$former_amt."', getElementById('bank_account').value);\">&nbsp;<input type='button' class='btn  btn-primary' value='Update'  onclick=\"xajax_update_withdrawal('".$withdrawal_id."', getElementById('memaccount_id').value, '".$former['mem_id']."', '".$former_amt."', getElementById('amount').value, getElementById('voucher_no').value, getElementById('cheque_no').value, getElementById('bank_account').value,  getElementById('date').value); return false;\">
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
	$resp->call("createDate","date");
	
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;		
}

//UPDATE WITHDRAWAL
function update_withdrawal($withdrawal_id, $memaccount_id, $mem_id, $former_amt, $amount, $voucher_no, $cheque_no, $bank_account, $date){
        list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$amount=str_ireplace(",","",$amount);
	
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$calc = new Date_Calc();
	$resp->assign("status", "innerHTML", "");
	if(isFYClosed(parseFY($year,$month,$mday)))
		$resp->alert("Financial year has been Closed!\nCannot run a transaction in the selected period.");
	elseif($withdrawal_id=='' || $memaccount_id==''|| $mem_id=='' || $amount=='' || $bank_account=='' || $date=='')
		$resp->alert("You may not leave any field blank");
	elseif($voucher_no == "" && $cheque_no == "")
		$resp->alert("You cannot leave both Cheque No. and Voucher No. blank.");
	elseif(!$calc->isValidDate($mday, $month, $year))
		$resp->alert("Update not done, please enter valid date");
	elseif($calc->isFutureDate($mday, $month, $year))
		$resp->alert("Update not done, you have entered a future date");
	else{
		//CHECK THAT THE BANK ACCOUNT WOULD NOT GO BELOW MINIMUM
		$sth = mysql_query("select * from bank_account where id='".$bank_account."'");
		$row = mysql_fetch_array($sth);
		/*
		if(($row['account_balance'] - $amount + $former_amt) < $row['min_balance']){
			$resp->alert("Update rejected! \n The disbursement would go below the minimum balance");
			return $resp;
		}
		*/
		$sth = mysql_query("select open_date, date_format(open_date, '%Y') as open_year, date_format(open_date, '%m') as open_month, date_format(open_date, '%d') as open_mday  from mem_accounts where id='".$memaccount_id."'");
		$row = mysql_fetch_array($sth);
		//$date = sprintf("%d-%02d-%02d", $date);
		$date = $date." ".date('H:i:s');
		$charge_res = mysql_query("select s.min_bal as min_bal, s.withdrawal_perc as withdrawal_perc, s.withdrawal_flat as withdrawal_flat, s.grace_period as grace_period from mem_accounts mem join savings_product s on mem.saveproduct_id=s.id where mem.id='".$memaccount_id."'");
		$charge = mysql_fetch_array($charge_res);
		if($calc->dateDiff($date, $row['open_mday'], $row['open_month'], $row['open_year']) < $charge['open_period'])
			$resp->alert("Update not done! Date entered, is earlier than the account was opened");
		elseif($date < $row['open_date'])  //No withdrawal before the account was opened
			$resp->alert("Update not done! The new date is in a period when withdrawal from this account is not acceptable");
		else{
			$pledged_res = mysql_query("select sum(amount) as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id where mem.mem_id='".$mem_id."' and s.type='pledged'");
			//check for compulsory savings
			$pledged = mysql_fetch_array($pledged_res);
			$dfree_res = mysql_query("select (sum(d.amount - d.percent_value - d.flat_value)-".$amount.") as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id where mem.mem_id='".$mem_id."' and s.type='free'");
			$dfree = @mysql_fetch_array($dfree_res);
			$dfree_amt = $dfree['amount'];
			
			$former_percent_value = ($former_amt * $charge['withdrawal_perc']) / 100;
			$former_flat_value = $charge['withdrawal_flat'];

			$now_percent_value = ($amount * $charge['withdrawal_perc']) / 100;
			$now_flat_value = $charge['withdrawal_flat'];

			$wfree_res = mysql_query("select (sum(w.amount + w.percent_value + w.flat_value) - ".$former_amt." - ".$former_percent_value." - ".$former_flat_value." + ".$amount." + ".$now_percent_value. " + ". $now_flat_value.") as amount from withdrawal w join mem_accounts mem on w.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id where mem.mem_id='".$mem_id."' and s.type='free'");
			$wfree = mysql_fetch_array($wfree_res); 
			$wfree_amt = $wfree['amount'];

			$pledged_amt = $pledged['amount'];
			$free_amt = $dfree_amt - $wfree_amt;

			$vouc_res = mysql_query("select * from withdrawal where voucher_no ='".$voucher_no."' and id<>'".$withdrawal_id."'");
			if($free_amt < $pledged_amt)
				$resp->alert("Update not done! The compulsory savings would be encroached on. \nDelete some loans for this member");
			elseif($free_amt < $charge['min_bal'])
				$resp->alert("Cannot withdraw this amount! \n Member hasnt sufficient savings");
			elseif(@ mysql_numrows($vouc_res) >0)
				$resp->alert("Update not done! The new VoucherNo already exists");
			else{
				$resp->confirmCommands(1, "Do you really want to update?");
				$resp->call('xajax_update2_withdrawal', $withdrawal_id, $memaccount_id, $former_amt, $amount, $now_percent_value, $now_flat_value, $voucher_no, $cheque_no, $bank_account, $date);
			}
		}	
	}
	return $resp;
}


//CONFIRM UPDATE OF WITHDRAWAL IN DB
function update2_withdrawal($withdrawal_id, $memaccount_id, $former_amt, $amount, $now_percent_value, $now_flat_value, $voucher_no, $cheque_no, $bank_account, $date){
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	start_trans();
	if(! mysql_query("update withdrawal set memaccount_id='".$memaccount_id."', amount='".$amount."', trans_date = (select now()), percent_value='".$now_percent_value."', flat_value='".$now_flat_value."', voucher_no='".$voucher_no."', cheque_no='".$cheque_no."', bank_account='".$bank_account."', date='".$date."' where id='".$withdrawal_id."'")){
		$resp->alert("ERROR: Could not update the withdrawal! \n Contact FLT");
		rollback();
		return $resp;
	}
	if(! mysql_query("update bank_account set account_balance=account_balance + ".$former_amt." - ".$amount." where id=".$bank_account."")){
		$resp->alert("ERROR: Could not update the bank account balance! \n Contact FLT");
		rollback();
		return $resp;
	}
			////////////////
			$accno =mysql_fetch_assoc(mysql_query("select m.first_name,m.last_name,m.mem_no from member m join mem_accounts ma on m.id=ma.mem_id where ma.id=".$memaccount_id));
			$action = "update withdrawal set memaccount_id='".$memaccount_id."', amount='".$amount."', percent_value='".$now_percent_value."', flat_value='".$now_flat_value."', voucher_no='".$voucher_no."', bank_account='".$bank_account."', date='".$date."' where id='".$withdrawal_id. "'";
			$msg = "Edited a withdrawal from:".number_format($former_amt,2)." to:".number_format($amount)." for member: ".$accno['first_name']." ".$accno['last_name']." - ".$accno['mem_no'];
			log_action($_SESSION['user_id'],$action,$msg);
			//////////////////
	commit();
	$resp->assign("status", "innerHTML", "<font color=red>Update done!</font>");
	return $resp;
}

//DELETE WITHDRAWAL
function delete_withdrawal($withdrawal_id, $amount, $bank_account_id){
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	$resp->assign("status", "innerHTML", "");
	$resp->confirmCommands(1, "Do you really want to delete?");
	$sth = mysql_query("select * from withdrawal where id='".$withdrawal_id."'");
	$row = mysql_fetch_array($sth);
	$resp->call('xajax_delete2_withdrawal', $withdrawal_id, $row['amount'], $row['bank_account']);
	return $resp;
}
 
function delete2_withdrawal($withdrawal_id, $amount, $bank_account_id){
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	$former_res = mysql_query("select * from withdrawal where id='".$withdrawal_id."'");
	$former = mysql_fetch_array($former_res);
	start_trans();
	$accno =mysql_fetch_assoc(mysql_query("select m.first_name,m.last_name,m.mem_no from member m join  mem_accounts ma on m.id=ma.mem_id join withdrawal w on w.memaccount_id=ma.id where w.id=".$withdrawal_id));
	if(! mysql_query("delete from withdrawal where id='".$withdrawal_id."'")){
		$resp->alert("ERROR: Could not delete withdrawal! \n Contact FLT");
		rollback();
		return $resp;
	}
	//UPDATE THE BANK ACCOUNT
	if(! mysql_query("update bank_account set account_balance=account_balance +".$former['amount']." where id='".$bank_account_id."'")){
		$resp->alert("ERROR: Could not update bank balance! \n Contact FLT");
		rollback();
		return $resp;
	}
			////////////////////
			$action = "delete from withdrawal where id='".$withdrawal_id."'";
			$msg = "Deleted an amount:".number_format($former['amount'],2)." from members account:".$accno['mem_no']." - ".$accno['first_name']." ".$accno['last_name'];
			log_action($_SESSION['user_id'],$action,$msg);
			//////////////////
	mysql_query("insert into logs set user_id='".$_SESSION['user_id']."', time=NOW(),  action='".$_SESSION['name'].":  ".mysql_escape_string($action)."'");
	commit();
	$resp->assign("status", "innerHTML", "<font color=red>Withdrawal deleted!</font>");
	return $resp;
}
//SAVINGS LEDGER FORM
function savings_ledger_form($mem_id)
{

    $save_accts ="";
	$resp = new xajaxResponse();
	if ($mem_id != '')
	{
		$mem_ac_res = @mysql_query("select ma.id as mem_acct_id, ac.name, ac.account_no from mem_accounts ma join savings_product sp on ma.saveproduct_id = sp.id join accounts ac on sp.account_id = ac.id where ma.mem_id = $mem_id and sp.type='free'");
		if (@mysql_num_rows($mem_ac_res) > 0)
		{	
			while ($acc_row = @mysql_fetch_array($mem_ac_res))
			{
				$save_accts .= "<option value='".$acc_row['mem_acct_id']."'>".$acc_row['account_no']." -".$acc_row['name']."</option>";
			}
		}
		else
			$save_accts .= "<option value=''>&nbsp;</option>";
	}
	else
		$save_accts .= "<option value=''>&nbsp;</opton>";

	$mem_res = @mysql_query("select id as mem_id, mem_no, first_name, last_name from member order by first_name, last_name asc");
	if (@mysql_num_rows($mem_res) > 0)
	{
		$members = "<option value=''>&nbsp;</option>";
		while ($mem_row = @mysql_fetch_array($mem_res))
		{
			if ($mem_row['mem_id'] == $mem_id)
				$members .= "<option value='$mem_row[mem_id]' selected> $mem_row[first_name] $mem_row[last_name] - $mem_row[mem_no] </option>";
			else
				$members .= "<option value='$mem_row[mem_id]'>$mem_row[first_name] $mem_row[last_name] - $mem_row[mem_no] </option>";
		}
	}
	else
		$members = "<option value=''>&nbsp;</option>";
		
		
	$content = '
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <p><h5 class="semibold text-primary mt0 mb5">RUN A SAVINGS LEDGER</h5></p>
                                          
                            <div class="panel-body">
                            
                      <div class="form-group">
                                   
                                        <div class="col-sm-3">
                                            <label class="control-label">Enter Member No:</label>
                                            <input type="int" id="mem_no" name="mem_no" class="form-control">                                            
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label">OR Search By Member No:</label>
                                            <select name="member" id="member" class="form-control" onchange=\'xajax_savings_ledger_form(getElementById("member").value); return false;\'>'.$members.'</select>
                                       
                                    </div>
                               ';
                              
                  $content .= '
                                   ';
                        if($mem_id<>""){             
                                     $content .= '<div class="col-sm-3">
                          <label class="control-label">Select Savings Account:</label>
                         <select id="save_acct" name="save_acct" class="form-control">'.$save_accts.'</select>              
                                        </div>';
                      }
                      else
		         $content .= '<input type=hidden id="save_acct">';                      
                                     $content .= '<div class="col-sm-3">
                                            <label class="control-label">Date range:</label>
                                           <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>
                                    </div>
                                </div></div>';
                                                                      
                
	$content .= "<div class='panel-footer'>                              
                                
                                <input type='button' class='btn  btn-primary' value='Show Ledger'  onclick=\"xajax_savings_ledger(getElementById('mem_no').value, getElementById('member').value, getElementById('save_acct').value, getElementById('from_date').value,getElementById('to_date').value); return false;\">
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","from_date");
	$resp->call("createDate","to_date");
	
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}
//GENERATE SAVINGS LEDGER
function savings_ledger($mem_no, $mem_id, $save_acct, $from_date,$to_date)
{       
        list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	if($save_acct =='' && $mem_no ==''){
		$resp->alert("Please enter the Member No");
		return $resp;
	}
	if($mem_no <>"" && $save_acct==''){
		$sth = mysql_query("select * from member where mem_no='".$mem_no."'");
		if(mysql_numrows($sth) ==0){
			$resp->alert("The entered Member No does not exist!");
			return $resp;
		}
		$row = mysql_fetch_array($sth);
		$acct_res = mysql_query("select mem.id as id from mem_accounts mem join savings_product s on mem.saveproduct_id=s.id where mem.mem_id='".$row['id']."' and s.type='free'");
		if(mysql_numrows($acct_res) > 1){
			$resp->call('xajax_savings_ledger_form', $row['id']);
			return $resp;
		}elseif(mysql_numrows($acct_res) >= 1){
			$acct = mysql_fetch_array($acct_res);
			$save_acct = $acct['id'];
			$mem_id = $row['id'];
		}elseif(mysql_numrows($acct_res) ==0){
			$resp->alert("This Member hasnt a savings account!");
			return $resp;
		}
	}
  if($to_date == ''){
    $today = date("Y-m-d h:i:s",time());
$start_date = "0000-00-00 00:00:00";
  $end_date = $today;
  }else{
	$start_date = sprintf("%04d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$end_date = sprintf("%04d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
  }	$start_month = intval($from_month); $end_month = intval($to_month);
	$start_year = intval($from_year); $end_year = intval($to_year);
	$total_saved = 0; $total_with = 0; $total_int = 0; $total_fees = 0;
	$cumm_save = 0;
	
	$drow1 = @mysql_fetch_array(@mysql_query("select sum(amount - flat_value - percent_value) as tot_savings from deposit where bank_account != 0 and memaccount_id = $save_acct and date <= '".$start_date."'"));
	$disb1 = @mysql_fetch_array(@mysql_query("select sum(amount) as amount from disbursed where mode=$save_acct and date <= '".$start_date."'"));	
	$wrow1 = @mysql_fetch_array(@mysql_query("select sum(amount + flat_value + percent_value) as tot_with from withdrawal where memaccount_id = $save_acct and date <= '".$start_date."'"));
	$mrow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_fees from monthly_charge where memaccount_id = $save_acct and date <= '".$start_date."'"));
	$irow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_int from save_interest where memaccount_id = $save_acct and date <= '".$start_date."'"));
	$prow1 = @mysql_fetch_array(@mysql_query("select sum(princ_amt + int_amt + penalty + other_charges) as tot_int from payment where mode = '$save_acct' and date <= '".$start_date."'"));
	$incow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_inc from other_income where mode = '$save_acct' and transaction  not in ('Other Charges', 'Loan Processing Fees','Interest','Penalty') and date <= '".$start_date."'"));
	$shares = @mysql_fetch_array(@mysql_query("select sum(value) as tot_val from shares where mode = '$save_acct' and date <= '".$start_date."'"));
	$total_shares = isset($shares['tot_val'])? $shares['tot_val'] : 0 ;
		
	$pay1 = @mysql_fetch_array(@mysql_query("select sum(amount) as amount from payable where mode='".$save_acct."' and transaction in ('Health Insurance','Loan Fees Payable') and date <= '".$start_date."'"));
	
	$debit = @mysql_fetch_array(@mysql_query("select sum(amount) as amount from non_cash where debitedSavingsAcct= '".$save_acct."' and date <= '".$start_date."'"));
	
	$credit = @mysql_fetch_array(@mysql_query("select sum(amount) as amount from non_cash where creditedSavingsAcct= '".$save_acct."' and date <= '".$start_date."'"));
	
	$other_fund = @mysql_fetch_array(@mysql_query("select sum(amount) as amount from other_funds where mode= '".$save_acct."' and date <= '".$start_date."'"));
												
        $total_saved = isset($drow1['tot_savings']) ? intval($drow1['tot_savings']) : 0;
        $total_fees = isset($mrow1['tot_fees']) ? intval($mrow1['tot_fees']) : 0;
        $total_with = isset($wrow1['tot_with']) ? intval($wrow1['tot_with']) : 0;
        $total_int = isset($irow1['tot_int']) ? intval($irow1['tot_int']) : 0;
	$total_pay = isset($prow1['tot_int']) ? intval($prow1['tot_int']) : 0;
	$total_inc = isset($incow1['tot_inc']) ? intval($incow1['tot_inc']) : 0;
	$total_disb = isset($disb1['amount']) ? intval($disb1['amount']) : 0;
	$total_pay = isset($pay1['amount']) ? intval($pay1['amount']) : 0;
	$total_debit = isset($debit['amount']) ? intval($debit['amount']) : 0;
	$total_credit = isset($credit['amount']) ? intval($credit['amount']) : 0;
	$total_other_fund = isset($other_fund['amount']) ? intval($other_fund['amount']) : 0;
	
        $net_save = ($total_saved + $total_int + $total_disb + $total_pay + $total_credit) - ($total_fees + $total_with + $total_pay + $total_inc + $total_shares + $total_debit - $total_other_fund);
	$cumm_save += $net_save;
	$mem_row = @mysql_fetch_array(@mysql_query("select first_name, last_name, mem_no, sign_name, photo_name,branch_id from member where id = $mem_id"));
	$branch = mysql_fetch_array(mysql_query("select * from branch where branch_no='".$mem_row['branch_id']."'"));
	//$pdt = mysql_fetch_array(mysql_query("select name from accounts where id = '".$save_acct."'"));

        $pdt = mysql_fetch_array(mysql_query("select ma.id as mem_acct_id, ac.name, ac.account_no from mem_accounts ma join savings_product sp on ma.saveproduct_id = '".$save_acct."' join accounts ac on sp.account_id = ac.id where ma.mem_id = $mem_id and sp.type='free'"));

  //get deposit balance
  $acc_res_bal = mysql_query("select id, date, amount, transaction, depositor as depositor from deposit where memaccount_id = $save_acct and date > '".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction, '--' as depositor from withdrawal where memaccount_id = $save_acct and date > '".$start_date."' and date <= '".$end_date."' UNION select id, date, amount, transaction, '--' as depositor  from monthly_charge where memaccount_id = $save_acct and date >'".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction, '--' as depositor  from save_interest where memaccount_id = $save_acct and date > '".$start_date."' and date <='".$end_date."' UNION select id, date, princ_amt + int_amt + penalty + other_charges as amount, transaction, '--' as depositor  from payment where mode= '".$save_acct."' and date > '".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction, '--' as depositor  from other_income where mode = '".$save_acct."' and date > '".$start_date."' and date <= '".$end_date."' UNION select id, date, value as amount, transaction, '--' as depositor  from shares where mode = '".$save_acct."' and date > '".$start_date."' and date <= '".$end_date."' order by date asc");
  $x = 0;
  while ($acc_row = mysql_fetch_array($acc_res_bal))
  {
    
    //$_SESSION["cumulateive_savings"]=$cumm_save;

    $charge_amt = 0;
    $tot_shares = strtolower($acc_row['transaction']) == 'shares' ? intval($acc_row['amount']) : 0 ;
    $tot_savings = ((strtolower($acc_row['transaction']) == 'deposit') || (strtolower($acc_row['transaction']) == 'loan disbursed')) ? intval($acc_row['amount']) : 0 ;
    $tot_fees = strtolower($acc_row['transaction']) == 'monthly_charge' ? intval($acc_row['amount']) : 0 ;
    $tot_with = strtolower($acc_row['transaction']) == 'withdrawal' ? intval($acc_row['amount']) : 0 ;
    $tot_int = strtolower($acc_row['transaction']) == 'save_interest' ? intval($acc_row['amount']) : 0 ;
    $tot_pay = strtolower($acc_row['transaction']) == 'payment' ? intval($acc_row['amount']) : 0 ;
    $tot_inc = strtolower($acc_row['transaction']) == 'other_income' ? intval($acc_row['amount']) : 0 ;

    if((strtolower($acc_row['transaction']) == 'deposit') || (strtolower($acc_row['transaction']) == 'loan disbursed')){
			$charge = mysql_fetch_array(mysql_query("select receipt_no, cheque_no, (flat_value + percent_value) as amount from deposit where id='".$acc_row['id']."'"));
			$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
			if(strtolower($acc_row['transaction']) == 'deposit'){
			$descr="Deposit - RCPT: ".$charge['receipt_no'];
			$descr = ($charge['cheque_no'] <>"") ? $descr." - CHEQ: ".$charge['cheque_no'] : $descr;
			}
			elseif(strtolower($acc_row['transaction']) == 'loan disbursed')
			$descr="Loan";
			
		}
		
    if(strtolower($acc_row['transaction']) == 'withdrawal'){
      $charge = mysql_fetch_array(mysql_query("select voucher_no, cheque_no, flat_value + percent_value as amount from withdrawal where id='".$acc_row['id']."'"));
      $charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
      $descr="Withdrawal
      - PV: ".$charge['voucher_no'];
      $descr = ($charge['cheque_no'] <>"") ? $descr."
      - CHEQ: ".$charge['cheque_no'] : $descr;
    }
    if(strtolower($acc_row['transaction']) == 'payment'){
  
      $pay = mysql_fetch_array(mysql_query("select receipt_no,  princ_amt + int_amt + penalty + other_charges as amount from payment where id='".$acc_row['id']."'"));
      $pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;
      $descr="Loan Repayment
      - PV: ".$pay['receipt_no'];
      //$resp->alert($tot_pay);
    }

    if(strtolower($acc_row['transaction']) == 'other_income'){
  
      $inc = mysql_fetch_array(mysql_query("select i.receipt_no, i.cheque_no, i.amount, a.name from other_income i join accounts a on a.id = i.account_id where i.id='".$acc_row['id']."'"));
      $inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;
      $descr="DEDUCTION ($inc[name])";
      //$resp->alert($tot_pay);
    }
    if(strtolower($acc_row['transaction']) == 'shares'){
  
      $share = mysql_fetch_array(mysql_query("select s.receipt_no, s.value as amount from shares s where s.id='".$acc_row['id']."'"));
      $share_amt = ($share['amount'] != NULL) ? $share['amount'] : 0;
      $descr="TRANSFER TO SHARES 
      - PV / CHEQ: ".$share['receipt_no'];
      //$resp->alert($tot_pay);
    }
    //$tot_fees = $tot_fees + $charge_amt;
    //$net_save = ($tot_savings + $tot_int) - ($tot_fees + $charge_amt + $tot_with);
    //$cumm_save += $net_save;
    if($tot_savings != 0){
      $cumm_save_deposit_remain += $tot_savings;
      $x++;
      //$color = ($x%2 == 0) ? "white" : "lightgrey";
 
    }
    if($tot_int !=0){
      $cumm_save_deposit_remain += $tot_int;
      $x++;
      //$color = ($x%2 == 0) ? "white" : "lightgrey";
  
    }
    if($tot_shares !=0){
      $cumm_save_deposit_remain -= $tot_shares;
      $x++;
      //$color = ($x%2 == 0) ? "white" : "lightgrey";
     
    }
    if($tot_with !=0){
      $cumm_save_deposit_remain -= $tot_with;
      $x++;
      //$color = ($x%2 == 0) ? "white" : "lightgrey";
    
    }
    if($tot_pay >0 || $tot_pay <0){
      $cumm_save_deposit_remain -= $tot_pay;
      $x++;
      
      //$color = ($x%2 == 0) ? "white" : "lightgrey";
     
    }
    if($charge_amt !=0){
      $x++;
      $cumm_save_deposit_remain -= $charge_amt;
      //$color = ($x%2 == 0) ? "white" : "lightgrey";
   
    }
    if($tot_inc !=0){
      $cumm_save_deposit_remain -= $tot_inc;
      $x++;
    
    }
    if($tot_fees !=0){
      $x++;
      $cumm_save_deposit_remain -= $tot_fees;
    
    }

  }

  //end deposit balance
	
	$content .= "<div class='panel panel-default' id='demo'>
                            <div class='panel-heading'>               
                     <center><h3 class=''>MEMBER SAVINGS STATEMENT</h3></center>";
		    $content.="<input type='button' class='pull-right' href=\"#\" onClick =\"$('#table-memSav').tableExport({type:'excel',escape:'false'});\" value='Excel'>   
   <input type='button' onclick=\"generate({mime:'jpeg',table_id:'table-memSav', filename:'member savings.pdf', title:'MEMBERS SAVINGS STATEMENT', subtitle:'".strtoupper($mem_row['first_name'])." ". strtoupper($mem_row['last_name'])." ". strtoupper($mem_row['mem_no'])."', logo:''})\" class='pull-right' value='PDF'><br><br>";
$content.="<table class='table table-bordered' id='table-tools'>
       <tr><td>";if(trim($mem_row['photo_name']) == ''){
$content .= " <img src='photos/default.png' width=90 height=90>";
       }
else{
       $content .= " <img src='photos/".$mem_row['photo_name']."?dummy=".time()."' width=90 height=90></td>";
      }
      $content .= " <td><p>
<h3 class='semibold'>Branch : ".$branch['branch_name']."</h3>
       <h5 class='semibold'><B>Name:</B><b>".strtoupper($mem_row['first_name'])." ". strtoupper($mem_row['last_name'])."</b></h5></p><p><h5 class='semibold'><B>Member Number:</B><b>".$mem_row['mem_no']."</b></h5></p><p><h5 class='semibold'><B>Period:</B><b>".$start_date  ."  -  ". $end_date."</b></h5></p></td>
       <td><p>
<h3 class='semibold'>Running Balance : <span style='color:blue;''>Ugx. ".number_format(libinc::get_savings_bal($save_acct),2)."<span></h3></p>
</td>

<td>";if(trim($mem_row['sign_name']) == ''){
$content .= " <img src='photos/signature-placeholder.png' width=90 height=90>";
       }
else{
       $content .= " <img src='signs/".$mem_row['sign_name']."?dummy=".time()."' width=90 height=90></td>";
      }
      $content .= " 

      </tr></table>
      </div>";
			
		   $content .= "<table class='table table-bordered' id='table-tools'>	 
		  
		  </table></div>";

 $content .= "<div class='panel' id='demo'>
                           
                            <table class='borderless table-hover table-striped' width='100%' id='table-memSav'>";
$content .= "<thead><th>Date</th><th>Depositor</th><th width='30%'>Description</th><th>Debit</th><th>Credit</th><th>Balance</th></thead><tbody><tr>";
       $content .="<td>Before $start_date</td><td>--</td><td>B/F</td><td>--</td><td>--</td><td>".number_format($cumm_save, 2)."</td>
		    </tr>";		  
		  
	$acc_res = mysql_query("select id, date, amount, transaction, depositor as depositor from deposit where memaccount_id = $save_acct and date >= '".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction, '--' as depositor from withdrawal where memaccount_id = $save_acct and date >= '".$start_date."' and date <= '".$end_date."' UNION select id, date, amount,'Loan Disbursement' as transaction, 'Loan Disbursement' as depositor from disbursed where mode = $save_acct and date >='".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction, '--' as depositor  from monthly_charge where memaccount_id = $save_acct and date >='".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction, '--' as depositor  from save_interest where memaccount_id = $save_acct and date >= '".$start_date."' and date <='".$end_date."' UNION select id, date, princ_amt + int_amt + penalty + other_charges as amount, transaction, '--' as depositor  from payment where mode= '".$save_acct."' and date >= '".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction, '--' as depositor  from other_income where mode = '".$save_acct."' and date >= '".$start_date."' and date <= '".$end_date."' UNION select id, date, amount, transaction, '--' as depositor  from other_funds where mode = '".$save_acct."' and date >= '".$start_date."' and date <= '".$end_date."' UNION select id, date, value as amount, transaction, '--' as depositor  from shares where mode = '".$save_acct."' and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id,date,amount, transaction, '--' as depositor from payable where mode= '".$save_acct."' and date >= '".$start_date."' and date <= '".$end_date."' UNION select id,date,amount, 'non cash source' as transaction, '--' as depositor  from non_cash where debitedSavingsAcct= '".$save_acct."' and date >= '".$start_date."' and date <= '".$end_date."' UNION select id,date,amount, 'non cash destination' as transaction, '--' as depositor from non_cash where creditedSavingsAcct= '".$save_acct."' and date >= '".$start_date."' and date <= '".$end_date."' UNION select id,date,amount, 'Shares Redeemed' as transaction, '--' as depositor  from shares_redeemed where savings_account_id= '".$save_acct."' and date >= '".$start_date."' and date <= '".$end_date."' order by date asc");
	$x = 0;
  $_SESSION["cumulateive_savings"]=0;
	while ($acc_row = mysql_fetch_array($acc_res))
	{
    
    //$_SESSION["cumulateive_savings"]=$cumm_save;

		$charge_amt = 0;
		$tot_shares = strtolower($acc_row['transaction']) == 'shares' ? intval($acc_row['amount']) : 0 ;
		$tot_savings = ((strtolower($acc_row['transaction']) == 'deposit') || (strtolower($acc_row['transaction']) == 'loan disbursed')) ? intval($acc_row['amount']) : 0 ;
		$tot_fees = strtolower($acc_row['transaction']) == 'monthly_charge' ? intval($acc_row['amount']) : 0 ;
		$tot_with = strtolower($acc_row['transaction']) == 'withdrawal' ? intval($acc_row['amount']) : 0 ;
		$tot_int = strtolower($acc_row['transaction']) == 'save_interest' ? intval($acc_row['amount']) : 0 ;
		$tot_pay = strtolower($acc_row['transaction']) == 'payment' ? intval($acc_row['amount']) : 0 ;
		$tot_inc = strtolower($acc_row['transaction']) == 'other_income' ? intval($acc_row['amount']) : 0 ;
		$tot_inc2 = strtolower($acc_row['transaction']) == 'monthly subscription' ? intval($acc_row['amount']) : 0 ;
		$tot_processing_inc = strtolower($acc_row['transaction']) == 'loan fees income' ? intval($acc_row['amount']) : 0 ;
		$tot_charge_payable = strtolower($acc_row['transaction']) == 'loan fees payable' ? intval($acc_row['amount']) : 0 ;
		$tot_payable = strtolower($acc_row['transaction']) == 'health insurance' ? intval($acc_row['amount']) : 0 ;
		$tot_sharesRedeemed = strtolower($acc_row['transaction']) == 'shares redeemed' ? intval($acc_row['amount']) : 0 ;
		$tot_disbursement = strtolower($acc_row['transaction']) == 'loan disbursement' ? intval($acc_row['amount']) : 0 ;
		$tot_transferFro = strtolower($acc_row['transaction']) == 'non cash source' ? intval($acc_row['amount']) : 0 ;
		$tot_transferTo = strtolower($acc_row['transaction']) == 'non cash destination' ? intval($acc_row['amount']) : 0;
		$shares_charge = strtolower($acc_row['transaction']) == 'share_charge' ? intval($acc_row['amount']) : 0;
		$premium = strtolower($acc_row['transaction']) == 'share premium' ? intval($acc_row['amount']) : 0;

		if((strtolower($acc_row['transaction']) == 'deposit') || (strtolower($acc_row['transaction']) == 'loan disbursed')){
			$charge = mysql_fetch_array(mysql_query("select receipt_no, cheque_no, (flat_value + percent_value) as amount from deposit where id='".$acc_row['id']."'"));
			$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
			if(strtolower($acc_row['transaction']) == 'deposit'){
			$descr="Deposit - RCPT: ".$charge['receipt_no'];
			$descr = ($charge['cheque_no'] <>"") ? $descr." - CHEQ: ".$charge['cheque_no'] : $descr;
			}
			elseif(strtolower($acc_row['transaction']) == 'loan disbursed')
			$descr="Loan Disbursement";
			
		}
		
		if(strtolower($acc_row['transaction']) == 'withdrawal'){
			$charge = mysql_fetch_array(mysql_query("select voucher_no, cheque_no, flat_value + percent_value as amount from withdrawal where id='".$acc_row['id']."'"));
			$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
			$descr="Withdrawal
      - PV: ".$charge['voucher_no'];
			$descr = ($charge['cheque_no'] <>"") ? $descr."
      - CHEQ: ".$charge['cheque_no'] : $descr;
		}
		if(strtolower($acc_row['transaction']) == 'payment'){
	
			$pay = mysql_fetch_array(mysql_query("select receipt_no,  princ_amt + int_amt + penalty + other_charges as amount from payment where id='".$acc_row['id']."'"));
			$pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;
			$descr="Loan Repayment
      - PV: ".$pay['receipt_no'];
			//$resp->alert($tot_pay);
		}
		
		if(strtolower($acc_row['transaction']) == 'monthly subscription'){
  
		      //$inc1 = mysql_fetch_array(mysql_query("select amount , aname from other_income i join accounts a on a.id = i.account_id where i.id='".$acc_row['id']."'"));
		     // $inc_amt1 = ($inc1['amount'] != NULL) ? $inc1['amount'] : 0;
		      $descr="Monthly Subscription";
		      
		 }
		
		if(strtolower($acc_row['transaction']) == 'shares redeemed'){
		      $descr="Shares Redeemed";      
		 }
		
		if(strtolower($acc_row['transaction']) == 'loan disbursement'){
		      $descr="Loan Disbursement";      
		 }

		if(strtolower($acc_row['transaction']) == 'other_income'){
	
			$inc = mysql_fetch_array(mysql_query("select i.receipt_no as receipt_no, i.cheque_no as cheque_no, i.amount as amount, a.name as name from other_income i join accounts a on a.id = i.account_id where i.id='".$acc_row['id']."'"));
			$inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;
			$descr="Deduction ($inc[name])";		
		}
					
		if(strtolower($acc_row['transaction']) == 'loan fees income'){
	
			$inc = mysql_fetch_array(mysql_query("select i.receipt_no as receipt_no, i.cheque_no as cheque_no, i.amount as amount, a.name as name from other_income i join accounts a on a.id = i.account_id where i.id='".$acc_row['id']."'"));
			$inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;
			$descr="DEDUCTION ($inc[name])";
    
		}
		
		if(strtolower($acc_row['transaction']) == 'loan fees payable'){
	
			$inc = mysql_fetch_array(mysql_query("select p.amount as amount, a.name as name from payable p join accounts a on a.id = p.account_id where p.id='".$acc_row['id']."'"));
			$inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;
			$descr="DEDUCTION ($inc[name])";
     
		}
				 
		if(strtolower($acc_row['transaction']) == 'shares'){
	
			$share = mysql_fetch_array(mysql_query("select s.value as amount from shares s where s.id='".$acc_row['id']."'"));
			$share_amt = ($share['amount'] != NULL) ? $share['amount'] : 0;
			$descr="Shares Purchase";
		}
		
		
		if(strtolower($acc_row['transaction']) == 'share_charge'){
	
			$inc = mysql_fetch_array(mysql_query("select i.amount as amount, a.name as name from other_income i join accounts a on a.id = i.account_id where i.id='".$acc_row['id']."'"));
			$inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;
			$descr="Deduction ($inc[name])";
    
		}
		
		if(strtolower($acc_row['transaction']) == 'share premium'){
	
			$inc = mysql_fetch_array(mysql_query("select i.amount as amount, a.name as name from other_funds i join accounts a on a.id = i.account_id where i.id='".$acc_row['id']."'"));
			$inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;
			$descr="Deduction ($inc[name])";
    
		}
		
		if((strtolower($acc_row['transaction']) == 'non cash source')){
			$nonCashDr = mysql_fetch_array(mysql_query("select amount,credit,creditedSavingsAcct,description from non_cash where id='".$acc_row['id']."'"));
			$nonCashAmtDr = ($nonCashDr['amount'] != NULL) ? $nonCashDr['amount'] : 0;
			$descr=$nonCashDr['description'];
			//$descr = ($nonCashDr['credit'] != NULL) ? $descr."to : ".$nonCashDr['credit'] : $descr."to : ".$nonCashDr['creditSavingsAcct'];
		}
		
		if((strtolower($acc_row['transaction']) == 'non cash destination')){
			$nonCashCr = mysql_fetch_array(mysql_query("select amount,debit,debitedSavingsAcct,description from non_cash where id='".$acc_row['id']."'"));
			$nonCashAmtCr = ($nonCashCr['amount'] != NULL) ? $nonCashCr['amount'] : 0;
			$descr=$nonCashCr['description'];
			//$descr = ($nonCashCr['debit'] != NULL) ? $descr."to : ".$nonCashCr['debit'] : $descr."to : ".$nonCashCr['debitedSavingsAcct'];
		}
		
		if($tot_savings != 0){
			$cumm_save += $tot_savings;
			$x++;
			//$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr>
			<td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td>".$descr."</td><td>--</td><td >".number_format($tot_savings, 2)."</td><td>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		
		if($tot_processing_inc > 0){
			$cumm_save -= $tot_processing_inc;
			$x++;
			
			
			$content .= "
		    <tr>
			<td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td>".$descr."</td><td>".number_format($tot_processing_inc, 2)."</td><td>--</td><td>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		
		if($tot_int !=0){
			$cumm_save += $tot_int;
			$x++;
			//$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr>
			<td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td>Interest Earned</td><td>--</td><td>".number_format($tot_int,2)."</td><td>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($tot_shares !=0){
			$cumm_save -= $tot_shares;
			$x++;
			//$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr>
			<td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td>".$descr."</td><td>".number_format($tot_shares, 2)."</td><td>--</td><td>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($tot_with !=0){
			$cumm_save -= $tot_with;
			$x++;
			//$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr>
			<td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td>".$descr."</td><td>".number_format($tot_with, 2)."</td><td>--</td><td>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($tot_pay >0 || $tot_pay <0){
			$cumm_save -= $tot_pay;
			$x++;
			
			//$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr>
			<td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td>".$descr."</td><td>".number_format($tot_pay, 2)."</td><td>--</td><td>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($charge_amt !=0){
			$x++;
			$cumm_save -= $charge_amt;
			//$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr>
			<td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td>Transactional Charge</td><td >".number_format($charge_amt,2)."</td><td>--</td><td>".number_format($cumm_save,2)."</td>
		    </tr>
		    ";
		}
		if($tot_inc !=0){
			$cumm_save -= $tot_inc;
			$x++;
			
			//$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr>
			<td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td>".$descr."</td><td>".number_format($tot_inc, 2)."</td><td>--</td><td>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		
		if($tot_inc2 !=0){
			$cumm_save -= $tot_inc2;
			$x++;
			
			//$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr>
			<td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td>".$descr."</td><td>".number_format($tot_inc2, 2)."</td><td>--</td><td>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		
		if($tot_disbursement != 0){
			$cumm_save += $tot_disbursement;
			$x++;
			
			$content .= "
		    <tr>
		    <td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td>".$descr."</td><td>--</td><td >".number_format($tot_disbursement, 2)."</td><td>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		
		if($tot_payable!=0){
			$x++;
			$cumm_save -= $tot_payable;
			//$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content.= "
		    <tr>
			<td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td>Health Insurance</td><td>".number_format($tot_payable,2)."</td><td>--</td><td>".number_format($cumm_save,2)."</td>
		    </tr>
		    ";
		}
		if($tot_charge_payable!=0){
			$x++;
			$cumm_save -= $tot_charge_payable;
			$content .= "
		    <tr>
			<td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td>".$descr."</td><td>".number_format($tot_charge_payable,2)."</td><td>--</td><td>".number_format($cumm_save,2)."</td>
		    </tr>
		    ";
		}
		
		if($tot_transferTo !=0){
			$x++;
			$cumm_save += $tot_transferTo;
			
			$content .= "
		    <tr>
			<td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td>".$descr."</td><td>--</td><td>".number_format($tot_transferTo,2)."</td><td>".number_format($cumm_save,2)."</td>
		    </tr>
		    ";
		}
		
		if($tot_transferFro !=0){
			$x++;
			$cumm_save -= $tot_transferFro;
			$content .= "
		    <tr>
		    <td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td>".$descr."</td><td>".number_format($tot_transferFro,2)."</td><td>--</td><td>".number_format($cumm_save,2)."</td>
		    </tr>
		    ";
		}
		
		if($tot_sharesRedeemed !=0){
			$x++;
			$cumm_save += $tot_sharesRedeemed;
			$content .= "
		    <tr>
		    <td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td>".$acc_row['transaction']."</td><td>--</td><td>".number_format($tot_sharesRedeemed,2)."</td><td>".number_format($cumm_save,2)."</td>
		    </tr>
		    ";
		}
		
		if($tot_fees !=0){
			$x++;
			$cumm_save -= $tot_fees;
			//$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr>
			<td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td>Monthly Charge</td><td>".number_format($tot_fees,2)."</td><td>--</td><td>".number_format($cumm_save,2)."</td>
		    </tr>
		    ";
		}
		
	       if($shares_charge !=0){
			$x++;
			$cumm_save -= $shares_charge;
			$content .= "
		    <tr>
			<td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td>Shares Purchase Charge</td><td>".number_format($shares_charge,2)."</td><td>--</td><td>".number_format($cumm_save,2)."</td>
		    </tr>
		    ";
		}
		
		if($premium !=0){
			$x++;
			$cumm_save -= $premium;
			$content.="
		    <tr>
		    <td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td>Shares Premium</td><td>".number_format($premium,2)."</td><td>--</td><td>".number_format($cumm_save,2)."</td>
		    </tr>
		    ";
		}

	} 	
	$content .= "</tbody></table></div>";
	//$resp->call('xajax_savings_ledger_form','');
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}
#INIT TRANSFER
function register_transfer_form($mem_no){
	$resp=new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
			
		$content = '
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">ADD TRANSFER</h3>
                                         
                            <div class="panel-body">';
                                                       
 		$content .= '<div class="form-group">
                                    
                                        <div class="col-sm-3">
                                        <label class="control-label">Enter Member No:</label>
                                        <div class="input-group">
                                            
                                            <input type=int name="mem_no1" id="mem_no1" class="form-control">
                                              <span class="input-group-btn">
                                                <button class="btn btn-info" type="button" onclick=\'xajax_register_transfer_form(getElementById("mem_no1").value); return false;\'>Next</button>
                                            </span>
                                        </div></div>
                                        <div class="col-sm-3">
                                            <label class="control-label">OR Select:</label>
                                            <div class="input-group">
                                           <select name="mem_no2" id="mem_no2" class="form-control"><option value="0">Select Member';
		$sth = mysql_query("select * from member order by first_name, last_name");
		while($row = mysql_fetch_array($sth)){
			$content .= "<option value='".$row['mem_no']."'>".$row['first_name']." ".$row['last_name'] ." - ".$row['mem_no'];
		}      
                                        $content.='</select>
                                            <span class="input-group-btn">
                                                <button class="btn btn-info" type="button" onclick=\'xajax_register_transfer_form(getElementById("mem_no2").value); return false;\'>Next</button>
                                            </span>
                                        </div>
                                        </div>
                                    </div>
                                </div></div></form></div></div>';
              
                if($mem_no=='' || $mem_no=='0'){                  
                    $cont ='<font color=red>Enter or select member who wants to make a transfer</font>';
		   
			$resp->assign("status", "innerHTML", $cont);
		}
	 	else{
		
			$sth = mysql_query("select * from member where mem_no= '".$mem_no."'");
		if(@ mysql_numrows($sth)>0){
		
			$row = mysql_fetch_array($sth);
			$branch = mysql_fetch_assoc(mysql_query("select branch_name from branch where branch_no='".$row['branch_id']."'"));
			
			$content .='
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="alert alert-info">
                              </p><table class="borderless" width="100%"><tr><td>BRANCH:</td><td><font color="#00BFFF"><span class="lead">'.$branch['branch_name'].'</span></font></td>
                              <td>MEMBER No:</td><td><font color="#00BFFF"><span class="lead">'.$row['mem_no'].'</span></font></td>
				<td>MEMBER NAME:&nbsp;</td><input type=hidden name="mem_id" value="'.$row['id'].'" id="mem_id"><td><font color="#00BFFF"><span class="lead">'.strtoupper($row['first_name'].' '.$row['last_name']).'</span></font></td></tr></table></p>
                            </div>               
                            <div class="panel-body">';
                        
                        $content.='<div class="col-sm-2"><div class="form-group">
                                  
                                        
                           <img src="photos/'.$row['photo_name'].'?dummy='.time().'" width=90 height=90 alt="photo">
                                     </div> <div class="form-group"> 
<img src="signs/'.$row['sign_name'].'?dummy='.time().'" width=90 height=90 alt="Signature">
                                    </div>
                                </div>';
                        
                                 
                        $content .= '<div class="col-sm-5"><div class="form-group">
                                   
                                        
                                            <label class="control-label">Savings Product:</label>
                                            <select name="memaccount_id" id="memaccount_id" class="form-control" ><option value="">';
				$prod_res = mysql_query("select a.name as name, s.account_id as account_id, a.account_no as account_no, d.id as id from mem_accounts d join savings_product s on d.saveproduct_id=s.id join member m on d.mem_id=m.id join accounts a on s.account_id=a.id where s.type='free' and m.mem_no='".$mem_no."' and d.close_date >NOW() and a.name not in ('Compulsory Shares', 'Compulsory Savings')");
				if(mysql_numrows($prod_res) == 0){
					$resp->alert("This member has no active Savings Account.\n Ensure it is not closed or create them a new one");
					return $resp;
				}
				while($prod = mysql_fetch_array($prod_res))
					$content .= "<option value='".$prod['id']."'>".$prod['account_no'] ." - ".$prod['name'];
				$content .= '</select></div>                                        
                                   
                               ';
                                
                               				
			   $content .='<div class="form-group">
                                    
                                            <label class="control-label">Enter Receiving Account No.:</label><input type=int class="form-control" name="acct_to" id="acct_to" onblur=\'xajax_list_receiver_accts(document.getElementById("acct_to").value); return false;\'></td></tr>';
					$content .= "<tr bgcolor=white><td align=center><div id='receiver1_div' name='receiver1_div'></div></td><td><div id='receiver2_div' name='receiver2_div'></div></td></tr></div>";         	
                                
			$content .='<div class="form-group">
                                   
                                            <label class="control-label">Date:</label>
                                            <input type="text" class="form-control" id="date" name="date" placeholder="'.date('Y-m-d').'" /> 
                                       
                                    </div>
                       ';
			
			 $content.='<div class="form-group">
                                    
                                        <label class="control-label">Amount:</label>
                                           <input onkeyup="format_as_number(this.id)" type=int name="amount" id="amount" class="form-control" >
                                    
                                 </div></div>';
                                 
                         $content.='<div class="col-sm-5"><div class="form-group">
                                     
                                       
                                        <label class="control-label">Voucher No.:</label>
                                           <input type=varchar(10) name="voucher_no" id="voucher_no" class="form-control" >
                                    
                                 </div>';
                                                           
                          $content.='<div class="form-group">
                                        <label class="control-label">Cheque No. (Optional):</label>
                                           <input type=varchar(10) name="cheque_no" id="cheque_no" class="form-control" >
                                    
                                 </div>';                         
                                 
                            $content.='<div class="form-group">
                                        <label class="control-label">Source Bank Account:</label>
                                           <select disabled name="bank_account" id="bank_account" class="form-control"><option value="">';
				//if (strtolower($_SESSION['position']) == 'manager')
					$account_res = mysql_query("select a.name as name, a.account_no as account_no, b.bank as bank, b.id as id from bank_account b join accounts a on b.account_id=a.id where b.id='".CAP_Session::get('account_assigned')."' && a.branch_no like '".$_SESSION['branch_no']."'");
				/*else
					$account_res = mysql_query("select a.name as name, a.account_no as account_no, b.bank as bank, b.id as id from bank_account b join accounts a on b.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."' and b.id in (select bank_account_id from user_account where user_id =".$_SESSION['user_id'].")");*/
				//$account_res = mysql_query("select a.name as name, a.account_no as account_no, b.bank as bank, b.id as id from bank_account b join accounts a on b.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."'");
				while($account = mysql_fetch_array($account_res)){
					$content .= "<option selected value='".$account['id']."'>".$account['account_no'] ." - ".$account['bank']." ".$account['name'];
				}
				$content .= '</select>
                                    </div>
                                 </div></div>';
                                                                                                 
			 $content .= '<div class="panel-footer">                              
                                
                                <input type="button" class="btn  btn-default" value="Back" onclick=\'xajax_register_transfer_form(""); return false;\'>&nbsp;<input type="reset" class="btn btn-default" value="Reset"  onclick=\'xajax_register_transfer_form("'.$mem_no.'")\'>&nbsp;<input type="button" class="btn  btn-primary" value="Transfer"  onclick=\'xajax_register_transfer(getElementById("memaccount_id").value, getElementById("mem_id").value, getElementById("amount").value,getElementById("voucher_no").value, getElementById("cheque_no").value, getElementById("date").value, "'.$row['branch_id'].'",getElementById("trans_to").value,getElementById("bank_account").value); return false;\'>
                            </div></div>
                        </form>
                        <!--/ Form default layout -->
                    </div></div>';
                    $resp->call("createDate","date");
								
		}
		
		else{
		$cont = "<font color=red>No such member exists!</font>";
			$resp->assign("status", "innerHTML", $cont);
			}
	}
	
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
	}
function register_transfer($memaccount_id, $mem_id, $amount, $voucher_no, $cheque_no, $date,$branch_id,$trans_to,$bank_account){
        list($year,$month,$mday) = explode('-', $date);
        $resp = new xajaxResponse();
	$calc = new Date_Calc();
	$amount=str_ireplace(",","",$amount);
	$resp->assign("status", "innerHTML", "");
	$sth = mysql_query("select open_date, date_format(open_date, '%Y') as open_year, date_format(open_date, '%m') as open_month, date_format(open_date, '%d') as open_mday  from mem_accounts where id='".$memaccount_id."'");
	$row = mysql_fetch_array($sth);
	$date = sprintf("%d-%02d-%02d", $year, $month, $mday);
	$date = $date." ".date('H:i:s');
	$charge_res = mysql_query("select s.min_bal as min_bal, s.withdrawal_perc as withdrawal_perc, s.withdrawal_flat as withdrawal_flat, s.grace_period as grace_period from mem_accounts mem join savings_product s on mem.saveproduct_id=s.id where mem.id='".$memaccount_id."'");
	$charge = mysql_fetch_array($charge_res);
	if(isFYClosed(parseFY($year,$month,$mday)))
		$resp->alert("Financial year has been Closed!\nCannot run a transaction in the selected period.");
	elseif($memaccount_id=='' || $mem_id=='' || $amount=='')
		$resp->alert("You may not leave any field blank");
	elseif($voucher_no == "" && $cheque_no == "")
		$resp->alert("You cannot leave both Cheque No. and Voucher No. blank.");
	elseif(!$calc->isValidDate($mday, $month, $year))
		$resp->alert("Tranfer halted unsuccessfully, please enter valid date");
	elseif($calc->isFutureDate($mday, $month, $year))
		$resp->alert("Tranfer halted unsuccessfully! You have entered a future date");
	elseif($calc->dateDiff($mday, $month, $year, $row['open_mday'], $row['open_month'], $row['open_year']) < $charge['open_period'])
		$resp->alert("Tranfer halted unsuccessfully! The date is in a period when withdrawal from this account is not acceptable");		
	elseif($date < $row['open_date'])  //No withdrawal before the account was opened
		$resp->alert("Tranfer halted unsuccessfully! Date entered, is earlier than the when the account was opened");
	else{

		//CHECK THAT THE BANK ACCOUNT WOULD NOT GO BELOW MINIMUM
		$sthb = mysql_query("select * from bank_account where id='".$bank_account."'");
		$rowb = mysql_fetch_array($sthb);
		
		$pledged_res = mysql_query("select sum(amount) as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id where mem.mem_id='".$mem_id."' and s.type='pledged'");
		$pledged = mysql_fetch_array($pledged_res);

		$dfree_res = mysql_query("select sum(d.amount - d.percent_value - d.flat_value)-".$amount." as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id where mem.mem_id='".$mem_id."' and s.type='free'");
		$dfree = mysql_fetch_array($dfree_res);
		$dfree_amt = $dfree['amount'];

		$wfree_res = mysql_query("select sum(w.amount + w.percent_value + w.flat_value) as amount from withdrawal w join mem_accounts mem on w.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id where mem.mem_id='".$mem_id."' and s.type='free'");
		$wfree = mysql_fetch_array($wfree_res);
		$wfree_amt = $wfree['amount'];
		
		//MONTHLY CHARGES 
		$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts mem on c.memaccount_id=mem.id where mem.mem_id='".$mem_id."'");
		$charge = mysql_fetch_array($charge_res);
		$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
		//INTEREST AWARDED
		$int = mysql_fetch_array(mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts mem on i.memaccount_id=mem.id where mem.mem_id='".$mem_id."'"));
		$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;
		//LOAN REPAYMENTS
		$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt + p.penalty + p.other_charges) as amount from payment p join mem_accounts mem on p.mode=mem.id where mem.mem_id='".$mem_id."'");
		$pay = mysql_fetch_array($pay_res);
		$pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;
		//INCOME DEDUCTIONS
		$inc_res = mysql_query("select sum(i.amount) as amount from other_income i join mem_accounts mem on i.mode=mem.id where mem.mem_id='".$mem_id."'");
		$inc = mysql_fetch_array($inc_res);
		$inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;



		$pledged_amt = $pledged['amount'];
		$free_amt = $dfree_amt - $wfree_amt - $charge_amt + $int_amt - $pay_amt - $inc_amt;
		$vouc_res = mysql_query("select voucher_no from withdrawal where voucher_no='".$voucher_no."' union (select voucher_no from expense where voucher_no ='".$voucher_no."')");
//test

$total_saved = 0; $total_with = 0; $total_int = 0; $total_fees = 0;
  $cumm_save = 0;
  
  $drow1 = @mysql_fetch_array(@mysql_query("select sum(amount - flat_value - percent_value) as tot_savings from deposit where bank_account != 0 and memaccount_id = $memaccount_id"));
  $wrow1 = @mysql_fetch_array(@mysql_query("select sum(amount + flat_value + percent_value) as tot_with from withdrawal where memaccount_id = $memaccount_id"));
  $mrow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_fees from monthly_charge where memaccount_id = $memaccount_id"));
  $irow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_int from save_interest where memaccount_id = $memaccount_id"));
  $prow1 = @mysql_fetch_array(@mysql_query("select sum(princ_amt + int_amt + penalty + other_charges) as tot_int from payment where mode = '$memaccount_id'"));
  $incow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_inc from other_income where mode = '$memaccount_id'"));
  
  $shares = @mysql_fetch_array(@mysql_query("select sum(value) as tot_val from shares where mode = '$memaccount_id'"));
  $total_shares = isset($shares['tot_val'])? $shares['tot_val'] : 0 ;

        $total_saved = isset($drow1['tot_savings'])? intval($drow1['tot_savings']) : 0 ;
        $total_fees = isset($mrow1['tot_fees'])? intval($mrow1['tot_fees']) : 0 ;
        $total_with = isset($wrow1['tot_with'])? intval($wrow1['tot_with']) : 0 ;
        $total_int = isset($irow1['tot_int'])? intval($irow1['tot_int']) : 0 ;
    $total_pay = isset($prow1['tot_int'])? intval($prow1['tot_int']) : 0 ;
    $total_inc = isset($incow1['tot_inc'])? intval($incow1['tot_inc']) : 0 ;
        $net_save = ($total_saved + $total_int) - ($total_fees + $total_with + $total_pay + $total_inc + $total_shares);
  $cumm_save += $net_save;
    //end test


		if(@ mysql_num_rows($vouc_res) >0)
			$resp->alert("Withdrawal not registered, voucher already exists");
		elseif($free_amt < $pledged_amt)
			$resp->alert("Cannot withdraw this amount! It is part of the compulsory savings. \n First delete some of this member's loans, deposit=".$dfree_amt.", w=".$wfree_amt);
		elseif(libinc::get_savings_bal($memaccount_id) < $amount)
			$resp->alert("Cannot withdraw this amount! Member has insufficient savings ");
		else{

			$prod_res = mysql_query("select s.withdrawal_perc as withdrawal_perc, s.withdrawal_flat as withdrawal_flat from mem_accounts mem join savings_product s on mem.saveproduct_id=s.id where mem.id='".$memaccount_id."'");
			
			//return $resp;
			$prod = mysql_fetch_array($prod_res);
			$percent_value = ($amount *$prod['withdrawal_perc']) /100;
			$flat_value= $prod['withdrawal_flat'];
			$tag = ($trans_to == 0)? "": "/Trans";
			start_trans();
			if($trans_to >0){
				$percent_value = 0;
				$flat_value= 0;
			}else{
				$percent_value = ($amount *$prod['withdrawal_perc']) /100;
				$flat_value= $prod['withdrawal_flat'];
			}
			mysql_query("insert into withdrawal set memaccount_id='".$memaccount_id."', voucher_no='".$voucher_no.$tag."', trans_date = (select now()), cheque_no='".$cheque_no."', amount='".$amount."', percent_value='".$percent_value."', transfer=".$trans_to.", flat_value='".$flat_value."', date='".$date."',bank_account='".$bank_account."', branch_id=".$branch_id);
		
			 //$resp->alert("on treact.");
						
			///////////////
			$accno =mysql_fetch_assoc(mysql_query("select m.first_name as fname,m.last_name as lname,m.mem_no as memno from member m join mem_accounts ma on m.id=ma.mem_id where ma.id=".$memaccount_id));
			
			$action = "insert into withdrawal set memaccount_id='".$memaccount_id."', voucher_no='".$voucher_no.$tag."', trans_date = (select now()), cheque_no='".$cheque_no."', amount='".$amount."', percent_value='".$percent_value."', transfer=".$trans_to.", flat_value='".$flat_value."', date='".$date."'";
			$msg = "Registered a withdrawal worth:".$amount." to member: ".$accno['fname']." ".$accno['lname']." - ".$accno['memno'];
			log_action($_SESSION['user_id'],$action,$msg);
			if ($trans_to <> 0)
			{
				mysql_query("insert into deposit set memaccount_id='".$trans_to."', amount='".$amount."', trans_date = (select now()), receipt_no='Transfer', cheque_no='', transfer=".$memaccount_id.", date='".$date."', bank_account='".$bank_account."',  branch_id=".$branch_id);
				$action = "insert into deposit set memaccount_id='".$trans_to."', amount='".$amount."', trans_date = (select now()), receipt_no='Transfer', cheque_no='', transfer=".$memaccount_id.", date='".$date."', branch_id=".$branch_id;
				$accnor =mysql_fetch_assoc(mysql_query("select m.first_name,m.last_name,m.mem_no from member m join mem_accounts ma on m.id=ma.mem_id where ma.id=".$trans_to));
				$msg = "Registered a deposit (transfer) worth:".$amount." to member: ".$accnor['first_name']." ".$accnor['last_name']." - ".$accnor['mem_no'];
				log_action($_SESSION['user_id'],$action,$msg);
			}
			//////////////////
			commit();
      $resp->alert("Transfer Successful");
      $resp->call("xajax_register_transfer_form('')");
      //return $resp;
			$content = "<font color=red>Transfer Successful.</font>";			
			//$content .= libinc::sendSMS($accno['telno'], "You have transfered ".$amount." from your ".$branch['branch_name']." Sacco account to ".$accnor['first_name']." ".$accnor['last_name']." - ".$accnor['mem_no']."  your balance is ".checkBalance($accno['mem_no']) );
					
			//$content .= libinc::sendSMS($accnor['telno'], "A transfer of ".$amount." has been made to your to your ".$branch['branch_name']." Sacco account by ".$accno['first_name']." ".$accno['last_name']." your balance is ".checkBalance($accnor['mem_no']) );
		}
	}
	return $resp;
	}

	
function list_transfer($mem_no, $name, $product, $from_date,$to_date,$branch_id){
                      list($from_year,$from_month,$from_mday) = explode('-', $from_date);
                list($to_year,$to_month,$to_mday) = explode('-', $to_date);
		$resp = new xajaxResponse();
		$resp->assign("status", "innerHTML", "");
	$branch=($branch_id=='all'||$branch_id=='')?NULL:'and mem.branch_id='.$branch_id;

$content = '<div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <p><h5 class="semibold text-primary">SEARCH FOR TRANSFERS</h5></p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                   
                                        <div class="col-sm-6">
                                            <label class="control-label">Search By Member No:</label>
                                            <input type="int" id="dmem_no" name="dmem_no" value="All" class="form-control">                                            
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Member Name:</label>
                                            <input type="text" id="dname" value="All" class="form-control">
                                       
                                    </div>
                                </div>
                                
                   <div class="form-group">
                                   
                                        <div class="col-sm-6">
                                            <label class="control-label">Savings Product:</label>
                                            <select name="saveproduct_id", id="saveproduct_id" class="form-control"><option value="">All';
	$sth = mysql_query("select a.name as name, a.account_no as account_no, s.id as id from savings_product s left join accounts a on s.account_id=a.id where a.branch_id like '".$_SESSION['branch_no']."'");
	while($row = @mysql_fetch_array($sth)){
		$content .= "<option value='".$row['id']."'>".$row['account_no']." - ". $row['name'];
	}
	$content .= '</select>                    
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Date range:</label>
                                           <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>
                                    </div>
                                </div>
        
        <div class="form-group">
        
                 
                                        <div class="col-sm-6">
                                            <label class="control-label">Branch:</label>
                                            <span>'.branch_rep($branch_id).'</span>
	                                          
                                        </div>
                                        
                                    </div>
                                </div>';
                                                              
                
	$content .= "<div class='panel-footer'>                              
                                
                                <input type='button' class='btn  btn-primary' value='Search'  onclick=\"xajax_list_transfer(getElementById('dmem_no').value, getElementById('dname').value, getElementById('saveproduct_id').value, getElementById('from_date').value,getElementById('to_date').value,getElementById('branch_id').value); return false;\">
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","from_date");
	$resp->call("createDate","to_date");
	//$resp->assign("display_div", "innerHTML", $content);
	
	if($mem_no=='' && $name=='' && $product==''){
		$cont = "<font color=red>Please enter your search criteria</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}		
		
	else{
		$dname = ($name == 'All') ? "" : $name;
		$dmem_no = ($mem_no == 'All') ? "" : $mem_no;
		$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
		$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);

		$sth = mysql_query("select w.voucher_no as voucher_no, w.cheque_no as cheque_no, w.bank_account as bank_account, w.id as id, w.percent_value as percent_value, w.flat_value as flat_value, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, w.amount as amount, w.date as date, a.account_no as account_no, a.name as name from  withdrawal w join mem_accounts mem on w.memaccount_id=mem.id join  member m on mem.mem_id=m.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where mem.saveproduct_id like '%".$product."%' and w.transfer>0 and (m.mem_no like '%".$dmem_no."%' and (m.first_name like '%".$dname."%' or m.last_name like '%".$dname."%')) and w.date >= '".$from_date."' and w.date <= '".$to_date."' and s.type='free' order by m.first_name, m.last_name, w.trans_date");
		
		$sth2 = mysql_query("select w.voucher_no as voucher_no, w.cheque_no as cheque_no, w.bank_account as bank_account, w.id as id, w.percent_value as percent_value, w.flat_value as flat_value, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, w.amount as amount, w.date as date, a.account_no as account_no, a.name as name from  withdrawal w join mem_accounts mem on w.memaccount_id=mem.id join  member m on mem.mem_id=m.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where mem.saveproduct_id like '%".$product."%' and w.transfer>0 and (m.mem_no like '%".$dmem_no."%' and (m.first_name like '%".$dname."%' or m.last_name like '%".$dname."%')) and w.date >= '".$from_date."' and w.date <= '".$to_date."' and s.type='free' order by m.first_name, m.last_name, w.trans_date");
		if(@mysql_numrows($sth)==0){
				$cont = "<font color=red>No Transfers in the selected search criteria</font>"; 
				$resp->assign("status", "innerHTML", $cont);
		}else{  
			//$max_page = ceil(mysql_num_rows($sth)/$num_rows);
			
			$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                 <p><h5 class="semibold text-primary mt0 mb5">LIST OF TRANSFERS</h5></p>
                                <p></p>
                               </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
 		
			$content .= "<thead><th>#</th><th><b>Member Name</b></th><th><b>MemberNo</b></th><th><b>Amount</b></th><th><b>VoucherNo</b></th><th><b>Cheque No</b></th><th><b>Percent Charge</b></th><th><b>Flat Charge</b></th><th><b>Date</b></th><th><b>Source Account</b></th><th><b>Destination Account</b></th></thead><tbody>";
			$i=$stat+1;
			while($row = @mysql_fetch_array($sth2)){
				//$color= ($i%2 == 0) ? "lightgrey" : "white";
				$bank_res = mysql_query("select a.account_no as account_no, a.name as name from bank_account b join accounts a on b.account_id=a.id where b.id='".$row['bank_account']."'");
				$bank = mysql_fetch_array($bank_res);
				$to = mysql_fetch_array(mysql_query("select m.first_name as first_name, m.last_name as last_name, a.account_no as account_no, m.mem_no as mem_no, a.name as account_name from deposit d join mem_accounts mem on d.memaccount_id=mem.id join member m on mem.mem_id=m.id join savings_product p on mem.saveproduct_id=p.id join accounts a on p.account_id=a.id where d.transfer=(select memaccount_id from withdrawal where id=".$row['id'].") and d.date=(select date from withdrawal where id=".$row['id'].")"));
				$content .= "<tr><td>".$i."</td><td>".$row['first_name']. " ".$row['last_name']."</td><td>".$row['mem_no']."</td><td><a href='javascript:;' onclick=\"xajax_edit_transfer('".$row['id']."', '".$row['amount']."', '".$mem_no."', '".$name."', '".$product."','".$row['voucher_no']."','".$row['cheque_no']."','".$row['bank_account']."','".$row['date']."', '".$from_year."', '".$from_month."', '".$from_mday."', '".$to_year."', '".$to_month."', '".$to_mday."','".$branch_id."')\">".number_format($row['amount'], 0)."</a></td><td>".$row['voucher_no']."</td><td>".$row['cheque_no']."</td><td>".number_format($row['percent_value'], 2)."</td><td>".number_format($row['flat_value'], 0)."</td><td>".$row['date']."</td><td>".$row['account_no']." - ".$row['name']."</td><td>".$to['mem_no']." - ".$to['first_name']." ".$to['last_name']." (".$to['account_no']." - ".$to['account_name'].")</td></tr>";
				$i++;
			}
		}
	}
	$content .= "</tbody></table></div><div align=center>";
	$resp->call("createTableJs");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}
function edit_transfer($withdrawal_id, $former_amt, $mem_no, $name, $product,$voucher_no,$cheque_no,$bank_account,$date, $from_date,$to_date,$branch_id,$num_rows,$stat,$cur_page){
$resp = new xajaxResponse();
if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
$resp->assign("status", "innerHTML", "");
$year = substr($date,0,4);
$month = substr($date,5,2);
$mday=substr($date,8,2);
//$resp->alert($product."----".$bank_account);

$former_mem_no=$mem_no;
if(!$sth = mysql_query("select*from member where id=(select mem_id from mem_accounts where id=(select memaccount_id from withdrawal where id=".$withdrawal_id."))"))
$resp->alert(mysql_error());
		$row = mysql_fetch_array($sth);
$mem_no = $row['mem_no'];
			$branch = mysql_fetch_assoc(mysql_query("select branch_name from branch where branch_no='".$row['branch_id']."'"));			
				
	$content ='<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                              </p><table><tr><td><b>Branch:</b></td><td>'.$branch['branch_name'].'</td></tr>
			<tr><td><b>Member No:</b></td><td>'.$row['mem_no'].'</td></tr>
				<t><td><b>Member Name:&nbsp;</b></td><input type=hidden name="mem_id" value="'.$row['id'].'" id="mem_id"><td>'.$row['first_name'].' '.$row['last_name'].'</td></tr></table></p>
                            </div>               
                            <div class="panel-body">';
                        
                        $content.='<div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12">
                                           <table><tr><td align=right><img src="photos/'.$row['photo_name'].'?dummy='.time().'" width=90 height=90><br>Photo</td><td width=45%>&nbsp;</td><td align=right><img src="signs/'.$row['sign_name'].'?dummy='.time().'" width=90 height=90><br>Signature</td></tr></table>
                                    </div>
                                 </div></div>';
                        
                                 
                        $content .= '<div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label class="control-label">Savings Product:</label>
                                            <select name="memaccount_id" id="memaccount_id" class="form-control" ><option value="">';
				$prod_res = mysql_query("select a.name as name, s.account_id as account_id, a.account_no as account_no, d.id as id from mem_accounts d join savings_product s on d.saveproduct_id=s.id join member m on d.mem_id=m.id join accounts a on s.account_id=a.id where s.type='free' and m.mem_no='".$mem_no."' and d.close_date >NOW() and a.name not in ('Compulsory Shares', 'Compulsory Savings')");
				if(mysql_numrows($prod_res) == 0){
					$resp->alert("This member has no active Savings Account.\n Ensure it is not closed or create them a new one");
					return $resp;
				}
				while($prod = mysql_fetch_array($prod_res))
					$content .= "<option value='".$prod['id']."'>".$prod['account_no'] ." - ".$prod['name'];
					
				$content .= '</select></div>                                        
                                    </div>
                                </div>';
                                				
			       $content .='<div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label class="control-label">Enter Receiving Account No.:</label><input type=text class="form-control" name="acct_to" id="acct_to" onblur=\'xajax_list_receiver_accts(document.getElementById("acct_to").value); return false;\'></td></tr>';
					$content .= "<tr bgcolor=white><td align=center><div id='receiver1_div' name='receiver1_div'></div></td><td><div id='receiver2_div' name='receiver2_div'></div></td></tr>";         	
                                
			$content .='<div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label class="control-label">Date:</label>
                                            <input type="text" class="form-control" id="date" name="date" placeholder="date" />
                                        </div>
                                    </div>
                                </div>';
			
			 $content.='<div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12">
                                        <label class="control-label">Amount:</label>
                                           <input onkeyup="format_as_number(this.id)" type=int name="amount" id="amount" value="'.$former_amt.'" class="form-control" >
                                    </div>
                                 </div></div>';
                                 
                         $content.='<div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12">
                                        <label class="control-label">Voucher No.:</label>
                                           <input type=varchar(10) name="voucher_no" value="'.$voucher_no.'" id="voucher_no" class="form-control" >
                                    </div>
                                 </div></div>';
                                 
                          
                          $content.='<div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12">
                                        <label class="control-label">Cheque No. (Optional):</label>
                                           <input type=varchar(10) name="cheque_no" id="cheque_no" value="'.$cheque_no.'" class="form-control" >
                                    </div>
                                 </div></div>';
                                                                                                                                  
			 $content .= "<div class='panel-footer'>                              
                                
                                <input type='button' class='btn  btn-inverse mb5' value='Back'  onclick=\"xajax_list_transfer('".$former_mem_no."', '".$name."', '".$product."', '".$from_date."','".$to_date."','".$branch_id."')\">&nbsp;&nbsp;<input type='reset' class='btn' value='Delete'  onclick=\"xajax_delete_transfer('".$withdrawal_id."', '".$former_amt."','".$former_mem_no."','".$name."','".$product."','".$voucher_no."','".$cheque_no."','".$bank_account."','".$date."', '".$from_date."','".$to_date."','".$branch_id."');\">&nbsp;<input type='button' class='btn  btn-primary' value='Save'  onclick=\"xajax_update_transfer('".$withdrawal_id."',getElementById('memaccount_id').value, getElementById('mem_id').value, getElementById('amount').value, getElementById('voucher_no').value,getElementById('cheque_no').value, getElementById('date').value,'".$row['branch_id']."',getElementById('trans_to').value); return false;\">
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";	
                    $resp->call("createDate","date");			

	$resp->assign("display_div", "innerHTML", $content);
	return $resp;		

	}


	#updating a transfer

function update_transfer($withdrawal_id,$memaccount_id, $mem_id, $amount, $voucher_no, $cheque_no,  $date,$branch_id,$trans_to){
        list($year,$month,$mday) = explode('-', $date);
//$date = sprintf("%04d-%02d-%02d 00:00:00", $date);
	$res = new xajaxResponse();
	$amount=str_ireplace(",","",$amount);
	
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	if(isFYClosed(parseFY($year,$month,$mday))){
		$resp->alert("Financial year has been Closed!\nCannot run a transaction in the selected period.");
	return $resp;
	}
	$res->confirmCommands(1,"Are you sure you want to edit the transfer?");

	if(mysql_query("update deposit set memaccount_id='".$trans_to."', amount='".$amount."', trans_date = (select now()), receipt_no='Transfer', cheque_no='', transfer=".$memaccount_id.", date='".$date."',  branch_id=".$branch_id ." where transfer=(select memaccount_id from withdrawal where id=".$withdrawal_id.") and date=(select date from withdrawal where id=".$withdrawal_id.")")){

		if(mysql_query("update withdrawal set memaccount_id='".$memaccount_id."', voucher_no='".$voucher_no."', trans_date = (select now()), cheque_no='".$cheque_no."', amount='".$amount."', transfer=".$trans_to.", date='".$date."', branch_id=".$branch_id." where id=".$withdrawal_id))

			$res->assign("status","innerHTML","<font color=red>Update successful</font>");

	}
else {
		$res->alert("Failed to update transfer".mysql_error());
		rollback(); 	
	}

		return $res;

	}
	
	
#deleting a transfer

	function delete_transfer($withdrawal_id, $former_amt, $mem_no, $name, $product,$voucher_no,$cheque_no,$bank_account,$date, $from_date,$to_date,$branch_id,$num_rows,$stat,$cur_page){

		$res = new xajaxResponse();
		if(CAP_Session::get('delete')<>1){
		$resp->alert('You Dont Have Permissions to Delete');
		return $resp;
		}

		$res->confirmCommands(1,"Are you sure you want to delete the transfer?");

	if(mysql_query("delete from deposit where transfer=(select memaccount_id from withdrawal where id=".$withdrawal_id.") and date=(select date from withdrawal where id=".$withdrawal_id.")")){

		if(mysql_query("delete from withdrawal where id=".$withdrawal_id))
$res->call("xajax_list_transfer",$mem_no,$name,$product,$from_date,$to_date,$branch_id,$num_rows,$stat,$cur_page);
else $res->alert("Cannot delete transfer!");
}
else $res->alert("Cannot proceed to delete transfer!\nFailed to delete Deposit\n".mysql_error());
return $res;
}
#END TRANSFER



function setDeductionForm(){ 
$resp = new xajaxResponse();

   $content.='<div class="row-fluid">
            <div class="span12">                                                        
                    <div class="block-fluid">
                     <div class="row-form">
                    <h3 class="panel-title">Set Deduction Amount</h3>
                    </div>';
                    $content.='<div class="row-form"> 		
                               <div class="span3">
                             <span class="top title">Amount Per Month</span>
				 <input class="form-control" onkeyup="format_as_number(this.id)" type="int" id="amount" name="amount" />
				 
                            </div>
                     	<div class="span3">
                            <span class="top title">Date</span>
                         <input class="form-control" type="int" id="date" name="date" />
                            </div> 
                            </div>';                    
                                                                                                      
                               $content .= '
                              <div class="toolbar bottom TAL">
                            <button type="submit" class="btn btn-primary" onclick=\'xajax_saveDeductionAmount(getElementById("amount").value,getElementById("date").value);\'>Save</button>
                       </div>';                                                                			
                    $content .= '</div></div></div>';
    	$resp->call("createDate","date");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function saveDeductionAmount($amount,$date){ 
$resp = new xajaxResponse();

   $qry=mysql_query("insert into savingDeductions set amount=$amount,datePosted='".$date."'");
    if($qry)
    $content="Deduction Amount Saved";
    else 
    $content=mysql_error();
	$resp->assign("status", "innerHTML", $content);
	return $resp;
}
?>
