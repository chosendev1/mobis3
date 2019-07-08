<?php


if(!isset($_GET['format']))
echo("<head>
	<title>Log Audit</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");

//
function display_log($userId, $action, $transaction, $relation, $from_year, $from_month, $from_mday, $from_hour, $to_year, $to_month, $to_mday, $to_hour, $format,$cmd)
	{

$user = ($userId == 'all') ? NULL: "and user_id=".$userId;
$act = ($action == 'all') ? NULL: "and action like '%".$action."%'";
$trans = ($transaction == 'all') ? NULL: "and action like '%".$transaction."%'";
$from_time = sprintf("%02d-%02d-%02d %02d", $from_year, $from_month, $from_mday, $from_hour);
$to_time = sprintf("%02d-%02d-%02d %02d", $to_year, $to_month, $to_mday, $to_hour);
$sql_cmd = (isset($cmd)) ? ",action" : NULL;
if($cmd)
$rel="";
switch($relation){
case 'all':
	$rel=NULL;
break;
case 'savings':
	$rel="and action like '%mem%' or action like '%open%' ". $user." ".$act." ".$trans." and time between '".$from_time."' and '".$to_time."' or action like '%deposit%' ". $user." ".$act." ".$trans." and time between '".$from_time."' and '".$to_time."' or action like '%withdraw%' ". $user." ".$act." ".$trans." and time between '".$from_time."' and '".$to_time."'";
break;
case 'Chart of Accounts':
	$rel="and action like '%pay%' or action like '%receivable%' ". $user." ".$act." ".$trans." and time between '".$from_time."' and '".$to_time."' or action like '%expense%' ". $user." ".$act." ".$trans." and time between '".$from_time."' and '".$to_time."' or action like '%other_%' ". $user." ".$act." ".$trans." and time between '".$from_time."' and '".$to_time."' and action like '%asset%' or action like '%invest%' ". $user." ".$act." ".$trans." and time between '".$from_time."' and '".$to_time."' or action like '%collect%' ". $user." ".$act." ".$trans." and time between '".$from_time."' and '".$to_time."'";
	break;
case 'loans':
	$rel="and action like '%loan%' or action like '%written_off%' ". $user." ".$act." ".$trans." and time between '".$from_time."' and '".$to_time."'";
	break;
case 'customers':
	$rel="and action like '%member%' or action like '%loan_group%' ". $user." ".$act." ".$trans." and time between '".$from_time."' and '".$to_time."'";
break;
case 'shares':
	$rel="and action like '%share%' or action like '%dividends%' ". $user." ".$act." ".$trans." and time between '".$from_time."' and '".$to_time."'";
break;
default:
	$rel=NULL;
}

$msg = mysql_query("select l.time,l.msg,u.name".$sql_cmd." from logs l join users u on l.user_id=u.id where time between '".$from_time."' and '".$to_time."' ".$user." ".$act." ".$trans." ".$rel." order by time desc" );
$num_operations = mysql_num_rows($msg);
$reply = ($num_operations==1) ? "Operation" : "Operations";
$thead="";
$td="";
if($num_operations>0){
	if($cmd=='show'){
		$thead = "<th>Command</th>";
		$td = "<td>".$row['action']."</td>";
	}
	else{
		$thead = NULL;
		$td = NULL;
	}
	$branchname = mysql_fetch_assoc(mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'"));
	if($userId == 'all')
		$member = "All Users";
	else{
	$name = mysql_fetch_assoc(mysql_query("select username,name from users where id=".$userId));
	$member = $name['username']." - ".$name['name'];
	}
		$content = "<center><font color=#00008b size=3pt><b>".$branchname['branch_name']."<br>LOG STATUS FOR ". strtoupper($member)." FROM ".$from_time." TO ".$to_time."</b></font></center><br>

<div align=RIGHT><strong>[".$num_operations."] ".$reply."</strong></div>";
$content .= "<table width='100%' height=100 align=CENTER cellspacing=1 cellpadding=0 style='border-collapse: collapse' bordercolor='#111111'><tr class='headings'><th>No.</th><th>Time</th><th>Name</th>".$thead."<th>Action</th><tr>";
$counter = 1;
while($row = mysql_fetch_assoc($msg))
	{
	$td = ($cmd=='show')? "<td>".$row['action']."</td>":NULL;
	$color = ($counter%2==0) ? "white" : "lightgrey";
$content .="<tr bgcolor=$color><td>".$counter."</td><td>".$row['time']."</td><td>".$row['name']."</td>".$td."<td>".$row['msg']."</td></tr>";
$counter++;
}
$content .= "</table>";

export($format, $content);
	
}
else
	$content .="<table height=100 border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='70%' id='AutoNumber2' align=center><tr><td><font color=red>No actions the user in the selected time range!</font><br></td></tr></table>";
	}
display_log($_GET['userId'], $_GET['action'], $_GET['transaction'], $_GET['relation'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['from_hour'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday'], $_GET['to_hour'], $_GET['format'], $_GET['cmd']);

?>
