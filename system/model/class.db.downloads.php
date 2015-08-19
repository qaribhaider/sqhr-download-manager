<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Downloads list database class
 * 
 * Uses $wpdb Wordpress database instance
 */
class DbDownloads {

    private $db;
    private $table_name;
    private $charset;
    public $file_id;
    public $user_id;
    public $ip;
    public $browser;
    public $DOC;
    public $status;

    public function __construct() {
        global $wpdb;

        $this->db = $wpdb;
        $this->table_name = $this->db->prefix . 'sqhr_dm_downloads';
        $this->charset = $this->db->get_charset_collate();
        $this->_create_table();
    }

    /**
     * Create table
     */
    public function _create_table() {
        $sql = "CREATE TABLE IF NOT EXISTS $this->table_name (
                    id int(11) NOT NULL AUTO_INCREMENT,
                    file_id int(11) NOT NULL,
                    user_id int(11) NOT NULL,
                    ip varchar(150) NOT NULL,
                    browser varchar(255) NOT NULL,
                    DOC int NOT NULL,
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
            'file_id' => $this->file_id,
            'user_id' => $this->user_id,
            'ip' => $this->ip,
            'browser' => $this->browser,
            'DOC' => $this->DOC,
                ), array('%d', '%d', '%s', '%s', '%d')
        );

        return ($insert) ? $this->db->insert_id : $insert;
    }

    /**
     * Return all records 
     * 
     * @return object
     */
    public function get_all() {
        return $this->db->query("SELECT * FROM {$this->table_name}");
    }

}
