<?php

namespace Jmvstream\Includes\Elementor;

use Jmvstream\Includes\Elementor\Controls\JmvstreamVideoControl;
use Jmvstream\Includes\Elementor\Widgets\JmvstreamVideoWidget;

final class JmvstreamElementorAddon
{
    private static $_instance = null;

    const MINIMUM_ELEMENTOR_VERSION = '3.2.0';

    const MINIMUM_PHP_VERSION = '7.0';

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        add_action('elementor/init', [$this, 'init']);
    }

    public function init()
    {
        add_action('elementor/controls/register', [$this, 'register_jmvstream_video_control']);
        add_action('elementor/widgets/register', [$this, 'register_jmvstream_video_widget']);
    }

    function register_jmvstream_video_widget($widgets_manager)
    {
        require_once __DIR__ . '/widgets/JmvstreamVideoWidget.php';

        $widgets_manager->register(new JmvstreamVideoWidget());
    }

    function register_jmvstream_video_control($controls_manager)
    {
        require_once __DIR__ . '/controls/JmvstreamVideoControl.php';

        $controls_manager->register(new JmvstreamVideoControl());
    }
}

JmvstreamElementorAddon::instance();
