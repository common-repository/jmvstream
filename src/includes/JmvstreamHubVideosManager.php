<?php

namespace Jmvstream\Includes;

use DateTime;
use Exception;
use Jmvstream\Includes\Helpers\JmvstreamCryptHelper as CryptHelper;
use Jmvstream\Includes\Helpers\JmvstreamNameConverterHelper as NameConverter;
use Jmvstream\Includes\Helpers\JmvstreamSlugConverterHelper as SlugConverter;

if (!class_exists('JmvstreamHubVideosManager')) {

    /**
     * Class to manage videos from JMV Ondemand
     *
     * @package Includes
     */
    class JmvstreamHubVideosManager
    {
        use CryptHelper, SlugConverter, NameConverter;

        private $_tablePluginVideos;
        private $_tableHubVideos;
        private $_tableHubGallery;
        private $_apiSettings;
        private $_defaultSettings;

        /**
         * Constructor
         */
        public function __construct()
        {

            global $wpdb;
            $this->_apiSettings = get_option('jmvstream-api-settings');
            $this->_defaultSettings = get_option('jmvstream-general-settings');
            $this->_tablePluginVideos = $wpdb->prefix . 'jmvstream_plugin_videos';

            add_action('wp_ajax_getHubVideos', array($this, 'getHubVideos'));
            add_action('wp_ajax_getHubGalleries', array($this, 'getHubGalleries'));
            add_action('wp_ajax_addVideoToPlugin', array($this, 'addVideoToPlugin'));
            add_action('wp_ajax_updateVideoInPlugin', array($this, 'updateVideoInPlugin'));
        }

        /**
         * Get Authorization JWT token
         *
         * @return string
         */
        private function _getAuthToken()
        {

            try {

                if (get_transient('jmvstream_auth_token')) {
                    return get_transient('jmvstream_auth_token');
                }

                $url = 'https://api.jmvstream.com/v1/authenticate';
                $credentials = [
                    'email' => $this->_apiSettings['jmvstream-email'],
                    'password' => $this->decrypt($this->_apiSettings['jmvstream-password']),
                    'resource' => $this->_apiSettings['jmvstream-resource']
                ];
               
                $response = wp_remote_post(
                    $url,
                    array(
                        'headers' => array(
                            'Content-Type' => 'application/json',
                            'Accept' => 'application/json',
                        ),
                        'body' => json_encode($credentials),
                        'method' => 'POST',
                        'data_format' => 'body'
                    )
                );

                if ($response instanceof \WP_Error) {
                    throw new Exception($response->get_error_message());
                }

                // Check for error if response code is not in 200 range
                if ($response['response']['code'] < 200 || $response['response']['code'] > 299) {
                    $error = [
                        'error' => $response['response']['code'],
                        'message' => $response['response']['message']
                    ];
                    return $error;
                }

                $body = json_decode($response['body']);

                if (isset($body->message) && $body->message == 'not allowed') {
                    throw new Exception('Not allowed');
                }

                $token = json_decode($response['body'])->token;
                set_transient('jmvstream_auth_token', $token, 60 * 60 * 4);

                return $token;
            } catch (Exception $e) {
                error_log("Error getting auth token: " . $e->getMessage());
            }
        }

        public function getHubVideos()
        {
            try {

                $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $page = absint($page) ?? 1;

                $galery = filter_input(INPUT_POST, 'gallery', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $filterGallery = isset($galery) ? sanitize_text_field($galery) : false;

                $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $filterByTitle = sanitize_text_field($title) ?? "";

                $initialDate = filter_input(INPUT_POST, 'initialDate', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $filterInitialDate = sanitize_text_field($initialDate) ?? "";

                $endDate = filter_input(INPUT_POST, 'endDate', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $filterEndDate = sanitize_text_field($endDate) ?? "";

                error_log("FILTER INITIAL DATE ::: " . $filterInitialDate);
                error_log("FILTER FINAL DATE ::: " . $filterEndDate);

                $order = filter_input(INPUT_POST, 'orderBy', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $orderBy = isset($order) ? sanitize_text_field($order) : 'created_date';

                $sort = filter_input(INPUT_POST, 'sort', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $sort = isset($sort) ? sanitize_text_field($sort) : 'desc';

                $token = $this->_getAuthToken();

                if (!$filterGallery) {
                    $url = "https://api.jmvstream.com/v1/videos/application/?name={$filterByTitle}&initialDate={$filterInitialDate}&endDate={$filterEndDate}&orderBy={$orderBy}&sort={$sort}&page={$page}";
                    error_log("URL ::: " . $url);
                }

                if ($filterGallery) {
                    $url = " https://api.jmvstream.com/v1/videos/folder/{$filterGallery}/?name={$filterByTitle}&initialDate={$filterInitialDate}&endDate={$filterEndDate}&orderBy={$orderBy}&sort={$sort}&page={$page}";
                }


                $response = wp_remote_get(
                    $url,
                    [
                        'headers' => ['Authorization' => "Bearer {$token}"],
                        'timeout' => 60,
                    ]
                );

                if (is_wp_error($response)) {
                    throw new Exception($response->get_error_message());
                }

                if (!$filterGallery) {
                    $body = json_decode($response['body'], true);
                    $videos = $body['videos'] ?? null;
                }

                if ($filterGallery) {
                    $body = json_decode($response['body'], true);
                    $data = $body['data'][0] ?? null;
                    $videos = $data['videos'] ?? null;
                }

                if ($videos) {
                    for ($i = 0; $i < count($videos); $i++) {
                        $video = &$videos[$i];
                        $video['title'] = $this->removeExtension($video['name']);
                        $video['slug'] = $this->toSlug($this->removeExtension($video['name']), $video['created_date']);
                        $video['shortcode'] = (new JmvstreamShortcode)->generateShortcode($video['slug']);
                        $video['created_date'] = (new DateTime($video['created_date']))->format('d/m/Y');
                        $video['in_plugin'] = $this->videoExistsInPlugin($video['hash']);
                    }
                }
                
                $success = [
                    "videos" => $videos,
                    "lastPage" => $body['last_page'],
                ];

                wp_send_json_success($success);
            } catch (Exception $e) {
                error_log("Error getting hub videos: " . $e->getMessage());
                wp_send_json_error(['error' => $e->getCode()]);
            }
        }

        public function getHubGalleries()
        {
            $token = $this->_getAuthToken();

            $url = "https://api.jmvstream.com/v1/folders/";
            $response = wp_remote_get(
                $url,
                [
                    'headers' => ['Authorization' => "Bearer {$token}"],
                    'method'  => 'GET',
                ]
            );

            if ($response instanceof \WP_Error) {
                throw new Exception($response->get_error_message());
            }

            $body = json_decode($response['body']);
            $folders =  $this->getAllGalleries($body->folders);

            $return = wp_json_encode($folders);

            echo $return;
            wp_die();
        }

        public function getAllGalleries($folders, $parent = null)
        {

            $result = [];

            foreach ($folders as $folder) {
                $newFolder = [
                    'uuid' => $folder->uuid,
                    'name' => $folder->name,
                    'parent' => $parent
                ];

                if (isset($folder->folders) && count($folder->folders) > 0) {
                    $subFolders = $this->getAllGalleries($folder->folders, $folder->uuid);
                    $newFolder['folders'] = $subFolders;
                }

                $result[] = $newFolder;
            }

            return $result;
        }

        public function addVideoToPlugin()
        {
            try {

                $videoTitle = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $videoHash = filter_input(INPUT_POST, 'hash', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $videoSlug = filter_input(INPUT_POST, 'slug', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $videoPlayer = filter_input(INPUT_POST, 'player', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                if ($this->videoExistsInPlugin($videoHash)) {
                    $return = [
                        'error' => true,
                        'message' => 'Video already exists in plugin',
                    ];
                    echo $return;
                    wp_die();
                }

                global $wpdb;

                $videoData = [
                    'hash_video' => $videoHash,
                    'slug' => $videoSlug,
                    'title' => $videoTitle,
                    'player' => $videoPlayer,
                ];

                $wpdb->insert($this->_tablePluginVideos, $videoData);

                $return = [
                    'success' => true,
                    'message' => "Video saved successfully",
                ];
            } catch (\Exception $e) {
                $return = [
                    'error' => true,
                    'message' => "Error in addVideoToPlugin: {$e->getMessage()}",
                    'line' => $e->getLine(),
                ];
                echo $return;
                wp_die();
            }
        }

        public function videoExistsInPlugin($hash)
        {
            try {
                global $wpdb;

                $query = $wpdb->prepare("SELECT * FROM %s WHERE hash_video = %s", $this->_tablePluginVideos, $hash);
                $result = $wpdb->get_results($query);

                if (count($result) > 0) {
                    return true;
                }

                return false;
            } catch (\Exception $e) {
                return false;
            }
        }
    }
}
