<?php
/************************************************************************
 *@filename pmt_income_statement_class.php 				*
 *@date 2011-03-25 FRI							*
 *@author Noah Nambale [namnoah@gmail.com]				*
 *TODO									*
 *get report request							*
 *get queries								*
 *calculate amount							*
 *store values in array							*
 *calculate formular field values					*
 *print to HTML								*
 *print to XML								*
 *Copyright (c) FLT 2007 - 2011						*
 ************************************************************************/
class pmt_balancesheet extends pmt_statement{
	private $month;
	/**
 *class constructor, sets report name eg balance_sheet
 *@example $pmt_bal_sheet = new pmt_statement("Balance Sheet",
 *"basic","2009","monthly","2009","01");
 */
	
	function pmt_balancesheet($report,$basis,$year,$month,$period){
		$this->reportName=$report;
		$this->basis=$basis;
		$this->year=$year;
		$this->period=$period;
		$this->from_month=$month;
		$this->month=$month;
		$this->from_year=$year;
		$this->setInc();
		
	}
	
	
	/**
 *get the amount for the previous year given current year
 */
	private function getPrevAmount(){
		$to_date = sprintf("%d-%02d-01 23:59:59", (int)$this->year-1, $this->month);
		$result=mysql_fetch_array(mysql_query(str_replace("to_date",$to_date,$this->query))) or die(mysql_error());
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
				//$now_start = $calc->beginOfMonthBySpan($x, $this->from_month, $this->from_year, '%Y-%m-%d 00:00:00');
				$now_end = $calc->endOfMonthBySpan($x+$ext, $this->month, (int)$this->year-1, '%Y-%m-%d 23:59:59');
				 $result = mysql_fetch_array(mysql_query(str_replace("to_date",$now_end,$this->query)));
				 !isset($this->amount[$this->modifier][$x]) ? $this->amount[$this->modifier][$x]=(!isset($result['amount']) ? 0 : $result['amount']):$this->amount[$this->modifier][$x]+=(!isset($result['amount']) ? 0 : $result['amount']);
				 $this->total_amount[$this->modifier]=$this->amount[$this->modifier][$x];
		}
	}
	/**
	 * net surplus for current year
	 */
	function getSurplus(){
		$this->modifier="B35";
		$this->format[$this->modifier]="";
		$this->fieldName[$this->modifier]="Retained surplus/(deficit) current year";
		$calc= new Date_Calc();
		for($x=0;$x<NO_OF_MONTHS;$x+=$this->inc){
				$ext=$this->inc>1?$this->inc:0;
				//$now_start = $calc->beginOfMonthBySpan($x, $this->from_month, $this->from_year, '%Y-%m-%d 00:00:00');
				$now_end = $calc->endOfMonthBySpan($x+$ext, $this->mon, (int)$this->year-1, '%Y-%m-%d 23:59:59');
				 $result = net_cummulated_income('', $now_end, $this->basis);
				 !isset($this->amount[$this->modifier][$x]) ? $this->amount[$this->modifier][$x]=(!isset($result) ? 0 : $result):$this->amount[$this->modifier][$x]+=(!isset($result) ? 0 : $result);
				 $this->total_amount[$this->modifier]=$this->amount[$this->modifier][$x];
		}
	}
}
?>
