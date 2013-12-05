<?php


class all_around_main_class {

	public $wrapper, $db_prefix, $wrapper_class_file, $path, $url, $admin_url, $plugin_name, $plugin_code_name, $plugin_version, $settings, $settings_status, $settings_in_db, $checked_for_upgrade, $add_admin_menu, $add_js, $add_separated_js, $add_css, $add_ajax_hook, $mode, $alternative_jquery, $additional_scripts_loaded, $additional_functions_loaded;

	function __construct(all_around_wrapper_admin &$wrapper, $wrapper_class_file, $code_name, $name, $version, $mode) {
		$this->add_admin_menu=array();
		$this->add_js=array();
		$this->add_separated_js=array();
		$this->add_css=array();

		$this->wrapper=$wrapper;
		$this->db_prefix=$this->wrapper->get_db_prefix();
		$this->wrapper_class_file = $wrapper_class_file;
		$this->main_object_file = __FILE__;

		$this->plugin_code_name = $code_name;
		$this->plugin_name = $name;
		$this->plugin_version = $version;
		$this->mode = $mode;
		$this->settings=array();
		$this->settings_status=0;
		$this->settings_in_db=array();
		$this->checked_for_upgrade=0;
		$this->alternative_jquery=0;
		$this->additional_scripts_loaded=0;
		//$this->add_settings(array('version' => '1.4'));exit;*/

		$this->path = dirname( $this->main_object_file );
		$plugin_basename = basename( $this->path );
		$this->url = $this->wrapper->get_plugins_url()."/".$plugin_basename."/";	// with backslash!!!!
		$this->get_settings();

		$this->add_admin_menu[0] = array ('slug'=>$this->plugin_code_name, 'title'=>$this->plugin_name, 'func'=>'page_main_admin_page');
		$this->add_admin_menu[0][0] = array ('slug'=>$this->plugin_code_name.'&action=new', 'title'=>'Add New', 'func' => '');

		$this->add_js[]=array('layer'=>'backend', 'name'=>$this->plugin_code_name.'-admin-visual-js', 'url'=>$this->url.'js/backend/backend_visual.js');
		$this->add_js[]=array('layer'=>'backend', 'name'=>$this->plugin_code_name.'-admin-js', 'url'=>$this->url.'js/backend/backend.js');
		$this->add_js[]=array('layer'=>'backend', 'name'=>'jquery-ui-slider', 'url'=>'');
//		$this->add_js[]=array('layer'=>'backend', 'name'=>'jquery-ui-slider', 'url'=>'');
		$this->add_js[]=array('layer'=>'backend', 'name'=>'iris', 'url'=>'');

		$this->add_js[]=array('layer'=>'both', 'name'=>$this->plugin_code_name.'-js', 'url'=>$this->url.'js/frontend/jquery.content_slider.js');
		$this->add_js[]=array('layer'=>'both', 'name'=>'jQuery-mousewheel', 'url'=>$this->url.'js/frontend/jquery.mousewheel.js');
		$this->add_js[]=array('layer'=>'both', 'name'=>'jQuery-prettyPhoto', 'url'=>$this->url.'js/frontend/jquery.prettyPhoto.js');
		$this->add_js[]=array('layer'=>'both', 'name'=>'all_around-additional', 'url'=>$this->url.'js/frontend/jquery.additional_content.js');
		$this->add_js[]=array('layer'=>'both', 'name'=>'all_around-animate-colors', 'url'=>$this->url.'js/frontend/jquery.animate-colors.js');

		$this->add_separated_js[]=array('layer'=>'both', 'name'=>$this->plugin_code_name.'-js', 'url'=>$this->url.'js/frontend/separated/jquery.content_slider.js');
		$this->add_separated_js[]=array('layer'=>'both', 'name'=>'jQuery-mousewheel', 'url'=>$this->url.'js/frontend/separated/jquery.mousewheel.js');
		$this->add_separated_js[]=array('layer'=>'both', 'name'=>'jQuery-prettyPhoto', 'url'=>$this->url.'js/frontend/separated/jquery.prettyPhoto.js');
		$this->add_separated_js[]=array('layer'=>'both', 'name'=>'all_around-additional', 'url'=>$this->url.'js/frontend/separated/jquery.additional_content.js');
		$this->add_separated_js[]=array('layer'=>'both', 'name'=>'all_around-animate-colors', 'url'=>$this->url.'js/frontend/separated/jquery.animate-colors.js');

		$this->add_css[]=array('layer'=>'backend', 'name'=>$this->plugin_code_name.'-admin-css', 'url'=>$this->url.'css/backend/admin.css');
		$this->add_css[]=array('layer'=>'both', 'name'=>$this->plugin_code_name.'-css', 'url'=>$this->url.'css/frontend/content_slider_style.css');
		$this->add_css[]=array('layer'=>'both', 'name'=>'prettyPhoto-css', 'url'=>$this->url.'css/frontend/prettyPhoto.css');
		$this->add_css[]=array('layer'=>'both', 'name'=>'dosis-css', 'url'=>'http://fonts.googleapis.com/css?family=Dosis:400,200,300,500,600,700,800');
		
		//$this->add_ajax_hook['ajax_all_around_save'] = array(&$this, 'ajax_save');  
		//$this->add_ajax_hook['ajax_all_around_preview'] = array(&$this, 'ajax_preview');
		$this->add_ajax_hook['ajax_all_around_post_search'] = array(&$this, 'ajax_post_search');
		$this->add_ajax_hook['ajax_all_around_get_categories_listbox'] = array(&$this, 'ajax_get_categories_listbox');
		//$this->add_ajax_hook['ajax_all_around_post_get'] = array(&$this, 'ajax_post_get');
		//$this->add_ajax_hook['ajax_all_around_post_category_get'] = array(&$this, 'ajax_post_category_get');
		$this->add_ajax_hook['ajax_all_around_set_settings_2val'] = array(&$this, 'ajax_set_settings_2val');
		$this->add_ajax_hook['ajax_all_around_set_settings_1val'] = array(&$this, 'ajax_set_settings_1val');
		$this->add_ajax_hook['ajax_all_around_get_responder_answer'] = array(&$this, 'ajax_get_responder_answer');
		//$this->add_ajax_hook['ajax_all_around_download_google_fonts'] = array(&$this, 'ajax_download_google_fonts');
		//$this->add_ajax_hook['ajax_all_around_put_in_element'] = array(&$this, 'ajax_put_in_element');
		//$this->add_ajax_hook['ajax_all_around_get_thumb'] = array(&$this, 'ajax_get_thumb');



		//return $this;
	}
	
