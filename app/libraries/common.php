<?php
//session_start();
	require_once('resources/includes/Date/Calc.php');
	
	$_SESSION['branch_no'] = 1;
	date_default_timezone_set("Africa/Khartoum");
function start_trans(){
	mysql_query("START TRANSACTION");
	$_SESSION['commit'] =1;
}

function set_bank_balance()
{
	//$resp = new xajaxResponse();
	$date = sprintf("%d-%02d-%02d", date('Y'), date('m'), date('d'));
	$date = $date." 23:59:59";
	$sth = mysql_query("select * from bank_account");
	while ($row = mysql_fetch_array($sth))
	{
		$bank1['id'] = $row['id'];
			//DEPOSITS
			$dep_res = mysql_query("select sum(amount) as amount from deposit where bank_account='".$bank1['id']."' and date <='".$date."'");
			$dep = mysql_fetch_array($dep_res);
			$dep_amt = ($dep['amount'] == NULL) ? 0 : $dep['amount'];
			//WITHDRAWALS
			$with_res = mysql_query("select sum(amount) as amount from withdrawal where bank_account='".$bank1['id']."' and date <='".$date."'");
			$with = mysql_fetch_array($with_res);
			$with_amt = ($with['amount'] == NULL) ? 0 : $with['amount'];
			//OTHER INCOME
			$other_res = mysql_query("select sum(amount) as amount from other_income where bank_account='".$bank1['id']."' and date <='".$date."'");
			$other = mysql_fetch_array($other_res);
			$other_amt = ($other['amount'] == NULL) ? 0 : $other['amount'];
			//EXPENSES
			$expense_res = mysql_query("select sum(amount) as amount from expense where bank_account='".$bank1['id']."' and date <='".$date."'");
			$expense = mysql_fetch_array($expense_res);
			$expense_amt = ($expense['amount'] == NULL) ? 0 : $expense['amount'];
			//LOANS PAYABLE
			$loans_payable = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '212%' and p.bank_account='".$bank1['id']."' and p.date <= '".$date."'");
			$loans_payable = mysql_fetch_array($loans_payable);
			$loans_payable = ($loans_payable['amount'] != NULL) ? $loans_payable['amount'] : 0;
			//PAYABLE PAID
			$payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where bank_account='".$bank1['id']."' and date <='".$date."'");
			$payable_paid = mysql_fetch_array($payable_paid_res);
			$payable_paid_amt = ($payable_paid['amount'] == NULL) ? 0 : $payable_paid['amount'];
			//RECEIVALE COLLECTED
			$collected_res = mysql_query("select sum(amount) as amount from collected where bank_account='".$bank1['id']."' and date <='".$date."'");
			$collected = mysql_fetch_array($collected_res);
			$collected_amt = ($collected['amount'] == NULL) ? 0 : $collected['amount'];
			//DISBURSED LOANS
			$disb_res = mysql_query("select sum(amount) as amount from disbursed where bank_account='".$bank1['id']."' and date <= '".$date."'");
			$disb = mysql_fetch_array($disb_res);
			$disb_amt = ($disb['amount'] == NULL) ? 0 : $disb['amount'];
			//PAYMENTS
			$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id  where p.date <= '".$date."' and p.bank_account='".$bank1['id']."'");
			$pay = mysql_fetch_array($pay_res);
			$pay_amt = ($pay['amount'] == NULL) ? 0 : $pay['amount'];
			//PENALTIES
			$pen_res = mysql_query("select sum(p.amount) as amount from penalty p join disbursed d on p.loan_id=d.id where p.bank_account='".$bank1['id']."' and p.status='paid' and p.date <= '".$date."'");
			$pen = mysql_fetch_array($pen_res);
			$pen_amt = ($pen['amount']==NULL) ? 0 : $pen['amount'];
								
			//SHARES
			$shares_res = mysql_query("select sum(value) as amount from shares where date <='".$date."' and bank_account='".$bank1['id']."'");
			$shares = mysql_fetch_array($shares_res); 
			$shares_amt = ($shares['amount'] == NULL) ? 0 : $shares['amount'];
			//RECOVERED
			$rec_res = mysql_query("select sum(r.amount) as amount from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.bank_account='".$bank1['id']."' and r.date <= '".$date."'");
			$rec = mysql_fetch_array($rec_res);	
			$rec_amt = ($rec['amount'] == NULL) ? 0 : $rec['amount']; 
			//INVESTMENTS 
			$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where date <= '".$date."' and bank_account='".$bank1['id']."'");
			$invest = mysql_fetch_array($invest_res);
			$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
			//DIVIDENDS PAID
			$div_res = mysql_query("select sum(total_amount) as total_amount from share_dividends where date<='".$date."' and bank_account='".$bank1['id']."'");
			$div = mysql_fetch_array($div_res);
			$div_amt = ($div['amount'] != NULL) ? $div['amount'] : 0;

								
			$soldinvest_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where bank_account ='".$bank1['id']."' and date <= '".$date."'");
			$soldinvest = mysql_fetch_array($soldinvest_res);

			//FIXED ASSETS 
			$fixed_res = mysql_query("select sum(initial_value) as amount from fixed_asset where bank_account='".$bank1['id']."' and date <='".$date."'");
			$fixed = mysql_fetch_array($fixed_res);
			$soldasset_res = mysql_query("select sum(amount) as amount from sold_asset where bank_account ='".$bank1['id']."' and date <= '".$date."'");
			$soldasset = mysql_fetch_array($soldasset_res);
									
			//CASH IMPORTED
			$import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id='".$bank1['id']."' and date <='".$date."'");
			$import = mysql_fetch_array($import_res);
			$import_amt = $import['amount'];

			//CASH EXPORTED
			$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id='".$bank1['id']."' and date <='".$date."'");
			
			$export = mysql_fetch_array($export_res);
			$export_amt = intval($export['amount']);

			//CAPITAL FUNDS
			$fund_res = mysql_query("select sum(amount) as amount from other_funds where bank_account='".$bank1['id']."' and date <='".$date."'");
			$fund = mysql_fetch_array($fund_res);
			$fund_amt = $fund['amount'];

			$sub2_total =  $collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt + $fund_amt;	

			
			$up_res = mysql_query("update bank_account set account_balance = $sub2_total where id =". $row['id']."");

			//$resp->AddAppend("award_interest", "innerHTML", "<table bgcolor=#bebebe width=100%><tr><td><font color=red>Bank balances updated!<br>update bank_account set account_balance = $sub2_total where id =". $row['id']."<br></font></td></tr></table>");	
	}
//	return $resp;
}
//set_bank_balance();

//EXPORT
function export($format, $content){
	if($format=='excel' || $format=='word'){
		if($format=='excel'){
			$export_file ='export.xls';
			$out_file = 'out_file.xls';
		}elseif($format == 'word'){
			$export_file ='export.doc';
			$out_file = 'out_file.doc';
		}
		$fp = fopen($export_file, "w+");
		fwrite($fp, $content);
		fclose($fp);
		$data = file_get_contents($export_file);
		header("Content-Disposition: attachment; filename=$out_file");
		header("Content-Description: PHP Generated Data");
		echo($data);
		unlink($export_file);
	}else
		echo($content);
}

