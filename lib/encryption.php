<?php

class Encryption {
    private $encMethod;
    private $encKey;

    public function __construct() {
        global $SITE_VAR_ENCRYPTION_METHOD, $SITE_VAR_ENCRYPTION_KEY;
        $this->encMethod = $SITE_VAR_ENCRYPTION_METHOD;
        $this->encKey = $SITE_VAR_ENCRYPTION_KEY;
    }
    public function encrypt($value) {
        if($this->encMethod == 'md5') {
            return md5($value.$this->encKey);
        } else {
            $cipher = "aes-128-gcm";
            if (in_array($cipher, openssl_get_cipher_methods())) {
                $ivlen = openssl_cipher_iv_length($cipher);
                $iv = openssl_random_pseudo_bytes($ivlen);
                $ciphertext = openssl_encrypt($value, $cipher, $this->encKey, $options=0, $iv);
                return $ciphertext;
            }
        }
    }

    public function decrypt($value) {
        if($this->encMethod == 'md5') {
            return '';
        } else {
            $cipher = "aes-128-gcm";
            if (in_array($cipher, openssl_get_cipher_methods())) {
                $ivlen = openssl_cipher_iv_length($cipher);
                $iv = openssl_random_pseudo_bytes($ivlen);
                $original_plaintext = openssl_decrypt($value, $cipher, $this->encKey, $options=0, $iv);
                return $original_plaintext;
            }
        }
    }
}

?>