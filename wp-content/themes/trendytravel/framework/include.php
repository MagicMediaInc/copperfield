<?php
require_once get_template_directory().'/framework/social_media.php';
require_once get_template_directory().'/framework/google_fonts.php';
require_once get_template_directory().'/framework/theme_features.php';
require_once get_template_directory().'/framework/utils.php';
require_once get_template_directory().'/framework/admin_utils.php';
require_once get_template_directory().'/framework/register_admin.php';
require_once get_template_directory().'/framework/register_public.php';
require_once get_template_directory().'/framework/register_media_uploader.php';

##Metaboxes
require_once get_template_directory().'/framework/theme_metaboxes/post_metabox.php';
require_once get_template_directory().'/framework/theme_metaboxes/page_metabox.php';
require_once get_template_directory().'/framework/theme_metaboxes/seo_metabox.php';

#TGM Plugins
require_once get_template_directory().'/framework/class-tgm-plugin-activation.php';
require_once get_template_directory().'/framework/register_plugins.php';

##Register Widgets
require_once get_template_directory().'/framework/register_widgets.php';

##Register Widget Areas
require_once get_template_directory().'/framework/register_widget_areas.php';

##Include Theme options
require_once get_template_directory().'/framework/theme_options/menu.php';

##Include Theme shortcodes
require_once get_template_directory().'/framework/theme_shortcodes.php';

##Include Shop Woocommerce
if(dt_theme_is_plugin_active('woocommerce/woocommerce.php'))
	require_once get_template_directory().'/framework/woocommerce/index.php';

##MegaMenu
require_once get_template_directory().'/framework/register_custom_attributes_in_menu.php'; ?>