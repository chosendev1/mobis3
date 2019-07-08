            <form class="form-horizontal" action="<?php echo base_url()?>reports/export" method="post">
            	    <?php
                    foreach($variables as $key => $va){
                    if($key=="accountId")
                    $accountId=$va;
                    if($key=="branchId")
                    $branchId=$va;
                    if($key=="fromDate")
                    $fromDate=$va;
                    if($key=="toDate")
                    $toDate=$va;
                    if($key=="tdate")
                    $tdate=$va;
                    if($key=="fdate")
                    $fdate=$va;
                    if($key=="liabAcct")
                    $liabAcct=$va;
                    }
                    
                    ?>
                   
		    <input type="hidden" name="account" value="<?php echo $accountId ?>">
		    <input type="hidden" name="branch_id" value="<?php echo $branchId ?>">
		    <input type="hidden" name="from_date" value="<?php echo $fromDate?>">
		    <input type="hidden" name="to_date" value="<?php echo $toDate?>">
                                              
                    <button class="btn btn-success" type="submit">Export to Excel</button>
                    </form>
                    </div>
                          
                            <div class="panel panel-default" id="demo">
                            <div class="panel-heading">
                                 <h4 class="text-primary mt0 mb5">Transactions for <?php echo $liabAcct?> From <?php echo $fdate?> to <?php echo $tdate?></h4>
                            </div>
 	                     <table class="table-hover borderless" id="table-tools" width="100%">
			     <thead><th>Date</th><th>Account</th><th>Particulars</th><th>Paid By</th><th>Member No.</th><th>Dr.</th><th>Cr.</th><thead><tbody>
               
                        <?php
                        foreach($liability as $data => $row){?>
                         <tr>
 			<?php foreach($row as $key=> $val){?>			
                   
                        <?php  if($key=='date'){?>
                        <td><?php  echo $val; ?></td>
                        <?php }
                        if($key=='account'){?>
                        <td><?php  echo $val; ?></td>
                        <?php }
                        if($key=='transaction'){?>  
                        <td><?php echo $val; ?></td>
                        <?php }
                        if($key=='mode'){?>
                        <td><?php echo $val; ?></td>
                        <?php }
                        if($key=='memberNo'){?>
                        <td><?php echo $val; ?></td>
                        <?php }
                        if($key=='debit'){?>
                        <td><?php echo $val; ?></td>
                        <?php }
                        if($key=='credit'){?>
                        <td><?php echo $val; ?></td>
                        <?php }
                        }?>
                        </tr>
                   
                   <?php } ?>
                  
                    </table>
                    
                        </div>

                    </div>
                </div>
            </div>
            </div>

