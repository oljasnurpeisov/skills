<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class RegionTree extends Model
{
    /**
     * Справочник регионов
     */
    public static function getSprUoz($lang, $cod = null)
    {
        $length = is_null($cod) ? 2 : 5;
        $field = $lang == 'ru' ? 'NAME_KR_R' : 'NAME_KAZ';

        $db = DB::table('clcz')
            ->where(DB::raw('length(trim(CODUOZ))'), '=', $length)
            ->where(DB::raw('trim(CODUOZ)'), 'not like', '%000')
            ->where(DB::raw('trim(CODUOZ)'), 'like', $cod . '%')
            ->orderBy(DB::raw("trim(REPLACE(" . $field . ", 'г.', ''))"));

        $selectable = (!request()->get('selectable') && request()->get('selectable') == '1');

        if (!is_null($cod) and $selectable)
        {
            if ($cod == '01' or $cod == '10' or $cod == '79')
            {
                $db->where(DB::raw('trim(CODUOZ)'), 'not like', '__100');
            }
        }

        $rows = $db->get([
            DB::raw('trim(CODUOZ) as cod'),
            DB::raw("trim(REPLACE(" . $field . ", 'г.', '')) as caption"),
            'te'
        ])->toArray();

        $step = 1;
        $lists = array();

        foreach($rows as $key => $row)
        {
            $row->isFolder = is_null($cod) ? 1 : 0;
            $row->isLast = (count($rows) == $step) ? 1 : 0;
            $row->isFirst = ($step == 1) ? 1 : 0;
            $lists[] = $row;
            $step++;
        }
//
//        if (is_null($cod))
//        {
//            $orderBy = [12, 3, 16, 0, 1, 2, 4, 5, 6, 7, 8, 9, 10, 11, 13, 14, 15];
//            $rows = array_replace(array_flip($orderBy), $lists);
//        }

        return $rows;
    }
}
