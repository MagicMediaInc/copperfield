<?php
/*
 * Plugin Name:	DesignThemes Core Features Plugin 
 * URI: 	http://wedesignthemes.com/plugins/designthemes-core-features 
 * Description: A simple wordpress plugin designed to implements <strong>core features of DesignThemes</strong> 
 * Version: 	1.1 
 * Author: 		DesignThemes 
 * Author URI:	http://themeforest.net/user/designthemes
 */
if (! class_exists ( 'DTCorePlugin' )) {
	
	/**
	 * Basic class to load Shortcodes & Custom Posts
	 *
	 * @author iamdesigning11
	 */
	class DTCorePlugin {
		function __construct() {
			
			$this->plugin_dir_path = plugin_dir_path ( __FILE__ );
			// Add Hook into the 'init()' action
			add_action ( 'init', array (
					$this,
					'dtLoadPluginTextDomain' 
			) );
			// Register Shortcodes
			require_once plugin_dir_path ( __FILE__ ) . '/shortcodes/register-shortcodes.php';

			require_once plugin_dir_path ( __FILE__ ) . '/agents/agents.php';
			
			if (class_exists ( 'DTCoreShortcodes' )) {
				$dt_core_shortcodes = new DTCoreShortcodes ();
			}

			// Register Custom Post Types
			require_once plugin_dir_path ( __FILE__ ) . '/custom-post-types/register-post-types.php';
			
			if (class_exists ( 'DTCoreCustomPostTypes' )) {
				$dt_core_custom_posts = new DTCoreCustomPostTypes ();
			}

			// Register Page Builder
			require_once plugin_dir_path ( __FILE__ ) . '/page-builder/register-page-builder.php';
			
			if (class_exists ( 'DTCorePageBuilder' )) {
				$dt_core_page_builder = new DTCorePageBuilder ();
			}
		}
		
		/**
		 * To load text domain
		 */
		function dtLoadPluginTextDomain() {
			load_plugin_textdomain ( 'dt_themes', false, dirname ( plugin_basename ( __FILE__ ) ) . '/languages/' );
		}
		
		/**
		 */
		public static function dtCorePluginActivate() {
		}
		
		/**
		 */
		public static function dtCorePluginDectivate() {
		}
	}
}

if (class_exists ( 'DTCorePlugin' )) {
	
	register_activation_hook ( __FILE__, array (
			'DTCorePlugin',
			'dtCorePluginActivate' 
	) );
	register_deactivation_hook ( __FILE__, array (
			'DTCorePlugin',
			'dtCorePluginDectivate' 
	) );
	
	$dt_core_plugin = new DTCorePlugin ();
}
?>