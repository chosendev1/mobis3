<?php
/*session_start();
require_once("common.php");
require_once('./xajax_0.2.4/xajax.inc.php');
$xajax = new xajax();
$xajax->errorHandleron();*/

//$xajax->registerFunction("sharesMenu");
$xajax->registerFunction("sharesRegForm");
$xajax->registerFunction("getShareVal");
$xajax->registerFunction("regShares");
$xajax->registerFunction("val_receipt");
$xajax->registerFunction("genSharesReport");
$xajax->registerFunction("shareTransForm");
$xajax->registerFunction("transferShares");
$xajax->registerFunction("check_buyer");
$xajax->registerFunction("update_shares");
$xajax->registerFunction("update2_shares");
$xajax->registerFunction("delete_shares");
$xajax->registerFunction("delete2_shares");
$xajax->registerFunction("update_transfer");
$xajax->registerFunction("update2_transfer");
$xajax->registerFunction("delete_transfer");
$xajax->registerFunction("delete2_transfer");
$xajax->registerFunction("sharesLedger");
$xajax->registerFunction("sharesLedgerForm");
$xajax->registerFunction("showEditShares");
$xajax->registerFunction("editShares");
$xajax->registerFunction("deleteShares");
$xajax->registerFunction("shareDivForm");
$xajax->registerFunction("shareDividends");

$xajax->registerFunction("sharing_done");
$xajax->registerFunction("show_dividends");
$xajax->registerFunction("delete_dividends");
$xajax->registerFunction("delete2_dividends");


function sharesRegForm($memid = 0, $action = 'cash')
{
$resp = new xajaxResponse();
$res = @mysql_query("select id, mem_no, first_name, last_name from member order by first_name, last_name asc");
if (isset($res))
{  $members  = "";
        while($row = @mysql_fetch_array($res))
        {
                if ($memid == $row['id'])
					$members .= "<option value='".$row['id']."' selected>".$row['first_name']."".$row['last_name']."-".$row['mem_no']."</option>";
				else
					$members .= "<option value='".$row['id']."'>".$row['first_name']."".$row['last_name']."-".$row['mem_no']."</option>";
        }
}
/*
if (strtolower($_SESSION['position']) == 'manager')
	$acc = @mysql_query("select ba.id as bank_id, ac.account_no as account_no, ac.name, ba.bank from accounts ac join bank_account ba on ba.account_id = ac.id");
else
	$acc = @mysql_query("select ba.id as bank_id, ac.account_no as account_no, ac.name, ba.bank from accounts ac join bank_account ba on ba.account_id = ac.id where ba.id in (select bank_account_id from user_account where user_id =".$_SESSION['user_id'].")");
if (isset($acc))
{
	while ($accrow = @mysql_fetch_array($acc))
	{
		$accts .= "<option value=$accrow[bank_id]>$accrow[account_no] - $accrow[bank] $accrow[name]</option>";
	}
}
*/
// 
if ($action == 'offset')
	{ $mems ="";
	   $accts ="";
		$constraint = ($memid == 0)? "" : " AND m.id = ".$memid;
		$mem_res = mysql_query("select m.first_name, m.last_name, m.mem_no, ma.id as memacc_id, a.name from member m join mem_accounts ma on m.id = ma.mem_id join savings_product p on ma.saveproduct_id = p.id join accounts a on p.account_id = a.id where lower(a.name) <> 'compulsory savings' and lower(a.name) <> 'compulsory shares' ".$constraint." order by m.first_name, m.last_name");
		$mems .= "<option value=''>&nbsp;</option>";
		while ($mem_row = mysql_fetch_array($mem_res))
		{
			$mems .= "<option value='".$mem_row['memacc_id']."'>".$mem_row['first_name']."".$mem_row['last_name']."-".$mem_row[mem_no]."-".($mem_row['name'])."</option>";
		}
		
		$accts .= "<option value='0'>Not Applicable</option>";
		
	}
	else
	{   $mems ="";
	   $accts ="";
	   $modes = "";
		$mems .= "<option value='0'>Not Applicable</option>";
		//if (strtolower($_SESSION['position']) == 'manager')
			$acc = @mysql_query("select ba.id, ac.name, ac.account_no, ba.bank from accounts ac join bank_account ba on ac.id = ba.account_id");
		/*else
			$acc = @mysql_query("select ba.id, ac.name, ac.account_no, ba.bank from accounts ac join bank_account ba on ac.id = ba.account_id where ba.id in (select bank_account_id from user_account where user_id =".$_SESSION['user_id'].")");*/
		$accts .= "<option value=''>&nbsp;</option>";
		while ($accrow = @mysql_fetch_array($acc))
		{
			$accts .= "<option value='".$accrow['id']."'>".$accrow['account_no']." - ".$accrow['bank']."".$accrow['name']."</option>";
		}
	}

	$modes .= $action == 'cash'? "<option value='cash' selected>Cash/Cheque</option>" : "<option value='cash' selected>Cash/Cheque</option>";
	$modes .= $action == 'offset'? "<option value='offset' selected>Offset from Member's Savings</option>" : "<option value='offset'>Offset from Member's Savings</option>";


//
$content ="";
$content .="<form method='post' class='panel form-horizontal form-bordered'>";
$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h4 class="semibold text-primary mt0 mb5">PURCHASE SHARES</h4>
                                               		 <p class="text-default nm"></p>
                                           	 </div>
                                        </div><div id="responsediv" class="alert alert-dismissable alert-danger"></div>';
                                        
	 
	$content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Buying Member:</label>
                                            <div class="col-sm-6">
                                            <select name="memid" id="memid" class="form-control">'.$members.'</select>
                                            </div>
                                            </div>';
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">No. of Shares:</label>
                                            <div class="col-sm-6">
                                           <input type=text name="num_shares" class="form-control" id="num_shares" onblur=\"xajax_getShareVal(document.getElementById(\'num_shares\').value, document.getElementById(\'memid\').value); return false;\"><div id="share_div"></div>
                                            </div></div>';
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Select Payment Mode:</label>
                                            <div class="col-sm-6">
                                           <select name="mode" id="mode" class="form-control" onchange=\"xajax_sharesRegForm(getElementById(\'memid\').value, getElementById(\'mode\').value); return false;\">'.$modes.'</select>
                                            </div></div>';
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Select Paying Member:</label>
                                            <div class="col-sm-6">
                                           <select name="mem_acc_id" class="form-control" id="mem_acc_id" class="form-control">'.$mems.'</select>
                                            </div></div>';
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Dest. Bank Acc:</label>
                                            <div class="col-sm-6">
                                           <select name="bank_act" class="form-control" id="bank_act" class="form-control">'.$accts.'</select>
                                            </div></div>';
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Receipt No:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" name="rcpt_no" class="form-control" id="rcpt_no" onblur=\"xajax_val_receipt(getElementById(\'rcpt_no\').value, \'0\')\"/>
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Date of Purchase</label>
                                            <div class="col-sm-6">
                                           <input type="text" class="form-control" id="date" name="date" placeholder="'.date('Y-m-d').'" />
                                            </div></div>';
                                            $content .= "<div class='panel-footer'><button type='reset' class='btn btn-default' onclick=\"xajax_sharesRegForm()\">Reset</button>
                                            <button type='button' class='btn btn-primary' onclick=\"xajax_regShares(document.getElementById('memid').value, document.getElementById('num_shares').value, document.getElementById('rcpt_no').value, document.getElementById('bank_act').value, getElementById('mem_acc_id').value, getElementById('date').value); return false;\">Save changes</button>";
                                            $content .= '</div>';
          
	 					$resp->call("createDate","date");
	
	
                       
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}
					
function getShareVal($num_shares, $mem_id)
{
	$resp = new xajaxResponse();
	$row = @mysql_fetch_array(@mysql_query("select share_value as shareval from branch"));
	//$totalval = intval($row[shareval]) * intval($num_shares);
	$totalval = $row['shareval'] * $num_shares;
	$content = "<div><font color=blue>Total Value of shares to be purchased is:".$totalval."</font></div>";
	$resp->assign("status", "innerHTML", $content);
	return $resp;

}

function val_receipt($receipt_no, $share_id){
	$resp = new xajaxResponse();
	$rec_res = @mysql_query("SELECT receipt_no FROM payment where receipt_no='".$receipt_no."' UNION SELECT receipt_no FROM deposit where receipt_no='".$receipt_no."' UNION SELECT receipt_no from collected where receipt_no='".$receipt_no."' UNION SELECT receipt_no from shares where receipt_no='".$receipt_no."' UNION SELECT receipt_no from other_income where receipt_no='".$receipt_no."' UNION SELECT receipt_no from recovered where receipt_no='".$receipt_no."' union select receipt_no from sold_asset where receipt_no='".$receipt_no."' union select receipt_no from sold_invest where receipt_no='".$receipt_no."'");
	
	if(@ mysql_numrows($rec_res) >0 && receipt_no !='')
		$resp->alert("ERROR: The receiptNo already exists");	
	return $resp;
}

