<?php
$xajax->registerFunction("asAtForm");
$xajax->registerFunction("loan_tracking");
$xajax->registerFunction("portifolioAtRisk");
$xajax->registerFunction("rangeForm");
$xajax->registerFunction("listApplicants");
$xajax->registerFunction("listApproved");
$xajax->registerFunction("listDisbursed");
$xajax->registerFunction("ageingReport");
$xajax->registerFunction("arrearsReport");
$xajax->registerFunction("loanNo");
$xajax->registerFunction("loanNoAsAt");
$xajax->registerFunction("ledger");
$xajax->registerFunction("listRepayments");
$xajax->registerFunction("duePayments");
$xajax->registerFunction("repaymentReport");
$xajax->registerFunction("outStandingLoansReport");
$xajax->registerFunction("delete_application");
$xajax->registerFunction("delete2_application");
$xajax->registerFunction("portifolio_report");
$xajax->registerFunction("listWaivedLoans");

function loanNo($case){ 
$resp = new xajaxResponse();
		$content.='<div class="row-fluid">
                  <div class="span12">                                                        
                    <div class="block-fluid">
                     <div class="row-form">';
                  if($case=="rdisbursement")
                    $content.='<h3 class="panel-title">LOAN DISBURSEMENT REVERSAL</h3>'; 
                  if($case=="waive-interest")
                    $content.='<h3 class="panel-title">WAIVE OFF INTEREST</h3>';                 
                     $content.='</div>
		<div class="row-form">		                                                  
                       <div class="span3">
                            <span class="top title">Loan Ref No:</span>
                             <input type="int" id="loan_no" class="form-control" >
			      </div>			      
                                </div>'; 
                        
                         if($case=="ledger"){                                                           
                        $content.='<div class="toolbar bottom TAL">
                            <button type="button" class="btn btn-primary" onclick=\'xajax_ledger(getElementById("loan_no").value);\'>Search</button>
                        </div>';   
                        }
                        
                        if($case=="rdisbursement"){                                                           
                        $content.='<div class="toolbar bottom TAL">
                            <button type="button" class="btn btn-primary" onclick=\'xajax_rDisburse(getElementById("loan_no").value);\'>Reverse</button>
                        </div>';   
                        }  
                        
                        if($case=="waive-interest"){                                                           
                        $content.='<div class="toolbar bottom TAL">
                  <button type="button" class="btn btn-primary" onclick=\'xajax_waiveInterest(getElementById("loan_no").value);\'>Enter</button>
                        </div>';   
                        }                 

                    $content.='</div></div>
            </div>
            </div>
                </div>
            </div>
            </div>';
    	
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
}

function loanNoAsAt($report){ 
$resp = new xajaxResponse();

	         $content.='<div class="row-fluid">
                  <div class="span12">                                                        
                    <div class="block-fluid">
                     <div class="row-form">';
                     if($report=='ledger')
                    $content.='<h3 class="panel-title">LOANS LEDGER</h3>';                  
                     $content.='</div>'; 
                     
		$content.='<div class="row-form">		                                                  
                       <div class="span3">
                            <span class="top title">Loan Ref No:</span>
                             <input type="int" id="loan_no" class="form-control" >
			      </div>			      
                              
                       <div class="span3">
                           <span class="top title">Reporting Date:</span>
                           <input type="int" class="form-control" id="date" name="date" placeholder="As at" /> 
                           </div>                                           
                            </div>';                
                                                     
                         if($report=="ledger"){                                                           
                        $content.='<div class="toolbar bottom TAL">
                            <button type="button" class="btn btn-primary" onclick=\'xajax_ledger(getElementById("loan_no").value,getElementById("date").value);\'>Search</button>
                        </div>';   
                        }                

                    $content.='</div>
                </div>
            </div>
            </div>';
    	$resp->call("createDate","date");
	$resp->assign("display_div", "innerHTML", $content);
	return $resp;
} 

function rangeForm($report){
$resp = new xajaxResponse();

  $content.='<div class="row-fluid">
            <div class="span12">                                                        
                    <div class="block-fluid">
                     <div class="row-form">';
                     if($report=='listApplicants')
                     $content.='<h3 class="panel-title">SEARCH FOR LOAN APPLICATIONS</h3>';
                     if($report=='listApproved')
                     $content.='<h3 class="panel-title">SEARCH FOR APPROVED LOANS</h3>';
                     if($report=='listDisbursed')
                     $content.='<h3 class="panel-title">SEARCH FOR DISBURSED LOANS</h3>';
                     if($report=='listDisbursedReport')
                     $content.='<h3 class="panel-title">GENERATE A LOANS DISBURSEMENT REPORT</h3>';
                     if($report=='pendingApproval')
                     $content.='<h3 class="panel-title">SEARCH FOR LOANS PENDING APPROVAL</h3>';
                     if($report=='pendingApprovalReport')
                     $content.='<h3 class="panel-title">GENERATE A LOANS PENDING APPROVAL REPORT</h3>';
                     if($report=='pendingDisbursement')
                     $content.='<h3 class="panel-title">SEARCH FOR LOANS PENDING DISBURSEMENT</h3>';
                     if($report=='pendingDisbursementReport')
                     $content.='<h3 class="panel-title">GENERATE A LOANS PENDING DISBURSEMENT REPORT</h3>';
                     if($report=='paymentsReport')
                     $content.='<h3 class="panel-title">GENERATE A LOAN PAYMENTS REPORT</h3>';
                     if($report=='listPayments')
                     $content.='<h3 class="panel-title">GENERATE A LIST OF PAYMENTS REPORT</h3>';
                     if($report=='repaymentReport')
                     $content.='<h3 class="panel-title">GENERATE A LOAN REPAYMENT REPORT</h3>';
                     if($report=='duePayments')
                     $content.='<h3 class="panel-title">GENERATE A DUE PAYMENTS REPORT</h3>';
                     if($report=='waivedLoans')
                     $content.='<h3 class="panel-title">SEARCH FOR WAIVED LOANS</h3>';
                                    
                     $content.='</div>';                         
                     $content .= '<div class="row-form">
                                          <div class="span3">
                                            <span class="top title">Member No:</span>
                                            <input type=int id="cust_no" value="All" class="form-control">
                                            </div>
                                             <div class="span3">
                                            <span class="top title">Loan Product:</span>
                                            <select id="product_id" class="form-control"><option value="">All';
  $prod_res = mysql_query("select a.name as account_name, a.account_no as account_no,p.id as prod_id from loan_product p join accounts a on p.account_id=a.id order by a.name, a.account_no");
  while($prod = @ mysql_fetch_array($prod_res)){
    $content .= "<option value='".$prod['prod_id']."'>".$prod['account_no']." - ".$prod['account_name'];
  }
  $content .='</select>
                                            </div>
                                            <div class="span3">
                                            <span class="top title">Branch:</span>
                                            <span>'.branch_rep($branch_id).'</span>
                                            </div>
                                            <div class="span3">
                                            <span class="top title">Report Format:</span>
                                            <select id="report_format" class="form-control">
<option value="detail">Detailed<option value="summary">Summarized</select> 
                                            </div></div>
                                            <div class="row-form">
                                            <div class="span3">
                                            <span class="top title">Loan Officer:</span>
                                            <select id="officer_id" class="form-control"><option value="0">All';
  $officer_res = mysql_query("select * from employees order by firstName, lastName");
  while($officer = mysql_fetch_array($officer_res)){
    $content .= "<option value='".$officer['employeeId']."'>".$officer['firstName']." ".$officer['lastName'];
  }
  $content .='</select>
                                             </div>
                                             <div class="span6">
                                            <span class="top title" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date Range:</span>
                                           <div class="span3"><input type="int" class="form-control" id="fdate" placeholder="From" /></div>
                                           <div class="span3"><input type="int" class="form-control" id="tdate" placeholder="To" /> </div>
                                            </div>                                           
                                             </div>';                                           
$content .="<div class='panel-footer'>";
 if($report=='listApplicants')
 $content .="<button type='button' class='btn  btn-primary' onclick=\"xajax_listApplicants(getElementById('cust_no').value, getElementById('product_id').value, getElementById('officer_id').value, getElementById('fdate').value, getElementById('tdate').value,getElementById('report_format').value, getElementById('branch_id').value,'listApplicants');\">Show Report</button>"; 
 if($report=='listApproved')
 $content .="<button type='button' class='btn  btn-primary' onclick=\"xajax_listApproved(getElementById('cust_no').value, getElementById('product_id').value, getElementById('officer_id').value, getElementById('fdate').value, getElementById('tdate').value,getElementById('report_format').value, getElementById('branch_id').value);\">Show Report</button>"; 
 if($report=='listDisbursed')
 $content .="<button type='button' class='btn  btn-primary' onclick=\"xajax_listDisbursed(getElementById('cust_no').value, getElementById('product_id').value, getElementById('officer_id').value, getElementById('fdate').value, getElementById('tdate').value,getElementById('report_format').value, getElementById('branch_id').value);\">Show Report</button>";
 if($report=='listDisbursedReport')
 $content .="<button type='button' class='btn  btn-primary' onclick=\"xajax_listDisbursed(getElementById('cust_no').value, getElementById('product_id').value, getElementById('officer_id').value, getElementById('fdate').value, getElementById('tdate').value,getElementById('report_format').value, getElementById('branch_id').value,'report');\">Show Report</button>";
 if($report=='pendingApprovalReport')
 $content .="<button type='button' class='btn  btn-primary' onclick=\"xajax_listApplicants(getElementById('cust_no').value, getElementById('product_id').value, getElementById('officer_id').value, getElementById('fdate').value, getElementById('tdate').value,getElementById('report_format').value, getElementById('branch_id').value,'pendingApproval');\">Show Report</button>";
 if($report=='pendingApproval')
 $content .="<button type='button' class='btn  btn-primary' onclick=\"xajax_pendingApproval(getElementById('cust_no').value, getElementById('product_id').value, getElementById('officer_id').value, getElementById('fdate').value, getElementById('tdate').value,getElementById('report_format').value, getElementById('branch_id').value,'pendingApproval');\">Show Report</button>"; 
 if($report=='pendingDisbursement')
 $content .="<button type='button' class='btn  btn-primary' onclick=\"xajax_pendingDisbursement(getElementById('cust_no').value, getElementById('product_id').value, getElementById('officer_id').value, getElementById('fdate').value, getElementById('tdate').value,getElementById('report_format').value, getElementById('branch_id').value,'pendingDisbursement');\">Show Report</button>";
 if($report=='pendingDisbursementReport')
 $content .="<button type='button' class='btn  btn-primary' onclick=\"xajax_listApproved(getElementById('cust_no').value, getElementById('product_id').value, getElementById('officer_id').value, getElementById('fdate').value, getElementById('tdate').value,getElementById('report_format').value, getElementById('branch_id').value,'pendingDisbursement');\">Show Report</button>";
 if($report=='paymentsReport')
 $content .="<button type='button' class='btn  btn-primary' onclick=\"xajax_listRepayments(getElementById('cust_no').value, getElementById('product_id').value, getElementById('officer_id').value, getElementById('fdate').value, getElementById('tdate').value,getElementById('report_format').value, getElementById('branch_id').value,'report');\">Show Report</button>";
 if($report=='listPayments')
 $content .="<button type='button' class='btn  btn-primary' onclick=\"xajax_listRepayments(getElementById('cust_no').value, getElementById('product_id').value, getElementById('officer_id').value, getElementById('fdate').value, getElementById('tdate').value,getElementById('report_format').value, getElementById('branch_id').value);\">Show Report</button>";
 if($report=='repaymentReport')
 $content .="<button type='button' class='btn  btn-primary' onclick=\"xajax_repaymentReport(getElementById('cust_no').value, getElementById('product_id').value, getElementById('officer_id').value, getElementById('fdate').value, getElementById('tdate').value,getElementById('report_format').value, getElementById('branch_id').value,'pendingDisbursement');\">Show Report</button>";
 if($report=='duePayments')
$content .="<button type='button' class='btn  btn-primary' onclick=\"xajax_duePayments(getElementById('cust_no').value, getElementById('product_id').value, getElementById('officer_id').value,getElementById('fdate').value, getElementById('tdate').value,getElementById('report_format').value, getElementById('branch_id').value);\">Show Report</button>"; 
if($report=='waivedLoans')
$content .="<button type='button' class='btn  btn-primary' onclick=\"xajax_listWaivedLoans(getElementById('cust_no').value, getElementById('product_id').value, getElementById('officer_id').value, getElementById('fdate').value, getElementById('tdate').value,getElementById('report_format').value, getElementById('branch_id').value);\">Show Report</button>";
                                                                                                                       
     $content .= '</div></div></form>';
     
  $resp->call("createDate","fdate");
  $resp->call("createDate","tdate");
  $resp->assign("display_div", "innerHTML", $content);
  return $resp;

}

function asAtForm($report){

$resp = new xajaxResponse();
  $content.='<div class="row-fluid">
            <div class="span12">                                                        
                    <div class="block-fluid">
                     <div class="row-form">';
                    if($report=='loanTracking')
                    $content.='<h3 class="panel-title">LOAN TRACKING</h3>';
                    if($report=='PAR')
                    $content.='<h3 class="panel-title">PORTIFOLIO AT RISK</h3>';
                    if($report=='ageing')
                    $content.='<h3 class="panel-title">AGEING PRINCIPLE ARREARS</h3>';
                    if($report=='arrears')
                    $content.='<h3 class="panel-title">ARREARS</h3>';
                    if($report=='outstandingLoans')
                    $content.='<h3 class="panel-title">OUTSTANDING LOANS</h3>';
                    if($report=='outstandingLoansReport')
                    $content.='<h3 class="panel-title">OUTSTANDING LOANS REPORT</h3>';
                   
                   
                     $content.='</div>';                         
                     $content .= '<div class="row-form">
                                          <div class="span3">
                                            <span class="top title">Member No:</span>
                                            <input type=int id="cust_no" value="All" class="form-control">
                                            </div>
                                             <div class="span3">
                                            <span class="top title">Loan Product:</span>
                                            <select id="product_id" class="form-control"><option value="">All';
  $prod_res = mysql_query("select a.name as account_name, a.account_no as account_no,p.id as prod_id from loan_product p join accounts a on p.account_id=a.id order by a.name, a.account_no");
  while($prod = @ mysql_fetch_array($prod_res)){
    $content .= "<option value='".$prod['prod_id']."'>".$prod['account_no']." - ".$prod['account_name'];
  }
  $content .='</select>
                                            </div>
                                            <div class="span3">
                                            <span class="top title">Branch:</span>
                                            <span>'.branch_rep($branch_id).'</span>
                                            </div>
                                            <div class="span3">
                                            <span class="top title">Report Format:</span>
                                            <select id="report_format" class="form-control">
<option value="detail">Detailed<option value="summary">Summarized</select> 
                                            </div></div>
                                            <div class="row-form">
                                            <div class="span3">
                                            <span class="top title">Loan Officer:</span>
                                            <select id="officer_id" class="form-control"><option value="0">All';
  $officer_res = mysql_query("select * from employees order by firstName, lastName");
  while($officer = mysql_fetch_array($officer_res)){
    $content .= "<option value='".$officer['employeeId']."'>".$officer['firstName']." ".$officer['lastName'];
  }
  $content .='</select>
                                             </div>
                                             <div class="span3">
                                            <span class="top title">Reporting Date:</span>
                                           <input type="int" class="form-control" id="date" name="date" placeholder="As at" /> 
                                            </div>                                           
                                             </div>';                                           
$content.="<div class='panel-footer'>";
 if($report=='loanTracking')
 $content .="<button type='button' class='btn  btn-primary' onclick=\"xajax_loan_tracking(getElementById('cust_no').value, getElementById('product_id').value, getElementById('officer_id').value,  getElementById('date').value,getElementById('report_format').value, getElementById('branch_id').value);\">Show Report</button>"; 
 if($report=='PAR')
 $content .="<button type='button' class='btn  btn-primary' onclick=\"xajax_portifolioAtRisk(getElementById('cust_no').value, getElementById('product_id').value, getElementById('officer_id').value,  getElementById('date').value,getElementById('report_format').value, getElementById('branch_id').value);\">Show Report</button>";
  if($report=='ageing')
 $content .="<button type='button' class='btn  btn-primary' onclick=\"xajax_ageingReport(getElementById('cust_no').value, getElementById('product_id').value, getElementById('officer_id').value,  getElementById('date').value,getElementById('report_format').value, getElementById('branch_id').value);\">Show Report</button>";
 if($report=='arrears')
 $content .="<button type='button' class='btn  btn-primary' onclick=\"xajax_arrearsReport(getElementById('cust_no').value, getElementById('product_id').value, getElementById('officer_id').value,  getElementById('date').value,getElementById('report_format').value, getElementById('branch_id').value);\">Show Report</button>";
 if($report=='outstandingLoans')
 $content .="<button type='button' class='btn  btn-primary' onclick=\"xajax_outStandingLoans(getElementById('cust_no').value, getElementById('product_id').value, getElementById('officer_id').value,  getElementById('date').value,getElementById('report_format').value, getElementById('branch_id').value);\">Show Report</button>"; 
 if($report=='outstandingLoansReport')
 $content .="<button type='button' class='btn  btn-primary' onclick=\"xajax_outStandingLoansReport(getElementById('cust_no').value, getElementById('product_id').value, getElementById('officer_id').value,  getElementById('date').value,getElementById('report_format').value, getElementById('branch_id').value);\">Show Report</button>";
                                                                                                                            
   $content .= '</div></div></form>';
     
  $resp->call("createDate","date");
  $resp->assign("display_div", "innerHTML", $content);
  return $resp;

}

