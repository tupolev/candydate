<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

class TestController extends Controller
{
    public function index()
    {
        return ['id' => 1];
    }
}
