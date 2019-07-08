            <form class="form-horizontal" action="<?php echo base_url()?>reports/exportCustomers" method="post">
            
		    <input type="hidden" name="records" value="<?php echo $records ?>">
		    <input type="hidden" name="page" value="<?php echo $page ?>">
		    <input type="hidden" name="branchId" value="<?php echo $branchId ?>">
		    
                    <button class="btn btn-success" type="submit">Export to Excel</button>
                    </form>
                    </div>
                          
                            <div>
                            <div>
                                <!-- h4 class="text-primary mt0 mb5">Transactions for <?php echo $liabAcct?> From <?php echo $fdate?> to <?php echo $tdate?></h4> -->
                            </div>
 	                    <table width="100%">
 	                     	                    
			     <!-- <thead><th>branch_id</th><th>title</th><th>first_name</th><th>middle_name</th><th>last_name</th><th>dob</th><th>gender</th><th>phone_number</th><th>email</th><th>street_address</th><th>city</th><th>district</th><th>country</th><th>postcode</th><th>kin_full_name</th><th>kin_phone_number</th><th>total_shares</th><th>registration_date</th><th>reference_number</th><th>legacy_member_number</th><thead><tbody> -->
			     <thead><th>branch_id</th><th>customer_id</th><th>title</th><th>first_name</th><th>middle_name</th><th>last_name</th><th>dob</th><th>gender</th><th>phone_number</th><th>email</th><th>street_address</th><th>city</th><th>district</th><th>country</th><th>postcode</th><th>kin_full_name</th><th>kin_phone_number</th><th>total_shares</th><th>registration_date</th><th>reference_number</th><th>memaccount_id</th><th>legacy_member_number</th><th>savings_balance</th><th>account_open_date</th><thead><tbody>
 	                                   
                        <?php
                       
                         foreach($customers as $row){
                            
                           /* list($firstName, $middleName, $lastName)=explode(' ', $row->first_name, 3);
                            if(!empty($middleName) && empty($lastName)){
                            $lastName=$middleName;
                            $middleName="";
                            }
                           */
                            echo "<tr>"
                            ."<td>".libinc::getItemById('branch',$row->branch_id,'branch_no','branch_name')."</td>"
                            ."<td>".$row->id."</td>"
                            ."<td>".(empty(libinc::getItemById('ranks',$row->r_id,'position','rank')) ? '' : libinc::getItemById('ranks',$row->r_id,'position','rank'))."</td>"
                            ."<td>".$row->first_name."</td>"
                            ."<td>".$row->middle_name."</td>"
                            ."<td>".$row->last_name."</td>"
                            ."<td>".$row->dob."</td>"
                            ."<td>".(($row->sex=='M') ? 'Male' : (($row->sex=='F')  ? 'Female' : ''))."</td>"
                            ."<td>".$row->telno."</td>"
                            ."<td>".$row->email."</td>"
                            ."<td>".$row->address."</td>"
                            ."<td>".$row->city."</td>"
                            ."<td>".$row->district."</td>"
                            ."<td>".$row->country."</td>"
                            ."<td>".$row->postcode."</td>"
                            ."<td>".$row->kin."</td>"
                            ."<td>".$row->kin_telno."</td>"
                            ."<td>".number_format(libinc::numShares($row->id),1)."</td>"    
                            ."<td>".$row->dor."</td>"
                            ."<td>".$row->ipps."</td>"
                            ."<td>".libinc::getItemById('mem_accounts',$row->id,'mem_id','id')."</td>"
                            ."<td>".$row->mem_no."</td>"
                            ."<td>".number_format(libinc::get_savings_bal(libinc::getItemById('mem_accounts',$row->id,'mem_id','id')),2)."</td>"
                            ."<td>".libinc::getItemById('mem_accounts',$row->id,'mem_id','open_date')."</td>
                            </tr>";
                            } ?>  
                    
                   <p><?php echo $links; ?></p>         
                        </div>

                    </div>
                </div>
            </div>
            </div>

