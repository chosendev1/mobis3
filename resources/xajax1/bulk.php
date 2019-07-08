<?php
//require_once("common.php");
//require_once('./xajax_0.2.4/xajax.inc.php');
//require_once 'Excel/reader.php';

errororororo
//post bulk payments
function add_bulk()
{
	if (strtolower($_SESSION['position']) == 'manager')
		$acc = @mysql_query("select ba.id, ac.name, ac.account_no, ba.bank from accounts ac join bank_account ba on ac.id = ba.account_id");
	else
		$acc = @mysql_query("select ba.id, ac.name, ac.account_no, ba.bank from accounts ac join bank_account ba on ac.id = ba.account_id where ba.id in (select bank_account_id from user_account where user_id =".$_SESSION['user_id'].")");
	$accts .= "<option value=''>&nbsp;</option>";
	while ($accrow = @mysql_fetch_array($acc))
	{
		$accts .= "<option value=$accrow[id]>$accrow[account_no] - $accrow[bank] $accrow[name]</option>";
	}
	
	$modes .= $action == 'cash'? "<option value='cash' selected>Cash/Cheque</option>" : "<option value='cash' selected>Cash/Cheque</option>";
	$modes .= $action == 'offset'? "<option value='offset' selected>Offset from Member's Savings</option>" : "<option value='offset'>Offset from Member's Savings</option>";

	$content .= "<br><center><font color=#00008b size=3pt><B>POST A SCHEDULE</B></font></center><br>";
	$content .= "<form  enctype='multipart/form-data' action='bulk.php' method='post'><table height=100 border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='90%' id='AutoNumber2' align=center>";
	$content .= "<tr><td colspan=2><font color=red>The excel file should have the columns: MemberNo, ReceiptNo, Shares, then the Account Codes  of the transactions</font></td></tr>
	<tr bgcolor=lightgrey><td>Branch</td><td>".branch()."</td></tr>";
	$content .= "<tr bgcolor=white>
			<td>Select Dest Bank Account:</td><td><select name='bank_account' id='bank_account'>$accts</select></td>
		    </tr>
			<tr bgcolor=lightgrey>
		    	<td>Contact Person</td><td><input type='numeric' name='contact' id='contact'></td>
		    </tr>
		    <tr bgcolor=white>
		    	<td>File Ref:</td><td><input type='numeric' name='file_ref' id='file_ref'></td>
		    </tr>
			<tr bgcolor=lightgrey>
				<td>Cheque No (Optional):</td><td><input type='numeric' name='cheque_no' id='cheque_no'></td>
			</tr>
		    <tr bgcolor=white>
		    	<td>Date:</td><td>".month_array('', '', '').mday('', '')."</td>
		    </tr>
			 <tr bgcolor=white>
		    	<td>File:</td><td><input type='file' name='file' id='file'></td>
		    </tr>
		    <tr bgcolor=lightgrey>
		    	<td></td><td><input type=reset name='reset' value='Reset'>&nbsp;<input type=submit name='submit' value='Upload'></td>
		    </tr>
		    </table></form>
		    ";
	echo($content);
}



function unique_rcpt($rcptno, $table='')
{

	if ($rcptno == '')
		return false;
	elseif ($table == '')
	{
		$res = @mysql_query("SELECT receipt_no FROM payment where receipt_no='".$rcptno."' UNION SELECT receipt_no FROM deposit where receipt_no='".$rcptno."' UNION SELECT receipt_no from collected where receipt_no='".$rcptno."' UNION SELECT receipt_no from shares where receipt_no='".$rcptno."' UNION SELECT receipt_no from other_income where receipt_no='".$rcptno."' UNION SELECT receipt_no from recovered where receipt_no='".$rcptno."' union select receipt_no from sold_asset where receipt_no='".$rcptno."' union select receipt_no from sold_invest where receipt_no='".$rcptno."'");
	if (@mysql_num_rows($res) > 0)
		return false;
	else
		return true;
	}
	elseif ($table == 'other_income') 
	{
		$res = @mysql_query("SELECT receipt_no FROM payment where receipt_no='".$rcptno."' UNION SELECT receipt_no FROM deposit where receipt_no='".$rcptno."' UNION SELECT receipt_no from collected where receipt_no='".$rcptno."' UNION SELECT receipt_no from shares where receipt_no='".$rcptno."' UNION SELECT receipt_no from other_income where receipt_no !='".$rcptno."' UNION SELECT receipt_no from recovered where receipt_no='".$rcptno."'");
		if (@mysql_num_rows($res) > 0)
			return false;
		else
			return true;
	}			
}


