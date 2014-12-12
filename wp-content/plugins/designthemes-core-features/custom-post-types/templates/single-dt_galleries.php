<?php get_header();

	while(have_posts()): the_post();
	
	  //GETTING META VALUES...
	  $meta_set = get_post_meta($post->ID, '_gallery_settings', true);
	  $page_layout = !empty($meta_set['layout']) ? $meta_set['layout'] : 'single-gallery-layout-one';
	
	  //BREADCRUMP...
	  get_template_part('includes/breadcrumb_section'); ?>
      
      <div id="main">
		<section id="primary" class="content-full-width">
            <div class="dt-sc-hr-invisible"></div>
            <div class="dt-sc-hr-invisible-small"></div>
			<div id="post-<?php the_ID(); ?>" <?php post_class('fullwidth-section'); ?>>
				<div class="container">
                    <div class="portfolio-single"><?php
					
						//Checking the layouts...
						if($page_layout == "single-gallery-layout-one"):
							include(dirname(__FILE__).'/inc/gallery-single-layout-one.php');
						else:
							include(dirname(__FILE__).'/inc/gallery-single-layout-two.php');
						endif; ?>
                        
						<div class="post-nav-container">
                            <div class="prev-post">
							<?php
                                previous_post_link('%link', '<span class="fa fa-angle-left"></span> '.__('Previous', 'dt_themes'));
                                previous_post_link('%link', '<p>%title</p>'); ?>
                            </div>
                            <div class="next-post">
							<?php
                                next_post_link('%link', __('Next', 'dt_themes').' <span class="fa fa-angle-right"></span>');
                                next_post_link('%link', '<p>%title</p>'); ?>
                            </div>
                        </div>
						<?php if(dt_theme_option('general', 'disable-gallery-comment') != true && (isset($p_meta_set['comment']) != "")) { 
								echo '<div class="dt-sc-hr-invisible"></div>';
								comments_template('', true);
							  } ?>
                        <div class="dt-sc-hr-invisible-small"></div>
                    </div>
				</div>
			</div><?php
			//Show related posts items...
			if(isset($meta_set['show-related-items']) != ""):
				
				$args = array('orderby' =>	'rand', 'post_type' => 'dt_galleries', 'post__not_in' => array(get_the_ID()), 'posts_per_page' => 8);
				
				$the_query = new WP_Query($args);
				if($the_query->have_posts()): ?>
                    <div class="fullwidth-section">
                        <div class="dt-sc-portfolio-container"><?php
						while($the_query->have_posts()): $the_query->the_post();
						$terms = wp_get_post_terms($post->ID, 'gallery_entries', array("fields" => "slugs")); array_walk($terms, "arr_strfun"); ?>
                        
                            <div class="portfolio dt-sc-one-fourth column no-space <?php strtolower(implode(" ", $terms)); ?>">
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
		            </div><?php
				endif;
			endif; ?>
		</section>
      </div>
      
<?php endwhile; ?>

<?php get_footer(); ?>