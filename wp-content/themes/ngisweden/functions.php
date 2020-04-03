<?php
/* NGIsweden Theme Functions */

//////// DEBUG ONLY
///// REMOVE THIS WHEN THE SITE IS GOING LIVE
function show_all_draft_pending( $query ) {

    // Don't do this if we're on the admin overview tables,
    // otherwise we can't filter by post status
    if(is_admin() && get_current_screen()->base == 'edit'){
        return;
    }

    // Return all posts, even if draft or pending
    $query->set('post_status', 'publish,pending,draft,auto,future,private,inherit,trash');
}
add_action('pre_get_posts', 'show_all_draft_pending');

// Make a new user role where people can edit anything but not publish
function add_roles_on_plugin_activation() {
    // Get editor capabilities and then edit these
    $editor_caps = get_role( 'administrator' )->capabilities;
    $editor_caps['publish_pages'] = false;
    $editor_caps['publish_posts'] = false;
    // Make the new role
    add_role( 'editor_nopublish', 'NGI Editor', $editor_caps );
}
register_activation_hook(__FILE__, 'add_roles_on_plugin_activation' );

// Enqueue Bootstrap JS and CSS files
function ngis_wp_bootstrap_scripts_styles() {
    $ngisweden_theme = wp_get_theme();
    wp_enqueue_script('popperjs', get_stylesheet_directory_uri().'/includes/js/popper.min.js', array(), '1.14.7', true );
    wp_enqueue_script('bootstrapjs', get_stylesheet_directory_uri().'/includes/js/bootstrap.min.js', array('jquery'), '4.3.1', true );
    wp_enqueue_script('ngisweden', get_stylesheet_directory_uri().'/ngisweden.js', array('jquery'), $ngisweden_theme->version, true);
    wp_enqueue_style('bootstrapcss', get_stylesheet_directory_uri().'/includes/css/bootstrap.min.css', array(),'4.3.1');
    wp_enqueue_style('fontawesomecss', get_stylesheet_directory_uri().'/includes/css/fontawesome.all.min.css', array(),'5.8.1');
    wp_enqueue_style('ngisweden', get_stylesheet_directory_uri().'/style.css', array(), $ngisweden_theme->version);
}
add_action('wp_enqueue_scripts', 'ngis_wp_bootstrap_scripts_styles');

// Register navigation menus
function register_ngisweden_nav() {
    register_nav_menu('main-nav', __( 'Main Navigation' ));
    register_nav_menu('secondary-nav', __( 'Secondary Navigation' ));
}
add_action('init', 'register_ngisweden_nav');

// Functions for nav breadcrumbs
require_once('includes/bs4navwalker.php');
require_once('functions/bootstrap-breadcrumb.php');

// Bootstrap pagination links
require_once('functions/bootstrap-pagination.php');

// Search - highlight terms
function ngi_highlight_search_terms($text){
    if(is_search() && !is_admin()){
        $sr = get_query_var('s');
        if(trim($sr) != ''){
            $keys = explode(" ",$sr);
            $keys = array_filter($keys);
            $text = preg_replace('/('.implode('|', $keys) .')/iu', '<mark>$1</mark>', $text);
        }
    }
    return $text;
}
add_filter('the_excerpt', 'ngi_highlight_search_terms');
add_filter('the_title', 'ngi_highlight_search_terms');

// Rename "Posts" to "News"
// https://gist.github.com/gyrus/3155982
add_action( 'admin_menu', 'ngisweden_change_post_menu_label' );
add_action( 'init', 'ngisweden_change_post_object_label' );
function ngisweden_change_post_menu_label() {
    global $menu;
    global $submenu;
    $menu[5][0] = 'News';
    $submenu['edit.php'][5][0] = 'News';
    $submenu['edit.php'][10][0] = 'Add News';
    $submenu['edit.php'][16][0] = 'News Tags';
    echo '';
}
function ngisweden_change_post_object_label() {
    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;
    $labels->name = 'News';
    $labels->singular_name = 'News';
    $labels->add_new = 'Add News';
    $labels->add_new_item = 'Add News';
    $labels->edit_item = 'Edit News';
    $labels->new_item = 'News';
    $labels->view_item = 'View News';
    $labels->search_items = 'Search News';
    $labels->not_found = 'No News found';
    $labels->not_found_in_trash = 'No News found in Trash';
}

// Exclude media library attachments from search results
add_action( 'init', 'ngisweden_exclude_attachments_from_search_results' );
function ngisweden_exclude_attachments_from_search_results() {
    global $wp_post_types;
    $wp_post_types['attachment']->exclude_from_search = true;
}

// Enqueue a custom javascript file for extending gutenberg
function ngi_guten_enqueue() {
    wp_enqueue_script('ngi-gutenberg-extend',
        get_stylesheet_directory_uri().'/ngi-gutenberg-extend.js',
        array( 'wp-blocks' )
    );
}
add_action('enqueue_block_editor_assets', 'ngi_guten_enqueue');

// Code to clean up and improve the WordPress admin interface
require_once('functions/admin_ui.php');

// Initialising widget areas, creating new widgets
require_once('functions/widgets.php');

// New options for the Appearance > Customise
require_once('functions/theme_customiser.php');

// Theme shortcodes
require_once('functions/shortcodes/method-tabs.php');
require_once('functions/shortcodes/ngi-ajaxsearch.php');
require_once('functions/shortcodes/homepage.php');
require_once('functions/shortcodes/publications.php');
require_once('functions/shortcodes/github_repo.php');
require_once('functions/shortcodes/mailchimp_signup.php');
require_once('functions/shortcodes/site_map.php');