//UNIQUE FILE REF
function unique_ref($file_ref, $table='')
{
/*
	Check if a given rcpt_no has bn registered before.
	Tables currently containing receipt_no: payment, collected, deposit, shares, other_income, recovered
	returns false if rcpt_no has already bn registered or is an empty string
*/
	if ($file_ref == '')
		return false;
	elseif ($table == '')
	{
		$res = @mysql_query("SELECT file_ref FROM payment where file_ref='".$file_ref."' UNION SELECT file_ref FROM deposit where file_ref='".$file_ref."'  UNION SELECT file_ref from shares where file_ref='".$file_ref."' UNION SELECT file_ref from other_income where file_ref='".$file_ref."'");
	if (@mysql_num_rows($res) > 0)
		return false;
	else
		return true;
	}
			
}

//INSERT BULK POST
function insert_bulk($bank_acct_id, $contact, $file_ref, $cheque_no,  $year, $month, $mday, $branch_id, $file_name){
	//$resp = new xajaxResponse();
	$data = new Spreadsheet_Excel_Reader();
	// Set output Encoding.
	$data->setOutputEncoding('CP1251');
	$data->read($file_name);

	if($bank_acct_id=='' || $contact=='' || $file_ref=='' || $file_name==''){
		echo("file_name=".$file_name.", contact=".$contact.", bank_account=".$bank_acct_id);
		?>
		<script language='javascript'>
			alert("Bulk Posting rejected! Fill in all the fields");
		</script>
		<?php
		exit();
	}
	
	if(! unique_ref($file_ref, '')){
		?>
		<script language='javascript'>
			alert("Bulk Posting not done \n File Ref $file_ref  already exists");
		</script>
		<?php
		exit();
	}
	
	$numbers = array();
	$names = array();
	$calc = new Date_Calc();
	$branch = @mysql_fetch_array(@mysql_query("select share_value, prefix from branch where branch_no='".$branch_id."' order by branch_no limit 1"));
	$prefix = $branch['prefix'];
	$share_value = $branch['share_value'];
	$date= sprintf("%d-%02d-%02d ", $year, $month, $mday);
	$date = $date. date('H:i:s');

	start_trans();
	
	for ($i = 2;  $i <= $data->sheets[0]['numRows']; $i++) {
		$mem_no = $data->sheets[0]['cells'][$i][1];
		$receipt_no = $data->sheets[0]['cells'][$i][2];
		if(! unique_rcpt($receipt_no, '')){
			echo("Bulk Posting not done \n ReceiptNo in line $i already exists");
			rollback();
			exit();
		}
		$lastnum_shares =0;
		$lasttot_value =0;
		if($mem_no == ''){
			break;
		}
		if(!preg_match("/\d+$/", $mem_no)){
			rollback();
			echo("<font color=red>Invalid Member No ".$mem_no." found in row:".$i."</font>");
			exit();
		}
	
		$mem_no = str_pad($mem_no, 7, "0", STR_PAD_LEFT);
		$mem_no = (preg_match("/^".$prefix."\d+/", $mem_no) ) ? $mem_no : $prefix . $mem_no;
		//if($data->sheets[0]['cells'][$i][2] ==0)
		//	continue;
		$num_shares = $data->sheets[0]['cells'][$i][2] / $share_value;
	//$disbursed_amt = preg_replace("/,/", "", $data->sheets[0]['cells'][$i][3]);
		if(!preg_match("/^\d+[.]?\d*$/", $num_shares)){
			rollback();
			$echo("<font color=red>Invalid number of shares ".$data->sheets[0]['cells'][$i][3]." found in row:".$i."</font>");
			exit();
		}

		$mem_res =mysql_query("select *, datediff(CURDATE(), dob)/365 as age from member where mem_no='$mem_no'");
	//echo("<font color=red>select *, datediff(CURDATE(), dob)/365 as age from member where mem_no='$mem_no'</font>");
		if(mysql_numrows($mem_res) == 0){
			rollback();
			echo("<font color=red>MemberNo ".$mem_no." in row:".$i." NOT found in database<br>".mysql_error()."</font>");
			exit();
		}
		$mem = mysql_fetch_array($mem_res);
	
		$tot_value = $data->sheets[0]['cells'][$i][3];
	
		$num_shares = $data->sheets[0]['cells'][$i][3] / $share_value;
		
		if($tot_value !=0){
			if (! mysql_query("INSERT INTO shares set shares = ".$num_shares.", value = ".$tot_value.", date = '".$date."', mem_id = ".$mem['id'].", 	receipt_no = '".$receipt_no."', file_ref='".$file_ref."', bank_account = '".$bank_acct_id."', branch_id='".$branch_id."'"))
			{	
				rollback();
				echo("<font color=red>ERROR: Could not insert shares balance for row: ".$i." \n".mysql_error()."</font>");
				exit();
			}
			
		//UPDATE THE BANK ACCOUNT
			if(! mysql_query("update bank_account set account_balance=account_balance+".$tot_value." where id=".$bank_acct_id."")){
				rollback();
				echo("<font color=red>ERROR: Could not update bank/cash account balance for row: ".$i." \n".mysql_error()."</font>");
				exit();
			}
		}
		$tot_collections = 0;
		for($x=4; $x <=$data->sheets[0]['numCols']; $x++){     //REGISTER DEPOSITS, REPAYMENTS OF LOANS, AND OTHER INCOME CONTRIBUTIONS
			$acct_no = $data->sheets[0]['cells'][1][$x];
			$acc = mysql_fetch_array(mysql_query("select * from accounts where account_no='".$acct_no."'"));
			$account_id = $acc['id'];
			$amt_paid = $data->sheets[0]['cells'][$i][$x];
			if($amt_paid ==0)
				continue;
			
			if(preg_match("/^111/", $data->sheets[0]['cells'][1][$x])){  //loan repayment
				$loan_res = mysql_query("select d.id as loan_id from disbursed d join loan_applic applic on d.applic_id=applic.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id join member m on applic.mem_id=m.id where m.mem_no='".$mem_no."' and a.account_no='".$acct_no."' and d.balance>0 and d.date <='".$date."'");
				if(mysql_numrows($loan_res)== 0){
					rollback();
					echo("<font color=red>ERROR: Import rolled back! \n Member ".$mem_no ." does not have a loan on ".$acct_no." product</font>");
					exit();
				}
				while($loan = mysql_fetch_array($loan_res)){
					$sched = mysql_fetch_array(mysql_query("select sum(s.princ_amt) as princ_amt, sum(s.int_amt) as int_amt from schedule s join disbursed d on s.loan_id=d.id where d.id='".$loan['loan_id']."' and s.date <='".$date."'"));
					$paid = mysql_fetch_array(mysql_query("select sum(p.princ_amt) as princ_amt, sum(p.int_amt) as int_amt from payment p join disbursed d on p.loan_id=d.id where d.id='".$loan['loan_id']."'"));
					$int_due = $sched['int_amt'] - $paid['int_amt'];
					$int_due = ($int_due < 0) ? 0 : $int_due;

					$sched = mysql_fetch_array(mysql_query("select sum(s.princ_amt) as princ_amt, sum(s.int_amt) as int_amt from schedule s join disbursed d on s.loan_id=d.id where d.id='".$loan['loan_id']."'"));
					$balance = $sched['princ_amt'] - $paid['princ_amt'];

					if($amt_paid > ($balance + $int_due)){
						
						$int_pay = $amt_paid - $balance;
						$princ_pay = $balance;
						$end_bal = 0;
					}elseif($int_due > $amt_paid){
						$int_pay = $amt_paid;
						$princ_pay =0;
						$end_bal = $balance;
					}else{
						$int_pay = $int_due;
						$princ_pay = $amt_paid - $int_due;
						$end_bal = $balance - $princ_pay;
					}
					if(! mysql_query("insert into payment set loan_id='".$loan['loan_id']."', receipt_no='".$receipt_no."', princ_amt='".$princ_pay."', int_amt='".$int_pay."', mode='cash', begin_bal='".$balance."', end_bal='".$end_bal."', date='".$date."', file_ref='".$file_ref."', bank_account='$bank_acct_id', branch_id= (select branch_id from disbursed where id='".$loan['loan_id']."')")){	
						echo("ERROR: Could not insert into the payment table in line $i");
						rollback();
						exit();
					}
					mysql_query("update disbursed set balance='".$end_bal."' where id='".$loan['loan_id']."'");
					$amt_paid = $amt_paid - $princ_pay - $int_pay;
					if($amt_paid ==0)
						continue;
				}

			}elseif(preg_match("/^211/", $data->sheets[0]['cells'][1][$x])) {  //end if loan repayment
				$mem = mysql_fetch_array(mysql_query("select mem.id as memaccount_id from mem_accounts mem join member m on mem.mem_id=m.id join savings_product p on mem.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.account_no='".$acct_no."' and m.mem_no='".$mem_no."'"));
				if($mem['memaccount_id'] =='' || $mem['memaccount_id'] ==NULL){	
					rollback();
					echo("<font color=red>ERROR: Import rolled back! \n Member ".$mem_no ." does not have an account on ".$acct_no." product in line $i</font>");
					exit();
				}
				if(! mysql_query("insert into deposit set amount='".$amt_paid."', receipt_no='".$receipt_no."', date='".$date."', memaccount_id='".$mem['memaccount_id']."', cheque_no='".$cheque."', trans_date=CURDATE(), flat_value=0, percent_value=0, branch_id='".$branch_id."', file_ref='".$file_ref."', bank_account='".$bank_acct_id."'")){
					echo("ERROR: Could not register deposit in line $i".mysql_error());
					rollback();
					exit();
				}

			}elseif(preg_match("/^4/", $data->sheets[0]['cells'][1][$x])){  //end if savings
				$inc_res = mysql_query("select * from other_income o join accounts a on o.account_id=a.id where a.account_no='".$acct_no."'");
				if(mysql_numrows($inc_res)== 0){
					rollback();
					echo("<font color=red>ERROR: Import rolled back! \n You need to first deposit at least once on this account ". $acct_no." individually, before Bulk Posting can be done. Line $i</font>");
					exit();
				}
				if(! mysql_query("insert into other_income set amount='".$amt_paid."', date='".$date."', receipt_no='".$receipt_no."', account_id='".$account_id."', file_ref='".$file_ref."', bank_account='".$bank_acct_id."', branch_id='".$branch_id."', contact='".$mem_no."', cheque_no='".$cheque_no."', mode='cash'")){	
					rollback();
					echo("<font color=red>ERROR: Import rolled back! \n You need to first deposit at least once on this account ". $acct_no." individually, before Bulk Posting can be done. Line $i</font>");
					exit();
				}
			}elseif(preg_match("/^212/", $data->sheets[0]['cells'][1][$x])){  //IF LOAN PAYABLE
				
				if(! mysql_query("insert into payable set amount='".$amt_paid."', date='".$date."',  maturity_date='".$date."', vendor='".$mem_no."', account_id='".$account_id."',  bank_account='0', branch_id='".$branch_id."'")){	
					rollback();
					echo("<font color=red>ERROR: Import rolled back! \n You need to first deposit at least once on this account ". $acct_no." individually, before Bulk Posting can be done. Line $i</font>");
					exit();
				}
			}else{
				rollback();
				echo("<font color=red>ERROR: Import rolled back! \n Could not register transaction to account ".$acct_no." in line $i, you cant do bulk posting to this account.</font>");
				exit();
			}
			//UPDATE THE BANK ACCOUNT
			if(! mysql_query("update bank_account set account_balance=account_balance+".$amt_paid." where id=".$bank_acct_id."")){	
				rollback();
				echo("<font color=red>ERROR: Could not update bank/cash account balance for row: ".$i." \n".mysql_error()."</font>");
				exit();
			}
		}
	}
	//REGISTER BLOCK
	if(! mysql_query("insert into bulk_post set date='".$date."', contact='".$contact."', file_ref='".$file_ref."', cheque_no='".$cheque_no."', branch_id='".$branch_id."'")){	
		rollback();
		echo("<font color=red>ERROR: Import rolled back! \n Could not register the block post</font>");
		exit();
	}
	commit();
	echo("<font color=red>The bulk posting registered successfully!</font>");
}



