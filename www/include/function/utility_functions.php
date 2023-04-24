<?php
    /**
     * Returns the IP address of the client. This takes into consideration the use
     * of a proxy server.
     * @return string
     * @throws Exception
     */
    function getClientIp(): string {
        // These are the typical headers provided when using a HTTP proxy.
        $proxyHeaders = array(
            'X-Real-IP',
            'X-Forwarded-For',
            'X-Forwarded-Host'
        );

        foreach ($proxyHeaders as $header) {
            if ($ip = trim(getenv($header))) {
                if (!empty($ip)) {
                    if(filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                        // IP address is valid
                        return $ip;
                    } else {
                        throw new Exception("Invalid proxy IP address provided!");
                    }
                }
            }
        }

        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Returns a string of the users client user agent.
     * @return string
     */
    function getClientAgent(): string {
        if (isset($_SERVER['HTTP_USER_AGENT']) && !empty(trim($_SERVER['HTTP_USER_AGENT']))) {
            return trim($_SERVER['HTTP_USER_AGENT']);
        }

        return "Unknown";
    }
    
    /**
     * @param  array|string   $toSanitise Input to sanitise.
     * @param  int $stripTags Optional parameter (default is just use 'htmlspecialchars'). 1 = 'strip_tags', 2 = 'strip_tags(htmlspecialchars())'
     * @return array|string   Sanitised string/array.
     */
    function htmlSpecialClean($toSanitise, int $stripTags = 0) {
        if (is_array($toSanitise)) {
            // We're sanitising an array, so this funciton will be called recursively until the array has been fully cleaned.
            return array_map(function ($toSanitise) use ($stripTags) {
                return htmlSpecialClean($toSanitise, $stripTags);
            }, $toSanitise);
         } else {
            if ($stripTags) {
                if ($stripTags == 1) {
                    // "strip_tags" no longer supports passing a null value as an argument, which is an issue if
                    //  we're looping through every entry for a torrents details and we have a numeric value of 0.
                    //  PHP treats the int value 0 as null...
                    if ($toSanitise) {
                        return strip_tags($toSanitise);
                    }

                    if (is_numeric($toSanitise)) {
                        return intval($toSanitise);
                    }

                    return null;
                }

                if ($stripTags == 2) {
                    return strip_tags(htmlspecialchars($toSanitise));
                }
            }

            return htmlspecialchars($toSanitise);
        }
    }


    /**
     * Converts a unix timestamp into a human-readable format, in the form of how long ago
     * the time is.
     * @param int $timestamp
     * @return string
     */
    function timeAgo(int $timestamp) {
        $seconds = time() - $timestamp; // calculate the difference in seconds
        $minutes = round($seconds / 60);
        $hours = round($seconds / 3600);
        $days = round($seconds / 86400);
        $weeks = round($seconds / 604800);
        $months = round($seconds / 2629440);
        $years = round($seconds / 31553280);

        if ($seconds <= 60) {
            return 'just now';
        } else if ($minutes <= 60) {
            return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
        } else if ($hours <= 24) {
            return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
        } else if ($days <= 7) {
            return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
        } else if ($weeks <= 4) {
            return $weeks . ' week' . ($weeks > 1 ? 's' : '') . ' ago';
        } else if ($months <= 12) {
            return $months . ' month' . ($months > 1 ? 's' : '') . ' ago';
        } else {
            return $years . ' year' . ($years > 1 ? 's' : '') . ' ago';
        }
    }

    /**
     * Returns byte input in IEC format.
     * @param float $size Input bytes to convert.
     * @param int $precision Level of precision (default is 2DP).
     * @return String Size in IEC format.
     */
    function bytesFormat(float $size, int $precision = 2): string {
        if ($size <= 0) {
            return "0 B";
        }

        $base = log($size, 1024);
        $suffixes = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB');

        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
    }
?>
