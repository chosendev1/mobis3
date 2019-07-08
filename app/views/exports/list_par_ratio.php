<?php

echo("<head>
	<title>PERFOMANCE INDICATORS > GRAPH OF PORTIFOLIO AT RISK AGAINST TIME</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");
reports_header();
echo("<img src=\"par_ratio.jpg?dummy=".rand()."\" width=800 height=450>");
reports_footer();
?>
