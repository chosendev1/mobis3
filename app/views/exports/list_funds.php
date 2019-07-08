<?php

if(!isset($_GET['format']))
echo("<head>
	<title>SAVINGS PRODUCT ACCOUNTS FOR MEMBERS</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");

//LIST SAVINGS PRODUCT ACCOUNTS FOR MEMBERS
function listFund($account, $contact, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday,$format,$branch_id)
{
	
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:"and branch_id=".$branch_id;
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$content = "<center><p><font color=#00008b size=4pt><b>".$branch['branch_name']."</b></font></p></center>";
	$content .= "<center><font color=#00008b size=3pt><b>LIST OF OTHER CAPITAL FUNDS</b></font></center><br>";
	
	
	if($from_year ==''){
		$content .="<table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>
		<tr bgcolor=lightgrey><td><font color=red>Select the period for the capital funds!</font></td></tr></table>";
		$resp->AddAssign("display_div", "innerHTML", $content);
		return $resp;
	}
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59",  $to_year, $to_month, $to_mday);
	if ($account == '')
	{
		$res = @mysql_query("select r.bank_account, ac.id as acc_id, ac.name as acc_name, ac.account_no as account_no, r.id as rid, r.amount as amount, r.contact as contact, r.date as mdate, r.contact as contact, r.receipt_no, r.cheque_no, r.mode from accounts ac right join other_funds r on r.account_id = ac.id where r.date>= '".$from_date."' and r.date <= '".$to_date."' and r.contact like '%".$contact."%' ".$branch_." order by r.date desc");
	}else{
		$res = @mysql_query("select r.bank_account, ac.id as acc_id, ac.name as acc_name, ac.account_no as account_no, r.id as rid, r.amount as amount, r.contact as contact, r.date as mdate, r.contact as contact, r.receipt_no, r.cheque_no, r.mode from accounts ac right join other_funds r on r.account_id = ac.id where r.date>= '".$from_date."' and r.date <= '".$to_date."' and r.account_id='".$account."' and r.contact like '%".$contact."%' ".$branch_." order by r.date desc");
	}
	
	if (@mysql_num_rows($res) > 0)
	{
		$content .= "
			      <table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>
			    <tr class='headings'>
			        <th>Date</th><th>Account</th><th>Amount</th><th>Receipt No./Cheque No.</th><th>Payment Mode</th><th>Contact Person</th><th>Bank Account</th>
			    </tr>
			    ";
		$i=0;
		$balance=0;
		while ($fxrow = @mysql_fetch_array($res))
		{
			//if (strtolower($type) == 'all' || strtolower($type) == 'cleared')
			//{
			$mode = ($fxrow['mode'] == 'cash') ? $fxrow['mode'] : 'Offset from Savings' ;
			$color = ($i%2 == 0) ? "lightgrey" : "white";
			$bank = mysql_fetch_array(mysql_query("select b.bank as bank, a.name as name, a.account_no from bank_account b join accounts a on b.account_id=a.id where b.id='".$fxrow['bank_account']."'"));
			$content .= "
				    <tr bgcolor=$color>
				        <td>".$fxrow['mdate']."</td><td>$fxrow[account_no] - $fxrow[acc_name]</td><td>".number_format($fxrow[amount], 2)."</td><td>$fxrow[receipt_no] $fxrow[cheque_no]</td><td>$mode</td><td>$fxrow[contact]</td><td>".$bank['account_no'] ."-". $bank['bank']." ".$bank['name']."</td>
				    </tr>
				    ";	
					$balance += $fxrow['amount'];
			//}
			$i++;
		}
		$content .= "<tr class='headings'><th colspan=2>Total </th><th colspan=6>".number_format($balance, 2)."</th></tr></table>";
	}
	else
		$content .= "<table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center><tr><td><font color=red>No Fund registered yet.</font><br></td></tr></table>";
	
	export($format, $content);
}

listFund($_GET['account'], $_GET['contact'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday'], $_GET['format'], $_GET['branch_id']);


?>
