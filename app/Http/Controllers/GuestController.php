<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController as Controller;
use Illuminate\Support\Str;

class GuestController extends Controller
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $hasSession = $request->hasCookie('session');

        if(!$hasSession){
            $uuid = (string) Str::uuid();

            $data = [
                'id' => $uuid
            ];
            $cookie = cookie('session', json_encode($data), 60,'',$request->getHost());

            return $this->respondSuccess('session', $data,[],['cookie' => $cookie]);
        }

        $data = json_decode($request->cookie('session'));


        return $this->respondSuccess('session',['id' => $data->id]);

    }
}
