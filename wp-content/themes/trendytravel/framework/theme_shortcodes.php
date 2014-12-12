<?php
#CONTACT FORM SHORTCODE...
if(!function_exists('dt_contact_form')) {
	
	function dt_contact_form( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'to_email' => get_bloginfo('admin_email'),
			'success_msg' => __('Thanks for Contacting Us, We will call back to you soon.', 'iamd_text_domain'),
			'error_msg' => __('Sorry your message not sent, Try again Later.', 'iamd_text_domain')
		), $atts));
		
		$out = '';
		$out .= '<div id="ajax_message"> </div>';
		$out .= '<form class="wpcf7-form contact-frm" method="post" action="'.get_template_directory_uri().'/framework/sendmail.php">';
			$out .= '<div class="dt-sc-one-half column first">';
				$out .= '<p><input type="text" name="cname" placeholder="'.__('Name *', 'iamd_text_domain').'" required="required" /></p>';
				$out .= '<p><input type="email" name="cemail" placeholder="'.__('Email *','iamd_text_domain').'" required="required" /></p>';
				$out .= '<p><input type="text" name="csubject" placeholder="'.__('Subject', 'iamd_text_domain').'" /></p>';
			$out .= '</div>';
			$out .= '<p class="dt-sc-one-half column"><textarea cols="12" rows="7" name="cmessage" placeholder="'.__('Message *', 'iamd_text_domain').'"></textarea></p>';
			$out .= '<input type="submit" name="submit" value="'.__('Send Message', 'iamd_text_domain').'" />';
			$out .= '<input type="hidden" name="hidadminemail" value="'.$to_email.'" />';
			$out .= '<input type="hidden" name="hidsuccess" value="'.$success_msg.'" />';
			$out .= '<input type="hidden" name="hiderror" value="'.$error_msg.'" />';
		$out .= '</form>';
	
		return $out;
	}
	add_shortcode('dt_contact_form', 'dt_contact_form');
	add_shortcode('dt_sc_contact_form', 'dt_contact_form');
}

#SOCIAL ICONS...
if(!function_exists('dt_social')) {
	
	function dt_social($attrs, $content=null,$shortcodename="") {
		
		extract(shortcode_atts(array(
			'text' => ''
		), $attrs));
		
		$text = !empty($text) ? "<p class='social-media-text'>$text</p>" : "";
		
		$dt_theme_options = get_option(IAMD_THEME_SETTINGS);
		
		$out = "";

		if(is_array($dt_theme_options['social'])):
			$out .= $text;
			$out .= "<ul class='dt-sc-social-icons'>";
			foreach($dt_theme_options['social'] as $social):
				$link = $social['link'];
				$icon = $social['icon'];
				$out .= "<li class='".substr($icon, 3)."'>";
				$out .= "<a class='fa {$icon}' href='{$link}'></a>";
				$out .= "</li>";
			endforeach;
			$out .= "</ul>";
		endif;			
		
		return $out;
	}
	add_shortcode('dt_social','dt_social'); 
}

#BLOG LIST...
if(!function_exists('dt_blog_posts')) {

	function dt_blog_posts( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'excerpt_length' => 25,
			'show_meta' => 'true',
			'limit' => -1,
			'categories' => '',
			'posts_column' => 'one-third-column',		// one-column, one-half-column, one-third-column, one-fourth-column
		), $atts));
		
		global $post;
	
		$meta_set = get_post_meta(get_queried_object_id(), '_tpl_default_settings', true);
		$page_layout = !empty($meta_set['layout']) ? $meta_set['layout'] : 'content-full-width';
		$post_layout = $posts_column;
		
		$article_class = "";
		$feature_image = "blog-full";
		$column = ""; $out = "";
	
		//POST LAYOUT CHECK...
		if($post_layout == "one-column") {
			$article_class = "column dt-sc-one-column";
		}
		elseif($post_layout == "one-half-column") {
			$article_class = "column dt-sc-one-half";
			$column = 2;
		}
		elseif($post_layout == "one-third-column") {
			$article_class = "column dt-sc-one-third";
			$column = 3;
		}
		elseif($post_layout == "one-fourth-column") {
			$article_class = "column dt-sc-one-fourth";
			$column = 4;
		}
		
		//PAGE LAYOUT CHECK...
		if($page_layout != "content-full-width") {
			$article_class = $article_class." with-sidebar";
		}
		
		//POST VALUES....
		if($categories == "") $categories = 0;
	
		$args = array('post_type' => 'post', 'posts_per_page' => $limit, 'cat' => $categories, 'ignore_sticky_posts' => 1);
		$the_query = new WP_Query($args);
		
		if($the_query->have_posts()): $i = 1;
		 $out .= '<div class="blog-entry-posts">';
		 while($the_query->have_posts()): $the_query->the_post();
			
			$temp_class = "";
			
			if($i == 1) $temp_class = $article_class." first"; else $temp_class = $article_class;
			if($i == $column) $i = 1; else $i = $i + 1;
			
				$out .= '<div class="'.$temp_class.'">';
					$out .= '<div id="post-'.get_the_ID().'" class="'.implode(" ", get_post_class("entry-post", $post->ID)).'">';
							if($show_meta != "false"):
								$out .= '<div class="entry-date">';
									$out .= '<p>'.get_the_date('d').'<span>'.get_the_date('M').'&nbsp;'.get_the_date('Y').'</span></p>';
									$out .= '<span></span>';
								$out .= '</div>';
							endif;

							$out .= '<div class="entry-container">';
								$format = get_post_format();
								
								$out .= '<div class="entry-thumb">';
									if(is_sticky()):
										$out .= '<div class="featured-post"><span>'.__('Featured','iamd_text_domain').'</span></div>';
									endif;
									
									//Post Format Starts...
									if( $format === "image" || empty($format) ):
									  $out .= '<a href="'.get_permalink().'" title="'.get_the_title().'">';
									  if(has_post_thumbnail()):
										  $attr = array('title' => get_the_title()); $out .= get_the_post_thumbnail($post->ID, $feature_image, $attr);
									  else:
										  $out .= '<img src="http://placehold.it/1170x800&text=Image" width="1170" height="800" alt="'.get_the_title().'" />';
									  endif;
									  $out .= '<div class="image-overlay"><span class="image-overlay-inside"></span></div>';
									  $out .= '</a>';
									elseif( $format === "gallery" ):
									  $post_meta = get_post_meta($post->ID ,'_dt_post_settings', true);
									  if( @array_key_exists("items", $post_meta) ):
										  $out .= "<ul class='entry-gallery-post-slider'>";
										  foreach ( $post_meta['items'] as $item ) { $out .= "<li><img src='{$item}' alt='gal-img' /></li>"; }
										  $out .= "</ul>";
										  $out .= '<div class="image-overlay"><span class="image-overlay-inside"></span></div>';
									  endif;
									elseif( $format === "video" ):
										  $post_meta =  get_post_meta($post->ID ,'_dt_post_settings', true);
										  if( @array_key_exists('oembed-url', $post_meta) || array_key_exists('self-hosted-url', $post_meta) ):
											  if( array_key_exists('oembed-url', $post_meta) ):
												  $out .= "<div class='dt-video-wrap'>".wp_oembed_get($post_meta['oembed-url']).'</div>';
											  elseif( array_key_exists('self-hosted-url', $post_meta) ):
												  $out .= "<div class='dt-video-wrap'>".apply_filters( 'the_content', $post_meta['self-hosted-url'] ).'</div>';
											  endif;
										  endif;
									elseif( $format === "audio" ):
										  $post_meta =  get_post_meta($post->ID ,'_dt_post_settings', true);
										  if( @array_key_exists('oembed-url', $post_meta) || array_key_exists('self-hosted-url', $post_meta) ):
											  if( array_key_exists('oembed-url', $post_meta) ):
												  $out .= wp_oembed_get($post_meta['oembed-url']);
											  elseif( array_key_exists('self-hosted-url', $post_meta) ):
												  $out .= apply_filters( 'the_content', $post_meta['self-hosted-url'] );
											  endif;
										  endif;
									else:
									  $out .= '<a href="'.get_permalink().'" title="'.get_the_title().'">';
									  if(has_post_thumbnail()):
										  $attr = array('title' => get_the_title()); $out .= get_the_post_thumbnail($post->ID, $feature_image, $attr);
									  else:
										  $out .= '<img src="http://placehold.it/1170x800&text=Image" width="1170" height="800" alt="'.get_the_title().'" />';
									  endif;
									  $out .= '<div class="image-overlay"><span class="image-overlay-inside"></span></div>';
									  $out .= '</a>';
								   endif;
								$out .= '</div>';
							
								$out .= '<div class="entry-detail">';
									$out .= '<h2><a href="'.get_permalink().'">'.get_the_title().'</a></h2>';
									if($show_meta != "false"):								
										$out .= '<ul class="entry-post-meta">';
											$out .= '<li><span class="fa fa-user"></span>'.__('Posted By: ', 'iamd_text_domain').'<a href="'.get_author_posts_url(get_the_author_meta('ID')).'">'.get_the_author_meta('display_name').'</a></li>';
											$out .= '<li>';
												$commtext = "";
												if((wp_count_comments($post->ID)->approved) == 0)	$commtext = '0';
												else $commtext = wp_count_comments($post->ID)->approved;
												$out .= '<a href="'.get_permalink().'/#comments"><span class="fa fa-comment"></span>'.$commtext.'</a>';
											$out .= '</li>';
										$out .= '</ul>';
									endif;
									if($excerpt_length != "" || $excerpt_length != 0) $out .= dt_theme_excerpt($excerpt_length);
									
									$out .= '<p class="aligncenter"><a href="'.get_permalink().'" class="dt-sc-button too-small">'.__('Read More','iamd_text_domain').'</a></p>';
								$out .= '</div>';
							$out .= '</div>';
					$out .= '</div>';
				$out .= '</div>';
		 endwhile;
		$out .= '</div>';
		else:
			$out .= '<h2>'.__('Nothing Found.', 'iamd_text_domain').'</h2>';
			$out .= '<p>'.__('Apologies, but no results were found for the requested archive.', 'iamd_text_domain').'</p>';
		endif;
		
		return $out;
	}
	add_shortcode('dt_blog_posts', 'dt_blog_posts');
	add_shortcode('dt_sc_blogposts', 'dt_blog_posts');
}

