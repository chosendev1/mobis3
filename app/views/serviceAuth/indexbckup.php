<!DOCTYPE html>
<html class="backend">
    <!-- START Head -->
    <head>
        <!-- START META SECTION -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        
         <title><?=$lang['title']?></title>
        <base href="<?=base_url()?>">
        
        
         <link href="resources/themes/mobis/css/stylesheets.css" rel="stylesheet" type="text/css" />        
 
    
    
</head>
<body>

   
    
    <div class="login" id="login" align="center">
    
        <div class="wrap">
          
        
        <img src="resources/themes/mobis/img/logo1.png" style="height:60px;">
          
          
              <form class="panel" name="form-login" id="form-login" action="serviceAuth/login" method="POST">
            
                                <!-- Alert message -->
                                <div class="">
                                    <span class="semibold"></span><?php echo $msg;?>
                                </div>
                                <br>
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
                                    
                                    //if(!empty($msg))
                                    
                                ?>
                                
            
           
                <div class="input-prepend">
                    <span class="add-on"><i class="icon-user"></i></span>
                   <input name="username" type="text" class="form-control logininput" value="<?php echo !empty($username)? $username : '';?>" placeholder="Username" data-parsley-errors-container="#error-container" data-parsley-error-message="Please fill in your username" data-parsley-required>
                </div> 
                                                                
                <div class="input-prepend">
                    <span class="add-on"><i class="icon-lock"></i></span>
                   <input name="password" type="password" class="form-control logininput" placeholder="Password" data-parsley-errors-container="#error-container" data-parsley-error-message="Please fill in your password" data-parsley-required>
                </div>
                          
                                          
                     
            <div class="row-fluid">
                <div class="remember">                    
                    <input type="hidden" name="rem"/> <!--Remember me -->
                     </div>
                     
                </div>
               
                    
                   <button  type="submit" class="btn btn-block btn-primary" type="button">Sign in</button>                 
               
                </form>

            <div class="dr"><span></span></div>
            <div class="row-fluid"><div class="span12">
             
                    <button class="btn btn-warning btn-block"  onClick="loginBlock('#sign');">Forgot Password</button>
                </div>
                <br><br>
            <div class="dr"><span></span></div>

                <div class="span12">    
                  Don,t have an Account?                 
                 
                       <a href="client/createAccount" class="btn btn-block">CREATE ACCOUNT</a>
                  
                </div>
               
            </div>            
       <br>
          Powered by<a href="http://ensibuuko.com/" target="_blank" > Ensibuuko Tech ltd</a>
    </div>
    
   
    </div> 
        
        
        
        

                     
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

         <style type="text/css">
body { 
  /*background: url(resources/themes/mobis/img/backgrounds/bridge.jpg) no-repeat center center fixed; */
  background-color: #e8e8e8;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}
.logininput{
    width: 90% !important;
}

  </style>
</body></html>

