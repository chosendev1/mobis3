    
    <div class="">
        <div class="col-sm-12"> 
     
             <div class="row-fluid">
               <form action="user/addrollcall" method="POST" >             

                <div class="widget">
                    <div class="head">
                        <div class="icon"><i class=""></i></div>
                        <h3>Roll Call a System user</h3>
                    <hr>
                   

                       <div class="row-form">
                            <div class="span2">Report Time<span class="text-danger">*</span></div>                    
                                <div class="span3"><input type="text" name="time" placeholder="Usersname" 
                                    value="<?php echo date('h:i:s',time());?>"/></div>                          
                        
                            <div class="span1">User<span class="text-danger">*</span></div>                    
                                <div class="span3"><select id="parent" name="user">
                                        <?php foreach ($users as $key) {?>
                                           
                          <option value="<?php echo $key['userId']?>"><?php echo ucwords($key['userName']);?></option>
                                          <?php }  ?>
                                     
                                    </select></div>        <div class="span1">
                            <button class="btn btn-primary" type="submit">Roll call</button>
                        </div>  
                                       
                        </div>
                           <? if(isset($message)){
                          echo "<div class='alert alert-info'>".$message."</div>";
                        }?>     
                             
			</div>    
                            </div>
                        </div>
                        
                    </div>
                </div>
                 </form>
           
            </div> <!--close here-->
           </div>   
               
        
    </div>  
