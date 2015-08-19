<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Plugin startup class
 * 
 * Used for firing up all modules (Admin, Public)
 */
class SQHR_Download_Manager {

    protected $loader;
    protected $plugin_slug;
    protected $version;

    public function __construct() {
        $this->plugin_slug = 'sqhr-download-manager';
        $this->version = '1.0.0';

        $this->load_dependencies();
        $this->define_public_hooks();
        $this->define_admin_hooks();
    }

    /**
     * Attach plugin dependencies and initiate loader
     */
    private function load_dependencies() {
        require_once SQHR_DM_PLUGIN_PATH . 'public/class.public.main.php';
        require_once SQHR_DM_PLUGIN_PATH . 'admin/class.admin.main.php';
        require_once SQHR_DM_PLUGIN_PATH . 'system/controllers/class.loader.php';

        $this->loader = new SQHR_Download_Manager_Loader();
    }

    /**
     * Define public side hooks
     */
    private function define_public_hooks() {
        $public = new SQHR_Download_Manager_Public($this->get_version());

        $this->loader->add_action('init', $public, 'process_download');
    }

    /**
     * Define admin side hooks
     */
    private function define_admin_hooks() {
        $admin = new SQHR_Download_Manager_Admin($this->get_version());

        $this->loader->add_action('admin_menu', $admin, 'add_list_contact_form_page_nav');
    }

    /**
     * Initiate and add run post loaded methods
     */
    public function run() {
        $this->loader->run();
        $this->add_shortcodes();
    }

    /**
     * Add shortcodes support
     * 
     * Shortcode    : [dlink][/dlink]
     * Usage        : [dlink href="URL"]CONTENT_GOES_HERE[/dlink]
     */
    public function add_shortcodes() {
        $admin = new SQHR_Download_Manager_Admin($this->get_version());
        add_shortcode('dlink', array($admin, 'shortcode_download_link'));
    }

    /**
     * Returns plugin version number
     * 
     * @return string Plugin version number
     */
    public function get_version() {
        return $this->version;
    }

}
