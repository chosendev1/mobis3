<?php
class Savings_model extends CI_Model{
	public function __construct(){
		$this->load->database();
	}
	
	public function uploadWithdrawals($dbi){
		
			$n = 0;
			if(count($dbi) > 0)
				$n = count($dbi['memaccountId']);
				$m = $n+1;
			for($i=2;$i <=$m; $i++){
			
				                $mem_id = $dbi['memaccountId'][$i];
						$amount = $dbi['amount'][$i]; 
						$voucher_no = $dbi['voucherNo'][$i]; 
						$bank_id = $dbi['bankAcctId'][$i]; 
						$date = $dbi['date'][$i];
						$trans_date = date("Y-m-d h:i:s");
						$branch_id = $dbi['branchId'][$i];	
				
				$dataArray = array(
						"memaccount_id" => $mem_id, 
						"amount" 	=> $amount, 
						"voucher_no" 	=> $voucher_no, 
						"bank_account"	=> $bank_id, 
						"date" 		=> $date,
						"trans_date"    => $trans_date,
						"branch_id" 	=> $branch_id						
						);
					
				$dataArray2 = array(
						"debit"         => 448, 
					        "creditedSavingsAcct"   => $mem_id,
						"amount" 	=> 1000,
						"date" 		=> $date,
						"description"	=> "Monthly Subscription Refund"
						  );
						  
				$this->db->insert("withdrawal", $dataArray);
				$this->db->insert("non_cash", $dataArray2);
				
	$memb=mysql_query("select mem_no,first_name,last_name,ipps from member where id='".$mem_id."'");
	$member=mysql_fetch_array($memb);
	$mem_name = $member['first_name']." ".$member['last_name'];
	$mem_no = $member['mem_no'];
	$mem_no = $member['ipps'];
				
	 $action="insert into withdrawal set memaccount_id=$mem_id,amount='".$amount."',voucher_no='".$voucher_no."',bank_account=$bank_id,date='".$date."', trans_date='".$trans_date."',branch_id=$branch_id";
        
         $msg ="Registered amount ".$amount.",voucher_no ".$voucher_no.",date ".$date.", on ".$trans_date." for member  '".$mem_name."' member No. '".$mem_no."' IPPS No. '".$ipps."'";
	 log_action($_SESSION['user_id'],$action,$msg);		 
	 $action1="insert into non_cash set debit=448,creditedSavingsAcct=$mem_id,amount=1000,date='".$date."',description='Monthly Subscription Refund'";
        
         $msg1 ="Registered an a non cash refund amount 1000 on ".$date." to the savings account of member  '".$mem_name."' member No. '".$mem_no."' IPPS No. '".$ipps."'";
	 log_action($_SESSION['user_id'],$action1,$msg1);						
				
			}
			return "Withdrawals Import Successful";
		}
}
?>
