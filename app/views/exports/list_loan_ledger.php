<?php

if(!isset($_GET['format']))
echo("<head>
	<title>LOAN LEDGER</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");

//LOAN LEDGER
function loan_ledger($mem_no, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday, $format){
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$content .= "<center><p><font color=#00008b size=4pt><b>".$branch['branch_name']."</b></font></p></center>";
	$content .= "<center><font color=#00008b size=3pt><b>LOAN LEDGER</b></font></center>
		<table align=center border='0' height=10 cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='70%' id='AutoNumber2' align=left>";
	if($from_year =='')
		$content .= "<tr><td><font color=red>Please select options of the ledger</font></td></tr>";
	elseif($mem_no==''){
		$content .= "<tr><td><font color=red>Please enter the Member No or select the member!</font></td></tr>";
		
	}else{
		$over_res = mysql_query("select * from member where mem_no='".$mem_no."'");
		if(mysql_numrows($over_res) == 0){
			$content .= "<tr><td><font color=red>The Member No entered does not exist!</font></td></tr></table>";
		}
		$over=mysql_fetch_array($over_res);
		$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
		$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
		$content .= "<tr bgcolor=lightgrey><td><b>Member No:</b></td><td><b>".$over['mem_no']."</b></td></tr>
		<tr bgcolor=white><td><b>Member Name:</b></td><td><b>".$over['first_name']." ".$over['last_name']."</b></td></tr></table>
		<table height=100 border='1' height=100 cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='70%' id='AutoNumber2' align=center>";
		//START BALANCE
		$bal_res = mysql_query("select  sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."'  and d.date < '".$from_date."'"); 
		$bal = mysql_fetch_array($bal_res);
		$pay_res = mysql_query("select sum(p.princ_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where p.date < '".$from_date."' and applic.mem_id='".$over['id']."'");
		$pay = mysql_fetch_array($pay_res);
		$bal_amt = ($bal['amount'] == NULL) ? 0 : $bal['amount'];
		$pay_amt = ($pay['amount'] == NULL) ? 0 : $pay['amount'];
		$start_bal = $bal_amt - $pay_amt;

		$loan_res = mysql_query("select d.date as date, d.amount as amount from disbursed d join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' d.date >= '".$from_date."' and d.date <= '".$to_date."' and applic.mem_id='".$over['id']."'"); 

		$paid_res = mysql_query("select p.date as date, p.princ_amt as amount, p.int_amt as int_amt,  p.receipt_no as receipt_no from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and p.date >= '".$from_date."' and p.date <= '".$to_date."' and applic.mem_id='".$over['id']."' order by p.date asc");

		$pen_res = mysql_query("select pen.amount as amount, pen.date as date, pen.status as status from penalty pen join disbursed d on pen.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' pen.date >= '".$from_date."' and pen.date <= '".$to_date."' and applic.mem_id='".$over['id']."'");
		
			$content .= "<tr class='headings'><td><b>Date</b></td><td><b>Description of Transaction</b></td><td><b>Interest</b><td><b>Debit</b></td><td><b>Credit</b></td><td><b>Balance</b></td></tr>
		<tr bgcolor=lightgrey><td>Before ".$from_date."</td><td>Start Balance</td><td>--</td><td>--</td><td>--</td><td>".number_format($start_bal, 2)."</td></tr>";
		$last_date = $from_date;
		$balance = $start_bal;
		//if(mysql_numrows($paid_res) >0){
			$i=1;
			while($paid = mysql_fetch_array($paid_res)){
				$loan_res = mysql_query("select d.date as date, d.amount as amount from disbursed d join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and d.date >= '".$last_date."' and d.date < '".$paid['date']."' and applic.mem_id='".$over['id']."' order by d.date asc");
				while($loan = mysql_fetch_array($loan_res)){
					$pen_res = mysql_query("select pen.amount as amount, pen.date as date, pen.status as status from penalty pen join disbursed d on pen.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and pen.date >= '".$last_date."' and pen.date < '".$loan['date']."' and applic.mem_id='".$over['id']."' order by pen.date asc");
					while($pen = mysql_fetch_array($pen_res)){
						$color=($i%2 == 0) ? "lightgrey" : "white";
						if($pen['status'] == 'pending'){
							$balance = $balance + $pen['amount'];
						
							$content .= "<tr bgcolor=$color><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
						}else
							$content .= "<tr bgcolor=$color><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>".$pen['amount']."</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
						$last_date = $pen['date'];
						$i++;
					}
					$balance = $balance + $loan['amount'];
					$color=($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td>".$loan['date']."</td><td>Loan Disbursed</td><td>--</td><td>--</td><td>".number_format($loan['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
					$last_date = $loan['date'];
					$i++;
				}
				$pen_res = mysql_query("select pen.amount as amount, pen.date as date, pen.status as status from penalty pen join disbursed d on pen.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and pen.date >= '".$last_date."' and pen.date < '".$paid['date']."' order by pen.date asc");
				while($pen = mysql_fetch_array($pen_res)){
					$color=($i%2 == 0) ? "lightgrey" : "white";
					if($pen['status'] == 'pending'){
						$balance = $balance + $pen['amount'];
						$content .= "<tr bgcolor=$color><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
					}else
						$content .= "<tr bgcolor=$color><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
						$i++;
				}
				$balance = $balance - $paid['amount'];
				$color=($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td>".$paid['date']."</td><td>Repayment<br>RCPT: ".$paid['receipt_no']."</td><td>--</td><td>".number_format($paid['amount'], 2)."</td><td>--</td><td>".number_format($balance, 2)."</td></tr>";
				$last_date = $paid['date'];
				$i++;
				//INTEREST
				if($paid['int_amt'] != 0){
					$color=($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td>".$paid['date']."</td><td>Interest Paid<br>RCPT: ".$paid['receipt_no']."</td><td>".number_format($paid['int_amt'], 2)."</td><td>--</td><td>--</td><td>".number_format($balance, 2)."</td></tr>";
					//$last_date = $paid['date'];
					$i++;
				}
			}
			$loan_res = mysql_query("select d.date as date, d.amount as amount from disbursed d join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and d.date > '".$last_date."' and d.date < '".$to_date."' and applic.mem_id='".$over['id']."' order by d.date asc");
			while($loan = mysql_fetch_array($loan_res)){
				$pen_res = mysql_query("select pen.amount as amount, pen.date as date, pen.status as status from penalty pen join disbursed d on pen.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and pen.date >= '".$last_date."' and pen.date < '".$loan['date']."' and applic.mem_id='".$over['id']."' order by pen.date asc");
				while($pen = mysql_fetch_array($pen_res)){
					$color=($i%2 == 0) ? "lightgrey" : "white";
					if($pen['status'] == 'pending'){
						$balance = $balance + $pen['amount'];
						$content .= "<tr bgcolor=$color><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
					}else
						$content .= "<tr bgcolor=$color><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
					$last_date = $pen['date'];
					$i++;
				}
				$balance = $balance + $loan['amount'];
				$color=($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td>".$loan['date']."</td><td>Loan Disbursed</td><td>--</td><td>--</td><td>".number_format($loan['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
				$last_date = $loan['date'];
				$i++;
			}
			$pen_res = mysql_query("select pen.amount as amount, pen.date as date, pen.status as status from penalty pen join disbursed d on pen.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and pen.date >= '".$last_date."' and pen.date <= '".$to_date."' and applic.mem_id='".$over['id']."' order by pen.date asc");
			while($pen = mysql_fetch_array($pen_res)){
				if($pen['status'] == 'pending'){
					$color=($i%2 == 0) ? "lightgrey" : "white";
					$balance = $balance + $pen['amount'];
					$content .= "<tr bgcolor=$color><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
				}else
					$content .= "<tr bgcolor=$color><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
				$last_date = $pen['date'];
				$i++;
			}
	}
	$content .= "</table></td></tr></table>";
	export($format, $content);
}

//reports_header();
loan_ledger($_GET['mem_no'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday'], $_GET['format']);
//reports_footer();
?>
