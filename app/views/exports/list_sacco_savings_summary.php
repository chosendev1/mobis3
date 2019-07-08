<?php

if(!isset($_GET['format']))
echo("<head>
	<title>SAVINGS PLUS v 2.0 : SACCOS'S SAVINGS SUMMARY REPORT</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");
function sacco_savings_summary($branch_id, $from_year, $from_month, $to_year, $to_month, $format)
{
	$calc = new Date_Calc();
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$content = "<center><p><font color=#00008b size=4pt><b>".$branch['branch_name']."</b></font></p></center>";
	$content .= "<center><font color=#00008b size=3pt><B>SACCO'S SAVINGS SUMMARY REPORT</B></font></center>";
	
	if($from_year ==''){
		$content .= "<table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='70%' id='AutoNumber2' align=center><tr><td><font color=red>Select the period for which you want the report</font></td></tr>";
	}
	
	$start_date = date('Y-m-d 23:59:59', strtotime($calc->endOfMonthBySpan(0, intval($from_month), intval($from_year), '%Y-%m-%d')));
	$final_date = date('Y-m-d 23:59:59', strtotime($calc->endOfMonthBySpan(0, intval($to_month), intval($to_year), '%Y-%m-%d')));
	$start_month = intval($from_month); $end_month = intval($to_month);
	$start_year = intval($from_year); $end_year = intval($to_year);
	$total_saved = 0; $total_with = 0; $total_charges = 0; $total_fees = 0;
	$cumm_save = 0;
	$branch = ($branch_id=='all'||$branch_id=='')? "": " and  branch_id='".$branch_id."'";
	$drow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_savings, sum(percent_value + flat_value) as charge from deposit where bank_account != 0 and date < '".$start_date."'".$branch.""));
	$crow1 = @mysql_fetch_array(mysql_query("select sum(percent_value + flat_value) as charge from deposit where bank_account != 0 and date < '".$start_date."'".$branch.""));
	$crow2 = @mysql_fetch_array(mysql_query("select sum(percent_value + flat_value) as charge from withdrawal where bank_account != 0 and date < '".$start_date."'".$branch.""));
	$wrow1 = @mysql_fetch_array(mysql_query("select sum(amount) as tot_with, sum(percent_value + flat_value) as charge from withdrawal where date < '".$start_date."'".$branch.""));
	$mrow1 = @mysql_fetch_array(mysql_query("select sum(amount) as tot_fees from monthly_charge where date < '".$start_date."'".$branch.""));
	$irow1 = mysql_fetch_array(mysql_query("select sum(amount) as tot_int from save_interest where date < '".$start_date."'".$branch.""));
	$prow1 = @mysql_fetch_array(@mysql_query("select sum(princ_amt + int_amt) as tot_pay from payment where mode not in ('cash', 'Cheque', '') and date < '".$start_date."'".$branch.""));
	$incrow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_inc from other_income where mode not in ('cash', 'Cheque', '') and date < '".$start_date."'".$branch.""));
	$shares = @mysql_fetch_array(@mysql_query("select sum(value) as amount from shares where mode not in ('cash', 'Cheque', '') and date < '".$start_date."'".$branch.""));
	

	$tot_savings = isset($drow1[tot_savings])? $drow1[tot_savings] : 0 ;
	
			$tot_with = isset($wrow1['tot_with'])? $wrow1['tot_with'] : 0 ;
			$tot_int = isset($irow1['tot_int'])? $irow1['tot_int'] : 0 ;
			$tot_inc = isset($incrow1['tot_inc'])? $incrow1['tot_inc'] : 0 ;
			$tot_shares = ($shares['amount'] != NULL) ? $shares['amount'] : 0;
			$tot_pay = isset($prow1['tot_pay'])? $prow1['tot_pay'] : 0 ;
			
			$dcharge_amt = ($drow1['charge'] != NULL) ? $drow1['charge'] : 0;
			$wcharge_amt = ($wrow1['charge'] != NULL) ? $wrow1['charge'] : 0;
			$tot_fees = $dcharge_amt + $wcharge_amt;
			$tot_deduc = $tot_inc + $tot_pay + $tot_shares;
			$net_save = $tot_savings + $tot_int  - $tot_with - $tot_deduc - $tot_fees;


	
	$cumm_save= $net_save;
	//$tot_fees = $mrow1['tot_fees'] + $wcharge_amt + $dcharge_amt;
	//$tot_deduc = $incrow1['tot_inc'] + $payrow1['tot_pay'] + $shares['amount'] ; //$tot_shares;
	$content .= "
		    <table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>
		    <tr class='headings'>
			<th>Period</th><th>Total Deposits</th><th>Total Withdrawals</th><th>Total Interest</th><th>Total Fees</th><th>Other Deductions</th><th>Cummulative Savings</th>
		    </tr>
		    <tr bgcolor=lightgrey>
			<td>As At End of $start_year - ".$calc->getMonthAbbrname($start_month)."</td><td align=center>".number_format($drow1[tot_savings], 2)."</td><td align=center>".number_format($wrow1[tot_with], 2)."</td><td align=center>".number_format($irow1[tot_int], 2)."</td><td align=center>".number_format($tot_fees, 2)."</td><td align=center>".number_format($tot_deduc, 2)."</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
	$x = 0;
	while (intval(strtotime($start_date)) < intval(strtotime($final_date)))
	{
		
		$drow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_savings from deposit where bank_account != 0 and date >='".$start_date."' and date <= '".$end_date."'".$branch.""));
		$wrow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_with from withdrawal where date  >='".$start_date."' and date<='".$end_date."'".$branch.""));
		$crow1 = @mysql_fetch_array(@mysql_query("select sum(percent_value + flat_value) as charge from deposit where bank_account != 0 and date >= '".$start_date."' and date <= '".$end_date."'".$branch.""));
		$crow2 = @mysql_fetch_array(@mysql_query("select sum(percent_value + flat_value) as charge from withdrawal where bank_account != 0 and date >= '".$start_date."' and date <= '".$end_date."'".$branch.""));
		$mrow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_fees from monthly_charge where date >= '".$start_date."' and date <='".$end_date."'".$branch.""));
		$irow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_int from save_interest where date >= '".$start_date."' and date <='".$end_date."'".$branch.""));
		$incrow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_inc from other_income where mode not in ('cash', 'Cheque', '') and date >= '".$start_date."' and date <='".$end_date."'".$branch.""));
		$shares = @mysql_fetch_array(@mysql_query("select sum(value) as amount from shares where mode not in ('cash', 'Cheque', '') and date >= '".$start_date."' and date <='".$end_date."'".$branch.""));
		$payrow = @mysql_fetch_array(@mysql_query("select sum(princ_amt + int_amt) as tot_pay from payment where mode not in ('cash', 'Cheque', '') and date >= '".$start_date."' and date <='".$end_date."'".$branch.""));
		//$resp->AddAppend("status", innerHTML, "select sum(amount) as tot_int from save_interest where date > '".$start_date."' and date <='".$end_date."'");
		if (isset($drow[tot_savings]) || isset($wrow[tot_with]) || isset($mrow[tot_fees]) || isset($irow[tot_int]) || ($shares['amount'] != NULL))
		{
			$tot_savings = isset($drow[tot_savings])? $drow[tot_savings] : 0 ;
			$tot_fees = isset($mrow[tot_fees])? $mrow[tot_fees] : 0 ;
			$tot_with = isset($wrow[tot_with])? $wrow[tot_with] : 0 ;
			$tot_int = isset($irow[tot_int])? $irow[tot_int] : 0 ;
			$tot_inc = isset($incrow[tot_inc])? $incrow[tot_inc] : 0 ;
			$tot_shares = ($shares[amount])? $shares[amount] : 0 ;
			$tot_pay = isset($payrow[tot_pay])? $payrow[tot_pay] : 0 ;
			$dcharge_amt = ($crow1['charge'] != NULL) ? $crow1['charge'] : 0;
			$wcharge_amt = ($crow2['charge'] != NULL) ? $crow2['charge'] : 0;
			$tot_fees = $dcharge_amt + $wcharge_amt;
			$tot_deduc = $tot_inc + $tot_pay + $tot_shares;
			$net_save = $tot_savings + $tot_int - $tot_fees - $tot_with - $tot_deduc;
			$cumm_save += $net_save;
			$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
			    <tr bgcolor=$color>
				<td>$start_year - ".$calc->getMonthAbbrname($start_month)."</td><td align=center>".number_format($tot_savings, 2)."</td><td align=center>".number_format($tot_with, 2)."</td><td align=center>".number_format($tot_int, 2)."</td><td align=center>".number_format($tot_fees, 2)."</td><td align=center>".number_format($tot_deduc, 2)."</td><td align=center>".number_format($cumm_save, 2)."</td>
			    </tr>
			    ";
		}
		if (intval($start_month) == 12)
		{
			$start_month = 1; $start_year += 1;
		}
		else
		{
			$start_month += 1;
		}
		$x++;
		$start_date = sprintf("%d-%02d", $start_year, $start_month) . "-01 00:00:00";
		$end_date = date('Y-m-d 23:59:59', strtotime($calc->endOfMonthBySpan(0, intval($start_month), intval($start_year), '%Y-%m-%d')));
	} 	
	$content .= "</table>";
	if ($cumm_save == 0)
		$content .= "<font color=red>No savings have been registered yet by this SACCO. ".intval(strtotime($start_date)) - intval(strtotime($end_date))." from_year ".$start_date.": $net_save - $cumm_save</font><br>";
	export($format, $content);
}
//reports_header();
sacco_savings_summary($_GET['branch_id'], $_GET['from_year'], $_GET['from_month'], $_GET['to_year'], $_GET['to_month'], $_GET['format']);
//reports_footer();
?>
