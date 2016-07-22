
function deleteme(my_string)
{

    var My_Message = my_string;

        jQuery.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
				action: 'test_response',
				id:My_Message,
				},
				dataType: 'html',
				success: function(response) {
				location.reload();
				}
		});
	  
    return false;
}

