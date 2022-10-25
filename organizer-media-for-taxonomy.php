<?php
/**
 * Plugin Name: Organizer Media for Taxonomy
 * Plugin URI: 
 * Description: 
 * Version: 1.0.0
 * Author: Octavio Martinez
 * Author URI: https://github.com/zenx5
 * 
 */


require 'vendor/autoload.php';
 
register_activation_hook(__FILE__, ['OrganizeMedia','activation']);

register_deactivation_hook(__FILE__, ['OrganizeMedia','deactivation']);

add_action('init', ['OrganizeMedia','init']);

