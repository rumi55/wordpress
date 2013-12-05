<?php

class all_around_mvc_controller { // main, switch
	public $model, $view, $wrapper, $main_object, $ajax_call;

	function __construct(all_around_wrapper_admin &$wrapper, all_around_main_class &$main_object) {
		$this->wrapper=$wrapper;
		$this->main_object=$main_object;
		
		$this->view = new all_around_mvc_view($this, $wrapper, $main_object);
		$this->model = new all_around_mvc_model($this, $wrapper, $main_object);
		$this->view->set_model($this->model);
		$this->model->set_view($this->view);
		
		$this->ajax_call=0;

		if ($this->main_object->mode=='backend') {
			$this->wrapper->add_ajax_hook('all_around_add_subitem', array(&$this, 'ajax_add_subitem'));
			$this->wrapper->add_ajax_hook('all_around_get_custom_form', array(&$this, 'ajax_get_custom_form'));
			$this->wrapper->add_ajax_hook('all_around_add_subitem_from_category', array(&$this, 'ajax_add_subitem_from_category'));
			$this->wrapper->add_ajax_hook($this->wrapper->ajax_save_handler, array(&$this, 'ajax_save'));
			$this->wrapper->add_ajax_hook($this->wrapper->ajax_preview_handler, array(&$this, 'ajax_preview'));
		} else {
			//$this->wrapper->frontend_callback=array(&$this, 'frontend_function');
		}
	}
	
	function frontend_header_function($ids) {
		//print_r($ids);exit;
		$buffer='';
		foreach ($ids as $id) {
			$this->model->load($id);
			$buffer.=$this->view->generate_frontend_javascript($id);
		}
		return $buffer;
	}
	
	function frontend_body_function($id) {
		//$buffer = '============ '.$id.' ===============';
		$this->model->load($id);
		$buffer=$this->view->frontend_body_function($id);
		return $buffer;
	}
	
	function generate_backend_javascript($with_wrapper=0) {
		return $this->view->generate_backend_javascript();
	}
	
	function ajax_add_subitem_from_category() {
		$this->ajax_call=1;
		if (!isset($_POST['category'])) $this->ajax_return(0, 'No category specified.');

		$count=intval($_POST['count']);
		$rarr=array();
		$category=intval($_POST['category']);
		$arr=$this->wrapper->get_category_posts($category);
		//$buffer='cat='.$category;
		
		$i=0;
		foreach ($arr as $post) {
			$this->model->set_default_values_from_post(0, $post);
			$rarr['data'.$i] = $this->view->generate_html_form_part('empty_item', $count);
			$count++;
			$i++;
		}
		
		$this->ajax_return(1, $rarr);
	}

	function ajax_add_subitem() {
		$this->ajax_call=1;
		$id=intval($_POST['count']);
		if (isset($_POST['from_post'])) {
			$pid=intval($_POST['from_post']);
			$this->model->set_default_values_from_post($pid);
		}
		$r = $this->view->generate_html_form_part('empty_item', $id);
		$this->ajax_return(1, $r);
	}
	function ajax_get_custom_form() {
		// item_id & custom_form & sub_item_id
		$generated_html='';
		$custom_form=$_POST['custom_form'];
		$sub_item_id=intval($_POST['sub_item_id']);
		$generated_html=substr($custom_form,0,12);
		if (substr($custom_form,0,12)=='custom_form_') {
			$custom_form=intval(substr($custom_form,12));
			$this->model->create_empty_custom_form($sub_item_id, $custom_form);
			$generated_html=$this->view->generate_html_fields( $this->model->items_scheme_custom_forms_array[$custom_form][$sub_item_id] );
		}
		$this->ajax_return(1, $generated_html);
	}
	
	function get_items_scheme() {
		return $this->model->items_scheme;
	}
	function get_items_scheme_array() {
		return $this->model->items_scheme_array;
	}
	function get_settings_scheme() {
		return $this->model->settings_scheme;
	}
	function get_loaded_items_registry() {
		return $this->model->loaded_items_registry;
	}
	function get_loaded_settings_registry() {
		return $this->model->loaded_settings_registry;
	}
	function get_loaded_name() {
		return $this->model->loaded_name;
	}
	function get_loaded_id() {
		return $this->model->loaded_id;
	}
	
	function ajax_return ($status, $data) {
		$arr['status']=$status;
		if (!is_array($data)) $arr['data']=$data;
		else {
			foreach ($data as $var=>$val) $arr[$var]=$val;
		}
		echo json_encode($arr);
		die();
	}
	
	function get_index_table() {
		$arr = $this->model->list_items();
		return $this->view->list_items($arr);
	}
	
	function load($id) {
		return $this->model->load($id);
	}
	
	function delete($id) {
		$this->model->delete($id);
	}
	
	function strip_separator() {
		$post_array=explode('[odvoji]', $_POST['all_around_data']);
		foreach($post_array as $pval) {
			$pos=strpos($pval, '=');
			if ($pos!==FALSE) {
				$pkey=substr($pval, 0, $pos);
				$pval=substr($pval, $pos+1);
				$_POST[$pkey]=$pval;
			}
		}
		unset($_POST['all_around_data']);
		unset($_POST['action']);	
	}

	function ajax_save() {
		$this->ajax_call=1;
		//$this->ajax_return(1, 'povezano');
		$this->strip_separator();
		
		$r=$this->model->save($_POST);
		
		if ($r===FALSE) $this->ajax_return(0, 'Error, data not saved.');
		else {
			if ($r===TRUE) $this->ajax_return(1, 'Saved.');
			else $this->ajax_return(2, array('data'=>'Saved.', 'id'=>$r));
		}

		die();
	}
	
	function ajax_preview() {
		$this->ajax_call=1;
		//$this->ajax_return(1, 'povezano');
		$this->strip_separator();
		
		$r=$this->model->load_from_array($_POST);
		$r=$this->view->preview(0);

		if ($r===FALSE) $this->ajax_return(0, 'Preview error.');
		else {
			$this->ajax_return(1, $r);
		}

		die();
	}

	function generate_all_sub_items() {
		$arr=array();
		$count=count($this->model->items_scheme_array);
		for ($i=0; $i<$count; $i++) {
			$form = $this->view->generate_html_form_part('item', $i);
			$arr[]=$form;
		}
		return $arr;
	}

	function generate_html_form_part($for, $id=0) {
		return $this->view->generate_html_form_part($for, $id);
	}
}


class all_around_mvc_model { // data
	public $controller, $view, $wrapper, $main_object;
	public $items_scheme, $custom_forms_scheme, $custom_forms_count, $items_scheme_array, $items_scheme_custom_forms_array, $settings_scheme, $loaded_items_registry, $loaded_settings_registry, $default_settings_registry, $loaded_items_array, $loaded_name, $loaded_id;
	function __construct(all_around_mvc_controller &$controller, all_around_wrapper_admin &$wrapper, all_around_main_class &$main_object) {
		$this->wrapper=$wrapper;
		$this->controller=$controller;
		$this->main_object=$main_object;

		$this->reset();
	}
	
