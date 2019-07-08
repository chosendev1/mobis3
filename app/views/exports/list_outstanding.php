

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

//LIST OUTSTANDING
function list_outstanding($cust_name, $cust_no, $account_name, $loan_officer, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday, $format,$branch_id){
	
	$calc = new Date_Calc();
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:"and d.branch_id=".$branch_id;

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
$reportname = "OUTSTANDING BALANCE REPORT"; 
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
		//$content .= "<tr><td><center><font color=red>Please select the disbursed loans you would like to list!</font></center></td></tr></table></td></tr></table>";
	}
	$name = ($cust_name == 'All') ? "" : $cust_name;
	$mem_no = ($cust_no == 'All') ? "" : $cust_no;
	$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
	if($loan_officer >0)
		$officer = "o.id='".$loan_officer."'";
	else
		$officer = "o.id > 0";
	
	$sth = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.sex as sex, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, d.last_pay_date as last_pay_date, d.pay_freq as pay_freq, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year,p.int_rate as intrestrate from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId");
	
	if(@ mysql_numrows($sth) == 0){
		$content .= "<tr><td><center><font color=red>No outstanding loans in your search options</font></center></td></tr></table></td></tr></table>";
	}
	
	$former_officer ="";
	$i=$stat+1;
	while($row = mysql_fetch_array($sth)){
		$officer = $row['of_first_name']." ".$row['of_last_name'];
		if(strcmp($former_officer, $officer) != 0){
			$content .="<thead><th><b>No</b></th><th><b>Member No.</b></th><th><b>Name</b></th><th><b>Sex</b></th><th><b>Loan Amt</b></th><th><b>Intrest Amt</b></th><th><b>Disbursment Date</b></th><th><b>Expiry Date</b></th><th><b>Total Paid</b></th><th><b>Loan Balance</b></th><th><b>Amount in arrears</b></th></thead>";
			$former_officer = $officer;
		}

				//ARREARS
		$arrears_days = $calc->dateToDays(date('d'), date('m'), date('Y')) - $row['arrears_period'];
		$arrears_date = $calc->daysToDate($arrears_days, '%Y-%m-%d');
		$sched_res = mysql_query("select sum(princ_amt) as amount from schedule where loan_id='".$row['id']."' and date < '".$arrears_date."'");
		
		$sched = @mysql_fetch_array($sched_res);
				
		$paid_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from payment where loan_id='".$row['id']."' and date <= NOW()");
		
		$paid = mysql_fetch_array($paid_res);
		$sched_amount = ($sched['amount'] == NULL) ? "0" : $sched['amount'];
		$paid_amt = ($paid['amount'] == NULL) ? "0" : $paid['amount'];
		$paid_int = ($paid['int_amt'] == NULL) ? "0" : $paid['int_amt'];
		$arrears_amt = $sched_amount - $paid_amt;
		
		if($arrears_amt <= 0){
			$princ_arrears ="--";
			$int_arrears = "--";
			$total_arrears ="--";
			$total_amt_arrears = 0;
			$int_amt_arrears =0;
			$princ_amt_arrears =0;
		}else{
			$princ_arrears = $arrears_amt;
			$princ_amt_arrears = $arrears_amt;
			
			/*
			$arrears_days =$arrears_days - $calc->dateToDays($row['last_mday'], $row['last_month'], $row['last_year']);
			$no_months = floor($arrears_days / 30);
			if($no_months <= 0 ){
				$int_arrears = 0;		
			}elseif($row['int_method'] == 'Declining Balance')
				$int_arrears = ceil((($row['balance'] * $row['int_rate']/100) /12) * $no_months);
			elseif($row['int_method'] == 'Flat')
					$int_arrears = ceil((($row['disbursed_amt'] * $row['int_rate']/100) /12) * $no_months);
			*/
			$intsched = mysql_fetch_array(mysql_query("select sum(int_amt) as int_amt from schedule where  date < '".$arrears_date."' and loan_id='".$row['id']."'"));
			$int_arrears =($intsched['int_amt'] == NULL) ? 0 : $intsched['int_amt'];
			$intpaid = mysql_fetch_array(mysql_query("select sum(int_amt) as int_amt from payment where date < '".$arrears_date."' and loan_id='".$row['id']."'"));
			$int_arrears_paid =($intpaid['int_amt'] == NULL) ? 0 : $intpaid['int_amt'];
			
			$int_arrears -= $int_arrears_paid;
			$total_arrears = $princ_arrears + $int_arrears;
			$total_amt_arrears = $total_arrears;
			$int_amt_arrears = $int_arrears;
			$int_arrears = ($int_arrears == 0) ? "--" : $int_arrears;
		}
		
		//CALCULATE DUE PRINCIPAL
		$sched_res = mysql_query("select sum(princ_amt) as amount, sum(int_amt) as int_amt from schedule where loan_id='".$row['id']."' and date <= NOW()");
		$sched = @mysql_fetch_array($sched_res);
		$sched_amt = ($sched['amount'] == NULL) ? 0 : $sched['amount'];
		$sched_int = ($sched['int_amt'] == NULL) ? 0 : $sched['int_amt'];
		$due_princ_amt = $sched_amt - $paid_amt;  // - $arrears_amt;	
		$due_int_amt = $sched_int - $paid_int;
		
		if($due_princ_amt <= 0){
			$due_princ = "--";
			$due_int = "--";
			$due_int_amt=0;
		}else{
				$due_princ = $due_princ_amt;
			if($due_int_amt >0){	
				$due_int = $due_int_amt;
			}else
				$due_int="--"; 
			
			
			
			/* $due_princ = $due_princ_amt;
			//CALCULATE DUE INTEREST
			$intsched = mysql_fetch_array(mysql_query("select sum(int_amt) as int_amt from schedule where date> '".$arrears_date."' and date <= CURDATE() and loan_id='".$row['id']."'"));
			$due_int_amt =($intsched['int_amt'] == NULL) ? 0 : $intsched['int_amt'];
			$intpaid = mysql_fetch_array(mysql_query("select sum(int_amt) as int_amt from payment where date> '".$arrears_date."' and date <= CURDATE() and loan_id='".$row['id']."'"));
			$paid_int_amt =($intpaid['int_amt'] == NULL) ? 0 : $intpaid['int_amt'];
			$due_int_amt = ceil($due_int_amt - $paid_int_amt);

		
			$int_days = $calc->dateToDays(date('d'), date('m'), date('Y')) - $calc->dateToDays($row['last_mday'], $row['last_month'], $row['last_year']) - $row['arrears_period'];
		
			if($int_days >0){
				$no_months = floor($int_days /30);
				if($row['int_method'] =='Declining Balance'){
					$due_int_amt = ceil((($row['balance'] * $row['int_rate']/100) / 12) * $no_months);
					$due_int = $due_int_amt;
				}elseif($row['int_method'] == 'Flat'){
					$due_int_amt = ceil((($row['disbursed_amt'] * $row['int_rate']/100) / 12) * $no_months);
					$due_int = $due_int_amt;
				}
			}else
			$due_int="--";
			*/
		}		
		$total_amt_due = $due_int_amt + $due_princ_amt;
		$total_due = ($total_amt_due <= 0) ? "--" : $total_amt_due;
		
		$pay = "<a href='list_payments.php?loan_id=".$row['id']."&applic_id=".$row['applic_id']."&cust_name=".$cust_name."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&princ_arrears=".$princ_amt_arrears."&int_arrears=".$int_amt_arrears."&princ_due=".$due_princ_amt."&int_due=".$due_int_amt."&from-year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&status=outstanding' target=blank()>Payments</a>";
		
		$schedule = "<a href='list_schedule.php?loan_id=".$row['id']."&applic_id=".$row['applic_id']."&cust_name=".$cust_name."&cust_no=".$cust_no."&account_name=".$account_name."&loan_officer=".$loan_officer."&princ_arrears=".$princ_amt_arrears."&int_arrears=".$int_amt_arrears."&princ_due=".$due_princ_amt."&int_due=".$due_int_amt."&from_year=".$from_year."&from_month=".$from_month."&from_mday=".$from_mday."&to_year=".$to_year."&to_month=".$to_month."&to_mday=".$to_mday."&status=outstanding' target=blank()>Schedule</a>";

		//PENALTY
		$pen_res = mysql_query("select sum(amount) as amount from penalty where status='pending' and loan_id='".$row['id']."'");
		$pen = mysql_fetch_array($pen_res);
		$penalty = ($pen['amount'] == NULL) ? "--" : $pen['amount'];
		//$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr><td>".$i."</td><td>".$row['mem_no']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['sex']."</td><td>".number_format($row['amount'], 2)."</td><td>".number_format($intrestuded, 2)."</td><td>".$row['date']."</td><td>".$expiredate."</td><td>".number_format(($row['amount']-$row['balance']), 2)."</td><td>".number_format($row['balance'], 2)."</td><td>".number_format($princ_arrears, 2)."</td></tr>";
		$i++;
	// GET SUB TOTALS
		$amt_sub_total += $row['amount']; 
		$bal_sub_total += $row['balance']; 
		$princ_arrears_sub_total += $princ_arrears; 
		$int_arrears_sub_total += $int_arrears;
		$tot_arrears_sub_total += $total_arrears;
		$penalty_sub_total += $penalty; 
		$princ_due_sub_total += $due_princ; 
		$int_due_sub_total += $due_int; 
		$tot_amt_sub_total += $total_due;
	}
	// PRINT SUB TOTALS
	$content .= "<tfooter><tr><th>TOTAL</th><th></th><th><b></b></th><th><b></b></th><th><b>".number_format($amt_sub_total, 2)."</b></th><th><b>".number_format($int_sub_total, 2)."</b></th><th><b></b></th><th><b></b></th><th><b>".number_format(($amt_sub_total-$bal_sub_total), 2)."</b></th><th><b>".number_format($bal_sub_total, 2)."</b></th><th><b>".number_format($princ_arrears_sub_total, 2)."</b></th></tr></tfooter>";
	$content .="</table></td></tr></table>";
	export($format, $content);
}

//reports_header();
list_outstanding($_GET['cust_name'], $_GET['cust_no'], $_GET['account_name'], $_GET['loan_officer'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday'], $_GET['format'],$_GET['branch_id'],$_GET['num_rows'],$_GET['stat']);
//reports_footer();
?>
