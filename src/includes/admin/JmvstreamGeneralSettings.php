<?php

namespace Jmvstream\Includes\Admin;

if (!class_exists('JmvstreamGeneralSettings')) {

    /**
     * Class to manage admin area of plugin
     *
     * @package Includes/Admin
     */
    class JmvstreamGeneralSettings
    {

        private $_options;

        /**
         * Class constructor
         */
        public function __construct()
        {
            add_action('admin_menu', array($this, 'add_general_settings_page'));
            add_action('admin_init', array($this, 'page_init'));
            $this->_options = get_option('jmvstream-general-settings');
        }

        /**
         * Function to initialize and register fields admin pages
         * 
         * @return void
         */
        public function page_init()
        {
            register_setting(
                "jmvstream-general-settings",
                'jmvstream-general-settings',
                array($this, 'sanitize')
            );

            add_settings_section(
                'jmvstream-video-default-settings-section',
                __('Default video settings', 'jmvstream'),
                null,
                "jmvstream-general-settings"
            );

            add_settings_field(
                'video-default-width',
                __('Default width', 'jmvstream'),
                array($this, 'video_default_width_callback'),
                "jmvstream-general-settings",
                'jmvstream-video-default-settings-section'
            );

            add_settings_field(
                'video-default-height',
                __('Default height', 'jmvstream'),
                array($this, 'video_default_height_callback'),
                "jmvstream-general-settings",
                'jmvstream-video-default-settings-section'
            );

            add_settings_field(
                'video-default-align',
                __('Default align', 'jmvstream'),
                array($this, 'video_default_align_callback'),
                "jmvstream-general-settings",
                'jmvstream-video-default-settings-section'
            );
        }

        public function create_general_settings_page()
        {
?>
            <div class="wrap">
                <form method="post" action="options.php">
                    <?php
                    settings_fields("jmvstream-general-settings");
                    do_settings_sections("jmvstream-general-settings");
                    submit_button();
                    ?>
                </form>
            </div>
        <?php
        }

        public function video_default_width_callback()
        {
            $value = isset($this->_options['video-default-width']) ? esc_attr($this->_options['video-default-width']) : '640';
        ?>
            <input type="number" id="video-default-width" class="regular-text" name="jmvstream-general-settings[video-default-width]" value="<?php echo esc_attr($value); ?>" />
            <p class="description"><?php esc_html_e('Default videos width', 'jmvstream') ?> (px)</p>
        <?php
        }

        public function video_default_height_callback()
        {
            $value = isset($this->_options['video-default-height']) ? esc_attr($this->_options['video-default-height']) : '480';
        ?>
            <input type="number" id="video-default-height" class="regular-text" name="jmvstream-general-settings[video-default-height]" value="<?php echo esc_attr($value); ?>" />
            <p class="description"><?php esc_html_e('Default videos height', 'jmvstream') ?> (px)</p>
        <?php
        }

        public function video_default_align_callback()
        {
            $value = isset($this->_options['video-default-align']) ? esc_attr($this->_options['video-default-align']) : '';
        ?>
            <select name="jmvstream-general-settings[video-default-align]" id="video-default-align">

                <?php if ($value == 'center') : ?>
                    <option value="center" selected><?php esc_html_e('Center', 'jmvstream') ?></option>
                    <option value="left"><?php esc_html_e('Left', 'jmvstream') ?></option>
                    <option value="right"><?php esc_html_e('Right', 'jmvstream') ?></option>
                <?php elseif ($value == 'left') : ?>
                    <option value="center"><?php esc_html_e('Center', 'jmvstream') ?></option>
                    <option value="left" selected><?php esc_html_e('Left', 'jmvstream') ?></option>
                    <option value="right"><?php esc_html_e('Right', 'jmvstream') ?></option>
                <?php elseif ($value == 'right') : ?>
                    <option value="center"><?php esc_html_e('Center', 'jmvstream') ?></option>
                    <option value="left"><?php esc_html_e('Left', 'jmvstream') ?></option>
                    <option value="right" selected><?php esc_html_e('Right', 'jmvstream') ?></option>
                <?php else : ?>
                    <option value="center"><?php esc_html_e('Center', 'jmvstream') ?></option>
                    <option value="left"><?php esc_html_e('Left', 'jmvstream') ?></option>
                    <option value="right"><?php esc_html_e('Right', 'jmvstream') ?></option>
                <?php endif ?>

            </select>
            <p class="description"><?php esc_html_e('Default video align', 'jmvstream') ?></p>
<?php
        }

        public function sanitize($input)
        {
            $newInput = array();

            if (isset($input['video-default-width'])) {
                $newInput['video-default-width'] = sanitize_text_field($input['video-default-width']);
            }

            if (isset($input['video-default-height'])) {
                $newInput['video-default-height'] = sanitize_text_field($input['video-default-height']);
            }

            if (isset($input['video-default-align'])) {
                $newInput['video-default-align'] = sanitize_text_field($input['video-default-align']);
            }

            return $newInput;
        }
    }
}
