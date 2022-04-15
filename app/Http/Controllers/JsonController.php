<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExampleResponses;
use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JsonController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function newJson(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'json' => 'required|array'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Your JSON is not valid...!'], 400);
        }
        if (Response::isDuplicate()) {
            $response = Response::ofJson();
        } else {
            $response = new Response();
            $response->set();
        }

        $url = url("/show-json/$response->url");

        return response()->json(['url' => $url], 200);
    }


    /**
     * @param Response $json
     * @return \Illuminate\Http\JsonResponse
     */
    public function showJson(Response $json)
    {
        $json->increment('usage', 1);
        return response()->json($json->response);
    }

    public function examples()
    {
        $responses = Response::skip(request('start'))
            ->where(function ($query) {
                if (request('search')['value'])
                    return $query->where('title', 'LIKE', '%' . request('search')['value'] . '%');
            })
            ->get();
        return response()->json(['data' => ExampleResponses::collection($responses), 'recordsFiltered' => $responses->count()]);
    }
}
