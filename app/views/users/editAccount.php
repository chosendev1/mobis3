 <div class="row">
                <div class="col-md-6 col-lg-4">
                           <!-- INPUT FIELDS -->
                    <div><?echo $status?></div>
                    <div class="panel panel-default panel-block">
                    <form id="addUserForm" method="POST" action="users/editAccount/<?=CAP_Session::get('userId')?>" data-parsley-namespace="data-parsley-" data-parsley-validate>
                        <div class="list-group">
                            <div class="list-group-item">
                                <h4 class="section-title"><?=$lang["editAccount"]?></h4>

                                
                                
                                <div class="form-group">
                                    <label for="input-counter"><?=$lang["oldpassword"]?></label>
                                    <input type="password" id="oldpassword" name="oldpassword" class="form-control" data-parsley-required="true">
                                </div>

                                <div class="form-group">
                                    <label for="input-counter"><?=$lang["newpassword"]?></label>
                                    <input type="password" id="password" name="password" class="form-control" data-parsley-required="true">
                                </div>
                                
                                 <div class="form-group">
                                    <label for="input-counter"><?=$lang["confirmPassword"]?></label>
                                    <input type="password" name="confirmPassword" class="form-control" data-parsley-equalto="#password" data-parsley-required="true">
                                </div>
                                                         
                        <footer class="panel-footer text-right">
                                  <button type="submit" class="btn btn-success"><?=$lang["submit"]?></button>
                                </footer>
                         </form>
                    </div>
                   </div> <!-- row colum -->
                   <!-- column 4 -->
                   
                   
                   </div> <!-- row -->

