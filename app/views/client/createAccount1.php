<!DOCTYPE html>

<html class="frontend">
    <!-- START Head -->
    <head>
        <!-- START META SECTION -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Mobis</title>
        <meta name="author" content="craneapps.com">
       
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	 <base href="<?=base_url()?>">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="resources/themes/admire/image/touch/apple-touch-icon-144x144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="resources/themes/admire/image/touch/apple-touch-icon-114x114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="resources/themes/admire/image/touch/apple-touch-icon-72x72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="resources/themes/admire/image/touch/apple-touch-icon-57x57-precomposed.png">
        <link rel="shortcut icon" href="resources/themes/admire/image/favicon.ico">
        <!--/ END META SECTION -->

        <!-- START STYLESHEETS -->
        <!-- Plugins stylesheet : optional -->
        
        
        <!--/ Plugins stylesheet -->

        <!-- Application stylesheet : mandatory -->
        <link rel="stylesheet" href="resources/themes/admire/library/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="resources/themes/admire/stylesheet/layout.css">
        <link rel="stylesheet" href="resources/themes/admire/stylesheet/uielement.css">
        <link rel="stylesheet" href="resources/themes/admire/plugins/jqueryui/css/jquery-ui-timepicker.min.css">
        <link rel="stylesheet" href="resources/themes/admire/plugins/jqueryui/css/jquery-ui.min.css">
        <!--/ Application stylesheet -->
        <!-- END STYLESHEETS -->

        <!-- START JAVASCRIPT SECTION - Load only modernizr script here -->
        <script src="resources/themes/admire/library/modernizr/js/modernizr.min.js"></script>
        <!--/ END JAVASCRIPT SECTION -->
    </head>
    <!--/ END Head -->

    <!-- START Body -->
    <body>
         <!-- START Template Header -->
        <header id="header" class="navbar navbar-fixed-top">
            <div class="container">
                <!-- START navbar header -->
                <div class="navbar-header">
                    <!-- Brand -->
                    <a class="navbar-brand" href="javascript:void(0);">
                        <span class="logo-figure" style="margin-left:-4px;"></span>
                        <!-- <span class="logo-text"></span> -->
                        <span><b>Mobis</b></span>
                    </a>
                    <!--/ Brand -->
                </div>
                <!--/ END navbar header -->

                <!-- START Toolbar -->
                <div class="navbar-toolbar clearfix">
                    <!-- START Left nav -->
                    <ul class="nav navbar-nav">
                        <!-- Navbar collapse: This menu will take position at the top of template header (mobile only). Make sure that only #header have the `position: relative`, or it may cause unwanted behavior -->
                        <li class="navbar-main navbar-toggle">
                            <a href="javascript:void(0);" data-toggle="collapse" data-target="#navbar-collapse">
                                <span class="meta">
                                    <span class="icon"><i class="ico-paragraph-justify3"></i></span>
                                </span>
                            </a>
                        </li>
                        <!--/ Navbar collapse -->
                    </ul>
                    <!--/ END Left nav -->

                    <!-- START navbar form -->
                    <div class="navbar-form navbar-left dropdown" id="dropdown-form">
                        <form action="" role="search">
                            <div class="has-icon">
                                <input type="text" class="form-control" placeholder="Search this site...">
                                <i class="ico-search form-control-icon"></i>
                            </div>
                        </form>
                    </div>
                    <!-- START navbar form -->

                    <!-- START Right nav -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Search form toggler -->
                        <li>
                            <a href="javascript:void(0);" data-toggle="dropdown" data-target="#dropdown-form">
                                <span class="meta">
                                    <span class="icon"><i class="ico-search"></i></span>
                                </span>
                            </a>
                        </li>
                        <!--/ Search form toggler -->
                    </ul>
                    <!--/ END Right nav -->

                    <!-- START nav collapse -->
                    <div class="collapse navbar-collapse navbar-collapse-alt" id="navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                                <a href="#" class="dropdown-toggle " data-toggle="">
                                    <span class="meta">
                                        <span class="text">HOME</span>
                                       
                                    </span>
                                </a>                                
                            </li>                                       
                           
                            
                            <li class="dropdown">
                                <a href="serviceAuth" class="dropdown-toggle " data-toggle="">
                                    <span class="meta">
                                        <span class="text">LOG IN</span>
                                       
                                    </span>
                                </a>                                
                            </li>
                            
                        </ul>
                    </div>
                    <!--/ END nav collapse -->
                </div>
                <!--/ END Toolbar -->
            </div>
        </header>
        <!--/ END Template Header -->

        <!-- START Template Main -->
        <section id="main" role="main">
            <!-- START page header -->
            <section class="page-header page-header-block nm">
                <!-- pattern -->
                <div class="pattern pattern9"></div>
                <!--/ pattern -->
                <div class="container pt15 pb15">
                    <div class="page-header-section">
                        <h4 class="title font-alt">Account Register</h4>
                    </div>
                    <div class="page-header-section">
                        <!-- Toolbar -->
                        <div class="toolbar">
                            <ol class="breadcrumb breadcrumb-transparent nm">
                                <li><a href="javascript:void(0);">Accounts</a></li>
                                <li class="active">Account Register</li>
                            </ol>
                        </div>
                        <!--/ Toolbar -->
                    </div>
                </div>
            </section>
            <!--/ END page header -->

            <!-- START Register Content -->
            <section class="section bgcolor-white">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Header -->
                            <div class="section-header section-header-bordered text-center">
                                <h2 class="section-title">
                                    <p class="font-alt nm">Create your Mobis Account</p>
                                </h2>
                            </div>
                            <!--/ Header -->
                        </div>

                        <div class="col-md-6">
                            <!-- features #1 -->
                            <div class="table-layout">
                               <div class="table-layout animation" data-toggle="waypoints" data-showanim="fadeInRight" data-trigger-once="true">
                                <div class="col-xs-2 valign-top"><img src="resources/themes/admire/image/icons/responsivewebdesign.png" width="100%" alt=""></div>
                                <div class="col-xs-19 pl15">
                                    <h4 class="font-alt">Register By SMS</h4>
                                    <p class="nm"></p>
                                </div>
                            </div>
                            </div>
                            <!-- features #1 -->
                            <div class="visible-md visible-lg" style="margin-bottom:50px;"></div><!-- spacer -->
                            <!-- features #2 -->
                            <div class="table-layout">
                                 <div class="table-layout animation" data-toggle="waypoints" data-showanim="fadeInRight" data-trigger-once="true">
                                <div class="col-xs-2 valign-top"><img src="resources/themes/admire/image/icons/seoplanning.png" width="100%" alt=""></div>
                                <div class="col-xs-10 pl15">
                                    <h4 class="font-alt">Loan Application Using Mobile</h4>
                                    <p class="nm">.</p>
                                </div>
                            	</div>
                            </div>
                            <!-- features #2 -->
                            <div class="visible-md visible-lg" style="margin-bottom:50px;"></div><!-- spacer -->
                            <!-- features #3 -->
                            <div class="table-layout">
                                <div class="table-layout animation" data-toggle="waypoints" data-showanim="fadeInRight" data-trigger-once="true">
                                <div class="col-xs-2 valign-top"><img src="resources/themes/admire/image/icons/branddevelopment.png" width="100%" alt=""></div>
                                <div class="col-xs-10 pl15">
                                    <h4 class="font-alt">Loan Repayment Via Mobile</h4>
                                    <p class="nm">.</p>
                                </div>
                            </div>
                            </div>
                            <!-- features #3 -->
                            <div class="visible-md visible-lg" style="margin-bottom:50px;"></div><!-- spacer -->
                            <!-- features #4 -->
                            <div class="table-layout animation" data-toggle="waypoints" data-showanim="fadeInLeft" data-trigger-once="true">
                                <div class="col-xs-2 valign-top"><img src="resources/themes/admire/image/icons/affiliatemarketing.png" width="100%" alt=""></div>
                                <div class="col-xs-19 pl15">
                                    <h4 class="font-alt">Get Financial Statements</h4>
                                    <p class="nm">.</p>
                                </div>
                            </div>
                            <!-- features #4 -->
                        </div>

                        <div class="col-md-6">
                            <!-- Register form -->
                            <form class="panel nm" name="form-register" id="form-register" action="client/saveAccount" method="POST" data-parsley-validate>
                                <ul class="list-table pa15">
                                    <li>
                                        <!-- Alert message -->
                                        <div class="alert alert-warning nm">
                                            <span class="semibold">Note :</span>&nbsp;&nbsp;Please fill all the below field.
                                        </div>
                                        <!--/ Alert message -->
                                    </li>
                                    <li class="text-right" style="width:20px;"><a href="javascript:void(0);"><i class="ico-question-sign fsize16"></i></a></li>
                                </ul>
                                <hr class="nm">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label class="control-label">Organization Name</label>
                                        <div class="has-icon pull-left">
                                        <input type="text" class="form-control" name="orgName" data-parsley-required>
                                            
                                            
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Country</label>
                                        <div class="has-icon pull-left">
                                            <select class="form-control" name="country" data-parsley-required>
                                            <?
                                            	foreach(libinc::getCountry() as $key => $country){
                                            		$sel = $key == "Uganda" ? "SELECTED" : NULL;
                                            		?>
								<option value="<?=$key?>" <?=$sel?>><?=$country?></option>                                            		
                                            		<?
                                            	}
                                            ?>
                                            </select>
                                            
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="control-label">Region</label>
                                        <div class="has-icon pull-left">
                                            <select class="form-control" name="region" data-parsley-required>
                                            <option value="central">Central</option>
                                             <option value="estern">Estern</option>
                                              <option value="northern">Northern</option>
                                               <option value="southern">Southern</option>
                                                <option value="western">Western</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">City</label>
                                        <div class="has-icon pull-left">
                                            <input type="text" class="form-control" name="city" data-parsley-required>
                                           
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="control-label">Date Established</label>
                                        <div class="has-icon pull-left">
                                            <input type="text" class="form-control" name="dateEstablished" id="datepicker4">
                                           
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Postal Code</label>
                                        <div class="has-icon pull-left">
                                            <input type="text" class="form-control" name="postalCode" >
                                           
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Address 1</label>
                                        <div class="has-icon pull-left">
                                            <input type="text" class="form-control" name="address1" data-parsley-required>
                                           
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Address 2</label>
                                        <div class="has-icon pull-left">
                                            <input type="text" class="form-control" name="address2">
                                           
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="control-label">Telephone</label>
                                        <div class="has-icon pull-left">
                                            <input type="text" class="form-control" name="telephone" data-parsley-required>
                                           
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Website</label>
                                        <div class="has-icon pull-left">
                                            <input type="text" class="form-control" name="wurl">
                                           
                                        </div>
                                    </div>
                                    </div>
                                     <hr class="nm">
                                      <div class="panel-body">
                                       <p class="semibold text-muted">This will be the account you use to access the system for the first time. it'll also be the administrative account for your portal.</p>
                                    <div class="form-group">
                                        <label class="control-label">Username</label>
                                        <div class="has-icon pull-left">
                                            <input type="text" class="form-control" name="username" data-parsley-required>
                                            <i class="ico-user2 form-control-icon"></i>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Password</label>
                                        <div class="has-icon pull-left">
                                            <input type="password" class="form-control" name="password" data-parsley-required>
                                            <i class="ico-key2 form-control-icon"></i>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Retype Password</label>
                                        <div class="has-icon pull-left">
                                            <input type="password" class="form-control" name="retype-password" data-parsley-equalto="input[name=password]">
                                            <i class="ico-asterisk form-control-icon"></i>
                                        </div>
                                    </div>
                                </div>
                                <hr class="nm">
                                <div class="panel-body">
                                    <p class="semibold text-muted">To confirm and activate your new account, we will need to send the activation code to your e-mail.</p>
                                    <div class="form-group">
                                        <label class="control-label">Email</label>
                                        <div class="has-icon pull-left">
                                            <input type="email" class="form-control" name="email" placeholder="you@mail.com" data-parsley-required>
                                            <i class="ico-envelop form-control-icon"></i>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="checkbox custom-checkbox">  
                                            <input type="checkbox" name="agree" id="agree" value="1" data-parsley-required>  
                                            <label for="agree">&nbsp;&nbsp;I agree to the <a class="semibold" href="javascript:void(0);">Term Of Services</a></label>   
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="checkbox custom-checkbox">  
                                            <input type="checkbox" name="news" id="news" value="1">  
                                            <label for="news">&nbsp;&nbsp;Send me Newsletters.</label>   
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <button type="submit" class="btn btn-block btn-success"><span class="semibold">Sign up</span></button>
                                </div>
                            </form>
                            <!-- Register form -->
                        </div>
                    </div>
                </div>
            </section>
            <!--/ END Register Content -->

            <!-- START To Top Scroller -->
            <a href="#" class="totop animation" data-toggle="waypoints totop" data-showanim="bounceIn" data-hideanim="bounceOut" data-offset="50%"><i class="ico-angle-up"></i></a>
            <!--/ END To Top Scroller -->
        </section>
        <!--/ END Template Main -->

        <!-- START Template Footer -->
        <footer id="footer" role="contentinfo">
            <!-- container -->
            <div class="container">
                <!-- row -->
                <div class="row">
                    <!-- About -->
                    <div class="col-md-4">
                        <h4 class="font-alt mt0">About Mobis</h4>
                        <p>MOBIS is a core web-based and mobile banking software customised to enable Savings 
                        and Credit Cooperatives (SACCOS) and rural financial service providers to efficiently 
                        manage financial information and reporting.</p>
                        <p>MOBIS uses mobile tools such as SMS and 
                        mobile money with an in built e-wallet to bring financial services closer to un/underserved 
                        communities. You may Download and install MOBIS on your desktop or Sign-up to access online.</p>

                        <a href="javascript:void(0);" class="text-primary">Learn More</a>
                    </div>
                    <!--/ About -->

                    <!-- Address + Social -->
                    <div class="col-md-4">
                        <h4 class="font-alt mt0">Address</h4>
                        <address>
                            <strong>Ensibuuko Ltd</strong><br>
				deensibuko@gmail.com<br />
				www.ensibuuko.com<br>
                            <abbr title="Phone">Mobile:</abbr> +256 785 045 168
                        </address>
                        <h4 class="font-alt mt0">Stay Connect</h4>
                        <a href="javascript:void(0);" class="text-muted mr15" data-toggle="tooltip" title="Facebook"><i class="ico-facebook2"></i></a>
                        <a href="javascript:void(0);" class="text-muted mr15" data-toggle="tooltip" title="Twitter"><i class="ico-twitter2"></i></a>
                        <a href="javascript:void(0);" class="text-muted mr15" data-toggle="tooltip" title="Vimeo"><i class="ico-vimeo"></i></a>
                        <a href="javascript:void(0);" class="text-muted mr15" data-toggle="tooltip" title="Flickr"><i class="ico-flickr2"></i></a>
                        <a href="javascript:void(0);" class="text-muted mr15" data-toggle="tooltip" title="Google+"><i class="ico-google-plus2"></i></a>
                        <a href="javascript:void(0);" class="text-muted mr15" data-toggle="tooltip" title="Instagram"><i class="ico-instagram2"></i></a>
                    </div>
                    <!--/ Address + Social -->

                    <!-- Newsletter -->
                    <div class="col-md-4">
                        <h4 class="font-alt mt0">News Letter</h4>
                        <form role="form">
                            <div class="form-group">
                                <p class="form-control-static">Subscribe to our newsletter and stay up to date with the latest news and deals!</p>
                            </div>
                            <div class="form-group">
                                <input type="email" name="newsletter_email" class="form-control" id="newsletter_email" placeholder="Enter email">
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Subscribe Now</button>
                        </form>
                    </div>
                    <!--/ Newsletter -->
                </div>
                <!--/ row -->
            </div>
            <!--/ container -->

            <!-- bottom footer -->
            <div class="footer-bottom">
                <!-- container -->
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            <!-- copyright -->
                            <p class="nm text-muted">Copyright 2015 &copy;<a href="javascript:void(0);" class="text-white">Ensibuuko Ltd</a>. All Rights Reserved.</p>
                            <!--/ copyright -->
                        </div>
                        <div class="col-sm-6 text-right">
                            <a href="javascript:void(0);" class="text-white">Privacy Policy</a>
                            <span class="ml5 mr5">&#8226;</span>
                            <a href="javascript:void(0);" class="text-white">Terms of Service</a>
                        </div>
                    </div>
                </div>
                <!--/ container -->
            </div>
            <!--/ bottom footer -->
        </footer>
        <!--/ END Template Footer -->

        <!-- START JAVASCRIPT SECTION (Load javascripts at bottom to reduce load time) -->
        <!-- Library script : mandatory -->
        <script type="text/javascript" src="resources/themes/admire/library/jquery/js/jquery.min.js"></script>
        <script type="text/javascript" src="resources/themes/admire/library/jquery/js/jquery-migrate.min.js"></script>
        <script type="text/javascript" src="resources/themes/admire/library/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="resources/themes/admire/library/core/js/core.min.js"></script>
        <!--/ Library script -->

        <!-- App and page level script -->
        <script type="text/javascript" src="resources/themes/admire/plugins/sparkline/js/jquery.sparkline.min.js"></script><!-- will be use globaly as a summary on sidebar menu -->
        <script type="text/javascript" src="resources/themes/admire/javascript/app.min.js"></script>
        
        <script type="text/javascript" src="resources/themes/admire/plugins/parsley/js/parsley.min.js"></script>
        
        <script type="text/javascript" src="resources/js/client.js"></script> 
        <!--/ App and page level scrip -->
        <!--/ END JAVASCRIPT SECTION -->
    </body>
    <!--/ END Body -->
</html>
