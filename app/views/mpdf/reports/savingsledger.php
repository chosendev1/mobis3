<?php

$this->load->view("mpdf/mpdf");
//queries
function headerfun($mem_no, $mem_id, $save_acct, $from_year,$from_month,$from_mday,$to_year,$to_month,$to_mday){
$start_date = sprintf("%d-%02d-%02d", $from_year, $from_month, $from_mday);
    $end_date = sprintf("%d-%02d-%02d", $to_year, $to_month, $to_year);

$mem_row = @mysql_fetch_array(@mysql_query("select first_name, last_name, mem_no, sign_name, photo_name from member where id = $mem_id"));

//get branch
$bran = mysql_query("select * from branch where branch_no='".$branch_id."'");
               $bra = mysql_fetch_assoc($bran);
               $branum = mysql_num_rows($bran);
if($branum == 1){ $branch_didplay =  $bra['branch_name'];} else{$branch_didplay = 'All branches';}

//get loan product

if($account_name == ""){ $product_didplay = "All Products";} else{$product_didplay = $account_name;}

//report name
$reportname = "Savings Report for ".ucwords($mem_row['first_name']." ".$mem_row['last_name']);
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
           
            <td align=center width="50%"><label><b><h1>'.$compa.' </h1></b>'.$address.'
<br> <h2 style="color:red; " >'.$reportname.'</h2>
            </label></td><td>&nbsp;</td>
            <td>Printed on: '.date('Y-m-d h:i:s').'<br>
              Printed by: '.ucwords(CAP_Session::get('username')).'<br>
             Report Period: '.$start_date.' to '.$end_date.'</td>
            </tr></table>';

return $content;
}

//body
function bodyfun($mem_no, $mem_id, $save_acct, $from_year,$from_month,$from_mday,$to_year,$to_month,$to_mday){  
   $start_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
    $end_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);


