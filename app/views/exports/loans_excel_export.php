            <form class="form-horizontal" action="<?php echo base_url()?>reports/exportLoans" method="post">
            	
		    <input type="hidden" name="records" value="<?php echo $records ?>">
		    <input type="hidden" name="page" value="<?php echo $page ?>">
		    
                    <button class="btn btn-success" type="submit">Export to Excel</button>
                    </form>
                    </div>                        
                            <div>
                            <div>
                                <!-- h4 class="text-primary mt0 mb5">Transactions for <?php echo $liabAcct?> From <?php echo $fdate?> to <?php echo $tdate?></h4> -->
                            </div>
 	                    <table width="100%">                  
			    <thead><th>loan_officer
</th><th>legacy_member_number</th><th>customer_name</th><th>loan_product</th><th>requested_amount</th><th>purpose</th><th>application_date</th><th>repayment_period_months</th><th>primary_income_source</th><th>approved_amount</th><th>disbursed_at</th><th>voucher_number</th><th>paid_principal</th><th>paid_interest</th><th>paid_penalties</th><th>last_payment_date</th><thead><tbody>

 	                       	                                   
                        <?php
                        $date=date('Y-m-d');
                        foreach($loans as $row){
                            $approvalId=libinc::getItemById('approval',$row->id,'applic_id','id');
                            $disbursementId=libinc::getItemById('disbursed',$approvalId,'approval_id','id');
                            
                            if(empty($disbursementId))
                            continue;
                            
                            $InterestBal=libinc::interestBalance($row->id,$date);
                            $principalBal=libinc::loanBalance($row->id,$date);
                            
                           // $totalPaid=$principalPaid+$InterestPaid;
                            $totalBal=$principalBal+$InterestBal;
                            
                           $principalArrears=libinc::principalArrears($row->id,$date); 
                           $interestArrears=libinc::interestArrears($row->id,$date);
                           $totalArrears=$principalArrears+$interestArrears;
                            
                            if(number_format($totalBal) > 0 && number_format($totalArrears) > 0){
                            
                            echo "<tr>"
                            ."<td>".libinc::getItemById("employees",libinc::getItemById('approval',$approvalId,'id','officer_id'),"employeeId","firstName")." ".libinc::getItemById("employees",libinc::getItemById('approval',$approvalId,'id','officer_id'),"employeeId","lastName")."</td>"
                            ."<td>".libinc::getItemById('member',$row->mem_id,'id','mem_no')."</td>"
                            
                            ."<td>".libinc::getItemById('member',$row->mem_id,'id','first_name')." ".libinc::getItemById('member',$row->mem_id,'id','last_name')."</td>"
                            ."<td>".libinc::getItemById('accounts',libinc::getItemById('loan_product',$row->product_id,'id','account_id'),'id','name')."</td>"
                            ."<td>".$row->amount."</td>"
                            ."<td>".((empty(libinc::getItemById('approval',$approvalId,'id','purpose'))) ? '' : libinc::getItemById('approval',$approvalId,'id','purpose'))."</td>"
                            ."<td>".$row->date."</td>"
                            ."<td>".((empty(libinc::getItemById('approval',$approvalId,'id','loan_period'))) ? '' : libinc::getItemById('approval',$approvalId,'id','loan_period'))."</td>"
                            ."<td>".((empty(libinc::getItemById('approval',$approvalId,'id','income_source'))) ? '' : libinc::getItemById('approval',$approvalId,'id','income_source'))."</td>"
                            ."<td>".libinc::getItemById('approval',$approvalId,'id','amount')."</td>"
                            ."<td>".libinc::getItemById('disbursed',$disbursementId,'id','date')."</td>"
                            ."<td></td>"
                            ."<td>".libinc::getItemById('payment',$disbursementId,'disbursement_id','sum(princ_amt)')."</td>"
                            ."<td>".libinc::getItemById('payment',$disbursementId,'disbursement_id','sum(int_amt)')."</td>"
                            ."<td>".libinc::getItemById('payment',$disbursementId,'disbursement_id','penalty')."</td>"
                            ."<td>".libinc::getItemById('payment',$disbursementId,'disbursement_id','max(date)')."</td>
                            </tr>";
                            } }?> 
                    
                   <p><?php echo $links; ?></p>         
                        </div>

                    </div>
                </div>
            </div>
            </div>

