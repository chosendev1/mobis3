<!DOCTYPE html>
<html>
    <!-- START Head -->
    <head>
        <!-- START META SECTION -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?=$lang['title']?></title>
<meta name="description" content="Inu documentation">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
<base href="<?=base_url()?>">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="resources/themes/admire/image/touch/apple-touch-icon-144x144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="resources/themes/admire/image/touch/apple-touch-icon-114x114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="resources/themes/admire/image/touch/apple-touch-icon-72x72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="resources/themes/admire/image/touch/apple-touch-icon-57x57-precomposed.png">
<link rel="shortcut icon" href="resources/themes/admire/image/favicon.ico">
<!--/ END META SECTION -->

<!-- START STYLESHEETS -->
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
    <body style="background-color:#fefefe">
        <!-- START Template Sidebar (Left) -->
        <aside class="sidebar sidebar-left sidebar-menu" style="top:0px;">
            <!-- START Sidebar Content -->
            <section class="content slimscroll">
                <h5 class="heading">Main Menu</h5>
                <!-- START Template Navigation/Menu -->
                <ul id="nav" class="topmenu">
    <li>
        <a href="help#about">
            <span class="figure"><i class="ico-angle-right"></i></span>
            <span class="text">About</span>
        </a>
    </li>
        <li>
        <a href="help#folder-structure">
            <span class="figure"><i class="ico-angle-right"></i></span>
            <span class="text">Folder structure</span>
        </a>
    </li>
    <li>
        <a href="help#basic-template-backend">
            <span class="figure"><i class="ico-angle-right"></i></span>
            <span class="text">Basic Template - Backend</span>
        </a>
    </li>
    <li>
        <a href="help#basic-template-frontend">
            <span class="figure"><i class="ico-angle-right"></i></span>
            <span class="text">Basic Template - Frontend</span>
        </a>
    </li>
    <li>
        <a href="help#stylesheet">
            <span class="figure"><i class="ico-angle-right"></i></span>
            <span class="text">Stylesheet</span>
        </a>
    </li>
    <li>
        <a href="help#javascript">
            <span class="figure"><i class="ico-angle-right"></i></span>
            <span class="text">Javascript</span>
        </a>
    </li>
    <li>
        <a href="help#layout-option">
            <span class="figure"><i class="ico-angle-right"></i></span>
            <span class="text">Layout option</span>
        </a>
    </li>
    <li>
        <a href="help#credits">
            <span class="figure"><i class="ico-angle-right"></i></span>
            <span class="text">Credits</span>
        </a>
    </li>
    <li>
        <a href="help#changelog">
            <span class="figure"><i class="ico-angle-right"></i></span>
            <span class="text">Changelog</span>
        </a>
    </li>
</ul>
                <!--/ END Template Navigation/Menu -->
            </section>
            <!--/ END Sidebar Container -->
        </aside>
        <!--/ END Template Sidebar (Left) -->

        <!-- START Template Main -->
        <section id="main" role="main">
            <!-- START Template Container -->
            <section class="container-fluid">
                <!-- Page title -->
                <div class="page-header page-header-block">
                    <div class="page-header-section">
                        <h4 class="title semibold">Introduction</h4>
                    </div>
                </div>
                <!--/ Page title -->

                <section id="about">
    <!-- section header -->
    <div class="section-header">
        <h4 class="semibold title mb10">Inu mis Help Desk</h4>
    </div>
    <!--/ section header -->
    <div class="row">
        <div class="col-lg-12">
            <p>Inu is yet another admin template built using Bootstrap and other jQuery plugins that is perfect for your next projects. It provides an easy to use user interface design and a fully responsive layout that is compatible with handheld devices such as phones and tablets.</p>

            <p>Inu is built to work best in the latest desktop and mobile browsers but older browsers might display differently styled, though fully functional, renderings of certain components.</p>

            <p class="alert alert-info"><strong>Note:</strong> Internet Explorer 8 and below are not supported in this template.</p>
        </div>
    </div>

    <!-- section header -->
    <div class="section-header">
        <h5 class="semibold title mb10">Thanks For Your Purchase</h5>
    </div>
    <!--/ section header -->
    <div class="row">
        <div class="col-lg-12">
            <p>Thank you for purchasing my theme. Your support will help me to continue this template development and improvement. I'm please to hear any suggestion or feature request from you :)</p>
        </div>
    </div>

    <!-- section header -->
    <div class="section-header">
        <h5 class="semibold title mb10">Important Note!</h5>
    </div>
    <!--/ section header -->
    <div class="row">
        <div class="col-lg-12">
            <p>This is the version 1.2.0 release of this template and it documentation. For the moment, you can refer this template sample page to get started. This documentation may not be helpful enough at this time, but it will be update from time to time.</p>
            <p>Do email me at <a href="mailto:namnoah@gmail.com">namnoah@gmail.com</a> if you have anything to ask about this template. I'll do my best to ease and help you :). But don't forget to state the template name, because this is my second template on ThemeForest.</p>
        </div>
    </div>
</section>

<hr><!-- horizontal line -->

<section id="folder-structure">
    <!-- section header -->
    <div class="section-header">
        <h4 class="semibold title mb10">Folder structure</h4>
    </div>
    <!--/ section header -->
    <div class="row">
        <div class="col-lg-12">
        <p>You will find the below folder structure inside the downloaded file.</p>
<!-- Code example -->
<pre>
<code class="bash">root/
├── image/
├── javascript/
├── library/
├── plugins/
├── server/
├── stylesheets/
├── gh_frontend/
│   └── frontend file
└── gh_backend/
    └── backend file</code>
    
        
</pre>
<!--/ Code example -->
        </div>
    </div>
