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
	                            	<th>Status</th>
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
	                          <td><?=getItemById("accounts",getItemById("bank_account",$user->cashAccountId,"id","account_id"),"id","account_no")?>-<?=getItemById("accounts",getItemById("bank_account",$user->cashAccountId,"id","account_id"),"id","name")?></td>
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
               <form action="user/editUser" method="POST" >             

                <div class="widget">
                    <div class="head">
                        <div class="icon"><i class=""></i></div>
                        <h2>Edit User</h2>
                    </div>  
                    <div id="divbg2">                      
                    <div class="block-fluid">
  			<?	
	              foreach($userData->result() as $userdata){
	              ?>  
                       <div class="row-form">
                            <div class="span3">Username<span class="text-danger">*</span></div>                    
                                <div class="span9"><input type="text" name="username" value="<?=$userdata->userName?>" /></div>                          
                        </div> 
                            <input type="hidden" name="userId" value="<?=$userdata->userId?>" />                 
                        <div class="row-form">
                            <div class="span3">User Group<span class="text-danger">*</span></div>                    
                                <div class="span9"><select id="parent" name="groupId">
                                        <optgroup label="Choose User Group">
                                        <option value="<?=$userdata->userGroupId?>"><?=getItemById("userGroups",$userdata->userGroupId,"usergroupId","usergroupName")?></option>
                                            <?=$userGroups?>
                                        </optgroup>
                                    </select></div>                          
                        </div> 
                         
                         <div class="row-form">
                         <div class="span3">Cash Account(Optional)</div>                    
                               <div class="span9"><select id="cashAccount" name="cashAccount">
                                        <optgroup label="Choose Cash Account">
                                         <option value="<?=$userdata->cashAccountId?>"><?=getItemById("accounts",getItemById("bank_account",$userdata->cashAccountId,"id","account_id"),"id","account_no")?>-<?=getItemById("accounts",getItemById("bank_account",$userdata->cashAccountId,"id","account_id"),"id","name")?></option>
                                            <?=$cashAccounts?>
                                        </optgroup>
                                    </select></div>  
                        </div>  

			 <div class="row-form">
                            <div class="span3">Status<span class="text-danger">*</span></div>                    
                                <div class="span9"><select id="parent" name="status">
                                        <optgroup label="Choose status">
                                       <option value="<?=$userdata->status?>"><?=$userdata->status?></option>
                                            <option value="Active"><?=$lang["active"]?></option>
                                             <option value="inactive"><?=$lang["inactive"]?></option>
                                              <option value="suspended"><?=$lang["suspended"]?></option>
                                               <option value="pending"><?=$lang["pending"]?></option>
                                        </optgroup>
                                    </select></div>                          
                        </div>
                             <?}?>  
                             <div class="toolbar bottom TAR">
                            <button class="btn btn-primary" type="submit">Save</button>
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