	function load_additional_scripts() {
		if ($this->additional_scripts_loaded==1) return FALSE;
		$this->additional_scripts_loaded=1;
		require_once($this->path . '/includes/mvc.php');
		$this->mvc=new all_around_mvc_controller($this->wrapper, $this);
	
		return TRUE;
	}

	function load_additional_functions() {
		if ($this->additional_functions_loaded==1) return FALSE;
		$this->additional_functions_loaded=1;
		require_once($this->path . '/includes/functions.php');
		all_around_functions::$main_object=&$this;
		all_around_functions::$wrapper=&$this->wrapper;
	}
	
	function backend_init() {
		$this->admin_url=$this->wrapper->get_admin_url();
		$this->load_additional_scripts();
		$this->settings['use_separated_jquery']=0;
		require_once($this->path . '/includes/visual_elements.php');
		require_once($this->path . '/includes/viewer.php');
		$this->check_for_upgrade();
		//$this->wrapper->backend_init($this);

	}
	function frontend_init() {
	}
	
	function get_thumb_table_name() {
		return $this->db_prefix.$this->plugin_code_name.'_thmb';
	}
	function get_plugin_table_name() {
		return $this->db_prefix.$this->plugin_code_name;
	}

	function install_plugin() {
		$thmb_table_name = $this->get_thumb_table_name();
		if ($this->wrapper->db_get_var("SHOW TABLES LIKE '".$thmb_table_name."'") != $thmb_table_name) {
			$sql = "CREATE TABLE " . $thmb_table_name ." (
						`id` int(4) NOT NULL AUTO_INCREMENT,
						`ukey` varchar(512) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
						`orig_url` varchar(512) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
						`orig_file` varchar(512) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
						`dest_url` varchar(512) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
						`dest_file` varchar(512) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
						`width` int(4) NOT NULL DEFAULT '0',
						`height` int(4) NOT NULL DEFAULT '0',
						`filters` varchar(256) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
						`version` int(4) NOT NULL DEFAULT '0',
						PRIMARY KEY (`id`),
						UNIQUE KEY `ukey` (`ukey`)
					);";
			$this->wrapper->db_query($sql);
		}

