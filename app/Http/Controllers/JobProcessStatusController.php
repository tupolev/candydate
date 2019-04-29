<?php

namespace App\Http\Controllers;

use App\JobProcessLog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as HttpCodes;

class JobProcessStatusController extends AuthAwareController
{
    public function changeJobProcessStatus(Request $request, int $id): Response
    {
        $jobProcessStatusValidator = JobProcessLog::getValidatorForChangeStatusPayload($request->json()->all());

        if ($jobProcessStatusValidator->fails()) {
            return static::buildResponse(
                ['errors' => $jobProcessStatusValidator->errors()->getMessageBag()->toArray()],
                HttpCodes::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        try {
            return static::buildResponse(
                JobProcessLog::changeJobProcessStatus($id, $jobProcessStatusValidator->validated()['job_process_status_id']),
                HttpCodes::HTTP_CREATED
            );
        } catch (\Exception $ex) {
            return static::buildResponse($ex->getMessage(), HttpCodes::HTTP_BAD_REQUEST);
        }
    }
}
