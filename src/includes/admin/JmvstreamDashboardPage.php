<?php

namespace Jmvstream\Includes\Admin;

?>

<div id="wpbody" class="jmvstream-dashboard">
    <div id="wpbody-content" class="jmvstream-dashboard-container">
        <div class="wrap jmvstream-dashboard-wrap">
            <h1 class="jmvstream-dashboard-title"><?php esc_html_e('Jmvstream Plugin', 'jmvstream') ?></h1>    
            <div class="jmvstream-dashboard-content">
                <h2 class="jmvstream-dashboard-subtitle"><?php esc_html_e('Below are the steps to use the plugin:', 'jmvstream') ?></h2>
                <h4 class="jmvstream-dashboard-subtitle"><?php esc_html_e('To use the plugin, you need to configure it with your Jmvstream Ondemand plan API data.', 'jmvstream') ?></h4>
                <ul class="jmvstream-steps">
					<li><?php esc_html_e('To configure the API, access the page', 'jmvstream'); ?> <a href="admin.php?page=jmvstream-api-settings" target="__blank">"<?php esc_html_e('API settings', 'jmvstream') ?>"</a> </li>
					<li><?php esc_html_e('Fill in the fields with your account information.', 'jmvstream'); ?></li>
					<li><?php esc_html_e('To get the ', 'jmvstream'); ?><b><?php esc_html_e('Resource', 'jmvstream'); ?></b><?php esc_html_e(' of your Ondemand plan, access your ', 'jmvstream'); ?> <a href="https://hub.jmvtechnology.com/#/home" target="__blank">HUB</a>.</li>
					<li><?php esc_html_e('In your HUB, access your Ondemand plan and on the ', 'jmvstream'); ?><b><?php esc_html_e('Settings', 'jmvstream'); ?></b><?php esc_html_e(' page, you will find the ', 'jmvstream'); ?><b><?php esc_html_e('Resource', 'jmvstream'); ?></b><?php esc_html_e(': ', 'jmvstream') ?>
						<ul style="margin-left: 30px;">
							<li> <b> <?php esc_html_e('Example: ', 'jmvstream') ?> 15x2y234-dd8z-4e58-6771-9x8by0zx4122</b></li>
						</ul>
					</li>
					<li><?php esc_html_e('After filling in the fields with this information. Click on ', 'jmvstream'); ?><b><?php esc_html_e('Save', 'jmvstream'); ?></b><?php esc_html_e('.', 'jmvstream') ?></li>
					<li><?php esc_html_e('On the ', 'jmvstream'); ?><b><?php esc_html_e('Hub Videos', 'jmvstream'); ?></b><?php esc_html_e(' page of the plugin, you will find the videos from your Ondemand plan.', 'jmvstream'); ?> <a href="admin.php?page=jmvstream" target="__blank"><?php esc_html_e('Videos page', 'jmvstream') ?></a>. </li>
					<li><?php esc_html_e('Click on ', 'jmvstream'); ?><b><?php esc_html_e('Add Video', 'jmvstream'); ?></b><?php esc_html_e(', to add the video to your site, so that you can use it on your pages or posts.', 'jmvstream'); ?></li>
					<li><?php esc_html_e('To add the video to the page or post, simply copy the video ', 'jmvstream'); ?><b><?php esc_html_e('Shortcode', 'jmvstream'); ?></b><?php esc_html_e(' and paste it into the page or post, and the video player will be automatically added.', 'jmvstream') ?></li>
					<li><?php esc_html_e('With the Shortcode, it is also possible to set the height (height), width (width), and alignment (align) of the video. To set a default for your videos, go to ', 'jmvstream') ?> <a target="__blank" href="admin.php?page=jmvstream-general-settings"><?php esc_html_e("General Settings", 'jmvstream') ?></a></li>
					<li><?php esc_html_e('The plugin also has integration with the ', 'jmvstream'); ?><b><?php esc_html_e('Gutenberg', 'jmvstream'); ?></b><?php esc_html_e(' editor and the ', 'jmvstream'); ?><b><?php esc_html_e('Elementor', 'jmvstream'); ?></b><?php esc_html_e(' page builder. So you can add your videos directly through them.', 'jmvstream') ?></li>
				</ul>
			</div>
		</div>
	</div>
</div>