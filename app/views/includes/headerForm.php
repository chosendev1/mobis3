<?php
/*@fileName: headerForm.php
 *@date: 2014-09-14
 *@author: Noah Nambale [namnoah@gmail.com]
 *@description: 
 *TODO
 *
 */
 require_once('resources/common.php');
 require_once('resources/includes/mail/class.phpmailer.php');
 $cssfiles = array("resources/themes/admire/plugins/selectize/css/selectize.min.css",
    "resources/themes/mobis/css1/stylesheet.css",
    "resources/themes/admire/stylesheet/layout.min.css",
    "resources/themes/admire/stylesheet/uielement.min1.css",
   
    "resources/themes/admire/library/bootstrap/css/bootstrap.min.css",
    "resources/themes/mobis/css/stylesheets.css",
    
    
              "resources/themes/admire/mobis/css1/stylesheet.css",

                  "resources/themes/admire/mobis/css1/icons.css",
    
     "resources/themes/mobis/css1/icons-sprite.css",
      "resources/themes/mobis/css1/bootstrap11.css",

                  "resources/themes/mobis/css1/styles.css",
    
    "resources/themes/mobis/css/demo1.css",
    "resources/themes/mobis/css/toastr.min.css"
  
    );
    
 $jsfiles = array("resources/themes/mobis/js/plugins/jquery/jquery-1.10.2.min.js",
    "resources/themes/mobis/js/plugins/jquery/jquery-ui-1.10.1.custom.min.js",
    "resources/themes/mobis/js/plugins/jquery/jquery-migrate-1.1.1.min.js",
  
    "resources/themes/mobis/js/plugins/jquery/globalize.js",
    "resources/themes/mobis/js/plugins/other/excanvas.js",
   
    "resources/themes/mobis/js/plugins/other/jquery.mousewheel.min.js",
        
    "resources/themes/mobis/js/plugins/bootstrap/bootstrap.min.js", 

    "resources/themes/mobis/js/plugins/cookies/jquery.cookies.2.2.0.min.js",
    
    "resources/themes/mobis/js/plugins/fancybox/jquery.fancybox.pack.js",
    
    "resources/themes/mobis/js/plugins/jflot/jquery.flot.js",    
    "resources/themes/mobis/js/plugins/jflot/jquery.flot.stack.js",    
    "resources/themes/mobis/js/plugins/jflot/jquery.flot.pie.js",
    "resources/themes/mobis/js/plugins/jflot/jquery.flot.resize.js",
    
    "resources/themes/mobis/js/plugins/epiechart/jquery.easy-pie-chart.js",
    "resources/themes/mobis/js/plugins/knob/jquery.knob.js",
    
    "resources/themes/mobis/js/plugins/sparklines/jquery.sparkline.min.js",    
    
    "resources/themes/mobis/js/plugins/pnotify/jquery.pnotify.min.js",
    
    "resources/themes/mobis/js/plugins/fullcalendar/fullcalendar.min.js",        
    
    "resources/themes/mobis/js/plugins/datatables/jquery.dataTables.min.js",    
    
    "resources/themes/mobis/js/plugins/wookmark/jquery.wookmark.js",        
    
    "resources/themes/mobis/js/plugins/jbreadcrumb/jquery.jBreadCrumb.1.1.js",
    
    "resources/themes/mobis/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js",
    
    "resources/themes/mobis/js/plugins/uniform/jquery.uniform.min.js",
    "resources/themes/mobis/js/plugins/select/select2.min.js",
    "resources/themes/mobis/js/plugins/tagsinput/jquery.tagsinput.min.js",
    "resources/themes/mobis/js/plugins/maskedinput/jquery.maskedinput-1.3.min.js",
    "resources/themes/mobis/js/plugins/multiselect/jquery.multi-select.min.js",    
    
    "resources/themes/mobis/js/plugins/validationEngine/languages/jquery.validationEngine-en.js",
    "resources/themes/mobis/js/plugins/validationEngine/jquery.validationEngine.js",        
    "resources/themes/mobis/js/plugins/stepywizard/jquery.stepy.js",
        
    "resources/themes/mobis/js/plugins/animatedprogressbar/animated_progressbar.js",
    "resources/themes/mobis/js/plugins/hoverintent/jquery.hoverIntent.minified.js",
    
    "resources/themes/mobis/js/plugins/media/mediaelement-and-player.min.js",    
    
    "resources/themes/mobis/js/plugins/cleditor/jquery.cleditor.js",
    
    "resources/themes/mobis/js/plugins/shbrush/XRegExp.js",
    "resources/themes/mobis/js/plugins/shbrush/shCore.js",
    "resources/themes/mobis/js/plugins/shbrush/shBrushXml.js",
    "resources/themes/mobis/js/plugins/shbrush/shBrushJScript.js",
    "resources/themes/mobis/js/plugins/shbrush/shBrushCss.js",    
        
    "resources/themes/mobis/js/plugins/filetree/jqueryFileTree.js",
    
    "resources/themes/mobis/js/plugins/slidernav/slidernav-min.js",    
    "resources/themes/mobis/js/plugins/isotope/jquery.isotope.min.js",    
    "resources/themes/mobis/js/plugins/jnotes/jquery-notes_1.0.8_min.js",
    "resources/themes/mobis/js/plugins/jcrop/jquery.Jcrop.min.js",
    "resources/themes/mobis/js/plugins/ibutton/jquery.ibutton.min.js",
    
    "resources/themes/mobis/js/plugins/scrollup/jquery.scrollUp.min.js",
   
    "resources/themes/mobis/js/highcharts.js",
   "resources/themes/mobis/js/exporting.js",
    
    "resources/themes/mobis/js/plugins.js",
    "resources/themes/mobis/js/charts1.js",
  

    "resources/themes/mobis/js/plugins/chosen/bootstrap-chosen.css",            
    "resources/themes/mobis/js/plugins/chosen/chosen.css", 
    "resources/themes/mobis/js/plugins/chosen/chosen.jquery.js", 
    "resources/themes/mobis/js/accordion.js",
    "resources/themes/mobis/js/toastr.min.js",
    "resources/themes/mobis/js/jquery.searchabledropdown-1.0.8.min.js",
    "resources/themes/mobis/js/actions.js",
    "resources/js/common.js",  
     "resources/js/jquery.base64.js",
    "resources/js/jspdf/libs/sprintf.js",

 
     "resources/js/jspdf/jspdf.min.js",
     "resources/js/jspdf/jspdf.plugin.autotable.js",
    "resources/js/jspdf/libs/base64.js",
    "resources/js/tableexport.js",


    "resources/js/jspdf/testmargins.js"
    );
 if(isset($css)){
  foreach($css as $c)
    array_push($cssfiles,$c);
 }
 if(isset($js)){
  foreach($js as $j)
    array_push($jsfiles,$j);
 }
 $theme = new themeProc("admire", $jsfiles, $cssfiles);
 
