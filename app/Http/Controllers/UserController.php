<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends JsonController
{
    public function me(Request $request)
    {
        return static::buildResponse($request->user()->toPublicList());
    }

    public function create(Request $request)
    {
        return ['id' => 1];
    }

    public function edit($id)
    {
        return ['id' => 1];
    }
}
