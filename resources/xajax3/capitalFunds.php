<?php

$xajax->registerFunction("addFundForm");
$xajax->registerFunction("addFundForm1");
$xajax->registerFunction("addFund");
$xajax->registerFunction("listFund");
$xajax->registerFunction("editFundForm");
$xajax->registerFunction("updateFund");
$xajax->registerFunction("updateFund2");
$xajax->registerFunction("deleteFund2");
$xajax->registerFunction("deleteFund");

$xajax->registerFunction("registerPayment");
$xajax->registerFunction("registerPaymentForm");
$xajax->registerFunction("listPayments");
$xajax->registerFunction("editPaymentForm");
$xajax->registerFunction("updatePayment");
$xajax->registerFunction("deletePayment2");
$xajax->registerFunction("deletePayment");

$xajax->registerFunction("add_payable");
$xajax->registerFunction("insert_payable");


function add_payable($fund_id, $account, $contact, $from_date,$to_date, $branch_id)
{
	$resp = new xajaxResponse();
	//$fix = @mysql_query("select account_id, id from payable order by id");
	$level1 = @mysql_query("select id, account_no, name from accounts where  account_no >= '2131' and account_no <= '2139'");
	while ($level1row = @mysql_fetch_array($level1))
	{
		$level2 = @mysql_query("select id, account_no, name from accounts where account_no like '".$level1row['account_no']."%' and account_no >= '".$level1row['account_no']."01' and account_no <= '".$level1row['account_no']."99'");		
		if (@mysql_num_rows($level2) > 0) // there's a level2 account
		{
			while ($level2row = @mysql_fetch_array($level2))
			{
				$level3 = @mysql_query("select id, account_no, name from accounts where account_no like '".$level2row['account_no']."%' and account_no >= '".$level2row['account_no']."01' and account_no <= '".$level2row['account_no']."99' and id NOT in (select account_id from fixed_asset)");	
				if (@mysql_num_rows($level3) > 0) // there's a level3 account
				{
					while ($level3row = @mysql_fetch_array($level3))
					{
						$fixed_acc .= "<option value=$level3row[id]>$level3row[account_no] - $level3row[name]</option>";
					}
				}
				else /* Plain level2 accounts */
				{
					$fixed_acc .= "<option value=$level2row[id]>$level2row[account_no] - $level2row[name]</option>";
				}
			} // end while level2
		}
		else /* Plain level1 accounts */
		{	
			$fixed_acc .= "<option value=$level1row[id]>$level1row[account_no] - $level1row[name]</option>";
		}
	} // end while level1
	
	$acc = mysql_fetch_array(mysql_query("select ac.name, ac.account_no as account_no, f.amount as amount, f.branch_id as branch_id from other_funds f join accounts ac on f.account_id=ac.id where f.id='".$fund_id."'"));	

	$content .= "<center><font color=#00008b size=3pt><b>MAKE A RESERVE ACCOUNT TRANSACTION : ".$acc['account_no']." - ". strtoupper($acc['name'])." A PAYABLE</b></font></center>";
	$content .= "<form method=post><table height=100 border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='70%' id='AutoNumber2' align=center>
	<tr bgcolor=lightgrey><td>Amount </td><td>".number_format($acc['amount'], 2)."</td></tr>
		    <tr>
		        <td>Select Account Payable to Transfer To: (From Chart of Accts):</td><td><select id='acc_id' name='acc_id'>$fixed_acc</select</td>
		    </tr>
		    <tr>
		    	<td>Name of Creditor:</td><td><input type='text' name='creditor' id='creditor'></td>
		    </tr>
		    ";
	$content .= "
		    <tr>
			<td>Acquisition Date: </td><td>".month_array('acq_', '', '').mday('acq_', '')."</td>
		    </tr>
		    <tr>
		    	<td>Maturity Date: </td><td>".month_array('', '', '').mday('', '')."</td>
		    </tr>
		    ";
	
	$content .= "
		    <tr>
		    	<td></td><td><a href='javascript:;' onclick=\"xajax_listFund('".$account."', '".$contact."', '".$from_year."', '".$from_month."', '".$from_mday."', '".$to_year."', '".$to_month."', '".$to_mday."', '".$branch_id."')\"><img src='images/btn_back.jpg'></a>
<a href='javascript:;' onclick=\"xajax_add_payable('".$fund_id."', '".$account."', '".$contact."', '".$from_year."', '".$from_month."', '".$from_mday."', '".$to_year."', '".$to_month."', '".$to_mday."', '".$branch_id."')\"><img src='images/btn_clear.jpg'></a>&nbsp;<a href='javascript:;' onclick=\"xajax_insert_payable(document.getElementById('acc_id').value, '".$acc['amount']."', document.getElementById('creditor').value, '', getElementById('acq_year').value, getElementById('acq_month').value, getElementById('acq_mday').value, document.getElementById('year').value,document.getElementById('month').value, document.getElementById('mday').value, '".$acc['branch_id']."', '".$fund_id."'); return false;\"><img src='images/btn_enter.jpg'></a></td>
		    </tr>
		    </table></form>
		    ";
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function insert_payable($acc_id, $initval, $creditor, $bank_acct_id, $acq_year, $acq_month, $acq_mday, $year, $month, $mday, $branch_id, $fund_id)
{
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	//$date = date('Y-m-d');
	$trans = 0;
	$date = sprintf("%d-%02d-%02d", $year, $month, $mday);
	$date = $date." ".date('H:i:s');
	$acq_date = sprintf("%d-%02d-%02d", $acq_year, $acq_month, $acq_mday);

	$former_res = mysql_query("select * from other_funds where date >='".$date."' or date >= '".$acq_date."' and id='".$fund_id."'");
	if ($acc_id == '' || $initval == '' || $creditor == '')
	{
		$resp->alert('Please do not leave any field blank.');
	}
	elseif (!$calc->isValidDate($mday, $month, $year))
	{
		$resp->alert('Invalid Date. Most likely wrong date for Month: Feb.');
	}
	elseif ($calc->isPastDate($mday, $month, $year))
	{
		$resp->alert('Maturity Date can not be a past date.');
	}
	elseif ($calc->isFutureDate($acq_mday, $acq_month, $acq_year))
	{
		$resp->alert('Acquisition date cannot be a future date.');
	}
	elseif (mysql_numrows($former_res) > 0)
	{
		$resp->alert('Date entered is earlier than when the reserve fund was created');
	}
	else
	{
		mysql_query("START TRANSACTION");
		if ($bank_acct_id != '')   //LOANS PAYABLE
		{
			$brow = @mysql_fetch_array(@mysql_query("select * from bank_account where id = $bank_acct_id"));
			$new_bal = intval($brow['account_balance']) + $initval;
			$trans = 1;
				
			$bank_res = @mysql_query("update bank_account set account_balance = $new_bal where id = $bank_acct_id");
			if (isset($bank_res) && @mysql_affected_rows() == -1)
			{
				@mysql_query("ROLLBACK");
				$content .= "<font color=red>ERROR: Failed to update bank balance.</font>";
				$resp->assign("status", "innerHTML", $content);
				return $resp;
			}
		}	
		$res = @mysql_query("insert into payable (account_id, amount, vendor, bank_account, date, maturity_date, branch_id, fund_id) values ($acc_id, $initval, '".$creditor."', '".$bank_acct_id."', '".$acq_date."', '".$date."',".$branch_id.", '".$fund_id."')");
		
		if (@mysql_affected_rows() > 0)
		{
			$accno = mysql_fetch_assoc(mysql_query("select account_no,name from accounts where id=".$acc_id));
			mysql_query("update other_funds set date_payable='".$acq_date."' where id='".$fund_id."'");
			//////////////////
			$action = "insert into payable (account_id, amount, vendor, bank_account, date, maturity_date) values ($acc_id, $initval, '".$creditor."', '".$bank_acct_id."', '".$acq_date."', '".$date."')";
			$msg = "Registered payable of ".number_format($initval,2)." into ac/no: ".$accno['account_no']."-".$accno['name'];
			log_action($_SESSION['user_id'],$action,$msg);
			///////////////////
			mysql_query("commit");
			$content .= "<font color=blue>Payable registered successfully.</font><br>";
			$resp->assign("status", "innerHTML", $content);
			//$resp->call("xajax_listPayables", 'all', $from_date,$to_date);
			return $resp;
		}
		else
			$resp->alert("Error: Payable not registered.");
		mysql_query("rollback");
	}
	return $resp;
}


function addFundForm($prefix, $action)
{       $fixed_acc ='';
        $accts='';
        $modes='';
        $content ='';
        $type='';
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	$fix = @mysql_query("select account_id, id, account_no from other_income order by account_no");
	$level1 = @mysql_query("select id, account_no, name from accounts where account_no like '".$prefix."%' and account_no >= ".$prefix."1 and account_no <= ".$prefix."9");
	while ($level1row = @mysql_fetch_array($level1))
	{
		$level2 = @mysql_query("select id, account_no, name from accounts where account_no like '".$level1row['account_no']."%' and account_no >= ".$level1row['account_no']."01 and account_no <= ".$level1row['account_no']."99 and account_no <>'311101'");		
		if (@mysql_num_rows($level2) > 0) // there's a level2 account
		{
			while ($level2row = @mysql_fetch_array($level2))
			{
				$level3 = @mysql_query("select id, account_no, name from accounts where account_no like '".$level2row['account_no']."%' and account_no >= ".$level2row['account_no']."01 and account_no <= ".$level2row['account_no']."99 and id NOT in (select account_id from fixed_asset) and account_no not in ('311101', '311102')");	
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
							if ($fixrow[account_id] != $level2row[id])
								$fixed_acc .= "<option value='".$level2row['id']."'>".$level2row['account_no']." -".$level2row['name']."</option>";
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
					 if ($fixrow['account_id'] != $level1row['id'])
					 	$fixed_acc .= "<option value='".$level1row['id']."'>".$level1row['account_no']." -". $level1row['name']."</option>";
				}
				@mysql_data_seek($fix, 0);
			}
			else
				$fixed_acc .= "<option value='".$level1row['id']."'>".$level1row['account_no']." -". $level1row['name']."</option>";
		}
	} // end while level1
	
	if ($action == 'offset')
	{	
		$accts .= "<option value='0'>Not Applicable</option>";
		
	}
	else
	{
		//if (strtolower($_SESSION['position']) == 'manager')
			$acc = @mysql_query("select ba.id, ac.name, ac.account_no, ba.bank from accounts ac join bank_account ba on ac.id = ba.account_id");
		/*else
			$acc = @mysql_query("select ba.id, ac.name, ac.account_no, ba.bank from accounts ac join bank_account ba on ac.id = ba.account_id where ba.id in (select bank_account_id from user_account where user_id =".$_SESSION['user_id'].")");*/
		$accts .= "<option value=''>&nbsp;</option>";
		while ($accrow = @mysql_fetch_array($acc))
		{
			$accts .= "<option value='".$accrow['id']."'>".$accrow['account_no']." -". $accrow['bank']."". $accrow['name']."</option>";
		}
	}

	$modes .= $action == 'offset'? "<option value='offset' selected>Reserve Fund</option>" : "<option value='offset'>Reserve Fund</option>";
	$modes .= $action == 'cash'? "<option value='cash' selected>Cash/Cheque (only for Donations)</option>" : "<option value='cash'>Cash/Cheque  (only for Donations)</option>";
	

	$content .="<form method='post' class='panel form-horizontal form-bordered'>";
$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h4 class="semibold text-primary mt0 mb5">REGISTER CAPITAL FUND</h4>
                                               		 <p class="text-default nm"></p>
                                           	 </div>
                                        </div>';
$form_res = mysql_query("select * from accounts where account_no ='".$prefix."'");
	$form = mysql_fetch_array($form_res); 
	$sth = mysql_query("select account_no, name from accounts where account_no >='311' and account_no <= '399'");
	while($row = mysql_fetch_array($sth)){
		$type .= "<option value='".$row['account_no']."'>".$row['account_no']." - ".strtoupper($row['name']);
	}                                                                       	                                    
                                            $content .= '<div class="form-group">
                                           
                                            <label class="col-sm-3 control-label">Branch:</label>
                                            <div class="col-sm-6">
                                          '.branch().'
                                            </div></div>';                                         
                                             $content .= '<div class="form-group">
                                             
                                            <label class="col-sm-3 control-label">Type:</label>
                                            <div class="col-sm-6">
                                           <select id="ptype" name="ptype" class="form-control" onchange=\'xajax_addFundForm(getElementById("ptype").value, getElementById("mode").value); return false;\'><option value="'.$prefix.'">'.$prefix.' - '.$form['name'].$type.'</select>
                                            </div></div>';                                          
                                                                                                                                                      
                         
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Select Payment Mode:</label>
                                            <div class="col-sm-6">
                                         <select name="mode" id="mode" class="form-control" onchange=\'xajax_addFundForm("'.$prefix.'", getElementById("mode").value); return false;\'>'.$modes.'</select>
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
                                            <label class="col-sm-3 control-label">Contact Person:</label>
                                            <div class="col-sm-6">
                                          <input type="numeric" name="contact" id="contact" class="form-control">
                                            </div></div>';  
                                            
                                             $content .= '<div class="form-group">
                                             
                                            <label class="col-sm-3 control-label">Amount:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" name="amount" id="amount" class="form-control">
                                            </div></div>';                                          
                                                                                                                                                      
                         
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Receipt No:</label>
                                            <div class="col-sm-6">
                                         <input type="numeric" name="rcpt_no" id="rcpt_no" class="form-control">
                                            </div></div>';
                                            
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Cheque No (Optional):</label>
                                            <div class="col-sm-6">
                                         <input type="numeric" name="cheque_no" id="cheque_no" class="form-control">
                                            </div></div>'; 
                                                                                                                                                                                                                   
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Date:</label>
                                            <div class="col-sm-6">
                                            <input type="text" class="form-control" id="date" name="date" placeholder="" />
                                            </div></div>';                                         
                                             
                                            $content .= "<div class='panel-footer'>
                                            <button type='button' class='btn btn-default' onclick=\"xajax_addFundForm('".$prefix."', 'offset')\">Reset</button>&nbsp;<button type='button' class='btn btn-primary' onclick=\"xajax_addFund(document.getElementById('acc_id').value, document.getElementById('mode').value, document.getElementById('amount').value, getElementById('bank_acct').value, getElementById('contact').value, getElementById('rcpt_no').value, getElementById('cheque_no').value,  document.getElementById('date').value,getElementById('branch_id').value); return false;\">Save</button>";
	$resp->call("createDate","date");	    
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

/* function addIncomeForm1()
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
*/
function addFund($acc_id, $mode, $amount_paid, $bank_acct, $contact, $rcpt_no, $cheque_no, $date, $branch_id)
{
        list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$date = sprintf("%d-%02d-%02d", $year, $month, $mday);
	$date = $date." ".date('H:i:s');
	if ($acc_id == '' || $amount_paid == '' || $bank_acct == '')
	{
		$resp->alert('Please do not leave any field blank.');
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
	elseif (!unique_rcpt($rcpt_no, '', '') && $rcpt_no<>'')
	{
		$resp->alert("This receipt No has already been used.");
		return $resp;
	}
	else
	{
		//$mode = $mem_acc_id == 0 ? 'cash' : $mem_acc_id;
		start_trans();
		$res = mysql_query("insert into other_funds (account_id, amount, date, bank_account, contact, receipt_no, cheque_no, mode,branch_id) values ('$acc_id', $amount_paid, '".$date."', '$bank_acct', '$contact', '$rcpt_no', '$cheque_no', '$mode',".$branch_id.")");
		if ($mode == 'cash')
		{
			if (@mysql_affected_rows() > 0)
				if(mysql_query("update bank_account set account_balance=account_balance +".$amount_paid." where id='".$bank_acct."'")){
					$content .= "<font color=blue>Capital Fund registered successfully.</font><br>";
					//////////////////////
					$accno = mysql_fetch_assoc(mysql_query("select account_no,name from accounts where id='".$acc_id."'"));
					$action = "insert into other_funds (account_id, amount, date, bank_account, contact, receipt_no, cheque_no, mode) values ('$acc_id', $amount_paid, '".$date."', '$bank_acct', '$contact', '$rcpt_no', '$cheque_no', '$mode')";
					$msg = "Registered funds of: ".number_format($amount_paid,2)." on a/c ".$accno['account_no']." - ".$accno['name'];
			log_action($_SESSION['user_id'],$action,$msg);
			///////////////////
					commit();
				}else{
					$resp->alert("ERROR: Could not update bank account");
					rollback();
			}
			else{
				$content .= "Error: Capital Fund not registered.";
				rollback();
			}
		}else{
			$content .= "<font color=blue>Capital Fund registered successfully.</font><br>";
			commit();
		}
		
	}
	$resp->assign("status", "innerHTML", $content);
	return $resp;
}

function listFund($account, $contact, $from_date,$to_date,$branch_id)
{       
        list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	//$resp->assign("status", "innerHTML", "");
	$branch = ($branch_id=='all'||$branch_id=='')?NULL:"and branch_id=".$branch_id;
		
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <p><h5 class="semibold text-primary mt0 mb5">SEARCH FOR OTHER CAPITAL FUNDS</h5></p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Account:</label>
                                            <select id="account" class="form-control"><option value="">All';
	$sth = mysql_query("select distinct id as id, account_no, name from accounts where id in (select account_id from other_funds)");
	while($row = mysql_fetch_array($sth)){
		$content .= "<option value='".$row['id']."'>".$row['account_no'] ." - ".$row['name'];
	}
	$content .='</select>                                         
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Contact Person:</label>
                                            <input type="text" id="contact" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                
                   <div class="form-group">
                                    <div class="row">
                                       <div class="col-sm-6">
                                            <label class="control-label">Branch:</label>
                                            <span>'.branch_rep($branch_id).'</span>
	                                          
                                        </div>
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
                                
                                <input type='button' class='btn  btn-primary' value='Search' onclick=\"xajax_listFund(getElementById('account').value, getElementById('contact').value, getElementById('from_date').value, getElementById('to_date').value, getElementById('branch_id').value)\">
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    
        $resp->call("createDate","from_date");
	$resp->call("createDate","to_date");
               //$resp->assign("display_div", "innerHTML", $content);    
                   
	if($from_date =='' || $to_date ==''){
		
		$cont= "<font color=red>Select the period for the capital funds!</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59",  $to_year, $to_month, $to_mday);
	if ($account == '')
	{
		$res = @mysql_query("select r.bank_account, ac.id as acc_id, ac.name as acc_name, ac.account_no as account_no, r.id as rid, r.amount as amount, r.contact as contact, r.date as mdate, r.contact as contact, r.receipt_no, r.cheque_no, r.mode from accounts ac right join other_funds r on r.account_id = ac.id where r.date>= '".$from_date."' and r.date <= '".$to_date."' and r.contact like '%".$contact."%' ".$branch." order by r.date desc");
	}else{
		$res = @mysql_query("select r.bank_account, ac.id as acc_id, ac.name as acc_name, ac.account_no as account_no, r.id as rid, r.amount as amount, r.contact as contact, r.date as mdate, r.contact as contact, r.receipt_no, r.cheque_no, r.mode from accounts ac right join other_funds r on r.account_id = ac.id where r.date>= '".$from_date."' and r.date <= '".$to_date."' and r.account_id='".$account."' and r.contact like '%".$contact."%' ".$branch." order by r.date desc");
	}
	
	if (@mysql_num_rows($res) > 0)
	{
		/*$content .= "
			   <a href='list_funds.php?account=".$account."&branch_id=".$branch_id."&contact=".$contact."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."' target=blank()><b>Printable Version</b></a> | <a href='list_funds.php?account=".$account."&branch_id=".$branch_id."&contact=".$contact."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&format=excel' target=blank()><b>Export Excel</b></a> | <a href='list_funds.php?account=".$account."&branch_id=".$branch_id."&contact=".$contact."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&format=word' target=blank()><b>Export Word</b></a>";*/
			   
			   
			  $content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">LIST OF OTHER CAPITAL FUNDS</h3>
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">
			    <thead>
			        <th>Date</th><th>Account</th><th>Amount</th><th>Receipt No./Cheque No.</th><th>Payment Mode</th><th>Contact Person</th><th>Bank Account</th><th>Account Payable</th><th></th>
			    </thead> <tbody>
			    ';
		$i=0;
		$balance=0;
		while ($fxrow = @mysql_fetch_array($res))
		{
			//if (strtolower($type) == 'all' || strtolower($type) == 'cleared')
			//{
			$mode = ($fxrow['mode'] == 'cash') ? $fxrow['mode'] : 'Offset from Savings' ;
			//$color = ($i%2 == 0) ? "lightgrey" : "white";
			$bank = mysql_fetch_array(mysql_query("select b.bank as bank, a.name as name, a.account_no from bank_account b join accounts a on b.account_id=a.id where b.id='".$fxrow['bank_account']."'"));

			$pay = mysql_fetch_array(mysql_query("select a.name as name, a.account_no as account_no from payable p join accounts a on p.account_id=a.id where fund_id='".$fxrow['rid']."'"));
			$payable = ($pay['account_no'] == NULL) ? "<a href='javascript:;' onclick=\"xajax_add_payable('".$fxrow['rid']."', '".$account."', '".$contact."', '".$from_date."','".$to_date."', '".$branch_id."'); return false;\">Make Payable</a>" : $pay['account_no'] ." - ". $pay['name']; 
			$content .= "
				    <tr>
				        <td>".$fxrow['mdate']."</td><td>$fxrow[account_no] - $fxrow[acc_name]</td><td>".number_format($fxrow[amount], 2)."</td><td>$fxrow[receipt_no] $fxrow[cheque_no]</td><td>$mode</td><td>$fxrow[contact]</td><td>".$bank['account_no'] ."-". $bank['bank']." ".$bank['name']."</td><td>".$payable."</td><td><a href='javascript:;' onclick=\"xajax_editFundForm('".$fxrow['rid']."', '".$type."', '".$account."', '".$contact."','".$from_date."','".$to_date."', '".$branch_id."'); return false;\">Edit</a>&nbsp;<a href='javascript:;' onclick=\"xajax_deleteFund2('".$fxrow['rid']."', '".$fxrow['bank_account']."', '".$fxrow['amount']."', '".$type."', '".$account."', '".$contact."',  '".$from_date."','".$to_date."','".$branch_id."'); return false;\">Delete</a></td>
				    </tr>
				    ";	
					$balance += $fxrow['amount'];
			//}
			$i++;
		}
		$content .= "<tr><td>Total</td><td></td><td>".number_format($balance, 2)."</td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table></div>";
	}
	
	else{
		$cont = "<font color=red>No Fund registered yet.</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
	}
	}
	$resp->call("createTableJs");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function editFundForm($pid, $type, $account, $contact, $from_date,$to_date,$branch_id)
{       
        $content='';
	$resp = new xajaxResponse();
	/*if($_SESSION['position'] <>'Manager'){
		$resp->alert("Access Denied! \nOnly the Manager can edit an item");
		return $resp;
	}*/
		
	$sth = mysql_query("select * from payable where fund_id='".$pid."'");
	if(mysql_numrows($sth) > 0){
		$resp->alert("Access Denied! \nThis transaction is already a payable, first delete the payable");
		return $resp;
	}
	$res = @mysql_query("select  ac.name, r.id, r.contact as contact, r.bank_account as bank_account, r.amount, r.account_id, r.cheque_no, r.mode, r.receipt_no, date_format(date, '%Y') as year, date_format(date, '%m') as month, date_format(date, '%d') as mday from  other_funds r join accounts ac on ac.id = r.account_id where r.id = $pid");

	if (@mysql_num_rows($res) > 0)
	{
	       		
		$row = @mysql_fetch_array($res);
		
		$content .="<form method='post' class='panel form-horizontal form-bordered'>";
$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h4 class="semibold text-primary mt0 mb5">EDIT CAPITAL FUNDS</h4>
                                               		 <p class="text-default nm"></p>
                                           	 </div>
                                        </div>';
                                       
			    
		if ($row['mode'] == 'cash')
		{
			
			 $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Contact Person:</label>
                                            <div class="col-sm-6">
                                          <input type="numeric" name="contact" id="contact" value="'.$row['contact'].'" class="form-control">
                                            </div></div>';  
                                            
                                             $content .= '<div class="form-group">
                                             
                                            <label class="col-sm-3 control-label">Amount:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" name="amount" id="amount" value="'.$row['amount'].'" class="form-control">
                                            </div></div>';                                          
                                                                                                                                                                               
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Receipt No:</label>
                                            <div class="col-sm-6">
                                         <input type="numeric" name="rcpt_no" id="rcpt_no"  value="'.$row['receipt_no'].'" class="form-control">
                                            </div></div>';
                                            
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Cheque No (Optional):</label>
                                            <div class="col-sm-6">
                                         <input type="numeric" name="cheque_no" id="cheque_no" value="'.$row['cheque_no'].'" class="form-control">
                                            </div></div>'; 
                                                                                                                                                                                                                   
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Date:</label>
                                            <div class="col-sm-6">
                                            <input type="text" class="form-control" id="date" name="date" value="'.$row['year']."-".$row['month']."-".$row['mday'].'"/>
                                          
                                            </div></div>';
                                            
                                         $content .= "<div class='panel-footer'><button class='btn btn-default' onclick=\"xajax_listFund('".$account."', '".$contact."', '".$from_date."','".$to_date."','".$branch_id."')\">Back</button>&nbsp;<button class='btn btn-default' onclick=\"xajax_editFundForm('".$pid."', '".$type."', '".$from_date."','".$to_date."', '".$contact."', '".$branch_id."')\">Edit</button>&nbsp;<button class='btn btn-primary' onclick=\"xajax_updateFund2('".$row['id']."', '".$row['name']."', '".$row['bank_account']."', document.getElementById('amount').value, getElementById('rcpt_no').value, getElementById('cheque_no').value, '', document.getElementById('date').value,document.getElementById('contact').value); return false;\">Save</button>";
		}
		else
		{    
								
		 $mems='';
			$mem_res = mysql_query("select m.first_name, m.last_name, m.mem_no, ma.id as memacc_id, a.name from member m join mem_accounts ma on m.id = ma.mem_id join savings_product p on ma.saveproduct_id = p.id join accounts a on p.account_id = a.id where lower(a.name) <> 'compulsory savings' and lower(a.name) <> 'compulsory shares' and ma.id =".$row['id']." order by m.first_name, m.last_name");
			$mems .= "<option value=''>&nbsp;</option>";
			while ($mem_row = mysql_fetch_array($mem_res))
			{
				$mems .= ($mem_row['memacc_id'] == $row['id']) ? "<option value='".$mem_row['memacc_id']."' selected>".$mem_row['first_name']."". $mem_row['last_name']." -". $mem_row['mem_no']." -". "(".$mem_row['name'].")"."</option>" : "<option value='".$mem_row['memacc_id']."'>".$mem_row['first_name']."". $mem_row['last_name']." -". $mem_row['mem_no']." -"." (".$mem_row['name'].")"."</option>";
			}
						
			 $content .= '<div class="form-group">
                                             
                                            <label class="col-sm-3 control-label">Amount:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" name="amount" id="amount" value="'.$row['amount'].'" class="form-control">
                                            </div></div>';                                                                                                          
                         
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Select Paying Member:</label>
                                            <div class="col-sm-6">
                                         <select name="mem_acc_id" id="mem_acc_id" class="form-control">'.$mems.'</select>
                                            </div></div>';                                          
                                                                                   
                                                                                                                                                                                                                   
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Contact Person:</label>
                                            <div class="col-sm-6">
                                          <input type="numeric" name="contact" id="contact" value="'.$row['contact'].'" class="form-control">
                                            </div></div>';                                              
                                                                                                                                                                                                                                                              
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Receipt No:</label>
                                            <div class="col-sm-6">
                                         <input type="numeric" name="rcpt_no" id="rcpt_no"  value="'.$row['receipt_no'].'" class="form-control">
                                            </div></div>';
                                            
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Cheque No (Optional):</label>
                                            <div class="col-sm-6">
                                         <input type="numeric" name="cheque_no" id="cheque_no" value="'.$row['cheque_no'].'" class="form-control">
                                            </div></div>'; 
                                                                                                                                                                                                                   
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Date:</label>
                                            <div class="col-sm-6">
                                            <input type="text" class="form-control" id="date" name="date" value="'.$row['year']."-".$row['month']."-".$row['mday'].'"/>                                        
                                            </div></div>';                                         
                                             
                                            $content .= "<div class='panel-footer'><button class='btn btn-default' onclick=\"xajax_listFund('".$account."', '".$contact."', '".$from_date."','".$to_date."','".$branch_id."')\">Back</button>&nbsp;<button class='btn btn-default' onclick=\"xajax_editFundForm('".$pid."', '".$type."', '".$from_date."','".$to_date."', '".$contact."', '".$branch_id."')\">Reset</button>&nbsp<button class='btn btn-primary' onclick=\"xajax_updateFund2('".$row['id']."', '".$row['name']."', '".$row['bank_account']."', document.getElementById('amount').value, getElementById('rcpt_no').value, getElementById('cheque_no').value, getElementById('mem_acc_id').value, document.getElementById('date').value,document.getElementById('contact').value); return false;\">Update</button>";
		}
		  
		$content .= "</div></form></div></div>";
	}
	else{
		$cont = "
			    <font color=red><b>ERROR: Fund not found!</b></font>
			    ";
			    $resp->assign("status", "innerHTML", $cont);
	return $resp;
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function updateFund2($pid, $pname, $bank_account, $amount, $rcpt_no, $cheque_no, $mem_acc_id, $date, $contact)
{       
        list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	if ($pid == '' || $amount == '' || $rcpt_no == '')
		$resp->alert('Please do not leave any fields blank.');
	elseif ($rcpt_no == '' && $cheque_no == '' && $mem_acc_id == '')
	{
		$resp->alert("Please fill in either a receipt No or Cheque No.");	
	}
	elseif ($calc->isFutureDate($mday, $month, $year))
		$resp->alert('Date can not be a future date');
	elseif (!unique_rcpt($rcpt_no, 'other_funds', $pid)){

		$resp->alert("Receipt has already been used.");
	}else
	{
		$resp->ConfirmCommands(1, "Do you really want to update?");
		$resp->call('xajax_updateFund', $pid, $pname, $bank_account, $amount, $rcpt_no, $cheque_no, $mem_acc_id, $date, $contact);
	}
	return $resp;
}

function updateFund($pid, $pname, $bank_account, $amount, $rcpt_no, $cheque_no, $mem_acc_id, $date, $contact)
{       
        list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$former = mysql_fetch_array(mysql_query("select * from other_funds where id='".$pid."'"));
	$date = sprintf("%d-%02d-%02d", $year, $month, $mday);
	$date = $date." ".date('H:i:s');
	$mode = ($mem_acc_id == '') ? 'cash' : $mem_acc_id;
	start_trans();
//	$res = @mysql_query("update other_income set date='".$date."', amount=$amount, receipt_no='".$rcpt_no."', cheque_no = '".$cheque_no."', mode = '".$mode."', contact='$contact' where id=$pid");
	
	if (mysql_query("update other_funds set date='".$date."', amount=$amount, receipt_no='".$rcpt_no."', cheque_no = '".$cheque_no."', mode = '".$mode."', contact='$contact' where id=$pid")){
		if ($mode == 'cash')
		{
			if(mysql_query("update bank_account set account_balance=account_balance -".$former['amount']."+".$amount." where id='".$bank_account."'")){
				//////////////////////
				$accno = mysql_fetch_assoc(mysql_query("select a.account_no,a.name from accounts a join other_funds f on a.id=f.account_id where f.id='".$pid."'"));
				$action = "update other_funds set date='".$date."', amount=$amount, receipt_no='".$rcpt_no."' where id=$pid";
				$msg = "Updated Other_Funds, set date:".$date.", amount:".number_format($amount,2)." on ac/no:".$accno['account_no']." - ".$accno['name'];
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
			commit();
			$content .= "<font color=red><b>$pname updated successfully.</b></font><br>";
		}
	}else{
		$content .= "<font color=red><b>ERROR: $pname not updated!</b> ".mysql_error()."</font><br>";
		commit();
	}
	$resp->assign("status", "innerHTML", $content);
	//$resp->call('xajax_listIncome', $account, $contact, $from_date,$to_date);
	return $resp;
}

function deleteFund2($pid, $bank_account, $amount, $account, $contact, $from_date,$to_date, $contact,$branch_id)
{
	$resp = new xajaxResponse();
	/*if($_SESSION['position'] <>'Manager'){
		$resp->alert("Access Denied! \nOnly the Manager can edit an item");
		return $resp;
	}*/
	$sth = mysql_query("select * from payable where fund_id='".$pid."'");
	if(mysql_numrows($sth) > 0){
		$resp->alert("Access Denied! \nThis transaction is already a payable, first delete the payable");
		return $resp;
	}
	if (@mysql_num_rows(@mysql_query("select * from other_funds where id = $pid")) < 1)
		$resp->alert("Cannot delete: Fund entry not found.");
	else
	{
		$resp->ConfirmCommands(1, "Do you really want to delete?");
		$resp->call('xajax_deleteFund', $pid, $bank_account, $amount, $account, $contact, $from_date,$to_date, $branch_id);
	}
	//$resp->assign("status", "innerHTML", "test");
	return $resp;
}


function deleteFund($pid, $bank_account, $amount, $account, $contact, $from_date,$to_date,$branch_id)
{
	$resp = new xajaxResponse();
	
	start_trans();

	$accno =mysql_fetch_assoc(mysql_query( "select a.account_no,a.name from accounts a join other_funds f on a.id=f.account_id where f.id='".$pid."'"));
	$res = @mysql_query("delete from other_funds where id = $pid");
	if (@mysql_affected_rows() > 0)
		if(mysql_query("update bank_account set account_balance=account_balance -".$amount." where id='".$bank_account."'")){
		//////////////////////
			
			$action = "delete from other_funds where id = $pid";
			$msg = "Deleted  from other_funds a sum of: ".number_format($amount,2)." from ac/no: ".$accno['account_no']." - ".$accno['name'];
			log_action($_SESSION['user_id'],$action,$msg);
			///////////////////
			commit();
			$content .= "<font color=green><b>Capital Fund deleted successfully.</b></font><br>";
		}
	else{
		rollback();
		$content .= "<font color=green><b>Error: Failed to delete Income. </b></font><br>";
	}

	$resp->assign("status", "innerHTML", $content);
	$resp->call('xajax_listFund', $account, $contact, $from_date,$to_date, $branch_id);
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
	elseif ($table == 'other_funds') 
	{
		$res = @mysql_query("SELECT receipt_no FROM payment where receipt_no='".$rcptno."' UNION SELECT receipt_no FROM deposit where receipt_no='".$rcptno."' UNION SELECT receipt_no from collected where receipt_no='".$rcptno."' UNION SELECT receipt_no from shares where receipt_no='".$rcptno."' UNION SELECT receipt_no from other_income where receipt_no =='".$rcptno."' UNION SELECT receipt_no from other_funds where receipt_no =='".$rcptno."' and id<>'".$pid."' UNION SELECT receipt_no from recovered where receipt_no='".$rcptno."'");
		if (@mysql_num_rows($res) > 0)
			return false;
		else
			return true;
	}	
			
}

?>
