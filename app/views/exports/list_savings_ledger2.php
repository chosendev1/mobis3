<?php


if(!isset($_GET['format']))
echo("<head>
	<title>SAVINGS LEDGER</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");

//GENERATE SAVINGS LEDGER
function savings_ledger($mem_no, $mem_id, $save_acct, $from_year, $from_month, $to_year, $to_month, $format)
{

	$calc = new Date_Calc();
	if($save_acct =='' && $mem_no ==''){
		$content .="Please enter the Member No";
	}
	if($mem_no <>"" && $save_acct==''){
		$sth = mysql_query("select * from member where mem_no='".$mem_no."'");
		if(mysql_numrows($sth) ==0){
			$content .="The entered Member No does not exist!";
		}
		$row = mysql_fetch_array($sth);
		$acct_res = mysql_query("select mem.id as id from mem_accounts mem join savings_product s on mem.saveproduct_id=s.id where mem.mem_id='".$row['id']."' and s.type='free'");
		if(mysql_numrows($acct_res) > 1){
			$content .="Select which Savings account you want statement";
		}elseif(mysql_numrows($acct_res) ==1){
			$acct = mysql_fetch_array($acct_res);
			$save_acct = $acct['id'];
			$mem_id = $row['id'];
		}elseif(mysql_numrows($acct_res) ==0){
			$content .= "This Member hasnt a savings account!";
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

        $total_saved = isset($drow1[tot_savings])? intval($drow1[tot_savings]) : 0 ;
        $total_fees = isset($mrow1[tot_fees])? intval($mrow1[tot_fees]) : 0 ;
        $total_with = isset($wrow1[tot_with])? intval($wrow1[tot_with]) : 0 ;
        $total_int = isset($irow1[tot_int])? intval($irow1[tot_int]) : 0 ;
		$total_pay = isset($prow1[tot_int])? intval($prow1[tot_int]) : 0 ;
        $net_save = ($total_saved + $total_int) - ($total_fees + $total_with + $total_pay);
	$cumm_save += $net_save;
	$mem_row = @mysql_fetch_array(@mysql_query("select first_name, last_name, mem_no from member where id = $mem_id"));
	
	$branch = mysql_fetch_array(mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'"));
	$content = " <center><font color=#00008b size=5pt><B>".$branch['branch_name']."</B></font></center><p>
		   <center><font color=#00008b size=3pt><B>INTERIM SAVINGS STATEMENT</B></font><br><font color=#00008b size=2pt> Name:  <b>".strtoupper($mem_row[first_name])." ". strtoupper($mem_row[last_name])."</B><br>Member Number: <b>".$mem_row['mem_no']."</b> <br>Period:  <b>".$start_date  ."  -  ". $end_date."</b></font><br></p></center>
		   <table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='70%' id='AutoNumber2' align=center>
		    <tr class='headings'>
			<th>Date</th><th>Description</th><th>Debit</th><th>Credit</th><th>Account Balance</th>
		    </tr>
		    <tr bgcolor=lightgrey>
			<td>Before $start_date</td><td align=center>B/F</td><td align=center>--</td><td align=center>--</td><td align=center>$cumm_save</td>
		    </tr>
		    ";
	$acc_res = @mysql_query("select id, date, amount, transaction from deposit where bank_account != 0 and memaccount_id = $save_acct and date > '".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction from withdrawal where memaccount_id = $save_acct and date > '".$start_date."' and date <= '".$end_date."' UNION select id, date, amount, transaction from monthly_charge where memaccount_id = $save_acct and date >'".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction from save_interest where memaccount_id = $save_acct and date > '".$start_date."' and date <='".$end_date."' UNION select id, date, princ_amt + int_amt as amount, transaction from payment where mode= '".$save_acct."' and date > '".$start_date."' and date <='".$end_date."' order by date asc");
	$x = 0;
	while ($acc_row = @mysql_fetch_array($acc_res))
	{
		$charge_amt = 0;
		$tot_savings = strtolower($acc_row['transaction']) == 'deposit' ? intval($acc_row[amount]) : 0 ;
		$tot_fees = strtolower($acc_row['transaction']) == 'monthly_charge' ? intval($acc_row[amount]) : 0 ;
		$tot_with = strtolower($acc_row['transaction']) == 'withdrawal' ? intval($acc_row[amount]) : 0 ;
		$tot_int = strtolower($acc_row['transaction']) == 'save_interest' ? intval($acc_row[amount]) : 0 ;
		$tot_pay = strtolower($acc_row['transaction']) == 'payment' ? intval($acc_row[amount]) : 0 ;
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
			//$resp->AddAlert($tot_pay);
		}
		//$tot_fees = $tot_fees + $charge_amt;
		//$net_save = ($tot_savings + $tot_int) - ($tot_fees + $charge_amt + $tot_with);
		//$cumm_save += $net_save;
		if($tot_savings >0){
			$cumm_save += $tot_savings;
			$x++;
			$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr bgcolor=$color>
			<td>$acc_row[date]</td><td align=center>$descr</td><td align=center>--</td><td align=center>".number_format($tot_savings, 2)."</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($tot_int >0){
			$cumm_save += $tot_int;
			$x++;
			$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr bgcolor=$color>
			<td>$acc_row[date]</td><td align=center>Interest Earned</td><td align=center>--</td><td align=center>$tot_int</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($tot_with >0){
			$cumm_save -= $tot_with;
			$x++;
			$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr bgcolor=$color>
			<td>$acc_row[date]</td><td align=center>$descr</td><td align=center>".number_format($tot_with, 2)."</td><td align=center>--</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($tot_pay >0){
			$cumm_save -= $tot_pay;
			$x++;
			
			$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr bgcolor=$color>
			<td>$acc_row[date]</td><td align=center>$descr</td><td align=center>".number_format($tot_pay, 2)."</td><td align=center>--</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($charge_amt >0){
			$x++;
			$cumm_save -= $charge_amt;
			$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr bgcolor=$color>
			<td>$acc_row[date]</td><td align=center>Transactional Charge</td><td align=center>$charge_amt</td><td align=center>--</td><td align=center>$cumm_save</td>
		    </tr>
		    ";
		}
		if($tot_fees >0){
			$x++;
			$cumm_save -= $tot_fees;
			$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr bgcolor=$color>
			<td>$acc_row[date]</td><td align=center>Monthly Charge</td><td align=center>$tot_fees</td><td align=center>--</td><td align=center>$cumm_save</td>
		    </tr>
		    ";
		}
	} 	 	
	$content .= "</table>";
	
	export($format, $content);
	
}

//reports_header();
savings_ledger($_GET['mem_no'], $_GET['mem_id'], $_GET['save_acct'], $_GET['from_year'], $_GET['from_month'], $_GET['to_year'], $_GET['to_month'], $_GET['format']);
//reports_footer();

?>
