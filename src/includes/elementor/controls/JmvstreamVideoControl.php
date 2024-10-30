<?php

namespace Jmvstream\Includes\Elementor\Controls;

/**
 * JMV Stream Video control for elementor
 * 
 * A control for displaying a modal to add videos from Jmvstream Ondemand
 */
class JmvstreamVideoControl extends \Elementor\Base_Data_Control
{

    public function get_type()
    {
        return 'jmvstream_add_video_shortcode';
    }

    public function enqueue()
    {

        $defaultOptions = get_option('jmvstream-general-settings');
        $defaultWidth = isset($defaultOptions['video-default-width']) ? $defaultOptions['video-default-width'] : '640';
        $defaultHeight = isset($defaultOptions['video-default-height']) ? $defaultOptions['video-default-height'] : '360';
        $defaultAlign = isset($defaultOptions['video-default-align']) ? $defaultOptions['video-default-align'] : 'center';

        // Enqueue scripts
        wp_register_script('jmvstream-plugin-videos-manager', JMVSTREAM_PLUGIN_URL . '/src/includes/assets/js/jmvstreamPluginVideosManager.js', ['jquery']);
        wp_register_script('jmvstream-hub-video-manager', JMVSTREAM_PLUGIN_URL . '/src/includes/assets/js/jmvstreamHubVideosManager.js', ['jquery']);
        wp_register_script('jmvstream-video-control', JMVSTREAM_PLUGIN_URL . '/src/includes/assets/js/jmvstreamElementorControl.js', ['jquery']);
        wp_localize_script(
            'jmvstream-plugin-videos-manager',
            'jmvstream',
            array(
                'admin_url' => admin_url('admin-ajax.php'),
                'translations' =>  [
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
                    "video_added" => __("Video added", "jmvstream"),
                    "video_added_to_plugin" => __("Video added to plugin", "jmvstream"),
                    "add_to_page" => __("Add to page", "jmvstream"),
                    "initial_date" => __("Initial Date", "jmvstream"),
                    "end_date" => __("End Date", "jmvstream"),
                    "add_videos" => __("Add videos", "jmvstream"),
                ],
                'shortcode' => [
                    'width' => $defaultWidth,
                    'height' => $defaultHeight,
                    'align' => $defaultAlign,
                ]
            )
        );

        wp_enqueue_script('jmvstream-video-control');
        wp_enqueue_script('jmvstream-hub-video-manager');
        wp_enqueue_script('jmvstream-plugin-videos-manager');

        // Enqueue styles
        wp_register_style('jmvstream-elementor-addon', JMVSTREAM_PLUGIN_URL . '/src/includes/assets/css/jmvstreamElementorAddon.css');
        wp_enqueue_style('jmvstream-elementor-addon');
    }

    public function get_default_settings()
    {
        return [
            'label_block' => true,
            'button_type' => 'default',
            'text' => esc_html__('Select the video', 'jmvstream'),
            'label' => esc_html__('Select the video', 'jmvstream'),
            'show_label' => false,
        ];
    }

    public function content_template()
    {
        $control_uid = esc_attr($this->get_control_uid());
?>

        <div class="elementor-control-field">
            <# if (data.label) {#>
                <label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-title">{{{ data.label }}}</label>
            <# } #>

            <div class="elementor-control-input-wrapper">
                <button id="<?php echo esc_attr($control_uid); ?>" onclick="openModal('<?php echo esc_attr($control_uid); ?>')" class="elementor-button elementor-button-default jmvstream__open-modal-videos">{{ data.text }}</button>
            </div>
        </div>

        <?php
    }
}
