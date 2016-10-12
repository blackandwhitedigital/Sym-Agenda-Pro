
function editeme(my_string)
{

    var My_Message = my_string;
console.log(My_Message);
        jQuery.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
				action: 'editquery',
				id:My_Message,
				},
				//dataType: 'html',
				success: function(data) {
					
					var value = data;
	           		var test = value.split('**');
	           		var id= test[0];
	           		var title= test[1];
	           		var timefrom= test[2];
	           		var timeto= test[3];
	           		var desc= test[4];
	           		var speaker= test[5];  		
	           		var role= test[6];
	           		var org= test[7];
	           		var logo= test[8];
	           		var room= test[9];
	           		var check= test[10];
	           		var speaker_id = test[11];
	           		console.log(speaker);
	           		//alert(check);
					jQuery( "#session_id" ).val(id);
					jQuery( "#session_title" ).val(title);
					jQuery( "#session_timefrom" ).val(timefrom);
					jQuery( "#session_timeto" ).val(timeto);
					jQuery( "#sessionspeaker_name" ).val(speaker);
					jQuery('.speakerajax').replaceWith('<option>'+speaker+'</option>');
					jQuery( "#session_desc" ).val(desc);
					jQuery( "#session_speakerrole" ).val(role);
					jQuery( "#session_room" ).val(room);
					jQuery( "#session_speakerorg" ).val(org);
					jQuery( "#meta_image" ).val(logo);
					if (check==true){
						jQuery( "#checkbox").prop('checked', true);	
					}
					jQuery( "#speaker_id" ).val(speaker_id);
					
					
										
				}
		});
	  
    return false;
}
