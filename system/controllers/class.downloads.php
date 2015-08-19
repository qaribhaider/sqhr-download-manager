<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Downloads wrapper
 */
class Downloads {

    private $db_downloads;

    public function __construct() {
        $this->load_dependencies();
        $this->db_downloads = new DbDownloads();
    }

    /**
     * Load dependent modules
     */
    private function load_dependencies() {
        require_once SQHR_DM_PLUGIN_PATH . 'system/model/class.db.downloads.php';
    }

    /**
     * Save file record to database
     * 
     * @param int $file_id File Id
     */
    function add($file_id) {
        $this->db_downloads->file_id = $file_id;
        $this->db_downloads->user_id = get_current_user_id();
        $this->db_downloads->ip = get_client_ip();
        $this->db_downloads->browser = $_SERVER['HTTP_USER_AGENT'];
        $this->db_downloads->DOC = time();

        $this->db_downloads->add();
    }

}
