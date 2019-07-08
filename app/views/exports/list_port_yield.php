<?php

if(!isset($_GET['format']))
echo("<head>
	<title>PERFOMANCE INDICATORS > GRAPH OF PORTIFOLIO YIELD AGAINST TIME</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");
reports_header();
echo("<img src=\"port_yield.jpg?dummy=".rand()."\" width=800 height=450>");
reports_footer();
?>
