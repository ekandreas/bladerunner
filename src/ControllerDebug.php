<?php

namespace Bladerunner;

use Brain\Hierarchy\Hierarchy;

class ControllerDebug
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;

        $this->sanitize();
        $this->route();
    }

    /**
     * Sanitize
     *
     * Remove __env, app and obLevel arrays from data
     */
    protected function sanitize()
    {
        $this->data = array_diff_key($this->data['__data'], array_flip(['__env', 'app', 'obLevel']));
    }

    /**
     * Route
     *
     * Run method depending on type
     */
    public function route()
    {
        $this->controller();
        $this->data();
        $this->templates();
    }

    /**
     * Debug Dump
     *
     * Return var_dump of data
     */
    public function data()
    {
        echo "<pre><strong>Data:</strong>";
        echo print_r($this->data, true);
        echo "</pre>";
    }

    /**
     * Debug Controller
     *
     * Return list of keys from data
     */
    public function controller()
    {
        echo "<pre><strong>Controller:</strong><ul>";
        foreach ($this->data as $name => $item) {
            $item = (is_array($item) ? gettype($item) . '[' . count($item) . ']' : gettype($item));
            echo "<li>$" . $name . " &raquo; " . $item . "</li>";
        }
        echo '</ul></pre>';
    }

    /**
     * Debug Hierarchy
     *
     * Return list of hierarchy
     */
    public function templates()
    {
        global $wp_query;
        $templates = (new Hierarchy())->getTemplates($wp_query);
        echo '<pre><strong>Controller Template Hierarchy (first has highest prio):</strong><ul>';
        foreach ($templates as $template) {
            if ($template === 'index.php') {
                continue;
            }
            if ($template === 'index') {
                $template = 'index.php';
            }
            echo "<li>{$template}</li>";
        }
        echo '</ul></pre>';
    }
}
