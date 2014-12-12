<?php
if (! class_exists ( 'DTAgentPostType' )) {
	class DTAgentsPostType {
		
		/**
		 */
		function __construct() {
			// Add Hook into the 'init()' action
			add_action ( 'init', array (
					$this,
					'dt_init' 
			) );
			
			// Add Hook into the 'admin_init()' action
			add_action ( 'admin_init', array (
					$this,
					'dt_admin_init' 
			) );
			
			add_filter ( 'template_include', array (
					$this,
					'dt_template_include' 
			) );
		}
		
		/**
		 * A function hook that the WordPress core launches at 'init' points
		 */
		function dt_init() {
			$this->createPostType ();
			add_action ( 'save_post', array (
					$this,
					'save_post_meta' 
			) );
		}
		
		/**
		 * A function hook that the WordPress core launches at 'admin_init' points
		 */
		function dt_admin_init() {
			wp_enqueue_script ( 'jquery-ui-sortable' );
			
			remove_filter( 'manage_posts_custom_column', 'likeThisDisplayPostLikes');
			
			add_action ( 'add_meta_boxes', array (
					$this,
					'dt_add_agent_meta_box' 
			) );
			
			add_filter ( "manage_edit-dt_agents_columns", array (
					$this,
					"dt_agents_edit_columns" 
			) );
			
			add_action ( "manage_posts_custom_column", array (
					$this,
					"dt_agents_columns_display" 
			), 10, 2 );
		}
		
		/**
		 */
		function createPostType() {
			$labels = array (
					'name' => __ ( 'Agents', 'dt_themes' ),
					'all_items' => __ ( 'All Agents', 'dt_themes' ),
					'singular_name' => __ ( 'Agent', 'dt_themes' ),
					'add_new' => __ ( 'Add New', 'dt_themes' ),
					'add_new_item' => __ ( 'Add New Agent', 'dt_themes' ),
					'edit_item' => __ ( 'Edit Agent', 'dt_themes' ),
					'new_item' => __ ( 'New Agent', 'dt_themes' ),
					'view_item' => __ ( 'View Agent', 'dt_themes' ),
					'search_items' => __ ( 'Search Agents', 'dt_themes' ),
					'not_found' => __ ( 'No Agents found', 'dt_themes' ),
					'not_found_in_trash' => __ ( 'No Agents found in Trash', 'dt_themes' ),
					'parent_item_colon' => __ ( 'Parent Agent:', 'dt_themes' ),
					'menu_name' => __ ( 'Agents', 'dt_themes' ) 
			);
			
			$args = array (
					'labels' => $labels,
					'hierarchical' => false,
					'description' => 'This is custom post type agents',
					'supports' => array (
							'title',
							'editor',
							'comments',
							'thumbnail'
					),
					
					'public' => true,
					'show_ui' => true,
					'show_in_menu' => true,
					'menu_position' => 5,
					'menu_icon' => 'dashicons-businessman',
					
					'show_in_nav_menus' => true,
					'publicly_queryable' => true,
					'exclude_from_search' => false,
					'has_archive' => true,
					'query_var' => true,
					'can_export' => true,
					'rewrite' => true,
					'capability_type' => 'post' 
			);
			
			register_post_type ( 'dt_agents', $args );
			
			register_taxonomy ( "agent_entries", array (
					"dt_agents" 
			), array (
					"hierarchical" => true,
					"label" => "Categories",
					"singular_label" => "Category",
					"show_admin_column" => true,
					"rewrite" => true,
					"query_var" => true 
			) );
		}
		
		/**
		 */
		function dt_add_agent_meta_box() {
			add_meta_box ( "dt-agent-default-metabox", __ ( 'Default Options', 'dt_themes' ), array (
					$this,
					'dt_default_metabox' 
			), 'dt_agents', "normal", "default" );
		}
		
		/**
		 */
		function dt_default_metabox() {
			include_once plugin_dir_path ( __FILE__ ) . 'metaboxes/dt_agent_default_metabox.php';
		}
		
		/**
		 *
		 * @param unknown $columns        	
		 * @return multitype:
		 */
		function dt_agents_edit_columns($columns) {
			$columns = array (
				"cb" => "<input type=\"checkbox\" />",
				"dt_agent_thumb" => "Image",
				"title" => "Title",
				"agent_entries"=>"Categories",
				"likes"	=> "Likes",
				"author" => "Author"
			);
			return $columns;
		}
		
		/**
		 *
		 * @param unknown $columns
		 * @param unknown $id
		 */
		function dt_agents_columns_display($columns, $id) {
			global $post;
			
			switch ($columns) {
				
				case "dt_agent_thumb" :
				
				    $image = wp_get_attachment_image(get_post_thumbnail_id($id), array(75,75));
					if(!empty($image)):
					  	echo $image;
				    else:
						$agent_settings = get_post_meta ( $post->ID, '_agent_settings', TRUE );
						$agent_settings = is_array ( $agent_settings ) ? $agent_settings : array ();
					
						if( array_key_exists("items_thumbnail", $agent_settings)) {
							$item = $agent_settings ['items_thumbnail'] [0];
							$name = $agent_settings ['items_name'] [0];
						
							if( "video" === $name ) {
								echo '<span class="dt-video"></span>';
							}else{
								echo "<img src='{$item}' height='75px' width='75px' />";
							}
						}
					endif;
				break;
				
				case "agent_entries":
					echo get_the_term_list($post->ID, 'agent_entries', '', ', ','');
				break;
				
				case "likes":
					$likes = get_post_meta($post->ID, "_likes");
					if ($likes) {
					  echo $likes[0];
					} else {
					  echo 0;
					}
				break;
			}
		}
		
		/**
		 */
		function save_post_meta($post_id) {
			if (defined ( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE)
				return $post_id;
				
			if (!current_user_can('edit_posts'))
		        return;

		    if (!isset($id))
		        $id = (int) $post_id;
		
			if(isset($_POST['layout'])) :
			
				$settings = array ();
				$settings ['client'] = isset ( $_POST ['_client'] ) ? stripslashes ( $_POST ['_client'] ) : "";
				$settings ['location'] = isset ( $_POST ['_location'] ) ? stripslashes ( $_POST ['_location'] ) : "";
				$settings ['url'] = isset ( $_POST ['_url'] ) ? stripslashes ( $_POST ['_url'] ) : "";
				
				$settings ['mp-client-id'] = isset ( $_POST ['mp-client-id'] ) ? $_POST ['mp-client-id'] : "";
				$settings ['mp-client-secret'] = isset ( $_POST ['mp-client-secret'] ) ? $_POST ['mp-client-secret'] : "";
				
				$settings ['sub-title-bg'] = isset($_POST['sub-title-bg']) ? $_POST['sub-title-bg'] : "";
				$settings ['sub-title-bg-repeat'] = isset($_POST['sub-title-bg-repeat']) ? $_POST['sub-title-bg-repeat'] : "";
				$settings ['sub-title-bg-position'] = isset($_POST['sub-title-bg-position']) ? $_POST['sub-title-bg-position'] : "";
				$settings ['sub-title-bg-color'] = isset($_POST['sub-title-bg-color']) ? $_POST['sub-title-bg-color'] : "";
				
				$settings ['layout'] = isset ( $_POST ['layout'] ) ? $_POST ['layout'] : "";
				$settings ['show-social-share'] = isset ( $_POST ['mytheme-social-share'] ) ? $_POST ['mytheme-social-share'] : "";
				$settings ['show-related-items'] = isset ( $_POST ['mytheme-related-item'] ) ? $_POST ['mytheme-related-item'] : "";
				$settings ['comment'] = isset ( $_POST ['mytheme-agent-comment'] ) ? $_POST ['mytheme-agent-comment'] : "";
				$settings ['items'] = isset ( $_POST ['items'] ) ? $_POST ['items'] : "";
				$settings ['items_thumbnail'] = isset ( $_POST ['items_thumbnail'] ) ? $_POST ['items_thumbnail'] : "";
				$settings ['items_name'] = isset ( $_POST ['items_name'] ) ? $_POST ['items_name'] : "";
				
				update_post_meta ( $post_id, "_agent_settings", array_filter ( $settings ) );
	
				//For default category...
				$terms = wp_get_object_terms ( $post_id, 'agent_entries' );
				if (empty ( $terms )) :
					wp_set_object_terms ( $post_id, 'Uncategorized', 'agent_entries', true );
				endif;
				
			endif;
		}
		
		/**
		 * To load agent pages in front end
		 *
		 * @param string $template        	
		 * @return string
		 */
		function dt_template_include($template) {
			if (is_singular( 'dt_agents' )) {
				if (! file_exists ( get_stylesheet_directory () . '/single-dt_agents.php' )) {
					$template = plugin_dir_path ( __FILE__ ) . 'templates/single-dt_agents.php';
				}
			} elseif (is_tax ( 'agent_entries' )) {
				if (! file_exists ( get_stylesheet_directory () . '/taxonomy-agent_entries.php' )) {
					$template = plugin_dir_path ( __FILE__ ) . 'templates/taxonomy-agent_entries.php';
				}
			}
			return $template;
		}
	}
}
?>