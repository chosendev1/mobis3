<?php
class Pmt_model extends CI_Model{
	public function __construct(){
		$this->load->database();
	}
	
	
	public function membersKPIs(){
		
			
		$membersData=$this->db->query("select Details, currentperiod as 'CurrentPeriod', previousmonth as 'PrevousPeriod' , ((currentperiod - previousmonth)/previousmonth) as 'percentageGrowth'

					from (

					select 'Total No. of Members/Clients' as Details, 
					count(case when ( YEAR(timestamp) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) and  MONTH(timestamp) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) ) THEN 1 END) as previousmonth,
					count(case when  ( YEAR(timestamp) = YEAR(CURRENT_DATE) and MONTH(timestamp) = MONTH(CURRENT_DATE ) ) THEN 1 END) as currentperiod

					from member pt 

					    
					    ) data


					union

					select Details, currentperiod as 'CurrentPeriod', previousmonth as 'PrevousPeriod' , ((currentperiod - previousmonth)/previousmonth) as 'percentageGrowth'

					from (

					select 'Total Female Members' as Details, 
					count(case when ( YEAR(timestamp) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) and  MONTH(timestamp) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) ) THEN 1 END) as previousmonth,
					count(case when  ( YEAR(timestamp) = YEAR(CURRENT_DATE) and MONTH(timestamp) = MONTH(CURRENT_DATE ) ) THEN 1 END) as currentperiod

					from member pt 
					where  pt.sex ='F'
					group by pt.sex
					    
					    ) data
	
					union

					select 

					  Details , 
					   currentperiod/currentperiod  as 'CurrentPeriod',
					   previousmonth/previousmonthtotal as  'PrevousPeriod',
					   (( currentperiod/currentperiod) - (previousmonth/previousmonthtotal))/(previousmonth/previousmonthtotal) as 'percentageGrowth'
					  
					  from

					(
					select 'Percentage of Female' as Details, 
					count(case when ( YEAR(timestamp) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) and MONTH(timestamp) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) ) THEN 1 END) as previousmonthtotal, 

					count(case when ( pt.sex='F' and YEAR(timestamp) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) and MONTH(timestamp) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) ) THEN 1 END) as previousmonth,

					count(case when ( pt.sex='F' and YEAR(timestamp) = YEAR(CURRENT_DATE) and MONTH(timestamp) = MONTH(CURRENT_DATE ) ) THEN 1 END) as currentperiod ,

					count(case when ( YEAR(timestamp) = YEAR(CURRENT_DATE) and MONTH(timestamp) = MONTH(CURRENT_DATE ) ) THEN 1 END) as currentperiodtotal

					from member pt 

					) data");
		
			return $membersData->result();
		}
		
		
		public function loansKPIs(){
			$loansData=$this->db->query("select  
				   Details, 
				   currentperiod as 'CurrentPeriod',
				   previousmonth as 'PrevousPeriod' , 
				   ((currentperiod - previousmonth)/previousmonth) as 'percentageGrowth'
				  from
				(

				select 'Total Loan Amount Disbursed ' as Details,

				sum(case when ( YEAR(d.date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) and  MONTH(d.date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) ) THEN d.amount END) as previousmonth,
				sum(case when  ( YEAR(d.date) = YEAR(CURRENT_DATE) and MONTH(d.date) = MONTH(CURRENT_DATE ) ) THEN d.amount END) as currentperiod

				from disbursed d
				left join approval a on a.id= d.approval_id
				left join member m on m.id = a.mem_id
				)  data


				union


				select  
				   Details, 
				   currentperiodamount/currentperiod as 'CurrentPeriod',
				   previousmonthamount/previousmonth as 'PrevousPeriod' , 
				   (((currentperiodamount/currentperiod) - (previousmonthamount/previousmonth))/(previousmonthamount/previousmonth)) as 'percentageGrowth'
				  from
				(

				select 'Average Amount Disbursed ' as Details,
				sum(case when ( YEAR(d.date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) and  MONTH(d.date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) ) THEN d.amount END) as previousmonthamount,
				sum(case when ( YEAR(d.date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) and  MONTH(d.date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) ) THEN 1 END) as previousmonth,
				sum(case when  ( YEAR(d.date) = YEAR(CURRENT_DATE) and MONTH(d.date) = MONTH(CURRENT_DATE ) ) THEN d.amount END) as currentperiodamount,
				sum(case when  ( YEAR(d.date) = YEAR(CURRENT_DATE) and MONTH(d.date) = MONTH(CURRENT_DATE ) ) THEN 1 END) as currentperiod


				from disbursed d
				left join approval a on a.id= d.approval_id
				left join member m on m.id = a.mem_id
				)  data


				union


				select  
				   Details, 
				   currentperiod as 'CurrentPeriod',
				   previousmonth as 'PrevousPeriod' , 
				   ((currentperiod - previousmonth)/previousmonth) as 'percentageGrowth'
				  from
				(

				select 'Total No. of Loan Disbursed ' as Details,

				sum(case when ( YEAR(d.date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) and  MONTH(d.date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) ) THEN 1 END) as previousmonth,
				sum(case when  ( YEAR(d.date) = YEAR(CURRENT_DATE) and MONTH(d.date) = MONTH(CURRENT_DATE ) ) THEN 1 END) as currentperiod

				from disbursed d
				left join approval a on a.id= d.approval_id
				left join member m on m.id = a.mem_id
				)  data

				union

				-- total loans disbursed Female
				select  
				   Details, 
				   currentperiod as 'CurrentPeriod',
				   previousmonth as 'PrevousPeriod' , 
				   ((currentperiod - previousmonth)/previousmonth) as 'percentageGrowth'
				  from
				(

				select 'Total No. of Disbursements to Females' as Details,

				sum(case when ( YEAR(d.date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) and  MONTH(d.date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) ) THEN 1 END) as previousmonth,
				sum(case when  ( YEAR(d.date) = YEAR(CURRENT_DATE) and MONTH(d.date) = MONTH(CURRENT_DATE ) ) THEN 1 END) as currentperiod

				from disbursed d
				left join approval a on a.id= d.approval_id
				left join member m on m.id = a.mem_id
				where m.sex ='F'
				)  data");
		
			return $loansData->result();
		}
		
		public function depositsKPIs(){
		
			
		$depositsData=$this->db->query("select  
				   Details, 
				   currentperiod as 'CurrentPeriod',
				   previousmonth as 'PrevousPeriod' , 
				   ((currentperiod - previousmonth)/previousmonth) as 'percentageGrowth'
				  from
				(

				select 'Total  Amount Deposited ' as Details,

				sum(case when ( YEAR(d.date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) and  MONTH(d.date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) ) THEN d.amount END) as previousmonth,
				sum(case when  ( YEAR(d.date) = YEAR(CURRENT_DATE) and MONTH(d.date) = MONTH(CURRENT_DATE ) ) THEN d.amount END) as currentperiod

				from deposit d
				left join mem_accounts a on a.id= d.memaccount_id
				left join member m on m.id = a.mem_id
				)  data


				union


				select  
				   Details, 
				   currentperiodamount/currentperiod as 'CurrentPeriod',
				   previousmonthamount/previousmonth as 'PrevousPeriod' , 
				   (((currentperiodamount/currentperiod) - (previousmonthamount/previousmonth))/(previousmonthamount/previousmonth)) as 'percentageGrowth'
				  from
				(

				select 'Average Amount Deposited ' as Details,
				sum(case when ( YEAR(d.date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) and  MONTH(d.date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) ) THEN d.amount END) as previousmonthamount,
				sum(case when ( YEAR(d.date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) and  MONTH(d.date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) ) THEN 1 END) as previousmonth,
				sum(case when  ( YEAR(d.date) = YEAR(CURRENT_DATE) and MONTH(d.date) = MONTH(CURRENT_DATE ) ) THEN d.amount END) as currentperiodamount,
				sum(case when  ( YEAR(d.date) = YEAR(CURRENT_DATE) and MONTH(d.date) = MONTH(CURRENT_DATE ) ) THEN 1 END) as currentperiod

				from deposit d
				left join mem_accounts a on a.id= d.memaccount_id
				left join member m on m.id = a.mem_id

				)  data


				union


				select  
				   Details, 
				   currentperiod as 'CurrentPeriod',
				   previousmonth as 'PrevousPeriod' , 
				   ((currentperiod - previousmonth)/previousmonth) as 'percentageGrowth'
				  from
				(

				select 'Total No. of Deposits ' as Details,

				sum(case when ( YEAR(d.date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) and  MONTH(d.date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) ) THEN 1 END) as previousmonth,
				sum(case when  ( YEAR(d.date) = YEAR(CURRENT_DATE) and MONTH(d.date) = MONTH(CURRENT_DATE ) ) THEN 1 END) as currentperiod

				from deposit d
				left join mem_accounts a on a.id= d.memaccount_id
				left join member m on m.id = a.mem_id

				)  data


				union

				select Details, currentperiod as 'CurrentPeriod', previousmonth as 'PrevousPeriod' , ((currentperiod - previousmonth)/previousmonth) as 'percentageGrowth'

				from (

				select 'Total Female Deposits' as Details, 
				count(case when m.sex='F' and ( YEAR(timestamp) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) and  MONTH(timestamp) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) ) THEN 1 END) as previousmonth,
				count(case when  m.sex='F' and  ( YEAR(timestamp) = YEAR(CURRENT_DATE) and MONTH(timestamp) = MONTH(CURRENT_DATE ) ) THEN 1 END) as currentperiod

				from deposit d
				left join mem_accounts a on a.id= d.memaccount_id
				left join member m on m.id = a.mem_id
				    
				    ) data
	
	
					union

				select 

				  Details , 
				   currentperiod/currentperiod  as 'CurrentPeriod',
				   previousmonth/previousmonthtotal as  'PrevousPeriod',
				   (( currentperiod/currentperiod) - (previousmonth/previousmonthtotal))/(previousmonth/previousmonthtotal) as 'percentageGrowth'
				  
				  from

				(
				select 'Percentage of Female Deposits' as Details, 
				count(case when ( YEAR(d.date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) and MONTH(d.date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) ) THEN 1 END) as previousmonthtotal, 

				count(case when ( pt.sex='F' and YEAR(d.date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) and MONTH(d.date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) ) THEN 1 END) as previousmonth,

				count(case when ( pt.sex='F' and YEAR(d.date) = YEAR(CURRENT_DATE) and MONTH(d.date) = MONTH(CURRENT_DATE ) ) THEN 1 END) as currentperiod ,

				count(case when ( YEAR(d.date) = YEAR(CURRENT_DATE) and MONTH(d.date) = MONTH(CURRENT_DATE ) ) THEN 1 END) as currentperiodtotal

				from deposit d
				left join mem_accounts a on a.id= d.memaccount_id
				left join member pt on pt.id = a.mem_id


				) data");
		
			return $depositsData->result();
		}	
				
		public function withdrawalsKPIs(){
		
			
		$withdrawalsData=$this->db->query("select  
				   Details, 
				   currentperiod as 'CurrentPeriod',
				   previousmonth as 'PrevousPeriod' , 
				   ((currentperiod - previousmonth)/previousmonth) as 'percentageGrowth'
				  from
				(

				select 'Total  Amount Withdrawn ' as Details,

				sum(case when ( YEAR(d.date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) and  MONTH(d.date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) ) THEN d.amount END) as previousmonth,
				sum(case when  ( YEAR(d.date) = YEAR(CURRENT_DATE) and MONTH(d.date) = MONTH(CURRENT_DATE ) ) THEN d.amount END) as currentperiod

				from withdrawal d
				left join mem_accounts a on a.id= d.memaccount_id
				left join member m on m.id = a.mem_id
				)  data


				union


				select  
				   Details, 
				   currentperiodamount/currentperiod as 'CurrentPeriod',
				   previousmonthamount/previousmonth as 'PrevousPeriod' , 
				   (((currentperiodamount/currentperiod) - (previousmonthamount/previousmonth))/(previousmonthamount/previousmonth)) as 'percentageGrowth'
				  from
				(

				select 'Average Amount Withdrawn ' as Details,
				sum(case when ( YEAR(d.date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) and  MONTH(d.date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) ) THEN d.amount END) as previousmonthamount,
				sum(case when ( YEAR(d.date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) and  MONTH(d.date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) ) THEN 1 END) as previousmonth,
				sum(case when  ( YEAR(d.date) = YEAR(CURRENT_DATE) and MONTH(d.date) = MONTH(CURRENT_DATE ) ) THEN d.amount END) as currentperiodamount,
				sum(case when  ( YEAR(d.date) = YEAR(CURRENT_DATE) and MONTH(d.date) = MONTH(CURRENT_DATE ) ) THEN 1 END) as currentperiod

				from withdrawal d
				left join mem_accounts a on a.id= d.memaccount_id
				left join member m on m.id = a.mem_id

				)  data


				union


				select  
				   Details, 
				   currentperiod as 'CurrentPeriod',
				   previousmonth as 'PrevousPeriod' , 
				   ((currentperiod - previousmonth)/previousmonth) as 'percentageGrowth'
				  from
				(

				select 'Total No. of withdrawals ' as Details,

				sum(case when ( YEAR(d.date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) and  MONTH(d.date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) ) THEN 1 END) as previousmonth,
				sum(case when  ( YEAR(d.date) = YEAR(CURRENT_DATE) and MONTH(d.date) = MONTH(CURRENT_DATE ) ) THEN 1 END) as currentperiod

				from withdrawal d
				left join mem_accounts a on a.id= d.memaccount_id
				left join member m on m.id = a.mem_id

				)  data


				union

				select Details, currentperiod as 'CurrentPeriod', previousmonth as 'PrevousPeriod' , ((currentperiod - previousmonth)/previousmonth) as 'percentageGrowth'

				from (

				select 'Total Female Withdrawals' as Details, 
				count(case when m.sex='F' and ( YEAR(timestamp) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) and  MONTH(timestamp) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) ) THEN 1 END) as previousmonth,
				count(case when  m.sex='F' and  ( YEAR(timestamp) = YEAR(CURRENT_DATE) and MONTH(timestamp) = MONTH(CURRENT_DATE ) ) THEN 1 END) as currentperiod

				from withdrawal d
				left join mem_accounts a on a.id= d.memaccount_id
				left join member m on m.id = a.mem_id
				    
				    ) data
	
	
					union

				select 

				  Details , 
				   currentperiod/currentperiod  as 'CurrentPeriod',
				   previousmonth/previousmonthtotal as  'PrevousPeriod',
				   (( currentperiod/currentperiod) - (previousmonth/previousmonthtotal))/(previousmonth/previousmonthtotal) as 'percentageGrowth'
				  
				  from

				(
				select 'Percentage of Female Withdrawals' as Details, 
				count(case when ( YEAR(d.date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) and MONTH(d.date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) ) THEN 1 END) as previousmonthtotal, 

				count(case when ( pt.sex='F' and YEAR(d.date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) and MONTH(d.date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) ) THEN 1 END) as previousmonth,

				count(case when ( pt.sex='F' and YEAR(d.date) = YEAR(CURRENT_DATE) and MONTH(d.date) = MONTH(CURRENT_DATE ) ) THEN 1 END) as currentperiod ,

				count(case when ( YEAR(d.date) = YEAR(CURRENT_DATE) and MONTH(d.date) = MONTH(CURRENT_DATE ) ) THEN 1 END) as currentperiodtotal

				from withdrawal d
				left join mem_accounts a on a.id= d.memaccount_id
				left join member pt on pt.id = a.mem_id


				) data");
		
			return $withdrawalsData->result();
		}			
				
}
?>
