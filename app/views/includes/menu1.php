 <!-- START Template Sidebar (Left) -->
 <?php
 	$curlink = $this->uri->segment(1);
                    	//$sublink = "";
                    	//if(isset($this->uri->segment(2)))
        $sublink = $this->uri->segment(2);
        $mainm = libinc::getUserMainMenu(CAP_Session::get('userId'));
        $subm =  libinc::getSubMenus();
 ?>
        <aside class="sidebar sidebar-left sidebar-menu">
            <!-- START Sidebar Content -->
            <section class="content slimscroll">
                <h5 class="heading">Main Menu</h5>
                <!-- START Template Navigation/Menu -->
                <ul class="topmenu topmenu-responsive" data-toggle="menu">
                    <?php
                    $class = $curlink == "dashboard" ? "class='active open'" : NULL;
                    $class2 = $curlink == "dashboard" ? "class='submenu collapse in'" : "class='submenu collapse'";
                    ?>
                    <li <?=$class?>>
                        <a href="javascript:void(0);" data-target="#dashboard" data-toggle="submenu" data-parent=".topmenu">
                            <span class="figure"><i class="ico-home2"></i></span>
                            <span class="text">Dashboard</span>
                            <span class="arrow"></span>
                        </a>
                        <!-- START 2nd Level Menu -->
                        <ul id="dashboard" <?=$class2?>>
                            <li class="submenu-header ellipsis">Dashboard </li>
                            <li>
                                <a href="dashboard">
                                    <span class="text">Dashboard</span>
                                    <span class="number"><span class="label label-danger"></span></span>
                                </a>
                            </li>
                            
                        </ul>
                        <!--/ END 2nd Level Menu -->
                    </li>
                    
                    <?php 
                    	$isCompanyUser  = libinc::isCompanyUser();
                    	foreach($mainm->result() as $m){
                    		if($isCompanyUser && $m->menuName == "Administration"){
                    			continue;
                    		}
                    		else{
                    		 $class = $m->link == $curlink ? "class='active open'" : NULL;
                    		 $class2 = $curlink == $m->link ? "class='submenu collapse in'" : "class='submenu collapse'";
                    		?>
                    		 <li <?=$class?>>
                    		
		                <a href="javascript:void(0);" data-toggle="submenu" data-target="#<?=$m->menuName?>" data-parent=".topmenu">
		                                			                                 
		                     <?php
		             // foreach($mainm->result() as $m){
                    		if($m->menuName == "Configuration")
		                   echo "<span class='figure'><img src='resources/themes/admire/image/icons/configuration 2.png' width=16 height=16 ></span>";
		                if($m->menuName == "Administration")
		                   echo "<span class='figure'><img src='resources/themes/admire/image/icons/Administration 2.png'  width=16 height=16 ></span>";
		                if($m->menuName == "Staff")
		                   echo "<span class='figure'><img src='resources/themes/admire/image/icons/staff.png'  width=16 height=16 ></span>";
		                if($m->menuName == "Users")
		                   echo "<span class='figure'><img src='resources/themes/admire/image/icons/USER 1.png'  width=16 height=16 ></span>";
		                if($m->menuName == "Loans")
		                   echo "<span class='figure'><img src='resources/themes/admire/image/icons/loan 1.png'  width=16 height=16 ></span>";
		                if($m->menuName == "Customers")
		                   echo "<span class='figure'><img src='resources/themes/admire/image/icons/customer 1.png'  width=16 height=16 ></span>";
		                if($m->menuName == "Shares")
		                   echo "<span class='figure'><img src='resources/themes/admire/image/icons/shares 2.png'  width=16 height=16 ></span>";
		                if($m->menuName == "Savings")
		                   echo "<span class='figure'><img src='resources/themes/admire/image/icons/savings 3.png'  width=16 height=16 ></span>";
		                if($m->menuName == "Charts")
		                   echo "<span class='figure'><img src='resources/themes/admire/image/icons/charts.png'  width=16 height=16 ></span>";
		                if($m->menuName == "Transactions")
		                   echo "<span class='figure'><img src='resources/themes/admire/image/icons/Transaction 2.png'  width=16 height=16 ></span>";
		                if($m->menuName == "Reports")
		                   echo "<span class='figure'><img src='resources/themes/admire/image/icons/Reports 2.png'  width=16 height=16 ></span>";
		                if($m->menuName == "Holidays")
		                   echo "<span class='figure'><img src='resources/themes/admire/image/icons/holiday 2.png'  width=16 height=16 ></span>";
		                 if($m->menuName == "Media")
		                   echo "<span class='figure'><img src='resources/themes/admire/image/icons/Media 1.png'  width=16 height=16 ></span>";		        		              	 
		                    ?>   
		                    
		                    
		                    <span class="text"><?=$m->menuName?></span>
		                 
		                    <span class="arrow"></span>
		                </a>
		                <!-- START 2nd Level Menu -->
		                <ul id="<?=$m->menuName?>" <?=$class2?>>
		                    <li class="submenu-header ellipsis"><?=$m->menuName?></li>
                            	<?php
                            		foreach($subm->result() as $aM){
                            			if($aM->parentId == $m->menuId){
                            				if(libinc::hasChildren($aM->menuId)){
                            				?>
                            					 <li >
						        <a href="javascript:void(0);" data-toggle="submenu" data-target="#<?=$aM->link?>" data-parent="#<?=$aM->link?>">
						            <span class="text"><?=$aM->menuName?></span>
						            <span class="arrow"></span>
						        </a>
						        <!-- START 2nd Level Menu -->
						        <ul id="<?=$aM->link?>" class="submenu collapse ">
						        	<?php
						        		foreach(libinc::getSubMenus($aM->menuId)->result() as $aMM){
						        	?>
						            			<li >
						                		<a href="<?=$aM->link.'/'.$aMM->link?>"><span class="text"><?=$aMM->menuName?></span></a>
						            			</li>
						            
						            <?php
						            	}
						            ?>
						            
						        </ul>
						        <!--/ END 2nd Level Menu -->
						    </li>
						    				<?
                            				
                            				}
                            				else{
		                    				$class = $aM->link == $sublink ? "class='active'" : NULL;
		                    				?>
		                    				 <li <?=$class?>>
		                        			<a href="<?=$m->link.'/'.$aM->link?>">
		                            			<span class="text"><?=$aM->menuName?></span>
		                        			</a>
		                    				</li>
		                    				<?php
                            				}
                            			}
                            		}
                            		
                            	?>
                        </ul>
                        <!--/ END 2nd Level Menu -->
                    </li>
                    <?php
                    	}
                    	}
                    ?>
                    
                  <!--  <li >
                        <a href="<?=base_url();?>">
                            <span class="figure"><i class="ico-trophy"></i></span>
                            <span class="text">Home</span>
                        </a>
                    </li> -->
                   
                </ul>
                <!--/ END Template Navigation/Menu -->

  <!-- START Sidebar summary -->
                <!-- Summary -->
            <!--    <h5 class="heading">Summary</h5>
                <div class="wrapper">
                    <div class="table-layout">
                        <div class="col-xs-5 valign-middle">
                            <span class="sidebar-sparklines" sparkType="bar" sparkBarColor="#00B1E1">1,5,3,2,4,5,3,3,2,4,5,3</span>
                        </div>
                        <div class="col-xs-7 valign-middle">
                            <h5 class="semibold nm">Server uptime</h5>
                            <small class="semibold">1876 days</small>
                        </div>
                    </div>

                    <div class="table-layout">
                        <div class="col-xs-5 valign-middle">
                            <span class="sidebar-sparklines" sparkType="bar" sparkBarColor="#91C854">2,5,3,6,4,2,4,7,8,9,7,6</span>
                        </div>
                        <div class="col-xs-7 valign-middle">
                            <h5 class="semibold nm">Disk usage</h5>
                            <small class="semibold">83.1%</small>
                        </div>
                    </div>

                    <div class="table-layout">
                        <div class="col-xs-5 valign-middle">
                            <span class="sidebar-sparklines" sparkType="bar" sparkBarColor="#ED5466">5,1,3,7,4,3,7,8,6,5,3,2</span>
                        </div>
                        <div class="col-xs-7 valign-middle">
                            <h5 class="semibold nm">Daily visitors</h5>
                            <small class="semibold">56.5%</small>
                        </div>
                    </div>
                </div>
                <!--/ Summary -->
                <!--/ END Sidebar summary -->
            </section>
            <!--/ END Sidebar Container -->
        </aside>
        <!--/ END Template Sidebar (Left) -->