	function reset() {
		$this->custom_forms_count=0;
		$this->load_scheme();
		$this->items_scheme_array=array();
		$this->loaded_items_registry=array();
		$this->items_scheme_custom_forms_array=array();
		$this->loaded_settings_registry=$this->get_default_values_for_settings();
		$this->default_settings_registry=$this->loaded_settings_registry;
		$this->loaded_name='';
		$this->loaded_id=-1;
	}

	function set_view(all_around_mvc_view &$view) {
		$this->view=$view;
	}

	function load_scheme() {
		$style=array(
			'width' => '300px'
		);
		$this->items_scheme=array(
			array(
				'type' => 'input',
				'name' => 'title',
				'value' => 'Item',
				'label' => 'Title',
				'style' => $style,
				'html_before' => '<div style="width: 300px; float: left; margin-left: 15px;">'
			),
			array(
				'type' => 'listbox',
				'name' => 'link_type',
				'value' => 0,
				'list' => array(
					0 => 'Full size image',
					1 =>  'Custom link'
				),
				'label' => 'What to link',
				'style' => $style,
				'if_value' => array (
					0 => 'hide .item_*_DivGroup_custom_link', 
					1 => 'show .item_*_DivGroup_custom_link'
				)
			),
			array(
				'type' => 'input',
				'name' => 'custom_link',
				'value' => '',
				'label' => 'Custom link',
				'style' => $style,
				'group' => 'item_*_DivGroup_custom_link',
				'not_visible_if'  => 'item_*_link_type=0',
				'html_after' => '</div>'
			),
			array(
				'type' => 'image_upload',
				'name' => 'image',
				'value' => '',
				'label' => 'Image',
				'style' => $style,
				'html_before' => '<div style="width: 300px; float: left; margin-left: 15px;">',
				'html_after' => '</div>'
			),
			array(
				'type' => 'listbox',
				'name' => 'content_type',
				'value' => 0,
				'list' => array(
					0 => 'Our team',
					1 => 'Products',
					2 => 'Portfolio',
					3 => 'Services',
					4 => 'Custom'
				),
				'label' => 'Content type',
				'style' => $style,
				'html_before' => '<div style="width: 300px; float: left; clear: both; margin-left: 15px;">',
				'html_after' => '</div>',
				'if_value' => array (
					0 => 'ajax_load_form #item_*_attached_form custom_form_0',
					1 => 'ajax_load_form #item_*_attached_form custom_form_1',
					2 => 'ajax_load_form #item_*_attached_form custom_form_2',
					3 => 'ajax_load_form #item_*_attached_form custom_form_3',
					4 => 'empty #item_*_attached_form'
				)
			),
			array(
				'type' => 'attached_form',
				'name' => 'attached_form',
				'value' => '0',
				'style' => array('clear' => 'both'),
				'if_other_fields' => array(
						array(
							'item_*_content_type=0' => 'show_form custom_form_0',
							'item_*_content_type=1' => 'show_form custom_form_1',
							'item_*_content_type=2' => 'show_form custom_form_2',
							'item_*_content_type=3' => 'show_form custom_form_3'
						)
					)
			),
			array(
				'type' => 'text',
				'name' => 'content',
				'value' => '',
				'wrapper' => array('clear' => 'both', 'margin-left' => '15px'),
				'label' => 'Content that will be shown'
			)
		);


		$style=array(
			'width' => '240px'
		);
		$this->settings_scheme=array(
			array(
				'type' => 'input',
				'name' => 'settings_param_max_shown_items',
				'value' => '3',
				'label' => 'Number of visible circles',
				'style' => $style
			),
			array(
				'type' => 'input',
				'name' => 'settings_param_active_item',
				'value' => '0',
				'label' => 'Active item on start',
				'style' => $style
			),
			array(
				'type' => 'listbox',
				'name' => 'settings_param_responsive_by_available_space',
				'value' => 0,
				'list' => array(
					0 => 'By browser window',
					1 => 'By available space'
				),
				'label' => 'Responsive by',
				'style' => $style
			),
			array(
				'type' => 'number',
				'name' => 'settings_param_wrapper_text_max_height',
				'value' => '810',
				'min' => 0,
				'max' => 2000,
				'unit' => 'px',
				'label' => 'Slider height',
				'style' => $style
			),
			array(
				'type' => 'checkbox',
				'name' => 'settings_param_automatic_height_resize',
				'value' => 1,
				'label' => 'Automatic height resize',
				'without_wrapper_label' => 1,
				'style' => $style
			),
			array(
				'type' => 'listbox',
				'name' => 'settings_param_hv_switch',
				'value' => 0,
				'list' => array(
					0 => 'Horizontal',
					1 => 'Vertical'
				),
				'label' => 'Type',
				'style' => $style
			),
			array(
				'type' => 'listbox',
				'name' => 'settings_param_middle_click',
				'value' => 2,
				'list' => array(
					0 => 'No response',
					1 => 'Slider will go to the prev. circle',
					2 => 'Slider will go to the next circle'
				),
				'label' => 'When main circle is clicked',
				'style' => $style
			),
			array(
				'type' => 'checkbox',
				'name' => 'settings_param_bind_arrow_keys',
				'value' => 1,
				'label' => 'Slide with keyboard arrow keys',
				'without_wrapper_label' => 1,
				'style' => $style
			),
			array(
				'type' => 'checkbox',
				'name' => 'settings_param_allow_shadow',
				'value' => 1,
				'label' => 'Shadows on/off',
				'without_wrapper_label' => 1,
				'style' => $style
			),
			array(
				'type' => 'checkbox',
				'name' => 'settings_param_border_on_off',
				'value' => 1,
				'label' => 'Borders on/off',
				'without_wrapper_label' => 1,
				'style' => $style
			),
			array(
				'type' => 'number',
				'name' => 'settings_param_small_border',
				'value' => '5',
				'min' => 0,
				'max' => 50,
				'unit' => 'px',
				'label' => 'Border thickness of small circle',
				'style' => $style
			),
			array(
				'type' => 'number',
				'name' => 'settings_param_big_border',
				'value' => '8',
				'min' => 0,
				'max' => 50,
				'unit' => 'px',
				'label' => 'Border thickness of big circle',
				'style' => $style
			),
			array(
				'type' => 'number',
				'name' => 'settings_param_border_radius',
				'value' => '-1',
				'min' => -1,
				'max' => 500,
				'unit' => 'px',
				'label' => 'Border radius',
				'style' => $style
			),
			array(
				'type' => 'color',
				'name' => 'settings_param_border_color',
				'value' => '#282828',
				'label' => 'Border color',
				'style' => $style
			),
			array(
				'type' => 'checkbox',
				'name' => 'settings_param_radius_proportion',
				'value' => 1,
				'label' => 'Keep border radius proportion',
				'without_wrapper_label' => 1,
				'style' => $style
			),
			array(
				'type' => 'listbox',
				'name' => 'settings_param_mode',
				'value' => 2,
				'list' => array(
					1 => 'Do not enlarge middle circle',
					2 => 'Enlarge middle circle'
				),
				'label' => 'While sliding',
				'style' => $style
			),
			array(
				'type' => 'number',
				'name' => 'settings_param_small_resolution_max_height',
				'value' => '0',
				'min' => 0,
				'max' => 1600,
				'unit' => 'px',
				'label' => 'Max slider height in small resolution',
				'style' => $style
			),
			array(
				'type' => 'number',
				'name' => 'settings_param_big_small_width',
				'value' => '84',
				'min' => 1,
				'max' => 500,
				'unit' => 'px',
				'label' => 'Width of small circle',
				'style' => $style
			),
			array(
				'type' => 'number',
				'name' => 'settings_param_small_pic_height',
				'value' => '84',
				'min' => 1,
				'max' => 500,
				'unit' => 'px',
				'label' => 'Height of small circle',
				'style' => $style
			),
			array(
				'type' => 'number',
				'name' => 'settings_param_child_div_width',
				'value' => '104',
				'min' => 1,
				'max' => 500,
				'unit' => 'px',
				'label' => 'Space around small circle (width)',
				'style' => $style
			),
			array(
				'type' => 'number',
				'name' => 'settings_param_child_div_height',
				'value' => '104',
				'min' => 1,
				'max' => 500,
				'unit' => 'px',
				'label' => 'Space around small circle (height)',
				'style' => $style
			),
			array(
				'type' => 'number',
				'name' => 'settings_param_big_pic_width',
				'value' => '231',
				'min' => 1,
				'max' => 1000,
				'unit' => 'px',
				'label' => 'Width of big circle',
				'style' => $style
			),
			array(
				'type' => 'number',
				'name' => 'settings_param_big_pic_height',
				'value' => '231',
				'min' => 1,
				'max' => 1000,
				'unit' => 'px',
				'label' => 'Height of big circle',
				'style' => $style
			),
			array(
				'type' => 'number',
				'name' => 'settings_param_moving_speed',
				'value' => '70',
				'min' => 1,
				'max' => 500,
				'unit' => 'ms',
				'label' => 'Moving speed (animation)',
				'style' => $style
			),
			array(
				'type' => 'number',
				'name' => 'settings_param_moving_speed_offset',
				'value' => '100',
				'min' => 1,
				'max' => 500,
				'unit' => 'ms',
				'label' => 'Moving speed offset',
				'style' => $style
			),
			array(
				'type' => 'input',
				'name' => 'settings_param_moving_easing',
				'value' => 'linear',
				'label' => 'Moving easing',
				'style' => $style
			),
			array(
				'type' => 'checkbox',
				'name' => 'settings_param_use_thin_arrows',
				'value' => 0,
				'label' => 'Use thin arrows',
				'without_wrapper_label' => 1,
				'style' => $style
			),
			array(
				'type' => 'color',
				'name' => 'settings_param_arrow_color',
				'value' => '#282828',
				'label' => 'Arrow color',
				'style' => $style
			),
			array(
				'type' => 'number',
				'name' => 'settings_param_arrow_speed',
				'value' => '300',
				'min' => 1,
				'max' => 1000,
				'unit' => 'ms',
				'label' => 'Arrows speed (animation)',
				'style' => $style
			),
			array(
				'type' => 'input',
				'name' => 'settings_param_arrow_easing',
				'value' => 'linear',
				'label' => 'Arrows easing',
				'style' => $style
			),
			array(
				'type' => 'number',
				'name' => 'settings_param_hover_movement',
				'value' => '6',
				'min' => 0,
				'max' => 100,
				'unit' => 'px',
				'label' => 'Mouse over movement (hover effect)',
				'style' => $style
			),
			array(
				'type' => 'input',
				'name' => 'settings_param_hover_easing',
				'value' => 'linear',
				'label' => 'Hover easing',
				'style' => $style
			),
			array(
				'type' => 'number',
				'name' => 'settings_param_prettyPhoto_speed',
				'value' => '200',
				'min' => 1,
				'max' => 1000,
				'unit' => 'ms',
				'label' => 'Zoom icon speed (animation)',
				'style' => $style
			),
			array(
				'type' => 'input',
				'name' => 'settings_param_prettyPhoto_easing',
				'value' => 'linear',
				'label' => 'Zoom icon easing',
				'style' => $style
			),
			array(
				'type' => 'number',
				'name' => 'settings_param_prettyPhoto_width',
				'value' => '21',
				'min' => 1,
				'max' => 100,
				'unit' => 'px',
				'label' => 'Zoom icon width',
				'style' => $style
			),
			array(
				'type' => 'input',
				'name' => 'settings_param_prettyPhoto_start',
				'value' => '0.93',
				'label' => 'Position of zoom icon',
				'style' => $style
			),
			array(
				'type' => 'number',
				'name' => 'settings_param_prettyPhoto_movement',
				'value' => '45',
				'min' => 0,
				'max' => 250,
				'unit' => 'px',
				'label' => 'Zoom icon movement',
				'style' => $style
			),
			array(
				'type' => 'checkbox',
				'name' => 'settings_param_auto_play',
				'value' => 0,
				'label' => 'Auto play',
				'without_wrapper_label' => 1,
				'style' => $style
			),
			array(
				'type' => 'listbox',
				'name' => 'settings_param_auto_play_direction',
				'value' => 2,
				'list' => array(
					1 => 'Slider will go to the right',
					2 => 'Slider will go to the left'
				),
				'label' => 'Auto play direction',
				'style' => $style
			),
			array(
				'type' => 'number',
				'name' => 'settings_param_auto_play_pause_time',
				'value' => '3000',
				'min' => 0,
				'max' => 10000,
				'unit' => 'ms',
				'label' => 'Auto play interval',
				'style' => $style
			),
			array(
				'type' => 'checkbox',
				'name' => 'settings_param_preload_all_images',
				'value' => 0,
				'label' => 'Preload all images',
				'without_wrapper_label' => 1,
				'style' => $style
			),
			array(
				'type' => 'checkbox',
				'name' => 'settings_param_enable_mousewheel',
				'value' => 0,
				'label' => 'Enable Mousewheel scrolling',
				'without_wrapper_label' => 1,
				'style' => $style
			),
			array(
				'type' => 'checkbox',
				'name' => 'settings_param_activate_border_div',
				'value' => 0,
				'label' => 'Slower but nicer border rendering',
				'without_wrapper_label' => 1,
				'style' => $style
			),
		);

		$style=array(
			'width' => '300px'
		);
		$textarea_style=array(
			'width' => '300px',
			'height' => '280px'
		);
		$this->custom_forms_scheme[0]=array(
			array(
				'type' => 'input',
				'name' => 'f1_first_field',
				'value' => 'Position:',
				'label' => 'First field',
				'style' => $style,
				'html_before' => '<div style="width: 300px; float: left; margin-left: 15px;">'
			),
			array(
				'type' => 'input',
				'name' => 'f1_first_field_value',
				'value' => 'Enter here position in company',
				'label' => 'First field value',
				'style' => $style,
				'html_after' => '</div>'
			),
			array(
				'type' => 'input',
				'name' => 'f1_second_field',
				'value' => 'Address:',
				'label' => 'Second field',
				'style' => $style,
				'html_before' => '<div style="width: 300px; float: left; margin-left: 15px;">'
			),
			array(
				'type' => 'input',
				'name' => 'f1_second_field_value',
				'value' => 'Enter here address',
				'label' => 'Second field value',
				'style' => $style,
				'html_after' => '</div>'
			),
			array(
				'type' => 'input',
				'name' => 'f1_third_field',
				'value' => 'E-mail:',
				'label' => 'Third field',
				'style' => $style,
				'html_before' => '<div style="width: 300px; float: left; margin-left: 15px;">'
			),
			array(
				'type' => 'input',
				'name' => 'f1_third_field_value',
				'value' => '<a href="mailto:some@email.com">some@email.com</a>',
				'label' => 'Third field value',
				'style' => $style,
				'html_after' => '</div>'
			),
			array(
				'type' => 'input',
				'name' => 'f1_fourth_field',
				'value' => 'Web:',
				'label' => 'Fourth field',
				'style' => $style,
				'html_before' => '<div style="width: 300px; float: left; margin-left: 15px;">'
			),
			array(
				'type' => 'input',
				'name' => 'f1_fourth_field_value',
				'value' => '<a href="http://www.">www.</a>',
				'label' => 'Fourth field value',
				'style' => $style,
				'html_after' => '</div>'
			),
			array(
				'type' => 'text',
				'name' => 'f1_about',
				'value' => '<span class="bold">About: </span> Enter here text for about section...',
				'label' => 'About section',
				'style' => $textarea_style,
				'html_before' => '<div style="width: 300px; float: left; clear: both; margin-left: 15px;">',
				'html_after' => '</div>'
			),
			array(
				'type' => 'input',
				'name' => 'f1_facebook_link',
				'value' => '',
				'label' => 'Facebook link',
				'style' => $style,
				'html_before' => '<div style="width: 300px; float: left; margin-left: 15px;">'
			),
			array(
				'type' => 'input',
				'name' => 'f1_twitter_link',
				'value' => '',
				'label' => 'Twitter link',
				'style' => $style
			),
			array(
				'type' => 'input',
				'name' => 'f1_pinterest_link',
				'value' => '',
				'label' => 'Pinterest link',
				'style' => $style
			),
			array(
				'type' => 'input',
				'name' => 'f1_youtube_link',
				'value' => '',
				'label' => 'Youtube link',
				'style' => $style,
				'html_after' => '</div>'
			)
		);
		$textarea_style=array(
			'width' => '300px',
			'height' => '443px'
		);
		$this->custom_forms_scheme[1]=array(
			array(
				'type' => 'text',
				'name' => 'f2_about',
				'value' => 'Enter here text for about section...',
				'label' => 'About section',
				'style' => $textarea_style,
				'html_before' => '<div style="width: 300px; float: left; clear: both; margin-left: 15px;">',
				'html_after' => '</div>'
			),
			array(
				'type' => 'input',
				'name' => 'f2_first_field',
				'value' => 'Cost:',
				'label' => 'First field',
				'style' => $style,
				'html_before' => '<div style="width: 300px; float: left; margin-left: 15px;">'
			),
			array(
				'type' => 'input',
				'name' => 'f2_first_field_value',
				'value' => 'Enter here price',
				'label' => 'First field value',
				'style' => $style
			),
			array(
				'type' => 'input',
				'name' => 'f2_second_field',
				'value' => 'In Stock:',
				'label' => 'Second value',
				'style' => $style
			),
			array(
				'type' => 'input',
				'name' => 'f2_second_field_value',
				'value' => '',
				'label' => 'Second field value',
				'style' => $style
			),
			array(
				'type' => 'input',
				'name' => 'f2_button_text',
				'value' => 'More Info',
				'label' => 'Button text',
				'style' => $style
			),
			array(
				'type' => 'input',
				'name' => 'f2_button_link',
				'value' => 'http://www.',
				'label' => 'Button link',
				'style' => $style,
				'html_after' => '</div>'
			)
		);
		$textarea_style=array(
			'width' => '300px',
			'height' => '200px'
		);
		$this->custom_forms_scheme[2]=array(
			array(
				'type' => 'checkbox',
				'name' => 'f3_first_show',
				'value' => 1,
				'label' => 'Show first column',
				'without_wrapper_label' => 1,
				'style' => $style,
				'html_before' => '<div style="width: 300px; float: left; margin-left: 15px;">'
			),
			array(
				'type' => 'image_upload',
				'name' => 'f3_first_image',
				'value' => '',
				'label' => 'First image',
				'style' => $style
			),
			array(
				'type' => 'input',
				'name' => 'f3_first_title',
				'value' => '',
				'label' => 'First title',
				'style' => $style
			),
			array(
				'type' => 'text',
				'name' => 'f3_first_about',
				'value' => 'Enter here text for about section...',
				'label' => 'About section',
				'style' => $textarea_style,
				'html_after' => '</div>'
			),
			array(
				'type' => 'checkbox',
				'name' => 'f3_second_show',
				'value' => 1,
				'label' => 'Show second column',
				'without_wrapper_label' => 1,
				'style' => $style,
				'html_before' => '<div style="width: 300px; float: left; margin-left: 15px;">'
			),
			array(
				'type' => 'image_upload',
				'name' => 'f3_second_image',
				'value' => '',
				'label' => 'Second image',
				'style' => $style
			),
			array(
				'type' => 'input',
				'name' => 'f3_second_title',
				'value' => '',
				'label' => 'Second title',
				'style' => $style
			),
			array(
				'type' => 'text',
				'name' => 'f3_second_about',
				'value' => 'Enter here text for about section...',
				'label' => 'About section',
				'style' => $textarea_style,
				'html_after' => '</div>'
			),
			array(
				'type' => 'checkbox',
				'name' => 'f3_third_show',
				'value' => 1,
				'label' => 'Show third column',
				'without_wrapper_label' => 1,
				'style' => $style,
				'html_before' => '<div style="width: 300px; float: left; margin-left: 15px;">'
			),
			array(
				'type' => 'image_upload',
				'name' => 'f3_third_image',
				'value' => '',
				'label' => 'Third image',
				'style' => $style
			),
			array(
				'type' => 'input',
				'name' => 'f3_third_title',
				'value' => '',
				'label' => 'Third title',
				'style' => $style
			),
			array(
				'type' => 'text',
				'name' => 'f3_third_about',
				'value' => 'Enter here text for about section...',
				'label' => 'About section',
				'style' => $textarea_style,
				'html_after' => '</div>'
			),
			array(
				'type' => 'checkbox',
				'name' => 'f3_fourth_show',
				'value' => 1,
				'label' => 'Show fourth column',
				'without_wrapper_label' => 1,
				'style' => $style,
				'html_before' => '<div style="width: 300px; float: left; margin-left: 15px;">'
			),
			array(
				'type' => 'image_upload',
				'name' => 'f3_fourth_image',
				'value' => '',
				'label' => 'Fourth image',
				'style' => $style
			),
			array(
				'type' => 'input',
				'name' => 'f3_fourth_title',
				'value' => '',
				'label' => 'Fourth title',
				'style' => $style
			),
			array(
				'type' => 'text',
				'name' => 'f3_fourth_about',
				'value' => 'Enter here text for about section...',
				'label' => 'About section',
				'style' => $textarea_style,
				'html_after' => '</div>'
			)
		);
		$this->custom_forms_scheme[3]=array(
			array(
				'type' => 'checkbox',
				'name' => 'f4_first_show',
				'value' => 1,
				'label' => 'Show first column',
				'without_wrapper_label' => 1,
				'style' => $style,
				'html_before' => '<div style="width: 300px; float: left; margin-left: 15px;">'
			),
			array(
				'type' => 'input',
				'name' => 'f4_first_title',
				'value' => '',
				'label' => 'First title',
				'style' => $style
			),
			array(
				'type' => 'image_upload',
				'name' => 'f4_first_image',
				'value' => '',
				'label' => 'First image',
				'style' => $style,
			),
			array(
				'type' => 'text',
				'name' => 'f4_first_about',
				'value' => 'Enter here text for about section...',
				'label' => 'About section',
				'style' => $textarea_style
			),
			array(
				'type' => 'input',
				'name' => 'f4_first_button_text',
				'value' => 'More Info',
				'label' => 'Button text',
				'style' => $style
			),
			array(
				'type' => 'input',
				'name' => 'f4_first_button_link',
				'value' => 'http://www.',
				'label' => 'Button link',
				'style' => $style,
				'html_after' => '</div>'
			),
			array(
				'type' => 'checkbox',
				'name' => 'f4_second_show',
				'value' => 1,
				'label' => 'Show second column',
				'without_wrapper_label' => 1,
				'style' => $style,
				'html_before' => '<div style="width: 300px; float: left; margin-left: 15px;">'
			),
			array(
				'type' => 'input',
				'name' => 'f4_second_title',
				'value' => '',
				'label' => 'Second title',
				'style' => $style
			),
			array(
				'type' => 'image_upload',
				'name' => 'f4_second_image',
				'value' => '',
				'label' => 'Second image',
				'style' => $style,
			),
			array(
				'type' => 'text',
				'name' => 'f4_second_about',
				'value' => 'Enter here text for about section...',
				'label' => 'About section',
				'style' => $textarea_style
			),
			array(
				'type' => 'input',
				'name' => 'f4_second_button_text',
				'value' => 'More Info',
				'label' => 'Button text',
				'style' => $style
			),
			array(
				'type' => 'input',
				'name' => 'f4_second_button_link',
				'value' => 'http://www.',
				'label' => 'Button link',
				'style' => $style,
				'html_after' => '</div>'
			),
			array(
				'type' => 'checkbox',
				'name' => 'f4_third_show',
				'value' => 1,
				'label' => 'Show third column',
				'without_wrapper_label' => 1,
				'style' => $style,
				'html_before' => '<div style="width: 300px; float: left; margin-left: 15px;">'
			),
			array(
				'type' => 'input',
				'name' => 'f4_third_title',
				'value' => '',
				'label' => 'Third title',
				'style' => $style
			),
			array(
				'type' => 'image_upload',
				'name' => 'f4_third_image',
				'value' => '',
				'label' => 'Third image',
				'style' => $style,
			),
			array(
				'type' => 'text',
				'name' => 'f4_third_about',
				'value' => 'Enter here text for about section...',
				'label' => 'About section',
				'style' => $textarea_style
			),
			array(
				'type' => 'input',
				'name' => 'f4_third_button_text',
				'value' => 'More Info',
				'label' => 'Button text',
				'style' => $style
			),
			array(
				'type' => 'input',
				'name' => 'f4_third_button_link',
				'value' => 'http://www.',
				'label' => 'Button link',
				'style' => $style,
				'html_after' => '</div>'
			),
		);
		
		$this->custom_forms_count=count($this->custom_forms_scheme);

		foreach($this->items_scheme as $id => $arr) {
			if (isset($arr['name'])) $this->items_scheme[$id]['base_name']=$arr['name'];
		}
		foreach($this->settings_scheme as $id => $arr) {
			if (isset($arr['value'])) $this->settings_scheme[$id]['default_value']=$arr['value'];
		}
	}

