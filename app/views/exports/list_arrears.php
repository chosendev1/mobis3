

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
//ARREARS REPORT
function list_arrears($cust_name, $cust_no, $account_name, $loan_officer, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday, $status, $format,$branch_id){
	$calc = new Date_Calc();
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
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
$reportname = "ARREARS Report"; 
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
	$content .= "<table class='table borderless' width='95%' id='AutoNumber2' align=LEFT><tr><td>
		<table class='table borderless' width='95%' id='AutoNumber2'>";
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
	if($status == 'Over Due')
		$state = " and DATEDIFF(CURDATE(), d.date) >= (d.arrears_period + d.grace_period + d.period)";
	elseif($status == 'On going')
		$state = " and DATEDIFF(CURDATE(), d.date) < (d.arrears_period + d.grace_period + d.period)";
	else
		$state = $status;
	$sth = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, (DATEDIFF(CURDATE(), d.last_pay_date) - d.arrears_period) as arrears_days, datediff(CURDATE(), d.date) as ellapsed_time, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, d.last_pay_date as last_pay_date, d.pay_freq as pay_freq, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year,p.int_rate as intrest from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId");
	
	if(@ mysql_numrows($sth) == 0){
		$content .= "<tr><td><center><font color=red>No arrears in your search options</font></center></td></tr></table></td></tr></table>";

	}
	
	$former_officer ="";
	$i=$stat+1;
	while($row = mysql_fetch_array($sth)){
		$officer = $row['of_first_name']." ".$row['of_last_name'];
		if(strcmp($former_officer, $officer) != 0){
			$content .=" <thead><th><b>#</b></th><th><b>Loan No.</b></th><th><b>Name</b></th><th><b>Loan Amount</b></th><th><b>Interest Amount</b></th><th><b>Principal in Arrears</b></th><th><b>Interest in Arrears</b></th><th><b>Penalty in Arrears</b></th><th><b>Total Arrears</b></th><th><b>Last Repayment Date</b></th></thead>";
			$former_officer = $officer;
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
			$princ_arrears ="--";
			$int_arrears = "--";
			$total_arrears ="--";
			$total_amt_arrears = 0;
			$int_amt_arrears =0;
			$princ_amt_arrears =0;
		}else{
			$princ_arrears = $arrears_amt;
			$princ_amt_arrears = $arrears_amt;
		}
		if($int_arrears <= 0){  //INT IN ARREARS
			$int_arrears = "--";
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
		$prepaid = ($prepaid <= 0) ? "--" : $prepaid;
		$due_princ_amt = $sched_amt - $paid_amt;  // - $arrears_amt + $prepaid_amt;
		$due_int_amt = $sched_int - $paid_int; 
		if($due_princ_amt <= 0){    //PRINC DUE
			$due_princ = "--";
		}else{
			$due_princ = $due_princ_amt;
		}
		if($due_int_amt <= 0){	  //INTEREST DUE
			$due_int = "--";
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
			$nowsched_amt = ($nowsched['princ_amt'] ==NULL) ? 1: $nowsched['princ_amt'];
			$repay_rate = $nowpaid_amt / $nowsched_amt;
			$repay_rate = sprintf("%.02f", $repay_rate);
			$arrears_rate = 100.00 - $repay_rate;
		}
		//PENALTY
		$pen_res = mysql_query("select sum(amount) as amount from penalty where loan_id='".$row['id']."' and status='pending'");
		$pen = mysql_fetch_array($pen_res);
		$penalty = ($pen['amount'] == NULL) ? "--" : $pen['amount'];

		//$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr><td>".$i."</td><td>".$row['mem_no']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".number_format($row['amount'], 2)."</td><td>".number_format($intmoney, 2)."</td><td>".number_format($princ_arrears, 2)."</td><td>".number_format($int_arrears, 2)."</td><td>".number_format($penalty, 2)."</td><td>".number_format(($int_arrears+$princ_arrears+$penalty), 2)."</td><td>".$lastd['lastrepayday']."</td></tr>";
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
	$content .= "<tr><th>TOTAL</th><th></th><th></th><th>".number_format($amt_sub_total, 2)."</th> <th><b>".number_format($int_sub_total, 2)."</b></th><th><b>".number_format($princ_arrears_sub_total, 2)."</b></th><th><b>".number_format($int_arrears_sub_total, 2)."</b></th><th><b>".number_format($penalty_sub_total, 2)."</b></th><th><b>".number_format($totalout,2)."</b></th><th><b></b></th><th><b></b></th></tr>";
	
	$content .= "</table></td></tr></table>";
	export($format, $content);
}

//reports_header();
list_arrears($_GET['cust_name'], $_GET['cust_no'], $_GET['account_name'], $_GET['loan_officer'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday'], $_GET['status'], $_GET['format'],$_GET['branch_id']);
//reports_footer();
?>
