<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Files wrapper
 */
class Files {

    private $db_files;

    public function __construct() {
        $this->load_dependencies();
        $this->db_files = new DbFiles();
    }

    /**
     * Load dependent modules
     */
    private function load_dependencies() {
        require_once SQHR_DM_PLUGIN_PATH . 'system/model/class.db.files.php';
    }

    /**
     * Get file id using its URL 
     * 
     * Also adds the file to database if the file with this URL does not exist
     * 
     * @param url $file_url URL of file
     * @return int
     */
    public function get_file_id($file_url) {
        $file = $this->get_by_url($file_url);
        if ($file) {
            $file_id = $file->id;
        } else {
            $file_id = $this->add($file_url);
        }
        return $file_id;
    }

    /**
     * Save file information to database
     * 
     * @param url $url URL of file
     * @return object
     */
    public function add($url) {
        $this->db_files->file_url = $url;
        $this->db_files->DOC = time();
        $this->db_files->status = 0;

        return $this->db_files->add();
    }

    /**
     * Get file information by its Id
     * 
     * @param url $id Id of file
     * @return object
     */
    public function get_by_id($id) {
        return $this->db_files->get_by_id($id);
    }

    /**
     * Get file information by its URL
     * 
     * @param url $url URL of file
     * @return object
     */
    public function get_by_url($url) {
        return $this->db_files->get_by_url($url);
    }

}
