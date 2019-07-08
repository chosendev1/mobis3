<?php

$this->load->view("mpdf/mpdf");
//queries
function headerfun($account_name,$from_year,$from_month,$from_mday,$to_year,$to_month,$to_mday,$bank_acct_id){
//list($from_year,$from_month,$from_mday) = explode('-', $from_date);
   //     list($to_year,$to_month,$to_mday) = explode('-', $to_date);

          $start_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
     $end_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);

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
$reportname = "Cash Till Report for ".ucwords($account_name); 
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
$logo = $sel['logo'];
}else{
    $compa = "unknown2".mysql_error();
}

}else{
$companyd = "unknown".mysql_error();    
}

            //table
            $content = '<table width="100%"><tr>
            <td><img src="'.base_url().'logos/'.$logo.'" alt = "logo" style="width:20%; height:20%;padding-top:0px;"/></td>
            <td>&nbsp;</td>
            <td align=center><label><b><h1>'.$compa.' </h1></b>'.$address.'
<br> <h2 style="color:red; " >'.$reportname.'</h2>
            </label></td><td>&nbsp;</td>
            <td>Printed on: '.date('Y-m-d h:i:s').'<br>
              Printed by: '.ucwords(CAP_Session::get('username')).'<br>
              Report Period : '.$start_date.' to '.$end_date.'</td>
            </tr></table>';

return $content;
}

