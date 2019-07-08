<?php

$xajax->registerFunction("addPayableForm");
$xajax->registerFunction("addPayableForm1");
$xajax->registerFunction("addPayable");
$xajax->registerFunction("listPayables");
$xajax->registerFunction("editPayableForm");
$xajax->registerFunction("updatePayable");
$xajax->registerFunction("updatePayable2");
$xajax->registerFunction("deletePayable2");
$xajax->registerFunction("deletePayable");
$xajax->registerFunction("makePayment");
$xajax->registerFunction("makePaymentForm");
$xajax->registerFunction("listPayments");
$xajax->registerFunction("deletePayment2");
$xajax->registerFunction("deletePayment");


function addPayableForm($prefix)
{       
        $fixed_acc='';
        $accts='';
        $content='';
	$resp = new xajaxResponse();
	//$fix = @mysql_query("select account_id, id from payable order by id");
	$level1 = @mysql_query("select id, account_no, name from accounts where account_no like '".$prefix."%' and account_no between ".$prefix."1 and ".$prefix."9");
	while ($level1row = @mysql_fetch_array($level1))
	{
		$level2 = @mysql_query("select id, account_no, name from accounts where account_no like '".$level1row['account_no']."%' and account_no between ".$level1row['account_no']."01 and ".$level1row['account_no']."99");		
		if (@mysql_num_rows($level2) > 0) // there's a level2 account
		{
			while ($level2row = @mysql_fetch_array($level2))
			{
				$level3 = @mysql_query("select id, account_no, name from accounts where account_no like '".$level2row['account_no']."%' and account_no between ".$level2row['account_no']."01 and ".$level2row['account_no']."99 and id NOT in (select account_id from fixed_asset)");	
				if (@mysql_num_rows($level3) > 0) // there's a level3 account
				{
					while ($level3row = @mysql_fetch_array($level3))
					{
						$fixed_acc .= "<option value='".$level3row['id']."'>".$level3row['account_no']." - ".$level3row['name']."</option>";
					}
				}
				else /* Plain level2 accounts */
				{
					$fixed_acc .= "<option value='".$level2row['id']."'>".$level2row['account_no']." -".$level2row['name']."</option>";
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
	//$acc = @mysql_query("select ac.id, ac.account_no, ac.name, ba.bank, ba.id as bank_id from accounts ac join bank_account ba on ac.id = ba.account_id");
	$accts .= "<option value=''>&nbsp;</option>";
	if (@mysql_num_rows($acc) > 0)
	{
		while ($acc_row = @mysql_fetch_array($acc))
		{
			$accts .= "<option value='".$acc_row['bank_acct_id']."'>".$acc_row['bank_name']." -".$acc_row['name']."</option>";
		}
	}
	$former = mysql_fetch_array(mysql_query("select * from accounts where account_no='".$prefix."'"));
	$content .="<div class='col-md-12'><form method='post' class='panel form-horizontal panel-default'>";
$content .= '
  			  		<div class="panel-heading">
                                 		
                                                	<h4>REGISTER PAYABLE</h4>
                                               		 <p class="text-default nm"></p>
                                           	 </div>
                                        <div class="panel-body">';
                                        	 
	                                    $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Branch:</label>
                                            <div class="col-sm-4"><span>'.branch().'</span></div>                                            
                                            </div>';                                           
                                            $content .='<div class="form-group">
                                            
                                            <label class="col-sm-3 control-label">Type:</label>
                                            <div class="col-sm-4">
                                            <select id="ptype" name="ptype" onchange=\'xajax_addPayableForm(getElementById("ptype").value); return false;\' class="form-control"><option value="'.$prefix.'">'.$former['name'].'<option value="213">ACCOUNTS PAYABLE</option><option value="212">LOANS PAYABLE</option></select>
                                            </div></div>';
                                            $content .= '<div class="form-group">
                                           
                                            <label class="col-sm-3 control-label">Select Account (From Chart of Accts):</label>
                                            <div class="col-sm-4">
                                          <select id="acc_id" name="acc_id" class="form-control">'.$fixed_acc.'</select>
                                            </div></div>';                                         
                                             $content .= '<div class="form-group">
                                             
                                            <label class="col-sm-3 control-label">Initial Amount Due:</label>
                                            <div class="col-sm-4">
                                           <input onkeyup="format_as_number(this.id)"  type="numeric" id="initval" name="initval"  class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Name of Creditor:</label>
                                            <div class="col-sm-4">
                                           <input type="text" id="creditor" name="creditor"  class="form-control">
                                            </div></div>';
                                            if($prefix == '212'){
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Rate:</label>
                                            <div class="col-sm-4">
                                           <select name="rate" id="rate" class="form-control"><option value="market">Market</option><option value="subsidised" SELECTED>Subsidised</option></select>
                                            </div></div>';
                                                                                         
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Destination Bank A/c:</label>
                                            <div class="col-sm-4">
                                           <select id="bank_acct_id" name="bank_acct_id"  class="form-control">'.$accts.'<select>
                                            </div></div>';
                                            }
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Acquisition Date:</label>
                                            <div class="col-sm-4">
                                          <input type="text" class="form-control" id="acqdate" name="acqdate" placeholder="Acquisition Date" />
                                            </div></div>';
                                                                           
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Maturity Date:</label>
                                            <div class="col-sm-4">
                                           <input type="text" class="form-control" id="matdate" name="matdate" placeholder="Maturity Date" />
                                            </div></div>'; 
                                            
                 $resp->call("createDate","acqdate");                           
                 $resp->call("createDate","matdate");   
                                            if ($prefix == '212')
	                                    {
                                             
                                            $content .= "<div class='panel-footer'><button type='reset' class='btn btn-default' onclick=\"xajax_addPayableForm('".$prefix."')\">Reset</button>
                                            <button type='button' class='btn btn-primary' onclick=\"xajax_addPayable(document.getElementById('acc_id').value, document.getElementById('initval').value, document.getElementById('creditor').value, getElementById('bank_acct_id').value, getElementById('acqdate').value,document.getElementById('matdate').value,  getElementById('branch_id').value,getElementById('rate').value); return false;\">Save</button>";
                                            }
	                                   else{
	                                   
	                                   $content .= "<div class='panel-footer'><button type='reset' class='btn btn-default' onclick=\"xajax_addPayableForm('".$prefix."')\">Reset</button>
                                            <button type='button' class='btn btn-primary' onclick=\"xajax_addPayable(document.getElementById('acc_id').value, document.getElementById('initval').value,document.getElementById('creditor').value, '', getElementById('acqdate').value, document.getElementById('matdate').value, getElementById('branch_id').value,''); return false;\">Save</button>";
                                            }
                                            $content .= '</div></form></div>';
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function addPayable($acc_id, $initval, $creditor, $bank_acct_id, $acqdate, $matdate,$branch_id,$rate)
{
        
        $initval=str_ireplace(",","",$initval);
        list( $acq_year, $acq_month, $acq_mday) = explode('-', $acqdate);
        list($year, $month, $mday) = explode('-', $matdate);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	//$date = date('Y-m-d');
	$trans = 0;
	$date = sprintf("%d-%02d-%02d", $year, $month, $mday);
	$date = $date." ".date('H:i:s');
	$acq_date = sprintf("%d-%02d-%02d", $acq_year, $acq_month, $acq_mday);
	
	if ($acc_id == '' || $initval == '' || $creditor == '')
	{
		$resp->alert('Please do not leave any field blank.');
	}
	elseif (!$calc->isValidDate($mday, $month, $year))
	{
		$resp->alert('Invalid Date. Most likely wrong date for Month: Feb.');
	}
	/*elseif ($calc->isPastDate($mday, $month, $year))
	{
		$resp->alert('Maturity Date can not be a past date.');
	}*/
	elseif ($calc->isFutureDate($acq_mday, $acq_month, $acq_year))
	{
		$resp->alert('Acquisition date cannot be a future date.');
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
		$res = @mysql_query("insert into payable (account_id, amount, vendor, bank_account, date, maturity_date, branch_id,rate) values ($acc_id, $initval, '".$creditor."', '".$bank_acct_id."', '".$acq_date."', '".$date."',".$branch_id.",'".$rate."')");
		
		if (@mysql_affected_rows() > 0)
		{
			$accno = mysql_fetch_assoc(mysql_query("select account_no,name from accounts where id=".$acc_id));
			//////////////////
			$action = "insert into payable (account_id, amount, vendor, bank_account, date, maturity_date) values ($acc_id, $initval, '".$creditor."', '".$bank_acct_id."', '".$acq_date."', '".$date."')";
			$msg = "Registered payable of ".number_format($initval,2)." into ac/no: ".$accno['account_no']."-".$accno['name'];
			log_action($_SESSION['user_id'],$action,$msg);
			///////////////////
			mysql_query("commit");
			$content .= "<font color=red>Payable registered successfully.</font><br>";
			$resp->assign("status", "innerHTML", $content);
			//$resp->call("xajax_listPayables", 'all', $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday);
			return $resp;
		}
		else
			$resp->alert("Error: Payable not registered.");
		mysql_query("rollback");
	}
	return $resp;
}

function listPayables($type, $account, $contact, $from_date,$to_date, $branch_id,$rate,$search)
{   
        list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	$branch = ($branch_id=='all'||$branch_id=='')?NULL:"and p.branch_id=".$branch_id;	
	$content = '
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <p><h5>SEARCH FOR '.strtoupper($type).' PAYABLES</h5></p>
                                        
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    
                                        <div class="col-sm-3">
                                            <label class="control-label">Account:</label>
                                            <select id="account" class="form-control"><option value="">All';
	$sth = mysql_query("select distinct id as id, account_no, name from accounts where id in (select account_id from payable)");
	while($row = mysql_fetch_array($sth)){
		$content .= "<option value='".$row['id']."'>".$row['account_no'] ." - ".$row['name'];
	}
	$content .='</select>                                         
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label">Name of Creditor:</label>
                                            <input type="text" id="contact" class="form-control">
                                        
                                    </div>
                              
                                       <div class="col-sm-3">
                                            <label class="control-label">Branch:</label>
                                            <span>'.branch_rep($branch_id).'</span>
	                                          
                                        </div>
                                        <div class="col-sm-3">
                                            
                                            <label class="control-label">Date Range:</label>
                                            <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" placeholder="to" /></div>
                                                
                                        </div> </div>
                                    </div>
                                </div>';
                                
        $resp->call("createDate","from_date");
	$resp->call("createDate","to_date");
                                                                                        
	$content .= "<div class='panel-footer'>                              
                                
                                <input type='button' class='btn  btn-primary' id='search' value='Search'  onclick=\"xajax_listPayables('".$type."', getElementById('account').value, getElementById('contact').value, getElementById('from_date').value,getElementById('to_date').value, getElementById('branch_id').value,'',getElementById('search').value)\">
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                 //$resp->assign("display_div", "innerHTML", $content);  
                    		  
	if($search){
	
	if($from_date == '' && $to_date==''){
		$cont= "<font color=red>Select the period for your report</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
	
	$from_date = sprintf("%04d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%04d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
			 
	$where_account = ($account == '') ? "" : " and p.account_id='".$account."' ";
	if (strtolower($type) == 'all')
	{
		$res = @mysql_query("select ac.account_no, p.date, ac.id as acc_id, ac.name as acc_name, p.id as pid, p.amount as amount, p.maturity_date as mdate, p.vendor as creditor, p.status as status, p.rate as rate from accounts ac join payable p on p.account_id = ac.id where p.date >='".$from_date."'");
	}
	elseif (strtolower($type) == 'pending')
	{
		$res = @mysql_query("select ac.account_no, p.date, ac.id as acc_id, ac.name as acc_name, p.id as pid, p.amount as amount, p.maturity_date as mdate, p.vendor as creditor, p.status as status , p.rate as rate from accounts ac join  payable p on p.account_id = ac.id where lower(p.status) = 'pending' and p.date >='".$from_date."' and p.date <= '".$to_date."' and p.vendor like '%".$contact."%' ".$where_account." ".$branch." order by p.vendor,p.maturity_date desc");
	}
	elseif (strtolower($type) == 'cleared')
	{
		$res = @mysql_query("select ac.id as acc_id, ac.account_no, ac.name as acc_name, p.id as pid, p.amount as amount, p.maturity_date as mdate, p.date as date, p.vendor as creditor, p.status as status , p.rate as rate from accounts ac join payable p on p.account_id = ac.id where lower(p.status) = 'cleared' and p.date >='".$from_date."' and p.date <= '".$to_date."' and p.vendor like '%".$contact."%' ".$where_account." ".$branch." order by p.vendor,p.maturity_date desc");
	}
	if (@mysql_num_rows($res) > 0)
	{  
	
	/*$content .= "
		  <a href='list_payables.php?type=".$type."&branch_id=".$branch_id."&account=".$account."&contact=".$contact."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."' target=blank()><b>Printable Version</b></a> | 
		    <a href='list_payables.php?type=".$type."&branch_id=".$branch_id."&account=".$account."&contact=".$contact."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&format=excel' target=blank()><b>Export Excel</b></a> | 
			 <a href='list_payables.php?type=".$type."&branch_id=".$branch_id."&account=".$account."&contact=".$contact."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&format=word' target=blank()><b>Export Word</b></a>";*/
	
	$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">LIST OF '.strtoupper($type).' PAYABLES</h3>
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
 		
		$content .="<thead'>
			 <th>Account</th><th>Name of Creditor</th><th>Initial Amount</th><th>Maturity Date</th><th>Aquisition Date</th><th>Status</th><th>Balance</th><th>Payments</th><th>Action</th>
			 </thead><tbody>
			    ";
		$i=0;
		$total_amt =0;
		$total_bal=0;
		while ($fxrow = @mysql_fetch_array($res))
		{
			$paid=mysql_fetch_array(mysql_query("select sum(amount) as amount from payable_paid where payable_id='".$fxrow['pid']."'"));
			$paid_amt = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
			$balance = $fxrow['amount'] - $paid_amt;
			$total_amt += $fxrow['amount'];
			$total_bal += $balance;
			//$color = ($i%2 == 0) ? "lightgrey" : "white";
		
			$content .= "
				    <tr>
				    	<td>".$fxrow['account_no']." -".$fxrow['acc_name']."</td><td>".$fxrow['creditor']."</td><td>".number_format($fxrow['amount'], 2)."</td><td>".$fxrow['mdate']."</td><td>".$fxrow['date']."</td><td>".$fxrow['status']."</td><td>".number_format($balance, 2)."</td><td><a href='javascript:;' onclick=\"xajax_makePaymentForm('".$fxrow['pid']."', '".$type."', '".$account."', '".$contact."', '".$from_date."','".$to_date."','".$branch_id."','".$rate."'); return false;\">Payments</a></td><td><a href='javascript:;' onclick=\"xajax_editPayableForm('".$fxrow['pid']."',  '".$type."', '".$account."', '".$contact."', '".$from_date."','".$to_date."','".$branch_id."','".$rate."'); return false;\">Edit</a>&nbsp;<a href='javascript:;' onclick=\"xajax_deletePayable2('".$fxrow['pid']."', '".$type."', '".$account."', '".$contact."', '".$date."', '".$branch_id."','".$rate."'); return false;\">Delete</a></td>
				    </tr>
				    ";
					$i++;
		//	}
		}
		$content .= "<tr><td>Total</td><td></td><td>".number_format($total_amt, 2)."</td><td></td><td></td><td></td><td>".number_format($total_bal, 2)."</td><td></td><td></td></tr></tbody></table></div>";
		$resp->call("createTableJs");
	}
	else{
		$cont = "<font color=red>No payables registered yet.</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
	}
	}
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function editPayableForm($pid, $type, $account, $contact, $date,$branch_id,$rate)
{        
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$tmp = @mysql_query("select date from payable_paid where payable_id = $pid");
	if (@mysql_num_rows($tmp) > 0){
		$resp->alert("You cannot edit this payable: \nSome payments have been made.");
		return $resp;
	}
	$sth = mysql_query("select * from payable where id='".$pid."' and fund_id in (select id from other_funds)");
	if(mysql_numrows($sth) > 0){
		$resp->alert("You cannot edit this payable: \nIt is directly imported from Capital Reserve Funds\n You can only delete it");
		return $resp;
	}
	$res = @mysql_query("select  p.id as id, ac.name, p.amount, p.account_id, p.vendor, date_format(maturity_date, '%Y') as year, date_format(maturity_date, '%m') as month, date_format(maturity_date, '%d') as mday from  payable p join accounts ac on ac.id = p.account_id where p.id = $pid");
	if (@mysql_num_rows($res) > 0)
	{
		$row = @mysql_fetch_array($res);
		
			   $content ="<form method='post' class='panel form-horizontal form-bordered'>";
$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h4 class="semibold text-primary mt0 mb5">EDIT PAYABLE: '.$row['name'].'</h4>
                                               		 <p class="text-default nm"></p>
                                           	 </div>
                                        </div>';
                                        
                           $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Initial Amount Due:</label>
                                            <div class="col-sm-6">
                                           <input onkeyup="format_as_number(this.id)" type="numeric" id="initval" name="initval" value="'.$row['amount'].'" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Name of Creditor:</label>
                                            <div class="col-sm-6">
                                           <input type=text id="creditor" name="creditor" value="'.$row['vendor'].'" class="form-control">
                                            </div></div>';
                                                                                                                                                              
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Maturity Date:</label>
                                            <div class="col-sm-6">
                                           <input type="text" class="form-control" id="matdate" name="matdate" placeholder="Maturity Date" />
                                            </div></div>';                                             									                                                                                             
				            $content .= "<div class='panel-footer'><button type='reset' class='btn btn-default'  onclick=\"xajax_listPayables('".$type."', '".$account."', '".$contact."', '".$date."','".$branch_id."')\">Back</button>&nbsp;

                                            <button type='button' class='btn btn-primary'   onclick=\"xajax_updatePayable2('".$row['id']."', '".$row['name']."', document.getElementById('creditor').value, document.getElementById('initval').value, document.getElementById('matdate').value,'".$type."', '".$date."','".$branch_id."'); return false;\">Save</button>";
                                            $content .= '</div></form></div>';
		
	}
	else {
		$cont = "<font color=red><b>ERROR: Payable not found!</b></font>";
		$resp->assign("status", "innerHTML", $cont);
	return $resp;
	}
			 
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function updatePayable2($pid, $pname, $creditor, $initval, $date,  $type, $date,$branch_id,$rate)
{
	$resp = new xajaxResponse();
	$initval=str_ireplace(",","",$initval);
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$calc = new Date_Calc();
	$depr = @mysql_query("select date from payable_paid where payable_id = $pid");
	if ($pid == '' || $creditor == '' || $initval == '')
		$resp->alert('Please do not leave any fields blank.');
	//elseif ($calc->isPastDate($mday, $month, $year))
	//	$resp->alert('Maturity Date can not be a past date');
	elseif (@mysql_num_rows($depr) > 0)
		$resp->alert('Some payments have already been made. No changes can be made now.');
	else
	{
		$resp->confirmCommands(1, "Do you really want to update?");
		$resp->call('xajax_updatePayable', $pid, $pname, $creditor, $initval, $date,  $type, $from_date,$to_date,$branch_id,$rate);
	}
	return $resp;
}

function updatePayable($pid, $pname, $creditor, $initval, $date,  $type, $date,$branch_id,$rate)
{       $content ="";
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$date = $date." ".date('H:i:s');
	$former_res = mysql_query("select * from payable where id='".$pid."'");
	$former = mysql_fetch_array($former_res);
	start_trans();
	if($former['bank_account'] > 0){
		if(! mysql_query("update bank_account set account_balance=account_balance - ".$former['amount']." + ".$initval."")){
			$resp->alert("Payable not updated! \n Could not update the bank account");
			return $resp;
		}
	}
	$res = @mysql_query("update payable set maturity_date='".$date."', vendor='".$creditor."', amount=$initval where id='".$pid."' ");
	if (@mysql_affected_rows() > 0){
		commit();
		$content .= "<font color=green><b>".$pname."updated successfully.</b></font><br>";
		////////////////////////////////
		$accno = mysql_fetch_assoc(mysql_query("select accounts.account_no,accounts.name from accounts join payable on accounts.id=payable.account_id where payable.id=".$pid));
		$action = "update payable set maturity_date='".$date."', vendor='".$creditor."', amount=$initval where id=$pid";
			$msg = "Updated payable set amount:".number_format($initval,2).", vendor:".$creditor.", maturity date:".$date." on ac/no:".$accno['account_no']." - ".$accno['name'];
			log_action($_SESSION['user_id'],$action,$msg);
			///////////////////
		}
		else{
		$content .= "<font color=red><b>ERROR: ".$pname." not updated!</b></font><br>";
		rollback();
	}

	//$resp->call('xajax_listPayables',  $type, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday);
	$resp->call('xajax_editPayableForm', $pid, $type, $account, $contact, $from_date, $to_date, $branch_id,$rate);
	$resp->assign("status", "innerHTML", $content);
	return $resp;
}

function deletePayable2($pid, $type, $account, $contact, $date, $branch_id,$rate)
{
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	$depr = @mysql_query("select date from payable_paid where payable_id = $pid");
	if (@mysql_num_rows($depr) > 0)
		$resp->alert("Cannot delete Payable: Payments have already been made.");
	else
	{
		$resp->confirmCommands(1, "Do you really want to delete?");
		$resp->call('xajax_deletePayable', $pid, $type, $account, $contact, $date, $branch_id,$rate);
	}
	//$resp->assign("status", "innerHTML", "test");
	return $resp;
}


function deletePayable($pid, $type, $account, $contact,$date, $branch_id,$rate)
{
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	$former_res = mysql_query("select * from payable where id='".$pid."'");
	$former = mysql_fetch_array($former_res);
	start_trans();
	if($former['bank_account'] > 0){
		if(! mysql_query("update bank_account set account_balance=account_balance - ".$former['amount']."")){
			$resp->alert("Payable not deleted! \n Could not update the bank account");
			return $resp;
		}
	}

	$accno = mysql_fetch_assoc(mysql_query("select account_no,name from accounts join payable on accounts.id=payable.account_id where payable.id=".$pid));

	$res = @mysql_query("delete from payable where id = $pid");
	if (@mysql_affected_rows() > 0){
		/////////////////////////
		mysql_query("update other_funds set date_payable='0000-00-00' where id='".$former['fund_id']."'");
		$action = "delete from payable where id = $pid";
		$msg = "Deleted a sum of: ".number_format($former['amount'],2)." from payables on ac/no: ".$accno['account_no']." - ".$accno['name'];
		
			log_action($_SESSION['user_id'],$action,$msg);
			///////////////////
		commit();
		$content .= "<font color=green><b>Payable deleted successfully.</b></font><br>";
		
		}
		else{
		$content .= "<font color=green><b>Error: Failed to delete Payable. </b></font><br>";
		rollback();
	}
	$resp->assign("status", "innerHTML", $content);
	$resp->call('xajax_listPayables', $type, $account, $contact, $date,$branch_id,$rate);
	return $resp;
}

function makePaymentForm($pid, $type, $account, $contact, $date,$branch_id,$rate)
{
	$resp = new xajaxResponse();
	$pdres = @mysql_query("select sum(amount) as amt_paid from payable_paid where payable_id = $pid");
	$pdresInt=@mysql_query("select sum(interest) as int_paid from payable_paid where payable_id = $pid");
	$acc = @mysql_query("select ac.name, ba.id, ba.bank, ac.account_no from accounts ac join bank_account ba on ac.id = ba.account_id ");
	if (@mysql_num_rows($acc) > 0)
	{
		$accts .= "<option value=''>&nbsp;</option>";
		while ($acc_row = @mysql_fetch_array($acc))
		{
			$accts .= "<option value='".$acc_row['id']."'>".$acc_row['account_no']." -". $acc_row['bank']."".$acc_row['name']."</option>";
		}
	}
	$pres = @mysql_query("select ac.account_no, ac.name, p.vendor, sum(p.amount) as amt_due from payable p join accounts ac on ac.id = p.account_id where p.id = $pid group by p.id");
	if (@mysql_num_rows($pres) > 0)
	{
		$prow = @mysql_fetch_array($pres);
		if (@mysql_num_rows($pdres) > 0)
		{
			$pdrow = @mysql_fetch_array($pdres);
			$balance = intval($prow['amt_due']) - intval($pdrow['amt_paid']);
		}
		else
			$balance = intval($prow['amt_due']);
			$int=mysql_fetch_array($pdresInt);
			$interest=$int['int_paid']==NULL?0.0:$int['int_paid'];
			
		if ($balance <= 0) // payable has already been cleared
			//$resp->alert("This payable has already been cleared!");
		{
			$report = mysql_query("select p.id as id, p.amount as begBalance, p.voucher_no as receipt, p.date as date, r.amount as initBal, r.id as rid from accounts ac join payable r on ac.id = r.account_id join payable_paid p on r.id=p.payable_id where r.id='".$pid."' order by p.date desc");
			$content = '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">MAKE PAYMENT</h3>
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">
 <thead><th>Date</th><th>Amount</th><th>Voucher No.</th><th></th></thead><tbody>';
			$num = 1;
			$rem = 0;
			$count = mysql_num_rows($report);
			while($row=mysql_fetch_assoc($report))
			{
			//$color = ($num%2==0) ?"white":"lightgrey"; 
			$bal = $row['initBal']-$rem;
			$rem += $row['begBalance'];
			$end = $bal-$row['begBalance'];
			if($num != $count)
			{
			$content .= "<tr><td>".$row['date']."</td><td>".number_format($row['begBalance'],0)."</td><td>".$row['receipt']."</td><td>--</td></tr>";
			}
			else
			{
				
				$content .= "<tr><td>".$row['date']."</td><td>".number_format($row['begBalance'],2)."</td><td>".$row['receipt']."</td><td><a onclick=\"xajax_deletePayment2('".$row['id']."','".$row['rid']."','".$type."','".$account."','".$contact."','".$from_date."','".$to_date."', '".$branch_id."')\">Delete</a></td></tr>";
			}
			$num++;
			}

			$content .= "</tbody></table><table width='50%' align='CENTER'><tr><td align='CENTER'><button type='button' class='btn btn-default' onclick=\"xajax_listPayables('".$type."', '".$account."', '".$contact."', '".$from_date."','".$to_date."','".$branch_id."'); return false;\">Back</button></a></td></tr></table>";
	}
		else
		{
			$content .= "listPayments($pid, $type, $account, $contact, $from_date,$to_date, $branch_id)";
			
				    $content .="<form method='post' class='panel form-horizontal form-bordered'>";
$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h4 class="semibold text-primary mt0 mb5">REGISTER PAYMENT FOR PAYABLE: '.$prow['account_no']." -".$prow['name'].'</h4>
                                               		 <p class="text-default nm"></p>
                                           	 </div>
                                        </div>';
                                     $content .= '<div class="form-group">
                                             
                                            <label class="col-sm-3 control-label">Amount Due:</label>
                                            <div class="col-sm-6">
                                            <span><i>'.$balance.'</i></span>
                                           <input type="hidden" id="amt_due" name="amt_due" value="'.$balance.'" class="form-control">
                                            </div></div>';
                                            
                                      $content .= '<div class="form-group">
                                             
                                            <label class="col-sm-3 control-label">Amount Due:</label>
                                            <div class="col-sm-6">
                                            <span><i>'.$interest.'</i></span>
                                           <input type=hidden name="int_due" id="int_covered" value="'.$interest.'" class="form-control">
                                            </div></div>';
				    	
					 $content .= '<div class="form-group">
                                             
                                            <label class="col-sm-3 control-label">Amount paid:</label>
                                            <div class="col-sm-6">
                                           <input onkeyup="format_as_number(this.id)" type="numeric" id="amt_paid" name="amt_paid"  class="form-control">
                                            </div></div>';
                                            
					 $content .= '<div class="form-group">
                                             
                                            <label class="col-sm-3 control-label">Amount paid:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" id="int_paid" name="int_paid"  class="form-control">
                                            </div></div>';
                                            
                                            $content .= '<div class="form-group">
                                             
                                            <label class="col-sm-3 control-label">Amount paid:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" id="voucher" name="voucher"  class="form-control">
                                            </div></div>';
                                            
                                            $content .= '<div class="form-group">
                                             
                                            <label class="col-sm-3 control-label">Date of Payment:</label>
                                            <div class="col-sm-6">
                                          <input type="text" class="form-control" id="pdate" name="pdate" placeholder="" />
                                            </div></div>';
					
					    $content .= '<div class="form-group">
                                             
                                            <label class="col-sm-3 control-label">Source Bank Account:</label>
                                            <div class="col-sm-6">
                                           <select name="bank_account" id="bank_account" class="form-control">$accts</select>
                                            </div></div>';
					
					$content .= "<div class='panel-footer'><button type='reset' class='btn btn-default' onclick=\"xajax_listPayables('".$type."', '".$account."', '".$contact."',  '".$from_date."','".$to_date."', '".$branch_id."','".$rate."')\">Back</button>&nbsp;
                                            <button type='button' class='btn btn-primary' onclick=\"xajax_makePayment('".$pid."', '".$balance."', getElementById('amt_paid').value, getElementById('voucher').value, getElementById('bank_account').value, getElementById('pdate').value,'".$type."', '".$account."', '".$contact."', '".$from_date."', '".$to_date."', '".$branch_id."','".$rate."',getElementById('int_paid').value); return false;\">Save</button>";
                                         $content .= '</div></form></div>';
                                         
                                         $resp->call("createDate","pdate");
					
		}
	}
	else{
		$cont = "<font color=red><b>ERROR: Payable not found!</b></font>";
		$resp->assign("status", "innerHTML", $cont);
	return $resp;
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function makePayment($pid, $balance, $amt_paid, $voucher, $bank_account, $pdate, $type, $account, $contact,$from_date,$to_date,$branch_id,$rate,$int_paid)
{
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	//$date = sprintf("%d-%02d-%02d", $year, $month, $mday);
	$date = $pdate." ".date('H:i:s');
	$trans = 0;
	$bank_row = @mysql_fetch_array(@mysql_query("select * from bank_account where id = $bank_account"));
	if ($amt_paid == '' || $bank_account == '')
	{
		$resp->alert("You must enter the amount to be paid.");
		return $resp;
	}elseif(preg_match("/\D/", $amt_paid)){
		$resp->alert("You must enter only digits for Amount paid");
		return $resp;
	}elseif ($amt_paid > $balance)
	{
		$resp->alert("You have entered an amount greater than what is owed!");
		return $resp;
	}
	/*elseif ((intval($bank_row[account_balance]) - $amt_paid) < intval($bank_row[min_balance]) || (intval($bank_row[account_balance]) - $amt_paid) <=0)
	{
		$resp->alert("There are insufficient funds on the bank account to cover the payment.");
		return $resp;
	}*/
	elseif (($balance - $amt_paid) == 0)
	{
		@mysql_query("START TRANSACTION");
		$trans = 1;
		$cres = @mysql_query("update payable set status='cleared' where id=$pid");
	}
	if ($trans == 0)
	{
		@mysql_query("START TRANSACTION");
		$trans = 1;
	}
	$branch_id = mysql_fetch_assoc(mysql_query("select branch_id from payable where id=".$pid));
	if ($voucher != "")
		$res = @mysql_query("INSERT INTO payable_paid (payable_id, amount, bank_account, voucher_no, date,branch_id,interest) VALUES ($pid, $amt_paid, $bank_account, '".$voucher."', '".$date."',".$branch_id['branch_id'].",".$int_paid.") ");
	else
		$res = @mysql_query("INSERT INTO payable_paid (payable_id, amount, bank_account, date,branch_id,interest) VALUES ($pid, $amt_paid, $bank_account, '".$date."',".$branch_id['branch_id'].",".$int_paid.") ");
	
	if (isset($res) && @mysql_affected_rows() > 0)
	{
		$new_bal = intval($bank_row[account_balance]) - $amt_paid;
		$bres = @mysql_query("update bank_account set account_balance = $new_bal where id = $bank_account");
		if (isset($bres) && @mysql_affected_rows() != -1)
		{
			
			$content .= "<font color='red'>Successfully registered payment.</font><br>";
			////////////////
			$accno = mysql_fetch_assoc(mysql_query("select account_no,name from accounts join payable on accounts.id=payable.account_id where payable.id=".$pid));

			$action = "INSERT INTO payable_paid (payable_id, amount, bank_account, voucher_no, date) VALUES ($pid, $amt_paid, $bank_account, '".$voucher."', '".$date."')";
			$msg = "Registered a payable of: ".$amt_paid." into ac/no: ".$accno['account_no']." - ".$accno['name'];
			log_action($_SESSION['user_id'],$action,$msg);
			///////////////////
			@mysql_query("COMMIT");
		}
		else
		{
			$content .= "<font color=red>Bank balance not updated successfully.</font><br>";
			@mysql_query("ROLLBACK");
		}
		$resp->assign("status", "innerHTML", $content);
		$resp->call('xajax_makePaymentForm', $pid, $type, $account, $contact,$from_date,$to_date,$branch_id,$rate);
		return $resp;
	}
	else
	{
		$content .= "<font color='red'>Error: Payment registration failed.</font><br>";
		@mysql_query("ROLLBACK");
	}
	$resp->assign("status", "innerHTML", $content);
	$resp->call('makePaymentForm', $pid, $type, $account, $contact,$from_date,$to_date,$branch_id);
	return $resp;
}

function listPayments($payable_id, $type, $account, $contact, $from_date,$to_date, $branch_id)
{
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	$res = @mysql_query("select pd.id, pd.amount, pd.date, pd.voucher_no, p.interest from accounts ac join payable p on ac.id = p.account_id join payable_paid pd on p.id = pd.payable_id where pd.payable_id='".$payable_id."' order by pd.date desc");
	if (@mysql_num_rows($res) > 0)
	{
		$former = mysql_fetch_array(mysql_query("select a.account_no, a.name from payable p join accounts a on p.account_id=a.id where p.id='".$pid."'"));
		
			$content = '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">PAYMENTS MADE FOR PAYABLE: '.$former['account_no'] .'-'.$former['name'].'</h3>
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">
			    
			   
			     <thead><th>Date</th><th>Amount</th>Interest<th></th><th>Voucher No.</th><th></th></thead><tbody>';
			   
		$i=0;	
		while ($row = @mysql_fetch_array($res))
		{
			//$color= ($i%2 == 0) ? "lightgrey" : "white";
			$content .= "
				    <tr><td>".$row['date']."</td><td>".$row['amount']."</td><td>".$row['interest']."</td><td>".$row['voucher_no']."</td><td><button type='button' class='btn btn-default'  onclick=\"xajax_deletePayment2('".$row['id']."', '".$payable_id."', '".$type."', '".$account."', '".$contact."', '".$from_date."','".$to_date."', '".$branch_id."'); return false;\">Delete</button></td></tr>
				    ";
		}
		$content .=  "</tbody></table>
			    </div>
			    ";
			    $resp->call("createTableJs");
	}
	else {
		$cont = "<font color='red'><b>No payments for this Payable  yet.</b></font>";
		$resp->assign("status", "innerHTML", $content);	
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function deletePayment2($pid, $payable_id, $type, $account, $contact, $from_date,$to_date, $branch_id)
{
    $resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}

    $res = @mysql_query("select pd.amount,pd.bank_account, ba.min_balance, ba.account_balance from payable_paid pd join bank_account ba on pd.bank_account = ba.id where pd.id = $pid");
    if (@mysql_num_rows($res) > 0)
	{
		$row = @mysql_fetch_array($res);
		$expected_bal = intval($row['account_balance']) + intval($row['amount']);
		//if ($expected_bal < intval($row['min_balance']))
          //      	$resp->alert("Cannot delete Payment: Bank balance not sufficient.");
		//else
		//{
			$resp->confirmCommands(1, "Do you really want to delete?");
			$resp->call('xajax_deletePayment', $pid, $expected_bal, $row['bank_account'], $payable_id, $type, $account, $contact, $from_date,$to_date, $branch_id);
		//}
	}
    else
        {
		$cont = "An error occured: " . mysql_error();
        $resp->assign("status", "innerHTML", $cont);        
    }
    return $resp;
}

function deletePayment($pid, $expected_bal, $bank_account_id, $payable_id, $type, $account, $contact, $from_date,$to_date, $branch_id)
{
        $resp = new xajaxResponse();
        if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	@mysql_query("START TRANSACTION");
	$updres = @mysql_query("update bank_account set account_balance = $expected_bal where id = $bank_account_id");
	if (@mysql_affected_rows() != -1)
	{
		$updres2 = @mysql_query("UPDATE payable set status='pending' where id = (select payable_id from payable_paid where id = $pid)");
		if (@mysql_affected_rows() != -1)
		{
			$accno = mysql_fetch_assoc(mysql_query("select a.account_no,a.name,pp.amount from accounts a join payable p on a.id=p.account_id join payable_paid pp on p.id=pp.payable_id where pp.id=".$pid));
        		$res = @mysql_query("delete from payable_paid where id = $pid"); // ensure atomicity of update and delete operations.
        		if (@mysql_affected_rows() != -1)
			{

                $content .= "<font color=red><b>Payment deleted successfully.</b></font><br>";
				///////////////////////////////
				
				$action = "delete from payable_paid where id = $pid";
				$msg = "Deleted from payable  amount:".number_format($accno['amount'],2) ." on ac/no:".$accno['account_no'].$accno['name'];
			log_action($_SESSION['user_id'],$action,$msg);
			///////////////////
				
				@mysql_query("COMMIT");
			}
        		else
			{
				@mysql_query("ROLLBACK");
                		$content .= "<font color=red><b>Error: Failed to delete Payment. </b></font><br>";
			}
		}
		else 
		{
			@mysql_query("ROLLBACK");
			$content .= "<font color=red><b>Error: Failed to update payable status.</b></font><br>"; 
		}
	}
	else
	{
		@mysql_query("ROLLBACK");
		$content .= "<font color=red><b>Error: Failed to update bank account. ".mysql_error()."</b></font><br>";
	}
        $resp->assign("status", "innerHTML", $content);
        $resp->call('xajax_makePaymentForm', $payable_id, $type, $account, $contact, $from_date,$to_date, $branch_id);
        return $resp;
}

?>
