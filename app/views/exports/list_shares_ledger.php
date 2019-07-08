<?php

if(!isset($_GET['format']))
echo("<head>
	<title>SHARES LEDGER REPORT</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");

//SHOW THE SHARES LEDGER
function sharesLedger($mem_id, $type, $format)
{
// Direct purchases, in-ward transfers and out-ward transfers
	if($type == 'mem_no'){
		$mem_res = mysql_query("select * from member where mem_no='".$mem_id."'");
		if(mysql_numrows($mem_res) == 0){
			$content .= "<font color=red>No member identified by ".$mem_id." Number</font>";
		}
		$mem = mysql_fetch_array($mem_res);
		$mem_id = $mem['id'];
	}
	$direct = @mysql_query("select id, date, shares, value, receipt_no from shares where mem_id = $mem_id and receipt_no != '' order by date asc");
	$inward = @mysql_query("select id, date, shares, value from share_transfer where to_id = $mem_id order by date asc");
	$outward = @mysql_query("select id, date, shares, value from share_transfer where from_id = $mem_id order by date asc");
	$div_res = mysql_query("select d.id,  s.date, d.amount from dividends d join share_dividends s on d.share_dividend_id=s.id where d.mem_id=$mem_id and s.bank_account=0 order by s.date asc");
	$mem = @mysql_fetch_array(@mysql_query("select first_name, last_name, mem_no from member where id = $mem_id"));
	$tot_mem_shares = 0; 
	$found_shares = 0;
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$content = "<center><p><font color=#00008b size=4pt><b>".$branch['branch_name']."</b></font></p></center>";
	$content .= "<center><font color=#00008b size=3pt><b>SHARES LEDGER</b></font><br>Name: ".$mem[first_name]." ". $mem['last_name']."<br>Member No: " .$mem[mem_no]."</center>";
	$content .= "<table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='70%' id='AutoNumber2' align=center>
	<tr class='headings'><th>Date</th><th>Type of Transaction</th><th>No. of Shares</th><th>Value</th><th>Total Shares</th><th>Dividends</th><th>Balance</th></tr>";
	$balance = 0;
	if (@mysql_num_rows($direct) > 0)
	{
		$found_shares += 1; $i = 0;
		while ($drow = @mysql_fetch_array($direct))
		{
			$balance += $drow[value];
			$tot_mem_shares += $drow[shares];
			$color = ($i % 2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color>
				    <td>$drow[date]</td><td>Direct Purchase<br>RCPT: ".$drow['receipt_no']."</td><td align='center'>$drow[shares]</td><td align='center'>$drow[value]</td><td align='center'>$tot_mem_shares</td><td>--</td><td>".$balance."</td>
				    </tr>
				    ";
					$i++;
		}
	}
	if (@mysql_num_rows($inward) > 0)
	{
		$found_shares += 1;
		$i = 0;
		while ($inrow = @mysql_fetch_array($inward))
		{
			$balance += $inrow[value];
			$tot_mem_shares += $inrow[shares];
			$color = ($i % 2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color>
				    <td>$inrow[date]</td><td>Inward transfer</td><td align='center'>$inrow[shares]</td><td align='center'>".number_format($inrow[value], 2)."</td><td align='center'>$tot_mem_shares</td><td>--</td><td>".number_format($balance, 2)."</td>
				    </tr>
				    ";
					$i++;
		}
	}
	if (@mysql_num_rows($outward) > 0)
	{
		$found_shares += 1;
		$i=0;
		while ($outrow = @mysql_fetch_array($outward))
		{
			$balance -= $outrow['value'];
			$tot_mem_shares -= $outrow[shares];
			$color = ($i % 2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color>
				    <td>$outrow[date]</td><td>Outward transfer</td><td align='center'>$outrow[shares]</td><td align='center'>".number_format($outrow[value], 2)."</td><td align='center'>$tot_mem_shares</td><td>--</td><td>".number_format($balance, 2)."</td>
				    </tr>
			            ";
					$i++;
		}
	}
	if (@mysql_num_rows($div_res) > 0)
	{
		$found_shares += 1;
		$i=0;
		while ($div = @mysql_fetch_array($div_res))
		{
			$balance += $div['amount'];
			//$tot_mem_shares -= intval($outrow[shares]);
			$color = ($i % 2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color>
				    <td>$div[date]</td><td>Dividends</td><td align='center'>--</td><td align='center'>--</td><td align='center'>$tot_mem_shares</td><td>".$div['amount']."</td><td>".$balance."</td></td>
				    </tr>
			            ";
					$i++;
		}
	}

	$content .= "</table>";
	if ($found_shares < 1)
		$content = "<tr bgcolor=lightgrey><td><font color='red'>No shares activity yet registered for $mem[first_name] $mem[last_name]</font><br></td></tr></table>";
	export($format, $content);
}
//reports_header();
sharesLedger($_GET['mem_id'], $_GET['type'], $_GET['format']);
//reports_footer();
?>
