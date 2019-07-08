$(function(){
/***********************
 *organization configuration
 ***********************/
 	$('#organizationSettings').submit(function(){
 		//alert("now submit called");
 		var url = $(this).attr('action');
 		var data = $(this).serialize();
 		$.post(url, data, function(o){
 			if(o.status == "success")
 				document.location = o.link;
 		}, 'json');
 		return false;
 	});
});
