<?php

namespace Jmvstream\Includes\Helpers;

if (!class_exists('JmvstreamNameConverterHelper')) {
    /**
     * Helper to normalize names
     *
     * @package Includes/Helpers
     */
    trait JmvstreamNameConverterHelper
    {
        /**
         * Remove extension of files from name
         *
         * @param string $string Name to remove extension
         *
         * @return string
         */
        public function removeExtension($string)
        {
            $filemane = preg_replace('/\\.[^.\\s]{3,4}$/', '', $string);
            return $filemane;
        }
    }
}