 <div class="row">
                <div class="col-md-6 col-lg-4">
                           <!-- INPUT FIELDS -->
                           <?php
			   	$form = new forms(true,true,"POST","user/registerMenu","Skills","controller","",true);
			   	$form->setHeadings("Add Menu Form", "This form is for adding menus, that you'll then code. Don't play arround, we are serious.");
			   	$form->addField(array("lableName" => $lang["menuName"], "type" => "text", "name" => "name","value" => "", "desc" => "Enter menu name (what the users will see.)"));
			   	$form->addField(array("lableName" => $lang["menuLink"], "type" => "text", "name" => "link","value" => "", "desc" => "Enter menu Link (URL, default will prepend controller name)"));
			   	$form->addField(array("lableName" => $lang["menuParent"], "type" => "select", "name" => "parent", "desc" => "", "options" => $menus, "nested" => false));
			   	$form->addField(array("lableName" => "First Name", "type" => "button", "name" => "save", "desc" => "Stupid Button"));
			   ?>
			    <?=$form->render();?>
                   <!-- <div class="panel panel-default panel-block">
                    <form method="POST" action="user/addMenu">
                        <div class="list-group">
                            <div class="list-group-item">
                                <h4 class="section-title"><?=$lang["addMenu"]?></h4>

                                <div class="form-group">
                                    <label for="basic-input"><?=$lang["menuName"]?></label>
                                    <input id="basic-input" name="name" class="form-control" placeholder="Menu Name">
                                </div>

                                <div class="form-group">
                                    <label for="basic-input"><?=$lang["menuLink"]?></label>
                                    <input id="basic-input" name="link" class="form-control" placeholder="Menu Link">
                                </div>
                                
                                <div class="form-group">
                                    <label><?=$lang["menuParent"]?></label>
                                    <select class="form-control" id="parent" name="parent">
                                        <optgroup label="<?=$lang['parentSelect']?>">
                                            <option value="0">None</option>
                                            <?=$menu?>
                                        </optgroup>
                                    </select>
                                </div>
                               
                            </div>
                        </div>
                        <footer class="panel-footer text-right">
                                  <button type="submit" class="btn btn-success">Submit</button>
                                </footer>
                         </form>
                    </div> -->
                   </div> <!-- row colum -->
                   <!-- column 4 -->
                    <div class="col-md-6 col-lg-8">
	                     <?=tables::basic("Menus",$allMenus->result());?>
		           
		            </div>
                   <!-- /end colum 4-->
                   
                   </div> <!-- row -->

