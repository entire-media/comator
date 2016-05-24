<?php

#########################################################
#########################################################
#####                                               #####
#####    	OUTPUT FORMS                              #####
#####                                               #####
#########################################################
#########################################################

function print_form ($formdata) {
	if (isset($formdata)){
		print "<div class='form'>";
		foreach ($formdata AS $key => $data){
			
			
			if (isset($data['label_head'])) {
				print "<div class='form_label_head ";
				if (isset($data['label'])) print "label";
				print "'>".$data['label_head']."</div>";
			}
					
			if (isset($data['label_key_head'])) {
				print "<div class='form_label_key_head ";
				if (isset($data['style']['class'])) print $data['style']['class'];
				if (isset($data['label'])) print " label";
				print "'>".$data['label_key_head']."</div>";
			}
			if (isset($data['label_value_head'])) {
				print "<div class='form_label_value_head ";
				if (isset($data['style']['class'])) print $data['style']['class'];
				if (isset($data['label'])) print "label";
				print "'>".$data['label_value_head']."</div>";
			}
			
			if (!isset($data['value'])) $data['value'] = NULL;
			if (!isset($data['id'])) $data['id'] = $key;
			if (!isset($data['class'])) $data['class'] = $key;
			$data['style']['id']  = $data['id'];
			
			if (isset($data['style']['class'])) $data['style']['class'] .= " ".$data['class'];
			else $data['style']['class'] = $data['class'];
			
			if ($data['type'] != 'hidden'){
				print "<div class='form_element ";
				if (isset($data['parent_class'])) print $data['parent_class']." ";
				if (isset($data['settings']['alert'])) {
					if (is_array($data['settings']['alert'])) print $data['settings']['alert']['type']." ";
					else print $data['settings']['alert']." ";
				}
				print "'>";
				if (isset($data['label'])){
					print "
					<div class='form_field_label'>
						<label for='".$data['id']."'>".$data['label']."</label>
					</div>";
				}
				print "<div class='form_field'>";
			}
			
			if (isset($data['label_before'])){
				print "<div class='form_label_before'>".$data['label_before']."</div>";
			}
			if (!isset($data['settings'])) $data['settings'] = NULL;
			
			switch ($data['type']){
				case 'hidden':
					print form_hidden($key, $data['style'], $data['value']);
					break;
				case 'int':
					print form_text($key, $data['style'], $data['value'], $data['settings']);
					break;
				case 'text':
					print form_text($key, $data['style'], $data['value'], $data['settings']);
					break;
				case 'textarea':
					print form_textarea($key, $data['style'], $data['value'], $data['settings']);
					break;
				case 'checkbox':
					print form_checkbox($key, $data['style'], $data['value'], $data['settings']);
					break;
				case 'select':
					print form_select($key, $data['style'], $data['value'], $data['settings']);
					break;
				case 'radio':
					print form_radiogroup($key, $data['style'], $data['value'], $data['settings']);
					break;
				case 'password':
					print form_password($key, $data['style'], $data['value'], $data['settings']);
					break;
				case 'filter-date':
					filter_date($data['value'], $data['settings']);
					break;
				case 'datepicker':
					datepicker($data['value'], $data['settings']);
					break;
				case 'input_add':
					print form_text("key_".$key, $data['style'], $data['value']['key'], $data['settings']);
					print form_text("value_".$key, $data['style'], $data['value']['value'], $data['settings']);
					
					if (isset($data['settings']['number'])) print "<i class='icon-inputadd' id='".$data['settings']['number']."'></i>";
					break;
				case 'upload':
					print form_upload($key);
					break;
					
			}
			if (isset($data['label_after'])) print "<div class='form_label_after'>".$data['label_after']."</div>";
			if ($data['type'] != 'hidden'){
				print "
					</div>
				</div>";
				if (isset($data['settings']['alert'])) {
					if (is_array($data['settings']['alert'])) {
						if (isset($data['settings']['alert']['text'])) print print_alert($data['settings']['alert']['type'], $data['settings']['alert']['label'], $data['settings']['alert']['text']);
						else print print_alert($data['settings']['alert']['type'], v('CMT_HEADLINE_'.$data['settings']['alert']['label'].'_ERROR'), v('CMT_TEXT_'.$data['settings']['alert']['label'].'_ERROR'));
					} else {
						print print_alert($data['settings']['alert'], v('CMT_HEADLINE_REQUIRED_ERROR'), v('CMT_TEXT_REQUIRED_ERROR'));
					}
				}
			}
		}
		print "</div>";
	}
}