//LIST INDIVIDUAL POSTS
function list_ind_post($file_ref){
	$resp = new xajaxResponse();
	$sth = mysql_query("select 'Shares' as type, s.receipt_no as receipt_no, m.mem_no as mem_no, s.value as amount from shares s join member m on s.mem_id=m.id where s.file_ref='".$file_ref."' UNION select 'Savings' as type, d.receipt_no as receipt_no, m.mem_no as mem_no, d.amount as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join member m on mem.mem_id=m.id where d.file_ref='".$file_ref."' UNION select 'Loan Repayment' as type, p.receipt_no as receipt_no, m.mem_no as mem_no, (princ_amt + int_amt) as amount from payment p join disbursed d on p.loan_id=d.id join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id where p.file_ref='".$file_ref."' UNION select 'Income Contribution' as type, i.receipt_no as receipt_no, i.contact as mem_no, i.amount as amount from other_income i where i.file_ref='".$file_ref."' order by mem_no");
	
	$grand =0;
	$content .= "<table height=100 border='1' cellpadding='1' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='80%' id='AutoNumber2' align=center>";
	//$resp->AddAlert(mysql_error());
	if(mysql_numrows($sth) > 0){
		$content .= "<tr class='headings'><td><b>No</b></td><td><b>Member No</b></td><td><b>Transaction</b></td><td><b>Receipt No</b></td><td><b>Amount</b></td></tr>";
		$i=1;
		while($row = mysql_fetch_array($sth)){
			$color = ($i%2 == 0) ? "white" : "lightgrey";
			$content .= "<tr bgcolor=$color><td>".$i."</td><td>".$row['mem_no']."</td><td>".$row['type']."</td><td>".$row['receipt_no']."</td><td>".number_format($row['amount'], 2)."</td></tr>";
			$i++;
			$grand += $row['amount'];
		}
		$content .= "<tr class='headings'><td colspan=4>TOTAL</td><td>".number_format($grand, 2)."</td></tr>";
	}
	$content .= "</table>";
	$resp->AddAssign("display_div", "innerHTML", $content);
	return $resp;
}

