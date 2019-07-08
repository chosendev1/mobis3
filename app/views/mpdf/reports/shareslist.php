<?php

$this->load->view("mpdf/mpdf");
//queries
function headerfun(){
 

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
$reportname = "Shares Report"; 
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
            </td>
            </tr></table>';

return $content;
}
//$_GET['mem_no'],$_GET['branch_id'],$_GET['name'],$_GET['product'],$_GET['from_year'],$_GET['from_month'],$_GET['from_mday'],$_GET['to_year'],$_GET['to_month'],$_GET['to_mday']
//body
function bodyfun($year,$month,$day,$branch_id){

$date = $year.'-'.$month.'-'.$day;

     $tot_balance = 0;
            $tot_dividends = 0;
            $tot_totalval = 0;
            $tot_tot_mem_shares = 0;
            $max_page=0;

  $branch = ($branch_id=='all'||$branch_id=='')?NULL:"where branch_id=".$branch_id;
    $tot_shares = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_share from shares ".$branch));
    $mem_res = @mysql_query("select id, mem_no, first_name, last_name from member ".$branch." order by first_name, last_name asc");
    $mem_res2 = @mysql_query("select id, mem_no, first_name, last_name from member ".$branch." order by mem_no asc");

    $content = '<table class="bpmTopicC" id="table-tools" width="100%">

<tr><th width="30%" align=left>Name</th><th width="20%" align=left>Member No.</th><th width="20%" align=left>No. of Shares</th><th width="20%" align=left>Value of Shares</th><th width="10%" align=left>Percentage</th><th width="20%" align=left>Dividends</th><th width="10%" align=left>Balance</th> </tr>
   ';
     $i=0;
while ($members = @mysql_fetch_array($mem_res2))
            {
                $row = @mysql_fetch_array(@mysql_query("select share_value as shareval, max_share_percent as percentage from branch"));
                $share_purchase = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_pur, sum(value) as value from shares where mem_id = ".$members['id']." and date <= '".$date."'"));
                $share_sale = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_sale from share_transfer where from_id = ".$members['id']." and date <= '".$date."'"));
                $bought = @mysql_fetch_array(@mysql_query("select sum(shares) as shares from share_transfer where to_id = ".$members['id']." and date <= '".$date."'"));
                $div_res = mysql_query("select sum(d.amount) as amount from dividends d join share_dividends s on d.share_dividend_id=s.id where d.mem_id=".$members['id']." and s.bank_account=0 and s.date <= '".$date."'");
                $div = mysql_fetch_array($div_res);
                $dividends = $div['amount'];

                $transact = $share_sale['tot_sale'] + $bought['shares'] - $share_sale['tot_sale'];
                $transacted_amt = $transact * $row['shareval'];

                $tot_mem_shares = $share_purchase['tot_pur'] - $share_sale['tot_sale'] + $bought['shares'];
                //$totalval = $tot_mem_shares * $row['shareval'];
                $totalval = $transacted_amt + $share_purchase['value'];

                $percentage = ($tot_mem_shares / $tot_shares['tot_share']) * 100;
                $percentage = sprintf("%.02f", $percentage);
                $balance = $totalval + $dividends;
                //$color = ($i % 2 ==0) ? "lightgrey" : "white";
                $content .= "<tr><td>".$members['first_name']."". $members['last_name']."</td><td>".$members['mem_no']."</td><td align='left'>".number_format($tot_mem_shares, 2)."</td><td align='left'>".number_format($totalval, 2)."</td><td align='left'>".$percentage." %</td><td align='left'>".number_format($dividends, 2)."</td><td align='left'>".number_format($balance,2)."</td></tr>";
                 $i++;
                $tot_balance += $balance;
                $tot_dividends += $dividends;
                $tot_percentage += $percentage;
                $tot_totalval += $totalval;
                $tot_tot_mem_shares += $tot_mem_shares; 
            }
             $content .= "<tr><th>Total</th><th>".number_format($i)."</th><th align='left'>".number_format($tot_tot_mem_shares, 0)."</th><th align='left'>".number_format($tot_totalval, 0)."</th><th align='left'></th><th align='left'>".number_format($tot_dividends, 0)."</th><th align='left'>".number_format($tot_balance,0)."</th></tr>";

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

$footerE = '';
$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLHeader($headerE,'E');
//$mpdf->SetHTMLFooter($footer);
//$mpdf->SetHTMLFooter($footerE,'E');

$html = ''.bodyfun($_GET['year'],$_GET['month'],$_GET['mday'],$_GET['branch_id']).'';

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