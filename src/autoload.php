<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

spl_autoload_register('jmvstream_autoload');

function jmvstream_autoload($class)
{

    try {

        if (false === strpos($class, 'Jmvstream\\Includes')) {
            return;
        }

        $class = str_replace('\\', '/', $class);
        $class = str_replace('Jmvstream/', '', $class);
        $parts = explode("/", $class);
        $file = array_pop($parts);
        $class = str_replace($file, '', $class);
        $class = strtolower($class);

        $filePath = plugin_dir_path(__FILE__) . $class . $file . '.php';

        // If the file exists in the specified path, then include it.
        if (file_exists($filePath)) {
            require_once $filePath;
        } else {
            wp_die(
                esc_html("The file attempting to be loaded at $filePath does not exist.")
            );
        }
    } catch (Exception $e) {
        wp_die(
            error_log("Error autoloading class $class: " . $e->getMessage()),
            esc_html("The file attempting to be loaded at $filePath does not exist.")
        );
    }
}