</section>

<hr><!-- horizontal line -->

<section id="basic-template-backend">
    <!-- section header -->
    <div class="section-header">
        <h4 class="semibold title mt10">Basic template - Backend</h4>
    </div>
    <!--/ section header -->
    <div class="row">
        <div class="col-lg-12">
            <p>Copy the HTML below to begin working with a minimal document or you can refer to <code>html page</code></p>
<!-- Code example -->
<pre class="prettyprint linenums">
&lt;!DOCTYPE html&gt;
&lt;html class=&quot;backend&quot;&gt;
&lt;!-- START Head --&gt;
&lt;head&gt;
    &lt;!-- START META SECTION --&gt;
    &lt;meta charset=&quot;utf-8&quot;&gt;
    &lt;meta http-equiv=&quot;X-UA-Compatible&quot; content=&quot;IE=edge&quot;&gt;
    &lt;title&gt;Inu - 1.0.0&lt;/title&gt;
    &lt;meta name=&quot;description&quot; content=&quot;Inu admin dashboard&quot;&gt;
    &lt;meta name=&quot;viewport&quot; content=&quot;width=device-width, user-scalable=no, initial-scale=1.0&quot;&gt;

    &lt;link rel=&quot;apple-touch-icon-precomposed&quot; sizes=&quot;144x144&quot; href=&quot;image/touch/apple-touch-icon-144x144-precomposed.png&quot;&gt;
    &lt;link rel=&quot;apple-touch-icon-precomposed&quot; sizes=&quot;114x114&quot; href=&quot;image/touch/apple-touch-icon-114x114-precomposed.png&quot;&gt;
    &lt;link rel=&quot;apple-touch-icon-precomposed&quot; sizes=&quot;72x72&quot; href=&quot;image/touch/apple-touch-icon-72x72-precomposed.png&quot;&gt;
    &lt;link rel=&quot;apple-touch-icon-precomposed&quot; href=&quot;image/touch/apple-touch-icon-57x57-precomposed.png&quot;&gt;
    &lt;link rel=&quot;shortcut icon&quot; href=&quot;image/touch/apple-touch-icon.png&quot;&gt;
    &lt;!--/ END META SECTION --&gt;

    &lt;!-- START STYLESHEETS --&gt;
    &lt;!-- Plugins stylesheet : optional --&gt;
    ...
    &lt;!--/ Plugins stylesheet --&gt;

    &lt;!-- Application stylesheet : mandatory --&gt;
    &lt;link rel=&quot;stylesheet&quot; href=&quot;library/bootstrap/css/bootstrap.min.css&quot;&gt;
    &lt;link rel=&quot;stylesheet&quot; href=&quot;stylesheet/layout.min.css&quot;&gt;
    &lt;link rel=&quot;stylesheet&quot; href=&quot;stylesheet/uielement.min.css&quot;&gt;
    &lt;!--/ Application stylesheet --&gt;
    &lt;!-- END STYLESHEETS --&gt;

    &lt;!-- START JAVASCRIPT SECTION - Load only modernizr script here --&gt;
    &lt;script src=&quot;library/modernizr/js/modernizr.min.js&quot;&gt;&lt;/script&gt;
    &lt;!--/ END JAVASCRIPT SECTION --&gt;
&lt;/head&gt;
&lt;!--/ END Head --&gt;
&lt;body&gt;
    &lt;!-- START Template Header --&gt;
    &lt;header id=&quot;header&quot; class=&quot;navbar navbar-fixed-top&quot;&gt;...&lt;/header&gt;
    &lt;!--/ END Template Header --&gt;

    &lt;!-- START Template Sidebar (Left) --&gt;
    &lt;aside class=&quot;sidebar sidebar-left sidebar-menu&quot;&gt;...&lt;/aside&gt;
    &lt;!--/ END Template Sidebar (Left) --&gt;

    &lt;!-- START Template Main --&gt;
    &lt;section id=&quot;main&quot; role=&quot;main&quot;&gt;
        &lt;!-- START Template Container --&gt;
        &lt;section class=&quot;container-fluid&quot;&gt;...&lt;/section&gt;
        &lt;!--/ END Template Container --&gt;
    &lt;/section&gt;
    &lt;!--/ END Template Main --&gt;

    &lt;!-- START Template Sidebar (right) --&gt;
    &lt;aside class=&quot;sidebar sidebar-right&quot;&gt;...&lt;/aside&gt;
    &lt;!--/ END Template Sidebar (right) --&gt;

    &lt;!-- START JAVASCRIPT SECTION (Load javascripts at bottom to reduce load time) --&gt;
    &lt;!-- Library script : mandatory --&gt;
    &lt;script src=&quot;library/jquery/js/jquery.min.js&quot;&gt;&lt;/script&gt;
    &lt;script src=&quot;library/jquery/js/jquery-ui.min.js&quot;&gt;&lt;/script&gt;
    &lt;script src=&quot;library/jquery/js/jquery-ui-touch.min.js&quot;&gt;&lt;/script&gt;
    &lt;script src=&quot;library/jquery/js/jquery-migrate.min.js&quot;&gt;&lt;/script&gt;
    &lt;script src=&quot;library/bootstrap/js/bootstrap.min.js&quot;&gt;&lt;/script&gt;
    &lt;script src=&quot;library/core/js/core.min.js&quot;&gt;&lt;/script&gt;
    &lt;!--/ Library script --&gt;

    &lt;!-- app script --&gt;
    &lt;script src=&quot;javascript/app.min.js&quot;&gt;&lt;/script&gt;
    &lt;!--/ app script --&gt;
    &lt;!--/ END JAVASCRIPT SECTION --&gt;
    &lt;/body&gt;