function listApplicants($cust_no, $product, $loan_officer,$from_date,$to_date,$report_format,$branch_id,$status){
  //list($from_year,$from_month,$from_mday) = explode('-', $from_date);       
  $resp = new xajaxResponse();
  $calc = new Date_Calc();
  //$resp->assign("status", "innerHTML", "");

if($from_date && $to_date){
 list($year,$month,$day) = explode('-', $date);
   $branch= ($branch_id=='all'||$branch_id=='')?NULL:"and m.branch_id=".$branch_id;
  if ($date =='') {
    $tim = time();
    $today = date("Y-m-d h:i:s",$tim);
//$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
 $date = $today;
  }
 // else{
  else{
    //$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
  $date = sprintf("%04d-%02d-%02d 23:59:59", $year, $month, $day);
}
 
  $mem_no = ($cust_no == 'All') ? "" : "and m.mem_no='".$cust_no."'";
  $product = ($product == '') ? "" : "and p.id=".$product;

  if($loan_officer >0)
    $officer = "e.employeeId='".$loan_officer."'";
  else
    $officer = "e.employeeId > 0";
    
      //get officer
               $officen = mysql_query("select * from employees where employeeId='".$loan_officer."'");
               $off = mysql_fetch_assoc($officen);
               $offnum = mysql_num_rows($officen);
if($offnum == 1){ $offcer_didplay =  $off['firstName'].' '.$off['lastName'];} else{$offcer_didplay = 'All Officers';}

//get branch
$bran = mysql_query("select * from branch where branch_no='".$branch_id."'");
               $bra = mysql_fetch_assoc($bran);
               $branum = mysql_num_rows($bran);
if($branum == 1){ 
$branch_didplay =  $bra['branch_name'];} else{$branch_didplay = 'All branches';
}

//get loan product

if($product == ""){ $product_didplay = "All Products";} else{$product_didplay = $product;}

//report name

if(isset($status)){
   if($status=='pendingApproval')
   $reportname = "LOANS PENDING APPROVAL - REPORT";
   if($status=='listApplicants')
   $reportname = "LIST OF LOAN APPLICATIONS";
}
  
//get company name.
$com = mysql_query("select * from users where userId = '".CAP_Session::get('userId')."'");
$comp = mysql_fetch_array($com);
//var_dump($comp);
if($com){
$companyd = $comp['companyId'];

$companyb = $comp['branch'];
$se = mysql_query("select * from company where companyId='".$companyd."'");
$sel = mysql_fetch_assoc($se);
if($se){
$compa = $sel['companyName'];
$address = "Physical Address: ".$sel['country']." ".$sel['city']." ".$sel['address1']." ".$sel['address2']." | Telephone: ".$sel['telephone']." | Email: ".$sel['email'];
}else{
    $compa = "unknown2".mysql_error();
}

}else{
$companyd = "unknown".mysql_error();    
}

if($status<>"listApplicants"){

               $content .=' <div class="panel panel-default">

              <div class="panel-body">

              <div class="green">

               <label><b> '.$reportname.'</b>&nbsp;&nbsp;Period : '.libinc::formatDate($from_date) .' - '.libinc::formatDate($to_date) .'</label> </div>
               
               </div>       
            </div>';
           }
         else
         $content .=' <div class="panel panel-default">

              <div class="panel-body">

              <div class="green">

               <label><b>'.$reportname.' BETWEEN '.libinc::formatDate($from_date) .' AND '.libinc::formatDate($to_date) .'</b></label> </div>';

 $qry=mysql_query("select m.first_name as first_name, m.last_name as last_name, m.sex as sex, m.id as mem_id,m.mem_no as mem_no,l.date as date,l.amount as amount,l.id as loan_id,p.id as product_id from member m join loan_applic l on m.id = l.mem_id join employees e on l.officer_id=e.employeeId join loan_product p on p.id=l.product_id where ".$officer." ".$mem_no." ".$product." ".$branch." and l.date >='".$from_date."' and l.date <='".$to_date."'");

  if(@ mysql_numrows($qry) > 0){ 
   $content.="<input type='button' class='pull-right' href=\"#\" onClick =\"$('#table-app').tableExport({type:'excel',escape:'false'});\" value='Excel'>";
      $content.="<input type='button' onclick=\"generate({mime:'jpeg',table_id:'table-app', filename:'".$reportname.".pdf', title:'".$reportname."', subtitle:'', logo:''})\" class='pull-right' value='PDF'><br><br>"; 
    $content .= '<table class="borderless table-hover" id="table-app"  width=100%>';
$content .= '<thead><th>#</th><th>LOAN No.</th><th>NAME</th><th>MEMBER NO.</th><th>APPLICATION DATE</th><th>PRODUCT</th><th>AMOUNT</th><th>STATUS</th><th>ACTION</th></thead><tbody>';
 
    $i=1;
   while($row=mysql_fetch_array($qry)){

   if($report_format == "detail"){
  // list($y,$m,$d)=explode("-",$row['date']);
  // $date=$d.'/'.$m.'/'.$y;
   if(isset($status)){
   if($status=='pendingApproval'){
   if(libinc::loanStatus($row['loan_id'])=="Pending Approval")
   $content .= "<tr><td>".$i."</td><td>".$row['loan_id']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".libinc::formatDate($row['date'])."</td><td>".libinc::getItemById("accounts",libinc::getItemById("loan_product",$row['product_id'],"id","account_id"), "id","name")."</td><td>".number_format($row['amount'])."</td><td>".libinc::loanStatus($row['loan_id'])."</td></tr>";
   }
   if($status=='listApplicants'){
   if(libinc::loanStatus($row['loan_id'])=='Pending Approval')
   $delete ='<button type="button" class="btn btn-primary" onclick=\'xajax_delete_application("'.$row['loan_id'].'");\'>Delete</button>';
   else
   $delete='';
   $content .= "<tr><td>".$i."</td><td>".$row['loan_id']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".libinc::formatDate($row['date'])."</td><td>".libinc::getItemById("accounts",libinc::getItemById("loan_product",$row['product_id'],"id","account_id"), "id","name")."</td><td>".number_format($row['amount'])."</td><td>".libinc::loanStatus($row['loan_id'])."</td><td>".$delete."</td></tr>";
   }
   }
   else
   $content .= "<tr><td>".$i."</td><td>".$row['loan_id']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".libinc::formatDate($row['date'])."</td><td>".libinc::getItemById("accounts",libinc::getItemById("loan_product",$row['product_id'],"id","account_id"), "id","name")."</td><td>".number_format($row['amount'])."</td><td>".libinc::loanStatus($row['loan_id'])."</td></tr>";
   }
   $amt_sub_total += $row['amount']; 
    $i++;
}  

$content .= "<tfooter><tr><th>TOTAL</th><th></th><th></th><th></th><th></th><th></th><th><b>".number_format($amt_sub_total)."</b></th><th></th><th></th></tr></tfooter></tbody></table></div>";
 $resp->call("createTableJs");
 
}
else{
   $content = "<font color=red>No Results Found</font>";
  }
}

  $resp->assign("display_div", "innerHTML", $content);
  return $resp;
}

function listApproved($cust_no, $product, $loan_officer,$from_date,$to_date,$report_format,$branch_id,$status){
  //list($from_year,$from_month,$from_mday) = explode('-', $from_date);       
  $resp = new xajaxResponse();
  $calc = new Date_Calc();
  //$resp->assign("status", "innerHTML", "");

if($from_date && $to_date){
 list($year,$month,$day) = explode('-', $date);
   $branch= ($branch_id=='all'||$branch_id=='')?NULL:"and m.branch_id=".$branch_id;
  if ($date =='') {
    $tim = time();
    $today = date("Y-m-d h:i:s",$tim);
//$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
 $date = $today;
  }
 // else{
  else{
    //$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
  $date = sprintf("%04d-%02d-%02d 23:59:59", $year, $month, $day);
}
 
  $mem_no = ($cust_no == 'All') ? "" : "and m.mem_no='".$cust_no."'";
  $product = ($product == '') ? "" : "and p.id=".$product;

  if($loan_officer >0)
    $officer = "e.employeeId='".$loan_officer."'";
  else
    $officer = "e.employeeId > 0";
   
      //get officer
               $officen = mysql_query("select * from employees where employeeId='".$loan_officer."'");
               $off = mysql_fetch_assoc($officen);
               $offnum = mysql_num_rows($officen);
if($offnum == 1){ $offcer_didplay =  $off['firstName'].' '.$off['lastName'];} else{$offcer_didplay = 'All Officers';}

//get branch
$bran = mysql_query("select * from branch where branch_no='".$branch_id."'");
               $bra = mysql_fetch_assoc($bran);
               $branum = mysql_num_rows($bran);
if($branum == 1){ 
$branch_didplay =  $bra['branch_name'];} else{$branch_didplay = 'All branches';
}

//get loan product

if($product == ""){ $product_didplay = "All Products";} else{$product_didplay = $product;}

//report name
if(isset($status)){
   if($status=='pendingDisbursement')
   $reportname = "LOANS PENDING DISBURSEMENT - REPORT";
}
else
$reportname = "LIST OF APPROVED LOANS"; 

//get company name.
$com = mysql_query("select * from users where userId = '".CAP_Session::get('userId')."'");
$comp = mysql_fetch_array($com);
//var_dump($comp);
if($com){
$companyd = $comp['companyId'];

$companyb = $comp['branch'];
$se = mysql_query("select * from company where companyId='".$companyd."'");
$sel = mysql_fetch_assoc($se);
if($se){
$compa = $sel['companyName'];
$address = "Physical Address: ".$sel['country']." ".$sel['city']." ".$sel['address1']." ".$sel['address2']." | Telephone: ".$sel['telephone']." | Email: ".$sel['email'];
}else{
    $compa = "unknown2".mysql_error();
}

}else{
$companyd = "unknown".mysql_error();    
}

if($status=='pendingDisbursement'){

               $content .=' <div class="panel panel-default">

              <div class="panel-body">

              <div class="green">

               <label><b> '.$reportname.'</b>&nbsp;&nbsp;Period : '.libinc::formatDate($from_date) .' - '.libinc::formatDate($to_date) .'</label> </div>
             
           </div>
            </div>';
            }
            else
            $content .=' <div class="panel panel-default">

              <div class="panel-body">

              <div class="green">

               <label><b>LIST OF LOANS APPROVED BETWEEN '.libinc::formatDate($from_date) .' AND '.libinc::formatDate($to_date) .'</b></label> </div
               </div>       
          </div>';

 $qry=mysql_query("select m.first_name as first_name, m.last_name as last_name, m.sex as sex, m.id as mem_id,m.mem_no as mem_no,ap.date as date,ap.amount as amount,ap.loan_period as period,l.id as loan_id,p.id as product_id from member m join loan_applic l on m.id = l.mem_id join approval ap on ap.applic_id=l.id join employees e on ap.officer_id=e.employeeId join loan_product p on p.id=l.product_id where ".$officer." ".$mem_no." ".$product." ".$branch." and ap.date >='".$from_date."' and ap.date <='".$to_date."'");

  if(@ mysql_numrows($qry) > 0){  
   $content.="<input type='button' class='pull-right' href=\"#\" onClick =\"$('#table-pendisb').tableExport({type:'excel',escape:'false'});\" value='Excel'>";
      $content.="<input type='button' onclick=\"generate({mime:'jpeg',table_id:'table-pendisb', filename:'".$reportname.".pdf', title:'".$reportname."', subtitle:'', logo:''})\" class='pull-right' value='PDF'><br><br>"; 
      
    $content .= '<table class="borderless table-hover" id="table-pendisb" width=100%>';
$content .= '<thead><th>#</th><th>LOAN No.</th><th>NAME</th><th>MEMBER NO.</th><th>APPLICATION DATE</th><th>PRODUCT</th><th>PERIOD</th><th>AMOUNT</th><th>STATUS</th></thead><tbody>';
 
    $i=1;
   while($row=mysql_fetch_array($qry)){

   if($report_format == "detail"){
  // list($y,$m,$d)=explode("-",$row['date']);
 //$date=$d.'/'.$m.'/'.$y;
   if(isset($status)){
   if($status=='pendingDisbursement')
   if(libinc::loanStatus($row['loan_id'])=="Pending Disbursement")
   $content .= "<tr><td>".$i."</td><td>".$row['loan_id']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".libinc::formatDate($row['date'])."</td><td>".libinc::getItemById("accounts",libinc::getItemById("loan_product",$row['product_id'],"id","account_id"), "id","name")."</td><td>".$row['period']."</td><td>".number_format($row['amount'],2)."</td><td>".libinc::loanStatus($row['loan_id'])."</td></tr>";
   }
   else
   $content .= "<tr><td>".$i."</td><td>".$row['loan_id']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".libinc::formatDate($row['date'])."</td><td>".libinc::getItemById("accounts",libinc::getItemById("loan_product",$row['product_id'],"id","account_id"), "id","name")."</td><td>".$row['period']."</td><td>".number_format($row['amount'],2)."</td><td>".libinc::loanStatus($row['loan_id'])."</td></tr>";
   }
   $amt_sub_total += $row['amount']; 
    $i++;
}  

$content .= "<tfooter><tr><th>TOTAL</th><th></th><th></th><th></th><th></th><th></th><th><b>".number_format($amt_sub_total)."</b></th><th></th></tr></tfooter></tbody></table></div>";
 $resp->call("createTableJs");
 
}
else{
   $content = "<font color=red>No Results Found</font>";
  }
}

  $resp->assign("display_div", "innerHTML", $content);
  return $resp;
}


