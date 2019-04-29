<?php

namespace App\Http\Controllers;

use App\JobProcessLog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as HttpCodes;

class JobProcessLogController extends AuthAwareController
{
    public function getJobProcessLog(int $id): Response
    {
        return static::buildResponse(JobProcessLog::listByJobProcessId($id));
    }

    public function getJobProcessLogEntry(int $jobProcessId, int $jobProcessLogId): Response
    {
        return static::buildResponse(JobProcessLog::listByJobProcessLogId($jobProcessLogId));
    }

    public function createJobProcessLogEntry(Request $request): Response
    {
        $jobProcessLogValidator = JobProcessLog::getValidatorForCreatePayload($request->json()->all());

        if ($jobProcessLogValidator->fails()) {
            return static::buildResponse(
                ['errors' => $jobProcessLogValidator->errors()->getMessageBag()->toArray()],
                HttpCodes::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            return static::buildResponse(
                JobProcessLog::createJobProcessLogEntry($jobProcessLogValidator->validated())->toPublicList(),
                HttpCodes::HTTP_CREATED
            );
        } catch (\Exception $ex) {
            return static::buildResponse($ex->getMessage(), HttpCodes::HTTP_BAD_REQUEST);
        }
    }
}
