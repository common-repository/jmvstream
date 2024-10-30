<?php

namespace Jmvstream\Includes\Admin;

use Jmvstream\Includes\Helpers\JmvstreamCryptHelper;

if (!class_exists('JmvstreamApiSettings')) {

    /**
     * Class to manage admin area of plugin
     *
     * @package Includes/Admin
     */
    class JmvstreamApiSettings
    {

        use JmvstreamCryptHelper;

        private $_options;
        private $_plugin_basename;
        private $_plugin_slug;
        private $_plugin_version;

        /**
         * Class constructor
         *
         * @param string $basename The name of a plugin.
         * @param string $slug     Slug of the plugin
         * @param string $version  Version of the plugin
         */
        public function __construct($basename, $slug, $version)
        {
            $this->_options = get_option('jmvstream-api-settings') ?? [];
            $this->_plugin_basename = $basename;
            $this->_plugin_slug = $slug;
            $this->_plugin_version = $version;

            add_action('admin_menu', array($this, 'add_plugin_page'));
            add_action('admin_init', array($this, 'page_init'));
            add_action('admin_init', array($this, 'jmvstream_redirect'));
            add_filter('pre_update_option_jmvstream-api-settings', array($this, 'sanitize'), 10, 2);
        }

        /**
         * Function to initialize and register fields admin pages
         *
         * @return void
         */
        public function page_init()
        {
            register_setting(
                "jmvstream-api-settings",
                'jmvstream-api-settings',
            );

            add_settings_section(
                'jmvstream-api-settings-section',
                __('Jmvstream Ondemand API settings', 'jmvstream'),
                null,
                "jmvstream-api-settings"
            );

            add_settings_field(
                'jmvstream-email',
                'Email',
                array($this, 'email_callback'),
                "jmvstream-api-settings",
                'jmvstream-api-settings-section'
            );

            add_settings_field(
                'jmvstream-password',
                'Password',
                array($this, 'password_callback'),
                "jmvstream-api-settings",
                'jmvstream-api-settings-section'
            );

            add_settings_field(
                'jmvstream-resource',
                'Resource',
                array($this, 'resource_callback'),
                "jmvstream-api-settings",
                'jmvstream-api-settings-section'
            );
        }

        public function jmvstream_activate()
        {
            add_option('jmvstream_do_activation_redirect', true);
        }

        public function jmvstream_redirect()
        {
            if (get_option('jmvstream_do_activation_redirect', false)) {
                delete_option('jmvstream_do_activation_redirect');
                exit(wp_redirect("admin.php?page=jmvstream-api-settings"));
            }
        }

        public function add_plugin_page()
        {
            add_menu_page(
                'Jmvstream',
                'Jmvstream',
                'manage_options',
                'jmvstream-dashboard',
                '',
                JMVSTREAM_PLUGIN_URL . '/src/includes/assets/img/jmvstream-icon.svg',
            );

            add_submenu_page(
                'jmvstream-dashboard',
                'Dashboard',
                'Dashboard',
                'manage_options',
                'jmvstream-dashboard',
                array($this, 'create_dashboard_page')
            );

            add_submenu_page(
                'jmvstream-dashboard',
                'Jmvstream',
                'Hub Videos',
                'manage_options',
                'jmvstream-hub-videos',
                array($this, 'create_hub_videos_page')
            );
        
            add_submenu_page(
                'jmvstream-dashboard',
                __('Jmvstream Ondemand API settings', 'jmvstream'),
                __('API settings', 'jmvstream'),
                'manage_options',
                "jmvstream-api-settings",
                array($this, 'create_api_settings_page')
            );

            add_submenu_page(
                'jmvstream-dashboard',
                __('General Settings', 'jmvstream'),
                __('General Settings', 'jmvstream'),
                'manage_options',
                "jmvstream-general-settings",
                array((new JmvstreamGeneralSettings), 'create_general_settings_page')
            );
        }

        public function create_admin_page()
        {
            include_once JMVSTREAM_PLUGIN_DIR . 'src/includes/admin/JmvstreamPluginVideosPage.php';
        }

        public function create_hub_videos_page()
        {
            include_once JMVSTREAM_PLUGIN_DIR . 'src/includes/admin/JmvstreamHubVideosPage.php';
        }

        public function create_dashboard_page()
        {
            include_once JMVSTREAM_PLUGIN_DIR . 'src/includes/admin/JmvstreamDashboardPage.php';
        }

        public function create_api_settings_page()
        {
?>
            <div class="wrap">
                <form method="post" action="options.php">
                    <?php
                    settings_fields("jmvstream-api-settings");
                    do_settings_sections("jmvstream-api-settings");
                    submit_button();
                    ?>
                </form>

            </div>
            <?php if (empty($this->_options) || (!$this->_options['jmvstream-email'] || !$this->_options['jmvstream-password'] || !$this->_options['jmvstream-resource'])) : ?>
                <div id="message" class="error notice is-dismissible">
                    <p>
                        <?php esc_html_e('To use the plugin, you must fill in all the fields. For a step-by-step guide on how to fill in the fields', 'jmvstream') ?>
                        <a href="admin.php?page=jmvstream-dashboard" target="__blank">
                            <?php esc_html_e('click here', 'jmvstream') ?>
                        </a>
                    </p>
                    <button type="button" class="notice-dismiss">
                        <span class="screen-reader-text">
                            <?php esc_html_e('Dismiss this notice.', 'jmvstream') ?>
                        </span>
                    </button>
                </div>
            <?php endif; ?>
        <?php
        }

        public function email_callback()
        {
            $value = isset($this->_options['jmvstream-email']) ? esc_attr($this->_options['jmvstream-email']) : '';
        ?>
            <input type="email" id="jmvstream-email" class="regular-text" name="jmvstream-api-settings[jmvstream-email]" value="<?php echo esc_attr($value); ?>" />
        <?php
        }

        public function password_callback()
        {
            $value = isset($this->_options['jmvstream-password']) ? esc_attr($this->_options['jmvstream-password']) : '';
        ?>
            <input type="password" id="jmvstream-password" class="regular-text" name="jmvstream-api-settings[jmvstream-password]" value="<?php echo esc_attr($value); ?>" />
        <?php
        }

        public function resource_callback()
        {
            $value = isset($this->_options['jmvstream-resource']) ? esc_attr($this->_options['jmvstream-resource']) : '';
        ?>
            <input type="text" id="jmvstream-resource" class="regular-text" name="jmvstream-api-settings[jmvstream-resource]" value="<?php echo esc_attr($value); ?>" />
<?php
        }

        public function sanitize($input)
        {

            $newInput = [];
            $oldPassword = get_option('jmvstream-api-settings')['jmvstream-password'] ?? null;
            $oldPassword = $oldPassword ? $this->decrypt($oldPassword) : null;
            $newPassword = $input['jmvstream-password'] ? $this->decrypt($input['jmvstream-password']) : null;

            $newInput['jmvstream-email'] = isset($input['jmvstream-email']) ? sanitize_text_field($input['jmvstream-email']) : null;
            $newInput['jmvstream-password'] = ($oldPassword === $newPassword) ? $this->encrypt($oldPassword) : $this->encrypt($input['jmvstream-password']);
            $newInput['jmvstream-resource'] = isset($input['jmvstream-resource']) ? sanitize_text_field($input['jmvstream-resource']) : null;

            if (get_transient('jmvstream_auth_token')) {
                delete_transient('jmvstream_auth_token');
            }

            return $newInput;
        }
    }
}
