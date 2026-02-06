<?php

namespace App\Services;

class PromptPayService
{
    public static function generatePayload($total)
    {
        $promptpay_id = "0907300691"; // ใส่เบอร์รับเงินของคุณตรงนี้
        $amount = number_format($total, 2, '.', '');
        
        $target = str_replace('-', '', $promptpay_id);
        $targetType = strlen($target) >= 13 ? "02" : "01";
        if ($targetType == "01") {
            $target = "0066" . substr($target, 1);
        }

        $payload = "00020101021229370016A000000677010111" . 
                   $targetType . str_pad(strlen($target), 2, '0', STR_PAD_LEFT) . $target . 
                   "5802TH530376454" . str_pad(strlen($amount), 2, '0', STR_PAD_LEFT) . $amount . 
                   "6304";
        
        return $payload . self::crc16($payload);
    }

    private static function crc16($data) {
        $crc = 0xFFFF;
        for ($i = 0; $i < strlen($data); $i++) {
            $x = (($crc >> 8) ^ ord($data[$i])) & 0xFF;
            $x ^= $x >> 4;
            $crc = (($crc << 8) ^ ($x << 12) ^ ($x << 5) ^ $x) & 0xFFFF;
        }
        return strtoupper(str_pad(dechex($crc & 0xFFFF), 4, '0', STR_PAD_LEFT));
    }
}