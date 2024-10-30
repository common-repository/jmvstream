<?php

namespace Jmvstream\Includes\Helpers;

if (!class_exists('JmvstreamSlugConverterHelper')) {
    
    /**
     * Class JmvstreamSlugConverterHelper
     *
     * @package Includes/Helpers
     */
    trait JmvstreamSlugConverterHelper
    {
        /**
         * Function to generate, used in shortcodes
         *
         * @param string $name      The name of the video
         * @param string $createdAt The upload date of the video
         *
         * @return string The slug of the video
         */
        public function toSlug($name, $createdAt)
        {
            $name = preg_replace('/[\t\n]/', ' ', $name);
            $name = preg_replace('/\s{2,}/', ' ', $name);
            $list = array(
                'Š' => 'S',
                'š' => 's',
                'Đ' => 'Dj',
                'đ' => 'dj',
                'Ž' => 'Z',
                'ž' => 'z',
                'Č' => 'C',
                'č' => 'c',
                'Ć' => 'C',
                'ć' => 'c',
                'À' => 'A',
                'Á' => 'A',
                'Â' => 'A',
                'Ã' => 'A',
                'Ä' => 'A',
                'Å' => 'A',
                'Æ' => 'A',
                'Ç' => 'C',
                'È' => 'E',
                'É' => 'E',
                'Ê' => 'E',
                'Ë' => 'E',
                'Ì' => 'I',
                'Í' => 'I',
                'Î' => 'I',
                'Ï' => 'I',
                'Ñ' => 'N',
                'Ò' => 'O',
                'Ó' => 'O',
                'Ô' => 'O',
                'Õ' => 'O',
                'Ö' => 'O',
                'Ø' => 'O',
                'Ù' => 'U',
                'Ú' => 'U',
                'Û' => 'U',
                'Ü' => 'U',
                'Ý' => 'Y',
                'Þ' => 'B',
                'ß' => 'Ss',
                'à' => 'a',
                'á' => 'a',
                'â' => 'a',
                'ã' => 'a',
                'ä' => 'a',
                'å' => 'a',
                'æ' => 'a',
                'ç' => 'c',
                'è' => 'e',
                'é' => 'e',
                'ê' => 'e',
                'ë' => 'e',
                'ì' => 'i',
                'í' => 'i',
                'î' => 'i',
                'ï' => 'i',
                'ð' => 'o',
                'ñ' => 'n',
                'ò' => 'o',
                'ó' => 'o',
                'ô' => 'o',
                'õ' => 'o',
                'ö' => 'o',
                'ø' => 'o',
                'ù' => 'u',
                'ú' => 'u',
                'û' => 'u',
                'ý' => 'y',
                'ý' => 'y',
                'þ' => 'b',
                'ÿ' => 'y',
                'Ŕ' => 'R',
                'ŕ' => 'r',
                '/' => '-',
                ' ' => '-',
                '.' => '-',
                ',' => '-',
                '(' => '',
                ')' => '',
            );
        
            $name = strtr($name, $list);
            $name = preg_replace('/-{2,}/', '-', $name);
            $name = strtolower($name);

            $date = str_replace('-', '/', $createdAt);
            $date = str_replace(' ', '-', $createdAt);
            $slug = $name . '-' . $date;
            $slug = strtolower($slug);
        
            return $slug;
        }
    }
}