//alert("this file is being reached");
$(function() {
	//alert("jquery is okay");

	$('#form-register').submit(function(){
		//alert("this form has been called by ajax");
		var url = $(this).attr('action');
		var data = $(this).serialize();
		$.post(url, data, function(o) {
			//$('#listInserts').append("<div>" + o.text + "<a class='del' rel='" + o.id + "' href='#'>X</a></div>");
			//alert(o.link);
			//alert(o.status);
			//document.location = o.link;
			if(o.status == 'success'){
				//alert(1);
				document.location = o.link;
			}
			else
				alert(o.status);
			
		}, 'json');
		console.log(data);
		return false;
	});
	
	
});

