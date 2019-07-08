<?php
class Student_model extends CI_Model{

	public function __construct(){
		parent::__construct();
	}
	
	public function loadStudents(){
		$sth = $this->db->query("SELECT s.registrationNumber as RegNo, CONCAT(s.firstName,' ',s.middleName,' ',s.lastName) as Name, o.organizationName as Department, p.programName as program, sp.sponsorName as Sponsor,s.phone as Phone, s.email as Email FROM students s JOIN organizations o on(o.organizationId=s.organizationId) JOIN programs p on(s.programId=p.programId) JOIN sponsors sp on(sp.sponsorId=s.sponsorId) WHERE s.academicYearId=9");
		if($sth->num_rows() > 0)
			return $sth;
		return array();
	}
}
?>
