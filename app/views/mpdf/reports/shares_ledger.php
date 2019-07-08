<?php

$this->load->view("mpdf/mpdf");
//queries
function headerfun($mem_id){
 
$m = mysql_fetch_array(mysql_query("select * from member where id = '".$mem_id."'"));

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
$reportname = "Shares Ledger for ".ucwords($m['first_name']." ".$m['last_name']); 
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
            </td>
            </tr></table>';

return $content;
}
//$_GET['mem_no'],$_GET['branch_id'],$_GET['name'],$_GET['product'],$_GET['from_year'],$_GET['from_month'],$_GET['from_mday'],$_GET['to_year'],$_GET['to_month'],$_GET['to_mday']
//body
function bodyfun($mem_id,$type){
  $branch = ($branch_id=='all'||$branch_id=='')?NULL:"where branch_id=".$branch_id;
    $tot_shares = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_share from shares ".$branch));
    $mem_res = @mysql_query("select id, mem_no, first_name, last_name from member ".$branch." order by first_name, last_name asc");
    $mem_res2 = @mysql_query("select id, mem_no, first_name, last_name from member ".$branch." order by first_name, last_name asc");
 if($type == 'mem_no'){
        $mem_res = mysql_query("select * from member where mem_no='".$mem_id."'");
        if(mysql_numrows($mem_res) == 0){
            $resp->alert("No member identified by this number");
            //return $resp;
        }
        $mem = mysql_fetch_array($mem_res);
    
        $mem_id = $mem['id'];
    }
    else{
 $direct = @mysql_query("select id, date, shares, value, receipt_no from shares where mem_id = $mem_id and receipt_no != '' order by date asc");
    $inward = @mysql_query("select id, date, shares, value from share_transfer where to_id = $mem_id order by date asc");
    $outward = @mysql_query("select id, date, shares, value from share_transfer where from_id = $mem_id order by date asc");
    $div_res = mysql_query("select d.id,  s.date, d.amount from dividends d join share_dividends s on d.share_dividend_id=s.id where d.mem_id=$mem_id and s.bank_account=0 order by s.date asc");
    $mem = @mysql_fetch_array(@mysql_query("select first_name, mem_no, last_name from member where id = $mem_id"));
    $tot_mem_shares = 0; 
    $found_shares = 0;
    $branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
    $branch = mysql_fetch_array($branch_res);

    $content = '<table class="bpmTopicC" id="table-tools" width="100%">

<tr><th width="15%" align=left>Date</th><th width="35%" align=left>Type of Transaction</th><th width="15%" align=left>No. of Shares</th><th align=left width="15%">Value</th><th width="15%" align=left>Shares Balance</th><th align=left>Dividends</th><th width="15%" align=left>Total Shares</th> </tr>
    <tr><td colspan="7"><hr></td></tr>';
     
     $balance = 0;
    if (@mysql_num_rows($direct) > 0)
    {
        $found_shares += 1; $i = 0;
        while ($drow = @mysql_fetch_array($direct))
        {
            $balance += $drow['value'];
            $tot_mem_shares += $drow['shares'];
            //$color=($i % 2==0) ? "lightgrey" : "white";
            $content .= "<tr>
                    <td>".$drow['date']."</td><td>Direct Purchase - RCPT No: ".$drow['receipt_no']."</td><td align='left'>".$drow['shares']."</td><td align='left'>".number_format($drow['value'], 2)."</td><td align='left'>".$tot_mem_shares."</td><td>--</td><td>".number_format($balance, 2)."</td>
                    </tr>
                    ";
                    $i++;
        }
    }
    if (@mysql_num_rows($inward) > 0)
    {
        $found_shares += 1;
        $i = 0;
        while ($inrow = @mysql_fetch_array($inward))
        {
            $balance += $inrow[value];
            $tot_mem_shares += $inrow[shares];
            //$color=($i % 2==0) ? "lightgrey" : "white";
            $content .= "<tr>
                    <td>".$inrow['date']."</td><td>Inward transfer</td><td align='left'>".$inrow['shares']."</td><td align='left'>".number_format($inrow['value'], 2)."</td><td align='left'>".$tot_mem_shares."</td><td>--</td><td>".number_format($balance, 2)."</td>
                    </tr>
                    ";
                    $i++;
        }
    }
    if (@mysql_num_rows($outward) > 0)
    {
        $found_shares += 1;
        $i=0;
        while ($outrow = @mysql_fetch_array($outward))
        {
            $balance -= $outrow['value'];
            $tot_mem_shares -= $outrow['shares'];
            //$color=($i % 2==0) ? "lightgrey" : "white";
            $content .= "<tr>
                    <td>".$outrow['date']."</td><td>Outward transfer</td><td align='left'>".$outrow['shares']."</td><td align='left'>".number_format($outrow['value'], 2)."</td><td align='left'>".$tot_mem_shares."</td><td>--</td><td>".number_format($balance, 2)."</td>
                    </tr>
                        ";
                    $i++;
        }
    }
    if (@mysql_num_rows($div_res) > 0)
    {
        $found_shares += 1;
        $i=0;
        while ($div = @mysql_fetch_array($div_res))
        {
            $balance += $div['amount'];
            //$tot_mem_shares -= intval($outrow[shares]);
            //$color=($i % 2==0) ? "lightgrey" : "white";
            $content .= "<tr>
                    <td>".$div['date']."</td><td>Dividends</td><td align='left'>--</td><td align='left'>--</td><td align='left'>".$tot_mem_shares."</td><td>".number_format($div['amount'], 2)."</td><td>".number_format($balance, 2)."</td></td>
                    </tr>
                        ";
                    $i++;
        }
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
'.headerfun($_GET['mem_id']).'<hr>
';
$headerE = '

';

$footer = '';

$footerE = '';
$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLHeader($headerE,'E');
//$mpdf->SetHTMLFooter($footer);
//$mpdf->SetHTMLFooter($footerE,'E');

$html = ''.bodyfun($_GET['mem_id'],$_GET['type']).'';

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