&lt;/html&gt;
</pre>  
<!--/ Code example -->          
        </div>
    </div>
</section>

<hr><!-- horizontal line -->

<section id="basic-template-frontend">
    <!-- section header -->
    <div class="section-header">
        <h4 class="semibold title mt10">Basic template - Frontend</h4>
    </div>
    <!--/ section header -->
    <div class="row">
        <div class="col-lg-12">
            <p>Copy the HTML below to begin working with a minimal document or you can refer to <code>page-blank.html</code></p>
<!-- Code example -->
<pre class="prettyprint linenums">
&lt;!DOCTYPE html&gt;
&lt;html class=&quot;frontend&quot;&gt;
&lt;!-- START Head --&gt;
&lt;head&gt;
    &lt;!-- START META SECTION --&gt;
    &lt;meta charset=&quot;utf-8&quot;&gt;
    &lt;meta http-equiv=&quot;X-UA-Compatible&quot; content=&quot;IE=edge&quot;&gt;
    &lt;title&gt;Inu - 1.0.0&lt;/title&gt;
    &lt;meta name=&quot;description&quot; content=&quot;Inu admin dashboard&quot;&gt;
    &lt;meta name=&quot;viewport&quot; content=&quot;width=device-width, user-scalable=no, initial-scale=1.0&quot;&gt;

    &lt;link rel=&quot;apple-touch-icon-precomposed&quot; sizes=&quot;144x144&quot; href=&quot;image/touch/apple-touch-icon-144x144-precomposed.png&quot;&gt;
    &lt;link rel=&quot;apple-touch-icon-precomposed&quot; sizes=&quot;114x114&quot; href=&quot;image/touch/apple-touch-icon-114x114-precomposed.png&quot;&gt;
    &lt;link rel=&quot;apple-touch-icon-precomposed&quot; sizes=&quot;72x72&quot; href=&quot;image/touch/apple-touch-icon-72x72-precomposed.png&quot;&gt;
    &lt;link rel=&quot;apple-touch-icon-precomposed&quot; href=&quot;image/touch/apple-touch-icon-57x57-precomposed.png&quot;&gt;
    &lt;link rel=&quot;shortcut icon&quot; href=&quot;image/touch/apple-touch-icon.png&quot;&gt;
    &lt;!--/ END META SECTION --&gt;

    &lt;!-- START STYLESHEETS --&gt;
    &lt;!-- Plugins stylesheet : optional --&gt;
    ...
    &lt;!--/ Plugins stylesheet --&gt;

    &lt;!-- Application stylesheet : mandatory --&gt;
    &lt;link rel=&quot;stylesheet&quot; href=&quot;library/bootstrap/css/bootstrap.min.css&quot;&gt;
    &lt;link rel=&quot;stylesheet&quot; href=&quot;stylesheet/layout.min.css&quot;&gt;
    &lt;link rel=&quot;stylesheet&quot; href=&quot;stylesheet/uielement.min.css&quot;&gt;
    &lt;!--/ Application stylesheet --&gt;
    &lt;!-- END STYLESHEETS --&gt;

    &lt;!-- START JAVASCRIPT SECTION - Load only modernizr script here --&gt;
    &lt;script src=&quot;library/modernizr/js/modernizr.min.js&quot;&gt;&lt;/script&gt;
    &lt;!--/ END JAVASCRIPT SECTION --&gt;
&lt;/head&gt;
&lt;!--/ END Head --&gt;
&lt;body&gt;
    &lt;!-- START Template Header --&gt;
    &lt;header id=&quot;header&quot; class=&quot;navbar navbar-fixed-top&quot;&gt;...&lt;/header&gt;
    &lt;!--/ END Template Header --&gt;

    &lt;!-- START Template Sidebar (Left) --&gt;
    &lt;aside class=&quot;sidebar sidebar-left sidebar-menu&quot;&gt;...&lt;/aside&gt;
    &lt;!--/ END Template Sidebar (Left) --&gt;

    &lt;!-- START Template Main --&gt;
    &lt;section id=&quot;main&quot; role=&quot;main&quot;&gt;
        &lt;!-- START Template Container --&gt;
        &lt;section class=&quot;container-fluid&quot;&gt;...&lt;/section&gt;
        &lt;!--/ END Template Container --&gt;
    &lt;/section&gt;
    &lt;!--/ END Template Main --&gt;

    &lt;!-- START Template Sidebar (right) --&gt;
    &lt;aside class=&quot;sidebar sidebar-right&quot;&gt;...&lt;/aside&gt;
    &lt;!--/ END Template Sidebar (right) --&gt;

    &lt;!-- START JAVASCRIPT SECTION (Load javascripts at bottom to reduce load time) --&gt;
    &lt;!-- Library script : mandatory --&gt;
    &lt;script src=&quot;library/jquery/js/jquery.min.js&quot;&gt;&lt;/script&gt;
    &lt;script src=&quot;library/jquery/js/jquery-ui.min.js&quot;&gt;&lt;/script&gt;
    &lt;script src=&quot;library/jquery/js/jquery-ui-touch.min.js&quot;&gt;&lt;/script&gt;
    &lt;script src=&quot;library/jquery/js/jquery-migrate.min.js&quot;&gt;&lt;/script&gt;
    &lt;script src=&quot;library/bootstrap/js/bootstrap.min.js&quot;&gt;&lt;/script&gt;
    &lt;script src=&quot;library/core/js/core.min.js&quot;&gt;&lt;/script&gt;
    &lt;!--/ Library script --&gt;

    &lt;!-- app script --&gt;
    &lt;script src=&quot;javascript/app.min.js&quot;&gt;&lt;/script&gt;
    &lt;!--/ app script --&gt;
    &lt;!--/ END JAVASCRIPT SECTION --&gt;
    &lt;/body&gt;
