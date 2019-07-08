<?php

echo("<head>
	<title>PERFOMANCE INDICATORS > GRAPH OF AVERAGE LOAN PORTIFOLIO AGAINST TIME</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");
reports_header();
echo("<img src=\"average_loan.jpg?dummy=".rand()."\" width=800 height=450>");
reports_footer();
?>
