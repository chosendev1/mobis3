$(function() {
	

	$('#addUserForm').submit(function(){
		var url = $(this).attr('action');
		var data = $(this).serialize();
		$.post(url, data, function(o) {
			if(o.status == 'success'){
				//document.location = o.link
			}
			else{
				//alert(o.status);
				$('#responsediv').toggleClass("alert alert-dismissable alert-danger");
			}
			document.getElementById('responsediv').innerHTML = o.status;
				//document.location = o.failredirect;
		}, 'json');
		console.log(data);
		return false;
	});
	
	
});
