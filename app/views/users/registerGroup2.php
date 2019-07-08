
                 
                    
                  <div class="">
              
         <div class="row-fluid">
            
            <div class="span4">                

                <div class="widget">
                    <div class="head">
                        <div class="icon"><i class=""></i></div>
                        <h2>Groups Available</h2>
                    </div>                        
                  <div id="divbg">
			 
	                 <ol><?
	                        foreach($groupData->result() as $gD){
	                        
	                          ?>  
	                                <li><?=$gD->usergroupName?></li> 
	                               
	                          <?php } ?> 
	                    </ol></div>
                  
                    
                </div>
                
            </div>  
            
               
              <div class="span6"> 
     
             <div class="row-fluid">
               <form action="user/addUserGroup" method="POST" >             

                <div class="widget">
                    <div class="head">
                        <div class="icon"><i class=""></i></div>
                        <h2>Create New Group</h2>
                    </div>
                     <div id="divbg2">                        
                    <div class="block-fluid">

                       <div class="row-form">
                            <div class="span2">Group Name:</div>                    
                                <div class="span10"><input type="text" name="groupName" placeholder="Group Name"/></div>                          
                        </div> 
                     
                         <div class="row-form">
                            <div class="span2">Permissions:</div>
                            <div class="span2">
                                <input type="checkbox"  name="update" value="1"/>Edit                           
                                
                            </div>
                            <div class="span2">
                               
                                <input type="checkbox"  name="delete" value="1"/>Delete 
                                
                            </div>
                        </div>
                        
                        <div class="row-form">
                            <div class="span4">Access Levels:</div>                    
                                                         
                        </div> 
                        
                         
                          <? foreach(libinc::mainMenus()->result() as $m){
                          if($m->menuName == "Administration")
                                continue;
                                ?>
                          <div class="row-form">
                            
                            <span class="top title"><input type="checkbox" name="menus[]" value="<?=$m->menuId?>"/><?=$m->menuName?></span>
                 <div class="widget">
                    <div class="profile clearfix">
                  
                    <div class="info"><?
                  
                     foreach(libinc::getSubMenus($parentId)->result() as $aM){
                   
                                  if($aM->parentId == $m->menuId){?>
                    <div><input type="checkbox" name="menus[]" value="<?=$aM->menuId?>"/><?=$aM->menuName?></div> 
                     <?}}?>
                    </div>
                </div>
            </div>
                                                     
                                                       
                            </div>
                             <?}?>
                        <div class="toolbar bottom TAR">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
			</div>
                    </div>
                </div>
                 </form>
            
            </div> <!--close here-->