	function extract_array($arr) {
		$rarr=array();
		if (isset($arr['element_id'])) $rarr['element_id']=$arr['element_id'];
		if (isset($arr['element_name'])) $rarr['element_name']=$name=$arr['element_name'];
		
		$rarr['items']=array();
		$rarr['settings']=array();
		$current_base='';
		$i=-1;
		foreach($arr as $var => $val) {
			if (substr($var,0,4)=='item') {
				$pos=strpos($var, '_', 5);
				$base=substr($var, 0, $pos);
				$field=substr($var, $pos);
				if ($base!=$current_base) {$i++; $current_base=$base;}
				$nvar='item_'.$i.$field;
				//$this->controller->ajax_return(1, $var.'='.$nvar);
				$rarr['items'][$nvar]=stripslashes($val);
			}
			if (substr($var,0,8)=='settings') $rarr['settings'][$var]=stripslashes($val);
		}
		return $rarr;
	}
	
	function save($arr) {
		//$r='Result: '.print_r($_POST, TRUE);
		//$this->controller->ajax_return(1, $r);
		
		$rarr=$this->extract_array($arr);
		$data['name']='';		
		if (isset($rarr['element_name'])) $data['name']=$rarr['element_name'];
		$data['settings']=serialize($rarr['settings']);
		$data['items']=serialize($rarr['items']);
		
		//$arrs=serialize($data);
		$table=$this->main_object->get_plugin_table_name();
		//print_r($rarr); exit;
		
		if ($rarr['element_id']=='new') {
		
			$this->wrapper->db_insert_row($table, $data, array('%s', '%s', '%s'));
			return $this->wrapper->db_get_insert_id();
		} else {
			$this->wrapper->db_update($table, $data, array('id'=>$rarr['element_id']), array('%s', '%s', '%s'), array('%d'));
			return TRUE;
		}
		return FALSE;
	}
	
