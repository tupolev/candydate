<?php

namespace App\Http\Controllers;

use App\Exceptions\JobProcessContact\JobProcessContactException;
use App\Models\JobProcessContact;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as HttpCodes;

class JobProcessContactController extends JsonController
{
    public function listJobProcessContacts(Request $request, int $jobProcessId): Response
    {
        return static::buildResponse(JobProcessContact::listByProcessId($jobProcessId));
    }

    public function viewJobProcessContact(Request $request, int $jobProcessId, int $jobProcessContactId): Response
    {
        return static::buildResponse(JobProcessContact::viewJobProcessContact($jobProcessContactId));
    }

    public function createJobProcessContact(Request $request, int $jobProcessId): Response
    {
        try {
            return static::buildResponse(
                JobProcessContact::createJobProcessContact($jobProcessId, $request->json()->all())->toPublicList(),
                HttpCodes::HTTP_CREATED
            );
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

    public function editJobProcessContact(Request $request, int $jobProcessId, int $jobProcessContactId): Response
    {
        try {
            return static::buildResponse(
                JobProcessContact::editJobProcessContact($jobProcessId, $jobProcessContactId, $request->json()->all())->toPublicList(),
                HttpCodes::HTTP_CREATED
            );
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

    public function deleteJobProcessContact(Request $request, int $jobProcessId, int $jobProcessContactId): Response
    {
        try {
            JobProcessContact::deleteJobProcessContact($jobProcessContactId);

            return static::buildResponse(null, HttpCodes::HTTP_OK);
        } catch (\Exception $ex) {
            return static::buildResponse($ex->getMessage(), HttpCodes::HTTP_BAD_REQUEST);
        }
    }
}
