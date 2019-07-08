<?php

if(!isset($_GET['format']))
echo("<head>
	<title>SHARES REPORT</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");

//GENERATE SHARES REPORT
function genSharesReport($year, $month, $mday, $format,$branch_id,$num_rows,$stat)
{
	$date = sprintf("%02d-%02d-%02d 23:59:59", $year, $month, $mday);
	
	$branch_ = ($branch_id=='all'||$branch_id=='')? "":"where branch_id='".$branch_id."'";
	$tot_shares = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_share from shares ".$branch_.""));
	$mem_res = @mysql_query("select id, mem_no, first_name, last_name from member ".$branch_." order by first_name, last_name asc");
	if ($tot_shares['tot_share'] > 0)
	{
		$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
		$branch = mysql_fetch_array($branch_res);
		$content .= "<center><p><font color=#00008b size=4pt><b>".$branch['branch_name']."</b></font></p></center>";
		$content .= "<center><font color=#00008b size=3pt><b>SHARES REPORT</b></font></center>";
		$content .= "<table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>";
		if($year == '')
			$content .= "<tr><td><font color=red>Select the date for your report!</font></td></tr>";
		else{
			$content .= "<tr class='headings'><th>Name</th><th>Member No.</th><th>No. of Shares</th><th>Value of Shares</th><th>Percentage</th><th>Dividends Receivable</th><th>Balance</th></tr>";
			$i=0;
			$tot_balance = 0;
			$tot_dividends = 0;
			$tot_totalval = 0;
			$tot_tot_mem_shares = 0; 
			while ($members = @mysql_fetch_array($mem_res))
			{
				$row = @mysql_fetch_array(@mysql_query("select share_value as shareval, max_share_percent as percentage from branch ".$branch_));
				$share_purchase = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_pur, sum(value) as value from shares where mem_id = ".$members['id']." and date <= '".$date."'"));
				$share_sale = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_sale from share_transfer where from_id = ".$members['id']." and date <= '".$date."'"));
				$bought = @mysql_fetch_array(@mysql_query("select sum(shares) as shares from share_transfer where to_id = ".$members['id']." and date <= '".$date."'"));
				$div_res = mysql_query("select sum(d.amount) as amount from dividends d join share_dividends s on d.share_dividend_id=s.id where d.mem_id=".$members['id']." and s.bank_account=0 and s.date <= '".$date."'");
				$div = mysql_fetch_array($div_res);
				$dividends = $div['amount'];

				$transact = $bought['shares'] - $share_sale['tot_sale'];
				$transacted_amt = $transact * $row['shareval'];

				$tot_mem_shares = $share_purchase['tot_pur'] - $share_sale['tot_sale'] + $bought['shares'];
				//$totalval = $tot_mem_shares * $row['shareval'];
				$totalval = $transacted_amt + $share_purchase['value'];

				$percentage = ($tot_mem_shares / $tot_shares[tot_share]) * 100;
				$percentage = sprintf("%.02f", $percentage);
				$balance = $totalval + $dividends;
				$color = ($i % 2 ==0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td>$members[first_name] $members[last_name]</td><td>$members[mem_no]</td><td align='center'>".number_format($tot_mem_shares, 2)."</td><td align='center'>".number_format($totalval, 2)."</td><td align='center'>$percentage %</td><td align='center'>".number_format($dividends, 2)."</td><td align='center'>".number_format($balance,2)."</td></tr>";
				
				$i++;
				$tot_balance += $balance;
				$tot_dividends += $dividends;
				$tot_totalval += $totalval;
				$tot_tot_mem_shares += $tot_mem_shares; 
			}
		}
		
		$content .= "<tr class='headings'><td colspan=3 align=right>".number_format($tot_tot_mem_shares,2)."</td><td align='right'>".number_format($tot_totalval, 2)."</td><td align='right' colspan=2>".number_format($tot_dividends, 2)."</td><td align='right'>".number_format($tot_balance, 2)."</td></tr>";
		$content .= "</table>";
		
	}
	
	else
		$content .= "<br><font color=red> No registered shares yet.</font><br>";
	export($format, $content);
}
//reports_header();
genSharesReport($_GET['year'], $_GET['month'], $_GET['mday'], $_GET['format'],$_GET['branch_id'],$_GET['num_rows'],$_GET['stat']);
//reports_footer();
?>
