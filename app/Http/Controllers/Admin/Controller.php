<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Buffet;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(Request $request)
    {
        $date = strtotime(date('d.m.Y').' +1 days');
        $payments['success'] = Buffet::getPayments(date('d.m.Y', $date), 'days', 7, true);
        $payments['all'] = Buffet::getPayments(date('d.m.Y', $date), 'days', 7, null);
        $purchases = Buffet::getPurchases(date('d.m.Y', $date), 'days', 7);


        return view('admin.v1.index', [
            'purchases' => $purchases,
            'payments' => $payments,
        ]);
    }
}
