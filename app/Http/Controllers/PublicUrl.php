<?php

namespace App\Http\Controllers;

/**
 * @codeCoverageIgnore
 */
trait PublicUrl
{
    private static function _publicUrl($file, $disk = self::DISK)
    {
        return asset(\Storage::disk($disk)->url($file));
    }
}
