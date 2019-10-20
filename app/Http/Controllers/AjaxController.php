<?php

namespace App\Http\Controllers;

use App\Partner;

class AjaxController extends Controller
{
    public function partnerOffers($partnerId)
    {
        $partner = Partner::query()
            ->where('id', $partnerId)
            ->first();

        return response()->json($partner->offers ?? [],
            200,
            ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE);
    }
}