$xajax;
 if(isset($xajaxOn) && $xajaxOn === true){
  require_once('includes/external/xajax_0.5/xajax_core/xajax.inc.php');
  $xajax = new xajax();
  $xajax->setFlag('debug',true);
  if(isset($xjf)){
    foreach($xjf as $path)
      require_once($path);
  }
  $xajax->registerFunction("testXajax");
  require_once('resources/xajax/common.php');
  function testXajax(){
    $res = new xajaxResponse();
    $res->alert("Ajax was called");
    return $res;
  }
  
  $xajax->processRequest();
 }
?>

<!DOCTYPE html>

<html class="backend">
    <!-- START Head -->
    <head>
        <!-- START META SECTION -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?=$lang['title']?></title>
        <meta name="author" content="craneapps.com">
        <meta name="description" content="INU System Developed by Noah and Team alias Elabman">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <base href="<?=base_url()?>">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="resources/themes/admire/image/touch/apple-touch-icon-144x144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="resources/themes/admire/image/touch/apple-touch-icon-114x114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="resources/themes/admire/image/touch/apple-touch-icon-72x72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="resources/themes/admire/image/touch/apple-touch-icon-57x57-precomposed.png">
        <link rel="shortcut icon" href="resources/themes/admire/image/favicon.ico">
        <!--/ END META SECTION -->
  <?php
    if(isset($xajaxOn) && $xajaxOn === true){
      $xajax->printJavascript(base_url().'includes/external/xajax_0.5');
    }
  ?>
        <!-- START STYLESHEETS -->
        <!-- Plugins stylesheet : optional -->
        <?=$theme->renderCSS()?>
        <?=$theme->renderJs()?>
        <script type="text/javascript" src="resources/includes/external/calendar/ng_all.js"></script>
        <script type="text/javascript" language="javascript">

        
          function createDate(dateField){
            
            ng.ready(function () {
              $("#"+dateField).datepicker({
                
                changeMonth: true,
                changeYear: true,
                 yearRange: '-100:+3',
                dateFormat: 'yy-mm-dd'
           });  
            });
          } 
          
          function createDOB(dateField){
            
            ng.ready(function () {
              $("#"+dateField).datepicker({
                
                changeMonth: true,
                changeYear: true,
                yearRange: '-100:-0',
                dateFormat: 'yy-mm-dd'
           });  
            });
          }
          

        </script>

         <script type="text/javascript" src="resources/js/datatable_xajax.js"></script>
         
          <script type="text/javascript" language="javascript">
function printDiv(divName) {
 // alert("called");
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
   </script> 
    <script type="text/javascript" language="javascript"> 
     var dataLogo="<?php echo libinc::getLogoDataUri(); ?>";
     
    </script> 
    
    <script>
	  window.intercomSettings = {
	    app_id: "z5k0gx1o",
	    name: "<?php echo CAP_Session::get('username'); ?>", // Full name
	   // email: "<?php echo CAP_Session::get('email'); ?>", // Email address
	    created_at: "<?php echo strtotime(CAP_Session::get('timestamp')); ?>" // Signup date as a Unix timestamp
	  };
	</script>
	<script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/z5k0gx1o';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()
	</script>
    </head>
    <!--/ END Head -->
     <!-- START Body -->
    <body>
   
        <!-- START Template Header -->
     <?  
    
     $this->load->view("includes/header");
     echo "<br/><br/><br/>";
      $this->load->view("includes/breadcrumb");
     /* if($heading<>"Reports" || $heading<>"Chart Of Accounts"){
     include_once('breadcrumb2.php');
     }
        */
      ?>
     
    <div id="status" ></div>
      <div id="display_div">
      
      </div>
