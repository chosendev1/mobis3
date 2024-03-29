<?php

require_once("resources/pmt/pmt_statements_class.php");
require_once("resources/pmt/pmt_balancesheet_class.php");
require_once("resources/pmt/pmt_portfolio_class.php");


$xajax->registerFunction("reports");
$xajax->registerFunction("list_loanproducts");
$xajax->registerFunction("list_groups");
$xajax->registerFunction("list_applics");
$xajax->registerFunction("list_disbursed");
$xajax->registerFunction("list_outstanding");
$xajax->registerFunction("list_arrears");
$xajax->registerFunction("schedule");
$xajax->registerFunction("list_cleared");
$xajax->registerFunction("list_written_off");
$xajax->registerFunction("repayment");
$xajax->registerFunction("ageing");
$xajax->registerFunction("risk_ageing");
$xajax->registerFunction("portifolio_status");
$xajax->registerFunction("loan_ledger");
$xajax->registerFunction("list_due");
$xajax->registerFunction("assettrial_balance");
$xajax->registerFunction("lctrial_balance");
$xajax->registerFunction("incometrial_balance");
$xajax->registerFunction("groups_status");
$xajax->registerFunction("loan_repayments");

$xajax->registerFunction("trial_balance");
$xajax->registerFunction("balance_sheet");
$xajax->registerFunction("income_statement");
$xajax->registerFunction("cash_flow");

$xajax->registerFunction("pmt_balance_sheet");
$xajax->registerFunction("pmt_income_statement");
$xajax->registerFunction("pmt_portfolio_activity");

//PERFORMANCE
$xajax->registerFunction("repay_ratio");
$xajax->registerFunction("generate_repay_ratio");
$xajax->registerFunction("liquidity_ratio");
$xajax->registerFunction("generate_liquidity_ratio");
$xajax->registerFunction("par_ratio");
$xajax->registerFunction("generate_par_ratio");
$xajax->registerFunction("coverage_ratio");
$xajax->registerFunction("generate_coverage_ratio");
$xajax->registerFunction("port_yield");
$xajax->registerFunction("generate_port_yield");
$xajax->registerFunction("debtto_equity_ratio");
$xajax->registerFunction("generate_debtto_equity_ratio");
$xajax->registerFunction("average_loan");
$xajax->registerFunction("generate_average_loan");
$xajax->registerFunction("ratios");
$xajax->registerFunction("oper_sufficiency");
$xajax->registerFunction("generate_oper_sufficiency");
$xajax->registerFunction("fin_sufficiency");
$xajax->registerFunction("generate_fin_sufficiency");
$xajax->registerFunction("oper_expense_ratio");
$xajax->registerFunction("generate_oper_expense_ratio");

//MEMBERS AND SHARES
$xajax->registerFunction("membersList");
$xajax->registerFunction("sharesLedgerForm");
$xajax->registerFunction("sharesLedger");
$xajax->registerFunction("genSharesReport");
$xajax->registerFunction("sharing_done");
$xajax->registerFunction("show_dividends");
$xajax->registerFunction("genSharesReport");
$xajax->registerFunction("member_ledger");
$xajax->registerFunction("memsavings_ledger");
$xajax->registerFunction("memloan_ledger");


//SAVINGS
$xajax->registerFunction("list_saveproduct");
$xajax->registerFunction("list_accounts");
$xajax->registerFunction("list_deposits");
$xajax->registerFunction("list_withdrawal");
$xajax->registerFunction("savings_ledger");
$xajax->registerFunction("savings_ledger_form");
$xajax->registerFunction("cummulated_savings");
$xajax->registerFunction("ind_savings_summary");
$xajax->registerFunction("ind_savings_summary_form");
$xajax->registerFunction("sacco_savings_summary");
$xajax->registerFunction("portfolio_summary");

//BANK STATEMENTS
$xajax->registerFunction("showBankStatementForm");
$xajax->registerFunction("getStatement");

#SHOW LOG STATUS
$xajax->registerFunction("log_form");
$xajax->registerFunction("display_log");
$xajax->registerFunction("pmtExport");


#LOG FORM
function log_form($sel_trans='all')
	{       
	       
		$resp = new xajaxResponse();
		//$res = new xajaxResponse();

		$user = @mysql_query("select userName,userId from users");
		$time = @mysql_query("select time from logs order by id desc");
		$cmd = Array('cmd'=>Array('type' => 'inputCheckbox', 'displayname' => 'Show Command','value' => 'Y', 'onvalue' => 'Y', 'offvalue' => 'N',),);
			
		$sub_menu = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <p><h5 class="semibold text-primary mt0 mb5">AUDIT TRAIL</h5></p>
                            </div>               
                            <div class="panel-body">';
                            
                     $sub_menu .= "<div class='form-group'>
                                    <div class='row'>
                                        <div class='col-sm-6'>
                                            <label class='control-label'>Transaction:</label>
                                           <select name='transactions' id='transactions' class='form-control' onchange=\"xajax_log_form(getElementById('transactions').value);\" ><option value='".$sel_trans."'>".ucfirst($sel_trans)."</option><option value='all'>All</option><option value='savings'>Savings</option><option value='Chart of Accounts'>Chart of Accounts</option><option value='loans'>Loans</option><option value='customers'>Customers</option><option value='shares'>Shares</option>
	</select>                                            
                                        </div>
                                        <div class='col-sm-6'>
                                            <label class='control-label'>Relation:</label>";
                                            switch($sel_trans){
case 'all':
	$sub_menu .= "<select name='transaction' id='transaction' class='form-control'><option value='all'></select>";
break;
case 'savings':
	$sub_menu .= "<select name='transaction' id='transaction' class='form-control'><option value='all'>All</option><option value='open'>Open Account</option><option value='deposit'>Deposit</option><option value='withdrawal'>Withdrawal</option></select>";
break;
case 'Chart of Accounts':
	$sub_menu .= "<select name='transaction' id='transaction' class='form-control'><option value='all'>All</option><option value='other_income'>Income</option><option value='Receivable'>Receivables</option><option 'fixed_asset'>Fixed Assets</option><option value='investment'>Investments</option><option value='expense'>Expenses</option><option value='Payable'>Payables</option><option value='funds'>Capital Funds</option></select>";
break;
case 'loans':
	$sub_menu .= "<select name='transaction' id='transaction' class='form-control'><option value='all'>All</option><option value='product'>Product</option><option value='payment'>Repayment</option><option value='written_off'>Written Off</option><option value='recovered'>Recovered</option><option value='application'>Application</option></select>";
break;
case 'customers':
	$sub_menu .= "<select name='transaction' id='transaction' class='form-control'><option value='all'>All</option><option value='member'>Members</option><option value='loan_group'>Loan Group</option></select>";
break;
case 'shares':
	$sub_menu .= "<select name='transaction' id='transaction' class='form-control'><option value='all'>All</option><option value='share'>Shares</option><option value='share_transfer'>Share Transfer</option></select>";
break;
default:
	$sub_menu .= "<select name='transaction' id='transaction' class='form-control'><option value='all'></select>";
	}
	$sub_menu .="</div></div></div>";
	
	$sub_menu .='<div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">User:</label>
                                            <select id="userId" name="userId" class="form-control"><option value="all">All</option>';
                      while($rowUser = @mysql_fetch_assoc($user)){
$sub_menu .= "<option value='".$rowUser['userId']."'>".$rowUser['userName']."</option>";
}

	$sub_menu .= '</select></div>
                                
                  
                                        <div class="col-sm-6">
                                            <label class="control-label">Action:</label>
                                            <select id="action" name="action" class="form-control"><option value="all">All</option><option value ="insert">Insertion</option><option value="update">Editing</option><option value="delete">Deletion</option>
	</select>                    
                                        </div></div></div>';
                                        
                                $sub_menu .='<div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">From Date:</label>
                                           <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From date" /></div>
                                                <div class="col-md-6">'.hour('from_','').'</div>
                                            </div>                                          
                                    </div>
                                    
                                     <div class="col-sm-6">
                                            <label class="control-label">To Date:</label>
                                           <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to date" /></div>
                                                <div class="col-md-6">'.hour('to_','').'</div>
                                            </div></div>
                                </div></div>
        
                               <div class="form-group">
        
                                      <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Show Command:</label>
                                           	                                    
                                          <span class="checkbox custom-checkbox">
                                                <input type="checkbox" name="customcheckbox2" id="customcheckbox2" />
                                                <label for="customcheckbox2"></label>
                                            </span>     
                                        </div>
                                        
                                    </div>
                                </div>';
                                                              
                
	$sub_menu .= "<div class='panel-footer'>                              
                                
                                <input type='button' class='btn  btn-primary' value='Display Log'  onclick=\"xajax_display_log(getElementById('userId').value, getElementById('action').value, getElementById('transaction').value, getElementById('transactions').value,  getElementById('from_date').value, getElementById('from_hour').value, getElementById('to_date').value, getElementById('to_hour').value, getElementById('customcheckbox2').checked); return false;\">
                            </div></div>
                        </form>
                        <!--/ Form default layout -->
                    </div></div>";
                    $resp->call("createDate","from_date");
		    $resp->call("createDate","to_date"); 

$resp->assign("display_div", "innerHTML", $sub_menu);
return $resp;
}


function checked($checked){
$res = new xajaxResponse();
$res->assign("display_div","innerHTML",$checked);

return $res;
}

# DISPLAY LOG ACTIONS
function display_log($userId, $action, $transaction,$relation,$from_date,$from_hour,$to_date, $to_hour,$cmd)
	{
	
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
$resp = new xajaxResponse();
/*if($_SESSION['position'] <>'Manager'){
		$resp->alert("Access Denied! \nOnly the Manager can view the log audit.");
		return $resp;
	}*/
$user = ($userId == 'all') ? NULL: "and user_id=".$userId;
$act = ($action == 'all') ? NULL: "and action like '%".$action."%'";
$trans = ($transaction == 'all') ? NULL: "and action like '%".$transaction."%'";
$from_time = sprintf("%02d-%02d-%02d %02d", $from_year, $from_month, $from_mday, $from_hour);
$to_time = sprintf("%02d-%02d-%02d %02d", $to_year, $to_month, $to_mday, $to_hour);
$sql_cmd = (isset($cmd)) ? ",action" : NULL;
if($cmd)
$rel="";
switch($relation){
case 'all':
	$rel=NULL;
break;
case 'savings':
	$rel="and action like '%member_account%' or action like '%open%' ". $user." ".$act." ".$trans." and time between '".$from_time."' and '".$to_time."' or action like '%deposit%' ". $user." ".$act." ".$trans." and time between '".$from_time."' and '".$to_time."' or action like '%withdraw%' ". $user." ".$act." ".$trans." and time between '".$from_time."' and '".$to_time."'";
break;
case 'Chart of Accounts':
	$rel="and action like '%paya%' or action like '%receivable%' ". $user." ".$act." ".$trans." and time between '".$from_time."' and '".$to_time."' or action like '%expense%' ". $user." ".$act." ".$trans." and time between '".$from_time."' and '".$to_time."' or action like '%other_%' ". $user." ".$act." ".$trans." and time between '".$from_time."' and '".$to_time."' and action like '%asset%' or action like '%invest%' ". $user." ".$act." ".$trans." and time between '".$from_time."' and '".$to_time."' or action like '%collect%' ". $user." ".$act." ".$trans." and time between '".$from_time."' and '".$to_time."'";
	break;
case 'loans':
	$rel="and action like '%loan_app%' or action like '%written_off%' ". $user." ".$act." ".$trans." and time between '".$from_time."' and '".$to_time."' or action like '%loan_prod%' ". $user." ".$act." ".$trans." and time between '".$from_time."' and '".$to_time."' or action like '%payment%' ". $user." ".$act." ".$trans." and time between '".$from_time."' and '".$to_time."' or action like '%recovered%' ". $user." ".$act." ".$trans." and time between '".$from_time."' and '".$to_time."' or action like '%penalty%' ". $user." ".$act." ".$trans." and time between '".$from_time."' and '".$to_time."' or action like '%colla%' ". $user." ".$act." ".$trans." and time between '".$from_time."' and '".$to_time."'";
	break;
case 'customers':
	$rel="and action like '%member%' or action like '%loan_group%' ". $user." ".$act." ".$trans." and time between '".$from_time."' and '".$to_time."'";
break;
case 'shares':
	$rel="and action like '%share%' or action like '%dividends%' ". $user." ".$act." ".$trans." and time between '".$from_time."' and '".$to_time."'";
break;
default:
	$rel=NULL;
}

$msg = mysql_query("select l.time,l.msg,u.userName ".$sql_cmd." from logs l join users u on l.user_id=u.userId where l.time between '".$from_time."' and '".$to_time."' ".$user." ".$act." ".$trans." ".$rel." order by time desc");
//$resp->alert($msg);
//return $resp;
$num_operations = mysql_num_rows($msg);
$reply = ($num_operations==1) ? "Operation" : "Operations";
$thead="";
$td="";
$content="";

if($num_operations>0){


	if($cmd=='true'){
		$thead = "<th>Command</th>";
		//$td = "<td>".$row['action']."</td>";
	}
	else{
		$thead = NULL;
		//$td = NULL;
	}
	$branchname = mysql_fetch_assoc(mysql_query("select * from branch"));
	if($userId == 'all')
		$member = "All Users";
	else{
	$name = mysql_fetch_assoc(mysql_query("select userName from users where userId=".$userId));
	$member = $name['userName'];
	//$member = $name['userName']." - ".$name['name'];
	}
		

$content .= "<a href='export/log?userId=".$userId."&cmd=".$cmd."&action=".$action."&transaction=".$transaction."&relation=".$relation."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&from_hour=".$from_hour."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&to_hour=".$to_hour."' target=blank()><b>Printable Version</b></a> | <a href='export/log?userId=".$userId."&cmd=".$cmd."&action=".$action."&transaction=".$transaction."&relation=".$relation."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&from_hour=".$from_hour."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&to_hour=".$to_hour."&format=excel' target=blank()><b>Export Excel</b></a><div align=RIGHT><strong>[".$num_operations."&nbsp;Operation(s)]"."</strong></div>";

$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <p><h3 class="text-primary mt0">'.$branchname['branch_name'].'</h3></p>
                                <p><h5 class="text-primary mt0">LOG STATUS FOR '.strtoupper($member).' FROM '.$from_time.' TO '.$to_time.'</h5></p>                 <p><h5 class="text-primary mt0">'.$reply.'</h5></p>
                              
                            </div>';
                            $content .= '<table class="table table-striped table-bordered" id="table-tools">';
 		$content .= "<thead><th>#</th><th>Time</th><th>Name</th>".$thead."<th>Action</th><thead><tbody>";
$counter = 1;
while($row = mysql_fetch_assoc($msg))
	{
	$td = ($cmd=='true')? "<td>".$row['action']."</td>":NULL;
	//$color = ($counter%2==0) ? "white" : "lightgrey";
$content .="<tr><td>".$counter."</td><td>".$row['time']."</td><td>".$row['userName']."</td>".$td."<td>".$row['msg']."</td></tr>";
$counter++;
}
$content .= "<tbody></table></div>";
$resp->call("createTableJs");
}
else{
$cont = "<font color='red'>No actions from the user in the selected time range!</font>";
$resp->assign("status", "innerHTML", $cont);
}

$resp->assign("display_div", "innerHTML", $content);
return $resp;
}
//


//DEFINE THE PARAMENTERS  OF THE STATEMENT
function showBankStatementForm()
{
	$resp = new xajaxResponse();
	//$acc = @mysql_query("select ac.account_no, ac.name, ba.id as bank_id from accounts ac join bank_account ba on ac.id = ba.account_id ");
	$accts .= "<option value=''>&nbsp;</option>";
	/* while ($acc_row = @mysql_fetch_array($acc))
	{
		$accts .= "<option value=$acc_row[bank_id]>$acc_row[name]</option>";
	}
	*/


//if (strtolower($_SESSION['position']) == 'manager')
					$account_res = mysql_query("select a.name as name, a.account_no as account_no, b.bank as bank, b.id as id from bank_account b join accounts a on b.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."'");
				/*else
					$account_res = mysql_query("select a.name as name, a.account_no as account_no, b.bank as bank, b.id as id from bank_account b join accounts a on b.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."' and b.id in (select bank_account_id from user_account where user_id =".$_SESSION['user_id'].")");*/
				while($account = mysql_fetch_array($account_res)){
					$accts .= "<option value='".$account['id']."'>".$account['account_no'] ." - ".$account['bank']." ".$account['name'];
				}


$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR BANK ACCOUNT STATEMENT</h3>
                                <p class="text-default nm"></p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Select Bank/Cash Account:</label>
                                           <select name="bank_acct_id" id="bank_acct_id">'.$accts.'</select>       
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Select Period:</label>
                                          <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>';                                  
                                   	
		 $content .= "<div class='panel-footer'>                              
                               
                                <button type='button' class='btn  btn-primary' onclick=\"xajax_getStatement(getElementById('bank_acct_id').value,  getElementById('from_date').value,  getElementById('to_date').value); return false;\">Show Statement</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","from_date");
	$resp->call("createDate","to_date");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//GENERATE BANK STATEMENT
function getStatement($bank_acct_id, $from_date,$to_date)
{  
     	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);

     $content="";
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	
	$acc = @mysql_query("select ac.account_no, ac.name, ba.id as bank_id from accounts ac join bank_account ba on ac.id = ba.account_id ");
		$accts = "<option value=''>&nbsp;</option>";
		while ($acc_row = @mysql_fetch_array($acc))
		{
			$accts .= "<option value='".$acc_row['bank_id']."'>".$acc_row['account_no']." -". $acc_row['name']."</option>";
		}
		
	$content .="<form method='post' class='panel form-horizontal form-bordered'>";
$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h5 class="semibold text-primary mt0 mb5">BANK ACCOUNT STATEMENT</h5>
                                           	 </div>
                                        </div>';
                                        
                                                                                                                           
                                            $content .= '<div class="form-group">
                                           
                                            <label class="col-sm-3 control-label">Select Bank/Cash Account:</label>
                                            <div class="col-sm-6">
                                           <select name="bank_acct_id" id="bank_acct_id" class="form-control">'.$accts.'</select>
                                            </div></div>';                                         
                                             $content .= '<div class="form-group">
                                              
                                            <label class="col-sm-3 control-label">Select Period:</label>
                                            <div class="col-sm-6">
                                            <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>
                                            </div></div>';                                            
                                            
                                            $content .= "<div class='panel-footer'>
                                            <button type='button' class='btn btn-primary'   onclick=\"xajax_getStatement(getElementById('bank_acct_id').value, getElementById('from_date').value,  getElementById('to_date').value); return false;\">Show Statement</button></div>";
                                            $content .= '</div></form>';
                                            $resp->call("createDate","from_date");
					    $resp->call("createDate","to_date"); 
					    
	if($bank_acct_id ==''){
		
		$cont="<font color=red>Please select a Bank/Cash account</font>";
		$resp->assign("status", "innerHTML", $cont);
	}
	
	elseif($from_date =='' || $to_date==''){
	
		$cont="<font color=red>Please Select the Date Range for Your Report</font>";
		$resp->assign("status", "innerHTML", $cont);
	}
	
	else{
	$acc_row = @mysql_fetch_array(@mysql_query("select name from accounts where id = (select account_id from bank_account where id = $bank_acct_id)"));
	$acc_name = $acc_row['name'];
	$start_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	 $end_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_year);
	$query = "select date, sum(amount) as amount, transaction from collected where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, 'import' as transaction from cash_transfer where dest_id = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, 'export' as transaction from cash_transfer where source_id = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, transaction from deposit where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, transaction from disbursed where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, transaction from expense where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(initial_value) as amount, transaction from fixed_asset where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(quantity * amount) as amount, transaction from investments where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, transaction from other_income where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, transaction from payable_paid where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select p.date, sum(p.int_amt + p.princ_amt) as amount, p.transaction from payment p join disbursed d on p.loan_id=d.id where p.bank_account = $bank_acct_id and p.date < '".$start_date."' and p.mode in ('Cash', 'Cheque') group by p.date UNION select pen.date, sum(pen.amount) as amount, pen.transaction from penalty pen join disbursed d on pen.loan_id=d.id where pen.bank_account = $bank_acct_id and pen.date < '".$start_date."'  and pen.status <>'pending' group by pen.date UNION select r.date as date, sum(r.amount) as amount, r.transaction as transaction from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.bank_account = $bank_acct_id and r.date < '".$start_date."'  group by r.date UNION select date, sum(total_amount) as amount, transaction from share_dividends where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(value) as amount, transaction from shares where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, transaction from withdrawal where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, transaction from sold_asset where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, transaction from payable where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(quantity * amount) as amount, transaction from sold_invest where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, transaction from other_funds where bank_account = $bank_acct_id and date < '".$start_date."' group by date order by date asc";	
	$bal_res = @mysql_query($query);
	if (@mysql_num_rows($bal_res) > 0)
	{
		$start_balance = 0;
		while ($bal_row = @mysql_fetch_array($bal_res))
		{
			if (strtolower($bal_row['transaction']) == 'collected' || strtolower($bal_row['transaction']) == 'import' || strtolower($bal_row['transaction']) == 'deposit' || strtolower($bal_row['transaction']) == 'sold_invest' || strtolower($bal_row['transaction']) == 'other_income' || strtolower($bal_row['transaction']) == 'payment' || strtolower($bal_row['transaction']) == 'penalty' || strtolower($bal_row['transaction']) == 'recovered' || strtolower($bal_row['transaction']) == 'shares' || strtolower($bal_row['transaction']) == 'sold_asset' || strtolower($bal_row['transaction']) == 'payable' || strtolower($bal_row['transaction']) == 'other_funds')
			{
				$amount = ($bal_row['amount'] == NULL) ? 0: $bal_row['amount'];
				$start_balance += $amount;
			}
			elseif (strtolower($bal_row['transaction']) == 'disbursed' || strtolower($bal_row['transaction']) == 'export' || strtolower($bal_row['transaction']) == 'expense' || strtolower($bal_row['transaction']) == 'investments' || strtolower($bal_row['transaction']) == 'payable_paid' || strtolower($bal_row['transaction']) == 'share_dividends' || strtolower($bal_row['transaction']) == 'withdrawal' || strtolower($bal_row['transaction']) == 'fixed_asset')
			{
				$start_balance -= $bal_row['amount'];
			//	if(strtolower($bal_row['transaction']) == 'export')
			//		$resp->alert($bal_row['amount']);
			}
		}
	}
	else
	{
		$start_balance = 0;
	}

	$query2 = "select id, date, amount as amount, transaction, receipt_no as ref from collected where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, amount as amount, 'import' as transaction, 'Cash Imported' as ref from cash_transfer where dest_id = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, amount as amount, 'export' as transaction, 'Cash Exported' as ref from cash_transfer where source_id = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, amount as amount, transaction, receipt_no as ref from deposit where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."' UNION select id, date, amount as amount, transaction, cheque_no as ref from disbursed where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, amount as amount, transaction, voucher_no as ref from expense where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, initial_value as amount, transaction, 'Fixed Asset Acquired' as ref from fixed_asset where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, amount as amount, transaction, voucher_no as ref from investments where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, amount as amount, transaction, receipt_no as ref from other_income where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION  select id, date, amount as amount, transaction, voucher_no as ref from payable_paid where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."' UNION select p.id, p.date, (p.int_amt + p.princ_amt) as amount, p.transaction, receipt_no as ref from payment p join disbursed d on p.loan_id=d.id where p.bank_account = $bank_acct_id and p.date >= '".$start_date."' and p.date <= '".$end_date."' and p.mode in ('Cash', 'Cheque') UNION select pen.id, pen.date as date, pen.amount as amount, pen.transaction, 'Penalty' as ref from penalty pen join disbursed d on pen.loan_id=d.id where pen.bank_account = $bank_acct_id and pen.date >= '".$start_date."' and pen.date <= '".$end_date."' and pen.status <>'pending'  UNION select r.id, r.date as date, r.amount as amount, r.transaction as transaction, receipt_no as ref from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.bank_account = $bank_acct_id and r.date >= '".$start_date."' and r.date <= '".$end_date."'  UNION select id, date, total_amount as amount, transaction, 'Dividends' as ref from share_dividends where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, value as amount, transaction, receipt_no as ref from shares where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, amount as amount, transaction, voucher_no as ref from withdrawal where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, amount as amount, transaction, receipt_no as ref from sold_asset where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, amount as amount, transaction, 'Loan Acquired' as ref from payable where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, (quantity * amount) as amount, transaction, receipt_no as ref from sold_invest where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, amount as amount, transaction, receipt_no as ref from other_funds where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."' order by date asc"; 
	
	$st_res = @mysql_query($query2);
	if (@mysql_num_rows($st_res) > 0)
	{
		$name = mysql_fetch_array(mysql_query("select * from branch"));
		
		$content .= "<a href='export/statement?bank_acct_id=".$bank_acct_id."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."' target=blank()><b>Printable Version</b></a> | <a href='export/statement?bank_acct_id=".$bank_acct_id."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&format=excel' target=blank()><b>Export Excel</b></a>";
				
		$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <p><h3 class="semibold text-primary mt0 mb5">'.$name['branch_name'].'</h3></p>
                                <p><h5 class="semibold text-primary mt0 mb5">BANK STATEMENT FOR '.strtoupper($acc_name).' FROM '.$start_date.' TO '.$end_date.'</p></h5>
                               
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
				
		$content .= "<thead><th>Date</th><th>Transaction</th><th>Reference</th><th>Debit</th><th>Credit</th><th>Balance</th></thead><tbody>";
		$content .= "<tr><td>".$start_date."</td><td>Balance B/F</td><th>Balance B/F</th><td>--</td><td>--</td><td>".number_format($start_balance, 2)."</td></tr>";
		$cur_bal = $start_balance;
		$i=1;
		while ($st_row = mysql_fetch_array($st_res))
		{
			if (strtolower($st_row['transaction']) == 'collected' || strtolower($st_row['transaction']) == 'import' || strtolower($st_row['transaction']) == 'import' || strtolower($st_row['transaction']) == 'deposit' || strtolower($st_row['transaction']) == 'sold_invest' || strtolower($st_row['transaction']) == 'other_income' || strtolower($st_row['transaction']) == 'payment' || strtolower($st_row['transaction']) == 'penalty' || strtolower($st_row['transaction']) == 'recovered' || strtolower($st_row['transaction']) == 'shares' || strtolower($st_row['transaction']) == 'sold_asset' || strtolower($st_row['transaction']) == 'payable' || strtolower($st_row['transaction']) == 'other_funds')
			{
				$cur_bal += $st_row['amount'];
				$trans = ucfirst($st_row['transaction']);
				if($trans == 'Disbursed')
					$trans = "Disbursement";
				elseif($trans == 'Payment')
					$trans = "Re-payment";
				elseif($trans == 'Other_income')
					$trans = 'Other Income';
				elseif($trans == 'Import')
					$trans= "Cash Imported";
				elseif($trans == 'other_funds')
					$trans= "Donations and Grants";
				
				
				//$color=($i % 2==0) ? "lightgrey" : "white";
				$content .= "<tr><td>".$st_row['date']."</td><td>".$trans."</td><td>RECEIPT NO: ".$st_row['ref']."</td><td>--</td><td>".number_format($st_row['amount'], 2)."</td><td>".number_format($cur_bal, 2)."</td></tr>";
				$i++;
			}
			elseif (strtolower($st_row['transaction']) == 'disbursed' || strtolower($st_row['transaction']) == 'export' || strtolower($st_row['transaction']) == 'expense' || strtolower($st_row['transaction']) == 'fixed_asset' || strtolower($st_row['transaction']) == 'payable_paid' || strtolower($st_row['transaction']) == 'share_dividends' || strtolower($st_row['transaction']) == 'investments' || strtolower($st_row['transaction']) == 'withdrawal') 
			{
				$cur_bal -= $st_row['amount'];
				$trans = ucfirst($st_row['transaction']);
				if($trans == 'Payable_paid')
					$trans = 'Payable Paid';
				elseif($trans == 'Export')
					$trans= "Cash Exported";
				//$color=($i % 2==0) ? "lightgrey" : "white";
				$content .= "<tr><td>".$st_row['date']."</td><td>".$trans."</td><td>PV/CHEQUE NO: ".$st_row['ref']."</td><td>".number_format($st_row['amount'], 2)."</td><td>--</td><td>".number_format($cur_bal, 2)."</td></tr>";
				$i++;
			}
		}
		
	}
	else
	{
		$cont= "<font color='red'>There are no transaction yet for this period.</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	$content.="</tbody></table></div>";
	$resp->call("createTableJs");
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


//LIST SAVINGS PRODUCT
function list_saveproduct(){
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
			
	$sth = mysql_query("select s.id as saveproduct_id, s.type as type, a.account_no as account_no, a.name as name,s.id as id, s.account_id as account_id, s.grace_period as grace_period, s.opening_bal as opening_bal, s.min_bal as min_bal, s.int_rate as int_rate, s.withdrawal_perc as withdrawal_perc, s.withdrawal_flat as withdrawal_flat, s.deposit_perc as deposit_perc, s.deposit_flat as deposit_flat, s.monthly_charge as monthly_charge, s.int_frequency as int_frequency from savings_product s join accounts a on s.account_id=a.id  order by a.name");
	
	if(@ mysql_numrows($sth) == 0){
		
		$cont = "<font color=red>No savings products created yet!</font>";
		$resp->assign("status", "innerHTML", $cont);
	return $resp;
	}else{
	
	        $content ="<a href='export/saveproducts' target=blank()><b>Printable Version</b></a> | <a href='export/saveproducts?format=excel' target=blank()><b>Export Excel</b></a>";
	
	$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <p><h5 class="text-primary mt0">LIST OF SAVINGS PRODUCTS</h5></p>
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
	
		$content .= "<thead><th><b>Product</b></th><th><b>Opening Bal.</b></th><th><b>Min Bal.</b></th><th><b>Int. Rate (%)</b></th><th><b>Withdrawal Charge (% of Amt)</b></th><th><b>Withdrawal Flat Charge</b></th><th><b>Deposit Charge (% of Amt)</b></th><th><b>Deposit Flat Charge</b></th><th><b>Monthly Charge</b></th><th><b>Int. Frequency(Months)</b></th><th><b>Type</b></th></thead><tbody>";
		$i=0;
		while($row = mysql_fetch_array($sth)){
			//$color=($i%2 == 0) ? "lightgrey" : "white";
			$name = $row['account_no']." - ".$row['name'];
			$content .= "<tr><td>".$name."</td><td>".$row['opening_bal']."</td><td>".$row['min_bal']."</td><td>".$row['int_rate']."</td><td>".$row['withdrawal_perc']."</td><td>".$row['withdrawal_flat']."</td><td>".$row['deposit_perc']."</td><td>".$row['deposit_flat']."</td><td>".$row['monthly_charge']."</td><td>".$row['int_frequency']."</td><td>".$row['type']."</td></tr>";
			$i++;		
		}
	}
	$content .= "</tbody></table></div>";
	$resp->call("createTableJs");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

/*
//LIST SAVINGS PRODUCT ACCOUNTS FOR MEMBERS
function list_accounts($mem_no, $name, $product_id){
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	$branch_res = mysql_query("select * from branch");
	$branch = mysql_fetch_array($branch_res);
	$content .= "<center><p><font color=#00008b size=4pt><b>".$branch['branch_name']."</b></font></p></center>";
	$content .= "<center><font color=#00008b size=3pt><B>SAVINGS PRODUCT ACCOUNTS FOR MEMBERS</B></font></center>
	<form action='savings.php' method=post>
	<table  border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' id='AutoNumber2' align=center>
		<tr><td>Member No:<input type='int' id='smem_no' name='smem_no' value='All'></td><td>Member Name:<input type='text' name='sname' id='sname' value='All'></td><td>Savings Product:<select name='product_id', id='product_id'><option value=''>All";
	$sth = mysql_query("select a.name as name, a.account_no as account_no, p.id as id from savings_product p join accounts a on p.account_id=a.id where a.name not like 'Compulsory Shares' and a.name not like 'Compulsory Savings' order by a.account_no, a.name");
	while($row = mysql_fetch_array($sth)){
		$content .="<option value='".$row['id']."'>".$row['account_no'] ." - ".$row['name'];
	}
	$content .="</td><td><input type='button' value='Search' onclick=\"xajax_list_accounts(getElementById('smem_no').value, getElementById('sname').value, getElementById('product_id').value); return false;\"></td></tr></table></form>
	<a href='list_accounts.php?mem_no=".$mem_no."&name=".$name."&product_id=".$product_id."' target=blank()><b>Printable Version</b></a>";
	$content .= "<table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width=70% id='AutoNumber2' align=center>";
	if($mem_no=='' && $name=='' && $product_type=='')
		$content .= "<tr><td><font color=red>Please enter your search criteria</font></td></tr>";
	else{
		$mem_no1 = ($mem_no == 'All') ? "" : $mem_no;
		$name1 = ($name == 'All') ? "" : $name;
		if($product_id == '')
			$product = " and s.id like '%%'";
		else
			$product =" and s.id='".$product_id."'";
	  	$sth = mysql_query("select m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, a.name as name, a.account_no as account_no, d.id as id, d.open_date as open_date, d.close_date as close_date from mem_accounts d left join member m on d.mem_id=m.id left join savings_product s on d.saveproduct_id=s.id join accounts a on s.account_id=a.id where (m.first_name like '%".$name1."%' or m.last_name like '%".$name1."%') and m.mem_no like '%".$mem_no1."%'".$product. " and a.name not in ('Compulsory Shares', 'Compulsory Savings') order by m.first_name, m.last_name");
		if(@ mysql_numrows($sth)==0)
			$content .= "<tr><td align=center><font color=red>No product accounts for the members selected</font></td></tr>";
		else{
			$content .= "<tr class='headings'><td><b>No</b></td><td><b>Member Name</b></td><td><b>Member No</b></td><td><b>Product</b></td><td><b>Date Opened</b></td><td><b>Balance</b></td><td><b>Status</b></td></tr>";
			$i=1;
			while($row = mysql_fetch_array($sth)){
				$status = (date( 'Y-m-d', strtotime($row['close_date'])) <= date('Y-m-d')) ? "Closed" : "Active";
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
				//INTEREST AWARDE
				$int = mysql_fetch_array(mysql_query("select sum(amount) as amount from save_interest where memaccount_id='".$row['id']."'"));
				$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;
				//LOAN REPAYMENTS
				$pay_res = mysql_query("select sum(princ_amt + int_amt) as amount from payment where mode='".$row['id']."'");
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

				$color = ($i%2 == 0) ? "white" : "lightgrey";
				$content .= "<tr><td>".$i."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".$row['account_no']." - ".$row['name']."</td><td>".$row['open_date']."</td><td>".$balance."</td><td>".$status."</td></tr>";
				$i++;
			}
		}
	}
	$content .= "</table>";
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}
*/

//LIST SAVINGS PRODUCT ACCOUNTS FOR MEMBERS
function list_accounts($group_name, $mem_no, $name, $product_id, $branch_id){
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	$branch_id = ($branch_id=='all' || $branch_id=='') ? NULL:'and d.branch_id='.$branch_id;
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR SAVINGS PRODUCT ACCOUNTS FOR MEMBERS</h3>
                                <p class="text-default nm"></p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Group Name:</label>
                                            <input type="varchar(250)" id="group_name" name="group_name" value="All" class="form-control">                                            
                                        </div>
                                        <div class="col-sm-6">
                                           <label class="control-label">Member No:</label>
                                           <input type="int" id="smem_no" name="smem_no" value="All" class="form-control">
                                        </div>
                                    </div>
                                </div>       
        
                        
                         <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Member Name:</label>
                                             <input type="text" name="sname" id="sname" value="All" class="form-control">                                             
                                        </div>
                                        <div class="col-sm-6">
                                           <label class="control-label">Savings Product:</label>
                                          <select name="product_id" id="product_id" class="form-control"><option value="">All';
	$sth = mysql_query("select a.name as name, a.account_no as account_no, d.id as id from  savings_product d join accounts a on d.account_id=a.id where a.name not like 'Compulsory Shares' and a.name not like 'Compulsory Savings' ".$branch_id." order by a.account_no, a.name");
	while($row = mysql_fetch_array($sth)){
		$content .="<option value='".$row['id']."'>".$row['account_no'] ." - ".$row['name'];
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
                                    
                                </div>';
		
		 $content .= "<div class='panel-footer'>                              
                               
                                <button type='button' class='btn  btn-primary'  onclick=\"xajax_list_accounts(getElementById('group_name').value, getElementById('smem_no').value, getElementById('sname').value, getElementById('product_id').value, getElementById('branch_id').value); return false;\">Search</button></diV>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div></div>";
                    
                //$resp->assign("display_div", "innerHTML", $content);
                	
	if($mem_no=='' && $name=='' && $product_id==''){		
		$cont = "<font color=red>Please enter your search criteria</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
	}else{
		$group_n = ($group_name == 'All') ? "(g.name is null or m.id in (select mem_id from group_member))" : "g.name like '%".$group_name."%'";
		$mem_no1 = ($mem_no == 'All') ? "" : $mem_no;
		$name1 = ($name == 'All') ? "" : $name;
		if($product_id == '')
			$product = " and s.id like '%%'";
		else
			$product =" and s.id='".$product_id."'";
	  	//$sth = mysql_query("select  m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, a.name as name, a.account_no as account_no, d.id as id, d.open_date as open_date, d.close_date as close_date from mem_accounts d left join member m on d.mem_id=m.id  left join savings_product s on d.saveproduct_id=s.id join accounts a on s.account_id=a.id where (m.first_name like '%".$name1."%' or m.last_name like '%".$name1."%') and m.mem_no like '%".$mem_no1."%'".$product. " and a.name not in ('Compulsory Shares', 'Compulsory Savings')  order by m.first_name, m.last_name");
		
		$sth2 = mysql_query("select g.name as group_name, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, a.name as name, a.account_no as account_no, d.id as id, d.open_date as open_date, d.close_date as close_date from mem_accounts d left join member m on d.mem_id=m.id  left join group_member gm on m.id=gm.mem_id left join loan_group g on gm.group_id=g.id left join savings_product s on d.saveproduct_id=s.id join accounts a on s.account_id=a.id where (m.first_name like '%".$name1."%' or m.last_name like '%".$name1."%') and m.mem_no like '%".$mem_no1."%'".$product. " and a.name not in ('Compulsory Shares', 'Compulsory Savings')  and ".$group_n." ".$branch_id." order by g.name, m.first_name, m.last_name");
		
		$sth3 = mysql_query("select g.name as group_name, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, a.name as name, a.account_no as account_no, d.id as id, d.open_date as open_date, d.close_date as close_date from mem_accounts d left join member m on d.mem_id=m.id  left join group_member gm on m.id=gm.mem_id left join loan_group g on gm.group_id=g.id left join savings_product s on d.saveproduct_id=s.id join accounts a on s.account_id=a.id where (m.first_name like '%".$name1."%' or m.last_name like '%".$name1."%') and m.mem_no like '%".$mem_no1."%'".$product. " and a.name not in ('Compulsory Shares', 'Compulsory Savings')  and ".$group_n." ".$branch_id." order by g.name, m.first_name, m.last_name ");
		//$max_page = ceil(mysql_num_rows($sth2)/$num_rows);

		if(@ mysql_numrows($sth2)==0){
			
			$cont = "<font color=red>No product accounts for the members selected.</font>";
		$resp->assign("status", "innerHTML", $cont);
	       // return $resp;
		}else{
			$content .= "<a href='export/accounts?group_name=".$group_name."&branch_id=".$branch_id."&mem_no=".$mem_no."&name=".$name."&product_id=".$product_id."' target=blank()><b>Printable Version</b></a> | <a href='export/accounts?group_name=".$group_name."&branch_id=".$branch_id."&mem_no=".$mem_no."&name=".$name."&product_id=".$product_id."&format=excel' target=blank()><b>Export Excel</b></a>";
						
			$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">SAVINGS PRODUCT ACCOUNTS FOR MEMBERS</h3>
                                
                                
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">
			<thead><th><b>No</b></th><th><b>Member Name</b></th><th><b>Member No</b></th><th><b>Product</b></th><th><b>Date Opened</b></th><th><b>Balance</b></th><th><b>Status</b></th></thead><tbody>';
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
						$content .= "<tr class='headings'><td colspan=5><b>GROUP: ".strtoupper($former)." SUB TOTAL</b></td><td colspan=3>".number_format($sub_total, 2)."</td></tr>";
						$overall_total += $sub_total;
						$sub_total =0;
					}

					$content .="<tr class='headings'><td colspan=8><b>GROUP: ".strtoupper($group_show)."</b></td></tr>";
					$former = $group_show;
				}
				$status = (date( 'Y-m-d', strtotime($row['close_date'])) <= date('Y-m-d')) ? "Closed" : "Active";
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
				//INTEREST AWARDE
				$int = mysql_fetch_array(mysql_query("select sum(amount) as amount from save_interest where memaccount_id='".$row['id']."'"));
				$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;
				//LOAN REPAYMENTS
				$pay_res = mysql_query("select sum(princ_amt + int_amt) as amount from payment where mode='".$row['id']."'");
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
				//$color=($i % 2==0) ? "lightgrey" : "white";
				$content .= "<tr><td>".$i."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".$row['account_no']." - ".$row['name']."</td><td>".$row['open_date']."</td><td>".number_format($balance, 2)."</td><td>".$status."</td></tr>";
				if($i == $num){
					$overall_total += $sub_total;
					$content .= "<tr class='headings'><td colspan=5><b>".strtoupper($former)." SUB TOTAL</b></td><td colspan=3><B>".number_format($sub_total, 2)."</B></td></tr>
					<tr class='headings'><td colspan=5><b> OVERALL TOTAL </b></td><td colspan=3><B>".number_format($overall_total, 2)."</B></td></tr>";
				}

				$i++;
			}
		}
	
	$content .= "</tbody></table></div>";
	$resp->call("createTableJs");
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}
//LIST DEPOSITS
function list_deposits($mem_no, $name, $product, $from_date,$to_date, $branch_id){
        list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
		$branch_res = mysql_query("select * from branch");

	$branch = mysql_fetch_array($branch_res);
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:'and d.branch_id='.$branch_id;
	
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">'.$branch['branch_name'].'</h3>
                                <p class="text-default nm">SEARCH FOR DEPOSITS</p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">By Member Name:</label>
                                            <input type="text" id="dname" name="dname" value="All" class="form-control">                                            
                                        </div>
                                        <div class="col-sm-6">
                                           <label class="control-label">OR Member No:</label>
                                           <input type="int" id="dmem_no" name="dmem_no" value="All" class="form-control">
                                        </div>
                                    </div>
                                </div>       
        
                        
                         <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Date range:</label>
                                              <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div> 
                                        </div>
                                        <div class="col-sm-6">
                                           <label class="control-label">Savings Product:</label>
                                          <select name="saveproduct_id", id="saveproduct_id" class="form-control"><option value="">All';
	$sth = mysql_query("select a.name as name, a.account_no as account_no, s.id as id from savings_product s left join accounts a on s.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."' ".$branch_);
	while($row = @mysql_fetch_array($sth)){
		$content .= "<option value='".$row['id']."'>".$row['account_no']." - ". $row['name'];
	}
	$content .= '</select>
                                        </div>
                                    </div>
                                </div>';                                    
                                   
                $content .='<div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6"> 
                                        <label class="control-label">Branch:</label>
                                        <span>'.branch_rep($branch_id).'</span>  
                                    </div>
                                   
                                </div>';
		
		 $content .= "<div class='panel-footer'>                              
                               
                                <button type='button' class='btn  btn-primary'  onclick=\"xajax_list_deposits(getElementById('dmem_no').value, getElementById('dname').value, getElementById('saveproduct_id').value, getElementById('from_date').value,getElementById('to_date').value,getElementById('branch_id').value); return false;\">Search</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
        $resp->call("createDate","from_date");
	$resp->call("createDate","to_date");
                    
               // $resp->assign("display_div", "innerHTML", $content);
	
	if($mem_no=='' && $name=='' && $product==''){
		$cont = "<font color=red>Please enter your search criteria.</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
	}else{
		$dname = ($name == 'All') ? "" : $name;
		$dmem_no = ($mem_no == 'All') ? "" : $mem_no;
		$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
		$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_year);

		$sth = mysql_query("select d.receipt_no as receipt_no, d.bank_account as bank_account, d.id as id, d.percent_value as percent_value, d.flat_value as flat_value, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, d.amount as amount, d.date as date, a.account_no as account_no, a.name as name from deposit d join mem_accounts mem on d.memaccount_id=mem.id join  member m on mem.mem_id=m.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where mem.saveproduct_id like '%".$product."%' and (m.mem_no like '%".$dmem_no."%' and (m.first_name like '%".$dname."%' or m.last_name like '%".$dname."%')) and d.date >= '".$from_date."' and d.date <= '".$to_date."' and s.type='free' ".$branch_." order by m.first_name, m.last_name, d.date");
	
	$sth2 = mysql_query("select d.receipt_no as receipt_no, d.bank_account as bank_account, d.id as id, d.percent_value as percent_value, d.flat_value as flat_value, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, d.amount as amount, d.date as date, a.account_no as account_no, a.name as name from deposit d join mem_accounts mem on d.memaccount_id=mem.id join  member m on mem.mem_id=m.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where mem.saveproduct_id like '%".$product."%' and (m.mem_no like '%".$dmem_no."%' and (m.first_name like '%".$dname."%' or m.last_name like '%".$dname."%')) and d.date >= '".$from_date."' and d.date <= '".$to_date."' and s.type='free' ".$branch_." order by m.first_name, m.last_name, d.date ");
		if(@mysql_numrows($sth)==0){
		$cont = "<font color=red>No deposits in the selected search criteria.</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
	}
		else{
		
		    $content .="<a href='export/deposits?mem_no=".$mem_no."&branch_id=".$branch_id."&name=".$name."&product=".$product."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."' target=blank()><b>Printable Version</b></a> | <a href='export/deposits?mem_no=".$mem_no."&branch_id=".$branch_id."&name=".$name."&product=".$product."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&format=excel' target=blank()><b>Export Excel</b></a>";
		    
			//////////$max_page = ceil(mysql_num_rows($sth)/$num_rows); 			
			 
                            $content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <p><h5 class="text-primary mt0">LIST OF DEPOSITS</h5></p>
                                
                                
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">
			
			<thead><th><b>Member Name</b></th><th><b>MemberNo</b></th><th><b>Amount</b></th><th><b>ReceiptNo</b></th><th><b>Percent Charge</b></th><th><b>Flat Charge</b></th><th><b>Date</b></th><th><b>Product</b></th><th><b>Bank Account</b></th></thead><tbody>';
			$i=$stat+1;
			while($row = @mysql_fetch_array($sth2)){
				$bank_res = mysql_query("select a.account_no as account_no, a.name as name from bank_account b join accounts a on b.account_id=a.id where b.id='".$row['bank_account']."'");
				$bank = mysql_fetch_array($bank_res);
				//$color=($i % 2==0) ? "lightgrey" : "white";
				$content .= "<tr><td>".$row['first_name']. " ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".$row['amount']."</td><td>".$row['receipt_no']."</td><td>".$row['percent_value']."</td><td>".$row['flat_value']."</td><td>".$row['date']."</td><td>".$row['account_no']." - ".$row['name']."</td><td>".$bank['account_no']." - ".$bank['name']."</td></tr>";
				$i++;
			}
		}
	
	$content .= "</tbody></table></div>";
	 $resp->call("createTableJs");
	 }
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//LIST WITHDRAWALS
function list_withdrawal($mem_no, $name, $product, $from_date, $to_date,$branch_id){
        $content='';
	$resp = new xajaxResponse();
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:'and w.branch_id='.$branch_id;
	$resp->assign("status", "innerHTML", "");
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR WITHDRAWALS</h3>
                                <p class="text-default nm"></p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">By Member Name:</label>
                                            <input type="text" id="dname" name="dname" value="All" class="form-control">                                            
                                        </div>
                                        <div class="col-sm-6">
                                           <label class="control-label">OR Member No:</label>
                                           <input type="int" id="dmem_no" name="dmem_no" value="All" class="form-control">
                                        </div>
                                    </div>
                                </div>       
        
                        
                         <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Date range:</label>
                                             <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                           <label class="control-label">Savings Product:</label>
                                          <select name="saveproduct_id" id="saveproduct_id" class="form-control"><option value="">All';
	$sth = mysql_query("select a.name as name, a.account_no as account_no, s.id as id from savings_product s left join accounts a on s.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."' ".$branch_);
	while($row = @mysql_fetch_array($sth)){
		$content .= "<option value='".$row['id']."'>".$row['account_no']." - ". $row['name'];
	}
	$content .= '</select>
                                        </div>
                                    </div>
                                </div>';                                    
                                   
                $content .='<div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6"> 
                                        <label class="control-label">Branch:</label>
                                        <span>'.branch_rep($branch_id).'</span>  
                                    </div>
                                    
                                </div>';
		
		 $content .= "<div class='panel-footer'>                              
                               
                                <button type='button' class='btn  btn-primary' onclick=\"xajax_list_withdrawal(getElementById('dmem_no').value, getElementById('dname').value, getElementById('saveproduct_id').value, getElementById('from_date').value,getElementById('to_date').value, getElementById('branch_id').value); return false;\">Search</button></div>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    
        $resp->call("createDate","from_date");
	$resp->call("createDate","to_date");
                    
                //$resp->assign("display_div", "innerHTML", $content);
                
               
	if($mem_no=='' && $name=='' && $product==''){
		$cont = "<font color=red>Please enter your search criteria.</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
	}
	else{
		$dname = ($name == 'All') ? "" : $name;
		$dmem_no = ($mem_no == 'All') ? "" : $mem_no;
		$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
		$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_year);

		$sth = mysql_query("select w.voucher_no as voucher_no, w.bank_account as bank_account, w.id as id, w.percent_value as percent_value, w.flat_value as flat_value, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, w.amount as amount, w.date as date, a.account_no as account_no, a.name as name from  withdrawal w join mem_accounts mem on w.memaccount_id=mem.id join  member m on mem.mem_id=m.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where mem.saveproduct_id like '%".$product."%' and (m.mem_no like '%".$dmem_no."%' and (m.first_name like '%".$dname."%' or m.last_name like '%".$dname."%')) and w.date >= '".$from_date."' and w.date <= '".$to_date."' and s.type='free' ".$branch_." order by m.first_name, m.last_name, w.date");
		$sth2 = mysql_query("select w.voucher_no as voucher_no, w.bank_account as bank_account, w.id as id, w.percent_value as percent_value, w.flat_value as flat_value, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, w.amount as amount, w.date as date, a.account_no as account_no, a.name as name from  withdrawal w join mem_accounts mem on w.memaccount_id=mem.id join  member m on mem.mem_id=m.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where mem.saveproduct_id like '%".$product."%' and (m.mem_no like '%".$dmem_no."%' and (m.first_name like '%".$dname."%' or m.last_name like '%".$dname."%')) and w.date >= '".$from_date."' and w.date <= '".$to_date."' and s.type='free' ".$branch_." order by m.first_name, m.last_name, w.date ");
		if(@mysql_numrows($sth)==0){
				
	         $cont = "<font color=red>No withdrawals in the selected search criteria.</font>";
		$resp->assign("status", "innerHTML", $cont);
	        return $resp;}
		else{
			////$max_page = ceil(mysql_num_rows($sth)/$num_rows);
			$content .="<a href='export/withdrawals?mem_no=".$mem_no."&branch_id=".$branch_id."&name=".$name."&product=".$product."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."' target=blank()><b>Printable Version</b></a> | <a href='export/withdrawals?mem_no=".$mem_no."&branch_id=".$branch_id."&name=".$name."&product=".$product."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&format=excel' target=blank()><b>Export Excel</b></a>";
			
			$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <p><h5 class="text-primary mt0">LIST OF WITHDRAWALS</h5></p>
                               
                               
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
			
			$content .= "<thead><th><b>Member Name</b></th><th><b>MemberNo</b></th><th><b>Amount</b></th><th><b>VoucherNo</b></th><th><b>Percent Charge</b></th><th><b>Flat Charge</b></th><th><b>Date</b></th><th><b>Product</b></th><th><b>Bank Account</b></th></thead><tbody>";
			$i=0;
			while($row = @mysql_fetch_array($sth2)){
				//$color= ($i%2 == 0) ? "lightgrey" : "white";
				$bank_res = mysql_query("select a.account_no as account_no, a.name as name from bank_account b join accounts a on b.account_id=a.id where b.id='".$row['bank_account']."'");
				$bank = mysql_fetch_array($bank_res);
				$content .= "<tr><td>".$row['first_name']. " ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".$row['amount']."</td><td>".$row['voucher_no']."</td><td>".$row['percent_value']."</td><td>".$row['flat_value']."</td><td>".$row['date']."</td><td>".$row['account_no']." - ".$row['name']."</td><td>".$bank['account_no']." - ".$bank['name']."</td></tr>";
				$i++;
			}
		}
	
	$content .= "</tbody></table></div>";
	$resp->call("createTableJs");
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//LIST LOAN PRODUCTS
function list_loanproducts(){
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
		
	$sth = mysql_query("select p.id as loanproduct_id, p.int_rate as int_rate, p.int_method as int_method, p.based_on as based_on,  p.grace_period as grace_period, p.arrears_period as arrears_period, p.loan_period as loan_period, p.writeoff_period as writeoff_period, p.max_loan_amt as max_loan_amt, a.id as account_id, a.name as name, a.account_no as account_no from loan_product p join accounts a on p.account_id=a.id order by a.account_no");
	if(@ mysql_numrows($sth) == 0){
		$cont = "<font color=red>No loan products created yet!</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
		}
	else{
	
		
		$content = '<a href="export/loanProducts" target==blank()><b>Printable Version</b></a> | <a href="export/loanProducts?format=excel" target==blank()><b>Export Excel</b></a>';
	
		$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <p><h5 class="text-primary mt0">LIST OF LOAN PRODUCTS</h5></p>
                            </div>
 		<table class="table table-striped table-bordered" id="table-tools">';
	
		$content .= "<thead><th><b>Product</b></th><th><b>Int. Rate (% Per Annum)</b></th><th><b>Method</b></th><th><b>Grace Period (Months)</b></th><th><b>Arrears Maturity (Months)</b></th><th><b>Loan Period</b></th><th><b>Max. Amount</b></th><th><b>Write-off Period (Months)</b></th><th><b>Based On</b></th></thead><tbody>";
		$i=0;
		while($row = mysql_fetch_array($sth)){
			$grace_period = $row['grace_period'] /30;
			$loan_period = $row['loan_period']/30;
			$arrears_period = $row['arrears_period']/30;
			$writeoff_period = $row['writeoff_period']/30;
			//$color= ($i%2 == 0) ? "lightgrey" : "white"; 
			$content .= "<tr><td>".$row['account_no'] ." - ".$row['name']."</td><td>".$row['int_rate']."</td><td>".$row['int_method']."</td><td>".$grace_period."</td><td>".$arrears_period."</td><td>".$loan_period."</td><td>".number_format($row['max_loan_amt'], 2)."</td><td>".$writeoff_period."</td><td>".ucfirst($row['based_on'])."</td></tr>";
			$i++;
		}
	}
	$content .= "</tbody></table></div>";
	$resp->call("createTableJs");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;

}


//LIST LOAN GROUPS
function list_groups($stuff){
	$resp = new xajaxResponse();
	
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">LIST OF GROUPS</h5>
                            </div>               
                            <div class="panel-body">';
                            
	$content .= '<div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Search By Group or Member:</label>
                                            <div class="input-group">
                                            <input type="text" id="stuff" name="stuff" value="All" class="form-control">
                                            <span class="input-group-btn">
                                                <button class="btn btn-info" type="button" onclick=\'xajax_list_groups(getElementById("stuff").value)\'>Search</button>
                                            </span>
                                        </div>                                             
                                        </div>';
                                        
                $content .= '</div></form></div></div>';	
	
	if($stuff ==''){
	
		$cont = "<font color=red>Select the groups you want to list!</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
	}else{
		//$stuff = ($stuff =='All') ? "" : $stuff;
		$sth = mysql_query("select distinct g.name as name, g.id as id from loan_group g join group_member gm on g.id=gm.group_id join member m on gm.mem_id=m.id where m.last_name like '%".$stuff."%' or m.first_name like '%".$stuff."%' or g.name like '%".$stuff."%'");
		if(@ mysql_numrows($sth) == 0){
		
			$cont = "<font color=red>No groups registered yet!</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
			}
		else{
		
		$content .= '<a href="export/groups?stuff='.$stuff.'" target=blank()><b>Printable Version</b></a> | <a href="export/groups?stuff='.$stuff.'&format=excel" target=blank()><b>Export Excel</b></a>';
 		
		$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <p><h5 class="semibold text-primary mt0 mb5">LIST OF GROUPS</h5></p>
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
 		
			$content .= "<thead><th><b>#</b></th><th><b>Group Name</b></th><th><b>Members</b></th></thead><tbody>";
			$x=1;
			while($row = mysql_fetch_array($sth)){
				////$color=($i % 2==0) ? "lightgrey" : "white";
				$content .= "<tr><td>".$x."</td><td>".$row['name']."</td><td>";
				$mem_res = mysql_query("select m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no from group_member gm join member m on gm.mem_id=m.id where gm.group_id='".$row['id']."' order by m.mem_no");
				$i=1;
				while($mem = mysql_fetch_array($mem_res)){
					$content .= $i.". ".$mem['mem_no']." - ".$mem['first_name']. "  ". $mem['last_name']."<br>";
					$i++;
				}
				$content .= "</td></tr>";
				$x++;
			}
		}
	
	$content .="</tbody></table></div>";
	$resp->call("createTableJs");
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//LIST LOAN APPLICATIONS
function list_applics($cust_name, $cust_no, $account_name, $from_date,$to_date,$category,$branch_id){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	$branch = ($branch_id=='all'||$branch_id=='')? NULL:" and applic.branch_id='".$branch_id."'";
	if($category=='all' || $category==''){
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
                                <h3 class="panel-title">SEARCH FOR&nbsp;'.$head.'</h3>
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
                                            '.branch_rep($branch_id).'
                                    </div>
                                </div>  </div>
                                
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Date Range:</label>
                                           <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" placeholder="to" /></div>
                                            </div> 
                                        </div>
                                    </div>
                                </div>                             
                                                                                                                                       
                            '; 
		
		 $content .= "<div class='panel-footer'>                              
                                
                                <input type='button' class='btn  btn-primary' value='Search' onclick=\"xajax_list_applics(document.getElementById('cust_name').value, document.getElementById('cust_no').value, document.getElementById('account_name').value, document.getElementById('from_date').value,document.getElementById('to_date').value, '".$category."', document.getElementById('branch_id').value);return false;\"></div>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div></div>";
                     $resp->call("createDate","from_date");
	             $resp->call("createDate","to_date");
		
		//$resp->assign("display_div", "innerHTML", $content);
	if($from_date=='' || $to_date==''){
		$cont = "<font color=red>Please select the applications you would like to list!</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
	
	
	$name = ($cust_name == 'All') ? "" : $cust_name;
	$mem_no = ($cust_no == 'All') ? "" : $cust_no;
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_year);
	
	$sth = mysql_query("select dis.id as disbursed_id, applic.group_id as group_id, applic.approved as approved, applic.reason as reason, (DATEDIFF(applic.date, m.dob)/365) as age, o.firstName as of_firstName, o.lastName as of_lastName, m.id as mem_id, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, applic.amount as amount, applic.date as date, applic.guarantor1 as guarantor1, applic.guarantor2 as guarantor2, applic.id as applic_id, a.name as account_name, a.account_no as account_no, m.id as mem_id, p.based_on as based_on from loan_applic applic join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id join employees o on applic.officer_id=o.employeeId left join disbursed dis on applic.id=dis.applic_id where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$choose." applic.date >='".$from_date."' and applic.date <= '".$to_date."' ".$branch." order by o.firstName, o.lastName, m.first_name, m.last_name, m.mem_no");

	////$max_page = ceil(mysql_num_rows($sth)/$num_rows);
	$sth2 = mysql_query("select dis.id as disbursed_id, applic.group_id as group_id, applic.approved as approved, applic.reason as reason, (DATEDIFF(applic.date, m.dob)/365) as age, o.firstName as of_firstName, o.lastName as of_lastName, m.id as mem_id, m.sign_name as sign_name, m.photo_name as photo_name, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, applic.amount as amount, applic.date as date, applic.guarantor1 as guarantor1, applic.guarantor2 as guarantor2, applic.id as applic_id, a.name as account_name, a.account_no as account_no, m.id as mem_id, p.based_on as based_on from loan_applic applic join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id join employees o on applic.officer_id=o.employeeId left join disbursed dis on applic.id=dis.applic_id where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') ".$branch." and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$choose." applic.date >='".$from_date."' and applic.date <= '".$to_date."' order by o.firstName, o.lastName, m.first_name, m.last_name, m.mem_no ");
	if(@ mysql_numrows($sth) == 0){
		$cont= "<font color=red>No applications found in your search options!</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
	
	
	$total__amount = mysql_fetch_assoc(mysql_query("select sum(applic.amount) as amountd from loan_applic applic join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id join employees o on applic.officer_id=o.employeeId left join disbursed dis on applic.id=dis.applic_id where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') ".$branch." and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$choose." applic.date >='".$from_date."' and applic.date <= '".$to_date."' order by o.firstName, o.lastName, m.first_name, m.last_name, m.mem_no"));
	$former_officer ="";
	$i=$stat+1;
	$amt_sub_total=0;
	while($row = mysql_fetch_array($sth2)){
		$officer = $row['of_firstName']." ".$row['of_lastName'];
		if(strcmp($former_officer, $officer) != 0){
		
			
			$content .="<a href='export/applications?cust_name=".$cust_name."&branch_id=".$branch_id."&cust_no=".$cust_no."&account_name=".$account_name."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&category=".$category."&num_rows=".$num_rows."&stat=".$stat."&cur_page=".$cur_page."' target=blank()><b>Printable Version</b></a> | <a href='export/applications?cust_name=".$cust_name."&branch_id=".$branch_id."&cust_no=".$cust_no."&account_name=".$account_name."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&category=".$category."&format=excel&num_rows=".$num_rows."&stat=".$stat."&cur_page=".$cur_page."' target=blank()><b>Export Excel</b></a>";
			
			$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                            <p><h4 class="semibold text-primary mt0 mb5">LIST OF&nbsp;'.$head.'</h4></p>
                                </p><h5 class="text-primary mt0">Loan Officer:  '.$officer.'</h5><p>
                                <p>Total Amount: '.number_format($total__amount['amountd'],2).'</p>
                                
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
 		$content .= '<thead><th>#</th><th>NAME</th><th>MEMBER No.</th><th>AGE</th><th>PRODUCT</th><th>AMOUNT</th><th>REASON</th><th>COLLATERAL</th><th>GUARANTORS</th><th>DATE</th><th>LOAN BALANCE</th><th>POSSIBLE LOAN ON SHARES</th><th>POSSIBLE LOAN SAVINGS</th><th>STATUS</th></thead><tbody>';
			$former_officer = $officer;
		}
		$col_res = mysql_query("select * from collateral where applic_id='".$row['applic_id']."'");
		$col = @mysql_fetch_array($col_res);
		if($col['collateral1'] == NULL){
			$collateral1 = 0;
			$value1 = 0;
		}else{
			$collateral1 = $col['collateral1'];
			$value1 = "(".$col['value1'].")";
		}

		if($col['collateral2'] == NULL){
			$collateral2 = 0;
			$value2 = 0;
		}else{
			$collateral2 = $col['collateral2'];
			$value2 = "(".$col['value2'].")";
		}
		$sub = "<table><tr><td>".$collateral1."</td><td>".$value1."</td></tr> <tr><td>".$collateral2." </td><td>".$value2."</td></tr></table>";
		$x=1;
		if($row['group_id'] > 0){
			$list = $row['guarantor1'] .", ".$row['guarantor2'];
			$guarantors =explode(', ', $list);
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
		$loan_res = mysql_query("select sum(l.balance) as balance from disbursed l join loan_applic applic on lapplic_id=applic.id where applic_mem_id='".$row['mem_id']."' and l.balance >0");
		$loan = @mysql_fetch_array($loan_res);
		$loan_balance = ($loan['balance'] == NULL) ? 0 : $loan['balance'];
		//MAX PERCENT OF SAVINGS OR SHARES THAT CAN BE LOAN
		$limit_res = mysql_query("select * from branch");
		$limit = mysql_fetch_array($limit_res);
		//POSSIBLE LOAN ON SAVINGS FOR THIS MEMBER
		$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.secure_loan='yes' and m.mem_id='".$row['mem_id']."'");
		$dep = mysql_fetch_array($dep_res);
		$dep_amt = ($dep['amount'] == NULL) ? 0 : $dep['amount'];

		$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.secure_loan='yes' and m.mem_id='".$row['mem_id']."'");
		$with = mysql_fetch_array($with_res);
		$with_amt = ($with['amount'] == NULL) ? 0 : $with['amount'];

		$pledged_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.secure_loan='no' and m.mem_id='".$row['mem_id']."' and p.account_id in (select pledged_account_id from savings_product)");
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

		//$balance = $dep_amt + $int_amt - $with_amt - $pledged_amt - $charge_amt;
		$possible_savings_loan = ($balance * $limit['loan_save_percent']) / 100;
		
		//POSSIBLE LOAN ON SHARES	
		$shares_res = mysql_query("select sum(value) as value from shares where mem_id='".$row['mem_id']."'");
		$shares = @mysql_fetch_array($shares_res);
		$shares_amt = ($shares['value'] == NULL) ? 0 : $shares['amount']; 
			
		$donated_res = mysql_query("select sum(value) as value from share_transfer where to_id='".$row['mem_id']."'");
		$donated = @mysql_fetch_array($donated_res);
		$donated_amt = ($donated['value'] == NULL) ? 0 : $donated['value'];

		$trans_res = mysql_query("select sum(value) as value from share_transfer where from_id='".$row['mem_id']."'");
		$trans = @mysql_fetch_array($trans_res);
		$trans_amt = ($trans['value'] == NULL) ? 0 : $trans['value'];

		$pledged_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where a.name='Compulsory Shares' and mem.mem_id='".$row['mem_id']."'");
		$pledged = @mysql_fetch_array($pledged_res);
		$pledged_amt = ($pledged['amount'] == NULL) ? 0 : $pledged['amount'];

		$balance = $shares_amt + $donated_amt - $trans_amt - $pledged_amt;
		$possible_shares_loan = ($balance * $limit['loan_share_percent']) / 100;
		
		//PASS LIMITS TO DISBURSEMENT MODULE
		if($row['based_on'] == 'savings')
			$possible_amt = $possible_savings_loan;
		else
			$possible_amt = $possible_shares_loan;
		if($row['disbursed_id'] != NULL){
			$approving = "Disbursed";
			$edit = "Disbursed";
		}elseif($row['approved'] == '0')
			$approving = "Pending Approval";
		else
			$approving = "Pending Disbursement</a>";
		if($row['disbursed_id'] == NULL){
			$edit = "<a href='javascript:;' onclick=\"xajax_edit_applic('".$row['applic_id']."');\">Edit/ Collateral</a>";		
		}
		if($row['disbursed_id'] == NULL && $row['approved'] ==1)
			$disburse="<a href='javascript:;' onclick=\"xajax_add_disbursed('".$row['applic_id']."', '".$row['amount']."', '".$possible_amt."', '".$row['based_on']."');\">Disburse</a>";
		else
			$disburse = 0.0;

		////$color=($i % 2==0) ? "lightgrey" : "white";
		$content .= "<tr><td>".$i."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".floor($row['age'])."</td><td>".$row['account_no']." - ".$row['account_name']."</td><td>".$row['amount']."</td><td>".$row['reason']."</td><td>".$sub."</td><td>".$guar."</td><td>".$row['date']."</td><td>".number_format($loan_balance, 2)."</td><td>".number_format($possible_shares_loan, 2)."</td><td>".number_format($possible_savings_loan, 2)."</td><td>".$approving."</td></tr>";
		$i++;
		$amt_sub_total += $row['amount'];
	}
	$content .= "<tr><td></td><td></td><td></td><td></td><td><b>TOTAL</b></td><td></td><td></td><td></td><td></td><td><b>".number_format($amt_sub_total, 2)."</b></td><td></td><td></td><td></td><td></td><td></td></tr>";
	$content .= "</tbody></table></div>";
	 $resp->call("createTableJs");
	 }
	 }
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


//LIST DISBURSEMENTS
function list_disbursed($cust_name, $cust_no, $account_name, $loan_officer, $from_date,$to_date,$branch_id){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	$branch_res = mysql_query("select * from branch");
	$branch = mysql_fetch_array($branch_res);
	$branch_ = ($branch_id=='all'||$branch_id=='') ? "" : " and applic.branch_id='".$branch_id."'";
			
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title"><b>'.$branch['branch_name'].'</b></h3>
                                <p class="text-default nm">LIST OF LOANS DISBURSED</p>
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
	$officer_res = mysql_query("select * from employees order by first_name, last_name");
	while($officer = mysql_fetch_array($officer_res)){
		$content .= "<option value='".$officer['id']."'>".$officer['first_name']." ".$officer['last_name'];
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
                                
                               <button type='button' class='btn  btn-primary'  onclick=\"xajax_list_disbursed(getElementById('cust_name').value, getElementById('cust_no').value, getElementById('account_name').value, getElementById('officer_id').value,  getElementById('from_date').value,  getElementById('to_date').value,  getElementById('branch_id').value);\">Search</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","from_date");
	$resp->call("createDate","to_date"); 
		//$resp->assign("display_div", "innerHTML", $content);
		
	if($from_date=='' && $to_date==''){
		$cont = "<font color=red>Please select the disbursed loans you would like to list!</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
	$name = ($cust_name == 'All') ? "" : $cust_name;
	$mem_no = ($cust_no == 'All') ? "" : $cust_no;
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_year);
	if($loan_officer >0)
		$officer = "o.employeeId='".$loan_officer."'";
	else
		$officer = "o.employeeId > 0";
	
	$sth = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.cheque_no as cheque_no, d.date as date, d.balance as balance, d.period as loan_period, d.int_rate as int_rate, d.int_method as int_method, d.grace_period as grace_period, d.writeoff_period as writeoff_period, d.arrears_period as arrears_period, d.written_off as written_off, d.bank_account as bank_account from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' ".$branch_." order by o.firstName, o.lastName, d.cheque_no, m.first_name, m.last_name");
	
	////$max_page = ceil(mysql_num_rows($sth)/$num_rows);
	$sth2 = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.cheque_no as cheque_no, d.date as date, d.balance as balance, d.period as loan_period, d.int_rate as int_rate, d.int_method as int_method, d.grace_period as grace_period, d.writeoff_period as writeoff_period, d.arrears_period as arrears_period, d.written_off as written_off, d.bank_account as bank_account from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' ".$branch_." order by o.firstName, o.lastName, d.cheque_no, m.first_name, m.last_name ");
	if(@ mysql_num_rows($sth) == 0){
		$cont = "<font color=red>No disbursed loans in your search options</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	$total__amount =mysql_fetch_assoc(mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' ".$branch_." order by o.firstName, o.lastName, d.cheque_no, m.first_name, m.last_name")); 
	$former_officer ="";
	$i=$stat+1;
	$amt_sub_total=0;
	while($row = mysql_fetch_array($sth2)){
		$officer = $row['of_firstName']." ".$row['of_lastName'];
		if(strcmp($former_officer, $officer) != 0){
		
		$content .= "<a href='export/disbursed?cust_name=".$cust_name."&branch_id=".$branch_id."&cust_no=".$cust_no."&account_name=".$account_name."&loan-officer=".$loan_officer."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."' target=blank()><b>Printable Version</b></a> | <a href='export/disbursed?cust_name=".$cust_name."&branch_id=".$branch_id."&cust_no=".$cust_no."&account_name=".$account_name."&loan-officer=".$loan_officer."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&format=excel' target=blank()><b>Export Excel</b></a>";
			
			$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">Loan Officer:  '.$officer.'</h3>
                                <p>Total Amount Disbursed: '.number_format($total__amount['amount'],2).'</p>
                                
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
 		$content .= '<thead><th>#</th><th>DATE</th><th>MEMBER NO.</th><th>MEMBER NAME</th><th>PRODUCT</th><th>CHEQUE NO.</th><th>AMOUNT</th><th>ANNUAL INT. RATE(%)</th><th>METHOD</th><th>LOAN PERIOD (MONTHS)</th><th>GRACE PERIOD (MONTHS)</th><th>ARREARS PERIOD</th><th>WRITE-OFF PERIOD (MONTHS)</th><th>DISBURSEMENT ACCOUNT</th></thead><tbody>';
			$former_officer = $officer;
		}
		if($row['balance'] != $row['amount'] && $row['written_off']==0)
			$edit = "Payment Started";
		elseif($row['written_off'] == '1')
			$edit = "Written Off";
		else
			$edit = "<a href='javascript:;' onclick=\"xajax_edit_disbursed('".$row['id']."', '".$row['applic_id']."');\">Edit</a>";
		$bank_res = mysql_query("select a.name as account_name, a.account_no as account_no from bank_account b join accounts a on b.account_id=a.id where b.id='".$row['bank_account']."'");
		$bank = mysql_fetch_array($bank_res);
		//$color=($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr><td>".$i."</td><td>".$row['date']."</td><td>".$row['mem_no']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['account_no']." - ".$row['account_name']."</td><td>".$row['cheque_no']."</td><td>".number_format($row['amount'], 2)."</td><td>".$row['int_rate']."</td><td>".$row['int_method']."</td><td>".($row['loan_period']/30)."</td><td>".($row['grace_period']/30)."</td><td>".($row['arrears_period']/30)."</td><td>".($row['writeoff_period']/30)."</td><td>".$bank['account_no']. " - ".$bank['account_name']."</td></tr>";
		$i++;
		$amt_sub_total += $row['amount']; 
	}
	$content .= "<tr><td></td><td></td><td></td><td></td><td><b>TOTAL</b></td><td></td><td></td><td></td><td></td><td></td><td><b>".number_format($amt_sub_total, 2)."</b></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table></div>";
	}
	$resp->call("createTableJs");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//AGEING REPORT
function ageing($cust_name, $cust_no, $account_name, $loan_officer, $from_date,$to_date, $branch_id){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	$branch_res = mysql_query("select * from branch");
	$branch = mysql_fetch_array($branch_res);
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:'and d.branch_id='.$branch_id;
		
	
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title"><b>'.$branch['branch_name'].'</b></h3>
                                <p class="text-default nm">SEARCH FOR AGEING REPORT</p>
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
	$officer_res = mysql_query("select * from employees order by first_name, last_name");
	while($officer = mysql_fetch_array($officer_res)){
		$content .= "<option value='".$officer['id']."'>".$officer['first_name']." ".$officer['last_name'];
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
                                
                                <button type='button' class='btn  btn-primary' onclick=\"xajax_ageing(getElementById('cust_name').value, getElementById('cust_no').value, getElementById('account_name').value, getElementById('officer_id').value,  getElementById('from_date').value,  getElementById('to_date').value,getElementById('branch_id').value);\">Search</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                     $resp->call("createDate","from_date");
	             $resp->call("createDate","to_date");
                //$resp->assign("display_div", "innerHTML", $content);		
		
	if($from_date=='' || $to_date=''){
		$cont = "<font color=red>Please select the Period of the disbursed loans you would like to list!</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
	$name = ($cust_name == 'All') ? "" : $cust_name;
	$mem_no = ($cust_no == 'All') ? "" : $cust_no;
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_year);
	if($loan_officer >0)
		$officer = "o.employeeId='".$loan_officer."'";
	else
		$officer = "o.employeeId > 0";
	
	$sth = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, d.int_rate as int_rate, d.int_method as int_method, d.grace_period as grace_period, d.writeoff_period as writeoff_period, d.arrears_period as arrears_period, d.written_off as written_off, d.bank_account as bank_account from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.written_off='0' ".$branch_." order by o.firstName, o.lastName, m.first_name, m.last_name");
	
	$sth2 = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, d.int_rate as int_rate, d.int_method as int_method, d.grace_period as grace_period, d.writeoff_period as writeoff_period, d.arrears_period as arrears_period, d.written_off as written_off, d.bank_account as bank_account from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.written_off='0' ".$branch_." order by o.firstName, o.lastName, m.first_name, m.last_name ");
	if(@ mysql_numrows($sth) == 0){
		$cont = "<font color=red>No disbursed loans in your search options</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	////$max_page = ceil(mysql_num_rows($sth)/$num_rows);
	$former_officer ="";
	$i=$stat+1;
	while($row = mysql_fetch_array($sth2)){
		$officer = $row['of_firstName']." ".$row['of_lastName'];
		if(strcmp($former_officer, $officer) != 0){
		
		$content.="<a href='export/ageing?cust_name=".$cust_name."&branch_id=".$branch_id."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&status=".$status."' target=blank()><b>Printable Version</b></a> | <a href='export/ageing?cust_name=".$cust_name."&branch_id=".$branch_id."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&status=1&format=excel' target=blank()><b>Export Excel</b></a>";
		
		        $content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                            <p><h4 class="semibold text-primary mt0 mb5">AGEING REPORT</h4></p>
                                <p><h5 class="text-primary mt0">Loan Officer:  '.$officer.'</h5></p>
                                
                                
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
		
						
			$content .= '<thead><th><b>#</b></th><th><b>Member Name</b></th><th><b>MemberNo</b></th><th><b>Date Awarded</b></th><th><b>Date Last Payment Due</b></th><th ><b>Interest Paid Up To</b></th><th><b>No. of  Annual Payments</b></th><th><b>Annual Int. Rate(%)</b></th><th><b>Amount Awarded</b></th><th><b> Outstanding Balance</b></th><th><b>Deliq. Payments</b></th><th ><b>Non Deliquent</b></th><th><b>AA Deliquent Ranges</b><th><b>A 1 Day - 1 Month</b></th><th><b>B 2-3 Months</b></th><th><b>C 4-6 Months</b></th><th><b>D 7-9 Months</b></th><th><b>E 10-12 Months</b></th><th><b>F >12 Months</b></th></th></thead><tbody>
			<tr class="headings"></tr>';
			$former_officer = $officer;
		}
		if($row['balance'] != $row['amount'] && $row['written_off']==0)
			$edit = "Payment Started";
		elseif($row['written_off'] == '1')
			$edit = "Written Off";
		else
			$edit = "<a href='javascript:;' onclick=\"xajax_edit_disbursed('".$row['id']."', '".$row['applic_id']."');\">Edit</a>";
		$bank_res = mysql_query("select a.name as account_name, a.account_no as account_no from bank_account b join accounts a on b.account_id=a.id where b.id='".$row['bank_account']."'");
		$bank = mysql_fetch_array($bank_res);
		$last_res = mysql_query("select date as last_date from schedule where loan_id='".$row['id']."' order by date desc limit 1");
		$last = mysql_fetch_array($last_res);
		$paid_res = mysql_query("select sum(int_amt) as int_amt from payment where loan_id='".$row['id']."'");
		$paid = mysql_fetch_array($paid_res);
		$int_paid = ($paid['int_amt'] == NULL) ? 0 : $paid['int_amt'];
		$annual_payments = ($row['loan_period']/30 <12) ? $row['loan_period']/30 : 12;

		//DELIQUENT PAYMENTS
		$deliquent = 0;
		$non_deliquent=0;
		$paid_res = mysql_query("select p.date as date, p.princ_amt as princ_amt from payment p join disbursed d on p.loan_id=d.id where p.loan_id='".$row['id']."' order by p.date asc");
		while($paid = mysql_fetch_array($paid_res)){
			$sched_res = mysql_query("select sum(princ_amt) as princ_amt from schedule where loan_id='".$row['id']."' and date < '".$paid['date']."'");
			$sched = mysql_fetch_array($sched_res);
			$sched_amt = ($sched['princ_amt'] == NULL) ? 0 : $sched['princ_amt'];
			$tot_res = mysql_query("select sum(princ_amt) as princ_amt from payment where loan_id='".$row['id']."' and date < '".$paid['date']."'");
			$tot = mysql_fetch_array($tot_res);
			$tot_amt = ($tot['princ_amt'] == NULL) ? 0 : $tot['princ_amt'];
			$arrears_amt = $sched_amt - $tot_amt;
			if($arrears_amt > 0){
				if($arrears_amt > $paid['princ_amt'])
					$portion = $paid['princ_amt'];
				else{
					$portion = $arrears_amt;
					$non_deliquent = $non_deliquent + $paid['princ_amt'] - $arrears_amt;	
				}
				$deliquent = $deliquent + $portion;
			}else
				$non_deliquent = $non_deliquent + $paid['princ_amt'];
		}
		//DELIQUENT RANGES
		$paid_amt = $row['amount'] - $row['balance'];
		$sched_res = mysql_query("select sum(princ_amt) as princ_amt from schedule where loan_id='".$row['id']."' and date <= CURDATE()");
		$sched = mysql_fetch_array($sched_res);
		$sched_amt = ($sched['princ_amt'] != NULL) ? $sched['princ_amt'] : 0;
		$arrears_amt = $sched_amt - $paid_amt; 
		//$resp->alert($arrears_amt);
		$range1 = 0;
		$range2 = 0;
		$range3 = 0;  
		$range4 = 0;
		$range5 = 0;
		$range6 = 0;
		//OFFSET MONTHS
		$offset_res = mysql_query("select (DATEDIFF(CURDATE(), date) / 30) as months_off from schedule where loan_id=".$row['id']."  order by date desc limit 1");
		$offset = mysql_fetch_array($offset_res);
		$offset = floor($offset['months_off']);
		if($arrears_amt >0){
			$instalment = ceil($row['amount'] / ($row['loan_period']/ 30));
			$no = ceil($arrears_amt / $instalment);
			//$no += $offset;
			//$remainder = $arrears_amt % $instalment;
			if($offset >= 12){
				$range6 = $arrears_amt;
			}else{
				if($no >= 13)
					$range6 = $arrears_amt - (12 * $instalment);
				if($no >= 10)
					$range5 = $arrears_amt - $range6 - (9 * $instalment);
				if($no >= 7)
					$range4 =$arrears_amt - $range6 -$range5 - (6 * $instalment);
				if($no >= 4)
					$range3 = $arrears_amt - $range6 - $range5 - $range4 - (3 * $instalment);
				if($no >=2)
					$range2 = $arrears_amt - $range6 - $range5 - $range4 - $range3 - 1* $instalment;
				if($no >= 1){
					$range1 = ($instalment > $arrears_amt) ? $arrears_amt : $instalment;
				}
				if($offset >=1 && $offset <= 3){
					$range6 += $range5;
					$range5 = $range4;
					$range4 = $range3;
					$range3 = $range2;
					$range2 = $range1;
					$range1 = 0;
				}
				if($offset >=4 && $offset <= 5){
					$range6 = $range6 + $range5 + $range4;
					$range5 = $range3;
					$range4 = $range2;
					$range3 = $range1;
					$range2 = 0;
					$range1 = 0;
				}
				if($offset >=6 && $offset <= 8){
					$range6 = $range6 + $range5 + $range4 + $range3;
					$range5 = $range2;
					$range4 = $range1;
					$range3 = 0;
					$range2 = 0;
					$range1 = 0;
				}
				if($offset >=9 && $offset <= 11){
					$range6 = $range6 + $range5 + $range4 + $range3 + $range2;
					$range5 = $range1;
					$range4 = 0;
					$range3 = 0;
					$range2 = 0;
					$range1 = 0;
				}
			}
		}
		////$color=($i % 2==0) ? "lightgrey" : "white";
		$content .= "<tr><td>".$i."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".$row['date']."</td><td>".$last['last_date']."</td><td>".number_format($int_paid, 2)."</td><td>".$annual_payments."</td><td>".$row['int_rate']."</td><td>".number_format($row['amount'], 2)."</td><td>".number_format($row['balance'], 2)."</td><td>".number_format($deliquent, 2)."</td><td>".number_format(($row['balance'] - $deliquent), 2)."</td><td>".$range1."</td><td>".number_format($range2, 2)."</td><td>".number_format($range3, 2)."</td><td>".number_format($range4, 2)."</td><td>".number_format($range5, 2)."</td><td>".number_format($range6, 2)."</td></tr>";
		$i++;
	}
	$content .="</tbody></table></div>";
	}
	$resp->call("createTableJs");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//PORTFOLIO AT RISK BY AGEING  REPORT
function risk_ageing($cust_name, $cust_no, $account_name, $loan_officer, $from_date,$to_date,$branch_id){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:'and d.branch_id='.$branch_id;
	
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR PORTFOLIO AT RISK BY AGEING REPORT</h3>
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
	$officer_res = mysql_query("select * from employees order by first_name, last_name");
	while($officer = mysql_fetch_array($officer_res)){
		$content .= "<option value='".$officer['id']."'>".$officer['first_name']." ".$officer['last_name'];
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
                                
                                <button type='button' class='btn  btn-primary'  onclick=\"xajax_risk_ageing(getElementById('cust_name').value, getElementById('cust_no').value, getElementById('account_name').value, getElementById('officer_id').value,  getElementById('from_date').value,  getElementById('to_date').value, getElementById('branch_id').value);\">Search</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                     $resp->call("createDate","from_date");
	             $resp->call("createDate","to_date");
               // $resp->assign("display_div", "innerHTML", $content);
              		
	if($from_date=='' || $to_date==''){
		$cont = "<font color=red>Please select the disbursed loans you would like to list!</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
	$name = ($cust_name == 'All') ? "" : $cust_name;
	$mem_no = ($cust_no == 'All') ? "" : $cust_no;
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_year);
	if($loan_officer >0)
		$officer = "o.employeeId='".$loan_officer."'";
	else
		$officer = "o.employeeId > 0";
	
	$sth = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, d.int_rate as int_rate, d.int_method as int_method, d.grace_period as grace_period, d.writeoff_period as writeoff_period, (DATEDIFF(CURDATE(), d.last_pay_date)/30) as since_period, d.arrears_period as arrears_period, d.written_off as written_off, d.bank_account as bank_account from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.written_off='0' ".$branch_." order by o.firstName, o.lastName, m.first_name, m.last_name");
	
	////$max_page = ceil(mysql_num_rows($sth)/$num_rows);
	$sth2 = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, d.int_rate as int_rate, d.int_method as int_method, d.grace_period as grace_period, d.writeoff_period as writeoff_period, (DATEDIFF(CURDATE(), d.last_pay_date)/30) as since_period, d.arrears_period as arrears_period, d.written_off as written_off, d.bank_account as bank_account, d.last_pay_date as last_pay_date, d.pay_freq as pay_freq from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.written_off='0' ".$branch_." order by o.firstName, o.lastName, m.first_name, m.last_name ");
	if(@ mysql_numrows($sth) == 0){
		$cont = "<font color=red>No disbursed loans in your search options</font>";
		$resp->assign("status", "innerHTML", $cont);
		return $resp;
	}
	
	$former_officer ="";
	$i=$stat+1;
	while($row = mysql_fetch_array($sth2)){
		$officer = $row['of_firstName']." ".$row['of_lastName'];
		if(strcmp($former_officer, $officer) != 0){
		
		$content .="<a href='export/risk_ageing?cust_name=".$cust_name."&branch_id=".$branch_id."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&status=".$status."' target=blank()><b>Printable Version</b></a> | <a href='export/risk_ageing?cust_name=".$cust_name."&branch_id=".$branch_id."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&status=".$status."&format=excel' target=blank()><b>Export Excel</b></a>";
		
			$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                            <p><h4 class="semibold text-primary mt0 mb5">PORTFOLIO AT RISK BY AGEING REPORT</h4></p>
                                <p><h5 class="text-primary mt0">Loan Officer:  '.$officer.'</h5></p>
                                
                                
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">
			
			
			
			
			<tr><td rowspan=2><b>No</b></td><td rowspan=2><b>MemberNo</b></td><td rowspan=2><b>Member Name</b></td><td rowspan=2><b>Date Disbursed</b></td><td rowspan=2><b>Amount Disbursed</b></td><td rowspan=2><b>Portfolio At Risk</b></td><td colspan=6><b>AA Deliquent Ranges</b></td><td rowspan=2><b>Principal Outstanding</b></td><td rowspan=2><b>Interest Outstanding</b></td><td rowspan=2><b>Total Outstanding</b></td></tr>
			<tr><td><b>A 1 Day - 1 Month</b></td><td><b>B 2-3 Months</b></td><td><b>C 4-6 Months</b></td><td><b>D 7-9 Months</b></td><td><b>E 10-12 Months</b></td><td><b>F >12 Months</b></td></tr>';
			
			$former_officer = $officer;
		}
		//RESCHEDULE INTEREST FOR DECLINING BALANCE METHOD
		if($row['int_method'] == 'Declining Balance'){
			$sched_res = mysql_query("select id from schedule where loan_id='".$row['id']."' and princ_amt>0 and date> '".$row['last_pay_date']."' and date <= '".$to_date."'");
			while($sched = mysql_fetch_array($sched_res)){
				$new_int = $row['balance'] * (($row['int_rate']/100)/12) * ($row['pay_freq']/30);
				mysql_query("update schedule set int_amt ='".$new_int."' where id='".$sched['id']."'");
			}
		}

		//DELIQUENT RANGES
		$paid_amt = $row['amount'] - $row['balance'];
		$sched_res = mysql_query("select sum(princ_amt) as princ_amt from schedule where loan_id='".$row['id']."' and date <= CURDATE()");
		$sched = mysql_fetch_array($sched_res);
		$sched_amt = ($sched['princ_amt'] != NULL) ? $sched['princ_amt'] : 0;
		$arrears_amt = $sched_amt - $paid_amt; 
		$range1 = 0;
		$range2 = 0;
		$range3 = 0;  
		$range4 = 0;
		$range5 = 0;
		$range6 = 0;
		//OFFSET MONTHS
		$offset_res = mysql_query("select (DATEDIFF(CURDATE(), date) / 30) as months_off from schedule where loan_id=".$row['id']."  order by date desc limit 1");
		$offset = mysql_fetch_array($offset_res);
		$offset = floor($offset['months_off']);
		if($arrears_amt >0){
			$instalment = ceil($row['amount'] / ($row['loan_period']/ 30));
			$no = ceil($arrears_amt / $instalment);
			//$no += $offset;
			//$remainder = $arrears_amt % $instalment;
			if($offset >= 12){
				$range6 = $arrears_amt;
			}else{
				if($no >= 13)
					$range6 = $arrears_amt - (12 * $instalment);
				if($no >= 10)
					$range5 = $arrears_amt - $range6 - (9 * $instalment);
				if($no >= 7)
					$range4 =$arrears_amt - $range6 -$range5 - (6 * $instalment);
				if($no >= 4)
					$range3 = $arrears_amt - $range6 - $range5 - $range4 - (3 * $instalment);
				if($no >=2)
					$range2 = $arrears_amt - $range6 - $range5 - $range4 - $range3 - 1* $instalment;
				if($no >= 1){
					$range1 = ($instalment > $arrears_amt) ? $arrears_amt : $instalment;
				}
				if($offset >=1 && $offset <= 3){
					$range6 += $range5;
					$range5 = $range4;
					$range4 = $range3;
					$range3 = $range2;
					$range2 = $range1;
					$range1 = 0;
				}
				if($offset >=4 && $offset <= 5){
					$range6 = $range6 + $range5 + $range4;
					$range5 = $range3;
					$range4 = $range2;
					$range3 = $range1;
					$range2 = 0;
					$range1 = 0;
				}
				if($offset >=6 && $offset <= 8){
					$range6 = $range6 + $range5 + $range4 + $range3;
					$range5 = $range2;
					$range4 = $range1;
					$range3 = 0;
					$range2 = 0;
					$range1 = 0;
				}
				if($offset >=9 && $offset <= 11){
					$range6 = $range6 + $range5 + $range4 + $range3 + $range2;
					$range5 = $range1;
					$range4 = 0;
					$range3 = 0;
					$range2 = 0;
					$range1 = 0;
				}
			}
		}

		//ARREARS
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id='".$row['id']."' and date < DATE_SUB(CURDATE(), INTERVAL ".$row['arrears_period']." DAY)");
		$sched = @mysql_fetch_array($sched_res);
	
		$paid_res = mysql_query("select sum(princ_amt) as princ_amt, sum(int_amt) as int_amt from payment where loan_id='".$row['id']."' and date <= CURDATE()");
		
		$paid = mysql_fetch_array($paid_res);
		$sched_amount = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? 0 : $sched['int_amt'];
		$int_paid_amt = ($paid['int_amt'] == NULL) ? 0 : $paid['int_amt'];
		$int_arrears = $sched_int - $int_paid_amt;
		
		if($int_arrears <= 0){
			$outstanding_int = 0;
		}else{
			$outstanding_int = $int_arrears;
		}
		
		$outstanding_int = ceil($outstanding_int);
		$outstanding_int = ($outstanding_int > 0) ? $outstanding_int : 0;
		$total_outstanding = $row['balance'] + $outstanding_int;
		////$color=($i % 2==0) ? "lightgrey" : "white";
		$content .= "<tr><td>".$i."</td><td>".$row['mem_no']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['date']."</td><td>".number_format($row['amount'], 2)."</td><td>".number_format($row['balance'], 2)."</td><td>".number_format($range1, 2)."</td><td>".number_format($range2, 2)."</td><td>".number_format($range3, 2)."</td><td>".number_format($range4, 2)."</td><td>".number_format($range5, 2)."</td><td>".number_format($range6, 2)."</td><td>".$row['balance']."</td><td>".number_format($outstanding_int, 2)."</td><td>".number_format($total_outstanding, 2)."</td></tr>";
		$i++;
	}
	$content .= "</table></div>";
	}
	$resp->call("createTableJs");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}



//LIST OUTSTANDING
function list_outstanding($cust_name, $cust_no, $account_name, $loan_officer, $from_date,$to_date,$branch_id){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$resp->assign("status", "innerHTML", "");
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:"and d.branch_id=".$branch_id;
	
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
	$officer_res = mysql_query("select * from employees order by first_name, last_name");
	while($officer = mysql_fetch_array($officer_res)){
		$content .= "<option value='".$officer['id']."'>".$officer['first_name']." ".$officer['last_name'];
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
                                
                                <button type='button' class='btn  btn-primary'  onclick=\"xajax_list_outstanding(getElementById('cust_name').value, getElementById('cust_no').value, getElementById('account_name').value, getElementById('officer_id').value,  getElementById('from_date').value,  getElementById('to_date').value,getElementById('branch_id').value);\">Search</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","from_date");
	$resp->call("createDate","to_date");
                    
                //$resp->assign("display_div", "innerHTML", $content);
               
	if($from_date=='' || $to_date==''){
		$cont = "<font color=red>Please select the Date Range of the disbursed loans you would like to list!</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
	$name = ($cust_name == 'All') ? "" : $cust_name;
	$mem_no = ($cust_no == 'All') ? "" : $cust_no;
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_year);
	if($loan_officer >0)
		$officer = "o.employeeId='".$loan_officer."'";
	else
		$officer = "o.employeeId > 0";
	
	$sth = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.balance > 0 and d.written_off=0 ".$branch_." order by o.firstName, o.lastName, m.first_name, m.last_name");
	
	////$max_page = ceil(mysql_num_rows($sth)/$num_rows);
	$sth2 = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, d.last_pay_date as last_pay_date, d.pay_freq as pay_freq, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.balance > 0 and d.written_off=0 ".$branch_." order by o.firstName, o.lastName, m.first_name, m.last_name ");
	if(@ mysql_numrows($sth2) == 0){
		$cont = "<font color=red>No outstanding loans in your search options</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	$total_amount = mysql_fetch_assoc(mysql_query($sth = "select sum(d.amount) as disbursed, sum(d.balance) as balance from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.balance >0  and d.written_off=0 ".$branch_." order by o.firstName, o.lastName, d.cheque_no, m.first_name, m.last_name"));
	$former_officer ="";
	$i=$stat+1;
	$amt_sub_total=0;
	$bal_sub_total=0;
	$princ_arrears_sub_total=0;
	$int_arrears_sub_total=0;
	$tot_arrears_sub_total=0;
	$penalty_sub_total=0;
	$princ_due_sub_total=0;
	$int_due_sub_total=0;
	$tot_amt_sub_total=0;
	while($row = mysql_fetch_array($sth2)){
		$officer = $row['of_firstName']." ".$row['of_lastName'];
		if(strcmp($former_officer, $officer) != 0){
		
		$content .="<a href='export/outstanding?cust_name=".$cust_name."&branch_id=".$branch_id."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."' target=blank()><b>Printable Version</b></a> | <a href='export/outstanding?cust_name=".$cust_name."&branch_id=".$branch_id."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&format=excel' target=blank()><b>Export Excel</b></a>";
					
			$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                            <p><h4 class="semibold text-primary mt0 mb5">LIST OF OUTSTANDING LOANS</h4></p>
                                               		
                               <p><h3 class="panel-title">Loan Officer: '.$officer.'</h3></p>
                                <p>Total Disbursed: '.number_format($total_amount['disbursed'],2).'</p>
                                 <p>Total Balance:'.number_format($total_amount['balance'],2).'</p>
                                
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">
 		
			 <thead><th><b>No</b></th><th><b>Member Name</b></th><th><b>MemberNo</b></th><th><b>Date</b></th><th><b>Product</b></th><th><b>Amount</b></th><th><b>Loan Balance</b></th><th><b>Princ Arrears</b></th><th><b>Int Arrears</b></th><th><b>Total Arrears</b></th><th><b>Penalty</b></th><th><b>Princ Due</b></th><th><b>Int Due</b></th><th><b>Total Amt Due</b></th><th><b>Schedule</b></th><th><b>Payment</b></th></thead><tbody>';
			$former_officer = $officer;
		}

		//RESCHEDULE INTEREST FOR DECLINING BALANCE METHOD
		if($row['int_method'] == 'Declining Balance'){
			$sched_res = mysql_query("select id from schedule where loan_id='".$row['id']."' and princ_amt>0 and date> '".$row['last_pay_date']."' and date <= '".$date."'");
			while($sched = mysql_fetch_array($sched_res)){
				$new_int = $row['balance'] * (($row['int_rate']/100)/12) * ($row['pay_freq']/30);
				mysql_query("update schedule set int_amt ='".$new_int."' where id='".$sched['id']."'");
			}
		}


		//ARREARS
		$arrears_days = $calc->dateToDays(date('d'), date('m'), date('Y')) - $row['arrears_period'];
		$arrears_date = $calc->daysToDate($arrears_days, '%Y-%m-%d');
		$sched_res = mysql_query("select sum(princ_amt) as amount from schedule where loan_id='".$row['id']."' and date < '".$arrears_date."'");
		
		$sched = @mysql_fetch_array($sched_res);
				
		$paid_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from payment where loan_id='".$row['id']."' and date <= NOW()");
		
		$paid = mysql_fetch_array($paid_res);
		$sched_amount = ($sched['amount'] == NULL) ? "0" : $sched['amount'];
		$paid_amt = ($paid['amount'] == NULL) ? "0" : $paid['amount'];
		$paid_int = ($paid['int_amt'] == NULL) ? "0" : $paid['int_amt'];
		$arrears_amt = $sched_amount - $paid_amt;
		
		if($arrears_amt <= 0){
			$princ_arrears =0.0;
			$int_arrears = 0.0;
			$total_arrears =0.0;
			$total_amt_arrears = 0;
			$int_amt_arrears =0;
			$princ_amt_arrears =0;
		}else{
			$princ_arrears = $arrears_amt;
			$princ_amt_arrears = $arrears_amt;
			
			/*
			$arrears_days =$arrears_days - $calc->dateToDays($row['last_mday'], $row['last_month'], $row['last_year']);
			$no_months = floor($arrears_days / 30);
			if($no_months <= 0 ){
				$int_arrears = 0;		
			}elseif($row['int_method'] == 'Declining Balance')
				$int_arrears = ceil((($row['balance'] * $row['int_rate']/100) /12) * $no_months);
			elseif($row['int_method'] == 'Flat')
					$int_arrears = ceil((($row['disbursed_amt'] * $row['int_rate']/100) /12) * $no_months);
			*/
			$intsched = mysql_fetch_array(mysql_query("select sum(int_amt) as int_amt from schedule where  date < '".$arrears_date."' and loan_id='".$row['id']."'"));
			$int_arrears =($intsched['int_amt'] == NULL) ? 0 : $intsched['int_amt'];
			$intpaid = mysql_fetch_array(mysql_query("select sum(int_amt) as int_amt from payment where date < '".$arrears_date."' and loan_id='".$row['id']."'"));
			$int_arrears_paid =($intpaid['int_amt'] == NULL) ? 0 : $intpaid['int_amt'];
			
			$int_arrears -= $int_arrears_paid;
			$total_arrears = $princ_arrears + $int_arrears;
			$total_amt_arrears = $total_arrears;
			$int_amt_arrears = $int_arrears;
			$int_arrears = ($int_arrears == 0) ? 0.0 : $int_arrears;
		}
		
		//CALCULATE DUE PRINCIPAL
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id='".$row['id']."' and date <= NOW()");
		$sched = @mysql_fetch_array($sched_res);
		$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? 0 : $sched['int_amt'];
		$due_princ_amt = $sched_amt - $paid_amt;  // - $arrears_amt;	
		$due_int_amt = $sched_int - $paid_int;
		
		if($due_princ_amt <= 0){
			$due_princ = 0.0;
			$due_int = 0.0;
			$due_int_amt=0;
		}else{
				$due_princ = $due_princ_amt;
			if($due_int_amt >0){	
				$due_int = $due_int_amt;
			}else
				$due_int=0.0; 
			
			
			
			/* $due_princ = $due_princ_amt;
			//CALCULATE DUE INTEREST
			$intsched = mysql_fetch_array(mysql_query("select sum(int_amt) as int_amt from schedule where date> '".$arrears_date."' and date <= CURDATE() and loan_id='".$row['id']."'"));
			$due_int_amt =($intsched['int_amt'] == NULL) ? 0 : $intsched['int_amt'];
			$intpaid = mysql_fetch_array(mysql_query("select sum(int_amt) as int_amt from payment where date> '".$arrears_date."' and date <= CURDATE() and loan_id='".$row['id']."'"));
			$paid_int_amt =($intpaid['int_amt'] == NULL) ? 0 : $intpaid['int_amt'];
			$due_int_amt = ceil($due_int_amt - $paid_int_amt);

		
			$int_days = $calc->dateToDays(date('d'), date('m'), date('Y')) - $calc->dateToDays($row['last_mday'], $row['last_month'], $row['last_year']) - $row['arrears_period'];
		
			if($int_days >0){
				$no_months = floor($int_days /30);
				if($row['int_method'] =='Declining Balance'){
					$due_int_amt = ceil((($row['balance'] * $row['int_rate']/100) / 12) * $no_months);
					$due_int = $due_int_amt;
				}elseif($row['int_method'] == 'Flat'){
					$due_int_amt = ceil((($row['disbursed_amt'] * $row['int_rate']/100) / 12) * $no_months);
					$due_int = $due_int_amt;
				}
			}else
			$due_int="--";
			*/
		}
		$total_amt_due = $due_int_amt + $due_princ_amt;
		$total_due = ($total_amt_due <= 0) ? 0 : $total_amt_due;
		
		$pay = "<a href='export/payments?loan_id=".$row['id']."&applic_id=".$row['applic_id']."&cust_name=".$cust_name."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&princ_arrears=".$princ_amt_arrears."&int_arrears=".$int_amt_arrears."&princ_due=".$due_princ_amt."&int_due=".$due_int_amt."&from-year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&status=outstanding&format=excel' target=blank()>Payments</a>";
		
		$schedule = "<a href='export/schedule?loan_id=".$row['id']."&applic_id=".$row['applic_id']."&cust_name=".$cust_name."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&princ_arrears=".$princ_amt_arrears."&int_arrears=".$int_amt_arrears."&princ_due=".$due_princ_amt."&int_due=".$due_int_amt."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&status=outstanding&format=excel' target=blank()>Schedule</a>";

		//PENALTY
		$pen_res = mysql_query("select sum(amount) as amount from penalty where status='pending' and loan_id='".$row['id']."'");
		$pen = mysql_fetch_array($pen_res);
		$penalty = ($pen['amount'] == NULL) ? 0.0 : $pen['amount'];
		////$color=($i % 2==0) ? "lightgrey" : "white";
		$content .= "<tr><td>".$i."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".$row['date']."</td><td>".$row['account_no']." - ".$row['account_name']."</td><td>".number_format($row['amount'], 2)."</td><td>".number_format($row['balance'], 2)."</td><td>".number_format($princ_arrears, 2)."</td><td>".number_format($int_arrears, 2)."</td><td>".number_format($total_arrears, 2)."</td><td>".$penalty."</td><td>".number_format($due_princ, 2)."</td><td>".number_format($due_int, 2)." ".$row['last_pay_date']."</td><td>".number_format($total_due, 2)."</td><td>".$schedule."</td><td>".$pay."</td></tr>";
		$i++;
	// GET SUB TOTALS
		$amt_sub_total += $row['amount']; 
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
	$content .= "<tr><td></td><td></td><td></td><td></td><td><b>TOTAL</b></td><td><b>".number_format($amt_sub_total, 2)."</b></td><td><b>".number_format($bal_sub_total, 2)."</b></td><td><b>".number_format($princ_arrears_sub_total, 2)."</b></td><td><b>".number_format($int_arrears_sub_total, 2)."</b></td><td><b>".number_format($tot_arrears_sub_total, 2)."</b></td><td><b>".number_format($penalty_sub_total, 2)."</b></td><td><b>".number_format($princ_due_sub_total, 2)."</b></td><td><b>".number_format($int_due_sub_total, 2)."</b></td><td><b>".number_format($tot_amt_sub_total, 2)."</b></td><td></td><td></td></tr></tbody></table></div>";
	}
	$resp->call("createTableJs");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


//LIST OUTSTANDING
function list_due($cust_name, $cust_no, $account_name, $loan_officer, $date,$branch_id){
	//list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$resp->assign("status", "innerHTML", "");
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:"and d.branch_id=".$branch_id;
		
	
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
	$officer_res = mysql_query("select * from employees order by first_name, last_name");
	while($officer = mysql_fetch_array($officer_res)){
		$content .= "<option value='".$officer['id']."'>".$officer['first_name']." ".$officer['last_name'];
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
                                       
                                            <label class="control-label">Date of Reporting:</label>
                                          
                                              <input type="text" class="form-control" id="date" name="date" placeholder="date" />
                                            </div>   
                                       
                                    </div>
                                </div>
               
                               
                            '; 
				
		 $content .= "<div class='panel-footer'>                              
                                
                                <button type='button' class='btn  btn-primary'  onclick=\"xajax_list_due(getElementById('cust_name').value, getElementById('cust_no').value, getElementById('account_name').value, getElementById('officer_id').value,getElementById('date').value,getElementById('branch_id').value);\">Search</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                      $resp->call("createDate","date");
                //$resp->assign("display_div", "innerHTML", $content);
	
	
	if($date==''){
		$cont = "<font color=red>Please select Due Date for the disbursed loans you would like to list!</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
	$name = ($cust_name == 'All') ? "" : $cust_name;
	$mem_no = ($cust_no == 'All') ? "" : $cust_no;
	//$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	//$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_year);
	if($loan_officer >0)
		$officer = "o.employeeId='".$loan_officer."'";
	else
		$officer = "o.employeeId > 0";
	
	$sth = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date <='".$date."' and d.balance > 0 and d.written_off=0 ".$branch_." order by o.firstName, o.lastName, m.first_name, m.last_name");
	
	////$max_page = ceil(mysql_num_rows($sth)/$num_rows);
	$sth2 = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, d.last_pay_date as last_pay_date, d.pay_freq as pay_freq,  date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date <='".$date."' and d.balance > 0 and d.written_off=0 ".$branch_." order by o.firstName, o.lastName, m.first_name, m.last_name ");
	if(@ mysql_numrows($sth2) == 0){
		$cont = "<font color=red>No due payments in your search options</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	//$total_amount = mysql_fetch_assoc(mysql_query($sth = "select sum(d.amount) as disbursed, sum(d.balance) as balance from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date <='".$to_date."' and d.balance >0 and d.cheque_no like '%".$cheque_no."%' and d.written_off=0 ".$branch." order by o.firstName, o.lastName, d.cheque_no, m.first_name, m.last_name"));
	$former_officer ="";
	$i=$stat+1;
	$amt_sub_total=0;
	$bal_sub_total=0;
	$princ_arrears_sub_total=0;
	$int_arrears_sub_total=0;
	$tot_arrears_sub_total=0;
	$penalty_sub_total=0;
	$princ_due_sub_total=0;
	$int_due_sub_total=0;
	$tot_amt_sub_total=0;
	while($row = mysql_fetch_array($sth2)){
		$officer = $row['of_firstName']." ".$row['of_lastName'];
		if(strcmp($former_officer, $officer) != 0){
		
		$content .="<a href='export/duePayments?cust_name=".$cust_name."&branch_id=".$branch_id."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&date=".$date."' target=blank()><b>Printable Version</b></a> | <a href='export/duePayments?cust_name=".$cust_name."&branch_id=".$branch_id."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&date=".$date."&format=excel' target=blank()><b>Export Excel</b></a>";
		
		$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                            <p><h4 class="semibold text-primary mt0 mb5">LIST OF DUE PAYMENTS</h4></p>
                                <p><h5 class="text-primary mt0">Loan Officer:  '.$officer.'</h5></p>                              
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">
 					
			<thead><th><b>No</b></th><th><b>Member Name</b></th><th><b>MemberNo</b></th><th><b>Date</b></th><th><b>Product</b></th><th><b>Amount</b></th><th><b>Loan Balance</b></th><th><b>Princ Arrears</b></th><th><b>Int Arrears</b></th><th><b>Total Arrears</b></th><th><b>Penalty</b></th><th><b>Princ Due</b></th><th><b>Int Due</b></th><th><b>Total Amt Due</b></th><th><b>Schedule</b></th><th><b>Payment</b></th></thead><tbody>';
			$former_officer = $officer;
		}
		//RESCHEDULE INTEREST FOR DECLINING BALANCE METHOD
		if($row['int_method'] == 'Declining Balance'){
			$sched_res = mysql_query("select id from schedule where loan_id='".$row['id']."' and princ_amt>0 and date> '".$row['last_pay_date']."' and date <= '".date('Y-m-d 23:59:59')."'");
			while($sched = mysql_fetch_array($sched_res)){
				$new_int = $row['balance'] * (($row['int_rate']/100)/12) * ($row['pay_freq']/30);
				mysql_query("update schedule set int_amt ='".$new_int."' where id='".$sched['id']."'");
			}
		}

		//ARREARS
		$arrears_days = $calc->dateToDays(date('d'), date('m'), date('Y')) - $row['arrears_period'];
		$arrears_date = $calc->daysToDate($arrears_days, '%Y-%m-%d');
		$sched_res = mysql_query("select sum(princ_amt) as amount from schedule where loan_id='".$row['id']."' and date < '".$arrears_date."'");
		
		$sched = @mysql_fetch_array($sched_res);
				
		$paid_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from payment where loan_id='".$row['id']."' and date <= '".$date."'");
		
		$paid = mysql_fetch_array($paid_res);
		$sched_amount = ($sched['amount'] == NULL) ? "0" : $sched['amount'];
		$paid_amt = ($paid['amount'] == NULL) ? "0" : $paid['amount'];
		$paid_int = ($paid['int_amt'] == NULL) ? "0" : $paid['int_amt'];
		$arrears_amt = $sched_amount - $paid_amt;
		
		if($arrears_amt <= 0){
			$princ_arrears =0.0;
			$int_arrears = 0.0;
			$total_arrears =0.0;
			$total_amt_arrears = 0;
			$int_amt_arrears =0;
			$princ_amt_arrears =0;
		}else{
			$princ_arrears = $arrears_amt;
			$princ_amt_arrears = $arrears_amt;
			
			/*
			$arrears_days =$arrears_days - $calc->dateToDays($row['last_mday'], $row['last_month'], $row['last_year']);
			$no_months = floor($arrears_days / 30);
			if($no_months <= 0 ){
				$int_arrears = 0;		
			}elseif($row['int_method'] == 'Declining Balance')
				$int_arrears = ceil((($row['balance'] * $row['int_rate']/100) /12) * $no_months);
			elseif($row['int_method'] == 'Flat')
					$int_arrears = ceil((($row['disbursed_amt'] * $row['int_rate']/100) /12) * $no_months);
			*/
			$intsched = mysql_fetch_array(mysql_query("select sum(int_amt) as int_amt from schedule where  date < '".$arrears_date."' and loan_id='".$row['id']."'"));
			$int_arrears =($intsched['int_amt'] == NULL) ? 0 : $intsched['int_amt'];
			$intpaid = mysql_fetch_array(mysql_query("select sum(int_amt) as int_amt from payment where date < '".$arrears_date."' and loan_id='".$row['id']."'"));
			$int_arrears_paid =($intpaid['int_amt'] == NULL) ? 0 : $intpaid['int_amt'];
			
			$int_arrears -= $int_arrears_paid;
			$total_arrears = $princ_arrears + $int_arrears;
			$total_amt_arrears = $total_arrears;
			$int_amt_arrears = $int_arrears;
			$int_arrears = ($int_arrears == 0) ? 0.0 : $int_arrears;
		}
		
		//CALCULATE DUE PRINCIPAL
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id='".$row['id']."' and date <= NOW()");
		$sched = @mysql_fetch_array($sched_res);
		$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? 0 : $sched['int_amt'];
		$due_princ_amt = $sched_amt - $paid_amt;  // - $arrears_amt;	
		$due_int_amt = $sched_int - $paid_int;
		
		if($due_princ_amt <= 0){
			continue;
		}else{
				$due_princ = $due_princ_amt;
			if($due_int_amt >0){	
				$due_int = $due_int_amt;
			}else
				$due_int=0.0; 
			
			
			
			/* $due_princ = $due_princ_amt;
			//CALCULATE DUE INTEREST
			$intsched = mysql_fetch_array(mysql_query("select sum(int_amt) as int_amt from schedule where date> '".$arrears_date."' and date <= CURDATE() and loan_id='".$row['id']."'"));
			$due_int_amt =($intsched['int_amt'] == NULL) ? 0 : $intsched['int_amt'];
			$intpaid = mysql_fetch_array(mysql_query("select sum(int_amt) as int_amt from payment where date> '".$arrears_date."' and date <= CURDATE() and loan_id='".$row['id']."'"));
			$paid_int_amt =($intpaid['int_amt'] == NULL) ? 0 : $intpaid['int_amt'];
			$due_int_amt = ceil($due_int_amt - $paid_int_amt);

		
			$int_days = $calc->dateToDays(date('d'), date('m'), date('Y')) - $calc->dateToDays($row['last_mday'], $row['last_month'], $row['last_year']) - $row['arrears_period'];
		
			if($int_days >0){
				$no_months = floor($int_days /30);
				if($row['int_method'] =='Declining Balance'){
					$due_int_amt = ceil((($row['balance'] * $row['int_rate']/100) / 12) * $no_months);
					$due_int = $due_int_amt;
				}elseif($row['int_method'] == 'Flat'){
					$due_int_amt = ceil((($row['disbursed_amt'] * $row['int_rate']/100) / 12) * $no_months);
					$due_int = $due_int_amt;
				}
			}else
			$due_int="--";
			*/
		}
		$total_amt_due = $due_int_amt + $due_princ_amt;
		$total_due = ($total_amt_due <= 0) ? 0.0 : $total_amt_due;
		
		$pay = "<a href='export/payments?loan_id=".$row['id']."&applic_id=".$row['applic_id']."&cust_name=".$cust_name."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&princ_arrears=".$princ_amt_arrears."&int_arrears=".$int_amt_arrears."&princ_due=".$due_princ_amt."&int_due=".$due_int_amt."&from-year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&status=due&format=excel' target=blank()>Payments</a>";
		
		$schedule = "<a href='export/schedule?loan_id=".$row['id']."&applic_id=".$row['applic_id']."&cust_name=".$cust_name."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&princ_arrears=".$princ_amt_arrears."&int_arrears=".$int_amt_arrears."&princ_due=".$due_princ_amt."&int_due=".$due_int_amt."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&status=due&format=excel' target=blank()>Schedule</a>";

		//PENALTY
		$pen_res = mysql_query("select sum(amount) as amount from penalty where status='pending' and loan_id='".$row['id']."'");
		$pen = mysql_fetch_array($pen_res);
		$penalty = ($pen['amount'] == NULL) ? 0.0 : $pen['amount'];
		//$color=($i % 2==0) ? "lightgrey" : "white";
		$content .= "<tr><td>".$i."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".$row['date']."</td><td>".$row['account_no']." - ".$row['account_name']."</td><td>".number_format($row['amount'], 2)."</td><td>".number_format($row['balance'], 2)."</td><td>".number_format($princ_arrears, 2)."</td><td>".number_format($int_arrears, 2)."</td><td>".number_format($total_arrears, 2)."</td><td>".$penalty."</td><td>".number_format($due_princ, 2)."</td><td>".number_format($due_int, 2)." ".$row['last_pay_date']."</td><td>".number_format($total_due, 2)."</td><td>".$schedule."</td><td>".$pay."</td></tr>";
		$i++;
	// GET SUB TOTALS
		$amt_sub_total += $row['amount']; 
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
	$content .= "<tr><td></td><td></td><td></td><td></td><td><b>TOTAL</b></td><td><b>".number_format($amt_sub_total, 2)."</b></td><td><b>".number_format($bal_sub_total, 2)."</b></td><td><b>".number_format($princ_arrears_sub_total, 2)."</b></td><td><b>".number_format($int_arrears_sub_total, 2)."</b></td><td><b>".number_format($tot_arrears_sub_total, 2)."</b></td><td><b>".number_format($penalty_sub_total, 2)."</b></td><td><b>".number_format($princ_due_sub_total, 2)."</b></td><td><b>".number_format($int_due_sub_total, 2)."</b></td><td><b>".number_format($tot_amt_sub_total, 2)."</b></td><td></td><td></td></tr></tbody></table></div>";
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
	$branch_res = mysql_query("select * from branch");
	$branch = mysql_fetch_array($branch_res);
	$branch_=($branch_id=='all'||$branch_id=='')?NULL:'and d.branch_id='.$branch_id;
		
	 $content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title"><b>'.$branch['branch_name'].'</b></h3>
                                <p class="text-default nm"> SEARCH FOR ARREARS REPORT</p>
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
	$officer_res = mysql_query("select * from employees order by first_name, last_name");
	while($officer = mysql_fetch_array($officer_res)){
		$content .= "<option value='".$officer['id']."'>".$officer['first_name']." ".$officer['last_name'];
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
                                            <label class="control-label">Loan Status:</label>
                                            <select id="state" class="form-control"><option value="">All<option value="On going">On going<option value="Over Due">Over Due</select>                                           
                                        </div>
                           
                                    
                                </div>                          
                            '; 
		
		 $content .= "<div class='panel-footer'>                              
                                
                                <button type='button' class='btn  btn-primary'  onclick=\"xajax_list_arrears(getElementById('cust_name').value, getElementById('cust_no').value, getElementById('account_name').value, getElementById('officer_id').value,  getElementById('from_date').value,  getElementById('to_date').value, getElementById('state').value,getElementById('branch_id').value);\">Search</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    
                    $resp->call("createDate","from_date");
	            $resp->call("createDate","to_date");
                    
                //$resp->assign("display_div", "innerHTML", $content);
	

	if($from_date=='' || $to_date==''){
		$cont = "<font color=red>Please select the options for the Arrears Report!</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
	$name = ($cust_name == 'All') ? "" : $cust_name;
	$mem_no = ($cust_no == 'All') ? "" : $cust_no;
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_year);
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
	$sth = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, (DATEDIFF(CURDATE(), d.last_pay_date) - d.arrears_period) as arrears_days, datediff(CURDATE(), d.date) as ellapsed_time, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.balance > 0 and d.written_off='0'".$state." and DATEDIFF(CURDATE(), d.date) > (d.arrears_period + d.grace_period + 30) ".$branch_." order by o.firstName, o.lastName, m.first_name, m.last_name");
	
	$sth2 = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, (DATEDIFF(CURDATE(), d.last_pay_date) - d.arrears_period) as arrears_days, datediff(CURDATE(), d.date) as ellapsed_time, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, d.last_pay_date as last_pay_date, d.pay_freq as pay_freq, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.balance > 0 and d.written_off='0'".$state." and DATEDIFF(CURDATE(), d.date) > (d.arrears_period + d.grace_period + 30) ".$branch_." order by o.firstName, o.lastName, m.first_name, m.last_name ");
	
	if(@ mysql_numrows($sth) == 0){
		$cont = "<font color=red>No arrears in your search options</font>";
		$resp->assign("status", "innerHTML", $cont);
		return $resp;
	}
	////$max_page = ceil(mysql_num_rows($sth)/$num_rows);
	$former_officer ="";
	$i=$stat+1;
	        $amt_sub_total =0; 
		$prepaid_sub_total =0; 
		$paid_sub_total =0; 
		$princ_arrears_sub_total =0;; 
		$int_arrears_sub_total =0; 
		$penalty_sub_total =0; 
		$princ_due_sub_total =0; 
		$int_due_sub_total =0; 
		$out_standing_sub_total =0;
		
	while($row = mysql_fetch_array($sth2)){
		$officer = $row['of_firstName']." ".$row['of_lastName'];
		if(strcmp($former_officer, $officer) != 0){
		
		$content .="<a href='export/arrears?cust_name=".$cust_name."&branch_id=".$branch_id."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&status=".$status."' target=blank()><b>Printable Version</b></b></a>| <a href='export/arrears?cust_name=".$cust_name."&branch_id=".$branch_id."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&status=".$status."&format=excel' target=blank()><b>Export Excel</b></b></a>";
		
		$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                            <p><h4 class="semibold text-primary mt0 mb5">ARREARS REPORT</h4></p>
                                <p><h5 class="text-primary mt0">Loan Officer: '.$officer.'</h5></p>
                                                         
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">
			
			<thead><th><b>#</b></th><th><b>Member Name</b></th><th><b>Member No.</b></th><th><b>Date Disbursed</b></th><th><b>Product</b></th><th><b>Amount</b></th><th><b>Amt Paid</b></th><th><b>Amt Prepaid</b></th><th><b>Princ Arrears</b></th><th><b>Int Arrears</b></th><th><b>Penalty</b></th><th><b>Princ Due</b></th><th><b>Int Due</b></th><th><b>Outstanding Balance</b></th></th><th><b>Repayment Rate(%)</b></th><th><b>Arrears Rate(%)</b></th><th><b>Cummulated Repayment Rate(%)</b></th><th><b>Cummulated Arrears Rate(%)</b></th></thead><tbody>';
			$former_officer = $officer;
		}
		//RESCHEDULE INTEREST FOR DECLINING BALANCE METHOD
		if($row['int_method'] == 'Declining Balance'){
			$sched_res = mysql_query("select id from schedule where loan_id='".$row['id']."' and princ_amt>0 and date> '".$row['last_pay_date']."' and date <= '".$to_date."'");
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
			$princ_arrears =0.0;
			$int_arrears = 0.0;
			$total_arrears =0.0;
			$total_amt_arrears = 0;
			$int_amt_arrears =0;
			$princ_amt_arrears =0;
		}else{
			$princ_arrears = $arrears_amt;
			$princ_amt_arrears = $arrears_amt;
		}
		if($int_arrears <= 0){  //INT IN ARREARS
			$int_arrears = 0.0;
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
		$prepaid = ($prepaid <= 0) ? 0.0 : $prepaid;
		$due_princ_amt = $sched_amt - $paid_amt;  // - $arrears_amt + $prepaid_amt;
		$due_int_amt = $sched_int  - $paid_int;
		if($due_princ_amt <= 0){    //PRINC DUE
			$due_princ = 0.0;
		}else{
			$due_princ = $due_princ_amt;
		}
		if($due_int_amt <= 0){	  //INTEREST DUE
			$due_int = 0.0;
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
		}else{
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
			$nowsched_amt = ($nowsched['princ_amt'] ==NULL || $nowsched['princ_amt'] ==0) ? 1: $nowsched['princ_amt'];
			$repay_rate = $nowpaid_amt / $nowsched_amt;
			$repay_rate = sprintf("%.02f", $repay_rate);
			$arrears_rate = 100.00 - $repay_rate;
		}
		//PENALTY
		$pen_res = mysql_query("select sum(amount) as amount from penalty where loan_id='".$row['id']."' and status='pending'");
		$pen = mysql_fetch_array($pen_res);
		$penalty = ($pen['amount'] == NULL) ? 0.0 : $pen['amount'];

		////$color=($i % 2==0) ? "lightgrey" : "white";
		$content .= "<tr><td>".$i."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".$row['date']."</td><td>".$row['account_no']." - ".$row['account_name']."</td><td>".number_format($row['amount'], 2)."</td><td>".number_format($paid_amt, 2)."</td><td>".number_format($prepaid, 2)."</td><td>".number_format($princ_arrears, 2)."</td><td>".number_format($int_arrears, 2)."</td><td>".number_format($penalty, 2)."</td><td>".number_format($due_princ, 2)."</td><td>".number_format($due_int, 2)."</td><td>".number_format($row['balance'], 2)."</td><td>".$repay_rate."</td><td>".$arrears_rate."</td><td>".$cumm_repay_rate. "</td><td>".$cumm_arrears_rate."</td></tr>";
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
	$content .= "<tr><td></td><td></td><td></td><td></td><td><b>TOTAL</b></td><td><b>".number_format($amt_sub_total, 2)."</b></td><td><b>".number_format($paid_sub_total, 2)."</b></td><td><b>".number_format($prepaid_sub_total, 2)."</b></td><td><b>".number_format($princ_arrears_sub_total, 2)."</b></td><td><b>".number_format($int_arrears_sub_total, 2)."</b></td><td><b>".number_format($penalty_sub_total, 2)."</b></td><td><b>".number_format($princ_due_sub_total, 2)."</b></td><td><b>".number_format($int_due_sub_total, 2)."</b></td><td><b>".number_format($out_standing_sub_total, 2)."</b></td><td></td><td></td><td></td><td></td><td></td></tr>";
	$content .= "</tbody></table></div>";
	}
	$resp->call("createTableJs");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


//PORTIFOILIO STATUS REPORT
function portifolio_status($cust_name, $cust_no, $account_name, $loan_officer, $from_date, $to_date, $status,$branch_id){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$resp->assign("status", "innerHTML", "");
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL: ' and applic.branch_id='.$branch_id."";
		
	 $content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title"><b>SEARCH FOR PORTFOLIO STATUS REPORT</b></h3>
                                <p class="text-default nm"></p>
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
	$officer_res = mysql_query("select * from employees order by first_name, last_name");
	while($officer = mysql_fetch_array($officer_res)){
		$content .= "<option value='".$officer['id']."'>".$officer['first_name']." ".$officer['last_name'];
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
                                            <label class="control-label">Loan Status:</label>
                                            <select id="state" class="form-control"><option value="">All<option value="On going">On going<option value="Over Due">Over Due</select>                                           
                                        </div>
                           
                                    
                                </div>                          
                            '; 
		
		 $content .= "<div class='panel-footer'>                              
                                
                                <button type='button' class='btn  btn-primary'  onclick=\"xajax_portifolio_status(getElementById('cust_name').value, getElementById('cust_no').value, getElementById('account_name').value, getElementById('officer_id').value,  getElementById('from_date').value,  getElementById('to_date').value, getElementById('state').value,getElementById('branch_id').value);\">Search</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    
                    $resp->call("createDate","from_date");
		    $resp->call("createDate","to_date");
                    
                //$resp->assign("display_div", "innerHTML", $content);
	
	if($from_date=='' || $to_date==''){
		$cont = "<font color=red>Please select the options for the report!</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
	$name = ($cust_name == 'All') ? "" : $cust_name;
	$mem_no = ($cust_no == 'All') ? "" : $cust_no;
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_year);
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
	
	$sth = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, (DATEDIFF(CURDATE(), d.last_pay_date) - d.arrears_period) as arrears_days, datediff(CURDATE(), d.date) as ellapsed_time, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.written_off='0' ".$state . " ".$branch_." order by o.firstName, o.lastName, m.mem_no, m.first_name, m.last_name, d.date");
	

	////$max_page = ceil(mysql_num_rows($sth)/$num_rows);
	
	$sth2 = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, (DATEDIFF(CURDATE(), d.last_pay_date) - d.arrears_period) as arrears_days, datediff(CURDATE(), d.date) as ellapsed_time, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, d.last_pay_date as last_pay_date, d.pay_freq as pay_freq, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.written_off='0' ".$state . " ".$branch_." order by o.firstName, o.lastName, m.mem_no, m.first_name, m.last_name, d.date ");
	if(@ mysql_numrows($sth) == 0){
		$cont = "<font color=red>No results in your search options</font>";
		$resp->assign("status", "innerHTML", $cont);
		return $resp;
	}
	
	$former_officer ="";
	$i=$stat+1;
	$cumm_arrears_rate=0;
	while($row = mysql_fetch_array($sth2)){
		$officer = $row['of_firstName']." ".$row['of_lastName'];
		if(strcmp($former_officer, $officer) != 0){
		
		$content .="<a href='export/portifolioStatus?cust_name=".$cust_name."&branch_id=".$branch_id."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&status=".$status."' target=blank()><b>Printable Version</b></a> | <a href='export/portifolioStatus?cust_name=".$cust_name."&branch_id=".$branch_id."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&status=".$status."&format=excel' target=blank()><b>Export Excel</b></a>";
		
		 $content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                            <p><h4 class="semibold text-primary mt0 mb5">PORTFOLIO STATUS REPORT</h4></p>
                               <p><h5 class="text-primary mt0">Loan Officer:  '.$officer.'</h5></p>
                                
                                
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">
		<thead><th><b>#</b></th><th><b>Date Disbursed</b></th><th><b>Member No</b></th><th><b>Member Name</b></th><th><b>Product</b></th><th><b>Amount Awarded</b></th><th><table><tr><td rowspan=2><b>Amt Due</b></td></tr><tr><td><b>Princ</b></td><td><b>Interest</b></td></tr></table></th><th><b>Amt Paid</b></th><th><b>Amt Prepaid</b></th><th><b>Arrears At Start Of Period</b></th><th ><b>Arrears At End Of Period</b></th><th><b>Outstanding Balance</b></th></th><th><b>Repayment Rate(%)</b></th><th><b>Cummulated Repayment Rate(%)</b></th></thead><tbody>
			<tr class="headings"><td><b>Princ</b></td><td><b>Interest</b></td> <td><b>Princ</b></td><td><b>Interest</b></td> <td><b>Princ</b></td><td><b>Interest</b></td> <td><b>Princ</b></td><td><b>Interest</b></td> <td><b>Princ</b></td><td><b>Interest</b></td></tr>';
			$former_officer = $officer;
		}
		//RESCHEDULE INTEREST FOR DECLINING BALANCE METHOD
		if($row['int_method'] == 'Declining Balance'){
			$sched_res = mysql_query("select id from schedule where loan_id='".$row['id']."' and princ_amt>0 and date> '".$row['last_pay_date']."' and date <= '".$to_date."'");
			while($sched = mysql_fetch_array($sched_res)){
				$new_int = $row['balance'] * (($row['int_rate']/100)/12) * ($row['pay_freq']/30);
				mysql_query("update schedule set int_amt ='".$new_int."' where id='".$sched['id']."'");
			}
		}


		//ARREARS AT BEGINNING OF PERIOD
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id='".$row['id']."' and date < DATE_SUB(CURDATE(), INTERVAL ".($row['arrears_period']+30)." DAY)");
		$sched = @mysql_fetch_array($sched_res);
	
		$paid_res = mysql_query("select sum(princ_amt) as princ_amt, sum(int_amt) as int_amt from payment where loan_id='".$row['id']."' and date <= DATE_SUB(CURDATE(), INTERVAL 30 DAY)");
		
		$paid = mysql_fetch_array($paid_res);
		$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? 0 : $sched['int_amt'];
		$paid_princ = ($paid['princ_amt'] == NULL) ? 0 : $paid['princ_amt'];
		$paid_int = ($paid['int_amt'] == NULL) ? 0 : $paid['int_amt'];
		$start_princ_arrears = $sched_amt - $paid_princ;
		$start_princ_arrears = ($start_princ_arrears < 0) ? 0 : $start_princ_arrears; 
		
		$start_int_arrears = $sched_int - $paid_int;
		//$start_int_arrears = ($start_int_arrears < 0) ? 0 : $start_int_arrears; 
		
		
		//ARREARS AT THE END OF PERIOD
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id='".$row['id']."' and date < DATE_SUB(CURDATE(), INTERVAL ".($row['arrears_period'])." DAY)");
		$sched = @mysql_fetch_array($sched_res);
	
		$paid_res = mysql_query("select sum(princ_amt) as princ_amt, sum(int_amt) as int_amt from payment where loan_id='".$row['id']."' and date <= CURDATE()");
		
		$paid = mysql_fetch_array($paid_res);
		$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? 0 : $sched['int_amt'];
		$paid_princ = ($paid['princ_amt'] == NULL) ? 0 : $paid['princ_amt'];
		$paid_int = ($paid['int_amt'] == NULL) ? 0 : $paid['int_amt'];
		$end_princ_arrears = $sched_amt - $paid_princ;
		$end_princ_arrears = ($end_princ_arrears < 0) ? 0 : $end_princ_arrears;
		$end_int_arrears = $sched_int - $paid_int;
		
		//CALCULATE DUE PRINCIPAL CUMMULATIVE
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id='".$row['id']."' and date <= CURDATE()");
		
		$sched = @mysql_fetch_array($sched_res);
		$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? 0 : $sched['int_amt'];
		$prepaid = $paid_princ - $sched_amt;
		$prepaid = ($prepaid <= 0) ? 0.0 : $prepaid;
		$due_princ_amt = $sched_amt - $paid_princ;  // - $end_princ_arrears;
		$due_princ_amt = ($due_princ_amt > 0) ? $due_princ_amt : 0;
		
		$due_int_amt = $sched_int - $paid_int;  // - $end_int_arrears;   //INTEREST DUE
		$outstandg_int = $sched_int - $paid_int;
		//CUMMULATIVE RATES
		//CHECK WHETHER ALL LOAN IS OVER DUE
		if($row['ellapsed_time'] >= $row['loan_period'] + $row['arrears_period']){
			$cumm_repay_rate = 0.00;
			$repay_rate = 0.00;
		}else{
			$sched_amt_den = ($sched_amt == 0) ? 1 : $sched_amt;
			$cumm_repay_rate = ($paid_princ * 100) / $sched_amt_den;
			$cumm_repay_rate = sprintf("%.02f", $cumm_repay_rate);
			$nowsched_res = mysql_query("select * from schedule where loan_id='".$row['id']."' and date < CURDATE() order by date desc limit 1");
			$nowsched = mysql_fetch_array($nowsched_res);
			$nowpaid_res = mysql_query("select sum(princ_amt) as princ_amt from payment where loan_id='".$row['id']."' and date >= '".$nowsched['date']."' and date < DATE_ADD(date, INTERVAL 30 DAY)");
			
			$nowpaid = mysql_fetch_array($nowpaid_res);
			$nowsched_amt = ($nowsched['princ_amt'] != NULL) ? $nowsched['princ_amt'] : 1;
			$nowpaid_amt = ($nowpaid['princ_amt'] == NULL) ? 0 : $nowpaid['princ_amt'];
			$repay_rate = $nowpaid_amt / $nowsched_amt;
			$repay_rate = sprintf("%.02f", $repay_rate);
		}
		
		$outstandg_int = $outstandg_int;
		$due_int_amt = $due_int_amt;
		////$color=($i % 2==0) ? "lightgrey" : "white";
		$content .= "<tr><td>".$i."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".$row['date']."</td><td>".$row['account_no']." - ".$row['account_name']."</td><td>".number_format($row['amount'], 2)."</td><td>".number_format($due_princ_amt, 2)."</td><td>".number_format($due_int_amt, 2)."</td><td>".number_format($paid_princ, 2)."</td><td>".number_format($paid_int, 2)."</td><td>".number_format($prepaid, 2)."</td><td>".number_format($start_princ_arrears, 2)."</td><td>".number_format($start_int_arrears, 2)."</td><td>".number_format($end_princ_arrears, 2)."</td><td>".number_format($end_int_arrears, 2)."</td><td>".number_format($row['balance'], 2)."</td><td>".number_format($outstandg_int, 2)."</td><td>".$repay_rate."</td><td>".$cumm_repay_rate. "</td><td>".$cumm_arrears_rate."</td></tr>";
		$i++;
	}
	$content .= "</tbody></table></div>";
	}
	$resp->call("createTableJs");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


//REPAYMENTS MADE IN A PERIOD
function loan_repayments($cust_name, $cust_no, $account_name, $loan_officer, $from_date,$to_date, $status,$branch_id){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$resp->assign("status", "innerHTML", "");
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:'and d.branch_id='.$branch_id;
	
	
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <p><h5 class="text-primary mt0">REPAYMENTS MADE FROM '.strtoupper($from_date).' TO '.strtoupper($to_date).'</h5></p>
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
	$officer_res = mysql_query("select * from employees order by first_name, last_name");
	while($officer = mysql_fetch_array($officer_res)){
		$content .= "<option value='".$officer['id']."'>".$officer['first_name']." ".$officer['last_name'];
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
                                
                                <button type='button' class='btn  btn-primary'  onclick=\"xajax_loan_repayments(getElementById('cust_name').value, getElementById('cust_no').value, getElementById('account_name').value, getElementById('officer_id').value,  getElementById('from_date').value,  getElementById('to_date').value, '',getElementById('branch_id').value);\">Search</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    
        $resp->call("createDate","from_date");
	$resp->call("createDate","to_date"); 
                    
                //$resp->assign("display_div", "innerHTML", $content);
	
	if($from_date=='' || $to_date==''){
		$cont = "<font color=red>Please select the options for the Arrears report!</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_year);
	$name = ($cust_name == 'All') ? "" : $cust_name;
	$mem_no = ($cust_no == 'All') ? "" : $cust_no;
	
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
	//$sth = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, (DATEDIFF(CURDATE(), d.last_pay_date) - d.arrears_period) as arrears_days, datediff(CURDATE(), d.date) as ellapsed_time, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.written_off='0'".$state);

	$sth = mysql_query("select d.id as id, d.cheque_no as cheque_no, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, (DATEDIFF(CURDATE(), d.last_pay_date) - d.arrears_period) as arrears_days, datediff(CURDATE(), d.date) as ellapsed_time, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.id in (select loan_id from payment where date >= '".$from_date."' and date <= '".$to_date."') ".$branch_." order by o.firstName, o.lastName, d.cheque_no");

	////$max_page = ceil(mysql_num_rows($sth)/$num_rows);
	$sth2 = mysql_query("select d.id as id, d.cheque_no as cheque_no, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, (DATEDIFF(CURDATE(), d.last_pay_date) - d.arrears_period) as arrears_days, datediff(CURDATE(), d.date) as ellapsed_time, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.id in (select loan_id from payment where date >= '".$from_date."' and date <= '".$to_date."') ".$branch_." order by o.firstName, o.lastName, d.cheque_no ");

	if(@ mysql_numrows($sth) == 0){
		$cont = "<font color=red>No loans in your search options</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	
	$former_officer ="";
	$i=$stat+1;
	$princ_paid_sub_total=0;
	$int_paid_sub_total=0;
	$amt_sub_total=0;
	$pen_sub_total=0;
	$total_paid_sub_total=0;
	
	while($row = mysql_fetch_array($sth2)){
		$officer = $row['of_firstName']." ".$row['of_lastName'];
		if(strcmp($former_officer, $officer) != 0){
		
		
		$content .="<a href='export/loanRepayments?cust_name=".$cust_name."&branch_id=".$branch_id."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&status=".$status."' target=blank()><b>Printable Version</b></a> | <a href='export/loanRepayments?cust_name=".$cust_name."&branch_id=".$branch_id."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&status=".$status."&format=excel' target=blank()><b>Export Excel</b></a>";
		
		$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <p><h5 class="text-primary mt0">Loan Officer:  '.$officer.'</h5></p>
                                
                                
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">
		
			
			
			 <thead><th><b>#</b></th><th><b>Date Disbursed</b></th><th><b>MemberNo</b></th><th><b>Member Name</b></th><th><b>Product</b></th><th><b>Cheque No</b></th><th><b>Amount</b></th><th><b>Princ Paid</b></th><th><b>Int Paid</b></th><th><b>Penalties</b></th><th><b>Total Paid</b></th></thead><tbody>';
			$former_officer = $officer;
		}
	
		$paid_res = mysql_query("select sum(princ_amt) as princ_amt, sum(int_amt) as int_amt from payment where loan_id='".$row['id']."' and date >='".$from_date."' and date <= '".$to_date."'");
		$paid = mysql_fetch_array($paid_res);

		$pen_res = mysql_query("select sum(amount) as amount from penalty where loan_id='".$row['id']."' and date >='".$from_date."' and date <= '".$to_date."' and status='paid'");
		$pen = mysql_fetch_array($pen_res);
		$pen_amt = ($pen['amount'] != NULL) ? $pen['amount'] : 0;
		
		$princ_paid_amt = ($paid['princ_amt'] == NULL) ? 0 : $paid['princ_amt'];
		$int_paid_amt = ($paid['int_amt'] == NULL) ? 0 : $paid['int_amt'];
		
		////$color=($i % 2==0) ? "lightgrey" : "white";
		$content .= "<tr><td>".$i."</td><td>".$row['date']."</td><td>".$row['mem_no']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['account_no']." - ".$row['account_name']."</td><td>".$row['cheque_no']."</td><td>".number_format($row['amount'], 2)."</td><td>".number_format($princ_paid_amt, 2)."</td><td>".number_format($int_paid_amt, 2)."</td><td>".number_format($pen_amt, 2)."</td><td>".number_format(($princ_paid_amt + $int_paid_amt + $pen_amt), 2)."</td></tr>";
		$i++;
	// GET SUB TOTALS
		$amt_sub_total += $row['amount']; 
		$princ_paid_sub_total += $princ_paid_amt; 
		$int_paid_sub_total += $int_paid_amt; 
		$pen_sub_total += $pen_amt; 
		$total_paid_sub_total += ($int_paid_amt + $princ_paid_amt + $pen_amt);
	}
	// PRINT SUB TOTALS
	$content .= "<tr><td></td><td></td><td></td><td></td><td></td><td><b>TOTAL</b></td><td><b>".number_format($amt_sub_total, 2)."</b></td><td><b>".number_format($princ_paid_sub_total, 2)."</b></td><td><b>".number_format($int_paid_sub_total, 2)."</b></td><td><b>".number_format($pen_sub_total, 2)."</b></td><td><b>".number_format($total_paid_sub_total, 2)."</b></td></tr>";
	$content .="</tbody></table></div>";
	}
	$resp->call("createTableJs");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


//REPAYMENT REPORT
function repayment($cust_name, $cust_no, $account_name, $loan_officer, $from_date,$to_date, $status,$branch_id){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$resp->assign("status", "innerHTML", "");
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:'and d.branch_id='.$branch_id;
	
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR REPAYMENT REPORT</h3>
                                <p class="text-default nm"></p>
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
	$officer_res = mysql_query("select * from employees order by first_name, last_name");
	while($officer = mysql_fetch_array($officer_res)){
		$content .= "<option value='".$officer['id']."'>".$officer['first_name']." ".$officer['last_name'];
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
                                            <label class="control-label">Loan Status:</label>
                                            <select id="state" class="form-control"><option value="">All<option value="On going">On going<option value="Over Due">Over Due</select>                                           
                                        </div>
                           
                                    
                                </div>                          
                            '; 
		
		 $content .= "<div class='panel-footer'>                              
                                
                                <button type='button' class='btn  btn-primary'  onclick=\"xajax_repayment(getElementById('cust_name').value, getElementById('cust_no').value, getElementById('account_name').value, getElementById('officer_id').value,  getElementById('from_date').value,  getElementById('to_date').value, getElementById('state').value,getElementById('branch_id').value);\">Search</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","from_date");
	$resp->call("createDate","to_date"); 
                    
                //$resp->assign("display_div", "innerHTML", $content);
	
	if($from_date=='' || $to_date==''){
		$cont= "<font color=red>Please select the options for the Arrears report!</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
	
	$content .="<a href='export/repayment?cust_name=".$cust_name."&branch_id=".$branch_id."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&status=".$status."' target=blank()><b>Printable Version</b></a> | <a href='export/repayment?cust_name=".$cust_name."&branch_id=".$branch_id."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&status=".$status."&format=excel' target=blank()><b>Export Excel</b></a>";
	
	$name = ($cust_name == 'All') ? "" : $cust_name;
	$mem_no = ($cust_no == 'All') ? "" : $cust_no;
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_year);
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
	$sth = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, (DATEDIFF(CURDATE(), d.last_pay_date) - d.arrears_period) as arrears_days, datediff(CURDATE(), d.date) as ellapsed_time, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.written_off='0'".$state. " ".$branch_." order by o.firstName, o.lastName");

	$sth2 = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, (DATEDIFF(CURDATE(), d.last_pay_date) - d.arrears_period) as arrears_days, datediff(CURDATE(), d.date) as ellapsed_time, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, d.last_pay_date as last_pay_date, d.pay_freq as pay_freq, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.written_off='0'".$state. " ".$branch_." order by o.firstName, o.lastName ");
	
	if(@ mysql_numrows($sth) == 0){
		$cont= "<font color=red>No loans in your search options</font>";
		$resp->assign("status", "innerHTML", $cont);
		return $resp;
	}
	
	$former_officer ="";
	$i=$stat+1;
	$total_arrears =0.0;
	$amt_sub_total=0.0;
	$princ_paid_sub_total=0.0;
	$int_paid_sub_total=0.0;
	$total_paid_sub_total=0.0;
	$princ_arrears_sub_total=0.0;
	$int_arrears_sub_total=0.0;
	$prepaid_sub_total=0.0;
	$due_int=0.0;
	$int_due_sub_total=0.0;
	$balance_sub_total=0.0;
	
	////$max_page = ceil(mysql_num_rows($sth)/$num_rows);
	while($row = mysql_fetch_array($sth2)){
		$officer = $row['of_firstName']." ".$row['of_lastName'];
		if(strcmp($former_officer, $officer) != 0){
		
		$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                            <p><h4 class="semibold text-primary mt0 mb5">REPAYMENT REPORT</h4></p>
                                <h3 class="panel-title">Loan Officer:'.$officer.'</h3>
                                                              
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">
			
			<thead><th><b>#</b></th><th><b>Date Disbursed</b></th><th><b>MemberNo</b></th><th><b>Member Name</b></th><th><b>Product</b></th><th><b>Amount</b></th><th><b>Princ Paid To Date</b></th><th><b>Int Paid To Date</b></th><th><b>Total Paid To Date</b></th><th><b>Princ Arrears</b></th><th><b>Int Arrears</b></th><th><b>Amt Prepaid To Date</b></th></th><th><b>Repayment Rate(%)</b></th><th><b>Cummulated Repayment Rate(%)</b></th><th><b>Outstanding Balance</b></th></thead><tbody>';
			$former_officer = $officer;
		}
		//RESCHEDULE INTEREST FOR DECLINING BALANCE METHOD
		if($row['int_method'] == 'Declining Balance'){
			$sched_res = mysql_query("select id from schedule where loan_id='".$row['id']."' and princ_amt>0 and date> '".$row['last_pay_date']."' and date <= '".$to_date."'");
			while($sched = mysql_fetch_array($sched_res)){
				$new_int = $row['balance'] * (($row['int_rate']/100)/12) * ($row['pay_freq']/30);
				mysql_query("update schedule set int_amt ='".$new_int."' where id='".$sched['id']."'");
			}
		}

		//ARREARS
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id='".$row['id']."' and date < DATE_SUB(CURDATE(), INTERVAL ".$row['arrears_period']." DAY)");
		$sched = mysql_fetch_array($sched_res);
	
		$paid_res = mysql_query("select sum(princ_amt) as princ_amt, sum(int_amt) as int_amt from payment where loan_id='".$row['id']."' and date <= CURDATE()");
		
		$paid = mysql_fetch_array($paid_res);
		$sched_amount = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? 0 : $sched['int_amt'];
		$princ_paid_amt = ($paid['princ_amt'] == NULL) ? 0 : $paid['princ_amt'];
		$int_paid_amt = ($paid['int_amt'] == NULL) ? 0 : $paid['int_amt'];
		$arrears_amt = $sched_amount - $princ_paid_amt;
		$int_arrears = $sched_int - $int_paid_amt;
		
		if($arrears_amt <= 0){
			$princ_arrears =0.0;
			$int_arrears = 0.0;
			$total_arrears =0.0;
			$total_amt_arrears = 0;
			$int_amt_arrears =0;
			$princ_amt_arrears =0;
		}else{
			$princ_arrears = $arrears_amt;
			$princ_amt_arrears = $arrears_amt;
			$total_amt_arrears = $total_arrears;
		}
		if($int_arrears <= 0){
			$int_arrears = 0.0;
			$int_amt_arrears =0;
		}else{
			$int_amt_arrears = $int_arrears;
		}
		$total_arrears = $princ_amt_arrears + $int_amt_arrears;
		$total_amt_arrears = $total_arrears;

		//CUMMULATIVE RATES
		//CHECK WHETHER ALL LOAN IS OVER DUE
		if($row['ellapsed_time'] >= $row['loan_period'] + $row['arrears_period']){
			$cumm_repay_rate = 0.00;
			$cumm_arrears_rate = 0.00;
			$repay_rate = 0.00;
			$arrears_rate = 0.00;
		}else{
			$sched_amount = ($sched_amount == 0) ? 1 : $sched_amount;
			$cumm_repay_rate = ($princ_paid_amt * 100) / $sched_amount;
			$cumm_repay_rate = sprintf("%.02f", $cumm_repay_rate);
			if($cumm_repay_rate >= 100.00)
				$cumm_arrears_rate = 0.00;
			else
				$cumm_arrears_rate = 100.00 - $cumm_repay_rate;
			$nowsched_res = mysql_query("select * from schedule where loan_id='".$row['id']."' and date < CURDATE() order by date desc limit 1");
			$nowsched = mysql_fetch_array($nowsched_res);
			$nowpaid_res = mysql_query("select sum(princ_amt) as princ_amt from payment where loan_id='".$row['id']."' and date >= '".$nowsched['date']."' and date < DATE_ADD(date, INTERVAL 30 DAY)");
			
			$nowpaid = mysql_fetch_array($nowpaid_res);
			$nowsched_amt = ($nowsched['princ_amt'] == NULL || $nowsched['princ_amt'] == 0) ? 1 : $nowsched['princ_amt'];
			$nowpaid_amt = ($nowpaid['princ_amt'] == NULL) ? 0 : $nowpaid['princ_amt'];
			$repay_rate = $nowpaid_amt / $nowsched_amt;
			$repay_rate = sprintf("%.02f", $repay_rate);
			$arrears_rate = 100.00 - $repay_rate;
		}
		//PREPAID PRINC
		$allpaid_res = mysql_query("select sum(princ_amt) as princ_amt from payment where loan_id='".$row['id']."'");
		$allpaid = mysql_fetch_array($allpaid_res);
		$allpaid_amt = ($allpaid['princ_amt'] == NULL) ? 0 : $allpaid['princ_amt'];

		$prepaid = $allpaid_amt - $princ_paid_amt;
		//$prepaid = ($prepaid == 0) ? "--" : $prepaid;

		////$color=($i % 2==0) ? "lightgrey" : "white";
		$content .= "<tr><td>".$i."</td><td>".$row['date']."</td><td>".$row['mem_no']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['account_no']." - ".$row['account_name']."</td><td>".number_format($row['amount'], 2)."</td><td>".number_format($princ_paid_amt, 2)."</td><td>".number_format($int_paid_amt, 2)."</td><td>".number_format(($princ_paid_amt + $int_paid_amt), 2)."</td><td>".number_format($princ_arrears, 2)."</td><td>".number_format($int_arrears, 2)."</td><td>".number_format($prepaid, 2)."</td><td>".$repay_rate."</td><td>".$cumm_repay_rate. "</td><td>".number_format($row['balance'], 2)."</td></tr>";
		$i++;
	// GET SUB TOTALS
		$amt_sub_total += $row['amount']; 
		$princ_paid_sub_total += $princ_paid_amt; 
		$int_paid_sub_total += $int_paid_amt; 
		$total_paid_sub_total += ($int_paid_amt + $princ_paid_amt);
		$princ_arrears_sub_total += $princ_arrears;
		$int_arrears_sub_total += $int_arrears; 
		$prepaid_sub_total += $prepaid; 
		$int_due_sub_total += $due_int; 
		$balance_sub_total += $row['balance'];
	}
	// PRINT SUB TOTALS
	$content .= "<tr><td></td><td></td><td></td><td></td><td><b>TOTAL</b></td><td><b>".number_format($amt_sub_total, 2)."</b></td><td><b>".number_format($princ_paid_sub_total, 2)."</b></td><td><b>".number_format($int_paid_sub_total, 2)."</b></td><td><b>".number_format($total_paid_sub_total, 2)."</b></td><td><b>".number_format($princ_arrears_sub_total, 2)."</b></td><td><b>".number_format($int_arrears_sub_total, 2)."</b></td><td><b>".number_format($prepaid_sub_total, 2)."</b></td><td></td><td><b></b></td><td ><b>".number_format($balance_sub_total, 2)."</b></td></tr>";
	$content .="</tbody></table></div>";
	}
	$resp->call("createTableJs");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


//LIST OF WRITTEN OFF LOANS
function list_written_off($cust_name, $cust_no, $account_name, $loan_officer, $from_date,$to_date, $branch_id){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:'and d.branch_id='.$branch_id;

       $content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title"> SEARCH FOR WRITTEN-OFF LOANS</h3>
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
	$officer_res = mysql_query("select * from employees order by first_name, last_name");
	while($officer = mysql_fetch_array($officer_res)){
		$content .= "<option value='".$officer['id']."'>".$officer['first_name']." ".$officer['last_name'];
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
                                
                                <button type='button' class='btn  btn-primary'  onclick=\"xajax_list_written_off(getElementById('cust_name').value, getElementById('cust_no').value, getElementById('account_name').value, getElementById('officer_id').value,  getElementById('from_date').value,  getElementById('to_date').value,getElementById('branch_id').value);\">Search</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","from_date");
	$resp->call("createDate","to_date");
                    
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
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_year);
	if($loan_officer >0)
		$officer = "o.employeeId='".$loan_officer."'";
	else
		$officer = "o.employeeId > 0";
	
	$sth = mysql_query("select w.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.amount as disburse_amount, d.date as disburse_date, w.balance as write_balance,  d.bank_account as bank_account, w.amount as write_amount, w.date as write_date from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId join written_off w on w.loan_id=d.id where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.written_off='1' ".$branch_);
	
	//$max_page = ceil(mysql_num_rows($sth)/$num_rows);
	$sth2 = mysql_query("select w.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.amount as disburse_amount, d.date as disburse_date, w.balance as write_balance,  d.bank_account as bank_account, w.amount as write_amount, w.date as write_date from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId join written_off w on w.loan_id=d.id where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.written_off='1' ".$branch_ );
	if(@ mysql_numrows($sth) == 0){
		$cont = "<font color=red>No loans written off in your search options</font>";
		$resp->assign("status", "innerHTML", $cont);
		return $resp;
	}
	
	$former_officer ="";
	$i=$stat+1;
	while($row = mysql_fetch_array($sth2)){
		//$color=($i%2 == 0) ? "lightgrey" : "white";
		$officer = $row['of_firstName']." ".$row['of_lastName'];
		if(strcmp($former_officer, $officer) != 0){
		
		$content .= "<<a href='export/writtenOff?cust_name=".$cust_name."&branch_id=".$branch_id."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."' target=blank()><b>Printable Version</b></a> | <a href='export/writtenOff?cust_name=".$cust_name."&branch_id=".$branch_id."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&format=excel' target=blank()><b>Export Excel</b></a>";
		
		 $content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                            <p><h4 class="semibold text-primary mt0 mb5">LIST OF WRITTEN-OFF LOANS</h4></p>
                                <p><h5 class="text-primary mt0">Loan Officer:'.$officer.'</h3></p>
                                                            
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">
 					
			<thead><th><b>#</b></th><th><b>Date Disbursed</b></th><th><b>Member No</b></th><th><b>Member Name</b></th><th><b>Product</b></th><th><b>Disbursed Amount</b></th><th><b>Amount Written-off</b></th><th><b>Write-off Date</b></th><th><b>Written-off Balance</b></th></thead><tbody>';
			$former_officer = $officer;
		}
		$content .= "<tr><td>".$i."</td><td>".$row['disburse_date']."</td><td>".$row['mem_no']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['account_no']." - ".$row['account_name']."</td><td>".number_format($row['disburse_amount'], 2)."</td><td>".number_format($row['write_amount'], 2)."</td><td>".$row['write_date']."</td><td>".number_format($row['write_balance'], 2)."</td></tr>";
		$i++;
	}
	$content .="</tbody></table></div>";
	}
	$resp->call("createTableJs");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


//LIST CLEARED LOANS
function list_cleared($cust_name, $cust_no, $account_name, $loan_officer, $from_date,$to_date,$branch_id){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$resp->assign("status", "innerHTML", "");
	$branch_res = mysql_query("select * from branch");
	$branch = mysql_fetch_array($branch_res);
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:'and d.branch_id='.$branch_id;
	
	
       $content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title"><b>'.$branch['branch_name'].'</b></h3>
                                <p class="text-default nm">SEARCH FOR CLEARED LOANS</p>
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
	$officer_res = mysql_query("select * from employees order by first_name, last_name");
	while($officer = mysql_fetch_array($officer_res)){
		$content .= "<option value='".$officer['id']."'>".$officer['first_name']." ".$officer['last_name'];
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
                                
                                <button type='button' class='btn  btn-primary'  onclick=\"xajax_list_cleared(getElementById('cust_name').value, getElementById('cust_no').value, getElementById('account_name').value, getElementById('officer_id').value,  getElementById('from_date').value,  getElementById('to_date').value,getElementById('branch_id').value);\">Search</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    
                    $resp->call("createDate","from_date");
	$resp->call("createDate","to_date"); 
                    
                //$resp->assign("display_div", "innerHTML", $content);	
	
	if($from_date=='' || $to_date==''){
		$cont = "<font color=red>Please select the Cleared loans you would like to list!</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
	$name = ($cust_name == 'All') ? "" : $cust_name;
	$mem_no = ($cust_no == 'All') ? "" : $cust_no;
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_year);
	if($loan_officer >0)
		$officer = "o.employeeId='".$loan_officer."'";
	else
		$officer = "o.employeeId > 0";
	
	$sth = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, (d.period/30) as loan_period,  DATEDIFF(d.last_pay_date, d.date) as actual_days from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.balance <=0 ".$branch_." order by o.firstName, o.lastName");
	
	//$max_page = ceil(mysql_num_rows($sth)/$num_rows);
	$sth2 = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, (d.period/30) as loan_period,  DATEDIFF(d.last_pay_date, d.date) as actual_days from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.balance <=0 ".$branch_." order by o.firstName, o.lastName ");
	if(@ mysql_numrows($sth) == 0){
		$cont = "<font color=red>No cleared loans in your search options</font>";
		$resp->assign("status", "innerHTML", $cont);
		return $resp;
	}
	
	$former_officer ="";
	$i=$stat+1;
	$princ_amt_arrears=0;
	$int_amt_arrears=0;
	$due_princ_amt=0;
	$due_int_amt=0;
	while($row = mysql_fetch_array($sth2)){
		$officer = $row['of_firstName']." ".$row['of_lastName'];
		if(strcmp($former_officer, $officer) != 0){
		
		
		$content .="<a href='export/cleared?cust_name=".$cust_name."&branch_id=".$branch_id."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."' target=blank()><b>Printable Version</b></a> | 		<a href='export/cleared?cust_name=".$cust_name."&branch_id=".$branch_id."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&format=excel' target=blank()><b>Export Excel</b></a>";
		
		 $content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                            <p><h4 class="semibold text-primary mt0 mb5">LIST OF CLEARED LOANS</h4></p>
                                <p><h5 class="text-primary mt0">Loan Officer: '.$officer.'</h5></p>
                                                            
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">
			
			<thead><th><b>#</b></th><th><b>Date</b></th><th><b>MemberNo</b></th><th><b>Member Name</b></th><th><b>Product</b></th><th><b>Amount</b></th><th><b>Total Interest Paid</b></th><th><b>Total Penalties Paid</b></th><th><b>Loan Period Awarded</b></th><th><b>Actual Period Taken</b></th><th><b>Schedule</b></th><th><b>Payments</b></th></thead><tbody>';
			$former_officer = $officer;
		}
		//INTEREST
		$int_res = mysql_query("select sum(int_amt) as int_amt from payment where loan_id='".$row['id']."'");
		$int = mysql_fetch_array($int_res);
		//PENALTIES
		$pen_res = mysql_query("select * from penalty where loan_id='".$row['id']."'");
		$pen = mysql_fetch_array($pen_res);
		$pen_amt = ($pen['amount'] == NULL) ? 0 : $pen['amount']; 
		//ACTUAL PERIOD TAKEN
		$actual_months = floor($row['actual_days']/30);
		$actual_days = $row['actual_days'] % 30;
		$actual_period = $actual_months. " Months, ". $actual_days. " Days";
		
		$pay = "<a href='export/payments?loan_id=".$row['id']."&applic_id=".$row['applic_id']."&cust_name=".$cust_name."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&princ_arrears=".$princ_amt_arrears."&int_arrears=".$int_amt_arrears."&princ_due=".$due_princ_amt."&int_due=".$due_int_amt."&from-year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&status=outstanding&format=excel' target=blank()>Payments</a>";
		
		$schedule = "<a href='export/schedule?loan_id=".$row['id']."&applic_id=".$row['applic_id']."&cust_name=".$cust_name."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&princ_arrears=".$princ_amt_arrears."&int_arrears=".$int_amt_arrears."&princ_due=".$due_princ_amt."&int_due=".$due_int_amt."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&status=outstanding&format=excel' target=blank()>Schedule</a>";


		//PENALTY
		$pen_res = mysql_query("select sum(amount) as amount from penalty where status='pending' and loan_id='".$row['id']."'");
		$pen = mysql_fetch_array($pen_res);
		$penalty = ($pen['amount'] == NULL) ? 0 : $pen['amount'];
		////$color=($i % 2==0) ? "lightgrey" : "white";
		$content .= "<tr><td>".$i."</td><td>".$row['date']."</td><td>".$row['mem_no']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['account_no']." - ".$row['account_name']."</td><td>".number_format($row['amount'], 2)."</td><td>".number_format($int['int_amt'], 2)."</td><td>".number_format($pen_amt, 2)."</td><td>".ceil($row['loan_period'])." Months</td><td>".$actual_period."</td><td>".$schedule."</td><td>".$pay."</td></tr>";
		$i++;
	}
	$content .= "</tbody></table></div>";
	}
	$resp->call("createTableJs");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//LOAN LEDGER
function loan_ledger($mem_no, $from_date,$to_date){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	$branch_res = mysql_query("select * from branch");
	$branch = mysql_fetch_array($branch_res);	
	
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <p><h5 class="text-primary mt0"><b>'.$branch['branch_name'].'</b></h5></p>
                                <p class="text-default nm">SEARCH FOR LOAN LEDGER</p>
                            </div>               
                            <div class="panel-body">
                            
                             <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Date range:</label>
                                            <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                            
 		$content .= "<div class='form-group'>
                                    <div class='row'>
                                        <div class='col-sm-6'>
                                        <label class='control-label'>Enter Member No:</label>
                                        <div class='input-group'>
                                            
                                            <input type=int name='mem_no1' id='mem_no1' class='form-control'>
                                              <span class='input-group-btn'>
                                                <button class='btn btn-info' type='button' onclick=\"xajax_loan_ledger(getElementById('mem_no1').value,  getElementById('from_date').value,  getElementById('to_date').value); return false;\">Show Ldger</button>
                                            </span>
                                        </div></div>
                                        <div class='col-sm-6'>
                                            <label class='control-label'>OR Select:</label>
                                            <div class='input-group'>
                                           <select name='mem_no2' id='mem_no2' class='form-control'><option value='0'>Select Member";
		$sth = mysql_query("select distinct m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name from loan_applic applic join member m on applic.mem_id=m.id where applic.id in (select applic_id from disbursed) order by m.first_name, m.last_name");
	while($row = mysql_fetch_array($sth)){
		$content .= "<option value='".$row['mem_no']."'>".$row['mem_no']." - ".$row['first_name']." ".$row['last_name'];
	}    
                                        $content.="</select>
                                            <span class='input-group-btn'>
                                                <button class='btn btn-info' type='button' onclick=\"xajax_loan_ledger(getElementById('mem_no2').value,  getElementById('from_date').value,  getElementById('to_date').value);\">Show Ldger</button>
                                            </span>
                                        </div>
                                        </div>
                                    </div>
                                </div>";
                                
                                $content.='</div></form></div></div>';
                                $resp->call("createDate","from_date");
	$resp->call("createDate","to_date");
                                
                  //$resp->assign("display_div", "innerHTML", $content);
                                 
	if($from_date=='' || $to_date==''){
		
		$cont = "<font color=red>Please select options of the ledger.</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
	}
	elseif($mem_no==''){
		$cont = "<font color=red>Please enter the Member No or select the member!</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;

	}else{
		$over_res = mysql_query("select * from member where mem_no='".$mem_no."'");
		if(mysql_numrows($over_res) == 0){
			$cont = "<font color=red>The Member No entered does not exist!</font>";
			$resp->assign("status", "innerHTML", $cont);
			//return $resp;
		}
		$over=mysql_fetch_array($over_res);
		$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
		$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_year);
		
		//START BALANCE
		$bal_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."'  and d.date < '".$from_date."'"); 
		$bal = mysql_fetch_array($bal_res);
		$pay_res = mysql_query("select sum(p.princ_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where p.date < '".$from_date."' and applic.mem_id='".$over['id']."'");
		$pay = mysql_fetch_array($pay_res);
		$bal_amt = ($bal['amount'] == NULL) ? 0 : $bal['amount'];
		$pay_amt = ($pay['amount'] == NULL) ? 0 : $pay['amount'];
		$start_bal = $bal_amt - $pay_amt;

		$loan_res = mysql_query("select d.date as date, d.amount as amount from disbursed d join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' d.date >= '".$from_date."' and d.date <= '".$to_date."' and applic.mem_id='".$over['id']."'"); 

		$paid_res = mysql_query("select p.date as date, p.princ_amt as amount, p.int_amt as int_amt, p.receipt_no as receipt_no from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and p.date >= '".$from_date."' and p.date <= '".$to_date."' and applic.mem_id='".$over['id']."' order by p.date asc");

		$pen_res = mysql_query("select pen.amount as amount, pen.date as date, pen.status as status from penalty pen join disbursed d on pen.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' pen.date >= '".$from_date."' and pen.date <= '".$to_date."' and applic.mem_id='".$over['id']."'");
		
		$content .= "<a href='export/loan_ledger?mem_no=".$mem_no."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."' target=blank()><b>Printable Version</b></a> | <a href='export/loan_ledger?mem_no=".$mem_no."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&format=excel' target=blank()><b>Export Excel</b></a>";
		
		$content .= "<div class='panel panel-default' id='demo'>
                            <div class='panel-heading'>
                                <p><h5 class='text-primary mt0'>LOAN LEDGER</h5></p>
                                <p>Member Name: <b>".$over['first_name']." ".$over['last_name']."</b></p>
                                <p>Member No: <b>".$over['mem_no']."</b></p>
                            </div>";
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
		
		$content .= "<thead><th><b>Date</b></th><th><b>Description of Transaction</b></th><th><b>Interest</b></th><th><b>Debit</b></th><th><b>Credit</b></th><th><b>Balance</b></th></thead><tbody>
		<tr><td>Before ".$from_date."</td><td>Start Balance</td><td>--</td><td>--</td><td>--</td><td>".number_format($start_bal, 2)."</td></tr>";
		$last_date = $from_date;
		$balance = $start_bal;
		//if(mysql_numrows($paid_res) >0){
			$i=1;
			while($paid = mysql_fetch_array($paid_res)){
				$loan_res = mysql_query("select d.date as date, d.amount as amount from disbursed d join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and d.date >= '".$last_date."' and d.date < '".$paid['date']."' and applic.mem_id='".$over['id']."' order by d.date asc");
				while($loan = mysql_fetch_array($loan_res)){
					$pen_res = mysql_query("select pen.amount as amount, pen.date as date, pen.status as status from penalty pen join disbursed d on pen.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and pen.date >= '".$last_date."' and pen.date < '".$loan['date']."' and applic.mem_id='".$over['id']."' order by pen.date asc");
					while($pen = mysql_fetch_array($pen_res)){
						//$color=($i%2 == 0) ? "lightgrey" : "white";
						if($pen['status'] == 'pending'){
							$balance = $balance + $pen['amount'];
						
							$content .= "<tr><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
						}else
							$content .= "<tr><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>".$pen['amount']."</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
						$last_date = $pen['date'];
						$i++;
					}
					$balance = $balance + $loan['amount'];
					//$color=($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr><td>".$loan['date']."</td><td>Loan Disbursed</td><td>--</td><td>--</td><td>".number_format($loan['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
					$last_date = $loan['date'];
					$i++;
				}
				$pen_res = mysql_query("select pen.amount as amount, pen.date as date, pen.status as status from penalty pen join disbursed d on pen.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and pen.date >= '".$last_date."' and pen.date < '".$paid['date']."' order by pen.date asc");
				while($pen = mysql_fetch_array($pen_res)){
					//$color=($i%2 == 0) ? "lightgrey" : "white";
					if($pen['status'] == 'pending'){
						$balance = $balance + $pen['amount'];
						$content .= "<tr><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
					}else
						$content .= "<tr><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
						$i++;
				}
				$balance = $balance - $paid['amount'];
				//$color=($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr><td>".$paid['date']."</td><td>Repayment<br>RCPT: ".$paid['receipt_no']."</td><td>--</td><td>".number_format($paid['amount'], 2)."</td><td>--</td><td>".number_format($balance, 2)."</td></tr>";
				$last_date = $paid['date'];
				$i++;
				//INTEREST
				if($paid['int_amt'] != 0){
					//$color=($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr><td>".$paid['date']."</td><td>Interest Paid<br>RCPT: ".$paid['receipt_no']."</td><td>".number_format($paid['int_amt'], 2)."</td><td>--</td><td>--</td><td>".number_format($balance, 2)."</td></tr>";
					//$last_date = $paid['date'];
					$i++;
				}
			}
			$loan_res = mysql_query("select d.date as date, d.amount as amount from disbursed d join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and d.date > '".$last_date."' and d.date < '".$to_date."' and applic.mem_id='".$over['id']."' order by d.date asc");
			while($loan = mysql_fetch_array($loan_res)){
				$pen_res = mysql_query("select pen.amount as amount, pen.date as date, pen.status as status from penalty pen join disbursed d on pen.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and pen.date >= '".$last_date."' and pen.date < '".$loan['date']."' and applic.mem_id='".$over['id']."' order by pen.date asc");
				while($pen = mysql_fetch_array($pen_res)){
					//$color=($i%2 == 0) ? "lightgrey" : "white";
					if($pen['status'] == 'pending'){
						$balance = $balance + $pen['amount'];
						$content .= "<tr><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
					}else
						$content .= "<tr><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
					$last_date = $pen['date'];
					$i++;
				}
				$balance = $balance + $loan['amount'];
				//$color=($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr><td>".$loan['date']."</td><td>Loan Disbursed</td><td>--</td><td>--</td><td>".number_format($loan['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
				$last_date = $loan['date'];
				$i++;
			}
			$pen_res = mysql_query("select pen.amount as amount, pen.date as date, pen.status as status from penalty pen join disbursed d on pen.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and pen.date >= '".$last_date."' and pen.date <= '".$to_date."' and applic.mem_id='".$over['id']."' order by pen.date asc");
			while($pen = mysql_fetch_array($pen_res)){
				if($pen['status'] == 'pending'){
					//$color=($i%2 == 0) ? "lightgrey" : "white";
					$balance = $balance + $pen['amount'];
					$content .= "<tr><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
				}else
					$content .= "<tr><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
				$last_date = $pen['date'];
				$i++;
			}
	
	$content .= "</tbody></table></div>";
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


//PRINT REPAYMENT SCHEDULE
function schedule($loan_id, $applic_id,   $cust_name, $cust_no, $account_name, $loan_officer, $from_date, $to_date, $status){
	$resp = new xajaxResponse();
	//PRINT THE REPAYMENT SCHEDULE
	$sth = mysql_query("select date, begin_bal, end_bal, princ_amt, int_amt, (princ_amt + int_amt) as total_amt  from schedule where loan_id='".$loan_id."' order by date asc");
	//if(@ mysql_numrows($sth) > 0){
	$pay_res = mysql_query("select m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no from loan_applic applic join member m on applic.mem_id=m.id where applic.id='".$applic_id."'");
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
			<tr><td>Scheduled Payment</td><td>".number_format(($disb['next_princ_amt']+$disb['next_int_amt']), 2)."</td></tr>
			<tr><td>Scheduled Number of Payments</td><td>".($disb['period']/30)."</td></tr>
			<tr><td>Total Interest</td><td>".number_format($sched['total_int'], 2)."</td></tr>
		</table>
	</td></tr>
	</table>
		<table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='80%' id='AutoNumber2' align=center>
			<tr bgcolor=#cdcdcd><td><b>No</b></td><td><b>Date</b></td><td><b>Beginning Balance</b></td><td><b>Total Payment</b></td><td><b>Principal</b></td><td><b>Interest</b></td><td><b>Ending Balance</b></td></tr>";
	$i=1;
	while($row = mysql_fetch_array($sth)){
		$end_bal = ($row['end_bal'] <= 0) ? 0 : $row['end_bal'];
		$content .= "<tr><td>".$i."</td><td>".$row['date']."</td><td>".number_format($row['begin_bal'], 2)."</td><td>".number_format($row['total_amt'], 2)."</td><td>".number_format($row['princ_amt'], 2)."</td><td>".number_format($row['int_amt'], 2)."</td><td>".number_format($end_bal, 2)."</td></tr>";
		$i++;
	}
	if($status == 'outstanding')
		$back = "<input type=button value='Back' onclick=\"xajax_list_outstanding('".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."','".$to_date."');\">";
	elseif($status == 'due')
		$back = "<input type=button value='Back' onclick=\"xajax_list_due('".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."','".$to_date."');\">";
	elseif($status=='cleared')
		$back = "<input type=button value='Back' onclick=\"xajax_list_cleared('".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$from_date."','".$to_date."');\">";
	$content .= "</table>
	<table height=100 border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='80%' id='AutoNumber2' align=center>
		<tr><td>".$back."</td></tr>
	</table>";
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

// PRINT THE SUB-MENU FOR THE MODULE CHOSEN
function reports($module){

        $accts ='';
	$resp = new xajaxResponse();
	if($module == 'loans'){
		$sub_menu = "<table width=100%><tr><td><a href='javascript:;' onclick=\"xajax_list_loanproducts(); return false;\">List Loan Products</a></td><td><a href='javascript:;' onclick=\"xajax_list_groups(''); return false;\">List Groups</a></td><td><a href='javascript:;' onclick=\"xajax_list_applics('', '', '', '', '', '', '', '', '', 'all','','100','0','1'); return false;\">All Applications</a></td><td><a href='javascript:;' onclick=\"xajax_list_applics('', '', '', '', '', '', '', '', '', 'approval','','100','0','1'); return false;\">Pending Approval</a></td><td><a href='javascript:;' onclick=\"xajax_list_applics('', '', '', '', '', '', '', '', '', 'disbursement','','100','0','1'); return false;\">Pending Disbursement</a></td><td><a href='javascript:;' onclick=\"xajax_list_disbursed('', '', '', '', '', '', '', '', '', '','','100','0','1'); return false;\">Disbursement Report</a></td><td><a href='javascript:;' onclick=\"xajax_list_outstanding('', '', '', '', '', '', '', '', '', '','','100','0','1'); return false;\">Outstanding Loans</a></td><td><a href='javascript:;' onclick=\"xajax_list_due('', '', '', '', '', '', '', '', '', '','','100','0','1'); return false;\">Due Payments</a></td></tr>
<tr><td><a href='javascript:;' onclick=\"xajax_loan_repayments('', '', '', '', '', '', '', '', '', '', '','','100','0','1'); return false;\">Loan Repayments</a></td><td><a href='javascript:;' onclick=\"xajax_repayment('', '', '', '', '', '', '', '', '', '', '','','100','0','1'); return false;\">Repayment Report</a></td><td><a href='javascript:;' onclick=\"xajax_list_arrears('', '', '', '', '', '', '', '', '', '', '','','100','0','1'); return false;\">Arrears Report</a></td><td><a href='javascript:;' onclick=\"xajax_ageing('', '', '', '', '', '', '', '', '', '', '','100','0','1'); return false;\">Ageing Report</a></td><td><a href='javascript:;' onclick=\"xajax_risk_ageing('', '', '', '', '', '', '', '', '', '', '','100','1','1'); return false;\">Portfolio At Risk By Ageing Report</a></td><td><a href='javascript:;' onclick=\"xajax_portifolio_status('', '', '', '', '', '', '', '', '', '', '','','100','0','1'); return false;\">Portfolio Status Report</a></td><td><a href='javascript:;' onclick=\"xajax_list_written_off('', '', '', '', '', '', '', '', '', '','','100','0','1'); return false;\">Written-off</a></td><td><a href='javascript:;' onclick=\"xajax_list_cleared('', '', '', '', '', '', '', '', '', '','','100','0','1'); return false;\">Cleared Loans</a></td></tr>
	<tr><td><a href='javascript:;' onclick=\"xajax_loan_ledger('', '', '', '', '', '', '');\">Loan Ledger</a></td><td><a href='javascript:;' onclick=\"xajax_groups_status('', '', '', '','');\">Groups Status Report</a></td></tr></table>";
	}elseif($module == 'financial'){
		$sub_menu = "<table width=100%><tr><td><a href='javascript:;' onclick=\"xajax_assettrial_balance('', '', '', 'Cash Basis','');\">Sub. (Assets) Trial Balance</a></td><td><a href='javascript:;' onclick=\"xajax_lctrial_balance('', '', '', 'Cash Basis','');\">Sub. (Liabilities & Capital)Trial Balance</a></td><td><a href='javascript:;' onclick=\"xajax_incometrial_balance('', '', '', 'Cash Basis','');\">Sub. (Income & Expenses) Trial Balance</a></td></tr>
		<tr><td><a href='javascript:;' onclick=\"xajax_trial_balance('', '', '', 'Cash Basis','');\">General Trial Balance</a></td><td><a href='javascript:;' onclick=\"xajax_income_statement('', '', '', '', '', '', 'Cash Basis','');\">Income Statement</a></td><td><a href='javascript:;' onclick=\"xajax_balance_sheet('', '', '', 'Cash Basis','');\">Balance Sheet</a></td><td><a href='javascript:;' onclick=\"xajax_cash_flow('', '', '', '', '', '', 'Cash Basis','');\">Cash Flow Statement</a></td></tr>
		<tr><td></td><td><a href='javascript:;' onclick=\"xajax_pmt_income_statement('', '', '', '', '', 'monthly');\">PMT Income Statement</a></td><td><a href='javascript:;' onclick=\"xajax_pmt_balance_sheet('','','','monthly');\">PMT Balance Sheet</a></td><td><a href='javascript:;' onclick=\"xajax_pmt_portfolio_activity('', '', '', '', '', 'monthly');\">PMT Portfolio Activity</a></td><td><a href='javascript:;' onclick = \"xajax_pmtExport();\">Export to AMFIU PMT XML</a></td></tr></table>";
	}elseif($module == 'performance'){
		$sub_menu = "<table height=50 border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>";
	/*
		<tr><td><b>Portfolio Quality:</b></td><td><a href='javascript:;' onclick=\"xajax_coverage_ratio('', '', '', '')\">Risk Coverage Ratio Graph</a></td><td><a href='javascript:;' onclick=\"xajax_par_ratio('', '', '', '')\">Portfolio At Risk Graph</a></td><td><a href='javascript:;' onclick=\"xajax_port_yield('', '', '', '')\">Portfolio yield Graph</a></td><td><a href='javascript:;' onclick=\"xajax_repay_ratio('', '', '', '')\">Effective Repayment Rate Graph</a></td></tr>

		<tr><td><b>Profitability:</b></td><td colspan=2><a href='javascript:;' onclick=\"xajax_oper_sufficiency('', '', '', '')\">Operational Self-Sufficiency [OSS] Graph</a></td><td colspan=2><a href='javascript:;' onclick=\"xajax_fin_sufficiency('', '', '', '')\">Financial Self-Sufficiency [FSS] Graph</a></td><td></td><td></td></tr>

		<tr><td><b>Liquidity:</b></td><td colspan=4><a href='javascript:;' onclick=\"xajax_liquidity_ratio('', '', '', '')\">Liquidity Ratio Graph</td></tr>

		<tr><td><b>Operating Effeciency:</b></td><td colspan=2><a href='javascript:;' onclick=\"xajax_oper_expense_ratio('', '', '', '')\">Operating Expense Ratio Graph</a></td><td colspan=2><a href='javascript:;' onclick=\"xajax_average_loan('', '', '', '')\">Average Loan Portfolio Graph</a></td></tr>

		<tr><td><b>Capital Ratio:</b></td><td colspan=4><a href='javascript:;' onclick=\"xajax_debtto_equity_ratio('', '', '', '')\">Debt to Equity Ratio Graph</a></td></tr>";
		*/
		$sub_menu .= "<tr><td colspan=4><center><a href='javascript:;' onclick=\"xajax_portfolio_summary('', '', '');\"><b>Portfolio Summary</b></td><td></a><a href='javascript:;' onclick=\"xajax_ratios('', '', '');\"><b>Performance Indicators Report</b></a></center></td></tr>
		</table>";
	}elseif($module =='shares'){
		$sub_menu = "<table width=100%><tr><td><a href='javascript:;' onclick=\"xajax_membersList('all', 'All', 'All', '0','','100','0','1'); return false;\">All Customers</a></td><td><td><a href='javascript:;' onclick=\"xajax_membersList('members', 'All', 'All', '0','','100','0','1'); return false;\">All Members</a></td><td><a href='javascript:;' onclick=\"xajax_membersList('non_members', 'All', 'All', '0','','100','0','1'); return false;\">All Non Members</a></td>
		<td><a href='javascript:;' onclick=\"xajax_genSharesReport('', '', '','','100','0','1'); return false;\">Shares Report</a></td><td><a href='javascript:;' onclick=\"xajax_sharesLedgerForm(); return false;\">Shares Ledger</a></td><td><a href='javascript:;' onclick=\"xajax_sharing_done('', '', '', '', '', '',''); return false;\">Sharing Done</a></td><td><a href='javascript:;' onclick=\"xajax_member_ledger('', '', '', '', '', '', '', ''); return false;\">Member Statement</a></td></tr>
		</table>";
	}elseif($module == 'savings'){
		$sub_menu = "<table width=100%><tr><td><a href='javascript:;' onclick=\"xajax_list_saveproduct(); return false;\">List Savings Products</a></td><td><a href='javascript:;' onclick=\"xajax_list_accounts('', '', '', '','','100','0','1'); return false;\">List Accounts</a></td><td><a href='javascript:;' onclick=\"xajax_list_deposits('', '', '', '', '', '', '', '', '','','100','0','1'); return false;\">List Deposits</a></td><td><a href='javascript:;' onclick=\"xajax_list_withdrawal('', '', '', '', '', '', '', '', '','','100','0','1'); return false;\">List Withdrawals</a></td><td><a href='javascript:;' onclick=\"xajax_savings_ledger_form('')\">Savings Ledger</a></td><td><a href='javascript:;' onclick=\"xajax_ind_savings_summary_form()\">Individual Savings Summary</a></td>
		</tr>
		<tr><td><a href='javascript:;' onclick=\"xajax_cummulated_savings('', '', '','','100','0','1')\">Members Cummulated Savings Report</a></td><td><a href='javascript:;' onclick=\"xajax_sacco_savings_summary('', '', '', '', '')\">Savings Summary Report</a></td></tr>
		</table>";
	}elseif($module == 'statement'){
		//$sub_menu = "<table width=100%><tr><td><a href='javascript:;' onclick=\"xajax_showBankStatementForm(); return false;\">Bank Account Statements</a></td></tr>
		//</table>";
		$acc = @mysql_query("select ac.account_no, ac.name, ba.id as bank_id from accounts ac join bank_account ba on ac.id = ba.account_id ");
		$accts = "<option value=''>&nbsp;</option>";
		while ($acc_row = @mysql_fetch_array($acc))
		{
			$accts .= "<option value='".$acc_row['bank_id']."'>".$acc_row['account_no']." -". $acc_row['name']."</option>";
		}
		
	$content ="<form method='post' class='panel form-horizontal form-bordered'>";
$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h5 class="semibold text-primary mt0 mb5">BANK ACCOUNT STATEMENT</h5>
                                           	 </div>
                                        </div>';
                                        
                                                                                                                           
                                            $content .= '<div class="form-group">
                                           
                                            <label class="col-sm-3 control-label">Select Bank/Cash Account:</label>
                                            <div class="col-sm-6">
                                           <select name="bank_acct_id" id="bank_acct_id" class="form-control">'.$accts.'</select>
                                            </div></div>';                                         
                                             $content .= '<div class="form-group">
                                              
                                            <label class="col-sm-3 control-label">Select Period:</label>
                                            <div class="col-sm-6">
                                            <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>
                                            </div></div>';                                            
                                            
                                            $content .= "<div class='panel-footer'>
                                            <button type='button' class='btn btn-primary'   onclick=\"xajax_getStatement(getElementById('bank_acct_id').value, getElementById('from_date').value,  getElementById('to_date').value); return false;\">Show Statement</button></div>";
                                            $content .= '</div></form>';
                                            $resp->call("createDate","from_date");
					    $resp->call("createDate","to_date"); 
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//GROUPS STATUS REPORT
function groups_status($group_name, $date,$branch_id){
list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:'and branch_id='.$branch_id;
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $year, $month, $mday);
	//$arrears_date = sprintf("%d-%02d-%02d", $date);
	
	$arrears_date = $date;
		
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR GROUPS STATUS REPORT</h3>
                                <p class="text-default nm"></p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Group:</label>
                                            <input type="text" id="group_name" class="form-control">                                            
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Date:</label>
                                            <input type="text" class="form-control" id="date" name="date" placeholder="Date" />
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
                                </div>';               
               		
		 $content .= "<div class='panel-footer'>                              
                                
                                <button type='button' class='btn  btn-primary'  onclick=\"xajax_groups_status(getElementById('group_name').value, getElementById('date').value,getElementById('branch_id').value); return false;\">Show Report</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","date");
                    
               // $resp->assign("display_div", "innerHTML", $content);
	
	if($date ==''){
		$cont = "<font color=red>Select the Date for which you want the report</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
	$group_res = mysql_query("select * from loan_group where name like '%".$group_name."%' ".$branch_);
	if(mysql_numrows($group_res) == 0){
		$cont = "<font color=red>No group found in your search options</font>";
		$resp->assign("status", "innerHTML", $cont);
		return $resp;
	}
	$content .= "<a href='export/groups_status?group_name=".$group_name."&year=".$year."&month=".$month."&mday=".$mday."&branch_id=".$branch_id."&format=' target=blank()><b>Printable Version</b></a> | <a href='export/groups_status?group_name=".$group_name."&year=".$year."&month=".$month."&mday=".$mday."&branch_id=".$branch_id."&format=excel' target=blank()><b>Export Excel</b></a>";
	
	$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <p><h5 class="text-primary mt0">GROUPS STATUS REPORT AS OF '.$date.'</h5></p>
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">
	
	<thead><th rowspan=2>Name</th><th rowspan=2>MemberNo</th><th rowspan=2>Savings</th><th colspan=3>Loan Details</th><th colspan=3>Paid To Date</th><th colspan=3>Arrears To Date</th><th colspan=3>Due To Date</th></tr>
	<tr class="headings"><th>Loan Amt</th><th>Loan Balance</th><th>Pre-payment</th><th>Principal</th><th>Interest</th><th>Total</th><th>Principal</th><th>Interest</th><th>Total</th><th>Principal</th><th>Interest</th><th>Total Due</th></thead><tbody>';

	while($group = mysql_fetch_array($group_res)){
		$content .= "<tr class='headings'><th colspan=1 align=left>".strtoupper($group['name'])."</th></tr>";
		$mem_res = mysql_query("select m.id as mem_id, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name from group_member g join member m on g.mem_id=m.id where g.group_id=".$group['id']."");
		$size = mysql_fetch_array(mysql_query("select count(id) as no from group_member where group_id='".$group['id']."'"));
		$size = $size['no'];
		$x=1;
		
		$sub_savings_balance =0;
		$sub_loan_amt = 0;
		$sub_loan_balance =0;
		$sub_pre_paid_todate = 0;
		$sub_paid_princ_todate = 0;
		$paid_int_todate = 0;
		$sub_total_paid_todate = 0;
		$sub_arrears_princ = 0;
		$arrears_int = 0;
		$sub_total_arrears = 0;
		$sub_princ_due_todate = 0;
		$sub_int_due_todate = 0;
		$sub_total_due_todate = 0;
		while($mem = mysql_fetch_array($mem_res)){
			//SAVINGS
			$dep_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id where mem.mem_id=".$mem['mem_id']." and  d.bank_account <>0 and d.date <= '".$date."'");
			$dep = mysql_fetch_array($dep_res);
			$dep_amt = ($dep['amount'] == NULL) ? 0 : $dep['amount'];

			$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts mem on i.memaccount_id=mem.id where m.mem_id=".$mem['mem_id']."  and i.date <= '".$date."'");
			$int = mysql_query($int_res);
			$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];

			$with_res = mysql_query("select sum(w.amount) as amount from withdrawal w join mem_accounts mem on w.memaccount_id=mem.id where m.mem_id=".$mem['mem_id']." and  w.bank_account <>0 and w.date <= '".$date."'");
			$with = mysql_query($with_res);
			$with_amt = ($with['amount'] == NULL) ? 0 : $with['amount'];

			$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts mem on c.memaccount_id=mem.id where m.mem_id=".$mem['mem_id']."  and c.date <= '".$date."'");
			$charge = mysql_query($charge_res);
			$charge_amt = ($charge['amount'] == NULL) ? 0 : $charge['amount'];

			//LOAN REPAYMENTS
			$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id where m.mem_id=".$mem['mem_id']."  and p.date <= '".$date."'");
			$pay = mysql_fetch_array($pay_res);
			$pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;
			//INCOME DEDUCTIONS
			$inc_res = mysql_query("select sum(i.amount) as amount from other_income i join mem_accounts m on i.mode=m.id where m.mem_id=".$mem['mem_id']."  and i.date <= '".$date."'");
			$inc = mysql_fetch_array($inc_res);
			$inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;
			//TRANSFER TO SHARES
			$shares_res = mysql_query("select sum(s.value) as amount from shares s join mem_accounts m on s.mode=m.id where m.mem_id=".$mem['mem_id']."  and s.date <= '".$date."'");
			$shares = mysql_fetch_array($shares_res);
			$shares_amt = ($shares['amount'] != NULL) ? $shares['amount'] : 0;


			$savings_balance = $dep_amt +  $int_amt - $charge_amt - $with_amt - $shares_amt - $inc_amt - $pay_amt;

			$mem_id = $mem['mem_id'];
			//LOANS
			//DISBURSEMENT
			$disb_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id = applic.id where applic.mem_id=".$mem_id." and d.date <= '".$date ."' and applic.group_id='".$group['id']."'");
			$disb = mysql_fetch_array($disb_res);
			$loan_amt = ($disb['amount'] == NULL) ? 0 : $disb['amount'];	

			//EXPECTED
			$sched_res = mysql_query("select sum(s.int_amt) as int_amt, sum(s.princ_amt) as amount from schedule s join disbursed d on s.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$mem_id."' and s.date <= '".$date."' and applic.group_id='".$group['id']."'");
			$sched = @mysql_fetch_array($sched_res);
			$sched_princ = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
			$sched_int = ($sched['int_amt'] == NULL) ? 0 : $sched['int_amt'];
			$total_sched = $sched_princ + $sched_int;
			
			//PAID TO DATE
			$paidint_res = mysql_query("select sum(p.int_amt) as int_amt, sum(p.princ_amt) as princ_amt from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$mem_id."' and p.date <= '".$date."' and applic.group_id='".$group['id']."'");
			$paidint = mysql_fetch_array($paidint_res);
			$paid_int_todate = ($paidint['int_amt'] == NULL) ? 0 : $paidint['int_amt'];
			$paid_princ_todate = ($paidint['princ_amt'] == NULL) ? 0 : $paidint['princ_amt'];
			$total_paid_todate = $paid_princ_todate + $paid_int_todate; 

			//PAID TO PREVIOUS DATE (ARREARS CALCULATION)
			$prevint_res = mysql_query("select sum(s.int_amt) as int_amt, sum(s.princ_amt) as princ_amt from schedule s join disbursed d on s.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$mem_id."' and s.date < '".$arrears_date."' and applic.group_id='".$group['id']."'");
			$prevint = mysql_fetch_array($prevint_res);
			$prev_int_todate = ($prevint['int_amt'] == NULL) ? 0 : $prevint['int_amt'];
			$prev_princ_todate = ($prevint['princ_amt'] == NULL) ? 0 : $prevint['princ_amt'];
			
			//PREPAYMENTS
			$pre_paid_todate = ($total_paid_todate > $total_sched) ? ($total_paid_todate - $total_sched) : 0;

			//ARREARS
			if($total_paid_todate > $total_sched){
				$arrears_princ = 0;
				$arrears_int = 0;
				$total_arrears = 0;
				$princ_due_todate =0;
				$int_due_todate =0;
				$total_due_todate =0;
			}else{
				$princ_due_todate = $sched_princ - $paid_princ_todate;
				$int_due_todate = $sched_int - $paid_int_todate;
				$total_due_todate = $princ_due_todate + $int_due_todate;
				$arrears_princ = $prev_princ_todate - $paid_princ_todate;
				$arrears_int = $prev_int_todate - $paid_int_todate;
				$total_arrears = $arrears_princ + $arrears_int;
			}
			//LOAN BALANCE
			$loan_balance = $loan_amt - $paid_princ_todate;

			$content .= "<tr><td>".$x.". ".$mem['first_name']." ".$mem['last_name']."</td><td>".$mem['mem_no']."</td><td>".number_format($savings_balance, 2)."</td><td>".number_format($loan_amt, 2)."</td><td>".number_format($loan_balance, 2)."</td><td>".number_format($pre_paid_todate, 2)."</td><td>".number_format($paid_princ_todate, 2)."</td><td>".number_format($paid_int_todate, 2)."</td><td>".number_format($total_paid_todate, 2)."</td><td>".number_format($arrears_princ, 2)."</td><td>".number_format($arrears_int, 2)."</td><td>".number_format($total_arrears, 2)."</td><td>".number_format($princ_due_todate, 2)."</td><td>".number_format($int_due_todate, 2)."</td><td>".number_format($total_due_todate, 2)."</td></tr>";

			$sub_savings_balance +=$savings_balance;
			$sub_loan_amt += $loan_amt;
			$sub_loan_balance += $loan_balance;
			$sub_pre_paid_todate += $pre_paid_todate;
			$sub_paid_princ_todate += $paid_princ_todate;
			$paid_int_todate += $paid_int_todate;
			$sub_total_paid_todate += $total_paid_todate;
			$sub_arrears_princ += $arrears_princ;
			$arrears_int += $arrears_int;
			$sub_total_arrears += $total_arrears;
			$sub_princ_due_todate += $princ_due_todate;
			$sub_int_due_todate += $int_due_todate;
			$sub_total_due_todate += $total_due_todate;
			if($x == $size){
				$content .= "<tr><td></td><td>GROUP TOTAL</td><td>".number_format($sub_savings_balance, 2)."</td><td>".number_format($sub_loan_amt, 2)."</td><td>".number_format($sub_loan_balance, 2)."</td><td>".number_format($sub_pre_paid_todate, 2)."</td><td>".number_format($sub_paid_princ_todate, 2)."</td><td>".number_format($sub_paid_princ_todate, 2)."</td><td>".number_format($sub_total_paid_todate, 2)."</td><td>".number_format($sub_arrears_princ, 2)."</td><td>".number_format($sub_arrears_princ, 2)."</td><td>".number_format($sub_total_arrears, 2)."</td><td>".number_format($sub_princ_due_todate, 2)."</td><td>".number_format($sub_int_due_todate, 2)."</td><td>".number_format($sub_total_due_todate, 2)."</td></tr>";
				
			}
			$x++;
		}
	}
	$content .="</tbody></table></div>";
	$resp->call("createTableJs");
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function portfolio_summary($date){

	list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$resp->assign("status", "innerHTML", "");
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">INSTITUTION\'S PORTFOLIO SUMMARY REPORT AS OF '.$date.'</h3>
                                <p class="text-default nm"></p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Date:</label>
                                           <input type="text" class="form-control" id="date" name="date" placeholder="date" />        
                                        </div>
                                        
                                    </div>
                                </div>';                                  
                                   	
		 $content .= "<div class='panel-footer'>                              
                               
                                <button type='button' class='btn  btn-primary' onclick=\"xajax_portfolio_summary(getElementById('date').value); return false;\">Show Report</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","date");
                    
                //$resp->assign("display_div", "innerHTML", $content);
	
	
	if($date ==''){
		$cont = "<font color=red>Select the period for which you want the report</font>";
		$resp->assign("status", "innerHTML", $cont);
		
	}
	else{
	
	$content .= "<a href='export/portfolio_summary?year=".$year."&month=".$month."&mday=".$mday."' target=blank()><b>Printable Version</b></a> | <a href='export/portfolio_summary?year=".$year."&month=".$month."&mday=".$mday."&format=excel' target=blank()><b>Export Excel</b></a>";
	
	$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <p><h5 class="text-primary mt0">INSTITUTION\'S PORTFOLIO SUMMARY REPORT AS OF '.$date.'</h5></p>
                            </div><p></p>';
 		$content .= "<table border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111'  id='AutoNumber2' align=center><tr class='headings'><th rowspan=2>A: LOAN ACTIVITY</th><th colspan=2>Loans Disbursed This Period</th><th colspan=2>Total Loans Disbursed</th></tr>
	<tr class='headings'><th>No of Loans</th><th>Amount Disbursed</th><th>No of Loans</th><th>Amount Disbursed</th></tr>";
	$date = sprintf("%d-%02d-%02d", $year, $month, $mday);
	$tot_res = mysql_query("select count(d.id) as no, sum(d.amount) as amount, d.cycle as cycle from disbursed d where d.date <= '".$date."' group by d.cycle order by d.cycle asc");
	
	$x=1;
	while($tot = mysql_fetch_array($tot_res)){
		if($tot['cycle'] == 1) {
			$text = "First Loans";
			$tot_no = $tot['no'];
			$tot_amount = $tot['amount'];
		}
		if($tot['cycle'] == '2') {
			$text = "Second Loans";
			$tot_no = $tot['no'];
			$tot_amount = $tot['amount'];
		}
		if($tot['cycle'] == 3) {
			$text = "Third Loans";
			$tot_no = $tot['no'];
			$tot_amount = $tot['amount'];
		}
		if($tot['cycle'] == 4) {
			$text = "Fourth Loans";
			$tot_no = $tot['no'];
			$tot_amount = $tot['amount'];
		}
		if($tot['cycle'] == 5) {
			$text = "Fifth Loans";
			$tot_no = $tot['no'];
			$tot_amount = $tot['amount'];
		}
		if($tot['cycle'] >= 6) {
			$tot_res = mysql_query("select count(d.id) as no, sum(d.amount) as amount from disbursed d where d.date <= '".$date."' and d.cycle >=6");
			$tot = mysql_fetch_array($tot_res);
			$text = "Sixth Loans And Above";
			$tot_no = $tot['no'];
			$tot_amount = $tot['amount'];
			//break;
		}
		if($text == "Sixth Loans And Above")
			$where_cycle = " d.cycle >=6";
		else
			$where_cycle = " d.cycle=".$tot['cycle'];
		$now_res = mysql_query("select count(d.id) as no, sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id = applic.id join loan_product p on applic.product_id = p.id join accounts a on p.account_id=a.id where d.date >= DATE_SUB('".$date."', INTERVAL 30 DAY) and d.date <= '".$date."' and ".$where_cycle."");
		
		$now = mysql_fetch_array($now_res);
		$tot_no = ($tot['no'] == NULL) ? "--" : $tot['no'];
		$now_no = ($now['no'] == NULL) ? "--" : $now['no'];
		$tot_amount = ($tot['amount'] == NULL) ? "--" : $tot['amount'];
		$now_amount = ($now['amount'] == NULL) ? "--" : $tot['amount'];
		$color = ($x%2 == 0) ? "white" : "lightgrey";
		$content .= "<tr bgcolor=$color><td>".$x.". ".$text."</td><td>".$now_no."</td><td>".number_format($now_amount, 2)."</td><td>".$tot_no."</td><td>".number_format($tot_amount, 2)."</td></tr>";
		$x++;
		if($text == "Sixth Loans And Above")
			break;
	}


	$tot_res = mysql_query("select count(d.id) as no, sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id = applic.id join loan_product p on applic.product_id = p.id join accounts a on p.account_id=a.id where d.date <= '".$date."'");
	$tot = mysql_fetch_array($tot_res);
	$now_res = mysql_query("select count(d.id) as no, sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id = applic.id join loan_product p on applic.product_id = p.id join accounts a on p.account_id=a.id where d.date >= DATE_SUB('".$date."', INTERVAL 30 DAY) and d.date <= '".$date."'");	
	$now = mysql_fetch_array($now_res);
	
	$tot_no = ($tot['no'] == NULL || $tot_no =="--") ? "--" : $tot['no'];
	$now_no = ($now['no'] == NULL || $now_no =="--") ? "--" : $now['no'];
	$tot_amount = ($tot['amount'] == NULL || $tot_amount =="--") ? "--" : $tot['amount'];
	$now_amount = ($now['amount'] == NULL || $now_amount =="--") ? "--" : $tot['amount'];

	$color = ($x%2 == 0) ? "white" : "lightgrey";
	$content .= "<tr bgcolor=$color><td><b>Total Disbursements</b></td><td><b>".$now_no."</b></td><td><b>".number_format($now_amount, 2)."</b></td><td><b>".$tot_no."</b></td><td><b>".number_format($tot_amount, 2)."</b></td></tr>";
	$x++;
	

	//CLIENT INFORMATION
	$group_res = mysql_query("select count(name) as no from loan_group");
	$group = mysql_fetch_array($group_res);
	$groups = ($group['no'] == NULL) ? "--" : $group['no'];
	$client_res = mysql_query("select count(*) as no from member");
	$client = mysql_fetch_array($client_res);
	$client_no = ($client['no'] == NULL) ? "--" : $client['no'];
	$content .= "<tr class='headings'><th colspan=3 align=left>B: CLIENTS INFORMATION</th><th>GROUPS</th><TH>CLIENTS</TH></tr>
	<tr bgcolor=white><td colspan=3>Total Number of Groups / Clients End of Last Period</td><td>".$groups."</td><td>".$client_no."</td></tr>";


	//outstanding 
	$content .= "<tr class='headings'><th>C: LOANS OUTSTANDING</th><th colspan=2>PRINCIPAL</th><th colspan=2>INTEREST</th></tr>";
	$sth = mysql_query("select sum(d.amount) as amount, d.cycle as cycle from  disbursed d  where d.date <= '".$date."' group by d.cycle asc");

	$x=1;
	$total_princ =0;
	$total_int = 0;
	$int6_amt = 0;
	$princ6_int = 0;


	$sched_res = mysql_query("select sum(s.princ_amt) as princ_amt, sum(s.int_amt) as int_amt, d.cycle from schedule s join disbursed d on s.loan_id=d.id where d.date <= '".$date."' group by d.cycle order by d.cycle asc");
	while($tot = mysql_fetch_array($sched_res)){
		$paid_res = mysql_query("select sum(p.princ_amt) as princ_amt, sum(p.int_amt) as int_amt, d.cycle from payment p join disbursed d on p.loan_id=d.id where d.date <= '".$date."' and d.cycle='".$tot['cycle']."' group by d.cycle");
		$paid = mysql_fetch_array($paid_res);
		$paid_amt = ($paid['princ_amt'] == NULL) ? 0 : $paid['princ_amt'];
		$paid_int = ($paid['int_amt'] == NULL) ? 0 : $paid['int_amt'];

		$sched_amt = ($tot['princ_amt'] == NULL) ? 0 : $tot['princ_amt'];
		$sched_int = ($tot['int_amt'] == NULL) ? 0 : $tot['int_amt'];
		$princ_out = $sched_amt - $paid_amt;
		$int_out = $sched_int - $paid_int;
		if($tot['cycle'] == 1) {
			$text = "First Loans";
		}
		if($tot['cycle'] == 2) {
			$text = "Second Loans";
		}
		if($tot['cycle'] == 3) {
			$text = "Third Loans";
		}
		if($tot['cycle'] == 4) {
			$text = "Fourth Loans";
		}
		if($tot['cycle'] == 5) {
			$text = "Fifth Loans";
		}
		if($tot['cycle'] >= 6) {
			$int6_amt += $int_out;
			$princ6_amt += $princ_out;
			$test_res = mysql_query("select * from disbursed where cycle >'".$tot['cycle']."' and date <= '".$date."'");
			
			if(mysql_numrows($test_res) == 0){
				$text = "Sixth Loans And Above";
				$int_out = $int6_amt;
				$princ_out = $princ6_amt;
			}else
				continue;
		}

		$color = ($x%2 == 0) ? "white" : "lightgrey";
		$content .= "<tr bgcolor=$color><td>".$x.". ".$text."</td><td colspan=2>".number_format($princ_out, 2)."</td><td colspan=2>".number_format($int_out, 2)."</td></tr>";
		$total_princ += $princ_out;
		$total_int += $int_out;
		$x++;
	}
	$color = ($x%2 == 0) ? "white" : "lightgrey";	
	$content .= "<tr bgcolor=$color><td><b>Total</b></td><td colspan=2><b>".number_format($total_princ, 2)."</b></td><td colspan=2><b>".number_format($total_int, 2)."</b></td></tr>";
	
	$sth = mysql_query("select count(id) as no from disbursed where DATEDIFF('".$date."', date) >= 30 and id not in (select loan_id from written_off where DATEDIFF('".$date."', date) >= 30)");
	$row = mysql_fetch_array($sth);
	$loans_end = ($row['no'] == NULL) ? 0 : $row['no'];
	$x++;
	$color = ($x%2 == 0) ? "white" : "lightgrey";
	$content .= "
	<tr bgcolor=$color><td colspan=3>Number of Loans End of Last period</td><td colspan=2>".$loans_end."</td></tr>";

//OUTSTANDING P & I AT END LAST PERIOD

	$sched_res = mysql_query("select sum(s.princ_amt + s.int_amt) as amount, sum(s.int_amt) as int_amt from schedule s join disbursed d on s.loan_id=d.id where d.date <= DATE_SUB('".$date."', INTERVAL 30 DAY)");
	$sched = mysql_fetch_array($sched_res);
	$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
	$paid_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount, sum(p.int_amt) as int_amt from payment p join disbursed d on p.loan_id=d.id where d.date <= DATE_SUB('".$date."', INTERVAL 30 DAY)");
	$paid = mysql_fetch_array($paid_res);
	$paid_amt = ($paid['amount'] == NULL) ? 0 : $paid['amount'];
	$total_outstanding = $sched_amt - $paid_amt;	
	$x++;
	$color = ($x%2 == 0) ? "white" : "lightgrey";
	$content .="
	<tr bgcolor=$color><td colspan=3>Outstanding P & I End of Last Period</td><td colspan=2>".number_format($total_outstanding, 2)."</td></tr>";

//Number of Loans Disbursed this Period
	$sth = mysql_query("select count(id) as no from disbursed where DATEDIFF('".$date."', date) <= 30 and DATEDIFF('".$date."', date) >= 0 and id not in (select loan_id from written_off where date < '".$date."')");
	$row = mysql_fetch_array($sth);
	$loans_this_period = ($row['no'] == NULL) ? 0 : $row['no'];
	$x++;
	$color = ($x%2 == 0) ? "white" : "lightgrey";
	$content .="
	<tr bgcolor=$color><td colspan=3>Number of Loans Disbursed this Period</td><td colspan=2>".$loans_this_period."</td></tr>";

//Disbursed Amount [Principal] This Period
	$sth = mysql_query("select sum(amount) as amount from disbursed where DATEDIFF('".$date."', date) <= 30 and DATEDIFF('".$date."', date) >=0 and id not in (select loan_id from written_off where date < '".$date."')");
	$row = mysql_fetch_array($sth);
	$amount_dis_this_period = ($row['amount'] == NULL) ? 0 : $row['amount'];
	$x++;
	$color = ($x%2 == 0) ? "white" : "lightgrey";
	$content .="
	<tr bgcolor=$color><td colspan=3>Disbursed Amount [Principal] This Period</td><td colspan=2>".$amount_dis_this_period."</td></tr>";

	$sth = mysql_query("select sum(princ_amt) as amount from payment where  loan_id not in (select loan_id from written_off where date <= '".$date."') and date <= '".$date."'");
	$row = mysql_fetch_array($sth);
	$pay_amt = ($row['amount'] == NULL) ? 0 : $row['amount'];
	$write_res = mysql_query("select sum(amount) as amount from written_off where date <= '".$date."'");
	$write = mysql_fetch_array($write_res);
	$write_amt = ($write['amount'] == NULL) ? 0 : $write['amount'];
	$tot_debits = $write_amt + $pay_amt;

	$x++;
	$color = ($x%2 == 0) ? "white" : "lightgrey";
	$content .="
	<tr bgcolor=$color><td colspan=3>Total Debits</td><td colspan=2>".number_format($tot_debits, 2)."</td></tr>";

	$sth = mysql_query("select sum(amount) as amount from disbursed where date <= '".$date."'");
	$row = mysql_fetch_array($sth);
	$tot_credits = ($row['amount'] == NULL) ? 0 : $row['amount'];
	$x++;
	$color = ($x%2 == 0) ? "white" : "lightgrey";
	$content .="
	<tr bgcolor=$color><td colspan=3>Total Credits</td><td colspan=2>".number_format($tot_credits, 2)."</td></tr>";
	
//OUTSTANDING PRINC AND INTEREST
	$sched_res = mysql_query("select sum(s.princ_amt + s.int_amt) as amount, sum(s.int_amt) as int_amt from schedule s join disbursed d on s.loan_id=d.id where d.date <= '".$date."'");
	$sched = mysql_fetch_array($sched_res);
	$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
	$paid_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount, sum(p.int_amt) as int_amt from payment p join disbursed d on p.loan_id=d.id where d.date <= '".$dat."'");
	$paid = mysql_fetch_array($paid_res);
	$paid_amt = ($paid['amount'] == NULL) ? 0 : $paid['amount'];
	$total_outstanding = $sched_amt - $paid_amt;	
	$x++;
	$color = ($x%2 == 0) ? "white" : "lightgrey";
	$content .="
	<tr bgcolor=$color><td colspan=3>P & I Outstanding</td><td colspan=2>".number_format($total_outstanding, 2)."</td></tr>";
	
	//CURRENT LOAN STATUS
	$x++;
	$content .="
	<tr class='headings'><td><b>D: CURRENT LOAN STATUS:</b></td><td><b>P & I DUE TO DATE</b></td><td><b>P & I PAID TO DATE</b></td><td><b>PREPAYMENTS</b></td><td><b>ARREARS</b></td></tr>";
	$sth = mysql_query("select sum(d.amount) as amount, a.account_no as account_no, a.name as name, p.id as product_id, d.cycle as cycle from schedule s join disbursed d on s.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where d.date <= '".$date."' group by d.cycle order by d.cycle");
	$sub_amt_due = 0;
	$sub_paid_pi_todate = 0;
	$sub_pre_paid_todate = 0;
	$sub_amt_arrears = 0;
	//FOR 6TH LOANS AND ABOVE
	$total_amt_due6 = 0;
	$paid_pi_todate6 = 0;
	$pre_paid_todate6 = 0;
	$total_amt_arrears6 = 0;
	/* while($row = mysql_fetch_array($sth)){
		$pre_paid_todate = 0;
		$total_amt_due =0;
		$total_amt_arrears = 0;
		$paid_pi_todate = 0;
		$pre_paid_todate = 0;
		$loan_res = mysql_query("select d.id as id, applic.id as applic_id, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year from disbursed d join loan_applic applic on d.applic_id=applic.id where d.cycle= '".$row['cycle']."' and d.date <= '".$date."'");
		while($loan = mysql_fetch_array($loan_res)){
			//Period ellapsed
			$last_res = mysql_query("select DATE_FORMAT(date, '%d') as last_mday, DATE_FORMAT(date, '%m') as last_month, DATE_FORMAT(date, '%Y') as last_year  from payment where date <= '".$date."' and loan_id='".$loan['id']."' order by date desc");
				
			if(mysql_numrows($last_res) ==0)
				$last_res = mysql_query("select DATE_FORMAT(date, '%d') as last_mday, DATE_FORMAT(date, '%m') as last_month, DATE_FORMAT(date, '%Y') as last_year from schedule where date <= '".$date."' and loan_id='".$loan['id']."' order by date asc");
			$last = mysql_fetch_array($last_res);
			
		//CALCULATE DUE PRINCIPAL
		$sched_res = mysql_query("select sum(princ_amt) as amount from schedule where loan_id='".$loan['id']."' and date <= '".$date."'");
		$sched = @mysql_fetch_array($sched_res);
		$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
		//PAID INTEREST
		$paidint_res = mysql_query("select sum(int_amt) as int_amt, sum(princ_amt) as princ_amt from payment where loan_id='".$loan['id']."' and date <= '".$date."'");
		$paidint = mysql_fetch_array($paidint_res);
		$paidint_amt = ($paidint['int_amt'] == NULL) ? 0 : $paidint['int_amt'];
		$paid_princ_todate = ($paidint['princ_amt'] == NULL) ? 0 : $paidint['princ_amt'];
		$due_princ_amt = $sched_amt + $paidint_amt;  // - $paid_amt - $arrears_amt;	
		//PREPAYMENTS
		if($paid_princ_todate > $sched_amt)
			$pre_paid_todate += $paid_princ_todate - $sched_amt;
		
		if($due_princ_amt <= 0){
			$due_int_amt=0;
		}else{
			$due_princ = $due_princ_amt;

		//CALCULATE DUE INTEREST
		$int_days = $calc->dateToDays($mday, $month, $year) - $calc->dateToDays($last['last_mday'], 		$last['last_month'], $last['last_year']); 
			if($int_days >0){
				$no_months = floor($int_days /30);
				if($loan['int_method'] =='Declining Balance'){
					$due_int_amt = ceil((($balance * $loan['int_rate']/100) / 12) * $no_months);
					$due_int = $due_int_amt;
				}elseif($row['int_method'] == 'Flat'){
					$due_int_amt = ceil((($loan['amount'] * $loan['int_rate']/100) / 12) * $no_months);
					$due_int = $due_int_amt;
				}
			}else
				$due_int="--"; 
		}
		$total_amt_due = $total_amt_due + $due_princ_amt + $due_int_amt ;
	} 
		
	//PAID P & I TO DATE
		$paid_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount, sum(p.princ_amt) as princ_amt from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where d.cycle='".$row['cycle']."' and p.date <='".$date."'");

		$paid = mysql_fetch_array($paid_res);
		$paid_pi_todate = ($paid['amount'] == NULL) ? 0 : $paid['amount'];
		$paid_princ_todate = ($paid['princ_amt'] == NULL) ? 0 : $paid['princ_amt'];

	//PREPAYMENTS
		
		//$pre_paid_todate = $paid_princ_todate - $sched_princ_todate;
		//$pre_paid_todate = ($pre_paid_todate < 0) ? 0 : $pre_paid_todate;
		if($row['cycle'] == 1) {
			$text = "First Loans";
		}
		if($row['cycle'] == 2) {
			$text = "Second Loans";
		}
		if($row['cycle'] == 3) {
			$text = "Third Loans";
		}
		if($row['cycle'] == 4) {
			$text = "Fourth Loans";
		}
		if($row['cycle'] == 5) {
			$text = "Fifth Loans";
		}
		if($row['cycle'] >= 6) {
			$total_amt_due6 += $total_amt_due;
				$paid_pi_todate6 += $paid_pi_todate;
				$pre_paid_todate6 += $pre_paid_todate;
				$total_amt_arrears6 += $total_amt_arrears;
			$test_res = mysql_query("select * from disbursed where cycle >=".$row['cycle']." and date <= '".$date."'");
			if(mysql_numrows($test_res) == 0){
				$text = "Sixth Loans And Above";
				$total_amt_due = $total_amt_due6;
				$paid_pi_todate = $paid_pi_todate6;
				$pre_paid_todate = $pre_paid_todate6;
				$total_amt_arrears = $total_amt_arrears6;
			}else
				continue;
		}
		//ARREARS
		$total_amt_arrears =$total_amt_due - $paid_pi_todate + $pre_paid_todate;

		*/
		
	$sched_res = mysql_query("select sum(s.princ_amt) as princ_amt, sum(s.int_amt) as int_amt, d.cycle from schedule s join disbursed d on s.loan_id=d.id where s.date <= '".$date."' group by d.cycle order by d.cycle asc");
	while($tot = mysql_fetch_array($sched_res)){
		$paid_res = mysql_query("select sum(p.princ_amt) as princ_amt, sum(p.int_amt) as int_amt, d.cycle from payment p join disbursed d on p.loan_id=d.id where p.date <= '".$date."' and d.cycle='".$tot['cycle']."' group by d.cycle");
		$paid = mysql_fetch_array($paid_res);
		$paid_amt = ($paid['princ_amt'] == NULL) ? 0 : $paid['princ_amt'];
		$paid_int = ($paid['int_amt'] == NULL) ? 0 : $paid['int_amt'];

		$sched_amt = ($tot['princ_amt'] == NULL) ? 0 : $tot['princ_amt'];
		$sched_int = ($tot['int_amt'] == NULL) ? 0 : $tot['int_amt'];
		$princ_out = $sched_amt - $paid_amt;
		$int_out = $sched_int - $paid_int;
		
		if($tot['cycle'] == 1) {
			$text = "First Loans";
		}
		if($tot['cycle'] == 2) {
			$text = "Second Loans";
		}
		if($tot['cycle'] == 3) {
			$text = "Third Loans";
		}
		if($tot['cycle'] == 4) {
			$text = "Fourth Loans";
		}
		if($tot['cycle'] == 5) {
			$text = "Fifth Loans";
		}
		if($tot['cycle'] >= 6) {
			$int6_amt += $int_out;
			$princ6_amt += $princ_out;
			$paid6_amt += $paid_amt;
			$paid6_int += $paid_int;
			$test_res = mysql_query("select * from disbursed where cycle >'".$tot['cycle']."' and date <= '".$date."'");
			if(mysql_numrows($test_res) == 0){
				$text = "Sixth Loans And Above";
				$int_out = $int6_amt;
				$princ_out = $princ6_amt;
				$paid_amt = $paid6_amt;
				$paid_int = $paid6_int;
				$where_cycle = " d.cycle >= '6'";
			}else
				continue;
			
		}else
			$where_cycle = "d.cycle='".$tot['cycle']."' group by d.cycle";
		//ARREARS
		$check = mysql_fetch_array(mysql_query("select sum(s.princ_amt + s.int_amt) as amount, sum(s.int_amt) as int_amt from schedule s join disbursed d on s.loan_id=d.id where s.date < '".$date."' and ".$where_cycle.""));
	
		$check_amt = ($check['amount'] == NULL) ? 0 : $check['amount'];
		
		$total_amt_arrears = $check_amt - $paid_amt - $paid_int;
		$total_amt_arrears = ($total_amt_arrears <0) ? 0  : $total_amt_arrears;

		$total_amt_due = $princ_out + $int_out;
		$paid_pi_todate = $paid_amt + $paid_int;
		$pre_paid_todate = $paid_pi_todate - $total_amt_due;
		
		$pre_paid_todate = ($pre_paid_todate <= 0) ? 0  : $pre_paid_todate;
		$x++;
		$color = ($x%2 == 0) ? "white" : "lightgrey";
		$content .= "<tr bgcolor=$color><td>".$x.". ".$text."</td><td>".number_format($total_amt_due, 2)."</td><td>".number_format($paid_pi_todate, 2)."</td><td>".number_format($pre_paid_todate, 2)."</td><td>".number_format($total_amt_arrears, 2)."</td></tr>";
		$sub_amt_due += $total_amt_due;
		$sub_paid_pi_todate += $paid_pi_todate;
		$sub_pre_paid_todate += $pre_paid_todate;
		$sub_amt_arrears += $total_amt_arrears;
	}

	//SUB TOTALS
	$x++;
	$color = ($x%2 == 0) ? "white" : "lightgrey";
	$content .= "<tr bgcolor=$color><td><b>Total</b></td><td><b>".number_format($sub_amt_due, 2)."</b></td><td><b>".number_format($sub_paid_pi_todate, 2)."</b></td><td><b>".number_format($sub_pre_paid_todate, 2)."</b></td><td><b>".number_format($sub_amt_arrears, 2)."</b></td></tr>";
	$content .= "</table>";
      }
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function sacco_savings_summary($from_date,$to_date, $branch_id)
{
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$resp->assign("status", "innerHTML", "");
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">INSTITUTION\'S SAVINGS SUMMARY REPORT</h3>
                                <p class="text-default nm"></p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Date Range:</label>
                                            <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div></span>       
                                        </div>
                                        <div class="col-sm-6">
                                           <label class="control-label">Branch:</label>
                                           '.branch_rep($branch_id).'
                                        </div>
                                    </div>
                                </div>';                                  
                                   	
		 $content .= "<div class='panel-footer'>                              
                               
                                <button type='button' class='btn  btn-primary' onclick=\"xajax_sacco_savings_summary(getElementById('from_date').value, getElementById('to_date').value, getElementById('branch_id').value); return false;\">Show Report</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
        $resp->call("createDate","from_date");
	$resp->call("createDate","to_date");
                    
                //$resp->assign("display_div", "innerHTML", $content);
                

	if($from_date=='' || $to_date==''){
		$cont = "<font color=red>Select the period for which you want the report</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
	//$resp->alert("$branch_id");
	$start_date = date('Y-m-d 23:59:59', strtotime($calc->endOfMonthBySpan(0, intval($from_month), intval($from_year), '%Y-%m-%d')));
	$final_date = date('Y-m-d 23:59:59', strtotime($calc->endOfMonthBySpan(0, intval($to_month), intval($to_year), '%Y-%m-%d')));
	$start_month = intval($from_month); $end_month = intval($to_month);
	$start_year = intval($from_year); $end_year = intval($to_year);
	$total_saved = 0; $total_with = 0; $total_charges = 0; $total_fees = 0;
	$cumm_save = 0;
	$branch = ($branch_id=='all'||$branch_id=='')? "": " and  branch_id='".$branch_id."'";
	$drow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_savings, sum(percent_value + flat_value) as charge from deposit where bank_account != 0 and date < '".$start_date."'".$branch.""));
	$crow1 = @mysql_fetch_array(mysql_query("select sum(percent_value + flat_value) as charge from deposit where bank_account != 0 and date < '".$start_date."'".$branch.""));
	$crow2 = @mysql_fetch_array(mysql_query("select sum(percent_value + flat_value) as charge from withdrawal where bank_account != 0 and date < '".$start_date."'".$branch.""));
	$wrow1 = @mysql_fetch_array(mysql_query("select sum(amount) as tot_with, sum(percent_value + flat_value) as charge from withdrawal where date < '".$start_date."'".$branch.""));
	$mrow1 = @mysql_fetch_array(mysql_query("select sum(amount) as tot_fees from monthly_charge where date < '".$start_date."'".$branch.""));
	$irow1 = mysql_fetch_array(mysql_query("select sum(amount) as tot_int from save_interest where date < '".$start_date."'".$branch.""));
	$prow1 = @mysql_fetch_array(@mysql_query("select sum(princ_amt + int_amt) as tot_pay from payment where mode not in ('cash', 'Cheque', '') and date < '".$start_date."'".$branch.""));
	$incrow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_inc from other_income where mode not in ('cash', 'Cheque', '') and date < '".$start_date."'".$branch.""));
	$shares = @mysql_fetch_array(@mysql_query("select sum(value) as amount from shares where mode not in ('cash', 'Cheque', '') and date < '".$start_date."'".$branch.""));
	

	$tot_savings = isset($drow1['tot_savings'])? $drow1['tot_savings'] : 0 ;
	
			$tot_with = isset($wrow1['tot_with'])? $wrow1['tot_with'] : 0 ;
			$tot_int = isset($irow1['tot_int'])? $irow1['tot_int'] : 0 ;
			$tot_inc = isset($incrow1['tot_inc'])? $incrow1['tot_inc'] : 0 ;
			$tot_shares = ($shares['amount'] != NULL) ? $shares['amount'] : 0;
			$tot_pay = isset($prow1['tot_pay'])? $prow1['tot_pay'] : 0 ;
			
			$dcharge_amt = ($drow1['charge'] != NULL) ? $drow1['charge'] : 0;
			$wcharge_amt = ($wrow1['charge'] != NULL) ? $wrow1['charge'] : 0;
			$tot_fees = $dcharge_amt + $wcharge_amt;
			$tot_deduc = $tot_inc + $tot_pay + $tot_shares;
			$net_save = $tot_savings + $tot_int  - $tot_with - $tot_deduc - $tot_fees;


	
	$cumm_save= $net_save;
	//$tot_fees = $mrow1['tot_fees'] + $wcharge_amt + $dcharge_amt;
	//$tot_deduc = $incrow1['tot_inc'] + $payrow1['tot_pay'] + $shares['amount'] ; //$tot_shares;
	$content .= "<a href='export/sacco_savings_summary?branch_id=".$branch_id."&from_year=".$from_year."&from_month=".$from_month."&to_year=".$to_year."&to_month=".$to_month."' target=blank()><b>Printable Version</b></a> | <a href='export/sacco_savings_summary?branch_id=".$branch_id."&from_year=".$from_year."&from_month=".$from_month."&to_year=".$to_year."&to_month=".$to_month."&format=excel' target=blank()><b>Export Excel</b></a>";
		    $content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <p><h5 class="text-primary mt0">INSTITUTION\'S SAVINGS SUMMARY REPORT</h5></p>
                               
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">
			<thead><th>Period</th><th>Total Deposits</th><th>Total Withdrawals</th><th>Total Interest</th><th>Total Fees</th><th>Other Deductions</th><th>Cummulative Savings</th>
		    <thead></tbody>
		    <tr>
			<td>As at End of '.$start_year.' - '.$calc->getMonthAbbrname($start_month).'</td><td align=center>'.number_format($drow1['tot_savings'], 2).'</td><td align=center>'.number_format($wrow1['tot_with'], 2).'</td><td align=center>'.number_format($irow1['tot_int'], 2).'</td><td align=center>'.number_format($tot_fees, 2).'</td><td align=center>'.number_format($tot_deduc, 2).'</td><td align=center>'.number_format($cumm_save, 2).'</td>
		    </tr>
		    ';
	$x = 0;
	while (intval(strtotime($start_date)) < intval(strtotime($final_date)))
	{
		$drow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_savings from deposit where bank_account != 0 and date >='".$start_date."' and date <= '".$end_date."'".$branch.""));
		$wrow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_with from withdrawal where date  >='".$start_date."' and date<='".$end_date."'".$branch.""));
		$crow1 = @mysql_fetch_array(@mysql_query("select sum(percent_value + flat_value) as charge from deposit where bank_account != 0 and date >= '".$start_date."' and date <= '".$end_date."'".$branch.""));
		$crow2 = @mysql_fetch_array(@mysql_query("select sum(percent_value + flat_value) as charge from withdrawal where bank_account != 0 and date >= '".$start_date."' and date <= '".$end_date."'".$branch.""));
		$mrow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_fees from monthly_charge where date >= '".$start_date."' and date <='".$end_date."'".$branch.""));
		$irow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_int from save_interest where date >= '".$start_date."' and date <='".$end_date."'".$branch.""));
		$incrow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_inc from other_income where mode not in ('cash', 'Cheque', '') and date >= '".$start_date."' and date <='".$end_date."'".$branch.""));
		$shares = @mysql_fetch_array(@mysql_query("select sum(value) as amount from shares where mode not in ('cash', 'Cheque', '') and date >= '".$start_date."' and date <='".$end_date."'".$branch.""));
		$payrow = @mysql_fetch_array(@mysql_query("select sum(princ_amt + int_amt) as tot_pay from payment where mode not in ('cash', 'Cheque', '') and date >= '".$start_date."' and date <='".$end_date."'".$branch.""));
		//$resp->append("status", innerHTML, "select sum(amount) as tot_int from save_interest where date > '".$start_date."' and date <='".$end_date."'");
		if (isset($drow['tot_savings']) || isset($wrow['tot_with']) || isset($mrow['tot_fees']) || isset($irow['tot_int']) || ($shares['amount'] != NULL))
		{
			$tot_savings = isset($drow['tot_savings'])? $drow['tot_savings'] : 0 ;
			$tot_fees = isset($mrow['tot_fees'])? $mrow['tot_fees'] : 0 ;
			$tot_with = isset($wrow['tot_with'])? $wrow['tot_with'] : 0 ;
			$tot_int = isset($irow['tot_int'])? $irow['tot_int'] : 0 ;
			$tot_inc = isset($incrow['tot_inc'])? $incrow['tot_inc'] : 0 ;
			$tot_shares = ($shares[amount])? $shares[amount] : 0 ;
			$tot_pay = isset($payrow[tot_pay])? $payrow['tot_pay']: 0 ;
			$dcharge_amt = ($crow1['charge'] != NULL) ? $crow1['charge'] : 0;
			$wcharge_amt = ($crow2['charge'] != NULL) ? $crow2['charge'] : 0;
			$tot_fees = $dcharge_amt + $wcharge_amt;
			$tot_deduc = $tot_inc + $tot_pay + $tot_shares;
			$net_save = $tot_savings + $tot_int - $tot_fees - $tot_with - $tot_deduc;
			$cumm_save += $net_save;
			//$color=($i % 2==0) ? "lightgrey" : "white";
			$content .= "
			   <tr>
				<td>".$start_year." - ".$calc->getMonthAbbrname($start_month)."</td><td align=center>".number_format($tot_savings, 2)."</td><td align=center>".number_format($tot_with, 2)."</td><td align=center>".number_format($tot_int, 2)."</td><td align=center>".number_format($tot_fees, 2)."</td><td align=center>".number_format($tot_deduc, 2)."</td><td align=center>".number_format($cumm_save, 2)."</td>
			    </tr>
			    ";
		}
		if (intval($start_month) == 12)
		{
			$start_month = 1; $start_year += 1;
		}
		else
		{
			$start_month += 1;
		}
		$x++;
		$start_date = sprintf("%d-%02d", $start_year, $start_month) . "-01 00:00:00";
		$end_date = date('Y-m-d 23:59:59', strtotime($calc->endOfMonthBySpan(0, intval($start_month), intval($start_year), '%Y-%m-%d')));
	} 	
	$content .= "</tbody></table></div>";
	//$resp->call("createTableJs");
	if ($cumm_save == 0){
		$cont = "<font color=red>No savings have been registered yet by this BRANCH.</font>";
	$resp->assign("status", "innerHTML", $cont);
	}
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function cummulated_savings($date, $branch_id)
{       $content ='';
	$resp = new xajaxResponse();
	$branch = ($branch_id=='all'||$branch_id=='')? "": 'where branch_id='.$branch_id;
	$mem_res = @mysql_query("select id as mem_id, mem_no, first_name, last_name from member ".$branch." order by first_name, last_name asc");
	$mem_res2 = @mysql_query("select id as mem_id, mem_no, first_name, last_name from member ".$branch." order by first_name, last_name asc ");
	//$date = sprintf("%d-%02d-%02d", $date);
	list($year,$month,$mday) = explode('-', $date);
	////$max_page = ceil(mysql_num_rows($mem_res)/$num_rows);
	if (@mysql_num_rows($mem_res) > 0)
	{	
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3  class="panel-title">SEARCH FOR MEMBERS CUMMULATED SAVINGS REPORT</h3>
                                <p class="text-default nm"></p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">As At:</label>
                                          <input type="text" class="form-control" id="date" name="date" placeholder="date" />        
                                        </div>
                                        <div class="col-sm-6">
                                           <label class="control-label">Branch:</label>
                                           '.branch_rep($branch_id).'
                                        </div>
                                    </div>
                                </div>       
        
                        
                        ';                                    
                                   	
		 $content .= "<div class='panel-footer'>                              
                               
                                <button type='button' class='btn  btn-primary' onclick=\"xajax_cummulated_savings(document.getElementById('date').value,getElementById('branch_id').value);\">Show Savings</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","date");
                    
               // $resp->assign("display_div", "innerHTML", $content);
				    

	if($date==''){
		
		$cont = "<font color=red>Select date for your report.</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
	}
	else{
	
	$content .="<a href='export/cummulated_savings?year=".$year."&branch_id=".$branch_id."&month=".$month."&mday=".$mday."&stat=".$stat."' target=blank()><b>Printable Version</b></a> | <a href=export/cummulated_savings?year=".$year."&branch_id=".$branch_id."&month=".$month."&mday=".$mday."&stat=".$stat."&format=excel' target=blank()><b>export Excel</b></a>";
		
		 $content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                               <p><h5 class="text-primary mt0">MEMBERS CUMMULATED SAVINGS REPORT</h5></p><p></p>
                               
                                
                            </div>';
                            
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">
 		
		<thead><th>#</th><th>Name</th><th>Member No</th><th>Total Deposits</th><th>Total Withdrawals</th><th>Total Interest</th><th>Total Fees</th><th>Other Deductions</th><th>Cummulative Savings</th>
		    </thead><tbody>
		    ';
		$x = $stat+1;
		$sub_net_save = 0;
		$sub_tot_fees = 0;
		$sub_tot_int = 0;
		$sub_tot_with = 0;
		$sub_tot_deduc = 0;
		$sub_tot_savings = 0;
		
		while ($mem_row = @mysql_fetch_array($mem_res2))
		{
			$tot_savings = 0;
			$tot_shares=0;
			$tot_with = 0;
			$tot_fees = 0;
			$tot_int = 0;
			$tot_inc = 0;
			$tot_pay = 0;
			$tot_deduc = 0;
			$cumm_save = 0;
			$net_save = 0;
			$mem_ac = @mysql_query("select id as mem_acct_id from mem_accounts where mem_id = $mem_row[mem_id]");
			if (@mysql_num_rows($mem_ac) > 0)
			{
				while ($mem_ac_row = @mysql_fetch_array($mem_ac))
				{
					$drow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_savings, sum(flat_value + percent_value) as charges from deposit where bank_account != 0 and memaccount_id = $mem_ac_row[mem_acct_id] and date <= '".$date."'"));
					$wrow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_with, sum(flat_value + percent_value) as charges from withdrawal where memaccount_id = $mem_ac_row[mem_acct_id] and date <= '".$date."'"));
					$mrow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_fees from monthly_charge where memaccount_id = $mem_ac_row[mem_acct_id] and date <= '".$date."'"));
					$irow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_int from save_interest where memaccount_id = $mem_ac_row[mem_acct_id] and date <= '".$date."'"));
					$incrow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_inc from other_income where mode = '$mem_ac_row[mem_acct_id]' and date <= '".$date."'"));
					$prow = @mysql_fetch_array(@mysql_query("select sum(princ_amt + int_amt) as tot_pay from payment where mode = '$mem_ac_row[mem_acct_id]' and date <= '".$date."'"));
					$shares = mysql_fetch_array(mysql_query("select sum(value) as amount from shares where mode = '$mem_ac_row[mem_acct_id]' and date <= '".$date."'"));
					
				
					$tot_savings += ($drow['tot_savings'] != NULL)? $drow['tot_savings'] : 0 ;
					$tot_fees += ($mrow['tot_fees'] != NULL)? $mrow['tot_fees'] : 0 ;
					$tot_fees += ($drow['charges']  != NULL)? $drow['charges'] : 0 ;
					$tot_fees += ($wrow['charges'] != NULL)? $wrow['charges'] : 0 ;
					$tot_with += ($wrow['tot_with'] != NULL)? $wrow['tot_with'] : 0;
					$tot_int += ($irow['tot_int'] != NULL)? $irow['tot_int'] : 0 ;
					$tot_shares = ($shares['amount'] != NULL)? $shares['amount'] : 0 ;
					$tot_deduc += ($incrow['tot_inc'] != NULL)? $incrow['tot_inc'] : 0 ;
					$tot_deduc += ($prow['tot_pay']!= NULL)? $prow['tot_pay']: 0;
					$tot_deduc += $tot_shares;
					//tot_deduc += $tot_inc + $tot_pay;
					
					$net_save = $tot_savings + $tot_int - $tot_fees - $tot_with - $tot_deduc - $tot_shares;
					$cumm_save += $net_save;
				} // close while mem_ac
			} // close if mem_ac
			//$color = ($x%2 == 0) ? "lightgrey" : "white";
			$content .= "
		   <tr>
			<td>".$x."</td><td>".$mem_row['first_name']."".$mem_row['last_name']."</td><td>".$mem_row['mem_no']."</td><td align=center>".number_format($tot_savings, 2)."</td><td align=center>".number_format($tot_with, 2)."</td><td align=center>".number_format($tot_int, 2)."</td><td align=center>".number_format($tot_fees, 2)."</td><td align=center>".number_format($tot_deduc, 2)."</td><td align=center> ".number_format($net_save, 2)."</td>
		    </tr>
		    ";
				$sub_net_save += $net_save;
				$sub_tot_fees += $tot_fees;
				$sub_tot_int += $tot_int;
				$sub_tot_with += $tot_with;
				$sub_tot_deduc += $tot_deduc;
				$sub_tot_savings += $tot_savings;
			$x++;
		} 	// close while mem_row
		$content .= "
		    <tr>
			<td></td><td><b>Total</b><td></td></td><td align=center>".number_format($sub_tot_savings, 2)."</td><td align=center>".number_format($sub_tot_with, 2)."</td><td align=center>".number_format($sub_tot_int, 2)."</td><td align=center>".number_format($sub_tot_fees, 2)."</td><td align=center>".number_format($sub_tot_deduc, 2)."</td><td align=center> ".number_format($sub_net_save, 2)."</td>
		    </tr>
		    ";
	}
	} // close if mem_res
	else{
		$cont = "<font color='red'>No members have been registered yet.</font>";
	$resp->assign("status", "innerHTML", $cont);
	//return $resp;
	}
	$content .= "</tbody></table></div>";
	
	$resp->call("createTableJs");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


//SAVINGS LEDGER FORM
function savings_ledger_form($mem_id)
{       
        $save_accts=0;
        $members =0;
	$resp = new xajaxResponse();
	if ($mem_id != '')
	{
		$mem_ac_res = @mysql_query("select ma.id as mem_acct_id, ac.name, ac.account_no from mem_accounts ma join savings_product sp on ma.saveproduct_id = sp.id join accounts ac on sp.account_id = ac.id where ma.mem_id = $mem_id and sp.type='free'");
		if (@mysql_num_rows($mem_ac_res) > 0)
		{	
			while ($acc_row = @mysql_fetch_array($mem_ac_res))
			{
				$save_accts .= "<option value='".$acc_row['mem_acct_id']."'>".$acc_row['account_no']." -". $acc_row['name']."</option>";
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
		$members .= "<option value=''>&nbsp;</option>";
		while ($mem_row = @mysql_fetch_array($mem_res))
		{
			if ($mem_row['mem_id'] == $mem_id)
				$members .= "<option value='".$mem_row['mem_id']."' selected>". $mem_row['first_name']."". $mem_row['last_name']." -". $mem_row['mem_no']." </option>";
			else
				$members .= "<option value='".$mem_row['mem_id']."'>".$mem_row['first_name']."". $mem_row['last_name']." -". $mem_row['mem_no']." </option>";
		}
	}
	else
		$members = "<option value=''>&nbsp;</option>";
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR SAVINGS LEDGER</h3>
                                <p class="text-default nm"></p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">By Member No:</label>
                                            <input type="text" id="mem_no" name="mem_no" value="All" class="form-control">                                            
                                        </div>
                                        <div class="col-sm-6">
                                           <label class="control-label">OR Select Member:</label>
                                         <select name="member" id="member" class="form-control" onchange=\'xajax_savings_ledger_form(getElementById("member").value); return false;\'>'.$members.'</select>
                                        </div>
                                    </div>
                                </div>  ';
                                
                                if($mem_id<>"")
                                $content .='<div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Select Savings Account:</label>
                                            <select id="save_acct" name="save_acct" class="form-control">'.$save_accts.'</select> </div> </div> </div>';		
	                      else
		                 $content .= '<input type="hidden" id="save_acct">';     
        
                        
                               $content .='<div class="form-group">
                                    <div class="row">
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
                               
                                <button type='button' class='btn  btn-primary'  onclick=\"xajax_savings_ledger(getElementById('mem_no').value, getElementById('member').value, getElementById('save_acct').value,  getElementById('from_date').value,  getElementById('to_date').value); return false;\">Search</button>
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
		}elseif(mysql_numrows($acct_res) ==1){
			$acct = mysql_fetch_array($acct_res);
			$save_acct = $acct['id'];
			$mem_id = $row['id'];
		}elseif(mysql_numrows($acct_res) ==0){
			$resp->alert("This Member hasnt a savings account!");
			return $resp;
		}
	}
	$start_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$end_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_year);
	//$end_date = date('Y-m-d', strtotime($calc->endOfMonthBySpan(0, intval($to_month), intval($to_year), intval($to_date))));
	$start_month = intval($from_month); $end_month = intval($to_month);
	$start_year = intval($from_year); $end_year = intval($to_year);
	$total_saved = 0; $total_with = 0; $total_int = 0; $total_fees = 0;
	$cumm_save = 0;
	
	$drow1 = @mysql_fetch_array(@mysql_query("select sum(amount - flat_value - percent_value) as tot_savings from deposit where bank_account != 0 and memaccount_id = $save_acct and date <= '".$start_date."'"));
	$wrow1 = @mysql_fetch_array(@mysql_query("select sum(amount + flat_value + percent_value) as tot_with from withdrawal where memaccount_id = $save_acct and date <= '".$start_date."'"));
	$mrow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_fees from monthly_charge where memaccount_id = $save_acct and date <= '".$start_date."'"));
	$irow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_int from save_interest where memaccount_id = $save_acct and date <= '".$start_date."'"));
	$prow1 = @mysql_fetch_array(@mysql_query("select sum(princ_amt + int_amt) as tot_int from payment where mode = '$save_acct' and date <= '".$start_date."'"));
	$incow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_inc from other_income where mode = '$save_acct' and date <= '".$start_date."'"));

      	$shares = @mysql_fetch_array(@mysql_query("select sum(value) as tot_val from shares where mode = '$save_acct' and date <= '".$start_date."'"));
	$total_shares = isset($shares['tot_val'])? $shares['tot_val'] : 0 ;

        $total_saved = isset($drow1['tot_savings'])? intval($drow1['tot_savings']) : 0 ;
        $total_fees = isset($mrow1['tot_fees'])? intval($mrow1['tot_fees']) : 0 ;
        $total_with = isset($wrow1['tot_with'])? intval($wrow1['tot_with']) : 0 ;
        $total_int = isset($irow1['tot_int'])? intval($irow1['tot_int']) : 0 ;
		$total_pay = isset($prow1['tot_int'])? intval($prow1['tot_int']) : 0 ;
		$total_inc = isset($incow1['tot_inc'])? intval($incow1['tot_inc']) : 0 ;
        $net_save = ($total_saved + $total_int) - ($total_fees + $total_with + $total_pay + $total_inc + $total_shares);
	$cumm_save += $net_save;
	$mem_row = @mysql_fetch_array(@mysql_query("select first_name, last_name, mem_no, sign_name, photo_name from member where id = $mem_id"));
	$branch = mysql_fetch_array(mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'"));		  
		  
		  $content = "<div class='panel panel-default' id='demo'>
                            <div class='panel-heading'>
                        <center><h3 class='semibold text-primary mt0 mb5'><B>".$branch['branch_name']."</B></h3>
		  <h4 class='semibold text-primary mt0 mb5'>INTERIM SAVINGS STATEMENT</h4></center>
		   
			<p><h5 class='semibold text-primary mt0 mb5'><B>Name:</B></h5><b>".strtoupper($mem_row['first_name'])." ". strtoupper($mem_row['last_name'])."</b></p><p><h5 class='semibold text-primary mt0 mb5'><B>Member Number:</B></h5><b>".$mem_row['mem_no']."</b></p><p><h5 class='semibold text-primary mt0 mb5'><B>Period:</B></h5><b>".$start_date  ."  -  ". $end_date."</b></p></div>";
			
		   $content .= "<table class='table table-striped table-bordered' id='table-tools'>
		   <tr><td><img src='photos/".$mem_row['photo_name']."?dummy=".time()."' width=90 height=90><br>Photo</td><td align=right><img src='signs/".$mem_row['sign_name']."?dummy=".time()."' width=90 height=90><br>Signature</td></tr></table><br>
			<a href='export/savings_ledger?mem_no=".$mem_no."&mem_id=".$mem_id."&save_acct=".$save_acct."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."' target=blank()><b>Printable Version</b></a> | <a href='export/savings_ledger?mem_no=".$mem_no."&mem_id=".$mem_id."&save_acct=".$save_acct."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&format=excel' target=blank()><b>Export Excel</b></a></div>";
			
	//$resp->append("display_div", "innerHTML", $content);
		  
		  
		   $content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">SAVINGS</h3>                          
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">
		    <thead>
			<th>Date</th><th>Depositor</th><th>Description</th><th>Debit</th><th>Credit</th><th>Account Balance</th>
		    </thead><tbody>
		    <tr>
			<td>Before '.$start_date.'</td><td>--</td><td align=center>B/F</td><td align=center>--</td><td align=center>--</td><td align=center>'.number_format($cumm_save, 2).'</td>
		    </tr>
		    ';
		$acc_res = @mysql_query("select id, date, amount, transaction, depositor as depositor from deposit where bank_account != 0 and memaccount_id = $save_acct and date > '".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction, '--' as depositor from withdrawal where memaccount_id = $save_acct and date > '".$start_date."' and date <= '".$end_date."' UNION select id, date, amount, transaction, '--' as depositor  from monthly_charge where memaccount_id = $save_acct and date >'".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction, '--' as depositor  from save_interest where memaccount_id = $save_acct and date > '".$start_date."' and date <='".$end_date."' UNION select id, date, princ_amt + int_amt as amount, transaction, '--' as depositor  from payment where mode= '".$save_acct."' and date > '".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction, '--' as depositor  from other_income where mode = '".$save_acct."' and date > '".$start_date."' and date <= '".$end_date."' UNION select id, date, value as amount, transaction, '--' as depositor  from shares where mode = '".$save_acct."' and date > '".$start_date."' and date <= '".$end_date."' order by date asc");
	$x = 0;
	while ($acc_row = @mysql_fetch_array($acc_res))
	{
		$charge_amt = 0;
		$tot_shares = strtolower($acc_row['transaction']) == 'shares' ? intval($acc_row['amount']) : 0 ;
		$tot_savings = strtolower($acc_row['transaction']) == 'deposit' ? intval($acc_row['amount']) : 0 ;
		$tot_fees = strtolower($acc_row['transaction']) == 'monthly_charge' ? intval($acc_row['amount']) : 0 ;
		$tot_with = strtolower($acc_row['transaction']) == 'withdrawal' ? intval($acc_row['amount']) : 0 ;
		$tot_int = strtolower($acc_row['transaction']) == 'save_interest' ? intval($acc_row['amount']) : 0 ;
		$tot_pay = strtolower($acc_row['transaction']) == 'payment' ? intval($acc_row['amount']) : 0 ;
		$tot_inc = strtolower($acc_row['transaction']) == 'other_income' ? intval($acc_row['amount']) : 0 ;

		if(strtolower($acc_row['transaction']) == 'deposit'){
			$charge = mysql_fetch_array(mysql_query("select receipt_no, cheque_no, (flat_value + percent_value) as amount from deposit where id='".$acc_row['id']."'"));
			$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
			$descr="Deposit<br>RCPT: ".$charge['receipt_no'];
			$descr = ($charge['cheque_no'] <>"") ? $descr."<br>CHEQ: ".$charge['cheque_no'] : $descr;
		}
		if(strtolower($acc_row['transaction']) == 'withdrawal'){
			$charge = mysql_fetch_array(mysql_query("select voucher_no, cheque_no, flat_value + percent_value as amount from withdrawal where id='".$acc_row['id']."'"));
			$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
			$descr="Withdrawal<br>PV: ".$charge['voucher_no'];
			$descr = ($charge['cheque_no'] <>"") ? $descr."<br>CHEQ: ".$charge['cheque_no'] : $descr;
		}
		if(strtolower($acc_row['transaction']) == 'payment'){
	
			$pay = mysql_fetch_array(mysql_query("select receipt_no,  princ_amt + int_amt as amount from payment where id='".$acc_row['id']."'"));
			$pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;
			$descr="Loan Repayment<br>PV: ".$pay['receipt_no'];
			//$resp->alert($tot_pay);
		}

		if(strtolower($acc_row['transaction']) == 'other_income'){
	
			$inc = mysql_fetch_array(mysql_query("select i.receipt_no, i.cheque_no, i.amount, a.name from other_income i join accounts a on a.id = i.account_id where i.id='".$acc_row['id']."'"));
			$inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;
			$descr="DEDUCTION(".$inc['name'].")"."<br>PV / CHEQ: ".$inc['receipt_no']. " ".$inc['cheque_no'];
			//$resp->alert($tot_pay);
		}
		if(strtolower($acc_row['transaction']) == 'shares'){
	
			$share = mysql_fetch_array(mysql_query("select s.receipt_no, s.value as amount from shares s where s.id='".$acc_row['id']."'"));
			$share_amt = ($share['amount'] != NULL) ? $share['amount'] : 0;
			$descr="TRANSFER TO SHARES <br>PV / CHEQ: ".$share['receipt_no'];
			//$resp->alert($tot_pay);
		}
		//$tot_fees = $tot_fees + $charge_amt;
		//$net_save = ($tot_savings + $tot_int) - ($tot_fees + $charge_amt + $tot_with);
		//$cumm_save += $net_save;
		if($tot_savings >0){
			$cumm_save += $tot_savings;
			$x++;
			//$color=($i % 2==0) ? "lightgrey" : "white";
			$content .= "
		   <tr>
			<td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td align=center>".$descr."</td><td align=center>--</td><td align=center>".number_format($tot_savings, 2)."</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($tot_int >0){
			$cumm_save += $tot_int;
			$x++;
			//$color=($i % 2==0) ? "lightgrey" : "white";
			$content .= "
		   <tr>
			<td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td align=center>Interest Earned</td><td align=center>--</td><td align=center>".$tot_int."</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($tot_shares >0){
			$cumm_save -= $tot_shares;
			$x++;
			//$color=($i % 2==0) ? "lightgrey" : "white";
			$content .= "
		   <tr>
			<td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td align=center>".$descr."</td><td align=center>".number_format($tot_shares, 2)."</td><td align=center>--</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($tot_with >0){
			$cumm_save -= $tot_with;
			$x++;
			//$color=($i % 2==0) ? "lightgrey" : "white";
			$content .= "
		   <tr>
			<td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td align=center>".$descr."</td><td align=center>".number_format($tot_with, 2)."</td><td align=center>--</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($tot_pay >0 || $tot_pay <0){
			$cumm_save -= $tot_pay;
			$x++;
			
			//$color=($i % 2==0) ? "lightgrey" : "white";
			$content .= "
		   <tr>
			<td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td align=center>".$descr."</td><td align=center>".number_format($tot_pay, 2)."</td><td align=center>--</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($charge_amt >0){
			$x++;
			$cumm_save -= $charge_amt;
			//$color=($i % 2==0) ? "lightgrey" : "white";
			$content .= "
		   <tr>
			<td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td align=center>Transactional Charge</td><td align=center>".$charge_amt."</td><td align=center>--</td><td align=center>".$cumm_save."</td>
		    </tr>
		    ";
		}
		if($tot_inc >0){
			$cumm_save -= $tot_inc;
			$x++;
			
			//$color=($i % 2==0) ? "lightgrey" : "white";
			$content .= "
		   <tr>
			<td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td align=center>".$descr."</td><td align=center>".number_format($tot_inc, 2)."</td><td align=center>--</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($tot_fees >0){
			$x++;
			$cumm_save -= $tot_fees;
			//$color=($i % 2==0) ? "lightgrey" : "white";
			$content .= "
		   <tr>
			<td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td align=center>Monthly Charge</td><td align=center>".$tot_fees."</td><td align=center>--</td><td align=center>".$cumm_save."</td>
		    </tr>
		    ";
		}
	}	
	$content .= "</tbody></table></div>";
	$resp->call("createTableJs");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function ind_savings_summary_form()
{       $members ='';
        
	$resp = new xajaxResponse();
	$mem_res = @mysql_query("select id as mem_id, mem_no, first_name, last_name from member order by first_name, last_name asc");
	if (@mysql_num_rows($mem_res) > 0)
	{
		$members .= "<option value=''>&nbsp;</option>";
		while ($mem_row = @mysql_fetch_array($mem_res))
		{
			$members .= "<option value='".$mem_row['mem_no']."'>".$mem_row['first_name'] ."".$mem_row['last_name']." -".$mem_row['mem_no']."</option>";
		}
	}
	else
		$members = "<option value=''>&nbsp;</option>";
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR INDIVIDUAL\'S SAVINGS SUMMARY</h3>
                                <p class="text-default nm"></p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">By Member No:</label>
                                            <input type="int" id="mem_no1" value="All" class="form-control">                                            
                                        </div>
                                        <div class="col-sm-6">
                                           <label class="control-label">OR Select Member:</label>
                                           <select name="mem_no2" id="mem_no2" class="form-control">'.$members.'</select>
                                        </div>
                                    </div>
                                </div>       
        
                        
                         <div class="form-group">
                                    <div class="row">
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
                               
                                <button type='button' class='btn  btn-primary' onclick=\"xajax_ind_savings_summary(getElementById('mem_no1').value, getElementById('mem_no2').value, getElementById('from_date').value, getElementById('to_date').value); return false;\">Show Report</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    
                    $resp->call("createDate","from_date");
	$resp->call("createDate","to_date");
                    
                //$resp->assign("display_div", "innerHTML", $content);
	
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function ind_savings_summary($mem_no1, $mem_no2, $from_date,$to_date)
{      
        list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
        $total_deduc=0;
        $net_save=0;
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	if($mem_no1=='' && $mem_no2==''){
		$resp->alert("Please specify the Member No");
		return $resp;
	}
	$start_date = date('Y-m-d 00:00:00', strtotime($calc->endOfMonthBySpan(0, intval($from_month), intval($from_year), '%Y-%m-%d')));
	$final_date = date('Y-m-d 23:59:59', strtotime($calc->endOfMonthBySpan(0, intval($to_month), intval($to_year), '%Y-%m-%d')));
	$start_month = intval($from_month); $end_month = intval($to_month);
	$start_year = intval($from_year); $end_year = intval($to_year);
	$total_saved = 0; $total_with = 0; $total_int = 0; $total_fees = 0;
	$cumm_save = 0;
	if($mem_no1<>'' && $mem_no2<>'')
		$mem_no = $mem_no2;
	elseif($mem_no1<>'')
		$mem_no = $mem_no1;
	else
		$mem_no = $mem_no2;

	$mem_ac = mysql_query("select ma.id as mem_acct_id from mem_accounts ma join member m on ma.mem_id=m.id where m.mem_no = '".$mem_no."'");
	
	if (@mysql_num_rows($mem_ac) > 0)
	{       
	
		while ($mem_ac_row = @mysql_fetch_array($mem_ac))
		{
			$drow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_savings from deposit where bank_account != 0 and memaccount_id = $mem_ac_row[mem_acct_id] and date <= '".$start_date."'"));
			$wrow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_with from withdrawal where memaccount_id = $mem_ac_row[mem_acct_id] and date <= '".$start_date."'"));
			$mrow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_fees from monthly_charge where memaccount_id = $mem_ac_row[mem_acct_id] and date <= '".$start_date."'"));
			$prow1 = @mysql_fetch_array(@mysql_query("select sum(princ_amt + int_amt) as tot_pay from payment where mode = '$mem_ac_row[mem_acct_id]' and date <= '".$start_date."'"));
			$irow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_int from save_interest where memaccount_id = $mem_ac_row[mem_acct_id] and date <= '".$start_date."'"));
			$incow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_inc from other_income where mode = '$mem_ac_row[mem_acct_id]' and date <= '".$start_date."'"));
			$shares = @mysql_fetch_array(@mysql_query("select sum(value) as amount from shares where mode = '$mem_ac_row[mem_acct_id]' and date <= '".$start_date."'"));
			$c1row = @mysql_fetch_array(@mysql_query("select sum(w.percent_value + w.flat_value) as charge from  withdrawal w  join mem_accounts ma on w.memaccount_id=ma.id where  w.date <='".$start_date."'  and w.memaccount_id='".$mem_ac_row['mem_acct_id']."'"));
			
			$c2row = @mysql_fetch_array(@mysql_query("select sum(d.percent_value + d.flat_value) as charge from  deposit d  join mem_accounts ma on d.memaccount_id=ma.id where  d.date <= '".$start_date."' and d.memaccount_id='".$mem_ac_row['mem_acct_id']."'"));


           	$total_saved =($drow1['tot_savings'] != NULL)? intval($drow1['tot_savings']) : 0 ;
            $total_fees = ($mrow1['tot_fees'] != NULL)? intval($mrow1['tot_fees']) : 0 ;
            $total_with = ($wrow1['tot_with'] != NULL)? intval($wrow1['tot_with']) : 0 ;
            $total_int = ($irow1['tot_int'] != NULL)? intval($irow1['tot_int']) : 0 ;
			$total_pay = ($prow1['tot_pay']!= NULL)? intval($prow1['tot_pay']) : 0 ;
			$total_inc = ($incow1['tot_inc'] != NULL) ? intval($incowl['tot_inc']) : 0;
			$total_shares = ($shares['amount'] != NULL) ? $shares['amount'] : 0;
			$dcharge_amt = ($c1row['charge'] != NULL)? intval($c1row['charge']) : 0 ;
			$wcharge_amt = ($c2row['charge']  != NULL)? intval($c2row['charge']) : 0 ;
			$total_fees += $dcharge_amt + $wcharge_amt;
			$total_deduc += $total_pay + $total_inc + $total_shares;
            $net_save = ($total_saved + $total_int) - ($total_fees + $total_with + $total_inc + $total_pay -$total_shares);
		    $cumm_save += $net_save;
		}
	}
	else
	{
		$cont = "<font color='red'>No savings accounts yet registered for this member.</font><br>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	$mem_row = @mysql_fetch_array(@mysql_query("select first_name, mem_no, last_name from member where mem_no = '$mem_no'"));
	$branch_res = mysql_query("select * from branch");
	$branch = mysql_fetch_array($branch_res);
		
		   $content =" <a href='export/ind_savings_summary?mem_no1=".$mem_no1."&mem_no2=".$mem_no2."&from_year=".$from_year."&from_month=".$from_month."&to_year=".$to_year."&to_month=".$to_month."' target=blank()><b>Printable Version</b></a> |  <a href='export/ind_savings_summary?mem_no1=".$mem_no1."&mem_no2=".$mem_no2."&from_year=".$from_year."&from_month=".$from_month."&to_year=".$to_year."&to_month=".$to_month."&format=excel' target=blank()><b>Export Excel</b></a>
		   ";
		   
		   $content .= "<div class='panel panel-default' id='demo'>
                            <div class='panel-heading'>
                        <p><center><h3 class='semibold text-primary mt0 mb5'><B>".$branch['branch_name']."</B></h3>
		  <h4 class='semibold text-primary mt0 mb5'>SAVINGS SUMMARY REPORT</h4></center></p>
		   
			<p><h5 class='semibold text-primary mt0 mb5'><B>Name:</B></h5><b>".strtoupper($mem_row['first_name'])." ". strtoupper($mem_row['last_name'])."</b></p><p><h5 class='semibold text-primary mt0 mb5'><B>Member Number:</B></h5><b>".$mem_row['mem_no']."</b></p><p><h5 class='semibold text-primary mt0 mb5'><B>Period:</B></h5><b>".$start_date  ."  -  ". $final_date."</b></p></div>";
		 $content .= "<table class='table table-striped table-bordered' id='table-tools'>
		    <thead>
			<th>Period</th><th>Total Deposits</th><th>Total Withdrawals</th><th>Total Interest</th><th>Total Fees</th><th>Other Deductions</th><th>Cummulative Savings</th>
		    </thead><tbody>
		    <tr>
			<td>Before ".$start_year." - ".$calc->getMonthAbbrname($start_month)."</td><td align=center>".number_format($total_saved, 2)."</td><td align=center>".number_format($total_with, 2)."</td><td align=center>".number_format($total_int, 2)."</td><td align=center>".number_format($total_fees, 2)."</td><td align=center>".number_format($total_deduc, 2)."</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
	$x = 1;
	//$mem_ac2 = @mysql_query("select ma.id as mem_acct_id from mem_accounts ma join member m on ma.mem_id=m.id where m.mem_no = $mem_no");
	//$mem_ac2_row = @mysql_fetch_array($mem_ac2);
	while (intval(strtotime($start_date)) < intval(strtotime($final_date)))
	{
		 if (intval($start_month) == 12)
		{
			$start_month = 1; $start_year = $start_year + 1;
		}
		else
		{
			$start_month = $start_month + 1;
		}
		//$x++;
		$start_date = sprintf("%d-%02d", $start_year, $start_month) . "-01 00:00:00";
		$end_date = date('Y-m-d 23:59:59', strtotime($calc->endOfMonthBySpan(0, intval($start_month), intval($start_year))));
	
		$tot_savings = 0; $tot_int = 0; $tot_with = 0; $tot_fees = 0; $tot_deduc = 0; $tot_pay = 0; $tot_inc = 0;
		$mem_ac2 = @mysql_query("select ma.id as mem_acct_id from mem_accounts ma join member m on ma.mem_id=m.id where m.mem_no = $mem_no");
		//while ($mem_ac2_row = @mysql_fetch_array($mem_ac2))
		//{
			$mem_ac2_row = @mysql_fetch_array($mem_ac2);
			$drow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_savings from deposit where bank_account != 0 and date >= '".$start_date."' and date <='".$end_date."' and memaccount_id='".$mem_ac2_row['mem_acct_id']."'"));
			$wrow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_with from withdrawal where date >= '".$start_date."' and date <='".$end_date."' and memaccount_id='".$mem_ac2_row['mem_acct_id']."'"));
			$mrow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_fees from monthly_charge where date >= '".$start_date."' and date <='".$end_date."' and memaccount_id='".$mem_ac2_row['mem_acct_id']."'"));
			$irow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_int from save_interest where date >= '".$start_date."' and date <='".$end_date."' and memaccount_id='".$mem_ac2_row['mem_acct_id']."'"));
			$incrow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_inc from other_income where date >= '".$start_date."' and date <='".$end_date."' and mode='".$mem_ac2_row['mem_acct_id']."'"));
			$shares = @mysql_fetch_array(@mysql_query("select sum(value) as amount from shares where date >= '".$start_date."' and date <='".$end_date."' and mode='".$mem_ac2_row['mem_acct_id']."'"));
			$payrow = @mysql_fetch_array(@mysql_query("select sum(princ_amt + int_amt) as tot_pay from payment where date >= '".$start_date."' and date <='".$end_date."' and mode='".$mem_ac2_row['mem_acct_id']."'"));
			$c1row = @mysql_fetch_array(@mysql_query("select sum(w.percent_value + w.flat_value) as charge from  withdrawal w  join mem_accounts ma on w.memaccount_id=ma.id where  w.date >= '".$start_date."' and w.date <= '".$end_date."' and w.memaccount_id='".$mem_ac2_row['mem_acct_id']."'"));
			
			$c2row = @mysql_fetch_array(@mysql_query("select sum(d.percent_value + d.flat_value) as charge from  deposit d  join mem_accounts ma on d.memaccount_id=ma.id where  d.date >= '".$start_date."' and d.date <= '".$end_date."' and d.memaccount_id='".$mem_ac2_row['mem_acct_id']."'"));
			if (isset($drow['tot_savings']) || isset($wrow['tot_with']) || isset($mrow['tot_fees']) || isset($irow['tot_int']) || isset($incrow['tot_inc']) || isset($payrow['tot_pay']) || isset($c1row['charge']) || isset($c2row['charge']))
			{
				$tot_savings = ($drow['tot_savings']  != NULL)? intval($drow['tot_savings']) : 0 ;
				$tot_fees = ($mrow['tot_fees']  != NULL)? intval($mrow['tot_fees']) : 0 ;
				$tot_with = ($wrow['tot_with']  != NULL)? intval($wrow['tot_with']) : 0 ;
				$tot_int = ($irow['tot_int']  != NULL)? intval($irow['tot_int']) : 0 ;
				$tot_inc = ($incrow['tot_inc']  != NULL)? intval($incrow['tot_inc']) : 0 ;
				$tot_shares = ($shares['amount'] != NULL)? intval($shares['amount']) : 0 ;
				$tot_pay = ($payrow['tot_pay'] != NULL)? intval($payrow['tot_pay']) : 0 ;
				$tot_charge1 = ($c1row['charge']  != NULL)? intval($c1row['charge']) : 0 ;
				$tot_charge2 = ($c2row['charge'] != NULL)? intval($c2row['charge']) : 0 ;
				$tot_fees += $tot_charge1 + $tot_charge2; 
				$tot_deduc += $tot_pay + $tot_inc + $tot_shares;
				$net_save = ($tot_savings + $tot_int) - ($tot_fees + $tot_with + $tot_deduc);
				$cumm_save += $net_save;
			}
			//$color = ($x%2 == 0) ? "lightgrey" : "white";
			if ($tot_savings > 0 || $tot_with > 0 || $tot_fees > 0 || $tot_int > 0 || $tot_deduc > 0)
			{	
				$content .= "
					<tr>
				<td>".$start_year ."- ".$calc->getMonthAbbrname($start_month)."</td><td align=center>".number_format($tot_savings, 2)."</td><td align=center>".number_format($tot_with, 2)."</td><td align=center>".number_format($tot_int, 2)."</td><td align=center>".number_format($tot_fees, 2)."</td><td align=center>ttttttt".number_format($tot_deduc, 2)."</td><td align=center>".number_format($cumm_save, 2)."</td>
			    </tr>
			    ";
				$x++;
			}
			/*
			if (intval($start_month) == 12)
			{
				$start_month = 1; $start_year += 1;
			}
			else
			{
				$start_month += 1;
			}
			$x++;
			$start_date = sprintf("%d-%02d", $start_year, $start_month) . "-01";
			$end_date = date('Y-m-d', strtotime($calc->endOfMonthBySpan(0, intval($start_month), intval($start_year), '%Y-%m-%d')));
			*/
		//}
		
		
		
	} 	
	$content .= "</tbody></table></div>";
	$resp->call("createTableJs");
	if ($cumm_save == 0){
		$cont = "<font color=red>No savings have been registered yet for the member</font> ";
		
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
		
		}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//GENERATE SHARES REPORT
//GENERATE SHARES REPORT
function genSharesReport($date,$branch_id)
{
list($year,$month,$mday) = explode('-', $date);
        $tot_balance = 0;
			$tot_dividends = 0;
			$tot_totalval = 0;
			$tot_tot_mem_shares = 0;
			$max_page=0;

	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	//$date = sprintf("%02d-%02d-%02d", $date);
	$branch = ($branch_id=='all'||$branch_id=='')?NULL:"where branch_id=".$branch_id;
	$tot_shares = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_share from shares ".$branch));
	$mem_res = @mysql_query("select id, mem_no, first_name, last_name from member ".$branch." order by first_name, last_name asc");
	$mem_res2 = @mysql_query("select id, mem_no, first_name, last_name from member ".$branch." order by first_name, last_name asc");
	if ($tot_shares['tot_share'] != NULL)
	{
		$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR SHARES REPORT</h3>
                                <p class="text-default nm"></p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">As At:</label>
                                           <input type="text" class="form-control" id="date" name="date" placeholder="date" />
                                        </div>
                                        <div class="col-sm-6">
                                           <label class="control-label">Branch:</label>
                                            <span>'.branch_rep($branch_id).'</span>                                      
                                        </div>
                                    </div>
                                </div>';        
		
		 $content .= "<div class='panel-footer'>                              
                                
                                <button type='button' class='btn  btn-primary'  onclick=\"xajax_genSharesReport(document.getElementById('date').value,document.getElementById('branch_id').value)\">Search</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","date");
                //$resp->assign("display_div", "innerHTML", $content);
		
		
			
		if($date == ''){
			
			$cont = "<font color=red>Select the date for your report!</font>";
		$resp->assign("status", "innerHTML", $cont);
	       //return $resp;
		}else{
		
		        $content .="<a href='export/shares_report?year=".$year."&month=".$month."&mday=".$mday."&branch_id=".$branch_id."&format=' target=blank()><b>printable Version</b></a>|
		<a href='export/shares_report?year=".$year."&month=".$month."&mday=".$mday."&branch_id=".$branch_id."&format=excel' target=blank()><b>export Excel</b></a>";
		       
			//////$max_page = ceil(mysql_num_rows($mem_res)/$num_rows);
			
			 $content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">SHARES REPORT</h3>                              
                                
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">
 		<thead><th>Name</th><th>Member No.</th><th>No. of Shares</th><th>Value of Shares</th><th>Percentage</th><th>Dividends Receivable</th><th>Balance</th></thead><tbody>';
			$i=0;
			 
			while ($members = @mysql_fetch_array($mem_res2))
			{
				$row = @mysql_fetch_array(@mysql_query("select share_value as shareval, max_share_percent as percentage from branch"));
				$share_purchase = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_pur, sum(value) as value from shares where mem_id = ".$members['id']." and date <= '".$date."'"));
				$share_sale = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_sale from share_transfer where from_id = ".$members['id']." and date <= '".$date."'"));
				$bought = @mysql_fetch_array(@mysql_query("select sum(shares) as shares from share_transfer where to_id = ".$members['id']." and date <= '".$date."'"));
				$div_res = mysql_query("select sum(d.amount) as amount from dividends d join share_dividends s on d.share_dividend_id=s.id where d.mem_id=".$members['id']." and s.bank_account=0 and s.date <= '".$date."'");
				$div = mysql_fetch_array($div_res);
				$dividends = $div['amount'];

				$transact = $share_sale['tot_sale'] + $bought['shares'] - $share_sale['tot_sale'];
				$transacted_amt = $transact * $row['shareval'];

				$tot_mem_shares = $share_purchase['tot_pur'] - $share_sale['tot_sale'] + $bought['shares'];
				//$totalval = $tot_mem_shares * $row['shareval'];
				$totalval = $transacted_amt + $share_purchase['value'];

				$percentage = ($tot_mem_shares / $tot_shares['tot_share']) * 100;
				$percentage = sprintf("%.02f", $percentage);
				$balance = $totalval + $dividends;
				//$color = ($i % 2 ==0) ? "lightgrey" : "white";
				$content .= "<tr><td>".$members['first_name']."". $members['last_name']."</td><td>".$members['mem_no']."</td><td align='center'>".number_format($tot_mem_shares, 2)."</td><td align='center'>".number_format($totalval, 2)."</td><td align='center'>".$percentage." %</td><td align='center'>".number_format($dividends, 2)."</td><td align='center'>".number_format($balance,2)."</td></tr>";
				$i++;
				$tot_balance += $balance;
				$tot_dividends += $dividends;
				$tot_totalval += $totalval;
				$tot_tot_mem_shares += $tot_mem_shares; 
			}
		
		$content .= "<tr><td></td><td></td><td>".number_format($tot_tot_mem_shares,2)."</td><td align='right'>".number_format($tot_totalval, 2)."</td><td></td><td>".number_format($tot_dividends, 2)."</td><td align='right'>".number_format($tot_balance, 2)."</td></tr>";
		$content .= "</tbody></table></div>";
	}
	}
	else{
		
		$cont = "<font color=red>No registered shares yet.</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
	}
	$resp->call("createTableJs");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}



function sharesLedgerForm()
{       $mems ="";
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	$mem_res = @mysql_query("select id, first_name, last_name, mem_no from member order by first_name, last_name desc");
	while ($mem_row = @mysql_fetch_array($mem_res))
	{
		$mems .= "<option value='".$mem_row['id']."'>".$mem_row['first_name']."".$mem_row['last_name']." -". $mem_row['mem_no']." </option>";
	}
	
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR SHARES LEDGER</h3>
                                <p class="text-default nm"></p>
                            </div>               
                            <div class="panel-body">';
	$content .= '<div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Enter Member No:</label>
                                            <div class="input-group">
                                            <input type="int" id="mem_no" name="mem_no" class="form-control">
                                            <span class="input-group-btn">
                                                <button class="btn btn-info" type="button" onclick=\'xajax_sharesLedger(document.getElementById("mem_no").value, "mem_no"); return false;\'>Next</button>
                                            </span>
                                        </div>                                             
                                        </div>
                                       
                                        <div class="col-sm-6">
                                            <label class="control-label">OR Select:</label>
                                             <div class="input-group">
                                <select class="form-control" name="memid" id="memid">'.$mems.'</select>
                                            <span class="input-group-btn">
                                                <button class="btn btn-info" type="button" onclick=\'xajax_sharesLedger(document.getElementById("memid").value, "mem_id"); return false;\'>Next</button>
                                            </span>
                                        </div></div>
                                    </div>
                                </div>';
                          $content .= "</div></form></div></div>";      
	
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//SHOW THE SHARES LEDGER
function sharesLedger($mem_id, $type)
{
// Direct purchases, in-ward transfers and out-ward transfers
	$resp = new xajaxResponse();
	$original_mem_id = $mem_id;
	
	$mem_res = @mysql_query("select id, first_name, last_name, mem_no from member order by first_name, last_name desc");
	while ($mem_row = @mysql_fetch_array($mem_res))
	{
		$mems .= "<option value='".$mem_row['id']."'>".$mem_row['first_name']."&nbsp;".$mem_row['last_name']." -". $mem_row['mem_no']." </option>";
	}
	
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR SHARES LEDGER</h3>
                                <p class="text-default nm"></p>
                            </div>               
                            <div class="panel-body">';
	$content .= '<div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Enter Member No:</label>
                                            <div class="input-group">
                                            <input type="int" id="mem_no" name="mem_no" class="form-control">
                                            <span class="input-group-btn">
                                                <button class="btn btn-info" type="button" onclick=\'xajax_sharesLedger(document.getElementById("mem_no").value, "mem_no"); return false;\'>Next</button>
                                            </span>
                                        </div>                                             
                                        </div>
                                       
                                        <div class="col-sm-6">
                                            <label class="control-label">OR Select:</label>
                                             <div class="input-group">
                                <select class="form-control" name="memid" id="memid">'.$mems.'</select>
                                            <span class="input-group-btn">
                                                <button class="btn btn-info" type="button" onclick=\'xajax_sharesLedger(document.getElementById("memid").value, "mem_id"); return false;\'>Next</button>
                                            </span>
                                        </div></div>
                                    </div>
                                </div>';
                          $content .= "</div></form></div></div>";
                          
                          $content2 = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR SHARES LEDGER</h3>
                                <p class="text-default nm"></p>
                            </div>               
                            <div class="panel-body">';
	$content2 .= '<div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Enter Member No:</label>
                                            <div class="input-group">
                                            <input type="int" id="mem_no" name="mem_no" class="form-control">
                                            <span class="input-group-btn">
                                                <button class="btn btn-info" type="button" onclick=\'xajax_sharesLedger(document.getElementById("mem_no").value, "mem_no"); return false;\'>Next</button>
                                            </span>
                                        </div>                                             
                                        </div>
                                       
                                        <div class="col-sm-6">
                                            <label class="control-label">OR Select:</label>
                                             <div class="input-group">
                                <select class="form-control" name="memid" id="memid">'.$mems.'</select>
                                            <span class="input-group-btn">
                                                <button class="btn btn-info" type="button" onclick=\'xajax_sharesLedger(document.getElementById("memid").value, "mem_id"); return false;\'>Next</button>
                                            </span>
                                        </div></div>
                                    </div>
                                </div>';
                          $content2 .= "</div></form></div></div>";
                          
	if($type == 'mem_no'){
		$mem_res = mysql_query("select * from member where mem_no='".$mem_id."'");
		if(mysql_numrows($mem_res) == 0){
			$resp->alert("No member identified by this number");
			//return $resp;
		}
		$mem = mysql_fetch_array($mem_res);
	
		$mem_id = $mem['id'];
	}
	else{
	$direct = @mysql_query("select id, date, shares, value, receipt_no from shares where mem_id = $mem_id and receipt_no != '' order by date asc");
	$inward = @mysql_query("select id, date, shares, value from share_transfer where to_id = $mem_id order by date asc");
	$outward = @mysql_query("select id, date, shares, value from share_transfer where from_id = $mem_id order by date asc");
	$div_res = mysql_query("select d.id,  s.date, d.amount from dividends d join share_dividends s on d.share_dividend_id=s.id where d.mem_id=$mem_id and s.bank_account=0 order by s.date asc");
	$mem = @mysql_fetch_array(@mysql_query("select first_name, mem_no, last_name from member where id = $mem_id"));
	$tot_mem_shares = 0; 
	$found_shares = 0;
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
		
	$content .= "<a href='export/shares_ledger?mem_id=".$original_mem_id."&type=".$type."' target=blank()><b>Printable Version</b></a> | <a href='export/shares_ledger?mem_id=".$original_mem_id."&type=".$type."&format=excel' target=blank()><b>Export Excel</b></a>";
	
	 $content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <p><h3 class="title-panel">'.$branch['branch_name'].'</h3></p>
                                 <p><h4 class="title-panel">SHARES LEDGER</h4></p>
                                <p><h5 class="text-primary mt0">Name: '.$mem['first_name'].'&nbsp;'. $mem['last_name'].'<h5></p>
                                 <p><h5 class="text-primary mt0">Member No: '.$mem['mem_no'].'<h5></p>
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">
 		
	<thead><th>Date</th><th>Type of Transaction</th><th>No. of Shares</th><th>Value</th><th>Total Shares</th><th>Dividends</th><th>Balance</th></thead><tbody>';
	$balance = 0;
	if (@mysql_num_rows($direct) > 0)
	{
		$found_shares += 1; $i = 0;
		while ($drow = @mysql_fetch_array($direct))
		{
			$balance += $drow['value'];
			$tot_mem_shares += $drow['shares'];
			//$color=($i % 2==0) ? "lightgrey" : "white";
			$content .= "<tr>
				    <td>".$drow['date']."</td><td>Direct Purchase<br>RCPT No: ".$drow['receipt_no']."</td><td align='center'>".$drow['shares']."</td><td align='center'>".number_format($drow['value'], 2)."</td><td align='center'>".$tot_mem_shares."</td><td>--</td><td>".number_format($balance, 2)."</td>
				    </tr>
				    ";
					$i++;
		}
	}
	if (@mysql_num_rows($inward) > 0)
	{
		$found_shares += 1;
		$i = 0;
		while ($inrow = @mysql_fetch_array($inward))
		{
			$balance += $inrow[value];
			$tot_mem_shares += $inrow[shares];
			//$color=($i % 2==0) ? "lightgrey" : "white";
			$content .= "<tr>
				    <td>".$inrow['date']."</td><td>Inward transfer</td><td align='center'>".$inrow['shares']."</td><td align='center'>".number_format($inrow['value'], 2)."</td><td align='center'>".$tot_mem_shares."</td><td>--</td><td>".number_format($balance, 2)."</td>
				    </tr>
				    ";
					$i++;
		}
	}
	if (@mysql_num_rows($outward) > 0)
	{
		$found_shares += 1;
		$i=0;
		while ($outrow = @mysql_fetch_array($outward))
		{
			$balance -= $outrow['value'];
			$tot_mem_shares -= $outrow['shares'];
			//$color=($i % 2==0) ? "lightgrey" : "white";
			$content .= "<tr>
				    <td>".$outrow['date']."</td><td>Outward transfer</td><td align='center'>".$outrow['shares']."</td><td align='center'>".number_format($outrow['value'], 2)."</td><td align='center'>".$tot_mem_shares."</td><td>--</td><td>".number_format($balance, 2)."</td>
				    </tr>
			            ";
					$i++;
		}
	}
	if (@mysql_num_rows($div_res) > 0)
	{
		$found_shares += 1;
		$i=0;
		while ($div = @mysql_fetch_array($div_res))
		{
			$balance += $div['amount'];
			//$tot_mem_shares -= intval($outrow[shares]);
			//$color=($i % 2==0) ? "lightgrey" : "white";
			$content .= "<tr>
				    <td>".$div['date']."</td><td>Dividends</td><td align='center'>--</td><td align='center'>--</td><td align='center'>".$tot_mem_shares."</td><td>".number_format($div['amount'], 2)."</td><td>".number_format($balance, 2)."</td></td>
				    </tr>
			            ";
					$i++;
		}
	}

	$content .= "</tbody></table></div>";
	
	if ($found_shares < 1){
		$cont = "<font color='red'>No Shares Activity Yet Registered For ".$mem['first_name']."". $mem['last_name']."</font>";
		$resp->assign("status", "innerHTML", $cont);
	$resp->assign("display_div", "innerHTML", $content2);
	return $resp;
	}
	$resp->call("createTableJs");
	//$resp->call("xajax_sharesLedgerForm");
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//LIST DIVIDENDS SHARING
function sharing_done($from_date,$to_date,$branch_id){
	$resp = new xajaxResponse();
	$branch = ($branch_id=='all'||$branch_id=='')?NULL:"and branch_id=".$branch_id;
	
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
        
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SHARING DONE</h3>
                                <p class="text-default nm"></p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Date Range:</label>
                                            <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>                           
                                        </div>
                                        <div class="col-sm-6">
                                           <label class="control-label">Branch:</label>
                                            <span>'.branch_rep($branch_id).'</span>                                      
                                        </div>
                                    </div>
                                </div>';        
		
		 $content .= "<div class='panel-footer'>                              
                                
                                <button type='button' class='btn  btn-primary' onclick=\"xajax_sharing_done( getElementById('from_date').value,  getElementById('to_date').value, getElementById('branch_id').value)\">Show Report</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                  $resp->call("createDate","from_date");
	$resp->call("createDate","to_date");  
               //$resp->assign("display_div", "innerHTML", $content);

	if($from_date=='' || $to_date==''){
		
		$cont = "<font color=red>Select the period for your report!</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
		
	}else{
	
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_year);
	$sth = mysql_query("select s.id as id, a.name as account_name, s.total_amount, s.date, s.action, s.bank_account, b.bank as bank_name from share_dividends s left join bank_account b on s.bank_account=b.id left join accounts a on b.account_id=a.id where s.date>='".$from_date."' and s.date <='".$to_date."' and b.branch_id='".$branch_id."'");
	
	if(mysql_numrows($sth) > 0){
		
		$content = '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">LIST OF SOLD INVESTMENTS</h3>
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
 		$content .="<thead><td><b>Date</b></td><td><b>Amount</b></td><td><b>Action</b></td><td><b>Source Bank Account</b></td><td><b>Dividends</b></td></thead><tbody>";
 		
		$i=0;
		while($row = mysql_fetch_array($sth)){
			//$color=($i % 2==0) ? "lightgrey" : "white";
			$action = ($row['action'] == 'post') ? "Posted to Members" : "Credited on Members' Shares";
			$bank_account = ($row['bank_account'] == 0) ? "Not Applicable" : $row['account_no'] ." - ".$row['bank_name']."  ".$row['account_name'];
			$content .= "<tr><td>".$row['date']."</td><td>".number_format($row['total_amount'], 2)."</td><td>".$action."</td><td>".$bank_account."</td><td><a href='list_show_dividends.php?share_dividend_id=".$row['id']."' target=blank()>Dividends</a></td></tr>";
			$i++;
		}
	
	$content .= "</tbody></table></div>";
	$resp->call("createTableJs");
	}
	else{
		
		$cont = "<font color=red>No dividends shared in the selected period!</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
	}
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}
 
//GENERATE SAVINGS LEDGER
function memsavings_ledger($mem_no, $mem_id, $save_acct, $from_date, $to_date)
{
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
		$acct_res = mysql_query("select mem.id as id, a.account_no as account_no from mem_accounts mem join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where mem.mem_id='".$row['id']."' and s.type='free'");
		if(mysql_numrows($acct_res) > 1){
			$resp->call('xajax_memsavings_ledger_form', $row['id']);
			return $resp;
		}elseif(mysql_numrows($acct_res) ==1){
			$acct = mysql_fetch_array($acct_res);
			$save_acct = $acct['id'];
			$mem_id = $row['id'];
		}elseif(mysql_numrows($acct_res) ==0){
			$resp->alert("This Member hasnt a savings account!");
			return $resp;
		}
	}
	//$acct = mysql_fetch_array($acct_res);
	$start_date = sprintf("%d-%02d", $from_year, $from_month) . "-01 00:00:00";
	$end_date = date('Y-m-d 23:59:59', strtotime($calc->endOfMonthBySpan(0, intval($to_month), intval($to_year), '%Y-%m-%d')));
	$start_month = intval($from_month); $end_month = intval($to_month);
	$start_year = intval($from_year); $end_year = intval($to_year);
	$total_saved = 0; $total_with = 0; $total_int = 0; $total_fees = 0;
	$cumm_save = 0;
	
	$drow1 = @mysql_fetch_array(@mysql_query("select sum(amount - flat_value - percent_value) as tot_savings from deposit where bank_account != 0 and memaccount_id = $save_acct and date <= '".$start_date."'"));
	$wrow1 = @mysql_fetch_array(@mysql_query("select sum(amount + flat_value + percent_value) as tot_with from withdrawal where memaccount_id = $save_acct and date <= '".$start_date."'"));
	$mrow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_fees from monthly_charge where memaccount_id = $save_acct and date <= '".$start_date."'"));
	$irow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_int from save_interest where memaccount_id = $save_acct and date <= '".$start_date."'"));
	$prow1 = @mysql_fetch_array(@mysql_query("select sum(princ_amt + int_amt) as tot_int from payment where mode = '$save_acct' and date <= '".$start_date."'"));
	$incow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_inc from other_income where mode = '$save_acct' and date <= '".$start_date."'"));

	$shares = @mysql_fetch_array(@mysql_query("select sum(value) as tot_val from shares where mode = '$save_acct' and date <= '".$start_date."'"));
	$total_shares = isset($shares['tot_val'])? $shares['tot_val'] : 0 ;

        $total_saved = isset($drow1['tot_savings'])? intval($drow1['tot_savings']) : 0 ;
        $total_fees = isset($mrow1['tot_fees'])? intval($mrow1['tot_fees']) : 0 ;
        $total_with = isset($wrow1['tot_with'])? intval($wrow1['tot_with']) : 0 ;
        $total_int = isset($irow1['tot_int'])? intval($irow1['tot_int']) : 0 ;
		$total_pay = isset($prow1['tot_int'])? intval($prow1['tot_int']) : 0 ;
		$total_inc = isset($incow1['tot_inc'])? intval($incow1['tot_inc']) : 0 ;
        $net_save = ($total_saved + $total_int) - ($total_fees + $total_with + $total_pay + $total_inc + $total_shares);
	$cumm_save += $net_save;
	$mem_row = @mysql_fetch_array(@mysql_query("select first_name, last_name, mem_no, sign_name, photo_name from member where id = $mem_id"));
	$branch = mysql_fetch_array(mysql_query("select * from branch"));
	$prod = mysql_fetch_array(mysql_query("select a.account_no as account_no from mem_accounts mem join savings_product p on mem.saveproduct_id=p.id join accounts a on p.account_id=a.id where mem.id='".$save_acct."'"));
	
	$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">INTERIM SAVINGS STATEMENT ('.$prod['account_no'].')</h3>
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">
	
			<thead><th>Date</th><th>Depositor</th><th>Description</th><th>Debit</th><th>Credit</th><th>Account Balance</th></thead><tbody>
		 
		    <tr>
			<td>Before '.$start_date.'</td><td>--</td><td align=center>B/F</td><td align=center>--</td><td align=center>--</td><td align=center>'.number_format($cumm_save, 2).'</td>
		    </tr>
		    ';
		$acc_res = @mysql_query("select id, date, amount, transaction, depositor as depositor from deposit where bank_account != 0 and memaccount_id = $save_acct and date > '".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction, '--' as depositor from withdrawal where memaccount_id = $save_acct and date > '".$start_date."' and date <= '".$end_date."' UNION select id, date, amount, transaction, '--' as depositor  from monthly_charge where memaccount_id = $save_acct and date >'".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction, '--' as depositor  from save_interest where memaccount_id = $save_acct and date > '".$start_date."' and date <='".$end_date."' UNION select id, date, princ_amt + int_amt as amount, transaction, '--' as depositor  from payment where mode= '".$save_acct."' and date > '".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction, '--' as depositor  from other_income where mode = '".$save_acct."' and date > '".$start_date."' and date <= '".$end_date."' UNION select id, date, value as amount, transaction, '--' as depositor  from shares where mode = '".$save_acct."' and date > '".$start_date."' and date <= '".$end_date."' order by date asc");
	$x = 0;
	while ($acc_row = @mysql_fetch_array($acc_res))
	{
		$charge_amt = 0;
		$tot_shares = strtolower($acc_row['transaction']) == 'shares' ? intval($acc_row[amount]) : 0 ;
		$tot_savings = strtolower($acc_row['transaction']) == 'deposit' ? intval($acc_row[amount]) : 0 ;
		$tot_fees = strtolower($acc_row['transaction']) == 'monthly_charge' ? intval($acc_row[amount]) : 0 ;
		$tot_with = strtolower($acc_row['transaction']) == 'withdrawal' ? intval($acc_row[amount]) : 0 ;
		$tot_int = strtolower($acc_row['transaction']) == 'save_interest' ? intval($acc_row[amount]) : 0 ;
		$tot_pay = strtolower($acc_row['transaction']) == 'payment' ? intval($acc_row[amount]) : 0 ;
		$tot_inc = strtolower($acc_row['transaction']) == 'other_income' ? intval($acc_row[amount]) : 0 ;

		if(strtolower($acc_row['transaction']) == 'deposit'){
			$charge = mysql_fetch_array(mysql_query("select receipt_no, cheque_no, (flat_value + percent_value) as amount from deposit where id='".$acc_row['id']."'"));
			$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
			$descr="Deposit<br>RCPT: ".$charge['receipt_no'];
			$descr = ($charge['cheque_no'] <>"") ? $descr."<br>CHEQ: ".$charge['cheque_no'] : $descr;
		}
		if(strtolower($acc_row['transaction']) == 'withdrawal'){
			$charge = mysql_fetch_array(mysql_query("select voucher_no, cheque_no, flat_value + percent_value as amount from withdrawal where id='".$acc_row['id']."'"));
			$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
			$descr="Withdrawal<br>PV: ".$charge['voucher_no'];
			$descr = ($charge['cheque_no'] <>"") ? $descr."<br>CHEQ: ".$charge['cheque_no'] : $descr;
		}
		if(strtolower($acc_row['transaction']) == 'payment'){
	
			$pay = mysql_fetch_array(mysql_query("select receipt_no,  princ_amt + int_amt as amount from payment where id='".$acc_row['id']."'"));
			$pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;
			$descr="Loan Repayment<br>PV: ".$pay['receipt_no'];
			//$resp->alert($tot_pay);
		}

		if(strtolower($acc_row['transaction']) == 'other_income'){
	
			$inc = mysql_fetch_array(mysql_query("select i.receipt_no, i.cheque_no, i.amount, a.name from other_income i join accounts a on a.id = i.account_id where i.id='".$acc_row['id']."'"));
			$inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;
			$descr="DEDUCTION ($inc[name])<br>PV / CHEQ: ".$inc['receipt_no']. " ".$inc['cheque_no'];
			//$resp->alert($tot_pay);
		}
		if(strtolower($acc_row['transaction']) == 'shares'){
	
			$share = mysql_fetch_array(mysql_query("select s.receipt_no, s.value as amount from shares s where s.id='".$acc_row['id']."'"));
			$share_amt = ($share['amount'] != NULL) ? $share['amount'] : 0;
			$descr="TRANSFER TO SHARES <br>PV / CHEQ: ".$share['receipt_no'];
			//$resp->alert($tot_pay);
		}
		//$tot_fees = $tot_fees + $charge_amt;
		//$net_save = ($tot_savings + $tot_int) - ($tot_fees + $charge_amt + $tot_with);
		//$cumm_save += $net_save;
		if($tot_savings >0){
			$cumm_save += $tot_savings;
			$x++;
			//$color=($i % 2==0) ? "lightgrey" : "white";
			$content .= "
		   <tr>
			<td>$acc_row[date]</td><td>$acc_row[depositor]</td><td align=center>$descr</td><td align=center>--</td><td align=center>".number_format($tot_savings, 2)."</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($tot_int >0){
			$cumm_save += $tot_int;
			$x++;
			//$color=($i % 2==0) ? "lightgrey" : "white";
			$content .= "
		   <tr>
			<td>$acc_row[date]</td><td>$acc_row[depositor]</td><td align=center>Interest Earned</td><td align=center>--</td><td align=center>$tot_int</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($tot_shares >0){
			$cumm_save -= $tot_shares;
			$x++;
			//$color=($i % 2==0) ? "lightgrey" : "white";
			$content .= "
		   <tr>
			<td>$acc_row[date]</td><td>$acc_row[depositor]</td><td align=center>$descr</td><td align=center>".number_format($tot_shares, 2)."</td><td align=center>--</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($tot_with >0){
			$cumm_save -= $tot_with;
			$x++;
			//$color=($i % 2==0) ? "lightgrey" : "white";
			$content .= "
		   <tr>
			<td>$acc_row[date]</td><td>$acc_row[depositor]</td><td align=center>$descr</td><td align=center>".number_format($tot_with, 2)."</td><td align=center>--</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($tot_pay >0 || $tot_pay <0){
			$cumm_save -= $tot_pay;
			$x++;
			
			//$color=($i % 2==0) ? "lightgrey" : "white";
			$content .= "
		   <tr>
			<td>$acc_row[date]</td><td>$acc_row[depositor]</td><td align=center>$descr</td><td align=center>".number_format($tot_pay, 2)."</td><td align=center>--</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($charge_amt >0){
			$x++;
			$cumm_save -= $charge_amt;
			//$color=($i % 2==0) ? "lightgrey" : "white";
			$content .= "
		   <tr>
			<td>$acc_row[date]</td><td>$acc_row[depositor]</td><td align=center>Transactional Charge</td><td align=center>$charge_amt</td><td align=center>--</td><td align=center>$cumm_save</td>
		    </tr>
		    ";
		}
		if($tot_inc >0){
			$cumm_save -= $tot_inc;
			$x++;
			
			//$color=($i % 2==0) ? "lightgrey" : "white";
			$content .= "
		   <tr>
			<td>$acc_row[date]</td><td>$acc_row[depositor]</td><td align=center>$descr</td><td align=center>".number_format($tot_inc, 2)."</td><td align=center>--</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($tot_fees >0){
			$x++;
			$cumm_save -= $tot_fees;
			//$color=($i % 2==0) ? "lightgrey" : "white";
			$content .= "
		   <tr>
			<td>$acc_row[date]</td><td>$acc_row[depositor]</td><td align=center>Monthly Charge</td><td align=center>$tot_fees</td><td align=center>--</td><td align=center>$cumm_save</td>
		    </tr>
		    ";
		}
	}	$content .= "</tbody></table>";
	$resp->call("createTableJs");
	$resp->append("display_div", "innerHTML", $content);
	return $resp;
}


//LIST MEMBER LEDGER
function member_ledger($mem_id, $type, $from_date,$to_date){

	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
        $mems="";
        $mem_id2="";
	$resp = new xajaxResponse();
        $calc = new Date_Calc();

	$resp->assign("status", "innerHTML", "");
	$mem_res = @mysql_query("select id, first_name, last_name, mem_no from member order by first_name, last_name desc");
	while ($mem_row = mysql_fetch_array($mem_res))
	{
		$mems .= "<option value=$mem_row[id]>$mem_row[first_name] - $mem_row[last_name] - $mem_row[mem_no]  </option>";
	}
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);

	if($mem_id !=  ''){
		$choice = ($type == 'mem_id') ? " id='".$mem_id."'" : " mem_no ='".$mem_id."'";
		$former_res = mysql_query("select * from member where ".$choice."");
		$former = mysql_fetch_array($former_res);
		$mem_id2 = $former['id'];
		$mem_no2 = $former['mem_no'];
	}

	$mem_row = mysql_fetch_array(mysql_query("select first_name, last_name, mem_no, sign_name, photo_name from member where id = '".$mem_id2."'"));
	$branch = mysql_fetch_array(mysql_query("select b.* from member m join branch b on m.branch_id=b.branch_no where m.id='".$mem_id2."'"));
	
	
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR MEMBER STATEMENT</h3>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Select Member:</label>
                                                  
                
                                        <div class="input-group">
                                            <select name="memid" id="memid" class="form-control">'.$mems.'</select>
                                            <span class="input-group-btn">
                                                <button class="btn btn-info" type="button" onclick=\'xajax_member_ledger(document.getElementById("memid").value, "mem_id", document.getElementById("from_date").value,document.getElementById("to_date").value); return false;\'>Show Ledger</button>
                                            </span>
                                        </div>
                                    </div>
                                       
                                        
                                        <div class="col-sm-6">
                                            <label class="control-label">OR Enter Member No:</label>
                                                                                                      
                                        <div class="input-group">
                                             <input type=text id="mem_no" class="form-control">
                                            <span class="input-group-btn">
                                                <button class="btn btn-info" type="button" onclick=\'xajax_member_ledger(document.getElementById("mem_no").value, "mem_no", document.getElementById("from_date").value,document.getElementById("to_date").value); return false;\'>Show Ledger</button>
                                            </span>
                                        </div>
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
                                    </div>
                                </div> '; 
                                            		
	       $content .= '</div> </form>
                        <!--/ Form default layout -->
                    </div></div>';
                    
        $resp->call("createDate","from_date");
	$resp->call("createDate","to_date");	
	
	//$resp->assign("display_div", "innerHTML", $content);
	
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_year);
	if($mem_id == ''){
		$cont = "<font color=red>Please Select The Period and The Member</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}elseif($mem_id !=  ''){
		$choice = ($type == 'mem_id') ? " id='".$mem_id."'" : " mem_no ='".$mem_id."'";
		$former_res = mysql_query("select * from member where ".$choice."");
		if(mysql_numrows($former_res) ==0){
			$resp->alert("No member found!");
			return $resp;
		}
		$former = mysql_fetch_array($former_res);
		$mem_id = $former['id'];
		$mem_no = $former['mem_no'];
	//}
			   
	$content .= "<div class='panel panel-default' id='demo'>
                            <div class='panel-heading'>
                        <center><p><h3 class='semibold text-primary mt0 mb5'><B>".$branch['branch_name']."</B></h3></p>
		  <h4 class='semibold text-primary mt0 mb5'>MEMBER STATEMENT</h4></center>
		   
			<p><h5 class='semibold text-primary mt0 mb5'><B>Name:</B></h5><b>".strtoupper($mem_row['first_name'])." ". strtoupper($mem_row['last_name'])."</b></p><p><h5 class='semibold text-primary mt0 mb5'><B>Member Number:</B></h5><b>".$mem_row['mem_no']."</b></p><p><h5 class='semibold text-primary mt0 mb5'><B>Period:</B></h5><b>".$from_date  ."  -  ". $to_date."</b></p></div>";
			
		   $content .= "<table class='table table-striped table-bordered' id='table-tools'>
		   <tr><td><img src='photos/".$mem_row['photo_name']."?dummy=".time()."' width=90 height=90><br>Photo</td><td align=right><img src='signs/".$mem_row['sign_name']."?dummy=".time()."' width=90 height=90><br>Signature</td></tr></table><br>
			<a href='export/member_ledger?mem_id=".$mem_id."&type=".$type."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."' target=blank()><b>Printable Version</b></a> | <a href='export/member_ledger?mem_id=".$mem_id."&type=".$type."&from_year=".$from_year."&from_month=".$from_month."&to_year=".$to_year."&to_month=".$to_month."&format=excel' target=blank()><b>Export Excel</b></a></div>";
			
	//$resp->append("display_div", "innerHTML", $content);

	//CALCULATE BALANCE 
	$direct = mysql_query("select sum(shares) as shares, sum(value) as value from shares where mem_id = $mem_id and receipt_no != '' and date <= '".$from_date."'");
	$inward = mysql_query("select sum(shares) as shares, sum(value) as value from share_transfer where to_id = $mem_id and date <= '".$from_date."'");
	$outward = @mysql_query("select sum(shares) as shares, sum(value) as value from share_transfer where from_id = $mem_id and date <= '".$from_date."'");
	$div_res = mysql_query("select sum(d.amount) as amount from dividends d join share_dividends s on d.share_dividend_id=s.id where d.mem_id=$mem_id and s.bank_account=0 and s.date <= '".$from_date."'");
	
	$direct = mysql_fetch_array($direct);
	$inward = mysql_fetch_array($inward);
	$outward = mysql_fetch_array($outward);
	$div = mysql_fetch_array($div_res);

	$direct_amt = ($direct['value'] == NULL) ? 0 : $direct['value'];
	$inward_amt = ($inward['value'] == NULL) ? 0 : $inward['value'];
	$outward_amt = ($outward['value'] == NULL) ? 0 : $outward['value'];
	$direct_no = ($direct['shares'] == NULL) ? 0 : $direct['shares'];
	$inward_no = ($inward['shares'] == NULL) ? 0 : $inward['shares'];
	$outward_no = ($outward['shares'] == NULL) ? 0 : $outward['shares'];
	$div_amt = ($div['amount'] == NULL) ? 0 : $div['amount'];

	$balance = $direct_amt + $inward_amt - $outward_amt + $div_amt;
	$tot_mem_shares = $direct_no + $inward_no - $outward_no; 


	$direct = @mysql_query("select id, date, shares, value from shares where mem_id = $mem_id and receipt_no != '' and date >'$from_date' and date <= '$to_date' order by date asc");
	$inward = @mysql_query("select id, date, shares, value from share_transfer where to_id = $mem_id and date >'$from_date' and date <= '$to_date' order by date asc");
	$outward = @mysql_query("select id, date, shares, value from share_transfer where from_id = $mem_id and date >'$from_date' and date <= '$to_date' order by date asc");
	$div_res = mysql_query("select d.id,  s.date, d.amount from dividends d join share_dividends s on d.share_dividend_id=s.id where d.mem_id=$mem_id and s.bank_account=0 and s.date >'$from_date' and s.date <= '$to_date' order by s.date asc");
	$mem = @mysql_fetch_array(@mysql_query("select first_name, last_name from member where id = $mem_id"));
	
	$found_shares = 0;
	$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">SHARES</h3>                          
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
 				
	$content .= "<thead><th>Date</th><th>Type of Transaction</th><th>No. of Shares</th><th>Value</th><th>Total Shares</th><th>Dividends</th><th>Balance</th></thead>";
	$content .= "<tbody><tr>
				    <td>".$from_date."</td><td>Balance Brought Forward</td><td align='center'>--</td><td align='center'>--</td><td align='center'>".number_format($tot_mem_shares, 2)."</td><td>--</td><td>".number_format($balance, 2)."</td>
				    </tr>
				    ";
	if (@mysql_num_rows($direct) > 0 || $balance >0)
	{
		$found_shares += 1; $i = 0;
		while ($drow = mysql_fetch_array($direct))
		{
			$balance += $drow[value];
			$tot_mem_shares += $drow[shares];
			//$color=($i % 2==0) ? "lightgrey" : "white";
			$content .= "<tr>
				    <td>".$drow['date']."</td><td>Direct Purchase</td><td align='center'>".$drow['shares']."</td><td align='center'>".$drow['value']."</td><td align='center'>".number_format($tot_mem_shares, 2)."</td><td>--</td><td>".number_format($balance, 2)."</td>
				    </tr>
				    ";
					$i++;
		}
	}
	if (mysql_num_rows($inward) > 0)
	{
		$found_shares += 1;
		$i = 0;
		while ($inrow = mysql_fetch_array($inward))
		{
			$balance += $inrow['value'];
			$tot_mem_shares += $inrow['shares'];
			//$color=($i % 2==0) ? "lightgrey" : "white";
			$content .= "<tr>
				    <td>".$inrow['date']."</td><td>Inward transfer</td><td align='center'>".$inrow['shares']."</td><td align='center'>".$inrow['value']."</td><td align='center'>".number_format($tot_mem_shares, 2)."</td><td>--</td><td>".number_format($balance, 2)."</td>
				    </tr>
				    ";
					$i++;
		}
	}
	if (mysql_num_rows($outward) > 0)
	{
		$found_shares += 1;
		$i=0;
		while ($outrow = mysql_fetch_array($outward))
		{
			$balance -= $outrow['value'];
			$tot_mem_shares -= $outrow['shares'];
			//$color=($i % 2==0) ? "lightgrey" : "white";
			$content .= "<tr>
				    <td>".$outrow['date']."</td><td>Outward transfer</td><td align='center'>".number_format($outrow['shares'], 2)."</td><td align='center'>".number_format($outrow['value'], 2)."</td><td align='center'>".number_format($tot_mem_shares, 2)."</td><td>--</td><td>".number_format($balance, 2)."</td>
				    </tr>
			            ";
					$i++;
		}
	}
	if (@mysql_num_rows($div_res) > 0)
	{
		$found_shares += 1;
		$i=0;
		while ($div = @mysql_fetch_array($div_res))
		{
			$balance += $div['amount'];
			//$tot_mem_shares -= intval($outrow[shares]);
			//$color=($i % 2==0) ? "lightgrey" : "white";
			$content .= "<tr>
				    <td>".$div['date']."</td><td>Dividends</td><td align='center'>--</td><td align='center'>--</td><td align='center'>".number_format($tot_mem_shares, 2)."</td><td>".number_format($div['amount'], 2)."</td><td>".number_format($balance, 2)."</td>
				    </tr>
			            ";
					$i++;
		}
	}
	$content .= "</tbody></table></div>";
	
        //start savings ledger
	$acct_res = mysql_query("select ma.id as mem_acct_id, ac.name, ac.account_no from mem_accounts ma join savings_product sp on ma.saveproduct_id = sp.id join accounts ac on sp.account_id = ac.id where ma.mem_id = '".$mem_id."' and sp.type='free'");
	//$content .= mysql_numrows($acct_res);
	//while($acct = mysql_fetch_array($acct_res)){
	/*$acct = mysql_fetch_array($acct_res);
		$resp->call('xajax_memsavings_ledger', $mem_no, $mem_id, $acct['mem_acct_id'], $from_date, $to_date);
	
	//if($mem_no <>"" && $save_acct==''){
		$sth = mysql_query("select * from member where mem_no='".$mem_no."'");
		if(mysql_numrows($sth) ==0){
			$resp->alert("The entered Member No does not exist!");
			return $resp;
		}
		$row = mysql_fetch_array($sth);
		$acct_res = mysql_query("select mem.id as id from mem_accounts mem join savings_product s on mem.saveproduct_id=s.id where mem.mem_id='".$row['id']."' and s.type='free'");
		if(mysql_numrows($acct_res) > 1){
			$resp->call('xajax_memsavings_ledger_form', $row['id']);
			return $resp;
		}else */
		
		if(mysql_numrows($acct_res) ==1){
			$acct = mysql_fetch_array($acct_res);
			$save_acct = $acct['mem_acct_id'];
			//$mem_id = $row['id'];
			
		 $start_date = sprintf("%d-%02d", $from_year, $from_month) . "-01";
	$end_date = date('Y-m-d', strtotime($calc->endOfMonthBySpan(0, intval($to_month), intval($to_year), '%Y-%m-%d')));
	$start_month = intval($from_month); $end_month = intval($to_month);
	$start_year = intval($from_year); $end_year = intval($to_year);
	$total_saved = 0; $total_with = 0; $total_int = 0; $total_fees = 0;
	$cumm_save = 0;
	
	$drow1 = @mysql_fetch_array(@mysql_query("select sum(amount - flat_value - percent_value) as tot_savings from deposit where bank_account != 0 and memaccount_id = $save_acct and date <= '".$start_date."'"));
	$wrow1 = @mysql_fetch_array(@mysql_query("select sum(amount + flat_value + percent_value) as tot_with from withdrawal where memaccount_id = $save_acct and date <= '".$start_date."'"));
	$mrow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_fees from monthly_charge where memaccount_id = $save_acct and date <= '".$start_date."'"));
	$irow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_int from save_interest where memaccount_id = $save_acct and date <= '".$start_date."'"));
	$prow1 = @mysql_fetch_array(@mysql_query("select sum(princ_amt + int_amt) as tot_int from payment where mode = '$save_acct' and date <= '".$start_date."'"));
	$incow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_inc from other_income where mode = '$save_acct' and date <= '".$start_date."'"));

        $total_saved = isset($drow1['tot_savings'])? intval($drow1['tot_savings']) : 0 ;
        $total_fees = isset($mrow1['tot_fees'])? intval($mrow1['tot_fees']) : 0 ;
        $total_with = isset($wrow1['tot_with'])? intval($wrow1['tot_with']) : 0 ;
        $total_int = isset($irow1['tot_int'])? intval($irow1['tot_int']) : 0 ;
		$total_pay = isset($prow1['tot_int'])? intval($prow1['tot_int']) : 0 ;
		$total_inc = isset($incow1['tot_inc'])? intval($incow1['tot_inc']) : 0 ;
        $net_save = ($total_saved + $total_int) - ($total_fees + $total_with + $total_pay + $total_inc);
	$cumm_save += $net_save;
	$mem_row = @mysql_fetch_array(@mysql_query("select first_name, last_name, mem_no, sign_name, photo_name from member where id = $mem_id"));
	$branch = mysql_fetch_array(mysql_query("select * from branch"));
	
	$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                        <p><h5 class="semibold text-primary mt0 mb5">INTERIM SAVINGS STATEMENT</p>                          
                            </div>';
        $content .= "<table class='table table-striped table-bordered' id='table-tools'>";                    
                            
 		$content .= "<thead>
			<th>Date</th><th>Description</th><th>Debit</th><th>Credit</th><th>Account Balance</th></thead><tbody>";
		   $content .= "<tr>		    
			<td>Before".$start_date."</td><td align=center>B/F</td><td align=center>--</td><td align=center>--</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
	$acc_res = @mysql_query("select id, date, amount, transaction from deposit where bank_account != 0 and memaccount_id = '".$save_acct."' and date > '".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction from withdrawal where memaccount_id = $save_acct and date > '".$start_date."' and date <= '".$end_date."' UNION select id, date, amount, transaction from monthly_charge where memaccount_id = $save_acct and date >'".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction from save_interest where memaccount_id = $save_acct and date > '".$start_date."' and date <='".$end_date."' UNION select id, date, princ_amt + int_amt as amount, transaction from payment where mode= '".$save_acct."' and date > '".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction from other_income where mode = '".$save_acct."' and date > '".$start_date."' and date <= '".$end_date."' order by date asc");
	$x = 0;
	while ($acc_row = @mysql_fetch_array($acc_res))
	{
		$charge_amt = 0;
		$tot_savings = strtolower($acc_row['transaction']) == 'deposit' ? intval($acc_row['amount']) : 0 ;
		$tot_fees = strtolower($acc_row['transaction']) == 'monthly_charge' ? intval($acc_row['amount']) : 0 ;
		$tot_with = strtolower($acc_row['transaction']) == 'withdrawal' ? intval($acc_row['amount']) : 0 ;
		$tot_int = strtolower($acc_row['transaction']) == 'save_interest' ? intval($acc_row['amount']) : 0 ;
		$tot_pay = strtolower($acc_row['transaction']) == 'payment' ? intval($acc_row['amount']) : 0 ;
		$tot_inc = strtolower($acc_row['transaction']) == 'other_income' ? intval($acc_row['amount']) : 0 ;

		if(strtolower($acc_row['transaction']) == 'deposit'){
			$charge = mysql_fetch_array(mysql_query("select receipt_no, cheque_no, (flat_value + percent_value) as amount from deposit where id='".$acc_row['id']."'"));
			$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
			$descr="Deposit<br>RCPT: ".$charge['receipt_no'];
			$descr = ($charge['cheque_no'] <>"") ? $descr."<br>CHEQ: ".$charge['cheque_no'] : $descr;
		}
		if(strtolower($acc_row['transaction']) == 'withdrawal'){
			$charge = mysql_fetch_array(mysql_query("select voucher_no, cheque_no, flat_value + percent_value as amount from withdrawal where id='".$acc_row['id']."'"));
			$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
			$descr="Withdrawal<br>PV: ".$charge['voucher_no'];
			$descr = ($charge['cheque_no'] <>"") ? $descr."<br>CHEQ: ".$charge['cheque_no'] : $descr;
		}
		if(strtolower($acc_row['transaction']) == 'payment'){
	
			$pay = mysql_fetch_array(mysql_query("select receipt_no,  princ_amt + int_amt as amount from payment where id='".$acc_row['id']."'"));
			$pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;
			$descr="Loan Repayment<br>PV: ".$pay['receipt_no'];
			//$resp->alert($tot_pay);
		}

		if(strtolower($acc_row['transaction']) == 'other_income'){
	
			$inc = mysql_fetch_array(mysql_query("select i.receipt_no, i.cheque_no, i.amount, a.name from other_income i join accounts a on a.id = i.account_id where i.id='".$acc_row['id']."'"));
			$inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;
			$descr="Deduction ($inc[name])<br>PV / CHEQ No.: ".$inc['receipt_no']. " ".$inc['cheque_no'];
			//$resp->alert($tot_pay);
		}
		//$tot_fees = $tot_fees + $charge_amt;
		//$net_save = ($tot_savings + $tot_int) - ($tot_fees + $charge_amt + $tot_with);
		//$cumm_save += $net_save;
		if($tot_savings >0){
			$cumm_save += $tot_savings;
			$x++;
			//$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr>
			<td>".$acc_row['date']."</td><td align=center>".$descr."</td><td align=center>--</td><td align=center>".number_format($tot_savings, 2)."</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($tot_int >0){
			$cumm_save += $tot_int;
			$x++;
			//$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr>
			<td>".$acc_row['date']."</td><td align=center>Interest Earned</td><td align=center>--</td><td align=center>".$tot_int."</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($tot_with >0){
			$cumm_save -= $tot_with;
			$x++;
			//$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr>
			<td>".$acc_row['date']."</td><td align=center>".$descr."</td><td align=center>".number_format($tot_with, 2)."</td><td align=center>--</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($tot_pay >0){
			$cumm_save -= $tot_pay;
			$x++;
			
			//$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr>
			<td>".$acc_row['date']."</td><td align=center>".$descr."</td><td align=center>".number_format($tot_pay, 2)."</td><td align=center>--</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($charge_amt >0){
			$x++;
			$cumm_save -= $charge_amt;
			//$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr>
			<td>".$acc_row['date']."</td><td align=center>Transactional Charge</td><td align=center>".$charge_amt>"</td><td align=center>--</td><td align=center>".$cumm_save."</td>
		    </tr>
		    ";
		}
		if($tot_inc >0){
			$cumm_save -= $tot_inc;
			$x++;
			
			//$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr>
			<td>".$acc_row['date']."</td><td align=center>".$descr."</td><td align=center>".number_format($tot_inc, 2)."</td><td align=center>--</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($tot_fees >0){
			$x++;
			$cumm_save -= $tot_fees;
			//$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr>
			<td>".$acc_row['date']."</td><td align=center>Monthly Charge</td><td align=center>".$tot_fees."</td><td align=center>--</td><td align=center>".$cumm_save."</td>
		    </tr>
		    ";
		}
	} 	
	$content .= "</tbody></table></div>";
		}else
		      $content .="<font color='red'>This Member Has No Savings Account!</font><br>";
		      
			//return $resp;
		
	//}
	
	//}//end savings ledger
	
	//start loans ledger
	/*$over_res = mysql_query("select * from member where mem_no='".$mem_no."'");
		if(mysql_numrows($over_res) == 0){
			$cont = "<font color=red>MemberNo does not exist! \nPlease enter correct Member No!</font>";
			//$resp->alert("MemberNo does not exist! \nPlease enter correct Member No");
			//$resp->assign("status", "innerHTML", $cont);
			//return $resp;
		}
		$over=mysql_fetch_array($over_res);
		$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
		$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_year);
		*/
		
		//START BALANCE
		$bal_res = mysql_query("select  sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."'  and d.date < '".$from_date."'"); 
		$bal = mysql_fetch_array($bal_res);
		$pay_res = mysql_query("select sum(p.princ_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where p.date < '".$from_date."' and applic.mem_id='".$over['id']."'");
		$pay = mysql_fetch_array($pay_res);
		$bal_amt = ($bal['amount'] == NULL) ? 0 : $bal['amount'];
		$pay_amt = ($pay['amount'] == NULL) ? 0 : $pay['amount'];
		$start_bal = $bal_amt - $pay_amt;

		$loan_res = mysql_query("select d.date as date, d.amount as amount from disbursed d join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' d.date >= '".$from_date."' and d.date <= '".$to_date."' and applic.mem_id='".$over['id']."'"); 

		$paid_res = mysql_query("select p.date as date, p.princ_amt as amount, p.int_amt as int_amt, p.receipt_no as receipt_no from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and p.date >= '".$from_date."' and p.date <= '".$to_date."' and applic.mem_id='".$over['id']."' order by p.date asc");

		$pen_res = mysql_query("select pen.amount as amount, pen.date as date, pen.status as status from penalty pen join disbursed d on pen.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' pen.date >= '".$from_date."' and pen.date <= '".$to_date."' and applic.mem_id='".$over['id']."'");
		
		$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <p><h5 class="semibold text-primary mt0 mb5">LOAN LEDGER</h5></p>
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
		
		$content .= "<thead><th><b>Date</b></th><th><b>Description of Transaction</b></th><th><b>Interest</b></th><th><b>Debit</b></th><th><b>Credit</b></th><th><b>Balance</b></th></thead><tbody>
		<tr><td>Before ".$from_date."</td><td>Start Balance</td><td>--</td><td>--</td><td>--</td><td>".number_format($start_bal, 2)."</td></tr>";
		$last_date = $from_date;
		$balance = $start_bal;
		//if(mysql_numrows($paid_res) >0){
			$i=1;
			while($paid = mysql_fetch_array($paid_res)){
				$loan_res = mysql_query("select d.date as date, d.amount as amount from disbursed d join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and d.date >= '".$last_date."' and d.date < '".$paid['date']."' and applic.mem_id='".$over['id']."' order by d.date asc");
				while($loan = mysql_fetch_array($loan_res)){
					$pen_res = mysql_query("select pen.amount as amount, pen.date as date, pen.status as status from penalty pen join disbursed d on pen.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and pen.date >= '".$last_date."' and pen.date < '".$loan['date']."' and applic.mem_id='".$over['id']."' order by pen.date asc");
					while($pen = mysql_fetch_array($pen_res)){
						$color=($i%2 == 0) ? "lightgrey" : "white";
						if($pen['status'] == 'pending'){
							$balance = $balance + $pen['amount'];
						
							$content .= "<tr><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
						}else
							$content .= "<tr><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>".$pen['amount']."</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
						$last_date = $pen['date'];
						$i++;
					}
					$balance = $balance + $loan['amount'];
					//$color=($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr><td>".$loan['date']."</td><td>Loan Disbursed</td><td>--</td><td>--</td><td>".number_format($loan['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
					$last_date = $loan['date'];
					$i++;
				}
				$pen_res = mysql_query("select pen.amount as amount, pen.date as date, pen.status as status from penalty pen join disbursed d on pen.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and pen.date >= '".$last_date."' and pen.date < '".$paid['date']."' order by pen.date asc");
				while($pen = mysql_fetch_array($pen_res)){
					//$color=($i%2 == 0) ? "lightgrey" : "white";
					if($pen['status'] == 'pending'){
						$balance = $balance + $pen['amount'];
						$content .= "<tr><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
					}else
						$content .= "<tr><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
						$i++;
				}
				$balance = $balance - $paid['amount'];
				//$color=($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr><td>".$paid['date']."</td><td>Repayment<br>RCPT: ".$paid['receipt_no']."</td><td>--</td><td>".number_format($paid['amount'], 2)."</td><td>--</td><td>".number_format($balance, 2)."</td></tr>";
				$last_date = $paid['date'];
				$i++;
				//INTEREST
				if($paid['int_amt'] != 0){
					//$color=($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr><td>".$paid['date']."</td><td>Interest Paid<br>RCPT: ".$paid['receipt_no']."</td><td>".number_format($paid['int_amt'], 2)."</td><td>--</td><td>--</td><td>".number_format($balance, 2)."</td></tr>";
				//$last_date = $paid['date'];
					$i++;
				}
			}
			$loan_res = mysql_query("select d.date as date, d.amount as amount from disbursed d join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and d.date > '".$last_date."' and d.date < '".$to_date."' and applic.mem_id='".$over['id']."' order by d.date asc");
			while($loan = mysql_fetch_array($loan_res)){
				$pen_res = mysql_query("select pen.amount as amount, pen.date as date, pen.status as status from penalty pen join disbursed d on pen.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and pen.date >= '".$last_date."' and pen.date < '".$loan['date']."' and applic.mem_id='".$over['id']."' order by pen.date asc");
				while($pen = mysql_fetch_array($pen_res)){
					//$color=($i%2 == 0) ? "lightgrey" : "white";
					if($pen['status'] == 'pending'){
						$balance = $balance + $pen['amount'];
						$content .= "<tr><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
					}else
						$content .= "<tr><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
					$last_date = $pen['date'];
					$i++;
				}
				$balance = $balance + $loan['amount'];
				//$color=($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr><td>".$loan['date']."</td><td>Loan Disbursed</td><td>--</td><td>--</td><td>".number_format($loan['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
				$last_date = $loan['date'];
				$i++;
			}
			$pen_res = mysql_query("select pen.amount as amount, pen.date as date, pen.status as status from penalty pen join disbursed d on pen.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and pen.date >= '".$last_date."' and pen.date <= '".$to_date."' and applic.mem_id='".$over['id']."' order by pen.date asc");
			while($pen = mysql_fetch_array($pen_res)){
				if($pen['status'] == 'pending'){
					//$color=($i%2 == 0) ? "lightgrey" : "white";
					$balance = $balance + $pen['amount'];
					$content .= "<tr><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
				}else
					$content .= "<tr><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
				$last_date = $pen['date'];
				$i++;
			}
	//end loans ledger
	}
	//if ($found_shares < 1)
	//	$content .= "<font color='red'>No shares activity yet registered for $mem[first_name] $mem[last_name]</font><br>";
	
	//$resp->call('xajax_memloan_ledger', $mem_no, $from_date, $to_date);
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

////LOAN LEDGER
function memloan_ledger($mem_no, $from_date,$to_date){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	
	
	if($from_date =='')
		$cont = "<font color=red>Please select options of the ledger</font>";
	elseif($mem_no==''){
		$cont = "<tr><td><font color=red>Please enter the Member No or select the member!</font>";
		//$resp->alert("Please enter the Member No");

	}else{
		$over_res = mysql_query("select * from member where mem_no='".$mem_no."'");
		if(mysql_numrows($over_res) == 0){
			$cont = "<font color=red>MemberNo does not exist! \nPlease enter correct Member No!</font>";
			//$resp->alert("MemberNo does not exist! \nPlease enter correct Member No");
			//$resp->assign("status", "innerHTML", $cont);
			//return $resp;
		}
		$over=mysql_fetch_array($over_res);
		$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
		$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_year);
		
		
		//START BALANCE
		$bal_res = mysql_query("select  sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."'  and d.date < '".$from_date."'"); 
		$bal = mysql_fetch_array($bal_res);
		$pay_res = mysql_query("select sum(p.princ_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where p.date < '".$from_date."' and applic.mem_id='".$over['id']."'");
		$pay = mysql_fetch_array($pay_res);
		$bal_amt = ($bal['amount'] == NULL) ? 0 : $bal['amount'];
		$pay_amt = ($pay['amount'] == NULL) ? 0 : $pay['amount'];
		$start_bal = $bal_amt - $pay_amt;

		$loan_res = mysql_query("select d.date as date, d.amount as amount from disbursed d join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' d.date >= '".$from_date."' and d.date <= '".$to_date."' and applic.mem_id='".$over['id']."'"); 

		$paid_res = mysql_query("select p.date as date, p.princ_amt as amount, p.int_amt as int_amt, p.receipt_no as receipt_no from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and p.date >= '".$from_date."' and p.date <= '".$to_date."' and applic.mem_id='".$over['id']."' order by p.date asc");

		$pen_res = mysql_query("select pen.amount as amount, pen.date as date, pen.status as status from penalty pen join disbursed d on pen.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' pen.date >= '".$from_date."' and pen.date <= '".$to_date."' and applic.mem_id='".$over['id']."'");
		
		$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">LOAN LEDGER</h3>
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
		
		$content .= "<thead><th><b>Date</b></th><th><b>Description of Transaction</b></th><th><b>Interest</b></th><th><b>Debit</b></th><th><b>Credit</b></th><th><b>Balance</b></th></thead><tbody>
		<tr><td>Before ".$from_date."</td><td>Start Balance</td><td>--</td><td>--</td><td>--</td><td>".number_format($start_bal, 2)."</td></tr>";
		$last_date = $from_date;
		$balance = $start_bal;
		//if(mysql_numrows($paid_res) >0){
			$i=1;
			while($paid = mysql_fetch_array($paid_res)){
				$loan_res = mysql_query("select d.date as date, d.amount as amount from disbursed d join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and d.date >= '".$last_date."' and d.date < '".$paid['date']."' and applic.mem_id='".$over['id']."' order by d.date asc");
				while($loan = mysql_fetch_array($loan_res)){
					$pen_res = mysql_query("select pen.amount as amount, pen.date as date, pen.status as status from penalty pen join disbursed d on pen.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and pen.date >= '".$last_date."' and pen.date < '".$loan['date']."' and applic.mem_id='".$over['id']."' order by pen.date asc");
					while($pen = mysql_fetch_array($pen_res)){
						$color=($i%2 == 0) ? "lightgrey" : "white";
						if($pen['status'] == 'pending'){
							$balance = $balance + $pen['amount'];
						
							$content .= "<tr><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
						}else
							$content .= "<tr><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>".$pen['amount']."</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
						$last_date = $pen['date'];
						$i++;
					}
					$balance = $balance + $loan['amount'];
					//$color=($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr><td>".$loan['date']."</td><td>Loan Disbursed</td><td>--</td><td>--</td><td>".number_format($loan['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
					$last_date = $loan['date'];
					$i++;
				}
				$pen_res = mysql_query("select pen.amount as amount, pen.date as date, pen.status as status from penalty pen join disbursed d on pen.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and pen.date >= '".$last_date."' and pen.date < '".$paid['date']."' order by pen.date asc");
				while($pen = mysql_fetch_array($pen_res)){
					//$color=($i%2 == 0) ? "lightgrey" : "white";
					if($pen['status'] == 'pending'){
						$balance = $balance + $pen['amount'];
						$content .= "<tr><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
					}else
						$content .= "<tr><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
						$i++;
				}
				$balance = $balance - $paid['amount'];
				//$color=($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr><td>".$paid['date']."</td><td>Repayment<br>RCPT: ".$paid['receipt_no']."</td><td>--</td><td>".number_format($paid['amount'], 2)."</td><td>--</td><td>".number_format($balance, 2)."</td></tr>";
				$last_date = $paid['date'];
				$i++;
				//INTEREST
				if($paid['int_amt'] != 0){
					//$color=($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr><td>".$paid['date']."</td><td>Interest Paid<br>RCPT: ".$paid['receipt_no']."</td><td>".number_format($paid['int_amt'], 2)."</td><td>--</td><td>--</td><td>".number_format($balance, 2)."</td></tr>";
				//$last_date = $paid['date'];
					$i++;
				}
			}
			$loan_res = mysql_query("select d.date as date, d.amount as amount from disbursed d join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and d.date > '".$last_date."' and d.date < '".$to_date."' and applic.mem_id='".$over['id']."' order by d.date asc");
			while($loan = mysql_fetch_array($loan_res)){
				$pen_res = mysql_query("select pen.amount as amount, pen.date as date, pen.status as status from penalty pen join disbursed d on pen.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and pen.date >= '".$last_date."' and pen.date < '".$loan['date']."' and applic.mem_id='".$over['id']."' order by pen.date asc");
				while($pen = mysql_fetch_array($pen_res)){
					$color=($i%2 == 0) ? "lightgrey" : "white";
					if($pen['status'] == 'pending'){
						$balance = $balance + $pen['amount'];
						$content .= "<tr><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
					}else
						$content .= "<tr><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
					$last_date = $pen['date'];
					$i++;
				}
				$balance = $balance + $loan['amount'];
				//$color=($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr><td>".$loan['date']."</td><td>Loan Disbursed</td><td>--</td><td>--</td><td>".number_format($loan['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
				$last_date = $loan['date'];
				$i++;
			}
			$pen_res = mysql_query("select pen.amount as amount, pen.date as date, pen.status as status from penalty pen join disbursed d on pen.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and pen.date >= '".$last_date."' and pen.date <= '".$to_date."' and applic.mem_id='".$over['id']."' order by pen.date asc");
			while($pen = mysql_fetch_array($pen_res)){
				if($pen['status'] == 'pending'){
					$color=($i%2 == 0) ? "lightgrey" : "white";
					$balance = $balance + $pen['amount'];
					$content .= "<tr><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
				}else
					$content .= "<tr><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
				$last_date = $pen['date'];
				$i++;
			}
	}
	$content .= "</tbody></table>";
	$resp->call("createTableJs");
	$resp->append("display_div", "innerHTML", $content);
	return $resp;
}


//LIST CUSTOMERS
function membersList($type, $mem_no, $mem_name, $start, $branch_id)
{       $content ="";
        $max_page=0;

	$resp = new xajaxResponse();
	$branch = ($branch_id=='all'||$branch_id=='')?NULL:"and branch_id=".$branch_id;
	$mem_no1 = ($mem_no == 'All') ? "" : $mem_no;
	$mem_name1 = ($mem_name == 'All') ? "" : $mem_name;
	$resp->assign("status", "innerHTML", "");
	if (strtolower($type) == 'all'){
		$mem = @mysql_query("select id, mem_no, first_name, last_name, sex, (DATEDIFF(NOW(), dob)/365) as age, kin, kin_telno, address, status,  email, telno from member  where (first_name like '%".$mem_name1."%' or last_name  like '%".$mem_name1."%') and mem_no like '%".$mem_no1."%' ".$branch." order by first_name, last_name asc");
		$memb = @mysql_query("select id, mem_no, first_name, last_name, sex, (DATEDIFF(NOW(), dob)/365) as age, kin, kin_telno, address, status,  email, telno from member  where (first_name like '%".$mem_name1."%' or last_name  like '%".$mem_name1."%') and mem_no like '%".$mem_no1."%' ".$branch." order by first_name, last_name asc ");
		$head = "CUSTOMERS LIST";
	}elseif (strtolower($type) == 'members'){
		$mem = @mysql_query("select id, mem_no, first_name, last_name, sex, (DATEDIFF(NOW(), dob)/365) as age, kin, kin_telno, address, status,  email, telno  from member where id in (select mem_id from shares) and (first_name like '%".$mem_name1."%' or last_name  like '%".$mem_name1."%') and mem_no like '%".$mem_no1."%'  ".$branch." order by first_name, last_name asc");
		$memb = @mysql_query("select id, mem_no, first_name, last_name, sex, (DATEDIFF(NOW(), dob)/365) as age, kin, kin_telno, address, status,  email, telno  from member where id in (select mem_id from shares) and (first_name like '%".$mem_name1."%' or last_name  like '%".$mem_name1."%') and mem_no like '%".$mem_no1."%'  ".$branch." order by first_name, last_name asc ");
		$head = "MEMBERS LIST";
	}elseif (strtolower($type) == 'non_members'){
		$mem = @mysql_query("select id, mem_no, first_name, last_name, sex, (DATEDIFF(NOW(), dob)/365) as age, kin, kin_telno, address, status,  email, telno from member where id NOT in (select mem_id from shares) and (first_name like '%".$mem_name1."%' or last_name  like '%".$mem_name1."%') and mem_no like '%".$mem_no1."%'  ".$branch." order by first_name, last_name asc");
		$memb = @mysql_query("select id, mem_no, first_name, last_name, sex, (DATEDIFF(NOW(), dob)/365) as age, kin, kin_telno, address, status,  email, telno from member where id NOT in (select mem_id from shares) and (first_name like '%".$mem_name1."%' or last_name  like '%".$mem_name1."%') and mem_no like '%".$mem_no1."%'  ".$branch." order by first_name, last_name asc ");
		$head = "NON-MEMBERS LIST";
	}
	
	
	$content .= '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title"><b>SEARCH FOR&nbsp;'.$head.'</b></h3>
                                <p class="text-default nm"></p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Customer Name:</label>
                                            <input type="text" value="'.$mem_name.'" id="mem_name" class="form-control">                                            
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Member No:</label>
                                            <input type=text value="'.$mem_no.'" id="mem_no" class="form-control">
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
                                </div>';
		
		 $content .= "<div class='panel-footer'>                              
                                
                                <button type='button' class='btn  btn-primary'  onclick=\"xajax_membersList('".$type."', document.getElementById('mem_no').value, document.getElementById('mem_name').value, '1',document.getElementById('branch_id').value)\">Search</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    
                //$resp->assign("display_div", "innerHTML", $content);	
	if($start==1){
	 
	if (mysql_num_rows($mem) >0)
	
	{      
	 $content .="<a href='export/customers?type=".$type."&mem_no=".$mem_no."&mem_name=".$mem_name."&branch_id=".$branch_id."&stat=".$stat."' target=blank()><b>Printable Version</b></a> | <a href='export/customers?type=".$type."&mem_no=".$mem_no."&mem_name=".$mem_name."&branch_id=".$branch_id."&stat=".$stat."&format=excel' target=blank()><b>Export Excel</b></a>";
				
					////$max_page = ceil(mysql_num_rows($mem)/$num_rows);
		 $content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <p><h4 class="text-primary mt0">ALL&nbsp;'.$head.'</h4></p>
                                                          
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
		$content .= "		    	 
			    <thead>
					<th>No</th>
			       <th>Name</th>
					<th>Member No.</th>
					<th>Age</th>
					<th>Sex</th>
			       <th>Email</th>
			       <th>Phone</th>
			       <th>Physical Address</th>
					 <th>Kin</th>
			       <th>Kin Telno</th> 
			    </thead><tbody>
		    	    ";
		$i=$stat+1;
		while ($members = @mysql_fetch_array($memb))
		{
			////$color=($i % 2==0) ? "lightgrey" : "white";
			$content .= "
				   <tr>
						<td>".$i."</td>
						<td>".$members['first_name']."". $members['last_name']."</td>
						 <td>".$members['mem_no']."</td>
						<td>".floor($members['age'])."</td>
						<td>".$members['sex']."</td>
				       <td>".$members['email']."</td>
				       <td>".$members['telno']."</td>
				       <td>".$members['address']."</td>
						<td>".$members['kin']."</td>
				       <td>".$members['kin_telno']."</td>
				   </tr>";
					$i++;
		}
	}
	elseif($mem_no == '' && $mem_name==''){
		
		$cont = "<font color=red>Select a Client.</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
	}elseif(strtolower($type) == 'all'){
		
		$cont = "<font color=red>No Registered Customers Yet.</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
	}elseif(strtolower($type) == 'non_members'){
			
		$cont = "<font color=red>No Non Members Registered Yet.</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
		
	}elseif (strtolower($type) == 'members'){
		
		$cont = "<font color=red>No Members Registered Yet.</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
         }
	$content .= "<tbody></table></div>";
	$resp->call("createTableJs");
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}
//PERFORMANCE INDICATORS REPORT
function ratios($date){
        list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$link = ($year == '') ? "" : "<a href='export/ratios?year=".$year."&month=".$month."&mday=".$mday."' target=blank()><b>Printable Version</b></a> | <a href='export/ratios?year=".$year."&month=".$month."&mday=".$mday."&format=excel' target=blank()><b>Export Excel</b></a>";
	
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR PERFORMANCE INDICATORS REPORT</h3>
                                <p class="text-default nm"></p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Date:</label>
                                          <input type="text" class="form-control" id="date" name="date" placeholder="date" />        
                                        </div>
                                        
                                    </div>
                                </div>';                                  
                                   	
		 $content .= "<div class='panel-footer'>                              
                               
                                <button type='button' class='btn  btn-primary' onclick=\"xajax_ratios(getElementById('date').value); return false;\">Show Report</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","date");
                    
               //$resp->assign("display_div", "innerHTML", $content);
	
	
	
	if($date == ''){
		$cont = "<font color=red>Please select the date</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	
	else{
	$last2_perc =0;
	//$chosen_date = sprintf("%d-%02d-%02d", $date);
	$chosen_date = $date;
	$last1_date = $calc->daysToDate($calc->dateToDays($mday, $month, $year) - 365, "%Y-%m-%d");
	$last2_date = $calc->daysToDate($calc->dateToDays($mday, $month, $year) - 730, "%Y-%m-%d");
        $content .=$link;
	 $content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <p><h5 class="text-primary mt0">FINANCIAL PERFORMANCE INDICATORS REPORT</h5</p>
                               
                            </div>';
 		//$content .= '<table class="table table-striped table-bordered" id="table-tools">';
	
	$content .= "<table  border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='100%'><tr class='headings'><td></td><td><b>Management Accounts As At ".$chosen_date."</b></td><td><b>Management Accounts As At ".$last1_date."</b></td><td><b>Management Accounts As At ".$last2_date."</b></td></tr>
	<tr bgcolor=white><td><b>1. Outreach</b></td><td></td><td></td><td></td></tr>";
	
	//LOAN CLIENTS ON  CHOSEN DATE
	//female
	$loan_res = mysql_query("select count(distinct applic.mem_id) as clients from loan_applic applic join member m on applic.mem_id=m.id where applic.date <='".$chosen_date."' and m.sex='F'");
	$loan = mysql_fetch_array($loan_res);
	$chosen_female = ($loan['clients'] != NULL) ? $loan['clients'] : 0;
	//male
	$loan_res = mysql_query("select count(distinct applic.mem_id) as clients from loan_applic applic join member m on applic.mem_id=m.id where applic.date <='".$chosen_date."' and m.sex='M'");
	$loan = mysql_fetch_array($loan_res);
	$male = ($loan['clients'] != NULL) ? $loan['clients'] : 0;
	$chosen_clients = ($chosen_female == 0 && $male==0) ? 0: $chosen_female + $male;
	$denom = ($chosen_clients == 0) ? 1 : $chosen_clients;
	$chosen_perc = ($chosen_female / $denom) * 100;
	$chosen_perc = sprintf("%.02f", $chosen_perc);

	//LOAN CLIENTS PREVIOUS YEAR
	$loan_res = mysql_query("select count(distinct applic.mem_id) as clients from loan_applic applic join member m on applic.mem_id=m.id where applic.date <='".$last1_date."' group by m.sex order by m.sex");
	$loan = mysql_fetch_array($loan_res);
	$last1_female = ($loan['clients'] != NULL) ? $loan['clients'] : 0;
	$loan = mysql_fetch_array($loan_res);
	$male = ($loan['clients'] != NULL) ? $loan['clients'] : 0;
	$last1_clients = ($last1_female == 0 && $male ==0) ? 0 : $last1_female + $male;
	$denom = ($last1_clients == 0) ? 1 : $last1_clients;
	$last1_perc = ($last1_female / $denom) * 100;
	$last1_perc = sprintf("%.02f", $last1_perc);

	//LOAN CLIENTS PREVIOUS PREVIOUS YEAR
	$loan_res = mysql_query("select count(distinct applic.mem_id) as clients from loan_applic applic join member m on applic.mem_id=m.id where applic.date <='".$last2_date."' group by m.sex order by m.sex");
	$loan = mysql_fetch_array($loan_res);
	$last2_female = ($loan['clients'] != NULL) ? $loan['clients'] : 0;
	$loan = mysql_fetch_array($loan_res);
	$male = ($loan['clients'] != NULL) ? $loan['clients'] : 0;
	$last2_clients = ($last2_female == 0 && $male==0) ? 0 : $last2_female + $male;
	$denom = ($last2_clients == 0) ? 1 : $last2_clients;
	$chosen_perc = ($last2_female / $denom) * 100;
	$last2_perc = sprintf("%.02f", $last2_perc);
	$content .="<tr bgcolor=lightgrey><td>No. active Loan clients</td><td>".$chosen_clients."</td><td>".$last1_clients."</td><td>".$last2_clients."</td></tr>
	<tr bgcolor=white><td>Women Loan clients (% total)</td><td>".$chosen_perc."</td><td>".$last1_perc."</td><td>".$last2_perc."</td></tr>";
	
	//SAVINGS AS AT CHOSEN DATE
	$dep_res = mysql_query("select sum(amount - flat_value - percent_value) as amount from deposit where date <='".$chosen_date."'");
	$dep = mysql_fetch_array($dep_res);
	$dep_amt = ($dep['amount'] != NULL) ? $dep['amount'] : 0;
	$with_res = mysql_query("select sum(amount + flat_value + percent_value) as amount from withdrawal where date <='".$chosen_date."'");
	$with = mysql_fetch_array($with_res);
	$with_amt = ($with['amount'] != NULL) ? $with['amount'] : 0;
	$chosen_savings = $dep_amt - $with_amt;

	//SAVINGS AS OF PREVIOUS YEAR
	$dep_res = mysql_query("select sum(amount - flat_value - percent_value) as amount from deposit where date <='".$last1_date."'");
	$dep = mysql_fetch_array($dep_res);
	$dep_amt = ($dep['amount'] != NULL) ? $dep['amount'] : 0;
	$with_res = mysql_query("select sum(amount + flat_value + percent_value) as amount from withdrawal where date <='".$last1_date."'");
	$with = mysql_fetch_array($with_res);
	$with_amt = ($with['amount'] != NULL) ? $with['amount'] : 0;
	$last1_savings = $dep_amt - $with_amt;

	//SAVINGS AS OF PREVIOUS PREVIOUS YEAR
	$dep_res = mysql_query("select sum(amount - flat_value - percent_value) as amount from deposit where date <='".$last2_date."'");
	$dep = mysql_fetch_array($dep_res);
	$dep_amt = ($dep['amount'] != NULL) ? $dep['amount'] : 0;
	$with_res = mysql_query("select sum(amount + flat_value + percent_value) as amount from withdrawal where date <='".$last2_date."'");
	$with = mysql_fetch_array($with_res);
	$with_amt = ($with['amount'] != NULL) ? $with['amount'] : 0;
	$last2_savings = $dep_amt - $with_amt;

	$save_res = mysql_query("select count(distinct mem_id) as savers from mem_accounts where open_date <='".$chosen_date."'");
	$save = mysql_fetch_array($save_res);
	$chosen_savers = ($save['savers'] != NULL) ? $save['savers'] : 0;

	$save_res = mysql_query("select count(distinct mem_id) as savers from mem_accounts where open_date <='".$last1_date."'");
	$save = mysql_fetch_array($save_res);
	$last1_savers = ($save['savers'] != NULL) ? $save['savers'] : 0;

	$save_res = mysql_query("select count(distinct mem_id) as savers from mem_accounts where open_date <='".$last2_date."'");
	$save = mysql_fetch_array($save_res);
	$last2_savers = ($save['savers'] != NULL) ? $save['savers'] : 0;

	$content .= "<tr bgcolor=lightgrey><td><b>2. Savings</b></td><td></td><td></td><td></td></tr>
	<tr bgcolor=white><td>Value of Savings</td><td>".number_format($chosen_savings, 2)."</td><td>".number_format($last1_savings, 2)."</td><td>".number_format($last2_savings, 2)."</td></tr>
	<tr bgcolor=lightgrey><td>No. of Savers</td><td>".$chosen_savers."</td><td>".$last1_savers."</td><td>".$last2_savers."</td></tr>";
	
	//PORTFOLIO ACTIVITY
	//PORTFOLIO ACTIVITY AS AT CHOSEN DATE
	//female
	$loan_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id where d.date <='".$chosen_date."' and m.sex='F'");
	$paid_res = mysql_query("select sum(p.princ_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id where d.date <='".$chosen_date."' and m.sex='F'");
	$loan = mysql_fetch_array($loan_res);
	$paid = mysql_fetch_array($paid_res);
	$female_taken = ($loan['amount'] != NULL) ? $loan['amount'] : 0;
	$female_paid = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
	$chosen_female = $female_taken - $female_paid;
	//male
	$loan_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id where d.date <='".$chosen_date."' and m.sex='M'");
	$paid_res = mysql_query("select sum(p.princ_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id where d.date <='".$chosen_date."' and m.sex='M'");
	$loan = mysql_fetch_array($loan_res);
	$paid = mysql_fetch_array($paid_res);
	$male_taken = ($loan['amount'] != NULL) ? $loan['amount'] : 0;
	$male_paid = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
	$male = $male_taken - $male_paid;
	$chosen_out = ($chosen_female == 0 && $male ==0) ? 0 : $chosen_female + $male;
	$denom = ($chosen_out == 0) ? 1 : $chosen_out;
	$chosen_perc = ($chosen_female / $denom) * 100;
	$chosen_perc = sprintf("%.02f", $chosen_perc);

	//PORTFOLIO ACTIVITY END OF PREVIOUS YEAR
	//female
	$loan_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id where d.date <='".$last1_date."' and m.sex='F'");
	$paid_res = mysql_query("select sum(p.princ_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id where d.date <='".$last1_date."' and m.sex='F'");
	$loan = mysql_fetch_array($loan_res);
	$paid = mysql_fetch_array($paid_res);
	$female_taken = ($loan['amount'] != NULL) ? $loan['amount'] : 0;
	$female_paid = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
	$last1_female = $female_taken - $female_paid;
	//male
	$loan_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id where d.date <='".$last1_date."' and m.sex='M'");
	$paid_res = mysql_query("select sum(p.princ_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id where d.date <='".$last1_date."' and m.sex='M'");
	$loan = mysql_fetch_array($loan_res);
	$paid = mysql_fetch_array($paid_res);
	$male_taken = ($loan['amount'] != NULL) ? $loan['amount'] : 0;
	$male_paid = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
	$male = $male_taken - $male_paid;
	$last1_out = ($last1_female == 0 && $male ==0) ? 0 : $last1_female + $male;
	$denom = ($last1_out == 0) ? 1 : $last1_out;
	$last1_perc = ($last1_female / $denom) * 100;
	$last1_perc = sprintf("%.02f", $last1_perc);

	//PORTFOLIO ACTIVITY END OF PREVIOUS PREVIOUS YEAR
	//female
	$loan_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id where d.date <='".$last2_date."' and m.sex='F'");
	$paid_res = mysql_query("select sum(p.princ_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id where d.date <='".$last2_date."' and m.sex='F'");
	$loan = mysql_fetch_array($loan_res);
	$paid = mysql_fetch_array($paid_res);
	$female_taken = ($loan['amount'] != NULL) ? $loan['amount'] : 0;
	$female_paid = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
	$last2_female = $female_taken - $female_paid;
	//male
	$loan_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id where d.date <='".$last2_date."' and m.sex='M'");
	$paid_res = mysql_query("select sum(p.princ_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id where d.date <='".$last2_date."' and m.sex='M'");
	$loan = mysql_fetch_array($loan_res);
	$paid = mysql_fetch_array($paid_res);
	$male_taken = ($loan['amount'] != NULL) ? $loan['amount'] : 0;
	$male_paid = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
	$male = $male_taken - $male_paid;
	$last2_out = ($last2_female == 0 && $male ==0) ? 0 : $last2_female + $male;
	$denom = ($last2_out == 0) ? 1 : $last2_out;
	$last2_perc = ($last2_female / $denom) * 100;
	$last2_perc = sprintf("%.02f", $last2_perc);
	$content .= "<tr bgcolor=white><td><b>3. Portfolio Activity</b></td><td></td><td></td><td></td></tr>
	<tr bgcolor=lightgrey><td>Value of gross loan portfolio</td><td>".$chosen_out."</td><td>".$last1_out."</td><td>".$last2_out."</td></tr>
	<tr bgcolor=white><td>% of outstanding loans of female clients</td><td>".$chosen_perc."</td><td>".$last1_perc."</td><td>".$last2_perc."</td></tr>";

	//PORTFOLIO QUALITY
	$content .= "<tr bgcolor=lightgrey><td><b>4. Portfolio Quality</b></td><td></td><td></td><td></td></tr>";
	$prov_res = mysql_query("select * from provissions order by range");
	while($row = mysql_fetch_array($prov_res)){  //SET PROVISSION PERCENTAGES
		${$row['range']} = $row['percent'];
	}
	for($i=0; $i <3; $i++){
		$provission = 0;
		if($i == 0)
			$apparent_date = $chosen_date;
		elseif($i==1)
			$apparent_date = $last1_date;
		elseif($i == 2)
			$apparent_date = $last2_date;
		$sth = mysql_query("select d.id as loan_id, d.last_pay_date as last_pay_date, d.amount as amount, d.balance as balance, d.period as loan_period from disbursed d where d.date <= '".$apparent_date."'");
		while($row = mysql_fetch_array($sth)){
			$paid_amt = $row['amount'] - $row['balance'];
			$sched_res = mysql_query("select  sum(princ_amt) as princ_amt from schedule where loan_id='".$row['loan_id']."' and date <= '".$apparent_date."'");
			$sched = mysql_fetch_array($sched_res);
			$sched_amt =($sched['princ_amt'] != NULL) ? $sched['princ_amt'] : 0;
			$arrears_amt = $sched_amt - $paid_amt; 
			$arrears_amt = ($arrears_amt >= 0) ? $arrears_amt : 0;
			$range1 = 0;
			$range2 = 0;
			$range3 = 0;  
			$range4 = 0;
			$range5 = 0;
			$range6 = 0;
			$offset=0;
			if($arrears_amt >0){
				$lastdue_res = mysql_query("select date, date_format(date, '%Y') as year, date_format(date, '%m') as month, date_format(date, '%d') as mday from schedule order by date desc limit 1");
				$lastdue = mysql_fetch_array($lastdue_res);
				$offset = strtotime($apparent_date) - strtotime($lastdue['date']);
			
		
				$offset = ($offset >0) ? $offset : 0;
				$offset = floor($offset /(3600 * 24 * 30));
				$instalment = ceil($row['amount'] / ($row['loan_period']/ 30));
				$no = ceil($arrears_amt / $instalment);
				$remainder = $arrears_amt % $instalment;
			}
			
			if($offset >= 6)   //THE WHOLE SCHEDULE IS MORE THAN 6 MONTHS OVERDUE
				$range6 = $arrears_amt * ($range180_over /100);
			elseif($offset == 5){    //THE WHOLE SCHEDULE IS 5 MONTHS OVERDUE
				if($no == 1)
					$range5 = $arrears_amt * $range120_180/100; 
				elseif($no == 2){
					$range4 = $instalment * $range90_120/100;
					$range5 = ($arrears_amt - $instalment) * $range120_180/100;
				}elseif($no == 3){
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = ($arrears_amt - 2*$instalment) * $range120_180/100;
				}elseif($no == 4){
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = ($arrears_amt - 3*$instalment) * $range120_180/100;
				}elseif($no == 5){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = ($arrears_amt - 4*$instalment) * $range120_180/100;
				}elseif($no >= 6){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = $instalment * $range120_180/100;
					$range6 = ($arrears_amt - 5*$instalment) * $range180_over/100;
				}
			}elseif($offset == 4){    //THE WHOLE SCHEDULE IS 4 MONTHS OVERDUE
				if($no == 1)
					$range4 = $arrears_amt * $range90_120/100; 
				elseif($no == 2){
					$range3 = $instalment * $range60_90/100;
					$range4 = ($arrears_amt - $instalment) * $range90_120/100;
				}elseif($no == 3){
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = ($arrears_amt - 2*$instalment) * $range90_120/100;
				}elseif($no == 4){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = ($arrears_amt - 3*$instalment) * $range90_120/100;
				}elseif($no == 5){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = ($arrears_amt - 4*$instalment) * $range120_180/100;
				}elseif($no >= 6){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = $instalment * $range120_180/100;
					$range6 = ($arrears_amt - 5*$instalment) * $range180_over/100;
				}
			}elseif($offset == 3){    //THE WHOLE SCHEDULE IS 3 MONTHS OVERDUE
				if($no == 1)
					$range3 = $arrears_amt * $range60_90/100; 
				elseif($no == 2){
					$range2 = $instalment * $range30_60/100;
					$range3 = ($arrears_amt - $instalment) * $range60_90/100;
				}elseif($no == 3){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = ($arrears_amt - 2*$instalment) * $range60_90/100;
				}elseif($no == 4){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = ($arrears_amt - 3*$instalment) * $range90_120/100;
				}elseif($no == 5){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = ($arrears_amt - 4*$instalment) * $range120_180/100;
				}elseif($no >= 6){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = $instalment * $range120_180/100;
					$range6 = ($arrears_amt - 5*$instalment) * $range180_over/100;
				}
			}elseif($offset == 2){    //THE WHOLE SCHEDULE IS 2 MONTHS OVERDUE
				if($no == 1)
					$range2 = $arrears_amt * $range30_60/100; 
				elseif($no == 2){
					$range1 = $instalment * $range1_30/100;
					$range2 = ($arrears_amt - $instalment) * $range30_60/100;
				}elseif($no == 3){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = ($arrears_amt - 2*$instalment) * $range60_90/100;
				}elseif($no == 4){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = ($arrears_amt - 3*$instalment) * $range90_120/100;
				}elseif($no == 5){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = ($arrears_amt - 4*$instalment) * $range120_180/100;
				}elseif($no >= 6){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = $instalment * $range120_180/100;
					$range6 = ($arrears_amt - 5*$instalment) * $range180_over/100;
				}
			}elseif($offset <= 1){    //THE WHOLE SCHEDULE IS 1 MONTHS OVERDUE
				if($no == 1)
					$range1 = $arrears_amt * $range1_30/100; 
				elseif($no == 2){
					$range1 = $instalment * $range1_30/100;
					$range2 = ($arrears_amt - $instalment) * $range30_60/100;
				}elseif($no == 3){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = ($arrears_amt - 2*$instalment) * $range60_90/100;
				}elseif($no == 4){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = ($arrears_amt - 3*$instalment) * $range90_120/100;
				}elseif($no == 5){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = ($arrears_amt - 4*$instalment) * $range120_180/100;
				}elseif($no >= 6){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range31_60/100;
					$range3 = $instalment * $range61_90/100;
					$range4 = $instalment * $range91_120/100;
					$range5 = $instalment * $range121_180/100;
					$range6 = ($arrears_amt - 5*$instalment) * $range180_over/100;
				}
			}
			$provission = $provission + $range1 + $range2 + $range3 + $range4 + $range5 + $range6;	
		}
		
		$out_res = mysql_query("select sum(d.amount) as amount from disbursed d where d.date <= '".$apparent_date."'");
		$out = mysql_fetch_array($out_res);
		$out_amt = ($out['amount'] != NULL) ? $out['amount'] : 0;
		$in_res = mysql_query("select sum(princ_amt) as amount from payment where date <= '".$apparent_date."'");
		$in = mysql_fetch_array($in_res);
		$in_amt = ($in['amount'] != NULL) ? $in['amount'] : 0;
		
		if($in_amt >= $out_amt)
			$percent =0;
		else{
			$div = $out_amt - $in_amt;
			$percent = ($provission / ($div)) * 100;
			$percent = sprintf("%.02f", $percent);
		}
		if($i == 0)
			$chosen_perc = $percent;
		elseif($i == 1)
			$last1_perc = $percent;
		elseif($i == 2)
			$last2_perc = $percent;
	}	
	$content .= "<tr bgcolor=lightgrey><td>Risk Coverage Ratio (%)</td><td>".$chosen_perc."</td><td>".$last1_perc."</td><td>".$last2_perc."</td></tr>";
	//PORTIFPLIO AT RISK
	for($i=0; $i <3; $i++){
		$provission = 0;
		if($i == 0)
			$apparent_date = $chosen_date;
		elseif($i==1)
			$apparent_date = $last1_date;
		elseif($i == 2)
			$apparent_date = $last2_date;
		//CALCULATE ARREARS
		$sched_res = mysql_query("select s.loan_id as loan_id,  sum(s.princ_amt) as princ_amt from schedule s join disbursed d on s.loan_id=d.id left join written_off w on d.id=w.loan_id where (w.date >='".$apparent_date."' or w.date is null) and s.date <= date_sub('".$apparent_date."', INTERVAL d.arrears_period DAY) group by s.loan_id");
		$risky_amt =0;
		while($sched = mysql_fetch_array($sched_res)){
			$paid_res = mysql_query("select sum(p.princ_amt) as princ_amt from payment p join disbursed d on p.loan_id=d.id left join written_off w on d.id=w.loan_id where (w.date >='".$apparent_date."' or w.date is null) and p.date <= '".$apparent_date."' and p.loan_id='".$sched['loan_id']."'");
			$paid = mysql_fetch_array($paid_res);
			$paid_amt = ($paid['princ_amt'] != NULL) ? $paid['princ_amt'] : 0;
			if($sched['princ_amt'] > $paid_amt){
				//OUTSTANDING PORTFOLIO THEN
				$disbursed_res = mysql_query("select sum(d.amount) as amount from disbursed d left join written_off w on d.id=w.loan_id where (w.date >='".$apparent_date."' or w.date is null) and d.date <= '".$apparent_date."' and d.id='".$sched['loan_id']."'");
				$disbursed = mysql_fetch_array($disbursed_res);
				$disbursed_amt = ($disbursed['amount'] != NULL) ? $disbursed['amount'] : 0;
				$outstanding = $disbursed_amt - $paid_amt;
				$risky_amt = $risky_amt + $outstanding;
			
			}
		}
		//TOTAL DISBURSED
		$disbursed_res = mysql_query("select sum(d.amount) as amount from disbursed d left join written_off w on d.id=w.loan_id where (w.date >='".$apparent_date."' or w.date is null) and d.date <= '".$apparent_date."'");
		$disbursed = mysql_fetch_array($disbursed_res);
		$disbursed_amt = ($disbursed['amount'] != NULL) ? $disbursed['amount'] : 0;
		
		//TOTAL PAID
		$paid_res = mysql_query("select sum(p.princ_amt) as princ_amt from payment p join disbursed d on p.loan_id=d.id left join written_off w on d.id=w.loan_id where (w.date >='".$apparent_date."' or w.date is null) and p.date <= '".$apparent_date."'");
		$paid = mysql_fetch_array($paid_res);
		$paid_amt = ($paid['princ_amt'] != NULL) ? $paid['princ_amt'] : 0;
		//TOTAL OUTSTANDING
		$outstanding = $disbursed_amt - $paid_amt;
		$outstanding = ($outstanding > 0) ? $outstanding : 1;
		$percent = ($risky_amt / $outstanding) * 100.00;
		$percent = sprintf("%.02f", $percent);
		if($i == 0)
			$chosen_perc = $percent;
		elseif($i == 1)
			$last1_perc = $percent;
		elseif($i == 2)
			$last2_perc = $percent;
	}	
	$content .= "<tr bgcolor=white><td>Portfolio At Risk (%)</td><td>".$chosen_perc."</td><td>".$last1_perc."</td><td>".$last2_perc."</td></tr>";

	//PORTIFOLIO YIELD
	for($i=0; $i <3; $i++){
		$provission = 0;
		if($i == 0)
			$apparent_date = $chosen_date;
		elseif($i==1)
			$apparent_date = $last1_date;
		elseif($i == 2)
			$apparent_date = $last2_date;
	//DISBURSED LOANS
		$disbursed_res = mysql_query("select sum(amount) as amount from disbursed where date <='".$apparent_date."'");
		$disbursed = mysql_fetch_array($disbursed_res);
		$disbursed_amt = ($disbursed['amount'] != NULL) ? $disbursed['amount'] : 0;
		//INTEREST PAID
		$paid_res = mysql_query("select sum(int_amt) as int_amt, sum(princ_amt) as princ_amt from payment where date <='".$apparent_date."'");
		$paid = mysql_fetch_array($paid_res);
		$paid_int = ($paid['int_amt'] != NULL) ? $paid['int_amt'] : 0;
		$paid_princ = ($paid['princ_amt'] != NULL) ? $paid['princ_amt'] : 0;
		//WRITTEN OFF 
		$write_res = mysql_query("select sum(amount) as amount from written_off where date <='".$apparent_date."'");
		$write = mysql_fetch_array($write_res);
		$written_amt = ($write['amount'] != NULL) ? $write['amount'] : 0;
		//OUTSTANDING
		$balance = $disbursed_amt - $paid_princ - $written_amt;
		$balance = ($balance == 0) ? 1 : $balance;
		$percent = ($paid_int / $balance) * 100.00;
		$percent = sprintf("%.02f", $percent);
		if($i == 0)
			$chosen_perc = $percent;
		elseif($i == 1)
			$last1_perc = $percent;
		elseif($i == 2)
			$last2_perc = $percent;
	}	
	$content .= "<tr bgcolor=lightgrey><td>Portfolio Yield (%) </td><td>".$chosen_perc."</td><td>".$last1_perc."</td><td>".$last2_perc."</td></tr>";

	//EFFECTIVE REPAYMENT RATE 
	//CALCULATE CUMMULATED REPAYMENT RATE
	for($i=0; $i <3; $i++){
		if($i == 0)
			$apparent_date = $chosen_date;
		elseif($i==1)
			$apparent_date = $last1_date;
		elseif($i == 2)
			$apparent_date = $last2_date;
		$sched_res = mysql_query("select  sum(s.princ_amt) as princ_amt from schedule s join disbursed d on s.loan_id=d.id left join written_off w on d.id=w.loan_id where (w.date >='".$apparent_date."' or w.date is null) and s.date <= '".$apparent_date."'");
		$sched = mysql_fetch_array($sched_res);
		$sched_amt = ($sched['princ_amt']!= NULL) ? $sched['princ_amt'] : 1;
		$paid_res = mysql_query("select sum(p.princ_amt) as princ_amt from payment p join disbursed d on p.loan_id=d.id left join written_off w on d.id=w.loan_id where (w.date >='".$apparent_date."' or w.date is null) and p.date <= '".$apparent_date."'");
		$paid = mysql_fetch_array($paid_res);
		$paid_amt = ($paid['princ_amt'] != NULL) ? $paid['princ_amt'] : 0;

		$percent = ($paid_amt / $sched_amt) * 100.00;		
		$percent = sprintf("%.02f", $percent);
		if($i == 0)
			$chosen_perc = $percent;
		elseif($i == 1)
			$last1_perc = $percent;
		elseif($i == 2)
			$last2_perc = $percent;
	}	
	$content .= "<tr bgcolor=white><td>Effective Repayment Rate (%) </td><td>".$chosen_perc."</td><td>".$last1_perc."</td><td>".$last2_perc."</td></tr>
	<tr bgcolor=lightgrey><td><b>5. Profitability:</b></td><td></td><td></td><td></td></tr>";
	for($i=0; $i <3; $i++){
		if($i == 0)
			$apparent_date = $chosen_date;
		elseif($i==1)
			$apparent_date = $last1_date;
		elseif($i == 2)
			$apparent_date = $last2_date;
		//OPERATING INCOME
		//INCOME FROM LOANS
		$int_res = mysql_query("select sum(int_amt) as amount from payment where date <='".$apparent_date."'");
		$int = mysql_fetch_array($int_res);
		$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;

		//PENALITIES
		$pen_res = mysql_query("select sum(amount) as amount from penalty where date <='".$apparent_date."'");
		$pen = mysql_fetch_array($pen_res);
		$pen_amt = ($pen['amount'] != NULL) ? $pen['amount'] : 0;

		//OTHER INCOME
		$other_res = mysql_query("select sum(amount) as amount from other_income where date <= '".$apparent_date."'");
		$other = mysql_fetch_array($other_res);
		$other_amt = ($other['amount'] != NULL);
		
		//TRANSACTIONAL CHARGES
		$dep_res = mysql_query("select sum(flat_value + percent_value) as charge from deposit where date <='".$apparent_date."'");
		$dep = mysql_fetch_array($dep_res);
		$dep_charge = ($dep['charge'] != NULL) ? $dep['charge'] : 0;
		$with_res = mysql_query("select sum(flat_value + percent_value) as charge from withdrawal where date <='".$apparent_date."'");
		$with = mysql_fetch_array($with_res);
		$with_charge = ($with['charge'] != NULL) ? $with['charge'] : 0;

		$charge_res = mysql_query("select sum(amount) as amount from monthly_charge where date <='".$last_date."'");
		$charge = mysql_fetch_array($charge_res);
		$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;

		$income = $int_amt + $pen_amt + $other_amt + $dep_charge + $with_charge + $charge_amt;
		
//OPERATING EXPENSES
		$expense_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where date <= '".$apparent_date."'");
		$expense = mysql_fetch_array($expense_res);
		$expense_amt = ($expense['amount'] != NULL) ? $expense['amount'] : 1;
		$percent = ($income / $expense_amt) * 100;		
		$percent = sprintf("%.02f", $percent);
		if($i == 0)
			$chosen_perc = $percent;
		elseif($i == 1)
			$last1_perc = $percent;
		elseif($i == 2)
			$last2_perc = $percent;
	}	
	$content .= "<tr bgcolor=white><td>Operational Self-Sufficiency (%) </td><td>".$chosen_perc."</td><td>".$last1_perc."</td><td>".$last2_perc."</td></tr>";
	
	for($i=0; $i <3; $i++){
		if($i == 0)
			$apparent_date = $chosen_date;
		elseif($i==1)
			$apparent_date = $last1_date;
		elseif($i == 2)
			$apparent_date = $last2_date;
	//OPERATING INCOME
		//INCOME FROM LOANS
		$int_res = mysql_query("select sum(int_amt) as amount from payment where date <='".$apparent_date."'");
		$int = mysql_fetch_array($int_res);
		$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;
	//	$resp->AddAlert($int_amt);
		//PENALITIES
		$pen_res = mysql_query("select sum(amount) as amount from penalty where date <='".$apparent_date."'");
		$pen = mysql_fetch_array($pen_res);
		$pen_amt = ($pen['amount'] != NULL) ? $pen['amount'] : 0;

		//OTHER INCOME
		$other_res = mysql_query("select sum(i.amount) as amount from other_income i join accounts a on i.account_id=a.id where i.date <= '".$apparent_date."'");
		$other = mysql_fetch_array($other_res);
		$other_amt = ($other['amount'] != NULL) ? $other['amount'] : 0;

		//COLLECTED
		$col_res = mysql_query("select sum(amount) as amount from collected where date <'".$apparent_date."'");
		$col = mysql_fetch_array($col_res);
		$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
	
		//RECOVERED
		$recovered_res = mysql_query("select sum(amount) as amount from recovered where date < '".$apparent_date."'");
		$recovered = mysql_fetch_array($recovered_res);
		$recovered_amt = ($recovered['amount'] != NULL) ? $recovered['amount'] : 0;

		//TRANSACTIONAL CHARGES
		$dep_res = mysql_query("select sum(flat_value + percent_value) as charge from deposit where date <='".$apparent_date."'");
		$dep = mysql_fetch_array($dep_res);
		$dep_charge = ($dep['charge'] != NULL) ? $dep['charge'] : 0;
		$with_res = mysql_query("select sum(flat_value + percent_value) as charge from withdrawal where date <='".$apparent_date."'");
		$with = mysql_fetch_array($with_res);
		$with_charge = ($with['charge'] != NULL) ? $with['charge'] : 0;

		//GAIN ON SALE OF FIXED ASSETS
		$fixed_res = mysql_query("select sum(s.amount - f.initial_value) as amount from fixed_asset f join sold_asset s on f.id=s.asset_id where f.date <'".$apparent_date."' and s.id in (select asset_id from sold_asset where date <='".$apparent_date."')");
		$fixed = mysql_fetch_array($fixed_res);
		$fixed_amt =($fixed['amount'] != NULL) ? $fixed['amount'] : 0;
		//GAIN ON SALE OF INVESTMENTS
		$inv_res = mysql_query("select sum(quantity * purchase_price) as tot_cost, sum(quantity * amount) as tot_gain from sold_invest where date <= '".$apparent_date."'");
		$inv = mysql_fetch_array($inv_res);
		if($inv['tot_cost'] == NULL){
			$invest_amt =0;
		}else{
			$invest_amt = $inv['tot_gain'] - $inv['tot_cost'];
		}

		


		//$invest_res = mysql_query("select sum(s.amount - i.amount) as amount from investments i join sold_invest s on i.id=s.investment_id where i.date <'".$apparent_date."' and i.id in (select investment_id from sold_invest where date <='".$apparent_date."')");
		//$invest = mysql_fetch_array($invest_res);
		//$invest_amt =($invest['amount'] != NULL) ? $invest['amount'] : 0;

		$charge_res = mysql_query("select sum(amount) as amount from monthly_charge where date <='".$last_date."'");
		$charge = mysql_fetch_array($charge_res);
		$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
		
		$income = $int_amt + $pen_amt + $other_amt + $recovered_amt + $col_amt + $fixed_amt + $invest_amt + $dep_charge + $with_charge + $charge_amt;
		
		
//OPERATING EXPENSES
		$expense_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where date <= '".$apparent_date."'");
		$expense = mysql_fetch_array($expense_res);
		$expense_amt = ($expense['amount'] != NULL) ? $expense['amount'] : 1;

		$payable_res = mysql_query("select sum(amount) as amount from payable_paid where date <'".$apparent_date."'");
		$payable = mysql_fetch_array($payable_res);
		$payable_amt = ($payable['amount'] != NULL) ? $payable['amount'] : 0;

		$shared_res = mysql_query("select sum(total_amount) as amount from share_dividends where date <='".$apparent_date."'");
		$shared = mysql_fetch_array($shared_res);
		$shared_amt = ($shared['amount'] != NULL) ? $shared['amount'] : 0;

		$written_res = mysql_query("select sum(amount) as amount from written_off where date <='".$apparent_date."'");
		$written = mysql_fetch_array($written_res);
		$written_amt = ($written['amount'] != NULL) ? $written['amount'] : 0;
		$expense = $expense_amt + $payable_amt + $shared_amt + $written_amt;
		$expense = ($expense != 0) ? $expense : 1;

		$percent = ($income / $expense) * 100.00;
		$percent = sprintf("%.02f", $percent);
		if($i == 0)
			$chosen_perc = $percent;
		elseif($i == 1)
			$last1_perc = $percent;
		elseif($i == 2)
			$last2_perc = $percent;
	}	
	$content .= "<tr bgcolor=lightgrey><td>Financial Self-Sufficiency (%) </td><td>".$chosen_perc."</td><td>".$last1_perc."</td><td>".$last2_perc."</td></tr>";
	


	//LIQUIDITY RATIO
	$content .= "<tr bgcolor=white><td><b>6. Liquidity:</b></td><td></td><td></td><td></td></tr>";
	for($i=0; $i <3; $i++){
		if($i == 0)
			$apparent_date = $chosen_date;
		elseif($i==1)
			$apparent_date = $last1_date;
		elseif($i == 2)
			$apparent_date = $last2_date;
		//CASH
		//DEPOSITS
		$dep_res = mysql_query("select sum(amount) as amount from deposit where  date <='".$apparent_date."' and bank_account >0");
		$dep = mysql_fetch_array($dep_res);
		//WITHDRAWALS
		$with_res = mysql_query("select sum(amount) as amount from withdrawal where  date <='".$apparent_date."'");
		$with = mysql_fetch_array($with_res);
		//OTHER INCOME
		$other_res = mysql_query("select sum(amount) as amount from other_income where  date <='".$apparent_date."'");
		$other = mysql_fetch_array($other_res);
		//EXPENSES
		$expense_res = mysql_query("select sum(amount) as amount from expense where  date <='".$apparent_date."'");
		$expense = mysql_fetch_array($expense_res);
		//PAYABLE PAID
		$payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where  date <='".$apparent_date."'");	
		$payable_paid = mysql_fetch_array($payable_paid_res);
		//RECEIVALE COLLECTED
		$collected_res = mysql_query("select sum(amount) as amount from collected where  date <='".$apparent_date."'");
		$collected = mysql_fetch_array($collected_res);
		//DISBURSED LOANS
		$disb_res = mysql_query("select sum(amount) as amount from disbursed where  date <= '".$apparent_date."'");
		$disb = mysql_fetch_array($disb_res);
		//PAYMENTS
		$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id  where p.date <= '".$apparent_date."'");
		$pay = mysql_fetch_array($pay_res);
		//PENALTIES
		$pen_res = mysql_query("select sum(p.amount) as amount from penalty p join disbursed d on p.loan_id=d.id where  p.status='paid' and p.date <= '".$apparent_date."'");
		$pen = mysql_fetch_array($pen_res);
								
		//SHARES
		$shares_res = mysql_query("select sum(value) as amount from shares where date <='".$apparent_date."'");
		$shares = mysql_fetch_array($shares_res); 
		//RECOVERED
		$rec_res = mysql_query("select sum(r.amount) as amount from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where  r.date <= '".$apparent_date."'");
		$rec = mysql_fetch_array($rec_res);		
		//INVESTMENTS 
		$invest_res = mysql_query("select sum(amount) as amount from investments where date < '".$apparent_date."'");
		$invest = mysql_fetch_array($invest_res);
		$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
		$soldinvest_res = mysql_query("select sum(amount) as amount from sold_invest where date <='".$apparent_date."'");
		$soldinvest = mysql_fetch_array($soldinvest_res);

		//FIXED ASSETS 
		$fixed_res = mysql_query("select sum(initial_value) as amount from fixed_asset where  date <='".$apparent_date."'");
		$fixed = mysql_fetch_array($fixed_res);
		$soldasset_res = mysql_query("select sum(amount) as amount from sold_asset where date <='".$apparent_date."'");
		$soldasset = mysql_fetch_array($soldasset_res);
									
		$cash_amt =  $collected['amount'] + $dep['amount'] + $other['amount'] - $with['amount'] - $expense['amount'] -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'];	

		//LIQUID INVESTMENTS
		$invest_res = mysql_query("select sum(i.amount) as amount from investments i join accounts a on i.account_id=a.id where i.date <= '".$apparent_date."' and i.account_id not in (select account_id from sold_invest where date <= '".$apparent_date."') and a.account_no like '113%'");
		$invest = mysql_fetch_array($invest_res);
		$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
		$liquid_amt = $cash_amt + $invest_amt;
		

		//CURRENT LIABILITIES
		//SAVINGS
		$dep_res = mysql_query("select sum(amount - flat_value - percent_value) as amount from deposit where  date <='".$apparent_date."' and bank_account >0");
		$dep = mysql_fetch_array($dep_res);
		$dep_amt = ($dep['amount'] != NULL) ? $dep['amount'] : 0;
		$with_res = mysql_query("select sum(amount + flat_value + percent_value) as amount from withdrawal where  date <='".$apparent_date."'");
		$with = mysql_fetch_array($with_res);
		$with_amt = ($with['amount'] != NULL) ? $with['amount'] : 0;
		$savings = $dep_amt - $with_amt;
		//INTEREST PAYABLE ON SAVINGS
		$int_res = mysql_query("select sum(amount) as amount from save_interest where date <= '".$apparent_date."'");
		$int = mysql_fetch_array($int_res);
		$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;
		//OTHER SHORT-TERM LIABILITIES
		$payable_res = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '2%' and a.account_no not like '2122%' and p.maturity_date='".$apparent_date."'");
		$payable = mysql_fetch_array($pay_res);
		$payable_amt = ($payable['amount'] != NULL) ? $payable['amount'] : 0;

		$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id join accounts a on p.account_id=a.id where a.account_no like '2%' and a.account_no not like '2122%' and p.maturity_date='".$apparent_date."' and paid.date <='".$apparent_date."'");
		
		$paid = @mysql_fetch_array($paid_res);
		$paid_amt = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
		$payable_amt = $payable_amt - $paid_amt;
		$liabilities = $payable_amt + $savings + $int_amt;
		$liabilities = ($liabilities > 0) ? $liabilities  : 1;

		$percent = ($liquid_amt / $liabilities) * 100.00;
		$percent = sprintf("%.02f", $percent);
		if($i == 0)
			$chosen_perc = $percent;
		elseif($i == 1)
			$last1_perc = $percent;
		elseif($i == 2)
			$last2_perc = $percent;
	}	
	$content .= "<tr bgcolor=lightgrey><td>Liquidity Ratio (%) </td><td>".$chosen_perc."</td><td>".$last1_perc."</td><td>".$last2_perc."</td></tr>";

	//OPERATING EFFICIENCY
	$content .= "<tr bgcolor=white><td><b>7. Operating Efficiency:</b> </td><td></td><td></td><td></td></tr>";
	for($i=0; $i <3; $i++){
		if($i == 0)
			$apparent_date = $chosen_date;
		elseif($i==1)
			$apparent_date = $last1_date;
		elseif($i == 2)
			$apparent_date = $last2_date;
	//OPERATING INCOME
		//INCOME FROM LOANS
		$int_res = mysql_query("select sum(int_amt) as amount from payment where date <='".$apparent_date."'");
		$int = mysql_fetch_array($int_res);
		$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;

		//PENALITIES
		$pen_res = mysql_query("select sum(amount) as amount from penalty where date <='".$apparent_date."'");
		$pen = mysql_fetch_array($pen_res);
		$pen_amt = ($pen['amount'] != NULL) ? $pen['amount'] : 0;

		//OTHER INCOME
		$other_res = mysql_query("select sum(amount) as amount from other_income where date <= '".$apparent_date."'");
		$other = mysql_fetch_array($other_res);
		$other_amt = ($other['amount'] != NULL);
		
		//TRANSACTIONAL CHARGES
		$dep_res = mysql_query("select sum(flat_value + percent_value) as charge from deposit where date <='".$apparent_date."'");
		$dep = mysql_fetch_array($dep_res);
		$dep_charge = ($dep['charge'] != NULL) ? $dep['charge'] : 0;
		$with_res = mysql_query("select sum(flat_value + percent_value) as charge from withdrawal where date <='".$apparent_date."'");
		$with = mysql_fetch_array($with_res);
		$with_charge = ($with['charge'] != NULL) ? $with['charge'] : 0;

		$charge_res = mysql_query("select sum(amount) as amount from monthly_charge where date <='".$last_date."'");
		$charge = mysql_fetch_array($charge_res);
		$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;

		$income = $int_amt + $pen_amt + $other_amt + $dep_charge + $with_charge + $charge_amt;
		$income_amt = ($income != 0) ? $income : 1;
		
//OPERATING EXPENSES
		$expense_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where date <= '".$apparent_date."'");
		$expense = mysql_fetch_array($expense_res);
		$expense_amt = ($expense['amount'] != NULL) ? $expense['amount'] : 0;
		
		$percent = ($expense_amt / $income_amt) * 100.00;
		$percent = sprintf("%.02f", $percent);
		if($i == 0)
			$chosen_perc = $percent;
		elseif($i == 1)
			$last1_perc = $percent;
		elseif($i == 2)
			$last2_perc = $percent;
	}	
	$content .= "<tr bgcolor=lightgrey><td>Operating Expense Ratio (%) </td><td>".$chosen_perc."</td><td>".$last1_perc."</td><td>".$last2_perc."</td></tr>";
	for($i=0; $i <3; $i++){
		if($i == 0)
			$apparent_date = $chosen_date;
		elseif($i==1)
			$apparent_date = $last1_date;
		elseif($i == 2)
			$apparent_date = $last2_date;
		$loan_res = mysql_query("select sum(amount) as amount from disbursed where date <'".$apparent_date."'");
		$loan = mysql_fetch_array($loan_res);
		$loan_amt = ($loan['amount'] != NULL) ? $loan['amount'] : 0;

		$paid_res = mysql_query("select sum(princ_amt) as amount from payment where date<='".$apparent_date."'");
		$paid = mysql_fetch_array($paid_res);
		$paid_amt = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
		$portifolio = $loan_amt - $paid_amt;

		$no_res = mysql_query("select amount, id from disbursed where date <='".$apparent_date."'");
		$x=0;
		while($no = mysql_fetch_array($no_res)){
			$paid_res = mysql_query("select sum(princ_amt) as amount from payment where date <='".$apparent_date."' and loan_id='".$no['id']."'");
			$paid = mysql_fetch_array($paid_res);
			$paid_amt = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
			if($no['amount'] >= $paid_amt)
				$x++;
		}
		$x = ($x >0) ? $x : 1;
		$percent = $portifolio / $x;
		$percent = sprintf("%.02f", $percent);
		if($i == 0)
			$chosen_perc = $percent;
		elseif($i == 1)
			$last1_perc = $percent;
		elseif($i == 2)
			$last2_perc = $percent;
	}
	$content .= "<tr bgcolor=white><td>Average Loan Portfolio (%) </td><td>".$chosen_perc."</td><td>".$last1_perc."</td><td>".$last2_perc."</td></tr>";




	//CAPITAL RATIO
	$content .= "<tr bgcolor=lightgrey><td><b>8. Capital Ratio:</b> </td><td></td><td></td><td></td></tr>";
	for($i=0; $i <3; $i++){
		if($i == 0)
			$apparent_date = $chosen_date;
		elseif($i==1)
			$apparent_date = $last1_date;
		elseif($i == 2)
			$apparent_date = $last2_date;
	//LONG-TERM LOANS PAYABLE
		$payable_res = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '2122%' and p.maturity_date='".$apparent_date."'");
		$payable = mysql_fetch_array($pay_res);
		$payable_amt = ($payable['amount'] != NULL) ? $payable['amount'] : 0;

		$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id join accounts a on p.account_id=a.id where a.account_no like '2%' and a.account_no not like '2122%' and p.maturity_date='".$apparent_date."' and paid.date <='".$apparent_date."'");
		$paid = @mysql_fetch_array($paid_res);
		$paid_amt = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
		$debt_amt = $payable_amt - $paid_amt;
		
		//CAPITAL
		$shares_res = mysql_query("select sum(value) as amount from shares where date <='".$apparent_date."'");
		$shares = mysql_fetch_array($shares_res);
		$shares_amt = ($shares['amount'] != NULL) ? $shares['amount'] : 0;

		$don_res = mysql_query("select sum(i.amount) as amount from other_income i join accounts a on i.account_id=a.id where a.account_no like '3313%' and i.date <= '".$apparent_date."'");
		$don = mysql_fetch_array($don_res);
		$don_amt = ($don['amount'] != NULL) ? $don['amount'] : 0;

		$equity_amt = $don_amt + $shares_amt;
		$equity_amt = ($equity_amt > 1) ? $equity_amt : 1;
		$percent = ($debt_amt / $equity_amt) * 100.00;
		$percent = sprintf("%.02f", $percent);
		if($i == 0)
			$chosen_perc = $percent;
		elseif($i == 1)
			$last1_perc = $percent;
		elseif($i == 2)
			$last2_perc = $percent;
	}	
	$content .= "<tr bgcolor=white><td>Debt To Equity Ratio (%) </td><td>".$chosen_perc."</td><td>".$last1_perc."</td><td>".$last2_perc."</td></tr>";

	$content .= "</table>";
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//DISPLAY GRAPH OF AVERAGE LOAN PORTFOLIO AGAINST TIME IN MONTHS
function average_loan($from_date, $to_date){
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$link = ($from_year == '') ? "" : "<a href='export/average_loan' target=blank()><b>Printable Version</b></a>";
	$content = '<div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>';
                                             $resp->call("createDate","from_date");
	                                     $resp->call("createDate","to_date"); 
                                    $content .= "<input type=button onclick=\"xajax_average_loan(getElementById('from_date').value,getElementById('to_date').value);\" value='Show Graph'>".$link."<table height=200 border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center> <tr><td>";
	if($from_date == '' || $to_date=''){
		$content .= "<font color=red>Please select the period</font></td></tr></table>";
		$resp->assign("display_div", "innerHTML", $content);
		return $resp;
	}
	$resp->call('xajax_generate_average_loan', $from_date, $to_date);
	$content .= "<img src=\"average_loan.jpg?dummy=".time()."\">";
	//unlink("coverage.");
	$content .= "</td></tr></table>";
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//GENERATE AVERAGE PORTFOLIO AGAINST TIME
function generate_average_loan($from_date,$to_date){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$to_date = $to_year ."-".$to_month."-31 23:59:59";
	//LOAN LOSS PROVISSIONS
	$next=0;
	$y_list ='';
	$x_list='';
	$from_days = $calc->dateToDays(28, $from_month, $from_year);
	$to_days = $calc->dateToDays(28, $to_month, $to_year);
	$diff = $to_days - $from_days;
	while($next <= $diff){
		$apparent_date = $calc->daysToDate($calc->dateToDays(28, $from_month, $from_year) + $next, '%Y-%m-28');
		preg_match("/(\d+)-(\d+)-28/", $apparent_date, $arr);
		$apparent_date = $calc->endOfMonthBySpan(0, $arr[2], $arr[1], '%Y-%m-%d');
		$loan_res = mysql_query("select sum(amount) as amount from disbursed where date <'".$apparent_date."'");
		$loan = mysql_fetch_array($loan_res);
		$loan_amt = ($loan['amount'] != NULL) ? $loan['amount'] : 0;

		$paid_res = mysql_query("select sum(princ_amt) as amount from payment where date<='".$apparent_date."'");
		$paid = mysql_fetch_array($paid_res);
		$paid_amt = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
		$portifolio = $loan_amt - $paid_amt;

		$no_res = mysql_query("select amount, id from disbursed where date <='".$apparent_date."'");
		$x=0;
		while($no = mysql_fetch_array($no_res)){
			$paid_res = mysql_query("select sum(princ_amt) as amount from payment where date <='".$apparent_date."' and loan_id='".$no['id']."'");
			$paid = mysql_fetch_array($paid_res);
			$paid_amt = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
			if($no['amount'] >= $paid_amt)
				$x++;
		}
		$x = ($x >0) ? $x : 1;
		$percent = $portifolio / $x;
		$percent = sprintf("%.02f", $percent);

		if($y_list == '')
			$y_list = $percent;
		else
			$y_list = $y_list .",".$percent;
		$month_year = date('M', strtotime($apparent_date)) ."-". date('Y', strtotime($apparent_date));
		if($x_list == '')
			$x_list = $month_year;
		else
			$x_list = $x_list .",".$month_year; 
		$next = $next + 30;
	}

	// Create the basic graph
	$x_array = split(',', $x_list);
	$y_array = split(',', $y_list);
	
//CREATE GRAPH
	$graph = new Graph(900,600,'auto');
	$graph->SetScale("textlin");
	$graph->SetMarginColor("lightblue");
	$graph->SetShadow();
	$graph->img->SetMargin(40,80,30,80);
	$graph->img->SetImgFormat("jpeg");

	// Adjust the position of the legend box
	$graph->legend->Pos(0.02,0.15, "right", "center");

	// Adjust the color for theshadow of the legend
	$graph->legend->SetShadow('darkgray@0.5');
	$graph->legend->SetFillColor('lightblue@0.3');

	// Get localised version of the month names
	$graph->xaxis->SetTickLabels($x_array);

	
	// Set axis titles and fonts
	$graph->xaxis->title->Set("Months");
	$graph->title->SetFont(FF_VERDANA,FS_NORMAL,12);
	$graph->title->SetColor("darkred");


	$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,7);
	$graph->xaxis->SetColor('darkblue');
	$graph->xaxis->SetLabelAngle(70);

	$graph->yaxis->title->Set("Average Loan Portfolio");
	$graph->yaxis->SetFont(FF_FONT1,FS_BOLD);
	$graph->yaxis->SetColor('white');

	$graph->ygrid->SetColor('white@0.5');

	// Setup graph title
	$graph->title->Set("AVERAGE LOAN PORTFOLIO PLOTTED AGAINST TIME");
	// Some extra margin (from the top)
	$graph->title->SetMargin(3);
	$graph->title->SetFont(FF_COMIC,FS_NORMAL,12);

	
	// Create the first line
	$p1 = new LinePlot($y_array);
	$p1->mark->SetType(MARK_FILLEDCIRCLE);
	$p1->mark->SetFillColor("red");
	$p1->mark->SetWidth(4);
	$p1->SetColor("blue");
	$p1->SetCenter();
	$p1->SetLegend("Average Loan Portfolio");
	$graph->Add($p1);
	$graph->Stroke("average_loan.jpg");
	return $resp;
}



//DISPLAY GRAPH OF DEBT TO EQUITY RATIO AGAINST TIME IN MONTHS
function debtto_equity_ratio($from_date, $to_date){
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$link = ($from_year == '') ? "" : "<a href='export/debtto_equity' target=blank()><b>Printable Version</b></a>";
	$content = '<div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>';
                                             $resp->call("createDate","from_date");
	                                     $resp->call("createDate","to_date");
	$content = "<input type=button onclick=\"xajax_debtto_equity_ratio(getElementById('from_date').value,getElementById('to_date').value);\" value='Show Graph'>".$link."<table height=200 border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center> <tr><td>";
	if($from_date == '' || $to_date == ''){
		$content .= "<font color=red>Please select the period</font></td></tr></table>";
		$resp->assign("display_div", "innerHTML", $content);
		return $resp;
	}
	$resp->call('xajax_generate_debtto_equity_ratio', $from_date,$to_date);
	$content .= "<img src=\"debtto_equity_ratio.jpg?dummy=".time()."\">";
	//unlink("coverage.");
	$content .= "</td></tr></table>";
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//GENERATE LIQUIDITY RATIO AGAINST TIME
function generate_debtto_equity_ratio($from_date,$to_date){

	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$to_date = $to_year ."-".$to_month."-31 23:59:59";
	//LOAN LOSS PROVISSIONS
	$next=0;
	$y_list ='';
	$x_list='';
	$from_days = $calc->dateToDays(28, $from_month, $from_year);
	$to_days = $calc->dateToDays(28, $to_month, $to_year);
	$diff = $to_days - $from_days;
	while($next <= $diff){
		$apparent_date = $calc->daysToDate($calc->dateToDays(28, $from_month, $from_year) + $next, '%Y-%m-28');
		preg_match("/(\d+)-(\d+)-28/", $apparent_date, $arr);
		$apparent_date = $calc->endOfMonthBySpan(0, $arr[2], $arr[1], '%Y-%m-%d');
		//LONG-TERM LOANS PAYABLE
		$payable_res = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '2122%' and p.maturity_date='".$apparent_date."'");
		$payable = @mysql_fetch_array($pay_res);
		$payable_amt = ($payable['amount'] != NULL) ? $payable['amount'] : 0;

		$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id join accounts a on p.account_id=a.id where a.account_no like '2%' and a.account_no not like '2122%' and p.maturity_date='".$apparent_date."' and paid.date <='".$apparent_date."'");
		$paid = @mysql_fetch_array($paid_res);
		$paid_amt = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
		$debt_amt = $payable_amt - $paid_amt;
		
		//CAPITAL
		$shares_res = mysql_query("select sum(value) as amount from shares where date <='".$apparent_date."'");
		$shares = mysql_fetch_array($shares_res);
		$shares_amt = ($shares['amount'] != NULL) ? $shares['amount'] : 0;

		$don_res = mysql_query("select sum(i.amount) as amount from other_income i join accounts a on i.account_id=a.id where a.account_no like '3313%' and i.date <= '".$apparent_date."'");
		$don = mysql_fetch_array($don_res);
		$don_amt = ($don['amount'] != NULL) ? $don['amount'] : 0;

		$equity_amt = $don_amt + $shares_amt;
		$equity_amt = ($equity_amt > 1) ? $equity_amt : 1;
		$percent = ($debt_amt / $equity_amt) * 100.00;
		$percent = sprintf("%.02f", $percent);

		if($y_list == '')
			$y_list = $percent;
		else
			$y_list = $y_list .",".$percent;
		$month_year = date('M', strtotime($apparent_date)) ."-". date('Y', strtotime($apparent_date));
		if($x_list == '')
			$x_list = $month_year;
		else
			$x_list = $x_list .",".$month_year; 
		$next = $next + 30;
	}

	// Create the basic graph
	$x_array = split(',', $x_list);
	$y_array = split(',', $y_list);
	
//CREATE GRAPH
	$graph = new Graph(900,600,'auto');
	$graph->SetScale("textlin");
	$graph->SetMarginColor("lightblue");
	$graph->SetShadow();
	$graph->img->SetMargin(40,80,30,80);
	$graph->img->SetImgFormat("jpeg");

	// Adjust the position of the legend box
	$graph->legend->Pos(0.02,0.15, "right", "center");

	// Adjust the color for theshadow of the legend
	$graph->legend->SetShadow('darkgray@0.5');
	$graph->legend->SetFillColor('lightblue@0.3');

	// Get localised version of the month names
	$graph->xaxis->SetTickLabels($x_array);

	
	// Set axis titles and fonts
	$graph->xaxis->title->Set("Months");
	$graph->title->SetFont(FF_VERDANA,FS_NORMAL,12);
	$graph->title->SetColor("darkred");


	$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,7);
	$graph->xaxis->SetColor('darkblue');
	$graph->xaxis->SetLabelAngle(70);

	$graph->yaxis->title->Set("Debt To Equity Ratio (%)");
	$graph->yaxis->SetFont(FF_FONT1,FS_BOLD);
	$graph->yaxis->SetColor('white');

	$graph->ygrid->SetColor('white@0.5');

	// Setup graph title
	$graph->title->Set("DEBT TO EQUITY RATIO PLOTTED AGAINST TIME");
	// Some extra margin (from the top)
	$graph->title->SetMargin(3);
	$graph->title->SetFont(FF_COMIC,FS_NORMAL,12);

	
	// Create the first line
	$p1 = new LinePlot($y_array);
	$p1->mark->SetType(MARK_FILLEDCIRCLE);
	$p1->mark->SetFillColor("red");
	$p1->mark->SetWidth(4);
	$p1->SetColor("blue");
	$p1->SetCenter();
	$p1->SetLegend("Debt To Equity Ratio (%)");
	$graph->Add($p1);
	$graph->Stroke("debtto_equity_ratio.jpg");
	return $resp;
}

//DISPLAY GRAPH OF OPERATING SELF SUFFICIENCY AGAINST TIME IN MONTHS
function oper_sufficiency($from_date,$to_date){

	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$link = ($from_year == '') ? "" : "<a href='export/oper_sufficiency' target=blank()><b>Printable Version</b></a>";
	
	$content='<div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>';
                                            $resp->call("createDate","from_date");
	$resp->call("createDate","to_date");
	$content ="<input type=button onclick=\"xajax_oper_sufficiency(getElementById('from_date').value, getElementById('to_date').value);\" value='Show Graph'>".$link."<table height=200 border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center> <tr><td>";
	if($from_date == '' || $to_date == ''){
		$content .= "<font color=red>Please select the period</font></td></tr></table>";
		$resp->assign("display_div", "innerHTML", $content);
		return $resp;
	}
	$resp->call('xajax_generate_oper_sufficiency', $from_date,$to_date);
	$content .= "<img src=\"oper_sufficiency.jpg?dummy=".rand()."\">";
	//unlink("coverage.");
	$content .= "</td></tr></table>";
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


//GENERATE OPERATIONAL SELF SUFFICIENCY
function generate_oper_sufficiency($from_date, $to_date){
list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$to_date = $to_year ."-".$to_month."-31 23:59:59";
	//LOAN LOSS PROVISSIONS
	$next=0;
	$y_list ='';
	$x_list='';
	$from_days = $calc->dateToDays(28, $from_month, $from_year);
	$to_days = $calc->dateToDays(28, $to_month, $to_year);
	$diff = $to_days - $from_days;
	while($next <= $diff){
		$apparent_date = $calc->daysToDate($calc->dateToDays(28, $from_month, $from_year) + $next, '%Y-%m-28');
		preg_match("/(\d+)-(\d+)-28/", $apparent_date, $arr);
		$apparent_date = $calc->endOfMonthBySpan(0, $arr[2], $arr[1], '%Y-%m-%d');
//OPERATING INCOME
		//INCOME FROM LOANS
		$int_res = mysql_query("select sum(int_amt) as amount from payment where date <='".$apparent_date."'");
		$int = mysql_fetch_array($int_res);
		$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;

		//PENALITIES
		$pen_res = mysql_query("select sum(amount) as amount from penalty where date <='".$apparent_date."'");
		$pen = mysql_fetch_array($pen_res);
		$pen_amt = ($pen['amount'] != NULL) ? $pen['amount'] : 0;

		//OTHER INCOME
		$other_res = mysql_query("select sum(amount) as amount from other_income where date <= '".$apparent_date."'");
		$other = mysql_fetch_array($other_res);
		$other_amt = ($other['amount'] != NULL);
		
		//TRANSACTIONAL CHARGES
		$dep_res = mysql_query("select sum(flat_value + percent_value) as charge from deposit where date <='".$apparent_date."'");
		$dep = mysql_fetch_array($dep_res);
		$dep_charge = ($dep['charge'] != NULL) ? $dep['charge'] : 0;
		$with_res = mysql_query("select sum(flat_value + percent_value) as charge from withdrawal where date <='".$apparent_date."'");
		$with = mysql_fetch_array($with_res);
		$with_charge = ($with['charge'] != NULL) ? $with['charge'] : 0;

		$charge_res = mysql_query("select sum(amount) as amount from monthly_charge where date <='".$last_date."'");
		$charge = mysql_fetch_array($charge_res);
		$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;

		$income = $int_amt + $pen_amt + $other_amt + $dep_charge + $with_charge + $charge_amt;
		
//OPERATING EXPENSES
		$expense_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where date <= '".$apparent_date."'");
		$expense = mysql_fetch_array($expense_res);
		$expense_amt = ($expense['amount'] != NULL) ? $expense['amount'] : 1;


		$percent = ($income / $expense_amt) * 100.00;
		$percent = sprintf("%.02f", $percent);
		if($y_list == '')
			$y_list = $percent;
		else
			$y_list = $y_list .",".$percent;
		$month_year = date('M', strtotime($apparent_date)) ."-". date('Y', strtotime($apparent_date));
		if($x_list == '')
			$x_list = $month_year;
		else
			$x_list = $x_list .",".$month_year; 
		$next = $next + 30;
	}

	// Create the basic graph
	$x_array = split(',', $x_list);
	$y_array = split(',', $y_list);
	//print_r($x_array."<br>".$y_array);
	//exit();
//CREATE GRAPH
	$graph = new Graph(900,600,'auto');
	$graph->SetScale("textlin");
	$graph->SetMarginColor("lightblue");
	$graph->SetShadow();
	$graph->img->SetMargin(90,80,80,40);
	$graph->img->SetImgFormat("jpeg");

	// Adjust the position of the legend box
	$graph->legend->Pos(0.02,0.15, "right", "center");

	// Adjust the color for theshadow of the legend
	$graph->legend->SetShadow('darkgray@0.5');
	$graph->legend->SetFillColor('lightblue@0.3');

	// Get localised version of the month names
	$graph->xaxis->SetTickLabels($x_array);

	// Set a nice summer (in Stockholm) image
	//$graph->SetBackgroundImage('stship.jpg',BGIMG_COPY);

	// Set axis titles and fonts
	$graph->xaxis->title->Set("Months");
	//$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
	//$graph->xaxis->title->SetColor('white');
	$graph->title->SetFont(FF_VERDANA,FS_NORMAL,12);
	$graph->title->SetColor("darkred");


	$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,7);
	$graph->xaxis->SetColor('darkblue');
	$graph->xaxis->SetLabelAngle(70);

	$graph->yaxis->title->Set("Operational Self Sufficiency (%)");
	$graph->yaxis->SetFont(FF_FONT1,FS_BOLD);
	$graph->yaxis->SetColor('white');

	//$graph->ygrid->Show(false);
	$graph->ygrid->SetColor('white@0.5');

	// Setup graph title
	$graph->title->Set("OPERATIONAL SELF SUFFICIENCY PLOTTED AGAINST TIME");
	// Some extra margin (from the top)
	$graph->title->SetMargin(3);
	$graph->title->SetFont(FF_COMIC,FS_NORMAL,12);

	
	// Create the first line
	$p1 = new LinePlot($y_array);
	$p1->mark->SetType(MARK_FILLEDCIRCLE);
	$p1->mark->SetFillColor("red");
	$p1->mark->SetWidth(4);
	$p1->SetColor("blue");
	$p1->SetCenter();
	$p1->SetLegend("Operational Self-Sufficiency (%)");
	$graph->Add($p1);
	$graph->Stroke("oper_sufficiency.jpg");
	return $resp;
}

//DISPLAY GRAPH OF OPERATING EXPENSE RATIO AGAINST TIME
function oper_expense_ratio($from_date,$to_date){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$link = ($from_year == '') ? "" : "<a href='export/oper_expense' target=blank()><b>Printable Version</b></a>";
	
	$content ='<div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>';
                                             $resp->call("createDate","from_date");
	$resp->call("createDate","to_date"); 
	$content .= "<input type=button onclick=\"xajax_oper_expense_ratio(getElementById('from_date').value, getElementById('to_date').value);\" value='Show Graph'>".$link."<table height=200 border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center><tr><td>";
	if($from_date == '' || $to_date == ''){
		$content .= "<font color=red>Please select the period</font></td></tr></table>";
		$resp->assign("display_div", "innerHTML", $content);
		return $resp;
	}
	$resp->call('xajax_generate_oper_expense_ratio', $from_date,$to_date);
	$content .= "<img src=\"expense_ratio.jpg?dummy=".time()."\">";
	//unlink("coverage.");
	$content .= "</td></tr></table>";
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


//GENERATE OPERATIONAL SELF SUFFICIENCY
function generate_oper_expense_ratio($from_date,$to_date){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$to_date = $to_year ."-".$to_month."-31 23:59:59";
	//LOAN LOSS PROVISSIONS
	$next=0;
	$y_list ='';
	$x_list='';
	$from_days = $calc->dateToDays(28, $from_month, $from_year);
	$to_days = $calc->dateToDays(28, $to_month, $to_year);
	$diff = $to_days - $from_days;
	while($next <= $diff){
		$apparent_date = $calc->daysToDate($calc->dateToDays(28, $from_month, $from_year) + $next, '%Y-%m-28');
		preg_match("/(\d+)-(\d+)-28/", $apparent_date, $arr);
		$apparent_date = $calc->endOfMonthBySpan(0, $arr[2], $arr[1], '%Y-%m-%d');
//OPERATING INCOME
		//INCOME FROM LOANS
		$int_res = mysql_query("select sum(int_amt) as amount from payment where date <='".$apparent_date."'");
		$int = mysql_fetch_array($int_res);
		$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;

		//PENALITIES
		$pen_res = mysql_query("select sum(amount) as amount from penalty where date <='".$apparent_date."'");
		$pen = mysql_fetch_array($pen_res);
		$pen_amt = ($pen['amount'] != NULL) ? $pen['amount'] : 0;

		//OTHER INCOME
		$other_res = mysql_query("select sum(amount) as amount from other_income where date <= '".$apparent_date."'");
		$other = mysql_fetch_array($other_res);
		$other_amt = ($other['amount'] != NULL);
		
		//TRANSACTIONAL CHARGES
		$dep_res = mysql_query("select sum(flat_value + percent_value) as charge from deposit where date <='".$apparent_date."'");
		$dep = mysql_fetch_array($dep_res);
		$dep_charge = ($dep['charge'] != NULL) ? $dep['charge'] : 0;
		$with_res = mysql_query("select sum(flat_value + percent_value) as charge from withdrawal where date <='".$apparent_date."'");
		$with = mysql_fetch_array($with_res);
		$with_charge = ($with['charge'] != NULL) ? $with['charge'] : 0;

		$charge_res = mysql_query("select sum(amount) as amount from monthly_charge where date <='".$last_date."'");
		$charge = mysql_fetch_array($charge_res);
		$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;

		$income = $int_amt + $pen_amt + $other_amt + $dep_charge + $with_charge + $charge_amt;
		$income_amt = ($income != 0) ? $income : 1;
		
//OPERATING EXPENSES
		$expense_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where date <= '".$apparent_date."'");
		$expense = mysql_fetch_array($expense_res);
		$expense_amt = ($expense['amount'] != NULL) ? $expense['amount'] : 0;
		
		$percent = ($expense_amt / $income_amt) * 100.00;
		$percent = sprintf("%.02f", $percent);
		if($y_list == '')
			$y_list = $percent;
		else
			$y_list = $y_list .",".$percent;
		$month_year = date('M', strtotime($apparent_date)) ."-". date('Y', strtotime($apparent_date));
		if($x_list == '')
			$x_list = $month_year;
		else
			$x_list = $x_list .",".$month_year; 
		$next = $next + 30;
	}

	// Create the basic graph
	$x_array = split(',', $x_list);
	$y_array = split(',', $y_list);
	//print_r($x_array."<br>".$y_array);
	//exit();
//CREATE GRAPH
	$graph = new Graph(900,600,'auto');
	$graph->SetScale("textlin");
	$graph->SetMarginColor("lightblue");
	$graph->SetShadow();
	$graph->img->SetMargin(90,80,40,80);
	$graph->img->SetImgFormat("jpeg");

	// Adjust the position of the legend box
	$graph->legend->Pos(0.02,0.15, "right", "center");

	// Adjust the color for theshadow of the legend
	$graph->legend->SetShadow('darkgray@0.5');
	$graph->legend->SetFillColor('lightblue@0.3');

	// Get localised version of the month names
	$graph->xaxis->SetTickLabels($x_array);

	// Set a nice summer (in Stockholm) image
	//$graph->SetBackgroundImage('stship.jpg',BGIMG_COPY);

	// Set axis titles and fonts
	$graph->xaxis->title->Set("Months");
	//$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
	//$graph->xaxis->title->SetColor('white');
	$graph->title->SetFont(FF_VERDANA,FS_NORMAL,12);
	$graph->title->SetColor("darkred");


	$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,7);
	$graph->xaxis->SetColor('darkblue');
	$graph->xaxis->SetLabelAngle(70);

	$graph->yaxis->title->Set("Operating Expense Ratio (%)");
	$graph->yaxis->SetFont(FF_FONT1,FS_BOLD);
	$graph->yaxis->SetColor('white');

	//$graph->ygrid->Show(false);
	$graph->ygrid->SetColor('white@0.5');

	// Setup graph title
	$graph->title->Set("OPERATING EXPENSE RATIO PLOTTED AGAINST TIME");
	// Some extra margin (from the top)
	$graph->title->SetMargin(3);
	$graph->title->SetFont(FF_COMIC,FS_NORMAL,12);

	
	// Create the first line
	$p1 = new LinePlot($y_array);
	$p1->mark->SetType(MARK_FILLEDCIRCLE);
	$p1->mark->SetFillColor("red");
	$p1->mark->SetWidth(4);
	$p1->SetColor("blue");
	$p1->SetCenter();
	$p1->SetLegend("Operating Expense Ratio (%)");
	$graph->Add($p1);
	$graph->Stroke("expense_ratio.jpg");
	return $resp;
}


//DISPLAY GRAPH OF FINANCIAL SELF SUFFICIENCY AGAINST TIME IN MONTHS
function fin_sufficiency($from_date, $to_date){
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
		$link = ($from_year == '') ? "" : "<a href='export/fin_sufficiency' target=blank()><b>Printable Version</b></a>";
		$content = '<div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>';
                                            $resp->call("createDate","from_date");
	$resp->call("createDate","to_date"); 
	$content .="<input type=button onclick=\"xajax_fin_sufficiency(getElementById('from_date').value, getElementById('to_date').value);\" value='Show Graph'>".$link."<table height=200 border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center> <tr><td>";
	if($from_date == '' || $to_date == ''){
		$content .= "<font color=red>Please select the period</font></td></tr></table>";
		$resp->assign("display_div", "innerHTML", $content);
		return $resp;
	}
	$resp->call('xajax_generate_fin_sufficiency', $from_date,$to_date);
	$content .= "<img src=\"fin_sufficiency.jpg?dummy=".rand()."\">";
	//unlink("coverage.");
	$content .= "</td></tr></table>";
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


//GENERATE OPERATIONAL SELF SUFFICIENCY
function generate_fin_sufficiency($from_date,$to_date){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$to_date = $to_year ."-".$to_month."-31 23:59:59";
	//LOAN LOSS PROVISSIONS
	$next=0;
	$y_list ='';
	$x_list='';
	$from_days = $calc->dateToDays(28, $from_month, $from_year);
	$to_days = $calc->dateToDays(28, $to_month, $to_year);
	$diff = $to_days - $from_days;
	while($next <= $diff){
		$apparent_date = $calc->daysToDate($calc->dateToDays(28, $from_month, $from_year) + $next, '%Y-%m-28');
		preg_match("/(\d+)-(\d+)-28/", $apparent_date, $arr);
		$apparent_date = $calc->endOfMonthBySpan(0, $arr[2], $arr[1], '%Y-%m-%d');
//OPERATING INCOME
		//INCOME FROM LOANS
		$int_res = mysql_query("select sum(int_amt) as amount from payment where date <='".$apparent_date."'");
		$int = mysql_fetch_array($int_res);
		$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;
	//	$resp->alert($int_amt);
		//PENALITIES
		$pen_res = mysql_query("select sum(amount) as amount from penalty where date <='".$apparent_date."'");
		$pen = mysql_fetch_array($pen_res);
		$pen_amt = ($pen['amount'] != NULL) ? $pen['amount'] : 0;

		//OTHER INCOME
		$other_res = mysql_query("select sum(i.amount) as amount from other_income i join accounts a on i.account_id=a.id where i.date <= '".$apparent_date."'");
		$other = mysql_fetch_array($other_res);
		$other_amt = ($other['amount'] != NULL) ? $other['amount'] : 0;

		//COLLECTED
		$col_res = mysql_query("select sum(amount) as amount from collected where date <='".$apparent_date."'");
		$col = mysql_fetch_array($col_res);
		$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
	
		//RECOVERED
		$recovered_res = mysql_query("select sum(amount) as amount from recovered where date <= '".$apparent_date."'");
		$recovered = mysql_fetch_array($recovered_res);
		$recovered_amt = ($recovered['amount'] != NULL) ? $recovered['amount'] : 0;

		//TRANSACTIONAL CHARGES
		$dep_res = mysql_query("select sum(flat_value + percent_value) as charge from deposit where date <='".$apparent_date."'");
		$dep = mysql_fetch_array($dep_res);
		$dep_charge = ($dep['charge'] != NULL) ? $dep['charge'] : 0;
		$with_res = mysql_query("select sum(flat_value + percent_value) as charge from withdrawal where date <='".$apparent_date."'");
		$with = mysql_fetch_array($with_res);
		$with_charge = ($with['charge'] != NULL) ? $with['charge'] : 0;

		//GAIN ON SALE OF FIXED ASSETS
		$fixed_res = mysql_query("select sum(s.amount - f.initial_value) as amount from fixed_asset f join sold_asset s on f.id=s.asset_id where f.date <='".$apparent_date."' and s.id in (select asset_id from sold_asset where date <='".$apparent_date."')");
		$fixed = mysql_fetch_array($fixed_res);
		$fixed_amt =($fixed['amount'] != NULL) ? $fixed['amount'] : 0;
		
		//GAIN ON SALE OF INVESTMENTS
		$loss = mysql_fetch_array(mysql_query("select sum(quantity * amount) as amount from investments where date <= '".$apparent_date."'"));
		$loss_amt = ($loss['amount'] == NULL) ? 0 : $loss['amount'];

		$gain = mysql_fetch_array(mysql_query("select sum(quantity * amount) as amount from sold_invest where date <= '".$apparent_date."'"));
		$gain_amt = ($gain['amount'] == NULL) ? 0 : $gain['amount'];
		$invest_amt = $gain_amt - $loss_amt;
		
		//$invest_res = mysql_query("select sum(s.amount - i.amount) as amount from investments i join sold_invest s on i.id=s.investment_id where i.date <='".$apparent_date."' and i.id in (select investment_id from sold_invest where date <='".$apparent_date."')");
		//$invest = mysql_fetch_array($invest_res);
		//$invest_amt =($invest['amount'] != NULL) ? $invest['amount'] : 0;

		$charge_res = mysql_query("select sum(amount) as amount from monthly_charge where date <='".$apparent_date."'");
		$charge = mysql_fetch_array($charge_res);
		$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
		
		$income = $int_amt + $pen_amt + $other_amt + $recovered_amt + $col_amt + $fixed_amt + $invest_amt + $dep_charge + $with_charge + $charge_amt;
		
		
//OPERATING EXPENSES
		$expense_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where date <= '".$apparent_date."'");
		$expense = mysql_fetch_array($expense_res);
		$expense_amt = ($expense['amount'] != NULL) ? $expense['amount'] : 1;

		$payable_res = mysql_query("select sum(amount) as amount from payable_paid where date <'".$apparent_date."'");
		$payable = mysql_fetch_array($payable_res);
		$payable_amt = ($payable['amount'] != NULL) ? $payable['amount'] : 0;

		$shared_res = mysql_query("select sum(total_amount) as amount from share_dividends where date <='".$apparent_date."'");
		$shared = mysql_fetch_array($shared_res);
		$shared_amt = ($shared['amount'] != NULL) ? $shared['amount'] : 0;

		$written_res = mysql_query("select sum(amount) as amount from written_off where date <='".$apparent_date."'");
		$written = mysql_fetch_array($written_res);
		$written_amt = ($written['amount'] != NULL) ? $written['amount'] : 0;
		$expense = $expense_amt + $payable_amt + $shared_amt + $written_amt;
		$expense = ($expense != 0) ? $expense : 1;

		$percent = ($income / $expense) * 100.00;
		$percent = sprintf("%.02f", $percent);
		if($y_list == '')
			$y_list = $percent;
		else
			$y_list = $y_list .",".$percent;
		$month_year = date('M', strtotime($apparent_date)) ."-". date('Y', strtotime($apparent_date));
		if($x_list == '')
			$x_list = $month_year;
		else
			$x_list = $x_list .",".$month_year; 
		$next = $next + 30;
	}

	// Create the basic graph
	$x_array = split(',', $x_list);
	$y_array = split(',', $y_list);
	//print_r($x_array."<br>".$y_array);
	//exit();
//CREATE GRAPH
	$graph = new Graph(900,600,'auto');
	$graph->SetScale("textlin");
	$graph->SetMarginColor("lightblue");
	$graph->SetShadow();
	$graph->img->SetMargin(80,30,30,80);
	$graph->img->SetImgFormat("jpeg");

	// Adjust the position of the legend box
	$graph->legend->Pos(0.02,0.15, "right", "center");

	// Adjust the color for theshadow of the legend
	$graph->legend->SetShadow('darkgray@0.5');
	$graph->legend->SetFillColor('lightblue@0.3');

	// Get localised version of the month names
	$graph->xaxis->SetTickLabels($x_array);

	// Set a nice summer (in Stockholm) image
	//$graph->SetBackgroundImage('stship.jpg',BGIMG_COPY);

	// Set axis titles and fonts
	$graph->xaxis->title->Set("Months");
	//$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
	//$graph->xaxis->title->SetColor('white');
	$graph->title->SetFont(FF_VERDANA,FS_NORMAL,12);
	$graph->title->SetColor("darkred");


	$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,7);
	$graph->xaxis->SetColor('darkblue');
	$graph->xaxis->SetLabelAngle(70);

	$graph->yaxis->title->Set("Financial Self Sufficiency (%)");
	$graph->yaxis->SetFont(FF_FONT1,FS_BOLD);
	$graph->yaxis->SetColor('white');

	//$graph->ygrid->Show(false);
	$graph->ygrid->SetColor('white@0.5');

	// Setup graph title
	$graph->title->Set("FINANCIAL SELF SUFFICIENCY PLOTTED AGAINST TIME");
	// Some extra margin (from the top)
	$graph->title->SetMargin(3);
	$graph->title->SetFont(FF_COMIC,FS_NORMAL,12);

	
	// Create the first line
	$p1 = new LinePlot($y_array);
	$p1->mark->SetType(MARK_FILLEDCIRCLE);
	$p1->mark->SetFillColor("red");
	$p1->mark->SetWidth(4);
	$p1->SetColor("blue");
	$p1->SetCenter();
	$p1->SetLegend("Financial Self-Sufficiency (%)");
	$graph->Add($p1);
	$graph->Stroke("fin_sufficiency.jpg");
	return $resp;
}


//DISPLAY GRAPH OF LIQUIDITY RATIO AGAINST TIME IN MONTHS
function liquidity_ratio($from_date, $to_date){
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$link = ($from_year == '') ? "" : "<a href='export/liquidity_ratio' target=blank()><b>Printable Version</b></a>";
	
	$content = '<div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>';
                                            $resp->call("createDate","from_date");
	$resp->call("createDate","to_date"); 
	$content .= "<input type=button onclick=\"xajax_liquidity_ratio(getElementById('from_date').value,getElementById('to_date').value);\" value='Show Graph'>".$link."<table height=200 border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center><tr><td>";
	if($from_date == '' || $to_date == ''){
		$content .= "<font color=red>Please select the period</font></td></tr></table>";
		$resp->assign("display_div", "innerHTML", $content);
		return $resp;
	}
	$resp->call('xajax_generate_liquidity_ratio', $from_date, $to_date);
	$content .= "<img src=\"liquidity_ratio.jpg?dummy=".time()."\">";
	//unlink("coverage.");
	$content .= "</td></tr></table>";
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


//GENERATE LIQUIDITY RATIO AGAINST TIME
function generate_liquidity_ratio($from_date,$to_date){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$to_date = $to_year ."-".$to_month."-31 23:59:59";
	//LOAN LOSS PROVISSIONS
	$next=0;
	$y_list ='';
	$x_list='';
	$from_days = $calc->dateToDays(28, $from_month, $from_year);
	$to_days = $calc->dateToDays(28, $to_month, $to_year);
	$diff = $to_days - $from_days;
	while($next <= $diff){
		$apparent_date = $calc->daysToDate($calc->dateToDays(28, $from_month, $from_year) + $next, '%Y-%m-28');
		preg_match("/(\d+)-(\d+)-28/", $apparent_date, $arr);
		$apparent_date = $calc->endOfMonthBySpan(0, $arr[2], $arr[1], '%Y-%m-%d');
		//CASH
		//DEPOSITS
		$dep_res = mysql_query("select sum(amount) as amount from deposit where  date <='".$apparent_date."' and bank_account >0");
		$dep = mysql_fetch_array($dep_res);
		//WITHDRAWALS
		$with_res = mysql_query("select sum(amount) as amount from withdrawal where  date <='".$apparent_date."'");
		$with = mysql_fetch_array($with_res);
		//OTHER INCOME
		$other_res = mysql_query("select sum(amount) as amount from other_income where  date <='".$apparent_date."'");
		$other = mysql_fetch_array($other_res);
		//EXPENSES
		$expense_res = mysql_query("select sum(amount) as amount from expense where  date <='".$apparent_date."'");
		$expense = mysql_fetch_array($expense_res);
		//PAYABLE PAID
		$payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where  date <='".$apparent_date."'");	
		$payable_paid = mysql_fetch_array($payable_paid_res);
		//RECEIVALE COLLECTED
		$collected_res = mysql_query("select sum(amount) as amount from collected where  date <='".$apparent_date."'");
		$collected = mysql_fetch_array($collected_res);
		//DISBURSED LOANS
		$disb_res = mysql_query("select sum(amount) as amount from disbursed where  date <= '".$apparent_date."'");
		$disb = mysql_fetch_array($disb_res);
		//PAYMENTS
		$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id  where p.date <= '".$apparent_date."'");
		$pay = mysql_fetch_array($pay_res);
		//PENALTIES
		$pen_res = mysql_query("select sum(p.amount) as amount from penalty p join disbursed d on p.loan_id=d.id where  p.status='paid' and p.date <= '".$apparent_date."'");
		$pen = mysql_fetch_array($pen_res);
								
		//SHARES
		$shares_res = mysql_query("select sum(value) as amount from shares where date <'".$apparent_date."'");
		$shares = mysql_fetch_array($shares_res); 
		//RECOVERED
		$rec_res = mysql_query("select sum(r.amount) as amount from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where  r.date <= '".$apparent_date."'");
		$rec = mysql_fetch_array($rec_res);		
		//INVESTMENTS 
		$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where date <= '".$apparent_date."'");
		$invest = mysql_fetch_array($invest_res);
		$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
		$soldinvest_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where date <='".$apparent_date."'");
		$soldinvest = mysql_fetch_array($soldinvest_res);

		//FIXED ASSETS 
		$fixed_res = mysql_query("select sum(initial_value) as amount from fixed_asset where  date <='".$apparent_date."'");
		$fixed = mysql_fetch_array($fixed_res);
		$soldasset_res = mysql_query("select sum(amount) as amount from sold_asset where date <='".$apparent_date."'");
		$soldasset = mysql_fetch_array($soldasset_res);
									
		$cash_amt =  $collected['amount'] + $dep['amount'] + $other['amount'] - $with['amount'] - $expense['amount'] -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'];	

		//LIQUID INVESTMENTS
		$invest_res = mysql_query("select sum(i.amount) as amount from investments i join accounts a on i.account_id=a.id where i.date < '".$apparent_date."' and i.id not in (select investment_id from sold_invest) and a.account_no like '113%'");
		$invest = mysql_fetch_array($invest_res);
		$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
		$liquid_amt = $cash_amt + $invest_amt;
		

		//CURRENT LIABILITIES
		//SAVINGS
		$dep_res = mysql_query("select sum(amount - flat_value - percent_value) as amount from deposit where  date <='".$apparent_date."' and bank_account >0");
		$dep = mysql_fetch_array($dep_res);
		$dep_amt = ($dep['amount'] != NULL) ? $dep['amount'] : 0;
		$with_res = mysql_query("select sum(amount + flat_value + percent_value) as amount from withdrawal where  date <='".$apparent_date."'");
		$with = mysql_fetch_array($with_res);
		$with_amt = ($with['amount'] != NULL) ? $with['amount'] : 0;
		$savings = $dep_amt - $with_amt;
		//INTEREST PAYABLE ON SAVINGS
		$int_res = mysql_query("select sum(amount) as amount from save_interest where date <= '".$apparent_date."'");
		$int = mysql_fetch_array($int_res);
		$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;
		//OTHER SHORT-TERM LIABILITIES
		$payable_res = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '2%' and a.account_no not like '2122%' and p.maturity_date='".$apparent_date."'");
		$payable = mysql_fetch_array($pay_res);
		$payable_amt = ($payable['amount'] != NULL) ? $payable['amount'] : 0;

		$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id join accounts a on p.account_id=a.id where a.account_no like '2%' and a.account_no not like '2122%' and p.maturity_date='".$apparent_date."' and paid.date <='".$apparent_date."'");
		
		$paid = @mysql_fetch_array($paid_res);
		$paid_amt = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
		$payable_amt = $payable_amt - $paid_amt;
		$liabilities = $payable_amt + $savings + $int_amt;
		$liabilities = ($liabilities > 0) ? $liabilities  : 1;

		$percent = ($liquid_amt / $liabilities) * 100.00;
		$percent = floor($percent);

		if($y_list == '')
			$y_list = $percent;
		else
			$y_list = $y_list .",".$percent;
		$month_year = date('M', strtotime($apparent_date)) ."-". date('Y', strtotime($apparent_date));
		if($x_list == '')
			$x_list = $month_year;
		else
			$x_list = $x_list .",".$month_year; 
		$next = $next + 30;
	}

	// Create the basic graph
	$x_array = split(',', $x_list);
	$y_array = split(',', $y_list);
	//print_r($x_array."<br>".$y_array);
	//exit();
//CREATE GRAPH
	$graph = new Graph(900,600,'auto');
	$graph->SetScale("textlin");
	$graph->SetMarginColor("lightblue");
	$graph->SetShadow();
	$graph->img->SetMargin(40,80,30,80);
	$graph->img->SetImgFormat("jpeg");

	// Adjust the position of the legend box
	$graph->legend->Pos(0.02,0.15, "right", "center");

	// Adjust the color for theshadow of the legend
	$graph->legend->SetShadow('darkgray@0.5');
	$graph->legend->SetFillColor('lightblue@0.3');

	// Get localised version of the month names
	$graph->xaxis->SetTickLabels($x_array);

	// Set a nice summer (in Stockholm) image
	//$graph->SetBackgroundImage('stship.jpg',BGIMG_COPY);

	// Set axis titles and fonts
	$graph->xaxis->title->Set("Months");
	//$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
	//$graph->xaxis->title->SetColor('white');
	$graph->title->SetFont(FF_VERDANA,FS_NORMAL,12);
	$graph->title->SetColor("darkred");


	$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,7);
	$graph->xaxis->SetColor('darkblue');
	$graph->xaxis->SetLabelAngle(70);

	$graph->yaxis->title->Set("Liquidity Ratio (%)");
	$graph->yaxis->SetFont(FF_FONT1,FS_BOLD);
	$graph->yaxis->SetColor('white');

	//$graph->ygrid->Show(false);
	$graph->ygrid->SetColor('white@0.5');

	// Setup graph title
	$graph->title->Set("LIQUIDITY RATIO PLOTTED AGAINST TIME");
	// Some extra margin (from the top)
	$graph->title->SetMargin(3);
	$graph->title->SetFont(FF_COMIC,FS_NORMAL,12);

	
	// Create the first line
	$p1 = new LinePlot($y_array);
	$p1->mark->SetType(MARK_FILLEDCIRCLE);
	$p1->mark->SetFillColor("red");
	$p1->mark->SetWidth(4);
	$p1->SetColor("blue");
	$p1->SetCenter();
	$p1->SetLegend("Liquidity Ratio (%)");
	$graph->Add($p1);
	$graph->Stroke("liquidity_ratio.jpg");
	return $resp;
}




//DISPLAY GRAPH OF EFFECTIVE REPAYMENT RATE AGAINST TIME IN MONTHS
function repay_ratio($from_date, $to_date){
        list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$link = ($from_year == '') ? "" : "<a href='export/repay_ratio' target=blank()><b>Printable Version</b></a>";
	
	$content='<div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>';
                                            $resp->call("createDate","from_date");
					   $resp->call("createDate","to_date");
                                            
	$content .= "<input type=button onclick=\"xajax_repay_ratio(getElementById('from_date').value, getElementById('to_date').value);\" value='Show Graph'>".$link."<table height=200 border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center> <tr><td>";
	if($from_date == '' || $to_date == ''){
		$cont = "<font color=red>Please select the period</font></td></tr></table>";
		$resp->assign("status", "innerHTML", $cont);
		return $resp;
	}
	$resp->call('xajax_generate_repay_ratio', $from_date, $to_date);
	$content .= "<img src=\"repay_ratio.jpg?dummy=".time()."\">";
	//unlink("coverage.");
	$content .= "</td></tr></table>";
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


//GENERATE PORTFOLIO AT RISK RATIO
function generate_repay_ratio($from_date,$to_date){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$to_date = $to_year ."-".$to_month."-31 23:59:59";
	//LOAN LOSS PROVISSIONS
	$next=0;
	$y_list ='';
	$x_list='';
	$from_days = $calc->dateToDays(28, $from_month, $from_year);
	$to_days = $calc->dateToDays(28, $to_month, $to_year);
	$diff = $to_days - $from_days;
	while($next <= $diff){
		$apparent_date = $calc->daysToDate($calc->dateToDays(28, $from_month, $from_year) + $next, '%Y-%m-28');
		preg_match("/(\d+)-(\d+)-28/", $apparent_date, $arr);
		$apparent_date = $calc->endOfMonthBySpan(0, $arr[2], $arr[1], '%Y-%m-%d');
		//CALCULATE CUMMULATED REPAYMENT RATE
		$sched_res = mysql_query("select  sum(s.princ_amt) as princ_amt from schedule s join disbursed d on s.loan_id=d.id left join written_off w on d.id=w.loan_id where (w.date >='".$apparent_date."' or w.date is null) and s.date <= '".$apparent_date."'");
		$sched = mysql_fetch_array($sched_res);
		$sched_amt = ($sched['princ_amt']!= NULL) ? $sched['princ_amt'] : 1;
		$paid_res = mysql_query("select sum(p.princ_amt) as princ_amt from payment p join disbursed d on p.loan_id=d.id left join written_off w on d.id=w.loan_id where (w.date >='".$apparent_date."' or w.date is null) and p.date <= '".$apparent_date."'");
		$paid = mysql_fetch_array($paid_res);
		$paid_amt = ($paid['princ_amt'] != NULL) ? $paid['princ_amt'] : 0;
		
		//CALCULATE REPAYMENT RATE  FOR THIS MONTH
		$nowsched_res = mysql_query("select  sum(s.princ_amt) as princ_amt from schedule s join disbursed d on s.loan_id=d.id left join written_off w on d.id=w.loan_id where (w.date >='".$apparent_date."' or w.date is null) and s.date like '".$arr[1]."-".$arr[2]."-%' ");
		$nowsched = mysql_fetch_array($nowsched_res);
		$nowsched_amt = ($sched['princ_amt']!= NULL) ? $nowsched['princ_amt'] : 1;
		$nowpaid_res = mysql_query("select sum(p.princ_amt) as princ_amt from payment p join disbursed d on p.loan_id=d.id left join written_off w on d.id=w.loan_id where (w.date >='".$apparent_date."' or w.date is null) and p.date like '".$arr[1]."-".$arr[2]."-%'");
		$nowpaid = mysql_fetch_array($nowpaid_res);
		$nowpaid_amt = ($nowpaid['princ_amt'] != NULL) ? $nowpaid['princ_amt'] : 0;
		

		$percent = ($paid_amt / $sched_amt) * 100.00;
		$nowpercent = ($nowpaid_amt / $nowsched_amt) * 100.00;
		$percent = sprintf("%.02f", $percent);
		$nowpercent = sprintf("%.02f", $nowpercent);
		if($y_list == ''){
			$y_list = $percent;
			$nowy_list = $nowpercent;
		}else{
			$y_list = $y_list .",".$percent;
			$nowy_list = $nowy_list .",".$nowpercent;
		}
		$month_year = date('M', strtotime($apparent_date)) ." ". date('Y', strtotime($apparent_date));
		if($x_list == '')
			$x_list = $month_year;
		else
			$x_list = $x_list .",".$month_year; 
		$next = $next + 30;
	}

	// Create the basic graph
	$x_array = split(',', $x_list);
	$y_array = split(',', $y_list);
	$nowy_array = split(',', $nowy_list);
	//print_r($x_array."<br>".$y_array);
	//exit();
//CREATE GRAPH
	$graph = new Graph(900,600,'auto');
	$graph->SetScale("textlin");
	$graph->SetMarginColor("lightblue");
	$graph->SetShadow();
	$graph->img->SetMargin(40,80,30,80);
	$graph->img->SetImgFormat("jpeg");

	// Adjust the position of the legend box
	$graph->legend->Pos(0.02,0.15, "right", "center");

	// Adjust the color for theshadow of the legend
	$graph->legend->SetShadow('darkgray@0.5');
	$graph->legend->SetFillColor('lightblue@0.3');

	// Get localised version of the month names
	$graph->xaxis->SetTickLabels($x_array);

	// Set a nice summer (in Stockholm) image
	//$graph->SetBackgroundImage('stship.jpg',BGIMG_COPY);

	// Set axis titles and fonts
	$graph->xaxis->title->Set("Months");
	//$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
	//$graph->xaxis->title->SetColor('white');
	$graph->title->SetFont(FF_VERDANA,FS_NORMAL,12);
	$graph->title->SetColor("darkred");


	$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,7);
	$graph->xaxis->SetColor('darkblue');
	$graph->xaxis->SetLabelAngle(70);

	$graph->yaxis->title->Set("Repayment Rate (%)");
	$graph->yaxis->SetFont(FF_FONT1,FS_BOLD);
	$graph->yaxis->SetColor('white');

	//$graph->ygrid->Show(false);
	$graph->ygrid->SetColor('white@0.5');

	// Setup graph title
	$graph->title->Set("REPAYMENT RATE  PLOTTED AGAINST TIME");
	// Some extra margin (from the top)
	$graph->title->SetMargin(3);
	$graph->title->SetFont(FF_COMIC,FS_NORMAL,12);

	
	// Create the first line
	$p1 = new LinePlot($y_array);
	$p1->mark->SetType(MARK_FILLEDCIRCLE);
	$p1->mark->SetFillColor("red");
	$p1->mark->SetWidth(4);
	$p1->SetColor("red");
	$p1->SetCenter();
	$p1->SetLegend("Cummulated Repayment Rate (%)");
	$graph->Add($p1);

	// Create the second line
	$p2 = new LinePlot($nowy_array);
	$p2->mark->SetType(MARK_FILLEDCIRCLE);
	$p2->mark->SetFillColor("darkblue");
	$p2->mark->SetWidth(4);
	$p2->SetColor("blue");
	$p2->SetCenter();
	$p2->SetLegend("Repayment Rate (%)");
	$graph->Add($p2);
	$graph->Stroke("repay_ratio.jpg");
	return $resp;
}



//DISPLAY GRAPH OF PORTFOLIO AT RISK AGAINST TIME
function par_ratio($from_date,$to_date){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$link = ($from_year == '') ? "" : "<a href='export/par_ratio' target=blank()><b>Printable Version</b></a>";
	
	$content='<div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>';
	$content .= "<input type=button onclick=\"xajax_par_ratio(getElementById('from_date').value,  getElementById('to_date').value);\" value='Show Graph'>".$link."<table height=200 border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center> <tr><td>";
	if($from_date == '' || $to_date == ''){
		$cont= "<font color=red>Please select the period</font>";
		$resp->assign("status", "innerHTML", $cont);
		return $resp;
	}
	$resp->call('xajax_generate_par_ratio', $from_date,$to_date);
	$content .= "<img src=\"par_ratio.jpg?dummy=".time()."\">";
	//unlink("coverage.");
	$content .= "</td></tr></table>";
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//GENERATE PORTFOLIO AT RISK RATIO
function generate_par_ratio($from_date, $to_date){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$to_date = $to_year ."-".$to_month."-31 23:59:59";
	//LOAN LOSS PROVISSIONS
	$next=0;
	$y_list ='';
	$x_list='';
	$from_days = $calc->dateToDays(28, $from_month, $from_year);
	$to_days = $calc->dateToDays(28, $to_month, $to_year);
	$diff = $to_days - $from_days;
	while($next <= $diff){
		$apparent_date = $calc->daysToDate($calc->dateToDays(28, $from_month, $from_year) + $next, '%Y-%m-28');
		preg_match("/(\d+)-(\d+)-28/", $apparent_date, $arr);
		$apparent_date = $calc->endOfMonthBySpan(0, $arr[2], $arr[1], '%Y-%m-%d');
		//CALCULATE ARREARS
		$sched_res = mysql_query("select s.loan_id as loan_id,  sum(s.princ_amt) as princ_amt from schedule s join disbursed d on s.loan_id=d.id left join written_off w on d.id=w.loan_id where (w.date >='".$apparent_date."' or w.date is null) and s.date <= date_sub('".$apparent_date."', INTERVAL d.arrears_period DAY) group by s.loan_id");
		$risky_amt =0;
		while($sched = mysql_fetch_array($sched_res)){
			$paid_res = mysql_query("select sum(p.princ_amt) as princ_amt from payment p join disbursed d on p.loan_id=d.id left join written_off w on d.id=w.loan_id where (w.date >='".$apparent_date."' or w.date is null) and p.date <= '".$apparent_date."' and p.loan_id='".$sched['loan_id']."'");
			$paid = mysql_fetch_array($paid_res);
			$paid_amt = ($paid['princ_amt'] != NULL) ? $paid['princ_amt'] : 0;
			if($sched['princ_amt'] > $paid_amt){
				//OUTSTANDING PORTIFOLIO THEN
				$disbursed_res = mysql_query("select sum(d.amount) as amount from disbursed d left join written_off w on d.id=w.loan_id where (w.date >='".$apparent_date."' or w.date is null) and d.date <= '".$apparent_date."' and d.id='".$sched['loan_id']."'");
				$disbursed = mysql_fetch_array($disbursed_res);
				$disbursed_amt = ($disbursed['amount'] != NULL) ? $disbursed['amount'] : 0;
				$outstanding = $disbursed_amt - $paid_amt;
				$risky_amt = $risky_amt + $outstanding;
			
			}
		}
		//TOTAL DISBURSED
		$disbursed_res = mysql_query("select sum(d.amount) as amount from disbursed d left join written_off w on d.id=w.loan_id where (w.date >='".$apparent_date."' or w.date is null) and d.date <= '".$apparent_date."'");
		$disbursed = mysql_fetch_array($disbursed_res);
		$disbursed_amt = ($disbursed['amount'] != NULL) ? $disbursed['amount'] : 0;
		
		//TOTAL PAID
		$paid_res = mysql_query("select sum(p.princ_amt) as princ_amt from payment p join disbursed d on p.loan_id=d.id left join written_off w on d.id=w.loan_id where (w.date >='".$apparent_date."' or w.date is null) and p.date <= '".$apparent_date."'");
		$paid = mysql_fetch_array($paid_res);
		$paid_amt = ($paid['princ_amt'] != NULL) ? $paid['princ_amt'] : 0;
		//TOTAL OUTSTANDING
		$outstanding = $disbursed_amt - $paid_amt;
		$outstanding = ($outstanding > 0) ? $outstanding : 1;
		$percent = ($risky_amt / $outstanding) * 100.00;
		$percent = sprintf("%.02f", $percent);
		if($y_list == '')
			$y_list = $percent;
		else
			$y_list = $y_list .",".$percent;
		$month_year = date('M', strtotime($apparent_date)) ."-". date('Y', strtotime($apparent_date));
		if($x_list == '')
			$x_list = $month_year;
		else
			$x_list = $x_list .",".$month_year; 
		$next = $next + 30;
	}

	// Create the basic graph
	$x_array = split(',', $x_list);
	$y_array = split(',', $y_list);
	//print_r($x_array."<br>".$y_array);
	//exit();
//CREATE GRAPH
	$graph = new Graph(900,600,'auto');
	$graph->SetScale("textlin");
	$graph->SetMarginColor("lightblue");
	$graph->SetShadow();
	$graph->img->SetMargin(40,80,30,80);
	$graph->img->SetImgFormat("jpeg");

	// Adjust the position of the legend box
	$graph->legend->Pos(0.02,0.15, "right", "center");

	// Adjust the color for theshadow of the legend
	$graph->legend->SetShadow('darkgray@0.5');
	$graph->legend->SetFillColor('lightblue@0.3');

	// Get localised version of the month names
	$graph->xaxis->SetTickLabels($x_array);

	// Set a nice summer (in Stockholm) image
	//$graph->SetBackgroundImage('stship.jpg',BGIMG_COPY);

	// Set axis titles and fonts
	$graph->xaxis->title->Set("Months");
	//$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
	//$graph->xaxis->title->SetColor('white');
	$graph->title->SetFont(FF_VERDANA,FS_NORMAL,12);
	$graph->title->SetColor("darkred");


	$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,7);
	$graph->xaxis->SetColor('darkblue');
	$graph->xaxis->SetLabelAngle(70);

	$graph->yaxis->title->Set("Portfolio At Risk Ratio (%)");
	$graph->yaxis->SetFont(FF_FONT1,FS_BOLD);
	$graph->yaxis->SetColor('white');

	//$graph->ygrid->Show(false);
	$graph->ygrid->SetColor('white@0.5');

	// Setup graph title
	$graph->title->Set("PORTFOLIO AT RISK RATIO PLOTTED AGAINST TIME");
	// Some extra margin (from the top)
	$graph->title->SetMargin(3);
	$graph->title->SetFont(FF_COMIC,FS_NORMAL,12);

	
	// Create the first line
	$p1 = new LinePlot($y_array);
	$p1->mark->SetType(MARK_FILLEDCIRCLE);
	$p1->mark->SetFillColor("red");
	$p1->mark->SetWidth(4);
	$p1->SetColor("blue");
	$p1->SetCenter();
	$p1->SetLegend("Portfolio At Risk Ratio (%)");
	$graph->Add($p1);
	$graph->Stroke("par_ratio.jpg");
	return $resp;
}




//DISPLAY GRAPH OF PORTFOLIO YIELD AGAINST TIME IN MONTHS
function port_yield($from_date,$to_date){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$link = ($from_year == '') ? "" : "<a href='export/port_yield' target=blank()><b>Printable Version</b></a>";
	$content='<div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>';
                                            $resp->call("createDate","from_date");
	                                    $resp->call("createDate","to_date"); 
	$content .= "<input type=button onclick=\"xajax_port_yield(getElementById('from_date').value, getElementById('to_date').value);\" value='Show Graph'>".$link."<table height=200 border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center> <tr><td>";
	if($from_date == ''){
		$cont = "<font color=red>Please select the period</font></td></tr></table>";
		$resp->assign("status", "innerHTML", $cont);
		return $resp;
	}
	$resp->call('xajax_generate_port_yield', $from_date,$to_date);
	$content .= "<img src=\"port_yield.jpg?dummy=".time()."\">";
	//unlink("coverage.");
	$content .= "</td></tr></table>";
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


//GENERATE PORTFOLIO YIELD GRAPH
function generate_port_yield($from_date, $to_date){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$to_date = $to_year ."-".$to_month."-31 23:59:59";
	//LOAN LOSS PROVISSIONS
	$next=0;
	$y_list ='';
	$x_list='';
	$from_days = $calc->dateToDays(28, $from_month, $from_year);
	$to_days = $calc->dateToDays(28, $to_month, $to_year);
	$diff = $to_days - $from_days;
	while($next <= $diff){
		$apparent_date = $calc->daysToDate($calc->dateToDays(28, $from_month, $from_year) + $next, '%Y-%m-28');
		preg_match("/(\d+)-(\d+)-28/", $apparent_date, $arr);
		$apparent_date = $calc->endOfMonthBySpan(0, $arr[2], $arr[1], '%Y-%m-%d');
		//DISBURSED LOANS
		$disbursed_res = mysql_query("select sum(amount) as amount from disbursed where date <='".$apparent_date."'");
		$disbursed = mysql_fetch_array($disbursed_res);
		$disbursed_amt = ($disbursed['amount'] != NULL) ? $disbursed['amount'] : 0;
		//INTEREST PAID
		$paid_res = mysql_query("select sum(int_amt) as int_amt, sum(princ_amt) as princ_amt from payment where date <='".$apparent_date."'");
		$paid = mysql_fetch_array($paid_res);
		$paid_int = ($paid['int_amt'] != NULL) ? $paid['int_amt'] : 0;
		$paid_princ = ($paid['princ_amt'] != NULL) ? $paid['princ_amt'] : 0;
		//WRITTEN OFF 
		$write_res = mysql_query("select sum(amount) as amount from written_off where date <='".$apparent_date."'");
		$write = mysql_fetch_array($write_res);
		$written_amt = ($write['amount'] != NULL) ? $write['amount'] : 0;
		//OUTSTANDING
		$balance = $disbursed_amt - $paid_princ - $written_amt;
		$percent = ($paid_int / $balance) * 100.00;
		$percent = sprintf("%.02f", $percent);
		if($y_list == '')
			$y_list = $percent;
		else
			$y_list = $y_list .",".$percent;
		$month_year = date('M', strtotime($apparent_date)) ."-". date('Y', strtotime($apparent_date));
		if($x_list == '')
			$x_list = $month_year;
		else
			$x_list = $x_list .",".$month_year; 
		$next = $next + 30;
	}

	// Create the basic graph
	$x_array = split(',', $x_list);
	$y_array = split(',', $y_list);
	//print_r($x_array."<br>".$y_array);
	//exit();
//CREATE GRAPH
	$graph = new Graph(900,600,'auto');
	$graph->SetScale("textlin");
	$graph->SetMarginColor("lightblue");
	$graph->SetShadow();
	$graph->img->SetMargin(40,80,30,80);
	$graph->img->SetImgFormat("jpeg");

	// Adjust the position of the legend box
	$graph->legend->Pos(0.02,0.15, "right", "center");

	// Adjust the color for theshadow of the legend
	$graph->legend->SetShadow('darkgray@0.5');
	$graph->legend->SetFillColor('lightblue@0.3');

	// Get localised version of the month names
	$graph->xaxis->SetTickLabels($x_array);

	// Set a nice summer (in Stockholm) image
	//$graph->SetBackgroundImage('stship.jpg',BGIMG_COPY);

	// Set axis titles and fonts
	$graph->xaxis->title->Set("Months");
	//$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
	//$graph->xaxis->title->SetColor('white');
	$graph->title->SetFont(FF_VERDANA,FS_NORMAL,12);
	$graph->title->SetColor("darkred");


	$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,7);
	$graph->xaxis->SetColor('darkblue');
	$graph->xaxis->SetLabelAngle(70);

	$graph->yaxis->title->Set("Portfolio Yield Ratio (%)");
	$graph->yaxis->SetFont(FF_FONT1,FS_BOLD);
	$graph->yaxis->SetColor('white');

	//$graph->ygrid->Show(false);
	$graph->ygrid->SetColor('white@0.5');

	// Setup graph title
	$graph->title->Set("PORTFOLIO YIELD RATIO PLOTTED AGAINST TIME");
	// Some extra margin (from the top)
	$graph->title->SetMargin(3);
	$graph->title->SetFont(FF_COMIC,FS_NORMAL,12);

	
	// Create the first line
	$p1 = new LinePlot($y_array);
	$p1->mark->SetType(MARK_FILLEDCIRCLE);
	$p1->mark->SetFillColor("red");
	$p1->mark->SetWidth(4);
	$p1->SetColor("blue");
	$p1->SetCenter();
	$p1->SetLegend("Portfolio Yield Ratio (%)");
	$graph->Add($p1);
	$graph->Stroke("port_yield.jpg");
	return $resp;
}

//DISPLAY GRAPH OF COVERAGE RISK RATIO AGAINST TIME IN MONTHS
function coverage_ratio($from_date, $to_date){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$link = ($from_year == '') ? "" : "<a href='export/coverage' target=blank()><b>Printable Version</b></a>";
	
	$content='<div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>';
                                            $resp->call("createDate","from_date");
					    $resp->call("createDate","to_date");
	$content .= "<input type=button onclick=\"xajax_coverage_ratio(getElementById('from_year').value, getElementById('from_month').value, getElementById('to_year').value, getElementById('to_month').value);\" value='Show Graph'>".$link."<table height=200 border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center> <tr><td>";
	if($from_date == '' || $to_date=''){
		$cont .= "<font color=red>Please select the period</font></td></tr></table>";
		$resp->assign("status", "innerHTML", $cont);
		return $resp;
	}
	$resp->call('xajax_generate_coverage_ratio', $from_date,$to_date);
	$content .= "<img src=\"coverage.jpg?dummy=".rand()."\">";
	//unlink("coverage.");
	$content .= "</td></tr></table>";
	$resp->assign("display_div", "innerHTML", $content);

	return $resp;
}


//GENERATE THE COVERAGE RISK RATIO
function generate_coverage_ratio($from_date, $to_date){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$to_date = $to_year ."-".$to_month."-31 23:59:59";
	//LOAN LOSS PROVISSIONS
	$next=0;
	$provission = 0;
	$y_list ='';
	$x_list='';
	$prov_res = mysql_query("select * from provissions order by range");
	while($row = mysql_fetch_array($prov_res)){  //SET PROVISSION PERCENTAGES
		${$row['range']} = $row['percent'];
	}
	$from_days = $calc->dateToDays(28, $from_month, $from_year);
	$to_days = $calc->dateToDays(28, $to_month, $to_year);
	$diff = $to_days - $from_days;
	while($next <= $diff){
		$apparent_date = $calc->daysToDate($calc->dateToDays(28, $from_month, $from_year) + $next, '%Y-%m-28');
		preg_match("/(\d+)-(\d+)-28/", $apparent_date, $arr);
		$apparent_date = $calc->endOfMonthBySpan(0, $arr[2], $arr[1], '%Y-%m-%d');
		//echo($arr[1]. "<br>");
		$sth = mysql_query("select d.id as loan_id, d.last_pay_date as last_pay_date, d.amount as amount, d.balance as balance, d.period as loan_period from disbursed d where d.date <= '".$apparent_date."'");
		while($row = mysql_fetch_array($sth)){
			$paid_amt = $row['amount'] - $row['balance'];
			$sched_res = mysql_query("select  sum(princ_amt) as princ_amt from schedule where loan_id='".$row['loan_id']."' and date <= '".$apparent_date."'");
			$sched = mysql_fetch_array($sched_res);
			$sched_amt =($sched['princ_amt'] != NULL) ? $sched['princ_amt'] : 0;
			$arrears_amt = $sched_amt - $paid_amt; 
			$arrears_amt = ($arrears_amt >= 0) ? $arrears_amt : 0;
			$range1 = 0;
			$range2 = 0;
			$range3 = 0;  
			$range4 = 0;
			$range5 = 0;
			$range6 = 0;
			$offset=0;
			
			if($arrears_amt >0){
				$lastdue_res = mysql_query("select date, date_format(date, '%Y') as year, date_format(date, '%m') as month, date_format(date, '%d') as mday from schedule order by date desc limit 1");
				$lastdue = mysql_fetch_array($lastdue_res);
				$offset = strtotime($apparent_date) - strtotime($lastdue['date']);
			
		
				$offset = ($offset >0) ? $offset : 0;
				$offset = floor($offset /(3600 * 24 * 30));
				$instalment = ceil($row['amount'] / ($row['loan_period']/ 30));
				$no = ceil($arrears_amt / $instalment);
				$remainder = $arrears_amt % $instalment;
			}
			
			if($offset >= 6)   //THE WHOLE SCHEDULE IS MORE THAN 6 MONTHS OVERDUE
				$range6 = $arrears_amt * ($range180_over /100);
			elseif($offset == 5){    //THE WHOLE SCHEDULE IS 5 MONTHS OVERDUE
				if($no == 1)
					$range5 = $arrears_amt * $range120_180/100; 
				elseif($no == 2){
					$range4 = $instalment * $range90_120/100;
					$range5 = ($arrears_amt - $instalment) * $range120_180/100;
				}elseif($no == 3){
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = ($arrears_amt - 2*$instalment) * $range120_180/100;
				}elseif($no == 4){
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = ($arrears_amt - 3*$instalment) * $range120_180/100;
				}elseif($no == 5){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = ($arrears_amt - 4*$instalment) * $range120_180/100;
				}elseif($no >= 6){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = $instalment * $range120_180/100;
					$range6 = ($arrears_amt - 5*$instalment) * $range180_over/100;
				}
			}elseif($offset == 4){    //THE WHOLE SCHEDULE IS 4 MONTHS OVERDUE
				if($no == 1)
					$range4 = $arrears_amt * $range90_120/100; 
				elseif($no == 2){
					$range3 = $instalment * $range60_90/100;
					$range4 = ($arrears_amt - $instalment) * $range90_120/100;
				}elseif($no == 3){
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = ($arrears_amt - 2*$instalment) * $range90_120/100;
				}elseif($no == 4){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = ($arrears_amt - 3*$instalment) * $range90_120/100;
				}elseif($no == 5){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = ($arrears_amt - 4*$instalment) * $range120_180/100;
				}elseif($no >= 6){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = $instalment * $range120_180/100;
					$range6 = ($arrears_amt - 5*$instalment) * $range180_over/100;
				}
			}elseif($offset == 3){    //THE WHOLE SCHEDULE IS 3 MONTHS OVERDUE
				if($no == 1)
					$range3 = $arrears_amt * $range60_90/100; 
				elseif($no == 2){
					$range2 = $instalment * $range30_60/100;
					$range3 = ($arrears_amt - $instalment) * $range60_90/100;
				}elseif($no == 3){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = ($arrears_amt - 2*$instalment) * $range60_90/100;
				}elseif($no == 4){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = ($arrears_amt - 3*$instalment) * $range90_120/100;
				}elseif($no == 5){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = ($arrears_amt - 4*$instalment) * $range120_180/100;
				}elseif($no >= 6){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = $instalment * $range120_180/100;
					$range6 = ($arrears_amt - 5*$instalment) * $range180_over/100;
				}
			}elseif($offset == 2){    //THE WHOLE SCHEDULE IS 2 MONTHS OVERDUE
				if($no == 1)
					$range2 = $arrears_amt * $range30_60/100; 
				elseif($no == 2){
					$range1 = $instalment * $range1_30/100;
					$range2 = ($arrears_amt - $instalment) * $range30_60/100;
				}elseif($no == 3){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = ($arrears_amt - 2*$instalment) * $range60_90/100;
				}elseif($no == 4){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = ($arrears_amt - 3*$instalment) * $range90_120/100;
				}elseif($no == 5){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = ($arrears_amt - 4*$instalment) * $range120_180/100;
				}elseif($no >= 6){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = $instalment * $range120_180/100;
					$range6 = ($arrears_amt - 5*$instalment) * $range180_over/100;
				}
			}elseif($offset <= 1){    //THE WHOLE SCHEDULE IS 1 MONTHS OVERDUE
				if($no == 1)
					$range1 = $arrears_amt * $range1_30/100; 
				elseif($no == 2){
					$range1 = $instalment * $range1_30/100;
					$range2 = ($arrears_amt - $instalment) * $range30_60/100;
				}elseif($no == 3){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = ($arrears_amt - 2*$instalment) * $range60_90/100;
				}elseif($no == 4){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = ($arrears_amt - 3*$instalment) * $range90_120/100;
				}elseif($no == 5){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = ($arrears_amt - 4*$instalment) * $range120_180/100;
				}elseif($no >= 6){
					
					//echo("Arrears=".$arrears_amt."<br>");
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range31_60/100;
					$range3 = $instalment * $range61_90/100;
					$range4 = $instalment * $range91_120/100;
					$range5 = $instalment * $range121_180/100;
					$range6 = ($arrears_amt - 5*$instalment) * $range180_over/100;
					//echo("2nd Arears=".$arrears_amt."<br>");
				}
			}
			$provission = $provission + $range1 + $range2 + $range3 + $range4 + $range5 + $range6;
			
		}
		
		$out_res = mysql_query("select sum(d.amount) as amount from disbursed d where d.date <= '".$apparent_date."'");
		$out = mysql_fetch_array($out_res);
		$out_amt = ($out['amount'] != NULL) ? $out['amount'] : 0;
		$in_res = mysql_query("select sum(princ_amt) as amount from payment where date <= '".$apparent_date."'");
		$in = mysql_fetch_array($in_res);
		$in_amt = ($in['amount'] != NULL) ? $in['amount'] : 0;
		
		if($in_amt >= $out_amt)
			$percent =0;
		else{
			$div = $out_amt - $in_amt;
			$percent = ($provission / ($div)) * 100;
			$percent = sprintf("%.02f", $percent);
		}
		
		if($y_list == '')
			$y_list = $percent;
		else
			$y_list = $y_list .",".$percent;
		$month_year = date('M', strtotime($apparent_date)) ."-". date('Y', strtotime($apparent_date));
		if($x_list == '')
			$x_list = $month_year;
		else
			$x_list = $x_list .",".$month_year; 
		$provission = 0;
		$next = $next + 30;

	}
	// Create the basic graph
	$x_array = split(',', $x_list);
	$y_array = split(',', $y_list);
//CREATE GRAPH
	$graph = new Graph(900,600,'auto');
	$graph->SetScale("textlin");
	$graph->SetMarginColor("lightblue");
	$graph->SetShadow();
	$graph->img->SetMargin(40,80,30,80);
	$graph->img->SetImgFormat("jpeg");

	// Adjust the position of the legend box
	$graph->legend->Pos(0.02,0.15, "right", "center");

	// Adjust the color for theshadow of the legend
	$graph->legend->SetShadow('darkgray@0.5');
	$graph->legend->SetFillColor('lightblue@0.3');

	// Get localised version of the month names
	$graph->xaxis->SetTickLabels($x_array);

	// Set a nice summer (in Stockholm) image
	//$graph->SetBackgroundImage('stship.jpg',BGIMG_COPY);

	// Set axis titles and fonts
	$graph->xaxis->title->Set('Months');
	//$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
	//$graph->xaxis->title->SetColor('white');
	$graph->title->SetFont(FF_VERDANA,FS_NORMAL,12);
	$graph->title->SetColor("darkred");


	$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,7);
	$graph->xaxis->SetColor('darkblue');
	$graph->xaxis->SetLabelAngle(70);

	$graph->yaxis->title->Set('Coverage Risk Ratio (%)');
	$graph->yaxis->SetFont(FF_FONT1,FS_BOLD);
	$graph->yaxis->SetColor('white');

	//$graph->ygrid->Show(false);
	$graph->ygrid->SetColor('white@0.5');

	// Setup graph title
	$graph->title->Set('RISK COVERAGE RATIO PLOTTED AGAINST TIME');
	// Some extra margin (from the top)
	$graph->title->SetMargin(3);
	$graph->title->SetFont(FF_COMIC,FS_NORMAL,12);

	
	// Create the first line
	$p1 = new LinePlot($y_array);
	$p1->mark->SetType(MARK_FILLEDCIRCLE);
	$p1->mark->SetFillColor("red");
	$p1->mark->SetWidth(4);
	$p1->SetColor("blue");
	$p1->SetCenter();
	$p1->SetLegend("Risk Coverage Ratio");
	$graph->Add($p1);
	$graph->Stroke("coverage.jpg");
	return $resp;
}

//FINANCIAL STATEMENTS
//SUB (ASSETS) TRIAL BAL
function assettrial_balance($date, $basis,$branch_id){
list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	if($year == ''){
		$year = date('Y');
		$month = date('m');
		$mday = date('d');
	}
	$resp->assign("status", "innerHTML", "");
	$date = sprintf("%d-%02d-%02d 23:59:59", $year, $month, $mday);
	$branch_res = mysql_query("select * from branch");
	$branch = mysql_fetch_array($branch_res);
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:'and branch_id='.$branch_id;
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR ASSETS SUBSIDIARY TRIAL BALANCE</h3>
                                <p class="text-default nm"></p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Date:</label>
                                           <input type="text" class="form-control" id="date" name="date" placeholder="date" />       
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Basis:</label>
                                          <select id="basis" class="form-control"><option value="Cash Basis">Cash Basis<option value="Accrual Basis">Accrual Basis</select>        
                                        </div>
                                        
                                    </div>
                                </div>  
                                
                       <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Branch:</label>
                                           '.branch_rep($branch_id).'        
                                        </div>
                                                                            
                                    </div>
                                </div>';                                 
                                   	
		 $content .= "<div class='panel-footer'>                              
                               
                                <button type='button' class='btn  btn-primary' onclick=\"xajax_assettrial_balance(getElementById('date').value,getElementById('basis').value,getElementById('branch_id').value);\">Show Report</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","date");
                    
	
	 $content .= "<a href='export/assettrial?year=".$year."&month=".$month."&mday=".$mday."&basis=".$basis."&branch_id=".$branch_id."' target=blank()><b>Printable Version</b></a> | <a href='export/assettrial?year=".$year."&month=".$month."&mday=".$mday."&basis=".$basis."&format=excel&branch_id=".$branch_id."' target=blank()><b>Export Excel</b></a>";
	
	 $content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">'.$branch['branch_name'].'</h3>
                                <p>ASSETS SUBSIDIARY TRIAL BALANCE AS AT '.$date.'</p>
                                <p>'.$basis.'</p>
                            </div>';
 		//$content .= '<table class="table table-striped table-bordered" id="table-tools">*/
	
		$content .= "<table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>
		<tr class='headings'><td><font size=5pt><b>ACCOUNT</b></font></td><td><font size=5pt><b>DESCRIPTION</b></font></td><td><font size=5pt><b>BALANCE</b></font></td></tr>";
		$level2_res= mysql_query("select * from accounts where account_no >10 and account_no <20 ".$branch_." order by account_no");
		$assets = 0;
		$i=0;
		while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
			$color=($i%2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color><td><font size=4pt><b>".$level2['account_no']."</b></font></td><td><font size=4pt><b>".$level2['name']."</b></font></td><td></td></tr>";
			$i++;
			$level3_res = mysql_query("select * from accounts where account_no >=111 and account_no <= 199 and account_no like '".$level2['account_no']."%' and account_no not in (124, 112) order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				$color=($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td><b>".$level3['account_no']."</b></td><td><b>".$level3['name']."</b></td><td></td></tr>";
				$i++;
				$grand_total = 0;
				if($level3['account_no'] == '111'){  //LOANS
					$grand_loss = 0;
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1111 and account_no <= 1119");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$loss_total=0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 111101 and account_no <= 111999");
						if( mysql_numrows($level5_res) > 0){
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td></td></tr>";
							$i++;
						}
						while($level5 = mysql_fetch_array($level5_res)){
							$loan1_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level5['id']."' and d.date <= '".$date."'");
							$loan1 = mysql_fetch_array($loan1_res);
							if($loan1['amount'] == NULL){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 11110101 and account_no <= 11199999");
								if(mysql_numrows($level6_res) >0){
									$sub2_total = 0;
									$loss2_total = 0;
									while($level6 = mysql_fetch_array($level6_res)){
										$loan2_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level6['id']."' and d.date <= '".$date."'");
										$loan2 = mysql_fetch_array($loan2_res);
										$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level6['id']."' and d.date <= '".$date."' and pay.date <='".$date."'");
										$pay = mysql_fetch_array($pay_res);
										$loan2_amt = ($loan2['amount'] == NULL) ? 0 : $loan2['amount'];
										
										$loss_res = mysql_query("select sum(w.amount) as amount from written_off w join disbursed d on w.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where w.date <= '".$date."' and p.account_id='".$level6['id']."'");
										$loss = mysql_fetch_array($loss_res);
										$loss2_total = $loss2_total + $loss['amount'];
										$sub2_total = $sub2_total + $loan2_amt - $pay['amount'] - $loss['amount'];
									}
								} 
							}else{
								$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level5['id']."' and d.date <= '".$date."' and pay.date <='".$date."'");
								$pay = mysql_fetch_array($pay_res);
								$loss_res = mysql_query("select sum(w.amount) as amount from written_off w join disbursed d on w.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where w.date <= '".$date."' and p.account_id='".$level5['id']."'");
								$loss = mysql_fetch_array($loss_res);
								$loss2_total = $loss['amount'];

								$sub2_total = $loan1['amount'] - $pay['amount'] - $loss['amount'];
							}
						
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td>".$level5['account_no']."</td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
							$i++;
							
							$sub1_total = $sub1_total + $sub2_total;
							$loss_total = $loss_total + $loss2_total;
							$sub2_total=0;
						}
						$color=($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						 $i++;
						/* $written_res = mysql_query("select * from accounts where account_no like '112%' and name like '%".$level4['name']."'");
						$written = mysql_fetch_array($written_res);
						$color=($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color ><td><b>ddd".$written['account_no']."</b></td><td><b>".$written['name']."</b></td><td><b>".$loss1_total."</b></td></tr>";
						$i++;
						*/

						$grand_total = $grand_total + $sub1_total;
						//$grand_loss = $grand_loss + $loss_total;
					}
					//LOAN LOSS ALLOWANCES
					$grand_loss=0;
					$prov_res = mysql_query("select * from provissions order by percent asc");
					while($prov = mysql_fetch_array($prov_res)){
						preg_match("/(\d*)[_](\d*)$/", $prov['range'], $arg);
						$interval = ($arg[1] <>180) ? ($arg[2] - $arg[1]) : 180;
						if($arg[2]==''){
							$arg[1] =180;
							$arg[2]=660;     //UP TO 22 MONTHS OF ARREARS, THE ASSUMPTION IS THAT BEYOND THAT, THE LOAN WILL HAVE BEEN WRITTEN OFF
						}
						
						$over_180 =0;
						$sched_res = mysql_query("select sum(princ_amt) as amount, loan_id as loan_id from schedule where date <= DATE_SUB('".$date."', INTERVAL ".$arg[1]." DAY) and date >= DATE_SUB('".$date."', INTERVAL ".$arg[2]." DAY) and loan_id not in (select loan_id from written_off where date <= DATE_SUB('".$date."', INTERVAL ".$arg[1]." DAY)) group by loan_id");
					
					
						while($sched = mysql_fetch_array($sched_res)){
							$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
							$all = mysql_fetch_array(mysql_query("select sum(princ_amt) as amount from schedule where date <= DATE_SUB('".$date."', INTERVAL ".$arg[2]." DAY)  and loan_id='".$sched['loan_id']."'"));
							$paid_res = mysql_query("select sum(princ_amt) as amount from payment where date <='".$date."' and loan_id='".$sched['loan_id']."'");
							$paid = mysql_fetch_array($paid_res);
							$paid_amt = ($paid['amount'] == NULL) ? 0 : $paid['amount'];
							$arrears = $all['amount'] - $paid_amt;
							if($arrears < 0){
								$arrears =0;
							}
							
							$arrears = ($arrears > $sched_amt) ? $sched_amt : $arrears;
							$over_180  += $arrears;

						}	
						$loss180_over = floor($over_180 * $prov['percent']/100);
						$grand_loss += $loss180_over;
					}

					//MANUAL LOAN LOSS PROVISSION
					$static = mysql_fetch_array(mysql_query("select sum(amount) as amount from otherloan_loss where date <= '".$date."'"));
					$static_amt = ($static['amount'] == NULL) ? 0 : $static['amount'];
					$grand_loss = $grand_loss + $static_amt;
					$grand_net = $grand_total - $grand_loss;  //SUBTRACT LOAN LOSS PROVISIONS







/*
					//FOR OVER 180 DAYS
					$sched_res = mysql_query("select sum(princ_amt) as amount from schedule where date < DATE_SUB('".$date."', INTERVAL 180 DAY)");
					$sched = mysql_fetch_array($sched_res);
					$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
					$paid_res = mysql_query("select sum(princ_amt) as amount from payment where date <='".$date."'");
					$paid = mysql_fetch_array($paid_res);
					$paid_amt = ($paid['amount'] == NULL) ? 0 : $paid['amount'];
					$over_180 = $sched_amt - $paid_amt;
					$over_180 = ($over_180 > 0) ? $over_180 : 0;
					$sth=mysql_query("select * from provissions where range='range180_over'");
					$row = mysql_fetch_array($sth);
					$loss180_over = floor($over_180 * $row['percent']/100);

					//FROM 121 TO 180 DAYS
					$sched_res = mysql_query("select sum(princ_amt) as amount from schedule where date < DATE_SUB('".$date."', INTERVAL 120 DAY)");
					$sched = mysql_fetch_array($sched_res);
					$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
					
					$from121_180 = $sched_amt - $paid_amt;
					$from121_180 = ($from121_180 >0) ? $from120_180 : 0;
					$from121_180 = $from121_180 - $over_180;
					$sth=mysql_query("select * from provissions where range='range121_180'");
					$row = mysql_fetch_array($sth);
					$loss121_180 = floor($from121_180 * $row['percent']/100);

					//FROM 91 TO 120 DAYS
					$sched_res = mysql_query("select sum(princ_amt) as amount from schedule where date < DATE_SUB('".$date."', INTERVAL 90 DAY)");
					$sched = mysql_fetch_array($sched_res);
					$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
					$from91_120 = $sched_amt - $paid_amt;
					$from91_120 = ($from91_120 >0) ? $from91_120 : 0;
					$from91_120 = $from91_120 - $from121_180 - $over_180;    
					$sth=mysql_query("select * from provissions where range='range91_120'");
					$row = mysql_fetch_array($sth);
					$loss91_120 = floor($from91_120 * $row['percent']/100);

					//FROM 61 TO 90 DAYS
					$sched_res = mysql_query("select sum(princ_amt) as amount from schedule where date < DATE_SUB('".$date."', INTERVAL 60 DAY)");
					$sched = mysql_fetch_array($sched_res);
					$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
					$from61_90 = $sched_amt - $paid_amt;
					$from61_90 = ($from61_90 >0) ? $from61_90 : 0;
					$from61_90 = $from61_90 - $from91_120 - $from121_180 - $over_180;
					$sth=mysql_query("select * from provissions where range='range61_90'");
					$row = mysql_fetch_array($sth);
					$loss61_90 = floor($from61_90 * $row['percent']/100);
					
					//FROM 31 TO 90 DAYS
					$sched_res = mysql_query("select sum(princ_amt) as amount from schedule where date < DATE_SUB('".$date."', INTERVAL 30 DAY)");
					$sched = mysql_fetch_array($sched_res);
					$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
					$from31_60 = $sched_amt - $paid_amt;
					$from31_60 = ($from31_60 >0) ? $from31_60 : 0;
					$from31_60 = $from31_60 - $from61_90 - $from91_120 - $from121_180 - $over_180;
					$sth=mysql_query("select * from provissions where range='range31_60'");
					$row = mysql_fetch_array($sth);
					$loss31_60 = floor($from31_60 * $row['percent']/100);

					//FROM 1 TO 30 DAYS
					$sched_res = mysql_query("select sum(princ_amt) as amount from schedule where date < DATE_SUB('".$date."', INTERVAL 30 DAY)");
					$sched = mysql_fetch_array($sched_res);
					$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
					$from1_30 = $sched_amt - $paid_amt;
					$from1_30 = ($from1_30 >0) ? $from1_30 : 0;
					$from1_30 = $from1_30 - $from31_60 - $from61_90 - $from91_120 - $from121_180 - $over_180;
					$sth=mysql_query("select * from provissions where range='range1_30'");
					$row = mysql_fetch_array($sth);
					$loss1_30 = floor($from1_30 * $row['percent']/100);
					//MANUAL LOAN LOSS PROVISSION
					$static = mysql_fetch_array(mysql_query("select sum(amount) as amount from otherloan_loss where date <= '".$date."'"));
					$static_amt = ($static['amount'] == NULL) ? 0 : $static['amount'];
					$grand_loss = $loss1_30 + $loss31_60 + $loss61_90 + $loss91_120 + $loss121_180 + $loss180_over + $static_amt;
					$grand_net = $grand_total - $grand_loss;  //SUBTRACT LOAN LOSS PROVISIONS
				*/
				}elseif($level3['account_no'] >= 113 && $level3['account_no'] <= 119){   //INVESTMENTS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1131 and account_no <= 1199");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level4['id']."' and date <='".$date."'");
						$invest = mysql_fetch_array($invest_res);
						if($invest['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 113101 and account_no <= 119999");
							if( mysql_numrows($level5_res) > 0){
								$color=($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td></td></tr>";
								$i++;
							}
							while($level5 = mysql_fetch_array($level5_res)){
								$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level5['id']."' and date<='".$date."'");
								$invest = mysql_fetch_array($invest_res);
								$sub2_total=0;
								if($invest['amount'] ==NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 11310101 and account_no <= 11399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level6['id']."' and date <='".$date."'");		
										$invest = mysql_fetch_array($invest_res);
										//$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level6['id']."' and date <='".$date."' order by date desc limit 1"));
										$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level6['id']."' and date <='".$date."'");
										$sold = mysql_fetch_array($sold_res);
										$sold_amt = ($sold['amount'] == NULL) ? 0 : $sold['amount'];
										$sub2_total = $sub2_total + $invest['amount'] - $sold_amt;
									}
								}else{
									//$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level5['id']."' and date <='".$date."' order by date desc limit 1"));
									$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level5['id']."' and date <='".$date."'");
									$sold = mysql_fetch_array($sold_res);
									$sold_amt = ($sold['amount'] == NULL) ? 0 : $sold['amount'];
									$sub2_total = $invest['amount'] - $sold_amt;
									//$sub2_total = $invest['amount'];
								}
								$sub1_total = $sub1_total + $sub2_total;
							}
						}else{
							//$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level4['id']."' and date <='".$date."' order by date desc limit 1"));
							$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level4['id']."' and date <='".$date."'");
							$sold = mysql_fetch_array($sold_res);
							$sold_amt = ($sold['amount'] == NULL) ? 0 : $sold['amount'];
							$sub1_total = $invest['amount'] - $sold_amt;
						}
						$color=($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color ><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						$i++;
						$grand_total = $grand_total + $sub1_total;
					}
				}elseif($level3['account_no'] >= 125){   //INVESTMENTS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1251 and account_no <= 1259");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 125101 and account_no <= 125999");
						if( mysql_numrows($level5_res) > 0){
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td></td></tr>";
							$i++;
						}
						while($level5 = mysql_fetch_array($level5_res)){
							$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level5['id']."' and date <= '".$date."' and id not in (select investment_id from sold_invest where date<='".$date."')");
							$invest = mysql_fetch_array($invest_res);
							$sub2_total=0;
							if($invest['amount'] == NULL){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 12510101 and account_no <= 11399999");
								while($level6 = mysql_fetch_array($level6_res)){
									$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level6['id']."' and date <= '".$date."' and id not in (select investment_id from sold_invest where date<='".$date."')");		
									$invest = mysql_fetch_array($invest_res);
									$sub2_total = $sub2_total + $invest['amount'];
								}
							}else{
								
								$sub2_total = $invest['amount'];
							}
							$sub1_total = $sub1_total + $sub2_total;
						}
						$color=($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color ><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						$i++;
						$grand_total = $grand_total + $sub1_total;
					}
				}elseif($level3['account_no'] == 121){
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1211 and account_no <= 1219");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 121101 and account_no <= 121999");
						if( mysql_numrows($level5_res) > 0){
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td></td></tr>";
							$i++;
						}
						while($level5 = mysql_fetch_array($level5_res)){
							$bank1_res = mysql_query("select * from bank_account where account_id='".$level5['id']."'");
							$sub2_total = 0;
							if(mysql_numrows($bank1_res) == 0){
								//$resp->AddAlert("Hi");
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 12110101 and account_no <= 12199999");
								while($level6 = mysql_fetch_array($level6_res)){
									$bank2_res = mysql_query("select * from bank_account where account_id='".$level6['id']."'");
									$bank2 = mysql_fetch_array($bank2_res);
									//DEPOSITS
									$dep_res = mysql_query("select sum(amount) as amount from deposit where bank_account='".$bank2['id']."' and date <='".$date."'");
									$dep = mysql_fetch_array($dep_res);
									//WITHDRAWALS
									$with_res = mysql_query("select sum(amount) as amount from withdrawal where bank_account='".$bank2['id']."' and date <='".$date."'");
									$with = mysql_fetch_array($with_res);
									//OTHER INCOME
									$other_res = mysql_query("select sum(amount) as amount from other_income where bank_account='".$bank2['id']."' and date <='".$date."'");
									$other = mysql_fetch_array($other_res);
									//LOANS PAYABLE
									$loans_payable = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '212%' and p.bank_account='".$bank2['id']."' and p.date <= '".$date."'");
									$loans_payable = mysql_fetch_array($loans_payable);
									$loans_payable = ($loans_payable['amount'] != NULL) ? $loans_payable['amount'] : 0;
									//EXPENSES
									$expense_res = mysql_query("select sum(amount) as amount from expense where bank_account='".$bank2['id']."' and date <='".$date."'");
									$expense = mysql_fetch_array($expense_res);
									//PAYABLE PAID
									$payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where bank_account='".$bank2['id']."' and date <='".$date."'");	
									$payable_paid = mysql_fetch_array($payable_paid_res);
									//RECEIVABLE COLLECTED
									$collected_res = mysql_query("select sum(amount) as amount from collected where bank_account='".$bank2['id']."' and date <='".$date."'");
									$collected = mysql_fetch_array($collected_res);
									//DISBURSED LOANS
									$disb_res = mysql_query("select sum(amount) as amount from disbursed where bank_account='".$bank2['id']."' and date <= '".$date."'");
									$disb = mysql_fetch_array($disb_res);
									//PAYMENTS
									$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id  where p.date <= '".$date."' and p.bank_account='".$bank2['id']."'");
									$pay = mysql_fetch_array($pay_res);
									//PENALTIES
									$pen_res = mysql_query("select sum(p.amount) as amount from penalty p join disbursed d on p.loan_id=d.id where p.bank_account='".$bank2['id']."' and p.status='paid' and p.date <='".$date."'");
									$pen = mysql_fetch_array($pen_res);
								
									//SHARES
									$shares_res = mysql_query("select sum(value) as amount from shares where date <='".$date."' and bank_account='".$bank2['id']."'");
									$shares = mysql_fetch_array($shares_res); 
									//RECOVERED
									$rec_res = mysql_query("select sum(r.amount) as amount from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.bank_account='".$bank2['id']."' and r.date <= '".$date."'");
									$rec = mysql_fetch_array($rec_res);			
									//INVESTMENTS 
									$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where date <= '".$date."' and bank_account='".$bank2['id']."'");
									$invest = mysql_fetch_array($invest_res);
									$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
									//DIVIDENDS PAID
									$div_res = mysql_query("select sum(total_amount) as total_amount from share_dividends where date<='".$date."' and bank_account='".$bank2['id']."'");
									$div = mysql_fetch_array($div_res);
									$div_amt = ($div['amount'] != NULL) ? $div['amount'] : 0;
									//SOLD INVESTMENTS
									$soldinvest_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where bank_account ='".$bank2['id']."' and date <= '".$date."'");
									$soldinvest = mysql_fetch_array($soldinvest_res);

									//FIXED ASSETS 
									$fixed_res = mysql_query("select sum(initial_value) as amount from fixed_asset where bank_account='".$bank2['id']."' and date <='".$date."'");
									$fixed = mysql_fetch_array($fixed_res);
									$soldasset_res = mysql_query("select sum(amount) as amount from sold_asset where bank_account ='".$bank2['id']."' and date <= '".$date."'");
									$soldasset = mysql_fetch_array($soldasset_res);
									//CASH IMPORTED
									$import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id='".$bank2['id']."' and date <='".$date."'");
									$import = mysql_fetch_array($import_res);
									$import_amt = ($import['amount'] ==NULL) ? 0 : $import['amount'];
									

									//CASH EXPORTED
									$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id='".$bank2['id']."' and date <='".$date."'");
									$export = mysql_fetch_array($export_res);
									$export_amt = ($export['amount'] ==NULL) ? 0 : $export['amount'];
									

									//CAPITAL FUNDS
									$fund_res = mysql_query("select sum(amount) as amount from other_funds where bank_account='".$bank2['id']."' and date <='".$date."'");
									$fund = mysql_fetch_array($fund_res);
									$fund_amt = $fund['amount'];


									$sub2_total = $sub2_total + $collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt + $fund_amt;
									
								}
							}else{
								$bank1 = mysql_fetch_array($bank1_res);
								//DEPOSITS
								$dep_res = mysql_query("select sum(amount) as amount from deposit where bank_account='".$bank1['id']."' and date <='".$date."'");
								$dep = mysql_fetch_array($dep_res);
								//WITHDRAWALS
								$with_res = mysql_query("select sum(amount) as amount from withdrawal where bank_account='".$bank1['id']."' and date <='".$date."'");
								$with = mysql_fetch_array($with_res);
								//OTHER INCOME
								$other_res = mysql_query("select sum(amount) as amount from other_income where bank_account='".$bank1['id']."' and date <='".$date."'");
								$other = mysql_fetch_array($other_res);
								//EXPENSES
								$expense_res = mysql_query("select sum(amount) as amount from expense where bank_account='".$bank1['id']."' and date <='".$date."'");
								$expense = mysql_fetch_array($expense_res);
								//LOANS PAYABLE
								$loans_payable = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '212%' and p.bank_account='".$bank1['id']."' and p.date <= '".$date."'");
								$loans_payable = mysql_fetch_array($loans_payable);
								$loans_payable = ($loans_payable['amount'] != NULL) ? $loans_payable['amount'] : 0;
								//PAYABLE PAID
								$payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where bank_account='".$bank1['id']."' and date <='".$date."'");
								$payable_paid = mysql_fetch_array($payable_paid_res);
								//RECEIVABLE COLLECTED
								$collected_res = mysql_query("select sum(amount) as amount from collected where bank_account='".$bank1['id']."' and date <='".$date."'");
								$collected = mysql_fetch_array($collected_res);
								//DISBURSED LOANS
								$disb_res = mysql_query("select sum(amount) as amount from disbursed where bank_account='".$bank1['id']."' and date <= '".$date."'");
								$disb = mysql_fetch_array($disb_res);
								//PAYMENTS
								$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id  where p.date <= '".$date."' and p.bank_account='".$bank1['id']."'");
								$pay = mysql_fetch_array($pay_res);
								//PENALTIES
								$pen_res = mysql_query("select sum(p.amount) as amount from penalty p join disbursed d on p.loan_id=d.id where p.bank_account='".$bank1['id']."' and p.status='paid' and p.date <= '".$date."'");
								$pen = mysql_fetch_array($pen_res);
								
								//SHARES
								$shares_res = mysql_query("select sum(value) as amount from shares where date <='".$date."' and bank_account='".$bank1['id']."'");
								$shares = mysql_fetch_array($shares_res); 
								//RECOVERED
								$rec_res = mysql_query("select sum(r.amount) as amount from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.bank_account='".$bank1['id']."' and r.date <= '".$date."'");
								$rec = mysql_fetch_array($rec_res);
								$rec_amt = ($rec['amount'] != NULL) ? $rec['amount'] : 0;
								//INVESTMENTS 
								$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where date <= '".$date."' and bank_account='".$bank1['id']."'");
								$invest = mysql_fetch_array($invest_res);
								$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
								//DIVIDENDS PAID
								$div_res = mysql_query("select sum(total_amount) as total_amount from share_dividends where date<='".$date."' and bank_account='".$bank1['id']."'");
								$div = mysql_fetch_array($div_res);
								$div_amt = ($div['amount'] != NULL) ? $div['amount'] : 0;

								
								$soldinvest_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where bank_account ='".$bank1['id']."' and date <= '".$date."'");
								$soldinvest = mysql_fetch_array($soldinvest_res);

								//FIXED ASSETS 
								$fixed_res = mysql_query("select sum(initial_value) as amount from fixed_asset where bank_account='".$bank1['id']."' and date <='".$date."'");
								$fixed = mysql_fetch_array($fixed_res);
								$soldasset_res = mysql_query("select sum(amount) as amount from sold_asset where bank_account ='".$bank1['id']."' and date <= '".$date."'");
								$soldasset = mysql_fetch_array($soldasset_res);
								//CASH IMPORTED
								$import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id=".$bank1['id']." and date <='".$date."'");
								$import = mysql_fetch_array($import_res);
								$import_amt = ($import['amount'] ==NULL) ? 0 : $import['amount'];

								//CASH EXPORTED
								$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id=".$bank1['id']." and date <='".$date."'");
								$export = mysql_fetch_array($export_res);
								$export_amt = ($export['amount'] ==NULL) ? 0 : $export['amount'];
							
								
								//CAPITAL FUNDS
								$fund_res = mysql_query("select sum(amount) as amount from other_funds where bank_account='".$bank1['id']."' and date <='".$date."'");
								$fund = mysql_fetch_array($fund_res);
								$fund_amt = $fund['amount'];


								$sub2_total =  $collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt + $fund_amt;
							}
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td>".$level5['account_no']."</td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
							$i++;
							$sub1_total = $sub1_total + $sub2_total;
						}
						$color=($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						$i++;
						$grand_total += $sub1_total;
					}
				}elseif($level3['account_no']=='122'){   //RECEIVABLES
					if($basis == 'Cash Basis')
						continue;
					$rec_res = mysql_query("select sum(amount) as amount from receivable where maturity_date <='".$date."'");
					$rec = mysql_fetch_array($rec_res);
					if($rec['amount'] != NULL){
						$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where c.date <= '".$date."'");
						$col = mysql_fetch_array($col_res);
						$grand_total =  $rec['amount'] - $col['amount'];
					}
				}elseif($level3['account_no'] == '123'){  //FIXED ASSETS
					$deppreciation = 0;
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= '1231' and account_no <= '1239' order by account_no");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$depp1_total = 0;
						$fixed_res = mysql_query("select sum(f.initial_value) as amount from fixed_asset f  where f.account_id='".$level4['id']."' and f.date <='".$date."' and f.id not in (select asset_id from sold_asset where date <='".$date."')");
						$fixed = mysql_fetch_array($fixed_res);
						if($fixed['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 123101 and account_no <= 123999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$depp2_total = 0;
								
								$fixed_res = mysql_query("select sum(f.initial_value) as amount from fixed_asset f  where f.account_id='".$level5['id']."' and f.date <= '$date' and f.id not in (select asset_id from sold_asset where date <='".$date."')");
								$fixed = mysql_fetch_array($fixed_res);
								if($fixed['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >=12310101 and account_no <= 12399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$fixed_res = mysql_query("select sum(f.initial_value) as amount from fixed_asset f  where f.account_id='".$level6['id']."' and f.date <='".$date."' and f.id not in (select asset_id from sold_asset where date <='".$date."')");
										$fixed = mysql_fetch_array($fixed_res);
										$sub2_total = $sub2_total + $fixed['amount'];
										$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from fixed_asset where account_id='".$level6['id']."') and date<= '".$date."'");
										$depp = mysql_fetch_array($depp_res);
										$depp2_total = $depp2_total + $depp['amount'];
									}
								}else{
									//$fixed = mysql_fetch_array($fixed_res);
									$sub2_total = $fixed['amount'];
									$depp_res = mysql_query("select sum(amount) as amount from deppreciation  where asset_id in (select id from fixed_asset where account_id='".$level5['id']."') and date<= '".$date."'");
									$depp = mysql_fetch_array($depp_res);
									$depp2_total = $depp2_total + $depp['amount'];
								}
								if($fixed['id'] == '0')
									continue;
								$color=($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td>".$level5['account_no']."</td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
								$i++;
								$acc_res = mysql_query("select * from accounts where name='Accumulated Depreciation - ".$level5['name']."'");
								$acc = mysql_fetch_array($acc_res);
								$color=($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td>".$acc['account_no']."</td><td>Depreciation - ".$level5['name']."</td><td>".number_format($depp2_total, 2)."</td></tr>";
								$i++;
								$net_total = $sub2_total - $depp2_total;		
				
								$sub1_total += $sub2_total;
								$depp1_total += $depp2_total;
							}
						}else{  //IF THE FIXED ASSET IS A LEVEL 4 ACCOUNT
							$sub1_total = $fixed['amount'];
							$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from fixed_asset where account_id='".$level4['id']."') and date<= '".$date."'");
							$depp = mysql_fetch_array($depp_res);
							$depp1_total = ($depp['amount'] != NULL) ? $depp['amount'] : 0;
						}
						
						$net_total = $sub1_total - $depp1_total;
						$color=($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td></td><td><b>Net ".$acc['name']."</b></td><td>".number_format($net_total, 2)."</td></tr>";
						if($level4['name'] != 'Land'){
							$acc_res = mysql_query("select * from accounts where name = 'Accumulated Depreciation - ".$level4['name']."'");
							$acc = mysql_fetch_array($acc_res);
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color ><td><b>".$acc['account_no']."</b></td><td><b>".$acc['name']."</b></td><td><b>".number_format($depp1_total, 2)."</b></td></tr>";
							$i++;
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td></td><td><b>Net ".$level4['name']."</b></td><td><b>".number_format($net_total, 2)."</b></td></tr>";
							$i++;
						}
						$grand_total = $grand_total + $net_total;
						$deppreciation = $deppreciation + $depp1_total;
					}








/*

					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['[account_no']."%' and account_no >= 1231 and account_no <= 1239");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$depp1_total = 0;
						$fixed_res = mysql_query("select f.id as id, sum(f.initial_value) as amount from fixed_asset f left join sold_asset s on s.asset_id=f.id where f.account_id='".$level4['id']."' and f.date <='".$date."' and (s.date >'".$date."' or s.date is null) group by f.id");
						$fixed = mysql_fetch_array($fixed_res);
						if($fixed['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 123101 and account_no <= 123999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$depp2_total = 0;
								$fixed_res = mysql_query("select f.id as id, sum(f.initial_value) as amount from fixed_asset f left join sold_asset s on s.asset_id=f.id where f.account_id='".$level5['id']."' and f.date <='".$date."' and (s.date >'".$date."' or s.date is null) group by f.id");
								$fixed = mysql_fetch_array($fixed_res);
								if($fixed['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >=12310101 and account_no <= 12399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$fixed_res = mysql_query("select f.id as id, sum(f.initial_value) as amount from fixed_asset f left join sold_asset s on s.asset_id=f.id where f.account_id='".$level6['id']."' and f.date <= '".$date."' and (s.date >'".$date."' or s.date is null) group by f.id");
										$fixed = mysql_fetch_array($fixed_res);
										$sub2_total = $sub2_total + $fixed['amount'];
										$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id='".$fixed['id']."' and date<= '".$date."'");
										$depp = mysql_fetch_array($depp_res);
										$depp2_total = $depp2_total + $depp['amount'];
									}
								}else{
								//$fixed = mysql_fetch_array($fixed_res);
									$sub2_total = $fixed['amount'];
									$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id='".$fixed['id']."' and date<= '".$date."'");
									$depp = mysql_fetch_array($depp_res);
									$depp2_total = $depp2_total + $depp['amount'];
								}
								if($fixed['id'] == '')
									continue;
								$color=($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td>".$level5['account_no']."</td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
								$i++;
								$acc_res = mysql_query("select * from accounts where name='Accumulated Depreciation - ".$level5['name']."'");
								$acc = mysql_fetch_array($acc_res);
								$color=($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td>".$acc['account_no']."</td><td>".$acc['name']."</td><td>".number_format($depp2_total, 2)."</td></tr>";
								$i++;
								$net_total = $sub2_total - $depp2_total;	
								$color=($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td></td><td><b>Net ".$acc['name']."</b></td><td>".number_format($net_total, 2)."</td></tr>";
								$sub1_total += $sub2_total;
								$depp1_total += $depp2_total;
								$i++;
							}
						}else{
							$sub1_total = $fixed['amount'];
							$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id='".$fixed['id']."' and date<= '".$date."'");
							$depp = mysql_fetch_array($depp_res);
							$depp1_total = $depp1_total + $depp['amount'];
						}
						$color=($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color ><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";	
						$i++;
						$net_total = $sub1_total - $depp1_total;
						if($level4['name'] != 'Land'){
							$acc_res = mysql_query("select * from accounts where name='Accumulated Depreciation - ".$level4['name']."'");
							$acc = mysql_fetch_array($acc_res);
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color ><td><b>".$acc['account_no']."</b></td><td><b>".$acc['name']."</b></td><td><b>".number_format($depp1_total, 2)."</b></td></tr>";
							$i++;
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td></td><td><b>Net ".$level4['name']."</b></td><td><b>".number_format($net_total, 2)."</b></td></tr>";
							$i++;
						}
						
						$grand_total = $grand_total + $net_total;
					} */
				}
				$level2_total += $grand_total;
				$color=($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td><b>".$level3['account_no']."</b></td><td><b> ".$level3['name']."</b></td><td><b>".number_format($grand_total, 2)."</b></td></tr>";	
				$i++;
				if($level3['account_no'] == 111){
					$loss_res = mysql_query("select * from accounts where account_no='112'");
					$loss = mysql_fetch_array($loss_res);
					$color=($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td><b> ".$loss['account_no']."</b></td><td><b> ".$loss['name']."</b></td><td><b>".number_format($grand_loss, 2)."</b></td></tr>";
					$i++;
					$color=($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td><b></b></td><td><b> TOTAL NET LOAN PORTFOLIO</b></td><td><b>".number_format($grand_net, 2)."</b></td></tr>";
					$i++;
					$level2_total -= $grand_loss;
					$grand_loss = 0;
				}
			}	
			$color=($i%2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color><td><font size=4pt><b>".$level2['account_no']."</b></font></td><td><font size=4pt><b> ".$level2['name']."</b></font></td><td><font size=4pt><b>".number_format($level2_total,  2)."</b></font></td></tr>";	
			$assets += $level2_total;
			$i++;
		}
		$color=($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr bgcolor=$color><td><font size=5pt><b>1</b></font></td><td><font size=5pt><b> ASSETS</b></font></td><td><font size=5pt><b>".number_format($assets, 2)."</b></font></td></tr>";	
		$content .= "</table>";
		$resp->assign("display_div", "innerHTML", $content);
		return $resp;
}


//SUB (ASSETS) TRIAL BAL
function lctrial_balance($date, $basis,$branch_id){
	$resp = new xajaxResponse();
	list($year,$month,$mday) = explode('-', $date);
	if($year == ''){
		$year = date('Y');
		$month = date('m');
		$mday = date('d');
	}
	$resp->assign("status", "innerHTML", "");
	$date = sprintf("%d-%02d-%02d 23:59:59", $year, $month, $mday);
	$branch_res = mysql_query("select * from branch");
	$branch = mysql_fetch_array($branch_res);
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:'and branch_id='.$branch_id;
	
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR LIABILITIES AND CAPITAL SUBSIDIARY TRIAL BALANCE</h3>
                                <p class="text-default nm"></p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Date:</label>
                                           <input type="text" class="form-control" id="date" name="date" placeholder="date" />       
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Basis:</label>
                                          <select id="basis" class="form-control"><option value="Cash Basis">Cash Basis<option value="Accrual Basis">Accrual Basis</select>        
                                        </div>
                                        
                                    </div>
                                </div>  
                                
                       <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Branch:</label>
                                           '.branch_rep($branch_id).'        
                                        </div>
                                                                            
                                    </div>
                                </div>';                                 
                                   	
		 $content .= "<div class='panel-footer'>                              
                               
                                <button type='button' class='btn  btn-primary' onclick=\"xajax_lctrial_balance(getElementById('date').value,getElementById('basis').value,getElementById('branch_id').value);\">Show Report</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","date");
                    	
	$content .= "<a href='export/lctrial?year=".$year."&month=".$month."&mday=".$mday."&branch_id=".$branch_id."&basis=".$basis."' target=blank()><b>Printable Version</b></a> | <a href='export/lctrial?year=".$year."&month=".$month."&mday=".$mday."&branch_id=".$branch_id."&basis=".$basis."&format=excel' target=blank()><b>Export Excel</b></a>";
		$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">'.$branch['branch_name'].'</h3>
                                <p>LIABILITIES AND CAPITAL SUBSIDIARY TRIAL BALANCE AS AT '.$date.'</p>
                                <p>'.$basis.'</p>
                            </div>';
 		$content .= "<table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>
		<tr CLASS='headings'><td><font size=5pt><b>ACCOUNT</b></font></td><td><font size=5pt><b>DESCRIPTION</b></font></td><td><font size=5pt><b>BALANCE</b></font></td></tr>";
		$level2_res= mysql_query("select * from accounts where account_no >20 and account_no <40 ".$branch_." order by account_no");
		$capital = 0;
		$liabilities = 0;
		$content .= "<tr bgcolor=lightgrey><td><font size=5pt><b>2</b></font></td><td><font size=5pt><b>LIABILITIES</b></font></td><td><font size=5pt><b></b></font></td></tr>";
		$i=1;
		while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
			$color = ($i%2 == 0) ? "lightgrey" : "white";
			if($level2['account_no'] ==31){
				$content .= "<tr bgcolor=$color><td><font size=5pt><b>2</b></font></td><td><font size=5pt><b>LIABILITIES</b></font></td><td><font size=5pt><b>".number_format($liabilities, 2)."</b></font></td></tr>";
				$i++;
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .="<tr bgcolor=$color><td><font size=5pt><b>3</b></font></td><td><font size=5pt><b>CAPITAL</b></font></td><td></td></tr>";
				$i++;
			}
			$color = ($i%2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color><td><font size=4pt><b>".$level2['account_no']."</b></font></td><td><font size=4pt><b>".$level2['name']."</b></font></td><td></td></tr>";
			$i++;
			$level3_res = mysql_query("select * from accounts where account_no >=211 and account_no <= 399 and account_no like '".$level2['account_no']."%' order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td><b>".$level3['account_no']."</b></td><td><b>".$level3['name']."</b></td><td></td></tr>";
				$i++;
				$grand_total = 0;
				$savings = 0;
				if($level3['account_no'] == '211'){  //SAVINGS & DEPOSITS
					//MONTHLY CHARGES
					$charge_res = mysql_query("select sum(amount) as amount from monthly_charge where date <= '".$date."'");
					$charge = mysql_fetch_array($charge_res);
					$grand_total -= $charge['amount'];
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=2111 and account_no <= 2119");
					while($level4 = mysql_fetch_array($level4_res)){
						if($level4['name'] == 'Compulsory Savings')
							continue;
						$sub1_total = 0;
						$prod_res = mysql_query("select * from savings_product where account_id='".$level4['id']."'");
						if(mysql_numrows($prod_res) > 0){
							$prod = mysql_fetch_array($prod_res);
							$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and d.date<= '".$date."'");
							$dep = mysql_fetch_array($dep_res);
							$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.id='".$prod['id']."' and p.type='free' and w.date<= '".$date."'");
							$with = mysql_fetch_array($with_res);
							
							$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and i.date<= '".$date."'");
							$int = mysql_fetch_array($int_res);
							$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
							$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') and o.date <= '".$date."'"));
							$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;
							//OFFSETTING FROM SAVINGS
							$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' and p.date<= '".$date."'");
							$pay = mysql_fetch_array($pay_res);
							$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;

							//SHARES OFFSET FROM SAVINGS
							$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') and s.date <= '".$date."'"));
							$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;

							$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and i.date<= '".$date."'");
							$int = mysql_fetch_array($int_res);
							
							$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
							$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
							$sub1_total = $dep_amt + $int_amt - $with_amt - $pay_amt - $income_amt - $share_amt;  // + $int['amount'];
						}else{
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 211101 and account_no <= 211999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total =0;
								$prod_res = mysql_query("select * from savings_product where account_id='".$level5['id']."'");
								if(mysql_numrows($prod_res) > 0){
									$prod = mysql_fetch_array($prod_res);
									$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and d.date<= '".$date."'");
									$dep = mysql_fetch_array($dep_res);
									$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.id='".$prod['id']."' and p.type='free' and w.date<= '".$date."'");
									$with = mysql_fetch_array($with_res);
									
									$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and i.date<= '".$date."'");
									$int = mysql_fetch_array($int_res);
									$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
									//OFFSETTING FROM SAVINGS
									$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' and p.date<= '".$date."'");
									$pay = mysql_fetch_array($pay_res);
									$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;

									//SHARES OFFSET FROM SAVINGS
									$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') and s.date <= '".$date."'"));
									$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;

									$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and i.date<= '".$date."'");
									$int = mysql_fetch_array($int_res);

									$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') and o.date <= '".$date."'"));
									$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;
									$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
									
									$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
									$sub2_total = $dep_amt + $int_amt - $with_amt - $income_amt - $pay_amt - $share_amt;
									$sub1_total = $sub1_total + $sub2_total;
								}else{
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 21110101 and account_no <= 21199999");
									while($level6 = mysql_fetch_array($level6_res)){
										$prod_res = mysql_query("select * from savings_product where account_id='".$level6['id']."'");
										if(mysql_numrows($prod_res) > 0){
											$prod = mysql_fetch_array($prod_res);
											$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and d.date<= '".$date."'");
											$dep = mysql_fetch_array($dep_res);
											$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.id='".$prod['id']."' and p.type='free' and w.date<= '".$date."'");
											$with = mysql_fetch_array($with_res);
											
											$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and i.date<= '".$date."'");
											$int = mysql_fetch_array($int_res);
											$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
											//OFFSETTING FROM SAVINGS
											$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' and p.date<= '".$date."'");
											$pay = mysql_fetch_array($pay_res);
											$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;

											//SHARES OFFSET FROM SAVINGS
											$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') and s.date <= '".$date."'"));
											$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;

											$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') and o.date <= '".$date."'"));
											$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;
										
											$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
											$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
											$sub2_total = $sub2_total + $dep_amt + $int_amt- $with_amt - $income_amt - $pay_amt - $share_amt;  // + $int['amount'];
										}
									}
									$sub1_total = $sub1_total + $sub2_total;
								}
								$color = ($i%2 == 0) ? "lightgrey" : "white";
								$content .="<tr bgcolor=$color><td>".$level5['account_no']."</td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>"; 
								$i++;
							}
						}
						$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .="<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						$i++;
						$savings += $sub1_total;
						$grand_total = $grand_total + $sub1_total;
				}
				$level2_total += $grand_total;
				$liabilities += $grand_total;
			}elseif($level3['account_no'] >='212' && $level3['account_no'] <= 299){
				$grand_total=0;
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=2121 and account_no <= 2999");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['name'] == 'Interest Payable on Savings Deposits'){
					/*	$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.name like '%Savings%' and i.date <'".$date."'");
						
						$int = mysql_fetch_array($int_res);
						$sub1_total = $int['amount'];
						*/
						;
					}elseif($level4['name'] == 'Interest Payable on Time Deposits'){
						/* $int_res = mysql_query("select sum(amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.name like '%Time%' and i.date <= '".$date."'");
						$int = mysql_fetch_array($int_res);
						$sub1_total = $int['amount'];
						*/
						;
					}elseif($level4['name'] == 'Dividends Payable'){
						$div_res = mysql_query("select sum(total_amount) as amount from  share_dividends where bank_account=0 and date<= '".$date."'");
						$div = mysql_fetch_array($div_res);
						//$dividends = intval($div['amount']);
						$sub1_total = intval($div['amount']);
						//$interest_awarded += $sub1_total;
					}else{
						//if($basis == 'Cash Basis' && !preg_match("/^212/", $level4['account_no']))
							//continue;
						$pay_res = mysql_query("select sum(amount) as amount from payable where account_id='".$level4['id']."' and date<= '".$date."'");
						$pay = mysql_fetch_array($pay_res);
						if($pay['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 212101 and account_no <= 213999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$pay_res = mysql_query("select sum(amount) as amount from payable where account_id='".$level5['id']."' and date <= '".$date."'");
								$pay = mysql_fetch_array($pay_res);
								if($pay['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >=21210101 and account_no <= 21399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$pay_res = mysql_query("select sum(amount) as amount from payable where account_id='".$level6['id']."' and date <= '".$date."'");
										$pay = mysql_fetch_array($pay_res);
										$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id where paid.date <= '".$date."' and p.account_id='".$level4['id']."'");
										$paid = mysql_fetch_array($paid_res);
										$sub2_total = $sub2_total + $pay['amount'] - $paid['amount'];
									}
								}else{
									$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id where paid.date <= '".$date."' and p.account_id='".$level5['id']."'");
									$paid = mysql_fetch_array($paid_res);
									$sub2_total = $pay['amount'] - $paid['amount'];
								}
								$color = ($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td>".$level5['account_no']."</td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
								$i++;
							$sub1_total += $sub2_total;
							}
						}else{
							$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id where paid.date <= '".$date."' and p.account_id='".$level4['id']."'");
							$paid = mysql_fetch_array($paid_res);
							$sub1_total = $pay['amount'] - $paid['amount'];
						}
					}
					$grand_total = $grand_total + $sub1_total;
					$level2_total += $sub1_total;
					$liabilities += $sub1_total;
					$color = ($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
					$i++;
				}
			}elseif($level3['account_no'] == '311'){			
				$share_res = mysql_query("select sum(value) as value from shares where date <='".$date."'");
				$share = mysql_fetch_array($share_res);
				$level4_res = mysql_query("select * from accounts where name like 'Member Shares'");
				$level4 = mysql_fetch_array($level4_res);
				$sub1_total = $share['value'];
				$grand_total = ($sub1_total > 0) ? $sub1_total : 0;
				$level2_total += $grand_total; 
				$capital += $grand_total;
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
				$i++;
			}elseif($level3['account_no'] == '341'){
				$div_res = mysql_query("select sum(total_amount) as amount from share_dividends where date like '".$year."-%'");
				$div = mysql_fetch_array($div_res);
				$level4_res = mysql_query("select * from accounts where account_no like '3411'");
				$level4 = mysql_fetch_array($level4_res);
				//$sub1_total = ($div['amount'] != NULL) ? $div['amount'] : 0;
				//$grand_total = $grand_total + $div['amount'];
				//$level2_total += $div['amount'];
				//$capital += $div['amount'];
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
			}else{
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 3211 and account_no <= 3399");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					$invest_res = mysql_query("select sum(amount) as amount from investments where account_id='".$level4['id']."' and date <= '".$date."'");
					if(mysql_numrows($invest_res) == NULL){
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 321101 and account_no <= 339999");
						while($level5 = mysql_fetch_array($level5_res)){
							$sub2_total = 0;
							$invest_res = mysql_query("select sum(amount) as amount from investments where account_id='".$level5['id']."' and date < '".$date."'");
							if(mysql_numrows($invest_res) == NULL){
								$level6_res = mysql_query("select  * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 32110101 and account_no <= 33999999");
								while($level6 = mysql_fetch_array($level6_res)){
									$invest_res = mysql_query("select sum(amount) as amount from investments where account_id='".$level6['id']."' and date < '".$date."'");
									$invest = mysql_fetch_array($invest_res);
									$sub2_total = $sub2_total + $invest['amount'];
								}
							}else
								$sub2_total = $invest['amount'];
							$color = ($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td>".$level5['account_no']."</td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
							$i++;
							$sub1_total = $sub1_total + $sub2_total;
						}
					}else{
						$invest = mysql_fetch_array($invest_res);
						$sub1_total = $invest['amount'];
					}
					$color = ($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td>".number_format($sub1_total, 2)."</td></tr>";
					$i++;
					$grand_total = $grand_total + $sub1_total;
					$level2_total += $sub1_total;
					$capital += $sub1_total;
				}
			}
			$color = ($i%2 == 0) ? "lightgrey" : "white";

			$content .= "<tr bgcolor=$color><td><b>".$level3['account_no']."</b></td><td><b> ".$level3['name']."</b></td><td><b>".number_format($grand_total, 2)."</b></td></tr>";
			$i++;
		}
		$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr bgcolor=$color><td><font size=4pt><b>".$level2['account_no']."</b></font></td><td><font size=4pt><b> ".$level2['name']."</b></font></td><td><font size=4pt><b>".number_format($level2_total, 2)."</b></font></td></tr>";
		$i++;
	}
	$color = ($i%2 == 0) ? "lightgrey" : "white";
	$content .= "<tr bgcolor=$color><td><font size=5pt><b>3</b></font></td><td><font size=5pt><b>CAPITAL</b></font></td><td><font size=5pt><b>".number_format($capital, 2)."</b></font></td></tr>";
	$i++;
	$color = ($i%2 == 0) ? "lightgrey" : "white";
	$content .="<tr bgcolor=$color><td><font size=5pt><b>2 + 3</b></font></td><td><font size=5pt><b>LIABILITIES + CAPITAL</b></font></td><td><font size=5pt><b>".number_format(($capital + $liabilities), 2)."</b></font></td></tr></table>";
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//SUB (INCOME & EXPENSES) TRIAL BAL
function incometrial_balance($date, $basis, $branch_id){
	$resp = new xajaxResponse();
	list($year,$month,$mday) = explode('-', $date);
	if($year == ''){
		$year = date('Y');
		$month = date('m');
		$mday = date('d');
	}
	$resp->assign("status", "innerHTML", "");
	$date = sprintf("%d-%02d-%02d 23:59:59", $year, $month, $mday);
	$branch_res = mysql_query("select * from branch");
	$branch = mysql_fetch_array($branch_res);
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:"and branch_id=".$branch_id;
	
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">INCOME AND EXPENSES SUBSIDIARY TRIAL BALANCE</h3>
                                <p class="text-default nm"></p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Date:</label>
                                          <input type="text" class="form-control" id="date" name="date" placeholder="date" />       
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Basis:</label>
                                          <select id="basis" class="form-control"><option value="Cash Basis">Cash Basis<option value="Accrual Basis">Accrual Basis</select>        
                                        </div>
                                        
                                    </div>
                                </div>  
                                
                       <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Branch:</label>
                                           '.branch_rep($branch_id).'        
                                        </div>
                                                                            
                                    </div>
                                </div>';                                 
                                   	
		 $content .= "<div class='panel-footer'>                              
                               
                                <button type='button' class='btn  btn-primary' onclick=\"xajax_incometrial_balance(getElementById('date').value,getElementById('basis').value, getElementById('branch_id').value);\">Show Report</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","date");
	
	$content .= "<a href='export/incometrial?year=".$year."&month=".$month."&mday=".$mday."&branch_id=".$branch_id."&basis=".$basis."' target=blank()><b>Printable Version</b></a> | <a href='export/incometrial?year=".$year."&month=".$month."&mday=".$mday."&branch_id=".$branch_id."&basis=".$basis."&format=excel' target=blank()><b>Export Excel</b></a>";
	
$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">'.$branch['branch_name'].'</h3>
                                <p>INCOME AND EXPENSES SUBSIDIARY TRIAL BALANCE AS AT '.$date.'</p>
                                <p>'.$basis.'</p>
                            </div>';
 		$content .= "<table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>
		<tr class='headings'><td><font size=5pt><b>ACCOUNT</b></font></td><td><font size=5pt><b>DESCRIPTION</b></font></td><td><font size=5pt><b>BALANCE</b></font></td></tr>";
		$level2_res= mysql_query("select * from accounts where account_no >40 and account_no <=49 ".$branch_." order by account_no");
		$income = 0;
		$i=1;
		$content .= "<tr bgcolor=lightgrey><td><font size=5pt><b>4</b></font></td><td><font size=5pt><b>INCOME</b></font></td><td><font size=5pt><b></b></font></td></tr>";
		while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
			$color = ($i%2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color><td><font size=4pt><b>".$level2['account_no']."</b></font></td><td><font size=4pt><b>".$level2['name']."</b></font></td><td></td></tr>";
			$i++;
			$level3_res = mysql_query("select * from accounts where account_no >=411 and account_no <= 599 and account_no like '".$level2['account_no']."%' order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color ><td><b>".$level3['account_no']."</b></td><td><b>".$level3['name']."</b></td><td></td></tr>";
				$i++;
				$grand_total = 0;
				if($level3['account_no'] == '411'){
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4111 and account_no <= 4119");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						if($level4['account_no'] == 4111)
							$int_res = mysql_query("select sum(p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product prod on applic.product_id=prod.id join accounts a on prod.account_id=a.id where p.date <='".$date."' and a.name like '%Short%Term%'");
						if($level4['account_no'] == 4112)
							$int_res = mysql_query("select sum(p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product prod on applic.product_id=prod.id join accounts a on prod.account_id=a.id where p.date <='".$date."' and a.name like '%Medium%Term%'");
						if($level4['account_no'] == 4113)
							$int_res = mysql_query("select sum(p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product prod on applic.product_id=prod.id join accounts a on prod.account_id=a.id where p.date <='".$date."' and a.name like '%Long%Term%'");
						if($level4['account_no'] == 4114)
							$int_res = mysql_query("select sum(amount) as amount from penalty where date <='".$date."' and status='paid'");
						if($level4['account_no'] > 4114)
							$int_res = mysql_query("select sum(i.amount) as amount from other_income i join accounts a on i.account_id=a.id where a.account_no like '".$level4['account_no']."%' and i.date <='".$date."'");
						$int = mysql_fetch_array($int_res);
						if($int['amount'] != NULL)
							$sub1_total = $int['amount'];
						$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						$i++;
						$grand_total += $sub1_total;
					}
				}elseif($level3['account_no'] >= 412 && $level3['account_no'] <= 419){   //RECEIVABLES
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4121 and account_no <= 4199 and account_no not like '%2'");
					$pre = ($basis == 'Cash Basis') ? "(Received)" : "";
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$rec_res = mysql_query("select sum(amount) as amount from receivable where account_id='".$level4['id']."' and maturity_date <= '".$date."'");
						$rec = mysql_fetch_array($rec_res);
						
						if($rec['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 412101 and account_no <= 419999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$rec_res = mysql_query("select sum(amount) as amount from receivable where account_id='".$level5['id']."' and maturity_date <= '".$date."'");
								$rec = mysql_fetch_array($rec_res);
								if($rec['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 41210101 and account_no <= 41999999");
									while($level6 = mysql_fetch_array($level6_res)){
										$rec_res = mysql_query("select sum(amount) as amount from receivable where account_id='".$level6['id']."' and maturity_date <= '".$date."'");
										$rec = mysql_fetch_array($rec_res);
										$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where r.account_id='".$level6['id']."' and r.maturity_date <= '".$date."' and c.date <= '".$date."'");
										$col = mysql_fetch_array($col_res);
										$rec_amt = ($rec['amount'] != NULL) ? $rec['amount'] : 0;
										$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
										$top_up = ($basis == 'Accrual Basis') ? $rec_amt : $col_amt;
										$sub2_total += $top_up;
									}
								}else{
									$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where r.account_id='".$level5['id']."' and r.maturity_date <= '".$date."' and c.date <= '".$date."'");
									$col = mysql_fetch_array($col_res);
									$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
									$sub2_total = ($basis == 'Accrual Basis') ? $rec['amount'] : $col_amt;
								}
								$color = ($i%2 == 0) ? "lightgrey" : "white";
								
								$content .= "<tr bgcolor=$color><td><font size=4pt>".$level5['account_no']."</font></td><td><font size=4pt>".$level5['name']." ".$pre."</font></td><td>".number_format($sub2_total, 2)."</td></tr>";
								$i++;
								$sub1_total += $sub2_total;
							}
						}else{
							$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where r.account_id='".$level6['id']."' and r.maturity_date <= '".$date."' and c.date <= '".$date."'");
							$col = mysql_fetch_array($col_res);
							
							$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
							$top_up = ($basis == 'Accrual Basis') ? $rec['amount'] : $col_amt;
							$sub1_total += $top_up;
						}
						$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']." ".$pre."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						$i++;
						$grand_total += $sub1_total;
					}

					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4121 and account_no <= 4199 and account_no like '%2'");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						//INCOME REGISTERED DIRECTLY
						$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.account_no='".$level4['account_no']."' and o.date <='".$date."'");
						$inc = mysql_fetch_array($inc_res);
						if($inc['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 412101 and account_no <= 419999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.account_no='".$level5['account_no']."' and o.date <='".$date."'");
								$inc = mysql_fetch_array($inc_res);
								if($inc['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 41210101 and account_no <= 41999999");
									while($level6 = mysql_fetch_array($level6_res)){
										$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.account_no='".$level5['account_no']."' and o.date <='".$date."'");
										$inc = mysql_fetch_array($inc_res);						
										$sub2_total += $inc['amount'];
									}
								}else{
									$sub2_total = $inc['amount'];
								}
								$color = ($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td><font size=4pt>".$level5['account_no']."</font></td><td><font size=4pt>".$level5['name']."</font></td><td>".number_format($sub2_total, 2)."</td></tr>";
								$i++;
								$sub1_total += $sub2_total;
							}
						}else{
							$sub1_total = $inc['amount'];
					}
					$color = ($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
					$i++;
					$grand_total += $sub1_total;
				}
			}elseif($level3['account_no'] == '424'){
				$inv_res = mysql_query("select sum(quantity * purchase_price) as tot_cost, sum(quantity * amount) as tot_gain from sold_invest where date <= '".$date."'");
				$inv = mysql_fetch_array($inv_res);
				if($inv['tot_cost'] == NULL){
					$grand_total =0;
				}else{
					$grand_total = $inv['tot_gain'] - $inv['tot_cost'];
				}
			}elseif($level3['account_no'] >= 421){
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4141 and account_no <= 4299");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['account_no'] == '4221'){  //MONTHLY CHARGES
						$charge_res = mysql_query("select sum(amount) as amount from monthly_charge where date <= '".$date."'");
						$charge = mysql_fetch_array($charge_res);
						$sub1_total = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
					}elseif($level4['account_no'] == '4222'){   //TRANSACTIONAL CHARGES
						$dep_res = mysql_query("select sum(flat_value + percent_value) as amount from deposit where date <= '".$date."'");
						$dep = mysql_fetch_array($dep_res);

						$with_res = mysql_query("select sum(flat_value + percent_value) as amount from withdrawal where date <= '".$date."'");
						$with = mysql_fetch_array($with_res);
						$sub1_total = $dep['amount'] + $with['amount'];
					}elseif($level4['account_no'] == '4223'){   //INCOME FROM SALE OF ASSETS
						$sold_res = mysql_query("select sum(amount) as amount from sold_asset where date <='".$date."'");
						$sold = mysql_fetch_array($sold_res);
						$sub1_total = ($sold['amount'] != NULL) ? $sold['amount'] : 0;
					}else{	
						$inc_res = mysql_query("select sum(amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level4['id']."' and o.date <= '".$date."'");
						$inc = mysql_fetch_array($inc_res);
						if($inc['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= '421101' and account_no <= '429999'");
							//$resp->AddAppend("status", "innerHTML", "select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= '414101' and account_no <= '429999'<BR>");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$inc_res = mysql_query("select sum(amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level5['id']."' and o.date <= '".$date."'");
								
								$inc = mysql_fetch_array($inc_res);
								if($inc['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 42110101 and account_no <= 42999999");
									while($level6 = mysql_fetch_array($level6_res)){
										$inc_res = mysql_query("select sum(amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level6['id']."' and o.date <= '".$date."'");
										$inc = mysql_fetch_array($inc_res);
										$sub2_total += $inc['amount'];
									}
								}else{
									$sub2_total = $inc['amount'];
									//$resp->AddAlert("Hi");
								}
								$color = ($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td>".$level5['account_no']."</td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
								$i++;
								$sub1_total += $sub2_total;
							}
							
						}else{
							
							$sub1_total += $inc['amount'];
						}
						
					}
					$color = ($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						$i++;
					$grand_total += $sub1_total;
				}
			}
		$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr bgcolor=$color><td><b>".$level3['account_no']."</b></td><td><b>".$level3['name']."</b></td><td><b>".number_format($grand_total, 2)."</b></td></tr>";
		$i++;
		$level2_total += $grand_total;
		}
		$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr bgcolor=$color><td><font size=4pt><b>".$level2['account_no']."</b></font></td><td><font size=4pt><b>".$level2['name']."</b></font></td><td><font size=4pt><b>".number_format($level2_total, 2)."</b></font></td></tr>";
		$i++;
		$income += $level2_total;
	}
	$color = ($i%2 == 0) ? "lightgrey" : "white";
	$content .= "<tr bgcolor=$color><td><font size=5pt><b>4</b></font></td><td><font size=5pt><b>INCOME</b></font></td><td><font size=4pt><b>".number_format($income, 2)."</b></font></td></tr>";
	$i++;

// EXPENSES
	$color = ($i%2 == 0) ? "lightgrey" : "white";
	$content .= "<tr bgcolor=$color><td><font size=5pt><b>5</b></font></td><td><font size=5pt><b>COSTS & EXPENSES</b></font></td><td><font size=4pt><b></b></font></td></tr>";
	$i++;
	$level2_res = mysql_query("select * from accounts where account_no >50 and account_no <60 order by account_no");
	while($level2 = mysql_fetch_array($level2_res)){
		$level2_total = 0;
		$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr bgcolor=$color><td><font size=4pt=><b>".$level2['account_no']."</b></font></td><td><font size=4pt><b>".$level2['name']."</b></font></td><td></td></tr>";
		$i++;
		$level3_res = mysql_query("select * from accounts where account_no like '".$level2['account_no']."%' and account_no >= 511 and account_no <= 599");
		while($level3 = mysql_fetch_array($level3_res)){
			$grand_total = 0;
			$color = ($i%2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color><td><b>".$level3['account_no']."</b></td><td><b>".$level3['name']."</b></td><td></td></tr>";
			$i++;
			$int_accounts = '0';
			if($level3['account_no'] == '511'){   //INTEREST AWARDED
				$prod_res = mysql_query("select s.id as id, a.name as name, a.account_no as account_no from savings_product s  join accounts a on s.account_id=a.id where s.type='free' order by a.account_no");
				while($prod = mysql_fetch_array($prod_res)){
					$int_res = mysql_query("select sum(amount) as amount from save_interest  i join mem_accounts m on i.memaccount_id=m.id where m.saveproduct_id='".$prod['id']."' and i.date <='".$date."'");
					$int = mysql_fetch_array($int_res);
					$sub1_total = ($int['amount'] != NULL) ? $int['amount'] : 0;
					$level4_res = mysql_query("select * from accounts where name like '%Expense%on %".$prod['name']."'");
					$level4 = mysql_fetch_array($level4_res);
					$color = ($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
					$i++;
					$grand_total += $sub1_total; 
					$int_accounts .= ", '".$level4['id']."'";
				}
			
			}   //else{   //TO INCLUDE OTHER EXPENSES
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 5111 and account_no <= 5999 and id not in (".$int_accounts.") and name not like '%Interest%Expense%on%'");
				
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['name']== 'Dividends Paid on Member Shares'){
						$div_res = mysql_query("select sum(total_amount) as amount from share_dividends where date<='".$date."'");
						$div = mysql_fetch_array($div_res);
						$sub1_total = ($div['amount'] != NULL) ? $div['amount'] : 0;
					}elseif($level4['name']== 'Bad Debt Expense on Loans'){
						$write_res = mysql_query("select sum(amount) as amount from written_off where date <= '".$date."'");
						$write = mysql_fetch_array($write_res);
						$written_amt = ($write['amount'] != NULL) ? $write['amount'] : 0;
						$rec_res = mysql_query("select sum(amount) as amount from recovered where date<= '".$date."'");
						$rec = mysql_fetch_array($rec_res);
						$rec_amt = ($rec['amount'] != NULL) ? $rec['amount'] : 0;
						$sub1_total = $written_amt - $rec_amt;
					}else{
						$exp_res = mysql_query("select sum(amount) as amount from expense where account_id='".$level4['id']."' and date <='".$date."'");
						$exp = mysql_fetch_array($exp_res);
						if($exp['amount'] == NULL){
							if($level4['account_no'] == '5342'){
								$sth = mysql_query("select * from accounts where account_no like '123%' and account_no>='1231' and account_no <= 1239");
								$x=1;
								while($row = mysql_fetch_array($sth)){
									$dep_res = mysql_query("select sum(d.amount) as amount from deppreciation d join fixed_asset f on d.asset_id=f.id join accounts a on f.account_id=a.id where a.account_no like '".$row['account_no']."%' and d.date <= '$date'");
									
										$dep = mysql_fetch_array($dep_res);
										$dep_amt = ($dep['amount'] == NULL) ? 0 : $dep['amount'];
										$color = ($i%2 == 0) ? "lightgrey" : "white";
										$content .= "<tr bgcolor=$color><td>".$level4['account_no'].$x."</td><td>Depreciation - ".$row['name']."</td><td><b>".number_format($dep_amt, 2)."</b></td></tr>";
										$sub2_total = $dep_amt;
										$sub1_total += $sub2_total;
								}
							}else{
								$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= '512101' and account_no <= '599999'");
								while($level5 = mysql_fetch_array($level5_res)){
									$sub2_total = 0;
									$exp_res = mysql_query("select sum(amount) as amount from expense where account_id='".$level5['id']."' and date <='".$date."'");
									$exp = mysql_fetch_array($exp_res);
									if($exp['amount'] == NULL){
										$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= '51210101' and account_no <= '59999999'");
										while($level6 = mysql_fetch_array($level6_res)){
											$exp_res = mysql_query("select sum(amount) as amount from expense where account_id='".$level6['id']."' and date <='".$date."'");
											$exp = mysql_fetch_array($exp_res);
											$sub2_total += $exp['amount'];
										}	
									}else{
										$sub2_total = $exp['amount'];
									}
									$color = ($i%2 == 0) ? "lightgrey" : "white";
									$content .= "<tr bgcolor=$color><td>".$level5['account_no']."</td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
									$i++;
									$sub1_total += $sub2_total;
								}
							}
							
						}else{
							$sub1_total = ($exp['amount'] != NULL) ? $exp['amount'] : 0;
						}
						
					}
					$color = ($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
					$i++;
					$grand_total += $sub1_total;
					
				}
			//}    //for the else '511'
		
			if($level3['account_no'] == '561'){
			//ACCOUNTS PAYABLE
				$pay_res = mysql_query("select p.account_id as account_id, a.name as name, sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where p.bank_account =0 and p.date <= '".$date."' group by p.account_id");
				//$resp->AddAlert(mysql_error());
				$y=1;
				while($pay = mysql_fetch_array($pay_res)){
					//$pay = mysql_fetch_array($pay_res);
					$pay_amt = ($pay['amount'] == NULL) ? 0 : $pay['amount'];
					
					$color=($i%2 == 0) ? lightgrey : "white";
					$content .= "<tr bgcolor=$color><td><b>561".$y."</b></td><td><b> ".$pay['name']." Expenses</b></td><td><b>".number_format($pay_amt, 2)."</b></td></tr>";
					$i++;
					$grand_total += $pay_amt;  // - $paid_amt;
					$y++;
				}
			}
			$color=($i%2 == 0) ? lightgrey : "white";
			$content .= "<tr bgcolor=$color><td><b>".$level3['account_no']."</b></td><td><b>".$level3['name']."</b></td><td><b>".number_format($grand_total, 2)."</b></td></tr>";
			$i++;
			$level2_total += $grand_total;
		}
		$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr bgcolor=$color><td><font size=4pt><b>".$level2['account_no']."</b></font></td><td><font size=4pt><b>".$level2['name']."</b></font></td><td><font size=4pt><b>".number_format($level2_total, 2)."</b></font></td></tr>";
		
		$i++;
		if($level2['account_no'] ==51)
			$financial = $level2_total;
		if($level2['account_no'] == 52)
			$non_financial = $level2_total;
		$expense += $level2_total;
	}
	$color = ($i%2 == 0) ? "lightgrey" : "white";
	$content .= "<tr bgcolor=$color><td><font size=5pt><b>5</b></font></td><td><font size=5pt><b>COSTS & EXPENSES</b></font></td><td><font size=5pt><b>".number_format($expense, 2)."</b></font></td></tr>";
	$content .= "</table>";
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


//GENERAL TRIAL BALANCE
function trial_balance($date, $basis,$branch_id){
list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	if($year == ''){
		$year = date('Y');
		$month = date('m');
		$mday = date('d');
	}
	$resp->assign("status", "innerHTML", "");
	$date = sprintf("%d-%02d-%02d 23:59:59", $year, $month, $mday);
	$branch_res = mysql_query("select * from branch");
	$branch = mysql_fetch_array($branch_res);
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:'and branch_id='.$branch_id;
	$credit =0;
	$debit=0;
	$accrual_expense = 0;
	$interest_awarded =0;
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR GENERAL TRIAL BALANCE</h3>
                                <p class="text-default nm"></p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Date:</label>
                                           <input type="text" class="form-control" id="date" name="date" placeholder="date" />
	
	        
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Basis:</label>
                                          <select id="basis" class="form-control"><option value="Cash Basis">Cash Basis<option value="Accrual Basis">Accrual Basis</select>        
                                        </div>
                                        
                                    </div>
                                </div>  
                                
                       <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Branch:</label>
                                           '.branch_rep($branch_id).'        
                                        </div>
                                                                            
                                    </div>
                                </div>';                                 
                                   	
		 $content .= "<div class='panel-footer'>                              
                               
                                <button type='button' class='btn  btn-primary' onclick=\"xajax_trial_balance(getElementById('date').value,getElementById('basis').value,getElementById('branch_id').value);\">Show Report</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","date");
                    
	$content .= "<a href='export/trial?year=".$year."&month=".$month."&mday=".$mday."&branch_id=".$branch_id."&basis=".$basis."' target=blank()><b>Printable Version</b></a> | <a href='export/trial?year=".$year."&month=".$month."&mday=".$mday."&branch_id=".$branch_id."&basis=".$basis."&format=excel' target=blank()><b>Export Excel</b></a>";
	
	
	$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">'.$branch['branch_name'].'</h3>
                                <p>GENERAL TRIAL BALANCE AS AT '.$date.'</p>
                                <p>'.$basis.'</p>
                            </div>';
 		$content .= "<table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>
		<tr class='headings'><td colspan=2 align=center><font size=5pt><b>ACCOUNTS</b></font></td><td><font size=5pt><b>DEBIT</b></font></td><td><font size=5pt><b>CREDIT</b></font></td></tr>
		<tr bgcolor=lightgrey><td colspan=2 align=center><font size=5pt><b>ASSET ACCOUNTS (1 SERIES)</b></font></td><td><font size=5pt><b></b></font></td><td><font size=5pt><b></b></font></td></tr>";
	//ASSETS
		$i=1;
		$level2_res= mysql_query("select * from accounts where account_no >10 and account_no <20 ".$branch_." order by account_no");
		$assets = 0;
		while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
			
			$level3_res = mysql_query("select * from accounts where account_no >=111 and account_no <= 199 and account_no like '".$level2['account_no']."%' and account_no not in (124, 112) order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				//$content .= "<tr bgcolor=$color><td colspan=2 align=left><b>".$level3['name']."</b></td><td></td></tr>";
				$i++;
				$grand_total = 0;
				if($level3['account_no'] == '111'){  //LOANS
					$grand_loss = 0;
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1111 and account_no <= 1119");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$loss_total=0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 111101 and account_no <= 111999");
						//if( mysql_numrows($level5_res) > 0){
							//$color=($i%2 == 0) ? "lightgrey" : "white";
							//$content .= "<tr bgcolor=$color><td></td><td><b>".$level4['name']."</b></td><td></td></tr>";
						//	$i++;
						//}
						while($level5 = mysql_fetch_array($level5_res)){
							$loan1_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level5['id']."' and d.date <= '".$date."'");
							$loan1 = mysql_fetch_array($loan1_res);
							
							if($loan1['amount'] == NULL){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 11110101 and account_no <= 11199999");
								if(mysql_numrows($level6_res) >0){
									
									$sub2_total = 0;
									$loss2_total = 0;
									while($level6 = mysql_fetch_array($level6_res)){
										$loan2_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level6['id']."' and d.date <= '".$date."'");
										$loan2 = mysql_fetch_array($loan2_res);
										//$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level6['id']."' and d.date <= '".$date."' and pay.date <='".$date."'");
										$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level6['id']."' and pay.date <='".$date."'");
										$pay = mysql_fetch_array($pay_res);
										$loan2_amt = ($loan2['amount'] == NULL) ? 0 : $loan2['amount'];
										
										$loss_res = mysql_query("select sum(w.amount) as amount from written_off w join disbursed d on w.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where w.date <= '".$date."' and p.account_id='".$level6['id']."'");
										$loss = mysql_fetch_array($loss_res);
										$loss2_total = $loss2_total + $loss['amount'];
										$sub2_total = $sub2_total + $loan2_amt - $pay['amount'] - $loss['amount'];
									}
								} 
							}else{
								//$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level5['id']."' and d.date <= '".$date."' and pay.date <='".$date."'");
								$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level5['id']."'  and pay.date <='".$date."'");

								$pay = mysql_fetch_array($pay_res);
								$loss_res = mysql_query("select sum(w.amount) as amount from written_off w join disbursed d on w.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where w.date <= '".$date."' and p.account_id='".$level5['id']."'");
								$loss = mysql_fetch_array($loss_res);
								$loss2_total = $loss['amount'];
								$sub2_total = $loan1['amount'] - $pay['amount'] - $loss['amount'];
							}
						//	$color=($i%2 == 0) ? "lightgrey" : "white";
						//	$content .= "<tr bgcolor=$color><td></td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
						//	$i++;
							
							$sub1_total = $sub1_total + $sub2_total;
							$loss_total = $loss_total + $loss2_total;
							$sub2_total = 0;
						}
						//$color=($i%2 == 0) ? "lightgrey" : "white";
						//$content .= "<tr bgcolor=$color><td></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						 //$i++;
						/* $written_res = mysql_query("select * from accounts where account_no like '112%' and name like '%".$level4['name']."'");
						$written = mysql_fetch_array($written_res);
						$color=($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color ><td><b>".$written['account_no']."</b></td><td><b>".$written['name']."</b></td><td><b>".$loss1_total."</b></td></tr>";
						$i++;
						*/

						$grand_total = $grand_total + $sub1_total;
						//$grand_loss = $grand_loss + $loss_total;
					}
						
					

					//LOAN LOSS ALLOWANCES
					//LOAN LOSS ALLOWANCES
					$grand_loss=0;
					$prov_res = mysql_query("select * from provissions order by percent asc");
					while($prov = mysql_fetch_array($prov_res)){
						preg_match("/(\d*)[_](\d*)$/", $prov['range'], $arg);
						$interval = ($arg[1] <>180) ? ($arg[2] - $arg[1]) : 180;
						if($arg[2]==''){
							$arg[1] =180;
							$arg[2]=660;     //UP TO 22 MONTHS OF ARREARS, THE ASSUMPTION IS THAT BEYOND THAT, THE LOAN WILL HAVE BEEN WRITTEN OFF
						}
						
						$over_180 =0;
						$sched_res = mysql_query("select sum(princ_amt) as amount, loan_id as loan_id from schedule where date <= DATE_SUB('".$date."', INTERVAL ".$arg[1]." DAY) and date >= DATE_SUB('".$date."', INTERVAL ".$arg[2]." DAY) and loan_id not in (select loan_id from written_off where date <= DATE_SUB('".$date."', INTERVAL ".$arg[1]." DAY)) group by loan_id");
					
					
						while($sched = mysql_fetch_array($sched_res)){
							$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
							$all = mysql_fetch_array(mysql_query("select sum(princ_amt) as amount from schedule where date <= DATE_SUB('".$date."', INTERVAL ".$arg[2]." DAY)  and loan_id='".$sched['loan_id']."'"));
							$paid_res = mysql_query("select sum(princ_amt) as amount from payment where date <='".$date."' and loan_id='".$sched['loan_id']."'");
							$paid = mysql_fetch_array($paid_res);
							$paid_amt = ($paid['amount'] == NULL) ? 0 : $paid['amount'];
							$arrears = $all['amount'] - $paid_amt;
							if($arrears < 0){
								$arrears =0;
							}
							
							$arrears = ($arrears > $sched_amt) ? $sched_amt : $arrears;
							$over_180  += $arrears;

						}	
						$loss180_over = floor($over_180 * $prov['percent']/100);
						$grand_loss += $loss180_over;
					}

					//MANUAL LOAN LOSS PROVISSION
					$static = mysql_fetch_array(mysql_query("select sum(amount) as amount from otherloan_loss where date <= '".$date."'"));
					$static_amt = ($static['amount'] == NULL) ? 0 : $static['amount'];
					$grand_loss = $grand_loss + $static_amt;
					$grand_net = $grand_total - $grand_loss;  //SUBTRACT LOAN LOSS PROVISIONS


/*
					//FOR OVER 180 DAYS
					$sched_res = mysql_query("select sum(princ_amt) as amount from schedule where date < DATE_SUB('".$date."', INTERVAL 180 DAY)");
					$sched = mysql_fetch_array($sched_res);
					$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
					$paid_res = mysql_query("select sum(princ_amt) as amount from payment where date <='".$date."'");
					$paid = mysql_fetch_array($paid_res);
					$paid_amt = ($paid['amount'] == NULL) ? 0 : $paid['amount'];
					$over_180 = $sched_amt - $paid_amt;
					$over_180 = ($over_180 > 0) ? $over_180 : 0;
					$sth=mysql_query("select * from provissions where range='range180_over'");
					$row = mysql_fetch_array($sth);
					$loss180_over = floor($over_180 * $row['percent']/100);

					//FROM 121 TO 180 DAYS
					$sched_res = mysql_query("select sum(princ_amt) as amount from schedule where date < DATE_SUB('".$date."', INTERVAL 120 DAY)");
					$sched = mysql_fetch_array($sched_res);
					$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
					
					$from121_180 = $sched_amt - $paid_amt;
					$from121_180 = ($from121_180 >0) ? $from120_180 : 0;
					$from121_180 = $from121_180 - $over_180;
					$sth=mysql_query("select * from provissions where range='range121_180'");
					$row = mysql_fetch_array($sth);
					$loss121_180 = floor($from121_180 * $row['percent']/100);

					//FROM 91 TO 120 DAYS
					$sched_res = mysql_query("select sum(princ_amt) as amount from schedule where date < DATE_SUB('".$date."', INTERVAL 90 DAY)");
					$sched = mysql_fetch_array($sched_res);
					$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
					$from91_120 = $sched_amt - $paid_amt;
					$from91_120 = ($from91_120 >0) ? $from91_120 : 0;
					$from91_120 = $from91_120 - $from121_180 - $over_180;    
					$sth=mysql_query("select * from provissions where range='range91_120'");
					$row = mysql_fetch_array($sth);
					$loss91_120 = floor($from91_120 * $row['percent']/100);

					//FROM 61 TO 90 DAYS
					$sched_res = mysql_query("select sum(princ_amt) as amount from schedule where date < DATE_SUB('".$date."', INTERVAL 60 DAY)");
					$sched = mysql_fetch_array($sched_res);
					$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
					$from61_90 = $sched_amt - $paid_amt;
					$from61_90 = ($from61_90 >0) ? $from61_90 : 0;
					$from61_90 = $from61_90 - $from91_120 - $from121_180 - $over_180;
					$sth=mysql_query("select * from provissions where range='range61_90'");
					$row = mysql_fetch_array($sth);
					$loss61_90 = floor($from61_90 * $row['percent']/100);
					
					//FROM 31 TO 90 DAYS
					$sched_res = mysql_query("select sum(princ_amt) as amount from schedule where date < DATE_SUB('".$date."', INTERVAL 30 DAY)");
					$sched = mysql_fetch_array($sched_res);
					$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
					$from31_60 = $sched_amt - $paid_amt;
					$from31_60 = ($from31_60 >0) ? $from31_60 : 0;
					$from31_60 = $from31_60 - $from61_90 - $from91_120 - $from121_180 - $over_180;
					$sth=mysql_query("select * from provissions where range='range31_60'");
					$row = mysql_fetch_array($sth);
					$loss31_60 = floor($from31_60 * $row['percent']/100);

					//FROM 1 TO 30 DAYS
					$sched_res = mysql_query("select sum(princ_amt) as amount from schedule where date < DATE_SUB('".$date."', INTERVAL 30 DAY)");
					$sched = mysql_fetch_array($sched_res);
					$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
					$from1_30 = $sched_amt - $paid_amt;
					$from1_30 = ($from1_30 >0) ? $from1_30 : 0;
					$from1_30 = $from1_30 - $from31_60 - $from61_90 - $from91_120 - $from121_180 - $over_180;
					$sth=mysql_query("select * from provissions where range='range1_30'");
					$row = mysql_fetch_array($sth);
					$loss1_30 = floor($from1_30 * $row['percent']/100);
					
					//MANUAL LOAN LOSS PROVISSION
					$static = mysql_fetch_array(mysql_query("select sum(amount) as amount from otherloan_loss where date <= '".$date."'"));
					$static_amt = ($static['amount'] == NULL) ? 0 : $static['amount'];
					$grand_loss = $loss1_30 + $loss31_60 + $loss61_90 + $loss91_120 + $loss121_180 + $loss180_over + $static_amt;
*/
					$grand_total = $grand_total - $grand_loss;  //SUBTRACT LOAN LOSS PROVISIONS
				}elseif($level3['account_no'] >= 113 && $level3['account_no'] <= 119){   //INVESTMENTS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1131 and account_no <= 1199");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level4['id']."' and date <='".$date."'");
						$invest = mysql_fetch_array($invest_res);
						if($invest['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 113101 and account_no <= 119999");
							while($level5 = mysql_fetch_array($level5_res)){
								$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level5['id']."' and date<='".$date."'");
								$invest = mysql_fetch_array($invest_res);
								$sub2_total=0;
								if($invest['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 11310101 and account_no <= 11399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level5['id']."' and date <='".$date."'");		
										$invest = mysql_fetch_array($invest_res);

										//$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level6['id']."' and date <='".$date."' order by date desc limit 1"));
										$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level6['id']."' and date <='".$date."'");
										$sold = mysql_fetch_array($sold_res);
										$sold_amt = ($sold['amount'] == NULL) ? 0 : $sold['amount'];
										$sub2_total = $sub2_total + $invest['amount'] - $sold_amt;
									}
								}else{
									//$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level5['id']."' and date <='".$date."' order by date desc limit 1"));
									$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level5['id']."' and date <='".$date."'");
									$sold = mysql_fetch_array($sold_res);
									$sold_amt = ($sold['amount'] == NULL) ? 0 : $sold['amount'];
									$sub2_total = $invest['amount'] - $sold_amt;
								}
								$sub1_total = $sub1_total + $sub2_total;
							}
						}else{
							//$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level4['id']."' and date <='".$date."' order by date desc limit 1"));
							$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level4['id']."' and date <='".$date."'");
							$sold = mysql_fetch_array($sold_res);
							$sold_amt = ($sold['amount'] == NULL) ? 0 : $sold['amount'];
							$sub1_total = $invest['amount'] - $sold_amt;
						}
						$grand_total = $grand_total + $sub1_total;
					}
				}elseif($level3['account_no'] >= 125){   //INVESTMENTS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1251 and account_no <= 1259");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						if($level4['account_no'] == '1252'){
							//INTEREST PAYABLE ON SAVINGS
							/* $int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.type='free' and i.date<= '".$date."'");
							$int = mysql_fetch_array($int_res);
							//$sub1_total = ($int['amount'] == NULL) ? 0 : $int['amount'];
							//ACCOUNTS PAYABLE
							$pay_res = mysql_query("select sum(amount) as amount from payable where bank_account =0 and date <= '".$date."'");
							$pay = mysql_fetch_array($pay_res);
							$pay_amt = ($pay['amount'] == NULL) ? 0 : $pay['amount'];
							//$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid left join payable p on paid.payable_id=p.id where p.bank_account=0 and paid.date <= '".$date."'");
							$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id join accounts a on p.account_id=a.id where paid.date <= '".$date."' and a.account_no like '213%'");
							$paid = mysql_fetch_array($paid_res);
							$paid_amt = ($paid['amount'] == NULL) ?  0 : $paid['amount'];
							$sub1_total += $pay_amt - $paid_amt;
							*/
							$sub1_total =0;
						}else{
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 125101 and account_no <= 125999");
							while($level5 = mysql_fetch_array($level5_res)){
								$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level5['id']."' and date<='".$date."'");
								$sub2_total=0;
								$invest = mysql_fetch_array($invest_res);
								if($invest['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 12510101 and account_no <= 12599999");
									while($level6 = mysql_fetch_array($level6_res)){
										$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level6['id']."' and date<='".$date."'");		
										$invest = mysql_fetch_array($invest_res);

										$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level6['id']."' and date <='".$date."' order by date desc limit 1"));
										$sold_res = mysql_query("select sum(quantity) as quantity from sold_invest where account_id='".$level6['id']."' and date <='".$date."'");
										$sold = mysql_fetch_array($sold_res);
										$sold_amt = ($sold['quantity'] == NULL) ? 0 : ($sold['quantity'] * $unit['amount']);
										$sub2_total = $sub2_total + $invest['amount'] - $sold_amt;
									}
								}else{
								//$invest = mysql_fetch_array($invest_res);
									$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level5['id']."' and date <='".$date."' order by date desc limit 1"));
									$sold_res = mysql_query("select sum(quantity) as quantity from sold_invest where account_id='".$level5['id']."' and date <='".$date."'");
									$sold = mysql_fetch_array($sold_res);
									$sold_amt = ($sold['quantity'] == NULL) ? 0 : ($sold['quantity'] * $unit['amount']);
									$sub2_total = $invest['amount'] - $sold_amt;
									
								}
								$sub1_total = $sub1_total + $sub2_total;
							}
						}
						$grand_total = $grand_total + $sub1_total;
					}
				}elseif($level3['account_no'] == 121){  //LIQUID ASSETS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1211 and account_no <= 1219");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 121101 and account_no <= 121999");
						while($level5 = mysql_fetch_array($level5_res)){
							$bank1_res = mysql_query("select * from bank_account where account_id='".$level5['id']."'");
							$sub2_total = 0;
							if(mysql_numrows($bank1_res) == 0){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 12110101 and account_no <= 12199999");
								while($level6 = mysql_fetch_array($level6_res)){
									//$resp->AddAlert("Hi");
									$bank2_res = mysql_query("select * from bank_account where account_id='".$level6['id']."'");
									$bank2 = mysql_fetch_array($bank2_res);
									//DEPOSITS
									$dep_res = mysql_query("select sum(amount) as amount from deposit where bank_account='".$bank2['id']."' and date <='".$date."'");
									$dep = mysql_fetch_array($dep_res);
									//WITHDRAWALS
									$with_res = mysql_query("select sum(amount) as amount from withdrawal where bank_account='".$bank2['id']."' and date <='".$date."'");
									$with = mysql_fetch_array($with_res);
									//OTHER INCOME
									$other_res = mysql_query("select sum(amount) as amount from other_income where bank_account='".$bank2['id']."' and date <='".$date."'");
									$other = mysql_fetch_array($other_res);
									//LOANS PAYABLE
									$loans_payable = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '212%' and p.bank_account='".$bank2['id']."' and p.date <= '".$date."'");
									$loans_payable = mysql_fetch_array($loans_payable);
									$loans_payable = ($loans_payable['amount'] != NULL) ? $loans_payable['amount'] : 0;
									//EXPENSES
									$expense_res = mysql_query("select sum(amount) as amount from expense where bank_account='".$bank2['id']."' and date <='".$date."'");
									$expense = mysql_fetch_array($expense_res);
									//PAYABLE PAID
									$payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where bank_account='".$bank2['id']."' and date <='".$date."'");	
									$payable_paid = mysql_fetch_array($payable_paid_res);
									//RECEIVABLE COLLECTED
									$collected_res = mysql_query("select sum(amount) as amount from collected where bank_account='".$bank2['id']."' and date <='".$date."'");
									$collected = mysql_fetch_array($collected_res);
									//DISBURSED LOANS
									$disb_res = mysql_query("select sum(amount) as amount from disbursed where bank_account='".$bank2['id']."' and date <= '".$date."'");
									$disb = mysql_fetch_array($disb_res);
									//PAYMENTS
									$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id  where p.date <= '".$date."' and p.bank_account='".$bank2['id']."'");
									$pay = mysql_fetch_array($pay_res);
									//PENALTIES
									$pen_res = mysql_query("select sum(p.amount) as amount from penalty p join disbursed d on p.loan_id=d.id where p.bank_account='".$bank2['id']."' and p.status='paid' and p.date <='".$date."'");
									$pen = mysql_fetch_array($pen_res);
								
									//SHARES
									$shares_res = mysql_query("select sum(value) as amount from shares where date <='".$date."' and bank_account='".$bank2['id']."'");
									$shares = mysql_fetch_array($shares_res); 
									//RECOVERED
									$rec_res = mysql_query("select sum(r.amount) as amount from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.bank_account='".$bank2['id']."' and r.date <= '".$date."'");
									$rec = mysql_fetch_array($rec_res);			
									//INVESTMENTS 
									$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where date <= '".$date."' and bank_account='".$bank2['id']."'");
									$invest = mysql_fetch_array($invest_res);
									$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
									//DIVIDENDS PAID
									$div_res = mysql_query("select sum(total_amount) as total_amount from share_dividends where date<='".$date."' and bank_account='".$bank1['id']."'");
									$div = mysql_fetch_array($div_res);
									$div_amt = ($div['amount'] != NULL) ? $div['amount'] : 0;
									//SOLD INVESTMENTS
									$soldinvest_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where bank_account ='".$bank2['id']."' and date <= '".$date."'");
									$soldinvest = mysql_fetch_array($soldinvest_res);

									//FIXED ASSETS 
									$fixed_res = mysql_query("select sum(initial_value) as amount from fixed_asset where bank_account='".$bank2['id']."' and date <='".$date."'");
									$fixed = mysql_fetch_array($fixed_res);
									$soldasset_res = mysql_query("select sum(amount) as amount from sold_asset where bank_account ='".$bank2['id']."' and date <= '".$date."'");
									$soldasset = mysql_fetch_array($soldasset_res);
									
									//CASH IMPORTED
									$import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id='".$bank2['id']."' and date <='".$date."'");
									$import = mysql_fetch_array($import_res);
									$import_amt = ($import['amount'] ==NULL) ? 0 : $import['amount'];

									//CASH EXPORTED
									$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id='".$bank2['id']."' and date <='".$date."'");
									$export = mysql_fetch_array($export_res);
									$export_amt = ($export['amount'] ==NULL) ? 0 : $export['amount'];

									
									$sub2_total = $sub2_total + $collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt;	
								}
							}else{
								$bank1 = mysql_fetch_array($bank1_res);
								//DEPOSITS
								$dep_res = mysql_query("select sum(amount) as amount from deposit where bank_account='".$bank1['id']."' and date <='".$date."'");
								$dep = mysql_fetch_array($dep_res);
								//WITHDRAWALS
								$with_res = mysql_query("select sum(amount) as amount from withdrawal where bank_account='".$bank1['id']."' and date <='".$date."'");
								$with = mysql_fetch_array($with_res);
								//OTHER INCOME
								$other_res = mysql_query("select sum(amount) as amount from other_income where bank_account='".$bank1['id']."' and date <='".$date."'");
								$other = mysql_fetch_array($other_res);
								//EXPENSES
								$expense_res = mysql_query("select sum(amount) as amount from expense where bank_account='".$bank1['id']."' and date <='".$date."'");
								$expense = mysql_fetch_array($expense_res);
								//LOANS PAYABLE
								$loans_payable = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '212%' and p.bank_account='".$bank1['id']."' and p.date <= '".$date."'");
								$loans_payable = mysql_fetch_array($loans_payable);
								$loans_payable = ($loans_payable['amount'] != NULL) ? $loans_payable['amount'] : 0;
								//PAYABLE PAID
								$payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where bank_account='".$bank1['id']."' and date <='".$date."'");
								$payable_paid = mysql_fetch_array($payable_paid_res);
								//RECEIVABLE COLLECTED
								$collected_res = mysql_query("select sum(amount) as amount from collected where bank_account='".$bank1['id']."' and date <='".$date."'");
								$collected = mysql_fetch_array($collected_res);
								//DISBURSED LOANS
								$disb_res = mysql_query("select sum(amount) as amount from disbursed where bank_account='".$bank1['id']."' and date <= '".$date."'");
								$disb = mysql_fetch_array($disb_res);
								//PAYMENTS
								$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id  where p.date <= '".$date."' and p.bank_account='".$bank1['id']."'");
								$pay = mysql_fetch_array($pay_res);
								//PENALTIES
								$pen_res = mysql_query("select sum(p.amount) as amount from penalty p join disbursed d on p.loan_id=d.id where p.bank_account='".$bank1['id']."' and p.status='paid' and p.date <= '".$date."'");
								$pen = mysql_fetch_array($pen_res);
								
								//SHARES
								$shares_res = mysql_query("select sum(value) as amount from shares where date <='".$date."' and bank_account='".$bank1['id']."'");
								$shares = mysql_fetch_array($shares_res); 
								//RECOVERED
								$rec_res = mysql_query("select sum(r.amount) as amount from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.bank_account='".$bank1['id']."' and r.date <= '".$date."'");
								$rec = mysql_fetch_array($rec_res);
								$rec_amt = ($rec['amount'] != NULL) ? $rec['amount'] : 0;
								//INVESTMENTS 
								$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where date <= '".$date."' and bank_account='".$bank1['id']."'");
								$invest = mysql_fetch_array($invest_res);
								$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
								//DIVIDENDS PAID
								$div_res = mysql_query("select sum(total_amount) as total_amount from share_dividends where date<='".$date."' and bank_account='".$bank1['id']."'");
								$div = mysql_fetch_array($div_res);
								$div_amt = ($div['amount'] != NULL) ? $div['amount'] : 0;

								
								$soldinvest_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where bank_account ='".$bank1['id']."' and date <= '".$date."'");
								$soldinvest = mysql_fetch_array($soldinvest_res);

								//FIXED ASSETS 
								$fixed_res = mysql_query("select sum(initial_value) as amount from fixed_asset where bank_account='".$bank1['id']."' and date <='".$date."'");
								$fixed = mysql_fetch_array($fixed_res);
								$soldasset_res = mysql_query("select sum(amount) as amount from sold_asset where bank_account ='".$bank1['id']."' and date <= '".$date."'");
								$soldasset = mysql_fetch_array($soldasset_res);
								//CASH IMPORTED
								$import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id=".$bank1['id']." and date <='".$date."'");
								$import = mysql_fetch_array($import_res);
								$import_amt = ($import['amount'] ==NULL) ? 0 : $import['amount'];

								//CASH EXPORTED
								$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id=".$bank1['id']." and date <='".$date."'");
								$export = mysql_fetch_array($export_res);
								$export_amt = ($export['amount'] ==NULL) ? 0 : $export['amount'];									

								$sub2_total =$collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt;	
							}
							$sub1_total = $sub1_total + $sub2_total;
						}
						$grand_total += $sub1_total;
					}
				}elseif($level3['account_no']=='122'){     //RECEIVABLES
					if($basis == 'Cash Basis')
						continue;
					$rec_res = mysql_query("select sum(amount) as amount from receivable where maturity_date <='".$date."'");
					$rec = mysql_fetch_array($rec_res);
					if($rec['amount'] != NULL){
						$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where c.date <= '".$date."'");
						$col = mysql_fetch_array($col_res);
						$grand_total =  $rec['amount'] - $col['amount'];
					}
				}elseif($level3['account_no'] == '123'){  //FIXED ASSETS
					$deppreciation = 0;
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= '1231' and account_no <= '1239' order by account_no");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$depp1_total = 0;
						$fixed_res = mysql_query("select sum(f.initial_value) as amount from fixed_asset f left join sold_asset s on s.asset_id=f.id where f.account_id='".$level4['id']."' and f.date <='".$date."' and (s.date >'".$date."' or s.date is null)");
						$fixed = mysql_fetch_array($fixed_res);
						if($fixed['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 123101 and account_no <= 123999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$depp2_total = 0;
								$fixed_res = mysql_query("select sum(f.initial_value) as amount from fixed_asset f left join sold_asset s on s.asset_id=f.id where f.account_id='".$level5['id']."' and f.date <='".$date."' and (s.date >'".$date."' or s.date is null)");
								$fixed = mysql_fetch_array($fixed_res);
								if($fixed['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >=12310101 and account_no <= 12399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$fixed_res = mysql_query("select sum(f.initial_value) as amount from fixed_asset f left join sold_asset s on s.asset_id=f.id where f.account_id='".$level6['id']."' and f.date <='".$date."' and (s.date >'".$date."' or s.date is null)");
										$fixed = mysql_fetch_array($fixed_res);
										$sub2_total = $sub2_total + $fixed['amount'];
										$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from fixed_asset where account_id='".$level6['id']."') and date<= '".$date."'");
										$depp = mysql_fetch_array($depp_res);
										$depp2_total = $depp2_total + $depp['amount'];
									}
								}else{
									//$fixed = mysql_fetch_array($fixed_res);
									$sub2_total = $fixed['amount'];
									$depp_res = mysql_query("select sum(amount) as amount from deppreciation  where asset_id in (select id from fixed_asset where account_id='".$level5['id']."') and date<= '".$date."'");
									$depp = mysql_fetch_array($depp_res);
									$depp2_total = $depp2_total + $depp['amount'];
								}
								if($fixed['id'] == '0')
									continue;
								$acc_res = mysql_query("select * from accounts where name='Accumulated Depreciation - ".$level5['name']."'");
								$acc = mysql_fetch_array($acc_res);
								$net_total = $sub2_total - $depp2_total;		
				
								$sub1_total += $sub2_total;
								$depp1_total += $depp2_total;
							}
						}else{  //IF THE FIXED ASSET IS A LEVEL 4 ACCOUNT
							$sub1_total = $fixed['amount'];
							$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from fixed_asset where account_id='".$level4['id']."') and date<= '".$date."'");
							$depp = mysql_fetch_array($depp_res);
							$depp1_total = ($depp['amount'] != NULL) ? $depp['amount'] : 0;
						}
						
						$net_total = $sub1_total - $depp1_total;
						if($level4['name'] != 'Land'){
							$acc_res = mysql_query("select * from accounts where name = 'Accumulated Depreciation - ".$level4['name']."'");
							$acc = mysql_fetch_array($acc_res);
						}
						$grand_total = $grand_total + $net_total;
						$deppreciation = $deppreciation + $depp1_total;
					}
				}elseif($level3['account_no'] == '124'){
					//$grand_total=$deppreciation;
					;
				}
				$color=($i%2 == 0) ? "lightgrey" : "white";
				$level2_total += $grand_total;
				$content .= "<tr bgcolor=$color><td width=8%>".$level3['account_no']."</td><td> ".$level3['name']."</td><td>".number_format($grand_total, 2)."</td><td>--</td></tr>";	
				$i++;
				if($level3['account_no'] == '111'){
					$loss_res = mysql_query("select * from accounts where account_no='112'");
					$loss = mysql_fetch_array($loss_res);
					$color=($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td>".$loss['account_no']."</td><td> ".$loss['name']."</td><td>".number_format($grand_loss, 2)."</td><td>--</td></tr>";	
					$i++;
					$level2_total += $grand_loss;
				}
			}			
			$assets += $level2_total;
		}

// LIABILITY AND CAPITAL ACCOUNTS
		$color=($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr bgcolor=$color><td colspan=2 align=center><font size=5pt><b>LIABILITY ACCOUNTS (2 SERIES)</b></font></td><td><font size=5pt><b></b></font></td><td><font size=5pt><b></b></font></td></tr>";
		$level2_res= mysql_query("select * from accounts where account_no >20 and account_no <40 order by account_no");
		$liabilities = 0;
		$capital = 0;
		
		while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
		
			if($level2['account_no'] ==31){
				$color=($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td colspan=2 align=center><font size=5pt><b>CAPITAL ACCOUNTS (3 SERIES)</b></font></td><td><font size=5pt><b></b></font></td><td><font size=5pt><b></b></font></td></tr>";
				$i++;
			}
			
			$level3_res = mysql_query("select * from accounts where account_no >=211 and account_no <= 399 and account_no like '".$level2['account_no']."%' order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				$grand_total = 0;
				if($level3['account_no'] == '211'){  //SAVINGS & DEPOSITS
					//MONTHLY CHARGES
					$charge_res = mysql_query("select sum(amount) as amount from monthly_charge where date <= '".$date."'");
					$charge = mysql_fetch_array($charge_res);
					$grand_total = $grand_total - $charge['amount'];
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=2111 and account_no <= 2119");
					while($level4 = mysql_fetch_array($level4_res)){
						if($level4['name'] == 'Compulsory Savings')
							continue;
						$sub1_total = 0;
						$prod_res = mysql_query("select * from savings_product where account_id='".$level4['id']."'");
						if(mysql_numrows($prod_res) > 0){
							$prod = mysql_fetch_array($prod_res);
							$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and d.date<= '".$date."'");
							$dep = mysql_fetch_array($dep_res);
							$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.id='".$prod['id']."' and p.type='free' and w.date<= '".$date."'");
							$with = mysql_fetch_array($with_res);

							$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and i.date<= '".$date."'");
							$int = mysql_fetch_array($int_res);
							$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];

							$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') and o.date <= '".$date."'"));
							$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;

							//SHARES OFFSET FROM SAVINGS
							$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') and s.date <= '".$date."'"));
							$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;

							//OFFSETTING FROM SAVINGS
							$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' and p.date<= '".$date."'");
							$pay = mysql_fetch_array($pay_res);
							$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;
							
							$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
							$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
							$sub1_total = $dep_amt +$int_amt - $with_amt - $income_amt - $pay_amt - $share_amt; // + $int['amount'];
						}else{
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 211101 and account_no <= 211999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total =0;
								$prod_res = mysql_query("select * from savings_product where account_id='".$level5['id']."'");
								if(mysql_numrows($prod_res) > 0){
									$prod = mysql_fetch_array($prod_res);
									$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and d.date<= '".$date."'");
									$dep = mysql_fetch_array($dep_res);
									$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.id='".$prod['id']."' and p.type='free' and w.date<= '".$date."'");
									$with = mysql_fetch_array($with_res);

									$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and i.date<= '".$date."'");
									$int = mysql_fetch_array($int_res);
									$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
									$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') and o.date <= '".$date."'"));
									$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;
									//OFFSETTING FROM SAVINGS
									$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' and p.date<= '".$date."'");
									$pay = mysql_fetch_array($pay_res);
									$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;

									//SHARES OFFSET FROM SAVINGS
									$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') and s.date <= '".$date."'"));
									$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;


									$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
									$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
									$sub2_total = $dep_amt + $int_amt - $with_amt - $income_amt - $pay_amt - $share_amt;  // + $int['amount'];
									$sub1_total = $sub1_total + $sub2_total;
								}else{
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 21110101 and account_no <= 21199999");
									while($level6 = mysql_fetch_array($level6_res)){
										$prod_res = mysql_query("select * from savings_product where account_id='".$level6['id']."'");
										if(mysql_numrows($prod_res) > 0){
											$prod = mysql_fetch_array($prod_res);
											$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and d.date<= '".$date."'");
											$dep = mysql_fetch_array($dep_res);
											$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.id='".$prod['id']."' and p.type='free' and w.date<= '".$date."'");
											$with = mysql_fetch_array($with_res);
											
											$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and i.date<= '".$date."'");
											$int = mysql_fetch_array($int_res);
											$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
											$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') and o.date <= '".$date."'"));
											$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;

											//OFFSETTING FROM SAVINGS
											$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' and p.date<= '".$date."'");
											$pay = mysql_fetch_array($pay_res);
											$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;

											//SHARES OFFSET FROM SAVINGS
											$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') and s.date <= '".$date."'"));
											$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;

										
											$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
											$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
											$sub2_total = $sub2_total + $dep_amt + $int_amt - $with_amt - $income_amt - $pay_amt - $share_amt; // + $int['amount'];
										}
									}
									$sub1_total = $sub1_total + $sub2_total;
								}
							}
						}
						$grand_total = $grand_total + $sub1_total;
				}
				$level2_total += $grand_total;
				$liabilities += $grand_total;
			}elseif($level3['account_no'] >=212 && $level3['account_no'] <= 299){
				$grand_total=0;
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=2121 and account_no <= 2999");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['name'] == 'Interest Payable on Savings Deposits'){
						/* THIS  INTEREST IS ALREADY PAID OUT, AND SO IT IS NOT AN ACCOUNTS PAYABLE 
						$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.name like '%Savings%' and i.date <='".$date."'");
						
						$int = mysql_fetch_array($int_res);
						$sub1_total = ($int['amount'] != NULL) ? $int['amount'] : 0;
						$interest_awarded += $sub1_total;
						*/
						;
					}elseif($level4['name'] == 'Interest Payable on Time Deposits'){
						/*
						$int_res = mysql_query("select sum(amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.name like '%Time%' and i.date <='".$date."'");
						$int = mysql_fetch_array($int_res);
						$sub1_total = ($int['amount'] !=NULL) ? $int['amount'] : 0;
						$interest_awarded += $sub1_total;
						*/
						;
					}elseif($level4['name'] == 'Dividends Payable'){
						$div_res = mysql_query("select sum(total_amount) as amount from  share_dividends where bank_account=0 and date<= '".$date."'");
						$div = mysql_fetch_array($div_res);
						$sub1_total = intval($div['amount']);
						$interest_awarded += $sub1_total;
					}else{
						//if($basis == 'Cash Basis' && !preg_match("/^212/", $level4['account_no']))
						//	continue;

						$pay_res = mysql_query("select sum(amount) as amount from payable where account_id='".$level4['id']."' and date<= '".$date."'");
						$pay = mysql_fetch_array($pay_res);
						
						if($pay['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 212101 and account_no <= 213999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$pay_res = mysql_query("select sum(amount) as amount from payable where account_id='".$level5['id']."' and date <= '".$date."'");
								$pay = mysql_fetch_array($pay_res);
								if($pay['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >=21210101 and account_no <= 21399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$pay_res = mysql_query("select sum(amount) as amount from payable where account_id='".$level6['id']."' and date <= '".$date."'");
										$pay = mysql_fetch_array($pay_res);
										$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id where paid.date <= '".$date."' and p.account_id='".$level6['id']."'");
										$paid = mysql_fetch_array($paid_res);
										$sub2_total = $sub2_total + $pay['amount'] - $paid['amount'];
									}
								}else{
									$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id where paid.date <= '".$date."' and p.account_id='".$level5['id']."'");
									$paid = mysql_fetch_array($paid_res);
									$sub2_total = $pay['amount'] - $paid['amount'];
								}
							$sub1_total += $sub2_total;
							}
						}else{
							//$pay = mysql_fetch_array($pay_res);
							$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id where paid.date <= '".$date."' and p.account_id='".$level4['id']."'");
							$paid = mysql_fetch_array($paid_res);
							$sub1_total = $pay['amount'] - $paid['amount'];
						}
						//if($level3['account_no'] == '213')
							//$resp->AddAlert($sub1_total);
					}
					$grand_total = $grand_total + $sub1_total;
					if($level3['account_no'] =='213'){
						$accrual_expense = $accrual_expense + $sub1_total;
					}
					$level2_total += $sub1_total;
					$liabilities += $sub1_total;
				}
			}elseif($level3['account_no'] == '311'){			
				$share_res = mysql_query("select sum(value) as value from shares where date <='".$date."'");
				$share = mysql_fetch_array($share_res);
				$level4_res = mysql_query("select * from accounts where name like 'Member Shares'");
				$level4 = mysql_fetch_array($level4_res);
				$sub1_total = $share['value'];
				$grand_total = ($sub1_total > 0) ? $sub1_total : 0;
				$level2_total += $grand_total; 
				$capital += $grand_total;
				
			}elseif($level3['account_no'] == '341'){
				$div_res = mysql_query("select sum(total_amount) as amount from share_dividends where date like '".$year."-%'");
				$div = mysql_fetch_array($div_res);
				$level4_res = mysql_query("select * from accounts where account_no like '3411'");
				$level4 = mysql_fetch_array($level4_res);
				//$sub1_total = ($div['amount'] != NULL) ? $div['amount'] : 0;
				//$grand_total = $grand_total + $div['amount'];
				//$level2_total += $div['amount'];
				//$capital += $div['amount'];
				
			}else{
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 3211 and account_no <= 3399");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					$invest_res = mysql_query("select sum(amount) as amount from investments where account_id='".$level4['id']."' and date <= '".$date."'");
					$invest = mysql_fetch_array($invest_res);
					if($invest['amount'] == NULL){
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 321101 and account_no <= 339999");
						while($level5 = mysql_fetch_array($level5_res)){
							$sub2_total = 0;
							$invest_res = mysql_query("select sum(amount) as amount from investments where account_id='".$level5['id']."' and date <= '".$date."'");
							$invest = mysql_fetch_array($invest_res);
							if($invest['amount'] == NULL){
								$level6_res = mysql_query("select  * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 32110101 and account_no <= 33999999");
								while($level6 = mysql_fetch_array($level6_res)){
									$invest_res = mysql_query("select sum(amount) as amount from investments where account_id='".$level6['id']."' and date <= '".$date."'");
									$invest = mysql_fetch_array($invest_res);
									$sub2_total = $sub2_total + $invest['amount'];
								}
							}else
								$sub2_total = $invest['amount'];
							$sub1_total = $sub1_total + $sub2_total;
						}
					}else{
						$sub1_total = $invest['amount'];
					}
					$grand_total = $grand_total + $sub1_total;
					$level2_total += $sub1_total;
					$capital += $sub1_total;
				}
			}
			$color=($i%2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color><td>".$level3['account_no']."</td><td> ".$level3['name']."</td><td>--</td><td>".number_format($grand_total, 2)."</td></tr>";
			$i++;
		}
	}

	//INCOME ACCOUNTS
	$color=($i%2 == 0) ? "lightgrey" : "white";
	$content .= "<tr bgcolor=$color><td colspan=2 align=center><font size=5pt><b>INCOME ACCOUNTS (4 SERIES)
</b></font></td><td><font size=5pt><b></b></font></td><td><font size=5pt><b></b></font></td></tr>";
	$i++;
	$level2_res= mysql_query("select * from accounts where account_no >40 and account_no <=49 order by account_no");
	$income = 0;
		
	while($level2 = mysql_fetch_array($level2_res)){
		$level2_total = 0;
		
		$level3_res = mysql_query("select * from accounts where account_no >=411 and account_no <= 599 and account_no like '".$level2['account_no']."%' order by account_no");
		while($level3 = mysql_fetch_array($level3_res)){
			$grand_total = 0;
			if($level3['account_no'] == '411'){
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4111 and account_no <= 4119");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['account_no'] == 4111)
						$int_res = mysql_query("select sum(p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product prod on applic.product_id=prod.id join accounts a on prod.account_id=a.id where p.date <='".$date."' and a.name like '%Short%Term%'");
					if($level4['account_no'] == 4112)
						$int_res = mysql_query("select sum(p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product prod on applic.product_id=prod.id join accounts a on prod.account_id=a.id where p.date <='".$date."' and a.name like '%Medium%Term%'");
					if($level4['account_no'] == 4113)
						$int_res = mysql_query("select sum(p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product prod on applic.product_id=prod.id join accounts a on prod.account_id=a.id where p.date <='".$date."' and a.name like '%Long%Term%'");
					if($level4['account_no'] == 4114)
						$int_res = mysql_query("select sum(amount) as amount from penalty where date <='".$date."' and status='paid'");
					if($level4['account_no'] > 4114)
						$int_res = mysql_query("select sum(i.amount) as amount from other_income i join accounts a on i.account_id=a.id where a.account_no like '".$level4['account_no']."%' and i.date <='".$date."'");
					$int = mysql_fetch_array($int_res);
					if($int['amount'] != NULL)
						$sub1_total = $int['amount'];
					
					$grand_total += $sub1_total;
				}
			}elseif($level3['account_no'] >= 412 && $level3['account_no'] <= 413){   //RECEIVABLES
				//if($basis == 'Accrual Basis'){
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4121 and account_no <= 4199 and account_no not like '%2'");      //INCOME ACCRUED
					
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$rec_res = mysql_query("select sum(amount) as amount from receivable where account_id='".$level4['id']."' and maturity_date <= '".$date."'");
						$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where r.account_id='".$level4['id']."' and c.date <= '".$date."'");
						$rec = mysql_fetch_array($rec_res);
						if($rec['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 412101 and account_no <= 419999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$rec_res = mysql_query("select sum(amount) as amount from receivable where account_id='".$level5['id']."' and maturity_date <='".$date."'");
								$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where r.account_id='".$level5['id']."' and c.date <= '".$date."'");
								$rec = mysql_fetch_array($rec_res);
								if($rec['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 41210101 and account_no <= 41999999");
									while($level6 = mysql_fetch_array($level6_res)){
										$rec_res = mysql_query("select sum(amount) as amount from receivable where account_id='".$level5['id']."' and maturity_date <= '".$date."'");
										$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where r.account_id='".$level5['id']."' and c.date <= '".$date."'");
										$rec = mysql_fetch_array($rec_res);
										$col = mysql_fetch_array($col_res);
										$rec_amt = ($rec['amount'] != NULL) ? $rec['amount'] : 0;
										$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
										$top_up = ($basis == 'Accrual Basis') ? $rec_amt : $col_amt;
										$sub2_total = $sub2_total + $top_up;
									}
								}else{
									$col = mysql_fetch_array($col_res);
									$rec_amt = ($rec['amount'] != NULL) ? $rec['amount'] : 0;
									$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
									$sub2_total = ($basis == 'Accrual Basis') ? $rec_amt : $col_amt;
								}
								$sub1_total = $sub1_total + $sub2_total;
							}
						}else{
							$col = mysql_fetch_array($col_res)
;							$rec_amt = ($rec['amount'] != NULL) ? $rec['amount'] : 0;
							$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
							$top_up = ($basis == 'Accrual Basis') ? $rec_amt : $col_amt;
							$sub1_total =$sub1_total + $top_up;
						}
						$grand_total += $sub1_total;
					}
				//}

				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4121 and account_no <= 4199 and account_no like '%2'");         //INCOME RECEIVED
				
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					//$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id join accounts a on r.account_id=a.id where a.account_no like '".$level3['account_no']."1' and c.date <='".$date."'");   //INCOME ACCOUNTS END WITH 1
					//$col = mysql_fetch_array($col_res);
					
					//INCOME REGISTERED DIRECTLY
					$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.account_no='".$level4['account_no']."' and o.date <='".$date."'");
					$inc = mysql_fetch_array($inc_res);
					if($col['amount'] == NULL && $inc['amount'] == NULL){
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 412101 and account_no <= 419999");
						while($level5 = mysql_fetch_array($level5_res)){
							$sub2_total = 0;
							$account_no = $level3['account_no']."1".substr($level5['account_no'], 2, -2);
							//$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id join accounts a on r.account_id=a.id where a.account_no like '".$account_no."' and c.date <'".$date."'");
							//$col = mysql_fetch_array($col_res);
							$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.account_no='".$level5['account_no']."' and o.date <='".$date."'");
							$inc = mysql_fetch_array($inc_res);
							if($col['amount'] == NULL && $inc['amount'] ==NULL){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 41210101 and account_no <= 41999999");
								while($level6 = mysql_fetch_array($level6_res)){
									$account_no = $level3['account_no']."1".substr($level5['account_no'], 4, -4);
									//$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id join accounts a on r.account_id=a.id where a.account_no like '".$account_no."'  and c.date <'".$date."'");
									//$col = mysql_fetch_array($rec_res);	
									$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.account_no='".$level4['account_no']."' and o.date <='".$date."'");
									$inc = mysql_fetch_array($inc_res);
									$sub2_total +=$inc['amount'];
								}
							}else{
								$sub2_total = $inc['amount'];
							}
						}
					}else{
						$sub1_total += $inc['amount'];
					}
					$grand_total += $sub1_total;
				}
			}elseif($level3['account_no'] == '424'){
				$inv_res = mysql_query("select sum(quantity * purchase_price) as tot_cost, sum(quantity * amount) as tot_gain from sold_invest where  date <= '".$date."'");
				$inv = mysql_fetch_array($inv_res);
				if($inv['tot_cost'] == NULL){
					$grand_total =0;
				}else{
					$grand_total = $inv['tot_gain'] - $inv['tot_cost'];
				}
				//$grand_total = ($grand_total > 0) ? $grand_total : 0;
			}elseif($level3['account_no'] >= 414){
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4141 and account_no <= 4299");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['account_no'] == '4221'){
						$charge_res = mysql_query("select sum(amount) as amount from monthly_charge where date <= '".$date."'");
						$charge = mysql_fetch_array($charge_res);
						$sub1_total = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
					}elseif($level4['account_no'] == '4222'){
						$dep_res = mysql_query("select sum(flat_value + percent_value) as amount from deposit where date <= '".$date."'");
						$dep = mysql_fetch_array($dep_res);

						$with_res = mysql_query("select sum(flat_value + percent_value) as amount from withdrawal where date <= '".$date."'");
						$with = mysql_fetch_array($with_res);
						$sub1_total = $dep['amount'] + $with['amount'];
					}elseif($level4['account_no']=='4223'){
						$gain = mysql_fetch_array(mysql_query("select sum(s.amount - f.initial_value) as amount from sold_asset s join fixed_asset f on s.asset_id=f.id where s.date <='".$date."'"));
						$sub1_total = ($gain['amount'] != NULL) ? $gain['amount'] : 0;
					}else{
					
						$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level4['id']."' and o.date <= '".$date."'");
						$inc = mysql_fetch_array($inc_res);
						if($inc['amount'] == NULL){
						
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 421101 and account_no <= 429999");
							
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level5['id']."' and o.date <= '".$date."'");
								//$resp->AddAppend("status", "innerHTML", "select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level5['id']."' and o.date <= '".$date."'<BR>");
								$inc = mysql_fetch_array($inc_res);
								if($inc['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 41410101 and account_no <= 42999999");
									while($level6 = mysql_fetch_array($level6_res)){
										$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level6['id']."' and o.date <= '".$date."'");
										$inc = mysql_fetch_array($inc_res);
										$sub2_total += $inc['amount'];
									}
								}else{
										//$resp->AddAlert("Hi");
									$sub2_total = $inc['amount'];
								}
								$sub1_total += $sub2_total;
							}
						}else{
							$sub1_total += $inc['amount'];
						}
					}
					$grand_total += $sub1_total;
				}
			}
		$color=($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr bgcolor=$color><td>".$level3['account_no']."</td><td>".$level3['name']."</td><td>--</td><td>".number_format($grand_total, 2)."</td></tr>";
		$i++;
		$level2_total += $grand_total;
		}
		$income += $level2_total;
	}
	

// EXPENSE ACCOUNTS
	$color=($i%2 == 0) ? "lightgrey" : "white";
	$content .= "<tr bgcolor=$color><td colspan=2 align=center><font size=5pt><b>COSTS AND EXPENSE ACCOUNTS (5 SERIES)
</b></font></td><td><font size=5pt><b></b></font></td><td><font size=5pt><b></b></font></td></tr>";
	$i++;
	$expense =0;
	$level2_res = mysql_query("select * from accounts where account_no >50 and account_no <60 order by account_no");
	while($level2 = mysql_fetch_array($level2_res)){
		$level2_total = 0;
		$level3_res = mysql_query("select * from accounts where account_no like '".$level2['account_no']."%' and account_no >= '511' and account_no <= '599'");
		while($level3 = mysql_fetch_array($level3_res)){
			$grand_total = 0;
			$int_accounts = '0';
			 if($level3['account_no'] == 511){
				 $prod_res = mysql_query("select s.id as id, a.name as name, a.account_no as account_no from savings_product s  join accounts a on s.account_id=a.id where s.type='free' order by a.account_no");
				while($prod = mysql_fetch_array($prod_res)){
					$int_res = mysql_query("select sum(amount) as amount from save_interest  i join mem_accounts m on i.memaccount_id=m.id where m.saveproduct_id='".$prod['id']."' and i.date <='".$date."'");
					$int = mysql_fetch_array($int_res);
					$sub1_total = ($int['amount'] != NULL) ? $int['amount'] : 0;
					$level4_res = mysql_query("select * from accounts where name like '%Expense%on %".$prod['name']."'");
					$level4 = mysql_fetch_array($level4_res);
					$grand_total += $sub1_total; 
					$int_accounts .= ", '".$level4['id']."'";
				}  
			}
			if($level3['account_no'] == '534'){
					$grand_total=$deppreciation;
			}else{  
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 5111 and account_no <= 5999 and id not in (".$int_accounts.") and name not like '%Interest%Expense%on%'");
				
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['name']== 'Dividends Paid on Member Shares'){
						$div_res = mysql_query("select sum(total_amount) as amount from share_dividends where date<='".$date."'");
						$div = mysql_fetch_array($div_res);
						$sub1_total = ($div['amount'] != NULL) ? $div['amount'] : 0;
					}elseif($level4['name']== 'Bad Debt Expense on Loans'){
						$write_res = mysql_query("select sum(amount) as amount from written_off where date <= '".$date."'");
						$write = mysql_fetch_array($write_res);
						$written_amt = ($write['amount'] != NULL) ? $write['amount'] : 0;
						$rec_res = mysql_query("select sum(amount) as amount from recovered where date<= '".$date."'");
						$rec = mysql_fetch_array($rec_res);
						$rec_amt = ($rec['amount'] != NULL) ? $rec['amount'] : 0;
						$sub1_total = $written_amt - $rec_amt; 
					}else{
						$exp_res = mysql_query("select sum(amount) as amount from expense where account_id='".$level4['id']."' and date <='".$date."'");
						$exp = mysql_fetch_array($exp_res);
						if($exp['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= '512101' and account_no <= '599999'");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$exp_res = mysql_query("select sum(amount) as amount from expense where account_id='".$level5['id']."' and date <='".$date."'");
								$exp = mysql_fetch_array($exp_res);
								if($exp['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= '51210101' and account_no <= '59999999'");
									while($level6 = mysql_fetch_array($level6_res)){
										$exp_res = mysql_query("select sum(amount) as amount from expense where account_id='".$level6['id']."' and date <='".$date."'");
										$exp = mysql_fetch_array($exp_res);
										$sub2_total += $exp['amount'];
									}
									
								}else{
									$sub2_total = $exp['amount'];
								}
								$sub1_total += $sub2_total;
							}
							
						}else{
							$sub1_total = ($exp['amount'] != NULL) ? $exp['amount'] : 0;
						}
						
					}
					$grand_total += $sub1_total;
				}
			}
			$color=($i%2 == 0) ? "lightgrey" : "white";
			if($level3['account_no'] == '561'){
				/*if($basis == 'Accrual Basis'){
					$pay_res = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '213%' and p.maturity_date <= '".$date."'");
				
					$pay = mysql_fetch_array($pay_res);
					$grand_total = $pay['amount'];
				}elseif($basis == 'Cash Basis'){ 
					$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id join accounts a on p.account_id=a.id where paid.date <= '".$date."' and a.account_no like '213%'");
					$paid = mysql_fetch_array($paid_res);
					$grand_total =  $paid['amount'];
				} 
				*/
				
				//ACCOUNTS PAYABLE
				$pay_res = mysql_query("select sum(amount) as amount from payable where bank_account =0 and date <= '".$date."'");
				$pay = mysql_fetch_array($pay_res);
				$pay_amt = ($pay['amount'] == NULL) ? 0 : $pay['amount'];
				//$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid left join payable p on paid.payable_id=p.id where p.bank_account=0 and paid.date <= '".$date."'");
				$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id join accounts a on p.account_id=a.id where paid.date <= '".$date."' and a.account_no like '213%'");
				$paid = mysql_fetch_array($paid_res);
				$paid_amt = ($paid['amount'] == NULL) ?  0 : $paid['amount'];

				//INTEREST PAYABLE 
				/*
				//INTEREST ALREADY AWARDED ON SAVINGS IS NOT ACCRUED BUT ACTUALLY PAID OUT EXPENSE
				$int_res = mysql_query("select sum(amount) as amount from save_interest where date<= '".$date."'");
				$int = mysql_fetch_array($int_res);
				$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
				$grand_total = $pay_amt +  $int_amt;  // - $paid_amt;
				*/
				$grand_total = $pay_amt;  // +  $int_amt;  // - $paid_amt;
			}
			$content .= "<tr bgcolor=$color><td>".$level3['account_no']."</td><td>".$level3['name']."</td><td>".number_format($grand_total, 2)."</td><td>--</td></tr>";
			$i++;
			$level2_total += $grand_total;
		}
		$expense += $level2_total;
	}
	$debit = $assets + $expense;
	$credit = $income + $liabilities + $capital;
	$content .= "<tr bgcolor=LIGHTGREY><td align=center colspan=2><font size=5pt><b>TOTAL</b></font></td><td><font size=5pt><b>".number_format($debit, 2)."</b></font></td><td><font size=5pt><b>".number_format($credit, 2)."</b></font></td></b></font></tr>";
	$i++;
	$content .= "</table>";
	$resp->assign("display_div", "innerHTML", $content);	
	return $resp;
}



///THE BALANCE SHEET
function balance_sheet($date, $basis,$branch_id){
list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	//$branch_ =($branch_id=='all'||$branch_id=='')? NULL:" and branch_id=".$branch_id;
	if($date == ''){
		$year = date('Y');
		$month = date('m');
		$mday = date('d');
	}
	$resp->assign("status", "innerHTML", "");
	$date = sprintf("%d-%02d-%02d 23:59:59", $year, $month, $mday);
	//$from_date = daysToDate($calc->dateToDays($mday, $month, $year)-365, '%Y-%m-%d');
	$branch_res = mysql_query("select * from branch");
	$branch = mysql_fetch_array($branch_res);
	
	
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR THE BALANCE SHEET</h3>
                                <p class="text-default nm"></p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Date:</label>
                                           <input type="text" class="form-control" id="date" name="date" placeholder="date" />
	
	        
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Basis:</label>
                                          <select id="basis" class="form-control"><option value="Cash Basis">Cash Basis<option value="Accrual Basis">Accrual Basis</select>        
                                        </div>
                                        
                                    </div>
                                </div>  
                                
                       <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Branch:</label>
                                           '.branch_rep($branch_id).'        
                                        </div>
                                                                            
                                    </div>
                                </div>';                                 
                                   	
		 $content .= "<div class='panel-footer'>                              
                               
                                <button type='button' class='btn  btn-primary' onclick=\"xajax_balance_sheet(getElementById('date').value,getElementById('basis').value,getElementById('branch_id').value);\">Show Report</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","date");
                    	
	$content .= "<a href='export/balance_sheet?year=".$year."&month=".$month."&mday=".$mday."&branch_id=".$branch_id."&basis=".$basis."' target=blank()><b>Printable Version</b></a> | <a href='export/balance_sheet?year=".$year."&month=".$month."&mday=".$mday."&branch_id=".$branch_id."&basis=".$basis."&format=excel' target=blank()><b>Export Excel</b></a>";
	
	$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">'.$branch['branch_name'].'</h3>
                                <p>BALANCE SHEET AS AT '.$date.'</p>
                                <p>'.$basis.'</p>
                            </div>';
 		$content .= "<table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>
		<tr bgcolor=lightgrey><td colspan=3 align=center><font size=5pt><b>ASSETS</b></font></td></tr>";
		$level2_res= mysql_query("select * from accounts where account_no >10 and account_no <20 ".$branch_." order by account_no");
		$assets = 0;
		$i=0;
		while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
			$color = ($i%2 == 0) ? "white" : "lightgrey";
			$content .= "<tr bgcolor=$color><td colspan=2><font size=4pt><b>".$level2['name']."</b></font></td><td></td></tr>";
			$i++;
			$level3_res = mysql_query("select * from accounts where account_no >=111 and account_no <= 199 and account_no like '".$level2['account_no']."%' and account_no not in (124, 112) order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td colspan=2 align=left><b>".$level3['name']."</b></td><td></td></tr>";
				$i++;
				$grand_total = 0;
				if($level3['account_no'] == '111'){  //LOANS
					$grand_loss = 0;
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1111 and account_no <= 1119");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$loss_total=0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 111101 and account_no <= 111999");
						if( mysql_numrows($level5_res) > 0){
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td></td><td><b>".$level4['name']."</b></td><td></td></tr>";
							$i++;
						}
						while($level5 = mysql_fetch_array($level5_res)){
							$loan1_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level5['id']."' and d.date <= '".$date."'");
							$loan1 = mysql_fetch_array($loan1_res);
							
							if($loan1['amount'] == NULL){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 11110101 and account_no <= 11199999");
								if(mysql_numrows($level6_res) >0){
									
									$sub2_total = 0;
									$loss2_total = 0;
									while($level6 = mysql_fetch_array($level6_res)){
										$loan2_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level6['id']."' and d.date <= '".$date."'");
										$loan2 = mysql_fetch_array($loan2_res);
										//$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level6['id']."' and d.date <= '".$date."' and pay.date <='".$date."'");
										$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level6['id']."' and pay.date <='".$date."'");
										$pay = mysql_fetch_array($pay_res);
										$loan2_amt = ($loan2['amount'] == NULL) ? 0 : $loan2['amount'];
										
										$loss_res = mysql_query("select sum(w.amount) as amount from written_off w join disbursed d on w.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where w.date <= '".$date."' and p.account_id='".$level6['id']."'");
										$loss = mysql_fetch_array($loss_res);
										$loss2_total = $loss2_total + $loss['amount'];
										$sub2_total = $sub2_total + $loan2_amt - $pay['amount'] - $loss['amount'];
									}
								} 
							}else{
								//$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level5['id']."' and d.date <= '".$date."' and pay.date <='".$date."'");
								$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level5['id']."'  and pay.date <='".$date."'");

								$pay = mysql_fetch_array($pay_res);
								$loss_res = mysql_query("select sum(w.amount) as amount from written_off w join disbursed d on w.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where w.date <= '".$date."' and p.account_id='".$level5['id']."'");
								$loss = mysql_fetch_array($loss_res);
								$loss2_total = $loss['amount'];
								$sub2_total = $loan1['amount'] - $pay['amount'] - $loss['amount'];
							}
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td></td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
							$i++;
							
							$sub1_total = $sub1_total + $sub2_total;
							$loss_total = $loss_total + $loss2_total;
							$sub2_total = 0;
						}
						$color=($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						 $i++;
						/* $written_res = mysql_query("select * from accounts where account_no like '112%' and name like '%".$level4['name']."'");
						$written = mysql_fetch_array($written_res);
						$color=($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color ><td><b>".$written['account_no']."</b></td><td><b>".$written['name']."</b></td><td><b>".$loss1_total."</b></td></tr>";
						$i++;
						*/

						$grand_total = $grand_total + $sub1_total;
						//$grand_loss = $grand_loss + $loss_total;
					}
					//LOAN LOSS ALLOWANCES
					$grand_loss=0;
					$prov_res = mysql_query("select * from provissions order by percent asc");
					
					while($prov = mysql_fetch_array($prov_res)){
						preg_match("/(\d*)[_](\d*)$/", $prov['range'], $arg);
						$interval = ($arg[1] <>180) ? ($arg[2] - $arg[1]) : 180;
						if($arg[2]==''){
							$arg[1] =180;
							$arg[2]=660;     //UP TO 22 MONTHS OF ARREARS, THE ASSUMPTION IS THAT BEYOND THAT, THE LOAN WILL HAVE BEEN WRITTEN OFF
						}
						
						$over_180 =0;
						$sched_res = mysql_query("select sum(princ_amt) as amount, loan_id as loan_id from schedule where date <= DATE_SUB('".$date."', INTERVAL ".$arg[1]." DAY) and date >= DATE_SUB('".$date."', INTERVAL ".$arg[2]." DAY) and loan_id not in (select loan_id from written_off where date <= DATE_SUB('".$date."', INTERVAL ".$arg[1]." DAY)) group by loan_id");
					
					
						while($sched = mysql_fetch_array($sched_res)){
							$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
							$all = mysql_fetch_array(mysql_query("select sum(princ_amt) as amount from schedule where date <= DATE_SUB('".$date."', INTERVAL ".$arg[2]." DAY)  and loan_id='".$sched['loan_id']."'"));
							$paid_res = mysql_query("select sum(princ_amt) as amount from payment where date <='".$date."' and loan_id='".$sched['loan_id']."'");
							$paid = mysql_fetch_array($paid_res);
							$paid_amt = ($paid['amount'] == NULL) ? 0 : $paid['amount'];
							$arrears = $all['amount'] - $paid_amt;
							if($arrears < 0){
								$arrears =0;
							}
							
							$arrears = ($arrears > $sched_amt) ? $sched_amt : $arrears;
							$over_180  += $arrears;

						}	
						$loss180_over = floor($over_180 * $prov['percent']/100);
						$grand_loss += $loss180_over;
					}
					//MANUAL LOAN LOSS PROVISSION
					$static = mysql_fetch_array(mysql_query("select sum(amount) as amount from otherloan_loss where date <= '".$date."'"));
					$static_amt = ($static['amount'] == NULL) ? 0 : $static['amount'];
					$grand_loss = $grand_loss + $static_amt;
					$grand_net = $grand_total - $grand_loss;  //SUBTRACT LOAN LOSS PROVISIONS


					//$grand_loss = $loss1_30 + $loss31_60 + $loss61_90 + $loss91_120 + $loss121_180 + $loss180_over;
				}elseif($level3['account_no'] >= 113 && $level3['account_no'] <= 119){   //INVESTMENTS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1131 and account_no <= 1199");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level4['id']."' and date <='".$date."'");
						$invest = mysql_fetch_array($invest_res);
						if($invest['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 113101 and account_no <= 119999");
							if( mysql_numrows($level5_res) > 0){
								$color=($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td></td></tr>";
								$i++;
							}
							while($level5 = mysql_fetch_array($level5_res)){
								$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level5['id']."' and date<='".$date."'");
								$invest = mysql_fetch_array($invest_res);
								$sub2_total=0;
								if($invest['amount'] ==NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 11310101 and account_no <= 11399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level6['id']."' and date <='".$date."'");		
										$invest = mysql_fetch_array($invest_res);
										//$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level6['id']."' and date <='".$date."' order by date desc limit 1"));
										$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level6['id']."' and date <='".$date."'");
										$sold = mysql_fetch_array($sold_res);
										$sold_amt = ($sold['quantity'] == NULL) ? 0 : $sold['amount'];
										$sub2_total = $sub2_total + $invest['amount'] - $sold_amt;
									}
								}else{
									//$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level5['id']."' and date <='".$date."' order by date desc limit 1"));
									$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level5['id']."' and date <='".$date."'");
									$sold = mysql_fetch_array($sold_res);
									$sold_amt = ($sold['quantity'] == NULL) ? 0 : $sold['amount'];
									$sub2_total = $invest['amount'] - $sold_amt;
									//$sub2_total = $invest['amount'];
								}
								$sub1_total = $sub1_total + $sub2_total;
							}
						}else{
							//$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level4['id']."' and date <='".$date."' order by date desc limit 1"));
							$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level4['id']."' and date <='".$date."'");
							$sold = mysql_fetch_array($sold_res);
							$sold_amt = ($sold['amount'] == NULL) ? 0 : $sold['amount'];
							$sub1_total = $invest['amount'] - $sold_amt;
						}
						$color=($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color ><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						$i++;
						$grand_total = $grand_total + $sub1_total;
					}
				}elseif($level3['account_no'] >= 125){   //INVESTMENTS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1251 and account_no <= 1259");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 125101 and account_no <= 125999");
						if( mysql_numrows($level5_res) > 0){
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td></td></tr>";
							$i++;
						}
						while($level5 = mysql_fetch_array($level5_res)){
							$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level5['id']."' and date <= '".$date."'");
							$invest = mysql_fetch_array($invest_res);
							$sub2_total=0;
							if($invest['amount'] == NULL){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 12510101 and account_no <= 11399999");
								while($level6 = mysql_fetch_array($level6_res)){
									$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level6['id']."' and date <= '".$date."' and id not in (select investment_id from sold_invest where date<='".$date."')");		
									$invest = mysql_fetch_array($invest_res);

									$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level5['id']."' and date <='".$date."' order by date desc limit 1"));
									$sold_res = mysql_query("select sum(quantity) as quantity from sold_invest where account_id='".$level5['id']."' and date <='".$date."'");
									$sold = mysql_fetch_array($sold_res);
									$sold_amt = ($sold['quantity'] == NULL) ? 0 : ($sold['quantity'] * $unit['amount']);
									$sub2_total =$sub2_total + $invest['amount'] - $sold_amt;		

									$sub2_total = $sub2_total + $invest['amount'];
								}
							}else{
								$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level5['id']."' and date <='".$date."' order by date desc limit 1"));
								$sold_res = mysql_query("select sum(quantity) as quantity from sold_invest where account_id='".$level5['id']."' and date <='".$date."'");
								$sold = mysql_fetch_array($sold_res);
								$sold_amt = ($sold['quantity'] == NULL) ? 0 : ($sold['quantity'] * $unit['amount']);
								$sub2_total = $invest['amount'] - $sold_amt;		
							}
							$sub1_total = $sub1_total + $sub2_total;
						}
						$color=($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color ><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						$i++;
						$grand_total = $grand_total + $sub1_total;
					}
				}elseif($level3['account_no'] == 121){
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1211 and account_no <= 1219");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 121101 and account_no <= 121999");
						if( mysql_numrows($level5_res) > 0){
							$color= ($i%2 == 0) ? lightgrey : "white";
							$content .= "<tr bgcolor=$color><td><b></b></td><td><b>".$level4['name']."</b></td><td></td></tr>";
							$i++;
						}
						while($level5 = mysql_fetch_array($level5_res)){
							$bank1_res = mysql_query("select * from bank_account where account_id='".$level5['id']."'");
							$sub2_total = 0;
							if(mysql_numrows($bank1_res) == 0){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 12110101 and account_no <= 12199999");
								while($level6 = mysql_fetch_array($level6_res)){
									$bank2_res = mysql_query("select * from bank_account where account_id='".$level6['id']."'");
									$bank2 = mysql_fetch_array($bank2_res);
									//DEPOSITS
									$dep_res = mysql_query("select sum(amount) as amount from deposit where bank_account='".$bank2['id']."' and date <='".$date."'");
									$dep = mysql_fetch_array($dep_res);
									//WITHDRAWALS
									$with_res = mysql_query("select sum(amount) as amount from withdrawal where bank_account='".$bank2['id']."' and date <='".$date."'");
									$with = mysql_fetch_array($with_res);
									//OTHER INCOME
									$other_res = mysql_query("select sum(amount) as amount from other_income where bank_account='".$bank2['id']."' and date <='".$date."'");
									$other = mysql_fetch_array($other_res);
									//LOANS PAYABLE
									$loans_payable = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '212%' and p.bank_account='".$bank2['id']."' and p.date <= '".$date."'");
									$loans_payable = mysql_fetch_array($loans_payable);
									$loans_payable = ($loans_payable['amount'] != NULL) ? $loans_payable['amount'] : 0;
									//EXPENSES
									$expense_res = mysql_query("select sum(amount) as amount from expense where bank_account='".$bank2['id']."' and date <='".$date."'");
									$expense = mysql_fetch_array($expense_res);
									//PAYABLE PAID
									$payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where bank_account='".$bank2['id']."' and date <='".$date."'");	
									$payable_paid = mysql_fetch_array($payable_paid_res);
									//RECEIVABLE COLLECTED
									$collected_res = mysql_query("select sum(amount) as amount from collected where bank_account='".$bank2['id']."' and date <='".$date."'");
									$collected = mysql_fetch_array($collected_res);
									//DISBURSED LOANS
									$disb_res = mysql_query("select sum(amount) as amount from disbursed where bank_account='".$bank2['id']."' and date <= '".$date."'");
									$disb = mysql_fetch_array($disb_res);
									//PAYMENTS
									$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id  where p.date <= '".$date."' and p.bank_account='".$bank2['id']."'");
									$pay = mysql_fetch_array($pay_res);
									//PENALTIES
									$pen_res = mysql_query("select sum(p.amount) as amount from penalty p join disbursed d on p.loan_id=d.id where p.bank_account='".$bank2['id']."' and p.status='paid' and p.date <='".$date."'");
									$pen = mysql_fetch_array($pen_res);
								
									//SHARES
									$shares_res = mysql_query("select sum(value) as amount from shares where date <='".$date."' and bank_account='".$bank2['id']."'");
									$shares = mysql_fetch_array($shares_res); 
									//RECOVERED
									$rec_res = mysql_query("select sum(r.amount) as amount from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.bank_account='".$bank2['id']."' and r.date <= '".$date."'");
									$rec = mysql_fetch_array($rec_res);			
									//INVESTMENTS 
									$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where date <= '".$date."' and bank_account='".$bank2['id']."'");
									$invest = mysql_fetch_array($invest_res);
									$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
									//DIVIDENDS PAID
									$div_res = mysql_query("select sum(total_amount) as total_amount from share_dividends where date<='".$date."' and bank_account='".$bank1['id']."'");
									$div = mysql_fetch_array($div_res);
									$div_amt = ($div['amount'] != NULL) ? $div['amount'] : 0;
									
									//SOLD INVESTMENTS
									$soldinvest_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where bank_account ='".$bank2['id']."' and date <= '".$date."'");
									$soldinvest = mysql_fetch_array($soldinvest_res);

									//FIXED ASSETS 
									$fixed_res = mysql_query("select sum(initial_value) as amount from fixed_asset where bank_account='".$bank2['id']."' and date <='".$date."'");
									$fixed = mysql_fetch_array($fixed_res);
									$soldasset_res = mysql_query("select sum(amount) as amount from sold_asset where bank_account ='".$bank2['id']."' and date <= '".$date."'");
									$soldasset = mysql_fetch_array($soldasset_res);
									
									//CASH IMPORTED
									$import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id='".$bank2['id']."' and date <='".$date."'");
									$import = mysql_fetch_array($import_res);
									$import_amt = ($import['amount'] ==NULL) ? 0 : $import['amount'];
								
									//CASH EXPORTED
									$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id='".$bank2['id']."' and date <='".$date."'");
									$export = mysql_fetch_array($export_res);
									$export_amt = ($export['amount'] ==NULL) ? 0 : $export['amount'];

									//CAPITAL FUNDS
									$fund_res = mysql_query("select sum(amount) as amount from other_funds where bank_account='".$bank2['id']."' and date <='".$date."'");
									$fund = mysql_fetch_array($fund_res);
									$fund_amt = $fund['amount'];


									$sub2_total = $sub2_total + $collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt + $fund_amt;		
									
								}
							}else{
								$bank1 = mysql_fetch_array($bank1_res);
								//DEPOSITS
								$dep_res = mysql_query("select sum(amount) as amount from deposit where bank_account='".$bank1['id']."' and date <='".$date."'");
								$dep = mysql_fetch_array($dep_res);
								$dep_amt = ($dep['amount'] == NULL) ? 0 : $dep['amount'];
								//WITHDRAWALS
								$with_res = mysql_query("select sum(amount) as amount from withdrawal where bank_account='".$bank1['id']."' and date <='".$date."'");
								$with = mysql_fetch_array($with_res);
								$with_amt = ($with['amount'] == NULL) ? 0 : $with['amount'];
								//OTHER INCOME
								$other_res = mysql_query("select sum(amount) as amount from other_income where bank_account='".$bank1['id']."' and date <='".$date."'");
								$other = mysql_fetch_array($other_res);
								$other_amt = ($other['amount'] == NULL) ? 0 : $other['amount'];
								//EXPENSES
								$expense_res = mysql_query("select sum(amount) as amount from expense where bank_account='".$bank1['id']."' and date <='".$date."'");
								$expense = mysql_fetch_array($expense_res);
								$expense_amt = ($expense['amount'] == NULL) ? 0 : $expense['amount'];
								//LOANS PAYABLE
								$loans_payable = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '212%' and p.bank_account='".$bank1['id']."' and p.date <= '".$date."'");
								$loans_payable = mysql_fetch_array($loans_payable);
								$loans_payable = ($loans_payable['amount'] != NULL) ? $loans_payable['amount'] : 0;
								//PAYABLE PAID
								$payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where bank_account='".$bank1['id']."' and date <='".$date."'");
								$payable_paid = mysql_fetch_array($payable_paid_res);
								$payable_paid_amt = ($payable_paid['amount'] == NULL) ? 0 : $payable_paid['amount'];
								//RECEIVALE COLLECTED
								$collected_res = mysql_query("select sum(amount) as amount from collected where bank_account='".$bank1['id']."' and date <='".$date."'");
								$collected = mysql_fetch_array($collected_res);
								$collected_amt = ($collected['amount'] == NULL) ? 0 : $collected['amount'];
								//DISBURSED LOANS
								$disb_res = mysql_query("select sum(amount) as amount from disbursed where bank_account='".$bank1['id']."' and date <= '".$date."'");
								$disb = mysql_fetch_array($disb_res);
								$disb_amt = ($disb['amount'] == NULL) ? 0 : $disb['amount'];
								//PAYMENTS
								$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id  where p.date <= '".$date."' and p.bank_account='".$bank1['id']."'");
								$pay = mysql_fetch_array($pay_res);
								$pay_amt = ($pay['amount'] == NULL) ? 0 : $pay['amount'];
								//PENALTIES
								$pen_res = mysql_query("select sum(p.amount) as amount from penalty p join disbursed d on p.loan_id=d.id where p.bank_account='".$bank1['id']."' and p.status='paid' and p.date <= '".$date."'");
								$pen = mysql_fetch_array($pen_res);
								$pen_amt = ($pen['amount']==NULL) ? 0 : $pen['amount'];
								
								//SHARES
								$shares_res = mysql_query("select sum(value) as amount from shares where date <='".$date."' and bank_account='".$bank1['id']."'");
								$shares = mysql_fetch_array($shares_res); 
								$shares_amt = ($shares['amount'] == NULL) ? 0 : $shares['amount'];
								//RECOVERED
								$rec_res = mysql_query("select sum(r.amount) as amount from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.bank_account='".$bank1['id']."' and r.date <= '".$date."'");
								$rec = mysql_fetch_array($rec_res);	
								$rec_amt = ($rec['amount'] == NULL) ? 0 : $rec['amount']; 
								//INVESTMENTS 
								$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where date <= '".$date."' and bank_account='".$bank1['id']."'");
								$invest = mysql_fetch_array($invest_res);
								$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
								//DIVIDENDS PAID
								$div_res = mysql_query("select sum(total_amount) as total_amount from share_dividends where date<='".$date."' and bank_account='".$bank1['id']."'");
								$div = mysql_fetch_array($div_res);
								$div_amt = ($div['amount'] != NULL) ? $div['amount'] : 0;

								
								$soldinvest_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where bank_account ='".$bank1['id']."' and date <= '".$date."'");
								$soldinvest = mysql_fetch_array($soldinvest_res);

								//FIXED ASSETS 
								$fixed_res = mysql_query("select sum(initial_value) as amount from fixed_asset where bank_account='".$bank1['id']."' and date <='".$date."'");
								$fixed = mysql_fetch_array($fixed_res);
								$soldasset_res = mysql_query("select sum(amount) as amount from sold_asset where bank_account ='".$bank1['id']."' and date <= '".$date."'");
								$soldasset = mysql_fetch_array($soldasset_res);
									
								//CASH IMPORTED
								$import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id=".$bank1['id']." and date <='".$date."'");
								$import = mysql_fetch_array($import_res);
								$import_amt = ($import['amount'] ==NULL) ? 0 : $import['amount'];

								//CASH EXPORTED
								$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id=".$bank1['id']." and date <='".$date."'");
								$export = mysql_fetch_array($export_res);
								$export_amt = ($export['amount'] ==NULL) ? 0 : $export['amount'];

								//CAPITAL FUNDS
								$fund_res = mysql_query("select sum(amount) as amount from other_funds where bank_account='".$bank1['id']."' and date <='".$date."'");
								$fund = mysql_fetch_array($fund_res);
								$fund_amt = $fund['amount'];


								$sub2_total = $collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt + $fund_amt;
							}
							$color = ($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td></td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
							$sub1_total = $sub1_total + $sub2_total;
							$i++;
						}
						$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td></td><td><b>Total ".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						$i++;
						$grand_total += $sub1_total;
					}
				}elseif($level3['account_no']=='122'){
					if($basis == 'Cash Basis')
						continue;
					$rec_res = mysql_query("select sum(amount) as amount from receivable where maturity_date <='".$date."'");
					$rec = mysql_fetch_array($rec_res);
					if($rec['amount'] != NULL){
						$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where c.date <= '".$date."'");
						$col = mysql_fetch_array($col_res);
						$grand_total =  $rec['amount'] - $col['amount'];
					}
				}elseif($level3['account_no'] == '123'){  //FIXED ASSETS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['[account_no']."%' and account_no >= 1231 and account_no <= 1239");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$depp1_total = 0;
						//$fixed_res = mysql_query("select f.id as id, sum(f.initial_value) as amount from fixed_asset f left join sold_asset s on s.asset_id=f.id where f.account_id='".$level4['id']."' and f.date <='".$date."' and (s.date >'".$date."' or s.date is null) group by f.id");
						$fixed_res = mysql_query("select  sum(f.initial_value) as amount from fixed_asset f where f.account_id='".$level4['id']."' and f.id not in (select asset_id from sold_asset where date <= '".$date."') and f.date <='".$date."'");
						$fixed = mysql_fetch_array($fixed_res);
						if($fixed['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 123101 and account_no <= 123999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$depp2_total = 0;
								//$fixed_res = mysql_query("select f.id as id, sum(f.initial_value) as amount from fixed_asset f left join sold_asset s on s.asset_id=f.id where f.account_id='".$level5['id']."' and f.date <='".$date."' and (s.date >'".$date."' or s.date is null) group by f.id");
								$fixed_res = mysql_query("select  sum(f.initial_value) as amount from fixed_asset f where f.account_id='".$level5['id']."' and f.id not in (select asset_id from sold_asset where date <= '".$date."') and f.date <='".$date."'");
								$fixed = mysql_fetch_array($fixed_res);
								if($fixed['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >=12310101 and account_no <= 12399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$fixed_res = mysql_query("select  sum(f.initial_value) as amount from fixed_asset f where f.account_id='".$level6['id']."' and f.id not in (select asset_id from sold_asset where date <= '".$date."') and f.date <='".$date."'");
										$fixed = mysql_fetch_array($fixed_res);
										$sub2_total = $sub2_total + $fixed['amount'];
										$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from  fixed_asset where account_id='".$level6['id']."' and date <= '".$date."') and date <='".$date."'");
										$depp = mysql_fetch_array($depp_res);
										$depp2_total = $depp2_total + $depp['amount'];
									}
								}else{
						
									$sub2_total = $fixed['amount'];
									$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from  fixed_asset where account_id='".$level5['id']."' and date <= '".$date."') and date <='".$date."'");
									$depp = mysql_fetch_array($depp_res);
									$depp2_total = $depp2_total + $depp['amount'];
								}
						
								$color=($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td>".$level5['account_no']."</td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
								$i++;
								$acc_res = mysql_query("select * from accounts where name='Accumulated Depreciation - ".$level5['name']."'");
								$acc = mysql_fetch_array($acc_res);
								$color=($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td>".$acc['account_no']."</td><td>".$acc['name']."</td><td>".number_format($depp2_total, 2)."</td></tr>";
								$i++;
								$net_total = $sub2_total - $depp2_total;	
								$color=($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td></td><td><b>Net ".$acc['name']."</b></td><td>".number_format($net_total, 2)."</td></tr>";
								$sub1_total += $sub2_total;
								$depp1_total += $depp2_total;
								$i++;
							}
						}else{
							$sub1_total = $fixed['amount'];
							$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from  fixed_asset where account_id='".$level4['id']."' and date <= '".$date."') and date <='".$date."'");
							$depp = mysql_fetch_array($depp_res);
							$depp1_total = $depp1_total + $depp['amount'];
						}
						$color=($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color ><td></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";	
						$i++;
						$net_total = $sub1_total - $depp1_total;
						if($level4['name'] != 'Land'){
							$acc_res = mysql_query("select * from accounts where name='Accumulated Depreciation - ".$level4['name']."'");
							$acc = mysql_fetch_array($acc_res);
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color ><td></td><td><b>".$acc['name']."</b></td><td><b>".number_format($depp1_total, 2)."</b></td></tr>";
							$i++;
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td></td><td><b>Net ".$level4['name']."</b></td><td><b>".number_format($net_total, 2)."</b></td></tr>";
							$i++;
						}
						$grand_total = $grand_total + $net_total;
					}
				}
				$level2_total += $grand_total;
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td colspan=2 align=left><b> TOTAL ".$level3['name']."</b></td><td><b>".number_format($grand_total, 2)."</b></td></tr>";	
				$i++;
				if($level3['account_no'] == 111){
					$loss_res = mysql_query("select * from accounts where account_no='112'");
					$loss = mysql_fetch_array($loss_res);
					$color = ($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td><b>(-) ".$loss['account_no']."</b></td><td><b> ".$loss['name']."</b></td><td><b>".number_format($grand_loss, 2)."</b></td></tr>";
					$i++;
					$color = ($i%2 == 0) ? "lightgrey" : "white";
					$content .="<tr bgcolor=$color><td><b></b></td><td><b> TOTAL NET LOAN PORTFOLIO</b></td><td><b>".number_format(($grand_total - $grand_loss), 2)."</b></td></tr>";
					$i++;
					$level2_total -= $grand_loss;
					//$grand_loss = 0;
				}
			}			
			$color = ($i%2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color><td colspan=2><font size=4pt><b>TOTAL  ".$level2['name']."</b></font></td><td><font size=4pt><b>".number_format($level2_total, 2)."</b></font></td></tr>";
			$i++;
			$assets += $level2_total;
		}
		$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr bgcolor=$color><td colspan=2><font size=5pt><b>TOTAL ASSETS</b></font></td><td><font size=5pt><b>".number_format($assets, 2)."</b></font></td></tr>";
		$i++;
		
//LIABILITIES
		$capital = 0;
		$liabilities = 0;
		$content .= "<tr bgcolor=#cdcdcd><td colspan=3><font size=5pt><b>LIABILITIES</b></font></td></tr>";
		$level2_res = mysql_query("select * from accounts where account_no >=21 and account_no <= 39 order by account_no");
		while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
			if($level2['account_no'] ==31){
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td colspan=2><font size=5pt><b>TOTAL LIABILITIES</b></font></td><td><font size=5pt><b>".number_format($liabilities, 2)."</b></font></td></tr>";
				$i++;
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .="<tr bgcolor=$color><td colspan=3><font size=5pt><b>CAPITAL</b></font></td></tr>";
				$i++;
			}
			$color = ($i%2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color><td colspan=2><font size=4pt><b>".$level2['name']."</b></font></td><td></td></tr>";
			$level3_res = mysql_query("select * from accounts where account_no >=211 and account_no <= 399 and account_no like '".$level2['account_no']."%' order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td colspan=2 align=left><b>".$level3['name']."</b></td><td></td></tr>";
				$i++;
				$grand_total = 0;
				if($level3['account_no'] == '211'){  //SAVINGS & DEPOSITS
					
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=2111 and account_no <= 2119");
					while($level4 = mysql_fetch_array($level4_res)){
						if($level4['name'] == 'Compulsory Savings')
							continue;
						$sub1_total = 0;
						$prod_res = mysql_query("select * from savings_product where account_id='".$level4['id']."'");
						if(mysql_numrows($prod_res) > 0){
							$prod = mysql_fetch_array($prod_res);
							$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and d.date<= '".$date."'");
							$dep = mysql_fetch_array($dep_res);
							$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.id='".$prod['id']."' and p.type='free' and w.date<= '".$date."'");
							$with = mysql_fetch_array($with_res);
							$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and i.date<= '".$date."'");
							$int = mysql_fetch_array($int_res);
							$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
							//OTHER DEDUCTIONS
							$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') and o.date <= '".$date."'"));
							$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;
							//MONTHLY ACCOUNT CHARGES
							$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts mem on c.memaccount_id=mem.id join savings_product p on mem.saveproduct_id=p.id where c.date <='".$date."' and p.id='".$prod['id']."' and p.type='free'");
							$charge = mysql_fetch_array($charge_res);
							//OFFSETTING FROM SAVINGS
							$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' and p.date<= '".$date."'");
							$pay = mysql_fetch_array($pay_res);
							$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;
							
							//SHARES OFFSET FROM SAVINGS
							$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') and s.date <= '".$date."'"));
							$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;

							$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
							$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
							$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
							
							$sub1_total = $dep_amt + $int_amt - $with_amt - $income_amt - $charge_amt- $pay_amt - $share_amt;  
						}else{
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 211101 and account_no <= 211999");
							while($level5 = mysql_fetch_array($level5_res)){
								if($level5['name'] == 'Compulsory Shares')
									continue;
								$sub2_total =0;
								$prod_res = mysql_query("select * from savings_product where account_id='".$level5['id']."'");
								if(mysql_numrows($prod_res) > 0){
									$prod = mysql_fetch_array($prod_res);
									//$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and d.date<= '".$date."'");
									$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and d.date<= '".$date."'");
									$dep = mysql_fetch_array($dep_res);
									$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.id='".$prod['id']."' and p.type='free' and w.date<= '".$date."'");
									$with = mysql_fetch_array($with_res);

									$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and i.date<= '".$date."'");
									$int = mysql_fetch_array($int_res);
									$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
									//OTHER DEDUCTIONS
									$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') and o.date <= '".$date."'"));
									$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;
									//MONTHLY ACCOUNT CHARGES
									$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts mem on c.memaccount_id=mem.id join savings_product p on mem.saveproduct_id=p.id where c.date <='".$date."' and p.id='".$prod['id']."' and p.type='free'");
									$charge = mysql_fetch_array($charge_res);
									//OFFSETTING FROM SAVINGS
									$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' and p.date<= '".$date."'");
									$pay = mysql_fetch_array($pay_res);
									$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;
									$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;

									//SHARES OFFSET FROM SAVINGS
									$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') and s.date <= '".$date."'"));
									$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;

									
									$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
									$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
									$sub2_total = $dep_amt + $int_amt - $with_amt - $charge_amt - $income_amt - $pay_amt - $share_amt; // + $int['amount'];
									$sub1_total = $sub1_total + $sub2_total;
								}else{
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 21110101 and account_no <= 21199999");
									while($level6 = mysql_fetch_array($level6_res)){
										$prod_res = mysql_query("select * from savings_product where account_id='".$level6['id']."'");
										if(mysql_numrows($prod_res) > 0){
											$prod = mysql_fetch_array($prod_res);
											$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and d.date<= '".$date."'");
											$dep = mysql_fetch_array($dep_res);
											$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.id='".$prod['id']."' and p.type='free' and w.date<= '".$date."'");
											$with = mysql_fetch_array($with_res);
											$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and i.date<= '".$date."'");
											$int = mysql_fetch_array($int_res);
											$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
											//OTHER DEDUCTIONS
											$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') and o.date <= '".$date."'"));
											$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;
											//MONTHLY ACCOUNT CHARGES
											$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts mem on c.memaccount_id=mem.id join savings_product p on mem.saveproduct_id=p.id where c.date <='".$date."' and p.id='".$prod['id']."' and p.type='free'");
											$charge = mysql_fetch_array($charge_res);
											$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
											//OFFSETTING FROM SAVINGS
											$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' and p.date<= '".$date."'");
											$pay = mysql_fetch_array($pay_res);
											$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;

											//SHARES OFFSET FROM SAVINGS
											$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') and s.date <= '".$date."'"));
											$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;

										//	$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and i.date<= '".$date."'");
										//	$int = mysql_fetch_array($int_res);
											$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
											$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
											$sub2_total = $sub2_total + $dep_amt + $int_amt - $with_amt - $charge_amt - $income_amt - $share_amt;  // + $int['amount'];
										}
									}
									$sub1_total = $sub1_total + $sub2_total;
								}
								$color = ($i%2 == 0) ? "lightgrey" : "white";
								$content .="<tr bgcolor=$color><td></td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
								$i++;
							}
						}
						$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .="<tr bgcolor=$color><td><b></b></td><td><b>Total ".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						$i++;
						$grand_total = $grand_total + $sub1_total;
				}
				$level2_total += $grand_total;
				$liabilities += $grand_total;
			}elseif($level3['account_no'] >='212' && $level3['account_no'] <= 299){
				$grand_total=0;
				
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=2121 and account_no <= 2999");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					
					if($level4['name'] == 'Interest Payable on Savings Deposits'){
						/* 
						//INTEREST GIVEN TO MEMBERS IS ALREADY PAID OUT AND IS NOT INTEREST PAYABLE
						$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.name like '%Savings%' and i.date <='".$date."'");
						
						$int = mysql_fetch_array($int_res);
						
						$sub1_total = $int['amount'];
						*/
						;
					}elseif($level4['name'] == 'Interest Payable on Time Deposits'){
						/* 
						//INTEREST GIVEN TO MEMBERS IS ALREADY PAID OUT AND IS NOT INTEREST PAYABLE
						$int_res = mysql_query("select sum(amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.name like '%Time%' and i.date <='".$date."'");
						$int = mysql_fetch_array($int_res);
						$sub1_total = $int['amount'];
						*/
						;
					}elseif($level4['name'] == 'Dividends Payable'){
						$div_res = mysql_query("select sum(d.amount) as amount from dividends d join share_dividends s on d.share_dividend_id=s.id where s.bank_account=0 and s.date<= '".$date."'");
						$div = mysql_fetch_array($div_res);
						$dividends = intval($div['amount']);
						$sub1_total = $dividends;
					//	$interest_awarded += $sub1_total;
					}else{
						
						//if($basis == 'Cash Basis' && !preg_match("/^212/", $level4['account_no']))
						//	continue;
						
						$pay_res = mysql_query("select sum(amount) as amount from payable where account_id='".$level4['id']."' and date<= '".$date."'");
						$pay = mysql_fetch_array($pay_res);
						
						if($pay['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 212101 and account_no <= 213999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$pay_res = mysql_query("select sum(amount) as amount from payable where account_id='".$level5['id']."' and date <= '".$date."'");
								$pay = mysql_fetch_array($pay_res);
								if($pay['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >=21210101 and account_no <= 21399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$pay_res = mysql_query("select sum(amount) as amount from payable where account_id='".$level6['id']."' and date <= '".$date."'");
										$pay = mysql_fetch_array($pay_res);
										$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id where paid.date <= '".$date."' and p.account_id='".$level4['id']."'");
										$paid = mysql_fetch_array($paid_res);
										//$add = ($basis == 'Cash Basis') ? $paid['amount'] : $pay['amount'] - $paid['amount'];
										//$sub2_total += $add;
										$sub2_total = $sub2_total + $pay['amount'] - $paid['amount'];
									}
								}else{
									$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id where paid.date <= '".$date."' and p.account_id='".$level5['id']."'");
									$paid = mysql_fetch_array($paid_res);
									$add = ($basis == 'Cash Basis') ? $paid['amount'] : $pay['amount'] - $paid['amount'];
									
									$sub2_total = $pay['amount'] - $paid['amount'];
								}
								$color = ($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td></td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
								$i++;
							$sub1_total += $sub2_total;
							}
						}else{
							$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id where paid.date <= '".$date."' and p.account_id='".$level4['id']."'");
							$paid = mysql_fetch_array($paid_res);
							//$add = ($basis == 'Cash Basis') ? $paid['amount'] : $pay['amount'] - //$paid['amount'];
							//$sub2_total =$add;
							$sub1_total = $pay['amount'] - $paid['amount'];
						}
					}
					$grand_total = $grand_total + $sub1_total;
					$level2_total += $sub1_total;
					$liabilities += $sub1_total;
					$color = ($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td><b></b></td><td><b>Total ".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
					$i++;
				}
			}elseif($level3['account_no'] >= '311' && $level3['account_no'] <= '399'){
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 3111 and account_no <= 3999");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['account_no'] == '3111'){
						$share_res = mysql_query("select sum(value) as value from shares where date <='".$date."'");
						$share = mysql_fetch_array($share_res);
						$level42_res = mysql_query("select * from accounts where name like 'Member Shares'");
						$level42 = mysql_fetch_array($level42_res);
						$sub1_total = $share['value'];
						$sub1_total = ($sub1_total > 0) ? $sub1_total : 0;
						$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td><b></b></td><td><b>Total ".$level42['name']."</b></td><td>".number_format($sub1_total, 2)."</td></tr>";
						$i++;
					}elseif($level4['account_no'] == '3312'){
						$sub1_total -= $grand_loss;
						$sub1_total += net_cummulated_income('', $date, $basis);
						$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td><b></b></td><td><b>Total ".$level4['name']."</b></td><td>".number_format($sub1_total, 2)."</td></tr>";
						$i++;
						//$resp->AddAlert(net_cummulated_income($date, $basis));
					}else{
						$invest_res = mysql_query("select sum(amount) as amount from other_funds where account_id='".$level4['id']."' and date <= '".$date."' and id not in (select fund_id from payable where date <= '".$date."' and fund_id<>'0')");
						$invest = mysql_fetch_array($invest_res);
						if($invest['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 321101 and account_no <= 339999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$invest_res = mysql_query("select sum(amount) as amount from other_funds where account_id='".$level5['id']."' and date <= '".$date."'  and id not in (select fund_id from payable where date <= '".$date."' and fund_id<>'0')");
								$invest = mysql_fetch_array($invest_res);
								if($invest['amount'] == NULL){
									$level6_res = mysql_query("select  * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 32110101 and account_no <= 33999999");
									while($level6 = mysql_fetch_array($level6_res)){
										$invest_res = mysql_query("select sum(amount) as amount from other_funds where account_id='".$level6['id']."' and date <= '".$date."'   and id not in (select fund_id from payable where date <= '".$date."' and fund_id<>'0')");
										$invest = mysql_fetch_array($invest_res);
										$sub2_total = $sub2_total + $invest['amount'];
									}
								}else
									$sub2_total = $invest['amount'];
								$color = ($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td></td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
								$i++;
								$sub1_total = $sub1_total + $sub2_total;
							}
						}else{
							$sub1_total = $invest['amount'];
						}
						$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td><b></b></td><td><b>Total ".$level4['name']."</b></td><td>".number_format($sub1_total, 2)."</td></tr>";
						$i++;
					}
					$color = ($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td><b></b></td><td><b>Total ".$level4['name']."</b></td><td>".number_format($sub1_total, 2)."</td></tr>";
					$i++;
					$grand_total += $sub1_total;
					$level2_total += $sub1_total; 
					$capital += $sub1_total;
				}
			}
			$color = ($i%2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color><td colspan=2 align=left><b>TOTAL  ".$level3['name']."</b></td><td><b>".number_format($grand_total, 2)."</b></td></tr>";
			$i++;
		}
		$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr bgcolor=$color><td colspan=2><font size=4pt><b> ".$level2['name']."</b></font></td><td><font size=4pt><b>".number_format($level2_total, 2)."</b></font></td></tr>";
		$i++;
	}
	$color = ($i%2 == 0) ? "lightgrey" : "white";
	$content .= "<tr bgcolor=$color><td colspan=2><font size=5pt><b>TOTAL CAPITAL</b></font></td><td><font size=5pt><b>".number_format($capital, 2)."</b></font></td></tr>";
	$i++;
	$color = ($i%2 == 0) ? "lightgrey" : "white";
	$content .="<tr bgcolor=$color><td colspan=2><font size=5pt><b>TOTAL CAPITAL & LIABILITIES</b></font></td><td><font size=5pt><b>".number_format(($capital+$liabilities), 2)."</b></font></td></tr>";
	$i++;
	$color = ($i%2 == 0) ? "lightgrey" : "white";
	$net_assets = $assets - $capital - $liabilities;
	$net_assets = ($net_assets >= 0) ? number_format($net_assets, 2) : "(".number_format((0 - $net_assets), 2).")"; 
	$content .="<tr bgcolor=$color><td colspan=2><font size=5pt><b>NET ASSETS AVAILABLE FOR BENEFIT</b></font></td><td><font size=5pt><b>".$net_assets."</b></font></td></tr>";
		$content .= "</table>";
		$resp->assign("display_div", "innerHTML", $content);
		return $resp;
}

//CASH FLOW STATEMENT
function cash_flow($from_date,$date, $branch_id){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:"and branch_id=".$branch_id;
	if($date == ''){
		$year = date('Y');
		$month = date('m');
		$mday = date('d');
	}
	$resp->assign("status", "innerHTML", "");
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $year, $month, $mday);
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	if($from_year ==''){
		$from_date = $calc->daysToDate($calc->dateToDays($mday, $month, $year)-365, '%Y-%m-%d');
		$from_year = date('Y', strtotime($from_date));
		$from_month = date('m', strtotime($from_date));
		$from_mday = date('d', strtotime($from_date));
	}
	$branch_res = mysql_query("select * from branch");
	$branch = mysql_fetch_array($branch_res);
	
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR CASH FLOW STATEMENT</h3>
                                <p class="text-default nm"></p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Date Range:</label>
                                           <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>        
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Branch:</label>
                                           '.branch_rep($branch_id).'        
                                        </div>
                                        
                                    </div>
                                </div>';  
                                                          
                                   	
		 $content .= "<div class='panel-footer'>                              
                               
                                <button type='button' class='btn  btn-primary' onclick=\"xajax_cash_flow( getElementById('from_date').value, getElementById('to_date').value,getElementById('branch_id').value);\">Show Report</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div></div>";
                    $resp->call("createDate","from_date");
	$resp->call("createDate","to_date"); 
                   		
	$content .= "<a href='export/cash_flow?from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&year=".$year."&month=".$month."&mday=".$mday."&branch_id=".$branch_id."' target=blank()><b>Printable Version</b></a> | <a href='export/cash_flow?from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&year=".$year."&month=".$month."&mday=".$mday."&format=excel&branch_id=".$branch_id."' target=blank()><b>Export Excel</b></a>";
	
	
	$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">'.$branch['branch_name'].'</h3>
                                <p>CASH FLOW STATEMENT FOR THE PERIOD FROM '.$from_date.' TO '.$to_date.'</p>
                                <p></p>
                            </div>';
 				
	$content .="<table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>
	<tr bgcolor=lightgrey><td colspan=2 align=left><font size=3pt><b>CASH FLOW FROM OPERATING        ACTIVITIES</b></font></td><td><font size=5pt><b></b></font></td></tr>
	<tr bgcolor=white><td colspan=2 align=left><font size=2pt><b>Inflows (Cash Increases)</b></font></td><td><font size=2pt><b></b></font></td></tr>";
	
//LOANS COLLECTED (ISSUED BY THE SACCO)
	$collected_res = mysql_query("select sum(amount) as amount from collected where date >= '".$from_date."' and date <='".$to_date."'");
	$collected = mysql_fetch_array($collected_res);
	$collected_amt = ($collected['amount'] != NULL) ? $collected['amount'] : 0;

	$pay_res = mysql_query("select sum(princ_amt) as princ_amt, sum(int_amt) as int_amt from payment where date >= '".$from_date."' and date <= '".$to_date."' and bank_account>'0'");
	$pay = mysql_fetch_array($pay_res);
	$princ_amt = ($pay['princ_amt'] != NULL) ? $pay['princ_amt'] : 0;
	$int_amt = ($pay['int_amt'] != NULL) ? $pay['int_amt'] : 0;
	$recovered_res = mysql_query("select sum(amount) as amount from recovered where date >='".$from_date."' and date <='".$to_date."'");
	$recovered = mysql_fetch_array($recovered_res);
	$recovered_amt = ($recovered['amount'] != NULL) ? $recovered['amount'] : 0;
	$loans_collected = $princ_amt;     // + $recovered_amt;

//COMMISSION
	$pen_res = mysql_query("select sum(amount) as amount from penalty where status='paid' and date >='".$from_date."' and date <='".$to_date."'");
	$pen = mysql_fetch_array($pen_res);
	$pen_amt = ($pen['amount'] != NULL) ? $pen['amount'] : 0;
	$commission = $pen_amt + $int_amt;

	$deposit_res = mysql_query("select sum(amount) as amount from deposit where date >='".$from_date."' and date <='".$to_date."' and bank_account>'0'");
	$deposit = mysql_fetch_array($deposit_res);
	$deposit_amt = ($deposit['amount'] != NULL) ? $deposit['amount'] : 0;
//SHORT-TERM LOANS PAYABLE
	$short_res = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '2121%' and date >='".$from_date."' and date <='".$to_date."' and p.bank_account >'0'");
	$short = mysql_fetch_array($short_res);
	$short_amt = ($short['amount'] != NULL) ? $short['amount'] : 0;
	$short_credit = $deposit_amt + $short_amt;

//OTHER INCOME
	$inc = mysql_fetch_array(mysql_query("select sum(amount) as amount from other_income where bank_account > 0 and date >='".$from_date."' and date <= '".$to_date."'"));
	$inc_amt = ($inc['amount'] == NULL) ? 0 : $inc['amount'];

	$inflow = $short_credit + $commission + $loans_collected + $collected_amt + $inc_amt + $recovered_amt;
	$content .="<tr bgcolor=lightgrey><td width=10%></td><td><font size=2pt>Collection of Accounts or Documents Receivable</font></td><td><font size=2pt>".number_format($collected_amt, 2)."</font></td></td></tr>
		<tr bgcolor=white><td width=10%></td><td><font size=2pt>Collection of Loans</font></td><td><font size=2pt>".number_format($loans_collected, 2)."</font></td></td></tr>
		<tr bgcolor=lightgrey><td width=10%></td><td><font size=2pt>Short Term External Credit or Savings Deposits Received</font></td><td><font size=2pt>".number_format($short_credit, 2)."</font></td></td></tr>
		<tr bgcolor=white><td width=10%></td><td><font size=2pt>Interest & Penalties</font></td><td><font size=2pt>".number_format($commission, 2)."</font></td></td></tr>
		<tr bgcolor=lightgrey><td width=10%></td><td><font size=2pt>Other Income</font></td><td><font size=2pt>".number_format(($inc_amt+ $recovered_amt), 2)."</font></td></td></tr>
		<tr bgcolor=white><td width=10%></td><td><font size=2pt><b>Total Cash Inflows</b></font></td><td><font size=2pt><b>".number_format($inflow, 2)."</b></font></td></td></tr>";

//OUTFLOW OF CASH
	$content .="<tr bgcolor=lightgrey><td colspan=2 align=left><font size=2pt><b>Outflows (Cash Decrease)</b></font></td><td><font size=5pt><b></b></font></td></tr>";
//DISBURSEMENTS
	$loan_res = mysql_query("select sum(amount) as amount from disbursed where date >='".$from_date."' and date <='".$to_date."' and bank_account>'0'");
	$loan = mysql_fetch_array($loan_res);
	$loan_amt = ($loan['amount'] != NULL) ? $loan['amount'] : 0;
//WITHDRAWALS
	$with_res = mysql_query("select sum(amount) as amount from withdrawal where date >='".$from_date."' and date <='".$to_date."'");
	$with= mysql_fetch_array($with_res);
	$with_amt = ($with['amount'] != NULL) ? $with['amount'] : 0;
//PAYMENT FROM SAVINGS
	//$paid = mysql_fetch_array(mysql_query("select sum(princ_amt + int_amt) as amount from payment where date >='".$from_date."' and date <='".$to_date."' and mode>0"));
	//$paid_amt = ($paid['amount'] == NULL) ? 0 : $paid['amount'];
	$disburse_with = $loan_amt + $with_amt;  // + $paid_amt;

//PAYMENTS OF SHORT TERM LOANS AND ACCOUNTS PAYABLE
	$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id join accounts a on p.account_id=a.id where a.account_no not like '2122%' and paid.date >='".$from_date."' and paid.date <='".$to_date."'");
	$paid = mysql_fetch_array($paid_res);
	$paid_amt = ($paid['amount'] != NULL) ? $paid['amount'] : 0;

//PAYMENT TO EMPLOYEES
	$emp_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where a.account_no like '531%' and e.date >='".$from_date."' and e.date <= '".$to_date."'");
	$emp = mysql_fetch_array($emp_res);
	$emp_amt = ($emp['amount'] != NULL) ? $emp['amount'] : 0;
//ACCRUED EXPENSES
//	$accrued_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id join accounts a on p.account_id=a.id where paid.date >='".$from_date."' and paid.date <= '".$to_date."'");
//	$accrued = mysql_fetch_array($accrued_res);
//	$accrued_amt = ($accrued['amount'] != NULL) ? $accrued['amount'] : 0;

//BANK CHARGES, COMMISSIONS AND INTEREST
//	$charge_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where a.account_no like '515%' and e.date >='".$from_date."' and e.date <= '".$to_date."'");
//	$charge = mysql_fetch_array($charge_res);
//	$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
//
//INSURANCE
//	$ins_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where a.account_no like '522%' and e.date >='".$from_date."' and e.date <= '".$to_date."'");
//	$ins = mysql_fetch_array($ins_res);
//	$ins_amt = ($ins['amount'] != NULL) ? $ins['amount'] : 0;
//MARKETING
	$market_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where a.account_no like '533%' and e.date >='".$from_date."' and e.date <= '".$to_date."'");
	$market = mysql_fetch_array($market_res);
	$market_amt = ($market['amount'] != NULL) ? $market['amount'] : 0;
//GOVERNANCE
	$govern_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where a.account_no like '532%' and e.date >='".$from_date."' and e.date <= '".$to_date."'");
	$govern = mysql_fetch_array($govern_res);
	$govern_amt = ($govern['amount'] != NULL) ? $govern['amount'] : 0;
//ADMINISTRATIVE
	$admin_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where a.account_no like '535%' and e.date >='".$from_date."' and e.date <= '".$to_date."'");
	$admin = mysql_fetch_array($admin_res);
	$admin_amt = ($admin['amount'] != NULL) ? $admin['amount'] : 0;
//OTHER EXPENSES
	//$other_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where (a.account_no like '521%' or a.account_no like '551%' or a.account_no like '513%') and e.date >='".$from_date."' and e.date <= '".$to_date."'");
	$other_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where (a.account_no not like '535%' and a.account_no not like '532%' and a.account_no not like '533%' and a.account_no not like '531%') and e.date >='".$from_date."' and e.date <= '".$to_date."'");
	$other = mysql_fetch_array($other_res);
	$otherexp_amt = ($other['amount'] != NULL) ? $other['amount'] : 0;

	$outflow = $otherexp_amt + $admin_amt + $market_amt + $govern_amt + $ins_amt + $disburse_with + $paid_amt  + $emp_amt;
	$content .="<tr bgcolor=lightgrey><td width=10%></td><td><font size=2pt>Disbursements & Savings Withdrawals</font></td><td><font size=2pt>".number_format($disburse_with, 2)."</font></td></td></tr>
	<tr bgcolor=white><td width=10%></td><td><font size=2pt>Repayment of Short Term Ext. Credit & Accounts Payable</font></td><td><font size=2pt>".number_format($paid_amt, 2)."</font></td></td></tr>
	<tr bgcolor=lightgrey><td width=10%></td><td><font size=2pt>Payments on Personnel Expenses (Salaries, Benefits, Training, etc)</font></td><td><font size=2pt>".number_format($emp_amt, 2)."</font></td></td></tr>
	<tr bgcolor=lightgrey><td width=10%></td><td><font size=2pt>Payment on Marketing Expenses</font></td><td><font size=2pt>".number_format($market_amt, 2)."</font></td></td></tr>
	<tr bgcolor=white><td width=10%></td><td><font size=2pt>Payment on Governance Expenses</font></td><td><font size=2pt>".number_format($govern_amt, 2)."</font></td></td></tr>
	<tr bgcolor=lightgrey><td width=10%></td><td><font size=2pt>Payment on Administrative Expenses</font></td><td><font size=2pt>".number_format($admin_amt, 2)."</font></td></td></tr>
	<tr bgcolor=white><td width=10%></td><td><font size=2pt>Payment on Other Expenses and Services</font></td><td><font size=2pt>".number_format($otherexp_amt, 2)."</font></td></td></tr>
	<tr bgcolor=lightgrey><td width=10%></td><td><font size=2pt><b>Total Cash Outflows</b></font></td><td><font size=2pt><b>".number_format($outflow, 2)."</b></font></td></td></tr>";

	$operating_amt = $inflow - $outflow;
	$operating = ($operating_amt >= 0) ? $operating_amt : "(".(0-$operating_amt).")";
	$content .= "<tr bgcolor=white><td colspan=2 align=left><font size=2pt><b>NET INFLOW (OUTFLOW) OF CASH FROM OPERATING ACTIVITIES</b></font></td><td><font size=2pt><b>".number_format($operating, 2)."</b></font></td></td></tr>
	<tr bgcolor=lightgrey><td colspan=2 align=left><font size=2pt><b>CASH FLOW FROM INVESTMENT ACTIVITIES</b></font></td><td><font size=2pt><b></b></font></td></td></tr>
	<tr bgcolor=white><td colspan=2 align=left><font size=2pt><b>Inflows (Cash Increase)</b></font></td><td><font size=2pt><b></b></font></td></td></tr>";
//SALE OF FIXED ASSETS
	$sell_res = mysql_query("select sum(amount) as amount from sold_asset where date >='".$from_date."' and date<= '".$to_date."'");
	$sell = mysql_fetch_array($sell_res);
	$sell_asset = ($sell['amount'] != NULL) ? $sell['amount'] : 0;
//PURCHASE OF FIXED ASSETS
	$buy_res = mysql_query("select sum(initial_value) as amount from fixed_asset where date >='".$from_date."' and date <= '".$to_date."'");
	$buy = mysql_fetch_array($buy_res);
	$buy_asset= ($buy['amount'] != NULL) ? $buy['amount'] : 0;
//SALE OF INVESTMENTS
	$sell_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where date >='".$from_date."' and date <='".$to_date."'");
	$sell = mysql_fetch_array($sell_res);
	//OTHER INCOME EG PHOTOCOPIER, EXCEPT DONATIONS
	//$other_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.account_no not like '423%' and o.date >='".$from_date."' and o.date <= '".$to_date."'");  //LEAVE OUT DONATIONS
	//$other = mysql_fetch_array($other_res);
	//$other_amt = ($other['amount'] != NULL) ? $other['amount'] : 0;

	$sell_invest = ($sell['amount'] != NULL) ? $sell['amount'] : 0;
//PURCHASE OF INVESTMENTS
	$buy_res = mysql_query("select sum(quantity * amount) as amount from investments where date >='".$from_date."' and date <='".$to_date."'");
	$buy = mysql_fetch_array($buy_res);
	$buy_invest = ($buy['amount'] != NULL) ? $buy['amount'] : 0;

	//$invest_res = mysql_query("select sum(amount) as amount from investments where ")
	$investment_amt = $sell_asset + $sell_invest - $buy_asset - $buy_invest;  // + $other_amt;
	$investment = ($investment_amt >= 0) ? $investment_amt : "(".(0-$investment_amt).")";
	$content .= "<tr bgcolor=lightgrey><td></td><td><font size=2pt>Sale of Fixed Assets</font></td><td><font size=2pt>".number_format($sell_asset, 2)."</font></td></td></tr>
	<tr bgcolor=white><td></td><td><font size=2pt>Sale of Investments</font></td><td><font size=2pt>".number_format($sell_invest, 2)."</font></td></td></tr>
	<tr bgcolor=lightgrey><td></td><td><font size=2pt><b>Total Cash Inflows</b></font></td><td><font size=2pt><b>".number_format(($sell_invest + $sell_asset), 2)."</b></font></td></td></tr>
	<tr bgcolor=white><td colspan=2 align=left><font size=2pt><b>Outflows (Cash Decrease)</b></font></td><td><font size=2pt><b></b></font></td></td></tr>
	<tr bgcolor=lightgrey><td></td><td><font size=2pt>Purchase of Fixed Assets</font></td><td><font size=2pt>".number_format($buy_asset, 2)."</font></td></td></tr>
	<tr bgcolor=white><td></td><td><font size=2pt>Purchase of Investments</font></td><td><font size=2pt>".number_format($buy_invest, 2)."</font></td></td></tr>
	<tr bgcolor=lightgrey><td></td><td><font size=2pt><b>Total Cash Outflows</b></font></td><td><font size=2pt><b>".number_format(($buy_invest + $buy_asset), 2)."</b></font></td></td></tr>
	<tr bgcolor=white><td colspan=2 align=left><font size=2pt><b>NET INFLOW (OUTFLOW) OF CASH FROM INVESTMENT ACTIVITIES</b></font></td><td><font size=2pt><b>".number_format($investment, 2)."</b></font></td></td></tr>";
//FINANCING ACTIVITIES
	$content .="<tr bgcolor=lightgrey><td colspan=2 align=left><font size=2pt><b>CASH FLOW FROM FINANCING ACTIVITIES</b></font></td><td><font size=2pt><b></b></font></td></td></tr>
	<tr bgcolor=white><td colspan=2 align=left><font size=2pt><b>Inflows (Cash Increase)</b></font></td><td><font size=2pt><b></b></font></td></td></tr>";
//INFLOWS
//SHARES SOLD
	$shares_res = mysql_query("select sum(value) as amount from shares where date >='".$from_date."' and date <='".$to_date."' and bank_account>0");
	$shares = mysql_fetch_array($shares_res);
	$shares_amt = ($shares['amount'] != NULL) ? $shares['amount'] : 0;
//LONG TERM  EXTERNAL CREDIT
	$long_res = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '2122%' and date >='".$from_date."' and date <='".$to_date."' and p.bank_account>'0'");
	$long = mysql_fetch_array($long_res);
	$long_amt = ($long['amount'] != NULL) ? $long['amount'] : 0;
//DONATIONS AND GRANTS
	$other_res = mysql_query("select sum(o.amount) as amount from other_funds o where o.date >='".$from_date."' and o.date <= '".$to_date."' and o.bank_account >'0'"); 
	$other = mysql_fetch_array($other_res);
	$other_amt = ($other['amount'] != NULL) ? $other['amount'] : 0;
//REPAYMENT OF LONG TERM CREDIT
	$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id join accounts a on p.account_id=a.id where a.account_no like '2122%' and paid.date >='".$from_date."' and paid.date <='".$to_date."'");
	$paid = mysql_fetch_array($paid_res);
	$paid_amt = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
//DIVIDENDS PAID
	$div_res = mysql_query("select sum(total_amount) as amount from share_dividends where date >= '".$from_date."' and date <= '".$to_date."' and bank_account>0");
	$div = mysql_fetch_array($div_res);
	$div_amt = ($div['amount'] != NULL) ? $div['amount'] : 0;
	$finance_amt = $shares_amt + $long_amt + $other_amt -$paid_amt - $div_amt;
	$finance = ($finance_amt >= 0) ? $finance_amt : "(".(0-$finance_amt).")";

	
	$content .= "<tr bgcolor=lightgrey><td></td><td><font size=2pt>Sale of Shares</font></td><td><font size=2pt>".$shares_amt."</font></td></td></tr>
	<tr bgcolor=white><td></td><td><font size=2pt>Long Term External Credit Received</font></td><td><font size=2pt>".number_format($long_amt, 2)."</font></td></td></tr>
	<tr bgcolor=lightgrey><td></td><td><font size=2pt>Donations & Grants</font></td><td><font size=2pt>".number_format($other_amt, 2)."</font></td></td></tr>
	<tr bgcolor=white><td></td><td><font size=2pt><b>Total Cash Inflows</b></font></td><td><font size=2pt><b>".number_format(($shares_amt + $other_amt + $long_amt), 2)."</b></font></td></td></tr>
	<tr bgcolor=lightgrey><td colspan=2 align=left><font size=2pt><b>Outflows (Cash Decrease)</b></font></td><td><font size=2pt><b></b></font></td></td></tr>
	<tr bgcolor=white><td></td><td><font size=2pt>Long Term External Credit Repaid</font></td><td><font size=2pt>".number_format($paid_amt, 2)."</font></td></td></tr>
	<tr bgcolor=lightgrey><td></td><td><font size=2pt>Dividends Paid</font></td><td><font size=2pt>".number_format($div_amt, 2)."</font></td></td></tr>
	<tr bgcolor=white><td></td><td><font size=2pt><b>Total Cash Outflows</b></font></td><td><font size=2pt><b>".number_format(($div_amt + $paid_amt), 2)."</b></font></td></td></tr>
	<tr bgcolor=lightgrey><td colspan=2 align=left><font size=2pt><b>NET INFLOW (OUTFLOW) OF CASH FROM FINANCE ACTIVITIES</b></font></td><td><font size=2pt><b>".number_format($finance, 2)."</b></font></td></td></tr>";
	$content .= "</table>";

	$net_change = $operating_amt + $investment_amt + $finance_amt;
	$net = ($net_change >= 0) ? $net_change : "(".(0 - $net_change).")";

//DEPOSITS
			$dep_res = mysql_query("select sum(amount) as amount from deposit where bank_account>'0' and date <'".$from_date."'");
			$dep = mysql_fetch_array($dep_res);
			$dep_amt = ($dep['amount'] == NULL) ? 0 : $dep['amount'];
			//WITHDRAWALS
			$with_res = mysql_query("select sum(amount) as amount from withdrawal where bank_account>'0' and date <'".$from_date."'");
			$with = mysql_fetch_array($with_res);
			$with_amt = ($with['amount'] == NULL) ? 0 : $with['amount'];
			//OTHER INCOME
			$other_res = mysql_query("select sum(amount) as amount from other_income where bank_account>'0' and date <'".$from_date."'");
			$other = mysql_fetch_array($other_res);
			$other_amt = ($other['amount'] == NULL) ? 0 : $other['amount'];
			//EXPENSES
			$expense_res = mysql_query("select sum(amount) as amount from expense where bank_account>'0' and date <'".$from_date."'");
			$expense = mysql_fetch_array($expense_res);
			$expense_amt = ($expense['amount'] == NULL) ? 0 : $expense['amount'];
			//LOANS PAYABLE
			$loans_payable = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '212%' and p.bank_account>'0' and p.date < '".$from_date."'");
			$loans_payable = mysql_fetch_array($loans_payable);
			$loans_payable = ($loans_payable['amount'] != NULL) ? $loans_payable['amount'] : 0;
			//PAYABLE PAID
			$payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where bank_account>'0' and date <'".$from_date."'");
			$payable_paid = mysql_fetch_array($payable_paid_res);
			$payable_paid_amt = ($payable_paid['amount'] == NULL) ? 0 : $payable_paid['amount'];
			//RECEIVALE COLLECTED
			$collected_res = mysql_query("select sum(amount) as amount from collected where bank_account>'0' and date <'".$from_date."'");
			$collected = mysql_fetch_array($collected_res);
			$collected_amt = ($collected['amount'] == NULL) ? 0 : $collected['amount'];
			//DISBURSED LOANS
			$disb_res = mysql_query("select sum(amount) as amount from disbursed where bank_account>'0' and date < '".$from_date."'");
			$disb = mysql_fetch_array($disb_res);
			$disb_amt = ($disb['amount'] == NULL) ? 0 : $disb['amount'];
			//PAYMENTS
			$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id  where p.date < '".$from_date."' and p.bank_account>0");
			$pay = mysql_fetch_array($pay_res);
			$pay_amt = ($pay['amount'] == NULL) ? 0 : $pay['amount'];
			//PENALTIES
			$pen_res = mysql_query("select sum(p.amount) as amount from penalty p join disbursed d on p.loan_id=d.id where p.status='paid' and p.date < '".$from_date."'");
			$pen = mysql_fetch_array($pen_res);
			$pen_amt = ($pen['amount']==NULL) ? 0 : $pen['amount'];
								
			//SHARES
			$shares_res = mysql_query("select sum(value) as amount from shares where date <'".$from_date."'");
			$shares = mysql_fetch_array($shares_res); 
			$shares_amt = ($shares['amount'] == NULL) ? 0 : $shares['amount'];
			//RECOVERED
			$rec_res = mysql_query("select sum(r.amount) as amount from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.date < '".$from_date."'");
			$rec = mysql_fetch_array($rec_res);	
			$rec_amt = ($rec['amount'] == NULL) ? 0 : $rec['amount']; 
			//INVESTMENTS 
			$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where date < '".$from_date."'");
			$invest = mysql_fetch_array($invest_res);
			$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
			//DIVIDENDS PAID
			$div_res = mysql_query("select sum(total_amount) as total_amount from share_dividends where date<'".$from_date."'");
			$div = mysql_fetch_array($div_res);
			$div_amt = ($div['amount'] != NULL) ? $div['amount'] : 0;

								
			//SOLD INVESTMENTS
			$soldinvest_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where  date < '".$from_date."'");
			$soldinvest = mysql_fetch_array($soldinvest_res);


			//FIXED ASSETS 
			$fixed_res = mysql_query("select sum(initial_value) as amount from fixed_asset where  date <'".$from_date."'");
			$fixed = mysql_fetch_array($fixed_res);
			$soldasset_res = mysql_query("select sum(amount) as amount from sold_asset where  date < '".$from_date."'");
			$soldasset = mysql_fetch_array($soldasset_res);
									
		/*	//CASH IMPORTED
			$import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id>'0' and date <'".$from_date."'");
			$import = mysql_fetch_array($import_res);
			$import_amt = $import['amount'];

			//CASH EXPORTED
			$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id>'0' and date <'".$from_date."'");
			
			$export = mysql_fetch_array($export_res);
			$export_amt = intval($export['amount']);
*/
			//CAPITAL FUNDS
			$fund_res = mysql_query("select sum(amount) as amount from other_funds where bank_account>0 and date <'".$from_date."'");
			$fund = mysql_fetch_array($fund_res);
			$fund_amt = $fund['amount'];

			$begin_bal =  $collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt + $fund_amt;	
	
	$content .="<br><table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>
	<tr bgcolor=white><td colspan=2 align=left><font size=2pt><b>Net Increase (Decrease) in Cash</b></font></td><td><font size=2pt><b>".number_format($net, 2)."</b></font></td></td></tr>
	<tr bgcolor=lightgrey><td colspan=2 align=left><font size=2pt><b>Cash Balance at the Beginning of the Period</b></font></td><td><font size=2pt><b>".number_format($begin_bal, 2)."</b></font></td></td></tr>
	<tr bgcolor=white><td colspan=2 align=left><font size=2pt><b>Cash Balance at End of Period</b></font></td><td><font size=2pt><b>".number_format(($begin_bal + $net_change), 2)."</b></font></td></td></tr>";
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


//INCOME STATEMENT
function income_statement($from_date,$date, $basis, $branch_id){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$branch_=($branch_id=='all'||$branch_id=='')?NULL:"and branch_id=".$branch_id;
	if($date == ''){
		$year = date('Y');
		$month = date('m');
		$mday = date('d');
	}
	$resp->assign("status", "innerHTML", "");
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $year, $month, $mday);
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	if($from_year ==''){
		$from_date = $calc->daysToDate($calc->dateToDays($mday, $month, $year)-365, '%Y-%m-%d');
		$from_year = date('Y', strtotime($from_date));
		$from_month = date('m', strtotime($from_date));
		$from_mday = date('d', strtotime($from_date));
	}
	$branch_res = mysql_query("select * from branch");
	$branch = mysql_fetch_array($branch_res);
	
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR INCOME STATEMENT</h3>
                                <p class="text-default nm"></p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                           <label class="control-label">Date Range:</label>
                                            <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>         
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Basis:</label>
                                          <select id="basis" class="form-control"><option value="Cash Basis">Cash Basis<option value="Accrual Basis">Accrual Basis</select>        
                                        </div>
                                        
                                    </div>
                                </div>  
                                
                       <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Branch:</label>
                                           '.branch_rep($branch_id).'        
                                        </div>
                                                                            
                                    </div>
                                </div>';                                 
                                   	
		 $content .= "<div class='panel-footer'>                              
                               
                                <button type='button' class='btn  btn-primary' onclick=\"xajax_income_statement( getElementById('from_date').value,getElementById('to_date').value, getElementById('basis').value,getElementById('branch_id').value);\">Show Report</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    
                    $resp->call("createDate","from_date");
		    $resp->call("createDate","to_date");
	
	$content .= "<a href='export/income_statement?from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&year=".$year."&month=".$month."&mday=".$mday."&branch_id=".$branch_id."&basis=".$basis."' target=blank()><b>Printable Version</b></a> | <a href='export/income_statement?from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&year=".$year."&month=".$month."&mday=".$mday."&branch_id=".$branch_id."&basis=".$basis."&format=excel' target=blank()><b>Export Excel</b></a>";
	
	$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <p><h4 class="text-primary mt0">'.$branch['branch_name'].'</h5></p>
                                <p><h5 class="text-primary mt0">INCOME STATEMENT FOR THE PERIOD FROM '.$from_date.' TO '.$to_date.'</h5></p>
                                <p class="text-primary mt0">'.$basis.'</p>
                            </div>';
 		$content .= "<table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>";
	
	//INCOME FOR INCOME STATEMENT
		$level2_res= mysql_query("select * from accounts where account_no >40 and account_no <=49 ".$branch_." order by account_no");
		$income = 0;
		$i=0;
		$content .= "<tr class=headings><td colspan=3><font size=5pt><b>INCOME</b></font></td></tr>";
		while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
			
			$color=($i%2 == 0) ? lightgrey : "white";
			$content .= "<tr bgcolor=$color><td colspan=2><font size=4pt><b>".$level2['name']."</b></font></td><td></td></tr>";
			$i++;
			$level3_res = mysql_query("select * from accounts where account_no >=411 and account_no <= 599 and account_no like '".$level2['account_no']."%' order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				$color=($i%2 == 0) ? lightgrey : "white";
				$content .= "<tr bgcolor=$color><td colspan=2 align=left><b>".$level3['name']."</b></td><td></td></tr>";
				$i++;
				$grand_total = 0;
				if($level3['account_no'] == '411'){
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4111 and account_no <= 4119");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						if($level4['account_no'] == 4111)
							$int_res = mysql_query("select sum(p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product prod on applic.product_id=prod.id join accounts a on prod.account_id=a.id where p.date >= '".$from_date."' and p.date <='".$to_date."' and a.name like '%Short%Term%'");
						if($level4['account_no'] == 4112)
							$int_res = mysql_query("select sum(p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product prod on applic.product_id=prod.id join accounts a on prod.account_id=a.id where p.date >= '".$from_date."' and p.date <='".$to_date."' and a.name like '%Medium%Term%'");
						if($level4['account_no'] == 4113)
							$int_res = mysql_query("select sum(p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product prod on applic.product_id=prod.id join accounts a on prod.account_id=a.id where p.date >= '".$from_date."' and p.date <='".$to_date."' and a.name like '%Long%Term%'");
						if($level4['account_no'] == 4114)
							$int_res = mysql_query("select sum(amount) as amount from penalty where date>='".$from_date."' and date <='".$to_date."' and status='paid'");
						if($level4['account_no'] > 4114)
							$int_res = mysql_query("select sum(i.amount) as amount from other_income i join accounts a on i.account_id=a.id where a.account_no like '".$level4['account_no']."%' and i.date >='".$from_date."' and i.date <='".$to_date."'");
						$int = mysql_fetch_array($int_res);
						if($int['amount'] != NULL)
							$sub1_total = $int['amount'];
						$color=($i%2 == 0) ? lightgrey : "white";
						$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						$i++;
						$grand_total += $sub1_total;
					}
			//	}elseif($level3['account_no'] >= 412 && $level3['account_no'] <= 419){   //RECEIVABLES
				}elseif($level3['account_no'] >= 412 && $level3['account_no'] <= 413){   //RECEIVABLES
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4121 and account_no <= 4199 and account_no not like '%2'");
					$pre = ($basis == 'Cash Basis') ? "(Received)" : "";
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$rec_res = mysql_query("select sum(amount) as amount from receivable where account_id='".$level4['id']."' and maturity_date <= '".$to_date."' and maturity_date >='".$from_date."'");
						$rec = mysql_fetch_array($rec_res);
						
						if($rec['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 412101 and account_no <= 419999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$rec_res = mysql_query("select sum(amount) as amount from receivable where account_id='".$level5['id']."' and maturity_date <= '".$to_date."' and maturity_date >= '".$from_date."'");
								
								$rec = mysql_fetch_array($rec_res);
								if($rec['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 41210101 and account_no <= 41999999");
									while($level6 = mysql_fetch_array($level6_res)){
										$rec_res = mysql_query("select sum(amount) as amount from receivable where account_id='".$level6['id']."' and maturity_date <= '".$to_date."' and maturity_date >= '".$from_date."'");
										$rec = mysql_fetch_array($rec_res);
										$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where r.account_id='".$level6['id']."' and r.maturity_date <= '".$to_date."' and c.date <= '".$to_date."' and c.date >='".$from_date."'");
										$col = mysql_fetch_array($col_res);
										$rec_amt = ($rec['amount'] != NULL) ? $rec['amount'] : 0;
										$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
										$top_up = ($basis == 'Accrual Basis') ? $rec_amt : $col_amt;
										$sub2_total += $top_up;
									}
								}else{
									$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where r.account_id='".$level5['id']."' and r.maturity_date <= '".$to_date."' and c.date <= '".$to_date."' and c.date >= '".$from_date."'");
									$col = mysql_fetch_array($col_res);
									$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
									
									$sub2_total = ($basis == 'Accrual Basis') ? $rec['amount'] : $col_amt;
								
								}
								$color = ($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td><font size=4pt>".$level5['account_no']."</font></td><td><font size=4pt>".$level5['name']." ".$pre."</font></td><td>".number_format($sub2_total, 2)."</td></tr>";
								$i++;
								$sub1_total += $sub2_total;
							}
						}else{
							$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where r.account_id='".$level6['id']."' and r.maturity_date <= '".$to_date."' and c.date <= '".$to_date."' and c.date >= '".$from_date."'");
							$col = mysql_fetch_array($col_res);
							
							$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
							$top_up = ($basis == 'Accrual Basis') ? $rec['amount'] : $col_amt;
							
							$sub1_total += $top_up;
						}
						$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']." ".$pre."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						$i++;
						$grand_total += $sub1_total;
					}

				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4121 and account_no <= 4199 and account_no like '%2'");
				
				while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						//INCOME REGISTERED DIRECTLY
						$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.account_no='".$level4['account_no']."' and o.date <='".$to_date."' and o.date >='".$from_date."'");
						$inc = mysql_fetch_array($inc_res);
						if($inc['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 412101 and account_no <= 419999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.account_no='".$level5['account_no']."' and o.date <='".$to_date."' and o.date >='".$from_date."'");
								$inc = mysql_fetch_array($inc_res);
								if($inc['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and  account_no >= 41210101 and account_no <= 41999999");
									while($level6 = mysql_fetch_array($level6_res)){
										$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.account_no='".$level5['account_no']."' and o.date >='".$from_date."' and o.date <='".$to_date."'");
										$inc = mysql_fetch_array($inc_res);						
										$sub2_total += $inc['amount'];
									}
								}else{
									$sub2_total = $inc['amount'];
								}
								$color = ($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td><font size=4pt></td><td><font size=4pt>".$level5['name']."</font></td><td>".number_format($sub2_total, 2)."</td></tr>";
								$i++;
								$sub1_total += $sub2_total;
							}
						}else{
							$sub1_total = $inc['amount'];
					}
					$color = ($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
					$i++;
					$grand_total += $sub1_total;
				}
			}elseif($level3['account_no'] == '424'){
				$inv_res = mysql_query("select sum(quantity * purchase_price) as tot_cost, sum(quantity * amount) as tot_gain from sold_invest where date >='".$from_date."' and date <= '".$to_date."'");
				$inv = mysql_fetch_array($inv_res);
				if($inv['tot_cost'] == NULL){
					$grand_total =0;
				}else{
					$grand_total = $inv['tot_gain'] - $inv['tot_cost'];
				}
			}elseif($level3['account_no'] >= 414){
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4141 and account_no <= 4999");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['account_no'] == '4221'){    //MONTHLY CHARGES
						$charge_res = mysql_query("select sum(amount) as amount from monthly_charge where date >='".$from_date."' and date <= '".$to_date."'");
						$charge = mysql_fetch_array($charge_res);
						$sub1_total = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
					}elseif($level4['account_no'] == '4222'){    //TRANSACTIONAL CHARGES
						$dep_res = mysql_query("select sum(flat_value + percent_value) as amount from deposit where date >= '".$from_date."' and date <= '".$to_date."'");
						$dep = mysql_fetch_array($dep_res);

						$with_res = mysql_query("select sum(flat_value + percent_value) as amount from withdrawal where date >= '".$from_date."' and date <= '".$to_date."'");
						$with = mysql_fetch_array($with_res);
						$sub1_total = $dep['amount'] + $with['amount'];
					}elseif($level4['account_no'] == '4223'){   //INCOME FROM SALE OF ASSETS
						$sold_res = mysql_query("select sum(amount) as amount from sold_asset where date <='".$to_date."' and date >= '".$from_date."'");
						$sold = mysql_fetch_array($sold_res);
						$sub1_total = ($sold['amount'] != NULL) ? $sold['amount'] : 0;
					}else{	
						$inc_res = mysql_query("select sum(amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level4['id']."' and o.date >= '".$from_date."' and o.date <= '".$to_date."'");
						$inc = mysql_fetch_array($inc_res);
						if($inc['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 414101 and account_no <= 499999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level5['id']."' and o.date >= '".$from_date."' and o.date <= '".$to_date."'");
								$inc = mysql_fetch_array($inc_res);
								if($inc['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 41410101 and account_no <= 49999999");
									while($level6 = mysql_fetch_array($level6_res)){
										$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level6['id']."' and o.date >= '".$from_date."' and o.date <= '".$to_date."'");
										$inc = mysql_fetch_array($inc_res);
										$sub2_total += $inc['amount'];
									}
								}else{
									
									$sub2_total = $inc['amount'];
								}
								$color=($i%2 == 0) ? lightgrey : "white";
								$content .= "<tr bgcolor=$color><td></td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
								$sub1_total += $sub2_total;
								$i++;
							}
						}else{
							
							$sub1_total += $inc['amount'];
						}
						
					}
					$color=($i%2 == 0) ? lightgrey : "white";
					$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
					$i++;
					$grand_total += $sub1_total;
				}
			}
			$color=($i%2 == 0) ? lightgrey : "white";
			$content .= "<tr bgcolor=$color><td colspan=2 align=left><b>TOTAL ".$level3['name']."</b></td><td><b>".number_format($grand_total, 2)."</b></td></tr>";
			$i++;
			$level2_total += $grand_total;
		}

		$color=($i%2 == 0) ? lightgrey : "white";
		$content .= "<tr bgcolor=$color><td colspan=2><font size=4pt><b>TOTAL ".$level2['name']."</b></font></td><td><font size=4pt><b>".number_format($level2_total, 2)."</b></font></td></tr>";
		$i++;
		$income += $level2_total;
	}
	$color=($i%2 == 0) ? lightgrey : "white";
	$content .= "<tr bgcolor=$color><td colspan=2><font size=5pt><b>TOTAL INCOME</b></font></td><td><font size=4pt><b>".number_format($income, 2)."</b></font></td></tr>";
	$i++;

// EXPENSES
	$color=($i%2 == 0) ? lightgrey : "white";
	$content .= "<tr bgcolor=$color><td colspan=3><font size=5pt><b>COSTS & EXPENSES</b></font></td></tr>";
	$i++;
	$level2_res = mysql_query("select * from accounts where account_no >50 and account_no <60 order by account_no");
	while($level2 = mysql_fetch_array($level2_res)){
		$level2_total = 0;
		$color=($i%2 == 0) ? lightgrey : "white";
		$content .= "<tr bgcolor=$color><td colspan=2><font size=4pt><b>".$level2['name']."</b></font></td><td></td></tr>";
		$i++;
		$level3_res = mysql_query("select * from accounts where account_no like '".$level2['account_no']."%' and account_no >= 511 and account_no <= 599");
		while($level3 = mysql_fetch_array($level3_res)){
			$grand_total = 0;
			$color=($i%2 == 0) ? lightgrey : "white";
			$content .= "<tr bgcolor=$color><td colspan=2 align=left><b>".$level3['name']."</b></td><td></td></tr>";
			$i++;
			$int_accounts ='0';
			if($level3['account_no'] == 511){
				$prod_res = mysql_query("select s.id as id, a.name as name, a.account_no as account_no from savings_product s  join accounts a on s.account_id=a.id where s.type='free' order by a.account_no");
				while($prod = mysql_fetch_array($prod_res)){
					$int_res = mysql_query("select sum(amount) as amount from save_interest  i join mem_accounts m on i.memaccount_id=m.id where m.saveproduct_id='".$prod['id']."' and i.date <='".$to_date."' and i.date >='".$from_date."'");
					$int = mysql_fetch_array($int_res);
					$sub1_total = ($int['amount'] != NULL) ? $int['amount'] : 0;
					$level4_res = mysql_query("select * from accounts where name like '%Expense%on %".$prod['name']."'");
					$level4 = mysql_fetch_array($level4_res);
					$color=($i%2 == 0) ? lightgrey : "white";
					$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
					$i++;
					$grand_total += $sub1_total; 
					$int_accounts .= ", '".$level4['id']."'";
				}
			}   //else{   //TO INCLUDE OTHER EXPENSES ON SAVINGS 	
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 5111 and account_no <= 5999 and id not in (".$int_accounts.") and name not like '%Interest%Expense%on%'");

				
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['name']== 'Dividends Paid on Member Shares'){
						$div_res = mysql_query("select sum(total_amount) as amount from share_dividends where date >= '".$from_date."' and  date<='".$to_date."'");
						$div = mysql_fetch_array($div_res);
						$sub1_total = ($div['amount'] != NULL) ? $div['amount'] : 0;
					}elseif($level4['name']== 'Bad Debt Expense on Loans'){
						$write_res = mysql_query("select sum(amount) as amount from written_off where date >= '".$from_date."' and date <= '".$to_date."'");
						$write = mysql_fetch_array($write_res);
						$written_amt = ($write['amount'] != NULL) ? $write['amount'] : 0;
						$rec_res = mysql_query("select sum(amount) as amount from recovered where date<= '".$to_date."' and date >= '".$from_date."'");
						$rec = mysql_fetch_array($rec_res);
						$rec_amt = ($rec['amount'] != NULL) ? $rec['amount'] : 0;
						$sub1_total = $written_amt - $rec_amt;
					}elseif($level4['account_no'] == '5342'){
								$sth = mysql_query("select * from accounts where account_no like '123%' and account_no>'1231' and account_no <= 1239");
								$x=1;
								while($row = mysql_fetch_array($sth)){
									$dep_res = mysql_query("select sum(d.amount) as amount from deppreciation d join fixed_asset f on d.asset_id=f.id join accounts a on f.account_id=a.id where a.account_no like '".$row['account_no']."%' and d.date >='$from_date' and d.date <= '$to_date'");
									$dep = mysql_fetch_array($dep_res);
									$dep_amt = ($dep['amount'] == NULL) ? 0 : $dep['amount'];
									$color = ($i%2 == 0) ? "lightgrey" : "white";
									$content .= "<tr bgcolor=$color><td>".$level4['account_no'].$x."</td><td>Depreciation - ".$row['name']."</td><td>".number_format($dep_amt, 2)."</td></tr>";
									$sub2_total = $dep_amt;
									$sub1_total += $sub2_total;
									$i++;
									$x++;
								}
					}else{
						$exp_res = mysql_query("select sum(amount) as amount from expense where account_id='".$level4['id']."' and date>= '".$from_date."' and date <='".$to_date."'");
						$exp = mysql_fetch_array($exp_res);
						if($exp['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= '511101' and account_no <= '599999'");
							//$resp->AddAppend("status", "innerHTML", "select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= '512101' and account_no <= '599999'<BR>");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$exp_res = mysql_query("select sum(amount) as amount from expense where account_id='".$level5['id']."' and date>= '".$from_date."' and date <='".$to_date."'");
								$exp = mysql_fetch_array($exp_res);
								if($exp['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= '51110101' and account_no <= '59999999'");
									while($level6 = mysql_fetch_array($level6_res)){
										$exp_res = mysql_query("select sum(amount) as amount from expense where account_id='".$level6['id']."' and date>= '".$from_date."' and date <='".$to_date."'");
										$exp = mysql_fetch_array($exp_res);
										$sub2_total += $exp['amount'];
									}
								}else{
									$sub2_total = $exp['amount'];
								}
								$color=($i%2 == 0) ? lightgrey : "white";
								$content .= "<tr bgcolor=$color><td><b>".$level5['account_no']."</b></td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
								$i++;
								$sub1_total += $sub2_total;
								
							}
							
						}else{
							//if($level4['account_no'] == '5521')
							//		$resp->AddAlert("Hi");
							$sub1_total = ($exp['amount'] != NULL) ? $exp['amount'] : 0;
							//$sub1_total = $exp['amount'];
						}
					}
					
					$color=($i%2 == 0) ? lightgrey : "white";
					$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><font size=2><b>".number_format($sub1_total, 2)."</b></font></td></tr>";
					$i++;
					$grand_total += $sub1_total;
					
				}
			//}   //ELSE FOR SAVINGS EXPENSES
			
			if($level3['account_no'] == '561'){
				//ACCOUNTS PAYABLE
				$pay_res = mysql_query("select p.account_id as account_id, a.name as name, sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where p.bank_account =0 and p.date >= '".$from_date."' and p.date <= '".$to_date."' group by p.account_id");
				//$resp->AddAlert(mysql_error());
				while($pay = mysql_fetch_array($pay_res)){
					//$pay = mysql_fetch_array($pay_res);
					$pay_amt = ($pay['amount'] == NULL) ? 0 : $pay['amount'];
					
					$color=($i%2 == 0) ? lightgrey : "white";
					$content .= "<tr bgcolor=$color><td colspan=2><b> ".$pay['name']." Expenses</b></td><td><b>".number_format($pay_amt, 2)."</b></td></tr>";
					$i++;
					$grand_total += $pay_amt;  // - $paid_amt;
				}
			}
			$color=($i%2 == 0) ? lightgrey : "white";
			$content .= "<tr bgcolor=$color><td colspan=2><b>TOTAL ".$level3['name']."</b></td><td><b>".number_format($grand_total, 2)."</b></td></tr>";
			$i++;
			$level2_total += $grand_total;
		}
		$color=($i%2 == 0) ? lightgrey : "white";
		$content .= "<tr bgcolor=$color><td COLSPAN=2><font size=4pt><b>TOTAL ".$level2['name']."</b></font></td><td><font size=4pt><b>".number_format($level2_total, 2)."</b></font></td></tr>";
		$i++;
		if($level2['account_no'] ==51)
			$financial = $level2_total;
		if($level2['account_no'] == 52)
			$non_financial = $level2_total;
		$expense = $expense + $level2_total;
	}
	$color=($i%2 == 0) ? lightgrey : "white";
	$content .= "<tr bgcolor=$color><td colspan=2><font size=5pt><b>TOTAL COSTS & EXPENSES</b></font></td><td><font size=5pt><b>".number_format($expense, 2)."</b></font></td></tr>";
	$i++;
	$gross_margen = $income - $financial - $non_financial;
	$color=($i%2 == 0) ? lightgrey : "white";
	$content .= "<tr bgcolor=$color><td colspan=2><font size=5pt><b>GROSS MARGEN</b></font></td><td><font size=5pt><b>".number_format($gross_margen, 2)."</b></font></td></tr>";
	$i++;
	$net_income = $income - $expense;
	
	//$net_income = number_format($net_income, 2);
	$net_income = ($net_income > 0) ? number_format($net_income,2) : "(". number_format((0 - $net_income),2) .")";
	$color=($i%2 == 0) ? lightgrey : "white";
	$content .= "<tr bgcolor=$color><td colspan=2><font size=5pt><b>NET INCOME (LOSS)</b></font></td><td><font size=5pt><b>".$net_income."</b></font></td></tr>";
	$i++;
	$content .= "</table>";
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//PMT INCOME STATEMENT
function pmt_income_statement($from_date, $date, $basis, $report_freq){
 list($from_year,$from_month,$from_mday) = explode('-', $from_date);
 list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$pmt = new pmt_statement("Income Statement",$basis,$year,$report_freq,$from_year,$from_month);
	//$branch_=($branch_id=='all'||$branch_id=='')?NULL:"and branch_id=".$branch_id;
	$branch_ = NULL;
	if($year == ''){
		$year = date('Y');
		$month = date('m');
		$mday = date('d');
	}
	$resp->assign("status", "innerHTML", "");
	$to_date = $calc->endofMonthBySpan(0, $month, $year, '%Y-%m-%d 23:59:59');
	$from_date = sprintf("%d-%02d-01 00:00:00", $from_year, $from_month);
	$last_from_date = sprintf("%d-%02d-01 00:00:00", $from_year-1, $from_month);
	if($from_year ==''){
		$from_date = sprintf("%d-01-01 00:00:00", date('Y')-1);
		$from_year = date('Y', strtotime($from_date));
		$from_month = date('m', strtotime($from_date));
		$from_mday = date('d', strtotime($from_date));
	}
	$days = $calc->dateDiff($from_mday, $from_month, $from_year, $mday, $month, $year);
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR PMT INCOME STATEMENT</h3>
                                <p class="text-default nm"></p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Date Range:</label>
                                         
                                          <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="Last Financial Year" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>      
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Basis:</label>
                                          <select id="basis" class="form-control"><option value="Cash Basis">Cash Basis<option value="Accrual Basis">Accrual Basis</select>        
                                        </div>
                                        
                                    </div>
                                </div>  
                                
                       <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Report Frequency:</label>
                                           <select name="report_freq" id="report_freq" class="form-control"><option value="monthly">Monthly<option value="quarterly">Quarterly</option></select>        
                                        </div>
                                                                            
                                    </div>
                                </div>';                                 
                                   	
		 $content .= "<div class='panel-footer'>                              
                               
                                <button type='button' class='btn  btn-primary' onclick=\"xajax_pmt_income_statement(getElementById('from_date').value,getElementById('to_date').value, getElementById('basis').value,getElementById('report_freq').value);\">Show Report</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","from_date");
	$resp->call("createDate","to_date");
	
	/*if($from_date =='' || $date==''){
	
		$cont="<font color=red>Please Select the Date Range for Your Report</font>";
		$resp->assign("status", "innerHTML", $cont);
	}
	else{*/
                    
	$content .= $pmt->setDates($from_year, $from_month);
	$content .= "<a href='export/pmt_income_statement?from_year=".$from_year."&from_month=".$from_month."&year=".$year."&month=".$month."&branch_id=".$branch_id."&basis=".$basis."' target=blank()><b>Printable Version</b></a> | <a href='export/pmt_income_statement?from_year=".$from_year."&from_month=".$from_month."&year=".$year."&month=".$month."&branch_id=".$branch_id."&basis=".$basis."&format=excel' target=blank()><b>Export Excel</b></a>";

	
	//INCOME FOR INCOME STATEMENT
		$level2_res= mysql_query("select * from accounts where account_no >40 and account_no <=49 ".$branch_." order by account_no");
		$income = 0;
		$i=0;
		
		$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <p><h4 class="text-primary mt0">'.$branch['branch_name'].'</h4></p>
                                <p><h5 class="text-primary mt0">PMT INCOME STATEMENT FOR THE PERIOD FROM '.$from_date.' TO '.$to_date.'</h5></p>
                                <p>'.$basis.'</p>
                            </div>';
 		//$content .= '<table class="table table-striped table-bordered" id="table-tools">';
		$content .= "<table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>";
	
	//INCOME FOR INCOME STATEMENT
		$level2_res= mysql_query("select * from accounts where account_no >40 and account_no <=49 ".$branch_." order by account_no");
		$income = 0;
		$i=0;
		$content .= "<tr class=headings><td><font size=3pt><b>Ref</b></font></td><td><font size=3pt><b>Caption</b></font></td><td><font size=3pt><b>Last Financial Year</b></font></td>";
		if($report_freq == 'monthly')  {
			for($x=1; $x<=12; $x++)
				$content .= "<td width=100><font size=3pt><b>Month ".$x."</b></font></td>";
		}elseif($report_freq == 'quarterly'){
			for($x=1; $x<=4; $x++)
				$content .= "<td width=100><font size=3pt><b>Q".$x."</b></font></td>";
		}
		$content .="<td><font size=3pt><b>YTD</b></font></td></tr>";
		
		
		#income
		$pmt->addHeading("Income");
		#Y01
		$pmt->setQuery("select sum(int_amt) as amount from payment where date >= 'to_date' and date <= 'from_date'","Y01","Interest Income from Loans","");
		#YO2
		$pmt->setQuery("select sum(i.amount) as amount from other_income i inner join accounts a on(i.account_id=a.id) where a.account_no like '411%' and a.name not like '%interest%loans%' and i.date >= 'to_date' and i.date <= 'from_date'","Y02","Fee Income From loans","");
		#Y03
		$pmt->setQuery("select sum(i.amount) as amount from other_income i inner join accounts a on(i.account_id=a.id) where i.date >= 'to_date' and i.date <= 'from_date' and substr(a.account_no,1,3) between 412 and 419","Y03","Income from investments","");
		#Y04
		$pmt->setQuery("select sum(amount) as amount from monthly_charge where date >= 'to_date' and date <= 'from_date'","Y04","Other operating income","");
		$pmt->setQuery("select sum(percent_value) as amount from withdrawal where date >= 'to_date' and date <= 'from_date'","Y04","Other operating income","");
		$pmt->setQuery("select sum(flat_value) as amount from deposit where date >= 'to_date' and date <= 'from_date'","Y04","Other operating income","");
		#Y05  //FORMULAR FIELD
		$pmt->addFormularField("Y05","TOTAL operating income","Y01+Y02+Y03+Y04");
		#heading financing Expenses
		$pmt->addHeading("Financing Expenses");
		#Y06a
		$pmt->setQuery("select sum(interest) from payable_paid where payable_id in (select id from payable where lower(rate) like '%market%') and date >= 'to_date' and date <= 'from_date'","Y06a","Interest and fees paid on market debt ","");
		#Y06b
		$pmt->setQuery("select sum(interest) from payable_paid where payable_id in (select id from payable where lower(rate) like '%subsidised%') and date >= 'to_date' and date <= 'from_date'","Y06b","Interest and fees paid on subsidised debt ","");
		#Y07a
		
		#Y07b
		
		#Y08
		
		#Y09

		#Y10 //formular
		$pmt->addFormularField("Y10","TOTAL financing expenses","Y06a+Y06b"); //*********incomplete formular *********************
		#Y11
		$pmt->addFormularField("Y11","GROSS FINANCIAL MARGIN","Y05-Y10");
		
		#Operating Expenses
		$pmt->addHeading("Operating Expenses");
		#y14a
		$pmt->setQuery("select sum(amount) as amount from expense where account_id in (select id from accounts where account_no like '531%') and date >= 'to_date' and date <= 'from_date'","Y14a","Personal expenses","");
		#y14b
		$pmt->setQuery("select sum(amount) as amount from expense where account_id in (select id from accounts where account_no like '532%') and date >= 'to_date' and date <= 'from_date'","Y14b","Governance costs","");
		#y14c
		$pmt->setQuery("select sum(amount) as amount from expense where account_id in (select id from accounts where name like '%rent%' or name like '%utility%' or name like '%utilities%' or name like '%electricity%' or name like '%water%') and date >= 'to_date' and date <= 'from_date'","Y14c","Rent and utilities","");
		#y14d
		$pmt->setQuery("select sum(amount) as amount from expense where account_id in (select id from accounts where name like '%travel%' or name like '%transport%' and account_no like '535%') and date >= 'to_date' and date <= 'from_date'","Y14d","Travel and transport","");
		#y14e
		$pmt->setQuery("select sum(amount) as amount from expense where account_id in (select id from accounts where name like '%stationery%' or name like '%stationary%' and account_no like '535%') and date >= 'to_date' and date <= 'from_date'","Y14e","Stationery and office supplies","");
		#y15
		$pmt->setQuery("select sum(amount) as amount from expense where account_id  in (select id from accounts where name not like '%stationery%' or name not like '%stationary%' or name not like '%travel%' or name not like '%transport%' or name not like '%rent%' or name not like '%utility%' or name not like '%utilities%' or name not like '%electricity%' or name not like '%water%' and account_no like '535%') and date >= 'to_date' and date <= 'from_date'","Y15","Other administrative expenses","");
		#y16
		$pmt->setQuery("select sum(amount) as amount from deppreciation where date >= 'to_date' and date <= 'from_date'","Y16","Depreciation","");
		#y17
		$pmt->setQuery("select sum(amount) as amount from expense where account_id in (select id from accounts where account_no not like '531%' and account_no not like '532%' and account_no not like '534%' and account_no not like '551%') and date >= 'to_date' and date <= 'from_date'","Y17","Other operating costs","");
		#Y18  //FORMULAR FIELD
		$pmt->addFormularField("Y18","TOTAL operating expenses","Y14a+Y14b+Y14c+Y14d+Y14e+Y15+Y16+Y17");
		#print report
		$content.=$pmt->printToHTML("all");
		
		
	$content .= "</table>";
	//}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function pmt_balance_sheet($date,$basis, $report_freq){
 list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$pmt = new pmt_balancesheet("Balance Sheet",$basis,$year,$month,$report_freq);
	//$pmt->pmt_statement("Balance Sheet",$basis,$year,$month,$report_freq);
	//$branch_=($branch_id=='all'||$branch_id=='')?NULL:"and branch_id=".$branch_id;
	$branch_ = NULL;
	if($year == ''){
		$year = date('Y');
		$month = date('m');
		$mday = date('d');
	}
	$resp->assign("status", "innerHTML", "");
	$to_date = $calc->endofMonthBySpan(0, $month, $year, '%Y-%m-%d 23:59:59');
	$from_date = sprintf("%d-%02d-01 00:00:00", $from_year, $from_month);
	$last_from_date = sprintf("%d-%02d-01 00:00:00", $from_year-1, $from_month);
	if($from_year ==''){
		$from_date = sprintf("%d-01-01 00:00:00", date('Y')-1);
		$from_year = date('Y', strtotime($from_date));
		$from_month = date('m', strtotime($from_date));
		$from_mday = date('d', strtotime($from_date));
	}
	
	
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	
	
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR PMT BALANCE SHEET</h3>
                                <p class="text-default nm"></p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Date Range:</label>
                                          <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="Last Financial Year" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>     
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Basis:</label>
                                          <select id="basis" class="form-control"><option value="Cash Basis">Cash Basis<option value="Accrual Basis">Accrual Basis</select>        
                                        </div>
                                        
                                    </div>
                                </div>  
                                
                       <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Report Frequency:</label>
                                           <select name="report_freq" id="report_freq" class="form-control"><option value="monthly">Monthly<option value="quarterly">Quarterly</option></select>        
                                        </div>
                                                                            
                                    </div>
                                </div>';                                 
                                   	
		 $content .= "<div class='panel-footer'>                              
                               
                                <button type='button' class='btn  btn-primary' onclick=\"xajax_pmt_balance_sheet(getElementById('from_date').value,getElementById('to_date').value, getElementById('basis').value,getElementById('report_freq').value);\">Show Report</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                     $resp->call("createDate","from_date");
	$resp->call("createDate","to_date");
	
	$content .= $pmt->setInc($year, $month);
	
	$content .="<a href='export/pmt_balance_sheet?from_year=".$from_year."&from_month=".$from_month."&year=".$year."&month=".$month."&branch_id=".$branch_id."&basis=".$basis."' target=blank()><b>Printable Version</b></a> | <a href='export/pmt_balance_sheet?from_year=".$from_year."&from_month=".$from_month."&year=".$year."&month=".$month."&branch_id=".$branch_id."&basis=".$basis."&format=excel' target=blank()><b>Export Excel</b></a> <a href='javascript:;' onclick=\"xajax_pmtExport();\">";
	
	$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">'.$branch['branch_name'].'</h3>
                                <p>PMT BALANCE SHEET AS '.$to_date.'</p>
                                <p>'.$basis.'</p>
                            </div>';
 			
	
		$content .= "<table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>";
	
	
		$content .= "<tr class=headings><td><font size=3pt><b>Ref</b></font></td><td><font size=3pt><b>Caption</b></font></td><td><font size=3pt><b>Last Financial Year</b></font></td>";
		if($report_freq == 'monthly')  {
			for($x=1; $x<=12; $x++)
				$content .= "<td width=100><font size=3pt><b>Month ".$x."</b></font></td>";
		}elseif($report_freq == 'quarterly'){
			for($x=1; $x<=4; $x++)
				$content .= "<td width=100><font size=3pt><b>Q".$x."</b></font></td>";
		}
		$content .="<td><font size=3pt><b>YTD</b></font></td></tr>";
		
		$pmt->addHeading("Assets");
		#CASH (1211%)
		/*//deposit
		$pmt->setQuery("select sum(amount) as amount from deposit where bank_account in(select id from bank_account where account_id in(select id from accounts where account_no like '1211%')) and date <='to_date'","B01","Cash","");
		//withdrawal
		$pmt->setQuery("select -sum(amount) as amount from withdrawal where bank_account in(select id from bank_account where account_id in(select id from accounts where account_no like '1211%')) and date <='to_date'","B01","Cash","");
		//other income
		$pmt->setQuery("select sum(amount) as amount from other_income where bank_account in(select id from bank_account where account_id in(select id from accounts where account_no like '1211%')) and date <='to_date'","B01","Cash","");
		//loans payable
		//$pmt->setQuery("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '212%' and p.bank_account in(select id from bank_account where account_id in(select id from accounts where account_no like '1211%')) and p.date <= 'to_date''","B01","Cash","");*/
		//deposit
		$pmt->setQuery("select sum(amount) as amount from deposit where bank_account in(select id from bank_account where account_id in(select id from accounts where account_no like '1211%')) and date <='to_date'","B01","Cash","");
		
		//withdrawal
		$pmt->setQuery("select -sum(amount) as amount from withdrawal where bank_account in(select id from bank_account where account_id in(select id from accounts where account_no like '1211%')) and date <='to_date'","B01","Cash","");
		//other income
		$pmt->setQuery("select sum(amount) as amount from other_income where bank_account in(select id from bank_account where account_id in(select id from accounts where account_no like '1211%')) and date <='to_date'","B01","Cash","");
		
		//LOANS PAYABLE
		$pmt->setQuery("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '212%' and p.bank_account in (select id from bank_account where account_id in(select id from accounts where account_no like '1211%'))  and p.date <= 'to_date'","B01","Cash","");
									
		//EXPENSES
		$pmt->setQuery("select -sum(amount) as amount from expense where bank_account in (select id from bank_account where account_id in(select id from accounts where account_no like '1211%')) and date <='to_date'","B01","Cash","");
									
		//PAYABLE PAID
		$pmt->setQuery("select -sum(amount) as amount from payable_paid where bank_account in (select id from bank_account where account_id in(select id from accounts where account_no like '1211%')) and date <='to_date'","B01","Cash","");
		
		//RECEIVABLE COLLECTED
		$pmt->setQuery("select sum(amount) as amount from collected where bank_account in (select id from bank_account where account_id in(select id from accounts where account_no like '1211%')) and date <='to_date'","B01","Cash","");
		
		//DISBURSED LOANS
		$pmt->setQuery("select -sum(amount) as amount from disbursed where bank_account in (select id from bank_account where account_id in(select id from accounts where account_no like '1211%')) and date <='to_date'","B01","Cash","");
									
		//PAYMENTS
		$pmt->setQuery("select sum(p.princ_amt + p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id  where p.date <= '".$date."' and p.bank_account in (select id from bank_account where account_id in(select id from accounts where account_no like '1211%')) and p.date <='to_date'","B01","Cash","");
									
		//PENALTIES
		$pmt->setQuery("select sum(p.amount) as amount from penalty p join disbursed d on p.loan_id=d.id where p.bank_account in (select id from bank_account where account_id in(select id from accounts where account_no like '1211%')) and p.date <='to_date'","B01","Cash","");
												
		//SHARES
		$pmt->setQuery("select sum(value) as amount from shares where  bank_account in (select id from bank_account where account_id in(select id from accounts where account_no like '1211%')) and date <='to_date'","B01","Cash","");
									
		//RECOVERED
		$pmt->setQuery("select sum(r.amount) as amount from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.bank_account in (select id from bank_account where account_id in(select id from accounts where account_no like '1211%')) and r.date <='to_date'","B01","Cash","");
								
		//INVESTMENTS 
		$pmt->setQuery("select -sum(quantity * amount) as amount from investments where bank_account in (select id from bank_account where account_id in(select id from accounts where account_no like '1211%')) and date <='to_date'","B01","Cash","");
									
		//DIVIDENDS PAID
		$pmt->setQuery("select -sum(total_amount) as amount from share_dividends where bank_account in (select id from bank_account where account_id in(select id from accounts where account_no like '1211%')) and date <='to_date'","B01","Cash","");
																
		//SOLD INVESTMENTS
		$pmt->setQuery("select sum(quantity * amount) as amount from sold_invest where bank_account  in (select id from bank_account where account_id in(select id from accounts where account_no like '1211%')) and date <='to_date'","B01","Cash","");							

		//FIXED ASSETS 
		$pmt->setQuery("select -sum(initial_value) as amount from fixed_asset where bank_account  in (select id from bank_account where account_id in(select id from accounts where account_no like '1211%')) and date <='to_date'","B01","Cash","");
									
		$pmt->setQuery("select sum(amount) as amount from sold_asset where bank_account in (select id from bank_account where account_id in(select id from accounts where account_no like '1211%')) and date <='to_date'","B01","Cash","");
															
		//CASH IMPORTED
		$pmt->setQuery("select sum(amount) as amount from cash_transfer where dest_id in (select id from bank_account where account_id in(select id from accounts where account_no like '1211%')) and source_id in (select id from bank_account where account_id in(select id from accounts where account_no like '1212%')) and date <='to_date'","B01","Cash","");
								
		//CASH EXPORTED
		$pmt->setQuery("select -sum(amount) as amount from cash_transfer where source_id in (select id from bank_account where account_id in(select id from accounts where account_no like '1211%')) and dest_id in (select id from bank_account where account_id in(select id from accounts where account_no like '1212%')) and date <='to_date'","B01","Cash","");

		//CAPITAL FUNDS
		$pmt->setQuery("select sum(amount) as amount from other_funds where bank_account in (select id from bank_account where account_id in(select id from accounts where account_no like '1211%')) and date <='to_date'","B01","Cash","");
			
		#B02
		#BANK ACCOUNTS (1212%)
		$pmt->setQuery("select sum(amount) as amount from deposit where bank_account in(select id from bank_account where account_id in(select id from accounts where account_no like '1212%')) and date <='to_date'","B02","Deposits in financial institutions","");
		
		
		#short-term invetsments B03 subtract sold_invest
		$pmt->setQuery("select sum(amount) as amount from investments where account_id in(select id from accounts where account_no like '113%') and date <='to_date'","B03","Short-term investments","");
		$pmt->setQuery("select -sum(amount) as amount from sold_invest where account_id in(select id from accounts where account_no like '113%') and date <='to_date'","B03","Short-term investments","");
		
		#B04
		$pmt->setQuery("select sum(amount) as amount from disbursed where date <='to_date'","B04","Gross loan portfolio","");
		$pmt->setQuery("select -sum(princ_amt) as amount from payment where date <='to_date'","B04","Gross loan portfolio","");
		#B05
		$pmt->setQuery("select sum(amount) as amount from otherloan_loss where date <='to_date'","B05","(Loan loss reserve)","");
		#B06 formular fiels
		$pmt->addFormularField("B06","Net loan portfolio","B04-B05");
		#B07 other_short term investment  sub sold_investments
		/***** Dummy ATM ***********/
		$pmt->setQuery("select sum(amount) as amount from investments where account_id in(select id from accounts where account_no like '113%' and name like 'this account is a dummy') and date <='to_date'","B07","Other short-term assets","");
		$pmt->setQuery("select -sum(amount) as amount from sold_invest where account_id in(select id from accounts where account_no like '113%' and name like 'this account is a dummy') and date <='to_date'","B07","Other short-term assets","");
		
		#B08 Fornular field
		$pmt->addFormularField("B08","TOTAL current assets","B01+B02+B03+B06+B07");
		#B09 investiment |long term sub sold_invest|
		$pmt->setQuery("select sum(amount) as amount from investments where account_id in(select id from accounts where account_no like '114%') and date <='to_date'","B09","Long-term investments","");
		$pmt->setQuery("select -sum(amount) as amount from sold_invest where account_id in(select id from accounts where account_no like '114%') and date <='to_date'","B09","Long-term investments","");
		#B10 fixed assets
		$pmt->setQuery("select sum(initial_value) as amount from fixed_asset where date <='to_date'","B10","Fixed assets","");
		#B11 (Accumulated depreciation)
		$pmt->setQuery("select -sum(amount) as amount from deppreciation where date <='to_date'","B11","(Accumulated depreciation)","");
		#B12
		$pmt->addFormularField("B12","Net fixed assets","B10+B11");
		#B13 other long term investments "sub sold_invest"
		 $pmt->setQuery("select sum(amount) as amount from investments where account_id in(select id from accounts where account_no not like '114%' and account_no not like '113%') and date <='to_date'","B13","Other long-term assets","");
		 $pmt->setQuery("select -sum(amount) as amount from sold_invest where account_id in(select id from accounts where account_no not like '114%' and account_no not like '113%') and date <='to_date'","B13","Other long-term assets","");
		 #B14 formular field
		 $pmt->addFormularField("B14","TOTAL  non-current assets","B09+B12+B13");
		 #B15 formular field B08+B14
		 $pmt->addFormularField("B15","TOTAL ASSETS","B08+B14");
		 /*
		  *Liabilities
		  */
		 $pmt->addHeading("Liabilities");
		 #B16 compulsory savings 
		 $pmt->setQuery("select sum(no_of_accounts*min_bal) as amount from mem_accounts_product_view where open_date <='to_date'","B16","Compulsory savings","");
		 #B17 voluntary savings // product type = free
		 //deposits
		 $pmt->setQuery("select sum(amount - flat_value - percent_value) as amount from deposit where date <= 'to_date' and memaccount_id in (select id from mem_accounts where saveproduct_id in (select id from savings_product where lower(type)='free'))","B17","Voluntary savings","");
		 // -withdrawal
		 $pmt->setQuery("select -sum(amount + flat_value + percent_value) as amount from withdrawal where date <= 'to_date'","B17","Voluntary savings","");
		 // -monthly charges
		  $pmt->setQuery("select -sum(amount) as amount from monthly_charge where date <= 'to_date'","B17","Voluntary savings","");
		  // +interets awarded
		   $pmt->setQuery("select sum(amount) as amount from save_interest where date <= 'to_date'","B17","Voluntary savings","");
		  // - loan repayment //mode not cash or cheque
		   $pmt->setQuery("select -sum(princ_amt + int_amt+penalty_amt) as amount from payment where date <= 'to_date' and lower(mode) not like '%cash%' and lower(mode) not like '%cheque%'","B17","Voluntary savings",""); 
		  // - other_income //mode not cash or cheque
		   $pmt->setQuery("select -sum(amount) as amount from other_income  where date <= 'to_date' and lower(mode) not like '%cash%' and lower(mode) not like '%cheque%'","B17","Voluntary savings","");
		  // - shares //mode not cash or cheque
		   $pmt->setQuery("select sum(value) as amount from shares  where date <= 'to_date' and lower(mode) not like '%cash%' and lower(mode) not like '%cheque%'","B17","Voluntary savings","");
		   // - minmum balance compulsory
		    $pmt->setQuery("select -sum(no_of_accounts*min_bal) as amount from mem_accounts_product_view where open_date <='to_date'","B17","Voluntary savings","");
		    #B18 grace period > 0
		    $pmt->setQuery("select sum(amount - flat_value - percent_value) as amount from deposit where date <='to_date' and memaccount_id in(select id from mem_accounts where saveproduct_id in(select id from savings_product where grace_period<=12))","B18","Time Deposits <= 1 Year ","");
		    
		    #B19 Formular field
		    $pmt->addFormularField("B19","TOTAL short-term deposits","B16+B17+B18");
		    #B20 
		    $pmt->setQuery("select sum(amount) as amount from payable where lower(vendor) not like '%central%' and lower(rate) like '%market%' and account_id in(select id from accounts where account_no like '2121%')","B20","Short-term debt (market rate) ","");
		    // - payable_paid
		    $pmt->setQuery("select -sum(amount) as amount from payable_paid where payable_id in(select id from payable where lower(vendor) not like '%central%' and lower(rate) like '%market%' and date <='to_date' and account_id in(select id from accounts where account_no like '2121%')) and date <='to_date'","B20","Short-term debt (market rate)","");
		    
		    #B21
		    $pmt->setQuery("select sum(amount) as amount from payable where lower(vendor) not like '%central%' and lower(rate) like '%subsidised%' and account_id in(select id from accounts where account_no like '2121%')","B21","Short-term debt (subsidised rate)","");
		    // - payable_paid
		    $pmt->setQuery("select -sum(amount) as amount from payable_paid where payable_id in(select id from payable where lower(vendor) not like '%central%' and lower(rate) like '%subsidised%' and date <='to_date' and account_id in(select id from accounts where account_no like '2121%')) and date <='to_date'","B21","Short-term debt (subsidised rate)","");
		    
		    #B22
		    $pmt->setQuery("select sum(amount) as amount from payable where lower(vendor) like '%central%' and account_id in(select id from accounts where account_no like '2121%')","B22","Loans from the central bank","");
		    // - payable_paid
		    $pmt->setQuery("select -sum(amount) as amount from payable_paid where payable_id in(select id from payable where lower(vendor) like '%central%' and date <='to_date' and account_id in(select id from accounts where account_no like '2121%')) and date <='to_date'","B22","Loans from the central bank","");
		    #B23
		    $pmt->setQuery("select sum(amount) as amount from payable where bank_account=0  and date <='to_date'","B23","Other current liabilities","");
		    // - payable_paid
		    $pmt->setQuery("select -sum(amount) as amount from payable_paid where payable_id in(select id from payable where bank_account=0 and date <='to_date') and date <='to_date'","B23","Other current liabilities","");
		    #B24
		    $pmt->addFormularField("B24","TOTAL current liabilities","B19+B20+B21+B22+B23");
		    
		    #B25 time deposit grace_period > 12
		    $pmt->setQuery("select sum(amount - flat_value - percent_value) as amount from deposit where date <='to_date' and memaccount_id in(select id from mem_accounts where saveproduct_id in(select id from savings_product where grace_period > 12))","B25","Time deposits > 1 Year","");
		    #B26 Long-term debt (market rate)
		    $pmt->setQuery("select sum(amount) as amount from payable where lower(vendor) not like '%central%' and lower(rate) like '%market%' and account_id in(select id from accounts where account_no like '2122%')","B26","Long-term debt (market rate) ","");
		    // - payable_paid
		    $pmt->setQuery("select -sum(amount) as amount from payable_paid where payable_id in(select id from payable where lower(vendor) not like '%central%' and lower(rate) like '%market%' and date <='to_date' and account_id in(select id from accounts where account_no like '2121%')) and date <='to_date'","B26","Long-term debt (market rate)","");
		    
		    #B27
		    $pmt->setQuery("select sum(amount) as amount from payable where lower(vendor) not like '%central%' and lower(rate) like '%subsidised%' and account_id in(select id from accounts where account_no like '2122%')","B27","Short-term debt (subsidised rate)","");
		    // - payable_paid
		    $pmt->setQuery("select -sum(amount) as amount from payable_paid where payable_id in(select id from payable where lower(vendor) not like '%central%' and lower(rate) like '%subsidised%' and date <='to_date' and account_id in(select id from accounts where account_no like '2122%')) and date <='to_date'","B27","Long-term debt (subsidised rate)","");
		    
		    #B28 Deferred income or restricted funds
		    $pmt->setQuery("select sum(amount) as amount from payable where account_id in(select id from accounts where lower(name) like '%deferred%') and date <='to_date'", "B28", "Deferred income or restricted funds", "");
		    $pmt->setQuery("select -sum(amount) as amount from payable_paid where payable_id in(select id from payable where account_id in(select id from accounts where lower(name) like '%deferred%') and date <='to_date') and date<='to_date'","B28","Deferred income or restricted funds","");
		    
		    #B29 Leave as a dummy ATM (Other long-term liabilities)
		    $pmt->setQuery("select sum(amount) as amount from payable where account_id in (select id from accounts where name='dummy_account___') and date<='to_date'","B29","Other long-term liabilities","");
		    
		    #B30 formular field
		    $pmt->addFormularField("B30","TOTAL non-current liabilities","B25+B26+B28+B29");
		    #B31 formular field total liabilities
		    $pmt->addFormularField("B31","TOTAL LIABILITIES","B24+B25");
		    /*
		     *Equity/capital
		     */
		     $pmt->addHeading("Equity");
		     
		     #B32 members' shares
		     $pmt->setQuery("select sum(value) as amount from shares where date <='to_date'","B32","Capital from shareholders or member shares","");
		     
		     #B33 Donated equity
		     $pmt->setQuery("select sum(amount) as amount from other_funds where lower(mode) = 'cash' and date <='to_date'","B33","Donated equity","");
		     
		     #B34 Reserves
		     $pmt->setQuery("select sum(amount) as amount from other_funds where lower(mode) = 'reserve' and account_id in(select id from accounts where lower(name) not like '%retained%') and date <='to_date'","B34","Reserves","");
		     
		     
		      #B36 Retained surplus/(deficit) prior years
		     $pmt->getSurplus();
		     
		     #B35 Retained surplus/(deficit) current year
		     //$pmt->setQuery("select sum(amount) as amount from other_funds where account_id in (select id from accounts where account_no not like '3111' and account_no not like '3312') and date <= 'to_date' and id not in (select fund_id from payable where date <= 'to_date' and fund_id<>'0')","B35","Retained surplus/(deficit) current year","");
		     $pmt->setQuery("select sum(amount) as amount from other_funds where account_id in (select id from accounts where lower(name) like '%current%') and date <= 'to_date'","B36","Retained surplus/(deficit) prior years","");
		    
		    #B37 Retained surplus/(deficit)
		     $pmt->addFormularField("B37","Retained surplus/(deficit)","B35+B36");
		     
		     #B38 Leave as a dummy ATM
		     $pmt->setQuery("select sum(amount) as amount from other_funds where account_id in (select id from accounts where lower(name) = 'this is a dummy account') and date <= 'to_date'","B38","Other capital accounts","");
		     
		     #B39 Formular field
		     $pmt->addFormularField("B39","TOTAL EQUITY","B32+B33+B34+B37+B38");
		     
		     #B40 Formular Field
		     $pmt->addFormularField("B40","TOTAL LIABILITIES AND EQUITY","B31+B39");
		     
		#print report
		$content.=$pmt->printToHTML("all");
		$pmt->printToXMLBalanceSheet();
		
	$content .= "</table>";
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}
//PORTFOLIO ACTIVITY
function pmt_portfolio_activity($from_date, $date, $basis, $report_freq){
list($from_year,$from_month,$from_mday) = explode('-', $from_date);
list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$pmt = new pmt_portfolio("Portfolio Activity",$basis,$year,$report_freq,$from_year,$from_month);
	//$branch_=($branch_id=='all'||$branch_id=='')?NULL:"and branch_id=".$branch_id;
	$branch_ = NULL;
	if($year == ''){
		$year = date('Y');
		$month = date('m');
		$mday = date('d');
	}
	$resp->assign("status", "innerHTML", "");
	$to_date = $calc->endofMonthBySpan(0, $month, $year, '%Y-%m-%d 23:59:59');
	$from_date = sprintf("%d-%02d-01 00:00:00", $from_year, $from_month);
	$last_from_date = sprintf("%d-%02d-01 00:00:00", $from_year-1, $from_month);
	if($from_year ==''){
		$from_date = sprintf("%d-01-01 00:00:00", date('Y')-1);
		$from_year = date('Y', strtotime($from_date));
		$from_month = date('m', strtotime($from_date));
		$from_mday = date('d', strtotime($from_date));
	}
	$days = $calc->dateDiff($from_mday, $from_month, $from_year, $mday, $month, $year);
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR PMT PORTFOLIO ACTIVITY</h3>
                                <p class="text-default nm"></p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Date Range:</label>
                                          <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="Last Financial Year" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>     
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Basis:</label>
                                          <select id="basis" class="form-control"><option value="Cash Basis">Cash Basis<option value="Accrual Basis">Accrual Basis</select>        
                                        </div>
                                        
                                    </div>
                                </div>  
                                
                       <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Report Frequency:</label>
                                           <select name="report_freq" id="report_freq" class="form-control"><option value="monthly">Monthly<option value="quarterly">Quarterly</option></select>        
                                        </div>
                                                                            
                                    </div>
                                </div>';                                 
                                   	
		 $content .= "<div class='panel-footer'>                              
                               
                                <button type='button' class='btn  btn-primary' onclick=\"xajax_pmt_income_statement(getElementById('from_date').value,getElementById('to_date').value, getElementById('basis').value,getElementById('report_freq').value);\">Show Report</button>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                     $resp->call("createDate","from_date");
	$resp->call("createDate","to_date");
	
	$content .= $pmt->setDates($from_year, $from_month);
	
	$content .= "<a href='export/pmt_income_statement?from_year=".$from_year."&from_month=".$from_month."&year=".$year."&month=".$month."&branch_id=".$branch_id."&basis=".$basis."' target=blank()><b>Printable Version</b></a> | <a href='export/pmt_income_statement?from_year=".$from_year."&from_month=".$from_month."&year=".$year."&month=".$month."&branch_id=".$branch_id."&basis=".$basis."&format=excel' target=blank()><b>Export Excel</b></a>";
	$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <p><h4 class="text-primary mt0">'.$branch['branch_name'].'</h4></p>
                                <p class="text-primary mt0">PMT PORTFOLIO ACTIVITY FOR THE PERIOD FROM '.$from_date.' TO '.$to_date.'</p>
                                <p>'.$basis.'</p>
                            </div>';
 		//$content .= '<table class="table table-striped table-bordered" id="table-tools">';
	
	$content .= "
	<table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>";
	
	$content .= "<tr class=headings><td><font size=3pt><b>Ref</b></font></td><td><font size=3pt><b>Caption</b></font></td><td><font size=3pt><b>Last Financial Year</b></font></td>";
		if($report_freq == 'monthly')  {
			for($x=1; $x<=12; $x++)
				$content .= "<td width=100><font size=3pt><b>Month ".$x."</b></font></td>";
		}elseif($report_freq == 'quarterly'){
			for($x=1; $x<=4; $x++)
				$content .= "<td width=100><font size=3pt><b>Q".$x."</b></font></td>";
		}
		$content .="<td><font size=3pt><b>YTD</b></font></td></tr>";
	
	$pmt->addHeading("Activity");
	#A01 Value of loans disbursed (during period)
	$pmt->setQuery("select sum(amount) as amount from disbursed where date >= 'to_date' and date <= 'from_date'","A01","Value of loans disbursed (during period)","");
	#A02 No of loans disbursed in a period.
	$pmt->setQuery("select count(*) as amount from disbursed where date >= 'to_date' and date <= 'from_date'","A02","Number of loans disbursed (during period)","",false);
	#A03 Number of clients taking 1st loan (during period)
	$pmt->setQuery("select count(*) as amount from disbursed where date >= 'to_date' and date <= 'from_date' and cycle=1","A03","Number of clients taking 1st loan (during period)","",false);
	#A04
	$pmt->setQuery("select count(*) as amount from disbursed where balance=0 and id in(select loan_id from payment where date >= 'to_date' and date <= 'from_date')","A04","Number of loans fully repaid (during the period)","");
	
	#A05 Value of loan payments received (during the period)
	$pmt->setQuery("select sum(princ_amt) as amount from payment where date >= 'to_date' and date <= 'from_date'  and loan_id in(select id from disbursed  where balance=0)","A05","Value of loan payments received (during the period)","");
	#A06 Number of clients receiving loans as individuals, excluding group loans (end of period)
	$pmt->setQuery("select count(id) as amount from member where id in(select mem_id from loan_applic where group_id =0 and id in (select applic_id from disbursed where date <='to_date'))","A06","Number of clients receiving loans as individuals, excluding group loans (end of period)","");
	#A07 Number of outstanding loans (end of period)
	$pmt->setQuery("select count(id) from disbursed where date<='to_date'","A07","Number of outstanding loans (end of period)","");
	$pmt->setQuery("select -count(id) as no from disbursed where balance=0 and id in (select loan_id from payment where date <='to_date') and id not in (select loan_id from payment where date >'to_date')","A07","Number of outstanding loans (end of period)","");
	#A08
	$pmt->setQuery("select sum(amount) as amount from disbursed where date <='to_date'","A08","Value of outstanding loans (end of period)","");
	$pmt->setQuery("select -sum(princ_amt) as amount from payment where date <='to_date'","A08","Value of outstanding loans (end of period)","");
	
	$pmt->addHeading("Outreach (counting individuals as well as members of groups)");
	
	#A09 Total number of active borrowers, counting individuals as well as members of groups (end of period)
	$pmt->setQuery("select count(distinc mem_id) from loan_applic where id in (select applic_id from disbursed where date<='to_date') and id not in (select a.id as applic_id from disbursed d join loan_applic a on d.applic_id=a.id where d.balance=0 and d.id in (select loan_id from payment where date <='to_date') and d.id not in (select loan_id from payment where date >'to_date')","A09","Total number of active borrowers, counting individuals as well as members of groups (end of period)","",false);
	#A10a loans bellow poverty line 
	$pmt->setQuery("select count(id) as amount from disbursed where amount <= (select value from pmt_settings where lower(field) like '%poverty%') and date <='to_date'", "A10a","Number of borrowers below poverty line, counting individuals as well as members of groups (end of period)","");
	
	#A10b Number of loans below poverty line (during period)
	$pmt->setQuery("select count(id) as amount from disbursed where amount <= (select value from pmt_settings where lower(field) like '%poverty%') and date <='to_date' and date >='from_date'", "A10b","Number of loans below poverty line (during period)","");
	#A11a Number of borrowers in the agriculture sector, counting individuals as well as members of groups (end of period)
	//$pmt->setQuery("select count(distinct mem_id) as amount from loan_applic where id in (select applic_id from disbursed where date <= 'to_date' and bank_account in(select id from bank_account where account_id in (select id from accounts where lower(name) like '% agric%')))","A11a","Number of borrowers in the agriculture sector, counting individuals as well as members of groups (end of period)","",false);
	$pmt->setQuery("select count(distinct l.mem_id) as amount from loan_applic l join disbursed d on(l.id=d.applic_id) join bank_account b on(d.bank_account=b.id) join accounts a on(b.account_id=a.id) where a.name like '%agric%' and d.date <='to_date'","A11a","Number of borrowers in the agriculture sector, counting individuals as well as members of groups (end of period)","");
	#A11b Number of outstanding loans in the agriculture sector (end of period)
	$pmt->setQuery("select count(d.id) as amount from disbursed d join bank_account b on (d.bank_account=b.id) join accounts a on (b.account_id=a.id) where  d.balance > 0 and date <= 'to_date' and a.name like '%agric%'","A11b","Number of outstanding loans in the agriculture sector (end of period)","");
	
	#A11c Value of outstanding loans in the agriculture sector (end of period)
	$pmt->setQuery("select sum(d.amount) as amount from disbursed d join bank_account b on (d.bank_account=b.id) join accounts a on (b.account_id=a.id) where  d.balance > 0 and date <= 'to_date' and a.name like '%agric%'","A11c","Value of outstanding loans in the agriculture sector (end of period)","");
	
	#12 female borrowers
	#12a Number of female borrowers, counting individuals as well as members of groups (end or period)
	$pmt->setQuery("select count(distinct l.mem_id) as amount from loan_applic l join disbursed d on(l.id=d.applic_id) join member m on(l.mem_id = m.id) where lower(m.sex) = 'f' and d.date <='to_date'","A12a","Number of female borrowers, counting individuals as well as members of groups (end or period)","");
	#12b Number of outstanding loans to female borrowers (end or period)
	$pmt->setQuery("select count(d.id) as amount from disbursed d join loan_applic l  on(l.id=d.applic_id) join member m on(l.mem_id = m.id) where lower(m.sex) = 'f' and d.date <='to_date' and d.balance > 0","A12b","Number of outstanding loans to female borrowers (end or period)","");
	#12c Value of outstanding loans to female borrowers (end of period)
	$pmt->setQuery("select sum(d.amount) as amount from disbursed d join loan_applic l  on(l.id=d.applic_id) join member m on(l.mem_id = m.id) where lower(m.sex) = 'f' and d.date <='to_date' and d.balance > 0","A12c","Value of outstanding loans to female borrowers (end of period)","");
	
	#13a Number of borrowers in rural branches, counting individuals as well as members of groups (end of period)
	$pmt->setQuery("select value as amount from pmt_settings where lower(field) like '%rural%'","A13a","Number of borrowers in rural branches, counting individuals as well as members of groups (end of period)","");
	#13b Number of borrowers in rural branches, counting individuals as well as members of groups (end of period)
	$pmt->setQuery("select value as amount from pmt_settings where lower(field) like '%rural%'","A13b","Number of outstanding loans in rural branches (end of period)","");
	#13c Number of borrowers in rural branches, counting individuals as well as members of groups (end of period)
	$pmt->setQuery("select value as amount from pmt_settings where lower(field) like '%rural%'","A13c","Value of outstanding loans in rural branches (end of period)","");
	
	$pmt->addHeading("Savings (counting individuals as well as members of groups)");
	#A14
	$pmt->setQuery("select count(m.id) as amount from mem_accounts m join savings_product s on(m.saveproduct_id=s.id) join accounts a on (s.account_id=a.id) where lower(a.name) like '%savings%' and m.open_date <='to_date'","A14","Number of savings accounts (end of period)","");
	#A15 Total number of savers, counting individuals as well as members of groups (end of period)
	$pmt->setQuery("select count(id) as amount from mem_accounts where open_date <='to_date'","A15","Total number of savers, counting individuals as well as members of groups (end of period)","");
	#A16a Number of clients with compulsory savings, counting individuals as well as members of groups (end of period)
	$pmt->setQuery("select count(distinct m.mem_id) as amount from mem_accounts m join deposit d on(d.memaccount_id=m.id) join savings_product s on(m.saveproduct_id=s.id) where d.amount >= s.min_bal and d.date <='to_date'","A16a","Number of clients with compulsory savings, counting individuals as well as members of groups (end of period)","");
	#A16b Value of compulsory savings (end of period)
	$pmt->setQuery("select sum(no_of_accounts*min_bal) as amount from mem_accounts_product_view where open_date <='to_date'","A16b","Value of compulsory savings (end of period)","");
	#A17 Number of clients with voluntary savings, counting individuals as well as members of groups (end of period)
	//$pmt->setQuery();
	
	#value of arrears
	//$pmt->setQuery("select sum(s_princ_amt)+sum(s_princ_amt)-sum(p_princ_amt)-sum(p_int_amt)");
	
	#Ageing
	$pmt->addHeading("Aging of Portfolio At Risk (PAR)");
	$pmt->addHeading("A25 Value of outstanding loan balance with arrears by age of arrears (end of period)");
	
	$pmt->setQuery("select sum(balance) as amount from ageing_view where missed_days >=1 and missed_days <=30","A25a","1 - 30 days","");
	$pmt->setQuery("select sum(balance) as amount from ageing_view where missed_days >=31 and missed_days <=60","A25b","31 - 60 days","");
	$pmt->setQuery("select sum(balance) as amount from ageing_view where missed_days >=61 and missed_days <=90","A25c","61 - 90 days","");
	$pmt->setQuery("select sum(balance) as amount from ageing_view where missed_days >=91 and missed_days <=180","A25d","91 - 180 days","");
	$pmt->setQuery("select sum(balance) as amount from ageing_view where missed_days > 180 ","A25e","> 180 days","");
	#A26 Written off
	$pmt->addHeading("Write-Offs");
	$pmt->setQuery("select sum(amount) as amount from written_off where date>='from_date' and date <='to_date'","A26","Amount written off (during period)","");
	#A27 No of loans written off
	$pmt->setQuery("select count(distinct loan_id) as amount from written_off where date >='from_date' and date <='to_date'","A27","Number of loans written-off (during period)","");
	#Dividends and Exchange Gains/(Losses)
	$pmt->addHeading("Dividends and Exchange Gains/(Losses)");
	#A28
	$pmt->setQuery("select sum(amount) as amount from dividends","A28","Amount of dividends paid (during period)","");
	#
	$pmt->addHeading("Solvency");
	#A30
	$pmt->setQuery("select count(id) as amount from member where trans_date <='to_date' and trans_date >='from_date'","A30","Number of members (end of period)","");
	
	$pmt->setQuery("select sum(amount) as amount from shares where date <=''to_date","A34","Total value of member shares (end of period)","");
	
	#
	$pmt->addHeading("Human Resources");
	
	$pmt->setQuery("select count(id) as amount from users where branch_id=(select min(branch_no) from branch) and lower(position)<>'member'","A35","Total number of staff in the head office (end of period)","");
	#
	$pmt->setQuery("select count(id) as amount from users where branch_id<>(select min(branch_no) from branch) and lower(position)<>'member'","A36","Total number of staff in branches (end of period)","");
	#
	$pmt->setQuery("select count(id) as amount from users where lower(position) like '%loan officer%'","A37","Total number of loan officers in head office and branches (end of period)","");
	#
	$pmt->setQuery("select count(id) as amount from branch","A38","Number of branches (end of period)","");
	$pmt->addHeading("Miscellaneous");
	
	$pmt->addHeading("Economic Indicators Uganda (annual)");
	
	
	$content .= $pmt->printToHTML();
	$content .= "</table>";
	$resp->assign("display_div","innerHTML",$content);
	return $resp;
}
//PERFORMANCE GRAPHS
function savers($from_date,$to_date){
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	$to_year = sprintf("%d-%02d-%02d", $to_year, $to_month);
	$from_year = sprintf("%d-%02d-%02d", $from_year, $from_month);
	$from_year = $calc->daysToDate($calc->dateToDays($mday, $month, $year)-365, '%Y-%m-%d');
	$branch_res = mysql_query("select * from branch");
	$branch = mysql_fetch_array($branch_res);
	$content = "<center><font color=#00008b size=5pt><b>".$branch['branch_name']."</b></font></center><br>
	<center><font color=#00008b size=3pt><b>GRAPH OF NUMBER OF SAVERS OVER THE MONTHS  </b><br>".
	$content .='<div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>';
	"<input type=button value='Show Report' onclick=\"xajax_savers(getElementById('from_date').value, getElementById('to_date').value).value);\"><table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>";
	$resp->call("createDate","from_date");
	$resp->call("createDate","to_date"); 
	if($from_date == '')
		$content .="<tr><td></td></tr>";
	return $resp;
}

?>