<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

register_uninstall_hook( __FILE__, 'jmvstream_plugin_uninstall' );

function jmvstream_plugin_uninstall() {
    //Remove cache transiente
    delete_transient('jmvstream_auth_token');

    delete_option('jmvstream-api-settings');
    delete_option('jmvstream-general-settings');

    global $wpdb;

    $table_plugin_videos = $wpdb->prefix . 'jmvstream_plugin_videos';

    $dropTablePluginVideos = "DROP TABLE IF EXISTS $table_plugin_videos";

    $wpdb->query($dropTablePluginVideos);

}