	function get_default_values_for_item() {
		$rarr=array();
		foreach ($this->items_scheme as $id=>$arr) {
			$name=$arr['name'];
			$value=$arr['value'];
			$rarr[$name]=$value;
		}
		return $rarr;
	}
	function get_default_values_for_settings() {
		$rarr=array();
		foreach ($this->settings_scheme as $id=>$arr) {
			$name=$arr['name'];
			$value=$arr['value'];
			$rarr[$name]=$value;
		}
		return $rarr;
	}
	
	function put_items_in_array(&$registry) {
		$this->loaded_items_array=array();
		foreach ($registry as $var => $val) {
			if (substr($var, 0, 5)=='item_') {
				$pos=strpos($var, '_', 5);
				$id=substr($var, 5, $pos-5);
				if (!isset($this->loaded_items_array[$id])) $this->loaded_items_array[$id]=$this->get_default_values_for_item();
				$var=substr($var, $pos+1);
				$this->loaded_items_array[$id][$var]=$val;
			}
		}
	}
	
	function load_from_array($arr) {
		$this->reset();
		$arr2=array();
		foreach ($arr as $var => $val) {
			if (substr($var, 0, 5)=='item_') {$arr2['items'][$var]=$val; continue;}
			if (substr($var, 0, 9)=='settings_') {$arr2['settings'][$var]=$val; continue;}
			$arr2['other'][$var]=$val;
		}
		return $this->load(0, $arr2);
	}
	
