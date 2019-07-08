


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

                                        <!-- TABS -->
                    <ul class="nav nav-tabs panel panel-default panel-block">
                        <li class="active"><a href="#user-overview" data-toggle="tab">OVERVIEW</a></li>
                        <!--li><a href="#user-messages" data-toggle="tab">MESSAGES</a></li>
                        <li><a href="#user-activity" data-toggle="tab">ACTIVITY</a></li-->
                        <li><a href="#user-settings" data-toggle="tab">LEVELS</a></li>
                    </ul>
                    <div class="tab-content panel panel-default panel-block">
                        <div style="" class="tab-pane active" id="user-overview">
                            <!--div class="list-group"-->
                                <div class="list-group-item">
                                    <h4>
                                        Information
                                    </h4>
                                    <p>
                                        <label class="col-lg-4 control-label">Organisation Level : </label><button class="btn btn-lg btn-primary">ONE (1)</button>
                                    </p>
                                    <p>
                                        <label class="col-lg-4 control-label">Description : </label><button  class="btn btn-lg btn-primary">DIVISION</button>
                                    </p>
                                </div>
                                <!--/div-->
                                
                            
                        </div>
  <!--commented out list tabs=== 
                        <!--div class="tab-pane scrollable list-group" id="user-activity">
                            <div class="panel panel-default scrollable messages-view">
                                <div class="arrow user-menu-arrow"></div>
                                
                            </div>
                        </div>
                        <div class="tab-pane scrollable list-group" id="user-messages">
                            <div class="panel panel-default scrollable messages-view">
                                <div class="arrow user-menu-arrow"></div>
                                
                            </div>
                        </div>
   =========================-->                     
                        <div class="tab-pane scrollable list-group" id="user-settings">
                            <form action="#" data-parsley-namespace="data-parsley-" data-parsley-validate>
                            <div class="panel panel-default panel-block">
                                <div class="list-group">
                                    <div class="list-group-item">
                                        <h4 class="section-title"><?=$this->lang['organizationLevels']?></h4>

                                        <div class="form-group">
                                            <label><?=$this->lang['levelName']?><span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Level Name" data-parsley-required="true">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label><?=$this->lang['description']?><span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter description" data-parsley-type="text" data-parsley-required="true">
                                        </div>
                                        <div class="form-group">
                                            <label><?=$this->lang['levelDuration']?><span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter period" data-parsley-type="" data-parsley-required="true">
                                        </div>
                                        <!--div class="form-group">
                                            <label>Telephone <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Phone Number" data-parsley-type="phone" data-parsley-required="true">
                                        </div>
                                        <div class="form-group">
                                            <label>Email Address<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Enter Email" data-parsley-type="email" data-parsley-required="true">
                                        </div>
                                        <div class="form-group">
                                            <label>Website URL <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Your Website URL" data-parsley-type="url" data-parsley-required="true">
                                        </div>
                                        <div class="form-group">
                                        <label>Header</label>
                                    <div>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                          <div class="input-group">
                                            <div class="form-control uneditable-input span3" data-trigger="fileinput"><i class="icon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
                                            <span class="input-group-addon btn btn-primary btn-file"><span class="fileinput-new">Select file</span><span class="fileinput-exists">Change</span><input type="file" name="..."></span>
                                            <a href="#" class="input-group-addon btn btn-primary fileinput-exists" data-dismiss="fileinput">Remove</a>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                                        
                                        <div class="form-group">
                                    <label>Footer</label>
                                    <div>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                          <div class="input-group">
                                            <div class="form-control uneditable-input span3" data-trigger="fileinput"><i class="icon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
                                            <span class="input-group-addon btn btn-primary btn-file"><span class="fileinput-new">Select file</span><span class="fileinput-exists">Change</span><input type="file" name="..."></span>
                                            <a href="#" class="input-group-addon btn btn-primary fileinput-exists" data-dismiss="fileinput">Remove</a>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                                        
                                        
                                    </div>
                                
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Change Avatar</label>
                                        <div class="col-md-10">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                              <div class="input-group">
                                                <div class="form-control uneditable-input span3" data-trigger="fileinput"><i class="icon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
                                                <span class="input-group-addon btn btn-primary btn-file"><span class="fileinput-new">Select file</span><span class="fileinput-exists">Change</span><input type="file" name="..."></span>
                                                <a href="#" class="input-group-addon btn btn-primary fileinput-exists" data-dismiss="fileinput">Remove</a>
                                              </div>
                                            </div>
                                        </div-->
                                    </div>
                                   </div>
                         <!--============contact infowas=============-->

                                <footer class="panel-footer text-right">
                                  <button type="submit" class="btn btn-success">Submit</button>
                                </footer>
                            </div>
                        </form>
                        </div>
                        
                    </div> 

                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="panel panel-default panel-block">
                        <div class="panel-heading clearfix">
                            <span class="title">Contact</span>
                            <small>
                                <br /><b class="text-bold">Email:</b> <a href="mailto:info@craneapps.com">mailtohelp</a>
                            </small>
                            <small>
                                <br /><b class="text-bold">Facebook:</b> <a href="https://www.facebook.com/craneappsAutomatingProcessess">CraneApps</a>
                            </small>
                            <small>
                                <br /><b class="text-bold">Twitter:</b> <a href="https://twitter.com/AskiBogere">Capps</a>
                            </small>
                            <small>
                                <br /><b class="text-bold">Website:</b> <a href="http://www.craneapps.com">www.craneapps.com</a>
                            </small>
                            <!--div class="map" id="hybridMap" style="height:250px; width:100%"></div-->
                        </div>
                    </div>
                                        <div class="panel panel-default panel-block">
                        <div class="list-group">
                            <div class="list-group-item">
                                <h4 class="section-title">Personal Skills Rated 1 To 5</h4>
                                <div class="form-group">
                                    <div id="hero-bar" class="graph" style="height: 200px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Word Counter -->
        <!-- http://bavotasan.com/2011/simple-textarea-word-counter-jquery-plugin/ -->
            <script src="scripts/vendor/jquery.textareaCounter.js"></script>
            </div>
        </section>


        

        
        <script src="scripts/bootstrap.min.js"></script>

		<!-- Proton base scripts: -->
        
        <script src="scripts/main.js"></script>
		<script src="scripts/proton/common.js"></script>
		<script src="scripts/proton/main-nav.js"></script>
		<script src="scripts/proton/user-nav.js"></script>
		

	<!-- Select2 For Bootstrap3 -->
        <!-- https://github.com/fk/select2-bootstrap-css -->
            <script src="scripts/vendor/select2.min.js"></script>
	
        <!-- Page-specific scripts: -->
        <script src="scripts/proton/sidebar.js"></script>
        <script src="scripts/proton/userProfile.js"></script>
        <!-- jsTree -->
        <script src="scripts/vendor/jquery.jstree.js"></script>
        <!-- Raphael, used for graphs -->
        <!-- http://raphaeljs.com/ -->
            <script src="scripts/vendor/raphael-min.js"></script>
        
        <!-- Morris graphs -->
        <!-- https://github.com/oesmith/morris.js -->
            <script src="scripts/vendor/morris.min.js"></script>

        <!-- Google Maps -->
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

        <!-- Word Counter -->
        <!-- http://bavotasan.com/2011/simple-textarea-word-counter-jquery-plugin/ -->
            <script src="scripts/vendor/jquery.textareaCounter.js"></script>

        <!-- File Input -->
        <!-- http://jasny.github.io/bootstrap/javascript/#fileinput -->
            <script src="scripts/vendor/fileinput.js"></script>
            
      </body>
</html>
