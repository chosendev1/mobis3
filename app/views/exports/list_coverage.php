<?php


echo("<head>
	<title>PERFOMANCE INDICATORS > GRAPH OF COVERAGE RISK RATIO AGAINST TIME</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");
reports_header();
echo("<img src=\"coverage.jpg?dummy=".rand()."\" width=800 height=450>");
reports_footer();
?>
