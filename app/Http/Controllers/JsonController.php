<?php

namespace App\Http\Controllers;

use App\Traits\Http\JsonResponseTrait;
use Laravel\Lumen\Routing\Controller;

class JsonController extends Controller
{
    use JsonResponseTrait;
}
