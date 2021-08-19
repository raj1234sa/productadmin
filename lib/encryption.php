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
            $cipher = "aes-256-ctr";
            if (in_array($cipher, openssl_get_cipher_methods())) {
                $iv = $this->encKey;
                $ciphertext = openssl_encrypt($value, $cipher, $this->encKey, 0, $iv);
                return $ciphertext;
            }
        }
    }

    public function decrypt($value) {
        if($this->encMethod == 'md5') {
            return '';
        } else {
            $cipher = "aes-256-ctr";
            if (in_array($cipher, openssl_get_cipher_methods())) {
                $iv = $this->encKey;
                $original_plaintext = openssl_decrypt($value, $cipher, $this->encKey, 0, $iv);
                return $original_plaintext;
            }
        }
    }
}

?>