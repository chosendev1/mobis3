//alert("this file is being reached");
$(function() {
	

	$('#loginForm').submit(function(){
		//alert("this form has been called by ajax");
		var url = $(this).attr('action');
		var data = $(this).serialize();
		$.post(url, data, function(o) {
			//$('#listInserts').append("<div>" + o.text + "<a class='del' rel='" + o.id + "' href='#'>X</a></div>");
			alert(o.status);
			if(o.status == 'success')
				document.location = o.link;
			else
				alert(o.status);
		}, 'json');
		console.log(data);
		return false;
	});
	
	
});
