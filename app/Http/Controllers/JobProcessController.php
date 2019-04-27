<?php

namespace App\Http\Controllers;

use App\Exceptions\JobProcess\JobProcessCreateException;
use App\JobProcess;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as HttpCodes;

class JobProcessController extends JsonController
{
    public function listJobProcesses(Request $request): Response
    {
        return static::buildResponse(JobProcess::listByUserId($request->user()->id));
    }

    public function viewJobProcess(int $id): Response
    {
        return static::buildResponse(JobProcess::query()->findOrFail($id)->first()->toPublicList());
    }

    public function createJobProcess(Request $request): Response
    {
        $jobProcessValidator = JobProcess::getValidatorForCreatePayload($request->json()->all());

        if ($jobProcessValidator->fails()) {
            return static::buildResponse(
                ['errors' => $jobProcessValidator->errors()->getMessageBag()->toArray()],
                HttpCodes::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            return static::buildResponse(
                JobProcess::createJobProcess($jobProcessValidator->validated())->toPublicList(),
                HttpCodes::HTTP_CREATED
            );
        } catch (JobProcessCreateException $ex) {
            return static::buildResponse($ex->getMessage(), HttpCodes::HTTP_BAD_REQUEST);
        }
    }

    public function editJobProcess(int $id, Request $request): Response
    {
        $jobProcessValidator = JobProcess::getValidatorForEditPayload($request->json()->all());

        if ($jobProcessValidator->fails()) {
            return static::buildResponse(
                ['errors' => $jobProcessValidator->errors()->getMessageBag()->toArray()],
                HttpCodes::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            return static::buildResponse(
                JobProcess::editJobProcess($id, $jobProcessValidator->validated())->toPublicList(),
                HttpCodes::HTTP_CREATED
            );
        } catch (JobProcessCreateException $ex) {
            return static::buildResponse($ex->getMessage(), HttpCodes::HTTP_BAD_REQUEST);
        }
    }

    public function deleteJobProcess(int $id): Response
    {
        return static::buildResponse(JobProcess::deleteProcess($id));
    }
}
