<?php

$xajax->registerFunction("addBankAcctForm");
$xajax->registerFunction("addBankAcct");
$xajax->registerFunction("listBankAccts");
$xajax->registerFunction("editBankAcctForm");
$xajax->registerFunction("updateBankAcct");
$xajax->registerFunction("updateBankAcct2");
$xajax->registerFunction("deleteBankAcct2");
$xajax->registerFunction("deleteBankAcct");
$xajax->registerFunction("insert_transfer");
$xajax->registerFunction("transfer_cash");
$xajax->registerFunction("delete_transfer");
$xajax->registerFunction("delete2_transfer");
$xajax->registerFunction("list_transfer");
//POSTING BULK
$xajax->registerFunction("add_bulk");
$xajax->registerFunction("insert_bulk");
$xajax->registerFunction("list_bulk");
$xajax->registerFunction("delete_bulk");
$xajax->registerFunction("delete2_bulk");
$xajax->registerFunction("list_ind_post");
$xajax->registerFunction("loadBranches");

function transfer_cash(){
	$resp = new xajaxResponse();
	
	$content ="";
	$content .="<div class='col-md-12'><form method='post' class='panel panel-default'>";
$content .= '<div class="panel-body">
  			  		<div class="panel-heading">
                                 		
                                        <h5 class="semibold text-primary">TRANSFER CASH FROM ONE ACCOUNT TO ANOTHER</h5>
                                      		                                  
                                        </div>';
                                  $sth = mysql_query("select b.id, b.bank, a.account_no, a.name from bank_account b join accounts a on b.account_id=a.id order by a.account_no");      
	 
	                                    $content .= '<div class="form-group"><div class="col-sm-3">
                                            <label class="control-label">Source Account:</label>
                                            <select name="source_id" id="source_id"  class="form-control"><option value="">';
	while($row = mysql_fetch_array($sth)){
		$content .= "<option value='".$row['id']."'>".$row['account_no'] ." - ".$row['bank'] ." ".$row['name'];
	}
	$content .='</select></div>                         
                                            '; 
                                   $sth = mysql_query("select b.id, b.bank, a.account_no, a.name from bank_account b join accounts a on b.account_id=a.id order by a.account_no");                                                   
                                            $content .='
                                            <div class="col-sm-3">
                                            <label class="control-label">Destination Account:</label>
                                            
                                            <select name="dest_id" id="dest_id" class="form-control"><option value="">';
	while($row = mysql_fetch_array($sth)){
		$content .= "<option value='".$row['id']."'>".$row['account_no'] ." - ".$row['bank'] ." ".$row['name'];
	}
	$content .='</select>	
                                            </div>';
                                            $content .= '
                                                <div class="col-sm-3">
                                            <label class="control-label">Amount:</label>
                                       
                                           <input type="int" id="amount" name="amount" class="form-control">
                                            </div>';                                           
                                             $content .= '
                                                <div class="col-sm-3">
                                            <label class="control-label">Date:</label>
                                            <input type="text" class="form-control" id="date" name="date" placeholder="'.date('Y-m-d').'" />
                                            </div></div></div>';
                     
	 $content .= "<div class='panel-footer'><button type='reset' class='btn btn-default' onclick=\"xajax_showSettings()\">Reset</button>
                                            <button type='button' class='btn btn-primary'   onclick=\"xajax_insert_transfer(getElementById('source_id').value, getElementById('dest_id').value, getElementById('amount').value, getElementById('date').value); return false;\">Transfer</button>";
                                            $content .= '</div></form></div>';
                                            $resp->call("createDate","date");
		
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
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

function insert_transfer($source_id, $dest_id, $amount, $date){
	list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	//$resp->assign("status", "innerHTML", "");
	if($source_id=='' || $dest_id=='' || $amount==''){
		$resp->alert("You may not leave any field blank ".$dest_id);
		return $resp;
	}
	if(!$calc->isValidDate($mday, $month, $year)){
		$resp->alert("Cash Tranfer rejected! Please enter valid date");
		return $resp;
	}elseif($calc->isFutureDate($mday, $month, $year)){
		$resp->alert("Cash Transfer rejected! You have entered a future date");
		return $resp;
	}
	$sth = mysql_query("select account_balance - ".$amount." as account_balance, min_balance,account_id from bank_account where id=$source_id");
	$row = mysql_fetch_array($sth);
	$minBal=$row['min_balance'];
	//$date = sprintf("%d-%02d-%02d", $date);

	$date = $date." ".date("h:i:s",time());
	if($dest_id == $source_id){
		$resp->alert("Transfer Failed: \nYou must enter different accounts");
		return $resp;
	}
	
	//libinc::$trialDate=date('Y-m-d');
	$bal=libinc::cashAccountBalance($source_id);
	//libinc::cashAccountBalance($bank_account) - $amount < $row['min_balance']
	
	$amtLeft=$bal-$amount;
	
	
	if($bal < $amount){
		$resp->alert("Transfer Failed: \nInsufficient funds on the source account\n".number_format($bal)." is available");
		return $resp;
	}
	
	if($amtLeft < $minBal){
		$resp->alert("Transfer Failed: \nAccount Balance of the source account would go below the required minimum");
		return $resp;
	}
	/*
	libinc::$trialDate=date('Y-m-d');
	$bal=libinc::cash($row['account_id']);
	
	$amtLeft=$bal-$amount;
	
	
	if($bal < $amount){
		$resp->alert("Transfer Failed: \nInsufficient funds on the source account\n".number_format($bal)." is available");
		return $resp;
	}
	
	if($amtLeft < $row['min_balance']){
		$resp->alert("Transfer Failed: \nAccount Balance of the source account would go below the required minimum");
		return $resp;
	}
	
	if(!mysql_query("update bank_account set account_balance = account_balance - ".$amount." where id=$source_id")){
		$resp->alert("Transfer Failed: \n Could not update source account balance");
		rollback();
		return $resp;
	}
	if(!mysql_query("update bank_account set account_balance = account_balance + ".$amount." where id=$dest_id")){
		$resp->alert("Transfer Failed: \n Could not update Destination account balance");
		rollback();
		return $resp;
	}
	*/
	start_trans();
	if(! mysql_query("insert into cash_transfer set source_id=".$source_id.", dest_id=".$dest_id.", amount=".$amount.", date='".$date."'")){
		$resp->alert("Transfer Failed: \n Could not register the transfer");
		rollback();
		return $resp;
	}
	commit();
	$resp->assign("status", "innerHTML", "<font color=red>Cash transfered successfully!</font>");
	return $resp;
}

function list_transfer($from_date,$to_date){
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	//$from_date = sprintf("%d-%02d-%02d", $from_year, $from_month, $from_mday);
	//$to_date = sprintf("%d-%02d-%02d", $to_year, $to_month, $to_mday);
	
	$content = '
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <p><h5 class="semibold text-primary mt0 mb5">SEARCH FOR CASH TRANSFER</h5></p>
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
		$cont ="<font color=red>No Cash transfers were done in the selected period!</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
	}
	$content .= "</tbody></table></div>";
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//DELETE CASH TRANSFER
function delete_transfer($id, $from_date,$to_date){
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	//CHECK BANK ACCOUNT BALANCES
	$sth = mysql_query("select * from cash_transfer where id=$id");
	$row = mysql_fetch_array($sth);
	$dest = mysql_fetch_array(mysql_query("select account_balance - ".$row['amount']." as account_balance, min_balance from bank_account where id=".$row['dest_id'].""));
	if($dest['account_balance'] < $dest['min_balance']){
		$resp->alert("Cant delete this transfer! \n The Destination Account has insufficient funds");
	}elseif($dest['account_balance'] < $dest['min_balance']){
		$resp->alert("Cant delete this transfer! \n The Destination Account would go below minimum balance");
	}else{
		$resp->confirmCommands(1, "Do you really want to delete?");
		$resp->call('xajax_delete2_transfer', $id, $from_date,$to_date);
	}
	return $resp;
}

function delete2_transfer($id, $from_date,$to_date){
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	$sth = mysql_query("select * from cash_transfer where id=$id");
	$row = mysql_fetch_array($sth);
	start_trans();
	if(! mysql_query("update bank_account set account_balance=account_balance - ".$row['amount']." where id =".$row['dest_id']."")){
		rollback();
		$resp->alert("Cash Transfer not deleted! \n Could not update the Destination bank account");
		return $resp;
	}
	if(! mysql_query("update bank_account set account_balance=account_balance + ".$row['amount']." where id =".$row['source_id']."")){
		rollback();
		$resp->alert("Cash Transfer not deleted! \n Could not update the Source bank account");
		return $resp;
	}
	if(! mysql_query("delete from cash_transfer where id=$id")){
		rollback();
		$resp->alert("Cash Transfer not deleted! \n Could not delete cash transfer");
		return $resp;
	}
	commit();
	$resp->assign("status", "innerHTML", "<FONT COLOR=RED>Cash Transfer deleted</font>");
	$resp->call('xajax_list_transfer', $from_date,$to_date);
	return $resp;
}

//DELETE BULK
/*function delete_bulk($file_ref){
	$resp = new xajaxResponse();
	$resp->confirmCommands(1, "Do you really want to delete?");
	$resp->call('xajax_delete2_bulk', $file_ref);
	return $resp;
}

function delete2_bulk($file_ref){
	$resp = new xajaxResponse();
	
	start_trans();
	if(! mysql_query("delete from bulk_post where file_ref =".$file_ref."")){
		rollback();
		$resp->alert("Bulk Posting not deleted! \n ".mysql_error());
		return $resp;
	}
	mysql_query("delete from deposit where file_ref='".$file_ref."'");
	mysql_query("delete from shares where file_ref='".$file_ref."'");
	mysql_query("delete from other_income where file_ref='".$file_ref."'");
	mysql_query("delete from payment where file_ref='".$file_ref."'");
	commit();
	$resp->assign("status", "innerHTML", "<FONT COLOR=RED>Bulk Post deleted</font>");
	
	return $resp;
}
*/

function addBankAcctForm($prefix)
{       
        $fixed_acc='';
	$resp = new xajaxResponse();
	$fix = @mysql_query("select account_id, id from bank_account order by id desc");
	if($prefix==111)
	//mobile money account
	$level1 = @mysql_query("select id, account_no, name from accounts where account_no between ".$prefix."1 and ".$prefix."9 or account_no =1112 and id NOT in (select account_id from bank_account)");
	elseif($prefix=112)
	$level1 = @mysql_query("select id, account_no, name from accounts where account_no between ".$prefix."1 and ".$prefix."9 and id NOT in (select account_id from bank_account)");
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
	} // end while level1
	
	$content.='<div class="row-fluid">
                    <div class="span12">                                                        
                    <div class="block-fluid">
                     <div class="row-form">';
                     if ($prefix == '112')
                    $content.='<h3 class="panel-title">REGISTER BANK ACCOUNT</h3>';
                    else
                    $content.='<h3 class="panel-title">REGISTER CASH ACCOUNT</h3>';
                    $content.='</div>'; 
                    
               $content.='<div class="row-form">                                                                			                          
                         <div class="span3">
                            <span class="top title">Choose Account:</span>
                           <select id="acct_id" name="acct_id" class="form-control"><option value="">'.$fixed_acc.'</select>            
                            </div>';
                            if ($prefix == '112')
                            $content.='<div class="span3" >
                             <span class="top title">Name of Bank:</span>
				 <input type="int" name="bank_name" id="bank_name" class="form-control">
                            </div> 
                            <div class="span3" >
                             <span class="top title">Bank Account No:</span>
				<input type="int" id="bank_acct_no" name="bank_acct_no"   class="form-control">
                            </div> ';
                            
                             $content.='<div class="span3" >
                             <span class="top title">Minimum Balance:</span>
				 <input type="int" id="min_bal" name="min_bal" class="form-control">
                            </div></div>';
                                   if ($prefix == '112')
                                   
                                             $content .= "<div class='toolbar bottom TAL'><button type='reset' class='btn btn-default' onclick=\"xajax_addBankAcctForm('".$prefix."')\">Reset</button>
                                            <button type='button' class='btn btn-primary' onclick=\"xajax_addBankAcct(getElementById('acct_id').value, getElementById('bank_name').value, getElementById('bank_acct_no').value, getElementById('min_bal').value, '112'); return false;\">Save</button>";
			
			            else    
			                      $content .= "<div class='toolbar bottom TAL'><button type='reset' class='btn btn-default' onclick=\"xajax_addBankAcctForm('".$prefix."')\">Reset</button>
                                            <button type='button' class='btn btn-primary' onclick=\"xajax_addBankAcct(getElementById('acct_id').value, '', '', getElementById('min_bal').value, '111'); return false;\">Save</button>";	
	 $content .= '</div></form></div>';
	 
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function addBankAcct($acc_id, $bank_name, $bank_acct_no, $min_bal, $prefix)
{
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	if ($prefix == '112' && ($acc_id == '' || $bank_name == '' || $bank_acct_no == '' || $min_bal == ''))
	{
		$resp->alert('Please do not leave any field blank.');
	}
	else
	{
		if(mysql_query("insert into bank_account (account_id, bank_account_no, min_balance, account_balance, bank) values ($acc_id, '".$bank_acct_no."', $min_bal, 0, '".$bank_name."')")){
			$resp->assign("status", "innerHTML", "<font color=RED>Bank Account registered successfully.</font><br>");
		}else
			$resp->alert("Bank Account not registered. \n Could not insert the details");
	}
	return $resp;
}

function listBankAccts($prefix)
{
	$resp = new xajaxResponse();
	$date = sprintf("%04d-%02d-%02d", date('Y'), date('m'), date('d'));
	$date = $date." 23:59:59";
	$res = @mysql_query("select ac.id as acc_id, ac.name as acc_name, ba.id as bank_id, ba.account_id, ba.account_balance, ba.min_balance as min_balance, ba.bank_account_no, ba.bank from accounts ac, bank_account ba where ba.account_id = ac.id and ac.account_no like '".$prefix."%'");
	if (@mysql_num_rows($res) > 0)
	{ 
		if ($prefix == '112')
			
			$content = '<div class="col-md-12"><div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">LIST OF BANK AND CASH ACCOUNTS</h3>                                
                            </div>';
		elseif ($prefix == '111')
		$content = '<div class="col-md-12"><div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">LIST OF CASH ACCOUNTS</h3>                                
                            </div>';
                $content .= '<table class="table borderless" id="">';
	
			$content .= "<thead>
			        <th>Bank</th><th>Account Name</th><th>Account No</th><th>Min Balance</th><th>Current Balance</th><th></th>
			    </thead><tbody>
			    ";
		$i=0;
		while ($fxrow = @mysql_fetch_array($res))
		{
			$cashAcctId = $fxrow['bank_id'];
			$accId=$fxrow['acc_id'];
		        $minimumBalance=$fxrow['min_balance'];
		        
			//DEPOSITS
			$dep_res = mysql_query("select sum(amount) as amount from deposit where bank_account='".$cashAcctId."'");
			$dep = mysql_fetch_array($dep_res);
			$dep_amt = ($dep['amount'] == NULL) ? 0 : $dep['amount'];
			//WITHDRAWALS
			$with_res = mysql_query("select sum(amount) as amount from withdrawal where bank_account='".$cashAcctId."'");
			$with = mysql_fetch_array($with_res);
			$with_amt = ($with['amount'] == NULL) ? 0 : $with['amount'];
			//OTHER INCOME
			$other_res = mysql_query("select sum(amount) as amount from other_income where bank_account='".$cashAcctId."' and transaction not in ('Other Charges', 'Loan Processing Fees','Interest','Penalty')");
			$other = mysql_fetch_array($other_res);
			$other_amt = ($other['amount'] == NULL) ? 0 : $other['amount'];
			//$other_amt=0;
			//EXPENSES
			$expense_res = mysql_query("select sum(amount) as amount from expense where bank_account='".$cashAcctId."'");
			$expense = mysql_fetch_array($expense_res);
			$expense_amt = ($expense['amount'] == NULL) ? 0 : $expense['amount'];
			//LOANS PAYABLE
			$loans_payable = mysql_query("select sum(p.amount) as amount from payable p where  p.bank_account='".$cashAcctId."'");
			$loans_payable = mysql_fetch_array($loans_payable);
			$loans_payable = ($loans_payable['amount'] != NULL) ? $loans_payable['amount'] : 0;
			//PAYABLE PAID
			$payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where bank_account='".$cashAcctId."'");
			$payable_paid = mysql_fetch_array($payable_paid_res);
			$payable_paid_amt = ($payable_paid['amount'] == NULL) ? 0 : $payable_paid['amount'];
			//RECEIVALE COLLECTED
			$collected_res = mysql_query("select sum(amount) as amount from collected where bank_account='".$cashAcctId."");
			$collected = mysql_fetch_array($collected_res);
			$collected_amt = ($collected['amount'] == NULL) ? 0 : $collected['amount'];
			//DISBURSED LOANS
			$disb_res = mysql_query("select sum(amount) as amount from disbursed where bank_account='".$cashAcctId."'");
			$disb = mysql_fetch_array($disb_res);
			$disb_amt = ($disb['amount'] == NULL) ? 0 : $disb['amount'];
			//PAYMENTS
			$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt + p.penalty + p.other_charges) as amount from payment p join disbursed d on p.disbursement_id=d.id  where p.bank_account='".$cashAcctId."'");
			$pay = mysql_fetch_array($pay_res);
			$pay_amt = ($pay['amount'] == NULL) ? 0 : $pay['amount'];
											
			//SHARES
			$shares_res = mysql_query("select sum(value) as amount from shares where bank_account='".$cashAcctId."'");
			$shares = mysql_fetch_array($shares_res); 
			$shares_amt = ($shares['amount'] == NULL) ? 0 : $shares['amount'];
			//RECOVERED
			$rec_res = mysql_query("select sum(r.amount) as amount from recovered r where r.bank_account='".$cashAcctId."'");
			$rec = mysql_fetch_array($rec_res);	
			$rec_amt = ($rec['amount'] == NULL) ? 0 : $rec['amount']; 
			//INVESTMENTS 
			$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where bank_account='".$cashAcctId."'");
			$invest = mysql_fetch_array($invest_res);
			$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
			//DIVIDENDS PAID
			$div_res = mysql_query("select sum(total_amount) as total_amount from share_dividends where bank_account='".$cashAcctId."'");
			$div = mysql_fetch_array($div_res);
			$div_amt = ($div['total_amount'] != NULL) ? $div['total_amount'] : 0;
								
			//SOLD INVESTMENTS
			$soldinvest_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where bank_account ='".$cashAcctId."'");
			$soldinvestmt = mysql_fetch_array($soldinvest_res);
			$soldinvest =$soldinvestmt['amount'];
			//FIXED ASSETS 
			$fixed_res = mysql_query("select sum(initial_value) as amount from fixed_asset where bank_account='".$cashAcctId."'");
			$fixed = mysql_fetch_array($fixed_res);
			$fixed_assets=$fixed['amount'];
			$soldasset_res = mysql_query("select sum(amount) as amount from sold_asset where bank_account ='".$cashAcctId."'");
			$soldassets = mysql_fetch_array($soldasset_res);
			$soldasset=$soldassets['amount'];						
			//CASH IMPORTED
			$import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id='".$cashAcctId."'");
			$import = mysql_fetch_array($import_res);
			$import_amt = $import['amount'];

			//CASH EXPORTED
			$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id='".$cashAcctId."'");
			
			$export = mysql_fetch_array($export_res);
			$export_amt = intval($export['amount']);

			//CAPITAL FUNDS
			$fund_res = mysql_query("select sum(amount) as amount from other_funds where bank_account='".$cashAcctId."'");
			$fund = mysql_fetch_array($fund_res);
			$fund_amt = $fund['amount'];
			
			//NON CASH DEBIT
			$debit_res = mysql_query("select sum(amount) as amount from non_cash where debit='".$accId."'");
			$debit = mysql_fetch_array($debit_res);
			$non_cash_debit = $debit['amount'];
			
			//NON CASH CREDIT
			$credit_res = mysql_query("select sum(amount) as amount from non_cash where credit='".$accId."'");
			$credit = mysql_fetch_array($credit_res);
			$non_cash_credit = $credit['amount'];

			$sub2_total =  $collected_amt + $dep_amt+ $loans_payable + $other_amt - $with_amt - $expense_amt + $import_amt - $export_amt - $payable_paid_amt  - $disb_amt + $pay_amt + $shares_amt + $rec_amt + $soldasset + $invest_amt - $invest_amt - $fixed_assets - $div_amt + $fund_amt + $non_cash_debit - $non_cash_credit;


			//$sub2_total =  $collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt + $fund_amt + $non_cash_debit - $non_cash_credit;	
			
			mysql_query("update bank_account set account_balance='".$sub2_total."' where id='".$cashAcctId."'");

			//$color = ($i%2 == 0) ? "lightgrey" : "white";
			$content .= "
				    <tr>
				        <td>".$fxrow['bank']."</td>
						<td>".$fxrow['acc_name']."</td>
						<td>".$fxrow['bank_account_no']."</td>
						<td>".number_format($fxrow['min_balance'], 2)."</td>
						<td>".number_format($sub2_total, 2)."</td>
						<td>
					
						 </td>
				    </tr>
				    ";		
		}
		$content .= "</tbody></table></div>";
	}
	else{
		$cont = "<div><font color=red>No Accounts Registered Yet.</font></div>";
		$resp->assign("status", "innerHTML", $cont);
	}
	$resp->call("createTableJs");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function editBankAcctForm($bank_id, $prefix)
{       $content ="";
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$res = @mysql_query("select  ac.name, ba.* from  bank_account ba join accounts ac on ac.id = ba.account_id where ba.id = $bank_id");
	if (@mysql_num_rows($res) > 0)
	{
		$row = @mysql_fetch_array($res);

			    $content .="<form method='post' class='panel form-horizontal form-bordered'>";
$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h4 class="semibold text-primary mt0 mb5">EDIT BANK/CASH ACCOUNT: "'.$row['name'].'"</h4>
                                               		 <p class="text-default nm"></p>
                                           	 </div>
                                        </div>';
		if ($prefix == '1212')
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Name of Bank:</label>
                                            <div class="col-sm-6">
                                           <input type="text" name="bank_name" id="bank_name" value="'.$row['bank'].'" class="form-control">
                                            </div></div>
                                            <div class="form-group">
                                            <label class="col-sm-3 control-label">Bank Account No:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" id="bank_acct_no" name="bank_acct_no" value="'.$row['bank_account_no'].'" class="form-control">
                                            </div></div>';
                                           
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Minimum Balance:</label>
                                            <div class="col-sm-6">
                                           <input type="text" id="min_bal" name="min_bal" value="'.$row['min_balance'].'" class="form-control">
                                            </div></div>';
                                   if ($prefix == '1212')
                                   
                                             $content .= "<div class='panel-footer'><button type='reset' class='btn btn-default'  onclick=\"xajax_listBankAccts('".$prefix."')\">Back</button>
                                            <button type='button' class='btn btn-primary' onclick=\"xajax_updateBankAcct2('".$row['id']."', '".$row['name']."', document.getElementById('bank_name').value, document.getElementById('bank_acct_no').value, document.getElementById('min_bal').value, $prefix); return false;\">Save</button>";
			
			           elseif ($prefix == '1211')    
			                      $content .= "<div class='panel-footer'><button type='reset' class='btn btn-default'  onclick=\"xajax_listBankAccts('".$prefix."')\">Back</button>
                                            <button type='button' class='btn btn-primary' onclick=\"xajax_updateBankAcct2('".$row['id']."', '".$row['name']."', '', '', getElementById('min_bal').value, $prefix); return false;\">Save</button>"; 
                                                     
	                           else{
		                 $cont='<div><font color=red><b>ERROR: Account not found!</b></font></div>';
		                $resp->assign("status", "innerHTML", $cont);
		                }
		                $content .= '</div></form></div>';
		       }
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function updateBankAcct2($bank_id, $acc_name, $bank_name, $bank_acct_no, $min_bal, $prefix)
{
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	if ($prefix == '1212' && ($bank_id == '' || $bank_name == '' || $bank_acct_no == '' || $min_bal == ''))
		$resp->alert('Please do not leave any fields blank.');
	else
	{
		$resp->confirmCommands(1, "Do you really want to update?");
		$resp->call('xajax_updateBankAcct', $bank_id, $acc_name, $bank_name, $bank_acct_no, $min_bal, $prefix);
	}
	return $resp;
}

function updateBankAcct($bank_id, $acc_name, $bank_name, $bank_acct_no, $min_bal, $prefix)
{
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$res = @mysql_query("update bank_account set bank='".$bank_name."', bank_account_no='".$bank_acct_no."', min_balance='".$min_bal."' where id=$bank_id");
	if (@mysql_affected_rows() > 0){
		
		$cont = "<font color=green><b>$acc_name updated successfully.</b></font><br>";
		$resp->assign("status", "innerHTML", $cont);
		
		$action = "update bank_account set bank='".$bank_name."', bank_account_no='".$bank_acct_no."', min_balance='".$min_bal."' where id=$bank_id";
		mysql_query("insert into logs set user_id='".$_SESSION['user_id']."', time=NOW(),  action='".$_SESSION['name'].":  ".mysql_escape_string($action)."'");
	}else{
		$cont = "<font color=red><b>ERROR: $acc_name not updated!</b></font>";
		$resp->assign("status", "innerHTML", $cont);
	return $resp;
	}
	$resp->assign("status", "innerHTML", $content);
	$resp->call('xajax_listBankAccts', $prefix);
	return $resp;
}

function deleteBankAcct2($bank_id, $prefix)
{
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	$depr = @mysql_query("select bank_account from deposit where bank_account='".$bank_id."' union select bank_account from withdrawal where bank_account='".$bank_id."' union select bank_account from collected where bank_account='".$bank_id."' union select bank_account from disbursed where bank_account='".$bank_id."' union select bank_account from expense where bank_account='".$bank_id."' union select bank_account from fixed_asset where bank_account='".$bank_id."' union select bank_account from investments where bank_account='".$bank_id."' union select bank_account from other_income where bank_account='".$bank_id."' union select bank_account from  payable_paid where bank_account='".$bank_id."' union select bank_account from share_dividends where bank_account='".$bank_id."' union select bank_account from shares where bank_account='".$bank_id."' union select bank_account from sold_asset where bank_account='".$bank_id."' union select bank_account from sold_invest where bank_account='".$bank_id."'");

	if (mysql_num_rows($depr) > 0){
		$resp->alert("Cannot delete bank account: The account has some transactions");
	}else{
		$resp->confirmCommands(1, "Do you really want to delete?");
		$resp->call('xajax_deleteBankAcct', $bank_id, $prefix);
	}
	return $resp;
}


function deleteBankAcct($bank_id, $prefix)
{
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	$res = @mysql_query("delete from bank_account where id = $bank_id");
	if (@mysql_affected_rows() > 0){
		$cont = "<font color=green><b>Bank Account deleted successfully.</b></font><br>";
		$resp->assign("status", "innerHTML", $cont);
		$action = "delete from bank_account where id = $bank_id";
		mysql_query("insert into logs set user_id='".$_SESSION['user_id']."', time=NOW(),  action='".$_SESSION['name'].":  ".mysql_escape_string($action)."'");
		
	}
	else {
		$cont = "<font color=red><b>Error: Failed to delete Bank Account. </b></font>";
	$resp->assign("status", "innerHTML", $content);
	}
	$resp->call('xajax_listBankAccts', $prefix);
	return $resp;
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
                                           <input type="numeric" class="form-control" name="contact" id="contact">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">File Ref:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" name="rcpt_no" id="rcpt_no"  class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Cheque No (Optional):</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" name="cheque_no" id="cheque_no" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Date:</label>
                                            <div class="col-sm-6">
                                           <span><input type="text" class="form-control" name="date" id="date" placeholder="'.date('Y-m-d').'"></span>
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


//UNIQUE FILE REF
function unique_ref($file_ref, $table='')
{
/*
	Check if a given rcpt_no has bn registered before.
	Tables currently containing receipt_no: payment, collected, deposit, shares, other_income, recovered
	returns false if rcpt_no has already bn registered or is an empty string
*/
	if ($file_ref == '')
		return false;
	elseif ($table == '')
	{
		$res = @mysql_query("SELECT file_ref FROM payment where file_ref='".$file_ref."' UNION SELECT file_ref FROM deposit where file_ref='".$file_ref."'  UNION SELECT file_ref from shares where file_ref='".$file_ref."' UNION SELECT file_ref from other_income where file_ref='".$file_ref."'");
	if (@mysql_num_rows($res) > 0)
		return false;
	else
		return true;
	}
			
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
	$sth = mysql_query("select 'Shares' as type, s.receipt_no as receipt_no, m.mem_no as mem_no, s.value as amount from shares s join member m on s.mem_id=m.id where s.file_ref='".$file_ref."' UNION select 'Savings' as type, d.receipt_no as receipt_no, m.mem_no as mem_no, d.amount as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join member m on mem.mem_id=m.id where d.file_ref='".$file_ref."' UNION select 'Loan Repayment' as type, p.receipt_no as receipt_no, m.mem_no as mem_no, (princ_amt + int_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id where p.file_ref='".$file_ref."' UNION select 'Income Contribution' as type, i.receipt_no as receipt_no, i.contact as mem_no, i.amount as amount from other_income i where i.file_ref='".$file_ref."' order by mem_no");
	
	$grand =0;
	$content = '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                 <p><h5 class="semibold text-primary mt0 mb5">SHARING DONE</h5></p>
                                
                               </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';           
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
			
			$content .= "<tr><td>".$row['date']."</td><td>".$row['file_ref']."</td><td>".number_format($total_amt, 2)."</td><td>".$row['contact']."</td><td> <a href='javascript:;' onclick=\"xajax_list_ind_post('".$row['file_ref']."')\">View Individual Posts</a></td></tr>";
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

?>
