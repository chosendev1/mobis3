<?php

if(!isset($_GET['format']))
echo("<head>
	<title>LIST OF EXPENSES</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");

//EXPENSE REPORT
function listExpenses($type, $contact, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday, $format,$branch_id)
{
	$branch_res = mysql_query("select * from branch");
	$branch = mysql_fetch_array($branch_res);
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:"and r.branch_id=".$branch_id;
	$content .= "<center><p><font color=#00008b size=4pt><b>".$branch['branch_name']."</b></font></p></center>";
	$content .= "<center><font color=#00008b size=3pt><b>EXPENSES</b></font></center>";
	$content .= "<table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>";
	$to_date = sprintf("%02d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
	$from_date = sprintf("%02d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	if (strtolower($type) == 'all')
	{
		$res = mysql_query("select ac.id as acc_id, ac.account_no, ac.name as acc_name, r.id as rid, r.bank_account, r.amount as amount, r.date as mdate, r.voucher_no,r.contact as contact,r.details as details from accounts ac right join expense r on r.account_id = ac.id where r.date >='".$from_date."' and r.date <='".$to_date."'  and r.contact like '%".$contact."%' ".$branch_." order by r.date desc");
		
	}
	/****************************
	* SPECIFY ACCOUNT TYPE
	***************************/
	else
	{
		$res = mysql_query("select ac.id as acc_id, ac.account_no, ac.name as acc_name, r.id as rid, r.bank_account, r.amount as amount, r.date as mdate, r.voucher_no,r.contact as contact,r.details as details from accounts ac join expense r on r.account_id = ac.id where r.date >='".$from_date."' and r.date <='".$to_date."' and r.account_id='".$type."'  and r.contact like '%".$contact."%'  ".$branch_." order by r.date desc");
	}
	if (@mysql_num_rows($res) > 0)
	{
		$content .= "<tr class='headings'>
			        <th>No.</th><th>Account</th><th>Amount</th><th>Voucher No.</th><th>Date</th><th>Source Bank Account</th><th>Contact Person</th><th>Details</th>
			    </tr>
			    ";
		$i=1; $total=0;
		while ($fxrow = @mysql_fetch_array($res))
		{
			$bank = mysql_fetch_array(mysql_query("select a.account_no, a.name, b.bank from bank_account b join accounts a on b.account_id=a.id where b.id='".$fxrow['bank_account']."'"));
			if (strtolower($type) == 'all' || strtolower($type) == 'cleared'|| $type !="")
			{
			$color = ($i%2==0) ? "lightgrey" : "white";
			$content .= "
				    <tr bgcolor=$color>
				        <td>$i.</td><td>$fxrow[account_no] - $fxrow[acc_name]</td><td>".number_format($fxrow[amount], 2)."</td><td>$fxrow[voucher_no]</td><td>$fxrow[mdate]</td><td>$bank[account_no] - $bank[bank] $bank[name]</td><td>$fxrow[contact]</td><td>$fxrow[details]</td>
				    </tr>
				    ";
					$total += $fxrow[amount];
					$i++;
			}
		}
		$content .= "<tr class='headings'><td colspan='2'>Total</td><td>".number_format($total, 2)."</td><td colspan='7'></td></tr>";
		$content .= "</table>";
	}
	else
		$content .= "<font color=red>No Expense found in your search options.</font><br>";
	export($format, $content);
}
//reports_header();
listExpenses($_GET['account'], $_GET['contact']='', $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday'], $_GET['format'],$_GET['branch_id'])
//reports_footer();
?>
