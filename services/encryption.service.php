<?php

class EncryptionService
{
    private $cipher = 'aes-128-gcm';

    public function __construct(private ConfigService $configService)
    {
    }

    public function encrypt($input)
    {
        $secret = $this->configService->getConfig('encryptionSecret');
        $ivLength = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($ivLength);

        $encrypted = openssl_encrypt($input, $this->cipher, hex2bin($secret), OPENSSL_RAW_DATA, $iv, $tag);

        return base64_encode($iv . $tag . $encrypted);
    }

    public function decrypt($input)
    {
        $input = base64_decode($input);

        $secret = $this->configService->getConfig('encryptionSecret');
        $ivLength = openssl_cipher_iv_length($this->cipher);

        $iv = substr($input, 0, $ivLength);
        $tag = substr($input, $ivLength, 16);
        $encrypted = substr($input, $ivLength + 16);

        $decrypted = openssl_decrypt($encrypted, $this->cipher, hex2bin($secret),  OPENSSL_RAW_DATA, $iv, $tag);

        return $decrypted;
    }
}
