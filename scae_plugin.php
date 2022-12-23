<?php
/*
Plugin Name: Scae Custom Plugins
Plugin URI: www.scae.academy
Description: This plugin creates custom set of pages for SCAE site.
Version: 1.0
Author: João Lucas
Author URI: https://github.com/lucassdmp
*/

//Initialize SCAE DB  on activation (WORKING)
require_once("includes/DB.php");
register_activation_hook(__FILE__, 'create_DB');

// TO DO AND CLEAR DOWN THERE

include("includes/plugin.php");


?>