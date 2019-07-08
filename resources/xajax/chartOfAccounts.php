<?php
/*require_once("common.php");
require_once('./xajax_0.2.4/xajax.inc.php');
$xajax = new xajax();
$xajax->errorHandleron();*/

$xajax->registerFunction("manage_chart");
$xajax->registerFunction("add_account");
$xajax->registerFunction("chartofaccounts");
$xajax->registerFunction("chart_view");
$xajax->registerFunction("edit_account");
$xajax->registerFunction("insert_account");
$xajax->registerFunction("edit_account");
$xajax->registerFunction("update_account");
$xajax->registerFunction("update2_account");
$xajax->registerFunction("delete_account");
$xajax->registerFunction("delete2_account");


function manage_chart($type){
	$resp = new xajaxResponse();
		
	$content = '<div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                <h3 class="panel-title">CHART OF ACCOUNTS</h3>
                               
                               </div>';
 		$content .= '<table class="table table-striped table-bordered" id="table-tools">';
	$level1_res=mysql_query("select * from accounts where account_no like '".$type."' order by account_no");
	while($level1 =  mysql_fetch_array($level1_res)){
		$found1=0;
		$content .= "<tr><td><b>".$level1['account_no'] ." - ".$level1['name']."</b><ul>";
		 $min = $level1['account_no'] * 10;
		$max = ($level1['account_no'] * 10) + 10;
		$level2_res = mysql_query("select * from accounts where account_no > ".$min." and account_no < ".$max." order by account_no");
		while($level2 = @ mysql_fetch_array($level2_res)){
			$found2=0;
			$content .= "<li>".$level2['account_no']." - ".$level2['name']."</li>";
			$min = $level2['account_no'] * 10;
			$max = ($level2['account_no'] * 10) + 10;
			$level3_res = mysql_query("select * from accounts where account_no > ".$min." and account_no < ".$max."  order by account_no");
			//if(@mysql_numrows($level3_res) > 0)
				$content .= "<ul>";
			while($level3 = @ mysql_fetch_array($level3_res)){
				$found3 =0;
				$content .= "<li>".$level3['account_no']." - ".$level3['name']."</li>";
				//if($level3['account_no'] == '112')
				//	continue;
				$min = $level3['account_no'] * 10;
				$max = ($level3['account_no'] * 10) + 10;
				$level4_res = mysql_query("select * from accounts where account_no > ".$min." and account_no < ".$max."  order by account_no");
				//if(@mysql_numrows($level4_res) > 0)
					$content .= "<ul>";
				while($level4 = @ mysql_fetch_array($level4_res)){
					$found4=0;
					if($level4['name']=='Compulsory Savings'){
						$content .= "<li>".$level4['account_no']." - ".$level4['name']."</li>";
						continue;
					}elseif(($level4['account_no'] >=1231 && $level4['account_no'] <= 1239) || ($level4['account_no'] >=4111 && $level4['account_no'] <=4114) || $level4['account_no']=='5411' || $level4['account_no']=='4221' || $level4['account_no']=='4222' || $level4['account_no']=='4223' || $level4['name']=='Dividends Paid on Members Shares' || $level4['account_no']=='2211' || $level4['account_no']=='2212' || $level4['account_no']=='2122' || $level4['account_no']=='2121' || $level4['account_no']=='1252'){
						$content .= "<li>".$level4['account_no']." - ".$level4['name']."</li>";
						if($level4['account_no'] <>""){
							$last_no = $level4['account_no'];
							$found3=1;
						}
						if($level4['account_no'] >=4111 && $level4['account_no'] <=4114 || $level4['account_no']=='5411' || $level4['account_no']=='4221' || $level4['account_no']=='4222' || $level4['account_no']=='4223' || $level4['account_no']=='2211' || $level4['account_no']=='2212' || $level4['account_no']=='1252')
							continue;
					}else
						$content .= "<li><a href='javascript:;' onclick=\"xajax_edit_account('".$level4['id']."', '".$level4['account_no']."', '".$level4['name']."', '".$type."');\">".$level4['account_no']." - ".$level4['name']."</a></li>";
					$min = $level4['account_no'] * 100;
					$max = ($level4['account_no'] * 100) + 100;
					$level5_res = mysql_query("select * from accounts where account_no > ".$min." and account_no < ".$max."  order by account_no");
					//if(@mysql_numrows($level5_res) > 0)
						$content .= "<ul>";
					while($level5 = @ mysql_fetch_array($level5_res)){
						$found5 = 0;
						if($level5['account_no'] =='311102')
							$content .= "<li><a onclick=\"xajax_edit_account('".$level5['id']."', '".$level5['account_no']."', '".$level5['name']."', '".$type."');\">".$level5['account_no']." - ".$level5['name']."</a></li>";
						else
							$content .= "<li><a href='javascript:;' onclick=\"xajax_edit_account('".$level5['id']."', '".$level5['account_no']."', '".$level5['name']."', '".$type."');\">".$level5['account_no']." - ".$level5['name']."</a></li>";
						$min = $level5['account_no'] * 100;
						$max = ($level5['account_no'] * 100) + 100;
						$level6_res = mysql_query("select * from accounts where account_no > ".$min." and account_no < ".$max."  order by account_no");
						//if(@mysql_numrows($level6_res) > 0)
							 $content .= "<ul>";
						while($level6 = @ mysql_fetch_array($level6_res)){
							$content .= "<li><a href='javascript:;' onclick=\"xajax_edit_account('".$level6['id']."', '".$level6['account_no']."', '".$level6['name']."', '".$type."');\">".$level6['account_no']." - ".$level6['name']."</a></li>";
							if($level6['account_no'] <>""){
								$last_no = $level6['account_no'];
								$found5 =1;
							}
						} 	
						$last_no = ($found5 == 1) ? $last_no : $level5['account_no']*100;
						//if(@mysql_numrows($level6_res) > 0){
							$content .= "<li><a href='javascript:;' onclick=\"xajax_add_account('".$last_no."', '".$level5['account_no']."', '".$level5['name']."'); return false;\">Add Account</a></li>";
							$content .= "</ul>";
						//}
						if($level5['account_no'] <>""){
							$last_no = $level5['account_no'];
							$found4=1;
						}
					} 			
					$last_no = ($found4 == 1) ? $last_no : $level4['account_no']*100;
					//if(@mysql_numrows($level5_res) > 0){
						$content .= "<li><a href='javascript:;' onclick=\"xajax_add_account('".$last_no."', '".$level4['account_no']."', '".$level4['name']."'); return false;\">Add Account</a></li>";
						$content .= "</ul>";
					//}
					if($level4['account_no'] <>""){
						$last_no = $level4['account_no'];
						$found3=1;
					}
				} 	
				$last_no = ($found3 == 1) ? $last_no : $level3['account_no']*10;
					$content .= "<li><a href='javascript:;' onclick=\"xajax_add_account('".$last_no."', '".$level3['account_no']."', '".$level3['name']."'); return false;\">Add Account</a></li>";
					$content .= "</ul>";
				if($level3['account_no'] <>""){
					$last_no = $level3['account_no'];
					$found2=1;
				}
			} 	
			$last_no = ($found2 == 1) ? $last_no : $level2['account_no']*10;
			//if(@mysql_numrows($level3_res) > 0){
				$content .= "<li><a href='javascript:;' onclick=\"xajax_add_account('".$last_no."', '".$level2['account_no']."', '".$level2['name']."'); return false;\">Add Account</a></li>";
				$content .= "</ul>";	
			//}
			if($level2['account_no'] <>""){
				$last_no = $level2['account_no'];
				$found1=1;
			}
		} 
		$last_no = ($found1 == 1) ? $last_no : $level1['account_no']*10;
		$content .= "<li><a href='javascript:;' onclick=\"xajax_add_account('".$last_no."', '".$level1['account_no']."', '".$level1['name']."'); return false;\">Add Account</a></li>";
		if(@mysql_numrows($level2_res) > 0){
			$content .= "</ul>";
		}
		$content .= "</td></tr>";
	}
	$content .= "</table></div>";
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}
	
