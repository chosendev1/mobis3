<?php


if(!isset($_GET['format']))
echo("<head>
	<title>PORTFOLIO STATUS REPORT</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");
//PORTIFOILIO STATUS REPORT
function portfolio_status($cust_name, $cust_no, $account_name, $loan_officer, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday, $status, $format, $branch_id, $num_rows,$stat){
	$calc = new Date_Calc();
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL: ' and applic.branch_id='.$branch_id."";
	
	$content .= "<center><p><font color=#00008b size=4pt><b>".$branch['branch_name']."</b></font></p></center>";
	$content .= "<center><font color=#00008b size=3pt><b>PORTFOLIO STATUS REPORT</b></font></center><table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='70%' id='AutoNumber2' align=center><tr><td>
		<table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='70%' id='AutoNumber2' align=center>";
	if($from_year==''){
		$content .= "<tr bgcolor=lightgrey><td><center><font color=red>Please select the options for the report!</font></center></td></tr></table></td></tr></table>";
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
	$sth = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.first_name as of_first_name, o.last_name as of_last_name, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, (DATEDIFF(CURDATE(), d.last_pay_date) - d.arrears_period) as arrears_days, datediff(CURDATE(), d.date) as ellapsed_time, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join officer o on applic.officer_id=o.id where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.written_off='0' ".$state . " ".$branch_." order by o.first_name, o.last_name, m.mem_no, m.first_name, m.last_name, d.date ");


	if(@ mysql_numrows($sth) == 0){
		$content .= "<tr bgcolor=lightgrey><td><center><font color=red>No results in your search options</font></center></td></tr></table></td></tr></table>";
	}
	
	$former_officer ="";
	$i=$stat+1;
	while($row = mysql_fetch_array($sth)){
		$officer = $row['of_first_name']." ".$row['of_last_name'];
		if(strcmp($former_officer, $officer) != 0){
			$content .="<tr class='headings'><td colspan=19><b>Loan Officer:  ".$officer."</b></td></tr> <tr class='headings'><td rowspan=2><b>No</b></td><td rowspan=2><b>Date Disbursed</b></td><td rowspan=2><b>MemberNo</b></td><td rowspan=2><b>Member Name</b></td><td rowspan=2><b>Product</b></td><td rowspan=2><b>Amount Awarded</b></td><td colspan=2><b>Amt Due</b></td><td colspan=2><b>Amt Paid</b></td><td rowspan=2><b>Amt Prepaid</b></td><td colspan=2><b>Arrears At Start Of Period</b></td><td colspan=2><b>Arrears At End Of Period</b></td><td colspan=2><b>Outstanding Balance</b></td></td><td rowspan=2><b>Repayment Rate(%)</b></td><td rowspan=2><b>Cummulated Repayment Rate(%)</b></td></tr>
			<tr class='headings'><td><b>Princ</b></td><td><b>Interest</b></td> <td><b>Princ</b></td><td><b>Interest</b></td> <td><b>Princ</b></td><td><b>Interest</b></td> <td><b>Princ</b></td><td><b>Interest</b></td> <td><b>Princ</b></td><td><b>Interest</b></td></tr>";
			$former_officer = $officer;
		}

				//ARREARS AT BEGINNING OF PERIOD
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id='".$row['id']."' and date < DATE_SUB(CURDATE(), INTERVAL ".($row['arrears_period']+30)." DAY)");
		$sched = @mysql_fetch_array($sched_res);
	
		$paid_res = mysql_query("select sum(princ_amt) as princ_amt, sum(int_amt) as int_amt from payment where loan_id='".$row['id']."' and date <= DATE_SUB(CURDATE(), INTERVAL 30 DAY)");
		
		$paid = mysql_fetch_array($paid_res);
		$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? 0 : $sched['int_amt'];
		$paid_princ = ($paid['princ_amt'] == NULL) ? 0 : $paid['princ_amt'];
		$paid_int = ($paid['int_amt'] == NULL) ? 0 : $paid['int_amt'];
		$start_princ_arrears = $sched_amt - $paid_princ;
		$start_princ_arrears = ($start_princ_arrears < 0) ? 0 : $start_princ_arrears; 
		
		$start_int_arrears = $sched_int - $paid_int;
		//$start_int_arrears = ($start_int_arrears < 0) ? 0 : $start_int_arrears; 
		
		
		//ARREARS AT THE END OF PERIOD
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id='".$row['id']."' and date < DATE_SUB(CURDATE(), INTERVAL ".($row['arrears_period'])." DAY)");
		$sched = @mysql_fetch_array($sched_res);
	
		$paid_res = mysql_query("select sum(princ_amt) as princ_amt, sum(int_amt) as int_amt from payment where loan_id='".$row['id']."' and date <= CURDATE()");
		
		$paid = mysql_fetch_array($paid_res);
		$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? 0 : $sched['int_amt'];
		$paid_princ = ($paid['princ_amt'] == NULL) ? 0 : $paid['princ_amt'];
		$paid_int = ($paid['int_amt'] == NULL) ? 0 : $paid['int_amt'];
		$end_princ_arrears = $sched_amt - $paid_princ;
		$end_princ_arrears = ($end_princ_arrears < 0) ? 0 : $end_princ_arrears;
		$end_int_arrears = $sched_int - $paid_int;
		
		//CALCULATE DUE PRINCIPAL CUMMULATIVE
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id='".$row['id']."' and date <= CURDATE()");
		
		$sched = @mysql_fetch_array($sched_res);
		$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? 0 : $sched['int_amt'];
		$prepaid = $paid_princ - $sched_amt;
		$prepaid = ($prepaid <= 0) ? "--" : $prepaid;
		$due_princ_amt = $sched_amt - $paid_princ - $end_princ_arrears;
		$due_princ_amt = ($due_princ_amt > 0) ? $due_princ_amt : 0;
		
		$due_int_amt = $sched_int - $paid_int - $end_int_arrears;   //INTEREST DUE
		$outstandg_int = $sched_int - $paid_int;
		//CUMMULATIVE RATES
		//CHECK WHETHER ALL LOAN IS OVER DUE
		if($row['ellapsed_time'] >= $row['loan_period'] + $row['arrears_period']){
			$cumm_repay_rate = 0.00;
			$repay_rate = 0.00;
		}else{
			$sched_amt_den = ($sched_amt == 0) ? 1 : $sched_amt;
			$cumm_repay_rate = ($paid_princ * 100) / $sched_amt_den;
			$cumm_repay_rate = sprintf("%.02f", $cumm_repay_rate);
			$nowsched_res = mysql_query("select * from schedule where loan_id='".$row['id']."' and date < CURDATE() order by date desc limit 1");
			$nowsched = mysql_fetch_array($nowsched_res);
			$nowpaid_res = mysql_query("select sum(princ_amt) as princ_amt from payment where loan_id='".$row['id']."' and date >= '".$nowsched['date']."' and date < DATE_ADD(date, INTERVAL 30 DAY)");
			
			$nowpaid = mysql_fetch_array($nowpaid_res);
			$nowsched_amt = ($nowsched['princ_amt'] != NULL) ? $nowsched['princ_amt'] : 1;
			$nowpaid_amt = ($nowpaid['princ_amt'] == NULL) ? 0 : $nowpaid['princ_amt'];
			$repay_rate = $nowpaid_amt / $nowsched_amt;
			$repay_rate = sprintf("%.02f", $repay_rate);
		}
		
		$outstandg_int = ceil($outstandg_int);
		$due_int_amt = ceil($due_int_amt);
		
		$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr bgcolor=$color><td>".$i."</td><td>".$row['date']."</td><td>".$row['mem_no']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['account_no']." - ".$row['account_name']."</td><td>".number_format($row['amount'], 2)."</td><td>".number_format($due_princ_amt, 2)."</td><td>".number_format($due_int_amt, 2)."</td><td>".number_format($paid_princ, 2)."</td><td>".number_format($paid_int, 2)."</td><td>".number_format($prepaid, 2)."</td><td>".number_format($start_princ_arrears, 2)."</td><td>".number_format($start_int_arrears, 2)."</td><td>".number_format($end_princ_arrears, 2)."</td><td>".number_format($end_int_arrears, 2)."</td><td>".number_format($row['balance'], 2)."</td><td>".number_format($outstandg_int, 2)."</td><td>".$repay_rate."</td><td>".$cumm_repay_rate. "</td><td>".$cumm_arrears_rate."</td></tr>";
		$i++;
	}
	$content .= "</table></td></tr></table>";
	export($format, $content);
}
//reports_header();
portfolio_status($_GET['cust_name'], $_GET['cust_no'], $_GET['account_name'], $_GET['loan_officer'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday'], $_GET['status'], $_GET['format'],$_GET['branch_id'],$_GET['num_rows'],$_GET['stat']);
//reports_footer();
?>
