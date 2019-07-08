<?php

if(!isset($_GET['format']))
echo("<head>
	<title>PORTFOLIO AT RISK BY AGEING  REPORT</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");
//PORTIFOLIO AT RISK BY AGEING  REPORT
function risk_ageing($cust_name, $cust_no, $account_name, $loan_officer, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday, $format,$branch_id){
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:"and d.branch_id=".$branch_id;
	$content .= "<center><p><font color=#00008b size=4pt><b>".$branch['branch_name']."</b></font></p></center>";
	$content .= "<center><font color=#00008b size=3pt><b>PORTFOLIO AT RISK BY AGEING REPORT</b></font></center><table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='70%' id='AutoNumber2' align=center><tr><td>
		<table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>";
	if($from_year==''){
		$content .= "<tr><td><center><font color=red>Please select the disbursed loans you would like to list!</font></center></td></tr></table></td></tr></table>";
	}
	$name = ($cust_name == 'All') ? "" : $cust_name;
	$mem_no = ($cust_no == 'All') ? "" : $cust_no;
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
	if($loan_officer >0)
		$officer = "o.id='".$loan_officer."'";
	else
		$officer = "o.id > 0";
	
	$sth = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.first_name as of_first_name, o.last_name as of_last_name, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, d.int_rate as int_rate, d.int_method as int_method, d.grace_period as grace_period, d.writeoff_period as writeoff_period, (DATEDIFF(CURDATE(), d.last_pay_date)/30) as since_period, d.arrears_period as arrears_period, d.written_off as written_off, d.bank_account as bank_account from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join officer o on applic.officer_id=o.id where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' and written_off='0' ".$branch_." order by o.first_name, o.last_name, m.first_name, m.last_name limit");
	
	if(@ mysql_numrows($sth) == 0){
		$content .= "<tr><td><center><font color=red>No disbursed loans in your search options</font></center></td></tr></table></td></tr></table>";
	}
	
	$former_officer ="";
	$i=$stat+1;
	while($row = mysql_fetch_array($sth)){
		$officer = $row['of_first_name']." ".$row['of_last_name'];
		if(strcmp($former_officer, $officer) != 0){
			$content .="<tr class='headings'><td colspan=27><b>Loan Officer:  ".$officer."</b></td></tr> <tr class='headings'><td rowspan=2><b>No</b></td><td rowspan=2><b>MemberNo</b></td><td rowspan=2><b>Member Name</b></td><td rowspan=2><b>Date Disbursed</b></td><td rowspan=2><b>Amount Disbursed</b></td><td rowspan=2><b>Portifolio At Risk</b></td><td colspan=6><b>AA Deliquent Ranges</b></td><td rowspan=2><b>Principal Outstanding</b></td><td rowspan=2><b>Interest Outstanding</b></td><td rowspan=2><b>Total Outstanding</b></td></tr>
			<tr class='headings'><td><b>A 1 Day - 1 Month</b></td><td><b>B 1-3 Months</b></td><td><b>C 4-6 Months</b></td><td><b>D 7-9 Months</b></td><td><b>E 10-12 Months</b></td><td><b>F >12 Months</b></td></tr>";
			$former_officer = $officer;
		}
		//DELIQUENT RANGES
		$paid_amt = $row['amount'] - $row['balance'];
		$sched_res = mysql_query("select sum(princ_amt) as princ_amt from schedule where loan_id='".$row['id']."' and date <= CURDATE()");
		$sched = mysql_fetch_array($sched_res);
		$sched_amt = ($sched['princ_amt'] != NULL) ? $sched['princ_amt'] : 0;
		$arrears_amt = $sched_amt - $paid_amt; 
		$range1 = 0;
		$range2 = 0;
		$range3 = 0;  
		$range4 = 0;
		$range5 = 0;
		$range6 = 0;
		//OFFSET MONTHS
		$offset_res = mysql_query("select (DATEDIFF(CURDATE(), date) / 30) as months_off from schedule where loan_id=".$row['id']."  order by date desc limit 1");
		$offset = mysql_fetch_array($offset_res);
		$offset = floor($offset['months_off']);
		if($arrears_amt >0){
			$instalment = ceil($row['amount'] / ($row['loan_period']/ 30));
			$no = ceil($arrears_amt / $instalment);
			//$no += $offset;
			//$remainder = $arrears_amt % $instalment;
			if($offset >= 12){
				$range6 = $arrears_amt;
			}else{
				if($no >= 13)
					$range6 = $arrears_amt - (12 * $instalment);
				if($no >= 10)
					$range5 = $arrears_amt - $range6 - (9 * $instalment);
				if($no >= 7)
					$range4 =$arrears_amt - $range6 -$range5 - (6 * $instalment);
				if($no >= 4)
					$range3 = $arrears_amt - $range6 - $range5 - $range4 - (3 * $instalment);
				if($no >=2)
					$range2 = $arrears_amt - $range6 - $range5 - $range4 - $range3 - 1* $instalment;
				if($no >= 1){
					$range1 = ($instalment > $arrears_amt) ? $arrears_amt : $instalment;
				}
				if($offset >=1 && $offset <= 3){
					$range6 += $range5;
					$range5 = $range4;
					$range4 = $range3;
					$range3 = $range2;
					$range2 = $range1;
					$range1 = 0;
				}
				if($offset >=4 && $offset <= 5){
					$range6 = $range6 + $range5 + $range4;
					$range5 = $range3;
					$range4 = $range2;
					$range3 = $range1;
					$range2 = 0;
					$range1 = 0;
				}
				if($offset >=6 && $offset <= 8){
					$range6 = $range6 + $range5 + $range4 + $range3;
					$range5 = $range2;
					$range4 = $range1;
					$range3 = 0;
					$range2 = 0;
					$range1 = 0;
				}
				if($offset >=9 && $offset <= 11){
					$range6 = $range6 + $range5 + $range4 + $range3 + $range2;
					$range5 = $range1;
					$range4 = 0;
					$range3 = 0;
					$range2 = 0;
					$range1 = 0;
				}
			}
		}
		//ARREARS
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id='".$row['id']."' and date < DATE_SUB(CURDATE(), INTERVAL ".$row['arrears_period']." DAY)");
		$sched = @mysql_fetch_array($sched_res);
	
		$paid_res = mysql_query("select sum(princ_amt) as princ_amt, sum(int_amt) as int_amt from payment where loan_id='".$row['id']."' and date <= CURDATE()");
		
		$paid = mysql_fetch_array($paid_res);
		$sched_amount = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? 0 : $sched['int_amt'];
		$int_paid_amt = ($paid['int_amt'] == NULL) ? 0 : $paid['int_amt'];
		$int_arrears = $sched_int - $int_paid_amt;
		
		if($int_arrears <= 0){
			$outstanding_int = 0;
		}else{
			$outstanding_int = $int_arrears;
		}
		
		$outstanding_int = ceil($outstanding_int);
		$outstanding_int = ($outstanding_int > 0) ? $outstanding_int : 0;
		$total_outstanding = $row['balance'] + $outstanding_int;
		$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr bgcolor=$color><td>".$i."</td><td>".$row['mem_no']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['date']."</td><td>".number_format($row['amount'], 2)."</td><td>".number_format($row['balance'], 2)."</td><td>".number_format($range1, 2)."</td><td>".number_format($range2, 2)."</td><td>".number_format($range3, 2)."</td><td>".number_format($range4, 2)."</td><td>".number_format($range5, 2)."</td><td>".number_format($range6, 2)."</td><td>".number_format($row['balance'], 2)."</td><td>".number_format($outstanding_int, 2)."</td><td>".number_format($total_outstanding, 2)."</td></tr>";
		$i++;
	}
	$content .= "</table></td></tr></table>";
	export($format, $content);
}
//reports_header();
risk_ageing($_GET['cust_name'], $_GET['cust_no'], $_GET['account_name'], $_GET['loan_officer'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday'], $_GET['format'],$_GET['branch_id']);
//reports_footer();
?>