		$table_name = $this->get_plugin_table_name();
		if ($this->wrapper->db_get_var("SHOW TABLES LIKE '".$table_name."'") != $table_name) {
			$sql = "CREATE TABLE " . $table_name ." (
				  id INT(4) NOT NULL AUTO_INCREMENT,
				  name TINYTEXT NOT NULL COLLATE utf8_general_ci,
				  settings TEXT NOT NULL COLLATE utf8_general_ci,
				  items MEDIUMTEXT NOT NULL COLLATE utf8_general_ci,
				  PRIMARY KEY (id)
				);";	
			$this->wrapper->activation_sql($sql);
		}
	}
	
	function check_for_upgrade() {
		if ($this->checked_for_upgrade==1) return;
		if (count($this->settings)==0) $this->get_settings();
		if (!isset($this->settings_in_db['version'])) $version='1';
		else $version=$this->settings_in_db['version'];
		$old_version=self::version_to_number($version);
		$current_version=self::version_to_number($this->plugin_version);
		if ($old_version!=$current_version) $this->upgrade($old_version, $current_version);
		$this->checked_for_upgrade=1;
	}

	function upgrade ($old_version, $current_version) {
		global $wpdb;
		if ($old_version!=$current_version) {
/*
			//echo $old_version.", ".$current_version; exit;
			if ($old_version<self::version_to_number('1.4'))
			{
				$table_name = $wpdb->base_prefix . 'options';
				$v = $wpdb->get_var('SELECT autoload FROM '.$table_name.' WHERE option_name="all_around_settings"');
				if ($v=='yes') $wpdb->query('UPDATE '.$table_name.' SET autoload="no" WHERE option_name="all_around_settings"');
			}
*/
			$this->add_settings(array(
				'version' => $this->plugin_version
			));
		}
	}

	static function version_to_number($version)
	{
		$version=strval($version);
		$arr=explode('.', $version);
		if (count($arr)<1) $arr[0]='0';
		if (count($arr)<2) $arr[1]='0';
		if (count($arr)<3) $arr[2]='0';
		$arr[0]=str_pad($arr[0], 3, '0', STR_PAD_LEFT);
		$arr[1]=str_pad($arr[1], 3, '0', STR_PAD_LEFT);
		$arr[2]=str_pad($arr[2], 3, '0', STR_PAD_LEFT);
		$r=$arr[0].$arr[1].$arr[2];
		return intval($r);
	}
	
	function get_settings($skip_cache=false) {
		if ($skip_cache==false && count($this->settings)) return $this->settings;

		$this->settings=array(
			'use_new_jquery' => 0,
			'use_prettyPhoto' => 0,
			'new_jquery_url' => 'http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js',
			'last_update_check' => '10-11-2012',
			'version' => $this->plugin_version,
			'google_fonts_update' => '01-2013',
			'use_separated_jquery' => 0
		);

		$this->settings_status=1;

		$a = array();
		$s=$this->wrapper->get_option('all_around_settings');
		if ($s!==FALSE) {
			if ($s!="") {
				$a=@unserialize($s);
				if (is_array($a)) $this->settings_in_db=$a;				
			}
		}
		
		if (is_array($a)) if (count($a)) $this->settings_status=2;

		if (is_array($a)) {
			foreach ($a as $var => $val) {
				$this->settings[$var] = $val;
			}
		}
		
		return $this->settings;
	}

	function add_settings($a) {
		$this->get_settings(true);
		//echo '<pre>add_settings: '; print_r($this->settings); echo '</pre><hr />'; exit;
		if (!is_array($a)) $a=array($a);
		foreach ($a as $var => $val) {
			$this->settings[$var] = $val;
		}
		$s=serialize($this->settings);
		if ($this->settings_status==2) {
			$this->wrapper->update_option('all_around_settings', $s);
		}
		else {
			$r=$this->wrapper->add_option('all_around_settings', $s, '', 'no');
			if ($r==false) $this->wrapper->update_option('all_around_settings', $s);
		}
		return TRUE;
	}
	function save_settings($a) {	// just a alias for add_settings()
		return $this->add_settings($a);
	}


	function inline_header() {
		
		if ($this->settings['use_separated_jquery']) $this->inline_js_include();

		$arr=$this->wrapper->find_all_shortcodes_on_page();
		if (count($arr)==0) return;

		$this->load_additional_scripts();
		$frontHtml='';
		$frontHtml=$this->mvc->frontend_header_function($arr);
		echo $frontHtml;
	}

	function inline_js_include() {
		$jqueryurl = $this->settings['new_jquery_url'];
		
		// ---------------- ATTENTION ------------------
		// This piece of code will be used only if users template is using old jQuery.
		// This is the only way to avoid old jQuery, not removing old jQuery, but bringing 
		// totaly separated new version of jQuery and not replacing old jQuery,
		// because old jQuery must be kept in order to run old jQuery plugins which could not 
		// be run by new jQuery.
		// We are using inline .js including because this is the only way to 'copy' new 
		// jQuery immeditely after including it.
		// This piece of code is just a option - not activated by default.
		// By default - script will use wp_enqueue_script() function.

			echo <<<eof
<script type='text/javascript'>
	if (typeof jQuery != 'undefined') var all_around_main_jquery1_backup = jQuery;
	if (typeof $ != 'undefined') var all_around_main_jquery2_backup = $;
</script>
<script type='text/javascript' src='$jqueryurl'></script>
<script type='text/javascript'>
	var all_around_jQuery = jQuery.noConflict();
	if (typeof all_around_main_jquery1_backup != 'undefined') jQuery = all_around_main_jquery1_backup;
	if (typeof all_around_main_jquery2_backup != 'undefined') $ = all_around_main_jquery2_backup;
</script>

eof;
		foreach ($this->add_separated_js as $arr) {
			echo "<script type='text/javascript' src='".$arr['url']."'></script>\n";
		}
		$this->alternative_jquery=1;
	}
	
	function frontend_shortcode($id) {
		$this->load_additional_scripts();
		return $this->mvc->frontend_body_function($id);
	}

	function ajax_set_settings_1val() {
		$var1 = $_POST['var1'];
		$val1 = $_POST['val1'];
		$a[$var1]=$val1;
		//print_r($a);
		$this->add_settings($a);
		//echo 'Saved!';
		$this->mvc->ajax_return(1, 'Saved!');
		die();
	}

	function ajax_set_settings_2val() {
		$var1 = $_POST['var1'];
		$val1 = $_POST['val1'];
		$var2 = $_POST['var2'];
		$val2 = $_POST['val2'];
		$a[$var1]=$val1;
		$a[$var2]=$val2;
		$this->add_settings($a);
		//echo 'Saved!';
		$this->mvc->ajax_return(1, 'Saved!');
		die();
	}

	function get_responder_answer($action, $var1='', $var2='') {
		$plugin_name=$this->plugin_code_name;
		$url='http://www.shindiristudio.com/responder/responder.php?plugin_name='.$plugin_name.'&action='.$action.'&var1='.$var1.'&var2='.$var2;
		$r=$this->wrapper->get_remote ($url);
		$this->mvc->ajax_return(1, $r);
		return $r;
	}
	
	function ajax_get_responder_answer() {
		$action='';
		$var1='';
		$var2='';
		if (isset($_POST['action2'])) $action=$_POST['action2'];
		if (isset($_POST['var1'])) $var1=$_POST['var1'];
		if (isset($_POST['var2'])) $var1=$_POST['var2'];
		if ($action!='') echo $this->get_responder_answer($action, $var1, $var2);
		die();
	}
	
	function ajax_post_search(){
		if(isset($_POST['query']) && !empty($_POST['query'])){
			$searchVal = strtolower($_POST['query']);
		}
		else {
			$this->mvc->ajax_return(1, '');
		}
		
		$arr=$this->wrapper->post_search($searchVal);
	
		$buffer='';
		foreach ($arr as $row) {
			$buffer.='<li><a class="all_around_search_li_a" data-id="'.$row['id'].'"><img style="margin-right: 5px; width: 32px; height: 32px;" src="'.$row['thumbnail'].'" alt="" /><span class="all_around_search_li_span">'.$row['title'].'</span><span class="clear"></span></a></li>';
		}
		$this->mvc->ajax_return(1, $buffer);
	}

	function ajax_get_categories_listbox(){
		$name='all_around_category_select';
		if (isset($_POST['listbox_name'])) $name=$_POST['listbox_name'];
		$categories=$this->wrapper->get_categories();
		$buffer='<select style="width:200px" id="'.$name.'" name="'.$name.'">';
		foreach ($categories as $category) {
			$buffer.='<option value="'.$category['id'].'">'.$category['name'].'</option>';
		}
		$buffer.='</select>';
		$this->mvc->ajax_return(1, $buffer);		
	}

	
