<?php

class all_around_visual_elements {

	static $last_width=0;

	static public function wrap_it ($buffer, &$wrapper, $class='', $id='') {
		if (!$wrapper) return $buffer;
		if (is_array($wrapper) && count($wrapper)==0) return;
		if ($class!='') $class=' '.$class;
		$style='';
		foreach ($wrapper as $var => $val) {
				if ($var=='span' || $var=='span-inline' || $var=='group') continue;
				if ($style!="") $style.=" ";
				if ($var=='empty_wrapper' && !isset($wrapper['padding']) && !isset($wrapper['padding-top'])) {
					$var='padding-top';
					$val='11px';
					$style.=$var.': '.$val.'; ';
					$var='margin-bottom';
					$val='20px';
					$style.=$var.': '.$val.';';
					continue;
				}
				$style.=$var.': '.$val.';';
		}
		//echo '<pre>'; print_r($wrapper); echo '</pre>';
		if (isset($wrapper['group'])) $class.=' '.$wrapper['group'];
		if ($style!='') $style=' style="'.$style.'"';
		$pbuffer='<div class="fbuilder_control'.$class.'"'.$style.'>';
		$span_id='';
		if ($id!='') $span_id=' id="'.$id.'_span"';
		if (isset($wrapper['span'])) $pbuffer.='<span'.$span_id.'>'.$wrapper['span'].':</span>';
		if (isset($wrapper['span-inline'])) $pbuffer.='<span class="inline">'.$wrapper['span-inline'].':</span>';
		$pbuffer.=$buffer.'</div>';
		return $pbuffer;
	}
	
	static public function generate_style ($style, $auto_width=TRUE) {
		$buffer='';
		if ($style===NULL) $style=array();
		if (isset($style['auto_width'])) $auto_width=$style['auto_width'];
		if ($auto_width && !isset($style['width'])) $style['width']='200px';
		if (isset($style['width'])) self::$last_width=intval($style['width']);
		else self::$last_width=0;
		foreach ($style as $var => $val) {
			if ($buffer!='') $buffer.=' ';
			$buffer.=$var.': '.$val.';';
		}
		if ($buffer!='') return ' style="'.$buffer.'"';
		return '';
	}