function listdisbursed($cust_no, $product, $loan_officer,$from_date,$to_date,$report_format,$branch_id,$status){
  //list($from_year,$from_month,$from_mday) = explode('-', $from_date);       
  $resp = new xajaxResponse();
  $calc = new Date_Calc();
  //$resp->assign("status", "innerHTML", "");

if($from_date && $to_date){
 //list($year,$month,$day) = explode('-', $date);
   $branch= ($branch_id=='all'||$branch_id=='')?NULL:"and m.branch_id=".$branch_id;

  $mem_no = ($cust_no == 'All') ? "" : "and m.mem_no='".$cust_no."'";
  $product = ($product == '') ? "" : "and p.id=".$product;

  if($loan_officer >0)
    $officer = "e.employeeId='".$loan_officer."'";
  else
    $officer = "e.employeeId > 0";
    
      //get officer
               $officen = mysql_query("select * from employees where employeeId='".$loan_officer."'");
               $off = mysql_fetch_assoc($officen);
               $offnum = mysql_num_rows($officen);
if($offnum == 1){ $offcer_didplay =  $off['firstName'].' '.$off['lastName'];} else{$offcer_didplay = 'All Officers';}

//get branch
$bran = mysql_query("select * from branch where branch_no='".$branch_id."'");
               $bra = mysql_fetch_assoc($bran);
               $branum = mysql_num_rows($bran);
if($branum == 1){ 
$branch_didplay =  $bra['branch_name'];} else{$branch_didplay = 'All branches';
}

//get loan product

if($product == ""){ $product_didplay = "All Products";} else{$product_didplay = $product;}

//get company name.
$com = mysql_query("select * from users where userId = '".CAP_Session::get('userId')."'");
$comp = mysql_fetch_array($com);
//var_dump($comp);
if($com){
$companyd = $comp['companyId'];

$companyb = $comp['branch'];
$se = mysql_query("select * from company where companyId='".$companyd."'");
$sel = mysql_fetch_assoc($se);
if($se){
$compa = $sel['companyName'];
$address = "Physical Address: ".$sel['country']." ".$sel['city']." ".$sel['address1']." ".$sel['address2']." | Telephone: ".$sel['telephone']." | Email: ".$sel['email'];
}else{
    $compa = "unknown2".mysql_error();
}

}else{
$companyd = "unknown".mysql_error();    
}

if($status=='report'){
               $content .=' <div class="panel panel-default">

              <div class="panel-body">

              <div class="green">

               <label><b> DISBURSEMENT REPORT</b><span class="small right">PERIOD '.libinc::formatDate($from_date) .' - '.libinc::formatDate($to_date) .'</span></label> </div>

            </div>       
          </div>';
            }
            
            else
            $content .=' <div class="panel panel-default">

              <div class="panel-body">

              <div class="green">

               <label><b>LIST OF LOANS DISBURSED BETWEEN '.libinc::formatDate($from_date) .' AND '.libinc::formatDate($to_date) .'</b></label> </div
               </div>       
          </div>';

 $qry=mysql_query("select m.first_name as first_name, m.last_name as last_name, m.sex as sex, m.id as mem_id,m.mem_no as mem_no,d.date as date,ap.amount as amount,ap.loan_period as period,l.id as loan_id,p.id as product_id,d.approval_id as approval_id from member m join loan_applic l on m.id = l.mem_id join loan_product p on p.id=l.product_id join approval ap on ap.applic_id =l.id join employees e on ap.officer_id=e.employeeId  join disbursed d on d.approval_id=ap.id where ".$officer." ".$mem_no." ".$product." ".$branch." and d.date >='".$from_date."' and d.date <='".$to_date."' order by l.id asc");

  if(@ mysql_numrows($qry) > 0){  
  $content.="<input type='button' class='pull-right' href=\"#\" onClick =\"$('#table-disb').tableExport({type:'excel',escape:'false'});\" value='Excel'>";
  $content.="<input type='button' onclick=\"generate({mime:'jpeg',table_id:'table-disb', filename:'Disbursement Report.pdf', title:'DISBURSEMENT REPORT', subtitle:'', logo:''})\" class='pull-right' value='PDF'><br><br>"; 
   
    $content .= '<table class="borderless table-hover" id="table-disb"  width=100%>';
$content .= '<thead><th>#</th><th>LOAN No.</th><th>NAME</th><th>MEMBER NO.</th><th>DISBURSEMENT DATE</th><th>PRODUCT</th><th>PERIOD</th><th>AMOUNT</th><th>SCHEDULE</th></th></thead><tbody>';
 
    $i=1;
   while($row=mysql_fetch_array($qry)){
   
   if($report_format == "detail"){
  //list($y,$m,$d)=explode("-",$row['date']);
   //$date=$d.'/'.$m.'/'.$y;
   $schedule = "<a href='export/schedule/".$row['approval_id']."/".$row['loan_id']."/schedule' target='_blank'>View/Print</a>";
   $content .= "<tr><td>".$i."</td><td>".$row['loan_id']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".libinc::formatDate($row['date'])."</td><td>".libinc::getItemById("accounts",libinc::getItemById("loan_product",$row['product_id'],"id","account_id"), "id","name")."</td><td>".$row['period']."</td><td>".number_format($row['amount'])."</td><td>".$schedule."</td></tr>";
   }
   $amt_sub_total += $row['amount']; 
    $i++;
}  

$content .= "<tfooter><tr><th>TOTAL</th><th></th><th></th><th></th><th><th></th></th><th></th><th></th><th><b>".number_format($amt_sub_total)."</b></th><th></th></tr></tfooter></tbody></table></div>";
 $resp->call("createTableJs");
 
}
else{
   $content = "<font color=red>No Results Found</font>";
  }
}

  $resp->assign("display_div", "innerHTML", $content);
  return $resp;
}

function loan_tracking($cust_no, $product, $loan_officer,$date,$report_format,$branch_id){
  //list($from_year,$from_month,$from_mday) = explode('-', $from_date);
       
  $resp = new xajaxResponse();
  $calc = new Date_Calc();
  //$resp->assign("status", "innerHTML", "");

if($date){
 list($year,$month,$day) = explode('-', $date);
   $branch= ($branch_id=='all'||$branch_id=='')?NULL:"and m.branch_id=".$branch_id;
  if ($date =='') {
    $tim = time();
    $today = date("Y-m-d h:i:s",$tim);
//$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
 $date = $today;
  }
 // else{
  else{
    //$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
  $date = sprintf("%04d-%02d-%02d 23:59:59", $year, $month, $day);
}
 
  $mem_no = ($cust_no == 'All') ? "" : "and m.mem_no='".$cust_no."'";
  $product = ($product == '') ? "" : "and p.id=".$product;

  if($loan_officer >0)
    $officer = "e.employeeId='".$loan_officer."'";
  else
    $officer = "e.employeeId > 0";
  
      //get officer
               $officen = mysql_query("select * from employees where employeeId='".$loan_officer."'");
               $off = mysql_fetch_assoc($officen);
               $offnum = mysql_num_rows($officen);
if($offnum == 1){ $offcer_didplay =  $off['firstName'].' '.$off['lastName'];} else{$offcer_didplay = 'All Officers';}

//get branch
$bran = mysql_query("select * from branch where branch_no='".$branch_id."'");
               $bra = mysql_fetch_assoc($bran);
               $branum = mysql_num_rows($bran);
if($branum == 1){ 
$branch_didplay =  $bra['branch_name'];} else{$branch_didplay = 'All branches';
}

//get loan product

if($product == ""){ $product_didplay = "All Products";} else{$product_didplay = $product;}

//report name
$reportname = "LOAN TRACKING REPORT"; 

//get company name.
$com = mysql_query("select * from users where userId = '".CAP_Session::get('userId')."'");
$comp = mysql_fetch_array($com);
//var_dump($comp);
if($com){
$companyd = $comp['companyId'];

$companyb = $comp['branch'];
$se = mysql_query("select * from company where companyId='".$companyd."'");
$sel = mysql_fetch_assoc($se);
if($se){
$compa = $sel['companyName'];
$address = "Physical Address: ".$sel['country']." ".$sel['city']." ".$sel['address1']." ".$sel['address2']." | Telephone: ".$sel['telephone']." | Email: ".$sel['email'];
}else{
    $compa = "unknown2".mysql_error();
}

}else{
$companyd = "unknown".mysql_error();    
}

               $content .=' <div class="panel panel-default">

              <div class="panel-body">

              <div class="green">

               <label><b> '.$reportname.'&nbsp;&nbsp;As At '.$date.'</b></label> </div>
		</div>
            </div>';

$qry=mysql_query("select m.first_name as first_name, m.last_name as last_name, m.sex as sex, m.id as mem_id,m.mem_no as mem_no,d.date as date,d.amount as amount,l.id as loan_id from member m join loan_applic l on m.id = l.mem_id join approval ap on l.id = ap.applic_id join disbursed d on d.approval_id=ap.id join employees e on ap.officer_id=e.employeeId join loan_product p on p.id=l.product_id where ".$officer." ".$mem_no." ".$product." ".$branch."");

  if(@ mysql_numrows($qry) > 0){

   $content.="<input type='button' class='pull-right' href=\"#\" onClick =\"$('#table-tracking').tableExport({type:'excel',escape:'false'});\" value='Excel'>   
   <input type='button' onclick=\"generate({mime:'jpeg',table_id:'table-tracking', filename:'".$reportname.".pdf', title:'".$reportname."', subtitle:'', logo:''})\" class='pull-right' value='PDF'><br><br>";
    $content .= '<table class="borderless table-hover" id="table-tracking" width=100%>';

$content .= '<thead><th>#</th><th>LOAN No.</th><th>NAME</th><th>MEMBER NO.</th><th>DISBURSEMENT DATE</th><th>DISBURSED AMOUNT</th><th>PRINCIPAL DUE</th><th>INTEREST DUE</th><th>PRINCIPAL PAID</th><th>INTEREST PAID</th><th>%GE PRINCIPAL</th><th>%GE INTEREST</th><th>OUTSTANDING BALANCE</th></thead><tbody>';
    $date=$year.'-'.$month.'-'.$day;
    $percentPrincPaid=0;
    $percentIntPaid=0;
    $percentPrincPaid_sub_total=0;
    $percentIntPaid_sub_total=0;
    $amt_sub_total =0; 
    $bal_sub_total =0;   
    $princ_due_sub_total =0;
    $int_paid_sub_total =0;
    $int_due_sub_total =0;
    $paid_amt_sub_total =0;
    $outstandingBal_sub_total =0;
    $i=1;
   while($row=mysql_fetch_array($qry)){
  
   $principalDue=libinc::principalDue($row['loan_id'],$date);
   $interestDue=libinc::interestDue($row['loan_id'],$date);
   $principalPaid=libinc::principalPaid($row['loan_id'],$date);
   $InterestPaid=libinc::interestPaid($row['loan_id'],$date);
   $outstandingBal=libinc::loanBalance($row['loan_id'],$date);
   $principalArrears=libinc::principalArrears($row['loan_id'],$date);
   $interestArrears=libinc::interestArrears($row['loan_id'],$date); 
     
    if(number_format($principalArrears) < 0)
    $principalArrears=0;
    if(number_format($interestArrears) < 0)
    $interestArrears=0;
    $principalDue=$principalDue+$principalArrears;
    $interestDue=$InterestPaid+$interestArrears;
    if($principalPaid >0 && ($principalPaid <=$principalDue))
    $percentPrincPaid=($principalPaid/$principalDue)*100;
    if($principalPaid > $principalDue)
    $percentPrincPaid=100;
    if($InterestPaid >0 && ($InterestPaid <=$interestDue));
    $percentIntPaid=($InterestPaid/$interestDue)*100;
    if($InterestPaid > $interestDue)
    $percentIntPaid=100;
    if($report_format == "detail"){
    //list($y,$m,$d)=explode("-",$row['date']);
   //$date=$d.'/'.$m.'/'.$y;
   $content .= "<tr><td>".$i."</td><td>".$row['loan_id']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".libinc::formatDate($row['date'])."</td><td>".number_format($row['amount'])."</td><td>".number_format($principalDue)."</td><td>".number_format($interestDue)."</td><td>".number_format($principalPaid)."</td><td>".number_format($InterestPaid)."</td><td>".number_format($percentPrincPaid, 2)."</td><td>".number_format($percentIntPaid, 2)."</td><td>".number_format($outstandingBal)."</td></tr>";
   }
   $amt_sub_total += $row['amount']; 
    $bal_sub_total += $outstandingBal; 
    
    $princ_due_sub_total += $principalDue; 
    $int_paid_sub_total += $InterestPaid; 
    $int_due_sub_total += $interestDue; 
    $paid_amt_sub_total += $principalPaid;
    $outstandingBal_sub_total += $outstandingBal;
    $i++;
   }  
    
    //$tot_amt_sub_total += $total_due;
      
    if($paid_amt_sub_total >0 && ($paid_amt_sub_total <=$princ_due_sub_total))
    $percentPrincPaid_sub_total=($paid_amt_sub_total/$princ_due_sub_total)*100;
    if($paid_amt_sub_total > $princ_due_sub_total)
    $percentPrincPaid_sub_total=100;
    if($int_paid_sub_total >0 && ($int_paid_sub_total <=$int_due_sub_total));
    $percentIntPaid_sub_total=($int_paid_sub_total/$int_due_sub_total)*100;
    if($int_paid_sub_total > $int_due_sub_total)
    $percentIntPaid_sub_total=100;
   
 
 
/*   if($report_format == "summary"){
  $content .= "<tfooter><tr><th>TOTAL</th><th></th><th></th><th></th><th></th><th><b>".number_format($amt_sub_total)."</b></th><th><b>".number_format($princ_due_sub_total)."</b></th><th><b>".number_format($int_due_sub_total)."</b></th><th><b>".number_format($paid_amt_sub_total)."</b></th><th><b>".number_format($int_paid_sub_total)."</b></th><th><b>".number_format($percentPrincPaid_sub_total,2)."</b></th><th><b>".number_format($percentIntPaid_sub_total,2)."</b></th><th><b>".number_format($outstandingBal_sub_total)."</b></th></tr></tfooter></tbody></table></div>";
 $resp->call("createTableJs");
 $resp->assign("display_div", "innerHTML", $content);
  return $resp;
}  

else
{*/
  $content .= "<tfooter><tr><th>TOTAL</th><th></th><th></th><th></th><th></th><th><b>".number_format($amt_sub_total)."</b></th><th><b>".number_format($princ_due_sub_total)."</b></th><th><b>".number_format($int_due_sub_total)."</b></th><th><b>".number_format($paid_amt_sub_total)."</b></th><th><b>".number_format($int_paid_sub_total)."</b></th><th><b>".number_format($percentPrincPaid_sub_total,2)."</b></th><th><b>".number_format($percentIntPaid_sub_total,2)."</b></th><th><b>".number_format($outstandingBal_sub_total)."</b></th></tr></tfooter></tbody></table></div>";
 $resp->call("createTableJs");
 // }
//}
 
}
else{
   $content = "<font color=red>No Results Found</font>";
  }
}

  $resp->assign("display_div", "innerHTML", $content);
  return $resp;
}


