jQuery(document).ready(function($){
	
	// We can also pass the url value separately from ajaxurl for front end AJAX implementations
	$( "#search" ).autocomplete({
		source: function( request, response ) {
			var data = {
				'action': 'autocomplete_post',
				'search' : request.term,
			};
			/*$.ajax({
				url : my_autocomplete.urlrrr,
				data : data,
				success: function(resp){
					response(resp);
				}
			});*/
			jQuery.post(my_autocomplete.urlrrr, data, function(resp) {
				response(resp); 
				
			});
		},
		minLength: 1,
		select: function( event, ui ) {
			if(ui.item){
				$('#search_name').val(ui.item.value);
			}
			/*$("#submit").click(function(){
				var dataa = {
					'action': 'get_value',
					'value' : $('#search').val(),
				};
				console.log(dataa);
   				var l = $('data.label').val();
   		 		$('#label_temp').text(l);		
			});*/
		}
	} );
	
});