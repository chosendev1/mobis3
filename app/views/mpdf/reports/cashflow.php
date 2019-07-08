<?php

$this->load->view("mpdf/mpdf");
//queries
function headerfun($todate){
 
 if($todate == ''){
        $todate = date("Y-m-d",time());
    }

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


$reportname = "CASHFLOW STATEMENT"; 
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
<br> <h4 style="color:red; " >'.$reportname.' <br>FOR THE PERIOD ENDING '.$todate.'</h4>
            </label></td><td>&nbsp;</td>
            <td>Printed on: '.date('Y-m-d h:i:s').'<br>
              Printed by: '.ucwords(CAP_Session::get('username')).'<br>
            </td>
            </tr></table>';

return $content;
}
function bodyfun($fromdate,$branch_id,$todate){

 list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($year,$month,$mday) = explode('-', $date);

    $branch_ = ($branch_id=='all'||$branch_id=='')?NULL:"and branch_id=".$branch_id;
  if($date == ''){
        $year = date('Y');
        $month = date('m');
        $mday = date('d');
    }
 if($fromdate == ''){
        $year = date('Y');
        $month = date('m');
        $mday = date('d');
    }

    $to_date = sprintf("%d-%02d-%02d 23:59:59", $year, $month, $mday);
    $from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
    $branch_res = mysql_query("select * from branch");
    $branch = mysql_fetch_array($branch_res);

$content .="<table class='borderlessreports' width='100%' id='AutoNumber2' align=center>
    <tr><td colspan=2 align=left><h3>CASH FLOW FROM OPERATING ACTIVITIES</h3></td><td><h3><b></h3></td></tr>
    <tr><td colspan=2 align=left><h4><b>Inflows</b></h4><td><font size=2pt><b></b></font></td></tr>";
    
//LOANS COLLECTED (ISSUED BY THE SACCO)
    $collected_res = mysql_query("select sum(amount) as amount from collected where date >= '".$from_date."' and date <='".$to_date."'");
    $collected = mysql_fetch_array($collected_res);
    $collected_amt = ($collected['amount'] != NULL) ? $collected['amount'] : 0;

    $pay_res = mysql_query("select sum(princ_amt) as princ_amt, sum(int_amt) as int_amt from payment where date >= '".$from_date."' and date <= '".$to_date."' and bank_account>'0'");
    $pay = mysql_fetch_array($pay_res);
    $princ_amt = ($pay['princ_amt'] != NULL) ? $pay['princ_amt'] : 0;
    $int_amt = ($pay['int_amt'] != NULL) ? $pay['int_amt'] : 0;
    $recovered_res = mysql_query("select sum(amount) as amount from recovered where date >='".$from_date."' and date <='".$to_date."'");
    $recovered = mysql_fetch_array($recovered_res);
    $recovered_amt = ($recovered['amount'] != NULL) ? $recovered['amount'] : 0;
    $loans_collected = $princ_amt;     // + $recovered_amt;

//COMMISSION
    $pen_res = mysql_query("select sum(amount) as amount from penalty where status='paid' and date >='".$from_date."' and date <='".$to_date."'");
    $pen = mysql_fetch_array($pen_res);
    $pen_amt = ($pen['amount'] != NULL) ? $pen['amount'] : 0;
    $commission = $pen_amt + $int_amt;

    $deposit_res = mysql_query("select sum(amount) as amount from deposit where date >='".$from_date."' and date <='".$to_date."' and bank_account>'0'");
    $deposit = mysql_fetch_array($deposit_res);
    $deposit_amt = ($deposit['amount'] != NULL) ? $deposit['amount'] : 0;
//SHORT-TERM LOANS PAYABLE
    $short_res = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '2121%' and date >='".$from_date."' and date <='".$to_date."' and p.bank_account >'0'");
    $short = mysql_fetch_array($short_res);
    $short_amt = ($short['amount'] != NULL) ? $short['amount'] : 0;
    $short_credit = $deposit_amt + $short_amt;

//OTHER INCOME
    $inc = mysql_fetch_array(mysql_query("select sum(amount) as amount from other_income where bank_account > 0 and date >='".$from_date."' and date <= '".$to_date."'"));
    $inc_amt = ($inc['amount'] == NULL) ? 0 : $inc['amount'];

    $inflow = $short_credit + $commission + $loans_collected + $collected_amt + $inc_amt + $recovered_amt;
    $content .="<tr><td width=10%></td><td><font size=2pt>Collection of Accounts or Documents Receivable</font></td><td align='right'><font size=2pt>".number_format($collected_amt, 0)."</font></td></td></tr>
        <tr><td width=10%></td><td><font size=2pt>Collection of Loans</font></td><td align='right'><font size=2pt>".number_format($loans_collected, 0)."</font></td></td></tr>
        <tr><td width=10%></td><td><font size=2pt>Short Term External Credit or Savings Deposits Received</font></td><td align='right'><font size=2pt>".number_format($short_credit, 0)."</font></td></td></tr>
        <tr><td width=10%></td><td><font size=2pt>Interest & Penalties</font></td><td align='right'><font size=2pt>".number_format($commission, 0)."</font></td></td></tr>
        <tr><td width=10%></td><td><font size=2pt>Other Income</font></td><td align='right'><font size=2pt>".number_format(($inc_amt+ $recovered_amt), 0)."</font></td></td></tr>
        <tr><td width=10%></td><td><font size=2pt><b>Total Cash Inflows</b></font></td><td align='right'><font size=2pt><b>".number_format($inflow, 0)."</b></font></td></td></tr>";

//OUTFLOW OF CASH
    $content .="<tr><td colspan=2 align=left><h4>Outflows</h4></td><td><font size=5pt><b></b></font></td></tr>";
//DISBURSEMENTS
    $loan_res = mysql_query("select sum(amount) as amount from disbursed where date >='".$from_date."' and date <='".$to_date."' and bank_account>'0'");
    $loan = mysql_fetch_array($loan_res);
    $loan_amt = ($loan['amount'] != NULL) ? $loan['amount'] : 0;
//WITHDRAWALS
    $with_res = mysql_query("select sum(amount) as amount from withdrawal where date >='".$from_date."' and date <='".$to_date."'");
    $with= mysql_fetch_array($with_res);
    $with_amt = ($with['amount'] != NULL) ? $with['amount'] : 0;
//PAYMENT FROM SAVINGS
    //$paid = mysql_fetch_array(mysql_query("select sum(princ_amt + int_amt) as amount from payment where date >='".$from_date."' and date <='".$to_date."' and mode>0"));
    //$paid_amt = ($paid['amount'] == NULL) ? 0 : $paid['amount'];
    $disburse_with = $loan_amt + $with_amt;  // + $paid_amt;

//PAYMENTS OF SHORT TERM LOANS AND ACCOUNTS PAYABLE
    $paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id join accounts a on p.account_id=a.id where a.account_no not like '2122%' and paid.date >='".$from_date."' and paid.date <='".$to_date."'");
    $paid = mysql_fetch_array($paid_res);
    $paid_amt = ($paid['amount'] != NULL) ? $paid['amount'] : 0;

//PAYMENT TO EMPLOYEES
    $emp_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where a.account_no like '531%' and e.date >='".$from_date."' and e.date <= '".$to_date."'");
    $emp = mysql_fetch_array($emp_res);
    $emp_amt = ($emp['amount'] != NULL) ? $emp['amount'] : 0;
//ACCRUED EXPENSES
//  $accrued_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id join accounts a on p.account_id=a.id where paid.date >='".$from_date."' and paid.date <= '".$to_date."'");
//  $accrued = mysql_fetch_array($accrued_res);
//  $accrued_amt = ($accrued['amount'] != NULL) ? $accrued['amount'] : 0;

//BANK CHARGES, COMMISSIONS AND INTEREST
//  $charge_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where a.account_no like '515%' and e.date >='".$from_date."' and e.date <= '".$to_date."'");
//  $charge = mysql_fetch_array($charge_res);
//  $charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
//
//INSURANCE
//  $ins_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where a.account_no like '522%' and e.date >='".$from_date."' and e.date <= '".$to_date."'");
//  $ins = mysql_fetch_array($ins_res);
//  $ins_amt = ($ins['amount'] != NULL) ? $ins['amount'] : 0;
//MARKETING
    $market_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where a.account_no like '533%' and e.date >='".$from_date."' and e.date <= '".$to_date."'");
    $market = mysql_fetch_array($market_res);
    $market_amt = ($market['amount'] != NULL) ? $market['amount'] : 0;
//GOVERNANCE
    $govern_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where a.account_no like '532%' and e.date >='".$from_date."' and e.date <= '".$to_date."'");
    $govern = mysql_fetch_array($govern_res);
    $govern_amt = ($govern['amount'] != NULL) ? $govern['amount'] : 0;
//ADMINISTRATIVE
    $admin_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where a.account_no like '535%' and e.date >='".$from_date."' and e.date <= '".$to_date."'");
    $admin = mysql_fetch_array($admin_res);
    $admin_amt = ($admin['amount'] != NULL) ? $admin['amount'] : 0;
//OTHER EXPENSES
    //$other_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where (a.account_no like '521%' or a.account_no like '551%' or a.account_no like '513%') and e.date >='".$from_date."' and e.date <= '".$to_date."'");
    $other_res = mysql_query("select sum(e.amount) as amount from expense e join accounts a on e.account_id=a.id where (a.account_no not like '535%' and a.account_no not like '532%' and a.account_no not like '533%' and a.account_no not like '531%') and e.date >='".$from_date."' and e.date <= '".$to_date."'");
    $other = mysql_fetch_array($other_res);
    $otherexp_amt = ($other['amount'] != NULL) ? $other['amount'] : 0;

    $outflow = $otherexp_amt + $admin_amt + $market_amt + $govern_amt + $ins_amt + $disburse_with + $paid_amt  + $emp_amt;
    $content .="<tr ><td width=10%></td><td><font size=2pt>Disbursements & Savings Withdrawals</font></td><td align='right'><font size=2pt>".number_format($disburse_with, 0)."</font></td></td></tr>
    <tr ><td width=10%></td><td><font size=2pt>Repayment of Short Term Ext. Credit & Accounts Payable</font></td><td align='right'><font size=2pt>".number_format($paid_amt, 0)."</font></td></td></tr>
    <tr ><td width=10%></td><td><font size=2pt>Payments on Personnel Expenses (Salaries, Benefits, Training, etc)</font></td><td align='right'><font size=2pt>".number_format($emp_amt, 0)."</font></td></td></tr>
    <tr ><td width=10%></td><td><font size=2pt>Payment on Marketing Expenses</font></td><td align='right'><font size=2pt>".number_format($market_amt, 0)."</font></td></td></tr>
    <tr ><td width=10%></td><td><font size=2pt>Payment on Governance Expenses</font></td><td align='right'><font size=2pt>".number_format($govern_amt, 0)."</font></td></td></tr>
    <tr ><td width=10%></td><td><font size=2pt>Payment on Administrative Expenses</font></td><td align='right'><font size=2pt>".number_format($admin_amt, 0)."</font></td></td></tr>
    <tr ><td width=10%></td><td><font size=2pt>Payment on Other Expenses and Services</font></td><td align='right'><font size=2pt>".number_format($otherexp_amt, 0)."</font></td></td></tr>
    <tr ><td width=10%></td><td><font size=2pt><b>Total Cash Outflows</b></font></td><td align='right'><font size=2pt><b>".number_format($outflow, 0)."</b></font></td></td></tr>";

    $operating_amt = $inflow - $outflow;
    $operating = ($operating_amt >= 0) ? $operating_amt : (0-$operating_amt);
    if($inflow > $outflow){
$effect = "NET INCREASE";
    }elseif($inflow < $outflow){
$effect = "NET DECREASE";

    }else{
        $effect = "EQUIVALENCE";
    }
    $content .= "<tr bgcolor=lightgrey><td colspan=2 align=left><font size=2pt><b>".$effect." OF CASH FROM OPERATING ACTIVITIES</b></font></td><td align='right'><font size=2pt><b>".number_format($operating, 0)."</b></font></td></td></tr>
    <tr ><td colspan=2 align=left><font size=2pt><b>CASH FLOW FROM INVESTMENT ACTIVITIES</b></font></td><td><font size=2pt><b></b></font></td></td></tr>
    <tr ><td colspan=2 align=left><font size=2pt><b>Inflows </b></font></td><td><font size=2pt><b></b></font></td></td></tr>";
//SALE OF FIXED ASSETS
    $sell_res = mysql_query("select sum(amount) as amount from sold_asset where date >='".$from_date."' and date<= '".$to_date."'");
    $sell = mysql_fetch_array($sell_res);
    $sell_asset = ($sell['amount'] != NULL) ? $sell['amount'] : 0;
//PURCHASE OF FIXED ASSETS
    $buy_res = mysql_query("select sum(initial_value) as amount from fixed_asset where date >='".$from_date."' and date <= '".$to_date."'");
    $buy = mysql_fetch_array($buy_res);
    $buy_asset= ($buy['amount'] != NULL) ? $buy['amount'] : 0;
//SALE OF INVESTMENTS
    $sell_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where date >='".$from_date."' and date <='".$to_date."'");
    $sell = mysql_fetch_array($sell_res);
    //OTHER INCOME EG PHOTOCOPIER, EXCEPT DONATIONS
    //$other_res = mysql_query("select sum(o.amount) as amount from other_income o join accounts a on o.account_id=a.id where a.account_no not like '423%' and o.date >='".$from_date."' and o.date <= '".$to_date."'");  //LEAVE OUT DONATIONS
    //$other = mysql_fetch_array($other_res);
    //$other_amt = ($other['amount'] != NULL) ? $other['amount'] : 0;

    $sell_invest = ($sell['amount'] != NULL) ? $sell['amount'] : 0;
//PURCHASE OF INVESTMENTS
    $buy_res = mysql_query("select sum(quantity * amount) as amount from investments where date >='".$from_date."' and date <='".$to_date."'");
    $buy = mysql_fetch_array($buy_res);
    $buy_invest = ($buy['amount'] != NULL) ? $buy['amount'] : 0;

    //$invest_res = mysql_query("select sum(amount) as amount from investments where ")
    $investment_amt = $sell_asset + $sell_invest - $buy_asset - $buy_invest;  // + $other_amt;
    $investment = ($investment_amt >= 0) ? $investment_amt : "(".(0-$investment_amt).")";

     if(($sell_asset + $sell_invest) > ($buy_asset + $buy_invest)){
$effect = "NET INCREASE";
    }else if(($sell_asset + $sell_invest) < ($buy_asset + $buy_invest)){
$effect = "NET DECREASE";

    }else {
      $effect = "EQUIVALENCE";  
    }
    $content .= "<tr><td></td><td><font size=2pt>Sale of Fixed Assets</font></td><td align='right'><font size=2pt>".number_format($sell_asset, 0)."</font></td></td></tr>
    <tr bgcolor=white><td></td><td><font size=2pt>Sale of Investments</font></td><td align='right'><font size=2pt>".number_format($sell_invest, 0)."</font></td></td></tr>
    <tr><td></td><td><font size=2pt><b>Total Cash Inflows</b></font></td><td align='right'><font size=2pt><b>".number_format(($sell_invest + $sell_asset), 0)."</b></font></td></td></tr>
    <tr bgcolor=white><td colspan=2 align=left><font size=2pt><b>Outflows </b></font></td><td><font size=2pt><b></b></font></td></td></tr>
    <tr><td></td><td><font size=2pt>Purchase of Fixed Assets</font></td><td align='right'><font size=2pt>".number_format($buy_asset, 0)."</font></td></td></tr>
    <tr bgcolor=white><td></td><td><font size=2pt>Purchase of Investments</font></td><td align='right'><font size=2pt>".number_format($buy_invest, 0)."</font></td></td></tr>
    <tr><td></td><td><font size=2pt><b>Total Cash Outflows</b></font></td><td align='right'><font size=2pt><b>".number_format(($buy_invest + $buy_asset), 0)."</b></font></td></td></tr>
    <tr bgcolor=lightgrey><td colspan=2 align=left><font size=2pt><b>".$effect." OF CASH FROM INVESTMENT ACTIVITIES</b></font></td><td align='right'><font size=2pt><b>".number_format($investment, 0)."</b></font></td></td></tr>";
//FINANCING ACTIVITIES
    $content .="<tr><td colspan=2 align=left><font size=2pt><b>CASH FLOW FROM FINANCING ACTIVITIES</b></font></td><td><font size=2pt><b></b></font></td></td></tr>
    <tr bgcolor=white><td colspan=2 align=left><font size=2pt><b>Inflows </b></font></td><td><font size=2pt><b></b></font></td></td></tr>";
//INFLOWS
//SHARES SOLD
    $shares_res = mysql_query("select sum(value) as amount from shares where date >='".$from_date."' and date <='".$to_date."' and bank_account>0");
    $shares = mysql_fetch_array($shares_res);
    $shares_amt = ($shares['amount'] != NULL) ? $shares['amount'] : 0;
//LONG TERM  EXTERNAL CREDIT
    $long_res = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '2122%' and date >='".$from_date."' and date <='".$to_date."' and p.bank_account>'0'");
    $long = mysql_fetch_array($long_res);
    $long_amt = ($long['amount'] != NULL) ? $long['amount'] : 0;
//DONATIONS AND GRANTS
    $other_res = mysql_query("select sum(o.amount) as amount from other_funds o where o.date >='".$from_date."' and o.date <= '".$to_date."' and o.bank_account >'0'"); 
    $other = mysql_fetch_array($other_res);
    $other_amt = ($other['amount'] != NULL) ? $other['amount'] : 0;
//REPAYMENT OF LONG TERM CREDIT
    $paid_res = mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id join accounts a on p.account_id=a.id where a.account_no like '2122%' and paid.date >='".$from_date."' and paid.date <='".$to_date."'");
    $paid = mysql_fetch_array($paid_res);
    $paid_amt = ($paid['amount'] != NULL) ? $paid['amount'] : 0;
//DIVIDENDS PAID
    $div_res = mysql_query("select sum(total_amount) as amount from share_dividends where date >= '".$from_date."' and date <= '".$to_date."' and bank_account>0");
    $div = mysql_fetch_array($div_res);
    $div_amt = ($div['amount'] != NULL) ? $div['amount'] : 0;
    $finance_amt = $shares_amt + $long_amt + $other_amt -$paid_amt - $div_amt;
    $finance = ($finance_amt >= 0) ? $finance_amt : "(".(0-$finance_amt).")";

   if(($shares_amt + $long_amt + $other_amt -$paid_amt) > $div_amt){
$effect = "NET INCREASE";
    }elseif(($shares_amt + $long_amt + $other_amt -$paid_amt) < $div_amt){
$effect = "NET DECREASE";

    }else{
        $effect = "EQUIVALENCE";
    }
    
    $content .= "<tr ><td></td><td><font size=2pt>Sale of Shares</font></td><td align='right'><font size=2pt>".number_format($shares_amt,0)."</font></td></td></tr>
    <tr bgcolor=white><td></td><td><font size=2pt>Long Term External Credit Received</font></td><td align='right'><font size=2pt>".number_format($long_amt, 0)."</font></td></td></tr>
    <tr ><td></td><td><font size=2pt>Donations & Grants</font></td><td align='right'><font size=2pt>".number_format($other_amt, 0)."</font></td></td></tr>
    <tr bgcolor=white><td></td><td><font size=2pt><b>Total Cash Inflows</b></font></td><td align='right'><font size=2pt><b>".number_format(($shares_amt + $other_amt + $long_amt), 0)."</b></font></td></td></tr>
    <tr ><td colspan=2 align=left><font size=2pt><b>Outflows </b></font></td><td><font size=2pt><b></b></font></td></td></tr>
    <tr bgcolor=white><td></td><td><font size=2pt>Long Term External Credit Repaid</font></td><td align='right'><font size=2pt>".number_format($paid_amt, 0)."</font></td></td></tr>
    <tr ><td></td><td><font size=2pt>Dividends Paid</font></td><td align='right'><font size=2pt>".number_format($div_amt, 0)."</font></td></td></tr>
    <tr bgcolor=white><td></td><td><font size=2pt><b>Total Cash Outflows</b></font></td><td align='right'><font size=2pt><b>".number_format(($div_amt + $paid_amt), 0)."</b></font></td></td></tr><tr><td></td></tr><tr><td></td></tr>
    <tr  bgcolor=lightgrey><td colspan=2 align=left><font size=2pt><b>".$effect." OF CASH FROM FINANCE ACTIVITIES</b></font></td><td align='right'><font size=2pt><b>".number_format($finance, 0)."</b></font></td></td></tr>";
    $content .= "</table>";

    $net_change = $operating_amt + $investment_amt + $finance_amt;
    $net = ($net_change >= 0) ? $net_change : "(".(0 - $net_change).")";

      if($net > 0){
$effect = "NET INCREASE";
    }elseif($net < 0){
$effect = "NET DECREASE";

    }else{
        $effect = "EQUIVALENCE";
    }

//DEPOSITS
            $dep_res = mysql_query("select sum(amount) as amount from deposit where bank_account>'0' and date <'".$from_date."'");
            $dep = mysql_fetch_array($dep_res);
            $dep_amt = ($dep['amount'] == NULL) ? 0 : $dep['amount'];
            //WITHDRAWALS
            $with_res = mysql_query("select sum(amount) as amount from withdrawal where bank_account>'0' and date <'".$from_date."'");
            $with = mysql_fetch_array($with_res);
            $with_amt = ($with['amount'] == NULL) ? 0 : $with['amount'];
            //OTHER INCOME
            $other_res = mysql_query("select sum(amount) as amount from other_income where bank_account>'0' and date <'".$from_date."'");
            $other = mysql_fetch_array($other_res);
            $other_amt = ($other['amount'] == NULL) ? 0 : $other['amount'];
            //EXPENSES
            $expense_res = mysql_query("select sum(amount) as amount from expense where bank_account>'0' and date <'".$from_date."'");
            $expense = mysql_fetch_array($expense_res);
            $expense_amt = ($expense['amount'] == NULL) ? 0 : $expense['amount'];
            //LOANS PAYABLE
            $loans_payable = mysql_query("select sum(p.amount) as amount from payable p join accounts a on p.account_id=a.id where a.account_no like '212%' and p.bank_account>'0' and p.date < '".$from_date."'");
            $loans_payable = mysql_fetch_array($loans_payable);
            $loans_payable = ($loans_payable['amount'] != NULL) ? $loans_payable['amount'] : 0;
            //PAYABLE PAID
            $payable_paid_res = mysql_query("select sum(amount) as amount from payable_paid where bank_account>'0' and date <'".$from_date."'");
            $payable_paid = mysql_fetch_array($payable_paid_res);
            $payable_paid_amt = ($payable_paid['amount'] == NULL) ? 0 : $payable_paid['amount'];
            //RECEIVALE COLLECTED
            $collected_res = mysql_query("select sum(amount) as amount from collected where bank_account>'0' and date <'".$from_date."'");
            $collected = mysql_fetch_array($collected_res);
            $collected_amt = ($collected['amount'] == NULL) ? 0 : $collected['amount'];
            //DISBURSED LOANS
            $disb_res = mysql_query("select sum(amount) as amount from disbursed where bank_account>'0' and date < '".$from_date."'");
            $disb = mysql_fetch_array($disb_res);
            $disb_amt = ($disb['amount'] == NULL) ? 0 : $disb['amount'];
            //PAYMENTS
            $pay_res = mysql_query("select sum(p.princ_amt + p.int_amt) as amount from payment p join disbursed d on p.loan_id=d.id  where p.date < '".$from_date."' and p.bank_account>0");
            $pay = mysql_fetch_array($pay_res);
            $pay_amt = ($pay['amount'] == NULL) ? 0 : $pay['amount'];
            //PENALTIES
            $pen_res = mysql_query("select sum(p.amount) as amount from penalty p join disbursed d on p.loan_id=d.id where p.status='paid' and p.date < '".$from_date."'");
            $pen = mysql_fetch_array($pen_res);
            $pen_amt = ($pen['amount']==NULL) ? 0 : $pen['amount'];
                                
            //SHARES
            $shares_res = mysql_query("select sum(value) as amount from shares where date <'".$from_date."'");
            $shares = mysql_fetch_array($shares_res); 
            $shares_amt = ($shares['amount'] == NULL) ? 0 : $shares['amount'];
            //RECOVERED
            $rec_res = mysql_query("select sum(r.amount) as amount from recovered r join written_off w on r.written_off_id=w.id join disbursed d on w.loan_id=d.id where r.date < '".$from_date."'");
            $rec = mysql_fetch_array($rec_res); 
            $rec_amt = ($rec['amount'] == NULL) ? 0 : $rec['amount']; 
            //INVESTMENTS 
            $invest_res = mysql_query("select sum(quantity * amount) as amount from investments where date < '".$from_date."'");
            $invest = mysql_fetch_array($invest_res);
            $invest_amt = ($invest['amount'] != NULL) ? $invest['amount'] : 0;
            //DIVIDENDS PAID
            $div_res = mysql_query("select sum(total_amount) as total_amount from share_dividends where date<'".$from_date."'");
            $div = mysql_fetch_array($div_res);
            $div_amt = ($div['amount'] != NULL) ? $div['amount'] : 0;

                                
            //SOLD INVESTMENTS
            $soldinvest_res = mysql_query("select sum(quantity * amount) as amount from sold_invest where  date < '".$from_date."'");
            $soldinvest = mysql_fetch_array($soldinvest_res);


            //FIXED ASSETS 
            $fixed_res = mysql_query("select sum(initial_value) as amount from fixed_asset where  date <'".$from_date."'");
            $fixed = mysql_fetch_array($fixed_res);
            $soldasset_res = mysql_query("select sum(amount) as amount from sold_asset where  date < '".$from_date."'");
            $soldasset = mysql_fetch_array($soldasset_res);
                                    
        /*  //CASH IMPORTED
            $import_res = mysql_query("select sum(amount) as amount from cash_transfer where dest_id>'0' and date <'".$from_date."'");
            $import = mysql_fetch_array($import_res);
            $import_amt = $import['amount'];

            //CASH EXPORTED
            $export_res = mysql_query("select sum(amount) as amount from cash_transfer where source_id>'0' and date <'".$from_date."'");
            
            $export = mysql_fetch_array($export_res);
            $export_amt = intval($export['amount']);
*/
            //CAPITAL FUNDS
            $fund_res = mysql_query("select sum(amount) as amount from other_funds where bank_account>0 and date <'".$from_date."'");
            $fund = mysql_fetch_array($fund_res);
            $fund_amt = $fund['amount'];

            $begin_bal =  $collected['amount'] + $dep['amount'] + $loans_payable + $other['amount'] - $with['amount'] - $expense['amount'] + $import_amt - $export_amt -$payable_paid['amount']  - $disb['amount'] + $pay['amount'] + $shares['amount'] + $pen['amount'] + $rec['amount'] + $soldasset['amount'] + $soldinvest['amount'] - $invest_amt - $fixed['amount'] - $div_amt + $fund_amt;   
    
    $content .="<table class='borderlessreports' width='100%' id='AutoNumber2' align=center>
    <tr bgcolor=white><th colspan=2 align=left><font size=2pt><b>".$effect." IN CASH AT END OF PERIOD</b></font></th><th align='right'><font size=2pt><b>".number_format($net, 0)."</b></font></th></th></tr>
    <tr><th colspan=2 align=left><font size=2pt><b>Cash Balance at the Beginning of the Period</b></font></th><th align='right'><font size=2pt><b>".number_format($begin_bal, 0)."</b></font></th></th></tr>
    <tr bgcolor=white><th colspan=2 align=left><font size=2pt><b>Cash Balance at End of Period</b></font></th><th align='right'><font size=2pt><b>".number_format(($begin_bal + $net_change), 0)."</b></font></th></th></tr></table>";
    return $content;
}
//put queries here

$mpdf=new mPDF('en-x','A4','','',12,15,47,47,10,10); 
//$mpdf->showImageErrors = true;
$mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins
//$mpdf->Image($r['Image'],10,50,30,10);
$header = '
'.headerfun($_GET['todate']).'<hr>
';
$headerE = '

';

$footer = '';

$footerE = '';
$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLHeader($headerE,'E');
//$mpdf->SetHTMLFooter($footer);
//$mpdf->SetHTMLFooter($footerE,'E');

$html = ''.bodyfun($_GET['fromdate'],$_GET['branch_id'],$_GET['todate']).'';

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