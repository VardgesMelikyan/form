jQuery(document).ready(function(jQuery){
	if(jQuery('.fields-count').length){
		jQuery('.col-count').val(jQuery('.fields-count').val())
		jQuery('.fields-count').remove();
	}
	if(jQuery('.form-title').length){
		jQuery('.form_title').val(jQuery('.form-title').val())
		jQuery('.form-title').remove();
	}
	jQuery('.add_column').on('click',function(){
		var col = jQuery(this).attr('id');
		var colCount = parseInt(jQuery('.col-count').val()) +1;
		if(col == '1col'){
			jQuery('#form-container').append('<div class="grid-container-full"><div class="grid-item" data-id="' + colCount + '"><input type="hidden" name="emptyFull_' + colCount + '"><a href="/" data-id="' + colCount + '" data-attr="add-field" class="removefield">Remove</a><button class="add_field button full-width" data-id="' + colCount + '">Add Field</button></div></div>');
		}else{
			jQuery('#form-container').append('<div class="grid-container"><div class="grid-item" data-id="' + colCount + '-1"><input type="hidden" name="emptyGrid_' + colCount + '-1"><a href="/" data-id="' + colCount + '" data-attr="add-field" class="removefield">Remove</a><button class="add_field button" data-id="' + colCount + '-1">Add Field</button></div><div class="grid-item" data-id="' + colCount + '-2"><input type="hidden" name="emptyGrid_' + colCount + '-2"><a href="/" data-id="' + colCount + '" data-attr="add-field" class="removefield">Remove</a><button class="add_field button" data-id="' + colCount + '-2">Add Field</button></div></div>');
		}
		jQuery('.col-count').val(colCount)
	})
	jQuery('#form-container').delegate('.add_field','click',function(e){

	})
	function deselect(e) {
	  jQuery('.pop').slideFadeToggle(function() {
	    e.removeClass('selected');
	  });    
	}

	jQuery(function() {
	  jQuery('#form-container').delegate('.add_field','click',function(e){
	    if(jQuery(this).hasClass('selected')) {
	      deselect(jQuery(this));               
	    } else {
	      jQuery('#select-custom-field').attr('data-id',jQuery(this).attr('data-id'))
	      jQuery(this).addClass('selected');
	      jQuery('.pop').slideFadeToggle();
	    }
	    return false;
	  });	  
	});
	jQuery('.close').on('click', function() {
		jQuery("#select-custom-field").val(jQuery("#select-custom-field option:first").val());
	    deselect(jQuery('.add_field'));
	    return false;
	  });
	  jQuery('#field_submit').on('click',function(){	  	
		let input_type = jQuery("#select-custom-field")
		var data_id = input_type.attr('data-id')
		console.log(data_id)
		var field;
		switch(input_type.val()){
			case 'text':
				field='<a href="/" data-id="' + data_id + '" class="removefield">Remove</a><div class="field-option-wrapper"><input type="text" placeholder="Text" name="field_text_'+ data_id +'"><a style="float: right;" class="options" data-id="' + data_id + '" href="/">Options</a><div class="field-options-text" style="display: none;" data-id="'+ data_id +'"><div><label for="field-label'+data_id+'">Label</label><input type="text" id="field-label'+data_id+'" name="field-label'+data_id+'"></div><div><label for="field-class'+data_id+'">Class</label><input type="text" id="field-class'+data_id+'" name="field-class'+data_id+'"></div><div><label for="field-required'+data_id+'">Required</label><input type="checkbox" id="field-required'+data_id+'" name="field-required'+data_id+'"></div><div><label for="field-value'+data_id+'">Value</label><input type="text" id="field-value'+data_id+'" name="field-value'+data_id+'"></div></div></div>'
			break;
			case 'email':
				field='<a href="/" data-id="' + data_id + '" class="removefield">Remove</a><div class="field-option-wrapper"><input type="email" placeholder="Email" name="field_email_'+ data_id +'"><a style="float: right;" class="options" data-id="' + data_id + '" href="/">Options</a><div class="field-options-email" style="display: none;" data-id="'+ data_id +'"><div><label for="field-label'+data_id+'">Label</label><input type="text" id="field-label'+data_id+'" name="field-label'+data_id+'"></div><div><label for="field-class'+data_id+'">Class</label><input type="text" id="field-class'+data_id+'" name="field-class'+data_id+'"></div><div><label for="field-required'+data_id+'">Required</label><input type="checkbox" id="field-required'+data_id+'" name="field-required'+data_id+'"></div><div><label for="field-value'+data_id+'">Value</label><input type="text" id="field-value'+data_id+'" name="field-value'+data_id+'"></div></div></div>'
			break;
			case 'checkbox':
				field='<a href="/" data-id="' + data_id + '" class="removefield">Remove</a><div class="field-option-wrapper"><div class="wrapper_field_checkbox" data-id="'+ data_id +'"><div class="container_field_checkbox_'+ data_id +'" data-id="1"><input disabled type="checkbox" name="field_checkbox_'+ data_id +'_1"><label for="field_checkbox_value_'+ data_id +'_1">Value</label><input type="text" id="field_checkbox_value_'+ data_id +'_1" name="field_checkbox_value_'+ data_id +'_1"></div></div><a href="/" class="add_new_checkbox" data-id="'+ data_id +'">+</a><a style="float: right;" class="options" data-id="'+ data_id +'" href="/">Options</a><div class="field-options-checkbox" style="display: none;" data-id="'+ data_id +'"><div><label for="field-label'+ data_id +'">Label</label><input type="text" id="field-label'+ data_id +'" name="field-label'+ data_id +'"></div><div><label for="field-class'+ data_id +'">Class</label><input type="text" id="field-class'+ data_id +'" name="field-class'+ data_id +'"></div></div></div>'
			break;
			case 'radio':
				field='<a href="/" data-id="' + data_id + '" class="removefield">Remove</a><div class="field-option-wrapper"><div class="wrapper_field_radio" data-id="'+ data_id +'"><div class="container_field_radio_'+ data_id +'" data-id="1"><input disabled type="radio" name="field_radio_'+ data_id +'_1"><label for="field_radio_value_'+ data_id +'_1">Value</label><input type="text" id="field_radio_value_'+ data_id +'_1" name="field_radio_value_'+ data_id +'_1"></div></div><a href="/" class="add_new_radio" data-id="'+ data_id +'">+</a><a style="float: right;" class="options" data-id="'+ data_id +'" href="/">Options</a><div class="field-options-radio" style="display: none;" data-id="'+ data_id +'"><div><label for="field-label'+ data_id +'">Label</label><input type="text" id="field-label'+ data_id +'" name="field-label'+ data_id +'"></div><div><label for="field-class'+ data_id +'">Class</label><input type="text" id="field-class'+ data_id +'" name="field-class'+ data_id +'"></div><div><label for="field-required'+ data_id +'">Required</label><input type="radio" id="field-required'+ data_id +'" name="field-required'+ data_id +'"></div></div></div>'
			break;
			case 'calendar':
				field='<a href="/" data-id="' + data_id + '" class="removefield">Remove</a><div class="field-option-wrapper"><input type="date" placeholder="Calendar" name="field_date_'+ data_id +'"><a style="float: right;" class="options" data-id="' + data_id + '" href="/">Options</a><div class="field-options-date" style="display: none;" data-id="'+ data_id +'"><div><label for="field-label'+data_id+'">Label</label><input type="text" id="field-label'+data_id+'" name="field-label'+data_id+'"></div><div><label for="field-class'+data_id+'">Class</label><input type="text" id="field-class'+data_id+'" name="field-class'+data_id+'"></div><div><label for="field-required'+data_id+'">Required</label><input type="checkbox" id="field-required'+data_id+'" name="field-required'+data_id+'"></div></div></div>'
			break;
			case 'upload':
				field='<a href="/" data-id="' + data_id + '" class="removefield">Remove</a><div class="field-option-wrapper"><label class="file"><input name="field_file_'+ data_id +'" type="file" id="file" aria-label="File browser example"><span class="file-custom"></span><input type="hidden" name="field_file_' + data_id + '" ></label><a style="float: right;" class="options" data-id="' + data_id + '" href="/">Options</a><div class="field-options-file" style="display: none;" data-id="'+ data_id +'"><div><label for="field-label'+data_id+'">Label</label><input type="text" id="field-label'+data_id+'" name="field-label'+data_id+'"></div><div><label for="field-class'+data_id+'">Class</label><input type="text" id="field-class'+data_id+'" name="field-class'+data_id+'"></div><div><label for="field-required'+data_id+'">Required</label><input type="checkbox" id="field-required'+data_id+'" name="field-required'+data_id+'"></div></div></div>'
			break;
			default:
		}
		jQuery('#form-container div[class="grid-item"][data-id="' +data_id+ '"]').empty()
		jQuery('#form-container div[class="grid-item"][data-id="' +data_id+ '"]').append(field)
	  	input_type.val(jQuery("#select-custom-field option:first").val());
	  	deselect(jQuery('.add_field'));
	    return false;
	  })
	jQuery.fn.slideFadeToggle = function(easing, callback) {
	  return this.animate({ opacity: 'toggle', height: 'toggle' }, 'fast', easing, callback);
	};
	jQuery('#form-container').delegate('.options','click',function(e){
		e.preventDefault();
		let data_id = jQuery(this).attr('data-id');
		jQuery('div[class^="field-options-"][data-id="' + data_id + '"]').toggle("show")
	})
	jQuery('#form-container').delegate('.add_new_checkbox','click', function(e){
		e.preventDefault()
		let data_id = jQuery(this).attr('data-id')
		let checkbox_id = parseInt(jQuery(".container_field_checkbox_"+data_id).last().attr('data-id'))+1
		let new_checkbox = '<div class="container_field_checkbox_'+data_id+'" data-id="'+checkbox_id+'"><input disabled type="checkbox" name="field_checkbox_'+data_id+'_'+checkbox_id+'"><label for="field_checkbox_value_'+data_id+'_'+checkbox_id+'">Value</label><input type="text" id="field_checkbox_value_'+data_id+'_'+checkbox_id+'" name="field_checkbox_value_'+data_id+'_'+checkbox_id+'"></div>'
		jQuery('.wrapper_field_checkbox[data-id="'+data_id+'"]').append(new_checkbox)
	})
	jQuery('#form-container').delegate('.add_new_radio','click', function(e){
		e.preventDefault()
		let data_id = jQuery(this).attr('data-id')
		let radio_id = parseInt(jQuery(".container_field_radio_"+data_id).last().attr('data-id'))+1
		let new_radio = '<div class="container_field_radio_'+data_id+'" data-id="'+radio_id+'"><input disabled type="radio" name="field_radio_'+data_id+'_'+radio_id+'"><label for="field_radio_value_'+data_id+'_'+radio_id+'">Value</label><input type="text" id="field_radio_value_'+data_id+'_'+radio_id+'" name="field_radio_value_'+data_id+'_'+radio_id+'"></div>'
		jQuery('.wrapper_field_radio[data-id="'+data_id+'"]').append(new_radio)
	})
	jQuery('#form-container').delegate('.removefield','click',function(e){
		e.preventDefault();
		var _this = jQuery(this);
		if(_this.attr('data-attr') == 'add-field'){
			if(jQuery('.grid-item[data-id="'+_this.attr('data-id')+'"]').length){
				jQuery('.grid-item[data-id="'+_this.attr('data-id')+'"]').parent().remove()
			}else{
				jQuery(this).parent().parent().remove()
			}
		}else{
			jQuery('.grid-item[data-id="'+_this.attr('data-id')+'"]').empty().append('<a href="/" data-id="' + _this.attr('data-id') + '" data-attr="add-field" class="removefield">Remove</a><button class="add_field button full-width" data-id="' + _this.attr('data-id') + '">Add Field</button>')

		}
	})
	jQuery('.save-form').on('click',function(){
		jQuery('#vm-form-build input[type="submit"]').trigger('click');
	})
})