//body
function bodyfun($account_name,$from_year,$from_month,$from_mday,$to_year,$to_month,$to_mday,$bank_acct_id){  

  $acc_row = @mysql_fetch_array(@mysql_query("select name from accounts where id = (select account_id from bank_account where id = $bank_acct_id)"));
    $acc_name = $acc_row['name'];
    $start_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
     $end_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
    $query = "select date, sum(amount) as amount, transaction from collected where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, 'import' as transaction from cash_transfer where dest_id = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, 'export' as transaction from cash_transfer where source_id = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, transaction from deposit where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, transaction from disbursed where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, transaction from expense where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(initial_value) as amount, transaction from fixed_asset where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(quantity * amount) as amount, transaction from investments where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, transaction from other_income where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, transaction from payable_paid where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select p.date, sum(p.int_amt + p.princ_amt) as amount, p.transaction from payment p join disbursed d on p.loan_id=d.id where p.bank_account = $bank_acct_id and p.date < '".$start_date."' and p.mode in ('Cash', 'Cheque') group by p.date UNION select pen.date, sum(pen.amount) as amount, pen.transaction from penalty pen join disbursed d on pen.loan_id=d.id where pen.bank_account = $bank_acct_id and pen.date < '".$start_date."'  and pen.status <>'pending' group by pen.date UNION select r.date as date, sum(r.amount) as amount, r.transaction as transaction from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.bank_account = $bank_acct_id and r.date < '".$start_date."'  group by r.date UNION select date, sum(total_amount) as amount, transaction from share_dividends where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(value) as amount, transaction from shares where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, transaction from withdrawal where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, transaction from sold_asset where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, transaction from payable where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(quantity * amount) as amount, transaction from sold_invest where bank_account = $bank_acct_id and date < '".$start_date."' group by date UNION select date, sum(amount) as amount, transaction from other_funds where bank_account = $bank_acct_id and date < '".$start_date."' group by date order by date asc";   
    $bal_res = @mysql_query($query);
    if (@mysql_num_rows($bal_res) > 0)
    {
        $start_balance = 0;
        while ($bal_row = @mysql_fetch_array($bal_res))
        {
            if (strtolower($bal_row['transaction']) == 'collected' || strtolower($bal_row['transaction']) == 'import' || strtolower($bal_row['transaction']) == 'deposit' || strtolower($bal_row['transaction']) == 'sold_invest' || strtolower($bal_row['transaction']) == 'other_income' || strtolower($bal_row['transaction']) == 'payment' || strtolower($bal_row['transaction']) == 'penalty' || strtolower($bal_row['transaction']) == 'recovered' || strtolower($bal_row['transaction']) == 'shares' || strtolower($bal_row['transaction']) == 'sold_asset' || strtolower($bal_row['transaction']) == 'payable' || strtolower($bal_row['transaction']) == 'other_funds')
            {
                $amount = ($bal_row['amount'] == NULL) ? 0: $bal_row['amount'];
                $start_balance += $amount;
            }
            elseif (strtolower($bal_row['transaction']) == 'disbursed' || strtolower($bal_row['transaction']) == 'export' || strtolower($bal_row['transaction']) == 'expense' || strtolower($bal_row['transaction']) == 'investments' || strtolower($bal_row['transaction']) == 'payable_paid' || strtolower($bal_row['transaction']) == 'share_dividends' || strtolower($bal_row['transaction']) == 'withdrawal' || strtolower($bal_row['transaction']) == 'fixed_asset')
            {
                $start_balance -= $bal_row['amount'];
            //  if(strtolower($bal_row['transaction']) == 'export')
            //      $resp->alert($bal_row['amount']);
            }
        }
    }
    else
    {
        $start_balance = 0;
    }

$query2 = "select id, date, amount as amount, transaction, receipt_no as ref from collected where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, amount as amount, 'import' as transaction, 'Cash Imported' as ref from cash_transfer where dest_id = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, amount as amount, 'export' as transaction, 'Cash Exported' as ref from cash_transfer where source_id = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, trans_date,amount as amount, transaction, receipt_no as ref from deposit where bank_account = $bank_acct_id and trans_date >= '".$start_date."' and trans_date <= '".$end_date."' UNION select id, date, amount as amount, transaction, cheque_no as ref from disbursed where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, amount as amount, transaction, voucher_no as ref from expense where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, initial_value as amount, transaction, 'Fixed Asset Acquired' as ref from fixed_asset where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, amount as amount, transaction, voucher_no as ref from investments where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, amount as amount, transaction, receipt_no as ref from other_income where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION  select id, date, amount as amount, transaction, voucher_no as ref from payable_paid where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."' UNION select p.id, p.date, (p.int_amt + p.princ_amt) as amount, p.transaction, receipt_no as ref from payment p join disbursed d on p.loan_id=d.id where p.bank_account = $bank_acct_id and p.date >= '".$start_date."' and p.date <= '".$end_date."' and p.mode in ('Cash', 'Cheque') UNION select pen.id, pen.date as date, pen.amount as amount, pen.transaction, 'Penalty' as ref from penalty pen join disbursed d on pen.loan_id=d.id where pen.bank_account = $bank_acct_id and pen.date >= '".$start_date."' and pen.date <= '".$end_date."' and pen.status <>'pending'  UNION select r.id, r.date as date, r.amount as amount, r.transaction as transaction, receipt_no as ref from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.bank_account = $bank_acct_id and r.date >= '".$start_date."' and r.date <= '".$end_date."'  UNION select id, date, total_amount as amount, transaction, 'Dividends' as ref from share_dividends where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, value as amount, transaction, receipt_no as ref from shares where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, amount as amount, transaction, voucher_no as ref from withdrawal where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, amount as amount, transaction, receipt_no as ref from sold_asset where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, amount as amount, transaction, 'Loan Acquired' as ref from payable where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, (quantity * amount) as amount, transaction, receipt_no as ref from sold_invest where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."'  UNION select id, date, amount as amount, transaction, receipt_no as ref from other_funds where bank_account = $bank_acct_id and date >= '".$start_date."' and date <= '".$end_date."' order by date asc"; 
    
  
  
 

    $content .= '<table class="bpmTopicC" id="table-tools" width="100%"><tr><th width="25%" align="left">Date</th><th width="15%" align="left">Transction Date</th><th width="20%" align="left">Transaction</th><th width="30%" align="left">Reference</th><th width="30%" align="left">Particulars</th><th width="20%" align="left">Debit</th><th width="20%" align="left">Credit</th><th width="20%" align="left">Balance</th></tr><tr><td colspan="8"><hr></td></tr>';
        $content .= "<tr><td>".$start_date."</td><td></td><td>Balance B/F</td><td>Balance B/F</td><td>--</td><td>--</td><td>--</td><td>".number_format($start_balance, 2)."</td></tr>";
        $cur_bal = $start_balance;
        $i=1;
        $st_res = @mysql_query($query2);
      
 while ($st_row = mysql_fetch_array($st_res))
        {

                  //$date_trans = $st_row['date'];
            if (strtolower($st_row['transaction']) == 'collected' || strtolower($st_row['transaction']) == 'import' || strtolower($st_row['transaction']) == 'import' || strtolower($st_row['transaction']) == 'deposit' || strtolower($st_row['transaction']) == 'sold_invest' || strtolower($st_row['transaction']) == 'other_income' || strtolower($st_row['transaction']) == 'payment' || strtolower($st_row['transaction']) == 'penalty' || strtolower($st_row['transaction']) == 'recovered' || strtolower($st_row['transaction']) == 'shares' || strtolower($st_row['transaction']) == 'sold_asset' || strtolower($st_row['transaction']) == 'payable' || strtolower($st_row['transaction']) == 'other_funds')
            {
                $cur_bal += $st_row['amount'];
                $trans = ucfirst($st_row['transaction']);
                if($trans == 'Disbursed')
                    $trans = "Disbursement";
                elseif($trans == 'Payment')
                    $trans = "Re-payment";
                elseif($trans == 'Other_income')
                    $trans = 'Other Income';
                elseif($trans == 'Import')
                    $trans= "Cash Imported";
                elseif($trans == 'other_funds')
                    $trans= "Donations and Grants";
                
                //geting the memeber who deposited
if($trans == "Deposit"){
$q = mysql_fetch_array(mysql_query("select d.memaccount_id,mm.id,mm.mem_id,m.id,m.mem_no as mnumber,m.first_name,m.last_name,m.telno from deposit d join mem_accounts mm on (d.memaccount_id = mm.id) join member m on (mm.mem_id = m.id) where d.id = '".$st_row['id']."'"));

$Particulars = $q['first_name']." ".$q['last_name']." - ".$q['mnumber'];

//get transction date
$d = mysql_fetch_array(mysql_query("select date from deposit where id = '".$st_row['id']."'"));
$date_trans = $d['date'];
}elseif($trans == "Re-payment"){
  $q = mysql_fetch_array(mysql_query("select l.loan_id,d.id,d.applic_id,la.id,la.mem_id,m.id,m.mem_no as mnumber,m.first_name,m.last_name,m.telno from payment l join disbursed d on(l.loan_id = d.id) join loan_applic la on (d.applic_id = la.id) join member m on (la.mem_id = m.id) where l.id = '".$st_row['id']."'"));

$Particulars = $q['first_name']." ".$q['last_name']." - ".$q['mnumber'];  
}elseif($trans == "Other Income"){
 $q = mysql_fetch_array(mysql_query("select o.account_id,o.contact,a.id,a.account_no,a.name as acname from other_income o join accounts a on (o.account_id = a.id) where o.id = '".$st_row['id']."'"));

$Particulars = $q['acname']." - ".$q['account_no']; 
}elseif($trans == "Cash Imported"){
 $q = mysql_fetch_array(mysql_query("select c.source_id,c.dest_id,b.id,b.account_id,a.name as acname,a.id,a.account_no from cash_transfer c join bank_account b on (c.source_id = b.id) join accounts a on (b.account_id = a.id) where c.id = '".$st_row['id']."'"));

$Particulars = $q['acname']." - ".$q['account_no']; 
}

                //$color=($i % 2==0) ? "lightgrey" : "white";
                $content .= "<tr><td>".$st_row['date']."</td><td>".$date_trans."</td><td>".$trans."</td><td>RECEIPT NO: ".$st_row['ref']."</td><td>".$Particulars."</td><td>".number_format($st_row['amount'], 2)."</td><td>--</td><td>".number_format($cur_bal, 2)."</td></tr>";
                $i++;
                $cre +=$st_row['amount'];
            }
               elseif (strtolower($st_row['transaction']) == 'disbursed' || strtolower($st_row['transaction']) == 'export' || strtolower($st_row['transaction']) == 'expense' || strtolower($st_row['transaction']) == 'fixed_asset' || strtolower($st_row['transaction']) == 'payable_paid' || strtolower($st_row['transaction']) == 'share_dividends' || strtolower($st_row['transaction']) == 'investments' || strtolower($st_row['transaction']) == 'withdrawal') 
            {
                $cur_bal -= $st_row['amount'];
                $trans = ucfirst($st_row['transaction']);
                if($trans == 'Payable_paid')
                    $trans = 'Payable Paid';
                elseif($trans == 'Export')
                    $trans= "Cash Exported";
                //$color=($i % 2==0) ? "lightgrey" : "white";
//get payment pariculars
  if($trans == "Expense"){
$q = mysql_fetch_array(mysql_query("select e.details from expense e where e.id = '".$st_row['id']."'"));

$Particularsd = $q['details'];
}elseif($trans == "Withdrawal"){
//$q = mysql_fetch_array(mysql_query("select d.memaccount_id,m.id,m.mem_no as mnumber,m.first_name,m.last_name,m.telno from withdrawal d join member m on (d.memaccount_id = m.id) where d.id = '".$st_row['id']."'"));

$q = mysql_fetch_array(mysql_query("select d.memaccount_id,mm.id,mm.mem_id,m.id,m.mem_no as mnumber,m.first_name,m.last_name,m.telno from withdrawal d join mem_accounts mm on (d.memaccount_id = mm.id) join member m on (mm.mem_id = m.id) where d.id = '".$st_row['id']."'"));

$Particularsd = $q['first_name']." ".$q['last_name']." - ".$q['mnumber'];
}elseif($trans == "Disbursed"){
  $q = mysql_fetch_array(mysql_query("select l.applic_id,la.id,la.mem_id,m.id,m.mem_no as mnumber,m.first_name,m.last_name,m.telno from disbursed l join loan_applic la on (l.applic_id = la.id) join member m on (la.mem_id = m.id) where l.id = '".$st_row['id']."'"));

$Particularsd = $q['first_name']." ".$q['last_name']." - ".$q['mnumber'];  
}elseif($trans == "Cash Exported"){
 $q = mysql_fetch_array(mysql_query("select c.source_id,c.dest_id,b.id,b.account_id,a.name as acname,a.id,a.account_no from cash_transfer c join bank_account b on (c.dest_id = b.id) join accounts a on (b.account_id = a.id) where c.id = '".$st_row['id']."'"));

$Particularsd = $q['acname']." - ".$q['account_no']; 
}

//end

                $content .= "<tr><td>".$st_row['date']."</td><td>".$date_trans."</td><td>".$trans."</td><td>PV/CHEQUE NO: ".$st_row['ref']."</td><td>".$Particularsd."</td><td>--</td><td>".number_format($st_row['amount'], 2)."</td><td>".number_format($cur_bal, 2)."</td></tr>";
                $i++;
                $deb +=$st_row['amount'];
            }
        }
        $content .= "<tr><td colspan='8'><hr></td></tr>
        <tr><th>End Of Day Balance</th><th></th><th></th><th></th><th></th><th></th><th></th><th>".number_format($cur_bal, 2)."</th></tr>
        <tr><td colspan='8'><hr></td></tr>
   <tr><th>Totals</th><th></th><th></th><th></th><th></th><th>".number_format($cre,2)."</th><th>".number_format($deb,2)."</th><th>".number_format($cur_bal, 2)."</th></tr><tr><td colspan='8'><hr></td></tr>";
    $content .="</table>";
return $content;
}
//put queries here

