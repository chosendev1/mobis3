<?php

if(!isset($_GET['format']))
echo("<head>
	<title>LIST OF DUE PAYMENTS</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");

//LIST OUTSTANDING
function list_due($cust_name, $cust_no, $account_name, $loan_officer,$date,$format,$branch_id){
	
	$calc = new Date_Calc();
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:"and d.branch_id=".$branch_id;
	$content .= "<center><p><font color=#00008b size=4pt><b>".$branch['branch_name']."</b></font></p></center>";
	$content .= "<center><font color=#00008b size=3pt><b>LIST OF DUE PAYMENTS</b></font></center><table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center><tr><td>
		<table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>";
	if($to_year==''){
		$content .= "<tr><td><center><font color=red>Please select the disbursed loans you would like to list!</font></center></td></tr></table></td></tr></table>";
	}
	$name = ($cust_name == 'All') ? "" : $cust_name;
	$mem_no = ($cust_no == 'All') ? "" : $cust_no;
	//$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	//$date = sprintf("%d-%02d-%02d 23:59:59", $year, $month, $mday);
	if($loan_officer >0)
		$officer = "o.id='".$loan_officer."'";
	else
		$officer = "o.id > 0";
	
	$sth = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.first_name as of_first_name, o.last_name as of_last_name, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join officer o on applic.officer_id=o.id where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer."  and d.date <='".$date."' and d.balance > 0 and d.written_off=0 ".$branch_." order by o.first_name, o.last_name, m.first_name, m.last_name");
	
	if(@ mysql_numrows($sth) == 0){
		$content .= "<tr><td><center><font color=red>No Due Payments in your search options</font></center></td></tr></table></td></tr></table>";
	}
	
	$former_officer ="";
	$i=$stat+1;
	while($row = mysql_fetch_array($sth)){
		$officer = $row['of_first_name']." ".$row['of_last_name'];
		if(strcmp($former_officer, $officer) != 0){
			$content .="<tr class='headings'><td colspan=16><b>Loan Officer:  ".$officer."</b></td></tr> <tr class='headings'><td><b>No</b></td><td><b>Member Name</b></td><td><b>MemberNo</b></td><td><b>Date</b></td><td><b>Product</b></td><td><b>Amount</b></td><td><b>Loan Balance</b></td><td><b>Princ Arrears</b></td><td><b>Int Arrears</b></td><td><b>Total Arrears</b></td><td><b>Penalty</b></td><td><b>Princ Due</b></td><td><b>Int Due</b></td><td><b>Total Amt Due</b></td><td><b>Schedule</b></td><td><b>Payment</b></td></tr>";
			$former_officer = $officer;
		}

				//ARREARS
		$arrears_days = $calc->dateToDays(date('d'), date('m'), date('Y')) - $row['arrears_period'];
		$arrears_date = $calc->daysToDate($arrears_days, '%Y-%m-%d');
		$sched_res = mysql_query("select sum(princ_amt) as amount from schedule where loan_id='".$row['id']."' and date < '".$arrears_date."'");
		
		$sched = @mysql_fetch_array($sched_res);
				
		$paid_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from payment where loan_id='".$row['id']."' and date <= NOW()");
		
		$paid = mysql_fetch_array($paid_res);
		$sched_amount = ($sched['amount'] == NULL) ? "0" : $sched['amount'];
		$paid_amt = ($paid['amount'] == NULL) ? "0" : $paid['amount'];
		$paid_int = ($paid['int_amt'] == NULL) ? "0" : $paid['int_amt'];
		$arrears_amt = $sched_amount - $paid_amt;
		
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
			
			/*
			$arrears_days =$arrears_days - $calc->dateToDays($row['last_mday'], $row['last_month'], $row['last_year']);
			$no_months = floor($arrears_days / 30);
			if($no_months <= 0 ){
				$int_arrears = 0;		
			}elseif($row['int_method'] == 'Declining Balance')
				$int_arrears = ceil((($row['balance'] * $row['int_rate']/100) /12) * $no_months);
			elseif($row['int_method'] == 'Flat')
					$int_arrears = ceil((($row['disbursed_amt'] * $row['int_rate']/100) /12) * $no_months);
			*/
			$intsched = mysql_fetch_array(mysql_query("select sum(int_amt) as int_amt from schedule where  date < '".$arrears_date."' and loan_id='".$row['id']."'"));
			$int_arrears =($intsched['int_amt'] == NULL) ? 0 : $intsched['int_amt'];
			$intpaid = mysql_fetch_array(mysql_query("select sum(int_amt) as int_amt from payment where date < '".$arrears_date."' and loan_id='".$row['id']."'"));
			$int_arrears_paid =($intpaid['int_amt'] == NULL) ? 0 : $intpaid['int_amt'];
			
			$int_arrears -= $int_arrears_paid;
			$total_arrears = $princ_arrears + $int_arrears;
			$total_amt_arrears = $total_arrears;
			$int_amt_arrears = $int_arrears;
			$int_arrears = ($int_arrears == 0) ? "--" : $int_arrears;
		}
		
		//CALCULATE DUE PRINCIPAL
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id='".$row['id']."' and date <= NOW()");
		$sched = @mysql_fetch_array($sched_res);
		$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? 0 : $sched['int_amt'];
		$due_princ_amt = $sched_amt - $paid_amt;  // - $arrears_amt;	
		$due_int_amt = $sched_int - $paid_int;
		
		if($due_princ_amt <= 0){
			continue;
		}else{
				$due_princ = $due_princ_amt;
			if($due_int_amt >0){	
				$due_int = $due_int_amt;
			}else
				$due_int="--"; 
		}		
		$total_amt_due = $due_int_amt + $due_princ_amt;
		$total_due = ($total_amt_due <= 0) ? "--" : $total_amt_due;
		
		$pay = "<a href='list_payments.php?loan_id=".$row['id']."&applic_id=".$row['applic_id']."&cust_name=".$cust_name."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&princ_arrears=".$princ_amt_arrears."&int_arrears=".$int_amt_arrears."&princ_due=".$due_princ_amt."&int_due=".$due_int_amt."&from-year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&status=outstanding' target=blank()>Payments</a>";
		
		$schedule = "<a href='list_schedule.php?loan_id=".$row['id']."&applic_id=".$row['applic_id']."&cust_name=".$cust_name."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&princ_arrears=".$princ_amt_arrears."&int_arrears=".$int_amt_arrears."&princ_due=".$due_princ_amt."&int_due=".$due_int_amt."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&status=due' target=blank()>Schedule</a>";

		//PENALTY
		$pen_res = mysql_query("select sum(amount) as amount from penalty where status='pending' and loan_id='".$row['id']."'");
		$pen = mysql_fetch_array($pen_res);
		$penalty = ($pen['amount'] == NULL) ? "--" : $pen['amount'];
		$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr bgcolor=$color><td>".$i."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".$row['date']."</td><td>".$row['account_no']." - ".$row['account_name']."</td><td>".number_format($row['amount'], 2)."</td><td>".number_format($row['balance'], 2)."</td><td>".number_format($princ_arrears, 2)."</td><td>".number_format($int_arrears, 2)."</td><td>".number_format($total_arrears, 2)."</td><td>".number_format($penalty, 2)."</td><td>".number_format($due_princ, 2)."</td><td>".number_format($due_int, 2)." ".$row['last_pay_date']."</td><td>".number_format($total_due, 2)."</td><td>".$schedule."</td><td>".$pay."</td></tr>";
		$i++;
	// GET SUB TOTALS
		$amt_sub_total += $row['amount']; 
		$bal_sub_total += $row['balance']; 
		$princ_arrears_sub_total += $princ_arrears; 
		$int_arrears_sub_total += $int_arrears;
		$tot_arrears_sub_total += $total_arrears;
		$penalty_sub_total += $penalty; 
		$princ_due_sub_total += $due_princ; 
		$int_due_sub_total += $due_int; 
		$tot_amt_sub_total += $total_due;
	}
	// PRINT SUB TOTALS
	$content .= "<tr class='headings'><td colspan='5'><b>TOTAL</b></td><td><b>".number_format($amt_sub_total, 2)."</b></td><td><b>".number_format($bal_sub_total, 2)."</b></td><td><b>".number_format($princ_arrears_sub_total, 2)."</b></td><td><b>".number_format($int_arrears_sub_total, 2)."</b></td><td><b>".number_format($tot_arrears_sub_total, 2)."</b></td><td><b>".number_format($penalty_sub_total, 2)."</b></td><td><b>".number_format($princ_due_sub_total, 2)."</b></td><td><b>".number_format($int_due_sub_total, 2)."</b></td><td><b>".number_format($tot_amt_sub_total, 2)."</b></td><td colspan=2>&nbsp;</td></tr>";
	$content .="</table></td></tr></table>";
	export($format, $content);
}

//reports_header();
list_due($_GET['cust_name'], $_GET['cust_no'], $_GET['account_name'], $_GET['loan_officer'], $_GET['date'],$_GET['format'],$_GET['branch_id'],$_GET['num_rows'],$_GET['stat']);
//reports_footer();
?>
