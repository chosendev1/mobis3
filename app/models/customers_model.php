<?php
class Customers_model extends CI_Model{
	public function __construct(){
		$this->load->database();
	}
	
	/*public function get_news($slug = FALSE){
		if($slug === FALSE){
			$query = $this->db->get('news');
			return $query->result_array();
		}
		$query = $this->db->get_where('news', array('slug' => $slug));
		return $query->row_array();
	}
	
	public function set_news(){
		$this->load->helper('url');
		$slug = url_title($this->input->post('title'),'dash',true);
		$data = array(
				'title' => $this->input->post('title'),
				'slug' => $slug,
				'text' => $this->input->post('text')
			);
			return $this->db->insert('news',$data);
				
	}*/
	
	public function uploadCustomers($company,$branchId,$dbi){
		
			$n = 0;
			if(count($dbi) > 0)
				$n = count($dbi['fname']);
				$arr=array();
				$grpArr=array();
				$branch_prefix = libinc::getItemById("branch",$branchId,"branch_no","prefix"); 
		                $last_res = @mysql_query("select mem_no from member where group_id =0 and branch_id='".$branchId."'");
				
				//individuals
				if(mysql_num_rows($last_res) > 0){
					while($mem=mysql_fetch_array($last_res)){
					array_push($arr,preg_replace("/^".$branch_prefix."/", "", $mem['mem_no']));
					}
								
					$last_memNo=max($arr);
					$mem_no = intval($last_memNo) + 1;				
					
					
				}
				else
				$mem_no = 1;
				//echo max($arr);
				//exit;						
				
			for($i=2;$i <= $n+1; ++$i){
				
				$group_no=$dbi['grpNo'][$i];	
				
				if(!empty($group_no)){
				$non_ind=1;
				
				$gp=@mysql_query("select id from loan_group where group_no='".$group_no."' and branch_id='".$branchId."'");
				
				$gpId = @mysql_fetch_array($gp);
				$group_id=$gpId['id'];
				
				$last_res = @mysql_query("select mem_no from member where group_id ='".$group_id."' and branch_id='".$branchId."' and non_individual= 1");      
				
				if(mysql_num_rows($last_res) > 0){
				while($mem=mysql_fetch_array($last_res)){  
				$grpNo=preg_replace("/^".$branch_prefix."/", "", $mem['mem_no']);
				array_push($grpArr,preg_replace("/^GP".$group_no."/", "", $grpNo));
				}
				$last_memNo=max($grpArr);
			        $mem_no = intval($last_memNo) + 1;
			    
				}  
				else
				$mem_no = 1;
				$new_mem_no = $branch_prefix."GP".$group_no.str_pad($mem_no,2,"0",STR_PAD_LEFT);
				
				}				
				else{						
				$new_mem_no = $branch_prefix. str_pad($mem_no, 3, "0", STR_PAD_LEFT);
				$group_id=0;
				$non_ind=0;
				}	        
					
				$dataArray = array("branch_no" 	=> $branchId, 
						"first_name" 	=> $dbi['fname'][$i], 
						"last_name" 	=> $dbi['lname'][$i], 
						"telno" 	=> $dbi['phone'][$i], 
						"email"		=> $dbi['email'][$i], 
						"address" 	=> $dbi['address'][$i], 
						"kin" 		=> $dbi['kin'][$i], 
						"kin_telno" 	=> $dbi['kinphone'][$i], 
						"dob" 		=> $dbi['dob'][$i],
						"dor" 		=> $dbi['dor'][$i], 
						"sex"		=> $dbi['sex'][$i],
						"branch_id" 	=> $branchId,
						"mem_no"        => $new_mem_no,
						"group_id"	=> $group_id,
						"non_individual"  => $non_ind,
						"ipps"		=> $dbi['ipps'][$i]
						);
						//var_dump($dataArray);
						
				$q = $this->db->insert("member", $dataArray);
				
				//if(!empty($group_no))
				//$q = $this->db->insert("group_member", $grpArray);
				$mem_no++;
				if(!$q)
				return mysql_error();
				
			}
			//die(1);
			return "Success";
		}
		
}
?>
