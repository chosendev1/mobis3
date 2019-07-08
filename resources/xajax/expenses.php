<?php
$xajax->registerFunction("addBudgetExpenseForm");
$xajax->registerFunction("addBudgetIncomeForm");
$xajax->registerFunction("insertBudgetIncomeForm");
$xajax->registerFunction("addExpenseForm");
$xajax->registerFunction("addExpenseForm1");
$xajax->registerFunction("addExpense");
$xajax->registerFunction("listExpenses");
$xajax->registerFunction("editExpenseForm");
$xajax->registerFunction("updateExpense");
$xajax->registerFunction("updateExpense2");
$xajax->registerFunction("deleteExpense2");
$xajax->registerFunction("deleteExpense");


function addBudgetExpenseForm($prefix)
{       $fixed_acc='';
        $accts ='';
        $type  ='';  
	$resp = new xajaxResponse();
	//$fix = @mysql_query("select account_id, id from other_income order by id");
	$level1 = @mysql_query("select id, account_no, name from accounts where account_no like '".$prefix."%' and account_no >= ".$prefix."1 and account_no <=".$prefix."9 and name not like '%Bad Debt Expense on Loans%' and name not like '%Dividends Paid on Member Shares%'");
	while ($level1row = @mysql_fetch_array($level1))
	{
		$level2 = @mysql_query("select id, account_no, name from accounts where account_no like '".$level1row['account_no']."%' and account_no >= ".$level1row['account_no']."01 and account_no <= ".$level1row['account_no']."99 and name not like 'Bad Debt Expense on Loans' and name not like '%Dividends Paid on Member Shares%'");		
		if (@mysql_num_rows($level2) > 0) // there's a level2 account
		{
			while ($level2row = @mysql_fetch_array($level2))
			{
				$level3 = @mysql_query("select id, account_no, name from accounts where account_no like '".$level2row['account_no']."%' and account_no >= ".$level2row['account_no']."01 and account_no <= ".$level2row['account_no']."99 and id NOT in (select account_id from fixed_asset) and name not like 'Bad Debt Expense on Loans' and name not like '%Dividends Paid on Member Shares%'");	
				if (@mysql_num_rows($level3) > 0) // there's a level3 account
				{
					while ($level3row = @mysql_fetch_array($level3))
					{
						$fixed_acc .= "<option value='".$level3row['id']."'>".$level3row['account_no']." -". $level3row['name']."</option>";
					}
				}
				else /* Plain level2 accounts */
				{
					$fixed_acc .= "<option value='".$level2row['id']."'>".$level2row['account_no']." -". $level2row['name']."</option>";
				}
			} // end while level2
		}
		else /* Plain level1 accounts */
		{
			$fixed_acc .= "<option value='".$level1row['id']."'>".$level1row['account_no']." -". $level1row['name']."</option>";
		}
	} // end while level1
	//if (strtolower($_SESSION['position']) == 'manager')
		$acc = @mysql_query("select ac.name, ac.account_no as account_no, ba.id as bank_acct_id, ba.bank as bank_name from accounts ac join bank_account ba on ac.id = ba.account_id ");	
	/*else
		$acc = @mysql_query("select ac.name, ac.account_no as account_no, ba.id as bank_acct_id, ba.bank as bank_name from accounts ac join bank_account ba on ac.id = ba.account_id where ba.id in (select bank_account_id from user_account where user_id =".$_SESSION['user_id'].")");*/	
	//$acc = @mysql_query("select ba.id, ac.name, ac.account_no, ba.bank from accounts ac join bank_account ba on ac.id = ba.account_id");
	$accts .= "<option value=''>&nbsp;</option>";
	while ($accrow = @mysql_fetch_array($acc))
	{
		$accts .= "<option value='".$accrow['bank_acct_id']."'>".$accrow['account_no']." - ".$accrow['bank_name']." ". $accrow['name']."</option>";
	}
	$content ="<div class='col-md-12'><form method='post' class='panel panel-default form-horizontal'>";
$content .= '
<div class="panel-heading">
<h4 class="semibold text-primary mt0 mb5">Register Expense Budget </h4>
</div>
<div class="panel-body">';
		    
	$former = mysql_fetch_array(mysql_query("select name, account_no from accounts where account_no='".$prefix."'"));
	$sth = mysql_query("select account_no, name from accounts where account_no >='511' and account_no <= '599'");
	while($row = mysql_fetch_array($sth)){
		$type .= "<option value='".$row['account_no']."'>".$row['account_no']." - ".strtoupper($row['name']);
	}
	$content .= '<div class="form-group">
	<label class="col-sm-3 control-label">Branch:</label>
	<div class="col-sm-6"><span>'.branch().'</span></div>                                            
	</div>';                                           
	$content .='<div class="form-group">
	
	<label class="col-sm-3 control-label">Expense Type:</label>
	<div class="col-sm-6">
	<select id="ptype" name="ptype"  onchange=\'xajax_addBudgetExpenseForm(getElementById("ptype").value); return false;\' class="form-control"><option value="'.$prefix.'">'.$prefix.' - '.$former['name'].$type.'</select>
	</div></div>';

	$content .= '<div class="form-group">
	
	<label class="col-sm-3 control-label">Select Account:</label>
	<div class="col-sm-6">
	<select id="acc_id" name="acc_id" class="form-control">'.$fixed_acc.'</select>
	</div></div>';                                         

		$content .= '<div class="form-group">
	<label class="col-sm-3 control-label">Estimated Budget Amount:</label>
	<div class="col-sm-6">
	<input type="numeric" id="amount" name="amount" class="form-control" onkeyup="format_as_number(this.id)">
	</div></div>';
	
		$content .= '<div class="form-group">
	<label class="col-sm-3 control-label">Date:</label>
	<div class="col-sm-3">
	<input type="text" class="form-control" id="date" name="date" placeholder="Start date" />
	</div>
	<div class="col-sm-3">
	<input type="text" class="form-control" id="enddate" name="enddate" placeholder="End date" />
	</div></div>';
	
	$content .= "<div class='panel-footer'><button type='reset' class='btn btn-default' onclick=\"xajax_addBudgetExpenseForm('".$prefix."')\">Reset</button>
	
	<button type='button' class='btn btn-primary' 
	  onclick=\"xajax_insertBudgetIncomeForm(document.getElementById('acc_id').value,document.getElementById('ptype').value, document.getElementById('amount').value, document.getElementById('date').value,document.getElementById('enddate').value,getElementById('branch_id').value,'Expense'); return false;\">Save</button>";                           
	$content .= '</div></form></div>';
	$resp->call("createDate","date");
	$resp->call("createDate","enddate");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function addBudgetIncomeForm($prefix)
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

	//$level1 = @mysql_query("select id, account_no, name from accounts where account_no like '41%' and account_no like '51%'"); 
	//and account_no >= ".$prefix."1 and account_no <= ".$prefix."9");

	
	$level1 = @mysql_query("select id, account_no, name from accounts where account_no like '".$prefix."%'	and account_no >= ".$prefix."1 and account_no <= ".$prefix."9");

	while ($level1row = @mysql_fetch_array($level1))
	{
		//
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
	$content .= '<div class="panel-heading">
				 <p><h4 class="semibold text-primary mt0 mb5">Register Budget Income</h4></p>                                             		
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
		
	<label class="col-sm-3 control-label">Income Type:</label>
	<div class="col-sm-6">
	<select id="ptype" name="ptype" onchange=\'xajax_addBudgetIncomeForm(getElementById("ptype").value, "'.$action.'"); return false;\' class="form-control">
	<option value="'.$prefix.'">'.$prefix.' - '.$form['name'].''.$type.'</select>
	</div></div>';
		$content .= '<div class="form-group">
	<label class="col-sm-3 control-label">Select Account:</label>
	<div class="col-sm-6">
	<select id="acc_id" name="acc_id" class="form-control">'.$fixed_acc.'</select>
	</div></div>';

		$content .= '<div class="form-group">
	<label class="col-sm-3 control-label">Budget Amount :</label>
	<div class="col-sm-6">
	<input type="numeric" id="amount" name="amount" onkeyup="format_as_number(this.id)"  class="form-control">
	</div></div>';
									
		$content .= '<div class="form-group">
	<label class="col-sm-3 control-label">Date:</label>
	<div class="col-sm-3">
	<input type="text" class="form-control" id="sdate" name="sdate" placeholder="Start date" />
	</div>
		<div class="col-sm-3">
	<input type="text" class="form-control" id="enddate" name="enddate" placeholder="End date" />
	</div></div>';
	
	$content .= "<div class='panel-footer'><button type='reset' class='btn btn-default'  onclick=\"xajax_addBudgetIncomeForm('".$prefix."', 'cash')\">Reset</button>
	<button type='button' class='btn btn-primary'   
	onclick=\"xajax_insertBudgetIncomeForm(document.getElementById('acc_id').value,document.getElementById('ptype').value, document.getElementById('amount').value,document.getElementById('sdate').value,document.getElementById('enddate').value,getElementById('branch_id').value,'Income'); return false;\">Save</button>";
	$content .= '</div></form></div>';
	$resp->call("createDate","sdate");
	$resp->call("createDate","enddate");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function insertBudgetIncomeForm($acc_id,$ptype,$amount,$startday,$endday,$branch_id,$transctiontype){

$today = date("Y-m-d h:i:s",time());
$whoset = CAP_session::get('userId');
        
	$resp = new xajaxResponse();
	
	if ($acc_id == '' || $amount == '' )
	{
		$resp->alert("Please do not leave any field blank. ". $bank_acct);
		return $resp;
	}
	elseif ($startday = '' || $endday == '') {
		$resp->alert("Please select the budgeting period. ");
		return $resp;
	}
	else
	{
		$amount=str_ireplace(",","",$amount);

		$startday = $startday." 00:00:00";
		$endday = $endday." 23:59:59";

		$res = @mysql_query("INSERT INTO budget (who_set, branch_id, type, account,amount, start_day, end_day,transction,time_set) 
		VALUES ('".$whoset."', $branch_id, $ptype, $acc_id,$amount,'".$startday."','".$endday."','".$transctiontype."','".$today."')");

		if($res){
			$resp->alert("Success: Budget Income registered");
			return $resp;			
		}else{
			$resp->alert("Error: Budget Income not registered".mysql_error());
			return $resp;			
		}
	}
	
	//$resp->assign("status", "innerHTML", $cont);
	return $resp;
}

function addExpenseForm($prefix)
{       $fixed_acc='';
        $accts ='';
        $type  ='';  
	$resp = new xajaxResponse();
	//$fix = @mysql_query("select account_id, id from other_income order by id");
	$level1 = @mysql_query("select id, account_no, name from accounts where account_no like '".$prefix."%' and account_no >= ".$prefix."1 and account_no <=".$prefix."9 and name not like '%Bad Debt Expense on Loans%' and name not like '%Dividends Paid on Member Shares%'");
	while ($level1row = @mysql_fetch_array($level1))
	{
		$level2 = @mysql_query("select id, account_no, name from accounts where account_no like '".$level1row['account_no']."%' and account_no >= ".$level1row['account_no']."01 and account_no <= ".$level1row['account_no']."99 and name not like 'Bad Debt Expense on Loans' and name not like '%Dividends Paid on Member Shares%'");		
		if (@mysql_num_rows($level2) > 0) // there's a level2 account
		{
			while ($level2row = @mysql_fetch_array($level2))
			{
				$level3 = @mysql_query("select id, account_no, name from accounts where account_no like '".$level2row['account_no']."%' and account_no >= ".$level2row['account_no']."01 and account_no <= ".$level2row['account_no']."99 and id NOT in (select account_id from fixed_asset) and name not like 'Bad Debt Expense on Loans' and name not like '%Dividends Paid on Member Shares%'");	
				if (@mysql_num_rows($level3) > 0) // there's a level3 account
				{
					while ($level3row = @mysql_fetch_array($level3))
					{
						$fixed_acc .= "<option value='".$level3row['id']."'>".$level3row['account_no']." -". $level3row['name']."</option>";
					}
				}
				else /* Plain level2 accounts */
				{
					$fixed_acc .= "<option value='".$level2row['id']."'>".$level2row['account_no']." -". $level2row['name']."</option>";
				}
			} // end while level2
		}
		else /* Plain level1 accounts */
		{
			$fixed_acc .= "<option value='".$level1row['id']."'>".$level1row['account_no']." -". $level1row['name']."</option>";
		}
	} // end while level1
	//if (strtolower($_SESSION['position']) == 'manager')
		$acc = @mysql_query("select ac.name, ac.account_no as account_no, ba.id as bank_acct_id, ba.bank as bank_name from accounts ac join bank_account ba on ac.id = ba.account_id ");	
	/*else
		$acc = @mysql_query("select ac.name, ac.account_no as account_no, ba.id as bank_acct_id, ba.bank as bank_name from accounts ac join bank_account ba on ac.id = ba.account_id where ba.id in (select bank_account_id from user_account where user_id =".$_SESSION['user_id'].")");*/	
	//$acc = @mysql_query("select ba.id, ac.name, ac.account_no, ba.bank from accounts ac join bank_account ba on ac.id = ba.account_id");
	$accts .= "<option value=''>&nbsp;</option>";
	while ($accrow = @mysql_fetch_array($acc))
	{
		$accts .= "<option value='".$accrow['bank_acct_id']."'>".$accrow['account_no']." - ".$accrow['bank_name']." ". $accrow['name']."</option>";
	}
	$content ="<div class='col-md-12'><form method='post' class='panel form-horizontal panel-default'>";
$content .= '
  			  		<div class="panel-heading">
                                 		
                                                	<h4 class="semibold text-primary mt0 mb5">REGISTER EXPENSE</h4>
                                               		 
                                           	 </div>
                                       <div class="panel-body">';
		    
	$former = mysql_fetch_array(mysql_query("select name, account_no from accounts where account_no='".$prefix."'"));
	$sth = mysql_query("select account_no, name from accounts where account_no >='511' and account_no <= '599'");
	while($row = mysql_fetch_array($sth)){
		$type .= "<option value='".$row['account_no']."'>".$row['account_no']." - ".strtoupper($row['name']);
	}
	$content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Branch:</label>
                                            <div class="col-sm-4"><span>'.branch().'</span></div>                                            
                                            </div>';                                           
                                            $content .='<div class="form-group">
                                            
                                            <label class="col-sm-3 control-label">Type:</label>
                                            <div class="col-sm-4">
                                            <select id="ptype" name="ptype"  onchange=\'xajax_addExpenseForm(getElementById("ptype").value); return false;\' class="form-control"><option value="'.$prefix.'">'.$prefix.' - '.$former['name'].$type.'</select>
                                            </div></div>';
                                            $content .= '<div class="form-group">
                                           
                                            <label class="col-sm-3 control-label">Select Account:</label>
                                            <div class="col-sm-4">
                                          <select id="acc_id" name="acc_id" class="form-control">'.$fixed_acc.'</select>
                                            </div></div>';                                         
                                             $content .= '<div class="form-group">
                                             
                                            <label class="col-sm-3 control-label">Select Source Bank A/c:</label>
                                            <div class="col-sm-4">
                                           <select name="bank_acct" id="bank_acct" class="form-control">'.$accts.'</select>
                                            </div></div>';

											
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Amount Paid:</label>
                                            <div class="col-sm-4">
                                           <input onkeyup="format_as_number(this.id)" type="numeric" id="amount" name="amount" class="form-control">
                                            </div></div>';
                                          
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Voucher No:</label>
                                            <div class="col-sm-4">
                                           <input type="numeric" id="voucher_no" name="voucher_no" class="form-control">
                                            </div></div>';
                                                                                         
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Contact Person:</label>
                                            <div class="col-sm-4">
                                           <input type="numeric" id="contact" name="contact" class="form-control">
                                            </div></div>';
                                          
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Details:</label>
                                            <div class="col-sm-4">
                                           <textarea name="details" id="details" rows="6" class="form-control" name="details"></textarea>
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Date:</label>
                                            <div class="col-sm-4">
                                           <input type="text" class="form-control" id="date" name="date" placeholder="date" />
                                            </div></div>';
                                            
                                            $content .= "<div class='panel-footer'><button type='reset' class='btn btn-default' onclick=\"xajax_addExpenseForm('".$prefix."')\">Reset</button>
                                            
                                            <button type='button' class='btn btn-primary' onclick=\"xajax_addExpense(document.getElementById('acc_id').value, document.getElementById('amount').value, document.getElementById('bank_acct').value, document.getElementById('voucher_no').value,document.getElementById('date').value,document.getElementById('contact').value, document.getElementById('details').value,document.getElementById('branch_id').value); return false;\">Save</button>";                              
                                            $content .= '</div></form></div>';
                                            $resp->call("createDate","date");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function addExpenseForm1()
{
	$resp = new xajaxResponse();
	
	$content ="<form method='post' class='panel form-horizontal form-bordered'>";
$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h4 class="semibold text-primary mt0 mb5">Add Payable</h4>
                                               		 <p class="text-default nm">Select Type of Payable:</p>
                                           	 </div>
                                        </div>';
	
		    $content .= '<div class="form-group">
                                              
                                            <label class="col-sm-3 control-label">Type:</label>
                                            <div class="input-group">
                                            
                                            <select id="ptype" name="ptype" class="form-control"><option value="213">Accounts Payable</option><option value="212">Loans Payable</option></select>
                                              <span class="input-group-btn">
                                                <button class="btn btn-info" type="button" onclick=\'xajax_addPayableForm(getElementById("ptype").value); return false;\'>Next</button>
                                            </span>
                                        </div>
                                            </div></div>';
                                            
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function addExpense($acc_id, $amount, $bank_acct, $voucher_no, $date,$contact,$details,$branch_id)
{       
	$amount=str_ireplace(",","",$amount);
        list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$date1 = sprintf("%04d-%02d-%02d", $year, $month, $mday);
	$date = $date." ".date('H:i:s');
	
	if(empty($voucher_no))
	{
		$resp->alert("Voucher No is required");
		return $resp;
	}
	
	$vch= mysql_query("select * from expense where account_id=$acc_id and voucher_no='".$voucher_no."' and date like '".$date1."%' and amount=$amount");
                     if(mysql_num_rows($vch) > 0){
	              $resp->alert("Transaction already saved!");
	              return $resp;
	}
		
	if ($acc_id == '' || $amount == '' || $bank_acct == '')
	{
		$resp->alert("Please do not leave any field blank. ". $bank_acct);
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
	else
	{
		$bank_res = mysql_query("select account_balance -".$amount." as account_balance, min_balance from bank_account where id='".$bank_acct."'");
		$bank = mysql_fetch_array($bank_res);
		/*
		if($bank['account_balance'] < 0){
			$resp->alert("Expense rejected! \n No sufficient funds on account");
			return $resp;
		}elseif($bank['account_balance'] < $bank['min_balance']){
			$resp->alert("Expense rejected! \n Account balance would go below minimum");
			return $resp;
		}
		*/
		start_trans();
		if ($voucher_no != '')
			$res = @mysql_query("insert into expense (account_id, amount, date, bank_account, voucher_no,contact,details,branch_id) values ($acc_id, $amount, '".$date."', $bank_acct, '".$voucher_no."','".$contact."','".$details."',".$branch_id.")");
		else 
			$res = @mysql_query("INSERT INTO expense (account_id, amount, date, bank_account,contact,details,branch_id) VALUES ($acc_id, $amount, '".$date."', $bank_acct,'".$contact."','".$details."',".$branch_id.")");
		if (@mysql_affected_rows() > 0){
			if(! mysql_query("update bank_account set account_balance=account_balance-".$amount." where id='".$bank_acct."'")){
				$resp->alert("Expense not registered! \n Could not update bank account");
				rollback();
			}else
				{
                $accno = mysql_fetch_assoc(mysql_query("select account_no,name from accounts where id=".$acc_id));
				/////////////////////
				$action = "INSERT INTO expense (account_id, amount, date, bank_account,contact,details) VALUES ($acc_id, $amount, '".$date."', $bank_acct,'".$contact."','".$details."')";
				$msg = "Registered an expense of ".number_format($amount,2)." on ac/no ".$accno['account_no']." - ".$accno['name'];
				log_action($_SESSION['user_id'],$action,$msg);
				////////////////////
				$cont = "<font color=red>Expense registered successfully.</font>";
			}
			commit();
		}else
			$cont = "<font color=red>Error: Expense not registered.</font>";
	}
	$resp->assign("status", "innerHTML", $cont);
	return $resp;
}

function listExpenses($type, $contact, $from_date,$to_date, $branch_id,$search)
{      
        list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	//$resp->alert($from_date.'-'.$to_date);
	//return $resp;
	//$resp->assign("status", "innerHTML", "");
	$branch = ($branch_id=='all'||$branch_id=='')?NULL:"and r.branch_id=".$branch_id;
	$contact = ($contact=='all'|| $contact=='')?NULL:"and r.contact like '%".$contact."%'";
	
$content = '
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <p><h5 class="semibold text-primary mt0 mb5">LIST OF EXPENSES</h5></p>
                                   
                            <div class="panel-body">
                            
                      <div class="form-group">
                                  
                                        <div class="col-sm-3">
                                            <label class="control-label">Account:</label>
                                            <select id="account" class="form-control"><option value="all">All';
	$sth = mysql_query("select distinct id as id, account_no, name from accounts where id in (select account_id from expense)");
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
                                           
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div></div>
                                   
                                </div>';
                                                                                        
	$content .= "<div class='panel-footer'>                              
                                
                                <input type='button' class='btn  btn-primary' id='search' value='Search'  onclick=\"xajax_listExpenses(getElementById('account').value, getElementById('contact').value, getElementById('from_date').value, getElementById('to_date').value, getElementById('branch_id').value,getElementById('search').value)\">
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","from_date");
	$resp->call("createDate","to_date");
                   // $resp->assign("display_div", "innerHTML", $content);
                   
        if($search){
			
	if($from_date == '' || $to_date==''){
		$cont = "<font color=red>Please select the period for expenses</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
	$to_date = sprintf("%04d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
	$from_date = sprintf("%04d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	if (strtolower($type) == 'all')
	{
		$res = @mysql_query("select ac.id as acc_id, ac.account_no, ac.name as acc_name, r.id as rid, r.bank_account, r.amount as amount, r.date as mdate, r.voucher_no,r.contact as contact,r.details as details from accounts ac right join expense r on r.account_id = ac.id where r.date >='".$from_date."' and r.date <='".$to_date."' ".$contact." ".$branch." order by r.date desc");
	}
	/****************************
	* SPECIFY ACCOUNT TYPE
	***************************/
	else
	{
		$res = @mysql_query("select ac.id as acc_id, ac.account_no, ac.name as acc_name, r.id as rid, r.bank_account, r.amount as amount, r.date as mdate, r.voucher_no,r.contact as contact,r.details as details from accounts ac join expense r on r.account_id = ac.id where r.date >='".$from_date."' and r.date <='".$to_date."' and r.account_id='".$type."' ".$contact." ".$branch." order by r.date desc");
	}
	if (@mysql_num_rows($res) > 0)
	{
					   
			 $content .= "<input type='button' class='pull-right' href=\"#\" onClick =\"$('#table-tools').tableExport({type:'excel',escape:'false'});\" value='Excel'>";
                            $content .= "<div class='panel panel-default' id='demo'>
                            <div class='panel-heading'>
                                <h3 class='panel-title'>LIST OF EXPENSES</h3>
                                
                            </div>";
                            
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
 		
		$content .="<thead'>
		
			        <th>#</th><th>Account</th><th>Amount</th><th>Voucher No.</th><th>Date</th><th>Source Bank Account</th><th>Contact Person</th><th>Details</th><th></th>
			    </thead><tbody>
			    ";
		$i=1; $total=0;
		while ($fxrow = @mysql_fetch_array($res))
		{
			$bank = mysql_fetch_array(mysql_query("select a.account_no, a.name, b.bank from bank_account b join accounts a on b.account_id=a.id where b.id='".$fxrow['bank_account']."'"));
			if (strtolower($type) == 'all' || strtolower($type) == 'cleared'|| $type !="")
			{
			//$color = ($i%2==0) ? "lightgrey" : "white";
			$content .= "
				    <tr>
				        <td>".$i."</td><td>".$fxrow['account_no']." -". $fxrow['acc_name']."</td><td>".number_format($fxrow['amount'], 2)."</td><td>".$fxrow['voucher_no']."</td><td>".$fxrow['mdate']."</td><td>".$bank['account_no']." -". $bank['bank']."". $bank['name']."</td><td>".$fxrow['contact']."</td><td>".$fxrow['details']."</td><td><a href='javascript:;' onclick=\"xajax_editExpenseForm('".$fxrow['rid']."', '".$type."', '".$date."', '".$fxrow['contact']."', '".$fxrow['details']."', '".$branch_id."'); return false;\">Edit</a>&nbsp;<a href='javascript:;' onclick=\"xajax_deleteExpense2('".$fxrow['rid']."', '".$type."', '".$from_date."', '".$to_date."', '".$branch_id."'); return false;\">Delete</a></td>
				    </tr>
				    ";
					$total += $fxrow['amount'];
					$i++;
			}
		}
		$content .= "<tr class='headings'><td colspan='2'>Total</td><td>".number_format($total, 2)."</td><td colspan='7'></td></tr>";
		$content .= "<tbody></table></div>";
	}
	else{
		$cont = "<font color=red>No Expense registered yet.</font>";
		$resp->assign("status", "innerHTML", $cont);
	}
	}
	}
	$resp->call("createTables");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function editExpenseForm($pid, $type, $date,$contact,$details,$branch_id)
{             
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}

	$res = @mysql_query("select  r.bank_account, ac.name, r.id,ac.id as acctId,ac.account_no as account_no, r.amount, r.account_id, r.voucher_no, date_format(date, '%Y') as year, date_format(date, '%m') as month, date_format(date, '%d') as mday,r.contact as contact,r.details as details from  expense r join accounts ac on ac.id = r.account_id where r.id = $pid");
	if (@mysql_num_rows($res) > 0)
	{
		$row = @mysql_fetch_array($res);
		/*$acc = @mysql_query("select ba.id, ac.name, ac.account_no, ba.bank from accounts ac join bank_account ba on ac.id = ba.account_id");
		$accts .= "<option value=''>&nbsp;</option>";
		while ($accrow = @mysql_fetch_array($acc))
		{
			if ($accrow['id'] == $row['bank_account'])
				$accts .= "<option value='".$accrow['id']."' selected>".$accrow['account_no']." -".$accrow['name']."</option>";
			else
				$accts .= "<option value='".$accrow['id']."'>".$accrow['account_no']." - ".$accrow['name']."</option>";
		}*/
		 $content .="<form method='post' class='panel panel-default form-horizontal form-bordered'>";
$content .= '
<div class="panel-heading">
                              <h3 class="panel-title">EDIT EXPENSES</h3>
                                        
                            <div class="panel-body">';
                                        $exp=mysql_query("select * from accounts where account_no like '5%' order by account_no ");
                                        
                                        
                                         $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Expense A/c:</label>
                                            <div class="col-sm-6">
                                            <select id="accountId" class="form-control"><option value="'.$row['acctId'].'" selected>'.$row['account_no'].' -'.$row['name'].'</option>';
                                            while ($accts = @mysql_fetch_array($exp)){
		                
				         $content.= "<option value='".$accts['id']."'>".$accts['account_no']." - ".$accts['name']."</option>";
				           }
		                           $content.='</select></div></div>';
                                            
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Amount Paid:</label>
                                            <div class="col-sm-6">
                                          <input onkeyup="format_as_number(this.id)" type="numeric" id="amount" name="amount" value="'.$row['amount'].'" class="form-control">
                                            </div></div>';
                                            
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Voucher No:</label>
                                            <div class="col-sm-6">
                                          <input type="text" id="voucher_no" name="voucher_no" value="'.$row['voucher_no'].'" class="form-control">
                                            </div></div>';
                                            
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Source Bank Account:</label>
                                            <div class="col-sm-6">
                                                                                   
                                          <select name="bank_acct_id" id="bank_acct_id" class="form-control" disabled><option value="">';
				//if (strtolower($_SESSION['position']) == 'admin')
					$account_res = mysql_query("select a.name as name, a.account_no as account_no, b.bank as bank, b.id as id from bank_account b join accounts a on b.account_id=a.id where b.id='".$row['bank_account']."'");
				/*else
					$account_res = mysql_query("select a.name as name, a.account_no as account_no, b.bank as bank, b.id as id from bank_account b join accounts a on b.account_id=a.id where a.branch_no like '".$_SESSION['branch_no']."' and b.id in (select bank_account_id from user_account where user_id =".$_SESSION['user_id'].")");*/
				while($account = mysql_fetch_array($account_res)){
					$content .= "<option selected value='".$account['id']."'>".$account['account_no'] ." - ".$account['bank']." ".$account['name'];
				}
				//$resp->alert($row['branch_id']);
				$content .= '</select>
                                            </div></div>';
                                            
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Contact Person:</label>
                                            <div class="col-sm-6">
                                         <input name="contact" id="contact" value="'.$row['contact'].'" class="form-control">
                                            </div></div>';
                                            
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Details:</label>
                                            <div class="col-sm-6">
                                        <textarea name="details" id="details" rows="3" cols="40" class="form-control">'.$row['details'].'</textarea>
                                            </div></div>';
                                            
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Date:</label>
                                            <div class="col-sm-6">
                                                                              
                                       <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="date" name="date" placeholder="date" /></div>
                                                
                                            </div>
                                            </div></div>';
                                            
                                            $content .= "<div class='panel-footer'><button type='reset' class='btn btn-default'   onclick=\"xajax_listExpenses('".$type."', '', '".$from_date."', '".$to_date."','".$contact."','".$details."','".$branch_id."')\">Back</button>
                                            <button type='button' class='btn btn-primary'   onclick=\"xajax_updateExpense2('".$row['id']."',document.getElementById('accountId').value, '".$row['name']."', '".$row['bank_account']."', '".$row['amount']."', document.getElementById('amount').value, document.getElementById('voucher_no').value, getElementById('bank_acct_id').value, document.getElementById('date').value, '".$type."', document.getElementById('contact').value, document.getElementById('details').value, '".$branch_id."'); return false;\">Save</button>";
                                            $content .= '</div></form></div>';
                                            $resp->call("createDate","date");
					    					
			   
	}
	else {
		$cont = "<font color=red><b>ERROR: Expense record not found!</b></font>";
            $resp->assign("status", "innerHTML", $cont);
            }
	
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function updateExpense2($pid,$acctId, $pname, $prev_bank_acct_id, $prev_amount, $amount, $voucher_no, $bank_acct_id, $date, $type, $contact, $details,$branch_id)
{       
        list($year,$month,$mday) = explode('-', $date);
        $amount=str_ireplace(",","",$amount);
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$calc = new Date_Calc();
	if ($pid == '' || $amount == '' || $voucher_no == '' || $bank_acct_id == '' || $date == '' || $contact == '' || $details == '')
		$resp->alert('Please do not leave any fields blank.');
	elseif ($calc->isFutureDate($mday, $month, $year))
		$resp->alert('Date can not be a future date');
	/* elseif (!unique_rcpt($rcpt_no, 'other_income'))
		$resp->alert("Receipt has already been used."); */
	else
	{
		$resp->ConfirmCommands(1, "Do you really want to update?");
		$resp->call('xajax_updateExpense', $pid, $acctId, $pname, $prev_bank_acct_id, $prev_amount, $amount, $voucher_no, $bank_acct_id, $date, $type,$contact, $details, $branch_id);
	}
	return $resp;
}

function updateExpense($pid,$acctId,$pname, $prev_bank_acct_id, $prev_amount, $amount, $voucher_no, $bank_acct_id, $date, $type,$contact, $details, $branch_id)
{	
	list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$date = sprintf("%d-%02d-%02d", $year, $month, $mday);
	$date = $date." ".date('H:i:s');
	start_trans();
	if ($prev_bank_acct_id == $bank_acct_id && $prev_amount == $amount)
	{    //SAME BANK ACCOUNT AND AMOUNT
		$res = @mysql_query("update expense set date='".$date."',account_id=$acctId, amount=$amount, voucher_no='".$voucher_no."', contact='".$contact."', details='".$details."' where id=$pid");
		if (@mysql_affected_rows() != -1)
		{			
			commit();
			$cont = "<font color=red>Expense $pname updated successfully.</font>";
			//$resp->assign("status", "innerHTML", $cont);
		}
		else
		{
			rollback();
			$cont = "<font color=red>ERROR: Expense $pname not updated! ".mysql_error()."</font>";			
			//$resp->assign("status", "innerHTML", $cont);
		}
	}elseif($prev_bank_acct_id == $bank_acct_id){    //IF THE BANK ACCOUNT HASNT CHANGED BUT AMOUNT HAS
		$cur_bank_row = @mysql_fetch_array(@mysql_query("select account_balance, min_balance from bank_account where id = $bank_acct_id"));
		$new_bal = intval($cur_bank_row['account_balance']) - $amount + $prev_amount;
		/*
		if ( $new_bal < 0)
		{
			$resp->alert("Insufficient funds on newly selected bank account.");
			return $resp;
		
		}elseif ( $new_bal <intval($cur_bank_row['min_balance']))
		{
			$resp->alert("Account balance would go below minimum balance.");
			return $resp;
		}
		else
		{
			*/
			$new_bank_upd_res = @mysql_query("UPDATE bank_account set account_balance = $new_bal where id = $bank_acct_id");
			//$resp->alert("UPDATE bank_account set account_balance = $new_bal where id = $bank_acct_id");
			//return $resp;
			if (isset($new_bank_upd_res) && @mysql_affected_rows() != -1)
			{
				$inv_upd_res = @mysql_query("UPDATE expense set amount = $amount, date = '".$date."', bank_account = $bank_acct_id, voucher_no = '".$voucher_no."', contact='".$contact."', details='".$details."' where id = $pid");
				if (isset($inv_upd_res) && @mysql_affected_rows() != -1)
				{
					commit();
					$cont = "<font color=red>Expense $pname updated successfully.</font>";					// $resp->assign("status", "innerHTML", $cont);
				}
				else
				{
					rollback();
					$cont = "<font color=red>ERROR: Expense $pname not updated! ".mysql_error()."</font>";		//$resp->assign("status", "innerHTML", $cont);
				}
			}
			else
			{
				rollback();
				$cont = "<font color=red>ERROR: New bank balance not updated! ".mysql_error()."</font>";			//$resp->assign("status", "innerHTML", $cont);
			}
		//}
	
	}else
	{  //BANK ACCOUNT HAS CHANGED
		$prev_bank_row = @mysql_fetch_array(@mysql_query("select account_balance, min_balance from bank_account where id = $prev_bank_acct_id"));
		$cur_bank_row = @mysql_fetch_array(@mysql_query("select account_balance, min_balance from bank_account where id = $bank_account_id"));
		$old_bal = intval($prev_bank_row['account_balance']) + $prev_amount;
		$new_bal = intval($cur_bank_bal['account_balance']) - $amount;
	/*
	if ($new_bal < 0)
		{
			$resp->alert("Insufficient funds on the \n the newly selected Account.");
			return $resp;
		}elseif (intval($cur_bank_row['min_balance']) >= $new_bal)
		{
			$resp->alert("Account Balance would go below the minimum balance \non the newly selected Account.");
			return $resp;
		}
		else
		{
		*/
			$old_bank_upd_res = @mysql_query("UPDATE bank_account set account_balance = $old_bal where id = $prev_bank_acct_id");
			if (isset($old_bank_upd_res) && @mysql_affected_rows() != -1)
			{
				$new_bank_upd_res = @mysql_query("UPDATE bank_account set account_balance = $new_bal where id = $bank_acct_id");
				if (isset($new_bank_upd_res) && @mysql_affected_rows() != -1)
				{
					$exp_upd_res = @mysql_query("UPDATE expense set amount = $amount, date = '".$date."', bank_account = $bank_acct_id, voucher_no = '".$voucher_no."' contact='".$contact."', details='".$details."' where id = $pid");
					if (isset($exp_upd_res) && @mysql_affected_rows() != -1)
					{
						$accno = mysql_fetch_assoc(mysql_query("select a.account_no, a.name from accounts a join expense e on e.account_id=a.id where e.id=".$pid));
						
			/////////////////////
			$action = "update expense set date='".$date."', amount=$amount, voucher_no='".$voucher_no."', contact='".$contact."', details='".$details."' where id=$pid";
			$msg = "Updated expense set amount: ".number_format($amount,2) ."on ac/no:".$accno['account_no']." - ".$accno['name'];
			log_action($_SESSION['user_id'],$action,$msg);
			////////////////////
						commit();
						$cont = "<font color=green>Expense $pname updated successfully.</font>";				//$resp->assign("status", "innerHTML", $cont);
					}
					else
					{
						rollback();
						$cont = "<font color=red>ERROR: Expense $pname not updated! ".mysql_error()."</font>";		//$resp->assign("status", "innerHTML", $cont);
					}
				}
				else
				{
					rollback();
					$cont = "<font color=red>ERROR: New bank balance not updated! ".mysql_error()."</font>";		//$resp->assign("status", "innerHTML", $cont);
				}
			}
			else
			{
				rollback();
				$cont = "<font color=red>ERROR: Old Bank balance not updated! ".mysql_error()."</font>";			//$resp->assign("status", "innerHTML", $cont);
			}
		//}
	}

	//$resp->call('xajax_editExpenseForm', $pid, $type, $date,$contact,$details,$branch_id);
	$resp->call('xajax_listExpenses', 'all','','','','','');
	$resp->assign("status", "innerHTML", $cont);
	//$resp->call('xajax_listExpenses', 'all', $content, $type, $msg, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday);
	return $resp;
}

function deleteExpense2($pid, $type, $from_date, $to_date, $branch_id)
{       
         
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}

	if (@mysql_num_rows(@mysql_query("select * from expense where id = $pid")) < 1)
		$resp->alert("Cannot delete: Expense entry not found.");
	else
	{
		$exp_row = @mysql_fetch_array(@mysql_query("select exp.amount, exp.bank_account, ba.min_balance, (ba.account_balance + exp.amount) as new_bal from expense exp join bank_account ba on exp.bank_account = ba.id where exp.id = $pid"));
	//	$resp->alert("select exp.amount, exp.bank_account, ba.min_balance, (ba.account_balance + exp.amount) as new_bal from expense exp join bank_account ba on exp.bank_account = ba.id where exp.id = $pid");
	//	return $resp;
		$resp->ConfirmCommands(1, "Do you really want to delete?");
		$resp->call('xajax_deleteExpense', $pid, $type, $exp_row['bank_account'], $exp_row['new_bal'], $from_date, $to_date, $branch_id);
	}
	//$resp->assign("status", "innerHTML", "test");
	return $resp;
}

function deleteExpense($pid, $type, $bank_acct_id, $new_bal, $from_date,$to_date, $branch_id)
{
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	start_trans();
	$upd_res = @mysql_query("UPDATE bank_account set account_balance = $new_bal where id = $bank_acct_id");
	
	if (isset($upd_res) && @mysql_affected_rows() != -1)
	{
		$accno = mysql_fetch_assoc(mysql_query("select a.account_no,a.name, e.amount from accounts a join expense e on e.account_id=a.id where e.id=".$pid));
		$res = @mysql_query("delete from expense where id = $pid");
		if (isset($res) && @mysql_affected_rows() != -1)
		{
			
			/////////////////////
			$action = "delete from expense where id = $pid";
			$msg = "Deleted an expense of: ".number_format($accno['amount'],2)." on ac/no: ".$accno['account_no']." - ".$accno['name'];
			log_action($_SESSION['user_id'],$action,$msg);
			////////////////////
			commit();
			$cont = "<font color=red>Expense deleted successfully.</font>";
			$resp->assign("status", "innerHTML", $cont);
		}
		else
		{
			rollback();
			$cont = "<font color=red>Error: Failed to delete Expense.</font>";
			$resp->assign("status", "innerHTML", $cont);
		}
	}
	else
	{
		rollback();
		$cont = "<font color=red>Error: Failed to update bank account balance.</font>";
	        $resp->assign("status", "innerHTML", $cont);
	}
	//$resp->assign("status", "innerHTML", $content);
	$resp->call('xajax_listExpenses', $type,'', $from_date, $to_date, $branch_id,'');
	return $resp;
}

?>
