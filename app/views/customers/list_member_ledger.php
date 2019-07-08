<?php
require_once('common.php');

//LICENCE
require_once("spl_license/rsa.class");
require_once("spl_license/class.base32.php");

function evalKey()
{
	//$resp = new xajaxResponse();
	$rsa = New SecurityRSA;
	$b32 = New Base32;
	/*
	$fp=fopen("hard.txt", "w+");
	fclose($fp);
	system("hardware_print.exe >hard.txt");
	$hardware_print = file_get_contents("hard.txt");
	*/
	$file_name = "hard_".rand().".txt";
	$fp=fopen($file_name, "w+");
	fclose($fp);
	$cmd = "hardware_print.exe >".$file_name;
	//system("hardware_print.exe >hard.txt");
	system($cmd);
	//$hardware_print = file_get_contents("hard.txt");
	$hardware_print = file_get_contents($file_name);
	@unlink($file_name);

	$row = @mysql_fetch_array(@mysql_query("select branch_name, branch_no from branch order by branch_no asc limit 1"));
	
	$sacco_name = strtoupper($row['branch_name']);
	$sacco_print = $hardware_print . $sacco_name;
	$hwpr = sha1($sacco_print);
	$length = strlen($hwpr) - 1;
	$seq = "";
	for ($i = 0; $i < $length; $i++)
	{
		$str1 = substr($hwpr, $i, 1);
		$str2 = substr($hwpr, ($i + 1), 1);
		$char = (intval($str1) > intval($str2))? 0 : 1 ;
		$seq .= $char;
	
	}
	$seq .= "1";
	$client_id = "";

	for ($j = 0; $j < $length; $j+=4)
	{
		$client_id .= base_convert(substr($seq, $j, 4), 2, 16);
	}
	$client_id = strtoupper($client_id);
	$row = mysql_fetch_array(mysql_query("select * from flt_key order by id desc limit 1"));
	$sub_license = strtoupper($row['license_key']);
	//$sub_license = strtoupper($key1) . strtoupper($key2) . strtoupper($key3) . strtoupper($key4);
	$date = $sub_license{2} . $sub_license{5} . $sub_license{8} . $sub_license{11} . $sub_license{14} . $sub_license{15};
	$text = $client_id . $date;

	$n = 57264617;
	$e = 7603;
	$keys = array($n, $e);
	//$b32->setCharset($b->csSafe);
	$encoded = $rsa->rsa_encrypt($text, $keys[1], $keys[0]);
	$key = $b32->fromString($encoded);
	$keysum = sha1($key);
	$keylen = strlen($keysum);
	$seq2 = "";

	for ($i = 0; $i < $keylen; $i++)
	{
        $str1 = substr($keysum, $i, 1);
        $str2 = substr($keysum, ($i + 1), 1);
        $char = (intval($str1) > intval($str2))? 0 : 1 ;
        $seq2 .= $char;
	}
	$seq2 .= "1";
	for ($j = 0; $j < $keylen; $j+=4)
	{
        $rkey .= base_convert(substr($seq2, $j, 4), 2, 16);
	}
	$ack1 = substr($rkey, 0, 2);
	$ack2 = substr($rkey, 2, 2);
	$ack3 = substr($rkey, 4, 2);
	$ack4 = substr($rkey, 6, 2);
	$ack5 = substr($rkey, 8);
	//$ack5 = $rkey{9};
	$date1 = $date{0};
	$date2 = $date{1};
	$date3 = $date{2};
	$date4 = $date{3};
	$date5 = substr($date, 4);
	$actual_key = $ack1 . $date1.$ack2.$date2.$ack3.$date3.$ack4.$date4.$ack5.$date5;
	$fkey .= substr($sub_license, 0, 4) . "-" . substr($sub_license, 4, 4) . "-" . substr($sub_license, 8, 4) . "-" . substr($sub_license, 12);
	if ((strtoupper($sub_license) != strtoupper($actual_key)) || (date('ymd') >= $date))
	{
		header("Location: home2.php");
		exit();
	}
}

//evalKey();

if($_SESSION['user_id'] ==0 || !isset($_SESSION['user_id']))
	header("Location: index.php");

