<?php
include("../mpdf.php");
mysql_connect('localhost','root','');
mysql_select_db('hostelmanager');
$num = 0;
$project = $_GET['stdnumber'];
$qur = mysql_query("select * from Payment where StudentNumber='$project' order by AdmissionNumber Desc");
$fet = mysql_fetch_array($qur);
$qurg = mysql_query("select * from Admitted where StudentNumber='$project'");
$r = mysql_fetch_array($qurg);
$time = time();
$dat = date('d'."-".'m'."-".'y'." @ ".'H'.":".'i'.":".'s',$time);
$mpdf=new mPDF('en-x','A4','','',32,25,47,47,10,10); 

$mpdf->mirrorMargins = 1;	// Use different Odd/Even headers and footers and mirror margins
//$mpdf->Image($r['Image'],10,50,30,10);
$header = '
<table width="100%" style="border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;"><tr>
<td>Page <span style="font-size:14pt;">{PAGENO}</span></td>
<td ALIGN="center"><img src="ico.png" width="250px" HEIGHT="100PX"/></td>
<td>
<img src="" width="20px" height="50px" /></td>
</tr></table>
';
$headerE = '
<table width="100%" style="border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;"><tr>
<td>Page <span style="font-size:14pt;">{PAGENO}</span></td>
<td ALIGN="center"><img src="ico.png" width="250px" HEIGHT="100PX"/></td>
<td>
<img src="" width="20px" height="50px" /></td>
</tr></table>
';

$footer = '<table width="100%" style="border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;"><tr>
<td>
<img src="'.$r['Image'].'" width="20px" height="50px" /></td>
<td>
Signed and Verified By<br/><br/>
-------------------------------<br/>
[Drake]<br/>
Hostel Manager<br/><br/>
PrintOut From <u>Hostel Manager</u>

</td>
<td>
<img src="'.$r['Image'].'" width="20px" height="50px" /></td>
</tr></table>';
$footerE = '<table width="100%" style="border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;"><tr>
<td>
<img src="'.$r['Image'].'" width="20px" height="50px" /></td>
<td>
Signed and Verified By<br/><br/>
-------------------------------<br/>
[Drake]<br/>
Hostel Manager<br/><br/>
PrintOut From <u>Hostel Manager</u>

</td>
<td>
<img src="'.$r['Imag'].'" width="20px" height="50px" /></td>
</tr></table>';


$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLHeader($headerE,'E');
$mpdf->SetHTMLFooter($footer);
$mpdf->SetHTMLFooter($footerE,'E');

$html = '
<h4 align="center"><u>Payment Report For '.ucwords($fet['Fullname']).'</u></h4>

<table border=0 cellspacing="0" width="70%"><tr>
<th>Student No.</th><td>St00'.$project.'</td>
<th>Room Number.</th><td>'.$fet['RoomNum'].'</td>
</tr>
<tr>
<th>Student Name.</th><td>'.ucwords($fet['Fullname']).'</td>
<th>Room Category.</th><td>'.$fet['RoomCate'].'</td>
</tr>
<tr>
<th>Institution.</th><td>'.ucwords($fet['Institution']).'</td>
<th>Date & Time.</th><td>'.$dat.'</td>
</tr>
</table>

<br/>
<i>The Following Are the Various Payments that <u>'.$fet['Fullname'].' </u> has made.</i> <br> <br>
<table class="bpmTopicC" border=0 cellspacing="0" width="70%">
<tr><th width="10%" align="center">#</th><th width="50%" align="center">Date</th><th align="center">Amount Paid</th><th align="center">Balance</th></tr>
';
$qur2 = mysql_query("select * from Payment where StudentNumber='$project' order by AdmissionNumber Desc");
while($feter = mysql_fetch_array($qur2)){
$num += 1;
$html .= '
<tr><td>'.$num.'</td><td width="50%">'.$feter['RegDate'].'</td><td width="50%">'.$feter['Amount'].'</td><td width="50%">'.$feter['Balance'].'</td></tr>
';
}
$html .= '

</table>

<br/>


';

//==============================================================
//==============================================================
//==============================================================



//$mpdf=new mPDF('c','A4','','',32,25,27,25,16,13); 

$mpdf->SetDisplayMode('default');

$mpdf->SetWatermarkText("$fet[Fullname]");
$mpdf->watermark_font = 'DejaVuSansCondensed';
$mpdf->showWatermarkText = true;
$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

// LOAD a stylesheet
$stylesheet = file_get_contents('mpdfstyletables.css');
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($html);

$mpdf->Output();
exit;
//==============================================================
//==============================================================
//==============================================================


?>