<?php

if(!isset($_GET['format']))
echo("<head>
	<title>LIST OF DEPOSITS</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");

//LIST DEPOSITS
function list_deposits($mem_no, $name, $product, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday, $format,$branch_id){
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:"and d.branch_id=".$branch_id;
	$content = "<center><p><font color=#00008b size=4pt><b>".$branch['branch_name']."</b></font></p></center>";
	$content .= "<center><font color=#00008b size=3pt><b>LIST OF DEPOSITS</b></font></center>";
	
	$content .= "<table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width=70% id='AutoNumber2' align=center>";
	if($mem_no=='' && $name=='' && $product_type=='')
		$content .= "<tr bgcolor=lightgrey><td><font color=red>Please enter your search criteria</font></td></tr>";
	else{
		$dname = ($name == 'All') ? "" : $name;
		$dmem_no = ($mem_no == 'All') ? "" : $mem_no;
		$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
		$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);

		$sth = mysql_query("select d.receipt_no as receipt_no, d.bank_account as bank_account, d.id as id, d.percent_value as percent_value, d.flat_value as flat_value, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, d.amount as amount, d.date as date, a.account_no as account_no, a.name as name from deposit d join mem_accounts mem on d.memaccount_id=mem.id join  member m on mem.mem_id=m.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where mem.saveproduct_id like '%".$product."%' and (m.mem_no like '%".$dmem_no."%' and (m.first_name like '%".$dname."%' or m.last_name like '%".$dname."%')) and d.date >= '".$from_date."' and d.date <= '".$to_date."' and s.type='free' ".$branch_." order by m.first_name, m.last_name, d.date");
	
		if(@mysql_numrows($sth)==0)
				$content .= "<tr><td><font color=red>No deposits in the selected search criteria</font></td></tr>";
		else{
			$content .= "<tr class='headings'><td><b>Member Name</b></td><td><b>MemberNo</b></td><td><b>Amount</b></td><td><b>ReceiptNo</b></td><td><b>Percent Charge</b></td><td><b>Flat Charge</b></td><td><b>Date</b></td><td><b>Product</b></td><td><b>Bank Account</b></td></tr>";
			$i=0;
			while($row = @mysql_fetch_array($sth)){
				$bank_res = mysql_query("select a.account_no as account_no, a.name as name from bank_account b join accounts a on b.account_id=a.id where b.id='".$row['bank_account']."'");
				$bank = mysql_fetch_array($bank_res);
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td>".$row['first_name']. " ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".$row['amount']."</td><td>".$row['receipt_no']."</td><td>".$row['percent_value']."</td><td>".$row['flat_value']."</td><td>".$row['date']."</td><td>".$row['account_no']." - ".$row['name']."</td><td>".$bank['account_no']." - ".$bank['name']."</td></tr>";
				$i++;
			}
		}
	}
	$content .= "</table>";
	export($format, $content);
}
//reports_header();
list_deposits($_GET['mem_no'], $_GET['name'], $_GET['product'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday'], $_GET['format'],$_GET['branch_id']);
//reports_footer();
?>
