<?
class loans {
function principalPaid($loan_id,$date){
$prod_res = mysql_query("select a.name as name, a.account_no as account_no, p.id as id,p.int_rate as rate from loan_product p join accounts a on p.account_id=a.id order by a.account_no, a.name");
     
      $qry=@mysql_query("select * from member where mem_no='".$mem_no."'");
      $mem = @mysql_fetch_array($qry);
    $sth=@mysql_query("select * from loan_applic where mem_id='".$mem['id']."'");
		
		$loan = @mysql_fetch_array($sth);
		$applic_id =$loan_id;
			//$options .= "<option value='".$row['id']."'>".$row['first_name']." ".$row['last_name'] ." - ".$row['mem_no'];
	$totalPR = mysql_query("select SUM(princ_amt) as total_principal_expected,SUM(int_amt) as total_interest_expected from schedule where loan_id='".$applic_id."' and date <=CURDATE() order by date asc ");
	$PR = mysql_fetch_array($totalPR);
	$totalPY = mysql_query("select SUM(princ_amt) as total_principal_paid,SUM(int_amt) as total_interest_paid from payment where loan_id='".$applic_id."' and date <=CURDATE() order by date asc");						
	$PY = mysql_fetch_array($totalPY);
	$disburse = mysql_query("select amount,date from disbursed where applic_id='".$applic_id."' order by id desc limit 1");						
	$disburseAmt = mysql_fetch_array($disburse);
	
	$payD = mysql_query("select date from payment where loan_id='".$applic_id."' order by id desc limit 1");						
	$payDt = mysql_fetch_array($payD);
			
	$applic_res = mysql_query("select  p.id as product_id, p.penalty_rate as penalty_rate, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no,  ap.amount as amount,  a.account_no as account_no, a.name as account_name from loan_applic ap join member m on ap.mem_id=m.id join loan_product p on ap.product_id=p.id join accounts a on p.account_id=a.id where ap.id='".$applic_id."'");	
	$applic = mysql_fetch_array($applic_res);
	
	 $disbDate=$disburseAmt['date'];
	 $today = strtotime(date('Y-m-d'));
	 if(mysql_num_rows($payD)==0){	 
	 $firstDueDate = date('Y-m-d', strtotime('+1 month', strtotime($disbDate)));
	 $dueDate = strtotime($firstDueDate);
	 $days=($today-$dueDate)/ (60 * 60 * 24);
	 }
         else{
         $payDate=$payDt['date'];         
         $payDate = strtotime($payDate);
	 $days=($today-$payDate)/ (60 * 60 * 24);
	 }
	 
	 $mem_name = $applic['first_name']." ".$applic['last_name'];
	 $principalDue=$PR['total_principal_expected']-$PY['total_principal_paid'];
	 $interestDue=$PR['total_interest_expected']-$PY['total_interest_paid'];
	 $loanBalance=$disburseAmt['amount']-$PY['total_principal_paid'];
	 $principalPaid=$PY['total_principal_paid'];
	 $interestPaid=$PY['total_interest_paid'];
	 $principalArrears= $PR['total_principal_expected']-$principalPaid;
	 $interestArrears= ($PR['total_interest_expected']-$interestPaid);
	 $totalArrears=$principalArrears+$interestArrears;
	 
	 //$totalArrearsPaid=$amtPaid	 
	 
	 	 
	 $penaltyDue=$totalArrears*($applic['penalty_rate']/100/12)*($days/30);
	 
	 if($principalDue < 0)
	 $principalDue=0;
	 elseif($interestDue < 0)
	 $interestDue=0;
	 
	 
	 return $principalPaid;
	 }
	 
function interestPaid($loan_id,$date){
$prod_res = mysql_query("select a.name as name, a.account_no as account_no, p.id as id,p.int_rate as rate from loan_product p join accounts a on p.account_id=a.id order by a.account_no, a.name");
     
      $qry=@mysql_query("select * from member where mem_no='".$mem_no."'");
      $mem = @mysql_fetch_array($qry);
    $sth=@mysql_query("select * from loan_applic where mem_id='".$mem['id']."'");
		
		$loan = @mysql_fetch_array($sth);
		$applic_id =$loan_id;
			//$options .= "<option value='".$row['id']."'>".$row['first_name']." ".$row['last_name'] ." - ".$row['mem_no'];
	$totalPR = mysql_query("select SUM(princ_amt) as total_principal_expected,SUM(int_amt) as total_interest_expected from schedule where loan_id='".$applic_id."' and date <=CURDATE() order by date asc ");
	$PR = mysql_fetch_array($totalPR);
	$totalPY = mysql_query("select SUM(princ_amt) as total_principal_paid,SUM(int_amt) as total_interest_paid from payment where loan_id='".$applic_id."' and date <=CURDATE() order by date asc");						
	$PY = mysql_fetch_array($totalPY);
	$disburse = mysql_query("select amount,date from disbursed where applic_id='".$applic_id."' order by id desc limit 1");						
	$disburseAmt = mysql_fetch_array($disburse);
	
	$payD = mysql_query("select date from payment where loan_id='".$applic_id."' order by id desc limit 1");						
	$payDt = mysql_fetch_array($payD);
			
	$applic_res = mysql_query("select  p.id as product_id, p.penalty_rate as penalty_rate, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no,  ap.amount as amount,  a.account_no as account_no, a.name as account_name from loan_applic ap join member m on ap.mem_id=m.id join loan_product p on ap.product_id=p.id join accounts a on p.account_id=a.id where ap.id='".$applic_id."'");	
	$applic = mysql_fetch_array($applic_res);
	
	 $disbDate=$disburseAmt['date'];
	 $today = strtotime(date('Y-m-d'));
	 if(mysql_num_rows($payD)==0){	 
	 $firstDueDate = date('Y-m-d', strtotime('+1 month', strtotime($disbDate)));
	 $dueDate = strtotime($firstDueDate);
	 $days=($today-$dueDate)/ (60 * 60 * 24);
	 }
         else{
         $payDate=$payDt['date'];         
         $payDate = strtotime($payDate);
	 $days=($today-$payDate)/ (60 * 60 * 24);
	 }
	 
	 $mem_name = $applic['first_name']." ".$applic['last_name'];
	 $principalDue=$PR['total_principal_expected']-$PY['total_principal_paid'];
	 $interestDue=$PR['total_interest_expected']-$PY['total_interest_paid'];
	 $loanBalance=$disburseAmt['amount']-$PY['total_principal_paid'];
	 $principalPaid=$PY['total_principal_paid'];
	 $interestPaid=$PY['total_interest_paid'];
	 $principalArrears= $PR['total_principal_expected']-$principalPaid;
	 $interestArrears= ($PR['total_interest_expected']-$interestPaid);
	 $totalArrears=$principalArrears+$interestArrears;
	 
	 //$totalArrearsPaid=$amtPaid	 
	 
	 	 
	 $penaltyDue=$totalArrears*($applic['penalty_rate']/100/12)*($days/30);
	 
	 if($principalDue < 0)
	 $principalDue=0;
	 elseif($interestDue < 0)
	 $interestDue=0;
	 
	 
	 return $interestPaid;
	 }
	 
	 
function principalDue($loan_id,$date){
$prod_res = mysql_query("select a.name as name, a.account_no as account_no, p.id as id,p.int_rate as rate from loan_product p join accounts a on p.account_id=a.id order by a.account_no, a.name");
     
      $qry=@mysql_query("select * from member where mem_no='".$mem_no."'");
      $mem = @mysql_fetch_array($qry);
    $sth=@mysql_query("select * from loan_applic where mem_id='".$mem['id']."'");
		
		$loan = @mysql_fetch_array($sth);
		$applic_id =$loan_id;
			//$options .= "<option value='".$row['id']."'>".$row['first_name']." ".$row['last_name'] ." - ".$row['mem_no'];
	$totalPR = mysql_query("select SUM(princ_amt) as total_principal_expected,SUM(int_amt) as total_interest_expected from schedule where loan_id='".$applic_id."' and date <=CURDATE() order by date asc ");
	$PR = mysql_fetch_array($totalPR);
	$totalPY = mysql_query("select SUM(princ_amt) as total_principal_paid,SUM(int_amt) as total_interest_paid from payment where loan_id='".$applic_id."' and date <=CURDATE() order by date asc");						
	$PY = mysql_fetch_array($totalPY);
	$disburse = mysql_query("select amount,date from disbursed where applic_id='".$applic_id."' order by id desc limit 1");						
	$disburseAmt = mysql_fetch_array($disburse);
	
	$payD = mysql_query("select date from payment where loan_id='".$applic_id."' order by id desc limit 1");						
	$payDt = mysql_fetch_array($payD);
			
	$applic_res = mysql_query("select  p.id as product_id, p.penalty_rate as penalty_rate, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no,  ap.amount as amount,  a.account_no as account_no, a.name as account_name from loan_applic ap join member m on ap.mem_id=m.id join loan_product p on ap.product_id=p.id join accounts a on p.account_id=a.id where ap.id='".$applic_id."'");	
	$applic = mysql_fetch_array($applic_res);
	
	 $disbDate=$disburseAmt['date'];
	 $today = strtotime(date('Y-m-d'));
	 if(mysql_num_rows($payD)==0){	 
	 $firstDueDate = date('Y-m-d', strtotime('+1 month', strtotime($disbDate)));
	 $dueDate = strtotime($firstDueDate);
	 $days=($today-$dueDate)/ (60 * 60 * 24);
	 }
         else{
         $payDate=$payDt['date'];         
         $payDate = strtotime($payDate);
	 $days=($today-$payDate)/ (60 * 60 * 24);
	 }
	 
	 $mem_name = $applic['first_name']." ".$applic['last_name'];
	 $principalDue=$PR['total_principal_expected']-$PY['total_principal_paid'];
	 $interestDue=$PR['total_interest_expected']-$PY['total_interest_paid'];
	 $loanBalance=$disburseAmt['amount']-$PY['total_principal_paid'];
	 $principalPaid=$PY['total_principal_paid'];
	 $interestPaid=$PY['total_interest_paid'];
	 $principalArrears= $PR['total_principal_expected']-$principalPaid;
	 $interestArrears= ($PR['total_interest_expected']-$interestPaid);
	 $totalArrears=$principalArrears+$interestArrears;
	 
	 //$totalArrearsPaid=$amtPaid	 
	 
	 	 
	 $penaltyDue=$totalArrears*($applic['penalty_rate']/100/12)*($days/30);
	 
	 if($principalDue < 0)
	 $principalDue=0;
	 elseif($interestDue < 0)
	 $interestDue=0;
	 
	 
	 return $principalDue;
	 }
	 
function interestDue($loan_id,$date){
$prod_res = mysql_query("select a.name as name, a.account_no as account_no, p.id as id,p.int_rate as rate from loan_product p join accounts a on p.account_id=a.id order by a.account_no, a.name");
     
      $qry=@mysql_query("select * from member where mem_no='".$mem_no."'");
      $mem = @mysql_fetch_array($qry);
    $sth=@mysql_query("select * from loan_applic where mem_id='".$mem['id']."'");
		
		$loan = @mysql_fetch_array($sth);
		$applic_id =$loan_id;
			//$options .= "<option value='".$row['id']."'>".$row['first_name']." ".$row['last_name'] ." - ".$row['mem_no'];
	$totalPR = mysql_query("select SUM(princ_amt) as total_principal_expected,SUM(int_amt) as total_interest_expected from schedule where loan_id='".$applic_id."' and date <=CURDATE() order by date asc ");
	$PR = mysql_fetch_array($totalPR);
	$totalPY = mysql_query("select SUM(princ_amt) as total_principal_paid,SUM(int_amt) as total_interest_paid from payment where loan_id='".$applic_id."' and date <=CURDATE() order by date asc");						
	$PY = mysql_fetch_array($totalPY);
	$disburse = mysql_query("select amount,date from disbursed where applic_id='".$applic_id."' order by id desc limit 1");						
	$disburseAmt = mysql_fetch_array($disburse);
	
	$payD = mysql_query("select date from payment where loan_id='".$applic_id."' order by id desc limit 1");						
	$payDt = mysql_fetch_array($payD);
			
	$applic_res = mysql_query("select  p.id as product_id, p.penalty_rate as penalty_rate, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no,  ap.amount as amount,  a.account_no as account_no, a.name as account_name from loan_applic ap join member m on ap.mem_id=m.id join loan_product p on ap.product_id=p.id join accounts a on p.account_id=a.id where ap.id='".$applic_id."'");	
	$applic = mysql_fetch_array($applic_res);
	
	 $disbDate=$disburseAmt['date'];
	 $today = strtotime(date('Y-m-d'));
	 if(mysql_num_rows($payD)==0){	 
	 $firstDueDate = date('Y-m-d', strtotime('+1 month', strtotime($disbDate)));
	 $dueDate = strtotime($firstDueDate);
	 $days=($today-$dueDate)/ (60 * 60 * 24);
	 }
         else{
         $payDate=$payDt['date'];         
         $payDate = strtotime($payDate);
	 $days=($today-$payDate)/ (60 * 60 * 24);
	 }
	 
	 $mem_name = $applic['first_name']." ".$applic['last_name'];
	 $principalDue=$PR['total_principal_expected']-$PY['total_principal_paid'];
	 $interestDue=$PR['total_interest_expected']-$PY['total_interest_paid'];
	 $loanBalance=$disburseAmt['amount']-$PY['total_principal_paid'];
	 $principalPaid=$PY['total_principal_paid'];
	 $interestPaid=$PY['total_interest_paid'];
	 $principalArrears= $PR['total_principal_expected']-$principalPaid;
	 $interestArrears= ($PR['total_interest_expected']-$interestPaid);
	 $totalArrears=$principalArrears+$interestArrears;
	 
	 //$totalArrearsPaid=$amtPaid	 
	 
	 	 
	 $penaltyDue=$totalArrears*($applic['penalty_rate']/100/12)*($days/30);
	 
	 if($principalDue < 0)
	 $principalDue=0;
	 elseif($interestDue < 0)
	 $interestDue=0;
	 
	 
	 return $interestDue;
	 }
	 
function principalArrears($loan_id,$date){
$prod_res = mysql_query("select a.name as name, a.account_no as account_no, p.id as id,p.int_rate as rate from loan_product p join accounts a on p.account_id=a.id order by a.account_no, a.name");
     
      $qry=@mysql_query("select * from member where mem_no='".$mem_no."'");
      $mem = @mysql_fetch_array($qry);
    $sth=@mysql_query("select * from loan_applic where mem_id='".$mem['id']."'");
		
		$loan = @mysql_fetch_array($sth);
		$applic_id =$loan_id;
			//$options .= "<option value='".$row['id']."'>".$row['first_name']." ".$row['last_name'] ." - ".$row['mem_no'];
	$totalPR = mysql_query("select SUM(princ_amt) as total_principal_expected,SUM(int_amt) as total_interest_expected from schedule where loan_id='".$applic_id."' and date <=CURDATE() order by date asc ");
	$PR = mysql_fetch_array($totalPR);
	$totalPY = mysql_query("select SUM(princ_amt) as total_principal_paid,SUM(int_amt) as total_interest_paid from payment where loan_id='".$applic_id."' and date <=CURDATE() order by date asc");						
	$PY = mysql_fetch_array($totalPY);
	$disburse = mysql_query("select amount,date from disbursed where applic_id='".$applic_id."' order by id desc limit 1");						
	$disburseAmt = mysql_fetch_array($disburse);
	
	$payD = mysql_query("select date from payment where loan_id='".$applic_id."' order by id desc limit 1");						
	$payDt = mysql_fetch_array($payD);
			
	$applic_res = mysql_query("select  p.id as product_id, p.penalty_rate as penalty_rate, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no,  ap.amount as amount,  a.account_no as account_no, a.name as account_name from loan_applic ap join member m on ap.mem_id=m.id join loan_product p on ap.product_id=p.id join accounts a on p.account_id=a.id where ap.id='".$applic_id."'");	
	$applic = mysql_fetch_array($applic_res);
	
	 $disbDate=$disburseAmt['date'];
	 $today = strtotime(date('Y-m-d'));
	 if(mysql_num_rows($payD)==0){	 
	 $firstDueDate = date('Y-m-d', strtotime('+1 month', strtotime($disbDate)));
	 $dueDate = strtotime($firstDueDate);
	 $days=($today-$dueDate)/ (60 * 60 * 24);
	 }
         else{
         $payDate=$payDt['date'];         
         $payDate = strtotime($payDate);
	 $days=($today-$payDate)/ (60 * 60 * 24);
	 }
	 
	 $mem_name = $applic['first_name']." ".$applic['last_name'];
	 $principalDue=$PR['total_principal_expected']-$PY['total_principal_paid'];
	 $interestDue=$PR['total_interest_expected']-$PY['total_interest_paid'];
	 $loanBalance=$disburseAmt['amount']-$PY['total_principal_paid'];
	 $principalPaid=$PY['total_principal_paid'];
	 $interestPaid=$PY['total_interest_paid'];
	 $principalArrears= $PR['total_principal_expected']-$principalPaid;
	 $interestArrears= ($PR['total_interest_expected']-$interestPaid);
	 $totalArrears=$principalArrears+$interestArrears;
	 
	 //$totalArrearsPaid=$amtPaid	 
	 
	 	 
	 $penaltyDue=$totalArrears*($applic['penalty_rate']/100/12)*($days/30);
	 
	 if($principalDue < 0)
	 $principalDue=0;
	 elseif($interestDue < 0)
	 $interestDue=0;
	 
	 
	 return $principalArrears;
	 }
	 
function InterestArrears($loan_id,$date){
$prod_res = mysql_query("select a.name as name, a.account_no as account_no, p.id as id,p.int_rate as rate from loan_product p join accounts a on p.account_id=a.id order by a.account_no, a.name");
     
      $qry=@mysql_query("select * from member where mem_no='".$mem_no."'");
      $mem = @mysql_fetch_array($qry);
    $sth=@mysql_query("select * from loan_applic where mem_id='".$mem['id']."'");
		
		$loan = @mysql_fetch_array($sth);
		$applic_id =$loan_id;
			//$options .= "<option value='".$row['id']."'>".$row['first_name']." ".$row['last_name'] ." - ".$row['mem_no'];
	$totalPR = mysql_query("select SUM(princ_amt) as total_principal_expected,SUM(int_amt) as total_interest_expected from schedule where loan_id='".$applic_id."' and date <=CURDATE() order by date asc ");
	$PR = mysql_fetch_array($totalPR);
	$totalPY = mysql_query("select SUM(princ_amt) as total_principal_paid,SUM(int_amt) as total_interest_paid from payment where loan_id='".$applic_id."' and date <=CURDATE() order by date asc");						
	$PY = mysql_fetch_array($totalPY);
	$disburse = mysql_query("select amount,date from disbursed where applic_id='".$applic_id."' order by id desc limit 1");						
	$disburseAmt = mysql_fetch_array($disburse);
	
	$payD = mysql_query("select date from payment where loan_id='".$applic_id."' order by id desc limit 1");						
	$payDt = mysql_fetch_array($payD);
			
	$applic_res = mysql_query("select  p.id as product_id, p.penalty_rate as penalty_rate, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no,  ap.amount as amount,  a.account_no as account_no, a.name as account_name from loan_applic ap join member m on ap.mem_id=m.id join loan_product p on ap.product_id=p.id join accounts a on p.account_id=a.id where ap.id='".$applic_id."'");	
	$applic = mysql_fetch_array($applic_res);
	
	 $disbDate=$disburseAmt['date'];
	 $today = strtotime(date('Y-m-d'));
	 if(mysql_num_rows($payD)==0){	 
	 $firstDueDate = date('Y-m-d', strtotime('+1 month', strtotime($disbDate)));
	 $dueDate = strtotime($firstDueDate);
	 $days=($today-$dueDate)/ (60 * 60 * 24);
	 }
         else{
         $payDate=$payDt['date'];         
         $payDate = strtotime($payDate);
	 $days=($today-$payDate)/ (60 * 60 * 24);
	 }
	 
	 $mem_name = $applic['first_name']." ".$applic['last_name'];
	 $principalDue=$PR['total_principal_expected']-$PY['total_principal_paid'];
	 $interestDue=$PR['total_interest_expected']-$PY['total_interest_paid'];
	 $loanBalance=$disburseAmt['amount']-$PY['total_principal_paid'];
	 $principalPaid=$PY['total_principal_paid'];
	 $interestPaid=$PY['total_interest_paid'];
	 $principalArrears= $PR['total_principal_expected']-$principalPaid;
	 $interestArrears= ($PR['total_interest_expected']-$interestPaid);
	 $totalArrears=$principalArrears+$interestArrears;
	 
	 //$totalArrearsPaid=$amtPaid	 
	 
	 	 
	 $penaltyDue=$totalArrears*($applic['penalty_rate']/100/12)*($days/30);
	 
	 if($principalDue < 0)
	 $principalDue=0;
	 elseif($interestDue < 0)
	 $interestDue=0;
	 
	 
	 return $interestArrears;
	 }
	 
function penaltyDue($loan_id,$date){
$prod_res = mysql_query("select a.name as name, a.account_no as account_no, p.id as id,p.int_rate as rate from loan_product p join accounts a on p.account_id=a.id order by a.account_no, a.name");
     
      $qry=@mysql_query("select * from member where mem_no='".$mem_no."'");
      $mem = @mysql_fetch_array($qry);
    $sth=@mysql_query("select * from loan_applic where mem_id='".$mem['id']."'");
		
		$loan = @mysql_fetch_array($sth);
		$applic_id =$loan_id;
			//$options .= "<option value='".$row['id']."'>".$row['first_name']." ".$row['last_name'] ." - ".$row['mem_no'];
	$totalPR = mysql_query("select SUM(princ_amt) as total_principal_expected,SUM(int_amt) as total_interest_expected from schedule where loan_id='".$applic_id."' and date <=CURDATE() order by date asc ");
	$PR = mysql_fetch_array($totalPR);
	$totalPY = mysql_query("select SUM(princ_amt) as total_principal_paid,SUM(int_amt) as total_interest_paid from payment where loan_id='".$applic_id."' and date <=CURDATE() order by date asc");						
	$PY = mysql_fetch_array($totalPY);
	$disburse = mysql_query("select amount,date from disbursed where applic_id='".$applic_id."' order by id desc limit 1");						
	$disburseAmt = mysql_fetch_array($disburse);
	
	$payD = mysql_query("select date from payment where loan_id='".$applic_id."' order by id desc limit 1");						
	$payDt = mysql_fetch_array($payD);
			
	$applic_res = mysql_query("select  p.id as product_id, p.penalty_rate as penalty_rate, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no,  ap.amount as amount,  a.account_no as account_no, a.name as account_name from loan_applic ap join member m on ap.mem_id=m.id join loan_product p on ap.product_id=p.id join accounts a on p.account_id=a.id where ap.id='".$applic_id."'");	
	$applic = mysql_fetch_array($applic_res);
	
	 $disbDate=$disburseAmt['date'];
	 $today = strtotime(date('Y-m-d'));
	 if(mysql_num_rows($payD)==0){	 
	 $firstDueDate = date('Y-m-d', strtotime('+1 month', strtotime($disbDate)));
	 $dueDate = strtotime($firstDueDate);
	 $days=($today-$dueDate)/ (60 * 60 * 24);
	 }
         else{
         $payDate=$payDt['date'];         
         $payDate = strtotime($payDate);
	 $days=($today-$payDate)/ (60 * 60 * 24);
	 }
	 
	 $mem_name = $applic['first_name']." ".$applic['last_name'];
	 $principalDue=$PR['total_principal_expected']-$PY['total_principal_paid'];
	 $interestDue=$PR['total_interest_expected']-$PY['total_interest_paid'];
	 $loanBalance=$disburseAmt['amount']-$PY['total_principal_paid'];
	 $principalPaid=$PY['total_principal_paid'];
	 $interestPaid=$PY['total_interest_paid'];
	 $principalArrears= $PR['total_principal_expected']-$principalPaid;
	 $interestArrears= ($PR['total_interest_expected']-$interestPaid);
	 $totalArrears=$principalArrears+$interestArrears;
	 
	 //$totalArrearsPaid=$amtPaid	 
	 
	 	 
	 $penaltyDue=$totalArrears*($applic['penalty_rate']/100/12)*($days/30);
	 
	 if($principalDue < 0)
	 $principalDue=0;
	 elseif($interestDue < 0)
	 $interestDue=0;
	 
	 
	 return $penaltyDue;
	 }
	 
	 
function loanBalance($loan_id,$date){
$prod_res = mysql_query("select a.name as name, a.account_no as account_no, p.id as id,p.int_rate as rate from loan_product p join accounts a on p.account_id=a.id order by a.account_no, a.name");
     
      $qry=@mysql_query("select * from member where mem_no='".$mem_no."'");
      $mem = @mysql_fetch_array($qry);
    $sth=@mysql_query("select * from loan_applic where mem_id='".$mem['id']."'");
		
		$loan = @mysql_fetch_array($sth);
		$applic_id =$loan_id;
			//$options .= "<option value='".$row['id']."'>".$row['first_name']." ".$row['last_name'] ." - ".$row['mem_no'];
	$totalPR = mysql_query("select SUM(princ_amt) as total_principal_expected,SUM(int_amt) as total_interest_expected from schedule where loan_id='".$applic_id."' and date <=CURDATE() order by date asc ");
	$PR = mysql_fetch_array($totalPR);
	$totalPY = mysql_query("select SUM(princ_amt) as total_principal_paid,SUM(int_amt) as total_interest_paid from payment where loan_id='".$applic_id."' and date <=CURDATE() order by date asc");						
	$PY = mysql_fetch_array($totalPY);
	$disburse = mysql_query("select amount,date from disbursed where applic_id='".$applic_id."' order by id desc limit 1");						
	$disburseAmt = mysql_fetch_array($disburse);
	
	$payD = mysql_query("select date from payment where loan_id='".$applic_id."' order by id desc limit 1");						
	$payDt = mysql_fetch_array($payD);
			
	$applic_res = mysql_query("select  p.id as product_id, p.penalty_rate as penalty_rate, m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no,  ap.amount as amount,  a.account_no as account_no, a.name as account_name from loan_applic ap join member m on ap.mem_id=m.id join loan_product p on ap.product_id=p.id join accounts a on p.account_id=a.id where ap.id='".$applic_id."'");	
	$applic = mysql_fetch_array($applic_res);
	
	 $disbDate=$disburseAmt['date'];
	 $today = strtotime(date('Y-m-d'));
	 if(mysql_num_rows($payD)==0){	 
	 $firstDueDate = date('Y-m-d', strtotime('+1 month', strtotime($disbDate)));
	 $dueDate = strtotime($firstDueDate);
	 $days=($today-$dueDate)/ (60 * 60 * 24);
	 }
         else{
         $payDate=$payDt['date'];         
         $payDate = strtotime($payDate);
	 $days=($today-$payDate)/ (60 * 60 * 24);
	 }
	 
	 $mem_name = $applic['first_name']." ".$applic['last_name'];
	 $principalDue=$PR['total_principal_expected']-$PY['total_principal_paid'];
	 $interestDue=$PR['total_interest_expected']-$PY['total_interest_paid'];
	 $loanBalance=$disburseAmt['amount']-$PY['total_principal_paid'];
	 $principalPaid=$PY['total_principal_paid'];
	 $interestPaid=$PY['total_interest_paid'];
	 $principalArrears= $PR['total_principal_expected']-$principalPaid;
	 $interestArrears= ($PR['total_interest_expected']-$interestPaid);
	 $totalArrears=$principalArrears+$interestArrears;
	 
	 //$totalArrearsPaid=$amtPaid	 
	 	 	 
	 $penaltyDue=$totalArrears*($applic['penalty_rate']/100/12)*($days/30);
	 
	 if($principalDue < 0)
	 $principalDue=0;
	 elseif($interestDue < 0)
	 $interestDue=0;
	  
	 return $loanBalance;
	 }

}

?>
