<?php

if(!isset($_GET['format']))
echo("<head>
	<title>PERFOMANCE INDICATORS SUMMARY REPORT</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");
//PERFORMANCE INDICATORS REPORT
function ratios($year, $month, $mday, $format){
	
	$calc = new Date_Calc();
	$link = ($year == '') ? "" : "<a href='list_ratios.php' target=blank()><b>Printable Version</b></a>";
	$content = "
	<table height=200 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center> ";
	if($year == ''){
		$content .= "<tr><td><font color=red>Please select the date</font></td></tr></table>";
	}
	$chosen_date = sprintf("%d-%02d-%02d", $year, $month, $mday);
	$last1_date = $calc->daysToDate($calc->dateToDays($mday, $month, $year) - 365, "%Y-%m-%d");
	$last2_date = $calc->daysToDate($calc->dateToDays($mday, $month, $year) - 730, "%Y-%m-%d");
	$content .= "<tr><td colspan=4><center><font size=4pt><b>FINANCIAL PERFORMANCE INDICATORS REPORT</b></font></center></td></tr>
	<tr class='headings'><td></td><td><b>Management Accounts As At ".$chosen_date."</b></td><td><b>Management Accounts As At ".$last1_date."</b></td><td><b>Management Accounts As At ".$last2_date."</b></td></tr>
	<tr bgcolor=white><td><b>1. Outreach</b></td><td></td><td></td><td></td></tr>";
	
	//LOAN CLIENTS ON  CHOSEN DATE
	//female
	$loan_res = mysql_query("select count(distinct applic.mem_id) as clients from loan_applic applic join member m on applic.mem_id=m.id where applic.date <='".$chosen_date."' and m.sex='F'");
	$loan = mysql_fetch_array($loan_res);
	$chosen_female = ($loan['clients'] != NULL) ? $loan['clients'] : 0;
	//male
	$loan_res = mysql_query("select count(distinct applic.mem_id) as clients from loan_applic applic join member m on applic.mem_id=m.id where applic.date <='".$chosen_date."' and m.sex='M'");
	$loan = mysql_fetch_array($loan_res);
	$male = ($loan['clients'] != NULL) ? $loan['clients'] : 0;
	$chosen_clients = ($chosen_female == 0 && $male==0) ? 0: $chosen_female + $male;
	$denom = ($chosen_clients == 0) ? 1 : $chosen_clients;
	$chosen_perc = ($chosen_female / $denom) * 100;
	$chosen_perc = sprintf("%.02f", $chosen_perc);

	//LOAN CLIENTS PREVIOUS YEAR
	$loan_res = mysql_query("select count(distinct applic.mem_id) as clients from loan_applic applic join member m on applic.mem_id=m.id where applic.date <='".$last1_date."' group by m.sex order by m.sex");
	$loan = mysql_fetch_array($loan_res);
	$last1_female = ($loan['clients'] != NULL) ? $loan['clients'] : 0;
	$loan = mysql_fetch_array($loan_res);
	$male = ($loan['clients'] != NULL) ? $loan['clients'] : 0;
	$last1_clients = ($last1_female == 0 && $male ==0) ? 0 : $last1_female + $male;
	$denom = ($last1_clients == 0) ? 1 : $last1_clients;
	$last1_perc = ($last1_female / $denom) * 100;
	$last1_perc = sprintf("%.02f", $last1_perc);

	//LOAN CLIENTS PREVIOUS PREVIOUS YEAR
	$loan_res = mysql_query("select count(distinct applic.mem_id) as clients from loan_applic applic join member m on applic.mem_id=m.id where applic.date <='".$last2_date."' group by m.sex order by m.sex");
	$loan = mysql_fetch_array($loan_res);
	$last2_female = ($loan['clients'] != NULL) ? $loan['clients'] : 0;
	$loan = mysql_fetch_array($loan_res);
	$male = ($loan['clients'] != NULL) ? $loan['clients'] : 0;
	$last2_clients = ($last2_female == 0 && $male==0) ? 0 : $last2_female + $male;
	$denom = ($last2_clients == 0) ? 1 : $last2_clients;
	$chosen_perc = ($last2_female / $denom) * 100;
	$last2_perc = sprintf("%.02f", $last2_perc);
	$content .="<tr bgcolor=lightgrey><td>No. active Loan clients</td><td>".$chosen_clients."</td><td>".$last1_clients."</td><td>".$last2_clients."</td></tr>
	<tr bgcolor=white><td>Women Loan clients (% total)</td><td>".$chosen_perc."</td><td>".$last1_perc."</td><td>".$last2_perc."</td></tr>";
	
	//SAVINGS AS AT CHOSEN DATE
	$dep_res = mysql_query("select sum(amount - flat_value - percent_value) as amount from deposit where date <='".$chosen_date."'");
	$dep = mysql_fetch_array($dep_res);
	$dep_amt = ($dep['amount'] != NULL) ? $dep['amount'] : 0;
	$with_res = mysql_query("select sum(amount + flat_value + percent_value) as amount from withdrawal where date <='".$chosen_date."'");
	$with = mysql_fetch_array($with_res);
	$with_amt = ($with['amount'] != NULL) ? $with['amount'] : 0;
	$chosen_savings = $dep_amt - $with_amt;

	//SAVINGS AS OF PREVIOUS YEAR
	$dep_res = mysql_query("select sum(amount - flat_value - percent_value) as amount from deposit where date <='".$last1_date."'");
	$dep = mysql_fetch_array($dep_res);
	$dep_amt = ($dep['amount'] != NULL) ? $dep['amount'] : 0;
	$with_res = mysql_query("select sum(amount + flat_value + percent_value) as amount from withdrawal where date <='".$last1_date."'");
	$with = mysql_fetch_array($with_res);
	$with_amt = ($with['amount'] != NULL) ? $with['amount'] : 0;
	$last1_savings = $dep_amt - $with_amt;

	//SAVINGS AS OF PREVIOUS PREVIOUS YEAR
	$dep_res = mysql_query("select sum(amount - flat_value - percent_value) as amount from deposit where date <='".$last2_date."'");
	$dep = mysql_fetch_array($dep_res);
	$dep_amt = ($dep['amount'] != NULL) ? $dep['amount'] : 0;
	$with_res = mysql_query("select sum(amount + flat_value + percent_value) as amount from withdrawal where date <='".$last2_date."'");
	$with = mysql_fetch_array($with_res);
	$with_amt = ($with['amount'] != NULL) ? $with['amount'] : 0;
	$last2_savings = $dep_amt - $with_amt;

	$save_res = mysql_query("select count(distinct mem_id) as savers from mem_accounts where open_date <='".$chosen_date."'");
	$save = mysql_fetch_array($save_res);
	$chosen_savers = ($save['savers'] != NULL) ? $save['savers'] : 0;

	$save_res = mysql_query("select count(distinct mem_id) as savers from mem_accounts where open_date <='".$last1_date."'");
	$save = mysql_fetch_array($save_res);
	$last1_savers = ($save['savers'] != NULL) ? $save['savers'] : 0;

	$save_res = mysql_query("select count(distinct mem_id) as savers from mem_accounts where open_date <='".$last2_date."'");
	$save = mysql_fetch_array($save_res);
	$last2_savers = ($save['savers'] != NULL) ? $save['savers'] : 0;

	$content .= "<tr bgcolor=lightgrey><td><b>2. Savings</b></td><td></td><td></td><td></td></tr>
	<tr bgcolor=white><td>Value of Savings</td><td>".$chosen_savings."</td><td>".$last1_savings."</td><td>".$last2_savings."</td></tr>
	<tr bgcolor=lightgrey><td>No. of Savers</td><td>".$chosen_savers."</td><td>".$last1_savers."</td><td>".$last2_savers."</td></tr>";
	
	//PORTFOLIO ACTIVITY
	//PORTFOLIO ACTIVITY AS AT CHOSEN DATE
	//female
	$loan_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id where d.date <='".$chosen_date."' and m.sex='F'");
	$paid_res = mysql_query("select sum(p.princ_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id where d.date <='".$chosen_date."' and m.sex='F'");
	$loan = mysql_fetch_array($loan_res);
	$paid = mysql_fetch_array($paid_res);
	$female_taken = ($loan['amount'] != NULL) ? $loan['amount'] : 0;
	$female_paid = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
	$chosen_female = $female_taken - $female_paid;
	//male
	$loan_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id where d.date <='".$chosen_date."' and m.sex='M'");
	$paid_res = mysql_query("select sum(p.princ_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id where d.date <='".$chosen_date."' and m.sex='M'");
	$loan = mysql_fetch_array($loan_res);
	$paid = mysql_fetch_array($paid_res);
	$male_taken = ($loan['amount'] != NULL) ? $loan['amount'] : 0;
	$male_paid = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
	$male = $male_taken - $male_paid;
	$chosen_out = ($chosen_female == 0 && $male ==0) ? 0 : $chosen_female + $male;
	$denom = ($chosen_out == 0) ? 1 : $chosen_out;
	$chosen_perc = ($chosen_female / $denom) * 100;
	$chosen_perc = sprintf("%.02f", $chosen_perc);

	//PORTFOLIO ACTIVITY END OF PREVIOUS YEAR
	//female
	$loan_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id where d.date <='".$last1_date."' and m.sex='F'");
	$paid_res = mysql_query("select sum(p.princ_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id where d.date <='".$last1_date."' and m.sex='F'");
	$loan = mysql_fetch_array($loan_res);
	$paid = mysql_fetch_array($paid_res);
	$female_taken = ($loan['amount'] != NULL) ? $loan['amount'] : 0;
	$female_paid = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
	$last1_female = $female_taken - $female_paid;
	//male
	$loan_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id where d.date <='".$last1_date."' and m.sex='M'");
	$paid_res = mysql_query("select sum(p.princ_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id where d.date <='".$last1_date."' and m.sex='M'");
	$loan = mysql_fetch_array($loan_res);
	$paid = mysql_fetch_array($paid_res);
	$male_taken = ($loan['amount'] != NULL) ? $loan['amount'] : 0;
	$male_paid = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
	$male = $male_taken - $male_paid;
	$last1_out = ($last1_female == 0 && $male ==0) ? 0 : $last1_female + $male;
	$denom = ($last1_out == 0) ? 1 : $last1_out;
	$last1_perc = ($last1_female / $denom) * 100;
	$last1_perc = sprintf("%.02f", $last1_perc);

	//PORTFOLIO ACTIVITY END OF PREVIOUS PREVIOUS YEAR
	//female
	$loan_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id where d.date <='".$last2_date."' and m.sex='F'");
	$paid_res = mysql_query("select sum(p.princ_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id where d.date <='".$last2_date."' and m.sex='F'");
	$loan = mysql_fetch_array($loan_res);
	$paid = mysql_fetch_array($paid_res);
	$female_taken = ($loan['amount'] != NULL) ? $loan['amount'] : 0;
	$female_paid = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
	$last2_female = $female_taken - $female_paid;
	//male
	$loan_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id where d.date <='".$last2_date."' and m.sex='M'");
	$paid_res = mysql_query("select sum(p.princ_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id where d.date <='".$last2_date."' and m.sex='M'");
	$loan = mysql_fetch_array($loan_res);
	$paid = mysql_fetch_array($paid_res);
	$male_taken = ($loan['amount'] != NULL) ? $loan['amount'] : 0;
	$male_paid = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
	$male = $male_taken - $male_paid;
	$last2_out = ($last2_female == 0 && $male ==0) ? 0 : $last2_female + $male;
	$denom = ($last2_out == 0) ? 1 : $last2_out;
	$last2_perc = ($last2_female / $denom) * 100;
	$last2_perc = sprintf("%.02f", $last2_perc);
	$content .= "<tr bgcolor=white><td><b>3. Portfolio Activity</b></td><td></td><td></td><td></td></tr>
	<tr bgcolor=lightgrey><td>Value of gross loan portfolio</td><td>".$chosen_out."</td><td>".$last1_out."</td><td>".$last2_out."</td></tr>
	<tr bgcolor=white><td>% of outstanding loans of female clients</td><td>".$chosen_perc."</td><td>".$last1_perc."</td><td>".$last2_perc."</td></tr>";

	//PORTFOLIO QUALITY
	$content .= "<tr bgcolor=lightgrey><td><b>4. Portfolio Quality</b></td><td></td><td></td><td></td></tr>";
	$prov_res = mysql_query("select * from provissions order by range");
	while($row = mysql_fetch_array($prov_res)){  //SET PROVISSION PERCENTAGES
		${$row['range']} = $row['percent'];
	}
	for($i=0; $i <3; $i++){
		$provission = 0;
		if($i == 0)
			$apparent_date = $chosen_date;
		elseif($i==1)
			$apparent_date = $last1_date;
		elseif($i == 2)
			$apparent_date = $last2_date;
		$sth = mysql_query("select d.id as loan_id, d.last_pay_date as last_pay_date, d.amount as amount, d.balance as balance, d.period as loan_period from disbursed d where d.date <= '".$apparent_date."'");
		while($row = mysql_fetch_array($sth)){
			$paid_amt = $row['amount'] - $row['balance'];
			$sched_res = mysql_query("select  sum(princ_amt) as princ_amt from schedule where loan_id='".$row['loan_id']."' and date <= '".$apparent_date."'");
			$sched = mysql_fetch_array($sched_res);
			$sched_amt =($sched['princ_amt'] != NULL) ? $sched['princ_amt'] : 0;
			$arrears_amt = $sched_amt - $paid_amt; 
			$arrears_amt = ($arrears_amt >= 0) ? $arrears_amt : 0;
			$range1 = 0;
			$range2 = 0;
			$range3 = 0;  
			$range4 = 0;
			$range5 = 0;
			$range6 = 0;
			$offset=0;
			if($arrears_amt >0){
				$lastdue_res = mysql_query("select date, date_format(date, '%Y') as year, date_format(date, '%m') as month, date_format(date, '%d') as mday from schedule order by date desc limit 1");
				$lastdue = mysql_fetch_array($lastdue_res);
				$offset = strtotime($apparent_date) - strtotime($lastdue['date']);
			
		
				$offset = ($offset >0) ? $offset : 0;
				$offset = floor($offset /(3600 * 24 * 30));
				$instalment = ceil($row['amount'] / ($row['loan_period']/ 30));
				$no = ceil($arrears_amt / $instalment);
				$remainder = $arrears_amt % $instalment;
			}
			
			if($offset >= 6)   //THE WHOLE SCHEDULE IS MORE THAN 6 MONTHS OVERDUE
				$range6 = $arrears_amt * ($range180_over /100);
			elseif($offset == 5){    //THE WHOLE SCHEDULE IS 5 MONTHS OVERDUE
				if($no == 1)
					$range5 = $arrears_amt * $range120_180/100; 
				elseif($no == 2){
					$range4 = $instalment * $range90_120/100;
					$range5 = ($arrears_amt - $instalment) * $range120_180/100;
				}elseif($no == 3){
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = ($arrears_amt - 2*$instalment) * $range120_180/100;
				}elseif($no == 4){
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = ($arrears_amt - 3*$instalment) * $range120_180/100;
				}elseif($no == 5){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = ($arrears_amt - 4*$instalment) * $range120_180/100;
				}elseif($no >= 6){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = $instalment * $range120_180/100;
					$range6 = ($arrears_amt - 5*$instalment) * $range180_over/100;
				}
			}elseif($offset == 4){    //THE WHOLE SCHEDULE IS 4 MONTHS OVERDUE
				if($no == 1)
					$range4 = $arrears_amt * $range90_120/100; 
				elseif($no == 2){
					$range3 = $instalment * $range60_90/100;
					$range4 = ($arrears_amt - $instalment) * $range90_120/100;
				}elseif($no == 3){
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = ($arrears_amt - 2*$instalment) * $range90_120/100;
				}elseif($no == 4){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = ($arrears_amt - 3*$instalment) * $range90_120/100;
				}elseif($no == 5){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = ($arrears_amt - 4*$instalment) * $range120_180/100;
				}elseif($no >= 6){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = $instalment * $range120_180/100;
					$range6 = ($arrears_amt - 5*$instalment) * $range180_over/100;
				}
			}elseif($offset == 3){    //THE WHOLE SCHEDULE IS 3 MONTHS OVERDUE
				if($no == 1)
					$range3 = $arrears_amt * $range60_90/100; 
				elseif($no == 2){
					$range2 = $instalment * $range30_60/100;
					$range3 = ($arrears_amt - $instalment) * $range60_90/100;
				}elseif($no == 3){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = ($arrears_amt - 2*$instalment) * $range60_90/100;
				}elseif($no == 4){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = ($arrears_amt - 3*$instalment) * $range90_120/100;
				}elseif($no == 5){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = ($arrears_amt - 4*$instalment) * $range120_180/100;
				}elseif($no >= 6){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = $instalment * $range120_180/100;
					$range6 = ($arrears_amt - 5*$instalment) * $range180_over/100;
				}
			}elseif($offset == 2){    //THE WHOLE SCHEDULE IS 2 MONTHS OVERDUE
				if($no == 1)
					$range2 = $arrears_amt * $range30_60/100; 
				elseif($no == 2){
					$range1 = $instalment * $range1_30/100;
					$range2 = ($arrears_amt - $instalment) * $range30_60/100;
				}elseif($no == 3){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = ($arrears_amt - 2*$instalment) * $range60_90/100;
				}elseif($no == 4){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = ($arrears_amt - 3*$instalment) * $range90_120/100;
				}elseif($no == 5){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = ($arrears_amt - 4*$instalment) * $range120_180/100;
				}elseif($no >= 6){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = $instalment * $range120_180/100;
					$range6 = ($arrears_amt - 5*$instalment) * $range180_over/100;
				}
			}elseif($offset <= 1){    //THE WHOLE SCHEDULE IS 1 MONTHS OVERDUE
				if($no == 1)
					$range1 = $arrears_amt * $range1_30/100; 
				elseif($no == 2){
					$range1 = $instalment * $range1_30/100;
					$range2 = ($arrears_amt - $instalment) * $range30_60/100;
				}elseif($no == 3){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = ($arrears_amt - 2*$instalment) * $range60_90/100;
				}elseif($no == 4){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = ($arrears_amt - 3*$instalment) * $range90_120/100;
				}elseif($no == 5){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range30_60/100;
					$range3 = $instalment * $range60_90/100;
					$range4 = $instalment * $range90_120/100;
					$range5 = ($arrears_amt - 4*$instalment) * $range120_180/100;
				}elseif($no >= 6){
					$range1 = $instalment * $range1_30/100;
					$range2 = $instalment * $range31_60/100;
					$range3 = $instalment * $range61_90/100;
					$range4 = $instalment * $range91_120/100;
					$range5 = $instalment * $range121_180/100;
					$range6 = ($arrears_amt - 5*$instalment) * $range180_over/100;
				}
			}
			$provission = $provission + $range1 + $range2 + $range3 + $range4 + $range5 + $range6;	
		}
		
		$out_res = mysql_query("select sum(d.amount) as amount from disbursed d where d.date <= '".$apparent_date."'");
		$out = mysql_fetch_array($out_res);
		$out_amt = ($out['amount'] != NULL) ? $out['amount'] : 0;
		$in_res = mysql_query("select sum(princ_amt) as amount from payment where date <= '".$apparent_date."'");
		$in = mysql_fetch_array($in_res);
		$in_amt = ($in['amount'] != NULL) ? $in['amount'] : 0;
		
		if($in_amt >= $out_amt)
			$percent =0;
		else{
			$div = $out_amt - $in_amt;
			$percent = ($provission / ($div)) * 100;
			$percent = sprintf("%.02f", $percent);
		}
		if($i == 0)
			$chosen_perc = $percent;
		elseif($i == 1)
			$last1_perc = $percent;
		elseif($i == 2)
			$last2_perc = $percent;
	}	
	$content .= "<tr bgcolor=lightgrey><td>Risk Coverage Ratio (%)</td><td>".$chosen_perc."</td><td>".$last1_perc."</td><td>".$last2_perc."</td></tr>";
	//PORTIFPLIO AT RISK
	for($i=0; $i <3; $i++){
		$provission = 0;
		if($i == 0)
			$apparent_date = $chosen_date;
		elseif($i==1)
			$apparent_date = $last1_date;
		elseif($i == 2)
			$apparent_date = $last2_date;
		//CALCULATE ARREARS
		$sched_res = mysql_query("select s.loan_id as loan_id,  sum(s.princ_amt) as princ_amt from schedule s join disbursed d on s.loan_id=d.id left join written_off w on d.id=w.loan_id where (w.date >='".$apparent_date."' or w.date is null) and s.date <= date_sub('".$apparent_date."', INTERVAL d.arrears_period DAY) group by s.loan_id");
		$risky_amt =0;
		while($sched = mysql_fetch_array($sched_res)){
			$paid_res = mysql_query("select sum(p.princ_amt) as princ_amt from payment p join disbursed d on p.loan_id=d.id left join written_off w on d.id=w.loan_id where (w.date >='".$apparent_date."' or w.date is null) and p.date <= '".$apparent_date."' and p.loan_id='".$sched['loan_id']."'");
			$paid = mysql_fetch_array($paid_res);
			$paid_amt = ($paid['princ_amt'] != NULL) ? $paid['princ_amt'] : 0;
			if($sched['princ_amt'] > $paid_amt){
				//OUTSTANDING PORTIFOLIO THEN
				$disbursed_res = mysql_query("select sum(d.amount) as amount from disbursed d left join written_off w on d.id=w.loan_id where (w.date >='".$apparent_date."' or w.date is null) and d.date <= '".$apparent_date."' and d.id='".$sched['loan_id']."'");
				$disbursed = mysql_fetch_array($disbursed_res);
				$disbursed_amt = ($disbursed['amount'] != NULL) ? $disbursed['amount'] : 0;
				$outstanding = $disbursed_amt - $paid_amt;
				$risky_amt = $risky_amt + $outstanding;
			
			}
		}
		//TOTAL DISBURSED
		$disbursed_res = mysql_query("select sum(d.amount) as amount from disbursed d left join written_off w on d.id=w.loan_id where (w.date >='".$apparent_date."' or w.date is null) and d.date <= '".$apparent_date."'");
		$disbursed = mysql_fetch_array($disbursed_res);
		$disbursed_amt = ($disbursed['amount'] != NULL) ? $disbursed['amount'] : 0;
		
		//TOTAL PAID
		$paid_res = mysql_query("select sum(p.princ_amt) as princ_amt from payment p join disbursed d on p.loan_id=d.id left join written_off w on d.id=w.loan_id where (w.date >='".$apparent_date."' or w.date is null) and p.date <= '".$apparent_date."'");
		$paid = mysql_fetch_array($paid_res);
		$paid_amt = ($paid['princ_amt'] != NULL) ? $paid['princ_amt'] : 0;
		//TOTAL OUTSTANDING
		$outstanding = $disbursed_amt - $paid_amt;
		$outstanding = ($outstanding > 0) ? $outstanding : 1;
		$percent = ($risky_amt / $outstanding) * 100.00;
		$percent = sprintf("%.02f", $percent);
		if($i == 0)
			$chosen_perc = $percent;
		elseif($i == 1)
			$last1_perc = $percent;
		elseif($i == 2)
			$last2_perc = $percent;
	}	
	$content .= "<tr bgcolor=white><td>Portfolio At Risk (%)</td><td>".$chosen_perc."</td><td>".$last1_perc."</td><td>".$last2_perc."</td></tr>";

	//PORTIFOLIO YIELD
	for($i=0; $i <3; $i++){
		$provission = 0;
		if($i == 0)
			$apparent_date = $chosen_date;
		elseif($i==1)
			$apparent_date = $last1_date;
		elseif($i == 2)
			$apparent_date = $last2_date;
	//DISBURSED LOANS
		$disbursed_res = mysql_query("select sum(amount) as amount from disbursed where date <='".$apparent_date."'");
		$disbursed = mysql_fetch_array($disbursed_res);
		$disbursed_amt = ($disbursed['amount'] != NULL) ? $disbursed['amount'] : 0;
		//INTEREST PAID
		$paid_res = mysql_query("select sum(int_amt) as int_amt, sum(princ_amt) as princ_amt from payment where date <='".$apparent_date."'");
		$paid = mysql_fetch_array($paid_res);
		$paid_int = ($paid['int_amt'] != NULL) ? $paid['int_amt'] : 0;
		$paid_princ = ($paid['princ_amt'] != NULL) ? $paid['princ_amt'] : 0;
		//WRITTEN OFF 
		$write_res = mysql_query("select sum(amount) as amount from written_off where date <='".$apparent_date."'");
		$write = mysql_fetch_array($write_res);
		$written_amt = ($write['amount'] != NULL) ? $write['amount'] : 0;
		//OUTSTANDING
		$balance = $disbursed_amt - $paid_princ - $written_amt;
		$balance = ($balance == 0) ? 1 : $balance;
		$percent = ($paid_int / $balance) * 100.00;
		$percent = sprintf("%.02f", $percent);
		if($i == 0)
			$chosen_perc = $percent;
		elseif($i == 1)
			$last1_perc = $percent;
		elseif($i == 2)
			$last2_perc = $percent;
	}	
	$content .= "<tr bgcolor=lightgrey><td>Portfolio Yield (%) </td><td>".$chosen_perc."</td><td>".$last1_perc."</td><td>".$last2_perc."</td></tr>";

	//EFFECTIVE REPAYMENT RATE 
	//CALCULATE CUMMULATED REPAYMENT RATE
	for($i=0; $i <3; $i++){
		if($i == 0)
			$apparent_date = $chosen_date;
		elseif($i==1)
			$apparent_date = $last1_date;
		elseif($i == 2)
			$apparent_date = $last2_date;
		$sched_res = mysql_query("select  sum(s.princ_amt) as princ_amt from schedule s join disbursed d on s.loan_id=d.id left join written_off w on d.id=w.loan_id where (w.date >='".$apparent_date."' or w.date is null) and s.date <= '".$apparent_date."'");
		$sched = mysql_fetch_array($sched_res);
		$sched_amt = ($sched['princ_amt']!= NULL) ? $sched['princ_amt'] : 1;
		$paid_res = mysql_query("select sum(p.princ_amt) as princ_amt from payment p join disbursed d on p.loan_id=d.id left join written_off w on d.id=w.loan_id where (w.date >='".$apparent_date."' or w.date is null) and p.date <= '".$apparent_date."'");
		$paid = mysql_fetch_array($paid_res);
		$paid_amt = ($paid['princ_amt'] != NULL) ? $paid['princ_amt'] : 0;

		$percent = ($paid_amt / $sched_amt) * 100.00;		
		$percent = sprintf("%.02f", $percent);
		if($i == 0)
			$chosen_perc = $percent;
		elseif($i == 1)
			$last1_perc = $percent;
		elseif($i == 2)
			$last2_perc = $percent;
	}	
	$content .= "<tr bgcolor=white><td>Effective Repayment Rate (%) </td><td>".$chosen_perc."</td><td>".$last1_perc."</td><td>".$last2_perc."</td></tr>
	<tr bgcolor=lightgrey><td><b>5. Profitability:</b></td><td></td><td></td><td></td></tr>";
	for($i=0; $i <3; $i++){
		if($i == 0)
			$apparent_date = $chosen_date;
		elseif($i==1)
			$apparent_date = $last1_date;
		elseif($i == 2)
			$apparent_date = $last2_date;
		//OPERATING INCOME
		//INCOME FROM LOANS
		$int_res = mysql_query("select sum(int_amt) as amount from payment where date <='".$apparent_date."'");
		$int = mysql_fetch_array($int_res);
		$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;

		//PENALITIES
		$pen_res = mysql_query("select sum(amount) as amount from penalty where date <='".$apparent_date."'");
		$pen = mysql_fetch_array($pen_res);
		$pen_amt = ($pen['amount'] != NULL) ? $pen['amount'] : 0;

		//OTHER INCOME
		$other_res = mysql_query("select sum(amount) as amount from other_income where date <= '".$apparent_date."'");
		$other = mysql_fetch_array($other_res);
		$other_amt = ($other['amount'] != NULL);
		
		//TRANSACTIONAL CHARGES
		$dep_res = mysql_query("select sum(flat_value + percent_value) as charge from deposit where date <='".$apparent_date."'");
		$dep = mysql_fetch_array($dep_res);
		$dep_charge = ($dep['charge'] != NULL) ? $dep['charge'] : 0;
		$with_res = mysql_query("select sum(flat_value + percent_value) as charge from withdrawal where date <='".$apparent_date."'");
		$with = mysql_fetch_array($with_res);
		$with_charge = ($with['charge'] != NULL) ? $with['charge'] : 0;

		$charge_res = mysql_query("select sum(amount) as amount from monthly_charge where date <='".$last_date."'");
		$charge = mysql_fetch_array($charge_res);
		$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;

		$income = $int_amt + $pen_amt + $other_amt + $dep_charge + $with_charge + $charge_amt;
		
//OPERATING EXPENSES
		$expense_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where date <= '".$apparent_date."'");
		$expense = mysql_fetch_array($expense_res);
		$expense_amt = ($expense['amount'] != NULL) ? $expense['amount'] : 1;
		$percent = ($income / $expense_amt) * 100;		
		$percent = sprintf("%.02f", $percent);
		if($i == 0)
			$chosen_perc = $percent;
		elseif($i == 1)
			$last1_perc = $percent;
		elseif($i == 2)
			$last2_perc = $percent;
	}	
	$content .= "<tr bgcolor=white><td>Operational Self-Sufficiency (%) </td><td>".$chosen_perc."</td><td>".$last1_perc."</td><td>".$last2_perc."</td></tr>";
	
	for($i=0; $i <3; $i++){
		if($i == 0)
			$apparent_date = $chosen_date;
		elseif($i==1)
			$apparent_date = $last1_date;
		elseif($i == 2)
			$apparent_date = $last2_date;
	//OPERATING INCOME
		//INCOME FROM LOANS
		$int_res = mysql_query("select sum(int_amt) as amount from payment where date <='".$apparent_date."'");
		$int = mysql_fetch_array($int_res);
		$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;
	
		//PENALITIES
		$pen_res = mysql_query("select sum(amount) as amount from penalty where date <='".$apparent_date."'");
		$pen = mysql_fetch_array($pen_res);
		$pen_amt = ($pen['amount'] != NULL) ? $pen['amount'] : 0;

		//OTHER INCOME
		$other_res = mysql_query("select sum(i.amount) as amount from other_income i join accounts a on i.account_id=a.id where i.date <= '".$apparent_date."'");
		$other = mysql_fetch_array($other_res);
		$other_amt = ($other['amount'] != NULL) ? $other['amount'] : 0;

		//COLLECTED
		$col_res = mysql_query("select sum(amount) as amount from collected where date <'".$apparent_date."'");
		$col = mysql_fetch_array($col_res);
		$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
	
		//RECOVERED
		$recovered_res = mysql_query("select sum(amount) as amount from recovered where date < '".$apparent_date."'");
		$recovered = mysql_fetch_array($recovered_res);
		$recovered_amt = ($recovered['amount'] != NULL) ? $recovered['amount'] : 0;

		//TRANSACTIONAL CHARGES
		$dep_res = mysql_query("select sum(flat_value + percent_value) as charge from deposit where date <='".$apparent_date."'");
		$dep = mysql_fetch_array($dep_res);
		$dep_charge = ($dep['charge'] != NULL) ? $dep['charge'] : 0;
		$with_res = mysql_query("select sum(flat_value + percent_value) as charge from withdrawal where date <='".$apparent_date."'");
		$with = mysql_fetch_array($with_res);
		$with_charge = ($with['charge'] != NULL) ? $with['charge'] : 0;

		//GAIN ON SALE OF FIXED ASSETS
		$fixed_res = mysql_query("select sum(s.amount - f.initial_value) as amount from fixed_asset f join sold_asset s on f.id=s.asset_id where f.date <'".$apparent_date."' and s.id in (select asset_id from sold_asset where date <='".$apparent_date."')");
		$fixed = mysql_fetch_array($fixed_res);
		$fixed_amt =($fixed['amount'] != NULL) ? $fixed['amount'] : 0;
		//GAIN ON SALE OF INVESTMENTS
		$invest_res = mysql_query("select sum(s.amount - i.amount) as amount from investments i join sold_invest s on i.id=s.investment_id where i.date <'".$apparent_date."' and i.id in (select investment_id from sold_invest where date <='".$apparent_date."')");
		$invest = mysql_fetch_array($invest_res);
		$invest_amt =($invest['amount'] != NULL) ? $invest['amount'] : 0;

		$charge_res = mysql_query("select sum(amount) as amount from monthly_charge where date <='".$last_date."'");
		$charge = mysql_fetch_array($charge_res);
		$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
		
		$income = $int_amt + $pen_amt + $other_amt + $recovered_amt + $col_amt + $fixed_amt + $invest_amt + $dep_charge + $with_charge + $charge_amt;
		
		
//OPERATING EXPENSES
		$expense_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where date <= '".$apparent_date."'");
		$expense = mysql_fetch_array($expense_res);
		$expense_amt = ($expense['amount'] != NULL) ? $expense['amount'] : 1;

		$payable_res = mysql_query("select sum(amount) as amount from payable_paid where date <'".$apparent_date."'");
		$payable = mysql_fetch_array($payable_res);
		$payable_amt = ($payable['amount'] != NULL) ? $payable['amount'] : 0;

		$shared_res = mysql_query("select sum(total_amount) as amount from share_dividends where date <='".$apparent_date."'");
		$shared = mysql_fetch_array($shared_res);
		$shared_amt = ($shared['amount'] != NULL) ? $shared['amount'] : 0;

		$written_res = mysql_query("select sum(amount) as amount from written_off where date <='".$apparent_date."'");
		$written = mysql_fetch_array($written_res);
		$written_amt = ($written['amount'] != NULL) ? $written['amount'] : 0;
		$expense = $expense_amt + $payable_amt + $shared_amt + $written_amt;
		$expense = ($expense != 0) ? $expense : 1;

		$percent = ($income / $expense) * 100.00;
		$percent = sprintf("%.02f", $percent);
		if($i == 0)
			$chosen_perc = $percent;
		elseif($i == 1)
			$last1_perc = $percent;
		elseif($i == 2)
			$last2_perc = $percent;
	}	
	$content .= "<tr bgcolor=lightgrey><td>Financial Self-Sufficiency (%) </td><td>".$chosen_perc."</td><td>".$last1_perc."</td><td>".$last2_perc."</td></tr>";
	


	//LIQUIDITY RATIO
	$content .= "<tr bgcolor=white><td><b>6. Liquidity:</b></td><td></td><td></td><td></td></tr>";
	for($i=0; $i <3; $i++){
		if($i == 0)
			$apparent_date = $chosen_date;
		elseif($i==1)
			$apparent_date = $last1_date;
		elseif($i == 2)
			$apparent_date = $last2_date;
		//CASH
		//DEPOSITS
		$dep_res = mysql_query("select sum(amount) as amount from deposit where  date <='".$apparent_date."' and bank_account >0");
		$dep = mysql_fetch_array($dep_res);
		//WITHDRAWALS
		$with_res = mysql_query("select sum(amount) as amount from withdrawal where  date <='".$apparent_date."'");
		$with = mysql_fetch_array($with_res);
		//OTHER INCOME
		$other_res = mysql_query("select sum(amount) as amount from other_income where  date <='".$apparent_date."'");
		$other = mysql_fetch_array($other_res);
		//EXPENSES
		$expense_res = mysql_query("select sum(amount) as amount from expense where  date <='".$apparent_date."'");
		$expense = mysql_fetch_array($expense_res);
		//PAYABLE PAID
		$payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where  date <='".$apparent_date."'");	
		$payable_paid = mysql_fetch_array($payable_paid_res);
		//RECEIVALE COLLECTED
		$collected_res = mysql_query("select sum(amount) as amount from collected where  date <='".$apparent_date."'");
		$collected = mysql_fetch_array($collected_res);
		//DISBURSED LOANS
		$disb_res = mysql_query("select sum(amount) as amount from disbursed where  date <= '".$apparent_date."'");
		$disb = mysql_fetch_array($disb_res);
		//PAYMENTS
		$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id  where p.date <= '".$apparent_date."'");
		$pay = mysql_fetch_array($pay_res);
		//PENALTIES
		$pen_res = mysql_query("select sum(p.amount) as amount from penalty p join disbursed d on p.loan_id=d.id where  p.status='paid' and p.date <= '".$apparent_date."'");
		$pen = mysql_fetch_array($pen_res);
								
		//SHARES
		$shares_res = mysql_query("select sum(value) as amount from shares where date <'".$apparent_date."'");
		$shares = mysql_fetch_array($shares_res); 
		//RECOVERED
		$rec_res = mysql_query("select sum(r.amount) as amount from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where  r.date <= '".$apparent_date."'");
		$rec = mysql_fetch_array($rec_res);		
		//INVESTMENTS 
		$invest_res = mysql_query("select sum(amount) as amount from investments where date < '".$apparent_date."'");
		$invest = mysql_fetch_array($invest_res);
		$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
		$soldinvest_res = mysql_query("select sum(amount) as amount from sold_invest where date <='".$apparent_date."'");
		$soldinvest = mysql_fetch_array($soldinvest_res);

		//FIXED ASSETS 
		$fixed_res = mysql_query("select sum(initial_value) as amount from fixed_asset where  date <='".$apparent_date."'");
		$fixed = mysql_fetch_array($fixed_res);
		$soldasset_res = mysql_query("select sum(amount) as amount from sold_asset where date <='".$apparent_date."'");
		$soldasset = mysql_fetch_array($soldasset_res);
									
		$cash_amt =  $collected['amount'] + $dep['amount'] + $other['amount'] - $with['amount'] - $expense['amount'] -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'];	

		//LIQUID INVESTMENTS
		$invest_res = mysql_query("select sum(i.amount) as amount from investments i join accounts a on i.account_id=a.id where i.date < '".$apparent_date."' and i.id not in (select investment_id from sold_invest) and a.account_no like '113%'");
		$invest = mysql_fetch_array($invest_res);
		$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
		$liquid_amt = $cash_amt + $invest_amt;
		

		//CURRENT LIABILITIES
		//SAVINGS
		$dep_res = mysql_query("select sum(amount - flat_value - percent_value) as amount from deposit where  date <='".$apparent_date."' and bank_account >0");
		$dep = mysql_fetch_array($dep_res);
		$dep_amt = ($dep['amount'] != NULL) ? $dep['amount'] : 0;
		$with_res = mysql_query("select sum(amount + flat_value + percent_value) as amount from withdrawal where  date <='".$apparent_date."'");
		$with = mysql_fetch_array($with_res);
		$with_amt = ($with['amount'] != NULL) ? $with['amount'] : 0;
		$savings = $dep_amt - $with_amt;
		//INTEREST PAYABLE ON SAVINGS
		$int_res = mysql_query("select sum(amount) as amount from save_interest where date <= '".$apparent_date."'");
		$int = mysql_fetch_array($int_res);
		$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;
		//OTHER SHORT-TERM LIABILITIES
		$payable_res = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '2%' and a.account_no not like '2122%' and p.maturity_date='".$apparent_date."'");
		$payable = mysql_fetch_array($pay_res);
		$payable_amt = ($payable['amount'] != NULL) ? $payable['amount'] : 0;

		$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id join accounts a on p.account_id=a.id where a.account_no like '2%' and a.account_no not like '2122%' and p.maturity_date='".$apparent_date."' and paid.date <='".$apparent_date."'");
		
		$paid = @mysql_fetch_array($paid_res);
		$paid_amt = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
		$payable_amt = $payable_amt - $paid_amt;
		$liabilities = $payable_amt + $savings + $int_amt;
		$liabilities = ($liabilities > 0) ? $liabilities  : 1;

		$percent = ($liquid_amt / $liabilities) * 100.00;
		$percent = sprintf("%.02f", $percent);
		if($i == 0)
			$chosen_perc = $percent;
		elseif($i == 1)
			$last1_perc = $percent;
		elseif($i == 2)
			$last2_perc = $percent;
	}	
	$content .= "<tr bgcolor=lightgrey><td>Liquidity Ratio (%) </td><td>".$chosen_perc."</td><td>".$last1_perc."</td><td>".$last2_perc."</td></tr>";

	//OPERATING EFFICIENCY
	$content .= "<tr bgcolor=white><td><b>7. Operating Efficiency:</b> </td><td></td><td></td><td></td></tr>";
	for($i=0; $i <3; $i++){
		if($i == 0)
			$apparent_date = $chosen_date;
		elseif($i==1)
			$apparent_date = $last1_date;
		elseif($i == 2)
			$apparent_date = $last2_date;
	//OPERATING INCOME
		//INCOME FROM LOANS
		$int_res = mysql_query("select sum(int_amt) as amount from payment where date <='".$apparent_date."'");
		$int = mysql_fetch_array($int_res);
		$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;

		//PENALITIES
		$pen_res = mysql_query("select sum(amount) as amount from penalty where date <='".$apparent_date."'");
		$pen = mysql_fetch_array($pen_res);
		$pen_amt = ($pen['amount'] != NULL) ? $pen['amount'] : 0;

		//OTHER INCOME
		$other_res = mysql_query("select sum(amount) as amount from other_income where date <= '".$apparent_date."'");
		$other = mysql_fetch_array($other_res);
		$other_amt = ($other['amount'] != NULL);
		
		//TRANSACTIONAL CHARGES
		$dep_res = mysql_query("select sum(flat_value + percent_value) as charge from deposit where date <='".$apparent_date."'");
		$dep = mysql_fetch_array($dep_res);
		$dep_charge = ($dep['charge'] != NULL) ? $dep['charge'] : 0;
		$with_res = mysql_query("select sum(flat_value + percent_value) as charge from withdrawal where date <='".$apparent_date."'");
		$with = mysql_fetch_array($with_res);
		$with_charge = ($with['charge'] != NULL) ? $with['charge'] : 0;

		$charge_res = mysql_query("select sum(amount) as amount from monthly_charge where date <='".$last_date."'");
		$charge = mysql_fetch_array($charge_res);
		$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;

		$income = $int_amt + $pen_amt + $other_amt + $dep_charge + $with_charge + $charge_amt;
		$income_amt = ($income != 0) ? $income : 1;
		
//OPERATING EXPENSES
		$expense_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where date <= '".$apparent_date."'");
		$expense = mysql_fetch_array($expense_res);
		$expense_amt = ($expense['amount'] != NULL) ? $expense['amount'] : 0;
		
		$percent = ($expense_amt / $income_amt) * 100.00;
		$percent = sprintf("%.02f", $percent);
		if($i == 0)
			$chosen_perc = $percent;
		elseif($i == 1)
			$last1_perc = $percent;
		elseif($i == 2)
			$last2_perc = $percent;
	}	
	$content .= "<tr bgcolor=lightgrey><td>Operating Expense Ratio (%) </td><td>".$chosen_perc."</td><td>".$last1_perc."</td><td>".$last2_perc."</td></tr>";
	for($i=0; $i <3; $i++){
		if($i == 0)
			$apparent_date = $chosen_date;
		elseif($i==1)
			$apparent_date = $last1_date;
		elseif($i == 2)
			$apparent_date = $last2_date;
		$loan_res = mysql_query("select sum(amount) as amount from disbursed where date <'".$apparent_date."'");
		$loan = mysql_fetch_array($loan_res);
		$loan_amt = ($loan['amount'] != NULL) ? $loan['amount'] : 0;

		$paid_res = mysql_query("select sum(princ_amt) as amount from payment where date<='".$apparent_date."'");
		$paid = mysql_fetch_array($paid_res);
		$paid_amt = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
		$portifolio = $loan_amt - $paid_amt;

		$no_res = mysql_query("select amount, id from disbursed where date <='".$apparent_date."'");
		$x=0;
		while($no = mysql_fetch_array($no_res)){
			$paid_res = mysql_query("select sum(princ_amt) as amount from payment where date <='".$apparent_date."' and loan_id='".$no['id']."'");
			$paid = mysql_fetch_array($paid_res);
			$paid_amt = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
			if($no['amount'] >= $paid_amt)
				$x++;
		}
		$x = ($x >0) ? $x : 1;
		$percent = $portifolio / $x;
		$percent = sprintf("%.02f", $percent);
		if($i == 0)
			$chosen_perc = $percent;
		elseif($i == 1)
			$last1_perc = $percent;
		elseif($i == 2)
			$last2_perc = $percent;
	}
	$content .= "<tr bgcolor=white><td>Average Loan Portfolio (%) </td><td>".$chosen_perc."</td><td>".$last1_perc."</td><td>".$last2_perc."</td></tr>";




	//CAPITAL RATIO
	$content .= "<tr bgcolor=lightgrey><td><b>8. Capital Ratio:</b> </td><td></td><td></td><td></td></tr>";
	for($i=0; $i <3; $i++){
		if($i == 0)
			$apparent_date = $chosen_date;
		elseif($i==1)
			$apparent_date = $last1_date;
		elseif($i == 2)
			$apparent_date = $last2_date;
	//LONG-TERM LOANS PAYABLE
		$payable_res = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '2122%' and p.maturity_date='".$apparent_date."'");
		$payable = mysql_fetch_array($pay_res);
		$payable_amt = ($payable['amount'] != NULL) ? $payable['amount'] : 0;

		$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id join accounts a on p.account_id=a.id where a.account_no like '2%' and a.account_no not like '2122%' and p.maturity_date='".$apparent_date."' and paid.date <='".$apparent_date."'");
		$paid = @mysql_fetch_array($paid_res);
		$paid_amt = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
		$debt_amt = $payable_amt - $paid_amt;
		
		//CAPITAL
		$shares_res = mysql_query("select sum(value) as amount from shares where date <='".$apparent_date."'");
		$shares = mysql_fetch_array($shares_res);
		$shares_amt = ($shares['amount'] != NULL) ? $shares['amount'] : 0;

		$don_res = mysql_query("select sum(i.amount) as amount from other_income i join accounts a on i.account_id=a.id where a.account_no like '3313%' and i.date <= '".$apparent_date."'");
		$don = mysql_fetch_array($don_res);
		$don_amt = ($don['amount'] != NULL) ? $don['amount'] : 0;

		$equity_amt = $don_amt + $shares_amt;
		$equity_amt = ($equity_amt > 1) ? $equity_amt : 1;
		$percent = ($debt_amt / $equity_amt) * 100.00;
		$percent = sprintf("%.02f", $percent);
		if($i == 0)
			$chosen_perc = $percent;
		elseif($i == 1)
			$last1_perc = $percent;
		elseif($i == 2)
			$last2_perc = $percent;
	}	
	$content .= "<tr bgcolor=white><td>Debt To Equity Ratio (%) </td><td>".$chosen_perc."</td><td>".$last1_perc."</td><td>".$last2_perc."</td></tr>";

	$content .= "</table>";
	export($format, $content);
}
reports_header();
ratios($_GET['year'], $_GET['month'], $_GET['mday'], $GET['format']);
reports_footer();
?>
