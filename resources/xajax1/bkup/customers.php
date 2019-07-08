<?php
CAP_session::init();
$xajax->registerFunction("addCustForm");
$xajax->registerFunction("editCustForm");
$xajax->registerFunction("addCustomer");
$xajax->registerFunction("updateCustomer");
$xajax->registerFunction("deleteCustomer");
$xajax->registerFunction("delete2Customer");
$xajax->registerFunction("update2Customer");
$xajax->registerFunction("membersList");
$xajax->registerFunction("edit_photos");

$xajax->registerFunction("member_ledger");
$xajax->registerFunction("memsavings_ledger");
$xajax->registerFunction("memloan_ledger");

$xajax->registerFunction("add_group");
$xajax->registerFunction("list_groups");
$xajax->registerFunction("insert_group");
$xajax->registerFunction("edit_group");
$xajax->registerFunction("update_group");
$xajax->registerFunction("update2_group");
$xajax->registerFunction("delete_group");
$xajax->registerFunction("delete2_group");
$xajax->registerFunction("add_groupmember");
$xajax->registerFunction("remove_groupmember");
$xajax->registerFunction("uploadCustomersForm");
$xajax->registerFunction("loadBranches");
$xajax->registerFunction("mobileBankingSubscription");
$xajax->registerFunction("subscription");
$xajax->registerFunction("list_mbSubscribers");
function uploadCustomersForm(){
	$res = new xajaxResponse();
	//$res->alert("upload form is coming yeeeeah");
	$content .= 
			"<form method='post' action='customers/importCustomers' enctype='multipart/form-data' class='panel form-horizontal form-bordered'>";
	$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h4 class="semibold text-primary mt0 mb5"> Upload  Existing Customers</h4>
                                               		 <p class="text-default nm"></p>
                                           	 </div>
                                        </div><div id="responsediv" class="alert alert-dismissable alert-warning">
                                        
					<br/>Click mirror to download the format
					<a href="./resources/templates/customerfile.xls" target="_blank" >Click here for Mirror</a>
                                        </div>
                                        ';
         $content .='<div class="form-group">
                    <label class="col-sm-3 control-label">Organization:</label>
                    <div class="col-sm-6"><span><select class="form-control" name="company" id="company" onchange="xajax_loadBranches(this.value);" required>
                    	<option value="">Select Company</option>
                    	'.libinc::getItem("company","companyId","companyName","").'
                    </select>
                    </span></div></div>';
                     $content .='<div class="form-group">
                    <label class="col-sm-3 control-label">Branch:</label>
                    <div class="col-sm-6"><span>
                    <div id="branchDiv">
                    
                    </div>
                    </span></div></div>';                               
	$content .='<div class="input-group">
		<input type="text" name="fname_" id="fname_" class="form-control"  readonly>
							       
								 <span class="input-group-btn">
                                                    <div class="btn btn-primary btn-file">
                                                        <span class="icon iconmoon-file-3"></span> Browse <input type="file" name="fname" id="fname" required>
                                                    </div>
                                                </span>
							    </div>';
		
	$content .='<div class="panel-footer"><input type="submit" class="btn btn-primary btn-large" name="Upload" id="Upload" value="Upload" ><button type="reset" class="btn btn-large">Cancel</button></div>
				</form>';

	$res->assign("display_div","innerHTML",$content);
	return $res;
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
//GENERATE SAVINGS LEDGER
function memsavings_ledger($mem_no, $mem_id, $save_acct, $from_year, $from_month, $to_year, $to_month)
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
		$acct_res = mysql_query("select mem.id as id from mem_accounts mem join savings_product s on mem.saveproduct_id=s.id where mem.mem_id='".$row['id']."' and s.type='free'");
		if(mysql_numrows($acct_res) > 1){
			$resp->call('xajax_memsavings_ledger_form', $row['id']);
			return $resp;
		}elseif(mysql_numrows($acct_res) ==1){
			$acct = mysql_fetch_array($acct_res);
			$save_acct = $acct['id'];
			$mem_id = $row['id'];
		}elseif(mysql_numrows($acct_res) ==0){
			$resp->alert("This Member has no savings account!");
			return $resp;
		}
	}
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
	
	$content = '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                        <h3 class="panel-title">INTERIM SAVINGS STATEMENT</h3>                          
                            </div>';
        $content .= "<table class='table table-striped table-bordered' id='table-tools'>";                    
                            
 		$content .= "<thead>
			<th>Date</th><th>Description</th><th>Debit</th><th>Credit</th><th>Account Balance</th></thead><tbody>";
		   $content .= "</tr>
		    <tr bgcolor=lightgrey>
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
	$content .= "</tbody></table></div></div>";
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
			</div>";
			
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
                               <p><h5 class="semibold text-primary mt0 mb5">SHARES</h5></p>                          
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
	}
	//if ($found_shares < 1)
	//	$content .= "<font color='red'>No shares activity yet registered for $mem[first_name] $mem[last_name]</font><br>";
	
     
	$acct_res = mysql_query("select ma.id as mem_acct_id, ac.name, ac.account_no from mem_accounts ma join savings_product sp on ma.saveproduct_id = sp.id join accounts ac on sp.account_id = ac.id where ma.mem_id = '$mem_id' and sp.type='free'");
	while($acct = mysql_fetch_array($acct_res)){
		$resp->call('xajax_memsavings_ledger', $mem_no, $mem_id, $acct['mem_acct_id'], $from_date, $to_dates);
	}
	$resp->call('xajax_memloan_ledger', $mem_no, $from_date, $to_date);
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

//REGISTER GROUP TO BORROW 
function add_group(){
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	$_SESSION['mem_array'] = array();
	$_SESSION['members']= '';
		
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">REGISTER GROUP</h3>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Branch:</label>
                                            <span>'.branch().'</span>      
                                        </div>                                        
                                    </div>
                                </div>
                                
                      <div class="form-group">
                                   <div class="row">
                                    <div class="col-sm-6">
                                            <label class="control-label">Group Name:</label>
                                            <input type=text name="group_name" id="group_name" value="All" class="form-control">
                                        </div>                                       
                                    </div>
                                    </div>                              
                                
                         <div id="from_div">       
                         <div class="form-group">
                                   <div class="row">
                                    <div class="col-sm-6">
                                            <label class="control-label">Members:</label>
                                           <div><select name="from" id="from" size=6>';
	$sth = mysql_query("select * from member order by first_name, last_name, mem_no");
	while($row = mysql_fetch_array($sth)){
		$content .= "<option value='".$row['id']."'>".$row['mem_no']." - ".$row['first_name']." ".$row['last_name'];
	}
	$content .="</select></div></div></div>
                        </div> ";
                        
                        
                       $content .="<div class='panel-footer'>
                                    
                                            <input type='button' class='btn btn-default' value ='Add' onclick=\"xajax_add_groupmember(getElementById('from').value, 0, 'add', 0);\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type='reset' class='btn btn-default' value ='Remove' onclick=\"xajax_remove_groupmember(getElementById('to').value, 0, 'add', 0);\">
                                        </div><br>";                                       
                                   
                                    
                         $content .='<div class="form-group">
                                   <div class="row">
                                    <div class="col-sm-6">
                                            <label class="control-label">Group Members</label><div><select name="to" id="to" size=6>
	<option>
	</select>
                                        </div></div>                                       
                                    </div>
                                    </div>';                          
                                    $content .='</div> </div> </form> </div> </div>';                                  
                                
                                
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;

}

