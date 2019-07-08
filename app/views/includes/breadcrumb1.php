<?php
$app = get_instance();
        $sublink = $app->uri->segment(1);
        $mainm = libinc::getUserMainMenu(CAP_Session::get('userId'));
        $subm =  libinc::getUserSubMenus();
 ?>

<div class="block-fluid"> 
                    
                     <!-- Toolbar -->
                     <?
                       if($heading=="Reports" || $heading=="Transactions"){ 
                        ?>
                           
                                <!--<li><a href="javascript:void(0);"><?=isset($subheading)? $subheading:"Dashboard"?></a></li> -->
                   <?php   
                   foreach($mainm->result() as $m){ 
                           
                                  foreach($subm->result() as $aM){
                                  if($aM->parentId == $m->menuId){ 
                            			if($aM->menuId == $parentId){
                            				if(libinc::hasChildren($parentId)){
                            				?>
                            				 <ol class="breadcrumb breadcrumb-transparent nm">
                            				 <?
                                              foreach(libinc::getSubMenus($parentId)->result() as $aMM){
						        	?>
						        <li>
						      <a href="<?=$m->link.'/'.$aMM->link.'/'.$parentId?>"><span class="text"><?=$aMM->menuName?></span></a>
						        </li> 
                         <?php
                     }
		            ?>                                
                            </ol>
                            <?}
                     
                 }
             }
        }}
       
                            }
                            ?>
                      
                    </div>
                </div> 
    
                <!-- Page Header -->
                <div class="container">

                
           
            
            
            
