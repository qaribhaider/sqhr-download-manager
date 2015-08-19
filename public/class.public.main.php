<?php

/**
 * Plugin public modules loader and handler
 */
class SQHR_Download_Manager_Public {

    protected $version;

    public function __construct($version) {
        $this->version = $version;

        $this->load_dependencies();
    }

    /**
     * Load dependent modules
     */
    private function load_dependencies() {
        require_once SQHR_DM_PLUGIN_PATH . 'system/controllers/class.files.php';
        require_once SQHR_DM_PLUGIN_PATH . 'system/controllers/class.downloads.php';
    }

    /**
     * Processing file download
     * 
     * Callback function fired with wordpress action 
     */
    public function process_download() {
        if (!isset($_REQUEST['process_download']) && !strlen($_REQUEST['process_download'])) {
            return true;
        }

        $file_id = $_REQUEST['process_download'];
        $file = $this->get_file_by_id($file_id);
        $force_download = (isset($_REQUEST['pd_force']) && $_REQUEST['pd_force'] == 'no') ? false : true;

        if (!$file) {
            die("File not found. Please make sure the link to file is correct.");
        }

        $file_url = $file->file_url;

        $this->add_download_record($file_id);

        if (!headers_sent() && $force_download) {
            $this->force_download($file_url);
        } else {
            $this->not_force_download($file_url);
        }

        exit;
    }

    /**
     * Get file details using id
     * 
     * @param int $id File Id
     * @return object
     */
    private function get_file_by_id($id) {
        $files = new Files();
        return $files->get_by_id($id);
    }

    /**
     * Force download file 
     * 
     * @param url $file_url URL of file to download
     */
    private function force_download($file_url) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file_url));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        ob_clean();
        flush();
        readfile($file_url);
    }

    /**
     * Redirect to file link, file will be handled by browser
     * 
     * @param url $file_url URL of file
     */
    private function not_force_download($file_url) {
        echo '<meta http-equiv="refresh" content="1;url=' . $file_url . '" />';
    }

    /**
     * Add file download record
     * 
     * @param int $file_id File Id
     */
    private function add_download_record($file_id) {
        $downloads = new Downloads();
        $downloads->add($file_id);
    }

}
