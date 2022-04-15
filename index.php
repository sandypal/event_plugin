<?php
/**
 * Plugin Name: Event Listing
 * Plugin URI:
 * Description: Event List save and preview
 * Author: Sandeep
 */

# Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
#Absolute path to the plugin directory.
#eg - /var/www/html/wp-content/plugins/event-listing/
if (!defined('event_listing')) {
    define('event_listing', plugin_dir_path(__FILE__));
}

#Load everything
if (file_exists(dirname(__FILE__) . '/public/event.php')) {
    require_once dirname(__FILE__) . '/public/event.php';
}


?>