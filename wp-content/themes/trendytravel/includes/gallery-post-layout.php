<?php
	//PERFORMING GALLERY POST LAYOUT...
	$meta_set = get_post_meta($post->ID, '_tpl_default_settings', true);
	$page_layout = !empty($meta_set['layout']) ? $meta_set['layout'] : 'content-full-width';
	$post_layout = !empty($meta_set['gallery-post-layout']) ? $meta_set['gallery-post-layout'] : 'one-half-column';
	
	$li_class = "";
	
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
	
	//POST VALUES....
	$limit = $meta_set['gallery-post-per-page'];
	$cats  = $meta_set['gallery-categories'];
	
	$cats = array_filter(array_unique($cats));
	
	if(empty($cats)) {
		$cats = get_categories('taxonomy=gallery_entries&hide_empty=1');
		$cats = get_terms( array('gallery_entries'), array('fields' => 'ids'));		
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
	$wp_query = new WP_Query($args);
	if($wp_query->have_posts()): 
	
	 if(isset($meta_set['filter']) != ""): ?>
         <div class="dt-sc-sorting-container">
            <a href="#" data-filter="*" class="first active-sort"><?php _e('All', 'iamd_text_domain'); ?></a>
            <?php
				foreach($cats as $term) {
					$myterm = get_term_by('id', $term, 'gallery_entries');
					?><a href="#" data-filter=".<?php echo strtolower($myterm->slug); ?>"><?php echo $myterm->name; ?></a><?php
				}?>
         </div><?php
	 endif; ?>
     
     <div class="dt-sc-portfolio-container"><?php
		while($wp_query->have_posts()): $wp_query->the_post(); 
			$terms = wp_get_post_terms($post->ID, 'gallery_entries', array("fields" => "slugs")); array_walk($terms, "arr_strfun"); ?>
			<div class="<?php echo $li_class." ".strtolower(implode(" ", $terms)); ?> no-space">
                <figure><?php
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
                      the_post_thumbnail('full', $attr);
                  else: ?>
                      <img src="http://placehold.it/1170X800.jpg&text=No Image" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" /><?php
                  endif; ?>
                  <figcaption>
                  	<div class="fig-content-wrapper">
                      <div class="fig-content">
                          <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                          <p><?php echo get_the_term_list($post->ID, 'gallery_entries', ' ', ', ', ' '); ?></p>                          
                          <div class="fig-overlay">
                              <a class="zoom" title="<?php the_title(); ?>" data-gal="prettyPhoto[gallery]" href="<?php echo $currenturl; ?>"><span class="fa <?php echo $currenticon; ?>"> </span></a>
                              <a class="link" href="<?php the_permalink(); ?>"> <span class="fa fa-link"> </span> </a>
                              <?php if(dt_theme_is_plugin_active('roses-like-this/likethis.php')): ?>
	                              <?php printLikes($post->ID); ?>
							  <?php endif; ?>
                          </div>
                      </div>
                    </div>  
                  </figcaption>
                </figure>
	        </div><?php
		endwhile; ?>
     </div>
     <?php if($limit != -1): ?>
         <div class="aligncenter load-more">
             <button id="ajax_load_gallery" class="dt-sc-button small" data-per-page="<?php echo $limit; ?>" data-taxonomy="<?php echo implode(',', $cats); ?>" data-li-class="<?php echo $li_class; ?>"><?php _e('Load More', 'iamd_text_domain'); ?><span></span></button>
        </div>
	<?php endif;
	 wp_reset_query($wp_query);
	 else: ?>
		<h2><?php _e('Nothing Found.', 'iamd_text_domain'); ?></h2>
		<p><?php _e('Apologies, but no results were found for the requested archive.', 'iamd_text_domain'); ?></p><?php
	endif; ?>