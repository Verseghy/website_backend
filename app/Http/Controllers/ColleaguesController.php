<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colleagues;

class ColleaguesController extends Controller
{
    use PublicUrl;
    const DISK = 'colleagues_images';

    public function listColleagues(Request $request)
    {
        $colleagues = Colleagues::orderBy('name')->get();
        $colleagues = $colleagues->toArray();
        usort($colleagues, function ($item1, $item2) {
            return preg_replace('/^Dr\. /', '', $item1['name']) <=> preg_replace('/^Dr\. /', '', $item2['name']);
        });

        $colleagues = array_map('self::_expandUrls', $colleagues);

        return $colleagues;
    }

    private static function _expandUrls($colleague)
    {
        if ($colleague instanceof Colleagues) {
            $colleague = $colleague->toArray();
        }
        assert(is_array($colleague));

        $colleague['image'] = isset($colleague['image']) ? self::_publicUrl($colleague['image']) : null;

        return $colleague;
    }
}