&lt;/html&gt;
</pre>  
<!--/ Code example -->          
        </div>
    </div>
</section>

<hr><!-- horizontal line -->

<section id="stylesheet">
    <!-- section header -->
    <div class="section-header">
        <h4 class="semibold title mb10">Stylesheet</h4>
    </div>
    <!--/ section header -->
    <div class="row">
        <div class="col-lg-12">
            <p>There are 3 main stylesheet used in this template. Note that bootstrap stylesheet is need to be include on top of this stylesheet file.</p>
            <ul>
                <li>bootstrap.css</li>
                <li>layout.css</li>
                <li>uielement.css</li>
            </ul>
<!-- Code example -->
<pre class="prettyprint linenums">
&lt;!-- START STYLESHEETS --&gt;
&lt;!-- Plugins stylesheet : optional --&gt;
...
&lt;!--/ Plugins stylesheet --&gt;

&lt;!-- Application stylesheet : mandatory --&gt;
&lt;link rel=&quot;stylesheet&quot; href=&quot;library/bootstrap/css/bootstrap.min.css&quot;&gt;
&lt;link rel=&quot;stylesheet&quot; href=&quot;stylesheet/layout.min.css&quot;&gt;
&lt;link rel=&quot;stylesheet&quot; href=&quot;stylesheet/uielement.min.css&quot;&gt;
&lt;!--/ Application stylesheet --&gt;
&lt;!-- END STYLESHEETS --&gt;
</pre>
<!--/ Code example -->
        </div>
    </div>
</section>

<hr><!-- horizontal line -->

<section id="javascript">
    <!-- section header -->
    <div class="section-header">
        <h4 class="semibold title mb10">Javascript</h4>
    </div>
    <!--/ section header -->
    <div class="row">
        <div class="col-lg-12">
            <p>This template is powered more by javascript. Disabling javascript in your browser will break the template and some of the features will not work so it is highly recommended that you make sure javascript is enabled.</p>
            <p>The javascript files can be found in the plugins &amp; library folder of template. Please be aware that any updates and changes made by yourself on this plugins might break the template. If you have trouble regarding this, please do not hesitate to ask my help.</p>
            <p>For plugins level javascript, include it if only you used the plugins. Below javascript is need to be include in every page.</p>
            <ul>
                <li>jquery.min.js</li>
                <li>jquery-ui.min.js</li>
                <li>jquery-ui-touch.min.js</li>
                <li>jquery-migrate.min.js</li>
                <li>bootstrap.min.js</li>
                <li>core.min.js</li>
            </ul>
<!-- Code example -->
<pre class="prettyprint linenums">
&lt;!-- START JAVASCRIPT SECTION (Load javascripts at bottom to reduce load time) --&gt;
&lt;!-- Library script : mandatory --&gt;
&lt;script src=&quot;library/jquery/js/jquery.min.js&quot;&gt;&lt;/script&gt;
&lt;script src=&quot;library/jquery/js/jquery-ui.min.js&quot;&gt;&lt;/script&gt;
&lt;script src=&quot;library/jquery/js/jquery-ui-touch.min.js&quot;&gt;&lt;/script&gt;
&lt;script src=&quot;library/jquery/js/jquery-migrate.min.js&quot;&gt;&lt;/script&gt;
&lt;script src=&quot;library/bootstrap/js/bootstrap.min.js&quot;&gt;&lt;/script&gt;
&lt;script src=&quot;library/core/js/core.min.js&quot;&gt;&lt;/script&gt;
&lt;!--/ Library script --&gt;

&lt;!-- app script --&gt;
&lt;script src=&quot;javascript/app.min.js&quot;&gt;&lt;/script&gt;
&lt;!--/ app script --&gt;
&lt;!--/ END JAVASCRIPT SECTION --&gt;
</pre>
<!--/ Code example -->
        </div>
    </div>
</section>

<hr><!-- horizontal line -->

<section id="layout-option">
    <!-- section header -->
    <div class="section-header">
        <h4 class="semibold title mb10">Layout Option</h4>
    </div>
    <!--/ section header -->
    <div class="row">
        <div class="col-lg-12">
            <p>You can change the behavior or appearance of this template by making the header always visible to the top or making the left panel fixed to it's position.</p>
            <h5 class="semibold mb5 text-primary">1. Static Header</h5>
<!-- Code example -->
<pre class="prettyprint linenums">
&lt;!-- START Template Header --&gt;
&lt;header id=&quot;header&quot; class=&quot;navbar&quot;&gt;...&lt;/header&gt;
&lt;!--/ END Template Header --&gt;
</pre>
<!--/ Code example -->
            <h5 class="semibold mb5 text-primary">2. Sticky Header</h5>
            <p class="mb5">Just add <code>navbar-fixed-top</code> class to <code>header</code> tag.</p>
<!-- Code example -->
<pre class="prettyprint linenums">
&lt;!-- START Template Header --&gt;
&lt;header id=&quot;header&quot; class=&quot;navbar navbar-fixed-top&quot;&gt;...&lt;/header&gt;
&lt;!--/ END Template Header --&gt;
</pre>
<!--/ Code example -->
            <h5 class="semibold mb5 text-primary">3. Static Sidebar</h5>
            <p class="mb5">Just add <code>sidebar-static</code> class to <code>aside</code> tag.</p>
