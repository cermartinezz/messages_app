<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response as StatusResponse;
use Illuminate\Support\Facades\Response;

class ApiController extends Controller
{
    protected $status_code = 200;

    public function respondCreated($message = "Record Created Successfully", $data = null, $headers = [], $options = [])
    {
        $result = [
            'message' => $message,
        ];

        $result = array_merge($result,$data);
        return $this->setStatusCode(StatusResponse::HTTP_CREATED)
            ->respond([
                'result'=>$result
            ],$headers, $options);
    }

    public function respondSuccess($message = "Success",$data = [], $headers = [], $options = [])
    {
        $result = [
            'message' => $message,
        ];

        $result = array_merge($result,$data);

        return $this->setStatusCode(StatusResponse::HTTP_OK)
            ->respond([
                'result'=>$result
            ],$headers, $options);
    }

    public function respond($data,$headers = [], $options = []): \Illuminate\Http\JsonResponse
    {
        $response = Response::json($data,$this->getStatusCode(),$headers);

        isset($options['cookie']) ? $response->withCookie($options['cookie']) : null;

        return $response;
    }

    public function getStatusCode(): int
    {
        return $this->status_code;
    }

    public function setStatusCode(int $status_code): static
    {
        $this->status_code = $status_code;

        return $this;
    }
}