//logging
function log_action($user_id, $action, $msg)
{
	if ($user_id == '')
		return ;
	else
	{
		$res = mysql_query("INSERT into logs set user_id='".$_SESSION['user_id']."', time=NOW(),  action='". mysql_escape_string($action)."', msg = '".mysql_escape_string($msg)."'");
	}
}

function hour($str,$hour){
//$hour = ($hour<>"") ? $hour : date('h').":"."00";
$content = "Time:<select name='".$str."hour' id='".$str."hour'><option value='08:00'>08:00";
for($i=1;$i<=24;$i++){
	$x=sprintf("%02d:00",$i);
	$content .= "<option value='".$x."'>".$x."</option>";
}
$content .= "</select>";
return $content;
}


//NET INCOME/LOSS CUMMULATIVE
function net_cummulated_income($from_date='0000-00-00 00:00:00', $to_date, $basis){
		//INCOME FOR INCOME STATEMENT
		$level2_res= mysql_query("select * from accounts where account_no >40 and account_no <=49 order by account_no");
		$income = 0;
		$i=0;
		while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
			$i++;
			$level3_res = mysql_query("select * from accounts where account_no >=411 and account_no <= 599 and account_no like '".$level2['account_no']."%' order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				$color=($i%2 == 0) ? lightgrey : "white";
				
				$i++;
				$grand_total = 0;
				if($level3['account_no'] == '411'){
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4111 and account_no <= 4119");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						if($level4['account_no'] == 4111)
							$int_res = mysql_query("select sum(p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product prod on applic.product_id=prod.id join accounts a on prod.account_id=a.id where p.date >= '".$from_date."' and p.date <='".$to_date."' and a.name like '%Short%Term%'");
						if($level4['account_no'] == 4112)
							$int_res = mysql_query("select sum(p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product prod on applic.product_id=prod.id join accounts a on prod.account_id=a.id where p.date >= '".$from_date."' and p.date <='".$to_date."' and a.name like '%Medium%Term%'");
						if($level4['account_no'] == 4113)
							$int_res = mysql_query("select sum(p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product prod on applic.product_id=prod.id join accounts a on prod.account_id=a.id where p.date >= '".$from_date."' and p.date <='".$to_date."' and a.name like '%Long%Term%'");
						if($level4['account_no'] == 4114)
							$int_res = mysql_query("select sum(amount) as amount from penalty where date>='".$from_date."' and date <='".$to_date."' and status='paid'");
						if($level4['account_no'] > 4114)
							$int_res = mysql_query("select sum(i.amount) as amount from other_income i join accounts a on i.account_id=a.id where a.account_no like '".$level4['account_no']."%' and i.date >='".$from_date."' and i.date <='".$to_date."'");
						$int = mysql_fetch_array($int_res);
						if($int['amount'] != NULL)
							$sub1_total = $int['amount'];
						
						$i++;
						$grand_total += $sub1_total;
					}
				}elseif($level3['account_no'] >= 412 && $level3['account_no'] <= 419){   //RECEIVABLES
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4121 and account_no <= 4199 and account_no not like '%2'");
					$pre = ($basis == 'Cash Basis') ? "(Received)" : "";
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$rec_res = mysql_query("select sum(amount) as amount from receivable where account_id='".$level4['id']."' and maturity_date <= '".$to_date."' and maturity_date >='".$from_date."'");
						$rec = mysql_fetch_array($rec_res);
						
						if($rec['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 412101 and account_no <= 419999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$rec_res = mysql_query("select sum(amount) as amount from receivable where account_id='".$level5['id']."' and maturity_date <= '".$to_date."' and maturity_date >= '".$from_date."'");
								
								$rec = mysql_fetch_array($rec_res);
								if($rec['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 41210101 and account_no <= 41999999");
									while($level6 = mysql_fetch_array($level6_res)){
										$rec_res = mysql_query("select sum(amount) as amount from receivable where account_id='".$level6['id']."' and maturity_date <= '".$to_date."' and maturity_date >= '".$from_date."'");
										$rec = mysql_fetch_array($rec_res);
										$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where r.account_id='".$level6['id']."' and r.maturity_date <= '".$to_date."' and c.date <= '".$to_date."' and c.date >='".$from_date."'");
										$col = mysql_fetch_array($col_res);
										$rec_amt = ($rec['amount'] != NULL) ? $rec['amount'] : 0;
										$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
										$top_up = ($basis == 'Accrual Basis') ? $rec_amt : $col_amt;
										$sub2_total += $top_up;
									}
								}else{
									$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where r.account_id='".$level5['id']."' and r.maturity_date <= '".$to_date."' and c.date <= '".$to_date."' and c.date >= '".$from_date."'");
									$col = mysql_fetch_array($col_res);
									$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
									
									$sub2_total = ($basis == 'Accrual Basis') ? $rec['amount'] : $col_amt;
								
								}
								$color = ($i%2 == 0) ? "lightgrey" : "white";
							
								$i++;
								$sub1_total += $sub2_total;
							}
						}else{
							$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where r.account_id='".$level6['id']."' and r.maturity_date <= '".$to_date."' and c.date <= '".$to_date."' and c.date >= '".$from_date."'");
							$col = mysql_fetch_array($col_res);
							
							$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
							$top_up = ($basis == 'Accrual Basis') ? $rec['amount'] : $col_amt;
							
							$sub1_total += $top_up;
						}
						
						$i++;
						$grand_total += $sub1_total;
					}

				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4121 and account_no <= 4199 and account_no like '%2'");
				while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						//INCOME REGISTERED DIRECTLY
						$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.account_no='".$level4['account_no']."' and o.date <='".$to_date."' and o.date >='".$from_date."'");
						$inc = mysql_fetch_array($inc_res);
						if($inc['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 412101 and account_no <= 419999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.account_no='".$level5['account_no']."' and o.date <='".$to_date."' and o.date >='".$from_date."'");
								$inc = mysql_fetch_array($inc_res);
								if($inc['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and  account_no >= 41210101 and account_no <= 41999999");
									while($level6 = mysql_fetch_array($level6_res)){
										$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.account_no='".$level5['account_no']."' and o.date >='".$from_date."' and o.date <='".$to_date."'");
										$inc = mysql_fetch_array($inc_res);						
										$sub2_total += $inc['amount'];
									}
								}else{
									$sub2_total = $inc['amount'];
								}
								
								$i++;
								$sub1_total += $sub2_total;
							}
						}else{
							$sub1_total = $inc['amount'];
					}
					
					$i++;
					$grand_total += $sub1_total;
				}
			}elseif($level3['account_no'] == '424'){
				$inv_res = mysql_query("select sum(quantity * purchase_price) as tot_cost, sum(quantity * amount) as tot_gain from sold_invest where date >='".$from_date."' and date <= '".$to_date."'");
				$inv = mysql_fetch_array($inv_res);
				if($inv['tot_cost'] == NULL){
					$grand_total =0;
				}else{
					$grand_total = $inv['tot_gain'] - $inv['tot_cost'];
				}
			}elseif($level3['account_no'] >= 421){
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4211 and account_no <= 4299");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['account_no'] == '4221'){    //MONTHLY CHARGES
						$charge_res = mysql_query("select sum(amount) as amount from monthly_charge where date >='".$from_date."' and date <= '".$to_date."'");
						$charge = mysql_fetch_array($charge_res);
						$sub1_total = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
					}elseif($level4['account_no'] == '4222'){    //TRANSACTIONAL CHARGES
						$dep_res = mysql_query("select sum(flat_value + percent_value) as amount from deposit where date >= '".$from_date."' and date <= '".$to_date."'");
						$dep = mysql_fetch_array($dep_res);

						$with_res = mysql_query("select sum(flat_value + percent_value) as amount from withdrawal where date >= '".$from_date."' and date <= '".$to_date."'");
						$with = mysql_fetch_array($with_res);
						$sub1_total = $dep['amount'] + $with['amount'];
					}elseif($level4['account_no'] == '4223'){   //INCOME FROM SALE OF ASSETS
						$sold_res = mysql_query("select sum(amount) as amount from sold_asset where date <='".$to_date."' and date >= '".$from_date."'");
						$sold = mysql_fetch_array($sold_res);
						$sub1_total = ($sold['amount'] != NULL) ? $sold['amount'] : 0;
					}else{	
						$inc_res = mysql_query("select sum(amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level4['id']."' and o.date >= '".$from_date."' and o.date <= '".$to_date."'");
						$inc = mysql_fetch_array($inc_res);
						if($inc['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 421101 and account_no <= 429999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level5['id']."' and o.date >= '".$from_date."' and o.date <= '".$to_date."'");
								$inc = mysql_fetch_array($inc_res);
								if($inc['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 42110101 and account_no <= 42999999");
									while($level6 = mysql_fetch_array($level6_res)){
										$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level6['id']."' and o.date >= '".$from_date."' and o.date <= '".$to_date."'");
										$inc = mysql_fetch_array($inc_res);
										$sub2_total += $inc['amount'];
									}
								}else{
									
									$sub2_total = $inc['amount'];
								}
								
								$sub1_total += $sub2_total;
								$i++;
							}
						}else{
							
							$sub1_total += $inc['amount'];
						}
						
					}
					
					$i++;
					$grand_total += $sub1_total;
				}
			}
		
			
			$level2_total += $grand_total;
		}

		
	
		$income += $level2_total;
	}
	
	

