<?php

class Encryption {
    private $encKey;
    private $cipher;
    private $encIv;

    public function __construct() {
        $this->encKey = 'PA-KEYOPENSSL';
        $this->encIv = 'PA-IVOPENSSL';

        $this->cipher = 'aes-256-ctr';
    }
    public function encrypt($value) {
        if (in_array($this->cipher, openssl_get_cipher_methods())) {
            $iv = $this->encIv;
            $ciphertext = openssl_encrypt($value, $this->cipher, $this->encKey, 0, $iv);
            return $ciphertext;
        }
    }

    public function decrypt($value) {
        if (in_array($this->cipher, openssl_get_cipher_methods())) {
            $iv = $this->encIv;
            $original_plaintext = openssl_decrypt($value, $this->cipher, $this->encKey, 0, $iv);
            return $original_plaintext;
        }
    }

    public function encryptPassword($password) {
        global $SITE_VAR_ENCRYPTION_METHOD;
        if($SITE_VAR_ENCRYPTION_METHOD == 'md5') {
            return md5($password.$this->encKey);
        } else {
            $this->encrypt($password);
        }
    }
}

?>