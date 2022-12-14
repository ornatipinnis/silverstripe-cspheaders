<?php

namespace ornatipinnis\Extensions;

use SilverStripe\Core\Config\Config;
use SilverStripe\ORM\DataExtension;

class CSPHeaders extends DataExtension {

    private static $CSPArray = [];
    public static $ApplyNonce = 'script-src';

    public function onBeforeInit() {
        $CSPArr = Config::inst()->get('ornatipinnis\Extensions\CSPHeaders', 'CSPArray');
        if (!empty($CSPArr) && is_array($CSPArr)) {
            $CSPString = '';
            foreach ($CSPArr as $k => $v) {
                foreach ($v as $ks => $vs) {
                    $CSPString .= $ks . ' ';
                    if (!empty(self::$ApplyNonce) && self::$ApplyNonce == $ks) {
                        $CSPString .= "'nonce-" . $this->owner->StoredNonce() . "' ";
                    }
                    $CSPString .= @implode(' ', $vs);
                    $CSPString .= '; ';
                }
            }
            $this->owner->getResponse()->addHeader('Content-Security-Policy', $CSPString);
        }
    }

    protected function getNonce(int $length = 16): string {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size = $length - $len;

            $bytes = random_bytes($size);

            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }

    /**
     * Store the nonce in a static var so it can it be called by both the template and the CSP directive
     * but still last only for this instance (page load).
     *
     * @return string|null
     */
    public function StoredNonce() {
        static $nonce = null;

        if ($nonce === null) {
            $nonce = $this->getNonce();
        }
        return $nonce;
    }

}
