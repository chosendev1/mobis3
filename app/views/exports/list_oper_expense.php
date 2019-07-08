<?php

echo("<head>
	<title>PERFOMANCE INDICATORS > GRAPH OF OPERATING EXPENSE RATIO AGAINST TIME</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");
reports_header();
echo("<img src=\"expense_ratio.jpg?dummy=".rand()."\" width=800 height=450>");
reports_footer();
?>
