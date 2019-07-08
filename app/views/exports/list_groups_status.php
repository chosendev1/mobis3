<?php

if(!isset($_GET['format']))
echo("<head>
	<title>SAVINGS PRODUCT ACCOUNTS FOR MEMBERS</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");

//GROUPS STATUS REPORT
function groups_status($group_name, $year, $month, $mday,$branch_id, $format){
	
	$calc = new Date_Calc();
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:'and branch_id='.$branch_id;
	$date = sprintf("%d-%02d-%02d 23:59:59", $year, $month, $mday);
	$arrears_date = sprintf("%d-%02d-%02d", $year, $month, $mday);
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$content = "<center><p><font color=#00008b size=4pt><b>".$branch['branch_name']."</b></font></p></center>";
	$content .= "<center><font color=#00008b size=3pt><B>GROUPS STATUS REPORT AS OF ".$date." </B></font></center>";
	
	if($year ==''){
		$content .= "<table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center><tr><td><font color=red>Select the period for which you want the report</font></td></tr></table>";
		
	}
	
	$group_res = mysql_query("select * from loan_group where name like '%".$group_name."%' ".$branch_);
	if(mysql_numrows($group_res) == 0){
		$content .= "<table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center><tr><td><font color=red>No group found in your search options</font></td></tr></table>";
		
	}
	$content .= "<table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center><tr class='headings'><th rowspan=2>Name</th><th rowspan=2>MemberNo</th><th rowspan=2>Savings</th><th colspan=3>Loan Details</th><th colspan=3>Paid To Date</th><th colspan=3>Arrears To Date</th><th colspan=3>Due To Date</th></tr>
	<tr class='headings'><th>Loan Amt</th><th>Loan Balance</th><th>Pre-payment</th><th>Principal</th><th>Interest</th><th>Total</th><th>Principal</th><th>Interest</th><th>Total</th><th>Principal</th><th>Interest</th><th>Total Due</th></tr>";

	while($group = mysql_fetch_array($group_res)){
		$content .= "<tr class='headings'><th colspan=1 align=left>".strtoupper($group['name'])."</th></tr>";
		$mem_res = mysql_query("select m.id as mem_id, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name from group_member g join member m on g.mem_id=m.id where g.group_id=".$group['id']."");
		$size = mysql_fetch_array(mysql_query("select count(id) as no from group_member where group_id='".$group['id']."'"));
		$size = $size['no'];
		$x=1;
		
		$sub_savings_balance =0;
		$sub_loan_amt = 0;
		$sub_loan_balance =0;
		$sub_pre_paid_todate = 0;
		$sub_paid_princ_todate = 0;
		$paid_int_todate = 0;
		$sub_total_paid_todate = 0;
		$sub_arrears_princ = 0;
		$arrears_int = 0;
		$sub_total_arrears = 0;
		$sub_princ_due_todate = 0;
		$sub_int_due_todate = 0;
		$sub_total_due_todate = 0;
		while($mem = mysql_fetch_array($mem_res)){
			//SAVINGS
			$dep_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id where mem.mem_id=".$mem['mem_id']." and  d.bank_account <>0 and d.date <= '".$date."'");
			$dep = mysql_fetch_array($dep_res);
			$dep_amt = ($dep['amount'] == NULL) ? 0 : $dep['amount'];

			$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts mem on i.memaccount_id=mem.id where m.mem_id=".$mem['mem_id']."  and i.date <= '".$date."'");
			$int = mysql_query($int_res);
			$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];

			$with_res = mysql_query("select sum(w.amount) as amount from withdrawal w join mem_accounts mem on w.memaccount_id=mem.id where m.mem_id=".$mem['mem_id']." and  w.bank_account <>0 and w.date <= '".$date."'");
			$with = mysql_query($with_res);
			$with_amt = ($with['amount'] == NULL) ? 0 : $with['amount'];

			$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts mem on c.memaccount_id=mem.id where m.mem_id=".$mem['mem_id']."  and c.date <= '".$date."'");
			$charge = mysql_query($charge_res);
			$charge_amt = ($charge['amount'] == NULL) ? 0 : $charge['amount'];

			//LOAN REPAYMENTS
			$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id where m.mem_id=".$mem['mem_id']."  and p.date <= '".$date."'");
			$pay = mysql_fetch_array($pay_res);
			$pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;
			//INCOME DEDUCTIONS
			$inc_res = mysql_query("select sum(i.amount) as amount from other_income i join mem_accounts m on i.mode=m.id where m.mem_id=".$mem['mem_id']."  and i.date <= '".$date."'");
			$inc = mysql_fetch_array($inc_res);
			$inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;
			//TRANSFER TO SHARES
			$shares_res = mysql_query("select sum(s.value) as amount from shares s join mem_accounts m on s.mode=m.id where m.mem_id=".$mem['mem_id']."  and s.date <= '".$date."'");
			$shares = mysql_fetch_array($shares_res);
			$shares_amt = ($shares['amount'] != NULL) ? $shares['amount'] : 0;


			$savings_balance = $dep_amt +  $int_amt - $charge_amt - $with_amt - $shares_amt - $inc_amt - $pay_amt;

			$mem_id = $mem['mem_id'];
			//LOANS
			//DISBURSEMENT
			$disb_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id = applic.id where applic.mem_id=".$mem_id." and d.date <= '".$date ."' and applic.group_id='".$group['id']."'");
			$disb = mysql_fetch_array($disb_res);
			$loan_amt = ($disb['amount'] == NULL) ? 0 : $disb['amount'];	

			//EXPECTED
			$sched_res = mysql_query("select sum(s.int_amt) as int_amt, sum(s.princ_amt) as amount from schedule s join disbursed d on s.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$mem_id."' and s.date <= '".$date."' and applic.group_id='".$group['id']."'");
			$sched = @mysql_fetch_array($sched_res);
			$sched_princ = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
			$sched_int = ($sched['int_amt'] == NULL) ? 0 : $sched['int_amt'];
			$total_sched = $sched_princ + $sched_int;
			
			//PAID TO DATE
			$paidint_res = mysql_query("select sum(p.int_amt) as int_amt, sum(p.princ_amt) as princ_amt from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$mem_id."' and p.date <= '".$date."' and applic.group_id='".$group['id']."'");
			$paidint = mysql_fetch_array($paidint_res);
			$paid_int_todate = ($paidint['int_amt'] == NULL) ? 0 : $paidint['int_amt'];
			$paid_princ_todate = ($paidint['princ_amt'] == NULL) ? 0 : $paidint['princ_amt'];
			$total_paid_todate = $paid_princ_todate + $paid_int_todate; 

			//PAID TO PREVIOUS DATE (ARREARS CALCULATION)
			$prevint_res = mysql_query("select sum(s.int_amt) as int_amt, sum(s.princ_amt) as princ_amt from schedule s join disbursed d on s.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$mem_id."' and s.date < '".$arrears_date."' and applic.group_id='".$group['id']."'");
			$prevint = mysql_fetch_array($prevint_res);
			$prev_int_todate = ($prevint['int_amt'] == NULL) ? 0 : $prevint['int_amt'];
			$prev_princ_todate = ($prevint['princ_amt'] == NULL) ? 0 : $prevint['princ_amt'];
			
			//PREPAYMENTS
			$pre_paid_todate = ($total_paid_todate > $total_sched) ? ($total_paid_todate - $total_sched) : 0;

			//ARREARS
			if($total_paid_todate > $total_sched){
				$arrears_princ = 0;
				$arrears_int = 0;
				$total_arrears = 0;
				$princ_due_todate =0;
				$int_due_todate =0;
				$total_due_todate =0;
			}else{
				$princ_due_todate = $sched_princ - $paid_princ_todate;
				$int_due_todate = $sched_int - $paid_int_todate;
				$total_due_todate = $princ_due_todate + $int_due_todate;
				$arrears_princ = $prev_princ_todate - $paid_princ_todate;
				$arrears_int = $prev_int_todate - $paid_int_todate;
				$total_arrears = $arrears_princ + $arrears_int;
			}
			//LOAN BALANCE
			$loan_balance = $loan_amt - $paid_princ_todate;

			$content .= "<tr><td>".$x.". ".$mem['first_name']." ".$mem['last_name']."</td><td>".$mem['mem_no']."</td><td>".number_format($savings_balance, 2)."</td><td>".number_format($loan_amt, 2)."</td><td>".number_format($loan_balance, 2)."</td><td>".number_format($pre_paid_todate, 2)."</td><td>".number_format($paid_princ_todate, 2)."</td><td>".number_format($paid_int_todate, 2)."</td><td>".number_format($total_paid_todate, 2)."</td><td>".number_format($arrears_princ, 2)."</td><td>".number_format($arrears_int, 2)."</td><td>".number_format($total_arrears, 2)."</td><td>".number_format($princ_due_todate, 2)."</td><td>".number_format($int_due_todate, 2)."</td><td>".number_format($total_due_todate, 2)."</td></tr>";

			$sub_savings_balance +=$savings_balance;
			$sub_loan_amt += $loan_amt;
			$sub_loan_balance += $loan_balance;
			$sub_pre_paid_todate += $pre_paid_todate;
			$sub_paid_princ_todate += $paid_princ_todate;
			$paid_int_todate += $paid_int_todate;
			$sub_total_paid_todate += $total_paid_todate;
			$sub_arrears_princ += $arrears_princ;
			$arrears_int += $arrears_int;
			$sub_total_arrears += $total_arrears;
			$sub_princ_due_todate += $princ_due_todate;
			$sub_int_due_todate += $int_due_todate;
			$sub_total_due_todate += $total_due_todate;
			if($x == $size){
				$content .= "<tr class='headings'><td colspan=2>GROUP TOTAL</td><td>".number_format($sub_savings_balance, 2)."</td><td>".number_format($sub_loan_amt, 2)."</td><td>".number_format($sub_loan_balance, 2)."</td><td>".number_format($sub_pre_paid_todate, 2)."</td><td>".number_format($sub_paid_princ_todate, 2)."</td><td>".number_format($sub_paid_int_todate, 2)."</td><td>".number_format($sub_total_paid_todate, 2)."</td><td>".number_format($sub_arrears_princ, 2)."</td><td>".number_format($sub_arrears_int, 2)."</td><td>".number_format($sub_total_arrears, 2)."</td><td>".number_format($sub_princ_due_todate, 2)."</td><td>".number_format($sub_int_due_todate, 2)."</td><td>".number_format($sub_total_due_todate, 2)."</td></tr>";
				
			}
			$x++;
		}
	}
	$content .="</table>";
	export($format, $content);
}

groups_status($_GET['group_name'], $_GET['year'], $_GET['month'], $_GET['mday'], $_GET['branch_id'], $_GET['format']);


?>