function list_bulk($file_ref, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday){
	$resp = new xajaxResponse();
	
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
	//$resp->AddAlert($from_date);
	$sth = mysql_query("select * from bulk_post where date >= '".$from_date."' and date <= '".$to_date."' and file_ref like '%".$file_ref."%' order by date asc");
	
	$content = "<br><center><font color=#00008b size=3pt><B>LIST BULK POSTING</B></font></center><table border='0' width=100% cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111'  id='AutoNumber2' align=center><tr><td>File Ref<input type='text' id='file_ref'><b>From</b>".month_array('from_', $from_year, $from_month).mday('from_', $from_mday). " <b>To</b>".month_array('to_', $to_year, $to_month).mday('to_', $to_mday)."<input type=button value='Show Report' onclick=\"xajax_list_bulk(getElementById('file_ref').value, getElementById('from_year').value, getElementById('from_month').value, getElementById('from_mday').value, getElementById('to_year').value, getElementById('to_month').value, getElementById('to_mday').value);\">
	<table border='1' cellpadding='0' cellspacing='1' height=200 style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>";
	//$resp->AddAssign("status", "innerHTML", "select * from bulk_post where date >= '".$from_date."' and date <= '".$to_date."' and file_ref like '%".$file_ref."%' order by date asc");
	//return $resp;
	if(mysql_numrows($sth) >0){
		$content .= "<tr class='headings'><td><b>Date</b></td><td><b>File Reference</b></td><td><b>Total Amt</b></td><td><b>Contact</b></td><td><b>Action</b></td><td><b></b></td></tr>";
		$grand = 0;
		while($row = mysql_fetch_array($sth)){
			$shares = mysql_fetch_array(mysql_query("select sum(value) as amount from shares where file_ref='".$row['file_ref']."'"));
			$save = mysql_fetch_array(mysql_query("select sum(amount) as amount from deposit where file_ref='".$row['file_ref']."'"));
			$inc = mysql_fetch_array(mysql_query("select sum(amount) as amount from other_income where file_ref='".$row['file_ref']."'"));
			$pay = mysql_fetch_array(mysql_query("select sum(princ_amt) as princ_amt, sum(int_amt) as int_amt from payment where file_ref='".$row['file_ref']."'"));
			$total_amt = $shares['amount'] + $save['amount'] + $pay['princ_amt'] + $pay['int_amt'] + $inc['amount'];
			
			$content .= "<tr bgcolor=lightgrey><td>".$row['date']."</td><td>".$row['file_ref']."</td><td>".number_format($total_amt, 2)."</td><td>".$row['contact']."</td><td> <a href=# onclick=\"xajax_list_ind_post('".$row['file_ref']."')\">View Individual Posts</a></td></tr>";
			$grand += $total_amt;
		}
		$content .= "<tr class='headings'><td colspan=2></td><td colspan=3>".number_format($grand, 2)."</td></tr>";
	}elseif($from_year !=''){
		$content .="<tr bgcolor=lightgrey><td><font color=red>No bulk posting done in the selected period!</font></td></tr>";
	}else{
		$content .="<tr bgcolor=lightgrey><td><font color=red>Select the period for your report!</font></td></tr>";
	}
	$content .= "</table></td></tr></table>";
	$resp->AddAssign("display_div", "innerHTML", $content);
	return $resp;
}

