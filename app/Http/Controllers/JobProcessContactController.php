<?php

namespace App\Http\Controllers;

use App\Exceptions\JobProcessContact\JobProcessContactException;
use App\JobProcessContact;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as HttpCodes;

class JobProcessContactController extends JsonController
{
    public function listJobProcessContacts(Request $request, int $id): Response
    {
        return static::buildResponse(JobProcessContact::listByProcessId($id));
    }

    public function viewJobProcessContact(Request $request, int $id): Response
    {
        return static::buildResponse(JobProcessContact::query()->findOrFail($id)->first()->toPublicList());
    }

    public function createJobProcessContact(Request $request, int $id): Response
    {
        $jobProcessContactValidator = JobProcessContact::getValidatorForCreatePayload($request->json()->all());

        if ($jobProcessContactValidator->fails()) {
            return static::buildResponse(
                ['errors' => $jobProcessContactValidator->errors()->getMessageBag()->toArray()],
                HttpCodes::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            return static::buildResponse(
                JobProcessContact::createJobProcessContact($id, $jobProcessContactValidator->validated())->toPublicList(),
                HttpCodes::HTTP_CREATED
            );
        } catch (\Exception $ex) {
            return static::buildResponse($ex->getMessage(), HttpCodes::HTTP_BAD_REQUEST);
        }
    }

    public function editJobProcessContact(Request $request, int $id): Response
    {
        $jobProcessContactValidator = JobProcessContact::getValidatorForEditPayload($request->json()->all());

        if ($jobProcessContactValidator->fails()) {
            return static::buildResponse(
                ['errors' => $jobProcessContactValidator->errors()->getMessageBag()->toArray()],
                HttpCodes::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            return static::buildResponse(
                JobProcessContact::editJobProcessContact($id, $jobProcessContactValidator->validated())->toPublicList(),
                HttpCodes::HTTP_CREATED
            );
        } catch (JobProcessContactException $ex) {
            return static::buildResponse($ex->getMessage(), HttpCodes::HTTP_BAD_REQUEST);
        }
    }

    public function deleteJobProcessContact(Request $request, int $id): Response
    {
        try {
            return static::buildResponse(JobProcessContact::deleteJobProcessContact($id), HttpCodes::HTTP_OK);
        } catch (\Exception $ex) {
            return static::buildResponse($ex->getMessage(), HttpCodes::HTTP_BAD_REQUEST);
        }
    }
}
