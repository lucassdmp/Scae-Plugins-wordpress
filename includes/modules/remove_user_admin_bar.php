<?php
add_action('after_setup_theme', 'scae_remove_admin_bar');
function scae_remove_admin_bar()
{
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}

add_action('admin_menu', 'scae_remove_admin_menu_pages');

function scae_remove_admin_menu_pages() {
    if (!current_user_can('administrator')) {
        remove_menu_page('index.php');
        remove_menu_page('edit.php');
        remove_menu_page('upload.php');
        remove_menu_page('link-manager.php');
        remove_menu_page('edit.php?post_type=page');
        remove_menu_page('edit-comments.php');
        remove_menu_page('themes.php');
        remove_menu_page('plugins.php');
        remove_menu_page('users.php');
        remove_menu_page('tools.php');
        remove_menu_page('options-general.php');
    }
}
?>