<?php
/*Filename: pmt_potfolio_class.php
 *@date created 2011-06-07 (TUE)
 *@TODO
 *@author Noah Nambale [namnoah@gmail.com]
 *inherit the properties of income statement
 *Override some functions where necessary
 *Store the portfolio results in arrays
 *
 */

class pmt_portfolio extends pmt_statement{
	public $isAmount = true;
	
	function pmt_portfolio($report,$basis,$year,$period,$fromYear,$fromMonth){
                $this->reportName=$report;
                $this->basis=$basis;
                $this->year=$year;
                $this->period=$period;
                $this->from_month=$fromMonth;
                $this->from_year=$fromYear;
                $this->setInc();

        }
        
        function setQuery($query,$modifier,$title,$format,$isAmountValue = true){
		$this->query=$query;
		$this->modifier=$modifier;
		$this->format[$this->modifier]=$format;
		$this->fieldName[$modifier]=$title;
		$this->isAmount = $isAmountValue;
		$this->getAmount();
	}
        
       /* function ageing(){
        	$loans = array();
        	$days = mysql_query("select d.id as loan_id,(sum(s.princ_amt)-sum(p.princ_amt))/(d.amount/d.period) as missed_days from where s.date <='".$this->to_date."' and p.date <='".$this->to_date."'");
        	while($row)
        	
        }*/
        
        /**
 *generate  HTML from stored values for display
 *can display  1 modifier, 2,.. or all at once
 *@example 
 *$pmt->printToHTML(); //this will print all
 *$pmt->printTOHTML("Y01,Y02"); //mixed modifiers
 */
	/*function printToHTML($modifier="all"){
		
		if($modifier!="all"){
			$modifiers=explode(",",$modifier);
			$content=$this->year."Hello world";
			$c=0;
			foreach($modifiers as $mod){
				$color=$c%2==1?"lightgrey":"white";
				
				if(array_key_exists($mod,$this->fieldName)){
					if(count($this->amount[$mod])==0){
						$content.="<tr class=headings><td colspan=16><font size=3pt><b>".$this->fieldName[$mod]."</b></font></td></tr>";
					}
					else{
						$bopn=$this->format[$mod]=="b"?"<b>":NULL;
						$bcls=$this->format[$mod]=="b"?"</b>":NULL;
						if($$this->isAmount==false)
							$content .= "<tr bgcolor='".$color."'><td>".$bopn.$modifier.$bcls."</td><td>".$bopn.$this->fieldName[$mod].$bcls."</td><td>".$bopn.number_format($this->prev_amount[$mod],2).$bcls."</td>";
						else
							$content .= "<tr bgcolor='".$color."'><td>".$bopn.$modifier.$bcls."</td><td>".$bopn.$this->fieldName[$mod].$bcls."</td><td>".$bopn.number_format($this->prev_amount[$mod],0).$bcls."</td>";
						$i=0;
						while($i<NO_OF_MONTHS){
							if($this->isAmount==true)
								$content.="<td>".$bopn.number_format($this->amount[$mod][$i],2).$bcls."</td>";
							else
								$content.="<td>".$bopn.number_format($this->amount[$mod][$i],0).$bcls."</td>";
							$i+=$this->inc;
						}
						if($this->isAmount==true)
							$content.="<td>".$bopn.number_format($this->total_amount[$mod],2).$bcls."</td></tr>";
						else
							$content.="<td>".$bopn.number_format($this->total_amount[$mod],0).$bcls."</td></tr>";
					}
				
				}
				else
					throw new Exception("1001: Unknown Modifier.");
				$c++;
					
			}
			return $content;
		}
			
			else{
					$c=0;
					foreach($this->fieldName as $mod=>$name){
						if(count($this->amount[$mod])==0){
						$content.="<tr class=headings><td colspan=16><font size=5pt><b>".$this->fieldName[$mod]."</b></font></td></tr>";
						}
						else{
							$color=$c%2==1?"lightgrey":"white";
							$bopn=$this->format[$mod]=="b"?"<b>":NULL;
							$bcls=$this->format[$mod]=="b"?"</b>":NULL;
							if($this->isAmount == true)
								$content.="<tr bgcolor='".$color."'><td>".$bopn.$mod.$bcls."</td><td>".$bopn.$name.$bcls."</td><td>".$bopn.number_format($this->prev_amount[$mod],2).$bcls."</td>";
							else
								$content.="<tr bgcolor='".$color."'><td>".$bopn.$mod.$bcls."</td><td>".$bopn.$name.$bcls."</td><td>".$bopn.number_format($this->prev_amount[$mod],0).$bcls."</td>";
							$i=0;
							while($i<NO_OF_MONTHS){
							if($this->isAmount == true)
								$content.="<td>".$bopn.number_format($this->amount[$mod][$i],2).$bcls."</td>";
							else
								$content.="<td>".$bopn.number_format($this->amount[$mod][$i],0).$bcls."</td>";
								$i+=$this->inc;
							}
							if($this->isAmount == true)
								$content.="<td>".$bopn.number_format($this->total_amount[$mod],2).$bcls."</td></tr>";
							else
								$content.="<td>".$bopn.number_format($this->total_amount[$mod],0).$bcls."</td></tr>";
							$c++;
						}
					}
					return $content;
			}	
		
	}*/
	
	

}
?>
