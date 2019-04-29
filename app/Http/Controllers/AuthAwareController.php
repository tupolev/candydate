<?php


namespace App\Http\Controllers;


use App\Traits\Http\JsonResponseTrait;
use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Controller;

class AuthAwareController extends Controller
{
    use JsonResponseTrait;
}
