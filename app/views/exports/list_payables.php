<?php


if(!isset($_GET['format']))
echo("<head>
	<title>SAVINGS PLUS v 2.0 : SAVINGS PRODUCT ACCOUNTS FOR MEMBERS</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");

function listPayables($type, $account, $contact, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday, $format,$branch_id)
{
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:"and p.branch_id=".$branch_id;
	$content .= "<center><p><font color=#00008b size=4pt><b>".$branch['branch_name']."</b></font></p></center>";
	$content .= "<center><font color=#00008b size=3pt><b>LIST OF ".strtoupper($type)." PAYABLES</b></font></center>
	  <table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='80%' id='AutoNumber2' align=CENTER ALIGN=LEFT>";
	$from_date = sprintf("%02d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%02d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
	if($from_year == ''){
		$content .= "<tr bgcolor=lightgrey><td><font color=red>Select the period for your report</font></td></tr></table>";
		echo($content);
		return 0;
	}
	$where_account = ($account == '') ? "" : " and p.account_id='".$account."' ";
	if (strtolower($type) == 'all')
	{
		$res = @mysql_query("select ac.account_no, p.date, ac.id as acc_id, ac.name as acc_name, p.id as pid, p.amount as amount, p.maturity_date as mdate, p.vendor as creditor, p.status as status from accounts ac join payable p on p.account_id = ac.id where p.date >='".$from_date."' and p.date <= '".$to_date."' and p.vendor like '%".$contact."%' ".$where_account." ".$branch_." order by p.vendor,p.maturity_date desc");
	}
	elseif (strtolower($type) == 'pending')
	{
		$res = @mysql_query("select ac.account_no, p.date, ac.id as acc_id, ac.name as acc_name, p.id as pid, p.amount as amount, p.maturity_date as mdate, p.vendor as creditor, p.status as status from accounts ac join  payable p on p.account_id = ac.id where lower(p.status) = 'pending' and p.date >='".$from_date."' and p.date <= '".$to_date."' and p.vendor like '%".$contact."%' ".$where_account." ".$branch_." order by p.vendor,p.maturity_date desc");
	}
	elseif (strtolower($type) == 'cleared')
	{
		$res = @mysql_query("select ac.id as acc_id, ac.account_no, ac.name as acc_name, p.id as pid, p.amount as amount, p.maturity_date as mdate, p.date as date, p.vendor as creditor, p.status as status from accounts ac join payable p on p.account_id = ac.id where lower(p.status) = 'cleared' and p.date >='".$from_date."' and p.date <= '".$to_date."' and p.vendor like '%".$contact."%' ".$where_account." ".$branch_." order by p.vendor,p.maturity_date desc");
	}
	if (@mysql_num_rows($res) > 0)
	{
		$content .="<tr class='headings'>
			 <th>Account</th><th>Creditor</th><th>Initial Amount</th><th>Maturity Date</th><th>Aquisition Date</th><th>Status</th><th>Balance</th>
			 </tr>
			    ";
		$i=0;
		$total_amt =0;
		$total_bal=0;
		while ($fxrow = @mysql_fetch_array($res))
		{
			$paid=mysql_fetch_array(mysql_query("select sum(amount) as amount from payable_paid where payable_id='".$fxrow['pid']."'"));
			$paid_amt = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
			$balance = $fxrow['amount'] - $paid_amt;
			$total_amt += $fxrow['amount'];
			$total_bal += $balance;
			$color = ($i%2 == 0) ? "lightgrey" : "white";
		
			$content .= "
				    <tr bgcolor=$color>
				    	<td>$fxrow[account_no] - $fxrow[acc_name]</td><td>$fxrow[creditor]</td><td>".number_format($fxrow[amount], 2)."</td><td>$fxrow[mdate]</td><td>$fxrow[date]</td><td>$fxrow[status]<td>".number_format($balance, 2)."</td>
				    </tr>
				    ";
					$i++;
		//	}
		}
		$content .= "<tr class='headings'><th colspan=2>Total</th><th colspan=4>".number_format($total_amt, 2)."</th><th colspan=1>".number_format($total_bal, 2)."</th></tr></table>";
	}
	else
		$content .= "<tr bgcolor=lightgrey><td><center><font color=red>No payables registered yet.</font><br></center></td></tr></table>";
	
	export($format, $content);
}

listPayables($_GET['type'], $_GET['account'], $_GET['contact'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday'], $_GET['format'],$_GET['branch_id']);
//reports_footer();

?>
