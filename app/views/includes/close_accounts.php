<?
/**********************************************************************************
*close_accounts class created to handle all accounts for closing fincancial years
*@date 2010-03-17 (Thur) 16:47 EAT
*@file close_accounts
*@Updated 2010-03-22 (Mon) 08:30 EAT 
**********************************************************************************/
/*
*balance sheet class
*check that the sheet is balanced
*store amounts for the respective accounts
*/
class balanceSheet{
	var $year;
	var $basis;
	var $datefrom="";
	var $dateto="";
	var $yearfrom;
	var $yearto;
	function balanceSheet($year,$basis=""){
		$this->year=$year;
		$this->basis=$basis;
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
function setDate(){
	$last_month=mysql_query("select distinct last_month from branch");
	if(mysql_num_rows($last_month)>0){
		$month= mysql_fetch_array($last_month);
		if(intval($month['last_month'])==12){
			$this->yearfrom=$this->year;
			$this->yearto=$this->year;
		}
		else{
			$year=explode("/",$this->year);
			$this->yearfrom=$year[0];
			$this->yearto=$year[1];
		}
		$this->datefrom=sprintf("%04d-%02d-01 00:00:00",$this->yearfrom,intval($month['last_month']+1)%12);
		$this->dateto=sprintf("%04d-%02d-%02d 23:59:59",$this->yearto,intval($month['last_month']),$this->getLastDay(intval($month['last_month'])));
	}
	if($this->datefrom==""||$this->dateto=="")
		throw new Exception("Invalid data range");
}
function getDatefrom(){
	return $this->datefrom;
}
function getDateTo(){
	return $this->dateto;
}
function getRange($dateField="",$clause=""){
		$range_=$clause=="where"?" where ".$dateField." >='".$this->datefrom."' and ".$dateField." <='".$this->dateto."'":" and ".$dateField." >='".$this->datefrom."' and ".$dateField." <='".$this->dateto."'";
		return $range_;
		
}
function test($fiscal_id,$thisance,$account_id,$report_type){
return $fiscal_id."--".$thisance."--".$account_id."--".$report_type;
}
function prevBal($id,$fiscal_id,$report_type){
$pre_bal=0.0;
switch($report_type){
case "balance sheet":
case "trial balance":
$bal=mysql_query("select a.balance from account_values a join fiscal f on a.fiscal_id=f.id where a.account_id=".$id." and f.id=".$fiscal_id." and f.close_date=(select max(close_date) from fiscal where id=".$fiscal_id.")");
if(mysql_num_rows($bal)>0){
$amount=mysql_fetch_array($bal);
$prev_bal= $amount['balance'];
}
break;
default:
 break;
}
return $prev_bal;
}
function isFiscalSaved($close_date,$report_type){
return mysql_query("insert into fiscal(close_date,report_type,financial_year)values('".$close_date."','".$report_type."','".$this->year."')");
}
function storeValue($fiscal_id,$thisance,$account_id,$report_type){
return mysql_query("insert into account_values(fiscal_id,balance,account_id)values(".$fiscal_id.",".($thisance+$this->prevBal($account_id,$fiscal_id,$report_type)).",".$account_id.")");
}

# Calculate Balance
function calcBalance(){
	try{
		$level2_res= mysql_query("select * from accounts where account_no >10 and account_no <20 order by account_no");
		$assets = 0;
		while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
			$level3_res = mysql_query("select * from accounts where account_no >=111 and account_no <= 199 and account_no like '".$level2['account_no']."%' and account_no not in (124, 112) order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				$grand_total = 0;
				if($level3['account_no'] == '111'){  //LOANS
					$grand_loss = 0;
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1111 and account_no <= 1119");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$loss_total=0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 111101 and account_no <= 111999");
						while($level5 = mysql_fetch_array($level5_res)){
							$loan1_res =  mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level5['id']."' ".$this->getRange("d.date"));
							$loan1 = mysql_fetch_array($loan1_res);
							if($loan1['amount'] == NULL){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 11110101 and account_no <= 11199999");
								if(mysql_numrows($level6_res) >0){
									
									$sub2_total = 0;
									$loss2_total = 0;
									while($level6 = mysql_fetch_array($level6_res)){
										$loan2_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level6['id']."'".$this->getRange("p.date"));
										$loan2 = mysql_fetch_array($loan2_res);
										$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level6['id']."' ".$this->getRange("pay.date"));
										$pay = mysql_fetch_array($pay_res);
										$loan2_amt = ($loan2['amount'] == NULL) ? 0 : $loan2['amount'];
										
										$loss_res = mysql_query("select sum(w.amount) as amount from written_off w join disbursed d on w.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id ".$this->getRange("w.date","where")." and p.account_id='".$level6['id']."'");
										$loss = mysql_fetch_array($loss_res);
										$loss2_total = $loss2_total + $loss['amount'];
										$sub2_total = $sub2_total + $loan2_amt - $pay['amount'] - $loss['amount'];
									}
								} 
							}else{
								$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level5['id']."' ".$this->getRange("pay.date"));
								$pay = mysql_fetch_array($pay_res);
								$loss_res = mysql_query("select sum(w.amount) as amount from written_off w join disbursed d on w.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id and p.account_id='".$level5['id']."' ".$this->getRange("w.date"));
								$loss = mysql_fetch_array($loss_res);
								$loss2_total = $loss['amount'];
								$sub2_total = $loan1['amount'] - $pay['amount'] - $loss['amount'];
							}	
							$sub1_total = $sub1_total + $sub2_total;
							$loss_total = $loss_total + $loss2_total;
							$sub2_total = 0;
						}

						$grand_total = $grand_total + $sub1_total;
						//$grand_loss = $grand_loss + $loss_total;
					}
					//LOAN LOSS ALLOWANCES
					$grand_loss=0;
					$prov_res = mysql_query("select * from provissions order by percent asc");
					while($prov = mysql_fetch_array($prov_res)){
						preg_match("/(\d*)[_](\d*)$/", $prov['range'], $arg);
						$interval = ($arg[1] <>180) ? ($arg[2] - $arg[1]) : 180;
						if($arg[2]==''){
							$arg[1] =180;
							$arg[2]=660;     //UP TO 22 MONTHS OF ARREARS, THE ASSUMPTION IS THAT BEYOND THAT, THE LOAN WILL HAVE BEEN WRITTEN OFF
						}
						
						$over_180 =0;
						$sched_res = mysql_query("select sum(princ_amt) as amount, loan_id as loan_id from schedule  ".$this->getRange("date","where")." and loan_id not in (select loan_id from written_off group by loan_id) ".$this->getRange("date"));
					
					
						while($sched = mysql_fetch_array($sched_res)){
							$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
							$all = mysql_fetch_array(mysql_query("select sum(princ_amt) as amount from schedule ".$this->getRange("date","where")."   and loan_id='".$sched['loan_id']."'"));
							$paid_res = mysql_query("select sum(princ_amt) as amount from payment ".$this->getRange("date","where")." and loan_id='".$sched['loan_id']."'");
							$paid = mysql_fetch_array($paid_res);
							$paid_amt = ($paid['amount'] == NULL) ? 0 : $paid['amount'];
							$arrears = $all['amount'] - $paid_amt;
							if($arrears < 0){
								$arrears =0;
							}
							
							$arrears = ($arrears > $sched_amt) ? $sched_amt : $arrears;
							$over_180  += $arrears;

						}	
						$loss180_over = floor($over_180 * $prov['percent']/100);
						$grand_loss += $loss180_over;
					}
					//MANUAL LOAN LOSS PROVISSION
					$static = mysql_fetch_array(mysql_query("select sum(amount) as amount from otherloan_loss ".$this->getRange("date","where")));
					$static_amt = ($static['amount'] == NULL) ? 0 : $static['amount'];
					$grand_loss = $grand_loss + $static_amt;
					$grand_net = $grand_total - $grand_loss;  //SUBTRACT LOAN LOSS PROVISION
				}elseif($level3['account_no'] >= 113 && $level3['account_no'] <= 119){   //INVESTMENTS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1131 and account_no <= 1199");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level4['id']."' ".$this->getRange("date"));
						$invest = mysql_fetch_array($invest_res);
						if($invest['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 113101 and account_no <= 119999");
							while($level5 = mysql_fetch_array($level5_res)){
								$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level5['id']."' ".$this->getRange("date"));
								$invest = mysql_fetch_array($invest_res);
								$sub2_total=0;
								if($invest['amount'] ==NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 11310101 and account_no <= 11399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level6['id']."' ".$this->getRange("date"));
										$invest = mysql_fetch_array($invest_res);
										
										$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level6['id']."' ".$this->getRange("date"));
										$sold = mysql_fetch_array($sold_res);
										$sold_amt = ($sold['quantity'] == NULL) ? 0 : $sold['amount'];
										$sub2_total = $sub2_total + $invest['amount'] - $sold_amt;
									}
								}else{
									$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level5['id']."' ".$this->getRange("date"));
									$sold = mysql_fetch_array($sold_res);
									$sold_amt = ($sold['quantity'] == NULL) ? 0 : $sold['amount'];
									$sub2_total = $invest['amount'] - $sold_amt;
								}
								$sub1_total = $sub1_total + $sub2_total;
							}
						}else{							
							$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level4['id']."' ".$this->getRange("date"));
							$sold = mysql_fetch_array($sold_res);
							$sold_amt = ($sold['amount'] == NULL) ? 0 : $sold['amount'];
							$sub1_total = $invest['amount'] - $sold_amt;
						}
						$grand_total = $grand_total + $sub1_total;
					}
				}elseif($level3['account_no'] >= 125){   //INVESTMENTS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1251 and account_no <= 1259");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 125101 and account_no <= 125999");
						while($level5 = mysql_fetch_array($level5_res)){
							$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level5['id']."' ".$this->getRange("date"));
							$invest = mysql_fetch_array($invest_res);
							$sub2_total=0;
							if($invest['amount'] == NULL){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 12510101 and account_no <= 11399999");
								while($level6 = mysql_fetch_array($level6_res)){
									$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level6['id']."' ".$this->getRange("date","where")." and id not in (select investment_id from sold_invest ".$this->getRange("date","where"));		
									$invest = mysql_fetch_array($invest_res);

									$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level5['id']."' ".$this->getRange("date")." order by date desc limit 1"));
									$sold_res = mysql_query("select sum(quantity) as quantity from sold_invest where account_id='".$level5['id']."' ".$this->getRange("date"));
									$sold = mysql_fetch_array($sold_res);
									$sold_amt = ($sold['quantity'] == NULL) ? 0 : ($sold['quantity'] * $unit['amount']);
									$sub2_total =$sub2_total + $invest['amount'] - $sold_amt;		

									$sub2_total = $sub2_total + $invest['amount'];
								}
							}else{
								$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level5['id']."' ".$this->getRange("date")." order by date desc limit 1"));
								$sold_res = mysql_query("select sum(quantity) as quantity from sold_invest where account_id='".$level5['id']."' ".$this->getRange("date"));
								$sold = mysql_fetch_array($sold_res);
								$sold_amt = ($sold['quantity'] == NULL) ? 0 : ($sold['quantity'] * $unit['amount']);
								$sub2_total = $invest['amount'] - $sold_amt;		
							}
							$sub1_total = $sub1_total + $sub2_total;
						}
						$grand_total = $grand_total + $sub1_total;
					}
				}elseif($level3['account_no'] == 121){
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1211 and account_no <= 1219");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 121101 and account_no <= 121999");
						while($level5 = mysql_fetch_array($level5_res)){
							$bank1_res = mysql_query("select * from bank_account where account_id='".$level5['id']."'");
							$sub2_total = 0;
							if(mysql_numrows($bank1_res) == 0){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 12110101 and account_no <= 12199999");
								while($level6 = mysql_fetch_array($level6_res)){
									$bank2_res = mysql_query("select * from bank_account where account_id='".$level6['id']."'");
									$bank2 = mysql_fetch_array($bank2_res);
									//DEPOSITS
									$dep_res = mysql_query("select sum(amount) as amount from deposit where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$dep = mysql_fetch_array($dep_res);
									//WITHDRAWALS
									$with_res = mysql_query("select sum(amount) as amount from withdrawal where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$with = mysql_fetch_array($with_res);
									//OTHER INCOME
									$other_res = mysql_query("select sum(amount) as amount from other_income where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$other = mysql_fetch_array($other_res);
									//LOANS PAYABLE
									$loans_payable = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '212%' and p.bank_account='".$bank2['id']."' ".$this->getRange("p.date"));
									$loans_payable = mysql_fetch_array($loans_payable);
									$loans_payable = ($loans_payable['amount'] != NULL) ? $loans_payable['amount'] : 0;
									//EXPENSES
									$expense_res = mysql_query("select sum(amount) as amount from expense where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$expense = mysql_fetch_array($expense_res);
									//PAYABLE PAID
									$payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where bank_account='".$bank2['id']."' ".$this->getRange("date"));	
									$payable_paid = mysql_fetch_array($payable_paid_res);
									//RECEIVABLE COLLECTED
									$collected_res = mysql_query("select sum(amount) as amount from collected where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$collected = mysql_fetch_array($collected_res);
									//DISBURSED LOANS
									$disb_res = mysql_query("select sum(amount) as amount from disbursed where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$disb = mysql_fetch_array($disb_res);
									//PAYMENTS
									$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id  ".$this->getRange("date","where")." and p.bank_account='".$bank2['id']."'");
									$pay = mysql_fetch_array($pay_res);
									//PENALTIES
									$pen_res = mysql_query("select sum(p.amount) as amount from penalty p join disbursed d on p.loan_id=d.id where p.bank_account='".$bank2['id']."' and p.status='paid' ".$this->getRange("p.date"));
									$pen = mysql_fetch_array($pen_res);
								
									//SHARES
									$shares_res = mysql_query("select sum(value) as amount from shares ".$this->getRange("date","where")." and bank_account='".$bank2['id']."'");
									$shares = mysql_fetch_array($shares_res); 
									//RECOVERED
									$rec_res = mysql_query("select sum(r.amount) as amount from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.bank_account='".$bank2['id']."' ".$this->getRange("r.date"));
									$rec = mysql_fetch_array($rec_res);			
									//INVESTMENTS 
									$invest_res = mysql_query("select sum(quantity * amount) as amount from investments ".$this->getRange("date","where")." and bank_account='".$bank2['id']."'");
									$invest = mysql_fetch_array($invest_res);
									$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
									//DIVIDENDS PAID
									$div_res = mysql_query("select sum(total_amount) as total_amount from share_dividends ".$this->getRange("date","where")." and bank_account='".$bank1['id']."'");
									$div = mysql_fetch_array($div_res);
									$div_amt = ($div['amount'] != NULL) ? $div['amount'] : 0;
									
									//SOLD INVESTMENTS
									$soldinvest_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where bank_account ='".$bank2['id']."' ".$this->getRange("date"));
									$soldinvest = mysql_fetch_array($soldinvest_res);

									//FIXED ASSETS 
									$fixed_res = mysql_query("select sum(initial_value) as amount from fixed_asset where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$fixed = mysql_fetch_array($fixed_res);
									$soldasset_res = mysql_query("select sum(amount) as amount from sold_asset where bank_account ='".$bank2['id']."' ".$this->getRange("date"));
									$soldasset = mysql_fetch_array($soldasset_res);
									
									//CASH IMPORTED
									$import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id='".$bank2['id']."' ".$this->getRange("date"));
									$import = mysql_fetch_array($import_res);
									$import_amt = ($import['amount'] ==NULL) ? 0 : $import['amount'];
								
									//CASH EXPORTED
									$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id='".$bank2['id']."' ".$this->getRange("date"));
									$export = mysql_fetch_array($export_res);
									$export_amt = ($export['amount'] ==NULL) ? 0 : $export['amount'];

									//CAPITAL FUNDS
									$fund_res = mysql_query("select sum(amount) as amount from other_funds where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$fund = mysql_fetch_array($fund_res);
									$fund_amt = $fund['amount'];


									$sub2_total = $sub2_total + $collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt + $fund_amt;		
									
								}
							}else{
								$bank1 = mysql_fetch_array($bank1_res);
								//DEPOSITS
								$dep_res = mysql_query("select sum(amount) as amount from deposit where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$dep = mysql_fetch_array($dep_res);
								$dep_amt = ($dep['amount'] == NULL) ? 0 : $dep['amount'];
								//WITHDRAWALS
								$with_res = mysql_query("select sum(amount) as amount from withdrawal where bank_account='".$bank1['id']."' ");
								$with = mysql_fetch_array($with_res);
								$with_amt = ($with['amount'] == NULL) ? 0 : $with['amount'];
								//OTHER INCOME
								$other_res = mysql_query("select sum(amount) as amount from other_income where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$other = mysql_fetch_array($other_res);
								$other_amt = ($other['amount'] == NULL) ? 0 : $other['amount'];
								//EXPENSES
								$expense_res = mysql_query("select sum(amount) as amount from expense where bank_account='".$bank1['id']."' ");
								$expense = mysql_fetch_array($expense_res);
								$expense_amt = ($expense['amount'] == NULL) ? 0 : $expense['amount'];
								//LOANS PAYABLE
								$loans_payable = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '212%' and p.bank_account='".$bank1['id']."' ".$this->getRange("p.date"));
								$loans_payable = mysql_fetch_array($loans_payable);
								$loans_payable = ($loans_payable['amount'] != NULL) ? $loans_payable['amount'] : 0;
								//PAYABLE PAID
								$payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$payable_paid = mysql_fetch_array($payable_paid_res);
								$payable_paid_amt = ($payable_paid['amount'] == NULL) ? 0 : $payable_paid['amount'];
								//RECEIVALE COLLECTED
								$collected_res = mysql_query("select sum(amount) as amount from collected where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$collected = mysql_fetch_array($collected_res);
								$collected_amt = ($collected['amount'] == NULL) ? 0 : $collected['amount'];
								//DISBURSED LOANS
								$disb_res = mysql_query("select sum(amount) as amount from disbursed where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$disb = mysql_fetch_array($disb_res);
								$disb_amt = ($disb['amount'] == NULL) ? 0 : $disb['amount'];
								//PAYMENTS
								$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id  ".$this->getRange("p.date","where")." and p.bank_account='".$bank1['id']."'");
								$pay = mysql_fetch_array($pay_res);
								$pay_amt = ($pay['amount'] == NULL) ? 0 : $pay['amount'];
								//PENALTIES
								$pen_res = mysql_query("select sum(p.amount) as amount from penalty p join disbursed d on p.loan_id=d.id where p.bank_account='".$bank1['id']."' and p.status='paid' ".$this->getRange("p.date"));
								$pen = mysql_fetch_array($pen_res);
								$pen_amt = ($pen['amount']==NULL) ? 0 : $pen['amount'];
								
								//SHARES
								$shares_res = mysql_query("select sum(value) as amount from shares ".$this->getRange("date","where")." and bank_account='".$bank1['id']."'");
								$shares = mysql_fetch_array($shares_res); 
								$shares_amt = ($shares['amount'] == NULL) ? 0 : $shares['amount'];
								//RECOVERED
								$rec_res = mysql_query("select sum(r.amount) as amount from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.bank_account='".$bank1['id']."' ".$this->getRange("r.date"));
								$rec = mysql_fetch_array($rec_res);	
								$rec_amt = ($rec['amount'] == NULL) ? 0 : $rec['amount']; 
								//INVESTMENTS 
								$invest_res = mysql_query("select sum(quantity * amount) as amount from investments ".$this->getRange("date","where")." and bank_account='".$bank1['id']."'");
								$invest = mysql_fetch_array($invest_res);
								$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
								//DIVIDENDS PAID
								$div_res = mysql_query("select sum(total_amount) as total_amount from share_dividends ".$this->getRange("date","where")." and bank_account='".$bank1['id']."'");
								$div = mysql_fetch_array($div_res);
								$div_amt = ($div['amount'] != NULL) ? $div['amount'] : 0;

								
								$soldinvest_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where bank_account ='".$bank1['id']."' ".$this->getRange("date"));
								$soldinvest = mysql_fetch_array($soldinvest_res);

								//FIXED ASSETS 
								$fixed_res = mysql_query("select sum(initial_value) as amount from fixed_asset where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$fixed = mysql_fetch_array($fixed_res);
								$soldasset_res = mysql_query("select sum(amount) as amount from sold_asset where bank_account ='".$bank1['id']."' ".$this->getRange("date"));
								$soldasset = mysql_fetch_array($soldasset_res);
									
								//CASH IMPORTED
								$import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id=".$bank1['id']." ".$this->getRange("date"));
								$import = mysql_fetch_array($import_res);
								$import_amt = ($import['amount'] ==NULL) ? 0 : $import['amount'];

								//CASH EXPORTED
								$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id=".$bank1['id']." ".$this->getRange("date"));
								$export = mysql_fetch_array($export_res);
								$export_amt = ($export['amount'] ==NULL) ? 0 : $export['amount'];

								//CAPITAL FUNDS
								$fund_res = mysql_query("select sum(amount) as amount from other_funds where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$fund = mysql_fetch_array($fund_res);
								$fund_amt = $fund['amount'];


								$sub2_total = $collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt + $fund_amt;
							}
							$sub1_total = $sub1_total + $sub2_total;
							
						}
						$grand_total += $sub1_total;
					}
				}elseif($level3['account_no']=='122'){
					if($basis == 'Cash Basis')
						continue;
					$rec_res = mysql_query("select sum(amount) as amount from receivable ".$this->getRange("maturity_date","where"));
					$rec = mysql_fetch_array($rec_res);
					if($rec['amount'] != NULL){
						$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id ".$this->getRange("c.date","where"));
						$col = mysql_fetch_array($col_res);
						$grand_total =  $rec['amount'] - $col['amount'];
					}
				}elseif($level3['account_no'] == '123'){  //FIXED ASSETS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['[account_no']."%' and account_no >= 1231 and account_no <= 1239");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$depp1_total = 0;
						$fixed_res = mysql_query("select  sum(f.initial_value) as amount from fixed_asset f where f.account_id='".$level4['id']."' and f.id not in (select asset_id from sold_asset ".$this->getRange("date","where").") ".$this->getRange("f.date"));
						$fixed = mysql_fetch_array($fixed_res);
						if($fixed['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 123101 and account_no <= 123999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$depp2_total = 0;
								$fixed_res = mysql_query("select  sum(f.initial_value) as amount from fixed_asset f where f.account_id='".$level5['id']."' and f.id not in (select asset_id from sold_asset ".$this->getRange("date","where").") ".$this->getRange("f.date"));
								$fixed = mysql_fetch_array($fixed_res);
								if($fixed['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >=12310101 and account_no <= 12399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$fixed_res = mysql_query("select  sum(f.initial_value) as amount from fixed_asset f where f.account_id='".$level6['id']."' and f.id not in (select asset_id from sold_asset ".$this->getRange("date","where").") ".$this->getRange("f.date"));
										$fixed = mysql_fetch_array($fixed_res);
										$sub2_total = $sub2_total + $fixed['amount'];
										$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from  fixed_asset where account_id='".$level6['id']."' ".$this->getRange("date").") ".$this->getRange("date"));
										$depp = mysql_fetch_array($depp_res);
										$depp2_total = $depp2_total + $depp['amount'];
									}
								}else{
									$sub2_total = $fixed['amount'];
									$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from  fixed_asset where account_id='".$level5['id']."' ".$this->getRange("date").") ".$this->getRange("date"));
									$depp = mysql_fetch_array($depp_res);
									$depp2_total = $depp2_total + $depp['amount'];
								}
								$acc_res = mysql_query("select * from accounts where name='Accumulated Depreciation - ".$level5['name']."'");
								$acc = mysql_fetch_array($acc_res);
								$net_total = $sub2_total - $depp2_total;	
								$sub1_total += $sub2_total;
								$depp1_total += $depp2_total;
							}
						}else{
							$sub1_total = $fixed['amount'];
							$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from  fixed_asset where account_id='".$level4['id']."' ".$this->getRange("date").") ".$this->getRange("date"));
							$depp = mysql_fetch_array($depp_res);
							$depp1_total = $depp1_total + $depp['amount'];
						}
						$net_total = $sub1_total - $depp1_total;
						if($level4['name'] != 'Land'){
							$acc_res = mysql_query("select * from accounts where name='Accumulated Depreciation - ".$level4['name']."'");
							$acc = mysql_fetch_array($acc_res);
						}
						$grand_total = $grand_total + $net_total;
					}
				}
				$level2_total += $grand_total;
				if($level3['account_no'] == 111){
					$loss_res = mysql_query("select * from accounts where account_no='112'");
					$loss = mysql_fetch_array($loss_res);
					$level2_total -= $grand_loss;
				}
			}			
			$assets += $level2_total;     #total assets
		}
//LIABILITIES
		$capital = 0;
		$liabilities = 0;
		while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
			$level3_res = mysql_query("select * from accounts where account_no >=211 and account_no <= 399 and account_no like '".$level2['account_no']."%' order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				$grand_total = 0;
				if($level3['account_no'] == '211'){  //SAVINGS & DEPOSITS
					
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=2111 and account_no <= 2119");
					while($level4 = mysql_fetch_array($level4_res)){
						if($level4['name'] == 'Compulsory Savings')
							continue;
						$sub1_total = 0;
						$prod_res = mysql_query("select * from savings_product where account_id='".$level4['id']."'");
						if(mysql_numrows($prod_res) > 0){
							$prod = mysql_fetch_array($prod_res);
							$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("d.date"));
							$dep = mysql_fetch_array($dep_res);
							$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.id='".$prod['id']."' and p.type='free' and ".$this->getRange("w.date"));
							$with = mysql_fetch_array($with_res);
							$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("i.date"));
							$int = mysql_fetch_array($int_res);
							$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
							//OTHER DEDUCTIONS
							$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') ".$this->getRange("o.date")));
							$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;
							//MONTHLY ACCOUNT CHARGES
							$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts mem on c.memaccount_id=mem.id join savings_product p on mem.saveproduct_id=p.id ".$this->getRange("c.date","where")." and p.id='".$prod['id']."' and p.type='free'");
							$charge = mysql_fetch_array($charge_res);
							//OFFSETTING FROM SAVINGS
							$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' ".$this->getRange("p.date"));
							$pay = mysql_fetch_array($pay_res);
							$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;
							
							//SHARES OFFSET FROM SAVINGS
							$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') ".$this->getRange("s.date")));
							$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;

							$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
							$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
							$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
							
							$sub1_total = $dep_amt + $int_amt - $with_amt - $income_amt - $charge_amt- $pay_amt - $share_amt;  
						}else{
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 211101 and account_no <= 211999");
							while($level5 = mysql_fetch_array($level5_res)){
								if($level5['name'] == 'Compulsory Shares')
									continue;
								$sub2_total =0;
								$prod_res = mysql_query("select * from savings_product where account_id='".$level5['id']."'");
								if(mysql_numrows($prod_res) > 0){
									$prod = mysql_fetch_array($prod_res);
									$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("d.date"));
									$dep = mysql_fetch_array($dep_res);
									$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("w.date"));
									$with = mysql_fetch_array($with_res);
									$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("i.date"));
									$int = mysql_fetch_array($int_res);
									$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
									//OTHER DEDUCTIONS
									$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') ".$this->getRange("o.date")));
									$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;
									//MONTHLY ACCOUNT CHARGES
									$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts mem on c.memaccount_id=mem.id join savings_product p on mem.saveproduct_id=p.id ".$this->getRange("c.date","where")." and p.id='".$prod['id']."' and p.type='free'");
									$charge = mysql_fetch_array($charge_res);
									//OFFSETTING FROM SAVINGS
									$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' ".$this->getRange("p.date"));
									$pay = mysql_fetch_array($pay_res);
									$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;
									$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;

									//SHARES OFFSET FROM SAVINGS
									$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') ".$this->getRange("s.date")));
									$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;

									
									$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
									$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
									$sub2_total = $dep_amt + $int_amt - $with_amt - $charge_amt - $income_amt - $pay_amt - $share_amt; // + $int['amount'];
									$sub1_total = $sub1_total + $sub2_total;
								}else{
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 21110101 and account_no <= 21199999");
									while($level6 = mysql_fetch_array($level6_res)){
										$prod_res = mysql_query("select * from savings_product where account_id='".$level6['id']."'");
										if(mysql_numrows($prod_res) > 0){
											$prod = mysql_fetch_array($prod_res);
											$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("d.date"));
											$dep = mysql_fetch_array($dep_res);
											$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("w.date"));
											$with = mysql_fetch_array($with_res);
											$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("i.date"));
											$int = mysql_fetch_array($int_res);
											$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
											//OTHER DEDUCTIONS
											$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') ".$this->getRange("o.date")));
											$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;
											//MONTHLY ACCOUNT CHARGES
											$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts mem on c.memaccount_id=mem.id join savings_product p on mem.saveproduct_id=p.id ".$this->getRange("c.date","where")." and p.id='".$prod['id']."' and p.type='free'");
											$charge = mysql_fetch_array($charge_res);
											$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
											//OFFSETTING FROM SAVINGS
											$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' ".$this->getRange("p.date"));
											$pay = mysql_fetch_array($pay_res);
											$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;

											//SHARES OFFSET FROM SAVINGS
											$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') ".$this->getRange("s.date")));
											$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;
											$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
											$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
											$sub2_total = $sub2_total + $dep_amt + $int_amt - $with_amt - $charge_amt - $income_amt - $share_amt;  // + $int['amount'];
										}
									}
									$sub1_total = $sub1_total + $sub2_total;
								}
							}
						}
						
						$grand_total = $grand_total + $sub1_total;
				}
				$level2_total += $grand_total;
				$liabilities += $grand_total;
			}elseif($level3['account_no'] >='212' && $level3['account_no'] <= 299){
				$grand_total=0;
				
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=2121 and account_no <= 2999");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['name'] == 'Interest Payable on Savings Deposits'){}
					elseif($level4['name'] == 'Interest Payable on Time Deposits'){}
					elseif($level4['name'] == 'Dividends Payable'){
						$div_res = mysql_query("select sum(d.amount) as amount from dividends d join share_dividends s on d.share_dividend_id=s.id where s.bank_account=0 ".$this->getRange("s.date"));
						$div = mysql_fetch_array($div_res);
						$dividends = intval($div['amount']);
						$sub1_total = $dividends;
					}else{
						$pay_res = mysql_query("select sum(amount) as amount from payable where account_id='".$level4['id']."' a".$this->getRange("date"));
						$pay = mysql_fetch_array($pay_res);
						
						if($pay['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 212101 and account_no <= 213999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$pay_res = mysql_query("select sum(amount) as amount from payable where account_id='".$level5['id']."' ".$this->getRange("date"));
								$pay = mysql_fetch_array($pay_res);
								if($pay['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >=21210101 and account_no <= 21399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$pay_res = mysql_query("select sum(amount) as amount from payable where account_id='".$level6['id']."' ".$this->getRange("date"));
										$pay = mysql_fetch_array($pay_res);
										$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id ".$this->getRange("paid.date","where")." and p.account_id='".$level4['id']."'");
										$paid = mysql_fetch_array($paid_res);
										$sub2_total = $sub2_total + $pay['amount'] - $paid['amount'];
									}
								}else{
									$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id ".$this->getRange("padi.date","where")." and p.account_id='".$level5['id']."'");
									$paid = mysql_fetch_array($paid_res);
									$add = ($basis == 'Cash Basis') ? $paid['amount'] : $pay['amount'] - $paid['amount'];
									
									$sub2_total = $pay['amount'] - $paid['amount'];
								}
							$sub1_total += $sub2_total;
							}
						}else{
							$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id ".$this->getRange("paid.date","where")." and p.account_id='".$level4['id']."'");
							$paid = mysql_fetch_array($paid_res);
							$sub1_total = $pay['amount'] - $paid['amount'];
						}
					}
					$grand_total = $grand_total + $sub1_total;
					$level2_total += $sub1_total;
					$liabilities += $sub1_total;
				}
			}elseif($level3['account_no'] >= '311' && $level3['account_no'] <= '399'){
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 3111 and account_no <= 3999");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['account_no'] == '3111'){
						$share_res = mysql_query("select sum(value) as value from shares ".$this->getRange("date","where"));
						$share = mysql_fetch_array($share_res);
						$level42_res = mysql_query("select * from accounts where name like 'Member Shares'");
						$level42 = mysql_fetch_array($level42_res);
						$sub1_total = $share['value'];
						$sub1_total = ($sub1_total > 0) ? $sub1_total : 0;
					}elseif($level4['account_no'] == '3312'){
						$sub1_total -= $grand_loss;
						$sub1_total += net_cummulated_income($this->datefrom, $this->dateto, $this->basis);
					}else{
						$invest_res = mysql_query("select sum(amount) as amount from other_funds where account_id='".$level4['id']."' ".$this->getRange("date")." and id not in (select fund_id from payable ".$this->getRange("date","where")." and fund_id<>'0')");
						$invest = mysql_fetch_array($invest_res);
						if($invest['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 321101 and account_no <= 339999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$invest_res = mysql_query("select sum(amount) as amount from other_funds where account_id='".$level5['id']."' ".$this->getRange("date")."  and id not in (select fund_id from payable ".$this->getRange("date","where")." and fund_id<>'0')");
								$invest = mysql_fetch_array($invest_res);
								if($invest['amount'] == NULL){
									$level6_res = mysql_query("select  * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 32110101 and account_no <= 33999999");
									while($level6 = mysql_fetch_array($level6_res)){
										$invest_res = mysql_query("select sum(amount) as amount from other_funds where account_id='".$level6['id']."' ".$this->getRange("date")."   and id not in (select fund_id from payable ".$this->getRange("date","where")." and fund_id<>'0')");
										$invest = mysql_fetch_array($invest_res);
										$sub2_total = $sub2_total + $invest['amount'];
									}
								}else
									$sub2_total = $invest['amount'];
								$sub1_total = $sub1_total + $sub2_total;
							}
						}else{
							$sub1_total = $invest['amount'];
						}
					}
					$grand_total += $sub1_total;
					$level2_total += $sub1_total; 
					$capital += $sub1_total;
				}
			}
		}
	}
	$net_assets = $assets - $capital - $liabilities;
	$net_assets = ($net_assets >= 0) ? number_format($net_assets, 2) : "(".number_format((0 - $net_assets), 2).")";
	return $net_assets;	
	}
	catch(Exception $e){throw $e->getMessage();}
}

# Calculate Balance 2
function calcBalance2(){
	$date = $this->dateto;
	
		$level2_res= mysql_query("select * from accounts where account_no >10 and account_no <20 order by account_no");
		$assets = 0;
		$i=0;
		while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
			$color = ($i%2 == 0) ? "white" : "lightgrey";
			$content .= "<tr bgcolor=$color><td colspan=2><font size=4pt><b>".$level2['name']."</b></font></td><td></td></tr>";
			$i++;
			$level3_res = mysql_query("select * from accounts where account_no >=111 and account_no <= 199 and account_no like '".$level2['account_no']."%' and account_no not in (124, 112) order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td colspan=2 align=left><b>".$level3['name']."</b></td><td></td></tr>";
				$i++;
				$grand_total = 0;
				if($level3['account_no'] == '111'){  //LOANS
					$grand_loss = 0;
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1111 and account_no <= 1119");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$loss_total=0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 111101 and account_no <= 111999");
						if( mysql_numrows($level5_res) > 0){
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td></td><td><b>".$level4['name']."</b></td><td></td></tr>";
							$i++;
						}
						while($level5 = mysql_fetch_array($level5_res)){
							$loan1_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level5['id']."' and d.date <= '".$date."'");
							$loan1 = mysql_fetch_array($loan1_res);
							
							if($loan1['amount'] == NULL){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 11110101 and account_no <= 11199999");
								if(mysql_numrows($level6_res) >0){
									
									$sub2_total = 0;
									$loss2_total = 0;
									while($level6 = mysql_fetch_array($level6_res)){
										$loan2_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level6['id']."' and d.date <= '".$date."'");
										$loan2 = mysql_fetch_array($loan2_res);
								
										$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level6['id']."' and pay.date <='".$date."'");
										$pay = mysql_fetch_array($pay_res);
										$loan2_amt = ($loan2['amount'] == NULL) ? 0 : $loan2['amount'];
										
										$loss_res = mysql_query("select sum(w.amount) as amount from written_off w join disbursed d on w.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where w.date >= '".$date."' and p.account_id='".$level6['id']."'");
										$loss = mysql_fetch_array($loss_res);
										$loss2_total = $loss2_total + $loss['amount'];
										$sub2_total = $sub2_total + $loan2_amt - $pay['amount'] - $loss['amount'];
									}
								} 
							}else{
								$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level5['id']."'  and pay.date <='".$date."'");

								$pay = mysql_fetch_array($pay_res);
								$loss_res = mysql_query("select sum(w.amount) as amount from written_off w join disbursed d on w.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where w.date <= '".$date."'  and p.account_id='".$level5['id']."'");
								$loss = mysql_fetch_array($loss_res);
								$loss2_total = $loss['amount'];
								$sub2_total = $loan1['amount'] - $pay['amount'] - $loss['amount'];
							}
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td></td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
							$i++;
							
							$sub1_total = $sub1_total + $sub2_total;
							$loss_total = $loss_total + $loss2_total;
							$sub2_total = 0;
						}
						

						$grand_total = $grand_total + $sub1_total;
						//$grand_loss = $grand_loss + $loss_total;
					}
					//LOAN LOSS ALLOWANCES
					$grand_loss=0;
					$prov_res = mysql_query("select * from provissions order by percent asc");
			
					while($prov = mysql_fetch_array($prov_res)){
						preg_match("/(\d*)[_](\d*)$/", $prov['range'], $arg);
						$interval = ($arg[1] <>180) ? ($arg[2] - $arg[1]) : 180;
						if($arg[2]==''){
							$arg[1] =180;
							$arg[2]=660;     //UP TO 22 MONTHS OF ARREARS, THE ASSUMPTION IS THAT BEYOND THAT, THE LOAN WILL HAVE BEEN WRITTEN OFF
						}
						
						$over_180 =0;
						$sched_res = mysql_query("select sum(princ_amt) as amount, loan_id as loan_id from schedule where date <= DATE_SUB('".$date."', INTERVAL ".$arg[1]." DAY) and date >= DATE_SUB('".$date."', INTERVAL ".$arg[2]." DAY) and loan_id not in (select loan_id from written_off where date <= DATE_SUB('".$date."', INTERVAL ".$arg[1]." DAY)) group by loan_id");
					
					
						while($sched = mysql_fetch_array($sched_res)){
							$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
							$all = mysql_fetch_array(mysql_query("select sum(princ_amt) as amount from schedule where date <= DATE_SUB('".$date."', INTERVAL ".$arg[2]." DAY)  and loan_id='".$sched['loan_id']."'"));
							$paid_res = mysql_query("select sum(princ_amt) as amount from payment where date <='".$date."' and loan_id='".$sched['loan_id']."'");
							$paid = mysql_fetch_array($paid_res);
							$paid_amt = ($paid['amount'] == NULL) ? 0 : $paid['amount'];
							$arrears = $all['amount'] - $paid_amt;
							if($arrears < 0){
								$arrears =0;
							}
							
							$arrears = ($arrears > $sched_amt) ? $sched_amt : $arrears;
							$over_180  += $arrears;

						}	
						$loss180_over = floor($over_180 * $prov['percent']/100);
						$grand_loss += $loss180_over;
					}
					//MANUAL LOAN LOSS PROVISSION
					$static = mysql_fetch_array(mysql_query("select sum(amount) as amount from otherloan_loss where date <= '".$date."'"));
					$static_amt = ($static['amount'] == NULL) ? 0 : $static['amount'];
					$grand_loss = $grand_loss + $static_amt;
					$grand_net = $grand_total - $grand_loss;  //SUBTRACT LOAN LOSS PROVISIONS

				}elseif($level3['account_no'] >= 113 && $level3['account_no'] <= 119){   //INVESTMENTS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1131 and account_no <= 1199");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level4['id']."' and date <='".$date."'");
						$invest = mysql_fetch_array($invest_res);
						if($invest['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 113101 and account_no <= 119999");
							if( mysql_numrows($level5_res) > 0){
								
							}
							while($level5 = mysql_fetch_array($level5_res)){
								$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level5['id']."' and date<='".$date."'");
								$invest = mysql_fetch_array($invest_res);
								$sub2_total=0;
								if($invest['amount'] ==NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 11310101 and account_no <= 11399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level6['id']."' and date <='".$date."'");		
										$invest = mysql_fetch_array($invest_res);
								
										$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level6['id']."' and date <='".$date."'");
										$sold = mysql_fetch_array($sold_res);
										$sold_amt = ($sold['quantity'] == NULL) ? 0 : $sold['amount'];
										$sub2_total = $sub2_total + $invest['amount'] - $sold_amt;
									}
								}else{
									
									$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level5['id']."' and date <='".$date."'");
									$sold = mysql_fetch_array($sold_res);
									$sold_amt = ($sold['quantity'] == NULL) ? 0 : $sold['amount'];
									$sub2_total = $invest['amount'] - $sold_amt;
								}
								$sub1_total = $sub1_total + $sub2_total;
							}
						}else{
							$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level4['id']."' and date <='".$date."'");
							$sold = mysql_fetch_array($sold_res);
							$sold_amt = ($sold['amount'] == NULL) ? 0 : $sold['amount'];
							$sub1_total = $invest['amount'] - $sold_amt;
						}
						$grand_total = $grand_total + $sub1_total;
					}
				}elseif($level3['account_no'] >= 125){   //INVESTMENTS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1251 and account_no <= 1259");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 125101 and account_no <= 125999");
						if( mysql_numrows($level5_res) > 0){
							
						}
						while($level5 = mysql_fetch_array($level5_res)){
							$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level5['id']."' and date <= '".$date."'");
							$invest = mysql_fetch_array($invest_res);
							$sub2_total=0;
							if($invest['amount'] == NULL){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 12510101 and account_no <= 11399999");
								while($level6 = mysql_fetch_array($level6_res)){
									$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level6['id']."' and date <= '".$date."' and id not in (select investment_id from sold_invest where date<='".$date."')");		
									$invest = mysql_fetch_array($invest_res);

									$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level5['id']."' and date <='".$date."' order by date desc limit 1"));
									$sold_res = mysql_query("select sum(quantity) as quantity from sold_invest where account_id='".$level5['id']."' and date <='".$date."'");
									$sold = mysql_fetch_array($sold_res);
									$sold_amt = ($sold['quantity'] == NULL) ? 0 : ($sold['quantity'] * $unit['amount']);
									$sub2_total =$sub2_total + $invest['amount'] - $sold_amt;		

									$sub2_total = $sub2_total + $invest['amount'];
								}
							}else{
								$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level5['id']."' and date <='".$date."' order by date desc limit 1"));
								$sold_res = mysql_query("select sum(quantity) as quantity from sold_invest where account_id='".$level5['id']."' and date <='".$date."'");
								$sold = mysql_fetch_array($sold_res);
								$sold_amt = ($sold['quantity'] == NULL) ? 0 : ($sold['quantity'] * $unit['amount']);
								$sub2_total = $invest['amount'] - $sold_amt;		
							}
							$sub1_total = $sub1_total + $sub2_total;
						}
						$grand_total = $grand_total + $sub1_total;
					}
				}elseif($level3['account_no'] == 121){
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1211 and account_no <= 1219");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 121101 and account_no <= 121999");
						if( mysql_numrows($level5_res) > 0){
							$color= ($i%2 == 0) ? lightgrey : "white";
							$content .= "<tr bgcolor=$color><td><b></b></td><td><b>".$level4['name']."</b></td><td></td></tr>";
							$i++;
						}
						while($level5 = mysql_fetch_array($level5_res)){
							$bank1_res = mysql_query("select * from bank_account where account_id='".$level5['id']."'");
							$sub2_total = 0;
							if(mysql_numrows($bank1_res) == 0){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 12110101 and account_no <= 12199999");
								while($level6 = mysql_fetch_array($level6_res)){
									$bank2_res = mysql_query("select * from bank_account where account_id='".$level6['id']."'");
									$bank2 = mysql_fetch_array($bank2_res);
									//DEPOSITS
									$dep_res = mysql_query("select sum(amount) as amount from deposit where bank_account='".$bank2['id']."' and date <='".$date."'");
									$dep = mysql_fetch_array($dep_res);
									//WITHDRAWALS
									$with_res = mysql_query("select sum(amount) as amount from withdrawal where bank_account='".$bank2['id']."' and date <='".$date."'");
									$with = mysql_fetch_array($with_res);
									//OTHER INCOME
									$other_res = mysql_query("select sum(amount) as amount from other_income where bank_account='".$bank2['id']."' and date <='".$date."'");
									$other = mysql_fetch_array($other_res);
									//LOANS PAYABLE
									$loans_payable = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '212%' and p.bank_account='".$bank2['id']."' and p.date <= '".$date."'");
									$loans_payable = mysql_fetch_array($loans_payable);
									$loans_payable = ($loans_payable['amount'] != NULL) ? $loans_payable['amount'] : 0;
									//EXPENSES
									$expense_res = mysql_query("select sum(amount) as amount from expense where bank_account='".$bank2['id']."' and date <='".$date."'");
									$expense = mysql_fetch_array($expense_res);
									//PAYABLE PAID
									$payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where bank_account='".$bank2['id']."' and date <='".$date."'");	
									$payable_paid = mysql_fetch_array($payable_paid_res);
									//RECEIVABLE COLLECTED
									$collected_res = mysql_query("select sum(amount) as amount from collected where bank_account='".$bank2['id']."' and date <='".$date."'");
									$collected = mysql_fetch_array($collected_res);
									//DISBURSED LOANS
									$disb_res = mysql_query("select sum(amount) as amount from disbursed where bank_account='".$bank2['id']."' and date <= '".$date."'");
									$disb = mysql_fetch_array($disb_res);
									//PAYMENTS
									$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id  where p.date <= '".$date."' and p.bank_account='".$bank2['id']."'");
									$pay = mysql_fetch_array($pay_res);
									//PENALTIES
									$pen_res = mysql_query("select sum(p.amount) as amount from penalty p join disbursed d on p.loan_id=d.id where p.bank_account='".$bank2['id']."' and p.status='paid' and p.date <='".$date."'");
									$pen = mysql_fetch_array($pen_res);
								
									//SHARES
									$shares_res = mysql_query("select sum(value) as amount from shares where date <='".$date."' and bank_account='".$bank2['id']."'");
									$shares = mysql_fetch_array($shares_res); 
									//RECOVERED
									$rec_res = mysql_query("select sum(r.amount) as amount from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.bank_account='".$bank2['id']."' and r.date <= '".$date."'");
									$rec = mysql_fetch_array($rec_res);			
									//INVESTMENTS 
									$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where date <= '".$date."' and bank_account='".$bank2['id']."'");
									$invest = mysql_fetch_array($invest_res);
									$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
									//DIVIDENDS PAID
									$div_res = mysql_query("select sum(total_amount) as total_amount from share_dividends where date<='".$date."' and bank_account='".$bank1['id']."'");
									$div = mysql_fetch_array($div_res);
									$div_amt = ($div['amount'] != NULL) ? $div['amount'] : 0;
									
									//SOLD INVESTMENTS
									$soldinvest_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where bank_account ='".$bank2['id']."' and date <= '".$date."'");
									$soldinvest = mysql_fetch_array($soldinvest_res);

									//FIXED ASSETS 
									$fixed_res = mysql_query("select sum(initial_value) as amount from fixed_asset where bank_account='".$bank2['id']."' and date <='".$date."'");
									$fixed = mysql_fetch_array($fixed_res);
									$soldasset_res = mysql_query("select sum(amount) as amount from sold_asset where bank_account ='".$bank2['id']."' and date <= '".$date."'");
									$soldasset = mysql_fetch_array($soldasset_res);
									
									//CASH IMPORTED
									$import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id='".$bank2['id']."' and date <='".$date."'");
									$import = mysql_fetch_array($import_res);
									$import_amt = ($import['amount'] ==NULL) ? 0 : $import['amount'];
								
									//CASH EXPORTED
									$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id='".$bank2['id']."' and date <='".$date."'");
									$export = mysql_fetch_array($export_res);
									$export_amt = ($export['amount'] ==NULL) ? 0 : $export['amount'];

									//CAPITAL FUNDS
									$fund_res = mysql_query("select sum(amount) as amount from other_funds where bank_account='".$bank2['id']."' and date <='".$date."'");
									$fund = mysql_fetch_array($fund_res);
									$fund_amt = $fund['amount'];


									$sub2_total = $sub2_total + $collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt + $fund_amt;		
									
								}
							}else{
								$bank1 = mysql_fetch_array($bank1_res);
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
								$import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id=".$bank1['id']." and date <='".$date."'");
								$import = mysql_fetch_array($import_res);
								$import_amt = ($import['amount'] ==NULL) ? 0 : $import['amount'];

								//CASH EXPORTED
								$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id=".$bank1['id']." and date <='".$date."'");
								$export = mysql_fetch_array($export_res);
								$export_amt = ($export['amount'] ==NULL) ? 0 : $export['amount'];

								//CAPITAL FUNDS
								$fund_res = mysql_query("select sum(amount) as amount from other_funds where bank_account='".$bank1['id']."' and date <='".$date."'");
								$fund = mysql_fetch_array($fund_res);
								$fund_amt = $fund['amount'];


								$sub2_total = $collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt + $fund_amt;
							}
							$color = ($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td></td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
							$sub1_total = $sub1_total + $sub2_total;
							$i++;
						}
						$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td></td><td><b>Total ".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						$i++;
						$grand_total += $sub1_total;
					}
				}elseif($level3['account_no']=='122'){
					if($basis == 'Cash Basis')
						continue;
					$rec_res = mysql_query("select sum(amount) as amount from receivable where maturity_date <='".$date."'");
					$rec = mysql_fetch_array($rec_res);
					if($rec['amount'] != NULL){
						$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where c.date <= '".$date."'");
						$col = mysql_fetch_array($col_res);
						$grand_total =  $rec['amount'] - $col['amount'];
					}
				}elseif($level3['account_no'] == '123'){  //FIXED ASSETS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['[account_no']."%' and account_no >= 1231 and account_no <= 1239");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$depp1_total = 0;
						$fixed_res = mysql_query("select  sum(f.initial_value) as amount from fixed_asset f where f.account_id='".$level4['id']."' and f.id not in (select asset_id from sold_asset where date <= '".$date."') and f.date <='".$date."'");
						$fixed = mysql_fetch_array($fixed_res);
						if($fixed['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 123101 and account_no <= 123999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$depp2_total = 0;
								$fixed_res = mysql_query("select  sum(f.initial_value) as amount from fixed_asset f where f.account_id='".$level5['id']."' and f.id not in (select asset_id from sold_asset where date <= '".$date."') and f.date <='".$date."'");
								$fixed = mysql_fetch_array($fixed_res);
								if($fixed['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >=12310101 and account_no <= 12399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$fixed_res = mysql_query("select  sum(f.initial_value) as amount from fixed_asset f where f.account_id='".$level6['id']."' and f.id not in (select asset_id from sold_asset where date <= '".$date."') and f.date <='".$date."'");
										$fixed = mysql_fetch_array($fixed_res);
										$sub2_total = $sub2_total + $fixed['amount'];
										$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from  fixed_asset where account_id='".$level6['id']."' and date <= '".$date."') and date <='".$date."'");
										$depp = mysql_fetch_array($depp_res);
										$depp2_total = $depp2_total + $depp['amount'];
									}
								}else{
						
									$sub2_total = $fixed['amount'];
									$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from  fixed_asset where account_id='".$level5['id']."' and date <= '".$date."') and date <='".$date."'");
									$depp = mysql_fetch_array($depp_res);
									$depp2_total = $depp2_total + $depp['amount'];
								}
								$acc_res = mysql_query("select * from accounts where name='Accumulated Depreciation - ".$level5['name']."'");
								$acc = mysql_fetch_array($acc_res);
								$net_total = $sub2_total - $depp2_total;	
								$sub1_total += $sub2_total;
								$depp1_total += $depp2_total;
								
							}
						}else{
							$sub1_total = $fixed['amount'];
							$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from  fixed_asset where account_id='".$level4['id']."' and date <= '".$date."') and date <='".$date."'");
							$depp = mysql_fetch_array($depp_res);
							$depp1_total = $depp1_total + $depp['amount'];
						}
						$net_total = $sub1_total - $depp1_total;
						if($level4['name'] != 'Land'){
							$acc_res = mysql_query("select * from accounts where name='Accumulated Depreciation - ".$level4['name']."'");
							$acc = mysql_fetch_array($acc_res);
						}
						$grand_total = $grand_total + $net_total;
					}
				}
				$level2_total += $grand_total;
				if($level3['account_no'] == 111){
					$loss_res = mysql_query("select * from accounts where account_no='112'");
					$loss = mysql_fetch_array($loss_res);
					$level2_total -= $grand_loss;
					//$grand_loss = 0;
				}
			}			
			$assets += $level2_total;
		}
	
//LIABILITIES
		$capital = 0;
		$liabilities = 0;
		$content .= "<tr bgcolor=#cdcdcd><td colspan=3><font size=5pt><b>LIABILITIES</b></font></td></tr>";
		$level2_res = mysql_query("select * from accounts where account_no >=21 and account_no <= 39 order by account_no");
		while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
			if($level2['account_no'] ==31){
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td colspan=2><font size=5pt><b>TOTAL LIABILITIES</b></font></td><td><font size=5pt><b>".number_format($liabilities, 2)."</b></font></td></tr>";
				$i++;
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .="<tr bgcolor=$color><td colspan=3><font size=5pt><b>CAPITAL</b></font></td></tr>";
				$i++;
			}
			$color = ($i%2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color><td colspan=2><font size=4pt><b>".$level2['name']."</b></font></td><td></td></tr>";
			$level3_res = mysql_query("select * from accounts where account_no >=211 and account_no <= 399 and account_no like '".$level2['account_no']."%' order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				$grand_total = 0;
				if($level3['account_no'] == '211'){  //SAVINGS & DEPOSITS
					
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
							//OTHER DEDUCTIONS
							$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') and o.date <= '".$date."'"));
							$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;
							//MONTHLY ACCOUNT CHARGES
							$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts mem on c.memaccount_id=mem.id join savings_product p on mem.saveproduct_id=p.id where c.date <='".$date."' and p.id='".$prod['id']."' and p.type='free'");
							$charge = mysql_fetch_array($charge_res);
							//OFFSETTING FROM SAVINGS
							$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' and p.date<= '".$date."'");
							$pay = mysql_fetch_array($pay_res);
							$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;
							
							//SHARES OFFSET FROM SAVINGS
							$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') and s.date <= '".$date."'"));
							$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;

							$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
							$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
							$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
							
							$sub1_total = $dep_amt + $int_amt - $with_amt - $income_amt - $charge_amt- $pay_amt - $share_amt;  
						}else{
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 211101 and account_no <= 211999");
							while($level5 = mysql_fetch_array($level5_res)){
								if($level5['name'] == 'Compulsory Shares')
									continue;
								$sub2_total =0;
								$prod_res = mysql_query("select * from savings_product where account_id='".$level5['id']."'");
								if(mysql_numrows($prod_res) > 0){
									$prod = mysql_fetch_array($prod_res);
									//$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and d.date<= '".$date."'");
									$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and d.date<= '".$date."'");
									$dep = mysql_fetch_array($dep_res);
									$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.id='".$prod['id']."' and p.type='free' and w.date<= '".$date."'");
									$with = mysql_fetch_array($with_res);

									$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and i.date<= '".$date."'");
									$int = mysql_fetch_array($int_res);
									$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
									//OTHER DEDUCTIONS
									$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') and o.date <= '".$date."'"));
									$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;
									//MONTHLY ACCOUNT CHARGES
									$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts mem on c.memaccount_id=mem.id join savings_product p on mem.saveproduct_id=p.id where c.date <='".$date."' and p.id='".$prod['id']."' and p.type='free'");
									$charge = mysql_fetch_array($charge_res);
									//OFFSETTING FROM SAVINGS
									$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' and p.date<= '".$date."'");
									$pay = mysql_fetch_array($pay_res);
									$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;
									$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;

									//SHARES OFFSET FROM SAVINGS
									$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') and s.date <= '".$date."'"));
									$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;

									
									$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
									$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
									$sub2_total = $dep_amt + $int_amt - $with_amt - $charge_amt - $income_amt - $pay_amt - $share_amt; // + $int['amount'];
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
											$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and i.date<= '".$date."'");
											$int = mysql_fetch_array($int_res);
											$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
											//OTHER DEDUCTIONS
											$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') and o.date <= '".$date."'"));
											$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;
											//MONTHLY ACCOUNT CHARGES
											$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts mem on c.memaccount_id=mem.id join savings_product p on mem.saveproduct_id=p.id where c.date <='".$date."' and p.id='".$prod['id']."' and p.type='free'");
											$charge = mysql_fetch_array($charge_res);
											$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
											//OFFSETTING FROM SAVINGS
											$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' and p.date<= '".$date."'");
											$pay = mysql_fetch_array($pay_res);
											$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;

											//SHARES OFFSET FROM SAVINGS
											$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') and s.date <= '".$date."'"));
											$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;

			
											$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
											$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
											$sub2_total = $sub2_total + $dep_amt + $int_amt - $with_amt - $charge_amt - $income_amt - $share_amt; 
										}
									}
									$sub1_total = $sub1_total + $sub2_total;
								}
							}
						}
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
						//INTEREST GIVEN TO MEMBERS IS ALREADY PAID OUT AND IS NOT INTEREST PAYABLE
						*/
						;
					}elseif($level4['name'] == 'Interest Payable on Time Deposits'){
						/* 
						//INTEREST GIVEN TO MEMBERS IS ALREADY PAID OUT AND IS NOT INTEREST PAYABLE*/
					}elseif($level4['name'] == 'Dividends Payable'){
						$div_res = mysql_query("select sum(d.amount) as amount from dividends d join share_dividends s on d.share_dividend_id=s.id where s.bank_account=0 and s.date<= '".$date."'");
						$div = mysql_fetch_array($div_res);
						$dividends = intval($div['amount']);
						$sub1_total = $dividends;
					
					}else{
						
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
									$add = ($basis == 'Cash Basis') ? $paid['amount'] : $pay['amount'] - $paid['amount'];
									
									$sub2_total = $pay['amount'] - $paid['amount'];
								}
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
				}
			}elseif($level3['account_no'] >= '311' && $level3['account_no'] <= '399'){
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 3111 and account_no <= 3999");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['account_no'] == '3111'){
						$share_res = mysql_query("select sum(value) as value from shares where date <='".$date."'");
						$share = mysql_fetch_array($share_res);
						$level42_res = mysql_query("select * from accounts where name like 'Member Shares'");
						$level42 = mysql_fetch_array($level42_res);
						$sub1_total = $share['value'];
						$sub1_total = ($sub1_total > 0) ? $sub1_total : 0;
					}elseif($level4['account_no'] == '3312'){
						$sub1_total -= $grand_loss;
						$sub1_total += net_cummulated_income($this->datefrom, $this->dateto, $basis);
						$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td><b></b></td><td><b>Total ".$level4['name']."</b></td><td>".number_format($sub1_total, 2)."</td></tr>";
						$i++;
						//$resp->AddAlert(net_cummulated_income($date, $basis));
					}else{
						$invest_res = mysql_query("select sum(amount) as amount from other_funds where account_id='".$level4['id']."' and date <= '".$date."' and id not in (select fund_id from payable where date <= '".$date."' and fund_id<>'0')");
						$invest = mysql_fetch_array($invest_res);
						if($invest['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 321101 and account_no <= 339999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$invest_res = mysql_query("select sum(amount) as amount from other_funds where account_id='".$level5['id']."' and date <= '".$date."'  and id not in (select fund_id from payable where date <= '".$date."' and fund_id<>'0')");
								$invest = mysql_fetch_array($invest_res);
								if($invest['amount'] == NULL){
									$level6_res = mysql_query("select  * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 32110101 and account_no <= 33999999");
									while($level6 = mysql_fetch_array($level6_res)){
										$invest_res = mysql_query("select sum(amount) as amount from other_funds where account_id='".$level6['id']."' and date <= '".$date."'   and id not in (select fund_id from payable where date <= '".$date."' and fund_id<>'0')");
										$invest = mysql_fetch_array($invest_res);
										$sub2_total = $sub2_total + $invest['amount'];
									}
								}else
									$sub2_total = $invest['amount'];
							
								$sub1_total = $sub1_total + $sub2_total;
							}
						}else{
							$sub1_total = $invest['amount'];
						}
						
					}
					$grand_total += $sub1_total;
					$level2_total += $sub1_total; 
					$capital += $sub1_total;
				}
			}
			
		}
		
	}
	$net_assets = $assets - $capital - $liabilities;
	$net_assets = ($net_assets >= 0) ? number_format($net_assets, 2) : "(".number_format((0 - $net_assets), 2).")"; 
	return $net_assets;
}
function isBalanced(){
	$bal=$this->calcBalance2;
	if($bal>0||$bal<0)
		return 0;
	return 1;
}
#balance sheet handler
function closeBalanceSheet(){
		$assets = 0;	
$report_type='balance sheet';
$close_date=date('Y-m-d H:i:s');
$fiscal_id="";
if($this->isFiscalSaved($close_date,$report_type))
{$id_query=(mysql_fetch_array(mysql_query("select max(id) as id from fiscal")));
$fiscal_id=$id_query['id'];
}
		$level2_res= mysql_query("select * from accounts where account_no >10 and account_no <20 order by account_no");
		while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;	
			$level3_res = mysql_query("select * from accounts where account_no >=111 and account_no <= 199 and account_no like '".$level2['account_no']."%' and account_no not in (124, 112) order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				
				$grand_total = 0;
				if($level3['account_no'] == '111'){  //LOANS
					$grand_loss = 0;
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1111 and account_no <= 1119");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$loss_total=0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 111101 and account_no <= 111999");
						if( mysql_numrows($level5_res) > 0){
							
						}
						while($level5 = mysql_fetch_array($level5_res)){
							$loan1_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level5['id']."' ".$this->getRange("d.date"));
							$loan1 = mysql_fetch_array($loan1_res);
							
							if($loan1['amount'] == NULL){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 11110101 and account_no <= 11199999");
								if(mysql_numrows($level6_res) >0){
									
									$sub2_total = 0;
									$loss2_total = 0;
									while($level6 = mysql_fetch_array($level6_res)){
										$loan2_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level6['id']."' ".$this->getRange("d.date"));
										$loan2 = mysql_fetch_array($loan2_res);
										
										$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level6['id']."' ".$this->getRange("pay.date"));
										$pay = mysql_fetch_array($pay_res);
										$loan2_amt = ($loan2['amount'] == NULL) ? 0 : $loan2['amount'];
										
										$loss_res = mysql_query("select sum(w.amount) as amount from written_off w join disbursed d on w.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id ".$this->getRange("w.date","where")." and p.account_id='".$level6['id']."'");
										$loss = mysql_fetch_array($loss_res);
										$loss2_total = $loss2_total + $loss['amount'];
										$sub2_total = $sub2_total + $loan2_amt - $pay['amount'] - $loss['amount'];
									}
								} 
							}else{
								
								$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level5['id']."'  ".$this->getRange("pay.date"));

								$pay = mysql_fetch_array($pay_res);
								$loss_res = mysql_query("select sum(w.amount) as amount from written_off w join disbursed d on w.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id ".$this->getRange("w.date","where")." and p.account_id='".$level5['id']."'");
								$loss = mysql_fetch_array($loss_res);
								$loss2_total = $loss['amount'];
								$sub2_total = $loan1['amount'] - $pay['amount'] - $loss['amount'];
							}
							
						////
						#store10
						$this->storeValue($fiscal_id,$sub2_total,$level5['id'],$report_type);
						////
							$i++;
							
							$sub1_total = $sub1_total + $sub2_total;
							$loss_total = $loss_total + $loss2_total;
							$sub2_total = 0;
						}
						
						#store11
						$this->storeValue($fiscal_id,$sub2_total,$level4['id'],$report_type);
						////
						$grand_total = $grand_total + $sub1_total;
					}
					//LOAN LOSS ALLOWANCES
					$grand_loss=0;
					$prov_res = mysql_query("select * from provissions order by percent asc");
				
					while($prov = mysql_fetch_array($prov_res)){
						preg_match("/(\d*)[_](\d*)$/", $prov['range'], $arg);
						$interval = ($arg[1] <>180) ? ($arg[2] - $arg[1]) : 180;
						if($arg[2]==''){
							$arg[1] =180;
							$arg[2]=660;     //UP TO 22 MONTHS OF ARREARS, THE ASSUMPTION IS THAT BEYOND THAT, THE LOAN WILL HAVE BEEN WRITTEN OFF
						}
						
						$over_180 =0;
						$sched_res = mysql_query("select sum(princ_amt) as amount, loan_id as loan_id from schedule where date <= DATE_SUB('".$date."', INTERVAL ".$arg[1]." DAY) and date >= DATE_SUB('".$date."', INTERVAL ".$arg[2]." DAY) and loan_id not in (select loan_id from written_off where date <= DATE_SUB('".$date."', INTERVAL ".$arg[1]." DAY)) group by loan_id");
					
					
						while($sched = mysql_fetch_array($sched_res)){
							$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
							$all = mysql_fetch_array(mysql_query("select sum(princ_amt) as amount from schedule where date <= DATE_SUB('".$date."', INTERVAL ".$arg[2]." DAY)  and loan_id='".$sched['loan_id']."'"));
							$paid_res = mysql_query("select sum(princ_amt) as amount from payment ".$this->getRange("date","where")." and loan_id='".$sched['loan_id']."'");
							$paid = mysql_fetch_array($paid_res);
							$paid_amt = ($paid['amount'] == NULL) ? 0 : $paid['amount'];
							$arrears = $all['amount'] - $paid_amt;
							if($arrears < 0){
								$arrears =0;
							}
							
							$arrears = ($arrears > $sched_amt) ? $sched_amt : $arrears;
							$over_180  += $arrears;

						}	
						$loss180_over = floor($over_180 * $prov['percent']/100);
						$grand_loss += $loss180_over;
					}
					//MANUAL LOAN LOSS PROVISSION
					$static = mysql_fetch_array(mysql_query("select sum(amount) as amount from otherloan_loss ".$this->getRange("date","where")));
					$static_amt = ($static['amount'] == NULL) ? 0 : $static['amount'];
					$grand_loss = $grand_loss + $static_amt;
					$grand_net = $grand_total - $grand_loss;  //SUBTRACT LOAN LOSS PROVISIONS


					//$grand_loss = $loss1_30 + $loss31_60 + $loss61_90 + $loss91_120 + $loss121_180 + $loss180_over;
				}elseif($level3['account_no'] >= 113 && $level3['account_no'] <= 119){   //INVESTMENTS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1131 and account_no <= 1199");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level4['id']."' ".$this->getRange("date"));
						$invest = mysql_fetch_array($invest_res);
						if($invest['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 113101 and account_no <= 119999");
							if( mysql_numrows($level5_res) > 0){
								
							while($level5 = mysql_fetch_array($level5_res)){
								$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level5['id']."' ".$this->getRange("date"));
								$invest = mysql_fetch_array($invest_res);
								$sub2_total=0;
								if($invest['amount'] ==NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 11310101 and account_no <= 11399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level6['id']."' ".$this->getRange("date"));		
										$invest = mysql_fetch_array($invest_res);
										
										$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level6['id']."' ".$this->getRange("date"));
										$sold = mysql_fetch_array($sold_res);
										$sold_amt = ($sold['quantity'] == NULL) ? 0 : $sold['amount'];
										$sub2_total = $sub2_total + $invest['amount'] - $sold_amt;
									}
								}else{
									
									$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level5['id']."' ".$this->getRange("date"));
									$sold = mysql_fetch_array($sold_res);
									$sold_amt = ($sold['quantity'] == NULL) ? 0 : $sold['amount'];
									$sub2_total = $invest['amount'] - $sold_amt;
									//$sub2_total = $invest['amount'];
								}
								$sub1_total = $sub1_total + $sub2_total;
							}
						}else{
							
							$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level4['id']."' ".$this->getRange("date"));
							$sold = mysql_fetch_array($sold_res);
							$sold_amt = ($sold['amount'] == NULL) ? 0 : $sold['amount'];
							$sub1_total = $invest['amount'] - $sold_amt;
						}
						
						////
						#store20
						$this->storeValue($fiscal_id,$sub2_total,$level4['id'],$report_type);
						////
						$grand_total = $grand_total + $sub1_total;
					}
				}

				//elseif($level3['account_no'] >= 125){   //INVESTMENTS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1251 and account_no <= 1259");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 125101 and account_no <= 125999");
						if( mysql_numrows($level5_res) > 0){
							
						}
						while($level5 = mysql_fetch_array($level5_res)){
							$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level5['id']."' ".$this->getRange("date"));
							$invest = mysql_fetch_array($invest_res);
							$sub2_total=0;
							if($invest['amount'] == NULL){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 12510101 and account_no <= 11399999");
								while($level6 = mysql_fetch_array($level6_res)){
									$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level6['id']."' ".$this->getRange("date")." and id not in (select investment_id from sold_invest ".$this->getRange("date","where").")");		
									$invest = mysql_fetch_array($invest_res);

									$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level5['id']."' ".$this->getRange("date")." order by date desc limit 1"));
									$sold_res = mysql_query("select sum(quantity) as quantity from sold_invest where account_id='".$level5['id']."' ".$this->getRange("date"));
									$sold = mysql_fetch_array($sold_res);
									$sold_amt = ($sold['quantity'] == NULL) ? 0 : ($sold['quantity'] * $unit['amount']);
									$sub2_total =$sub2_total + $invest['amount'] - $sold_amt;		

									$sub2_total = $sub2_total + $invest['amount'];
								}
							}else{
								$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level5['id']."' ".$this->getRange("date")." order by date desc limit 1"));
								$sold_res = mysql_query("select sum(quantity) as quantity from sold_invest where account_id='".$level5['id']."' ".$this->getRange("date"));
								$sold = mysql_fetch_array($sold_res);
								$sold_amt = ($sold['quantity'] == NULL) ? 0 : ($sold['quantity'] * $unit['amount']);
								$sub2_total = $invest['amount'] - $sold_amt;		
							}
							$sub1_total = $sub1_total + $sub2_total;
						}
						////
						#store21
						$this->storeValue($fiscal_id,$sub2_total,$level4['id'],$report_type);
						$grand_total = $grand_total + $sub1_total;
					}
				}elseif($level3['account_no'] == 121){
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1211 and account_no <= 1219");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 121101 and account_no <= 121999");
						if( mysql_numrows($level5_res) > 0){
							
						}
						while($level5 = mysql_fetch_array($level5_res)){
							$bank1_res = mysql_query("select * from bank_account where account_id='".$level5['id']."'");
							$sub2_total = 0;
							if(mysql_numrows($bank1_res) == 0){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 12110101 and account_no <= 12199999");
								while($level6 = mysql_fetch_array($level6_res)){
									$bank2_res = mysql_query("select * from bank_account where account_id='".$level6['id']."'");
									$bank2 = mysql_fetch_array($bank2_res);
									//DEPOSITS
									$dep_res = mysql_query("select sum(amount) as amount from deposit where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$dep = mysql_fetch_array($dep_res);
									//WITHDRAWALS
									$with_res = mysql_query("select sum(amount) as amount from withdrawal where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$with = mysql_fetch_array($with_res);
									//OTHER INCOME
									$other_res = mysql_query("select sum(amount) as amount from other_income where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$other = mysql_fetch_array($other_res);
									//LOANS PAYABLE
									$loans_payable = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '212%' and p.bank_account='".$bank2['id']."' ".$this->getRange("p.date"));
									$loans_payable = mysql_fetch_array($loans_payable);
									$loans_payable = ($loans_payable['amount'] != NULL) ? $loans_payable['amount'] : 0;
									//EXPENSES
									$expense_res = mysql_query("select sum(amount) as amount from expense where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$expense = mysql_fetch_array($expense_res);
									//PAYABLE PAID
									$payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where bank_account='".$bank2['id']."' ".$this->getRange("date"));	
									$payable_paid = mysql_fetch_array($payable_paid_res);
									//RECEIVABLE COLLECTED
									$collected_res = mysql_query("select sum(amount) as amount from collected where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$collected = mysql_fetch_array($collected_res);
									//DISBURSED LOANS
									$disb_res = mysql_query("select sum(amount) as amount from disbursed where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$disb = mysql_fetch_array($disb_res);
									//PAYMENTS
									$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id  ".$this->getRange("p.date")." and p.bank_account='".$bank2['id']."'");
									$pay = mysql_fetch_array($pay_res);
									//PENALTIES
									$pen_res = mysql_query("select sum(p.amount) as amount from penalty p join disbursed d on p.loan_id=d.id where p.bank_account='".$bank2['id']."' and p.status='paid' ".$this->getRange("p.date"));
									$pen = mysql_fetch_array($pen_res);
								
									//SHARES
									$shares_res = mysql_query("select sum(value) as amount from shares ".$this->getRange("date","where")." and bank_account='".$bank2['id']."'");
									$shares = mysql_fetch_array($shares_res); 
									//RECOVERED
									$rec_res = mysql_query("select sum(r.amount) as amount from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.bank_account='".$bank2['id']."' ".$this->getRange("r.date"));
									$rec = mysql_fetch_array($rec_res);			
									//INVESTMENTS 
									$invest_res = mysql_query("select sum(quantity * amount) as amount from investments ".$this->getRange("date","where")." and bank_account='".$bank2['id']."'");
									$invest = mysql_fetch_array($invest_res);
									$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
									//DIVIDENDS PAID
									$div_res = mysql_query("select sum(total_amount) as total_amount from share_dividends ".$this->getRange("date","where")." and bank_account='".$bank1['id']."'");
									$div = mysql_fetch_array($div_res);
									$div_amt = ($div['amount'] != NULL) ? $div['amount'] : 0;
									
									//SOLD INVESTMENTS
									$soldinvest_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where bank_account ='".$bank2['id']."' ".$this->getRange("date"));
									$soldinvest = mysql_fetch_array($soldinvest_res);

									//FIXED ASSETS 
									$fixed_res = mysql_query("select sum(initial_value) as amount from fixed_asset where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$fixed = mysql_fetch_array($fixed_res);
									$soldasset_res = mysql_query("select sum(amount) as amount from sold_asset where bank_account ='".$bank2['id']."' ".$this->getRange("date"));
									$soldasset = mysql_fetch_array($soldasset_res);
									
									//CASH IMPORTED
									$import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id='".$bank2['id']."' ".$this->getRange("date"));
									$import = mysql_fetch_array($import_res);
									$import_amt = ($import['amount'] ==NULL) ? 0 : $import['amount'];
								
									//CASH EXPORTED
									$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id='".$bank2['id']."' ".$this->getRange("date"));
									$export = mysql_fetch_array($export_res);
									$export_amt = ($export['amount'] ==NULL) ? 0 : $export['amount'];

									//CAPITAL FUNDS
									$fund_res = mysql_query("select sum(amount) as amount from other_funds where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$fund = mysql_fetch_array($fund_res);
									$fund_amt = $fund['amount'];


									$sub2_total = $sub2_total + $collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt + $fund_amt;		
									
								}
							}else{
								$bank1 = mysql_fetch_array($bank1_res);
								//DEPOSITS
								$dep_res = mysql_query("select sum(amount) as amount from deposit where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$dep = mysql_fetch_array($dep_res);
								$dep_amt = ($dep['amount'] == NULL) ? 0 : $dep['amount'];
								//WITHDRAWALS
								$with_res = mysql_query("select sum(amount) as amount from withdrawal where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$with = mysql_fetch_array($with_res);
								$with_amt = ($with['amount'] == NULL) ? 0 : $with['amount'];
								//OTHER INCOME
								$other_res = mysql_query("select sum(amount) as amount from other_income where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$other = mysql_fetch_array($other_res);
								$other_amt = ($other['amount'] == NULL) ? 0 : $other['amount'];
								//EXPENSES
								$expense_res = mysql_query("select sum(amount) as amount from expense where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$expense = mysql_fetch_array($expense_res);
								$expense_amt = ($expense['amount'] == NULL) ? 0 : $expense['amount'];
								//LOANS PAYABLE
								$loans_payable = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '212%' and p.bank_account='".$bank1['id']."' ".$this->getRange("p.date"));
								$loans_payable = mysql_fetch_array($loans_payable);
								$loans_payable = ($loans_payable['amount'] != NULL) ? $loans_payable['amount'] : 0;
								//PAYABLE PAID
								$payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$payable_paid = mysql_fetch_array($payable_paid_res);
								$payable_paid_amt = ($payable_paid['amount'] == NULL) ? 0 : $payable_paid['amount'];
								//RECEIVALE COLLECTED
								$collected_res = mysql_query("select sum(amount) as amount from collected where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$collected = mysql_fetch_array($collected_res);
								$collected_amt = ($collected['amount'] == NULL) ? 0 : $collected['amount'];
								//DISBURSED LOANS
								$disb_res = mysql_query("select sum(amount) as amount from disbursed where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$disb = mysql_fetch_array($disb_res);
								$disb_amt = ($disb['amount'] == NULL) ? 0 : $disb['amount'];
								//PAYMENTS
								$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id  ".$this->getRange("p.date","where")." and p.bank_account='".$bank1['id']."'");
								$pay = mysql_fetch_array($pay_res);
								$pay_amt = ($pay['amount'] == NULL) ? 0 : $pay['amount'];
								//PENALTIES
								$pen_res = mysql_query("select sum(p.amount) as amount from penalty p join disbursed d on p.loan_id=d.id where p.bank_account='".$bank1['id']."' and p.status='paid' ".$this->getRange("p.date"));
								$pen = mysql_fetch_array($pen_res);
								$pen_amt = ($pen['amount']==NULL) ? 0 : $pen['amount'];
								
								//SHARES
								$shares_res = mysql_query("select sum(value) as amount from shares ".$this->getRange("date","where")." and bank_account='".$bank1['id']."'");
								$shares = mysql_fetch_array($shares_res); 
								$shares_amt = ($shares['amount'] == NULL) ? 0 : $shares['amount'];
								//RECOVERED
								$rec_res = mysql_query("select sum(r.amount) as amount from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.bank_account='".$bank1['id']."' ".$this->getRange("r.date"));
								$rec = mysql_fetch_array($rec_res);	
								$rec_amt = ($rec['amount'] == NULL) ? 0 : $rec['amount']; 
								//INVESTMENTS 
								$invest_res = mysql_query("select sum(quantity * amount) as amount from investments ".$this->getRange("date","where")." and bank_account='".$bank1['id']."'");
								$invest = mysql_fetch_array($invest_res);
								$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
								//DIVIDENDS PAID
								$div_res = mysql_query("select sum(total_amount) as total_amount from share_dividends ".$this->getRange("date","where")." and bank_account='".$bank1['id']."'");
								$div = mysql_fetch_array($div_res);
								$div_amt = ($div['amount'] != NULL) ? $div['amount'] : 0;

								
								$soldinvest_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where bank_account ='".$bank1['id']."' ".$this->getRange("date"));
								$soldinvest = mysql_fetch_array($soldinvest_res);

								//FIXED ASSETS 
								$fixed_res = mysql_query("select sum(initial_value) as amount from fixed_asset where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$fixed = mysql_fetch_array($fixed_res);
								$soldasset_res = mysql_query("select sum(amount) as amount from sold_asset where bank_account ='".$bank1['id']."' ".$this->getRange("date"));
								$soldasset = mysql_fetch_array($soldasset_res);
									
								//CASH IMPORTED
								$import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id=".$bank1['id']." ".$this->getRange("date"));
								$import = mysql_fetch_array($import_res);
								$import_amt = ($import['amount'] ==NULL) ? 0 : $import['amount'];

								//CASH EXPORTED
								$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id=".$bank1['id']." ".$this->getRange("date"));
								$export = mysql_fetch_array($export_res);
								$export_amt = ($export['amount'] ==NULL) ? 0 : $export['amount'];

								//CAPITAL FUNDS
								$fund_res = mysql_query("select sum(amount) as amount from other_funds where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$fund = mysql_fetch_array($fund_res);
								$fund_amt = $fund['amount'];


								$sub2_total = $collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt + $fund_amt;
							}
							
						#store30
							$this->storeValue($fiscal_id,$sub2_total,$level5['id'],$report_type);
							$sub1_total = $sub1_total + $sub2_total;
						}
						////
						#store31
						$this->storeValue($fiscal_id,$sub1_total,$level4['id'],$report_type);
						$grand_total += $sub1_total;
					}
				}elseif($level3['account_no']=='122'){
					if($basis == 'Cash Basis')
						continue;
					$rec_res = mysql_query("select sum(amount) as amount from receivable ".$this->getRange("maturity_date","where"));
					$rec = mysql_fetch_array($rec_res);
					if($rec['amount'] != NULL){
						$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id ".$this->getRange("c.date","where"));
						$col = mysql_fetch_array($col_res);
						$grand_total =  $rec['amount'] - $col['amount'];
					}
				}elseif($level3['account_no'] == '123'){  //FIXED ASSETS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['[account_no']."%' and account_no >= 1231 and account_no <= 1239");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$depp1_total = 0;
						
						$fixed_res = mysql_query("select  sum(f.initial_value) as amount from fixed_asset f where f.account_id='".$level4['id']."' and f.id not in (select asset_id from sold_asset ".$this->getRange("date","where").") ".$this->getRange("f.date"));
						$fixed = mysql_fetch_array($fixed_res);
						if($fixed['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 123101 and account_no <= 123999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$depp2_total = 0;
								
								$fixed_res = mysql_query("select  sum(f.initial_value) as amount from fixed_asset f where f.account_id='".$level5['id']."' and f.id not in (select asset_id from sold_asset ".$this->getRange("date","where").") ".$this->getRange("f.date"));
								$fixed = mysql_fetch_array($fixed_res);
								if($fixed['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >=12310101 and account_no <= 12399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$fixed_res = mysql_query("select  sum(f.initial_value) as amount from fixed_asset f where f.account_id='".$level6['id']."' and f.id not in (select asset_id from sold_asset ".$this->getRange("date","where").") ".$this->getRange("f.date"));
										$fixed = mysql_fetch_array($fixed_res);
										$sub2_total = $sub2_total + $fixed['amount'];
										$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from  fixed_asset where account_id='".$level6['id']."' ".$this->getRange("date").") ".$this->getRange("date"));
										$depp = mysql_fetch_array($depp_res);
										$depp2_total = $depp2_total + $depp['amount'];
									}
								}else{
						
									$sub2_total = $fixed['amount'];
									$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from  fixed_asset where account_id='".$level5['id']."' ".$this->getRange("date").") ".$this->getRange("date"));
									$depp = mysql_fetch_array($depp_res);
									$depp2_total = $depp2_total + $depp['amount'];
								}
								////
						#store40
						$this->storeValue($fiscal_id,$sub2_total,$level5['id'],$report_type);
								$acc_res = mysql_query("select * from accounts where name='Accumulated Depreciation - ".$level5['name']."'");
								$acc = mysql_fetch_array($acc_res);
								////
						#store41
						$this->storeValue($fiscal_id,$depp2_total,$acc['id'],$report_type);
								$net_total = $sub2_total - $depp2_total;	
								$sub1_total += $sub2_total;
								$depp1_total += $depp2_total;
								
							}
						}else{
							$sub1_total = $fixed['amount'];
							$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from  fixed_asset where account_id='".$level4['id']."' ".$this->getRange("date").") ".$this->getRange("date"));
							$depp = mysql_fetch_array($depp_res);
							$depp1_total = $depp1_total + $depp['amount'];
						}
						
						////
						#store42
						$this->storeValue($fiscal_id,$sub1_total,$level4['id'],$report_type);
						/********/
						$net_total = $sub1_total - $depp1_total;
						if($level4['name'] != 'Land'){
							$acc_res = mysql_query("select * from accounts where name='Accumulated Depreciation - ".$level4['name']."'");
							$acc = mysql_fetch_array($acc_res);
							
						#store
						$this->storeValue($fiscal_id,$depp1_total,$acc['id'],$report_type);
						/********/
						}
						$grand_total = $grand_total + $net_total;
					}
				}
				#level 3 total
			}			
			
		}
		
		
//LIABILITIES
		$capital = 0;
		$liabilities = 0;
		$level2_res = mysql_query("select * from accounts where account_no >=21 and account_no <= 39 order by account_no");
		while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
			$level3_res = mysql_query("select * from accounts where account_no >=211 and account_no <= 399 and account_no like '".$level2['account_no']."%' order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				$grand_total = 0;
				if($level3['account_no'] == '211'){  //SAVINGS & DEPOSITS
					
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=2111 and account_no <= 2119");
					while($level4 = mysql_fetch_array($level4_res)){
						if($level4['name'] == 'Compulsory Savings')
							continue;
						$sub1_total = 0;
						$prod_res = mysql_query("select * from savings_product where account_id='".$level4['id']."'");
						if(mysql_numrows($prod_res) > 0){
							$prod = mysql_fetch_array($prod_res);
							$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("d.date"));
							$dep = mysql_fetch_array($dep_res);
							$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("w.date"));
							$with = mysql_fetch_array($with_res);
							$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("i.date"));
							$int = mysql_fetch_array($int_res);
							$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
							//OTHER DEDUCTIONS
							$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') ".$this->getRange("o.date")));
							$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;
							//MONTHLY ACCOUNT CHARGES
							$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts mem on c.memaccount_id=mem.id join savings_product p on mem.saveproduct_id=p.id ".$this->getRange("c.date","where")." and p.id='".$prod['id']."' and p.type='free'");
							$charge = mysql_fetch_array($charge_res);
							//OFFSETTING FROM SAVINGS
							$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' ".$this->getRange("p.date"));
							$pay = mysql_fetch_array($pay_res);
							$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;
							
							//SHARES OFFSET FROM SAVINGS
							$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') ".$this->getRange("s.date")));
							$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;

							$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
							$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
							$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
							
							$sub1_total = $dep_amt + $int_amt - $with_amt - $income_amt - $charge_amt- $pay_amt - $share_amt;  
						}else{
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 211101 and account_no <= 211999");
							while($level5 = mysql_fetch_array($level5_res)){
								if($level5['name'] == 'Compulsory Shares')
									continue;
								$sub2_total =0;
								$prod_res = mysql_query("select * from savings_product where account_id='".$level5['id']."'");
								if(mysql_numrows($prod_res) > 0){
									$prod = mysql_fetch_array($prod_res);
									
									$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("d.date"));
									$dep = mysql_fetch_array($dep_res);
									$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("w.date"));
									$with = mysql_fetch_array($with_res);

									$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("i.date"));
									$int = mysql_fetch_array($int_res);
									$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
									//OTHER DEDUCTIONS
									$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') ".$this->getRange("o.date")));
									$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;
									//MONTHLY ACCOUNT CHARGES
									$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts mem on c.memaccount_id=mem.id join savings_product p on mem.saveproduct_id=p.id ".$this->getRange("c.date","where")." and p.id='".$prod['id']."' and p.type='free'");
									$charge = mysql_fetch_array($charge_res);
									//OFFSETTING FROM SAVINGS
									$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' ".$this->getRange("p.date"));
									$pay = mysql_fetch_array($pay_res);
									$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;
									$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;

									//SHARES OFFSET FROM SAVINGS
									$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') ".$this->getRange("s.date")));
									$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;

									
									$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
									$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
									$sub2_total = $dep_amt + $int_amt - $with_amt - $charge_amt - $income_amt - $pay_amt - $share_amt; // + $int['amount'];
									$sub1_total = $sub1_total + $sub2_total;
								}else{
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 21110101 and account_no <= 21199999");
									while($level6 = mysql_fetch_array($level6_res)){
										$prod_res = mysql_query("select * from savings_product where account_id='".$level6['id']."'");
										if(mysql_numrows($prod_res) > 0){
											$prod = mysql_fetch_array($prod_res);
											$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("d.date"));
											$dep = mysql_fetch_array($dep_res);
											$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("w.date"));
											$with = mysql_fetch_array($with_res);
											$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("i.date"));
											$int = mysql_fetch_array($int_res);
											$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
											//OTHER DEDUCTIONS
											$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') ".$this->getRange("o.date")));
											$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;
											//MONTHLY ACCOUNT CHARGES
											$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts mem on c.memaccount_id=mem.id join savings_product p on mem.saveproduct_id=p.id ".$this->getRange("c.date","where")." and p.id='".$prod['id']."' and p.type='free'");
											$charge = mysql_fetch_array($charge_res);
											$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
											//OFFSETTING FROM SAVINGS
											$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' ".$this->getRange("p.date"));
											$pay = mysql_fetch_array($pay_res);
											$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;

											//SHARES OFFSET FROM SAVINGS
											$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') ".$this->getRange("s.date")));
											$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;
											$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
											$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
											$sub2_total = $sub2_total + $dep_amt + $int_amt - $with_amt - $charge_amt - $income_amt - $share_amt;  // + $int['amount'];
										}
									}
									$sub1_total = $sub1_total + $sub2_total;
								}
						#store
						$this->storeValue($fiscal_id,$sub2_total,$level5['id'],$report_type);
						/********/
							}
						}
						#store
						$this->storeValue($fiscal_id,$sub1_total,$level4['id'],$report_type);
						/********/
						
				}
				$level2_total += $grand_total;
				$liabilities += $grand_total;
			}elseif($level3['account_no'] >='212' && $level3['account_no'] <= 299){
				$grand_total=0;
				
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=2121 and account_no <= 2999");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					
					if($level4['name'] == 'Interest Payable on Savings Deposits'){
					}
					elseif($level4['name'] == 'Interest Payable on Time Deposits'){
						
					}elseif($level4['name'] == 'Dividends Payable'){
						$div_res = mysql_query("select sum(d.amount) as amount from dividends d join share_dividends s on d.share_dividend_id=s.id where s.bank_account=0 ".$this->getRange("s.date"));
						$div = mysql_fetch_array($div_res);
						$dividends = intval($div['amount']);
						$sub1_total = $dividends;
					}else{
						
						$pay_res = mysql_query("select sum(amount) as amount from payable where account_id='".$level4['id']."' ".$this->getRange("date"));
						$pay = mysql_fetch_array($pay_res);
						
						if($pay['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 212101 and account_no <= 213999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$pay_res = mysql_query("select sum(amount) as amount from payable where account_id='".$level5['id']."' ".$this->getRange("date"));
								$pay = mysql_fetch_array($pay_res);
								if($pay['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >=21210101 and account_no <= 21399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$pay_res = mysql_query("select sum(amount) as amount from payable where account_id='".$level6['id']."' ".$this->getRange("date"));
										$pay = mysql_fetch_array($pay_res);
										$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id ".$this->getRange("paid.date","where")." and p.account_id='".$level4['id']."'");
										$paid = mysql_fetch_array($paid_res);
										//$add = ($basis == 'Cash Basis') ? $paid['amount'] : $pay['amount'] - $paid['amount'];
										//$sub2_total += $add;
										$sub2_total = $sub2_total + $pay['amount'] - $paid['amount'];
									}
								}else{
									$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id ".$this->getRange("paid.date","where")." and p.account_id='".$level5['id']."'");
									$paid = mysql_fetch_array($paid_res);
									$add = ($basis == 'Cash Basis') ? $paid['amount'] : $pay['amount'] - $paid['amount'];
									
									$sub2_total = $pay['amount'] - $paid['amount'];
								}
								
								#store
								$this->storeValue($fiscal_id,$sub2_total,$level5['id'],$report_type);
								/********/
							$sub1_total += $sub2_total;
							}
						}else{
							$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id ".$this->getRange("paid.date","where")." and p.account_id='".$level4['id']."'");
							$paid = mysql_fetch_array($paid_res);
							//$add = ($basis == 'Cash Basis') ? $paid['amount'] : $pay['amount'] - //$paid['amount'];
							//$sub2_total =$add;
							$sub1_total = $pay['amount'] - $paid['amount'];
						}
					}
					$grand_total = $grand_total + $sub1_total;
					$level2_total += $sub1_total;
					$liabilities += $sub1_total;
					
					#store
					$this->storeValue($fiscal_id,$sub1_total,$level4['id'],$report_type);
					/********/
					$i++;
				}
			}elseif($level3['account_no'] >= '311' && $level3['account_no'] <= '399'){
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 3111 and account_no <= 3999");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['account_no'] == '3111'){
						$share_res = mysql_query("select sum(value) as value from shares ".$this->getRange("date","where"));
						$share = mysql_fetch_array($share_res);
						$level42_res = mysql_query("select * from accounts where name like 'Member Shares'");
						$level42 = mysql_fetch_array($level42_res);
						$sub1_total = $share['value'];
						$sub1_total = ($sub1_total > 0) ? $sub1_total : 0;
						
					}elseif($level4['account_no'] == '3312'){
						$sub1_total -= $grand_loss;
						$sub1_total += net_cummulated_income('', $date, $basis);
						
					}else{
						$invest_res = mysql_query("select sum(amount) as amount from other_funds where account_id='".$level4['id']."' ".$this->getRange("date")." and id not in (select fund_id from payable ".$this->getRange("date","where")." and fund_id<>'0')");
						$invest = mysql_fetch_array($invest_res);
						if($invest['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 321101 and account_no <= 339999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$invest_res = mysql_query("select sum(amount) as amount from other_funds where account_id='".$level5['id']."' ".$this->getRange("date")."  and id not in (select fund_id from payable ".$this->getRange("date","where")." and fund_id<>'0')");
								$invest = mysql_fetch_array($invest_res);
								if($invest['amount'] == NULL){
									$level6_res = mysql_query("select  * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 32110101 and account_no <= 33999999");
									while($level6 = mysql_fetch_array($level6_res)){
										$invest_res = mysql_query("select sum(amount) as amount from other_funds where account_id='".$level6['id']."' ".$this->getRange("date","where")."   and id not in (select fund_id from payable ".$this->getRange("date","where")." and fund_id<>'0')");
										$invest = mysql_fetch_array($invest_res);
										$sub2_total = $sub2_total + $invest['amount'];
									}
								}else
									$sub2_total = $invest['amount'];
								
								$sub1_total = $sub1_total + $sub2_total;
							}
						}else{
							$sub1_total = $invest['amount'];
						}
						
					}
					
					$grand_total += $sub1_total;
					$level2_total += $sub1_total; 
					$capital += $sub1_total;
				}
			}
			
		}
		
	}
	#grand total
}

#income statement
function closeIncomeStatement(){
$from_date=$this->datefrom;
$to_date=$this->dateto;
$report_type='income statement';
$close_date=date('Y-m-d H:i:s');
$fiscal_id="";
if($this->isFiscalSaved($close_date,$report_type))
{
	$id_query=(mysql_fetch_array(mysql_query("select max(id) as id from fiscal")));
	$fiscal_id=$id_query['id'];
}
	//INCOME FOR INCOME STATEMENT
		$level2_res= mysql_query("select * from accounts where account_no >40 and account_no <=49 ".$branch_." order by account_no");
		$income = 0;
		while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
			
			$level3_res = mysql_query("select * from accounts where account_no >=411 and account_no <= 599 and account_no like '".$level2['account_no']."%' order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
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
						$color=($i%2 == 0) ? lightgrey : "white";
						#store
						$this->storeValue($fiscal_id,$sub1_total,$level4['id'],$report_type);
						/*********/
					}
				}elseif($level3['account_no'] >= 412 && $level3['account_no'] <= 413){
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4121 and account_no <= 4199 and account_no not like '%2'");
					$pre = ($this->basis == 'Cash Basis') ? "(Received)" : "";
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
								
						#store
						$this->storeValue($fiscal_id,$sub2_total,$level5['id'],$report_type);
						/*********/
							
								$sub1_total += $sub2_total;
							}
						}else{
							$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where r.account_id='".$level6['id']."' and r.maturity_date <= '".$to_date."' and c.date <= '".$to_date."' and c.date >= '".$from_date."'");
							$col = mysql_fetch_array($col_res);
							
							$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
							$top_up = ($basis == 'Accrual Basis') ? $rec['amount'] : $col_amt;
							
							$sub1_total += $top_up;
						}
						#store
						$this->storeValue($fiscal_id,$sub1_total,$level4['id'],$report_type);
						/*********/
						
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
						#store
						$this->storeValue($fiscal_id,$sub2_total,$level5['id'],$report_type);
						/*********/
								$sub1_total += $sub2_total;
							}
						}else{
							$sub1_total = $inc['amount'];
					}
					
					#store
					$this->storeValue($fiscal_id,$sub1_total,$level4['id'],$report_type);
					/*********/
					
				}
			}elseif($level3['account_no'] == '424'){
				$inv_res = mysql_query("select sum(quantity * purchase_price) as tot_cost, sum(quantity * amount) as tot_gain from sold_invest where date >='".$from_date."' and date <= '".$to_date."'");
				$inv = mysql_fetch_array($inv_res);
				if($inv['tot_cost'] == NULL){
					$grand_total =0;
				}else{
					$grand_total = $inv['tot_gain'] - $inv['tot_cost'];
				}
			}elseif($level3['account_no'] >= 414){
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4141 and account_no <= 4999");
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
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 414101 and account_no <= 499999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level5['id']."' and o.date >= '".$from_date."' and o.date <= '".$to_date."'");
								$inc = mysql_fetch_array($inc_res);
								if($inc['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 41410101 and account_no <= 49999999");
									while($level6 = mysql_fetch_array($level6_res)){
										$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level6['id']."' and o.date >= '".$from_date."' and o.date <= '".$to_date."'");
										$inc = mysql_fetch_array($inc_res);
										$sub2_total += $inc['amount'];
									}
								}else{
									
									$sub2_total = $inc['amount'];
								}
								
						#store
						$this->storeValue($fiscal_id,$sub2_total,$level5['id'],$report_type);
						/*********/
								$sub1_total += $sub2_total;
								
							}
						}else{
							
							$sub1_total += $inc['amount'];
						}
						
					}
					
					#store
					$this->storeValue($fiscal_id,$sub1_total,$level4['id'],$report_type);
					/*********/
					
				}
			}
			#total level 3
		}

		#total level 2
	}
	#total

// EXPENSES
	
	$level2_res = mysql_query("select * from accounts where account_no >50 and account_no <60 order by account_no");
	while($level2 = mysql_fetch_array($level2_res)){
		$level2_total = 0;
		$level3_res = mysql_query("select * from accounts where account_no like '".$level2['account_no']."%' and account_no >= 511 and account_no <= 599");
		while($level3 = mysql_fetch_array($level3_res)){
			$int_accounts ='0';
			if($level3['account_no'] == 511){
				$prod_res = mysql_query("select s.id as id, a.name as name, a.account_no as account_no, a.id as account_id from savings_product s  join accounts a on s.account_id=a.id where s.type='free' order by a.account_no");
				while($prod = mysql_fetch_array($prod_res)){
					$int_res = mysql_query("select sum(amount) as amount from save_interest  i join mem_accounts m on i.memaccount_id=m.id where m.saveproduct_id='".$prod['id']."' and i.date <='".$to_date."' and i.date >='".$from_date."'");
					$int = mysql_fetch_array($int_res);
					$sub1_total = ($int['amount'] != NULL) ? $int['amount'] : 0;
					
					$level4_res = mysql_query("select * from accounts where name like '%Expense%on %".$prod['name']."'");
					$level4 = mysql_fetch_array($level4_res);
					$exp = mysql_fetch_array(mysql_query("select sum(amount) as amount from expense where account_id='".$level4['id']."' and date>='".$from_date."' and date <= '".$to_date."'"));
					$sub1_total = ($exp['amount'] != NULL) ? $sub1_total + $exp['amount'] : $sub1_total;
					
					#store
					$this->storeValue($fiscal_id,$sub1_total,$level4['id'],$report_type);
					/*********/
					$int_accounts .= ", '".$level4['id']."'";
				}
			}   //else{   //TO INCLUDE OTHER EXPENSES ON SAVINGS 	
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 5111 and account_no <= 5999 and id not in (".$int_accounts.") and name not like '%Interest%Expense%on%'");

				
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
									#store
						$this->storeValue($fiscal_id,$sub2_total,$level4['id'],$report_type);
						/*********/
									$sub1_total += $sub2_total;
									$x++;
								}
					}else{
						$exp_res = mysql_query("select sum(amount) as amount from expense where account_id='".$level4['id']."' and date>= '".$from_date."' and date <='".$to_date."'");
						$exp = mysql_fetch_array($exp_res);
						if($exp['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= '511101' and account_no <= '599999'");
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
								#store
						$this->storeValue($fiscal_id,$sub2_total,$level5['id'],$report_type);
						/*********/
								$sub1_total += $sub2_total;
								
							}
							
						}else{
							//if($level4['account_no'] == '5521')
							//		$resp->AddAlert("Hi");
							$sub1_total = ($exp['amount'] != NULL) ? $exp['amount'] : 0;
							//$sub1_total = $exp['amount'];
						}
					}
					
						#store
						$this->storeValue($fiscal_id,$sub1_total,$level4['id'],$report_type);
						/*********/
					
				}
			//}   //ELSE FOR SAVINGS EXPENSES
			
			if($level3['account_no'] == '561'){
				//ACCOUNTS PAYABLE
				$pay_res = mysql_query("select p.account_id as account_id, a.name as name, sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '213%' and p.date >= '".$from_date."' and p.date <= '".$to_date."' group by p.account_id");
				//$resp->AddAlert(mysql_error());
				while($pay = mysql_fetch_array($pay_res)){
					//$pay = mysql_fetch_array($pay_res);
					$pay_amt = ($pay['amount'] == NULL) ? 0 : $pay['amount'];
					
					#store
					$this->storeValue($fiscal_id,$pay_amt,$pay['account_id'],$report_type);
					/*********/
					
				}
			}
			#total level 3
		}
		#total level 2
	}
	#grand total
	
}
#trial balance
function closeTrialBalance(){
	$report_type='trial balance';
	$close_date=date('Y-m-d H:i:s');
	$fiscal_id="";
	if($this->isFiscalSaved($close_date,$report_type)){
		$id_query=(mysql_fetch_array(mysql_query("select max(id) as id from fiscal")));
		$fiscal_id=$id_query['id'];
		}
		$level2_res= mysql_query("select * from accounts where account_no >10 and account_no <20 order by account_no");
		$assets = 0;
		while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
			
			$level3_res = mysql_query("select * from accounts where account_no >=111 and account_no <= 199 and account_no like '".$level2['account_no']."%' and account_no not in (124, 112) order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				$grand_total = 0;
				if($level3['account_no'] == '111'){  //LOANS
					$grand_loss = 0;
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1111 and account_no <= 1119");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$loss_total=0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 111101 and account_no <= 111999");
						
						while($level5 = mysql_fetch_array($level5_res)){
							$loan1_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level5['id']."' ".$this->getRange("d.date"));
							$loan1 = mysql_fetch_array($loan1_res);
							
							if($loan1['amount'] == NULL){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 11110101 and account_no <= 11199999");
								if(mysql_numrows($level6_res) >0){
									
									$sub2_total = 0;
									$loss2_total = 0;
									while($level6 = mysql_fetch_array($level6_res)){
										$loan2_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level6['id']."' ".$this->getRange("d.date"));
										$loan2 = mysql_fetch_array($loan2_res);
										$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level6['id']."' ".$this->getRange("pay.date"));
										$pay = mysql_fetch_array($pay_res);
										$loan2_amt = ($loan2['amount'] == NULL) ? 0 : $loan2['amount'];
										
										$loss_res = mysql_query("select sum(w.amount) as amount from written_off w join disbursed d on w.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id ".$this->getRange("w.date","where")." and p.account_id='".$level6['id']."'");
										$loss = mysql_fetch_array($loss_res);
										$loss2_total = $loss2_total + $loss['amount'];
										$sub2_total = $sub2_total + $loan2_amt - $pay['amount'] - $loss['amount'];
									}
								} 
							}else{
								$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level5['id']."'  ".$this->getRange("pay.date"));

								$pay = mysql_fetch_array($pay_res);
								$loss_res = mysql_query("select sum(w.amount) as amount from written_off w join disbursed d on w.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id ".$this->getRange("w.date","where")." and p.account_id='".$level5['id']."'");
								$loss = mysql_fetch_array($loss_res);
								$loss2_total = $loss['amount'];
								$sub2_total = $loan1['amount'] - $pay['amount'] - $loss['amount'];
							}
						
							$sub1_total = $sub1_total + $sub2_total;
							$loss_total = $loss_total + $loss2_total;
							$sub2_total = 0;
						}

						$grand_total = $grand_total + $sub1_total;
						//$grand_loss = $grand_loss + $loss_total;
					}
						
					

					//LOAN LOSS ALLOWANCES
					//LOAN LOSS ALLOWANCES
					$grand_loss=0;
					$prov_res = mysql_query("select * from provissions order by percent asc");
					while($prov = mysql_fetch_array($prov_res)){
						preg_match("/(\d*)[_](\d*)$/", $prov['range'], $arg);
						$interval = ($arg[1] <>180) ? ($arg[2] - $arg[1]) : 180;
						if($arg[2]==''){
							$arg[1] =180;
							$arg[2]=660;     //UP TO 22 MONTHS OF ARREARS, THE ASSUMPTION IS THAT BEYOND THAT, THE LOAN WILL HAVE BEEN WRITTEN OFF
						}
						
						$over_180 =0;
						$sched_res = mysql_query("select sum(princ_amt) as amount, loan_id as loan_id from schedule where date <= DATE_SUB('".$date."', INTERVAL ".$arg[1]." DAY) and date >= DATE_SUB('".$date."', INTERVAL ".$arg[2]." DAY) and loan_id not in (select loan_id from written_off where date <= DATE_SUB('".$date."', INTERVAL ".$arg[1]." DAY)) group by loan_id");
					
					
						while($sched = mysql_fetch_array($sched_res)){
							$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
							$all = mysql_fetch_array(mysql_query("select sum(princ_amt) as amount from schedule where date <= DATE_SUB('".$date."', INTERVAL ".$arg[2]." DAY)  and loan_id='".$sched['loan_id']."'"));
							$paid_res = mysql_query("select sum(princ_amt) as amount from payment ".$this->getRange("date","where")." and loan_id='".$sched['loan_id']."'");
							$paid = mysql_fetch_array($paid_res);
							$paid_amt = ($paid['amount'] == NULL) ? 0 : $paid['amount'];
							$arrears = $all['amount'] - $paid_amt;
							if($arrears < 0){
								$arrears =0;
							}
							
							$arrears = ($arrears > $sched_amt) ? $sched_amt : $arrears;
							$over_180  += $arrears;

						}	
						$loss180_over = floor($over_180 * $prov['percent']/100);
						$grand_loss += $loss180_over;
					}

					//MANUAL LOAN LOSS PROVISSION
					$static = mysql_fetch_array(mysql_query("select sum(amount) as amount from otherloan_loss ".$this->getRange("date","where")));
					$static_amt = ($static['amount'] == NULL) ? 0 : $static['amount'];
					$grand_loss = $grand_loss + $static_amt;
					$grand_net = $grand_total - $grand_loss;  //SUBTRACT LOAN LOSS PROVISIONS

					$grand_total = $grand_total - $grand_loss;  //SUBTRACT LOAN LOSS PROVISIONS
				}elseif($level3['account_no'] >= 113 && $level3['account_no'] <= 119){   //INVESTMENTS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1131 and account_no <= 1199");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level4['id']."' ".$this->getRange("date"));
						$invest = mysql_fetch_array($invest_res);
						if($invest['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 113101 and account_no <= 119999");
							while($level5 = mysql_fetch_array($level5_res)){
								$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level5['id']."' ".$this->getRange("date"));
								$invest = mysql_fetch_array($invest_res);
								$sub2_total=0;
								if($invest['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 11310101 and account_no <= 11399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level5['id']."' ".$this->getRange("date"));		
										$invest = mysql_fetch_array($invest_res);

										$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level6['id']."' ".$this->getRange("date"));
										$sold = mysql_fetch_array($sold_res);
										$sold_amt = ($sold['amount'] == NULL) ? 0 : $sold['amount'];
										$sub2_total = $sub2_total + $invest['amount'] - $sold_amt;
									}
								}else{
									
									$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level5['id']."' ".$this->getRange("date"));
									$sold = mysql_fetch_array($sold_res);
									$sold_amt = ($sold['amount'] == NULL) ? 0 : $sold['amount'];
									$sub2_total = $invest['amount'] - $sold_amt;
								}
								$sub1_total = $sub1_total + $sub2_total;
							}
						}else{
							$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level4['id']."' ".$this->getRange("date"));
							$sold = mysql_fetch_array($sold_res);
							$sold_amt = ($sold['amount'] == NULL) ? 0 : $sold['amount'];
							$sub1_total = $invest['amount'] - $sold_amt;
						}
						$grand_total = $grand_total + $sub1_total;
					}
				}elseif($level3['account_no'] >= 125){   //INVESTMENTS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1251 and account_no <= 1259");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						if($level4['account_no'] == '1252'){
							$sub1_total =0;
						}else{
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 125101 and account_no <= 125999");
							while($level5 = mysql_fetch_array($level5_res)){
								$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level5['id']."' ".$this->getRange("date"));
								$sub2_total=0;
								$invest = mysql_fetch_array($invest_res);
								if($invest['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 12510101 and account_no <= 12599999");
									while($level6 = mysql_fetch_array($level6_res)){
										$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level6['id']."' ".$this->getRange("date"));		
										$invest = mysql_fetch_array($invest_res);

										$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level6['id']."' ".$this->getRange("date")." order by date desc limit 1"));
										$sold_res = mysql_query("select sum(quantity) as quantity from sold_invest where account_id='".$level6['id']."' ".$this->getRange("date"));
										$sold = mysql_fetch_array($sold_res);
										$sold_amt = ($sold['quantity'] == NULL) ? 0 : ($sold['quantity'] * $unit['amount']);
										$sub2_total = $sub2_total + $invest['amount'] - $sold_amt;
									}
								}else{
								//$invest = mysql_fetch_array($invest_res);
									$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level5['id']."' ".$this->getRange("date")." order by date desc limit 1"));
									$sold_res = mysql_query("select sum(quantity) as quantity from sold_invest where account_id='".$level5['id']."' ".$this->getRange("date"));
									$sold = mysql_fetch_array($sold_res);
									$sold_amt = ($sold['quantity'] == NULL) ? 0 : ($sold['quantity'] * $unit['amount']);
									$sub2_total = $invest['amount'] - $sold_amt;
									
								}
								$sub1_total = $sub1_total + $sub2_total;
							}
						}
						$grand_total = $grand_total + $sub1_total;
					}
				}elseif($level3['account_no'] == 121){  //LIQUID ASSETS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1211 and account_no <= 1219");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 121101 and account_no <= 121999");
						while($level5 = mysql_fetch_array($level5_res)){
							$bank1_res = mysql_query("select * from bank_account where account_id='".$level5['id']."'");
							$sub2_total = 0;
							if(mysql_numrows($bank1_res) == 0){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 12110101 and account_no <= 12199999");
								while($level6 = mysql_fetch_array($level6_res)){
									//$resp->AddAlert("Hi");
									$bank2_res = mysql_query("select * from bank_account where account_id='".$level6['id']."'");
									$bank2 = mysql_fetch_array($bank2_res);
									//DEPOSITS
									$dep_res = mysql_query("select sum(amount) as amount from deposit where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$dep = mysql_fetch_array($dep_res);
									//WITHDRAWALS
									$with_res = mysql_query("select sum(amount) as amount from withdrawal where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$with = mysql_fetch_array($with_res);
									//OTHER INCOME
									$other_res = mysql_query("select sum(amount) as amount from other_income where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$other = mysql_fetch_array($other_res);
									//LOANS PAYABLE
									$loans_payable = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '212%' and p.bank_account='".$bank2['id']."' ".$this->getRange("p.date"));
									$loans_payable = mysql_fetch_array($loans_payable);
									$loans_payable = ($loans_payable['amount'] != NULL) ? $loans_payable['amount'] : 0;
									//EXPENSES
									$expense_res = mysql_query("select sum(amount) as amount from expense where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$expense = mysql_fetch_array($expense_res);
									//PAYABLE PAID
									$payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where bank_account='".$bank2['id']."' ".$this->getRange("date"));	
									$payable_paid = mysql_fetch_array($payable_paid_res);
									//RECEIVABLE COLLECTED
									$collected_res = mysql_query("select sum(amount) as amount from collected where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$collected = mysql_fetch_array($collected_res);
									//DISBURSED LOANS
									$disb_res = mysql_query("select sum(amount) as amount from disbursed where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$disb = mysql_fetch_array($disb_res);
									//PAYMENTS
									$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id  ".$this->getRange("p.date","where")." and p.bank_account='".$bank2['id']."'");
									$pay = mysql_fetch_array($pay_res);
									//PENALTIES
									$pen_res = mysql_query("select sum(p.amount) as amount from penalty p join disbursed d on p.loan_id=d.id where p.bank_account='".$bank2['id']."' and p.status='paid' ".$this->getRange("p.date"));
									$pen = mysql_fetch_array($pen_res);
								
									//SHARES
									$shares_res = mysql_query("select sum(value) as amount from shares ".$this->getRange("date","where")." and bank_account='".$bank2['id']."'");
									$shares = mysql_fetch_array($shares_res); 
									//RECOVERED
									$rec_res = mysql_query("select sum(r.amount) as amount from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.bank_account='".$bank2['id']."' ".$this->getRange("r.date"));
									$rec = mysql_fetch_array($rec_res);			
									//INVESTMENTS 
									$invest_res = mysql_query("select sum(quantity * amount) as amount from investments ".$this->getRange("date","where")." and bank_account='".$bank2['id']."'");
									$invest = mysql_fetch_array($invest_res);
									$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
									//DIVIDENDS PAID
									$div_res = mysql_query("select sum(total_amount) as total_amount from share_dividends ".$this->getRange("date","where")." and bank_account='".$bank1['id']."'");
									$div = mysql_fetch_array($div_res);
									$div_amt = ($div['amount'] != NULL) ? $div['amount'] : 0;
									//SOLD INVESTMENTS
									$soldinvest_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where bank_account ='".$bank2['id']."' ".$this->getRange("date"));
									$soldinvest = mysql_fetch_array($soldinvest_res);

									//FIXED ASSETS 
									$fixed_res = mysql_query("select sum(initial_value) as amount from fixed_asset where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$fixed = mysql_fetch_array($fixed_res);
									$soldasset_res = mysql_query("select sum(amount) as amount from sold_asset where bank_account ='".$bank2['id']."' ".$this->getRange("date"));
									$soldasset = mysql_fetch_array($soldasset_res);
									
									//CASH IMPORTED
									$import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id='".$bank2['id']."' ".$this->getRange("date"));
									$import = mysql_fetch_array($import_res);
									$import_amt = ($import['amount'] ==NULL) ? 0 : $import['amount'];

									//CASH EXPORTED
									$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id='".$bank2['id']."' ".$this->getRange("date"));
									$export = mysql_fetch_array($export_res);
									$export_amt = ($export['amount'] ==NULL) ? 0 : $export['amount'];

									
									$sub2_total = $sub2_total + $collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt;	
								}
							}else{
								$bank1 = mysql_fetch_array($bank1_res);
								//DEPOSITS
								$dep_res = mysql_query("select sum(amount) as amount from deposit where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$dep = mysql_fetch_array($dep_res);
								//WITHDRAWALS
								$with_res = mysql_query("select sum(amount) as amount from withdrawal where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$with = mysql_fetch_array($with_res);
								//OTHER INCOME
								$other_res = mysql_query("select sum(amount) as amount from other_income where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$other = mysql_fetch_array($other_res);
								//EXPENSES
								$expense_res = mysql_query("select sum(amount) as amount from expense where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$expense = mysql_fetch_array($expense_res);
								//LOANS PAYABLE
								$loans_payable = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '212%' and p.bank_account='".$bank1['id']."' ".$this->getRange("p.date"));
								$loans_payable = mysql_fetch_array($loans_payable);
								$loans_payable = ($loans_payable['amount'] != NULL) ? $loans_payable['amount'] : 0;
								//PAYABLE PAID
								$payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$payable_paid = mysql_fetch_array($payable_paid_res);
								//RECEIVABLE COLLECTED
								$collected_res = mysql_query("select sum(amount) as amount from collected where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$collected = mysql_fetch_array($collected_res);
								//DISBURSED LOANS
								$disb_res = mysql_query("select sum(amount) as amount from disbursed where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$disb = mysql_fetch_array($disb_res);
								//PAYMENTS
								$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id  ".$this->getRange("p.date","where")." and p.bank_account='".$bank1['id']."'");
								$pay = mysql_fetch_array($pay_res);
								//PENALTIES
								$pen_res = mysql_query("select sum(p.amount) as amount from penalty p join disbursed d on p.loan_id=d.id where p.bank_account='".$bank1['id']."' and p.status='paid' ".$this->getRange("p.date"));
								$pen = mysql_fetch_array($pen_res);
								
								//SHARES
								$shares_res = mysql_query("select sum(value) as amount from shares ".$this->getRange("date","where")." and bank_account='".$bank1['id']."'");
								$shares = mysql_fetch_array($shares_res); 
								//RECOVERED
								$rec_res = mysql_query("select sum(r.amount) as amount from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.bank_account='".$bank1['id']."' ".$this->getRange("r.date"));
								$rec = mysql_fetch_array($rec_res);
								$rec_amt = ($rec['amount'] != NULL) ? $rec['amount'] : 0;
								//INVESTMENTS 
								$invest_res = mysql_query("select sum(quantity * amount) as amount from investments ".$this->getRange("date","where")." and bank_account='".$bank1['id']."'");
								$invest = mysql_fetch_array($invest_res);
								$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
								//DIVIDENDS PAID
								$div_res = mysql_query("select sum(total_amount) as total_amount from share_dividends ".$this->getRange("date","where")." and bank_account='".$bank1['id']."'");
								$div = mysql_fetch_array($div_res);
								$div_amt = ($div['amount'] != NULL) ? $div['amount'] : 0;

								
								$soldinvest_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where bank_account ='".$bank1['id']."' ".$this->getRange("date"));
								$soldinvest = mysql_fetch_array($soldinvest_res);

								//FIXED ASSETS 
								$fixed_res = mysql_query("select sum(initial_value) as amount from fixed_asset where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$fixed = mysql_fetch_array($fixed_res);
								$soldasset_res = mysql_query("select sum(amount) as amount from sold_asset where bank_account ='".$bank1['id']."' ".$this->getRange("date"));
								$soldasset = mysql_fetch_array($soldasset_res);
								//CASH IMPORTED
								$import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id=".$bank1['id']." ".$this->getRange("date"));
								$import = mysql_fetch_array($import_res);
								$import_amt = ($import['amount'] ==NULL) ? 0 : $import['amount'];

								//CASH EXPORTED
								$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id=".$bank1['id']." ".$this->getRange("date"));
								$export = mysql_fetch_array($export_res);
								$export_amt = ($export['amount'] ==NULL) ? 0 : $export['amount'];									

								$sub2_total =$collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt;	
							}
							$sub1_total = $sub1_total + $sub2_total;
						}
						$grand_total += $sub1_total;
					}
				}elseif($level3['account_no']=='122'){     //RECEIVABLES
					if($basis == 'Cash Basis')
						continue;
					$rec_res = mysql_query("select sum(amount) as amount from receivable ".$this->getRange("maturity_date","where"));
					$rec = mysql_fetch_array($rec_res);
					if($rec['amount'] != NULL){
						$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id ".$this->getRange("c.date","where"));
						$col = mysql_fetch_array($col_res);
						$grand_total =  $rec['amount'] - $col['amount'];
					}
				}elseif($level3['account_no'] == '123'){  //FIXED ASSETS
					$deppreciation = 0;
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= '1231' and account_no <= '1239' order by account_no");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$depp1_total = 0;
						$fixed_res = mysql_query("select sum(f.initial_value) as amount from fixed_asset f left join sold_asset s on s.asset_id=f.id where f.account_id='".$level4['id']."' ".$this->getRange("f.date")." and (s.date >'".$this->dateto."' and s.date <'".$this->datefrom."' or s.date is null)");
						$fixed = mysql_fetch_array($fixed_res);
						if($fixed['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 123101 and account_no <= 123999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$depp2_total = 0;
								$fixed_res = mysql_query("select sum(f.initial_value) as amount from fixed_asset f left join sold_asset s on s.asset_id=f.id where f.account_id='".$level5['id']."' ".$this->getRange("f.date")." and (s.date >'".$this->dateto."' and s.date <'".$this->datefrom."' or s.date is null)");
								$fixed = mysql_fetch_array($fixed_res);
								if($fixed['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >=12310101 and account_no <= 12399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$fixed_res = mysql_query("select sum(f.initial_value) as amount from fixed_asset f left join sold_asset s on s.asset_id=f.id where f.account_id='".$level6['id']."' ".$this->getRange("f.date")." and (s.date >'".$this->dateto."' and s.date <'".$this->datefrom."' or s.date is null)");
										$fixed = mysql_fetch_array($fixed_res);
										$sub2_total = $sub2_total + $fixed['amount'];
										$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from fixed_asset where account_id='".$level6['id']."') ".$this->getRange("date"));
										$depp = mysql_fetch_array($depp_res);
										$depp2_total = $depp2_total + $depp['amount'];
									}
								}else{
									//$fixed = mysql_fetch_array($fixed_res);
									$sub2_total = $fixed['amount'];
									$depp_res = mysql_query("select sum(amount) as amount from deppreciation  where asset_id in (select id from fixed_asset where account_id='".$level5['id']."') ".$this->getRange("date"));
									$depp = mysql_fetch_array($depp_res);
									$depp2_total = $depp2_total + $depp['amount'];
								}
								if($fixed['id'] == '0')
									continue;
								$acc_res = mysql_query("select * from accounts where name='Accumulated Depreciation - ".$level5['name']."'");
								$acc = mysql_fetch_array($acc_res);
								$net_total = $sub2_total - $depp2_total;		
				
								$sub1_total += $sub2_total;
								$depp1_total += $depp2_total;
							}
						}else{  //IF THE FIXED ASSET IS A LEVEL 4 ACCOUNT
							$sub1_total = $fixed['amount'];
							$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from fixed_asset where account_id='".$level4['id']."') ".$this->getRange("date"));
							$depp = mysql_fetch_array($depp_res);
							$depp1_total = ($depp['amount'] != NULL) ? $depp['amount'] : 0;
						}
						
						$net_total = $sub1_total - $depp1_total;
						if($level4['name'] != 'Land'){
							$acc_res = mysql_query("select * from accounts where name = 'Accumulated Depreciation - ".$level4['name']."'");
							$acc = mysql_fetch_array($acc_res);
						}
						$grand_total = $grand_total + $net_total;
						$deppreciation = $deppreciation + $depp1_total;
					}
				}elseif($level3['account_no'] == '124'){
					//$grand_total=$deppreciation;
					;
				}
				$level2_total += $grand_total;
					#store
					$this->storeValue($fiscal_id,$grand_total,$level3['id'],$report_type);
					/*********/
				if($level3['account_no'] == '111'){
					$loss_res = mysql_query("select * from accounts where account_no='112'");
					$loss = mysql_fetch_array($loss_res);
					#store
					$this->storeValue($fiscal_id,$grand_loss,$loss['id'],$report_type);
					/*********/
					$level2_total += $grand_loss;
				}
			}			
			$assets += $level2_total;
		}

// LIABILITY AND CAPITAL ACCOUNTS
		$level2_res= mysql_query("select * from accounts where account_no >20 and account_no <40 order by account_no");
		$liabilities = 0;
		$capital = 0;
		
		while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
			
			$level3_res = mysql_query("select * from accounts where account_no >=211 and account_no <= 399 and account_no like '".$level2['account_no']."%' order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				$grand_total = 0;
				if($level3['account_no'] == '211'){  //SAVINGS & DEPOSITS
					//MONTHLY CHARGES
					$charge_res = mysql_query("select sum(amount) as amount from monthly_charge ".$this->getRange("date","where"));
					$charge = mysql_fetch_array($charge_res);
					$grand_total = $grand_total - $charge['amount'];
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=2111 and account_no <= 2119");
					while($level4 = mysql_fetch_array($level4_res)){
						if($level4['name'] == 'Compulsory Savings')
							continue;
						$sub1_total = 0;
						$prod_res = mysql_query("select * from savings_product where account_id='".$level4['id']."'");
						if(mysql_numrows($prod_res) > 0){
							$prod = mysql_fetch_array($prod_res);
							$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("d.date"));
							$dep = mysql_fetch_array($dep_res);
							$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("w.date"));
							$with = mysql_fetch_array($with_res);

							$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("i.date"));
							$int = mysql_fetch_array($int_res);
							$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];

							$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') ".$this->getRange("o.date")));
							$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;

							//SHARES OFFSET FROM SAVINGS
							$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') ".$this->getRange("s.date")));
							$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;

							//OFFSETTING FROM SAVINGS
							$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' ".$this->getRange("p.date"));
							$pay = mysql_fetch_array($pay_res);
							$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;
							
							$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
							$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
							$sub1_total = $dep_amt +$int_amt - $with_amt - $income_amt - $pay_amt - $share_amt; // + $int['amount'];
						}else{
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 211101 and account_no <= 211999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total =0;
								$prod_res = mysql_query("select * from savings_product where account_id='".$level5['id']."'");
								if(mysql_numrows($prod_res) > 0){
									$prod = mysql_fetch_array($prod_res);
									$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("d.date"));
									$dep = mysql_fetch_array($dep_res);
									$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("w.date"));
									$with = mysql_fetch_array($with_res);

									$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("i.date"));
									$int = mysql_fetch_array($int_res);
									$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
									$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') ".$this->getRange("o.date")));
									$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;
									//OFFSETTING FROM SAVINGS
									$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' ".$this->getRange("p.date"));
									$pay = mysql_fetch_array($pay_res);
									$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;

									//SHARES OFFSET FROM SAVINGS
									$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') ".$this->getRange("s.date")));
									$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;


									$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
									$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
									$sub2_total = $dep_amt + $int_amt - $with_amt - $income_amt - $pay_amt - $share_amt;  // + $int['amount'];
									$sub1_total = $sub1_total + $sub2_total;
								}else{
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 21110101 and account_no <= 21199999");
									while($level6 = mysql_fetch_array($level6_res)){
										$prod_res = mysql_query("select * from savings_product where account_id='".$level6['id']."'");
										if(mysql_numrows($prod_res) > 0){
											$prod = mysql_fetch_array($prod_res);
											$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("d.date"));
											$dep = mysql_fetch_array($dep_res);
											$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("w.date"));
											$with = mysql_fetch_array($with_res);
											
											$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("i.date"));
											$int = mysql_fetch_array($int_res);
											$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
											$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') ".$this->getRange("o.date")));
											$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;

											//OFFSETTING FROM SAVINGS
											$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' ".$this->getRange("p.date"));
											$pay = mysql_fetch_array($pay_res);
											$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;

											//SHARES OFFSET FROM SAVINGS
											$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') ".$this->getRange("s.date")));
											$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;

										
											$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
											$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
											$sub2_total = $sub2_total + $dep_amt + $int_amt - $with_amt - $income_amt - $pay_amt - $share_amt; // + $int['amount'];
										}
									}
									$sub1_total = $sub1_total + $sub2_total;
								}
							}
						}
						$grand_total = $grand_total + $sub1_total;
				}
				$level2_total += $grand_total;
				$liabilities += $grand_total;
			}elseif($level3['account_no'] >=212 && $level3['account_no'] <= 299){
				$grand_total=0;
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=2121 and account_no <= 2999");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['name'] == 'Interest Payable on Savings Deposits'){
						
					}elseif($level4['name'] == 'Interest Payable on Time Deposits'){
						
					}elseif($level4['name'] == 'Dividends Payable'){
						$div_res = mysql_query("select sum(total_amount) as amount from  share_dividends where bank_account=0 ".$this->getRange("date"));
						$div = mysql_fetch_array($div_res);
						$sub1_total = intval($div['amount']);
						$interest_awarded += $sub1_total;
					}else{
						//if($basis == 'Cash Basis' && !preg_match("/^212/", $level4['account_no']))
						//	continue;

						$pay_res = mysql_query("select sum(amount) as amount from payable where account_id='".$level4['id']."' ".$this->getRange("date"));
						$pay = mysql_fetch_array($pay_res);
						
						if($pay['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 212101 and account_no <= 213999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$pay_res = mysql_query("select sum(amount) as amount from payable where account_id='".$level5['id']."' ".$this->getRange("date"));
								$pay = mysql_fetch_array($pay_res);
								if($pay['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >=21210101 and account_no <= 21399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$pay_res = mysql_query("select sum(amount) as amount from payable where account_id='".$level6['id']."' ".$this->getRange("date"));
										$pay = mysql_fetch_array($pay_res);
										$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id ".$this->getRange("paid.date","where")." and p.account_id='".$level6['id']."'");
										$paid = mysql_fetch_array($paid_res);
										$sub2_total = $sub2_total + $pay['amount'] - $paid['amount'];
									}
								}else{
									$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id ".$this->getRange("paid.date","where")." and p.account_id='".$level5['id']."'");
									$paid = mysql_fetch_array($paid_res);
									$sub2_total = $pay['amount'] - $paid['amount'];
								}
							$sub1_total += $sub2_total;
							}
						}else{
							//$pay = mysql_fetch_array($pay_res);
							$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id ".$this->getRange("paid.date","where")." and p.account_id='".$level4['id']."'");
							$paid = mysql_fetch_array($paid_res);
							$sub1_total = $pay['amount'] - $paid['amount'];
						}
						//if($level3['account_no'] == '213')
							//$resp->AddAlert($sub1_total);
					}
					$grand_total = $grand_total + $sub1_total;
					if($level3['account_no'] =='213'){
						$accrual_expense = $accrual_expense + $sub1_total;
					}
					$level2_total += $sub1_total;
					$liabilities += $sub1_total;
				}
			}elseif($level3['account_no'] == '311'){			
				$share_res = mysql_query("select sum(value) as value from shares ".$this->getRange("date","where"));
				$share = mysql_fetch_array($share_res);
				$level4_res = mysql_query("select * from accounts where name like 'Member Shares'");
				$level4 = mysql_fetch_array($level4_res);
				$sub1_total = $share['value'];
				$grand_total = ($sub1_total > 0) ? $sub1_total : 0;
				$level2_total += $grand_total; 
				$capital += $grand_total;
				
			}elseif($level3['account_no'] == '341'){
				$div_res = mysql_query("select sum(total_amount) as amount from share_dividends where date like '".$year."-%'");
				$div = mysql_fetch_array($div_res);
				$level4_res = mysql_query("select * from accounts where account_no like '3411'");
				$level4 = mysql_fetch_array($level4_res);
				//$sub1_total = ($div['amount'] != NULL) ? $div['amount'] : 0;
				//$grand_total = $grand_total + $div['amount'];
				//$level2_total += $div['amount'];
				//$capital += $div['amount'];
				
			}else{
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 3211 and account_no <= 3399");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					$invest_res = mysql_query("select sum(amount) as amount from investments where account_id='".$level4['id']."' ".$this->getRange("date"));
					$invest = mysql_fetch_array($invest_res);
					if($invest['amount'] == NULL){
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 321101 and account_no <= 339999");
						while($level5 = mysql_fetch_array($level5_res)){
							$sub2_total = 0;
							$invest_res = mysql_query("select sum(amount) as amount from investments where account_id='".$level5['id']."' ".$this->getRange("date"));
							$invest = mysql_fetch_array($invest_res);
							if($invest['amount'] == NULL){
								$level6_res = mysql_query("select  * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 32110101 and account_no <= 33999999");
								while($level6 = mysql_fetch_array($level6_res)){
									$invest_res = mysql_query("select sum(amount) as amount from investments where account_id='".$level6['id']."' ".$this->getRange("date"));
									$invest = mysql_fetch_array($invest_res);
									$sub2_total = $sub2_total + $invest['amount'];
								}
							}else
								$sub2_total = $invest['amount'];
							$sub1_total = $sub1_total + $sub2_total;
						}
					}else{
						$sub1_total = $invest['amount'];
					}
					$grand_total = $grand_total + $sub1_total;
					$level2_total += $sub1_total;
					$capital += $sub1_total;
				}
			}
			
					#store
					$this->storeValue($fiscal_id,$grand_total,$level3['id'],$report_type);
					/*********/
		}
	}

	//INCOME ACCOUNTS
	$level2_res= mysql_query("select * from accounts where account_no >40 and account_no <=49 order by account_no");
	$income = 0;
		
	while($level2 = mysql_fetch_array($level2_res)){
		$level2_total = 0;
		
		$level3_res = mysql_query("select * from accounts where account_no >=411 and account_no <= 599 and account_no like '".$level2['account_no']."%' order by account_no");
		while($level3 = mysql_fetch_array($level3_res)){
			$grand_total = 0;
			if($level3['account_no'] == '411'){
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4111 and account_no <= 4119");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['account_no'] == 4111)
						$int_res = mysql_query("select sum(p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product prod on applic.product_id=prod.id join accounts a on prod.account_id=a.id ".$this->getRange("p.date","where")." and a.name like '%Short%Term%'");
					if($level4['account_no'] == 4112)
						$int_res = mysql_query("select sum(p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product prod on applic.product_id=prod.id join accounts a on prod.account_id=a.id ".$this->getRange("p.date","where")." and a.name like '%Medium%Term%'");
					if($level4['account_no'] == 4113)
						$int_res = mysql_query("select sum(p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product prod on applic.product_id=prod.id join accounts a on prod.account_id=a.id ".$this->getRange("p.date","where")." and a.name like '%Long%Term%'");
					if($level4['account_no'] == 4114)
						$int_res = mysql_query("select sum(amount) as amount from penalty ".$this->getRange("date","where")." and status='paid'");
					if($level4['account_no'] > 4114)
						$int_res = mysql_query("select sum(i.amount) as amount from other_income i join accounts a on i.account_id=a.id where a.account_no like '".$level4['account_no']."%' ".$this->getRange("i.date"));
					$int = mysql_fetch_array($int_res);
					if($int['amount'] != NULL)
						$sub1_total = $int['amount'];
					
					$grand_total += $sub1_total;
				}
			}elseif($level3['account_no'] >= 412 && $level3['account_no'] <= 413){   //RECEIVABLES
				//if($basis == 'Accrual Basis'){
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4121 and account_no <= 4199 and account_no not like '%2'");      //INCOME ACCRUED
					
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$rec_res = mysql_query("select sum(amount) as amount from receivable where account_id='".$level4['id']."' ".$this->getRange("maturity_date"));
						$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where r.account_id='".$level4['id']."' ".$this->getRange("c.date"));
						$rec = mysql_fetch_array($rec_res);
						if($rec['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 412101 and account_no <= 419999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$rec_res = mysql_query("select sum(amount) as amount from receivable where account_id='".$level5['id']."' ".$this->getRange("maturity_date"));
								$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where r.account_id='".$level5['id']."' ".$this->getRange("c.date"));
								$rec = mysql_fetch_array($rec_res);
								if($rec['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 41210101 and account_no <= 41999999");
									while($level6 = mysql_fetch_array($level6_res)){
										$rec_res = mysql_query("select sum(amount) as amount from receivable where account_id='".$level5['id']."' ".$this->getRange("maturity_date"));
										$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where r.account_id='".$level5['id']."' ".$this->getRange("c.date"));
										$rec = mysql_fetch_array($rec_res);
										$col = mysql_fetch_array($col_res);
										$rec_amt = ($rec['amount'] != NULL) ? $rec['amount'] : 0;
										$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
										$top_up = ($basis == 'Accrual Basis') ? $rec_amt : $col_amt;
										$sub2_total = $sub2_total + $top_up;
									}
								}else{
									$col = mysql_fetch_array($col_res);
									$rec_amt = ($rec['amount'] != NULL) ? $rec['amount'] : 0;
									$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
									$sub2_total = ($basis == 'Accrual Basis') ? $rec_amt : $col_amt;
								}
								$sub1_total = $sub1_total + $sub2_total;
							}
						}else{
							$col = mysql_fetch_array($col_res)
;							$rec_amt = ($rec['amount'] != NULL) ? $rec['amount'] : 0;
							$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
							$top_up = ($basis == 'Accrual Basis') ? $rec_amt : $col_amt;
							$sub1_total =$sub1_total + $top_up;
						}
						$grand_total += $sub1_total;
					}
				//}

				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4121 and account_no <= 4199 and account_no like '%2'");         //INCOME RECEIVED
				
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					
					
					//INCOME REGISTERED DIRECTLY
					$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.account_no='".$level4['account_no']."' ".$this->getRange("o.date"));
					$inc = mysql_fetch_array($inc_res);
					if($col['amount'] == NULL && $inc['amount'] == NULL){
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 412101 and account_no <= 419999");
						while($level5 = mysql_fetch_array($level5_res)){
							$sub2_total = 0;
							$account_no = $level3['account_no']."1".substr($level5['account_no'], 2, -2);
							
							$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.account_no='".$level5['account_no']."' ".$this->getRange("o.date"));
							$inc = mysql_fetch_array($inc_res);
							if($col['amount'] == NULL && $inc['amount'] ==NULL){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 41210101 and account_no <= 41999999");
								while($level6 = mysql_fetch_array($level6_res)){
									$account_no = $level3['account_no']."1".substr($level5['account_no'], 4, -4);
										
									$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.account_no='".$level4['account_no']."' ".$this->getRange("o.date"));
									$inc = mysql_fetch_array($inc_res);
									$sub2_total +=$inc['amount'];
								}
							}else{
								$sub2_total = $inc['amount'];
							}
						}
					}else{
						$sub1_total += $inc['amount'];
					}
					$grand_total += $sub1_total;
				}
			}elseif($level3['account_no'] == '424'){
				$inv_res = mysql_query("select sum(quantity * purchase_price) as tot_cost, sum(quantity * amount) as tot_gain from sold_invest ".$this->getRange("date","where"));
				$inv = mysql_fetch_array($inv_res);
				if($inv['tot_cost'] == NULL){
					$grand_total =0;
				}else{
					$grand_total = $inv['tot_gain'] - $inv['tot_cost'];
				}
				//$grand_total = ($grand_total > 0) ? $grand_total : 0;
			}elseif($level3['account_no'] >= 414){
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4141 and account_no <= 4299");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['account_no'] == '4221'){
						$charge_res = mysql_query("select sum(amount) as amount from monthly_charge ".$this->getRange("date","where"));
						$charge = mysql_fetch_array($charge_res);
						$sub1_total = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
					}elseif($level4['account_no'] == '4222'){
						$dep_res = mysql_query("select sum(flat_value + percent_value) as amount from deposit ".$this->getRange("date","where"));
						$dep = mysql_fetch_array($dep_res);

						$with_res = mysql_query("select sum(flat_value + percent_value) as amount from withdrawal ".$this->getRange("date","where"));
						$with = mysql_fetch_array($with_res);
						$sub1_total = $dep['amount'] + $with['amount'];
					}elseif($level4['account_no']=='4223'){
						$gain = mysql_fetch_array(mysql_query("select sum(s.amount - f.initial_value) as amount from sold_asset s join fixed_asset f on s.asset_id=f.id ".$this->getRange("s.date","where")));
						$sub1_total = ($gain['amount'] != NULL) ? $gain['amount'] : 0;
					}else{
					
						$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level4['id']."' ".$this->getRange("o.date"));
						$inc = mysql_fetch_array($inc_res);
						if($inc['amount'] == NULL){
						
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 421101 and account_no <= 429999");
							
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level5['id']."' ".$this->getRange("o.date"));
								
								$inc = mysql_fetch_array($inc_res);
								if($inc['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 41410101 and account_no <= 42999999");
									while($level6 = mysql_fetch_array($level6_res)){
										$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level6['id']."' ".$this->getRange("o.date"));
										$inc = mysql_fetch_array($inc_res);
										$sub2_total += $inc['amount'];
									}
								}else{
										//$resp->AddAlert("Hi");
									$sub2_total = $inc['amount'];
								}
								$sub1_total += $sub2_total;
							}
						}else{
							$sub1_total += $inc['amount'];
						}
					}
					$grand_total += $sub1_total;
				}
			}
		
					#store
					$this->storeValue($fiscal_id,$grand_total,$level3['id'],$report_type);
					/*********/
		$level2_total += $grand_total;
		}
		$income += $level2_total;
	}
	

// EXPENSE ACCOUNTS
	
	$expense =0;
	$level2_res = mysql_query("select * from accounts where account_no >50 and account_no <60 order by account_no");
	while($level2 = mysql_fetch_array($level2_res)){
		$level2_total = 0;
		$level3_res = mysql_query("select * from accounts where account_no like '".$level2['account_no']."%' and account_no >= '511' and account_no <= '599'");
		while($level3 = mysql_fetch_array($level3_res)){
			$grand_total = 0;
			$int_accounts = '0';
			 if($level3['account_no'] == 511){
				 $prod_res = mysql_query("select s.id as id, a.name as name, a.account_no as account_no from savings_product s  join accounts a on s.account_id=a.id where s.type='free' order by a.account_no");
				while($prod = mysql_fetch_array($prod_res)){
					$int_res = mysql_query("select sum(amount) as amount from save_interest  i join mem_accounts m on i.memaccount_id=m.id where m.saveproduct_id='".$prod['id']."' ".$this->getRange("i.date"));
					$int = mysql_fetch_array($int_res);
					$sub1_total = ($int['amount'] != NULL) ? $int['amount'] : 0;
					$level4_res = mysql_query("select * from accounts where name like '%Expense%on %".$prod['name']."'");
					$level4 = mysql_fetch_array($level4_res);
					$grand_total += $sub1_total; 
					$int_accounts .= ", '".$level4['id']."'";
				}  
			}
			if($level3['account_no'] == '534'){
					$grand_total=$deppreciation;
			}else{  
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 5111 and account_no <= 5999 and id not in (".$int_accounts.") and name not like '%Interest%Expense%on%'");
				
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['name']== 'Dividends Paid on Member Shares'){
						$div_res = mysql_query("select sum(total_amount) as amount from share_dividends ".$this->getRange("date","where"));
						$div = mysql_fetch_array($div_res);
						$sub1_total = ($div['amount'] != NULL) ? $div['amount'] : 0;
					}elseif($level4['name']== 'Bad Debt Expense on Loans'){
						$write_res = mysql_query("select sum(amount) as amount from written_off ".$this->getRange("date","where"));
						$write = mysql_fetch_array($write_res);
						$written_amt = ($write['amount'] != NULL) ? $write['amount'] : 0;
						$rec_res = mysql_query("select sum(amount) as amount from recovered ".$this->getRange("date","where"));
						$rec = mysql_fetch_array($rec_res);
						$rec_amt = ($rec['amount'] != NULL) ? $rec['amount'] : 0;
						$sub1_total = $written_amt - $rec_amt; 
					}else{
						$exp_res = mysql_query("select sum(amount) as amount from expense where account_id='".$level4['id']."' ".$this->getRange("date"));
						$exp = mysql_fetch_array($exp_res);
						if($exp['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= '512101' and account_no <= '599999'");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$exp_res = mysql_query("select sum(amount) as amount from expense where account_id='".$level5['id']."' ".$this->getRange("date"));
								$exp = mysql_fetch_array($exp_res);
								if($exp['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= '51210101' and account_no <= '59999999'");
									while($level6 = mysql_fetch_array($level6_res)){
										$exp_res = mysql_query("select sum(amount) as amount from expense where account_id='".$level6['id']."' ".$this->getRange("date"));
										$exp = mysql_fetch_array($exp_res);
										$sub2_total += $exp['amount'];
									}
									
								}else{
									$sub2_total = $exp['amount'];
								}
								$sub1_total += $sub2_total;
							}
							
						}else{
							$sub1_total = ($exp['amount'] != NULL) ? $exp['amount'] : 0;
						}
						
					}
					$grand_total += $sub1_total;
				}
			}
			if($level3['account_no'] == '561'){
				
				
				//ACCOUNTS PAYABLE
				$pay_res = mysql_query("select sum(amount) as amount from payable where bank_account =0 ".$this->getRange("date"));
				$pay = mysql_fetch_array($pay_res);
				$pay_amt = ($pay['amount'] == NULL) ? 0 : $pay['amount'];
				
				$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id join accounts a on p.account_id=a.id ".$this->getRange("paid.date","where")." and a.account_no like '213%'");
				$paid = mysql_fetch_array($paid_res);
				$paid_amt = ($paid['amount'] == NULL) ?  0 : $paid['amount'];

				
				$grand_total = $pay_amt;  // +  $int_amt;  // - $paid_amt;
			}
					#store
					$this->storeValue($fiscal_id,$grand_total,$level3['id'],$report_type);
					/*********/
			$level2_total += $grand_total;
		}
		$expense += $level2_total;
	}
	
}


#GENERATE REPORTS
#case 1 both closed
	function trial_balance1(){
		$content = "trial1";
	}

#case 2 one year closed
	function trial_balance2(){
		$content = "<table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>
		<tr class='headings'><td colspan=2 align=center><font size=5pt><b>ACCOUNTS</b></font></td><td><font size=5pt><b>DEBIT</b></font></td><td><font size=5pt><b>CREDIT</b></font></td></tr>
		<tr bgcolor=lightgrey><td colspan=2 align=center><font size=5pt><b>ASSET ACCOUNTS (1 SERIES)</b></font></td><td><font size=5pt><b></b></font></td><td><font size=5pt><b></b></font></td></tr>";
	//ASSETS";
	
		$level2_res= mysql_query("select * from accounts where account_no >10 and account_no <20 order by account_no");
		$assets = 0;
		while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
			
			$level3_res = mysql_query("select * from accounts where account_no >=111 and account_no <= 199 and account_no like '".$level2['account_no']."%' and account_no not in (124, 112) order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				$grand_total = 0;
				if($level3['account_no'] == '111'){  //LOANS
					$grand_loss = 0;
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1111 and account_no <= 1119");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$loss_total=0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 111101 and account_no <= 111999");
						
						while($level5 = mysql_fetch_array($level5_res)){
							$loan1_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level5['id']."' ".$this->getRange("d.date"));
							$loan1 = mysql_fetch_array($loan1_res);
							if($loan1['amount'] == NULL){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 11110101 and account_no <= 11199999");
								if(mysql_numrows($level6_res) >0){
									
									$sub2_total = 0;
									$loss2_total = 0;
									while($level6 = mysql_fetch_array($level6_res)){
										$loan2_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level6['id']."' ".$this->getRange("d.date"));
										$loan2 = mysql_fetch_array($loan2_res);
										$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level6['id']."' ".$this->getRange("pay.date"));
										$pay = mysql_fetch_array($pay_res);
										$loan2_amt = ($loan2['amount'] == NULL) ? 0 : $loan2['amount'];
										
										$loss_res = mysql_query("select sum(w.amount) as amount from written_off w join disbursed d on w.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id ".$this->getRange("w.date","where")." and p.account_id='".$level6['id']."'");
										$loss = mysql_fetch_array($loss_res);
										$loss2_total = $loss2_total + $loss['amount'];
										$sub2_total = $sub2_total + $loan2_amt - $pay['amount'] - $loss['amount'];
									}
								} 
							}else{
								$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level5['id']."'  ".$this->getRange("pay.date"));

								$pay = mysql_fetch_array($pay_res);
								$loss_res = mysql_query("select sum(w.amount) as amount from written_off w join disbursed d on w.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id ".$this->getRange("w.date","where")." and p.account_id='".$level5['id']."'");
								$loss = mysql_fetch_array($loss_res);
								$loss2_total = $loss['amount'];
								$sub2_total = $loan1['amount'] - $pay['amount'] - $loss['amount'];
							}
						
							$sub1_total = $sub1_total + $sub2_total;
							$loss_total = $loss_total + $loss2_total;
							$sub2_total = 0;
						}

						$grand_total = $grand_total + $sub1_total;
						//$grand_loss = $grand_loss + $loss_total;
					}
						
					

					//LOAN LOSS ALLOWANCES
					//LOAN LOSS ALLOWANCES
					$grand_loss=0;
					$prov_res = mysql_query("select * from provissions order by percent asc");
					while($prov = mysql_fetch_array($prov_res)){
						preg_match("/(\d*)[_](\d*)$/", $prov['range'], $arg);
						$interval = ($arg[1] <>180) ? ($arg[2] - $arg[1]) : 180;
						if($arg[2]==''){
							$arg[1] =180;
							$arg[2]=660;     //UP TO 22 MONTHS OF ARREARS, THE ASSUMPTION IS THAT BEYOND THAT, THE LOAN WILL HAVE BEEN WRITTEN OFF
						}
						
						$over_180 =0;
						$sched_res = mysql_query("select sum(princ_amt) as amount, loan_id as loan_id from schedule where date <= DATE_SUB('".$date."', INTERVAL ".$arg[1]." DAY) and date >= DATE_SUB('".$date."', INTERVAL ".$arg[2]." DAY) and loan_id not in (select loan_id from written_off where date <= DATE_SUB('".$date."', INTERVAL ".$arg[1]." DAY)) group by loan_id");
					
					
						while($sched = mysql_fetch_array($sched_res)){
							$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
							$all = mysql_fetch_array(mysql_query("select sum(princ_amt) as amount from schedule where date <= DATE_SUB('".$date."', INTERVAL ".$arg[2]." DAY)  and loan_id='".$sched['loan_id']."'"));
							$paid_res = mysql_query("select sum(princ_amt) as amount from payment ".$this->getRange("date","where")." and loan_id='".$sched['loan_id']."'");
							$paid = mysql_fetch_array($paid_res);
							$paid_amt = ($paid['amount'] == NULL) ? 0 : $paid['amount'];
							$arrears = $all['amount'] - $paid_amt;
							if($arrears < 0){
								$arrears =0;
							}
							
							$arrears = ($arrears > $sched_amt) ? $sched_amt : $arrears;
							$over_180  += $arrears;

						}	
						$loss180_over = floor($over_180 * $prov['percent']/100);
						$grand_loss += $loss180_over;
					}

					//MANUAL LOAN LOSS PROVISSION
					$static = mysql_fetch_array(mysql_query("select sum(amount) as amount from otherloan_loss ".$this->getRange("date","where")));
					$static_amt = ($static['amount'] == NULL) ? 0 : $static['amount'];
					$grand_loss = $grand_loss + $static_amt;
					$grand_net = $grand_total - $grand_loss;  //SUBTRACT LOAN LOSS PROVISIONS

					$grand_total = $grand_total - $grand_loss;  //SUBTRACT LOAN LOSS PROVISIONS
				}elseif($level3['account_no'] >= 113 && $level3['account_no'] <= 119){   //INVESTMENTS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1131 and account_no <= 1199");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level4['id']."' ".$this->getRange("date"));
						$invest = mysql_fetch_array($invest_res);
						if($invest['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 113101 and account_no <= 119999");
							while($level5 = mysql_fetch_array($level5_res)){
								$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level5['id']."' ".$this->getRange("date"));
								$invest = mysql_fetch_array($invest_res);
								$sub2_total=0;
								if($invest['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 11310101 and account_no <= 11399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level5['id']."' ".$this->getRange("date"));		
										$invest = mysql_fetch_array($invest_res);

										$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level6['id']."' ".$this->getRange("date"));
										$sold = mysql_fetch_array($sold_res);
										$sold_amt = ($sold['amount'] == NULL) ? 0 : $sold['amount'];
										$sub2_total = $sub2_total + $invest['amount'] - $sold_amt;
									}
								}else{
									
									$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level5['id']."' ".$this->getRange("date"));
									$sold = mysql_fetch_array($sold_res);
									$sold_amt = ($sold['amount'] == NULL) ? 0 : $sold['amount'];
									$sub2_total = $invest['amount'] - $sold_amt;
								}
								$sub1_total = $sub1_total + $sub2_total;
							}
						}else{
							$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level4['id']."' ".$this->getRange("date"));
							$sold = mysql_fetch_array($sold_res);
							$sold_amt = ($sold['amount'] == NULL) ? 0 : $sold['amount'];
							$sub1_total = $invest['amount'] - $sold_amt;
						}
						$grand_total = $grand_total + $sub1_total;
					}
				}elseif($level3['account_no'] >= 125){   //INVESTMENTS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1251 and account_no <= 1259");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						if($level4['account_no'] == '1252'){
							$sub1_total =0;
						}else{
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 125101 and account_no <= 125999");
							while($level5 = mysql_fetch_array($level5_res)){
								$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level5['id']."' ".$this->getRange("date"));
								$sub2_total=0;
								$invest = mysql_fetch_array($invest_res);
								if($invest['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 12510101 and account_no <= 12599999");
									while($level6 = mysql_fetch_array($level6_res)){
										$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level6['id']."' ".$this->getRange("date"));		
										$invest = mysql_fetch_array($invest_res);

										$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level6['id']."' ".$this->getRange("date")." order by date desc limit 1"));
										$sold_res = mysql_query("select sum(quantity) as quantity from sold_invest where account_id='".$level6['id']."' ".$this->getRange("date"));
										$sold = mysql_fetch_array($sold_res);
										$sold_amt = ($sold['quantity'] == NULL) ? 0 : ($sold['quantity'] * $unit['amount']);
										$sub2_total = $sub2_total + $invest['amount'] - $sold_amt;
									}
								}else{
								//$invest = mysql_fetch_array($invest_res);
									$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level5['id']."' ".$this->getRange("date")." order by date desc limit 1"));
									$sold_res = mysql_query("select sum(quantity) as quantity from sold_invest where account_id='".$level5['id']."' ".$this->getRange("date"));
									$sold = mysql_fetch_array($sold_res);
									$sold_amt = ($sold['quantity'] == NULL) ? 0 : ($sold['quantity'] * $unit['amount']);
									$sub2_total = $invest['amount'] - $sold_amt;
									
								}
								$sub1_total = $sub1_total + $sub2_total;
							}
						}
						$grand_total = $grand_total + $sub1_total;
					}
				}elseif($level3['account_no'] == 121){  //LIQUID ASSETS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1211 and account_no <= 1219");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 121101 and account_no <= 121999");
						while($level5 = mysql_fetch_array($level5_res)){
							$bank1_res = mysql_query("select * from bank_account where account_id='".$level5['id']."'");
							$sub2_total = 0;
							if(mysql_numrows($bank1_res) == 0){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 12110101 and account_no <= 12199999");
								while($level6 = mysql_fetch_array($level6_res)){
									//$resp->AddAlert("Hi");
									$bank2_res = mysql_query("select * from bank_account where account_id='".$level6['id']."'");
									$bank2 = mysql_fetch_array($bank2_res);
									//DEPOSITS
									$dep_res = mysql_query("select sum(amount) as amount from deposit where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$dep = mysql_fetch_array($dep_res);
									//WITHDRAWALS
									$with_res = mysql_query("select sum(amount) as amount from withdrawal where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$with = mysql_fetch_array($with_res);
									//OTHER INCOME
									$other_res = mysql_query("select sum(amount) as amount from other_income where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$other = mysql_fetch_array($other_res);
									//LOANS PAYABLE
									$loans_payable = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '212%' and p.bank_account='".$bank2['id']."' ".$this->getRange("p.date"));
									$loans_payable = mysql_fetch_array($loans_payable);
									$loans_payable = ($loans_payable['amount'] != NULL) ? $loans_payable['amount'] : 0;
									//EXPENSES
									$expense_res = mysql_query("select sum(amount) as amount from expense where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$expense = mysql_fetch_array($expense_res);
									//PAYABLE PAID
									$payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where bank_account='".$bank2['id']."' ".$this->getRange("date"));	
									$payable_paid = mysql_fetch_array($payable_paid_res);
									//RECEIVABLE COLLECTED
									$collected_res = mysql_query("select sum(amount) as amount from collected where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$collected = mysql_fetch_array($collected_res);
									//DISBURSED LOANS
									$disb_res = mysql_query("select sum(amount) as amount from disbursed where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$disb = mysql_fetch_array($disb_res);
									//PAYMENTS
									$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id  ".$this->getRange("p.date","where")." and p.bank_account='".$bank2['id']."'");
									$pay = mysql_fetch_array($pay_res);
									//PENALTIES
									$pen_res = mysql_query("select sum(p.amount) as amount from penalty p join disbursed d on p.loan_id=d.id where p.bank_account='".$bank2['id']."' and p.status='paid' ".$this->getRange("p.date"));
									$pen = mysql_fetch_array($pen_res);
								
									//SHARES
									$shares_res = mysql_query("select sum(value) as amount from shares ".$this->getRange("date","where")." and bank_account='".$bank2['id']."'");
									$shares = mysql_fetch_array($shares_res); 
									//RECOVERED
									$rec_res = mysql_query("select sum(r.amount) as amount from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.bank_account='".$bank2['id']."' ".$this->getRange("r.date"));
									$rec = mysql_fetch_array($rec_res);			
									//INVESTMENTS 
									$invest_res = mysql_query("select sum(quantity * amount) as amount from investments ".$this->getRange("date","where")." and bank_account='".$bank2['id']."'");
									$invest = mysql_fetch_array($invest_res);
									$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
									//DIVIDENDS PAID
									$div_res = mysql_query("select sum(total_amount) as total_amount from share_dividends ".$this->getRange("date","where")." and bank_account='".$bank1['id']."'");
									$div = mysql_fetch_array($div_res);
									$div_amt = ($div['amount'] != NULL) ? $div['amount'] : 0;
									//SOLD INVESTMENTS
									$soldinvest_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where bank_account ='".$bank2['id']."' ".$this->getRange("date"));
									$soldinvest = mysql_fetch_array($soldinvest_res);

									//FIXED ASSETS 
									$fixed_res = mysql_query("select sum(initial_value) as amount from fixed_asset where bank_account='".$bank2['id']."' ".$this->getRange("date"));
									$fixed = mysql_fetch_array($fixed_res);
									$soldasset_res = mysql_query("select sum(amount) as amount from sold_asset where bank_account ='".$bank2['id']."' ".$this->getRange("date"));
									$soldasset = mysql_fetch_array($soldasset_res);
									
									//CASH IMPORTED
									$import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id='".$bank2['id']."' ".$this->getRange("date"));
									$import = mysql_fetch_array($import_res);
									$import_amt = ($import['amount'] ==NULL) ? 0 : $import['amount'];

									//CASH EXPORTED
									$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id='".$bank2['id']."' ".$this->getRange("date"));
									$export = mysql_fetch_array($export_res);
									$export_amt = ($export['amount'] ==NULL) ? 0 : $export['amount'];

									
									$sub2_total = $sub2_total + $collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt;	
								}
							}else{
								$bank1 = mysql_fetch_array($bank1_res);
								//DEPOSITS
								$dep_res = mysql_query("select sum(amount) as amount from deposit where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$dep = mysql_fetch_array($dep_res);
								//WITHDRAWALS
								$with_res = mysql_query("select sum(amount) as amount from withdrawal where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$with = mysql_fetch_array($with_res);
								//OTHER INCOME
								$other_res = mysql_query("select sum(amount) as amount from other_income where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$other = mysql_fetch_array($other_res);
								//EXPENSES
								$expense_res = mysql_query("select sum(amount) as amount from expense where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$expense = mysql_fetch_array($expense_res);
								//LOANS PAYABLE
								$loans_payable = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '212%' and p.bank_account='".$bank1['id']."' ".$this->getRange("p.date"));
								$loans_payable = mysql_fetch_array($loans_payable);
								$loans_payable = ($loans_payable['amount'] != NULL) ? $loans_payable['amount'] : 0;
								//PAYABLE PAID
								$payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$payable_paid = mysql_fetch_array($payable_paid_res);
								//RECEIVABLE COLLECTED
								$collected_res = mysql_query("select sum(amount) as amount from collected where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$collected = mysql_fetch_array($collected_res);
								//DISBURSED LOANS
								$disb_res = mysql_query("select sum(amount) as amount from disbursed where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$disb = mysql_fetch_array($disb_res);
								//PAYMENTS
								$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id  ".$this->getRange("p.date","where")." and p.bank_account='".$bank1['id']."'");
								$pay = mysql_fetch_array($pay_res);
								//PENALTIES
								$pen_res = mysql_query("select sum(p.amount) as amount from penalty p join disbursed d on p.loan_id=d.id where p.bank_account='".$bank1['id']."' and p.status='paid' ".$this->getRange("p.date"));
								$pen = mysql_fetch_array($pen_res);
								
								//SHARES
								$shares_res = mysql_query("select sum(value) as amount from shares ".$this->getRange("date","where")." and bank_account='".$bank1['id']."'");
								$shares = mysql_fetch_array($shares_res); 
								//RECOVERED
								$rec_res = mysql_query("select sum(r.amount) as amount from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.bank_account='".$bank1['id']."' ".$this->getRange("r.date"));
								$rec = mysql_fetch_array($rec_res);
								$rec_amt = ($rec['amount'] != NULL) ? $rec['amount'] : 0;
								//INVESTMENTS 
								$invest_res = mysql_query("select sum(quantity * amount) as amount from investments ".$this->getRange("date","where")." and bank_account='".$bank1['id']."'");
								$invest = mysql_fetch_array($invest_res);
								$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
								//DIVIDENDS PAID
								$div_res = mysql_query("select sum(total_amount) as total_amount from share_dividends ".$this->getRange("date","where")." and bank_account='".$bank1['id']."'");
								$div = mysql_fetch_array($div_res);
								$div_amt = ($div['amount'] != NULL) ? $div['amount'] : 0;

								
								$soldinvest_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where bank_account ='".$bank1['id']."' ".$this->getRange("date"));
								$soldinvest = mysql_fetch_array($soldinvest_res);

								//FIXED ASSETS 
								$fixed_res = mysql_query("select sum(initial_value) as amount from fixed_asset where bank_account='".$bank1['id']."' ".$this->getRange("date"));
								$fixed = mysql_fetch_array($fixed_res);
								$soldasset_res = mysql_query("select sum(amount) as amount from sold_asset where bank_account ='".$bank1['id']."' ".$this->getRange("date"));
								$soldasset = mysql_fetch_array($soldasset_res);
								//CASH IMPORTED
								$import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id=".$bank1['id']." ".$this->getRange("date"));
								$import = mysql_fetch_array($import_res);
								$import_amt = ($import['amount'] ==NULL) ? 0 : $import['amount'];

								//CASH EXPORTED
								$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id=".$bank1['id']." ".$this->getRange("date"));
								$export = mysql_fetch_array($export_res);
								$export_amt = ($export['amount'] ==NULL) ? 0 : $export['amount'];									

								$sub2_total =$collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt;	
							}
							$sub1_total = $sub1_total + $sub2_total;
						}
						$grand_total += $sub1_total;
					}
				}elseif($level3['account_no']=='122'){     //RECEIVABLES
					if($basis == 'Cash Basis')
						continue;
					$rec_res = mysql_query("select sum(amount) as amount from receivable ".$this->getRange("maturity_date","where"));
					$rec = mysql_fetch_array($rec_res);
					if($rec['amount'] != NULL){
						$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id ".$this->getRange("c.date","where"));
						$col = mysql_fetch_array($col_res);
						$grand_total =  $rec['amount'] - $col['amount'];
					}
				}elseif($level3['account_no'] == '123'){  //FIXED ASSETS
					$deppreciation = 0;
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= '1231' and account_no <= '1239' order by account_no");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$depp1_total = 0;
						$fixed_res = mysql_query("select sum(f.initial_value) as amount from fixed_asset f left join sold_asset s on s.asset_id=f.id where f.account_id='".$level4['id']."' ".$this->getRange("f.date")." and (s.date >'".$this->dateto."' and s.date <'".$this->datefrom."' or s.date is null)");
						$fixed = mysql_fetch_array($fixed_res);
						if($fixed['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 123101 and account_no <= 123999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$depp2_total = 0;
								$fixed_res = mysql_query("select sum(f.initial_value) as amount from fixed_asset f left join sold_asset s on s.asset_id=f.id where f.account_id='".$level5['id']."' ".$this->getRange("f.date")." and (s.date >'".$this->dateto."' and s.date <'".$this->datefrom."' or s.date is null)");
								$fixed = mysql_fetch_array($fixed_res);
								if($fixed['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >=12310101 and account_no <= 12399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$fixed_res = mysql_query("select sum(f.initial_value) as amount from fixed_asset f left join sold_asset s on s.asset_id=f.id where f.account_id='".$level6['id']."' ".$this->getRange("f.date")." and (s.date >'".$this->dateto."' and s.date <'".$this->datefrom."' or s.date is null)");
										$fixed = mysql_fetch_array($fixed_res);
										$sub2_total = $sub2_total + $fixed['amount'];
										$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from fixed_asset where account_id='".$level6['id']."') ".$this->getRange("date"));
										$depp = mysql_fetch_array($depp_res);
										$depp2_total = $depp2_total + $depp['amount'];
									}
								}else{
									//$fixed = mysql_fetch_array($fixed_res);
									$sub2_total = $fixed['amount'];
									$depp_res = mysql_query("select sum(amount) as amount from deppreciation  where asset_id in (select id from fixed_asset where account_id='".$level5['id']."') ".$this->getRange("date"));
									$depp = mysql_fetch_array($depp_res);
									$depp2_total = $depp2_total + $depp['amount'];
								}
								if($fixed['id'] == '0')
									continue;
								$acc_res = mysql_query("select * from accounts where name='Accumulated Depreciation - ".$level5['name']."'");
								$acc = mysql_fetch_array($acc_res);
								$net_total = $sub2_total - $depp2_total;		
				
								$sub1_total += $sub2_total;
								$depp1_total += $depp2_total;
							}
						}else{  //IF THE FIXED ASSET IS A LEVEL 4 ACCOUNT
							$sub1_total = $fixed['amount'];
							$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from fixed_asset where account_id='".$level4['id']."') ".$this->getRange("date"));
							$depp = mysql_fetch_array($depp_res);
							$depp1_total = ($depp['amount'] != NULL) ? $depp['amount'] : 0;
						}
						
						$net_total = $sub1_total - $depp1_total;
						if($level4['name'] != 'Land'){
							$acc_res = mysql_query("select * from accounts where name = 'Accumulated Depreciation - ".$level4['name']."'");
							$acc = mysql_fetch_array($acc_res);
						}
						$grand_total = $grand_total + $net_total;
						$deppreciation = $deppreciation + $depp1_total;
					}
				}elseif($level3['account_no'] == '124'){
					//$grand_total=$deppreciation;
					;
				}
				$level2_total += $grand_total;
				//$prev_amount=getPrev
				$color=($i%2 == 0) ? "lightgrey" : "white";
				$level2_total += $grand_total;
				$content .= "<tr bgcolor=$color><td width=8%>".$level3['account_no']."</td><td> ".$level3['name']."</td><td>".number_format($level2_total,2)."</td><td>".number_format($grand_total, 2)."</td><td>--</td></tr>";	
				$i++;
					#store
					$this->storeValue($fiscal_id,$grand_total,$level3['id'],$report_type);
					/*********/
				if($level3['account_no'] == '111'){
					$loss_res = mysql_query("select * from accounts where account_no='112'");
					$loss = mysql_fetch_array($loss_res);
					#store
					$this->storeValue($fiscal_id,$grand_loss,$loss['id'],$report_type);
					/*********/
					$level2_total += $grand_loss;
				}
			}			
			$assets += $level2_total;
		}

// LIABILITY AND CAPITAL ACCOUNTS
		$level2_res= mysql_query("select * from accounts where account_no >20 and account_no <40 order by account_no");
		$liabilities = 0;
		$capital = 0;
		
		while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
			
			$level3_res = mysql_query("select * from accounts where account_no >=211 and account_no <= 399 and account_no like '".$level2['account_no']."%' order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				$grand_total = 0;
				if($level3['account_no'] == '211'){  //SAVINGS & DEPOSITS
					//MONTHLY CHARGES
					$charge_res = mysql_query("select sum(amount) as amount from monthly_charge ".$this->getRange("date","where"));
					$charge = mysql_fetch_array($charge_res);
					$grand_total = $grand_total - $charge['amount'];
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=2111 and account_no <= 2119");
					while($level4 = mysql_fetch_array($level4_res)){
						if($level4['name'] == 'Compulsory Savings')
							continue;
						$sub1_total = 0;
						$prod_res = mysql_query("select * from savings_product where account_id='".$level4['id']."'");
						if(mysql_numrows($prod_res) > 0){
							$prod = mysql_fetch_array($prod_res);
							$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("d.date"));
							$dep = mysql_fetch_array($dep_res);
							$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("w.date"));
							$with = mysql_fetch_array($with_res);

							$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("i.date"));
							$int = mysql_fetch_array($int_res);
							$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];

							$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') ".$this->getRange("o.date")));
							$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;

							//SHARES OFFSET FROM SAVINGS
							$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') ".$this->getRange("s.date")));
							$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;

							//OFFSETTING FROM SAVINGS
							$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' ".$this->getRange("p.date"));
							$pay = mysql_fetch_array($pay_res);
							$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;
							
							$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
							$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
							$sub1_total = $dep_amt +$int_amt - $with_amt - $income_amt - $pay_amt - $share_amt; // + $int['amount'];
						}else{
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 211101 and account_no <= 211999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total =0;
								$prod_res = mysql_query("select * from savings_product where account_id='".$level5['id']."'");
								if(mysql_numrows($prod_res) > 0){
									$prod = mysql_fetch_array($prod_res);
									$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("d.date"));
									$dep = mysql_fetch_array($dep_res);
									$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("w.date"));
									$with = mysql_fetch_array($with_res);

									$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("i.date"));
									$int = mysql_fetch_array($int_res);
									$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
									$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') ".$this->getRange("o.date")));
									$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;
									//OFFSETTING FROM SAVINGS
									$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' ".$this->getRange("p.date"));
									$pay = mysql_fetch_array($pay_res);
									$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;

									//SHARES OFFSET FROM SAVINGS
									$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') ".$this->getRange("s.date")));
									$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;


									$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
									$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
									$sub2_total = $dep_amt + $int_amt - $with_amt - $income_amt - $pay_amt - $share_amt;  // + $int['amount'];
									$sub1_total = $sub1_total + $sub2_total;
								}else{
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 21110101 and account_no <= 21199999");
									while($level6 = mysql_fetch_array($level6_res)){
										$prod_res = mysql_query("select * from savings_product where account_id='".$level6['id']."'");
										if(mysql_numrows($prod_res) > 0){
											$prod = mysql_fetch_array($prod_res);
											$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("d.date"));
											$dep = mysql_fetch_array($dep_res);
											$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("w.date"));
											$with = mysql_fetch_array($with_res);
											
											$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' ".$this->getRange("i.date"));
											$int = mysql_fetch_array($int_res);
											$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
											$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') ".$this->getRange("o.date")));
											$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;

											//OFFSETTING FROM SAVINGS
											$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' ".$this->getRange("p.date"));
											$pay = mysql_fetch_array($pay_res);
											$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;

											//SHARES OFFSET FROM SAVINGS
											$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') ".$this->getRange("s.date")));
											$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;

										
											$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
											$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
											$sub2_total = $sub2_total + $dep_amt + $int_amt - $with_amt - $income_amt - $pay_amt - $share_amt; // + $int['amount'];
										}
									}
									$sub1_total = $sub1_total + $sub2_total;
								}
							}
						}
						$grand_total = $grand_total + $sub1_total;
				}
				$level2_total += $grand_total;
				$liabilities += $grand_total;
			}elseif($level3['account_no'] >=212 && $level3['account_no'] <= 299){
				$grand_total=0;
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=2121 and account_no <= 2999");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['name'] == 'Interest Payable on Savings Deposits'){
						
					}elseif($level4['name'] == 'Interest Payable on Time Deposits'){
						
					}elseif($level4['name'] == 'Dividends Payable'){
						$div_res = mysql_query("select sum(total_amount) as amount from  share_dividends where bank_account=0 ".$this->getRange("date"));
						$div = mysql_fetch_array($div_res);
						$sub1_total = intval($div['amount']);
						$interest_awarded += $sub1_total;
					}else{
						//if($basis == 'Cash Basis' && !preg_match("/^212/", $level4['account_no']))
						//	continue;

						$pay_res = mysql_query("select sum(amount) as amount from payable where account_id='".$level4['id']."' ".$this->getRange("date"));
						$pay = mysql_fetch_array($pay_res);
						
						if($pay['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 212101 and account_no <= 213999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$pay_res = mysql_query("select sum(amount) as amount from payable where account_id='".$level5['id']."' ".$this->getRange("date"));
								$pay = mysql_fetch_array($pay_res);
								if($pay['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >=21210101 and account_no <= 21399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$pay_res = mysql_query("select sum(amount) as amount from payable where account_id='".$level6['id']."' ".$this->getRange("date"));
										$pay = mysql_fetch_array($pay_res);
										$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id ".$this->getRange("paid.date","where")." and p.account_id='".$level6['id']."'");
										$paid = mysql_fetch_array($paid_res);
										$sub2_total = $sub2_total + $pay['amount'] - $paid['amount'];
									}
								}else{
									$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id ".$this->getRange("paid.date","where")." and p.account_id='".$level5['id']."'");
									$paid = mysql_fetch_array($paid_res);
									$sub2_total = $pay['amount'] - $paid['amount'];
								}
							$sub1_total += $sub2_total;
							}
						}else{
							//$pay = mysql_fetch_array($pay_res);
							$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id ".$this->getRange("paid.date","where")." and p.account_id='".$level4['id']."'");
							$paid = mysql_fetch_array($paid_res);
							$sub1_total = $pay['amount'] - $paid['amount'];
						}
						//if($level3['account_no'] == '213')
							//$resp->AddAlert($sub1_total);
					}
					$grand_total = $grand_total + $sub1_total;
					if($level3['account_no'] =='213'){
						$accrual_expense = $accrual_expense + $sub1_total;
					}
					$level2_total += $sub1_total;
					$liabilities += $sub1_total;
				}
			}elseif($level3['account_no'] == '311'){			
				$share_res = mysql_query("select sum(value) as value from shares ".$this->getRange("date","where"));
				$share = mysql_fetch_array($share_res);
				$level4_res = mysql_query("select * from accounts where name like 'Member Shares'");
				$level4 = mysql_fetch_array($level4_res);
				$sub1_total = $share['value'];
				$grand_total = ($sub1_total > 0) ? $sub1_total : 0;
				$level2_total += $grand_total; 
				$capital += $grand_total;
				
			}elseif($level3['account_no'] == '341'){
				$div_res = mysql_query("select sum(total_amount) as amount from share_dividends where date like '".$year."-%'");
				$div = mysql_fetch_array($div_res);
				$level4_res = mysql_query("select * from accounts where account_no like '3411'");
				$level4 = mysql_fetch_array($level4_res);
				//$sub1_total = ($div['amount'] != NULL) ? $div['amount'] : 0;
				//$grand_total = $grand_total + $div['amount'];
				//$level2_total += $div['amount'];
				//$capital += $div['amount'];
				
			}else{
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 3211 and account_no <= 3399");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					$invest_res = mysql_query("select sum(amount) as amount from investments where account_id='".$level4['id']."' ".$this->getRange("date"));
					$invest = mysql_fetch_array($invest_res);
					if($invest['amount'] == NULL){
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 321101 and account_no <= 339999");
						while($level5 = mysql_fetch_array($level5_res)){
							$sub2_total = 0;
							$invest_res = mysql_query("select sum(amount) as amount from investments where account_id='".$level5['id']."' ".$this->getRange("date"));
							$invest = mysql_fetch_array($invest_res);
							if($invest['amount'] == NULL){
								$level6_res = mysql_query("select  * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 32110101 and account_no <= 33999999");
								while($level6 = mysql_fetch_array($level6_res)){
									$invest_res = mysql_query("select sum(amount) as amount from investments where account_id='".$level6['id']."' ".$this->getRange("date"));
									$invest = mysql_fetch_array($invest_res);
									$sub2_total = $sub2_total + $invest['amount'];
								}
							}else
								$sub2_total = $invest['amount'];
							$sub1_total = $sub1_total + $sub2_total;
						}
					}else{
						$sub1_total = $invest['amount'];
					}
					$grand_total = $grand_total + $sub1_total;
					$level2_total += $sub1_total;
					$capital += $sub1_total;
				}
			}
			
					#store
					$this->storeValue($fiscal_id,$grand_total,$level3['id'],$report_type);
					/*********/
		}
	}

	//INCOME ACCOUNTS
	$level2_res= mysql_query("select * from accounts where account_no >40 and account_no <=49 order by account_no");
	$income = 0;
		
	while($level2 = mysql_fetch_array($level2_res)){
		$level2_total = 0;
		
		$level3_res = mysql_query("select * from accounts where account_no >=411 and account_no <= 599 and account_no like '".$level2['account_no']."%' order by account_no");
		while($level3 = mysql_fetch_array($level3_res)){
			$grand_total = 0;
			if($level3['account_no'] == '411'){
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4111 and account_no <= 4119");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['account_no'] == 4111)
						$int_res = mysql_query("select sum(p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product prod on applic.product_id=prod.id join accounts a on prod.account_id=a.id ".$this->getRange("p.date","where")." and a.name like '%Short%Term%'");
					if($level4['account_no'] == 4112)
						$int_res = mysql_query("select sum(p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product prod on applic.product_id=prod.id join accounts a on prod.account_id=a.id ".$this->getRange("p.date","where")." and a.name like '%Medium%Term%'");
					if($level4['account_no'] == 4113)
						$int_res = mysql_query("select sum(p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product prod on applic.product_id=prod.id join accounts a on prod.account_id=a.id ".$this->getRange("p.date","where")." and a.name like '%Long%Term%'");
					if($level4['account_no'] == 4114)
						$int_res = mysql_query("select sum(amount) as amount from penalty ".$this->getRange("date","where")." and status='paid'");
					if($level4['account_no'] > 4114)
						$int_res = mysql_query("select sum(i.amount) as amount from other_income i join accounts a on i.account_id=a.id where a.account_no like '".$level4['account_no']."%' ".$this->getRange("i.date"));
					$int = mysql_fetch_array($int_res);
					if($int['amount'] != NULL)
						$sub1_total = $int['amount'];
					
					$grand_total += $sub1_total;
				}
			}elseif($level3['account_no'] >= 412 && $level3['account_no'] <= 413){   //RECEIVABLES
				//if($basis == 'Accrual Basis'){
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4121 and account_no <= 4199 and account_no not like '%2'");      //INCOME ACCRUED
					
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$rec_res = mysql_query("select sum(amount) as amount from receivable where account_id='".$level4['id']."' ".$this->getRange("maturity_date"));
						$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where r.account_id='".$level4['id']."' ".$this->getRange("c.date"));
						$rec = mysql_fetch_array($rec_res);
						if($rec['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 412101 and account_no <= 419999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$rec_res = mysql_query("select sum(amount) as amount from receivable where account_id='".$level5['id']."' ".$this->getRange("maturity_date"));
								$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where r.account_id='".$level5['id']."' ".$this->getRange("c.date"));
								$rec = mysql_fetch_array($rec_res);
								if($rec['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 41210101 and account_no <= 41999999");
									while($level6 = mysql_fetch_array($level6_res)){
										$rec_res = mysql_query("select sum(amount) as amount from receivable where account_id='".$level5['id']."' ".$this->getRange("maturity_date"));
										$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where r.account_id='".$level5['id']."' ".$this->getRange("c.date"));
										$rec = mysql_fetch_array($rec_res);
										$col = mysql_fetch_array($col_res);
										$rec_amt = ($rec['amount'] != NULL) ? $rec['amount'] : 0;
										$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
										$top_up = ($basis == 'Accrual Basis') ? $rec_amt : $col_amt;
										$sub2_total = $sub2_total + $top_up;
									}
								}else{
									$col = mysql_fetch_array($col_res);
									$rec_amt = ($rec['amount'] != NULL) ? $rec['amount'] : 0;
									$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
									$sub2_total = ($basis == 'Accrual Basis') ? $rec_amt : $col_amt;
								}
								$sub1_total = $sub1_total + $sub2_total;
							}
						}else{
							$col = mysql_fetch_array($col_res)
;							$rec_amt = ($rec['amount'] != NULL) ? $rec['amount'] : 0;
							$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
							$top_up = ($basis == 'Accrual Basis') ? $rec_amt : $col_amt;
							$sub1_total =$sub1_total + $top_up;
						}
						$grand_total += $sub1_total;
					}
				//}

				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4121 and account_no <= 4199 and account_no like '%2'");         //INCOME RECEIVED
				
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					
					
					//INCOME REGISTERED DIRECTLY
					$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.account_no='".$level4['account_no']."' ".$this->getRange("o.date"));
					$inc = mysql_fetch_array($inc_res);
					if($col['amount'] == NULL && $inc['amount'] == NULL){
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 412101 and account_no <= 419999");
						while($level5 = mysql_fetch_array($level5_res)){
							$sub2_total = 0;
							$account_no = $level3['account_no']."1".substr($level5['account_no'], 2, -2);
							
							$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.account_no='".$level5['account_no']."' ".$this->getRange("o.date"));
							$inc = mysql_fetch_array($inc_res);
							if($col['amount'] == NULL && $inc['amount'] ==NULL){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 41210101 and account_no <= 41999999");
								while($level6 = mysql_fetch_array($level6_res)){
									$account_no = $level3['account_no']."1".substr($level5['account_no'], 4, -4);
										
									$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.account_no='".$level4['account_no']."' ".$this->getRange("o.date"));
									$inc = mysql_fetch_array($inc_res);
									$sub2_total +=$inc['amount'];
								}
							}else{
								$sub2_total = $inc['amount'];
							}
						}
					}else{
						$sub1_total += $inc['amount'];
					}
					$grand_total += $sub1_total;
				}
			}elseif($level3['account_no'] == '424'){
				$inv_res = mysql_query("select sum(quantity * purchase_price) as tot_cost, sum(quantity * amount) as tot_gain from sold_invest ".$this->getRange("date","where"));
				$inv = mysql_fetch_array($inv_res);
				if($inv['tot_cost'] == NULL){
					$grand_total =0;
				}else{
					$grand_total = $inv['tot_gain'] - $inv['tot_cost'];
				}
				//$grand_total = ($grand_total > 0) ? $grand_total : 0;
			}elseif($level3['account_no'] >= 414){
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4141 and account_no <= 4299");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['account_no'] == '4221'){
						$charge_res = mysql_query("select sum(amount) as amount from monthly_charge ".$this->getRange("date","where"));
						$charge = mysql_fetch_array($charge_res);
						$sub1_total = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
					}elseif($level4['account_no'] == '4222'){
						$dep_res = mysql_query("select sum(flat_value + percent_value) as amount from deposit ".$this->getRange("date","where"));
						$dep = mysql_fetch_array($dep_res);

						$with_res = mysql_query("select sum(flat_value + percent_value) as amount from withdrawal ".$this->getRange("date","where"));
						$with = mysql_fetch_array($with_res);
						$sub1_total = $dep['amount'] + $with['amount'];
					}elseif($level4['account_no']=='4223'){
						$gain = mysql_fetch_array(mysql_query("select sum(s.amount - f.initial_value) as amount from sold_asset s join fixed_asset f on s.asset_id=f.id ".$this->getRange("s.date","where")));
						$sub1_total = ($gain['amount'] != NULL) ? $gain['amount'] : 0;
					}else{
					
						$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level4['id']."' ".$this->getRange("o.date"));
						$inc = mysql_fetch_array($inc_res);
						if($inc['amount'] == NULL){
						
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 421101 and account_no <= 429999");
							
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level5['id']."' ".$this->getRange("o.date"));
								
								$inc = mysql_fetch_array($inc_res);
								if($inc['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 41410101 and account_no <= 42999999");
									while($level6 = mysql_fetch_array($level6_res)){
										$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level6['id']."' ".$this->getRange("o.date"));
										$inc = mysql_fetch_array($inc_res);
										$sub2_total += $inc['amount'];
									}
								}else{
										//$resp->AddAlert("Hi");
									$sub2_total = $inc['amount'];
								}
								$sub1_total += $sub2_total;
							}
						}else{
							$sub1_total += $inc['amount'];
						}
					}
					$grand_total += $sub1_total;
				}
			}
		
					#store
					$this->storeValue($fiscal_id,$grand_total,$level3['id'],$report_type);
					/*********/
		$level2_total += $grand_total;
		}
		$income += $level2_total;
	}
	

// EXPENSE ACCOUNTS
	
	$expense =0;
	$level2_res = mysql_query("select * from accounts where account_no >50 and account_no <60 order by account_no");
	while($level2 = mysql_fetch_array($level2_res)){
		$level2_total = 0;
		$level3_res = mysql_query("select * from accounts where account_no like '".$level2['account_no']."%' and account_no >= '511' and account_no <= '599'");
		while($level3 = mysql_fetch_array($level3_res)){
			$grand_total = 0;
			$int_accounts = '0';
			 if($level3['account_no'] == 511){
				 $prod_res = mysql_query("select s.id as id, a.name as name, a.account_no as account_no from savings_product s  join accounts a on s.account_id=a.id where s.type='free' order by a.account_no");
				while($prod = mysql_fetch_array($prod_res)){
					$int_res = mysql_query("select sum(amount) as amount from save_interest  i join mem_accounts m on i.memaccount_id=m.id where m.saveproduct_id='".$prod['id']."' ".$this->getRange("i.date"));
					$int = mysql_fetch_array($int_res);
					$sub1_total = ($int['amount'] != NULL) ? $int['amount'] : 0;
					$level4_res = mysql_query("select * from accounts where name like '%Expense%on %".$prod['name']."'");
					$level4 = mysql_fetch_array($level4_res);
					$grand_total += $sub1_total; 
					$int_accounts .= ", '".$level4['id']."'";
				}  
			}
			if($level3['account_no'] == '534'){
					$grand_total=$deppreciation;
			}else{  
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 5111 and account_no <= 5999 and id not in (".$int_accounts.") and name not like '%Interest%Expense%on%'");
				
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['name']== 'Dividends Paid on Member Shares'){
						$div_res = mysql_query("select sum(total_amount) as amount from share_dividends ".$this->getRange("date","where"));
						$div = mysql_fetch_array($div_res);
						$sub1_total = ($div['amount'] != NULL) ? $div['amount'] : 0;
					}elseif($level4['name']== 'Bad Debt Expense on Loans'){
						$write_res = mysql_query("select sum(amount) as amount from written_off ".$this->getRange("date","where"));
						$write = mysql_fetch_array($write_res);
						$written_amt = ($write['amount'] != NULL) ? $write['amount'] : 0;
						$rec_res = mysql_query("select sum(amount) as amount from recovered ".$this->getRange("date","where"));
						$rec = mysql_fetch_array($rec_res);
						$rec_amt = ($rec['amount'] != NULL) ? $rec['amount'] : 0;
						$sub1_total = $written_amt - $rec_amt; 
					}else{
						$exp_res = mysql_query("select sum(amount) as amount from expense where account_id='".$level4['id']."' ".$this->getRange("date"));
						$exp = mysql_fetch_array($exp_res);
						if($exp['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= '512101' and account_no <= '599999'");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$exp_res = mysql_query("select sum(amount) as amount from expense where account_id='".$level5['id']."' ".$this->getRange("date"));
								$exp = mysql_fetch_array($exp_res);
								if($exp['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= '51210101' and account_no <= '59999999'");
									while($level6 = mysql_fetch_array($level6_res)){
										$exp_res = mysql_query("select sum(amount) as amount from expense where account_id='".$level6['id']."' ".$this->getRange("date"));
										$exp = mysql_fetch_array($exp_res);
										$sub2_total += $exp['amount'];
									}
									
								}else{
									$sub2_total = $exp['amount'];
								}
								$sub1_total += $sub2_total;
							}
							
						}else{
							$sub1_total = ($exp['amount'] != NULL) ? $exp['amount'] : 0;
						}
						
					}
					$grand_total += $sub1_total;
				}
			}
			if($level3['account_no'] == '561'){
				
				
				//ACCOUNTS PAYABLE
				$pay_res = mysql_query("select sum(amount) as amount from payable where bank_account =0 ".$this->getRange("date"));
				$pay = mysql_fetch_array($pay_res);
				$pay_amt = ($pay['amount'] == NULL) ? 0 : $pay['amount'];
				
				$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id join accounts a on p.account_id=a.id ".$this->getRange("paid.date","where")." and a.account_no like '213%'");
				$paid = mysql_fetch_array($paid_res);
				$paid_amt = ($paid['amount'] == NULL) ?  0 : $paid['amount'];

				
				$grand_total = $pay_amt;  // +  $int_amt;  // - $paid_amt;
			}
					#store
					$this->storeValue($fiscal_id,$grand_total,$level3['id'],$report_type);
					/*********/
			$level2_total += $grand_total;
		}
		$expense += $level2_total;
	}
	

	}
	#case 3 no year closed
	function trial_balance3(){
	$date=$this->dateto;
	$credit =0;
	$debit=0;
	$accrual_expense = 0;
	$interest_awarded =0;
	//return $date;
	$content = "<table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>
		<tr class='headings'><td colspan=2 align=center><font size=5pt><b>ACCOUNTS</b></font></td><td><font size=5pt><b>DEBIT</b></font></td><td><font size=5pt><b>CREDIT</b></font></td></tr>
		<tr bgcolor=lightgrey><td colspan=2 align=center><font size=5pt><b>ASSET ACCOUNTS (1 SERIES)</b></font></td><td><font size=5pt><b></b></font></td><td><font size=5pt><b></b></font></td></tr>";
	//ASSETS
		$i=1;
		$level2_res= mysql_query("select * from accounts where account_no >10 and account_no <20 order by account_no");
		$assets = 0;
		while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
			
			$level3_res = mysql_query("select * from accounts where account_no >=111 and account_no <= 199 and account_no like '".$level2['account_no']."%' and account_no not in (124, 112) order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				//$content .= "<tr bgcolor=$color><td colspan=2 align=left><b>".$level3['name']."</b></td><td></td></tr>";
				$i++;
				$grand_total = 0;
				if($level3['account_no'] == '111'){  //LOANS
					$grand_loss = 0;
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1111 and account_no <= 1119");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$loss_total=0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 111101 and account_no <= 111999");
						//if( mysql_numrows($level5_res) > 0){
							//$color=($i%2 == 0) ? "lightgrey" : "white";
							//$content .= "<tr bgcolor=$color><td></td><td><b>".$level4['name']."</b></td><td></td></tr>";
						//	$i++;
						//}
						while($level5 = mysql_fetch_array($level5_res)){
							$loan1_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level5['id']."' and d.date <= '".$date."'");
							$loan1 = mysql_fetch_array($loan1_res);
							
							if($loan1['amount'] == NULL){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 11110101 and account_no <= 11199999");
								if(mysql_numrows($level6_res) >0){
									
									$sub2_total = 0;
									$loss2_total = 0;
									while($level6 = mysql_fetch_array($level6_res)){
										$loan2_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level6['id']."' and d.date <= '".$date."'");
										$loan2 = mysql_fetch_array($loan2_res);
										//$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level6['id']."' and d.date <= '".$date."' and pay.date <='".$date."'");
										$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level6['id']."' and pay.date <='".$date."'");
										$pay = mysql_fetch_array($pay_res);
										$loan2_amt = ($loan2['amount'] == NULL) ? 0 : $loan2['amount'];
										
										$loss_res = mysql_query("select sum(w.amount) as amount from written_off w join disbursed d on w.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where w.date <= '".$date."' and p.account_id='".$level6['id']."'");
										$loss = mysql_fetch_array($loss_res);
										$loss2_total = $loss2_total + $loss['amount'];
										$sub2_total = $sub2_total + $loan2_amt - $pay['amount'] - $loss['amount'];
									}
								} 
							}else{
								//$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level5['id']."' and d.date <= '".$date."' and pay.date <='".$date."'");
								$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level5['id']."'  and pay.date <='".$date."'");

								$pay = mysql_fetch_array($pay_res);
								$loss_res = mysql_query("select sum(w.amount) as amount from written_off w join disbursed d on w.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where w.date <= '".$date."' and p.account_id='".$level5['id']."'");
								$loss = mysql_fetch_array($loss_res);
								$loss2_total = $loss['amount'];
								$sub2_total = $loan1['amount'] - $pay['amount'] - $loss['amount'];
							}
						//	$color=($i%2 == 0) ? "lightgrey" : "white";
						//	$content .= "<tr bgcolor=$color><td></td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
						//	$i++;
							
							$sub1_total = $sub1_total + $sub2_total;
							$loss_total = $loss_total + $loss2_total;
							$sub2_total = 0;
						}
						//$color=($i%2 == 0) ? "lightgrey" : "white";
						//$content .= "<tr bgcolor=$color><td></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						 //$i++;
						/* $written_res = mysql_query("select * from accounts where account_no like '112%' and name like '%".$level4['name']."'");
						$written = mysql_fetch_array($written_res);
						$color=($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color ><td><b>".$written['account_no']."</b></td><td><b>".$written['name']."</b></td><td><b>".$loss1_total."</b></td></tr>";
						$i++;
						*/

						$grand_total = $grand_total + $sub1_total;
						//$grand_loss = $grand_loss + $loss_total;
					}
						
					

					//LOAN LOSS ALLOWANCES
					//LOAN LOSS ALLOWANCES
					$grand_loss=0;
					$prov_res = mysql_query("select * from provissions order by percent asc");
					while($prov = mysql_fetch_array($prov_res)){
						preg_match("/(\d*)[_](\d*)$/", $prov['range'], $arg);
						$interval = ($arg[1] <>180) ? ($arg[2] - $arg[1]) : 180;
						if($arg[2]==''){
							$arg[1] =180;
							$arg[2]=660;     //UP TO 22 MONTHS OF ARREARS, THE ASSUMPTION IS THAT BEYOND THAT, THE LOAN WILL HAVE BEEN WRITTEN OFF
						}
						
						$over_180 =0;
						$sched_res = mysql_query("select sum(princ_amt) as amount, loan_id as loan_id from schedule where date <= DATE_SUB('".$date."', INTERVAL ".$arg[1]." DAY) and date >= DATE_SUB('".$date."', INTERVAL ".$arg[2]." DAY) and loan_id not in (select loan_id from written_off where date <= DATE_SUB('".$date."', INTERVAL ".$arg[1]." DAY)) group by loan_id");
					
					
						while($sched = mysql_fetch_array($sched_res)){
							$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
							$all = mysql_fetch_array(mysql_query("select sum(princ_amt) as amount from schedule where date <= DATE_SUB('".$date."', INTERVAL ".$arg[2]." DAY)  and loan_id='".$sched['loan_id']."'"));
							$paid_res = mysql_query("select sum(princ_amt) as amount from payment where date <='".$date."' and loan_id='".$sched['loan_id']."'");
							$paid = mysql_fetch_array($paid_res);
							$paid_amt = ($paid['amount'] == NULL) ? 0 : $paid['amount'];
							$arrears = $all['amount'] - $paid_amt;
							if($arrears < 0){
								$arrears =0;
							}
							
							$arrears = ($arrears > $sched_amt) ? $sched_amt : $arrears;
							$over_180  += $arrears;

						}	
						$loss180_over = floor($over_180 * $prov['percent']/100);
						$grand_loss += $loss180_over;
					}

					//MANUAL LOAN LOSS PROVISSION
					$static = mysql_fetch_array(mysql_query("select sum(amount) as amount from otherloan_loss where date <= '".$date."'"));
					$static_amt = ($static['amount'] == NULL) ? 0 : $static['amount'];
					$grand_loss = $grand_loss + $static_amt;
					$grand_net = $grand_total - $grand_loss;  //SUBTRACT LOAN LOSS PROVISIONS


/*
					//FOR OVER 180 DAYS
					$sched_res = mysql_query("select sum(princ_amt) as amount from schedule where date < DATE_SUB('".$date."', INTERVAL 180 DAY)");
					$sched = mysql_fetch_array($sched_res);
					$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
					$paid_res = mysql_query("select sum(princ_amt) as amount from payment where date <='".$date."'");
					$paid = mysql_fetch_array($paid_res);
					$paid_amt = ($paid['amount'] == NULL) ? 0 : $paid['amount'];
					$over_180 = $sched_amt - $paid_amt;
					$over_180 = ($over_180 > 0) ? $over_180 : 0;
					$sth=mysql_query("select * from provissions where range='range180_over'");
					$row = mysql_fetch_array($sth);
					$loss180_over = floor($over_180 * $row['percent']/100);

					//FROM 121 TO 180 DAYS
					$sched_res = mysql_query("select sum(princ_amt) as amount from schedule where date < DATE_SUB('".$date."', INTERVAL 120 DAY)");
					$sched = mysql_fetch_array($sched_res);
					$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
					
					$from121_180 = $sched_amt - $paid_amt;
					$from121_180 = ($from121_180 >0) ? $from120_180 : 0;
					$from121_180 = $from121_180 - $over_180;
					$sth=mysql_query("select * from provissions where range='range121_180'");
					$row = mysql_fetch_array($sth);
					$loss121_180 = floor($from121_180 * $row['percent']/100);

					//FROM 91 TO 120 DAYS
					$sched_res = mysql_query("select sum(princ_amt) as amount from schedule where date < DATE_SUB('".$date."', INTERVAL 90 DAY)");
					$sched = mysql_fetch_array($sched_res);
					$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
					$from91_120 = $sched_amt - $paid_amt;
					$from91_120 = ($from91_120 >0) ? $from91_120 : 0;
					$from91_120 = $from91_120 - $from121_180 - $over_180;    
					$sth=mysql_query("select * from provissions where range='range91_120'");
					$row = mysql_fetch_array($sth);
					$loss91_120 = floor($from91_120 * $row['percent']/100);

					//FROM 61 TO 90 DAYS
					$sched_res = mysql_query("select sum(princ_amt) as amount from schedule where date < DATE_SUB('".$date."', INTERVAL 60 DAY)");
					$sched = mysql_fetch_array($sched_res);
					$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
					$from61_90 = $sched_amt - $paid_amt;
					$from61_90 = ($from61_90 >0) ? $from61_90 : 0;
					$from61_90 = $from61_90 - $from91_120 - $from121_180 - $over_180;
					$sth=mysql_query("select * from provissions where range='range61_90'");
					$row = mysql_fetch_array($sth);
					$loss61_90 = floor($from61_90 * $row['percent']/100);
					
					//FROM 31 TO 90 DAYS
					$sched_res = mysql_query("select sum(princ_amt) as amount from schedule where date < DATE_SUB('".$date."', INTERVAL 30 DAY)");
					$sched = mysql_fetch_array($sched_res);
					$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
					$from31_60 = $sched_amt - $paid_amt;
					$from31_60 = ($from31_60 >0) ? $from31_60 : 0;
					$from31_60 = $from31_60 - $from61_90 - $from91_120 - $from121_180 - $over_180;
					$sth=mysql_query("select * from provissions where range='range31_60'");
					$row = mysql_fetch_array($sth);
					$loss31_60 = floor($from31_60 * $row['percent']/100);

					//FROM 1 TO 30 DAYS
					$sched_res = mysql_query("select sum(princ_amt) as amount from schedule where date < DATE_SUB('".$date."', INTERVAL 30 DAY)");
					$sched = mysql_fetch_array($sched_res);
					$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
					$from1_30 = $sched_amt - $paid_amt;
					$from1_30 = ($from1_30 >0) ? $from1_30 : 0;
					$from1_30 = $from1_30 - $from31_60 - $from61_90 - $from91_120 - $from121_180 - $over_180;
					$sth=mysql_query("select * from provissions where range='range1_30'");
					$row = mysql_fetch_array($sth);
					$loss1_30 = floor($from1_30 * $row['percent']/100);
					
					//MANUAL LOAN LOSS PROVISSION
					$static = mysql_fetch_array(mysql_query("select sum(amount) as amount from otherloan_loss where date <= '".$date."'"));
					$static_amt = ($static['amount'] == NULL) ? 0 : $static['amount'];
					$grand_loss = $loss1_30 + $loss31_60 + $loss61_90 + $loss91_120 + $loss121_180 + $loss180_over + $static_amt;
*/
					$grand_total = $grand_total - $grand_loss;  //SUBTRACT LOAN LOSS PROVISIONS
				}elseif($level3['account_no'] >= 113 && $level3['account_no'] <= 119){   //INVESTMENTS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1131 and account_no <= 1199");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level4['id']."' and date <='".$date."'");
						$invest = mysql_fetch_array($invest_res);
						if($invest['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 113101 and account_no <= 119999");
							while($level5 = mysql_fetch_array($level5_res)){
								$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level5['id']."' and date<='".$date."'");
								$invest = mysql_fetch_array($invest_res);
								$sub2_total=0;
								if($invest['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 11310101 and account_no <= 11399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level5['id']."' and date <='".$date."'");		
										$invest = mysql_fetch_array($invest_res);

										//$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level6['id']."' and date <='".$date."' order by date desc limit 1"));
										$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level6['id']."' and date <='".$date."'");
										$sold = mysql_fetch_array($sold_res);
										$sold_amt = ($sold['amount'] == NULL) ? 0 : $sold['amount'];
										$sub2_total = $sub2_total + $invest['amount'] - $sold_amt;
									}
								}else{
									//$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level5['id']."' and date <='".$date."' order by date desc limit 1"));
									$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level5['id']."' and date <='".$date."'");
									$sold = mysql_fetch_array($sold_res);
									$sold_amt = ($sold['amount'] == NULL) ? 0 : $sold['amount'];
									$sub2_total = $invest['amount'] - $sold_amt;
								}
								$sub1_total = $sub1_total + $sub2_total;
							}
						}else{
							//$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level4['id']."' and date <='".$date."' order by date desc limit 1"));
							$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level4['id']."' and date <='".$date."'");
							$sold = mysql_fetch_array($sold_res);
							$sold_amt = ($sold['amount'] == NULL) ? 0 : $sold['amount'];
							$sub1_total = $invest['amount'] - $sold_amt;
						}
						$grand_total = $grand_total + $sub1_total;
					}
				}elseif($level3['account_no'] >= 125){   //INVESTMENTS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1251 and account_no <= 1259");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						if($level4['account_no'] == '1252'){
							//INTEREST PAYABLE ON SAVINGS
							/* $int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.type='free' and i.date<= '".$date."'");
							$int = mysql_fetch_array($int_res);
							//$sub1_total = ($int['amount'] == NULL) ? 0 : $int['amount'];
							//ACCOUNTS PAYABLE
							$pay_res = mysql_query("select sum(amount) as amount from payable where bank_account =0 and date <= '".$date."'");
							$pay = mysql_fetch_array($pay_res);
							$pay_amt = ($pay['amount'] == NULL) ? 0 : $pay['amount'];
							//$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid left join payable p on paid.payable_id=p.id where p.bank_account=0 and paid.date <= '".$date."'");
							$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id join accounts a on p.account_id=a.id where paid.date <= '".$date."' and a.account_no like '213%'");
							$paid = mysql_fetch_array($paid_res);
							$paid_amt = ($paid['amount'] == NULL) ?  0 : $paid['amount'];
							$sub1_total += $pay_amt - $paid_amt;
							*/
							$sub1_total =0;
						}else{
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 125101 and account_no <= 125999");
							while($level5 = mysql_fetch_array($level5_res)){
								$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level5['id']."' and date<='".$date."'");
								$sub2_total=0;
								$invest = mysql_fetch_array($invest_res);
								if($invest['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 12510101 and account_no <= 12599999");
									while($level6 = mysql_fetch_array($level6_res)){
										$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level6['id']."' and date<='".$date."'");		
										$invest = mysql_fetch_array($invest_res);

										$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level6['id']."' and date <='".$date."' order by date desc limit 1"));
										$sold_res = mysql_query("select sum(quantity) as quantity from sold_invest where account_id='".$level6['id']."' and date <='".$date."'");
										$sold = mysql_fetch_array($sold_res);
										$sold_amt = ($sold['quantity'] == NULL) ? 0 : ($sold['quantity'] * $unit['amount']);
										$sub2_total = $sub2_total + $invest['amount'] - $sold_amt;
									}
								}else{
								//$invest = mysql_fetch_array($invest_res);
									$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level5['id']."' and date <='".$date."' order by date desc limit 1"));
									$sold_res = mysql_query("select sum(quantity) as quantity from sold_invest where account_id='".$level5['id']."' and date <='".$date."'");
									$sold = mysql_fetch_array($sold_res);
									$sold_amt = ($sold['quantity'] == NULL) ? 0 : ($sold['quantity'] * $unit['amount']);
									$sub2_total = $invest['amount'] - $sold_amt;
									
								}
								$sub1_total = $sub1_total + $sub2_total;
							}
						}
						$grand_total = $grand_total + $sub1_total;
					}
				}elseif($level3['account_no'] == 121){  //LIQUID ASSETS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1211 and account_no <= 1219");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 121101 and account_no <= 121999");
						while($level5 = mysql_fetch_array($level5_res)){
							$bank1_res = mysql_query("select * from bank_account where account_id='".$level5['id']."'");
							$sub2_total = 0;
							if(mysql_numrows($bank1_res) == 0){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 12110101 and account_no <= 12199999");
								while($level6 = mysql_fetch_array($level6_res)){
									//$resp->AddAlert("Hi");
									$bank2_res = mysql_query("select * from bank_account where account_id='".$level6['id']."'");
									$bank2 = mysql_fetch_array($bank2_res);
									//DEPOSITS
									$dep_res = mysql_query("select sum(amount) as amount from deposit where bank_account='".$bank2['id']."' and date <='".$date."'");
									$dep = mysql_fetch_array($dep_res);
									//WITHDRAWALS
									$with_res = mysql_query("select sum(amount) as amount from withdrawal where bank_account='".$bank2['id']."' and date <='".$date."'");
									$with = mysql_fetch_array($with_res);
									//OTHER INCOME
									$other_res = mysql_query("select sum(amount) as amount from other_income where bank_account='".$bank2['id']."' and date <='".$date."'");
									$other = mysql_fetch_array($other_res);
									//LOANS PAYABLE
									$loans_payable = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '212%' and p.bank_account='".$bank2['id']."' and p.date <= '".$date."'");
									$loans_payable = mysql_fetch_array($loans_payable);
									$loans_payable = ($loans_payable['amount'] != NULL) ? $loans_payable['amount'] : 0;
									//EXPENSES
									$expense_res = mysql_query("select sum(amount) as amount from expense where bank_account='".$bank2['id']."' and date <='".$date."'");
									$expense = mysql_fetch_array($expense_res);
									//PAYABLE PAID
									$payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where bank_account='".$bank2['id']."' and date <='".$date."'");	
									$payable_paid = mysql_fetch_array($payable_paid_res);
									//RECEIVABLE COLLECTED
									$collected_res = mysql_query("select sum(amount) as amount from collected where bank_account='".$bank2['id']."' and date <='".$date."'");
									$collected = mysql_fetch_array($collected_res);
									//DISBURSED LOANS
									$disb_res = mysql_query("select sum(amount) as amount from disbursed where bank_account='".$bank2['id']."' and date <= '".$date."'");
									$disb = mysql_fetch_array($disb_res);
									//PAYMENTS
									$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id  where p.date <= '".$date."' and p.bank_account='".$bank2['id']."'");
									$pay = mysql_fetch_array($pay_res);
									//PENALTIES
									$pen_res = mysql_query("select sum(p.amount) as amount from penalty p join disbursed d on p.loan_id=d.id where p.bank_account='".$bank2['id']."' and p.status='paid' and p.date <='".$date."'");
									$pen = mysql_fetch_array($pen_res);
								
									//SHARES
									$shares_res = mysql_query("select sum(value) as amount from shares where date <='".$date."' and bank_account='".$bank2['id']."'");
									$shares = mysql_fetch_array($shares_res); 
									//RECOVERED
									$rec_res = mysql_query("select sum(r.amount) as amount from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.bank_account='".$bank2['id']."' and r.date <= '".$date."'");
									$rec = mysql_fetch_array($rec_res);			
									//INVESTMENTS 
									$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where date <= '".$date."' and bank_account='".$bank2['id']."'");
									$invest = mysql_fetch_array($invest_res);
									$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
									//DIVIDENDS PAID
									$div_res = mysql_query("select sum(total_amount) as total_amount from share_dividends where date<='".$date."' and bank_account='".$bank1['id']."'");
									$div = mysql_fetch_array($div_res);
									$div_amt = ($div['amount'] != NULL) ? $div['amount'] : 0;
									//SOLD INVESTMENTS
									$soldinvest_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where bank_account ='".$bank2['id']."' and date <= '".$date."'");
									$soldinvest = mysql_fetch_array($soldinvest_res);

									//FIXED ASSETS 
									$fixed_res = mysql_query("select sum(initial_value) as amount from fixed_asset where bank_account='".$bank2['id']."' and date <='".$date."'");
									$fixed = mysql_fetch_array($fixed_res);
									$soldasset_res = mysql_query("select sum(amount) as amount from sold_asset where bank_account ='".$bank2['id']."' and date <= '".$date."'");
									$soldasset = mysql_fetch_array($soldasset_res);
									
									//CASH IMPORTED
									$import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id='".$bank2['id']."' and date <='".$date."'");
									$import = mysql_fetch_array($import_res);
									$import_amt = ($import['amount'] ==NULL) ? 0 : $import['amount'];

									//CASH EXPORTED
									$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id='".$bank2['id']."' and date <='".$date."'");
									$export = mysql_fetch_array($export_res);
									$export_amt = ($export['amount'] ==NULL) ? 0 : $export['amount'];

									
									$sub2_total = $sub2_total + $collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt;	
								}
							}else{
								$bank1 = mysql_fetch_array($bank1_res);
								//DEPOSITS
								$dep_res = mysql_query("select sum(amount) as amount from deposit where bank_account='".$bank1['id']."' and date <='".$date."'");
								$dep = mysql_fetch_array($dep_res);
								//WITHDRAWALS
								$with_res = mysql_query("select sum(amount) as amount from withdrawal where bank_account='".$bank1['id']."' and date <='".$date."'");
								$with = mysql_fetch_array($with_res);
								//OTHER INCOME
								$other_res = mysql_query("select sum(amount) as amount from other_income where bank_account='".$bank1['id']."' and date <='".$date."'");
								$other = mysql_fetch_array($other_res);
								//EXPENSES
								$expense_res = mysql_query("select sum(amount) as amount from expense where bank_account='".$bank1['id']."' and date <='".$date."'");
								$expense = mysql_fetch_array($expense_res);
								//LOANS PAYABLE
								$loans_payable = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '212%' and p.bank_account='".$bank1['id']."' and p.date <= '".$date."'");
								$loans_payable = mysql_fetch_array($loans_payable);
								$loans_payable = ($loans_payable['amount'] != NULL) ? $loans_payable['amount'] : 0;
								//PAYABLE PAID
								$payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where bank_account='".$bank1['id']."' and date <='".$date."'");
								$payable_paid = mysql_fetch_array($payable_paid_res);
								//RECEIVABLE COLLECTED
								$collected_res = mysql_query("select sum(amount) as amount from collected where bank_account='".$bank1['id']."' and date <='".$date."'");
								$collected = mysql_fetch_array($collected_res);
								//DISBURSED LOANS
								$disb_res = mysql_query("select sum(amount) as amount from disbursed where bank_account='".$bank1['id']."' and date <= '".$date."'");
								$disb = mysql_fetch_array($disb_res);
								//PAYMENTS
								$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id  where p.date <= '".$date."' and p.bank_account='".$bank1['id']."'");
								$pay = mysql_fetch_array($pay_res);
								//PENALTIES
								$pen_res = mysql_query("select sum(p.amount) as amount from penalty p join disbursed d on p.loan_id=d.id where p.bank_account='".$bank1['id']."' and p.status='paid' and p.date <= '".$date."'");
								$pen = mysql_fetch_array($pen_res);
								
								//SHARES
								$shares_res = mysql_query("select sum(value) as amount from shares where date <='".$date."' and bank_account='".$bank1['id']."'");
								$shares = mysql_fetch_array($shares_res); 
								//RECOVERED
								$rec_res = mysql_query("select sum(r.amount) as amount from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.bank_account='".$bank1['id']."' and r.date <= '".$date."'");
								$rec = mysql_fetch_array($rec_res);
								$rec_amt = ($rec['amount'] != NULL) ? $rec['amount'] : 0;
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
								$import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id=".$bank1['id']." and date <='".$date."'");
								$import = mysql_fetch_array($import_res);
								$import_amt = ($import['amount'] ==NULL) ? 0 : $import['amount'];

								//CASH EXPORTED
								$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id=".$bank1['id']." and date <='".$date."'");
								$export = mysql_fetch_array($export_res);
								$export_amt = ($export['amount'] ==NULL) ? 0 : $export['amount'];									

								$sub2_total =$collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt;	
							}
							$sub1_total = $sub1_total + $sub2_total;
						}
						$grand_total += $sub1_total;
					}
				}elseif($level3['account_no']=='122'){     //RECEIVABLES
					if($basis == 'Cash Basis')
						continue;
					$rec_res = mysql_query("select sum(amount) as amount from receivable where maturity_date <='".$date."'");
					$rec = mysql_fetch_array($rec_res);
					if($rec['amount'] != NULL){
						$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where c.date <= '".$date."'");
						$col = mysql_fetch_array($col_res);
						$grand_total =  $rec['amount'] - $col['amount'];
					}
				}elseif($level3['account_no'] == '123'){  //FIXED ASSETS
					$deppreciation = 0;
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= '1231' and account_no <= '1239' order by account_no");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$depp1_total = 0;
						$fixed_res = mysql_query("select sum(f.initial_value) as amount from fixed_asset f left join sold_asset s on s.asset_id=f.id where f.account_id='".$level4['id']."' and f.date <='".$date."' and (s.date >'".$date."' or s.date is null)");
						$fixed = mysql_fetch_array($fixed_res);
						if($fixed['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 123101 and account_no <= 123999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$depp2_total = 0;
								$fixed_res = mysql_query("select sum(f.initial_value) as amount from fixed_asset f left join sold_asset s on s.asset_id=f.id where f.account_id='".$level5['id']."' and f.date <='".$date."' and (s.date >'".$date."' or s.date is null)");
								$fixed = mysql_fetch_array($fixed_res);
								if($fixed['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >=12310101 and account_no <= 12399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$fixed_res = mysql_query("select sum(f.initial_value) as amount from fixed_asset f left join sold_asset s on s.asset_id=f.id where f.account_id='".$level6['id']."' and f.date <='".$date."' and (s.date >'".$date."' or s.date is null)");
										$fixed = mysql_fetch_array($fixed_res);
										$sub2_total = $sub2_total + $fixed['amount'];
										$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from fixed_asset where account_id='".$level6['id']."') and date<= '".$date."'");
										$depp = mysql_fetch_array($depp_res);
										$depp2_total = $depp2_total + $depp['amount'];
									}
								}else{
									//$fixed = mysql_fetch_array($fixed_res);
									$sub2_total = $fixed['amount'];
									$depp_res = mysql_query("select sum(amount) as amount from deppreciation  where asset_id in (select id from fixed_asset where account_id='".$level5['id']."') and date<= '".$date."'");
									$depp = mysql_fetch_array($depp_res);
									$depp2_total = $depp2_total + $depp['amount'];
								}
								if($fixed['id'] == '0')
									continue;
								$acc_res = mysql_query("select * from accounts where name='Accumulated Depreciation - ".$level5['name']."'");
								$acc = mysql_fetch_array($acc_res);
								$net_total = $sub2_total - $depp2_total;		
				
								$sub1_total += $sub2_total;
								$depp1_total += $depp2_total;
							}
						}else{  //IF THE FIXED ASSET IS A LEVEL 4 ACCOUNT
							$sub1_total = $fixed['amount'];
							$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from fixed_asset where account_id='".$level4['id']."') and date<= '".$date."'");
							$depp = mysql_fetch_array($depp_res);
							$depp1_total = ($depp['amount'] != NULL) ? $depp['amount'] : 0;
						}
						
						$net_total = $sub1_total - $depp1_total;
						if($level4['name'] != 'Land'){
							$acc_res = mysql_query("select * from accounts where name = 'Accumulated Depreciation - ".$level4['name']."'");
							$acc = mysql_fetch_array($acc_res);
						}
						$grand_total = $grand_total + $net_total;
						$deppreciation = $deppreciation + $depp1_total;
					}
				}elseif($level3['account_no'] == '124'){
					//$grand_total=$deppreciation;
					;
				}
				$color=($i%2 == 0) ? "lightgrey" : "white";
				$level2_total += $grand_total;
				$content .= "<tr bgcolor=$color><td width=8%>".$level3['account_no']."</td><td> ".$level3['name']."</td><td>".number_format($grand_total, 2)."</td><td>--</td></tr>";	
				$i++;
				if($level3['account_no'] == '111'){
					$loss_res = mysql_query("select * from accounts where account_no='112'");
					$loss = mysql_fetch_array($loss_res);
					$color=($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td>".$loss['account_no']."</td><td> ".$loss['name']."</td><td>".number_format($grand_loss, 2)."</td><td>--</td></tr>";	
					$i++;
					$level2_total += $grand_loss;
				}
			}			
			$assets += $level2_total;

		}

// LIABILITY AND CAPITAL ACCOUNTS
		$color=($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr bgcolor=$color><td colspan=2 align=center><font size=5pt><b>LIABILITY ACCOUNTS (2 SERIES)</b></font></td><td><font size=5pt><b></b></font></td><td><font size=5pt><b></b></font></td></tr>";
		$level2_res= mysql_query("select * from accounts where account_no >20 and account_no <40 order by account_no");
		$liabilities = 0;
		$capital = 0;
		
		while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
		
			if($level2['account_no'] ==31){
				$color=($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td colspan=2 align=center><font size=5pt><b>CAPITAL ACCOUNTS (3 SERIES)</b></font></td><td><font size=5pt><b></b></font></td><td><font size=5pt><b></b></font></td></tr>";
				$i++;
			}
			
			$level3_res = mysql_query("select * from accounts where account_no >=211 and account_no <= 399 and account_no like '".$level2['account_no']."%' order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				$grand_total = 0;
				if($level3['account_no'] == '211'){  //SAVINGS & DEPOSITS
					//MONTHLY CHARGES
					$charge_res = mysql_query("select sum(amount) as amount from monthly_charge where date <= '".$date."'");
					$charge = mysql_fetch_array($charge_res);
					$grand_total = $grand_total - $charge['amount'];
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

							$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') and o.date <= '".$date."'"));
							$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;

							//SHARES OFFSET FROM SAVINGS
							$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') and s.date <= '".$date."'"));
							$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;

							//OFFSETTING FROM SAVINGS
							$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' and p.date<= '".$date."'");
							$pay = mysql_fetch_array($pay_res);
							$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;
							
							$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
							$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
							$sub1_total = $dep_amt +$int_amt - $with_amt - $income_amt - $pay_amt - $share_amt; // + $int['amount'];
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
									$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') and o.date <= '".$date."'"));
									$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;
									//OFFSETTING FROM SAVINGS
									$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' and p.date<= '".$date."'");
									$pay = mysql_fetch_array($pay_res);
									$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;

									//SHARES OFFSET FROM SAVINGS
									$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') and s.date <= '".$date."'"));
									$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;


									$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
									$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
									$sub2_total = $dep_amt + $int_amt - $with_amt - $income_amt - $pay_amt - $share_amt;  // + $int['amount'];
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
											
											$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and i.date<= '".$date."'");
											$int = mysql_fetch_array($int_res);
											$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
											$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') and o.date <= '".$date."'"));
											$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;

											//OFFSETTING FROM SAVINGS
											$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' and p.date<= '".$date."'");
											$pay = mysql_fetch_array($pay_res);
											$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;

											//SHARES OFFSET FROM SAVINGS
											$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') and s.date <= '".$date."'"));
											$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;

										
											$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
											$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
											$sub2_total = $sub2_total + $dep_amt + $int_amt - $with_amt - $income_amt - $pay_amt - $share_amt; // + $int['amount'];
										}
									}
									$sub1_total = $sub1_total + $sub2_total;
								}
							}
						}
						$grand_total = $grand_total + $sub1_total;
				}
				$level2_total += $grand_total;
				$liabilities += $grand_total;
			}elseif($level3['account_no'] >=212 && $level3['account_no'] <= 299){
				$grand_total=0;
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=2121 and account_no <= 2999");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['name'] == 'Interest Payable on Savings Deposits'){
						/* THIS  INTEREST IS ALREADY PAID OUT, AND SO IT IS NOT AN ACCOUNTS PAYABLE 
						$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.name like '%Savings%' and i.date <='".$date."'");
						
						$int = mysql_fetch_array($int_res);
						$sub1_total = ($int['amount'] != NULL) ? $int['amount'] : 0;
						$interest_awarded += $sub1_total;
						*/
						;
					}elseif($level4['name'] == 'Interest Payable on Time Deposits'){
						/*
						$int_res = mysql_query("select sum(amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.name like '%Time%' and i.date <='".$date."'");
						$int = mysql_fetch_array($int_res);
						$sub1_total = ($int['amount'] !=NULL) ? $int['amount'] : 0;
						$interest_awarded += $sub1_total;
						*/
						;
					}elseif($level4['name'] == 'Dividends Payable'){
						$div_res = mysql_query("select sum(total_amount) as amount from  share_dividends where bank_account=0 and date<= '".$date."'");
						$div = mysql_fetch_array($div_res);
						$sub1_total = intval($div['amount']);
						$interest_awarded += $sub1_total;
					}else{
						//if($basis == 'Cash Basis' && !preg_match("/^212/", $level4['account_no']))
						//	continue;

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
										$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id where paid.date <= '".$date."' and p.account_id='".$level6['id']."'");
										$paid = mysql_fetch_array($paid_res);
										$sub2_total = $sub2_total + $pay['amount'] - $paid['amount'];
									}
								}else{
									$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id where paid.date <= '".$date."' and p.account_id='".$level5['id']."'");
									$paid = mysql_fetch_array($paid_res);
									$sub2_total = $pay['amount'] - $paid['amount'];
								}
							$sub1_total += $sub2_total;
							}
						}else{
							//$pay = mysql_fetch_array($pay_res);
							$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id where paid.date <= '".$date."' and p.account_id='".$level4['id']."'");
							$paid = mysql_fetch_array($paid_res);
							$sub1_total = $pay['amount'] - $paid['amount'];
						}
						//if($level3['account_no'] == '213')
							//$resp->AddAlert($sub1_total);
					}
					$grand_total = $grand_total + $sub1_total;
					if($level3['account_no'] =='213'){
						$accrual_expense = $accrual_expense + $sub1_total;
					}
					$level2_total += $sub1_total;
					$liabilities += $sub1_total;
				}
			}elseif($level3['account_no'] == '311'){			
				$share_res = mysql_query("select sum(value) as value from shares where date <='".$date."'");
				$share = mysql_fetch_array($share_res);
				$level4_res = mysql_query("select * from accounts where name like 'Member Shares'");
				$level4 = mysql_fetch_array($level4_res);
				$sub1_total = $share['value'];
				$grand_total = ($sub1_total > 0) ? $sub1_total : 0;
				$level2_total += $grand_total; 
				$capital += $grand_total;
				
			}elseif($level3['account_no'] == '341'){
				$div_res = mysql_query("select sum(total_amount) as amount from share_dividends where date like '".$year."-%'");
				$div = mysql_fetch_array($div_res);
				$level4_res = mysql_query("select * from accounts where account_no like '3411'");
				$level4 = mysql_fetch_array($level4_res);
				//$sub1_total = ($div['amount'] != NULL) ? $div['amount'] : 0;
				//$grand_total = $grand_total + $div['amount'];
				//$level2_total += $div['amount'];
				//$capital += $div['amount'];
				
			}else{
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 3211 and account_no <= 3399");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					$invest_res = mysql_query("select sum(amount) as amount from investments where account_id='".$level4['id']."' and date <= '".$date."'");
					$invest = mysql_fetch_array($invest_res);
					if($invest['amount'] == NULL){
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 321101 and account_no <= 339999");
						while($level5 = mysql_fetch_array($level5_res)){
							$sub2_total = 0;
							$invest_res = mysql_query("select sum(amount) as amount from investments where account_id='".$level5['id']."' and date <= '".$date."'");
							$invest = mysql_fetch_array($invest_res);
							if($invest['amount'] == NULL){
								$level6_res = mysql_query("select  * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 32110101 and account_no <= 33999999");
								while($level6 = mysql_fetch_array($level6_res)){
									$invest_res = mysql_query("select sum(amount) as amount from investments where account_id='".$level6['id']."' and date <= '".$date."'");
									$invest = mysql_fetch_array($invest_res);
									$sub2_total = $sub2_total + $invest['amount'];
								}
							}else
								$sub2_total = $invest['amount'];
							$sub1_total = $sub1_total + $sub2_total;
						}
					}else{
						$sub1_total = $invest['amount'];
					}
					$grand_total = $grand_total + $sub1_total;
					$level2_total += $sub1_total;
					$capital += $sub1_total;
				}
			}
			$color=($i%2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color><td>".$level3['account_no']."</td><td> ".$level3['name']."</td><td>--</td><td>".number_format($grand_total, 2)."</td></tr>";
			$i++;
		}
	}

	//INCOME ACCOUNTS
	$color=($i%2 == 0) ? "lightgrey" : "white";
	$content .= "<tr bgcolor=$color><td colspan=2 align=center><font size=5pt><b>INCOME ACCOUNTS (4 SERIES)
</b></font></td><td><font size=5pt><b></b></font></td><td><font size=5pt><b></b></font></td></tr>";
	$i++;
	$level2_res= mysql_query("select * from accounts where account_no >40 and account_no <=49 order by account_no");
	$income = 0;
		
	while($level2 = mysql_fetch_array($level2_res)){
		$level2_total = 0;
		
		$level3_res = mysql_query("select * from accounts where account_no >=411 and account_no <= 599 and account_no like '".$level2['account_no']."%' order by account_no");
		while($level3 = mysql_fetch_array($level3_res)){
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
					
					$grand_total += $sub1_total;
				}
			}elseif($level3['account_no'] >= 412 && $level3['account_no'] <= 413){   //RECEIVABLES
				//if($basis == 'Accrual Basis'){
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4121 and account_no <= 4199 and account_no not like '%2'");      //INCOME ACCRUED
					
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$rec_res = mysql_query("select sum(amount) as amount from receivable where account_id='".$level4['id']."' and maturity_date <= '".$date."'");
						$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where r.account_id='".$level4['id']."' and c.date <= '".$date."'");
						$rec = mysql_fetch_array($rec_res);
						if($rec['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 412101 and account_no <= 419999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$rec_res = mysql_query("select sum(amount) as amount from receivable where account_id='".$level5['id']."' and maturity_date <='".$date."'");
								$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where r.account_id='".$level5['id']."' and c.date <= '".$date."'");
								$rec = mysql_fetch_array($rec_res);
								if($rec['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 41210101 and account_no <= 41999999");
									while($level6 = mysql_fetch_array($level6_res)){
										$rec_res = mysql_query("select sum(amount) as amount from receivable where account_id='".$level5['id']."' and maturity_date <= '".$date."'");
										$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where r.account_id='".$level5['id']."' and c.date <= '".$date."'");
										$rec = mysql_fetch_array($rec_res);
										$col = mysql_fetch_array($col_res);
										$rec_amt = ($rec['amount'] != NULL) ? $rec['amount'] : 0;
										$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
										$top_up = ($basis == 'Accrual Basis') ? $rec_amt : $col_amt;
										$sub2_total = $sub2_total + $top_up;
									}
								}else{
									$col = mysql_fetch_array($col_res);
									$rec_amt = ($rec['amount'] != NULL) ? $rec['amount'] : 0;
									$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
									$sub2_total = ($basis == 'Accrual Basis') ? $rec_amt : $col_amt;
								}
								$sub1_total = $sub1_total + $sub2_total;
							}
						}else{
							$col = mysql_fetch_array($col_res)
;							$rec_amt = ($rec['amount'] != NULL) ? $rec['amount'] : 0;
							$col_amt = ($col['amount'] != NULL) ? $col['amount'] : 0;
							$top_up = ($basis == 'Accrual Basis') ? $rec_amt : $col_amt;
							$sub1_total =$sub1_total + $top_up;
						}
						$grand_total += $sub1_total;
					}
				//}

				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4121 and account_no <= 4199 and account_no like '%2'");         //INCOME RECEIVED
				
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					//$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id join accounts a on r.account_id=a.id where a.account_no like '".$level3['account_no']."1' and c.date <='".$date."'");   //INCOME ACCOUNTS END WITH 1
					//$col = mysql_fetch_array($col_res);
					
					//INCOME REGISTERED DIRECTLY
					$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.account_no='".$level4['account_no']."' and o.date <='".$date."'");
					$inc = mysql_fetch_array($inc_res);
					if($col['amount'] == NULL && $inc['amount'] == NULL){
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 412101 and account_no <= 419999");
						while($level5 = mysql_fetch_array($level5_res)){
							$sub2_total = 0;
							$account_no = $level3['account_no']."1".substr($level5['account_no'], 2, -2);
							//$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id join accounts a on r.account_id=a.id where a.account_no like '".$account_no."' and c.date <'".$date."'");
							//$col = mysql_fetch_array($col_res);
							$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.account_no='".$level5['account_no']."' and o.date <='".$date."'");
							$inc = mysql_fetch_array($inc_res);
							if($col['amount'] == NULL && $inc['amount'] ==NULL){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 41210101 and account_no <= 41999999");
								while($level6 = mysql_fetch_array($level6_res)){
									$account_no = $level3['account_no']."1".substr($level5['account_no'], 4, -4);
									//$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id join accounts a on r.account_id=a.id where a.account_no like '".$account_no."'  and c.date <'".$date."'");
									//$col = mysql_fetch_array($rec_res);	
									$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.account_no='".$level4['account_no']."' and o.date <='".$date."'");
									$inc = mysql_fetch_array($inc_res);
									$sub2_total +=$inc['amount'];
								}
							}else{
								$sub2_total = $inc['amount'];
							}
						}
					}else{
						$sub1_total += $inc['amount'];
					}
					$grand_total += $sub1_total;
				}
			}elseif($level3['account_no'] == '424'){
				$inv_res = mysql_query("select sum(quantity * purchase_price) as tot_cost, sum(quantity * amount) as tot_gain from sold_invest where  date <= '".$date."'");
				$inv = mysql_fetch_array($inv_res);
				if($inv['tot_cost'] == NULL){
					$grand_total =0;
				}else{
					$grand_total = $inv['tot_gain'] - $inv['tot_cost'];
				}
				//$grand_total = ($grand_total > 0) ? $grand_total : 0;
			}elseif($level3['account_no'] >= 414){
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4141 and account_no <= 4299");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['account_no'] == '4221'){
						$charge_res = mysql_query("select sum(amount) as amount from monthly_charge where date <= '".$date."'");
						$charge = mysql_fetch_array($charge_res);
						$sub1_total = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
					}elseif($level4['account_no'] == '4222'){
						$dep_res = mysql_query("select sum(flat_value + percent_value) as amount from deposit where date <= '".$date."'");
						$dep = mysql_fetch_array($dep_res);

						$with_res = mysql_query("select sum(flat_value + percent_value) as amount from withdrawal where date <= '".$date."'");
						$with = mysql_fetch_array($with_res);
						$sub1_total = $dep['amount'] + $with['amount'];
					}elseif($level4['account_no']=='4223'){
						$gain = mysql_fetch_array(mysql_query("select sum(s.amount - f.initial_value) as amount from sold_asset s join fixed_asset f on s.asset_id=f.id where s.date <='".$date."'"));
						$sub1_total = ($gain['amount'] != NULL) ? $gain['amount'] : 0;
					}else{
					
						$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level4['id']."' and o.date <= '".$date."'");
						$inc = mysql_fetch_array($inc_res);
						if($inc['amount'] == NULL){
						
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 421101 and account_no <= 429999");
							
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level5['id']."' and o.date <= '".$date."'");
								//$resp->AddAppend("status", "innerHTML", "select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level5['id']."' and o.date <= '".$date."'<BR>");
								$inc = mysql_fetch_array($inc_res);
								if($inc['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 41410101 and account_no <= 42999999");
									while($level6 = mysql_fetch_array($level6_res)){
										$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level6['id']."' and o.date <= '".$date."'");
										$inc = mysql_fetch_array($inc_res);
										$sub2_total += $inc['amount'];
									}
								}else{
										//$resp->AddAlert("Hi");
									$sub2_total = $inc['amount'];
								}
								$sub1_total += $sub2_total;
							}
						}else{
							$sub1_total += $inc['amount'];
						}
					}
					$grand_total += $sub1_total;
				}
			}
		$color=($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr bgcolor=$color><td>".$level3['account_no']."</td><td>".$level3['name']."</td><td>--</td><td>".number_format($grand_total, 2)."</td></tr>";
		$i++;
		$level2_total += $grand_total;
		}
		$income += $level2_total;
	}
	

// EXPENSE ACCOUNTS
	$color=($i%2 == 0) ? "lightgrey" : "white";
	$content .= "<tr bgcolor=$color><td colspan=2 align=center><font size=5pt><b>COSTS AND EXPENSE ACCOUNTS (5 SERIES)
</b></font></td><td><font size=5pt><b></b></font></td><td><font size=5pt><b></b></font></td></tr>";
	$i++;
	$expense =0;
	$level2_res = mysql_query("select * from accounts where account_no >50 and account_no <60 order by account_no");
	while($level2 = mysql_fetch_array($level2_res)){
		$level2_total = 0;
		$level3_res = mysql_query("select * from accounts where account_no like '".$level2['account_no']."%' and account_no >= '511' and account_no <= '599'");
		while($level3 = mysql_fetch_array($level3_res)){
			$grand_total = 0;
			$int_accounts = '0';
			 if($level3['account_no'] == 511){
				 $prod_res = mysql_query("select s.id as id, a.name as name, a.account_no as account_no from savings_product s  join accounts a on s.account_id=a.id where s.type='free' order by a.account_no");
				while($prod = mysql_fetch_array($prod_res)){
					$int_res = mysql_query("select sum(amount) as amount from save_interest  i join mem_accounts m on i.memaccount_id=m.id where m.saveproduct_id='".$prod['id']."' and i.date <='".$date."'");
					$int = mysql_fetch_array($int_res);
					$sub1_total = ($int['amount'] != NULL) ? $int['amount'] : 0;
					$level4_res = mysql_query("select * from accounts where name like '%Expense%on %".$prod['name']."'");
					$level4 = mysql_fetch_array($level4_res);
					$grand_total += $sub1_total; 
					$int_accounts .= ", '".$level4['id']."'";
				}  
			}
			if($level3['account_no'] == '534'){
					$grand_total=$deppreciation;
			}else{  
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 5111 and account_no <= 5999 and id not in (".$int_accounts.") and name not like '%Interest%Expense%on%'");
				
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
								$sub1_total += $sub2_total;
							}
							
						}else{
							$sub1_total = ($exp['amount'] != NULL) ? $exp['amount'] : 0;
						}
						
					}
					$grand_total += $sub1_total;
				}
			}
			$color=($i%2 == 0) ? "lightgrey" : "white";
			if($level3['account_no'] == '561'){
				/*if($basis == 'Accrual Basis'){
					$pay_res = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '213%' and p.maturity_date <= '".$date."'");
				
					$pay = mysql_fetch_array($pay_res);
					$grand_total = $pay['amount'];
				}elseif($basis == 'Cash Basis'){ 
					$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id join accounts a on p.account_id=a.id where paid.date <= '".$date."' and a.account_no like '213%'");
					$paid = mysql_fetch_array($paid_res);
					$grand_total =  $paid['amount'];
				} 
				*/
				
				//ACCOUNTS PAYABLE
				$pay_res = mysql_query("select sum(amount) as amount from payable where bank_account =0 and date <= '".$date."'");
				$pay = mysql_fetch_array($pay_res);
				$pay_amt = ($pay['amount'] == NULL) ? 0 : $pay['amount'];
				//$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid left join payable p on paid.payable_id=p.id where p.bank_account=0 and paid.date <= '".$date."'");
				$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id join accounts a on p.account_id=a.id where paid.date <= '".$date."' and a.account_no like '213%'");
				$paid = mysql_fetch_array($paid_res);
				$paid_amt = ($paid['amount'] == NULL) ?  0 : $paid['amount'];

				//INTEREST PAYABLE 
				/*
				//INTEREST ALREADY AWARDED ON SAVINGS IS NOT ACCRUED BUT ACTUALLY PAID OUT EXPENSE
				$int_res = mysql_query("select sum(amount) as amount from save_interest where date<= '".$date."'");
				$int = mysql_fetch_array($int_res);
				$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
				$grand_total = $pay_amt +  $int_amt;  // - $paid_amt;
				*/
				$grand_total = $pay_amt;  // +  $int_amt;  // - $paid_amt;
			}
			$content .= "<tr bgcolor=$color><td>".$level3['account_no']."</td><td>".$level3['name']."</td><td>".number_format($grand_total, 2)."</td><td>--</td></tr>";
			$i++;
			$level2_total += $grand_total;
		}
		$expense += $level2_total;
	}
	$debit = $assets + $expense;
	$credit = $income + $liabilities + $capital;
	$content .= "<tr bgcolor=LIGHTGREY><td align=center colspan=2><font size=5pt><b>TOTAL</b></font></td><td><font size=5pt><b>".number_format($debit, 2)."</b></font></td><td><font size=5pt><b>".number_format($credit, 2)."</b></font></td></b></font></tr>";
	$i++;
	$content .= "</table>";
	return $content;
	}


	#INCOME STATEMENT
	#case 1 

	function income_statement3(){
	$from_date=$this->datefrom;
	$to_date=$this->dateto;
	$content="<table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>";
	
	//INCOME FOR INCOME STATEMENT
		$level2_res= mysql_query("select * from accounts where account_no >40 and account_no <=49 order by account_no");
		$income = 0;
		$i=0;
		$content .= "<tr class=headings><td colspan=3><font size=5pt><b>INCOME</b></font></td></tr>";
		while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
			
			$color=($i%2 == 0) ? lightgrey : "white";
			$content .= "<tr bgcolor=$color><td colspan=2><font size=4pt><b>".$level2['name']."</b></font></td><td></td></tr>";
			$i++;
			$level3_res = mysql_query("select * from accounts where account_no >=411 and account_no <= 599 and account_no like '".$level2['account_no']."%' order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				$color=($i%2 == 0) ? lightgrey : "white";
				$content .= "<tr bgcolor=$color><td colspan=2 align=left><b>".$level3['name']."</b></td><td></td></tr>";
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
						$color=($i%2 == 0) ? lightgrey : "white";
						$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						$i++;
						$grand_total += $sub1_total;
					}
			//	}elseif($level3['account_no'] >= 412 && $level3['account_no'] <= 419){   //RECEIVABLES
				}elseif($level3['account_no'] >= 412 && $level3['account_no'] <= 413){   //RECEIVABLES
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
								$content .= "<tr bgcolor=$color><td><font size=4pt>".$level5['account_no']."</font></td><td><font size=4pt>".$level5['name']." ".$pre."</font></td><td>".number_format($sub2_total, 2)."</td></tr>";
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
						$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']." ".$pre."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
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
								$color = ($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td><font size=4pt></td><td><font size=4pt>".$level5['name']."</font></td><td>".number_format($sub2_total, 2)."</td></tr>";
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
				$inv_res = mysql_query("select sum(quantity * purchase_price) as tot_cost, sum(quantity * amount) as tot_gain from sold_invest where date >='".$from_date."' and date <= '".$to_date."'");
				$inv = mysql_fetch_array($inv_res);
				if($inv['tot_cost'] == NULL){
					$grand_total =0;
				}else{
					$grand_total = $inv['tot_gain'] - $inv['tot_cost'];
				}
			}elseif($level3['account_no'] >= 414){
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 4141 and account_no <= 4999");
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
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 414101 and account_no <= 499999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level5['id']."' and o.date >= '".$from_date."' and o.date <= '".$to_date."'");
								$inc = mysql_fetch_array($inc_res);
								if($inc['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 41410101 and account_no <= 49999999");
									while($level6 = mysql_fetch_array($level6_res)){
										$inc_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.id='".$level6['id']."' and o.date >= '".$from_date."' and o.date <= '".$to_date."'");
										$inc = mysql_fetch_array($inc_res);
										$sub2_total += $inc['amount'];
									}
								}else{
									
									$sub2_total = $inc['amount'];
								}
								$color=($i%2 == 0) ? lightgrey : "white";
								$content .= "<tr bgcolor=$color><td></td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
								$sub1_total += $sub2_total;
								$i++;
							}
						}else{
							
							$sub1_total += $inc['amount'];
						}
						
					}
					$color=($i%2 == 0) ? lightgrey : "white";
					$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
					$i++;
					$grand_total += $sub1_total;
				}
			}
			$color=($i%2 == 0) ? lightgrey : "white";
			$content .= "<tr bgcolor=$color><td colspan=2 align=left><b>TOTAL ".$level3['name']."</b></td><td><b>".number_format($grand_total, 2)."</b></td></tr>";
			$i++;
			$level2_total += $grand_total;
		}

		$color=($i%2 == 0) ? lightgrey : "white";
		$content .= "<tr bgcolor=$color><td colspan=2><font size=4pt><b>TOTAL ".$level2['name']."</b></font></td><td><font size=4pt><b>".number_format($level2_total, 2)."</b></font></td></tr>";
		$i++;
		$income += $level2_total;
	}
	$color=($i%2 == 0) ? lightgrey : "white";
	$content .= "<tr bgcolor=$color><td colspan=2><font size=5pt><b>TOTAL INCOME</b></font></td><td><font size=4pt><b>".number_format($income, 2)."</b></font></td></tr>";
	$i++;

// EXPENSES
	$color=($i%2 == 0) ? lightgrey : "white";
	$content .= "<tr bgcolor=$color><td colspan=3><font size=5pt><b>COSTS & EXPENSES</b></font></td></tr>";
	$i++;
	$level2_res = mysql_query("select * from accounts where account_no >50 and account_no <60 order by account_no");
	while($level2 = mysql_fetch_array($level2_res)){
		$level2_total = 0;
		$color=($i%2 == 0) ? lightgrey : "white";
		$content .= "<tr bgcolor=$color><td colspan=2><font size=4pt><b>".$level2['name']."</b></font></td><td></td></tr>";
		$i++;
		$level3_res = mysql_query("select * from accounts where account_no like '".$level2['account_no']."%' and account_no >= 511 and account_no <= 599");
		while($level3 = mysql_fetch_array($level3_res)){
			$grand_total = 0;
			$color=($i%2 == 0) ? lightgrey : "white";
			$content .= "<tr bgcolor=$color><td colspan=2 align=left><b>".$level3['name']."</b></td><td></td></tr>";
			$i++;
			$int_accounts ='0';
			if($level3['account_no'] == 511){
				$prod_res = mysql_query("select s.id as id, a.name as name, a.account_no as account_no, a.id as account_id from savings_product s  join accounts a on s.account_id=a.id where s.type='free' order by a.account_no");
				while($prod = mysql_fetch_array($prod_res)){
					$int_res = mysql_query("select sum(amount) as amount from save_interest  i join mem_accounts m on i.memaccount_id=m.id where m.saveproduct_id='".$prod['id']."' and i.date <='".$to_date."' and i.date >='".$from_date."'");
					$int = mysql_fetch_array($int_res);
					$sub1_total = ($int['amount'] != NULL) ? $int['amount'] : 0;
					
					$level4_res = mysql_query("select * from accounts where name like '%Expense%on %".$prod['name']."'");
					$level4 = mysql_fetch_array($level4_res);
					$exp = mysql_fetch_array(mysql_query("select sum(amount) as amount from expense where account_id='".$level4['id']."' and date>='".$from_date."' and date <= '".$to_date."'"));
					$sub1_total = ($exp['amount'] != NULL) ? $sub1_total + $exp['amount'] : $sub1_total;
					
					$color=($i%2 == 0) ? lightgrey : "white";
					$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
					$i++;
					$grand_total += $sub1_total; 
					$int_accounts .= ", '".$level4['id']."'";
				}
			}   //else{   //TO INCLUDE OTHER EXPENSES ON SAVINGS 	
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 5111 and account_no <= 5999 and id not in (".$int_accounts.") and name not like '%Interest%Expense%on%'");

				
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
									$color = ($i%2 == 0) ? "lightgrey" : "white";
									$content .= "<tr bgcolor=$color><td>".$level4['account_no'].$x."</td><td>Depreciation - ".$row['name']."</td><td>".number_format($dep_amt, 2)."</td></tr>";
									$sub2_total = $dep_amt;
									$sub1_total += $sub2_total;
									$i++;
									$x++;
								}
					}else{
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
								$color=($i%2 == 0) ? lightgrey : "white";
								$content .= "<tr bgcolor=$color><td><b>".$level5['account_no']."</b></td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
								$i++;
								$sub1_total += $sub2_total;
								
							}
							
						}else{
							//if($level4['account_no'] == '5521')
							//		$resp->AddAlert("Hi");
							$sub1_total = ($exp['amount'] != NULL) ? $exp['amount'] : 0;
							//$sub1_total = $exp['amount'];
						}
					}
					
					$color=($i%2 == 0) ? lightgrey : "white";
					$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><font size=2><b>".number_format($sub1_total, 2)."</b></font></td></tr>";
					$i++;
					$grand_total += $sub1_total;
					
				}
			//}   //ELSE FOR SAVINGS EXPENSES
			
			if($level3['account_no'] == '561'){
				//ACCOUNTS PAYABLE
				$pay_res = mysql_query("select p.account_id as account_id, a.name as name, sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '213%' and p.date >= '".$from_date."' and p.date <= '".$to_date."' group by p.account_id");
				//$resp->AddAlert(mysql_error());
				while($pay = mysql_fetch_array($pay_res)){
					//$pay = mysql_fetch_array($pay_res);
					$pay_amt = ($pay['amount'] == NULL) ? 0 : $pay['amount'];
					
					$color=($i%2 == 0) ? lightgrey : "white";
					$content .= "<tr bgcolor=$color><td colspan=2><b> ".$pay['name']." Expenses</b></td><td><b>".number_format($pay_amt, 2)."</b></td></tr>";
					$i++;
					$grand_total += $pay_amt;  // - $paid_amt;
				}
			}
			$color=($i%2 == 0) ? lightgrey : "white";
			$content .= "<tr bgcolor=$color><td colspan=2><b>TOTAL ".$level3['name']."</b></td><td><b>".number_format($grand_total, 2)."</b></td></tr>";
			$i++;
			$level2_total += $grand_total;
		}
		$color=($i%2 == 0) ? lightgrey : "white";
		$content .= "<tr bgcolor=$color><td COLSPAN=2><font size=4pt><b>TOTAL ".$level2['name']."</b></font></td><td><font size=4pt><b>".number_format($level2_total, 2)."</b></font></td></tr>";
		$i++;
		if($level2['account_no'] ==51)
			$financial = $level2_total;
		if($level2['account_no'] == 52)
			$non_financial = $level2_total;
		$expense = $expense + $level2_total;
	}
	$color=($i%2 == 0) ? lightgrey : "white";
	$content .= "<tr bgcolor=$color><td colspan=2><font size=5pt><b>TOTAL COSTS & EXPENSES</b></font></td><td><font size=5pt><b>".number_format($expense, 2)."</b></font></td></tr>";
	$i++;
	$gross_margen = $income - $financial - $non_financial;
	$color=($i%2 == 0) ? lightgrey : "white";
	$content .= "<tr bgcolor=$color><td colspan=2><font size=5pt><b>GROSS MARGEN</b></font></td><td><font size=5pt><b>".number_format($gross_margen, 2)."</b></font></td></tr>";
	$i++;
	$net_income = $income - $expense;
	
	//$net_income = number_format($net_income, 2);
	$net_income = ($net_income > 0) ? number_format($net_income,2) : "(". number_format((0 - $net_income),2) .")";
	$color=($i%2 == 0) ? lightgrey : "white";
	$content .= "<tr bgcolor=$color><td colspan=2><font size=5pt><b>NET INCOME (LOSS)</b></font></td><td><font size=5pt><b>".$net_income."</b></font></td></tr>";
	$i++;
	$content .= "</table>";
	return $content;
	}

	#BALANCE SHEET
//balance sheet after closing
function balance_sheet2($date,$basis){
	$start=microtime(true);
	//$date=$this->dateto;
	/****Start jiba jeba **/
	$calc = new Date_Calc();
	//$branch_ =($branch_id=='all'||$branch_id=='')? NULL:" and branch_id=".$branch_id;
	
	//$from_date = daysToDate($calc->dateToDays($mday, $month, $year)-365, '%Y-%m-%d');
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	
	$content = '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <center><h3 class="panel-title">'.$branch['branch_name'].'</h3>
                                <p>BALANCE SHEET AS AT '.$date.'</p>
                                <p>'.$basis.'</p></center>
                            </div>';
 		$content .= "<table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>
		<tr bgcolor=lightgrey><td colspan=3 align=center><font size=5pt><b>ASSETS</b></font></td></tr>";
		$level2_res= mysql_query("select * from accounts where account_no >10 and account_no <20 ".$branch_." order by account_no");
		$assets = 0;
		$i=0;
		while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
			$color = ($i%2 == 0) ? "white" : "lightgrey";
			$content .= "<tr bgcolor=$color><td colspan=2><font size=4pt><b>".$level2['name']."</b></font></td><td></td></tr>";
			$i++;
			$level3_res = mysql_query("select * from accounts where account_no >=111 and account_no <= 199 and account_no like '".$level2['account_no']."%' and account_no not in (124, 112) order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td colspan=2 align=left><b>".$level3['name']."</b></td><td></td></tr>";
				$i++;
				$grand_total = 0;
				if($level3['account_no'] == '111'){  //LOANS
					$grand_loss = 0;
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1111 and account_no <= 1119");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$loss_total=0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 111101 and account_no <= 111999");
						if( mysql_numrows($level5_res) > 0){
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td></td><td><b>".$level4['name']."</b></td><td></td></tr>";
							$i++;
						}
						while($level5 = mysql_fetch_array($level5_res)){
							$loan1_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level5['id']."' and d.date <= '".$date."'");
							$loan1 = mysql_fetch_array($loan1_res);
							
							if($loan1['amount'] == NULL){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 11110101 and account_no <= 11199999");
								if(mysql_numrows($level6_res) >0){
									
									$sub2_total = 0;
									$loss2_total = 0;
									while($level6 = mysql_fetch_array($level6_res)){
										$loan2_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level6['id']."' and d.date <= '".$date."'");
										$loan2 = mysql_fetch_array($loan2_res);
										//$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level6['id']."' and d.date <= '".$date."' and pay.date <='".$date."'");
										$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level6['id']."' and pay.date <='".$date."'");
										$pay = mysql_fetch_array($pay_res);
										$loan2_amt = ($loan2['amount'] == NULL) ? 0 : $loan2['amount'];
										
										$loss_res = mysql_query("select sum(w.amount) as amount from written_off w join disbursed d on w.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where w.date <= '".$date."' and p.account_id='".$level6['id']."'");
										$loss = mysql_fetch_array($loss_res);
										$loss2_total = $loss2_total + $loss['amount'];
										$sub2_total = $sub2_total + $loan2_amt - $pay['amount'] - $loss['amount'];
									}
								} 
							}else{
								//$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level5['id']."' and d.date <= '".$date."' and pay.date <='".$date."'");
								$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level5['id']."'  and pay.date <='".$date."'");

								$pay = mysql_fetch_array($pay_res);
								$loss_res = mysql_query("select sum(w.amount) as amount from written_off w join disbursed d on w.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where w.date <= '".$date."' and p.account_id='".$level5['id']."'");
								$loss = mysql_fetch_array($loss_res);
								$loss2_total = $loss['amount'];
								$sub2_total = $loan1['amount'] - $pay['amount'] - $loss['amount'];
							}
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td></td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
							$i++;
							
							$sub1_total = $sub1_total + $sub2_total;
							$loss_total = $loss_total + $loss2_total;
							$sub2_total = 0;
						}
						$color=($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						 $i++;
						/* $written_res = mysql_query("select * from accounts where account_no like '112%' and name like '%".$level4['name']."'");
						$written = mysql_fetch_array($written_res);
						$color=($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color ><td><b>".$written['account_no']."</b></td><td><b>".$written['name']."</b></td><td><b>".$loss1_total."</b></td></tr>";
						$i++;
						*/

						$grand_total = $grand_total + $sub1_total;
						//$grand_loss = $grand_loss + $loss_total;
					}
					//LOAN LOSS ALLOWANCES
					$grand_loss=0;
					$prov_res = mysql_query("select * from provissions order by percent asc");
					
					while($prov = mysql_fetch_array($prov_res)){
						preg_match("/(\d*)[_](\d*)$/", $prov['range'], $arg);
						$interval = ($arg[1] <>180) ? ($arg[2] - $arg[1]) : 180;
						if($arg[2]==''){
							$arg[1] =180;
							$arg[2]=660;     //UP TO 22 MONTHS OF ARREARS, THE ASSUMPTION IS THAT BEYOND THAT, THE LOAN WILL HAVE BEEN WRITTEN OFF
						}
						
						$over_180 =0;
						$sched_res = mysql_query("select sum(princ_amt) as amount, loan_id as loan_id from schedule where date <= DATE_SUB('".$date."', INTERVAL ".$arg[1]." DAY) and date >= DATE_SUB('".$date."', INTERVAL ".$arg[2]." DAY) and loan_id not in (select loan_id from written_off where date <= DATE_SUB('".$date."', INTERVAL ".$arg[1]." DAY)) group by loan_id");
					
					
						while($sched = mysql_fetch_array($sched_res)){
							$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
							$all = mysql_fetch_array(mysql_query("select sum(princ_amt) as amount from schedule where date <= DATE_SUB('".$date."', INTERVAL ".$arg[2]." DAY)  and loan_id='".$sched['loan_id']."'"));
							$paid_res = mysql_query("select sum(princ_amt) as amount from payment where date <='".$date."' and loan_id='".$sched['loan_id']."'");
							$paid = mysql_fetch_array($paid_res);
							$paid_amt = ($paid['amount'] == NULL) ? 0 : $paid['amount'];
							$arrears = $all['amount'] - $paid_amt;
							if($arrears < 0){
								$arrears =0;
							}
							
							$arrears = ($arrears > $sched_amt) ? $sched_amt : $arrears;
							$over_180  += $arrears;

						}	
						$loss180_over = floor($over_180 * $prov['percent']/100);
						$grand_loss += $loss180_over;
					}
					//MANUAL LOAN LOSS PROVISSION
					$static = mysql_fetch_array(mysql_query("select sum(amount) as amount from otherloan_loss where date <= '".$date."'"));
					$static_amt = ($static['amount'] == NULL) ? 0 : $static['amount'];
					$grand_loss = $grand_loss + $static_amt;
					$grand_net = $grand_total - $grand_loss;  //SUBTRACT LOAN LOSS PROVISIONS


					//$grand_loss = $loss1_30 + $loss31_60 + $loss61_90 + $loss91_120 + $loss121_180 + $loss180_over;
				}elseif($level3['account_no'] >= 113 && $level3['account_no'] <= 119){   //INVESTMENTS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1131 and account_no <= 1199");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level4['id']."' and date <='".$date."'");
						$invest = mysql_fetch_array($invest_res);
						if($invest['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 113101 and account_no <= 119999");
							if( mysql_numrows($level5_res) > 0){
								$color=($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td></td></tr>";
								$i++;
							}
							while($level5 = mysql_fetch_array($level5_res)){
								$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level5['id']."' and date<='".$date."'");
								$invest = mysql_fetch_array($invest_res);
								$sub2_total=0;
								if($invest['amount'] ==NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 11310101 and account_no <= 11399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level6['id']."' and date <='".$date."'");		
										$invest = mysql_fetch_array($invest_res);
										//$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level6['id']."' and date <='".$date."' order by date desc limit 1"));
										$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level6['id']."' and date <='".$date."'");
										$sold = mysql_fetch_array($sold_res);
										$sold_amt = ($sold['quantity'] == NULL) ? 0 : $sold['amount'];
										$sub2_total = $sub2_total + $invest['amount'] - $sold_amt;
									}
								}else{
									//$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level5['id']."' and date <='".$date."' order by date desc limit 1"));
									$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level5['id']."' and date <='".$date."'");
									$sold = mysql_fetch_array($sold_res);
									$sold_amt = ($sold['quantity'] == NULL) ? 0 : $sold['amount'];
									$sub2_total = $invest['amount'] - $sold_amt;
									//$sub2_total = $invest['amount'];
								}
								$sub1_total = $sub1_total + $sub2_total;
							}
						}else{
							//$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level4['id']."' and date <='".$date."' order by date desc limit 1"));
							$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level4['id']."' and date <='".$date."'");
							$sold = mysql_fetch_array($sold_res);
							$sold_amt = ($sold['amount'] == NULL) ? 0 : $sold['amount'];
							$sub1_total = $invest['amount'] - $sold_amt;
						}
						$color=($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color ><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						$i++;
						$grand_total = $grand_total + $sub1_total;
					}
				}elseif($level3['account_no'] >= 125){   //INVESTMENTS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1251 and account_no <= 1259");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 125101 and account_no <= 125999");
						if( mysql_numrows($level5_res) > 0){
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td></td></tr>";
							$i++;
						}
						while($level5 = mysql_fetch_array($level5_res)){
							$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level5['id']."' and date <= '".$date."'");
							$invest = mysql_fetch_array($invest_res);
							$sub2_total=0;
							if($invest['amount'] == NULL){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 12510101 and account_no <= 11399999");
								while($level6 = mysql_fetch_array($level6_res)){
									$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level6['id']."' and date <= '".$date."' and id not in (select investment_id from sold_invest where date<='".$date."')");		
									$invest = mysql_fetch_array($invest_res);

									$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level5['id']."' and date <='".$date."' order by date desc limit 1"));
									$sold_res = mysql_query("select sum(quantity) as quantity from sold_invest where account_id='".$level5['id']."' and date <='".$date."'");
									$sold = mysql_fetch_array($sold_res);
									$sold_amt = ($sold['quantity'] == NULL) ? 0 : ($sold['quantity'] * $unit['amount']);
									$sub2_total =$sub2_total + $invest['amount'] - $sold_amt;		

									$sub2_total = $sub2_total + $invest['amount'];
								}
							}else{
								$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level5['id']."' and date <='".$date."' order by date desc limit 1"));
								$sold_res = mysql_query("select sum(quantity) as quantity from sold_invest where account_id='".$level5['id']."' and date <='".$date."'");
								$sold = mysql_fetch_array($sold_res);
								$sold_amt = ($sold['quantity'] == NULL) ? 0 : ($sold['quantity'] * $unit['amount']);
								$sub2_total = $invest['amount'] - $sold_amt;		
							}
							$sub1_total = $sub1_total + $sub2_total;
						}
						$color=($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color ><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						$i++;
						$grand_total = $grand_total + $sub1_total;
					}
				}elseif($level3['account_no'] == 121){
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1211 and account_no <= 1219");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 121101 and account_no <= 121999");
						if( mysql_numrows($level5_res) > 0){
							$color= ($i%2 == 0) ? lightgrey : "white";
							$content .= "<tr bgcolor=$color><td><b></b></td><td><b>".$level4['name']."</b></td><td></td></tr>";
							$i++;
						}
						while($level5 = mysql_fetch_array($level5_res)){
							$bank1_res = mysql_query("select * from bank_account where account_id='".$level5['id']."'");
							$sub2_total = 0;
							if(mysql_numrows($bank1_res) == 0){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 12110101 and account_no <= 12199999");
								while($level6 = mysql_fetch_array($level6_res)){
									$bank2_res = mysql_query("select * from bank_account where account_id='".$level6['id']."'");
									$bank2 = mysql_fetch_array($bank2_res);
									//DEPOSITS
									$dep_res = mysql_query("select sum(amount) as amount from deposit where bank_account='".$bank2['id']."' and date <='".$date."'");
									$dep = mysql_fetch_array($dep_res);
									//WITHDRAWALS
									$with_res = mysql_query("select sum(amount) as amount from withdrawal where bank_account='".$bank2['id']."' and date <='".$date."'");
									$with = mysql_fetch_array($with_res);
									//OTHER INCOME
									$other_res = mysql_query("select sum(amount) as amount from other_income where bank_account='".$bank2['id']."' and date <='".$date."'");
									$other = mysql_fetch_array($other_res);
									//LOANS PAYABLE
									$loans_payable = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '212%' and p.bank_account='".$bank2['id']."' and p.date <= '".$date."'");
									$loans_payable = mysql_fetch_array($loans_payable);
									$loans_payable = ($loans_payable['amount'] != NULL) ? $loans_payable['amount'] : 0;
									//EXPENSES
									$expense_res = mysql_query("select sum(amount) as amount from expense where bank_account='".$bank2['id']."' and date <='".$date."'");
									$expense = mysql_fetch_array($expense_res);
									//PAYABLE PAID
									$payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where bank_account='".$bank2['id']."' and date <='".$date."'");	
									$payable_paid = mysql_fetch_array($payable_paid_res);
									//RECEIVABLE COLLECTED
									$collected_res = mysql_query("select sum(amount) as amount from collected where bank_account='".$bank2['id']."' and date <='".$date."'");
									$collected = mysql_fetch_array($collected_res);
									//DISBURSED LOANS
									$disb_res = mysql_query("select sum(amount) as amount from disbursed where bank_account='".$bank2['id']."' and date <= '".$date."'");
									$disb = mysql_fetch_array($disb_res);
									//PAYMENTS
									$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id  where p.date <= '".$date."' and p.bank_account='".$bank2['id']."'");
									$pay = mysql_fetch_array($pay_res);
									//PENALTIES
									$pen_res = mysql_query("select sum(p.amount) as amount from penalty p join disbursed d on p.loan_id=d.id where p.bank_account='".$bank2['id']."' and p.status='paid' and p.date <='".$date."'");
									$pen = mysql_fetch_array($pen_res);
								
									//SHARES
									$shares_res = mysql_query("select sum(value) as amount from shares where date <='".$date."' and bank_account='".$bank2['id']."'");
									$shares = mysql_fetch_array($shares_res); 
									//RECOVERED
									$rec_res = mysql_query("select sum(r.amount) as amount from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.bank_account='".$bank2['id']."' and r.date <= '".$date."'");
									$rec = mysql_fetch_array($rec_res);			
									//INVESTMENTS 
									$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where date <= '".$date."' and bank_account='".$bank2['id']."'");
									$invest = mysql_fetch_array($invest_res);
									$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
									//DIVIDENDS PAID
									$div_res = mysql_query("select sum(total_amount) as total_amount from share_dividends where date<='".$date."' and bank_account='".$bank1['id']."'");
									$div = mysql_fetch_array($div_res);
									$div_amt = ($div['amount'] != NULL) ? $div['amount'] : 0;
									
									//SOLD INVESTMENTS
									$soldinvest_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where bank_account ='".$bank2['id']."' and date <= '".$date."'");
									$soldinvest = mysql_fetch_array($soldinvest_res);

									//FIXED ASSETS 
									$fixed_res = mysql_query("select sum(initial_value) as amount from fixed_asset where bank_account='".$bank2['id']."' and date <='".$date."'");
									$fixed = mysql_fetch_array($fixed_res);
									$soldasset_res = mysql_query("select sum(amount) as amount from sold_asset where bank_account ='".$bank2['id']."' and date <= '".$date."'");
									$soldasset = mysql_fetch_array($soldasset_res);
									
									//CASH IMPORTED
									$import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id='".$bank2['id']."' and date <='".$date."'");
									$import = mysql_fetch_array($import_res);
									$import_amt = ($import['amount'] ==NULL) ? 0 : $import['amount'];
								
									//CASH EXPORTED
									$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id='".$bank2['id']."' and date <='".$date."'");
									$export = mysql_fetch_array($export_res);
									$export_amt = ($export['amount'] ==NULL) ? 0 : $export['amount'];

									//CAPITAL FUNDS
									$fund_res = mysql_query("select sum(amount) as amount from other_funds where bank_account='".$bank2['id']."' and date <='".$date."'");
									$fund = mysql_fetch_array($fund_res);
									$fund_amt = $fund['amount'];


									$sub2_total = $sub2_total + $collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt + $fund_amt;		
									
								}
							}else{
								$bank1 = mysql_fetch_array($bank1_res);
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
								$import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id=".$bank1['id']." and date <='".$date."'");
								$import = mysql_fetch_array($import_res);
								$import_amt = ($import['amount'] ==NULL) ? 0 : $import['amount'];

								//CASH EXPORTED
								$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id=".$bank1['id']." and date <='".$date."'");
								$export = mysql_fetch_array($export_res);
								$export_amt = ($export['amount'] ==NULL) ? 0 : $export['amount'];

								//CAPITAL FUNDS
								$fund_res = mysql_query("select sum(amount) as amount from other_funds where bank_account='".$bank1['id']."' and date <='".$date."'");
								$fund = mysql_fetch_array($fund_res);
								$fund_amt = $fund['amount'];


								$sub2_total = $collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt + $fund_amt;
							}
							$color = ($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td></td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
							$sub1_total = $sub1_total + $sub2_total;
							$i++;
						}
						$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td></td><td><b>Total ".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						$i++;
						$grand_total += $sub1_total;
					}
				}elseif($level3['account_no']=='122'){
					if($basis == 'Cash Basis')
						continue;
					$rec_res = mysql_query("select sum(amount) as amount from receivable where maturity_date <='".$date."'");
					$rec = mysql_fetch_array($rec_res);
					if($rec['amount'] != NULL){
						$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where c.date <= '".$date."'");
						$col = mysql_fetch_array($col_res);
						$grand_total =  $rec['amount'] - $col['amount'];
					}
				}elseif($level3['account_no'] == '123'){  //FIXED ASSETS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['[account_no']."%' and account_no >= 1231 and account_no <= 1239");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$depp1_total = 0;
						//$fixed_res = mysql_query("select f.id as id, sum(f.initial_value) as amount from fixed_asset f left join sold_asset s on s.asset_id=f.id where f.account_id='".$level4['id']."' and f.date <='".$date."' and (s.date >'".$date."' or s.date is null) group by f.id");
						$fixed_res = mysql_query("select  sum(f.initial_value) as amount from fixed_asset f where f.account_id='".$level4['id']."' and f.id not in (select asset_id from sold_asset where date <= '".$date."') and f.date <='".$date."'");
						$fixed = mysql_fetch_array($fixed_res);
						if($fixed['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 123101 and account_no <= 123999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$depp2_total = 0;
								//$fixed_res = mysql_query("select f.id as id, sum(f.initial_value) as amount from fixed_asset f left join sold_asset s on s.asset_id=f.id where f.account_id='".$level5['id']."' and f.date <='".$date."' and (s.date >'".$date."' or s.date is null) group by f.id");
								$fixed_res = mysql_query("select  sum(f.initial_value) as amount from fixed_asset f where f.account_id='".$level5['id']."' and f.id not in (select asset_id from sold_asset where date <= '".$date."') and f.date <='".$date."'");
								$fixed = mysql_fetch_array($fixed_res);
								if($fixed['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >=12310101 and account_no <= 12399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$fixed_res = mysql_query("select  sum(f.initial_value) as amount from fixed_asset f where f.account_id='".$level6['id']."' and f.id not in (select asset_id from sold_asset where date <= '".$date."') and f.date <='".$date."'");
										$fixed = mysql_fetch_array($fixed_res);
										$sub2_total = $sub2_total + $fixed['amount'];
										$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from  fixed_asset where account_id='".$level6['id']."' and date <= '".$date."') and date <='".$date."'");
										$depp = mysql_fetch_array($depp_res);
										$depp2_total = $depp2_total + $depp['amount'];
									}
								}else{
						
									$sub2_total = $fixed['amount'];
									$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from  fixed_asset where account_id='".$level5['id']."' and date <= '".$date."') and date <='".$date."'");
									$depp = mysql_fetch_array($depp_res);
									$depp2_total = $depp2_total + $depp['amount'];
								}
						
								$color=($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td>".$level5['account_no']."</td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
								$i++;
								$acc_res = mysql_query("select * from accounts where name='Accumulated Depreciation - ".$level5['name']."'");
								$acc = mysql_fetch_array($acc_res);
								$color=($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td>".$acc['account_no']."</td><td>".$acc['name']."</td><td>".number_format($depp2_total, 2)."</td></tr>";
								$i++;
								$net_total = $sub2_total - $depp2_total;	
								$color=($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td></td><td><b>Net ".$acc['name']."</b></td><td>".number_format($net_total, 2)."</td></tr>";
								$sub1_total += $sub2_total;
								$depp1_total += $depp2_total;
								$i++;
							}
						}else{
							$sub1_total = $fixed['amount'];
							$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from  fixed_asset where account_id='".$level4['id']."' and date <= '".$date."') and date <='".$date."'");
							$depp = mysql_fetch_array($depp_res);
							$depp1_total = $depp1_total + $depp['amount'];
						}
						$color=($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color ><td></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";	
						$i++;
						$net_total = $sub1_total - $depp1_total;
						if($level4['name'] != 'Land'){
							$acc_res = mysql_query("select * from accounts where name='Accumulated Depreciation - ".$level4['name']."'");
							$acc = mysql_fetch_array($acc_res);
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color ><td></td><td><b>".$acc['name']."</b></td><td><b>".number_format($depp1_total, 2)."</b></td></tr>";
							$i++;
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td></td><td><b>Net ".$level4['name']."</b></td><td><b>".number_format($net_total, 2)."</b></td></tr>";
							$i++;
						}
						$grand_total = $grand_total + $net_total;
					}
				}
				$level2_total += $grand_total;
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td colspan=2 align=left><b> TOTAL ".$level3['name']."</b></td><td><b>".number_format($grand_total, 2)."</b></td></tr>";	
				$i++;
				if($level3['account_no'] == 111){
					$loss_res = mysql_query("select * from accounts where account_no='112'");
					$loss = mysql_fetch_array($loss_res);
					$color = ($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td><b>(-) ".$loss['account_no']."</b></td><td><b> ".$loss['name']."</b></td><td><b>".number_format($grand_loss, 2)."</b></td></tr>";
					$i++;
					$color = ($i%2 == 0) ? "lightgrey" : "white";
					$content .="<tr bgcolor=$color><td><b></b></td><td><b> TOTAL NET LOAN PORTFOLIO</b></td><td><b>".number_format(($grand_total - $grand_loss), 2)."</b></td></tr>";
					$i++;
					$level2_total -= $grand_loss;
					//$grand_loss = 0;
				}
			}			
			$color = ($i%2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color><td colspan=2><font size=4pt><b>TOTAL  ".$level2['name']."</b></font></td><td><font size=4pt><b>".number_format($level2_total, 2)."</b></font></td></tr>";
			$i++;
			$assets += $level2_total;
		}
		$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr bgcolor=$color><td colspan=2><font size=5pt><b>TOTAL ASSETS</b></font></td><td><font size=5pt><b>".number_format($assets, 2)."</b></font></td></tr>";
		$i++;
		
//LIABILITIES
		$capital = 0;
		$liabilities = 0;
		$content .= "<tr bgcolor=#cdcdcd><td colspan=3><font size=5pt><b>LIABILITIES</b></font></td></tr>";
		$level2_res = mysql_query("select * from accounts where account_no >=21 and account_no <= 39 order by account_no");
		while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
			if($level2['account_no'] ==31){
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td colspan=2><font size=5pt><b>TOTAL LIABILITIES</b></font></td><td><font size=5pt><b>".number_format($liabilities, 2)."</b></font></td></tr>";
				$i++;
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .="<tr bgcolor=$color><td colspan=3><font size=5pt><b>CAPITAL</b></font></td></tr>";
				$i++;
			}
			$color = ($i%2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color><td colspan=2><font size=4pt><b>".$level2['name']."</b></font></td><td></td></tr>";
			$level3_res = mysql_query("select * from accounts where account_no >=211 and account_no <= 399 and account_no like '".$level2['account_no']."%' order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td colspan=2 align=left><b>".$level3['name']."</b></td><td></td></tr>";
				$i++;
				$grand_total = 0;
				if($level3['account_no'] == '211'){  //SAVINGS & DEPOSITS
					
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
							//OTHER DEDUCTIONS
							$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') and o.date <= '".$date."'"));
							$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;
							//MONTHLY ACCOUNT CHARGES
							$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts mem on c.memaccount_id=mem.id join savings_product p on mem.saveproduct_id=p.id where c.date <='".$date."' and p.id='".$prod['id']."' and p.type='free'");
							$charge = mysql_fetch_array($charge_res);
							//OFFSETTING FROM SAVINGS
							$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' and p.date<= '".$date."'");
							$pay = mysql_fetch_array($pay_res);
							$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;
							
							//SHARES OFFSET FROM SAVINGS
							$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') and s.date <= '".$date."'"));
							$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;

							$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
							$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
							$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
							
							$sub1_total = $dep_amt + $int_amt - $with_amt - $income_amt - $charge_amt- $pay_amt - $share_amt;  
						}else{
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 211101 and account_no <= 211999");
							while($level5 = mysql_fetch_array($level5_res)){
								if($level5['name'] == 'Compulsory Shares')
									continue;
								$sub2_total =0;
								$prod_res = mysql_query("select * from savings_product where account_id='".$level5['id']."'");
								if(mysql_numrows($prod_res) > 0){
									$prod = mysql_fetch_array($prod_res);
									//$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and d.date<= '".$date."'");
									$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and d.date<= '".$date."'");
									$dep = mysql_fetch_array($dep_res);
									$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.id='".$prod['id']."' and p.type='free' and w.date<= '".$date."'");
									$with = mysql_fetch_array($with_res);

									$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and i.date<= '".$date."'");
									$int = mysql_fetch_array($int_res);
									$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
									//OTHER DEDUCTIONS
									$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') and o.date <= '".$date."'"));
									$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;
									//MONTHLY ACCOUNT CHARGES
									$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts mem on c.memaccount_id=mem.id join savings_product p on mem.saveproduct_id=p.id where c.date <='".$date."' and p.id='".$prod['id']."' and p.type='free'");
									$charge = mysql_fetch_array($charge_res);
									//OFFSETTING FROM SAVINGS
									$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' and p.date<= '".$date."'");
									$pay = mysql_fetch_array($pay_res);
									$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;
									$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;

									//SHARES OFFSET FROM SAVINGS
									$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') and s.date <= '".$date."'"));
									$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;

									
									$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
									$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
									$sub2_total = $dep_amt + $int_amt - $with_amt - $charge_amt - $income_amt - $pay_amt - $share_amt; // + $int['amount'];
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
											$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and i.date<= '".$date."'");
											$int = mysql_fetch_array($int_res);
											$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
											//OTHER DEDUCTIONS
											$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') and o.date <= '".$date."'"));
											$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;
											//MONTHLY ACCOUNT CHARGES
											$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts mem on c.memaccount_id=mem.id join savings_product p on mem.saveproduct_id=p.id where c.date <='".$date."' and p.id='".$prod['id']."' and p.type='free'");
											$charge = mysql_fetch_array($charge_res);
											$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
											//OFFSETTING FROM SAVINGS
											$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' and p.date<= '".$date."'");
											$pay = mysql_fetch_array($pay_res);
											$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;

											//SHARES OFFSET FROM SAVINGS
											$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') and s.date <= '".$date."'"));
											$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;

										//	$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and i.date<= '".$date."'");
										//	$int = mysql_fetch_array($int_res);
											$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
											$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
											$sub2_total = $sub2_total + $dep_amt + $int_amt - $with_amt - $charge_amt - $income_amt - $share_amt;  // + $int['amount'];
										}
									}
									$sub1_total = $sub1_total + $sub2_total;
								}
								$color = ($i%2 == 0) ? "lightgrey" : "white";
								$content .="<tr bgcolor=$color><td></td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
								$i++;
							}
						}
						$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .="<tr bgcolor=$color><td><b></b></td><td><b>Total ".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						$i++;
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
						//INTEREST GIVEN TO MEMBERS IS ALREADY PAID OUT AND IS NOT INTEREST PAYABLE
						$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.name like '%Savings%' and i.date <='".$date."'");
						
						$int = mysql_fetch_array($int_res);
						
						$sub1_total = $int['amount'];
						*/
						;
					}elseif($level4['name'] == 'Interest Payable on Time Deposits'){
						/* 
						//INTEREST GIVEN TO MEMBERS IS ALREADY PAID OUT AND IS NOT INTEREST PAYABLE
						$int_res = mysql_query("select sum(amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.name like '%Time%' and i.date <='".$date."'");
						$int = mysql_fetch_array($int_res);
						$sub1_total = $int['amount'];
						*/
						;
					}elseif($level4['name'] == 'Dividends Payable'){
						$div_res = mysql_query("select sum(d.amount) as amount from dividends d join share_dividends s on d.share_dividend_id=s.id where s.bank_account=0 and s.date<= '".$date."'");
						$div = mysql_fetch_array($div_res);
						$dividends = intval($div['amount']);
						$sub1_total = $dividends;
					//	$interest_awarded += $sub1_total;
					}else{
						
						//if($basis == 'Cash Basis' && !preg_match("/^212/", $level4['account_no']))
						//	continue;
						
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
										//$add = ($basis == 'Cash Basis') ? $paid['amount'] : $pay['amount'] - $paid['amount'];
										//$sub2_total += $add;
										$sub2_total = $sub2_total + $pay['amount'] - $paid['amount'];
									}
								}else{
									$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id where paid.date <= '".$date."' and p.account_id='".$level5['id']."'");
									$paid = mysql_fetch_array($paid_res);
									$add = ($basis == 'Cash Basis') ? $paid['amount'] : $pay['amount'] - $paid['amount'];
									
									$sub2_total = $pay['amount'] - $paid['amount'];
								}
								$color = ($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td></td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
								$i++;
							$sub1_total += $sub2_total;
							}
						}else{
							$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id where paid.date <= '".$date."' and p.account_id='".$level4['id']."'");
							$paid = mysql_fetch_array($paid_res);
							//$add = ($basis == 'Cash Basis') ? $paid['amount'] : $pay['amount'] - //$paid['amount'];
							//$sub2_total =$add;
							$sub1_total = $pay['amount'] - $paid['amount'];
						}
					}
					$grand_total = $grand_total + $sub1_total;
					$level2_total += $sub1_total;
					$liabilities += $sub1_total;
					$color = ($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td><b></b></td><td><b>Total ".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
					$i++;
				}
			}elseif($level3['account_no'] >= '311' && $level3['account_no'] <= '399'){
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 3111 and account_no <= 3999");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['account_no'] == '3111'){
						$share_res = mysql_query("select sum(value) as value from shares where date <='".$date."'");
						$share = mysql_fetch_array($share_res);
						$level42_res = mysql_query("select * from accounts where name like 'Member Shares'");
						$level42 = mysql_fetch_array($level42_res);
						$sub1_total = $share['value'];
						$sub1_total = ($sub1_total > 0) ? $sub1_total : 0;
						$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td><b></b></td><td><b>Total ".$level42['name']."</b></td><td>".number_format($sub1_total, 2)."</td></tr>";
						$i++;
					}elseif($level4['account_no'] == '3312'){
						$sub1_total -= $grand_loss;
						$sub1_total += net_cummulated_income('', $date, $basis);
						$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td><b></b></td><td><b>Total ".$level4['name']."</b></td><td>".number_format($sub1_total, 2)."</td></tr>";
						$i++;
						//$resp->AddAlert(net_cummulated_income($date, $basis));
					}else{
						$invest_res = mysql_query("select sum(amount) as amount from other_funds where account_id='".$level4['id']."' and date <= '".$date."' and id not in (select fund_id from payable where date <= '".$date."' and fund_id<>'0')");
						$invest = mysql_fetch_array($invest_res);
						if($invest['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 321101 and account_no <= 339999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$invest_res = mysql_query("select sum(amount) as amount from other_funds where account_id='".$level5['id']."' and date <= '".$date."'  and id not in (select fund_id from payable where date <= '".$date."' and fund_id<>'0')");
								$invest = mysql_fetch_array($invest_res);
								if($invest['amount'] == NULL){
									$level6_res = mysql_query("select  * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 32110101 and account_no <= 33999999");
									while($level6 = mysql_fetch_array($level6_res)){
										$invest_res = mysql_query("select sum(amount) as amount from other_funds where account_id='".$level6['id']."' and date <= '".$date."'   and id not in (select fund_id from payable where date <= '".$date."' and fund_id<>'0')");
										$invest = mysql_fetch_array($invest_res);
										$sub2_total = $sub2_total + $invest['amount'];
									}
								}else
									$sub2_total = $invest['amount'];
								$color = ($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td></td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
								$i++;
								$sub1_total = $sub1_total + $sub2_total;
							}
						}else{
							$sub1_total = $invest['amount'];
						}
						$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td><b></b></td><td><b>Total ".$level4['name']."</b></td><td>".number_format($sub1_total, 2)."</td></tr>";
						$i++;
					}
					$color = ($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td><b></b></td><td><b>Total ".$level4['name']."</b></td><td>".number_format($sub1_total, 2)."</td></tr>";
					$i++;
					$grand_total += $sub1_total;
					$level2_total += $sub1_total; 
					$capital += $sub1_total;
				}
			}
			$color = ($i%2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color><td colspan=2 align=left><b>TOTAL  ".$level3['name']."</b></td><td><b>".number_format($grand_total, 2)."</b></td></tr>";
			$i++;
		}
		$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr bgcolor=$color><td colspan=2><font size=4pt><b> ".$level2['name']."</b></font></td><td><font size=4pt><b>".number_format($level2_total, 2)."</b></font></td></tr>";
		$i++;
	}
	$color = ($i%2 == 0) ? "lightgrey" : "white";
	$content .= "<tr bgcolor=$color><td colspan=2><font size=5pt><b>TOTAL CAPITAL</b></font></td><td><font size=5pt><b>".number_format($capital, 2)."</b></font></td></tr>";
	$i++;
	$color = ($i%2 == 0) ? "lightgrey" : "white";
	$content .="<tr bgcolor=$color><td colspan=2><font size=5pt><b>TOTAL CAPITAL & LIABILITIES</b></font></td><td><font size=5pt><b>".number_format(($capital+$liabilities), 2)."</b></font></td></tr>";
	$i++;
	$color = ($i%2 == 0) ? "lightgrey" : "white";
	$net_assets = $assets - $capital - $liabilities;
	$net_assets = ($net_assets >= 0) ? number_format($net_assets, 2) : "(".number_format((0 - $net_assets), 2).")"; 
	$content .="<tr bgcolor=$color><td colspan=2><font size=5pt><b>NET ASSETS AVAILABLE FOR BENEFIT</b></font></td><td><font size=5pt><b>".$net_assets."</b></font></td></tr>";
		$content .= "</table>";
	
	/*end jiba jeba*/
	
	$finish=microtime(true);
	$time=round($finish-$start);
	return $content;
}
#balance_sheet3
function balance_sheet3(){
	$date=$this->getDateto();
	$content="<table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>";
	$content.="<tr bgcolor=lightgrey><td clospan=2></td><td align=center></td><td><b>".$this->year."</b></td></tr><tr bgcolor=lightgrey><td colspan=4 align=center><font size=5pt><b>ASSETS</b></font></td></tr>";
		$level2_res= mysql_query("select * from accounts where account_no >10 and account_no <20 order by account_no");
		$assets = 0;
		$prev_assets=0;
		$i=0;
		while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
			$color = ($i%2 == 0) ? "white" : "lightgrey";
			$content .= "<tr bgcolor=$color><td colspan=2><font size=4pt><b>".$level2['name']."</b></font></td><td></td></tr>";
			$i++;
			$level3_res = mysql_query("select * from accounts where account_no >=111 and account_no <= 199 and account_no like '".$level2['account_no']."%' and account_no not in (124, 112) order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td colspan=2 align=left><b>".$level3['name']."</b></td><td></td></tr>";
				$i++;
				$grand_total = 0;
				if($level3['account_no'] == '111'){  //LOANS
					$grand_loss = 0;
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1111 and account_no <= 1119");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$loss_total=0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 111101 and account_no <= 111999");
						if( mysql_numrows($level5_res) > 0){
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td></td><td><b>".$level4['name']."</b></td><td></td></tr>";
							$i++;
						}
						while($level5 = mysql_fetch_array($level5_res)){
							$loan1_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level5['id']."' and d.date <= '".$date."'");
							$loan1 = mysql_fetch_array($loan1_res);
							
							if($loan1['amount'] == NULL){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 11110101 and account_no <= 11199999");
								if(mysql_numrows($level6_res) >0){
									
									$sub2_total = 0;
									$loss2_total = 0;
									while($level6 = mysql_fetch_array($level6_res)){
										$loan2_res = mysql_query("select sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level6['id']."' and d.date <= '".$date."'");
										$loan2 = mysql_fetch_array($loan2_res);
										//$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level6['id']."' and d.date <= '".$date."' and pay.date <='".$date."'");
										$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level6['id']."' and pay.date <='".$date."'");
										$pay = mysql_fetch_array($pay_res);
										$loan2_amt = ($loan2['amount'] == NULL) ? 0 : $loan2['amount'];
										
										$loss_res = mysql_query("select sum(w.amount) as amount from written_off w join disbursed d on w.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where w.date <= '".$date."' and p.account_id='".$level6['id']."'");
										$loss = mysql_fetch_array($loss_res);
										$loss2_total = $loss2_total + $loss['amount'];
										$sub2_total = $sub2_total + $loan2_amt - $pay['amount'] - $loss['amount'];
									}
								} 
							}else{
								//$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level5['id']."' and d.date <= '".$date."' and pay.date <='".$date."'");
								$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level5['id']."'  and pay.date <='".$date."'");

								$pay = mysql_fetch_array($pay_res);
								$loss_res = mysql_query("select sum(w.amount) as amount from written_off w join disbursed d on w.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where w.date <= '".$date."' and p.account_id='".$level5['id']."'");
								$loss = mysql_fetch_array($loss_res);
								$loss2_total = $loss['amount'];
								$sub2_total = $loan1['amount'] - $pay['amount'] - $loss['amount'];
							}
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td></td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
							$i++;
							
							$sub1_total = $sub1_total + $sub2_total;
							$loss_total = $loss_total + $loss2_total;
							$sub2_total = 0;
						}
						$color=($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						 $i++;
						/* $written_res = mysql_query("select * from accounts where account_no like '112%' and name like '%".$level4['name']."'");
						$written = mysql_fetch_array($written_res);
						$color=($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color ><td><b>".$written['account_no']."</b></td><td><b>".$written['name']."</b></td><td><b>".$loss1_total."</b></td></tr>";
						$i++;
						*/

						$grand_total = $grand_total + $sub1_total;
						//$grand_loss = $grand_loss + $loss_total;
					}
					//LOAN LOSS ALLOWANCES
					$grand_loss=0;
					$prov_res = mysql_query("select * from provissions order by percent asc");
					/*
					while($prov = mysql_fetch_array($prov_res)){
						preg_match("/(\d)[_](\d)$/", $prov['range'], $arg);
						$interval = ($arg[1] <>180) ? ($arg[2] - $arg[1]) : 180;
						$sched_res = mysql_query("select sum(princ_amt) as amount from schedule where date < DATE_SUB('".$date."', INTERVAL ".$interval." DAY)");
						$sched = mysql_fetch_array($sched_res);
						$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
						$paid_res = mysql_query("select sum(princ_amt) as amount from payment where date <='".$date."'");
						$paid = mysql_fetch_array($paid_res);
						$paid_amt = ($paid['amount'] == NULL) ? 0 : $paid['amount'];
						$over_180 = $sched_amt - $paid_amt;
						$over_180 = ($over_180 > 0) ? $over_180 : 0;
						
						$loss180_over = floor($over_180 * $prov['percent']/100);
						$grand_loss += $loss180_over;
					}
					*/
					while($prov = mysql_fetch_array($prov_res)){
						preg_match("/(\d*)[_](\d*)$/", $prov['range'], $arg);
						$interval = ($arg[1] <>180) ? ($arg[2] - $arg[1]) : 180;
						if($arg[2]==''){
							$arg[1] =180;
							$arg[2]=660;     //UP TO 22 MONTHS OF ARREARS, THE ASSUMPTION IS THAT BEYOND THAT, THE LOAN WILL HAVE BEEN WRITTEN OFF
						}
						
						$over_180 =0;
						$sched_res = mysql_query("select sum(princ_amt) as amount, loan_id as loan_id from schedule where date <= DATE_SUB('".$date."', INTERVAL ".$arg[1]." DAY) and date >= DATE_SUB('".$date."', INTERVAL ".$arg[2]." DAY) and loan_id not in (select loan_id from written_off where date <= DATE_SUB('".$date."', INTERVAL ".$arg[1]." DAY)) group by loan_id");
					
					
						while($sched = mysql_fetch_array($sched_res)){
							$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
							$all = mysql_fetch_array(mysql_query("select sum(princ_amt) as amount from schedule where date <= DATE_SUB('".$date."', INTERVAL ".$arg[2]." DAY)  and loan_id='".$sched['loan_id']."'"));
							$paid_res = mysql_query("select sum(princ_amt) as amount from payment where date <='".$date."' and loan_id='".$sched['loan_id']."'");
							$paid = mysql_fetch_array($paid_res);
							$paid_amt = ($paid['amount'] == NULL) ? 0 : $paid['amount'];
							$arrears = $all['amount'] - $paid_amt;
							if($arrears < 0){
								$arrears =0;
							}
							
							$arrears = ($arrears > $sched_amt) ? $sched_amt : $arrears;
							$over_180  += $arrears;

						}	
						$loss180_over = floor($over_180 * $prov['percent']/100);
						$grand_loss += $loss180_over;
					}
					//MANUAL LOAN LOSS PROVISSION
					$static = mysql_fetch_array(mysql_query("select sum(amount) as amount from otherloan_loss where date <= '".$date."'"));
					$static_amt = ($static['amount'] == NULL) ? 0 : $static['amount'];
					$grand_loss = $grand_loss + $static_amt;
					$grand_net = $grand_total - $grand_loss;  //SUBTRACT LOAN LOSS PROVISIONS


					//$grand_loss = $loss1_30 + $loss31_60 + $loss61_90 + $loss91_120 + $loss121_180 + $loss180_over;
				}elseif($level3['account_no'] >= 113 && $level3['account_no'] <= 119){   //INVESTMENTS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1131 and account_no <= 1199");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level4['id']."' and date <='".$date."'");
						$invest = mysql_fetch_array($invest_res);
						if($invest['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 113101 and account_no <= 119999");
							if( mysql_numrows($level5_res) > 0){
								$color=($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td></td></tr>";
								$i++;
							}
							while($level5 = mysql_fetch_array($level5_res)){
								$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level5['id']."' and date<='".$date."'");
								$invest = mysql_fetch_array($invest_res);
								$sub2_total=0;
								if($invest['amount'] ==NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 11310101 and account_no <= 11399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level6['id']."' and date <='".$date."'");		
										$invest = mysql_fetch_array($invest_res);
										//$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level6['id']."' and date <='".$date."' order by date desc limit 1"));
										$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level6['id']."' and date <='".$date."'");
										$sold = mysql_fetch_array($sold_res);
										$sold_amt = ($sold['quantity'] == NULL) ? 0 : $sold['amount'];
										$sub2_total = $sub2_total + $invest['amount'] - $sold_amt;
									}
								}else{
									//$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level5['id']."' and date <='".$date."' order by date desc limit 1"));
									$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level5['id']."' and date <='".$date."'");
									$sold = mysql_fetch_array($sold_res);
									$sold_amt = ($sold['quantity'] == NULL) ? 0 : $sold['amount'];
									$sub2_total = $invest['amount'] - $sold_amt;
									//$sub2_total = $invest['amount'];
								}
								$sub1_total = $sub1_total + $sub2_total;
							}
						}else{
							//$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level4['id']."' and date <='".$date."' order by date desc limit 1"));
							$sold_res = mysql_query("select sum(quantity * purchase_price) as amount from sold_invest where account_id='".$level4['id']."' and date <='".$date."'");
							$sold = mysql_fetch_array($sold_res);
							$sold_amt = ($sold['amount'] == NULL) ? 0 : $sold['amount'];
							$sub1_total = $invest['amount'] - $sold_amt;
						}
						$color=($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color ><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						$i++;
						$grand_total = $grand_total + $sub1_total;
					}
				}elseif($level3['account_no'] >= 125){   //INVESTMENTS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1251 and account_no <= 1259");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 125101 and account_no <= 125999");
						if( mysql_numrows($level5_res) > 0){
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td></td></tr>";
							$i++;
						}
						while($level5 = mysql_fetch_array($level5_res)){
							$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level5['id']."' and date <= '".$date."'");
							$invest = mysql_fetch_array($invest_res);
							$sub2_total=0;
							if($invest['amount'] == NULL){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 12510101 and account_no <= 11399999");
								while($level6 = mysql_fetch_array($level6_res)){
									$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level6['id']."' and date <= '".$date."' and id not in (select investment_id from sold_invest where date<='".$date."')");		
									$invest = mysql_fetch_array($invest_res);

									$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level5['id']."' and date <='".$date."' order by date desc limit 1"));
									$sold_res = mysql_query("select sum(quantity) as quantity from sold_invest where account_id='".$level5['id']."' and date <='".$date."'");
									$sold = mysql_fetch_array($sold_res);
									$sold_amt = ($sold['quantity'] == NULL) ? 0 : ($sold['quantity'] * $unit['amount']);
									$sub2_total =$sub2_total + $invest['amount'] - $sold_amt;		

									$sub2_total = $sub2_total + $invest['amount'];
								}
							}else{
								$unit = mysql_fetch_array(mysql_query("select amount from investments where account_id='".$level5['id']."' and date <='".$date."' order by date desc limit 1"));
								$sold_res = mysql_query("select sum(quantity) as quantity from sold_invest where account_id='".$level5['id']."' and date <='".$date."'");
								$sold = mysql_fetch_array($sold_res);
								$sold_amt = ($sold['quantity'] == NULL) ? 0 : ($sold['quantity'] * $unit['amount']);
								$sub2_total = $invest['amount'] - $sold_amt;		
							}
							$sub1_total = $sub1_total + $sub2_total;
						}
						$color=($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color ><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						$i++;
						$grand_total = $grand_total + $sub1_total;
					}
				}elseif($level3['account_no'] == 121){
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >=1211 and account_no <= 1219");
					$grand_total = 0;
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 121101 and account_no <= 121999");
						if( mysql_numrows($level5_res) > 0){
							$color= ($i%2 == 0) ? lightgrey : "white";
							$content .= "<tr bgcolor=$color><td><b></b></td><td><b>".$level4['name']."</b></td><td></td></tr>";
							$i++;
						}
						while($level5 = mysql_fetch_array($level5_res)){
							$bank1_res = mysql_query("select * from bank_account where account_id='".$level5['id']."'");
							$sub2_total = 0;
							if(mysql_numrows($bank1_res) == 0){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 12110101 and account_no <= 12199999");
								while($level6 = mysql_fetch_array($level6_res)){
									$bank2_res = mysql_query("select * from bank_account where account_id='".$level6['id']."'");
									$bank2 = mysql_fetch_array($bank2_res);
									//DEPOSITS
									$dep_res = mysql_query("select sum(amount) as amount from deposit where bank_account='".$bank2['id']."' and date <='".$date."'");
									$dep = mysql_fetch_array($dep_res);
									//WITHDRAWALS
									$with_res = mysql_query("select sum(amount) as amount from withdrawal where bank_account='".$bank2['id']."' and date <='".$date."'");
									$with = mysql_fetch_array($with_res);
									//OTHER INCOME
									$other_res = mysql_query("select sum(amount) as amount from other_income where bank_account='".$bank2['id']."' and date <='".$date."'");
									$other = mysql_fetch_array($other_res);
									//LOANS PAYABLE
									$loans_payable = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '212%' and p.bank_account='".$bank2['id']."' and p.date <= '".$date."'");
									$loans_payable = mysql_fetch_array($loans_payable);
									$loans_payable = ($loans_payable['amount'] != NULL) ? $loans_payable['amount'] : 0;
									//EXPENSES
									$expense_res = mysql_query("select sum(amount) as amount from expense where bank_account='".$bank2['id']."' and date <='".$date."'");
									$expense = mysql_fetch_array($expense_res);
									//PAYABLE PAID
									$payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where bank_account='".$bank2['id']."' and date <='".$date."'");	
									$payable_paid = mysql_fetch_array($payable_paid_res);
									//RECEIVABLE COLLECTED
									$collected_res = mysql_query("select sum(amount) as amount from collected where bank_account='".$bank2['id']."' and date <='".$date."'");
									$collected = mysql_fetch_array($collected_res);
									//DISBURSED LOANS
									$disb_res = mysql_query("select sum(amount) as amount from disbursed where bank_account='".$bank2['id']."' and date <= '".$date."'");
									$disb = mysql_fetch_array($disb_res);
									//PAYMENTS
									$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id  where p.date <= '".$date."' and p.bank_account='".$bank2['id']."'");
									$pay = mysql_fetch_array($pay_res);
									//PENALTIES
									$pen_res = mysql_query("select sum(p.amount) as amount from penalty p join disbursed d on p.loan_id=d.id where p.bank_account='".$bank2['id']."' and p.status='paid' and p.date <='".$date."'");
									$pen = mysql_fetch_array($pen_res);
								
									//SHARES
									$shares_res = mysql_query("select sum(value) as amount from shares where date <='".$date."' and bank_account='".$bank2['id']."'");
									$shares = mysql_fetch_array($shares_res); 
									//RECOVERED
									$rec_res = mysql_query("select sum(r.amount) as amount from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.bank_account='".$bank2['id']."' and r.date <= '".$date."'");
									$rec = mysql_fetch_array($rec_res);			
									//INVESTMENTS 
									$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where date <= '".$date."' and bank_account='".$bank2['id']."'");
									$invest = mysql_fetch_array($invest_res);
									$invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
									//DIVIDENDS PAID
									$div_res = mysql_query("select sum(total_amount) as total_amount from share_dividends where date<='".$date."' and bank_account='".$bank1['id']."'");
									$div = mysql_fetch_array($div_res);
									$div_amt = ($div['amount'] != NULL) ? $div['amount'] : 0;
									
									//SOLD INVESTMENTS
									$soldinvest_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where bank_account ='".$bank2['id']."' and date <= '".$date."'");
									$soldinvest = mysql_fetch_array($soldinvest_res);

									//FIXED ASSETS 
									$fixed_res = mysql_query("select sum(initial_value) as amount from fixed_asset where bank_account='".$bank2['id']."' and date <='".$date."'");
									$fixed = mysql_fetch_array($fixed_res);
									$soldasset_res = mysql_query("select sum(amount) as amount from sold_asset where bank_account ='".$bank2['id']."' and date <= '".$date."'");
									$soldasset = mysql_fetch_array($soldasset_res);
									
									//CASH IMPORTED
									$import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id='".$bank2['id']."' and date <='".$date."'");
									$import = mysql_fetch_array($import_res);
									$import_amt = ($import['amount'] ==NULL) ? 0 : $import['amount'];
								
									//CASH EXPORTED
									$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id='".$bank2['id']."' and date <='".$date."'");
									$export = mysql_fetch_array($export_res);
									$export_amt = ($export['amount'] ==NULL) ? 0 : $export['amount'];

									//CAPITAL FUNDS
									$fund_res = mysql_query("select sum(amount) as amount from other_funds where bank_account='".$bank2['id']."' and date <='".$date."'");
									$fund = mysql_fetch_array($fund_res);
									$fund_amt = $fund['amount'];


									$sub2_total = $sub2_total + $collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt + $fund_amt;		
									
								}
							}else{
								$bank1 = mysql_fetch_array($bank1_res);
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
								$import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id=".$bank1['id']." and date <='".$date."'");
								$import = mysql_fetch_array($import_res);
								$import_amt = ($import['amount'] ==NULL) ? 0 : $import['amount'];

								//CASH EXPORTED
								$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id=".$bank1['id']." and date <='".$date."'");
								$export = mysql_fetch_array($export_res);
								$export_amt = ($export['amount'] ==NULL) ? 0 : $export['amount'];

								//CAPITAL FUNDS
								$fund_res = mysql_query("select sum(amount) as amount from other_funds where bank_account='".$bank1['id']."' and date <='".$date."'");
								$fund = mysql_fetch_array($fund_res);
								$fund_amt = $fund['amount'];


								$sub2_total = $collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt + $fund_amt;
							}
							$color = ($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td></td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
							$sub1_total = $sub1_total + $sub2_total;
							$i++;
						}
						$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td></td><td><b>Total ".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						$i++;
						$grand_total += $sub1_total;
					}
				}elseif($level3['account_no']=='122'){
					if($basis == 'Cash Basis')
						continue;
					$rec_res = mysql_query("select sum(amount) as amount from receivable where maturity_date <='".$date."'");
					$rec = mysql_fetch_array($rec_res);
					if($rec['amount'] != NULL){
						$col_res = mysql_query("select sum(c.amount) as amount from collected c join receivable r on c.receivable_id=r.id where c.date <= '".$date."'");
						$col = mysql_fetch_array($col_res);
						$grand_total =  $rec['amount'] - $col['amount'];
					}
				}elseif($level3['account_no'] == '123'){  //FIXED ASSETS
					$level4_res = mysql_query("select * from accounts where account_no like '".$level3['[account_no']."%' and account_no >= 1231 and account_no <= 1239");
					while($level4 = mysql_fetch_array($level4_res)){
						$sub1_total = 0;
						$depp1_total = 0;
						//$fixed_res = mysql_query("select f.id as id, sum(f.initial_value) as amount from fixed_asset f left join sold_asset s on s.asset_id=f.id where f.account_id='".$level4['id']."' and f.date <='".$date."' and (s.date >'".$date."' or s.date is null) group by f.id");
						$fixed_res = mysql_query("select  sum(f.initial_value) as amount from fixed_asset f where f.account_id='".$level4['id']."' and f.id not in (select asset_id from sold_asset where date <= '".$date."') and f.date <='".$date."'");
						$fixed = mysql_fetch_array($fixed_res);
						if($fixed['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 123101 and account_no <= 123999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$depp2_total = 0;
								//$fixed_res = mysql_query("select f.id as id, sum(f.initial_value) as amount from fixed_asset f left join sold_asset s on s.asset_id=f.id where f.account_id='".$level5['id']."' and f.date <='".$date."' and (s.date >'".$date."' or s.date is null) group by f.id");
								$fixed_res = mysql_query("select  sum(f.initial_value) as amount from fixed_asset f where f.account_id='".$level5['id']."' and f.id not in (select asset_id from sold_asset where date <= '".$date."') and f.date <='".$date."'");
								$fixed = mysql_fetch_array($fixed_res);
								if($fixed['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >=12310101 and account_no <= 12399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$fixed_res = mysql_query("select  sum(f.initial_value) as amount from fixed_asset f where f.account_id='".$level6['id']."' and f.id not in (select asset_id from sold_asset where date <= '".$date."') and f.date <='".$date."'");
										$fixed = mysql_fetch_array($fixed_res);
										$sub2_total = $sub2_total + $fixed['amount'];
										$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from  fixed_asset where account_id='".$level6['id']."' and date <= '".$date."') and date <='".$date."'");
										$depp = mysql_fetch_array($depp_res);
										$depp2_total = $depp2_total + $depp['amount'];
									}
								}else{
						
									$sub2_total = $fixed['amount'];
									$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from  fixed_asset where account_id='".$level5['id']."' and date <= '".$date."') and date <='".$date."'");
									$depp = mysql_fetch_array($depp_res);
									$depp2_total = $depp2_total + $depp['amount'];
								}
						
								$color=($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td>".$level5['account_no']."</td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
								$i++;
								$acc_res = mysql_query("select * from accounts where name='Accumulated Depreciation - ".$level5['name']."'");
								$acc = mysql_fetch_array($acc_res);
								$color=($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td>".$acc['account_no']."</td><td>".$acc['name']."</td><td>".number_format($depp2_total, 2)."</td></tr>";
								$i++;
								$net_total = $sub2_total - $depp2_total;	
								$color=($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td></td><td><b>Net ".$acc['name']."</b></td><td>".number_format($net_total, 2)."</td></tr>";
								$sub1_total += $sub2_total;
								$depp1_total += $depp2_total;
								$i++;
							}
						}else{
							$sub1_total = $fixed['amount'];
							$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from  fixed_asset where account_id='".$level4['id']."' and date <= '".$date."') and date <='".$date."'");
							$depp = mysql_fetch_array($depp_res);
							$depp1_total = $depp1_total + $depp['amount'];
						}
						$color=($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color ><td></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";	
						$i++;
						$net_total = $sub1_total - $depp1_total;
						if($level4['name'] != 'Land'){
							$acc_res = mysql_query("select * from accounts where name='Accumulated Depreciation - ".$level4['name']."'");
							$acc = mysql_fetch_array($acc_res);
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color ><td></td><td><b>".$acc['name']."</b></td><td><b>".number_format($depp1_total, 2)."</b></td></tr>";
							$i++;
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td></td><td><b>Net ".$level4['name']."</b></td><td><b>".number_format($net_total, 2)."</b></td></tr>";
							$i++;
						}
						$grand_total = $grand_total + $net_total;
					}
				}
				$level2_total += $grand_total;
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td colspan=2 align=left><b> TOTAL ".$level3['name']."</b></td><td><b>".number_format($grand_total, 2)."</b></td></tr>";	
				$i++;
				if($level3['account_no'] == 111){
					$loss_res = mysql_query("select * from accounts where account_no='112'");
					$loss = mysql_fetch_array($loss_res);
					$color = ($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td><b>(-) ".$loss['account_no']."</b></td><td><b> ".$loss['name']."</b></td><td><b>".number_format($grand_loss, 2)."</b></td></tr>";
					$i++;
					$color = ($i%2 == 0) ? "lightgrey" : "white";
					$content .="<tr bgcolor=$color><td><b></b></td><td><b> TOTAL NET LOAN PORTFOLIO</b></td><td><b>".number_format(($grand_total - $grand_loss), 2)."</b></td></tr>";
					$i++;
					$level2_total -= $grand_loss;
					//$grand_loss = 0;
				}
			}			
			$color = ($i%2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color><td colspan=2><font size=4pt><b>TOTAL  ".$level2['name']."</b></font></td><td><font size=4pt><b>".number_format($level2_total, 2)."</b></font></td></tr>";
			$i++;
			$assets += $level2_total;
		}
		$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr bgcolor=$color><td colspan=2><font size=5pt><b>TOTAL ASSETS</b></font></td><td><font size=5pt><b>".number_format($assets, 2)."</b></font></td></tr>";
		$i++;
		
//LIABILITIES
		$capital = 0;
		$liabilities = 0;
		$content .= "<tr bgcolor=#cdcdcd><td colspan=3><font size=5pt><b>LIABILITIES</b></font></td></tr>";
		$level2_res = mysql_query("select * from accounts where account_no >=21 and account_no <= 39 order by account_no");
		while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
			if($level2['account_no'] ==31){
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td colspan=2><font size=5pt><b>TOTAL LIABILITIES</b></font></td><td><font size=5pt><b>".number_format($liabilities, 2)."</b></font></td></tr>";
				$i++;
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .="<tr bgcolor=$color><td colspan=3><font size=5pt><b>CAPITAL</b></font></td></tr>";
				$i++;
			}
			$color = ($i%2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color><td colspan=2><font size=4pt><b>".$level2['name']."</b></font></td><td></td></tr>";
			$level3_res = mysql_query("select * from accounts where account_no >=211 and account_no <= 399 and account_no like '".$level2['account_no']."%' order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td colspan=2 align=left><b>".$level3['name']."</b></td><td></td></tr>";
				$i++;
				$grand_total = 0;
				if($level3['account_no'] == '211'){  //SAVINGS & DEPOSITS
					
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
							//OTHER DEDUCTIONS
							$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') and o.date <= '".$date."'"));
							$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;
							//MONTHLY ACCOUNT CHARGES
							$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts mem on c.memaccount_id=mem.id join savings_product p on mem.saveproduct_id=p.id where c.date <='".$date."' and p.id='".$prod['id']."' and p.type='free'");
							$charge = mysql_fetch_array($charge_res);
							//OFFSETTING FROM SAVINGS
							$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' and p.date<= '".$date."'");
							$pay = mysql_fetch_array($pay_res);
							$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;
							
							//SHARES OFFSET FROM SAVINGS
							$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') and s.date <= '".$date."'"));
							$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;

							$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
							$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
							$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
							
							$sub1_total = $dep_amt + $int_amt - $with_amt - $income_amt - $charge_amt- $pay_amt - $share_amt;  
						}else{
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 211101 and account_no <= 211999");
							while($level5 = mysql_fetch_array($level5_res)){
								if($level5['name'] == 'Compulsory Shares')
									continue;
								$sub2_total =0;
								$prod_res = mysql_query("select * from savings_product where account_id='".$level5['id']."'");
								if(mysql_numrows($prod_res) > 0){
									$prod = mysql_fetch_array($prod_res);
									//$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and d.date<= '".$date."'");
									$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and d.date<= '".$date."'");
									$dep = mysql_fetch_array($dep_res);
									$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.id='".$prod['id']."' and p.type='free' and w.date<= '".$date."'");
									$with = mysql_fetch_array($with_res);

									$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and i.date<= '".$date."'");
									$int = mysql_fetch_array($int_res);
									$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
									//OTHER DEDUCTIONS
									$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') and o.date <= '".$date."'"));
									$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;
									//MONTHLY ACCOUNT CHARGES
									$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts mem on c.memaccount_id=mem.id join savings_product p on mem.saveproduct_id=p.id where c.date <='".$date."' and p.id='".$prod['id']."' and p.type='free'");
									$charge = mysql_fetch_array($charge_res);
									//OFFSETTING FROM SAVINGS
									$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' and p.date<= '".$date."'");
									$pay = mysql_fetch_array($pay_res);
									$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;
									$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;

									//SHARES OFFSET FROM SAVINGS
									$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') and s.date <= '".$date."'"));
									$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;

									
									$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
									$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
									$sub2_total = $dep_amt + $int_amt - $with_amt - $charge_amt - $income_amt - $pay_amt - $share_amt; // + $int['amount'];
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
											$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and i.date<= '".$date."'");
											$int = mysql_fetch_array($int_res);
											$int_amt = ($int['amount'] == NULL) ? 0 : $int['amount'];
											//OTHER DEDUCTIONS
											$income = mysql_fetch_array(mysql_query("select sum(o.amount) as amount from other_income o join mem_accounts mem on o.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and o.mode not in ('cash', 'cheque') and o.date <= '".$date."'"));
											$income_amt = ($income['amount'] > 0) ? $income['amount'] : 0;
											//MONTHLY ACCOUNT CHARGES
											$charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts mem on c.memaccount_id=mem.id join savings_product p on mem.saveproduct_id=p.id where c.date <='".$date."' and p.id='".$prod['id']."' and p.type='free'");
											$charge = mysql_fetch_array($charge_res);
											$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
											//OFFSETTING FROM SAVINGS
											$pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id join savings_product prod on m.saveproduct_id =prod.id where prod.id='".$prod['id']."' and prod.type='free' and p.date<= '".$date."'");
											$pay = mysql_fetch_array($pay_res);
											$pay_amt = ($pay['amount'] > 0) ? $pay['amount'] : 0;

											//SHARES OFFSET FROM SAVINGS
											$share = mysql_fetch_array(mysql_query("select sum(s.value) as amount from shares s join mem_accounts mem on s.mode=mem.id join savings_product p on mem.saveproduct_id=p.id where p.id='".$prod['id']."' and s.mode not in ('cash', 'cheque') and s.date <= '".$date."'"));
											$share_amt = ($share['amount'] > 0) ? $share['amount'] : 0;

										//	$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id =p.id where p.id='".$prod['id']."' and p.type='free' and i.date<= '".$date."'");
										//	$int = mysql_fetch_array($int_res);
											$dep_amt = ($dep['amount'] > 0) ? $dep['amount'] : 0;
											$with_amt = ($with['amount'] > 0) ? $with['amount'] : 0;
											$sub2_total = $sub2_total + $dep_amt + $int_amt - $with_amt - $charge_amt - $income_amt - $share_amt;  // + $int['amount'];
										}
									}
									$sub1_total = $sub1_total + $sub2_total;
								}
								$color = ($i%2 == 0) ? "lightgrey" : "white";
								$content .="<tr bgcolor=$color><td></td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
								$i++;
							}
						}
						$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .="<tr bgcolor=$color><td><b></b></td><td><b>Total ".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						$i++;
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
						//INTEREST GIVEN TO MEMBERS IS ALREADY PAID OUT AND IS NOT INTEREST PAYABLE
						$int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.name like '%Savings%' and i.date <='".$date."'");
						
						$int = mysql_fetch_array($int_res);
						
						$sub1_total = $int['amount'];
						*/
						;
					}elseif($level4['name'] == 'Interest Payable on Time Deposits'){
						/* 
						//INTEREST GIVEN TO MEMBERS IS ALREADY PAID OUT AND IS NOT INTEREST PAYABLE
						$int_res = mysql_query("select sum(amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.name like '%Time%' and i.date <='".$date."'");
						$int = mysql_fetch_array($int_res);
						$sub1_total = $int['amount'];
						*/
						;
					}elseif($level4['name'] == 'Dividends Payable'){
						$div_res = mysql_query("select sum(d.amount) as amount from dividends d join share_dividends s on d.share_dividend_id=s.id where s.bank_account=0 and s.date<= '".$date."'");
						$div = mysql_fetch_array($div_res);
						$dividends = intval($div['amount']);
						$sub1_total = $dividends;
					//	$interest_awarded += $sub1_total;
					}else{
						
						//if($basis == 'Cash Basis' && !preg_match("/^212/", $level4['account_no']))
						//	continue;
						
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
										//$add = ($basis == 'Cash Basis') ? $paid['amount'] : $pay['amount'] - $paid['amount'];
										//$sub2_total += $add;
										$sub2_total = $sub2_total + $pay['amount'] - $paid['amount'];
									}
								}else{
									$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id where paid.date <= '".$date."' and p.account_id='".$level5['id']."'");
									$paid = mysql_fetch_array($paid_res);
									$add = ($basis == 'Cash Basis') ? $paid['amount'] : $pay['amount'] - $paid['amount'];
									
									$sub2_total = $pay['amount'] - $paid['amount'];
								}
								$color = ($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td></td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
								$i++;
							$sub1_total += $sub2_total;
							}
						}else{
							$paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id where paid.date <= '".$date."' and p.account_id='".$level4['id']."'");
							$paid = mysql_fetch_array($paid_res);
							//$add = ($basis == 'Cash Basis') ? $paid['amount'] : $pay['amount'] - //$paid['amount'];
							//$sub2_total =$add;
							$sub1_total = $pay['amount'] - $paid['amount'];
						}
					}
					$grand_total = $grand_total + $sub1_total;
					$level2_total += $sub1_total;
					$liabilities += $sub1_total;
					$color = ($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td><b></b></td><td><b>Total ".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
					$i++;
				}
			}elseif($level3['account_no'] >= '311' && $level3['account_no'] <= '399'){
				$level4_res = mysql_query("select * from accounts where account_no like '".$level3['account_no']."%' and account_no >= 3111 and account_no <= 3999");
				while($level4 = mysql_fetch_array($level4_res)){
					$sub1_total = 0;
					if($level4['account_no'] == '3111'){
						$share_res = mysql_query("select sum(value) as value from shares where date <='".$date."'");
						$share = mysql_fetch_array($share_res);
						$level42_res = mysql_query("select * from accounts where name like 'Member Shares'");
						$level42 = mysql_fetch_array($level42_res);
						$sub1_total = $share['value'];
						$sub1_total = ($sub1_total > 0) ? $sub1_total : 0;
						$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td><b></b></td><td><b>Total ".$level42['name']."</b></td><td>".number_format($sub1_total, 2)."</td></tr>";
						$i++;
					}elseif($level4['account_no'] == '3312'){
						$sub1_total -= $grand_loss;
						$sub1_total += net_cummulated_income('', $date, $basis);
						$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td><b></b></td><td><b>Total ".$level4['name']."</b></td><td>".number_format($sub1_total, 2)."</td></tr>";
						$i++;
						//$resp->AddAlert(net_cummulated_income($date, $basis));
					}else{
						$invest_res = mysql_query("select sum(amount) as amount from other_funds where account_id='".$level4['id']."' and date <= '".$date."' and id not in (select fund_id from payable where date <= '".$date."' and fund_id<>'0')");
						$invest = mysql_fetch_array($invest_res);
						if($invest['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 321101 and account_no <= 339999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$invest_res = mysql_query("select sum(amount) as amount from other_funds where account_id='".$level5['id']."' and date <= '".$date."'  and id not in (select fund_id from payable where date <= '".$date."' and fund_id<>'0')");
								$invest = mysql_fetch_array($invest_res);
								if($invest['amount'] == NULL){
									$level6_res = mysql_query("select  * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 32110101 and account_no <= 33999999");
									while($level6 = mysql_fetch_array($level6_res)){
										$invest_res = mysql_query("select sum(amount) as amount from other_funds where account_id='".$level6['id']."' and date <= '".$date."'   and id not in (select fund_id from payable where date <= '".$date."' and fund_id<>'0')");
										$invest = mysql_fetch_array($invest_res);
										$sub2_total = $sub2_total + $invest['amount'];
									}
								}else
									$sub2_total = $invest['amount'];
								$color = ($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td></td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
								$i++;
								$sub1_total = $sub1_total + $sub2_total;
							}
						}else{
							$sub1_total = $invest['amount'];
						}
						$color = ($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td><b></b></td><td><b>Total ".$level4['name']."</b></td><td>".number_format($sub1_total, 2)."</td></tr>";
						$i++;
					}
					$color = ($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td><b></b></td><td><b>Total ".$level4['name']."</b></td><td>".number_format($sub1_total, 2)."</td></tr>";
					$i++;
					$grand_total += $sub1_total;
					$level2_total += $sub1_total; 
					$capital += $sub1_total;
				}
			}
			$color = ($i%2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color><td colspan=2 align=left><b>TOTAL  ".$level3['name']."</b></td><td><b>".number_format($grand_total, 2)."</b></td></tr>";
			$i++;
		}
		$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr bgcolor=$color><td colspan=2><font size=4pt><b> ".$level2['name']."</b></font></td><td><font size=4pt><b>".number_format($level2_total, 2)."</b></font></td></tr>";
		$i++;
	}
	$color = ($i%2 == 0) ? "lightgrey" : "white";
	$content .= "<tr bgcolor=$color><td colspan=2><font size=5pt><b>TOTAL CAPITAL</b></font></td><td><font size=5pt><b>".number_format($capital, 2)."</b></font></td></tr>";
	$i++;
	$color = ($i%2 == 0) ? "lightgrey" : "white";
	$content .="<tr bgcolor=$color><td colspan=2><font size=5pt><b>TOTAL CAPITAL & LIABILITIES</b></font></td><td><font size=5pt><b>".number_format(($capital+$liabilities), 2)."</b></font></td></tr>";
	$i++;
	$color = ($i%2 == 0) ? "lightgrey" : "white";
	$net_assets = $assets - $capital - $liabilities;
	$net_assets = ($net_assets >= 0) ? number_format($net_assets, 2) : "(".number_format((0 - $net_assets), 2).")"; 
	$content .="<tr bgcolor=$color><td colspan=2><font size=5pt><b>NET ASSETS AVAILABLE FOR BENEFIT</b></font></td><td><font size=5pt><b>".$net_assets."</b></font></td></tr>";
		$content .= "</table>";
		return $content;
}
}
?>
