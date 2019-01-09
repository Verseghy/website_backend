<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colleagues;

class ColleaguesController extends Controller
{
    //
    public function listColleagues(Request $request)
    {
        $colleagues=Colleagues::orderBy('name')->get();

        $colleagues = array_map("self::_expandUrls", $colleagues->toArray());

        return $colleagues;
    }

    private static function _publicUrl($file, $disk='colleagues_images')
    {
        return asset(\Storage::disk($disk)->url($file));
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
