<?php


if(!isset($_GET['format']))
echo("<head>
	<title>BALANCE SHEET</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");

//THE BALANCE SHEET
function balance_sheet($year,$month,$mday,$basis, $format){
	if($year == ''){
		$year = date('Y');
		$month = date('m');
		$mday = date('d');
	}
	$date = sprintf("%d-%02d-%02d 23:59:59", $year, $month, $mday);
	
	$rep=new balanceSheet($year,$basis);
	$rep->setDate();
	
	//$content="<br><center><font size=5>BALANCE SHEET AS: ".$rep->getDateto()."</font></center><br>";
	//$content="<br><center><font size=5>BALANCE SHEET AS: ".$date."</font></center><br>";
	$content.=$rep->balance_sheet2($date,$basis);
	export($format, $content);
}

//reports_header();
balance_sheet($_GET['year'],$_GET['month'],$_GET['mday'], $_GET['basis'], $_GET['format']);
//reports_footer();
?>
