<?php

if(!isset($_GET['format']))
echo("<head>
	<title>INCOME STATEMENT</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");

//INCOME STATEMENT
function income_statement($year,$basis,$format){
	$rep=new balanceSheet($year,$basis);
	$rep->setDate();
	$content="<br><center><font size=5>INCOME STATEMENT FOR RANGE FROM ".$rep->getdatefrom()." TO ".$rep->getDateto()."</font></center><br>";
	$content.=$rep->income_statement3();
	export($format, $content);
}

//reports_header();
income_statement($_GET['year'], $_GET['basis'], $_GET['format']);
//reports_footer();
?>