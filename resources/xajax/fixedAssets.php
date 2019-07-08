<?php

$xajax->registerFunction("addFixedAssetForm");
$xajax->registerFunction("addFixedAsset");
$xajax->registerFunction("listFixedAssets");
$xajax->registerFunction("editFixedAssetForm");
$xajax->registerFunction("updateFixedAsset");
$xajax->registerFunction("updateFixedAsset2");
$xajax->registerFunction("deleteFixedAsset2");
$xajax->registerFunction("deleteFixedAsset");
$xajax->registerFunction("saleFixedAssetForm");
$xajax->registerFunction("saleFixedAsset");
$xajax->registerFunction("listSoldAssets");
$xajax->registerFunction("editSoldAssetForm");
$xajax->registerFunction("updateSoldAsset2");
$xajax->registerFunction("updateSoldAsset");
$xajax->registerFunction("deleteSoldAsset2");
$xajax->registerFunction("deleteSoldAsset");
$xajax->registerFunction("purchase_date");
$xajax->registerFunction("show_dep");
$xajax->registerFunction("insert_dep");
$xajax->registerFunction("schedule");


function purchase_date($inv_id, $table){
	$resp = new xajaxResponse();
	$check = mysql_fetch_array(mysql_query("select * from ".$table." where id='".$inv_id."'"));
	$resp->assign("purchase_date", "innerHTML", "Purchase Date: ". $check['date']);
	return $resp;
}