if(!isset($_GET['format']))
echo("<head>
	<title>SAVINGS PLUS v 2.0 : LOAN LEDGER</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");


//GENERATE SAVINGS LEDGER
function memsavings_ledger($mem_no, $mem_id, $save_acct, $from_year, $from_month, $to_year, $to_month)
{
	
	$calc = new Date_Calc();
	if($save_acct =='' && $mem_no ==''){
		echo("Please enter the Member No");
	}
	if($mem_no <>"" && $save_acct==''){
		$sth = mysql_query("select * from member where mem_no='".$mem_no."'");
		if(mysql_numrows($sth) ==0){
			echo("The entered Member No does not exist!");
		}
		$row = mysql_fetch_array($sth);
		$acct_res = mysql_query("select mem.id as id, a.account_no as account_no from mem_accounts mem join savings_product s on mem.saveproduct_id=s.id where mem.mem_id='".$row['id']."' and s.type='free'");
		
	}
	$start_date = sprintf("%d-%02d", $from_year, $from_month) . "-01 00:00:00";
	$end_date = date('Y-m-d', strtotime($calc->endOfMonthBySpan(0, intval($to_month), intval($to_year), '%Y-%m-%d 23:59:59')));
	$start_month = intval($from_month); $end_month = intval($to_month);
	$start_year = intval($from_year); $end_year = intval($to_year);
	$total_saved = 0; $total_with = 0; $total_int = 0; $total_fees = 0;
	$cumm_save = 0;
	
	$drow1 = @mysql_fetch_array(@mysql_query("select sum(amount - flat_value - percent_value) as tot_savings from deposit where bank_account != 0 and memaccount_id = $save_acct and date <= '".$start_date."'"));
	$wrow1 = @mysql_fetch_array(@mysql_query("select sum(amount + flat_value + percent_value) as tot_with from withdrawal where memaccount_id = $save_acct and date <= '".$start_date."'"));
	$mrow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_fees from monthly_charge where memaccount_id = $save_acct and date <= '".$start_date."'"));
	$irow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_int from save_interest where memaccount_id = $save_acct and date <= '".$start_date."'"));
	$prow1 = @mysql_fetch_array(@mysql_query("select sum(princ_amt + int_amt) as tot_int from payment where mode = '$save_acct' and date <= '".$start_date."'"));
	$incow1 = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_inc from other_income where mode = '$save_acct' and date <= '".$start_date."'"));
	$shares = @mysql_fetch_array(@mysql_query("select sum(value) as tot_val from shares where mode = '$save_acct' and date <= '".$start_date."'"));
	$total_shares = isset($shares['tot_val'])? $shares['tot_val'] : 0 ;

        $total_saved = isset($drow1[tot_savings])? intval($drow1[tot_savings]) : 0 ;
        $total_fees = isset($mrow1[tot_fees])? intval($mrow1[tot_fees]) : 0 ;
        $total_with = isset($wrow1[tot_with])? intval($wrow1[tot_with]) : 0 ;
        $total_int = isset($irow1[tot_int])? intval($irow1[tot_int]) : 0 ;
		$total_pay = isset($prow1[tot_int])? intval($prow1[tot_int]) : 0 ;
		$total_inc = isset($incow1[tot_inc])? intval($incow1[tot_inc]) : 0 ;
        $net_save = ($total_saved + $total_int) - ($total_fees + $total_with + $total_pay + $total_inc + $total_shares);
	$cumm_save += $net_save;
	$mem_row = @mysql_fetch_array(@mysql_query("select first_name, last_name, mem_no, sign_name, photo_name from member where id = $mem_id"));
	$branch = mysql_fetch_array(mysql_query("select * from branch"));
	$prod = mysql_fetch_array(mysql_query("select a.account_no as account_no from mem_accounts mem join savings_product p on mem.saveproduct_id=p.id join accounts a on p.account_id=a.id where mem.id='".$save_acct."'"));
	$content .= " 
		   <center><font color=#00008b size=3pt><B>INTERIM SAVINGS STATEMENT (".$prod['account_no'].")</B></font></center>
		   <table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='70%' id='AutoNumber2' align=center>
		    <tr class='headings'>
			<th>Date</th><th>Depositor</td><th>Description</th><th>Debit</th><th>Credit</th><th>Account Balance</th>
		    </tr>
		    <tr bgcolor=lightgrey>
			<td>Before $start_date</td><td>--</td><td align=center>B/F</td><td align=center>--</td><td align=center>--</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		$acc_res = @mysql_query("select id, date, amount, transaction, depositor as depositor from deposit where bank_account != 0 and memaccount_id = $save_acct and date > '".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction, '--' as depositor from withdrawal where memaccount_id = $save_acct and date > '".$start_date."' and date <= '".$end_date."' UNION select id, date, amount, transaction, '--' as depositor  from monthly_charge where memaccount_id = $save_acct and date >'".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction, '--' as depositor  from save_interest where memaccount_id = $save_acct and date > '".$start_date."' and date <='".$end_date."' UNION select id, date, princ_amt + int_amt as amount, transaction, '--' as depositor  from payment where mode= '".$save_acct."' and date > '".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction, '--' as depositor  from other_income where mode = '".$save_acct."' and date > '".$start_date."' and date <= '".$end_date."' UNION select id, date, value as amount, transaction, '--' as depositor  from shares where mode = '".$save_acct."' and date > '".$start_date."' and date <= '".$end_date."' order by date asc");
	$x = 0;
	while ($acc_row = @mysql_fetch_array($acc_res))
	{
		$charge_amt = 0;
		$tot_shares = strtolower($acc_row['transaction']) == 'shares' ? intval($acc_row[amount]) : 0 ;
		$tot_savings = strtolower($acc_row['transaction']) == 'deposit' ? intval($acc_row[amount]) : 0 ;
		$tot_fees = strtolower($acc_row['transaction']) == 'monthly_charge' ? intval($acc_row[amount]) : 0 ;
		$tot_with = strtolower($acc_row['transaction']) == 'withdrawal' ? intval($acc_row[amount]) : 0 ;
		$tot_int = strtolower($acc_row['transaction']) == 'save_interest' ? intval($acc_row[amount]) : 0 ;
		$tot_pay = strtolower($acc_row['transaction']) == 'payment' ? intval($acc_row[amount]) : 0 ;
		$tot_inc = strtolower($acc_row['transaction']) == 'other_income' ? intval($acc_row[amount]) : 0 ;

		if(strtolower($acc_row['transaction']) == 'deposit'){
			$charge = mysql_fetch_array(mysql_query("select receipt_no, cheque_no, (flat_value + percent_value) as amount from deposit where id='".$acc_row['id']."'"));
			$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
			$descr="Deposit<br>RCPT: ".$charge['receipt_no'];
			$descr = ($charge['cheque_no'] <>"") ? $descr."<br>CHEQ: ".$charge['cheque_no'] : $descr;
		}
		if(strtolower($acc_row['transaction']) == 'withdrawal'){
			$charge = mysql_fetch_array(mysql_query("select voucher_no, cheque_no, flat_value + percent_value as amount from withdrawal where id='".$acc_row['id']."'"));
			$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
			$descr="Withdrawal<br>PV: ".$charge['voucher_no'];
			$descr = ($charge['cheque_no'] <>"") ? $descr."<br>CHEQ: ".$charge['cheque_no'] : $descr;
		}
		if(strtolower($acc_row['transaction']) == 'payment'){
	
			$pay = mysql_fetch_array(mysql_query("select receipt_no,  princ_amt + int_amt as amount from payment where id='".$acc_row['id']."'"));
			$pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;
			$descr="Loan Repayment<br>PV: ".$pay['receipt_no'];
			//$resp->AddAlert($tot_pay);
		}

		if(strtolower($acc_row['transaction']) == 'other_income'){
	
			$inc = mysql_fetch_array(mysql_query("select i.receipt_no, i.cheque_no, i.amount, a.name from other_income i join accounts a on a.id = i.account_id where i.id='".$acc_row['id']."'"));
			$inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;
			$descr="DEDUCTION ($inc[name])<br>PV / CHEQ: ".$inc['receipt_no']. " ".$inc['cheque_no'];
			//$resp->AddAlert($tot_pay);
		}
		if(strtolower($acc_row['transaction']) == 'shares'){
	
			$share = mysql_fetch_array(mysql_query("select s.receipt_no, s.value as amount from shares s where s.id='".$acc_row['id']."'"));
			$share_amt = ($share['amount'] != NULL) ? $share['amount'] : 0;
			$descr="TRANSFER TO SHARES <br>PV / CHEQ: ".$share['receipt_no'];
			//$resp->AddAlert($tot_pay);
		}
		//$tot_fees = $tot_fees + $charge_amt;
		//$net_save = ($tot_savings + $tot_int) - ($tot_fees + $charge_amt + $tot_with);
		//$cumm_save += $net_save;
		if($tot_savings !=0){
			$cumm_save += $tot_savings;
			$x++;
			$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr bgcolor=$color>
			<td>$acc_row[date]</td><td>$acc_row[depositor]</td><td align=center>$descr</td><td align=center>--</td><td align=center>".number_format($tot_savings, 2)."</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($tot_int !=0){
			$cumm_save += $tot_int;
			$x++;
			$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr bgcolor=$color>
			<td>$acc_row[date]</td><td>$acc_row[depositor]</td><td align=center>Interest Earned</td><td align=center>--</td><td align=center>$tot_int</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($tot_shares !=0){
			$cumm_save -= $tot_shares;
			$x++;
			$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr bgcolor=$color>
			<td>$acc_row[date]</td><td>$acc_row[depositor]</td><td align=center>$descr</td><td align=center>".number_format($tot_shares, 2)."</td><td align=center>--</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($tot_with !=0){
			$cumm_save -= $tot_with;
			$x++;
			$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr bgcolor=$color>
			<td>$acc_row[date]</td><td>$acc_row[depositor]</td><td align=center>$descr</td><td align=center>".number_format($tot_with, 2)."</td><td align=center>--</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($tot_pay >0 || $tot_pay <0){
			$cumm_save -= $tot_pay;
			$x++;
			
			$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr bgcolor=$color>
			<td>$acc_row[date]</td><td>$acc_row[depositor]</td><td align=center>$descr</td><td align=center>".number_format($tot_pay, 2)."</td><td align=center>--</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($charge_amt !=0){
			$x++;
			$cumm_save -= $charge_amt;
			$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr bgcolor=$color>
			<td>$acc_row[date]</td><td>$acc_row[depositor]</td><td align=center>Transactional Charge</td><td align=center>$charge_amt</td><td align=center>--</td><td align=center>$cumm_save</td>
		    </tr>
		    ";
		}
		if($tot_inc !=0){
			$cumm_save -= $tot_inc;
			$x++;
			
			$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr bgcolor=$color>
			<td>$acc_row[date]</td><td>$acc_row[depositor]</td><td align=center>$descr</td><td align=center>".number_format($tot_inc, 2)."</td><td align=center>--</td><td align=center>".number_format($cumm_save, 2)."</td>
		    </tr>
		    ";
		}
		if($tot_fees !=0){
			$x++;
			$cumm_save -= $tot_fees;
			$color = ($x%2 == 0) ? "white" : "lightgrey";
			$content .= "
		    <tr bgcolor=$color>
			<td>$acc_row[date]</td><td>$acc_row[depositor]</td><td align=center>Monthly Charge</td><td align=center>$tot_fees</td><td align=center>--</td><td align=center>$cumm_save</td>
		    </tr>
		    ";
		}
	}
	$content .= "</table>";
	return $content;
}


//LIST MEMBER LEDGER
function member_ledger($mem_id, $type, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday, $format){
	$mem_res = @mysql_query("select id, first_name, last_name, mem_no from member order by first_name, last_name desc");
	while ($mem_row = mysql_fetch_array($mem_res))
	{
		$mems .= "<option value=$mem_row[id]>$mem_row[first_name] $mem_row[last_name] - $mem_row[mem_no]  </option>";
	}
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);


	if($mem_id !=  ''){
		$choice = ($type == 'mem_id') ? " id='".$mem_id."'" : " mem_no ='".$mem_id."'";
		$former_res = mysql_query("select * from member where ".$choice."");
		$former = mysql_fetch_array($former_res);
		$mem_id2 = $former['id'];
		$mem_no2 = $former['mem_no'];
	}

	$mem_row = mysql_fetch_array(mysql_query("select first_name, last_name, mem_no, sign_name, photo_name from member where id = '".$mem_id2."'"));
	$branch = mysql_fetch_array(mysql_query("select b.* from member m join branch b on m.branch_id=b.branch_no where m.id='".$mem_id2."'"));
	$content .= " <center><font color=#00008b size=5pt><B>".$branch['branch_name']."</B></font></center><p>
		   <center><font color=#00008b size=3pt><B>MEMBER STATEMENT</B></font>
		   
			<font color=#00008b size=2pt> Name:  <b>".strtoupper($mem_row[first_name])." ". strtoupper($mem_row[last_name])."</B><br>Member Number: <b>".$mem_row['mem_no']."</b> <br>Period:  <b>".$from_date  ."  -  ". $to_date."</b></font><br>
		   <table width=70%><tr bgcolor=white><td align=right><img src='photos/".$mem_row['photo_name']."?dummy=".time()."' width=90 height=90><br>Photo</td><td align=right><img src='signs/".$mem_row['sign_name']."?dummy=".time()."' width=90 height=90><br>Signature</td></tr></table></p></center>";
	$from_date = sprintf("%d-%02d-%02d", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d", $to_year, $to_month, $to_mday);
	if($mem_id == ''){
		$content = "<table  border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='70%' id='AutoNumber2' align=center height=100><tr><td><font color=red>Please select the period and the member</font></td></tr></table>";
		echo($content);
	}elseif($mem_id !=  ''){
		$choice = ($type == 'mem_id') ? " id='".$mem_id."'" : " mem_no ='".$mem_id."'";
		$former_res = mysql_query("select * from member where ".$choice."");
		if(mysql_numrows($former_res) ==0){
			echo("No member found!");
		}
		$former = mysql_fetch_array($former_res);
		$mem_id = $former['id'];
		$mem_no = $former['mem_no'];
	}
	$content .="
	<table  border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='70%' id='AutoNumber2' align=center height=100><tr><td>";

	//CALCULATE BALANCE 
	$direct = mysql_query("select sum(shares) as shares, sum(value) as value from shares where mem_id = $mem_id and receipt_no != '' and date <= '".$from_date."'");
	$inward = mysql_query("select sum(shares) as shares, sum(value) as value from share_transfer where to_id = $mem_id and date <= '".$from_date."'");
	$outward = @mysql_query("select sum(shares) as shares, sum(value) as value from share_transfer where from_id = $mem_id and date <= '".$from_date."'");
	$div_res = mysql_query("select sum(d.amount) as amount from dividends d join share_dividends s on d.share_dividend_id=s.id where d.mem_id=$mem_id and s.bank_account=0 and s.date <= '".$from_date."'");
	
	$direct = mysql_fetch_array($direct);
	$inward = mysql_fetch_array($inward);
	$outward = mysql_fetch_array($outward);
	$div = mysql_fetch_array($div_res);

	$direct_amt = ($direct['value'] == NULL) ? 0 : $direct['value'];
	$inward_amt = ($inward['value'] == NULL) ? 0 : $inward['value'];
	$outward_amt = ($outward['value'] == NULL) ? 0 : $outward['value'];
	$direct_no = ($direct['shares'] == NULL) ? 0 : $direct['shares'];
	$inward_no = ($inward['shares'] == NULL) ? 0 : $inward['shares'];
	$outward_no = ($outward['shares'] == NULL) ? 0 : $outward['shares'];
	$div_amt = ($div['amount'] == NULL) ? 0 : $div['amount'];

	$balance = $direct_amt + $inward_amt - $outward_amt + $div_amt;
	$tot_mem_shares = $direct_no + $inward_no - $outward_no; 


	$direct = @mysql_query("select id, date, shares, value from shares where mem_id = $mem_id and receipt_no != '' and date >'$from_date' and date <= '$to_date' order by date asc");
	$inward = @mysql_query("select id, date, shares, value from share_transfer where to_id = $mem_id and date >'$from_date' and date <= '$to_date' order by date asc");
	$outward = @mysql_query("select id, date, shares, value from share_transfer where from_id = $mem_id and date >'$from_date' and date <= '$to_date' order by date asc");
	$div_res = mysql_query("select d.id,  s.date, d.amount from dividends d join share_dividends s on d.share_dividend_id=s.id where d.mem_id=$mem_id and s.bank_account=0 and s.date >'$from_date' and s.date <= '$to_date' order by s.date asc");
	$mem = @mysql_fetch_array(@mysql_query("select first_name, last_name from member where id = $mem_id"));
	
	$found_shares = 0;
	$content .= "<center><font color=#00008b size=2pt><b>SHARES </b></font></center><br>";
	$content .= "<table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>
	<tr class='headings'><th>Date</th><th>Type of Transaction</th><th>No. of Shares</th><th>Value</th><th>Total Shares</th><th>Dividends</th><th>Balance</th></tr>";
	$content .= "<tr bgcolor=white>
				    <td>$from_date</td><td>Balance Brought Forward</td><td align='center'>--</td><td align='center'>--</td><td align='center'>".number_format($tot_mem_shares, 2)."</td><td>--</td><td>".number_format($balance, 2)."</td>
				    </tr>
				    ";
	if (@mysql_num_rows($direct) > 0 || $balance >0)
	{
		$found_shares += 1; $i = 0;
		while ($drow = mysql_fetch_array($direct))
		{
			$balance += $drow[value];
			$tot_mem_shares += $drow[shares];
			$color = ($i % 2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color>
				    <td>$drow[date]</td><td>Direct Purchase</td><td align='center'>$drow[shares]</td><td align='center'>$drow[value]</td><td align='center'>".number_format($tot_mem_shares, 2)."</td><td>--</td><td>".number_format($balance, 2)."</td>
				    </tr>
				    ";
					$i++;
		}
	}
	if (mysql_num_rows($inward) > 0)
	{
		$found_shares += 1;
		$i = 0;
		while ($inrow = mysql_fetch_array($inward))
		{
			$balance += $inrow[value];
			$tot_mem_shares += $inrow[shares];
			$color = ($i % 2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color>
				    <td>$inrow[date]</td><td>Inward transfer</td><td align='center'>$inrow[shares]</td><td align='center'>$inrow[value]</td><td align='center'>".number_format($tot_mem_shares, 2)."</td><td>--</td><td>".number_format($balance, 2)."</td>
				    </tr>
				    ";
					$i++;
		}
	}
	if (mysql_num_rows($outward) > 0)
	{
		$found_shares += 1;
		$i=0;
		while ($outrow = mysql_fetch_array($outward))
		{
			$balance -= $outrow['value'];
			$tot_mem_shares -= $outrow[shares];
			$color = ($i % 2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color>
				    <td>$outrow[date]</td><td>Outward transfer</td><td align='center'>".number_format($outrow['shares'], 2)."</td><td align='center'>".number_format($outrow['value'], 2)."</td><td align='center'>".number_format($tot_mem_shares, 2)."</td><td>--</td><td>".number_format($balance, 2)."</td>
				    </tr>
			            ";
					$i++;
		}
	}
	if (@mysql_num_rows($div_res) > 0)
	{
		$found_shares += 1;
		$i=0;
		while ($div = @mysql_fetch_array($div_res))
		{
			$balance += $div['amount'];
			//$tot_mem_shares -= intval($outrow[shares]);
			$color = ($i % 2 == 0) ? "lightgrey" : "white";
			$content .= "<tr bgcolor=$color>
				    <td>$div[date]</td><td>Dividends</td><td align='center'>--</td><td align='center'>--</td><td align='center'>".number_format($tot_mem_shares, 2)."</td><td>".number_format($div['amount'], 2)."</td><td>".number_format($balance, 2)."</td>
				    </tr>
			            ";
					$i++;
		}
	}
	$content .= "</table></td></tr></table>";
	//if ($found_shares < 1)
	//	$content .= "<font color='red'>No shares activity yet registered for $mem[first_name] $mem[last_name]</font><br>";
	//echo($content);

	$acct_res = mysql_query("select ma.id as mem_acct_id, ac.name, ac.account_no from mem_accounts ma join savings_product sp on ma.saveproduct_id = sp.id join accounts ac on sp.account_id = ac.id where ma.mem_id = '$mem_id' and sp.type='free'");
	while($acct = mysql_fetch_array($acct_res)){
		$content .= memsavings_ledger($mem_no, $mem_id, $acct['mem_acct_id'], $from_year, $from_month, $to_year, $to_month);
	}
	$content .= memloan_ledger($mem_no, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday);
	export($format, $content);
}

////LOAN LEDGER
function memloan_ledger($mem_no, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday){
	$content .="<center><B>LOAN LEDGER</B></center><table height=100 border='1' height=100 cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='70%' id='AutoNumber2' align=center><tr><td>";
	if($from_year =='')
		$content .= "<tr><td><font color=red>Please select options of the ledger</font></td></tr>";
	elseif($mem_no==''){
		$content .= "<tr><td><font color=red>Please enter the Member No or select the member!</font></td></tr>";
		echo("Please enter the Member No");

	}else{
		$over_res = mysql_query("select * from member where mem_no='".$mem_no."'");
		if(mysql_numrows($over_res) == 0){
			$content .= "<tr><td><font color=red>The Member No entered does not exist!</font></td></tr></table>";
			echo($content);
		}
		$over=mysql_fetch_array($over_res);
		$from_date = sprintf("%d-%02d-%d 00:00:00", $from_year, $from_month, $from_mday);
		$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
		
		
		$content .="<table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>";
		//START BALANCE
		$bal_res = mysql_query("select  sum(d.amount) as amount from disbursed d join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."'  and d.date < '".$from_date."'"); 
		$bal = mysql_fetch_array($bal_res);
		$pay_res = mysql_query("select sum(p.princ_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where p.date < '".$from_date."' and applic.mem_id='".$over['id']."'");
		$pay = mysql_fetch_array($pay_res);
		$bal_amt = ($bal['amount'] == NULL) ? 0 : $bal['amount'];
		$pay_amt = ($pay['amount'] == NULL) ? 0 : $pay['amount'];
		$start_bal = $bal_amt - $pay_amt;

		$loan_res = mysql_query("select d.date as date, d.amount as amount from disbursed d join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' d.date >= '".$from_date."' and d.date <= '".$to_date."' and applic.mem_id='".$over['id']."'"); 

		$paid_res = mysql_query("select p.date as date, p.princ_amt as amount, p.int_amt as int_amt, p.receipt_no as receipt_no from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and p.date >= '".$from_date."' and p.date <= '".$to_date."' and applic.mem_id='".$over['id']."' order by p.date asc");

		$pen_res = mysql_query("select pen.amount as amount, pen.date as date, pen.status as status from penalty pen join disbursed d on pen.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' pen.date >= '".$from_date."' and pen.date <= '".$to_date."' and applic.mem_id='".$over['id']."'");
		
			$content .= "<tr class='headings'><td><b>Date</b></td><td><b>Description of Transaction</b></td><td><b>Interest</b></td><td><b>Debit</b></td><td><b>Credit</b></td><td><b>Balance</b></td></tr>
		<tr bgcolor=lightgrey><td>Before ".$from_date."</td><td>Start Balance</td><td>--</td><td>--</td><td>--</td><td>".number_format($start_bal, 2)."</td></tr>";
		$last_date = $from_date;
		$balance = $start_bal;
		//if(mysql_numrows($paid_res) >0){
			$i=1;
			while($paid = mysql_fetch_array($paid_res)){
				$loan_res = mysql_query("select d.date as date, d.amount as amount from disbursed d join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and d.date >= '".$last_date."' and d.date < '".$paid['date']."' and applic.mem_id='".$over['id']."' order by d.date asc");
				while($loan = mysql_fetch_array($loan_res)){
					$pen_res = mysql_query("select pen.amount as amount, pen.date as date, pen.status as status from penalty pen join disbursed d on pen.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and pen.date >= '".$last_date."' and pen.date < '".$loan['date']."' and applic.mem_id='".$over['id']."' order by pen.date asc");
					while($pen = mysql_fetch_array($pen_res)){
						$color=($i%2 == 0) ? "lightgrey" : "white";
						if($pen['status'] == 'pending'){
							$balance = $balance + $pen['amount'];
						
							$content .= "<tr bgcolor=$color><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
						}else
							$content .= "<tr bgcolor=$color><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>".$pen['amount']."</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
						$last_date = $pen['date'];
						$i++;
					}
					$balance = $balance + $loan['amount'];
					$color=($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td>".$loan['date']."</td><td>Loan Disbursed</td><td>--</td><td>--</td><td>".number_format($loan['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
					$last_date = $loan['date'];
					$i++;
				}
				$pen_res = mysql_query("select pen.amount as amount, pen.date as date, pen.status as status from penalty pen join disbursed d on pen.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and pen.date >= '".$last_date."' and pen.date < '".$paid['date']."' order by pen.date asc");
				while($pen = mysql_fetch_array($pen_res)){
					$color=($i%2 == 0) ? "lightgrey" : "white";
					if($pen['status'] == 'pending'){
						$balance = $balance + $pen['amount'];
						$content .= "<tr bgcolor=$color><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
					}else
						$content .= "<tr bgcolor=$color><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
						$i++;
				}
				$balance = $balance - $paid['amount'];
				$color=($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td>".$paid['date']."</td><td>Repayment<br>RCPT: ".$paid['receipt_no']."</td><td>--</td><td>".number_format($paid['amount'], 2)."</td><td>--</td><td>".number_format($balance, 2)."</td></tr>";
				$last_date = $paid['date'];
				$i++;
				//INTEREST
				if($paid['int_amt'] != 0){
					$color=($i%2 == 0) ? "lightgrey" : "white";
					$content .= "<tr bgcolor=$color><td>".$paid['date']."</td><td>Interest Paid<br>RCPT: ".$paid['receipt_no']."</td><td>".number_format($paid['int_amt'], 2)."</td><td>--</td><td>--</td><td>".number_format($balance, 2)."</td></tr>";
				//$last_date = $paid['date'];
					$i++;
				}
			}
			$loan_res = mysql_query("select d.date as date, d.amount as amount from disbursed d join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and d.date > '".$last_date."' and d.date < '".$to_date."' and applic.mem_id='".$over['id']."' order by d.date asc");
			while($loan = mysql_fetch_array($loan_res)){
				$pen_res = mysql_query("select pen.amount as amount, pen.date as date, pen.status as status from penalty pen join disbursed d on pen.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and pen.date >= '".$last_date."' and pen.date < '".$loan['date']."' and applic.mem_id='".$over['id']."' order by pen.date asc");
				while($pen = mysql_fetch_array($pen_res)){
					$color=($i%2 == 0) ? "lightgrey" : "white";
					if($pen['status'] == 'pending'){
						$balance = $balance + $pen['amount'];
						$content .= "<tr bgcolor=$color><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
					}else
						$content .= "<tr bgcolor=$color><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
					$last_date = $pen['date'];
					$i++;
				}
				$balance = $balance + $loan['amount'];
				$color=($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td>".$loan['date']."</td><td>Loan Disbursed</td><td>--</td><td>--</td><td>".number_format($loan['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
				$last_date = $loan['date'];
				$i++;
			}
			$pen_res = mysql_query("select pen.amount as amount, pen.date as date, pen.status as status from penalty pen join disbursed d on pen.loan_id=d.id join loan_applic applic on d.applic_id=applic.id where applic.mem_id='".$over['id']."' and pen.date >= '".$last_date."' and pen.date <= '".$to_date."' and applic.mem_id='".$over['id']."' order by pen.date asc");
			while($pen = mysql_fetch_array($pen_res)){
				if($pen['status'] == 'pending'){
					$color=($i%2 == 0) ? "lightgrey" : "white";
					$balance = $balance + $pen['amount'];
					$content .= "<tr bgcolor=$color><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
				}else
					$content .= "<tr bgcolor=$color><td>".$pen['date']."</td><td>Penalty</td><td>--</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($pen['amount'], 2)."</td><td>".number_format($balance, 2)."</td></tr>";
				$last_date = $pen['date'];
				$i++;
			}
	}
	$content .= "</table>";
	return $content;
}

//reports_header();
member_ledger($_GET['mem_id'], $_GET['type'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday'], $_GET['format']);
//reports_footer();
?>