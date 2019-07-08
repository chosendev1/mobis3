<?php

if(!isset($_GET['format']))
echo("<head>
	<title>LIST OF EXPENSES</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");


//EXPENSE REPORT
function listFixedAssets($type, $from_year, $from_month, $from_mday, $to_year, $to_month, $to_mday, $format, $branch_id)
{
	$branch = ($branch_id=='all'||$branch_id=='')?NULL:"and r.branch_id=".$branch_id;
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$content = "<center><p><font color=#00008b size=4pt><b>".$branch['branch_name']."</b></font></p></center>";
	$content .= "<center><font color=#00008b size=3pt><b>LIST OF FIXED ASSETS</b></font></center>";

	
	$to_date = sprintf("%02d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
	$from_date = sprintf("%02d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);

	if (strtolower($type) == 'all')
	{
		$res = mysql_query("select ac.id as acc_id, ac.name as acc_name, fx.id as fxid, fx.initial_value as initval, fx.date as date, fx.dep_percent as dep_rate from accounts ac join  fixed_asset fx on fx.account_id = ac.id where fx.id not in (select asset_id from sold_asset) and fx.date >='".$from_date."' and fx.date <='".$to_date."' ".$branch." order by fx.date desc");
	}
	/****************************
	* SPECIFY ACCOUNT TYPE
	***************************/
	else
	{
		$res = mysql_query("select ac.id as acc_id, ac.name as acc_name, fx.id as fxid, fx.initial_value as initval, fx.date as date, fx.dep_percent as dep_rate from accounts ac join  fixed_asset fx on fx.account_id = ac.id where fx.id not in (select asset_id from sold_asset) and fx.date >='".$from_date."' and fx.date <='".$to_date."' and fx.account_id='".$type."' ".$branch." order by fx.date desc");
	}
	if (@mysql_num_rows($res) > 0)
	{
		
		$content .= "
			    <table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='70%' id='AutoNumber2' align=center>
			    <tr CLASS='headings'>
			        <th width='2%'></th><th>Asset Name</th><th>Purchase Date</th><th>Initial Value</th><th>Current Value</th><th>Dep. Rate (%)</th><th>Depreciation Values</th>
			    </tr>
			    ";
		$i = 1;	
		$tot_cur_balance = 0;
		$tot_initval = 0;
		$tot_cur_amt = 0;
		while ($fxrow = @mysql_fetch_array($res))
		{
			$cur = mysql_fetch_array(mysql_query("select sum(amount) amount from deppreciation where asset_id='".$fxrow['fxid']."'"));
			$cur_amt = ($cur['amount'] != NULL) ? $cur['amount'] : 0;
			$cur_balance = $fxrow['initval'] - $cur['amount'];
			$color = ($i%2 == 0) ? "white" : "lightgrey";
			$content .= "
				    <tr bgcolor=$color>
				      <td>$i.</td><td>$fxrow[acc_name]</td><td>$fxrow[date]</td><td>".number_format($fxrow['initval'], 2)."</td><td>".number_format($cur_balance, 2)."</td><td>$fxrow[dep_rate]</td><td><a href=# onclick=\"xajax_show_dep('".$fxrow['fxid']."', '".$type."', '".$from_year."', '".$from_month."', '".$from_mday."', '".$to_year."', '".$to_month."', '".$to_mday."', '".$branch_id."')\">".number_format($cur['amount'], 2)."</a></td>
				    </tr>
				    ";		
			$i++;
			$tot_cur_balance += $cur_balance;
			$tot_initval += $fxrow['initval'];
			$tot_cur_amt += $cur_amt;
		}
		$content .= "<tr class='headings'>
				      <td colspan=3>TOTAL</td><td>".number_format($tot_initval, 2)."</td><td>".number_format($tot_cur_balance, 2)."</td><td colspan=2>".number_format($tot_cur_amt, 2)."</td>
				    </tr></table>";
	}
	else
		$content .= "<font color=red>No fixed assets registered yet.</font><br>select ac.id as acc_id, ac.name as acc_name, fx.id as fxid, fx.initial_value as initval, fx.date as date, fx.dep_percent as dep_rate from accounts ac join  fixed_asset fx on fx.account_id = ac.id where fx.id not in (select asset_id from sold_asset) and fx.date >='".$from_date."' and fx.date <='".$to_date."' ".$branch." order by fx.date desc";
	export($format, $content);
}

//reports_header();
listFixedAssets($_GET['account'], $_GET['from_year'], $_GET['from_month'], $_GET['from_mday'], $_GET['to_year'], $_GET['to_month'], $_GET['to_mday'], $_GET['format'],$_GET['branch_id'])
//reports_footer();
?>
