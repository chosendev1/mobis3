
        <title><?=PROJECT_TITLE?></title>
        <meta name="description" content="">

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <link rel="stylesheet" href="<?=THEMEPATH?>styles/bootstrap.css">


        <!-- Proton CSS: -->
        <link rel="stylesheet" href="<?=THEMEPATH?>styles/proton.css">
        <link rel="stylesheet" href="<?=THEMEPATH?>styles/vendor/animate.css">

        <!-- adds CSS media query support to IE8   -->
        <!--[if lt IE 9]>
            <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.2/html5shiv.js"></script>
            <script src="scripts/vendor/respond.min.js"></script>
        <![endif]-->

        <!-- Fonts CSS: -->
        <link rel="stylesheet" href="<?=THEMEPATH?>styles/font-awesome.css" type="text/css" />
        <link rel="stylesheet" href="<?=THEMEPATH?>styles/font-titillium.css" type="text/css" />

        <!-- Common Scripts: -->
       <!-- <script>
        (function () {
          var js;
          if (typeof JSON !== 'undefined' && 'querySelector' in document && 'addEventListener' in window) {
            js = '//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js';
          } else {
            js = '//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js';
          }
          document.write('<script src="' + js + '"><\/script>');
        }());
        </script> -->
         <script src="<?=THEMEPATH?>scripts/jquery.js"></script> 
        <script src="<?=THEMEPATH?>scripts/vendor/modernizr.js"></script>
        <script src="<?=THEMEPATH?>scripts/vendor/jquery.cookie.js"></script>
    </head>

    <body class="error-page">
        <!--[if lt IE 8]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
<section class="wrapper scrollable">
            <div class="panel panel-default panel-block panel-error-block">
                <div class="panel-heading">
                    <div>
                        <i class="icon-frown"></i>
                        <h1>
                            <small>
                            Sorry, there's been an error. <br />
                            <?php
//require_once("app/views/header.php");
echo($this->msg);
?>
                            </small>
                            Page Not Found
                            <i class="icon-info-sign uses-tooltip" data-toggle="tooltip" data-placement="top" data-original-title="The requested resource could not be found but may be available again in the future."></i>
                        </h1>
                        <div class="error-code">
                            404
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <a href="<?=URL?>"><i class="icon-home"></i></a>
                    <div class="input-group">
                        <input type="text" class="form-control">
                        <div class="input-group-btn">
                            <button class="btn btn-default btn-search" type="button"><i class="icon-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    
        <script src="<?=THEMEPATH?>scripts/bootstrap.min.js"></script>

		<!-- Proton base scripts: -->
        
        <script src="<?=THEMEPATH?>scripts/main.js"></script>
		<script src="<?=THEMEPATH?>scripts/proton/common.js"></script>
		<script src="<?=THEMEPATH?>scripts/proton/main-nav.js"></script>
		<script src="<?=THEMEPATH?>scripts/proton/user-nav.js"></script>
		
	
    </body>
</html>