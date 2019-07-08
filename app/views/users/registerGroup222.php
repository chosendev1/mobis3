<!-- <script type="text/javascript">
        	$('#select_all').change(function() {
		    var checkboxes = $(this).closest('form').find(':checkbox');
		    alert("changed");
		    if($(this).is(':checked')) {
			checkboxes.attr('checked', 'checked');
		    } else {
			checkboxes.removeAttr('checked');
		    }
		});
        </script> -->
       <?php //include_once('resources/lang/lang.en.php');
       // $lang['groupName'] = "Group Name";
       //	echo count($groupData);
       //	$lang = strings();
       ?>
 
 <div class="row">
                                    <!-- FORM VALIDATION -->
                    <form action="user/addUserGroup" method="POST" data-parsley-namespace="data-parsley-" data-parsley-validate>
                    <div class="col-md-6 col-lg-4">
                        
                            <div class="panel panel-default panel-block">
                                <div class="list-group">
                                    <div class="list-group-item">
                                        <h4 class="section-title"><?=$lang["registerGroup"]?></h4>

                                        <div class="form-group">
                                            <label><?=$lang["groupName"]?> <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Group Name" name="groupName" data-parsley-required="true">
                                        </div>
                                        
                                    </div>
                                </div>
                                <footer class="panel-footer text-right">
                                  <button type="submit" class="btn btn-success"><?=$lang["submit"]?></button>
                                </footer>
                            </div>
                        <div class="panel panel-default panel-block">
                    	<div id="data-table" class="panel-heading datatable-heading">
                            <h4 class="section-title"><?=$lang["userGroups"]?></h4>
                    	</div>
	                    <table class="table table-bordered table-striped" id="tableSortable">
	                        <thead>
	                            <tr>
	                            	<th></th>
	                            	<th>#</th>
	                            	<th><?=$lang["groupName"]?></th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                        <?php
	                        	$i = 0;
	                        foreach($groupData->result() as $gD){
	                        
	                          ?>  <tr>
	                           <td>
	                               <div class="checkbox custom-checkbox nm">  
                                                    <input type="checkbox" id="customcheckbox-four<?=$i+1?>"  name="userGroup[]" value="1" data-toggle="selectrow" data-target="tr" data-contextual="success">  
                                                    
                                                    <label for="customcheckbox-four<?=$i+1?>"></label>   
                                   </div>
                                   </td>
	                          	<td><?=$i+1?></td>
	                                <td><?=$gD->usergroupName?></td>
	                              
	                            </tr>
	                          <?php ++$i; } ?> 
	                        </tbody>
	                    </table>
		            </div>
		             <footer class="panel-footer text-right">
                                  <button type="submit" class="btn btn-success"><?=$lang["save"]?></button>
                                </footer>
                    </div>
                   <!-- </div>
                    
                    <div class="row"> -->
                <div class="col-md-6 col-lg-8">
                           <!-- INPUT FIELDS -->
                     <div class="panel panel-primary">
                            <!-- panel heading/header -->
                            <div class="panel-heading">
                                <h3 class="panel-title"><span class="panel-icon mr5"><i class="ico-table22"></i></span><?=$lang["menus"]?></h3>
                                <!-- panel toolbar -->
                                <div class="panel-toolbar text-right">
                                    <!-- option -->
                                    <div class="option">
                                        <button class="btn up" data-toggle="panelcollapse"><i class="arrow"></i></button>
                                       
                                    </div>
                                    <!--/ option -->
                                </div>
                                <!--/ panel toolbar -->
                            </div>
                            <!--/ panel heading/header -->
                            <!-- panel toolbar wrapper -->
                            <div class="panel-toolbar-wrapper pl0 pt5 pb5">
                                <div class="panel-toolbar pl10">
                                    <div class="checkbox custom-checkbox pull-left">  
                                        <input type="checkbox" id="customcheckbox-one0" value="1" data-toggle="checkall" data-target="#table1">  
                                        <label for="customcheckbox-one0">&nbsp;&nbsp;Select All</label>  
                                    </div>
                                    
                                </div>
                                
                            </div>
                            <!--/ panel toolbar wrapper -->

                            <!-- panel body with collapse capabale -->
                            <div class="table-responsive panel-collapse pull out">

	                     <table class="table table-bordered table-hover" id="table1">
                                    <thead>
	                            <tr>
	                             <th class="text-center" colspan=3><i class="ico-long-arrow-down"></i>Permissions</th>
	                              
	                            	<th>#<?=$lang["id"]?></th>
	                                <th><?=$lang["menu"]?></th>
	                                <th><?=$lang["parent"]?></th>
	                                <th><?=$lang["link"]?></th>
	                                
	                            </tr>
	                        </thead>
	                        <tbody>
	                        <?php
	                        	$i = 0;
	                        	
	                        	//print_r($allMenus->result());
	                        foreach($allMenus->result() as $m){
	                        	$j=$i+1;
	                          ?>  <tr>
	                          <td>
	                           <div class="checkbox custom-checkbox nm">  
                                                    <input type="checkbox" id="customcheckbox-one<?=$j?>"  name="readP[]" value="1" data-toggle="selectrow" data-target="tr" data-contextual="success">  
                                                    
                                                    <label for="customcheckbox-one<?=$j?>">R</label>   
                                   </div>
                                   </td>
                                   <td>
                                   <div class="checkbox custom-checkbox nm">  
                                                    <input type="checkbox" id="customcheckbox-two<?=$j?>" name="writeP[]" value="1" data-toggle="selectrow" data-target="tr" data-contextual="success">  
                                                    <label for="customcheckbox-two<?=$j?>">W</label>   
                                   </div>
                                    </td>
                                     <td>
                                   <div class="checkbox custom-checkbox nm">  
                                                    <input type="checkbox" id="customcheckbox-three<?=$j?>" name="deleteP[]" value="1" data-toggle="selectrow" data-target="tr" data-contextual="success">  
                                                    <label for="customcheckbox-three<?=$j?>">D</label>   
                                   </div>
                                    </td>
	                          	<td><?=$m->menuId?></td>
	                                <td><?=$m->menuName?></td>
	                                <td><?=$m->parentId?></td>
	                                <td><?=$m->link?></td>
	                                
	                               
	                            </tr>
	                          <?php ++$i; } ?> 
	                        </tbody>
	                    </table>
	                    
		            </div>
                    </div>
                   </div>
                   </div> <!-- row colum -->
                    <?php 
	                    	//$tables = new tables("basic",$allMenus->result());
	                        echo tables::basic("Menus",$allMenus->result());
	                    ?>
                    </form>
                   </div> <!-- row -->
