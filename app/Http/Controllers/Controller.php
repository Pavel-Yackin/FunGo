<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $perPage = 15;
    public $hideFields = true;

    public function __construct()
    {
        $this->perPage = request()->get('per-page', $this->perPage);
    }

    public function makeCollectionResponse($data)
    {
        return response()->json([
            'data' => $data->items(),
            'pagination' => [
                'total' => $data->total(),
                'count' => $data->count(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'total_pages' => $data->lastPage(),
            ],
        ], 200, ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }
}
