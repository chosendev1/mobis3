<?php

$xajax->registerFunction("addInvestmentForm");
$xajax->registerFunction("addInvestmentForm1");
$xajax->registerFunction("addInvestment");
$xajax->registerFunction("addSaleInvestmentForm");
$xajax->registerFunction("addSaleInvestment");
$xajax->registerFunction("listInvestments");
$xajax->registerFunction("editInvestmentForm");
$xajax->registerFunction("updateInvestment2");
$xajax->registerFunction("updateInvestment");
$xajax->registerFunction("deleteInvestment2");
$xajax->registerFunction("deleteInvestment");

$xajax->registerFunction("saleInvestmentForm");
$xajax->registerFunction("saleInvestment");
$xajax->registerFunction("listSoldInvestments");
$xajax->registerFunction("editSoldInvestmentsForm");
$xajax->registerFunction("updateSoldInvestment2");
$xajax->registerFunction("updateSoldInvestment");
$xajax->registerFunction("deleteSoldInvestment2");
$xajax->registerFunction("deleteSoldInvestment");

function addInvestmentForm($prefix)
{       
        $fixed_acc='';
        $accts='';
        $content  ='';
        $type ='';
	$resp = new xajaxResponse();
	//$fix = @mysql_query("select account_id, id from other_income order by id");
	
	$investments = @mysql_query("select id, account_no, name from accounts where (account_no >=1221 and account_no <=1229) or (account_no >=122101 and account_no <= 122199)   order by account_no ");
	if(mysql_num_rows($investments) > 0){
	while ($level1row = @mysql_fetch_array($investments))
	{
			
			$fixed_acc .= "<option value='".$level1row['id']."'>".$level1row['account_no']." -".$level1row['name']."</option>";
	} 
	}
	
		$acc = @mysql_query("select ac.name, ac.account_no as account_no, ba.id as bank_acct_id, ba.bank as bank_name from accounts ac join bank_account ba on ac.id = ba.account_id ");	
	/*else
		$acc = @mysql_query("select ac.name, ac.account_no as account_no, ba.id as bank_acct_id, ba.bank as bank_name from accounts ac join bank_account ba on ac.id = ba.account_id where ba.id in (select bank_account_id from user_account where user_id =".$_SESSION['user_id'].")");*/	
	//$acc = @mysql_query("select ba.id, ac.name, ac.account_no, ba.bank from accounts ac join bank_account ba on ac.id = ba.account_id");
	$accts .= "<option value=''>&nbsp;</option>";
	while ($accrow = @mysql_fetch_array($acc))
	{
		$accts .= "<option value='".$accrow['bank_acct_id']."'>".$accrow['account_no']." - ".$accrow['name']."</option>";
	}
	$content .="<div class='col-md-12'><form method='post' class='panel form-horizontal panel-default'>";
$content .= '
  			  		<div class="panel-heading">
                                 		
                                                	<h4>REGISTER INVESTMENT</h4>
                                           
                                        </div><div class="panel-body">';
                                                                                                      	 
	                                    $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Branch:</label>
                                            <div class="col-sm-6"><span>'.branch().'</span></div>                                            
                                            </div>';                                           
                                            
                                            $content .= '<div class="form-group">
                                           
                                            <label class="col-sm-3 control-label">Select Account (From Chart of Accts):</label>
                                            <div class="col-sm-6">
                                          <select id="acc_id" name="acc_id" class="form-control">'.$fixed_acc.'</select>
                                            </div></div>';                                         
                                             $content .= '<div class="form-group">
                                             
                                            <label class="col-sm-3 control-label">Select Source Bank Account:</label>
                                            <div class="col-sm-6">
                                           <select name="bank_acct_id" id="bank_acct_id" class="form-control">'.$accts.'</select>
                                            </div></div>';                                          
                                                                                       
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Quantity:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" name="quantity" id="quantity" class="form-control">
                                            </div></div>';                                            
                         
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Unit Purchase Price:</label>
                                            <div class="col-sm-6">
                                          <input onkeyup="format_as_number(this.id)" type="numeric" name="amount" id="amount" class="form-control">
                                            </div></div>';                                         
                                                                                         
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Voucher No:</label>
                                            <div class="col-sm-6">
                                          <input type="numeric" name="voucher_no" id="voucher_no" class="form-control">
                                            </div></div>';                                          
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Contact Person:</label>
                                            <div class="col-sm-6">
                                           <input type="text" name="contact" id="contact" class="form-control">
                                            </div></div>';
                                            
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Date:</label>
                                            <div class="col-sm-6">
                                          <input type="text" class="form-control" id="date" name="date" placeholder="date" />
                                            </div></div>';                                           
                                             
                                            $content .= "<div class='panel-footer'><button type='reset' class='btn btn-default' onclick=\"xajax_addInvestmentForm('$prefix')\">Reset</button>
                                            <button type='button' class='btn btn-primary' onclick=\"xajax_addInvestment(document.getElementById('acc_id').value, document.getElementById('amount').value, document.getElementById('quantity').value,document.getElementById('bank_acct_id').value,	document.getElementById('voucher_no').value,document.getElementById('date').value,document.getElementById('branch_id').value, document.getElementById('contact').value); return false;\">Save</button>";
				$resp->call("createDate","date");
				
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


function addSaleInvestmentForm()
{       $accts="";
        $fixed_acc="";
        $content="";
	$resp = new xajaxResponse();
	
	//$fix = @mysql_query("select account_id, id from other_income order by id");
	$sth = mysql_query("select distinct id as id, account_no, name from accounts where id in (select account_id from investments)");
	while($row = mysql_fetch_array($sth)){
		$fixed_acc .= "<option value='".$row['id']."'>".$row['account_no']." -".$row['name']."</option>";
	}
	//if (strtolower($_SESSION['position']) == 'manager')
		$acc = @mysql_query("select ac.name, ac.account_no as account_no, ba.id as bank_acct_id, ba.bank as bank_name from accounts ac join bank_account ba on ac.id = ba.account_id ");	
	/*else
		$acc = @mysql_query("select ac.name, ac.account_no as account_no, ba.id as bank_acct_id, ba.bank as bank_name from accounts ac join bank_account ba on ac.id = ba.account_id where ba.id in (select bank_account_id from user_account where user_id =".$_SESSION['user_id'].")");*/	
	//$acc = @mysql_query("select ba.id, ac.name, ac.account_no, ba.bank from accounts ac join bank_account ba on ac.id = ba.account_id");
	$accts .= "<option value=''>&nbsp;</option>";
	while ($accrow = @mysql_fetch_array($acc))
	{
		$accts .= "<option value='".$accrow['bank_acct_id']."'>".$accrow['account_no']." -". $accrow['name']."</option>";
	}
	
	$content .="<div class='col-md-12'><form method='post' class='panel form-horizontal panel-default'>";
$content .= '
  			  		<div class="panel-heading">
                                 		
                                                	<h4>SELL INVESTMENT</h4>
                                           	 </div>
                                        <div class="panel-body">';
	/* $sth = mysql_query("select account_no, name from accounts where account_no >='113' and account_no <= '119'");
	while($row = mysql_fetch_array($sth)){
		$fixed_acc .= "<option value='".$row['account_no']."'>".$row['account_no']." - ".strtoupper($row['name']);
	}
	*/
	 $content .= '<div class="form-group">
                                           
                                            <label class="col-sm-3 control-label">Select Account (From Chart of Accts):</label>
                                            <div class="col-sm-4">
                                          <select id="acc_id" name="acc_id" class="form-control">'.$fixed_acc.'</select>
                                            </div></div>';                                         
                                             $content .= '<div class="form-group">
                                             
                                            <label class="col-sm-3 control-label">Select Destination Bank Account:</label>
                                            <div class="col-sm-4">
                                           <select name="bank_acct_id" id="bank_acct_id" class="form-control">'.$accts.'</select>
                                            </div></div>';                                          
                                                                                                                                                      
                         
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Unit Purchase Price:</label>
                                            <div class="col-sm-4">
                                          <input onkeyup="format_as_number(this.id)" type="numeric" name="purchase_price" id="purchase_price" class="form-control">
                                            </div></div>';
                                            
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Unit Selling Price:</label>
                                            <div class="col-sm-4">
                                          <input onkeyup="format_as_number(this.id)" type="numeric" name="amount" id="amount" class="form-control">
                                            </div></div>'; 
                                                                                                                                                                           
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Quantity:</label>
                                            <div class="col-sm-4">
                                          <input type="numeric" name="quantity" id="quantity" class="form-control">
                                            </div></div>';                                          
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Receipt No:</label>
                                            <div class="col-sm-4">
                                           <input type="numeric" name="receipt_no" id="receipt_no" class="form-control">
                                            </div></div>';
                                            
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Date:</label>
                                            <div class="col-sm-4">
                                           <input type="text" class="form-control" id="date" name="date" placeholder="date" />
                                            </div></div>';                                           
                                             
                                            $content .= "<div class='panel-footer'><button type='reset' class='btn btn-default' onclick=\"xajax_addSaleInvestmentForm()\">Reset</button>
                                            <button type='button' class='btn btn-primary' onclick=\"xajax_addSaleInvestment(document.getElementById('acc_id').value, document.getElementById('purchase_price').value, document.getElementById('amount').value,  document.getElementById('quantity').value, 
				document.getElementById('bank_acct_id').value,
				document.getElementById('receipt_no').value,
				document.getElementById('date').value,return false;\">Save</button>";
				 $content .= '</div></form></div>';
				 $resp->call("createDate","date");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}



function addInvestmentForm1()
{
	$resp = new xajaxResponse();
	$content .= "
		    <font color=blue><b>Add Payable</b><br>
		    Select Type of Payable:</font><br>
		    <form method=post><table border=o cellspacing=0 cellpadding=0>
		    <tr>
		    	<td>Type: </td><td><select id='ptype' name='ptype'><option value='213'>Accounts Payable</option><option value='212'>Loans Payable</option></select></td><td><input type=button value='Next' onclick=\"xajax_addPayableForm(getElementById('ptype').value); return false;\"></td>
		    </tr>
		    </table></form>
		    ";
	$resp->assign("status", "innerHTML", "");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function addInvestment($acc_id, $amount, $quantity, $bank_acct_id, $voucher_no, $date,$branch_id, $contact)
{       
	list($year,$month,$mday) = explode('-', $date);
	
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$amount=str_ireplace(",","",$amount);
	
	$date = sprintf("%d-%02d-%02d", $year, $month, $mday);
	
	if ($acc_id == '' || $amount == '' || $bank_acct_id == '' || $voucher_no == '')
	{
		$resp->AddAlert('Please do not leave any field blank.');
		return $resp;
	}elseif(!preg_match("/\d+[.]?\d*/", $amount)){
		$resp->AddAlert("Investment not registered! \n Please enter only digits for amount");
		return $resp;
	}
	elseif (!$calc->isValidDate($mday, $month, $year))
	{
		$resp->AddAlert('Invalid Date. Most likely wrong date for Month: Feb.');
		return $resp;
	}
	elseif ($calc->isFutureDate($mday, $month, $year))
	{
		$resp->AddAlert('Date can not be a future date.');
	}
	else
	{
		$bal_row = @mysql_fetch_array(@mysql_query("select account_balance, min_balance from bank_account where id = $bank_acct_id"));
		$new_bal = $bal_row['account_balance'] - ($amount * $quantity);
		//if (  $new_bal <intval($bal_row['min_balance']))
		//{
			//$resp->AddAlert("Insufficient bank account balance.");
			//return $resp;
		//}
		start_trans();
		$bank_upd_res = @mysql_query("UPDATE bank_account set account_balance = ".$new_bal." where id = ".$bank_acct_id."");
		if (isset($bank_upd_res) && @mysql_affected_rows() == -1)
		{
			rollback();
			$resp->AddAlert("Error: Failed to update bank account balance.");
			return $resp;
		}
	
		$res = mysql_query("insert into investments (account_id, amount, quantity, bank_account, voucher_no, date,branch_id, contact) values ('".$acc_id."', '".$amount."', '".$quantity."', '".$bank_acct_id."', '".$voucher_no."', '".$date."', '".$branch_id."', '".$contact."')");
		if (mysql_affected_rows() > 0)
		{
			commit();
			$cont = "<font color=red>Investment registered successfully.</font><br>";
		}
		else
		{
			$cont = "<font color=red>Error: Investment not registered.</font>".mysql_error();
			rollback();
			
		}
	}
	$resp->assign("status", "innerHTML", $cont);
	return $resp;
}

//INSERT SALE OF INVESTMENT
function addSaleInvestment($acc_id, $purchase_price, $amount, $quantity, $bank_acct_id, $receipt_no, $date)
{
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$purchase_price=str_ireplace(",","",$purchase_price);
	$amount=str_ireplace(",","",$amount);
	
	$stock = mysql_fetch_array(mysql_query("select sum(quantity) as quantity from investments where account_id='".$acc_id."' and date <='".$date."'"));
	$stock = ($stock['quantity'] == NULL) ? 0 : $stock['quantity'];

	$sold = mysql_fetch_array(mysql_query("select sum(quantity) as quantity from sold_invest where account_id='".$acc_id."' and date <='".$date."'"));
	$sold = ($sold['quantity'] == NULL) ? 0 : $sold['quantity'];
	
	if(($sold + $quantity) > $stock){
		$resp->alert("Stock not sufficient for this transaction.");
		return $resp;
	}elseif($purchase_price =='' || intval($purchase_price)==0){
		$resp->alert("Please enter the correct unit purchase price");
		return $resp;
	}elseif ($acc_id == '' || $amount == '' || $bank_acct_id == '' || $receipt_no == '')
	{
		$resp->alert('Please do not leave any field blank.');
		return $resp;
	}elseif(!preg_match("/\d+[.]?\d*/", $amount)){
		$resp->alert("Investment Sale not registered! \n Please enter only digits for amount");
		return $resp;
	}
	elseif (!$calc->isValidDate($date))
	{
		$resp->alert('Invalid Date. Most likely wrong date for Month: Feb.');
		return $resp;
	}
	elseif ($calc->isFutureDate($date))
	{
		$resp->alert('Date can not be a future date.');
	}
	else
	{
		$total_amount = $amount * $quantity;
		$bal_row = @mysql_fetch_array(@mysql_query("select account_balance, min_balance from bank_account where id = $bank_acct_id"));
		$new_bal = $bal_row['account_balance'] - $total_amount;
		//if (  $new_bal < $bal_row['min_balance'])
		//{
		//	$resp->alert("Insufficient bank account balance.");
		//	return $resp;
		//}
		start_trans();
		$bank_upd_res = @mysql_query("UPDATE bank_account set account_balance = ".$new_bal." where id = ".$bank_acct_id."");
		if (isset($bank_upd_res) && @mysql_affected_rows() == -1)
		{
			rollback();
			$resp->alert("Error: Failed to update bank account balance.");
			return $resp;
		}
	
		$res = mysql_query("insert into sold_invest (account_id, purchase_price, amount, quantity, bank_account, receipt_no, date) values ('".$acc_id."', '".$purchase_price."', '".$amount."', '".$quantity."', '".$bank_acct_id."', '".$receipt_no."', '".$date."')");
		if (mysql_affected_rows() > 0)
		{
			commit();
			$content .= "<font color=blue>Investment Sale registered successfully.</font><br>";
		}
		else
		{
			$content .= "<font color=red>Error: Investment Sale not registered.</font>".mysql_error();
			rollback();
			
		}
	}
	$resp->assign("status", "innerHTML", $content);
	return $resp;
}


function listInvestments($type, $contact, $from_date,$to_date, $branch_id)
{    
        list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	//$resp->assign("status", "innerHTML", "");
	$branch = ($branch_id=='all'||$branch_id=='')?NULL:"and r.branch_id=".$branch_id;
	$content = '
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <p><h5>LIST OF INVESTMENTS</h5></p>
                                          
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    
                                        <div class="col-sm-3">
                                            <label class="control-label">Account:</label>
                                            <select id="account" class="form-control"><option value="all">All';
	$sth = mysql_query("select distinct id as id, account_no, name from accounts where id in (select account_id from investments)");
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
                                            </div>
                                    </div>
                                </div></div>';
                                                                                        
	$content .= "<div class='panel-footer'>                              
                                
                                <input type='button' class='btn  btn-primary' value='Search' onclick=\"xajax_listInvestments(getElementById('account').value, getElementById('contact').value, getElementById('from_date').value,getElementById('to_date').value, getElementById('branch_id').value)\">
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","from_date");
	            $resp->call("createDate","to_date"); 
                     //$resp->assign("display_div", "innerHTML", $content);
	if($from_date == '' || $to_date==''){
		$cont= "<font color=red>Please select the period for your Investments</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
	$to_date = sprintf("%04d-%02d-%02d 23:59:59", $to_year, $to_month, $to_date);
	$from_date = sprintf("%04d-%02d-%02d 00:00:00", $from_date, $from_month, $from_mday);
	if (strtolower($type) == 'all')
	{
		$res = @mysql_query("select ac.id as acc_id, ac.account_no as account_no, ac.name as acc_name, r.quantity as quantity, r.id as rid, r.amount as amount, r.date as mdate, r.contact as contact  from accounts ac right join investments r on r.account_id = ac.id where  r.contact like '%".$contact."%' ".$branch." order by r.date desc");
		//$resp->assign("status", "innerHTML", "select ac.id as acc_id, ac.account_no as account_no, ac.name as acc_name, r.quantity as quantity, r.id as rid, r.amount as amount, r.date as mdate, r.contact as contact  from accounts ac right join investments r on r.account_id = ac.id where r.date >='".$from_date."' and r.date <='".$to_date."' and r.contact like '%".$contact."%' ".$branch." order by r.date desc");
	}
	/****************************
	* SPECIFY ACCOUNT TYPE
	***************************/
	else
	{
		$res = @mysql_query("select ac.id as acc_id, ac.account_no as account_no, ac.name as acc_name, r.quantity as quantity, r.id as rid, r.amount as amount, r.date as mdate, r.contact as contact  from accounts ac join investments r on r.account_id = ac.id where r.account_id='".$type."' and r.contact like '%".$contact."%' ".$branch." order by r.date desc");
	}
	if (@mysql_num_rows($res) > 0)
	{
		/*$content .=
			   "<a href='list_investments.php?account=".$type."&branch_id=".$branch_id."&contact=".$contact."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."' target=blank()><b>Printable Version</b></a> | <a href='list_investments.php?account=".$type."&branch_id=".$branch_id."&contact=".$$contact."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&format=excel' target=blank()><b>Export Excel</b></a> | <a href='list_investments.php?account=".$type."&branch_id=".$branch_id."&contact=".$contact."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&format=word' target=blank()><b>Export Word</b></a>";*/
		$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="semibold text-primary mt0 mb5">LIST OF INVESTMENTS</h3>
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
 		
		$content .="<thead'>
				        <th>ACCOUNT</th><th>CONTACT/ITEM</th><th>UNIT COST</th><th>QUANTITY</th><th>TOTAL COST</th><th>PURCHASE DATE</th><th>DISBURSEMENT A/C</th><th><th></th>
			   </thead><tbody>
			    ";
		$i=0;
		while ($fxrow = @mysql_fetch_array($res))
		{
			$bank = mysql_fetch_array(mysql_query("select b.bank, a.account_no, a.name from investments i join bank_account b on i.bank_account=b.id join accounts a on b.account_id=a.id where i.id='".$fxrow['rid']."'"));
			$int = mysql_fetch_array(mysql_query("select sum(amount) as amount from other_income where account_id='".$fxrow['acc_id']."'"));
			$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;
			//$color = ($i%2 == 0) ? "lightgrey" : "white";
			//if (strtolower($type) == 'all' || strtolower($type) == 'cleared')
			//{
			$content .= "
				    <tr>
				        <td>".$fxrow['account_no'] ."-" .$fxrow['acc_name']."</td><td>".$fxrow['contact']."</td><td>".number_format($fxrow['amount'])."</td><td>".$fxrow['quantity']."</td><td>".number_format($fxrow['amount']* $fxrow['quantity'])."</td><td>".$fxrow['mdate']."</td><td>".$bank['account_no']. " - ".$bank['bank']." ".$bank['name']."</td><td><a href='javascript:;'  onclick=\"xajax_editInvestmentForm('".$fxrow['rid']."', '".$type."', '".$contact."', '".$date."', '".$branch_id."'); return false;\">Edit</a>&nbsp;<a href='javascript:;' onclick=\"xajax_deleteInvestment2('".$fxrow['rid']."', '".$type."'); return false;\">Delete</a></td>
				    </tr>
				    ";		
			$total += $fxrow['amount']* $fxrow['quantity'];
			//}
		}

		$content .= "<tr>
			<td>TOTAL</td><td></td><td></td><td></td><td>".number_format($total, 2)."</td><td></td><td></td><td></td>
				    </tr></tbody></table></div>";
				    $resp->call("createTableJs");
	}

	else{
		$cont = "<font color=red>No Investments found in your search options.</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
	}
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


function editInvestmentForm($pid, $type, $contact,$date, $branch_id)
{       $accts='';
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$res = @mysql_query("select  ac.name, r.id, r.amount, r.account_id, r.voucher_no, r.bank_account, date_format(date, '%Y') as year, date_format(date, '%m') as month, date_format(date, '%d') as mday from  investments r join accounts ac on ac.id = r.account_id where r.id = $pid");

	if (@mysql_num_rows($res) > 0)
	{
		$row = @mysql_fetch_array($res);
		$acc = @mysql_query("select ba.id, ac.name, ac.account_no, ba.bank from accounts ac join bank_account ba on ac.id = ba.account_id");
		$accts .= "<option value=''>&nbsp;</option>";
		while ($accrow = @mysql_fetch_array($acc))
		{
			if ($accrow['id'] == $row[bank_account])
				$accts .= "<option value='".$accrow['id']."' selected>".$accrow['account_no']." - ".$accrow['name']."</option>";
			else
				$accts .= "<option value='".$accrow['id']."'>".$accrow['account_no']." -". $accrow['name']."</option>";
		}
		$content ="<form method='post' class='panel form-horizontal form-bordered'>";
$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h4 class="semibold text-primary mt0 mb5">EDIT INVESTMENT</h4>
                                               		 <p class="text-default nm"></p>
                                           	 </div>
                                        </div>';
                                        
                                         $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Unit Price:</label>
                                            <div class="col-sm-6">
                                           <input onkeyup="format_as_number(this.id)" type="numeric" id="amount" name="amount" value="'.$row['amount'].'" class="form-control">
                                            </div></div>';
                                            
                                         $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Quantity:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" id="quantity" name="quantity" value="'.$row['amount'].'" class="form-control">
                                            </div></div>';   
                                          $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Date:</label>
                                            <div class="col-sm-6">
                                          <input type="text" class="form-control" id="date" name="date" value="'.$row['mday']."/".$row['month']."/".$row['year'].'" />
                                            </div></div>'; 
                                            
                                           $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Voucher No:</label>
                                            <div class="col-sm-6">
                                           <input type=text id="voucher_no" name="voucher_no" value="'.$row['voucher_no'].'" class="form-control">
                                            </div></div>';
			                  
			                  $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Select Source Bank Account:</label>
                                            <div class="col-sm-6">
                                           <select name="bank_acct_id" id="bank_acct_id"  class="form-control">'.$accts.'</select>
                                            </div></div>';
                                           
                                           $content .= "<div class='panel-footer'><button type='reset' class='btn btn-default'  onclick=\"xajax_listInvestments('".$type."', '".$contact."', '".$from_date."', '".$to_date."', '".$branch_id."')\">Back</button>

                                            <button type='button' class='btn btn-primary'  onclick=\"xajax_updateInvestment2('".$row['id']."', '".$row['name']."', '".$row['bank_account']."', '".$row['amount']."', document.getElementById('amount').value, document.getElementById('quantity').value, getElementById('bank_acct_id').value, getElementById('voucher_no').value, document.getElementById('date').value,'".$contact."', '".$from_date."','".$to_date."', '".$branch_id."'); return false;\">Save</button>";
                                            $content .= '</div></form></div>';
                                             $resp->call("createDate","date");
			   							    	
	}
	else {
		$cont = "<font color=red><b>ERROR: Investment not found!</b></font>";
		$resp->assign("display_div", "innerHTML", $cont);
	return $resp;
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function updateInvestment2($pid, $pname, $prev_bank_acct_id, $prev_amount, $amount, $quantity, $bank_acct_id, $voucher_no, $date, $contact, $from_date,$to_date, $branch_id)
{      
        $amount=str_ireplace(",","",$amount);
        
        list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$calc = new Date_Calc();

	$acc = mysql_fetch_array(mysql_query("select * from investments where id='".$pid."'"));
	$acc_id= $acc['account_id'];
	$stock = mysql_fetch_array(mysql_query("select sum(quantity) as quantity from investments where account_id='".$acc_id."' and id<>'".$pid."'"));
	$stock = ($stock['quantity'] == NULL) ? 0 : $stock['quantity'];

	$sold = mysql_fetch_array(mysql_query("select sum(quantity) as quantity from sold_invest where account_id='".$acc_id."'"));
	$sold = ($sold['quantity'] == NULL) ? 0 : $sold['quantity'];
	if(($stock+$quantity) < $sold){
		$resp->alert("Update rejected, part of this stock has been sold already.");
	}elseif ($pid == '' || $amount == '' || $bank_acct_id == '' || $voucher_no == '')
		$resp->alert('Please do not leave any fields blank.');
	elseif ($calc->isFutureDate($mday, $month, $year))
		$resp->alert('Date can not be a future date');
	/* elseif (!unique_rcpt($rcpt_no, 'other_income'))
		$resp->alert("Receipt has already been used."); */
	else
	{

		$resp->ConfirmCommands(1, "Do you really want to update?");
		$resp->call('xajax_updateInvestment', $pid, $pname, $prev_bank_acct_id, $prev_amount, $amount, $quantity, $bank_acct_id, $voucher_no, $date, $contact, $from_date,$to_date, $branch_id);
	}
	return $resp;
}

function updateInvestment($pid, $pname, $prev_bank_acct_id, $prev_amount, $amount, $quantity, $bank_acct_id, $voucher_no, $date, $contact, $from_date,$to_date, $branch_id)
{
	$resp = new xajaxResponse();
	$amount=str_ireplace(",","",$amount);
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	start_trans();
	$former = mysql_fetch_array(mysql_query("select (amount*quantity) as tot_prev from investments where id='".$pid."'"));
	$tot_prev = ($former['tot_prev'] == NULL) ? 0 : $former['tot_prev'];
	if ($prev_bank_acct_id == $bank_acct_id && $prev_amount == $amount)
	{
		$res = @mysql_query("update investments set date='".$date."', quantity='".$quantity."', voucher_no = '".$voucher_no."' where id=$pid");
		if (@mysql_affected_rows() != -1)
		{
			$action = "update investments set date='".$date."', voucher_no = '".$voucher_no."' where id=$pid";
			mysql_query("insert into logs set user_id='".$_SESSION['user_id']."', time=NOW(),  action='".$_SESSION['name'].":  ".mysql_escape_string($action)."'");
			commit();
			$content .= "<font color=green><b>Investment $pname updated successfully.</b></font><br>";
		}
		else
		{
			rollback();
			$content .= "<font color=red><b>ERROR: Investment $pname not updated! ".mysql_error()."</b></font><br>";
		}
	}
	elseif($prev_bank_acct_id == $bank_acct_id){
		$tot_cur = $amount * $quantity;
		$cur_bank_row = @mysql_fetch_array(@mysql_query("select account_balance, min_balance from bank_account where id = $bank_acct_id"));
		$new_bal = $cur_bank_row['account_balance'] - $tot_cur + $tot_prev;
		
		if ( $new_bal <$cur_bank_row['min_balance'])
		{
			$resp->alert("Insufficient funds on newly selected bank account.");
			return $resp;
		}
		else
		{	
			$new_bank_upd_res = @mysql_query("UPDATE bank_account set account_balance = $new_bal where id = $bank_acct_id");
			if (isset($new_bank_upd_res) && @mysql_affected_rows() != -1)
			{
				$inv_upd_res = @mysql_query("UPDATE investments set amount = ".$amount.", date = '".$date."', bank_account = $bank_acct_id, voucher_no = '".$voucher_no."' where id = '".$pid."'");
				if (isset($inv_upd_res) && @mysql_affected_rows() != -1)
				{
					commit();
					$content .= "<font color=green><b>Investment ".$pname." updated successfully.</b></font><br>";
				}
				else
				{
					rollback();
					$content .= "<font color=red><b>ERROR: Investment ".$pname." not updated! ".mysql_error()."</b></font><br>";	
				}
			}
			else
			{
				rollback();
				$content .= "<font color=red><b>ERROR: New bank balance not updated! ".mysql_error()."</b></font><br>";
			}
		}
	}else{
		$prev_bank_row = @mysql_fetch_array(@mysql_query("select account_balance, min_balance from bank_account where id = $prev_bank_acct_id"));
		$cur_bank_row = @mysql_fetch_array(@mysql_query("select account_balance, min_balance from bank_account where id = $bank_acct_id"));
		$old_bal = intval($prev_bank_row['account_balance']) + $prev_amount;
		$new_bal = intval($cur_bank_row['account_balance']) - $amount;
		
		if ( $new_bal <intval($cur_bank_row['min_balance']))
		{
			$resp->alert("Insufficient funds on newly selected bank account.");
			return $resp;
		}
		else
		{
			$old_bank_upd_res = @mysql_query("UPDATE bank_account set account_balance = $old_bal where id = $prev_bank_acct_id");
			if (isset($old_bank_upd_res) && @mysql_affected_rows() != -1)
			{
				$new_bank_upd_res = @mysql_query("UPDATE bank_account set account_balance = $new_bal where id = $bank_acct_id");
				if (isset($new_bank_upd_res) && @mysql_affected_rows() != -1)
				{
					$inv_upd_res = @mysql_query("UPDATE investments set amount = $amount, date = '".$date."', bank_account = $bank_acct_id, voucher_no = '".$voucher_no."' where id = $pid");
					if (isset($inv_upd_res) && @mysql_affected_rows() != -1)
					{
						commit();
						$content .= "<font><b>Investment ".$pname." updated successfully.</b></font>";
					}
					else
					{
						rollback();
						$content .= "<font color=red><b>ERROR: Investment ".$pname." not updated! ".mysql_error()."</b></font>";	
					}
				}
				else
				{
					rollback();
					$content .= "<font color=red><b>ERROR: New bank balance not updated! ".mysql_error()."</b></font><br>";
				}
			}
			else
			{
				rollback();
				$content .= "<font color=red><b>ERROR: Old Bank balance not updated! ".mysql_error()."</b></font><br>";
			}
		}
	}
	$resp->assign("status", "innerHTML", $content);
	$resp->call('xajax_listInvestments', 'all', $contact, $from_date,$to_date, $branch_id);
	return $resp;
}

function deleteInvestment2($pid, $contact, $from_date,$to_date, $branch_id)
{
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	if (@mysql_num_rows(@mysql_query("select * from investments where id = $pid")) < 1)
		$resp->alert("Cannot delete: Investment entry not found.");
	else
	{
		$acc = mysql_fetch_array(mysql_query("select * from investments where id='".$pid."'"));
		$acc_id= $acc['account_id'];
		$stock = mysql_fetch_array(mysql_query("select sum(quantity) as quantity from investments where account_id='".$acc_id."' and id<>'".$pid."'"));
		$stock = ($stock['quantity'] == NULL) ? 0 : $stock['quantity'];

		$sold = mysql_fetch_array(mysql_query("select sum(quantity) as quantity from sold_invest where account_id='".$acc_id."'"));
		$sold = ($sold['quantity'] == NULL) ? 0 : $sold['quantity'];
		if(($stock) < $sold){
			$resp->alert("Could not delete this stock, part of this stock has been sold already.");
		}else{
			$inv_row = @mysql_fetch_array(@mysql_query("select inv.amount, inv.bank_account, ba.min_balance, (ba.account_balance + (inv.amount* inv.quantity)) as new_bal from investments inv join bank_account ba on inv.bank_account = ba.id where inv.id = $pid"));
			$resp->ConfirmCommands(1, "Do you really want to delete?");
			$resp->call('xajax_deleteInvestment', $pid, $inv_row['bank_account'], $inv_row['new_bal'], $contact, $from_date,$to_date, $branch_id);
		}
	}
	//$resp->assign("status", "innerHTML", "test");
	return $resp;
}


function deleteInvestment($pid, $bank_acct_id, $new_bal, $contact, $from_date,$to_date, $branch_id)
{        
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	start_trans();
	$upd_res = @mysql_query("UPDATE bank_account set account_balance = ".$new_bal." where id ='". $bank_acct_id."' ");
	if (isset($upd_res) && @mysql_affected_rows() != -1)
	{
		$res = @mysql_query("delete from investments where id = '".$pid."'");
		if (@mysql_affected_rows() > 0)
		{
			$action = "delete from investments where id = '".$pid."'";
			mysql_query("insert into logs set user_id='".$_SESSION['user_id']."', time=NOW(),  action='".$_SESSION['name'].":  ".mysql_escape_string($action)."'");
			commit();
			$content .= "<font><b>Investment deleted successfully.</b></font>";
		}
		else
		{
			rollback();
			$content .= "<font color=red><b>Error: Failed to delete Investment. </b></font>";
		}
	}
	else
	{
		rollback();
		$content .= "<font color=red><b>Error: Failed to update bank account balance. </b></font><br>";
	}
	$resp->assign("status", "innerHTML", $content);
	$resp->call('xajax_listInvestments', 'all', $contact, $from_date,$to_date, $branch_id);
	return $resp;
}

function saleInvestmentForm()
{        $invest='';
         $accts ='';
	$resp = new xajaxResponse();
	$inv_res = @mysql_query("select ac.name, ac.account_no, inv.id as inv_id, inv.amount as amount from accounts ac join investments inv  on ac.id = inv.account_id where inv.id NOT IN (select investment_id from sold_invest) order by ac.account_no, inv.date desc");
	$acc = @mysql_query("select ac.name, ac.id as acc_id, ac.account_no, ba.bank, ba.id as bank_id from accounts ac join bank_account ba on ac.id = ba.account_id");

        if (@mysql_num_rows($acc) > 0)
        {
                $accts .= "<option value=''>&nbsp;</option>";
                while ($acc_row = @mysql_fetch_array($acc))
                {
                        $accts .= "<option value='".$acc_row['bank_id']."'>".$acc_row['bank']." -". $acc_row['name']."</option>";
                }
        }
        else
                $accts .= "<option value=''>&nbsp;</option>";

        if (@mysql_num_rows($inv_res) > 0)
        {
                $invest .= "<option value=''>&nbsp;</option>";
                while ($inv_row = @mysql_fetch_array($inv_res))
                {
                        $invest .= "<option value='".$inv_row['inv_id']."'>".$inv_row['account_no']." -". $inv_row['name']."(".$inv_row['amount'].")"."</option>";
                }
                $content .="<div class='col-md-12'><form method='post' class='panel form-horizontal panel-default'>";
$content .= '
  			  		<div class="panel-heading">
                                 		
                                                	<h4>SELL INVESTMENT</h4>
                                               		
                                           	 </div>
                                       <div class="panel-body">';
                                        
                                        $content .= '<div class="form-group">
                                           
                                            <label class="col-sm-3 control-label">Choose Investment:</label>
                                            <div class="col-sm-6">
                                          <select id="inv_id" name="inv_id" onchange=\'xajax_purchase_date(document.getElementById("inv_id").value, "investments"); \' class="form-control">'.$invest.'</select>
                                            </div></div>'; 
                                            
                                          $content .= '<div class="form-group">
                                           
                                            <label class="col-sm-3 control-label">Destination Bank Account:</label>
                                            <div class="col-sm-6">
                                          <select id="bank_acct_id" name="bank_acct_id" class="form-control">'.$accts.'</select><div id="purchase_date">
                                            </div></div>';    
                              
                                        $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Date:</label>
                                            <div class="col-sm-6">
                                           <input type="text" class="form-control" id="date" name="date" placeholder="date" />
                                            </div></div>'; 
                                            
                                          $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Amount Paid:</label>
                                            <div class="col-sm-6">
                                           <input onkeyup="format_as_number(this.id)" type="numeric" name="amount" id="amount">
                                            </div></div>';
                                                                                      
                                          $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Receipt No:</label>
                                            <div class="col-sm-6">
                                           <input type="text" name="rcptno" id="rcptno">
                                            </div></div>'; 
                              
                                           $content .= "<div class='panel-footer'>
                                            <button type='button' class='btn btn-default' onclick=\"xajax_saleInvestmentForm()\">Reset</button> <button type='button' class='btn btn-primary' onclick=\"xajax_saleInvestment(getElementById('inv_id').value, getElementById('bank_acct_id').value, getElementById('amount').value, getElementById('rcptno').value, getElementById('date').value); return false;\">Save</button>";
                             
                $resp->assign("display_div", "innerHTML", $content);
                return $resp;
        }
        else
        {
                $cont = "<font color='red'>There are no investments for sale at the moment.</font>";
                $resp->assign("status", "innerHTML", $cont);
                return $resp;
        }
}

/*function purchased_date($inv_id, $table){
	$resp = new xajaxResponse();
	$check = mysql_fetch_array(mysql_query("select * from ".$table." where id='".$inv_id."'"));
	$resp->assign("purchase_date", "innerHTML", "Purchase Date: ".$check['date']);
	return $resp;
}
*/
function saleInvestment($inv_id, $bank_acct_id, $amount, $rcptno, $date)
{       $content='';
	$resp = new xajaxResponse();
       $amount=str_ireplace(",","",$amount);
		$check = mysql_fetch_array(mysql_query("select * from investments where id='".$inv_id."'"));
        if ($bank_acct_id == '' || $amount == '' || $rcptno == '')
        {
            $resp->alert("You must fill in all the fields.");
            return $resp;
        }elseif($date <= $check['date']){
			$resp->alert("Transaction rejected! You have entered a date before the purchase date");
			return $resp;
		}
		$res = @mysql_query("SELECT receipt_no FROM payment where receipt_no='".$rcptno."' UNION SELECT receipt_no FROM deposit where receipt_no='".$rcptno."' UNION SELECT receipt_no from collected where receipt_no='".$rcptno."' UNION SELECT receipt_no from shares where receipt_no='".$rcptno."' UNION SELECT receipt_no from other_income where receipt_no='".$rcptno."' UNION SELECT receipt_no from recovered where receipt_no='".$rcptno."' union select receipt_no from sold_asset where receipt_no='".$rcptno."' union select receipt_no from sold_invest where receipt_no='".$rcptno."'");
		if(mysql_numrows($res) > 0){
			$resp->alert("Sale rejected! \nReceipt No already registered");
			return $resp;
		}
        $bank_row = @mysql_fetch_array(@mysql_query("select min_balance, account_balance from bank_account where id = $bank_acct_id"));
        $new_bal = intval($bank_row[account_balance]) + $amount;
        @mysql_query("START TRANSACTION");
        $bal_res = @mysql_query("UPDATE bank_account set account_balance = $new_bal where id = $bank_acct_id");
        if (isset($bank_res) && @mysql_affected_rows() == -1)
        {
                @mysql_query("ROLLBACK");
                $content .= "<font color=red>ERROR: Failed to update bank account. ".mysql_error()."</font>";
                $resp->assign("status", "innerHTML", $content);
                return $resp;
        }
        else
        {
                $res = mysql_query("INSERT INTO sold_invest (amount, receipt_no, investment_id, date, bank_account) VALUES ($amount, '".$rcptno."', $inv_id, '".$date."', $bank_acct_id)");
                if (isset($res) && @mysql_affected_rows() > 0)
                {
                        @mysql_query("COMMIT");
                        $content .= "<font color='green'>Sale of fixed investment successfully registered.</font><br>";
                        $resp->assign("status", "innerHTML", $content);
                        $resp->call('xajax_listSoldInvestments', '', '', '', '', '', '');
                        return $resp;
                }
                else
                {
                        $content .= "<font color=red>ERROR: Failed to register sale of investment</font>";
						mysql_query("ROLLBACK");
                        $resp->assign("status", "innerHTML", $content);
                        return $resp;
                }
        }

}
/*
function listSoldInvestments($from_date,$to_date)
{
	$resp = new xajaxResponse();
        $resp->assign("status", "innerHTML", "");
        $content .="<center><font color=#00008b size=3pt><b>LIST OF SOLD INVESTMENTS</b></font></center>
                            From ".month_array('from_', $from_date, $from_month). mday('from_', $from_mday)." To ".month_array('to_', $to_year, $to_month).mday('to_', $to_date)."<input type=button value='Search' onclick=\"xajax_listSoldInvestments(getElementById('from_year').value, getElementById('from_month').value, getElementById('from_mday').value, getElementById('to_year').value, getElementById('to_month').value, getElementById('to_mday').value)\">
								<table height=100 border='0' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>";
        if($from_date == '' && $to_date==''){
                $content .="
                <tr bgcolor=lightgrey><td><font color=red>Select the period for the sold investments!</font></td></tr></table>";
                $resp->assign("display_div", "innerHTML", $content);
                return $resp;
        }
        $from_date = sprintf("%02d-%02d-%02d", $from_date, $from_month, $from_mday);
        $to_date = sprintf("%02d-%02d-%02d", $to_year, $to_month, $to_date);

        //$res = @mysql_query("select ac.name, ac.account_no as account_no, inv.date as pur_date, inv.amount as pur_value, si.id as si_id, si.date as sale_date, si.amount as sale_value from accounts ac join investments inv on ac.id = inv.account_id join sold_invest si on inv.id = si.investment_id where si.date between '".$from_date."' and '".$to_date."' order by si.date desc");
		$res = mysql_query("select ac.name, ac.account_no as account_no, inv.date as pur_date, inv.amount as pur_value, si.id as si_id, si.date as sale_date, si.amount as sale_value from  sold_invest si join accounts ac on ac.id = si.account_id where si.date >= '".$from_date."' and si.date <= '".$to_date."' order by si.date desc");

		if (mysql_num_rows($res) > 0)
        {
         //       $content .= "<font color=blue>List of sold investments</font><br>";
                $content .= "
                            <table height='100' border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>
                            <tr class='headings'>
                                <th align=center>Name of Investment</th><th align=center>Purchase Date</th><th align=center>Purchase Value</th><th align=center>Sale Date</th><th align=center>Sale Value</th><th></th>
                            </tr>
                            ";
				$i=0;
                while ($row = @mysql_fetch_array($res))
                {
					$color=($i%2 == 0) ? "lightgrey" : "white";
                     $content .= "
                               <tr bgcolor=$color>
                                        <td align=center>$row[account_no] - $row[name]</td><td align=center>$row[pur_date]</td><td align=center>$row[pur_value]</td><td align=center>$row[sale_date]</td><td align=center>$row[sale_value]</td><td align=center><a href='#' onclick=\"xajax_editSoldInvestmentsForm('".$row[si_id]."', '".$from_date."', '".$from_month."', '".$from_mday."', '".$to_year."', '".$to_month."', '".$to_date."'); return false;\"><img src='images/btn_edit.jpg'></a><a href='#' onclick=\"xajax_deleteSoldInvestment2('".$row[si_id]."', '".$from_date."', '".$from_month."', '".$from_mday."', '".$to_year."', '".$to_month."', '".$to_date."'); return false;\"><img src='images/btn_delete.jpg'></a></td>
                                    </tr>
                                    ";
                }
                $content .= "<tr bgcolor=#cdcdcd><td colspan='6'>&nbsp;</td></tr>";
                $resp->assign("display_div", "innerHTML", $content);
                return $resp;
        }
        else
        {
                $content .= "<font color=red>No Investments have been sold yet.</font><br>";
                $resp->assign("display_div", "innerHTML", $content);
                return $resp;
        }

}
*/
function listSoldInvestments($from_date,$to_date)
{

        list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");

        $content = '
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <p><h5 class="panel-title">SEARCH FOR SOLD INVESTMENTS</h5></p>
                                        
                            <div class="panel-body">
                                                        
                   <div class="form-group">
                                        <div class="col-sm-4">
                                            <label class="control-label">Date range:</label>
                                           
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                              </div> <div class="col-sm-4">';
                                           $content .= "<label class='control-label'>.</label>
                                           <input type='button' class='btn  btn-primary' value='Search' onclick=\"xajax_listSoldInvestments(getElementById('from_date').value, getElementById('to_date').value)\">
                           
                                    </div>
                                </div>";
                                                                                        
	$content .= "                             
                                
                                
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                     $resp->call("createDate","from_date");
		     $resp->call("createDate","to_date"); 
                    
                    // $resp->assign("display_div", "innerHTML", $content);
        
        
        if($from_date == '' || $to_date==''){
                
                $cont= "<font color=red>Select the period for the sold investments!</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
        }
        
      
       else{
		 $from_date = sprintf("%04d-%02d-%02d", $from_date, $from_month, $from_mday);
       		 $to_date = sprintf("%04d-%02d-%02d", $to_year, $to_month, $to_date);
       
		$res = mysql_query("select ac.id as acc_id, ac.account_no as account_no, ac.name as acc_name, r.purchase_price as purchase_price, r.quantity as quantity, r.id as rid, r.amount as amount, r.date as mdate from accounts ac join sold_invest r on r.account_id = ac.id where r.date >='".$from_date."' and r.date <= '".$to_date."' order by r.date desc");
		
		$buy = mysql_fetch_array(mysql_query("select count(id) as count from investments"));
		$sell = mysql_fetch_array(mysql_query("select count(id) as count from sold_invest"));
		$buy = ($buy['count'] == NULL) ? 0 : $buy['count'];
		$sell = ($sell['count'] == NULL) ? 0 : $sell['count'];
	if (@mysql_num_rows($res) > 0)
	{
	
	        $content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="semibold text-primary mt0 mb5">LIST OF SOLD INVESTMENTS</h3>
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
		$content .= "<thead>
			        <th>ACCOUNT</th><th>UNIT PURCHASE-PRICE</th><th>UNIT SALE-PRICE</th><th>QUANTITY</th><th>TOTAL SALE</th><th>PURCHASE DATE</th><th>COLLECTION A/C</th><th><th></th>
			   </thead><tbody>
			    ";
		$i=0;
		$balance = $buy - $sell;
		while ($fxrow = @mysql_fetch_array($res))
		{
			$bank = mysql_fetch_array(mysql_query("select b.bank, a.account_no, a.name from sold_invest i join bank_account b on i.bank_account=b.id join accounts a on b.account_id=a.id where i.id='".$fxrow['rid']."'"));
			$int = mysql_fetch_array(mysql_query("select sum(amount) as amount from other_income where account_id='".$fxrow['acc_id']."'"));
			$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;
			//$color = ($i%2 == 0) ? "lightgrey" : "white";
			
			$content .= "
				    <tr>
				        <td>".$fxrow['account_no'] ."-" .$fxrow['acc_name']."</td><td>".number_format($fxrow['purchase_price'])."</td><td>".number_format($fxrow['amount'])."</td><td>".$fxrow['quantity']."</td><td>".number_format($fxrow['amount']* $fxrow['quantity'])."</td><td>".$fxrow['mdate']."</td><td>".$bank['account_no']. " - ".$bank['bank']." ".$bank['name']."</td><td><a  onclick=\"xajax_editSoldInvestmentsForm('".$fxrow['rid']."', '".$from_date."','".$to_date."'); return false;\">Edit</a>&nbsp;<a  onclick=\"xajax_deleteSoldInvestment2('".$fxrow['rid']."', '".$from_date."','".$to_date."'); return false;\">Delete</a></td>
				    </tr>
				    ";		
		}
		$content .= "</tbody></table></div>";
		$resp->call("createTableJs");
	}

        else {
		$cont = "<font color=red>No Sold Investments Recorded Yet!</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
	}
	}
	
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function editSoldInvestmentsForm($si_id, $from_date,$to_date)
{       $accts='';
        $resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
        $acc = @mysql_query("select ac.name, ac.id as acc_id, ac.account_no, ba.bank, ba.id as bank_id from accounts ac join bank_account ba on ac.id = ba.account_id");
        $bank = @mysql_fetch_array(@mysql_query("select bank_account from sold_invest where id = $si_id"));
        if (@mysql_num_rows($acc) > 0)
        {
                $accts .= "<option value=''>&nbsp;</option>";
                while ($acc_row = @mysql_fetch_array($acc))
                {
                        if ($bank['bank_account'] == $acc_row['bank_id'])
                                $accts .= "<option value='".$acc_row['bank_id']."' selected>".$acc_row['account_no']." -".$acc_row['bank']."".$acc_row['name']."</option>";
                        else
                                $accts .= "<option value='".$acc_row['bank_id']."'>".$acc_row['name']."</option>";
                }
        }
        else
        {
                $accts .= "<option value=''>[No Account]&nbsp;</option>";
        }
        $res = @mysql_query("select ac.name, ac.account_no, inv.account_id, inv.amount, inv.quantity, inv.purchase_price, inv.receipt_no, inv.bank_account, date_format(inv.date, '%Y') as year, date_format(inv.date, '%m') as month, date_format(inv.date, '%d') as mday from accounts ac join sold_invest inv on ac.id = inv.account_id where inv.id='".$si_id."'");  
	if (@mysql_num_rows($res) > 0)
        {
                $row = @mysql_fetch_array($res);
                                
                $content ="<form method='post' class='panel form-horizontal form-bordered'>";
$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h4 class="semibold text-primary mt0 mb5">EDIT SALE OF INVESTMENT:</b>'.$row['account_no'] .'-'.$row['name'].'</h4>
                                               		 <p class="text-default nm"></p>
                                           	 </div>
                                        </div>';
                 $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Unit Purchase-Price:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" id="purchase_price" name="purchase_price" value="'.$row['purchase_price'].'">';                       
                                        
                $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Unit Sale-Price:</label>
                                            <div class="col-sm-6">
                                           <input onkeyup="format_as_number(this.id)" type="numeric" id="amount" name="amount" value="'.$row['amount'].'" class="form-control">
                                            </div></div>';
                                            
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Quantity:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" id="quantity" name="quantity" value="'.$row['quantity'].'" class="form-control">
                                            </div></div>';
                                            
                 $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Receipt No:</label>
                                            <div class="col-sm-6">
                                           <input type="text" id="rcptno" name="rcptno" value="'.$row['receipt_no'].'" class="form-control">
                                            </div></div>';
                                            
                     $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Date:</label>
                                            <div class="col-sm-6">
                                           <input type="text" class="form-control" id="date" name="date" value="'.$row['mday']."/".$row['month']."/".$row['year'].'" />
                                            </div></div>';
                                            
                      $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Bank Account:</label>
                                            <div class="col-sm-6">
                                           <select name="bank_acct_id" id="bank_acct_id">'.$accts.'</select>
                                            </div></div>';
                                            
                      $content .= "<div class='panel-footer'><button type='reset' class='btn btn-default'  onclick=\"xajax_listSoldInvestments('".$from_date."','".$to_date."')\">Back</button>
                                            <button type='button' class='btn btn-primary' onclick=\"xajax_updateSoldInvestment2(".$si_id.", getElementById('purchase_price').value, ".$row['amount'].", getElementById('amount').value, getElementById('quantity').value, getElementById('rcptno').value, getElementById('bank_acct_id').value, getElementById('date').value,'".$from_date."','".$to_date."'); return false;\">Save</button>";
                                            $content .= '</div></form></div>';
                                            $resp->call("createDate","date");
                                                       
        }
                        
        else {
		$cont = "<font color=red><b>ERROR: No record found!</b></font>";
		$resp->assign("display_div", "innerHTML", $cont);
	//return $resp;
	}

        $resp->assign("display_div", "innerHTML", $content);
        return $resp;
}

function updateSoldInvestment2($si_id, $purchase_price, $prev_amount, $amount, $quantity, $rcptno, $bank_acct_id, $date, $from_date,$to_date)
{
        $resp = new xajaxResponse();
        $amount=str_ireplace(",","",$amount);
        if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
		$former = mysql_fetch_array(mysql_query("select account_id, quantity from sold_invest where id='".$si_id."'"));
		$acc_id = $former['account_id'];
		$prev_quantity = $former['quantity'];

		$stock = mysql_fetch_array(mysql_query("select sum(quantity) as quantity from sold_invest where account_id='".$acc_id."'"));
		$stock = ($stock['quantity'] == NULL) ? 0 : $stock['quantity'];

		$sold = mysql_fetch_array(mysql_query("select sum(quantity) as quantity from sold_invest where account_id='".$acc_id."'"));
		$sold = ($sold['quantity'] == NULL) ? 0 : $sold['quantity'] - $prev_quantity + $quantity;
	
		if($sold > $stock){
			$resp->alert("Update rejected! Stock not sufficient for this transaction.");
			return $resp;
		}elseif($purchase_price =='' || intval($purchase_price) == 0){
			$resp->alert("Please enter the correct the unit Purchase-Price");
			return $resp;
        }elseif ($si_id == '' || $amount == '' || $rcptno == '' || $bank_acct_id == '')
        {
                $resp->alert("Please do not leave any fields blank Incl. the bank account.");
                return $resp;
        }
        $row = @mysql_fetch_array(@mysql_query("select ba.account_balance, ba.min_balance, si.bank_account from bank_account ba join sold_invest si on ba.id = si.bank_account where si.id = $si_id"));
        $new_bal = $row[account_balance] - $prev_amount * $prev_quantity;
        if ($new_bal < $row[min_balance])
        {
                $resp->alert("Error: Update failed: Insufficient funds on original account.");
                return $resp;
        }
        $resp->ConfirmCommands(1, "Do you really want to update?");
        $resp->call('xajax_updateSoldInvestment', $si_id, $row['account_balance'], $purchase_price, $prev_amount, $amount, $quantity, $rcptno, $bank_acct_id, $row['bank_account'], $date, $from_date,$to_date);
        return $resp;
}

function updateSoldInvestment($si_id, $account_balance, $purchase_price, $prev_amount, $amount, $quantity, $rcptno, $bank_acct_id, $prev_bank_acct_id, $date, $from_date,$to_date)
{
        $resp = new xajaxResponse();
      if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
		$former = mysql_fetch_array(mysql_query("select account_id, quantity from sold_invest where id='".$si_id."'"));
		$acc_id = $former['account_id'];
		$prev_quantity = $former['quantity'];
	
        if ($prev_amount == $amount && $bank_acct_id == $prev_bank_acct_id)
        {
                @mysql_query("START TRANSACTION");
                $res = @mysql_query("UPDATE sold_invest set date = '".$date."', purchase_price='".$purchase_price."', receipt_no = '".$rcptno."', quantity='".$quantity."' where id = $si_id");
                if (isset($res) && @mysql_affected_rows() != -1)
                {
					$action = "UPDATE sold_invest set date = '".$date."', receipt_no = '".$rcptno."', purchase_price='".$purchase_price."', quantity='".$quantity."' where id = $si_id";
					mysql_query("insert into logs set user_id='".$_SESSION['user_id']."', time=NOW(),  action='".$_SESSION['name'].":  ".mysql_escape_string($action)."'");
                        @mysql_query("COMMIT");
                        $content .= "<font color='green'>Sale of Investment successfully updated.</font><br>";
                        $resp->assign("status", "innerHTML", $content);
                        $resp->call('xajax_listSoldInvestments', $from_date,$to_date);
                        return $resp;
                }
                else
                {
                        @mysql_query("ROLLBACK");
                        $content .= "<font color='red'>ERROR: Failed to update Sale of Investment.</font><br>";
                        $resp->assign("status", "innerHTML", $content);
                        return $resp;
                }
        }
        else
        {
                $new_bal = $account_balance - $prev_amount * $prev_quantity;
                @mysql_query("START TRANSACTION");
                $bank1_res = @mysql_query("UPDATE bank_account set account_balance = $new_bal where id = $prev_bank_acct_id");
                if (isset($bank1_res) && @mysql_affected_rows() != -1)
                {
                        $row = @mysql_fetch_array(@mysql_query("select account_balance from bank_account where id = $bank_acct_id"));
                        $cur_bal = $row[account_balance] + ($amount * $quantity);
                        $bank2_res = @mysql_query("UPDATE bank_account set account_balance = $cur_bal where id = $bank_acct_id");
                        if (isset($bank2_res) && @mysql_affected_rows() != -1)
                        {
                                $upd_res = @mysql_query("UPDATE sold_invest set bank_account = $bank_acct_id, amount = $amount, purchase_price='".$purchase_price."', date = '".$date."', receipt_no = '".$rcptno."' where id = $si_id");
                                if (isset($upd_res) && @mysql_affected_rows() != -1)
                                {
                                        @mysql_query("COMMIT");
                                        $content .= "<font color=green>Sale of Investment updated successfully.</font><br>";
                                        $resp->call('xajax_listSoldInvestments', $from_date,$to_date);
                                }
                                else
                                {
                                        @mysql_query("ROLLBACK");
                                        $content .= "<font color=red>ERROR: Failed to update sale of Investment.</font>";

                                }
                        }
                        else
                        {
                                @mysql_query("ROLLBACK");
                                $content .= "<font color=red>ERROR: Failed to update new bank balance.</font><br>";
                        }
                }
                else
                {
                        @mysql_query("ROLLBACK");
                        $content .= "<font color=red>ERROR: Failed to update bank balance.</font><br>";
                }
                $resp->assign("status", "innerHTML", $content);
                return $resp;
        }
}

function deleteSoldInvestment2($si_id, $from_date,$to_date)
{
        $resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
        $row = @mysql_fetch_array(@mysql_query("select ba.account_balance, ba.min_balance, si.bank_account, si.amount, (ba.account_balance - (si.amount * si.quantity)) as new_bal from bank_account ba join sold_invest si on ba.id=si.bank_account where si.id = '".$si_id."'"));

        if ($row['new_bal'] < $row['min_balance'])
        {
                $resp->alert("You cannot delete this sale: Insufficient funds on the account.");
                return $resp;
        }
        $resp->ConfirmCommands(1, "Do you really want to delete the sale?");
        $resp->call('xajax_deleteSoldInvestment', $si_id, $row['new_bal'], $row['bank_account'], $from_date,$to_date);
        return $resp;
}

function deleteSoldInvestment($si_id, $new_bal, $bank_acct_id, $from_date,$to_date)
{
        $resp = new xajaxResponse();
        if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
        @mysql_query("START TRANSACTION");
        $upd_res = @mysql_query("UPDATE bank_account set account_balance =".$new_bal." where id = '".$bank_acct_id."'");
        if (isset($upd_res) && @mysql_affected_rows() != -1)
        {
                $del_res = @mysql_query("delete from sold_invest where id = $si_id");
                if (isset($del_res) && @mysql_affected_rows() != -1)
                {
					$action = "delete from sold_invest where id = '".$si_id."'";
					mysql_query("insert into logs set user_id='".$_SESSION['user_id']."', time=NOW(),  action='".$_SESSION['name'].":  ".mysql_escape_string($action)."'");
                        @mysql_query("COMMIT");
                        $content .= "<font color=green>Sale of Investment successfully deleted.</font><br>";
                        $resp->call('xajax_listSoldInvestments', $from_date,$to_date);
                }
                else
                {
                        @mysql_query("ROLLBACK");
                        $content .= "<font color=red>ERROR: Failed to delete Sale of Investment.</font><br>";
                }
        }
        else
        {
                @mysql_query("ROLLBACK");
                $content .= "<font color=red>ERROR: Failed to update bank balance.</font><br>";
        }
        $resp->assign("status", "innerHTML", $content);
        return $resp;
}


/*function unique_rcpt($rcptno, $table='')
{
/*
	Check if a given rcpt_no has bn registered before.
	Tables currently containing receipt_no: payment, collected, deposit, shares, other_income, recovered
	returns false if rcpt_no has already bn registered or is an empty string
*/
/*
	if ($rcptno == '')
		return false;
	elseif ($table == '')
	{
		$res = @mysql_query("SELECT receipt_no FROM payment where receipt_no='".$rcptno."' UNION SELECT receipt_no FROM deposit where receipt_no='".$rcptno."' UNION SELECT receipt_no from collected where receipt_no='".$rcptno."' UNION SELECT receipt_no from shares where receipt_no='".$rcptno."' UNION SELECT receipt_no from other_income where receipt_no='".$rcptno."' UNION SELECT receipt_no from recovered where receipt_no='".$rcptno."'");
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
*/
?>
