<?php

namespace App\Http\Controllers;

use App\Exceptions\JobProcessCreateException;
use App\JobProcess;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpCodes;

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

    public function editJobProcess(int $id, Request $request): string
    {
    }

    public function deleteJobProcess(int $id): string
    {
    }
}
