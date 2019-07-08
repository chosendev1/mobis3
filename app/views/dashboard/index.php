 <div class="row">
                    <!-- START Left Side -->
                    <div class="col-md-9">
                        <!-- Top Stats -->
                        <div class="row">
                            <div class="col-sm-4">
                                <!-- START Statistic Widget -->
                                <div class="table-layout animation delay animating fadeInDown">
                                    <div class="col-xs-4 panel bgcolor-info">
                                        <div class="ico-users3 fsize24 text-center"></div>
                                    </div>
                                    <div class="col-xs-8 panel">
                                        <div class="panel-body text-center">
                                            <h4 class="semibold nm"><?=stats::numberOfStaff()?></h4>
                                            <p class="semibold text-muted mb0 mt5">REGISTERED STAFF</p>
                                        </div>
                                    </div>
                                </div>
                                <!--/ END Statistic Widget -->
                            </div>
                            <div class="col-sm-4">
                                <!-- START Statistic Widget -->
                                <div class="table-layout animation delay animating fadeInUp">
                                    <div class="col-xs-4 panel bgcolor-teal">
                                        <div class="ico-crown fsize24 text-center"></div>
                                    </div>
                                    <div class="col-xs-8 panel">
                                        <div class="panel-body text-center">
                                            <h4 class="semibold nm"><?=stats::numberOfUsers()?></h4>
                                            <p class="semibold text-muted mb0 mt5">ACTIVE USERS</p>
                                        </div>
                                    </div>
                                </div>
                                <!--/ END Statistic Widget -->
                            </div>
                            <div class="col-sm-4">
                                <!-- START Statistic Widget -->
                                <div class="table-layout animation delay animating fadeInDown">
                                    <div class="col-xs-4 panel bgcolor-primary">
                                        <div class="ico-box-add fsize24 text-center"></div>
                                    </div>
                                    <div class="col-xs-8 panel">
                                        <div class="panel-body text-center">
                                            <h4 class="semibold nm">0</h4>
                                            <p class="semibold text-muted mb0 mt5">TOTAL Accounts</p>
                                        </div>
                                    </div>
                                </div>
                                <!--/ END Statistic Widget -->
                            </div>
                        </div>   </div>
                        <!--/ Top Stats -->
                        <?php
                        /*	if(is_array($orgs))
			 		echo tables::basic("Organization Structure",$orgs->result()) */?>
                        <!-- Website States -->
                   <!--     <div class="row">
                            <div class="col-sm-12">
                                <!-- START panel -->
                     <!--            <div class="panel mt10">
                                    <!-- panel-toolbar -->
                  <!--                   <div class="panel-heading pt10">
                                        <div class="panel-toolbar">
                                            <h5 class="semibold nm ellipsis">Performance Graph Per Organization</h5>
                                        </div>
                                        <div class="panel-toolbar text-right">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-default">Duration</button>
                                                <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li class="dropdown-header">Select duration :</li>
                                                    <li><a href="#">Year</a></li>
                                                    <li><a href="#">Month</a></li>
                                                    <li><a href="#">Week</a></li>
                                                    <li><a href="#">Day</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/ panel-toolbar -->
                                    <!-- panel-body -->
                        <!--             <div class="panel-body pt0">
                                        <div class="chart mt10" id="chart-audience" style="height:250px;"></div>
                                    </div>
                                    <!--/ panel-body -->
                                    <!-- panel-footer -->
                         <!--            <div class="panel-footer hidden-xs">
                                        <ul class="nav nav-section nav-justified">
                                            <li>
                                                <div class="section">
                                                    <h4 class="bold text-default mt0 mb5" data-toggle="counterup">24,548</h4>
                                                    <p class="nm text-muted">
                                                        <span class="semibold">Visits</span>
                                                        <span class="text-muted mr5 ml5">•</span>
                                                        <span class="text-danger"><i class="ico-arrow-down4"></i> 32%</span>
                                                    </p>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="section">
                                                    <h4 class="bold text-default mt0 mb5" data-toggle="counterup">175,132</h4>
                                                    <p class="nm text-muted">
                                                        <span class="semibold">Page Views</span>
                                                        <span class="text-muted mr5 ml5">•</span>
                                                        <span class="text-success"><i class="ico-arrow-up4"></i> 15%</span>
                                                    </p>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="section">
                                                    <h4 class="bold text-default mt0 mb5"><span data-toggle="counterup">89.96</span>%</h4>
                                                    <p class="nm text-muted">
                                                        <span class="semibold">Bounce Rate</span>
                                                        <span class="text-muted mr5 ml5">•</span>
                                                        <span class="text-success"><i class="ico-arrow-up4"></i> 3%</span>
                                                    </p>
                                                </div>
                                            </li>
                                        </ul>
                                    </div> -->
                                    <!--/ panel-footer -->
                               <!-- </div>
                                <!--/ END panel -->
                          <!--  </div>
                        </div>
                        <!--/ Website States -->
			
                        <!-- Browser Breakpoint -->
                        <div class="row">
                            <div class="col-lg-12">
                             
                                <!-- START panel -->
                                <div class="panel panel-default">
                                    <!-- panel body with collapse capabale -->
                                    <div class="table-responsive panel-collapse pull out">
                                       <?=tables::basic("CLIENTS",$clients->result(),array("companyName","country","city","email","telephone","status"));?>
                                    </div>
                                    <!--/ panel body with collapse capabale -->
                                </div>
                                <!--/ END panel -->
                            </div>
                        </div>
                        <!-- Browser Breakpoint -->
                    </div>
                    <!--/ END Left Side -->
