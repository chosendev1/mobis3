  <?php
$app = get_instance();
        $sublink = $app->uri->segment(1);
        $mainm = libinc::getUserMainMenu(CAP_Session::get('userId'));
        $subm =  libinc::getUserSubMenus();
         //$action = $heading=="Reports" : "Reports" ? "Action"

                     if (isset($parentId)){?>                    
                                        
                       <div class="widget"> 
                   <div class="head dark">
                        <div class="icon"><i class="icos-star3"></i></div>
                        <h2><?=$heading?></h2>
                                          
                    </div> 
                                        
                     <div class="toolbar">
                      
                           <!--     <div class="btn-group">
                            <button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
                              
                                <?=$subheading?>
                                
                                </button>                            
                                <ul class="dropdown-menu"> -->
                                <?
                   
                        foreach($mainm->result() as $m){ 
                           
                                  foreach($subm->result() as $aM){
                                  if($aM->parentId == $m->menuId){ 
                            			if($aM->menuId == $parentId){
                            				if(libinc::hasChildren($parentId)){
                            				
                                              foreach(libinc::getSubMenus($parentId)->result() as $aMM){
						        	?>
						        	 <a class="btn"  href="<?=$m->link.'/'.$aMM->link.'/'.$parentId?>"><span class="text"><?=$aMM->menuName?></span></a>
						       
						        
				<?}}}}}} ?>                       
                             <!--       </ul>
                            </div>     -->                       
                      
                        </div></div>   				              				                         
                    <?} ?>
                 
                                 
                  
                        
                      
