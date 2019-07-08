<?php

if(!isset($_GET['format']))
echo("<head>
	<title>SUBSIDIARY TRIAL BALANCE - LIABILITIES & CAPITAL</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");
if($_SESSION['user_id'] ==0 || !isset($_SESSION['user_id']))
		header("Location: index.php");
//SUB (ASSETS) TRIAL BAL
function lctrial_balance($year, $month, $mday, $basis, $format,$branch_id){
	if($year == ''){
		$year = date('Y');
		$month = date('m');
		$mday = date('d');
	}
	$date = sprintf("%d-%02d-%02d", $year, $month, $mday);
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$content = "<center><font color=#00008b size=5pt><b>".$branch['branch_name']."</b></font></center><br>";
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:"and branch_id=".$branch_id;
	
	$content .= "<center><font color=#00008b size=2pt><b>".$basis."</b></font><center><br>
	<center><font color=#00008b size=3pt><b>SUBSIDIARY TRIAL BALANCE AS AT ".$date."</center><br><center>LIABILITIES AND CAPITAL</center></b></font><br>
		<table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2'>
		<tr CLASS='headings'><td><font size=5pt><b>ACCOUNT</b></font></td><td><font size=5pt><b>DESCRIPTION</b></font></td><td><font size=5pt><b>BALANCE</b></font></td></tr>";
		$level2_res= mysql_query("select * from accounts where account_no >20 and account_no <40 ".$branch_." order by account_no");
		$capital = 0;
		$liabilities = 0;
		$content .= "<tr bgcolor=lightgrey><td><font size=5pt><b>2</b></font></td><td><font size=5pt><b>LIABILITIES</b></font></td><td><font size=5pt><b></b></font></td></tr>";
		$i=1;
		while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
			$color = ($i%2 == 0) ? "lightgrey" : "white";
			if($level2['account_no'] ==31){
				$content .= "<tr bgcolor=$color><td><font size=5pt><b>2</b></font></td><td><font size=5pt><b>LIABILITIES</b></font></td><td><font size=5pt><b>".number_format($liabilities, 2)."</b></font></td></tr>";
				$i++;
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .="<tr bgcolor=$color><td><font size=5pt><b>3</b></font></td><td><font size=5pt><b>CAPITAL</b></font></td><td></td></tr>";
				$i++;
			}
			$color = ($i%2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color><td><font size=4pt><b>".$level2['account_no']."</b></font></td><td><font size=4pt><b>".$level2['name']."</b></font></td><td></td></tr>";
			$i++;
			$level3_res = mysql_query("select * from accounts where account_no >=211 and account_no <= 399 and account_no like '".$level2['account_no']."%' order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td><b>".$level3['account_no']."</b></td><td><b>".$level3['name']."</b></td><td></td></tr>";
				$i++;
				$grand_total = 0;
				$savings = 0;
				if($level3['account_no'] == '211'){  //SAVINGS & DEPOSITS
					//MONTHLY CHARGES
					$charge_res = mysql_query("select sum(amount) as amount from monthly_charge where date <= '".$date."'");
					$charge = mysql_fetch_array($charge_res);
					$grand_total -= $charge['amount'];
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=2111 and account_no <= 2119");
					while($level4 = mysql_fetch_array($level4_res)){
						if($level4['name'] == 'Compulsory Savings')
							continue;
						$sub1_total = 0;
						$prod_res = mysql_query("select * from savings_product where account_id='".$level4['id']."'");
						if(mysql_numrows($prod_res) > 0){
							$prod = mysql_fetch_array($prod_res);
							$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and d.date<= '".$date."'");
							$dep = mysql_fetch_array($dep_res);
							$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.id='".$prod['id']."' and p.type='free' and w.date<= '".$date."'");
							$with = mysql_fetch_array($with_res);
							$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and i.date<= '".$date."'");
							$int = mysql_fetch_array($int_res);
							$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
							//OFFSETTING FROM SAVINGS
							$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' and p.date<= '".$date."'");
							$pay = mysql_fetch_array($pay_res);
							$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;
							$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') and o.date <= '".$date."'"));
							$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;
							$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
							$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
							$sub1_total = $dep_amt +$int_amt - $with_amt - $income_amt - $pay_amt;  // + $int['amount'];
						}else{
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 211101 and account_no <= 211999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total =0;
								$prod_res = mysql_query("select * from savings_product where account_id='".$level5['id']."'");
								if(mysql_numrows($prod_res) > 0){
									$prod = mysql_fetch_array($prod_res);
									$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and d.date<= '".$date."'");
									$dep = mysql_fetch_array($dep_res);
									$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.id='".$prod['id']."' and p.type='free' and w.date<= '".$date."'");
									$with = mysql_fetch_array($with_res);
									$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and i.date<= '".$date."'");
									$int = mysql_fetch_array($int_res);
									$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
									//OFFSETTING FROM SAVINGS
									$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' and p.date<= '".$date."'");
									$pay = mysql_fetch_array($pay_res);
									$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;
									$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') and o.date <= '".$date."'"));
									$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;

									$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
									$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
									$sub2_total = $dep_amt + $int_amt - $with_amt - $income_amt - $pay_amt;  // + $int['amount'];
									$sub1_total = $sub1_total + $sub2_total;
								}else{
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 21110101 and account_no <= 21199999");
									while($level6 = mysql_fetch_array($level6_res)){
										$prod_res = mysql_query("select * from savings_product where account_id='".$level6['id']."'");
										if(mysql_numrows($prod_res) > 0){
											$prod = mysql_fetch_array($prod_res);
											$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and d.date<= '".$date."'");
											$dep = mysql_fetch_array($dep_res);
											$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.id='".$prod['id']."' and p.type='free' and w.date<= '".$date."'");
											$with = mysql_fetch_array($with_res);
											$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') and o.date <= '".$date."'"));
											$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;
											$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and i.date<= '".$date."'");
											$int = mysql_fetch_array($int_res);
											$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
											//OFFSETTING FROM SAVINGS
											$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' and p.date<= '".$date."'");
											$pay = mysql_fetch_array($pay_res);
											$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;
											$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
											$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
											$sub2_total = $sub2_total + $dep_amt + $int_amt - $with_amt - $income_amt - $pay_amt;  // + $int['amount'];
										}
									}
									$sub1_total = $sub1_total + $sub2_total;
								}
								$color = ($i%2 == 0) ? "lightgrey" : "white";
								$content .="<tr bgcolor=$color><td>".$level5['account_no']."</td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>"; 
								$i++;
							}
						}
						$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .="<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						$i++;
						$savings += $sub1_total;
						$grand_total = $grand_total + $sub1_total;
				}
				$level2_total += $grand_total;
				$liabilities += $grand_total;
			}elseif($level3['account_no'] >='212' && $level3['account_no'] <= 299){
				$grand_total=0;
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=2121 and account_no <= 2999");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['name'] == 'Interest Payable on Savings Deposits'){
						/* 
						$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.name like '%Savings%' and i.date <'".$date."'");
						
						$int = mysql_fetch_array($int_res);
						$sub1_total = $int['amount'];
						*/
						;
					}elseif($level4['name'] == 'Interest Payable on Time Deposits'){
						/*
						$int_res = mysql_query("select sum(amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.name like '%Time%' and i.date <'".$date."'");
						$int = mysql_fetch_array($int_res);
						$sub1_total = $int['amount'];
						*/
						;
					}elseif($level4['name'] == 'Dividends Payable'){
						$div_res = mysql_query("select sum(total_amount) as amount from  share_dividends where bank_account=0 and date<= '".$date."'");
						$div = mysql_fetch_array($div_res);
						//$dividends = intval($div['amount']);
						$sub1_total = intval($div['amount']);
						//$interest_awarded += $sub1_total;
					}else{
						//if($basis == 'Cash Basis' && !preg_match("/^212/", $level4['account_no']))
							//continue;
						$pay_res = mysql_query("select sum(amount) as amount from payable where account_id='".$level4['id']."' and date<= '".$date."'");
						$pay = mysql_fetch_array($pay_res);
						if($pay['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 212101 and account_no <= 213999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$pay_res = mysql_query("select sum(amount) as amount from payable where account_id='".$level5['id']."' and date <= '".$date."'");
								$pay = mysql_fetch_array($pay_res);
								if($pay['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >=21210101 and account_no <= 21399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$pay_res = mysql_query("select sum(amount) as amount from payable where account_id='".$level6['id']."' and date <= '".$date."'");
										$pay = mysql_fetch_array($pay_res);
										$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id where paid.date <= '".$date."' and p.account_id='".$level4['id']."'");
										$paid = mysql_fetch_array($paid_res);
										$sub2_total = $sub2_total + $pay['amount'] - $paid['amount'];
									}
								}else{
									$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id where paid.date <= '".$date."' and p.account_id='".$level5['id']."'");
									$paid = mysql_fetch_array($paid_res);
									$sub2_total = $pay['amount'] - $paid['amount'];
								}
								$color = ($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td>".$level5['account_no']."</td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
								$i++;
							$sub1_total += $sub2_total;
							}
						}else{
							$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id where paid.date <= '".$date."' and p.account_id='".$level4['id']."'");
							$paid = mysql_fetch_array($paid_res);
							$sub1_total = $pay['amount'] - $paid['amount'];
						}
					}
					$grand_total = $grand_total + $sub1_total;
					$level2_total += $sub1_total;
					$liabilities += $sub1_total;
					$color = ($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
					$i++;
				}
			}elseif($level3['account_no'] == '311'){			
				$share_res = mysql_query("select sum(value) as value from shares where date <'".$date."'");
				$share = mysql_fetch_array($share_res);
				$level4_res = mysql_query("select * from accounts where name like 'Member Shares'");
				$level4 = mysql_fetch_array($level4_res);
				$sub1_total = $share['value'];
				$grand_total = ($sub1_total > 0) ? $sub1_total : 0;
				$level2_total += $grand_total; 
				$capital += $grand_total;
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
				$i++;
			}elseif($level3['account_no'] == '341'){
				$div_res = mysql_query("select sum(total_amount) as amount from share_dividends where date like '".$year."-%'");
				$div = mysql_fetch_array($div_res);
				$level4_res = mysql_query("select * from accounts where account_no like '3411'");
				$level4 = mysql_fetch_array($level4_res);
				//$sub1_total = ($div['amount'] != NULL) ? $div['amount'] : 0;
				//$grand_total = $grand_total + $div['amount'];
				//$level2_total += $div['amount'];
				//$capital += $div['amount'];
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
			}else{
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 3211 and account_no <= 3399");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					$invest_res = mysql_query("select sum(amount) as amount from investments where account_id='".$level4['id']."' and date <= '".$date."'");
					if(mysql_numrows($invest_res) == NULL){
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 321101 and account_no <= 339999");
						while($level5 = mysql_fetch_array($level5_res)){
							$sub2_total = 0;
							$invest_res = mysql_query("select sum(amount) as amount from investments where account_id='".$level5['id']."' and date < '".$date."'");
							if(mysql_numrows($invest_res) == NULL){
								$level6_res = mysql_query("select  * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 32110101 and account_no <= 33999999");
								while($level6 = mysql_fetch_array($level6_res)){
									$invest_res = mysql_query("select sum(amount) as amount from investments where account_id='".$level6['id']."' and date < '".$date."'");
									$invest = mysql_fetch_array($invest_res);
									$sub2_total = $sub2_total + $invest['amount'];
								}
							}else
								$sub2_total = $invest['amount'];
							$color = ($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td>".$level5['account_no']."</td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
							$i++;
							$sub1_total = $sub1_total + $sub2_total;
						}
					}else{
						$invest = mysql_fetch_array($invest_res);
						$sub1_total = $invest['amount'];
					}
					$color = ($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td>".number_format($sub1_total, 2)."</td></tr>";
					$i++;
					$grand_total = $grand_total + $sub1_total;
					$level2_total += $sub1_total;
					$capital += $sub1_total;
				}
			}
			$color = ($i%2 == 0) ? "lightgrey" : "white";

			$content .= "<tr bgcolor=$color><td><b>".$level3['account_no']."</b></td><td><b> ".$level3['name']."</b></td><td><b>".number_format($grand_total, 2)."</b></td></tr>";
			$i++;
		}
		$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr bgcolor=$color><td><font size=4pt><b>".$level2['account_no']."</b></font></td><td><font size=4pt><b> ".$level2['name']."</b></font></td><td><font size=4pt><b>".number_format($level2_total, 2)."</b></font></td></tr>";
		$i++;
	}
	$color = ($i%2 == 0) ? "lightgrey" : "white";
	$content .= "<tr bgcolor=$color><td><font size=5pt><b>3</b></font></td><td><font size=5pt><b>CAPITAL</b></font></td><td><font size=5pt><b>".number_format($capital, 2)."</b></font></td></tr>";
	$i++;
	$color = ($i%2 == 0) ? "lightgrey" : "white";
	$content .="<tr bgcolor=$color><td><font size=5pt><b>2 + 3</b></font></td><td><font size=5pt><b>LIABILITIES + CAPITAL</b></font></td><td><font size=5pt><b>".number_format(($capital + $liabilities), 2)."</b></font></td></tr></table>";
	export($format, $content);
}
//reports_header();
lctrial_balance($_GET['year'], $_GET['month'], $_GET['mday'], $_GET['basis'], $_GET['format'],$_GET['branch_id']);
//reports_footer();
?>