function outStandingLoansReport($cust_no, $product, $loan_officer,$date,$report_format,$branch_id){
  //list($from_year,$from_month,$from_mday) = explode('-', $from_date);
       
  $resp = new xajaxResponse();
  $calc = new Date_Calc();

if($date){
 list($year,$month,$day) = explode('-', $date);
   $branch= ($branch_id=='all'||$branch_id=='')?NULL:"and m.branch_id=".$branch_id;
  if ($date =='') {
    $tim = time();
    $today = date("Y-m-d h:i:s",$tim);
//$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
 $date = $today;
  }
 // else{
  else{
    //$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
  $date = sprintf("%04d-%02d-%02d 23:59:59", $year, $month, $day);
}
 
  $mem_no = ($cust_no == 'All') ? "" : "and m.mem_no='".$cust_no."'";
  $product = ($product == '') ? "" : "and p.id=".$product;

  if($loan_officer >0)
    $officer = "e.employeeId='".$loan_officer."'";
  else
    $officer = "e.employeeId > 0";
    
      //get officer
               $officen = mysql_query("select * from employees where employeeId='".$loan_officer."'");
               $off = mysql_fetch_assoc($officen);
               $offnum = mysql_num_rows($officen);
if($offnum == 1){ $offcer_didplay =  $off['firstName'].' '.$off['lastName'];} else{$offcer_didplay = 'All Officers';}

//get branch
$bran = mysql_query("select * from branch where branch_no='".$branch_id."'");
               $bra = mysql_fetch_assoc($bran);
               $branum = mysql_num_rows($bran);
if($branum == 1){ 
$branch_didplay =  $bra['branch_name'];} else{$branch_didplay = 'All branches';
}

//get loan productF

if($product == ""){ $product_didplay = "All Products";} else{$product_didplay = $product;}

//report name
$reportname = "OUTSTANDING LOANS REPORT"; 

//get company name.
$com = mysql_query("select * from users where userId = '".CAP_Session::get('userId')."'");
$comp = mysql_fetch_array($com);
//var_dump($comp);
if($com){
$companyd = $comp['companyId'];

$companyb = $comp['branch'];
$se = mysql_query("select * from company where companyId='".$companyd."'");
$sel = mysql_fetch_assoc($se);
if($se){
$compa = $sel['companyName'];
$address = "Physical Address: ".$sel['country']." ".$sel['city']." ".$sel['address1']." ".$sel['address2']." | Telephone: ".$sel['telephone']." | Email: ".$sel['email'];
}else{
    $compa = "unknown2".mysql_error();
}

}else{
$companyd = "unknown".mysql_error();    
}

               $content .=' <div class="panel panel-default">

              <div class="panel-body">

              <div class="green">

               <label><b> '.$reportname.'&nbsp;&nbsp;As At '.$date.'</b></label> </div>
     
         </div>
            </div>';

$qry=mysql_query("select m.first_name as first_name, m.last_name as last_name, m.sex as sex, m.id as mem_id,m.mem_no as mem_no,d.date as date,d.amount as amount,l.id as loan_id from member m join loan_applic l on m.id = l.mem_id join approval ap on l.id = ap.applic_id join disbursed d on d.approval_id=ap.id join employees e on ap.officer_id=e.employeeId join loan_product p on p.id=l.product_id where ".$officer." ".$mem_no." ".$product." ".$branch." and d.date <='".$date."'");

  if(@ mysql_numrows($qry) > 0){
  $content.="<input type='button' class='pull-right' href=\"#\" onClick =\"$('#table-out').tableExport({type:'excel',escape:'false'});\" value='Excel'>";
    $content.="<input type='button' onclick=\"generate({mime:'jpeg',table_id:'table-out', filename:'".$reportname.".pdf', title:'".$reportname."', subtitle:'', logo:''})\" class='pull-right' value='PDF'><br><br>";
     
    $content .= '<table class="borderless table-hover" id="table-out" width=100%>';

$content .= '<thead><th>#</th><th>LOAN No.</th><th>NAME</th><th>MEMBER NO.</th><th>DISBURSEMENT DATE</th><th>DISBURSED AMOUNT</th><th>TOTAL PAID</th><th>PRINCIPAL BAL</th><th>INTEREST BAL</th><th>PENALTY BAL</th><th>TOTAL BAL</th><th>TOTAL ARREARS</th></thead><tbody>';
    
    $percentPrincPaid=0;
    $percentIntPaid=0;
    $percentPrincPaid_sub_total=0;
    $percentIntPaid_sub_total=0;
    $amt_sub_total =0; 
    $bal_sub_total =0;   
    $princ_due_sub_total =0;
    $int_paid_sub_total =0;
    $int_due_sub_total =0;
    $paid_amt_sub_total =0;
    $outstandingBal_sub_total =0;
    $i=1;
   while($row=mysql_fetch_array($qry)){
 
   $principalDue=libinc::principalDue($row['loan_id'],$date);
   $interestDue=libinc::interestDue($row['loan_id'],$date);
   $principalPaid=libinc::principalPaid($row['loan_id'],$date);
   $InterestPaid=libinc::interestPaid($row['loan_id'],$date);
   $InterestBal=libinc::interestBalance($row['loan_id'],$date);
   $principalBal=libinc::loanBalance($row['loan_id'],$date);
   $penaltyBal=libinc::penaltyDue($row['loan_id'],$date);
   //$penaltyPaid=libinc::penaltyPaid($row['loan_id'],$date);   
   $totalPaid=$principalPaid+$InterestPaid;
   $totalBal=$principalBal+$InterestBal+$penaltyBal;
   $principalArrears=libinc::principalArrears($row['loan_id'],$date); 
   $interestArrears=libinc::interestArrears($row['loan_id'],$date);
   $daysInArrears=libinc::daysInArrears($row['loan_id'],$date);
   
   $penaltyDue=libinc::penaltyDue($row['loan_id'],$date);
   if(number_format($principalBal) < 0){ 
     $principalBal=0;
    }   
   if(number_format($InterestBal) < 0){ 
     $InterestBal=0;
    }
   if(number_format($penaltyBal) < 0){ 
     $penaltyBal=0;
    }
   if(number_format($principalArrears) < 0){ 
     $principalArrears=0;
    }   
   if(number_format($interestArrears) < 0){ 
     $interestArrears=0;
    }
   if(number_format($penaltyDue) < 0){ 
     $penaltyDue=0;
    }
   $penaltyArrears=$penaltyDue;
   $totalArrears=$principalArrears+$interestArrears+$penaltyArrears;
   
    if($report_format == "detail"){
    if(number_format($totalBal) > 0 && number_format($totalArrears) > 0){
   $content .= "<tr><td>".$i."</td><td>".$row['loan_id']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".libinc::formatDate($row['date'])."</td><td>".number_format($row['amount'])."</td><td>".number_format($totalPaid)."</td><td>".number_format($principalBal)."</td><td>".number_format($InterestBal)."</td><td>".number_format($penaltyBal)."</td><td>".number_format($totalBal)."</td><td>".number_format($totalArrears)."</td></tr>";
   
    $amtSubtotal += $row['amount']; 
    $totalPaidSubtotal += $totalPaid;    
    $principalBalSubtotal += $principalBal; 
    $interestBalSubtotal += $InterestBal; 
    $penaltyBalSubtotal += $penaltyBal; 
    $totalBalSubtotal += $totalBal;
    $totalArrearsSubtotal += $totalArrears;
    $i++;
   }
   }  
   }
  $content .= "<tfooter><tr><th>TOTAL</th><th></th><th></th><th></th><th></th><th><b>".number_format($amtSubtotal)."</b></th><th><b>".number_format( $totalPaidSubtotal)."</b></th><th><b>".number_format($principalBalSubtotal)."</b></th><th><b>".number_format($interestBalSubtotal)."</b></th><th><b>".number_format( $penaltyBalSubtotal)."</b></th><th><b>".number_format($totalBalSubtotal)."</b></th><th><b>".number_format($totalArrearsSubtotal)."</b></th></tr></tfooter></tbody></table></div>";
 $resp->call("createTableJs");
 // }
//}
 
}
else{
   $content = "<font color=red>No Results Found</font>";
  }
}

  $resp->assign("display_div", "innerHTML", $content);
  return $resp;
}

function duePayments($cust_no, $product, $loan_officer,$from_date,$to_date,$report_format,$branch_id){
  //list($from_year,$from_month,$from_mday) = explode('-', $from_date);
       
  $resp = new xajaxResponse();
  $calc = new Date_Calc();
  //$resp->assign("status", "innerHTML", "");

if($from_date && $to_date){
 list($year,$month,$day) = explode('-', $date);
   $branch= ($branch_id=='all'||$branch_id=='')?NULL:"and m.branch_id=".$branch_id;
  if ($date =='') {
    $tim = time();
    $today = date("Y-m-d h:i:s",$tim);
//$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
 $date = $today;
  }
 // else{
  else{
    //$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
  $date = sprintf("%04d-%02d-%02d 23:59:59", $year, $month, $day);
}
 
  $mem_no = ($cust_no == 'All') ? "" : "and m.mem_no='".$cust_no."'";
  $product = ($product == '') ? "" : "and p.id=".$product;

  if($loan_officer >0)
    $officer = "e.employeeId='".$loan_officer."'";
  else
    $officer = "e.employeeId > 0";
    
      //get officer
               $officen = mysql_query("select * from employees where employeeId='".$loan_officer."'");
               $off = mysql_fetch_assoc($officen);
               $offnum = mysql_num_rows($officen);
if($offnum == 1){ $offcer_didplay =  $off['firstName'].' '.$off['lastName'];} else{$offcer_didplay = 'All Officers';}

//get branch
$bran = mysql_query("select * from branch where branch_no='".$branch_id."'");
               $bra = mysql_fetch_assoc($bran);
               $branum = mysql_num_rows($bran);
if($branum == 1){ 
$branch_didplay =  $bra['branch_name'];} else{$branch_didplay = 'All branches';
}

//get loan product

if($product == ""){ $product_didplay = "All Products";} else{$product_didplay = $product;}

//report name
$reportname = "DUE PAYMENTS REPORT"; 

//get company name.
$com = mysql_query("select * from users where userId = '".CAP_Session::get('userId')."'");
$comp = mysql_fetch_array($com);
//var_dump($comp);
if($com){
$companyd = $comp['companyId'];

$companyb = $comp['branch'];
$se = mysql_query("select * from company where companyId='".$companyd."'");
$sel = mysql_fetch_assoc($se);
if($se){
$compa = $sel['companyName'];
$address = "Physical Address: ".$sel['country']." ".$sel['city']." ".$sel['address1']." ".$sel['address2']." | Telephone: ".$sel['telephone']." | Email: ".$sel['email'];
}else{
    $compa = "unknown2".mysql_error();
}

}else{
$companyd = "unknown".mysql_error();    
}

               $content .=' <div class="panel panel-default">

              <div class="panel-body">

              <div class="green">

               <label><b> '.$reportname.'&nbsp;&nbsp;As At '.$date.'</b></label> </div>
              
           </div>
            </div>';

$qry=mysql_query("select m.first_name as first_name, m.last_name as last_name, m.sex as sex, m.id as mem_id,m.mem_no as mem_no,d.date as date,d.amount as amount,l.id as loan_id,s.date as due_date from member m join loan_applic l on m.id = l.mem_id join approval ap on l.id = ap.applic_id join disbursed d on d.approval_id=ap.id join employees e on ap.officer_id=e.employeeId join loan_product p on p.id=l.product_id join schedule s on s.approval_id = ap.id where ".$officer." ".$mem_no." ".$product." ".$branch."  and ((s.date >='".$from_date."' and s.date <='".$to_date."') and s.date >=current_date()) order by s.date asc");

  if(@ mysql_numrows($qry) > 0){
  $content.="<input type='button' class='pull-right' href=\"#\" onClick =\"$('#table-due').tableExport({type:'excel',escape:'false'});\" value='Excel'>";
   $content.="<input type='button' onclick=\"generate({mime:'jpeg',table_id:'table-due', filename:'".$reportname.".pdf', title:'".$reportname."', subtitle:'', logo:''})\" class='pull-right' value='PDF'><br><br>";
    
    $content .= '<table class="borderless table-hover" id="table-due" width=100%>';

$content .= '<thead><th>#</th><th>LOAN No.</th><th>NAME</th><th>MEMBER NO.</th><th>DISBURSED AMOUNT</th><th>DISBURSEMENT DATE</th><th>PRINCIPAL DUE</th><th>INTEREST DUE</th><th>PENALTY DUE</th><th>TOTAL DUE</th><th>DUE DATE</th></thead><tbody>';
    
    $percentPrincPaid=0;
    $percentIntPaid=0;
    $percentPrincPaid_sub_total=0;
    $percentIntPaid_sub_total=0;
    $amt_sub_total =0; 
    $bal_sub_total =0;   
    $princ_due_sub_total =0;
    $int_paid_sub_total =0;
    $int_due_sub_total =0;
    $paid_amt_sub_total =0;
    $outstandingBal_sub_total =0;
    $i=0;
   while($row=mysql_fetch_array($qry)){
   
   $date=$row['due_date'];
   $principalDue=libinc::principalDue($row['loan_id'],$date);
   $interestDue=libinc::interestDue($row['loan_id'],$date);
   $principalPaid=libinc::principalPaid($row['loan_id'],$date);
   $InterestPaid=libinc::interestPaid($row['loan_id'],$date);
   $outstandingBal=libinc::loanBalance($row['loan_id'],$date);
   $principalArrears=libinc::principalArrears($row['loan_id'],$date);
   $interestArrears=libinc::interestArrears($row['loan_id'],$date);
   $daysInArrears=libinc::daysInArrears($row['loan_id'],$date);
   
   $penaltyDue=libinc::penaltyDue($row['loan_id'],$date);
   
 
    $penaltyArrears=$penaltyDue;
    $totalArrears=$principalArrears+$interestArrears+$penaltyArrears;
    $totalDue=$principalDue+$interestDue+$penaltyDue;
      
    if($report_format == "detail"){
   if($principalDue > 0 || $interestDue > 0){
   $i++;
   $content .= "<tr><td>".$i."</td><td>".$row['loan_id']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".number_format($row['amount'])."</td><td>".libinc::formatDate($row['date'])."</td><td>".number_format($principalDue)."</td><td>".number_format($interestDue)."</td><td>".number_format($penaltyDue)."</td><td>".number_format($totalDue)."</td><td>".libinc::formatDate($row['due_date'])."</td></tr>";
   
    $amtSubtotal += $row['amount']; 
    $principalDueSubtotal += $principalDue;   
    $interestDueSubtotal += $interestDue; 
    $penaltyDueSubtotal += $penaltyDue; 
    $totalDueSubtotal += $totalDue; 
        
   }
   }   
 }  

$content .= "<tfooter><tr><th>TOTAL</th><th></th><th></th><th></th><th><b>".number_format($amtSubtotal)."</b></th><th></th><th><b>".number_format( $principalDueSubtotal)."</b></th><th><b>".number_format($interestDueSubtotal)."</b></th><th><b>".number_format($penaltyDueSubtotal)."</b></th><th><b>".number_format($totalDueSubtotal)."</b></th><th></th></tr></tfooter></tbody></table></div>";
 $resp->call("createTableJs");

}
else{
   $content = "<font color=red>No Results Found</font>";
  }
}

  $resp->assign("display_div", "innerHTML", $content);
  return $resp;
}