<!-- Code example -->
<pre class="prettyprint linenums">
&lt;!-- START Template Sidebar --&gt;
&lt;aside class=&quot;sidebar sidebar-left sidebar-static&quot;&gt;...&lt;/aside&gt;
&lt;!--/ END Template Sidebar --&gt;
</pre>
        </div>
    </div>
</section>

<hr><!-- horizontal line -->

<section id="credits">
    <!-- section header -->
    <div class="section-header">
        <h4 class="semibold title mb10">Credits</h4>
    </div>
    <!--/ section header -->
    <div class="row">
        <div class="col-lg-12">
            <p>3rd party plugins and library script used by this template</p>
            <ul>
                <li><a href="http://jquery.com/">jQuery</a></li>
                <li><a href="http://jqueryui.com/">jQuery UI</a></li>
                <li><a href="http://touchpunch.furf.com/">jQuery UI touch punch</a></li>
                <li><a href="http://modernizr.com/">Modernizr</a></li>
                <li><a href="http://rocha.la/jQuery-slimScroll">Slimscroll</a></li>
                <li><a href="http://ricostacruz.com/jquery.transit/">Transit</a></li>
                <li><a href="http://luis-almeida.github.io/unveil/">Unveil</a></li>
                <li><a href="http://imakewebthings.com/jquery-waypoints/">Waypoints</a></li>
                <li><a href="http://bootboxjs.com/">Bootbox</a></li>
                <li><a href="https://datatables.net/">Datatables</a></li>
                <li><a href="http://www.flotcharts.org/">Flot Chart</a></li>
                <li><a href="http://arshaw.com/fullcalendar/">Fullcalendar</a></li>
                <li><a href="https://github.com/jboesch/Gritter">Gritter</a></li>
                <li><a href="http://jasny.github.io/bootstrap/javascript/#inputmask">Input mask</a></li>
                <li><a href="http://dimsemenov.com/plugins/magnific-popup/">Magnific popup</a></li>
                <li><a href="http://parsleyjs.org/">Parsley</a></li>
                <li><a href="http://brianreavis.github.io/selectize.js/">Selectize</a></li>
                <li><a href="http://omnipotent.net/jquery.sparkline/">Sparkline</a></li>
                <li><a href="http://hackerwins.github.io/summernote/">Summernote</a></li>
                <li><a href="http://masonry.desandro.com/">Masonry</a></li>
                <li><a href="http://hpneo.github.io/gmaps/">gMaps</a></li>
                <li><a href="http://jvectormap.com/">jvectormap</a></li>
            </ul>
            <p>Image used by this template</p>
            <ul>
                <li><a href="http://pixabay.com/">Avatar</a></li>
                <li><a href="http://unsplash.com/">Background image</a></li>
            </ul>
        </div>
    </div>
</section>

<hr><!-- horizontal line -->