	function set_default_values_from_post($id, $arr=FALSE) {
		$this->reset();
		if ($arr===FALSE) $arr=$this->wrapper->post_get($id);
		if ($arr===NULL) return FALSE;
		foreach ($this->items_scheme as $aid => $row) {
			if ($row['name']=='content_type') $this->items_scheme[$aid]['value']=4;
			if (!empty($arr['title'])) if ($row['name']=='title') $this->items_scheme[$aid]['value']=$arr['title'];
			if (!empty($arr['thumbnail'])) if ($row['name']=='image') $this->items_scheme[$aid]['value']=$arr['thumbnail'];
			if (!empty($arr['content'])) if ($row['name']=='content') $this->items_scheme[$aid]['value']=$arr['content'];
			if (!empty($arr['link'])) {
				if ($row['name']=='custom_link') $this->items_scheme[$aid]['value']=$arr['link'];
				if ($row['name']=='link_type') $this->items_scheme[$aid]['value']=1;
			}
		}
		return TRUE;
	}
	
	function create_empty_custom_form($item_id, $custom_form_id) {
		if (!isset($this->items_scheme_custom_forms_array[$custom_form_id][$item_id])) {
			$this->items_scheme_custom_forms_array[$custom_form_id][$item_id]=$this->custom_forms_scheme[$custom_form_id];
			foreach ($this->items_scheme_custom_forms_array[$custom_form_id][$item_id] as $aid => $arr) {
				$this->items_scheme_custom_forms_array[$custom_form_id][$item_id][$aid]['name']='item_'.$item_id.'_'.$this->items_scheme_custom_forms_array[$custom_form_id][$item_id][$aid]['name'];
			}
		}
	}
	
