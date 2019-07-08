<?php

if(!isset($_GET['format']))
echo("<head>
	<title>LIST OF WRITTEN-OFF LOANS</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");
//LIST OF WRITTEN OFF LOANS
function list_written_off($cust_name, $cust_no, $account_name, $loan_officer, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday, $format,$branch_id){
	$branch_res = mysql_query("select * from branch");
	$branch = mysql_fetch_array($branch_res);
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:"and d.branch_id=".$branch_id;
	$content .= "<center><p><font color=#00008b size=4pt><b>".$branch['branch_name']."</b></font></p></center>";
	$content .= "<center><font color=#00008b size=3pt><B>LIST OF WRITTEN-OFF LOANS</B></font></center><table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center><tr><td>
		<table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='70%' id='AutoNumber2' align=center>";
	if($from_year==''){
		$content .= "<tr bgcolor=lightgrey><td><center><font color=red>Please select the Written loans you would like to list!</font></center></td></tr></table></td></tr></table>";
	}
	$name = ($cust_name == 'All') ? "" : $cust_name;
	$mem_no = ($cust_no == 'All') ? "" : $cust_no;
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
	if($loan_officer >0)
		$officer = "o.id='".$loan_officer."'";
	else
		$officer = "o.id > 0";
	
	$sth = mysql_query("select w.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.first_name as of_first_name, o.last_name as of_last_name, a.account_no as account_no, a.name as account_name, d.amount as disburse_amount, d.date as disburse_date, w.balance as write_balance,  d.bank_account as bank_account, w.amount as write_amount, w.date as write_date from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join officer o on applic.officer_id=o.id join written_off w on w.loan_id=d.id where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and d.written_off='1' ".$branch_);
	
	if(@ mysql_numrows($sth) == 0){
		$content .= "<tr bgcolor=lightgrey><td><center><font color=red>No loans written off in your search options</font></center></td></tr></table></td></tr></table>";
	}
	
	$former_officer ="";
	$i=$stat+1;
	while($row = mysql_fetch_array($sth)){
		$color=($i%2 == 0) ? "lightgrey" : "white";
		$officer = $row['of_first_name']." ".$row['of_last_name'];
		if(strcmp($former_officer, $officer) != 0){
			$content .="<tr class='headings'><td colspan=16><b>Loan Officer:  ".$officer."</b></td></tr> <tr class='headings'><td><b>No</b></td><td><b>Date Disbursed</b></td><td><b>MemberNo</b></td><td><b>Member Name</b></td><td><b>Product</b></td><td><b>Disbursed Amount</b></td><td><b>Amount Written-off</b></td><td><b>Write-off Date</b></td><td><b>Written-off Balance</b></td></tr>";
			$former_officer = $officer;
		}
		$content .= "<tr bgcolor=$color><td>".$i."</td><td>".$row['disburse_date']."</td><td>".$row['mem_no']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['account_no']." - ".$row['account_name']."</td><td>".number_format($row['disburse_amount'], 2)."</td><td>".number_format($row['write_amount'], 2)."</td><td>".$row['write_date']."</td><td>".number_format($row['write_balance'], 2)."</td></tr>";
		$i++;
	// GET SUB TOTALS
		$disbursed_sub_total += $row['disburse_amount']; 
		$write_amt_sub_total +=$row['write_amount'];
		$write_balance_sub_total +=$row['write_balance'];
	}
	// PRINT SUB TOTALS
	$content .= "<tr class='headings'><td colspan='5'><b>TOTAL</b></td><td><b>".number_format($amt_sub_total, 2)."</b></td><td><b>".number_format($disbursed_sub_total, 2)."</b></td><td><b>".number_format($write_amt_sub_total, 2)."</b></td><td colspan=2><b>".number_format($write_balance_sub_total, 2)."</b></td></tr>";
	$content .="</table></td></tr></table>";
	export($format, $content);
}
//reports_header();
list_written_off($_GET['cust_name'], $_GET['cust_no'], $_GET['account_name'], $_GET['loan_officer'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday'], $_GET['format'],$_GET['branch_id']);
//reports_footer();
?>
