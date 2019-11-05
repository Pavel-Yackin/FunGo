<?php

namespace App\Http\Controllers;

use App\Check;
use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CheckController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $page = $request->get('page', 1);

        $data = Check::query()
            ->with(['user', 'partner'])
            ->where('user_id', '=', Auth::id())
            ->limit($this->perPage)
            ->offset(($page - 1) * $this->perPage)
            ->paginate();

        return $this->makeCollectionResponse($data);
    }

    /**
     * @param $partnerId
     * @return \Illuminate\Http\JsonResponse
     */
    public function one($id)
    {
        $partner = Check::query()
            ->where('id', $id)
            ->where('user_id', '=', Auth::id())
            ->with(['user', 'partner'])
            ->first();


        return response()->json(['object' => $partner ? $partner->toArray():[]], 200, ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Throwable
     */
    public function create(Request $request)
    {
        if ($request->hasFile('images')) {
            abort(400, "api|no_images");
        }

        $check = new Check();
        $check->status = Check::STATUS_CHECKING;
        $check->user_id = Auth::id();
        $check->partner_id = null;
        $check->offer_id = null;
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
