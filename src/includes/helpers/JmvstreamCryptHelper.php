<?php

namespace Jmvstream\Includes\Helpers;

use Exception;

if (!class_exists('JmvstreamCryptHelper')) {

    /**
     * Helper to encrypt and decrypt strings
     *
     * @package Includes/Helpers
     */
    trait JmvstreamCryptHelper
    {

        private $_key;
        private $_iv;

        /**
         * Encrypt a string
         *
         * @param string $string String to encrypt
         *
         * @return string
         */
        public function encrypt(string $string): string
        {
            try {
                $this->_key = hash('sha256', 'C#Y39%8zRnh4');
                $this->_iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-256-CBC'));
                $encrypted = openssl_encrypt($string, 'AES-256-CBC', $this->_key, 0, $this->_iv);
                // Armazene $this->_iv junto com $encrypted, por exemplo, concatenando-os
                return base64_encode($encrypted . ':::' . $this->_iv);
            } catch (Exception $e) {
                return $e->getMessage();
                error_log("Error JmvstreamCrypt ::: " . $e->getMessage());
            }
        }

        /**
         * Decrypt a string
         *
         * @param string $string String to decrypt
         *
         * @return string
         */
        public function decrypt(string $string): string
        {
            try {
                $this->_key = hash('sha256', 'C#Y39%8zRnh4');
                $decoded = base64_decode($string);
                // Verifique se o delimitador está presente na string decodificada
                if (strpos($decoded, ':::') === false) {
                    throw new Exception('Formato de string inválido para descriptografia.');
                }
                list($encrypted, $iv) = explode(':::', $decoded, 2);
                $this->_iv = $iv;
                $decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', $this->_key, 0, $this->_iv);
                return $decrypted;
            } catch (Exception $e) {
                return $e->getMessage();
                error_log("Error JmvstreamDecrypt ::: " . $e->getMessage());
            }
        }
    }
}
