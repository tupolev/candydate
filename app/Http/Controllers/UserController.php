<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as HttpCodes;

class UserController extends JsonController
{
    public function createUser(Request $request): Response
    {
        try {
            $user = User::registerUser($request->json()->all());
            $client = $user->createClientCredentials();
            $clientData = [
                'name' => $client->name,
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'redirect' => $client->redirect,
                'password_client' => $client->password_client,
                'personal_access_client' => $client->personal_access_client,
                'revoked' => $client->revoked,
                'created_at' => $client->created_at,
            ];
            return static::buildResponse(['user' => $user->toPublicList(), 'client' => $clientData], HttpCodes::HTTP_CREATED);
        } catch (ValidationException $ex) {
            return static::buildResponse(
                ['errors' => $ex->validator->errors()->getMessageBag()->toArray()],
                HttpCodes::HTTP_UNPROCESSABLE_ENTITY
            );
        } catch (ModelNotFoundException $ex) {
            return static::buildResponse($ex->getMessage(), HttpCodes::HTTP_NOT_FOUND);
        } catch (\Exception $ex) {
            return static::buildResponse($ex->getMessage(), HttpCodes::HTTP_INTERNAL_SERVER_ERROR);
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

    public function viewUser(Request $request): Response
    {
        return static::buildResponse($request->user()->toPublicList());
    }

    public function editUser(Request $request): Response
    {
        try {
            return static::buildResponse(
                User::editUser(Auth::user()->id, $request->json()->all())->toPublicList(), HttpCodes::HTTP_OK);
        } catch (ValidationException $ex) {
            return static::buildResponse(
                ['errors' => $ex->validator->errors()->getMessageBag()->toArray()],
                HttpCodes::HTTP_UNPROCESSABLE_ENTITY
            );
        } catch (ModelNotFoundException $ex) {
            return static::buildResponse($ex->getMessage(), HttpCodes::HTTP_NOT_FOUND);
        } catch (\Exception $ex) {
            return static::buildResponse($ex->getMessage(), HttpCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function changeUserPassword(Request $request): Response
    {
        try {
            return static::buildResponse(User::changeUserPassword(Auth::user()->id, $request->json()->all()), HttpCodes::HTTP_OK);
        } catch (ValidationException $ex) {
            return static::buildResponse($ex->validator->errors()->getMessageBag()->get('password'), HttpCodes::HTTP_UNPROCESSABLE_ENTITY);
        } catch (ModelNotFoundException $ex) {
            return static::buildResponse($ex->getMessage(), HttpCodes::HTTP_NOT_FOUND);
        } catch (\Exception $ex) {
            return static::buildResponse($ex->getMessage(), HttpCodes::HTTP_BAD_REQUEST);
        }
    }
}
