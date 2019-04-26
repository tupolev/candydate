<?php


namespace App\Http\Controllers;


use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Controller;

class JsonController extends Controller
{
    protected static function buildResponse($content = [], int $status = 200): Response
    {
        return response(json_encode($content), $status, ['Content-type' => 'application/json']);
    }

    protected static function buildUnauthorizedResponse(): Response
    {
        return response(null, Response::HTTP_UNAUTHORIZED, ['Content-type' => 'application/json']);
    }
}
