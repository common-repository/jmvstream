<?php

namespace Jmvstream\Includes\Db;

use Exception;

if (!class_exists('JmvstreamTables')) {

    /**
     * Class JmvstreamTables
     *
     * @package Includes/Db
     */
    class JmvstreamTables
    {
        /**
         * Function to create the table for the videos
         *
         * @return void
         */
        function __construct()
        {
            add_action('init', array($this, 'jmvstreamCreateTables'));
            register_activation_hook(__FILE__, array($this, 'activate'));
        }

        /**
         * Function to create the table for the videos
         *
         * @return void
         */
        function activate()
        {
            $this->jmvstreamCreateTables();
            flush_rewrite_rules();
        }

        /**
         * Function to create queries to create database
         *
         * @return void
         */
        function jmvstreamCreateTables()
        {
            try{
                global $wpdb;
                $charset_collate = $wpdb->get_charset_collate();
    
                $table_videos = $wpdb->prefix . 'jmvstream_plugin_videos';
                $wpdb->query(
                    "CREATE TABLE IF NOT EXISTS $table_videos (
                    hash_video varchar(100) NOT NULL,
                    slug varchar(100) NOT NULL,
                    title varchar(100) NOT NULL,
                    player varchar(600) NOT NULL,
                    PRIMARY KEY  (hash_video),
                    UNIQUE KEY hash_video (hash_video),
                    UNIQUE KEY slug (slug)
                    ) $charset_collate;"
                );

            }catch(Exception $e){
                error_log("Error creating tables: " . $e->getMessage());
            }
        }
    }

  

}