	static public function generate_input ($name, $value, $wrapper=NULL, $style=NULL) {
		$style_string=self::generate_style($style);
		$value=str_replace('"', '&quot;', $value);
		$buffer='<input class="fbuilder_input" name="'.$name.'" id="'.$name.'" value="'.$value.'"'.$style_string.' />';
		if (is_array($wrapper)) $buffer=self::wrap_it($buffer, $wrapper, '', $name);
		return $buffer;
	}
	static public function generate_hidden ($name, $value) {
		return '<input type="hidden" name="'.$name.'" id="'.$name.'" value="'.$value.'" />';
	}
	static public function generate_textarea ($name, $value, $wrapper=NULL, $style=NULL) {
		$style_string=self::generate_style($style);
		$buffer='<textarea class="fbuilder_textarea" name="'.$name.'" id="'.$name.'"'.$style_string.'>'.$value.'</textarea>';
		if (is_array($wrapper)) $buffer=self::wrap_it($buffer, $wrapper, '', $name);
		return $buffer;
	}
	static public function generate_checkbox ($label, $name, $value, $wrapper=NULL, $style=NULL) {
		$style_string=self::generate_style($style);
		$active=''; $fvalue=0;
		if ($value) $active=' active'; $fvalue=1;
		$buffer='<div class="fbuilder_checkbox'.$active.'"></div><input class="fbuilder_checkbox_input" name="'.$name.'" id="'.$name.'" style="display:none;" value="'.$value.'" /><div class="fbuilder_checkbox_label"><label for="check">'.$label.'</label></div><div style="clear:both;"></div>';
		if (is_array($wrapper)) $buffer=self::wrap_it($buffer, $wrapper, '', $name);
		return $buffer;
	}
	static public function generate_image($name, $value, $wrapper=NULL, $style=NULL, $default='') {
		$style_string=self::generate_style($style);
		$input_width=self::$last_width-80;
		$max_width_image=self::$last_width-11;
		$img_src=$value;
		$input_value=$value;
		if ($img_src=='') $img_src=$default;
		$buffer='<div id="'.$name.'_holder" class="fbuilder_image_holder"'.$style_string.'><img alt="" src="'.$img_src.'" style="max-width:'.$max_width_image.'px; max-height:153px;" id="'.$name.'_img"></div><div class="fbuilder_image_input" style="width: '.$input_width.'px;"><input id="'.$name.'" class="fbuilder_input" value="'.$input_value.'" name="'.$name.'"></div><a class="fbuilder_image_button fbuilder_gradient_primary" data-input="'.$name.'" html="content">Upload</a><div style="clear:both;"></div>';
		if (is_array($wrapper)) $buffer=self::wrap_it($buffer, $wrapper, '', $name);
		return $buffer;
	}
	static public function generate_number($name, $value, $min, $max, $unit, $wrapper=NULL, $style=NULL) {
		if ($style!=NULL) if (isset($style['width'])) {
			$width=intval($style['width']);
			$style['width']=$width-60;
			$style['width']=$style['width'].'px';
		}
		$style_string=self::generate_style($style);
		$buffer='<div class="fbuilder_number_bar" data-min="'.$min.'" data-max="'.$max.'" data-std="'.$value.'" data-unit="'.$unit.'"'.$style_string.'></div><input class="fbuilder_number_amount" name="'.$name.'" id="fbuilder_number_bar_'.$name.'" value="'.$value.'" /><span class="fbuilder_number_span">&nbsp;'.$unit.'</span><div style="clear:both;"></div>';
		if (is_array($wrapper)) $buffer=self::wrap_it($buffer, $wrapper, 'fbuilder_numberbar', $name);
		return $buffer;
	}
	static public function generate_color($name, $value, $wrapper=NULL, $style=NULL) {
		$style_string=self::generate_style($style);
		$buffer='<div class="fbuilder_color_wrapper"'.$style_string.'><input class="fbuilder_color fbuilder_input" name="'.$name.'" id="fbuilder_color_'.$name.'" value="'.$value.'" /><div class="fbuilder_color_display"></div><div class="fbuilder_colorpicker"></div></div>';
		if (is_array($wrapper)) $buffer=self::wrap_it($buffer, $wrapper, '', $name);
		return $buffer;
	}
	static public function generate_listbox($name, $value, $list, $wrapper=NULL, $style=NULL) {
		$style_string=self::generate_style($style);
		$ul_width=self::$last_width;
		$span_width=self::$last_width-56;
		$li_width=self::$last_width-20;
		$buffer='<div class="fbuilder_select fbuilder_gradient" data-name="'.$name.'"'.$style_string.'><input type="hidden" style="display:none;" name="'.$name.'" id="'.$name.'" value="'.$value.'"><span style="width: '.$span_width.'px; height: 14px;">'.$list[$value].'</span><div class="drop_button"></div><ul style="display: none; width: '.$ul_width.'px;">';
		foreach ($list as $var => $val) $buffer.='<li><a'.($var==$value ? ' class="selected"' : '').' data-value="'.$var.'" style="width: '.$li_width.'px;">'.$val.'</a></li>';
		$buffer.='</ul></div><div class="clear"></div>';
		if (is_array($wrapper)) $buffer=self::wrap_it($buffer, $wrapper, '', $name);
		return $buffer;
	}
	static public function generate_button($name, $value, $type, $wrapper=NULL, $style=NULL, $clear=FALSE, $href='') {
		$style_string=self::generate_style($style);
		$classes='fbuilder_gradient fbuilder_button fbuilder_toggle left';
		if ($type=='black_clear') $classes='fbuilder_gradient fbuilder_button fbuilder_toggle_clear left';
		if ($type=='blue') $classes='fbuilder_gradient_primary fbuilder_button fbuilder_save left';
		if ($type=='blue_clear') $classes='fbuilder_gradient_primary fbuilder_button fbuilder_save left';
		if ($href!='') $href=' href="'.$href.'"';
		$buffer='<a'.$href.' id="'.$name.'" class="'.$classes.'"'.$style_string.'>'.$value.'</a>';
		if ($clear) $buffer.='<div class="clear"></div>';
		if (is_array($wrapper)) $buffer=self::wrap_it($buffer, $wrapper, '', $name);
		return $buffer;
	}
	static public function generate_collapsible ($title, $content, $style=NULL, $state=FALSE, $content_style='') {
		$style_string=self::generate_style($style, FALSE);
		if ($state) {
			if ($content_style!='') $content_style=' '.$content_style;
			$divstyle=' style="display: block;'.$content_style.'" ';
			$active=' active';
			$plus='-';
		} else {
			$divstyle='';
			if ($content_style!='') $divstyle=' style="'.$content_style.'"';
			$active='';
			$plus='+';
		}
		return '<div class="fbuilder_collapsible"'.$style_string.'><div class="fbuilder_gradient fbuilder_collapsible_header">'.$title.'<span class="fbuilder_collapse_trigger'.$active.'">'.$plus.'</span></div><div class="fbuilder_collapsible_content"'.$divstyle.'>'.$content.'</div></div>';
	}
	static public function generate_sortable ($name, $list, $style=NULL, $li_class='') {
		$style_string=self::generate_style($style, FALSE);
		$buffer='<ul id="'.$name.'" class="sortable"'.$style_string.'>';
		if ($li_class!='') $li_class=' '.$li_class;
		foreach ($list as $line) $buffer.='<li class="ui-state-default'.$li_class.'">'.$line.'</li>';
		$buffer.='</ul>';
		return $buffer;
	}
	static public function generate_table ($rows, $header=NULL, $style=NULL, $td_style=NULL) {
		$style_string=self::generate_style($style);
		$buffer='<table class="fbuilder_table"'.$style_string.'>';
		if ($header) {
			$buffer.='<thead><tr class="fbuilder_gradient">';
			foreach ($header as $item) $buffer.='<th>'.$item.'</th>';
			$buffer.='</tr></thead>';
		}
		$buffer.='<tbody>';
		$i=0;
		foreach ($rows as $row) {
			$buffer.='<tr>';
			$j=0;
			foreach ($row as $item) {
				$istyle='';
				if ($td_style!=NULL) if (isset($td_style[$i][$j])) $istyle=' '.$td_style[$i][$j];
				$buffer.='<td'.$istyle.'>'.$item.'</td>';
				$j++;
			}
			$buffer.='</tr>';
			$i++;
		}
		$buffer.='</tbody>';
		if ($header) {
			$buffer.='<tfoot><tr class="fbuilder_gradient">';
			foreach ($header as $item) $buffer.='<th>'.$item.'</th>';
			$buffer.='</tr></tfoot>';
		}
		$buffer.='</table>';
		return $buffer;
	}
	static public function generate_form_layout($left, $right) {
		$buffer='<div style="margin-right: 300px;"><div style="float: left; width: 100%; padding:0; ">'.$left.'</div>';
		$buffer.='<div style="float: right; margin-right: -300px; width: 280px; padding:0;">'.$right.'</div></div>';
		return $buffer;
	}
	static public function generate_form ($content, $name, $script="", $method="", $file_upload=FALSE) {
		if ($script!="") $script=' action="'.$script.'"';
		if ($method!="") $method=' method="'.$method.'"';
		$enctype='';
		if ($file_upload) $enctype=' enctype="multipart/form-data"';
		return '<form name="'.$name.'" id="'.$name.'"'.$script.$method.$file_upload.'>'.$content.'</form>';
	}
	static public function generate_div($id, $content, $style) {
		$style_string=self::generate_style($style, FALSE);
		return '<div id="'.$id.'"'.$style_string.'>'.$content.'</div>';
	}
}


?>