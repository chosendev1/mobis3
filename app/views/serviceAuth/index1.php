<!DOCTYPE html>
<html class="backend">
    <!-- START Head -->
    <head>
        <!-- START META SECTION -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?=$lang['title']?></title>
        <meta name="author" content="pampersdry.info">
        <meta name="description" content="Adminre is a clean and flat backend and frontend theme build with twitter bootstrap 3.1.1">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

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
        <link rel="stylesheet" href="resources/themes/admire/stylesheet/layout.min.css">
        <link rel="stylesheet" href="resources/themes/admire/stylesheet/uielement.min.css">
        <!--/ Application stylesheet -->
        <!-- END STYLESHEETS -->

        <!-- START JAVASCRIPT SECTION - Load only modernizr script here -->
        <script src="resources/themes/admire/library/modernizr/js/modernizr.min.js"></script>
        <!--/ END JAVASCRIPT SECTION -->
    </head>
    <!--/ END Head -->

    <!-- START Body -->
    <body>
        <!-- START Template Main -->
        <section id="main" role="main">
            <!-- START Template Container -->
            <section class="container">
                <!-- START row -->
                <div class="row">
                    <div class="col-lg-4 col-lg-offset-4">
                        <!-- Brand -->
                        <div class="text-center" style="margin-bottom:40px;">
                            <!--<span class="logo-figure inverse"></span>
                            <span class="logo-text inverse"></span>-->
                             <span><img src="resources/themes/admire/image/logo/mobislogo.png"><span>
				<br>
                           <p> <h5 class="semibold text-muted mt-5"><b>Login in</b></h5></p>
                        </div>
                        <!--/ Brand -->

                        <!-- Social button -->
                      <!--  <ul class="list-table">
                            <li><button type="button" class="btn btn-block btn-facebook">Connect with <i class="ico-facebook2 ml5"></i></button></li>
                            <li><button type="button" class="btn btn-block btn-twitter">Connect with <i class="ico-twitter2 ml5"></i></button></li>
                        </ul> -->
                        <!-- Social button -->

                        <hr><!-- horizontal line -->

                        <!-- Login form -->
                        <form class="panel" name="form-login" id="form-login" action="serviceAuth/login" method="POST">
                            <div class="panel-body">
                                <!-- Alert message -->
                                <div class="alert alert-warning">
                                    <span class="semibold">Note :</span>&nbsp;&nbsp;Enter your credentials and hit 'sign-in'.
                                </div>
                                <?
                                	if(isset($_GET['fail'])){
                                		$fail = $_GET['fail'];
                                		if($fail == 2){
                                		echo '
                                			<div class="alert alert-danger">
                                    <span class="semibold">oh snap!</span>&nbsp;&nbsp;Unauthorised Access! This account is inactive, please contact administrator.
                                </div>
                                		';
                                		}
                                		else{
                                			echo '
                                			<div class="alert alert-danger">
                                    <span class="semibold">oh snap!</span>&nbsp;&nbsp;Invalid Login!
                                </div>
                                		';
                                		}
                                	}
                                ?>
                                <!--/ Alert message -->
                                <div class="form-group">
                                    <select class="form-control" name="lang">
                                        <option value="0">Select language</option>
                                        <option value="en">English</option>
                                       
                                    </select>
                                </div>
                                <div class="form-group">
                                    <div class="form-stack has-icon pull-left">
                                        <input name="username" type="text" class="form-control input-lg" placeholder="Username" data-parsley-errors-container="#error-container" data-parsley-error-message="Please fill in your username" data-parsley-required>
                                        <i class="ico-user2 form-control-icon"></i>
                                    </div>
                                    <div class="form-stack has-icon pull-left">
                                        <input name="password" type="password" class="form-control input-lg" placeholder="Password" data-parsley-errors-container="#error-container" data-parsley-error-message="Please fill in your password" data-parsley-required>
                                        <i class="ico-lock2 form-control-icon"></i>
                                    </div>
                                </div>

                                <!-- Error container -->
                                <div id="error-container"class="mb15"></div>
                                <!--/ Error container -->

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div class="checkbox custom-checkbox">  
                                                <input type="checkbox" name="remember" id="remember" value="1">  
                                                <label for="remember">&nbsp;&nbsp;Remember me</label>   
                                            </div>
                                        </div>
                                        <div class="col-xs-6 text-right">
                                            <a href="javascript:void(0);">Lost password?</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group nm">
                                  <!--  <button type="submit" class="btn btn-block btn-success"><span class="semibold">Sign In</span></button> -->
                                    <input type="submit" class="btn btn-block btn-success" value="Sign In" />
                                </div>
                            </div>
                        </form>
                        <!-- Login form -->

                        <hr><!-- horizontal line -->

                        <p class="text-muted text-center">Don't have any account? <a class="semibold" href="./">Go Home</a></p>
                    </div>
                </div>
                <!--/ END row -->
            </section>
            <!--/ END Template Container -->
        </section>
        <!--/ END Template Main -->

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
        
        <script type="text/javascript" src="resources/themes/admire/javascript/pages/login.js"></script>
        
        <!--/ App and page level script -->
        <!--/ END JAVASCRIPT SECTION -->
    </body>
    <!--/ END Body -->
</html>