function addFixedAssetForm()
{       $fixed_acc='';
        $accts='';
        $content ='';
	$resp = new xajaxResponse();
	//$resp->assign("status", "innerHTML", "");
	//$fix = @mysql_query("select account_id, id from fixed_asset order by id");
	$fAssets = @mysql_query("select id, account_no, name from accounts where (account_no >=1211 and account_no <=1219) or (account_no >=121101 and account_no <= 121199) or (account_no >=121201 and account_no <= 121299) or (account_no >=121301 and account_no <= 121399) or (account_no >=121401 and account_no <= 121499) or (account_no >=121301 and account_no <= 121399) or (account_no >=121401 and account_no <= 121499) or (account_no >=121501 and account_no <= 121599) or (account_no >=121601 and account_no <= 121699) or (account_no >=121701 and account_no <= 121799) or (account_no >=121801 and account_no <= 121899) or (account_no >=121901 and account_no <= 121999) order by account_no");
	if(mysql_num_rows($fAssets) > 0){
	while ($level1row = @mysql_fetch_array($fAssets))
	{
			
			$fixed_acc .= "<option value='".$level1row['id']."'>".$level1row['account_no']." -".$level1row['name']."</option>";
	} 
	}		$acc_res = @mysql_query("select ac.name, ac.account_no as account_no, ba.id as bank_acct_id, ba.bank as bank_name from accounts ac join bank_account ba on ac.id = ba.account_id ");	
	/*else
		$acc_res = @mysql_query("select ac.name, ac.account_no as account_no, ba.id as bank_acct_id, ba.bank as bank_name from accounts ac join bank_account ba on ac.id = ba.account_id where ba.id in (select bank_account_id from user_account where user_id =".$_SESSION['user_id'].")");*/		

	$accts .= "<option value=''>&nbsp;</option>";
	if (@mysql_num_rows($acc_res) > 0)
		while ($acc_row = @mysql_fetch_array($acc_res))
		{
			$accts .= "<option value='".$acc_row['bank_acct_id']."'>".$acc_row['account_no']." -".$acc_row['name']."</option>";
		}
		
		
	$content .="<div class='col-md-12'><form method='post' class='panel form-horizontal panel-default'>";
$content .= '
  			  		<div class="panel-heading">
  			  		<h4>REGISTER FIXED ASSET</h4>
                                           	 </div>
                                           	  <div class="panel-body"><div class="col-md-6">
                                       ';
                                            $content .= '<div class="form-group">
                                           
                                            <label class="col-sm-6 control-label">Select Branch:</label>
                                            <div class="col-sm-8">
                                         '.branch_rep($branch_id).'
                                            </div></div>';                             	                                    
                                            $content .= '<div class="form-group">
                                           
                                            <label class="col-sm-6 control-label">Select Account:</label>
                                            <div class="col-sm-8">
                                          <select id="acc_id" name="acc_id" class="form-control">'.$fixed_acc.'</select>
                                            </div></div>';                                         
                                             $content .= '<div class="form-group">
                                             
                                            <label class="col-sm-6 control-label">Select Bank Account:</label>
                                            <div class="col-sm-8">
                                           <select name="bank_acct_id" id="bank_acct_id" class="form-control">'.$accts.'</select>
                                            </div></div>';                                          
                                                                                                                                                      
                         
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-6 control-label">Initial Value:</label>
                                            <div class="col-sm-8">
                                          <input onkeyup="format_as_number(this.id)" type="numeric" name="initval" id="initval" class="form-control">
                                            </div></div>';
                                            
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-6 control-label">Rate of depreciation:</label>
                                            <div class="col-sm-8">
                                          <input type="numeric" name="deprate" id="deprate" class="form-control">
                                            </div></div>'; 
                                                                                                                                                                                                                   
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-6 control-label">Purchase Date:</label>
                                            <div class="col-sm-8">
                                            <input type="text" class="form-control" id="date" name="date" placeholder="date" />
                                            </div></div>
                                             </div>  
                                           	 <div class="col-md-6">
<div class="form-group">
                                            <label class="col-sm-6 control-label">Asset Reference:</label>
                                            <div class="col-sm-8">
                                          <input type="text" name="ref" id="ref" class="form-control">
                                            </div></div>
                                            <div class="form-group">
                                            <label class="col-sm-6 control-label">Asset Description:</label>
                                            <div class="col-sm-8">
                                          <input type="text" name="descp" id="descp" class="form-control">
                                            </div></div>
                                            <div class="form-group">
                                            <label class="col-sm-6 control-label">Serial Number:</label>
                                            <div class="col-sm-8">
                                          <input type="text" name="serialnum" id="serialnum" class="form-control">
                                            </div></div>
                                            <div class="form-group">
                                            <label class="col-sm-6 control-label">Asset group or Category:</label>
                                            <div class="col-sm-8">
                                          <input type="text" name="asset_grp" id="asset_grp" class="form-control">
                                            </div></div>
                                            <div class="form-group">
                                            <label class="col-sm-6 control-label">Asset Department:</label>
                                            <div class="col-sm-8">
                                          <input type="text" name="dept" id="dept" class="form-control">
                                            </div></div>

                                           	 </div></div>';                                           
                                             
                                            $content .= "<div class='panel-footer'><button type='reset' class='btn btn-default' onclick=\"xajax_addFixedAssetForm()\">Reset</button>
                                            <button type='button' class='btn btn-primary' onclick=\"xajax_addFixedAsset(document.getElementById('acc_id').value, document.getElementById('initval').value, document.getElementById('deprate').value, document.getElementById('bank_acct_id').value, document.getElementById('date').value,document.getElementById('branch_id').value,document.getElementById('ref').value,document.getElementById('descp').value,document.getElementById('serialnum').value,document.getElementById('asset_grp').value,document.getElementById('dept').value); return false;\">Save</button>";
                                             $content .= '</div></form></div>';
                                              $resp->call("createDate","date");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function addFixedAsset($acc_id, $initval, $deprate, $bank_acct_id, $date,$branch_id,$ref,$descp,$serialnum,$asset_grp,$dept)
{     
         list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$initval=str_ireplace(",","",$initval);
	//$date = sprintf("%d-%02d-%02d", $date);

	 $yeardays = ($year % 4 == 0 && ($year % 100 != 0 || $year % 400 == 0)) ? 366 : 365;
	$nxt_dep_date = date('Y-m-d', (mktime(0, 0, 0, $month, $mday, $year) + (intval($yeardays)*24*60*60/12)));
	if ($acc_id == '' || $initval == '' || $deprate == '' || $bank_acct_id == '')
	{
		$resp->alert('Please do not leave any field blank.');
	}
	elseif (!$calc->isValidDate($mday, $month, $year))
	{
		$resp->alert('Invalid Date. Most likely wrong date for Month: Feb.');
	}
	elseif ($calc->isFutureDate($mday, $month, $year))
	{
		$resp->alert('Purchase Date can not be a future date.');
	}
	else
	{
		
		//test for sufficient funds
		/*$sth = mysql_query("select account_balance - $initval as account_balance, min_balance from bank_account where id=$bank_acct_id");
		$row = mysql_fetch_array($sth);
		if($row['account_balance'] < 0){
			$resp->alert("Fixed Asset not registered! \n No sufficient funds on Account");
			return $resp;
		}*/
		/*
		if($row['account_balance'] < $row['min_balance']){
			$resp->alert("Fixed Asset not registered! \n Account Balance would go below minimum");
			return $resp;
		}
		*/
		$date = $date." ".date("h:i:s",time());
		start_trans();
		$res = @mysql_query("insert into fixed_asset (account_id, initial_value, dep_percent, bank_account, date, next_dep_date,branch_id,assetref,assetdesc,serialnumber,assetgrp,assetdept) values ($acc_id, $initval, $deprate, $bank_acct_id, '".$date."', '".$nxt_dep_date."','".$branch_id."','".$ref."','".$descp."','".$serialnum."','".$asset_grp."','".$dept."')");
		if (@mysql_affected_rows() > 0)
		{
			/////////////////////
			$accno = mysql_fetch_assoc(mysql_query("select account_no, name from accounts where id='".$acc_id."'"));
			$action = "insert into fixed_asset (account_id, initial_value, dep_percent, bank_account, date, next_dep_date) values ($acc_id, $initval, $deprate, $bank_acct_id, '".$date."', '".$nxt_dep_date."')";
			$msg = "Registered a fixed asset of: ".$initval." on ac/no: ".$accno['account_no']." - ".$accno['name'];
			log_action($_SESSION['user_id'],$action,$msg);
			////////////////////
			if(mysql_query("update bank_account set account_balance = account_balance-$initval where id = $bank_acct_id")){
				commit();
				$content .= "<font color=blue>Fixed Asset registered successfully.</font><br>";
			}else{
				$resp->alert("Error: fixed asset not registered");
				rollback();
			}
		}
		else
			$resp->alert("Error: fixed asset not registered".mysql_error());
	}
	$resp->assign("status", "innerHTML", $content);
	return $resp;
}


function listFixedAssets($type,$from_date,$to_date, $branch_id)
{       
        list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
        
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	$branch = ($branch_id=='all'||$branch_id=='')?NULL:"and fx.branch_id=".$branch_id;
	//$resp->alert($from_date."-".$to_date);
	//return $resp;
	$content = '
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <p><h5>LIST OF FIXED ASSETS</h5></p>
                                         
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    
                                        <div class="col-sm-3">
                                            <label class="control-label">Account:</label>
                                            <select id="account" class="form-control">
                                            <option value="all"> All </option>';
	$sth = mysql_query("select distinct id as id, account_no, name from accounts where id in (select account_id from fixed_asset)");
	while($row = mysql_fetch_array($sth)){
		$content .= "<option value='".$row['id']."'>".$row['account_no'] ." - ".$row['name'];
	}
	$content .='</select>                                         
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label">Branch:</label>
                                            <span>'.branch_rep($branch_id).'</span>
	                                          
                                        
                                    </div>
                               
                                   
                                       
                                        <div class="col-sm-3">
                                            <label class="control-label">Date range:</label>
                                        
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                           
                                    </div><div class="col-sm-3">';


                             $content.="     <label class='control-label'>.</label>
                                <input type='button' class='btn  btn-primary' value='Search'  onclick=\"xajax_listFixedAssets(getElementById('account').value, getElementById('from_date').value, getElementById('to_date').value, getElementById('branch_id').value)\">
                                </div> </div> </div>";
                                                                                        
	$content .= "                 
                           
                        </form>
                        <!--/ Form default layout -->
                    </div>  ";
                    $resp->call("createDate","from_date");
	            $resp->call("createDate","to_date"); 
                    
                    // $resp->assign("display_div", "innerHTML", $content);
	
	if($from_date == '' || $to_date ==''){
		$cont= "<font color=red>Please select the period for the fixed assets</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	        }
	        else{
	$to_date = sprintf("%04d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
	$from_date = sprintf("%04d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	if (strtolower($type) == 'all')
	{
		$res = @mysql_query("select ac.id as acc_id, ac.name as acc_name, fx.id as fxid, fx.initial_value as initval, fx.date as date, fx.dep_percent as dep_rate,fx.branch_id as branch,fx.next_dep_date as next_date,fx.serialnumber as serialnumber,fx.assetgrp as grp,fx.assetref as ref from accounts ac join  fixed_asset fx on fx.account_id = ac.id where fx.id not in (select asset_id from sold_asset) and fx.date >='".$from_date."' and fx.date <='".$to_date."' ".$branch." order by fx.date desc");
	}
	/****************************
	* SPECIFY ACCOUNT TYPE
	***************************/
	else
	{
		$res = @mysql_query("select ac.id as acc_id, ac.name as acc_name, fx.id as fxid, fx.initial_value as initval, fx.date as date, fx.dep_percent as dep_rate,fx.branch_id as branch,fx.next_dep_date as next_date,fx.serialnumber as serialnumber,fx.assetgrp as grp,fx.assetref as ref from accounts ac join  fixed_asset fx on fx.account_id = ac.id where fx.id not in (select asset_id from sold_asset) and fx.date >='".$from_date."' and fx.date <='".$to_date."' and fx.account_id='".$type."' ".$branch." order by fx.date desc");
	}
	if (@mysql_num_rows($res) > 0)
	{
		
		$content .= '<div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">ASSET REGISTER REPORT</h3>
                            </div>';
 		$content .= '<table class="borderless table-hover" width="100%" id="table-tools">
			    <thead>
			        <th width="2%">#</th><th>Asset Name</th><th>Serial-No</th><th>Asset Group</th><th>Purchase Date</th><th>Asset Cost</th><th>Branch</th><th>Dep. Rate (%)</th><th>Date De-Value</th><th>Action</th>
			    </thead><tbody>';
		$i = 1;	
		$tot_cur_balance = 0;
		$tot_initval = 0;
		$tot_cur_amt = 0;
		while ($fxrow = @mysql_fetch_array($res))
		{
			$cur = mysql_fetch_array(mysql_query("select sum(amount) amount from deppreciation where asset_id='".$fxrow['fxid']."'"));
			$cur_amt = ($cur['amount'] != NULL) ? $cur['amount'] : 0;
			$cur_balance = $fxrow['initval'] - $cur['amount'];
			//$color = ($i%2 == 0) ? "white" : "lightgrey";

			//get branch name
			if($fxrow['branch'] > 0){
			$bre = mysql_fetch_array(mysql_query("select branch_name from branch where 	branch_no = '".$fxrow['branch']."'"));
			$branch = $bre['branch_name'];
}else{
	$branch = "All";
}

			$content .= "
				    <tr>
				      <td>$i</td><td>".$fxrow['acc_name']." - ".$fxrow['ref']."</td><td>".$fxrow['serialnumber']."</td><td>".$fxrow['grp']."</td><td>".$fxrow['date']."</td><td>".number_format($fxrow['initval'], 0)."</td><td>".$branch."</td><td>".$fxrow['dep_rate']."</td><td>".$fxrow['next_date']."</td><td><a href='javascript:;' onclick=\"xajax_editFixedAssetForm('".$fxrow['fxid']."', '".$type."', '".$from_date."','".$to_date."', '".$branch_id."'); return false;\">Edit</a>&nbsp;<a href='javascript:;' onclick=\"xajax_deleteFixedAsset2('".$fxrow['fxid']."', '".$type."', '".$from_date."', '".$to_date."', '".$branch_id."'); return false;\">Delete</a></td>
				    </tr>
				    ";		
			$i++;
			$tot_cur_balance += $cur_balance;
			$tot_initval += $fxrow['initval'];
			$tot_cur_amt += $cur_amt;
		}
		$content .= "<tr class='headings'>
				      <th colspan=5>TOTAL</th><th>".number_format($tot_initval, 0)."</th><th colspan=2></th><th colspan=2></th>
				    </tr></tbody></table></div>";
	}
			
       else{
		$cont = "<font color=red>No fixed assets registered yet.</font>";
		$resp->assign("status", "innerHTML", $cont);
	return $resp;
	}
	}
	$resp->call("createTables");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


function listFixedAssetdds($type,$from_date,$to_date, $branch_id)
{       
        list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
        
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	$branch = ($branch_id=='all'||$branch_id=='')?NULL:"and r.branch_id=".$branch_id;
	//$resp->alert($from_date."-".$to_date);
	//return $resp;
	$content = '
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <p><h5>LIST OF FIXED ASSETS</h5></p>
                                         
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    
                                        <div class="col-sm-3">
                                            <label class="control-label">Account:</label>
                                            <select id="account" class="form-control">';
	$sth = mysql_query("select distinct id as id, account_no, name from accounts where id in (select account_id from fixed_asset)");
	while($row = mysql_fetch_array($sth)){
		$content .= "<option value='".$row['id']."'>".$row['account_no'] ." - ".$row['name'];
	}
	$content .='</select>                                         
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label">Branch:</label>
                                            <span>'.branch_rep($branch_id).'</span>
	                                          
                                        
                                    </div>
                               
                                   
                                       
                                        <div class="col-sm-3">
                                            <label class="control-label">Date range:</label>
                                        
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                           
                                    </div><div class="col-sm-3">';


                             $content.="     <label class='control-label'>.</label>
                                <input type='button' class='btn  btn-primary' value='Search'  onclick=\"xajax_listFixedAssets(getElementById('account').value, getElementById('from_date').value, getElementById('to_date').value, getElementById('branch_id').value)\">
                                </div>";
                                                                                        
	$content .= "                 
                           
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","from_date");
	            $resp->call("createDate","to_date"); 
                    
                    // $resp->assign("display_div", "innerHTML", $content);
	
	if($from_date == '' || $to_date ==''){
		$cont= "<font color=red>Please select the period for the fixed assets</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	        }
	        else{
	$to_date = sprintf("%04d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
	$from_date = sprintf("%04d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	if (strtolower($type) == 'all')
	{
		$res = @mysql_query("select ac.id as acc_id, ac.name as acc_name, fx.id as fxid, fx.initial_value as initval, fx.date as date, fx.dep_percent as dep_rate from accounts ac join  fixed_asset fx on fx.account_id = ac.id where fx.id not in (select asset_id from sold_asset) and fx.date >='".$from_date."' and fx.date <='".$to_date."' ".$branch." order by fx.date desc");
	}
	/****************************
	* SPECIFY ACCOUNT TYPE
	***************************/
	else
	{
		$res = @mysql_query("select ac.id as acc_id, ac.name as acc_name, fx.id as fxid, fx.initial_value as initval, fx.date as date, fx.dep_percent as dep_rate from accounts ac join  fixed_asset fx on fx.account_id = ac.id where fx.id not in (select asset_id from sold_asset) and fx.date >='".$from_date."' and fx.date <='".$to_date."' and fx.account_id='".$type."' ".$branch." order by fx.date desc");
	}
	if (@mysql_num_rows($res) > 0)
	{
		$content .=
			   "<a href='list_fixed_assets.php?account=".$type."&branch_id=".$branch_id."&from_date=".$from_date."&to_date=".$to_date."' target=blank()><b>Printable Version</b></a> | <a href='list_fixed_assets.php?account=".$type."&branch_id=".$branch_id."&from_date=".$from_date."&to_date=".$to_date."&format=excel' target=blank()><b>Export Excel</b></a> | <a href='list_fixed_assets.php?account=".$type."&branch_id=".$branch_id."&from_date=".$from_date."&to_date=".$to_date."&format=word' target=blank()><b>Export Word</b></a>";
		$content .= '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">LIST OF FIXED ASSETS</h3>
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">
			    <thead>
			        <th width="2%">#</th><th>Asset Name</th><th>Purchase Date</th><th>Initial Value</th><th>Current Value</th><th>Dep. Rate (%)</th><th>Depreciation Values</th><th></th>
			    </thead><tbody>';
		$i = 1;	
		$tot_cur_balance = 0;
		$tot_initval = 0;
		$tot_cur_amt = 0;
		while ($fxrow = @mysql_fetch_array($res))
		{
			$cur = mysql_fetch_array(mysql_query("select sum(amount) amount from deppreciation where asset_id='".$fxrow['fxid']."'"));
			$cur_amt = ($cur['amount'] != NULL) ? $cur['amount'] : 0;
			$cur_balance = $fxrow['initval'] - $cur['amount'];
			//$color = ($i%2 == 0) ? "white" : "lightgrey";
			$content .= "
				    <tr>
				      <td>$i.</td><td>".$fxrow['acc_name']."</td><td>".$fxrow['date']."</td><td>".number_format($fxrow['initval'], 2)."</td><td>".number_format($cur_balance, 2)."</td><td>".$fxrow['dep_rate']."</td><td><a href='javascript:;' onclick=\"xajax_show_dep('".$fxrow['fxid']."', '".$type."', '".$from_date."','".$to_date."', '".$branch_id."')\">".number_format($cur['amount'], 2)."</a></td><td><a href='javascript:;' onclick=\"xajax_editFixedAssetForm('".$fxrow['fxid']."', '".$type."', '".$from_date."','".$to_date."', '".$branch_id."'); return false;\">Edit</a>&nbsp;<a href='javascript:;' onclick=\"xajax_deleteFixedAsset2('".$fxrow['fxid']."', '".$type."', '".$from_date."', '".$to_date."', '".$branch_id."'); return false;\">Delete</a></td>
				    </tr>
				    ";		
			$i++;
			$tot_cur_balance += $cur_balance;
			$tot_initval += $fxrow['initval'];
			$tot_cur_amt += $cur_amt;
		}
		$content .= "<tr class='headings'>
				      <td colspan=3>TOTAL</td><td>".number_format($tot_initval, 2)."</td><td colspan=2>".number_format($tot_cur_balance, 2)."</td><td colspan=2>".number_format($tot_cur_amt, 2)."</td>
				    </tr></tbody></table></div>";
	}
			
       else{
		$cont = "<font color=red>No fixed assets registered yet.</font>";
		$resp->assign("status", "innerHTML", $cont);
	return $resp;
	}
	}
	$resp->call("createTables");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function show_dep($fxid, $type,$from_date,$to_date, $branch_id)
{        

        	 list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
        
	$resp = new xajaxResponse();
	$res = mysql_fetch_array(mysql_query("select ac.id as acc_id, ac.account_no as account_no, ac.name as acc_name, fx.initial_value as initval, fx.date as date, fx.dep_percent as dep_rate from accounts ac join  fixed_asset fx on fx.account_id = ac.id where fx.id='".$fxid."'"));
	if ($res['acc_id'] != NULL)
	{
		
		$content = "<div class='panel panel-default' id='demo'>
                            <div class='panel-heading'>
                                <h3 class='panel-title'>DEPRECIATION VALUES OF ASSET: ".strtoupper($res['account_no'])." - ".strtoupper($res['acc_name'])." AT ".$res['dep_rate']."%</h3>
                            </div>";
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">
		
			        <thead><th width="2%"></th><th>Date</th><th>Start Balance</th><th>Depreciation</th><th>End Balance</th>
			    </thead><tbody>';
			   
		$i = 1;	
		$sth = mysql_query("select * from deppreciation where asset_id='".$fxid."' order by date asc");
		$begin_bal = $res['initval'];
		while ($fxrow = @mysql_fetch_array($sth))
		{
			$end_bal = $begin_bal - $fxrow['amount'];
			//$color = ($i%2 == 0) ? "white" : "lightgrey";
			$content .= "
				    <tr>
				      <td>".$i."</td><td>".$fxrow['date']."</td><td>".number_format($begin_bal, 2)."</td><td>".number_format($fxrow['amount'], 2)."</td><td>".number_format($end_bal, 2)."</td>
				    </tr>
				    ";		
			$begin_bal = $end_bal;
			$i++;
		}
		$content .= "</tbody></table></div>";
	}
	
	else{
		$cont = "<font color=red>No depreciation found..</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
	}
	
	
	
	$content .="<form method='post' class='panel form-horizontal panel-default'>";
$content .= '<div class="panel-body">';                                        
                                        
                                        $content .= '<div class="form-group">
                                             
                                            <label class="col-sm-3 control-label">Amount Depreciated:</label>
                                            <div class="col-sm-4">
                                           <input type=input id="amt_dep" class="form-control" >
                                            </div></div>';  
                                                                                     
	                                $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Date:</label>
                                            <div class="col-sm-4">
                                          <input type="text" class="form-control" id="date" name="date" placeholder="From" />
                                            </div></div>';
                                       $content .= "<div class='panel-footer'><button type='reset' class='btn btn-default' onclick=\"xajax_schedule('".$type."', '".$from_date."','".$to_date."', '".$branch_id."')\">Back</button>
                                            <button type='button' class='btn btn-primary' onclick=\"xajax_insert_dep('".$fxid."', getElementById('amt_dep').value, getElementById('date').value,'".$type."', '".$from_date."','".$to_date."', '".$branch_id."')\">Save</button>";
                                             $resp->call("createDate","date");      
		
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function insert_dep($fxid, $amt_dep, $date, $type,$from_date,$to_date, $branch_id){

         list($year,$month,$mday) = explode('-', $date);
         list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$end_day =  date("Y-m-t", strtotime(date("Y-m-d",time())) ) ;
	$now_day =  date("Y-m-d", strtotime(date("Y-m-d",time())) ) ;
	//$now_day =  "2016-07-31" ;

if($end_day != $date){
$resp->alert("Depreciation rejected! Only possible at the end of the month as at ".$end_day);
return $resp;
}



	if($amt_dep ==''){
		$resp->alert("Depreciation rejected! Dont leave any field blank");
		return $resp;
	}elseif(!$calc->isValidDate($mday, $month, $year)){
		$resp->alert("Depreciation rejected! Please enter valid date");
		return $resp;
	}elseif($calc->isFutureDate($mday, $month, $year)){
		$resp->alert("Depreciation rejected! You have entered a future date");
		return $resp;
	}
	//$date = sprintf("%d-%02d-%02d 00:00:00", $date);
	$sth = mysql_query("select * from fixed_asset where id='".$fxid."' and date <= '".$date."'");
	if(mysql_numrows($sth) == 0){
		$resp->alert("Depreciation rejected! Date is earlier than the Asset purchase date");
		return $resp;
	}
	$row = mysql_fetch_array(mysql_query("select * from fixed_asset where id='".$fxid."'"));
	$branch_id = $row['branch_id'];
	mysql_query("insert into deppreciation set date='".$date."', amount='".$amt_dep."', asset_id='".$fxid."', branch_id='".$branch_id."'");
	$resp->call('xajax_show_dep', $fxid, $type,$from_date,$to_date, $branch_id);
	return $resp;
}

function editFixedAssetForm($assetid, $type,$from_date,$to_date, $branch_id)
{
	$resp = new xajaxResponse();
	/*if($_SESSION['position'] <>'Manager'){
		$resp->alert("Access Denied! \nOnly the Manager can manage account");
		return $resp;
	}*/
	
	$resp->assign("status", "innerHTML", "");
	$res = @mysql_query("select  ac.name, fx.* from  fixed_asset fx join accounts ac on ac.id = fx.account_id where fx.id = $assetid");
	if (@mysql_num_rows($res) > 0)
	{
		$row = @mysql_fetch_array($res);
		$content .="<form method='post' class='panel form-horizontal panel-default'>";
$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="panel-header">
                                 		<div class="col-md-12">
                                                	<h4 class="semibold text-primary mt0 mb5">EDIT FIXED ASSET</h4>
                                               		 <p class="text-default nm"></p>
                                           	 </div>
                                        </div>';
                                                                       	                                                                                                                                                                                                                                                   
                                             $content .= '<div class="col-md-6"><div class="form-group">
                                            <label class="col-sm-4 control-label">Initial Value:</label>
                                            <div class="col-sm-8">
                                          <input onkeyup="format_as_number(this.id)" type="numeric" name="initval" id="initval" class="form-control" value="'.$row['initial_value'].'">
                                            </div></div>';
                                            
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-4 control-label">Rate of depreciation:</label>
                                            <div class="col-sm-8">
                                          <input type="numeric" name="deprate" id="deprate" class="form-control" value="'.$row['dep_percent'].'">
                                            </div></div>'; 
                                                                                                                                                                                                                   
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-4 control-label">Purchase Date:</label>
                                            <div class="col-sm-8">
                                           <input type="text" class="form-control" id="date" name="date" placeholder="date" />
                                            </div></div>
                                            <div class="form-group">
                                            <label class="col-sm-4 control-label">Asset Reference:</label>
                                            <div class="col-sm-8">
                                          <input type="numeric" name="ref" id="ref" class="form-control" value="'.$row['assetref'].'">
                                            </div></div></div>';  
                                             $content .= '<div class="col-md-6"><div class="form-group">
                                            <label class="col-sm-4 control-label">Asset category / Group:</label>
                                            <div class="col-sm-8">
                                          <input type="numeric" name="grp" id="grp" class="form-control" value="'.$row['assetgrp'].'">
                                            </div></div>';
                                            
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-4 control-label">Asset Description:</label>
                                            <div class="col-sm-8">
                                          <input type="numeric" name="descp" id="descp" class="form-control" value="'.$row['assetdesc'].'">
                                            </div></div>'; 
                                            $content .= '<div class="form-group">
                                            <label class="col-sm-4 control-label">Asset department:</label>
                                            <div class="col-sm-8">
                                          <input type="numeric" name="dept" id="dept" class="form-control" value="'.$row['assetdept'].'">
                                            </div></div>'; 
                                                                                                                                                                                                                   
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-4 control-label">Serial Number:</label>
                                            <div class="col-sm-8">
                                           <input type="numeric" name="serialnum" id="serialnum" class="form-control" value="'.$row['serialnumber'].'">
                                            </div></div></div></div>';                                         
                                             
                                            $content .= "<div class='panel-footer'>
                                             <button type='button' class='btn btn-default' onclick=\"xajax_listFixedAssets('".$type."', '".$from_date."','".$to_date."', '".$branch_id."');\">Back</button>&nbsp;<button type='button' class='btn btn-primary' onclick=\"xajax_updateFixedAsset2('".$row['id']."', '".$row['name']."', document.getElementById('deprate').value, document.getElementById('initval').value, document.getElementById('date').value,'".$type."', '".$from_date."','".$to_date."', '".$branch_id."', document.getElementById('ref').value, document.getElementById('descp').value, document.getElementById('serialnum').value,document.getElementById('dept').value,document.getElementById('grp').value); return false;\">Save</button>";
                   $content .= '</div></form></div>';
                   $resp->call("createDate","date");
		
	}
	
	else{
		$cont = "<font color=red>ERROR: Fixed asset not found!</font>";
		$resp->assign("status", "innerHTML", $cont);
	//return $resp;
	}
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function updateFixedAsset2($assetid,$asset_name,$deprate,$initval,$date,$type,$from_date,$to_date,$branch_id,$ref,$desc,$serialnumber,$dept,$grp)
{       
        $initval=str_ireplace(",","",$initval);
        list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$calc = new Date_Calc();
	$depr = @mysql_query("select date from deppreciation where asset_id = $assetid");
	if ($assetid == '' || $deprate == '' || $initval == '' || $date == '')
		$resp->alert('Please do not leave any fields blank.');
	elseif ($calc->isFutureDate($mday, $month, $year))
		$resp->alert('Purchase Date can not be a future date');
	elseif (@mysql_num_rows($depr) > 0)
		$resp->alert('Asset has already depreciated. No changes can be made now.');
	else
	{
		$resp->ConfirmCommands(1, "Do you really want to update?");
		$resp->call('xajax_updateFixedAsset', $assetid, $asset_name, $deprate, $initval, $date, $type,$from_date,$to_date, $branch_id,$ref,$desc,$serialnumber,$dept,$grp);
	}
	return $resp;
}

function updateFixedAsset($assetid, $asset_name, $deprate, $initval, $date, $type,$from_date,$to_date, $branch_id,$ref,$desc,$serialnumber,$dept,$grp)
{       list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$yeardays = ($year % 4 == 0 && ($year % 100 != 0 || $year % 400 == 0)) ? 366 : 365;
	
	$nxt_dep_date = date('Y-m-d', (mktime(0, 0, 0, $month, $mday, $year) + (intval($yeardays) *24*60*60/12)));    //MONTHLY
	start_trans();
	$former_res = mysql_query("select * from fixed_asset where id='".$assetid."'");
	$former = mysql_fetch_array($former_res);
	$res = @mysql_query("update fixed_asset set date='".$date."', next_dep_date='".$nxt_dep_date."', dep_percent='".$deprate."', initial_value='".$initval."', assetref='".$ref."', assetdesc='".$desc."', serialnumber='".$serialnumber."', assetgrp='".$grp."', assetdept='".$dept."' where id='".$assetid."'");
	
	if (@mysql_affected_rows() > 0){
		if(mysql_query("update bank_account set account_balance=account_balance +".$former['initial_value']." - ".$initval." where id='".$former['bank_account']."'")){
			//////////////////
			$accno = mysql_fetch_assoc(mysql_query("select a.account_no,a.name from accounts a join fixed_asset f on f.account_id=a.id where f.id='".$assetid."'"));
			$action = "update fixed_asset set date='".$date."', next_dep_date='".$nxt_dep_date."', dep_percent='".$deprate."', initial_value=$initval where id=$assetid";
			$msg = "Updated fixed assets set date:".$date.", initval_value:".$initval." on ac/no: ".$accno['account_no']." - ".$accno['name'];
			log_action($_SESSION['user_id'],$action,$msg);
			////////////////////
			commit();
			$content .= "<font color=green><b>$asset_name updated successfully.</b></font><br>";
		}else{
			$resp->alert("Update not done! \nCould not update bank account");
			rollback();
		}
	}else
		$cont = "<font color=red><b>ERROR: ".$asset_name." not updated!</b></font><br>".mysql_error();
	$resp->assign("status", "innerHTML", $cont);
	$resp->call('xajax_listFixedAssets', $type,$from_date,$to_date, $branch_id);
	return $resp;
}

function deleteFixedAsset2($assetid, $type,$from_date,$to_date, $branch_id)
{
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	//$depr = @mysql_query("select date from deppreciation where asset_id = $assetid");
	//if (@mysql_num_rows($depr) > 0)
	//	$resp->alert("Cannot delete asset: Depreciation has already been calculated.");
	//else
	//{
		$resp->ConfirmCommands(1, "Do you really want to delete?");
		$resp->call('xajax_deleteFixedAsset', $assetid, $type,$from_date,$to_date, $branch_id);
	//}
	//$resp->assign("status", "innerHTML", "test");
	return $resp;
}


function deleteFixedAsset($assetid, $type,$from_date,$to_date, $branch_id)
{
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	start_trans();
	$former_res = mysql_query("select * from fixed_asset where id='".$assetid."'");
	$former = mysql_fetch_array($former_res);
	$accno = mysql_fetch_assoc(mysql_query("select a.account_no,a.name from accounts a join fixed_asset f on f.account_id=a.id where f.id='".$assetid."'"));
	$res = @mysql_query("delete from fixed_asset where id = $assetid");
	if (@mysql_affected_rows() > 0){
		if(mysql_query("update bank_account set account_balance=account_balance +".$former['initial_value']."  where id='".$former['bank_account']."'") && mysql_query("delete from deppreciation where asset_id='".$assetid."'")){
			///////////////
			
			$action = "delete from fixed_asset where id = $assetid";
			$msg = "Deleted amount: ".number_format($former['initial_value'],2)." from fixed assets on ac/no:".$accno['account_no']." - ".$accno['name'];
			log_action($_SESSION,$action,$msg);
			//////////////
			commit();
			$content .= "<font color=green><b>Asset deleted successfully.</b></font><br>";
		}else{
			$resp->alert("Aasset not deleted! \n Could not update bank account");
			rollback();
		}
	}else
		$cont = "<font color=red><b>Error: Failed to delete Asset. </b></font>";
	$resp->assign("status", "innerHTML", $cont);
	$resp->call('xajax_listFixedAssets', $type,$from_date,$to_date, $branch_id);
	return $resp;
}

function saleFixedAssetForm()
{       
        $accts="";
	$resp = new xajaxResponse();
	$ass_res = @mysql_query("select ac.name, fx.id as fxid, fx.initial_value as initial_value, ac.account_no as account_no from accounts ac join fixed_asset fx on ac.id = fx.account_id where fx.id NOT IN (select asset_id from sold_asset)");
	$acc = @mysql_query("select ac.name, ac.id as acc_id, ac.account_no, ba.bank, ba.id as bank_id from accounts ac join bank_account ba on ac.id = ba.account_id");
	if (@mysql_num_rows($acc) > 0)
	{
		$accts .= "<option value=''>&nbsp;</option>";
		while ($acc_row = @mysql_fetch_array($acc))
		{
			$accts .= "<option value='".$acc_row['bank_id']."'>".$acc_row['bank']." -". $acc_row['name']."</option>";
		}
	}
	else
		$accts .= "<option value=''>&nbsp;</option>";

	if (@mysql_num_rows($ass_res) > 0)
	{
		$assets .= "<option value=''>&nbsp;</option>";
		while ($ass_row = @mysql_fetch_array($ass_res))
		{
			$assets .= "<option value='".$ass_row['fxid']."'>".$ass_row['account_no']." -". $ass_row['name']."(".$ass_row['initial_value'].")"."</option>";
		}
		$content .="<div class='col-md-12'><form method='post' class='panel form-horizontal panel-default'>";
$content .= '
  			  		<div class="panel-heading">
                               <h4>SELL FIXED ASSET</h4>
                                               	
                                           	 </div>
                                        <div class="panel-body">';
                                                                                                           
                                            $content .= '<div class="form-group">
                                           
                                            <label class="col-sm-3 control-label">Choose Asset:</label>
                                            <div class="col-sm-6">
                                          <select name="assetid" id="assetid" onchange=\'xajax_purchase_date(document.getElementById("assetid").value, "fixed_asset"); \' class="form-control"> class="form-control">'.$assets.'</select><div id="purchase_date"></div>
                                            </div></div>';                                         
                                             $content .= '<div class="form-group">
                                             
                                            <label class="col-sm-3 control-label">Destination Bank Account:</label>
                                            <div class="col-sm-6">
                                           <select name="bank_acct_id" id="bank_acct_id" class="form-control">'.$accts.'</select>
                                            </div></div>';                                         
                                                                                                                                                                                                                                                                                                                                                                       
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Date:</label>
                                            <div class="col-sm-6">
                                           <input type="text" class="form-control" id="date" name="date" placeholder="date" />
                                            </div></div>';
                                            
                                            $content .= '<div class="form-group">
                                             
                                            <label class="col-sm-3 control-label">Amount Paid:</label>
                                            <div class="col-sm-6">
                                          <input onkeyup="format_as_number(this.id)" type="numeric" name="amount" id="amount" class="form-control">
                                            </div></div>'; 
                                            
                                            $content .= '<div class="form-group">
                                             
                                            <label class="col-sm-3 control-label">Receipt No:</label>
                                            <div class="col-sm-6">
                                          <input type="text" name="rcptno" id="rcptno" class="form-control">
                                            </div></div>';
                                                                                                                                                                                                                  
                                             $content .= "<div class='panel-footer'><button type='reset' class='btn btn-default' onclick=\"xajax_saleFixedAssetForm()\">Reset</button>
                                            <button type='button' class='btn btn-primary' onclick=\"xajax_saleFixedAsset(getElementById('assetid').value, getElementById('bank_acct_id').value, getElementById('amount').value, getElementById('rcptno').value,getElementById('date').value); return false;\">Save</button>";
				 $content .= '</div></form></div>';
				 $resp->call("createDate","date");
		$resp->assign("display_div", "innerHTML", $content);
		return $resp;
	}
	else
	{
		$cont = "<font color='red'>There are no assets for sale at the moment.</font><br>";
		$resp->assign("status", "innerHTML", $cont);
		return $resp;
	}
}

function saleFixedAsset($assetid, $bank_acct_id, $amount, $rcptno, $date)
{       
        $amount=str_ireplace(",","",$amount);
        list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$resp->assign("status", "innerHTML", "");
	//$date = sprintf("%04d-%02d-%02d", $date);
	$check = mysql_fetch_array(mysql_query("select * from fixed_asset where id='".$assetid."'"));
	if ($bank_acct_id == '' || $amount == '' || $rcptno == '')
	{
		$resp->alert("You must fill in all the fields.");
		return $resp;
	}elseif(!$calc->isValidDate($mday, $month, $year)){
		$resp->alert("Transaction rejected! Please enter valid date");
		return $resp;
	}elseif($calc->isFutureDate($mday, $month, $year)){
		$resp->alert("Transaction rejected! You have entered a future date");
		return $resp;
	}elseif($date <= $check['date']){
		$resp->alert("Transaction rejected! You have entered a date before the purchase date");
		return $resp;
	}
	
	$rec_res = mysql_query("SELECT receipt_no FROM payment where receipt_no='".$rcptno."' UNION SELECT receipt_no FROM deposit where receipt_no='".$rcptno."' UNION SELECT receipt_no from collected where receipt_no='".$rcptno."' UNION SELECT receipt_no from shares where receipt_no='".$rcptno."' UNION SELECT receipt_no from other_income where receipt_no='".$rcptno."' UNION SELECT receipt_no from recovered where receipt_no='".$rcptno."' union select receipt_no from sold_asset where receipt_no='".$rcptno."' union select receipt_no from sold_invest where receipt_no='".$rcptno."'");
	if(mysql_numrows($rec_res)){
		$resp->alert("Sale not done! \n Receipt no. already exists");
		return $resp;
	}
	if(preg_match("/\D/", $amount)){
		$resp->alert("Please enter only digits for amount");
		return $resp;
	}
	$bank_row = @mysql_fetch_array(@mysql_query("select min_balance, account_balance from bank_account where id = $bank_acct_id"));
	$new_bal = intval($bank_row[account_balance]) + $amount;
	@mysql_query("START TRANSACTION");	
	$bal_res = @mysql_query("UPDATE bank_account set account_balance = ".$new_bal." where id = '".$bank_acct_id."'");
	if (isset($bank_res) && @mysql_affected_rows() == -1)
	{
		@mysql_query("ROLLBACK");
		$content .= "<font color=red>ERROR: Failed to update bank account. ".mysql_error()."</font>";
		$resp->assign("status", "innerHTML", $content);
		return $resp;
	}
	else
	{
		$branch_id = mysql_fetch_assoc(mysql_query("select branch_id from fixed_asset where id='".$assetid."'"));
		
		$res = @mysql_query("INSERT INTO sold_asset (amount, receipt_no, date, asset_id,  bank_account,branch_id) VALUES ($amount, '".$rcptno."', '".$assetid."', '".$date."', '".$bank_acct_id."','".$branch_id['branch_id']."')");
		$resp->alert($res);
		return $resp;
		if (isset($res) && @mysql_affected_rows($res) > 0)
		{
			///////////////////
			$accno = mysql_fetch_assoc(mysql_query("select a.account_no,a.name from accounts a join fixed_asset f on f.account_id=a.id where f.id ='".$assetid."'"));
			$action = "INSERT INTO sold_asset (amount, receipt_no, asset_id, date, bank_account) VALUES ($amount, '".$rcptno."', $assetid, '".$date."', $bank_acct_id";
			$msg = "Sold an asset at amount:".$amount. " to ac/no: ".$accno['account_no']." - ".$accno['name'];
			log_action($_SESSION['user_id'],$action,$msg);
			/////////////////////
			@mysql_query("COMMIT");
			$cont = "<font color='green'>Sale of fixed asset successfully registered.</font>";
			$resp->assign("status", "innerHTML", $cont);
			$resp->call('xajax_listSoldAssets');
			return $resp;
		}
		else
		{
			@mysql_query("ROLLBACK");
			$cont = "<font color=red>Sold item not registered! \n could not reigiter it</font>";
			$resp->assign("status", "innerHTML", $cont);
			return $resp;
		}
	}
}

function listSoldAssets()
{
	$resp = new xajaxResponse();
	$res = @mysql_query("select ac.name, fx.date as pur_date, fx.initial_value as pur_value, sa.id as sa_id, sa.date as sale_date, sa.amount as sale_value from accounts ac join fixed_asset fx on ac.id = fx.account_id join sold_asset sa on fx.id = sa.asset_id order by sa.date desc");
	if (@mysql_num_rows($res) > 0)
	{
		$content = '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">LIST OF SOLD ASSETS</h3>
                            </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">

			    
			    
			   <thead>
				<th align=center>Name of Asset</th><th align=center>Purchase Date</th><th align=center>Purchase Value</th><th align=center>Sale Date</th><th align=center>Sale Value</th><th></th>
			    </thead><tbody>'; 
			    
		$i=0;
		while ($row = @mysql_fetch_array($res))
		{
			//$color = ($i%2 == 0) ? "lightgrey" : "white";
			$content .= "
				    <tr>
					<td align=center>".$row['name']."</td><td align=center>".$row['pur_date']."</td><td align=center>".$row['pur_value']."</td><td align=center>".$row['sale_date']."</td><td align=center>".$row['sale_value']."</td><td align=center><a  onclick=\"xajax_editSoldAssetForm('".$row['sa_id']."'); return false;\">Edit</a>&nbsp;<a onclick=\"xajax_deleteSoldAsset2('".$row['sa_id']."'); return false;\">Delete</a></td>
				    </tr> 
				    ";
		}
		$content .= "</tbody></table></div>";
		$resp->assign("display_div", "innerHTML", $content);
		return $resp;
	}
		
	else{
		$cont = "<font color=red>No Assets have been sold yet.</font>";
		$resp->assign("status", "innerHTML", $cont);
	return $resp;
	}
}

function editSoldAssetForm($sa_id)
{
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	//$resp->assign("status", "innerHTML", "");
	$acc = @mysql_query("select ac.name, ac.id as acc_id, ac.account_no, ba.bank, ba.id as bank_id from accounts ac join bank_account ba on ac.id = ba.account_id");
	$bank = @mysql_fetch_array(@mysql_query("select s.bank_account as bank_account, a.account_no, a.name, b.bank from sold_asset s join bank_account b on s.bank_account=b.id join accounts a on b.account_id=a.id where s.id = $sa_id"));
	if (@mysql_num_rows($acc) > 0)
	{
		$accts .= "<option value=''>&nbsp;</option>";
		while ($acc_row = @mysql_fetch_array($acc))
		{
			if ($bank[bank_account] == $acc_row[bank_id])
				$accts .= "<option value='".$acc_row['bank_id']."' selected>".$acc_row['account_no']." -". $acc_row['bank']."". $acc_row['name']."</option>";
			else
				$accts .= "<option value='".$acc_row['bank_id']."'>".$acc_row['account_no']." - ".$acc_row['bank']."". $acc_row['name']."</option>";
		}
	}
	$res = @mysql_query("select ac.name, fx.account_id, sa.amount, sa.receipt_no, sa.bank_account, date_format(sa.date, '%Y') as year, date_format(sa.date, '%m') as month, date_format(sa.date, '%d') as mday from accounts ac join fixed_asset fx on ac.id = fx.account_id join sold_asset sa on fx.id = sa.asset_id where sa.id = $sa_id");
	if (@mysql_num_rows($res) > 0)
	{
		$row = @mysql_fetch_array($res);
		$content .="<form method='post' class='panel form-horizontal form-bordered'>";
$content .= '<div class="panel-body pt0 pb0">
  			  		<div class="form-group header bgcolor-default">
                                 		<div class="col-md-12">
                                                	<h4 class="semibold text-primary mt0 mb5">EDIT SOLD FIXED ASSET</h4>
                                               		 <p class="text-default nm"></p>
                                           	 </div>
                                        </div>';
                                                                       	                                    
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
                                            $content .= '<div class="form-group">
                                             
                                            <label class="col-sm-3 control-label">Amount:</label>
                                            <div class="col-sm-6">
                                          <input type="numeric" name="amount" id="amount" value="'.$row[amount].'">
                                            </div></div>'; 
                                            
                                            $content .= '<div class="form-group">
                                             
                                            <label class="col-sm-3 control-label">Receipt No:</label>
                                            <div class="col-sm-6">
                                          <input type="text" name="rcptno" id="rcptno"  value="'.$row[receipt_no].'">
                                            </div></div>';
                                            
                                             $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Date:</label>
                                            <div class="col-sm-6">
                                           <input type="text" class="form-control" id="date" name="date" value="'.$row['mday']."/".$row['month']."/".$row['year'].'" />
                                            </div></div>';
                                            
                                            $content .= '<div class="form-group">
                                             
                                            <label class="col-sm-3 control-label">Bank Account:</label>
                                            <div class="col-sm-6">
                                           <select name="bank_acct_id" id="bank_acct_id">'.$accts.'</select>
                                            </div></div>';
                                            
                                            $content .= "<div class='panel-footer'><button type='reset' class='btn btn-default'onclick=\"xajax_listSoldAssets()\">Back</button>
                                            <button type='button' class='btn btn-primary' onclick=\"xajax_updateSoldAsset2(".$sa_id.", ".$row[amount].", getElementById('amount').value, getElementById('rcptno').value, getElementById('bank_acct_id').value, getElementById('date').value; return false;\">Save</button>";
				 $content .= '</div></form></div>';
				 $resp->call("createDate","date");
			      
	}
	else
		
	{
		$cont = "<font color='red'>ERROR: No record found!</font>";
		$resp->assign("status", "innerHTML", $cont);
		return $resp;
	}

	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function updateSoldAsset2($sa_id, $prev_amount, $amount, $rcptno, $bank_acct_id, $date)
{
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	if ($sa_id == '' || $amount == '' || $rcptno == '' || $bank_acct_id == '')
	{
		$resp->alert("Please do not leave any fields blank Incl. the bank account.");
		return $resp;
	}
	if(preg_match("/\D/", $amount)){
		$resp->alert("Please enter only digits for amount");
		return $resp;
	}
	$rec_res = mysql_query("SELECT receipt_no FROM payment where receipt_no='".$rcptno."' UNION SELECT receipt_no FROM deposit where receipt_no='".$rcptno."' UNION SELECT receipt_no from collected where receipt_no='".$rcptno."' UNION SELECT receipt_no from shares where receipt_no='".$rcptno."' UNION SELECT receipt_no from other_income where receipt_no='".$rcptno."' UNION SELECT receipt_no from recovered where receipt_no='".$rcptno."' union select receipt_no from sold_asset where receipt_no='".$rcptno."' and id<>'".$sa_id."' union select receipt_no from sold_invest where receipt_no='".$rcptno."'");
	if(mysql_numrows($rec_res)){
		$resp->alert("Update not done! \n New receipt no already exists");
		return $resp;
	}
	$row = @mysql_fetch_array(@mysql_query("select ba.account_balance, ba.min_balance, sa.bank_account from bank_account ba join sold_asset sa on ba.id = sa.bank_account where sa.id = $sa_id"));
	$new_bal = intval($row[account_balance]) - $prev_amount;
	/* if ($new_bal < intval($row[min_balance]))
	{
		$resp->alert("Error: Update failed: Insufficient funds on original account.");
		return $resp;
	}
	*/
	$resp->ConfirmCommands(1, "Do you really want to update?");
	$resp->call('xajax_updateSoldAsset', $sa_id, $row[account_balance], $prev_amount, $amount, $rcptno, $bank_acct_id, $row[bank_account], $date);
	return $resp;
}

function updateSoldAsset($sa_id, $account_balance, $prev_amount, $amount, $rcptno, $bank_acct_id, $prev_bank_acct_id, $date)
{
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	if ($prev_amount == $amount && $bank_acct_id == $prev_bank_acct_id)
	{
		@mysql_query("START TRANSACTION");
		$res = @mysql_query("UPDATE sold_asset set date = '".$date."', receipt_no = '".$rcptno."' where id = $sa_id");
		if (isset($res) && @mysql_affected_rows() != -1)
		{
			
			@mysql_query("COMMIT");

			$content .= "<font color='green'>Sale of Asset successfully updated.</font><br>";
			$resp->assign("status", "innerHTML", $content);
			$resp->call('xajax_listSoldAssets');
			return $resp;
		}
		else
		{
			@mysql_query("ROLLBACK");
			$content .= "<font color='red'>ERROR: Failed to update Sale of Asset.</font><br>";
			$resp->assign("status", "innerHTML", $content);
			return $resp;
		}
	}
	else
	{
		$new_bal = $account_balance - $prev_amount;
		@mysql_query("START TRANSACTION");
		$bank1_res = @mysql_query("UPDATE bank_account set account_balance = $new_bal where id = $prev_bank_acct_id");
		if (isset($bank1_res) && @mysql_affected_rows() != -1)
		{
			$row = @mysql_fetch_array(@mysql_query("select account_balance from bank_account where id = $bank_acct_id"));
			$cur_bal = intval($row[account_balance]) + $amount;
			$bank2_res = @mysql_query("UPDATE bank_account set account_balance = $cur_bal where id = $bank_acct_id");  
			if (isset($bank2_res) && @mysql_affected_rows() != -1)
			{
				$upd_res = @mysql_query("UPDATE sold_asset set bank_account = $bank_account, amount = $amount, date = '".$date."', receipt_no = '".$rcptno."' where id = $sa_id");
				if (isset($upd_res) && @mysql_affected_rows() != -1)
				{
					///////////////////////////
			$accno = mysql_fetch_assoc(mysql_query("select a.account_no,a.name from accounts a join fixed_asset f on f.account_id=a.id join sold_asset s on s.asset_id=f.id where s.id=".$sa_id));
			$action = "UPDATE sold_asset set date = '".$date."', receipt_no = '".$rcptno."' where id = $sa_id";
			$msg = "Updated a sold asset, set date:".$date.", amount:".number_format($amount,2)." on ac/no: ".$accno['account_no']." - ".$accno['name'];
			log_action($_SESSION['user_id'],$action,$msg);
			////////////////////////
					@mysql_query("COMMIT");
					$content .= "<font color=green>Sale of fixed asset updated successfully.</font><br>";
					$resp->call('xajax_listSoldAssets');
				}
				else
				{
					@mysql_query("ROLLBACK");
					$content .= "<font color=red>ERROR: Failed to update sale of fixed asset.</font><br>";
				}
			}
			else
			{
				@mysql_query("ROLLBACK");
				$content .= "<font color=red>ERROR: Failed to update new bank balance.</font><br>";
			}
		}
		else
		{
			@mysql_query("ROLLBACK");
			$content .= "<font color=red>ERROR: Failed to update bank balance.</font><br>";
		}
		$resp->assign("status", "innerHTML", $content);
		return $resp;
	}
}

function deleteSoldAsset2($sa_id)
{
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	$row = @mysql_fetch_array(@mysql_query("select ba.account_balance, ba.min_balance, sa.bank_account, sa.amount, (ba.account_balance - sa.amount) as new_bal from bank_account ba, sold_asset sa where ba.id = sa.bank_account and sa.id = $sa_id"));
	/*
	if (intval($row[new_bal]) < intval($row[min_balance]))
	{
		$resp->alert("You cannot delete this sale: Insufficient funds on the account.");
		return $resp;
	}
	*/
	$resp->ConfirmCommands(1, "Do you really want to delete the sale?");
	$resp->call('xajax_deleteSoldAsset', $sa_id, $row[new_bal], $row[bank_account]);
	return $resp;
}

function deleteSoldAsset($sa_id, $new_bal, $bank_acct_id)
{       $content ="";
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	@mysql_query("START TRANSACTION");
	$upd_res = @mysql_query("UPDATE bank_account set account_balance = $new_bal where id = $bank_acct_id");
	if (isset($upd_res) && @mysql_affected_rows() != -1)
	{
		$accno = mysql_fetch_assoc(mysql_query("select a.account_no,a.name from accounts a join fixed_asset f on f.account_id=a.id join sold_asset s on s.asset_id=f.id where s.id=".$sa_id));
		$amount = mysql_fetch_assoc(mysql_query("select amount from sold_asset where id=".$sa_id));
		$del_res = @mysql_query("delete from sold_asset where id = $sa_id");
		if (isset($del_res) && @mysql_affected_rows() != -1)
		{
			
			////////////////////
			
		$action = "delete from sold_asset where id = $sa_id";
		$msg = "Deleted a sold asset amounting to:".number_format($amount['amount'],2)." from ac/no:".$accno['account_no']." - ".$accno['name'];
		log_action($_SESSION['user_id'],$action,$msg);
		///////////////////////
			@mysql_query("COMMIT");
			$content .= "<font color=green>Sale of asset successfully deleted.</font><br>";
			$resp->call('xajax_listSoldAssets');
		}
		else
		{
			@mysql_query("ROLLBACK");
			$content .= "<font color=red>ERROR: Failed to delete Sale of asset.</font><br>";
		}
	}
	else
	{
		@mysql_query("ROLLBACK");
		$content .= "<font color=red>ERROR: Failed to update bank balance.</font><br>";
	}
	$resp->assign("status", "innerHTML", $content);
	return $resp;
}

function schedule($type,$from_date,$to_date, $branch_id)
{       
        list($from_year,$from_month,$from_mday) = explode('-', $from_date);
        list($to_year,$to_month,$to_mday) = explode('-', $to_date);
        
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
	$branch = ($branch_id=='all'||$branch_id=='')?NULL:"and fx.branch_id=".$branch_id;
	//$resp->alert($from_date."-".$to_date);
	//return $resp;
	$content = '
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                                <p><h5>Run an Asset schedule</h5></p>
                                         
                            <div class="panel-body">
                            
                      <div class="form-group">
                                    
                                        <div class="col-sm-3">
                                            <label class="control-label">Account:</label>
                                            <select id="account" class="form-control">
                                            <option value="all"> All </option>';
	$sth = mysql_query("select distinct id as id, account_no, name from accounts where id in (select account_id from fixed_asset)");
	while($row = mysql_fetch_array($sth)){
		$content .= "<option value='".$row['id']."'>".$row['account_no'] ." - ".$row['name'];
	}
	$content .='</select>                                         
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="control-label">Branch:</label>
                                            <span>'.branch_rep($branch_id).'</span>
	                                          
                                        
                                    </div>
                               
                                   
                                       
                                        <div class="col-sm-3">
                                            <label class="control-label">Date range:</label>
                                        
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                           
                                    </div><div class="col-sm-3">';


                             $content.="     <label class='control-label'>.</label>
                                <input type='button' class='btn  btn-primary' value='Search'  onclick=\"xajax_schedule(getElementById('account').value, getElementById('from_date').value, getElementById('to_date').value, getElementById('branch_id').value)\">
                                </div> </div> </div>";
                                                                                        
	$content .= "                 
                           
                        </form>
                        <!--/ Form default layout -->
                    </div>  ";
                    $resp->call("createDate","from_date");
	            $resp->call("createDate","to_date"); 
                    
                    // $resp->assign("display_div", "innerHTML", $content);
	
	if($from_date == '' || $to_date ==''){
		$cont= "<font color=red>Please select the period for the fixed assets</font>";
		$resp->assign("status", "innerHTML", $cont);
		//return $resp;
	        }
	        else{
	$to_date = sprintf("%04d-%02d-%02d 23:59:59", $to_year, $to_month, $to_mday);
	$from_date = sprintf("%04d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	if (strtolower($type) == 'all')
	{
		$res = @mysql_query("select ac.id as acc_id, ac.name as acc_name, fx.id as fxid, fx.initial_value as initval, fx.date as date, fx.dep_percent as dep_rate,fx.branch_id as branch,fx.next_dep_date as next_date,fx.serialnumber as serialnumber,fx.assetgrp as grp,fx.assetref as ref from accounts ac join  fixed_asset fx on fx.account_id = ac.id where fx.id not in (select asset_id from sold_asset) and fx.date >='".$from_date."' and fx.date <='".$to_date."' ".$branch." order by fx.date desc");
	}
	/****************************
	* SPECIFY ACCOUNT TYPE
	***************************/
	else
	{
		$res = @mysql_query("select ac.id as acc_id, ac.name as acc_name, fx.id as fxid, fx.initial_value as initval, fx.date as date, fx.dep_percent as dep_rate,fx.branch_id as branch,fx.next_dep_date as next_date,fx.serialnumber as serialnumber,fx.assetgrp as grp,fx.assetref as ref from accounts ac join  fixed_asset fx on fx.account_id = ac.id where fx.id not in (select asset_id from sold_asset) and fx.date >='".$from_date."' and fx.date <='".$to_date."' and fx.account_id='".$type."' ".$branch." order by fx.date desc");
	}
	if (@mysql_num_rows($res) > 0)
	{
		$content .=
			   "<a href='list_fixed_assets.php?account=".$type."&branch_id=".$branch_id."&from_date=".$from_date."&to_date=".$to_date."' target=blank()><b>Printable Version</b></a> | <a href='list_fixed_assets.php?account=".$type."&branch_id=".$branch_id."&from_date=".$from_date."&to_date=".$to_date."&format=excel' target=blank()><b>Export Excel</b></a> | <a href='list_fixed_assets.php?account=".$type."&branch_id=".$branch_id."&from_date=".$from_date."&to_date=".$to_date."&format=word' target=blank()><b>Export Word</b></a>";
		$content .= '<div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">ASSET SCHEDULE REPORT</h3>
                            </div>';
 		$content .= '<table class="borderless table-hover" width="100%" id="table-tools">
			    <thead>
			        <th width="2%">#</th><th>Asset Description</th><th>Serial-No</th><th>Asset Group</th><th>Purchase Date</th><th>Asset Cost</th><th>Depreciation Cost</th><th>Accumulated Description</th><th>Net Book Value</th>
			        <th>Manual Depreciation</th>
			    </thead><tbody>';
		$i = 1;	
		$tot_cur_balance = 0;
		$tot_initval = 0;
		$tot_cur_amt = 0;
		while ($fxrow = @mysql_fetch_array($res))
		{
$dep_amt = (($fxrow['dep_rate']/100) * $fxrow['initval']);

			$cur = mysql_fetch_array(mysql_query("select sum(amount) amount from deppreciation where asset_id='".$fxrow['fxid']."'"));
			$cur_amt = ($cur['amount'] != NULL) ? $cur['amount'] : 0;
			$cur_balance = $fxrow['initval'] - $cur['amount'];
			//$color = ($i%2 == 0) ? "white" : "lightgrey";

			//get branch name
			if($fxrow['branch'] > 0){
			$bre = mysql_fetch_array(mysql_query("select branch_name from branch where 	branch_no = '".$fxrow['branch']."'"));
			$branch = $bre['branch_name'];
}else{
	$branch = "All";
}

			$content .= "
				    <tr>
				      <td>$i</td><td>".$fxrow['acc_name']." - ".$fxrow['ref']."</td><td>".$fxrow['serialnumber']."</td><td>".$fxrow['grp']."</td><td>".$fxrow['date']."</td><td>".number_format($fxrow['initval'], 0)."</td><td>".number_format($dep_amt,0)."</td><td>".number_format($cur_amt,0)."</td><td>".number_format($cur_balance,0)."</td><td><a href='javascript:;' onclick=\"xajax_show_dep('".$fxrow['fxid']."', '".$type."', '".$from_date."','".$to_date."', '".$branch_id."')\">Depreciate</a></td>
				    </tr>
				    ";		
			$i++;
			$tot_cur_balance += $cur_balance;
			$tot_initval += $fxrow['initval'];
			$tot_cur_amt += $cur_amt;
			$tot_dep_amt += $dep_amt;
		}
		$content .= "<tr class='headings'>
				      <th colspan=5>TOTAL</th><th>".number_format($tot_initval, 0)."</th><th colspan=1>".number_format($tot_dep_amt, 0)."</th><th colspan=1>".number_format($tot_cur_amt, 0)."</th><th colspan=1>".number_format($tot_cur_balance, 0)."</th><th></th>
				    </tr></tbody></table></div>";
	}
			
       else{
		$cont = "<font color=red>No fixed assets registered yet.</font>";
		$resp->assign("status", "innerHTML", $cont);
	return $resp;
	}
	}
	$resp->call("createTables");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

?>
