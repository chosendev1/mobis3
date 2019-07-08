<?php

if(!isset($_GET['format']))
echo("<head>
	<title>LIST OF GROUPS</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");
//LIST LOAN GROUPS
function list_groups($stuff, $format){
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$content .= "<center><p><font color=#00008b size=4pt><b>".$branch['branch_name']."</b></font></p></center>";
	$content .= "<center><font color=#00008b size=3pt><b>LIST OF GROUPS</b></font></center><table height=100 border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center><tr><td>";

	$content .="<table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='70%' id='AutoNumber2' align=center>";
	if($stuff ==''){
		$content .= "<tr><td><center><font color=red>Select the groups you want to list!</font></center></td></tr>";
	}else{
		$stuff = ($stuff =='All') ? "" : $stuff;
		$sth = mysql_query("select distinct g.name as name, g.id as id from loan_group g join group_member gm on g.id=gm.group_id join member m on gm.mem_id=m.id where m.last_name like '%".$stuff."%' or m.first_name like '%".$stuff."%' or g.name like '%".$stuff."%'");
		if(@ mysql_numrows($sth) == 0)
			$content .= "<tr><td><center><font color=red>No groups registered yet!</font></center></td></tr>";
		else{
			$content .= "<tr class='headings'><td><b>No</b></td><td><b>Group Name</b></td><td><b>Members</b></td></tr>";
			$x=1;
			while($row = mysql_fetch_array($sth)){
				$color = ($x%2 == 0) ? "white" : "lightgrey";
				$content .= "<tr bgcolor=$color><td>".$x."</td><td>".$row['name']."</td><td>";
				$mem_res = mysql_query("select m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no from group_member gm join member m on gm.mem_id=m.id where gm.group_id='".$row['id']."' order by m.mem_no");
				$i=1;
				while($mem = mysql_fetch_array($mem_res)){
					$content .= $i.". ".$mem['mem_no']." - ".$mem['first_name']. "  ". $mem['last_name']."<br>";
					$i++;
				}
				$content .= "</td></tr>";
				$x++;
			}
		}
	}
	$content .="</table></td></tr></table>";
	export($format, $content);
}
//reports_header();
list_groups($_GET['stuff'], $_GET['format']);
//reports_footer();
?>