function repaymentReport($cust_no, $product, $loan_officer,$from_date,$to_date,$report_format,$branch_id){
  //list($from_year,$from_month,$from_mday) = explode('-', $from_date);
  
  $resp = new xajaxResponse();
  $calc = new Date_Calc();
  //$resp->assign("status", "innerHTML", "");

if($from_date && $to_date){
 list($year,$month,$day) = explode('-', $date);
   $branch= ($branch_id=='all'||$branch_id=='')?NULL:"and m.branch_id=".$branch_id;

  $mem_no = ($cust_no == 'All') ? "" : "and m.mem_no='".$cust_no."'";
  $product = ($product == '') ? "" : "and p.id=".$product;

  if($loan_officer >0)
    $officer = "e.employeeId='".$loan_officer."'";
  else
    $officer = "e.employeeId > 0";

               $officen = mysql_query("select * from employees where employeeId='".$loan_officer."'");
               $off = mysql_fetch_assoc($officen);
               $offnum = mysql_num_rows($officen);
if($offnum == 1){ $offcer_didplay =  $off['firstName'].' '.$off['lastName'];} else{$offcer_didplay = 'All Officers';}

//get branch
$bran = mysql_query("select * from branch where branch_no='".$branch_id."'");
               $bra = mysql_fetch_assoc($bran);
               $branum = mysql_num_rows($bran);
if($branum == 1){ 
$branch_didplay =  $bra['branch_name'];} else{$branch_didplay = 'All branches';
}

//get loan product

if($product == ""){ $product_didplay = "All Products";} else{$product_didplay = $product;}

//report name
$reportname = "DUE PAYMENTS REPORT"; 

//get company name.
$com = mysql_query("select * from users where userId = '".CAP_Session::get('userId')."'");
$comp = mysql_fetch_array($com);
//var_dump($comp);
if($com){
$companyd = $comp['companyId'];

$companyb = $comp['branch'];
$se = mysql_query("select * from company where companyId='".$companyd."'");
$sel = mysql_fetch_assoc($se);
if($se){
$compa = $sel['companyName'];
$address = "Physical Address: ".$sel['country']." ".$sel['city']." ".$sel['address1']." ".$sel['address2']." | Telephone: ".$sel['telephone']." | Email: ".$sel['email'];
}else{
    $compa = "unknown2".mysql_error();
}

}else{
$companyd = "unknown".mysql_error();    
}

               $content .=' <div class="panel panel-default">

              <div class="panel-body">

              <div class="green">

               <label><b> '.$reportname.'&nbsp;&nbsp;As At '.$date.'</b></label> </div>
             
           </div>
            </div>';

$qry=mysql_query("select m.first_name as first_name, m.last_name as last_name, m.sex as sex, m.id as mem_id,m.mem_no as mem_no,py.date as date,d.amount as amount,l.id as loan_id from member m join loan_applic l on m.id = l.mem_id join approval ap on l.id = ap.applic_id join disbursed d on d.approval_id=ap.id join payment py on py.disbursement_id=d.id join employees e on ap.officer_id=e.employeeId join loan_product p on p.id=l.product_id where ".$officer." ".$mem_no." ".$product." ".$branch." and (py.date >='".$from_date."' and py.date <='".$to_date."')");

  if(@ mysql_numrows($qry) > 0){
   $content.="<input type='button' class='pull-right' href=\"#\" onClick =\"$('#table-rept').tableExport({type:'excel',escape:'false'});\" value='Excel'>";
    $content.="<input type='button' onclick=\"generate({mime:'jpeg',table_id:'table-rept', filename:'".$reportname.".pdf', title:'".$reportname."', subtitle:'', logo:''})\" class='pull-right' value='PDF'><br><br>";
    
    $content .= '<table class="borderless table-hover" id="table-rept" width=100%>';

$content .= '<thead><th>NAME</th><th>MEMBER NO.</th><th>PRINCIPAL PAST DUE START DATE</th><th>PRINCIPAL DUE IN PERIOD</th><th>PRINCIPAL PAID</th><th>INTEREST PAST DUE START DATE</th><th>INTEREST DUE IN PERIOD</th><th>INTEREST PAID</th><th>PENALTY PAST DUE START DATE</th><th>PENALTY DUE IN PERIOD</th><th>PENALTY PAID</th><th>TOTAL AMOUNT PAID</th><th>REPAYMENT RATE</th></thead><tbody>';
  
    $i=1;
   while($row=mysql_fetch_array($qry)){
   
   $principalPastDueStartDate=libinc::principalPastDueStartDate($row['loan_id'],$from_date,$to_date);
   $interestPastDueStartDate=libinc::interestPastDueStartDate($row['loan_id'],$from_date,$to_date);
   $principalDueInPeriod=libinc::principalDueInPeriod($row['loan_id'],$from_date,$to_date);
   $interestDueInPeriod=libinc::interestDueInPeriod($row['loan_id'],$from_date,$to_date);
   $principalPaidInPeriod=libinc::principalPaidInPeriod($row['loan_id'],$from_date,$to_date);
   $interestPaidInPeriod=libinc::interestPaidInPeriod($row['loan_id'],$from_date,$to_date);
   $penaltyPaidInPeriod=libinc::penaltyPaidInPeriod($row['loan_id'],$from_date,$to_date);
   
    $totalPaid=$principalPaidInPeriod+$interestPaidInPeriod+$penaltyPaidInPeriod;
    $payRate=number_format(($row['loan_id']/$principalPaidInPeriod)*100,2);
    if($report_format == "detail"){
 
   $content .= "<tr><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".number_format($principalPastDueStartDate)."</td><td>".number_format($principalDueInPeriod)."</td><td>".number_format($principalPaidInPeriod)."</td><td>".number_format($interestPastDueStartDate)."</td><td>".number_format($interestDueInPeriod)."</td><td>".number_format($interestPaidInPeriod)."</td><td>".number_format($penaltyPastDueStartDate)."</td><td>".number_format($penaltyDueInPeriod)."</td><td>".number_format($penaltyPaidInPeriod)."</td><td>".number_format($totalPaid)."</td><td>".$payRate."</td></tr>";
   
    $total_principal_past += $principalPastDueStartDate; 
    $total_principal_due += $principalDueInPeriod;     
    $total_principal_paid += $principalPaidInPeriod; 
    $total_interest_past += $interestPastDueStartDate; 
    $total_interest_due += $interestDueInPeriod;   
    $total_interest_paid += $interestPaidInPeriod;
    $total_penalty_past += $penaltyPastDueStartDat; 
    $total_penalty_due += $penaltyDueInPeriod;   
    $total_penalty_paid += $penaltyPaidInPeriod;
    $total_amt_paid += $totalPaid;
    $total_repay_rate += $payRate;
    $i++;
 
   }   
 }  

 $content .= "<tfooter><tr><th>TOTAL</th><th></th><th><b>".number_format($total_principal_past)."</b></th><th><b>".number_format($total_principal_due)."</b></th><th><b>".number_format($total_principal_paid)."</b></th><th><b>".number_format($total_interest_past)."</b></th><th><b>".number_format( $total_interest_due)."</b></th><th><b>".number_format($total_interest_paid)."</b></th><th><b>".number_format($total_penalty_past)."</b></th><th><b>".number_format($total_penalty_due)."</b></th><th><b>".number_format($total_penalty_paid)."</b></th><th><b>".number_format($total_amt_paid)."</b></th><th><b>".$total_repay_rate."</b></th></tr></tfooter></tbody></table></div>";
 $resp->call("createTableJs");
}
else{
   $content = "<font color=red>No Results Found</font>";
  }
}

  $resp->assign("display_div", "innerHTML", $content);
  return $resp;
}


function portifolioAtRisk($cust_no, $product, $loan_officer,$date,$report_format,$branch_id){
  //list($from_year,$from_month,$from_mday) = explode('-', $from_date);
       
  $resp = new xajaxResponse();
  
 // $content = $cust_no.','. $product.','. $loan_officer.','.$date.','.$report_format.','.$branch_id;
  $calc = new Date_Calc();
 // $resp->assign("status", "innerHTML", $content);
//return $resp;

if($date){
 list($year,$month,$day) = explode('-', $date);
   $branch= ($branch_id=='all'||$branch_id=='')?NULL:"and m.branch_id=".$branch_id;
  if ($date =='') {
    $tim = time();
    $today = date("Y-m-d h:i:s",$tim);
//$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
 $date = $today;
  }
 // else{
  else{
    //$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
  $date = sprintf("%04d-%02d-%02d 23:59:59", $year, $month, $day);
}
 
  $mem_no = ($cust_no == 'All') ? "" : "and m.mem_no='".$cust_no."'";
  $product = ($product == '') ? "" : "and p.id=".$product;

  if($loan_officer >0)
    $officer = "e.employeeId='".$loan_officer."'";
  else
    $officer = "e.employeeId > 0";
 
      //get officer
               $officen = mysql_query("select * from employees where employeeId='".$loan_officer."'");
               $off = mysql_fetch_assoc($officen);
               $offnum = mysql_num_rows($officen);
if($offnum == 1){ $offcer_didplay =  $off['firstName'].' '.$off['lastName'];} else{$offcer_didplay = 'All Officers';}

//get branch
$bran = mysql_query("select * from branch where branch_no='".$branch_id."'");
               $bra = mysql_fetch_assoc($bran);
               $branum = mysql_num_rows($bran);
if($branum == 1){ 
$branch_didplay =  $bra['branch_name'];} else{$branch_didplay = 'All branches';
}

//get loan product

if($product == ""){ $product_didplay = "All Products";} else{$product_didplay = $product;}

//report name
$reportname = "PORTIFOLIO AT RISK REPORT"; 

//get company name.
$com = mysql_query("select * from users where userId = '".CAP_Session::get('userId')."'");
$comp = mysql_fetch_array($com);
//var_dump($comp);
if($com){
$companyd = $comp['companyId'];

$companyb = $comp['branch'];
$se = mysql_query("select * from company where companyId='".$companyd."'");
$sel = mysql_fetch_assoc($se);
if($se){
$compa = $sel['companyName'];
$address = "Physical Address: ".$sel['country']." ".$sel['city']." ".$sel['address1']." ".$sel['address2']." | Telephone: ".$sel['telephone']." | Email: ".$sel['email'];
}else{
    $compa = "unknown2".mysql_error();
}

}else{
$companyd = "unknown".mysql_error();    
}
               $content .=' <div class="panel panel-default">

              <div class="panel-body">

              <div class="green">

               <label><b> '.$reportname.'&nbsp;&nbsp;As At '.$date.'</b></label></div>
    
               </div>

            </div>';

$qry=mysql_query("select m.first_name as first_name, m.last_name as last_name, m.sex as sex, m.id as mem_id,m.mem_no as mem_no,d.date as date,d.amount as amount,l.id as loan_id from member m join loan_applic l on m.id = l.mem_id join approval ap on l.id = ap.applic_id join disbursed d on d.approval_id=ap.id join employees e on ap.officer_id=e.employeeId join loan_product p on p.id=l.product_id where ".$officer." ".$mem_no." ".$product." ".$branch."");

  if(@ mysql_numrows($qry) > 0){
  $content.="<input type='button' class='pull-right' href=\"#\" onClick =\"$('#table-PAR').tableExport({type:'excel',escape:'false'});\" value='Excel'>";
    $content.="<input type='button' onclick=\"generate({mime:'jpeg',table_id:'table-PAR', filename:'".$reportname.".pdf', title:'".$reportname."', subtitle:'', logo:''})\" class='pull-right' value='PDF'><br><br>";
     
    $content .= '<table class="borderless table-hover" id="table-PAR" width=100%>';

$content .= '<tr><th rowspan=2>#</th><th rowspan=2>LOAN No.</th><th rowspan=2>NAME</th><th rowspan=2>MEMBER NO.</th><th rowspan=2>DISBURSED AMOUNT</th><th rowspan=2>ARREARS</th><th rowspan=2>OUTSTANDING BAL</th><td colspan=10 class="center"><b>PORTIFILIO AT RISK</b></td></tr>
      <tr><th colspan=2><b> > 1 Day</b></th><th colspan=2><b> > 30 Days</b></th><th colspan=2><b> > 60 Days</b></th><th colspan=2><b> > 90 Days</b></th><th colspan=2><b> > 180 Days</b></th></tr>';
    
    $percentPrincPaid=0;
    $percentIntPaid=0;
    $percentPrincPaid_sub_total=0;
    $percentIntPaid_sub_total=0;
    $amt_sub_total =0; 
    $bal_sub_total =0;   
    $princ_due_sub_total =0;
    $int_paid_sub_total =0;
    $int_due_sub_total =0;
    $paid_amt_sub_total =0;
    $outstandingBal_sub_total =0;
    $i=1;
    while($row=mysql_fetch_array($qry)){
   
   $principalDue=libinc::principalDue($row['loan_id'],$date);
   $interestDue=libinc::interestDue($row['loan_id'],$date);
   $principalPaid=libinc::principalPaid($row['loan_id'],$date);
   $InterestPaid=libinc::interestPaid($row['loan_id'],$date);
   $outstandingBal=libinc::loanBalance($row['loan_id'],$date);
   $principalArrears=libinc::principalArrears($row['loan_id'],$date);
   //$daysInArrears=libinc::daysInArrears($row['loan_id'],$date); 
   if(number_format($outstandingBal) < 0){ 
     $outstandingBal=0;
    }   
   if(number_format($principalArrears) < 0){ 
     $principalArrears=0;
    }
   
    $loan_id=$row['loan_id'];
    
	 $riskOverOneDay=0;
         $riskOverThirtyDays=0;
         $riskOverSixtyDays=0;
         $riskOverNintyDays=0;
         $riskOverOneEightyDays=0;
	 $daysInArrears=0;
	 
	 $appr = mysql_query("select id from approval where applic_id='".$loan_id."'");	
	 $apprId = mysql_fetch_array($appr);
	 $apprvId=$apprId['id'];
	 
	 $disb = mysql_query("select id from disbursed where approval_id='".$apprvId."'");	
	 $disbId = mysql_fetch_array($disb);
	 $disb_id=$disbId['id'];
	 $schd= @mysql_query("select date,princ_amt from schedule where approval_id=$apprvId and date <='".$date."'");
	 $totalAmtPaid=0;
	 $totalPY = mysql_query("select SUM(princ_amt) as total_principal_paid from payment where disbursement_id='".$disb_id."' and date <='".$date."' order by date asc");	
	$PY = mysql_fetch_array($totalPY);
	$amtPaid=$PY['total_principal_paid'];
	$balance=$amtPaid;
	 $arrearsMaturity=1;
	 while($row2 = mysql_fetch_array($schd)){
	 $arrears=0;
	 //$arrearsDate= date('Y-m-d', strtotime('+1 day', strtotime($row2['date'])));
	 $arrearsDate= $row2['date'];
         $arrDays=strtotime($arrearsDate);
         $dateDays=strtotime($date);
         //$arrears=libinc::principalArrears($loan_id,$arrearsDate);
         $instalment=$row2['princ_amt'];
         if($balance < $instalment)
         $arrears=$instalment-$balance;
         else $balance-=$instalment;
         
	if($arrears > 0){ 
	 $daysInArrears=($dateDays-$arrDays)/(60 * 60 * 24);
	 $days=ceil($daysInArrears); 

         if($days >180){
	 $riskOverOneEightyDays=$outstandingBal;
	 break;
	 }
	 if($days >90 && $days <=180){
	  $riskOverNintyDays=$outstandingBal;
	  break;
	 }
	 if($days >60 && $days <=90){
	 $riskOverSixtyDays=$outstandingBal;
	  break;
	 }
	 if($days >30 && $days <=60){
	 $riskOverThirtyDays=$outstandingBal;
	  break;
	 }
	 if($days >1 && $days <=30){
	 $riskOverOneDay=$outstandingBal;
	  break;
	 }	 	 
	 }
	 //break;
         }
         
      if($report_format == "detail"){
    if(number_format($outstandingBal) >0){
    $content .= "<tr><td>".$i."</td><td>".$row['loan_id']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".number_format($row['amount'])."</td><td>".number_format($principalArrears)."</td><td>".number_format($outstandingBal)."</td><td colspan=2>".number_format($riskOverOneDay)."</td><td colspan=2>".number_format($riskOverThirtyDays)."</td><td colspan=2>".number_format($riskOverSixtyDays)."</td><td colspan=2>".number_format($riskOverNintyDays)."</td><td colspan=2>".number_format($riskOverOneEightyDays)."</td></tr>";
   
   $amtSubTotal += $row['amount']; 
    $outstandingBalSubTotal += $outstandingBal;    
    $riskOverOneDaySubTotal += $riskOverOneDay; 
    $riskOverThirtyDaysSubTotal += $riskOverThirtyDays; 
    $riskOverSixtyDaysSubTotal += $riskOverSixtyDays; 
    $riskOverNintyDaysSubTotal += $riskOverNintyDays;   
    $riskOverOneEightyDaysSubTotal+=$riskOverOneEightyDays;
    $i++;
   } 
   }
   } 
    
    
$content .= "<tfooter><tr><th>TOTAL</th><th></th><th></th><th></th><th><b>".number_format($amtSubTotal)."</b></th><th><b>".number_format($principalArrearsSubTotal)."</b></th><th><b>".number_format($outstandingBalSubTotal)."</b></th><th colspan=2><b>".number_format($riskOverOneDaySubTotal)."</b></th><th colspan=2><b>".number_format($riskOverThirtyDaysSubTotal)."</b></th><th colspan=2><b>".number_format($riskOverSixtyDaysSubTotal)."</b></th><th colspan=2><b>".number_format($riskOverNintyDaysSubTotal)."</b></th><th colspan=2><b>".number_format($riskOverOneEightyDaysSubTotal)."</b></th></tr></tfooter>";
  
  $content .= "</tbody></table></div>";
 $resp->call("createTableJs");
 // }
//}
 
}
else{
   $content = "<font color=red>No Results Found</font>";
  }
}
 //else
 
  $resp->assign("display_div", "innerHTML", $content);
  return $resp;
}


