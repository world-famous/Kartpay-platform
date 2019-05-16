<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Firewall;

class SucuriFirewall extends Firewall
{
    public static function blacklist($ipaddress, $force = true)
    {
        try
        {
            self::deblacklist($ipaddress);
        }
        catch (Exception $e)
        {

        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://waf.sucuri.net/api?v2',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                // this is the Sucuri CloudProxy API key for this website
                'k' => config('services.sucuri.key'),
                // this is the Sucuri CloudProxy API secret for this website
                's' => config('services.sucuri.secret'),
                // this is the Sucuri CloudProxy API action for this website
                'a' => 'blacklist_ip',
                'ip' => $ipaddress,
            )
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response, true);

        if ($data['status'])
        {
            Firewall::blacklist($ipaddress, $force);
            return $data;
        }
        else
        {
            throw new Exception($data['messages'][0], 400);
        }
    }

    public static function deblacklist($ipaddress)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://waf.sucuri.net/api?v2',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                // this is the Sucuri CloudProxy API key for this website
                'k' => config('services.sucuri.key'),
                // this is the Sucuri CloudProxy API secret for this website
                's' => config('services.sucuri.secret'),
                // this is the Sucuri CloudProxy API action for this website
                'a' => 'delete_blacklist_ip',
                'ip' => $ipaddress,
            )
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response, true);

        if ($data['status']) {
            return $data;
        } else {
            throw new Exception($data['messages'][0], 400);
        }
    }

    public static function whitelist($ipaddress, $force = true)
    {
        try
        {
            self::dewhitelist($ipaddress);
        }
        catch (Exception $e)
        {

        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://waf.sucuri.net/api?v2',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                // this is the Sucuri CloudProxy API key for this website
                'k' => config('services.sucuri.key'),
                // this is the Sucuri CloudProxy API secret for this website
                's' => config('services.sucuri.secret'),
                // this is the Sucuri CloudProxy API action for this website
                'a' => 'whitelist_ip',
                'ip' => $ipaddress,
            )
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response, true);

        if ($data['status'])
        {
            Firewall::whitelist($ipaddress, $force);
            return $data;
        }
        else
        {
            throw new Exception($data['messages'][0], 400);
        }
    }

    public static function dewhitelist($ipaddress)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://waf.sucuri.net/api?v2',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                // this is the Sucuri CloudProxy API key for this website
                'k' => config('services.sucuri.key'),
                // this is the Sucuri CloudProxy API secret for this website
                's' => config('services.sucuri.secret'),
                // this is the Sucuri CloudProxy API action for this website
                'a' => 'delete_whitelist_ip',
                'ip' => $ipaddress,
            )
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response, true);

        if ($data['status'])
        {
            return $data;
        }
        else
        {
            throw new Exception($data['messages'][0], 400);
        }
    }

    public static function remove($ipaddress)
    {
        $result = parent::remove($ipaddress);

        try
        {
            self::dewhitelist($ipaddress);
        }
        catch (Exception $e)
        {

        }

        try
        {
            self::deblacklist($ipaddress);
        }
        catch (Exception $e)
        {

        }
        return $result;
    }
}
