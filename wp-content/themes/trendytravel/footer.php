            <!-- footer starts here -->
            <footer id="footer">
				<?php if(dt_theme_option('general', 'show-footer')): ?>
                    <div class="footer-widgets-wrapper type2">
                        <div class="container"><?php dt_theme_show_footer_widgetarea(dt_theme_option('general','footer-columns')); ?></div>
                    </div>
                <?php endif; ?>
                
                <?php if(dt_theme_option('general','bottom-content') != '' && dt_theme_option('general', 'show-footer-bottom')): ?>
                    <div class="footer-row2">
                        <div class="container">
                            <?php echo do_shortcode(stripslashes(dt_theme_option('general','bottom-content'))); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="footer-row3">
                	<div class="container">
                        <div class="dt-sc-four-sixth column first"><?php
							//Footer Menu...
                            wp_nav_menu( array('theme_location' => 'secondary-menu', 'container'  => false, 'menu_class' => 'footer-links', 'fallback_cb' => 'dt_theme_footer_navigation'));
							//Partner Content...
							if(dt_theme_option('general','partner-content') != '')
								echo '<p>'.stripslashes(dt_theme_option('general','partner-content')).'</p>'; ?>
                        </div>
                        <div class="dt-sc-two-sixth column">
                        	<div class="dt-sc-hr-invisible-small"></div>
                            <div class="dt-sc-hr-invisible-small"></div>
                        	<p class="alignright"> <img src="<?php echo get_template_directory_uri(); ?>/images/trust-logo.png" alt="<?php _e('Trust Partner', 'iamd_text_domain'); ?>" class="alignright" /><?php _e('Your trustworthy partner <br /> for travel needs', 'iamd_text_domain'); ?></p>
                        </div>
                    </div>
                </div>
				<div class="copyright type2">
                	<div class="container"><?php
						if(dt_theme_option('general','community-status') != ''): ?>
                            <div class="foot-site-status">
                            	<?php echo do_shortcode(stripslashes(dt_theme_option('general','community-status'))); ?>
                            </div><?php
						endif; ?>
                        <?php if(dt_theme_option('general','show-copyrighttext') == "on"): ?>
                            <div class="copyright-content">
                                <p>&copy; 2014 tuescape - Marca registrada por <a href="http://www.magicmedia.com.ve">Magicmedia Inc</a> / Todos los derechos reservados<!--<?php //echo stripslashes(dt_theme_option('general','copyright-text')); ?>--></p>

                            </div>
                        <?php endif; ?>    
                    </div>
                </div>
            </footer>
            <!-- footer ends here -->
		</div>
    </div>
<?php if(dt_theme_option('integration', 'enable-body-code') != '') echo stripslashes(dt_theme_option('integration', 'body-code'));
wp_footer(); ?>
</body>
</html>