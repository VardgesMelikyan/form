<?php
global $wpdb;
$vm_form = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}vm_form WHERE id =".$_GET['id']);
$data = unserialize($vm_form->form);
?>
<input type="hidden" name="form-title" class="form-title" value="<?php echo $vm_form->title ?>">
<input type="hidden" name="form-id" value="<?php echo $vm_form->id ?>">
<?php
	$col = '';
	foreach ($data as $key => $value) {					
		$data_id = $value['id'];
		if(isset($value["field-required".$data_id]) && $value["field-required".$data_id] == 'on'){
			$required = "checked='checked'";
		}else{
			$required = '';
		}
		
		$cols = explode('-',$data_id);		
		if(isset($cols[1]) && $cols[1] == 1){
			echo '<div class="grid-container"><div class="grid-item" data-id="'.$data_id.'">';
		}elseif(isset($cols[1]) && $cols[1] == 2){
			echo '<div class="grid-item" data-id="'.$data_id.'">';	
		}elseif(!isset($cols[1])){
			echo '<div class="grid-container-full"><div class="grid-item" data-id="'.$data_id.'">';
		}		
			switch ($key){
				case 'emptyFull-'.$data_id:
					echo '<input type="hidden" name="emptyFull_'.$data_id.'"><a href="/" data-id="'.$data_id.'" data-attr="add-field" class="removefield">Remove</a><button class="add_field button full-width" data-id="'.$data_id.'">Add Field</button>';
				break;
				case 'emptyGrid-'.$data_id:
					echo '<input type="hidden" name="emptyFull_'.$data_id.'"><a href="/" data-id="'.$data_id.'" data-attr="add-field" class="removefield">Remove</a><button class="add_field button full-width" data-id="'.$data_id.'">Add Field</button>';
				break;
				case 'text-'.$data_id:
					echo '<a href="/" data-id="'.$data_id.'" class="removefield">Remove</a>
					<div class="field-option-wrapper">
						<input type="text" placeholder="Text" name="field_text_'.$data_id.'">
						<a style="float: right;" class="options" data-id="'.$data_id.'" href="/">Options</a>
						<div class="field-options-text" style="display: none;" data-id="'.$data_id.'">
							<div>
								<label for="field-label'.$data_id.'">Label</label>
								<input type="text" id="field-label'.$data_id.'" name="field-label'.$data_id.'" value="'. $value["field-label".$data_id] .'">
							</div>
							<div>
								<label for="field-class'.$data_id.'">Class</label>
								<input type="text" id="field-class'.$data_id.'" name="field-class'.$data_id.'" value="'. $value["field-class".$data_id] .'">
							</div>
							<div>
								<label for="field-required'.$data_id.'">Required</label>
								<input type="checkbox" '.$required.' id="field-required'.$data_id.'" name="field-required'.$data_id.'">
							</div>
							<div>
								<label for="field-value'.$data_id.'">Value</label>
								<input type="text" id="field-value'.$data_id.'" name="field-value'.$data_id.'" value="'. $value["field-value".$data_id] .'">
							</div>
						</div>
					</div>';
				break;
				case 'email-'.$data_id:
					echo '<a href="/" data-id="'.$data_id.'" class="removefield">Remove</a>
					<div class="field-option-wrapper">
						<input type="email" placeholder="Email" name="field_email_'.$data_id.'">
						<a style="float: right;" class="options" data-id="'.$data_id.'" href="/">Options</a>
						<div class="field-options-email" style="display: none;" data-id="'.$data_id.'">
							<div>
								<label for="field-label'.$data_id.'">Label</label>
								<input type="text" id="field-label'.$data_id.'" name="field-label'.$data_id.'" value="'. $value["field-label".$data_id] .'">
							</div>
							<div>
								<label for="field-class'.$data_id.'">Class</label>
								<input type="text" id="field-class'.$data_id.'" name="field-class'.$data_id.'" value="'. $value["field-class".$data_id] .'">
							</div>
							<div>
								<label for="field-required'.$data_id.'">Required</label>
								<input type="checkbox" '.$required.' id="field-required'.$data_id.'" name="field-required'.$data_id.'">
							</div>
							<div>
								<label for="field-value'.$data_id.'">Value</label>
								<input type="text" id="field-value'.$data_id.'" name="field-value'.$data_id.'" value="'. $value["field-value".$data_id] .'">
							</div>
						</div>
					</div>';
				break;
				case 'checkbox-'.$data_id:
			  echo '<a href="/" data-id="'.$data_id.'" class="removefield">Remove</a>
					<div class="field-option-wrapper">
						<div class="wrapper_field_checkbox" data-id="'.$data_id.'">';
						for($i=1; $i <= (int)$value["checkboxCount"]; $i++){
						  echo '<div class="container_field_checkbox_'.$data_id.'" data-id="'.$i.'">
									<input disabled type="checkbox" name="field_checkbox_'.$data_id.'_'.$i.'">
									<label for="field_checkbox_value_'.$data_id.'_'.$i.'">Value</label>
									<input type="text" id="field_checkbox_value_'.$data_id.'_'.$i.'" name="field_checkbox_value_'.$data_id.'_'.$i.'" value="'. $value["field_checkbox_value_".$data_id."_".$i] .'">
								</div>';
						}

				  echo '</div>
						<a href="/" class="add_new_checkbox" data-id="'.$data_id.'">+</a>
						<a style="float: right;" class="options" data-id="'.$data_id.'" href="/">Options</a>
						<div class="field-options-checkbox" style="display: none;" data-id="'.$data_id.'">
							<div>
								<label for="field-label'.$data_id.'">Label</label>
								<input type="text" id="field-label'.$data_id.'" name="field-label'.$data_id.'" value="'. $value["field-label".$data_id] .'">
							</div>
							<div>
								<label for="field-class'.$data_id.'">Class</label>
								<input type="text" id="field-class'.$data_id.'" name="field-class'.$data_id.'" value="'. $value["field-class".$data_id] .'">
							</div>
						</div>
					</div>';
				break;
				case 'radio-'.$data_id:
			  echo '<a href="/" data-id="'.$data_id.'" class="removefield">Remove</a>
					<div class="field-option-wrapper">
						<div class="wrapper_field_radio" data-id="'.$data_id.'">';
						for($i=1; $i <= (int)$value["radioCount"]; $i++){
						  echo '<div class="container_field_radio_'.$data_id.'" data-id="'.$i.'">
									<input disabled type="radio" name="field_radio_'.$data_id.'_'.$i.'">
									<label for="field_radio_value_'.$data_id.'_'.$i.'">Value</label>
									<input type="text" id="field_radio_value_'.$data_id.'_'.$i.'" name="field_radio_value_'.$data_id.'_'.$i.'" value="'. $value["field_radio_value_".$data_id."_".$i] .'">
								</div>';
						}

				  echo '</div>
						<a href="/" class="add_new_radio" data-id="'.$data_id.'">+</a>
						<a style="float: right;" class="options" data-id="'.$data_id.'" href="/">Options</a>
						<div class="field-options-radio" style="display: none;" data-id="'.$data_id.'">
							<div>
								<label for="field-label'.$data_id.'">Label</label>
								<input type="text" id="field-label'.$data_id.'" name="field-label'.$data_id.'" value="'. $value["field-label".$data_id] .'">
							</div>
							<div>
								<label for="field-class'.$data_id.'">Class</label>
								<input type="text" id="field-class'.$data_id.'" name="field-class'.$data_id.'" value="'. $value["field-class".$data_id] .'">
							</div>
							<div>
								<label for="field-required'.$data_id.'">Required</label>
								<input type="checkbox" '.$required.' id="field-required'.$data_id.'" name="field-required'.$data_id.'">
							</div>
						</div>
					</div>';
				break;
				case 'date-'.$data_id:
					echo '<a href="/" data-id="'.$data_id.'" class="removefield">Remove</a>
					<div class="field-option-wrapper">
						<input type="date" placeholder="Calendar" name="field_date_'.$data_id.'">
						<a style="float: right;" class="options" data-id="'.$data_id.'" href="/">Options</a>
						<div class="field-options-date" style="display: none;" data-id="'.$data_id.'">
							<div>
								<label for="field-label'.$data_id.'">Label</label>
								<input type="text" id="field-label'.$data_id.'" name="field-label'.$data_id.'" value="'. $value["field-label".$data_id] .'">
							</div>
							<div>
								<label for="field-class'.$data_id.'">Class</label>
								<input type="text" id="field-class'.$data_id.'" name="field-class'.$data_id.'" value="'. $value["field-class".$data_id] .'">
							</div>
							<div>
								<label for="field-required'.$data_id.'">Required</label>
								<input type="checkbox" '.$required.' id="field-required'.$data_id.'" name="field-required'.$data_id.'">
							</div>
						</div>
					</div>';
				break;
				case 'file-'.$data_id:
					echo '<a href="/" data-id="'.$data_id.'" class="removefield">Remove</a>
						  <div class="field-option-wrapper">
							  <label class="file">
								  <input type="file" id="file" aria-label="File browser example">
								  <span class="file-custom"></span>
								  <input type="hidden" name="field_file_'.$data_id.'" >
							  </label>
							  <a style="float: right;" class="options" data-id="'.$data_id.'" href="/">Options</a>
							  <div class="field-options-file" style="display: none;" data-id="'.$data_id.'">
								  <div>
									  <label for="field-label'.$data_id.'">Label</label>
									  <input type="text" id="field-label'.$data_id.'" name="field-label'.$data_id.'">
								  </div>
								  <div>
									  <label for="field-class'.$data_id.'">Class</label>
									  <input type="text" id="field-class'.$data_id.'" name="field-class'.$data_id.'">
								  </div>
								  <div>
									  <label for="field-required'.$data_id.'">Required</label>
									  <input type="checkbox" id="field-required'.$data_id.'" name="field-required'.$data_id.'">
								  </div>
							  </div>
						  </div>';
				break;
				default:
			}
		if(isset($cols[1]) && $cols[1] == 1){
			echo '</div>';
		}elseif(isset($cols[1]) && $cols[1] == 2){
			echo '</div></div>';	
		}elseif(!isset($cols[1])){
			echo '</div></div>';
		}		
	}
	echo '<input type="hidden" name="fields-count" class="fields-count" value="'.$vm_form->colCount.'">';
?>
