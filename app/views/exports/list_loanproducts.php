<?php

if(!isset($_GET['format']))
echo("<head>
	<title>LOAN PRODUCTS</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");

//LIST LOAN PRODUCTS
function list_loanproducts($format){
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$content .= "<center><p><font color=#00008b size=4pt><b>".$branch['branch_name']."</b></font></p></center>";
	$content .= "<center><font color=#00008b size=3pt><b>LIST OF LOAN PRODUCTS</b></font></center><table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>";
	$sth = mysql_query("select p.id as loanproduct_id, p.int_rate as int_rate, p.int_method as int_method, p.based_on as based_on,  p.grace_period as grace_period, p.arrears_period as arrears_period, p.loan_period as loan_period, p.writeoff_period as writeoff_period, p.max_loan_amt as max_loan_amt, a.id as account_id, a.name as name, a.account_no as account_no from loan_product p join accounts a on p.account_id=a.id order by a.account_no");
	if(@ mysql_numrows($sth) == 0)
		$content .= "<tr><td>No Loan Products Created Yet!</td></tr>";
	else{
		$content .= "<tr class='headings'><td><b>Product</b></td><td><b>Int. Rate (% Per Annum)</b></td><td><b>Method</b></td><td><b>Grace Period (Months)</b></td><td><b>Arrears Maturity (Months)</b></td><td><b>Loan Period</b></td><td><b>Max. Amount</b></td><td><b>Write-off Period (Months)</b></td><td><b>Based On</b></td></tr>";
		$i=0;
		while($row = mysql_fetch_array($sth)){
			$grace_period = $row['grace_period'] /30;
			$loan_period = $row['loan_period']/30;
			$arrears_period = $row['arrears_period']/30;
			$writeoff_period = $row['writeoff_period']/30;
			$color= ($i%2 == 0) ? "lightgrey" : "white"; 
			$content .= "<tr bgcolor=$color><td>".$row['account_no'] ." - ".$row['name']."</td><td>".$row['int_rate']."</td><td>".$row['int_method']."</td><td>".$grace_period."</td><td>".$arrears_period."</td><td>".$loan_period."</td><td>".$row['max_loan_amt']."</td><td>".$writeoff_period."</td><td>".ucfirst($row['based_on'])."</td></tr>";
			$i++;
		}
	}
	$content .= "</table>";
export($format, $content);
}

//reports_header();
list_loanproducts($_GET['format']);
//reports_footer();
?>
