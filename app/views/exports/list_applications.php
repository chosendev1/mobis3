

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

//LIST LOAN APPLICATIONS
function list_applics($cust_name, $cust_no, $account_name, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday, $category, $format,$branch_id){
	if($category=='all' || $category==''){
		$head= "LIST OF LOAN APPLICATIONS";
		$choose = "";
	}elseif($category == 'approval'){
		$head = "LIST OF LOAN APPLICATIONS AWAITING APPROVAL";
		$choose = " applic.approved='0' and ";
	}elseif($category == 'disbursement'){
		$head = "LIST OF LOANS AWAITING DISBURSEMENT";
		$choose = " applic.approved ='1' and dis.id is null and ";
	}
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:" and dis.branch_id=".$branch_id;
	
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
               <label><b>'.$compa.' - '.$head.' </b><span class="small right ">'.$address.'</span></label> </div>
               
         
     </div>

            </div>';
	$content .="<table class='table' width=90% align=center><tr><td>
		<table class='table borderless' width='100%' id='AutoNumber2' align=center>";
	if($from_year==''){
			$from_date = "0000-00-00 00:00:00";
	$to_date = date("Y-m-d h:i:s",time());
	}
	$name = ($cust_name == 'All') ? "" : $cust_name;
	$mem_no = ($cust_no == 'All') ? "" : $cust_no;
	$from_date = "0000-00-00 00:00:00";
	$to_date = date("Y-m-d h:i:s",time());
	$sth = mysql_query("select dis.id as disbursed_id, applic.group_id as group_id, applic.approved as approved, applic.reason as reason, (DATEDIFF(applic.date, m.dob)/365) as age, o.firstName as of_firstName, o.lastName as of_lastName, m.id as mem_id, m.sign_name as sign_name, m.photo_name as photo_name, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, applic.amount as amount, applic.date as date, applic.guarantor1 as guarantor1, applic.guarantor2 as guarantor2, applic.id as applic_id, a.name as account_name, a.account_no as account_no, m.id as mem_id, p.based_on as based_on from loan_applic applic join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id join employees o on applic.officer_id=o.employeeId left join disbursed dis on applic.id=dis.applic_id");
	if(@ mysql_num_rows($sth) == 0){
		$content .= "<tr><td><center><font color=red>No applications found in your search options!</font></center></td></tr></table></td></tr></table>";
	}

	$former_officer ="";
	$i=$stat+1;
	while($row = mysql_fetch_array($sth)){
		$officer = $row['of_first_name']." ".$row['of_last_name'];
		if(strcmp($former_officer, $officer) != 0){
			$content .="<tr class=''><th colspan=16><b>Loan Officer:  ".$officer."</b></th></tr> <tr class=''><th><b>No</b></th><th><b>Name</b></th><th><b>MemberNo</b></th><th><b>Age</b></th><th><b>Product</b></th><th><b>Amount</b></th><th><b>Reason</b></th><th><b>Collateral</b></th><th><b>Guarantors</b></th><th><b>Date</b></th><th><b>Loan Bal</b></th><th><b>Possible Loan on Shares</b></th><th><b>Possible Loan on Savings</b></th><th><b>Status</b></th></tr>";
			$former_officer = $officer;
		}
		$col_res = mysql_query("select * from collateral where applic_id='".$row['applic_id']."'");
		$col = @mysql_fetch_array($col_res);
		if($col['collateral1'] == NULL){
			$collateral1 = "--";
			$value1 = "--";
		}else{
			$collateral1 = $col['collateral1'];
			$value1 = "(".$col['value1'].")";
		}

		if($col['collateral2'] == NULL){
			$collateral2 = "--";
			$value2 = "--";
		}else{
			$collateral2 = $col['collateral2'];
			$value2 = "(".$col['value2'].")";
		}
		$sub = "<table><tr><td>".$collateral1."</td><td>".$value1."</td></tr> <tr><td>".$collateral2." </td><td>".$value2."</td></tr></table>";
		$x=1;
		if($row['group_id'] > 0){
			$list = $row['guarantor1'] .", ".$row['guarantor2'];
			$guarantors = explode(', ', $list);
			$guar='';
			while($id= current($guarantors)){
				$cat_res = mysql_query("select * from member where id='".$id."'");
				$cat = mysql_fetch_array($cat_res);
				$guar .= $x. ". ".$cat['first_name']." " .$cat['last_name']."<br>";
				$x++;
				next($guarantors);
			}
		}else{
			$cat_res = mysql_query("select * from member where id='".$row['guarantor1']."' or id='".$row['guarantor2']."'");
			$x=1;
			$guar='';
			while($cat = mysql_fetch_array($cat_res)){
				$guar .= $x. ". ".$cat['first_name']." " .$cat['last_name']."<br>";
				$x++;
			}
		}

		//LOAN BALANCE
		$loan_res = mysql_query("select sum(l.balance) as balance from disbursed l join loan_applic applic on lapplic_id=applic.id where applic_mem_id='".$row['mem_id']."' and l.balance >0");
		$loan = @mysql_fetch_array($loan_res);
		$loan_balance = ($loan['balance'] == NULL) ? 0 : $loan['balance'];
		//MAX PERCENT OF SAVINGS OR SHARES THAT CAN BE LOAN
		$limit_res = mysql_query("select * from branch");
		$limit = mysql_fetch_array($limit_res);
		//POSSIBLE LOAN ON SAVINGS FOR THIS MEMBER
		$dep_res = mysql_query("select sum(d.amount - d.flat_value - d.percent_value) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.secure_loan='yes' and m.mem_id='".$row['mem_id']."'");
		$dep = mysql_fetch_array($dep_res);
		$dep_amt = ($dep['amount'] == NULL) ? 0 : $dep['amount'];

		$with_res = mysql_query("select sum(w.amount + w.flat_value + w.percent_value) as amount from withdrawal w join mem_accounts m on w.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.secure_loan='yes' and m.mem_id='".$row['mem_id']."'");
		$with = mysql_fetch_array($with_res);
		$with_amt = ($with['amount'] == NULL) ? 0 : $with['amount'];

		$pledged_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id where p.secure_loan='no' and m.mem_id='".$row['mem_id']."' and p.account_id in (select pledged_account_id from savings_product)");
		$pledged = mysql_fetch_array($pledged_res);
		$pledged_amt = ($pledged['amount'] == NULL) ? 0 : $pledged['amount'];
		$balance = $dep_amt - $with_amt - $pledged_amt;
		$possible_savings_loan = ($balance * $limit['loan_save_percent']) / 100;
		
		//POSSIBLE LOAN ON SHARES	
		$shares_res = mysql_query("select sum(value) as value from shares where mem_id='".$row['mem_id']."'");
		$shares = @mysql_fetch_array($shares_res);
		$shares_amt = ($shares['value'] == NULL) ? 0 : $shares['amount']; 
			
		$donated_res = mysql_query("select sum(value) as value from share_transfer where to_id='".$row['mem_id']."'");
		$donated = @mysql_fetch_array($donated_res);
		$donated_amt = ($donated['value'] == NULL) ? 0 : $donated['value'];

		$trans_res = mysql_query("select sum(value) as value from share_transfer where from_id='".$row['mem_id']."'");
		$trans = @mysql_fetch_array($trans_res);
		$trans_amt = ($trans['value'] == NULL) ? 0 : $trans['value'];

		$pledged_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts mem on d.memaccount_id=mem.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where a.name='Compulsory Shares' and mem.mem_id='".$row['mem_id']."'");
		$pledged = @mysql_fetch_array($pledged_res);
		$pledged_amt = ($pledged['amount'] == NULL) ? 0 : $pledged['amount'];

		$balance = $shares_amt + $donated_amt - $trans_amt - $pledged_amt;
		$possible_shares_loan = ($balance * $limit['loan_share_percent']) / 100;
		
		//PASS LIMITS TO DISBURSEMENT MODULE
		if($row['based_on'] == 'savings')
			$possible_amt = $possible_savings_loan;
		else
			$possible_amt = $possible_shares_loan;
		if($row['disbursed_id'] != NULL){
			$approving = "Disbursed";
			$edit = "Disbursed";
		}elseif($row['approved'] == '0')
			$approving = "Pending Approval";
		else
			$approving = "Pending Disbursement</a>";
		if($row['disbursed_id'] == NULL){
			$edit = "<a href=# onclick=\"xajax_edit_applic('".$row['applic_id']."');\">Edit/ Collateral</a>";		
		}
		if($row['disbursed_id'] == NULL && $row['approved'] ==1)
			$disburse="<a href=# onclick=\"xajax_add_disbursed('".$row['applic_id']."', '".$row['amount']."', '".$possible_amt."', '".$row['based_on']."');\">Disburse</a>";
		else
			$disburse = "--";

		//$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr><td>".$i."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".floor($row['age'])."</td><td>".$row['account_no']." - ".$row['account_name']."</td><td>".number_format($row['amount'], 2)."</td><td>".$row['reason']."</td><td>".$sub."</td><td>".$guar."</td><td>".$row['date']."</td><td>".number_format($loan_balance, 2)."</td><td>".number_format($possible_shares_loan, 2)."</td><td>".number_format($possible_savings_loan, 2)."</td><td>".$approving."</td></tr>";
		$i++;
		$amt_sub_total += $row['amount'];
	}
	$content .= "<tfooter><tr class=''><th colspan='5'><b>TOTAL</b></th><th colspan='6'><b></b></th><th colspan='3'><b>".number_format($amt_sub_total, 2)."</b></th></tr>";
	$content .= "</table></th></tr></tfooter></table>";
	export($format, $content);
}

//reports_header();
list_applics($_GET['cust_name'], $_GET['cust_no'], $_GET['account_name'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday'], $_GET['category'], $_GET['format'],$_GET['branch_id']);
//reports_footer();
?>
