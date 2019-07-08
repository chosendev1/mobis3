
<?php
//schedule($a,$b,$c);
$this->load->view("mpdf/mpdf");
//queries
function headerfun($approval_id, $loan_id){

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
$reportname = "Loan Repayment Schedule"; 
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
            <td align=center width="50%"><label><b><h1>'.$compa.' </h1></b>'.$address.'
<br> <h2 style="color:red; " >'.$reportname.'</h2>
            </label></td><td>&nbsp;</td>
            <td>Printed on: '.date('d-m-Y h:i:s').'<br>
              Printed by: '.ucwords(CAP_Session::get('username')).'<br>
              </td>
            </tr></table>';

return $content;
}

//body
function bodyfun($approval_id, $loan_id){ 
 $sth = mysql_query("select date,princ_amt, int_amt,installment,end_bal from schedule where approval_id='".$approval_id."' order by date asc");
 $sth2 = mysql_query("select sum(installment) as total_loan from schedule where approval_id='".$approval_id."' order by date asc");
 $totalLoan = mysql_fetch_array($sth2);
 $oustandingBal= $totalLoan['total_loan'];
	$loan_res = mysql_query("select m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no,m.branch_id,l.id as loan_id,l.amount as appAmt,l.voucher as appVoucher, ap.officer_id as officer_id,ap.grace_period,d.date as disb_date,d.amount as amount,l.product_id as product_id,ap.loan_period as loan_period,ap.grace_period as grace_period,ap.rate as int_rate,ap.repay_freq as freq from loan_applic l join member m on l.mem_id=m.id join approval ap on ap.applic_id=l.id join disbursed d on d.approval_id=ap.id where l.id='".$loan_id."' and ap.id='".$approval_id."'");
	$loan = mysql_fetch_array($loan_res);
	
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$grace_period=($loan['grace_period']==NULL) ? 0 : $loan['grace_period'];
	$product=libinc::getItemById("accounts",libinc::getItemById("loan_product",$loan['product_id'],"id","account_id"), "id","name");
	$branch=libinc::getItemById("branch",$loan['branch_id'], "branch_no","branch_name");
	$penalty=libinc::getItemById("loan_product",$loan['product_id'],"id","penalty_rate");
	$rate=libinc::getItemById("loan_product",$loan['product_id'],"id","int_rate");
	if($loan['freq']=="monthly")
	$freq="Months";
	if($loan['freq']=="weekly")
	$freq="Weeks";
	if($loan['freq']=="annually")
	$freq="Years";
	
	$content .= '<hr>
			<table class="bpmTopicC" id="table-tools" width="100%"><tr>
	                <td width="50%"><b>Member Name:</b>&nbsp;'.$loan['first_name'].' '.$loan['last_name'].'</td>;
			<td width="50%"><b>Member No:</b>&nbsp;'.$loan['mem_no'].'</td></tr>
			<tr><td width="50%"><b>Branch:</b>&nbsp;'.$branch.'</td>
			<td width="50%"><b>Loan Product:</b>&nbsp;'.$product.'</td></tr>
			<tr><td width="50%"><b>Amount Applied:</b>&nbsp;'.number_format($loan['appAmt']).'</td>
			<td width="50%"><b>Loan No:</b>&nbsp;'.$loan['loan_id'].'</td></tr>
			<tr><td width="50%"><b>Amount Disbursed:</b>&nbsp;'.number_format($loan['amount']).'</td>
			<td width="50%"><b>Repayment Frequency:</b>&nbsp;'.$loan['freq'].'</td>
			<tr><td width="50%"><b>Interest Rate:</b>&nbsp;'.$rate.'%&nbsp;'.$loan['freq'].'</td>
			<td width="50%"><b>Penalty Rate (%) p.a:</b>&nbsp;'.$penalty.'</td></tr>
			<tr><td width="50%"><b>Loan Period:</b>&nbsp;'.$loan['loan_period'].'&nbsp;'.$freq.'</td>
			<td width="50%"><b>Grace Period:</b>&nbsp;'.$grace_period.'</td></tr>';
			if($loan['appVoucher']=='Imported Balance'){
			$content.='<tr><td><b>Loan Type:</b>&nbsp;Imported Balance</td></tr>';
			}
			$content.='</table><br><br>';

         $content .= '<table class="bpmTopicC"  border="1" style="border-collapse: collapse" width="100%">

<tr><th width="10%" align=left><b>Period</b></th><th width="20%" align=left><b>Date</b></th><th width="20%" align=left><b>Principal</b></th><th width="20%" align=left><b>Interest</b></th><th width="20%" align=left><b>Instalment</b></th><th align=left><b>Balance</b></th></tr>';
 
     $i=0;
     $total_princ=0;
     $total_int=0;
     $total_inst=0;
     $total_bal=0;
   
	while($row = mysql_fetch_array($sth)){
		//$end_bal = ($row['end_bal'] <= 0) ? "--" : $row['end_bal'];
		list($y,$m,$d)=explode("-",$row['date']);
		$date=$d.'/'.$m.'/'.$y;
		$installment=$row['princ_amt']+$row['int_amt'];
		$oustandingBal-=$installment;		
		$content .= "<tr><td align=left>".$i."</td><td>".$date."</td><td align=left>".number_format($row['princ_amt'])."</td><td align=left>".number_format($row['int_amt'])."</td><td align=left>".number_format($installment)."</td><td align=left>".number_format($oustandingBal)."</td></tr>";
				
		$i++;
		$total_princ+=$row['princ_amt'];
		$total_int+=$row['int_amt'];
		$total_inst+=$installment;
		$total_bal+=$row['end_bal'];
	}
	
	$content .= "
	<tr><th width=5% align=left>Total</th><th></th><th align=left>".number_format($total_princ)."</th><th align=left>".number_format($total_int)."</th><th align=left>".number_format($total_inst)."</th><th align=left>".number_format($oustandingBal)."</th></tr>";
	
    $content .="</table>";
return $content;
}
//put queries here
$a=$approval_id;
$b=$loan_id;
$mpdf=new mPDF('en-x','A4','','',12,15,47,47,10,10); 

$mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins
//$mpdf->Image($r['Image'],10,50,30,10);
$header = '
'.headerfun($a,$b).'
';

$footer = '';

$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLHeader($headerE,'E');
$mpdf->SetHTMLFooter($footer);
$mpdf->SetHTMLFooter($footerE,'E');

$html = ''.bodyfun($a,$b).'';

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
