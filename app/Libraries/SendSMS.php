<?php

/**
 * Created by PhpStorm.
 * User: mark
 * Date: 4/4/17
 * Time: 7:09 PM
 */

namespace App\Libraries;

class SendSMS
{
    protected $user;
    protected $password;
    protected $senderid;
    protected $smsurl;

    public function __construct()
    {
        ########################################################
        # Login information for the SMS Gateway
        ########################################################
        $this->user = config('sms.username');
        $this->password = config('sms.password');
        $this->senderid = config('sms.sender');
        $this->smsurl = config('sms.url');
    }

    public function httpRequest($url)
    {
        ########################################################
        # Functions used to send the SMS message
        ########################################################
        $pattern = "/http...([0-9a-zA-Z-.]*).([0-9]*).(.*)/";
        preg_match($pattern, $url, $args);
        $in = "";
        $fp = fsockopen($args[1], 80, $errno, $errstr, 30);
        if (!$fp)
        {
            return ("$errstr ($errno)");
        }
        else
        {
            $args[3] = "C" . $args[3];
            $out = "GET /$args[3] HTTP/1.1\r\n";
            $out .= "Host: $args[1]:$args[2]\r\n";
            $out .= "User-agent: PARSHWA WEB SOLUTIONS\r\n";
            $out .= "Accept: */*\r\n";
            $out .= "Connection: Close\r\n\r\n";

            fwrite($fp, $out);
            while (!feof($fp))
            {
                $in .= fgets($fp, 128);
            }
        }
        fclose($fp);
        return ($in);
    }

    public function send($phone, $msg, $debug = false)
    {
        $url = 'username=' . $this->user;
        $url .= '&password=' . $this->password;
        $url .= '&sender=' . $this->senderid;
        $url .= '&to=' . urlencode($phone);
        $url .= '&message=' . urlencode($msg);
        $url .= '&priority=1';
        $url .= '&dnd=1';
        $url .= '&unicode=0';

        $urltouse = $this->smsurl . $url;
        if ($debug)
        {
            echo "Request: <br>$urltouse<br><br>";
        }

        //Open the URL to send the message
        $response = httpRequest($urltouse);
        if ($debug)
        {
            echo "Response: <br><pre>" .
                str_replace(array("<", ">"), array("&lt;", "&gt;"), $response) .
                "</pre><br>";
        }

        return ($response);
    }
}
