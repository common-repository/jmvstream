<?php

namespace Jmvstream\Includes;

if (!class_exists('JmvstreamShortcode')) {

    /**
     * Classe JmvstreamShortcode
     *
     * @package Includes
     */
    class JmvstreamShortcode
    {

        private $_options;
       
        /**
         * Class constructor
         */
        public function __construct()
        {
            $this->_options = get_option('jmvstream-general-settings');
            add_action('init', [$this, 'registerShortcode']);
            add_filter('the_content', 'do_shortcode');
        }
       
        /**
         * Function to generate a shortcode for video
         *
         * @param string $slug Slug of the video
         *
         * @return string $shortcode Shortcode to be inserted in the post
         */
        public function generateShortcode($slug)
        {
            $defaultWidth = isset($this->_options['video-default-width']) ? $this->_options['video-default-width'] : '640';
            $defaultHeight = isset($this->_options['video-default-height']) ? $this->_options['video-default-height'] : '360';
            $defaultAlign = isset($this->_options['video-default-align']) ? $this->_options['video-default-align'] : 'center';            
            $shortcode = "[jmvstream video=\"$slug\" width=\"$defaultWidth\" height=\"$defaultHeight\" align=\"$defaultAlign\"]";
            return $shortcode;
        }

        /**
         * Function to register the shortcode hook in wordpress
         *
         * @return void
         */
        public function registerShortcode()
        {
            add_shortcode('jmvstream', [$this, 'renderShortcode']);
        }

        /**
         * Function to render the shortcode with player of video in website
         *
         * @param array $atts Paramerters of the shortcode
         *
         * @return string $html HTML code to be inserted in the post
         */
        public function renderShortcode($atts)
        {
            try{
                extract($atts);
                $pluginVideo = $this->getVideoBySlug($video);
                error_log(print_r($pluginVideo, true));


                if (strpos($width, 'px') !== false) {
                    $width = str_replace('px', '', $width);
                }

                if (strpos($height, 'px') !== false) {
                    $height = str_replace('px', '', $height);
                }

                $width = isset($width) ? $width : $this->_options['video-default-width'];
                $height = isset($height) ? $height : $this->_options['video-default-height'];
                $align = isset($align) ? $align : $this->_options['video-default-align'];

                $width = ($width != '') ? $width . 'px' : '640px';
                $height = ($height != '') ? $height . 'px' : '360px';
                
                $title = $pluginVideo->title;
                $player = $pluginVideo->player;

                $iframe = $this->generateIframe($player, $title, $width, $height);
                
                $html = '<div class="jmvstream__iframe-video-container" style="display: flex; justify-content: ' . $align . ';">' . $iframe . '</div>';
                return $html;
                        
            } catch (\Exception $e) {
                error_log("Error in shortcode jmvstream: " . $e->getMessage());
            }
        }

        public function getVideoBySlug($slug)
        {
            global $wpdb;
            $table_videos = $wpdb->prefix . 'jmvstream_plugin_videos';
            $query = $wpdb->prepare("SELECT * FROM $table_videos WHERE slug = %s", $slug);
            $video = $wpdb->get_row($query);
            return $video;
        }


        /**
         * Function to generate the iframe of the video
         *
         * @param string $player URL of the player
         * @param string $title  Title of the video
         * @param string $width  Width of the video
         * @param string $height Height of the video
         *
         * @return string $iframe HTML code of the iframe
         */
        public function generateIframe($player, $title, $width, $height)
        {
            $iframe = "<iframe title='$title' alt='$title' allowfullscreen allow='autoplay' frameBorder='0' style='width:$width; height:$height;' src='$player'></iframe>";
            return $iframe;
        }
    }
}