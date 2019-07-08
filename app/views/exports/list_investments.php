<?php

if(!isset($_GET['format']))
echo("<head>
	<title>LIST OF INVESTMENTS</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");

function listInvestments($type, $contact, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday, $format, $branch_id)
{
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$content = "<center><p><font color=#00008b size=4pt><b>".$branch['branch_name']."</b></font></p></center>";
	$branch = ($branch_id=='all'||$branch_id=='')?NULL:"and r.branch_id=".$branch_id;
	
	$content .= "<center><font color=#00008b size=3pt><b>LIST OF INVESTMENTS</b></font></center>";

	$to_date = sprintf("%02d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
	$from_date = sprintf("%02d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	if (strtolower($type) == 'all')
	{
		$res = @mysql_query("select ac.id as acc_id, ac.account_no as account_no, ac.name as acc_name, r.quantity as quantity, r.id as rid, r.amount as amount, r.date as mdate, r.contact as contact  from accounts ac right join investments r on r.account_id = ac.id where r.date >='".$from_date."' and r.date <='".$to_date."' and r.contact like '%".$contact."%' ".$branch." order by r.date desc");
	}
	/****************************
	* SPECIFY ACCOUNT TYPE
	***************************/
	else
	{
		$res = @mysql_query("select ac.id as acc_id, ac.account_no as account_no, ac.name as acc_name, r.quantity as quantity, r.id as rid, r.amount as amount, r.date as mdate, r.contact as contact  from accounts ac join investments r on r.account_id = ac.id where r.date >='".$from_date."' and r.date <='".$to_date."' and r.account_id='".$type."' and r.contact like '%".$contact."%' ".$branch." order by r.date desc");
	}
	if (@mysql_num_rows($res) > 0)
	{
		
		$content .= "<table height=100 border='1' cellpadding='1' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>
		<tr class='headings'>
				        <th>ACCOUNT</th><th>CONTACT/ITEM</th><th>UNIT COST</th><th>QUANTITY</th><th>TOTAL COST</th><th>PURCHASE DATE</th><th>DISBURSEMENT A/C</th>
			    </tr>
			    ";
		$i=0;
		while ($fxrow = @mysql_fetch_array($res))
		{
			$bank = mysql_fetch_array(mysql_query("select b.bank, a.account_no, a.name from investments i join bank_account b on i.bank_account=b.id join accounts a on b.account_id=a.id where i.id='".$fxrow['rid']."'"));
			$int = mysql_fetch_array(mysql_query("select sum(amount) as amount from other_income where account_id='".$fxrow['acc_id']."'"));
			$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;
			$color = ($i%2 == 0) ? "lightgrey" : "white";
			//if (strtolower($type) == 'all' || strtolower($type) == 'cleared')
			//{
			$content .= "
				    <tr bgcolor=$color>
				        <td>".$fxrow[account_no] ."-" .$fxrow['acc_name']."</td><td>".$fxrow['contact']."</td><td>".number_format($fxrow['amount'])."</td><td>".$fxrow['quantity']."</td><td>".number_format($fxrow['amount']* $fxrow['quantity'])."</td><td>".$fxrow['mdate']."</td><td>".$bank['account_no']. " - ".$bank['bank']." ".$bank['name']."</td>
				    </tr>
				    ";		
			$total += $fxrow['amount']* $fxrow['quantity'];
			//}
		}

		$content .= "
				    <tr class='headings'>
				        <td colspan=4>TOTAL</td><td colspan=3>".number_format($total, 2)."</td>
				    </tr></table>";
	}
	else
		$content .= "<table height=100 border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center><tr><td><font color=red>No Investments found in your search options.</font><br></td></tr></table>";
	export($format, $content);
}
//reports_header();
listInvestments($_GET['account'], $_GET['contact']='', $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday'], $_GET['format'],$_GET['branch_id'])
//reports_footer();
?>
