<?php

$this->load->view("mpdf/mpdf");
//queries
function headerfun($category){
 $from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
  $to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
  $to_date2 = sprintf("%2d", $to_month);

$monthName = date('F', mktime(0, 0, 0, $to_date2, 10)); // March

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

if($category=='all' || $category==''){
    $reportname= "LOAN APPLICATIONS";
  }elseif($category == 'approval'){
    $reportname = "LOAN APPLICATIONS AWAITING APPROVAL";
  }elseif($category == 'disbursement'){
    $reportname = "LOANS AWAITING DISBURSEMENT";
  }
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
$address = "Address: ".$sel['city']." ".$sel['address1']." <br> Telephone: ".$sel['telephone']." <br> Email: ".$sel['email'];
}else{
    $compa = "unknown2".mysql_error();
}

}else{
$companyd = "unknown".mysql_error();    
}

            //table
            $content = '<table width="100%"><tr>
            <td><img src="'.base_url().'logos/'.$sel['logo'].'" alt = "logo" style="width:5px; height:50px;padding-top:0px;"/></td>
           
            <td align=center width="50%"><label><b><h1>'.$compa.' </h1></b>'.$address.'
<br> <h4 style="color:red; " >'.$reportname.'</h4>
            </label></td><td>&nbsp;</td>
            <td>Printed on: '.date('Y-m-d h:i:s').'<br>
              Printed by: '.ucwords(CAP_Session::get('username')).'<br>
             </td>
            </tr></table>';

return $content;
}