function regShares($mem_id, $num_shares, $receipt_no, $bank_act_id, $mem_acc_id, $date)
{       $content ="";
   	 list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	
	$rec_res = mysql_query("select receipt_no from collected where receipt_no ='".$receipt_no."' union select receipt_no from payment where receipt_no ='".$receipt_no."' union select receipt_no from other_income where receipt_no ='".$receipt_no."' union select receipt_no from deposit where receipt_no ='".$receipt_no."' union select receipt_no from shares where receipt_no='".$receipt_no."' union select receipt_no from sold_asset where receipt_no='".$receipt_no."'");

	if(isFYClosed(parseFY($year,$month,$mday)))
		$resp->alert("Financial year has been Closed!\nCannot run a transaction in the selected period.");
	elseif($mem_id=='' || $num_shares=='')
		$resp->alert("Purchase not registered! \nPlease select a buying member \n and enter the number of shares!");
	elseif($mem_acc_id == 0 && $bank_act_id == '')
		$resp->alert("Purchase not registered! \nPlease select a bank account!");
	elseif($mem_acc_id == 0 && $receipt_no == '')
		$resp->alert("Purchase not registered! \nPlease enter a receipt no!");
	elseif(!$calc->isValidDate($mday, $month, $year))
		$resp->alert("Customer rejected! Please enter valid date");
	elseif($calc->isFutureDate($mday, $month, $year))
		$resp->alert("Transaction rejected! You have entered a future date");
	elseif(!preg_match("/\d[.]?\d*/", $num_shares))
		$resp->alert("Purchase not registered, \nplease specify the corrent number of shares");
	elseif($mem_acc_id == 0 && @mysql_num_rows($rec_res) >0)
		$resp->alert("ERROR: The receiptNo already exists");	
	else{
		$mode = ($mem_acc_id == 0) ? 'cash' : $mem_acc_id;
		start_trans();
		$row = @mysql_fetch_array(@mysql_query("select share_value as shareval, max_share_percent as max_percent, min_shares from branch"));
		//$totalval = intval($row[shareval]) * intval($num_shares);
		$totalval = $row['shareval'] * $num_shares;
		//$date = sprintf("%d-%02d-%02d", $date);
		$share_purchase = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_pur from shares where mem_id = ".$mem_id.""));
		$share_sale = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_sale from share_transfer where from_id = ".$mem_id.""));
		$tot_shares = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_share, count(distinct mem_id) as num_members from shares"));
		$tot_mem_shares = $share_purchase['tot_pur'] - $share_sale['tot_sale'] + $num_shares;
		if ($mode <> 'cash')
		{
			//GET MIN BALANCE
			$min_row = mysql_fetch_array(mysql_query("select min_bal from savings_product where id = (select saveproduct_id from mem_accounts where id = ".$mode.")"));
			$min_bal = $min_row['min_bal'];
			
			$balance = get_savings_bal($mode, date('Y'), date('m'), date('d'));
			if($balance < $min_bal){
				$resp->alert("Purchase not registered! \n Insufficient savings to offset the shares purchase: bal ".$balance. " min_bal ".$min_bal);
				rollback();
				return $resp;
			}
		}
	//$mem_percentage = (intval($tot_mem_shares) / intval($tot_shares[tot_share])) * 100;
		if ($tot_shares['num_members'] > 4) // min_members
		{
			$mem_percentage = ($tot_mem_shares / $tot_shares[tot_share]) * 100;
			if ($mem_percentage < $row['max_percent'])
			{
				$result = @mysql_query("insert into shares (shares, value, date, mem_id, receipt_no, bank_account, mode) VALUES ($num_shares, $totalval, '".$date."', $mem_id, '".$receipt_no."', $bank_act_id, '".$mode."')");
				if ($result)
				{
					/*
					Constraints: 1. Upgrade member status to paid if total_num_shares > min_shares
					 	2. Check that member doesn't exceed max_share_percent for branch if > 4 members
					*/
					if ($tot_mem_shares >= $row['min_shares'])
					{
			 			if(! mysql_query("update member set status = 'Paid' where id = ".$mem_id."")){
							$resp->alert("Transaction not registered! \n Could not update the member's status");
							rollback();
							return $resp;
						}
					}
					if(!mysql_query("UPDATE bank_account set account_balance =account_balance + ".$totalval." where id = $bank_act_id")){
						$resp->alert("Transaction not registered! \n Could not update the bank account balance");
						rollback();
						return $resp;
					}
					$content = "<div><font color=blue>Shares purchase registered successfully.</font></div>";
					commit();
					
				}else{
					
					$resp->assign("status", "innerHTML", "Transaction not registered! \n Could not insert the purchase!");
					rollback();
					return $resp;
				}
			}else
					$content = "<div><font color=red>You cannot purchase over ".$row['max_percent']."% of available shares.</font></div>";
		}else{
			
			$result = mysql_query("insert into shares (shares, value, date, mem_id, receipt_no, bank_account, mode) VALUES ($num_shares, $totalval, '".$date."', $mem_id, '".$receipt_no."', $bank_act_id, '".$mode."')");
			if ($result){
				if ($tot_mem_shares >= $row['min_shares']){
					if(!mysql_query("update member set status = 'Paid' where id = ".$mem_id."")){
						$resp->alert("Transaction not registered! \n Could not update the member's status");
						rollback();
						return $resp;	
					}
				}
				if(!mysql_query("UPDATE bank_account set account_balance = (account_balance + ".$totalval.") where id = $bank_act_id")){
					$resp->alert("Transaction not registered! \n Could not update the bank account balance");
					rollback();
					return $resp;
				}else{
						
					$content = "<div><font color=blue>Shares purchase registered successfully.</font></div>";
					commit();
				}
				
			}else{
				$resp->alert("Transaction not registered! \n Could not insert purchase");
				rollback();
				return $resp;	
			}
		}
	}
	$resp->assign("status", "innerHTML", $content);
	//$resp->call('genSharesReport', '', '', '');
	return $resp;
}

function check_buyer($smemid, $bmemid){
	$resp = new xajaxResponse();
	if($bmemid != '' && $smemid !=''){
		if($bmemid == $smemid){
			$resp->alert("The seller and the buyer cannot be the same");
		}
	}
	return $resp;
}

