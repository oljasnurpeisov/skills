<?php

namespace App\Http\Controllers\App\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Models\RegionTree;
use \App\Models\Kato;

class DictionaryController extends Controller
{
    public function getKatoChilds($lang, $code) {
        switch ($lang) {
            case 'ru':
                $name = 'rus_name';
                break;

            default:
                $name = 'kaz_name';
                break;
        }
        $query = Kato::select('te AS id', "${name} AS name")->where('coduoz', $code);

        if (!in_array($code, ['01100', '79100', '10100'])) {
            $query->whereRaw('NOT ((hij = "000") AND (ef <> "10"))');
        }

        $result = $query->get();

        return response()->json([
            'data' => $result,
        ], 200);
    }

    public function getRegions($lang)
    {
        $regions = RegionTree::getSprUoz($lang);

        foreach ($regions as $region) {
            $region->childs = RegionTree::getSprUoz($lang, $region->cod);
        }

        return view('app.layout.default.components.regiontree', compact('regions'));
    }
}