function form_hidden($name, $style, $value=''){
	$output = "<input ";
	if (isset($style['id'])) $output .= "id='".$style['id']."' ";
	if (isset($style['class'])) $output .= "class='".$style['class']."' ";
	$output .= "type='hidden' name='".$name."' value='".$value."' >";
	return $output;
}

function form_text ($name, $style, $value='', $settings) {
	$output = "<input ";
	if (isset($style['id'])) $output .= "id='".$style['id']."' ";
	if (isset($style['class'])) $output .= "class='".$style['class']."' ";
	if (isset($style['width'])) $output .= "style=' width:".$style['width']."px; ' ";
	$output .= "type='text' name='".$name."' value='".$value."' ";
	if (isset($settings['readonly'])){
		if ($settings['readonly']) $output .= "readonly='readonly' ";
	}
	if (isset($settings['required'])){
		if ($settings['required']) $output .= "required='required' ";
	}
	if (isset($settings['attributes'])){
		foreach ($settings['attributes'] AS $key => $val){
			$output .= $key."='".$val."' ";
		}
	}
	$output .=">";
	return $output;
}

function form_textarea ($name, $style, $value='', $settings) {
	$output = "<textarea ";
	if (isset($style['id'])) $output .= "id='".$style['id']."' ";
	if (isset($style['class'])) $output .= "class='".$style['class']."' ";
	if (isset($style['width'])) $output .= "style=' width:".$style['width']."px; ' ";
	if (isset($style['rows'])) $output .= "rows='".$style['rows']."'";
	$output .= "name='".$name."' ";
	if (isset($settings['readonly'])){
		if ($settings['readonly']) $output .= "readonly='readonly' ";
	}
	if (isset($settings['required'])){
		if ($settings['required']) $output .= "required='required' ";
	}
	if (isset($settings['attributes'])){
		foreach ($settings['attributes'] AS $key => $val){
			$output .= $key."='".$val."' ";
		}
	}
	$output .=">".$value."</textarea>";
	return $output;
}

function form_checkbox ($name, $style, $selected, $settings) {
	foreach ($settings['data'] AS $key => $text) {
  	$output = "<input ";
		if (isset($style['id'])) $output .= "id='".$style['id']."_".$key."'  ";
		if (isset($style['class'])) $output .= "class='".$style['class']."' ";
		$output .= "value='".$key."' type='checkbox' name='".$name."' ";
  	if ($selected == true) $output.= "checked='checked' ";
		if (isset($settings['readonly'])){
			if ($settings['readonly']) $output .= "disabled='disabled' ";
		}
		if (isset($settings['attributes'])){
			foreach ($settings['attributes'] AS $key_attr => $val_attr){
				$output .= $key_attr."='".$val_attr."' ";
			}
		}
  	$output .= "><label for='".$style['id']."_".$key."' ";
		if (isset($style['cols'])) $output .= "class='".$style['cols']."'";
		else $output .= "class='label-2_of_7'";
		if (isset($style['width'])) $output .= "style=' width:".$style['width']."px;' ";
		$output.= ">".$text."</label>";
  }
  return $output;
}

function form_select ($name, $style, $selected, $settings) {
	$output = "<select name='".$name."' ";
	if (isset($style['id'])) $output .= "id='".$style['id']."' ";
	if (isset($style['class'])) $output .= "class='".$style['class']."' ";
	if (isset($style['width'])) $output .= "style=' width:".$style['width']."px; ' ";
	if (isset($settings['attributes'])){
		foreach ($settings['attributes'] AS $key_attr => $val_attr){
			$output .= $key_attr."='".$val_attr."' ";
		}
	}
	$output .= ">";
	if (isset($settings['data'])){
		foreach ($settings['data'] AS $key => $text) {
	  	$output .= "<option value='".$key."' ";
	  	if ($key == $selected) $output.= "selected='selected' ";
			if (isset($settings['readonly'])){
				if ($settings['readonly']) $output .= "disabled='disabled' ";
			}
	  	$output .= ">".$text."</option>";
	  }
	}
  $output .="</select>";
  return $output;
}


