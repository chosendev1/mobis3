<?php
/************************************************************************
 *@filename pmt_income_statement_class.php				*
 *@author Noah Nambale [namnoah@gmail.com] 				*
 *@date 2010-08-27 FRI							*
 *@update 2010-09-28 TUE						*
 *@update 2010-10-20//							*
 *TODO									*
 *get report request							*
 *get queries								*
 *calculate amount							*
 *store values in array							*
 *calculate formular field values					*
 *print to HTML								*
 *print to XML								*
 *Copyright (c) FLT 2007-2011						*
 ************************************************************************/
if(!defined("NO_OF_MONTHS")){
	define("NO_OF_MONTHS",12);
}

class pmt_statement{
	public $modifier;
	public $basis;
	public $year;
	public $period;
	public $reportName;
	public $fieldName=array();
	public $amount=array();
	public $total_amount=array();
	public $prev_amount=array();
	public $format=array();
	public $formating=array();
	public $from_day;
	public $from_month;
	public $from_year;
	public $inc;
	public $buf=array();
	public $formular;
/**
 *class constructor, sets report name eg balance_sheet
 *@example $pmt_bal_sheet = new pmt_statement("Balance Sheet",
 *"basic","2009","monthly","2009","01");
 */
	
	function pmt_statement($report,$basis,$year,$period,$fromYear,$fromMonth){
		$this->reportName=$report;
		$this->basis=$basis;
		$this->year=$year;
		$this->period=$period;
		$this->from_month=$fromMonth;
		$this->from_year=$fromYear;
		$this->setInc();
		
	}
/**
 *set the month and year for report display period.
 *this functiion must be called after instatiating the
 *class, 
 *@example
 *$pmt->setDates("2009","01");
 */
	function setDates($fromYear,$fromMonth){
		$this->from_month=$fromMonth;
		$this->from_year=$fromYear;
	}
/**
 *function setInc for setting the incrementer
 *depeding on the period set during instatiation
 *You wont need to use this anywhere unless you alter
 *or add a new range/period
 */
	 function setInc(){
		if($this->period=="monthly")
			$this->inc=1;
		if($this->period=="quarterly")
			$this->inc=3;
		return $this->from_year;
	}
/**
 *pass query with the amount field as amount, modifier,title
 *@example
 *$pmt->setQuery();
 */
	function setQuery($query,$modifier,$title,$format){
		$this->query=$query;
		$this->modifier=$modifier;
		$this->format[$this->modifier]=$format;
		$this->fieldName[$modifier]=$title;
		$this->getAmount();
	}
/**
 *get the amount for the previous year given current year
 */
	private function getPrevAmount(){
		$from_date = sprintf("%d-%02d-01 00:00:00", $this->from_year, $this->from_month);
		$last_from_date = sprintf("%d-%02d-01 00:00:00", (int)$this->from_year-1, $this->from_month);
		$result=mysql_fetch_array(mysql_query(str_replace("to_date",$last_from_date,str_replace("from_date",$from_date,$this->query))));
		!isset($this->prev_amount[$this->modifier]) ? $this->prev_amount[$this->modifier]=(!isset($result['amount']) ? 0.0:$result['amount']):$this->prev_amount[$this->modifier]+=(!isset($result) ? 0.0:$result['amount']);
	}
/**
 *get the value/amount given the query and selection range
 */
	function getAmount(){
		$this->getPrevAmount();
		$calc= new Date_Calc();
		for($x=0;$x<NO_OF_MONTHS;$x+=$this->inc){
				$ext=$this->inc>1?$this->inc:0;
				$now_start = $calc->beginOfMonthBySpan($x, $this->from_month, $this->from_year, '%Y-%m-%d 00:00:00');
				$now_end = $calc->endOfMonthBySpan($x+$ext, $this->from_month, $this->from_year, '%Y-%m-%d 23:59:59');
				 $result = mysql_fetch_array(mysql_query(str_replace("to_date",$now_start,str_replace("from_date",$now_end,$this->query))));
				 !isset($this->amount[$this->modifier][$x]) ? $this->amount[$this->modifier][$x]=(!isset($result['amount']) ? 0 : $result['amount']):$this->amount[$this->modifier][$x]+=(!isset($result['amount']) ? 0 : $result['amount']);
				 !isset($this->total_amount[$this->modifier]) ? $this->total_amount[$this->modifier]=$this->amount[$this->modifier][$x]:$this->total_amount[$this->modifier]+=$this->amount[$this->modifier][$x];
		}
	}
/**
 *like setQuery(), but this adds a fomular field in place of a query
 *@example
 *$pmt->addFormularField("Y11","TOTAL INTEREST","Y7+Y10");
 */
	function addFormularField($modifier,$title,$formular){
		try{
			$this->modifier=$modifier;
			$this->format[$this->modifier]="b";
			$this->fieldName[$this->modifier]=$title;
			$this->formular=$formular;
			$this->startTokenizer();
			$this->calcFormularAmount();
		}
		catch(Exception $ex){
			return $ex->getMessage();
		}
	}
/**
 *Reads formular as a string and returns tokens for the given expression
 */
	private function startTokenizer(){
		if(!preg_match("/[\+ \- \/ \*]/",$this->formular))
			throw new Exception("1002: Missing Operator in Fomular.");
		$this->buf=array();
		$tokens = str_split($this->formular);
		$tok="";
		$i=1;
		$no_of_tokens=count($tokens);
		foreach($tokens as $token){
			if($token<>""){
				if($token=="+"||$token=="-"||$token=="*"||$token=="/"){
					array_push($this->buf,$tok);
					array_push($this->buf,$token);
					$tok="";
				}
				else
					$tok=$tok==""?$token:$tok.$token;
			}
			if($i==$no_of_tokens)
				array_push($this->buf,$tok);
			$i++;
		}
		if(count($this->buf)%2!=1)
				throw new Exception("1003: Formular in Incorect format.");
	}
/**
 *Calculates and stores the resulting values of the formular in arrays
 *with the corresponding modifier.
 */
	private function calcFormularAmount(){
		$i=0;
		$value=0.0;
		$no_of_tokens=count($this->buf);
		while($i<$no_of_tokens){
			if($i==0){
				$this->prev_amount[$this->modifier]=$this->prev_amount[$this->buf[$i]];
				for($x=0;$x<NO_OF_MONTHS;$x+=$this->inc){
					$this->amount[$this->modifier][$x]=$this->amount[$this->buf[$i]][$x];
				}
				$this->total_amount[$this->modifier]=$this->total_amount[$this->buf[$i]];
			}
			elseif($i%2==0){
				switch($this->buf[$i-1]){
					case "+":
						$this->prev_amount[$this->modifier]+=$this->prev_amount[$this->buf[$i]];
						for($x=0;$x<NO_OF_MONTHS;$x+=$this->inc){
							$this->amount[$this->modifier][$x]+=$this->amount[$this->buf[$i]][$x];
						}
						$this->total_amount[$this->modifier]+=$this->total_amount[$this->buf[$i]];
					break;
					case "-":
						$this->prev_amount[$this->modifier]-=$this->prev_amount[$this->buf[$i]];
						for($x=0;$x<NO_OF_MONTHS;$x+=$this->inc){
							$this->amount[$this->modifier][$x]-=$this->amount[$this->buf[$i]][$x];
						}
						$this->total_amount[$this->modifier]-=$this->total_amount[$this->buf[$i]];
					break;
					case "*":
						$this->prev_amount[$this->modifier]*=$this->prev_amount[$this->buf[$i]];
						for($x=0;$x<NO_OF_MONTHS;$x+=$this->inc){
							$this->amount[$this->modifier][$x]*=$this->amount[$this->buf[$i]][$x];
						}
						$this->total_amount[$this->modifier]*=$this->total_amount[$this->buf[$i]];
					break;
					case "/":
						$this->prev_amount[$this->modifier]/=$this->prev_amount[$this->buf[$i]];
						for($x=0;$x<NO_OF_MONTHS;$x+=$this->inc){
							$this->amount[$this->modifier][$x]/=$this->amount[$this->buf[$i]][$x];
						}
						$this->total_amount[$this->modifier]/=$this->total_amount[$this->buf[$i]];
					break;
				}
			}
			$i++;
		}
	}
/**
 *store headings in the right position
 */
	function addHeading($heading){
		$this->modifier="T".count($this->fieldName);
		$this->fieldName[$this->modifier]=$heading;
		}
/**
 *generate  HTML from stored values for display
 *can display  1 modifier, 2,.. or all at once
 *@example 
 *$pmt->printToHTML(); //this will print all
 *$pmt->printTOHTML("Y01,Y02"); //mixed modifiers
 */
	function printToHTML($modifier="all"){
	    	$content='';
		
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
						$content.="<tr bgcolor='".$color."'><td>".$bopn.$modifier.$bcls."</td><td>".$bopn.$this->fieldName[$mod].$bcls."</td><td>".$bopn.number_format($this->prev_amount[$mod],2).$bcls."</td>";
						$i=0;
						while($i<NO_OF_MONTHS){
							$content.="<td>".$bopn.number_format($this->amount[$mod][$i],2).$bcls."</td>";
							$i+=$this->inc;
						}
						$content.="<td>".$bopn.number_format($this->total_amount[$mod],2).$bcls."</td></tr>";
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
						if(!isset($this->amount[$mod])){
						$content.="<tr class=headings><td colspan=16><font size=5pt><b>".$this->fieldName[$mod]."</b></font></td></tr>";
						}
						else{
							//$color=$c%2==1?"lightgrey":"white";
							$bopn=$this->format[$mod]=="b"?"<b>":NULL;
							$bcls=$this->format[$mod]=="b"?"</b>":NULL;
							$content.="<tr><td>".$bopn.$mod.$bcls."</td><td>".$bopn.$name.$bcls."</td><td>".$bopn.number_format($this->prev_amount[$mod],2).$bcls."</td>";
							$i=0;
							while($i<NO_OF_MONTHS){
								$content.="<td>".$bopn.number_format($this->amount[$mod][$i],2).$bcls."</td>";
								$i+=$this->inc;
							}
							$content.="<td>".$bopn.number_format($this->total_amount[$mod],2).$bcls."</td></tr>";
							$c++;
						}
					}
					return $content;
			}	
		
	}
	
	
	
