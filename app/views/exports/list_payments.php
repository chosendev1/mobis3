<?php


if(!isset($_GET['format']))
echo("<head>
	<title>REPAYMENT REPORT</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");

//PRINT PAYMENTS
function add_pay($loan_id, $applic_id,   $cust_name, $cust_no, $account_name, $loan_officer, $princ_arrears, $int_arrears, $princ_due, $int_due, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday, $status, $format){
	
	$applic_res = mysql_query("select m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, applic.amount as amount, d.date as disburse_date, d.amount as disbursed_amt, d.balance as balance, d.int_method as int_method, d.int_rate as int_rate,  d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, d.cheque_no as cheque_no from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id where applic.id='".$applic_id."'");
	$applic = mysql_fetch_array($applic_res);
	$total_int = $int_arrears + $int_due;
	$pen_res = mysql_query("select sum(amount) as amount from penalty where loan_id='".$loan_id."' and status='pending'");
	$pen = @ mysql_fetch_array($pen_res);
	if($pen['amount'] == NULL){
		$penalty = "--";
		$former_penalty = 0;
	}else{
		$penalty = $pen['amount'];
		$former_penalty = $pen['amount'];
	}
	$branch_res = mysql_query("select * from branch");
	$branch = mysql_fetch_array($branch_res);
	$content .= "<center><p><font color=#00008b size=4pt><b>".$branch['branch_name']."</b></font></p></center>";
	$content .= "<center><font color=#00008b size=3pt><b>PAYMENTS MADE BY ".strtoupper($applic['first_name'])." ".strtoupper($applic['last_name'])."</b></font></center>
	<table height=100 border='1' cellpadding='0' height=70 cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='50%' id='AutoNumber2' align=center>
		<tr bgcolor=lightgrey><td>Member No:</td><td>".$applic['mem_no']."</td></tr>
		<tr bgcolor=white><td>Cheque No</td><td>".$applic['cheque_no']."</td></tr>
		<tr bgcolor=white><td>Amount Disbursed:</td><td>".number_format($applic['amount'], 2)."</td></tr>
		<tr bgcolor=lightgrey><td>Disbursement Date:</td><td>".$applic['disburse_date']."</td></tr>
		<tr bgcolor=white><td>Principal in Arrears:</td><td>".number_format($princ_arrears, 2)."</td></tr>
		<tr bgcolor=lightgrey><td>Penalty:</td><td>".number_format($penalty, 2)."</td></tr>
		<tr bgcolor=white><td>Principal Due:</td><td>".number_format($princ_due, 2)."</td></tr>
		<tr bgcolor=lightgrey><td>Total Interest:</td><td>".number_format($total_int, 2)."</td></tr>
	</table><br>
	<table height=100 border='1' cellpadding='0' height=70 cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='70%' id='AutoNumber2' align=center>";
	$sth = mysql_query("select * from payment where loan_id='".$loan_id."' order by id asc");
	if(@ mysql_numrows($sth) == 0){
		$content .= "<tr><td><font color=red>No payments yet made for this loan</font></td></tr>";
	}else{
		$content .= "<tr class='headings'><td><b>No</b></td><td><b>Date</b></td><td><b>ReceiptNo</b></td><td><b>Beginning Balance</b></td><td><b>Total Paid</b></td><td><b>Princ Paid</b></td><td><b>Interest Paid</b></td><td><b>Penalty</b></td><td><b>Ending Balance</b></td></tr>";
		$i=1;
		$num = mysql_numrows($sth);
		while($row = mysql_fetch_array($sth)){
			$edit = ($i == $num) ? "<a href=# onclick=\"xajax_delete_pay('".$row['id']."', '".$loan_id."', '".$applic_id."',   '".$cust_name."', '".$cust_no."', '".$account_name."', '".$loan_officer."', '".$princ_arrears."', '".$int_arrears."', '".$princ_due."', '".$int_due."', '".$from_year."', '".$from_month."', '".$from_mday."', '".$to_year."', '".$to_month."', '".$to_mday."');\">Delete</a>" : "--";
			$pen_res = mysql_query("select sum(amount) as amount from penalty where date='".$row['date']."' and loan_id='".$loan_id."' and status='paid'");
			$pen = mysql_fetch_array($pen_res);
			$pen_amt = ($pen['amount'] == NULL) ? 0 : $pen['amount'];
			$penalty = ($pen['amount'] == NULL) ? "--" : $pen['amount'];
			$color=($i%2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=".$color."><td>".$i."</td><td>".$row['date']."</td><td>".$row['receipt_no']."</td><td>".number_format($row['begin_bal'], 2)."</td><td>".number_format(($row['princ_amt']+$row['int_amt']+$pen_amt), 2)."</td> <td>".number_format($row['princ_amt'], 2)."</td><td>".number_format($row['int_amt'], 2)."</td><td>".number_format($penalty, 2)."</td><td>".number_format($row['end_bal'], 2)."</td></tr>";
			$i++;
		}
	}
	$content .= "</table>";
	export($format, $content);
}

//reports_header();
add_pay($_GET['loan_id'], $_GET['applic_id'],   $_GET['cust_name'], $_GET['cust_no'], $_GET['account_name'], $_GET['loan_officer'], $_GET['princ_arrears'], $_GET['int_arrears'], $_GET['princ_due'], $_GET['int_due'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday'], $_GET['status'], $_GET['format']);
//reports_footer();

?>