<!-- START Changelog -->
<section class="section" id="changelog">
    <div class="container-fluid">
        <!-- START Row -->
        <div class="row">
            <div class="col-md-12 mb15">
                <div class="note note-rounded note-primary">
                    <h4 class="mt5 mb5">
                        Updates for <strong>v1.2.0</strong>
                        <span class="pull-right">16/05/2014</span>
                    </h4>
                </div>
                <h5 class="semibold mb5">IMPROVEMENTS AND UPDATES</h5>
                <ul class="list-unstyled pl10">
                    <li><i class="ico-checkmark4 mr5"></i> Update Core.js</li>
                    <li>
                        <ul class="list-unstyled pl15">
                            <li><i class="ico-circle-small mr5"></i> Add functionality to wait until window is loaded</li>
                            <li><i class="ico-circle-small mr5"></i> Add moment.js - javascript date library</li>
                            <li><i class="ico-circle-small mr5"></i> Add stellar.js - parallax background plugin</li>
                            <li><i class="ico-circle-small mr5"></i> Add MockJax.js - simulating ajax requests and responses plugin</li>
                            <li><i class="ico-circle-small mr5"></i> Add counterup plugin - number increment plugin</li>
                        </ul>
                    </li>
                    <li><i class="ico-checkmark4 mr5"></i> Update layout.css file</li>
                    <li>
                        <ul class="list-unstyled pl15">
                            <li><i class="ico-circle-small mr5"></i> Fix navbar-collapse</li>
                            <li><i class="ico-circle-small mr5"></i> Optimze the code</li>
                            <li><i class="ico-circle-small mr5"></i> Add topmenu-responsive class - trigger only on window resize</li>
                        </ul>
                    </li>
                    <li><i class="ico-checkmark4 mr5"></i> Update table-layout component</li>
                    <li>
                        <ul class="list-unstyled pl15">
                            <li><i class="ico-circle-small mr5"></i> Add table-layout-section</li>
                            <li><i class="ico-circle-small mr5"></i> Optimze the code</li>
                        </ul>
                    </li>
                    <li><i class="ico-checkmark4 mr5"></i> Update jumbotron component</li>
                    <li><i class="ico-checkmark4 mr5"></i> Update Owl carousel component</li>
                    <li><i class="ico-checkmark4 mr5"></i> Update Bootstrap nav-tab component</li>
                    <li><i class="ico-checkmark4 mr5"></i> Fix people directory shuffle item issue</li>
                </ul>

                <h5 class="semibold mb5">UPDATED 3RD PARTY PLUGINS</h5>
                <ul class="list-unstyled pl10">
                    <li><i class="ico-checkmark4 mr5"></i> Update Summernote Plugin (v?? -> v0.5.2)</li>
                    <li><i class="ico-checkmark4 mr5"></i> Update fullCalendar Plugin (v1.6.4 -> v2.0.0)</li>
                    <li><i class="ico-checkmark4 mr5"></i> Update Shuffle plugins (v2.0.2 -> v2.1.2)</li>
                </ul>

                <h5 class="semibold mb5">NEW COMPONENTS</h5>
                <ul class="list-unstyled pl10">
                    <li><i class="ico-checkmark4 mr5"></i> Layer Slide - PREMIUM($18)</li>
                    <li><i class="ico-checkmark4 mr5"></i> 70 256*256 Flat PNG Icons  - PREMIUM($8)</li>
                    <li><i class="ico-checkmark4 mr5"></i> Sidebar chat UI - buat bgprofile bagi ada gradient ckit</li>
                    <li><i class="ico-checkmark4 mr5"></i> Navbar-collapse dropdown mega menu</li>
                    <li><i class="ico-checkmark4 mr5"></i> Note UI</li>
                    <li><i class="ico-checkmark4 mr5"></i> Typography dropcap</li>
                    <li><i class="ico-checkmark4 mr5"></i> 15 Pattern background + overlay</li>
                    <li><i class="ico-checkmark4 mr5"></i> In-place editing - x-editable</li>
                    <li><i class="ico-checkmark4 mr5"></i> Global Offcanvas</li>
                </ul>

                <h5 class="semibold mb5">NEW BACKEND PAGES</h5>
                <ul class="list-unstyled pl10">
                    <li><i>none</i></li>
                </ul>

                <h5 class="semibold mb5">NEW FRONTEND PAGES</h5>
                <ul class="list-unstyled pl10">
                    <li><i class="ico-checkmark4 mr5"></i> Home Pages</li>
                    <li>
                        <ul class="list-unstyled pl15">
                            <li><i class="ico-circle-small mr5"></i> Layer Slider</li>
                            <li><i class="ico-circle-small mr5"></i> Parallax Background</li>
                        </ul>
                    </li>
                    <li><i class="ico-checkmark4 mr5"></i> Pages</li>
                    <li>
                        <ul class="list-unstyled pl15">
                            <li><i class="ico-circle-small mr5"></i> About Us</li>
                            <li><i class="ico-circle-small mr5"></i> Contact Us</li>
                            <li><i class="ico-circle-small mr5"></i> Left Sidebar</li>
                            <li><i class="ico-circle-small mr5"></i> Right Sidebar</li>
                            <li><i class="ico-circle-small mr5"></i> Account Register</li>
                            <li><i class="ico-circle-small mr5"></i> Login</li>
                        </ul>
                    </li>
                    <li><i class="ico-checkmark4 mr5"></i> Blogs</li>
                    <li>
                        <ul class="list-unstyled pl15">
                            <li><i class="ico-circle-small mr5"></i> Blog Large</li>
                            <li><i class="ico-circle-small mr5"></i> Blog Medium</li>
                            <li><i class="ico-circle-small mr5"></i> Blog Single</li>
                            <li><i class="ico-circle-small mr5"></i> Blog Left Sidebar</li>
                            <li><i class="ico-circle-small mr5"></i> Blog Right Sidebar</li>
                            <li><i class="ico-circle-small mr5"></i> Blog Masonry</li>
                        </ul>
                    </li>
                    <li><i class="ico-checkmark4 mr5"></i> Portfolios</li>
                    <li>
                        <ul class="list-unstyled pl15">
                            <li><i class="ico-circle-small mr5"></i> Portfolio 2 Columns</li>
                            <li><i class="ico-circle-small mr5"></i> Portfolio 3 Columns</li>
                            <li><i class="ico-circle-small mr5"></i> Portfolio 4 Columns</li>
                            <li><i class="ico-circle-small mr5"></i> Portfolio Single</li>
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="col-md-12 mb15">
                <div class="note note-rounded note-inverse">
                    <h4 class="mt5 mb5">
                        Updates for <strong>v1.1.3</strong>
                        <span class="pull-right">20/05/2014</span>
                    </h4>
                </div>
                <h5 class="semibold mb5">IMPROVEMENTS AND UPDATES</h5>
                <ul class="list-unstyled pl10">
                    <li><i class="ico-checkmark4 mr5"></i> Update layout.css</li>
                    <li>
                        <ul class="list-unstyled pl15">
                            <li><i class="ico-circle-small mr5"></i> Minor appearance change</li>
                            <li><i class="ico-circle-small mr5"></i> Optimze the code</li>
                        </ul>
                    </li>
                    <li><i class="ico-checkmark4 mr5"></i> masonry.js plugin doesn't run after sidebar is minimized</li>
                    <li><i class="ico-checkmark4 mr5"></i> Fix parsley.js validation on selectize plugin</li>
                    <li><i class="ico-checkmark4 mr5"></i> Fix selectize and flot plugin</li>
                    <li><i class="ico-checkmark4 mr5"></i> Fix top search form closing when tap</li>
                    <li><i class="ico-checkmark4 mr5"></i> New folder structure</li>
                </ul>

                <h5 class="semibold mb5">NEW COMPONENTS</h5>
                <ul class="list-unstyled pl10">
                    <li><i class="ico-checkmark4 mr5"></i> Added button outline</li>
                    <li><i class="ico-checkmark4 mr5"></i> Added Time Picker</li>
                </ul>

                <h5 class="semibold mb5">NEW BACKEND PAGES</h5>
                <ul class="list-unstyled pl10">
                    <li><i>none</i></li>
                </ul>
            </div>

            <div class="col-md-12 mb15">
                <div class="note note-rounded note-inverse">
                    <h4 class="mt5 mb5">
                        Updates for <strong>v1.1.2</strong>
                        <span class="pull-right">04/05/2014</span>
                    </h4>
                </div>
                <h5 class="semibold mb5">IMPROVEMENTS AND UPDATES</h5>
                <ul class="list-unstyled pl10">
                    <li><i class="ico-checkmark4 mr5"></i> Core.js script updatet</li>
                    <li>
                        <ul class="list-unstyled pl15">
                            <li><i class="ico-circle-small mr5"></i> Code improvement</li>
                            <li><i class="ico-circle-small mr5"></i> Code optimization</li>
                            <li><i class="ico-circle-small mr5"></i> Fixed SelectRow & CheckAll mini plugin</li>
                        </ul>
                    </li>
                    <li><i class="ico-checkmark4 mr5"></i> Added sidebar menu figure/icon with "hasnotification" functionality</li>
                    <li><i class="ico-checkmark4 mr5"></i> Update thumbnail components</li>
                    <li><i class="ico-checkmark4 mr5"></i> Update widget components</li>
                    <li><i class="ico-checkmark4 mr5"></i> Update dropdown components</li>
                    <li><i class="ico-checkmark4 mr5"></i> Update table-default components(more sample usage)</li>
                    <li><i class="ico-checkmark4 mr5"></i> Fixed app.js file (remove extra commas)</li>
                    <li><i class="ico-checkmark4 mr5"></i> Fixed sidebar on tablet viewport</li>
                    <li><i class="ico-checkmark4 mr5"></i> Fixed sidebar js issue</li>
                    <li><i class="ico-checkmark4 mr5"></i> Fixed timeline v2 issue</li>
                </ul>

                <h5 class="semibold mb5">NEW COMPONENTS</h5>
                <ul class="list-unstyled pl10">
                    <li><i class="ico-checkmark4 mr5"></i> Added img-grid class</li>
                    <li><i class="ico-checkmark4 mr5"></i> Header navbar-collapse</li>
                    <li><i class="ico-checkmark4 mr5"></i> Vector maps</li>
                    <li><i class="ico-checkmark4 mr5"></i> Google maps</li>
                </ul>

                <h5 class="semibold mb5">NEW BACKEND PAGES</h5>
                <ul class="list-unstyled pl10">
                    <li><i>none</i></li>
                </ul>
            </div>

            <div class="col-md-12 mb15">
                <div class="note note-rounded note-inverse">
                    <h4 class="mt5 mb5">
                        Updates for <strong>v1.1.1</strong>
                        <span class="pull-right">17/04/2014</span>
                    </h4>
                </div>
                <h5 class="semibold mb5">IMPROVEMENTS AND UPDATES</h5>
                <ul class="list-unstyled pl10">
                    <li><i class="ico-checkmark4 mr5"></i> Core.js script updatet</li>
                    <li>
                        <ul class="list-unstyled pl15">
                            <li><i class="ico-circle-small mr5"></i> Embed imagesLoaded.js</li>
                            <li><i class="ico-circle-small mr5"></i> Fix sidebar minimize on touch devices(tablet)</li>
                            <li><i class="ico-circle-small mr5"></i> Add functionality to watch/observe submenu position</li>
                        </ul>
                    </li>
                    <li><i class="ico-checkmark4 mr5"></i> Fixed sidebar-menu not reset the position</li>
                    <li><i class="ico-checkmark4 mr5"></i> Fixed sidebar stats not showing</li>
                    <li><i class="ico-checkmark4 mr5"></i> Added fluid iframe container</li>
                    <li><i class="ico-checkmark4 mr5"></i> Added `btn-tag` wrapper</li>
                    <li><i class="ico-checkmark4 mr5"></i> Added more widget example</li>
                    <li><i class="ico-checkmark4 mr5"></i> Added breadcrumb to page header</li>
                </ul>

                <h5 class="semibold mb5">NEW COMPONENTS</h5>
                <ul class="list-unstyled pl10">
                    <li><i class="ico-checkmark4 mr5"></i> Spinner / loading indicator</li>
                    <li><i class="ico-checkmark4 mr5"></i> Button loading (ladda)</li>
                    <li><i class="ico-checkmark4 mr5"></i> Carousel</li>
                    <li><i class="ico-checkmark4 mr5"></i> Form layout</li>
                    <li><i class="ico-checkmark4 mr5"></i> Form ajax</li>
                </ul>

                <h5 class="semibold mb5">NEW BACKEND PAGES</h5>
                <ul class="list-unstyled pl10">
                    <li><i class="ico-checkmark4 mr5"></i> Dashboard v2</li>
                    <li><i class="ico-checkmark4 mr5"></i> Blog pages</li>
                    <li>
                        <ul class="list-unstyled pl15">
                            <li><i class="ico-circle-small mr5"></i> Blog default</li>
                            <li><i class="ico-circle-small mr5"></i> Blog grid (masonry)</li>
                            <li><i class="ico-circle-small mr5"></i> Single post</li>
                        </ul>
                    </li>
                    <li><i class="ico-checkmark4 mr5"></i> FAQ page</li>
                </ul>
            </div>

            <div class="col-md-12 mb15">
                <div class="note note-rounded note-inverse">
                    <h4 class="mt5 mb5">
                        Updates for <strong>v1.1.0</strong>
                        <span class="pull-right">04/04/2014</span>
                    </h4>
                </div>
                <h5 class="semibold mb5">IMPROVEMENTS AND UPDATES</h5>
                <ul class="list-unstyled pl10">
                    <li><i class="ico-checkmark4 mr5"></i> LESS File include</li>
                    <li><i class="ico-checkmark4 mr5"></i> Core.js script improvement</li>
                    <li>
                        <ul class="list-unstyled pl15">
                            <li><i class="ico-circle-small mr5"></i> Embed mustache.js (minimal javascript templating)</li>
                            <li><i class="ico-circle-small mr5"></i> Remove Yepnope script loader. library script is embeded inside the core script</li>
                            <li><i class="ico-circle-small mr5"></i> Move prettify plugin to plugins folder</li>
                            <li><i class="ico-circle-small mr5"></i> Move jquery-ui plugin to plugins folder</li>
                        </ul>
                    </li>
                    <li><i class="ico-checkmark4 mr5"></i> Page level script is organized &amp; structured</li>
                    <li>
                        <ul class="list-unstyled pl15">
                            <li><i class="ico-circle-small mr5"></i> All the page level script inside app.js file is move to seperate file.</li>
                            <li><i class="ico-circle-small mr5"></i> app.js will handle template global function from now.</li>
                        </ul>
                    </li>
                    <li><i class="ico-checkmark4 mr5"></i> Update Thumbnail UI</li>
                    <li>
                        <ul class="list-unstyled pl15">
                            <li><i class="ico-circle-small mr5"></i> Darken thumbnail ".meta" background color - readability purpose</li>
                            <li><i class="ico-circle-small mr5"></i> Thumbnail ".overlay" color is set to template accent color</li>
                            <li><i class="ico-circle-small mr5"></i> Remove thumbnail ".toolbar" button line(CSS property)</li>
                        </ul>
                    </li>
                    <li><i class="ico-checkmark4 mr5"></i> Update Sidebar menu UI and structure</li>
                    <li>
                        <ul class="list-unstyled pl15">
                            <li><i class="ico-circle-small mr5"></i> Remove ".menuheader" and replace with sidebar content ".heading"</li>
                            <li><i class="ico-circle-small mr5"></i> Added data-toggle="menu" attribute to ".topmenu" class to preserve menu position</li>
                        </ul>
                    </li>
                    <li><i class="ico-checkmark4 mr5"></i> Update Gitter components</li>
                    <li>
                        <ul class="list-unstyled pl15">
                            <li><i class="ico-circle-small mr5"></i> Update the UI</li>
                            <li><i class="ico-circle-small mr5"></i> Add functionality to inherit bootstrap alert classes(warning, info, success, and danger)</li>
                        </ul>
                    </li>
                    <li><i class="ico-checkmark4 mr5"></i> Update template logo</li>
                    <li>
                        <ul class="list-unstyled pl15">
                            <li><i class="ico-circle-small mr5"></i> Structure change</li>
                            <li><i class="ico-circle-small mr5"></i> Move all logo assets to "logo" folder</li>
                            <li><i class="ico-circle-small mr5"></i> Include the logo PSD file both retina and non retina</li>
                            <li><i class="ico-circle-small mr5"></i> Add new logo-figure(visible on table viewport and sidebar minimize)</li>
                        </ul>
                    </li>
                    <li><i class="ico-checkmark4 mr5"></i> Update datatable component</li>
                    <li><i class="ico-checkmark4 mr5"></i> Replace mixitup plugin with shuffle plugin(license issue)</li>
                    <li><i class="ico-checkmark4 mr5"></i> All library and plugins script updated</li>
                    <li><i class="ico-checkmark4 mr5"></i> Some bug fix</li>
                </ul>

                <h5 class="semibold mb5">NEW COMPONENTS</h5>
                <ul class="list-unstyled pl10">
                    <li><i class="ico-checkmark4 mr5"></i> Tablet menu</li>
                    <li><i class="ico-checkmark4 mr5"></i> Sidebar menu minimize/maximize</li>
                    <li><i class="ico-checkmark4 mr5"></i> Sidebar summary</li>
                    <li><i class="ico-checkmark4 mr5"></i> SVG loading indicator</li>
                    <li><i class="ico-checkmark4 mr5"></i> Youtube style progressbar</li>
                    <li><i class="ico-checkmark4 mr5"></i> Switchery - checkbox switch</li>
                </ul>

                <h5 class="semibold mb5">NEW BACKEND PAGES</h5>
                <ul class="list-unstyled pl10">
                    <li><i class="ico-checkmark4 mr5"></i> Profile</li>
                    <li><i class="ico-checkmark4 mr5"></i> People directory</li>
                    <li><i class="ico-checkmark4 mr5"></i> Timeline version 1</li>
                    <li><i class="ico-checkmark4 mr5"></i> Timeline version 2</li>
                    <li><i class="ico-checkmark4 mr5"></i> Invoice page with printable layout</li>
                </ul>
            </div>
        </div>
        <!--/ END Row -->
    </div>
</section>
<!--/ END Changelog -->
            </section>
            <!--/ END Template Container -->

            <!-- START To Top Scroller -->
            <a href="#" class="totop animation" data-toggle="waypoints totop" data-marker="#main" data-showanim="bounceIn" data-hideanim="bounceOut" data-offset="-50%"><i class="ico-angle-up"></i></a>
            <!--/ END To Top Scroller -->
        </section>
        <!--/ END Template Main -->

        <!-- START JAVASCRIPT SECTION (Load javascripts at bottom to reduce load time) -->
<!-- Library script : mandatory -->
<script type="text/javascript" src="resources/themes/admire/library/jquery/js/jquery.min.js"></script>
<script type="text/javascript" src="resources/themes/admire/library/jquery/js/jquery-migrate.min.js"></script>
<script type="text/javascript" src="resources/themes/admire/library/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="resources/themes/admire/library/core/js/core.min.js"></script>
<script type="text/javascript" src="resources/themes/admire/plugins/prettify/js/prettify.js"></script>

<script type="text/javascript">
$(function () {
    $("html").Core();
    prettyPrint();
});
</script>
<!--/ Library script -->
<!--/ END JAVASCRIPT SECTION -->
    </body>
</html>
