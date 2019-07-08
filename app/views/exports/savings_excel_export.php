            <form class="form-horizontal" action="<?php echo base_url()?>reports/exportSavings" method="post">
            	
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
			    <thead><th>legacy_member_number</th><th>customer_type</th><th>customer_name</th><th>ipps_no</th><th>savings_product</th><th>balance</th><th>opened_at</th><thead><tbody>
 	                                   
                        <?php
                       
                        foreach($savings as $row){
                            echo "<tr>"
                            ."<td>".libinc::getItemById('member',$row->mem_id,'id','mem_no')."</td>"
                            ."<td>".(($row->group_id==1) ? 'Institution' : 'Individual')."</td>"
                            ."<td>".libinc::getItemById('member',$row->mem_id,'id','first_name')." ".libinc::getItemById('member',$row->mem_id,'id','last_name')."</td>"
                            ."<td>".libinc::getItemById('member',$row->mem_id,'id','ipps')."</td>"
                            ."<td>".libinc::getItemById('accounts',libinc::getItemById('savings_product',$row->saveproduct_id,'id','account_id'),'id','name')."</td>"
                            ."<td>".number_format(libinc::get_savings_bal($row->id),2)."</td>"
                           
                            ."<td>".$row->open_date."</td>
                            </tr>";
                            } ?> 
                    
                   <p><?php echo $links; ?></p>         
                        </div>

                    </div>
                </div>
            </div>
            </div>