//FB LIKE...
add_shortcode('fblike','fblike');
function fblike( $attrs = null, $content = null,$shortcodename ="" ){
	extract(shortcode_atts(array('layout'=>'box_count','width'=>'','height'=>'','send'=>false,'show_faces'=>false,'action'=>'like','font'=> 'lucida+grande'
				,'colorscheme'=>'light'), $attrs));

	if ($layout == 'standard') { $width = '450'; $height = '35';  if ($show_faces == 'true') { $height = '80'; } }
	if ($layout == 'box_count') { $width = '55'; $height = '65'; }
	if ($layout == 'button_count') { $width = '90'; $height = '20'; }
	$layout = 'data-layout = "'.$layout.'" ';
	$width = 'data-width = "'.$width.'" ';
	$font = 'data-font = "'.str_replace("+", " ", $font).'" ';
	$colorscheme = 'data-colorscheme = "'.$colorscheme.'" ';
	$action = 'data-action = "'.$action.'" ';
	if ( $show_faces ) { $show_faces = 'data-show-faces = "true" '; } else { $show_faces = ''; }
	if ( $send ) { $send = 'data-send = "true" '; } else { $send = ''; }
	
    $out = '<div id="fb-root"></div><script>(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";fjs.parentNode.insertBefore(js, fjs);}(document, "script", "facebook-jssdk"));</script>';
	$out .= '<div class = "fb-like" data-href = "'.get_permalink().'" '.$layout.$width.$font.$colorscheme.$action.$show_faces.$send.'></div>';
return $out;
}

//GOOGLE PLUS...
add_shortcode('googleplusone','googleplusone');	
function googleplusone( $attrs = null, $content = null,$shortcodename ="" ){
	extract(shortcode_atts(array('size'=> '','lang'=> ''), $attrs));
	$size = empty($size) ? "size='small'" : "size='{$size}'";
	$lang = empty($lang) ? "{lang:en_GB}" : "{lang:'{$lang}'}";
	
	$out = '<script type="text/javascript" src="https://apis.google.com/js/plusone.js">'.$lang.'</script>';
	$out .= '<g:plusone '.$size.'></g:plusone>';
	return $out;
}

//TWITTER BUTTON...
add_shortcode('twitter','twitter');
function twitter( $attrs = null, $content = null,$shortcodename ="" ){
	extract(shortcode_atts(array('layout'=>'vertical','username'=>'','text'=>'','url'=>'','related'=> '','lang'=> ''), $attrs));
	
	$p_url= get_permalink();
	$p_title = get_the_title();
	
	$text = !empty($text) ? "data-text='{$text}'" :"data-text='{$p_title}'";
	$url = !empty($url) ? "data-url='{$url}'" :"data-url='{$p_url}'";
	$related = !empty($related) ? "data-related='{$related}'" :'';
	$lang = !empty($lang) ? "data-lang='{$lang}'" :'';
	$twitter_url = "http://twitter.com/share";
		$out = '<a href="{$twitter_url}" class="twitter-share-button" '.$url.' '.$lang.' '.$text.' '.$related.' data-count="'.$layout.'" data-via="'.$username.'">'.
	__('Tweet','iamd_text_domain').'</a>';
		$out .= '<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>';
	return $out;	
}

//STUMBLEUPON...
add_shortcode('stumbleupon','stumbleupon');
function stumbleupon( $attrs = null, $content = null,$shortcodename ="" ){
	extract(shortcode_atts(array('layout'=>'5','url'=>get_permalink()),$attrs));
	$url = "&r='{$url}'";
	$out = '<script src="http://www.stumbleupon.com/hostedbadge.php?s='.$layout.$url.'"></script>';
return $out;	
}

//LINKEDIN...
add_shortcode('linkedin','linkedin');
function linkedin( $attrs = null, $content = null,$shortcodename ="" ){
	extract(shortcode_atts(array('layout'=>'2','url'=>get_permalink()),$attrs));
	
    	if ($url != '') { $url = "data-url='".$url."'"; }
	    if ($layout == '2') { $layout = 'right'; }
		if ($layout == '3') { $layout = 'top'; }
		$out = '<script type="text/javascript" src="http://platform.linkedin.com/in.js"></script><script type="in/share" data-counter = "'.$layout.'" '.$url.'></script>';
return $out;	
}

//DELICIES...
add_shortcode('delicious','delicious');
function delicious( $attrs = null, $content = null,$shortcodename ="" ){
	extract(shortcode_atts(array('text'=>__("Delicious",'iamd_text_domain')),$attrs));
	
	$delicious_url = "http://www.delicious.com/save";
	
	$out = '<img src="http://www.delicious.com/static/img/delicious.small.gif" height="10" width="10" alt="Delicious" />&nbsp;<a href="{$delicious_url}" onclick="window.open(&#39;http://www.delicious.com/save?v=5&noui&jump=close&url=&#39;+encodeURIComponent(location.href)+&#39;&title=&#39;+encodeURIComponent(document.title), &#39;delicious&#39;,&#39;toolbar=no,width=550,height=550&#39;); return false;">'.$text.'</a>';
return $out;	
}