// EXPENSES
	
	$level2_res = mysql_query("select * from accounts where account_no >50 and account_no <60 order by account_no");
	while($level2 = mysql_fetch_array($level2_res)){
		$level2_total = 0;
		
		$level3_res = mysql_query("select * from accounts where account_no like '".$level2['account_no']."%' and account_no >= '511' and account_no <= '599'");
		while($level3 = mysql_fetch_array($level3_res)){
			$grand_total = 0;
			
			$int_accounts ='0';
			if($level3['account_no'] == '511'){
				$prod_res = mysql_query("select s.id as id, a.name as name, a.account_no as account_no from savings_product s  join accounts a on s.account_id=a.id where s.type='free' order by a.account_no");
				while($prod = mysql_fetch_array($prod_res)){
					$int_res = mysql_query("select sum(amount) as amount from save_interest  i join mem_accounts m on i.memaccount_id=m.id where m.saveproduct_id='".$prod['id']."' and i.date <='".$to_date."' and i.date >='".$from_date."'");
					$int = mysql_fetch_array($int_res);
					$sub1_total = ($int['amount'] != NULL) ? $int['amount'] : 0;
					$level4_res = mysql_query("select * from accounts where name like '%Expense%on %".$prod['name']."'");
					$level4 = mysql_fetch_array($level4_res);
					$exp = mysql_fetch_array(mysql_query("select sum(amount) as amount from expense where account_id='".$level4['id']."' and date>='".$from_date."' and date <= '".$to_date."'"));
					$sub1_total = ($exp['amount'] != NULL) ? $sub1_total + $exp['amount'] : $sub1_total;
					$grand_total += $sub1_total; 
					$int_accounts .= ", '".$level4['id']."'";
				}
			}   //else{   //TO INCLUDE OTHER EXPENSES ON SAVINGS 	
			$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= '5111' and account_no <= '5999' and id not in (".$int_accounts.") and name not like '%Interest%Expense%on%'");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['name']== 'Dividends Paid on Member Shares'){
						$div_res = mysql_query("select sum(total_amount) as amount from share_dividends where date >= '".$from_date."' and  date<='".$to_date."'");
						$div = mysql_fetch_array($div_res);
						$sub1_total = ($div['amount'] != NULL) ? $div['amount'] : 0;
					}elseif($level4['name']== 'Bad Debt Expense on Loans'){
						$write_res = mysql_query("select sum(amount) as amount from written_off where date >= '".$from_date."' and date <= '".$to_date."'");
						$write = mysql_fetch_array($write_res);
						$written_amt = ($write['amount'] != NULL) ? $write['amount'] : 0;
						$rec_res = mysql_query("select sum(amount) as amount from recovered where date<= '".$to_date."' and date >= '".$from_date."'");
						$rec = mysql_fetch_array($rec_res);
						$rec_amt = ($rec['amount'] != NULL) ? $rec['amount'] : 0;
						$sub1_total = $written_amt - $rec_amt;
					}elseif($level4['account_no'] == '5342'){
								$sth = mysql_query("select * from accounts where account_no like '123%' and account_no>'1231' and account_no <= 1239");
								$x=1;
								while($row = mysql_fetch_array($sth)){
									$dep_res = mysql_query("select sum(d.amount) as amount from deppreciation d join fixed_asset f on d.asset_id=f.id join accounts a on f.account_id=a.id where a.account_no like '".$row['account_no']."%' and d.date >='$from_date' and d.date <= '$to_date'");
									$dep = mysql_fetch_array($dep_res);
									$dep_amt = ($dep['amount'] == NULL) ? 0 : $dep['amount'];
									
									$sub2_total = $dep_amt;
									$sub1_total += $sub2_total;
								
									$x++;
								} 
					//}elseif(preg_match("/^56.{2}/i", $level4['account_no'])){
					//	$sub1total = 10000000000;
					}else{
						//$resp->AddAssign("status", "innerHTML", "select sum(amount) as amount from expense where account_id='".$level4['id']."' and date>= '".$from_date."' and date <='".$to_date."'<BR>");
						$exp_res = mysql_query("select sum(amount) as amount from expense where account_id='".$level4['id']."' and date>= '".$from_date."' and date <='".$to_date."'");
						$exp = mysql_fetch_array($exp_res);
						if($exp['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= '511101' and account_no <= '599999'");
							//$resp->AddAppend("status", "innerHTML", "select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= '512101' and account_no <= '599999'<BR>");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$exp_res = mysql_query("select sum(amount) as amount from expense where account_id='".$level5['id']."' and date>= '".$from_date."' and date <='".$to_date."'");
								$exp = mysql_fetch_array($exp_res);
								if($exp['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= '51110101' and account_no <= '59999999'");
									while($level6 = mysql_fetch_array($level6_res)){
										$exp_res = mysql_query("select sum(amount) as amount from expense where account_id='".$level6['id']."' and date>= '".$from_date."' and date <='".$to_date."'");
										$exp = mysql_fetch_array($exp_res);
										$sub2_total += $exp['amount'];
									}
								}else{
									$sub2_total = $exp['amount'];
								}
								
								$sub1_total += $sub2_total;
								
							}
							
						}else{
							
							//$sub1_total = ($exp['amount'] != NULL) ? $exp['amount'] : 0;
							$sub1_total = $exp['amount'];		
						}
					}
					$grand_total += $sub1_total;
					
				}
			//}   //ELSE FOR SAVINGS EXPENSES
			$color=($i%2 == 0) ? lightgrey : "white";
			if($level3['account_no'] == '561'){
				//ACCOUNTS PAYABLE
				//INTEREST ON SAVINGS PAYABLE
				/* $int_res = mysql_query("select sum(amount) as amount from save_interest where date >= '".$from_date."' and date <= '".$to_date."'");
				$int = mysql_fetch_array($int_res);
				$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
				*/
				$pay_res = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '213%' and p.date >= '".$from_date."' and p.date <= '".$to_date."'");
				$pay = mysql_fetch_array($pay_res);
				$pay_amt = ($pay['amount'] == NULL) ? 0 : $pay['amount'];
				//$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid left join payable p on paid.payable_id=p.id where p.bank_account=0 and paid.date <= '".$date."'");
				$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id join accounts a on p.account_id=a.id where paid.date >='".$from_date."' and paid.date <= '".$to_date."' and a.account_no like '213%'");
				$paid = mysql_fetch_array($paid_res);
				$paid_amt = ($paid['amount'] == NULL) ?  0 : $paid['amount'];
				//$grand_total = $pay_amt + $int_amt;  // - $paid_amt;
				$grand_total = $pay_amt;  // + $int_amt;  // - $paid_amt;
			}
			$level2_total += $grand_total;
		}
		$expense = $expense + $level2_total;
	}

	//RESERVE FUNDS
	$reserve_res = mysql_query("select sum(amount) as amount from other_funds where bank_account=0 and date >='$from_date' and date <='$to_date'  and id not in (select fund_id from payable where date <= '".$to_date."' and fund_id<>'0')");
	$reserve = mysql_fetch_array($reserve_res);
	$reserve_amt = ($reserve['amount'] == NULL) ? 0 : $reserve['amount'];

	$net_income = $income - $expense - $reserve_amt;
	return $net_income;
}


