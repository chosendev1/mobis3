<?php

if(!isset($_GET['format']))
echo("<head>
	<title>LIST OF RECEIVABLES</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");

//LIST SAVINGS PRODUCT ACCOUNTS FOR MEMBERS
function listReceivables($type, $account, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday,$format,$branch_id)
{
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:"and branch_id=".$branch_id;
	$content .= "<center><p><font color=#00008b size=4pt><b>".$branch['branch_name']."</b></font></p></center>";
	$content .="<center><font color=#00008b size=3pt><B>LIST OF ".strtoupper($type)." RECEIVABLES</B></font></center>";
	if($from_year ==''){
		$content .="<table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>
		<tr bgcolor=lightgrey><td><font color=red>Select the period for the Receivables!</font></td></tr></table>";
		echo($content);
		return 0;
	}
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59",  $to_year, $to_month, $to_mday);
	if($from_year ==''){
		$content .="
		<tr bgcolor=lightgrey><td><font color=red>Select the period for the income!</font></td></tr></table>";
		$resp->assign("display_div", "innerHTML", $content);
		return $resp;
	}
	$from_date = sprintf("%02d-%02d-%02d", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%02d-%02d-%02d", $to_year, $to_month, $to_mday);
	$selectedAccount = ($account=='all') ? NULL: "and r.account_id='".$account."'";
	if (strtolower($type) == 'all')
	{
		$res = @mysql_query("select ac.account_no as account_no, ac.id as acc_id, ac.name as acc_name, r.id as rid, r.amount as amount, r.maturity_date as mdate, r.status as status, r.contact as contact, r.details as details from accounts ac, receivable r where r.account_id = ac.id ".$selectedAccount." ".$branch_." order by r.maturity_date desc");
	}
	elseif (strtolower($type) == 'pending')
	{
		$res = @mysql_query("select ac.account_no as account_no, ac.id as acc_id, ac.name as acc_name, r.id as rid, r.amount as amount, r.maturity_date as mdate, r.status as status, r.contact as contact, r.details as details from accounts ac join receivable r on r.account_id = ac.id where lower(r.status) = 'pending' and r.maturity_date >='".$from_date."' and r.maturity_date <= '".$to_date."' ".$selectedAccount." ".$branch_." order by r.maturity_date desc");
	}
	elseif (strtolower($type) == 'cleared')
	{
		$res = @mysql_query("select ac.id as acc_id, ac.account_no as account_no, ac.name as acc_name, r.id as rid, r.amount as amount, r.maturity_date as mdate, r.status as status, r.contact as contact, r.details as details from accounts ac join receivable r on r.account_id = ac.id where lower(r.status) = 'cleared' and r.maturity_date >='".$from_date."' and r.maturity_date <= '".$to_date."' ".$selectedAccount." ".$branch_." order by r.maturity_date desc");
	}

	
	
	if (@mysql_num_rows($res) > 0)
	{
		
		$content .= "<table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='95%' id='AutoNumber2' align=CENTER>
			    <tr class='headings'>";
				
			        $content .="<th>No.</th><th>Account</th><th>Amount</th><th>Maturity Date</th><th>Balance</th><th>Status</th><th>Contact Person</th><th>Details</th>
			    </tr>
			    ";
			    
		$i=1; $total=0;
		while ($fxrow = @mysql_fetch_array($res))
		{
			$color = ($i %2 ==0) ? "lightgrey" : "white";
			$col = mysql_fetch_array(mysql_query("select sum(amount) as amount from collected where receivable_id='".$fxrow['rid']."'"));
			$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
			$balance = $fxrow['amount'] - $col_amt;
			$content .= "
				    <tr bgcolor=$color><td>$i.</td>
				    	<td>$fxrow[account_no] - $fxrow[acc_name]</td><td>".number_format($fxrow[amount], 2)."</td><td>$fxrow[mdate]</td><td>".number_format($balance, 2)."</td><td>$fxrow[status]</td><td>$fxrow[contact]</td><td>$fxrow[details]</td
				    </tr>
				    ";
			$i++;
		}
		$content .= "</table>";
	}
	else
		
		$content .= "<table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='70%' id='AutoNumber2' align=center><tr><td><font color=red>No Expense registered yet.</font><br></td></tr></table>";
	
	export($format, $content);
}

listReceivables($_GET['account'], $_GET['type'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday'], $_GET['format'],$_GET['branch_id']);


?>