//PINTEREST...
add_shortcode('pintrest','pintrest');
function pintrest( $attrs = null, $content = null,$shortcodename ="" ){
	extract(shortcode_atts(array('text'=>get_the_excerpt(),'layout'=>'horizontal','image'=>'','url'=>get_permalink(),'prompt'=>false),$attrs));
	$out = '<div class = "mysite_sociable"><a href="http://pinterest.com/pin/create/button/?url='.$url.'&media='.$image.'&description='.$text.'" class="pin-it-button" count-layout="'.$layout.'">'.__("Pin It",'iamd_text_domain').'</a>';
	$out .= '<script type="text/javascript" src="http://assets.pinterest.com/js/pinit.js"></script>';
	
	if($prompt):
		$out = '<a title="'.__('Pin It on Pinterest','iamd_text_domain').'" class="pin-it-button" href="javascript:void(0)">'.__("Pin It",'iamd_text_domain').'</a>';
		$out .= '<script type = "text/javascript">';
		$out .= 'jQuery(document).ready(function(){';
			$out .= 'jQuery(".pin-it-button").click(function(event) {';
			$out .= 'event.preventDefault();';
			$out .= 'jQuery.getScript("http://assets.pinterest.com/js/pinmarklet.js?r=" + Math.random()*99999999);';
			$out .= '});';
		$out .= '});';
		$out .= '</script>';
		$out .= '<style type = "text/css">a.pin-it-button {position: absolute;background: url(http://assets.pinterest.com/images/pinit6.png);font: 11px Arial, sans-serif;text-indent: -9999em;font-size: .01em;color: #CD1F1F;height: 20px;width: 43px;background-position: 0 -7px;}a.pin-it-button:hover {background-position: 0 -28px;}a.pin-it-button:active {background-position: 0 -49px;}</style>';
	
	endif;
	return $out;
}

//DIGG...
add_shortcode('digg','digg');
function digg( $attrs = null, $content = null,$shortcodename ="" ){
	extract(shortcode_atts(array('layout'=>'DiggMedium','url'=>get_permalink(),'title'=>get_the_title(),'type'=>'','description'=>get_the_content(),'related'=>''),$attrs));
	
	if ($title != '') { $title = "&title='".$title."'"; }
	if ($type != '') { $type = "rev='".$type."'"; }
	if ($description != '') { $description = "<span style = 'display: none;'>".$description."</span>"; }
	if ($related != '') { $related = "&related=no"; }

	$out = '<a class="DiggThisButton '.$layout.'" href="http://digg.com/submit?url='.$url.$title.$related.'"'.$type.'>'.$description.'</a>';
	$out .= '<script type = "text/javascript" src = "http://widgets.digg.com/buttons.js"></script>';
	return $out;
}

#GALLERY ITEMS
if(!function_exists('dt_gallery_items')) {
	
	function dt_gallery_items( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'limit' => -1,
			'categories' => '',
			'posts_column' => 'one-half-column', // one-third-column, one-fourth-column
			'filter' => ''
		), $atts));
		
		global $post;
		$meta_set = get_post_meta(get_queried_object_id(), '_tpl_default_settings', true);
		$page_layout = !empty($meta_set['layout']) ? $meta_set['layout'] : 'content-full-width';
		$post_layout = $posts_column;
		
		$li_class = "";
		$out = "";
		
		//POST LAYOUT CHECK...
		if($post_layout == "one-half-column") {
			$li_class = "portfolio dt-sc-one-half column";
		}
		elseif($post_layout == "one-third-column") {
			$li_class = "portfolio dt-sc-one-third column";
		}
		elseif($post_layout == "one-fourth-column") {
			$li_class = "portfolio dt-sc-one-fourth column";
		}
		
		//PAGE LAYOUT CHECK...
		if($page_layout != "content-full-width") {
			$li_class = $li_class." with-sidebar";
		}
		
		if(empty($categories)) {
			$cats = get_categories('taxonomy=gallery_entries&hide_empty=1');
			$cats = get_terms( array('gallery_entries'), array('fields' => 'ids'));		
		} else {
			$cats = explode(',', $categories);
		}
		
		//PERFORMING QUERY...
		if ( get_query_var('paged') ) { $paged = get_query_var('paged'); }
		elseif ( get_query_var('page') ) { $paged = get_query_var('page'); }
		else { $paged = 1; }
		
		//PERFORMING QUERY...
		$args = array('post_type' => 'dt_galleries', 'paged' => $paged , 'posts_per_page' => $limit,
																					   'tax_query' => array( 
																							array( 
																									'taxonomy' => 'gallery_entries', 
																									'field' => 'id', 
																									'terms' => $cats
																							)));
		$the_query = new WP_Query($args);
		if($the_query->have_posts()): 
		
		 if($filter != "false"):
			 $out .= '<div class="dt-sc-sorting-container">';
				$out .= '<a data-filter="*" href="#" class="first active-sort">'.__("All", "iamd_text_domain").'</a>';
					foreach($cats as $term) {
						$myterm = get_term_by('id', $term, 'gallery_entries');
						$out .= '<a href="#" data-filter=".'.strtolower($myterm->slug).'">'.$myterm->name.'</a>';
					}
			 $out .= '</div>';
		 endif;
		 
		 $out .= '<div class="dt-sc-portfolio-container">';
			while($the_query->have_posts()): $the_query->the_post(); 
				$terms = wp_get_post_terms(get_the_ID(), 'gallery_entries', array("fields" => "slugs")); array_walk($terms, "arr_strfun");
				
				$out .= '<div class="'.$li_class." ".strtolower(implode(" ", $terms)).' no-space">';
				  $out .= '<figure>';
					$fullimg = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full', false);
					$currenturl = $fullimg[0];
					$currenticon = "fa-plus";
					$pmeta_set = get_post_meta($post->ID, '_gallery_settings', true);
					if( @array_key_exists('items_thumbnail', $pmeta_set) && ($pmeta_set ['items_name'] [0] == 'video' )) {
						$currenturl = $pmeta_set ['items'] [0];
						$currenticon = "fa-video-camera";
					}
					//GALLERY IMAGES...
					if(has_post_thumbnail()): 
						$attr = array('title' => get_the_title(), 'alt' => get_the_title()); 
						$out .= get_the_post_thumbnail($post->ID, 'full', $attr);
					else:
						$out .= '<img src="http://placehold.it/1170X800.jpg&text=No Image" alt="'.get_the_title().'" title="'.get_the_title().'" />';
					endif;
					$out .= '<figcaption>';
					  $out .= '<div class="fig-content-wrapper">';
						$out .= '<div class="fig-content">';
							$out .= '<h5><a href="'.get_permalink().'">'.get_the_title().'</a></h5>';
							$out .= '<p>'.get_the_term_list($post->ID, 'gallery_entries', ' ', ', ', ' ').'</p>';							
							$out .= '<div class="fig-overlay">';
								$out .= '<a class="zoom" title="'.get_the_title().'" data-gal="prettyPhoto[gallery]" href="'.$currenturl.'"><span class="fa '.$currenticon.'"> </span></a>';
								$out .= '<a class="link" href="'.get_permalink().'"> <span class="fa fa-link"> </span> </a>';
								if(dt_theme_is_plugin_active('roses-like-this/likethis.php')):
									$out .= generateLikeString($post->ID, '');
								endif;
							$out .= '</div>';
						$out .= '</div>';
					  $out .= '</div>';
					$out .= '</figcaption>';
				  $out .= '</figure>';
				$out .= '</div>';
			endwhile;
		 $out .= '</div>';
		 
		 else:
			$out .= '<h2>'.__("Nothing Found.", "iamd_text_domain").'</h2>';
			$out .= '<p>'.__("Apologies, but no results were found for the requested archive.", "iamd_text_domain").'</p>';
		endif;
	
		return $out;
	}
	add_shortcode('dt_gallery_items', 'dt_gallery_items');
	add_shortcode('dt_sc_gallery_items', 'dt_gallery_items');
}

