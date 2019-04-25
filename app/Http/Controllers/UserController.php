<?php

namespace App\Http\Controllers;

use App\Exceptions\UserRegisterException;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class UserController extends JsonController
{
    public function me(Request $request)
    {
        return static::buildResponse($request->user()->toPublicList());
    }

    public function create(Request $request): string
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

    public function edit(Request $request): string
    {
        $userValidator = Validator::make(
            $request->json()->all(),
            [
                'id' => 'bail|required|integer|unique:users|exists:users,id',
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

    public function verify(string $username, string $verificationHash): string
    {
        try {
            $success = User::completeRegistration($username, $verificationHash);
        } catch (\Exception|\ErrorException $ex) {
            $success = false;
        } finally {
            return View::make('web.EmailVerificationResult', ['username' => $username, 'success' => $success]);
        }
    }
}
