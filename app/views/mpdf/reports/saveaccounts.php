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
$reportname = "Member Saving Accounts"; 
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

//body
function bodyfun($group_name,$name,$product_id){
   if($name==""){        
       // $cont = "<font color=red>Please enter your search criteria</font>";
       // $resp->assign("status", "innerHTML", $cont);
        $name1 = "";
         $group_n = ($group_name == "") ? "(g.name is null or m.id in (select mem_id from group_member))" : "g.name like '%".$group_name."%'";
    //return $resp;
    }else{
        $group_n = ($group_name == 'All') ? "(g.name is null or m.id in (select mem_id from group_member))" : "g.name like '%".$group_name."%'";
        //$mem_no1 = ($mem_no == 'All') ? "" : $mem_no;
        if($name == "All" || trim($name) == ""){
$name1 = "";
        }else{
             $name1 = $name;
        }
       
        if($product_id == '')
            $product = " and s.id like '%%'";
        else
            $product =" and s.id='".$product_id."'";
    }
 $sth3 = mysql_query("select g.name as group_name, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, a.name as name, a.account_no as account_no, d.id as id, d.open_date as open_date, d.close_date as close_date from mem_accounts d left join member m on d.mem_id=m.id  left join group_member gm on m.id=gm.mem_id left join loan_group g on gm.group_id=g.id left join savings_product s on d.saveproduct_id=s.id join accounts a on s.account_id=a.id where (m.mem_no like '%".$name1."%') and ".$group_n." order by g.name, m.first_name, m.last_name ");

    $content = '<table class="bpmTopicC" id="table-tools" width="100%">

<tr><th><b>No</b></th><th  width=15%><b>Member Name</b></th><th><b>Member No</b></th><th width=25%><b>Product</b></th><th><b>Date Opened</b></th><th><b>Balance</b></th><th><b>Status</b></th></tr>
    <tr><td colspan="7"><hr></td></tr>';
   $i=$stat+1;
            $group = 'NONE';
            
            $num = mysql_numrows($sth3);
            $sub_total =0;
            $overall_total = 0;
            while($row = mysql_fetch_array($sth3)){
                if($row['group_name'] <> $group){
                    
                    if($row['group_name'] == NULL)
                        $group_show = 'NONE';
                    else
                        $group_show = $row['group_name'];
                    $group = $row['group_name'];
                    if($i >1){
                        $content .= "<tr><td colspan=7><hr></td></tr><tr><th colspan=4 align=left><b>GROUP: ".strtoupper($former)." SUB TOTAL</b></th><th></th><th>".number_format($sub_total, 2)."</th></tr><tr><td colspan=7><hr></td></tr>";
                        $overall_total += $sub_total;
                        $sub_total =0;
                    }

                    $content .="<tr class='headings'><td colspan=8><b>GROUP: ".strtoupper($group_show)."</b></td></tr>";
                    $former = $group_show;
                }
                $status = (date( 'Y-m-d', strtotime($row['close_date'])) <= date('Y-m-d')) ? "Closed" : "Active";
                //ACCOUNT BALANCE
                $dep_res = mysql_query("select sum(amount - flat_value - percent_value) as amount from deposit where memaccount_id='".$row['id']."'");
                $dep = mysql_fetch_array($dep_res);
                $dep_amt = ($dep['amount'] != NULL) ? $dep['amount'] : 0;
                //WITHDRAWALS
                $with_res = mysql_query("select sum(amount + flat_value + percent_value) as amount from withdrawal where memaccount_id='".$row['id']."'");
                $with = mysql_fetch_array($with_res);
                $with_amt = ($with['amount'] != NULL) ? $with['amount'] : 0;
                //MONTHLY CHARGES 
                $charge_res = mysql_query("select sum(amount) as amount from monthly_charge where memaccount_id='".$row['id']."'");
                $charge = mysql_fetch_array($charge_res);
                $charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
                //INTEREST AWARDE
                $int = mysql_fetch_array(mysql_query("select sum(amount) as amount from save_interest where memaccount_id='".$row['id']."'"));
                $int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;
                //LOAN REPAYMENTS
                $pay_res = mysql_query("select sum(princ_amt + int_amt) as amount from payment where mode='".$row['id']."'");
                $pay = mysql_fetch_array($pay_res);
                $pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;
                //INCOME DEDUCTIONS
                $inc_res = mysql_query("select sum(amount) as amount from other_income where mode='".$row['id']."'");
                $inc = mysql_fetch_array($inc_res);
                $inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;
                //TRANSFER TO SHARES
                $shares_res = mysql_query("select sum(value) as amount from shares where mode='".$row['id']."'");
                $shares = mysql_fetch_array($shares_res);
                $shares_amt = ($shares['amount'] != NULL) ? $shares['amount'] : 0;

                $balance = $dep_amt + $int_amt  - $with_amt - $charge_amt - $pay_amt - $inc_amt - $shares_amt;

                $sub_total += $balance;
                //$color=($i % 2==0) ? "lightgrey" : "white";
                $content .= "<tr><td>".$i."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".$row['account_no']." - ".$row['name']."</td><td>".$row['open_date']."</td><td>".number_format($balance, 2)."</td><td>".$status."</td></tr>
                ';";
                if($i == $num){
                    $overall_total += $sub_total;
                    $content .= "<tr><td colspan=7><hr></td></tr><tr><th colspan=4 align=left><b>GROUP: ".strtoupper($former)." SUB TOTAL</b></th><th></th><th>".number_format($sub_total, 2)."</th></tr><tr><td colspan=7><hr></td></tr>
                    <tr><th colspan=2><b> OVERALL TOTAL </b></th><th></th><th></th><th></th><th><b>".number_format($overall_total, 2)."</b></th></tr>
                     <tr><td colspan=7><hr></td></tr>';";
                }

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

$footerE = '';
$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLHeader($headerE,'E');
//$mpdf->SetHTMLFooter($footer);
//$mpdf->SetHTMLFooter($footerE,'E');

$html = ''.bodyfun($_GET['group_name'],$_GET['name'],$_GET['product_id']).'';

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