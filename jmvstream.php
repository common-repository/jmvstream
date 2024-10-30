<?php

/**
 * Plugin Name: Jmvstream
 * Description: Manage, edit and add videos from the Jmvstream Ondemand Plan to your content using Wordpress
 * Version: 1.0.3
 * Text Domain: jmvstream
 * Domain Path: /src/languages/
 * Author:      Jmvstream
 * Author URI:  https://www.jmvstream.com
 * License:     GPL-2.0+
 */

use Jmvstream\Includes\Admin\JmvstreamApiSettings;
use Jmvstream\Includes\Db\JmvstreamTables;
use Jmvstream\Includes\Elementor\JmvstreamElementorAddon;
use Jmvstream\Includes\Jmvstream;
use Jmvstream\Includes\JmvstreamShortcode;
use Jmvstream\Includes\JmvstreamHubVideosManager;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!defined('JMVSTREAM_VERSION')) {
    define('JMVSTREAM_VERSION', '1.1.3');
}

if (!defined('JMVSTREAM_NAME')) {
    define('JMVSTREAM_NAME', 'Jmvstream');
}

if (!defined('JMVSTREAM_PLUGIN_SLUG')) {
    define('JMVSTREAM_PLUGIN_SLUG', 'jmvstream');
}

if (!defined('JMVSTREAM_BASENAME')) {
    define('JMVSTREAM_BASENAME', plugin_basename(__FILE__));
}

if (!defined('JMVSTREAM_PLUGIN_DIR')) {
    define('JMVSTREAM_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

if (!defined('JMVSTREAM_PLUGIN_URL')) {
    define('JMVSTREAM_PLUGIN_URL', plugin_dir_url(__FILE__));
}

require_once dirname(__FILE__) . '/src/autoload.php';

load_plugin_textdomain(JMVSTREAM_PLUGIN_SLUG, false, JMVSTREAM_PLUGIN_SLUG . '/src/languages/');

$shortcode = new JmvstreamShortcode();
$elementorAddon = new JmvstreamElementorAddon();

if (is_admin()) {

    $tables = new JmvstreamTables();
    $jmvstream = new Jmvstream();
    $hubVideosManager = new JmvstreamHubVideosManager();

    $jmvstream_admin = new JmvstreamApiSettings(
        JMVSTREAM_BASENAME,
        JMVSTREAM_PLUGIN_SLUG,
        JMVSTREAM_VERSION
    );

    register_activation_hook(__FILE__, [$jmvstream_admin, 'jmvstream_activate']);
}