	function load($id, $preview=FALSE) {
		$this->reset();
		if ($preview===FALSE) {
			$table=$this->main_object->get_plugin_table_name();
			$row=$this->wrapper->db_get_row('SELECT id, name, settings, items FROM '.$table.' WHERE id='.$id);
			$this->loaded_items_registry=unserialize($row['items']);
			$arr=unserialize($row['settings']);
			foreach($arr as $var=>$val)	$this->loaded_settings_registry[$var]=$val;;
			$this->loaded_name=$row['name'];
			$this->loaded_id=$row['id'];
		} else {
			foreach ($preview as $var => $arr)
				foreach ($arr as $var2 => $val2)
					$preview[$var][$var2]=stripslashes($val2);
			$this->loaded_items_registry=$preview['items'];
			$arr=$preview['settings'];
			foreach($arr as $var=>$val)	$this->loaded_settings_registry[$var]=$val;;
			$this->loaded_name=$preview['other']['element_name'];
			$this->loaded_id=$preview['other']['element_id'];
		}
		
		$this->put_items_in_array($this->loaded_items_registry);
		//echo '<pre>'; print_r($this->loaded_items_array); echo '</pre>'; exit;
		
		foreach($this->loaded_items_registry as $field => $value) {

			$temp=explode('_', $field); $i=$temp[1];

			if (!isset($this->items_scheme_array[$i])) {
				$this->items_scheme_array[$i]=$this->items_scheme;
				foreach ($this->items_scheme_array[$i] as $aid => $arr) {
					$this->items_scheme_array[$i][$aid]['name']='item_'.$i.'_'.$this->items_scheme_array[$i][$aid]['name'];
				}
			}
			for ($f=0; $f<$this->custom_forms_count; $f++) {
				$this->create_empty_custom_form($i, $f);
			}
			foreach ($this->items_scheme_array[$i] as $aid => $arr) {
				if ($arr['name']==$field) {
					$this->items_scheme_array[$i][$aid]['value']=$value;
					continue;
				}
			}
			for ($f=0; $f<$this->custom_forms_count; $f++) {
				foreach ($this->items_scheme_custom_forms_array[$f][$i] as $aid => $arr) {
					if ($arr['name']==$field) {
						$this->items_scheme_custom_forms_array[$f][$i][$aid]['value']=$value;
						continue;
					}
				}
			}
		}
		//echo '<pre>'; print_r($this->items_scheme_array); echo '</pre>';
		foreach($this->loaded_settings_registry as $field => $value) {
			foreach ($this->settings_scheme as $aid => $arr) if ($arr['name']==$field) {
				$this->settings_scheme[$aid]['value']=$value;
			}
		}
	}
	
	function get_field_from_items_scheme_array ($name) {
		//$r=print_r($looked_arr, true); 
		foreach($this->items_scheme_array as $id => $arr) {
			foreach($arr as $pid => $parr) {
				if ($parr['name']==$name) return $parr;
			}
		}
		return FALSE;
	}
	function get_field_from_custom_scheme ($arr, $name) {
		//$r=print_r($looked_arr, true); 
		//if ($this->controller->ajax_call==1) $this->controller->ajax_return(1, print_r($this->items_scheme) );
		foreach($arr as $id => $arr) {
			if ($arr['name']==$name) return $arr;
		}
		return FALSE;
	}
	
	function delete($id) {
		$table=$this->main_object->get_plugin_table_name();
		return $this->wrapper->db_query('DELETE FROM '.$table.' WHERE id='.$id);
	}

	function list_items() {
		$table=$this->main_object->get_plugin_table_name();
		return $this->wrapper->db_get_results('SELECT id, name, settings, items FROM '.$table);	
	}
}

class all_around_mvc_view { // viewer
	public $controller, $model, $wrapper, $main_object, $javascript_events, $generated_frontend_javascript;
	function __construct(all_around_mvc_controller &$controller, all_around_wrapper_admin &$wrapper, all_around_main_class &$main_object) {
		$this->controller=$controller;
		$this->wrapper=$wrapper;
		$this->main_object=$main_object;
		$this->javascript_events=array();
		$this->generated_frontend_javascript=array();
	}
	function set_model(all_around_mvc_model &$model) {
		$this->model=$model;
	}
	
	function preview($id) {
		$buffer=$this->frontend_body_function($id);
		return $buffer;
	}
	
