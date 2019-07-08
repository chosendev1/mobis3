<?php

$xajax->registerFunction("addReceivableForm");
$xajax->registerFunction("addReceivableForm1");
$xajax->registerFunction("addReceivable");
$xajax->registerFunction("listReceivables");
$xajax->registerFunction("editReceivableForm");
$xajax->registerFunction("updateReceivable");
$xajax->registerFunction("updateReceivable2");
$xajax->registerFunction("deleteReceivable2");
$xajax->registerFunction("deleteReceivable");

function addReceivableForm()
{       $fixed_acc ="";
	$resp = new xajaxResponse();
	//$fix = @mysql_query("select account_id, id from receivable order by id");
	$level1 = @mysql_query("select id, account_no, name from accounts where (account_no >=1141 and account_no <=1149) order by account_no");
	while ($level1row = @mysql_fetch_array($level1))
	{
		 $fixed_acc .= "<option value='".$level1row['id']."'>".$level1row['account_no']." -". $level1row['name']."</option>";
	} // end while level1
	$content ="<div class='col-md-12'><form method='post' class='panel form-horizontal panel-default'>";
$content .= '
  			  		<div class="panel-heading">
                                 		
                                                	<h4 >REGISTER RECEIVABLE</h4>
                                               		 <p class="text-default nm"></p>
                                           	 </div>
                                       <div class="panel-body">';
	                           $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Branch:</label>
                                            <div class="col-sm-6"><span>'.branch().'</span></div></div>';
                                            
                                     $content .= '<div class="form-group">
                                             
                                            <label class="col-sm-3 control-label">Select Account:</label>
                                            <div class="col-sm-6">
                                           <select id="acc_id" name="acc_id" class="form-control">'.$fixed_acc.'</select  >
                                            </div></div>';                                                    
		                     $content .= '<div class="form-group">
                                             
                                            <label class="col-sm-3 control-label">Initial Amount Due:</label>
                                            <div class="col-sm-6">
                                           <input onkeyup="format_as_number(this.id)" type="numeric" id="initval" name="initval"  class="form-control">
                                            </div></div>';
		    	
                                            
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Maturity Date:</label>
                                            <div class="col-sm-6">
                                           <input type="text" class="form-control" id="date" name="date" placeholder="date" />
                                            </div></div>';
		    	 $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Contact Person:</label>
                                            <div class="col-sm-6">
                                           <input type="text" id="contact" name="contact" class="form-control">
                                            </div></div>';
                                            
                                            
		    	$content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Details:</label>
                                            <div class="col-sm-6">
                                           <textarea name="details" id="details" cols="40" rows="3" class="form-control"></textarea>
                                            </div></div>';
                                            
                           $content .= "<div class='panel-footer'><button type='reset' class='btn btn-default' onclick=\"xajax_addReceivableForm('".$prefix1."', '".$prefix2."')\">Reset</button>
                                            <button type='button' class='btn btn-primary' onclick=\"xajax_addReceivable(document.getElementById('acc_id').value, document.getElementById('initval').value, document.getElementById('date').value,document.getElementById('contact').value, document.getElementById('details').value,getElementById('branch_id').value); return false;\">Save</button>";                  
			 $content .= '</div></form></div>';
			 $resp->call("createDate","date");
		 
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function addReceivableForm1()
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

function addReceivable($acc_id, $initval, $date, $contact, $details,$branch_id)
{       
        list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$initval=str_ireplace(",","",$initval);
	
	if ($acc_id == '' || $initval == '')
	{
		$resp->alert('Please do not leave any field blank.');
	}
	elseif (!$calc->isValidDate($mday, $month, $year))
	{
		$resp->alert('Invalid Date');
	}
	/* elseif ($calc->isPastDate($mday, $month, $year))
	{
		$resp->alert('Maturity Date can not be a past date.');
	} */
	else
	{
		////$date = sprintf("%d-%02d-%02d", $date);
		$res = mysql_query("insert into receivable (account_id, amount, maturity_date,contact,details,branch_id) values ($acc_id, $initval, '".$date."', '".$contact."', '".$details."',".$branch_id.")");
		if (@mysql_affected_rows() > 0){
			$accno = mysql_fetch_assoc(mysql_query("select account_no,name from accounts where id=".$acc_id));
			///////////////
			$action = "Insert into receivable values ($acc_id, $initval, $date, $contact, $details)";
			$msg = "Registered a receivable of: ".$initval." into ac/no: ".$accno['account_no']." - ".$accno['name'];
			log_action($_SESSION['user_id'],$action,$msg);
			//////////////
			$content .= "<font color=blue>Receivable registered successfully.</font><br>";
		}
		else
			$resp->alert("Receivable not registered! \n Could not register the entry");
	}
	$resp->assign("status", "innerHTML", $content);
	return $resp;
}

function listReceivables($type, $account, $from_date,$to_date,$branch_id)
{       
        list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	$branch = ($branch_id=='all'||$branch_id=='')?NULL:"and branch_id=".$branch_id;
	
$content = '
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <p><h5 class="semibold text-primary mt0 mb5">LIST OF '.strtoupper($type).' RECEIVABLES</h5></p>
                                       
                            <div class="panel-body">
                            
                      <div class="form-group">
                                        <div class="col-sm-3">
                                            <label class="control-label">Account:</label>
                                            <select id="account" class="form-control"><option value="">All';
	$sth = mysql_query("select distinct id as id, account_no, name from accounts where id in (select account_id from receivable)");
	while($row = mysql_fetch_array($sth)){
		$content .= "<option value='".$row['id']."'>".$row['account_no'] ." - ".$row['name']."</option>";
	}
	$content .='</select>                                         
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
                                            <div class="col-sm-3">
                                            <label class="control-label">.</label>';
                                            
	                                          $content .="<input type='button' class='btn  btn-primary' value='Search' onclick=\"xajax_listReceivables('".$type."', getElementById('account').value, getElementById('from_date').value, getElementById('to_date').value, getElementById('branch_id').value)\">
                                        </div>
                                    </div>
                                </div>";
                                                                                        
	$content .= "
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","from_date");
		    $resp->call("createDate","to_date");
                     //$resp->assign("display_div", "innerHTML", $content);
                     
	if($from_date == '' || $to_date==''){
		
		$cont= "<font color=red>Select period for the income</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}
	else{
	$from_date = sprintf("%04d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%04d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
	$selectedAccount = ($account=='all') ? NULL: "and r.account_id='".$account."'";
	if (strtolower($type) == 'all')
	{
		$res = @mysql_query("select ac.account_no as account_no, ac.id as acc_id, ac.name as acc_name, r.id as rid, r.amount as amount, r.maturity_date as mdate, r.status as status, r.contact as contact, r.details as details from accounts ac, receivable r where r.account_id = ac.id and r.maturity_date>='".$from_date."' and r.maturity_date<='".$to_date."' ".$selectedAccount." ".$branch." order by r.maturity_date desc");
	}
	elseif (strtolower($type) == 'pending')
	{
		$res = @mysql_query("select ac.account_no as account_no, ac.id as acc_id, ac.name as acc_name, r.id as rid, r.amount as amount, r.maturity_date as mdate, r.status as status, r.contact as contact, r.details as details from accounts ac join receivable r on r.account_id = ac.id where lower(r.status) = 'pending' and r.maturity_date >='".$from_date."' and r.maturity_date <= '".$to_date."' ".$selectedAccount." ".$branch." order by r.maturity_date desc");
	}
	elseif (strtolower($type) == 'cleared')
	{
		$res = @mysql_query("select ac.id as acc_id, ac.account_no as account_no, ac.name as acc_name, r.id as rid, r.amount as amount, r.maturity_date as mdate, r.status as status, r.contact as contact, r.details as details from accounts ac join receivable r on r.account_id = ac.id where lower(r.status) = 'cleared' and r.maturity_date >='".$from_date."' and r.maturity_date <= '".$to_date."' ".$selectedAccount." ".$branch." order by r.maturity_date desc");
	}

	if (@mysql_num_rows($res) > 0)
	{
		$content .=
			   "<a href='list_receivables.php?account=".$type."&branch_id=".$branch_id."&type=".$account."&contact=".$msg."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."' target=blank()><b>Printable Version</b></a> | <a href='list_receivables.php?account=".$type."&branch_id=".$branch_id."&type=".$account."&contact=".$msg."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&format=excel' target=blank()><b>Export Excel</b></a> | <a href='list_receivables.php?account=".$type."&branch_id=".$branch_id."&type=".$account."&contact=".$msg."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&format=word' target=blank()><b>Export Word</b></a>";
		$content = '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">LIST OF '.strtoupper($type).' RECEIVABLES</h3>
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
 		
		$content .="<thead'>
			       <th>#</th><th>Account</th><th>Amount</th><th>Maturity Date</th><th>Balance</th><th>Status</th><th>Contact Person</th><th>Details</th><th>Payments</th><th></th>
			   </thead><tbody>
			    ";
			
		$i=1;
		while ($fxrow = @mysql_fetch_array($res))
		{
			//$color = ($i %2 ==0) ? "lightgrey" : "white";
			$col = mysql_fetch_array(mysql_query("select sum(amount) as amount from collected where receivable_id='".$fxrow['rid']."'"));
			$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
			$balance = $fxrow['amount'] - $col_amt;
			$content .= "
				    <tr><td>".$i."</td>
				    	<td>".$fxrow['account_no']." -". $fxrow['acc_name']."</td><td>".number_format($fxrow['amount'], 2)."</td><td>".$fxrow['mdate']."</td><td>".number_format($balance, 2)."</td><td>".$fxrow['status']."</td><td>".$fxrow['contact']."</td><td>".$fxrow['details']."</td><td><a href='javascript:;' onclick=\"xajax_registerPaymentForm('".$fxrow['rid']."', '".$type."', '".$from_year."', '".$from_month."', '".$from_mday."', '".$to_year."', '".$to_month."', '".$to_mday."','".$branch_id."'); return false;\">Payments</a></td><td><a onclick=\"xajax_editReceivableForm('".$fxrow['rid']."', '".$type."', '".$from_year."', '".$from_month."', '".$from_mday."', '".$to_year."', '".$to_month."', '".$to_mday."','".$branch_id."'); return false;\">Edit</a> &nbsp; &nbsp;<a  onclick=\"xajax_deleteReceivable2('".$fxrow['rid']."', '".$type."', '".$from_year."', '".$from_month."', '".$from_mday."', '".$to_year."', '".$to_month."', '".$to_mday."','".$branch_id."'); return false;\">Delete</a></td>
				    </tr>
				    ";
			$i++;
		}
		$content .= "</tbody></table></div>";
	}
	else{
		$cont = "<font color=red>No Receivables registered yet.</font>";
		$resp->assign("status", "innerHTML", $cont);
	return $resp;
	}
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function editReceivableForm($pid, $type, $from_date,$to_date,$branch_id)
{
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$tmp = @mysql_query("select date from collected where receivable_id = $pid");
	if (@mysql_num_rows($tmp) > 0){
		$resp->alert("You cannot edit this receivable: Some payments have been made");
		return $resp;
	}
	$res = mysql_query("select  ac.name, r.id, r.amount, r.account_id, date_format(maturity_date, '%Y') as year, date_format(maturity_date, '%m') as month, date_format(maturity_date, '%d') as mday, r.contact as contact, r.details as details from  receivable r join accounts ac on ac.id = r.account_id where r.id = $pid");
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
                                            <label class="col-sm-3 control-label">Maturity Date:</label>
                                            <div class="col-sm-6">
                                          <input type="text" class="form-control" id="date" name="date" value="'.$row['mday']."/".$row['month']."/".$row['year'].'" />
                                            </div></div>'; 
                                            
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Contact Person:</label>
                                            <div class="col-sm-6">
                                           <input type="text" id="contact" name="contact" value="'.$row['contact'].'" class="form-control">
                                            </div></div>';  
                                            
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Details:</label>
                                            <div class="col-sm-6">
                                          <textarea name="details",name="details" class="form-control" cols="40" rows="3">'.$row['details'].'</textarea> >
                                            </div></div>';             
                                        
                                           
				            $content .= "<div class='panel-footer'><button type='reset' class='btn btn-default'  onclick=\"xajax_listReceivables('".$type."', '".$account."', '".$from_date."','".$to_date."','".$branch_id."')\">Back</button>
                                            <button type='button' class='btn btn-primary' onclick=\"xajax_updateReceivable2('".$row['id']."', '".$row['name']."', document.getElementById('initval').value, document.getElementById('date').value,document.getElementById('contact').value, document.getElementById('details').value,'".$branch_id."'); return false;\">Save</button>";
                                            $content .= '</div></form></div>';
                                            $resp->call("createDate","date");
                                            
	}
	else {
		$cont = "<font color=red><b>ERROR: Receivable not found!</b></font>";
		$resp->assign("display_div", "innerHTML", $cont);
	return $resp;
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function updateReceivable2($pid, $pname, $initval, $date, $contact, $details,$branch_id)
{
	$resp = new xajaxResponse();
	$initval=str_ireplace(",","",$initval);
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$calc = new Date_Calc();
	$depr = @mysql_query("select date from collected where receivable_id = $pid");
	if ($pid == '' || $initval == '')
		$resp->alert('Please do not leave any fields blank.');
	/*elseif ($calc->isPastDate($mday, $month, $year))
		$resp->alert('Maturity Date can not be a past date');
		*/
	elseif (@mysql_num_rows($depr) > 0)
		$resp->alert('Some payments have already been made. No changes can be made now.');
	else
	{
		$resp->ConfirmCommands(1, "Do you really want to update?");
		$resp->call('xajax_updateReceivable', $pid, $pname, $initval, $date, $contact, $details,$branch_id);
	}
	return $resp;
}

function updateReceivable($pid, $pname, $initval, $date, $contact, $details,$branch_id)
{       
$content = "";
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$date = $date." ".date('H:i:s');
	$res = @mysql_query("update receivable set maturity_date='".$date."', amount=$initval, contact='".$contact."', details='".$details."' where id='".$pid."'");
	if (@mysql_affected_rows() > 0){
		////////////////////////////
		$accno = mysql_fetch_assoc(mysql_query("select account_no,name from accounts where id=(select account_id from receivable where id='".$pid."')"));
		$action = "update receivable set maturity_date='".$date."', amount=$initval, contact='".$contact."', details='".$details."' where id=$pid";
		$msg = "Updated Receivable set maturity_date: ".$date.", amount=".$initval." On ac/no: ".$accno['account_no']."-".$accno['name'];
		log_action($_SESSION['user_id'],$action,$msg);
		//////////////////////////////
		$content .= "<font color=green><b>".$pname." updated successfully.</b></font><br>";
	}
	else
		$content .= "<font color=red><b>ERROR: ".$pname." not updated!</b></font><br>";
	$resp->assign("status", "innerHTML", $content);
	$resp->call('xajax_listReceivables', $type, $account, $from_date,$to_date,$branch_id);
	return $resp;
}

function deleteReceivable2($pid, $type, $from_date,$to_date, $branch_id)
{
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	$depr = @mysql_query("select date from collected where receivable_id = $pid");
	if (@mysql_num_rows($depr) > 0)
		$resp->alert("Cannot delete Receivable: Payments have already been made.");
	else
	{
		$resp->ConfirmCommands(1, "Do you really want to delete?");
		$resp->call('xajax_deleteReceivable', $pid, $type, $from_date,$to_date,$branch_id);
	}
	//$resp->assign("status", "innerHTML", "test");
	return $resp;
}


function deleteReceivable($pid, $type, $from_date,$to_date, $branch_id)
{$content = "";
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	$accno = mysql_fetch_assoc(mysql_query("select account_no,name from accounts where id=(select account_id from receivable where id='".$pid."')"));
	$amount = mysql_fetch_assoc(mysql_query("select amount from receivable where id=".$pid));
	$res = @mysql_query("delete from receivable where id = $pid");
	if (@mysql_affected_rows() > 0){
		///////////////////////////

		$action = "delete from receivable where id = $pid";
		$msg  = "Deleted from receivables amount:".number_format($amount['amount'],2)." from ac/no: ".$accno['account_no']." - ".$accno['name'];
		log_action($_SESSION,$action,$msg);
		/////////////////////////
		
		$content .= "<font color=green><b>Receivable deleted successfully.</b></font><br>";
		
	}else
		$content .= "<font color=green><b>Error: Failed to delete Receivable. </b></font><br>";
	$resp->assign("status", "innerHTML", $content);
	$resp->call('xajax_listReceivables', $type, $account, $from_date,$to_date,$branch_id);
	return $resp;
}


?>