function rollback(){
	mysql_query("ROLLBACK");
	$_SESSION['commit'] =0;
}

function commit(){
	mysql_query("COMMIT");
}

//HEADER FOR REPORST
function reports_header(){
?>
<body bgcolor="#0111A2" topmargin="0" leftmargin="0" rightmargin="0">

<table border="0" cellspacing="0" cellpadding="0" width="801" style="border-collapse: collapse" bordercolor="#111111">
  <tr align="left" valign="top">
    <td rowspan="1" colspan="1" height="1" width="1"></td>
    <td rowspan="1" colspan="1" height="1" width="235"></td>
    <td rowspan="1" colspan="1" height="1" width="76"></td>
    <td rowspan="1" colspan="1" height="1" width="393"></td>
    <td rowspan="1" colspan="1" height="1" width="76"></td>
    <td rowspan="1" colspan="1" height="1" width="20"></td></TR>
  <tr align="left" valign="top">
    <td rowspan="1" colspan="1" width="1" height="129"></td>
    <td width="235" height="129" background="images/spsiteR1C2.JPG"><img border="0" width="234" height="129" src="images/spsiteR1C1.JPG" alt=""></td>
    <td width="76" height="129" background="images/spsiteR1C2.JPG"><img border="0" width="76" height="129" src="images/spsiteR1C2.JPG" alt=""></td>
    <td width="393" height="129" background="images/spsiteR1C2.JPG"><img border="0" width="316" height="129" src="images/spsiteR1C3.JPG" alt=""></td>
    <td width="76" height="129" background="images/spsiteR1C2.JPG"><img border="0" width="154" height="129" src="images/spsiteR1C4.JPG" alt=""></td>
    <td width="200" height="129" background="images/spsiteR1C2.JPG"><img border="0" width="200" height="129" src="images/spsiteR1C5.JPG" alt=""></td>
  </tr>
  <tr align="left" valign="top">
    <td colspan="6" width="801" height="300" bgcolor="#E2ECF5">
<?php
}
function users_header(){
?>
<body bgcolor="#0111A2" topmargin="0" leftmargin="0" rightmargin="0">

<table border="0" cellspacing="0" cellpadding="0" width="100%" height="100" style="border-collapse: collapse" bordercolor="#111111">
  <tr align="left" valign="top">
    <td rowspan="1" colspan="1" height="1" width="1"></td>
    <td rowspan="1" colspan="1" height="1" width="235"></td>
    <td rowspan="1" colspan="1" height="1" width="76"></td>
    <td rowspan="1" colspan="1" height="1" width="393"></td>
    <td rowspan="1" colspan="1" height="1" width="76"></td>
    <td rowspan="1" colspan="1" height="1" width="200"></td>
  </tr>
  <tr align="left" valign="top">
    <td rowspan="1" colspan="1" width="1" height="129"></td>
    <td width="235" height="129" background="images/spsiteR1C2.JPG"><img border="0" width="234" height="129" src="images/spsiteR1C1.JPG" alt=""></td>
    <td width="76" height="129" background="images/spsiteR1C2.JPG"><img border="0" width="76" height="129" src="images/spsiteR1C2.JPG" alt=""></td>
    <td width="393" height="129" background="images/spsiteR1C2.JPG"><img border="0" width="316" height="129" src="images/spsiteR1C3.JPG" alt=""></td>
    <td width="76" height="129" background="images/spsiteR1C2.JPG"><img border="0" width="154" height="129" src="images/spsiteR1C4.JPG" alt=""></td>
    <td width="200" height="129" background="images/spsiteR1C2.JPG"><img border="0" width="200" height="129" src="images/spsiteR1C5.JPG" alt=""></td>
  </tr>
  <tr align="left" valign="top">

    <td colspan="6" width="981" height="32" bgcolor="#ffffff">
    <table border="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%" id="AutoNumber1" bgcolor="#FFFFFF">
      <tr>
        <td width="1%">&nbsp;</td>
        <td width="1%">&nbsp;</td>
       <td width="15%"><a href="memberstatement.php">Member Statement</a></td>
	    <td width=""><a class="footernav" href="password.php?password=password">Change Password</a></td>
		<td width="20%"><?php
			if (isset($_SESSION['user_id']))
			{
				$urow = mysql_fetch_array(mysql_query("select username, name from users where id = $_SESSION[user_id]"));
				$logged_in_user = "You are logged in as: [".$urow['username']."]";
			}	
			echo($logged_in_user);
		 
		 ?></td>
		<td width="3%">
        <a href="index.php">
        <img border="0" src="icon/logout.jpg" width="28" height="28"></a></td>
        <td width="7%"><a class="footernav" href="index.php">Logout</a></td>
      </tr>
    </table>
    </td>
  </tr>
  <tr align="left" valign="top">
    <td rowspan="2" colspan="1" width="1" height="74"></td>
    <td colspan="5" width="999" height="1" bgcolor="#E2ECF5">
    <hr color="#336699" size="0" width="100%"></td>
  </tr>
  
  <tr align="left" valign="top">
    <td colspan="5" width="980" height="350" bgcolor="#E2ECF5">
    
    <!-- tabs -->        
    <ul id="maintab" class="shadetabs">
<?php

}
function inner_header(){
?>
<body bgcolor="#0111A2" topmargin="0" leftmargin="0" rightmargin="0">

<table border="0" cellspacing="0" cellpadding="0" width="100%" height="100" style="border-collapse: collapse" bordercolor="#111111">
  <tr align="left" valign="top">
    <td rowspan="1" colspan="1" height="1" width="1"></td>
    <td rowspan="1" colspan="1" height="1" width="235"></td>
    <td rowspan="1" colspan="1" height="1" width="76"></td>
    <td rowspan="1" colspan="1" height="1" width="393"></td>
    <td rowspan="1" colspan="1" height="1" width="76"></td>
    <td rowspan="1" colspan="1" height="1" width="200"></td>
  </tr>
  <tr align="left" valign="top">
    <td rowspan="1" colspan="1" width="1" height="129"></td>
<!--
    <td width="235" height="129" background="images/craneapp/spsiteR1C2.jpg"><img border="0" width="234" height="129" src="images/craneapp/spsiteR1C1.jpg" alt=""></td>
    <td width="76" height="129" background="images/spsiteR1C2.jpg"><img border="0" width="76" height="129" src="images/craneapp/spsiteR1C2.jpg" alt=""></td>
    <td width="393" height="129" background="images/spsiteR1C2.jpg"><img border="0" width="316" height="129" src="images/craneapp/spsiteR1C3.jpg" alt=""></td>
    <td width="76" height="129" background="images/spsiteR1C2.jpg"><img border="0" width="154" height="129" src="images/craneapp/spsiteR1C4.jpg" alt=""></td>
    <td width="200" height="129" background="images/spsiteR1C2.jpg"><img border="0" width="200" height="129" src="images/craneapp/spsiteR1C5.jpg" alt=""></td> -->

<td width=235 height=129 background='images/craneapp/spsiteR1C2.jpg'><img border=0 width=234 height=129 src='images/craneapp/spsiteR1C1.jpg' alt=''></td>
    <td width=76 height=129 background='images/craneapp/spsiteR1C2.jpg'><img border=0 width=76 height=129 src='images/craneapp/spsiteR1C2.jpg' alt=''></td>
    <td width=393 height=129 background='images/craneapp/spsiteR1C2.jpg'><img border=0 width=316 height=129 src='images/craneapp/spsiteR1C3.jpg' alt=''></td>
    <td width=76 height=129 background='images/craneapp/spsiteR1C2.jpg'><img border=0 width=154 height=129 src='images/craneapp/spsiteR1C4.jpg' alt=''></td>
    <td width=200 height=129 background='images/craneapp/spsiteR1C2.jpg'><img border=0 width=100% height=129 src='images/craneapp/spsiteR1C5.jpg' alt=''></td>
  </tr>
  <tr align="left" valign="top">

    <td colspan="6" width="981" height="32" bgcolor="#ffffff">
    <table border="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%" id="AutoNumber1" bgcolor="#FFFFFF">
      <tr>
        <td width="1%">&nbsp;</td>
        <td width="1%">&nbsp;</td>
        <td width="3%">
        <a href="backup.php" target="_blank()">
        <img border="0" src="icon/Jobs_blue.gif" width="20" height="18"></a></td>
        <td width="6%"><a class="footernav" href="backup.php" target="_blank()">Backup</a></td>
        <td width="3%">
        <a href="recover.php" target="_blank()">
        <img border="0" src="icon/zpdown.gif" width="20" height="18"></a></td>
        <td width="6%"><a id="footernav" class="footernav" href="recover.php" target="_blank()">Restore</a></td>
        <td width="3%">
        <a href="manual.php" target=blank()>
        <img border="0" src="icon/zphelp.gif" width="20" height="18"></a></td>
        <td width="5%"><a class="footernav" href="manual.php" target=blank()>Help</a></td>
        
        <td width="3%">
        <a href="support.php" target=blank()>
        <img border="0" src="icon/wi0054-24.gif" width="24" height="24"></a></td>
        <td width="25%"><a class="footernav" href="support.php" target=blank()>Support</a></td>
		<td width="20%"><?php
			if (isset($_SESSION['user_id']))
			{
				$urow = mysql_fetch_array(mysql_query("select username, name from users where id = $_SESSION[user_id]"));
				$logged_in_user = "You are logged in as: [".$urow['username']."]";
			}	
			echo($logged_in_user);
		 
		 ?></td>
         <td width="13%"><a class="footernav" href="users.php?password=password">Change Password</a></td>
		<td width="3%">
        <a href="index.php">
        <img border="0" src="icon/logout.jpg" width="28" height="28"></a></td>
        <td width="7%"><a class="footernav" href="index.php">Logout</a></td>
      </tr>
    </table>
    </td>
  </tr>
  <tr align="left" valign="top">
    <td rowspan="2" colspan="1" width="1" height="74"></td>
    <td colspan="5" width="999" height="1" bgcolor="#E2ECF5">
    <hr color="#336699" size="0" width="100%"></td>
  </tr>
  
  <tr align="left" valign="top">
    <td colspan="5" width="980" height="350" bgcolor="#E2ECF5">
    
    <!-- tabs -->        
    <ul id="maintab" class="shadetabs">
<?php
if($_SESSION['position'] <>'Loan Officer'){
	$customers = "href='customers.php'";
	$shares ="href='shares.php'";
	$savings = "href='savings.php'";
	$loans = "href='loans.php'";
	$chart = "href='chart.php'";
	$bank_acct = "href='bank_acct.php'";
	$staff = "href ='staff.php'";
	$holiday = "href='holiday.php'";
	$settings = "href='settings.php'";
	$users = "href='users.php'";
}
echo("<li><a ".$customers."><img border='0' src='icon/blue/About_blue.gif' width='20' height='18'> Customers</a></li>
<li><a ".$shares."><img border=0 src='icon/blue/Services_blue.gif' width=20 height=18> Shares</a></li>
<li><a ".$savings."><img border=0 src='icon/blue/Buy_blue.gif' width=20 height=18> Savings</a></li>
<li><a ".$loans."><img border=0 src='icon/blue/Pricing_blue.gif' width=20 height=18> Loans</a></li>
<li><a ".$chart."><img border=0 src='icon/blue/News_blue.gif' width=20 height=18> Chart of Accts</a></li>
<li><a ".$bank_acct."><img border=0 src='icon/blue/Products_blue.gif' width=20 height=18> Bank Accnts</a></li>
<li><a href='reports.php'><img border=0 src='icon/blue/Documentation_blue.gif' width=20 height=18> Reports</a></li>
<li><a ".$staff."><img border=0 src='icon/blue/Consulting_blue.gif' width=20 height=18> Staff</a></li>
<li><a ".$holiday."><img border=0 src='icon/blue/Store_blue.gif' width=20 height=18> Holidays</a></li>
<li><a ".$settings."><img border=0 src='icon/blue/Support_blue.gif' width=20 height=18> Settings</a></li>
<li><a ".$users."><img border=0 src='icon/blue/Forum_blue.gif' width=20 height=18> Users</a></li>
</ul>");

}


//header for manual

function manual_header()
{
?>
<body bgcolor="#0111A2" topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0">
<table border="0" cellspacing="0" cellpadding="0" width="100%" height="132" style="border-collapse: collapse" bordercolor="#111111">
  <tr align="left" valign="top">
    <td rowspan="1" colspan="1" height="1" width="1"></td>
    <td rowspan="1" colspan="1" height="1" width="235"></td>
    <td rowspan="1" colspan="1" height="1" width="76"></td>
    <td rowspan="1" colspan="1" height="1" width="393"></td>
    <td rowspan="1" colspan="1" height="1" width="76"></td>
    <td rowspan="1" colspan="1" height="1" width="200"></td>
  </tr>
  <tr align="left" valign="top">
    <td rowspan="1" colspan="1" width="1" height="130"></td>
    <!--<td width="235" height="130" background="images/craneapp/spsiteR1C2.jpg"><img border="0" width="234" height="129" src="images/craneapp/spsiteR1C1.jpg" alt=""></td>
    <td width="76" height="130" background="images/craneapp/spsiteR1C2.jpg"><img border="0" width="76" height="129" src="images/craneapp/spsiteR1C2.jpg" alt=""></td>
    <td width="393" height="130" background="images/craneapp/spsiteR1C2.jpg"><img border="0" width="316" height="129" src="images/craneapp/spsiteR1C3.jpg" alt=""></td>
    <td width="76" height="130" background="images/craneapp/spsiteR1C2.jpg"><img border="0" width="154" height="129" src="images/craneapp/spsiteR1C4.jpg" alt=""></td>
    <td width="200" height="130" background="images/craneapp/spsiteR1C2.jpg"><img border="0" width="100%" height="129" src="images/craneapp/spsiteR1C5.jpg" alt=""></td> -->

<td width=235 height=129 background='images/craneapp/spsiteR1C2.jpg'><img border=0 width=234 height=129 src='images/craneapp/spsiteR1C1.jpg' alt=''></td>
    <td width=76 height=129 background='images/craneapp/spsiteR1C2.jpg'><img border=0 width=76 height=129 src='images/craneapp/spsiteR1C2.jpg' alt=''></td>
    <td width=393 height=129 background='images/craneapp/spsiteR1C2.jpg'><img border=0 width=316 height=129 src='images/craneapp/spsiteR1C3.jpg' alt=''></td>
    <td width=76 height=129 background='images/craneapp/spsiteR1C2.jpg'><img border=0 width=154 height=129 src='images/craneapp/spsiteR1C4.jpg' alt=''></td>
    <td width=200 height=129 background='images/craneapp/spsiteR1C2.jpg'><img border=0 width=100% height=129 src='images/craneapp/spsiteR1C5.jpg' alt=''></td>
  </tr>
</table>
<?php
}

//FOOTER
function footer(){
?>
 <script type="text/javascript">
//Start Ajax tabs script for UL with id="maintab" Separate multiple ids each with a comma.
startajaxtabs("maintab")
    </script>
	<!-- tabs end -->
      </tr>
  <tr align="left" valign="top">
    <td rowspan="1" colspan="1" width="1" height="43"></td>
    <td width="980" height="43" colspan="5" background="images/spsiteR4C11.jpg">
    <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%" id="AutoNumber2" ALINK=ORANGE>
      <tr>
        <td width="37%" class="style3">&nbsp;</td>
        <td width="38%"><span class="style4"><FONT COLOR=#FFFFFF SIZE=1>Copyright \A9 2012 </FONT></span></td>
        <td width="25%"><span class="style4"><font size="1" COLOR=#FFFFFF>Email: 
          
        <a href="mailto:support@craneapp.com"><FONT COLOR=#FFFFFF>CraneApps</FONT></a><br>
        Tel: +256-414-580533<br>
        www.craneapps.com</font><font size="1"></font></span></td>
      </tr>
    </table>    </td>
  </tr>
</table>
</body>

</html>
<?php
}

function reports_footer(){
?>
</td>
  </tr>
  <tr align="left" valign="top">
    <td rowspan="1" colspan="1" width="1" height="43"></td>
    <td colspan="5" width="980" height="43" background="images/spsiteR4C11.JPG">
    <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%" id="AutoNumber2">
      <tr>
        <td width="37%">&nbsp;</td>
        <td width="38%"><font color="#FFFFFF">Copyright \A9 2012 CraneApps</font></td>
        <td width="25%"><font size="1" color="#FFFFFF">Email: info@craneapps.com </font>
        <font size="1">
        <a href="mailto:info@fl-t.com"><font color="#FFFFFF">info@craneapps.com</font></a></font><font size="1" color="#FFFFFF"><br>
        Tel: +256-414-580533<br>
        URL:www.craneapps.com</font></td>
      </tr>
    </table>
    </td>
  </tr>
</table>

</body>

</html>
<?php
}

//COMMON SP FUNCTIONS 
function get_savings_bal($mode)
{
				//ACCOUNT BALANCE
				$dep_res = mysql_query("select sum(amount - flat_value - percent_value) as amount from deposit where memaccount_id='".$mode."'");
				$dep = mysql_fetch_array($dep_res);
				$dep_amt = ($dep['amount'] != NULL) ? $dep['amount'] : 0;
				//WITHDRAWALS
				$with_res = mysql_query("select sum(amount + flat_value + percent_value) as amount from withdrawal where memaccount_id='".$mode."'");
				$with = mysql_fetch_array($with_res);
				$with_amt = ($with['amount'] != NULL) ? $with['amount'] : 0;
				//MONTHLY CHARGES 
				$charge_res = mysql_query("select sum(amount) as amount from monthly_charge where memaccount_id='".$mode."'");
				$charge = mysql_fetch_array($charge_res);
				$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
				//INTEREST AWARDED
				$int = mysql_fetch_array(mysql_query("select sum(amount) as amount from save_interest where memaccount_id='".$mode."'"));
				$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;
				//LOAN REPAYMENTS
				$pay_res = mysql_query("select sum(princ_amt + int_amt + penalty + other_charges) as amount from payment where mode='".$mode."'");
				$pay = mysql_fetch_array($pay_res);
				$pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;
				//INCOME DEDUCTIONS
				$inc_res = mysql_query("select sum(amount) as amount from other_income where mode='".$mode."'");
				$inc = mysql_fetch_array($inc_res);
				$inc_amount = ($inc['amount'] != NULL) ? $inc['amount'] : 0;
				//SHARES DEDUCTIONS
				$sha_res = mysql_query("select sum(value) as amount from shares where mode='".$mode."'");
				$sha = mysql_fetch_array($sha_res);
				$sha_amount = ($sha['amount'] != NULL) ? $sha['amount'] : 0;

				$balance = $dep_amt + $int_amt  - $with_amt - $charge_amt - $pay_amt - $inc_amount - $sha_amount;

		return $balance;
}

//CHANGE DIGIT TO MONTH NAME
function digit_month($num){
	switch ($num){
		case "1":
			$name="JANUARY";
			break;
		case "2":
			$name="FEBRUARY";
			break;
		case "3":
			$name="MARCH";
			break;
		case "4":
			$name="APRIL";
			break;
		case "5":
			$name="MAY";
			break;
		case "6":
			$name="JUNE";
			break;
		case "7":
			$name="JULY";
			break;
		case "8":
			$name="AUGUST";
			break;
		case "9":
			$name="SEPTEMBER";
			break;
		case "10":
			$name="OCTOBER";
			break;
		case "11":
			$name="NOVEMBER";
			break;
		default:
			$name="DECEMBER";
		break;
		
	}
	return $name;
}
//DROPDOWN FOR MONTHS
function monthdob_array($str, $year, $month){
	$content = "<select name='".$str."year' id='".$str."year'>";
		if($year<>"")
			$content .= "<option value='".$year."'>" . $year;
		else
			$content .= "<option value='".date('Y')."'>" . date("Y");
		for($i=1920; $i <= date("Y")+10; $i++)
			$content .= "<option value='".$i."'>" . $i;
	$content .= "</select>";
	$content .= "<select name='".$str."month' id='".$str."month'>";
	if($month<>"")
		$content .= "<option value='".$month."'>" . substr(digit_month($month), 0, 1). strtolower(substr(digit_month($month), 1, 2));
	else
		$content .= "<option value='".date("m")."'>" . date("M");
	$Month1=array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' );
	$i=1;
	$content .= "<option value='$i'>" . current($Month1);
	while(next($Month1)){	
		$i++;
		$content .= "<option value='$i'>" . current($Month1);
	}	
	$content .= "</select>";
	return $content;
}

//DROPDOWN FOR MONTHS
function month_array($str, $year, $month){
	$content = "<select name='".$str."year' id='".$str."year'>";
		if($year<>"")
			$content .= "<option value='".$year."'>" . $year;
		else
			$content .= "<option value='".date('Y')."'>" . date("Y");
		for($i=1900; $i <= date("Y")+10; $i++)
			$content .= "<option value='".$i."'>" . $i;
	$content .= "</select>";
	$content .= "<select name='".$str."month' id='".$str."month'>";
	if($month<>"")
		$content .= "<option value='".$month."'>" . substr(digit_month($month), 0, 1). strtolower(substr(digit_month($month), 1, 2));
	else
		$content .= "<option value='".date("m")."'>" . date("M");
	$Month1=array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' );
	$i=1;
	$content .= "<option value='$i'>" . current($Month1);
	while(next($Month1)){	
		$i++;
		$content .= "<option value='$i'>" . current($Month1);
	}	
	$content .= "</select>";
	return $content;
}

/* print days of the month*/
function mday($str, $mday){
	$mday = ($mday<>"") ? $mday : date("d");
	$content = "<select name='".$str."mday' id='".$str."mday'><option value='".$mday."'>".$mday;
    for($i=1; $i<32; $i++){
         $x=sprintf("%02d", $i);
		 $content .= "<option value='".$x."'>".$x;
    }
	$content .= "</select>";
	return $content;
}

# INCLUDE BRANCH

function branch(){
$branch = mysql_query("select branch_no,branch_name from branch");
$content = "<select name='branch_id' id='branch_id'>";
while($row = mysql_fetch_assoc($branch)){
$content .="<option value='".$row['branch_no']."' label='".$row['branch_name']."'>".$row['branch_name'];
}
$content .="</select";
return $content;
}

# SELECT BRANCH FOR REPORTING

function branch_rep($branch_id){
$branch = mysql_query("select branch_no,branch_name from branch");
if(!mysql_query("select branch_name from branch where branch_no=".$branch_id))
	$branchName =  '-- ALL Branches --';
else{
$name = mysql_fetch_assoc(mysql_query("select branch_name from branch where branch_no=".$branch_id));
$branchName=$name['branch_name'];
}
$content = "<select name='branch_id' id='branch_id'><option value='all' label='".$branchName."'>".$branchName."<option value='all' label=' --All Branches-- '> --All Branches-- ";
while($row = mysql_fetch_assoc($branch)){
$content .="<option value='".$row['branch_no']."' label='".$row['branch_name']."'>".$row['branch_name'];
}
$content .="</select>";
return $content;
}

function pages($num_rows){
$content = "Records Per Page<select name='num' id='num'><option value='".$num_rows."' >".$num_rows."</option>";
$num = 10;
while($num<=400){
$content .= "<option value='".$num."'>".$num."</option>";
$num+=10;
}
$content .= "</select>";
return $content;
}

#PAGING GLOBAL VARIABLES
$num_pages = 1;
$num_records = 100;
$cur_page = 1;
$start = 1;
function new_query($query,$start,$num_rows){
return mysql_query($query." limit ".$start.",".$num_rows);
}
function __init__paging($query,$fun,$num_rec){
	global $num_pages,$cur_page,$num_records,$start;
	$num_pages = ceil(mysql_num_rows(mysql_query($query))/$num_rec);
$content = "<div align=center>";

	if($num_pages > 1 && $cur_page > 1)
	$content .= $sender."<a href ='#?sender=first' onclick=".$fun."><font color=#666666>First</font></a>";
	if($num_pages>1&&$cur_page != 1)
	$content.= " | <a href='#?sender=previous' onclick=".$fun."><font color=#666666><< Previous</font></a> |";
	if($num_pages > 2){
     $i=1;
	 while($i < $num_pages){
		 $color = ($cur_page == $i) ? "#FF0000" : "#666666";
	 $content .=" <a href='#?sender=".$i."' onclick=".$fun."><font color=$color>".$i."</font></a>";
	 $i++;
	 }
	 }
	 if($num_pages > 1 && $cur_page < $num_pages)
		 $content.= " | <a href='#?sender=next' onclick=".$fun."><font color=#666666> Next >></font></a>";
	 if($num_pages > 1 && $cur_page < $num_pages)
		 $content.= " | <a href='#?sender=last' onclick=''><font color=#666666> Last </font></a>";
	 $content .="</div>";
	 $sender = $_GET['sender'];
	 if($sender = "")
		 $sender = "none";
	return $content;
	}
	function createMemberAccount($mem_no){
		$name="";$status='active';$username="";$password="";$position="member";$date = date('y-m-d');
		$close_time="23:59:59";$open_time="00:00:00";
		$mem = mysql_query("select mem_no,first_name,last_name from member where mem_no='".$mem_no."'");
		$row = mysql_fetch_array($mem);
		$name = $row['first_name']." ".$row['last_name'];
		$username=strtolower(preg_replace("[ ]","",$row['first_name'].".".$row['last_name']));
		$password=crypt($row['mem_no'],"xy");
		if(mysql_query("insert into users(name,username,password,position,date,open_time,close_time,status,mem_no)values('".$name."','".$username."','".$password."','".$position."','".$date."','".$open_time."','".$close_time."','".$status."','".$row['mem_no']."')")){
			$content = "<font color=red>Created user account for ".$name;
			$content .="<br \>Username: ".$username;
			$content .="<br />Password: ".$row['mem_no'];
			$content .="<br /></font>";
			return $content;
		}
		return mysql_error();
	}
	function isCreateAccountsOn(){
		$status = mysql_query("select*from customer_settings");
		if(mysql_num_rows($status)>0){
			$value = mysql_fetch_array($status);
			if($value['always_create']=="on")
				return 1;
		}
		return 0;
	}
	/*************************
	*returns all the years   * 
	*in the system from first* 
	*to last transaction	 *
	*************************/
	function years(){
		$date = mysql_query("select substr(date,1,4) as date from collected UNION select substr(date,1,4) as date from cash_transfer UNION select substr(date,1,4) as date from cash_transfer UNION select substr(date,1,4) as date from deposit UNION select substr(date,1,4) as date from disbursed UNION select substr(date,1,4) as date from expense UNION select substr(date,1,4) as date from fixed_asset UNION select substr(date,1,4) as date from investments UNION select substr(date,1,4) as date from other_income UNION select substr(date,1,4) as date from payable_paid UNION select substr(p.date,1,4) as date from payment p join disbursed d on p.loan_id=d.id UNION select substr(pen.date,1,4) as date from penalty pen join disbursed d on pen.loan_id=d.id UNION select substr(r.date,1,4) as date from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id UNION select substr(date,1,4) as date from share_dividends UNION select substr(date,1,4) as date from shares UNION select substr(date,1,4) as date from withdrawal UNION select substr(date,1,4) from sold_asset UNION select substr(date,1,4) as date from payable UNION select substr(date,1,4) as date from sold_invest UNION select substr(date,1,4) as date from other_funds order by date asc");
		$years=array();
		while($row=mysql_fetch_array($date)){
			$year=substr($row['date'],0,4);
			if(!intval(array_search($year,$years)))
				{
					array_push($years,$year);
				}
			
		}
		return $years;
	}
	/***************************************
	*organises the financial years in their*
	*correct format depending on the month *
	*chosen as the closing month           *
	****************************************/
	function financial_years(){
	$last_month=mysql_query("select distinct last_month from branch");
	$years=array();
		if(mysql_num_rows($last_month)<=0)
			$years[0] ="Unknown settings";
		else{
			$month=mysql_fetch_array($last_month);
			if($month['last_month']==12){
				$i=0;
				foreach($year=years() as $yr){
					$years[$i]=$yr;
					$i++;
				}
			}
			else{
				$i=1;
				$yrp=0;
				foreach($year=years() as $yr){
					if($i%2==1)
						$yrp=$yr;
					else{
						if($yr-$yrp==1)
							$years[$i]=sprintf("%04d",$yrp)."/".sprintf("%04d",$yr);
						else{
							$years[$i]=sprintf("%04d",$yrp)."/".sprintf("%04d",$yrp+1);
							$i++;
							$years[$i]=sprintf("%04d",$yr)."/".sprintf("%04d",$yr+1);
						}
					}
					$i++;
				}
			}
		}
				return $years;
	}
	/******************************
	*lists all the finacial years
	*doos not take any parameters
	********************************/
	function list_financial_years($year=""){
		$numCount=count(years());
		
		$content="<select name='year' id='year'>";
		foreach($year=financial_years() as $yr){
			$sel=$year!=""&&$year==$yr?"selected":NULL;
			$content .="<option value='".$yr."' ".$sel.">".$yr."</option>";
		}
		$content.="</select>";
		return $content;
	}
	function getLastDay($month){
		switch($month){
			case 1:
			case 3:
			case 5:
			case 7:
			case 8:
			case 10:
			case 12:
				return 31;
			break;
			case 2:
				return 28;
			break;
			default:
				return 30;
		}
		return 0;
	}
	/**********************************
	*returns last month as set        *
	*by an institution in the settings*
	**********************************/
	function getLastMonth(){
		$last_month=mysql_query("select distinct last_month from branch");
		if(mysql_num_rows($last_month)>0){
			$month= mysql_fetch_array($last_month);
		    return intval($month['last_month']);
		}
		return 0;
	}
	/************************************************
	*takes in date and return a valid financial year*
	*e.g parseFY(2009,4,28);  or parseFY(2009,4);   *
	************************************************/
	function parseFY($year,$month,$day=30){
		$lastMonth=getLastMonth();
		if($lastMonth==12)
			return $year;
		if(intval($month)<=$lastMonth&&array_search($year,years(),true)>0)
			return ($year-1)."/".($year);
		return $year."/".($year+1);
	}
	/**********************************************
	*check that the given financial year is closed*
	*e.g isFYClosed("2009/2010") or               *
	*isFYClosed(parseFY(2009,9,30))               *
	***********************************************/
	function isFYClosed($year){
		if($year=mysql_query("select financial_year from fiscal where financial_year='".$year."'"))
			if(mysql_num_rows($year)>0)
			return 1;
		return 0;
	}
	/*****************************************
	*
	*
	******************************************/
	function isPrevFYClosed($year){
		$yr=financial_years();
		$pos=array_search($year,$yr,true);
		if($pos==0)
			return 0;
		if($pos>0&&isFYClosed($yr[$pos-1]))
			return 1;
		return 0;
	}
	function isFirstYear($year){
		$yr=financial_years();
		if(min($yr)==$year)
			return 1;
		return 0;
	}
	function getTransDate($trans_id,$table,$date_field="date"){
		if($date=mysql_query("select ".$date_field." from ".$table." where id=".$trans_id)){
			$row=mysql_fetch_array($date);
			return $row[$date_field];
		}
		return 0;
	}

	function getPrevFY($year){
		$yr=financial_years();
		$pos=array_search($year,$yr,true);
		if($pos==0)
			return 0;
		if($pos>0)
			return $yr[$pos-1];
	}
	function getPrevAmount($id,$year,$report_type){
		$amount=mysql_query("select balance from account_values where id='".$id."' and fiscal_id=(select id from fiscal where report_type='".$report_type."' and financial_year='".getPrevFY($year)."')");
		if(mysql_num_rows($amount)>0){
			$value=mysql_fetch_array($amount);
			return floatval($value['balance']);
		}
		return 0.0;
	}
	/*
	 *Undo Close Accounts
	 */
	function undoCloseAccounts($year){
		$max_year=mysql_query("select year from fiscal where id=(select max(id) from fiscal)");
	}
	
	/*
	 *Large hexadecimal to integer
	 *
	 */
	 function bchexdec($hex){
    		$dec = 0;
    		$len = strlen($hex);
    		for ($i = 1; $i <= $len; $i++) {
        		$dec = bcadd($dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
    		}
    	return $dec;
	}
	
	
	/*
	 *version
	 */
	function writeVersion(){
		return "CraneApps-Banking v1.0.1";
	}
?>