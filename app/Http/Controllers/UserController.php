<?php

namespace App\Http\Controllers;

use App\Exceptions\User\ChangeUserPasswordException;
use App\Exceptions\User\UserException;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response as HttpCodes;

class UserController extends AuthAwareController
{
    /* Public endpoints */

    public function createUser(Request $request): Response
    {
        $userValidator = User::getValidatorForCreatePayload($request->json()->all());

        if ($userValidator->fails()) {
            return static::buildResponse(
                ['errors' => $userValidator->errors()->getMessageBag()->toArray()],
                HttpCodes::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            return static::buildResponse(
                User::registerUser($userValidator->validated())->toPublicList(),
                HttpCodes::HTTP_CREATED
            );
        } catch (UserException $ex) {
            return static::buildResponse($ex->getMessage(), HttpCodes::HTTP_BAD_REQUEST);
        }
    }

    public function verifyEmail(string $username, string $verificationHash): string
    {
        try {
            $success = User::completeRegistration($username, $verificationHash);
        } catch (\Exception $ex) {
            $success = false;
        } finally {
            return View::make('web.EmailVerificationResult', ['username' => $username, 'success' => $success]);
        }
    }

    /* Protected endpoints */

    public function viewUser(Request $request): Response
    {
        return static::buildResponse($request->user()->toPublicList());
    }

    public function editUser(Request $request): Response
    {
        $userValidator = User::getValidatorForEditPayload($request->json()->all());

        if ($userValidator->fails()) {
            return static::buildResponse(
                ['errors' => $userValidator->errors()->getMessageBag()->toArray()],
                HttpCodes::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        try {
            return static::buildResponse(
                User::editUser($userValidator->validated())->toPublicList(),
                HttpCodes::HTTP_OK
            );
        } catch (\Exception $ex) {
            return static::buildResponse($ex->getMessage(), HttpCodes::HTTP_BAD_REQUEST);
        }
    }

    public function changeUserPassword(Request $request): Response
    {
        try {
            return static::buildResponse(User::changeUserPassword($request->json()->all()), HttpCodes::HTTP_OK);
        } catch (ChangeUserPasswordException $ex) {
            return static::buildResponse($ex->getMessage(), HttpCodes::HTTP_BAD_REQUEST);
        }
    }
}