if($save_acct =='' && $mem_no ==''){
        $resp->alert("Please enter the Member No");
        return $resp;
    }
    if($mem_no <>"" && $save_acct==''){
        $sth = mysql_query("select * from member where mem_no='".$mem_no."'");
        if(mysql_numrows($sth) ==0){
            $resp->alert("The entered Member No does not exist!");
            return $resp;
        }
        $row = mysql_fetch_array($sth);
        $acct_res = mysql_query("select mem.id as id from mem_accounts mem join savings_product s on mem.saveproduct_id=s.id where mem.mem_id='".$row['id']."' and s.type='free'");
        if(mysql_numrows($acct_res) > 1){
            $resp->call('xajax_savings_ledger_form', $row['id']);
            return $resp;
        }elseif(mysql_numrows($acct_res) ==1){
            $acct = mysql_fetch_array($acct_res);
            $save_acct = $acct['id'];
            $mem_id = $row['id'];
        }elseif(mysql_numrows($acct_res) ==0){
            $resp->alert("This Member hasnt a savings account!");
            return $resp;
        }
    }

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

        $total_saved = isset($drow1['tot_savings'])? intval($drow1['tot_savings']) : 0 ;
        $total_fees = isset($mrow1['tot_fees'])? intval($mrow1['tot_fees']) : 0 ;
        $total_with = isset($wrow1['tot_with'])? intval($wrow1['tot_with']) : 0 ;
        $total_int = isset($irow1['tot_int'])? intval($irow1['tot_int']) : 0 ;
        $total_pay = isset($prow1['tot_int'])? intval($prow1['tot_int']) : 0 ;
        $total_inc = isset($incow1['tot_inc'])? intval($incow1['tot_inc']) : 0 ;
        $net_save = ($total_saved + $total_int) - ($total_fees + $total_with + $total_pay + $total_inc + $total_shares);
    $cumm_save += $net_save;
    $mem_row = @mysql_fetch_array(@mysql_query("select first_name, last_name, mem_no, sign_name, photo_name from member where id = $mem_id"));
    $branch = mysql_fetch_array(mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'"));  

    $content = '<table class="bpmTopicC" id="table-tools" width="100%">

<tr><th width="30%" align=left>Date</th><th width="20%" align=left>Depositor</th><th width="30%" align=left>Description</th><th width="15%" align=left>Debit</th><th width="15%" align=left>Credit</th><th width="20%" align=left>Account Balance</th>
            </tr>
    <tr><td colspan="6"><hr></td></tr>

    <tr>
            <td>Before '.$start_date.'</td><td>--</td><td>B/F</td><td>--</td><td>--</td><td>'.number_format($cumm_save, 2).'</td>
            </tr>';
            $acc_res = @mysql_query("select id, date, amount, transaction, depositor as depositor from deposit where bank_account != 0 and memaccount_id = $save_acct and date > '".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction, '--' as depositor from withdrawal where memaccount_id = $save_acct and date > '".$start_date."' and date <= '".$end_date."' UNION select id, date, amount, transaction, '--' as depositor  from monthly_charge where memaccount_id = $save_acct and date >'".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction, '--' as depositor  from save_interest where memaccount_id = $save_acct and date > '".$start_date."' and date <='".$end_date."' UNION select id, date, princ_amt + int_amt as amount, transaction, '--' as depositor  from payment where mode= '".$save_acct."' and date > '".$start_date."' and date <='".$end_date."' UNION select id, date, amount, transaction, '--' as depositor  from other_income where mode = '".$save_acct."' and date > '".$start_date."' and date <= '".$end_date."' UNION select id, date, value as amount, transaction, '--' as depositor  from shares where mode = '".$save_acct."' and date > '".$start_date."' and date <= '".$end_date."' order by date asc");
   $x = 0;
    while ($acc_row = @mysql_fetch_array($acc_res))
    {
        $charge_amt = 0;
        $tot_shares = strtolower($acc_row['transaction']) == 'shares' ? intval($acc_row['amount']) : 0 ;
        $tot_savings = strtolower($acc_row['transaction']) == 'deposit' ? intval($acc_row['amount']) : 0 ;
        $tot_fees = strtolower($acc_row['transaction']) == 'monthly_charge' ? intval($acc_row['amount']) : 0 ;
        $tot_with = strtolower($acc_row['transaction']) == 'withdrawal' ? intval($acc_row['amount']) : 0 ;
        $tot_int = strtolower($acc_row['transaction']) == 'save_interest' ? intval($acc_row['amount']) : 0 ;
        $tot_pay = strtolower($acc_row['transaction']) == 'payment' ? intval($acc_row['amount']) : 0 ;
        $tot_inc = strtolower($acc_row['transaction']) == 'other_income' ? intval($acc_row['amount']) : 0 ;

        if(strtolower($acc_row['transaction']) == 'deposit'){
            $charge = mysql_fetch_array(mysql_query("select receipt_no, cheque_no, (flat_value + percent_value) as amount from deposit where id='".$acc_row['id']."'"));
            $charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
            $descr="Deposit - RCPT: ".$charge['receipt_no'];
            $descr = ($charge['cheque_no'] <>"") ? $descr."<br>CHEQ: ".$charge['cheque_no'] : $descr;
        }
        if(strtolower($acc_row['transaction']) == 'withdrawal'){
            $charge = mysql_fetch_array(mysql_query("select voucher_no, cheque_no, flat_value + percent_value as amount from withdrawal where id='".$acc_row['id']."'"));
            $charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
            $descr="Withdrawal - PV: ".$charge['voucher_no'];
            $descr = ($charge['cheque_no'] <>"") ? $descr."<br>CHEQ: ".$charge['cheque_no'] : $descr;
        }
        if(strtolower($acc_row['transaction']) == 'payment'){
    
            $pay = mysql_fetch_array(mysql_query("select receipt_no,  princ_amt + int_amt as amount from payment where id='".$acc_row['id']."'"));
            $pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;
            $descr="Loan Repayment - PV: ".$pay['receipt_no'];
            //$resp->alert($tot_pay);
        }

        if(strtolower($acc_row['transaction']) == 'other_income'){
    
            $inc = mysql_fetch_array(mysql_query("select i.receipt_no, i.cheque_no, i.amount, a.name from other_income i join accounts a on a.id = i.account_id where i.id='".$acc_row['id']."'"));
            $inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;
            $descr="DEDUCTION(".$inc['name'].")"."<br>PV / CHEQ: ".$inc['receipt_no']. " ".$inc['cheque_no'];
            //$resp->alert($tot_pay);
        }
        if(strtolower($acc_row['transaction']) == 'shares'){
    
            $share = mysql_fetch_array(mysql_query("select s.receipt_no, s.value as amount from shares s where s.id='".$acc_row['id']."'"));
            $share_amt = ($share['amount'] != NULL) ? $share['amount'] : 0;
            $descr="TRANSFER TO SHARES - PV / CHEQ: ".$share['receipt_no'];
            //$resp->alert($tot_pay);
        }
        //$tot_fees = $tot_fees + $charge_amt;
        //$net_save = ($tot_savings + $tot_int) - ($tot_fees + $charge_amt + $tot_with);
        //$cumm_save += $net_save;
        if($tot_savings >0){
            $cumm_save += $tot_savings;
            $x++;
            //$color=($i % 2==0) ? "lightgrey" : "white";
            $content .= "
           <tr>
            <td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td >".$descr."</td><td >--</td><td >".number_format($tot_savings, 2)."</td><td >".number_format($cumm_save, 2)."</td>
            </tr>
            ";
        }
        if($tot_int >0){
            $cumm_save += $tot_int;
            $x++;
            //$color=($i % 2==0) ? "lightgrey" : "white";
            $content .= "
           <tr>
            <td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td >Interest Earned</td><td >--</td><td >".$tot_int."</td><td >".number_format($cumm_save, 2)."</td>
            </tr>
            ";
        }
        if($tot_shares >0){
            $cumm_save -= $tot_shares;
            $x++;
            //$color=($i % 2==0) ? "lightgrey" : "white";
            $content .= "
           <tr>
            <td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td >".$descr."</td><td >".number_format($tot_shares, 2)."</td><td >--</td><td >".number_format($cumm_save, 2)."</td>
            </tr>
            ";
        }
        if($tot_with >0){
            $cumm_save -= $tot_with;
            $x++;
            //$color=($i % 2==0) ? "lightgrey" : "white";
            $content .= "
           <tr>
            <td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td >".$descr."</td><td >".number_format($tot_with, 2)."</td><td >--</td><td >".number_format($cumm_save, 2)."</td>
            </tr>
            ";
        }
        if($tot_pay >0 || $tot_pay <0){
            $cumm_save -= $tot_pay;
            $x++;
            
            //$color=($i % 2==0) ? "lightgrey" : "white";
            $content .= "
           <tr>
            <td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td >".$descr."</td><td >".number_format($tot_pay, 2)."</td><td >--</td><td >".number_format($cumm_save, 2)."</td>
            </tr>
            ";
        }
        if($charge_amt >0){
            $x++;
            $cumm_save -= $charge_amt;
            //$color=($i % 2==0) ? "lightgrey" : "white";
            $content .= "
           <tr>
            <td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td >Transactional Charge</td><td >".$charge_amt."</td><td >--</td><td >".number_format($cumm_save,2)."</td>
            </tr>
            ";
        }
        if($tot_inc >0){
            $cumm_save -= $tot_inc;
            $x++;
            
            //$color=($i % 2==0) ? "lightgrey" : "white";
            $content .= "
           <tr>
            <td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td >".$descr."</td><td >".number_format($tot_inc, 2)."</td><td >--</td><td >".number_format($cumm_save, 2)."</td>
            </tr>
            ";
        }
        if($tot_fees >0){
            $x++;
            $cumm_save -= $tot_fees;
            //$color=($i % 2==0) ? "lightgrey" : "white";
            $content .= "
           <tr>
            <td>".$acc_row['date']."</td><td>".$acc_row['depositor']."</td><td >Monthly Charge</td><td >".$tot_fees."</td><td >--</td><td >".$cumm_save."</td>
            </tr>
            ";
        }
    }


    $content .="</table>";
return $content;
}
//put queries here

$mpdf=new mPDF('en-x','A4','','',12,15,47,47,10,10); 

$mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins
//$mpdf->Image($r['Image'],10,50,30,10);
$header = '
'.headerfun( $_GET['mem_no'], $_GET['mem_id'], $_GET['save_acct'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday']).'<hr>
';


$mpdf->SetHTMLHeader($header);
//$mpdf->SetHTMLHeader($headerE,'E');
//$mpdf->SetHTMLFooter($footer);
//$mpdf->SetHTMLFooter($footerE,'E');

$html = ''.bodyfun( $_GET['mem_no'], $_GET['mem_id'], $_GET['save_acct'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday']).'';

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