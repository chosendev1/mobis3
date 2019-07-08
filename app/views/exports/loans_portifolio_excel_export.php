            <form class="form-horizontal" action="<?php echo base_url()?>reports/exportLoansPortifolio" method="post">
            	
		    <input type="hidden" name="records" value="<?php echo $records ?>">
		    <input type="hidden" name="page" value="<?php echo $page ?>">
		    <input type="hidden" name="date" value="<?php echo $date ?>">
		    
                    <button class="btn btn-success" type="submit">Export to Excel</button>
                    </form>
                    </div>                        
                    <div>
                    <div>
                                <!-- h4 class="text-primary mt0 mb5">Transactions for <?php echo $liabAcct?> From <?php echo $fdate?> to <?php echo $tdate?></h4> -->
                            </div>
 	                    <table> 
 	                   <thead><th>#</th><th>LOAN No.</th><th>NAME</th><th>MEMBER NO.</th><th>DISBURSEMENT DATE</th><th>DISBURSED AMOUNT</th><th>PRINCIPAL DUE</th><th>INTEREST DUE</th><th>TOTAL DUE</th><th>PRINCIPAL PAID</th><th>INTEREST PAID</th><th>TOTAL PAID</th><th>PRINCIPAL ARREARS</th><th>INTEREST ARREARS</th><th>TOTAL ARREARS</th><th>OUTSTANDING BALANCE</th><th>PRINCIPAL PAID(%AGE)</th></thead><tbody>                 	
 	                       	                                   
                        <?php
                  if(!empty($date))
                  $asAtDate=$date;
                  if(!empty($this->session->userdata('ddate')))
                  $asAtDate=$this->session->userdata('ddate');
                  $x=0;                     
                  foreach($portifolio as $row){
                  $x++;         
                  $principalDue=libinc::principalDue($row->applic_id,$asAtDate);
		  $interestDue=libinc::interestDue($row->applic_id,$asAtDate);
		  $principalToBePaid=libinc::principalToBePaid($row->applic_id,$asAtDate);
                  $interestToBePaid=libinc::interestToBePaid($row->applic_id,$asAtDate);
		  $principalPaid=libinc::principalPaid($row->applic_id,$asAtDate);
		  $interestPaid=libinc::interestPaid($row->applic_id,$asAtDate);
		  $outstandingBal=libinc::loanBalance($row->applic_id,$asAtDate);
		 // $principalArrears=libinc::principalArrears($row->applic_id,$asAtDate);
		 // $interestArrears=libinc::interestArrears($row->applic_id,$asAtDate); 
		 $principalArrears=$principalToBePaid-$principalPaid;
		 $interestArrears=$interestToBePaid-$interestPaid;
		      
		if(number_format($principalArrears) < 0)
		$principalArrears=0;
		if(number_format($interestArrears) < 0)
		$interestArrears=0;

		if($principalPaid >0 && ($principalPaid <=$row->amount))
		$percentPrincPaid=($principalPaid/$row->amount)*100; //
		if($principalPaid >$row->amount)
		$percentPrincPaid=100;
		/*
		if($interestPaid >0 && ($interestPaid <=$totalInterest));
		$percentIntPaid=($interestPaid/$totalInterest)*100;
		if($interestPaid > $totalInterest)
		$percentIntPaid=100;
		*/
		//$principalDue=$principalDue+$principalArrears;//  check with loan tracking report and rectify using this computation
		//$interestDue=$interestDue+$interestArrears;// check with loan tracking report and rectify using this computation
		$totalDue=$principalDue+$interestDue;
		$totalToBePaid=$principalToBePaid+$interestToBePaid;
		$totalArrears=$principalArrears+$interestArrears;
		$totalPaid=$principalPaid+$interestPaid;
		   
		$newDate = date('Y-m-d',strtotime($row->date));
		list($y,$m,$d)=explode("-",$newDate);
		$disDate=$d.'/'.$m.'/'.$y;
		
		//$row_data=array('counter'=>$counter,'loanNo'=>$loanId,'name'=>$fname." ". $lname,'memberNo'=>$memNo,'disburseDate'=>$disDate,'amount'=>$amount,'princDue'=>$principalDue,'intDue'=>$interestDue,'totalDue'=>$totalDue,'princPaid'=>$principalPaid,'intPaid'=>$interestPaid,'totalPaid'=>$totalPaid,'princArrears'=>$principalArrears,'intArrears'=>$interestArrears,'totalArrears'=>$totalArrears,'outstandingPrincipal'=>$outstandingBal,'percentagePrincPaid'=>$percentPrincPaid);
		$mem_id=libinc::getItemById('loan_applic',$row->applic_id,'id','mem_id');
                        
                            echo "<tr>"
                           
                            ."<td>".$x."</td>"
                            ."<td>".$row->applic_id."</td>"
                            ."<td>".libinc::getItemById('member',$mem_id,'id','first_name')." ".libinc::getItemById('member',$mem_id,'id','last_name')."</td>"
                            ."<td>".libinc::getItemById('member',$mem_id,'id','mem_no')."</td>"
                            ."<td>".$disDate."</td>"
                            ."<td>".number_format($row->amount,2)."</td>"
                            ."<td>".number_format($principalToBePaid,2)."</td>"
                            ."<td>".number_format($interestToBePaid,2)."</td>"
                            ."<td>".number_format($totalToBePaid,2)."</td>"
                            ."<td>".number_format($principalPaid,2)."</td>"
                            ."<td>".number_format($interestPaid,2)."</td>"
                            ."<td>".number_format($totalPaid,2)."</td>"
                            ."<td>".number_format($principalArrears,2)."</td>"
                            ."<td>".number_format($interestArrears,2)."</td>"
                            ."<td>".number_format($totalArrears,2)."</td>"
                            ."<td>".number_format($outstandingBal,2)."</td>"
                            ."<td>".number_format($percentPrincPaid,1)."</td>"
                            
                            //."<td>".(($row->loan_period==0) ? '' : $row->loan_period)."</td>"                            
                            //."<td>".libinc::getItemById('payment',$disbursementId,'disbursement_id','sum(princ_amt)')."</td>"
                            
                            ."</tr>";
                            } ?> 
                    
                   <p><?php echo $links; ?></p>         
                        </div>

                    </div>
                </div>
            </div>
            </div>

