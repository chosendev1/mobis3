<?php

$this->load->view("mpdf/mpdf");
//queries
function headerfun($cust_name, $cust_no, $account_name, $loan_officer, $from_date,$to_date,$branch_id,$report_format){


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
$reportname = "Ageing Report"; 
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
           <td><img src="'.base_url().'logos/'.$sel['logo'].'" alt = "logo" style="width:20%; height:20%;padding-top:0px;"/></td>
            <td>&nbsp;</td>
            <td align=center width="60%"><label><b><h1>'.$compa.' </h1></b>'.$address.'
<br> <h2 style="color:red; " >'.$reportname.'</h2>
            </label></td><td>&nbsp;</td>
            <td>Printed on: '.date('Y-m-d h:i:s').'<br>
              Printed by: '.ucwords(CAP_Session::get('username')).'<br>
              Report Period : '.$from_date.' to '.$to_date.'</td>
            </tr></table>';

return $content;
}

//body
function bodyfun($cust_name, $report_format, $branch_id, $cust_no, $account_name,$loan_officer,$from_year,$from_month,$from_mday,$to_year,$to_month,$to_mday){  list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
 if ($to_date =='') {
    $tim = time();
    $today = date("Y-m-d h:i:s",$tim);
//$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
 $to_date = $today;
  }
 // else{
  else{
    //$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
  $to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
}
  $name = ($cust_name == 'All') ? "" : $cust_name;
  $mem_no = ($cust_no == 'All') ? "" : $cust_no;

  if($loan_officer >0)
    $officer = "o.employeeId='".$loan_officer."'";
  else
    $officer = "o.employeeId > 0";
  ////$max_page = ceil(mysql_num_rows($sth)/$num_rows);
  $sth2 = mysql_query("select d.id as id, applic.id as applic_id, m.first_name as first_name, m.last_name as last_name, m.sex as sex, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.date as date, d.balance as balance, d.period as loan_period, d.arrears_period as arrears_period, d.int_rate as int_rate, d.int_method as int_method, d.next_princ_amt as next_princ_amt, d.next_int_amt as next_int_amt, d.next_pay_date as next_pay_date, d.last_pay_date as last_pay_date, d.pay_freq as pay_freq, date_format(d.last_pay_date, '%d') as last_mday, date_format(d.last_pay_date, '%m') as last_month, date_format(d.last_pay_date, '%Y') as last_year,p.int_rate as intrestrate from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date <='".$to_date."' and d.balance > 0 and d.written_off=0 ".$branch_." order by o.firstName, o.lastName, m.first_name, m.last_name ");
  if(@ mysql_numrows($sth2) == 0){
    $content = "<font color=red>No outstanding loans in your search options</font>";
  // $resp->assign("status", "innerHTML", $cont);
    //return $resp;
  }
  
 

    $content .= '<table class="bpmTopicC" id="table-tools" width="100%">

<tr><th rowspan=2><b>#</b></th><th rowspan=2><b>Member Name</b></th><th rowspan=2><b>Member No.</b></th><th rowspan=2><b>Loan Amount</b></th><th rowspan=2><b>Interest Amount</b></th><th rowspan=2><b>Principal Re-Paid</b></th><th rowspan=2><b>Interest Re-Paid</b></th><th colspan=5 style="text-align:center"><b>Principle in Arrears Per Age Class (days)</b></th><th rowspan=2><b>Interest in Arreas</b></th><th rowspan=2><b>Balance Principle</b></th><th rowspan=2><b>Balance Interest</b></th></tr>
            <tr><th><b>1-30</b></th><th><b>31-60</b></th><th><b>61-90</b></th><th><b>91-120</b></th><th><b>>120</b></th></tr>
    <tr><td colspan="16"><hr></td></tr>';
     

    $content .="</table>";
return $content;
}
//put queries here

$mpdf=new mPDF('en-x','A3','','',12,15,47,47,10,10); 

$mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins
//$mpdf->Image($r['Image'],10,50,30,10);
$header = '
'.headerfun($_GET['cust_name'], $_GET['cust_no'], $_GET['account_name'], $_GET['loan_officer'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday'], $_GET['format'],$_GET['branch_id']).'<hr>
';
$headerE = '

';

$footer = '';


$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLHeader($headerE,'E');
$mpdf->SetHTMLFooter($footer);
$mpdf->SetHTMLFooter($footerE,'E');

$html = ''.bodyfun($_GET['cust_name'], $_GET['report_format'], $_GET['branch_id'], $_GET['cust_no'], $_GET['account_name'], $_GET['loan_officer'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_mday'], $_GET['to_year'],$_GET['to_month'],$_GET['to_mday']).'';

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