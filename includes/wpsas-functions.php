<?php
add_action( 'admin_init', 'wpsas_settings_control' );
if(get_option('wpsas_simplified')){
	add_action( 'admin_init', 'wpsas_simplify_admin_sidebar' );
	add_action( 'load-options-general.php', 'wpsas_simplify_admin_general', 1 );
	add_action( 'load-options-writing.php', 'wpsas_simplify_admin_writing', 1 );
	add_action( 'load-options-reading.php', 'wpsas_simplify_admin_reading', 1 );
	add_action( 'load-options-discussion.php', 'wpsas_simplify_admin_discussion', 1 );
}

/**
 *  wpsas_settings_control
 */
function wpsas_settings_control() {
	add_settings_section(
		'wpsas_settings_section',
		'Modo de administración',
		function(){},
		'general'
	);
	add_settings_field(
		'wpsas_simplified',
		'Panel de administración simplificado',
		function(){echo '<label><input name="wpsas_simplified" id="wpsas_simplified" type="checkbox" value="1" class="code" ' . 
						checked( 1, get_option( 'wpsas_simplified' ), false ) . ' /> (Ante la duda, dejar tildado)<label>';},
		'general',
		'wpsas_settings_section'
	);
	register_setting( 'general', 'wpsas_simplified');
}

/**
 * wpsas_simplify_admin_sidebar
 */

function wpsas_simplify_admin_sidebar() {
	foreach(array('themes.php','plugins.php','edit.php?post_type=page','tools.php') as $a){remove_menu_page($a);}
	remove_submenu_page('edit.php','edit-tags.php?taxonomy=category');
	remove_submenu_page('options-general.php','options-permalink.php');
	remove_submenu_page('options-general.php','options-media.php');	
	remove_submenu_page('options-general.php','tinymce-advanced');
}

/**
 * Reemplaza admin templates
 */
 
function wpsas_simplify_admin_general(){
    require_once plugin_dir_path(__FILE__) . '/adminpages/options-general.php';
	die();
} 
function wpsas_simplify_admin_writing(){
    require_once plugin_dir_path(__FILE__) . '/adminpages/options-writing.php';
	die();
} 
function wpsas_simplify_admin_reading(){
    require_once plugin_dir_path(__FILE__) . '/adminpages/options-reading.php';
	die();
} 
function wpsas_simplify_admin_discussion(){
    require_once plugin_dir_path(__FILE__) . '/adminpages/options-discussion.php';
	die();
} 
