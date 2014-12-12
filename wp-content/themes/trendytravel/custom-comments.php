<?php
// Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
die ('Please do not load this page directly. Thanks!');
 
if ( post_password_required() ) { ?>
	<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
<?php 
	return;
}
?>
<!-- START EDITING HERE. -->

<?php if(have_comments()): ?>
	
	<?php if(get_comment_pages_count() > 1 && get_option('page_comments')): ?>
    	<div class="pagination">
            <ul class="commentNav">
                <li><?php previous_comments_link(); ?></li>
                <li><?php next_comments_link(); ?></li>
            </ul>
		</div>
	<?php endif; ?>
    
    <div class="reviewentries" id="reviews">
		<h2 class="section-title"><?php comments_number(__('No Travellers Word', 'iamd_text_domain'), __('Travellers Word (1)', 'iamd_text_domain'), __('Travellers Word (%)', 'iamd_text_domain')); ?></h2>
        <div class="reviewlist">
            <?php wp_list_comments('avatar_size=62&type=comment&callback=dt_theme_hotel_comments&style=div'); ?>
        </div>
	</div><?php
	else:
		if('open' == $post->comment_status): ?>
            <h2 class="section-title"><?php _e('No Travellers Word', 'iamd_text_domain'); ?></h2><?php
		endif;
	endif;
	
	//PERFORMING COMMENT FORM...
	if('open' == $post->comment_status):
		$args = array(
			'comment_field' => '<div class="dt-sc-one-column column"><p><textarea id="comment" name="comment" placeholder="Message"></textarea></p></div>',
			'fields' => array(
					'author' => '<div class="dt-sc-one-half column first"><p><input id="author" name="author" type="text" required="" placeholder="Name (required)" /></p>',
					'email' => '<p><input id="email" name="email" type="text" required="" placeholder="Email (required)" /></p>',
					'profession' => '<p><input type="text" id="profession" name="profession" placeholder="Profession"></p></div>',
					'url' => '<div class="dt-sc-one-half column"><p><input id="url" name="url" type="text" placeholder="Website" /></p>',
					'title' => '<p><input type="text" id="title" name="title" required="" placeholder="Title (required)"></p>',
					'rating' => '<p><label for="dt-rating">' . __( 'Your Rating', 'iamd_text_domain' ) .'</label>
						  <select name="rating" id="dt-rating">
							  <option value="">' . __( 'Rate&hellip;', 'iamd_text_domain' ) . '</option>
							  <option value="5">' . __( 'Perfect', 'iamd_text_domain' ) . '</option>
							  <option value="4">' . __( 'Good', 'iamd_text_domain' ) . '</option>
							  <option value="3">' . __( 'Average', 'iamd_text_domain' ) . '</option>
							  <option value="2">' . __( 'Not that bad', 'iamd_text_domain' ) . '</option>
							  <option value="1">' . __( 'Very Poor', 'iamd_text_domain' ) . '</option>
						  </select></p></div>',),
			'comment_notes_before' => '',
			'label_submit' => __('Submit Review', 'iamd_text_domain'),
			'comment_notes_after' => '',
			'title_reply' => __('Leave a Review', 'iamd_text_domain'),
			'cancel_reply_link' => 'cancel reply'
		);
		comment_form($args);
	endif; ?>