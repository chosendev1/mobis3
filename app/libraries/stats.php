<?php
/*@fileName: stats.php
 *@date: 2014-11-16 09:18 CAT
 *@author: Noah Nambale [namnoah@gmail.com]
 */
 class stats {
 	
 	public static function numberOfStaff(){
 		$ci =& get_instance();
 		$sth = $ci->db->query("SELECT * FROM employees");
		if($sth->num_rows() > 0)
			return count($sth->result());
		return 0;
 	}
 	
 	public static function numberOfUsers(){
 		$ci =& get_instance();
 		$sth = $ci->db->query("SELECT * FROM users");
		if($sth->num_rows() > 0)
			return count($sth->result());
		return 0;
 	}
 	
 	
 }
?>
