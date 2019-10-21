<?php

namespace App\Http\Controllers;

use App\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = 10;
        $page = $request->get('page', 1);

        $data = Partner::query()
            ->with(['partnerType'])
            ->limit($perPage)
            ->offset(($page - 1) * $perPage)
            ->get();

        return response()->json(['objects' => $data->toArray()], 200, ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param $partnerId
     * @return \Illuminate\Http\JsonResponse
     */
    public function one($partnerId)
    {
        $partner = Partner::query()
            ->where('id', $partnerId)
            ->with(['partnerType', 'partnerOptions', 'images', 'offers'])
            ->first();


        return response()->json(['object' => $partner ? $partner->toArray():[]], 200, ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }
}
