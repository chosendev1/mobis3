<?php
class Excel_export_model extends CI_Model{
 
               public function __construct(){
		parent::__construct();
	}
	    
    function fetch_data($from_date,$to_date,$account,$branch){
                        $data=array();
			
       //$liabilities = $this->db->query("select r.bank_account, ac.id as acc_id, ac.name as acc_name, ac.account_no as account_no, r.id as rid, r.amount as amount, r.date as date, r.mode,r.transaction as transaction from accounts ac right join payable r on r.account_id = ac.id");
       //$liabilities = $this->db->query("select r.date as date,ac.account_no as account_no,r.transaction as transaction, r.mode, r.amount as amount from accounts ac right join payable r on r.account_id = ac.id");
       
                       list($from_year,$from_month,$from_mday) = explode('-', $from_date);
                       list($to_year,$to_month,$to_mday) = explode('-', $to_date);
                       $from_date = sprintf("%04d-%02d-%02d 00:00:00", $from_year, $from_month, $from_mday);
	               $to_date = sprintf("%04d-%02d-%02d 23:59:59",  $to_year, $to_month, $to_mday);
	
	               $branch = ($branch_id=='all'||$branch_id=='')?NULL:"and branch_id=".$branch_id;
       
          $res = @mysql_query("select r.bank_account as bank_account, ac.id as acc_id, ac.name as acc_name, ac.account_no as account_no, r.id as rid, r.amount as amount, r.date as date, r.mode as mode,r.transaction as transaction from accounts ac right join payable r on r.account_id = ac.id where r.date >= '".$from_date."' and r.date <= '".$to_date."' and r.account_id='".$account."' ".$branch." order by r.date asc");
	  //$acct= mysql_fetch_array($res);
	  //$account=$acct['account_no'].'-'.$acct['acc_name'];
	  while($row= mysql_fetch_array($res)){
       
       			$newDate = date('Y-m-d',strtotime($row['date']));
                        list($y,$m,$d)=explode("-",$newDate);
                        $date=$d.'/'.$m.'/'.$y;
   
                        if($row['mode'] <> 'cash'){
			$memName = mysql_query("select m.first_name as first_name, m.last_name as last_name, m.mem_no as mem_no, ma.id as memacc_id, a.name as name from member m join mem_accounts ma on m.id = ma.mem_id join savings_product p on ma.saveproduct_id = p.id join accounts a on p.account_id = a.id where ma.id =".$row['mode']." ");
			$rows = mysql_fetch_array($memName);
			$savings= $rows['first_name']."". $rows['last_name']."-".$rows['mem_no']." ".$rows['name'];
			$memNo=$rows['mem_no'];
			}
			else
			$memNo="";
						
			$cash = mysql_fetch_array(mysql_query("select a.name as name, a.account_no as account_no from bank_account b join accounts a on b.account_id=a.id where b.id='".$row['bank_account']."'"));
			
			$mode = ($row['mode'] == 'cash') ? "Cash to - ".$cash['account_no']."-".$cash['name'] : $savings;
			
			$row_data=array('date'=>$date,'account'=>$row['account_no'].'-'.$row['acc_name'],'transaction'=>$row['transaction'],'mode'=>$mode,'memberNo'=>$memNo,'debit'=>"--",'credit'=>$row['amount']);
			array_push($data,$row_data);
			}
			
       return $data; 
    }

      public function customers_count() {
           return $this->db->count_all("member");
                }
      
      public function fetch_customers($limit, $start) {
             $this->db->limit($limit, $start);
             $query = $this->db->get("member");

             if ($query->num_rows() > 0) {
                 foreach ($query->result() as $row) {
                     $data[] = $row;
                }
             return $data;
            }
            return false;
          }
          
      //public function fetch_customers($limit, $start) {
                    /*   public function fetch_customers($branchId) {
                        //$this->db->limit($limit, $start);
                         //$query = $this->db->query("member");
                         //$query = $this->db->get_where('mytable', array('id' => $id), $limit, $offset);
                         $query = $this->db->get_where('member', array('branch_no' => $branchId));
                         
                          if ($query->num_rows() > 0) {
                             foreach ($query->result() as $row) {
                                $data[] = $row;
                                  }
                              return $data;
                            }
                              return false;
                             }	
                   */
                   
      public function savings_count() {
           return $this->db->count_all("mem_accounts");
                }
          
      public function fetch_savings($limit, $start) {
             $this->db->limit($limit, $start);
             $query = $this->db->get("mem_accounts");

             if ($query->num_rows() > 0) {
                 foreach ($query->result() as $row) {
                     $data[] = $row;
                }
             return $data;
            }
            return false;
          }
          
      public function loans_count() {
           return $this->db->count_all("loan_applic");
                }
          
      public function fetch_loans($limit, $start) {
             $this->db->limit($limit, $start);
             $query = $this->db->get("loan_applic");

             if ($query->num_rows() > 0) {
                 foreach ($query->result() as $row) {
                     $data[] = $row;
                }
             return $data;
            }
            return false;
          }


    function getLiabAccount($id){
       if(empty($id) or $id=='all')
       $libAcct='All Liability Accounts';
       else{
       $acct = mysql_fetch_array(mysql_query("select name, account_no from  accounts where id='".$id."'"));
       $libAcct=$acct['account_no'].'-'.$acct['name'];
       }
       return $libAcct;
      }
    
    public function disbursed_count() {
           return $this->db->count_all("disbursed");
                }
          
    public function fetch_disbursed($limit, $start) {
             $this->db->limit($limit, $start);
             $query = $this->db->get("disbursed");
             
             if ($query->num_rows() > 0) {
                 foreach ($query->result() as $row) {
                     $data[] = $row;
                }
             return $data;
            }
            return false;
          }
}
    
?>