/**
 *generate an XML document from the values in the arrays
 */	
	function printToXML($report="all"){
		switch(strtolower($report)){
			case "balance sheet":
				$this->printToXMLBalanceSheet();
			break;
			case "income statement":
				$this->printToXMLIncomeStatement();
			break;
			case "portfolio":
				$this->printToXMLPortfolio();
			break;
			default:
				$this->generateExportPMTXML();
		}
		return 1;
	}
/*
 *balance sheet to xml
 *
 */
	function printToXMLBalanceSheet(){
		$fileName = "pmt_balance_sheet.xml";
		$pmt_settings = mysql_query("select * from pmt_settings");
		$org_id = "";
		$org_name="";
		$currency="";
		$workbook_id="57124196397522658";
		while($pmt=mysql_fetch_array($pmt_settings)){
			switch(strtolower($pmt['field'])){
				case "company name":
					$org_name = $pmt['value'];
				break;
				case "report code":
					$org_id = $pmt['value'];
					break;
				case "currency":
					$currency = $pmt['value'];
				break;
				case "workbook_id":
				default:
				break;
			}
			}
		$currency = $currency==""||$currency==NULL?"UGX":$currency;
		$periodicity = $this->period=="monthly"?1:4;
		$hashVal = md5($org_id.$org_name);
		$data = "<?xml version='1.0' encoding='UTF-8'?>
  <PMT  version=\"1.0\" org_id=\"".$org_id."\" org_name=\"".$org_name."\" periodicity_id=\"".$periodicity."\" currency_id=\"".$currency."\" basis_id=\"1\" comments=\" \" workbook_id=\"".$workbook_id."\" fiscal_year=\"".$this->year."\" wkb_type_id=\"1\" hash=\"".$hashVal."\">
        <period-data  period=\"1\">
            <field name=\"report_code\">
        ".$this->reportName."
      </field>";
      	foreach($this->total_amount as $name => $val){
      		$data .= '<field name="'.$name.'">';
      		$data .=$val;
      		$data .="</field>";

	}
	$fp = fopen($fileName,'w') or die("Cannot write to file");
	fwrite($fp,$data);
	fclose($fp);
	
	}
}
?>