#EVENTS LIST SHORTCODE...
if(!function_exists('dt_events_list') && dt_theme_is_plugin_active('the-events-calendar/the-events-calendar.php')) {
	
	function dt_events_list( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'limit'  => '-1',
			'excerpt_length' => '18',
			'post_column' => 'one-third-column',	// one-half-column, one-third-column, one-fourth-column
		), $atts));

		$post_column = !empty($post_column) ? $post_column : 'one-third-column';
		
		if($post_column == "one-half-column") {
			$div_class = "column dt-sc-one-half";
			$column = 2;
		}
		elseif($post_column == "one-third-column") {
			$div_class = "column dt-sc-one-third";
			$column = 3;
		}
		elseif($post_column == "one-fourth-column") {
			$div_class = "column dt-sc-one-fourth";
			$column = 4;
		}

		global $post; $out = ""; $i = 1;
		$all_events = tribe_get_events(array( 'eventDisplay'=>'all', 'posts_per_page'=> $limit ));

		foreach($all_events as $post) {
		  setup_postdata($post);
		  
		  $tmpclass = "";
		  if($i == 1) $tmpclass = $div_class." first"; else $tmpclass = $div_class;
		  if($i == $column) $i = 1; else $i = $i + 1;		  
		  
			$out .= '<div id="post-'.$post->ID.'" class="'.$tmpclass.'">';
				$out .= '<div class="dt-sc-event">';
					$out .= '<h4><a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a></h4>';
					$out .= '<div class="event-thumb">';
						$out .= '<a href="'.get_permalink($post->ID).'" title="'.$post->post_title.'">';
							$attr = array('title' => $post->post_title); $out .= get_the_post_thumbnail($post->ID, 'full', $attr);
							$out .= '<div class="image-overlay">';
								$out .= '<span class="image-overlay-inside"></span>';
							$out .= '</div>';
						$out .= '</a>';
					$out .= '</div>';
					$out .= dt_theme_excerpt($excerpt_length);
					$out .= '<div class="dt-sc-event-detail">';
						$cost = get_post_meta($post->ID, '_EventCost', true);
						if(!empty($cost)) {
							$out .= '<div class="event-price">';
								$out .= '<p>'.__('Starts From', 'iamd_text_domain').'</p>';
								$sym = get_post_meta($post->ID, '_EventCurrencySymbol', true);
								$out .= '<span>'.$sym.$cost.'</span>';
							$out .= '</div>';
						}
						$out .= '<a href="'.get_permalink($post->ID).'" class="dt-sc-button too-small">'.__('View details', 'iamd_text_domain').'</a>';
					$out .= '</div>';
				$out .= '</div>';
			$out .= '</div>';
		}
		return $out;
	}
	add_shortcode('dt_events_list', 'dt_events_list');
	add_shortcode('dt_sc_events_list', 'dt_events_list');
}

//WELCOME TEXT...
if(!function_exists('dt_welcome_text')) {

	function dt_welcome_text($attrs, $content=null, $shortcodename =""){
		$out = '';
		$out .= '<div class="welcome-txt aligncenter">'.do_shortcode($content).'</div>';
		return $out;
	}
	add_shortcode('dt_welcome_text', 'dt_welcome_text');
	add_shortcode('dt_sc_welcome_text', 'dt_welcome_text');
}

//BEST PACKAGES WIDGET...
if(!function_exists('dt_packages_list') && dt_theme_is_plugin_active('woocommerce/woocommerce.php')) {

	function dt_packages_list( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'limit'  => '-1',
			'carousel' => 'false',
			'categories' => '',
			'post_column' => 'one-fourth-column',	// one-half-column, one-third-column, one-fourth-column
		), $atts));
		
		$post_column = !empty($post_column) ? $post_column : 'one-fourth-column';
		
		if($post_column == "one-half-column") {
			$div_class = "column dt-sc-one-half";
			$column = 2;
		}
		elseif($post_column == "one-third-column") {
			$div_class = "column dt-sc-one-third";
			$column = 3;
		}
		elseif($post_column == "one-fourth-column") {
			$div_class = "column dt-sc-one-fourth";
			$column = 4;
		}

		if(empty($categories)) {
			$cats = get_categories('taxonomy=product_cat&hide_empty=1');
			$cats = get_terms( array('product_cat'), array('fields' => 'ids'));
		} else {
			$cats = explode(',', $categories);
		}

		$out = "";

		$args = array('post_type' => 'product', 'posts_per_page' => $limit, 'tax_query' => array( array( 'taxonomy' => 'product_cat', 'field' => 'id', 'terms' => $cats )));
		$the_query = new WP_Query($args);

		if($the_query->have_posts()): $i = 1;
			if($carousel == 'true')
				$out .= '<div class="package-wrapper dt_carousel" data-items="'.$column.'">';
			else
				$out .= '<div class="package-wrapper">';

			  while($the_query->have_posts()): $the_query->the_post();
				$tclass = "";
				if($i == 1 && $carousel != 'true') $tclass = $div_class." first"; else $tclass = $div_class;
				if($i == $column) $i = 1; else $i = $i + 1;
			
				$out .= '<div id="'.get_the_ID().'" class="'.$tclass.'">';
					$out .= '<div class="package-item">';
						if(has_post_thumbnail()):
							$out .= '<div class="package-thumb">';
								$out .= '<a href="'.get_permalink(get_the_ID()).'" title="'.get_the_title().'">';							
									$attr = array('title' => get_the_title()); $out .= get_the_post_thumbnail(get_the_ID(), 'full', $attr);
									$out .= '<div class="image-overlay">';
										$out .= '<span class="image-overlay-inside"></span>';
									$out .= '</div>';
								$out .= '</a>';
							$out .= '</div>';
						endif;
						$out .= '<div class="package-details">';
							$out .= '<h5><a href="'.get_permalink().'">'.get_the_title().'</a></h5>';
							$city = get_post_meta(get_the_ID(), '_package_place', true);
							if(!empty($city))
								$out .= '<p>'.$city.'</p>';
							$out .= '<div class="package-content">';
								$out .= '<ul class="package-meta">';
									$days = get_post_meta(get_the_ID(), '_package_days_duration', true);
									if(!empty($days))
										$out .= '<li> <span class="fa fa-calendar"></span>No of Days: '.$days.' </li>';
									$people = get_post_meta(get_the_ID(), '_package_people', true);
									if(!empty($people))
										$out .= '<li> <span class="fa fa-user"></span>People: '.$people.' </li>';
								$out .= '</ul>';
								$sprice = get_post_meta(get_the_ID(), '_sale_price', true);
								if(!empty($sprice))
									$out .= '<span class="package-price">'.get_woocommerce_currency_symbol().$sprice.'</span>';
								$out .= '<a href="'.get_permalink().'" class="dt-sc-button too-small">'.__('View details', 'iamd_text_domain').'</a>';
							$out .= '</div>';
						$out .= '</div>';
				   $out .= '</div>';
				$out .= '</div>';
			  endwhile;
			$out .= '</div>';  
		endif;
		
		if($carousel == 'true') {
			return '<div class="carousel_items">'
						.$out
						.'<div class="carousel-arrows">
							<a class="prev-arrow" href="#"><span class="fa fa-angle-left"> </span></a>
							<a class="next-arrow" href="#"><span class="fa fa-angle-right"> </span></a>
						  </div>
					</div>';
		} else {
			return $out;
		}
	}
	add_shortcode('dt_packages_list', 'dt_packages_list');
	add_shortcode('dt_sc_packages_list', 'dt_packages_list');
}

//MAP MARKER...
if(!function_exists('dt_marker')) {
	
	function dt_marker($attrs, $content=null, $shortcodename =""){

		extract(shortcode_atts(array(
			'title' => '',
			'color' => 'green'
		), $attrs));
		
		$out = "";
		$out .= '<p class="map-marker"><span class="'.$color.'"></span>'.$title.'</p>';
		
		return $out;
	}
	add_shortcode('dt_marker', 'dt_marker');
	add_shortcode('dt_sc_marker', 'dt_marker');
}

//NUMBER COUNT...
if(!function_exists('dt_number_count')) {
	
	function dt_number_count($attrs, $content=null, $shortcodename =""){

		extract(shortcode_atts(array(
			'icon' => 'location-arrow',
			'value' => '1540',
			'title' => ''
		), $attrs));
		
		$out = "";
		$out .= '<div class="dt-sc-ico-content type3">';
			$out .= '<div class="icon">';
				$out .= '<span class="fa fa-'.$icon.'"></span>';
			$out .= '</div>';
			
			$out .= '<span class="dt-sc-num-count" data-value="'.$value.'">'.$value.'</span>';
		
			if($title != NULL) $out .= '<h4>'.$title.'</h4>';
		$out .= '</div>';
		
		return $out;
	}
	add_shortcode('dt_number_count','dt_number_count');
	add_shortcode('dt_sc_number_count','dt_number_count');
}

