@php
function public_url($disk, $path = '')
{
    $fs = \Storage::disk($disk);

    if ($fs->getAdapter() instanceof \League\Flysystem\Adapter\Local) {
        return asset($fs->url($path));
    }

    return $fs->url($path);
}


function is_valid_url($value)
{
    // URL validitation regex from https://stackoverflow.com/questions/206059/php-validation-regex-for-url
    $pattern = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";

    return preg_match($pattern, $value);
}
@endphp
