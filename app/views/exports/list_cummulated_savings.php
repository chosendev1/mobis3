<?php


if(!isset($_GET['format']))
echo("<head>
	<title>CUMMULATED SAVINGS REPORT</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");
function cummulated_savings($year, $month, $mday, $format,$branch_id)
{
	$date = sprintf("%d-%02d-%02d 23:59:59", $year, $month, $mday);
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:"branch_id=".$branch_id;
	$mem_res = @mysql_query("select id as mem_id, mem_no, first_name, last_name from member ".$branch_." order by first_name, last_name asc");
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$content .= "<center><p><font color=#00008b size=4pt><b>".$branch['branch_name']."</b></font></p></center>";
	if (@mysql_num_rows($mem_res) > 0)
	{	
	$content .= "
		    <center><font color=#00008b size=3pt><b>MEMBERS CUMMULATED SAVINGS REPORT</b></font></center>
		    <table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='70%' id='AutoNumber2' align=center>
		    <tr class='headings'>
			<th></th><th>Name</th><th>Member No</th><th>Total Deposits</th><th>Total Withdrawals</th><th>Total Interest</th><th>Total Fees</th><th>Other Deductions</th><th>Cummulative Savings</th>
		    </tr>
		    ";
	$x = 1;
	$sub_net_save = 0;
	$sub_tot_fees = 0;
	$sub_tot_int = 0;
	$sub_tot_with = 0;
	$sub_tot_deduc = 0;
	$sub_tot_savings = 0;
	while ($mem_row = @mysql_fetch_array($mem_res))
	{
				$tot_savings = 0;
			$tot_shares=0;
			$tot_with = 0;
			$tot_fees = 0;
			$tot_int = 0;
			$tot_inc = 0;
			$tot_pay = 0;
			$tot_deduc = 0;
			$cumm_save = 0;
			$net_save = 0;
			$mem_ac = @mysql_query("select id as mem_acct_id from mem_accounts where mem_id = $mem_row[mem_id]");
			if (@mysql_num_rows($mem_ac) > 0)
			{
				while ($mem_ac_row = @mysql_fetch_array($mem_ac))
				{
					$drow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_savings, sum(flat_value + percent_value) as charges from deposit where bank_account != 0 and memaccount_id = $mem_ac_row[mem_acct_id] and date <= '".$date."'"));
					$wrow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_with, sum(flat_value + percent_value) as charges from withdrawal where memaccount_id = $mem_ac_row[mem_acct_id] and date <= '".$date."'"));
					$mrow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_fees from monthly_charge where memaccount_id = $mem_ac_row[mem_acct_id] and date <= '".$date."'"));
					$irow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_int from save_interest where memaccount_id = $mem_ac_row[mem_acct_id] and date <= '".$date."'"));
					$incrow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_inc from other_income where mode = '$mem_ac_row[mem_acct_id]' and date <= '".$date."'"));
					$prow = @mysql_fetch_array(@mysql_query("select sum(princ_amt + int_amt) as tot_pay from payment where mode = '$mem_ac_row[mem_acct_id]' and date <= '".$date."'"));
					$shares = mysql_fetch_array(mysql_query("select sum(value) as amount from shares where mode = '$mem_ac_row[mem_acct_id]' and date <= '".$date."'"));
					
				
					$tot_savings += ($drow[tot_savings] != NULL)? $drow[tot_savings] : 0 ;
					$tot_fees += ($mrow[tot_fees] != NULL)? $mrow[tot_fees] : 0 ;
					$tot_fees += ($drow[charges]  != NULL)? $drow[charges] : 0 ;
					$tot_fees += ($wrow[charges] != NULL)? $wrow[charges] : 0 ;
					$tot_with += ($wrow[tot_with] != NULL)? $wrow[tot_with] : 0;
					$tot_int += ($irow[tot_int] != NULL)? $irow[tot_int] : 0 ;
					$tot_shares = ($shares['amount'] != NULL)? $shares['amount'] : 0 ;
					$tot_deduc += ($incrow[tot_inc] != NULL)? $incrow[tot_inc] : 0 ;
					$tot_deduc += ($prow[tot_pay] != NULL)? $prow[tot_pay] : 0;
					$tot_deduc += $tot_shares;
					//tot_deduc += $tot_inc + $tot_pay;
					
					$net_save = $tot_savings + $tot_int - $tot_fees - $tot_with - $tot_deduc - $tot_shares;
					$cumm_save += $net_save;
				} // close while mem_ac
			} // close if mem_ac		$color = ($x%2 == 0) ? "lightgrey" : "white";
		$content .= "
		    <tr bgcolor=$color>
			<td>$x.</td><td>$mem_row[first_name] $mem_row[last_name]</td><td>".$mem_row['mem_no']."</td><td align=center>".number_format($tot_savings, 2)."</td><td align=center>".number_format($tot_with, 2)."</td><td align=center>".number_format($tot_int, 2)."</td><td align=center>".number_format($tot_fees, 2)."</td><td align=center>".number_format($tot_deduc, 2)."</td><td align=center>".number_format($net_save)."</td>
		    </tr>
		    ";
			$sub_net_save += $net_save;
			$sub_tot_fees += $tot_fees;
			$sub_tot_int += $tot_int;
			$sub_tot_with += $tot_with;
			$sub_tot_deduc += $tot_deduc;
			$sub_tot_savings += $tot_savings;
		$x++;
	} 	// close while mem_row
	$content .= "
		    <tr class='headings'>
			<td colspan=3><b>Total</b></td><td align=center>".number_format($sub_tot_savings, 2)."</td><td align=center>".number_format($sub_tot_with, 2)."</td><td align=center>".number_format($sub_tot_int, 2)."</td><td align=center>".number_format($sub_tot_fees, 2)."</td><td align=center>".number_format($sub_tot_deduc, 2)."</td><td align=center> ".number_format($sub_net_save, 2)."</td>
		    </tr>
		    ";
	} // close if mem_res
	else
		$content = "<font color='red'>No members have been registered yet.</font><br>";
	$content .= "</table>";
	export($format, $content);
}
//reports_header();
cummulated_savings($_GET['year'], $_GET['month'], $_GET['mday'], $_GET['format'],$_GET['branch_id']);
//reports_footer();
?>
