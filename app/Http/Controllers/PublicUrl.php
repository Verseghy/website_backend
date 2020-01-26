<?php

namespace App\Http\Controllers;

/**
 * @codeCoverageIgnore
 */
trait PublicUrl
{
    protected static function _publicUrl($file, $disk = self::DISK ? self::DISK : 'public')
    {
        return asset(\Storage::disk($disk)->url($file));
    }
}