//ADD GROUPMEMBER TO GROUP
function add_groupmember($mem_id, $no, $action, $group_id){
	$resp = new xajaxResponse();
	if($mem_id == '')
		$resp->alert("Please select the member to add");
	else{
		
		if($no==0)
			$_SESSION['members'] = "0, ".$mem_id."";
		else
			$_SESSION['members'] .= ", ".$mem_id."";
		$no++;
		//$resp->alert($_SESSION['members']);
		$content ="<div class='form-group'>
                                   <div class='row'>
                                    <div class='col-sm-6'>
                                            <label class='control-label'>Members:</label><div><select name='from' id='from' size=6><option value=''>";
		$sth = mysql_query("select * from member where id not in (".$_SESSION['members'].") order by first_name, last_name, mem_no");
		
		while($row = @mysql_fetch_array($sth)){
			$content .= "<option value='".$row['id']."'>".$row['mem_no']." - ".$row['first_name']." ".$row['last_name'];
		}
		$content .="</select></div></div></div></div>
                        </div>
                        <div class='panel-footer'>
                                        <input type='button' class='btn btn-default' value ='Add' onclick=\"xajax_add_groupmember(getElementById('from').value, ".$no.", '".$action."', '".$group_id."');\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type='button' class='btn btn-default' value ='Remove' onclick=\"xajax_remove_groupmember(getElementById('to').value, ".$no.", '".$action."', '".$group_id."');\"></div><div></div>";
                                        
                   $content .='<div class="form-group">
                                   <div class="row">
                                    </div></div>'; 
                                                         
                 $content .='<div class="form-group">
                                   <div class="row">
                                    <div class="col-sm-6">
                                    
                                            <label class="control-label">Group Members:</label><div><select name="to" id="to" size=6>';
	
		$mems = array($_SESSION['members']);
		$sth = mysql_query("select * from member where id in (".$_SESSION['members'].") order by first_name, last_name, mem_no");
		//$resp->assign("status", "innerHTML", "select * from member where id in (".$_SESSION['members'].") order by first_name, last_name, mem_no");
		while($row = @mysql_fetch_array($sth)){
			$content .= "<option value='".$row['id']."'>".$row['mem_no']." - ".$row['first_name']." ".$row['last_name'];
		}

	if($action == 'add'){
			$content .= "</select></div></div></div></div>
		<div class='panel-footer'>
		<input type=button name='submit' id='submit' class='btn btn-primary' value='Save' onclick=\"xajax_insert_group(getElementById('group_name').value, $no, getElementById('branch_id').value);\"> </div>";
		}elseif($action== 'edit'){
			$content .= "</select></div></div></div>
			<div class='panel-footer'>
		<input type=button class='btn btn-danger mb5' name='submit' id='submit' value='Delete' onclick=\"xajax_delete_group(".$group_id.");\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=button class='btn btn-primary' name='submit' id='submit' value='Update' onclick=\"xajax_update_group(".$group_id.", getElementById('group_name').value);\"></div>";
		}
		$resp->assign("from_div", "innerHTML", $content);
	}	
	return $resp;
}


//REMOVE GROUPMEMBER FROM GROUP
function remove_groupmember($mem_id, $no, $action, $group_id){
	$resp = new xajaxResponse();
	if($mem_id == '')
		$resp->alert("Please select the member to add");
	else{
		$match = "/[,]*[ ]*".$mem_id."/i";
		$_SESSION['members'] = preg_replace($match, "", $_SESSION['members']);
		$no--;
		$content ="<div class='form-group'>
                                   <div class='row'>
                                    <div class='col-sm-6'>
                                            <label class='control-label'>Members:</label><div><select name='from' id='from' size=6><option value=''>";
                                            
		$sth = mysql_query("select * from member where id not in (".$_SESSION['members'].") and id<>0 order by first_name, last_name, mem_no");
		if(@mysql_numrows($sth) >0){
			while($row = mysql_fetch_array($sth)){
				$content .= "<option value='".$row['id']."'>".$row['mem_no']." - ".$row['first_name']." ".$row['last_name'];
			}
		}else{
			$content .= "<option value=''>";
		}
		$content .="</select></div></div></div></div>
		
		<div class='panel-footer'>
                                        <input type='button' class='btn btn-default' value ='Add' onclick=\"xajax_add_groupmember(getElementById('from').value, ".$no.", '".$action."', ".$group_id.");\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type='button' class='btn btn-default' value ='Remove' onclick=\"xajax_remove_groupmember(getElementById('to').value, ".$no.", '".$action."', ".$group_id.");\"></div>";
                   $content .='<div class="form-group">
                                   <div class="row">
                                   <div class="col-sm-6">
                                    </div></div></div>';                       
                                        
                 $content .='<div class="form-group">
                                   <div class="row">
                                    <div class="col-sm-6">
                                            <label class="control-label">Group Members:</label><div><select name="to" id="to" size=6>';
		$sth = mysql_query("select * from member where id in (".$_SESSION['members'].") and id<>0 order by first_name, last_name, mem_no");
		//$resp->assign("status", "innerHTML", "select * from member where id in (".$_SESSION['members'].") order by first_name, last_name, mem_no");
		if(@ mysql_numrows($sth) > 0){
			while($row = mysql_fetch_array($sth)){
				$content .= "<option value='".$row['id']."'>".$row['mem_no']." - ".$row['first_name']." ".$row['last_name'];
			}
		}else{
			$content .= "<option value=''>";
		}
		if($action == 'add'){
			$content .= "</select></div></div></div></div>
		<div class='panel-footer'>
		<input type=button class='btn btn-primary' name='submit' id='submit' value='Save' onclick=\"xajax_insert_group(getElementById('group_name').value, $no, getElementById('branch_id').value);\"></div>";
		}elseif($action== 'edit'){
			$content .= "</select></div></div></div></div>
			<div class='panel-footer'>
		<input type=button class='btn btn-danger mb5' name='submit' id='submit' value='Delete' onclick=\"xajax_delete_group(".$group_id.").value);\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=button class='btn btn-primary mb5' name='submit' id='submit' value='Update' onclick=\"xajax_update_group(".$group_id.", getElementById('group_name').value);\"></div>";
		}
		$resp->assign("from_div", "innerHTML", $content);
	}	
	return $resp;
}


