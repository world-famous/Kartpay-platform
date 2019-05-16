<?php

namespace App\Classes\Gateways;

class CreditCardAvenue
{

    protected $working_key;
    protected $access_code;

    public function __construct()
    {
        $this->working_key = '6B960982747E90437E402FF1E0820F33';
        $this->access_code = 'AVBV05CG51AF62VBFA';
    }

    private function encrypt($plainText, $key)
    {
        $secretKey  = hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $openMode   = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');
        $blockSize  = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
        $plainPad   = pkcs5_pad($plainText, $blockSize);

        if (mcrypt_generic_init($openMode, $secretKey, $initVector) != -1) {

            $encryptedText = mcrypt_generic($openMode, $plainPad);

            mcrypt_generic_deinit($openMode);

        }

        return bin2hex($encryptedText);
    }

    private function decrypt($encryptedText, $key)
    {
        $secretKey     = hextobin(md5($key));
        $initVector    = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $encryptedText = hextobin($encryptedText);
        $openMode      = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', 'cbc', '');

        mcrypt_generic_init($openMode, $secretKey, $initVector);

        $decryptedText = mdecrypt_generic($openMode, $encryptedText);
        $decryptedText = rtrim($decryptedText, "\0");

        mcrypt_generic_deinit($openMode);

        return $decryptedText;
    }

    private function hextobin($hexString)
    {
        $length    = strlen($hexString);
        $binString = "";
        $count     = 0;

        while ($count < $length) {

            $subString    = substr($hexString, $count, 2);
            $packedString = pack("H*", $subString);

            if ($count == 0) {
                $binString = $packedString;
            } else {
                $binString .= $packedString;
            }

            $count += 2;
        }

        return $binString;
    }
}
