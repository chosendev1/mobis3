//alert("this file is being reached");
$(function() {
	//alert(1);
	/*$.get('dashboard/xhrGetListings', function(o) {
		//console.log(o);
		for(var i = 0; i< o.length; ++i){
			$('#listInserts').append("<div>" + o[i].text + "<a class='del' rel='" + o[i].id + "' href='#'>X</a></div>");
		}
		
		$('.del').click(function(){
			delItem = $(this);
			var id = $(this).attr('rel');
			alert(id);
			$.post("dashboard/xhrDeleteListing", { 'id': id}, function(o) {
				//$('#listInserts').append("<div>" + o.text + "<a class='del' rel='" + o.id + "' href='#'>X</a></div>");
				delItem.parent().remove();
			});
			return false;
		});
		
	}, 'json');*/

	$('#addUserForm').submit(function(){
		//alert("this form has been called by ajax");
		var url = $(this).attr('action');
		var data = $(this).serialize();
		$.post(url, data, function(o) {
			//$('#listInserts').append("<div>" + o.text + "<a class='del' rel='" + o.id + "' href='#'>X</a></div>");
			alert(o.status);
			if(o.status == 'success')
				location.reload();
		}, 'json');
		console.log(url);
		return false;
	});
	
	
});
