<?php

namespace App\Http\Controllers;

use App\Exceptions\UserRegisterException;
use App\JobProcess;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JobProcessController extends JsonController
{
    public function listJobProcesses(Request $request): string
    {
        return static::buildResponse(JobProcess::listByUserId($request->user()->id));
    }

    public function viewJobProcess(int $id): string
    {
    }

    public function createJobProcess(Request $request): string
    {
        $userValidator = Validator::make(
            $request->json()->all(),
            [
                'username' => 'bail|required|unique:users|string|max:25|min:4|alpha_dash',
                'password' => 'bail|required|string|max:16|min:6',
                'fullname' => 'bail|required|string|max:128|min:4',
                'email' => 'bail|required|string|unique:users,email|email|max:128',
                'language_id' => 'bail|required|integer|exists:languages,id',
            ]
        );

        if ($userValidator->fails()) {
            return static::buildResponse(['errors' => $userValidator->errors()->getMessageBag()->toArray()], 422);
        }

        try {
            return static::buildResponse(User::registerUser($userValidator->validated())->toPublicList(), 201);
        } catch (UserRegisterException $ex) {
            return static::buildResponse($ex->getMessage(), 400);
        }
    }

    public function editJobProcess(int $id, Request $request): string
    {
    }

    public function deleteJobProcess(int $id): string
    {
    }
}
