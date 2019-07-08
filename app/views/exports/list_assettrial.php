<?php

if(!isset($_GET['format']))
echo("<head>
	<title>SUBSIDIARY TRIAL BALANCE - ASSETS</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");
//SUB (ASSETS) TRIAL BAL
function assettrial_balance($year, $month, $mday, $basis, $format,$branch_id){
	if($year == ''){
		$year = date('Y');
		$month = date('m');
		$mday = date('d');
	}
	$date = sprintf("%d-%02d-%02d 23:59:59", $year, $month, $mday);
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:"and branch_id=".$branch_id;
	$content = "<center><font color=#00008b size=5pt><b>".$branch['branch_name']."</b></font></center><br>
	<center><font color=#00008b size=2pt><b>".$basis."</b></font><center><br>
	<center><font color=#00008b size=3pt><b>SUBSIDIARY TRIAL BALANCE AS AT ".$date."</center><br><center>ASSETS</center></b></font><br>
	<table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='70%' id='AutoNumber2' align=center>
		<tr class='headings'><td><font size=5pt><b>ACCOUNT</b></font></td><td><font size=5pt><b>DESCRIPTION</b></font></td><td><font size=5pt><b>BALANCE</b></font></td></tr>";
		$level2_res= mysql_query("select * from accounts where account_no >10 and account_no <20 ".$branch_id." order by account_no");
		$assets = 0;
		$i=0;
			while($level2 = mysql_fetch_array($level2_res)){
			$level2_total = 0;
			$color=($i%2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color><td><font size=4pt><b>".$level2['account_no']."</b></font></td><td><font size=4pt><b>".$level2['name']."</b></font></td><td></td></tr>";
			$i++;
			$level3_res = mysql_query("select * from accounts where account_no >=111 and account_no <= 199 and account_no like '".$level2['account_no']."%' and account_no not in (124, 112) order by account_no");
			while($level3 = mysql_fetch_array($level3_res)){
				$color=($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td><b>".$level3['account_no']."</b></td><td><b>".$level3['name']."</b></td><td></td></tr>";
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
							$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td></td></tr>";
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
										$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level6['id']."' and d.date <= '".$date."' and pay.date <='".$date."'");
										$pay = mysql_fetch_array($pay_res);
										$loan2_amt = ($loan2['amount'] == NULL) ? 0 : $loan2['amount'];
										
										$loss_res = mysql_query("select sum(w.amount) as amount from written_off w join disbursed d on w.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where w.date <= '".$date."' and p.account_id='".$level6['id']."'");
										$loss = mysql_fetch_array($loss_res);
										$loss2_total = $loss2_total + $loss['amount'];
										$sub2_total = $sub2_total + $loan2_amt - $pay['amount'] - $loss['amount'];
										
									}
								} 
							}else{
								$pay_res = mysql_query("select sum(pay.princ_amt) as amount from payment pay join disbursed d on pay.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join  loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where p.account_id='".$level5['id']."' and d.date <= '".$date."' and pay.date <='".$date."'");
								$pay = mysql_fetch_array($pay_res);
								$loss_res = mysql_query("select sum(w.amount) as amount from written_off w join disbursed d on w.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id where w.date <= '".$date."' and p.account_id='".$level5['id']."'");
								$loss = mysql_fetch_array($loss_res);
								$loss2_total = $loss['amount'];

								$sub2_total = $loan1['amount'] - $pay['amount'] - $loss['amount'];
							}
						
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td>".$level5['account_no']."</td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
							$i++;
							
							$sub1_total = $sub1_total + $sub2_total;
							$loss_total = $loss_total + $loss2_total;
							$sub2_total=0;
						}
						$color=($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						 $i++;
						/* $written_res = mysql_query("select * from accounts where account_no like '112%' and name like '%".$level4['name']."'");
						$written = mysql_fetch_array($written_res);
						$color=($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color ><td><b>ddd".$written['account_no']."</b></td><td><b>".$written['name']."</b></td><td><b>".$loss1_total."</b></td></tr>";
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
					$grand_loss = $loss1_30 + $loss31_60 + $loss61_90 + $loss91_120 + $loss121_180 + $loss180_over;
					$grand_net = $grand_total - $grand_loss;  //SUBTRACT LOAN LOSS PROVISIONS
					*/
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
							$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level5['id']."' and date <= '".$date."' and id not in (select investment_id from sold_invest where date<='".$date."')");
							$invest = mysql_fetch_array($invest_res);
							$sub2_total=0;
							if($invest['amount'] == NULL){
								$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >= 12510101 and account_no <= 11399999");
								while($level6 = mysql_fetch_array($level6_res)){
									$invest_res = mysql_query("select sum(quantity * amount) as amount from investments where account_id='".$level6['id']."' and date <= '".$date."' and id not in (select investment_id from sold_invest where date<='".$date."')");		
									$invest = mysql_fetch_array($invest_res);
									$sub2_total = $sub2_total + $invest['amount'];
								}
							}else{
								
								$sub2_total = $invest['amount'];
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
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td></td></tr>";
							$i++;
						}
						while($level5 = mysql_fetch_array($level5_res)){
							$bank1_res = mysql_query("select * from bank_account where account_id='".$level5['id']."'");
							$sub2_total = 0;
							if(mysql_numrows($bank1_res) == 0){
								//$resp->AddAlert("Hi");
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
									$div_res = mysql_query("select sum(total_amount) as total_amount from share_dividends where date<='".$date."' and bank_account='".$bank2['id']."'");
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
									$import_amt = $import['amount'];

									//CASH EXPORTED
									$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id='".$bank2['id']."' and date <='".$date."'");
									$export = mysql_fetch_array($export_res);
									$export_amt = $export['amount'];

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
								$import_amt = intval($import['amount']);

								//CASH EXPORTED
								$export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id=".$bank1['id']." and date <='".$date."'");
								$export = mysql_fetch_array($export_res);
								$export_amt = intval($export['amount']);
								
								//CAPITAL FUNDS
								$fund_res = mysql_query("select sum(amount) as amount from other_funds where bank_account='".$bank1['id']."' and date <='".$date."'");
								$fund = mysql_fetch_array($fund_res);
								$fund_amt = $fund['amount'];


								$sub2_total =  $collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt + $fund_amt;
							}
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td>".$level5['account_no']."</td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
							$i++;
							$sub1_total = $sub1_total + $sub2_total;
						}
						$color=($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";
						$i++;
						$grand_total += $sub1_total;
					}
				}elseif($level3['account_no']=='122'){   //RECEIVABLES
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
						$fixed_res = mysql_query("select sum(f.initial_value) as amount from fixed_asset f  where f.account_id='".$level4['id']."' and f.id not in (select asset_id from sold_asset where date <='".$date."')");
						$fixed = mysql_fetch_array($fixed_res);
						if($fixed['amount'] == NULL){
							$level5_res = mysql_query("select * from accounts where account_no like '".$level4['account_no']."%' and account_no >= 123101 and account_no <= 123999");
							while($level5 = mysql_fetch_array($level5_res)){
								$sub2_total = 0;
								$depp2_total = 0;
								$fixed_res = mysql_query("select sum(f.initial_value) as amount from fixed_asset f  where f.account_id='".$level5['id']."' and f.id not in (select asset_id from sold_asset where date <='".$date."')");
								$fixed = mysql_fetch_array($fixed_res);
								if($fixed['amount'] == NULL){
									$level6_res = mysql_query("select * from accounts where account_no like '".$level5['account_no']."%' and account_no >=12310101 and account_no <= 12399999");
									while($level6 = mysql_fetch_array($level6_res)){
										$fixed_res = mysql_query("select sum(f.initial_value) as amount from fixed_asset f  where f.account_id='".$level6['id']."' and f.id not in (select asset_id from sold_asset where date <='".$date."')");
										$fixed = mysql_fetch_array($fixed_res);
										$sub2_total = $sub2_total + $fixed['amount'];
										$depp_res = mysql_query("select sum(amount) as amount from deppreciation where asset_id in (select id from fixed_asset where account_id='".$level6['id']."') and date<= '".$date."'");
										$depp = mysql_fetch_array($depp_res);
										$depp2_total = $depp2_total + $depp['amount'];
									}
								}else{
								$sub2_total = $fixed['amount'];
									$depp_res = mysql_query("select sum(amount) as amount from deppreciation  where asset_id in (select id from fixed_asset where account_id='".$level5['id']."') and date<= '".$date."'");
									$depp = mysql_fetch_array($depp_res);
									$depp2_total = $depp2_total + $depp['amount'];
								}
								if($fixed['id'] == '0')
									continue;
								$color=($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td>".$level5['account_no']."</td><td>".$level5['name']."</td><td>".number_format($sub2_total, 2)."</td></tr>";
								$i++;
								//$acc_res = mysql_query("select * from accounts where name='Accumulated Depreciation - ".$level5['name']."'");
								//$acc = mysql_fetch_array($acc_res);
								$color=($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td>".$acc['account_no']."</td><td>Deppreciation - ".$level5['name']."</td><td>".number_format($depp2_total, 2)."</td></tr>";
								$i++;
								$net_total = $sub2_total - $depp2_total;	
								$color=($i%2 == 0) ? "lightgrey" : "white";
								$content .= "<tr bgcolor=$color><td></td><td><b>Net ".$level5['name']."</b></td><td>".number_format($net_total, 2)."</td></tr>";
								$sub1_total += $sub2_total;
								$depp1_total += $depp2_total;
								$i++;
							}
						}else{
							$sub1_total = $fixed['amount'];
							$depp_res = mysql_query("select sum(amount) as amount from deppreciation  where asset_id in (select id from fixed_asset where account_id='".$level4['id']."') and date<= '".$date."'");
							$depp = mysql_fetch_array($depp_res);
							$depp1_total = $depp1_total + $depp['amount'];
						}
						$color=($i%2 == 0) ? "lightgrey" : "white";
						$content .= "<tr bgcolor=$color ><td><b>".$level4['account_no']."</b></td><td><b>".$level4['name']."</b></td><td><b>".number_format($sub1_total, 2)."</b></td></tr>";	
						$i++;
						$net_total = $sub1_total - $depp1_total;
						if($level4['name'] != 'Land'){
							$acc_res = mysql_query("select * from accounts where name='Accumulated Depreciation - ".$level4['name']."'");
							$acc = mysql_fetch_array($acc_res);
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color ><td><b>".$acc['account_no']."</b></td><td><b>".$acc['name']."</b></td><td><b>".number_format($depp1_total, 2)."</b></td></tr>";
							$i++;
							$color=($i%2 == 0) ? "lightgrey" : "white";
							$content .= "<tr bgcolor=$color><td></td><td><b>Net ".$level4['name']."</b></td><td><b>".number_format($net_total, 2)."</b></td></tr>";
							$i++;
						}
						$grand_total = $grand_total + $net_total;
					}
				}
				$level2_total += $grand_total;
				$color=($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td><b>".$level3['account_no']."</b></td><td><b> ".$level3['name']."</b></td><td><b>".number_format($grand_total, 2)."</b></td></tr>";	
				$i++;
				if($level3['account_no'] == 111){
					$loss_res = mysql_query("select * from accounts where account_no='112'");
					$loss = mysql_fetch_array($loss_res);
					$color=($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td><b> ".$loss['account_no']."</b></td><td><b> ".$loss['name']."</b></td><td><b>".number_format($grand_loss, 2)."</b></td></tr>";
					$i++;
					$color=($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td><b></b></td><td><b> TOTAL NET LOAN PORTFOLIO</b></td><td><b>".number_format($grand_net, 2)."</b></td></tr>";
					$i++;
					$level2_total -= $grand_loss;
					$grand_loss = 0;
				}
			}	
			$color=($i%2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color><td><font size=4pt><b>".$level2['account_no']."</b></font></td><td><font size=4pt><b> ".$level2['name']."</b></font></td><td><font size=4pt><b>".number_format($level2_total, 2)."</b></font></td></tr>";	
			$assets += $level2_total;
			$i++;
		}
		$color=($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr bgcolor=$color><td><font size=5pt><b>1</b></font></td><td><font size=5pt><b> ASSETS</b></font></td><td><font size=5pt><b>".number_format($assets, 2)."</b></font></td></tr>";	
		$content .= "</table></td></tr></table>";
		export($format, $content);
}

//reports_header();
assettrial_balance($_GET['year'], $_GET['month'], $_GET['mday'], $_GET['basis'], $_GET['format'],$_GET['branch_id']);
//reports_footer();
?>
