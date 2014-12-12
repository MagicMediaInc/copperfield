<?php
/*
 * Plugin Name:	Agents Plugin 
 * URI: 	http://magicmedia.com.ve
 * Description: A simple wordpress plugin designed to implements <strong>core features of DesignThemes</strong> 
 * Version: 	1.1 
 * Author: 		Alexis Montenegro CTO Magicmedia Inc. 
 * Author URI:	http://alexismontenegro.com.ve
 */

add_action( 'show_user_profile', 'add_extra_social_links' );
add_action( 'edit_user_profile', 'add_extra_social_links' );

function add_extra_social_links( $user )
{
    ?>
        <h3>Cuenta de MercadoPago</h3>

        <table class="form-table">
            <tr>
                <th><label for="mc_client_id">MercadoPago ClientID</label></th>
                <td><input type="text" name="mc_client_id" value="<?php echo esc_attr(get_the_author_meta( 'mc_client_id', $user->ID )); ?>" class="regular-text" /></td>
            </tr>

            <tr>
                <th><label for="mc_client_secret">MercadoPago Client Secret</label></th>
                <td><input type="text" name="mc_client_secret" value="<?php echo esc_attr(get_the_author_meta( 'mc_client_secret', $user->ID )); ?>" class="regular-text" /></td>
            </tr>
        </table>
    <?php
}

add_action( 'personal_options_update', 'save_extra_social_links' );
add_action( 'edit_user_profile_update', 'save_extra_social_links' );

function save_extra_social_links( $user_id )
{
    update_user_meta( $user_id,'mc_client_id', sanitize_text_field( $_POST['mc_client_id'] ) );
    update_user_meta( $user_id,'mc_client_secret', sanitize_text_field( $_POST['mc_client_secret'] ) );
}

?>