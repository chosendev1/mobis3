<?php

$this->load->view("mpdf/mpdf");
//queries
function headerfun($mem_no,$branch_id,$name,$product,$from_year,$from_month,$from_mday,$to_year,$to_month,$to_mday){
    $from_date = sprintf("%d-%02d-%02d", $from_year, $from_month, $from_mday);
        $to_date = sprintf("%d-%02d-%02d", $to_year, $to_month, $to_year);

//get branch
$bran = mysql_query("select * from branch where branch_no='".$branch_id."'");
               $bra = mysql_fetch_assoc($bran);
               $branum = mysql_num_rows($bran);
if($branum == 1){ $branch_didplay =  $bra['branch_name'];} else{$branch_didplay = 'All branches';}

//get loan product

if($account_name == ""){ $product_didplay = "All Products";} else{$product_didplay = $account_name;}

//report name
$reportname = "DEPOSITS REPORT";
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
           
            <td align=center width="50%"><label><b><h1>'.$compa.' </h1></b>'.$address.'
<br> <h2 style="color:red; " >'.$reportname.'</h2>
            </label></td><td>&nbsp;</td>
            <td>Printed on: '.date('Y-m-d h:i:s').'<br>
              Printed by: '.ucwords(CAP_Session::get('username')).'<br>
             Report Period: '.$from_date.' to '.$to_date.'</td>
            </tr></table>';

return $content;
}

//body
function bodyfun($mem_no,$branch_id,$name,$product,$from_year,$from_month,$from_mday,$to_year,$to_month,$to_mday){  
    
          $from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
        $to_date = sprintf("%d-%02d-%02d 23:59:59", $to_year, $to_month, $to_year);

 $sth = mysql_query("select d.receipt_no as receipt_no, d.bank_account as bank_account, d.id as id, d.percent_value as percent_value, d.flat_value as flat_value, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, d.amount as amount, d.date as date, a.account_no as account_no, a.name as name from deposit d join mem_accounts mem on d.memaccount_id=mem.id join  member m on mem.mem_id=m.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where mem.saveproduct_id like '%".$product."%' and (m.mem_no like '%".$dmem_no."%' and (m.first_name like '%".$dname."%' or m.last_name like '%".$dname."%')) and d.date >= '".$from_date."' and d.date <= '".$to_date."' and s.type='free' ".$branch_." order by m.first_name, m.last_name, d.date");
    
    $sth2 = mysql_query("select d.receipt_no as receipt_no, d.bank_account as bank_account, d.id as id, d.percent_value as percent_value, d.flat_value as flat_value, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, d.amount as amount, d.date as date, a.account_no as account_no, a.name as name from deposit d join mem_accounts mem on d.memaccount_id=mem.id join  member m on mem.mem_id=m.id join savings_product s on mem.saveproduct_id=s.id join accounts a on s.account_id=a.id where mem.saveproduct_id like '%".$product."%' and (m.mem_no like '%".$dmem_no."%' and (m.first_name like '%".$dname."%' or m.last_name like '%".$dname."%')) and d.date >= '".$from_date."' and d.date <= '".$to_date."' and s.type='free' ".$branch_." order by m.first_name, m.last_name, d.date ");
   




    $content = '<table class="bpmTopicC" id="table-tools" width="100%">

<tr><th width="20%" align=left><b>Member Name</b></th><th width="10%" align=left><b>MemberNo</b></th><th width="10%" align=left><b>Amount</b></th><th width="10%" align=left><b>ReceiptNo</b></th><th width="10%" align=left><b>Percent Charge</b></th><th width="10%" align=left><b>Flat Charge</b></th><th width="20%" align=left><b>Date</b></th><th width="30%" align=left><b>Product</b></th><th width="20%" align=left><b>Bank Account</b></th>
            </tr>
    <tr><td colspan="9"><hr></td></tr>
   
';
 $i=$stat+1;
            while($row = @mysql_fetch_array($sth2)){
                $bank_res = mysql_query("select a.account_no as account_no, a.name as name from bank_account b join accounts a on b.account_id=a.id where b.id='".$row['bank_account']."'");
                $bank = mysql_fetch_array($bank_res);
                //$color=($i % 2==0) ? "lightgrey" : "white";
                $content .= "<tr><td>".$row['first_name']. " ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".$row['amount']."</td><td>".$row['receipt_no']."</td><td>".$row['percent_value']."</td><td>".$row['flat_value']."</td><td>".$row['date']."</td><td>".$row['account_no']." - ".$row['name']."</td><td>".$bank['account_no']." - ".$bank['name']."</td></tr>";
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
'.headerfun( $_GET['mem_no'],$_GET['branch_id'], $_GET['name'], $_GET['product'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday']).'<hr>
';


$mpdf->SetHTMLHeader($header);
//$mpdf->SetHTMLHeader($headerE,'E');
//$mpdf->SetHTMLFooter($footer);
//$mpdf->SetHTMLFooter($footerE,'E');

$html = ''.bodyfun( $_GET['mem_no'],$_GET['branch_id'], $_GET['name'], $_GET['product'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday']).'';

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