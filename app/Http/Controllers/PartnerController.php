<?php

namespace App\Http\Controllers;

use App\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartnerController extends Controller
{
    protected $longitude;
    protected $latitude;

    public function __construct()
    {
        $this->latitude = \request('latitude');
        $this->longitude = \request('longitude');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $page = $request->get('page', 1);

        $query = Partner::query()
            ->select()
            ->with(['partnerType'])
            ->limit($this->perPage)
            ->offset(($page - 1) * $this->perPage);

        if ($request->get('name')) {
            $query->where('name', 'like', '%'.$request->get('name').'%');
        }

        $sort = $request->get('sort');
        if ($sort == 'distance' && $this->latitude && $this->longitude) {
            $sf = pi()/180;
            $query->orderBy(DB::raw("ACOS(SIN(latitude*$sf)*SIN({$this->latitude}*$sf) + COS(latitude*$sf)*COS({$this->latitude}*$sf)*COS((longitude-{$this->longitude})*$sf))"));
        } elseif ($sort == 'cashback') {
            $query->leftJoin('offers', 'offers.partner_id', '=', 'partners.id');
            $query->orderBy('offers.value');
        }

        $data = $query->paginate();

        return $this->makeCollectionResponse($data);
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

        $partner->setHidden(Partner::SINGLE_HIDDEN);


        return response()->json(['data' => $partner ? $partner->toArray():[]], 200, ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }
}
