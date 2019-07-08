<?php

if(!isset($_GET['format']))
echo("<head>
	<title>INDIVIDUAL SAVINGS REPORT</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");

function ind_savings_summary($mem_no1, $mem_no2, $from_year, $from_month, $to_year, $to_month, $format)
{
	$calc = new Date_Calc();
	$start_date = date('Y-m-d 00:00:00', strtotime($calc->endOfMonthBySpan(0, intval($from_month), intval($from_year), '%Y-%m-%d')));
	$final_date = date('Y-m-d 23:59:59', strtotime($calc->endOfMonthBySpan(0, intval($to_month), intval($to_year), '%Y-%m-%d')));
	$start_month = intval($from_month); $end_month = intval($to_month);
	$start_year = intval($from_year); $end_year = intval($to_year);
	$total_saved = 0; $total_with = 0; $total_int = 0; $total_fees = 0;
	$cumm_save = 0;
	if($mem_no1<>'' && $mem_no2<>'')
		$mem_no = $mem_no2;
	elseif($mem_no1<>'')
		$mem_no = $mem_no1;
	else
		$mem_no = $mem_no2;

	$mem_ac = mysql_query("select ma.id as mem_acct_id from mem_accounts ma join member m on ma.mem_id=m.id where m.mem_no = '".$mem_no."'");
	
	if (@mysql_num_rows($mem_ac) > 0)
	{
		while ($mem_ac_row = @mysql_fetch_array($mem_ac))
		{
			$drow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_savings from deposit where bank_account != 0 and memaccount_id = $mem_ac_row[mem_acct_id] and date <= '".$start_date."'"));
			$wrow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_with from withdrawal where memaccount_id = $mem_ac_row[mem_acct_id] and date <= '".$start_date."'"));
			$mrow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_fees from monthly_charge where memaccount_id = $mem_ac_row[mem_acct_id] and date <= '".$start_date."'"));
			$irow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_int from save_interest where memaccount_id = $mem_ac_row[mem_acct_id] and date <= '".$start_date."'"));
			$prow1 = @mysql_fetch_array(@mysql_query("select sum(princ_amt + int_amt) as tot_pay from payment where mode = '$mem_ac_row[mem_acct_id]' and date <= '".$start_date."'"));
			$incow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_inc from other_income where mode = '$mem_ac_row[mem_acct_id]' and date <= '".$start_date."'"));
			$c1row = @mysql_fetch_array(@mysql_query("select sum(w.percent_value + w.flat_value) as charge from  withdrawal w  join mem_accounts ma on w.memaccount_id=ma.id where  w.date <='".$start_date."'  and w.memaccount_id='".$mem_ac_row['mem_acct_id']."'"));
			
			$c2row = @mysql_fetch_array(@mysql_query("select sum(d.percent_value + d.flat_value) as charge from  deposit d  join mem_accounts ma on d.memaccount_id=ma.id where  d.date <= '".$start_date."' and d.memaccount_id='".$mem_ac_row['mem_acct_id']."'"));


           	$total_saved = isset($drow1[tot_savings])? intval($drow1[tot_savings]) : 0 ;
            $total_fees = isset($mrow1[tot_fees])? intval($mrow1[tot_fees]) : 0 ;
            $total_with = isset($wrow1[tot_with])? intval($wrow1[tot_with]) : 0 ;
            $total_int = isset($irow1[tot_int])? intval($irow1[tot_int]) : 0 ;
			$total_pay = isset($prow1[tot_pay])? intval($prow1[tot_pay]) : 0 ;
			$total_inc = isset($incowl['tot_inc']) ? intval($incowl['tot_inc']) : 0;
			$dcharge_amt = isset($c1row[charge])? intval($c1row[charge]) : 0 ;
			$wcharge_amt = isset($c2row[charge])? intval($c2row[charge]) : 0 ;
			$total_fees += $dcharge_amt + $wcharge_amt;
			$total_fees += $dcharge_amt + $wcharge_amt;
            $net_save = ($total_saved + $total_int) - ($total_fees + $total_with + $total_inc + $total_pay);
		    $cumm_save += $net_save;
		}
	}
	else
	{
		$content .= "<font color='red'>No savings accounts yet registered for this member.</font><br>";
		$resp->AddAssign("display_div", "innerHTML", $content);
		return $resp;
	}
	$mem_row = @mysql_fetch_array(@mysql_query("select first_name, mem_no, last_name from member where mem_no = '$mem_no'"));
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$content .= "<center><p><font color=#00008b size=4pt><b>".$branch['branch_name']."</b></font></p></center>";
	$content .= "
		   <center><font color=#00008b size=3pt>SAVINGS SUMMARY REPORT</font><br> 
		   Name: <b>".$mem_row[first_name]." ". $mem_row[last_name]. " </b><br>
		   Memebr No: <b>".$mem_row['mem_no']."</b><br>
		   Period: <i><b>$start_date</b> TO <b>$final_date</b></i></CENTER>
		   
		    <table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='70%' id='AutoNumber2' align=center>
		    <tr class='headings'>
			<th>Period</th><th>Total Deposits</th><th>Total Withdrawals</th><th>Total Interest</th><th>Total Fees</th><th>Other Deductions</th><th>Cummulative Savings</th>
		    </tr>
		    <tr bgcolor=lightgrey>
			<td>Before $start_year - ".$calc->getMonthAbbrname($start_month)."</td><td align=center>".number_format($total_saved, 2)."</td><td align=center>".number_format($total_with, 2)."</td><td align=center>".number_format($total_int, 2)."</td><td align=center>".number_format($total_fees, 2)."</td><td align=center>".number_format($total_deduc, 2)."</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
	$x = 1;
	//$mem_ac2 = @mysql_query("select ma.id as mem_acct_id from mem_accounts ma join member m on ma.mem_id=m.id where m.mem_no = $mem_no");
	//$mem_ac2_row = @mysql_fetch_array($mem_ac2);
	while (intval(strtotime($start_date)) < intval(strtotime($final_date)))
	{
		 if (intval($start_month) == 12)
		{
			$start_month = 1; $start_year = $start_year + 1;
		}
		else
		{
			$start_month = $start_month + 1;
		}
		//$x++;
		$start_date = sprintf("%d-%02d", $start_year, $start_month) . "-01";
		$end_date = date('Y-m-d', strtotime($calc->endOfMonthBySpan(0, intval($start_month), intval($start_year))));
	
		$tot_savings = 0; $tot_int = 0; $tot_with = 0; $tot_fees = 0;
		$mem_ac2 = @mysql_query("select ma.id as mem_acct_id from mem_accounts ma join member m on ma.mem_id=m.id where m.mem_no = $mem_no");
		//while ($mem_ac2_row = @mysql_fetch_array($mem_ac2))
		//{
			$mem_ac2_row = @mysql_fetch_array($mem_ac2);
			$drow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_savings from deposit where bank_account != 0 and date >= '".$start_date."' and date <='".$end_date."' and memaccount_id='".$mem_ac2_row['mem_acct_id']."'"));
			$wrow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_with from withdrawal where date >= '".$start_date."' and date <='".$end_date."' and memaccount_id='".$mem_ac2_row['mem_acct_id']."'"));
			$mrow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_fees from monthly_charge where date >= '".$start_date."' and date <='".$end_date."' and memaccount_id='".$mem_ac2_row['mem_acct_id']."'"));
			$irow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_int from save_interest where date >= '".$start_date."' and date <='".$end_date."' and memaccount_id='".$mem_ac2_row['mem_acct_id']."'"));
			$incrow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_inc from other_income where date >= '".$start_date."' and date <='".$end_date."' and mode='".$mem_ac2_row['mem_acct_id']."'"));
			$payrow = @mysql_fetch_array(@mysql_query("select sum(princ_amt + int_amt) as tot_pay from payment where date >= '".$start_date."' and date <='".$end_date."' and mode='".$mem_ac2_row['mem_acct_id']."'"));
			$c1row = @mysql_fetch_array(@mysql_query("select sum(w.percent_value + w.flat_value) as charge from  withdrawal w  join mem_accounts ma on w.memaccount_id=ma.id where  w.date >= '".$start_date."' and w.date <= '".$end_date."' and w.memaccount_id='".$mem_ac2_row['mem_acct_id']."'"));
			
			$c2row = @mysql_fetch_array(@mysql_query("select sum(d.percent_value + d.flat_value) as charge from  deposit d  join mem_accounts ma on d.memaccount_id=ma.id where  d.date >= '".$start_date."' and d.date <= '".$end_date."' and d.memaccount_id='".$mem_ac2_row['mem_acct_id']."'"));
			if (isset($drow[tot_savings]) || isset($wrow[tot_with]) || isset($mrow[tot_fees]) || isset($irow[tot_int]) || isset($c1row[charge]) || isset($c2row[charge]) || isset($incrow[tot_inc]) || isset($payrow[tot_pay]))
			{
				$tot_savings = isset($drow[tot_savings])? intval($drow[tot_savings]) : 0 ;
				$tot_fees = isset($mrow[tot_fees])? intval($mrow[tot_fees]) : 0 ;
				$tot_with = isset($wrow[tot_with])? intval($wrow[tot_with]) : 0 ;
				$tot_int = isset($irow[tot_int])? intval($irow[tot_int]) : 0 ;
				$tot_inc = isset($incrow[tot_inc])? intval($incrow[tot_inc]) : 0 ;
				$tot_pay = isset($payrow[tot_pay])? intval($payrow[tot_pay]) : 0 ;
				$tot_charge1 = isset($c1row[charge])? intval($c1row[charge]) : 0 ;
				$tot_charge2 = isset($c2row[charge])? intval($c2row[charge]) : 0 ;
				$tot_fees += $tot_charge1 + $tot_charge2; 
				$tot_deduc += $tot_pay + $tot_inc;
				$net_save = ($tot_savings + $tot_int) - ($tot_fees + $tot_with + $tot_deduc);
				$cumm_save += $net_save;
			}
			$color = ($x%2 == 0) ? "lightgrey" : "white";
			if ($tot_savings > 0 || $tot_with > 0 || $tot_fees > 0 || $tot_int > 0 || $tot_deduc > 0)
			{	
				$content .= "
					<tr bgcolor=$color>
				<td>$start_year - ".$calc->getMonthAbbrname($start_month)."</td><td align=center>".number_format($tot_savings, 2)."</td><td align=center>".number_format($tot_with, 2)."</td><td align=center>".number_format($tot_int, 2)."</td><td align=center>".number_format($tot_fees, 2)."</td><td align=center>".number_format($tot_deduc, 2)."</td><td align=center>".number_format($cumm_save, 2)."</td>
			    </tr>
			    ";
				$x++;
			}
			/*
			if (intval($start_month) == 12)
			{
				$start_month = 1; $start_year += 1;
			}
			else
			{
				$start_month += 1;
			}
			$x++;
			$start_date = sprintf("%d-%02d", $start_year, $start_month) . "-01";
			$end_date = date('Y-m-d', strtotime($calc->endOfMonthBySpan(0, intval($start_month), intval($start_year), '%Y-%m-%d')));
			*/
		//}
		
	} 	
	$content .= "</table>";
	if ($cumm_save == 0)
		$content .= "<font color=red>No savings have been registered yet for the member. ".intval(strtotime($start_date)) - intval(strtotime($end_date))." from_year ".$start_date.": $net_save - $cumm_save</font><br>";
	export($format, $content);
}
//reports_header();
ind_savings_summary($_GET['mem_no1'], $_GET['mem_no2'], $_GET['from_year'], $_GET['from_month'], $_GET['to_year'], $_GET['to_month'], $_GET['format']);
//reports_footer();
?>
