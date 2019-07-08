<!DOCTYPE html>

<html class="frontend">
    <!-- START Head -->
    <head>
        <!-- START META SECTION -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?=$lang['title']?></title>
        <base href="<?=base_url()?>">
        <meta name="author" content="craneapps.com">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="resources/themes/admire/image/touch/apple-touch-icon-144x144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="resources/themes/admire/image/touch/apple-touch-icon-114x114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="resources/themes/admire/image/touch/apple-touch-icon-72x72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="resources/themes/admire/image/touch/apple-touch-icon-57x57-precomposed.png">
        <link rel="shortcut icon" href="resources/themes/admire/image/favicon.ico">
        <!--/ END META SECTION -->

        <!-- START STYLESHEETS -->
        <!-- Plugins stylesheet : optional -->
        
        
        <link rel="stylesheet" href="resources/themes/admire/plugins/owl/css/owl.carousel.min.css">
        
        <link rel="stylesheet" href="resources/themes/admire/plugins/layerslider/css/layerslider.min.css">
        
        <!--/ Plugins stylesheet -->

        <!-- Application stylesheet : mandatory -->
        <link rel="stylesheet" href="resources/themes/admire/library/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="resources/themes/admire/stylesheet/layout.css">
        <link rel="stylesheet" href="resources/themes/admire/stylesheet/uielement.css">
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
                        <span class="logo-text"></span>
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
                                <a href="./" class="dropdown-toggle active" data-toggle="dropdown">
                                    <span class="meta">
                                        <span class="text">HOME</span>
                                     
                                    </span>
                                </a>
                               								
                            </li>
							
                            <li class="dropdown">
                                <a href="inu/institutionStructure" class="dropdown-toggle">
                                    <span class="meta">
                                        <span class="text">INSTITUTION STRUCTURE</span>
                                        
                                    </span>
                                </a>
								<!--
                                <ul class="dropdown-menu">
                                    <li><a href="page-about.html">About Us</a></li>
                                    <li><a href="page-contact.html">Contact Us</a></li>
                                    <li><a href="page-left-sidebar.html">Left Sidebar</a></li>
                                    <li><a href="page-right-sidebar.html">Right Sidebar</a></li>
                                    <li><a href="page-account-register.html">Account Register</a></li>
                                    <li><a href="page-login.html">Login</a></li>
                                </ul>
								-->
                            </li>
							
                            <li class="dropdown custom mega">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <span class="meta">
                                        <span class="text">PROGRAMS</span>
                                        <span class="caret"></span>
                                    </span>
                                </a>
                                <div class="dropdown-menu">
                                    <ul class="table-layout nm">
                                        <li class="col-sm-3 valign-top">
                                            <ul class="dropdown-menu">
                                                <li class="dropdown-header">DAY PROGRAM</li>
                                                <li><a href="#">Information & Communication technology</a></li>
                                                <li><a href="#">Electrical & Electronic Engineering</a></li>
												<li><a href="#">Civil Engineering</a></li>
                                                <li><a href="#">Mechanical Engineering</a></li>
                                                <li><a href="#">Mining Engineering</a></li>
                                           	
											</ul>
                                        </li>

                                         <li class="col-sm-3 valign-top">
                                            <ul class="dropdown-menu">
                                                <li class="dropdown-header">EVENING PROGRAM</li>
                                                <li><a href="#">Information & Communication technology</a></li>
                                                <li><a href="#">Electrical & Electronic Engineering</a></li>
												<li><a href="#">Civil Engineering</a></li>
                                                <li><a href="#">Mechanical Engineering</a></li>
                                                <li><a href="#">Mining Engineering</a></li>
                                             	
                                            </ul>
                                        </li>
                    						
                                        <li class="col-sm-6 valign-top">
                                            <ul class="dropdown-menu">
                                                <li class="dropdown-header">SHORT COURSES</li>
                                                <li><a href="#">Information & Communication technology</a></li>
                                                <li><a href="#">Civil Engineering</a></li>
                                                <li><a href="#">Mechanical Engineering</a></li>
                                              	
                                            </ul>
                                        </li>
                                        </li>
                                    </ul>
                                </div>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle">
                                    <span class="meta">
                                        <span class="text">APPLICATION</span>
                                        
                                    </span>
                                </a>
                                
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle " data-toggle="dropdown">
                                    <span class="meta">
                                        <span class="text">ADMISSION</span>
                                        <span class="caret"></span>
                                    </span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="information-single.html">Information</a></li>
                                    <li><a href="#">Admitted List</a></li>
                                    <li><a href="#">Current Student Registration</a></li>
                                    <li><a href="#">New Student Registration</a></li>
                                </ul>
                            </li>
							
                           
							<li class="dropdown">
                                <a href="javascript:;" class="dropdown-toggle">
                                    <span class="meta">
                                        <span class="text">FAQs</span>
                                        
                                    </span>
                                </a>
                                
                           </li>		
								<li class="dropdown">
                                <a href="#" class="dropdown-toggle " data-toggle="dropdown">
                                    <span class="meta">
                                        <span class="text">LOGIN</span>
										<span class="caret"></span>
                                      
                                    </span>
                                </a>	
									<ul class="dropdown-menu">
									<li><a href="serviceAuth">Students</a></li>
									<li><a href="serviceAuth">Staff only</a></li>
							</li>
														
                        </ul>
                    </div>
                    <!--/ END nav collapse -->
                </div>
                <!--/ END Toolbar -->
            </div>
        </header>
        <!--/ END Template Header -->