//INSERT GROUP
function insert_group($name, $no,$branch_id){
	$resp = new xajaxResponse();
	$sth = mysql_query("select * from loan_group where name='".$name."'");
	if($name=='')
		$resp->alert("You may not leave group name blank");
	elseif($no <= 0)
		$resp->alert("First add members to the group");
	else{
		//$resp->alert($_SESSION['members']);
		//return $resp;
		start_trans();
		$members = split(', ', $_SESSION['members']);
		mysql_query("insert into loan_group set name='".$name."', branch_id=".$branch_id);
		$last_res = mysql_query("select last_insert_id() as id");
		$last = mysql_fetch_array($last_res);
		
		while($mem = next($members)){
			if(! mysql_query("insert into group_member set mem_id='".$mem."', group_id='".$last['id']."', branch_id=".$branch_id)){
				rollback();
				$resp->alert("ERROR: Transactio rolled back! ".mysql_error());	
				return $resp;
			}
			$action2 .= "insert into group_member set mem_id='".$mem."', group_id='".$last['id']."'";
			//$resp->alert(count($members));
		}
		$action = "insert into loan_group set name=".$name."; ". $action2;
		$msg = "Created Loan Group: ".$name."";
		log_action($_SESSION['user_id'], $action, $msg);
		commit();
		$resp->assign("status", "innerHTML", "<font color=red>Group inserted!</font>");
	}
	return $resp;
}

