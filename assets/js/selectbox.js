function displayVals() {
  var singleValues = jQuery( "#session_speaker" ).val();
	
  jQuery.ajax({
			type: 'post',
			url: ajaxurl,		
			data:{
					
			     	singleValues: singleValues,
			  }, 
			success: function(data) {
			 //alert(speaker);
	           var value = singleValues;
	           var test = value.split('**');
	           var speaker= test[0];
	           var designation= test[1];
	           var orgnisation= test[2];
	           var org_logo= test[3];
	           var speaker_id= test[4];
	           //alert(speaker+speaker_id);
	           //speakerajax
	            jQuery( "#session_speakerrole" ).val(designation);
	            jQuery( "#session_speakerorg" ).val(orgnisation);
	            jQuery( "#meta_image" ).val(org_logo);
	            jQuery( "#speaker_id" ).val(speaker_id);
	            
	            
	  
        	}
		});
}
 
jQuery( "select" ).change( displayVals );
displayVals();