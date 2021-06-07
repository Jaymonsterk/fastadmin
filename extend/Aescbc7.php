<?php

class Aescbc7
{
    public static function decrypt($sStr, $key, $iv)
    {
        $data = openssl_decrypt(base64_decode($sStr), 'aes-128-cbc', $key, true, $iv);

        return $data;
    }

    public static function encrypt($sStr, $key, $iv)
    {
        $data = base64_encode(openssl_encrypt($sStr, 'aes-128-cbc', $key, true, $iv));

        return $data;
    }

    public static function decryptBySecret($sStr, $secret)
    {
        $key = substr($secret, 0, 16);
        $iv = substr($secret, 16);

        return AesCBC7::decrypt($sStr, $key, $iv);
    }

    public static function encryptBySecret($sStr, $secret)
    {
        $key = substr($secret, 0, 16);
        $iv = substr($secret, 16);

        return AesCBC7::encrypt($sStr, $key, $iv);
    }
}

?>