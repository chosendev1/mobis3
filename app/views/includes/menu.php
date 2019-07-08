
 <?php
    $curlink = $this->uri->segment(1);
                        //$sublink = "";
                        //if(isset($this->uri->segment(2)))
        $sublink = $this->uri->segment(2);
        $mainm = libinc::getUserMainMenu(CAP_Session::get('userId'));
        $subm =  libinc::getSubMenus();
        
 ?>

<div class="navigation">

        <ul class="main">
            <li><a href="clientDashboard/" class="active"><span class="icom-home"></span><span class="text">Dashboard</span></a></li>
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
                            
            <li><a href="#<?=$m->menuName?>"><?php
                     // foreach($mainm->result() as $m){
                            if($m->menuName == "Configuration")
                           echo "<span class='figure'><span class='icom-cog'></span><span class='text'>";
                        if($m->menuName == "Administration")
                           echo "<span class='figure'><span class='icom-accessibility'></span><span class='text'>";
                        if($m->menuName == "Staff")
                           echo "<span class='figure'><span class='icom-users'></span><span class='text'>";
                        if($m->menuName == "Users")
                           echo "<span class='figure'><span class='icom-user'></span><span class='text'>";
                        if($m->menuName == "Loans")
                           echo "<span class='figure'><span class='icom-database'></span><span class='text'>";
                        if($m->menuName == "Customers")
                           echo "<span class='figure'><span class='icom-user3'></span><span class='text'>";
                        if($m->menuName == "Shares")
                           echo "<span class='figure'><span class='icom-cube'></span><span class='text'>";
                        if($m->menuName == "Savings")
                           echo "<span class='figure'><span class='icom-paypal'></span><span class='text'>";
                        if($m->menuName == "Charts")
                           echo "<span class='figure'><span class='icom-stats-up'></span><span class='text'>";
                        if($m->menuName == "Transactions")
                           echo "<span class='figure'><span class='icom-share'></span><span class='text'>";
                        if($m->menuName == "Reports")
                           echo "<span class='figure'><span class='icom-bars'></span><span class='text'>";
                        if($m->menuName == "Holidays")
                           echo "<span class='figure'><span class='icom-delicious'></span><span class='text'>";
                         if($m->menuName == "Media")
                           echo "<span class='figure'><span class='icom-film'></span><span class='text'>";                                       
                            ?>
                            <span class="text"><?=$m->menuName?></span>   
                            </a></li>
                         <?php }}?>
        </ul>

        <div class="control"></div>        

        <div class="submain">
        <?php
            foreach($mainm->result() as $m){
                            if($isCompanyUser && $m->menuName == "Administration"){
                                continue;
                            }
                            else{?>
            <div id="<?=$m->menuName?>">
                <div class="menu">
                <?php
                                    foreach($subm->result() as $aM){
                                        if($aM->parentId == $m->menuId){
                                            if(libinc::hasChildren($aM->menuId)){
                                            ?>
                    <ul class="fmenu">
                    <li>
                        <a href="#<?=$aM->link?>"><span class="icon-user"></span> <?=$aM->menuName?></a>
                        <ul>
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
                    </li>                
                </ul>
                    <?
                                            
                                            }
                                            else{
                                            
                                            ?>
                                              <a href="<?=$m->link.'/'.$aM->link?>"><span class="icon-user"></span> <?=$aM->menuName?></a>
                                            <?php
                                            }
                                        }
                                    }
                                    
                                ?>
                                                      
                </div>                
                <div class="dr"><span></span></div>
            </div>
            <?php }}?>
            </div>

                       

        </div>

    </div>