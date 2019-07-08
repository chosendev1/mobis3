<?php

if(!isset($_GET['format']))
echo("<head>
	<title>LIST OF MEMBERS</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");
//LIST CUSTOMERS
function membersList($type, $mem_no, $mem_name, $format,$branch_id)
{
	$branch = ($branch_id=='all'||$branch_id=='')?NULL:"and branch_id=".$branch_id;
	$mem_no1 = ($mem_no == 'All') ? "" : $mem_no;
	$mem_name1 = ($mem_name == 'All') ? "" : $mem_name;
	
	if (strtolower($type) == 'all'){
		$mem = @mysql_query("select id, mem_no, first_name, last_name, sex, (DATEDIFF(NOW(), dob)/365) as age, kin, kin_telno, address, status,  email, telno from member  where (first_name like '%".$mem_name1."%' or last_name  like '%".$mem_name1."%') and mem_no like '%".$mem_no1."%' ".$branch." order by first_name, last_name asc");
		$head = "ALL CUSTOMERS LIST";
	}elseif (strtolower($type) == 'members'){
		$mem = @mysql_query("select id, mem_no, first_name, last_name, sex, (DATEDIFF(NOW(), dob)/365) as age, kin, kin_telno, address, status,  email, telno  from member where id in (select mem_id from shares) and (first_name like '%".$mem_name1."%' or last_name  like '%".$mem_name1."%') and mem_no like '%".$mem_no1."%'  ".$branch." order by first_name, last_name asc ");
		$head = "ALL MEMBERS LIST";
	}elseif (strtolower($type) == 'non_members'){
		$mem = @mysql_query("select id, mem_no, first_name, last_name, sex, (DATEDIFF(NOW(), dob)/365) as age, kin, kin_telno, address, status,  email, telno from member where id NOT in (select mem_id from shares) and (first_name like '%".$mem_name1."%' or last_name  like '%".$mem_name1."%') and mem_no like '%".$mem_no1."%'  ".$branch." order by first_name, last_name asc ");
		$head = "ALL NON-MEMBERS LIST";
	}
	if (mysql_num_rows($mem) >0)
	{
		$branch_res = mysql_query("select * from branch where branch_no='".$branch_id."'");
		$branchname = mysql_fetch_array($branch_res);
		$content .= "<center><p><font color=#00008b size=4pt><b>".$branchname['branch_name']."</b></font></p></center>";
		$content .= "
		    	    <br><center><font color=#00008b size=3pt><b>".$head."</b></font></center>
		    	    <table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>
			    <tr class='headings'>
					<th>No</th>
					<th>Member No.</th>
			       <th>Name</th>
					<th>Age</th>
					<th>Sex</th>
			       <th>Email</th>
			       <th>Phone</th>
			       <th>Physical Address</th>
					 <th>Kin</th>
			       <th>Kin Telno</th> 
			    </tr>
		    	    ";
		$i=$stat+1;
		while ($members = @mysql_fetch_array($mem))
		{
			$color=($i % 2==0) ? "lightgrey" : "white";
			$content .= "
				    <tr bgcolor=$color>
						<td>".$i."</td>
						 <td>$members[mem_no]</td>
				       <td>$members[first_name] $members[last_name]</td>
						<td>".floor($members['age'])."</td>
						<td>$members[sex]</td>
				       <td>$members[email]</td>
				       <td>$members[telno]</td>
				       <td>$members[address]</td>
						<td>$members[kin]</td>
				       <td>$members[kin_telno]</td>
				   </tr>";
					$i++;
		}
	}
	elseif($mem_no == '' && $mem_name=='')
		$content .= "<tr bgcolor=lightgrey><td><font color=red>Select a Client</font></td></tr>";
	elseif(strtolower($type) == 'all')
		$content .= "<tr bgcolor=lightgrey><td><center><font color='red'>No registered customers yet.</font></center><br></td></tr>";
	elseif (strtolower($type) == 'non_members')
		$content .= "<tr bgcolor=lightgrey><td><br><center><font color='red'>No non members registered yet.</font></center><br></td></tr>";	
	elseif (strtolower($type) == 'members')
		$content .= "<tr bgcolor=lightgrey><td><br><center><font color='red'>No members registered yet.</font></center><br></td></tr>";
	$content .= "</table>";
	export($format, $content);
}
//reports_header();
membersList($_GET['type'], $_GET['mem_no'], $_GET['mem_name'], $_GET['format'],$_GET['branch_id']);
//reports_footer();
?>
