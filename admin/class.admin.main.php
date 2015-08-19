<?php

/**
 * Plugin admin (WP) module loader and handler
 */
class SQHR_Download_Manager_Admin {

    protected $version;

    public function __construct($version) {
        $this->version = $version;
        $this->load_dependencies();
    }

    /**
     * Load dependent module
     */
    private function load_dependencies() {
        require_once SQHR_DM_PLUGIN_PATH . 'system/thirdparty/wp.list.data.downloads.php';
        require_once SQHR_DM_PLUGIN_PATH . 'system/model/class.db.downloads.php';
    }

    /**
     * Callback function for handling shortcode
     */
    public function shortcode_download_link($atts, $content = "") {
        $force_download = true;

        if (!isset($atts['href'])) {
            $atts['href'] = false;
        }
        if (isset($atts['force'])) {
            $force_download = ($atts['force'] == "no") ? false : true;
        }

        $atts['href'] = sqhrdm_get_download_link($atts['href'], $force_download);

        $return = '<a ';

        foreach ($atts as $att_key => $att) {
            $return .= $att_key . '="' . $att . '"';
        }
        $return .= '>' . $content . '</a>';

        return $return;
    }

    /**
     * Add navigation in wp admin 
     */
    function add_list_contact_form_page_nav() {
        add_menu_page('Downloads', 'Downloads', 'publish_posts', 'sqhrdm_downloads', array($this, 'list_sqhrdm_downloads_page'), 'dashicons-download', '81');
    }

    /**
     * Options page - Main page, callback function of add menu
     */
    function list_sqhrdm_downloads_page() {
        $downloads = new DbDownloads();
        $total_listings = $downloads->get_all();
        ?>  
        <div class="wrap">  
            <h2>Latest Downloads</h2>  

            <?php
            if (!$total_listings) {
                echo '<div class="error">No downloads until now..</div>';
            } else {
                $wp_list_table = new SQHR_DM_Downloads_List_Table();
                $wp_list_table->prepare_items();
                $wp_list_table->display();
            }
            ?>
        </div>  
        <?php
    }

}
