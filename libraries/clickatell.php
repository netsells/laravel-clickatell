<?php

class Clickatell
{
    private static $to = array();
    private static $message = false;

    public function to($number = false)
    {
        if (is_array($number)) {
            foreach($number as $n) {
                self::$to[] = $this->format_number($n);
            }
        } else {
            self::$to[] = $this->format_number($number);
        }

        return $this;
    }

    public function message($message)
    {
        self::$message = $message;

        return $this;
    }

    public function send()
    {
        // Check to see if recipients and a message have been specified
        if ( (count(self::$to) == 0) or (self::$message === false) ) {
            return 'Required attributes (to and message) have not been set.';
        }

        // Load the config
        include Bundle::path('clickatell') . 'config/clickatell.php';

        // Authentication Call
        $auth = file($clickatell['base_url'] . '/http/auth?api_id=' . $clickatell['api_id'] . '&user=' . $clickatell['user'] . '&password=' . $clickatell['password']);
        $auth_ex = explode(":", $auth[0]);

        if ($auth_ex[0] == "OK") {
            // Authentication was successful
            $uri = array(
                'session_id=' . trim($auth_ex[1]),
                'to=' . implode(",", self::$to),
                'text=' . str_replace(' ', '+', self::$message)
            );

            $url = $clickatell['base_url'] . '/http/sendmsg?' . implode("&", $uri);

            $request = file($url);
            $request_ex = explode(":", $request[1]);

            if ($request_ex[0] == "ID") {
                return 'Message Sent #' . $request_ex[1];
            } else {
                return "Sending Message Failed";
            }
        } else {
            // Authentication failed
            return '<b>Authentication Failed</b> - ' . $auth_ex[1];
        }

    }

    private function format_number($number)
    {
        return trim($number, " +");
    }
}
