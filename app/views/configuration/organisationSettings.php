
                <!-- START row -->
                <div class="row">
                    <!-- Left / Top Side -->
                    <div class="col-lg-3">
                        <!-- tab menu -->
                        <ul class="list-group list-group-tabs">
                            <li class="list-group-item active"><a href="#profile" data-toggle="tab"><i class="ico-user2 mr5"></i> Profile</a></li>
                            <li class="list-group-item"><a href="#accounts" data-toggle="tab"><i class="ico-archive2 mr5"></i> Accounts</a></li>
                            <li class="list-group-item"><a href="#security" data-toggle="tab"><i class="ico-shield3 mr5"></i> Security &amp; privacy</a></li>
                            <li class="list-group-item"><a href="#currency" data-toggle="tab"><i class="ico-key2 mr5"></i> Currency</a></li>
                        </ul>
                        <!-- tab menu -->

                        <hr><!-- horizontal line -->

                        <!-- figure with progress -->
                        <ul class="list-table">
                            <li style="width:70px;">
                                <img class="img-circle img-bordered" src="../image/avatar/avatar7.jpg" alt="" width="65px">
                            </li>
                            <li class="text-left">
                                <h5 class="semibold ellipsis mt0">Institution</h5>
                                <div style="max-width:200px;">
                                    <div class="progress progress-xs mb5">
                                        <div class="progress-bar progress-bar-warning" style="width:70%"></div>
                                    </div>
                                    <p class="text-muted clearfix nm">
                                        <span class="pull-left">Profile complete</span>
                                        <span class="pull-right">70%</span>
                                    </p>
                                </div>
                            </li>
                        </ul>
                        <!--/ figure with progress -->

                        <hr><!-- horizontal line -->

                       
                    </div>
                    <!--/ Left / Top Side -->

                    <!-- Left / Bottom Side -->
                    <div class="col-lg-9">
                        <!-- START Tab-content -->
                        <div class="tab-content">
                            <!-- tab-pane: profile -->
                            <div class="tab-pane active" id="profile">
                                <!-- form profile -->
                                <?php
                                	$form = new forms(true,true,"POST","configuration/saveOrganisationSettings","OrganisationSettings","controller","",true);
			   	$form->setHeadings("Organization Settings", "This information is required for creating the template of your organization.");
			   	$form->addField(array("lableName" => "Organization Name", "type" => "text", "name" => "name","value" => "", "required" => "Yes", "desc" => "Full Name of the Institution"));
			   	$form->addField(array("lableName" => "Code", "type" => "text", "name" => "code","value" => "", "required" => "Yes","desc" => ""));
			   	$form->addField(array("lableName" => "Ownership", "type" => "select", "name" => "ownership","value" => "", "desc" => "", "options" => array("Government" => "Government","Private" => "Private","Public" => "Public", "Religion" => "Religion"), "nested" => false));
			   	$form->addField(array("lableName" => "Postal Address", "type" => "text", "name" => "postalAddress","value" => "", "desc" => ""));
			   	$form->addField(array("lableName" => "Physical address", "type" => "text", "name" => "physicalAddress","value" => "", "desc" => ""));
			   	$form->addField(array("lableName" => "Email", "type" => "email", "name" => "email","value" => "", "required" => "Yes","desc" => "e.g info@orgdomain.com"));
			   	$form->addField(array("lableName" => "Telephone", "type" => "text", "name" => "phone","value" => "", "required" => "Yes","desc" => "e.g +250782247657"));
			   	$form->addField(array("lableName" => "Website", "type" => "text", "name" => "website","value" => "", "desc" => "e.g http://iprckigali.ac.rw"));
			   	$form->addField(array("lableName" => "Date Established", "type" => "date", "name" => "dateEstablished","value" => "", "desc" => ""));
			   	$form->addField(array("lableName" => "Province", "type" => "select", "name" => "province", "required" => "Yes","desc" => "", "options" => libinc::getCountryData(0), "nested" => true, "mydiv" => "provinceDiv", "calldiv" => "districtDiv", "calledName" => "district", "callbackdiv" => "sectorDiv", "callbackname"=>"sector","fun" => "xajax_getHierachyData"));
                                	$form->addField(array("lableName" => "District", "type" => "select", "name" => "district","required" => "Yes", "desc" => "", "options" => libinc::getCountryData(-1), "nested" => true, "mydiv" => "districtDiv", "calldiv" => "sectorDiv", "calledName" => "sector", "callbackdiv" =>"cellDiv", "callbackname"=>"cell", "fun" => "xajax_getHierachyData"));
                                	$form->addField(array("lableName" => "Sector", "type" => "select", "name" => "sector","required" => "Yes", "desc" => "", "options" => libinc::getCountryData(-1), "nested" => true, "mydiv" => "sectorDiv", "calldiv" => "cellDiv", "calledName" => "cell", "callbackdiv" =>"villageDiv", "callbackname"=>"village", "fun" => "xajax_getHierachyData"));
                                	$form->addField(array("lableName" => "Cell", "type" => "select", "name" => "cell","required" => "Yes", "desc" => "", "options" => libinc::getCountryData(-1), "nested" => true, "mydiv" => "cellDiv", "calldiv" => "villageDiv", "calledName" => "village","callbackdiv" =>"", "callbackname"=>"", "fun" => "xajax_getHierachyData"));
                                	$form->addField(array("lableName" => "Village", "type" => "select", "name" => "village","required" => "Yes", "desc" => "", "options" => libinc::getCountryData(-1), "nested" => true,"mydiv" => "villageDiv", "calldiv" => "villageDiv", "calledName" => "village", "fun" => "xajax_getHierachyData"));
                               
			   	$form->addField(array("lableName" => "Longitude", "type" => "text", "name" => "longitude","value" => "", "desc" => ""));
			   	$form->addField(array("lableName" => "Latitude", "type" => "text", "name" => "latitude","value" => "", "desc" => ""));
			   	$form->addField(array("lableName" => "Zip Code", "type" => "text", "name" => "Zip Code","value" => "", "desc" => ""));
			   	$form->addField(array("lableName" => "certificateNumber", "type" => "text", "name" => "certificateNumber","value" => "", "desc" => ""));
			   	
			   	$form->addField(array("lableName" => "First Name", "type" => "button", "name" => "save", "desc" => "Stupid Button"));
                                ?>
                                <?=$form->render()?>
                                <!--/ form profile -->
                            </div>
                            <!--/ tab-pane: profile -->

                            <!-- tab-pane: account -->
                            <div class="tab-pane" id="account">
                                <!-- form account -->
                                <?php
                                	$form = new forms(true,true,"POST","configuration/organisationSettings","OrganisationSettings","controller","",true);
			   		$form->setHeadings("Bank accounts", "This information is required for creating the template of your organization.");
			   	?>
                               <?=$form->render()?>
                            </div>
                            <!--/ tab-pane: account -->

                            <!-- tab-pane: security -->
                            <div class="tab-pane" id="security">
                                <?php
                                	$form = new forms(true,true,"POST","configuration/organisationSettings","OrganisationSettings","controller","",true);
			   		$form->setHeadings("Bank accounts", "This information is required for creating the template of your organization.");
			   	?>
                               <?=$form->render()?>
                            </div>
                            <!--/ tab-pane: security -->

                            <!-- tab-pane: password -->
                            <div class="tab-pane" id="currency">
                                <?php
                                	$form = new forms(true,true,"POST","configuration/organisationSettings","OrganisationSettings","controller","",true);
			   		$form->setHeadings("Bank accounts", "This information is required for creating the template of your organization.");
			   	?>
                               <?=$form->render()?>
                            </div>
                            <!--/ tab-pane: password -->
                        </div>
                        <!--/ END Tab-content -->
                    </div>
                    <!--/ Left / Bottom Side -->
                </div>
                <!--/ END row -->
            </div>
            <!--/ END Template Container -->

            <!-- START To Top Scroller -->
            <a href="#" class="totop animation" data-toggle="waypoints totop" data-showanim="bounceIn" data-hideanim="bounceOut" data-offset="50%"><i class="ico-angle-up"></i></a>
            <!--/ END To Top Scroller -->

        </section>
        <!--/ END Template Main -->

        <!-- START Template Sidebar (right) -->
        <aside class="sidebar sidebar-right">
            <!-- START Offcanvas -->
            <div class="offcanvas-container" data-toggle="offcanvas" data-options='{"openerClass":"offcanvas-opener", "closerClass":"offcanvas-closer"}'>
                <!-- START Wrapper -->
                <div class="offcanvas-wrapper">
                    <!-- Offcanvas Left -->
                    <div class="offcanvas-left">
                        <!-- Header -->
                        <div class="header pl0 pr0">
                            <ul class="list-table nm">
                                <li style="width:50px;">
                                    <a href="javascript:void(0);" class="btn btn-link text-default offcanvas-closer"><i class="ico-arrow-left6 fsize16"></i></a>
                                </li>
                                <li class="text-center">
                                    <h5 class="semibold nm">Settings</h5>
                                </li>
                                <li style="width:50px;" class="text-right">
                                    <a href="javascript:void(0);" class="btn btn-link text-default"><i class="ico-info22 fsize16"></i></a>
                                </li>
                            </ul>
                        </div>
                        <!--/ Header -->

                        <!-- Content -->
                        <div class="content slimscroll">
                            <h5 class="heading">News Feed</h5>
                            <ul class="topmenu">
                                <li>
                                    <a href="javascript:void(0);">
                                        <span class="figure"><i class="ico-plus"></i></span>
                                        <span class="text">Add &amp; Manage Source</span>
                                        <span class="arrow"></span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <span class="figure"><i class="ico-google-plus"></i></span>
                                        <span class="text">Google Reader</span>
                                        <span class="arrow"></span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <span class="figure"><i class="ico-twitter2"></i></span>
                                        <span class="text">Twitter Source</span>
                                        <span class="arrow"></span>
                                    </a>
                                </li>
                            </ul>

                            <h5 class="heading">Friends</h5>
                            <ul class="topmenu">
                                <li>
                                    <a href="javascript:void(0);">
                                        <span class="figure"><i class="ico-search22"></i></span>
                                        <span class="text">Find Friends</span>
                                        <span class="arrow"></span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <span class="figure"><i class="ico-user-plus2"></i></span>
                                        <span class="text">Add Friends</span>
                                        <span class="arrow"></span>
                                    </a>
                                </li>
                            </ul>

                            <h5 class="heading">Account</h5>
                            <ul class="topmenu">
                                <li>
                                    <a href="javascript:void(0);">
                                        <span class="figure"><i class="ico-user2"></i></span>
                                        <span class="text">Edit Account</span>
                                        <span class="arrow"></span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <span class="figure"><i class="ico-envelop"></i></span>
                                        <span class="text">Manage Subscription</span>
                                        <span class="arrow"></span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <span class="figure"><i class="ico-location6"></i></span>
                                        <span class="text">Location Service</span>
                                        <span class="arrow"></span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <span class="figure"><i class="ico-switch"></i></span>
                                        <span class="text">Logout</span>
                                        <span class="arrow"></span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="text-danger">
                                        <span class="figure"><i class="ico-minus-circle2"></i></span>
                                        <span class="text">Deactivate</span>
                                        <span class="arrow"></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!--/ Content -->
                    </div>
                    <!--/ Offcanvas Left -->

                    <!-- Offcanvas Content -->
                    <div class="offcanvas-content">
                        <!-- Content -->
                        <div class="content slimscroll">
                            <!-- START profile -->
                            <div class="panel nm">
                    <!-- thumbnail -->
                    <div class="thumbnail">
                        <!-- media -->
                        <div class="media">
                            <!-- indicator -->
                            <div class="indicator"><span class="spinner"></span></div>
                            <!--/ indicator -->
                            <img data-toggle="unveil" src="../image/background/400x250/placeholder.jpg" data-src="../image/background/400x250/background3.jpg" alt="Cover" width="100%">
                        </div>
                        <!--/ media -->
                    </div>
                    <!--/ thumbnail -->
                </div>
                <div class="panel-body text-center" style="margin-top:-55px;z-index:11">
                    <img class="img-circle mb5" src="../image/avatar/avatar7.jpg" alt="" width="75">
                    <h5 class="bold mt0 mb5">Erich Reyes</h5>
                    <p>Administrator</p>
                    <button type="button" class="btn btn-primary offcanvas-opener offcanvas-open-ltr"><i class="ico-settings"></i> Settings</button>
                </div>
                            <!--/ END profile -->

                            <!-- START contact -->
                            <div class="media-list media-list-contact">
                    <h5 class="heading pa15 pb0">Family</h5>
                    <a href="javascript:void(0);" class="media offcanvas-opener offcanvas-open-rtl">
                        <span class="media-object pull-left">
                            <img src="../image/avatar/avatar1.jpg" class="img-circle" alt="">
                        </span>
                        <span class="media-body">
                            <span class="media-heading"><span class="hasnotification hasnotification-success mr5"></span> Autumn Barker</span>
                            <span class="media-meta ellipsis">Malaysia</span>
                        </span>
                    </a>
                    <a href="javascript:void(0);" class="media offcanvas-opener offcanvas-open-rtl">
                        <span class="media-object pull-left">
                            <img src="../image/avatar/avatar2.jpg" class="img-circle" alt="">
                        </span>
                        <span class="media-body">
                            <span class="media-heading"><span class="hasnotification hasnotification-success mr5"></span> Giselle Horn</span>
                            <span class="media-meta ellipsis">Bolivia</span>
                        </span>
                    </a>
                    <a href="javascript:void(0);" class="media offcanvas-opener offcanvas-open-rtl">
                        <span class="media-object pull-left">
                            <img src="../image/avatar/avatar.png" class="img-circle" alt="">
                        </span>
                        <span class="media-body">
                            <span class="media-heading"><span class="hasnotification hasnotification-danger mr5"></span> Austin Shields</span>
                            <span class="media-meta ellipsis">Timor-Leste</span>
                        </span>
                    </a>
                    <a href="javascript:void(0);" class="media offcanvas-opener offcanvas-open-rtl">
                        <span class="media-object pull-left">
                            <img src="../image/avatar/avatar.png" class="img-circle" alt="">
                        </span>
                        <span class="media-body">
                            <span class="media-heading"><span class="hasnotification hasnotification-danger mr5"></span> Caryn Gibson</span>
                            <span class="media-meta ellipsis">Libya</span>
                        </span>
                    </a>
                    <a href="javascript:void(0);" class="media offcanvas-opener offcanvas-open-rtl">
                        <span class="media-object pull-left">
                            <img src="../image/avatar/avatar3.jpg" class="img-circle" alt="">
                        </span>
                        <span class="media-body">
                            <span class="media-heading"><span class="hasnotification hasnotification-success mr5"></span> Nash Evans</span>
                            <span class="media-meta ellipsis">Honduras</span>
                        </span>
                    </a>

                    <h5 class="heading pa15 pb0">Friends</h5>
                    <a href="javascript:void(0);" class="media offcanvas-opener offcanvas-open-rtl">
                        <span class="media-object pull-left">
                            <img src="../image/avatar/avatar4.jpg" class="img-circle" alt="">
                        </span>
                        <span class="media-body">
                            <span class="media-heading"><span class="hasnotification hasnotification-default mr5"></span> Josiah Johnson</span>
                            <span class="media-meta ellipsis">Belgium</span>
                        </span>
                    </a>
                    <a href="javascript:void(0);" class="media offcanvas-opener offcanvas-open-rtl">
                        <span class="media-object pull-left">
                            <img src="../image/avatar/avatar.png" class="img-circle" alt="">
                        </span>
                        <span class="media-body">
                            <span class="media-heading"><span class="hasnotification hasnotification-default mr5"></span> Philip Hewitt</span>
                            <span class="media-meta ellipsis">Bahrain</span>
                        </span>
                    </a>
                    <a href="javascript:void(0);" class="media offcanvas-opener offcanvas-open-rtl">
                        <span class="media-object pull-left">
                            <img src="../image/avatar/avatar5.jpg" class="img-circle" alt="">
                        </span>
                        <span class="media-body">
                            <span class="media-heading"><span class="hasnotification hasnotification-default mr5"></span> Wilma Hunt</span>
                            <span class="media-meta ellipsis">Dominica</span>
                        </span>
                    </a>
                    <a href="javascript:void(0);" class="media offcanvas-opener offcanvas-open-rtl">
                        <span class="media-object pull-left">
                            <img src="../image/avatar/avatar6.jpg" class="img-circle" alt="">
                        </span>
                        <span class="media-body">
                            <span class="media-heading"><span class="hasnotification hasnotification-success mr5"></span> Noah Gill</span>
                            <span class="media-meta ellipsis">Guatemala</span>
                        </span>
                    </a>

                    <h5 class="heading pa15 pb0">Others</h5>
                    <a href="javascript:void(0);" class="media offcanvas-opener offcanvas-open-rtl">
                        <span class="media-object pull-left">
                            <img src="../image/avatar/avatar8.jpg" class="img-circle" alt="">
                        </span>
                        <span class="media-body">
                            <span class="media-heading"><span class="hasnotification hasnotification-success mr5"></span> David Fisher</span>
                            <span class="media-meta ellipsis">French Guiana</span>
                        </span>
                    </a>
                    <a href="javascript:void(0);" class="media offcanvas-opener offcanvas-open-rtl">
                        <span class="media-object pull-left">
                            <img src="../image/avatar/avatar9.jpg" class="img-circle" alt="">
                        </span>
                        <span class="media-body">
                            <span class="media-heading"><span class="hasnotification hasnotification-success mr5"></span> Samantha Avery</span>
                            <span class="media-meta ellipsis">Jersey</span>
                        </span>
                    </a>
                    <a href="javascript:void(0);" class="media offcanvas-opener offcanvas-open-rtl">
                        <span class="media-object pull-left">
                            <img src="../image/avatar/avatar.png" class="img-circle" alt="">
                        </span>
                        <span class="media-body">
                            <span class="media-heading"><span class="hasnotification hasnotification-success mr5"></span> Madaline Medina</span>
                            <span class="media-meta ellipsis">Finland</span>
                        </span>
                    </a>
                </div>
                            <!--/ END contact -->
                        </div>
                        <!--/ Content -->
                    </div>
                    <!--/ Offcanvas Content -->

                    <!-- Offcanvas Right -->
                    <div class="offcanvas-right has-footer">
                        <!-- Header -->
                        <div class="header pl0 pr0">
                            <ul class="list-table nm">
                                <li style="width:50px;">
                                    <a href="javascript:void(0);" class="btn btn-link text-default offcanvas-closer"><i class="ico-arrow-left6 fsize16"></i></a>
                                </li>
                                <li class="text-center">
                                    <h5 class="semibold nm">
                                        <p class="nm">Autumn Barker</p>
                                        <small>Last seen 02:21am</small>
                                    </h5>
                                </li>
                                <li style="width:50px;" class="text-right">
                                    <a href="javascript:void(0);" class="btn btn-link text-default"><i class="ico-info22 fsize16"></i></a>
                                </li>
                            </ul>
                        </div>
                        <!--/ Header -->

                        <!-- Content -->
                        <div class="content slimscroll">
                            <!-- START chat -->
                            <ul class="media-list media-list-bubble">
                            <li class="media media-right">
                                <a href="javascript:void(0);" class="media-object">
                                    <img src="../image/avatar/avatar7.jpg" class="img-circle" alt="">
                                </a>
                                <div class="media-body">
                                    <p class="media-text">eros non enim commodo hendrerit.</p>
                                    <span class="clearfix"></span>
                                    <p class="media-text">Suspendisse dui.</p>
                                    <span class="clearfix"></span>
                                    <p class="media-text">eu nulla at</p>
                                    <!-- meta -->
                                    <span class="clearfix"></span><!-- important: clearing floated media text -->
                                    <p class="media-meta">Sun, Mar 02</p>
                                </div>
                            </li>
                            <li class="media">
                                <a href="javascript:void(0);" class="media-object">
                                    <img src="../image/avatar/avatar6.jpg" class="img-circle" alt="">
                                </a>
                                <div class="media-body">
                                    <p class="media-text">Etiam laoreet, libero et tristique pellentesque, tellus sem mollis dui, in sodales elit erat.</p>
                                    <span class="clearfix"></span>
                                    <p class="media-text">faucibus ut, nulla. Cras eu tellus</p>
                                    <!-- meta -->
                                    <span class="clearfix"></span><!-- important: clearing floated media text -->
                                    <p class="media-meta">Tue, Oct 01</p>
                                </div>
                            </li>
                            <li class="media media-right">
                                <a href="javascript:void(0);" class="media-object">
                                    <img src="../image/avatar/avatar7.jpg" class="img-circle" alt="">
                                </a>
                                <div class="media-body">
                                    <p class="media-text">Duis a mi fringilla mi lacinia mattis. Integer</p>
                                    <!-- meta -->
                                    <span class="clearfix"></span><!-- important: clearing floated media text -->
                                    <p class="media-meta">Fri, Sep 27</p>
                                </div>
                            </li>
                            <li class="media">
                                <a href="javascript:void(0);" class="media-object">
                                    <img src="../image/avatar/avatar6.jpg" class="img-circle" alt="">
                                </a>
                                <div class="media-body">
                                    <p class="media-text">Praesent interdum ligula eu enim. Etiam imperdiet dictum magna.</p>
                                    <!-- meta -->
                                    <span class="clearfix"></span><!-- important: clearing floated media text -->
                                    <p class="media-meta">Wed, Aug 28</p>
                                </div>
                            </li>
                            <li class="media media-right">
                                <a href="javascript:void(0);" class="media-object">
                                    <img src="../image/avatar/avatar7.jpg" class="img-circle" alt="">
                                </a>
                                <div class="media-body">
                                    <p class="media-text">Aliquam rutrum lorem ac risus. Morbi metus. Vivamus euismod urna.</p>
                                    <!-- meta -->
                                    <span class="clearfix"></span><!-- important: clearing floated media text -->
                                    <p class="media-meta">Sat, Sep 27</p>
                                </div>
                            </li>
                            <li class="media">
                                <a href="javascript:void(0);" class="media-object">
                                    <img src="../image/avatar/avatar6.jpg" class="img-circle" alt="">
                                </a>
                                <div class="media-body">
                                    <p class="media-text">Vestibulum accumsan neque et nunc. Quisque ornare tortor at risus. Nunc ac</p>
                                    <span class="clearfix"></span>
                                    <p class="media-text">Nam porttitor scelerisque neque</p>
                                    <!-- meta -->
                                    <span class="clearfix"></span><!-- important: clearing floated media text -->
                                    <p class="media-meta">Sun, Feb 22</p>
                                </div>
                            </li>
                        </ul>
                            <!--/ END chat -->
                        </div>
                        <!--/ Content -->

                        <!-- Footer -->
                        <div class="footer">
                            <div class="has-icon">
                                <input type="text" class="form-control" placeholder="Type message...">
                                <i class="ico-paper-plane form-control-icon"></i>
                            </div>
                        </div>
                        <!--/ Footer -->
                    </div>
                    <!--/ Offcanvas Right -->
                </div>
                <!--/ END Wrapper -->
            </div>
            <!--/ END Offcanvas -->
        </aside>
        <!--/ END Template Sidebar (right) -->
