<?php
/**
 * Class Bencode
 *
 * Used to encode and decode Bencoded strings and torrent files.
 *
 * Written by Jack Ryan Davies (jrd15)
 */

namespace Bencode;

class Bencode
{
    /**
     * Encode an array, string or int into bencode format.
     * @param $data
     * @return void
     */
    static function encode($data, bool $announceError = false) {
        if ($announceError) {
            // We're replying with an error message.

            if (is_string($data)) {
                $data = array(
                    "failure reason" => $data
                );
            } else {
                $data = array(
                    "failure reason" => "Unknown error."
                );
            }
        }

        if (is_int($data)) {
            return "i{$data}e";
        }

        if (is_string($data)) {
            return strlen($data).":". $data;
        }

        if (is_array($data)) {
            if (count($data) === 0) {
                // Array is empty, so just return an empty list.
                return "le";
            }

            $is_dict = array_keys($data) !== range(0, count($data) - 1);

            if ($is_dict) {
                // Sort the array by the key.
                ksort($data, SORT_STRING);
                $result = "d";

                foreach ($data as $key => $value) {
                    $result .= self::encode($key).self::encode($value);
                }

                return $result . "e";
            } else {
                $result = "l";

                foreach ($data as $value) {
                    $result .= self::encode($value);
                }

                return $result."e";
            }
        }

        throw new Exception("Invalid data provided to encoder!");
    }

    /**
     * Decode a bencoded string into a PHP array.
     *
     * @param string $string The bencoded string to decode.
     * @return array The decoded data as a PHP array.
     */
    static function decode(String $string) {
        if (!$string) {
            return false; // Nothing provided.
        }

        $length = strlen($string);
        $pos = 0;

        return self::bdecodeInternal($string, $pos, $length);
    }

    /**
     * Internal function to recursively decode a bencoded string.
     *
     * @param string $string The bencoded string to decode.
     * @param int $pos The current position in the string.
     * @param int $length The length of the string.
     * @return mixed The decoded data.
     */
    private static function bdecodeInternal(
        string $string,
        int &$pos, // We use a pointer as this method will be called recursively and we re-use this variable.
        int $length
    ) {
        switch ($string[$pos]) {
            case 'i': // We're dealing with a integer value.
                $pos++;        // Increment the position in the $string variable.
                $num_str = ''; // The string we are building, that will consist of the integer value.

                // Loop through the $string variable until we reach the end or hit character 'e'.
                while ($pos < $length && $string[$pos] !== 'e') {
                    $num_str .= $string[$pos];
                    $pos++;
                }

                $pos++;
                return intval($num_str);

            case 'l': // We're dealing with a list. A list is an ordered collection of elements.
                $pos++;
                $list = array(); // The array list we are building.

                // Loop through the $string variable until we reach the end or hit character 'e'.
                while ($pos < $length && $string[$pos] !== 'e') {
                    // Call this function recursively and then add the result as a new key on the $list array.
                    $list[] = self::bdecodeInternal($string, $pos, $length);
                }

                $pos++;
                return $list;

            case 'd': // We're dealing with a dictionary. A dictionary is an unordered collection
                      // of key-value pairs.
                $pos++;
                $dict = array();

                while ($pos < $length && $string[$pos] !== 'e') {
                    $key    =  self::bdecodeInternal($string, $pos, $length);
                    $value  =  self::bdecodeInternal($string, $pos, $length);

                    /*if ($key !== "pieces") {
                        $dict[$key] =  $value;
                    }*/

                    $dict[$key] =  $value;
                }

                $pos++;
                return $dict;

            default: // We're dealing with a string.
                $num_str = '';

                while ($pos < $length && is_numeric($string[$pos])) {
                    $num_str .= $string[$pos];
                    $pos++;
                }

                $num = intval($num_str);
                $pos++; // Skip ':'
                $str  = substr($string, $pos, $num);
                $pos += $num;
                return $str;
        }
    }
}
?>