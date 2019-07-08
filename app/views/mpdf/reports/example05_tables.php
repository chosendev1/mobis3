<?php
mysql_connect('localhost','root','');
mysql_select_db('threat');
$user = $_GET['user'];
$project = $_GET['project'];
$qur = mysql_query("select * from projects where id = '$project'");
$fet = mysql_fetch_array($qur);
$pro = $fet['Projectname'];
$comb = $pro.$user;
$name = $user;
$query_1 = mysql_query("SELECT * FROM securityreq");

$query_2 = mysql_query("SELECT * FROM likely");

$mit = mysql_query("SELECT * FROM mitigation where MitigationControl != '' order by effectiveness DESC");

$query_3 = mysql_query("SELECT Value FROM policies where UserProject='$comb' order by id_policy DESC");
$resue = mysql_fetch_array($query_3);

$query_4 = mysql_query("SELECT * FROM itsupport where UserProject='$comb' order by id_Itsupport DESC");
$resue4 = mysql_fetch_array($query_4);

$query_so = mysql_query("SELECT * FROM software where UserProject='$comb' order by id_software DESC");
$resueso = mysql_fetch_array($query_so);

//calculations
$Existance = ($resue['Value']+$$resue4['Value']+$resueso['Value'])-(($resueso['Value']*$$resue['Value'])+($resueso['Value']*$resue4['Value'])+($resue['Value']*$resue4['Value']))+($resueso['Value']*$resue['Value']*$resue4['Value']);
$final = number_format($Existance,4);

include("../mpdf.php");
$mpdf=new mPDF('en-x','A4','','',32,25,47,47,10,10); 
$mpdf->mirrorMargins = 1;
$header = '
<table width="100%" style="border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;"><tr>
<td width="33%">Left header p <span style="font-size:14pt;">{PAGENO}</span></td>
<td width="33%" align="center"><img src="sunset.jpg" width="126px" /></td>
<td width="33%" style="text-align: right;"><span style="font-weight: bold;">Right header</span></td>
</tr></table>
';
$headerE = '
<table width="100%" style="border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;"><tr>
<td width="33%"><span style="font-weight: bold;">Outer header</span></td>
<td width="33%" align="center"><img src="sunset.jpg" width="126px" /></td>
<td width="33%" style="text-align: right;">Inner header p <span style="font-size:14pt;">{PAGENO}</span></td>
</tr></table>
';

$footer = '<div align="center">See <a href="http://mpdf1.com/manual/index.php">documentation manual</a></div>';
$footerE = '<div align="center">See <a href="http://mpdf1.com/manual/index.php">documentation manual</a></div>';


$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLHeader($headerE,'E');
$mpdf->SetHTMLFooter($footer);
$mpdf->SetHTMLFooter($footerE,'E');


//hrml variable
$html = '
<h1>ThreNet Report</h1>
<h2>Project Name : '.$pro.' Analyzed By: '.$name.'</h2>
<br/>
System Description
<table class="bpmTopic"><thead></thead><tbody>
<tr>
<th>Governance</th><td>&nbsp;</td><td></td><td>'.$resue['Value'].'</td>
</tr>
<tr>
<th>Human Resource</th><td>&nbsp;</td><td></td><td>'.$resue4['Value'].'</td>
</tr>
<tr>
<th>Software</th><td>&nbsp;</td><td></td><td>'.$resueso['Value'].'</td>
</tr>
<tr>
<th></th><td>&nbsp;</td><td></td>
</tr>
<tr>
<th>Likelyhood of <br>existence</th><td>&nbsp;</td><td></td><td>'.$final.'</td>
</tr>';

$html.='
</tbody></table>
<br/>
Threat Analysis
<table class="bpmTopic"><thead></thead><tbody>
<tr>
<th>Threat</th>
<th>Asset</th>
<th>Vulnability</th>
<th>Security Requirement</th>
<th>LikelyHood</th>
</tr>';

while($resu = mysql_fetch_array($query_1)){
while($resu2 = mysql_fetch_array($query_2)){
$html.='
<tr>
<td>
<p>Threat'.$resu['ThreatId'].'</p>
</td>
<td>
<p>'.$resu['AssertName'].'</p>
</td>
<td>
<p>'.$resu['Vulnability'].'</p>
</td>
<td>
<p>'.$resu['SecurityReq'].'</p>
</td>
<td>
<p>'.$resu2['Likelihood'].'</p>
</td>
</tr>
';
}
}
$html.='
</tbody></table>
<br/>
Mitigation Control
<table class="bpmTopic"><thead></thead><tbody>
<tr>
<th>Mitigation</th>
<th>Threat</th>
<th>Effectiveness</th>
</tr>';


while($mits = mysql_fetch_array($mit)){
$html.='
<tr>
<td>
<p>'.$mits['MitigationControl'].'</p>
</td>
<td>
<p>Threat'.$mits['ThreatId'].'</p>
</td>
<td>
<p>'.$mits['effectiveness'].'</p>
</td>
</tr>
';
}
$html.='
</tbody></table>
<p>&nbsp;</p>
';


//==============================================================
//==============================================================
//==============================================================


$mpdf=new mPDF('c','A4','','',32,25,27,25,16,13); 

$mpdf->SetDisplayMode('fullpage');
$mpdf->SetWatermarkText('ThreNet');
$mpdf->watermark_font = 'DejaVuSansCondensed';
$mpdf->showWatermarkText = true;
$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

// LOAD a stylesheet
$stylesheet = file_get_contents('mpdfstyletables.css');
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($html,2);
$mpdf->Output('mpdf.pdf','I');
exit;
//==============================================================
//==============================================================
//==============================================================


?>