	function generate_frontend_javascript($id) {
		if (isset($this->generated_frontend_javascript[$id])) return '';
		$this->generated_frontend_javascript[$id]=1;
		$buffer=<<<eof
<script>
(function($){
	$(document).ready(function(){
		var image_array = new Array();
		image_array = [

eof;
			$i=0;
			foreach ($this->model->loaded_items_array as $aid => $arr) {
				if ($arr['link_type']==0) {$link=$arr['image']; $rel='prettyPhoto';}
				if ($arr['link_type']==1) {$link=$arr['custom_link']; $rel='';}
				if ($i) $buffer.=",\n";
				$width=$this->model->loaded_settings_registry['settings_param_big_pic_width']+39;
				$height=$this->model->loaded_settings_registry['settings_param_big_pic_height']+39;
				if ($arr['image']=='') $arr['image']=$this->main_object->url.'images/no_image3.jpg';
				$image=$this->main_object->get_cached_image ($arr['image'], $width, $height);
				$buffer.="			{image: '".$image."', link_url: '".$link."', link_rel: '".$rel."'}";
				$i++;
			}
			$url=$this->main_object->url;
			$buffer.=<<<eof

		];
		$('#all_around_slider_$id').content_slider({
			map: image_array,
			plugin_url: '$url',

eof;

			$i=0;
			foreach ($this->model->loaded_settings_registry as $var => $val) {
				if (substr($var,0,15)=='settings_param_') {
					if ($this->model->default_settings_registry[$var]==$val) continue;
					$var=substr($var, 15);
					if ($i) $buffer.=",\n";
					if (!is_numeric($val)) $val='"'.$val.'"';
					$buffer.='			'.$var.': '.$val;
					$i++;
				}
			}


			$jQuery='jQuery';
			if ($this->main_object->alternative_jquery) $jQuery='all_around_jQuery';
			$buffer.=<<<eof

		});
		$("a[rel^='prettyPhoto']").prettyPhoto();
	});
})($jQuery);
</script>

eof;
		return $buffer;
	}
	
	function generate_frontend_html($id) {
		$class='content_slider_wrapper';
		if ($this->model->loaded_settings_registry['settings_param_hv_switch']==1) $class='content_slider_wrapper_vertical';
		$buffer='<div class="'.$class.'" id="all_around_slider_'.$id.'">'."\n";
		$i=0;
		foreach ($this->model->loaded_items_array as $id => $arr) {
			$buffer.='	<div class="circle_slider_text_wrapper" id="sw'.$i.'" style="display: none;">'."\n";
			$buffer.='		<div class="content_slider_text_block_wrap">'."\n";
			$buffer.=$arr['content']."\n";
			$buffer.='		</div>'."\n";
			$buffer.='		<div class="clear"></div>'."\n";
			$buffer.='	</div>'."\n";
			$i++;
		}
	
		$buffer.="</div>";
		return $buffer;
	}

	function frontend_body_function($id) {
		//$buffer = '============ '.$id.' ===============';
		$buffer='';
		$buffer.=$this->generate_frontend_javascript($id);
		$buffer.=$this->generate_frontend_html($id);
		//echo $buffer; exit;
		return $buffer;
	}
	
	function generate_backend_javascript($with_wrapper=0) {
		//print_r($this->javascript_events); exit;
		if (count($this->javascript_events)==0) return '';
		$buffer='';
		if ($with_wrapper) {
			$buffer.=<<<eof
<script>
(function($){
	$(document).ready(function(){

eof;
		}
		foreach ($this->javascript_events as $id => $arr) {
			if (isset($arr['if_value'])) {
				$buffer.="all_around_add_event('".$arr['object']."', function(name, value) {\n";
				foreach ($arr['if_value'] as $value => $action_array) {
					foreach ($action_array as $action => $target_arr) {
						$target=$target_arr['target'];
						$action_string='';
						if ($action=='show' || $action=='hide') $action_string=$action."();";
						if ($action=='empty') $action_string="html('');";
						if ($action=='ajax_load_form') {
							$buffer.="	if (value==".$value.") all_around_ajax_load_form('".$target."', '".$target_arr['param1']."', '".$target_arr['param2']."');\n";
							continue;
						}
						if ($action_string!='') $buffer.="	if (value==".$value.") $('".$target."').".$action_string."\n";
					}
				}
				$buffer.="});\n";
			}
		}
		if ($with_wrapper) {
			$buffer.=<<<eof
	});
})(jQuery);
</script>
eof;
		}
		return $buffer;
	}

	function list_items($resource) {
		$url=$this->main_object->admin_url;
		$new_url=$url.'&action=new';
		if ($resource==NULL) {
			$arr=array(array('<b>There is no created items. <a href="'.$new_url.'">Create one now</a>.</b>'));
			$td_style[0][0]='colspan="4" style="text-align: center;"';
		} else {
			$td_style=NULL;
			foreach ($resource as $row) {
				$edit_url=$url.'&action=edit&id='.$row['id'];
				$delete_url=$url.'&action=delete&id='.$row['id'];
				$arr[]=array ($row['id'], $row['name'], '[all_around id="'.$row['id'].'"]', '<a href="'.$edit_url.'">Edit</a> | <a href="'.$delete_url.'" class="all_around_delete">Delete</a>');
			}
		}

		$header=array(
			'ID',
			'Name',
			'Shortcode',
			'Actions'
		);
		return all_around_visual_elements::generate_table ($arr, $header, array('auto_width'=>FALSE), $td_style);
	}

