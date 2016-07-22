
function event_show(postid) {
	var id= postid;
	console.log(id);
	jQuery.ajax({
			type: 'post',
			url: the_ajax_event.ajaxurl,		
			data:{
					action: 'eventinfo',
			     	id: id,
			  }, 
			success: function(data) {
			 
	            console.log(data);
	            document.getElementById('textm').style.display = "block";
	            var value = data;
		        var test = value.split('**');
		        var title= test[0];
		        var speakerimg= test[1];
		        var organsation= test[2];
		        var designation= test[3];
		        var description= test[4];
		        var logos= test[5];
		        if (logos != false){
					 document.getElementById("urlpopup").innerHTML = '<img src=\''+logos+'\'>';
				}
		        document.getElementById("speakerimg").innerHTML = '<img src=\''+speakerimg+'\'>';
	            document.getElementById("namepopup").innerHTML = title;
	            document.getElementById("desigpopup").innerHTML = designation;
	            document.getElementById("orgpopup").innerHTML = organsation;
	            document.getElementById("biopopup").innerHTML = description;
	           
	         
	            
        	}
		});

}

function event_hide(){
	document.getElementById('textm').style.display = "none";
}
