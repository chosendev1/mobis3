<?php

if(!isset($_GET['format']))
echo("<head>
	<title>SHARES LEDGER REPORT</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");

function show_dividends($share_dividend_id, $format){

	$sth = mysql_query("select * from share_dividends where id=$share_dividend_id");
	$row = mysql_fetch_array($sth);
	$heading = ($row['action'] == 'post') ? "DIVIDENDS POSTED TO MEMBERS " : "DIVIDENDS CREDITED ON MEMBERS' SHARES ";
	$branch_res = mysql_query("select * from branch");
	$branch = mysql_fetch_array($branch_res);
	$content .= "<center><p><font color=#00008b size=4pt><b>".$branch['branch_name']."</b></font></p></center>";
	$content .= "<center><font color=#00008b size=3pt><b>".$heading." ON ".$row['date']."</b></font></center><form method=post><table  border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>";
	$sth=mysql_query("select m.mem_no, m.first_name, m.last_name, d.amount from dividends d join member m on d.mem_id=m.id where d.share_dividend_id=$share_dividend_id order by m.first_name, m.last_name, m.mem_no");
	$content .="<tr class='headings'><td><b>No</b></td><td><b>Name</b></td><td><b>Member No</b></td><td><b>Amount</b></td></tr>";
	$i=1;
	while($row = mysql_fetch_array($sth)){
		$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr bgcolor=$color><td>$i</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".$row['amount']."</td></tr>";
		$i++;
	}
	$content .= "</table>";
	export($format, $content);
}
//reports_header();
show_dividends($_GET['share_dividend_id'], $GET['format']);
//reports_footer();

?>
