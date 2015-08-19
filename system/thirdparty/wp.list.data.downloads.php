<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Import required table listing class
 */
if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Custom table listing class
 */
class SQHR_DM_Downloads_List_Table extends WP_List_Table {

    /**
     * Override the parent constructor to pass custom parameters
     */
    function __construct() {
        parent::__construct(array(
            'singular' => 'wp_list_bdm_downloads', // Singular label
            'plural' => 'wp_list_bdm_downloads', // Plural label
            'ajax' => false // AJAX support ?
        ));
    }

    /**
     * Extra navigation
     * 
     * @param string $which
     */
    function extra_tablenav($which) {
        if ($which == "top") {
            // Code that goes before the table
        }
        if ($which == "bottom") {
            // Code that goes after the table
        }
    }

    /**
     * Define columns and their titles
     * 
     * @return array
     */
    function get_columns() {
        return $columns = array(
            'col_id' => __('SNo'),
            'col_file_id' => __('File'),
            'col_user_id' => __('User'),
            'col_ip' => __('IP'),
            'col_DOC' => __('Date')
        );
    }

    /**
     * Define sortable columns
     * 
     * @return array
     */
    public function get_sortable_columns() {
        return $sortable = array(
            'col_id' => array('id', true),
            'col_file_id' => array('file_id', true),
            'col_user_id' => array('user_id', true),
            'col_ip' => array('ip', true),
            'col_DOC' => array('DOC', true)
        );
    }

    /**
     * Fetch and Prepare table data
     * 
     * @global object $wpdb
     * @global array $_wp_column_headers
     */
    function prepare_items() {
        global $wpdb;

        // Table name
        $table_name = $wpdb->prefix . "sqhr_dm_downloads";

        // Prepare query
        $query = "SELECT * FROM $table_name";

        // Order parameters
        $orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'ASC';
        $order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : '';
        if (!empty($orderby) & !empty($order)) {
            $query.=' ORDER BY ' . $orderby . ' ' . $order;
        }

        // Pagination parameters
        // Number of elements in table
        // Returns the total number of affected rows
        $totalitems = $wpdb->query($query);

        // How many records to display per page
        $perpage = 10;

        // Get the current page
        $paged = !empty($_GET["paged"]) ? mysql_real_escape_string($_GET["paged"]) : '';
        if (empty($paged) || !is_numeric($paged) || $paged <= 0) {
            $paged = 1;
        }

        // Count Total pages
        $totalpages = ceil($totalitems / $perpage);

        // Add pagination parameters to query
        if (!empty($paged) && !empty($perpage)) {
            $offset = ($paged - 1) * $perpage;
            $query.=' LIMIT ' . (int) $offset . ',' . (int) $perpage;
        }

        // Register pagination
        // The pagination links are automatically built according to these parameters
        $this->set_pagination_args(array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $perpage,
        ));

        // Register the columns
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        // Fetch results and store in local variable
        $this->items = $wpdb->get_results($query);
    }

    function display_rows() {
        // Get the records fetched by prepare_items
        $records = $this->items;

        // Get the columns registered in the get_columns and get_sortable_columns methods
        list( $columns, $hidden ) = $this->get_column_info();

        // Loop for each record
        foreach ($records as $rec) {
            // Row
            echo '<tr id="record_' . $rec->id . '">';
            foreach ($columns as $column_name => $column_display_name) {

                // Style attributes for each column
                $class = "class='$column_name column-$column_name'";
                $style = (in_array($column_name, $hidden)) ? ' style="display:none;"' : "";
                $attributes = $class . $style;

                // Display the cell
                switch ($column_name) {
                    case "col_id": echo '<td ' . $attributes . '>' . stripslashes($rec->id) . '</td>';
                        break;
                    case "col_file_id": echo '<td ' . $attributes . '>' . $this->parseFileId($rec->file_id) . '</td>';
                        break;
                    case "col_user_id": echo '<td ' . $attributes . '>' . $this->parseUserId($rec->user_id) . '</td>';
                        break;
                    case "col_ip": echo '<td ' . $attributes . '>' . stripslashes($rec->ip) . '</td>';
                        break;
                    case "col_DOC": echo '<td ' . $attributes . '>' . $this->parseDate($rec->DOC) . '</td>';
                        break;
                }
            }

            echo'</tr>';
        }
    }

    /**
     * Parse user id to return user name
     * 
     * @param int $userid
     * @return string
     */
    function parseUserId($userid = null) {
        if (!$userid) {
            return 'Unknown';
        }

        $user_info = get_userdata($userid);
        $name = $user_info->first_name . ' ' . $user_info->last_name;

        return (strlen(trim($name)) > 1) ? $name : $user_info->user_login;
    }

    /**
     * Parse file id to return file url
     * 
     * @param int $fileid
     * @return string
     */
    function parseFileId($fileid = null) {
        if (!$fileid) {
            return '-';
        }

        require_once SQHR_DM_PLUGIN_PATH . 'system/controllers/class.files.php';

        $files = new Files();
        $file = $files->get_by_id($fileid);

        if ($file) {
            $fileurl = $file->file_url;
            $siteurl = get_site_url() . '/';

            if (strpos($fileurl, $siteurl) !== false) {
                $fileurl = str_replace($siteurl, '', $fileurl);
            }

            return $fileurl;
        } else {
            return '-';
        }
    }

    /**
     * Parse date
     * 
     * @param UNIXTIMESTAMP $timestamp
     * @return string Human readable date
     */
    function parseDate($timestamp) {
        return date("d M Y", $timestamp);
    }

}
