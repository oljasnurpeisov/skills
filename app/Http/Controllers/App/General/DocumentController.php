<?php

namespace App\Http\Controllers\App\General;

use App\Models\AVR;
use App\Models\Contract;
use App\Models\Document;
use Illuminate\Http\Request;

/**
 * Class DocumentController
 * @package App\Http\Controllers\App\General
 */
class DocumentController
{
    /**
     * Verify document by unique number
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function verify(Request $request)
    {
        $number = $request->get('number');
        $document = Document::where('number', $number)->firstOrFail();

        switch ($document->type_id) {
            case 2:
                $model = AVR::where('document_id', $document->id)->first();
                break;
            default:
                $model = Contract::where('document_id', $document->id)->first();
                break;
        }

        return view('app.pages.general.documents.verify', [
            'number' => $number,
            'document' => $document,
            'model' => $model
        ]);
    }
}