function shareTransForm()
{
	$resp = new xajaxResponse();
	$res = @mysql_query("select id, mem_no, first_name, last_name from member order by first_name, last_name asc");
	if (isset($res))
	{  $members ="";
	     while($row = @mysql_fetch_array($res))
	     {
	          $members .= "<option value=$row[id]> $row[first_name] $row[last_name] - $row[mem_no] </option>";
	     }
	}
	$resp->assign("status", "innerHTML", "");
	
	//Transfer shares form
	$content ="";
	$content .="<form method='post' class='panel form-horizontal form-bordered'>";
$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h4 class="semibold text-primary mt0 mb5">TRANSFER SHARES</h4>
                                               		 <p class="text-default nm"></p>
                                           	 </div>
                                        </div><div id="responsediv" class="alert alert-dismissable alert-danger"></div>';
                                        
	 
	$content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Selling Member:</label>
                                            <div class="col-sm-6">
                                            <select name="smemid" id="smemid" class="form-control"><option value="">'.$members.'</option></select>
                                            </div>
                                            </div>';                                           
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Buying Member:</label>
                                            <div class="col-sm-6">
                                            <select name="bmemid" id="bmemid" class="form-control" onblur=\"xajax_check_buyer(getElementById(\'smemid\').value, getElementById(\'bmemid\').value)\"><option value="">'.$members.'</select>
                                            </div></div>';
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">No. of Shares:</label>
                                            <div class="col-sm-6">
                                           <input type=text name="num_shares" id="num_shares" class="form-control" onblur=\'xajax_getShareVal(document.getElementById("num_shares").value, document.getElementById("smemid").value); return false;\'>
                                            </div></div>';                                            
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Date of Transfer</label>
                                            <div class="col-sm-6">
                                           <input type="text" class="form-control" id="date" name="date" placeholder="'.date('Y-m-d').'" />
                                            </div></div>';
                                            $content .= '<div class="panel-footer"><button type="reset" class="btn btn-default" onclick=\'xajax_shareTransForm()\'>Reset</button>
                                            <button type="button" class="btn btn-primary" onclick=\'xajax_transferShares(document.getElementById("smemid").value, document.getElementById("bmemid").value, document.getElementById("num_shares").value,  getElementById("date").value); return false;\'>Save</button>';
                                            $content .= '</div>';
          				$resp->call("createDate","date");
	
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


function transferShares($smem_id, $bmem_id, $num_shares, $date)
{       $content ="";
	 list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	if(isFYClosed(parseFY($year,$month,$mday))){
		$resp->alert("Financial year has been Closed!\nCannot run a transaction in the selected period.");
		return $resp;
	}
	if ($num_shares == '')
	{
		$resp->alert("Please enter the number of shares.");
		return $resp;
	}
	$row = @mysql_fetch_array(@mysql_query("select share_value as shareval, max_share_percent as percentage from branch"));
	$bshare_purchase = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_pur from shares where mem_id = ".$bmem_id.""));
	$bshare_sale = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_sale from share_transfer where from_id = ".$bmem_id.""));
	$btrans_out_res = mysql_query("select sum(shares) as shares from share_transfer where from_id='".$bmem_id."'");
	$btrans_out = mysql_fetch_array($btrans_out_res);
	$btrans_out = ($btrans_out['shares'] != NULL) ? $btrans_out['shares'] : 0;

	$btrans_in_res = mysql_query("select sum(shares) as shares from share_transfer where to_id='".$bmem_id."'");
	$btrans_in = mysql_fetch_array($btrans_in_res);
	$btrans_in = ($btrans_in['shares'] != NULL) ? $btrans_in['shares'] : 0;
	$tot_shares = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_share from shares"));
	$btotalval = $row['shareval'] * $num_shares;
	$btot_mem_shares = $bshare_purchase['tot_pur'] + $btrans_in + $num_shares  - $btrans_out - $bshare_sale['tot_sale'];
	$den = ($tot_shares['tot_share'] >0) ? $tot_shares['tot_share'] : 1;
	$bmem_percentage = ($btot_mem_shares / $den) * 100; // throws div by zero error
	
	$sshare_purchase = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_pur from shares where mem_id = ".$smem_id.""));
	$sshare_sale = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_sale from share_transfer where from_id = ".$smem_id.""));

	$sshare_sale = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_sale from share_transfer where from_id = ".$smem_id.""));
	$strans_out_res = mysql_query("select sum(shares) as shares from share_transfer where from_id='".$smem_id."'");
	$strans_out = mysql_fetch_array($strans_out_res);
	$strans_out = ($strans_out['shares'] != NULL) ? $strans_out['shares'] : 0;

	$strans_in_res = mysql_query("select sum(shares) as shares from share_transfer where to_id='".$smem_id."'");
	$strans_in = mysql_fetch_array($strans_in_res);
	$strans_in = ($strans_in['shares'] != NULL) ? $strans_in['shares'] : 0;
	$stot_mem_shares = $sshare_purchase['tot_pur'] + $strans_in -$strans_out - $sshare_sale['tot_sale'];
	$smem_percentage = ($stot_mem_shares / $tot_shares['tot_share']) * 100;
	//$date = sprintf("%02d-%02d-%02d", $date);
	if ($stot_mem_shares >= $num_shares)
	{
		$pledged_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.name='Compulsory Shares' and m.mem_id='".$smem_id."'");
		$pledged = mysql_fetch_array($pledged_res);
		$pledged_amt = ($pledged['amount'] != NULL) ? $pledged['amount'] : 0;
		$available_amt = $stot_mem_shares-$pledged_amt;
		if($available_amt < $num_shares) {  //IF THE SHARES ARE HELD TO GUARANTEE LOAN
			$available_shares = $available_amt/$row['share_value'];
			$resp->alert("Transfer not done! Only ".$available_shares." can be sold. \nThe rest is held against a loan taken");
			return $resp;
		}
	    if ($smem_id != $bmem_id)
	    {
		if ($bmem_percentage < $row['percentage'])
		{
			start_trans();
			$result = @mysql_query("INSERT INTO share_transfer (from_id, to_id, shares, value, date) VALUES ($smem_id, $bmem_id, $num_shares, $btotalval, '".$date."')");

			if ($result)
			{
				if ($btot_mem_shares >= 1){
				 	if(! mysql_query("update member set status = 'Paid' where id = '".$bmem_id."'")){
						$resp->alert("Transfer not done! \n Could not update member details");
						rollback();
						return $resp;
					}
				}
				if (($stot_mem_shares - $num_shares) < 1){
					if(! mysql_query("update member set status = 'not paid' where id = $smem_id")){
						$resp->alert("Transfer not done! \n Could not update member details");
						rollback();
						return $resp;
					}
				}
				$content = "<div><font color=blue> Shares transfer registered successfully.</font></div>";
				commit();
			}
			else
				$resp->alert("Share tranfer not registered! Could not insert it into the table");
		}
		else
			$resp->alert("You cannot purchase over ".$row['percentage']."% of available shares ". $bmem_percentage."");
	    }
	    else
			$resp->alert("Error: Selling member and Buying member cannot be the same!");
	}
	else
		$resp->alert("There are not enough shares to cover the transfer! ".$num_shares);
	
	$resp->assign("status", "innerHTML", $content);
	//$resp->call('genSharesReport', '', '', '');
	return $resp;
}

function update_transfer($transfer_id, $smem_id, $bmem_id, $num_shares, $date){
	$resp = new xajaxResponse();
	if ($num_shares == '')
	{
		$resp->alert("Please enter the number of shares.");
		return $resp;
	}
	$row = @mysql_fetch_array(@mysql_query("select share_value as shareval, max_share_percent as percentage from branch "));
	$bshare_purchase = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_pur from shares where mem_id = ".$bmem_id.""));
	$bshare_sale = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_sale from share_transfer where from_id = ".$bmem_id.""));
	$btrans_out_res = mysql_query("select sum(shares) as shares from share_transfer where from_id='".$bmem_id."' and id <>'".$transfer_id."'");
	$btrans_out = mysql_fetch_array($btrans_out_res);
	$btrans_out = ($btrans_out['shares'] != NULL) ? $btrans_out['shares'] : 0;

	$btrans_in_res = mysql_query("select sum(shares) as shares from share_transfer where to_id='".$bmem_id."' and id<>'".$transfer_id."'");
	$btrans_in = mysql_fetch_array($btrans_in_res);
	$btrans_in = ($btrans_in['shares'] != NULL) ? $btrans_in['shares'] : 0;
	$tot_shares = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_share from shares"));
	$btotalval = $row[shareval] * $num_shares;
	$btot_mem_shares = $bshare_purchase['tot_pur'] + $btrans_in + $num_shares  - $btrans_out - $bshare_sale['tot_sale'];
	$den = ($tot_shares[tot_share] >0) ? $tot_shares[tot_share] : 1;
	$bmem_percentage = ($btot_mem_shares / $den) * 100; // throws div by zero error
	
	$sshare_purchase = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_pur from shares where mem_id = ".$smem_id.""));
	$sshare_sale = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_sale from share_transfer where from_id = ".$smem_id.""));

	$sshare_sale = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_sale from share_transfer where from_id = ".$smem_id." and id<>'".$transfer_id."'"));
	$strans_out_res = mysql_query("select sum(shares) as shares from share_transfer where from_id='".$smem_id."' and id<>'".$transfer_id."'");
	$strans_out = mysql_fetch_array($strans_out_res);
	$strans_out = ($strans_out['shares'] != NULL) ? $strans_out['shares'] : 0;

	$strans_in_res = mysql_query("select sum(shares) as shares from share_transfer where to_id='".$smem_id."' and id<>'".$transfer_id."'");
	$strans_in = mysql_fetch_array($strans_in_res);
	$strans_in = ($strans_in['shares'] != NULL) ? $strans_in['shares'] : 0;
	$stot_mem_shares = $sshare_purchase['tot_pur'] + $strans_in -$strans_out;
	$smem_percentage = ($stot_mem_shares / $tot_shares[tot_share]) * 100;
	//$date = sprintf("%02d-%02d-%02d", $date);
	if ($stot_mem_shares >= $num_shares)
	{
		$pledged_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.name='Compulsory Shares' and m.mem_id='".$smem_id."'");
		$pledged = mysql_fetch_array($pledged_res);
		$pledged_amt = ($pledged['amount'] != NULL) ? $pledged['amount'] : 0;
		$branch_res = mysql_query("select * from branch");
		$branch = mysql_fetch_array($branch_res);
		$available_amt = $stot_mem_shares-$pledged_amt;
		if($available_amt < $num_shares) {  //IF THE SHARES ARE HELD TO GUARANTEE LOAN
			$available_shares = $available_amt/$branch['share_value'];
			$resp->alert("Update not done! Only ".$available_shares." can be sold. \nThe rest is held against a loan taken");
			return $resp;
		}
	    if ($smem_id != $bmem_id){
			if ($bmem_percentage < $row['percentage']){
				$resp->confirmCommands(1, "Do you really want to update?");
				$resp->call('xajax_update2_transfer', $transfer_id, $smem_id, $bmem_id, $num_shares, $btotalval, $date);
			}else
				$resp->alert("You cannot purchase over ".$row['percentage']."% of available shares ". $bmem_percentage."");
		}else
			$resp->alert("Error: Selling member and Buying member cannot be the same!");
	}else
		$resp->assign("status", "innerHTML", "There are not enough shares to cover the transfer! ".$num_shares."");
	return $resp;
}

function update2_transfer($transfer_id, $smem_id, $bmem_id, $num_shares, $btotalval, $date){
	$resp = new xajaxResponse();
	start_trans();
	$result = @mysql_query("update share_transfer set from_id='".$smem_id."', to_id='".$bmem_id."', shares='".$num_shares."', value='".$btotalval."', date='".$date."' where id='".$transfer_id."'");
	if ($result)
	{
		if ($btot_mem_shares >= 1){
		 	if(! mysql_query("update member set status = 'Paid' where id = '".$bmem_id."'")){
				$resp->alert(mysql_error()."Transfer not done! \n Could not update member details");
				rollback();
				return $resp;
			}
		}
		if (($stot_mem_shares - $num_shares) < 1){
			if(! mysql_query("update member set status = 'not paid' where id ='".$smem_id."'")){
				$resp->alert(mysql_error()."Transfer not done! \n Could not update member details");
				rollback();
				return $resp;
			}
		}
		$action = "update share_transfer set from_id='".$smem_id."', to_id='".$bmem_id."', shares='".$num_shares."', value='".$btotalval."', date='".$date."' where id='".$transfer_id."'";
		mysql_query("insert into logs set user_id='".$_SESSION['user_id']."', time=NOW(),  action='".$_SESSION['name'].":  ".mysql_escape_string($action)."'");
		$resp->assign("status", "innerHTML", "<br><font color=blue> Shares transfer registered successfully.</font><br>");
		commit();
	}else
		$resp->alert("Share tranfer not registered! Could not insert it into the table");
	return $resp;
}

function delete_transfer($transfer_id, $smem_id, $bmem_id){
	$resp = new xajaxResponse();
	$branch_res = mysql_query("select share_value from branch");
	$branch = mysql_fetch_array($branch_res);
	$shares_res = mysql_query("select sum(shares) as shares from shares where mem_id='".$bmem_id."'");
	$shares = mysql_fetch_array($shares_res);
	$shares_amt = ($shares['shares'] != NULL) ? $shares_amt : 0;
	$buy_res = mysql_query("select sum(shares) as shares from share_transfer where to_id='".$bmem_id."' and id<>'".$transfer_id."'");
	$buy = mysql_fetch_array($buy_res);
	$buy_amt = ($buy['shares'] != NULL) ? $buy['shares'] : 0;
	
	$sell_res = mysql_query("select sum(shares) as shares from share_transfer where from_id='".$bmem_id."' and id<>'".$transfer_id."'");
	$sell = mysql_fetch_array($buy_res);
	$sell_amt = ($sell['shares'] != NULL) ? $sell['shares'] : 0;

	//COMPULSORY SHARES
	$pledged_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.name='Compulsory Shares' and m.mem_id='".$bmem_id."'");
	$pledged = mysql_fetch_array($pledged_res);
	$pledged_amt = ($pledged['amount'] != NULL) ? $pledged['amount'] : 0;

	$available_amt = $shares_amt + $buy_amt - $sell_amt;
	if($available_amt < $pledged_amt){
		$resp->alert("Cannot delete this transfer! \nThe Buying member used the shares to guarantee a loan");
		return $resp;
	}
	$resp->confirmCommands(1, "Do you really want to delete this transfer?");
	$resp->call('xajax_delete2_transfer', $transfer_id);
	return $resp;
}

function delete2_transfer($transfer_id){
	$resp = new xajaxResponse();
	mysql_query("delete from share_transfer where id='".$transfer_id."'");
	$action = "delete from share_transfer where id='".$transfer_id."'";
	mysql_query("insert into logs set user_id='".$_SESSION['user_id']."', time=NOW(),  action='".$_SESSION['name'].":  ".mysql_escape_string($action)."'");
	$resp->assign("status", "innerHTML", "<font color=red>Share transfer deleted</font>");
	return $resp;
}
function genSharesReport($date)
{
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	//$date = sprintf("%02d-%02d-%02d", $date);
	$tot_shares = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_share from shares"));
	$mem_res = @mysql_query("select id, mem_no, first_name, last_name from member order by first_name, last_name asc");
	if ($tot_shares['tot_share'] != NULL)
	{
				
		$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <p><h5 class="semibold text-primary mt0 mb5">SEARCH FOR SHARES REPORT</h5></p>
                            </div>               
                            <div class="panel-body">';
                                                 
                       $content .= '<div class="form-group">
                                    <div class="row">
                                    <div class="col-sm-6">
                                            <label class="control-label">As at:</label>
                                           <input type="text" class="form-control" id="date" name="date" placeholder="date" /></div>
                                    </div>
                                </div></div>';
                                                                      
                
	$content .= "<div class='panel-footer'>                            
                                
                                <input type='button' class='btn  btn-primary' value='Show'  onclick=\"xajax_genSharesReport(document.getElementById('date').value)\">
                            </div></div>
                        </form>
                        <!--/ Form default layout -->
                    </div></div>"; 
                    $resp->call("createDate","date");
        
        //$resp->assign("display_div", "innerHTML", $content);        
                            		
		if($date == ''){
			$cont = "<font color=red>Select the Date for Your Report!</font>";
			$resp->assign("status", "innerHTML", $cont);
	                //return $resp;
		}
		else{  
			$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                
                                <p><h4 class="semibold text-primary mt0 mb5">SHARES REPORT</h4></p>
                               
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
			$content .="<thead><th>Name</th><th>Member No.</th><th>No. of Shares</th><th>Value of Shares</th><th>Percentage</th><th>Dividends Receivable</th><th>Balance</th></thead><tbody>";
			$i=0;
			$tot_balance = 0;
			$tot_dividends = 0;
			$tot_totalval = 0;
			$tot_tot_mem_shares = 0; 
			while ($members = @mysql_fetch_array($mem_res))
			{
				$row = @mysql_fetch_array(@mysql_query("select share_value as shareval, max_share_percent as percentage from branch"));
				$share_purchase = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_pur from shares where mem_id = ".$members['id']." and date <= '".$date."'"));
				$share_sale = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_sale from share_transfer where from_id = ".$members['id']." and date <= '".$date."'"));
				$bought = @mysql_fetch_array(@mysql_query("select sum(shares) as shares from share_transfer where to_id = ".$members['id']." and date <= '".$date."'"));
				$div_res = mysql_query("select sum(d.amount) as amount from dividends d join share_dividends s on d.share_dividend_id=s.id where d.mem_id=".$members['id']." and s.bank_account=0 and s.date <= '".$date."'");
				$div = mysql_fetch_array($div_res);
				$dividends = $div['amount'];

				$tot_mem_shares = $share_purchase['tot_pur'] - $share_sale['tot_sale'] + $bought['shares'];
				$totalval = $tot_mem_shares * $row['shareval'];
				$percentage = ($tot_mem_shares / $tot_shares['tot_share']) * 100;
				$percentage = sprintf("%.02f", $percentage);
				$balance = $totalval + $dividends;
				//$color = ($i % 2 ==0) ? "lightgrey" : "white";
				$content .= "<tr><td>".$members['first_name']."".$members['last_name']."</td><td>".$members['mem_no']."</td><td align='center'>".number_format($tot_mem_shares, 2)."</td><td align='center'>".number_format($totalval, 2)."</td><td align='center'>".$percentage."%</td><td align='center'>".number_format($dividends, 2)."</td><td align='center'>".number_format($balance,2)."</td></tr>";
				$i++;
				$tot_balance += $balance;
				$tot_dividends += $dividends;
				$tot_totalval += $totalval;
				$tot_tot_mem_shares += $tot_mem_shares; 
			}
		
		$content .= "<tr><td></td><td></td><td align=right>".number_format($tot_tot_mem_shares,2)."</td><td align='right'>".number_format($tot_totalval, 2)."</td></td><td><td align='right'>".number_format($tot_dividends, 2)."</td><td align='right'>".number_format($tot_balance, 2)."</td></tr>";
		$content .= "</tbody></table></div>";
		$resp->call("createTableJs");
		}
	}
	else{
		$cont = "<font color=red> No registered shares yet.</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	}		
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//LIST DIVIDENDS SHARING
function sharing_done($from_date, $to_date){
	$resp = new xajaxResponse();
	list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
        $resp->assign("status", "innerHTML", "");	
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <p><h5 class="semibold text-primary mt0 mb5">SHARING DONE</h5></p>
                            </div>               
                            <div class="panel-body">';
                            
                      $content .="<div class='form-group'>
                                    <div class='row'>
                                        <div class='col-sm-6'>
                                            <label class='control-label'>Date range:</label>
                                            <div class='row'>
                                                <div class='col-md-6'><input type='text' class='form-control' id='from_date' name='from_date' placeholder='From' /></div>
                                                <div class='col-md-6'><input type='text' class='form-control' id='to_date' name='to_date' placeholder='to' /></div>
                                            </div>
                                    </div>
                                        </div>
                                    </div>";                                  
                                                                      
                
	$content .= "<div class='panel-footer'>                              
                                
                                <input type='button' class='btn  btn-primary' value='Show Report'  onclick=\"xajax_sharing_done(getElementById('from_date').value,getElementById('to_date').value)\">
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","from_date");
		    $resp->call("createDate","to_date");
            //$resp->assign("display_div", "innerHTML", $content);        
                    
       
	
    
        if($from_date =='' || $to_date==''){
		$cont = "<font color=red>Select the period for your report!</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
		
	}
	
	else{     
	
	 $from_date = sprintf("%04d-%02d-%02d", $from_year, $from_month, $from_mday);
	$to_date = sprintf("%04d-%02d-%02d", $to_year, $to_month, $to_mday);
	$sth = mysql_query("select s.id as id, a.name as account_name, s.total_amount, s.date, s.transaction, s.bank_account, b.bank as bank_name from share_dividends s left join bank_account b on s.bank_account=b.id left join accounts a on b.account_id=a.id where s.date>='".$from_date."' and s.date <='".$to_date."'");
	
	if(mysql_num_rows($sth) > 0){
                    
        $content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                 <p><h5 class="semibold text-primary mt0 mb5">SHARING DONE</h5></p>
                                
                               </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';           
	
		$content .="<thead><th><b>Date</b></th><th><b>Amount</b></th><th><b>Action</b></td><th><b>Source Bank Account</b></th><th><b>Dividends</b></th><th><b>Edit</b></th></thead><tbody>";
		$i=0;
		while($row = mysql_fetch_array($sth)){
			//$color = ($i%2 == 0) ? "lightgrey" : "white";
			$action = ($row['action'] == 'post') ? "Posted to Members" : "Credited on Members' Shares";
			$bank_account = ($row['bank_account'] == 0) ? "Not Applicable" : $row['account_no'] ." - ".$row['bank_name']."  ".$row['account_name'];
			$content .= "<tr><td>".$row['date']."</td><td>".$row['total_amount']."</td><td>".$action."</td><td>".$bank_account."</td><td><a href='javascript:;' onclick=\"xajax_show_dividends('".$row['id']."', '".$from_date."','".$to_date."')\">Dividends</a></td><td><a href='javascript:;' onclick=\"xajax_delete_dividends('".$row['id']."', '".$from_date."','".$to_date."')\">Delete</a></td></tr>";
			$i++;
		}
		
	  $content .= "<tbody></table></div>";	
	  $resp->call("createTableJs");
	}
	else{
		$cont = "<font color=red>No dividends shared in the selected period!</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
	} 
	}  
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}
 
function show_dividends($share_dividend_id, $from_date,$to_date){
	$resp = new xajaxResponse();
	 list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp->assign("status", "innerHTML", "");
	$sth = mysql_query("select * from share_dividends where id=$share_dividend_id");
	$row = mysql_fetch_array($sth);
	$heading = ($row['action'] == 'post') ? "DIVIDENDS POSTED TO MEMBERS " : "DIVIDENDS CREDITED ON MEMBERS' SHARES ";
	$content = "<center><font color=#00008b size=3pt><b>".$heading." ON ".$row['date']."</b></font></center><form method=post><a href=# onclick=\"xajax_sharing_done('".$from_date."','".$to_date."')\"><img src='images/btn_back.jpg'></a><table  border='1' cellpadding='0' cellspacing='1' style='border-collapse: collapse' bordercolor='#111111' width='100%' id='AutoNumber2' align=center>";
	$sth=mysql_query("select m.mem_no, m.first_name, m.last_name, d.amount from dividends d join member m on d.mem_id=m.id where d.share_dividend_id=$share_dividend_id order by m.first_name, m.last_name, m.mem_no");
	$content .="<tr class='headings'><td><b>No</b></td><td><b>Name</b></td><td><b>Member No</b></td><td><b>Amount</b></td></tr>";
	$i=1;
	while($row = mysql_fetch_array($sth)){
		//$color = ($i%2 == 0) ? "lightgrey" : "white";
		$content .= "<tr><td>$i</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".$row['amount']."</td></tr>";
		$i++;
	}
	$content .= "</table>";
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function delete_dividends($share_dividend_id, $from_date,$to_date){
	$resp = new xajaxResponse();
	$resp->confirmCommands(1, "Do you really want to delete this sharing?");
	$resp->call('xajax_delete2_dividends', $share_dividend_id, $from_date,$to_date);
	return $resp;
}

function delete2_dividends($share_dividend_id, $from_date,$to_date){
	$resp = new xajaxResponse();
	start_trans();
	$former_res = mysql_query("select * from share_dividends where id=$share_dividend_id");
	$former = mysql_fetch_array($former_res);
	if($former['bank_account'] != 0){
		if(! mysql_query("update bank_account set account_balance=account_balance +".$former['total_amount']." where id=".$former['bank_account']."")){
			rollback();
			$resp->alert("ERROR: Bank Account balance could not be updated!");
			return $resp;
		}
	}
	if(! mysql_query("delete from dividends where share_dividend_id=$share_dividend_id")){
		rollback();
		$resp->alert("ERROR: Individual dividends could not be deleted");
		return $resp;
	}
	if(! mysql_query("delete from share_dividends where id=$share_dividend_id")){
		rollback();
		$resp->alert("ERROR: The sharing could not be deleted");
		return $resp;
	}
	commit();
	$resp->assign("status", "innerHTML", "<FONT COLOR=RED>Sharing of dividends deleted!</font>");
	$resp->call('xajax_sharing_done', $from_date,$to_date);
	return $resp;
}
function sharesLedgerForm()
{       $mems="";
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	$mem_res = @mysql_query("select id, first_name, last_name, mem_no from member order by first_name, last_name desc");
	while ($mem_row = @mysql_fetch_array($mem_res))
	{
		$mems .= "<option value='".$mem_row['id']."'>".$mem_row['first_name']."".$mem_row['last_name']." - ".$mem_row['mem_no']."</option>";
	}
	
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <p><h5 class="semibold text-primary mt0 mb5">SEARCH FOR SHARES LEDGER</h5></p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                   <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Select Member:</label>
                                            <div class="input-group">
                                            <select name="memid" id="memid" class="form-control">'.$mems.'</select>
                                            <span class="input-group-btn">
                                                <button class="btn btn-info" type="button"  onclick=\'xajax_sharesLedger(document.getElementById("memid").value, "mem_id"); return false;\'>Show Ledger</button>
                                            </span>
                                        </div></div>                                         
                                       <div class="col-sm-6">
                                        <label class="control-label">Enter Member No:</label>
                                        <div class="input-group">
                                            
                                            <input type=text name="mem_no" id="mem_no" class="form-control">
                                              <span class="input-group-btn">
                                                <button class="btn btn-info" type="button" onclick=\'xajax_sharesLedger(document.getElementById("mem_no").value, "mem_no"); return false;\'>Show Ledger</button>
                                            </span>
                                        </div></div>
                                    </div>
                                </div>';
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


function sharesLedger($mem_id, $type)
{
// Direct purchases, in-ward transfers and out-ward transfers
	$mems="";
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	
	$mems="<option value=''>Select Member</option>";
	$mem_res = @mysql_query("select id, first_name, last_name, mem_no from member order by first_name, last_name desc");
	while ($mem_row = @mysql_fetch_array($mem_res))
	{
		$mems .= "<option value='".$mem_row['id']."'>".$mem_row['first_name']."".$mem_row['last_name']." - ".$mem_row['mem_no']."</option>";
	}
	
	$content = '<div class="row">
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <p><h5 class="panel-title">SEARCH FOR SHARES LEDGER</h5></p>
                            </div>               
                            <div class="panel-body">
                            
                      <div class="form-group">
                                   <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label">Select Member:</label>
                                            <div class="input-group">
                                            <select name="memid" id="memid" class="form-control">'.$mems.'</select>
                                            <span class="input-group-btn">
                                                <button class="btn btn-info" type="button"  onclick=\'xajax_sharesLedger(document.getElementById("memid").value, "mem_id"); return false;\'>Show Ledger</button>
                                            </span>
                                        </div></div>                                         
                                       <div class="col-sm-6">
                                        <label class="control-label">Enter Member No:</label>
                                        <div class="input-group">
                                            
                                            <input type=text name="mem_no" id="mem_no" class="form-control">
                                              <span class="input-group-btn">
                                                <button class="btn btn-info" type="button" onclick=\'xajax_sharesLedger(document.getElementById("mem_no").value, "mem_no"); return false;\'>Show Ledger</button>
                                            </span>
                                        </div></div>
                                    </div>
                             </div>
                             </div></form></div></div>';
                                
        if($mem_id ==''){
	
		$cont="<font color=red>Please Select or Enter Member No.</font>";
		$resp->assign("status", "innerHTML", $cont);
	}
                                
	elseif($type == 'mem_no'){
		$mem_res = mysql_query("select * from member where mem_no='".$mem_id."'");
		if(mysql_numrows($mem_res) == 0){
			
			$cont="<font color=red>No member identified by this number.</font>";
			$resp->assign("status", "innerHTML", $cont);
		}
		$mem = mysql_fetch_array($mem_res);
		$mem_id = $mem['id'];
	}
	
	else{
	$direct = @mysql_query("select id, date, shares, value from shares where mem_id = $mem_id and receipt_no != '' order by date asc");
	$inward = @mysql_query("select id, date, shares, value from share_transfer where to_id = $mem_id order by date asc");
	$outward = @mysql_query("select id, date, shares, value from share_transfer where from_id = $mem_id order by date asc");
	$div_res = mysql_query("select d.id,  s.date, d.amount from dividends d join share_dividends s on d.share_dividend_id=s.id where d.mem_id=$mem_id and s.bank_account=0 order by s.date asc");
	$mem = @mysql_fetch_array(@mysql_query("select first_name, last_name from member where id = $mem_id"));
	$tot_mem_shares = 0; 
	$found_shares = 0;
	
	$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <p><h5 class="semibold text-primary mt0 mb5">SHARES LEDGER FOR '.strtoupper($mem['first_name']).' '. strtoupper($mem['last_name']).'</h5></p>                                
                            </div>';
		
		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
	
	$content .= "<thead><th>Date</th><th>Type of Transaction</th><th>No. of Shares</th><th>Value</th><th>Total Shares</th><th>Dividends</th><th>Balance</th><th>Action</th></thead></tbody>";
	$balance = 0;
	if (@mysql_num_rows($direct) > 0)
	{
		$found_shares += 1; $i = 0;
		while ($drow = @mysql_fetch_array($direct))
		{
			$balance += $drow['value'];
			$tot_mem_shares += $drow['shares'];
			//$color = ($i % 2 == 0) ? "lightgrey" : "white";
			$content .= "<tr>
				    <td>".$drow['date']."</td><td>Direct Purchase</td><td align='center'>".$drow['shares']."</td><td align='center'>".$drow['value']."</td><td align='center'>".$tot_mem_shares."</td><td>--</td><td>".number_format($balance, 2)."</td><td align='left'><a href='#' onclick=\"xajax_showEditShares('shares', '".$drow['id']."', 'direct', '".$mem_id."', '".$type."'); return false;\">Edit</a></td>
				    </tr>
				    ";
					$i++;
		}
	}
	if (@mysql_num_rows($inward) > 0)
	{
		$found_shares += 1;
		$i = 0;
		while ($inrow = @mysql_fetch_array($inward))
		{
			$balance += $inrow[value];
			$tot_mem_shares += $inrow[shares];
			//$color = ($i % 2 == 0) ? "lightgrey" : "white";
			$content .= "<tr>
				    <td>".$inrow['date']."</td><td>Inward transfer</td><td align='center'>".$inrow['shares']."</td><td align='center'>".number_format($inrow['value'])."</td><td align='center'>".$tot_mem_shares."</td><td>--</td><td>".number_format($balance,2)."</td><td align='left'><a href='#' onclick=\"xajax_showEditShares('share_transfer', '".$inrow['id']."', 'in', '".$mem_id."', '".$type."'); return false;\">Edit</a></td>
				    </tr>
				    ";
					$i++;
		}
	}
	if (@mysql_num_rows($outward) > 0)
	{
		$found_shares += 1;
		$i=0;
		while ($outrow = @mysql_fetch_array($outward))
		{
			$balance -= $outrow['value'];
			$tot_mem_shares -= $outrow['shares'];
			//$color = ($i % 2 == 0) ? "lightgrey" : "white";
			$content .= "<tr>
				    <td>".$outrow['date']."</td><td>Outward transfer</td><td align='center'>".$outrow['shares']."</td><td align='center'>".$outrow['value']."</td><td align='center'>".$tot_mem_shares."</td><td>--</td><td>".$balance."</td><td align='left'><a href='#' onclick=\"xajax_showEditShares('share_transfer', '".$outrow['id']."', 'out', '".$mem_id."', '".$type."'); return false;\">Edit</a></td>
				    </tr>
			            ";
					$i++;
		}
	}
	if (@mysql_num_rows($div_res) > 0)
	{
		$found_shares += 1;
		$i=0;
		while ($div = @mysql_fetch_array($div_res))
		{
			$balance += $div['amount'];
			//$tot_mem_shares -= intval($outrow[shares]);
			//$color = ($i % 2 == 0) ? "lightgrey" : "white";
			$content .= "<tr>
				    <td>".$div['date']."</td><td>Dividends</td><td align='center'>--</td><td align='center'>--</td><td align='center'>".$tot_mem_shares."</td><td>".$div['amount']."</td><td>".$balance."</td><td align='left'>--</td>
				    </tr>
			            ";
					$i++;
		}
	}
	$content .= "</tbody></table></div>";
	$resp->call("createTableJs");
	if ($found_shares < 1){
		$cont = "<font color='red'>No shares activity yet registered for ".$mem['first_name']."".$mem['last_name']."</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
		}
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function showEditShares($table, $id, $type, $mem_id, $category)
{       
        $members ='';
        $accts   ='';
        $content ='';
	$resp = new xajaxResponse();
	/*if($_SESSION['position'] <>'Manager'){
		$resp->alert("Access Denied! \nOnly the Manager can edit an item");
		return $resp;
	}
	$resp->assign("status", "innerHTML", "");*/
	$shares = @mysql_fetch_array(@mysql_query("select *, date_format(date, '%Y') as year, date_format(date, '%m') AS month, date_format(date, '%d') as mday from ".$table." where id = ".$id.""));
	$mem_res = @mysql_query("select id, mem_no, first_name, last_name from member order by mem_no asc");
	if ($mem_res)
		while ($mem_row = @mysql_fetch_array($mem_res))
		{
			$members .= "<option value='".$mem_row['id']."'>".$mem_row['mem_no']."-". $mem_row['first_name']."".$mem_row['last_name']."</option>";
		}
	$acc = @mysql_query("select ac.account_no as account_no, ac.name, ba.bank from accounts ac join bank_account ba on ba.account_id = ac.id where ba.id<>'".$id."'");
	if ($acc)
		while ($accrow = @mysql_fetch_array($acc))
		{
			$accts .= "<option value='".$accrow['account_no']."'>".$accrow['account_no']." - ".$accrow['bank']."".$accrow['name']."</option>";
		}
	if (strtolower($table) == 'shares' && isset($shares))
	{
		$mrow = @mysql_fetch_array(@mysql_query("select mode from shares where id = $id"));
		if ($mrow['mode'] == 'cash')
			$mem_res = mysql_query("select m.*, a.account_no, b.id as bank_account, b.bank as bank, a.name as name, date_format(s.date, '%Y') as year, date_format(s.date, '%m') as month, date_format(s.date, '%d') as mday, s.mode from shares s join member m on s.mem_id=m.id join bank_account b on s.bank_account=b.id join accounts a on b.account_id=a.id where s.id='".$id."'");
		else
			$mem_res = mysql_query("select m.*, date_format(s.date, '%Y') as year, date_format(s.date, '%m') as month, date_format(s.date, '%d') as mday, s.mode from shares s join member m on s.mem_id=m.id where s.id='".$id."'");
		$mem = mysql_fetch_array($mem_res);
		

          
$content ="";
	$content .="<form method='post' class='panel form-horizontal form-bordered'>";
$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h4 class="semibold text-primary mt0 mb5">EDIT SHARES OF '.strtoupper($mem['first_name']).' '.strtoupper($mem['last_name']).'('.$mem['mem_no'].')'.'</h4>
                                               		 <p class="text-default nm"></p>
                                           	 </div>
                                        </div><input type=hidden id="memid" name="memid"  value="'.$mem['id'].'">';
                                        
	 
	$content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">No. of Shares:</label>
                                            <div class="col-sm-6">
                                            <input type=numeric value="'.$shares['shares'].'" id="num_shares" name="num_shares" class="form-control">
                                            
                                 <input name="bank_acc_id" id="bank_acc_id" type="hidden" value="'.$mem['bank_account'].'">     
                                  </div>
                                            </div>';      
                                                                                      
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Receipt No:</label>
                                            <div class="col-sm-6">
                                            <input type=text value="'.$shares['receipt_no'].'" id="rcpt_no" name="rcpt_no" onblur=\'xajax_val_receipt(getElementById("rcpt_no").value, "'.$id.'")\'/ class="form-control">
                                            </div></div>';
                                                                                       
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Date of Purchase</label>
                                            <div class="col-sm-6">
                                           <span>'.month_array("", $mem['year'], $mem['month']).mday("", $mem['mday']).'</span>
                                            </div></div>';
                                            $content .= "<div class='panel-footer'><button type='reset' class='btn btn-default' onclick=\"xajax_sharesLedger('".$mem_id."', '".$category."')\">Back</button>
                                            
                                            <button type='reset' class='btn btn-default' onclick=\"xajax_delete_shares('".$id."', getElementById('memid').value, getElementById('bank_acc_id').value); return false;\">Delete</button>
                                            <button type='button' class='btn btn-primary' onclick=\"xajax_update_shares('".$id."', getElementById('memid').value, getElementById('num_shares').value, getElementById('bank_acc_id').value, getElementById('rcpt_no').value, getElementById('date').value); return false;\">Save</button>";
                                            $content .= '</div>';
	}
	elseif (strtolower($table) == 'share_transfer')
	{
		$content .= "<center><font color=#00008b size=3pt>EDIT SHARE TRANSFER</font></center><br>";
		$content .= "<form method=post><table height=100 border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='50%' id='AutoNumber2' align=center>";
		$seller_res = mysql_query("select * from member where id='".$shares['from_id']."'");
		$seller = mysql_fetch_array($seller_res);
		$buy_res = mysql_query("select * from member where id='".$shares['to_id']."'");
		$buy = mysql_fetch_array($buy_res);
			   $content .="<tr><td valign=top>Selling Member:</td><td><select id='smemid' name='smemid'><option value='".$seller['id']."'>".$seller['mem_no']."-".$seller['first_name']."".$seller['last_name']."".$members."</select></td></tr>
			    <tr><td valign=top>Buying Member:</td><td><select id='bmemid' name='bmemid'><option value='".$buy['id']."'>".$buy['mem_no']."-".$buy['first_name']."".$buy['last_name']."".$members."</select></td></tr>
			    <tr><td valign=top>No. of Shares:</td><td><input type=numeric value='".$shares['shares']."' id='num_shares' name='num_shares' /></td></tr>
			    </tr>
				<tr><td valign=top>Date of Transfer:</td><td>".month_array('', $shares['year'], $shares['month']). mday('', $shares['mday'])."</td></tr>
			    <tr>
			    	<td></td><td valign=top><input type=button value='Delete' onclick=\"xajax_delete_transfer('".$id."', getElementById('smemid').value, getElementById('bmemid').value); return false;\"><input type=button value='Update' onclick=\"xajax_update_transfer('".$id."', getElementById('smemid').value, getElementById('bmemid').value, getElementById('num_shares').value, getElementById('date').value); return false;\"></td>
			    </tr>
			    ";
		$content .= "</table></form>";
	}

	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function delete_shares($share_id, $mem_id, $bank_acc_id){
	$resp = new xajaxResponse();
	$branch_res = mysql_query("select * from branch");
	$branch = mysql_fetch_array($branch_res);

	$shares_res = mysql_query("select sum(value) as value from shares where mem_id='".$mem_id."' and id<>'".$share_id."'");
	$shares = mysql_fetch_array($shares_res);
	$shares_amt = ($shares['value'] != NULL) ? $shares['value'] : 0;
	$buy_res = mysql_query("select sum(shares) as shares from share_transfer where to_id='".$mem_id."'");
	$buy = mysql_fetch_array($buy_res);
	$buy_shares = ($buy['shares'] != NULL) ? $buy['shares'] : 0;
	$buy_amt = $buy_shares * $branch['share_value'];

	$sell_res = mysql_query("select sum(shares) as shares from share_transfer where from_id='".$mem_id."'");
	$sell = mysql_fetch_array($sell_res);
	$sell_shares = ($sell['shares'] != NULL) ? $sell['shares'] : 0;
	$sell_amt = $sell['shares'] * $branch['share_value'];

	//COMPULSORY SHARES
	$pledged_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.name='Compulsory Shares' and m.mem_id='".$mem_id."'");
	$pledged = mysql_fetch_array($pledged_res);
	$pledged_amt = ($pledged['amount'] != NULL) ? $pledged['amount'] : 0;
	$available_amt = $shares_amt + $buy_amt - $sell_amt;

	if($available_amt < $pledged_amt){
		$resp->alert("Share not deleted! These shares are held as \nguarantee for a loan taken by the member");
		return $resp;
	}
	$resp->confirmCommands(1, "Do you really want to delete?");
	$resp->call('xajax_delete2_shares', $share_id, $mem_id, $bank_acc_id, $available_amt, $branch['min_shares'], $branch['share_value']);
	return $resp;
}

function delete2_shares($share_id, $mem_id, $bank_acc_id, $available_amt, $min_shares, $share_value){
	$resp = new xajaxResponse();
	$former_res = mysql_query("select * from shares where id='".$share_id."'");
	$former = mysql_fetch_array($former_res);

	start_trans();
	//UPDATE THE ACCOUNT BALANCE 
	if(! mysql_query("update bank_account set account_balance=account_balance -".$former['value']." where id='".$bank_acc_id."'")){
		$resp->alert("Shares not deleted! \nCould not update the account balance");
		rollback();
		return $resp;
	}
	//UPDATE MEMBERSHIP
	if($available_amt < ($min_shares * $share_value)){
		if(! mysql_query("update member set status='not paid' where id='".$mem_id."'")){
			$resp->alert("Shares not deleted! \n Could not update the member details!");
			rollback();
			return $resp;
		}
	}
	//DELETE THE SHARES
	if(! mysql_query("delete from shares where id='".$share_id."'")){
		$resp->alert("Shares not deleted! \n Could not update the member details!");
		rollback();
		return $resp;
	}
	$action = "delete from shares where id='".$share_id."'";
	mysql_query("insert into logs set user_id='".$_SESSION['user_id']."', time=NOW(),  action='".$_SESSION['name'].":  ".mysql_escape_string($action)."'");
	$resp->assign("status", "innerHTML", "<FONT COLOR=red>Shares successfully deleted!</font>");
	commit();
	return $resp;
}

function update_shares($share_id, $mem_id, $num_shares, $bank_acc_id, $receipt_no, $date){
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	list($year,$month,$mday) = explode('-', $date);
	$rec_res = mysql_query("select receipt_no from collected where receipt_no ='".$receipt_no."' union select receipt_no from payment where receipt_no ='".$receipt_no."' union select receipt_no from other_income where receipt_no ='".$receipt_no."' union select receipt_no from deposit where receipt_no ='".$receipt_no."' union select receipt_no from shares where receipt_no='".$receipt_no."' and id<>'".$share_id."' union select receipt_no from sold_asset where receipt_no='".$receipt_no."' union select receipt_no from sold_invest where receipt_no='".$receipt_no."'");
	
	if($mem_id=='' || $num_shares=='' || $receipt_no=='' || $bank_acc_id=='')
		$resp->alert("Purchase not updated! \nYou may not leave any field blank!".$bank_acc_id);
	elseif(!$calc->isValidDate($mday, $month, $year))
		$resp->alert("Purchase rejected! Please enter valid date");
	elseif($calc->isFutureDate($mday, $month, $year))
		$resp->alert("Purchase rejected! You have entered a future date");
	elseif(preg_match("/\D/", $num_shares))
		$resp->alert("Purchase rejected, \nplease specify the corrent number of shares");
	elseif(@ mysql_numrows($rec_res) >0)
		$resp->alert("Purchase rejected! The receiptNo already exists");	
	else{
		$resp->confirmCommands(1, "Do you really want to update?");
		$resp->call('xajax_update2_shares', $share_id, $mem_id, $num_shares, $bank_acc_id, $receipt_no, $date);
		
	}
	//$resp->assign("status", "innerHTML", $content);
	$resp->call('genSharesReport', $date);
	return $resp;
}

function update2_shares($share_id, $mem_id, $num_shares, $bank_act_id, $receipt_no, $date){
        $plegdes_amt ="";
        $former_val ="";
        //list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	start_trans();
	$row = @mysql_fetch_array(@mysql_query("select share_value as shareval, max_share_percent as max_percent, min_shares from branch"));
	$totalval = $row['shareval'] * $num_shares;
	//$date = sprintf("%d-%02d-%02d", $date);
	$share_purchase = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_pur from shares where mem_id = ".$mem_id." and id<>'".$share_id."'"));
	$share_sale = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_sale from share_transfer where from_id = ".$mem_id.""));
	$tot_shares = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_share, count(distinct mem_id) as num_members from shares"));
	$buy_res = mysql_query("select sum(shares) as shares from share_transfer where to_id='".$mem_id."'");
	$buy = mysql_fetch_array($buy_res);
	$buy_amt = ($buy['shares'] != NULL) ? $buy['shares'] : 0;
		
	$tot_mem_shares = $share_purchase['tot_pur'] + $buy_amt + $num_shares - $share_sale['tot_sale'];

	//COMPULSORY SHARES
	$pledged_res = mysql_query("select sum(d.amount) as amount from deposit d join mem_accounts m on d.memaccount_id=m.id join savings_product p on m.saveproduct_id=p.id join accounts a on p.account_id=a.id where a.name='Compulsory Shares' and m.mem_id='".$mem_id."'");
	$pledged = mysql_fetch_array($pledged_res);
	$pledged_amt = ($pledged['amount'] != NULL) ? $pledged['amount'] : 0;
	if($plegdes_amt > ($tot_mem_shares * $row['shareval'])){
		$resp->alert("Update not done! \n Shares held as guarantee for a loan taken \n by the member would be encroached on");
		return $resp;
	}
	//$mem_percentage = (intval($tot_mem_shares) / intval($tot_shares[tot_share])) * 100;
	$increment = $totalval - $former_val;
	if ($tot_shares['num_members'] > 4) // min_members
	{
		$former_res = mysql_query("select shares from shares where id='".$share_id."'");
		$former = mysql_fetch_array($former_res);
		$former_val = $former['shares'] * $row['shareval'];
		$mem_percentage = ($tot_mem_shares / ($tot_shares[tot_share] + $num_shares - $former['shares'])) * 100;
		if ($mem_percentage < $row['max_percent'])
		{		
			if(mysql_query("update shares set shares='".$num_shares."', value='".$totalval."', date='".$date."', receipt_no='".$receipt_no."' where id='".$share_id."'")){
				/*
				Constraints: 1. Upgrade member status to paid if total_num_shares > min_shares
			 	2. Check that member doesn't exceed max_share_percent for branch if > 4 members
				*/
				if ($tot_mem_shares >= $row['min_shares'])
				{
					if(! mysql_query("update member set status = 'Paid' where id = ".$mem_id."")){
						$resp->alert("Transaction not updated! \n Could not update the member's status");
						rollback();
						return $resp;
					}
				}
				if(!mysql_query("UPDATE bank_account set account_balance = (account_balance + ".$increment.") where id = $bank_act_id")){
					$resp->alert("Transaction not registered! \n Could not update the bank account balance");
					rollback();
					return $resp;
				}
				$action = "update shares set shares='".$num_shares."', value='".$totalval."', date='".$date."', receipt_no='".$receipt_no."' where id='".$share_id."'";
				mysql_query("insert into logs set user_id='".$_SESSION['user_id']."', time=NOW(),  action='".$_SESSION['name'].":  ".mysql_escape_string($action)."'");
				$resp->assign("status", "innerHTML", "<br><font color=red>Shares purchase registered successfully.</font><br>");
				commit();
			}else{
				$resp->assign("status", "innerHTML", "Transaction not registered! \n Could not update the purchase!");
				rollback();
				return $resp;
			}
		}else 
			$resp->alert("You cannot purchase over ".$row['max_percent']."% of available shares.</font><br>");
			return $resp;
	}else{	
		if (mysql_query("update shares set shares='".$num_shares."', receipt_no='".$receipt_no."', value='".$totalval."', date='".$date."' where id='".$share_id."'")){
			mysql_query("update shares set shares='".$num_shares."', receipt_no='".$receipt_no."', value='".$totalval."' where id='".$share_id."'");
			if ($tot_mem_shares >= $row['min_shares']){
				if(!mysql_query("update member set status = 'Paid' where id = ".$mem_id."")){
					$resp->alert("Transaction not registered! \n Could not update the member's status");
					rollback();
					return $resp;	
				}elseif(! mysql_query("UPDATE bank_account set account_balance = (account_balance + ".$increment.") where id = '".$bank_act_id."'")){
					$resp->alert("Transaction not registered! \n Could not update the bank account balance".mysql_error());
					rollback();
					return $resp;
				}
			}
			$action = "update shares set shares='".$num_shares."', receipt_no='".$receipt_no."', value='".$totalval."', date='".$date."' where id='".$share_id."'";
			/*mysql_query("insert into logs set user_id='".$_SESSION['user_id']."', time=NOW(),  action='".$_SESSION['name'].":  ".mysql_escape_string($action)."'");*/
			$resp->assign("status", "innerHTML", "<div><font color=red>Shares Purchase Registered Successfully.</font></div>");
			commit();
			return $resp;
		}else{
			$resp->alert("Transaction not updated, could not update shares");
			rollback();
			return $resp;	
		}
	}
	return $resp;
}

function shareDivForm($action="")
{   $content = "";
      $accts ="";
	$resp = new xajaxResponse();
	
	if($action == 'post'){
		$acc = @mysql_query("select ac.account_no, ac.name, ba.id as bank_id, ba.bank from accounts ac join bank_account ba on ac.id = ba.account_id where ac.account_no like '1212%'");
		$accts .= "<option value=''>&nbsp;</option>";
		while ($acc_row = @mysql_fetch_array($acc))
		{
			$accts = "<option value='".$acc_row['bank_id']."'>".$acc_row['account_no']." - ".$acc_row['bank']."".$acc_row['name']."</option>";
		}
		$action_display = "Post Dividends To Members";
	}else{
		$accts = "<option value='0'>Not Applicable</option>";
		$action_display = "Credit Members' Shares";
	}
	/*$content .= "
		   <center><font color=#00008b size=3pt><B>SHARE DIVIDENDS</B></FONT></CENTER>
		    <form method=post>
		   <table height=100 border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse' bordercolor='#111111' width='50%' id='AutoNumber2' align=center>
				<tr bgcolor=lightgrey>
			<td>Action: </td><td><select name='action' id='action' onchange=\"xajax_shareDivForm(getElementById('action').value);\"><option value='".$action."'>".$action_display."</option><option value='credit'>Credit Member's Shares</option><option value='post'>Post Dividends to Members</option></select></td>
		      </tr>
		      <tr bgcolor=white>
			<td>Source Bank Account: </td><td><select name='bank_acct_id' id='bank_acct_id'>$accts</select></td>
		      </tr>
		      <tr bgcolor=lightgrey>
			<td>Enter Amount to be shared: </td> <td><input type=numeric id='share_amt' name='share_amt'></td>
              </tr>
				<tr bgcolor=lightgrey><td></td><td><input type=button value='Share' onclick=\"xajax_shareDividends(getElementById('action').value, getElementById('share_amt').value, getElementById('bank_acct_id').value, 'enter'); return false;\"></td></tr>
		    </table>
		    </form>
		    ";*/
     $content .="<form method='post' class='panel form-horizontal form-bordered'>";
$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h4 class="panel-title">SHARE DIVIDENDS</h4>
                                               		 <p class="text-default nm"></p>
                                           	 </div>
                                        </div><div id="responsediv" class="alert alert-dismissable alert-danger"></div>';
                                        
	 
	$content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Action:</label>
                                            <div class="col-sm-6">
                                            <select name="action" id="action" class="form-control" onchange=\'xajax_shareDivForm(getElementById("action").value);\'><option value="'.$action.'">'.$action_display.'</option><option value="credit">Credit Member\'s Shares</option><option value="post">Post Dividends to Members</option></select>
                                            </div>
                                            </div>';                                           
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Source Bank Account:</label>
                                            <div class="col-sm-6">
                                            <select name="bank_acct_id" id="bank_acct_id" class="form-control">'.$accts.'</select>
                                            </div></div>';
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Enter Amount to be shared:</label>
                                            <div class="col-sm-6">
                                            <input type="numeric" id="share_amt" name="share_amt" class="form-control">
                                            </div></div>';                                                         
                                            $content .= '<div class="panel-footer"><button type="reset" class="btn btn-default" onclick=\"xajax_shareTransForm()\">Reset</button>
                                            <button type=button class="btn btn-primary" onclick=\'xajax_shareDividends(getElementById("action").value, getElementById("share_amt").value, getElementById("bank_acct_id").value, "enter"); return false;\'></td>Share</button>';
                                            $content .= '</div></div></form>';
          
	$resp->call('xajax_shareDividends', $action, '', '', '');
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


function shareDividends($dividends_action, $share_amt, $bank_acct_id, $action)
{       $start_date ="";
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	if ($share_amt == '' && $bank_acct_id != '')
	{
		$resp->alert("Please enter an amount to be shared.");
		return $resp;
	}
	$query = mysql_fetch_array(@mysql_query("select max(date) as date from share_dividends"));
	if (!isset($query['date'] )){
		$start_date = date('Y-m-d', (mktime(0, 0, 0, date('m'), date('d'), date('Y')) - (366*24*60*60))); 
	}else{
		$start_date = $query['date'];
	}
	// build income
	$mc_row = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_mcharge from monthly_charge where date > '".$start_date."'"));
	$oi_row = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_other_income from other_income where date > '".$start_date."'"));
	$pen_row = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_penalty from penalty where date > '".$start_date."'"));
	$re_row = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_recovered from recovered where date > '".$start_date."'"));
	$dep_row = @mysql_fetch_array(@mysql_query("select sum(percent_value + flat_value) as tot_deposit from deposit where date > '".$start_date."'"));
	$wit_row = @mysql_fetch_array(@mysql_query("select sum(percent_value + flat_value) as tot_withdrawal from withdrawal where date > '".$start_date."'"));
	$pay_row = @mysql_fetch_array(@mysql_query("select sum(int_amt) as tot_int_amt from payment where date > '".$start_date."'"));
	$col_row = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_collected from collected where date > '".$start_date."'"));

	// build expenses
	$exp_row = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_exp from expense where date > '".$start_date."'"));
	//LOSS FROM SALE OF FIXED ASSETS
	$fx_row = @mysql_fetch_array(@mysql_query("select sum(f.initial_value - s.amount) as sale_loss from fixed_asset f join asset_sold s on f.id=s.asset_id where s.date > '".$start_date."'"));
	//LOSS FROM SALE OF INVESTMENTS
	$invest = mysql_fetch_array(mysql_query("select sum(i.amount - s.amount) as amount from investments i join sold_invest s on i.id=s.investment_id where s.date > '".$start_date."'"));
	//PAYABLES PAID
	$paid = mysql_fetch_array(mysql_query("select sum(amount) as amount from payable_paid where date >'".$start_date."'"));
	//ACCUMULATED PAYABLES NOT YET PAID
	$payable = @mysql_fetch_array(@mysql_query("select sum(amount) as amount from payable where maturity_date <=NOW()"));
	$cleared = mysql_fetch_array(mysql_query("select sum(paid.amount) as amount from payable_paid paid join payable p on paid.payable_id=p.id where p.maturity_date <= NOW()"));
	$payable_amt = $payable['amount'] - $cleared['amount'];
	//INTEREST AWARDED TO SAVINGS ACCOUNTS
	$sav_row = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_save_int from save_interest where date > '".$start_date."'"));
	$wrt_row = @mysql_fetch_array(@mysql_query("select sum(amount) as tot_wrt_off from written_off where date > '".$start_date."'"));

	$grand_income = $col_row['tot_collected'] + $pay_row['tot_int_amt'] + $wit_row['tot_withdrawal'] + $dep_row['tot_deposit'] + $re_row['tot_recovered'] + $pen_row['tot_penalty'] + $oi_row['tot_other_income'] + $mc_row['tot_mcharge'];
	
	$grand_expense = $exp_row['tot_exp'] + $fx_row['sale_loss'] +  $sav_row['tot_save_int'] + $wrt_row['tot_wrt_off'] + $invest['sale_loss'] + $paid['amount'];
	
	$profit = $grand_income - $grand_expense;
	if ($share_amt == '' || $bank_acct_id == ''){
		$content = "<b>Max Profit to share: $profit</b><BR><b>Debt: $payable_amt</b>";
		$resp->assign("status", "innerHTML", $content);
		if($action == 'enter'){
			$resp->alert("Sharing not done! \n You may not leave any field blank");
		}
		return $resp;
	}
	if ($share_amt > $profit)
	{
		$resp->alert("Sharing not done! \nThe amount to be shared exceeds the available profit.");
		return $resp;
	}
	else
	{
		$total_shares = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_share from shares"));	
		$bank_row = @mysql_fetch_array(@mysql_query("select account_balance, min_balance from bank_account where id = $bank_acct_id"));
		if ($total_shares['tot_share'] < 1)
		{
			$resp->alert("There are no shareholders registered yet.");
			return $resp;
		}
		elseif (intval($calc->dateDiff(date('d'), date('m'), date('Y'), date('d', strtotime($start_date)), date('m', strtotime($start_date)), date('Y', strtotime($start_date)))) < 365)
		{
			$resp->alert("Cannot share dividends: Less than 1 year since last sharing.");
			return $resp;
		}
		else
		{
			$shares_paid = 0;
			start_trans();
			if(! mysql_query("INSERT into share_dividends (total_amount, date, bank_account, action) VALUES ($share_amt, '".date('Y-m-d')."', $bank_acct_id, '".$dividends_action."')")){
				$resp->alert("Sharing not done! \n Could not register the sharing");
				rollback();
				return $resp;
			}
			
			$share_div_id = mysql_insert_id();
			$mem_res = @mysql_query("select id, mem_no from member where id in (select mem_id from shares) order by id asc");
			while ($mem_row = @mysql_fetch_array($mem_res))
			{
				$mem_share_purchase = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_mem_pur from shares where mem_id = $mem_row[id]"));
				$mem_share_sale = @mysql_fetch_array(@mysql_query("select sum(shares) as tot_mem_sale from share_transfer where from_id = $mem_row[id]"));
				$buy = mysql_fetch_array(mysql_query("select sum(shares) as shares from share_transfer where to_id='".$mem_row['id']."'"));
				$mem_div = (($mem_share_purchase['tot_mem_pur'] + $buy['shares'] - $mem_share_sale['tot_mem_sale']) / $total_shares['tot_share']) * $share_amt;
				//$mem_div = ($mem_percentage * $share_amt) / 100;
				//$date = date('Y-m-d');
				if(! mysql_query("INSERT into dividends (mem_id, amount, share_dividend_id) VALUES ($mem_row[id], $mem_div, $share_div_id)")){
					$resp->alert("Sharing failed!\n Could not register member's dividends");
					rollback();
					return $resp;
				}
				$shares_paid += 1;
			}
			if($dividends_action == 'post'){
				if(! mysql_query("update bank_account set account_balance=account_balance -".$share_amt." where id='".$bank_acct_id."'")){
					$resp->alert("Sharing failed!\n Could not update bank account balance");
					rollback();
					return $resp;
				}
			}
			commit();
			$content .= "<font color=green>Dividends have been paid to $shares_paid shareholders. Start_date: $start_date<br></font>";
		}

	}
	$resp->assign("status", "innerHTML", $content);
	return $resp;

}

?>
