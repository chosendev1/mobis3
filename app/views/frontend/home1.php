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
                       <!-- <span class="logo-figure" style="margin-left:-4px;"></span>
                        <!-- <span class="logo-text"></span> -->
                       <!-- <span><b>Mobis</b></span>-->
                       <span><img src="resources/themes/admire/image/logo/mobislogo.png"><span>
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
            <section id="layerslider" style="width:100%;height:480px;">
                <!-- Slide #1 -->
                <div class="ls-slide" data-ls="transition2d:1; slidedelay:8000;">
                    <!-- slide background -->
                    <img src="resources/themes/admire/image/layerslider/bg8.png" class="ls-bg">
                    <!--/ slide background -->

                    <!-- Layer #1 -->
                    <img class="ls-l" style="top:90px;left:68%;" src="resources/themes/admire/image/layerslider/layer/man3.png" data-ls="delayin:1000; easingin:easeOutElastic;">
                    <!--/ Layer #1 -->

                    <!-- Layer #2 -->
                    <h1 class="ls-l font-alt" style="top:110px;left:150px;" data-ls="offsetxin:0;durationin:2000;delayin:1500;easingin:easeOutElastic;rotatexin:-90;transformoriginin:50% top 0;offsetxout:-200;durationout:1000;">
                        Welcome To <span class="text-primary">Mobis</span> 
                    </h1>
                    <!--/ Layer #2 -->

                    <!-- Layer #3 -->
                    <h4 class="ls-l font-alt thin text-default" style="top:170px;left:150px;width:550px;" data-ls="offsetxin:0; durationin:2000; delayin:2000; easingin:easeOutElastic; rotatexin:90; transformoriginin:50% top 0; offsetxout:-400;">
                        MOBIS is a core web-based and mobile banking software customised to enable Savings 
                        and Credit Cooperatives (SACCOS) and rural financial service providers to efficiently 
                        manage financial information and reporting.
                    </h4>
                    <!--/ Layer #3 -->

                    <!-- Layer #4 -->
                    <p class="ls-l text-default" style="top:280px;left:150px;width:550px;" data-ls="offsetxin:0; durationin:2000; delayin:2500; easingin:easeOutElastic; rotatexin:90; transformoriginin:50% top 0; offsetxout:-400;">
                        MOBIS uses mobile tools such as SMS and 
                        mobile money with an in built e-wallet to bring financial services closer to un/underserved 
                        communities. You may Download and install MOBIS on your desktop or Sign-up to access online.
                    </p>
                    <!--/ Layer #4 -->

                    <!-- Layer #5 -->
                    <a href="javascript:void(0);" onclick="alert('coming soon!')" class="ls-l btn btn-primary" style="top:360px; left:150px;" data-ls="offsetxin:0; durationin:2000; delayin:3000; easingin:easeOutElastic; rotatexin:90; transformoriginin:50% top 0; offsetxout:-400;">
                       View Demo<i class="ico-angle-right ml5"></i>
                    </a>
                    <!--/ Layer #5 -->

                    <!-- Layer #6 -->
                    <img class="ls-l" style="top:370px;left:280px;" src="resources/themes/admire/image/layerslider/layer/arrow.png" data-ls="delayin:3500; offsetxin:0; offsetyin:-30; easingin:easeOutElastic;">
                    <!--/ Layer #6 -->
                </div>
                <!-- Slide #1 -->

                <!-- Slide #2 -->
                <div class="ls-slide" data-ls="transition2d:1; slidedelay:8000;">
                    <!-- slide background -->
                    <img src="resources/themes/admire/image/layerslider/bg2.png" class="ls-bg" alt="Slide background">
                    <!--/ slide background -->
                    
                    <!-- Layer #1 -->
                    <h4 class="ls-l font-alt thin text-default text-right" style="top:120px;left:65%;width:550px;" data-ls="easingin:easeOutElastic; delayin:0;">
                        
                    </h4>
                    <!--/ Layer #1 -->

                    <!-- Layer #2 -->
                    <h1 class="ls-l font-alt text-right" style="top:150px;left:65%;width:550px;" data-ls="easingin:easeOutElastic; delayin:500;">
                        <span class="text-primary">Register</span> By Mobile
                    </h1>
                    <!--/ Layer #2 -->

                    <!-- Layer #3 -->
                    <p class="ls-l text-default text-right" style="top:210px;left:65%;width:550px;" data-ls="easingin:easeOutElastic; delayin:1000;">
                        
                    </p>
                    <!--/ Layer #3 -->

                    <!-- Layer #5 -->
                    <p class="ls-l text-default text-right" style="top:290px;left:65%;width:550px;" data-ls="easingin:easeOutElastic; delayin:1500;">
                        <a href="javascript:void(0);" class="btn btn-primary">
                            View Demo<i class="ico-angle-right ml5"></i>
                        </a>
                    </p>
                    <!--/ Layer #5 -->

                    <!-- Layer #6 -->
                    <img class="ls-l" style="top:20px;left:250px;" src="resources/themes/admire/image/layerslider/layer/ewallet2.png" data-ls="delayin:2000; easingin:easeOutElastic;">
                    <!--/ Layer #6 -->
                </div>
                <!-- Slide #2 -->
                
                 <!-- Slide #1 -->
                <div class="ls-slide" data-ls="transition2d:1; slidedelay:8000;">
                    <!-- slide background -->
                    <img src="resources/themes/admire/image/layerslider/bg3.png" class="ls-bg">
                    <!--/ slide background -->

                    <!-- Layer #1 -->
                    <img class="ls-l" style="top:90px;left:68%;" src="resources/themes/admire/image/layerslider/layer/3.jpg" data-ls="delayin:1000; easingin:easeOutElastic;">
                    <!--/ Layer #1 -->
                    
                    <!-- Layer #2 -->
                    <h1 class="ls-l font-alt" style="top:110px;left:150px;" data-ls="offsetxin:0;durationin:2000;delayin:1500;easingin:easeOutElastic;rotatexin:-90;transformoriginin:50% top 0;offsetxout:-200;durationout:1000;">
                        Apply for&nbsp;<span class="text-primary">Loans</span>&nbsp;Using Your <br> Mobile Device
                    </h1>
                    <!--/ Layer #2 -->

                    <!-- Layer #3 -->
                    <h4 class="ls-l font-alt thin text-default" style="top:170px;left:150px;width:550px;" data-ls="offsetxin:0; durationin:2000; delayin:2000; easingin:easeOutElastic; rotatexin:90; transformoriginin:50% top 0; offsetxout:-400;">
                       
                    </h4>
                    <!--/ Layer #3 -->

                    <!-- Layer #4 -->
                    <p class="ls-l text-default" style="top:230px;left:150px;width:550px;" data-ls="offsetxin:0; durationin:2000; delayin:2500; easingin:easeOutElastic; rotatexin:90; transformoriginin:50% top 0; offsetxout:-400;">
                       
                    </p>
                    <!--/ Layer #4 -->

                    <!-- Layer #5 -->
                    <a href="javascript:void(0);" class="ls-l btn btn-primary" style="top:310px; left:150px;" data-ls="offsetxin:0; durationin:2000; delayin:3000; easingin:easeOutElastic; rotatexin:90; transformoriginin:50% top 0; offsetxout:-400;">
                       View Demo<i class="ico-angle-right ml5"></i>
                    </a>
                    <!--/ Layer #5 -->

                    <!-- Layer #6 -->
                    <img class="ls-l" style="top:320px;left:280px;" src="resources/themes/admire/image/layerslider/layer/arrow.png" data-ls="delayin:3500; offsetxin:0; offsetyin:-30; easingin:easeOutElastic;">
                    <!--/ Layer #6 -->
                </div>
                <!-- Slide #1 -->

                <!-- Slide #2 -->
                <div class="ls-slide" data-ls="transition2d:1; slidedelay:8000;">
                    <!-- slide background -->
                    <img src="resources/themes/admire/image/layerslider/bg3.png" class="ls-bg" alt="Slide background">
                    <!--/ slide background -->
                    
                    <!-- Layer #1 -->
                    <h4 class="ls-l font-alt thin text-default text-right" style="top:120px;left:65%;width:550px;" data-ls="easingin:easeOutElastic; delayin:0;">
                        
                    </h4>
                    <!--/ Layer #1 -->

                    <!-- Layer #2 -->
                    <h1 class="ls-l font-alt text-right" style="top:150px;left:65%;width:550px;" data-ls="easingin:easeOutElastic; delayin:500;">
                        Loan&nbsp;<span class="text-primary">Repayment</span>&nbsp;Via Mobile
                    </h1>
                    <!--/ Layer #2 -->

                    <!-- Layer #3 -->
                    <p class="ls-l text-default text-right" style="top:210px;left:65%;width:550px;" data-ls="easingin:easeOutElastic; delayin:1000;">
                       
                    </p>
                    <!--/ Layer #3 -->

                    <!-- Layer #5 -->
                    <p class="ls-l text-default text-right" style="top:290px;left:65%;width:550px;" data-ls="easingin:easeOutElastic; delayin:1500;">
                        <a href="javascript:void(0);" class="btn btn-primary">
                            View Demo<i class="ico-angle-right ml5"></i>
                        </a>
                    </p>
                    <!--/ Layer #5 -->

                    <!-- Layer #6 -->
                    <img class="ls-l" style="top:20px;left:250px;" src="resources/themes/admire/image/layerslider/layer/transfer2.png" data-ls="delayin:2000; easingin:easeOutElastic;">
                    <!--/ Layer #6 -->
                </div>
                <!-- Slide #2 -->
                
                <!-- Slide #2 -->
                <div class="ls-slide" data-ls="transition2d:1; slidedelay:8000;">
                    <!-- slide background -->
                    <img src="resources/themes/admire/image/layerslider/bg2.png" class="ls-bg" alt="Slide background">
                    <!--/ slide background -->
                    
                    <!-- Layer #1 -->
                    <h4 class="ls-l font-alt thin text-default text-right" style="top:120px;left:65%;width:550px;" data-ls="easingin:easeOutElastic; delayin:0;">
                       
                    </h4>
                    <!--/ Layer #1 -->

                    <!-- Layer #2 -->
                    <h1 class="ls-l font-alt text-right" style="top:150px;left:65%;width:550px;" data-ls="easingin:easeOutElastic; delayin:500;">
                        Get&nbsp;<span class="text-primary">Financial Statements</span>
                    </h1>
                    <!--/ Layer #2 -->

                    <!-- Layer #3 -->
                    <p class="ls-l text-default text-right" style="top:210px;left:65%;width:550px;" data-ls="easingin:easeOutElastic; delayin:1000;">
                      
                    </p>
                    <!--/ Layer #3 -->

                    <!-- Layer #5 -->
                    <p class="ls-l text-default text-right" style="top:290px;left:65%;width:550px;" data-ls="easingin:easeOutElastic; delayin:1500;">
                        <a href="javascript:void(0);" class="btn btn-primary">
                            View Demo<i class="ico-angle-right ml5"></i>
                        </a>
                    </p>
                    <!--/ Layer #5 -->

                    <!-- Layer #6 -->
                    <img class="ls-l" style="top:20px;left:250px;" src="resources/themes/admire/image/layerslider/layer/pn2.png" data-ls="delayin:2000; easingin:easeOutElastic;">
                    <!--/ Layer #6 -->
                </div>
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