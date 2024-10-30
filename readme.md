# Jmvstream

Contributors: jmvstream
Tags: jmv, jmvstream, video, video hosting
Requires at least: 5.0
Tested up to: 6.5
Requires PHP: 7
Stable tag: 1.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

## Description

The Jmvstream plugin is for our clients that use Wordpress to manage and insert their videos in their pages or posts in a practical and easy way.

The plugin creates the Shortcode of your video so you can use it in your content, it also has integration with the **Gutenberg** editor (Wordpress Default) and the **Elementor** page builder to make the use of our service even easier.

To learn more about our Video Hosting plans please visit: https://jmvstream.com/en/video-hosting-platform/#hosting-video-plans-pricing

## Here are the steps you need to follow to use the plugin:

#### To use the plugin you need to configure it with the API data of your Jmvstream Ondemand plan.

- To configure the API, go to ["API Settings"](<?php echo admin_url('admin.php?page=jmvstream-api-settings');?>)

- Fill in the fields with your account information.

- To get your account information, go to your [HUB](https://hub.jmvtechnology.com/#/home).

- In your HUB, access your Ondemand plan and on the Settings page

  . You will find the information you need:

  - **API Key**
  - **Authorization**
  - **API URL**

- After filling the fields here in the plugin with this information. Click **"Save "**.

- To add your videos to the plugin, go to the [Video Page](<?php echo admin_url('admin.php?page=jmvstream');?>).

- Click **"Update Videos "** and the plugin will automatically bring your videos from the HUB into Wordpress.

- To add some video to the page or plugin, just copy the **Shortcode** of the video and paste it to the page or post and the video player will be automatically added.

- From the Shortcode you can also set the height, width and alignment of the video. To set a default for your videos, go to [General Settings](<?php echo admin_url('admin.php?page=jmvstream-general-settings');?>)

- The plugin also integrates with the Gutenberg editor and the Elementor page builder. So you can add your videos directly from them.

## 3rd Party Services
This plugin makes use of the JMVStream service, provided by JMV Technology to manage videos. The plugin makes requests to the JMVStream API in various circumstances, including authentication and video folder retrieval.

For more information about JMVStream and its terms of use and privacy policies, please visit the following links:

- [JMVStream](https://jmvstream.com/)
- [JMV Technology](https://jmvtechnology.com/)
- [JMV Technology Terms of Use](https://jmvtechnology.com/en/terms)
- [JMV Technology Privacy Policy](https://jmvtechnology.com/en/privacy-policy/)
- [JMVStream API Documentation](https://jmvstream.com/en/developer/)