$mpdf=new mPDF('en-x','A4','','',12,15,47,47,10,10); 

$mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins
//$mpdf->Image($r['Image'],10,50,30,10);
$header = '
'.headerfun($_GET['account_name'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday'],$_GET['bank_acct']).'<hr>
';

$headerE = '
'.headerfun($_GET['account_name'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday'],$_GET['bank_acct']).'<hr>
';


$footer = '<table><tr><td width="50%"><h4>Prepared By:</h4><br>
---------------------------------------------------- <br>
</td><td width="20%"></td><td width="50%"><h4>Approved By:</h4><br>
---------------------------------------------------- <br>
Date: '.date('Y-m-d h:i:s',time()).'</td></tr></table>';

$footerE = '<table><tr><td width="50%"><h4>Prepared By:</h4><br>
---------------------------------------------------- <br>
</td><td width="20%"></td><td width="50%"><h4>Approved By:</h4><br>
---------------------------------------------------- <br>
Date: '.date('Y-m-d h:i:s',time()).'</td></tr></table>';


$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLHeader($headerE,'E');
$mpdf->SetHTMLFooter($footer);
$mpdf->SetHTMLFooter($footerE,'E');

$html = ''.bodyfun($_GET['account_name'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday'],$_GET['bank_acct']).'';

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