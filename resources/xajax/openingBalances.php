<?php

$xajax->registerFunction("openingBalance");
$xajax->registerFunction("insertOpeningBalance");
$xajax->registerFunction("listOpeningBalances");
$xajax->registerFunction("saccoSettings");
$xajax->registerFunction("openingBalMirror");
$xajax->registerFunction("updateOpeningBalance");
$xajax->registerFunction("deleteOpeningBalance1");
$xajax->registerFunction("deleteOpeningBalance2");
$xajax->registerFunction("getSubAccounts");
$xajax->registerFunction("getSubAccounts2");
$xajax->registerFunction("getMemberSavingsAcctsDr");
$xajax->registerFunction("getMemberSavingsAcctsCr");
$xajax->registerFunction("listTransactions");
 
function openingBalance($srcAcctId,$dstAcctId,$amt,$date,$desc,$id,$edit){
	$resp = new xajaxResponse();
if(!empty($srcAcctId) && !empty($dstAcctId)){
$srcAcc=libinc::getItemById("accounts",$srcAcctId,"id","name");
$dstAcc=libinc::getItemById("accounts",$dstAcctId,"id","name");
}
else{
$srcAcc='Choose Account';
$dstAcc='Choose Account';
}
                $parent= @mysql_query("select id, account_no, name from accounts where account_no >= 1 and account_no <= 5");	
		if (@mysql_num_rows($parent) > 0) {
		while ($acc = @mysql_fetch_array($parent))
		{					
		$parentAccts .= "<option value='".$acc['account_no']."'>".$acc['account_no']."-".$acc['name']."</option>";		
		}
		}
		else
		$parentAccts .= "<option value=''>No Accounts</option>";
				
		
/*$level1 = @mysql_query("select id, account_no, name from accounts");
	//$level1 = @mysql_query("select id, account_no, name from accounts where account_no like '".$prefix."%' or and id NOT in (select account_id from bank_account)");
	while ($level1row = @mysql_fetch_array($level1))
	{
	        
		$level2 = @mysql_query("select id, account_no, name from accounts where account_no >= '".$level1row['account_no']."01' and account_no <= '".$level1row['account_no']."99' and id NOT in (select account_id from bank_account)");	
		if (@mysql_num_rows($level2) > 0) // there's a level2 account
		{
			while ($level2row = @mysql_fetch_array($level2))
			{
			
			
				$level3 = @mysql_query("select id, account_no, name from accounts where account_no between ".$level2row['account_no']."01 and ".$level2row['account_no']."99 and id NOT in (select account_id from bank_account)");	
				if (@mysql_num_rows($level3) > 0) // there's a level3 account
				{
					while ($level3row = @mysql_fetch_array($level3))
					{
					
					$fixed_acc .= "<option value='".$level3row['id']."'>".$level3row['account_no']." -".$level3row['name']."</option>";
					}
				}
				else
				{
					$fixed_acc .= "<option value='".$level2row['id']."'>".$level2row['account_no']."-".$level2row['name']."</option>";
				}
			} // end while level2
		}
		else
		{
			$fixed_acc .= "<option value='".$level1row['id']."'>".$level1row['account_no']."-".$level1row['name']."</option>";
		}
	}	
          */          
	//if(mysql_numrows($sth) >0){ 
	
	//$bal=@mysql_fetch_array($sth);       
        $content ='<div class="row-fluid">
            
            <div class="span12">                
                <div class="widget">
                    <div class="head">
                        <div class="icon"><i class="icos-pencil2"></i></div>
                        <h2>Register Transaction</h2>
                    </div> 
                    <div id="divbg2">                       
                    <div class="block-fluid" class="span12">
                        <div class="row-form">
                            <div class="span3">
                            <span class="top title">Debit-Dr.</span>
                            <select id="parentId" name="parentId" class="form-control" onchange="xajax_getSubAccounts(this.value);"><option value="">Choose Account'.$parentAccts.'<option value="savings">6-CUSTOMER SAVING ACCOUNTS</option></select>                                 
                          </div>
               		<div class="span9" id="drAccts">
                        <div class="span3" id="drAcctsMem">
                         </div>
                         
                        <div class="span3" id="savingsAcctDr_div">
                        <input type="hidden" id="svgsAcctIdDr" value="">
                        </div>
                         </div>
                          </div>
                           
                            <div class="row-form">
                            <div class="span3">
                            <span class="top title">Credit-Cr.</span>
                            <select id="parentId" name="parentId" class="form-control" onchange="xajax_getSubAccounts2(this.value);"><option value="">Choose Account'.$parentAccts.'<option value="savings">6-CUSTOMER SAVING ACCOUNTS</option></select>            
                            </div>                   
                         
                         <div class="span9" id="crAccts" >
                         <div class="span3" id="crAcctsMem">
                         </div>                       
                        <div class="span3" id="savingsAcctCr_div">
                        <input type="hidden" id="svgsAcctIdCr" value="">
                        </div>
                        </div>
                        </div>
                        <div class="row-form"> 
                        <div class="span3">
                        <span class="top title">Amount</span>
                        <input class="form-control" onkeyup="format_as_number(this.id)" type="int" id="amt" value="'.$amt.'"/>
                        </div>                      
                         <div class="span3">                      
                         <span class="top title">Date</span>
                         <input type="int" class="form-control" id="date" name="date" value="'.$date.'" />
                        </div>
                        </div>
                        <div class="row-form">       
                        <div class="span6">
                        <span class="top title">Description</span>
                        <textarea id="desc" value="'.$desc.'">'.$desc.'</textarea></div>                                                       
                        </div>
		        </div>
                        <div class="toolbar bottom TAL">';
                        if($edit)
                            $content.='<button class="btn btn-primary" onclick=\'xajax_updateOpeningBalance(getElementById("srcAcctId").value, getElementById("dstAcctId").value, getElementById("amt").value, getElementById("date").value,getElementById("desc").value,"'.$id.'"); return false;\'>Reverse</button>'; 
                        else
                            $content.='<button class="btn btn-primary" onclick=\'xajax_insertOpeningBalance(getElementById("srcAcctId").value, getElementById("dstAcctId").value,getElementById("svgsAcctIdDr").value,getElementById("svgsAcctIdCr").value,getElementById("amt").value, getElementById("date").value,getElementById("desc").value); return false;\'>Submit</button>';
                            $content.='</div>

                    </div>
                </div>
                </div>
                
                 </div>';
                
          
	 $resp->call("createDate","date");                                           
                                                                                    		
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function insertOpeningBalance($source_id, $dest_id,$svgsAcctIdDr,$svgsAcctIdCr,$amount, $date,$desc){
	list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$amount=str_ireplace(",","",$amount);
	if($amount=='' || $date=='' || $desc==''){
		$resp->alert("Some important fields are blank");
		return $resp;
	}
	if(!$calc->isValidDate($mday, $month, $year)){
		$resp->alert("Transaction rejected! Please enter valid date");
		return $resp;
	}
	
	//$sth = mysql_query("select account_balance - ".$amount." as account_balance, min_balance from bank_account where id=$source_id");
	//$row = mysql_fetch_array($sth);
	//$date = sprintf("%d-%02d-%02d", $date);
	/*if($dest_id == $source_id){
		$resp->alert("Transaction Failed: \nYou must enter different accounts");
		return $resp;
	}
	*/
	if(empty($dest_id) && empty($source_id) && !empty($svgsAcctIdDr) && !empty($svgsAcctIdCr)){
	if($svgsAcctIdDr == $svgsAcctIdCr){
		$resp->alert("Transaction Failed: \nYou must enter different saving accounts");
		return $resp;
	}
	}
	
	if(! mysql_query("insert into non_cash set debit='".$source_id."', credit='".$dest_id."',debitedSavingsAcct='".$svgsAcctIdDr."', creditedSavingsAcct='".$svgsAcctIdCr."', amount=".$amount.", date='".$date."',description='".$desc."'")){
	      $uu=mysql_error();
		$resp->alert($uu);
		rollback();
		return $resp;   //getItemById("company",$source_id,"companyId","companyName")
				//libinc::getItemById("accounts",$_SESSION['companyId'],"id","name")
		
	}
	commit();
	$action = "insert into non_cash set debit='".$source_id."', credit='".$dest_id."',debitedSavingsAcct='".$svgsAcctIdDr."', creditedSavingsAcct='".$svgsAcctIdCr."', amount=".$amount.", date='".$date."',description='".$desc."'";
				  $msg = "Registered a non cash transaction of amount:".$amount." by debiting:";
				  if(!empty($svgsAcctIdDr))
	                          { 
                                  $acctsres = mysql_query("select m.first_name as fname,m.last_name as lname,m.mem_no as memno,a.name as accountname from member m join mem_accounts ma on m.id=ma.mem_id join savings_product p on ma.saveproduct_id=p.id join accounts a on p.account_id=a.id where ma.id=$svgsAcctIdDr");        
                                  $acct=mysql_fetch_array($acctsres);
                                                                                         
	                          $msg.=$acct['fname'].' '.$acct['lname'].'-'.$acct['memno'].'<br>'.$acct['accountname'];
	                          }
	                          else
				  $msg.=libinc::getItemById("accounts",$source_id,"id","name");
				  $msg.=" and crediting:";
				  if(!empty($svgsAcctIdCr))
	                          { 
                                  $acctsres2 = mysql_query("select m.first_name as fname,m.last_name as lname,m.mem_no as memno,a.name as accountname from member m join mem_accounts ma on m.id=ma.mem_id join savings_product p on ma.saveproduct_id=p.id join accounts a on p.account_id=a.id where ma.id=$svgsAcctIdCr");        
                                  $acct2=mysql_fetch_array($acctsres2);                                                      
	                          $msg.=$acct2['fname'].' '.$acct2['lname'].'-'.$acct2['memno'].'<br>'.$acct2['accountname'];
	                          }
	                          else
				 $msg.=libinc::getItemById("accounts",$dest_id,"id","name"); 
				 $msg.=". Description: ".$desc;
				 log_action($_SESSION['user_id'],$action,$msg);
	$resp->assign("status", "innerHTML", "<font color=green>Transaction successfull!</font>");
	$resp->call("xajax_openingBalance");
	return $resp;
}

function updateOpeningBalance($source_id, $dest_id, $amount, $date,$desc,$id){
	list($year,$month,$mday) = explode('-', $date);
	$resp = new xajaxResponse();
	$calc = new Date_Calc();
	$amount=str_ireplace(",","",$amount);
	if($source_id=='' || $dest_id=='' || $amount==''){
		$resp->alert("You may not leave any field blank ".$dest_id);
		return $resp;
	}
	if(!$calc->isValidDate($mday, $month, $year)){
		$resp->alert("Transaction rejected! Please enter valid date");
		return $resp;
	}

	if($dest_id == $source_id){
		$resp->alert("Transaction Failed: \nYou must enter different accounts");
		return $resp;
	}
	
	if(! mysql_query("update non_cash set debit=".$source_id.", credit=".$dest_id.", amount=".$amount.", date='".$date."',description='".$desc."' where id=$id")){
		$resp->alert("Transaction Failed: \n Could not update the Transaction");
		rollback();
		return $resp;
	}
	commit();
	$resp->assign("status", "innerHTML", "<font color=green>Transaction Updated!</font>");
	$resp->call("xajax_openingBalance");
	return $resp;
}

function listOpeningBalances($from_date,$to_date){
	$resp = new xajaxResponse();
	$resp->assign("status", "innerHTML", "");
		
	$content = '
                    <div class="col-md-12">
                        <!-- Form default layout -->
                        <form class="panel panel-default" action="">
                            <div class="panel-heading">
                            <h4>LIST OF OPENING BALANCES</h4>
                            </div>               
                            <div class="panel-body">';
                            
                      $content .='<div class="form-group">
                                   
                                        <div class="col-sm-6">
                                            <label class="control-label">Date range:</label>
                                           <div class="row">
                                                <div class="col-md-6"><input type="text" class="form-control" id="from_date" name="from_date" placeholder="From" /></div>
                                                <div class="col-md-6"><input type="text" class="form-control" id="to_date" name="to_date" placeholder="to" /></div>
                                            </div>
                                    </div>
                                        </div>
                                    </div>';                                  
                                                                                    
	            $content.= "<div class='panel-footer'>                                          
                                <input type='button' class='btn  btn-primary' value='Show Report'  onclick=\"xajax_listTransactions(getElementById('from_date').value,getElementById('to_date').value);return false;\">
                            </div>
                        </form>
                        <!--/ Form default layout -->
                    </div>";
                    $resp->call("createDate","from_date");
		    $resp->call("createDate","to_date");
                  //$resp->assign("display_div", "innerHTML", $content);
        
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


function deleteOpeningBalance1($id){
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	$resp->confirmCommands(1, "Do You Really Want to Delete?");
	$resp->call('xajax_deleteOpeningBalance2', $id);
	return $resp;
}

function deleteOpeningBalance2($id){
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	
	mysql_query("delete from non_cash where id='".$id."'");
	$resp->call('xajax_openingBalance', $id);
	return $resp;
}

function getSubAccounts($parent){
	$resp = new xajaxResponse();
	
		
	        if($parent=='savings'){ 
	              
	        $content.='
	        <span class="top title">Member No.</span>';
		
		//$content='<input type=int id="srcAcctId" name="srcAcctId" class="form-control">';
		$content.='<input type="int" id="mem_no" class="form-control" onblur=\'xajax_getMemberSavingsAcctsDr(this.value);\'>';
		$resp->assign("drAcctsMem", "innerHTML", $content);
	        }
	        else{
                $children= @mysql_query("select id, account_no, name from accounts where account_no like '".$parent."%' order by account_no asc");	
		if (@mysql_num_rows($children) > 0) {
		while ($acc = @mysql_fetch_array($children))
		{					
		$childAccts .= "<option value='".$acc['id']."'>".$acc['account_no']."-".$acc['name']."</option>";
		}
		}
		else
		$childAccts .= "<option value=''>No Accounts</option>";
		$content.='<div class="span6" id="drAcctsMem">';      
                $content.='<span class="top title">Accounts</span>   
		<select id="srcAcctId" name="srcAcctId" class="form-control">'.$childAccts.'</select>
		</div>';
		$content.='                       
                        <div class="span3" id="savingsAcctDr_div">
                        <input type="hidden" id="svgsAcctIdDr" value="">
		</div>';
		$resp->assign("drAccts", "innerHTML", $content);
		}
	        return $resp;
}

function getSubAccounts2($parent){
	        $resp = new xajaxResponse();
                
                if($parent=='savings'){               
	        $content.='<span class="top title">Member No.</span>';
		$content.='<input type="int" id="mem_no" class="form-control" onblur=\'xajax_getMemberSavingsAcctsCr(this.value);\'>';
		$resp->assign("crAcctsMem", "innerHTML", $content);
	        }
	        else{
                $children= @mysql_query("select id, account_no, name from accounts where account_no like '".$parent."%' order by account_no asc");	
		if (@mysql_num_rows($children) > 0) {
		while ($acc = @mysql_fetch_array($children))
		{					
		$childAccts .= "<option value='".$acc['id']."'>".$acc['account_no']."-".$acc['name']."</option>";
		}
		}
		else
		$childAccts .= "<option value=''>No Accounts</option>";
		 
                $content.='<div class="span6" id="crAcctsMem">';            
                $content.='<span class="top title">Accounts</span>
		<select id="dstAcctId" name="dstAcctId" class="form-control">'.$childAccts.'</select>
		</div>';
		$content.='                       
                        <div class="span3" id="savingsAcctCr_div">
                        <input type="hidden" id="svgsAcctIdCr" value="">
		</div>';
		$resp->assign("crAccts", "innerHTML", $content);
		}
	        return $resp;
}

function getMemberSavingsAcctsDr($mem_no){
$resp = new xajaxResponse();

        $qry=mysql_query("select id,first_name,last_name from member where mem_no='".$mem_no."'");
	if(mysql_num_rows($qry)>0){
	$row=mysql_fetch_array($qry);
	$mem_id=$row['id'];
	$name=$row['first_name']." ".$row['last_name'];
	}
	else{	
	$resp->alert("Customer Does not Exist");
	return $resp;
	}
	
$mem_res =mysql_query("select a.name as account_name, a.account_no as account_no, mem.id as memaccount_id from mem_accounts mem join savings_product p on mem.saveproduct_id=p.id join accounts a on p.account_id=a.id join member m on mem.mem_id=m.id where m.id='".$mem_id."' and p.type='free' order by a.account_no");
         
        if(mysql_num_rows($mem_res)>0){
        $content.='<span class="top title">Savings Account for (<font color="green"><b>'.$name.'</b></font>):</span>
        <input type="hidden" id="srcAcctId" value="">
        <select type=int id="svgsAcctIdDr" class="form-control" required><option value="">--Choose --</option>';                                         
	while($prd = mysql_fetch_array($mem_res)){	
	$content .= "<option value='".$prd['memaccount_id']."'>".$prd['account_name']."</option>";
	}	
	$content.= '</select>';
	//$content.= '</div></div>';
	}
	else{	
	$resp->alert("Customer Has no Savings Account");
	return $resp;
	}
	
	$resp->assign("savingsAcctDr_div", "innerHTML", $content);
	return $resp;
}

function getMemberSavingsAcctsCr($mem_no){
$resp = new xajaxResponse();

        $qry=mysql_query("select id,first_name,last_name from member where mem_no='".$mem_no."'");
	if(mysql_num_rows($qry)>0){
	$row=mysql_fetch_array($qry);
	$mem_id=$row['id'];
	$name=$row['first_name']." ".$row['last_name'];
	}
	else{	
	$resp->alert("Customer Does not Exist");
	return $resp;
	}

	$mem_res =mysql_query("select a.name as account_name, a.account_no as account_no, mem.id as memaccount_id from mem_accounts mem join savings_product p on mem.saveproduct_id=p.id join accounts a on p.account_id=a.id join member m on mem.mem_id=m.id where m.id='".$mem_id."' and p.type='free' order by a.account_no");
	         
        if(mysql_num_rows($mem_res)>0){
        $content.='<span class="top title">Savings Account for (<font color="green"><b>'.$name.'</b></font>):</span>
        <input type="hidden" id="dstAcctId" value="">
        <select type=int id="svgsAcctIdCr" class="form-control" required><option value="">--Choose --</option>';                                         
	while($prd = mysql_fetch_array($mem_res)){	
	$content .= "<option value='".$prd['memaccount_id']."'>".$prd['account_name']."</option>";
	}	
	$content.= '</select>';
	//$content.= '</div></div>';
	}
	else{	
	$resp->alert("Customer Has no Savings Account");
	return $resp;
	}
	
	$resp->assign("savingsAcctCr_div", "innerHTML", $content);
	return $resp;
}


function listTransactions($from_date,$to_date){
  $resp = new xajaxResponse();
           
		 if($from_date =="" || $to_date ==""){
			$cont ="<font color=red>Select the period for your report!</font>";
			$resp->assign("status", "innerHTML", $cont);
			return $resp;
		 }
	
                    $content.='<div class="span12">                
                    <div class="widget">
                    <div class="head">
                        <div class="icon"><i class=""></i></div>
                        <h2>Transactions Between '.libinc::formatDate($from_date).' and '.libinc::formatDate($to_date).'</h2>
                    </div> 
                                         
                    <div id="divbg2">                      
                    <div class="block-fluid">
			 <table class="table table-bordered table-striped" id="tableSortable">
	                        <thead>
	                            <tr>
	                            	<th>#</th>
	                            	<th>Account</th>
	                            	<th>Dr.</th>
	                            	<th>Cr.</th>
	                            	<th>Date</th>
	                            	<th>Description</th>
	                            		                            	
	                            </tr>
	                        </thead>
	                        <tbody>';
	                       $sth = mysql_query("select * from non_cash where date >= '".$from_date."' and date <= '".$to_date."' order by date asc");
	                       $i=1;	
	                        while($bal=@mysql_fetch_array($sth)){
	                       
	                          $content.='<tr>
	                          <td>'.$i.'</td>';
	                          if(!empty($bal['debitedSavingsAcct']))
	                          {
	                          $debitedSavingsAcct=$bal['debitedSavingsAcct']; 
                                  $acctsres = mysql_query("select m.first_name as fname,m.last_name as lname,m.mem_no as memno,a.name as accountname from member m join mem_accounts ma on m.id=ma.mem_id join savings_product p on ma.saveproduct_id=p.id join accounts a on p.account_id=a.id where ma.id=$debitedSavingsAcct");        
                                  $acct=mysql_fetch_array($acctsres);
                                                                                         
	                          $content.='<td><p>'.$acct['fname'].' '.$acct['lname'].'-'.$acct['memno'].'<br>'.$acct['accountname'].'</p>';
	                          }else
	                          $content.='<td><p>'.libinc::getItemById("accounts",$bal['debit'],"id","name").'</p>';
	                          if(!empty($bal['creditedSavingsAcct']))
	                          {
	                          $creditedSavingsAcct=$bal['creditedSavingsAcct']; 
                                  $acctsres2 = mysql_query("select m.first_name as fname,m.last_name as lname,m.mem_no as memno,a.name as accountname from member m join mem_accounts ma on m.id=ma.mem_id join savings_product p on ma.saveproduct_id=p.id join accounts a on p.account_id=a.id where ma.id=$creditedSavingsAcct");        
                                  $acct2=mysql_fetch_array($acctsres2);
                                  $content.=$acct2['fname'].' '.$acct2['lname'].'-'.$acct2['memno'].'<br>'.$acct2['accountname'];
                                  }else
                                  $content.=libinc::getItemById("accounts",$bal['credit'],"id","name");
                                  $content.='</td>
	                          <td>'.$bal['amount'].'</td>
	                          <td><p>&nbsp;</p>
	                          '.$bal['amount'].'</td>
	                          <td>'.$bal['date'].'</td>
	                          <td>'.$bal['description'].'</td>';
	                        //$content.='<td> <div class="btn-group">
                                        //$content.='<button class="btn" onclick=\'xajax_openingBalance("'.$bal['sourceAccId'].'","'.$bal['destAccId'].'","'.$bal['amount'].'","'.$bal['date'].'","'.$bal['description'].'","'.$bal['id'].'",1); return false;\'><span class="icon-pencil"></span></button>
                                       //$content.=' <button class="btn" onclick=\'xajax_deleteOpeningBalance1("'.$bal['id'].'"); return false;\'><span class="icon-remove"></span></button>
//content.='</div></td> --> 
	                       	                              
	                           $content.='</tr>';
	                          $i++;}
	                        $content.='</tbody>
	                    </table>
                    </div>
                </div>
                 </div>
              
            </div>'; 
        $resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

?>
