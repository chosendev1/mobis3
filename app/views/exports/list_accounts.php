<?php


if(!isset($_GET['format']))
echo("<head>
	<title>SAVINGS PRODUCT ACCOUNTS FOR MEMBERS</title>
		<style>
.headings{ color:#ffffff; background-color:#00008b;}
	</style>
	</head><body>");

//LIST SAVINGS PRODUCT ACCOUNTS FOR MEMBERS
function list_accounts($group_name, $mem_no, $name, $product_id, $format,$branch_id,$num_rows,$stat){
	$branch_res = mysql_query("select * from branch where branch_no='".$_SESSION['branch_no']."'");
	$branch = mysql_fetch_array($branch_res);
	$branch_ = ($branch_id=='all'||$branch_id=='')?NULL:"m.branch_id=".$branch_id;
	$content .= "<center><p><font color=#00008b size=4pt><b>".$branch['branch_name']."</b></font></p></center>";
	$content .= "<center><font color=#00008b size=3pt><B>SAVINGS PRODUCT ACCOUNTS FOR MEMBERS</B></font></center>";
	$content .= "<table height=100 border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width=70% id='AutoNumber2' align=center>";
	if($mem_no=='' && $name=='' && $product_type=='')
		$content .= "<tr><td><font color=red>Please enter your search criteria</font></td></tr>";
	else{
				$group_name = ($group_name == 'All') ? "(g.name is null or m.id in (select mem_id from group_member))" : "g.name like '%".$group_name."%'";
		$mem_no1 = ($mem_no == 'All') ? "" : $mem_no;
		$name1 = ($name == 'All') ? "" : $name;
		if($product_id == '')
			$product = " and s.id like '%%'";
		else
			$product =" and s.id='".$product_id."'";
	  	//$sth = mysql_query("select  m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, a.name as name, a.account_no as account_no, d.id as id, d.open_date as open_date, d.close_date as close_date from mem_accounts d left join member m on d.mem_id=m.id  left join savings_product s on d.saveproduct_id=s.id join accounts a on s.account_id=a.id where (m.first_name like '%".$name1."%' or m.last_name like '%".$name1."%') and m.mem_no like '%".$mem_no1."%'".$product. " and a.name not in ('Compulsory Shares', 'Compulsory Savings')  order by m.first_name, m.last_name");
		$sth = mysql_query("select g.name as group_name, m.mem_no as mem_no, m.first_name as first_name, m.last_name as last_name, a.name as name, a.account_no as account_no, d.id as id, d.open_date as open_date, d.close_date as close_date from mem_accounts d left join member m on d.mem_id=m.id  left join group_member gm on m.id=gm.mem_id left join loan_group g on gm.group_id=g.id left join savings_product s on d.saveproduct_id=s.id join accounts a on s.account_id=a.id where (m.first_name like '%".$name1."%' or m.last_name like '%".$name1."%') and m.mem_no like '%".$mem_no1."%'".$product. " and a.name not in ('Compulsory Shares', 'Compulsory Savings')  and ".$group_name." ".$branch_." order by g.name, m.first_name, m.last_name ");
		if(@ mysql_numrows($sth)==0)
			$content .= "<tr bgcolor=lightgrey><td align=center><font color=red>No product accounts for the members selected</font></td></tr>";
		else{
			$content .= "<tr class='headings'><td><b>No</b></td><td><b>Member Name</b></td><td><b>Member No</b></td><td><b>Product</b></td><td><b>Date Opened</b></td><td><b>Balance</b></td><td><b>Status</b></td></tr>";
			$i=$stat+1;
			$group = 'NONE';
			
			$num = mysql_numrows($sth);
			$sub_total =0;
			$overall_total = 0;
			while($row = mysql_fetch_array($sth)){
				if($row['group_name'] <> $group){
					
					if($row['group_name'] == NULL)
						$group_show = 'NONE';
					else
						$group_show = $row['group_name'];
					$group = $row['group_name'];
					if($i >1){
						$content .= "<tr class='headings'><td colspan=5><b>GROUP: ".strtoupper($former)." SUB TOTAL</b></td><td colspan=3>".number_format($sub_total, 2)."</td></tr>";
						$overall_total += $sub_total;
						$sub_total =0;
					}

					$content .="<tr class='headings'><td colspan=8><b>GROUP: ".strtoupper($group_show)."</b></td></tr>";
					$former = $group_show;
				}
				$status = (date( 'Y-m-d', strtotime($row['close_date'])) <= date('Y-m-d')) ? "Closed" : "Active";
				//ACCOUNT BALANCE
				$dep_res = mysql_query("select sum(amount - flat_value - percent_value) as amount from deposit where memaccount_id='".$row['id']."'");
				$dep = mysql_fetch_array($dep_res);
				$dep_amt = ($dep['amount'] != NULL) ? $dep['amount'] : 0;
				//WITHDRAWALS
				$with_res = mysql_query("select sum(amount + flat_value + percent_value) as amount from withdrawal where memaccount_id='".$row['id']."'");
				$with = mysql_fetch_array($with_res);
				$with_amt = ($with['amount'] != NULL) ? $with['amount'] : 0;
				//MONTHLY CHARGES 
				$charge_res = mysql_query("select sum(amount) as amount from monthly_charge where memaccount_id='".$row['id']."'");
				$charge = mysql_fetch_array($charge_res);
				$charge_amt = ($charge['amount'] != NULL) ? $charge['amount'] : 0;
				//INTEREST AWARDE
				$int = mysql_fetch_array(mysql_query("select sum(amount) as amount from save_interest where memaccount_id='".$row['id']."'"));
				$int_amt = ($int['amount'] != NULL) ? $int['amount'] : 0;
				//LOAN REPAYMENTS
				$pay_res = mysql_query("select sum(princ_amt + int_amt) as amount from payment where mode='".$row['id']."'");
				$pay = mysql_fetch_array($pay_res);
				$pay_amt = ($pay['amount'] != NULL) ? $pay['amount'] : 0;
				//INCOME DEDUCTIONS
				$inc_res = mysql_query("select sum(amount) as amount from other_income where mode='".$row['id']."'");
				$inc = mysql_fetch_array($inc_res);
				$inc_amt = ($inc['amount'] != NULL) ? $inc['amount'] : 0;
				//TRANSFER TO SHARES
				$shares_res = mysql_query("select sum(value) as amount from shares where mode='".$row['id']."'");
				$shares = mysql_fetch_array($shares_res);
				$shares_amt = ($shares['amount'] != NULL) ? $shares['amount'] : 0;

				$balance = $dep_amt + $int_amt  - $with_amt - $charge_amt - $pay_amt - $inc_amt - $shares_amt;
				$sub_total += $balance;
				$color = ($i%2 == 0) ? "lightgrey" : "white";
				$content .= "<tr bgcolor=$color><td>".$i."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".$row['account_no']." - ".$row['name']."</td><td>".$row['open_date']."</td><td>".number_format($balance, 2)."</td><td>".$status."</td></tr>";
				if($i == $num){
					$overall_total += $sub_total;
					$content .= "<tr class='headings'><td colspan=5><b>".strtoupper($former)." SUB TOTAL</b></td><td colspan=3><B>".number_format($sub_total, 2)."</B></td></tr>
					<tr class='headings'><td colspan=5><b> OVERALL TOTAL </b></td><td colspan=3><B>".number_format($overall_total, 2)."</B></td></tr>";
				}

				$i++;
			}
		}
	}
	$content .= "</table>";
	export($format, $content);
}
//reports_header();
list_accounts($_GET['group_name'], $_GET['mem_no'], $_GET['name'], $_GET['product_id'], $_GET['format'],$GET['branch_id'],$_GET['num_rows'],$_GET['stat']);
//reports_footer();

?>