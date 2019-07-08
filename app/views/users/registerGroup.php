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
	                            	<th>#</th>
	                            	<th><?=$lang["groupName"]?></th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                        <?php
	                        	$i = 0;
	                        foreach($groupData->result() as $gD){
	                        
	                          ?>  <tr>
	                          	<td><?=$i+1?></td>
	                                <td><?=$gD->usergroupName?></td>
	                               
	                            </tr>
	                          <?php ++$i; } ?> 
	                        </tbody>
	                    </table>
		            </div>
                    </div>
                   <!-- </div>
                    
                    <div class="row"> -->
                <div class="col-md-6 col-lg-8">
                           <!-- INPUT FIELDS -->
                     <div class="panel panel-default panel-block">
                    	<div id="data-table" class="panel-heading datatable-heading">
                            <h4 class="section-title"><?=$lang["menus"]?></h4>
                    	</div>
	                    <table class="table table-bordered table-striped" id="table1">
	                        <thead>
	                            <tr>
	                            	<th>#<?=$lang["id"]?></th>
	                                <th><?=$lang["menu"]?></th>
	                                <th><?=$lang["parent"]?></th>
	                                <th><?=$lang["link"]?></th>
	                                <th><?=$lang["permissions"]?><br />R&nbsp&nbsp&nbsp&nbspW &nbsp&nbsp D<br /><input type="checkbox" class="readAll" id="customcheckbox-one0" data-target="#table1" /> <input type="checkbox" class="writeAll" /> <input type="checkbox" class="deleteAll" /></th>
	                                
	                            </tr>
	                        </thead>
	                        <tbody>
	                        <?php
	                        	$i = 0;
	                        	
	                        	//print_r($allMenus->result());
	                        foreach($allMenus->result() as $m){
	                        	$j=$i+1;
	                          ?>  <tr>
	                          	<td><?=$m->menuId?></td>
	                                <td><?=$m->menuName?></td>
	                                <td><?=$m->parentId?></td>
	                                <td><?=$m->link?></td>
	                                <td><input type="checkbox" class="readAll" name="readP[]" id="customcheckbox-one<?=$j?>" value="<?=$m->menuId?>" /> <input type="checkbox" class="writeAll" name="writeP[]" value="<?=$m->menuId?>" /> <input type="checkbox" class="deleteAll" name="deleteP[]" value="<?=$m->menuId?>" /></td>
	                               
	                            </tr>
	                          <?php ++$i; } ?> 
	                        </tbody>
	                    </table>
	                    
		            </div>
                    </div>
                   
                   </div> <!-- row colum -->
                    <?php 
	                    	//$tables = new tables("basic",$allMenus->result());
	                        echo tables::basic("Menus",$allMenus->result());
	                    ?>
                    </form>
                   </div> <!-- row -->

