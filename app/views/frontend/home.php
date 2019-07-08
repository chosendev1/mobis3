<!DOCTYPE html>

<html class="frontend">
    <!-- START Head -->
    <head>
        <!-- START META SECTION -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Mobis</title>
        <meta name="author" content="davy" >
        <meta name="description" content="Adminre is a clean and flat backend and frontend theme build with twitter bootstrap 3.1.1">
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
               
        <link rel="stylesheet" href="resources/themes/admire/plugins/owl/css/owl.carousel.min.css">
        
        <link rel="stylesheet" href="resources/themes/admire/plugins/layerslider/css/layerslider.min.css">
        
        <!--/ Plugins stylesheet -->

        <!-- Application stylesheet : mandatory -->
        <link rel="stylesheet" href="resources/themes/admire/library/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="resources/themes/admire/stylesheet/layout1.css">
        <link rel="stylesheet" href="resources/themes/admire/stylesheet/uielement1.css">
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
                       <!-- <span class="logo-figure" style="margin-left:-4px;"></span>
                        <!-- <span class="logo-text"></span> -->
                       <!-- <span><b>Mobis</b></span>-->
                       <span><img src="resources/themes/admire/image/logo/logo.png" height="50"><span>
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
                    </ul
                    ><!--/ END Left nav -->

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
                        <li class="dropdown active">
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
            <!-- START Layerslider -->
            <section id="layerslider" style="width:100%;height:350px;">
                <!-- Slide #1 -->
                <div class="ls-slide" data-ls="transition2d:1; ">
                    <!-- slide background -->
                   <img src="resources/themes/admire/image/layerslider/bg8.png" class="ls-bg"> 
                    <!--/ slide background -->

                </div>
                <!-- Slide #1 -->

              
                <!-- Slide #2 -->
            </section>
            <!--/ END Layerslider -->

            <!-- START Call To Action Section -->
            <section class="section bgcolor-primary">
                <div class="container">
                    <div class="col-sm-9">
                        <h3 class="font-alt text-white mt0">MOBIS is a Web-Based &amp; Mobile Banking Tool for SACCOS</h3>
                    </div>
                    <div class="col-sm-3 clearfix">
                        <a href="client/createAccount" class="btn btn-outline btn-default text-white pull-right semibold">CREATE ACCOUNT</a>
                    </div>
                </div>
            </section>
            <!-- END Call To Action Section -->

            

            <!-- START Features Section -->
            <section class="section bgcolor-white">
                <div class="container">
                    <!-- START row -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="table-layout animation" data-toggle="waypoints" data-showanim="fadeInRight" data-trigger-once="true">
                                <div class="col-xs-2 valign-top"><img src="resources/themes/admire/image/icons/responsivewebdesign.png" width="100%" alt=""></div>
                                <div class="col-xs-19 pl15">
                                    <h4 class="font-alt">Register By SMS</h4>
                                    <p class="nm"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="table-layout animation" data-toggle="waypoints" data-showanim="fadeInRight" data-trigger-once="true">
                                <div class="col-xs-2 valign-top"><img src="resources/themes/admire/image/icons/seoplanning.png" width="100%" alt=""></div>
                                <div class="col-xs-10 pl15">
                                    <h4 class="font-alt">Loan Application Using Mobile</h4>
                                    <p class="nm">.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="table-layout animation" data-toggle="waypoints" data-showanim="fadeInRight" data-trigger-once="true">
                                <div class="col-xs-2 valign-top"><img src="resources/themes/admire/image/icons/branddevelopment.png" width="100%" alt=""></div>
                                <div class="col-xs-10 pl15">
                                    <h4 class="font-alt">Loan Repayment Via Mobile</h4>
                                    <p class="nm">.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ END row -->

                    <!-- START row -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="table-layout animation" data-toggle="waypoints" data-showanim="fadeInLeft" data-trigger-once="true">
                                <div class="col-xs-2 valign-top"><img src="resources/themes/admire/image/icons/affiliatemarketing.png" width="100%" alt=""></div>
                                <div class="col-xs-19 pl15">
                                    <h4 class="font-alt">Get Financial Statements</h4>
                                    <p class="nm">.</p>
                                </div>
                            </div>
                        </div>
                        
                    <!--/ END row -->
                </div>
            </section>
            <!--/ END Features Section -->

            <!-- START Partnership -->
           
            <!--/ END Partnership -->

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
				mobis@ensibuuko.com<br />
				info@ensibuuko.com<br>
                            <abbr title="Phone">Mobile:Mobile:+256 783689831,+256 70437086<br></abbr> Office line:0414694498
                        </address>
                        <h4 class="font-alt mt0">Stay Connect</h4>
                        <a href="#"> <span><img src="resources/themes/admire/image/layerslider/img/linkedin.png"><span></a>
						    <a href="#" target="_blank"> <span><img src="resources/themes/admire/image/layerslider/img/facebook.png"><span></a>
							
							 <a href="#" target="_blank"> <span><img src="resources/themes/admire/image/layerslider/img/twitter.png"><span></a>
						    <a href="#" target="_blank"> <span><img src="resources/themes/admire/image/layerslider/img/youtube.png"><span></a>
							 <a href="#"> <span><img src="resources/themes/admire/image/layerslider/img/google_plus.png"><span></a>
						    <a href="#"> <span><img src="resources/themes/admire/image/layerslider/img/skype.png"><span></a>
							
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
                                <input type="email" class="form-control" id="newsletter_email" placeholder="Enter email">
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
        
        
        <script type="text/javascript" src="resources/themes/admire/plugins/owl/js/owl.carousel.min.js"></script>
        
        <script type="text/javascript" src="resources/themes/admire/plugins/layerslider/js/greensock.min.js"></script>
        
        <script type="text/javascript" src="resources/themes/admire/plugins/layerslider/js/transitions.min.js"></script>
        
        <script type="text/javascript" src="resources/themes/admire/plugins/layerslider/js/layerslider.min.js"></script>
        
        <script type="text/javascript" src="resources/themes/admire/javascript/pages/frontend/home.js"></script>
        
        <!--/ App and page level scrip -->
        <!--/ END JAVASCRIPT SECTION -->
    </body>
    <!--/ END Body -->
</html>
