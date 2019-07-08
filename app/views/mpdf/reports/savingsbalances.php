<?php

$this->load->view("mpdf/mpdf");
//queries
function headerfun($year,$branch_id,$month,$day,$format){
 $from_date = sprintf("%d-%02d-%02d", $year, $month, $day);

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
$reportname = "Member Savings Balance Report ";
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
              As At: '.$from_date.'</td>
            </tr></table>';

return $content;
}

//body
function bodyfun($year,$branch_id,$month,$day,$format){  
   $branch = ($branch_id=='all'||$branch_id=='')? "": 'where branch_id='.$branch_id;
    $mem_res = @mysql_query("select id as mem_id, mem_no, first_name, last_name from member ".$branch." order by first_name, last_name asc");
    $mem_res2 = @mysql_query("select id as mem_id, mem_no, first_name, last_name from member ".$branch." order by mem_no asc ");
    //$date = sprintf("%d-%02d-%02d", $date);
    //list($year,$month,$day) = explode('-', $date);

    $date = sprintf("%d-%02d-%02d", $year, $month, $day);
    $content = '<table class="bpmTopicC" id="table-tools" width="100%">

<tr><th width="5%">#</th><th width="15%">Name</th><th width="10%">Member No</th><th width="20%">Total Deposits</th><th width="20%">Total Withdrawals</th><th width="20%">Total Interest</th><th width="10%">Total Fees</th><th width="20%">Other Deductions</th><th width="15%">Balance</th><th>Batch Totals(100s)</th>
            </tr>
    <tr><td colspan="9"><hr></td></tr>

    ';
 
$x = $stat+1;
        $sub_net_save = 0;
        $sub_tot_fees = 0;
        $sub_tot_int = 0;
        $sub_tot_with = 0;
        $sub_tot_deduc = 0;
        $sub_tot_savings = 0;
        $dx=1;
        $batch_totals=0;
        $batches=0;
        while ($mem_row = @mysql_fetch_array($mem_res2))
        {
            $tot_savings = 0;
            $tot_shares=0;
            $tot_with = 0;
            $tot_fees = 0;
            $tot_int = 0;
            $tot_inc = 0;
            $tot_pay = 0;
            $tot_deduc = 0;
            $cumm_save = 0;
            $net_save = 0;
            $mem_ac = @mysql_query("select id as mem_acct_id from mem_accounts where mem_id = $mem_row[mem_id]");
            if (@mysql_num_rows($mem_ac) > 0)
            {
                while ($mem_ac_row = @mysql_fetch_array($mem_ac))
                {
                    $drow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_savings, sum(flat_value + percent_value) as charges from deposit where bank_account != 0 and memaccount_id = $mem_ac_row[mem_acct_id] and date <= '".$date."'"));
                    $wrow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_with, sum(flat_value + percent_value) as charges from withdrawal where memaccount_id = $mem_ac_row[mem_acct_id] and date <= '".$date."'"));
                    $mrow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_fees from monthly_charge where memaccount_id = $mem_ac_row[mem_acct_id] and date <= '".$date."'"));
                    $irow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_int from save_interest where memaccount_id = $mem_ac_row[mem_acct_id] and date <= '".$date."'"));
                    $incrow = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_inc from other_income where mode = '$mem_ac_row[mem_acct_id]' and date <= '".$date."'"));
                    $prow = @mysql_fetch_array(@mysql_query("select sum(princ_amt + int_amt) as tot_pay from payment where mode = '$mem_ac_row[mem_acct_id]' and date <= '".$date."'"));
                    $shares = mysql_fetch_array(mysql_query("select sum(value) as amount from shares where mode = '$mem_ac_row[mem_acct_id]' and date <= '".$date."'"));
                    
                
                    $tot_savings += ($drow['tot_savings'] != NULL)? $drow['tot_savings'] : 0 ;
                    $tot_fees += ($mrow['tot_fees'] != NULL)? $mrow['tot_fees'] : 0 ;
                    $tot_fees += ($drow['charges']  != NULL)? $drow['charges'] : 0 ;
                    $tot_fees += ($wrow['charges'] != NULL)? $wrow['charges'] : 0 ;
                    $tot_with += ($wrow['tot_with'] != NULL)? $wrow['tot_with'] : 0;
                    $tot_int += ($irow['tot_int'] != NULL)? $irow['tot_int'] : 0 ;
                    $tot_shares = ($shares['amount'] != NULL)? $shares['amount'] : 0 ;
                    $tot_deduc += ($incrow['tot_inc'] != NULL)? $incrow['tot_inc'] : 0 ;
                    $tot_deduc += ($prow['tot_pay']!= NULL)? $prow['tot_pay']: 0;
                    $tot_deduc += $tot_shares;
                    //tot_deduc += $tot_inc + $tot_pay;
                    
                    $net_save = $tot_savings + $tot_int - $tot_fees - $tot_with - $tot_deduc - $tot_shares;
                    $cumm_save += $net_save;
                } // close while mem_ac

            } // close if mem_ac
            $dx++;
	    $batches+=$net_save;
if($format == "" || $format == "detail"){ 
 	    if($dx%100==0)
            $batch_totals=$batches;
            else
            $batch_totals=0;
    //$tsave = number_format($tot_savings,2);
 $content .= "
           <tr>
            <td>".$dx."</td><td>".ucwords($mem_row['first_name']." ".$mem_row['last_name'])."</td><td>".$mem_row['mem_no']."</td><td>".number_format($tot_savings)."</td><td>".number_format($tot_with)."</td><td>".number_format($tot_int)."</td><td>".number_format($tot_fees)."</td><td>".number_format($tot_deduc)."</td><td> ".number_format($net_save)."</td><td> ".number_format($batch_totals)."</td>
            </tr>
            ";
            if($dx%100==0)
            $batches=0;
      /* $content .= "
           <tr>
            <td>$dx</td><td>$mem_row[first_name] $mem_row[last_name]</td><td>$mem_row[mem_no]</td><td>$tot_savings</td><td>".number_format($tot_with, 2)."</td><td>".number_format($tot_int, 2)."</td><td>$tot_fees</td><td>$tot_deduc</td>
            <td>$net_save</td>
            </tr>
            ";*/

        }
 $sub_net_save += $net_save;
                $sub_tot_fees += $tot_fees;
                $sub_tot_int += $tot_int;
                $sub_tot_with += $tot_with;
                $sub_tot_deduc += $tot_deduc;
                $sub_tot_savings += $tot_savings;
            $x++;
           // $dx++;
  }
if($format == "" || $format == "detail" || $format == "summary"){
        $content .= "

            <tfooter><tr>
           <th>Total</th><th>".$dx."</th><th></th><th>".number_format($sub_tot_savings)."</th><th>".number_format($sub_tot_with)."</th><th>".number_format($sub_tot_int)."</th><th>".number_format($sub_tot_fees)."</th><th>".number_format($sub_tot_deduc)."</th><th> ".number_format($sub_net_save)."</th><th> ".number_format($sub_net_save)."</th>
            </tr></tfooter>
            ";
   }


    $content .="</table>";
return $content;
}
//put queries here

$mpdf=new mPDF('en-x','A4','','',12,15,47,47,10,10); 

$mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins
//$mpdf->Image($r['Image'],10,50,30,10);
$header = '
'.headerfun( $_GET['year'], $_GET['branch_id'], $_GET['month'], $_GET['mday'], trim($_GET['format'])).'<hr>
';
$headerE = '

';

$footer = '';


$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLHeader($headerE,'E');
$mpdf->SetHTMLFooter($footer);
$mpdf->SetHTMLFooter($footerE,'E');

$html = ''.bodyfun( $_GET['year'], $_GET['branch_id'], $_GET['month'], $_GET['mday'], $_GET['format']).'';

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