function delete_bulk($id, $file_ref, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday){
	$resp = new xajaxResponse();
	
	$resp->AddConfirmCommands(1, "Do you really want to delete?");
	$resp->AddScriptCall('xajax_delete2_bulk', $id, $file_ref, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday);
	return $resp;
}

function delete2_bulk($id, $file_ref, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday){
	$resp = new xajaxResponse();
	$row = mysql_fetch_array(mysql_query("select * from bulk_post where id='".$id."'"));
	$nowfile_ref = $row['file_ref'];

	//SHARES
	mysql_query("delete from shares where file_ref='".$nowfile_ref."'");
	//savings
	mysql_query("delete from deposit where file_ref='".$nowfile_ref."'");
	//other income
	mysql_query("delete from other_income where file_ref='".$nowfile_ref."'");
	return $resp;
}


//LICENCE
require_once("spl_license/rsa.class");
require_once("spl_license/class.base32.php");
function evalKey()
{
	$resp = new xajaxResponse();
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
	$b32->setCharset($b->csSafe);
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
evalKey();
set_bank_balance();
//LOGIN
if($_SESSION['user_id'] ==0 || !isset($_SESSION['user_id']))
		header("Location: index.php");
//check whether the user is logged in
/* if(($_SESSION['user_id']==0 || !isset($_SESSION['user_id'])) && $_SESSION['try']<>'yes'){
	echo($_SESSION['try']);
	header("Location: index.php");
}
*/
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
	<title><?php echo(writeVersion()); ?></title>
	<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	
	<link rel="stylesheet" type="text/css" href="ajaxtabs/ajaxtabs.css" />

	<script type="text/javascript" src="ajaxtabs/ajaxtabs.js">

/***********************************************
* Ajax Tabs Content script- \A9 Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

	</script>
	</head>

<?php
//menu("1");
inner_header();
echo("<div id='sub_menu'>");
echo("<table><tr><td></td></tr></table></div>");
echo("<div id='status'>");
echo("</div>");
?>
  <!--webbot bot="SaveResults" u-file="fpweb:///_private/form_results.csv" s-format="TEXT/CSV" s-label-fields="TRUE" -->
  <?php
echo("<table height=290 border='1' cellpadding='2' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2'><tr><td>");
echo("<div id=status>");

if($_POST['submit']=="Upload" && $_FILES['file']['tmp_name']<>""){
	//$data = file_get_contents($_FILES['file']['tmp_name']);	
	$bank_account = $_POST['bank_account'];
	$contact = $_POST['contact'];
	$file_ref = $_POST['file_ref'];
	$cheque_no = $_POST['cheque_no'];
	$year = $_POST['year'];
	$month = $_POST['month'];
	$mday = $_POST['mday'];
	$branch_id = $_POST['branch_id'];

	move_uploaded_file($_FILES['file']['tmp_name'], $_FILES['file']['name']);
	insert_bulk($bank_account, $contact, $file_ref, $cheque_no,  $year, $month, $mday, $branch_id, $_FILES['file']['name']);
}elseif($_POST['submit']=='Upload'  && $_FILES['file']['tmp_name']==""){
	?>
	<script language='javascript'>
		alert("Bulk Posting rejected! Fill in all the fields");
	</script>
	<?php
	//exit();
}
echo("</div><div id='display_div'>");
add_bulk();
echo("</div>");
echo("</td></tr></table>");
footer();
?>
