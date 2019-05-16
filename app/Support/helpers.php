<?php
/**
 * Created by PhpStorm.
 * User: Dawood daudmalik06@gmail.com
 * Date: 31-Mar-17
 * Time: 18:41 PM
 */

if (!function_exists('getLiveEnv'))
{
    /**
     * get current env value.
     *
     * @param  string  $text
     * @return string
     */
    function getLiveEnv($key, $default = '')
    {
        $path     = base_path('.env');
        $contents = file($path);
        foreach ($contents as $content)
        {
            //if line contains provided key
            if (strstr($content, $key))
            {
                //non empty line
                list($k, $value) = explode('=', trim($content));
                //return the value of the key
                return $value;
            }
        }
        //return the default value
        return $default;
    }
}

if (!function_exists('setEnv'))
{
    /**
     * set env value
     *
     * @param  string  $text
     * @return string
     */
    function setEnv($key, $value)
    {
        $path     = base_path('.env');
        $contents = file($path);
        file_put_contents($path, '');
        $written  = 0;
        $tmpArray = [];
        foreach ($contents as $content)
        {
            //line contains the provided key
            if (strstr($content, $key))
            {
                //non empty line
                $written = 1;
                file_put_contents($path, "$key=$value" . PHP_EOL, FILE_APPEND);
            }
            else
            {
                file_put_contents($path, trim($content) . PHP_EOL, FILE_APPEND);
            }
        }
        if (!$written)
        {
            file_put_contents($path, "$key=$value" . PHP_EOL, FILE_APPEND);
        }
        return $value;
    }
}

if (!function_exists('ccencrypt'))
{
    /*
     * ccavenue ecrypt merchant data
     *
     */

    function ccencrypt($plainText, $key)
    {
        $secretKey  = cchextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $openMode   = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
        $blockSize  = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
        $plainPad   = ccpkcs5_pad($plainText, $blockSize);

        if (mcrypt_generic_init($openMode, $secretKey, $initVector) != -1)
        {
            $encryptedText = mcrypt_generic($openMode, $plainPad);
            mcrypt_generic_deinit($openMode);
        }
        return bin2hex($encryptedText);
    }
}

if (!function_exists('cchextobin'))
{
    /*
     * ccavenue hex to bin convertion
     *
     */
    function cchextobin($hexString)
    {
        $length    = strlen($hexString);
        $binString = "";
        $count     = 0;

        while ($count < $length)
        {
            $subString    = substr($hexString, $count, 2);
            $packedString = pack("H*", $subString);

            if ($count == 0)
            {
                $binString = $packedString;
            }
            else
            {
                $binString .= $packedString;
            }
            $count += 2;
        }
        return $binString;
    }
}

if (!function_exists('ccdecrypt'))
{
    /*
     * ccavenue decrypt merchant data
     *
     */
    function ccdecrypt($encryptedText, $key)
    {
        $secretKey     = cchextobin(md5($key));
        $initVector    = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $encryptedText = cchextobin($encryptedText);
        $openMode      = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');

        mcrypt_generic_init($openMode, $secretKey, $initVector);

        $decryptedText = mdecrypt_generic($openMode, $encryptedText);
        $decryptedText = rtrim($decryptedText, "\0");

        mcrypt_generic_deinit($openMode);

        return $decryptedText;
    }
}

if (!function_exists('ccpkcs5_pad'))
{
    /*
     * ccavenue method
     *
     */
    function ccpkcs5_pad($plainText, $blockSize)
    {
        $pad = $blockSize - (strlen($plainText) % $blockSize);
        return $plainText . str_repeat(chr($pad), $pad);
    }
}
