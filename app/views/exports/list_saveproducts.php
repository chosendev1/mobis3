<?php

if(!isset($_GET['format']))
echo("<head>
	<title>LIST OF SAVINGS PRODUCTS</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");

//LIST SAVINGS PRODUCT
function list_saveproduct($format){
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$content = "<center><p><font color=#00008b size=4pt><b>".$branch['branch_name']."</b></font></p></center>";
	$content .= "<center><font color=#00008b size=3pt><b>LIST OF SAVINGS PRODUCTS</b></font></center><br><table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>";
	$sth = mysql_query("select s.id as saveproduct_id, s.type as type, a.account_no as account_no, a.name as name,s.id as id, s.account_id as account_id, s.grace_period as grace_period, s.opening_bal as opening_bal, s.min_bal as min_bal, s.int_rate as int_rate, s.withdrawal_perc as withdrawal_perc, s.withdrawal_flat as withdrawal_flat, s.deposit_perc as deposit_perc, s.deposit_flat as deposit_flat, s.monthly_charge as monthly_charge, s.int_frequency as int_frequency from savings_product s join accounts a on s.account_id=a.id  order by a.name");
	
	if(@ mysql_numrows($sth) == 0)
		$content .= "<tr><td>No savings products created yet!</td></tr>";
	else{
		$content .= "<tr class='headings'><td><b>Product</b></td><td><b>Opening Bal.</b></td><td><b>Min Bal.</b></td><td><b>Int. Rate (%)</b></td><td><b>Withdrawal Charge (% of Amt)</b></td><td><b>Withdrawal Flat Charge</b></td><td><b>Deposit Charge (% of Amt)</b></td><td><b>Deposit Flat Charge</b></td><td><b>Monthly Charge</b></td><td><b>Int. Frequency(Months)</b></td><td><b>Type</b></td></tr>";
		$i=0;
		while($row = mysql_fetch_array($sth)){
			$color=($i%2 == 0) ? "lightgrey" : "white";
			$name = $row['account_no']." - ".$row['name'];
			$content .= "<tr bgcolor=$color><td>".$name."</td><td>".$row['opening_bal']."</td><td>".$row['min_bal']."</td><td>".$row['int_rate']."</td><td>".$row['withdrawal_perc']."</td><td>".$row['withdrawal_flat']."</td><td>".$row['deposit_perc']."</td><td>".$row['deposit_flat']."</td><td>".$row['monthly_charge']."</td><td>".$row['int_frequency']."</td><td>".$row['type']."</td></tr>";
			$i++;		
		}
	}
	$content .= "</table>";
	export($format, $content);
}
//reports_header();
list_saveproduct($_GET['format']);
//reports_footer();
?>