	function generate_html_fields (&$form) {
		$html='';
		foreach ($form as $id => $field) {
			$wrapper=array();
			$style=NULL;
			$style['auto_width']=FALSE;
			$generated_html='';
			if (isset($field['wrapper'])) $wrapper=$field['wrapper'];
			if (isset($field['if_other_fields'])) {
				foreach($field['if_other_fields'] as $arr) {
					$tarr=explode('_', $field['name']);
					$prefix=$tarr[0].'_'.$tarr[1];
					$item_number=$tarr[1];
					foreach ($arr as $condition => $action) {
						$condition=explode('=', $condition);
						$target=$condition[0];
						$target_value=$condition[1];
						$action=explode(' ', $action);
						
						if (substr($target,0,7)=='item_*_') $target=$prefix.'_'.substr($target,7);
						$target_arr=$this->model->get_field_from_custom_scheme($form, $target);
						if ($target_arr['value']==$target_value)
						{
							if ($action[0]=='show_form') {
								if (substr($action[1], 0, 12)=='custom_form_') {
									$custom_form=substr($action[1], 12);
									//echo "calling: ".$custom_form." ".$item_number."\n"; //print_r($this->model->items_scheme_custom_forms_array);
									$generated_html=$this->generate_html_fields( $this->model->items_scheme_custom_forms_array[$custom_form][$item_number] );
								}
							}
						}
						//print_r($target_arr); exit;
					}
				}
			}
			if (isset($field['not_visible_if'])) {
				$looking_for=explode('=', $field['not_visible_if']);
				$looked_val=$looking_for[1];
				//echo $looked_val; exit;
				$looking_for=$looking_for[0];
				$arr=explode('_', $field['name']);
				$prefix=$arr[0].'_'.$arr[1];
				if ($arr[0]=='item' && substr($looking_for,0,7)=='item_*_') $looking_for=$prefix.'_'.substr($looking_for,7);
				if ($this->model->loaded_id>-1) $looked_arr=$this->model->get_field_from_items_scheme_array($looking_for);
				else $looked_arr=$this->model->get_field_from_custom_scheme($form, $looking_for);
				if ($looked_arr['value']==$looked_val) $wrapper['display']='none';
				//echo 'looking_for='.$looking_for.'<br />'; echo 'looked_val='.$looked_val.'<br />'; echo '<pre>'; print_r($looked_arr); echo '</pre>';	exit;
			}
			if (isset($field['if_value'])) {
				$arr=$field['if_value'];
				//echo 'field='.$field['name'].'<br /><br />';
				$events=array();
				foreach($arr as $value => $expr) {
					$expr=explode(' ', $expr);
					$action=$expr[0];
					$target=$expr[1];
					$param1='';
					$param2='';
					$tarr=explode('_', $field['name']);
					$prefix=$tarr[0].'_'.$tarr[1];
					if (isset($expr[2])) $param1=$expr[2];
					if ($action=='ajax_load_form' && $tarr[0]=='item') $param2=$tarr[1];
					if ($tarr[0]=='item' && substr($target,0,7)=='item_*_') $target=$prefix.'_'.substr($target,7);
					if ($tarr[0]=='item' && substr($target,0,8)=='.item_*_') $target='.'.$prefix.'_'.substr($target,8);
					if ($tarr[0]=='item' && substr($target,0,8)=='#item_*_') $target='#'.$prefix.'_'.substr($target,8);
					//echo 'if value='.$value.'<br />action='.$action.'<br />target='.$target.'<br /><br />'; 
					$target_arr=array('target'=>$target, 'param1'=>$param1, 'param2'=>$param2);
					$events[$value]=array($action=>$target_arr);
				}
				$this->javascript_events[]=array(
					'object' => $field['name'],
					'if_value' => $events
				);
				//echo '<pre>'; print_r($this->javascript_events); echo '</pre>';exit;
			}

			if (isset($field['style'])) $style=$field['style'];
			if (isset($field['label'])) $wrapper['span']=$field['label'];
			if (isset($field['group'])) {
				$wrapper['group']=$field['group'];
				$tarr=explode('_', $field['name']);
				$prefix=$tarr[0].'_'.$tarr[1];
				if ($tarr[0]=='item' && substr($field['group'],0,7)=='item_*_') $wrapper['group']=$prefix.'_'.substr($wrapper['group'],7);
			}
			if (isset($field['html_before'])) $html.=$field['html_before'];
			if ($field['type']=='image_upload') $html.=all_around_visual_elements::generate_image($field['name'], $field['value'], $wrapper, $style, $this->main_object->url.'images/no_image.jpg');
			if ($field['type']=='listbox') $html.=all_around_visual_elements::generate_listbox($field['name'], $field['value'], $field['list'], $wrapper, $style);
			if ($field['type']=='text') $html.=all_around_visual_elements::generate_textarea($field['name'], $field['value'], $wrapper, $style);
			if ($field['type']=='input') $html.=all_around_visual_elements::generate_input($field['name'], $field['value'], $wrapper, $style);
			if ($field['type']=='color') $html.=all_around_visual_elements::generate_color($field['name'], $field['value'], $wrapper, $style);
			if ($field['type']=='checkbox') {
				if (isset($field['without_wrapper_label']) && $field['without_wrapper_label']==1 && isset($wrapper['span'])) {
					unset($wrapper['span']);
					$wrapper['empty_wrapper']=1;
				}
				$html.=all_around_visual_elements::generate_checkbox($field['label'], $field['name'], $field['value'], $wrapper, $style);
			}
			if ($field['type']=='number') $html.=all_around_visual_elements::generate_number($field['name'], $field['value'], $field['min'], $field['max'], $field['unit'],  $wrapper, $style);
			if ($field['type']=='attached_form') $html.=all_around_visual_elements::generate_div($field['name'], $generated_html, $style);
			if (isset($field['html_after'])) $html.=$field['html_after'];
		}
		return $html;
	}
	
	function generate_html_form_part($for, $id=0) {
		$html='';
		$form=array();
		$current_item_title='';
		if ($for=='empty_item') {
			$next_id=$id+1;
			$form=$this->model->items_scheme;
			for ($f=0; $f<$this->model->custom_forms_count; $f++) {
				$this->model->create_empty_custom_form($id, $f);
			}
			foreach($form as $aid => $arr) {
				if ($form[$aid]['name']=='title') {
					$form[$aid]['value'].=' '.$next_id;
					$current_item_title=$form[$aid]['value'];
				}
				$form[$aid]['name']='item_'.$id.'_'.$form[$aid]['name'];
			}			
		}
		if ($for=='item') {
			$form=$this->model->items_scheme_array[$id];
			foreach($form as $aid => $arr) {
				if ($arr['base_name']=='title') $current_item_title=$arr['value'];
			}
		}
		if ($for=='settings') {
			$hv=0;
			foreach ($this->model->settings_scheme as $id => $field) {
				if (isset($field['name'])) {
					if ($field['name']=='settings_param_hv_switch') $hv=$field['value'];
					if ($field['name']=='settings_param_wrapper_text_max_height' && $hv==1) $this->model->settings_scheme[$id]['label']='Slider width';
				}
			}
			if ($hv==1) {
				foreach ($this->model->settings_scheme as $id => $field) {
					if (isset($field['name'])) {
						if ($field['name']=='settings_param_wrapper_text_max_height') $this->model->settings_scheme[$id]['label']='Slider width';
					}
				}
			}
			$form=$this->model->settings_scheme;
		}
		
		// main task
		$html=$this->generate_html_fields($form);
		
		// finalizing
		if ($for=='empty_item') {
			if ($this->controller->ajax_call==1) $html.=$this->generate_backend_javascript(1);
			if ($current_item_title=='') $current_item_title='Item '.$next_id;
			$current_item_title.='&nbsp;&nbsp;&nbsp;<a class="all_around_delete_subitem">[Delete]</a>';
			$html = all_around_visual_elements::generate_collapsible($current_item_title, $html, NULL, TRUE, 'padding-left: 0px; padding-right: 15px;');
		}
		if ($for=='item') {
			$current_item_title.='&nbsp;&nbsp;&nbsp;<a class="all_around_delete_subitem">[Delete]</a>';
			$html=all_around_visual_elements::generate_collapsible ($current_item_title, $html, NULL, FALSE, 'padding-left: 0px; padding-right: 15px;');
		}
		if ($for=='settings') {
			$style_collapsible=array(
				'width' => '280px'
			);
			$html = all_around_visual_elements::generate_collapsible('Settings', $html, $style_collapsible, TRUE);
		}
		return $html;
	}
}

?>