//LIST LOAN GROUPS
function list_groups($stuff,$submit){
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	
	
$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR GROUPS</h3>
                            </div>               
                            <div class="panel-body">
 		<div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Search By Group or Member:</label>
                                            <input type="text" id="stuff" value="All" name="staff" class="form-control">                                            
                                        </div></div></div>';
                                        		
		 $content .= '<div class="panel-footer">                              
                                
                                <input type="button" id="submit" class="btn  btn-primary" value="Search" id="submit" onclick=\'xajax_list_groups(getElementById("stuff").value,getElementById("submit").value)\'>
                            </div></div>
                        </form>
                        <!--/ Form default layout -->
                    </div></div>';
           if($submit){	
	
	if($stuff ==''){
		$cont = "<font color=red>Select The Groups You Want To List!</font>";
		$resp->assign("status", "innerHTML", $cont);
	}else{
		$old_stuff = $stuff;
		$stuff = ($stuff =='All') ? "" : $stuff;
		$sth = mysql_query("select distinct g.name as name, g.id as id from loan_group g join group_member gm on g.id=gm.group_id join member m on gm.mem_id=m.id where m.last_name like '%".$stuff."%' or m.first_name like '%".$stuff."%' or g.name like '%".$stuff."%'");
		
		if(@ mysql_numrows($sth) == 0){
			$cont = "<font color=red>No Groups Registered Yet!</font>";
			$resp->assign("status", "innerHTML", $cont);
		}
		else{
			$content .= '<div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">';
			
			$content = '<div class="panel-heading">
                                <h3 class="text-primary mt0">LIST OF GROUPS</h3>
                            </div>
                            <table class="table table-striped table-bordered table-hover" id="row-detail">';
 		$content .= '<thead><th>#</th><th>GROUP NAME</th><th>MEMBERS</th></thead><tbody>';
 				
			$x=1;
			while($row = mysql_fetch_array($sth)){
				//$color = ($x%2 == 0) ? "white" : "lightgrey";
				$content .= "<tr><td>".$x."</td><td><a href='javascript:;' onclick=\"xajax_edit_group('".$row['id']."', '".$old_stuff."')\">".$row['name']."</a></td><td>";
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
	}
	$content .="</tbody></table></div></div></div>";
                       
        $resp->call("createTableJs");
        }
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//EDIT LOAN GROUP
function edit_group($group_id, $stuff){
	$resp = new xajaxResponse();
	/*if($_SESSION['position'] <>'Manager'){
		$resp->alert("Access Denied! \nOnly the Manager can edit an item");
		return $resp;
	}*/
	$resp->assign("status", "innerHTML", "");
	
	
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">EDIT GROUP</h3>
                            </div>               
                            <div class="panel-body">
                            
                     <!-- <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Branch:</label>
                                            <span>'.branch().'</span>      
                                        </div>                                        
                                    </div>
                                </div>-->
                                
                      ';
                                           
	
	$former_res = mysql_query("select name from loan_group where id='".$group_id."';");
	$former = mysql_fetch_array($former_res);
	
	$content .= '<div class="form-group">
                                   <div class="row">
                                    <div class="col-sm-6">
                                            <label class="control-label">Group Name:</label>
                                            <input type=text name="group_name" id="group_name" value="'.$former['name'].'" class="form-control">
                                        </div>                                       
                                    </div>
                                    </div>';
	$content .= '<div id="from_div">       
                         <div class="form-group">
                                   <div class="row">
                                    <div class="col-sm-6">
                                            <label class="control-label">Members:</label>
                                           <div><select name="from" id="from" size=6>';
	$sth = mysql_query("select * from member where id not in (select mem_id from group_member where group_id='".$group_id."') order by first_name, last_name, mem_no");
	while($row = mysql_fetch_array($sth)){
		$content .= "<option value='".$row['id']."'>".$row['mem_no']." - ".$row['first_name']." ".$row['last_name'];
	}
	
	$content .="</select></div></div></div>
                        </div> ";
                        
                        
                       
	
	//ALREADY MEMBERS
	$sth =  mysql_query("select m.id as mem_id, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name from group_member gm join member m on gm.mem_id=m.id where gm.group_id='".$group_id."'");
	$_SESSION['members'] =0;
	$no = @mysql_numrows($sth);
	
	$content .="<div class='form-group'>
                                   <div class='row'>
                                    <div class='col-sm-6'><input type=button class='btn btn-default' value ='Add' onclick=\"xajax_add_groupmember(getElementById('from').value, ".$no.", 'edit', ".$group_id.");\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=button class='btn btn-danger mb5' value ='Remove' onclick=\"xajax_remove_groupmember(getElementById('to').value, ".$no.", 'edit', ".$group_id.");\"></div></div></div>";
	  $content .='<div class="form-group">
                                   <div class="row">
                                    <div class="col-sm-6">
                                            <label class="control-label">Group Members</label><div><select name="to" id="to" size=6>';
	
	while($row = @ mysql_fetch_array($sth)){
		$_SESSION['members'] .= ", ".$row['mem_id'];
		$content .= "<option value='".$row['mem_id']."'>".$row['mem_no']." - ".$row['first_name'] ." ".$row['last_name'];
	}
	$content .= '</select></div></div>                                       
                                    </div>
                                    </div>'; 
         $content .= "<div class='panel-footer'>                            
		<input type='button' class='btn btn-inverse mb5' value='Back' onclick=\"xajax_list_groups('".$stuff."')\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='button' class='btn btn-danger mb5' value='Delete' onclick=\"xajax_delete_group(".$group_id.");\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='button' class='btn btn-primary mb5' value='Update'  onclick=\"xajax_update_group(".$group_id.", getElementById('group_name').value);\"></div>";
	$content .='</div></div> </form> </div> </div>';
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//UPDATE GROUP
function update_group($group_id, $name){
	$resp = new xajaxResponse();
	$sth = mysql_query("select  l.balance from disbursed l join loan_applic applic on l.applic_id=applic.id where applic.group_id='".$group_id."' and l.balance >0");
	if(@ mysql_numrows($sth) >0){
		$resp->alert("Cant update this group! \nIt is still servicing a loan");
	}else{
		$resp->confirmCommands(1, "Do you really want to update?");
		$resp->call('xajax_update2_group', $group_id, $name);
	}
	return $resp;
}

//DELETE A GROUP
function delete_group($group_id){
	$resp = new xajaxResponse();
	$sth = mysql_query("select  l.balance from disbursed l join loan_applic applic on l.applic_id=applic.id where applic.group_id='".$group_id."' and l.balance >0");
	if(@ mysql_numrows($sth) >0){
		$resp->alert("Cant delete this group! \nIt is still servicing a loan");
	}else{
		$resp->confirmCommands(1, "Do you really want to delete?");
		$resp->call('xajax_delete2_group', $group_id);
	}
	return $resp;
}

//CONFIRM UPDATE OF GROUP IN DB
function update2_group($group_id, $name){
	$resp = new xajaxResponse();
	mysql_query("update loan_group set name='$name' where id='".$group_id."'");
	mysql_query("delete from group_member where group_id='".$group_id."'");
	$action = "delete from group_member where group_id='".$group_id."'";
	$msg = "Updated group: ".$name." to ".count($_SESSION['members'])." members.";
	log_action($_SESSION['user_id'], $action, $msg);
	$members = split(', ', $_SESSION['members']);
	while($mem = next($members)){
		mysql_query("insert into group_member set mem_id='".$mem."', group_id='".$group_id."'");
	}
	$resp->assign("status", "innerHTML", "<font color=red>Group updated!</font>");
	return $resp;
}

//CONFIRM DELETION OF GROUP
function delete2_group($group_id){
	$resp = new xajaxResponse();
	$row = mysql_fetch_array(mysql_query("select * from loan_group where id = ".$group_id.""));
	mysql_query("delete from group_member where group_id='".$group_id."'");
	mysql_query("delete from loan_group where id='".$group_id."'");
	$action = "delete from loan_group where id='".$group_id."'";
	$msg = "Deleted group: ".$row['name']."";
	log_action($_SESSION['user_id'], $action, $msg);
	//mysql_query("insert into logs set user_id='".$_SESSION['user_id']."', time=NOW(),  action='".$_SESSION['name'].":  ".mysql_escape_string($action)."'");
	$resp->assign("status", "innerHTML", "<font color=red>Group deleted!</font>");
	return $resp;
}


function edit_photos($mem_no){
	$resp = new xajaxResponse();
	$sth = mysql_query("select * from member where mem_no='".$mem_no."'");
	$row = mysql_fetch_array($sth);
	
	$content ="";
	$content .="<form enctype='multipart/form-data' action='customers/editPhoto' method=post class='panel form-horizontal form-bordered'>";
$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h4 class="semibold text-primary mt0 mb5">EDIT PHOTO AND SIGNATURE OF MEMBER : '.strtoupper($row
		['first_name']).' '.strtoupper($row['last_name']). ' - '.$mem_no. '</h4>
                                               		 <p class="text-default nm"></p>
                                           	 </div>
                                        </div>';
                                        
	 
	$content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Photo:</label>
                                            <div class="col-sm-6"><img src="photos/'.$row['photo_name'].'?dummy='.time().'" width=100 height=80><input type=file name="photo"></div>                                            
                                            </div>'; 
                                            
        $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Signature:</label>
                                            <div class="col-sm-6"><img src="signs/'.$row['sign_name'].'?dummy='.time().'" width=100 height=80><input type=file name="sign"><input type=hidden name="mem_no" value="'.$mem_no.'"></div>                                            
                                            </div>';
                                            
                                             $content .= '<div class="panel-footer"><input class="btn btn-primary" type=submit name="photo_update" value="Update">';
                                            $content .= '</div></div>';    
	
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function b()
{
	$resp = new xajaxResponse();
	//$resp->alert("they called us");
	$branch = @mysql_fetch_array(@mysql_query("select branch_no from branch limit 1"));
	$resp->assign("status", "innerHTML", "");
	
   $content ="";
	$content .="<form method='post' class='panel form-horizontal form-bordered'>";
$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h4 class="semibold text-primary mt0 mb5">REGISTER CUSTOMER</h4>
                                               		 <p class="text-default nm"></p>
                                           	 </div>
                                        </div><div id="responsediv" class="alert alert-dismissable alert-danger"></div>';
                                        
	 
	$content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Branch:</label>
                                            <div class="col-sm-6"><span>'.branch().'</span></div>                                            
                                            </div>';                                           
                              
          	
	$content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">First Name:</label>
                                            <div class="col-sm-6">
                                           <input type="text" name="first_name" id="first_name" class="form-control">
                                            </div></div>';                                            
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Last Name:</label>
                                            <div class="col-sm-6">
                                           <input type="text" name="last_name" id="last_name" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Phone No:</label>
                                            <div class="col-sm-6">
                                           <input type="text" name="phone" id="phone"  maxlength=13 class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Email:</label>
                                            <div class="col-sm-6">
                                           <input type="text" id="email" name="email" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Physical Address:</label>
                                            <div class="col-sm-6">
                                           <input type="text" name="paddress" id="paddress" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Next of Kin Name:</label>
                                            <div class="col-sm-6">
                                           <input type="text" name="kin_name" id="kin_name" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Next of Kin Phone No:</label>
                                            <div class="col-sm-6">
                                           <input type="text" maxlength=13 name="kin_phone" id="kin_phone" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Sex:</label>
                                            <div class="col-sm-6">
                                           <select type=text name="sex" id="sex" class="form-control"><option value=""><option value="M">M<option value="F">F<option value="G">G</select>
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Date of Birth:</label>
                                            <div class="col-sm-6">
                                           <input type="" name="" id="" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Photo File:</label>
                                            <div class="col-sm-6">
                                            <div class="input-group">
                                                <input type="text" name="photo" id="photo" class="form-control" readonly>
                                                <span class="input-group-btn">
                                                    <div class="btn btn-primary btn-file">
                                                        <span class="icon iconmoon-file-3"></span> Browse <input type="file">
                                                    </div>
                                                </span>
                                            </div>
                                        </div></div>';                                            
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Signature File:</label>
                                            <div class="col-sm-6">
                                            <div class="input-group">
                                                <input type="text" name="sign" id="sign" class="form-control" readonly>
                                                <span class="input-group-btn">
                                                    <div class="btn btn-primary btn-file">
                                                        <span class="icon iconmoon-file-3"></span> Browse <input type="file">
                                                    </div>
                                                </span>
                                            </div>
                                       
                                           <input type=hidden name="branch_no" id="branch_no" value=$branch[branch_no]>
                                            </div></div>';
                                            
                                            $content .= '<div class="panel-footer"><button type="reset" class="btn btn-default" onclick=\"xajax_addCustForm(); return false;\">Reset</button>
                                            <input type="submit" value="Save" name="cust_enter" id="cust_enter" class="btn btn-primary">';
                                            $content .= '</div>';
          
          
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function addCustForm()
{
	$resp = new xajaxResponse();
	//$resp->alert("they called us");
	$branch = @mysql_fetch_array(@mysql_query("select branch_no from branch limit 1"));
	$resp->assign("status", "innerHTML", "");
	
   $content ="";
	$content .="<form method='post' action='customers/regCustomer' enctype='multipart/form-data' class='panel form-horizontal form-bordered'>";
$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h4 class="semibold text-primary mt0 mb5">REGISTER CUSTOMER</h4>
                                               		 <p class="text-default nm"></p>
                                           	 </div>
                                        </div><div id="responsediv" class="alert alert-dismissable alert-danger"></div>';
                                        
	 
	$content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Branch:</label>
                                            <div class="col-sm-6"><span>'.branch().'</span></div>                                            
                                            </div>';                                           
                              
          	
	$content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">First Name:</label>
                                            <div class="col-sm-6">
                                           <input type="text" name="first_name" id="first_name" class="form-control" required>
                                            </div></div>';                                            
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Last Name:</label>
                                            <div class="col-sm-6">
                                           <input type="text" name="last_name" id="last_name" class="form-control" required>
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Phone No:</label>
                                            <div class="col-sm-6">
                                           <input type="text" name="phone" id="phone"  maxlength=13 class="form-control" required>
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Email:</label>
                                            <div class="col-sm-6">
                                           <input type="email" id="email" name="email" class="form-control" required>
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Physical Address:</label>
                                            <div class="col-sm-6">
                                           <input type="text" name="paddress" id="paddress" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Next of Kin Name:</label>
                                            <div class="col-sm-6">
                                           <input type="text" name="kin_name" id="kin_name" class="form-control" required>
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Next of Kin Phone No:</label>
                                            <div class="col-sm-6">
                                           <input type="text" maxlength=13 name="kin_phone" id="kin_phone" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Sex:</label>
                                            <div class="col-sm-6">
                                           <select type=text name="sex" id="sex" class="form-control" required><option value=""><option value="M">M<option value="F">F<option value="G">G</select>
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Date of Birth:</label>
                                            <div class="col-sm-6">
                                           <input type="text" name="dob" id="dob" class="form-control" required>
                                            </div></div>';
                                            $resp->call("createDOB",'dob');
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Photo File:</label>
                                            <div class="col-sm-6">
                                            <div class="input-group">
                                                <input type="text" name="photo_" id="photo_" class="form-control"  readonly>
                                                <span class="input-group-btn">
                                                    <div class="btn btn-primary btn-file">
                                                        <span class="icon iconmoon-file-3"></span> Browse <input type="file" name="photo" id="photo">
                                                    </div>
                                                </span>
                                            </div>
                                        </div></div>';                                            
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Signature File:</label>
                                            <div class="col-sm-6">
                                            <div class="input-group">
                                                <input type="text" name="sign_" id="sign_" class="form-control" readonly>
                                                <span class="input-group-btn">
                                                    <div class="btn btn-primary btn-file">
                                                        <span class="icon iconmoon-file-3"></span> Browse <input type="file" name="sign" id="sign">
                                                    </div>
                                                </span>
                                            </div>
                                       
                                           <input type=hidden name="branch_no" id="branch_no" value='.$branch['branch_no'].'>
                                            </div></div>';
                                            
                                            $content .= '<div class="panel-footer"><button type="reset" class="btn btn-default" onclick=\"xajax_addCustForm(); return false;\">Reset</button>
                                            <input type="submit" value="Save" name="cust_enter" id="cust_enter" class="btn btn-primary">';
                                            $content .= '</div>';
          
          
	$resp->assign("display_div", "innerHTML", $content);
	
	return $resp;
}


function addCustomer($branch_no, $first_name, $last_name, $phone, $email, $paddress, $kin_name, $kin_phone, $dob_year, $dob_month, $dob_mday, $sex,$branch_id)
{
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	//$resp->alert("they called us");
	//return $resp;
	if($first_name == '' || $last_name=='' || $phone=='' || $paddress=='' || $kin_name=='' || $kin_phone =='' || $sex=='')
		$resp->alert("You may not leave any field marked with * blank");
	elseif(!$calc->isValidDate($dob_mday, $dob_month, $dob_year))
		$resp->alert("Customer rejected! Please enter valid date");
	elseif($calc->isFutureDate($dob_mday, $dob_month, $dob_year))
		$resp->alert("Customer rejected! You have entered a future date");
	else{
		$dob = sprintf("%d-%02d-%02d", $dob_year, $dob_month, $dob_mday);
		$new_cust = @mysql_query("INSERT INTO member (branch_no, first_name, last_name, telno, email, address, kin, kin_telno, dob, sex,branch_id) VALUES ($branch_no, '".$first_name."', '".$last_name."', '".$phone."', '".$email."', '".$paddress."', '".$kin_name."', '".$kin_phone."', '".$dob."', '".$sex."',".$branch_id.")");
		
		$action .= "INSERT INTO member (branch_no, first_name, last_name, telno, email, address, kin, kin_telno, dob, sex) VALUES ($branch_no, '".$first_name."', '".$last_name."', '".$phone."', '".$email."', '".$paddress."', '".$kin_name."', '".$kin_phone."', '".$dob."', '".$sex."')";
		$lastid = mysql_insert_id();
		$last_res = mysql_query("select max(mem_no) as mem_no from member");
		$last = mysql_fetch_array($last_res);
		$branch = mysql_fetch_array(mysql_query("select * from branch where branch_no='".$branch_id."'"));
		if ($last['mem_no'] != NULL)
		{
			$pre = strlen($branch['prefix']);
			$all = strlen($last['mem_no']);
			$num = $all - $pre;
			$resp->alert($last['mem_no']);
			preg_match("/(\d+){".$num."}/", $last['mem_no'], $arg);
			//$last_no = preg_replace("/".$branch['prefix']."/", "", $last['mem_no']);
			$last_no = $arg[2];
			$mem_no = intval($last_no) + 1;
		//	$resp->alert($mem_no);
			//return $resp;
		}
		else
			$mem_no = 1;
		
			$mem_no = $branch['prefix']. str_pad($mem_no, 7, "0", STR_PAD_LEFT);
		$msg = "ADDED Member: Name: ".$first_name." ".$last_name." Mem_no: ".$mem_no."";
		$upd = @mysql_query("update member set mem_no = '".$mem_no."' where id = $lastid");
		
		$action .= " + "."update member set mem_no = '".$mem_no."' where id = $lastid";
		log_action($_SESSION['user_id'], $action, $msg);
		$content .= "<br><font color=RED>Customer successfully added.</font><br>";
	}	
	$resp->assign("status", "innerHTML", $content);
	return $resp;
}

function membersList($type, $mem_no, $mem_name, $start,$branch_id)
{
	$content ="";
	$resp = new xajaxResponse();
	$mem_no1 = ($mem_no == 'All') ? "" : $mem_no;
	$mem_name1 = ($mem_name == 'All') ? "" : $mem_name;
	$branch = ($branch_id=='all') ? NULL : "and branch_id=".$branch_id;
	$resp->assign("status", "innerHTML", "");
	if (strtolower($type) == 'all'){
		$mem = @mysql_query("select id, mem_no, first_name, last_name, sex, sign_name, photo_name, (DATEDIFF(NOW(), dob)/365) as age, kin, kin_telno, address, status,  email, telno from member where (first_name like '%".$mem_name1."%' or last_name  like '%".$mem_name1."%') and mem_no like '%".$mem_no1."%' ".$branch." order by first_name, last_name asc");
		$memb = mysql_query("select id, mem_no, first_name, last_name, sex, sign_name, photo_name, (DATEDIFF(NOW(), dob)/365) as age, kin, kin_telno, address, status,  email, telno from member where (first_name like '%".$mem_name1."%' or last_name  like '%".$mem_name1."%') and mem_no like '%".$mem_no1."%' ".$branch." order by first_name, last_name asc");
		$head = "CUSTOMERS";
	}elseif (strtolower($type) == 'members'){
		$mem = @mysql_query("select id, mem_no, first_name, last_name, sex, sign_name, photo_name, (DATEDIFF(NOW(), dob)/365) as age, kin, kin_telno, address, status,  email, telno  from member where id in (select mem_id from shares) and (first_name like '%".$mem_name1."%' or last_name  like '%".$mem_name1."%') and mem_no like '%".$mem_no1."%' ".$branch." order by first_name, last_name asc");
		$memb = @mysql_query("select id, mem_no, first_name, last_name, sex, sign_name, photo_name, (DATEDIFF(NOW(), dob)/365) as age, kin, kin_telno, address, status,  email, telno  from member where id in (select mem_id from shares) and (first_name like '%".$mem_name1."%' or last_name  like '%".$mem_name1."%') and mem_no like '%".$mem_no1."%' ".$branch." order by first_name, last_name asc ") or trigger_error(mysql_error());
		$head = "MEMBERS";
	}elseif (strtolower($type) == 'non_members'){
		$mem = @mysql_query("select id, mem_no, first_name, last_name, sex, sign_name, photo_name, (DATEDIFF(NOW(), dob)/365) as age, kin, kin_telno, address, status,  email, telno from member where id NOT in (select mem_id from shares) and (first_name like '%".$mem_name1."%' or last_name  like '%".$mem_name1."%') and mem_no like '%".$mem_no1."%' ".$branch." order by first_name, last_name asc") or trigger_error(mysql_error());
		$memb = @mysql_query("select id, mem_no, first_name, last_name, sex, sign_name, photo_name, (DATEDIFF(NOW(), dob)/365) as age, kin, kin_telno, address, status,  email, telno from member where id NOT in (select mem_id from shares) and (first_name like '%".$mem_name1."%' or last_name  like '%".$mem_name1."%') and mem_no like '%".$mem_no1."%' ".$branch." order by first_name, last_name asc ") or trigger_error(mysql_error());
		$head = "NON-MEMBERS";
	}
			    	    
        $content .= '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" >
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR&nbsp;'.$head.'</h3>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Customer Name:</label>
                                            <input type="text"  value="'.$mem_name.'" id="mem_name" class="form-control">                                            
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Member No:</label>
                                            <input type=text value="'.$mem_no.'" id="mem_no" class="form-control">
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
                                                      	
	$content .= '<div class="panel-footer">                              
                                
                                <input type="button" class="btn  btn-primary" value="Show" id="submit"  onclick=\'xajax_membersList("'.$type.'", getElementById("mem_no").value, getElementById("mem_name").value, "1",getElementById("branch_id").value)\'>
                            </div></div>
                        </form>
                        <!--/ Form default layout -->
                    </div></div>';
	//$max_page = ceil(mysql_num_rows($mem)/$num_rows);
	if($start==1){
	if (mysql_numrows($memb) >0)
	{				    	    
		$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <p><h5 class="text-primary mt0">LIST OF&nbsp;'.$head.'</h5></p>
                                
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
 		$content .= '<thead><th>#</th><th>NAME</th><th>MEMBER NO.</th><th>AGE</th><th>SEX</th><th>EMAIL</th><th>PHONE NO.</th><th>PHYSICAL ADDRESS</th><th>NEXT OF KIN</th><th>NEXT OF KIN PHONE NO.</th><th>PHOTO</th><th>SIGNATURE</th><th>EDIT</th></thead><tbody>';
					
		$i=$stat+1;
		
		while ($members = @mysql_fetch_array($memb))
		{
			//$color = ($i % 2 == 0) ? "lightgrey" : "white";
			$content .= "
				    <tr>
				      <td>".$i."</td>
						 
				       <td>$members[first_name] $members[last_name]</td>
						<td>$members[mem_no]</td>
						<td>".floor($members['age'])."</td>
						<td>$members[sex]</td>
				       <td>$members[email]</td>
				       <td>$members[telno]</td>
				       <td>$members[address]</td>
						<td>$members[kin]</td>
				       <td>$members[kin_telno]</td>
						<td><img src='photos/".$members['photo_name']."?dummy=".time()."' width=60 height=50></td>
						  <td><img src='signs/".$members['sign_name']."?dummy=".time()."' width=60 height=50></td>
				       <td>";
				       $content .= '
				       		 <!-- button toolbar -->
                                                <div class="toolbar">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-sm btn-default">Action</button>
                                                        <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
                                                            <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-right">';
                                        $content .="
                                                            <li><a href='javascript:;' onclick=\"xajax_editCustForm('".$members['id']."', '".$type."', '".$mem_no."', '".$mem_name."', '".$start."','".$branch_id."'); return false;\"><i class='icon ico-pencil'></i>Edit Customer<a/>
                                                            </li>
                                                            <li>
                                                            <a href='javascript:;' onclick=\"xajax_edit_photos('".$members['mem_no']."')\"><i class='icon ico-pencil'></i>Edit Photos</a></li>
                                                            
                                                        </ul>
                                                    </div>
                                                </div>
                                                <!--/ button toolbar -->
				       </td>
				    </tr>
				    ";
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
		
	}elseif(strtolower($type) == 'members'){
		
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

function editCustForm($id, $type, $mem_no, $mem_name, $start,$branch_id,$num_rows,$stat,$cur_page)
{
	$resp = new xajaxResponse();
	/*if($_SESSION['position'] <>'Manager'){
		$resp->alert("Access Denied! \nOnly the Manager can edit an item");
		return $resp;
	}*/
	$mem = @mysql_fetch_array(@mysql_query("select mem_no, first_name, last_name, telno, email, address, sex, kin, kin_telno, date_format(dob, '%Y') as year, date_format(dob, '%m') as month, date_format(dob, '%d') as mday from member where id='".$id."'"));
	$resp->assign("status", "innerHTML", "");
	$content .= "<br><center><font color=#00008b size=3pt><b>EDIT CUSTOMER DETAILS</b></font></center><br>";
	$content .= "<form action=".$PHP_SELF." method=post><table height=100 border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='80%' id='AutoNumber2' align=center>
			<tr bgcolor=white><td valign=top><font color=red>* </font>Member No</td><td><input type=text id='mem_no' name='mem_no' value='".$mem['mem_no']."'></td></tr>
		    <tr bgcolor=lightgrey><td valign=top><font color=red>* </font>First Name: </td><td><input type=text id='first_name' name='first_name' value='".$mem['first_name']."'></td></tr>
		    <tr bgcolor=white><td valign=top><font color=red>* </font>Last Name: </td><td><input type=text id='last_name' name='last_name' value='".$mem['last_name']."'></td></tr>
		    <tr bgcolor=lightgrey><td valign=top><font color=red>* </font>Phone No: </td><td><input type=text maxlength=13 id='phone' name='phone' value='".$mem['telno']."'></td></tr>
		    <tr bgcolor=white><td valign=top>Email: </td><td><input type=text id='email' name='email' value='".$mem['email']."'></td></tr>
		    <tr bgcolor=lightgrey><td valign=top><font color=red>* </font>Physical Address:</td><td> <input type=text name='paddress' id='paddress' value='".$mem['address']."'></td></tr>
		    <tr bgcolor=white><td valign=top><font color=red>* </font>Next of Kin Name: </td><td><input type=text id='kin_name' name='kin_name' value='".$mem['kin']."'></td></tr>
		    <tr bgcolor=lightgrey><td valign=top><font color=red>* </font>Next of Kin Phone No: </td><td><input type=text maxlength=13 id='kin_phone' name='kin_phone' value='".$mem['kin_telno']."'></td></tr>
			<tr bgcolor=white><td valign=top><font color=red>* </font>Sex </td><td><select type=text name='sex' id='sex'><option value='".$mem['sex']."'>".$mem['sex']."<option value='M'>M<option value='F'>F<option value='G'>G</select></td></tr>
			<tr bgcolor=lightgrey><td><font color=red>* </font>Date of Birth</td><td>".monthdob_array('', $mem['year'], $mem['month']) . mday('', $mem['mday'])."</td></tr>
		    <input type=hidden name='branch_no' id='branch_no' value=$branch[branch_no]>
		    <tr bgcolor=lightgrey><td></td><td valign=top><a href=# onclick=\"xajax_membersList('".$type."', '".$mem_no."', '".$mem_name."', '".$start."','".$branch_id."','".$num_rows."','".$stat."','".$cur_page."')\"><img src='images/btn_back.jpg'></a>&nbsp;&nbsp;<a href=# onclick=\"xajax_deleteCustomer('".$id."');\"><img src='images/btn_delete.jpg'></a>&nbsp;&nbsp;<a href=#  onclick=\"xajax_updateCustomer('".$id."', document.getElementById('mem_no').value, document.getElementById('first_name').value, document.getElementById('last_name').value, document.getElementById('phone').value, document.getElementById('email').value, document.getElementById('paddress').value, document.getElementById('kin_name').value, document.getElementById('kin_phone').value, getElementById('year').value, getElementById('month').value, getElementById('mday').value, getElementById('sex').value); return false;\"><img src='images/btn_save.jpg'></a></td></tr>
		    </table>
		    </form>
		    ";
	
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function updateCustomer($id, $mem_no, $first_name, $last_name, $phone, $email, $paddress, $kin_name, $kin_phone, $dob_year, $dob_month, $dob_mday, $sex)
{
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	if($first_name == '' || $last_name=='' || $phone=='' || $paddress=='' || $kin_name=='' || $kin_phone =='' || $sex=='' || $mem_no=='')
		$resp->alert("You may not leave any field marked with * blank");
	elseif(!$calc->isValidDate($dob_mday, $dob_month, $dob_year))
		$resp->alert("Customer rejected! Please enter valid date");
	elseif($calc->isFutureDate($dob_mday, $dob_month, $dob_year))
		$resp->alert("Customer rejected! You have entered a future date");
	else{
		$mem_res = mysql_query("select * from member where mem_no='".$mem_no."' and id <>'".$id."'");
		if(mysql_numrows($mem_res) > 0){
			$resp->alert("Update rejected! You have entered a Member No already used by another member");
		}else{
			$dob = sprintf("%d-%02d-%02d", $dob_year, $dob_month, $dob_mday);
			$resp->confirmCommands(1, "Do you really want to update?");
			$resp->call("xajax_update2Customer", $id, $mem_no, $first_name, $last_name, $phone, $email, $paddress, $kin_name, $kin_phone, $dob, $sex);
		}
	}	
	$resp->assign("status", "innerHTML", $content);
	return $resp;
}

function update2Customer($id, $mem_no, $first_name, $last_name, $phone, $email, $paddress, $kin_name, $kin_phone, $dob, $sex){
	$resp = new xajaxResponse();
	if(mysql_query("update member set mem_no='".$mem_no."', first_name='".$first_name."', last_name='".$last_name."', telno='".$phone."', email='".$email."', kin='".$kin_name."', kin_telno='".$kin_phone."', dob='".$dob."', address='".$paddress."', sex='".$sex."' where id='".$id."'")){
		$resp->assign("status", "innerHTML", "<br><font color=red><b>Customer details updated!</b></font><br>");
		
		$action = "update member set first_name='".$first_name."', last_name='".$last_name."', telno='".$phone."', email='".$email."', kin='".$kin_name."', kin_telno='".$kin_phone."', dob='".$dob."', address='".$paddress."', sex='".$sex."' where id='".$id."'";
		$msg = "Update member details: set Mem_no: ".$mem_no.", First Name: ".$first_name.", Last Name: ".$last_name."";
		log_action($_SESSION['user_id'], $action, $msg);
		//mysql_query("insert into logs set user_id='".$_SESSION['user_id']."', time=NOW(),  action='".$_SESSION['name'].":  ".mysql_escape_string($action)."'");
	}else
		$resp->alert("Customer details not updated! \n update query failed");
	return $resp;
}

function deleteCustomer($id){
	$resp = new xajaxResponse();
	$shares_res = mysql_query("select * from shares where mem_id='".$id."'");
	$mem_res = mysql_query("select * from mem_accounts where mem_id='".$id."'");
	if(mysql_numrows($shares_res) > 0) 
		$resp->alert("Cant delete this customer, \n they already have shares");
	elseif(mysql_numrows($mem_res) > 0)
		$resp->alert("Cant delete this customer, \n they already have savings accounts");
	else{
		$resp->confirmCommands(1, "Do you really want to delete?");
		$resp->call('xajax_delete2Customer', $id);
	}
	return $resp;
}

function delete2Customer($id){
	$resp = new xajaxResponse();
	$former= mysql_fetch_array(mysql_query("select mem_no, first_name, last_name, sign_name, photo_name from member where id='$id'"));
	if(mysql_query("delete from member where id='".$id."'")){
		@unlink("photos/".$former['photo_name']);
		@unlink("signs/".$former['sign_name']);
		$action = "delete from member where id='".$id."'";
		$msg = "Deleted Member: Mem_no: ".$former['mem_no'].", Name: ".$former['first_name']." ".$former['last_name']."";
		log_action($_SESSION['user_id'], $action, $msg);
		//mysql_query("insert into logs set user_id='".$_SESSION['user_id']."', time=NOW(),  action='".$_SESSION['name'].":  ".mysql_escape_string($action)."'");
		$resp->assign("status", "innerHTML", "<font color=red>Customer deleted!</font>");
	}else
		$resp->alert("Customer not deleted!: \n Cant delete from table");
	return $resp;
}


//Mobile Banking Subscription
function mobileBankingSubscription(){
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	$mems ='';
	//$content ="";
	$mem_res = @mysql_query("select id, first_name, last_name, mem_no from member order by first_name, last_name desc");
	
	while ($mem_row = mysql_fetch_array($mem_res))
	{
		$mems .= "<option value=$mem_row[id]>$mem_row[first_name] $mem_row[last_name] - $mem_row[mem_no]</option>";
	}
	
	/*$mem_row = mysql_fetch_array(mysql_query("select first_name, last_name, mem_no, sign_name, photo_name from member where id = '$mem_id'"));*/
	$branch = mysql_fetch_array(mysql_query("select * from branch"));
				
		$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <h3 class="panel-title">MOBILE MONEY SUBSCRIPTION</h3>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Select Member:</label>
                                                                
                                        <div class="input-group">
                                            <select name="memid" id="memid" class="form-control"><option value="">--select member--</option>'.$mems.'</select>
                                          
                                        </div>
                                    </div>
                                                                     
                                        <div class="col-sm-6">
                                            <label class="control-label">OR Enter Member No:</label>
                                                                                                      
                                        <div class="input-group">
                                             <input type=text id="mem_no" class="form-control">
                                            
                                        </div>
                                    </div>                
                                     </div>   
                                    </div>';
                                    
                                  $content .='<div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Pin:</label>
                                                  
                
                                        <div class="input-group">
                                            <input type="text" id="pin" class="form-control">
                                        </div>
                                    </div>
                                                                               
                                        <div class="col-sm-6">
                                            <label class="control-label">Mobile Money No:</label>
                                                                                                      
                                        <div class="input-group">
                                             <input type="text" id="mm_no" class="form-control">
                                            
                                        </div>
                                    </div>                
                                     </div>   
                                    </div>';                
                                            	
		$content.='<div class="form-group">
        
                                      <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Subscribe:</label>
                                           	                                    
                                          <span class="checkbox custom-checkbox">
                                                <input type="checkbox" name="customcheckbox2" id="customcheckbox2" />
                                                <label for="customcheckbox2"></label>
                                            </span>     
                                        </div>
                                        
                                    </div>
                                </div>';
                                                                           
	$content.= "<div class='panel-footer'>                              
                                
                                <input type='button' class='btn  btn-primary' value='Save'  onclick=\"xajax_subscription(getElementById('memid').value, getElementById('mem_no').value, getElementById('pin').value, getElementById('mm_no').value,getElementById('customcheckbox2').checked); return false;\">
                            </div>";
	
	       $content .= '</div></form>
                        <!--/Form default layout -->
                    </div></div>';	
	
	$resp->assign("display_div", "innerHTML", $content);
	
	return $resp;	
	
}

function subscription($memid, $mem_no, $pin, $mm_no, $subscribe){

	$resp = new xajaxResponse();
	
	if($memid == '' || $pin=='' || $mm_no=='' || $subscribe==''){
	
		$resp->alert("You May Not Leave Any Field Blank");
		return $resp;
	}
	
	if(!empty($mem_no)){	
	
		$mem_res = mysql_query("select id from member where mem_no='".$mem_no."'");
		if(mysql_numrows($mem_res) == 0){
			$resp->alert("The Entered Member No. Does not Exist!");
			return $resp;
		}
		else
		$mem = mysql_fetch_array($mem_res);	
		$memid = $mem['id'];
	}
	
		$qry="update member set pin='".$pin."',mm_no='".$mm_no."',subscribe='".$subscribe."' where id='".$memid."'";
		if(mysql_query($qry))
		$resp->assign("status", "innerHTML", "<font color=red>Member Subscription Successful</font>");
		else
		$resp->alert("status","innerHTML", "<font color=red>Subscription Not Effected</font>");
		
	return $resp;
}	

function list_mbSubscribers($mem_name,$mem_no,$branch_id,$submit)
{
	$content ="";
	$resp = new xajaxResponse();
	$mem_no1 = ($mem_no == 'All') ? "" : $mem_no;
	$mem_name1 = ($mem_name == 'All') ? "" : $mem_name;
	$branch = ($branch_id=='all') ? NULL : "and branch_id=".$branch_id;
	$resp->assign("status", "innerHTML", "");
		   
        $content .= '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" >
                            <div class="panel-heading">
                                <h3 class="panel-title">SEARCH FOR SUBSCRIBED CUSTOMERS</h3>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Customer Name:</label>
                                            <input type="text" value="All" id="mem_name" class="form-control">                                            
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="control-label">Member No:</label>
                                            <input type=text  value="All" id="mem_no" class="form-control">
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
                                                      	
	$content .= '<div class="panel-footer">                              
                                
                                <input type="button" id="submit" class="btn  btn-primary" value="Show"  onclick=\'xajax_list_mbSubscribers(getElementById("mem_name").value, getElementById("mem_no").value,getElementById("branch_id").value,getElementById("submit").value)\'>
                            </div></div>
                        </form>
                        <!--/ Form default layout -->
                    </div></div>';
                   
         if($submit){
         
        if($mem_no == '' && $mem_name==''){
		
		$cont = "<font color=red>Enter Customer Name or Number.</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
	}
	
	else{
	
		$memb = mysql_query("select id, mem_no, first_name, last_name, sex, sign_name, photo_name, (DATEDIFF(NOW(), dob)/365) as age,address, status, email, telno,pin,mm_no,subscribe from member where (first_name like '%".$mem_name1."%' or last_name  like '%".$mem_name1."%') and mem_no like '%".$mem_no1."%' ".$branch." and subscribe=1 order by first_name, last_name asc");
	

	if (mysql_numrows($memb) >0)
	{				    	    
		$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <p><h5 class="text-primary mt0">LIST OF SUBSCRIBED CUSTOMERS</h5></p>
                                
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
 		$content .= '<thead><th>#</th><th>NAME</th><th>MEMBER NO.</th><th>AGE</th><th>SEX</th><th>EMAIL</th><th>PHONE NO.</th><th>PHYSICAL ADDRESS</th><th>PHOTO</th><th>SIGNATURE</th><th>PIN</th><th>MOBILE MONEY NO.</th><th>SUBSCRIBED</th></thead><tbody>';
					
		$i=1;
		
		while ($members = @mysql_fetch_array($memb))
		{
			if($members[subscribe]==1);
			 $subscribed='Yes';
			$content .= "
				    <tr>
				      <td>".$i."</td>
						 
				       <td>$members[first_name] $members[last_name]</td>
						<td>$members[mem_no]</td>
						<td>".floor($members['age'])."</td>
						<td>$members[sex]</td>
				       <td>$members[email]</td>
				       <td>$members[telno]</td>
				       <td>$members[address]</td>
						
						<td><img src='photos/".$members['photo_name']."?dummy=".time()."' width=60 height=50></td>
						  <td><img src='signs/".$members['sign_name']."?dummy=".time()."' width=60 height=50></td>
						  <td>$members[pin]</td>
				       <td>$members[mm_no]</td>
				      
				       <td>$subscribed</td>
				       </td>
				    </tr>
				    ";
			$i++;
		}
		
	}
		
	else{
		
		$cont = "<font color=red>No Subscribers Found.</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
	}
	$content .= "<tbody></table></div>";	
	$resp->call("createTableJs");
	}
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}
 
//UPDATE PHOTOS AND SIGNATURES
if(isset( $_POST['photo_update']) && $_POST['photo_update'] ==  'Update'){
	
}

?>