//ADD ACCOUNT
function add_account($last_no, $category, $cat_name){
	$resp = new xajaxResponse();
		
	$min = ($last_no >= 111101) ? ($category * 1000) + 1 : ($category * 100) + 1;
	$now = $min;
	$sth = mysql_query("select * from accounts where account_no >= ".$min." and account_no <= ".$last_no." order by account_no");
	while($row = mysql_fetch_array($sth)){
		for($i=$min; $i <= $last_no; $i++){
			$check = mysql_query("select * from accounts where account_no='".$i."'");
			if(mysql_numrows($check) == 0){
				$num = $i;
				break;
			}
		}
	}
	
	if((($min +99) == ($last_no + 1) && $last_no < 111101) || (($min +999) == ($last_no + 1) && $last_no >= 111101)){
		$next_no = '';
		for($i=$min; $i <= $last_no; $i++){
			$sth = mysql_query("select * from accounts where account_no='".$i."'");
			if(mysql_numrows($sth) == 0){
				$next_no = $i;
				break;
			}
		}
		if($next_no == ''){
			$resp->alert("You can only add a sub-account, you have used up all the account numbers for this category");
			return $resp;
		}
	}elseif($now != $min)
		$next_no = $now;
	else
		$next_no = $last_no + 1;
	
	$content ="<div class='col-md-12'><form method='post' class='panel panel-default'>";
$content .= '
  			  		<div class="panel-heading">
                                 		
                                                	<h4 class="panel-title">CREATE NEW ACCOUNT UNDER '.$category.' - '.$cat_name.'</h4>
                                        </div><div class="panel-body">';
                                        
        $content .= '<div class="form-group">
                                            <label class="col-sm-3 control-label">Account Name:</label>
                                            <div class="col-sm-6"><input type="text" id="account_name" name="account_name" class="form-control"><input type=hidden id="next_no" name="next_no" value="'.$next_no.'"/>                             </div> ';

                                        $content.=    "<button type='reset' class='btn btn-default' onclick=\"xajax_manage_chart('".substr($category, 0, 1)."')\">Back</button>
                                            <button type='button' class='btn btn-primary'   onclick=\"xajax_insert_account(getElementById('account_name').value, getElementById('next_no').value); return false;\">Save</button>                                           
                                            </div></div>";
                                            $content .= '</div></form></div>';
                                             
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


function edit_account($account_id, $account_no, $account_name, $type){
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}	
	$content ="<div class='col-md-12'><form method='post' class='panel panel-default'>";
$content .= '
  			  		<div class="panel-heading">
                                 		
                                                	<h4 class="panel-title">EDIT ACCOUNT '.$account_no.'</h4>
                                               		
                                           	 </div>
                                     <div class="panel-body">';
                                        
        $content .= '<div class="form-group">
                                            <label class=" col-md-2 control-label">Account Name:</label>
                                            <div class="col-sm-6"><input type=hidden id="account_id"  name="account_id" value="'.$account_id.'"/><input type="hidden" id="account_no" name="account_no" value="'.$account_no.'"/><input type="text" id="account_name" name="account_name" value="'.$account_name.'" class="form-control"><input type=hidden id="next_no" name="next_no" value="" /></div>                                            
                                            '; 
                                            
        $content .= "<button type='button' class='btn btn-default' onclick=\"xajax_manage_chart('".$type."')\">Back</button>
        
      <button type='reset' class='btn btn-default' onclick=\"xajax_edit_account('".$account_id."', '".$account_no."', '".$account_name."', '".$type."')\">Reset</button>
      
      <button type='button' class='btn btn-default' onclick=\"xajax_delete_account('".$account_id."', '".$account_no."', '".$type."')\">Delete</button>
                                            <button type='button' class='btn btn-primary'   onclick=\"xajax_update_account('".$account_id."', getElementById('account_name').value, '".$type."')\">Update</button>";
                                            $content .= '</form></div>';
                                        
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

//INSERT INTO DATABASE
function insert_account($account_name, $next_no){
	$resp = new xajaxResponse();
	if($account_name == '')
		$resp->alert("You may not leave Account Name blank");
	else{
		$sth = mysql_query("select * from accounts where (name ='".strtoupper($account_name)."' or account_no='".$next_no."')");
		if(mysql_numrows($sth) > 0)
			$resp->alert("This Account already exists for this branch");
		else{
			mysql_query("insert into accounts set name='".$account_name."', account_no='".$next_no."', branch_no='".$_SESSION['branch_no']."'");
			$msg = "Registered Account: ".$account_name;
		log_action($_SESSION['user_id'],$action,$msg);
			$resp->assign("status", "innerHTML", "<font color=red>Account created!</font>");
		}
	}
	return $resp;
}

//UPDATE ACCOUNT
function update_account($account_id, $account_name, $type){
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$sth = mysql_query("select * from accounts where name='".$account_name."' and id<>'".$account_id."'");
	if(mysql_numrows($sth) >0){
		$resp->alert("Update not done! Account name already exists");
	}else{
		$resp->confirmCommands(1, "Do you really want to update?");
		$resp->call('xajax_update2_account', $account_id, $account_name, $type);
	}
	return $resp;
}

//CONFIRM UPDATE OF ACCOUNT
function update2_account($account_id, $account_name, $type){
	$resp = new xajaxResponse();
	if(CAP_Session::get('edit')<>1){
	$resp->alert('You Dont Have Permissions to Edit');
	return $resp;
	}
	$former = mysql_fetch_array(mysql_query("select * from accounts where id='".$account_id."'"));
	if(! mysql_query("update accounts set name='".$account_name."' where id='".$account_id."'"))
		$resp->alert("ERROR: Account could not be updated");
	else{
		$action = "update accounts set name='".$account_name."' where id='".$account_id."'";
		//mysql_query("insert into logs set user_id='".$_SESSION['user_id']."', time=NOW(),  action='".$_SESSION['name'].":  ".mysql_escape_string($action)."'");

		$msg = "Updated Account:".$former['account_no']." and changed name from:  ".$former['name']." to: ".$account_name;
		log_action($_SESSION['user_id'],$action,$msg);
		$resp->assign("status", "innerHTML", "<center><font color=red>Account updated</font></center>");
	}
	return $resp;
}

//DELETE ACCOUNT
function delete_account($account_id, $account_no, $type){
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	$sth1 = mysql_query("select * from accounts where account_no like '%".$account_no."%' and id<>'".$account_id."'");
	$income_res = mysql_query("select * from other_income where account_id='".$account_id."'");
	$expense_res = mysql_query("select * from expense where account_id='".$account_id."'");
	$payable_res = mysql_query("select * from payable where account_id='".$account_id."'");
	$receivable_res = mysql_query("select * from receivable where account_id='".$account_id."'");
	$fixed_res = mysql_query("select * from fixed_asset where account_id='".$account_id."'");
	$invest_res = mysql_query("select * from investments where account_id='".$account_id."'");
	$loan_res = mysql_query("select * from loan_product where account_id='".$account_id."'");
	$savings_res = mysql_query("select * from savings_product where account_id='".$account_id."'");
	if(mysql_numrows($sth1) > 0)
		$resp->alert("Cant delete this account, \nit has sub accounts");
	elseif(mysql_numrows($income_res) > 0)
		$resp->alert("Cant delete this account, \nsome income has been deposited on it");
	elseif(mysql_numrows($expense_res) >0)
		$resp->alert("Cant delete this account, \nsome expenses have been deposited on it");
	elseif(mysql_numrows($payable_res) >0)
		$resp->alert("Cant delete this account, \nsome payables have been registered under it");
	elseif(mysql_numrows($receivable_res) > 0)
		$resp->AddgAlert("Cant delete this account, \nsome receivables have been registered under it");
	elseif(mysql_numrows($fixed_res) >0)
		$resp->alert("Cant delete this account, \nsome fixed assets have been registered under it");
	elseif(mysql_numrows($invest_res) >0)
		$resp->alert("Cant delete this account, \nsome investments have been registered under it");
	elseif(mysql_numrows($loan_res) >0)
		$resp->alert("Cant delete this account, \nsome loan product has been registered under it");
	elseif(mysql_numrows($savings_res) >0)
		$resp->alert("Cant delete this account, \nsome savings product has been registered under it");
	else{
		$resp->confirmCommands(1, "Do you really want to delete?");
		$resp->call('xajax_delete2_account', $account_id, $type);
	}
	return $resp;
}

//CONFIRM DELETION OF ACCOUNT 
function delete2_account($account_id, $type){
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	$former = mysql_fetch_array(mysql_query("select * from accounts where id='".$account_id."'"));
	if(! mysql_query("delete from accounts where id='".$account_id."'"))
		$resp->alert("ERROR: Could not delete the account");
	else{
		$action = "delete from accounts where id='".$account_id."'";
	//	mysql_query("insert into logs set user_id='".$_SESSION['user_id']."', time=NOW(),  action='".$_SESSION['name'].":  ".mysql_escape_string($action)."'");
	$msg = "Deleted Account:".$former['account_no']." and of name:  ".$former['name'];
		log_action($_SESSION['user_id'],$action,$msg);
		$resp->assign("status", "innerHTML", "<font color=red>Account deleted!</font>");
	}
	return $resp;
}

?>
