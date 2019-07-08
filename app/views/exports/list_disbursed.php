

<?php


if(!isset($_GET['format']))
echo("<head>
	<title></title>

		<style>
.borderless td {
    border: none;
}
.borderless th {
    border-left: none;
    border-right: none;
     border-top: solid 1px;
      border-bottom: solid 1px;
}
.green{
    background-color: #7D8488;
    margin-bottom: 10px;
    padding-left: 20px;
    padding-right: 20px;
    padding-top: 5px;
    padding-bottom: 5px;
    font-size: 16px;
    color: #fff;
    font-style: bold;

  }
  .right{
  	text-align:right;
  }
	</style>
	</head><body>");

//LIST DISBURSEMENTS
function list_disbursed($cust_name, $cust_no, $account_name, $loan_officer, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday, $format,$branch_id){
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:"and applic.branch_id=".$branch_id;



//get officer
         $officen = mysql_query("select * from employees where employeeId='".$loan_officer."'");
               $off = mysql_fetch_assoc($officen);
               $offnum = mysql_num_rows($officen);
if($offnum == 1){ $offcer_didplay =  $off['firstName'].' '.$off['lastName'];} else{$offcer_didplay = 'All Officers';}

//get branch
$bran = mysql_query("select * from branch where branch_no='".$branch_id."'");
               $bra = mysql_fetch_assoc($bran);
               $branum = mysql_num_rows($bran);
if($branum == 1){ $branch_didplay =  $bra['branch_name'];} else{$branch_didplay = 'All branches';}

//get loan product

if($account_name == ""){ $product_didplay = "All Products";} else{$product_didplay = $account_name;}

//report name
$reportname = "Disbursement Report"; 
//get company name.
$com = mysql_query("select * from users where userId = '".CAP_Session::get('userId')."'");
$comp = mysql_fetch_array($com);
//var_dump($comp);
if($com){
$companyd = $comp['companyId'];
$companyb = $comp['branch'];
$se = mysql_query("select * from company where companyId='".$companyd."'");
$sel = mysql_fetch_assoc($se);
if($se){
$compa = $sel['companyName'];
$address = "Physical Address: ".$sel['country']." ".$sel['city']." ".$sel['address1']." ".$sel['address2']." | Telephone: ".$sel['telephone']." | Email: ".$sel['email'];
}else{
    $compa = "unknown2".mysql_error();
}

}else{
$companyd = "unknown".mysql_error();    
}

               $content .=' <div class="panel panel-default">
              <div class="panel-body">
              <div class="green">
               <label><b>'.$compa.' - '.$reportname.' </b><span class="small right ">'.$address.'</span></label> </div>
               
         
     </div>

            </div>';

	$content .= "<table class='table borderless' width='98%' id='AutoNumber2' align=center><tr><td>
		<table class='table borderless' width='98%' id='AutoNumber2' align=center>";
	if($from_year==''){
			$from_date = "0000-00-00 00:00:00";
	$to_date = date("Y-m-d h:i:s",time());
	}
	$name = ($cust_name == 'All') ? "" : $cust_name;
	$mem_no = ($cust_no == 'All') ? "" : $cust_no;
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
	if($loan_officer >0)
		$officer = "o.id='".$loan_officer."'";
	else
		$officer = "o.id > 0";
	
	 $sth = mysql_query("select d.id as id, applic.id as applic_id,applic.`date` as apply_date, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.cheque_no as cheque_no, d.date as date, d.balance as balance, d.period as loan_period, d.int_rate as int_rate, d.int_method as int_method, d.grace_period as grace_period, d.writeoff_period as writeoff_period, d.arrears_period as arrears_period, d.written_off as written_off, d.bank_account as bank_account from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId");
	
	if(@ mysql_numrows($sth) == 0){
		$content .= "<tr><td><center><font color=red>No disbursed loans in your search options</font></center></td></tr></table></td></tr></table>";
	}
	
	$former_officer ="";
	$i=$stat+1;
	while($row = mysql_fetch_array($sth)){
		$officer = $row['of_first_name']." ".$row['of_last_name'];
		if(strcmp($former_officer, $officer) != 0){
			$content .="<tr class='headings'><th><b>No</b></th><th><b>Member Name</b></th><th><b>MemberNo</b></th><th><b>Date</b></th><th><b>Amount</b></th><th><b>Annual Int. Rate(%)</b></th><th><b>Method</b></th><th><b>Loan Period (Months)</b></th><th><b>Grace Period (Months)</b></th><th><b>Arrears Period</b></th><th><b>Write-off Period (Months)</b></th><th><b>Disbursement Account</b></td></tr>";
			$former_officer = $officer;
		}
		if($row['balance'] != $row['amount'] && $row['written_off']==0)
			$edit = "Payment Started";
		elseif($row['written_off'] == '1')
			$edit = "Written Off";
		else
			$edit = "<a href=# onclick=\"xajax_edit_disbursed('".$row['id']."', '".$row['applic_id']."');\">Edit</a>";
		$bank_res = mysql_query("select a.name as account_name, a.account_no as account_no from bank_account b join accounts a on b.account_id=a.id where b.id='".$row['bank_account']."'");
		$bank = mysql_fetch_array($bank_res);
		//$color=($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr><td>".$i."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".$row['date']."</td><td>".number_format($row['amount'], 2)."</td><td>".$row['int_rate']."</td><td>".$row['int_method']."</td><td>".($row['loan_period']/30)."</td><td>".($row['grace_period']/30)."</td><td>".($row['arrears_period']/30)."</td><td>".($row['writeoff_period']/30)."</td><td>".$bank['account_no']. " - ".$bank['account_name']."</td></tr>";
		$i++;
		$amt_sub_total += $row['amount'];
	}
	$content .= "<tr class='headings'><th colspan='1'><b>TOTAL</b></th><th colspan='6'><b>".number_format($amt_sub_total, 2)."</b></th><th colspan='5'>&nbsp;</th></tr>";
	$content .= "</table></th></tr></table>";
	export($format, $content);
}
//reports_header();
list_disbursed($_GET['cust_name'], $_GET['cust_no'], $_GET['account_name'], $_GET['loan_officer'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday'], $_GET['format'],$_GET['branch_id']);
//reports_footer();
?>
