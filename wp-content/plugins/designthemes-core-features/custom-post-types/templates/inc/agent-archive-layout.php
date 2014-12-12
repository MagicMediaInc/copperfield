<?php
	//PERFORMING GALLERY POST LAYOUT...
	$page_layout = dt_theme_option('specialty', 'gallery-archives-layout');
	$page_layout = !empty($page_layout) ? $page_layout : 'content-full-width';
	
	$post_layout = dt_theme_option('specialty', 'gallery-archives-post-layout');
	$post_layout = !empty($post_layout) ? $post_layout : 'one-half-column';
	
	$li_class = "";
	
	//POST LAYOUT CHECK...
	if($post_layout == "one-column" || $post_layout == "one-half-column") {
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
	
	global $wp_query;	//FOR PAGINATION PURPOSE...
	if(have_posts()): ?>
     
     <div class="dt-sc-portfolio-container"><?php
		while(have_posts()): the_post(); ?>
			<div class="<?php echo $li_class; ?> no-space">
              <figure><?php
				$fullimg = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full', false);
				$currenturl = $fullimg[0];
                $currenticon = "fa-plus";
				$pmeta_set = get_post_meta($post->ID, '_gallery_settings', true);
				if( @array_key_exists('items_thumbnail', $pmeta_set) && ($pmeta_set ['items_name'] [0] == 'video' )) {
					$currenturl = $pmeta_set ['items'][0];
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
	 <div class="dt-sc-hr-invisible"></div><?php
	 //Pagination...
	 if($wp_query->max_num_pages > 1): ?>
		<div class="pagination blog-pagination">
			<?php if(function_exists("dt_theme_pagination")) echo dt_theme_pagination("", $wp_query->max_num_pages, $wp_query); ?>
		</div><?php
	 endif;
	 wp_reset_query($wp_query);
	 else: ?>
		<h2><?php _e('Nothing Found.', 'dt_themes'); ?></h2>
		<p><?php _e('Apologies, but no results were found for the requested archive.', 'dt_themes'); ?></p><?php
	endif; ?>