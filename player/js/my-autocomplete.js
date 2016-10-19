jQuery(document).ready(function($){
	$( "#search" ).autocomplete({
		source: function( request, response ) {
			var data = {
				'action': 'autocomplete_post',
				'search' : request.term,
			};
			jQuery.post(my_autocomplete.urlrrr, data, function(resp) {
				response(resp); 
				
			});
		},
		minLength: 1,
		select: function( event, ui ) {
			if(ui.item){
				$('#search_name').val(ui.item.value);
			}
		}
	} );
});