/*	function ajax_download_google_fonts() {
		include_once($this->path . '/pages/all_around_http_functions.php');
		$fonts=all_around_get_http ('http://www.shindiristudio.com/responder/fonts.txt', 8);
		$now = date('m-Y');
		if (strlen($fonts)>1000) {
			$this -> add_settings(array(
				'google_fonts' => $fonts,
				'google_fonts_update' => $now
			));
		}
		die();
	}
	
	function ajax_put_in_element() {
		require_once($this->path . '/pages/all_around_functions.php');
		if ($_POST['key']=='get_font_variants') {
			$fonts_array = all_around_get_font_array($this->settings, $this->path);
			$fonts_assoc_array = $fonts_array['assoc'];
			$variant_array = all_around_generate_font_variants_array($fonts_assoc_array, $_POST['val']);
			echo all_around_generate_font_variants_list($variant_array);
		}
		die();
	}
	
	function ajax_get_thumb() {
		require_once($this->path . '/pages/all_around_functions.php');
		if (isset($_POST['url']) && isset($_POST['w']) && isset($_POST['h'])) {
			$url=$_POST['url'];
			$w=intval($_POST['w']);
			$h=intval($_POST['h']);
			$gray=0;
			if (isset($_POST['gray'])) $gray=intval($_POST['gray']);
			echo all_around_cache_image ($url, $w, $h, $gray);
		}
		die();
	}
*/
	function page_main_admin_page() {
		$viewer = new all_around_viewer($this->wrapper, $this);
		
		if (isset($_GET['action'])) {
			if ($_GET['action']=='new') $viewer->generate_form('new');
			if ($_GET['action']=='edit') $viewer->generate_form($_GET['id']);
			if ($_GET['action']=='delete') {
				$this->mvc->delete($_GET['id']);
				$viewer->generate_index();
			}
		} else {
			$viewer->generate_index();
		}
	}

	function get_cached_image ($url, $w, $h, $gray=0) {
		if ($url=='') return '';
		$opt=array();
		if ($gray) $opt=array('gray');
		$this->load_additional_functions();
		all_around_functions::makethumb_image_db($url, $w, $h, $opt, '', '', $carr);
		return $carr['dest_url'];
	}
}
?>
