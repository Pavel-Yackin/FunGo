<?php

namespace App\Http\Controllers;

use App\Check;
use Illuminate\Http\Request;

class CheckController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = 10;
        $page = $request->get('page', 1);

        $data = Check::query()
            ->with(['user', 'partner'])
            ->limit($perPage)
            ->offset(($page - 1) * $perPage)
            ->get();

        return response()->json(['objects' => $data->toArray()], 200, ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param $partnerId
     * @return \Illuminate\Http\JsonResponse
     */
    public function one($id)
    {
        $partner = Check::query()
            ->where('id', $id)
            ->with(['user', 'partner'])
            ->first();


        return response()->json(['object' => $partner ? $partner->toArray():[]], 200, ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }
}
