<?php

$this->load->view("mpdf/mpdf");
//queries
function headerfun(){
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
$reportname = "List of Loans Products"; 
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
            <td><img src="'.base_url().'logo/logo.jpg" alt = "logo" style="width:5px; height:50px;padding-top:0px;"/></td>
           
            <td align=center width="50%"><label><b><h1>'.$compa.' </h1></b>'.$address.'
<br> <h2 style="color:red; " >'.$reportname.'</h2>
            </label></td><td>&nbsp;</td>
            <td>Printed on: '.date('Y-m-d h:i:s').'<br>
              Printed by: '.ucwords(CAP_Session::get('username')).'<br>
             </td>
            </tr></table>';

return $content;
}

//body
function bodyfun(){
 $sth = mysql_query("select p.id as loanproduct_id, p.int_rate as int_rate, p.int_method as int_method, p.based_on as based_on,  p.grace_period as grace_period, p.arrears_period as arrears_period, p.loan_period as loan_period, p.writeoff_period as writeoff_period, p.max_loan_amt as max_loan_amt, a.id as account_id, a.name as name, a.account_no as account_no from loan_product p join accounts a on p.account_id=a.id order by a.account_no");
    $content = '<table class="bpmTopicC" id="table-tools" width="100%">

<tr><th width="30%" align=left><b>Product</b></th><th width="20%" align=left><b>Int. Rate (% Per Annum)</b></th><th width="20%" align=left><b>Method</b></th><th width="10%" align=left><b>Grace Period (Days)</b></th><th width="20%" align=left><b>Arrears Maturity (Days)</b></th><th width="10%" align=left><b>Loan Period</b></th><th width="20%" align=left><b>Max. Amount</b></th><th width="15%" align=left><b>Write-off Period (Months)</b></th><th><b>Based On</b></th></tr>
    <tr><td colspan="10"><hr></td></tr>';
    
    $i=0;
    while($row = mysql_fetch_array($sth)){
      $grace_period = $row['grace_period'];
      $loan_period = $row['loan_period']/30;
      $arrears_period = $row['arrears_period'];
      $writeoff_period = $row['writeoff_period']/30;
      //$color= ($i%2 == 0) ? "lightgrey" : "white"; 
      $content .= "<tr><td>".$row['name']."</td><td>".$row['int_rate']."</td><td>".$row['int_method']."</td><td>".$grace_period."</td><td>".$arrears_period."</td><td>".$loan_period."</td><td>".number_format($row['max_loan_amt'], 2)."</td><td>".$writeoff_period."</td><td>".ucfirst($row['based_on'])."</td></tr>";
      $i++;
    }
    $content .="</table>";
return $content;
}
//put queries here

$mpdf=new mPDF('en-x','A4','','',12,15,47,47,10,10); 

$mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins
//$mpdf->Image($r['Image'],10,50,30,10);
$header = '
'.headerfun().'<hr>
';
$headerE = '

';

$footer = '';


$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLHeader($headerE,'E');
$mpdf->SetHTMLFooter($footer);
$mpdf->SetHTMLFooter($footerE,'E');

$html = ''.bodyfun().'';

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