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

 
register_activation_hook(__FILE__, ['OrganizerMedia','active']);
register_activation_hook(__FILE__, ['OrganizerMedia','deactive']);
add_action('init', ['OrganizerMedia','init']);