//TOUR PACKAGES...
if(!function_exists('dt_tourpackage_list') && dt_theme_is_plugin_active('woocommerce/woocommerce.php')) {
	
	function dt_tourpackage_list( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'limit'  => '-1',
			'carousel' => 'false',
			'excerpt_length' => 30
		), $atts));
		
		$out = "";
		$div_class = "column dt-sc-one-half";
		$column = 2;
		
		$args = array('post_type' => 'product', 'posts_per_page' => $limit);
		$the_query = new WP_Query($args);
		
		if($the_query->have_posts()): $i = 1;
		
			if($carousel == 'true')
				$out .= '<div class="dt-sc-packages-wrapper dt_carousel" data-items="2">';
			else
				$out .= '<div class="dt-sc-packages-wrapper">';
			
			  while($the_query->have_posts()): $the_query->the_post();
				$tclass = "";
				if($i == 1 && $carousel != 'true') $tclass = $div_class." first"; else $tclass = $div_class;
				if($i == $column) $i = 1; else $i = $i + 1;
					
				  $out .= '<div id="'.get_the_ID().'" class="'.$tclass.'">';
					  $out .= '<div class="dt-sc-package-item">';
					  	  if(get_post_meta(get_the_ID(), '_featured', true) == 'yes')
						  	$out .= '<p class="dt-sc-packtype new"><span>'.__('New ', 'iamd_text_domain').'<br>'.__('Addition', 'iamd_text_domain').'</span></p>';
						  elseif(get_post_meta(get_the_ID(), '_stock_status', true) == 'outofstock')
						    $out .= '<p class="dt-sc-packtype sold"><span>'.__('Sold ', 'iamd_text_domain').'<br>'.__('Out', 'iamd_text_domain').'</span></p>';
							
						  $out .= '<div class="dt-sc-pack-thumb">';
							  $out .= '<a href="'.get_permalink(get_the_ID()).'" title="'.get_the_title().'">';
								  $attr = array('title' => get_the_title()); $out .= get_the_post_thumbnail(get_the_ID(), 'thumb', $attr);
							  $out .= '</a>';
							  if(get_post_meta(get_the_ID(), '_sale_price', true) != "")
								  $out .= '<p class="dt-sc-pack-price">'.__('From', 'iamd_text_domain').'<span>'.get_woocommerce_currency().' '.get_woocommerce_currency_symbol().get_post_meta(get_the_ID(), '_sale_price', true).'</span></p>';

							  $arr_rate = dt_theme_comment_rating_count(get_the_ID());
							  $all_avg = dt_theme_comment_rating_average(get_the_ID());
							  $out.= '<div class="star-rating-wrapper"><div class="star-rating"><span style="width:'.(($all_avg/5)*100).'%"></span></div>('.count($arr_rate).__(' Ratings', 'iamd_text_domain').')</div>';				  $out .= '</div>';
						  $out .= '<div class="dt-sc-pack-detail">';
							  $out .= '<h5><a href="'.get_permalink(get_the_ID()).'">'.get_the_title().'</a></h5>';
							  $out .= '<span class="subtitle">'.get_post_meta(get_the_ID(), '_package_place', true).'</span>';
							  $out .= '<ul class="dt-sc-pack-meta">';
								  $out .= '<li><span class="fa fa-clock-o"> </span>No of Days: '.get_post_meta(get_the_ID(), '_package_days_duration', true).'</li>';
								  $out .= '<li><span class="fa fa-user"></span> People: '.get_post_meta(get_the_ID(), '_package_days_duration', true).'</li>';
							  $out .= '</ul>';
							  $excerpt_length = !empty($excerpt_length) ? $excerpt_length : 30;
							  $out .= dt_theme_excerpt($excerpt_length);
							  $out .= '<a href="'.get_permalink(get_the_ID()).'">'.__('Read More', 'iamd_text_domain').'</a>';
						  $out .= '</div>';
					  $out .= '</div>';
				  $out .= '</div>';

			  endwhile;
			$out .= '</div>';  
		endif;
		
		if($carousel == 'true') {
			return '<div class="carousel_items">'
						.$out
						.'<div class="carousel-arrows">
							<a class="prev-arrow" href="#"><span class="fa fa-angle-left"> </span></a>
							<a class="next-arrow" href="#"><span class="fa fa-angle-right"> </span></a>
						  </div>
					</div>';
		} else {
			return $out;
		}
	}
	add_shortcode('dt_tourpackage_list', 'dt_tourpackage_list');
	add_shortcode('dt_sc_tourpackage_list', 'dt_tourpackage_list');
}

//TOUR PACKAGES...
if(!function_exists('dt_latest_hotel_reviews')) {

	function dt_latest_hotel_reviews( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'limit'  => '-1'
		), $atts));

		$out = "";

		$comm_arr = get_comments( array('number' => $limit, 'post_type' => 'dt_hotels', 'status' => 'approve'));

		if(count($comm_arr) > 0):
		  $out .= '<ul class="reviews-wrapper dt_carousel" data-items="1">';
		  foreach($comm_arr as $com) :
			  $out .= '<li>';
				  $out .= '<div class="review-thumb">';
					  $attr = array('title' => $com->post_title);
					  $out .= '<a href="'.get_permalink($com->ID).'">'.get_the_post_thumbnail($com->ID, array(100, 80), $attr).'</a>';
				  $out .= '</div>';
				  $out .= '<div class="review-detail">';
					  $hmeta = get_post_meta($com->ID ,'_hotel_settings', true);
				  
					  $out .= '<h6><a href="'.get_permalink($com->ID).'">'.$com->post_title.', <sub>'.$hmeta['hotel_add'].'</sub></a></h6>';
					  $out .= '<i>'.get_comment_meta($com->comment_ID, 'title', true).'</i>';
					  
					  $rate = get_comment_meta($com->comment_ID, 'rating', true);
					  $out .= '<div class="star-rating-wrapper">';
						  $out .= '<div class="star-rating">';
							  $out .= '<span style="width:'.(($rate/5)*100).'%"></span>';
						  $out .= '</div>';
						  $out .= '('.$rate.' '.__('out of 5', 'iamd_text_domain').')';
					  $out .= '</div>';

					  $out .= '<blockquote><q>&quot;'.$com->comment_content.'&quot;</q></blockquote>';
					  
					  $out .= '<div class="author-detail">';
						  $out .= get_avatar($com->ID, 62);
						  $out .= '<cite>'.$com->comment_author.'<span>'.get_comment_meta($com->comment_ID, 'profession', true).'</span></cite>';
					  $out .= '</div>';
				  $out .= '</div>';
			  $out .= '</li>';
		  endforeach;
		  $out .= '</ul>';
			
		  return '<div class="carousel_items">'
					.$out
					.'<div class="carousel-arrows">
						<a class="prev-arrow" href="#"><span class="fa fa-angle-left"> </span></a>
						<a class="next-arrow" href="#"><span class="fa fa-angle-right"> </span></a>
					  </div>
				 </div>';
		else:
			return '<h2>'.__('No Reviews Found.', 'iamd_text_domain').'</h2>';
		endif;
	}
	add_shortcode('dt_latest_hotel_reviews', 'dt_latest_hotel_reviews');
	add_shortcode('dt_sc_latest_hotel_reviews', 'dt_latest_hotel_reviews');
}

