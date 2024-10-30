<?php

namespace Jmvstream\Includes;

if (!class_exists('Jmvstream')) {
    /**
     * Class to manage scripts and styles of plugin
     *
     * @package Includes
     */
    class Jmvstream
    {
        public $translations;
        private $_options;
        public $defaultAlign;
        public $defaultWidth;
        public $defaultHeight;

        static function getUrl()
        {
            return admin_url('admin.php?page=jmvstream');
        }

        /**
         * Constructor of class
         */
        public function __construct()
        {
            $this->_options = get_option('jmvstream-general-settings');
            $this->defaultWidth = isset($this->_options['video-default-width']) ? $this->_options['video-default-width'] : '640';
            $this->defaultHeight = isset($this->_options['video-default-height']) ? $this->_options['video-default-height'] : '360';
            $this->defaultAlign = isset($this->_options['video-default-align']) ? $this->_options['video-default-align'] : 'center';

            add_action('init', array($this, 'enqueueAssets'));
            $this->translations =  [
                "jmvstream_videos" => __("Jmvstream Videos", "jmvstream"),
                "title" => __("Title", "jmvstream"),
                "edit" => __("Edit", "jmvstream"),
                "description" => __("Description", "jmvstream"),
                "gallery" => __("Gallery", "jmvstream"),
                "date" => __("Date", "jmvstream"),
                "no_videos_found" => __("No videos found", "jmvstream"),
                "all_galleries" => __("All Galleries", "jmvstream"),
                "all_dates" => __("All Dates", "jmvstream"),
                "of" => __("of", "jmvstream"),
                "checking_videos" => __("Checking videos... Please wait", "jmvstream"),
                "dismiss_notice" => __("Dismiss this notice", "jmvstream"),
                "uploaded_at" => __("Uploaded at", "jmvstream"),
                "updated_at" => __("Updated at", "jmvstream"),
                "video_player" => __("Video Player", "jmvstream"),
                "video_shortcode" => __("Video Shortcode", "jmvstream"),
                "video_duration" => __("Video Duration", "jmvstream"),
                "update" => __("Update", "jmvstream"),
                "gutenberg_label" => __("To embed a video, paste the shortcode or player link below", "jmvstream"),
                "gutenberg_error" => __("Error:: Invalid Link ou shortcode", "jmvstream"),
                "add_video" => __("Add Video", "jmvstream"),
                "update_videos" => __("Update Videos", "jmvstream"),
                "plan_upgrade" => __("Plan Upgrade", "jmvstream"),
                "plan_upgrade_url" => __("https://jmvstream.com/en/video-hosting-platform/#hosting-video-plans-pricing", "jmvstream"),
                "filter_videos" => __("Filter Videos", "jmvstream"),
                "filter_by_gallery" => __("Filter by Gallery", "jmvstream"),
                "filter_by_date" => __("Filter by Date", "jmvstream"),
                "search" => __("Search...", "jmvstream"),
                "list_videos" => __("List Videos", "jmvstream"),
                "video" => __("Video", "jmvstream"),
                "gallery" => __("Gallery", "jmvstream"),
                "date" => __("Date", "jmvstream"),
                "current_page" => __("Current Page", "jmvstream"),
                "unavailable" => __("Unavailable", "jmvstream"),
                "videos_from_jmvstream" => __(" videos from Jmvstream", "jmvstream"),
                "video_added_to_plugin" => __("Video added to plugin", "jmvstream"),
                "video_added" => __("Video added", "jmvstream"),
                "add_to_page" => __("Add to page", "jmvstream"),
                "initial_date" => __("Initial Date", "jmvstream"),
                "end_date" => __("End Date", "jmvstream"),
            ];
        }

        /**
         * Function to enqueue assets of plugin
         *
         * @return void
         */
        public function enqueueAssets()
        {
            wp_enqueue_script('jmvstream-plugin-videos-manager', plugin_dir_url(__DIR__) . '/includes/assets/js/jmvstreamPluginVideosManager.js', array('wp-i18n', 'jquery'));
            wp_localize_script(
                'jmvstream-plugin-videos-manager',
                'jmvstream',
                array(
                    'admin_url' => admin_url('admin-ajax.php'),
                    'translations' => $this->translations,
                    'shortcode' => [
                        'width' => $this->defaultWidth,
                        'height' => $this->defaultHeight,
                        'align' => $this->defaultAlign,
                    ]
                )
            );

            wp_enqueue_script('jmvstream-hub-video-manager', plugin_dir_url(__DIR__) . '/includes/assets/js/jmvstreamHubVideosManager.js', array('wp-i18n', 'jquery'));
            wp_enqueue_script('jmvstream-gutenberg-block', plugin_dir_url(__DIR__) . 'includes/assets/js/jmvstreamGutenbergBlock.js', array('wp-i18n', 'wp-blocks', 'wp-editor'), true);
            wp_enqueue_script('jmvstream-media-modal', plugin_dir_url(__DIR__) . 'includes/assets/js/jmvstreamGutenbergMediaModal.js', array('wp-i18n', 'jquery'), true);

            wp_enqueue_style('jmvstream-list-videos', plugin_dir_url(__DIR__) . '/includes/assets/css/jmvstreamListVideos.css');
            wp_enqueue_style('jmvstream-media-modal', plugin_dir_url(__DIR__) . '/includes/assets/css/jmvstreamMediaModal.css');
            wp_enqueue_style('jmvstream-preview-video-modal', plugin_dir_url(__DIR__) . '/includes/assets/css/jmvstreamPreviewVideoModal.css');
            wp_enqueue_style('jmvstream-spinner-loading', plugin_dir_url(__DIR__) . '/includes/assets/css/jmvstreamSpinnerLoading.css');
            wp_enqueue_style('jmvstream-dashboard', plugin_dir_url(__DIR__) . '/includes/assets/css/jmvstreamDashboard.css');

            wp_set_script_translations('jmvstream-hub-video-manager', 'jmvstream', plugin_dir_url(__DIR__) . '/languages');
            wp_set_script_translations('jmvstream-plugin-videos-manager', 'jmvstream', plugin_dir_url(__DIR__) . '/languages');
            wp_set_script_translations('jmvstream-gutenberg-block', 'jmvstream', plugin_dir_url(__DIR__) . '/languages');
            wp_set_script_translations('jmvstream-media-modal', 'jmvstream', plugin_dir_url(__DIR__) . '/languages');
        }

        function mediaButtonScript()
        {
            if (!current_user_can('upload_files')) return;
?>
            <script>
                jQuery(document).on('click', '#custom-browse-button', function(e) {
                    e.preventDefault();
                    window.location = '<?php echo esc_url(self::getUrl()); ?>';
                });
            </script>
<?php
        }
    }
}
