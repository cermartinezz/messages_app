<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Models\Message;
use App\Services\GuestMessageServices;
use Illuminate\Http\Request;

class MessageController extends ApiController
{

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $hasSession = $request->hasCookie('session');

        $session = $hasSession ? json_decode($request->cookie('session')) : null;

        $messages = Message::query()->with('response')->where('from', $session->id)->get();

        return $this->respondSuccess('messages', [
            'result' => [
                'message' => 'messages',
                'data' => $messages->toArray()
            ]
        ]);
    }

    /**
     * @throws \Throwable
     */
    public function store(MessageRequest $request, GuestMessageServices $messageServices): \Illuminate\Http\JsonResponse
    {
        $hasSession = $request->hasCookie('session');

        $session = $hasSession ? json_decode($request->cookie('session')) : null;

        $validated_data = $request->validated();

        $messages = $messageServices->createMessages($validated_data,$session);

        $data = array_map(function (Message $message) {
            return $message->load('response');
        }, $messages);


        return $this->respondCreated('Messages Sent',[
            'result' => [
                'message' => $data,
            ]
        ]);
    }
}
