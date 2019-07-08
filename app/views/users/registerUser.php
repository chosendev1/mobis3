<style type="text/css">
.datepicker{
  width: 100% !important;  
}
    </style>    
    <div class="">
              
         <div class="row-fluid">
            
            <div class="span8">                

                <div class="widget">
                    <div class="head">
                        <div class="icon"><i class=""></i></div>
                        <h2>Users Available</h2>
                    </div> 
                                         
                   <div id="divbg2">                      
                    <div class="block-fluid">
			 <table class="table table-bordered table-striped" id="tableSortable">
	                        <thead>
	                            <tr>
	                            	<th>#</th>
	                            	<th>Username</th>
	                            	<th>User Group</th>
	                            	<th>Cash Account</th>
	                            	<th>Statusdd</th>
	                            	<th></th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                        <?$i=1;	
	                        foreach($allUsers->result() as $user){
	                        ?> 
	                           <tr>
	                           <td><?=$i?></td>
	                          <td><?=$user->Username?></td>
	                          <td><?=$user->GroupName?></td>
	                          <td><?=libinc::getItemById("accounts",libinc::getItemById("bank_account",$user->cashAccountId,"id","account_id"),"id","account_no")?>-<?=libinc::getItemById("accounts",libinc::getItemById("bank_account",$user->cashAccountId,"id","account_id"),"id","name")?></td>
	                          <td><?=$user->status?></td>
	                          <td> <div class="btn-group">
                                       <a href="configuration/editUser/<?=$parentId.'/'.$user->userId?>" class="btn" ><span class="icon-pencil"></span></a>
                                        <a href="configuration/deleteUser" class="btn" ><span class="icon-remove"></span></a>
                                    </div>
	                         
	                       </td>	                              
	                            </tr>
	                          <?$i++;} ?> 
	                        </tbody>
	                    </table>
                    </div>
                </div>
                 </div>
                </div>
            
            
               
              <div class="span4"> 
     
             <div class="row-fluid">
               <form action="user/registerUser" method="POST" >             

                <div class="widget">
                    <div class="head">
                        <div class="icon"><i class=""></i></div>
                        <h2>Create New User</h2>
                    </div>  
                    <div id="divbg2">                      
                    <div class="block-fluid">

                       <div class="row-form">
                            <div class="span3">Username<span class="text-danger">*</span></div>                    
                                <div class="span9"><input type="text" name="username" placeholder="Usersname"/></div>                          
                        </div> 
                     
                         <div class="row-form">
                            <div class="span3">Password<span class="text-danger">*</span></div>                    
                                <div class="span9"><input type="password" id="password" name="password" placeholder="Password"></div>                          
                        </div>  
                        
                         <div class="row-form">
                            <div class="span3">Confirm Password<span class="text-danger">*</span></div>                    
                                <div class="span9"><input type="password" id="password" name="confirmPassword" placeholder="Password"></div>                          
                        </div> 
                        
                        <div class="row-form">
                            <div class="span3">User Group<span class="text-danger">*</span></div>                    
                                <div class="span9"><select id="parent" name="groupId">
                                        <optgroup label="Choose User Group">
                                            <?=$userGroups?>
                                        </optgroup>
                                    </select></div>                          
                        </div> 
                         
                         <div class="row-form">
                         <div class="span3">Cash Account(Optional)</div>                    
                               <div class="span9"><select id="cashAccount" name="cashAccount">
                                        <optgroup label="Choose Cash Account">
                                            <?=$cashAccounts?>
                                        </optgroup>
                                    </select></div>  
                        </div>  
                        
                         <div class="row-form">
                            <div class="span3">Start Date<span class="text-danger">*</span></div>                    
                                <div class="span9"><input type="text" class="form-control" id="startDate" name="startDate" /> </div>                          
                        </div> 

			 <div class="row-form">
                            <div class="span3">Status<span class="text-danger">*</span></div>                    
                                <div class="span9"><select id="parent" name="status">
                                        <optgroup label="Choose status">
                                            <option value="Active"><?=$lang["active"]?></option>
                                             <option value="inactive"><?=$lang["inactive"]?></option>
                                              <option value="suspended"><?=$lang["suspended"]?></option>
                                               <option value="pending"><?=$lang["pending"]?></option>
                                        </optgroup>
                                    </select></div>                          
                        </div>
                               
                             <div class="toolbar bottom TAR">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
			</div>    
                            </div>
                        </div>
                        
                    </div>
                </div>
                 </form>
           
            </div> <!--close here-->
           </div>   
               
        
    </div>  
