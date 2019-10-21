<?php

namespace App\Http\Controllers;

use App\Check;
use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function create(Request $request)
    {
        $check = new Check();
        $check->status = Check::STATUS_CHECKING;
        // TODO add user
        $check->user_id = 1;
        $check->partner_id = $request->get('partner_id');
        $check->offer_id = $request->get('offer_id');
        $check->saveOrFail();

        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $index => $image) {
                $filename = $check->id . "-" . $index . "-" . uniqid() . "." . $image->extension();
                $path = "image/$filename";


                Storage::disk('admin')->put($path, $image->get());
                $image = new Image([
                    'object_id' => $check->id,
                    'object_type' => Image::TYPE_CHECK,
                    'type' => Image::TYPE_CHECK,
                    'path' => $path
                ]);

                $image->saveOrFail();
            }
        }

        return response()->json(['status' => 'success'], 200, ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }

}
