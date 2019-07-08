<?php
$xajax->registerFunction("postSavings");
$xajax->registerFunction("postShares");
$xajax->registerFunction("postLoans");
$xajax->registerFunction("postWithdrawals");
$xajax->registerFunction("postPayments");

function postSavings()
{       $modes="";
	$resp = new xajaxResponse();
	
	//if (strtolower($_SESSION['position']) == 'manager')
		$acc = @mysql_query("select ba.id, ac.name, ac.account_no, ba.bank from accounts ac join bank_account ba on ac.id = ba.account_id");
	/*else
		$acc = @mysql_query("select ba.id, ac.name, ac.account_no, ba.bank from accounts ac join bank_account ba on ac.id = ba.account_id where ba.id in (select bank_account_id from user_account where user_id =".$_SESSION['user_id'].")");*/
	$accts .= "<option value=''>&nbsp;</option>";
	while ($accrow = @mysql_fetch_array($acc))
	{
		$accts .= "<option value='".$accrow['id']."'>".$accrow['account_no']."-".$accrow['bank']." ".$accrow['name']."</option>";
	}
	
	$modes .= $action == 'cash'? "<option value='cash' selected>Cash/Cheque</option>" : "<option value='cash' selected>Cash/Cheque</option>";
	$modes .= $action == 'offset'? "<option value='offset' selected>Offset from Member's Savings</option>" : "<option value='offset'>Offset from Member's Savings</option>";

	$content ="";
	$content .="<form method='post' class='panel panel-default' enctype='multipart/form-data' action='transactionAccounts/postSavings'>";
$content .= '
  			  		<div class="panel-heading">
                                 		
                                                	<h4 class="panel-title">POST A SAVINGS SCHEDULE</h4>
                                               		
                                           	
                                        </div><div class="panel-body"> <div class="alert alert-warning">The excel file should have the columns: MemberNo, ReceiptNo, Shares, then the Account Codes  of the transactions <br>The file should have the etension .xls</div>';
                                                                                                           
         				$content .='<div class="form-group">
					    <label class="control-label">Organization:</label>
					    <div class="col-sm-6"><span><select class="form-control" name="company" id="company" onchange="xajax_loadBranches(this.value);" required>
					    	<option value="">Select Company</option>
					    	'.libinc::getItem("company","companyId","companyName","").'
					    </select>
					    </span></div></div>';
					     $content .='<div class="form-group">
					    <label class="control-label">Branch:</label>
					    <div class="col-sm-6"><span>
					    <div id="branchDiv">
					    
					    </div>
					    </span></div></div>';                                          
                                             $content .= '<div class="form-group">
                                             
                                            <label class="control-label">Select Dest Bank Account:</label>
                                            <div class="col-sm-6">
                                           <select name="bank_acct" class="form-control" id="bank_acct" required>'.$accts.'</select>
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Contact Person:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" class="form-control" name="contact" id="contact">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">File Ref:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" name="rcpt_no" id="rcpt_no"  class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Cheque No (Optional):</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" name="cheque_no" id="cheque_no" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Date:</label>
                                            <div class="col-sm-6">
                                           <span><input type="text" class="form-control" name="date" id="date" placeholder="'.date('Y-m-d').'"></span>
                                            </div></div>';
                                            $resp->call("createDate","date");
                                            
                                              $content .= '<div class="form-group">
                                            <label class="control-label">File:</label>
                                            <div class="col-sm-6">
                                            <div class="input-group">
                                                 <input type="text" name="fname_" id="fname_" class="form-control"  readonly>
                                                <span class="input-group-btn">
                                                    <div class="btn btn-primary btn-file">
                                                        <span class="icon iconmoon-file-3"></span> Browse <input type="file" name="fname" id="fname" required>
                                                    </div>
                                                </span>
                                            </div> </div>
                                        </div></div>'; 
                                            $content.= "<div class='panel-footer'><button type='reset' class='btn btn-default' onclick=\"xajax_add_bulk()\">Reset</button>
                                            <input type='submit' class='btn btn-primary' onclick=\"xajax_insert_bulk(getElementById('bank_acct').value, getElementById('contact').value, getElementById('rcpt_no').value, getElementById('cheque_no').value,  document.getElementById('date').value,getElementById('branch_id').value); return false;\" value='Upload' name='submit' id='submit'>";
                                            $content .= '</div></form></div>';
		    
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


function postShares()
{       $modes="";
	$resp = new xajaxResponse();
	
	//if (strtolower($_SESSION['position']) == 'manager')
		$acc = @mysql_query("select ba.id, ac.name, ac.account_no, ba.bank from accounts ac join bank_account ba on ac.id = ba.account_id");
	/*else
		$acc = @mysql_query("select ba.id, ac.name, ac.account_no, ba.bank from accounts ac join bank_account ba on ac.id = ba.account_id where ba.id in (select bank_account_id from user_account where user_id =".$_SESSION['user_id'].")");*/
	$accts .= "<option value=''>&nbsp;</option>";
	while ($accrow = @mysql_fetch_array($acc))
	{
		$accts .= "<option value='".$accrow['id']."'>".$accrow['account_no']."-".$accrow['bank']." ".$accrow['name']."</option>";
	}
	
	$modes .= $action == 'cash'? "<option value='cash' selected>Cash/Cheque</option>" : "<option value='cash' selected>Cash/Cheque</option>";
	$modes .= $action == 'offset'? "<option value='offset' selected>Offset from Member's Savings</option>" : "<option value='offset'>Offset from Member's Savings</option>";

	$content ="";
	$content .="<form method='post' class='panel panel-default' enctype='multipart/form-data' action='transactionAccounts/postShares'>";
$content .= '
  			  		<div class="panel-heading">
                                 		
                                                	<h4 class="panel-title">POST A SHARES SCHEDULE</h4>
                                               		
                                           	
                                        </div><div class="panel-body"> <div class="alert alert-warning">The excel file should have the columns: MemberNo, ReceiptNo, Shares, then the Account Codes  of the transactions <br>The file should have the etension .xls</div>';
                                                                                                           
         				$content .='<div class="form-group">
					    <label class="control-label">Organization:</label>
					    <div class="col-sm-6"><span><select class="form-control" name="company" id="company" onchange="xajax_loadBranches(this.value);" required>
					    	<option value="">Select Company</option>
					    	'.libinc::getItem("company","companyId","companyName","").'
					    </select>
					    </span></div></div>';
					     $content .='<div class="form-group">
					    <label class="control-label">Branch:</label>
					    <div class="col-sm-6"><span>
					    <div id="branchDiv">
					    
					    </div>
					    </span></div></div>';                                          
                                             $content .= '<div class="form-group">
                                             
                                            <label class="control-label">Select Dest Bank Account:</label>
                                            <div class="col-sm-6">
                                           <select name="bank_acct" class="form-control" id="bank_acct" required>'.$accts.'</select>
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Contact Person:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" class="form-control" name="contact" id="contact">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">File Ref:</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" name="rcpt_no" id="rcpt_no"  class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Cheque No (Optional):</label>
                                            <div class="col-sm-6">
                                           <input type="numeric" name="cheque_no" id="cheque_no" class="form-control">
                                            </div></div>';
                                             $content .= '<div class="form-group">
                                            <label class="control-label">Date:</label>
                                            <div class="col-sm-6">
                                           <span><input type="text" class="form-control" name="date" id="date" placeholder="'.date('Y-m-d').'"></span>
                                            </div></div>';
                                            $resp->call("createDate","date");
                                            
                                              $content .= '<div class="form-group">
                                            <label class="control-label">File:</label>
                                            <div class="col-sm-6">
                                            <div class="input-group">
                                                 <input type="text" name="fname_" id="fname_" class="form-control"  readonly>
                                                <span class="input-group-btn">
                                                    <div class="btn btn-primary btn-file">
                                                        <span class="icon iconmoon-file-3"></span> Browse <input type="file" name="fname" id="fname" required>
                                                    </div>
                                                </span>
                                            </div> </div>
                                        </div></div>'; 
                                            $content .= "<div class='panel-footer'><button type='reset' class='btn btn-default' onclick=\"xajax_add_bulk()\">Reset</button>
                                            <input type='submit' class='btn btn-primary' onclick=\"xajax_insert_bulk(getElementById('bank_acct').value, getElementById('contact').value, getElementById('rcpt_no').value, getElementById('cheque_no').value,  document.getElementById('date').value,getElementById('branch_id').value); return false;\" value='Upload' name='submit' id='submit'>";
                                            $content .= '</div></form></div>';
		    
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


function postLoans()
{       $modes="";
	$resp = new xajaxResponse();
	
	//if (strtolower($_SESSION['position']) == 'manager')
		$acc = @mysql_query("select ba.id as id, ac.name as name, ac.account_no as account_no, ba.bank as bank from accounts ac join bank_account ba on ac.id = ba.account_id");
	/*else
		$acc = @mysql_query("select ba.id, ac.name, ac.account_no, ba.bank from accounts ac join bank_account ba on ac.id = ba.account_id where ba.id in (select bank_account_id from user_account where user_id =".$_SESSION['user_id'].")");*/
	$accts .= "<option value=''>&nbsp;</option>";
	while ($accrow = @mysql_fetch_array($acc))
	{
		$accts .= "<option value='".$accrow['id']."'>".$accrow['account_no']."-".$accrow['bank']." ".$accrow['name']."</option>";
	}
	
	$modes .= $action == 'cash'? "<option value='cash' selected>Cash/Cheque</option>" : "<option value='cash' selected>Cash/Cheque</option>";
	$modes .= $action == 'offset'? "<option value='offset' selected>Offset from Member's Savings</option>" : "<option value='offset'>Offset from Member's Savings</option>";

	$content ="";
	$content .="<form method='post' class='panel panel-default' enctype='multipart/form-data' action='transactionAccounts/importLoanBalances'>";

  	$content.='<div class="row-fluid">
            <div class="span12">                                                        
                    <div class="block-fluid">
                     <div class="row-form">
                    <h3 class="panel-title">BULK LOAN POSTING</h3>
                  <div class="alert alert-warning">The excel file should have the columns: MemberNo, Product Code, Disbursement Date,Disbursed Amount, Loan Period, Remaining Balance, Remaining Period, Next Due Date,Interest Rate,Repayment Frequency <br>The file should have the etension .xls</div>
                    </div>';
                                              
                         $content.='<div class="row-form">                       
                          <div class="span5">
                            <span class="top title">Cash Account:</span>
                         <select name="cash_acct" class="form-control" id="cash_acct" required>'.$accts.'</select>
                            </div>  

                             <div class="span3">
                             <span class="top title">File:</span>
                               <input type="file" name="fname" id="fname" required>
                          
                        </div> </div> ';                                                                			 
                           
                                            $content.= "<div class='toolbar bottom TAL'>
                                            <input type='submit' class='btn btn-primary' value='Upload' name='submit' id='submit'>";
                                            $content .= '</div></form></div>';
		    
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}


function postWithdrawals()
{       $modes="";
	$resp = new xajaxResponse();
	
	//if (strtolower($_SESSION['position']) == 'manager')
		$acc = @mysql_query("select ba.id, ac.name, ac.account_no, ba.bank from accounts ac join bank_account ba on ac.id = ba.account_id");
	/*else
		$acc = @mysql_query("select ba.id, ac.name, ac.account_no, ba.bank from accounts ac join bank_account ba on ac.id = ba.account_id where ba.id in (select bank_account_id from user_account where user_id =".$_SESSION['user_id'].")");*/
	$accts .= "<option value=''>&nbsp;</option>";
	while ($accrow = @mysql_fetch_array($acc))
	{
		$accts .= "<option value='".$accrow['id']."'>".$accrow['account_no']."-".$accrow['bank']." ".$accrow['name']."</option>";
	}
	
	$modes .= $action == 'cash'? "<option value='cash' selected>Cash/Cheque</option>" : "<option value='cash' selected>Cash/Cheque</option>";
	$modes .= $action == 'offset'? "<option value='offset' selected>Offset from Member's Savings</option>" : "<option value='offset'>Offset from Member's Savings</option>";

	$content ="";
	$content .='<div class="container">';
	$content .="<form method='post'  enctype='multipart/form-data' action='transactionAccounts/importWithdrawals'>";
$content .= '
					
  			  		<div class="panel-heading">
                                 		
                                        <h4 class="panel-title">POST WITHDRAWALS</h4>
                                             		                                         	
                                        </div>
                                        <div class="panel-body"> 
                                        <div class="alert alert-warning">The excel file should have the columns: MemberNo, Amount, ReceiptNo, and Date of transaction  of the transactions <br>The file should have the etension .xls</div>';
                                                                                                                                                    
                                            $content .= '<div class="form-group">                                           
                                            <label >Choose Cash Account:</label>
                                            
                                            <select name="bank_acct" class="form-control" id="bank_acct" required>'.$accts.'</select>
                                            </div>';
                                                                                        
                                            $content .= '<div class="form-group">
                                            <label >Date:</label>
                                           
                                            <input type="int" class="form-control" name="date" id="date" required>
                                            </div>';
                                            $resp->call("createDate","date");
                                            
                                            $content .= '<div class="form-group">
                                            <label ">File:</label>
                                            
                                            <div class="input-group">
                                                 <input type="int" name="fname_" id="fname_" class="form-control">
                                                <span class="input-group-btn">
                                                    <div class="btn btn-primary btn-file">
                                                        <span class="icon iconmoon-file-3"></span> Browse <input type="file" name="fname" id="fname" required>
                                                    </div>
                                                </span>
                                            </div> </div>                                            
                                       </div>'; 
                                            $content .= "<div class='panel-footer'>
                                            <input type='submit' class='btn btn-primary' value='Upload' name='submit' id='submit'>";
                                            $content .= '</div></form></div>';
		    
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function postPayments()
{       $modes="";
	$resp = new xajaxResponse();
	
	//if (strtolower($_SESSION['position']) == 'manager')
		$acc = @mysql_query("select ba.id as id, ac.name as name, ac.account_no as account_no, ba.bank as bank from accounts ac join bank_account ba on ac.id = ba.account_id");
	/*else
		$acc = @mysql_query("select ba.id, ac.name, ac.account_no, ba.bank from accounts ac join bank_account ba on ac.id = ba.account_id where ba.id in (select bank_account_id from user_account where user_id =".$_SESSION['user_id'].")");*/
	$accts .= "<option value=''>&nbsp;</option>";
	while ($accrow = @mysql_fetch_array($acc))
	{
		$accts .= "<option value='".$accrow['id']."'>".$accrow['account_no']."-".$accrow['bank']." ".$accrow['name']."</option>";
	}
	
	$modes .= $action == 'cash'? "<option value='cash' selected>Cash/Cheque</option>" : "<option value='cash' selected>Cash/Cheque</option>";
	$modes .= $action == 'offset'? "<option value='offset' selected>Offset from Member's Savings</option>" : "<option value='offset'>Offset from Member's Savings</option>";

	$content ="";
	$content .="<form method='post' class='panel panel-default' enctype='multipart/form-data' action='transactionAccounts/importLoanPayments'>";

  	$content.='<div class="row-fluid">
            <div class="span12">                                                        
                    <div class="block-fluid">
                     <div class="row-form">
                    <h3 class="panel-title">BULK PAYMENTS POSTING</h3>
                  <div class="alert alert-warning">The excel file should have the columns: MemberNo, Loan No, Principal,Interest, ReceiptNo. <br>The file should have the etension .xls</div>
                    </div>';
                                              
                         $content.='<div class="row-form">                       
                          <div class="span3">
                            <span class="top title">Cash Account:</span>
                         <select name="cash_acct" class="form-control" id="cash_acct" required>'.$accts.'</select>
                            </div> 
                            
                          <div class="span3">
                                          <span class="top title">Date:</span>
                                           <input type="int" class="form-control" name="date" id="date">
                           </div>
                                             
				
			  <div class="span3">
                                          <span class="top title">File Ref:</span>
                                           <input type="numeric" name="file_ref" id="file_ref"  class="form-control">
                           </div>
                           
                           <div class="span3">
                                          <span class="top title">Contact Person:</span>
                                           <input type="numeric" name="contact" id="contact"  class="form-control">
                           </div> </div>';
			     $content.='<div class="row-form">
                             <div class="span3">
                             <span class="top title">File:</span>
                               <input type="file" name="fname" id="fname" required>
                          
                        </div> </div> ';                                                                			 
                           $resp->call("createDate","date");
                                            $content.= "<div class='toolbar bottom TAL'>
                                            <input type='submit' class='btn btn-primary' value='Upload' name='submit' id='submit'>";
                                            $content .= '</div></form></div>';
		    
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}
?>