function ageingReport($cust_no, $product, $loan_officer,$date,$report_format,$branch_id){
  //list($from_year,$from_month,$from_mday) = explode('-', $from_date);
       
  $resp = new xajaxResponse();
  
 // $content = $cust_no.','. $product.','. $loan_officer.','.$date.','.$report_format.','.$branch_id;
  $calc = new Date_Calc();
 // $resp->assign("status", "innerHTML", $content);
//return $resp;

if($date){
 list($year,$month,$day) = explode('-', $date);
   $branch= ($branch_id=='all'||$branch_id=='')?NULL:"and m.branch_id=".$branch_id;
  if ($date =='') {
    $tim = time();
    $today = date("Y-m-d h:i:s",$tim);
//$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
 $date = $today;
  }
 // else{
  else{
    //$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
  $date = sprintf("%04d-%02d-%02d", $year, $month, $day);
}
 
  $mem_no = ($cust_no == 'All') ? "" : "and m.mem_no='".$cust_no."'";
  $product = ($product == '') ? "" : "and p.id=".$product;

  if($loan_officer >0)
    $officer = "e.employeeId='".$loan_officer."'";
  else
    $officer = "e.employeeId > 0";

      //get officer
               $officen = mysql_query("select * from employees where employeeId='".$loan_officer."'");
               $off = mysql_fetch_assoc($officen);
               $offnum = mysql_num_rows($officen);
if($offnum == 1){ $offcer_didplay =  $off['firstName'].' '.$off['lastName'];} else{$offcer_didplay = 'All Officers';}

//get branch
$bran = mysql_query("select * from branch where branch_no='".$branch_id."'");
               $bra = mysql_fetch_assoc($bran);
               $branum = mysql_num_rows($bran);
if($branum == 1){ 
$branch_didplay =  $bra['branch_name'];} else{$branch_didplay = 'All branches';
}

//get loan product

if($product == ""){ $product_didplay = "All Products";} else{$product_didplay = $product;}

//report name
$reportname = "AGEING REPORT"; 

//get company name.
$com = mysql_query("select * from users where userId = '".CAP_Session::get('userId')."'");
$comp = mysql_fetch_array($com);
//var_dump($comp);
if($com){
$companyd = $comp['companyId'];

$companyb = $comp['branch'];
$se = mysql_query("select * from company where companyId='".$companyd."'");
$sel = mysql_fetch_assoc($se);
if($se){
$compa = $sel['companyName'];
$address = "Physical Address: ".$sel['country']." ".$sel['city']." ".$sel['address1']." ".$sel['address2']." | Telephone: ".$sel['telephone']." | Email: ".$sel['email'];
}else{
    $compa = "unknown2".mysql_error();
}

}else{
$companyd = "unknown".mysql_error();    
}

               $content .=' <div class="panel panel-default">

              <div class="panel-body">

              <div class="green">

               <label><b> '.$reportname.'&nbsp;&nbsp;As At '.$date.'</b></label> </div>

             </div>

            </div>';

$qry=mysql_query("select m.first_name as first_name, m.last_name as last_name, m.sex as sex, m.id as mem_id,m.mem_no as mem_no,d.date as date,d.amount as amount,l.id as loan_id from member m join loan_applic l on m.id = l.mem_id join approval ap on l.id = ap.applic_id join disbursed d on d.approval_id=ap.id join employees e on ap.officer_id=e.employeeId join loan_product p on p.id=l.product_id where ".$officer." ".$mem_no." ".$product." ".$branch."");

  if(@ mysql_numrows($qry) > 0){
  $content.="<input type='button' class='pull-right' href=\"#\" onClick =\"$('#table-age').tableExport({type:'excel',escape:'false'});\" value='Excel'>";
    $content.="<input type='button' onclick=\"generate({mime:'jpeg',table_id:'table-age', filename:'Ageing Report.pdf', title:'AGEING REPORT', subtitle:'', logo:''})\" class='pull-right' value='PDF'><br><br>";
     
    $content .= '<table class="borderless table-hover" id="table-age" width=100%>';

$content .= '<tr><th rowspan=2>#</th><th rowspan=2>LOAN No.</th><th rowspan=2>NAME</th><th rowspan=2>MEMBER NO.</th><th rowspan=2>DISBURSED AMOUNT</th><th rowspan=2>ARREARS</th><th rowspan=2>OUTSTANDING BAL</th><td colspan=12 class="center"><b>PRINCIPAL IN ARREARS</b></td></tr>
      <tr><th colspan=2><b>1-30 Days</b></th><th colspan=2><b> 31-60 Days</b></th><th colspan=2><b> 61-90 Days</b></th><th colspan=2><b>91-120 Days</b></th><th colspan=2><b>121-180 Days</b></th><th colspan=2><b> > 180 Days</b></th></tr>';
    
    $percentPrincPaid=0;
    $percentIntPaid=0;
    $percentPrincPaid_sub_total=0;
    $percentIntPaid_sub_total=0;
    $amt_sub_total =0; 
    $bal_sub_total =0;   
    $princ_due_sub_total =0;
    $int_paid_sub_total =0;
    $int_due_sub_total =0;
    $paid_amt_sub_total =0;
    $outstandingBal_sub_total =0;
   
   $i=1;
   while($row=mysql_fetch_array($qry)){
   
   $principalDue=libinc::principalDue($row['loan_id'],$date);
   $interestDue=libinc::interestDue($row['loan_id'],$date);
   $principalPaid=libinc::principalPaid($row['loan_id'],$date);
   $InterestPaid=libinc::interestPaid($row['loan_id'],$date);
   $outstandingBal=libinc::loanBalance($row['loan_id'],$date);
   $principalArrears=libinc::principalArrears($row['loan_id'],$date);
   
   if(number_format($outstandingBal) < 0){ 
     $outstandingBal=0;
    }   
   if(number_format($principalArrears) < 0){ 
     $principalArrears=0;
    }
   
   
   
    if($principalPaid >0 && ($principalPaid <=$principalDue))
    $percentPrincPaid=($principalPaid/$principalDue)*100;
    if($principalPaid > $principalDue)
    $percentPrincPaid=100;
    if($InterestPaid >0 && ($InterestPaid <=$interestDue));
    $percentIntPaid=($InterestPaid/$interestDue)*100;
    if($InterestPaid > $interestDue)
    $percentIntPaid=100;
    
    $loan_id=$row['loan_id'];
    
         $arrears181=0;	
	 $arrears180=0;
	 $arrears120=0;
	 $arrears90=0;
	 $arrears60=0;
	 $arrears30=0;
	 $daysInArrears=0;
	 $appr = mysql_query("select id from approval where applic_id='".$loan_id."'");	
	 $apprId = mysql_fetch_array($appr);
	 $apprvId=$apprId['id'];
	 
	 $disb = mysql_query("select id from disbursed where approval_id='".$apprvId."'");	
	 $disbId = mysql_fetch_array($disb);
	 $disb_id=$disbId['id'];
	 $schd= @mysql_query("select date,princ_amt from schedule where approval_id=$apprvId and date <='".$date."'");
	 $totalAmtPaid=0;
	 $totalPY = mysql_query("select SUM(princ_amt) as total_principal_paid from payment where disbursement_id='".$disb_id."' and date <='".$date."' order by date asc");	
	$PY = mysql_fetch_array($totalPY);
	$amtPaid=$PY['total_principal_paid'];
	$balance=$amtPaid;
	 $arrearsMaturity=1;
	 while($row2 = mysql_fetch_array($schd)){
	 $arrears=0;
	 $arrearsDate= date('Y-m-d', strtotime('+1 day', strtotime($row2['date'])));
	 $arrearsDate= $row2['date'];
         $arrDays=strtotime($arrearsDate);
         $dateDays=strtotime($date);
         //$arrears=libinc::principalArrears($loan_id,$arrearsDate);
         $instalment=$row2['princ_amt'];
         if($balance < $instalment)
         $arrears=$instalment-$balance;
         else $balance-=$instalment;
         
	if($arrears > 0){ 
	 $daysInArrears=($dateDays-$arrDays)/(60 * 60 * 24);
	 $days=ceil($daysInArrears);
		 
	 if($days >180)
	 $arrears181+=$arrears; 
	 if($days >120 && $days <=180)
	 $arrears180+=$arrears;
	 if($days >90 && $days <=120)
	 $arrears120+=$arrears;
	 if($days >60 && $days <=90)
	 $arrears90+=$arrears;
	 if($days >30 && $days <=60)
	 $arrears60+=$arrears;
	 if($days <=30)
	 $arrears30+=$arrears;
	 	 
	 }
	 //break;
         }
    //  
    if($report_format == "detail"){
    if(number_format($principalArrears) > 0){
   $content .= "<tr><td>".$i."</td><td>".$row['loan_id']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".number_format($row['amount'])."</td><td>".number_format($principalArrears)."</td><td>".number_format($outstandingBal)."</td><td colspan=2>".number_format($arrears30)."</td><td colspan=2>".number_format($arrears60)."</td><td colspan=2>".number_format($arrears90)."</td><td colspan=2>".number_format($arrears120)."</td><td colspan=2>".number_format($arrears180)."</td><td colspan=2>".number_format($arrears181)."</td></tr>";
   
    $amtSubTotal += $row['amount']; 
    $outstandingBalSubTotal += $outstandingBal;
    $principalArrearsSubTotal+=$principalArrears;
    $arrears30SubTotal += $arrears30; 
    $arrears60SubTotal += $arrears60;  
    $arrears90SubTotal += $arrears90;  
    $arrears120SubTotal += $arrears120; 
    $arrears180SubTotal += $arrears180;
    $arrears181SubTotal += $arrears181;
    $i++;
   }  
   } 
   }
$content .= "<tfooter><tr><th>TOTAL</th><th></th><th></th><th></th><th><b>".number_format($amtSubTotal)."</b></th><th><b>".number_format($principalArrearsSubTotal)."</b></th><th><b>".number_format($outstandingBalSubTotal)."</b></th><th colspan=2><b>".number_format($arrears30SubTotal)."</b></th><th colspan=2><b>".number_format($arrears60SubTotal)."</b></th><th colspan=2><b>".number_format($arrears90SubTotal)."</b></th><th colspan=2><b>".number_format($arrears120SubTotal)."</b></th><th colspan=2><b>".number_format($arrears180SubTotal)."</b></th><th colspan=2><b>".number_format($arrears181SubTotal)."</b></th></tr></tfooter></tbody></table></div>";
 $resp->call("createTableJs");

}
else{
   $content = "<font color=red>No Results Found</font>";
  }
}
 //else
 
  $resp->assign("display_div", "innerHTML", $content);
  return $resp;
}

