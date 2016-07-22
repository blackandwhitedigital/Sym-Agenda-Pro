function drag(id) {
	/*jQuery( "#sortable" ).sortable({ 
		opacity:0.6, 
		update: function() {
		var id= id;
		var order = jQuery(this).sortable("serialize") + '&action=sort'; 
	}							  
	});*/
	/*jQuery( "#sortable" ).sortable();
    jQuery( "#sortable" ).disableSelection();*/
    var sortInput = jQuery('#sort_order');
	var submit = jQuery('#autoSubmit');
	//var messageBox = jQuery('#message-box');
	var list = jQuery('#sortable-list');
	/* create requesting function to avoid duplicate code */
	//console.log(sortInput[0].value);
	var request = function() {
		jQuery.ajax({
			url: ajaxurl,
			type: 'POST',
			data:{
				action:'dragndrop_table',
				sort_order: sortInput[0].value + '&ajax=' + submit[0].checked + '&do_submit=1&byajax=1', //need [0]?
			}, 		
		});
	};
	/* worker function */
	var fnSubmit = function(save) {
		var sortOrder = [];
		list.children('tr').each(function(){
			sortOrder.push(jQuery(this).data('id'));
		});
		sortInput.val(sortOrder.join(','));
		console.log(sortInput.val());
		if(save) {
			request();
		}
	};
	/* store values */
	list.children('tr').each(function() {
		var tr = jQuery(this);
		tr.data('id',tr.attr('title')).attr('title','');
	});
	/* sortables */
	list.sortable({
		opacity: 0.7,
		update: function() {
			fnSubmit(submit[0].checked);
		}
	});
	list.disableSelection();
	/* ajax form submission */
	jQuery('#dd-form').bind('submit',function(e) {
		if(e) e.preventDefault();
		fnSubmit(true);
	});
  }