<?php

if(!isset($_GET['format']))
echo("<head>
	<title>REPAYMENT REPORT</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");
function repayment($cust_name, $cust_no, $account_name, $loan_officer, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday, $status, $format,$branch_id){
	$calc = new Date_Calc();
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:"and d.branch_id=".$branch_id;
	$content .= "<center><p><font color=#00008b size=4pt><b>".$branch['branch_name']."</b></font></p></center>";
	$content .= "<center><font color=#00008b size=3pt><b>REPAYMENT REPORT</b></font></center><table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center><tr><td>
		<table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>";
	if($from_year==''){
		$content .= "<tr><td><center><font color=red>Please select the options for the Arrears report!</font></center></td></tr></table></td></tr></table>";
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
	$sth = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.first_name as of_first_name, o.last_name as of_last_name, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, (DATEDIFF(CURDATE(), d.last_pay_date) - d.arrears_period) as arrears_days, datediff(CURDATE(), d.date) as ellapsed_time, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join officer o on applic.officer_id=o.id where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.written_off='0'".$state ." ".$branch_." order by o.first_name, o.last_name");
	
	if(@ mysql_numrows($sth) == 0){
		$content .= "<tr><td><center><font color=red>No loans in your search options</font></center></td></tr></table></td></tr></table>";
	}
	
	$former_officer ="";
	$i=$stat+1;
	while($row = mysql_fetch_array($sth)){
		$officer = $row['of_first_name']." ".$row['of_last_name'];
		if(strcmp($former_officer, $officer) != 0){
			$content .="<tr class='headings'><td colspan=19><b>Loan Officer:  ".$officer."</b></td></tr> <tr bgcolor=#cdcdcd><td><b>No</b></td><td><b>Date Disbursed</b></td><td><b>MemberNo</b></td><td><b>Member Name</b></td><td><b>Product</b></td><td><b>Amount</b></td><td><b>Princ Paid</b></td><td><b>Int Paid</b></td><td><b>Total Paid</b></td><td><b>Princ Arrears</b></td><td><b>Int Arrears</b></td><td><b>Amt Prepaid</b></td></td><td><b>Repayment Rate(%)</b></td><td><b>Cummulated Repayment Rate(%)</b></td><td><b>Outstanding Balance</b></td></tr>";
			$former_officer = $officer;
		}

		//ARREARS
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id='".$row['id']."' and date < DATE_SUB(CURDATE(), INTERVAL ".$row['arrears_period']." DAY)");
		$sched = @mysql_fetch_array($sched_res);
	
		$paid_res = mysql_query("select sum(princ_amt) as princ_amt, sum(int_amt) as int_amt from payment where loan_id='".$row['id']."' and date <= CURDATE()");
		
		$paid = mysql_fetch_array($paid_res);
		$sched_amount = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? 0 : $sched['int_amt'];
		$princ_paid_amt = ($paid['princ_amt'] == NULL) ? 0 : $paid['princ_amt'];
		$int_paid_amt = ($paid['int_amt'] == NULL) ? 0 : $paid['int_amt'];
		$arrears_amt = $sched_amount - $princ_paid_amt;
		$int_arrears = $sched_int - $int_paid_amt;
		
		if($arrears_amt <= 0){
			$princ_arrears ="--";
			$int_arrears = "--";
			$total_arrears ="--";
			$total_amt_arrears = 0;
			$int_amt_arrears =0;
			$princ_amt_arrears =0;
		}else{
			$princ_arrears = $arrears_amt;
			$princ_amt_arrears = $arrears_amt;
			$total_amt_arrears = $total_arrears;
		}
		if($int_arrears <= 0){
			$int_arrears = "--";
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
			$nowsched_amt = ($nowsched['princ_amt'] != NULL) ? $nowsched['princ_amt'] : 1;
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
		$prepaid = ($prepaid == 0) ? "--" : $prepaid;
		$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr bgcolor=$color><td>".$i."</td><td>".$row['date']."</td><td>".$row['mem_no']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['account_no']." - ".$row['account_name']."</td><td>".number_format($row['amount'], 2)."</td><td>".number_format($princ_paid_amt, 2)."</td><td>".number_format($int_paid_amt, 2)."</td><td>".number_format(($princ_paid_amt + $int_paid_amt), 2)."</td><td>".number_format($princ_arrears, 2)."</td><td>".number_format($int_arrears, 2)."</td><td>".number_format($prepaid, 2)."</td><td>".$repay_rate."</td><td>".$cumm_repay_rate. "</td><td>".number_format($row['balance'], 2)."</td></tr>";
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
	$content .= "<tr class='headings'><td colspan='5'><b>TOTAL</b></td><td><b>".number_format($amt_sub_total, 2)."</b></td><td><b>".number_format($princ_paid_sub_total, 2)."</b></td><td><b>".number_format($int_paid_sub_total, 2)."</b></td><td><b>".number_format($total_paid_sub_total, 2)."</b></td><td><b>".number_format($princ_arrears_sub_total, 2)."</b></td><td><b>".number_format($int_arrears_sub_total, 2)."</b></td><td><b>".number_format($prepaid_sub_total, 2)."</b></td><td colspan=2><b></b></td><td ><b>".number_format($balance_sub_total, 2)."</b></td></tr>";
	$content .="</table></td></tr></table>";
	export($format, $content);
}

//reports_header();
repayment($_GET['cust_name'], $_GET['cust_no'], $_GET['account_name'], $_GET['loan_officer'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday'], $_GET['status'], $_GET['format'],$_GET['branch_id']);
//reports_footer();
?>