function arrearsReport($cust_no, $product, $loan_officer,$date,$report_format,$branch_id){
  //list($from_year,$from_month,$from_mday) = explode('-', $from_date);
       
  $resp = new xajaxResponse();
  
 // $content = $cust_no.','. $product.','. $loan_officer.','.$date.','.$report_format.','.$branch_id;
  $calc = new Date_Calc();
 // $resp->assign("status", "innerHTML", $content);
//return $resp;

if($date){
 list($year,$month,$day) = explode('-', $date);
   $branch= ($branch_id=='all'||$branch_id=='')?NULL:"and m.branch_id=".$branch_id;
  if ($date =='') {
    $tim = time();
    $today = date("Y-m-d h:i:s",$tim);
//$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
 $date = $today;
  }
 // else{
  else{
    //$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
  $date = sprintf("%04d-%02d-%02d 23:59:59", $year, $month, $day);
}
 
  $mem_no = ($cust_no == 'All') ? "" : "and m.mem_no='".$cust_no."'";
  $product = ($product == '') ? "" : "and p.id=".$product;

  if($loan_officer >0)
    $officer = "e.employeeId='".$loan_officer."'";
  else
    $officer = "e.employeeId > 0";

      //get officer
               $officen = mysql_query("select * from employees where employeeId='".$loan_officer."'");
               $off = mysql_fetch_assoc($officen);
               $offnum = mysql_num_rows($officen);
if($offnum == 1){ $offcer_didplay =  $off['firstName'].' '.$off['lastName'];} else{$offcer_didplay = 'All Officers';}

//get branch
$bran = mysql_query("select * from branch where branch_no='".$branch_id."'");
               $bra = mysql_fetch_assoc($bran);
               $branum = mysql_num_rows($bran);
if($branum == 1){ 
$branch_didplay =  $bra['branch_name'];} else{$branch_didplay = 'All branches';
}
//get loan product

if($product == ""){ $product_didplay = "All Products";} else{$product_didplay = $product;}

//report name
$reportname = "ARREARS REPORT"; 
//get company name.
$com = mysql_query("select * from users where userId = '".CAP_Session::get('userId')."'");
$comp = mysql_fetch_array($com);
//var_dump($comp);
if($com){
$companyd = $comp['companyId'];

$companyb = $comp['branch'];
$se = mysql_query("select * from company where companyId='".$companyd."'");
$sel = mysql_fetch_assoc($se);
if($se){
$compa = $sel['companyName'];
$address = "Physical Address: ".$sel['country']." ".$sel['city']." ".$sel['address1']." ".$sel['address2']." | Telephone: ".$sel['telephone']." | Email: ".$sel['email'];
}else{
    $compa = "unknown2".mysql_error();
}

}else{
$companyd = "unknown".mysql_error();    
}

               $content .=' <div class="panel panel-default">

              <div class="panel-body">

              <div class="green">

               <label><b> '.$reportname.'&nbsp;&nbsp;As At '.$date.'</b></label> </div>

           </div>
            </div>';

$qry=mysql_query("select m.first_name as first_name, m.last_name as last_name, m.sex as sex, m.id as mem_id,m.mem_no as mem_no,m.telno as phone_no,d.date as date,d.amount as amount,l.id as loan_id,ap.officer_id as officer_id,d.id as disbursement_id from member m join loan_applic l on m.id = l.mem_id join approval ap on l.id = ap.applic_id join disbursed d on d.approval_id=ap.id join employees e on ap.officer_id=e.employeeId join loan_product p on p.id=l.product_id where ".$officer." ".$mem_no." ".$product." ".$branch."");

  if(@ mysql_numrows($qry) > 0){
      
    $content.="<input type='button' class='pull-right' href=\"#\" onClick =\"$('#table-arrears').tableExport({type:'excel',escape:'false'});\" value='Excel'>   
   <input type='button' onclick=\"generate({mime:'jpeg',table_id:'table-arrears', filename:'Arrears Report.pdf', title:'ARREARS REPORT', subtitle:'', logo:''})\" class='pull-right' value='PDF'><br><br>";
    $content .= '<table class="borderless table-hover" id="table-arrears" width=100%>';

$content .= '<thead><th rowspan=2>#</th><th rowspan=2>LOAN No.</th><th rowspan=2>NAME</th><th rowspan=2>MEMBER NO.</th><th rowspan=2>PHONE NO.</th><th rowspan=2>DISBURSED AMOUNT</th><th rowspan=2>PRINCIPAL ARREARS</th><th rowspan=2>INTEREST ARREARS</th><th rowspan=2>PENALTY ARREARS</th><th rowspan=2>TOTAL ARREARS</th><th rowspan=2>LAST REPAY DATE</th><th rowspan=2>ARREARS RATE(%)</th><th rowspan=2>CREDIT OFFFICER</th></thead><tbody>';

    $percentPrincPaid=0;
    $percentIntPaid=0;
    $percentPrincPaid_sub_total=0;
    $percentIntPaid_sub_total=0;
    $amt_sub_total =0; 
    $bal_sub_total =0;   
    $princ_due_sub_total =0;
    $int_paid_sub_total =0;
    $int_due_sub_total =0;
    $paid_amt_sub_total =0;
    $outstandingBal_sub_total =0;
    $totalArrears=0;

    $i=1;
   while($row=mysql_fetch_array($qry)){
   
      $officer=libinc::getItemById('employees',$row['officer_id'],'employeeId','firstName')." ".libinc::getItemById('employees',$row['officer_id'],'employeeId','lastName');
      
   $pay=mysql_fetch_assoc(mysql_query("select max(date) as last_pay_date from payment where disbursement_id='".$row['disbursement_id']."'"));
   $lastPayDate=$pay['last_pay_date']==NULL ? "--" : libinc::formatDate($pay['last_pay_date']);
   
   $principalDue=libinc::principalDue($row['loan_id'],$date);
   $interestDue=libinc::interestDue($row['loan_id'],$date);
   $principalPaid=libinc::principalPaid($row['loan_id'],$date);
   $InterestPaid=libinc::interestPaid($row['loan_id'],$date);
   $outstandingBal=libinc::loanBalance($row['loan_id'],$date);
   $principalArrears=libinc::principalArrears($row['loan_id'],$date);
   $interestArrears=libinc::interestArrears($row['loan_id'],$date);
   $daysInArrears=libinc::daysInArrears($row['loan_id'],$date);
   
   $penaltyDue=libinc::penaltyDue($row['loan_id'],$date);
  
  if(number_format($outstandingBal) < 0){ 
     $outstandingBal=0;
    }   
   if(number_format($principalArrears) < 0){ 
     $principalArrears=0;
    }
    
    if(number_format($interestArrears) < 0){ 
     $interestArrears=0;
    }
    
    if(number_format($penaltyDue) < 0){ 
     $penaltyDue=0;
    }
   
  
    $penaltyArrears=$penaltyDue;
    $totalArrears=$principalArrears+$interestArrears+$penaltyArrears;
    
    $loan_id=$row['loan_id'];  
    $arrearsRate=($principalArrears/$row['amount'])*100;
    if($report_format == "detail"){
    if(number_format($totalArrears) > 0){
   $content .= "<tr><td>".$i."</td><td>".$row['loan_id']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".$row['phone_no']."</td><td>".number_format($row['amount'])."</td><td>".number_format($principalArrears)."</td><td>".number_format($interestArrears)."</td><td >".number_format($penaltyArrears)."</td><td>".number_format($totalArrears)."</td><td >".$lastPayDate."</td><td>".number_format($arrearsRate)."</td><td>".$officer."</td></tr>";
    $amtSubTotal += $row['amount']; 
    $interestArrearsSubTotal += $interestArrears; 
    $penaltyArrearsSubTotal += $penaltyArrears;
    $totalArrearsSubTotal += $totalArrears;
    $principalArrearsSubTotal+=$principalArrears;
    $arrearsRateSubTotal += $arrearsRate;
    $i++;
   }
   }
   }  
   
  //if($report_format == "summary"){
  $content .= "<tfooter><tr><th>TOTAL</th><th></th><th></th><th></th><th></th><th><b>".number_format($amtSubTotal)."</b></th><th><b>".number_format($principalArrearsSubTotal)."</b></th><th><b>".number_format($interestArrearsSubTotal)."</b></th><th><b>".number_format($penaltyArrearsSubTotal)."</b></th><th><b>".number_format($totalArrearsSubTotal)."</b></th><th></th><th><b>".number_format($arrearsRateSubTotal)."</b></th><th></th></tr></tfooter></tbody></table></div>";

 $content .= "</tbody></table></div>";
 $resp->call("createTableJs");
 // }
//}
 
}
else{
   $content = "<font color=red>No Results Found</font>";
  }
}
 //else
 
  $resp->assign("display_div", "innerHTML", $content);
  return $resp;
}


function ledger($loan_id,$date){
  //list($from_year,$from_month,$from_mday) = explode('-', $from_date);
       
  $resp = new xajaxResponse();

  $calc = new Date_Calc();
if(empty($loan_id)){
$resp->alert("Enter Loan Number Please");
return $resp;
}

if(empty($date)){
$resp->alert("Please Choose a Date");
return $resp;
}
  
$qry1=@mysql_query("select * from loan_applic where id='".$loan_id."'");
if(@mysql_num_rows($qry1) < 1){
$resp->alert("Loan Does Not Exist");
return $resp;
}
$applic=mysql_fetch_array($qry1);

$loan_officer=$applic['officer_id'];
$product_id=$applic['product_id'];
$account_id=libinc::getItemById("loan_product",$product_id,"id","account_id");
$product=libinc::getItemById("accounts",$account_id,"id","name");

$mem=mysql_query("select * from member where id='".$applic['mem_id']."'");
$member=mysql_fetch_assoc($mem);
$branch_id=$member['branch_id'];
$customer=$member['first_name']."".$member['last_name'];
$penalty=libinc::getItemById("loan_product",$product_id,"id","penalty_rate");



              $officen = mysql_query("select * from employees where employeeId='".$loan_officer."'");
               $off = mysql_fetch_assoc($officen);
               $offnum = mysql_num_rows($officen);
if($offnum == 1){ $offcer_didplay =  $off['firstName'].' '.$off['lastName'];} else{$offcer_didplay = 'All Officers';}

//get branch
$bran = mysql_query("select * from branch where branch_no=$branch_id");
               $bra = mysql_fetch_assoc($bran);
               $branum = mysql_num_rows($bran);
if($branum == 1){ 
$branch_didplay =  $bra['branch_name'];} else{$branch_didplay = 'All branches';
}
//get loan product
if($product == ""){ $product_didplay = "All Products";} else{$product_didplay = $product;}

//report name
$reportname = "LOAN LEDGER"; 
//get company name.
$com = mysql_query("select * from users where userId = '".CAP_Session::get('userId')."'");
$comp = mysql_fetch_array($com);
//var_dump($comp);
if($com){
$companyd = $comp['companyId'];

$companyb = $comp['branch'];
$se = mysql_query("select * from company where companyId='".$companyd."'");
$sel = mysql_fetch_assoc($se);
if($se){
$compa = $sel['companyName'];
$address =$sel['address1']." ".$sel['address2']." | ".$sel['telephone'];
$address.=($el['email']==NULL) ? "" : " | ".$sel['email']; 
$address.=" | ".$sel['city']." ".$sel['country'];
}else{
    $compa = "unknown2".mysql_error();
}

}else{
$companyd = "unknown".mysql_error();    
}

               $content .=' <div class="panel panel-default">

              <div class="panel-body">

              <div class="green">

               <label><b> '.$reportname.'&nbsp;&nbsp;As At '.$date.'</b></label> </div>
    
             </div>
            </div>';

$qry2=mysql_query("select * from approval where applic_id=".$loan_id."");
$approval=mysql_fetch_array($qry2);
	if($approval['repay_freq']=="monthly" || $approval['repay_freq']=="montly")
	$freq="Monthly";
	if($approval['repay_freq']=="weekly")
	$freq="Weekly";
	if($approval['repay_freq']=="annually")
	$freq="Annually";
	if($approval['repay_freq']=="bi-weekly")
	$freq="Bi Weekly";
	if($approval['repay_freq']=="quarterly")
	$freq="Quarterly";
	
$grace_period=($approval['grace_period']==NULL) ? 0 : $approval['grace_period'];

$qry3=mysql_query("select * from disbursed where approval_id=".$approval['id']."");
$disb=mysql_fetch_array($qry3);

$qry4=mysql_query("select sum(princ_amt) as total_principle,sum(int_amt) as total_interest from schedule where approval_id=".$approval['id']."");
$schedule=mysql_fetch_array($qry4);

//$qry5=mysql_query("select sum(princ_amt) as total_principle,sum(int_amt) as total_interest from payment where disbursement_id=".$approval['id']." and date <='".$date."'");
//$pay=mysql_fetch_array($qry5);
 $content.="<input type='button' class='pull-right' href=\"#\" onClick =\"$('#table-lgr').tableExport({type:'excel',escape:'false'});\" value='Excel'>";
$content.="<input type='button' onclick=\"generate({mime:'jpeg',table_id:'table-lgr', filename:'Loans Ledger.pdf', title:'LOAN LEDGER', subtitle:'', logo:''})\" class='pull-right' value='PDF'><br><br>";

$content .= '
			<table class="borderless table-hover" id="table-lgr" width=100%>
			<tr><td align="center" colspan=9><b>'.$compa.'</b></td></tr>
			<tr><td align="center" colspan=9><b>'.$address.'</b></td></tr>
	                <tr><td align="center" colspan=9><b>LOAN LEDGER FOR:</b>&nbsp;'.$member['first_name'].' '.$member['last_name'].'&nbsp;&nbsp;<b>A/c No:</b>&nbsp;'.$member['mem_no'].'&nbsp;&nbsp;<b>Branch:</b>&nbsp;'.$branch_didplay.'&nbsp;&nbsp;<b>As At:&nbsp;</b>'.$date.'</b></td>
			</tr>
			<tr><td align="center" colspan=9><b>Loan No:</b>&nbsp;'.$loan_id.'&nbsp;&nbsp;<b>Amount Disbursed:</b>&nbsp;'.number_format($approval['amount']).'&nbsp;&nbsp;<b>Loan Product:</b>&nbsp;'.$product.'&nbsp;&nbsp;<b>Frequency:</b>&nbsp;'.$freq.'</td></tr>
			 <tr><td align="center" colspan=9><b>Loan Period:</b>&nbsp;'.$approval['loan_period'].'&nbsp;&nbsp;<b>Grace Period:</b>&nbsp;'.$grace_period.'&nbsp;&nbsp;<b>Penalty Rate (%):</b>&nbsp;'.$penalty.'</td>
			</tr>';
$content .= '<tr><td colspan=9></td></tr>';			
$content .= '<tr><td><b>DATE</b></td><td><b>TRANSACTION TYPE</b></td><td><b>VOUCHER</b></td><td><b>PRINCIPLE</b></td><td><b>INTEREST</b></td><td><b>PENALTY</b></td><td><b>BALANCE PRINCIPLE</td><td><b>BALANCE INTEREST</b></td><td><b>TOTAL BALANCE</b></td></tr>';  

   $content .= "<tr><td>".libinc::formatDate($applic['date'])."</td><td>Application</td><td>".$applic['voucher']."</td><td>".number_format($applic['amount'])."</td><td>--</td><td>--</td><td>--</td><td>--</td><td>".number_format($applic['amount'])."</td></tr>";
   if(mysql_num_rows($qry2) > 0)
   
   $content .= "<tr><td>".libinc::formatDate($approval['date'])."</td><td>Approval</td><td>--</td><td>".number_format($approval['amount'])."</td><td>--</td><td>0</td><td>".number_format($approval['amount'])."</td><td>--</td><td>".number_format($approval['amount'])."</td></tr>";
   
   if(mysql_num_rows($qry3) > 0)
       
   $content .= "<tr><td>".libinc::formatDate($disb['date'])."</td><td>Disbursement</td><td>--</td><td>".number_format($schedule['total_principle'])."</td><td>".number_format($schedule['total_interest'])."</td><td>0</td><td>".number_format($schedule['total_principle'])."</td><td>".number_format($schedule['total_interest'])."</td><td>".number_format($schedule['total_principle']+$schedule['total_interest'])."</td></tr>";

   //$qry4=mysql_query("select id,date,princ_amt,int_amt,transaction,voucher,penalty from schedule where approval_id='".$approval['id']."' and date <='".$date."' and princ_amt > 0 UNION ALL select id,date,princ_amt,int_amt,transaction,receipt_no as voucher,penalty from payment where disbursement_id='".$disb['id']."' and date <='".$date."'");
   
   //$qry4=mysql_query("select date,princ_amt,int_amt,transaction,voucher,penalty from schedule where approval_id='".$approval['id']."' and date <='".$date."' and princ_amt > 0 UNION ALL select date,princ_amt,int_amt,transaction,receipt_no as voucher,penalty from payment where disbursement_id='".$disb['id']."' and date <='".$date."' UNION ALL select date,0 as princ_amt,waived_interest_amt as int_amt,'waived interest' as transaction,'' as voucher, '' as penalty from waived_loans where disbursement_id='".$disb['id']."' and date <='".$date."' order by date ASC");
   
   $qry4=mysql_query("select date,princ_amt,int_amt,transaction,receipt_no as voucher,penalty from payment where disbursement_id='".$disb['id']."' and date <='".$date."' UNION ALL select date,0 as princ_amt,waived_interest_amt as int_amt,'waived interest' as transaction,'' as voucher, '' as penalty from waived_loans where disbursement_id='".$disb['id']."' and date <='".$date."' order by date ASC");
   
    //$loanBalance=$disb['amount'];
    $loanBalance=$schedule['total_principle'];
    $interestBalance=$schedule['total_interest'];
   while($row=mysql_fetch_array($qry4)){
      
   if($row['transaction']=='payment' || $row['transaction']=='waived interest'){ 
   $loanBalance-=$row['princ_amt'];
   $interestBalance-=$row['int_amt'];
   }
   
   $content.= "<tr><td>".libinc::formatDate($row['date'])."</td><td>".$row['transaction']."</td><td>".$row['voucher']."</td><td>".number_format($row['princ_amt'])."</td><td>".number_format($row['int_amt'])."</td><td>".$row['penalty']."</td><td>".number_format($loanBalance)."</td><td>".number_format($interestBalance)."</td><td>".number_format($loanBalance+$interestBalance)."</td></tr>";     
   }
 $content .= "</tbody></table></div>";
 $resp->call("createTableJs");

 //else
 
  $resp->assign("display_div", "innerHTML", $content);
  return $resp;
}


