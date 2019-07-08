<?php

$xajax->registerFunction("listLiabilities");
$xajax->registerFunction("listLiabilitiesForm");


function listLiabilities($account, $contact, $from_date,$to_date, $branch_id)
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
                                <p><h4 class="semibold text-primary mt0 mb5">SEARCH FOR TRANSACTIONS</h4></p>                 
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
                                <input type='button' class='btn  btn-primary' value='Search'  onclick=\"xajax_listLiabilities(getElementById('account').value, getElementById('contact').value, getElementById('from_date').value,document.getElementById('to_date').value,getElementById('branch_id').value)\">
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                     $resp->call("createDate","from_date");
	             $resp->call("createDate","to_date");
                    //$resp->assign("display_div", "innerHTML", $content);
		
	if($from_date =='' || $to_date ==''){
		$cont ="<font color=red>Select the period!</font>";
		$resp->assign("status", "innerHTML", $cont);
		
	}
	else{
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59",  $to_year, $to_month, $to_mday);
	
	$from_date1 = $from_mday.'/'.$from_month.'/'.$from_year;
	$to_date1 = $to_mday.'/'.$to_month.'/'.$to_year;
	
	if ($account == '')
	{
		$res = @mysql_query("select r.bank_account, ac.id as acc_id, ac.name as acc_name, ac.account_no as account_no, r.id as rid, r.amount as amount, r.date as date,r.mode from accounts ac right join payable r on r.account_id = ac.id where r.date>= '".$from_date."' and r.date <= '".$to_date."' ".$branch." order by r.date desc");
		$account='Liabilities';
	}else{
		$res = @mysql_query("select r.bank_account, ac.id as acc_id, ac.name as acc_name, ac.account_no as account_no, r.id as rid, r.amount as amount, r.date as date, r.mode,r.transaction as transaction from accounts ac right join payable r on r.account_id = ac.id where r.date>= '".$from_date."' and r.date <= '".$to_date."' and r.account_id='".$account."' ".$branch." order by r.date desc");
	  $acct= mysql_fetch_array($res);
	  $account=$acct['account_no'].'-'.$acct['acc_name'];
	  
	 // $res = @mysql_query("select  sum(amount) as amount from accounts payable where date >= '".$from_date."' and date <= '".$to_date."' and account_id='".$account."' ".$branch." ");
	  
	}
	
	if (@mysql_num_rows($res) > 0)
	{
		/*$content .= "
			   <a href='list_incomes.php?account=".$account."&branch_id=".$branch_id."&contact=".$contact."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."' target=blank()><b>Printable Version</b></a> | <a href='list_incomes.php?account=".$account."&branch_id=".$branch_id."&contact=".$contact."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&format=excel' target=blank()><b>Export Excel</b></a> | <a href='list_incomes.php?account=".$account."&branch_id=".$branch_id."&contact=".$contact."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&format=word' target=blank()><b>Export Word</b></a>";*/
			   $content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                 <h4 class="text-primary mt0 mb5">Transactions for '.$account.' for the period '.$from_date1.' to '.$to_date1.'</h4>
                            </div>';
 		$content .= '<table class="table-hover borderless" id="table-tools" width="100%">
			        <thead><th>Date</th><th>Account</th><th>Particulars</th><th>Paid By</th><th>Dr.</th><th>Cr.</th><th>Balance</th><thead><tbody>';
		$balance=0;	    
      
            while($row= mysql_fetch_array($res)){
            
            if($row['mode'] <> 'cash'){
			$memName = mysql_query("select m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, ma.id as memacc_id, a.name as name from member m join mem_accounts ma on m.id = ma.mem_id join savings_product p on ma.saveproduct_id = p.id join accounts a on p.account_id = a.id where ma.id =".$row['mode']." ");
			$rows = mysql_fetch_array($memName);
			$savings= $rows['first_name']."". $rows['last_name']."-".$rows['mem_no']."<br>".$rows['name'];
			}
						
			$cash = mysql_fetch_array(mysql_query("select a.name as name, a.account_no from bank_account b join accounts a on b.account_id=a.id where b.id='".$row['bank_account']."'"));
			
			$mode = ($row['mode'] == 'cash') ? "Cash to - ".$cash['account_no']."-".$cash['name'] : $savings;
			
            $balance+=$row['amount'];
            $content .= "<tr><td>".$row['date']."</td><td>".$row['account_no']."-".$row['acc_name']."</td><td>".$row['transaction']."</td><td>".$mode."</td><td>--</td><td>".$row['amount']."</td><td>".number_format($balance,2)."</td></tr>";
                  }     
        
			$content .= "</tbody></table>";
		
	    }
	else {
		$cont = '<font color=red>No Transactions Found in that period.</font>';
	 $resp->assign("status", "innerHTML", $cont);
	
	}
	}
	$resp->call("createTableJs");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


function listLiabilitiesForm()
{        
        //list($from_year,$from_month,$from_mday) = explode('-', $from_date);
       // list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	//$branch = ($branch_id=='all'||$branch_id=='')?NULL:"and branch_id=".$branch_id;
	$content = '
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default"  action="reports/excel_export" method="post">
                            <div class="panel-heading">
                                <p><h4 class="semibold text-primary mt0 mb5">SEARCH FOR TRANSACTIONS</h4></p>                 
                            <div class="panel-body">       
                      <div class="form-group">
                                   
                                        <div class="col-sm-3">
                                            <label class="control-label">Account:</label>
                                            <select id="account" name="account" class="form-control"><option value="">All';
	$sth = mysql_query("select distinct id as id, account_no, name from accounts where id in (select account_id from payable)");
	while($row = mysql_fetch_array($sth)){
		$content .= "<option value='".$row['id']."'>".$row['account_no'] ." - ".$row['name'];
	}
	$content .='</select>                                         
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
                                <input type='submit' class='btn  btn-primary' value='Search'>
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                     $resp->call("createDate","from_date");
	             $resp->call("createDate","to_date");
                 
	$resp->call("createTableJs");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

?>
