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

?>