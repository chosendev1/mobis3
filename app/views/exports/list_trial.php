<?php

		if(!isset($_GET['format']))
echo("<head>
	<title>GENERAL LEDGER TRIAL BALANCE</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");


//GENERAL TRIAL BALANCE
function trial_balance($year, $basis, $format){
	$rep=new balanceSheet($year,$basis);
	$rep->setDate();
	$content="<center><font size=5>TRIAL BALANCE AS: ".$rep->getDateto()."</font></center><br>";
	$content.=$rep->trial_balance3();
	export($format, $content);
}
//reports_header();
trial_balance($_GET['year'],$_GET['basis'], $_GET['format']);
reports_footer();
?>