//DESTINATION PLACE...
if(!function_exists('dt_destination_place')) {

	function dt_destination_place( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'place_id'  => '1'
		), $atts));

		$out = "";

		$args = array('post_type' => 'dt_places', 'p' => $place_id);
		$the_query = new WP_Query($args);
		
		$out .= '<div class="dt-travel-place-wrapper">';
		
		if($the_query->have_posts()):
		 while($the_query->have_posts()): $the_query->the_post();
			$out .= '<div class="dt-sc-two-third column first">';
				$out .= '<div class="dt-sc-two-fifth column first">';
					$out .= '<div class="place-thumb">';
						$out .= '<div class="thumb-inner">';
							$out .= '<a href="'.get_permalink().'" title="'.get_the_title().'">';							
									$attr = array('title' => get_the_title()); $out .= get_the_post_thumbnail(get_the_ID(), 'full', $attr);
									$out .= '<div class="image-overlay">';
										$out .= '<span class="image-overlay-inside"></span>';
									$out .= '</div>';
							$out .= '</a>';
						$out .= '</div>';							
						$out .= '<a href="'.get_permalink().'" class="dt-sc-button too-small">'.__('View Gallery', 'iamd_text_domain').'</a>';
					$out .= '</div>';
				$out .= '</div>';
				$out .= '<div class="place-details dt-sc-three-fifth column">';
					$out .= '<h5><a href="'.get_permalink().'">'.get_the_title().'</a></h5>';
					$pmeta = get_post_meta(get_the_ID() ,'_place_settings', true);
					$out .= '<span class="subtitle">'.@$pmeta['place_add'].'</span>';
					$out .= '<p>'.get_the_excerpt().'</p>';
				$out .= '</div>';
			$out .= '</div>';
			$out .= '<div class="dt-sc-one-third column">';
				#check hotels...
				$harray = @array_filter($pmeta['place-hotels-list']);
				if($harray != NULL):
					$out .= '<div class="widget hotels-list-widget">';
						$out .= '<h3 class="widgettitle">'.__('Hotels to Stay', 'iamd_text_domain').'</h3>';
						$out .= '<div class="recent-hotels-widget">';
							$out .= '<ul>';
								foreach($harray as $hid):
									$tpost = get_post($hid);
									$attr = array('title' => $tpost->post_title);
									$hmeta = get_post_meta($hid, '_hotel_settings', true);
									$arr_rate = dt_theme_comment_rating_count($hid);
									$all_avg = dt_theme_comment_rating_average($hid);
									$out .= '<li>';
										$out .= '<a class="thumb" href="'.get_permalink($hid).'">'.get_the_post_thumbnail($hid, array(100, 80), $attr).'</a>';
										$out .= '<h6><a href="'.get_permalink($hid).'">'.$tpost->post_title.'</a> <sub>'.@$hmeta['hotel_add'].'</sub></h6>';
										$out .= '<div class="star-rating-wrapper"><div class="star-rating"><span style="width:'.(($all_avg/5)*100).'%"></span></div>('.count($arr_rate).__(' Ratings', 'iamd_text_domain').')</div>';
										$out .= '<a href="'.get_permalink($hid).'#hotel_map_'.$hid.'" class="map-marker"><span class="red"></span>'.__('View on Map', 'iamd_text_domain').'</a>';
									$out .= '</li>';
								endforeach;
							$out .= '</ul>';
						$out .= '</div>';
					$out .= '</div>';
				else:
					$out .= '<h4>'.__('No Hotels Found.', 'iamd_text_domain').'</h4>';
					$out .= '<p>'.__('Choose hotels from back-end to show in this section.', 'iamd_text_domain').'</h2>';
				endif;
			$out .= '</div>';
			$out .= '<div class="read-more">';
				$out .= '<a href="'.get_permalink().'">'.__('Read More', 'iamd_text_domain').'</a>';
			$out .= '</div>';
		endwhile;
	  else:
		$out .= '<h4>'.__('No Place Found.', 'iamd_text_domain').'</h4>';
		$out .= '<p>'.__('Put the correct place id from back end.', 'iamd_text_domain').'</h2>';
	  endif;
	  $out .= '</div>';
	  
	  return $out;
	}
	add_shortcode('dt_destination_place', 'dt_destination_place');
	add_shortcode('dt_sc_destination_place', 'dt_destination_place');
}

//BEST DESTINATION PLACE...
if(!function_exists('dt_best_destination_place')) {

	function dt_best_destination_place( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'place_ids'  => '-1',
			'carousel'	 =>	'false'
		), $atts));

		$out = "";

		$args = array('post_type' => 'dt_places', 'posts_per_page' => '-1', 'post__in' => explode(',', $place_ids));
		$the_query = new WP_Query($args);

		if($carousel == 'true')
			$out .= '<div class="dt-best-entry-place-wrapper dt_carousel" data-items="1">';
		else
			$out .= '<div class="dt-best-entry-place-wrapper">';
			
			if($the_query->have_posts()):
			 while($the_query->have_posts()): $the_query->the_post();
				$out .= '<div class="dt-entry-place-item">';
					$out .= '<div class="dt-sc-one-half column first">';
						$out .= '<div class="entry-place-thumb">';
							$out .= '<div class="thumb-inner">';
								$out .= '<a href="'.get_permalink().'" title="'.get_the_title().'">';							
									$attr = array('title' => get_the_title()); $out .= get_the_post_thumbnail(get_the_ID(), 'full', $attr);
									$out .= '<div class="image-overlay">';
										$out .= '<span class="image-overlay-inside"></span>';
									$out .= '</div>';
								$out .= '</a>';
							$out .= '</div>';	
							$out .= '<a href="'.get_permalink().'" class="dt-sc-button too-small">'.__('View Gallery', 'iamd_text_domain').'</a>';
						$out .= '</div>';
					$out .= '</div>';
					
					$out .= '<div class="dt-sc-one-half column">';
						$out .= '<div class="entry-place-detail">';
							$out .= '<div class="entry-place-title">';
								$out .= '<div class="float-left">';
									$out .= '<h5><a href="'.get_permalink().'">'.get_the_title().'</a></h5>';
									$pmeta = get_post_meta(get_the_ID() ,'_place_settings', true);
									$out .= '<p>'.@$pmeta['place_add'].'</p>';
								$out .= '</div>';
								$arr_rate = dt_theme_comment_rating_count(get_the_ID());
								$all_avg = dt_theme_comment_rating_average(get_the_ID());
								$out .= '<div class="star-rating-wrapper float-right"><div class="star-rating"><span style="width:'.(($all_avg/5)*100).'%"></span></div>('.count($arr_rate).__(' Ratings', 'iamd_text_domain').')</div>';
							$out .= '</div>';
							$out .= '<h6>'.__('Why it is best?', 'iamd_text_domain').'</h6>';
							$out .= dt_theme_excerpt(20);
							$out .= '<div class="dt-sc-hr-invisible-small"></div>';
							$out .= '<h6>'.__('Hotels to Stay', 'iamd_text_domain').'</h6>';
							$out .= '<div class="entry-place-meta">';
								$harray = @array_filter($pmeta['place-hotels-list']);
								if($harray != NULL):
									$out .= '<ul>';
										foreach($harray as $hid):
											$tpost = get_post($hid);
											$attr = array('title' => $tpost->post_title);
											$hmeta = get_post_meta($hid, '_hotel_settings', true);
											$arr_rate = dt_theme_comment_rating_count($hid);
											$all_avg = dt_theme_comment_rating_average($hid);
											$out .= '<li>';
												$out .= '<ul>';
													$out .= '<li><p>'.$tpost->post_title.', <sub>'.@$hmeta['hotel_add'].'</sub></p></li>';
													$out .= '<li><a href="'.get_permalink($hid).'#hotel_map_'.$hid.'" class="map-marker small"><span class="red"></span>'.__('View on Map', 'iamd_text_domain').'</a></li>';
												$out .= '</ul>';
												$out .= '<div class="star-rating-wrapper"><div class="star-rating"><span style="width:'.(($all_avg/5)*100).'%"></span></div>('.count($arr_rate).__(' Ratings', 'iamd_text_domain').')</div>';							
											$out .= '</li>';
										endforeach;
									$out .= '</ul>';
									$out .= '<a href="'.get_permalink().'" class="aligncenter">'.__('Read More', 'iamd_text_domain').'</a>';
								else:
									$out .= '<h4>'.__('No Hotels Found.', 'iamd_text_domain').'</h4>';
									$out .= '<p>'.__('Choose hotels from back-end to show in this section.', 'iamd_text_domain').'</h2>';
								endif;
							$out .= '</div>';
						$out .= '</div>';
					$out .= '</div>';
				$out .= '</div>';
			 endwhile;
			else:
				$out .= '<h4>'.__('No Place Found.', 'iamd_text_domain').'</h4>';
				$out .= '<p>'.__('Put the correct place ids from back end.', 'iamd_text_domain').'</h2>';
			endif;
	    $out .= '</div>';
		
		if($carousel == 'true') {
			return '<div class="carousel_items">'
						.$out
						.'<div class="carousel-arrows">
							<a class="prev-arrow" href="#"><span class="fa fa-angle-left"> </span></a>
							<a class="next-arrow" href="#"><span class="fa fa-angle-right"> </span></a>
						  </div>
					</div>';
		} else {
			return $out;
		}
	}
	add_shortcode('dt_best_destination_place', 'dt_best_destination_place');
	add_shortcode('dt_sc_best_destination_place', 'dt_best_destination_place');
}

