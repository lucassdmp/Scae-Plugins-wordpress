<?php
//Require all modules
require_once(SCAE_MODULES.'SCAE_modules_load.php');

//Require all pages
require_once(SCAE_PAGES.'SCAE_pages_load.php');

//Enqueue SCAE_style   
function scae_enqueue_style(){
    wp_enqueue_style('SCAE_styles', SCAE_CSS . 'SCAE_styles.css');
    wp_enqueue_style('SCAE_add_form', SCAE_CSS . 'SCAE_add_form.css');
    wp_enqueue_style('SCAE_table_content', SCAE_CSS . 'SCAE_table_content.css');
}
add_action('wp_enqueue_scripts', 'scae_enqueue_style');
?>