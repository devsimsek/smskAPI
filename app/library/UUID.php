<?php
/**
 * smskSoft UUID Library
 * Copyright smskSoft, mtnsmsk, devsimsek, Metin Şimşek.
 * @package     smskSoft-phpLibs
 * @subpackage  UUID
 * @file        UUID.php
 * @version     v1.0
 * @author      devsimsek
 * @copyright   Copyright (c) 2021, smskSoft, mtnsmsk
 * @license     https://opensource.org/licenses/MIT	MIT License
 * @link        https://www.php.net/manual/tr/function.uniqid.php
 * @since       Version 1.0
 * @filesource
 */
if (!class_exists("UUID")) {
    class UUID
    {
        // Always At The Top
        public function register(): array
        {
            // Library Configuration For phpRest
            $class_name = "UUID";
            $require_arguments = false;

            return array(
                "class_name" => $class_name,
                "require_arguments" => $require_arguments
            );
        }

        /**
         *
         * Generate
         *
         * Returns v4 36 character UUID
         *
         * @return string
         * @throws Exception
         */
        public static function generate(): string
        {
            return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

                // 32 bits for "time_low"
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),

                // 16 bits for "time_mid"
                mt_rand(0, 0xffff),

                // 16 bits for "time_hi_and_version",
                // four most significant bits holds version number 4
                mt_rand(0, 0x0fff) | 0x4000,

                // 16 bits, 8 bits for "clk_seq_hi_res",
                // 8 bits for "clk_seq_low",
                // two most significant bits holds zero and one for variant DCE1.1
                mt_rand(0, 0x3fff) | 0x8000,

                // 48 bits for "node"
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
            );
        }

        /**
         *
         * Generate v3
         *
         * Returns v3 UUID
         *
         * @param string $namespace
         * @param string $name
         * @return false|string
         *
         * @source: https://www.php.net/manual/tr/function.uniqid.php
         */
        public static function genv3(string $namespace, string $name)
        {
            if (!self::is_valid($namespace)) return false;

            // Get hexadecimal components of namespace
            $nhex = str_replace(array('-', '{', '}'), '', $namespace);

            // Binary Value
            $nstr = '';

            // Convert Namespace UUID to bits
            for ($i = 0; $i < strlen($nhex); $i += 2) {
                $nstr .= chr(hexdec($nhex[$i] . $nhex[$i + 1]));
            }

            // Calculate hash value
            $hash = md5($nstr . $name);

            return sprintf('%08s-%04s-%04x-%04x-%12s',

                // 32 bits for "time_low"
                substr($hash, 0, 8),

                // 16 bits for "time_mid"
                substr($hash, 8, 4),

                // 16 bits for "time_hi_and_version",
                // four most significant bits holds version number 3
                (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x3000,

                // 16 bits, 8 bits for "clk_seq_hi_res",
                // 8 bits for "clk_seq_low",
                // two most significant bits holds zero and one for variant DCE1.1
                (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,

                // 48 bits for "node"
                substr($hash, 20, 12)
            );
        }

        /**
         *
         * Generate v5
         *
         * Returns v5 UUID
         *
         * @param $namespace
         * @param $name
         * @return false|string
         *
         * @source: https://www.php.net/manual/tr/function.uniqid.php
         */
        public static function genv5($namespace, $name)
        {
            if (!self::is_valid($namespace)) return false;

            // Get hexadecimal components of namespace
            $nhex = str_replace(array('-', '{', '}'), '', $namespace);

            // Binary Value
            $nstr = '';

            // Convert Namespace UUID to bits
            for ($i = 0; $i < strlen($nhex); $i += 2) {
                $nstr .= chr(hexdec($nhex[$i] . $nhex[$i + 1]));
            }

            // Calculate hash value
            $hash = sha1($nstr . $name);

            return sprintf('%08s-%04s-%04x-%04x-%12s',

                // 32 bits for "time_low"
                substr($hash, 0, 8),

                // 16 bits for "time_mid"
                substr($hash, 8, 4),

                // 16 bits for "time_hi_and_version",
                // four most significant bits holds version number 5
                (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000,

                // 16 bits, 8 bits for "clk_seq_hi_res",
                // 8 bits for "clk_seq_low",
                // two most significant bits holds zero and one for variant DCE1.1
                (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,

                // 48 bits for "node"
                substr($hash, 20, 12)
            );
        }

        /**
         *
         * Is Valid
         *
         * Checks The Given String
         *
         * @param string $uuid
         * @return bool
         */
        public static function is_valid(string $uuid): bool
        {
            return preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?' .
                    '[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) === 1;
        }
    }
}