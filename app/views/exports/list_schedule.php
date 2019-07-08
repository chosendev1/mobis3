<?php

//schedule($a,$b,$c);

$this->load->view("mpdf/mpdf");
//queries

//body
function bodyfun($loan_id, $applic_id){ 
  $sth = mysql_query("select date, begin_bal, end_bal, princ_amt, int_amt, (princ_amt + int_amt) as total_amt  from schedule where loan_id='".$loan_id."' order by date asc");
	//if(@ mysql_numrows($sth) > 0){
	$pay_res = mysql_query("select m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no from loan_applic applic join member m on applic.mem_id=m.id where applic.id='".$applic_id."'");
	$pay = mysql_fetch_array($pay_res);
	$disb_res = mysql_query("select * from disbursed where id='".$loan_id."'");
	$disb = mysql_fetch_array($disb_res);
	$sched_res = mysql_query("select sum(int_amt) as total_int, count(id) as instalments from schedule where loan_id='".$loan_id."'");
	$sched = mysql_fetch_array($sched_res);
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$content = "<center><p><font color=#00008b size=4pt><b>".$branch['branch_name']."</b></font></p></center>";
	$content .= "<br><center><font color=#00008b size=3pt><b>REPAYMENT SCHEDULE</b></font></center>
	<table height=100 border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='80%' id='AutoNumber2' align=center><tr><td>
		<table height=100 border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='80%' id='AutoNumber2' align=center>
			<tr><td>
			<center><b>Conditions</b></center>
			<table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>
			<tr bgcolor=lightgrey><td>Member Name</td><td>".$pay['first_name']." ".$pay['last_name']."</td></tr>
			<tr bgcolor=white><td>Member No</td><td>".$pay['mem_no']."</td></tr>
			<tr bgcolor=white><td>Cheque No</td><td>".$disb['cheque_no']."</td></tr>
			<tr bgcolor=lightgrey><td>Loan Amount</td><td>".number_format($disb['amount'], 2)."</td></tr>
			<tr bgcolor=white><td>Annual Interest Rate (%)</td><td>".$disb['int_rate']."</td></tr>
			<tr bgcolor=lightgrey><td>Loan Period (Months)</td><td>".($disb['period']/30)."</td></tr>
			<tr bgcolor=white><td>Grace Period (Months)</td><td>".($disb['grace_period']/30)."</td></tr>
		</table>
	</td><td>
		<center><b>Loan Summary</b></center>
		<table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>
			
			<tr bgcolor=white><td>Scheduled Number of Payments</td><td>".$sched['instalments']."</td></tr>
			<tr bgcolor=lightgrey><td>Total Interest</td><td>".number_format($sched['total_int'], 2)."</td></tr>
		</table>
	</td></tr>
	</table>
		<table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='80%' id='AutoNumber2' align=center>
			<tr class='headings'><td><b>No</b></td><td><b>Date</b></td><td><b>Beginning Balance</b></td><td><b>Total Payment</b></td><td><b>Principal</b></td><td><b>Interest</b></td><td><b>Ending Balance</b></td></tr>";
	$i=1;
	while($row = mysql_fetch_array($sth)){
		$end_bal = ($row['end_bal'] <= 0) ? "--" : $row['end_bal'];
		$color=($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr bgcolor=$color><td>".$i."</td><td>".$row['date']."</td><td>".number_format($row['begin_bal'], 2)."</td><td>".number_format($row['total_amt'], 2)."</td><td>".number_format($row['princ_amt'], 2)."</td><td>".number_format($row['int_amt'], 2)."</td><td>".number_format($end_bal, 2)."</td></tr>";
		$i++;
	}
	$content .= "</table></td></tr></table>";
return $content;
}
//put queries here
$a=$loan_id;
$b=$applic_id;
$mpdf=new mPDF('en-x','A3','','',12,15,47,47,10,10); 

$mpdf->mirrorMargins = 1; // Use different Odd/Even headers and footers and mirror margins
//$mpdf->Image($r['Image'],10,50,30,10);


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