//CONTACT SUPPORT SECTION...
if(!function_exists('dt_support_section')) {

	function dt_support_section( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'title'  => '',
			'link' => '#',
			'phone' => '',
			'image' => ''
		), $atts));

		$out = "";
	
		$out .= '<div class="support-info">';
			if($title != '')
				$out .= '<h2 class="section-title">'.$title.'</h2>';
			if($content != '')
				$out .= '<p>'.do_shortcode($content).'</p>';
			if($phone != '')
				$out .= '<a class="dt-sc-button medium" href="'.$link.'"><span class="fa fa-phone"></span>'.$phone.'</a>';
			if($image != '')
				$out .= '<img alt="'.__('support_img', 'iamd_text_domain').'" src="'.$image.'">';
		$out .= '</div>';
	
		return $out;	
	}
	add_shortcode('dt_support_section', 'dt_support_section');
	add_shortcode('dt_sc_support_section', 'dt_support_section');
}

//RECOMMENDED PLACES...
if(!function_exists('dt_recommend_places')) {

	function dt_recommend_places( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'limit'  => '-1',
			'posts_column' => 'one-fourth'	// one-half, one-third, one-fourth
		), $atts));

		$out = "";

		$args = array('orderby' => 'rand', 'post_type' => 'dt_places', 'posts_per_page' => $limit);
		$the_query = new WP_Query($args);
		if($the_query->have_posts()):
		
		  if($the_query->post_count == 2):
		  	  $maxitems = 2;
		  elseif($the_query->post_count == 3):
		  	  $maxitems = 3;
		  elseif($the_query->post_count >= 4):
		  	  if($posts_column == 'one-half'):
			  	 $posts_column = 'dt-sc-one-half';
		  	     $maxitems = 2;
			  elseif($posts_column == 'one-third'):
			  	 $posts_column = 'dt-sc-one-third';
		  	     $maxitems = 3;
			  else:
			  	 $posts_column = 'dt-sc-one-fourth';
		  	     $maxitems = 4;
			  endif;	 
		  endif;

		  $out .= '<div class="carousel_items">';
			$out .= '<div class="dt-sc-places-wrapper dt_carousel" data-items="'.$maxitems.'">';
				while($the_query->have_posts()): $the_query->the_post();
					$place_meta = get_post_meta(get_the_id() ,'_place_settings', true);
					$out .= '<div class="'.$posts_column.' column">';
						$out .= '<div class="place-item">';
							$out .= '<div class="place-thumb">';
								if( has_post_thumbnail() ):
									$out .= '<a href="'.get_permalink().'" title="'.get_the_title().'">';
										$attr = array('title' => get_the_title()); $out .= get_the_post_thumbnail(get_the_id(), 'place-thumb', $attr);
										$out .= '<div class="image-overlay">';
											$out .= '<span class="image-overlay-inside"></span>';
										$out .= '</div>';
									$out .= '</a>';
								endif;
							$out .= '</div>';
							$out .= '<div class="place-detail-wrapper">';
								$out .= '<div class="place-title">';
									$out .= '<h5><a href="'.get_permalink().'">'.get_the_title().'</a></h5>';
									$out .= '<p>'.@$place_meta['place_add'].'</p>';
								$out .= '</div>';
								$out .= '<div class="place-content">';
									$out .= '<a class="map-marker" href="'.get_permalink().'#place_map_'.get_the_ID().'"> <span class="red"></span>'.__('View on Map', 'iamd_text_domain').'</a>';
									$out .= '<a class="dt-sc-button too-small" href="'.get_permalink().'">'.__('View details', 'iamd_text_domain').'</a>';
								$out .= '</div>';
							$out .= '</div>';
						$out .= '</div>';
					$out .= '</div>';
				endwhile;
			$out .= '</div>';
			$out .= '<div class="carousel-arrows">';
				$out .= '<a class="prev-arrow" href="#"><span class="fa fa-angle-left"> </span></a>';
				$out .= '<a class="next-arrow" href="#"><span class="fa fa-angle-right"> </span></a>';
			$out .= '</div>';
		  $out .= '</div>';
		endif;

		return $out;
	}
	add_shortcode('dt_recommend_places', 'dt_recommend_places');
	add_shortcode('dt_sc_recommend_places', 'dt_recommend_places');
}

//FEATURE ICON...
if(!function_exists('dt_feature_icon')) {

	function dt_feature_icon( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'icon'  => '',
			'text' => ''
		), $atts));

		$out = "";
		
		$out .= '<p class="dt-feature-icon"><span class="fa fa-'.$icon.'"></span>'.$text.'</p>';
		
		return $out;
	}
	add_shortcode('dt_feature_icon', 'dt_feature_icon');
	add_shortcode('dt_sc_feature_icon', 'dt_feature_icon');
}

//INTRO TEXT...
if(!function_exists('dt_intro_text')) {

	function dt_intro_text( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'type'  => ''
		), $atts));

		$out = "";

		$out .= '<div class="introtext '.$type.'">'.do_shortcode($content).'</div>';

		return $out;
	}
	add_shortcode('dt_intro_text', 'dt_intro_text');
	add_shortcode('dt_sc_intro_text', 'dt_intro_text');
}

//TIMELINE SHORTCODE...
if(!function_exists('dt_timeline_posts')) {

	function dt_timeline_posts( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'limit' => -1
		), $atts));

		$out = '';
		$lrclass = 'left';
		
		$args = array('post_type' => 'post', 'posts_per_page' => $limit);
		$the_query = new WP_Query($args);
		
		if($the_query->have_posts()):
			$out .= '<div class="dt-timeline-posts-wrapper">';
				$out .= '<div id="dt-timeline-posts">';
				 while($the_query->have_posts()): $the_query->the_post();
					$out .= '<div class="dt-timeline-post '.$lrclass.'">';
						$out .= '<div class="dt-sc-one-half column first">';
							$out .= '<div class="dt-timeline-content">';
								$out .= '<div class="dt-timeline-tilte"><h2><a href="'.get_permalink().'">'.get_the_title().'</a><span></span></h2></div>';
								$out .= dt_theme_excerpt(30);
							$out .= '</div>';
						$out .= '</div>';
						$out .= '<div class="dt-sc-one-half column">';
							$attr = array('title' => get_the_title()); $out .= get_the_post_thumbnail(get_the_id(), 'thumb', $attr);
						$out .= '</div>';
					$out .= '</div>';
					if($lrclass == 'left') $lrclass = 'right'; else $lrclass = 'left';
				 endwhile;
				$out .= '</div>';
			$out .= '</div>';
		endif;
		
		return $out;		
	}
	add_shortcode('dt_timeline_posts', 'dt_timeline_posts');
	add_shortcode('dt_sc_timeline_posts', 'dt_timeline_posts');
}