function form_radiogroup ($name, $style, $selected, $settings) {
	$output = "";
	if (isset($settings['data'])){
		$i = 1;
		foreach ($settings['data'] AS $key => $text) {
			$output .= "<input id='".$name."_".$key."' value='".$key."' type='radio' name='".$name."' ";
			if (isset($style['class'])) $output .= "class='".$style['class']."' ";
			if (!$selected && $i == 1) $output.= "checked='checked' ";
	  	if ($key == $selected) $output.= "checked='checked' ";
			if (isset($settings['readonly'])){
				if ($settings['readonly']) $output .= "readonly='readonly' ";
			}
			if (isset($settings['required'])){
				if ($settings['required']) $output .= "required='required' ";
			}
			if (isset($settings['attributes'])){
				foreach ($settings['attributes'] AS $key => $val){
					$output .= $key."='".$val."' ";
				}
			}
  		$output.= "><label for='".$name."_".$key."' ";
			if (isset($style['cols'])) $output .= "class='".$style['cols']."'";
			else $output .= "class='label-2_of_7'";
  		if (isset($style['width'])) $output .= "style=' width:".$style['width']."px;' ";
  		$output.= ">".$text."</label>";
  		$i++;
		}
	}
	return $output;
}

function form_password ($name, $style, $value='', $settings) {
	$output = "<input ";
	if (isset($style['id'])) $output .= "id='".$style['id']."' ";
	if (isset($style['class'])) $output .= "class='".$style['class']."' ";
	if (isset($style['width'])) $output .= "style=' width:".$style['width']."px; ' ";
	$output .= "type='password' name='".$name."' value='".$value."' ";
	if (isset($settings['readonly'])){
		if ($settings['readonly'] ) $output .= "readonly='readonly' ";
	}
	if (isset($settings['required'])){
		if ($settings['required']) $output .= "required='required' ";
	}
	if (isset($settings['attributes'])){
		foreach ($settings['attributes'] AS $key => $val){
			$output .= $key."='".$val."' ";
		}
	}
	$output .=">";
	return $output;
}

function form_upload($name) {
	return "<input id='".$name."' name='".$name."[]' type='file' multiple>";
}

function datepicker($value = NULL, $settings){
	if (!isset($value['date'])) $value['date'] = date('d.m.Y');
	if (!isset($value['time'])) $value['time'] = date('H:i');
	print form_text('date', array('class' => 'datepicker'), $value['date'], $settings);
	print "<i class='icon-date'></i>";
	print "</div>
					<div class='form_field_label time'>
						<label for='time'>".v('CMT_TIME')."</label>
					</div>
				<div class='form_field'>";
	print form_text('time', array('class' => 'time'), $value['time'], $settings);
	print "<i class='icon-time'></i>";
}

function filter_date($value = NULL, $settings){
	$days = array();
	for ($i = 1; $i <= 31; $i++){
		$days['data'][str_pad($i, 2, 0, STR_PAD_LEFT)] = str_pad($i, 2, 0, STR_PAD_LEFT);
	}
	$months = array();
	for ($i = 1; $i <= 12; $i++){
		$months['data'][str_pad($i, 2, 0, STR_PAD_LEFT)] = str_pad($i, 2, 0, STR_PAD_LEFT);
	}
	$years_from = array();
	for ($i = 1970; $i <= date('Y'); $i++){
		$years_from['data'][$i] = $i;
	}
	$years_to = array();
	for ($i = 1970; $i <= date('Y')+20; $i++){
		$years_to['data'][$i] = $i;
	}
	
	if (isset($settings['attributes'])){
		$days['attributes'] = $settings['attributes'];
		$months['attributes'] = $settings['attributes'];
		$years_from['attributes'] = $settings['attributes'];	
		$years_to['attributes'] = $settings['attributes'];
	}
	if (!isset($value['from_date']['day'])) $value['from_date']['day'] = date('d');
	if (!isset($value['from_date']['month'])) $value['from_date']['month'] = date('m');
	if (!isset($value['from_date']['year'])) $value['from_date']['year'] = date('Y')-2;
	if (!isset($value['to_date']['day'])) $value['to_date']['day'] = date('d');
	if (!isset($value['to_date']['month'])) $value['to_date']['month'] = date('m');
	if (!isset($value['to_date']['year'])) $value['to_date']['year'] = date('Y')+1;
	
	print form_select('from_day', array('class' => 'day'), $value['from_date']['day'], $days);
	print "&nbsp;&nbsp;.";
	print form_select('from_month', array('class' => 'month'), $value['from_date']['month'], $months);
	print "&nbsp;&nbsp;.";
	print form_select('from_year', array('class' => 'year'), $value['from_date']['year'], $years_from);
	print "&nbsp;&nbsp;".v('CMT_TO');
	print form_select('to_day', array('class' => 'day'), $value['to_date']['day'], $days);
	print "&nbsp;&nbsp;.";
	print form_select('to_month', array('class' => 'month'), $value['to_date']['month'], $months);
	print "&nbsp;&nbsp;.";
	print form_select('to_year', array('class' => 'year'), $value['to_date']['year'], $years_to);
}
?>