//body
function bodyfun($cust_name,$branch_id, $cust_no, $account_name, $from_date,$to_date,$category){
  $name = ($cust_name == 'All') ? "" : $cust_name;
  $mem_no = ($cust_no == 'All') ? "" : $cust_no;
  
  
  $sth = mysql_query("select dis.id as disbursed_id, applic.group_id as group_id, applic.approved as approved, applic.reason as reason, (DATEDIFF(applic.date, m.dob)/365) as age, o.firstName as of_firstName, o.lastName as of_lastName, m.id as mem_id, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, applic.amount as amount, applic.date as date, applic.guarantor1 as guarantor1, applic.guarantor2 as guarantor2, applic.id as applic_id, a.name as account_name, a.account_no as account_no, m.id as mem_id, p.based_on as based_on from loan_applic applic join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id join employees o on applic.officer_id=o.employeeId left join disbursed dis on applic.id=dis.applic_id where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$choose." applic.date >='".$from_date."' and applic.date <= '".$to_date."' ".$branch." order by o.firstName, o.lastName, m.first_name, m.last_name, m.mem_no");

  ////$max_page = ceil(mysql_num_rows($sth)/$num_rows);
  $sth2 = mysql_query("select dis.id as disbursed_id, applic.group_id as group_id, applic.approved as approved, applic.reason as reason, (DATEDIFF(applic.date, m.dob)/365) as age, o.firstName as of_firstName, o.lastName as of_lastName, m.id as mem_id, m.sign_name as sign_name, m.photo_name as photo_name, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, applic.amount as amount, applic.date as date, applic.guarantor1 as guarantor1, applic.guarantor2 as guarantor2, applic.id as applic_id, a.name as account_name, a.account_no as account_no, m.id as mem_id, p.based_on as based_on from loan_applic applic join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id join employees o on applic.officer_id=o.employeeId left join disbursed dis on applic.id=dis.applic_id where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') ".$branch." and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$choose." applic.date >='".$from_date."' and applic.date <= '".$to_date."' order by o.firstName, o.lastName, m.first_name, m.last_name, m.mem_no ");
  if(@ mysql_numrows($sth) == 0){
    $content.= "<div class='alert alert-danger'><font color=red>No applications found in your search options!</font></div>";
    $resp->assign("status", "innerHTML", $content);
    //return $resp;
  }
  else{
  
  
  $total__amount = mysql_fetch_assoc(mysql_query("select sum(applic.amount) as amountd from loan_applic applic join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id=a.id join employees o on applic.officer_id=o.employeeId left join disbursed dis on applic.id=dis.applic_id where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') ".$branch." and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$choose." applic.date >='".$from_date."' and applic.date <= '".$to_date."' order by o.firstName, o.lastName, m.first_name, m.last_name, m.mem_no"));
  $former_officer ="";
  $i=$stat+1;
  $amt_sub_total=0;
  
    $content = '<table class="bpmTopicC" id="table-tools" width="100%">
<tr><th>#</th><th align="left" width="10%">MEMBER No.</th><th align="left" width="10%">NAME</th><th align="left" width="10%">AMOUNT</th><th align="left" width="20%">REASON</th><th align="left" width="20%">DATE</th><th align="left" width="20%">LOAN ON SHARES</th><th align="left" width="20%">LOAN ON SAVINGS</th><th align="left" width="10%">STATUS</th><th align="left" width="20%">Loan Officer</th></tr>

    ';
     $former_officer = $officer;
   while($row = mysql_fetch_array($sth2)){
                $officer = $row['of_firstName']." ".$row['of_lastName'];
    $col_res = mysql_query("select * from collateral where applic_id='".$row['applic_id']."'");
    $col = @mysql_fetch_array($col_res);
    if($col['collateral1'] == NULL){
      $collateral1 = 0;
      $value1 = 0;
    }else{
      $collateral1 = $col['collateral1'];
      $value1 = "(".$col['value1'].")";
    }

    if($col['collateral2'] == NULL){
      $collateral2 = 0;
      $value2 = 0;
    }else{
      $collateral2 = $col['collateral2'];
      $value2 = "(".$col['value2'].")";
    }
    $sub = "<table><tr><td>".$collateral1."</td><td>".$value1."</td></tr> <tr><td>".$collateral2." </td><td>".$value2."</td></tr></table>";
    $x=1;
    if($row['group_id'] > 0){
      $list = $row['guarantor1'] .", ".$row['guarantor2'];
      $guarantors =explode(', ', $list);
      $guar='';
      //$resp->assign("status", "innerHTML", $list);
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

    //INTEREST AWARDED
    $int_res = mysql_query("select sum(i.amount) as amount from save_interest i join mem_accounts m on i.memaccount_id=m.id where m.mem_id=".$row['mem_id']."");
    $int =  mysql_fetch_array($int_res);
    $int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;

    //MONTHLY CHARGE
    $charge_res = mysql_query("select sum(c.amount) as amount from monthly_charge c join mem_accounts m on c.memaccount_id=m.id where m.mem_id='".$row['mem_id']."'");
    $charge = mysql_fetch_array($charge_res);
    $charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;

    //LOAN REPAYMENTS
    $pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join mem_accounts m on p.mode=m.id where m.mem_id='".$row['mem_id']."'");
    $pay = mysql_fetch_array($pay_res);
    $pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;

    //INCOME DEDUCTIONS
    $inc_res = mysql_query("select sum(i.amount) as amount from other_income i join mem_accounts m on i.mode=m.id where m.mem_id='".$row['mem_id']."'");
    $inc = mysql_fetch_array($inc_res);
    $inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;

    $balance = $dep_amt + $int_amt - $with_amt - $pledged_amt - $charge_amt - $pay_amt - $inc_amt;

    //$balance = $dep_amt + $int_amt - $with_amt - $pledged_amt - $charge_amt;
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
      $edit = "<a href='javascript:;' onclick=\"xajax_edit_applic('".$row['applic_id']."');\">Edit/ Collateral</a>";    
    }
    if($row['disbursed_id'] == NULL && $row['approved'] ==1)
      $disburse="<a href='javascript:;' onclick=\"xajax_add_disbursed('".$row['applic_id']."', '".$row['amount']."', '".$possible_amt."', '".$row['based_on']."');\">Disburse</a>";
    else
      $disburse = 0.0;

    ////$color=($i % 2==0) ? "lightgrey" : "white";
    $content .= "<tr><td>".$i."</td><td>".$row['mem_no']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['amount']."</td><td>".$row['reason']."</td><td>".$row['date']."</td><td>".number_format($possible_shares_loan, 2)."</td><td>".number_format($possible_savings_loan, 2)."</td><td>".$approving."</td><td>".$officer."</td></tr>";
    $i++;
    $amt_sub_total += $row['amount'];
        $amt_sub_total_save += $possible_savings_loan;
         $amt_sub_total_share += $possible_shares_loan;
  }
    $content .="</table>";
  }
return $content;
}
//put queries here

$mpdf=new mPDF('en-x','A4','','',12,15,47,47,10,10); 

$mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins
//$mpdf->Image($r['Image'],10,50,30,10);
$header = '
'.headerfun($_GET['category']).'<hr>
';
$headerE = '

';

$footer = '';


$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLHeader($headerE,'E');
$mpdf->SetHTMLFooter($footer);
$mpdf->SetHTMLFooter($footerE,'E');

$html = ''.bodyfun($_GET['cust_name'],$_GET['branch_id'],$_GET['cust_no'],$_GET['account_name'],$_GET['from_date'],$_GET['to_date'],$_GET['category']).'';

//==============================================================
//==============================================================
//==============================================================



//$mpdf=new mPDF('c','A4','','',32,25,27,25,16,13); 

//$mpdf->SetDisplayMode('default');
$mpdf->SetDisplayMode('fullpage');
$mpdf->SetWatermarkText("$fet[Fullname]");
$mpdf->watermark_font = 'DejaVuSansCondensed';
$mpdf->showWatermarkText = true;
$mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list

// LOAD a stylesheet
$stylesheet = file_get_contents('mpdfstyletables.css');
$mpdf->WriteHTML($stylesheet,1);

$mpdf->WriteHTML($html);

$mpdf->Output();
exit;
//==============================================================
//==============================================================
//==============================================================


?>