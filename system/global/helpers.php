<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Helper functions for plugin
 * 
 * These functions will be available globally when this plugin is activated
 */
if (!function_exists('sqhrdm_get_download_link')) {

    /**
     * Helper function to use within theme files for hardcoded links
     * 
     * @param url $file_url URL of file
     * @param boolean $force_download Wheather to force download or not
     * @return url Parsed link
     */
    function sqhrdm_get_download_link($file_url = null, $force_download = true) {
        if (!$file_url) {
            return '#';
        }

        require_once SQHR_DM_PLUGIN_PATH . 'system/controllers/class.files.php';

        $files = new Files();
        $file_id = $files->get_file_id($file_url);

        $return .= '?process_download=';
        $return .= $file_id;
        $return .= (!$force_download) ? '&pd_force=no' : '';

        return $return;
    }

    /**
     * Prints the response of `sqhrdm_get_download_link` helper function
     * Expects same parameters
     */
    function sqhrdm_download_link($file_url = null, $force_download = true) {
        echo sqhrdm_get_download_link($file_url, $force_download);
    }

}
