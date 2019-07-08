/*! ========================================================================
 * login.js
 * Page/renders: page-login.html
 * Plugins used: parsley
 * ======================================================================== */
/*$(function () {
    // Login form function
    // ================================
    var $form    = $("form[name=form-login]");

    // On button submit click
    $form.on("click", "button[type=submit]", function (e) {
        var $this = $(this);

        // Run parsley validation
        if ($form.parsley().validate()) {
            // Disable submit button
            $this.prop("disabled", true);

            // start nprogress bar
            NProgress.start();

            // you can do the ajax request here
            // this is for demo purpose only
            setTimeout(function () {
                // done nprogress bar
                NProgress.done();

                // redirect user
                location.href = "serviceAuth/login";
            }, 5000);
        } else {
            // toggle animation
            $form
                .removeClass("animation animating shake")
                .addClass("animation animating shake")
                .one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function () {
                    $(this).removeClass("animation animating shake");
                });
        }
        // prevent default
        e.preventDefault();
    });
});

*/

//alert("this file is being reached");
$(function() {
	
	$('#form-login').submit(function(){
		//alert("this form has been called by ajax");
		var url = $(this).attr('action');
		var data = $(this).serialize();
		$.post(url, data, function(o) {
			//$('#listInserts').append("<div>" + o.text + "<a class='del' rel='" + o.id + "' href='#'>X</a></div>");
			//alert(o.status);
			if(o.status == 'success'){
				document.location = o.link;
			}
			else
				document.location = o.failredirect;
		}, 'json');
		console.log(data);
		return false;
	});
	
	
});
