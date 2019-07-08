<?php


if(!isset($_GET['format']))
echo("<head>
	<title></title>

		<style>
.borderless td {
    border: none;
}
.borderless th {
    border-left: none;
    border-right: none;
     border-top: solid 1px;
      border-bottom: solid 1px;
}
.green{
    background-color: #7D8488;
    margin-bottom: 10px;
    padding-left: 20px;
    padding-right: 20px;
    padding-top: 5px;
    padding-bottom: 5px;
    font-size: 16px;
    color: #fff;
    font-style: bold;

  }
  .right{
  	text-align:right;
  }
	</style>
	</head><body>");
//AGEING REPORT
function ageing($cust_name, $cust_no, $account_name, $loan_officer, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday, $format,$branch_id){
	$branch_res = mysql_query("select * from branch");
	$branch = mysql_fetch_array($branch_res);
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:"and d.branch_id=".$branch_id;
	//get officer
         $officen = mysql_query("select * from employees where employeeId='".$loan_officer."'");
               $off = mysql_fetch_assoc($officen);
               $offnum = mysql_num_rows($officen);
if($offnum == 1){ $offcer_didplay =  $off['firstName'].' '.$off['lastName'];} else{$offcer_didplay = 'All Officers';}

//get branch
$bran = mysql_query("select * from branch where branch_no='".$branch_id."'");
               $bra = mysql_fetch_assoc($bran);
               $branum = mysql_num_rows($bran);
if($branum == 1){ $branch_didplay =  $bra['branch_name'];} else{$branch_didplay = 'All branches';}

//get loan product

if($account_name == ""){ $product_didplay = "All Products";} else{$product_didplay = $account_name;}

//report name
$reportname = "AGEING REPORT"; 
//get company name.
$com = mysql_query("select * from users where userId = '".CAP_Session::get('userId')."'");
$comp = mysql_fetch_array($com);
//var_dump($comp);
if($com){
$companyd = $comp['companyId'];
$companyb = $comp['branch'];
$se = mysql_query("select * from company where companyId='".$companyd."'");
$sel = mysql_fetch_assoc($se);
if($se){
$compa = $sel['companyName'];
$address = "Physical Address: ".$sel['country']." ".$sel['city']." ".$sel['address1']." ".$sel['address2']." | Telephone: ".$sel['telephone']." | Email: ".$sel['email'];
}else{
    $compa = "unknown2".mysql_error();
}

}else{
$companyd = "unknown".mysql_error();    
}

               $content .=' <div class="panel panel-default">
              <div class="panel-body">
              <div class="green">
               <label><b>'.$compa.' - '.$reportname.' </b><span class="small right ">'.$address.'</span></label> </div>
               
         
     </div>

            </div>';
	$content .= "
		<tableclass='table borderless' width='98%'  id='AutoNumber2'>";
		if($from_year==''){
			$from_date = "0000-00-00 00:00:00";
	$to_date = date("Y-m-d h:i:s",time());
	}
	$name = ($cust_name == 'All') ? "" : $cust_name;
	$mem_no = ($cust_no == 'All') ? "" : $cust_no;
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
	if($loan_officer >0)
		$officer = "o.id='".$loan_officer."'";
	else
		$officer = "o.id > 0";
	
	$sth = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, d.int_rate as int_rate, d.int_method as int_method, d.grace_period as grace_period, d.writeoff_period as writeoff_period, d.arrears_period as arrears_period, d.written_off as written_off, d.bank_account as bank_account from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId");
	
	if(@ mysql_numrows($sth) == 0){
		$content .= "<tr><td><center><font color=red>No disbursed loans in your search options</font></center></td></tr></table></td></tr></table>";
	}
	
	$former_officer ="";
	$i=$stat+1;
	while($row = mysql_fetch_array($sth)){
		$officer = $row['of_first_name']." ".$row['of_last_name'];
		if(strcmp($former_officer, $officer) != 0){
			$content .="<table class='table borderless table-hover' width=95%><tr><th rowspan=2><b>#</b></th><th rowspan=2><b>Member Name</b></th><th rowspan=2><b>Member No.</b></th><th rowspan=2><b>Loan Amount</b></th><th rowspan=2><b>Interest Amount</b></th><th rowspan=2><b>Principal Re-Paid</b></th><th rowspan=2><b>Interest Re-Paid</b></th><th colspan=5><b>Principle in Arrears Per Age Class (days)</b></th><th rowspan=2><b>Interest in Arreas</b></th><th rowspan=2><b>Balance Principle</b></th><th rowspan=2><b>Balance Interest</b></th></tr>
            <tr><th><b>1-30</b></th><th><b>31-60</b></th><th><b>61-90</b></th><th><b>91-120</b></th><th><b>>120</b></th></tr>";
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
        
        //TOTAL INTERETS
        
        $sched_res = mysql_query("select sum(int_amt) as total_int from schedule where loan_id='".$row['id']."'");
            $sched = mysql_fetch_array($sched_res);
            
            //PRINCIPAL PAID
        
        $query = mysql_query("select sum(princ_amt) as princ_amt from payment where loan_id='".$row['id']."'");
            $paid_princ = mysql_fetch_array($query);
            
            //ARREARS
        //$arrears_days = $calc->dateToDays(date('d'), date('m'), date('Y')) - $row['arrears_period'];
        //$arrears_date = $calc->daysToDate($arrears_days, '%Y-%m-%d');
        $sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id='".$row['id']."' ");
        
        $sched = @mysql_fetch_array($sched_res);
                
        $paid_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from payment where loan_id='".$row['id']."' and date <= '".$date."'");
        
        $paid = mysql_fetch_array($paid_res);
        $sched_amount = ($sched['amount'] == NULL) ? "0" : $sched['amount'];
        $sched_int = ($sched['int_amt'] == NULL) ? "0" : $sched['int_amt'];
        $paid_amt = ($paid['amount'] == NULL) ? "0" : $paid['amount'];
        $paid_int = ($paid['int_amt'] == NULL) ? "0" : $paid['int_amt'];
        $arrears_amt = $sched_amount - $paid_amt;
        $int_arrears = $sched_int - $paid_int;
		//$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr><td>".$i."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".number_format($row['amount'], 2)."</td><td>".number_format($sched['int_amt'],2)."</td><td>".number_format($paid_princ['princ_amt'], 2)."</td><td>".number_format($int_paid, 2)."</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>".number_format($int_arrears)."</td><td>".number_format($row['balance'], 2)."</td><td>".number_format($sched['int_amt']-$int_paid, 2)."</td></tr>";
		$i++;
		   $ttamt += $row['amount'];
         $ssamt += $sched['int_amt'];
          $ppamt += $paid_princ['princ_amt'];
           $intamt += $int_paid;
            $inaamt += $int_arrears;
             $bamt += $row['balance'];
              $siamt += ($sched['int_amt']-$int_paid);
	}
	$content .="<tfooter><tr><th></th><th></th><th></th><th>".number_format($ttamt, 2)."</th><th>".number_format($ssamt,2)."</th><th>".number_format($ppamt, 2)."</th><th>".number_format($inaamt, 2)."</th><th>0</th><th>0</th><th>0</th><th>0</th><th>0</th><th>".number_format($inaamt)."</th><th>".number_format($bamt, 2)."</th><th>".number_format($siamt, 2)."</th></tr></tfooter></tbody></table></div>";
	$content .="</table></td></tr></table>";
	export($format, $content);
}
//reports_header();
ageing($_GET['cust_name'], $_GET['cust_no'], $_GET['account_name'], $_GET['loan_officer'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday'], $_GET['format'],$_GET['branch_id']);
//reports_footer();
?>
