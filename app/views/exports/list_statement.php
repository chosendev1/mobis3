<?php

if(!isset($_GET['format']))
	echo("<head>
	<title>BANK STATEMENT</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");
//GENERATE BANK STATEMENT
function getStatement($bank_acct_id, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday, $format)
{
	$acc_row = @mysql_fetch_array(@mysql_query("select name from accounts where id = (select account_id from bank_account where id = $bank_acct_id)"));
	$acc_name = $acc_row['name'];
	$start_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$end_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
	$query = "select date, sum(amount) as amount, transaction from collected where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, 'import' as transaction from cash_transfer where dest_id = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, 'export' as transaction from cash_transfer where source_id = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, transaction from deposit where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, transaction from disbursed where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, transaction from expense where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(initial_value) as amount, transaction from fixed_asset where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(quantity * amount) as amount, transaction from investments where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, transaction from other_income where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, transaction from payable_paid where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select p.date, sum(p.int_amt + p.princ_amt) as amount, p.transaction from payment p join disbursed d on p.loan_id=d.id where p.bank_account = $bank_acct_id and p.date < '".$start_date."' and p.mode in ('Cash', 'Cheque') group by p.date UNION select pen.date, sum(pen.amount) as amount, pen.transaction from penalty pen join disbursed d on pen.loan_id=d.id where pen.bank_account = $bank_acct_id and pen.date < '".$start_date."'  and pen.status <>'pending' group by pen.date UNION select r.date as date, sum(r.amount) as amount, r.transaction as transaction from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.bank_account = $bank_acct_id and r.date < '".$start_date."'  group by r.date UNION select date, sum(total_amount) as amount, transaction from share_dividends where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(value) as amount, transaction from shares where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, transaction from withdrawal where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, transaction from sold_asset where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, transaction from payable where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(quantity * amount) as amount, transaction from sold_invest where bank_account = $bank_acct_id and date < '".$start_date."' group by date order by date asc";	
	$bal_res = @mysql_query($query);
	if (@mysql_num_rows($bal_res) > 0)
	{
		$start_balance = 0;
		while ($bal_row = @mysql_fetch_array($bal_res))
		{
			if (strtolower($bal_row['transaction']) == 'collected' || strtolower($bal_row['transaction']) == 'import' || strtolower($bal_row['transaction']) == 'deposit' || strtolower($bal_row['transaction']) == 'sold_invest' || strtolower($bal_row['transaction']) == 'other_income' || strtolower($bal_row['transaction']) == 'payment' || strtolower($bal_row['transaction']) == 'penalty' || strtolower($bal_row['transaction']) == 'recovered' || strtolower($bal_row['transaction']) == 'shares' || strtolower($bal_row['transaction']) == 'sold_asset' || strtolower($bal_row['transaction']) == 'payable')
			{
				$amount = ($bal_row['amount'] == NULL) ? 0: $bal_row['amount'];
				$start_balance += $amount;
			}
			elseif (strtolower($bal_row['transaction']) == 'disbursed' || strtolower($bal_row['transaction']) == 'export' || strtolower($bal_row['transaction']) == 'expense' || strtolower($bal_row['transaction']) == 'investments' || strtolower($bal_row['transaction']) == 'payable_paid' || strtolower($bal_row['transaction']) == 'share_dividends' || strtolower($bal_row['transaction']) == 'withdrawal' || strtolower($bal_row['transaction']) == 'fixed_asset')
			{
				$start_balance -= $bal_row['amount'];
			}
		}
	}
	else
	{
		$start_balance = 0;
	}

	$query2 = "select id, date, amount as amount, transaction, receipt_no as ref from collected where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, amount as amount, 'import' as transaction, 'Cash Imported' as ref from cash_transfer where dest_id = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, amount as amount, 'export' as transaction, 'Cash Exported' as ref from cash_transfer where source_id = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, amount as amount, transaction, receipt_no as ref from deposit where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."' UNION select id, date, amount as amount, transaction, cheque_no as ref from disbursed where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, amount as amount, transaction, voucher_no as ref from expense where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, initial_value as amount, transaction, 'Fixed Asset Acquired' as ref from fixed_asset where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, (quantity * amount) as amount, transaction, voucher_no as ref from investments where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, amount as amount, transaction, receipt_no as ref from other_income where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION  select id, date, amount as amount, transaction, voucher_no as ref from payable_paid where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."' UNION select p.id, p.date, (p.int_amt + p.princ_amt) as amount, p.transaction, receipt_no as ref from payment p join disbursed d on p.loan_id=d.id where p.bank_account = $bank_acct_id and p.date >= '".$start_date."' and p.date <= '".$end_date."' and p.mode in ('Cash', 'Cheque') UNION select pen.id, pen.date as date, pen.amount as amount, pen.transaction, 'Penalty' as ref from penalty pen join disbursed d on pen.loan_id=d.id where pen.bank_account = $bank_acct_id and pen.date >= '".$start_date."' and pen.date <= '".$end_date."' and pen.status <>'pending'  UNION select r.id, r.date as date, r.amount as amount, r.transaction as transaction, receipt_no as ref from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.bank_account = $bank_acct_id and r.date >= '".$start_date."' and r.date <= '".$end_date."'  UNION select id, date, total_amount as amount, transaction, 'Dividends' as ref from share_dividends where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, value as amount, transaction, receipt_no as ref from shares where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, amount as amount, transaction, voucher_no as ref from withdrawal where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, amount as amount, transaction, receipt_no as ref from sold_asset where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, amount as amount, transaction, 'Loan Acquired' as ref from payable where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, (quantity * amount) as amount, transaction, receipt_no as ref from sold_invest where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."' order by date asc"; 
	
	$st_res = @mysql_query($query2);
	if (@mysql_num_rows($st_res) > 0)
	{
		$name = mysql_fetch_array(mysql_query("select * from branch"));
		$content .= "<center><font color=#00008b size=3pt><b>".$name['branch_name']."<br>BANK STATEMENT FOR ".strtoupper($acc_name)." FROM $start_date TO $end_date</b></font></center><br>";
		$content .= "<table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>";
		$content .= "<tr class='headings'><th>Date</th><th>Transaction</th><th>Reference</th><th>Debit</th><th>Credit</th><th>Balance</th></tr>";
		$content .= "<tr bgcolor=lightgrey><td>$start_date</td><td> Balance B/F</td><th>Balance B/F</th><td>--</td><td>--</td><td>".number_format($start_balance, 2)."</td></tr>";
		$cur_bal = $start_balance;
		$i=1;
		while ($st_row = @mysql_fetch_array($st_res))
		{
			if (strtolower($st_row['transaction']) == 'collected' || strtolower($st_row['transaction']) == 'import' || strtolower($st_row['transaction']) == 'import' || strtolower($st_row['transaction']) == 'deposit' || strtolower($st_row['transaction']) == 'sold_invest' || strtolower($st_row['transaction']) == 'other_income' || strtolower($st_row['transaction']) == 'payment' || strtolower($st_row['transaction']) == 'penalty' || strtolower($st_row['transaction']) == 'recovered' || strtolower($st_row['transaction']) == 'shares' || strtolower($st_row['transaction']) == 'sold_asset' || strtolower($st_row['transaction']) == 'payable')
			{
				$cur_bal += $st_row['amount'];
				$trans = ucfirst($st_row['transaction']);
				if($trans == 'Disbursed')
					$trans = "Disbursement";
				elseif($trans == 'Payment')
					$trans = "Re-payment";
				elseif($trans == 'Other_income')
					$trans = 'Other Income';
				elseif($trans == 'Import')
					$trans= "Cash Imported";
				
				
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td>$st_row[date]</td><td>$trans</td><td>RECEIPT NO: ".$st_row['ref']."</td><td>--</td><td>".number_format($st_row[amount], 2)."</td><td>".number_format($cur_bal, 2)."</td></tr>";
				$i++;
			}
			elseif (strtolower($st_row['transaction']) == 'disbursed' || strtolower($st_row['transaction']) == 'export' || strtolower($st_row['transaction']) == 'expense' || strtolower($st_row['transaction']) == 'fixed_asset' || strtolower($st_row['transaction']) == 'payable_paid' || strtolower($st_row['transaction']) == 'share_dividends' || strtolower($st_row['transaction']) == 'investments' || strtolower($st_row['transaction']) == 'withdrawal') 
			{
				$cur_bal -= $st_row['amount'];
				$trans = ucfirst($st_row['transaction']);
				if($trans == 'Payable_paid')
					$trans = 'Payable Paid';
				elseif($trans == 'Export')
					$trans= "Cash Exported";
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td>$st_row[date]</td><td>$trans</td><td>PV/CHEQUE NO: ".$st_row['ref']."</td><td>".number_format($st_row[amount], 2)."</td><td>--</td><td>".number_format($cur_bal, 2)."</td></tr>";
				$i++;
			}
		}
		
	}
	else
	{
		$content .= "<font color='red'>There are no transaction yet for this period.".mysql_error()."</font><br>";
	}
	$content .="</table></td></tr></table>";
	export($format, $content);
}
//reports_header();
getStatement($_GET['bank_acct_id'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday'], $_GET['format']);
//reports_footer();
?>
