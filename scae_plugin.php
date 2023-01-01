<?php
/*
Plugin Name: Scae Custom Plugins
Plugin URI: www.scae.academy
Description: This plugin creates custom set of pages for SCAE site.
Version: 1.1
Author: João Lucas
Author URI: https://github.com/lucassdmp
*/
require_once("variables.php");
//Initialize SCAE DB  on activation (WORKING)

// TO DO AND CLEAR DOWN THERE

require_once(SCAE_INCLUDES.'SCAE.php');

function scae_enqueue_table_styles(){
    wp_enqueue_style('table_styles', plugin_dir_url(__FILE__) . '/pages/css/table.css');
}
add_action('wp_enqueue_scripts', 'scae_enqueue_table_styles');


?>