<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Actions and filter loader class
 * 
 * This class is used for storing all actions and filters used in 
 * whole plugin and running them. Wordpress hooks require to be filtered 
 * through actions/filters methods, so this class filters them through 
 * that functions avoiding unneccessary calls each time
 */
class SQHR_Download_Manager_Loader {

    protected $actions;
    protected $filters;

    public function __construct() {
        $this->actions = array();
        $this->filters = array();
    }

    /**
     * Store an action to be run
     * 
     * @param string $hook Wordpress hook to be called
     * @param Instance $component Callback method holding class instance
     * @param Method $callback Callback method
     */
    public function add_action($hook, $component, $callback) {
        $this->actions = $this->add($this->actions, $hook, $component, $callback);
    }

    /**
     * Stores a filter to be run
     * 
     * @param string $hook Wordpress hook to be called
     * @param Instance $component Callback method holding class instance
     * @param Method $callback Callback method
     */
    public function add_filter($hook, $component, $callback) {
        $this->filters = $this->add($this->filters, $hook, $component, $callback);
    }

    /**
     * Convert actions / filters to be run to array format for storing 
     */
    private function add($hooks, $hook, $component, $callback) {
        $hooks[] = array(
            'hook' => $hook,
            'component' => $component,
            'callback' => $callback
        );

        return $hooks;
    }

    /**
     * Run all actions and filters stored
     */
    public function run() {
        foreach ($this->filters as $hook) {
            add_filter($hook['hook'], array($hook['component'], $hook['callback']));
        }

        foreach ($this->actions as $hook) {
            add_action($hook['hook'], array($hook['component'], $hook['callback']));
        }
    }

}