function listRepayments($cust_no, $product, $loan_officer,$from_date,$to_date,$report_format,$branch_id,$status){
  //list($from_year,$from_month,$from_mday) = explode('-', $from_date);       
  $resp = new xajaxResponse();
  $calc = new Date_Calc();
  //$resp->assign("status", "innerHTML", "");

if($from_date && $to_date){
 //list($year,$month,$day) = explode('-', $date);
   $branch= ($branch_id=='all'||$branch_id=='')?NULL:"and m.branch_id=".$branch_id;
  if ($date =='') {
    $tim = time();
    $today = date("Y-m-d h:i:s",$tim);
//$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
 $date = $today;
  }
 // else{
  else{
    //$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
  $date = sprintf("%04d-%02d-%02d 23:59:59", $year, $month, $day);
}
 
  $mem_no = ($cust_no == 'All') ? "" : "and m.mem_no='".$cust_no."'";
  $product = ($product == '') ? "" : "and p.id=".$product;

  if($loan_officer >0)
    $officer = "e.employeeId='".$loan_officer."'";
  else
    $officer = "e.employeeId > 0";
    
      //get officer
               $officen = mysql_query("select * from employees where employeeId='".$loan_officer."'");
               $off = mysql_fetch_assoc($officen);
               $offnum = mysql_num_rows($officen);
if($offnum == 1){ $offcer_didplay =  $off['firstName'].' '.$off['lastName'];} else{$offcer_didplay = 'All Officers';}

//get branch
$bran = mysql_query("select * from branch where branch_no='".$branch_id."'");
               $bra = mysql_fetch_assoc($bran);
               $branum = mysql_num_rows($bran);
if($branum == 1){ 
$branch_didplay =  $bra['branch_name'];} else{$branch_didplay = 'All branches';
}

//get loan product

if($product == ""){ $product_didplay = "All Products";} else{$product_didplay = $product;}

//report name 

//get company name.
$com = mysql_query("select * from users where userId = '".CAP_Session::get('userId')."'");
$comp = mysql_fetch_array($com);
//var_dump($comp);
if($com){
$companyd = $comp['companyId'];

$companyb = $comp['branch'];
$se = mysql_query("select * from company where companyId='".$companyd."'");
$sel = mysql_fetch_assoc($se);
if($se){
$compa = $sel['companyName'];
$address = "Physical Address: ".$sel['country']." ".$sel['city']." ".$sel['address1']." ".$sel['address2']." | Telephone: ".$sel['telephone']." | Email: ".$sel['email'];
}else{
    $compa = "unknown2".mysql_error();
}

}else{
$companyd = "unknown".mysql_error();    
}

            $content .=' <div class="panel panel-default">

              <div class="panel-body">

              <div class="green">
               <label><b>LIST OF PAYMENTS BETWEEN '.libinc::formatDate($from_date) .' AND '.libinc::formatDate($to_date) .'</b></label> </div
               </div>       
          </div>';
          
 $qry=mysql_query("select m.first_name as first_name, m.last_name as last_name, m.sex as sex, m.id as mem_id,m.mem_no as mem_no,py.amount as amount,l.id as loan_id,p.id as product_id,py.date as date,py.receipt_no as receipt_no,py.id as id,py.princ_amt as principle,py.int_amt as interest,py.penalty as penalty,py.other_charges as charges  from member m join loan_applic l on m.id = l.mem_id join employees e on l.officer_id=e.employeeId join loan_product p on p.id=l.product_id join approval ap on l.id=ap.applic_id join disbursed d on ap.id=d.approval_id join payment py on d.id=py.disbursement_id where ".$officer." ".$mem_no." ".$product." ".$branch." and py.date >='".$from_date."' and py.date <='".$to_date."'");

  if(@ mysql_numrows($qry) > 0){ 
   $content.="<input type='button' class='pull-right' href=\"#\" onClick =\"$('#table-rept').tableExport({type:'excel',escape:'false'});\" value='Excel'>"; 
  $content.="<input type='button' onclick=\"generate({mime:'jpeg',table_id:'table-rept', filename:'repayments report.pdf', title:'LIST OF PAYMENTS', subtitle:'', logo:''})\" class='pull-right' value='PDF'><br><br>";
    $content .= '<table class="borderless table-hover"  id="table-rept" width=100%>';
$content .= '<thead><th>#</th><th>Payment Ref.</th><th>LOAN No.</th><th>NAME</th><th>MEMBER NO.</th><th>DATE</th><th>VOUCHER NO.</th><th>TOTAL AMOUNT PAID</th><th>PRINCIPAL PAID</th><th>INTEREST PAID</th><th>PENALTY PAID</th><th>OTHER CHARGES</th></thead><tbody>';
 
    $i=1;
   while($row=mysql_fetch_array($qry)){

   if($report_format == "detail"){
   $content .= "<tr><td>".$i."</td><td>".$row['id']."</td><td>".$row['loan_id']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".libinc::formatDate($row['date'])."</td><td>".$row['receipt_no']."</td><td>".number_format($row['amount'])."</td><td>".number_format($row['principle'])."</td><td>".number_format($row['interest'])."</td><td>".number_format($row['penalty'])."</td><td>".number_format($row['charges'])."</td></tr>";
   }
   $amt_sub_total += $row['amount']; 
   $amt_princ_total += $row['principle'];
   $amt_int_total += $row['interest'];
   $amt_Pen_total += $row['penalty'];
   $amt_chg_total += $row['charges'];
    $i++;
}  

$content .= "<tfooter><tr><th>TOTAL</th><th></th><th></th><th></th><th></th><th></th><th></th><th><b>".number_format($amt_sub_total)."</b></th><th>".$amt_princ_total."</th><th>".$amt_int_total."</th><th>".$amt_Pen_total."</th><th>".$amt_chg_total."</th></tr></tfooter></tbody></table></div>";
 $resp->call("createTableJs");
 
}
else{
   $content = "<font color=red>No Results Found</font>";
  }
}

  $resp->assign("display_div", "innerHTML", $content);
  return $resp;
}

//DELETE FROM DB
function delete_application($id){
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	$sth = mysql_query("select * from loan_applic where id='".$id."'");
	if(@ mysql_numrows($sth) ==0){
		$resp->alert("Loan No. Does not Exist");
	}else{
		$resp->confirmCommands(1, "Do you really want to delete this Loan?");
		$resp->call('xajax_delete2_application', $id);
	}
	return $resp;
}

//CONFIRM DELETION
function delete2_application($id){
	$resp = new xajaxResponse();
	if(CAP_Session::get('delete')<>1){
	$resp->alert('You Dont Have Permissions to Delete');
	return $resp;
	}
	
	$qry=mysql_query("select m.first_name as first_name, m.last_name as last_name, m.id as mem_id,m.mem_no as mem_no,l.date as date,l.amount as amount,l.id as loan_id from member m join loan_applic l on m.id = l.mem_id where l.id='".$id."'");
	$mem = @mysql_fetch_array($qry);

	///////////////////
	mysql_query("delete from loan_applic where id='".$id."'");
	$action = "delete from loan_applic where id='".$id."'";
	$msg = "Deleted loan application for customer: ".$mem['first_name']." ".$mem['last_name']." - ".$mem['mem_no']." Amount: ".$mem['amount']." Loan No: ".$id." ";
	log_action($_SESSION['user_id'],$action,$msg);
	//////////////////
	$resp->assign("status", "innerHTML", "<font color=red>Application deleted!</font>");
	return $resp;
}

function portifolio_report(){
	$resp = new xajaxResponse();
$content.='<div class="row-fluid">
            <div class="span12">                                                        
                    <div class="block-fluid">
                     <div class="row-form">';
                   
                    $content.='<h3 class="panel-title">PORTIFOLIO REPORT</h3>';
                    
                     $content.='</div>';  
                     $content.= '<form class="panel panel-default" action="reports/portifolio" method="post">                       
                     <div class="row-form">
                                         
                                            <div class="span3">
                                            <span class="top title">Reporting Date:</span>
                                           <input type="int" class="form-control" id="date" name="date" placeholder="As at" /> 
                                            </div>                                           
                                             </div>';                                           
$content.="<div class='panel-footer'>
<input type='submit' class='btn  btn-primary' value='Search'>";
 //$content .="<button type='button' class='btn  btn-primary' onclick=\"xajax_loan_tracking(getElementById('cust_no').value, getElementById('product_id').value,getElementById('date').value);\">Show Report</button>"; 

$content .= '</div></div></form>';
     
  $resp->call("createDate","date");
  $resp->assign("display_div", "innerHTML", $content);
  return $resp;

}


function listWaivedLoans($cust_no, $product, $loan_officer,$from_date,$to_date,$report_format,$branch_id){
  //list($from_year,$from_month,$from_mday) = explode('-', $from_date);      
     
  $resp = new xajaxResponse();
  $calc = new Date_Calc();
  //$resp->assign("status", "innerHTML", "");

if($from_date && $to_date){
 //list($year,$month,$day) = explode('-', $date);
   $branch= ($branch_id=='all'||$branch_id=='')?NULL:"and m.branch_id=".$branch_id;
  if ($date =='') {
    $tim = time();
    $today = date("Y-m-d h:i:s",$tim);
//$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
 $date = $today;
  }
 // else{
  else{
    //$from_date = sprintf("%d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
  $date = sprintf("%04d-%02d-%02d 23:59:59", $year, $month, $day);
}
 
  $mem_no = ($cust_no == 'All') ? "" : "and m.mem_no='".$cust_no."'";
  $product = ($product == '') ? "" : "and p.id=".$product;

  if($loan_officer >0)
    $officer = "e.employeeId='".$loan_officer."'";
  else
    $officer = "e.employeeId > 0";
    
      //get officer
               $officen = mysql_query("select * from employees where employeeId='".$loan_officer."'");
               $off = mysql_fetch_assoc($officen);
               $offnum = mysql_num_rows($officen);
if($offnum == 1){ $offcer_didplay =  $off['firstName'].' '.$off['lastName'];} else{$offcer_didplay = 'All Officers';}

//get branch
$bran = mysql_query("select * from branch where branch_no='".$branch_id."'");
               $bra = mysql_fetch_assoc($bran);
               $branum = mysql_num_rows($bran);
if($branum == 1){ 
$branch_didplay =  $bra['branch_name'];} else{$branch_didplay = 'All branches';
}

//get loan product

if($product == ""){ $product_didplay = "All Products";} else{$product_didplay = $product;}

//report name 

//get company name.
$com = mysql_query("select * from users where userId = '".CAP_Session::get('userId')."'");
$comp = mysql_fetch_array($com);
//var_dump($comp);
if($com){
$companyd = $comp['companyId'];

$companyb = $comp['branch'];
$se = mysql_query("select * from company where companyId='".$companyd."'");
$sel = mysql_fetch_assoc($se);
if($se){
$compa = $sel['companyName'];
$address = "Physical Address: ".$sel['country']." ".$sel['city']." ".$sel['address1']." ".$sel['address2']." | Telephone: ".$sel['telephone']." | Email: ".$sel['email'];
}else{
    $compa = "unknown2".mysql_error();
}

}else{
$companyd = "unknown".mysql_error();    
}

            $content .=' <div class="panel panel-default">

              <div class="panel-body">

              <div class="green">
               <label><b>LIST OF LOANS WAIVED BETWEEN '.libinc::formatDate($from_date) .' AND '.libinc::formatDate($to_date) .'</b></label> </div
               </div>       
          </div>';
          
 $qry=mysql_query("select m.first_name as first_name, m.last_name as last_name, m.sex as sex, m.id as mem_id,m.mem_no as mem_no,wy. waived_interest_amt as amount,l.id as loan_id,p.id as product_id,wy.date as date,wy.id as id from member m join loan_applic l on m.id = l.mem_id join employees e on l.officer_id=e.employeeId join loan_product p on p.id=l.product_id join approval ap on l.id=ap.applic_id join disbursed d on ap.id=d.approval_id join waived_loans wy on d.id=wy.disbursement_id where ".$officer." ".$mem_no." ".$product." ".$branch." and wy.date >='".$from_date."' and wy.date <='".$to_date."'");

  if(@ mysql_numrows($qry) > 0){ 
   $content.="<input type='button' class='pull-right' href=\"#\" onClick =\"$('#table-rept').tableExport({type:'excel',escape:'false'});\" value='Excel'>"; 
  $content.="<input type='button' onclick=\"generate({mime:'jpeg',table_id:'table-rept', filename:'waivers_report.pdf', title:'LIST OF WAIVERS', subtitle:'', logo:''})\" class='pull-right' value='PDF'><br><br>";
    $content .= '<table class="borderless table-hover"  id="table-rept" width=100%>';
$content .= '<thead><th>#</th><th>LOAN No.</th><th>NAME</th><th>MEMBER NO.</th><th>AMOUNT WAIVED</th><th>DATE</th></thead><tbody>';
 
    $i=1;
   while($row=mysql_fetch_array($qry)){

   if($report_format == "detail"){
   $content .= "<tr><td>".$i."</td><td>".$row['loan_id']."</td><td>".$row['first_name']." ".$row['last_name']."</td><td>".$row['mem_no']."</td><td>".number_format($row['amount'])."</td><td>".libinc::formatDate($row['date'])."</td></tr>";
   }
   $amt_sub_total += $row['amount']; 
    $i++;
}  

$content .= "<tfooter><tr><th>TOTAL</th><th></th><th></th><th></th><th><b>".number_format($amt_sub_total)."</b></th><th></th></tr></tfooter></tbody></table></div>";
 $resp->call("createTableJs");
 
}
else{
   $content = "<font color=red>No Results Found</font>";
  }
}

  $resp->assign("display_div", "innerHTML", $content);
  return $resp;
}
?>
