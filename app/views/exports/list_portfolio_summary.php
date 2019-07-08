<?php

if(!isset($_GET['format']))
echo("<head>
	<title>SAVINGS PRODUCT ACCOUNTS FOR MEMBERS</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");

function portfolio_summary($year, $month, $mday, $format){
	
	$calc = new Date_Calc();
	$date = sprintf("%d-%02d-%02d", $year, $month, $mday);
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$content = "<center><p><font color=#00008b size=4pt><b>".$branch['branch_name']."</b></font></p></center>";
	$content .= "<center><font color=#00008b size=3pt><B>INSTITUTION'S PORTFOLIO SUMMARY REPORT AS OF ".$date." </B></font></center>";
	$content .= "<form method=post></form>";
	if($year ==''){
		$content .= "<table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center><tr><td><font color=red>Select the period for which you want the report</font></td></tr></table>";
		echo($content);
		return 1;
	}
	$content="	<table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='80%' id='AutoNumber2' align=center><tr class='headings'><th rowspan=2>A: LOAN ACTIVITY</th><th colspan=2>Loans Disbursed This Period</th><th colspan=2>Total Loans Disbursed</th></tr>
	<tr class='headings'><th>No of Loans</th><th>Amount Disbursed</th><th>No of Loans</th><th>Amount Disbursed</th></tr>";
	$date = sprintf("%d-%02d-%02d 23:59:59", $year, $month, $mday);
	$tot_res = mysql_query("select count(d.id) as no, sum(d.amount) as amount, d.cycle as cycle from disbursed d where d.date <= '".$date."' group by d.cycle order by d.cycle asc");
	
	$x=1;
	while($tot = mysql_fetch_array($tot_res)){
		if($tot['cycle'] == 1) {
			$text = "First Loans";
			$tot_no = $tot['no'];
			$tot_amount = $tot['amount'];
		}
		if($tot['cycle'] == '2') {
			$text = "Second Loans";
			$tot_no = $tot['no'];
			$tot_amount = $tot['amount'];
		}
		if($tot['cycle'] == 3) {
			$text = "Third Loans";
			$tot_no = $tot['no'];
			$tot_amount = $tot['amount'];
		}
		if($tot['cycle'] == 4) {
			$text = "Fourth Loans";
			$tot_no = $tot['no'];
			$tot_amount = $tot['amount'];
		}
		if($tot['cycle'] == 5) {
			$text = "Fifth Loans";
			$tot_no = $tot['no'];
			$tot_amount = $tot['amount'];
		}
		if($tot['cycle'] >= 6) {
			$tot_res = mysql_query("select count(d.id) as no, sum(d.amount) as amount from disbursed d where d.date <= '".$date."' and d.cycle >=6");
			$tot = mysql_fetch_array($tot_res);
			$text = "Sixth Loans And Above";
			$tot_no = $tot['no'];
			$tot_amount = $tot['amount'];
			//break;
		}
		if($text == "Sixth Loans And Above")
			$where_cycle = " d.cycle >=6";
		else
			$where_cycle = " d.cycle=".$tot['cycle'];
		$now_res = mysql_query("select count(d.id) as no, sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id = applic.id join loan_product p on applic.product_id = p.id join accounts a on p.account_id=a.id where d.date >= DATE_SUB('".$date."', INTERVAL 30 DAY) and d.date <= '".$date."' and ".$where_cycle."");
		
		$now = mysql_fetch_array($now_res);
		$tot_no = ($tot['no'] == NULL) ? "--" : $tot['no'];
		$now_no = ($now['no'] == NULL) ? "--" : $now['no'];
		$tot_amount = ($tot['amount'] == NULL) ? "--" : $tot['amount'];
		$now_amount = ($now['amount'] == NULL) ? "--" : $tot['amount'];
		$color = ($x%2 == 0) ? "white" : "lightgrey";
		$content .= "<tr bgcolor=$color><td>".$x.". ".$text."</td><td>".$now_no."</td><td>".number_format($now_amount, 2)."</td><td>".$tot_no."</td><td>".number_format($tot_amount, 2)."</td></tr>";
		$x++;
		if($text == "Sixth Loans And Above")
			break;
	}


	$tot_res = mysql_query("select count(d.id) as no, sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id = applic.id join loan_product p on applic.product_id = p.id join accounts a on p.account_id=a.id where d.date <= '".$date."'");
	$tot = mysql_fetch_array($tot_res);
	$now_res = mysql_query("select count(d.id) as no, sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id = applic.id join loan_product p on applic.product_id = p.id join accounts a on p.account_id=a.id where d.date >= DATE_SUB('".$date."', INTERVAL 30 DAY) and d.date <= '".$date."'");	
	$now = mysql_fetch_array($now_res);
	
	$tot_no = ($tot['no'] == NULL || $tot_no =="--") ? "--" : $tot['no'];
	$now_no = ($now['no'] == NULL || $now_no =="--") ? "--" : $now['no'];
	$tot_amount = ($tot['amount'] == NULL || $tot_amount =="--") ? "--" : $tot['amount'];
	$now_amount = ($now['amount'] == NULL || $now_amount =="--") ? "--" : $tot['amount'];

	$color = ($x%2 == 0) ? "white" : "lightgrey";
	$content .= "<tr bgcolor=$color><td><b>Total Disbursements</b></td><td><b>".$now_no."</b></td><td><b>".number_format($now_amount, 2)."</b></td><td><b>".$tot_no."</b></td><td><b>".number_format($tot_amount, 2)."</b></td></tr>";
	$x++;
	

	//CLIENT INFORMATION
	$group_res = mysql_query("select count(name) as no from loan_group");
	$group = mysql_fetch_array($group_res);
	$groups = ($group['no'] == NULL) ? "--" : $group['no'];
	$client_res = mysql_query("select count(*) as no from member");
	$client = mysql_fetch_array($client_res);
	$client_no = ($client['no'] == NULL) ? "--" : $client['no'];
	$content .= "<tr class='headings'><th colspan=3 align=left>B: CLIENTS INFORMATION</th><th>GROUPS</th><TH>CLIENTS</TH></tr>
	<tr bgcolor=white><td colspan=3>Total Number of Groups / Clients End of Last Period</td><td>".$groups."</td><td>".$client_no."</td></tr>";


	//outstanding 
	$content .= "<tr class='headings'><th>C: LOANS OUTSTANDING</th><th colspan=2>PRINCIPAL</th><th colspan=2>INTEREST</th></tr>";
	$sth = mysql_query("select sum(d.amount) as amount, d.cycle as cycle from  disbursed d  where d.date <= '".$date."' group by d.cycle asc");

	$x=1;
	$total_princ =0;
	$total_int = 0;
	$int6_amt = 0;
	$princ6_int = 0;


	$sched_res = mysql_query("select sum(s.princ_amt) as princ_amt, sum(s.int_amt) as int_amt, d.cycle from schedule s join disbursed d on s.loan_id=d.id where d.date <= '".$date."' group by d.cycle order by d.cycle asc");
	while($tot = mysql_fetch_array($sched_res)){
		$paid_res = mysql_query("select sum(p.princ_amt) as princ_amt, sum(p.int_amt) as int_amt, d.cycle from payment p join disbursed d on p.loan_id=d.id where d.date <= '".$date."' and d.cycle='".$tot['cycle']."' group by d.cycle");
		$paid = mysql_fetch_array($paid_res);
		$paid_amt = ($paid['princ_amt'] == NULL) ? 0 : $paid['princ_amt'];
		$paid_int = ($paid['int_amt'] == NULL) ? 0 : $paid['int_amt'];

		$sched_amt = ($tot['princ_amt'] == NULL) ? 0 : $tot['princ_amt'];
		$sched_int = ($tot['int_amt'] == NULL) ? 0 : $tot['int_amt'];
		$princ_out = $sched_amt - $paid_amt;
		$int_out = $sched_int - $paid_int;
		if($tot['cycle'] == 1) {
			$text = "First Loans";
		}
		if($tot['cycle'] == 2) {
			$text = "Second Loans";
		}
		if($tot['cycle'] == 3) {
			$text = "Third Loans";
		}
		if($tot['cycle'] == 4) {
			$text = "Fourth Loans";
		}
		if($tot['cycle'] == 5) {
			$text = "Fifth Loans";
		}
		if($tot['cycle'] >= 6) {
			$int6_amt += $int_out;
			$princ6_amt += $princ_out;
			$test_res = mysql_query("select * from disbursed where cycle >'".$tot['cycle']."' and date <= '".$date."'");
			
			if(mysql_numrows($test_res) == 0){
				$text = "Sixth Loans And Above";
				$int_out = $int6_amt;
				$princ_out = $princ6_amt;
			}else
				continue;
		}

		$color = ($x%2 == 0) ? "white" : "lightgrey";
		$content .= "<tr bgcolor=$color><td>".$x.". ".$text."</td><td colspan=2>".number_format($princ_out, 2)."</td><td colspan=2>".number_format($int_out, 2)."</td></tr>";
		$total_princ += $princ_out;
		$total_int += $int_out;
		$x++;
	}
	$color = ($x%2 == 0) ? "white" : "lightgrey";	
	$content .= "<tr bgcolor=$color><td><b>Total</b></td><td colspan=2><b>".number_format($total_princ, 2)."</b></td><td colspan=2><b>".number_format($total_int, 2)."</b></td></tr>";
	
	$sth = mysql_query("select count(id) as no from disbursed where DATEDIFF('".$date."', date) >= 30 and id not in (select loan_id from written_off where DATEDIFF('".$date."', date) >= 30)");
	$row = mysql_fetch_array($sth);
	$loans_end = ($row['no'] == NULL) ? 0 : $row['no'];
	$x++;
	$color = ($x%2 == 0) ? "white" : "lightgrey";
	$content .= "
	<tr bgcolor=$color><td colspan=3>Number of Loans End of Last period</td><td colspan=2>".$loans_end."</td></tr>";

//OUTSTANDING P & I AT END LAST PERIOD

	$sched_res = mysql_query("select sum(s.princ_amt + s.int_amt) as amount, sum(s.int_amt) as int_amt from schedule s join disbursed d on s.loan_id=d.id where d.date <= DATE_SUB('".$date."', INTERVAL 30 DAY)");
	$sched = mysql_fetch_array($sched_res);
	$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
	$paid_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount, sum(p.int_amt) as int_amt from payment p join disbursed d on p.loan_id=d.id where d.date <= DATE_SUB('".$date."', INTERVAL 30 DAY)");
	$paid = mysql_fetch_array($paid_res);
	$paid_amt = ($paid['amount'] == NULL) ? 0 : $paid['amount'];
	$total_outstanding = $sched_amt - $paid_amt;	
	$x++;
	$color = ($x%2 == 0) ? "white" : "lightgrey";
	$content .="
	<tr bgcolor=$color><td colspan=3>Outstanding P & I End of Last Period</td><td colspan=2>".number_format($total_outstanding, 2)."</td></tr>";

//Number of Loans Disbursed this Period
	$sth = mysql_query("select count(id) as no from disbursed where DATEDIFF('".$date."', date) <= 30 and DATEDIFF('".$date."', date) >= 0 and id not in (select loan_id from written_off where date < '".$date."')");
	$row = mysql_fetch_array($sth);
	$loans_this_period = ($row['no'] == NULL) ? 0 : $row['no'];
	$x++;
	$color = ($x%2 == 0) ? "white" : "lightgrey";
	$content .="
	<tr bgcolor=$color><td colspan=3>Number of Loans Disbursed this Period</td><td colspan=2>".$loans_this_period."</td></tr>";

//Disbursed Amount [Principal] This Period
	$sth = mysql_query("select sum(amount) as amount from disbursed where DATEDIFF('".$date."', date) <= 30 and DATEDIFF('".$date."', date) >=0 and id not in (select loan_id from written_off where date < '".$date."')");
	$row = mysql_fetch_array($sth);
	$amount_dis_this_period = ($row['amount'] == NULL) ? 0 : $row['amount'];
	$x++;
	$color = ($x%2 == 0) ? "white" : "lightgrey";
	$content .="
	<tr bgcolor=$color><td colspan=3>Disbursed Amount [Principal] This Period</td><td colspan=2>".$amount_dis_this_period."</td></tr>";

	$sth = mysql_query("select sum(princ_amt) as amount from payment where  loan_id not in (select loan_id from written_off where date <= '".$date."') and date <= '".$date."'");
	$row = mysql_fetch_array($sth);
	$pay_amt = ($row['amount'] == NULL) ? 0 : $row['amount'];
	$write_res = mysql_query("select sum(amount) as amount from written_off where date <= '".$date."'");
	$write = mysql_fetch_array($write_res);
	$write_amt = ($write['amount'] == NULL) ? 0 : $write['amount'];
	$tot_debits = $write_amt + $pay_amt;

	$x++;
	$color = ($x%2 == 0) ? "white" : "lightgrey";
	$content .="
	<tr bgcolor=$color><td colspan=3>Total Debits</td><td colspan=2>".number_format($tot_debits, 2)."</td></tr>";

	$sth = mysql_query("select sum(amount) as amount from disbursed where date <= '".$date."'");
	$row = mysql_fetch_array($sth);
	$tot_credits = ($row['amount'] == NULL) ? 0 : $row['amount'];
	$x++;
	$color = ($x%2 == 0) ? "white" : "lightgrey";
	$content .="
	<tr bgcolor=$color><td colspan=3>Total Credits</td><td colspan=2>".number_format($tot_credits, 2)."</td></tr>";
	
//OUTSTANDING PRINC AND INTEREST
	$sched_res = mysql_query("select sum(s.princ_amt + s.int_amt) as amount, sum(s.int_amt) as int_amt from schedule s join disbursed d on s.loan_id=d.id where d.date <= '".$date."'");
	$sched = mysql_fetch_array($sched_res);
	$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
	$paid_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount, sum(p.int_amt) as int_amt from payment p join disbursed d on p.loan_id=d.id where d.date <= '".$dat."'");
	$paid = mysql_fetch_array($paid_res);
	$paid_amt = ($paid['amount'] == NULL) ? 0 : $paid['amount'];
	$total_outstanding = $sched_amt - $paid_amt;	
	$x++;
	$color = ($x%2 == 0) ? "white" : "lightgrey";
	$content .="
	<tr bgcolor=$color><td colspan=3>P & I Outstanding</td><td colspan=2>".number_format($total_outstanding, 2)."</td></tr>";
	
	//CURRENT LOAN STATUS
	$x++;
	$content .="
	<tr class='headings'><td><b>D: CURRENT LOAN STATUS:</b></td><td><b>P & I DUE TO DATE</b></td><td><b>P & I PAID TO DATE</b></td><td><b>PREPAYMENTS</b></td><td><b>ARREARS</b></td></tr>";
	$sth = mysql_query("select sum(d.amount) as amount, a.account_no as account_no, a.name as name, p.id as product_id, d.cycle as cycle from schedule s join disbursed d on s.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where d.date <= '".$date."' group by d.cycle order by d.cycle");
	$sub_amt_due = 0;
	$sub_paid_pi_todate = 0;
	$sub_pre_paid_todate = 0;
	$sub_amt_arrears = 0;
	//FOR 6TH LOANS AND ABOVE
	$total_amt_due6 = 0;
	$paid_pi_todate6 = 0;
	$pre_paid_todate6 = 0;
	$total_amt_arrears6 = 0;
	/* while($row = mysql_fetch_array($sth)){
		$pre_paid_todate = 0;
		$total_amt_due =0;
		$total_amt_arrears = 0;
		$paid_pi_todate = 0;
		$pre_paid_todate = 0;
		$loan_res = mysql_query("select d.id as id, applic.id as applic_id, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year from disbursed d join loan_applic applic on d.applic_id=applic.id where d.cycle= '".$row['cycle']."' and d.date <= '".$date."'");
		while($loan = mysql_fetch_array($loan_res)){
			//Period ellapsed
			$last_res = mysql_query("select DATE_FORMAT(date, '%d') as last_mday, DATE_FORMAT(date, '%m') as last_month, DATE_FORMAT(date, '%Y') as last_year  from payment where date <= '".$date."' and loan_id='".$loan['id']."' order by date desc");
				
			if(mysql_numrows($last_res) ==0)
				$last_res = mysql_query("select DATE_FORMAT(date, '%d') as last_mday, DATE_FORMAT(date, '%m') as last_month, DATE_FORMAT(date, '%Y') as last_year from schedule where date <= '".$date."' and loan_id='".$loan['id']."' order by date asc");
			$last = mysql_fetch_array($last_res);
			
		//CALCULATE DUE PRINCIPAL
		$sched_res = mysql_query("select sum(princ_amt) as amount from schedule where loan_id='".$loan['id']."' and date <= '".$date."'");
		$sched = @mysql_fetch_array($sched_res);
		$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
		//PAID INTEREST
		$paidint_res = mysql_query("select sum(int_amt) as int_amt, sum(princ_amt) as princ_amt from payment where loan_id='".$loan['id']."' and date <= '".$date."'");
		$paidint = mysql_fetch_array($paidint_res);
		$paidint_amt = ($paidint['int_amt'] == NULL) ? 0 : $paidint['int_amt'];
		$paid_princ_todate = ($paidint['princ_amt'] == NULL) ? 0 : $paidint['princ_amt'];
		$due_princ_amt = $sched_amt + $paidint_amt;  // - $paid_amt - $arrears_amt;	
		//PREPAYMENTS
		if($paid_princ_todate > $sched_amt)
			$pre_paid_todate += $paid_princ_todate - $sched_amt;
		
		if($due_princ_amt <= 0){
			$due_int_amt=0;
		}else{
			$due_princ = $due_princ_amt;

		//CALCULATE DUE INTEREST
		$int_days = $calc->dateToDays($mday, $month, $year) - $calc->dateToDays($last['last_mday'], 		$last['last_month'], $last['last_year']); 
			if($int_days >0){
				$no_months = floor($int_days /30);
				if($loan['int_method'] =='Declining Balance'){
					$due_int_amt = ceil((($balance * $loan['int_rate']/100) / 12) * $no_months);
					$due_int = $due_int_amt;
				}elseif($row['int_method'] == 'Flat'){
					$due_int_amt = ceil((($loan['amount'] * $loan['int_rate']/100) / 12) * $no_months);
					$due_int = $due_int_amt;
				}
			}else
				$due_int="--"; 
		}
		$total_amt_due = $total_amt_due + $due_princ_amt + $due_int_amt ;
	} 
		
	//PAID P & I TO DATE
		$paid_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount, sum(p.princ_amt) as princ_amt from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where d.cycle='".$row['cycle']."' and p.date <='".$date."'");

		$paid = mysql_fetch_array($paid_res);
		$paid_pi_todate = ($paid['amount'] == NULL) ? 0 : $paid['amount'];
		$paid_princ_todate = ($paid['princ_amt'] == NULL) ? 0 : $paid['princ_amt'];

	//PREPAYMENTS
		
		//$pre_paid_todate = $paid_princ_todate - $sched_princ_todate;
		//$pre_paid_todate = ($pre_paid_todate < 0) ? 0 : $pre_paid_todate;
		if($row['cycle'] == 1) {
			$text = "First Loans";
		}
		if($row['cycle'] == 2) {
			$text = "Second Loans";
		}
		if($row['cycle'] == 3) {
			$text = "Third Loans";
		}
		if($row['cycle'] == 4) {
			$text = "Fourth Loans";
		}
		if($row['cycle'] == 5) {
			$text = "Fifth Loans";
		}
		if($row['cycle'] >= 6) {
			$total_amt_due6 += $total_amt_due;
				$paid_pi_todate6 += $paid_pi_todate;
				$pre_paid_todate6 += $pre_paid_todate;
				$total_amt_arrears6 += $total_amt_arrears;
			$test_res = mysql_query("select * from disbursed where cycle >=".$row['cycle']." and date <= '".$date."'");
			if(mysql_numrows($test_res) == 0){
				$text = "Sixth Loans And Above";
				$total_amt_due = $total_amt_due6;
				$paid_pi_todate = $paid_pi_todate6;
				$pre_paid_todate = $pre_paid_todate6;
				$total_amt_arrears = $total_amt_arrears6;
			}else
				continue;
		}
		//ARREARS
		$total_amt_arrears =$total_amt_due - $paid_pi_todate + $pre_paid_todate;

		*/
		
	$sched_res = mysql_query("select sum(s.princ_amt) as princ_amt, sum(s.int_amt) as int_amt, d.cycle from schedule s join disbursed d on s.loan_id=d.id where s.date <= '".$date."' group by d.cycle order by d.cycle asc");
	while($tot = mysql_fetch_array($sched_res)){
		$paid_res = mysql_query("select sum(p.princ_amt) as princ_amt, sum(p.int_amt) as int_amt, d.cycle from payment p join disbursed d on p.loan_id=d.id where p.date <= '".$date."' and d.cycle='".$tot['cycle']."' group by d.cycle");
		$paid = mysql_fetch_array($paid_res);
		$paid_amt = ($paid['princ_amt'] == NULL) ? 0 : $paid['princ_amt'];
		$paid_int = ($paid['int_amt'] == NULL) ? 0 : $paid['int_amt'];

		$sched_amt = ($tot['princ_amt'] == NULL) ? 0 : $tot['princ_amt'];
		$sched_int = ($tot['int_amt'] == NULL) ? 0 : $tot['int_amt'];
		$princ_out = $sched_amt - $paid_amt;
		$int_out = $sched_int - $paid_int;
		
		if($tot['cycle'] == 1) {
			$text = "First Loans";
		}
		if($tot['cycle'] == 2) {
			$text = "Second Loans";
		}
		if($tot['cycle'] == 3) {
			$text = "Third Loans";
		}
		if($tot['cycle'] == 4) {
			$text = "Fourth Loans";
		}
		if($tot['cycle'] == 5) {
			$text = "Fifth Loans";
		}
		if($tot['cycle'] >= 6) {
			$int6_amt += $int_out;
			$princ6_amt += $princ_out;
			$paid6_amt += $paid_amt;
			$paid6_int += $paid_int;
			$test_res = mysql_query("select * from disbursed where cycle >'".$tot['cycle']."' and date <= '".$date."'");
			if(mysql_numrows($test_res) == 0){
				$text = "Sixth Loans And Above";
				$int_out = $int6_amt;
				$princ_out = $princ6_amt;
				$paid_amt = $paid6_amt;
				$paid_int = $paid6_int;
				$where_cycle = " d.cycle >= '6'";
			}else
				continue;
			
		}else
			$where_cycle = "d.cycle='".$tot['cycle']."' group by d.cycle";
		//ARREARS
		$check = mysql_fetch_array(mysql_query("select sum(s.princ_amt + s.int_amt) as amount, sum(s.int_amt) as int_amt from schedule s join disbursed d on s.loan_id=d.id where s.date < '".$date."' and ".$where_cycle.""));
	
		$check_amt = ($check['amount'] == NULL) ? 0 : $check['amount'];
		
		$total_amt_arrears = $check_amt - $paid_amt - $paid_int;
		$total_amt_arrears = ($total_amt_arrears <0) ? 0  : $total_amt_arrears;

		$total_amt_due = $princ_out + $int_out;
		$paid_pi_todate = $paid_amt + $paid_int;
		$pre_paid_todate = $paid_pi_todate - $total_amt_due;
		
		$pre_paid_todate = ($pre_paid_todate <= 0) ? 0  : $pre_paid_todate;
		$x++;
		$color = ($x%2 == 0) ? "white" : "lightgrey";
		$content .= "<tr bgcolor=$color><td>".$x.". ".$text."</td><td>".number_format($total_amt_due, 2)."</td><td>".number_format($paid_pi_todate, 2)."</td><td>".number_format($pre_paid_todate, 2)."</td><td>".number_format($total_amt_arrears, 2)."</td></tr>";
		$sub_amt_due += $total_amt_due;
		$sub_paid_pi_todate += $paid_pi_todate;
		$sub_pre_paid_todate += $pre_paid_todate;
		$sub_amt_arrears += $total_amt_arrears;
	}

	//SUB TOTALS
	$x++;
	$color = ($x%2 == 0) ? "white" : "lightgrey";
	$content .= "<tr bgcolor=$color><td><b>Total</b></td><td><b>".number_format($sub_amt_due, 2)."</b></td><td><b>".number_format($sub_paid_pi_todate, 2)."</b></td><td><b>".number_format($sub_pre_paid_todate, 2)."</b></td><td><b>".number_format($sub_amt_arrears, 2)."</b></td></tr>";
	$content .= "</table>";

	export($format, $content);
	return 0;
}

portfolio_summary($_GET['year'], $_GET['month'], $_GET['mday'], $_GET['format']);
//reports_footer();

?>
