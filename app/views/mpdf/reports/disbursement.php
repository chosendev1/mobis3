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
            <td align=center><label><b><h1>'.$compa.' </h1></b>'.$address.'
<br> <h2 style="color:red; " >'.$reportname.'</h2>
            </label></td><td>&nbsp;</td>
            <td>Printed on: '.date('Y-m-d h:i:s').'<br>
              Printed by: '.ucwords(CAP_Session::get('username')).'<br>
              </td>
            </tr></table>';

return $content;
}

//body
function bodyfun($cust_name, $report_format, $branch_id, $cust_no, $account_name,$loan_officer,$from_year,$from_month,$from_mday,$to_year,$to_month,$to_mday){


 if($from_date=='' && $to_date==''){
    $tim = time();
    $today = date("Y-m-d h:i:s",$tim);
  $from_date = "0000-00-00 00:00:00";
  $to_date = $today;

  }
  elseif($from_date=='' && $to_date !=''){
 $from_date = "0000-00-00 00:00:00";
 $to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
  }elseif ($from_date!='' && $to_date =='') {
    $tim = time();
    $today = date("Y-m-d h:i:s",$tim);
$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
 $to_date = $today;
  }
  else{
    $from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
  $to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
}
  $name = ($cust_name == 'All') ? "" : $cust_name;
  $mem_no = ($cust_no == 'All') ? "" : $cust_no;

  if($loan_officer >0)
    $officer = "o.employeeId='".$loan_officer."'";
  else
    $officer = "o.employeeId > 0";
  ////$max_page = ceil(mysql_num_rows($sth)/$num_rows);
  $sth2 = mysql_query("select d.id as id, applic.id as applic_id,applic.`date` as apply_date, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, o.firstName as of_firstName, o.lastName as of_lastName, a.account_no as account_no, a.name as account_name, d.id as id, d.amount as amount, d.cheque_no as cheque_no, d.date as date, d.balance as balance, d.period as loan_period, d.int_rate as int_rate, d.int_method as int_method, d.grace_period as grace_period, d.writeoff_period as writeoff_period, d.arrears_period as arrears_period, d.written_off as written_off, d.bank_account as bank_account from disbursed d join loan_applic applic on d.applic_id=applic.id join member m on applic.mem_id=m.id join loan_product p on applic.product_id=p.id join accounts a on p.account_id= a.id join employees o on applic.officer_id=o.employeeId where (m.first_name like '%".$name."%' or m.last_name like '%".$name."%') and m.mem_no like '%".$mem_no."%' and a.name like '%".$account_name."%' and ".$officer." and d.date >= '".$from_date."' and d.date <='".$to_date."' ".$branch_." order by o.firstName, o.lastName, d.cheque_no, m.first_name, m.last_name ");
  if(@ mysql_num_rows($sth2) == 0){
    $content .= "<font color=red>No disbursed loans in your search options</font>";
    //$resp->assign("status", "innerHTML", $content);
    //return $resp;
  }

  
    $content .= '<table class="bpmTopicC" id="table-tools" width="100%"><tr><th width="5%" align="">#</th><th width="10%" align="">Loan No.</th><th width="10%" align="">Name</th><th width="10%" align="">Reciept</th><th width="10%" align=""> Approved Amt</th><th width="10%" align=""> Disbursed Amt</th><th width="10%" align="">Disbursement Date</th><th width="10%" align="">Processing Period</th><th width="25%" align="">Expiry Date</th><th width="10%" align="">Officer</th></tr><tr><td colspan="10"><hr></td></tr>';
      $former_officer = $officer;
   // }
      $c = 1;
        while($row = mysql_fetch_array($sth2)){
          //  $c++;
    $officer = $row['of_firstName']." ".$row['of_lastName'];
        //get the processing period
$appyday = strtotime($row['apply_date']);
$disbday = strtotime($row['date']);

  $diff = abs($disbday - $appyday);
 $years = floor($diff / (365*60*60*24));
 $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));       
 $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));


//expirry
 $expiry = date("Y-m-d H:i:s",strtotime("+".$row['loan_period']."days",strtotime($row['date'])));

    if($row['balance'] != $row['amount'] && $row['written_off']==0)
      $edit = "Payment Started";
    elseif($row['written_off'] == '1')
      $edit = "Written Off";
    else
      $edit = "<a href='javascript:;' onclick=\"xajax_edit_disbursed('".$row['id']."', '".$row['applic_id']."');\">Edit</a>";
    $bank_res = mysql_query("select a.name as account_name, a.account_no as account_no from bank_account b join accounts a on b.account_id=a.id where b.id='".$row['bank_account']."'");
    $bank = mysql_fetch_array($bank_res);
    //$color=($i%2 == 0) ? "lightgrey" : "white";
        if($report_format == "detail" || $report_format == ""){
    $content .= "<tr><td width='5%'>".$c."</td><td width='10%'>".$row['mem_no']."</td><td width='20%'>".$row['first_name']." ".$row['last_name']."</td><td width='10%'>".$row['cheque_no']."</td><td width='15%'>".number_format($row['amount'], 0)."</td><td width='15%'>".number_format($row['amount'], 0)."</td><td width='20%'>".$row['date']."</td><td width='15%' align='center'>".$days."</td><td width='20%'>".$expiry."</td><td width='15%'>".$officer."</td></tr>";}
    
    $amt_sub_total += $row['amount']; 
        $totalday += $days;
$c++;
  }
    
  $content .= "<tr><td colspan='10'><hr></td></tr><tr><td>Total</td><td></td><td></td><td></td><td>".number_format($amt_sub_total)."</td><td>".number_format($amt_sub_total)."</td><td></td><td>".round($totalday/$i)."</td><td></td><td></td></tr></tbody></table></div></div></div>";

return $content;
}
//put queries here

$mpdf=new mPDF('en-x','A4','','',12,15,47,47,10,10); 

$mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins
//$mpdf->Image($r['Image'],10,50,30,10);
$header = '
'.headerfun($_GET['cust_name'], $_GET['report_format'], $_GET['branch_id'], $_GET['cust_no'], $_GET['account_name'], $_GET['loan_officer'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_mday'], $_GET['to_year'],$_GET['to_month'],$_GET['to_mday']).'<hr>
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