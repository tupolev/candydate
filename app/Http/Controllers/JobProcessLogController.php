<?php

namespace App\Http\Controllers;

use App\Models\JobProcessLog;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as HttpCodes;

class JobProcessLogController extends JsonController
{
    public function getJobProcessLog(int $id): Response
    {
        try {
            return static::buildResponse(JobProcessLog::listByJobProcessId($id));
        } catch (ModelNotFoundException $ex) {
            return static::buildResponse($ex->getMessage(), HttpCodes::HTTP_NOT_FOUND);
        }
    }

    public function getJobProcessLogEntry(int $jobProcessId, int $jobProcessLogId): Response
    {
        try {
            return static::buildResponse(JobProcessLog::listByJobProcessLogId($jobProcessLogId));
        } catch (ModelNotFoundException $ex) {
            return static::buildResponse($ex->getMessage(), HttpCodes::HTTP_NOT_FOUND);
        }
    }

    public function createJobProcessLogEntry(Request $request, int $jobProcessId): Response
    {
        try {
            return static::buildResponse(
                JobProcessLog::createJobProcessLogEntry($jobProcessId, $request->json()->all())->toPublicList(),
                HttpCodes::HTTP_CREATED
            );
        } catch (ValidationException $ex) {
            return static::buildResponse(
                ['errors' => $ex->validator->errors()->getMessageBag()->toArray()],
                HttpCodes::HTTP_UNPROCESSABLE_ENTITY
            );
        } catch (\Exception $ex) {
            return static::buildResponse($ex->getMessage(), HttpCodes::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $ex) {
            return static::buildResponse($ex->getMessage(), HttpCodes::HTTP_BAD_REQUEST);
        }
    }
}
