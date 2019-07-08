<?php
/*require_once("common.php");
//require_once('util.php');
require_once('./xajax_0.2.4/xajax.inc.php');
$xajax = new xajax();
$xajax->errorHandleron();*/

$xajax->registerFunction("addIncomeForm");
$xajax->registerFunction("addIncomeForm1");
$xajax->registerFunction("addIncome");
$xajax->registerFunction("listIncome");
$xajax->registerFunction("editIncomeForm");
$xajax->registerFunction("updateIncome");
$xajax->registerFunction("updateIncome2");
$xajax->registerFunction("deleteIncome2");
$xajax->registerFunction("deleteIncome");
$xajax->registerFunction("unique_rcpt");
$xajax->registerFunction("incomesReg");
$xajax->registerFunction("modePayment");
$xajax->registerFunction("verifyMember");
$xajax->registerFunction("childAccounts");
$xajax->registerFunction("saveIncomeNew");
$xajax->registerFunction("confirmIncome");

function addIncomeForm($prefix, $action = 'cash')
{       
        $accts="";
        $mems="";
        $fixed_acc="";
        $modes="";
        $content="";
        $type="";
        $rcts ="";
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	$fix = @mysql_query("select account_id, id, account_no from other_income order by account_no");
	//$level1 = @mysql_query("select id, account_no, name from accounts where account_no like '".$prefix."%' and account_no >= ".$prefix."1 and account_no <= ".$prefix."9 and account_no not in ('4121', '4131', '4221', '4222', '4223', '4111', '4112', '4113', '4114')");
	$level1 = @mysql_query("select id, account_no, name from accounts where account_no like '".$prefix."%' and account_no >= ".$prefix."1 and account_no <= ".$prefix."9");
	while ($level1row = @mysql_fetch_array($level1))
	{
		$level2 = @mysql_query("select id, account_no, name from accounts where account_no like '".$level1row['account_no']."%' and account_no >= ".$level1row['account_no']."01 and account_no <= ".$level1row['account_no']."99");		
		if (@mysql_num_rows($level2) > 0) // there's a level2 account
		{
			while ($level2row = @mysql_fetch_array($level2))
			{
				$level3 = @mysql_query("select id, account_no, name from accounts where account_no like '".$level2row['account_no']."%' and account_no >= ".$level2row['account_no']."01 and account_no <= ".$level2row['account_no']."99 and id NOT in (select account_id from fixed_asset)");	
				if (@mysql_num_rows($level3) > 0) // there's a level3 account
				{
					while ($level3row = @mysql_fetch_array($level3))
					{
						$fixed_acc .= "<option value='".$level3row['id']."'>".$level3row['account_no']." -". $level3row['name']."</option>";
					}
				}
				else /* Plain level2 accounts */
				{
					if (@mysql_num_rows($fix) > 0)
					{
						while ($fixrow = @mysql_fetch_array($fix))
						{
							if ($fixrow[account_id] != $level2row['id'])
								$fixed_acc .="<option value='".$level2row['id']."'>".$level2row['account_no']." -".$level2row['name']."</option>";
						}
						@mysql_data_seek($fix, 0);
					}
					else
						$fixed_acc .= "<option value='".$level2row['id']."'>".$level2row['name']."</option>";
				}
			} // end while level2
		}
		else /* Plain level1 accounts */
		{
			if (@mysql_num_rows($fix) > 0)
			{
				while ($fixrow = @mysql_fetch_array($fix))
				{
					 if ($fixrow[account_id] != $level1row['id'])
					 	$fixed_acc .= "<option value='".$level1row['id']."'>".$level1row['account_no']." -". $level1row['name']."</option>";
				}
				@mysql_data_seek($fix, 0);
			}
			else
				$fixed_acc .= "<option value='".$level1row['id']."'>".$level1row['account_no']." -". $level1row['name']."</option>";
		}
	} // end while level1
	
	if ($action == 'offset')
	{	$rcts .= "Not Applicable";
		$mem_res = mysql_query("select m.first_name, m.last_name, m.mem_no, ma.id as memacc_id, a.name from member m join mem_accounts ma on m.id = ma.mem_id join savings_product p on ma.saveproduct_id = p.id join accounts a on p.account_id = a.id where lower(a.name) <> 'compulsory savings' and lower(a.name) <> 'compulsory shares' order by m.first_name, m.last_name");
		$mems .= "<option value=''>&nbsp;</option>";
		while ($mem_row = mysql_fetch_array($mem_res))
		{
			$mems .= "<option value='".$mem_row['memacc_id']."'>".$mem_row['first_name']."". $mem_row['last_name']." -".$mem_row['mem_no']." -"."(".$mem_row['name'].")"."</option>";
		}
		
		$accts .= "<option value='0'>Not Applicable</option>";
		
	}
	else
	{
		$mems .= "<option value='0'>Not Applicable</option>";
		//if (strtolower($_SESSION['position']) == 'manager')
			$acc = @mysql_query("select ba.id, ac.name, ac.account_no, ba.bank from accounts ac join bank_account ba on ac.id = ba.account_id");
		/*else
			$acc = @mysql_query("select ba.id, ac.name, ac.account_no, ba.bank from accounts ac join bank_account ba on ac.id = ba.account_id where ba.id in (select bank_account_id from user_account where user_id =".$_SESSION['user_id'].")");*/
		$accts .= "<option value=''>&nbsp;</option>";
		while ($accrow = @mysql_fetch_array($acc))
		{
			$accts .= "<option value='".$accrow['id']."'>".$accrow['account_no']." - ".$accrow['bank']."".$accrow['name']."</option>";
		}
	}

	$modes .= $action == 'cash'? "<option value='cash' selected>Cash/Cheque</option>" : "<option value='cash' selected>Cash/Cheque</option>";
	$modes .= $action == 'offset'? "<option value='offset' selected>Offset from Member's Savings</option>" : "<option value='offset'>Offset from Member's Savings</option>";

	$content .="<div class='col-md-12'><form method='post' class='panel form-horizontal panel-default'>";
$content .= '
  			  		<div class="panel-heading">
                                 		
                                                	<p><h5 class="semibold text-primary mt0 mb5">REGISTER INCOME</h5></p>                                             		
                                           	 
                                        </div><div class="panel-body">';
	$form_res = mysql_query("select * from accounts where account_no ='".$prefix."'");
	$form = mysql_fetch_array($form_res); 
	$sth = mysql_query("select account_no, name from accounts where account_no >='411' and account_no <= '499'");
	while($row = mysql_fetch_array($sth)){
		$type .= "<option value='".$row['account_no']."'>".$row['account_no']." - ".strtoupper($row['name']);
	}
	 $content .='<div class="form-group">
                                            
                                            <label class="col-sm-3 control-label">Branch:</label>
                                            <div class="col-sm-6">
                                            <span>'.branch().'</span>
                                            </div></div>';
                                            $content .= '<div class="form-group">
                                           
                                            <label class="col-sm-3 control-label">Select Payment Mode:</label>
                                            <div class="col-sm-6">
                                           <select name="mode" id="mode" onchange=\'xajax_addIncomeForm("'.$prefix.'", getElementById("mode").value); return false;\' class="form-control">'.$modes.'</select>
                                            </div></div>';                                         
                                             $content .= '<div class="form-group">
                                              
                                            <label class="col-sm-3 control-label">Main account:</label>
                                            <div class="col-sm-6">
                                           <select id="ptype" name="ptype" onchange=\'xajax_addIncomeForm(getElementById("ptype").value, "'.$action.'"); return false;\' class="form-control"><option value="'.$prefix.'">'.$prefix.' - '.$form['name'].''.$type.'</select>
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Select Account:</label>
                                            <div class="col-sm-6">
                                           <select id="acc_id" name="acc_id" class="form-control">'.$fixed_acc.'</select>
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Select Dest Bank Account:</label>
                                            <div class="col-sm-6">
                                           <select name="bank_acct" id="bank_acct" class="form-control">'.$accts.'</select>  
                                            </div></div>';
                                        
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Select Paying Member:</label>
                                            <div class="col-sm-6">
                                           <select name="mem_acc_id" id="mem_acc_id" class="form-control">'.$mems.'</select> 
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Contact Person:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" id="contact" name="contact" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Amount Received:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" onkeyup="format_as_number(this.id)" id="amount" name="amount"  class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Receipt No:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" id="rcpt_no" name="rcpt_no" value="'.$rcts.'" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Cheque No (Optional):</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" id="cheque_no" name="cheque_no" class="form-control">
                                            </div></div>';                                            
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Date:</label>
                                            <div class="col-sm-6">
                                          <input type="text" class="form-control" id="date" name="date" placeholder="date" />
                                            </div></div>';
                                            
                                            $content .= "<div class='panel-footer'><button type='reset' class='btn btn-default'  onclick=\"xajax_addIncomeForm('".$prefix."', 'cash')\">Reset</button>
                                            <button type='button' class='btn btn-primary'   onclick=\"xajax_addIncome(document.getElementById('acc_id').value, document.getElementById('amount').value, getElementById('bank_acct').value, getElementById('contact').value, getElementById('rcpt_no').value, getElementById('cheque_no').value, getElementById('mem_acc_id').value, document.getElementById('date').value,getElementById('branch_id').value); return false;\">Save</button>";
                                            $content .= '</div></form></div>';
                                            $resp->call("createDate","date");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function addIncome($acc_id, $amount_paid, $bank_acct, $contact, $rcpt_no, $cheque_no, $mem_acc_id, $date,$branch_id)
{       list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$amount_paid=str_ireplace(",","",$amount_paid);
	
	$date = $date." ".date('H:i:s');
	if ($acc_id == '' || $amount_paid == '' || $bank_acct == '')
	{
		$resp->alert('Please do not leave any field blank.');
		return $resp;
	}
	elseif ($rcpt_no == '' && $cheque_no == '')
	{
		$resp->alert('Please enter either a cheque No or Receipt No');
		return $resp;
	}
	elseif (!$calc->isValidDate($mday, $month, $year))
	{
		$resp->alert('Invalid Date. Most likely wrong date for Month: Feb.');
		return $resp;
	}
	elseif ($calc->isFutureDate($mday, $month, $year))
	{
		$resp->alert('Date can not be a future date.');
	}
	elseif (!unique_rcpt($rcpt_no, '', ''))
	{
		$resp->alert("This receipt No has already been used.");
		return $resp;
	}
	else
	{	if($rcpt_no="Not Applicable")
	                 $rcpt_no='';
		$mode = $mem_acc_id == 0 ? 'cash' : $mem_acc_id;
		start_trans();
		
		//$resp->alert(mysql_error());
		if ($mode == 'cash')
		{
			//if (@mysql_affected_rows() > 0)
				if(mysql_query("update bank_account set account_balance=account_balance +".$amount_paid." where id='".$bank_acct."'")){
				///////////
				$accno = mysql_fetch_assoc(mysql_query("select account_no,name from accounts where id=".$acc_id));
				$action = "insert into other_income (account_id, amount, date, bank_account, contact, receipt_no, cheque_no, mode) values ('$acc_id', $amount_paid, '".$date."', '$bank_acct', '$contact', '$rcpt_no', '$cheque_no', '$mode')";
				$msg = "Registered a sum of ".$amount_paid." into AC/NO. ".$accno['account_no']." - ".$accno['name'];
				log_action($_SESSION['user_id'],$action,$msg);
				$content .= $SESSION['user_id'];
				/////////
					$resp->alert("Income registered successfully.");
					commit();
				}else{
					$resp->alert("ERROR: Could not update bank account".mysql_error());
					rollback();
				}
			
		}
		else
		{
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
				//LOAN REPAYMENTS
				$pay_res = mysql_query("select sum(princ_amt + int_amt) as amount from payment where mode='".$mode."'");
				$pay = mysql_fetch_array($pay_res);
				$pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;
				//INCOME DEDUCTIONS
				$inc_res = mysql_query("select sum(amount) as amount from other_income where mode='".$mode."'");
				$inc = mysql_fetch_array($inc_res);
				$inc_amount = ($inc['amount'] != NULL) ? $inc['amount'] : 0;

				//SHARES DEDUCTIONS
				$shares_res = mysql_query("select sum(value) as amount from shares where mode='".$mode."'");
				$shares = mysql_fetch_array($shares_res);
				$shares_amount = ($shares['amount'] != NULL) ? $shares['amount'] : 0;

				$balance = $dep_amt + $int_amt  - $with_amt - $charge_amt - $pay_amt - $inc_amount - $shares_amt;
				//IF INSUFFICIENT SAVINGS
				if($balance < $amount_paid){
					$resp->alert("Payment not registered! \n Insufficient savings to pay/offset the amount: Savings balance is ".$balance. " yet required is ".$amount_paid);
					rollback();
					return $resp;
				}
				
		}
		$res = @mysql_query("insert into other_income (account_id, amount, date, bank_account, contact, receipt_no, cheque_no, mode,branch_id) values ('$acc_id', $amount_paid, '".$date."', '$bank_acct', '$contact', '$rcpt_no', '$cheque_no', '$mode',".$branch_id.")");
		if(mysql_affected_rows() >0){
			$resp->alert("Income registered successfully.");
			commit();
		}else{
			$resp->alert("Error: Income not registered.");
			rollback();
		}
	}
	//$resp->assign("status", "innerHTML", $content);
	return $resp;
}

function listIncome($account, $contact, $from_date,$to_date, $branch_id)
{        
         list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	$branch = ($branch_id=='all'||$branch_id=='')?NULL:"and r.branch_id=".$branch_id;
	$content = '
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <p><h5 class="semibold text-primary mt0 mb5">LIST OF OTHER INCOME</h5></p>                                      
                            <div class="panel-body">                           
                      <div class="form-group">
                                   
                                        <div class="col-sm-3">
                                            <label class="control-label">Account:</label>
                                            <select id="account" class="form-control"><option value="">All';
	//$sth = mysql_query("select distinct id as id, account_no, name from accounts where id in (select account_id from other_income)");
	$sth = mysql_query("select distinct id as id, account_no, name from accounts where account_no like '4%'");
	while($row = mysql_fetch_array($sth)){
		$content .= "<option value='".$row['id']."'>".$row['account_no'] ." - ".$row['name'];
	}
	$content .='</select>                                         
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label">Contact Person:</label>
                                            <input type="text" id="contact" class="form-control">
                                        </div>
                                                                
                                       <div class="col-sm-3">
                                            <label class="control-label">Branch:</label>
                                            <span>'.branch_rep($branch_id).'</span>
	                                          
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label">Date range:</label>
                                           <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div></div>
                                    </div>
                                </div>';
                                                                                        
	$content .= "<div class='panel-footer'>                              
                                
                                <input type='button' class='btn  btn-primary' value='Search'  onclick=\"xajax_listIncome(getElementById('account').value, getElementById('contact').value, getElementById('from_date').value,document.getElementById('to_date').value,getElementById('branch_id').value)\">
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                     $resp->call("createDate","from_date");
	             $resp->call("createDate","to_date");
                    //$resp->assign("display_div", "innerHTML", $content);
		
	if($from_date =='' || $to_date ==''){
		$cont ="<font color=red>Select the period for the income!</font>";
		$resp->assign("status", "innerHTML", $cont);
		
	}
	else{
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59",  $to_year, $to_month, $to_mday);
	
	$account_no=libinc::getItemById("accounts",$account,"id","account_no");
	$account_Name=libinc::getItemById("accounts",$account,"id","name");
	if ($account == '')
	{
		$res = @mysql_query("select r.bank_account, ac.id as acc_id, ac.name as acc_name, ac.account_no as account_no, r.id as rid, r.amount as amount, r.contact as contact, r.date as mdate, r.contact as contact, r.receipt_no, r.cheque_no, r.mode from accounts ac right join other_income r on r.account_id = ac.id where r.date>= '".$from_date."' and r.date <= '".$to_date."' and r.contact like '%".$contact."%' ".$branch." order by r.date desc");
	}
	elseif($account_no == '4111'){ //interest on loans
		
		$res = @mysql_query("select r.int_amt as amount,r.mode as mode,r.bank_account as bank_account,r.date as mdate,r.receipt_no as receipt_no from payment r where r.date >= '".$from_date."' and r.date <= '".$to_date."' ".$branch." and r.int_amt > 0 order by r.date desc");
		
	}
	
	elseif($_SESSION['companyId']==135 && $account_no == '4123'){ //withdrawal charges
	
	$res= mysql_query("select r.flat_value as amount,r.memaccount_id as mode, r.bank_account as bank_account,r.date as mdate,r.voucher_no as receipt_no from withdrawal r where r.date >= '".$from_date."' and r.date <= '".$to_date."' ".$branch." order by r.date desc");
	
         }   
	else{
		$res = @mysql_query("select r.bank_account, ac.id as acc_id, ac.name as acc_name, ac.account_no as account_no, r.id as rid, r.amount as amount, r.contact as contact, r.date as mdate, r.contact as contact, r.receipt_no, r.cheque_no, r.mode from accounts ac right join other_income r on r.account_id = ac.id where r.date>= '".$from_date."' and r.date <= '".$to_date."' and r.account_id='".$account."' and r.contact like '%".$contact."%' ".$branch." order by r.date desc");
	}
		
	if (@mysql_num_rows($res) > 0)
	{
		 $content.="<input type='button' class='pull-right' href=\"#\" onClick =\"$('#table-incomes').tableExport({type:'excel',escape:'false'});\" value='Excel'>   
   <input type='button' onclick=\"generate({mime:'jpeg',table_id:'table-incomes', filename:'income.pdf', title:'".$account_no."-".$account_Name."', subtitle:' From ".$from_date." - ".$to_date."', logo:''})\" class='pull-right' value='PDF'><br><br>";
			   $content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">LIST OF INCOMES</h3>
                            </div>';
 		$content .= '<table class="table-hover borderless" id="table-incomes" width="100%">
			        <thead><th>Date</th><th>Account</th><th>Amount</th><th>Receipt No./Voucher No.</th><th>Payment Mode</th><th>Contact Person</th><th>Cash/Bank Account</th><thead><tbody>';
			   
		$i=0;
		$balance=0;
		while ($fxrow = @mysql_fetch_array($res))
		{
		        $acctNo=$fxrow['account_no'];
			$acctName=$fxrow['acc_name'];
			$amount=$fxrow['amount'];
			$receipt=$fxrow['receipt_no']." ".$fxrow['cheque_no'];
		
			if($fxrow['mode'] <> 'cash'){
			$memName = mysql_query("select m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, ma.id as memacc_id, a.name as name from member m join mem_accounts ma on m.id = ma.mem_id join savings_product p on ma.saveproduct_id = p.id join accounts a on p.account_id = a.id where ma.id =".$fxrow['mode']." ");
			$rows = mysql_fetch_array($memName);
			$savings= $rows['first_name']."". $rows['last_name']."-".$rows['mem_no']."<br>".$rows['name'];
			$receipt='Offset from Savings';
			}
			$mode = ($fxrow['mode'] == 'cash') ? $fxrow['mode'] : $savings;
			
			$bank = mysql_fetch_array(mysql_query("select b.bank as bank, a.name as name, a.account_no from bank_account b join accounts a on b.account_id=a.id where b.id='".$fxrow['bank_account']."'"));
			
			if($account_no == '4111' || $account_no == '4123'){
			
			$res1 = @mysql_query("select ac.id as acc_id, ac.name as acc_name,ac.account_no as account_no from accounts ac where ac.id='".$account."'");
			$accrow = mysql_fetch_array($res1);
			$acctNo=$accrow['account_no'];
			$acctName=$accrow['acc_name'];			
			}
						
			$content.= "
				    <tr>
				        <td>".$fxrow['mdate']."</td><td>".$acctNo." -".$acctName."</td><td>".number_format($amount, 2)."</td><td>".$receipt."</td><td>".$mode."</td><td>".$fxrow['contact']."</td><td>".$bank['account_no'] ."-". $bank['bank']." ".$bank['name']."</td>
				    </tr>
				    ";	
					$balance +=$amount;
			//}
			$i++;
		}
		$content .= "<tr><th>Total </th></th><th><th>".number_format($balance, 2)."</th><th></th><th></th><th></th><th></th><th></th></tr></tbody></table>";
		
	}
	else {
		$cont = '<font color=red>No Income registered yet.</font>';
	 $resp->assign("status", "innerHTML", $cont);
	
	}
	}
	$resp->call("createTableJs");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function editIncomeForm($pid, $type, $account, $contact, $from_date,$to_date,$branch_id)
{
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$resp->assign("status", "innerHTML", "");
	$res = @mysql_query("select  ac.name, r.id, r.contact as contact, r.bank_account as bank_account, r.amount, r.account_id, r.cheque_no, r.mode, r.receipt_no, date_format(date, '%Y') as year, date_format(date, '%m') as month, date_format(date, '%d') as mday from  other_income r join accounts ac on ac.id = r.account_id where r.id = $pid");

	if (@mysql_num_rows($res) > 0)
	{
		$row = @mysql_fetch_array($res);
		
		
		$content ="<form method='post' class='panel form-horizontal form-bordered'>";
$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h4 class="semibold text-primary mt0 mb5">EDIT INCOME</h4>
                                               		 <p class="text-default nm"></p>
                                           	 </div>
                                        </div>';
		if ($row['mode'] == 'cash')
		{
			
					
					 $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Amount Paid:</label>
                                            <div class="col-sm-6">
                                           <input onkeyup="format_as_number(this.id)" type="numeric" id="amount" name="amount"  value="'.$row['amount'].'" class="form-control">   </div></div>';
                                           
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Contact Person:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" id="contact" name="contact"  value="'.$row['contact'].'" class="form-control">   </div></div>';                   
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Receipt No:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" id="rcpt_no" name="rcpt_no" value="'.$row['receipt_no'].'" class="form-control">
                                            </div></div>';
                                            
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Cheque No (Optional):</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" id="cheque_no" name="cheque_no"  value="'.$row['cheque_no'].'" class="form-control">                                    
                                            </div></div>'; 
                                                                                      
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Date:</label>
                                            <div class="col-sm-6">
                                           <input type="text" class="form-control" id="date" name="date" value="'.$row['mday']."/".$row['month']."/".$row['year'].'" />
                                            </div></div>
					   <input type="hidden" id="mode" name="mode"  value="'.$row['mode'].'">';
			    		$content .= "<div class='panel-footer'><button type='reset' class='btn btn-default'  onclick=\"xajax_listIncome('".$account."', '".$contact."', '".$from_date."','".$to_date."','".$branch_id."')\">Back</button>
			    		<button type='reset' class='btn btn-default'  onclick=\"xajax_editIncomeForm('".$pid."', '".$type."', '".$from_date."', '".$to_date."', '".$contact."','".$branch_id."')\">Reset</button>
                                            <button type='button' class='btn btn-primary'    onclick=\"xajax_updateIncome2('".$row['id']."', '".$row['name']."', '".$row['bank_account']."', document.getElementById('amount').value, getElementById('rcpt_no').value, getElementById('cheque_no').value, '', document.getElementById('date').value,document.getElementById('contact').value,document.getElementById('mode').value); return false;\">Update</button>";
                                            $content .= '</div></form></div>';
                                            
                                            $resp->call("createDate","date");
					
		}
		else
		{
			$mem_res = mysql_query("select m.first_name, m.last_name, m.mem_no, ma.id as memacc_id, a.name from member m join mem_accounts ma on m.id = ma.mem_id join savings_product p on ma.saveproduct_id = p.id join accounts a on p.account_id = a.id where lower(a.name) <> 'compulsory savings' and lower(a.name) <> 'compulsory shares' and ma.id =". $row['mode']." order by m.first_name, m.last_name");
			$mems .= "<option value=''>&nbsp;</option>";
			while ($mem_row = mysql_fetch_array($mem_res))
			{
				$mems .= ($mem_row['memacc_id'] == $row['mode']) ? "<option value='$mem_row[memacc_id]' selected>".$mem_row['first_name']."".$mem_row['last_name']." -".$mem_row['mem_no']." -". ($mem_row['name'])."</option>" : "<option value='".$mem_row['memacc_id']."'>".$mem_row['first_name']."". $mem_row['last_name']."-".$mem_row['mem_no']."-".($mem_row['name'])."</option>";
			}
			
												
					 $content = '<div class="form-group">
                                            <label class="col-sm-3 control-label">Amount Paid:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" id="amount" name="amount"  value="'.$row['amount'].'" class="form-control">   </div></div>';
                                           
                                           $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Select Paying Member:</label>
                                            <div class="col-sm-6">
                                          <select name="mem_acc_id" id="mem_acc_id" class="form-control">'.$mems.'</select></div></div>';
                                           
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Contact Person:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" id="contact" name="contact"  value="'.$row['contact'].'" class="form-control">   </div></div>';
                                          /*   $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Receipt No:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" id="rcpt_no" name="rcpt_no" value="'.$row['receipt_no'].'" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Cheque No (Optional):</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" id="cheque_no" name="cheque_no"  value="'.$row['cheque_no'].'" class="form-control">                                 
                                            </div></div>'; 
                                            */                                           
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Date:</label>
                                            <div class="col-sm-6">
                                            <input type="text" class="form-control" id="date" name="date" value="'.$row['mday']."/".$row['month']."/".$row['year'].'" />
                                            </div></div>';
                                            
                                             $content .= "<div class='panel-footer'><button type='button' class='btn btn-default' onclick=\"xajax_listIncome('".$account."', '".$contact."', '".$from_date."','".$to_date."','".$branch_id."')\">Back</button>
                                             <button type='reset' class='btn btn-default' onclick=\"xajax_editIncomeForm('".$pid."', '".$type."', '".$from_date."','".$to_date."', '".$contact."','".$branch_id."')\">Reset</button>
                                            <button type='button' class='btn btn-primary'  onclick=\"xajax_updateIncome2('".$row['id']."', '".$row['name']."', '".$row['bank_account']."', document.getElementById('amount').value,'','', getElementById('mem_acc_id').value, document.getElementById('date').value, document.getElementById('contact').value); return false;\">Update</button>";
                     $content .= '</div></form></div>';
                     $resp->call("createDate","date");                       
		}
		
	}
	else{
		$cont = "<font color=red>ERROR: Income not found!</font>";
			    $resp->assign("status", "innerHTML", $cont);
	//return $resp;
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function updateIncome2($pid, $pname, $bank_account, $amount, $rcpt_no, $cheque_no, $mem_acc_id, $date, $contact,$mode)
{       list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$amount=str_ireplace(",","",$amount);
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$calc = new Date_Calc();
	if ($pid == '' || $amount == '')
		$resp->alert('Please do not leave any fields blank.');
	/*elseif ($rcpt_no == '' && $cheque_no == '' && $mem_acc_id == '')
	{
		$resp->alert("Please fill in either a receipt No or Cheque No.");	
	}*/
	elseif ($calc->isFutureDate($$mday, $month, $year))
		$resp->alert('Date can not be a future date');
	elseif (!unique_rcpt($rcpt_no, 'other_income', $pid)){

		$resp->alert("Receipt has already been used.");
	}else
	{
		$resp->confirmCommands(1, "Do you really want to update?");
		$resp->call('xajax_updateIncome', $pid, $pname, $bank_account, $amount, $rcpt_no, $cheque_no, $mem_acc_id, $date, $contact);
	}
	return $resp;
}

function updateIncome($pid, $pname, $bank_account, $amount, $rcpt_no, $cheque_no, $mem_acc_id, $date, $contact)
{
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$former = mysql_fetch_array(mysql_query("select * from other_income where id='".$pid."'"));
	//$date = sprintf("%d-%02d-%02d", $date);
	$date = $date." ".date('H:i:s');
	$mode = ($mem_acc_id == '') ? 'cash' : $mem_acc_id;
	start_trans();
//	$res = @mysql_query("update other_income set date='".$date."', amount=$amount, receipt_no='".$rcpt_no."', cheque_no = '".$cheque_no."', mode = '".$mode."', contact='$contact' where id=$pid");
	
	if (mysql_query("update other_income set date='".$date."', amount=$amount, receipt_no='".$rcpt_no."', cheque_no = '".$cheque_no."', mode = '".$mode."', contact='$contact' where id=$pid")){
		if ($mode == 'cash')
		{
			if(mysql_query("update bank_account set account_balance=account_balance -".$former['amount']."+".$amount." where id='".$bank_account."'")){
				/////////////////////
				$accno = mysql_fetch_assoc(mysql_query("select a.account_no,a.name from accounts a join other_income i on i.account_id=a.id where i.id=".$pid));
				$action = "update other_income set date='".$date."', amount=$amount, receipt_no='".$rcpt_no."' where id=$pid";
				
				$msg = "Updated Other Income set date:".$date.", amount:".$amount." on ac/no: ".$accno['account_no']." - ".$accno['name'];
				log_action($_SESSION['user_id'],$action,$msg);
				///////////////////
				commit();
				$content .= "<font color=red><b>$pname updated successfully.</b></font><br>";
			}else{
				$resp->alert("Update not done! \nCould not update bank account");
				rollback();
			}
		}
		else
		{
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
				//LOAN REPAYMENTS
				$pay_res = mysql_query("select sum(princ_amt + int_amt) as amount from payment where mode='".$mode."'");
				$pay = mysql_fetch_array($pay_res);
				$pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;
				//INCOME DEDUCTIONS
				$inc_res = mysql_query("select sum(amount) as amount from other_income where id != $pid and mode='".$mode."'");
				$inc = mysql_fetch_array($inc_res);
				$inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;

				$balance = $dep_amt + $int_amt + $former['amount']  - $with_amt - $charge_amt - $pay_amt - $inc_amt;
				//IF INSUFFICIENT SAVINGS
				if($balance < $amount){
					$resp->alert("Payment not registered! \n Insufficient savings to pay/offset the amount: dep_amt ". $dep_amt. " int_amt ". $int_amt. " with_amt ". $with_amt. " charge ".$charge_amt. " pay_amt ".$pay_amt. "Inc_amt ". $inc_amt ." bal ".$balance. " amt_paid ".$amount);
					rollback();
					return $resp;
				}
				else
				{
					$content .= "<font color=blue>Income <b>$pname</b> updated successfully.</font><br>";
					commit();
				}
		}
	}else{
		$content .= "<font color=red><b>ERROR: ".$pname." not updated!</b> ".mysql_error()."</font>";
		commit();
	}
	$resp->assign("status", "innerHTML", $content);
	//$resp->call('xajax_listIncome', $account, $contact, $from_date,$to_date);
	return $resp;
}

function deleteIncome2($pid, $bank_account, $amount, $account, $contact, $from_date,$to_date, $branch_id)
{
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	if (@mysql_num_rows(@mysql_query("select * from other_income where id = $pid")) < 1)
		$resp->alert("Cannot delete: Income entry not found.");
	else
	{
		$resp->confirmCommands(1, "Do you really want to delete?");
		$resp->call('xajax_deleteIncome', $pid, $bank_account, $amount, $account, $contact, $from_date,$to_date, $branch_id);
	}
	//$resp->assign("status", "innerHTML", "test");
	return $resp;
}


function deleteIncome($pid, $bank_account, $amount, $account, $contact, $from_date,$to_date,$branch_id)
{
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	start_trans();
	$accno = mysql_fetch_assoc(mysql_query("select a.account_no,a.name from accounts a join other_income i on i.account_id=a.id where i.id=".$pid));
	$res = @mysql_query("delete from other_income where id = $pid");
	if (@mysql_affected_rows() > 0)
		if(mysql_query("update bank_account set account_balance=account_balance -".$amount." where id='".$bank_account."'")){
		/////////////////
			$action = "delete from other_income where id = $pid";
			$msg = "Deleted  ".number_format($amount,2)." from other income on ac/no: ".$accno['account_no']." - ".$accno['name'];
			log_action($_SESSION['user_id'],$action,$msg);
			/////////////////
			commit();
			$content .= "<font color=green><b>Income deleted successfully.</b></font><br>";
		}
	else{
		rollback();
		$content .= "<font color=green><b>Error: Failed to delete Income. </b></font><br>";
	}
//listIncome($account, $contact, $from_date,$to_date, $branch_id)
	$resp->assign("status", "innerHTML", $content);
	$resp->call('xajax_listIncome', $account, $contact, $from_date,$to_date,$branch_id);
	return $resp;
}


function unique_rcpt($rcptno, $table='', $pid)
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
		$res = @mysql_query("SELECT receipt_no FROM payment where receipt_no='".$rcptno."' UNION SELECT receipt_no FROM deposit where receipt_no='".$rcptno."' UNION SELECT receipt_no from collected where receipt_no='".$rcptno."' UNION SELECT receipt_no from shares where receipt_no='".$rcptno."' UNION SELECT receipt_no from other_income where receipt_no =='".$rcptno."' and id<>'".$pid."' UNION SELECT receipt_no from recovered where receipt_no='".$rcptno."'");
		if (@mysql_num_rows($res) > 0)
			return false;
		else
			return true;
	}	
			
}

function incomesReg(){

	            $resp = new xajaxResponse();
	            $content='';
                    $content.='<div class="span12">                                                     
                    <div class="block-fluid">
                    <div class="row-form">
                    <h3 class="panel-title">REGISTER INCOME</h3>
                    </div>';                    
                     
		           $content.='<div class="row-form"> 		                                                        
	                   <div class="span12">             
	                                    
	                   </div></div>';
	                   $content.='<div class="row-form"> 
                	   <div class="span3">
                	   <span class="top title">Amount:</span>
      			   <input  type="int" id="amt" onkeyup="format_as_number(this.id)" name="amt" class="form-control">                                                  
                            </div>
                        					      			                          
                             <div class="span3">
                             <span class="top title">Paid by:</span>
                              <select id="payMethod" class="form-control" onchange=\'xajax_modePayment(getElementById("payMethod").value,getElementById("amt").value);\'>                          
                              <option value="">--Choose Method--</option>
                              <option value="offset">Off Set From Customer Savings</option>
                              <option value="cash">Cash</option>                              
                              </select>                                                     
		                </div>
		               
		                  <div id="payMethod_div">		                 
		                  <input type="hidden" id="cashAccId" value="">
		                  <input type="hidden" id="rcpt" value="">
		                  </div>
		                  
		                 <div id="savingsAcct_div">
		                  
		                  
		                  </div>			      			                        
		                 </div>';               
                            	
			      $content.='<div class="row-form">	 		                                                             
                              <div class="span3">
                              <span class="top title">Main Account:</span>';
                              $curr_assets=mysql_query("select * from accounts where account_no >=411 and account_no <= 419");
			   
			      $content.='<select id="accountNo" class="form-control" onchange=\'xajax_childAccounts(getElementById("accountNo").value);\'> 
			      <option value="">--Choose Account--</option>';
			      while($row2=mysql_fetch_array($curr_assets)){                                                
                              $content.='<option value="'.$row2['account_no'].'">'.$row2['account_no'].'-'.$row2['name'].'</option>';         
                              }
                              $content.='</select>  
                              </div>
                              <div id="childAccount_div">
                              
                              </div>';			      			                         
                                                
                             $content.='<div class="span3">
                             <span class="top title">Contact Person:</span>
                             <input type="int" id="contact" class="form-control">
                             </div>
                             <div class="span2">
                             <span class="top title">Date:</span>
                             <input type="int" id="date" class="form-control">                          
                             </div></div>';              
		                  
                             $content .= "<div class='panel-footer'>
                             <button type='button' class='btn btn-primary' onclick=\"xajax_saveIncomeNew(document.getElementById('amt').value,document.getElementById('payMethod').value,document.getElementById('svgsAcctId').value,getElementById('cashAccId').value,getElementById('rcpt').value,document.getElementById('accountId').value,getElementById('contact').value,document.getElementById('date').value); return false;\">Save</button></div>";
                             
                    $resp->call("createDate","date");
		    $resp->assign("display_div", "innerHTML", $content);	
		    return $resp;
}

function saveIncomeNew($amount_paid,$payMethod,$mem_acc_id,$cash_acct,$rcpt_no,$acc_id,$contact,$date)
{       list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	
	 if(empty($payMethod)){
	 $resp->alert('Choose Payment Method');
	 return $resp;
	 }
	 	
        if(empty($amount_paid)){        	        
        $resp->alert('Amount is REQUIRED');
        return $resp;
        }
        
        $amount=str_ireplace(",","",$amount_paid);
          
        /*if(!int_val($amount)){        	        
        $resp->alert('Enter Valid Amount');
        return $resp;
        }
        */       
	if($amount < 0){        	        
        $resp->alert('Enter Valid Amount');
        return $resp;
        }
        
        if($payMethod=='offset'){
	 $bal=libinc::get_savings_bal($mem_acc_id);
	 if($bal<$amount){
	 $resp->alert("Member Has Insuficient Savings. Available Balance is ".number_format($bal));
	 return $resp;
	 }
	 }
	
	if(empty($date)){
	 $resp->alert('Choose A Date Please');
	 return $resp;
	}
	
	if($payMethod=='offset' && empty($mem_acc_id)){
	 $resp->alert('Choose A savings Account');
	 return $resp;
	}
	
	if($payMethod=='cash' && empty($rcpt_no)){
	 $resp->alert('Enter Receipt No.');
	 return $resp;
	}
	
	if($payMethod=='cash' && !empty($rcpt_no)){
	if(!unique_rcpt($rcpt_no, '')){
	$resp->alert("ReceiptNo Already Exists");
	return $resp;
        }}
        
        if($payMethod=='offset'){
	 $mode = $mem_acc_id;
	}
	
	if($payMethod=='cash'){
	 $mode = 'cash';
	}
        	
	if ($calc->isFutureDate($mday, $month, $year))
	$resp->alert('You entered a future date.');
	
	$memName =mysql_query("select m.first_name as fname,m.last_name as lname from mem_accounts mem join member m on mem.mem_id=m.id where mem.id='".$mem_acc_id."' limit 1");           
        if(mysql_num_rows($memName)>0){
        $nm = mysql_fetch_array($memName);	
        $name=$nm['fname']." ".$nm['lname'] ;
	}
	else
	$name='';
	
	if ($calc->isFutureDate($mday, $month, $year))
	$resp->alert('You entered a future date.');
	
	if(!empty($name))
	$msg="Confirm Deduction On ".$name."'s Account";
	else
        $msg="Confirm Transaction Please!";
	
        $resp->confirmCommands(1, $msg);
	$resp->call("xajax_confirmIncome",$acc_id,$amount,$date,$cash_acct,$contact,$rcpt_no,$mode,$name);
	
	return $resp;
}

function confirmIncome($acc_id,$amount,$date,$cash_acct,$contact,$rcpt_no,$mode,$name){
$resp = new xajaxResponse();

		start_trans();	
	
		$res = @mysql_query("insert into other_income (account_id, amount, date, bank_account, contact, receipt_no, mode) values ('$acc_id', $amount, '".$date."', '$cash_acct', '$contact', '$rcpt_no','$mode')");
			
		if(mysql_affected_rows() >0){
		       if(!empty($name))
			$resp->alert("Income registered successfully from member ".$name.".");
		       else
		       $resp->alert("Income registered successfully.");
			commit();
		}else{
			$resp->alert("Error: Income not registered.");
			rollback();
		}
 return $resp;
}


function modePayment($method,$amount){
$resp = new xajaxResponse();

		if($method=='cash'){
		//$content.='<div class="row-form"> 
		$content.='<div class="span2">
		<span class="top title">Receipt/Voucher No.</span>
		<input type="int" id="rcpt" class="form-control">
		<input type="hidden" id="svgsAcctId" value="">                     
		</div>
		<div class="span3">
		<span class="top title">Destination Account</span>
		<select id="cashAccId" class="form-control" disabled><option value="">';
				
		$account_res = mysql_query("select a.name as name, a.account_no as account_no, b.bank as bank, b.id as id from bank_account b join accounts a on b.account_id=a.id where b.id='".CAP_Session::get('account_assigned')."' && a.branch_no like '".$_SESSION['branch_no']."'");

		while($account = mysql_fetch_array($account_res)){
		$content .= "<option selected value='".$account['id']."'>".$account['account_no'] ." - ".$account['bank']." ".$account['name'];
		}			
		$content .= '</select>
		   </div>
		 ';
		}
                elseif($method=='offset'){
	        //$content.='<div class="row-form">
	        $content.='<div class="span2">
		<span class="top title">Member No:</span>
		<input type="int" id="mem_no" class="form-control" onblur=\'xajax_verifyMember(this.value);\'>                                                     
                </div>
                        <div> </div>	               
	                <input type="hidden" id="cashAccId" value="">
	                <input type="hidden" id="rcpt" value="">
	                </div>';
                }
                
		else{
		$content.='<div>                        
			<input type="hidden" id="rcpt" value="">
			<input type="hidden" id="cashAccId" value="">		
			</div>';
		}
	
	$resp->assign("payMethod_div", "innerHTML", $content);
	return $resp;
}

function verifyMember($mem_no){
$resp = new xajaxResponse();

        $qry=mysql_query("select id,first_name,last_name from member where mem_no='".$mem_no."'");
	if(mysql_num_rows($qry)>0){
	$row=mysql_fetch_array($qry);
	$mem_id=$row['id'];
	$name=$row['first_name']." ".$row['last_name'];
	}
	else{	
	$resp->alert("Customer Does not Exist");
	return $resp;
	}

 $mem_res =mysql_query("select a.name as account_name, a.account_no as account_no, mem.id as memaccount_id from mem_accounts mem join savings_product p on mem.saveproduct_id=p.id join accounts a on p.account_id=a.id join member m on mem.mem_id=m.id where m.id='".$mem_id."' and p.type='free' order by a.account_no");
$content.='  
        <div class="span3">';          
        if(mysql_num_rows($mem_res)>0){
        $content.='<span class="top title">Savings Account(<font color="green"><b>'.$name.'</b></font>)</span>
        <select type=int id="svgsAcctId" class="form-control" required><option value="">--Choose --</option>';                                         
	while($prd = mysql_fetch_array($mem_res)){	
	$content .= "<option value='".$prd['memaccount_id']."'>".$prd['account_name']."</option>";
	}	
	$content.= '</select>';
	$content.= '</div></div>';
	}
	else{	
	$resp->alert("Customer Has no Savings Account");
	return $resp;
	}
	
	$resp->assign("savingsAcct_div", "innerHTML", $content);
	return $resp;
}

function childAccounts($acctNo){
$resp = new xajaxResponse();
                          $content.='<div class="span3">
                             <span class="top title">Account</span>';
			      $curr_assets=mysql_query("select * from accounts where account_no like '".$acctNo."%'");
			   
			      $content.='<select id="accountId" class="form-control");\'> 
			      <option value="">--Choose Account--</option>';
			      while($row=mysql_fetch_array($curr_assets)){                                                
                              $content.='<option value="'.$row['id'].'">'.$row['account_no'].'-'.$row['name'].'</option>';      
                              }
                              $content.='</select>
                            </div>';

	$resp->assign("childAccount_div", "innerHTML", $content);
	return $resp;
}

?>