//CUSTOM ICON BOXES...
if(!function_exists('dt_theme_iconbox')) {

	function dt_theme_iconbox( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'icon'  => '',
			'title'	=> '',
			'text'	=> ''
		), $atts));

		$out = "";
		
		$out .= '<div class="dt-sc-ico">';
			if($icon != '')
				$out .= '<span class="fa fa-'.$icon.'"></span>';
			if($title != '')				
                $out .= '<h5>'.$title.'</h5>';
			if($text != '')				
                $out .= '<p>'.$text.'</p>';
		$out .= '</div>';

		return $out;
	}
	add_shortcode('dt_theme_iconbox', 'dt_theme_iconbox');
	add_shortcode('dt_sc_theme_iconbox', 'dt_theme_iconbox');
}

//HOTEL ROOM...
if(!function_exists('dt_hotel_room')) {
	
	function dt_hotel_room( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'room_type'  => '',
			'persons'	=> '',
			'facilities'	=> '',
			'price'	=>	'',
			'available'	=>	''
		), $atts));

		$out = '';
		
		$out .= '<div class="dt-hotel-room-wrapper">';
			$out .= '<ul>';
				$out .= '<li class="room-name"> <span class="fa fa-building-o"></span> <a href="#">'.$room_type.'</a></li>';
				$out .= '<li class="room-persons"> <span class="fa fa-users"></span> '.$persons.' </li>';
				$out .= '<li class="room-details"> <span class="fa fa-bell"></span> '.$facilities.' </li>';
				$out .= '<li>';
					$out .= '<div class="hotel-thumb-meta">';
						$out .= '<div class="hotel-price">';
							$out .= __('Starts From', 'iamd_text_domain').' <span>'.$price.'</span>';
						$out .= '</div>';
						$out .= '<span class="hotel-option-type">';
							$out .= '<a href="#">'.$available.'</a>';
						$out .= '</span>';
					$out .= '</div>';
				$out .= '</li>';
			$out .= '</ul>';
		$out .= '</div>';

		return $out;
	}
	add_shortcode('dt_hotel_room', 'dt_hotel_room');
	add_shortcode('dt_sc_hotel_room', 'dt_hotel_room');
}

//CUSTOM TIMELINE POST...
if(!function_exists('dt_history_entry')) {

	function dt_history_entry( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'align' => 'left',
			'title'	=> '',
			'link'	=>	'',
			'image'	=>	'http://placehold.it/150x200'
		), $atts));

		$out = '';
		
		$out .= '<div class="dt-timeline-post '.$align.'">';
			$out .= '<div class="dt-sc-one-half column first">';
				$out .= '<div class="dt-timeline-content">';
					$out .= '<div class="dt-timeline-tilte"><h2><a href="'.$link.'">'.$title.'</a><span></span></h2></div>';
					$out .= do_shortcode($content);
				$out .= '</div>';
			$out .= '</div>';
			$out .= '<div class="dt-sc-one-half column">';
				$out .= '<img src="'.$image.'" alt="'.$title.'"  title="'.$title.'" />';
			$out .= '</div>';
		$out .= '</div>';
		
		return $out;		
	}
	add_shortcode('dt_history_entry', 'dt_history_entry');
}

//CUSTOM TIMELINE WRAPPER...
if(!function_exists('dt_history_wrapper')) {

	function dt_history_wrapper( $atts, $content = null ) {

		$out = '';
		
		$out .= '<div class="dt-timeline-posts-wrapper">';
			$out .= '<div id="dt-timeline-posts">';
				$out .= do_shortcode($content);
			$out .= '</div>';
		$out .= '</div>';
		
		return $out;		
	}
	add_shortcode('dt_history_wrapper', 'dt_history_wrapper');
}

//HOLIDAY PACKAGE...
if(!function_exists('dt_holiday_ads')) {
	
	function dt_holiday_ads( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'title'	=> '',
			'subtitle' => '',
			'link'	=> '',
			'image'	=> 'http://placehold.it/232x224',
			'button_text' => __('Hurry Book', 'iamd_text_domain')
		), $atts));

		$out = '';

		$out .= '<div class="holioday-pack-wrapper">';
			$out .= '<div class="holioday-pack">';
				$out .= '<h3>'.$title.'<br> <span>'.$subtitle.'</span></h3>';
				$out .= '<img src="'.$image.'" alt="holiday-img" />';
				$out .= '<h2><a href="'.$link.'">'.$button_text.'<br> <span>'.__('Now', 'iamd_text_domain').'</span></a></h2>';
			$out .= '</div>';
		$out .= '</div>';
		
		return $out;
	}
	add_shortcode('dt_holiday_ads', 'dt_holiday_ads');
}

//THEME STATISTICS...
if(!function_exists('dt_theme_status')) {
	
	function dt_theme_status( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'number' => '',
			'text' => ''
		), $atts));

		$out = '';

		$out .= '<p><span>'.$number.'</span> '.$text.'</p>';

		return $out;
	}
	add_shortcode('dt_theme_status', 'dt_theme_status');
}

//SUBSCRIBE SHORTCODE...
if(!function_exists('dt_subscribe_form')) {
	
	function dt_subscribe_form( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'title' => '',
			'subtitle' => '',
			'list_id' => ''
		), $atts));

		$out = '';

		$out .= '<div class="dt-footer-newsletter">';
			if($title != "")
				$out .= '<h3 class="widgettitle">'.$title.'</h3>';
			if($subtitle != "")
				$out .= '<p>'.$subtitle.'</p>';
				
			$out .= '<form name="frmsubscribe" method="post" class="subscribe-frm">';
				$out .= '<input type="email" name="mythem_mc_emailid" required="" placeholder="'.__('Enter Email', 'iamd_text_domain').'" />';
				$out .= "<input type='hidden' name='mythem_mc_listid' value='".$list_id."' />";
				$out .= '<input type="submit" name="submit" class="dt-sc-button small" value="'.__('Subscribe', 'iamd_text_domain').'" />';
			$out .= '</form>';
			
			if( isset( $_REQUEST['mythem_mc_emailid']) ):
				require_once(IAMD_FW."theme_widgets/mailchimp/MCAPI.class.php");
				$mcapi = new MCAPI( dt_theme_option('general','mailchimp-key') );
				
				$merge_vars = Array( 'EMAIL' => $_REQUEST['mythem_mc_emailid'] );
				
				if($mcapi->listSubscribe($list_id, $_REQUEST['mythem_mc_emailid'], $merge_vars ) ):
					$msg = '<span style="color:green;">'.__('Success!&nbsp; Check your inbox or spam folder for a message containing a confirmation link.', 'iamd_text_domain').'</span>';
				else:
					$msg = '<span style="color:red;"><b>'.__('Error:', 'iamd_text_domain').'</b>&nbsp; ' . $mcapi->errorMessage.'</span>';
				endif;
				
				if ( isset ( $msg ) ) $out .= '<span class="zn_mailchimp_result">'.$msg.'</span>';
			endif;
		$out .= '</div>';

		return $out;
	}
	add_shortcode('dt_subscribe_form', 'dt_subscribe_form');
}

//IMAGE MAP CONTAINER...
if(!function_exists('dt_image_map_container')) {
	
	function dt_image_map_container( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'bg_image' => 'http://placehold.it/1031x600&text=Map%20Image'
		), $atts));

		$out = '';
		
		$out .= '<div id="dt-image-map-container"><img src="'.$bg_image.'" />'.do_shortcode($content).'</div>';

		return $out;
	}
	add_shortcode('dt_image_map_container', 'dt_image_map_container');
}

//IMAGE MAP POINTER...
if(!function_exists('dt_image_map_pointer')) {
	
	function dt_image_map_pointer( $atts, $content = null ) {
		extract(shortcode_atts(array(
			'title' => '',
			'top' => '162',
			'left' => '574',
			'color' => 'blue'
		), $atts));

		$out = '';

		$style = "style='left:{$left}px; top:{$top}px;'";
		$divID = mt_rand();

		$out .= '<a href="#div_id_'.$divID.'" class="dt-sc-map-tooltip dt-map-pointer '.$color.'" title="'.$title.'" '.$style.'></a>';
        $out .= '<div id="div_id_'.$divID.'" class="dt-pointer-content" style="display:none;">'.do_shortcode($content).'</div>';

		return $out;
	}
	add_shortcode('dt_image_map_pointer', 'dt_image_map_pointer');
} ?>