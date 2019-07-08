<?php

if(!isset($_GET['format']))
echo("<head>
	<title>CASH FLOW STATEMENT</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");



//CASH FLOW STATEMENT
function cash_flow($from_year, $from_month, $from_mday, $year, $month, $mday, $format,$branch_id){
	$calc = new Date_Calc();
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:"and branch_id=".$branch_id;
	if($year == ''){
		$year = date('Y');
		$month = date('m');
		$mday = date('d');
	}
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $year, $month, $mday);
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	//$from_date = $calc->daysToDate($calc->dateToDays($mday, $month, $year)-365, '%Y-%m-%d');
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$content = "<center><font color=#00008b size=5pt><b>".$branch['branch_name']."</b></font></center>
	<center><font color=#00008b size=4pt><b>CASH FLOW STATEMENT FOR THE PERIOD FROM ".$from_date." TO ".$to_date." </b></font>
		<table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='70%' id='AutoNumber2' align=center>
	<tr bgcolor=lightgrey><td colspan=2 align=left><font size=3pt><b>CASH FLOW FROM OPERATING ACTIVITIES</b></font></td><td><font size=5pt><b></b></font></td></tr>
	<tr bgcolor=white><td colspan=2 align=left><font size=2pt><b>Inflows (Cash Increases)</b></font></td><td><font size=2pt><b></b></font></td></tr>";
	
	
//LOANS COLLECTED (ISSUED BY THE SACCO)
	$collected_res = mysql_query("select sum(amount) as amount from collected where date >= '".$from_date."' and date <='".$to_date."'");
	$collected = mysql_fetch_array($collected_res);
	$collected_amt = ($collected['amount'] != NULL) ? $collected['amount'] : 0;

	$pay_res = mysql_query("select sum(princ_amt) as princ_amt, sum(int_amt) as int_amt from payment where date >= '".$from_date."' and date <= '".$to_date."' and bank_account>'0'");
	$pay = mysql_fetch_array($pay_res);
	$princ_amt = ($pay['princ_amt'] != NULL) ? $pay['princ_amt'] : 0;
	$int_amt = ($pay['int_amt'] != NULL) ? $pay['int_amt'] : 0;
	$recovered_res = mysql_query("select sum(amount) as amount from recovered where date >='".$from_date."' and date <='".$to_date."'");
	$recovered = mysql_fetch_array($recovered_res);
	$recovered_amt = ($recovered['amount'] != NULL) ? $recovered['amount'] : 0;
	$loans_collected = $princ_amt;     // + $recovered_amt;

//COMMISSION
	$pen_res = mysql_query("select sum(amount) as amount from penalty where status='paid' and date >='".$from_date."' and date <='".$to_date."'");
	$pen = mysql_fetch_array($pen_res);
	$pen_amt = ($pen['amount'] != NULL) ? $pen['amount'] : 0;
	$commission = $pen_amt + $int_amt;

	$deposit_res = mysql_query("select sum(amount) as amount from deposit where date >='".$from_date."' and date <='".$to_date."' and bank_account>'0'");
	$deposit = mysql_fetch_array($deposit_res);
	$deposit_amt = ($deposit['amount'] != NULL) ? $deposit['amount'] : 0;
//SHORT-TERM LOANS PAYABLE
	$short_res = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '2121%' and date >='".$from_date."' and date <='".$to_date."' and p.bank_account >'0'");
	$short = mysql_fetch_array($short_res);
	$short_amt = ($short['amount'] != NULL) ? $short['amount'] : 0;
	$short_credit = $deposit_amt + $short_amt;

//OTHER INCOME
	$inc = mysql_fetch_array(mysql_query("select sum(amount) as amount from other_income where bank_account > 0 and date >='".$from_date."' and date <= '".$to_date."'"));
	$inc_amt = ($inc['amount'] == NULL) ? 0 : $inc['amount'];

	$inflow = $short_credit + $commission + $loans_collected + $collected_amt + $inc_amt + $recovered_amt;
	$content .="<tr bgcolor=lightgrey><td width=10%></td><td><font size=2pt>Collection of Accounts or Documents Receivable</font></td><td><font size=2pt>".number_format($collected_amt, 2)."</font></td></td></tr>
		<tr bgcolor=white><td width=10%></td><td><font size=2pt>Collection of Loans</font></td><td><font size=2pt>".number_format($loans_collected, 2)."</font></td></td></tr>
		<tr bgcolor=lightgrey><td width=10%></td><td><font size=2pt>Short Term External Credit or Savings Deposits Received</font></td><td><font size=2pt>".number_format($short_credit, 2)."</font></td></td></tr>
		<tr bgcolor=white><td width=10%></td><td><font size=2pt>Interest & Penalties</font></td><td><font size=2pt>".number_format($commission, 2)."</font></td></td></tr>
		<tr bgcolor=lightgrey><td width=10%></td><td><font size=2pt>Other Income</font></td><td><font size=2pt>".number_format(($inc_amt+ $recovered_amt), 2)."</font></td></td></tr>
		<tr bgcolor=white><td width=10%></td><td><font size=2pt><b>Total Cash Inflows</b></font></td><td><font size=2pt><b>".number_format($inflow, 2)."</b></font></td></td></tr>";

//OUTFLOW OF CASH
	$content .="<tr bgcolor=lightgrey><td colspan=2 align=left><font size=2pt><b>Outflows (Cash Decrease)</b></font></td><td><font size=5pt><b></b></font></td></tr>";
//DISBURSEMENTS
	$loan_res = mysql_query("select sum(amount) as amount from disbursed where date >='".$from_date."' and date <='".$to_date."' and bank_account>'0'");
	$loan = mysql_fetch_array($loan_res);
	$loan_amt = ($loan['amount'] != NULL) ? $loan['amount'] : 0;
//WITHDRAWALS
	$with_res = mysql_query("select sum(amount) as amount from withdrawal where date >='".$from_date."' and date <='".$to_date."'");
	$with= mysql_fetch_array($with_res);
	$with_amt = ($with['amount'] != NULL) ? $with['amount'] : 0;
//PAYMENT FROM SAVINGS
	//$paid = mysql_fetch_array(mysql_query("select sum(princ_amt + int_amt) as amount from payment where date >='".$from_date."' and date <='".$to_date."' and mode>0"));
	//$paid_amt = ($paid['amount'] == NULL) ? 0 : $paid['amount'];
	$disburse_with = $loan_amt + $with_amt;  // + $paid_amt;

//PAYMENTS OF SHORT TERM LOANS AND ACCOUNTS PAYABLE
	$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id join accounts a on p.account_id=a.id where a.account_no not like '2122%' and paid.date >='".$from_date."' and paid.date <='".$to_date."'");
	$paid = mysql_fetch_array($paid_res);
	$paid_amt = ($paid['amount'] != NULL) ? $paid['amount'] : 0;

//PAYMENT TO EMPLOYEES
	$emp_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where a.account_no like '531%' and e.date >='".$from_date."' and e.date <= '".$to_date."'");
	$emp = mysql_fetch_array($emp_res);
	$emp_amt = ($emp['amount'] != NULL) ? $emp['amount'] : 0;
//ACCRUED EXPENSES
//	$accrued_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id join accounts a on p.account_id=a.id where paid.date >='".$from_date."' and paid.date <= '".$to_date."'");
//	$accrued = mysql_fetch_array($accrued_res);
//	$accrued_amt = ($accrued['amount'] != NULL) ? $accrued['amount'] : 0;

//BANK CHARGES, COMMISSIONS AND INTEREST
//	$charge_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where a.account_no like '515%' and e.date >='".$from_date."' and e.date <= '".$to_date."'");
//	$charge = mysql_fetch_array($charge_res);
//	$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
//
//INSURANCE
//	$ins_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where a.account_no like '522%' and e.date >='".$from_date."' and e.date <= '".$to_date."'");
//	$ins = mysql_fetch_array($ins_res);
//	$ins_amt = ($ins['amount'] != NULL) ? $ins['amount'] : 0;
//MARKETING
	$market_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where a.account_no like '533%' and e.date >='".$from_date."' and e.date <= '".$to_date."'");
	$market = mysql_fetch_array($market_res);
	$market_amt = ($market['amount'] != NULL) ? $market['amount'] : 0;
//GOVERNANCE
	$govern_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where a.account_no like '532%' and e.date >='".$from_date."' and e.date <= '".$to_date."'");
	$govern = mysql_fetch_array($govern_res);
	$govern_amt = ($govern['amount'] != NULL) ? $govern['amount'] : 0;
//ADMINISTRATIVE
	$admin_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where a.account_no like '535%' and e.date >='".$from_date."' and e.date <= '".$to_date."'");
	$admin = mysql_fetch_array($admin_res);
	$admin_amt = ($admin['amount'] != NULL) ? $admin['amount'] : 0;
//OTHER EXPENSES
	//$other_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where (a.account_no like '521%' or a.account_no like '551%' or a.account_no like '513%') and e.date >='".$from_date."' and e.date <= '".$to_date."'");
	$other_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where (a.account_no not like '535%' and a.account_no not like '532%' and a.account_no not like '533%' and a.account_no not like '531%') and e.date >='".$from_date."' and e.date <= '".$to_date."'");
	$other = mysql_fetch_array($other_res);
	$otherexp_amt = ($other['amount'] != NULL) ? $other['amount'] : 0;

	$outflow = $otherexp_amt + $admin_amt + $market_amt + $govern_amt + $ins_amt + $disburse_with + $paid_amt  + $emp_amt;
	$content .="<tr bgcolor=lightgrey><td width=10%></td><td><font size=2pt>Disbursements & Savings Withdrawals</font></td><td><font size=2pt>".number_format($disburse_with, 2)."</font></td></td></tr>
	<tr bgcolor=white><td width=10%></td><td><font size=2pt>Repayment of Short Term Ext. Credit & Accounts Payable</font></td><td><font size=2pt>".number_format($paid_amt, 2)."</font></td></td></tr>
	<tr bgcolor=lightgrey><td width=10%></td><td><font size=2pt>Payments on Personnel Expenses (Salaries, Benefits, Training, etc)</font></td><td><font size=2pt>".number_format($emp_amt, 2)."</font></td></td></tr>
	<tr bgcolor=lightgrey><td width=10%></td><td><font size=2pt>Payment on Marketing Expenses</font></td><td><font size=2pt>".number_format($market_amt, 2)."</font></td></td></tr>
	<tr bgcolor=white><td width=10%></td><td><font size=2pt>Payment on Governance Expenses</font></td><td><font size=2pt>".number_format($govern_amt, 2)."</font></td></td></tr>
	<tr bgcolor=lightgrey><td width=10%></td><td><font size=2pt>Payment on Administrative Expenses</font></td><td><font size=2pt>".number_format($admin_amt, 2)."</font></td></td></tr>
	<tr bgcolor=white><td width=10%></td><td><font size=2pt>Payment on Other Expenses and Services</font></td><td><font size=2pt>".number_format($otherexp_amt, 2)."</font></td></td></tr>
	<tr bgcolor=lightgrey><td width=10%></td><td><font size=2pt><b>Total Cash Outflows</b></font></td><td><font size=2pt><b>".number_format($outflow, 2)."</b></font></td></td></tr>";

	$operating_amt = $inflow - $outflow;
	$operating = ($operating_amt >= 0) ? $operating_amt : "(".(0-$operating_amt).")";
	$content .= "<tr bgcolor=white><td colspan=2 align=left><font size=2pt><b>NET INFLOW (OUTFLOW) OF CASH FROM OPERATING ACTIVITIES</b></font></td><td><font size=2pt><b>".number_format($operating, 2)."</b></font></td></td></tr>
	<tr bgcolor=lightgrey><td colspan=2 align=left><font size=2pt><b>CASH FLOW FROM INVESTMENT ACTIVITIES</b></font></td><td><font size=2pt><b></b></font></td></td></tr>
	<tr bgcolor=white><td colspan=2 align=left><font size=2pt><b>Inflows (Cash Increase)</b></font></td><td><font size=2pt><b></b></font></td></td></tr>";
//SALE OF FIXED ASSETS
	$sell_res = mysql_query("select sum(amount) as amount from sold_asset where date >='".$from_date."' and date<= '".$to_date."'");
	$sell = mysql_fetch_array($sell_res);
	$sell_asset = ($sell['amount'] != NULL) ? $sell['amount'] : 0;
//PURCHASE OF FIXED ASSETS
	$buy_res = mysql_query("select sum(initial_value) as amount from fixed_asset where date >='".$from_date."' and date <= '".$to_date."'");
	$buy = mysql_fetch_array($buy_res);
	$buy_asset= ($buy['amount'] != NULL) ? $buy['amount'] : 0;
//SALE OF INVESTMENTS
	$sell_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where date >='".$from_date."' and date <='".$to_date."'");
	$sell = mysql_fetch_array($sell_res);
	//OTHER INCOME EG PHOTOCOPIER, EXCEPT DONATIONS
	//$other_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.account_no not like '423%' and o.date >='".$from_date."' and o.date <= '".$to_date."'");  //LEAVE OUT DONATIONS
	//$other = mysql_fetch_array($other_res);
	//$other_amt = ($other['amount'] != NULL) ? $other['amount'] : 0;

	$sell_invest = ($sell['amount'] != NULL) ? $sell['amount'] : 0;
//PURCHASE OF INVESTMENTS
	$buy_res = mysql_query("select sum(quantity * amount) as amount from investments where date >='".$from_date."' and date <='".$to_date."'");
	$buy = mysql_fetch_array($buy_res);
	$buy_invest = ($buy['amount'] != NULL) ? $buy['amount'] : 0;

	//$invest_res = mysql_query("select sum(amount) as amount from investments where ")
	$investment_amt = $sell_asset + $sell_invest - $buy_asset - $buy_invest;  // + $other_amt;
	$investment = ($investment_amt >= 0) ? $investment_amt : "(".(0-$investment_amt).")";
	$content .= "<tr bgcolor=lightgrey><td></td><td><font size=2pt>Sale of Fixed Assets</font></td><td><font size=2pt>".number_format($sell_asset, 2)."</font></td></td></tr>
	<tr bgcolor=white><td></td><td><font size=2pt>Sale of Investments</font></td><td><font size=2pt>".number_format($sell_invest, 2)."</font></td></td></tr>
	<tr bgcolor=lightgrey><td></td><td><font size=2pt><b>Total Cash Inflows</b></font></td><td><font size=2pt><b>".number_format(($sell_invest + $sell_asset), 2)."</b></font></td></td></tr>
	<tr bgcolor=white><td colspan=2 align=left><font size=2pt><b>Outflows (Cash Decrease)</b></font></td><td><font size=2pt><b></b></font></td></td></tr>
	<tr bgcolor=lightgrey><td></td><td><font size=2pt>Purchase of Fixed Assets</font></td><td><font size=2pt>".number_format($buy_asset, 2)."</font></td></td></tr>
	<tr bgcolor=white><td></td><td><font size=2pt>Purchase of Investments</font></td><td><font size=2pt>".number_format($buy_invest, 2)."</font></td></td></tr>
	<tr bgcolor=lightgrey><td></td><td><font size=2pt><b>Total Cash Outflows</b></font></td><td><font size=2pt><b>".number_format(($buy_invest + $buy_asset), 2)."</b></font></td></td></tr>
	<tr bgcolor=white><td colspan=2 align=left><font size=2pt><b>NET INFLOW (OUTFLOW) OF CASH FROM INVESTMENT ACTIVITIES</b></font></td><td><font size=2pt><b>".number_format($investment, 2)."</b></font></td></td></tr>";
//FINANCING ACTIVITIES
	$content .="<tr bgcolor=lightgrey><td colspan=2 align=left><font size=2pt><b>CASH FLOW FROM FINANCING ACTIVITIES</b></font></td><td><font size=2pt><b></b></font></td></td></tr>
	<tr bgcolor=white><td colspan=2 align=left><font size=2pt><b>Inflows (Cash Increase)</b></font></td><td><font size=2pt><b></b></font></td></td></tr>";
//INFLOWS
//SHARES SOLD
	$shares_res = mysql_query("select sum(value) as amount from shares where date >='".$from_date."' and date <='".$to_date."' and bank_account>0");
	$shares = mysql_fetch_array($shares_res);
	$shares_amt = ($shares['amount'] != NULL) ? $shares['amount'] : 0;
//LONG TERM  EXTERNAL CREDIT
	$long_res = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '2122%' and date >='".$from_date."' and date <='".$to_date."' and p.bank_account>'0'");
	$long = mysql_fetch_array($long_res);
	$long_amt = ($long['amount'] != NULL) ? $long['amount'] : 0;
//DONATIONS AND GRANTS
	$other_res = mysql_query("select sum(o.amount) as amount from other_funds o where o.date >='".$from_date."' and o.date <= '".$to_date."' and o.bank_account >'0'"); 
	$other = mysql_fetch_array($other_res);
	$other_amt = ($other['amount'] != NULL) ? $other['amount'] : 0;
//REPAYMENT OF LONG TERM CREDIT
	$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id join accounts a on p.account_id=a.id where a.account_no like '2122%' and paid.date >='".$from_date."' and paid.date <='".$to_date."'");
	$paid = mysql_fetch_array($paid_res);
	$paid_amt = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
//DIVIDENDS PAID
	$div_res = mysql_query("select sum(total_amount) as amount from share_dividends where date >= '".$from_date."' and date <= '".$to_date."' and bank_account>0");
	$div = mysql_fetch_array($div_res);
	$div_amt = ($div['amount'] != NULL) ? $div['amount'] : 0;
	$finance_amt = $shares_amt + $long_amt + $other_amt -$paid_amt - $div_amt;
	$finance = ($finance_amt >= 0) ? $finance_amt : "(".(0-$finance_amt).")";

	
	$content .= "<tr bgcolor=lightgrey><td></td><td><font size=2pt>Sale of Shares</font></td><td><font size=2pt>".$shares_amt."</font></td></td></tr>
	<tr bgcolor=white><td></td><td><font size=2pt>Long Term External Credit Received</font></td><td><font size=2pt>".number_format($long_amt, 2)."</font></td></td></tr>
	<tr bgcolor=lightgrey><td></td><td><font size=2pt>Donations & Grants</font></td><td><font size=2pt>".number_format($other_amt, 2)."</font></td></td></tr>
	<tr bgcolor=white><td></td><td><font size=2pt><b>Total Cash Inflows</b></font></td><td><font size=2pt><b>".number_format(($shares_amt + $other_amt + $long_amt), 2)."</b></font></td></td></tr>
	<tr bgcolor=lightgrey><td colspan=2 align=left><font size=2pt><b>Outflows (Cash Decrease)</b></font></td><td><font size=2pt><b></b></font></td></td></tr>
	<tr bgcolor=white><td></td><td><font size=2pt>Long Term External Credit Repaid</font></td><td><font size=2pt>".number_format($paid_amt, 2)."</font></td></td></tr>
	<tr bgcolor=lightgrey><td></td><td><font size=2pt>Dividends Paid</font></td><td><font size=2pt>".number_format($div_amt, 2)."</font></td></td></tr>
	<tr bgcolor=white><td></td><td><font size=2pt><b>Total Cash Outflows</b></font></td><td><font size=2pt><b>".number_format(($div_amt + $paid_amt), 2)."</b></font></td></td></tr>
	<tr bgcolor=lightgrey><td colspan=2 align=left><font size=2pt><b>NET INFLOW (OUTFLOW) OF CASH FROM FINANCE ACTIVITIES</b></font></td><td><font size=2pt><b>".number_format($finance, 2)."</b></font></td></td></tr>";
	$content .= "</table>";

	$net_change = $operating_amt + $investment_amt + $finance_amt;
	$net = ($net_change >= 0) ? $net_change : "(".(0 - $net_change).")";

//DEPOSITS
			$dep_res = mysql_query("select sum(amount) as amount from deposit where bank_account>'0' and date <'".$from_date."'");
			$dep = mysql_fetch_array($dep_res);
			$dep_amt = ($dep['amount'] == NULL) ? 0 : $dep['amount'];
			//WITHDRAWALS
			$with_res = mysql_query("select sum(amount) as amount from withdrawal where bank_account>'0' and date <'".$from_date."'");
			$with = mysql_fetch_array($with_res);
			$with_amt = ($with['amount'] == NULL) ? 0 : $with['amount'];
			//OTHER INCOME
			$other_res = mysql_query("select sum(amount) as amount from other_income where bank_account>'0' and date <'".$from_date."'");
			$other = mysql_fetch_array($other_res);
			$other_amt = ($other['amount'] == NULL) ? 0 : $other['amount'];
			//EXPENSES
			$expense_res = mysql_query("select sum(amount) as amount from expense where bank_account>'0' and date <'".$from_date."'");
			$expense = mysql_fetch_array($expense_res);
			$expense_amt = ($expense['amount'] == NULL) ? 0 : $expense['amount'];
			//LOANS PAYABLE
			$loans_payable = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '212%' and p.bank_account>'0' and p.date < '".$from_date."'");
			$loans_payable = mysql_fetch_array($loans_payable);
			$loans_payable = ($loans_payable['amount'] != NULL) ? $loans_payable['amount'] : 0;
			//PAYABLE PAID
			$payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where bank_account>'0' and date <'".$from_date."'");
			$payable_paid = mysql_fetch_array($payable_paid_res);
			$payable_paid_amt = ($payable_paid['amount'] == NULL) ? 0 : $payable_paid['amount'];
			//RECEIVALE COLLECTED
			$collected_res = mysql_query("select sum(amount) as amount from collected where bank_account>'0' and date <'".$from_date."'");
			$collected = mysql_fetch_array($collected_res);
			$collected_amt = ($collected['amount'] == NULL) ? 0 : $collected['amount'];
			//DISBURSED LOANS
			$disb_res = mysql_query("select sum(amount) as amount from disbursed where bank_account>'0' and date < '".$from_date."'");
			$disb = mysql_fetch_array($disb_res);
			$disb_amt = ($disb['amount'] == NULL) ? 0 : $disb['amount'];
			//PAYMENTS
			$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id  where p.date < '".$from_date."' and p.bank_account>0");
			$pay = mysql_fetch_array($pay_res);
			$pay_amt = ($pay['amount'] == NULL) ? 0 : $pay['amount'];
			//PENALTIES
			$pen_res = mysql_query("select sum(p.amount) as amount from penalty p join disbursed d on p.loan_id=d.id where p.status='paid' and p.date < '".$from_date."'");
			$pen = mysql_fetch_array($pen_res);
			$pen_amt = ($pen['amount']==NULL) ? 0 : $pen['amount'];
								
			//SHARES
			$shares_res = mysql_query("select sum(value) as amount from shares where date <'".$from_date."'");
			$shares = mysql_fetch_array($shares_res); 
			$shares_amt = ($shares['amount'] == NULL) ? 0 : $shares['amount'];
			//RECOVERED
			$rec_res = mysql_query("select sum(r.amount) as amount from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.date < '".$from_date."'");
			$rec = mysql_fetch_array($rec_res);	
			$rec_amt = ($rec['amount'] == NULL) ? 0 : $rec['amount']; 
			//INVESTMENTS 
			$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where date < '".$from_date."'");
			$invest = mysql_fetch_array($invest_res);
			$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
			//DIVIDENDS PAID
			$div_res = mysql_query("select sum(total_amount) as total_amount from share_dividends where date<'".$from_date."'");
			$div = mysql_fetch_array($div_res);
			$div_amt = ($div['amount'] != NULL) ? $div['amount'] : 0;

								
			//SOLD INVESTMENTS
			$soldinvest_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where  date < '".$from_date."'");
			$soldinvest = mysql_fetch_array($soldinvest_res);


			//FIXED ASSETS 
			$fixed_res = mysql_query("select sum(initial_value) as amount from fixed_asset where  date <'".$from_date."'");
			$fixed = mysql_fetch_array($fixed_res);
			$soldasset_res = mysql_query("select sum(amount) as amount from sold_asset where  date < '".$from_date."'");
			$soldasset = mysql_fetch_array($soldasset_res);
									
		/*	//CASH IMPORTED
			$import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id>'0' and date <'".$from_date."'");
			$import = mysql_fetch_array($import_res);
			$import_amt = $import['amount'];

			//CASH EXPORTED
			$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id>'0' and date <'".$from_date."'");
			
			$export = mysql_fetch_array($export_res);
			$export_amt = intval($export['amount']);
*/
			//CAPITAL FUNDS
			$fund_res = mysql_query("select sum(amount) as amount from other_funds where bank_account>0 and date <'".$from_date."'");
			$fund = mysql_fetch_array($fund_res);
			$fund_amt = $fund['amount'];

			$begin_bal =  $collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt + $fund_amt;	
	
	$content .="<br><table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>
	<tr bgcolor=white><td colspan=2 align=left><font size=2pt><b>Net Increase (Decrease) in Cash</b></font></td><td><font size=2pt><b>".number_format($net, 2)."</b></font></td></td></tr>
	<tr bgcolor=lightgrey><td colspan=2 align=left><font size=2pt><b>Cash Balance at the Beginning of the Period</b></font></td><td><font size=2pt><b>".number_format($begin_bal, 2)."</b></font></td></td></tr>
	<tr bgcolor=white><td colspan=2 align=left><font size=2pt><b>Cash Balance at End of Period</b></font></td><td><font size=2pt><b>".number_format(($begin_bal + $net_change), 2)."</b></font></td></td></tr>";

	$content .= "</td></tr></table>";
	export($format, $content);
}

//reports_header();
cash_flow($_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['year'], $_GET['month'], $_GET['mday'], $_GET['format'],$_GET['branch_id']);
//reports_footer();

?>
