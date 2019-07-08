<?php

if(!isset($_GET['format']))
echo("<head>
	<title> SUBSIDIARY TRIAL BALANCE - INCOME & EXPENSES</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");

//SUB (INCOME & EXPENSES) TRIAL BAL
function incometrial_balance($year, $month, $mday, $basis, $format, $branch_id){
	if($year == ''){
		$year = date('Y');
		$month = date('m');
		$mday = date('d');
	}
	
	$date = sprintf("%d-%02d-%02d", $year, $month, $mday);
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:"and branch_id=".$branch_id;
	$content = "<center><font color=#00008b size=5pt><b>".$branch['branch_name']."</b></font></center><br>
	<center><font color=#00008b size=2pt><b>".$basis."</b></font><center><br>
	<center><font color=#00008b size=3pt><b>SUBSIDIARY TRIAL BALANCE AS AT ".$date."</center><br><center>INCOME AND EXPENSES</center></b></font><br>
<table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='70%' id='AutoNumber2' align=center>
		<tr class='headings'><td><font size=5pt><b>ACCOUNT</b></font></td><td><font size=5pt><b>DESCRIPTION</b></font></td><td><font size=5pt><b>BALANCE</b></font></td></tr>";
		$level2_res= mysql_query("select * from accounts where account_no >40 and account_no <=49 ".$branch_." order by account_no");
		$income = 0;
		$i=1;
		$content .= "<tr bgcolor=lightgrey><td><font size=5pt><b>4</b></font></td><td><font size=5pt><b>INCOME</b></font></td><td><font size=5pt><b></b></font></td></tr>";
				while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
			$color = ($i%2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color><td><font size=4pt><b>".$level2['account_no']."</b></font></td><td><font size=4pt><b>".$level2['name']."</b></font></td><td></td></tr>";
			$i++;
			$level3_res = mysql_query("select * from accounts where account_no >=411 and account_no <= 599 and account_no like '".$level2['account_no']."%' order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color ><td><b>".$level3['account_no']."</b></td><td><b>".$level3['name']."</b></td><td></td></tr>";
				$i++;
				$grand_total = 0;
				if($level3['account_no'] == '411'){
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4111 and account_no <= 4119");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						if($level4['account_no'] == 4111)
							$int_res = mysql_query("select sum(p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product prod on applic.product_id=prod.id join accounts a on prod.account_id=a.id where p.date <='".$date."' and a.name like '%Short%Term%'");
						if($level4['account_no'] == 4112)
							$int_res = mysql_query("select sum(p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product prod on applic.product_id=prod.id join accounts a on prod.account_id=a.id where p.date <='".$date."' and a.name like '%Medium%Term%'");
						if($level4['account_no'] == 4113)
							$int_res = mysql_query("select sum(p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product prod on applic.product_id=prod.id join accounts a on prod.account_id=a.id where p.date <='".$date."' and a.name like '%Long%Term%'");
						if($level4['account_no'] == 4114)
							$int_res = mysql_query("select sum(amount) as amount from penalty where date <='".$date."' and status='paid'");
						if($level4['account_no'] > 4114)
							$int_res = mysql_query("select sum(i.amount) as amount from other_income i join accounts a on i.account_id=a.id where a.account_no like '".$level4['account_no']."%' and i.date <='".$date."'");
						$int = mysql_fetch_array($int_res);
						if($int['amount'] != NULL)
							$sub1_total = $int['amount'];
						$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						$i++;
						$grand_total += $sub1_total;
					}
				}elseif($level3['account_no'] >= 412 && $level3['account_no'] <= 419){   //RECEIVABLES
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4121 and account_no <= 4199 and account_no not like '%2'");
					$pre = ($basis == 'Cash Basis') ? "(Received)" : "";
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$rec_res = mysql_query("select sum(amount) as amount from receivable where account_id='".$level4['id']."' and maturity_date <= '".$date."'");
						$rec = mysql_fetch_array($rec_res);
						
						if($rec['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 412101 and account_no <= 419999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$rec_res = mysql_query("select sum(amount) as amount from receivable where account_id='".$level5['id']."' and maturity_date <= '".$date."'");
								$rec = mysql_fetch_array($rec_res);
								if($rec['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 41210101 and account_no <= 41999999");
									while($level6 = mysql_fetch_array($level6_res)){
										$rec_res = mysql_query("select sum(amount) as amount from receivable where account_id='".$level6['id']."' and maturity_date <= '".$date."'");
										$rec = mysql_fetch_array($rec_res);
										$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where r.account_id='".$level6['id']."' and r.maturity_date <= '".$date."' and c.date <= '".$date."'");
										$col = mysql_fetch_array($col_res);
										$rec_amt = ($rec['amount'] != NULL) ? $rec['amount'] : 0;
										$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
										$top_up = ($basis == 'Accrual Basis') ? $rec_amt : $col_amt;
										$sub2_total += $top_up;
									}
								}else{
									$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where r.account_id='".$level5['id']."' and r.maturity_date <= '".$date."' and c.date <= '".$date."'");
									$col = mysql_fetch_array($col_res);
									$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
									$sub2_total = ($basis == 'Accrual Basis') ? $rec['amount'] : $col_amt;
								}
								$color = ($i%2 == 0) ? "lightgrey" : "white";
								
								$content .= "<tr bgcolor=$color><td><font size=4pt>".$level5['account_no']."</font></td><td><font size=4pt>".$level5['name']." ".$pre."</font></td><td>".number_format($sub2_total, 2)."</td></tr>";
								$i++;
								$sub1_total += $sub2_total;
							}
						}else{
							$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where r.account_id='".$level6['id']."' and r.maturity_date <= '".$date."' and c.date <= '".$date."'");
							$col = mysql_fetch_array($col_res);
							
							$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
							$top_up = ($basis == 'Accrual Basis') ? $rec['amount'] : $col_amt;
							$sub1_total += $top_up;
						}
						$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']." ".$pre."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						$i++;
						$grand_total += $sub1_total;
					}

					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4121 and account_no <= 4199 and account_no like '%2'");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						//INCOME REGISTERED DIRECTLY
						$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.account_no='".$level4['account_no']."' and o.date <='".$date."'");
						$inc = mysql_fetch_array($inc_res);
						if($inc['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 412101 and account_no <= 419999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.account_no='".$level5['account_no']."' and o.date <='".$date."'");
								$inc = mysql_fetch_array($inc_res);
								if($inc['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 41210101 and account_no <= 41999999");
									while($level6 = mysql_fetch_array($level6_res)){
										$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.account_no='".$level5['account_no']."' and o.date <='".$date."'");
										$inc = mysql_fetch_array($inc_res);						
										$sub2_total += $inc['amount'];
									}
								}else{
									$sub2_total = $inc['amount'];
								}
								$color = ($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td><font size=4pt>".$level5['account_no']."</font></td><td><font size=4pt>".$level5['name']."</font></td><td>".number_format($sub2_total, 2)."</td></tr>";
								$i++;
								$sub1_total += $sub2_total;
							}
						}else{
							$sub1_total = $inc['amount'];
					}
					$color = ($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
					$i++;
					$grand_total += $sub1_total;
				}
			}elseif($level3['account_no'] == '424'){
				$inv_res = mysql_query("select sum(quantity * purchase_price) as tot_cost, sum(quantity * amount) as tot_gain from sold_invest where date <= '".$date."'");
				$inv = mysql_fetch_array($inv_res);
				if($inv['tot_cost'] == NULL){
					$grand_total =0;
				}else{
					$grand_total = $inv['tot_gain'] - $inv['tot_cost'];
				}
			}elseif($level3['account_no'] >= 421){
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4141 and account_no <= 4299");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['account_no'] == '4221'){  //MONTHLY CHARGES
						$charge_res = mysql_query("select sum(amount) as amount from monthly_charge where date <= '".$date."'");
						$charge = mysql_fetch_array($charge_res);
						$sub1_total = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
					}elseif($level4['account_no'] == '4222'){   //TRANSACTIONAL CHARGES
						$dep_res = mysql_query("select sum(flat_value + percent_value) as amount from deposit where date <= '".$date."'");
						$dep = mysql_fetch_array($dep_res);

						$with_res = mysql_query("select sum(flat_value + percent_value) as amount from withdrawal where date <= '".$date."'");
						$with = mysql_fetch_array($with_res);
						$sub1_total = $dep['amount'] + $with['amount'];
					}elseif($level4['account_no'] == '4223'){   //INCOME FROM SALE OF ASSETS
						$sold_res = mysql_query("select sum(amount) as amount from sold_asset where date <='".$date."'");
						$sold = mysql_fetch_array($sold_res);
						$sub1_total = ($sold['amount'] != NULL) ? $sold['amount'] : 0;
					}else{	
						$inc_res = mysql_query("select sum(amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level4['id']."' and o.date <= '".$date."'");
						$inc = mysql_fetch_array($inc_res);
						if($inc['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= '421101' and account_no <= '429999'");
							//$resp->AddAppend("status", "innerHTML", "select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= '414101' and account_no <= '429999'<BR>");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$inc_res = mysql_query("select sum(amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level5['id']."' and o.date <= '".$date."'");
								
								$inc = mysql_fetch_array($inc_res);
								if($inc['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 42110101 and account_no <= 42999999");
									while($level6 = mysql_fetch_array($level6_res)){
										$inc_res = mysql_query("select sum(amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level6['id']."' and o.date <= '".$date."'");
										$inc = mysql_fetch_array($inc_res);
										$sub2_total += $inc['amount'];
									}
								}else{
									$sub2_total = $inc['amount'];
									//$resp->AddAlert("Hi");
								}
								$color = ($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td>".$level5['account_no']."</td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
								$i++;
								$sub1_total += $sub2_total;
							}
							
						}else{
							
							$sub1_total += $inc['amount'];
						}
						
					}
					$color = ($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						$i++;
					$grand_total += $sub1_total;
				}
			}
		$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr bgcolor=$color><td><b>".$level3['account_no']."</b></td><td><b>".$level3['name']."</b></td><td><b>".number_format($grand_total, 2)."</b></td></tr>";
		$i++;
		$level2_total += $grand_total;
		}
		$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr bgcolor=$color><td><font size=4pt><b>".$level2['account_no']."</b></font></td><td><font size=4pt><b>".$level2['name']."</b></font></td><td><font size=4pt><b>".number_format($level2_total, 2)."</b></font></td></tr>";
		$i++;
		$income += $level2_total;
	}
	$color = ($i%2 == 0) ? "lightgrey" : "white";
	$content .= "<tr bgcolor=$color><td><font size=5pt><b>4</b></font></td><td><font size=5pt><b>INCOME</b></font></td><td><font size=4pt><b>".number_format($income, 2)."</b></font></td></tr>";
	$i++;

// EXPENSES
	$color = ($i%2 == 0) ? "lightgrey" : "white";
	$content .= "<tr bgcolor=$color><td><font size=5pt><b>5</b></font></td><td><font size=5pt><b>COSTS & EXPENSES</b></font></td><td><font size=4pt><b></b></font></td></tr>";
	$i++;
	$level2_res = mysql_query("select * from accounts where account_no >50 and account_no <60 order by account_no");
	while($level2 = mysql_fetch_array($level2_res)){
		$level2_total = 0;
		$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr bgcolor=$color><td><font size=4pt=><b>".$level2['account_no']."</b></font></td><td><font size=4pt><b>".$level2['name']."</b></font></td><td></td></tr>";
		$i++;
		$level3_res = mysql_query("select * from accounts where account_no like '".$level2['account_no']."%' and account_no >= 511 and account_no <= 599");
		while($level3 = mysql_fetch_array($level3_res)){
			$grand_total = 0;
			$color = ($i%2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color><td><b>".$level3['account_no']."</b></td><td><b>".$level3['name']."</b></td><td></td></tr>";
			$i++;
			if($level3['account_no'] == 511){
				$prod_res = mysql_query("select s.id as id, a.name as name, a.account_no as account_no from savings_product s  join accounts a on s.account_id=a.id where s.type='free' order by a.account_no");
				while($prod = mysql_fetch_array($prod_res)){
					$int_res = mysql_query("select sum(amount) as amount from save_interest  i join mem_accounts m on i.memaccount_id=m.id where m.saveproduct_id='".$prod['id']."' and i.date <='".$date."'");
					$int = mysql_fetch_array($int_res);
					$sub1_total = ($int['amount'] != NULL) ? $int['amount'] : 0;
					$level4_res = mysql_query("select * from accounts where name like '%Expense%on %".$prod['name']."'");
					$level4 = mysql_fetch_array($level4_res);
					$color = ($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
					$i++;
					$grand_total += $sub1_total; 
				}
			//}elseif($level3['account_no'] == '534'){
				//	$grand_total=$deppreciation;
			}else{
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 5121 and account_no <= 5999");
				
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['name']== 'Dividends Paid on Member Shares'){
						$div_res = mysql_query("select sum(total_amount) as amount from share_dividends where date<='".$date."'");
						$div = mysql_fetch_array($div_res);
						$sub1_total = ($div['amount'] != NULL) ? $div['amount'] : 0;
					}elseif($level4['name']== 'Bad Debt Expense on Loans'){
						$write_res = mysql_query("select sum(amount) as amount from written_off where date <= '".$date."'");
						$write = mysql_fetch_array($write_res);
						$written_amt = ($write['amount'] != NULL) ? $write['amount'] : 0;
						$rec_res = mysql_query("select sum(amount) as amount from recovered where date<= '".$date."'");
						$rec = mysql_fetch_array($rec_res);
						$rec_amt = ($rec['amount'] != NULL) ? $rec['amount'] : 0;
						$sub1_total = $written_amt - $rec_amt;
					}else{
						$exp_res = mysql_query("select sum(amount) as amount from expense where account_id='".$level4['id']."' and date <='".$date."'");
						$exp = mysql_fetch_array($exp_res);
						if($exp['amount'] == NULL){
							if($level4['account_no'] == '5342'){
								$sth = mysql_query("select * from accounts where account_no like '123%' and account_no>'1231' and account_no <= 1239");
								$x=1;
								while($row = mysql_fetch_array($sth)){
									$dep_res = mysql_query("select sum(d.amount) as amount from deppreciation d join fixed_asset f on d.asset_id=f.id join accounts a on f.account_id=a.id where a.account_no like '".$row['account_no']."%' and d.date <= '$date'");
									$dep = mysql_fetch_array($dep_res);
									$dep_amt = ($dep['amount'] == NULL) ? 0 : $dep['amount'];
									$color = ($i%2 == 0) ? "lightgrey" : "white";
									$content .= "<tr bgcolor=$color><td>".$level4['account_no'].$x."</td><td>Deppreciation - ".$row['name']."</td><td><b>".number_format($dep_amt, 2)."</b></td></tr>";
									$sub2_total = $dep_amt;
									$sub1_total += $sub2_total;
									$i++;
									$x++;
								}
							}else{
								$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= '512101' and account_no <= '599999'");
								while($level5 = mysql_fetch_array($level5_res)){
									$sub2_total = 0;
									$exp_res = mysql_query("select sum(amount) as amount from expense where account_id='".$level5['id']."' and date <='".$date."'");
									$exp = mysql_fetch_array($exp_res);
									if($exp['amount'] == NULL){
										$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= '51210101' and account_no <= '59999999'");
										while($level6 = mysql_fetch_array($level6_res)){
											$exp_res = mysql_query("select sum(amount) as amount from expense where account_id='".$level6['id']."' and date <='".$date."'");
											$exp = mysql_fetch_array($exp_res);
											$sub2_total += $exp['amount'];
										}
									
									}else{
										$sub2_total = $exp['amount'];
									}
									$color = ($i%2 == 0) ? "lightgrey" : "white";
									$content .= "<tr bgcolor=$color><td>".$level5['account_no']."</td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
									$i++;
									$sub1_total += $sub2_total;
								}
							}
						}else{
							$sub1_total = ($exp['amount'] != NULL) ? $exp['amount'] : 0;
						}
					}
					$color = ($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td>".$level4['account_no']."</td><td>".$level4['name']."</td><td>".number_format($sub1_total, 2)."</td></tr>";
					$i++;
					$grand_total += $sub1_total;
					
				}
			}
			$color = ($i%2 == 0) ? "lightgrey" : "white";
			if($level3['account_no'] == '561'){
			//ACCOUNTS PAYABLE
				$pay_res = mysql_query("select p.account_id as account_id, a.name as name, sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where p.bank_account =0 and p.date <= '".$date."' group by p.account_id");
				//$resp->AddAlert(mysql_error());
				$y=1;
				while($pay = mysql_fetch_array($pay_res)){
					//$pay = mysql_fetch_array($pay_res);
					$pay_amt = ($pay['amount'] == NULL) ? 0 : $pay['amount'];
					
					$color=($i%2 == 0) ? lightgrey : "white";
					$content .= "<tr bgcolor=$color><td><b>561".$y."</b></td><td><b> ".$pay['name']." Expenses</b></td><td><b>".number_format($pay_amt, 2)."</b></td></tr>";
					$i++;
					$grand_total += $pay_amt;  // - $paid_amt;
					$y++;
				}
			}
			$color=($i%2 == 0) ? lightgrey : "white";
			
			$content .= "<tr bgcolor=$color><td><b>".$level3['account_no']."</b></td><td><b>".$level3['name']."</b></td><td><b>".number_format($grand_total, 2)."</b></td></tr>";
			$i++;
			$level2_total += $grand_total;
		}
		$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr bgcolor=$color><td><font size=4pt><b>".$level2['account_no']."</b></font></td><td><font size=4pt><b>".$level2['name']."</b></font></td><td><font size=4pt><b>".number_format($level2_total, 2)."</b></font></td></tr>";
		
		$i++;
		if($level2['account_no'] ==51)
			$financial = $level2_total;
		if($level2['account_no'] == 52)
			$non_financial = $level2_total;
		$expense += $level2_total;
	}
	$color = ($i%2 == 0) ? "lightgrey" : "white";
	$content .= "<tr bgcolor=$color><td><font size=5pt><b>5</b></font></td><td><font size=5pt><b>COSTS & EXPENSES</b></font></td><td><font size=5pt><b>".number_format($expense, 2)."</b></font></td></tr>";
	$content .= "</table></td></tr></table>";
	export($format, $content);
}

//reports_header();
incometrial_balance($_GET['year'], $_GET['month'], $_GET['mday'], $_GET['basis'], $_GET['format'],$_GET['branch_id']);
//reports_footer();
?>
