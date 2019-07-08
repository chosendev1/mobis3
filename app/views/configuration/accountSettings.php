


<div class="row">
                <div class="col-md-6 col-lg-8">
                        <div class="panel panel-default panel-block panel-title-block">
                        <div class="panel-heading clearfix">
                            <div class="avatar">
                                <img src="images/user-icons/user4.jpg" alt="">
                                <div class="overlay">
                                    <div class="controls clearfix">
                                        <a href="javascript:;"><i class="icon-search"></i></a>
                                        <a href="javascript:;"><i class="icon-undo"></i></a>
                                        <a class="edit-item" href="javascript:;"><i class="icon-pencil"></i></a>
                                        <a class="trash-item" href="javascript:;"><i class="icon-trash"></i></a>
                                    </div>
                                    <div class="controls confirm-removal clearfix">
                                        <a class="remove-item" href="javascript:;">YES</a>
                                        <a class="remove-cancel" href="javascript:;">NO</a>
                                    </div>
                                </div>
                            </div>
                            <span class="title">CRANEAPPS</span>
                            <small>
                                <b class="text-bold">Location:</b> George st. 18B
                            </small>
                            <small>
                                <b class="text-bold">Category:</b> College
                            </small>
                        </div>
                    </div>
                                        <!-- INPUT FIELDS for tabs -->
                    <div class="panel panel-default panel-block">
                        <div class="list-group">
                            <div class="list-group-item">
                                <ul class="nav nav-tabs panel panel-default panel-block">
                        <li class="active"><a href="#tabsdemo-home" data-toggle="tab">Overview</a></li>        
                        <li><a href="#tabsdemo-settings" data-toggle="tab">Accounts</a></li>
                        <li><a href="#tabsdemo-profile" data-toggle="tab">Bank Accounts</a></li>
                        <li><a href="#tabsdemo-messages" data-toggle="tab">Bank Statements</a></li>
                        
                    </ul>


                                <div class="tab-content panel panel-default panel-block">
                        <div class="tab-pane list-group" id="tabsdemo-settings">
                <!--================================First tab Form======================================================-->        
                            <form action="#" data-parsley-namespace="data-parsley-" data-parsley-validate>
                            <!--div class="panel panel-default panel-block"-->
                                <div class="list-group">
                                    <div class="list-group-item">
                                        <h4 class="section-title"><?=$this->lang['accounts']?></h4>

                                        <div class="form-group">
                                            <label><?=$this->lang['bankName']?> <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="put bank name" data-parsley-required="true">
                                        </div>
                                        <div class="form-group">
                                            <label><?=$this->lang['accountsName']?> <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="put account name" data-parsley-required="true">
                                        </div>
                                        <div class="form-group">
                                            <label><?=$this->lang['accountsCode']?><span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="put account code" data-parsley-type="email" data-parsley-required="true">
                                        </div>
                                        <!--div class="form-group">
                                            <label><?=$this->lang['password']?><span class="text-danger">*</span></label>
                                            <input id="password" type="password" class="form-control" placeholder="Enter Password" data-parsley-required="true">
                                        </div>
                                        <div class="form-group">
                                            <label><?=$this->lang['confirmPassword']?> <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" placeholder="Confirm Password" data-parsley-equalto="#password" data-parsley-required="true">
                                        </div>
                                        <div class="form-group">
                                            <label><?=$this->lang['phoneNumber']?><span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Phone" data-parsley-type="phone" data-parsley-required="true">
                                        </div>
                                        <div class="form-group">
                                            <label>How Did You Hear About Us?</label>
                                            <textarea class="form-control" rows="5" placeholder="Optional"></textarea>
                                        </div-->
                                    </div>
                                </div>
                                <footer class="panel-footer text-right">
                                  <button type="submit" class="btn btn-success">Submit</button>
                                </footer>
                            <!--/div-->
                        </form>
                   <!-------========================End first tab form======================-------------------------->     
                        </div>
                    <!--=================second tab=================-->
                        <div class="tab-pane list-group" id="tabsdemo-profile">
                            <!--div class="list-group-item form-horizontal">
                            <div class="list-group-item"-->
                                <form action="#" data-parsley-namespace="data-parsley-" data-parsley-validate>
                            <!--div class="panel panel-default panel-block"-->
                                <div class="list-group">
                                    <div class="list-group-item">
                                        <h4 class="section-title"><?=$this->lang['bankAccount']?></h4>

                                        <div class="form-group">
                                            <label><?=$this->lang['bankName']?> <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter bank name" data-parsley-required="true">
                                        </div>
                                        <div class="form-group">
                                            <label><?=$this->lang['accountName']?> <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter account name" data-parsley-type="email" data-parsley-required="true">
                                        </div>
                                        <div class="form-group">
                                            <label><?=$this->lang['accountNo']?><span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter account number" data-parsley-type="phone" data-parsley-required="true">
                                        </div>
                                        <!--div class="form-group">
                                            <label>Your Website URL <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Your Website URL" data-parsley-type="url" data-parsley-required="true">
                                        </div>
                                        <div class="form-group">
                                            <label>Message <span class="text-danger">*</span></label>
                                            <textarea class="form-control" rows="4" data-parsley-minwords="4" data-parsley-required="true" placeholder="Type more details here"></textarea>
                                        </div-->
                                    </div>
                                </div>
                                <footer class="panel-footer text-right">
                                  <button type="submit" class="btn btn-success">Submit</button>
                                </footer>
                            <!--/div-->
                        </form>
            <!-------========================End second tab form======================--------------------->
                            <!--/div-->
                        </div>
            <!-------========================Third tab==================================--------->
                        <div class="tab-pane list-group" id="tabsdemo-messages">
                            <!--div class="list-group-item"-->
                                <form action="#" data-parsley-namespace="data-parsley-" data-parsley-validate>
                            <div class="panel panel-default panel-block">
                                <div class="list-group">
                                    <div class="list-group-item">
                                        <h4 class="section-title">Bank Statements</h4>
                                        <br />coming soon...

                                        <!--div class="form-group">
                                            <label>Full Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Your Full Name" data-parsley-required="true">
                                        </div>
                                        <div class="form-group">
                                            <label>Email <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Email" data-parsley-type="email" data-parsley-required="true">
                                        </div>
                                        <div class="form-group">
                                            <label>Phone <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Phone" data-parsley-type="phone" data-parsley-required="true">
                                        </div>
                                        <div class="form-group">
                                            <label>Your Website URL <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Your Website URL" data-parsley-type="url" data-parsley-required="true">
                                        </div>
                                        <div class="form-group">
                                            <label>Message <span class="text-danger">*</span></label>
                                            <textarea class="form-control" rows="4" data-parsley-minwords="4" data-parsley-required="true" placeholder="Type more details here"></textarea>
                                        </div-->
                                    </div>
                                </div>
                                <footer class="panel-footer text-right">
                                  <button type="submit" class="btn btn-success">Submit</button>
                                </footer>
                            <!--/div-->
                        </form>
               <!-------========================End Third tab form======================--------------->
                            </div>
                        </div>
                        <div class="tab-pane list-group active" id="tabsdemo-home">
                            <div class="list-group-item">
                            <div class="form-group">
                                <!--h4 class="section-title preface-title">Settings</h4-->
                                <h4 >Accounts</h4>
                                <table class="table table-striped">
                                        <thead class="">
                                            <tr>
                                                <th>#</th>
                                                <th>Bank Name</th>
                                                <th>Account Name</th>
                                                <th>Account Code</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Bank Of Africa</td>
                                                <td>Tution Fees</td>
                                                <td>capps/001</td>
                                                
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Bank Of Africa</td>
                                                <td>Institution Requirements</td>
                                                <td>capps/002</td>
                                                
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>CraneApps Bank</td>
                                                <td>Residential fee</td>
                                                <td>capps/003</td>
                                               
                                            </tr>
                                             
                                        </tbody>
                                    </table>
                                    
                                    <h4 >Bank Account</h4>
                                    <!--Basic Table-->
                                    <table class="table table-striped">
                                        <thead class="">
                                            <tr>
                                                <th>#</th>
                                                <th>Bank Name</th>
                                                <th>Account Name</th>
                                                <th>Account Number</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Bank Of Africa</td>
                                                <td>Otto</td>
                                                <td>400012000273</td>
                                                
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Bank Of Africa</td>
                                                <td>Otto</td>
                                                <td>400373802333</td>
                                                
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>CraneApps</td>
                                                <td>Simba</td>
                                                <td>200082311777</td>
                                                
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                    <!--==-->
                                  </div>
                            </div>
                        </div>
                    </div>
					<!--=========End of Tab=======-->

                            </div>
                        </div>
                    </div>

                    
                </div>

                <div class="col-md-6 col-lg-4">
                                        <div class="panel panel-default panel-block">
                        <div class="list-group">
                            <div class="list-group-item">
                                <h4 class="section-title">Checkboxes and Radios</h4>
                                <div class="form-group">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" value="">
                                        Option 1
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" value="">
                                        Option 2
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" value="">
                                        Option 3
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" disabled value="">
                                        Option Disabled
                                    </label>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="form-group">
                                    <div>
                                      <label>
                                        <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
                                        Radio 1
                                      </label>
                                    </div>
                                    <div>
                                      <label>
                                        <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
                                        Radio 2
                                      </label>
                                    </div>
                                    <div>
                                      <label>
                                        <input type="radio" disabled name="optionsRadios" id="optionsRadios3" value="option3">
                                        Radio Disabled
                                      </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                                        <!-- DATE AND TIME PICKERS -->
                    <!-- http://timschlechter.github.io/bootstrap-tagsinput/examples/ -->
                    <!-- NOTE: Proton is FORCING bootstrap 2 plugin mode in order to support font icons -->
                    <div class="panel panel-default panel-block">
                        <div class="list-group">
                            <div class="list-group-item">
                                <h4 class="section-title">Date Picker</h4>
                                <div class="form-group">
                                    <div>
                                        <input type="text" value="2012-05-15" class="datetimepicker-month form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <h4 class="section-title">Date Picker (with Time)</h4>
                                <div class="form-group">
                                    <div>
                                        <input type="text" value="2012-05-15 21:05" class="datetimepicker-default form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <h4 class="section-title">Date Range</h4>
                                <div class="form-group">
                                </div>
                                <form class="form-horizontal" role="form">
                                    <div class="form-group">
                                        <label for="fromdate" class="col-lg-4 control-label">From:</label>
                                        <div class="col-lg-8">
                                            <input id="fromdate" type="text" value="" class="datetimepicker-range form-control" placeholder="Start Date">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="todate" class="col-lg-4 control-label">To:</label>
                                        <div class="col-lg-8">
                                            <input id="todate" type="text" value="" class="datetimepicker-range form-control" placeholder="End Date">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                        </div>
                    </div>
                                        
                </div>
            </div>
        </section>

        
        <script src="scripts/bootstrap.min.js"></script>

		<!-- Proton base scripts: -->
        
        <script src="scripts/main.js"></script>
		<script src="scripts/proton/common.js"></script>
		<script src="scripts/proton/main-nav.js"></script>
		<script src="scripts/proton/user-nav.js"></script>
		


        <!-- Page-specific scripts: -->
        <script src="scripts/proton/sidebar.js"></script>
        <script src="scripts/proton/forms.js"></script>
        <!-- jsTree -->
        <script src="scripts/vendor/jquery.jstree.js"></script>
        <!-- Select2 For Bootstrap3 -->
        <!-- https://github.com/fk/select2-bootstrap-css -->
            <script src="scripts/vendor/select2.min.js"></script>

        <!-- uniformJs -->
            <script src="scripts/vendor/jquery.uniform.min.js"></script>

        <!-- Date Time Picker -->
        <!-- https://github.com/smalot/bootstrap-datetimepicker -->
        <!-- NOTE: Original JS file is modified: Proton is forcing bootstrap 2 plugin mode in order to support font icons -->
            <script src="scripts/vendor/bootstrap-datetimepicker.js"></script>

        <!-- Character Counter -->
        <!-- http://cssglobe.com/post/7161/jquery-plugin-simplest-twitterlike-dynamic-character-count-for-textareas -->
            <script src="scripts/vendor/charCount.js"></script>

        <!-- Word Counter -->
        <!-- http://bavotasan.com/2011/simple-textarea-word-counter-jquery-plugin/ -->
            <script src="scripts/vendor/jquery.textareaCounter.js"></script>

        <!-- WYSIWYG Editor -->
        <!-- http://hackerwins.github.io/summernote/ -->
            <script src="scripts/vendor/summernote.js"></script>

        <!-- File Input -->
        <!-- http://jasny.github.io/bootstrap/javascript/#fileinput -->
            <script src="scripts/vendor/fileinput.js"></script>

    </body>
</html>
