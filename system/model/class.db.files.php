<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Files list database class
 * 
 * Uses $wpdb Wordpress database instance
 */
class DbFiles {

    private $db;
    private $table_name;
    private $charset;
    public $file_url;
    public $DOC;
    public $status;

    public function __construct() {
        global $wpdb;

        $this->db = $wpdb;
        $this->table_name = $this->db->prefix . 'sqhr_dm_files';
        $this->charset = $this->db->get_charset_collate();
        $this->_create_table();
    }

    /**
     * Create table
     */
    public function _create_table() {
        $sql = "CREATE TABLE IF NOT EXISTS $this->table_name (
                    id int(11) NOT NULL AUTO_INCREMENT,
                    file_url varchar(255) NOT NULL,
                    DOC int NOT NULL,
                    status tinyint NOT NULL,
		UNIQUE KEY id (id),
                PRIMARY KEY (id)
          ) $this->charset;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);
    }

    /**
     * Add or update record
     *  
     * @return boolean/int Status/Post id
     */
    public function add() {
        $insert = $this->db->insert(
                $this->table_name, array(
            'file_url' => $this->file_url,
            'DOC' => $this->DOC,
            'status' => $this->status
                ), array('%s', '%d', '%d')
        );

        return ($insert) ? $this->db->insert_id : $insert;
    }

    /**
     * Get record by URL 
     * 
     * @param url $url URL of file
     * @return object
     */
    public function get_by_url($url) {
        $query = $this->db->prepare("SELECT * FROM {$this->table_name} WHERE file_url='%s'", $url);
        $result = $this->db->get_row($query);

        return $result;
    }

    /**
     * Get record by ID
     * 
     * @param int $id File Id
     * @return object
     */
    public function get_by_id($id) {
        $query = $this->db->prepare("SELECT * FROM {$this->table_name} WHERE id='%d'", $id);
        $result = $this->db->get_row($query);

        return $result;
    }

}
