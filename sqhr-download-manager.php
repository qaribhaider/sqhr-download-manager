<?php

/**
 * Downloads Manager
 *
 * The plugin provides count and details for file downloads
 * 
 * Normal file links are parsed by the plugin, allowing to track downloads with
 * force download support. Links can be created using shortcode in wordpress 
 * editor or by using helper method in theme files directly
 * 
 * sqhrdm_get_download_link([FILE_URL], [FORCE_DOWNLOAD])
 * 
 * [dlink href="URL" class="CLASSES" id="ID"]CONTENT[/dlink]
 * [dlink href="http://wp.hug/ogci/wp-content/uploads/2015/08/home-01.jpg" class="asdas dds" id="dlink"]Here is the content[/dlink]
 *
 * @link              http://syedqarib.com
 * @since             1.0.0
 * @package           Downloads Manager
 *
 * @wordpress-plugin
 * Plugin Name:       Downloads Manager
 * Plugin URI:        http://syedqarib.com/
 * Description:       The plugin provides count and details for file downloads
 * Version:           1.0.0
 * Author:            Syed Qarib
 * Author URI:        http://syedqarib.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sqhr
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Include base files
 */
require_once plugin_dir_path(__FILE__) . 'system/global/constants.php';
require_once plugin_dir_path(__FILE__) . 'system/global/helpers.php';
require_once plugin_dir_path(__FILE__) . 'system/controllers/class.main.php';

/**
 * Initiate the plugin
 */
function init_sqhr_download_manager() {
    $plugin = new SQHR_Download_Manager();
    $plugin->run();
}

init_sqhr_download_manager();
